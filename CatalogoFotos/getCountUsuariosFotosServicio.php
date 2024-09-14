<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

$vw = "";
$cat = [];


// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

/*
-- ****************************
-- Consulta de prueba empresa 7
SELECT 
(Select count(*) from vw_tiendas_hoy_7) as CtaTiendas,
ifnull((Select Cta from Vw_visitastemporaleshoy_asistir where idempresa=7),0) as Ctaasistir,
ifnull((Select Cta from Vw_visitastemporaleshoy_noasistir where idempresa=7),0) as Ctanoasistir,
(Select count(*) from vw_vistagrupo_fechapromotortienda_checkin_7 v1 inner join vw_tiendas_hoy_7 v2 on v1.idruta = v2.idruta where cast(FechaHora as Date) = cast(current_timestamp() as Date) order by FechaHora desc) as CtaVisitas,
(SELECT CtaTiendas+Ctaasistir-Ctanoasistir) as CtaTiendasPorVisitar,
(SELECT CtaTiendas+Ctaasistir-Ctanoasistir-CtaVisitas) as CtaTiendasSinVisitar,
(SELECT concat(round((CtaVisitas/CtaTiendas)*100),'%')) as Visitas_Diarias,
(Select count(*) from cat_promotor where idestatus = 1 and cat_promotor.idempresa = 7) as CtaUsuarios,
(SELECT COUNT(*) FROM (SELECT idpromotor FROM `vw_checkin_hoy_7` GROUP BY idpromotor) AS A) as CtaUsuariosActivos,
(SELECT '0') as CtaUsuariosTransito;
-- ****************************
*/

if(intval($idEmpresa) > 0){
	$vw = "_" . $idEmpresa;
	$condicion = $idEmpresa;
}

//$usuario = [];
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
(SELECT '0') as CtaUsuariosTransito;
(Select date_format(now(),'%d-%m-%Y %H:%i')) as FechaHora,
(Select cat_empresa.nombreempresa from cat_empresa where idempresa = " . $idEmpresa . ") as nombreempresa;";

if($result = mysqli_query($con,$sql))
{
    $i=0;
  while($row = mysqli_fetch_assoc($result))
  {
	//$cat[$i]['CtaTiendasPorVisitar']    =   $row['CtaTiendas'];
	//$cat[$i]['Ctaasistir']     			=   $row['Ctaasistir'];
	//$cat[$i]['Ctanoasistir']     		=   $row['Ctanoasistir'];
    $cat[$i]['Total_tiendas']     		=   $row['CtaTiendasPorVisitar'];
    $cat[$i]['Tiendas_visitadas']     	=   $row['CtaVisitas'];
    $cat[$i]['Tiendas_sin_visitar']     =   $row['CtaTiendasSinVisitar'];
    $cat[$i]['Visitas_Diarias']     	=   $row['Visitas_Diarias'];
    $cat[$i]['Total_usuarios']     		=   $row['CtaUsuarios'];
    $cat[$i]['Total_usuarios_activos']  =   $row['CtaUsuariosActivos'];
    $cat[$i]['Total_usuarios_inactivos']     =   ($row['CtaUsuarios']-$row['CtaUsuariosActivos']);
    $cat[$i]['Usuarios_transito']     	=   $row['CtaUsuariosTransito'];
	$cat[$i]['FechaHora']     			=   $row['FechaHora'];
	$cat[$i]['NombreEmpresa']     		=   $row['nombreempresa'];

    // $cat[$i]['sql']=   $sql;
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>