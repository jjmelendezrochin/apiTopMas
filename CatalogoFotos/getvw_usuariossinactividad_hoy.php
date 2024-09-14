<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

$vw = "";
$cat = [];
$condicion = "(Select cfg.valor from configuracion cfg where cfg.idconf = 2)";

if(intval($idEmpresa) > 0){
	$vw = "_" . $idEmpresa;
	$condicion = $idEmpresa;
}

$sql = "Select vu.*,(Select usa.observaciones from usuarios_sinactividad usa where usa.idpromotor = vu.idpromotor
and (Select count(*) from cat_promotor cp where cp.idpromotor = usa.idpromotor and cp.idempresa = " . $condicion . ") > 0
) as observaciones from vw_usuariossinactividad_hoy" . $vw . " vu order by 2 asc;";

if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromotor']      =   $row['idpromotor'];
    $cat[$i]['Usuario_SinRegistro']      =   $row['Usuario_SinRegistro'];
	$cat[$i]['observaciones']      =   $row['observaciones'];
$cat[$i]['icono']  = ($row['observaciones'] == NULL)?'add_circle_outline':'add_circle';
	$i++;
  }
  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>