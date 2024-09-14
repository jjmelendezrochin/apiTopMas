<?php
/**
 * Regresa los datos de la rutas a las que debe de asistir el promotor
 */
require '../database.php';

$resultado = array();

// *****************************
// Extract, idpromotor y tienda
$idruta = ($_GET['idruta'] !== null && strlen($_GET['idruta']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idruta']) : 0;
$producto = ($_GET['producto'] !== null && strlen($_GET['producto']) > 0)? mysqli_real_escape_string($con, (string)$_GET['producto']) : "%";
$idempresa = ($_GET['idempresa'] !== null && strlen($_GET['idempresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idempresa']) : 0;

if(!$idruta || !$idempresa)
{
  return http_response_code(400);
}

// *****************************************************
// Consulta la lista de todos los productos para todas las tiendas
// $sql = "  Select idproducto, idruta, descripcion, categoria2 as categoria ";
// $sql .= " from vw_producto_ruta where idruta = " . $idruta  . " and descripcion like '%" . $producto . "%' order by descripcion;";

//$sql = "  Select idproducto, " . $idruta  . " as idruta, concat(descripcion, ' ', descripcion1, ' ',cantidad_kgs) as descripcion , categoria2  as categoria, upc from cat_productos cp "; 
$sql = "  Select idproducto, " . $idruta  . " as idruta, concat(descripcion, ' ',cantidad_kgs) as descripcion , categoria2  as categoria, upc from cat_productos cp "; 
$sql .= " where idestatus = 1 and descripcion like '%" . $producto . "%' and idempresa = '" . $idempresa . "' order by descripcion;";

// *****************************************************
//echo($sql. "<br>");
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $pro['idproducto']    = $row['idproducto']; 
    $pro['idruta']        = $row['idruta'];
    $pro['descripcionproducto']   = str_replace("'", "", $row['descripcion']);
    $pro['categoriaproducto']     = str_replace("'", "",$row['categoria']); 
    $pro['upc']                   = str_replace("'", "",$row['upc']);
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
?>
