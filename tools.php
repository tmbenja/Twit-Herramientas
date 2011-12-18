<?php
//Aviso de caridad
if (stripos($_SERVER['HTTP_USER_AGENT'], "googlebot") == false && $_COOKIE['aviso'] != 1) {
	?>
	<script type="text/javascript">
		alert("ATENCIÓN: Este mensaje aparecerá sólo una vez, por favor, léalo con atención.\n\n\
	Twit-Herramientas es una página web que ofrece servicios de forma GRATUITA.\n\
	Por ello, para poder mantenerla, mostramos anuncios en la parte superior. Estos anuncios no son intrusivos, así que si utiliza software de bloqueo de anuncios como \"AdBlock Plus\" o similares, preferimos que lo desactive al navegar por nuestra página.\n\n\
	Además, si alguno de los anuncios que se muestran le parece interesante, le recordamos que hacer click en ellos no interrumpirá la navegación, además de que nos ayuda a seguir con la página de forma gratuita y en mejora constante.\n\n\
	Muchas gracias por su atención.\n\
	Atte: El Staff de Twit-Herramientas.");
	</script>
	<?
};
setcookie("aviso", 1, time() + 60 * 60 * 24 * 30 * 6);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
		<? $descr = "Conjunto de herramientas para utilizar con twitter";
		include("includes/head.inc") ?>
   </head>
   <body>
      <h1 style="display: none">Utilidades Twitter</h1>
      <h2 style="display: none">Herramientas para Twitter</h2>
<!--      <p align="center"><a target="_blank" href="/"><img src="http://img.twit-herramientas.com/logo.png" alt="Logo"></a></p>
      <a target="_blank" href="/"><img style="position:fixed;bottom:0%; right:2%" src="http://img.twit-herramientas.com/icon.png" alt="Icono"></a>-->
		<? include("includes/header.inc"); //echo('<div style="margin-top: 100px">') ?>
      <hr>
      <p align="center">
			<? include("includes/ads.inc"); ?>
      </p>
      <hr>
      <table align="center"><tbody>
            <tr>
               <th colspan="2" style="text-align: center">
                  <img src="http://img.twit-herramientas.com/herramientas.png" alt="Herramientas">
               </th>
            </tr>
            <tr>
               <td>
                  <table class="app" cellspacing="15px">
                     <tbody>
                        <tr><th><a href="reciprocaltest/">Test de Reciprocidad</a></th></tr>
                        <tr><td>Comprueba los usuarios que sigues pero no te siguen a ti.<br>
                              ¡Muy útil para limpiar tus followers de ególatras!</td></tr>
                     </tbody>
                  </table>
               </td>
               <td>
                  <table class="app" cellspacing="25px">
                     <tbody>
                        <tr><th><a href="exfollowers/">Buscador de Ex-Followers</a></th></tr>
                        <tr><td>Encuentra a la gente que te ha dejado de seguir.<br>
                              ¡Tienes derecho a saberlo!</td></tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr>
               <td>
                  <table class="app" cellspacing="15px">
                     <tbody>
                        <tr><th><a href="invreciprocaltest/">Test de Reciprocidad inverso</a></th></tr>
                        <tr><td>Comprueba quienes te siguen pero tú a ellos no.<br>
                              Quizá merezca la pena seguirlos, ¡no seas ególatra!</td></tr>
                     </tbody>
                  </table>
               </td>
               <td>
                  <table class="app" cellspacing="25px">
                     <tbody>
                        <tr><th><a href="inactive/">Test de Inactividad</a></th></tr>
                        <tr><td>Averigua que usuarios, de entre los que sigues,
                              Twittean muy poco.</td></tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr>
               <td>
                  <table class="app" cellspacing="25px">
                     <tbody>
                        <tr><th><a href="viewblock/">ViewBlock</a></th></tr>
                        <tr><td>Muestra a los usuarios que has bloqueado, y permite<br>
                              desbloquearlos si lo crees conveniente.</td></tr>
                     </tbody>
                  </table>
               </td>
               <td>
                  <table class="app" cellspacing="25px">
                     <tbody>
                        <tr><th><a href="followbysearch/">Seguir mediante búsqueda</a></th></tr>
                        <tr><td>Encuentra y sigue a numerosos usuarios
                              según un criterio de búsqueda.</td></tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <a href="?wipe"><img src="http://img.twit-herramientas.com/close.png" alt="Cerrar sesión" style="position: fixed; top: 2%; right: 0px;"></a>
      <hr>
      <table align="center">
         <tbody>
            <tr><th style="text-align: center; font-size: large"><a target="_blank" href="http://twitter.com/home?status=@tw_herramientas - Sugerencia:  "><img src="http://img.twit-herramientas.com/sugerencias.png" alt="Contacto"></a></th></tr>
            <tr><th style="color: red">Por favor, antes de enviar una sugerencia, lea la sección de<br /><a target="_blank" href="faq.html">Preguntas Frecuentes</a></th></tr>
            <tr><td style="text-align: center">Haz click <a target="_blank" href="http://twitter.com/home?status=@tw_herramientas - Sugerencia:  " style="color:red">aquí</a> para enviarnos tu sugerencia. ¡Gracias!</td></tr>
         </tbody>
      </table>
      <hr>
      <table align="center">
			<tbody>
            <tr><th colspan="2" style="text-align: center"><img src="http://img.twit-herramientas.com/patrocinadores.png" alt="Patrocinadores"></th></tr>
            <tr>
               <td style="width: 325px; text-align: center; vertical-align: middle;">
                  <span style="display: none;"><a href="http://www.ikkaro.com">Inventos y experimentos caseros</a></span>
                  <p style="vertical-align: top;"><a target="_blank" title="Inventos y experimentos caseros" href="http://www.ikkaro.com"><img alt="Inventos y experimentos caseros" src="http://img.twit-herramientas.com/ikkaro.jpg"></a></p></td>
               <td style="width: 325px; text-align: center; vertical-align: middle;">
                  <span style="display: none;"><a href="http://twittboy.com.com">Todo Twitter en una web</a></span><p>
                     <a target="_blank" title="Todo Twitter en una web" href="http://twittboy.com"><img alt="Twittboy: Todo Twitter en una web" src="http://img.twit-herramientas.com/twittboy.jpg"></a></p>
               </td>
            </tr>
         </tbody>
		</table>
      <hr>
		<? include("includes/footer.inc") ?>
   </body>
</html>
