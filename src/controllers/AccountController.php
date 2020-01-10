<?php

namespace Controllers;

/**
 * CLASSE GERANT LA PAGE D'ACCUEIL
 */
class AccountController extends Controller
{
    /*
     * AFFICHE UN COMPTE MEMBRE
     */
    public function index() {

        echo $this->twig->render('front/account/index.html.twig', []);
    }
}
