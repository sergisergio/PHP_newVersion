<?php

namespace Controllers;

//use Models\Comment;
use \Twig_Loader_Filesystem;
use \Twig_Environment;
use Models\Model;
use Service\DebugService;

/**
 * CLASSE CONTROLLER
 */
class Controller
{
    protected $twig;
    protected $model;
    protected $msg;
    protected $debugService;

    public function __construct()
    {
        if (!session_id()) @session_start();
        $this->msg = new \Plasticbrain\FlashMessages\FlashMessages();

        $loader = new Twig_Loader_Filesystem('./views/');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => false,
            'debug' => true,
        ));
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addGlobal('_get', $_GET);
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Paris');

        $this->model = new Model;
        $this->debugService = new DebugService;
    }

    // Redirection 404
    protected function redirect404() {
        header('Erreur 404', true, 404);
        include('views/404.html');
        exit();
    }

    // session admin ?
    protected function isAdmin() {
        if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
            return true;
        } else {
            return false;
        }
    }

    // session user ?
    protected function isUser() {
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

    // admin ou user connecté
    protected function isLogged() {
        if ($this->isAdmin()) {
            return true;
        } elseif ($this->isUser()) {
            return true;
        } else {
            return false;
        }
    }

    // récupération url courante
    protected function getUrl(bool $referer = false) {
        if ($referer == true) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
    }

    // récupération uri courante
    protected function getUri(bool $referer = false) {
        if ($referer == true) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return "$_SERVER[REQUEST_URI]";
        }
    }
}
