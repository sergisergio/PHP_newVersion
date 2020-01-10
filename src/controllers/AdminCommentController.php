<?php

namespace Controllers;

use Models\Comment;
use Service\SecurityService;

/**
 * classe AdminCommentController
 *
 * Cette classe gère les commentaires
 */
class AdminCommentController extends Controller
{
    protected $commentModel;
    protected $securityModel;

    /**
     * Constructeur
     *
     * REDIRIGE VERS LE FORMULAIRE DE CONNEXION SI LE MEMBRE N'EST PAS ADMINISTRATEUR
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAdmin()) {
            header('Location: ?c=login');
            exit;
        }
        $this->commentModel = new Comment;
        $this->securityService = new SecurityService;
    }
    /**
     * SUPPRIMER UN COMMENTAIRE
     *
     * récupère l'identifiant du commentaire via GET
     * vérifie que le token CSRF est bon
     * supprime le commentaire en base de données
     */
    public function deleteComment() {
        $session_token = $_SESSION['delete_comment_token'];
        $token = $_POST['delete_comment_token'];
        $comment['id'] = $_GET['id'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->commentModel->deleteComment($comment['id'])) {
                    $this->msg->success("Le commentaire a bien été supprimé", $this->getUrl(true));
                } else {
                    $this->msg->error("Le commentaire n'a pas pu être supprimé", $this->getUrl(true));
                }
            } else {
                $this->msg->error("Une erreur est survenue", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * PUBLIER OU DEPUBLIER UN COMMENTAIRE
     */
    public function toggleComment() {
        $published = $_GET['g'];
        $id = $_GET['id'];
        if ($published) {
            $this->commentModel->unPublish($id);
            $this->msg->success("Le commentaire a bien été dépublié !", $this->getUrl(true));
        } else {
            $this->commentModel->publish($id);
            $this->msg->success("Le commentaire a bien été publié !", $this->getUrl(true));
        }
    }
}
