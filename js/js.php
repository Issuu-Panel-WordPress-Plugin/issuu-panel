<?php 

$expires = 60 * 60 * 24 * 7;
header('Content-Type: text/javascript; charset: UTF-8'); 
header("Cache-Control: public, max-age=" . $expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');

echo "var teste = 2;\n";

readfile(dirname(__FILE__) . '/teste.js');