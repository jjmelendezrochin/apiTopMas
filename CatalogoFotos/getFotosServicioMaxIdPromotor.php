<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

$condicion = "(Select cfg.valor from configuracion cfg where cfg.idconf = 2)";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$fecha=date('Y/m/d');

/*if($cadena){
$condicion = "and cadena like'{$cadena}%'";
}*/

//$usuario = [];
if(intval($idEmpresa) > 0){
	$condicion = $idEmpresa;
}

$sql = "select u.idubicacion,ubic.latitud,ubic.longitud,ubic.idpromotor,DATE_FORMAT(u.FechaHora,'%d/%m/%Y %H:%i:%s') as FechaHora ,cp.idusuario
from ( 
select idpromotor,Max(idubicacion) as idubicacion,latitud,longitud, Max(FechaHora) as FechaHora
from ubicacion where Cast(FechaHora as date)=Cast((select cast(now() as date)) as date)
and (Select count(*) from cat_promotor p where p.idpromotor = ubicacion.idpromotor and p.idempresa = " . $condicion  .  ") > 0
group by idpromotor,Cast(FechaHora as date)
) as u left join cat_promotor cp on u.idpromotor=cp.idpromotor 
left join ubicacion ubic on u.idubicacion = ubic.idubicacion
where (Select count(*) from cat_promotor p where p.idpromotor = ubic.idpromotor and p.idempresa = " . $condicion  .  ") > 0
and cp.idempresa = " . $condicion  .  "
order by 1;
";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idubicacion']      =   $row['idubicacion'];
    $cat[$i]['latitud']     =   $row['latitud'];
    $cat[$i]['longitud']        =   $row['longitud'];
    $cat[$i]['idpromotor']           =   $row['idpromotor'];
    $cat[$i]['FechaHora']           =   $row['FechaHora'];
    $cat[$i]['idusuario']           =   $row['idusuario'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>