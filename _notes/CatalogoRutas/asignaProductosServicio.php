<?php
require '../database.php';

date_default_timezone_set('America/Mexico_City');


  // Validate.
  $idruta = ($_GET['idruta'] !== null && (int)$_GET['idruta'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idruta']) : false;
  $idproducto = ($_GET['idproducto'] !== null && (int)$_GET['idproducto'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idproducto']) : false;  
  $resurtible = ($_GET['resurtible'] !== null && (int)$_GET['resurtible'] > -1)? mysqli_real_escape_string($con, (int)$_GET['resurtible']) : 1;    
  $uda = ($_GET['uda'] !== null && $_GET['uda'] !='')? mysqli_real_escape_string($con, $_GET['uda']) : false;

  if(!$idruta || !$idproducto || !$resurtible){
    return http_response_code(400);
  }


  // Sanitize.
  $fa = date('Y-m-d H:i');
  
  // Create.
  $sql = "INSERT INTO `producto_ruta`(`idproductoruta`,`idproducto`,`resurtible`,`idruta`,`uda`,`fda`,`udc`,`fdc`,`idestatus`) 
  VALUES (null,'{$idproducto}','{$resurtible}','{$idruta}','{$uda}','{$fa}','{$uda}','{$fa}','1');";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'idpromotor' => $idpromotor,
      'idruta' => $idruta,
      'fechaasignacion' => $fa,
      'idrutaasignada'    => mysqli_insert_id($con)      
    ];
    echo json_encode($catcadena);
  }
  else
  {
    http_response_code(422);
  }

?>