<?
$Mode=$_REQUEST["Mode"];
if($Mode==''){?>
  <link rel="stylesheet" type="text/css" href="css/darkmode.css">
<?} else { ?>  
  <link rel="stylesheet" type="text/css" href="css/whitemode.css">  
<?}?>

<?php
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'ผู้ใช้งาน';
?>
<div class="navbar" id="myNavbar">
	<a href="dashboard.php?Mode=<?=$Mode?>"> หน้าหลัก</a>
	<a href="dog_update.php?Mode=<?=$Mode?>">บันทึกข้อมูลสัตว์ส่งรักษา</a>
	<a href="treatment_manage.php?Mode=<?=$Mode?>&clinic_id=1">การรักษาพยาบาล</a>
	<a href="appointment_register.php?Mode=<?=$Mode?>&clinic_id=1">การนัดหมาย</a>
  <?if($_SESSION['role']>=2) {?>
    <a href="report.php?Mode=<?=$Mode?>">รายงาน</a>
  <?}?>
  <?if($_SESSION['role']==3) {//admin?>   
    <a href="clinic_update.php?Mode=<?=$Mode?>">ข้อมูลคลินิค</a>
    <a href="user_update.php?Mode=<?=$Mode?>">การจัดการผู้ใช้งาน</a>
  <?}?>
  <div class="right">  
    <a href="logout.php">ออกจากระบบ</a>
  </div>
  <a href="javascript:void(0);" class="icon" onclick="toggleNavbar()">
    &#9776;
  </a>
</div>

<script>
function toggleNavbar() {
  var x = document.getElementById("myNavbar");
  if (x.className === "navbar") {
    x.className += " responsive";
  } else {
    x.className = "navbar";
  }
}
</script>
