<?php

require"manar.php";
global $connect;
mysqli_set_charset($connect,'utf8');
@$post01=$_POST['get01'];
@$post02=$_POST['get02'];
@$post03=$_POST['get03'];
@$post04=$_POST['get04'];
@$post06=$_POST['get06'];

$image = @$_FILES['get04']['name'];
$image_temp = @$_FILES['get05']['tmp_name'];
@$newimage = uniqid('imgproject', true).'.'.strtolower(pathinfo($_FILES['get05']['name'],PATHINFO_EXTENSION));

$Token=date('ymdhis');
$Rand=rand(100,200);
$NewToken=$Token.$Rand;



    $tokenproject = @$_GET['T'];
    $chang = "SELECT * FROM product WHERE p_token='$tokenproject'";
    $changRun = mysqli_query($connect, $chang);
    $changRow = mysqli_fetch_array($changRun);

    if(isset($post06)){
        if($image !=''){
            move_uploaded_file($image_temp,"imgproject/$newimage");
        }else{
            $newimage=$changRow['p_img'];
        }
        $update= "UPDATE product SET
            p_name='$post01',
            p_price='$post02',
            p_cat='$post03',
            p_info='$post04',
            p_img='$newimage'

            WHERE p_token='$tokenproject'
        ";
        if(mysqli_query($connect, $update)){
            echo'
                <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                    <img width="200" src="11.png" />
                    <p>شكراً لك تم تحديث البيانات بنجاح</p>
                </div>
                <meta http-equiv="refresh" content="3, url=showall.php"/>
            ';
            exit();
        }
    }
  
            //setcookie("Token",$NewToken,time()+(86400),"/");
            //setcookie("Name",$post01,time()+(86400),"/");
            //setcookie("islogin","yes",time()+(86400),"/");








?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات المنتج</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/brands.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/duotone-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-duotone-thin.css" rel="stylesheet" />
</head>
<body class="style18">
<table class="style12">

        <tr>
            <td>تعديل المنتج</td>
            <td><a class="style13" href="showall.php"><i class="fa-solid fa-arrow-left"></i></a></td>
        </tr>
    </table>
    <form method="post" action="" enctype="multipart/form-data">
    <div class="style01">
    <img width="300" src="imgproject/<?php  echo $changRow['p_img']; ?>" />
            <input value="<?php echo $changRow['p_name']; ?>" name="get01" class="style02" type="text" placeholder="اكتب اسم المنتج"/><i class="fa-solid fa-box style07"></i>
            <input value="<?php echo $changRow['p_price']; ?>" name="get02" class="style02" type="text" placeholder="اكتب سعر المنتج"/><i class="fa-solid fa-dollar-sign style07"></i>
            <input value="<?php echo $changRow['p_cat']; ?>" name="get03" class="style02" type="text" placeholder="اكتب تصنيف المنتج"/><i class="fa-solid fa-dollar-sign style07"></i>
            <textarea name="get04" class="style17" placeholder="اكتب وصف المنتج"><?php echo $changRow['p_info']; ?></textarea>
            <input name="get05" type="file"/>
        </div>
        <div class="style03">
            <div class="top"><input name="get06" class="style08" type="submit" value="تعديل المنتج"/></div>
        </div>
    </form>
</body>

</table>

</body>
</html>


