<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <?php
      $title = "Seguir mediante búsqueda";
      $descr = "Sigue a usuarios que coinciden con un criterio de búsqueda";
      include("../includes/head.inc")
      ?>
   </head>
   <body>
      <?php
      include("../includes/header.inc");
      if ($_GET['action'] == "start") {

         if ($_POST['criterio'] == "") {
            header("Location: ./");
         }

         session_start();

         if (!isset($_SESSION['access_token'])) {
            header("Location: /");
         }

         require ("../includes/config.php");
         require ("../includes/tmhOAuth.php");

         $tmhOAuth = new tmhOAuth(array(
                     'consumer_key' => ConsumerKey,
                     'consumer_secret' => ConsumerSecret
                 ));
         $tmhOAuth->config['user_token'] = $_SESSION['access_token']['oauth_token'];
         $tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];

         //Twittear
         if ($_COOKIE['twitear'] != "no") {
            $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
                'status' => "Usando las Twit-Herramientas \"Seguir mediante busqueda\": Sigue a usuarios que coincidan con un criterio de busqueda. " . KCY,
            ));
         }

         $busqueda = json_decode(file_get_contents("http://search.twitter.com/search.json?q={$_POST['criterio']}&rpp=100"));
         $resultados = $busqueda->results;
         if (!is_array($resultados)) {
            $resultados = array();
         }

         //die(print_r($busqueda));
         ?>
         <form name="follow" action="?action=follow" method="POST">
            <table cellspacing="15px" cellpadding="0" style="border: 1px solid #8ec1da; background-color: #c0deed" align="center">
               <tbody>
                  <tr><th colspan="2" style="text-align: center">Usuarios que coinciden con el criterio "<?= $_POST['criterio'] ?>":</th></tr>
                  <tr>
                     <td>&nbsp;</td>
                     <th>Usuario (@usuario)</th>
                     <th>Seguir</th>
                  </tr>
                  <?
                  foreach ($busqueda->results as $resultado) {
                     ?>
                     <tr>
                        <td>
                           <a hreflang="en" target="_blank" href="http://twitter.com/<?= $resultado->from_user ?>"><img border="0" width="48" height="48" style="vertical-align: middle;" src="<?= $resultado->profile_image_url ?>" alt=""></a>
                        </td>
                        <td>
                           <address>
                              <span><span style="text-transform:capitalize"><?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $resultado->from_user) ?></span> (<a hreflang="en" target="_blank" href="http://twitter.com/<?= $resultado->from_user ?>">@<?= $resultado->from_user ?></a>)</span>

                           </address>
                           <span>
                              <span style="font-size:smaller; color:#666666">
      <?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $resultado->text) ?>&nbsp; <br /><em><?= $resultado->created_at ?></em>
                              </span>

                           </span>
                        </td>
                        <td style="text-align: center">
                           <input type="checkbox" name="<?= $resultado->from_user ?>">
                        </td>
                     </tr>
                     <?
                  }
                  ?>
                  <tr>
                     <th colspan="3" style="text-align: center">
                        <input type="submit" value="Seguir a los usuarios seleccionados">
                     </th>
                  </tr>
               </tbody></table>
         </form>
         <?
      } elseif ($_GET['action'] == "follow") {

         session_start();

         if (!isset($_SESSION['access_token'])) {
            header("Location: /");
         }

         require ("../includes/config.php");
         require ("../includes/tmhOAuth.php");

         $tmhOAuth = new tmhOAuth(array(
                     'consumer_key' => ConsumerKey,
                     'consumer_secret' => ConsumerSecret
                 ));
         $tmhOAuth->config['user_token'] = $_SESSION['access_token']['oauth_token'];
         $tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];

         $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
         $credenciales = json_decode($tmhOAuth->response['response']);

         //Followear
         foreach ($_POST as $value => $follower) {
            $followeartot[] = $value;
         }
         $followear = array_slice($followeartot, 0, 100);


         $tmhOAuth->request('GET', $tmhOAuth->url('1/users/lookup'), array(
             'screen_name' => implode(",", $followear),
         ));
         $followear_data = json_decode($tmhOAuth->response['response']);
         if (!is_array($followear_data)) {
            $followear_data = array();
         }
         ?>
         <table cellspacing="15px" cellpadding="0" style="border: 1px solid #8ec1da; background-color: #c0deed; width: auto" align="center">
            <tbody>
               <tr><td>

                     <?
                     foreach ($followear_data as $followed) {
                        $tmhOAuth->request('POST', $tmhOAuth->url('1/friendships/create'), array(
                            'id' => $followed->id,
                        ));

                        echo("Ahora sigues a <b>" . iconv("UTF-8", "ISO-8859-1//TRANSLIT", $followed->name) . "</b> (@<a href=\"http://twitter.com/{$followed->screen_name}\">{$followed->screen_name}</a>)<br />");
                     }
                     echo("</td></tr><tr><td style=\"text-align: center\"><button onclick=\"location.href='?action='\">Volver</button>");
                     ?>
                  </td></tr></tbody></table>
         <?
      } else {
         ?>
         <hr>
         <p align="center">
   <? include("../includes/ads.inc"); ?>
         </p>
         <hr>
         <form action="?action=start" method="POST">
            <table align="center" cellspacing="50px"><tbody>
                  <tr>
                     <td><img src="http://img.twit-herramientas.com/lupa.jpg" alt="Busqueda"></td>
                     <td>
                        <p>Esta herramienta te permite hacer una búsqueda global en Twitter. <br>
                           Podrás ver que usuario ha hecho cada Tweet que coincide con tu criterio de búsqueda, y seguirle si lo crees conveniente.<br>
                           Resulta muy útil para seguir muchos usuarios de golpe.
                        </p>
                     </td>
                  </tr>
                  <tr><th colspan="2">Criterio de búsqueda <input type="text" name="criterio" value=""></th></tr>
                  <tr><th colspan="2"><input type="submit" value="<? $mensajes = array("¡Empezar!", "¡Dale Caña!", "¡A buscar!", "Ok pipol, press estart");
   echo($mensajes[rand(0, count($mensajes) - 1)]); ?>"></th></tr>
               </tbody></table>
            <hr>
         </form>
         <?
      }
      ?>
<? include("../includes/footer.inc"); ?>
   </body>
</html>