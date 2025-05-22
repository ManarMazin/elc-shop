<?php
session_start();
if (!isset($_SESSION['islogin']) || $_SESSION['islogin'] !== 'yes') {
    $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}


require "manar.php";
global $connect;
mysqli_set_charset($connect, 'utf8');

$showdb = "SELECT * FROM product";
$showdbRun = mysqli_query($connect, $showdb);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض المنتجات</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/brands.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/duotone-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-duotone-thin.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fff0f5;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h2 {
            color:rgb(96, 16, 22);
        }

        .back-link {
            text-decoration: none;
            color: rgb(96, 16, 22);
            font-weight: bold;
        }

        .products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .product-card {
    background: #ffffff;
    border: 1px solidrgb(240, 232, 234);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgb(96, 16, 22);
    width: 260px;
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 350px; /* تعيين الحد الأدنى للارتفاع */
    transition: transform 0.3s;
    overflow: hidden; /* لتجنب تجاوز المحتوى */
}

.product-card img {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 15px;
}

.product-card h3 {
    color:rgb(96, 16, 22);
    margin: 0 0 10px;
}

.product-card p {
    color: #555;
}

.product-card a {
    margin-top: auto; /* لضمان أن الزر يكون في الأسفل */
    background: rgb(96, 16, 22); /* اللون الوردي الفاتح */
    color: white;
    padding: 12px 25px;
    text-decoration: none;
    border-radius: 30px; /* حواف دائرية */
    font-weight: bold;
    text-transform: uppercase; /* تحويل النص إلى الحروف الكبيرة */
    font-size: 14px;
    transition: all 0.3s ease;
    align-self: center;
}

.product-card a:hover {
    background:white; /* اللون الوردي الداكن */
    transform: translateY(-5px); /* تأثير تحريك الزر للأعلى عند التمرير */
    box-shadow: 0 6px 15px rgb(96, 16, 22); /* إضافة ظل عند التمرير */
}

.product-card a:active {
    transform: translateY(0); /* إعادة الزر إلى مكانه عند الضغط عليه */
}

.product-card:hover {
    transform: scale(1.05);
}

    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2> عرض جميع المنتجات</h2>
            <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> رجوع</a>
        </div>

        <div class="products-container">
            <?php
            $number = 1;
            while($showdbRow = mysqli_fetch_array($showdbRun)){
                echo '
                    <div class="product-card">
                        <img src="imgproject/'.$showdbRow['p_img'].'" alt="صورة المنتج">
                        <h3>'.$showdbRow['p_name'].'</h3>
                        <p>السعر: <strong>'.$showdbRow['p_price'].' دينار</strong></p>
                        <a href="project1.php?T='.$showdbRow['p_token'].'">شراء المنتج</a>
                    </div>';
            }
            ?>
        </div>
    </div>

</body>
</html>
