<?php
require '../database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);


  // Validate.
  if ((int)$request->idpromocion < 1 || (int)$request->idEmpresa < 1 || trim($request->nombre) == '' || trim($request->capacidad) == '' || trim($request->canal) == ''
      || trim($request->alcance) == '' || trim($request->inicio) == '' || trim($request->_final) == ''
	  || trim($request->actividad) == '' || trim($request->precioregular) == '' || trim($request->preciopromocion) == '') {
    return http_response_code(400);
  }


  // Sanitize.
  $idpromocion    = mysqli_real_escape_string($con, (int)$request->idpromocion);
  $idEmpresa    = mysqli_real_escape_string($con, (int)$request->idEmpresa);
  $nombre = mysqli_real_escape_string($con, trim($request->nombre));
  $capacidad = mysqli_real_escape_string($con, trim($request->capacidad));
  $canal = mysqli_real_escape_string($con, trim($request->canal));
  $alcance = mysqli_real_escape_string($con, trim($request->alcance));
  $inicio = mysqli_real_escape_string($con, trim($request->inicio));
  $final = mysqli_real_escape_string($con, trim($request->_final));
  $periodo = mysqli_real_escape_string($con, trim($request->periodo));
  $actividad = mysqli_real_escape_string($con, trim($request->actividad));
  $precioregular = mysqli_real_escape_string($con, trim($request->precioregular));
  $preciopromocion = mysqli_real_escape_string($con, trim($request->preciopromocion));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc));

  $inicio = date_format(date_create($inicio),'Y-m-d');
  $final = date_format(date_create($final),'Y-m-d');


  // Create.
  $sql = "UPDATE `promocion`
SET
`idempresa` = '{$idEmpresa}',
`nombre` = '{$nombre}',
`capacidad` = '{$capacidad}',
`canal` = '{$canal}',
`alcance` = '{$alcance}',
`inicio` = cast('{$inicio}' as date),
`final` = cast('{$final}' as date),
`periodo` = '{$periodo}',
`actividad` = '{$actividad}',
`precioregular` = '{$precioregular}',
`preciopromocion` = '{$preciopromocion}',
`udc` = '{$udc}',
`fdc` = '{$fdc}'
WHERE `promocion`.`idpromocion` = '{$idpromocion}';";

  if(mysqli_query($con,$sql))
  {
    http_response_code(204);
  }
  else
  {
    http_response_code(422);
  }
}
?>