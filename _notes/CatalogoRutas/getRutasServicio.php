<?php
/**
 * Regresa el usuario.
 */
require '../database.php';
require '../config.php';

$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/
$Tienda_dir=(isset($_GET['Tienda_dir']))? mysqli_real_escape_string($con, (string)$_GET['Tienda_dir']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;


// Condicionamiento de registros
if($Tienda_dir){
    //$condicion = "and Tienda like '%{$Tienda_dir}%' or direccioncompleta like '%{$Tienda_dir}%'";
    $condicion = "and ( Tienda like '%{$Tienda_dir}%' "
    . "or direccioncompleta like '%{$Tienda_dir}%' "
    . "or concat(cc.nombrecorto, ' ', cf.formato, ' ' , r.Tienda) like '%{$Tienda_dir}%' )";
}

// Condicion de ordenamiento
switch (intval($ord)){
    case -2:
        $orden = " order by r.idruta asc";
        break;
    case -1:
        $orden = " order by r.idruta desc";
        break;
    case 0:
        $orden = " order by r.idruta asc";
        break;
    case 1:
        $orden = " order by concat(cc.nombrecorto, ' ', cf.formato, ' ' , r.Tienda) asc ";
        break;
    case 2:
        $orden = " order by concat(cc.nombrecorto, ' ', cf.formato, ' ' , r.Tienda) desc ";
        break;
    case 3:
        $orden = " order by r.cluster asc";
        break;
    case 4:
        $orden = " order by r.cluster desc";
        break;  
    case 5:
        $orden = " order by r.direccioncompleta asc";
        break;
    case 6:
        $orden = " order by r.direccioncompleta desc";
        break;  
}


//$usuario = [];
$sql = "Select r.*, c.`cadena`,DATE_FORMAT(r.fda,'%d/%m/%Y') as fda1,DATE_FORMAT(r.fdc,'%d/%m/%Y') as fdc1,
ifnull(concat(cc.nombrecorto, ' ', cf.formato, ' ' , r.Tienda),'') as Tienda1, ci.descripcion 
from cat_rutas r left join cat_cadena c on c.idcadena = r.idcadena 
left join cat_cadena cc on r.idcadena = cc.idcadena
left join cat_formato cf on r.idformato = cf.idformato
left join cat_intensidad ci on r.intensidad = ci.idintensidad
where r.idEstatus = 1 and cc.idempresa = '{$cliente}' " . $condicion . $orden;
//echo ('<br>');
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