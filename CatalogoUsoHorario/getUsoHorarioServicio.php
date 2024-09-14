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

/*$cadena=(isset($_GET['cadena']))? mysqli_real_escape_string($con, (string)$_GET['cadena']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;


if($cadena){
$condicion = " and (cz.letrazona like 'ltrim(rtrim({$cadena}%))' or cz.descripcion like 'ltrim(rtrim({$cadena}%))' 
or cz.estados like 'ltrim(rtrim({$cadena}%')))";
}


// Condicion de ordenamiento
switch (intval($ord)){
    case 0:
        $orden = " order by cz.letrazona asc";
        break;
    case 1:
        $orden = " order by cz.letrazona asc ";
        break;
    case 2:
        $orden = " order by cz.letrazona desc";
        break;
    case 3:
         $orden = " order by cz.estados asc";
        break;
    case 4:
        $orden = " order by cz.estados desc";
        break;
}*/

//$usuario = [];
$sql = "SELECT * FROM cat_usohorario ORDER BY idusohorario;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idusohorario']      =   $row['idusohorario'];
    $cat[$i]['dif_horario_normal']     =   $row['dif_horario_normal'];
    $cat[$i]['desc_horario_normal']        =   $row['desc_horario_normal'];
    $cat[$i]['dif_horario_verano']        =   $row['dif_horario_verano'];
    $cat[$i]['desc_horario_verano']           =   $row['desc_horario_verano'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>