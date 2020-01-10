<?php

namespace Controllers;

use Models\User;
use Service\SecurityService;

/**
 * classe AdminUsersController
 *
 * Cette classe gère les membres
 */
class AdminUsersController extends Controller
{
    protected $userModel;
    protected $securityService;
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
        $this->securityService = new SecurityService;
    }
    /**
     * METTRE A JOUR UN MEMBRE
     *
     * - récupère identifiant, role et statut du membre
     * - vérifie que le token CSRF est bon
     * - met à jour le membre dans la base de données
     */
    public function updateUser() {
        $role = htmlspecialchars($_POST['role']);
        $active = htmlspecialchars($_POST['active']);
        $banned = htmlspecialchars($_POST['banned']);
        $id = htmlspecialchars($_POST['id']);
        $session_token = $_SESSION['update_user_token'];
        $token = $_POST['update_user_token'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $this->userModel->updateUser($role, $active, $banned, $id);
                $this->msg->success("Le membre a bien été modifié", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UN MEMBRE
     *
     * - récupère l'identifiant du membre
     * - vérifie que le token CSRF est bon
     * - supprime le membre de la base de données
     */
    public function deleteUser() {
        $session_token = $_SESSION['delete_user_token'];
        $token = $_POST['delete_user_token'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
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
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
}
