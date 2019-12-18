<?php
namespace Controllers;
class BlogController extends Controller
{
    public function index() {
        // define how many results you want per page
        $results_per_page = $this->model->getConfig()['ppp'];
        // find out the number of results stored in database
        $number_of_posts = $this->blogModel->getNumberOfPosts();
        // determine number of total pages available
        $number_of_pages = ceil($number_of_posts/$results_per_page);
        // Minimum 1 page
        if ($number_of_pages == 0)
            $number_of_pages = 1;
        // determine which page number visitor is currently on
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        // check if the page exist
        if ($page > $number_of_pages || $page == 0) {
            $this->redirect404();
        }
        // determine the sql LIMIT starting number for the results on the displaying page
        $this_page_first_result = ($page-1)*$results_per_page;
        // retrieve selected results from database and display them on page
        $posts = $this->blogModel->getPostsPagination($this_page_first_result, $results_per_page);

        echo $this->twig->render('front/blog/index.html.twig', [
            'posts' => $posts,
            'number' => $number_of_posts,
            'numberOfPages' => $number_of_pages,
        ]);
    }
    /*
     * Show a blog post
     */
    public function post() {
        // if the post exist, show the post, else, redirect to a 404 error page
        if (isset($_GET['id']) && $post = $this->blogModel->getPostById($_GET['id'])) {
            // if the post is published or if the user is logged as admin, show the post, else, redirect to a 404 error page
            if ($post['active'] || $this->isAdmin()) {
                $post['content'] = htmlspecialchars_decode($post['content'], ENT_HTML5);
                // if user or admin is logged
                if ($this->isLogged()) {
                    $comments = $this->commentsModel->getVerifiedCommentsByPostId($post['id']);
                } else {
                    $comments = null;
                }
                echo $this->twig->render('front/post/index.html.twig', [
                    'post'      => $post,
                    'comments'  => $comments,
                    'message'   => $this->msg,
                    'maxLength' => $this->model->getConfig()['characters']
                ]);
            } else {
                $this->redirect404();
            }
        } else {
            $this->redirect404();
        }
    }
}
