// ✅ ฟังก์ชันบันทึกข้อมูลการนัดหมายแบบ Offline
function saveAppointmentOffline(form) {
    const formData = new FormData(form);
    const appointment = {
        id: formData.get('appointment_id'),
        action: formData.get('appointment_id') ? 'update' : 'insert',
        data: {
            dog_id: formData.get('dog_id'),
            clinic_id: formData.get('clinic_id'),
            appointment_date: formData.get('appointment_date'),
            description: formData.get('description')
        },
        timestamp: Date.now()
    };

    let appointments = JSON.parse(localStorage.getItem('offline_appointment')) || [];
    appointments.push(appointment);
    localStorage.setItem('offline_appointment', JSON.stringify(appointments));

    alert("✅ บันทึกข้อมูลการนัดหมายแบบ Offline แล้ว\nระบบจะ Sync อัตโนมัติเมื่อมีอินเทอร์เน็ต (เฉพาะใน 1 นาที)");
    form.reset();
}

// ✅ ฟังก์ชันจัดการตอนกด Submit ฟอร์ม
function handleAppointmentSubmit(event, form) {
    if (!navigator.onLine) {
        event.preventDefault();
        saveAppointmentOffline(form);
        return false;
    }
    return true;
}

// ✅ ฟังก์ชัน Sync ข้อมูลออฟไลน์ขึ้นเซิร์ฟเวอร์
async function syncOfflineAppointment() {
    if (!navigator.onLine) return;

    const offlineData = JSON.parse(localStorage.getItem('offline_appointment')) || [];
    const now = Date.now();
    const MAX_AGE = 60 * 1000; // 1 นาที

    const freshData = offlineData.filter(entry => now - entry.timestamp <= MAX_AGE);
    if (freshData.length === 0) return;

    try {
        const response = await fetch("appointment_register.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(freshData)
        });

        const result = await response.json();
        if (result.status === "success") {
            const remaining = offlineData.filter(entry => now - entry.timestamp > MAX_AGE);
            localStorage.setItem('offline_appointment', JSON.stringify(remaining));
            alert("✅ ข้อมูลการนัดหมายแบบออฟไลน์ ถูกส่งเรียบร้อยแล้ว");
            location.reload();
        } else {
            console.error("❌ การ sync ล้มเหลว:", result.message);
        }
    } catch (error) {
        console.error("❌ เกิดข้อผิดพลาดระหว่าง sync:", error);
    }
}

// ✅ เรียก sync อัตโนมัติเมื่อกลับมาออนไลน์หรือเปิดหน้าใหม่
window.addEventListener('online', syncOfflineAppointment);
window.addEventListener('load', syncOfflineAppointment);
