<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ระบบบริหารจัดการคลินิกรักษาสัตว์ - หมอบุญรักสัตว์</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun&display=swap');

    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to right, #e0f7fa, #ffffff);
      font-family: 'Sarabun', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background: #ffffff;
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .login-box img {
      max-width: 120px;
      margin-bottom: 15px;
    }

    .login-box h2 {
      margin-bottom: 20px;
      color: #0277bd;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #b0bec5;
      border-radius: 8px;
      font-size: 16px;
    }

    .login-box button {
      background-color: #0288d1;
      color: white;
      border: none;
      padding: 12px;
      width: 100%;
      font-size: 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
    }

    .login-box button:hover {
      background-color: #0277bd;
    }

    .footer {
      margin-top: 15px;
      font-size: 12px;
      color: #78909c;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <img src="images/clinic_logo.png" alt="Clinic Logo">
    <h2>ระบบบริหารจัดการคลินิกรักษาสัตว์</h2>
    <form action="login_process.php" method="post">
      <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
      <input type="password" name="password" placeholder="รหัสผ่าน" required>
      <button type="submit">เข้าสู่ระบบ</button>
      
      <div class="register-link">
        ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิกที่นี่</a>
    </div>
    </form>
    <div class="footer">© หมอบุญรักสัตว์ - 2025</div>
  </div>

</body>
</html>
