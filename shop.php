<?php
session_start();
if (@$_SESSION['islogin'] != 'yes') {
    echo '<meta http-equiv="refresh" content="0; url=login.php"/>';
    exit();
}

require "manar.php";
mysqli_set_charset($connect, 'utf8');

$TokenUser = $_SESSION['Token'];

// حذف منتج من السلة
if (@$_GET['D'] == 'D') {
    $tokenproject = @$_GET['T'];
    $DD = "DELETE FROM shoppingcarts WHERE shop_token='$tokenproject' AND shop_usertoken='$TokenUser'";
    mysqli_query($connect, $DD);
    echo '
        <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
            <img width="200" src="11.png" />
            <p>تم حذف المنتج من سلة المشتريات بنجاح</p>
        </div>
        <meta http-equiv="refresh" content="3; url=shop.php"/>
    ';
    exit();
}

// تثبيت الطلب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $selectCart = "SELECT * FROM shoppingcarts WHERE shop_usertoken='$TokenUser'";
    $cartResult = mysqli_query($connect, $selectCart);

    $total = 0;
    $cartItems = [];

    while ($row = mysqli_fetch_assoc($cartResult)) {
        $price = floatval($row['shop_price']);
        $qty = intval($row['shop_qty']);
        $subtotal = $price * $qty;

        $total += $subtotal;
        $cartItems[] = [
            'p_token' => $row['shop_protoken'],
            'p_name' => $row['shop_name'],
            'p_price' => $price,
            'p_qty' => $qty,
            'subtotal' => $subtotal
        ];
    }

    if (count($cartItems) > 0) {
        $insertOrder = "INSERT INTO `order` (user_token, order_status, total_price) 
                        VALUES ('$TokenUser', 'قيد المعالجة', '$total')";
        if (mysqli_query($connect, $insertOrder)) {
            $orderId = mysqli_insert_id($connect);

            foreach ($cartItems as $item) {
                $insertDetail = "INSERT INTO order_details 
                    (order_id, p_token, p_name, p_price, p_qty, subtotal) 
                    VALUES (
                        '$orderId', 
                        '{$item['p_token']}', 
                        '{$item['p_name']}', 
                        '{$item['p_price']}', 
                        '{$item['p_qty']}', 
                        '{$item['subtotal']}')";
                mysqli_query($connect, $insertDetail);
            }

            mysqli_query($connect, "DELETE FROM shoppingcarts WHERE shop_usertoken='$TokenUser'");

            echo '<div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                    <img width="200" src="11.png" />
                    <p>تم تثبيت الطلب بنجاح</p>
                  </div>
                  <meta http-equiv="refresh" content="3; url=shop.php"/>';
            exit();
        } else {
            echo "حدث خطأ أثناء إنشاء الطلب: " . mysqli_error($connect);
        }
    } else {
        echo "السلة فارغة!";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>سلة المشتريات</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />
    <style>
        .cart-container {
            width: 90%;
            margin: auto;
            text-align: center;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9f9f9;
            margin: 15px 0;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 0px 5px #ccc;
        }
        .product-details {
            text-align: right;
            flex: 1;
            margin-right: 15px;
        }
        .product-details p {
            margin: 5px 0;
        }
        .product-image img {
            width: 120px;
            height: auto;
            border-radius: 8px;
        }
        .btn-delete, .btn-view {
            display: inline-block;
            margin: 5px 5px;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-delete {
            background-color:rgb(92, 30, 33);
            color: white;
        }
        .btn-view {
            background-color:rgb(92, 30, 33);
            color: white;
        }
        .total-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }
        .place-order {
            background: rgb(92, 30, 33);
            color: white;
            padding: 10px 30px;
            font-weight: bold;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="cart-container">

<button type="button" onclick="window.location.href='index.php';" class="place-order" style="
    background-color: rgb(92, 30, 33);
    color: white;
    border: none;
    padding: 20px 40px;
    border-radius: 10px;
    border-color:black;
    cursor: pointer;
    font-weight: bold;
    margin-bottom: 20px;
">
    الرجوع
</button>


  
    
    <h1>🛒 سلة المشتريات</h1>

    <form method="post">
        <?php
        $TotalPrice = 0;
        $showdb = "SELECT * FROM shoppingcarts WHERE shop_usertoken='$TokenUser'";
        $showdbRun = mysqli_query($connect, $showdb);

        while ($row = mysqli_fetch_assoc($showdbRun)) {
            $price = floatval($row['shop_price']);
            $qty = intval($row['shop_qty']);
            $subtotal = $price * $qty;
            $TotalPrice += $subtotal;

            echo '
            <div class="cart-item">
                <div class="product-details">
                    <p><strong>' . $row['shop_name'] . '</strong></p>
                    <p>السعر: ' . $row['shop_price'] . ' دينار</p>
                    <p>الكمية: ' . $row['shop_qty'] . '</p>
                    <p>الإجمالي: ' . $subtotal . ' دينار</p>
                    <a href="shop.php?D=D&T=' . $row['shop_token'] . '" class="btn-delete">حذف</a>
                    <a href="project1.php?T=' . $row['shop_protoken'] . '" class="btn-view">مشاهدة</a>
                </div>
                <div class="product-image">
                    <img src="imgproject/' . $row['shop_img'] . '" alt="صورة المنتج">
                </div>
            </div>';
        }
        ?>
        <p class="total-price">💰 إجمالي المبلغ: <?php echo $TotalPrice; ?> دينار</p>

        <button type="submit" name="place_order" class="place-order">🧾 تثبيت الطلب</button>
        <p style="text-align:center; margin-top: 20px;">
        <a href="orders_history.php" style="color:rgb(92, 30, 33); font-weight:bold; text-decoration:none;">
            عرض الطلبات السابقة 🛒📦
        </a>
    </p>
    </form>
</div>

</body>
</html>
