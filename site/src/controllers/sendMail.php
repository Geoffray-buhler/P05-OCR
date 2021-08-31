<?php

namespace Controller;

use App\Debug;

class sendMail
{
    private $msg;
    private $destinataire;
    private $header;
    private $subject;

    function __construct($pseudo,$expediteur,$destinataire,$msg,$subject)
    {
        $header = "MIME-Version: 1.0\r\n";
        $header.= "From".$pseudo."<".$expediteur.">\n";
        $header.= 'Content-Type:text/html; charset="utf-8"'."\n";
        $header.= 'Content-Transfer-Encoding: 8bit';
        $this->header = $header;

        $this->getInfos($pseudo,$expediteur,$destinataire,$msg,$subject);
        $this->formatmsg();
        $this->sendmail();
    }

    function getInfos($pseudo,$expediteur,$destinataire,$msg,$subject)
    {
        $this->pseudo = $pseudo;
        $this->expediteur = $expediteur;
        $this->destinataire = $destinataire;
        $this->msg = $msg;
        $this->subject = $subject;
    }

    function formatmsg()
    {
        $this->msg ='
        <html>
            <body>
                <div align="center">'.$this->msg.'</div>
            </body>
        </html>';
    }

    function sendmail()
    {
        if (mail($this->destinataire,$this->subject,$this->msg,$this->header)) {
            echo 'Message bien envoyer';
        }else{
            echo 'Error je sais pas pourquoi et j\'arrive pas a le savoir';
        }
    }
}