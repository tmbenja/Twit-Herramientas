<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <?php
      $title = "ViewBlocked";
      $descr = "Comprueba a que usuarios estás bloqueando";
      include("../includes/head.inc")
      ?>
   </head>
   <body>
      <?php 
      include("../includes/header.inc");
      if ($_GET['action'] == "start") {

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
                'status' => "Usando las Twit-Herramientas \"ViewBlock\": Muestra los usuarios que bloqueas. " . KCY,
            ));
         }

         // Conseguir Followers
         $tmhOAuth->request('GET', $tmhOAuth->url('1/blocks/blocking'), array());
         $blocking = array('ids' => json_decode($tmhOAuth->response['response']), 'num' => count(json_decode($tmhOAuth->response['response'])));
         if (!is_array($blocking['ids'])) {
            $blocking['ids'] = array();
         }
         ?>
         <form name="unfollow" action="?action=unblock" method="POST">
            <table cellspacing="15px" cellpadding="0" style="border: 1px solid #8ec1da; background-color: #c0deed" align="center">
               <tbody>
                  <tr><th colspan="2" style="text-align: center">Estás bloqueando a <?= $blocking['num'] ?> personas:</th></tr>
                  <tr>
                     <td>&nbsp;</td>
                     <th>Nombre (@usuario)</th>
                     <th>Desbloquear</th>
                  </tr>
                  <?
                  foreach ($blocking['ids'] as $blocked) {
                     ?>
                     <tr>
                        <td>
                           <a hreflang="en" target="_blank" href="http://twitter.com/<?= $blocked->screen_name ?>"><img border="0" width="48" height="48" style="vertical-align: middle;" src="<?= $blocked->profile_image_url ?>" alt=""></a>
                        </td>
                        <td>
                           <address>
                              <span><?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $blocked->name) ?> (<a title="<?= $blocked->screen_name ?>" hreflang="en" target="_blank" href="http://twitter.com/<?= $blocked->screen_name ?>">@<?= $blocked->screen_name ?></a>)</span>

                           </address>
                           <span>
                              <span style="font-size:smaller; color:#666666">
      <?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $blocked->status->text) ?>&nbsp; <br /><em><?= $blocked->status->created_at ?></em>
                              </span>

                           </span>
                        </td>
                        <td style="text-align: center">
                           <input type="checkbox" name="<?= $blocked->id ?>">
                        </td>
                     </tr>
                     <?
                  }
                  ?>
                  <tr>
                     <th colspan="3" style="text-align: center">
                        <input type="submit" value="Desbloquear a los usuarios seleccionados">
                     </th>
                  </tr>
               </tbody></table>
            <?
         } elseif ($_GET['action'] == "unblock") {

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

//          $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
//          $credenciales = json_decode($tmhOAuth->response['response']);
            //Conseguir Firends
            $tmhOAuth->request('GET', $tmhOAuth->url('1/blocks/blocking'), array(
                    //'id' => $credenciales->id,
            ));
            $blocking = array('ids' => json_decode($tmhOAuth->response['response']), 'num' => count(json_decode($tmhOAuth->response['response'])));
            if (!is_array($blocking['ids'])) {
               $blocking['ids'] = array();
            }
            //Desbloquear
            $unblock = array();
            foreach ($blocking['ids'] as $hamijo) {
               if ($_POST[$hamijo->id] == "on") {
                  $unblock[] = $hamijo;
               }
            }
            ?>
            <table cellspacing="15px" cellpadding="0" style="border: 1px solid #8ec1da; background-color: #c0deed; width: auto" align="center">
               <tbody>
                  <tr><td>

                        <?
                        foreach ($unblock as $unblocked) {
                           $tmhOAuth->request('POST', $tmhOAuth->url('1/blocks/destroy'), array(
                               'id' => $unblocked->id,
                           ));

                           echo("Has desbloqueado a <b>" . iconv("UTF-8", "ISO-8859-1//TRANSLIT", $unblocked->name) . "</b> (@<a href=\"http://twitter.com/{$unblocked->screen_name}\">{$unblocked->screen_name}</a>)<br />");
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
            <table align="center" cellspacing="50px"><tbody>
                  <tr>
                     <td><img src="http://img.twit-herramientas.com/bloqueado.gif" alt="Bloqueado"></td>
                     <td>
                        <p>La herramienta "ViewBlock" te permite comprobar a los usuarios que estás bloqueando actualmente (y que, por tanto, no pueden seguirte).<br />
                           La aplicación te los mostrará y te dará la opción para desbloquearlos si lo crees conveniente.<br />
                        </p>
                     </td>
                  </tr>
                  <tr><th colspan="2"><button onclick="location.href='?action=start'"><? $mensajes = array("¡Empezar!", "¡Dale Caña!", "Dime a quien estoy bloqueando", "¡Enséñamelos!", "Ok pipol, press estart");
   echo($mensajes[rand(0, count($mensajes) - 1)]); ?></button></th></tr>
               </tbody></table>
            <hr>
         </form>
         <?
      }
      ?>

<? include("../includes/footer.inc"); ?>

   </body>
</html>
