<?php
    session_start();
    unset($_SESSION['Token']);
    unset($_SESSION['name']);
    unset($_SESSION['islogin']);
    session_destroy();



    //setcookie("Token",null,time()+(-1),"/");
    //setcookie("name",null,time()+(-1),"/");
    //setcookie("islogin",null,time()+(-1),"/");
    echo'
                <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;"><i class="fa-solid fa-right-from-bracket"></i>
                    <img width="200" src="cat.png" />
                    <p> شكراً لك تم مغادرة الحساب نراك لاحقاً</p>
                </div>
                <meta http-equiv="refresh" content="3, url=login.php"/>
            ';
            exit();
            ?>

