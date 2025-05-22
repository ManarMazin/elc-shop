<?php
session_start();

if (isset($_SESSION['islogin']) && $_SESSION['islogin'] == 'yes') {
    echo '<meta http-equiv="refresh" content="0, url=index.php"/>';
    exit();
}

require "manar.php";
global $connect;
mysqli_set_charset($connect, 'utf8');

$post01 = $_POST['get01'] ?? '';  // اسم المستخدم
$post02 = $_POST['get02'] ?? '';  // البريد الإلكتروني
$post03 = $_POST['get03'] ?? '';  // كلمة المرور
$post04 = $_POST['get04'] ?? '';  // العمر
$post05 = $_POST['get05'] ?? '';  // العنوان
$post06 = $_POST['get06'] ?? '';  // رقم الهاتف

$Token = date('ymdhis');
$Rand = rand(100, 200);
$NewToken = $Token . $Rand;

if (!empty($post05)) {
    // استعلام إضافة المستخدم إلى قاعدة البيانات
    $Wel = "INSERT INTO users (
        user_token,
        user_name,
        user_email,
        user_password,
        user_birthday,
        user_add,
        user_num
    ) VALUES (
        '$NewToken',
        '$post01',
        '$post02',
        '$post03',
        '$post04',
        '$post05',
        '$post06'
    )";

    if (mysqli_query($connect, $Wel)) {
        // إعدادات الجلسة بعد إضافة المستخدم
        $_SESSION['Token'] = $NewToken;
        $_SESSION['Name'] = $post01;
        $_SESSION['islogin'] = 'yes';

        echo '
        <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
            <img width="200" src="11.png" />
            <p>شكراً تم انشاء الحساب بنجاح</p>
        </div>
        <meta http-equiv="refresh" content="3, url=index.php"/>
        ';
        exit();
    } else {
        echo '
        <div style="text-align:center;color:#f00;margin: 50px auto;font-weight:bold;">
            <p>حدث خطأ أثناء إنشاء الحساب. حاول مرة أخرى لاحقاً.</p>
        </div>';
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>إنشاء حساب</title>
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

    .register-container {
      background-color: #ffffff;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 520px;
    }

    .register-container h2 {
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

    .register-btn {
      background-color:rgb(107, 30, 28);
      color: #fff;
      border: none;
      padding: 14px;
      width: 100%;
      font-size: 18px;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .register-btn:hover {
      background-color:white;
      color:rgb(107, 30, 28);
      

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

<form class="register-container" method="post">
  <h2><i class="fa-solid fa-user-plus"></i> إنشاء حساب</h2>

  <div class="form-group">
    <input type="text" name="get01" placeholder="اسم المستخدم" required>
    <i class="fa-solid fa-user-tie"></i>
  </div>

  <div class="form-group">
    <input type="email" name="get02" placeholder="البريد الإلكتروني" required>
    <i class="fa-solid fa-envelope"></i>
  </div>

  <div class="form-group">
    <input type="password" name="get03" placeholder="كلمة المرور" required>
    <i class="fa-solid fa-lock"></i>
  </div>

  <div class="form-group">
    <input type="text" name="get04" placeholder="العمر" required>
    <i class="fa-solid fa-cake-candles"></i>
  </div>

  <div class="form-group">
    <input type="text" name="get05" placeholder="العنوان" required>
    <i class="fa-solid fa-location-dot"></i>
  </div>

  <div class="form-group">
    <input type="text" name="get06" placeholder="رقم الهاتف" required>
    <i class="fa-solid fa-phone"></i>
  </div>

  <button class="register-btn" type="submit" name="get07">إنشاء حساب</button>

  <div class="links">
    <a href="index.php">الرجوع</a>
    <a href="login.php">تسجيل الدخول</a>
  </div>
</form>

</body>
</html>
