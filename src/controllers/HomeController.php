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
        $update_about_token = $this->securityService->str_random(100);
        $update_skill_token = $this->securityService->str_random(100);
        $add_skill_token = $this->securityService->str_random(100);
        $delete_skill_token = $this->securityService->str_random(100);
        $add_skill2_token = $this->securityService->str_random(100);
        $delete_skill2_token = $this->securityService->str_random(100);
        $update_skill2_token = $this->securityService->str_random(100);
        $update_subtitle = $this->securityService->str_random(100);
        $_SESSION['token'] = $update_about_token;
        $_SESSION['update_skill_token'] = $update_skill_token;
        $_SESSION['add_skill_token'] = $add_skill_token;
        $_SESSION['delete_skill_token'] = $delete_skill_token;
        $_SESSION['add_skill2_token'] = $add_skill2_token;
        $_SESSION['delete_skill2_token'] = $delete_skill2_token;
        $_SESSION['update_skill2_token'] = $update_skill2_token;
        $_SESSION['update_subtitle'] = $update_subtitle;
        echo $this->twig->render('front/home/index.html.twig', [
            'projects'              => $projects,
            'categories'            => $categories,
            'skills'                => $skills,
            'skills2'               => $skills2,
            'description'           => $description,
            'message'               => $this->msg,
            'update_about_token'    => $update_about_token,
            'update_skill_token'    => $update_skill_token,
            'add_skill_token'       => $add_skill_token,
            'delete_skill_token'    => $delete_skill_token,
            'add_skill2_token'      => $add_skill2_token,
            'delete_skill2_token'   => $delete_skill2_token,
            'update_skill2_token'   => $update_skill2_token,
            'update_subtitle'       => $update_subtitle,
        ]);
    }
}
