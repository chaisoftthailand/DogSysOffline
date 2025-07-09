<?php
// ? รับข้อมูล Sync แบบ Offline
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER["CONTENT_TYPE"]) &&
    strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false
) {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);
    $synced_ids = [];

    foreach ($data as $item) {
        $id = mysqli_real_escape_string($objCon, $item['id']);
        $action = $item['action'];
        $dog_id = mysqli_real_escape_string($objCon, $item['data']['dog_id']);
        $clinic_id = mysqli_real_escape_string($objCon, $item['data']['clinic_id']);
        $appointment_date = mysqli_real_escape_string($objCon, $item['data']['appointment_date']);
        $description = mysqli_real_escape_string($objCon, $item['data']['description']);

        $querySuccess = false;

        if ($action === 'insert' || strpos($id, 'offline_') === 0) {
            $sql = "INSERT INTO appointments (dog_id, clinic_id, appointment_date, description)
                    VALUES ('$dog_id', '$clinic_id', '$appointment_date', '$description')";
            $querySuccess = mysqli_query($objCon, $sql);
        } else if ($action === 'update' && is_numeric($id)) {
            $checkSQL = "SELECT 1 FROM appointments WHERE appointment_id='$id' LIMIT 1";
            $checkResult = mysqli_query($objCon, $checkSQL);
            if (mysqli_num_rows($checkResult) > 0) {
                $sql = "UPDATE appointments SET 
                        dog_id='$dog_id',
                        clinic_id='$clinic_id',
                        appointment_date='$appointment_date',
                        description='$description'
                        WHERE appointment_id='$id'";                        
               $querySuccess = mysqli_query($objCon, $sql);
            }
        }

        if ($querySuccess) {
            $synced_ids[] = $id;
        }
    }

    $total = count($data);
    $success = count($synced_ids);

    if ($success === $total) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode([
            "status" => "partial",
            "synced_ids" => $synced_ids,
            "message" => "$success จาก $total รายการสำเร็จ"
        ]);
    }
    exit();
}?>