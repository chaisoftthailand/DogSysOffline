// ✅ บันทึกข้อมูลสุนัขแบบ Offline
function saveDogOffline(form) {
    const formData = new FormData(form);

    const dog = {
        id: formData.get('dog_id'),
        action: formData.get('dog_id') ? 'update' : 'insert',
        data: {
            clinic_id: formData.get('clinic_id'),
            dog_name: formData.get('dog_name'),
            dog_breed: formData.get('dog_breed'),
            dog_age: formData.get('dog_age'),
            dog_weight: formData.get('dog_weight'),
            dog_gender: formData.get('dog_gender'),
            dog_medical_history: formData.get('dog_medical_history'),
            user_id: formData.get('user_id'),
            dog_image_base64: null,
            xray_image_base64: null
        },
        timestamp: Date.now()
    };

    // แปลงภาพเป็น base64 ถ้ามีภาพอัปโหลด
    const dogImg = form.querySelector('input[name="dog_image"]').files[0];
    const xrayImg = form.querySelector('input[name="xray_image"]').files[0];

    const readerPromises = [];

    if (dogImg) {
        readerPromises.push(readFileAsBase64(dogImg).then(base64 => dog.data.dog_image_base64 = base64));
    }
    if (xrayImg) {
        readerPromises.push(readFileAsBase64(xrayImg).then(base64 => dog.data.xray_image_base64 = base64));
    }

    Promise.all(readerPromises).then(() => {
        const queue = JSON.parse(localStorage.getItem('offline_dogs')) || [];
        queue.push(dog);
        localStorage.setItem('offline_dogs', JSON.stringify(queue));

        alert('✅ ข้อมูลถูกบันทึกแบบออฟไลน์แล้ว\nระบบจะ Sync อัตโนมัติเมื่อมีอินเทอร์เน็ต (ภายใน 1 นาที)');
        form.reset();
    });
}

function readFileAsBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
        reader.readAsDataURL(file);
    });
}

// ✅ Sync อัตโนมัติเมื่อออนไลน์
async function syncDogQueue() {
    if (!navigator.onLine) return;
    const queue = JSON.parse(localStorage.getItem('offline_dogs')) || [];
    const now = Date.now();
    const freshQueue = queue.filter(entry => now - entry.timestamp <= 60000); // 1 นาที
    if (freshQueue.length === 0) return;

    try {
        const res = await fetch('dog_update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(freshQueue)
        });
        const result = await res.json();
        if (result.status === 'success') {
            const remaining = queue.filter(entry => now - entry.timestamp > 60000);
            localStorage.setItem('offline_dogs', JSON.stringify(remaining));
            alert('✅ ข้อมูลที่ค้างไว้ถูก Sync เรียบร้อยแล้ว');
            location.reload();
        }
    } catch (err) {
        console.error('❌ การ Sync ข้อมูลสุนัขล้มเหลว:', err);
    }
}

// ✅ เรียกใช้เมื่อโหลดหน้าและกลับมาออนไลน์
window.addEventListener('online', syncDogQueue);
window.addEventListener('load', syncDogQueue);

// ✅ ฟังก์ชันใช้กับ onsubmit="return handleSubmit(...)"
function handleSubmit(event, form) {
    if (!navigator.onLine) {
        event.preventDefault();
        saveDogOffline(form);
        return false;
    }
    return true;
}
