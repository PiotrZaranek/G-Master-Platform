<?php 
    if(isset($_POST['code']))
    {
        require_once "connect.php";
        
        $code = $_POST['code'];
        //próba połączenia się z bazą
        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
    
        //Sprawdzenie czy połączenie się powiodło
        if($polaczenie->connect_errno != 0)
        {
            $_SESSION['error_DB'] = "Sorry but this site is close, try again later";
            header('Location: ../Special/error.php');
            exit();
        }
        else
        {
            $resault = $polaczenie->query("SELECT id, code FROM users WHERE code='$code'");
            $number_rows = $resault->num_rows;
            if($number_rows > 0)
            {
                $column = $resault->fetch_assoc();
                if($code == $column['code'])
                {
                    $id = $column['id'];
                    $resault = $polaczenie->query("UPDATE users SET verify='1', code='0' WHERE id='$id'");
                    header("location: ../MainPage/MainPage.html");
                    exit();
                }
                else
                {
                    $_SESSION['erroe_verify_account'] = "Wrong code entered!";
                }
            }
            else
            {
                $_SESSION['erroe_verify_account'] = "Wrong code entered!";
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
    <style>
        @keyframes slideBottom 
        {
            from
            {
                transform: translateY(-100%);
                opacity: 0;
            }
            to
            {
                transform: translateY(40%);
                opacity: 1;
            } 
        }

        body
        {
            background: linear-gradient(to right, #000 30%, #160038);
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, Helvetica, sans-serif;
        }

        div
        {
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            color: #dedede;
            font-size: 25px;
            border-radius: 10px;
            border:solid 1px rgb(91, 0, 228);
            background-color: rgba(0,0,0,0.5);
            width: 500px;
            padding: 20px;
            -webkit-box-shadow: 20px 18px 32px -9px rgb(0, 0, 0);
            -moz-box-shadow: 20px 18px 32px -9px rgb(0, 0, 0);
            box-shadow: 20px 18px 32px -9px rgba(0, 0, 0, 1);
            animation-name: slideBottom;
            animation-timing-function: ease-in-out;
            animation-duration: 0.5s;
            animation-delay: 0.1s;
            animation-fill-mode: both;
        }

        #button
        {
            background-color: seagreen;
            border-radius: 5px;
            border: solid 1px rgb(25, 73, 46);
            padding: 5px;
            color: #fff;
            width: 25%;
            margin-right: 15px;
            font-size: 18px;
            transition: transform 0.5s, background-color 0.5s;
        }

        #button:hover
        {
            cursor: pointer;
            background-color:rgb(25, 73, 46);
            transform: scale(110%);
        }

        /*Inputy*/
        #inputAA
        {
            width: 50%;
            font-size: 25px;
            background-color: #000;
            color: #fff;
            border:solid 1px rgb(91, 0, 228);
            border-radius: 5px;
            padding: 5px;
        }

        /*Spany*/
        #textColorPuprle
        {   
            color: rgb(67, 0, 167);
        }

        .errorSpan
        {
            color: rgb(200, 0, 0);
        }

        #sizeL
        {
            font-size: 40px;
        }
    </style>
    <title>G-master - Active account</title>
</head>
<body>
    <content>
        <div>
            <form action="" method="POST">
                <p><span id="sizeL">Activ<span id="textColorPuprle">e</span> Account</span></p>
                <p><input type="number" id="inputAA" name="code" placeholder=" Verify code" autocomplete="off" maxlength="6"></p>
                <?php 
                if(isset($_SESSION['erroe_verify_account']))
                {
                    echo '<p><span class="errorSpan">'. $_SESSION['erroe_verify_account']. '</span></p> ';
                    unset($_SESSION['erroe_verify_account']);                    
                }
                ?>
                <p><input type="submit" id="button" value="Activte"></p>
            </form>
        </div>
    </content>
</body>
</html>