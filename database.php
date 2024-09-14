<?php
ini_set("upload_max_filesize","1024M");
ini_set("post_max_size","1024M");
ini_set("max_execution_time",3000);
ini_set("max_input_time",3000);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// *****************************
// Ingresar al servidor correspondiente
$servidor = $_SERVER['SERVER_NAME'];

{
    // Producciòn
    define('DB_HOST', 'www.topmas.mx');
    define('DB_USER', 'wwtopm_topmkt');      
    define('DB_PASS', 'JI2TZnY=OVY]');       
    define('DB_NAME', 'wwtopm_topmkt');	
	
}
// *****************************

//echo $servidor . "<BR>";
function connect()
{
  $connect = mysqli_connect(DB_HOST ,DB_USER ,DB_PASS ,DB_NAME);


  if (mysqli_connect_errno()) {
    die("Failed to connect:" . mysqli_connect_error());
  }
  else
  {
	  //echo 'conexion correcta';
  }

  mysqli_set_charset($connect, "utf8");


  return $connect;
}

$con = connect();

?>