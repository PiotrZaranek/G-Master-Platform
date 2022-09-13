<?php 
    session_start();

    require_once('../Special/connect.php');

    if(isset($_POST['pass1']) && isset($_POST['code']))
    {
        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        if($polaczenie->connect_errno != 0)
        {
            $_SESSION['error_DB'] = "Sorry but this site is close, try again later";
            header('Location: ../Special/error.php');
            exit();
        }
        else
        {
            // sprawdzanie Hasła
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];
    
            if($pass1 != $pass2)
            {
                $czyWypelnionoDobrze = 0;
                $_SESSION['error_pass'] = "Enter the same passwords!";
            }
            
            if(strlen($pass1) < 8)
            {
                $czyWypelnionoDobrze = 0;
                $_SESSION['error_pass'] = "Too short password!";
            }
    
            $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
    
            $code = $_POST['code'];
    
            if($resault = $polaczenie->query("SELECT id, code FROM users WHERE code='$code'"))
            {
                if($resault->num_rows > 0)
                {
                    $resault = $resault->fetch_assoc();
                    if($code == $resault['code'])
                    {
                        $czyWypelnionoDobrze = 1;
                    }                
                    else
                    {
                        $czyWypelnionoDobrze = 0;
                        $_SESSION['error_code'] = "This code is incorrect!";
                    }  
                }                      
            }
            else
            {
                $_SESSION['error_DB'] = "Something goes wrong, please try again later";
                header('Location: ../Special/error.php');
                $polaczenie->close();
                exit();
            }
    
            if($czyWypelnionoDobrze == 1)
            {
                $id = $resault['id'];
                $polaczenie->query("UPDATE users SET pass='$pass_hash', code='0' WHERE id='$id'");
                $polaczenie->close();
                $_SESSION['completeCreateAccuntInfo'] = 'Your password has been changed!';
                header("Location: ../Special/information.php");
                exit();
            }        
    
            $polaczenie->close();
        }   
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../Style/forgotPasswordStyle.css"/>
    <title>G-master - Forgot password</title>
</head>
<body>
    <div id="Content">
        <div id="Logo">For<span id="textColorPuprle">g</span>ot Password</div>
        <div id="ForgotPassword">
            <form action="" method="POST">
                <input type="text" name="code" class="forgotPasswordInputs" placeholder=" Veryfi Code" autocomplete="off"/>
                <?php
                    if(isset($_SESSION['error_code']))
                    {
                        echo '<p><span class="errorSpan">'. $_SESSION['error_code']. "</span></p>";
                        unset($_SESSION['error_code']);
                    }
                ?>
                <input type="password" name="pass1" class="forgotPasswordInputs" placeholder=" New Password" autocomplete="off"/>
                <input type="password" name="pass2" class="forgotPasswordInputs" placeholder=" Repeat Password" autocomplete="off"/>
                <p><?php
                    if(isset($_SESSION['error_pass']))
                    {
                        echo '<p><span class="errorSpan">'. $_SESSION['error_pass']. "</span></p>";
                        unset($_SESSION['error_pass']);
                    }
                ?></p>
                <input type="submit" value="Send" id="submitForgotPassword"/>
            </form>
        </div>
    </div>  
    <footer>G-master &copy;2022</footer>
</body>
</html>