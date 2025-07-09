<?php
$strSQL="SELECT a.*, d.dog_name FROM appointments a LEFT JOIN dogs d ON a.dog_id = d.dog_id ";
if($_SESSION['role']==2){ //ระดับคลินิค
$strSQL.="WHERE a.clinic_id = '$clinic_id'";
}

if (!empty($start_date) && !empty($end_date)) {
    $strSQL .= " AND a.appointment_date BETWEEN '$start_date' AND '$end_date'";
}
$strSQL .= " ORDER BY a.appointment_date DESC";
$objQuery = mysqli_query($objConn, $strSQL);
?>

<h5>📅 รายงานการนัดหมาย</h5>
<table class="table table-bordered table-sm" id="DataTable">
    <thead>
        <tr>
            <th>วันที่นัด</th>
            <th>ชื่อสัตว์</th>
            <th>เวลา</th>
            <th>วัตถุประสงค์</th>
            <th>หมายเหตุ</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($objQuery)) { ?>
        <tr>
            <td><?= $row['appointment_date'] ?></td>
            <td><?= $row['dog_name'] ?></td>
            <td><?= $row['appointment_time'] ?></td>
            <td><?= $row['purpose'] ?></td>
            <td><?= $row['note'] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
