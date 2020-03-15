<?php

require_once "vendor/autoload.php";

$class = "Controllers\\" . (isset($_GET['c']) ? ucfirst($_GET['c']) . 'Controller' : 'HomeController');
$target = isset($_GET['t']) ? $_GET['t'] : "index";
$getParams = isset($_GET['params']) ? $_GET['params'] : null;
$postParams = isset($_POST['params']) ? $_POST['params'] : null;
$params = [
    "get"  => $getParams,
    "post" => $postParams
];
if (class_exists($class, true)) {
    $class = new $class();
    if (in_array($target, get_class_methods($class))) {
        call_user_func_array([$class, $target], $params);
    } else {
        call_user_func([$class, "index"]);
    }
} else {
    header('Erreur 404', true, 404);
    include('views/404.html');
    exit();
}
/*if (is_mod_rewrite_enabled()) {
  print "The apache module mod_rewrite is enabled.<br/>\n";
} else {
  print "The apache module mod_rewrite is NOT enabled.<br/>\n";
}

/**
 * Verifies if the mod_rewrite module is enabled
 *
 * @return boolean True if the module is enabled.
 */
/*function is_mod_rewrite_enabled() {
  if ($_SERVER['HTTP_MOD_REWRITE'] == 'On') {
    return TRUE;
  } else {
    return FALSE;
  }
}*/
