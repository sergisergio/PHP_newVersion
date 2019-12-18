<?php
namespace Controllers;
class AdminDashboardController extends AdminController
{
    /*
     * Show the dashboard with posts
     */
    public function index() {
        //$config = $this->model->getConfig();
        //$users = $this->userModel->getAllUsers();
        $posts = $this->blogModel->getAllPostsWithUsers();
        echo $this->twig->render('admin/dashboard/index.html.twig', [
            //'config'    => $config,
            'message'   => $this->msg,
            //'users'     => $users,
            'posts'     => $posts
        ]);
    }
    /*
     * Show the dashboard with users
     */
    public function users() {
        //$config = $this->model->getConfig();
        $users = $this->userModel->getAllUsers();
        //$posts = $this->blogModel->getAllPostsWithUsers();
        echo $this->twig->render('admin/dashboard/users.html.twig', [
            //'config'    => $config,
            'message'   => $this->msg,
            'users'     => $users,
            //'posts'     => $posts
        ]);
    }
    /*
     * Show the dashboard with users
     */
    public function comments() {
        //$config = $this->model->getConfig();
        $comments = $this->commentModel->getAllComments();
        //$posts = $this->blogModel->getAllPostsWithUsers();
        echo $this->twig->render('admin/dashboard/comments.html.twig', [
            //'config'    => $config,
            'message'   => $this->msg,
            'comments'     => $comments,
            //'posts'     => $posts
        ]);
    }
    /**
     * Update the config
     */
    public function editConfig() {
        // if it's a post method & edit_config is submitted & ppp value is not empty, then update the config, else, redirect to a 404 error page
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['edit_config'])) {
            if (empty($_POST['ppp']) || empty($_POST['cpc'])) {
                $this->msg->error("Tous les champs n'ont pas été remplis", $this->getUrl(true));
            } else {
                // post per page
                $ppp = $_POST['ppp'];
                // characters per comment
                $cpc = $_POST['cpc'];
                //if it works, redirect to the dashboard and show success message
                if ($this->model->updateConfig($ppp, $cpc)) {
                    $this->msg->success("La configuration a bien été modifié", $this->getUrl(true));
                // if it doesn't works, redirect to the dashboard and show error message
                } else {
                    $this->msg->error("Une erreur s'est produite", $this->getUrl(true));
                }
            }
        } else {
            $this->redirect404();
        }
    }
    /**
     * Upgrade or downgrade user role
     */
    public function updateUser() {
        // if it's a userDown post
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userDown']) && !empty($_POST['userDown'])) {
            $userId = $_POST['userDown'];
            $user = $this->usersModel->getUserById($userId);
            if ($this->usersModel->updateRoleUser(0, $userId)) {
                $this->msg->success($user['name']." est passé au rang de simple utilisateur", $this->getUrl(true));
            } else {
                $this->msg->error("Une erreur s'est produite", $this->getUrl(true));
            }
        // if it's a userUp post
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userUp']) && !empty($_POST['userUp'])) {
            $userId = $_POST['userUp'];
            $user = $this->usersModel->getUserById($userId);
            if ($this->usersModel->updateRoleUser(1, $userId)) {
                $this->msg->success($user['name']." est passé au rang d'administrateur", $this->getUrl(true));
            } else {
                $this->msg->error("Une erreur s'est produite", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Erreur lors de l'envoie des données", $this->getUrl(true));
        }
    }
    public function removeUser () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove']) && !empty($_POST['remove'])) {
            $userId = $_POST['remove'];
            $user = $this->usersModel->getUserById($userId);
            if ($this->usersModel->deleteUser($userId)) {
                $this->msg->success("l'utilisateur ".$user['name']." a été supprimé", $this->getUrl(true));
            } else {
                $this->msg->error("l'utilisateur ".$user['name']." n'a pas pu être supprimé", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Erreur lors de l'envoie des données", $this->getUrl(true));
        }
    }

    public function addPost() {
        $title = htmlspecialchars($_POST['title']);
        $content =  htmlspecialchars($_POST['content'], ENT_HTML5);
        $user_id = $_SESSION['admin']['id'];
        $data = [
                    'title'         => $title,
                    'content'       => $content,
                    'user_id'       => $user_id,
                    'published'     => 1
                ];
        $this->blogModel->setPost($data);
        header('Location: ' . '?c=adminDashboard');
        exit;
        /*if ($this->blogModel->setPost($data)) {
            $this->msg->success('L\'article a bien été ajouté !', $this->getUrl());
        } else {
            $this->msg->error('L\'article n\'a pas pu être ajouté.', $this->getUrl());
        }*/
    }

    public function deletePost() {
        // if method is "post" and if the blog post exist => remove the blog post + comment + image
        // TODO: remove comments
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['postId']) && $post = $this->blogModel->getPostById($_POST['postId'])) {

                //$image = $post['image'];
                //$path = 'assets/img/uploads/';

                // remove image
                //$this->removeImage($image, $path);

                if ($this->blogModel->deletePost($post['id'])) {
                    $this->msg->success("L'article a bien été supprimé", $this->getUrl());
                } else {
                    $this->msg->error("L'article n'a pas pu être supprimé", $this->getUrl());
                }
            } else {
                //redirect to the list of blog posts
                $this->msg->error("L'article n'existe pas", $this->getUrl());
            }
        } else {
            //redirect to the list of blog posts
            header('Location: ?c=adminDashboard');
            exit;
        }
    }

    public function deleteUser() {
        // if method is "post" and if the blog post exist => remove the blog post + comment + image
        // TODO: remove comments
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['userId']) && $post = $this->userModel->getUserById($_POST['postId'])) {

                //$image = $post['image'];
                //$path = 'assets/img/uploads/';

                // remove image
                //$this->removeImage($image, $path);

                if ($this->userModel->deleteUser($user['id'])) {
                    $this->msg->success("Le membre a bien été supprimé", $this->getUrl());
                } else {
                    $this->msg->error("Le membre n'a pas pu être supprimé", $this->getUrl());
                }
            } else {
                //redirect to the list of blog posts
                $this->msg->error("Le membre n'existe pas", $this->getUrl());
            }
        } else {
            //redirect to the list of blog posts
            header('Location: ?c=adminDashboard&t=users');
            exit;
        }
    }
}
