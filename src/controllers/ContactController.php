<?php

namespace Controllers;

/**
 * CLASSE GERANT LE FORMULAIRE DE CONTACT
 */
class ContactController extends Controller
{
    /*
     * ENVOI DE MESSAGE SUR MA BOITE MAIL
     */
    public function send() {
        if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])) {
            echo 'Veuillez remplir tous les champs !';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->msg->error("Veuillez entrer un email valide ! ");
        } else {
            $name = strip_tags(htmlspecialchars($_POST['name']));
            $email = strip_tags(htmlspecialchars($_POST['email']));
            $subject = strip_tags(htmlspecialchars($_POST['subject']));
            $message = strip_tags(htmlspecialchars($_POST['message']));
            $address = $_SERVER['REMOTE_ADDR'];
            $body = '<p>Nom: '.$name.'</p><br/><p>Adresse IP: '.$address.'</p><br><p>Email: '.$email.'</p><br><p>Sujet: '.$subject.'</p><br><p>Message: '.$message.'</p>';
            $this->mailService->sendToMe($name, $email, $subject, $message, $address, $body);
            echo 'Votre message a bien été envoyé';die();
        }
    }
}
