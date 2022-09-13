<?php 
    session_start();

    require_once('../Special/connect.php');    
    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);    

    if($polaczenie->connect_errno != 0)
    {
        $_SESSION['error_DB'] = "Sorry but this site is close, try again later";    
        header('Location: ../Special/error.php');
        exit();
    }
    else
    {
        //Sprawdzenie poprawności wpisanego maila
        $mail = $_POST['emailFP'];
        $mailB = filter_var($mail, FILTER_SANITIZE_EMAIL);
        if(filter_var($mailB, FILTER_VALIDATE_EMAIL) == false)
        {
            $_SESSION['error_mail'] = "Please enter a valid email!";
            $polaczenie->close();
            header('Location: ../../index.php');
            exit();
        }

        //Wykonanie Zapytania SQL
        if($resault = $polaczenie->query("SELECT id, code FROM users WHERE mail='$mailB'"))
        {
            //Sprawdzenie czy mail pasuje do jakiego kolwiek uzytkownika
            if($resault->num_rows > 0)
            {
                $resault = $resault->fetch_assoc();
                $id = $resault['id'];
                $random = random_int(100000, 999999);

                $_SESSION['random'] = $random;
                $_SESSION['mail'] = $mailB;
                $polaczenie->query("UPDATE users SET code='$random' WHERE id='$id'");
                $polaczenie->close();

                header("Location: ../../PHPMailer/Mail-forgot-password.php");
                exit();                        
            }
            else
            {
                $_SESSION['error_mail'] = "There is no user with this email!";
                $polaczenie->close();
                header('Location: ../../index.php');
                exit();
            }
        }                
    }
?>