<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Description" content="<?= $descr ?>">
<meta name="Keywords" content="twitter, herramientas, twit, followers, tools">
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: 'es'}
</script>
<?
if (in_array($_SERVER['PHP_SELF'], array("/index.php", "/tools.php", "/about.php"))) {
?>
  <link type="text/css" rel="stylesheet" media="screen" href="todo.css">
  <link type="image/x-icon" rel="shortcut icon" href="favicon.ico">
  <title>Twit-Herramientas | Saca un mayor provecho a Twitter</title>
<?
} else {
?>
  <link type="text/css" rel="stylesheet" media="screen" href="../todo.css">
  <link type="image/x-icon" rel="shortcut icon" href="../favicon.ico">
  <title><?= $title ?> | Twit-Herramientas</title>
<?
}
?>