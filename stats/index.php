<?php
require("../includes/db.php");
$link = mysql_connect(host, user, passdb);
mysql_select_db(database, $link);

//Usos totales
$res = mysql_query("SELECT * FROM `Users`");
while($row = mysql_fetch_array($res)) {
  $usos += $row['Usos'];
}

//Top Users
$res = mysql_query("SELECT * FROM `Users` ORDER BY `Users`.`Usos` DESC LIMIT 0 , 5");
while($row = mysql_fetch_array($res)) {
  $top[] = "[{$row['Usos']}] " . iconv("UTF-8", "ISO-8859-1//TRANSLIT", $row['Nombre']) . " (<a href=\"http://twitter.com/{$row['screen_name']}\">@{$row['screen_name']}</a>)";
}

//+1 Users
$res = mysql_query("SELECT * FROM `Users` WHERE `Users`.`Usos` <> 1");
while($row = mysql_fetch_array($res)) {
  $plus1++;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <link type="text/css" rel="stylesheet" media="screen" href="../todo.css">
    <link type="image/x-icon" rel="shortcut icon" href="../favicon.ico">
    <title>Estadísticas | Twit-Herramientas</title>
  </head>
  <body>
    <? include("../includes/header.inc"); ?>
    <table align="center" cellspacing="10px">
      <tr>
        <td colspan="3">
          <table class="app" align="center"><tbody>
              <tr>
                <th>Top usuarios</th>
              </tr>
              <tr>
                <td><ul>
                    <? foreach($top as $topper) {
                      echo ("<li>$topper</li>");
                    }?>
                  </ul></td>
              </tr>
            </tbody></table>
        </td>
      </tr>
      <tr><td align="center">
          <table class="app"><tbody>
              <tr>
                <th>Usos totales</th>
              </tr>
              <tr>
                <td><ul><li><?=$usos?></li></ul></td>
              </tr>
            </tbody></table>
        </td>
        <td>
          <table class="app" align="center"><tbody>
              <tr>
                <th>Usuarios con +1 usos</th>
              </tr>
              <tr>
                <td><ul><li><?=$plus1?> / <?=$usos?> (<?=floor(($plus1/$usos)*100)?>%)</li></ul></td>
              </tr>
            </tbody></table>
        </td>
        <td>
          <table class="app" align="center"><tbody>
              <tr>
                <th>Usuarios con sólo un uso</th>
              </tr>
              <tr>
                <td><ul><li><?=$usos - $plus1?> / <?=$usos?> (<?=floor((($usos - $plus1)/$usos)*100)?>%)</li></ul></td>
              </tr>
            </tbody></table>
        </td>
      </tr>
    </table>
    <? include("../includes/footer.inc"); ?>
  </body>
</html>
