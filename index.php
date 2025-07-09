<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <title>ระบบบริหารจัดการคลินิกรักษาสัตว์</title>     
</head>
<link rel="stylesheet" href="http://183.88.236.186:2454/DogSys/css/loginpage.css">  

<body>  
<div class="login-box">
  <h4>🐶 ระบบบริหารคลินิกรักษาสัตว์</h4>
  <form action="login_process.php" method="post">    
    <select name="username" required>      
      <option value="admin">admin</option>        
      <option value="clinic1">clinic1</option>
      <option value="user1">user1</option>
    </select>
       <input type="password" name="password" placeholder="รหัสผ่าน" value='1111' required>

    <button type="submit">เข้าสู่ระบบ</button>

      <div class="register-link"><br>
        ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิกที่นี่</a>
    </div>
    <a href="http://183.88.236.186:2454/index.php" class="btn btn-default btn-sm">หน้าหลัก</a>
    </form>    
    <div class="footer">© ระบบคลินิครักษาสัตว์ version 1.0/2568</div>
  </div>
    
</body>
</html>

