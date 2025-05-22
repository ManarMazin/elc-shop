<?php
session_start();
require "manar.php";
mysqli_set_charset($connect, 'utf8');

if (!isset($_GET['token'])) {
    die("❌ رمز المستخدم غير موجود.");
}

$user_token = mysqli_real_escape_string($connect, $_GET['token']);

// جلب الطلبات الخاصة بالمستخدم مع حالة الطلب وتاريخ الطلب والإجمالي
$query = "
    SELECT order_id, order_date, order_status, total_price
    FROM orders
    WHERE user_token = '$user_token'
    ORDER BY order_date DESC
";

$result = mysqli_query($connect, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("❌ لا توجد طلبات لهذا المستخدم.");
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8" />
    <title>الفواتير الخاصة بالمستخدم</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; }
        table { border-collapse: collapse; width: 80%; margin: auto; text-align: center; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; margin-top: 20px; margin-bottom: 20px; }
        a { color: #00bcd4; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h2>📋 جميع طلبات المستخدم: <?php echo htmlspecialchars($user_token); ?></h2>

<table>
    <tr>
        <th>رقم الطلب</th>
        <th>تاريخ الطلب</th>
        <th>حالة الطلب</th>
        <th>الإجمالي</th>
        <th>عرض التفاصيل</th>
    </tr>

<?php
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['order_id']) . '</td>';
    echo '<td>' . htmlspecialchars($row['order_date']) . '</td>';
    echo '<td>' . htmlspecialchars($row['order_status']) . '</td>';
    echo '<td>' . number_format($row['total_price'], 2) . ' د.ع</td>';
    echo '<td><a href="order_details.php?order_id=' . urlencode($row['order_id']) . '">عرض التفاصيل</a></td>';
    echo '</tr>';
}
?>

</table>

</body>
</html>
