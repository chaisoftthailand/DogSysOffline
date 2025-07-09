<?php
$strSQL = "SELECT t.*, d.dog_name FROM treatments t LEFT JOIN dogs d ON t.dog_id = d.dog_id ";
if($_SESSION['role']==2){ //ระดับคลินิค
    $strSQL.="WHERE t.clinic_id = '$clinic_id'";
}
if (!empty($start_date) && !empty($end_date)) {
    $strSQL .= " AND t.created_at BETWEEN '$start_date' AND '$end_date'";
}
$strSQL.= " ORDER BY t.created_at DESC";
//echo $strSQL;
$objQuery = mysqli_query($objConn, $strSQL);
?>

<h5>💉รายงานการรักษาพยาบาล</h5>
<table class="table table-bordered table-sm" id="DataTable">
    <thead>
        <tr>
            <th>วันที่</th>
            <th>ชื่อสัตว์</th>
            <th>อาการ</th>
            <th>การรักษา</th>
            <th>ผู้รักษา</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($objQuery)) { ?>
        <tr>
            <td><?= $row['created_at'] ?></td>
            <td><?= $row['dog_name'] ?></td>
            <td><?= nl2br($row['symptom']) ?></td>
            <td><?= nl2br($row['treatment_detail']) ?></td>
            <td><?= $row['vet_name'] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
