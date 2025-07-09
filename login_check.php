<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Prompt&display=swap">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 400px;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0066cc;
        }
        label {
            margin-top: 10px;
            display: block;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }
        button:hover {
            background-color: #004c99;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
        .register-link a {
            color: #0066cc;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <form action="login_process.php" method="post" class="login-box">
        <h2>เข้าสู่ระบบ</h2>
        <label for="username">ชื่อผู้ใช้</label>
        <input type="text" id="username" name="username" required>

        <label for="password">รหัสผ่าน</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">เข้าสู่ระบบ</button>

        <div class="register-link">
            ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิกที่นี่</a>
        </div>
    </form>
</body>
</html>
