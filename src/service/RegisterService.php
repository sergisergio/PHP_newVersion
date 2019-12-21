<?php

namespace Service;

class RegisterService {

  public function __construct() {
    $this->msg = new \Plasticbrain\FlashMessages\FlashMessages();
  }
  public function checkRegister() {
    // check if fields are empty
            $email = strip_tags(htmlspecialchars($_POST['email']));
            $username = strip_tags(htmlspecialchars($_POST['username']));
            $password = strip_tags(htmlspecialchars($_POST['password']));
            $passwordCheck = strip_tags(htmlspecialchars($_POST['passwordCheck']));
            $mailExist = $this->userModel->checkUserByEmail($email);
            $userExist = $this->userModel->checkUserByUsername($username);
            if (empty($email) || empty($username) || empty($password) || empty($passwordCheck)) {
                $this->msg->error("Tous les champs n'ont pas été remplis", self::getUrl());
            // check if passwords match
            } elseif ($password != $passwordCheck) {
                $this->msg->error("Les mots de passe ne correspondent pas", self::getUrl());
            // check if mail exist
            } elseif ($mailExist) {
                $this->msg->error("Adresse mail déjà utilisée", self::getUrl());
            // check if user exist
            } elseif ($userExist) {
                $this->msg->error("Pseudo déjà utilisé", self::getUrl());
            } else {
                $data = [
                    'name'      => $_POST['username'],
                    'email'     => $_POST['email'],
                    'password'  => sha1($_POST['password']),
                    'roles'     => 0,
                    'active'    => 1,
                    'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
                ];
                // create the user then redirect to "my account"
                if ($this->userModel->setUser($data)) {
                    //TODO: redirect to "my account"
                    $this->msg->success("Compte créé", self::getUrl());
                } else {
                    $this->msg->error("Une erreur s'est produite", self::getUrl());
                }
            }
  }

  private function getUrl(bool $referer = false) {
        if ($referer == true) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
    }
}
