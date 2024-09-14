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

$idruta=(isset($_GET['idruta']))? mysqli_real_escape_string($con, (string)$_GET['idruta']) : false;

/*if($idpromotor){
$condicion = "and p.nombre like'{$campos}%' or p.apellidos like'{$campos}%'";
}*/

//$usuario = [];
$sql = "Select r.*, c.`cadena`,DATE_FORMAT(r.fda,'%d/%m/%Y') as fda1,DATE_FORMAT(r.fdc,'%d/%m/%Y') as fdc1,
ifnull(concat(cc.nombrecorto, ' ', cf.formato, ' ' , r.Tienda),'') as Tienda1, ci.descripcion 
from cat_rutas r left join cat_cadena c on c.idcadena = r.idcadena 
left join cat_cadena cc on r.idcadena = cc.idcadena
left join cat_formato cf on r.idformato = cf.idformato
left join cat_intensidad ci on r.intensidad = ci.idintensidad
where r.idEstatus = 1 and  r.idruta = '{$idruta}' order by 1;";
//echo($sql);
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idruta']      =   $row['idruta'];
    $cat[$i]['ruta']     =   $row['ruta'];
    $cat[$i]['determinante']        =   $row['determinante'];
    $cat[$i]['idcadena']        =   $row['idcadena'];
    $cat[$i]['formato']        =   $row['formato'];
    $cat[$i]['Tienda']        =   $row['Tienda'];
    $cat[$i]['direccioncompleta']        =   $row['direccioncompleta'];
    $cat[$i]['idmunicipio']        =   $row['idmunicipio'];
    $cat[$i]['idestado']        =   $row['idestado'];
    $cat[$i]['cluster']          =   $row['cluster'];
    $cat[$i]['intensidad']        =   $row['intensidad'];
    $cat[$i]['intensidad_str']    =   $row['descripcion'];
    $cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']         =   $row['fda1'];
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']         =   $row['fdc1'];
    $cat[$i]['idEstatus']     =   $row['idEstatus'];
    $cat[$i]['latitud']     =   $row['latitud'];
    $cat[$i]['longitud']     =   $row['longitud'];
    if(intval($row['idEstatus']) == 0){
      $cat[$i]['estatus_btn'] =  "Inactivo";
      $cat[$i]['btn_estilo']="i_estatus";
    }else if(intval($row['idEstatus']) == 1){
      $cat[$i]['estatus_btn'] =  "Activo";
      $cat[$i]['btn_estilo']="a_estatus";
    }
    $cat[$i]['cadena']    =   $row['cadena'];
    $cat[$i]['idformato'] =   $row['idformato'];
    $cat[$i]['Tienda1']   =   $row['Tienda1'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>