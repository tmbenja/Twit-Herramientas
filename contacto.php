<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<?
if (!isset($_POST['descripcion'])) {
?>
   <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
   <html>
      <head>
         <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
         <title>Sugiérenos una Twit-Herramienta</title>
      </head>
      <body>
         <form action="" method="POST">
            <p><b>Sugiérenos una Twit-Herramienta</b></p>
            <p>¡Agradecemos tus ideas! Si tienes alguna en mente que pueda convertirse en una Twit-Herramienta,
               cuéntanoslo y quizá podamos llevar tu idea a la práctica:</p>
            Tu nombre en twitter: @<input type="text" name="nombre" size="17"><br /><br />
            Descripción de la Twit-Herramienta: <br />
            <textarea cols="49" rows="10" name="descripcion"></textarea><br />
            <input type="submit" value="Enviar sugerencia">
         </form>
      </body>
   </html>
<?
} else {
   if ($_POST['descripcion'] == "") {
      header("Location: contacto.php");
   }
   $mensaje = "Twit-Herramienta sugerida por {$_POST['nombre']}: \n\n{$_POST['descripcion']}";
   mail("roobre@roobre.net", "¡{$_POST['nombre']} ha sugerido una Twit-Herramienta!", $mensaje, "From: Twit-Herramientas <roobre@roobre.net>");
   echo("Mensaje enviado");
}
?>
