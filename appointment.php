<?php
session_start();
include 'navbar.php';
include("connect.php");

// ตรวจสอบ session
$clinic_id = $_SESSION["clinic_id"];

// เพิ่มนัดหมาย
if (isset($_POST['save'])) {
    $dog_id = $_POST['dog_id'];
    $appointment_date = $_POST['appointment_date'];
    $description = $_POST['description'];

    $strSQL = "INSERT INTO appointments (dog_id, clinic_id, appointment_date, description)
               VALUES ('$dog_id', '$clinic_id', '$appointment_date', '$description')";
    mysqli_query($objCon, $strSQL);
    echo "<script>alert('บันทึกนัดหมายเรียบร้อยแล้ว');window.location='appointment_register.php';</script>";
}

// ลบ
if (isset($_GET['delete'])) {
    $appointment_id = $_GET['delete'];
    mysqli_query($objCon, "DELETE FROM appointments WHERE appointment_id='$appointment_id' AND clinic_id='$clinic_id'");
    echo "<script>window.location='appointment_register.php';</script>";
}

// ดึงรายการนัดหมายทั้งหมดของคลินิก
$strSQL = "SELECT a.*, d.dog_name 
           FROM appointments a 
           LEFT JOIN dogs d ON a.dog_id = d.dog_id 
           WHERE a.clinic_id = '$clinic_id' 
           ORDER BY a.appointment_date DESC";
$objQuery = mysqli_query($objCon, $strSQL);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการนัดหมาย</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        .container { max-width: 900px; margin: auto; }
    </style>
</head>
<body>

<div class="container mt-4">
    <h3 class="mb-3 text-center">?? บันทึกนัดหมาย</h3>
    <form method="post" class="card p-4 shadow-sm">
        <div class="form-group">
            <label>เลือกสุนัข</label>
            <select name="dog_id" class="form-control" required>
                <option value="">-- เลือก --</option>
                <?php
                $dogQuery = mysqli_query($objCon, "SELECT * FROM dogs WHERE clinic_id='$clinic_id'");
                while($dog = mysqli_fetch_assoc($dogQuery)) {
                    echo "<option value='".$dog['dog_id']."'>".$dog['dog_name']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>วันเวลาเข้าพบ</label>
            <input type="datetime-local" name="appointment_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>รายละเอียด</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="text-center">
            <button type="submit" name="save" class="btn btn-primary">บันทึก</button>
        </div>
    </form>

    <hr>

    <h5 class="mt-4">?? รายการนัดหมาย</h5>
    <table class="table table-bordered table-hover table-sm">
        <thead class="thead-light">
            <tr>
                <th>ชื่อสุนัข</th>
                <th>วันเวลา</th>
                <th>รายละเอียด</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($objQuery)) { ?>
            <tr>
                <td><?= $row['dog_name'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['appointment_date'])) ?></td>
                <td><?= nl2br($row['description']) ?></td>
                <td>
                    <a href="?delete=<?= $row['appointment_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ลบนัดหมายนี้?')">ลบ</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
