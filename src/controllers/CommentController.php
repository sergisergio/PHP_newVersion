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
        // if user or admin is logged
        if ($this->isLogged()) {
            $config = $this->model->getConfig();
            $maxLength = $config['characters'];
            /*
             * if the user or the admin submit a comment and if fields are not empty, add the comment
             * else, redirect 404
             */
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
            //echo '<pre>';
            //var_dump($comment);die();
            //echo '</pre>';
            $this->commentModel->deleteComment($comment[0]['id']);
            $this->msg->success('Le commentaire a été supprimé !', $this->getUrl(true).'#comments');
        } else {
            $this->msg->error('Le commentaire n\'a pas pu été supprimé.', $this->getUrl(true));
        }
    }
}
