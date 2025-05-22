<?php
    session_start();
   

    require "manar.php";
    global $connect;
    mysqli_set_charset($connect, 'utf8');

    $showdb = "SELECT * FROM product";
    $showdbRun = mysqli_query($connect, $showdb);
    
    if(@$_GET['D']=='D'){
        $tokenproject = @$_GET['T'];
        $DD= "DELETE FROM product WHERE p_token='$tokenproject'";
        $DDRun = mysqli_query($connect,$DD);
        echo'
            <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                <img width="200" src="11.png" />
                <p>شكراً لك تم حذف المنتج بنجاح</p>
            </div>
            <meta http-equiv="refresh" content="3, url=showall.php"/>
        ';
        exit();
    }
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> عرض المنتجات</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/brands.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/duotone-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-duotone-thin.css" rel="stylesheet" />
</head>
<body class="style19">
    <table class="style12">
        <tr>
            <td>عرض جميع المنتجات </td>
            <td><a class="style13" href="admin.php"><i class="fa-solid fa-arrow-left"></i></a></td>
        </tr>
    </table>

    <table class="style20">
        <tr>
            <th>ت</th>
            <th>اسم المنتج</th>
            <th>صورة المنتج</th>
            <th>تصنيف  المنتج</th>
            <th>نوع المنتج</th>
            <th>تعديل</th>
            <th>حذف</th>

    </tr>
    
    <?php
            $number=1;
            while($showdbRow = mysqli_fetch_array($showdbRun)){
                echo'
                    <tr>
                        <td>'.$number++.'</td>
                        <td>'.$showdbRow['p_name'].'</td>
                        <td><img width="100" src="imgproject/'.$showdbRow['p_img'].'" /></td>
                        <td>'.$showdbRow['p_price'].' دينار</td>
                        <td>'.$showdbRow['p_cat'].' </td>
                        <td><a href="edit.php?T='.$showdbRow['p_token'].'">تعديل</a></td>
                        <td><a href="showall.php?D=D&T='.$showdbRow['p_token'].'">حذف</a></td>
                    </tr>
                ';
            }
        ?>
    </table>
 
</body>
</html>