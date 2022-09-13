<?php 
    session_start();

    use PHPMailer\PHPMailer\PHPmailer;

    require("src/PHPMailer.php");
    require("src/SMTP.php");
    require("src/Exception.php");
    
    $recipient = $_SESSION['mail'];
    $code = $_SESSION['random'];
    $mail = new PHPMailer();
    
   
    //SMTP settings
    $mail->IsSMTP();
    $mail->CharSet="UTF-8";
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPDebug = 1;
    $mail->SMTPAuth = true;
    $mail->Port = 465 ;
    $mail->SMTPSecure = 'ssl';
    $mail->Username = "centergmaster@gmail.com";
    $mail->Password = "GmasterCenter123";
        
    //Emial settings
    $mail->IsHTML(true);
    $mail->setFrom('centergmaster@gmail.com', 'Gmaster Center');
    $mail->AddAddress($recipient);
    $mail->Subject = "Gmaster - Veryfi Code";
    $mail->Body = "
    <h1>Hello!</h1>
    This is your verify code to change your password.
    <h2>$code</h2>    
    This messege was generating automtical, please don't reply on this mail.
    <br>
    Team G-master
     ";
    
    if(!$mail->Send()) {
    echo "Błąd wysyłania e-maila: " . $mail->ErrorInfo;
    }

    header("Location: ../Sites/ForgotPassword/forgotPassword.php");
    exit();    

?>