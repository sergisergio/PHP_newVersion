<?php

namespace Controllers;

use Models\Category;
use Models\Project;
use Models\Skill;
use Models\Description;
use Service\SecurityService;

/**
 * CLASSE GERANT LA PAGE D'ACCUEIL
 */
class HomeController extends Controller
{
    protected $skillModel;
    protected $categoryModel;
    protected $projectModel;
    protected $descriptionModel;
    protected $securityService;

    public function __construct() {
        parent::__construct();
        $this->projectModel = new Project;
        $this->categoryModel = new Category;
        $this->skillModel = new Skill;
        $this->descriptionModel = new Description;
        $this->securityService = new SecurityService;
    }
    /*
     * AFFICHE LA PAGE D'ACCUEIL
     */
    public function index() {
        $projects = $this->projectModel->getAllPublishedProjects();
        $categories = $this->categoryModel->getAllCategories();
        $skills = $this->skillModel->getAllSkills();
        $skills2 = $this->skillModel->getAllSkills2();
        $description = $this->descriptionModel->getDescription();
        $token = bin2hex(openssl_random_pseudo_bytes(6));
        $_SESSION['update_about_token'] = $token;
        $_SESSION['update_skill_token'] = $token;
        $_SESSION['add_skill_token'] = $token;
        $_SESSION['delete_skill_token'] = $token;
        $_SESSION['add_skill2_token'] = $token;
        $_SESSION['delete_skill2_token'] = $token;
        $_SESSION['update_skill2_token'] = $token;
        $_SESSION['update_subtitle'] = $token;

        echo $this->twig->render('front/home/index.html.twig', [
            'projects'              => $projects,
            'categories'            => $categories,
            'skills'                => $skills,
            'skills2'               => $skills2,
            'description'           => $description,
            'message'               => $this->msg,
            'update_about_token'    => $token,
            'update_skill_token'    => $token,
            'add_skill_token'       => $token,
            'delete_skill_token'    => $token,
            'add_skill2_token'      => $token,
            'delete_skill2_token'   => $token,
            'update_skill2_token'   => $token,
            'update_subtitle'       => $token,
        ]);
    }
}
