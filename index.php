<?php    
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/mainPageStyle.css">
    <title>G-master - Logging</title>
    <script>
        function showDiv()
        {
            var a = document.getElementById("ForgotPassword");
            a.style.animationName = "showDiv";
            a.style.animationDuration = "1s";
            a.style.animationFillMode = "both";
            console.log("Wykonaj");
        }
    </script>
</head>
<body>
    <div id="Content">
        <div id="Logo"><span id="textColorPuprle">G</span>-master</div>
        <div id="Logging">
            <form action="Sites/Logging/logging.php" method="POST">
                <!--LOGIN-->
                <p><input type="text" name="login" class="loggingMainPage" value="<?php
                    if(isset($_SESSION['lg_login']))
                    {
                        echo $_SESSION['lg_login'];
                        unset($_SESSION['lg_login']);
                    }
                ?>" placeholder=" Login" autocomplete="off"/></p>

                <!--PASSWORD-->                
                <p><input type="password" name="pass" class="loggingMainPage" placeholder=" Password"/></p>  

                <!--LOGGING BTN--> 
                <input type="submit" value="Sign in" id="btnMainPageSI"/>   
                
                <!--CREATE ACCOUNT BTN-->
                <a href="Sites/Register/register.php"><input type="button" value="Create Account" id="btnMainPageR"/></a>
             </form>
             <?php 
                if(isset($_SESSION['error_login_or_pass']))
                {
                    echo '<p><span class="errorSpan">'. $_SESSION['error_login_or_pass']. '</span></p> ';
                    unset($_SESSION['error_login_or_pass']);                    
                }
             ?>
             <!--FORGOT PASSWORD-->
             <button id="btnForgotPassword" onclick="showDiv();">Forgot Password</button>             
             <?php if(isset($_SESSION['error_mail']))
                {
                    echo '<p><span class="errorSpan">'. $_SESSION['error_mail']. '</span></p> ';
                    unset($_SESSION['error_mail']);                    
                }
            ?>
        </div>
        <div id="ForgotPassword">
            <form action="Sites/ForgotPassword/VeryfiCode.php" method="POST">
                <p>Please, write your e-mail</p>
                <input type="text" name="emailFP" class="loggingMainPage" autocomplete="off" placeholder=" Email"/>                
                <input type="submit" id="btnMainPageFP" value="Send"/>
            </form>                        
        </div>
        <footer>G-master &copy;2022</footer>
    </div>
</body>
</html>