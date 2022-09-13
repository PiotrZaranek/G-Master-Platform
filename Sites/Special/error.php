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

        button
        {
            width: 150px;
            padding: 10px;
            font-size: 18px;
            background-color: #000;
            border-radius: 5px;
            border: 1px solid rgb(91, 0, 228);
            color: #fff;
            transition: transform 0.5s, background-color 0.5s;
        }

        button:hover
        {
            cursor: pointer;
            background-color: #060606;
            transform: scale(110%);
        }
    </style>
    <title>G-master</title>
</head>
<body>
    <content>
        <div>
        <?php
            session_start();

            if(isset($_SESSION['error_DB']))
            {
                echo $_SESSION['error_DB'];
                unset($_SESSION['error_DB']);
            }
            else
            {
                if(isset($_SESSION['IsUserLogged']) && $_SESSION['IsUserLogged'] == true)
                {
                    header("Location: ../MainPage/mainPage.html");
                    exit();
                }
                else
                {
                    header("Location: ../../index.php");
                    exit();
                }                
            }       
            ?><br><br>
            <a href="../../index.php"><button>Back to login</button></a>
        </div>
    </content>
</body>
</html>