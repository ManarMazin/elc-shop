<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


    require "manar.php";
    global $connect;
    mysqli_set_charset($connect, 'utf8');
    $adQuery = "SELECT * FROM product";
    $adRun = mysqli_query($connect, $adQuery);
    $adNum = mysqli_num_rows($adRun);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الادمن</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/brands.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/duotone-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-duotone-thin.css" rel="stylesheet" />
</head>
<body class="style19">
    <table class="style12">
        <tr>
            <td>لوحة الادمن</td>
            <td><a class="style13" href="admin_logout.php"><i class="fa-solid fa-arrow-left"></i></a></td>
        </tr>
    </table>
    <div>

        <a class="style14" href="addprodect.php">اضافة منتج جديد</a>
    </div>
    <div>
        <a class="style15" href="">
            <p class="style16"><?php echo $adNum; ?></p>
            <p>اجمالي المنتجات في الموقع</p>
        </a>
    </div>
    <div>
        
        <a class="style14" class="left" href="showall.php">المنتجات</a>
    </div>
    
    <div>
        
        <a class="style14" class="left" href="order.php">الطلبات الواردة</a>
    </div>

    <div>
        
        <a class="style14" class="left" href="all_invoices.php">فواتير العملاء</a>
    </div>



</body>
</html>
