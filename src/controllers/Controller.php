<?php
namespace Controllers;
use Models\Comment;
use \Twig_Loader_Filesystem;
use \Twig_Environment;
use Models\Model;
use Models\Blog;
use Models\Project;
use Models\Category;
use Models\Skill;
use Models\User;
use Models\Description;
use Service\Register;
class Controller
{
    protected $twig;
    protected $model;
    protected $projectModel;
    protected $categoryModel;
    protected $blogModel;
    protected $skillModel;
    protected $userModel;
    protected $commentModel;
    protected $descriptionModel;
    protected $msg;
    function __construct()
    {
        //SESSION
        if (!session_id()) @session_start();
        // Flash messages
        $this->msg = new \Plasticbrain\FlashMessages\FlashMessages();
        // Twig Configuration
        $loader = new Twig_Loader_Filesystem('./views/');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => false,
            'debug' => true,
        ));
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addGlobal('_get', $_GET);
        $this->twig->addGlobal('session', $_SESSION);
        // Models
        $this->model = new Model;
        $this->blogModel = new Blog;
        $this->projectModel = new Project;
        $this->categoryModel = new Category;
        $this->skillModel = new Skill;
        $this->userModel = new User;
        $this->descriptionModel = new Description;
        $this->registerService = new Register;
        $this->commentModel = new Comment;
    }
    // Redirect to the 404 error page
    protected function redirect404() {
        header('This is not the page you are looking for', true, 404);
        include('views/404.html');
        exit();
    }
    // check if the image exist, then remove it
    protected function removeImage($image, $path) {
        if ($image != null) {
            if (file_exists($path . $image)){
                unlink($path . $image);
            }
        }
    }
    // check if logged as admin
    protected function isAdmin() {
        if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
            return true;
        } else {
            return false;
        }
    }
    // check if logged as simple user
    protected function isUser() {
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }
    // check if logged (admin or user)
    protected function isLogged() {
        if ($this->isAdmin()) {
            return true;
        } elseif ($this->isUser()) {
            return true;
        } else {
            return false;
        }
    }
    protected function getUrl(bool $referer = false) {
        if ($referer == true) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
    }
}
