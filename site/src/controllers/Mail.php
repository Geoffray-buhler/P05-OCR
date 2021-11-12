<?php

namespace Controller;

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
        include __DIR__.'/../env.php';
        // le dossier ou on trouve les templates
        $loader = new FilesystemLoader('../src/template');
        
        // initialiser l'environement Twig
        $this->twig = new Environment($loader,[
            'debug' => true,
        ]);

        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

        $mail = new PHPMailer(true);

        $msg = filter_var($msg, FILTER_DEFAULT);
        $email = filter_var($email, FILTER_DEFAULT);
        $AdressToSend = filter_var($AdressToSend, FILTER_DEFAULT);
        $raisonEmail = filter_var($raisonEmail, FILTER_DEFAULT);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_CONNECTION;                  //Enable verbose debug output
            $mail->isSMTP();                                              //Send using SMTP
            $mail->Host       = $Host;                                    //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                     //Enable SMTP authentication
            $mail->Username   = $Username;                                //SMTP username
            $mail->Password   = $Password;                                //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           //Enable implicit TLS encryption
            $mail->Port       = 587;                                      //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
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
        } catch (Exception $e) {
        
        }
    }
}