<?php

namespace Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * CLASSE GERANT LES MAILS
 */
class MailService {
    protected $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
    }
    /**
     * ENVOI DE MESSAGE SUR MA BOITE MAIL
     */
    public function sendToMe($name, $email, $subject, $message, $address, $body) {
        try {
                //$mail = new PHPMailer(true);
                $this->mail->SMTPDebug = 3;
                $this->mail->SMTPOptions = [
                    'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    ]
                ];
                $this->mail->isSMTP();
                $this->mail->Host = 'smtp.free.fr';
                $this->mail->Port = 587;
                $this->mail->SMTPAuth = false;
                $this->mail->CharSet = 'UTF-8';
                $this->mail->setFrom($email);
                $this->mail->addAddress('ptraon@gmail.com', explode('@', $email)[0]);
                $this->mail->isHTML(true);
                $this->mail->Subject = 'Message de '.$name;
                $this->mail->Body = $body;
                $this->mail->AltBody = 'Version text sans html';
                ob_start();
                $this->mail->send();
                ob_end_clean();
        } catch (Exception $e) {
                $this->msg->error("Un problème est survenu ! Le message n\'a pas pu être envoyé... ");
        }
    }
    /**
     * ENVOI DE MESSAGES SUR LA BOITE MAIL DES MEMBRES
     */
    public function send($email, $body, $subject) {

                //$mail = new PHPMailer(true);
                $this->mail->SMTPDebug = 3;
                $this->mail->SMTPOptions = [
                    'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    ]
                ];
                $this->mail->isSMTP();
                $this->mail->Host = 'smtp.free.fr';
                $this->mail->Port = 587;
                $this->mail->SMTPAuth = false;
                $this->mail->CharSet = 'UTF-8';
                $this->mail->setFrom('no-reply@philippetraon.com');
                $this->mail->addAddress($email, explode('@', $email)[0]);
                $this->mail->isHTML(true);
                $this->mail->Subject = $subject;
                $this->mail->Body = $body;
                $this->mail->AltBody = 'Version text sans html';
                ob_start();
                $this->mail->send();
                ob_end_clean();
    }
}
