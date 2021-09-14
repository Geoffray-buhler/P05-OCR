<?php

namespace Controller;

// require dirname(__DIR__).'\..\vendor\autoload.php';

use App\Debug;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    function __construct($name,$email,$msg,$subject)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'Griffont.RF@gmail.com';                //SMTP username
            $mail->Password   = 'HUB10base-t';                          //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom($email, 'Information contact blog');
            $mail->addAddress('Griffont.RF@gmail.com');  
            $mail->addReplyTo($email);
        
            //Content
            $mail->isHTML(true);                                        //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = '<div style="background-color:#dedede;padding:1rem;">';
            $mail->Body.= '<h2>'.$name.'</h2>';
            $mail->Body.= '<br/>';
            $mail->Body.= '<div style="background-color:red;padding:1rem;">'.$msg.'</div>';
            $mail->Body.= '</div>';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}