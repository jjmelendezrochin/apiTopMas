<?php
 /**
 * Obtiene los datos del panel de control para cada empresa
 */
require 'database.php';
date_default_timezone_set('America/Mexico_City');

$cat = [];


// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

if(intval($idEmpresa) > 0){
	$vw = "_" . $idEmpresa;
	$condicion = $idEmpresa;
}


$sql = "Select `CtaTiendas`,  `CtaVisitas`,  `CtaTiendasSinVisitar`,  `Visitas_Diarias`, `CtaUsuarios`,   `CtaUsuariosActivos`,
`CtaUsuariosinactivos`, `CtaUsuariosTransito` , date_format(FechaHora,'%d-%m-%Y %H:%i') as FechaHora, cat_empresa.nombreempresa
from datospanel inner join cat_empresa on datospanel.idEmpresa = cat_empresa.idempresa
where datospanel.idEmpresa = " . $idEmpresa . ";";

if($result = mysqli_query($con,$sql))
{
  $i=0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['Total_tiendas']     			=   $row['CtaTiendas'];
    $cat[$i]['Tiendas_visitadas']     		=   $row['CtaVisitas'];
    $cat[$i]['Tiendas_sin_visitar']     	=   $row['CtaTiendasSinVisitar'];
    $cat[$i]['Visitas_Diarias']     		=   $row['Visitas_Diarias'];
    $cat[$i]['Total_usuarios']     			=   $row['CtaUsuarios'];
    $cat[$i]['Total_usuarios_activos']     	=   $row['CtaUsuariosActivos'];
    $cat[$i]['Total_usuarios_inactivos']    =   $row['CtaUsuariosinactivos'];
    $cat[$i]['Usuarios_transito']     		=   $row['CtaUsuariosTransito'];
	$cat[$i]['FechaHora']     				=   $row['FechaHora'];
	$cat[$i]['NombreEmpresa']     			=   $row['nombreempresa'];
    // $cat[$i]['sql']=   $sql;
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}

/*
$sql = "SELECT 
(Select count(*) from vw_tiendas_hoy" . $vw . ") as CtaTiendas,
ifnull((Select Cta from Vw_visitastemporaleshoy_asistir where idempresa=" . $idEmpresa . "),0) as Ctaasistir,
ifnull((Select Cta from Vw_visitastemporaleshoy_noasistir where idempresa=" . $idEmpresa . "),0) as Ctanoasistir,
(Select count(*) from vw_vistagrupo_fechapromotortienda_checkin" . $vw . " v1 inner join vw_tiendas_hoy" . $vw . " v2 on v1.idruta = v2.idruta where cast(FechaHora as Date) = cast(current_timestamp() as Date) order by FechaHora desc) as CtaVisitas,
(SELECT CtaTiendas+Ctaasistir-Ctanoasistir) as CtaTiendasPorVisitar,
(SELECT CtaTiendas+Ctaasistir-Ctanoasistir-CtaVisitas) as CtaTiendasSinVisitar,
(SELECT concat(round((CtaVisitas/CtaTiendas)*100),'%')) as Visitas_Diarias,
(Select count(*) from cat_promotor where idestatus = 1 and cat_promotor.idempresa = " . $idEmpresa . ") as CtaUsuarios,
(SELECT COUNT(*) FROM (SELECT idpromotor FROM `vw_checkin_hoy" . $vw . "` GROUP BY idpromotor) AS A) as CtaUsuariosActivos,
(SELECT '0') as CtaUsuariosTransito,
(Select date_format(now(),'%d-%m-%Y %H:%i')) as FechaHora,
(Select cat_empresa.nombreempresa from cat_empresa where idempresa = " . $idEmpresa . ") as nombreempresa;";
*/
?>


