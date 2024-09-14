<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

//date_default_timezone_set('America/Mexico_City');

// Get the posted data.
//$postdata = file_get_contents("php://input");

// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;


/*if(trim($request->FechaInicial) === '' &&  trim($request->FechaFinal) === ''){
  return http_response_code(400);
}

// Sanitize.
$FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
$FechaFinal =  mysqli_real_escape_string($con, trim($request->FechaFinal));
$idoperacion = mysqli_real_escape_string($con, (int)$request->idoperacion);
$Tienda = mysqli_real_escape_string($con, trim($request->Tienda));
$idpromotor = mysqli_real_escape_string($con, (int)$request->idpromotor);
$idcadena = mysqli_real_escape_string($con, (int)$request->idcadena);

//$fecha=date('Y/m/d');
if(intval($idoperacion) > 0)
$condicion.=" and p.idoperacion='{$idoperacion}' ";

if($Tienda != "0")
$condicion.=" and cr.Tienda='{$Tienda}' ";

if(intval($idpromotor) > 0)
$condicion.=" and p.idpromotor='{$idpromotor}' ";

if(intval($idcadena) > 0)
$condicion.=" and cr.idcadena='{$idcadena}' ";*/

/*if($cadena){
$condicion = "and cadena like'{$cadena}%'";
}*/

$vw = "";
$cat = [];

if(intval($idEmpresa) > 0){
	$vw = "_" . $idEmpresa;
}

//$usuario = [];
$sql = "Select * from vw_ranking_capturas_usuarios" . $vw . ";";
//-- between '{$FechaInicial}' and '{$FechaFinal}' $condicion -- 
//--// echo($sql);
//--// echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromotor']      =   $row['idpromotor'];
    $cat[$i]['Usuario']      =   $row['Nombre'];
    $cat[$i]['Fecha']     =   $row['Fecha'];
    $cat[$i]['Total_de_capturas']        =   $row['Cta'];
    $i++;
  }

/*if(sizeof($cat)==0){
  $cat[0]['foto']      =   "";//$row['foto'];
  $cat[0]['promotor']     =   $sql;//$row['promotor'];
  $cat[0]['Tienda']        =  ""; //$row['Tienda'];
  $cat[0]['FechaHora']           =   "";//$row['FechaHora'];
  $cat[0]['actividad']           =   "";//$row['actividad'];
}*/

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>