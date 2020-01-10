<?php

namespace Service;

use Models\Security;

class LoginService {

  protected $securityModel;

  public function __construct() {
    $this->msg = new \Plasticbrain\FlashMessages\FlashMessages();
    $this->username = strip_tags(htmlspecialchars($_POST['username']));
    $this->password = strip_tags(htmlspecialchars($_POST['password']));
    $this->securityModel = new Security;
  }


  private function getUrl(bool $referer = false) {
        if ($referer == true) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
    }

  public function checkAttempts($array, $ip, $username) {
      $count = $this->securityModel->checkBruteForce($ip, $username);
      if ($count < 3) {
          $this->securityModel->registerAttempt($ip, $username);
          $count += 1;
          if ($count < 2) {
              $array["error"] = 'identifiant ou mot de passe incorrect !Il vous reste '.(3 - $count).' tentatives';
              $array["isSuccess"] = false;
              return $array;
          } elseif ($count == 2) {
              $array["error"] = 'identifiant ou mot de passe incorrect !Il vous reste une tentative';
              $array["isSuccess"] = false;
          } else {
              $array["error"] = 'Nombre de tentatives atteintes! Vous pourrez essayer de vous reconnecter dans 24h.';
              $array["isSuccess"] = false;
          }
      } elseif ($count == 3) {
          $attempts = $this->securityModel->getAttempts($ip);
          date_default_timezone_set('Europe/Paris');
          $now = strtotime(date("Y-m-d H:i:s"));
          $limitAttemptsDate = strtotime($attempts[2]['tried_at_plus_one_day']);
          if (isset($limitAttemptsDate)) {
              $diff = round(($limitAttemptsDate - $now)/3600);
              if ($diff > 0) {
                  $array["error"] = 'Nombre de tentatives atteintes! Vous pourrez essayer de vous reconnecter dans '.$diff.'h.';
                  $array["isSuccess"] = false;
              } else {
                  $this->securityModel->deleteAttempts($ip);
                  $this->securityModel->registerAttempt($ip, $username);
                  $count = 1;
                  $array["error"] = 'identifiant ou mot de passe incorrect !Il vous reste 2 tentatives';
              }
          }
      }
      return $array;
  }
}
