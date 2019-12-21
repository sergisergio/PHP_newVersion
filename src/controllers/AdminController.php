<?php

namespace Controllers;

/**
 * class AdminController
 *
 * Cette classe redirige vers le formulaire de connexion si l'utilisateur n'a pas de session admin
 */
class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAdmin()) {
            header('Location: ?c=login');
            exit;
        }
    }
}
