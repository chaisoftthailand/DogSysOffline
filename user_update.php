<?@ob_start();?>
<?@session_start();?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>จัดการข้อมูลผู้ใช้ระบบ</title>
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

<!-- ตรวจสอบการเชื่อมต่อเน็ต และ ส่งข้อมูลที่ค้างไว้ไปยังเซิร์ฟเวอร์อัตโนมัติเมื่อออนไลน์ -->
<script type="text/javascript" src="js/LocalStorage.js"></script>
<?php
ob_start();
session_start();

include 'navbar.php';
include 'dbconnect.php';
include 'function.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$clinic_id = $_SESSION['clinic_id'];
$msg = "";

// ✅ 1. รับ JSON แบบ Offline Sync ก่อนเงื่อนไขอื่นใด
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER["CONTENT_TYPE"]) &&
    strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false
) {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (!is_array($data)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        exit();
    }
    foreach ($data as $user) {
        $d = $user['data'];
        $id = mysqli_real_escape_string($objCon, $user['id']);
        $username = mysqli_real_escape_string($objCon, $d['username']);
        $password_raw = $d['password'];
        //$password = password_hash($password_raw, PASSWORD_DEFAULT);
        $password = dec_enc(encrypt,password_raw);            
        $clinic_id = mysqli_real_escape_string($objCon, $d['clinic_id']);
        $role = mysqli_real_escape_string($objCon, $d['role']);
        $fullname = mysqli_real_escape_string($objCon, $d['fullname']);
        $email = mysqli_real_escape_string($objCon, $d['email']);
        $created_at = date("Y-m-d H:i:s");
        if ($user['action'] === 'insert') {
            $strSQL = "INSERT INTO user (username, password, clinic_id, role, fullname, email, created_at)
                       VALUES ('$username', '$password', '$clinic_id', '$role', '$fullname', '$email', '$created_at')";
        } elseif ($user['action'] === 'update' && !empty($id)) {
            $strSQL = "UPDATE user SET username='$username', password='$password', role='$role',
                       fullname='$fullname', email='$email' WHERE id=$id";
        }
        if (isset($strSQL)) {
            mysqli_query($objCon, $strSQL);
        }
    }
    echo json_encode(['status' => 'success']);
    exit();
}

// ✅ 2. แบบฟอร์มปกติ
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    //$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password = dec_enc(encrypt,$_POST['password']);    
    $clinic_id = $_POST['clinic_id'];
    $role = $_POST['role'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $created_at = date("Y-m-d H:i:s");

    if ($id == '') {
        $strSQL = "INSERT INTO user (username, password, clinic_id, role, fullname, email, created_at)
                   VALUES ('$username', '$password', '$clinic_id', '$role', '$fullname', '$email', '$created_at')";
    } else {
        $strSQL = "UPDATE user SET username='$username', password='$password', clinic_id='$clinic_id',
                   role='$role', fullname='$fullname', email='$email' WHERE id=$id";
    }

    mysqli_query($objCon, $strSQL);
    header("Location: user_update.php");
    exit();
}

// ✅ 3. การลบข้อมูล
if (isset($_GET['del'])) {
    $del_id = (int)$_GET['del'];
    $strSQL = "DELETE FROM user WHERE id=$del_id";
    mysqli_query($objCon, $strSQL);
    header("Location: user_update.php");
    exit();
}

// ✅ 4. แก้ไขข้อมูลเพื่อแสดงในฟอร์ม
$edit = false;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $strSQL = "SELECT * FROM user WHERE id=$edit_id";
    $objQuery = mysqli_query($objCon, $strSQL);
    if ($objRequest = mysqli_fetch_assoc($objQuery)) {
        $id = $objRequest['id'];
        $edit = true;        
        $clinic_id = $objRequest['clinic_id'];
        $username = $objRequest['username'];
        //$password = $objRequest['password'];
        $password = dec_enc(decrypt,$objRequest['password']);            
        $role = $objRequest['role'];
        $fullname = $objRequest['fullname'];
        $email = $objRequest['email'];
    }
}
?>

<div class="container mt-4">
<div class="card p-4 mb-4 shadow-sm">
<h3 class="mb-4 text-center">👤 บันทึกข้อมูลการจัดการผู้ใช้งาน <sup><font color="#FF0000"> <?php if($id!='') { echo "(id=$id)"; } ?></font></sup></h3>
<!-- ✅ แก้ฟอร์มให้ทำงานแบบ JS Offline-First -->
<form method="POST" action="user_update.php" onsubmit="return handleSubmit(event, this);" enctype="multipart/form-data">   
        <div class="col-md-3"><input type="text" name="username" class="form-control" placeholder="Username" value="<?= $username ?>" required></div>
        <div class="col-md-3"><input type="text" name="password" class="form-control" placeholder="Password" value="<?= $password ?>" required></div>
        <div class="col-md-3">
            <select name="role" class="form-control" required>
            <?php opt_role($role) ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="clinic_id" class="form-control" required>
            <?php opt_clinic($clinic_id,$objCon) ?>
            </select>
        </div>
        <div class="col-md-3"><input type="text" name="fullname" class="form-control" placeholder="Full Name" value="<?= $fullname ?>" required></div>
        <div class="col-md-3"><input type="text" name="email" class="form-control" placeholder="Email" value="<?= $email ?>" required></div>      
        <br>
        <div class="form-group col-md-4" align=center>
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="Mode" value='<?= $Mode ?>'>                
            <button type="submit" name="save" class="btn btn-primary"><?= $edit ? "อัปเดตข้อมูล" : "บันทึกข้อมูล" ?></button>                   
        </div>
</form>
</div>

<div class="card p-4 mb-4 shadow-sm">
<!-- ตารางแสดงผล -->
	<table class="table table-bordered table-responsive-sm" id="DataTable">
        <thead>
            <tr>
            <th>Username</th>
            <th>Fullname</th>		
            <th>Lavel</th>
            <th class="no-sort">แก้ไข</th>
            <th class="no-sort">ลบ</th>
            </tr>
        </thead>
        <tbody>
            <?
		// ดึงรายชื่อผู้ใช้
	   	$strSQL = "SELECT * FROM user ORDER BY id DESC";
  	   	$objQuery = mysqli_query($objCon, $strSQL);		
		while($objRequest = mysqli_fetch_assoc($objQuery)) {  ?>
            <tr>
                <td><font size="-1"><?= htmlspecialchars($objRequest['username']) ?> <sup><font color=red><?=$objRequest['id']?></sup></font></td>
      		    <td><font size="-1"><?= $objRequest['fullname'] ?></font></td>
                <td><font size="-1"><?= $aRole[$objRequest['role']];?>:<?=ret_clinic($objRequest['clinic_id'],$objCon); ?></font></td>
            	<td align=center><a href="?edit=<?=$objRequest['id']?>&Mode=<?=$Mode?>" class="btn btn-sm btn-info"><i class='fa fa-pencil fa-lg'></i></a></td>            
	            <td align=center><a href="?del=<?= $objRequest['id']?>&Mode=<?=$Mode?>"  class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจว่าจะลบข้อมูลนี้?')"><i class="fa fa-trash-o fa-lg"></i></a>            </td>
            </tr>
        <?php } ?>
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


