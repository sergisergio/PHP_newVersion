<?php

namespace Controllers;

/**
 * classe AdminProjectsController
 *
 * Cette classe gère les projets
 */
class AdminProjectsController extends Controller
{
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
    }
}
