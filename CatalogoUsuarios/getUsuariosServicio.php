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

$usuario=(isset($_GET['usuario']))? mysqli_real_escape_string($con, (string)$_GET['usuario']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;

// Condición de selecci
if($usuario){
    $condicion = " and (u.usuario like '" . ltrim(rtrim($usuario)) . "%' or p.perfil like '" . ltrim(rtrim($usuario)) . "%')";
}

// Condicion de ordenamiento
switch (intval($ord)){
    case 0:
        $orden = " order by u.idusuario desc";
        break;
    case 1:
        $orden = " order by u.idusuario asc ";
        break;
    case 2:
        $orden = " order by u.usuario desc";
        break;
    case 3:
         $orden = " order by p.perfil asc";
        break;
    case 4:
        $orden = " order by p.perfil desc";
        break;  
}

//$usuario = [];
$sql = "Select u.idUsuario, u.usuario, u.clave, u.activo, u.idperfil, p.perfil, u.uda, u.fda, u.udc, u.fdc, DATE_FORMAT(u.fda,'%d/%m/%Y') as fda1, DATE_FORMAT(u.fdc,'%d/%m/%Y') as fdc1,u.idempresa from usuarios u inner join perfiles p on u.idperfil = p.idperfil where u.activo = 1 " . $condicion .  $orden . ";";
/*Select c.*, e.`nombreempresa`,DATE_FORMAT(c.fda,'%d/%m/%Y') as fda1,DATE_FORMAT(c.fdc,'%d/%m/%Y') as fdc1
from cat_cadena c inner join cat_empresa e on c.idEmpresa = e.idEmpresa 
where c.idestatus = 1*/
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idUsuario']      =   $row['idUsuario'];
    $cat[$i]['usuario']      =   $row['usuario'];
    $cat[$i]['clave']        =   $row['clave'];
    $cat[$i]['activo']     =   $row['activo'];
    $cat[$i]['idperfil']   =   $row['idperfil'];
    $cat[$i]['perfil']   =   $row['perfil'];
    $cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']         =   $row['fda1'];
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']         =   $row['fdc1'];
    $cat[$i]['idempresa']         =   $row['idempresa'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  echo json_encode($cat=["sql"=>$sql]);
//  http_response_code(404);
}
?>