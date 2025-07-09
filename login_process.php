<?php
session_start();
include 'dbconnect.php';
include 'function.php';
$username = $_POST['username'];
//$password = $_POST['password'];
$password = dec_enc(encrypt,$_POST['password']);
$Mode = $_POST['Mode'];

// ตรวจสอบว่าชื่อผู้ใช้มีในระบบหรือไม่
$strSQL = "SELECT * FROM user WHERE username = '".mysqli_real_escape_string($objCon, $username)."' LIMIT 1";

$objQuery = $objCon->query($strSQL);

if ($objQuery && $objQuery->num_rows > 0) {
    $user = $objQuery->fetch_assoc();    
    // ตรวจสอบรหัสผ่าน
    if ($password==$user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['clinic_id'] = $user['clinic_id'];        
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit();
    } else {       
        echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('ไม่พบชื่อผู้ใช้งานนี้'); window.location='index.php';</script>";
}
?>
