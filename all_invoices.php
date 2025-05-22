<?php
require "manar.php";
mysqli_set_charset($connect, 'utf8');

// احصل على التوكن من الرابط، أو اجعل القيمة فارغة لعرض جميع الطلبات
$user_token = $_GET['user_token'] ?? '';

// استعلام جلب الفواتير
if ($user_token) {
    // عرض طلبات مستخدم محدد
    $query = "
        SELECT o.order_id, o.user_token, u.user_name AS user_name
        FROM `order` o
        JOIN users u ON o.user_token = u.user_token
        WHERE o.user_token = '" . mysqli_real_escape_string($connect, $user_token) . "'
        ORDER BY o.order_date DESC
    ";
} else {
    // عرض جميع الطلبات لجميع المستخدمين
    $query = "
        SELECT o.order_id, o.user_token, u.user_name AS user_name
        FROM `order` o
        JOIN users u ON o.user_token = u.user_token
        ORDER BY o.order_date DESC
        LIMIT 100
    ";
}

$result = mysqli_query($connect, $query) or die("خطأ في استعلام الطلبات: " . mysqli_error($connect));
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>جميع فواتير المستخدم</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; }
        table { border-collapse: collapse; width: 90%; margin: auto; text-align: center; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        th { background-color: #f0f0f0; }
        a { color:rgb(116, 40, 51); text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
        h2 { text-align: center; margin-top: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>🧾 فواتير المستخدم - تفاصيل الطلبات</h2>

<table>
    <tr>
        <th>رقم الطلب</th>
        <th>رمز المستخدم</th>
        <th>اسم المستخدم</th>
        <th>عرض تفاصيل الطلب</th>
    </tr>

<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $order_id = htmlspecialchars($row['order_id']);
        $token = htmlspecialchars($row['user_token']);
        $user_name = htmlspecialchars($row['user_name']);

        echo "<tr>
            <td>$order_id</td>
            <td><a href='all_invoices.php?user_token=" . urlencode($token) . "'>$token</a></td>
            <td><a href='all_invoices.php?user_token=" . urlencode($token) . "'>$user_name</a></td>
            <td><a href='order_details.php?order_id=" . urlencode($order_id) . "'>عرض التفاصيل</a></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>لا توجد طلبات لهذا المستخدم.</td></tr>";
}
?>

</table>

</body>
</html>


