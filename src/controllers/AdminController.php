<?php
namespace Controllers;
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
