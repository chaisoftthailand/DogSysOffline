<?@ob_start();?>
<?@session_start();?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>รายงานระบบคลินิกรักษาสัตว์</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<?php
include 'navbar.php';
include 'dbconnect.php';
include 'function.php';

// ดึง clinic_id จาก session
$clinic_id = $_SESSION['clinic_id'] ?? 0;
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$report_type = $_GET['report_type'] ?? 'dog';
?>
<h3 class="mb-4 text-center">📊 รายงานระบบคลินิกรักษาสัตว์</h3>
<div class="row">
    <div class="col-md-4 mb-2">
        <a href="?report_type=dogs&Mode=<?=$Mode?>" class="btn btn-outline-primary btn-block">🐶 รายงานข้อมูลสัตว์</a>
    </div>
    <div class="col-md-4 mb-2">
        <a href="?report_type=treatments&Mode=<?=$Mode?>" class="btn btn-outline-success btn-block">💉 รายงานการรักษาพยาบาล</a>
    </div>
    <div class="col-md-4 mb-2">
        <a href="?report_type=appointments&Mode=<?=$Mode?>" class="btn btn-outline-warning btn-block">📅 รายงานการนัดหมาย</a>
    </div>
</div>

<div class="container mt-4" align=center>
    <!-- ฟอร์มเลือกประเภทและช่วงวันที่ -->
    <form method="GET" class="form-inline mb-3">
        <label class="mr-2">จากวันที่:</label>
        <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control mr-2">
        <label class="mr-2">ถึงวันที่:</label>
        <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control mr-2">
    <hr>
        <input type="hidden" name="Mode" value="<?=$Mode?>">
        <button type="submit" class="btn btn-primary">ค้นหา</button>
    </form>

    <hr>

    <?php
    if ($report_type == 'dogs') {
        include('report_dogs.php');
    } elseif ($report_type == 'treatments') {
        include('report_treatments.php');
    } elseif ($report_type == 'appointments') {
        include('report_appointments.php');
    }
    ?>
<div class="container mt-4">
    <h3 class="text-center">📊 รายงานภาพรวมคลินิก</h3>

    <div class="row text-center">
        <div class="col-md-4">
        <div class="card p-3 mb-3 shadow-sm custom-card">
                <h5>🐶 จำนวนสัตว์ในระบบ</h5>
                <p>
                    <?php
                    $sql_q1="SELECT COUNT(*) as total FROM dogs";
                    if($_SESSION['role']==2){ 
                        $sql_q1.=" WHERE clinic_id=$clinic_id";
                    }
                    $q1 = mysqli_query($objCon,$sql_q1);
                    $r1 = mysqli_fetch_assoc($q1);
                    echo number_format($r1['total']) . " ตัว";
                    ?>
                </p>
                <!-- ตัวอย่างการใช้ปุ่ม -->
                <button class="btn-gray">ดูรายละเอียด</button>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card p-3 mb-3 shadow-sm custom-card">
                <h5>💉 จำนวนการรักษา</h5>
                <p>
                    <?php
                    $sql_q2="SELECT COUNT(*) as total FROM treatments";
                    if($_SESSION['role']==2){ 
                        $sql_q2.=" WHERE clinic_id=$clinic_id";
                    }                    
                    $q2 = mysqli_query($objCon,$sql_q2);
                    $r2 = mysqli_fetch_assoc($q2);
                    echo number_format($r2['total']) . " ครั้ง";
                    ?>
                </p>
                <!-- ตัวอย่างการใช้ปุ่ม -->
                <button class="btn-gray">ดูรายละเอียด</button>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card p-3 mb-3 shadow-sm custom-card">
                <h5>📅 การนัดหมายทั้งหมด</h5>
                <p>
                    <?php
                    $sql_q3="SELECT COUNT(*) as total FROM appointments";
                    if($_SESSION['role']==2){ 
                        $sql_q3.=" WHERE clinic_id=$clinic_id";
                    }                    
                    $q3 = mysqli_query($objCon,$sql_q3);
                    $r3 = mysqli_fetch_assoc($q3);
                    echo number_format($r3['total']) . " รายการ";
                    ?>
                </p>
                <!-- ตัวอย่างการใช้ปุ่ม -->
                <button class="btn-gray">ดูรายละเอียด</button>
            </div>
        </div>
    </div>
</div>
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

</body>
</html>
