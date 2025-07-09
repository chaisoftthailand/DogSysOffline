<?@ob_start();?>
<?@session_start();?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เมนูหลักระบบคลินิกรักษาสุนัข</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?
include 'dbconnect.php';
include 'function.php';
$aRole=Array('คนทั่วไป','ลูกค้า','เจ้าหน้าที่คลินิก','ผู้ดูแลระบบ');
$Mode=$_REQUEST["Mode"];
if($Mode==''){?>
  <link rel="stylesheet" type="text/css" href="css/dark_mode.css">
<?} else { ?>  
  <link rel="stylesheet" type="text/css" href="css/white_mode.css">  
<?}?>

<div class="container py-5">
<h2 class="text-center mb-4">ระบบบริหารจัดการคลินิกรักษาสัตว์:<?=ret_clinic($_SESSION['clinic_id'],$objCon);?><br>
  <sup><font color="#FF0000">(สิทธิ์ผู้ใช้ระดับ:<?=$aRole[$_SESSION['role']]?>)</font></sup> </h2>
  
  <div align="center">  <?include 'Switch8.php';?>  </div>

  <br>
  <div class="row row-cols-1 row-cols-md-2 g-4">
    <div class="col">
      <div class="card p-3">
        <h5 class="card-title">🐶 บันทึกข้อมูลสัตว์ส่งรักษา</h5>
        <p>เพิ่ม/แก้ไข/ดูประวัติสุนัข (ส่วนนี้สามารถใช้งานได้ทั้งระบบ Online/Offline)</p>
        <a href="dog_update.php?Mode=<?=$Mode?>" class="btn btn-outline-secondary">ไปยังหน้าจัดการ</a>
      </div>
    </div>

    <div class="col">
      <div class="card p-3">
        <h5 class="card-title">💉 การรักษาพยาบาล</h5>
        <p>บันทึกการรักษาและประวัติการรักษา (ส่วนนี้สามารถใช้งานได้ทั้งระบบ Online/Offline)</p>
        <a href="treatment_manage.php?Mode=<?=$Mode?>" class="btn btn-outline-secondary">เริ่มการรักษา</a>
      </div>
    </div>

    <div class="col">
      <div class="card p-3">
        <h5 class="card-title">📅 การจัดการผู้ใช้งานนัดหมาย</h5>
        <p>ระบบนัดหมายและแจ้งเตือน (ส่วนนี้สามารถใช้งานได้ทั้งระบบ Online/Offline)</p>
        <a href="appointment_register.php?Mode=<?=$Mode?>" class="btn btn-outline-secondary">จัดการนัดหมาย</a>
      </div>
    </div>
    
    <? if($_SESSION['role']>=2) {//clinic?> 
    <div class="col">
      <div class="card p-3">
        <h5 class="card-title">📊 รายงาน</h5>
        <p>รายงานการรักษาและการทำงานของคลินิก</p>
        <a href="report.php?Mode=<?=$Mode?>" class="btn btn-outline-secondary">ดูรายงาน</a>
      </div>
    </div>
    <?}?>

    <? if($_SESSION['role']==3) {//admin?> 
      <div class="col">
      <div class="card p-3">
        <h5 class="card-title">🏥 ข้อมูลคลินิก</h5>
        <p>เพิ่มคลินิก/ผู้ดูแลระบบ</p>
        <a href="clinic_update.php?Mode=<?=$Mode?>" class="btn btn-outline-secondary">ตั้งค่าคลินิก</a>
      </div>
    </div>
    
      <div class="col">
      <div class="card p-3">
        <h5 class="card-title">👤 จัดการผู้ใช้งาน</h5>
        <p>สิทธิ์การใช้งานและผู้ดูแล (ส่วนนี้สามารถใช้งานได้ทั้งระบบ Online/Offline)</p>
        <a href="user_update.php?Mode=<?=$Mode?>" class="btn btn-outline-secondary">ผู้ใช้งานระบบ</a>
      </div>
    </div>
    <?}?>
    
    
    <div class="text-right mb-3" align=center>
    <a href="logout.php"  class="btn btn-outline-secondary">ออกจากระบบ</a>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
