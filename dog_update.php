<?php
@ob_start();
@session_start();
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ระบบจัดการข้อมูลสัตว์ส่งรักษา</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<!-- Fancybox CSS รูปภาพ -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<!-- Offline JS -->
<script type="text/javascript" src="js/LocalStorage_Dog.js"></script>

<?php
include 'navbar.php';
include 'dbconnect.php';
include 'function.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$clinic_id = ($_SESSION['role'] == 3) ? 0 : $_SESSION['clinic_id'];
// ✅ รับ JSON Sync แบบ Offline
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['CONTENT_TYPE']) &&
    strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
) {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    foreach ($data as $entry) {
        $d = $entry['data'];
        $dog_id = mysqli_real_escape_string($objCon, $entry['id']);
        $clinic_id = mysqli_real_escape_string($objCon, $d['clinic_id']);
        $dog_name = mysqli_real_escape_string($objCon, $d['dog_name']);
        $dog_breed = mysqli_real_escape_string($objCon, $d['dog_breed']);
        $dog_age = mysqli_real_escape_string($objCon, $d['dog_age']);
        $dog_weight = mysqli_real_escape_string($objCon, $d['dog_weight']);
        $dog_gender = mysqli_real_escape_string($objCon, $d['dog_gender']);
        $dog_medical_history = mysqli_real_escape_string($objCon, $d['dog_medical_history']);
        $created_at = date("Y-m-d H:i:s");
        $user_id = mysqli_real_escape_string($objCon, $d['user_id']);

        $dog_image_path = !empty($d['dog_image_base64']) ? saveBase64Image($d['dog_image_base64'], 'dog_') : '';
        $xray_image_path = !empty($d['xray_image_base64']) ? saveBase64Image($d['xray_image_base64'], 'xray_') : '';

        if ($entry['action'] === 'insert') {
            $strSQL = "INSERT INTO dogs (user_id, clinic_id, dog_name, dog_breed, dog_age, dog_weight, dog_gender, dog_medical_history, created_at, dog_image_path, xray_image_path)
                       VALUES ('$user_id', '$clinic_id', '$dog_name', '$dog_breed', '$dog_age', '$dog_weight', '$dog_gender', '$dog_medical_history', '$created_at', '$dog_image_path', '$xray_image_path')";
        } elseif ($entry['action'] === 'update' && !empty($dog_id)) {
            $strSQL = "UPDATE dogs SET dog_name='$dog_name', dog_breed='$dog_breed', dog_age='$dog_age', dog_weight='$dog_weight', dog_gender='$dog_gender', dog_medical_history='$dog_medical_history'";
            if ($dog_image_path) $strSQL .= ", dog_image_path='$dog_image_path'";
            if ($xray_image_path) $strSQL .= ", xray_image_path='$xray_image_path'";
            $strSQL .= " WHERE dog_id=$dog_id";
        }
        if (isset($strSQL)) mysqli_query($objCon, $strSQL);
    }
    echo json_encode(['status' => 'success']);
    exit();
}

// ✅ 2. แบบฟอร์มปกติ - Online
if (isset($_POST['save'])) {
    $dog_id = $_POST['dog_id'];
    $dog_name = $_POST['dog_name'];
    $dog_breed = $_POST['dog_breed'];
    $dog_age = $_POST['dog_age'];
    $dog_weight = $_POST['dog_weight'];    
    $dog_gender = $_POST['dog_gender'];
    $dog_medical_history= $_POST['dog_medical_history'];
    $created_at=date("Y-m-d H:i:s");

	$uploadDir = 'uploads/';
	$dog_image_path ='';
	$xray_image_path ='';

	if (!empty($_FILES['dog_image']['name'])) {
	    $dog_image_path = $uploadDir . basename($_FILES['dog_image']['name']);
	    move_uploaded_file($_FILES['dog_image']['tmp_name'], $dog_image_path);
	}
	if (!empty($_FILES['xray_image']['name'])) {
	    $xray_image_path = $uploadDir . basename($_FILES['xray_image']['name']);
	    move_uploaded_file($_FILES['xray_image']['tmp_name'], $xray_image_path);
	}
   	if ($dog_id==''):
       	$strSQL="INSERT INTO dogs (user_id,clinic_id, dog_name, dog_breed, dog_age, dog_weight, dog_gender, dog_medical_history,created_at,dog_image_path,xray_image_path) ";
  	    $strSQL.= " VALUES ($user_id,$clinic_id, '$dog_name', '$dog_breed', '$dog_age', '$dog_weight', '$dog_gender', '$dog_medical_history','$created_at','$dog_image_path','$xray_image_path')";
    else:
	    $strSQL="UPDATE dogs SET dog_name='$dog_name', dog_breed='$dog_breed', dog_age='$dog_age', dog_weight='$dog_weight', dog_gender='$dog_gender', dog_medical_history='$dog_medical_history' ";
		if($dog_image_path!=''){
	  	  $strSQL.=",dog_image_path='$dog_image_path' ";
		 }
 		if($xray_image_path!=''){
  	  		$strSQL.=",xray_image_path='$xray_image_path' ";
	  	}
	  $strSQL.="WHERE dog_id=$dog_id ";
    endif;
	   	mysqli_query($objCon, $strSQL);
        echo $strSQL;
 	    header("Location: dog_update.php?Mode=$Mode");
    	exit();
}

// ✅ 3.การลบข้อมูล
if (isset($_GET['del'])) {
    $del_id = $_GET['del'];
    $strSQL = "DELETE FROM dogs WHERE dog_id=$del_id";
    mysqli_query($objCon, $strSQL);
    header("Location: dog_update.php?Mode=$Mode");
    exit();
}

//------------------------------------------------ 1.Start Get Data to Variable -----------------------------------------///
// ✅ 0. เก็บเพื่อแก้ไขข้อมูล
$edit = false;
if (isset($_GET['edit'])) {
   	$edit_id = $_GET['edit'];
	$strSQL = "SELECT * FROM dogs WHERE dog_id=$edit_id  ORDER BY dog_id DESC";
	$objQuery = mysqli_query($objCon, $strSQL);
    while($objRequest = mysqli_fetch_assoc($objQuery)) {
        $edit = true;        
	    $dog_id = $objRequest['dog_id'];	
	    $dog_name = $objRequest['dog_name'];
	    $dog_breed = $objRequest['dog_breed'];
	    $dog_age = $objRequest['dog_age'];
	    $dog_weight = $objRequest['dog_weight'];    
		$dog_gender = $objRequest['dog_gender'];
	    $dog_medical_history = $objRequest['dog_medical_history'];
		$dog_image_path = $objRequest['dog_image_path'];
		$xray_image_path = $objRequest['xray_image_path'];
		$clinic_id=$objRequest['clinic_id'];
	}
}
//------------------------------------------------ 2.Get Data to Form -----------------------------------------//
?>

<!-- HTML Form และ Button Sync -->
<div align="center">
  <button type="button" onclick="syncDogQueue()" class="btn btn-success">🔁 ซิงก์ข้อมูลสัตว์ที่ค้างไว้</button>
</div><br>
<div class="container mt-4">
<div class="card p-4 mb-4 shadow-sm">
<h3 class="mb-4 text-center">🐶<?= $edit ? "แก้ไข" : "บันทึก" ?>ข้อมูลสัตว์ส่งรักษา<sup><font color="#FF0000"> <?if($dog_id!='') {?>(dog_id=<?=$dog_id?>)<?}?></font></sup></h3>
<form method="POST" action="dog_update.php" onsubmit="return handleSubmit(event, this);" enctype="multipart/form-data">
	<?if($_SESSION['role']==3){?>
        <div class="form-group col-md-2">
                <label class="form-label">เลือกคลินิก</label>
                <select name="clinic_id" class="form-control">
                  <option value="">-- เลือกคลินิก --</option>
			<?opt_clinic($clinic_id,$objCon)?>
                </select>
              </div>    
	<?}?>		  
        <div class="form-group col-md-2">
            <label>ชื่อสุนัข</label>
            <input type="text" class="form-control" name="dog_name" value='<?=$dog_name?>'>
        </div>
        <div class="form-group col-md-2">
            <label>สายพันธุ์</label>
            <input type="text" class="form-control" name="dog_breed" value='<?=$dog_breed?>'>
        </div>
        <div class="form-group col-md-2">
            <label>อายุ</label>
            <input type="number" class="form-control" name="dog_age" value=<?=$dog_age?> required>
        </div>
        <div class="form-group col-md-2">
            <label>น้ำหนัก</label>
            <input type="number" class="form-control" name="dog_weight" value=<?=$dog_weight?> required>
        </div>        
        <div class="form-group col-md-2">            
            <label>เพศ</label>
            <select name="dog_gender" class="form-control" value=<?=$dog_gender?> required>
                <option value="ตัวผู้">ตัวผู้</option>
                <option value="ตัวเมีย">ตัวเมีย</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>ประวัติการรักษา</label>            
            <textarea name="dog_medical_history" class="form-control" required><?=$dog_medical_history?></textarea>            
        </div>                
    <div class="form-row">
        <!-- ช่องกรอกข้อมูลเดิมทั้งหมดอยู่ตรงนี้ -->

        <div class="form-group col-md-4">
            <label>รูปภาพสุนัข</label>
            <input type="file" class="form-control-file" name="dog_image" accept="image/*">
        </div>

        <div class="form-group col-md-4">
            <label>ใบ X-Ray</label>
            <input type="file" class="form-control-file" name="xray_image" accept="image/*,.pdf">
        </div>
    </div>

    <input type="hidden" name="Mode" value='<?=$Mode?>'>
    <input type="hidden" name="dog_id" value="<?=$dog_id?>">
    <div align="center">        
    <button type="submit" name="save" class="btn btn-primary"><?= $edit ? "อัปเดตข้อมูล" : "บันทึกข้อมูล" ?></button>            
</form>
</div>
</div>


<!-- ✅ 00.List report -->
<div class="card p-4 mb-4 shadow-sm">    
<table class="table table-bordered table-responsive-sm" id="DataTable">
    <thead>
        <tr>
            <th>ชื่อสุนัข</th>
            <th>รายละเอียด</th> 		
            <th class="no-sort">แก้ไข</th>
            <th class="no-sort">ลบ</th>		
        </tr>
    </thead>
    <tbody>
        <?php
	   	// แสดงข้อมูล role-> user=1, clinic=2 ,admin=3 การมองเห็นข้อมูลจะต่างกัน
	   	if($_SESSION['role']==1){
		   $strSQL = "SELECT * FROM dogs WHERE user_id=".$_SESSION['user_id']." ORDER BY dog_id DESC";
		} 	
		if($_SESSION['role']==2){
		   $strSQL = "SELECT * FROM dogs WHERE clinic_id=$clinic_id ORDER BY dog_id DESC";
		}
	   	if($_SESSION['role']==3){
		   $strSQL = "SELECT * FROM dogs ORDER BY dog_id DESC";
		}
	//echo $strSQL;
	$objQuery = mysqli_query($objCon, $strSQL);
	while($objRequest = mysqli_fetch_assoc($objQuery)) {
        $No++;
        ?>
        <tr>
            <td><?=$No?>
            <?if (!empty($objRequest['dog_image_path'])):?>
				<a data-fancybox="gallery<?= $objRequest['dog_id'] ?>" href="<?= htmlspecialchars($objRequest['dog_image_path']) ?>">
                <img src="<?= htmlspecialchars($objRequest['dog_image_path']) ?>" style="max-width: 80px; border-radius: 5px;">
            <?else:?>
                    <span class="text-muted">ไม่มีรูป</span>
            <?endif;?>
		<?= $objRequest['dog_name'] ?><sup><font color="#FF0000"><?= $objRequest['dog_id'] ?></font></sup></td>
            <td>สายพันธุ์:<?= $objRequest['dog_breed'] ?>,อายุ: <?= $objRequest['dog_age'] ?> ปี,นำหนัก: <?= $objRequest['dog_weight'] ?>,เพศ: <?= $objRequest['dog_gender'] ?>, เจ้าของชื่อ: <?= ret_user($objRequest['user_id'],$objCon) ?><br>
                <button onclick="openPopup(`<?=$objRequest['dog_medical_history'] ?>`)" class="btn btn-sm btn-info"><i class='fa fa-medkit fa-lg'></i></button>
                <?php if (!empty($objRequest['xray_image_path'])): ?>
                    <a href="<?= htmlspecialchars($objRequest['xray_image_path']) ?>" target="_blank" class="btn btn-sm btn-info"><i class='fa fa-heartbeat fa-lg'></i></a>
                <?php endif; ?>
            </td>
            <td align=center><a href="?edit=<?= $objRequest['dog_id']?>&btn=edit&Mode=<?=$Mode?>" class="btn btn-sm btn-info"><i class='fa fa-edit fa-lg'></i></a></td>            
            <td align=center><a href="?del=<?= $objRequest['dog_id']?>&Mode=<?=$Mode?>"  class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจว่าจะลบข้อมูลนี้?')"><i class="fa fa-trash-o fa-lg"></i></a>            </td>
        </tr>
        <?}?>
    </tbody>
</table>
</div>

</body>
</html>

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
<script>
function handleSubmit(event, form) {
    if (!navigator.onLine) {
        event.preventDefault();
        saveDogOffline(form);
        return false;
    }
    return true;
}
</script>