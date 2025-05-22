<?php
session_start();
require "manar.php";
global $connect;
mysqli_set_charset($connect, 'utf8');


if (!isset($_SESSION['islogin']) || $_SESSION['islogin'] !== 'yes') {

    $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

$TokenUser = $_SESSION['Token'] ?? null;
$tokenproject = $_GET['T'] ?? null;

// إنشاء توكن جديد
$Token = date('ymdhis');
$Rand = rand(100, 200);
$NewToken = $Token . $Rand;

$chang = "SELECT * FROM product WHERE p_token='$tokenproject'";
$changRun = mysqli_query($connect, $chang);

if ($changRun && mysqli_num_rows($changRun) > 0) {
    $changRow = mysqli_fetch_array($changRun);
    $proName = $changRow['p_name'];
    $proPrice = $changRow['p_price'];
    $proImg = $changRow['p_img'];

    if (isset($_POST['get05'])) {
        $qtypro = "SELECT * FROM shoppingcarts WHERE shop_usertoken='$TokenUser' AND shop_protoken='$tokenproject'";
        $qtyproRun = mysqli_query($connect, $qtypro);
        $qtyproRow = mysqli_fetch_array($qtyproRun);

        if (isset($qtyproRow['shop_qty']) && $qtyproRow['shop_qty'] > 0) {
            $totalQty = $qtyproRow['shop_qty'] + 1;
            $col = "UPDATE shoppingcarts SET shop_qty='$totalQty' WHERE shop_usertoken='$TokenUser' AND shop_protoken='$tokenproject'";
            if (mysqli_query($connect, $col)) {
                echo '
                    <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                        <img width="200" src="11.png" />
                        <p>شكراً لك تم زيادة الكمية في سلة المشتريات</p>
                    </div>
                    <meta http-equiv="refresh" content="3; url=shop.php?T=' . $tokenproject . '" />
                ';
                exit();
            }
        } else {
            $manar = "INSERT INTO shoppingcarts (shop_token, shop_usertoken, shop_protoken, shop_name, shop_price, shop_img, shop_qty)
                      VALUES ('$NewToken', '$TokenUser', '$tokenproject', '$proName', '$proPrice', '$proImg', '1')";
            if (mysqli_query($connect, $manar)) {
                echo '
                    <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                        <img width="200" src="11.png" />
                        <p>شكراً لك تم اضافة المنتج الى سلة المشتريات</p>
                    </div>
                    <meta http-equiv="refresh" content="3; url=shop.php?T=' . $tokenproject . '" />
                ';
                exit();
            }
        }
    }
} else {
    echo "لم يتم العثور على هذا المشروع.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تفاصيل شراء المنتج</title>
  <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
  <link href="fontawesome/css/solid.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #fff;
      margin: 0;
      padding: 0;
      direction: rtl;
    }

    .header {
      background-color: #6b1e1c;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 18px;
    }

    .header a {
      color: #fff;
      text-decoration: none;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      background-color: #f9f9f9;
      border-radius: 12px;
      box-shadow: 0 0 8px rgba(0,0,0,0.08);
      display: flex;
      gap: 30px;
      padding: 20px;
    }

    .product-img {
      max-width: 300px;
      height: auto;
      border-radius: 12px;
    }

    .info-section {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .info-section input,
    .info-section textarea {
      width: 100%;
      margin-bottom: 15px;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      background-color: #6b1e1c;
    }

    .info-section input:disabled,
    .info-section textarea:disabled {
      background-color: #eee;
      color: #333;
    }

    .buy-btn {
      background-color: #6b1e1c;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      width: fit-content;
      transition: 0.3s ease;
    }

    .buy-btn:hover {
      background-color: black;
    }
  </style>
</head>
<body>

  <div class="header">
    <div>تفاصيل المنتج</div>
    <a href="index.php"><i class="fa-solid fa-arrow-left"></i> رجوع</a>
  </div>

  <form method="post" enctype="multipart/form-data">
    <div class="container">
      <img class="product-img" src="imgproject/<?php echo $changRow['p_img']; ?>" alt="صورة المنتج">

      <div class="info-section">
        <input disabled type="text" value="<?php echo $changRow['p_name']; ?>" placeholder="اسم المنتج">
        <input disabled type="text" value="<?php echo $changRow['p_price']; ?>" placeholder="السعر">
        <textarea disabled rows="4" placeholder="وصف المنتج"><?php echo $changRow['p_info']; ?></textarea>
        <input name="get05" class="buy-btn" type="submit" value="شراء المنتج" />
      </div>
    </div>
  </form>

</body>
</html>




