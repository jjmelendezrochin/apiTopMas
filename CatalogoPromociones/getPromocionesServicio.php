<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$nombre=(isset($_GET['nombre']))? mysqli_real_escape_string($con, (string)$_GET['nombre']) : false;
$idempresa=(isset($_GET['idempresa']))? mysqli_real_escape_string($con, (string)$_GET['idempresa']) : 1;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : -2;

// Condición de selecci
if($nombre){
    $condicion = "and (nombre like '" . ltrim(rtrim($nombre)) . "%' or 
	              nombre like '%" . ltrim(rtrim($nombre)) ."' or
				  nombre like '%" . ltrim(rtrim($nombre)) . "%' or 
				  nombre like '" . ltrim(rtrim($nombre)) . "')";
}

// Condicion de ordenamiento
switch (intval($ord)){
	case -2:
	    $orden = " order by p.idpromocion asc"; 
	    break;
	case -1:
	    $orden = " order by p.idpromocion desc";
	    break;
    case 0:
        $orden = " order by e.nombreempresa asc";
        break;
    case 1:
        $orden = " order by e.nombreempresa desc";
        break;
    case 2:
        $orden = " order by p.nombre asc";
        break;
    case 3:
        $orden = " order by p.nombre desc";
        break;
    case 4:
        $orden = " order by p.capacidad asc";
        break;  
	case 5:
        $orden = " order by p.capacidad desc";
        break;  
	case 6:
        $orden = " order by p.canal asc";
        break;  
	case 7:
        $orden = " order by p.canal desc";
        break;  
	case 8:
        $orden = " order by p.actividad asc";
        break;  	
	case 9:
        $orden = " order by p.actividad desc";
        break;  
}

//$usuario = [];
$sql = "Select p.*,DATE_FORMAT(p.fda,'%d/%m/%Y') as fda1,DATE_FORMAT(p.fdc,'%d/%m/%Y') as fdc1,e.nombreempresa from promocion p 
left join cat_empresa e on p.idempresa = e.idempresa 
where p.idestatus = 1 
and e.idempresa = " . $idempresa .  $condicion .  $orden . ";";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromocion']      =   $row['idpromocion'];
    $cat[$i]['idempresa']     =   $row['idempresa'];
    $cat[$i]['nombre']   =   $row['nombre'];
    $cat[$i]['capacidad']        =   $row['capacidad'];
	$cat[$i]['canal']        =   $row['canal'];
	$cat[$i]['alcance']        =   $row['alcance'];
    $cat[$i]['inicio']        =   $row['inicio'];
	$cat[$i]['final']        =   $row['final'];
	$cat[$i]['periodo']        =   $row['periodo'];    
	$cat[$i]['actividad']        =   $row['actividad'];
    $cat[$i]['precioregular']        =   $row['precioregular'];
    $cat[$i]['preciopromocion']        =   $row['preciopromocion'];
	$cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']         =   $row['fda1'];
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']         =   $row['fdc1'];
    $cat[$i]['idestatus']     =   $row['idestatus'];    
    $cat[$i]['nombreempresa'] =   $row['nombreempresa'];
	$cat[$i]['ruta'] =   $row['ruta'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  echo json_encode($cat=["sql"=>$sql]);
//  http_response_code(404);
}
?>