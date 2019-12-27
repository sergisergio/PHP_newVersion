<?php

namespace Controllers;

/**
 * CLASSE GERANT LES COMMENTAIRES
 */
class CommentController extends Controller
{
    /**
     * AJOUTER UN COMMENTAIRE
     */
    public function addComment() {
        if ($this->isLogged()) {
            $config = $this->configModel->getConfig();
            $maxLength = $config['characters'];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (empty($_POST['content']) || empty($_POST['user_id']) || empty($_POST['post_id'])) {
                    $this->msg->error("Tous les champs n'ont pas été remplis", $this->getUrl(true) .'#comments-notification');
                } elseif (strlen($_POST['content']) > $maxLength) {
                    $this->msg->error("Le commentaire fait plus de $maxLength caractères", $this->getUrl(true));
                } else {
                    $user = $this->userModel->getUserById($_POST['user_id']);
                    $content = $_POST['content'];
                    $data = [
                        'user_id'   => $user['id'],
                        'post_id'   => $_POST['post_id'],
                        'content'   => $content,
                        'validated' => 1
                    ];
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
            $this->redirect404();
        }
    }
    /**
     * SUPPRIMER UN COMMENTAIRE
     */
    public function deleteComment() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['id']) && $comment = $this->commentModel->getCommentById($_GET['id'], $_GET['postId'])) {
            $this->commentModel->deleteComment($comment[0]['id']);
            $this->blogModel->minusNumberComment($_GET['postId']);
            $this->msg->success('Le commentaire a été supprimé !', $this->getUrl(true).'#comments');
        } else {
            $this->msg->error('Le commentaire n\'a pas pu été supprimé.', $this->getUrl(true));
        }
    }
    /**
     * AJOUTER UN SOUS-COMMENTAIRE
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
     */
    public function likes() {
        if ($this->isLogged()) {
            $this->commentModel->plusLikes() {
            }
        }
    }
    /**
     * AJOUTER UN DISLIKE A UN COMMENTAIRE
     */
    public function dislikes() {
        if ($this->isLogged()) {
            $this->commentModel->minusLikes() {
            }
        }
    }
}
