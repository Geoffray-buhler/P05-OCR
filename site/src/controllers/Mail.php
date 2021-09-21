<?php

namespace Controller;

// require dirname(__DIR__).'\..\vendor\autoload.php';

use App\Debug;
use Twig\Environment;
use PHPMailer\PHPMailer\SMTP;
use Twig\Loader\FilesystemLoader;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    public $twig;

    function __construct($name,$email,$msg,$subject,$raisonEmail,$AdressToSend,$template,$acctu)
    {
        // le dossier ou on trouve les templates
        $loader = new FilesystemLoader('../src/template');
        
        // initialiser l'environement Twig
        $this->twig = new Environment($loader,[
            'debug' => true,
        ]);

        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp-griffont39.alwaysdata.net';       //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'griffont39@alwaysdata.net';            //SMTP username
            $mail->Password   = 'AU2nHF3mHC@H9py';                      //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom($email, $raisonEmail);
            $mail->addAddress($AdressToSend);  
            $mail->addReplyTo($email);
        
            //Content
            $mail->isHTML(true);                                        //Set email format to HTML
            $mail->Subject = $subject;
                                                                        // le dossier ou on trouve les templates
            $loader = new FilesystemLoader('../src/template');
        
            // initialiser l'environement Twig
            $this->twig = new Environment($loader,[
                'debug' => true,
            ]);
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
            $template = $this->twig->load('email/mail.html.twig');
            $mail->Body = $template->render(['name'=>$name,'msg'=>$msg,'acctu'=>$acctu]);

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}