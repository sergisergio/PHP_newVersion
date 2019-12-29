<?php

namespace Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * CLASSE GERANT LES MAILS
 */
class MailService {
    /**
     * ENVOI DE MESSAGE SUR MA BOITE MAIL
     */
    public function sendToMe($name, $email, $subject, $message, $address, $body) {
        try {
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = 3;
                $mail->SMTPOptions = [
                    'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    ]
                ];
                $mail->isSMTP();
                $mail->Host = 'smtp.free.fr';
                $mail->Port = 587;
                $mail->SMTPAuth = false;
                $mail->CharSet = 'UTF-8';
                $mail->setFrom($email);
                $mail->addAddress('ptraon@gmail.com', explode('@', $email)[0]);
                $mail->isHTML(true);
                $mail->Subject = 'Message de '.$name;
                $mail->Body = $body;
                $mail->AltBody = 'Version text sans html';
                ob_start();
                $mail->send();
                ob_end_clean();
        } catch (Exception $e) {
                $this->msg->error("Un problème est survenu ! Le message n\'a pas pu être envoyé... ");
        }
    }
    /**
     * ENVOI DE MESSAGES SUR LA BOITE MAIL DES MEMBRES
     */
    public function send() {

    }
}
