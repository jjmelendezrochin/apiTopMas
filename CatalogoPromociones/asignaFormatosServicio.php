<?php
require '../database.php';

date_default_timezone_set('America/Mexico_City');


  // Validate.
  $idpromocion = ($_GET['idpromocion'] !== null && (int)$_GET['idpromocion'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idpromocion']) : false;
    $idempresa = ($_GET['idempresa'] !== null && (int)$_GET['idempresa'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idempresa']) : false;

  $idcadena = ($_GET['idcadena'] !== null && (int)$_GET['idcadena'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idcadena']) : false;  
  $idformato = ($_GET['idformato'] !== null && (int)$_GET['idformato'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idformato']) : false;    
  $uda = ($_GET['uda'] !== null && $_GET['uda'] !='')? mysqli_real_escape_string($con, $_GET['uda']) : false;

  if(!$idpromocion || !$idempresa || !$idcadena || !$idformato){
    return http_response_code(400);
  }


  // Sanitize.
  $fa = date('Y-m-d H:i');
  
  // Create.
  $sql = "INSERT INTO `promocion_formato`(`idpromocionformato`,`idpromocion`,`idempresa`,`idcadena`,`idformato`,`uda`,`fda`,`udc`,`fdc`,`idestatus`) 
  VALUES (null,'{$idpromocion}','{$idempresa}','{$idcadena}','{$idformato}','{$uda}','{$fa}','{$uda}','{$fa}','1');";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'idpromocion' => $idpromocion,
      'idempresa' => $idempresa,
	  'idcadena' => $idcadena,
	  'idformato' => $idformato,
      'uda' => $uda,
      'fda' => $fa,
	  'udc' => $uda,
      'fdc' => $fa,	  
      'idpromocionformato'    => mysqli_insert_id($con)      
    ];
    echo json_encode($catcadena);
  }
  else
  {
	  echo json_encode(["sql" => $sql]);
    //http_response_code(422);
  }

?>