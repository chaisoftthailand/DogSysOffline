<?php @ob_start(); ?>
<?php @session_start(); ?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>บันทึกการนัดหมาย</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/LocalStorage_Appointment.js"></script>
</head>
<body>
<?php
include 'navbar.php';
include 'dbconnect.php';
include 'function.php';

if (!isset($_SESSION['clinic_id']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] <= 2) {
    $clinic_id = $_SESSION["clinic_id"];
    $user_id = $_SESSION['user_id'];    
} else {
    $clinic_id = $_POST["clinic_id"];
}
    include 'Offline.php';
// ✅ ปุ่มบันทึกแบบปกติ (Online)
echo isset($_POST['save']).'<br>';
if (isset($_POST['save'])) {
    $appointment_id = $_POST['appointment_id'];    
    $dog_id = $_POST['dog_id'];
    $clinic_id = $_POST['clinic_id'];
    $appointment_date = $_POST['appointment_date'];
    $description = $_POST['description'];

    if ($appointment_id == '') {
        $strSQL = "INSERT INTO appointments (dog_id, clinic_id, appointment_date, description)
                   VALUES ('$dog_id', '$clinic_id', '$appointment_date', '$description')";
    } else {
        $strSQL = "UPDATE appointments SET 
                    dog_id='$dog_id',
                    clinic_id='$clinic_id',
                    appointment_date='$appointment_date',
                    description='$description'
                   WHERE appointment_id='$appointment_id'";
    }
    echo $strSQL;
    mysqli_query($objCon, $strSQL);
    header("Location:appointment_register.php?Mode=$Mode");
    exit();    
} 

// ✅ ลบข้อมูล
if (isset($_GET['delete'])) {
    $appointment_id = $_GET['delete'];
    $strSQL = "DELETE FROM appointments WHERE appointment_id='$appointment_id'";
    mysqli_query($objCon, $strSQL);
    echo "<script>window.location='appointment_register.php?Mode=$Mode';</script>";
    exit();
}

// ✅ โหลดข้อมูลเพื่อแก้ไข
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit = false;
    
    $strSQL="SELECT * FROM appointments WHERE appointment_id=$edit_id";
    echo $strSQL;
    $result = mysqli_query($objCon, $strSQL);
    if ($row = mysqli_fetch_assoc($result)) {
        $edit = true;
        extract($row);
    }
}
?>

<div class="container mt-4">
<div class="card p-4 mb-4 shadow-sm">
    <h3 class="text-center">📅 บันทึกการนัดหมาย <sup><font color="#FF0000"><?php if($appointment_id!='') { ?>(appointment_id=<?= $appointment_id ?>)<?php } ?></font></sup></h3>
    <form method="POST" action="appointment_register.php" onsubmit="return handleAppointmentSubmit(event, this);" enctype="multipart/form-data">
        <?php if ($_SESSION['role'] == 3) { ?>
            <div class="mb-3">
                <label class="form-label">เลือกคลินิก</label>
                <select name="clinic_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- เลือกคลินิก --</option>
                    <?php opt_clinic($clinic_id, $objCon); ?>
                </select>
            </div>
        <?php } ?>

        <div class="form-group">
            <?php $dogs = mysqli_query($objCon, "SELECT * FROM dogs WHERE clinic_id='$clinic_id'"); ?>
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
            <label>วันเวลาเข้าพบ</label>
            <input type="datetime-local" name="appointment_date" class="form-control" value="<?= $appointment_date ?>" required>
        </div>

        <div class="form-group">
            <label>รายละเอียด</label>
            <textarea name="description" class="form-control" rows="3"><?= $description ?></textarea>
        </div>

        <div class="text-center">
            <input type="hidden" name="appointment_id" value="<?= $appointment_id ?>">
            <input type="hidden" name="clinic_id" value="<?= $clinic_id ?>">            
            
            <button type="submit" name="save" class="btn btn-primary"><?= $edit ? "อัปเดตข้อมูล" : "บันทึกข้อมูล" ?></button>                        
        </div>
    </form>
</div>

<!-- ✅ ตารางนัดหมาย -->
<div class="card p-4 mb-4 shadow-sm">        
<table class="table table-bordered table-responsive-sm" id="DataTable">
    <thead class="thead-light">
        <tr>
            <th>ชื่อสุนัข</th>
            <th>วันเวลา</th>
            <th>รายละเอียด</th>                
            <th>แก้ไข</th>                
            <th>ลบ</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($_SESSION['role'] <= 2) {
        $strSQL = "SELECT a.*, d.dog_name FROM appointments a 
                   LEFT JOIN dogs d ON a.dog_id = d.dog_id 
                   WHERE a.clinic_id = '$clinic_id' 
                   ORDER BY a.appointment_date DESC";           
    } else {
        $strSQL = "SELECT a.*, d.dog_name FROM appointments a 
                   LEFT JOIN dogs d ON a.dog_id = d.dog_id 
                   ORDER BY a.appointment_date DESC";
    }
    $objQuery = mysqli_query($objCon, $strSQL);
    while ($row = mysqli_fetch_assoc($objQuery)) { ?>
        <tr>
            <td><?= $row['dog_name'] ?></td>
            <td><?= date('d/m/Y h:i A', strtotime($row['appointment_date'])) ?></td>
            <td><?= nl2br($row['description']) ?></td>
            <td align="center">
                <a href="?edit=<?= $row['appointment_id'] ?>&Mode=<?= $Mode ?>" class="btn btn-sm btn-info">
                    <i class='fa fa-edit fa-lg'></i>
                </a>
            </td>
            <td align="center">
                <a href="?delete=<?= $row['appointment_id'] ?>&Mode=<?= $Mode ?>" class="btn btn-sm btn-danger" onclick="return confirm('ลบนัดหมายนี้?')">
                    <i class='fa fa-trash-o fa-lg'></i>
                </a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>

<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$('#DataTable').DataTable({
    "pageLength": 10,
    "columnDefs": [{
        "targets": 'no-sort',
        "orderable": false
    }]
});
</script>
</body>
</html>
