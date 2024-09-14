<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

$vw = "";
$cat = [];

//$usuario = [];
if(intval($idEmpresa) > 0){
	$vw = "_" . $idEmpresa;
}

$sql = "Select * from vw_checkin_sincheckout" . $vw . ";";
//$sql = "Select '' nombre, '' tienda, '' Actividad, '' FechaHora;";

if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['nombre']      =   strtoupper($row['nombre']);
    $cat[$i]['tienda']      =   $row['tienda'];
    $cat[$i]['actividad']   =   $row['Actividad'];
    $cat[$i]['fechahora']   =   $row['FechaHora'];
    $i++;
  }
  
   // Cerrar conexión
  if ($con) {
    mysqli_close($con);
  }

  echo json_encode($cat);
}
else
{
	$cat[][$i] = $sql;
	echo json_encode($cat);
  //http_response_code(404);
}
?>