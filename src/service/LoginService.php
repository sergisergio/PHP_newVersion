<?php

namespace Service;

class Login{

  public function __construct() {
    $this->msg = new \Plasticbrain\FlashMessages\FlashMessages();
    $this->username = strip_tags(htmlspecialchars($_POST['username']));
    $this->password = strip_tags(htmlspecialchars($_POST['password']));
  }


  private function getUrl(bool $referer = false) {
        if ($referer == true) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
    }
}
