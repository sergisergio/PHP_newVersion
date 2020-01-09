<?php

namespace Controllers;

use Models\User;
use Models\Security;
use Service\SecurityService;

/**
 * CLASSE GERANT L'INSCRIPTION, LA CONNEXION ET LA DECONNEXION
 */
class LoginController extends Controller
{
    protected $userModel;
    protected $securityModel;
    protected $securityService;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User;
        $this->securityModel = new Security;
        $this->securityService = new SecurityService;
    }

    /*
     * GERER LA CONNEXION
     */
    public function index() {

        $array = [
            "username" => "",
            "password" => "",
            "error" => "",
            "success" => "",
            "isSuccess" => false,
            "role" => ""
        ];



        if ($this->isLogged()) {
            header('Location: ?c=index');
            exit();
        }
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
                $count = $this->securityModel->checkBruteForce($ip, $username);
                if ($count < 3) {
                    $this->securityModel->registerAttempt($ip, $username);
                    $count += 1;
                    if ($count < 2) {
                        $array["error"] = 'identifiant ou mot de passe incorrect !Il vous reste '.(3 - $count).' tentatives';
                        $array["isSuccess"] = false;
                    } elseif ($count == 2) {
                        $array["error"] = 'identifiant ou mot de passe incorrect !Il vous reste une tentative';
                        $array["isSuccess"] = false;
                    } else {
                        $array["error"] = 'Nombre de tentatives atteintes! Vous pourrez essayer de vous reconnecter dans 24h.';
                        $array["isSuccess"] = false;
                    }
                } elseif ($count == 3) {
                    $attempts = $this->securityModel->getAttempts($ip);
                    date_default_timezone_set('Europe/Paris');
                    $now = strtotime(date("Y-m-d H:i:s"));
                    $limitAttemptsDate = strtotime($attempts[2]['tried_at_plus_one_day']);
                    if (isset($limitAttemptsDate)) {
                        $diff = round(($limitAttemptsDate - $now)/3600);
                        if ($diff > 0) {
                            $array["error"] = 'Nombre de tentatives atteintes! Vous pourrez essayer de vous reconnecter dans '.$diff.'h.';
                            $array["isSuccess"] = false;
                        } else {
                            $this->securityModel->deleteAttempts($ip);
                            $this->securityModel->registerAttempt($ip, $username);
                            $count = 1;
                            $array["error"] = 'identifiant ou mot de passe incorrect !Il vous reste 2 tentatives';
                        }
                    }
                }
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
        $array = [
            "email" => "",
            "username" => "",
            "password" => "",
            "error" => "",
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
            // check if fields are empty
            if (empty($email) || empty($username) || empty($password) || empty($passwordCheck)) {
                //echo "Tous les champs n'ont pas été remplis";
                $array["error"] = "Veuillez remplir les champs !";
                $array["isSuccess"] = false;
            // check if passwords match
            } elseif ($password != $passwordCheck) {
                //echo 'Les mots de passe ne correspondent pas';
                $array["error"] = "Les mots de passe ne correspondent pas !";
                $array["isSuccess"] = false;
            // check if mail exist
            } elseif ($mailExist) {
                //echo 'Adresse mail déjà utilisée';
                $array["error"] = "Adresse mail déjà utilisée !";
                $array["isSuccess"] = false;
            // check if user exist
            } elseif ($userExist) {
                //echo 'Pseudo déjà utilisé';
                $array["error"] = "Pseudo déjà utilisé !";
                $array["isSuccess"] = false;
            } elseif (!preg_match('/^[a-zA-Z0-9_@#&é§è!çà^¨$*`£ù%=+:\;.,?°<>]+$/', $username)) {
                //echo "Votre pseudo n'est pas valide";
                $array["error"] = "Votre pseudo n'est pas valide !";
                $array["isSuccess"] = false;
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                //echo "Votre email n'est pas valide !";
                $array["error"] = "Votre email n'est pas valide !";
                $array["isSuccess"] = false;
            } elseif (strlen($username) < 5  || strlen($username) > 20) {
                //echo 'Votre pseudo doit faire entre 5 et 20 caractères !';
                $array["error"] = "Votre pseudo doit faire entre 5 et 20 caractères !";
                $array["isSuccess"] = false;
            } elseif (strlen($password) < 6 || strlen($password) > 50) {
                //echo 'Votre mot de passe doit faire entre 6 et 50 caractères !';
                $array["error"] = "Votre mot de passe doit faire entre 6 et 50 caractères !";
                $array["isSuccess"] = false;
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
                if ($this->userModel->setUser($data)) {

                    //On active la visibilité des erreurs SMTP
                    try {
                        $this->mail->SMTPDebug = 3;

                        // On change certaines options du SSL
                        $this->mail->SMTPOptions = [
                            'ssl' => [
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            ]
                        ];

                        // On indique que l'on veut envoyer le mail via du SMTP
                        $this->mail->isSMTP();

                        // On indique notre serveur SMTP (local avec maildev)
                        $this->mail->Host = 'smtp.free.fr';
                        $this->mail->Port = 587;
                        $this->mail->SMTPAuth = false;

                        $this->mail->CharSet = 'UTF-8';

                        // From
                        $this->mail->setFrom('admin@free.fr', 'Admin du site');

                        // To
                        $this->mail->addAddress($email, explode('@', $email)[0]);

                        // On défini notre mail en HTML
                        $this->mail->isHTML(true);

                        // Subject
                        $this->mail->Subject = 'Vérification de votre adresse mail';

                        // On va faire le rendu de notre mail mais on va
                        // le stoquer pour le mettre dans le mail
                        $this->mail->Body = '<a href="http://localhost:8003/index.php?c=login&t=confirm&username='.$username.'&token='.$token.'">Lien</a>';

                        // On y mets la version sans html pour le mail texte
                        $this->mail->AltBody = 'Version text sans html';

                        // On envoie le mail
                        ob_start();
                        $this->mail->send();
                        ob_end_clean();
                        //header('Location: ?c=login&g=sentmail');
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
        header('Location: ?c=home');
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
        //var_dump($username);
        //var_dump($getUser['email']);
        //die();
        $token = $this->securityService->str_random(100);
        $this->userModel->updateToken($username, $token);
        try {
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
            $this->mail->setFrom('admin@free.fr', 'Admin du site');
            $this->mail->addAddress($email, explode('@', $email)[0]);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Vérification de votre adresse mail';
            $this->mail->Body = '<a href="http://localhost:8003/index.php?c=login&t=confirm&username='.$username.'&token='.$token.'">Lien</a>';
            $this->mail->AltBody = 'Version text sans html';
            ob_start();
            $this->mail->send();
            ob_end_clean();
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

            if (empty($username)) {
                $this->msg->error("Veuillez remplir le champ", $this->getUrl());
            } else {
                try {
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
                    $this->mail->setFrom('admin@free.fr', 'Admin du site');
                    $this->mail->addAddress($email, explode('@', $email)[0]);
                    $this->mail->isHTML(true);
                    $this->mail->Subject = 'Vérification de votre adresse mail';
                    $this->mail->Body = '<a href="?login&t=reset&username='.$username.'">Réinitialiser mon mot de passe</a>';
                    $this->mail->AltBody = 'Version text sans html';
                    ob_start();
                    $this->mail->send();
                    ob_end_clean();
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
