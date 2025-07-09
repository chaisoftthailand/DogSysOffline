<?@ob_start();?>
<?@session_start();?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>บันทึกข้อมูลคลินิค</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<!-- Fancybox CSS รูปภาพ-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<?php
include 'navbar.php';
include 'dbconnect.php';
include 'function.php';

// ตรวจสอบ session ว่ามีผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

//------------------------------------------------ 2.Post For Insert/Update/Delete  -----------------------------------------///
$clinic_id = $_SESSION['clinic_id'];
$msg = "";

if (isset($_POST['save'])) {
    $clinic_id = $_POST['clinic_id'];
    $clinic_name = $_POST['clinic_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];    
    $owner_name = $_POST['owner_name'];
    $created_at=date("Y-m-d H:i:s");

    	if ($clinic_id=='') {        	
        	$strSQL="INSERT INTO clinics (clinic_name, address, phone, email, owner_name, created_at) ";
	  	$strSQL.= " VALUES ('$clinic_name', '$address', '$phone', '$email', '$owner_name', '$created_at')";
	} else {
		    $strSQL="UPDATE clinics SET clinic_name='$clinic_name', address='$address', phone='$phone', email='$email', owner_name='$owner_name' ";
	  	$strSQL.="WHERE clinic_id=$clinic_id ";
    	}
   	mysqli_query($objConn, $strSQL);
  	header("Location: clinic_update.php?Mode=$Mode");
    	exit();
}

if (isset($_GET['del'])) {
    $del_id = $_GET['del'];
    $strSQL = "DELETE FROM clinics WHERE clinic_id=$del_id ";
    mysqli_query($objConn, $strSQL);
    header("Location: clinic_update.php?Mode=$Mode");
    exit();
}

//------------------------------------------------ 1.Start Get Data to Form -----------------------------------------///
$edit = false;
if (isset($_GET['edit'])) {
   	$clinic_id = $_GET['edit'];
	$strSQL = "SELECT * FROM clinics WHERE clinic_id=$clinic_id ORDER BY clinic_id DESC";
	$objQuery = mysqli_query($objConn, $strSQL);
      while($objRequest = mysqli_fetch_assoc($objQuery)) {
            $edit = true;            
	    	$clinic_id = $objRequest['clinic_id'];	
	    	$clinic_name = $objRequest['clinic_name'];
	    	$address = $objRequest['address'];
	    	$phone = $objRequest['phone'];
	    	$email = $objRequest['email'];    
		$owner_name = $objRequest['owner_name'];
	}
} else {
	$clinic_id='';
}
?>

<div class="container mt-4">
<div class="card p-4 mb-4 shadow-sm">    
<h3 class="mb-4 text-center">🏥บันทึกข้อมูลคลินิค <sup><font color="#FF0000"> <?if($clinic_id!='') {?>(clinic_id=<?=$clinic_id?>)<?}?></font></sup></h3>
<form method="POST" action="<?=$_SERVER["SCRIPT_NAME"]?>" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group col-md-2">
            <label>ชื่อคลินิค</label>
            <input type="text" class="form-control" name="clinic_name" value='<?=$clinic_name?>'>
        </div>
        <div class="form-group col-md-2">
            <label>ที่อยู่</label>
            <input type="text" class="form-control" name="address" value='<?=$address?>'>
        </div>
        <div class="form-group col-md-2">
            <label>โทรศัพท์</label>
            <input type="number" class="form-control" name="phone" value=<?=$phone?> >
        </div>
        <div class="form-group col-md-2">
            <label>email</label>
            <input type="text" class="form-control" name="email" value=<?=$email?>>
        </div>        
        <div class="form-group col-md-2">
            <label>เจ้าของคลินิค</label>
            <input type="text" class="form-control" name="owner_name" value='<?=$owner_name?>' >
        </div>        

<div align="center">
    <input type="hidden" name="clinic_id" value="<?=$clinic_id?>">
    <input type="hidden" name="Mode" value='<?=$Mode?>'>        
    <button type="submit" name="save" class="btn btn-primary"><?= $edit ? "อัปเดตข้อมูล" : "บันทึกข้อมูล" ?></button>         
</div>    
</form>
</div>
</div>

<!-- ✅ 00.List report -->
<div class="card p-4 mb-4 shadow-sm">        
<table class="table table-bordered table-responsive-sm" id="DataTable">
    <thead>
        <tr>
            <th>ชื่อคลินิค</th>
            <th>ที่อยู่</th> 		
            <th class="no-sort">แก้ไข</th>
            <th class="no-sort">ลบ</th>		
        </tr>
    </thead>
    <tbody>
        <?php
	
  	if($_SESSION['role']==2){
	   $strSQL = "SELECT * FROM clinics WHERE clinic_id=$clinic_id ORDER BY clinic_id DESC";	   
	} else { //admin
	   $strSQL = "SELECT * FROM clinics ORDER BY clinic_id DESC";	   
	}
	   //echo $strSQL;
  	   $objQuery = mysqli_query($objConn, $strSQL);
	   while($objRequest = mysqli_fetch_assoc($objQuery)) { 
	   ?>
        <tr>
            <td><?= $objRequest['clinic_name'] ?></td>
            <td><?= $objRequest['address'] ?></td>
            <td align=center><a href="?edit=<?= $objRequest['clinic_id']?>&Mode=<?=$Mode?>" class="btn btn-sm btn-info"><i class='fa fa-edit fa-lg'></i></a></td>            
            <td align=center><a href="?del=<?= $objRequest['clinic_id']?>&Mode=<?=$Mode?>"  class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจ?')"><i class="fa fa-trash-o fa-lg"></i></a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#DataTable').DataTable(
     {
 	 "pageLength": 10,
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
		"iDisplayLength": 300,
         }]
 
	}
	);
	//close show entries
	//$(".dataTables_length").hide(); 
</script>

<!-- Popup Modal -->
<div id="popup" class="popup-overlay" onclick="closePopup()">
  <div class="popup-content" onclick="event.stopPropagation()">
    <span class="close-btn" onclick="closePopup()">close</span>
    <div id="popup-text"></div>
  </div>
</div>

<style>
.popup-overlay {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  z-index: 9999;
  justify-content: center;
  align-items: center;
}

.popup-content {
  background: white;
  padding: 20px;
  max-width: 500px;
  width: 90%;
  border-radius: 10px;
  box-shadow: 0 0 10px #000;
  max-height: 80vh;
  overflow-y: auto;
  position: relative;
}

.close-btn {
  position: absolute;
  top: 8px;
  right: 12px;
  font-size: 18px;
  cursor: pointer;
  color: #333;
}
</style>

<script>
function openPopup(text) {
    document.getElementById('popup-text').innerText = text;
    document.getElementById('popup').style.display = 'flex';
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
}
</script>

</body>
</html>