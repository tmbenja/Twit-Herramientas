<?php

if (stripos($_SERVER['HTTP_USER_AGENT'], "googlebot") !== false) {
   include("tools.php");
   exit();
}

require ("includes/tmhOAuth.php");
require ("includes/tmhUtilities.php");
require ("includes/config.php");

$tmhOAuth = new tmhOAuth(array(
            'consumer_key' => ConsumerKey,
            'consumer_secret' => ConsumerSecret,
        ));

$here = tmhUtilities::php_self();
session_start();

//function outputError($tmhOAuth) {
//   echo 'Error: ' . $tmhOAuth->response['response'] . PHP_EOL;
//   tmhUtilities::pr($tmhOAuth);
//}

// WIPE
if (isset($_REQUEST['wipe'])) {
   session_destroy();
   setcookie("twitear");
   setcookie("db");
   setcookie("idioma");
   header("Location: /");

// Ya logueado
} elseif (isset($_SESSION['access_token'])) {

   if ($_COOKIE["db"] != 1) {
      $tmhOAuth->config['user_token'] = $_SESSION['access_token']['oauth_token'];
      $tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];
//      $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
//      $credenciales = json_decode($tmhOAuth->response['response']);

      require("includes/db.php");
      $link = @mysql_connect(host, user, passdb);
      @mysql_select_db(database, $link);
      $usuario = @mysql_fetch_array(mysql_query("SELECT * FROM `Users` WHERE `ID` = '{$_SESSION["access_token"]["user_id"]}'"));

      if (!isset($usuario["ID"])) {
         mysql_query("INSERT INTO `Users` (`ID`, `screen_name`, `Nombre`, `oauth_token`, `oauth_token_secret` `Usos`) VALUES ('{$_SESSION["access_token"]["user_id"]}', '{$_SESSION["access_token"]["screen_name"]}', '{$credenciales->name}', '{$_SESSION['access_token']['oauth_token']}', '{$_SESSION['access_token']['oauth_token_secret']}', '1')");
      } else {
         $usos = $usuario["Usos"] + 1;
         mysql_query("UPDATE `Users` SET `Usos` = '{$usos}', `oauth_token` = '{$_SESSION['access_token']['oauth_token']}', `oauth_token_secret` = '{$_SESSION['access_token']['oauth_token_secret']}' WHERE `Users`.`ID` = '{$_SESSION["access_token"]["user_id"]}'");
      }

      setcookie("db", 1);
   }

   include("tools.php");

// Estamos siendo Callbackeados
} elseif (isset($_REQUEST['oauth_verifier'])) {
   $tmhOAuth->config['user_token'] = $_SESSION['oauth']['oauth_token'];
   $tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

   $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
       'oauth_verifier' => $_REQUEST['oauth_verifier']
   ));
   $_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
//   unset($_SESSION['oauth']);
   header("Location: {$here}");

// OAuth
} else/* if (isset($_REQUEST['signin']) || isset($_REQUEST['allow'])) */ {
//      $callback = isset($_REQUEST['oob']) ? 'oob' : $here;

   $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', ''), array(
       'oauth_callback' => $here
   ));

   include("auth.inc");
}
?>
