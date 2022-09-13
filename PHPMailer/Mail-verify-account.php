<?php 
    session_start();

    use PHPMailer\PHPMailer\PHPmailer;

    require("src/PHPMailer.php");
    require("src/SMTP.php");
    require("src/Exception.php");
    
    $code = $_SESSION['verifyCode'];
    $nick = $_SESSION['playerNick'];
    $addresMail = $_SESSION['fr_email'];
    $mail = new PHPMailer();
    
    echo $nick;
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
    $mail->AddAddress($addresMail);
    $mail->Subject = "Active Account";
    $mail->Body = "
    <h1>Hello $nick!</h1>
    This is your verify code to activate your accunt on G-master
    <h2>$code</h2>    
    This messege was generating automtical, please don't reply on this mail.
    <br>
    Team G-master
     ";
    
    if(!$mail->Send()) {
    echo "Błąd wysyłania e-maila: " . $mail->ErrorInfo;
    }

?>