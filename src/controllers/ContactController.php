<?php

namespace Controllers;

/**
 * CLASSE GERANT LE FORMULAIRE DE CONTACT
 */
class ContactController extends Controller
{
    /*
     * ENVOI DE MAIL
     */
    public function send() {
        if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(500);
            exit();
        }
        $name = strip_tags(htmlspecialchars($_POST['name']));
        $email = strip_tags(htmlspecialchars($_POST['email']));
        $subject = strip_tags(htmlspecialchars($_POST['phone']));
        $message = strip_tags(htmlspecialchars($_POST['message']));
        $address = $_SERVER['REMOTE_ADDR'];
        // Create the email and send the message
        $to = "ptraon@pm.me";
        $subject = "Message de $name";
        $body = "Vous avez reçu un nouveau message de votre formulaire de contact de site Web.\n\n"."Voici les détails:\n\nNom: $name\n\nEmail: $email\n\nMessage:\n$message\n\nAdresse IP:\n$address";
        $header = "From: noreply@philippe-traon.com\n";
        $header .= "Reply-To: $email";
        if(!mail($to, $subject, $body, $header))
            http_response_code(500);
    }
}
