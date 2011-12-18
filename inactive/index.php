<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <?php
      $title = "Test de Inactividad";
      $descr = "Averigua a qué usuarios inactivos sigues";
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
                'status' => "Usando las Twit-Herramientas \"Test de actividad\": Averigua cuantos usuarios de los que sigues son inactivos. " . KCY,
            ));
         }

         //Conseguir Firends
	if (isset($_SESSION["friends"]["ids"]) && is_array($_SESSION["friends"]["ids"]) && count($_SESSION["friends"]["ids"]) != 0) {
		$friends = $_SESSION["friends"];
	} else {
		$tmhOAuth->request('GET', $tmhOAuth->url('1/friends/ids'), array(
			'id' => $_SESSION["access_token"]["user_id"]
		));
		$friends = array('ids' => json_decode($tmhOAuth->response['response']), 'num' => count(json_decode($tmhOAuth->response['response'])));
		if (!is_array($friends['ids'])) {
			$friends['ids'] = array();
		}
		$_SESSION["friends"] = $friends;
	}
         //$numfriends = count($friends);

         $cota = $_POST['cota'];
         $hoy = time();

         $i = 0;
         while ($i < $friends["num"]) {
            $tmhOAuth->request('GET', $tmhOAuth->url('1/users/lookup'), array(
                'user_id' => implode(",", array_slice($friends["ids"], $i, 100))
            ));
            $respuesta = json_decode($tmhOAuth->response['response']);
            if (!is_array($respuesta)) {
               $respuesta = array();
            }

            $friends_data = array();
            foreach ($respuesta as $friend_data) {
               if ($hoy - strtotime($friend_data->status->created_at) > $cota) {
                  $friends_data[] = $friend_data;
               }
            }

            $i = $i + 100;
         }
         unset($friend_data);
         ?>
         <form name="unfollow" action="?action=unfollow" method="POST">
            <table cellspacing="15px" cellpadding="0" style="border: 1px solid #8ec1da; background-color: #c0deed" align="center">
               <tbody>
                  <tr><th colspan="2" style="text-align: center"><?= count($friends_data) ?> usuarios coinciden con tu criterio de búsqueda:</th></tr>
                  <tr>
                     <td>&nbsp;</td>
                     <th>Nombre (@usuario)</th>
                     <th>Dejar de seguir</th>
                  </tr>
                  <?
                  foreach ($friends_data as $friend_data) {
                     ?>
                     <tr>
                        <td>
                           <a title="<?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $friend_data->description) ?>" hreflang="en" target="_blank" href="http://twitter.com/<?= $friend_data->screen_name ?>"><img border="0" width="48" height="48" style="vertical-align: middle;" src="<?= $friend_data->profile_image_url ?>" alt="Imagen"></a>
                        </td>
                        <td>
                           <address title="<?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $friend_data->description) ?>">
                              <span><?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $friend_data->name) ?> (<a hreflang="en" target="_blank" href="http://twitter.com/<?= $friend_data->screen_name ?>">@<?= $friend_data->screen_name ?></a>) <span style="color:gray">- <?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $friend_data->location) ?></span></span>

                           </address>
                           <span>
                              <span style="font-size:smaller; color:#666666">
                                 <?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $friend_data->status->text) ?>&nbsp; <br /><?= $friend_data->status->created_at ?>
                              </span>

                           </span>
                        </td>
                        <td style="text-align: center">
                           <input type="checkbox" name="<?= $friend_data->id ?>">
                        </td>
                     </tr>
                     <?
                  }
                  ?>
                  <tr>
                     <th colspan="3" style="text-align: center">
                        <input type="submit" value="Dejar de seguir a los usuarios seleccionados">
                     </th>
                  </tr>
               </tbody></table>
         </form>
         <?
      } elseif ($_GET['action'] == "unfollow") {

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

         //Conseguir Firends
	if (isset($_SESSION["friends"]["ids"]) && is_array($_SESSION["friends"]["ids"]) && count($_SESSION["friends"]["ids"]) != 0) {
		$friends = $_SESSION["friends"];
	} else {
		$tmhOAuth->request('GET', $tmhOAuth->url('1/friends/ids'), array(
			'id' => $_SESSION["access_token"]["user_id"]
		));
		$friends = array('ids' => json_decode($tmhOAuth->response['response']), 'num' => count(json_decode($tmhOAuth->response['response'])));
		if (!is_array($friends['ids'])) {
			$friends['ids'] = array();
		}
		$_SESSION["friends"] = $friends;
	}

         //Unfollowear
         $unfollowear = array();
         foreach ($friends['ids'] as $hamijo) {
            if ($_POST[$hamijo] == "on") {
               $unfollowear[] = $hamijo;
            }
         }

         $tmhOAuth->request('GET', $tmhOAuth->url('1/users/lookup'), array(
             'user_id' => implode(",", $unfollowear),
         ));
         $unfollowear_data = json_decode($tmhOAuth->response['response']);
         if (!is_array($unfollowear_data)) {
            $unfollowear_data = array();
         }
         ?>
         <table cellspacing="15px" cellpadding="0" style="border: 1px solid #8ec1da; background-color: #c0deed; width: auto" align="center">
            <tbody>
               <tr><td>

                     <?
                     foreach ($unfollowear_data as $unfollowed) {
                        $tmhOAuth->request('POST', $tmhOAuth->url('1/friendships/destroy'), array(
                            'id' => $unfollowed->id,
                        ));

                        echo("Has dejado de seguir a <b>" . iconv("UTF-8", "ISO-8859-1//TRANSLIT", $unfollowed->name) . "</b> (@<a href=\"http://twitter.com/{$unfollowed->screen_name}\">{$unfollowed->screen_name}</a>)<br />");
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
                     <td><img src="http://img.twit-herramientas.com/graph.jpg" alt="Actividad"></td>
                     <td>
                        <p>El "Test de Inctividad" sirve para mostrar los usuarios que sigues cuyo último Tweet tenga una antiguedad mayor que la especificada.<br />
                           Puedes dejar de seguir a los usuarios que han abandonado su cuenta o no la actualizan con suficiente frecuencia.
                        </p>
                        <p>

                        </p>
                     </td>
                  </tr>
                  <tr><th colspan="2">Antigüedad:
                        <select name="cota">
                           <option value="86400">1 Día</option>
                           <option value="172800">2 Días</option>
                           <option value="259200">3 Días</option>
                           <option value="604800">1 Semana</option>
                           <option value="1209600">2 Semanas</option>
                           <option value="2419200">1 Mes</option>
                           <option value="14515200">6 Meses</option>
                           <option value="29030400">1 Año</option>
                        </select></th></tr>
                  <tr><th colspan="2"><input type="submit" value="<? $mensajes = array("¡Empezar!", "¡Dale Caña!", "¡Enséñame esos inactivos!", "Ok pipol, press estart");
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
