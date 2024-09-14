<?php
/**
 * Regresa los datos de la rutas a las que debe de asistir el promotor
 */
require '../database.php';

$resultado = array();

// *****************************
// Extract, idpromotor y tienda
//$idruta = ($_GET['idruta'] !== null && strlen($_GET['idruta']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idruta']) : 0;
$idproducto = ($_GET['idproducto'] !== null && strlen($_GET['idproducto']) > 0)? mysqli_real_escape_string($con, $_GET['idproducto']) : 0;
$idruta = ($_GET['idproducto'] !== null && strlen($_GET['idruta']) > 0)? mysqli_real_escape_string($con, $_GET['idruta']) : 0;

// *****************************************************
// Consulta de datos
// $sql = "  select cp.upc, cp.descripcion, cp.descripcion1,  cp.cantidad_caja, cp.categoria1, cp.categoria2, cp.precio, ifnull(v.precioreal,0) as precioreal 
// from cat_productos cp  left join vw_producto_ruta_fecha v on cp.idproducto = v.idproducto and idruta=" . $idruta . " and cast(v.fda as date) = Cast(CURDATE() as date) 
// where cp.idproducto = " . $idproducto;

$sql = "Select cp.upc, cp.descripcion, cp.descripcion1, cp.cantidad_caja, cp.categoria1, cp.categoria2, pfp.precio, ifnull(v.precioreal,0) as precioreal  
from cat_productos cp 
inner join producto_formato_precio pfp on cp.idproducto = pfp.idproducto 
left join cat_rutas cr on pfp.idformato = cr.idformato  
left join vw_producto_ruta_fecha v on pfp.idproducto = v.idproducto and cr.idruta = v.idruta and cast(v.fda as date) = Cast(CURDATE() as date) 
Where cr.idruta = " . $idruta . " and pfp.idproducto = " . $idproducto . ";";

// *****************************************************
//echo($sql. "<br>");
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $pro['idproducto']      = $idproducto; 
    $pro['upc']             = $row['upc'] ;
    $pro['cantidad_caja']   = $row['cantidad_caja'];
    $pro['precio']          = $row['precio'];
    $pro['precioreal']      = $row['precioreal'];
    $pro['descripcion']     = str_replace("'", "", $row['descripcion']);
    $pro['descripcion1']    = str_replace("'", "", $row['descripcion1']);
    $pro['categoria1']      = str_replace("'", "",$row['categoria1']); 
    $pro['categoria2']      = str_replace("'", "",$row['categoria2']); 
    array_push($resultado,array('PRODUCTO'=>$pro));
    $i++;
  }

  //array_push($resultado,array('cuenta'=>$i));         // Agrega el nùmero de registros obtenidos
  echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
}
else
{
  http_response_code(404);
}