<?php

namespace Controllers;

use Models\Config;
use Models\User;
use Models\Blog;
use Models\Comment;
use Service\SecurityService;

/**
 * CLASSE GERANT LES COMMENTAIRES
 */
class BlogCommentController extends Controller
{
    protected $configModel;
    protected $userModel;
    protected $blogModel;
    protected $commentModel;
    protected $securityService;

    public function __construct()
    {
        parent::__construct();
        if (!$this->isAdmin()) {
            header('Location: ?c=login');
            exit;
        }
        $this->configModel = new Config;
        $this->userModel = new User;
        $this->blogModel = new Blog;
        $this->commentModel = new Comment;
        $this->securityService = new SecurityService;
    }

    /**
     * AJOUTER UN COMMENTAIRE
     *
     * - récupère auteur et contenu du commentaire
     * - vérifie que le token CSRF est bon
     * - ajoute le commentaire en base de données
     * - incrémente le nombre de commentaires de l'article
     */
    public function addComment() {

        $session_token = $_SESSION['add_comment_token'];
        $token = $_POST['add_comment_token'];
        $id = $_POST['user_id'];
        $postId = $_POST['post_id'];
        $user = $this->userModel->getUserById($id);
        $content = $_POST['content'];
        $data = [
            'user_id'   => $user['id'],
            'post_id'   => $postId,
            'content'   => $content,
            'validated' => 1
            ];

        if ($this->isLogged()) {
            $config = $this->configModel->getConfig();
            $maxLength = $config['characters'];

            if ($this->securityService->checkCsrf($token, $session_token)) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (empty($content) || empty($id) || empty($postId)) {
                        $this->msg->error("Tous les champs n'ont pas été remplis", $this->getUrl(true) .'#comments-notification');
                    } elseif (strlen($content) > $maxLength) {
                        $this->msg->error("Le commentaire fait plus de $maxLength caractères", $this->getUrl(true));
                    } else {
                        if ($this->commentModel->addComment($data)) {
                            $this->blogModel->addNumberComment($data['post_id']);
                            $this->msg->warning("Commentaire en attente de validation", $this->getUrl(true));
                        } else {
                            $this->msg->error("Le commentaire n'a pas pu être ajouté", $this->getUrl(true));
                        }
                    }
                } else {
                    $this->redirect404();
                }
            } else {
                $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
            }
        } else {
            $this->redirect404();
        }
    }

    public function updateComment() {
        $id = $_POST['comment_id'];
        $postId = $_POST['post_id'];
        $userId = $_POST['user_id'];
        $user = $this->userModel->getUserById($id);
        $content = $_POST['content'];

        $data = [
            'user_id'   => $userId,
            'post_id'   => $postId,
            'content'   => $content,
            'comment_id' => $id,
            'validated' => 1
            ];

        //if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (empty($content) || empty($id) || empty($postId)) {
                    echo 'champs vides';
                    //$this->msg->error("Tous les champs n'ont pas été remplis", $this->getUrl(true) .'#comments-notification');
                } elseif (strlen($content) > 250) {
                    $this->msg->error("Le commentaire fait plus de 250 caractères", $this->getUrl(true));
                } else {
                    if ($this->commentModel->updateComment($data)) {
                        //$this->blogModel->addNumberComment($data['post_id']);
                        $this->msg->warning("Commentaire en attente de validation", $this->getUrl(true));
                    } else {
                        //$this->msg->error("Le commentaire n'a pas pu être ajouté", $this->getUrl(true));
                    }
                }
            } else {
                $this->redirect404();
            }
        //}
    }
    /**
     * SUPPRIMER UN COMMENTAIRE
     *
     * - récupère le commentaire en fonction de son identifiant
     * - supprime le commentaire en base de données
     * - décrémente le nombre de commentaire liés à l'article
     * - TOKEN CSRF A FAIRE
     */
    public function deleteComment() {
        $id = $_POST['id'];
        $postId = $_POST['postId'];
        $userId = $_POST['userId'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($id) && $comment = $this->commentModel->getCommentById($id)) {
                if ($comment[0]['post_id'] == $postId) {
                    if (($userId == $_SESSION['admin']['id']) || ($userId == $_SESSION['user']['id'])) {
                        $this->commentModel->deleteComment($id);
                        $this->blogModel->minusNumberComment($postId);
                        $this->msg->warning("Commentaire en attente de validation", $this->getUrl(true));
                    }
                }
            }
        } else {
            $this->msg->error('Le commentaire n\'a pas pu été supprimé.', $this->getUrl(true));
        }
    }
    /**
     * AJOUTER UN SOUS-COMMENTAIRE
     *
     * A VERIFIER
     * TOKEN CSRF A FAIRE
     */
    public function addSubComment() {
        if ($this->isLogged()) {
            $config = $this->configModel->getConfig();
            $maxLength = $config['characters'];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (empty($_POST['content']) || empty($_POST['user_id']) || empty($_POST['post_id']) || empty($_POST['comment_id'])) {
                    $this->msg->error("Tous les champs n'ont pas été remplis", $this->getUrl(true) .'#comments-notification');
                } elseif (strlen($_POST['content']) > $maxLength) {
                    $this->msg->error("Le commentaire fait plus de $maxLength caractères", $this->getUrl(true));
                } else {
                    $user = $this->userModel->getUserById($_POST['user_id']);
                    $content = $_POST['content'];
                    $data = [
                        'user_id'   => $user['id'],
                        'post_id'   => $_POST['post_id'],
                        'comment_id' => $_POST['comment_id'],
                        'content'   => $content,
                        'validated' => 1
                    ];
                    if ($this->commentModel->addSubComment($data)) {
                        //$this->blogModel->addNumberComment($data['post_id']);
                        $this->msg->warning("Commentaire en attente de validation", $this->getUrl(true));
                    } else {
                        $this->msg->error("Le commentaire n'a pas pu être ajouté", $this->getUrl(true));
                    }
                }
            } else {
                $this->redirect404();
            }
        } else {
            $this->redirect404();
        }
    }
    /**
     * AJOUTER UN LIKE A UN COMMENTAIRE
     *
     * A FAIRE ET A VERIFIER
     */
    public function likes() {
        if ($this->isLogged()) {
            $this->commentModel->plusLikes();
            }
    }
    /**
     * AJOUTER UN DISLIKE A UN COMMENTAIRE
     *
     * A FAIRE ET A VERIFIER
     */
    public function dislikes() {
        if ($this->isLogged()) {
            $this->commentModel->minusLikes();
        }
    }
}
