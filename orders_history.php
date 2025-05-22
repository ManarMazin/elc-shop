<?php
session_start();
if (@$_SESSION['islogin'] != 'yes') {
    echo '<meta http-equiv="refresh" content="0; url=login.php"/>';
    exit();
}

require "manar.php";
mysqli_set_charset($connect, 'utf8');

$TokenUser = $_SESSION['Token'];

$queryOrders = "SELECT * FROM `order` WHERE user_token='$TokenUser' ORDER BY order_date DESC";
$resultOrders = mysqli_query($connect, $queryOrders);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8" />
    <title>الطلبات السابقة</title>
    <style>
        /* Reset */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: rtl;
            background: #fff;
            margin: 0;
            padding: 40px 20px;
            color: rgb(92, 30, 33);
            background-color: #fff;
        }
        h1 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            font-size: 2.2em;
            letter-spacing: 1px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 40px;
            text-decoration: none;
            color: rgb(92, 30, 33);
            font-weight: 600;
            font-size: 1.1em;
            border: 2px solid rgb(92, 30, 33);
            padding: 8px 16px;
            border-radius: 6px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .back-link:hover {
            background-color: rgb(92, 30, 33);
            color: #fff;
        }
        .order {
            background-color: #fff;
            border: 2px solid rgb(92, 30, 33);
            border-radius: 10px;
            margin-bottom: 30px;
            padding: 20px 25px;
            box-shadow: 0 6px 15px rgba(92, 30, 33, 0.15);
            transition: box-shadow 0.3s ease;
        }
        .order:hover {
            box-shadow: 0 10px 25px rgba(92, 30, 33, 0.3);
        }
        .order-header {
            font-weight: 700;
            font-size: 1.3em;
            margin-bottom: 20px;
            color: rgb(92, 30, 33);
            border-bottom: 2px solid rgb(92, 30, 33);
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        }
        thead {
            background-color: rgb(92, 30, 33);
            color: #fff;
            font-weight: 600;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 1em;
            color: rgb(92, 30, 33);
        }
        tbody tr:hover {
            background-color: rgba(92, 30, 33, 0.1);
        }
        .no-orders {
            font-size: 1.4em;
            color: rgb(92, 30, 33);
            text-align: center;
            margin-top: 80px;
            font-weight: 600;
        }
        @media (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            .order-header {
                font-size: 1.1em;
            }
            th, td {
                padding: 10px 8px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>

<a href="shop.php" class="back-link">&larr; العودة لسلة المشتريات</a>

<h1>الطلبات السابقة</h1>

<?php if (mysqli_num_rows($resultOrders) > 0): ?>

    <?php while ($order = mysqli_fetch_assoc($resultOrders)): ?>
        <div class="order">
            <div class="order-header">
                طلب رقم: <?php echo $order['order_id']; ?> -
                التاريخ: <?php echo $order['order_date']; ?> -
                الإجمالي: <?php echo $order['total_price']; ?> دينار
            </div>

            <?php
            $orderId = $order['order_id'];
            $queryDetails = "SELECT * FROM order_details WHERE order_id='$orderId'";
            $resultDetails = mysqli_query($connect, $queryDetails);
            ?>

            <table>
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($detail = mysqli_fetch_assoc($resultDetails)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['p_name']); ?></td>
                            <td><?php echo $detail['p_price']; ?> دينار</td>
                            <td><?php echo $detail['p_qty']; ?></td>
                            <td><?php echo $detail['subtotal']; ?> دينار</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endwhile; ?>

<?php else: ?>
    <p class="no-orders">لا توجد طلبات سابقة حتى الآن.</p>
<?php endif; ?>

</body>
</html>
