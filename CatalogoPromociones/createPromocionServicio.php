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
  if ((int)$request->idEmpresa < 1 || trim($request->nombre) == '' || trim($request->capacidad) == '' || trim($request->canal) == ''
      || trim($request->alcance) == '' || trim($request->inicio) == '' || trim($request->_final) == '' || trim($request->periodo) == ''
	  || trim($request->actividad) == '' || trim($request->precioregular) == '' || trim($request->preciopromocion) == '') {    
  }


  // Sanitize.
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
  $uda = mysqli_real_escape_string($con, trim($request->uda));
  $fda = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc));

  $inicio = date_format(date_create($inicio),'Y-m-d');
  $final = date_format(date_create($final),'Y-m-d');

  // Create.
  $sql = "INSERT INTO `promocion`
(`idpromocion`,
`idempresa`,
`nombre`,
`capacidad`,
`canal`,
`alcance`,
`inicio`,
`final`,
`periodo`,
`actividad`,
`precioregular`,
`preciopromocion`,
`udc`,
`fdc`,
`uda`,
`fda`,
`idestatus`) VALUES (NULL,
'{$idEmpresa}',
'{$nombre}',
'{$capacidad}',
'{$canal}',
'{$alcance}',
cast('{$inicio}' as date),
cast('{$final}' as date),
'{$periodo}',
'{$actividad}',
'{$precioregular}',
'{$preciopromocion}',
'{$udc}',
'{$fdc}',
'{$uda}',
'{$fda}',
'1');";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
'idempresa' => $idEmpresa,
'nombre' => $nombre,
'capacidad' => $capacidad,
'canal' => $canal,
'alcance' => $alcance,
'inicio' => $inicio,
'final' => $final,
'periodo' => $periodo,
'actividad' => $actividad,
'precioregular' => $precioregular,
'preciopromocion' => $preciopromocion,
'udc' => $udc,
'fdc' => $fdc,
'uda' => $uda,
'fda' => $fda,
      'idpromocion'    => mysqli_insert_id($con)
    ];
    echo json_encode($catcadena);
  }
  else
  {
	  echo json_encode($catcadena = ['sql' => $sql]);
    //http_response_code(422);
  }
}
?>