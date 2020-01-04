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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $role = htmlspecialchars($_POST['role']);
            $active = htmlspecialchars($_POST['active']);
            $banned = htmlspecialchars($_POST['banned']);
            $id = htmlspecialchars($_POST['id']);

            $this->userModel->updateUser($role, $active, $banned, $id);
            header('Location: ?c=adminDashboard&t=users');
            exit;
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
