<?php
    session_start();

    if(isset($_POST["login"]))
    {
        $czyWypelnionoDobrze = 1;
        require_once "../Special/connect.php";

        $polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);

        if($polaczenie->connect_errno != 0)
        {
            $_SESSION['error_DB'] = "Sorry but this site is close, try again later";
            header('Location: ../Special/error.php');
            exit();
        }
        else
        {
            //Zapamiętane wprowadzone dane
            $_SESSION['fr_login'] = $_POST['login'];
            $_SESSION['fr_email'] = $_POST['email'];
            $_SESSION['fr_pass1'] = $_POST['pass1'];
            $_SESSION['fr_pass2'] = $_POST['pass2'];
            
            // sprawdzanie loginu
            $login = $_POST['login'];            
            if((strlen($login) < 4) || (strlen($login) > 18))
            {
                $czyWypelnionoDobrze = 0;
                $_SESSION['error_login'] = "Login must be 4 to 18 characters long!";
            }
            
            if(ctype_alnum($login)==false)
            {
                $czyWypelnionoDobrze = 0;
                $_SESSION['error_login'] = "Use only alphanumeric characters!";
            }

            // sprawdzenie E-mail
            $email = $_POST['email'];
            $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
            if((filter_var($emailB,FILTER_VALIDATE_EMAIL) == false) || $email != $emailB)
            {
                $czyWypelnionoDobrze = 0;
                $_SESSION['error_email'] = "Please enter a valid email!";
            }

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

            //sprawdzanie Statute            
            if(!isset($_POST['statute']))
            {
                $czyWypelnionoDobrze = 0;
                $_SESSION['error_statute'] = "You have to accept statute!";
            }

            //sprawdzanie reCaptacha
            $secret = "6LdSqLAdAAAAAJnUsNi4qrQu_YYRJBaSAzL2fBdP";
            $response = $_POST['g-recaptcha-response'];
            $remoteip = $_SERVER['REMOTE_ADDR'];
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
            $data = file_get_contents($url);
            $row = json_decode($data, true);

            if($row['success'] != "true")
            {
                $czyWypelnionoDobrze = 0;
                $_SESSION['error_capcha'] = "Confirm you are not a robot!";
            }

            //sprawdzenie czy login lub Email się powtarza
            $resault = $polaczenie->query("SELECT id FROM users WHERE email='$email'");
            $number_rows = $resault->num_rows;            
            if($number_rows != 0)
            {
                $czyWypelnionoDobrze = 0;
                $_SESSION['error_email'] = "This emial have account!";
            }

            $resault = $polaczenie->query("SELECT id FROM `users` WHERE login='$login'");
            $number_rows = $resault->num_rows;
            echo $polaczenie->error;
            if($number_rows != 0)
            {
                $czyWypelnionoDobrze = 0;
                $_SESSION['error_login'] = "This login is used";
            }

            //Stworzenie użytkownika
            if($czyWypelnionoDobrze == 1)
            {
                $code = random_int(100000,999999);
                if($polaczenie->query("INSERT INTO users VALUES (NULL, '$login', '$email', '$pass_hash', '0', '$code', 'Graphic/DefaultUserIcon.png')"))
                {    
                    $_SESSION['verifyCode'] = $code;
                    $_SESSION['playerNick'] = $login;  
                    require_once '../../PHPMailer/Mail-verify-account.php';
                    $_SESSION['completeCreateAccuntInfo'] = "Your account has been created!<br> To your e-mail we send a verify code. Use it the first time you log in.";
                    header('Location: ../Special/information.php');
                    $polaczenie->close();
                    exit();
                }
                else
                {                    
                    $_SESSION['error_DB'] = "Something goes wrong, please try again later";
                    header('Location: ../Special/error.php');
                    $polaczenie->close();
                    exit();
                }
            }

            $polaczenie->close();
        }

    }    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Style/registerStyle.css">
    <title>G-master - Register</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div id="Content">        
        <div id="Register">
            <i class="demo-icon icon-ok"></i>
            <h1>Create <span id="textColorPuprle">A</span>ccount</h1>
            <form method="POST">
                <!--LOGIN-->
                <p><input type="text" name="login" class="inputRegister" value="<?php 
                    if(isset($_SESSION['fr_login']))
                    {
                        echo $_SESSION['fr_login'];
                        unset($_SESSION['fr_login']);
                    }
                ?>" placeholder=" Login" autocomplete="off"/></p>
                <?php
                    if(isset($_SESSION['error_login']))
                    {
                        echo '<span class="errorSpan">'. $_SESSION['error_login']. "</span>";
                        unset($_SESSION['error_login']);
                    }
                ?>

                <!--EMAIL-->
                <p><input type="text" name="email" class="inputRegister" value="<?php 
                    if(isset($_SESSION['fr_email']))
                    {
                        echo $_SESSION['fr_email'];
                        unset($_SESSION['fr_email']) ;
                    }
                ?>" placeholder=" Email" autocomplete="off"/></p>
                <?php
                    if(isset($_SESSION['error_email']))
                    {
                        echo '<span class="errorSpan">'. $_SESSION['error_email']. "</span>";
                        unset($_SESSION['error_email']);
                    }
                ?>

                <!--PASSWORD-->
                <p><input type="password" name="pass1" class="inputRegister" value="<?php 
                    if(isset($_SESSION['fr_pass1']))
                    {
                        echo $_SESSION['fr_pass1'];
                        unset($_SESSION['fr_pass1']) ;
                    }
                ?>" placeholder=" Password"/></p> 

                <p><input type="password" name="pass2" class="inputRegister" value="<?php 
                    if(isset($_SESSION['fr_pass2']))
                    {
                        echo $_SESSION['fr_pass2'];
                        unset($_SESSION['fr_pass2']) ;
                    }
                ?>" placeholder=" Repeat Password"/></p>
                <?php
                    if(isset($_SESSION['error_pass']))
                    {
                        echo '<span class="errorSpan">'. $_SESSION['error_pass']. "</span>";
                        unset($_SESSION['error_pass']);
                    }
                ?>

                <!--STATUTE-->
                <p><input type="checkbox" name="statute" id="check"><br>
                <label for="check" id="labelCheckboxRegister">Accept statute</label></p>
                <?php
                    if(isset($_SESSION['error_statute']))
                    {
                        echo '<span class="errorSpan">'. $_SESSION['error_statute']. "</span>";
                        unset($_SESSION['error_statute']);
                    }
                ?>
                <!--STATUTE-->

                <p><div class="g-recaptcha" data-sitekey="6LdSqLAdAAAAABT7Rob7n3oR9sMbC9YyVqKBF4eB"></div></p>
                <?php
                    if(isset($_SESSION['error_capcha']))
                    {
                        echo '<span class="errorSpan">'. $_SESSION['error_capcha']. "</span>";
                        unset($_SESSION['error_capcha']);
                    }
                ?>
                <p><input type="submit" value="Create" id="registerBtn"></p>
             </form>
        </div>
        <footer>G-master &copy;2022</footer>
    </div>
    
</body>
</html>