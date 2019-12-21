<?php

namespace Controllers;

/**
 * CLASSE GERANT LA PAGE D'ACCUEIL
 */
class IndexController extends Controller
{
    /*
     * AFFICHE LA PAGE D'ACCUEIL
     */
    public function index() {
        $projects = $this->projectModel->getAllProjects();
        $categories = $this->categoryModel->getAllCategories();
        $skills = $this->skillModel->getAllSkills();
        $skills2 = $this->skillModel->getAllSkills2();
        $description = $this->descriptionModel->getDescription();
        echo $this->twig->render('front/home/index.html.twig', [
            'projects' => $projects,
            'categories' => $categories,
            'skills' => $skills,
            'skills2' => $skills2,
            'description' => $description,
        ]);
    }
}
