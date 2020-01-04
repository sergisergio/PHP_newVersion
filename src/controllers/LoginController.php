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

        if ($this->isLogged()) {
            header('Location: ?c=index');
            exit();
        }
        if ((isset($_GET['g'])) && $_GET['g'] == 'sentmail') {
            $this->msg->success("Un lien de confirmation vous a été envoyé", 'index.php?c=login');
        } elseif ((isset($_GET['g'])) && $_GET['g'] == 'confirm') {
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
            $ip = $_SERVER['REMOTE_ADDR'];
            if (empty($username) || empty($password)) {
                $this->msg->error("Tous les champs n'ont pas été remplis", $this->getUrl());
            } elseif ( !$checkUser) {
                $this->securityModel->registerAttempt($ip, $username);
                $count = $this->securityModel->checkBruteForce($ip, $username);
                if ($count < 2) {
                    $this->msg->error("Identifiant ou mot de passe incorrect ! Il vous reste ".(3 - $count)." tentatives", $this->getUrl());
                } elseif ($count == 2) {
                    $this->msg->error("Identifiant ou mot de passe incorrect ! Il vous reste une tentative", $this->getUrl());
                } else {
                    $this->userModel->banUser($username);
                    $this->msg->error("Nombre de tentatives atteintes! Vous pourrez essayer de vous reconnecter dans 24h.", $this->getUrl());
                }
            } else {
                if ($checkPassword) {
                    if ($checkUser['roles'] == 1) {
                        $_SESSION['admin'] = $checkUser;
                        header('Location: ' . '?c=adminDashboard');
                        exit;
                    } else {
                        $_SESSION['user'] = $checkUser;
                        header('Location: ' . '?c=blog&v=view1&page=1');
                        exit;
                    }
                }
            }
        }

        echo $this->twig->render('front/login/index.html.twig', [
            'message'       => $this->msg,
            'session_admin' => $_SESSION['admin'],
            'session_user'  => $_SESSION['user'],
            //'token'     => $loginToken
        ]);
    }
    /*
     * GERER L'INSCRIPTION
     *
     * Metttre SMTP et port du FAI ds php.ini et utiliser PHPMailer
     */
    public function registration() {
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
                $this->msg->error("Tous les champs n'ont pas été remplis", $this->getUrl());
            // check if passwords match
            } elseif ($password != $passwordCheck) {
                $this->msg->error("Les mots de passe ne correspondent pas", $this->getUrl());
            // check if mail exist
            } elseif ($mailExist) {
                $this->msg->error("Adresse mail déjà utilisée", $this->getUrl());
            // check if user exist
            } elseif ($userExist) {
                $this->msg->error("Pseudo déjà utilisé", $this->getUrl());
            } elseif (!preg_match('/^[a-zA-Z0-9_@#&é§è!çà^¨$*`£ù%=+:\;.,?°<>]+$/', $username)) {
                $this->msg->error("Votre pseudo n'est pas valide");
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->msg->error("Votre email n'est pas valide !");
            } elseif (strlen($username) < 5  || strlen($username) > 20) {
                $this->msg->error("Votre pseudo doit faire entre 5 et 20 caractères !");
            } elseif (strlen($password) < 6 || strlen($password) > 50) {
                $this->msg->error("Votre mot de passe doit faire entre 6 et 50 caractères !");
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
                    'avatar_id'  => 10
                ];
                if ($this->userModel->setUser($data)) {

                    // On active la visibilité des erreurs SMTP
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
                        header('Location: ?c=login&g=sentmail');
                    } catch (Exception $e) {
                        $this->msg->error("Un problème est survenu ! Le message n\'a pas pu être envoyé... ");
                    }
                    //exit();
                } else {
                    $this->msg->error("Une erreur s'est produite", $this->getUrl());
                }
            }
        }
        echo $this->twig->render('front/registration/index.html.twig', [
            'message'   => $this->msg,
            'registerToken'     => $registerToken
        ]);
    }
    /*
     * GERER LA DECONNEXION
     */
    public function logout() {
        if ($this->isLogged()) {
            session_unset();
            session_destroy();
        }
        header('Location: ?c=index');

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
