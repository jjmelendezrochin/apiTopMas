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
  if ((int)$request->ruta < 1 
  /*|| (int)$request->determinante < 1 
  || (int)$request->idcadena < 1
  || trim($request->formato) == ''
  || trim($request->Tienda) == '' 
  || trim($request->direccioncompleta) == ''
  || trim($request->idmunicipio) == '' 
  || trim($request->idestado) == ''
  || (int)$request->cluster < 1
  || trim($request->latitud) == ''
  || trim($request->longitud) == ''*/) {
    return http_response_code(400);
  }


  // Sanitize.
  $ruta = mysqli_real_escape_string($con,(int)$request->ruta);
  $determinante = mysqli_real_escape_string($con, (int)$request->determinante);
  $idcadena = mysqli_real_escape_string($con, (int)$request->idcadena);
  $idformato = mysqli_real_escape_string($con, trim($request->idformato));
  $Tienda = mysqli_real_escape_string($con, trim($request->Tienda));
  $direccioncompleta = mysqli_real_escape_string($con, trim($request->direccioncompleta));
  $idmunicipio = mysqli_real_escape_string($con, trim($request->idmunicipio));
  $idestado = mysqli_real_escape_string($con, trim($request->idestado));
  $cluster = mysqli_real_escape_string($con, (int)$request->cluster);
  $intensidad = mysqli_real_escape_string($con, (int)$request->intensidad);
  $uda = mysqli_real_escape_string($con, trim($request->uda));
  $fda = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc));
  $latitud = mysqli_real_escape_string($con, trim($request->latitud));
  $longitud = mysqli_real_escape_string($con, trim($request->longitud));

  // Create.
   $sql = "INSERT INTO `cat_rutas`(`idruta`,`ruta`,`determinante`,`idcadena`,`idformato`,`Tienda`,`direccioncompleta`,`idmunicipio`,`idestado`,`cluster`,`uda`,`fda`,`udc`,`fdc`,idEstatus,`latitud`,`longitud`,`intensidad`) VALUES (null,'{$ruta}','{$determinante}','{$idcadena}','{$idformato}','{$Tienda}','{$direccioncompleta}','{$idmunicipio}','{$idestado}','{$cluster}','{$uda}','{$fda}','{$udc}','{$fdc}',1,'{$latitud}','{$longitud}','{$intensidad}');";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'ruta' => $ruta,
      'determinante' => $determinante,
      'idcadena' => $idcadena,
      'idformato' => $idformato,
      'Tienda' => $Tienda,
      'direccioncompleta' => $direccioncompleta,
      'idmunicipio' => $idmunicipio,
      'idestado' => $idestado,
      'cluster' => $cluster,
      'uda' => $uda,
      'fda' => $fda,
      'udc' => $udc,
      'fdc' => $fdc,
      'latitud' => $latitud,
      'longitud' => $longitud,
      'idruta'    => mysqli_insert_id($con)
    ];
    echo json_encode($catcadena);
  }
  else
  {
    http_response_code(422);
  }
}
?>