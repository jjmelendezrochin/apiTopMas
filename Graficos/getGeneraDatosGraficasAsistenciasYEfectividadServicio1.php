<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
// Extract the data.
    $request = json_decode($postdata);

    $condicion = "";
    $orden = "";
    $cat = [];

    if (trim($request->FechaInicial) === '' && trim($request->FechaFinal) === '') {
        return http_response_code(400);
    }

// Sanitize.
    $FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
    $FechaFinal = mysqli_real_escape_string($con, trim($request->FechaFinal));
    $idpromotor = mysqli_real_escape_string($con, (int) $request->idpromotor);
	$idEmpresa = mysqli_real_escape_string($con, (int) $request->idEmpresa);
	
	$FechaInicial = date_create($FechaInicial);
	$FechaInicial = date_format($FechaInicial,'Y-m-d');
	$FechaFinal = date_create($FechaFinal);
	$FechaFinal = date_format($FechaFinal,'Y-m-d');

//$usuario = [];
if(intval($idEmpresa) > 0){
  $condicion = "_" . $idEmpresa;
}

    $sqlImportaDatos = "Call proc_llenareportesgraficas();";
//echo($sql);
    //echo ('<br>');
  
   /* Ejecuta multiconsulta */
   if(mysqli_query($con,$sqlImportaDatos)) 
  {
	  $i = 0;
	  $cat[$i]["result"] = true;
      $cat[$i]["sql"] = $sqlImportaDatos;
  }else{
      $cat[$i]["sql"] = $sqlImportaDatos;	
  }
  echo json_encode($cat);
  
}
