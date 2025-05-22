<?php
session_start(); 
require "manar.php";
global $connect;
mysqli_set_charset($connect, 'utf8');

@$post01 = $_POST['get01'];
@$post02 = $_POST['get02'];
@$post03 = $_POST['get03'];

if (isset($post03)) {
    $abdo = "SELECT * FROM users WHERE user_name='$post01' AND user_password='$post02'";
    $abdoRun = mysqli_query($connect, $abdo);
    if (mysqli_num_rows($abdoRun) > 0) {
        $user = mysqli_fetch_assoc($abdoRun);

        // حفظ الجلسة
        $_SESSION['islogin'] = 'yes';
        $_SESSION['Token'] = $user['user_token']; // تأكد أن هذا الحقل موجود في قاعدة البيانات

        // التوجيه للصفحة الأصلية إن وُجدت
        if (isset($_SESSION['return_url'])) {
            $redirect = $_SESSION['return_url'];
            unset($_SESSION['return_url']);
            header("Location: $redirect");
            exit();
        } else {
            // توجيه افتراضي إلى الرئيسية
            header("Location: index.php");
            exit();
        }
    } else {
        echo '
            <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                <img width="200" src="11.png" />
                <p>عذراً لا يتوفر حساب بهذه المعلومات</p>
            </div>
            <meta http-equiv="refresh" content="2; url=login.php"/>
        ';
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- خطوط وأيقونات -->
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
  <link href="fontawesome/css/solid.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Cairo', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
      margin: 0;
    }

    .login-container {
      background-color: #ffffff;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 520px;
    }

    .login-container h2 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    .form-group {
      position: relative;
      margin-bottom: 20px;
    }

    .form-group input {
      width: 100%;
      padding: 14px 10px 14px 10px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: #fdfdfd;
      color: #333;
      font-size: 16px;
      transition: border 0.3s ease;
    }

    .form-group input:focus {
      border-color: rgb(107, 30, 28);
      outline: none;
      background-color: #fff;
    }

    .form-group i {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: rgb(107, 30, 28);
    }

    .login-btn {
      background-color: rgb(107, 30, 28);
      color: #fff;
      border: none;
      padding: 14px;
      width: 100%;
      font-size: 18px;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .login-btn:hover {
      background-color: white;
      color: rgb(107, 30, 28);
    }

    .links {
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
      font-size: 15px;
    }

    .links a {
      color: #555;
      text-decoration: none;
    }

    .links a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<form class="login-container" method="post">
  <h2><i class="fa-solid fa-lock"></i> تسجيل الدخول</h2>

  <div class="form-group">
    <input type="text" name="get01" placeholder="اسم المستخدم" required>
    <i class="fa-solid fa-user-tie"></i>
  </div>

  <div class="form-group">
    <input type="password" name="get02" placeholder="كلمة المرور" required>
    <i class="fa-solid fa-lock"></i>
  </div>

  <button class="login-btn" type="submit" name="get03">تسجيل الدخول</button>

  <div class="links">
    <a href="index.php">الرجوع</a>
    <a href="signup.php">إنشاء حساب</a>
  </div>
</form>

</body>
</html>


