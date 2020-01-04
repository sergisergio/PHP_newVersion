<?php

namespace Controllers;

use Models\Category;
use Models\Project;
use Models\Skill;
use Models\Description;

/**
 * CLASSE GERANT LA PAGE D'ACCUEIL
 */
class HomeController extends Controller
{
    protected $skillModel;
    protected $categoryModel;
    protected $projectModel;
    protected $descriptionModel;

    public function __construct() {
        parent::__construct();
        $this->projectModel = new Project;
        $this->categoryModel = new Category;
        $this->skillModel = new Skill;
        $this->descriptionModel = new Description;
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
        echo $this->twig->render('front/home/index.html.twig', [
            'projects'    => $projects,
            'categories'  => $categories,
            'skills'      => $skills,
            'skills2'     => $skills2,
            'description' => $description,
            'message'     => $this->msg,
        ]);
    }
}
