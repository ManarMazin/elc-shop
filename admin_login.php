<?php
session_start();
require 'manar.php';

$error_message = '';
$success_message = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE ad_email = '$email'";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        // قارن نصيًا بدون تشفير
        if ($password === $admin['ad_pass']) {
            $_SESSION['admin_id'] = $admin['ad_id'];
            $_SESSION['admin_email'] = $admin['ad_email'];
            header("Location: admin.php");
            exit;
        } else {
            $error_message = "❌ البريد الإلكتروني أو كلمة المرور غير صحيحة!";
        }
    } else {
        $error_message = "❌ البريد الإلكتروني أو كلمة المرور غير صحيحة!";
    }
}

if (isset($_POST['create_account'])) {
    $email = $_POST['new_email'];
    $password = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error_message = "❌ كلمة المرور غير متطابقة!";
    } else {
        $check = mysqli_query($connect, "SELECT * FROM admin WHERE ad_email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error_message = "❌ البريد مسجل مسبقًا!";
        } else {
            // تخزين كلمة المرور كنص عادي بدون تشفير
            $insert = mysqli_query($connect, "INSERT INTO admin (ad_email, ad_pass) VALUES ('$email', '$password')");
            if ($insert) {
                $success_message = "✅ تم إنشاء الحساب بنجاح!";
            } else {
                $error_message = "❌ حدث خطأ أثناء إنشاء الحساب.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول / إنشاء حساب</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background: linear-gradient(to right,white, rgb(96, 16, 22));
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 420px;
            margin: 80px auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color:rgb(96, 16, 22);
        }
        form {
            display: none;
        }
        form.active {
            display: block;
        }
        label {
            display: block;
            margin-top: 12px;
            color: #333;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background:rgb(96, 16, 22);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background:rgb(96, 16, 22);
        }
        .tabs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: #f1f1f1;
            cursor: pointer;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
        }
        .tab.active {
            background: #fff;
            color: rgb(96, 16, 22);
            border-bottom: 2px solid #fff;
        }
        .message {
            text-align: center;
            margin: 10px 0;
            color: red;
        }
        .success {
            color: green;
        }
    </style>
    <script>
        function showTab(tabName) {
            document.getElementById('login').classList.remove('active');
            document.getElementById('register').classList.remove('active');
            document.getElementById(tabName).classList.add('active');

            document.getElementById('tab-login').classList.remove('active');
            document.getElementById('tab-register').classList.remove('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }
    </script>
</head>
<body>

<div class="container">
    <div class="tabs">
        <div id="tab-login" class="tab active" onclick="showTab('login')">تسجيل الدخول</div>
        <div id="tab-register" class="tab" onclick="showTab('register')">إنشاء حساب</div>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- تسجيل الدخول -->
    <form id="login" method="POST" class="active">
        <label for="email">البريد الإلكتروني:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">كلمة المرور:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="login">تسجيل الدخول</button>
    </form>

 
    <form id="register" method="POST">

        <label for="new_email">البريد الإلكتروني:</label>
        <input type="email" id="new_email" name="new_email" required>

        <label for="new_password">كلمة المرور:</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="confirm_password">تأكيد كلمة المرور:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit" name="create_account">إنشاء الحساب</button>
    </form>
</div>

</body>
</html>



