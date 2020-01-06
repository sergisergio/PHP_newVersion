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
            $this->msg->success("Le membre a bien été modifié", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UN MEMBRE
     */
    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['userId']) && $user = $this->userModel->getUserById($_POST['userId'])) {
                if ($this->userModel->deleteUser($user['id'])) {
                    $this->msg->success("Le membre a bien été supprimé", $this->getUrl(true));
                } else {
                    $this->msg->error("Le membre n'a pas pu être supprimé", $this->getUrl(true));
                }
            } else {
                $this->msg->error("Le membre n'existe pas", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue.", $this->getUrl(true));
        }
    }
}
