<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$idpromotor=(isset($_GET['idpromotor']))? mysqli_real_escape_string($con, (int)$_GET['idpromotor']) : false;

//$usuario = [];
$sql = "Select distinct rap.idrutaasignada,rap.idpromotor,rap.idruta,cr.determinante
,cr.Tienda as Tienda,cr.direccioncompleta as Direccion,
rap.uda,DATE_FORMAT(rap.fechaasignacion,'%d/%m/%Y') as fda,rap.idestatus,
cr.determinante, concat(cc.nombrecorto, ' ', cf.formato, ' ' , cr.Tienda) as Tienda1
from rutas_promotor rap 
left join cat_rutas cr on rap.idruta = cr.idruta 
left join cat_promotor cp on rap.idpromotor = cp.idpromotor
left join cat_formato cf on cr.idformato = cf.idformato
left join cat_cadena cc on cf.idcadena = cc.idcadena
left join empresa_cadena ec on cc.idcadena = ec.idcadena
where rap.idpromotor='{$idpromotor}' and rap.idestatus='1' 
and ec.idempresa = (Select cfg.valor from configuracion cfg where cfg.idconf = 2)
order by 1 asc;";
// and cc.idempresa = (Select cfg.valor from configuracion cfg where cfg.idconf = 2)
// and cf.idempresa = (Select cfg.valor from configuracion cfg where cfg.idconf = 2)

//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idrutaasignada'] = $row['idrutaasignada'];
    $cat[$i]['idpromotor'] = $row['idpromotor'];
    $cat[$i]['idruta'] = $row['idruta'];
    $cat[$i]['determinante'] = $row['determinante'];
    $cat[$i]['Tienda']      =   $row['Tienda'];
    $cat[$i]['Direccion']     =   $row['Direccion'];
    $cat[$i]['uda']     =   $row['uda'];
    $cat[$i]['fechaasignacion']     =   $row['fda'];
    if($row['idestatus'] == '1'){
    $cat[$i]['estatus']     =   'Activo';
    $cat[$i]['Tienda1']      =   $row['Tienda1'];
    }
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>