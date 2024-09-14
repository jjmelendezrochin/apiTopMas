<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);

$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

//Verifica si se ha puesto la fecha
/*if(trim($request->FechaInicial) === ''){
  return http_response_code(400);
}*/

// Sanitize.
$FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
$Tienda = mysqli_real_escape_string($con, (int)trim($request->Tienda));
$idpromotor = mysqli_real_escape_string($con, (int)$request->idpromotor);

//$fecha=date('Y/m/d');
if($FechaInicial != "" )
 $condicion.=" Cast(FechaHora as date) = cast('{$FechaInicial}' as date) and ";

if(intval($Tienda) > 0)
$condicion.=" idruta = '{$Tienda}' and ";

if(intval($idpromotor) > 0)
$condicion.=" idpromotor = '{$idpromotor}' and ";

//$fecha=date('Y/m/d');

/*if($cadena){
$condicion = "and cadena like'{$cadena}%'";
}*/

//$usuario = [];
$sql = "Select a.idpromotor, a.idruta,   Concat(p.nombre,' ' , p.apellidos) as Nombre,  
r.Tienda, 
a.Diferencia, DATE_FORMAT(Max, '%d/%m/%Y')  as Fecha 
from
(
Select idpromotor, idruta, max(FechaHora) as Max, min(FechaHora) as Min,
timediff( max(FechaHora),  min(FechaHora)) as Diferencia 
from photos
where $condicion
 idoperacion in (1,2)		-- solo checkin y checkout
group by idpromotor, idruta
) as a
inner join cat_promotor p on a.idpromotor = p.idpromotor
inner join cat_rutas r on a.idruta = r.idruta";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['promotor']      =   $row['Nombre'];
    $cat[$i]['Tienda']     =   $row['Tienda'];
    $cat[$i]['estancia']        =   $row['Diferencia'];
    $cat[$i]['fecha']     =   $row['Fecha'];
    $i++;
  }
/*if(sizeof($cat)==0){
  $cat[0]['promotor']      =   $sql;//$row['foto'];
  $cat[0]['Tienda']        =  ""; //$row['Tienda'];
  $cat[0]['estancia']           =   "";//$row['FechaHora'];
  $cat[0]['fecha']           =   "";//$row['actividad'];
}*/
  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
}
?>