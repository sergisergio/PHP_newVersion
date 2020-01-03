<?php

namespace Controllers;

/**
 * class SearchController
 *
 * Cette classe gère le formulaire de recherche du blog
 */
class SearchController extends Controller
{
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
