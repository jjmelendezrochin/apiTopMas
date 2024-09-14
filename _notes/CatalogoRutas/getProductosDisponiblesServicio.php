<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$condicion_productos="";
$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$producto=(isset($_GET['producto']))? mysqli_real_escape_string($con, (string)$_GET['producto']) : false;
$idruta=(isset($_GET['idruta']))? mysqli_real_escape_string($con, (int)$_GET['idruta']) : false;

$sql0="Select distinct idproducto from producto_ruta where idruta='".$idruta."' and idestatus=1";

$idproductos='(';

if($result = mysqli_query($con,$sql0))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $idproductos .= "'".$row['idproducto']."',";
  }
  $idproductos = substr_replace($idproductos,'',strlen($idproductos)-1,strlen($idproductos));
  $idproductos .= ")";
}

if($idproductos != ")"){
$condicion_productos="and not cp.idproducto in".$idproductos."";
}

if($producto){
$condicion = "where (cp.descripcion like '%".$producto."%' or cp.descripcion1 like '%".$producto."%' "
. "or cp.categoria1 like '%".$producto."%' or cp.categoria2 like '%".$producto."%')  ".$condicion_productos."";
}

//$usuario = [];
// $sql = "Select distinct cp.*,DATE_FORMAT(cp.fdc,'%d/%m/%Y') as fdc1,DATE_FORMAT(cp.fda,'%d/%m/%Y') as fda1,ce.nombreempresa,cc.nombrecorto,ifnull(pr.resurtible,'0') as resurtible from cat_productos cp
// left join cat_empresa ce on cp.idempresa = ce.idempresa
// left join cat_cadena cc on cp.idcadena = cc.idcadena
// left join producto_ruta pr on cp.idproducto = pr.idproducto $condicion order by cp.descripcion;";

$sql = "Select distinct cp.upc, cp.descripcion, cp.descripcion1,cp.cantidad_caja, cp.cantidad_kgs, 1 as idempresa, cp.categoria1, cp.categoria2, cp.udc,  
DATE_FORMAT(cp.fdc,'%d/%m/%Y') as fdc1, cp.fda, DATE_FORMAT(cp.fda,'%d/%m/%Y') as fda1 
from cat_productos cp
left join cat_empresa ce on cp.idempresa = ce.idempresa";

//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idproducto']    =   $row['idproducto'];
    $cat[$i]['upc']           =   $row['upc'] . "  [" . $idproducto . "]";
    $cat[$i]['descripcion']   =   $row['descripcion'];
    $cat[$i]['descripcion1']  =   $row['descripcion1'];
    $cat[$i]['cantidad_caja'] =   $row['cantidad_caja'];
    $cat[$i]['cantidad_kgs']  =   $row['cantidad_kgs'];
    $cat[$i]['idempresa']     =   $row['idempresa'];
    $cat[$i]['categoria1']    =   $row['categoria1'];
    $cat[$i]['categoria2']    =   $row['categoria2'];
    // $cat[$i]['idcadena']   =   $row['idcadena'];
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']         =   $row['fdc1'];
    $cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']         =   $row['fda1'];
    $cat[$i]['idestatus']     =   $row['idestatus'];
    //$cat[$i]['nombreempresa']   =   $row['nombreempresa'];
    //$cat[$i]['cadena']   =   $row['nombrecorto'];
    //$cat[$i]['resurtible']   =   $row['resurtible'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>