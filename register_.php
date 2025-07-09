<?@ob_start();?>
<?@session_start();?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>สมัครสมาชิก</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
include 'dbconnect.php';
include 'function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password=dec_enc(encrypt, $_POST["password"]);
    $clinic_id = intval($_POST['clinic_id']);
    $email = trim($_POST['email']);
    $strSQL = "INSERT INTO user (username, password, clinic_id, email, role) VALUES ('$username', '$password', $clinic_id, '$email',1)";
        $objQuery = mysqli_query($objCon, $strSQL);
    if ($objQuery) {
        echo "<div class='alert alert-success text-center'>👤 สมัครสมาชิกเรียบร้อยแล้ว <a href='index.php'>เข้าสู่ระบบ</a></div>";
    } else {
        echo "<div class='alert alert-danger text-center'>👤 เกิดข้อผิดพลาด: " . mysqli_error($objCon) . "</div>";
    }
}
?>
  <body class="bg-dark text-white">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0" style="background-color: #1e1e1e;">
          <div class="card-body p-4">
            <h3 class="text-center mb-4 text-white">สมัครสมาชิก</h3>
            <form method="post">
              <div class="mb-3">
                <label class="form-label text-white">ชื่อผู้ใช้งาน</label>
                <input type="text" name="username" class="form-control bg-dark text-white border-secondary" required>
              </div>

              <div class="mb-3">
                <label class="form-label text-white">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
              </div>

              <div class="mb-3">
                <label class="form-label text-white">Email</label>
                <input type="text" name="email" class="form-control bg-dark text-white border-secondary" required>
              </div>

              <div class="mb-3">
                <label class="form-label text-white">เลือกคลินิก</label>
                <select name="clinic_id" class="form-select bg-dark text-white border-secondary" required>
                  <option value="" class="text-secondary">-- เลือกคลินิก --</option>
                  <?opt_clinic(1,$objCon)?>
                </select>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-secondary">สมัครสมาชิก</button>
              </div>
            </form>
            <p class="text-center mt-3 mb-0">
              <a href="index.php" class="text-light">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
