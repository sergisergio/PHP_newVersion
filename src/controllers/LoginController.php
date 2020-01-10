<?php

namespace Controllers;

use Models\User;
use Models\Security;
use Service\SecurityService;
use Service\LoginService;
use Service\RegisterService;
use Service\MailService;


/**
 * CLASSE GERANT L'INSCRIPTION, LA CONNEXION ET LA DECONNEXION
 */
class LoginController extends Controller
{
    protected $userModel;
    protected $securityModel;
    protected $securityService;
    protected $loginService;
    protected $registerService;
    protected $mailService;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User;
        $this->securityModel = new Security;
        $this->securityService = new SecurityService;
        $this->loginService = new LoginService;
        $this->registerService = new RegisterService;
        $this->mailService = new MailService;
    }

    /*
     * GERER LA CONNEXION
     */
    public function index() {
        if ($this->isLogged()) {
            header('Location: ?c=index');
            exit();
        }

        $array = [
            "username"  => "",
            "password"  => "",
            "error"     => "",
            "success"   => "",
            "isSuccess" => false,
            "role"      => ""
        ];

        if ((isset($_GET['g'])) && $_GET['g'] == 'confirm') {
            $this->msg->success("Votre compte est activé !", 'index.php?c=login');
        } elseif (((isset($_GET['g'])) && $_GET['g'] == 'notvalid') && (isset($_GET['user']))) {
            $username = $_GET['user'];
            $this->msg->success('Ce lien a expiré ! <a href="?c=login&t=resend&user='.$username.'">Renvoyer un lien</a>', 'index.php?c=login');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $username = strip_tags(htmlspecialchars($_POST['username']));
            $password = strip_tags(htmlspecialchars($_POST['password']));
            $checkUser = $this->userModel->getUserByUsernameOrEmail($username);
            $checkPassword = password_verify($password, $checkUser['password']);
            sleep(0.3);
            $ip = $_SERVER['REMOTE_ADDR'];

            if (empty($username) || empty($password)) {
                $array["error"] = "Veuillez remplir les champs !";
                $array["isSuccess"] = false;
            } elseif ($checkUser == false || !$checkPassword) {
                $array = $this->loginService->checkAttempts($array, $ip, $username);
            } else {
                $array["isSuccess"] = true;
                if ($checkUser['roles'] == 1) {
                    $_SESSION['admin'] = $checkUser;
                    $array["role"] = 'admin';

                } else {
                    $_SESSION['user'] = $checkUser;
                    $array["role"] = 'user';
                }
            }
            echo json_encode($array);
        }
    }
    /*
     * GERER L'INSCRIPTION
     *
     * Metttre SMTP et port du FAI ds php.ini et utiliser PHPMailer
     */
    public function registration() {
        if ($this->isLogged()) {
            header('Location: ?c=index');
            exit();
        }

        $array = [
            "email"     => "",
            "username"  => "",
            "password"  => "",
            "error"     => "",
            "isSuccess" => false
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = strip_tags(htmlspecialchars($_POST['email']));
            $username = strip_tags(htmlspecialchars($_POST['username']));
            $password = strip_tags(htmlspecialchars($_POST['password']));
            $passwordCheck = strip_tags(htmlspecialchars($_POST['passwordCheck']));
            $mailExist = $this->userModel->checkUserByEmail($email);
            $userExist = $this->userModel->checkUserByUsername($username);
            $token = $this->securityService->str_random(100);

            if (empty($email) || empty($username) || empty($password) || empty($passwordCheck)) {
                $array = $this->registerService->empty($array, $email, $username, $password, $passwordCheck);
            } elseif ($password != $passwordCheck) {
                $array = $this->registerService->notEqualPasswords($array, $password, $passwordCheck);
            } elseif ($mailExist) {
                $array = $this->registerService->thisMailExist($array, $mailExist);
            } elseif ($userExist) {
                $array = $this->registerService->thisUserExist($array, $userExist);
            } elseif (!preg_match('/^[a-zA-Z0-9_@#&é§è!çà^¨$*`£ù%=+:\;.,?°<>]+$/', $username)) {
                $array = $this->registerService->validUsername($array, $username);
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $array = $this->registerService->validEmail($array, $email);
            } elseif (strlen($username) < 5  || strlen($username) > 20) {
                $array = $this->registerService->checkLengthUsername($array, $username);
            } elseif (strlen($password) < 6 || strlen($password) > 50) {
                $array = $this->registerService->checkLengthPassword($array, $password);
            } else {
                date_default_timezone_set('Europe/Paris');
                $password = password_hash($password, PASSWORD_ARGON2I);
                $data = [
                    'username'   => $username,
                    'email'      => $email,
                    'password'   => $password,
                    'roles'      => 0,
                    'active'     => 0,
                    'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'token'      => $token,
                    'banned'     => 0,
                    'avatar_id'  => 15
                ];
                if ($this->userModel->setUser($data) == 'g') {
                    $body = '<a href="http://localhost:8003/index.php?c=login&t=confirm&username='.$username.'&token='.$token.'">Lien</a>';
                    $subject = 'Vérification de votre adresse mail';
                    try {
                        $this->mailService->send($email, $body, $subject);
                        $array["isSuccess"] = true;
                    } catch (Exception $e) {
                        $array["error"] = "Un problème est survenu ! Le message n\'a pas pu être envoyé... ";
                        $array["isSuccess"] = false;
                    }
                    exit();
                } else {
                    $array["error"] = "Une erreur s'est produite";
                    $array["isSuccess"] = false;
                }
            }
            echo json_encode($array);
        }
    }
    /*
     * GERER LA DECONNEXION
     */
    public function logout() {
        if ($this->isLogged()) {
            session_unset();
            session_destroy();
        }
        header('Location: index.php');
        exit();
    }

    public function confirm() {
        $username = strip_tags(htmlspecialchars($_GET['username']));
        $token = strip_tags(htmlspecialchars($_GET['token']));
        $getUser = $this->userModel->getUserByUsernameOrEmail($username);
        date_default_timezone_set('Europe/Paris');
        $now = strtotime(date("Y-m-d H:i:s"));
        $expireDate = strtotime($getUser['token_expire_date']);
        if (($token == $getUser['token']) && (($now - $expireDate) < 0)) {
            $this->userModel->setUserActive($username);
        } else {
            header('Location: ?c=login&g=notvalid&user='.$username);
            exit();
        }
        header('Location: ?c=login&g=confirm');
        exit();
    }

    public function resend() {
        $username = strip_tags(htmlspecialchars($_GET['user']));
        $getUser = $this->userModel->getUserByUsernameOrEmail($username);
        $email = $getUser['email'];
        $token = $this->securityService->str_random(100);
        $this->userModel->updateToken($username, $token);
        $body = '<a href="http://localhost:8003/index.php?c=login&t=confirm&username='.$username.'&token='.$token.'">Lien</a>';
        $subject = 'Vérification de votre adresse mail';
        try {
            $this->mailService->send($email, $body, $subject);
            $array["isSuccess"] = true;
            header('Location: ?c=login&g=sentmail');
        } catch (Exception $e) {
            $this->msg->error("Un problème est survenu ! Le message n\'a pas pu être envoyé... ");
        }
    }

    public function forgetPassword() {
        // Ajouter un token valable 24h pour réinitialiser le mot de passe
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = strip_tags(htmlspecialchars($_POST['username']));
            $getUser = $this->userModel->getUserByUsernameOrEmail($username);
            $subject = 'Vérification de votre adresse mail';
            $body = '<a href="?login&t=reset&username='.$username.'">Réinitialiser mon mot de passe</a>';

            if (empty($username)) {
                $this->msg->error("Veuillez remplir le champ", $this->getUrl());
            } else {
                try {
                    $this->mailService->send($email, $body, $subject);
                    $this->msg->error("Un lien de réinitialisation de votre mot de passe vous a été envoyé par mail");
                } catch (Exception $e) {
                    $this->msg->error("Un problème est survenu ! Le message n\'a pas pu être envoyé... ");
                }
                    }
                }
        echo $this->twig->render('front/forgot/index.html.twig', [
            'message'       => $this->msg,
            //'session_admin' => $_SESSION['admin'],
            //'session_user'  => $_SESSION['user'],
            //'token'     => $loginToken
        ]);
    }

    public function reset() {
        echo $this->twig->render('front/reset/index.html.twig', [
            'message'       => $this->msg,
            //'session_admin' => $_SESSION['admin'],
            //'session_user'  => $_SESSION['user'],
            //'token'     => $loginToken
        ]);
    }
}
