<?php
session_start();
require "manar.php";
mysqli_set_charset($connect, 'utf8');

if (!isset($_GET['order_id'])) {
    die("❌ رقم الطلب غير موجود.");
}

$order_id = mysqli_real_escape_string($connect, $_GET['order_id']);

// جلب معلومات الطلب مع بيانات المشتري
$queryOrder = "
    SELECT o.order_id, o.order_date, o.user_token, o.total_price,
           u.user_name, u.user_add, u.user_num
    FROM `order` o
    JOIN users u ON o.user_token = u.user_token
    WHERE o.order_id = '$order_id'
";

$resultOrder = mysqli_query($connect, $queryOrder);
if (!$resultOrder || mysqli_num_rows($resultOrder) == 0) {
    die("❌ لم يتم العثور على بيانات الطلب أو المشتري.");
}
$orderData = mysqli_fetch_assoc($resultOrder);

// جلب تفاصيل الطلب (منتجات)
$queryDetails = "
    SELECT p_name, p_price, p_qty, subtotal
    FROM order_details
    WHERE order_id = '$order_id'
";

$resultDetails = mysqli_query($connect, $queryDetails);
if (!$resultDetails || mysqli_num_rows($resultDetails) == 0) {
    die("❌ لا توجد تفاصيل لهذا الطلب.");
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل الطلب رقم <?php echo htmlspecialchars($order_id); ?></title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; background: #f9f9f9; margin: 20px; }
        .invoice-box {
            max-width: 900px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        h2 { text-align: center; margin-bottom: 20px; }
        .info, .products {
            width: 100%;
            margin-bottom: 30px;
        }
        .info td {
            padding: 5px 10px;
            font-size: 16px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
        }
        th {
            background-color: rgb(85, 28, 42);
            color: white;
        }
        tfoot th {
            text-align: right;
            padding-right: 15px;
            background-color: rgb(85, 28, 42);
        }
        .print-button {
            background-color:rgb(85, 28, 42);
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 30px;
            float: left;
        }
        .print-button:hover {
            background-color:rgb(85, 28, 42);
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .invoice-box, .invoice-box * {
                visibility: visible;
            }
            .invoice-box {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border: none;
            }
            .print-button {
                display: none;
            }
        }
    </style>
    <script>
        function printInvoice() {
            window.print();
        }
    </script>
</head>
<body>

<div class="invoice-box">
    <button class="print-button" onclick="printInvoice()">🖨️ طباعة الفاتورة</button>

    <h2>📋 فاتورة الطلب رقم <?php echo htmlspecialchars($orderData['order_id']); ?></h2>

    <table class="info">
        <tr>
            <td><strong>اسم المشتري:</strong> <?php echo htmlspecialchars($orderData['user_name']); ?></td>
            <td><strong>تاريخ الطلب:</strong> <?php echo htmlspecialchars($orderData['order_date']); ?></td>
        </tr>
        <tr>
            <td><strong>العنوان:</strong> <?php echo htmlspecialchars($orderData['user_add']); ?></td>
            <td><strong>الهاتف:</strong> <?php echo htmlspecialchars($orderData['user_num']); ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>رقم التوكن:</strong> <?php echo htmlspecialchars($orderData['user_token']); ?></td>
        </tr>
    </table>

    <table class="products">
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalOrder = 0;
            while ($row = mysqli_fetch_assoc($resultDetails)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['p_name']) . "</td>";
                echo "<td>" . number_format($row['p_price'], 2) . " د.ع</td>";
                echo "<td>" . intval($row['p_qty']) . "</td>";
                echo "<td>" . number_format($row['subtotal'], 2) . " د.ع</td>";
                echo "</tr>";
                $totalOrder += $row['subtotal'];
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right;">الإجمالي الكلي للطلب:</th>
                <th><?php echo number_format($totalOrder, 2); ?> د.ع</th>
            </tr>
        </tfoot>
    </table>
</div>

</body>
</html>
