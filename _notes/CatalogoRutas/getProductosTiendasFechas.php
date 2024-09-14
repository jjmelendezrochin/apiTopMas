<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$cat = [];

$idproducto=(isset($_GET['idproducto']))? mysqli_real_escape_string($con, (int)$_GET['idproducto']) : false;
$idruta=(isset($_GET['idruta']))? mysqli_real_escape_string($con, (int)$_GET['idruta']) : false;

$sql = "Select prf.descripcion,prf.invinicial,prf.invfinal, prf.categoria1, prf.precio, prf.precioreal,
prf.diferencia, prf.Fecha, prf.Nombre, prf.Tienda, prf.observaciones
from vw_productos_tiendas_fechas prf 
where prf.idproducto = " . $idproducto . " and prf.idruta = " . $idruta . "
order by prf.fda desc;";

//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['descripcion'] = $row['descripcion'];
    $cat[$i]['categoria1']   =   $row['categoria1'];
	$cat[$i]['invinicial']   =   $row['invinicial'];
    $cat[$i]['invfinal']   =   $row['invfinal'];
    $cat[$i]['precio']   =   $row['precio'];
    $cat[$i]['precioreal']   =   $row['precioreal'];
    $cat[$i]['diferencia']   =   $row['diferencia'];
    $cat[$i]['Fecha']   =   $row['Fecha'];
    $cat[$i]['Nombre']   =   $row['Nombre'];
    $cat[$i]['Tienda']   =   $row['Tienda'];
	$cat[$i]['observaciones']  =  $row['observaciones'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>