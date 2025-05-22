<?php
session_start();
require "manar.php";
global $connect;
mysqli_set_charset($connect, 'utf8');



// استقبال البيانات من النموذج
@$p_name     = $_POST['get01'];
@$p_price    = $_POST['get02'];
@$p_cat      = $_POST['get03'];
@$p_info     = $_POST['get04'];
@$submit     = $_POST['get06'];

$image_name  = @$_FILES['get05']['name'];
$image_temp  = @$_FILES['get05']['tmp_name'];
$image_ext   = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
$new_image   = uniqid('imgproject_', true) . '.' . $image_ext;

// إنشاء رمز مميز للمنتج
$token       = date('ymdhis') . rand(100, 200);

// إذا تم الضغط على زر الإرسال
if (isset($submit)) {
    if (!empty($image_name)) {
        move_uploaded_file($image_temp, "imgproject/$new_image");
    } else {
        $new_image = '';
    }

    $insert = "INSERT INTO product 
        (p_token, p_name, p_price, p_cat, p_info, p_img)
        VALUES ('$token', '$p_name', '$p_price', '$p_cat', '$p_info', '$new_image')";

    if (mysqli_query($connect, $insert)) {
        echo '
        <div style="text-align:center; color:#080; margin:50px auto; font-weight:bold;">
            <img width="200" src="11.png" />
            <p>شكراً لك، تم إضافة المنتج بنجاح.</p>
        </div>
        <meta http-equiv="refresh" content="3; url=admin.php">
        ';
        exit();
    }
}

  
            //setcookie("Token",$NewToken,time()+(86400),"/");
            //setcookie("Name",$post01,time()+(86400),"/");
            //setcookie("islogin","yes",time()+(86400),"/");

?>

  
  
  







        <!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة منتج</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/all.css" rel="stylesheet"/>
</head>
<body class="style18">
    <table class="style12">
        <tr>
            <td>إضافة المنتجات</td>
            <td><a class="style13" href="admin.php"><i class="fa-solid fa-arrow-left"></i></a></td>
        </tr>
    </table>

    <form method="post" action="" enctype="multipart/form-data">
        <div class="style01">
            <input name="get01" class="style02" type="text" placeholder="اكتب اسم المنتج" required />
            <i class="fa-solid fa-box style07"></i>

            <input name="get02" class="style02" type="text" placeholder="اكتب سعر المنتج" required />
            <i class="fa-solid fa-dollar-sign style07"></i>

            <input name="get03" class="style02" type="text" placeholder="اكتب تصنيف المنتج" required />
            <i class="fa-solid fa-tags style07"></i>

            <textarea name="get04" class="style17" placeholder="اكتب وصف المنتج" required></textarea>

            <input name="get05" type="file" required />
        </div>

        <div class="style03">
            <div class="top">
                <input name="get06" class="style08" type="submit" value="إضافة المنتج" />
            </div>
        </div>
    </form>
</body>
</html>
