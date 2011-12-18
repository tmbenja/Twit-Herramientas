<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
      <link type="text/css" rel="stylesheet" media="screen" href="todo.css">
      <link type="image/x-icon" rel="shortcut icon" href="favicon.ico">
      <title>Twit-Herramientas | Saca un mayor provecho a Twitter</title>
   </head>
   <body>
		<!-- ¡Hola series.ly! Atte @Roobre -->
      <p style="position:fixed;top:0%; right:2%; text-decoration: blink; font-weight: bold; text-align: center">
         <img src="http://img.twit-herramientas.com/raid.gif" /><br />
         Now Bug Free!
      </p>
      <p style="text-align: center"><a href="/"><img src="http://img.twit-herramientas.com/logo.png" alt="Logo"></a></p>
      <img style="position:fixed;bottom:0%; right:2%" src="http://img.twit-herramientas.com/icon.png" alt="Icono">
		<?php
		switch ($tmhOAuth->response['code']) {
			case 200:
				$_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
				$method = isset($_REQUEST['signin']) ? 'authenticate' : 'authorize';
				$link = $tmhOAuth->url("oauth/{$method}", '') . "?oauth_token={$_SESSION['oauth']['oauth_token']}";
				?>
				<div style="margin-top: 100px">
					<table align="center" cellspacing="50px"><tbody>
							<tr>
								<td>
									<h2>Autenticar con twitter:</h2>
									<p>Twit-Herramientas es un conjunto de herramientas útiles que te ayudarán a sacar mayor provecho a Twitter.</p>
								</td>
								<td>
									<a href ="<?= $link ?>"><img src="http://img.twit-herramientas.com/sign-in.png" alt="Sign in with Twitter"></a><br />
									<p style="font-size: xx-small"><input type="checkbox" onclick="document.cookie='twitear=no'"> No twittear la herramienta que estoy usando</p>
								</td>
							</tr>
						</tbody></table>
					<div align="center">
						<!-- Histats.com  START (html only)-->
						<a href="http://www.histats.com" alt="page hit counter" target="_blank" >
							<embed src="http://s10.histats.com/2047.swf"  flashvars="jver=1&acsid=1296087&domi=4"  quality="high"  width="280" height="25" name="2047.swf"  align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" />
						</a>
						<img src="http://sstatic1.histats.com/0.gif?1296087&101" alt="contador gratis">
						<!-- Histats.com  END  --> 
					</div>
					<hr>
					<div align="center">
						<script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script>
						<script type="text/javascript">
							new TWTR.Widget({
								version: 2,
								type: 'search',
								search: 'twit-herramientas',
								interval: 6000,
								title: '',
								subject: 'Twit-Herramientas',
								width: 728,
								height: 200,
								theme: {
									shell: {
										background: '#8ec1da',
										color: '#ffffff'
									},
									tweets: {
										background: '#ffffff',
										color: '#444444',
										links: '#1985b5'
									}
								},
								features: {
									scrollbar: false,
									loop: true,
									live: true,
									hashtags: true,
									timestamp: true,
									avatars: true,
									toptweets: true,
									behavior: 'default'
								}
							}).render().start();
						</script>
					</div>
					<hr>
				</div>

				<?
				break;
			default:
				?>


				<p>Error de autenticación. Por favor, recarga la página y asegúrate de autorizar la aplicación en Twitter.</p>


			<?
		}
		?>

      <script type="text/javascript">

         var _gaq = _gaq || [];
         _gaq.push(['_setAccount', 'UA-8735000-17']);
         _gaq.push(['_trackPageview']);

         (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
         })();

      </script>

   </body>
</html>
