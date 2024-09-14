<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$cat = [];

$idproducto=(isset($_GET['idproducto']))? mysqli_real_escape_string($con, (int)$_GET['idproducto']) : false;

//$usuario = [];
$sql = "SELECT DISTINCT cp.idproducto, cp.upc, cp.descripcion, cc.nombrecorto , cf.formato, pfp.idproductoformatoprecio,  pfp.idformato, pfp.precio 
FROM producto_formato_precio pfp inner join cat_formato cf on pfp.idformato = cf.idformato
inner join cat_cadena cc on cf.idcadena = cc.idcadena 
inner join cat_productos cp on pfp.idproducto = cp.idproducto 
inner join empresa_cadena ec on cc.idcadena = ec.idcadena
where cp.idproducto = " . $idproducto . " and ec.idempresa = (Select cfg.valor from configuracion cfg where cfg.idconf = 2) order by cp.upc, cf.formato;";

//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idproducto']  = $row['idproducto'];
    $cat[$i]['upc']         =   $row['upc'];
    $cat[$i]['descripcion'] =   $row['descripcion'];
    $cat[$i]['nombrecorto'] =   $row['nombrecorto'];
    $cat[$i]['formato']     =   $row['formato'];
    $cat[$i]['idproductoformatoprecio']   =   $row['idproductoformatoprecio'];
    $cat[$i]['idformato']   =   $row['idformato'];
    $cat[$i]['precio']      =   $row['precio'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
	$cat['sql'] = $sql;
	echo json_encode($cat);
  ///http_response_code(404);
}
?>