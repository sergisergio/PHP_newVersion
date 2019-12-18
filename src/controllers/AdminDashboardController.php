<?php
namespace Controllers;
class AdminDashboardController extends AdminController
{
    /*
     * Show the dashboard
     */
    public function index() {
        $config = $this->model->getConfig();
        $users = $this->userModel->getAllUsers();
        echo $this->twig->render('admin/dashboard/index.html.twig', [
            'config'    => $config,
            'message'   => $this->msg,
            'users'     => $users
        ]);
    }
    /**
     * Update the config
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
     * Upgrade or downgrade user role
     */
    public function updateUser() {
        // if it's a userDown post
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userDown']) && !empty($_POST['userDown'])) {
            $userId = $_POST['userDown'];
            $user = $this->usersModel->getUserById($userId);
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
    public function removeUser () {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove']) && !empty($_POST['remove'])) {
            $userId = $_POST['remove'];
            $user = $this->usersModel->getUserById($userId);
            if ($this->usersModel->deleteUser($userId)) {
                $this->msg->success("l'utilisateur ".$user['name']." a été supprimé", $this->getUrl(true));
            } else {
                $this->msg->error("l'utilisateur ".$user['name']." n'a pas pu être supprimé", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Erreur lors de l'envoie des données", $this->getUrl(true));
        }
    }
}
