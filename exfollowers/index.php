<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <?php
      $title = "Buscador de Ex-Followers";
      $descr = "Encuentra a la gente que te ha dejado de seguir";
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
         require ("../includes/db.php");

         $tmhOAuth = new tmhOAuth(array(
                     'consumer_key' => ConsumerKey,
                     'consumer_secret' => ConsumerSecret
                 ));
         $tmhOAuth->config['user_token'] = $_SESSION['access_token']['oauth_token'];
         $tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];

         //Twittear
         if ($_COOKIE['twitear'] != "no") {
            $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
                'status' => "Usando las Twit-Herramientas \"Buscador de Ex-Followers\": Encuentra a la gente que te ha dejado de seguir. " . KCY,
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

         $link = mysql_connect(host, user, passdb);
         mysql_select_db(database, $link);

         $usuario = mysql_fetch_array(mysql_query("SELECT * FROM `Exfollowers` WHERE `ID` = {$_SESSION["access_token"]["user_id"]}"));

         if (!isset($usuario["ID"])) {
            mysql_query("INSERT INTO `Exfollowers` (`ID`, `Followers`, `Usos`) VALUES ({$_SESSION["access_token"]["user_id"]}, '" . implode(";", $followers["ids"]) . "', 1)");
            header("Location: ?action=start");
         } else {
            foreach (explode(";", $usuario["Followers"]) as $viejofollower) {
               if (!in_array($viejofollower, $followers["ids"])) {
                  $exfollowers[] = $viejofollower;
               }
            }
            if (count($exfollowers) != 0) {
               $tmhOAuth->request('GET', $tmhOAuth->url('1/users/lookup'), array(
                   'user_id' => implode(",", $exfollowers),
               ));
               ?>
               <table cellspacing="15px" cellpadding="0" style="border: 1px solid #8ec1da; background-color: #c0deed; width: auto" align="center">
                  <tbody>
                     <tr><th colspan="2" style="text-align: center"><?= count($exfollowers) ?> persona(s) te han dejado de seguir:</th></tr>
                     <tr>
                        <th colspan="2">Nombre (@usuario)</th>
                     </tr>
                     <tr><td colspan="2"><hr style="width: 250px"></td></tr>
                     <?
                     $exfollowers_data = json_decode($tmhOAuth->response['response']);
                     if (!is_array($exfollowers_data)) {
                        $exfollowers_data = array();
                     }
                     foreach ($exfollowers_data as $exfollower) {
                        ?>
                        <tr>
                           <td>
                              <a title="<?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $exfollower->description) ?>" hreflang="en" target="_blank" href="http://twitter.com/<?= $exfollower->screen_name ?>"><img border="0" width="48" height="48" style="vertical-align: middle;" src="<?= $exfollower->profile_image_url ?>" alt="Imagen"></a>
                           </td>
                           <td>
                              <address title="<?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $exfollower->description) ?>">
                                 <span><?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $exfollower->name) ?> (<a hreflang="en" target="_blank" href="http://twitter.com/<?= $exfollower->screen_name ?>">@<?= $exfollower->screen_name ?></a>) <span style="color:gray">- <?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $exfollower->location) ?></span></span>

                              </address>
                              <span>
                                 <span style="font-size:smaller; color:#666666">
                                    <?= iconv("UTF-8", "ISO-8859-1//TRANSLIT", $exfollower->status->text) ?>&nbsp; <br /><?= $exfollower->status->created_at ?>
                                 </span>

                              </span>
                           </td>
                        </tr>
                        <?
                     }
                     ?>
                  </tbody></table>
               <?
            } else {
               echo("<p align=\"center\">Tienes los mismos followers que la última vez que lo consultaste.</p>");
            }
            $usos = $usuario["Usos"] + 1;
            mysql_query("UPDATE `Exfollowers` SET `Followers` = '" . implode(";", $followers["ids"]) . "', `Usos` = '" . $usos . "' WHERE `Exfollowers`.`ID` = '{$_SESSION["access_token"]["user_id"]}'");
         }
         ?><p align="center"><button onclick="location.href='?action='">Volver</button></p><?
      } else {
         ?>
         <hr>
         <p align="center">
            <? include("../includes/ads.inc"); ?>
         </p>
         <hr>
         <table align="center"><tbody><tr>
                  <td>
                     <p>El "Buscador de Ex-Followers" es una herramienta que te mostrará que usuarios han dejado de seguirte.</p>
                     <p>Funcionamiento:<br />
                        La primera vez que uses el "Buscador de Ex-Followers", registrará los followers que tienes en este momento.<br />
                        A partir de entonces, cada vez que visites la herramienta de nuevo te dirá quienes te han dejado de seguir, tomando como referencia la última vez que lo visitaste.</p>
                  </td>
               </tr>
               <tr><th colspan="2"><button onclick="location.href='?action=start'"><? $mensajes = array("¡Empezar!", "¡Dale Caña!", "¡Dime quién me ha dejado de seguir!", "¡Enséñamelos!", "Ok pipol, press estart");
         echo($mensajes[rand(0, count($mensajes) - 1)]); ?></button></th></tr>
            </tbody></table>
         <hr>
      <? } ?>

      <? include("../includes/footer.inc"); ?>

   </body>
</html>
