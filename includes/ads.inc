<?php

if ($_SERVER['PHP_SELF'] == "/index.php") {
  //Anuncios de texto
  $adsense = 'YOUR ADSENSE CODE 1';

  //ImpresionesWeb peque�ito
  $iw = 'YOUR ALTERNATE ADS CODE 1';
} else {
  //Anuncios gr�ficos
  $adsense = 'YOUR ADSENSE CODE 2';

  //ImpresionesWeb Grande
  $iw = 'YOUR ALTERNATE ADS CODE 2';
}


//$r = rand(0, 99);
//if ($r <= 94) {
  echo($adsense);
//} else {
//  echo($iw);
//}
?>