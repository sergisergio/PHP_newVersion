<?php

namespace Service;

use Models\User;

class RegisterService {
    protected $userModel;

    public function __construct() {
        $this->msg = new \Plasticbrain\FlashMessages\FlashMessages();
        $this->userModel = new User;
    }
    public function checkRegistration($array, $email, $username, $password, $passwordCheck, $mailExist, $userExist) {
            $array["error"] = "test !";
            $array["isSuccess"] = false;
        // if (empty($email) || empty($username) || empty($password) || empty($passwordCheck)) {
        //     $array["error"] = "Veuillez remplir les champs !";
        //     $array["isSuccess"] = false;
        // } elseif ($password != $passwordCheck) {
        //     $array["error"] = "Les mots de passe ne correspondent pas !";
        //     $array["isSuccess"] = false;
        // } elseif ($mailExist) {
        //     $array["error"] = "Adresse mail déjà utilisée !";
        //     $array["isSuccess"] = false;
        // } elseif ($userExist) {
        //     $array["error"] = "Pseudo déjà utilisé !";
        //     $array["isSuccess"] = false;
        // } elseif (!preg_match('/^[a-zA-Z0-9_@#&é§è!çà^¨$*`£ù%=+:\;.,?°<>]+$/', $username)) {
        //     $array["error"] = "Votre pseudo n'est pas valide !";
        //     $array["isSuccess"] = false;
        // } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //     $array["error"] = "Votre email n'est pas valide !";
        //     $array["isSuccess"] = false;
        // } elseif (strlen($username) < 5  || strlen($username) > 20) {
        //     $array["error"] = "Votre pseudo doit faire entre 5 et 20 caractères !";
        //     $array["isSuccess"] = false;
        // } elseif (strlen($password) < 6 || strlen($password) > 50) {
        //     $array["error"] = "Votre mot de passe doit faire entre 6 et 50 caractères !";
        //     $array["isSuccess"] = false;
        // }
        return $array;
    }

    public function empty($array, $email, $username, $password, $passwordCheck) {
        $array["error"] = "Veuillez remplir les champs !";
        $array["isSuccess"] = false;
        return $array;
    }

    public function notEqualPasswords($array, $password, $passwordCheck) {
        $array["error"] = "Les mots de passe ne correspondent pas !";
        $array["isSuccess"] = false;
        return $array;
    }

    public function thisMailExist() {
        $array["error"] = "Adresse mail déjà utilisée !";
        $array["isSuccess"] = false;
        return $array;
    }

    public function thisUserExist() {
        $array["error"] = "Pseudo déjà utilisé !";
        $array["isSuccess"] = false;
        return $array;
    }

    public function validUsername($array, $username) {
        $array["error"] = "Votre pseudo n'est pas valide !";
        $array["isSuccess"] = false;
        return $array;
    }

    public function validEmail($array, $email) {
        $array["error"] = "Votre email n'est pas valide !";
        $array["isSuccess"] = false;
        return $array;
    }

    public function checkLengthUsername($array, $username) {
        $array["error"] = "Votre pseudo doit faire entre 5 et 20 caractères !";
        $array["isSuccess"] = false;
        return $array;
    }

    public function checkLengthPassword($array, $password) {
        $array["error"] = "Votre mot de passe doit faire entre 6 et 50 caractères !";
        $array["isSuccess"] = false;
        return $array;
    }

    private static function getUrl(bool $referer = false) {
        if ($referer == true) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
    }
}
