<?php @session_start(); ?>
<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>จัดการข้อมูลการรักษาพยาบาลสัตว์</title>
<?@ob_start();?>
<?@session_start();?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>จัดการข้อมูลผู้ใช้ระบบ</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <script src="js/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/LocalStorage_Treatment.js"></script>
</head>
<body>
<?php include 'navbar.php'; include 'dbconnect.php'; include 'function.php'; ?>

<?php
$clinic_id = $_SESSION['clinic_id'];
$msg = "";

// ✅ รับ JSON แบบ Offline Sync
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

    foreach ($data as $entry) {
        $d = $entry['data'];
        $id = mysqli_real_escape_string($objCon, $entry['id']);
        $clinic_id = mysqli_real_escape_string($objCon, $d['clinic_id']);
        $dog_id = mysqli_real_escape_string($objCon, $d['dog_id']);
        $treatment_date = mysqli_real_escape_string($objCon, $d['treatment_date']);
        $symptoms = mysqli_real_escape_string($objCon, $d['symptoms']);
        $diagnosis = mysqli_real_escape_string($objCon, $d['diagnosis']);
        $treatment = mysqli_real_escape_string($objCon, $d['treatment']);
        $medication = mysqli_real_escape_string($objCon, $d['medication']);
        $doctor_name = mysqli_real_escape_string($objCon, $d['doctor_name']);
        $next_appointment = mysqli_real_escape_string($objCon, $d['next_appointment']);
        $created_at = date("Y-m-d H:i:s");
        $user_id = mysqli_real_escape_string($objCon, $d['user_id']);

        if ($entry['action'] === 'insert') {
            $sql = "INSERT INTO treatments (clinic_id, dog_id, treatment_date, symptoms, diagnosis, treatment, medication, doctor_name, next_appointment, created_at, user_id)
                    VALUES ('$clinic_id', '$dog_id', '$treatment_date', '$symptoms', '$diagnosis', '$treatment', '$medication', '$doctor_name', '$next_appointment', '$created_at', '$user_id')";
        } elseif ($entry['action'] === 'update' && !empty($id)) {
            $strSQL = "SELECT * FROM treatments WHERE treatment_id=$id";
            $objQuery = mysqli_query($objCon, $strSQL);
            if ($objRequest = mysqli_fetch_assoc($objQuery)) {
                $sql = "UPDATE treatments SET dog_id='$dog_id', treatment_date='$treatment_date', symptoms='$symptoms', diagnosis='$diagnosis',
                        treatment='$treatment', medication='$medication', doctor_name='$doctor_name', next_appointment='$next_appointment'
                        WHERE treatment_id=$id";
            }
        }

        if (isset($sql)) {
            mysqli_query($objCon, $sql);
        }
    }

    echo json_encode(['status' => 'success']);
    exit();
}
?>

<?php
// ตรวจสอบว่า login แล้วหรือยัง
if (!isset($_SESSION['clinic_id']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ตรวจสอบ session
if($_SESSION['role']<=2){
    $clinic_id = $_SESSION["clinic_id"];
    $user_id = $_SESSION['user_id'];    
} else {
    $clinic_id = $_POST["clinic_id"];
}

$treatment_id = "";
$dog_id = "";
$treatment_date = date('Y-m-d');
$next_appointment = date('Y-m-d');
$created_at = date('Y-m-d');
$symptoms = "";
$diagnosis = "";
$treatment = "";
$medication = "";
$doctor_name = "";

//------------------------------------------------ 4.Update Data to Database -----------------------------------------//
// เพิ่ม/แก้ไขข้อมูล
if (isset($_POST['save'])) {
    $treatment_id = $_POST['treatment_id'];
    $dog_id = $_POST['dog_id'];
    $treatment_date = $_POST['treatment_date'];
    $symptoms = $_POST['symptoms'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $medication = $_POST['medication'];
    $doctor_name = $_POST['doctor_name'];
    $next_appointment = $_POST['next_appointment'];
    if ($treatment_id == "") {
        $strSQL = "INSERT INTO treatments (clinic_id, dog_id, treatment_date, symptoms, diagnosis, treatment, medication, doctor_name, user_id, created_at) 
                   VALUES ('$clinic_id', '$dog_id', '$treatment_date', '$symptoms', '$diagnosis', '$treatment', '$medication', '$doctor_name', '$user_id', '$created_at')";
    } else {
        $strSQL = "UPDATE treatments SET 
                    dog_id='$dog_id',
                    treatment_date='$treatment_date',
                    symptoms='$symptoms',
                    diagnosis='$diagnosis',
                    treatment='$treatment',
                    medication='$medication',
                    doctor_name='$doctor_name',
                    next_appointment='$next_appointment'
                   WHERE treatment_id=$treatment_id";
    }
    echo $strSQL;    
    mysqli_query($objCon, $strSQL);
    header("Location: treatment_manage.php?Mode=$Mode");
    exit();
}

// ✅ 3. การลบข้อมูล
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($objCon, "DELETE FROM treatments WHERE treatment_id='$delete_id'");
    header("Location: treatment_manage.php?Mode=$Mode");
    exit();
}

// ✅ 4. แก้ไขข้อมูลเพื่อแสดงในฟอร์ม
$edit = false;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $result = mysqli_query($objCon, "SELECT * FROM treatments WHERE treatment_id='$edit_id'");
    if ($row = mysqli_fetch_assoc($result)) {
        $edit = true;        
        extract($row); // เอาค่าทั้งหมดมาเป็นตัวแปร
    }
}
?>

<div class="container mt-4">
<div class="card p-4 mb-4 shadow-sm">
    <h3 class="text-center">💉บันทึกการรักษาพยาบาลสัตว์ <sup><font color="#FF0000"> <?if($treatment_id!='') {?>(treatment_id=<?=$treatment_id?>)<?}?></font></sup></h3>
    <!-- ✅ แก้ฟอร์มให้ทำงานแบบ JS Offline-First -->
<form method="POST" action="treatment_manage.php" 
      onsubmit="return handleTreatmentSubmit(event, this);"
      enctype="multipart/form-data">     
    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
    <input type="hidden" name="treatment_id" value="<?= $treatment_id ?>">     
    <?if($_SESSION['role']==3){?>
            <div class="mb-3">
                <label class="form-label">เลือกคลินิก</label>
                <select name="clinic_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- เลือกคลินิค --</option>
			        <?opt_clinic($clinic_id,$objCon)?>
                </select>
              </div>
    <?} else {?>
        <input type="hidden" name="clinic_id" value="<?= $clinic_id ?>">            
    <?}?>
        <div class="form-group">
        
        <?$dogs = mysqli_query($objCon, "SELECT * FROM dogs WHERE clinic_id='$clinic_id'");?>
        <label>เลือกสุนัข</label>
            <select name="dog_id" class="form-control" required>
                <option value="">-- เลือกสุนัข --</option>
                <?php while ($dog = mysqli_fetch_assoc($dogs)) { ?>
                    <option value="<?= $dog['dog_id'] ?>" <?= $dog_id == $dog['dog_id'] ? 'selected' : '' ?>>
                        <?= $dog['dog_name'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>วันที่รักษา</label>
            <input type="date" name="treatment_date" class="form-control" value="<?= $treatment_date ?>" required>
        </div>
        <div class="form-group">
            <label>อาการ</label>
            <textarea name="symptoms" class="form-control"><?= $symptoms ?></textarea>
        </div>
        <div class="form-group">
            <label>วินิจฉัย</label>
            <textarea name="diagnosis" class="form-control"><?= $diagnosis ?></textarea>
        </div>
        <div class="form-group">
            <label>การรักษา</label>
            <textarea name="treatment" class="form-control"><?= $treatment ?></textarea>
        </div>
        <div class="form-group">
            <label>ยา</label>
            <input type="text" name="medication" class="form-control" value="<?= $medication ?>">
        </div>
        <div class="form-group">
            <label>ชื่อสัตวแพทย์</label>
            <input type="text" name="doctor_name" class="form-control" value="<?= $doctor_name ?>">
        </div>
        <div class="form-group">
            <label>วันนัดถัดไป</label>
            <input type="date" name="next_appointment" class="form-control" value="<?= $next_appointment ?>" required>
        </div>        
        <div class="text-center">
            <input type="hidden" name="Mode" value="<?=$Mode?>">                               
            <button type="submit" name="save" class="btn btn-primary"><?= $edit ? "อัปเดตข้อมูล" : "บันทึกข้อมูล" ?></button>                               
        </div>
    </form>
    </div>

    <!-- ✅ 00.List report -->
<div class="card p-4 mb-4 shadow-sm">        
<table class="table table-bordered table-responsive-sm" id="DataTable">
        <thead>
            <tr>
                <th>วันที่</th>
                <th>ชื่อสุนัข</th>
                <th>อาการ</th>
                <th>การรักษา</th>
                <th class="no-sort">แก้ไข</th>
                <th class="no-sort">ลบ</th>		          
            </tr>
        </thead>
        <tbody>
        <?
        if($_SESSION['role']<=2){
            $treatments = mysqli_query($objCon, "SELECT t.*, d.dog_name FROM treatments t 
                                            LEFT JOIN dogs d ON t.dog_id = d.dog_id 
                                            WHERE t.clinic_id='$clinic_id' 
                                            ORDER BY treatment_date DESC");
        } else {
            $treatments = mysqli_query($objCon, "SELECT t.*, d.dog_name FROM treatments t 
                                            LEFT JOIN dogs d ON t.dog_id = d.dog_id                                     
                                            ORDER BY treatment_date DESC");
        }
        
        while ($row = mysqli_fetch_assoc($treatments)) { ?>
            <tr>
                <td><?= $row['treatment_date'] ?><sup><font color="#FF0000"><?= $row['treatment_id'] ?></font></sup></td>
                <td><?= $row['dog_name'] ?></td>
                <td><?= htmlspecialchars(mb_substr($row['symptoms'], 0, 30)) ?>...</td>
                <td><?= htmlspecialchars(mb_substr($row['treatment'], 0, 30)) ?>...</td>
                <td align=center><a href="?edit=<?= $row['treatment_id'] ?>&Mode=<?=$Mode?>"  class="btn btn-sm btn-info"><i class='fa fa-edit fa-lg'></i></a>                </td>
                <td align=center><a href="?delete=<?= $row['treatment_id'] ?>&Mode=<?=$Mode?>" onclick="return confirm('ยืนยันลบข้อมูล')" class="btn btn-sm btn-danger"><i class='fa fa-trash-o fa-lg'></i></a>                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<br>
</body>
</html>
<script>
  window.addEventListener('online', syncTreatmentQueue);
  window.addEventListener('load', syncTreatmentQueue);
</script>
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
