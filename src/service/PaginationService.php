<?php

namespace Service;

use Models\Config;
use Models\Blog;

class PaginationService {

  protected $configModel;
  protected $projectModel;

  public function __construct() {
    $this->configModel = new Config;
    $this->blogModel = new Blog;
  }
  public function paginate($currentPage, $number_of_pages, $results_per_page) {
    if (isset($currentPage) AND !empty($currentPage) AND ($currentPage > 0 ) AND ($currentPage <= $number_of_pages)) {
      $start = ($currentPage-1)*(int)$results_per_page;
      $posts = $this->blogModel->getPostsPagination($start, $results_per_page);
      return $posts;
    }
    if (($currentPage > $number_of_pages) || ($currentPage < 1)) {
      header('Erreur 404', true, 404);
      include('views/404.html');
      exit();
    };
  }

  public function paginateCategory($category, $currentPage, $number_of_pages, $results_per_page) {
    if (isset($currentPage) AND !empty($currentPage) AND ($currentPage > 0 ) AND ($currentPage <= $number_of_pages)) {
      $start = ($currentPage-1)*(int)$results_per_page;
      $posts = $this->blogModel->getCategoryPagination($category, $start, $results_per_page);
      return $posts;
    }
    if (($currentPage > $number_of_pages) || ($currentPage < 1)) {
      header('Erreur 404', true, 404);
      include('views/404.html');
      exit();
    };
  }

  public function paginateTag($tag, $currentPage, $number_of_pages, $results_per_page) {
    if (isset($currentPage) AND !empty($currentPage) AND ($currentPage > 0 ) AND ($currentPage <= $number_of_pages)) {
      $start = ($currentPage-1)*(int)$results_per_page;
      $posts = $this->blogModel->getTagPagination($tag, $start, $results_per_page);
      return $posts;
    }
    if (($currentPage > $number_of_pages) || ($currentPage < 1)) {
      header('Erreur 404', true, 404);
      include('views/404.html');
      exit();
    };
  }
}
