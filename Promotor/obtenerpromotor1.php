<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

// Extract, usuario y contraseña
$idusuario = ($_GET['idusuario'] !== null && strlen($_GET['idusuario']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idusuario']) : false;
$clave = ($_GET['clave'] !== null && strlen($_GET['clave']) > 0)? mysqli_real_escape_string($con, (string)$_GET['clave']) : false;
$idempresa = ($_GET['idempresa'] !== null && strlen($_GET['idempresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idempresa']) : false;

if(!$idusuario || !$clave)
{
  return http_response_code(400);
}

//$usuario = [];
$sql = "Select idpromotor, idusuario, correo, idestatus as activo 
		from cat_promotor 
		where idusuario ='" .$idusuario ."' 
		and pwd = '" . $clave . "' 
		and idempresa = '" . $idempresa . "' 
		and idestatus = 1;";
		
$accessToken =  bin2hex(openssl_random_pseudo_bytes(64));
$expiresIn  =  24  *  60  *  60;
//echo($sql);
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $pro['id']  = $row['idpromotor'];
    $pro['name']   = $row['idusuario']; 
    $pro['email']      = $row['correo'];
    $pro['accessToken']  = $accessToken;
    $pro['expiresIn']   = $expiresIn;
    $i++;
  }

  if($i==0)
    {
      $pro['id']  = 0;
      $pro['name']   = null;
      $pro['email']      = null;  
      $pro['accessToken']  = null;
      $pro['expiresIn']   = null;
    }
    
    echo json_encode($pro);
}
else
{
  http_response_code(404);
}
?>