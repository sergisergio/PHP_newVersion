<?php

namespace Service;

use Models\Config;
use Models\Blog;
use Models\User;
use Models\Project;
use Models\Category;
use Models\Tag;
use Models\Comment;

class PaginationService {

  protected $configModel;
  protected $blogModel;
  protected $userModel;
  protected $projectModel;
  protected $categoryModel;
  protected $commentModel;
  protected $tagModel;

  public function __construct() {
    $this->configModel = new Config;
    $this->blogModel = new Blog;
    $this->userModel = new User;
    $this->projectModel = new Project;
    $this->categoryModel = new Category;
    $this->tagModel = new Tag;
    $this->commentModel = new Comment;
  }
  /**
   * PAGINATION DES ARTICLES COTE FRONT
   */
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
  /**
   * PAGINER LES CATEGORIES COTE ADMIN
   */
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
  /**
   * PAGINER LES TAGS COTE ADMIN
   */
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
  /**
   * PAGINER LES MEMBRES COTE ADMIN
   */
  public function paginateUser($currentPage, $number_of_pages, $results_per_page) {
    if (isset($currentPage) AND !empty($currentPage) AND ($currentPage > 0 ) AND ($currentPage <= $number_of_pages)) {
      $start = ($currentPage-1)*(int)$results_per_page;
      $users = $this->userModel->getUsersPagination($start, $results_per_page);
      return $users;
    }
    if (($currentPage > $number_of_pages) || ($currentPage < 1)) {
      header('Erreur 404', true, 404);
      include('views/404.html');
      exit();
    };
  }
  /**
   * PAGINER LES PROJETS COTE ADMIN
   */
  public function paginateProject($currentPage, $number_of_pages, $results_per_page) {
    if (isset($currentPage) AND !empty($currentPage) AND ($currentPage > 0 ) AND ($currentPage <= $number_of_pages)) {
      $start = ($currentPage-1)*(int)$results_per_page;
      $projects = $this->projectModel->getProjectsPagination($start, $results_per_page);
      return $projects;
    }
    if (($currentPage > $number_of_pages) || ($currentPage < 1)) {
      header('Erreur 404', true, 404);
      include('views/404.html');
      exit();
    };
  }
  /**
   * PAGINER LES CATEGORIES
   */
  public function paginateCategoryAdmin($currentPage, $number_of_pages, $results_per_page) {
    if (isset($currentPage) AND !empty($currentPage) AND ($currentPage > 0 ) AND ($currentPage <= $number_of_pages)) {
      $start = ($currentPage-1)*(int)$results_per_page;
      $posts = $this->categoryModel->getCategoryPagination($start, $results_per_page);
      return $posts;
    }
    if (($currentPage > $number_of_pages) || ($currentPage < 1)) {
      header('Erreur 404', true, 404);
      include('views/404.html');
      exit();
    };
  }
  /**
   * PAGINER LES TAGS
   */
  public function paginateTagAdmin($currentPage, $number_of_pages, $results_per_page) {
    if (isset($currentPage) AND !empty($currentPage) AND ($currentPage > 0 ) AND ($currentPage <= $number_of_pages)) {
      $start = ($currentPage-1)*(int)$results_per_page;
      $tags = $this->tagModel->getTagPagination($start, $results_per_page);
      return $tags;
    }
    if (($currentPage > $number_of_pages) || ($currentPage < 1)) {
      header('Erreur 404', true, 404);
      include('views/404.html');
      exit();
    };
  }
  /**
   * PAGINER LES COMMENTAIRES
   */
  public function paginateCommentAdmin($currentPage, $number_of_pages, $results_per_page) {
    if (isset($currentPage) AND !empty($currentPage) AND ($currentPage > 0 ) AND ($currentPage <= $number_of_pages)) {
      $start = ($currentPage-1)*(int)$results_per_page;
      $comments = $this->commentModel->getCommentPagination($start, $results_per_page);
      return $comments;
    }
    if (($currentPage > $number_of_pages) || ($currentPage < 1)) {
      header('Erreur 404', true, 404);
      include('views/404.html');
      exit();
    };
  }
}
