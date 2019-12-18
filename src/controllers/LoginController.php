<?php
namespace Controllers;
class LoginController extends Controller
{
    /*
     * Show the login page
     */
    public function index() {
        $loginToken = md5(time()*rand(1, 1000));

        $_SESSION['loginToken'] = $loginToken;
        //var_dump($loginToken);
        //var_dump($_POST['loginToken']);
        //die();
        // if the user is loged, redirect to "my account"
        // TODO: redirect to "my account"
        if ( $this->isLogged())
            header('Location: ?c=index');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = strip_tags(htmlspecialchars($_POST['username']));
            $password = strip_tags(htmlspecialchars($_POST['password']));
            $userExist = $this->userModel->getUser($username, $password);
            // check if email & password are empty
            if (empty($username) || empty($password)) {
                $this->msg->error("Tous les champs n'ont pas été remplis", $this->getUrl());
            // check if the user exist
            } elseif ( !$userExist) {
                $this->msg->error("Identifiant ou mot de passe incorrect !", $this->getUrl());
            } else {
                // if role = 1, run admin session, else, run user session
                if (isset($_SESSION['loginToken']) AND isset($_POST['loginToken']) AND !empty($_SESSION['loginToken']) AND !empty($_POST['loginToken'])) {
                    // On vérifie que les deux correspondent
                    if ($_SESSION['loginToken'] == $_POST['loginToken']) {
                        if ($userExist && $userExist['roles'] == 1) {
                            $_SESSION['admin'] = $userExist;
                            header('Location: ' . '?c=adminDashboard');
                            exit;
                        } else {
                            $_SESSION['user'] = $userExist;
                            header('Location: ' . '?c=blog');
                            exit;
                        }
                    }
                    else {
                        $this->msg->error("Une erreur s'est produite", $this->getUrl());
                    }
                }
            }
        }

        echo $this->twig->render('front/login/index.html.twig', [
            'message'   => $this->msg,
            'token'     => $loginToken
        ]);
    }
    /*
     * Show the registration page
     */
    public function registration() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = strip_tags(htmlspecialchars($_POST['email']));
            $username = strip_tags(htmlspecialchars($_POST['username']));
            $password = strip_tags(htmlspecialchars($_POST['password']));
            $passwordCheck = strip_tags(htmlspecialchars($_POST['passwordCheck']));
            $mailExist = $this->userModel->checkUserByEmail($email);
            $userExist = $this->userModel->checkUserByUsername($username);
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
                $data = [
                    'name'      => $_POST['username'],
                    'email'     => $_POST['email'],
                    'password'  => sha1($_POST['password']),
                    'roles'     => 0,
                    'active'    => 1,
                    'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
                ];
                // create the user then redirect to "my account"

                //if (isset($_SESSION['registerToken']) AND isset($_POST['registerToken']) AND !empty($_SESSION['registerToken']) AND !empty($_POST['registerToken'])) {
                    // On vérifie que les deux correspondent
                    //if ($_SESSION['registerToken'] == $_POST['registerToken']) {
                        if ($this->userModel->setUser($data)) {
                        //TODO: redirect to "my account"
                            $this->msg->success("Compte créé", $this->getUrl());
                        } else {
                            $this->msg->error("Une erreur s'est produite", $this->getUrl());
                        }
                    //}
                    //var_dump($_SESSION['registerToken']);
                    //var_dump($_POST['registerToken']);
                    //die();

                //}
                //else {
                //    $this->msg->error("Une erreur s'est produite", $this->getUrl());
                //}
            }
        }
        echo $this->twig->render('front/registration/index.html.twig', [
            'message'   => $this->msg,
            'registerToken'     => $registerToken
        ]);
    }
    /*
     * log out the user then redirect to the home page
     */
    public function logout() {
        if ($this->isLogged()) {
            session_unset();
            session_destroy();
        }
        header('Location: ?c=index');

    }
}
