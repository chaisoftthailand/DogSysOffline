<?php
include 'dbconnect.php';
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit;
}

// ตรวจสอบว่ามี treatment_id หรือไม่
if ($data['treatment_id'] == "") {
    $sql = "INSERT INTO treatments (clinic_id, dog_id, treatment_date, symptoms, diagnosis, treatment, medication, doctor_name, user_id, created_at, next_appointment) 
            VALUES (
                '{$data['clinic_id']}',
                '{$data['dog_id']}',
                '{$data['treatment_date']}',
                '{$data['symptoms']}',
                '{$data['diagnosis']}',
                '{$data['treatment']}',
                '{$data['medication']}',
                '{$data['doctor_name']}',
                '{$data['user_id']}',
                NOW(),
                '{$data['next_appointment']}'
            )";
} else {
    $sql = "UPDATE treatments SET 
                dog_id='{$data['dog_id']}',
                treatment_date='{$data['treatment_date']}',
                symptoms='{$data['symptoms']}',
                diagnosis='{$data['diagnosis']}',
                treatment='{$data['treatment']}',
                medication='{$data['medication']}',
                doctor_name='{$data['doctor_name']}',
                next_appointment='{$data['next_appointment']}'
            WHERE treatment_id='{$data['treatment_id']}'";
}

if (mysqli_query($objCon, $sql)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($objCon)]);
}
?>
