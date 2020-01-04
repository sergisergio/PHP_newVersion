<?php

namespace Controllers;

use Models\User;

/**
 * classe AdminUsersController
 *
 * Cette classe gère les membres
 */
class AdminUsersController extends Controller
{
    protected $userModel;

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
        $this->userModel = new User;
    }
    /**
     * METTRE A JOUR UN MEMBRE
     */
    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userDown']) && !empty($_POST['userDown'])) {
            $userId = $_POST['userDown'];
            $user = $this->userModel->getUserById($userId);
            if ($this->usersModel->updateRoleUser(0, $userId)) {
                $this->msg->success($user['name']." est passé au rang de simple utilisateur", $this->getUrl(true));
            } else {
                $this->msg->error("Une erreur s'est produite", $this->getUrl(true));
            }
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
     * SUPPRIMER UN MEMBRE
     */
    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['userId']) && $post = $this->userModel->getUserById($_POST['postId'])) {
                if ($this->userModel->deleteUser($user['id'])) {
                    $this->msg->success("Le membre a bien été supprimé", $this->getUrl());
                } else {
                    $this->msg->error("Le membre n'a pas pu être supprimé", $this->getUrl());
                }
            } else {
                $this->msg->error("Le membre n'existe pas", $this->getUrl());
            }
        } else {
            header('Location: ?c=adminDashboard&t=users');
            exit;
        }
    }
}
