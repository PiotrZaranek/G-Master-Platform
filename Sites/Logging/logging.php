<?php
    
    session_start();

    if(!isset($_POST['login']))
    {
        header('Location: ../../index.php');
        exit();
    }

    require_once "../Special/connect.php";

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
        $login = $_POST['login'];
        $pass = $_POST['pass'];     
        $_SESSION['lg_login'] = $login;

        // Zamiana znaków specjalnych na encje 
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");

        // Zabezpieczeni przed wstrzykiwaniem SQL
        if($resault = @$polaczenie->query(
        sprintf("SELECT * FROM users WHERE login='%s';",
        mysqli_real_escape_string($polaczenie, $login))))
        {
            $number_rows = $resault->num_rows;
            if($resault->num_rows > 0)
            {
                $rows = $resault->fetch_assoc();
                if(password_verify($pass, $rows['pass']))
                {
                    if($rows['verify'] == 1)
                    {
                        
                        header("location: ../MainPage/MainPage.html"); 
                        $_SESSION['IsUserLogged'] = true;
                        exit();  
                    }
                    else
                    {
                        header("location: ../Special/activeAccount.php"); 
                        exit();                       
                    }
                    
                }
                else
                {
                    $_SESSION['error_login_or_pass'] = "Password is incorrect!";
                    header("location: ../../index.php");
                    exit();
                }

            }
            else
            {
                $_SESSION['error_login_or_pass'] = "Login or password is incorrect!";
                header("location: ../../index.php");
                exit();
            }

        }
        else
        {
            $_SESSION['error_DB'] = "Sorry but this site is close, try again later";
            header('Location: ../Special/error.php');
            exit();
        }

        $polaczenie->close();
    }

?>