<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

//$condicion = "(Select valor from configuracion cfg where cfg.idconf = 2)";
$cat = [];
// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;


$fecha=date('Y/m/d');

/*if($cadena){
$condicion = "and cadena like'{$cadena}%'";
}*/

//$usuario = [];
//if(intval($idEmpresa) > 0){
//	$condicion = $idEmpresa;
//}


$sql = "select idpromotor,concat(nombre,' ',apellidos) as nombrecompleto from cat_promotor where idestatus=1 
and idempresa = " . $idEmpresa . " 
order by nombrecompleto asc;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromotor']      =   $row['idpromotor'];
    $cat[$i]['nombrecompleto']     =   strtoupper($row['nombrecompleto']);
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>