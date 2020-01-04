<?php

namespace Controllers;

use Models\Blog;
//use Service\PaginationService;
use Models\Config;
use Models\Tag;
use Models\Category;
use Models\Link;

/**
 * class SearchController
 *
 * Cette classe gère le formulaire de recherche du blog
 */
class BlogSearchController extends Controller
{
    protected $blogModel;
    //protected $paginationService;
    protected $configModel;
    protected $categoryModel;
    protected $tagModel;
    protected $linkModel;

    public function __construct() {
        parent::__construct();
        $this->blogModel = new Blog;
        //$this->paginationService = new PaginationService;
        $this->configModel = new Config;
        $this->categoryModel = new Category;
        $this->tagModel = new Tag;
        $this->linkModel = new Link;
    }
    /**
     * METHODE QUI TRAITE LA RECHERCHE ET RETOURNE LA PAGE ADEQUATE
     */
    public function index($search)
    {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        if (isset($search) && $search != null) {
            $results_per_page = $this->configModel->getConfig()['ppp'];
            $populars = $this->blogModel->getMostSeens();
            $categories = $this->categoryModel->getAllCategories();
            $tags = $this->tagModel->getAllTags();
            $url = $this->getUrl();
            $posts = $this->blogModel->searchRequest($search);
            $number = $this->blogModel->countSearchRequest($search);
            $number = (int)$number;
            $number_of_pages = ceil($number/$results_per_page);
            if ($number > 0)
            {
              if (isset($_GET['page']) AND !empty($_GET['page']) AND ($_GET['page'] > 0 ) AND ($_GET['page'] <= $number_of_pages)) {
                  $_GET['page'] = intval($_GET['page']);
                  $currentPage = $_GET['page'];
              } else {
                  $currentPage = 1;
              }
              if ($currentPage > $number_of_pages) {
                  $this->redirect404();
              }
              $start = ($currentPage-1)*(int)$results_per_page;
              $posts = $this->blogModel->getSearchPagination($search, $start, $results_per_page);
            } else {
              $posts = [];
            }
            echo $this->twig->render('front/blog/_partials/search/index.html.twig', [
                'posts'         => $posts,
                'number'        => $number,
                'numberOfPages' => $number_of_pages,
                'categories'    => $categories,
                'tags'          => $tags,
                'url'           => $url,
                'populars'      => $populars,
                'q'             => $search,
            ]);
        }
    }
}
