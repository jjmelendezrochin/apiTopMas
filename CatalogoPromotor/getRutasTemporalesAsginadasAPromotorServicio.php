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
$sql = "
Select distinct rap.idrutaasignada,rap.idpromotor,rap.idruta,
rap.uda,DATE_FORMAT(rap.fechaasignacion,'%d/%m/%Y') as fda,DATE_FORMAT(rap.dia,'%d/%m/%Y') as dia,rap.dia as dia1,rap.idestatus,
concat(cc.nombrecorto, ' ', cf.formato, ' ' , cr.Tienda) as Tienda,
rap.observaciones,if(rap.asiste = 0,'No','Si') as asiste
from rutas_promotor_temporal rap 
left join cat_rutas cr on rap.idruta = cr.idruta 
left join cat_promotor cp on rap.idpromotor = cp.idpromotor 
left join cat_formato cf on cr.idformato = cf.idformato
left join cat_cadena cc on cf.idcadena = cc.idcadena
left join empresa_cadena ec on cc.idcadena = ec.idcadena
where rap.idpromotor='{$idpromotor}' and rap.idestatus='1'
and ec.idempresa = (Select cfg.valor from configuracion cfg where cfg.idconf = 2)
order by rap.dia desc;";
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
    $cat[$i]['Tienda']      =   $row['Tienda'];
    $cat[$i]['dia']     =   $row['dia'];
	$cat[$i]['dia1']     =   $row['dia1'];
    $cat[$i]['observaciones']      =   $row['observaciones'];    
	$cat[$i]['uda']     =   $row['uda'];
    $cat[$i]['fechaasignacion']     =   $row['fda'];
    $cat[$i]['asiste']      =   $row['asiste'];
	$cat[$i]['desabilitar']      =   (strtolower(trim($row['asiste'])) == 'si')? false:true;
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>