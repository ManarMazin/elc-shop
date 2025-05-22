<?php
session_start();

if (@$_SESSION['islogin'] != 'yes') {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// بيانات المنتج تأتي عبر GET أو POST (للتجربة هنا GET)
if (isset($_GET['protoken'])) {
    $protoken = $_GET['protoken'];
    $name = $_GET['name'] ?? 'منتج تجريبي';
    $price = floatval($_GET['price'] ?? 0);
    $img = $_GET['img'] ?? 'default.jpg';
    $qty = intval($_GET['qty'] ?? 1);

    // استخدم $protoken كمفتاح داخل السلة
    $_SESSION['cart'][$protoken] = [
        'shop_token' => uniqid('shop_'),  // رمز فريد للسلة
        'shop_protoken' => $protoken,
        'shop_name' => $name,
        'shop_price' => $price,
        'shop_img' => $img,
        'shop_qty' => $qty,
    ];

    echo "تم إضافة المنتج إلى السلة.<br/>";
    echo '<a href="shop.php">اذهب للسلة</a>';
} else {
    echo "لا يوجد منتج محدد للإضافة.";
}
?>
