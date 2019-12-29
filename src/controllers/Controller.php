<?php

namespace Controllers;

use Models\Comment;
use \Twig_Loader_Filesystem;
use \Twig_Environment;
use Models\Model;
use Models\Config;
use Models\Blog;
use Models\Project;
use Models\Category;
use Models\Tag;
use Models\Skill;
use Models\User;
use Models\Description;
use Models\Security;
use Models\Link;
use Service\SecurityService;
use Service\RegisterService;
use Service\PaginationService;
use Service\MailService;
use Service\DebugService;
use Service\UploadService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Models\Image;

/**
 * CLASSE CONTROLLER
 */
class Controller
{
    protected $twig;
    protected $model;
    protected $configModel;
    protected $projectModel;
    protected $categoryModel;
    protected $blogModel;
    protected $skillModel;
    protected $userModel;
    protected $commentModel;
    protected $descriptionModel;
    protected $imageModel;
    protected $linkModel;
    protected $msg;
    protected $mail;
    protected $securityModel;
    protected $securityService;
    protected $registerService;
    protected $paginationService;
    protected $uploadService;
    protected $debugService;

    public function __construct()
    {
        // sémarrage session
        if (!session_id()) @session_start();
        // messages Flash
        $this->msg = new \Plasticbrain\FlashMessages\FlashMessages();
        // Configuration Twig
        $loader = new Twig_Loader_Filesystem('./views/');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => false,
            'debug' => true,
        ));
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addGlobal('_get', $_GET);
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Paris');
        // Models
        $this->model = new Model;
        $this->configModel = new Config;
        $this->blogModel = new Blog;
        $this->projectModel = new Project;
        $this->categoryModel = new Category;
        $this->tagModel = new Tag;
        $this->skillModel = new Skill;
        $this->userModel = new User;
        $this->descriptionModel = new Description;
        $this->imageModel = new Image;
        $this->securityService = new SecurityService;
        $this->registerService = new RegisterService;
        $this->debugService = new DebugService;
        $this->paginationService = new PaginationService;
        $this->mailService = new MailService;
        $this->uploadService = new UploadService;
        $this->securityModel = new Security;
        $this->commentModel = new Comment;
        $this->linkModel = new Link;
        $this->mail = new PHPMailer(true);
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
}
