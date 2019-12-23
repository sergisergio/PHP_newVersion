<?php

namespace Controllers;

/**
 * CLASSE GERANT LA PARTIE ADMIN
 */
class AdminDashboardController extends AdminController
{
    /*
     * AFFICHER LES ARTICLES
     */
    public function index() {
        $posts = $this->blogModel->getAllPostsWithUsers();
        $categories = $this->categoryModel->getAllCategories();
        echo $this->twig->render('admin/dashboard/index.html.twig', [
            'message'   => $this->msg,
            'posts'     => $posts,
            'categories'     => $categories
        ]);
    }
    /*
     * AFFICHER LES MEMBRES
     */
    public function users() {
        $users = $this->userModel->getAllUsers();
        echo $this->twig->render('admin/dashboard/users.html.twig', [
            'message'   => $this->msg,
            'users'     => $users
        ]);
    }
    /*
     * AFFICHER LES CATEGORIES
     */
    public function categories() {
        $categories = $this->categoryModel->getAllCategories();
        echo $this->twig->render('admin/dashboard/categories.html.twig', [
            'message'   => $this->msg,
            'categories'     => $categories
        ]);
    }
    /*
     * AFFICHER LES COMMENTAIRES
     */
    public function comments() {
        $comments = $this->commentModel->getAllComments();
        echo $this->twig->render('admin/dashboard/comments.html.twig', [
            'message'   => $this->msg,
            'comments'     => $comments
        ]);
    }
    /*
     * AFFICHER LES TAGS
     */
    public function tags() {
        $tags = $this->tagModel->getAllTags();
        echo $this->twig->render('admin/dashboard/tags.html.twig', [
            'message'   => $this->msg,
            'tags'     => $tags
        ]);
    }
    /*
     * AFFICHER LES PROJETS
     */
    public function projects() {
        $projects = $this->projectModel->getAllProjects();
        echo $this->twig->render('admin/dashboard/projects.html.twig', [
            'message'   => $this->msg,
            'projects'  => $projects
        ]);
    }
    /**
     * METTRE A JOUR LA CONFIGURATION
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
     * METTRE A JOUR UN MEMBRE
     */
    public function updateUser() {
        // if it's a userDown post
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userDown']) && !empty($_POST['userDown'])) {
            $userId = $_POST['userDown'];
            $user = $this->userModel->getUserById($userId);
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
    /**
     * AJOUTER UN ARTICLE
     */
    public function addPost() {
        $title = htmlspecialchars($_POST['title']);
        $content =  htmlspecialchars($_POST['content'], ENT_HTML5);
        $category = htmlspecialchars($_POST['category']);
        $image = htmlspecialchars($_FILES['file_extension']['name']);
        $user_id = $_SESSION['admin']['id'];
        $file_extension = $_FILES['file_extension'];
        $file_extension_error = $_FILES['file_extension']['error'];
        $file_extension_size = $_FILES['file_extension']['size'];
        $file_extension_tmp = $_FILES['file_extension']['tmp_name'];

        //$this->blogModel->setPost($data);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($file_extension) AND $file_extension_error == 0) {
                if ($file_extension_size <= 1000000) {
                    $infosfichier = pathinfo($image);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_access = array('jpg', 'jpeg', 'gif', 'png');
                    if (in_array($extension_upload, $extensions_access)) {
                        move_uploaded_file(
                            $file_extension_tmp,
                            'assets/img/' . basename($image));
                        $this->imageModel->setImage($image);
                        $imageId = $this->imageModel->getId($image);
                        $imageId = $imageId['id'];
                    }
                }
            }
            else {
                $imageId = 14;
            }
            $data = [
                    'title'         => $title,
                    'content'       => $content,
                    'user_id'       => $user_id,
                    'img_id'        => $imageId,
                    'published'     => 1,
                    'numberComments' => 0
                ];

            if ($this->blogModel->setPost($data)) {
                $last_id = $this->blogModel->getLastId();
                $this->categoryModel->addCategoryToPost($category, $last_id);
                $this->categoryModel->plusNumberPosts($category);
                $this->msg->success("L'article a bien été ajouté !", $this->getUrl());
            } else {
                $this->msg->error("L'article n'a pas pu être ajouté.", $this->getUrl());
            }
        } else {
            header('Location: ' . '?c=adminDashboard');
            exit;
        }
    }
    /**
     * SUPPRIMER UN ARTICLE
     */
    public function deletePost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $postId = $_POST['postId'];
            if (isset($postId) && $post = $this->blogModel->getPostById($_POST['postId'])) {
                $category = $post['category'];
                $imageId = $post['img_id'];

                if ($this->blogModel->deletePost($post['id'])) {
                    $this->imageModel->deleteImage($imageId);
                    $this->categoryModel->minusNumberPosts($category);
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
    /**
     * SUPPRIMER UN MEMBRE
     */
    public function deleteUser() {
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
    /**
     * AJOUTER UNE CATEGORIE
     */
    public function addCategory() {
        $name = htmlspecialchars($_POST['name']);
        $data = [
                    'name'         => $name,
                    'numberPosts'  => 0
                ];
        //$this->blogModel->setPost($data);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->categoryModel->setCategory($data)) {
                $this->msg->success("La catégorie a bien été ajoutée !", $this->getUrl());
            } else {
                $this->msg->error("La catégorie n'a pas pu être ajoutée.", $this->getUrl());
            }
        } else {
            header('Location: ' . '?c=adminDashboard&t=categories');
            exit;
        }
    }
    /**
     * SUPPRIMER UNE CATEGORIE
     */
    public function deleteCategory() {
        $category['id'] = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->categoryModel->deleteCategory($category['id'])) {
                    $this->msg->success("La catégorie a bien été supprimée", $this->getUrl());
                } else {
                    $this->msg->error("La catégorie n'a pas pu être supprimée", $this->getUrl());
                }
        } else {
            //redirect to the list of blog posts
            header('Location: ?c=adminDashboard&t=categories');
            exit;
        }
    }
    /**
     * SUPPRIMER UN COMMENTAIRE
     */
    public function deleteComment() {
        $comment['id'] = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->commentModel->deleteComment($comment['id'])) {
                    $this->msg->success("Le commentaire a bien été supprimé", $this->getUrl());
                } else {
                    $this->msg->error("Le commentaire n'a pas pu être supprimé", $this->getUrl());
                }
        } else {
            //redirect to the list of blog posts
            header('Location: ?c=adminDashboard&t=comments');
            exit;
        }
    }
    /**
     * AJOUTER UNE ETIQUETTE
     */
    public function addTag() {
        $name = htmlspecialchars($_POST['name']);
        $data = [
                    'name' => $name,
                    'numberPosts'  => 0
                ];
        //$this->blogModel->setPost($data);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->tagModel->setTag($data)) {
                $this->msg->success("Le tag a bien été ajouté !", $this->getUrl());
            } else {
                $this->msg->error("Le tag n'a pas pu être ajouté.", $this->getUrl());
            }
        } else {
            header('Location: ' . '?c=adminDashboard&t=tags');
            exit;
        }
    }
    /**
     * SUPPRIMER UNE ETIQUETTE
     */
    public function deleteTag() {
        $tag['id'] = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->tagModel->deleteTag($tag['id'])) {
                    $this->msg->success("Le tag a bien été supprimé", $this->getUrl());
                } else {
                    $this->msg->error("Le tag n'a pas pu être supprimé", $this->getUrl());
                }
        } else {
            //redirect to the list of blog posts
            header('Location: ?c=adminDashboard&t=tags');
            exit;
        }
    }
}
