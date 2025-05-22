<?php
session_start();
require "manar.php";
global $connect;
mysqli_set_charset($connect, 'utf8');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $stmt = mysqli_prepare($connect, "SELECT * FROM product WHERE p_name LIKE CONCAT('%', ?, '%') ORDER BY p_id DESC");
    mysqli_stmt_bind_param($stmt, 's', $search);
    mysqli_stmt_execute($stmt);
    $showdbRun = mysqli_stmt_get_result($stmt);
} else {
    $showdb = "SELECT * FROM product ORDER BY p_id DESC LIMIT 8";
    $showdbRun = mysqli_query($connect, $showdb);
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>متجر الإلكترونيات</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link href="style.css" rel="stylesheet"/>
    <style>
        .cart-icon a {
    font-size: 24px;
    color: #fff;
    border:rgb(66, 7, 7);
    text-decoration: none;
}

.cart-icon a:hover {
    color:rgb(66, 7, 7); 
}

.fa-shopping-cart {
    margin-right: 8px; 
}

        body {
            font-family: 'Cairo', sans-serif;
            background: #111;
            color: #fff;
            margin: 0;
            direction: rtl;
        }
        nav {
            background: #000;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links {
            display: flex;
            gap: 20px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            padding: 5px 10px;
        }
        nav a:hover {
            background-color: #fff;
            color: #000;
            border-radius: 5px;
        }
        .cart-icon img {
            height: 28px;
        }
        .search-bar {
            text-align: center;
            margin: 30px 0;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            width: 60%;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .search-bar button {
            padding: 10px 20px;
            margin-right: 10px;
            background-color: #fff;
            color: #000;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #ccc;
        }

        .products-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            gap: 20px;
        }

        .product-card {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.03);
            width: 270px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-info h3 {
            font-size: 22px;
            margin-top: 15px;
            color: #fff;
        }

        .product-info .price {
            font-size: 18px;
            font-weight: bold;
            color: #ff6666;
            margin: 10px 0;
        }

        .product-info .description {
            font-size: 15px;
            color: #ccc;
            margin: 10px 0;
            line-height: 1.6;
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-info .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .product-info a,
        .product-info button {
            padding: 10px 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
        }

        .product-info a {
            background-color: #fff;
            color: #000;
        }

        .product-info a:hover {
            background-color: #ddd;
        }

        .product-info button {
            background-color: #800000;
            color: #fff;
        }

        .product-info button:hover {
            background-color: #a10000;
        }

        footer .footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

footer .social-icons {
    margin-bottom: 15px;
}

footer .social-icons a {
    margin: 0 10px;
    font-size: 25px;
    color: #fff;
    transition: color 0.3s;
}

footer .social-icons a:hover {
    color: #00bcd4; /* اختر اللون الذي يناسب تصميمك */
}

footer .footer-nav {
    margin-top: 20px;
}

footer .footer-nav ul {
    display: flex;
    justify-content: center;
}

footer .footer-nav li {
    margin: 0 10px;
}

footer .footer-nav a:hover {
    text-decoration: underline;
}


.slider-section {
  background-color: #fff;
  padding: 40px 0;
  overflow: hidden;
}

.slider-wrapper {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.slider-track {
  display: flex;
  width: calc(250px * 8); /* عدد الصور × العرض */
  animation: scroll 15s linear infinite;
}

.slide {
  width: 250px;
  flex-shrink: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
}

.slide img {
  width: 100%;
  height: auto;
  object-fit: contain;
}

/* الحركة */
@keyframes scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}


    </style>
</head>
<body>

<nav>
    <div class="nav-links">
        <a href="index.php">الرئيسية</a>
        <a href="allproduct.php">تسوق</a>
        <a href="about.php">من نحن</a>
        <a href="login.php">تسجيل الدخول</a>
    </div>
    <div class="cart-icon">
    <a href="shop.php">
        <i class="fa fa-shopping-cart"></i> السلة
    </a>
</div>

</nav>


<section class="slider-section">
  <div class="slider-wrapper">
    <div class="slider-track">
      <div class="slide"><img src="logoimg\brand-apple.png" alt="Brand 1"></div>
      <div class="slide"><img src="logoimg\brand-hp.png" alt="Brand 2"></div>
      <div class="slide"><img src="logoimg\brand-lenovo.png" alt="Brand 3"></div>
      <div class="slide"><img src="logoimg\xi.png" alt="Brand 4"></div>
      <div class="slide"><img src="logoimg\hu.png" alt="Brand 4"></div>
      <div class="slide"><img src="logoimg\brand-apple.png" alt="Brand 1"></div>
      <div class="slide"><img src="logoimg\brand-hp.png" alt="Brand 2"></div>
      <div class="slide"><img src="logoimg\brand-lenovo.png" alt="Brand 3"></div>
      <div class="slide"><img src="logoimg\xi.png" alt="Brand 4"></div>
      <div class="slide"><img src="logoimg\hu.png" alt="Brand 4"></div>
      <!-- كرر الصور إذا أردت استمرار السلايدر -->
    </div>
  </div>
</section>


    





<!-- شريط البحث -->
<div class="search-bar">
    <form method="get" action="index.php">
        <input type="text" name="search" placeholder="ابحث عن منتج مثل آيباد، موبايل، لابتوب..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
        <button type="submit">بحث</button>
    </form>
</div>

<!-- عرض المنتجات -->
<div class="products-section">
<?php
if (isset($showdbRun) && mysqli_num_rows($showdbRun) > 0) {
    while ($row = mysqli_fetch_assoc($showdbRun)) {
        echo '
        <div class="product-card">
            <img src="imgproject/'.$row['p_img'].'" alt="'.$row['p_name'].'">
            <div class="product-info">
                <h3>'.$row['p_name'].'</h3>
                <div class="price">'.$row['p_price'].' دينار</div>
                <div class="description">'.mb_substr($row['p_info'], 0, 100).'...</div>
                <div class="actions">
                    <a href="project1.php?T='.$row['p_token'].'">عرض التفاصيل</a>
                   
                </div>
            </div>
        </div>';
    }
} else {
    echo '<p style="color:#ccc;text-align:center;">لا توجد منتجات مطابقة لبحثك حالياً.</p>';
}



?>

</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="allproduct.php" style="
        background-color: #fff;
        color: #000;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        transition: background-color 0.3s;
    " onmouseover="this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='#fff'">
        عرض كل المنتجات 
    </a>
</div>


<!-- Footer Section -->
<footer style="background-color: #000; color: #fff; padding: 20px 0; text-align: center;">
    <div class="footer-content">
        <p>&copy; 2025 موقعنا لشراء الهواتف والأجهزة الكهربائية. جميع الحقوق محفوظة.</p>
        
        <!-- Social Media Links -->
        <div class="social-icons">
            <a href="https://www.facebook.com" target="_blank" title="فيسبوك">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://twitter.com" target="_blank" title="تويتر">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="https://www.instagram.com" target="_blank" title="إنستاجرام">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.linkedin.com" target="_blank" title="لينكد إن">
                <i class="fab fa-linkedin-in"></i>
            </a>
        </div>
        
        <!-- Navigation Links -->
        <div class="footer-nav">
            <ul style="list-style: none; padding: 0;">
                <li><a href="index.php" style="color: #fff; text-decoration: none; margin: 0 15px;">الرئيسية</a></li>
                <li><a href="about.php" style="color: #fff; text-decoration: none; margin: 0 15px;">من نحن</a></li>
                <li><a href="contact.php" style="color: #fff; text-decoration: none; margin: 0 15px;">اتصل بنا</a></li>
                <li><a href="terms.php" style="color: #fff; text-decoration: none; margin: 0 15px;">الشروط والأحكام</a></li>
            </ul>
        </div>
    </div>
</footer>



</body>
</html>



