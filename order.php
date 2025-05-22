<?php
session_start();

require "manar.php";
mysqli_set_charset($connect, 'utf8');

// ✅ حذف الطلب بناءً على detail_id
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($connect, "DELETE FROM order_details WHERE detail_id = $id");
    header("Location: admin_orders.php");
    exit();
}

// ✅ جلب جميع الطلبات
$ordersResult = mysqli_query($connect, "SELECT * FROM order_details ORDER BY order_id DESC");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الطلبات</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #fff;
            color: #333;
        }
        h2 {
            text-align: center;
            color: rgb(78, 14, 32);
            margin-top: 20px;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: rgb(45, 4, 21);
            color: white;
        }
        button {
            background-color: #009688;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #00796b;
        }
        a {
            color: red;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .total {
            text-align: center;
            font-weight: bold;
            margin: 25px 0;
            color: #333;
        }
    </style>
</head>
<body>

<h2>📦 الطلبات الواردة</h2>

<table>
    <tr style="background-color:#f0f0f0;">
        <th>المنتج</th>
        <th>السعر</th>
        <th>الكمية</th>
        <th>الإجمالي</th>
        <th>رمز المنتج</th>
        <th>رقم الطلب</th>
        <th>خيارات</th>
    </tr>

<?php
$totalAll = 0;
while ($row = mysqli_fetch_assoc($ordersResult)) {
    $price = floatval($row['p_price']);
    $qty = intval($row['p_qty']);
    $subtotal = floatval($row['subtotal']);
    $totalAll += $subtotal;

    echo '
        <tr>
            <td>' . htmlspecialchars($row['p_name']) . '</td>
            <td>' . number_format($price, 2) . ' د.ع</td>
            <td>' . $qty . '</td>
            <td>' . number_format($subtotal, 2) . ' د.ع</td>
            <td>' . htmlspecialchars($row['p_token']) . '</td>
            <td>' . intval($row['order_id']) . '</td>
            <td>
                <a href="?delete=1&id=' . intval($row['detail_id']) . '" onclick="return confirm(\'هل أنت متأكد من الحذف؟\')">🗑️ حذف</a>
            </td>
        </tr>
    ';
}
?>

</table>

<p class="total">
📊 إجمالي الطلبات: <?= number_format($totalAll, 2) ?> د.ع
</p>

</body>
</html>

