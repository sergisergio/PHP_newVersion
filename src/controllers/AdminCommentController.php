<?php

namespace Controllers;

use Models\Comment;

/**
 * classe AdminCommentController
 *
 * Cette classe gère les commentaires
 */
class AdminCommentController extends Controller
{
    protected $commentModel;
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
        $this->linkComment = new Comment;
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
            header('Location: ?c=adminDashboard&t=comments');
            exit;
        }
    }
}
