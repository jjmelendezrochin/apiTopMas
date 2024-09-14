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

$idpromotor=(isset($_GET['idpromotor']))? mysqli_real_escape_string($con, (string)$_GET['idpromotor']) : false;

/*if($idpromotor){
$condicion = "and p.nombre like'{$campos}%' or p.apellidos like'{$campos}%'";
}*/

//$usuario = [];
$sql = "Select distinct p.*,DATE_FORMAT(p.fda,'%d/%m/%Y') as fda1,DATE_FORMAT(p.fdc,'%d/%m/%Y') as fdc1, e.`nombreempresa`,concat(p.`nombre`,' ',p.`apellidos`) as nombrecompleto_s,cz.estados as zona from cat_promotor p
left join cat_empresa e on p.idempresa = e.idempresa
left join rutas_promotor rp on rp.idpromotor = p.idpromotor
left join rutas_promotor_dias rpd on rpd.idpromotor= rp.idpromotor
left join cat_zonas cz on p.idzona = cz.idzona
where p.tipo = 1 and p.idpromotor='{$idpromotor}' order by 1;";
//echo($sql);
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromotor']      =   $row['idpromotor'];
    $cat[$i]['idempresa']     =   $row['idempresa'];
    $cat[$i]['nombre']        =   $row['nombre'];
    $cat[$i]['apellidos']        =   $row['apellidos'];
    $cat[$i]['idusuario']        =   $row['idusuario'];
    $cat[$i]['rol']        =   $row['rol'];
    $cat[$i]['correo']        =   $row['correo'];
    $cat[$i]['estatus']        =   $row['estatus'];
    $cat[$i]['QR']        =   $row['QR'];
    $cat[$i]['idusohorario']        =   $row['idusohorario'];
    $cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']           =   $row['fda1']; 
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']           =   $row['fdc1']; 
    $cat[$i]['idestatus']     =   $row['idestatus'];
    if(intval($row['idestatus']) == 0){
      $cat[$i]['estatus_btn'] =  "Inactivo";
      $cat[$i]['btn_estilo']="i_estatus";
    }else if(intval($row['idestatus']) == 1){
      $cat[$i]['estatus_btn'] =  "Activo";
      $cat[$i]['btn_estilo']="a_estatus";
    }
    $cat[$i]['pwd']        =   $row['pwd'];
    $cat[$i]['nombreempresa'] =   $row['nombreempresa'];
    $cat[$i]['nombrecompleto_s']        =   $row['nombrecompleto_s'];
    $cat[$i]['idzona']         =   $row['idzona'];
    $cat[$i]['zona']         =   $row['zona'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>