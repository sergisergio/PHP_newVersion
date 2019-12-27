<?php

namespace Service;

class DebugService {

  public function display_debug($key, $value, $debug=false) {
      if($debug){
        echo ''.$key . " = ";
        switch (gettype($value)) {
          case 'string' :
            echo $value;
            break;
          case 'array' :
          case 'object' :
          default :
            echo '';
            print_r($value);
            echo '';
            die();
            break;
        }
      }
  }

  public function display_debug2($value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
  }
}
