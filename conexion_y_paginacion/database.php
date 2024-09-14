<?php

ini_set("upload_max_filesize", "1024M");
ini_set("post_max_size", "1024M");
ini_set("max_execution_time", 3000);
ini_set("max_input_time", 3000);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require 'EnumX.php';
require 'pagination.php';
require 'mysqlManager.php';

date_default_timezone_set('America/Mexico_City');

$https = $_SERVER['HTTPS'];

$protocolo = 'http';

if (isset($https) && $https === 'on') {
    $protocolo = 'https';
} else {
    $protocolo = 'http';
}


// *****************************
// Ingresar al servidor correspondiente
$servidor = $_SERVER['SERVER_NAME'];

if ($servidor == 'www.jjcorp.com.mx' || $servidor == 'jjcorp.com.mx') {
    $proyecto = 'TopMkt';
    $puerto = (intval($_SERVER['SERVER_PORT']) == 80) ? '' : ':' . $_SERVER['SERVER_PORT'];
} else if ($servidor == 'www.topmas.mx' || $servidor == 'topmas.mx') {
    $proyecto = 'TopMas';
    $puerto = (intval($_SERVER['SERVER_PORT']) == 80) ? '' : ':' . $_SERVER['SERVER_PORT'];
} else {
    $proyecto = 'apiTopMas';
    $puerto = ':4200'; //Pruebas locales desde angular modo desarrollo con proxy
}


$dominio = $protocolo . '://' . $servidor . $puerto; {
    // Producciòn
    define('DB_HOST', 'www.topmas.mx');
    define('DB_PORT', 3306);
    define('DB_USER', 'wwtopm_topmkt');
    define('DB_PASS', 'JI2TZnY=OVY]');
    define('DB_NAME', 'wwtopm_topmkt');
}

// *****************************

class SelectType extends Enum {

    const NONE = "NONE";
    const SELECT = "SELECT";
    const SELECT_WITH_PAGINATION = "SELECT_WITH_PAGINATION";

}
