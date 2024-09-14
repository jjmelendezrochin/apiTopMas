<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$cat = [];
// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;
$idruta=(isset($_GET['idruta']))? mysqli_real_escape_string($con, (int)$_GET['idruta']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}/**/



//$usuario = [];
// $sql = "Select pr.idproductoruta,pr.resurtible,cp.*,ce.nombreempresa,cc.nombrecorto,DATE_FORMAT(cp.fdc,'%d/%m/%Y') as fdc1,DATE_FORMAT(cp.fda,'%d/%m/%Y') as fda1 from producto_ruta pr
// left join cat_productos cp on pr.idproducto = cp.idproducto
// left join cat_empresa ce on cp.idempresa = ce.idempresa
// left join cat_cadena cc on cp.idcadena = cc.idcadena
// left join cat_rutas cr on pr.idruta = cr.idruta
// where pr.idruta='{$idruta}' and pr.idestatus='1' order by cp.descripcion asc;";

$sql = "Select cp.*,
DATE_FORMAT(cp.fdc,'%d/%m/%Y') as fdc1,
DATE_FORMAT(cp.fda,'%d/%m/%Y') as fda1 
from cat_productos cp where cp.idempresa = " . $idEmpresa  . ";";

//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    //$cat[$i]['idproductoruta'] = $row['idproductoruta'];
    $cat[$i]['upc']   =   $row['upc'] /*. " [" . $row['idproducto'] . "], [" . $idruta . "]"*/;
    $cat[$i]['descripcion']   =   $row['descripcion'];
    $cat[$i]['descripcion1']   =   $row['descripcion1'];
    $cat[$i]['cantidad_caja']   =   $row['cantidad_caja'];
    $cat[$i]['cantidad_kgs']   =   $row['cantidad_kgs'];
    //$cat[$i]['idempresa']   =   $row['idempresa'];
    $cat[$i]['categoria1']   =   $row['categoria1'];
    $cat[$i]['categoria2']   =   $row['categoria2'];
    //$cat[$i]['idcadena']   =   $row['idcadena'];
    $cat[$i]['udc']   =   $row['udc'];
    $cat[$i]['fdc']   =   $row['fdc'];
    $cat[$i]['fdc_m']   =   $row['fdc1'];
    $cat[$i]['uda']   =   $row['uda'];
    $cat[$i]['fda']   =   $row['fda'];
    $cat[$i]['fda_m']   =   $row['fda1'];
    $cat[$i]['idestatus']   =   $row['idestatus'];
    //$cat[$i]['nombreempresa']   =   $row['nombreempresa'];
    //$cat[$i]['cadena']   =   $row['nombrecorto'];
    //$cat[$i]['resurtible']   =   $row['resurtible'];
    $cat[$i]['idproducto']   =   $row['idproducto'];
    $cat[$i]['idruta']   =   $idruta;
    $i++;
  }

  echo json_encode($cat);
}
else
{
	$cat['sql']=$sql;
	echo json_encode($cat);
  //http_response_code(404);
}
?>