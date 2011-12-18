<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <?php
      $title = "Test de reciprocidad inverso";
      $descr = "Descubre, de entre tus followers, a quien no sigues";
      include("../includes/head.inc");
      ?>
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
                'status' => "Usando las Twit-Herramientas \"Test de reciprocidad inverso\": Descubre, de entre tus followers, a quien no sigues. " . KCY,
            ));
         }

         // Conseguir Followers
	if (isset($_SESSION["followers"]["ids"]) && is_array($_SESSION["followers"]["ids"]) && count($_SESSION["followers"]["ids"]) != 0) {
		$followers = $_SESSION["followers"];
	} else {
		$tmhOAuth->request('GET', $tmhOAuth->url('1/followers/ids'), array(
			'id' => $_SESSION["access_token"]["user_id"]
		));
		$followers = array('ids' => json_decode($tmhOAuth->response['response']), 'num' => count(json_decode($tmhOAuth->response['response'])));		
		if (!is_array($followers['ids'])) {
			$followers['ids'] = array();
		}
		$_SESSION["followers"] = $followers;
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

//      if ($followers['num'] == 5000 || $friends['num'] == 5000) {
//        echo("<p align=\"center\">Atención: Tienes más de 5000 followers o sigues a más de 5000 personas.<br>
//            La operación se realizará con los 5000 más recientes.</p>");
//      }
         //Comparar
         $traidores = array();
         foreach ($followers['ids'] as $follower) {
            if (!in_array($follower, $friends['ids'])) {
               $traidores[] = $follower;
            }
         }
         If (count($traidores) != 0) {

            $tmhOAuth->request('GET', $tmhOAuth->url('1/users/lookup'), array(
                'user_id' => implode(",", array_slice($traidores, 0, 100)),
            ));
            $traidores_data = json_decode($tmhOAuth->response['response']);
            if (!is_array($traidores_data)) {
               $traidores_data = array();
            }
//        if (count($traidores) > 100) {
//          echo("<p align=\"center\">Eres ególatra hacia a más de 100 personas que te siguen.<br />
//                              Sólo se mostrarán los 100 más recientes</p>");
//        }
         }

         //
         ?>
         <form name="follow" action="?action=follow" method="POST">
            <table cellspacing="15px" cellpadding="0" style="border: 1px solid #8ec1da; background-color: #c0deed" align="center">
               <tbody>
                  <tr><th colspan="2" style="text-align: center"><?= count($traidores) ?> usuarios te siguen pero tú a ellos no:</th></tr>
                  <tr>
                     <td>&nbsp;</td>
                     <th>Nombre (@usuario)</th>
                     <th>Seguir</th>
                  </tr>
                  <?
                  foreach ($traidores_data as $traidor_data) {
                     ?>
                     <tr>
                        <td>
                           <a title="<?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $traidor_data->description) ?>" hreflang="en" target="_blank" href="http://twitter.com/<?= $traidor_data->screen_name ?>"><img border="0" width="48" height="48" style="vertical-align: middle;" src="<?= $traidor_data->profile_image_url ?>" alt="Imagen"></a>
                        </td>
                        <td>
                           <address title="<?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $traidor_data->description) ?>">
                              <span><?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $traidor_data->name) ?> (<a hreflang="en" target="_blank" href="http://twitter.com/<?= $traidor_data->screen_name ?>">@<?= $traidor_data->screen_name ?></a>) <span style="color:gray">- <?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $traidor_data->location) ?></span></span>

                           </address>
                           <span>
                              <span style="font-size:smaller; color:#666666">
                                 <?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $traidor_data->status->text) ?>&nbsp; <br /><?= $traidor_data->status->created_at ?>
                              </span>

                           </span>
                        </td>
                        <td style="text-align: center">
                           <input type="checkbox" name="<?= $traidor_data->id ?>">
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

         // Conseguir Followers
	if (isset($_SESSION["followers"]["ids"]) && is_array($_SESSION["followers"]["ids"]) && count($_SESSION["followers"]["ids"]) != 0) {
		$followers = $_SESSION["followers"];
	} else {
		$tmhOAuth->request('GET', $tmhOAuth->url('1/followers/ids'), array(
			'id' => $_SESSION["access_token"]["user_id"]
		));
		$followers = array('ids' => json_decode($tmhOAuth->response['response']), 'num' => count(json_decode($tmhOAuth->response['response'])));		
		if (!is_array($followers['ids'])) {
			$followers['ids'] = array();
		}
		$_SESSION["followers"] = $followers;
	}

            //Unfollowear
            $followear = array();
            foreach ($followers['ids'] as $follower) {
               if ($_POST[$follower] == "on") {
                  $followear[] = $follower;
               }
            }

            $tmhOAuth->request('GET', $tmhOAuth->url('1/users/lookup'), array(
                'user_id' => implode(",", $followear),
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
            <table align="center" cellspacing="50px"><tbody>
                  <tr>
                     <td><img src="http://img.twit-herramientas.com/invreciprocidad.jpg" alt="Reciprocidad"></td>
                     <td>
                        <p>El "Test de reciprocidad inverso" es la herramienta contraria al test de reciprocidad: Busca que usuarios te siguen, pero tú a ellos no.<br />
                           De esta manera puedes averiguar facilmente que usuarios te siguieron pero no les devolviste el follow, y reconsiderar seguirles.<br />
                        </p>
                     </td>
                  </tr>
                  <tr><th colspan="2"><button onclick="location.href='?action=start'"><? $mensajes = array("¡Empezar!", "¡Dale Caña!", "¡Enséñamelos!", "Ok pipol, press estart");
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
