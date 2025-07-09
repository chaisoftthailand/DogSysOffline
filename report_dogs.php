<?php
    $strSQL="SELECT * FROM dogs ";
if($_SESSION['role']==2){ //ระดับ clinic
    $strSQL.="WHERE clinic_id = '$clinic_id'";
}
    $strSQL.="ORDER BY dog_id DESC";
    $objQuery = mysqli_query($objConn, $strSQL);
?>
<h5>🐶รายงานข้อมูลสัตว์</h5>
<table class="table table-bordered table-sm" id="DataTable">
    <thead>
        <tr>
            <th>ชื่อ</th>
            <th>สายพันธุ์</th>
            <th>เพศ</th>
            <th>อายุ</th>
            <th>น้ำหนัก</th>
            <th>วันที่เพิ่ม</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($objQuery)) { ?>
        <tr>
            <td><?= $row['dog_name'] ?></td>
            <td><?= $row['dog_breed'] ?></td>
            <td><?= $row['dog_gender'] ?></td>
            <td><?= $row['dog_age'] ?></td>
            <td><?= $row['dog_weight'] ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
