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
  if ((int)$request->idproducto < 1 ) {
    return http_response_code(400);
  }

  // Sanitize.

  $idproducto = mysqli_real_escape_string($con, (int)$request->idproducto);
  $upc =  mysqli_real_escape_string($con, (string)$request->upc);                       
  $descripcion =  mysqli_real_escape_string($con, (string)$request->descripcion);                       
  $descripcion1 =  mysqli_real_escape_string($con, (string)$request->descripcion1);             
  $cantidad_caja =  mysqli_real_escape_string($con, (string)$request->cantidad_caja);             
  $cantidad_kgs =  mysqli_real_escape_string($con, (string)$request->cantidad_kgs);             
  $idempresa =  mysqli_real_escape_string($con, (string)$request->idempresa);       
  $categoria1 =  mysqli_real_escape_string($con, (string)$request->categoria1);             
  $categoria2 =  mysqli_real_escape_string($con, (string)$request->categoria2);           
  // $idcadena =  mysqli_real_escape_string($con, (string)$request->idcadena1); 
  // $precio =  mysqli_real_escape_string($con, (string)$request->precio);
  $udc =  mysqli_real_escape_string($con, (string)$request->udc);                   
  $fdc =  date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc)); 

  // Update.

    $sqlConsulta ="Select ruta from cat_productos WHERE idproducto = '{$idproducto}';";
	/*Ejcuta query de la consulta para traer la ruta vieja de la imagen*/
	if($result1 = mysqli_query($con,$sqlConsulta))
	{
		$ruta_anterior = ''; 
	  while($row = mysqli_fetch_assoc($result1))
	  {
		$ruta_anterior = $row['ruta'];
	  }
	}

  $sql = "UPDATE `cat_productos` SET `upc` = '$upc',
  `descripcion` = '$descripcion',
  `descripcion1` = '$descripcion1',
  `cantidad_caja` = '$cantidad_caja',
  `cantidad_kgs` = '$cantidad_kgs',
  `idempresa` = '$idempresa',
  `categoria1` = '$categoria1',
  `categoria2` = '$categoria2',
  -- `idcadena` = '$idcadena',
  -- `precio` = '$precio',
  `udc` = '$udc',
  `fdc` = '$fdc',
  `ruta` = 'http://" . $servidor . "/TopMas/ImagenesProductos/" . $idproducto . '_' . $upc . ".png'
   WHERE `idproducto` = '{$idproducto}' LIMIT 1";
  // echo $sql;
  
  // *********************
  // Renombrar archivo
   
  $archivo_origen =  str_replace("http://" . $servidor . "/TopMas/ImagenesProductos/", "", $ruta_anterior);
  $archivo_destino= $idproducto . '_' . $upc . '.png';
  
  $ruta_fisica = $_SERVER['DOCUMENT_ROOT'].'/TopMas/ImagenesProductos/';
  
  rename($ruta_fisica . "/" .  $archivo_origen, $ruta_fisica . "/" . $archivo_destino);
  
	/*Genera log */
	$Hora = date("d-m-Y H:i:s");
	$file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file,"[". $Hora . "] " .'sql: '. $sqlConsulta .  ' ruta_anterior: ['.$ruta_anterior.'] origen: ['.$archivo_origen.'] destino: [' .$archivo_destino . ']' . PHP_EOL);
	fclose($file);
	

  $inst = str_replace("`", "|", $sql);
    	
  // **************************** 
  // Insercion en bitacora
  $bitacora = " Insert into bitacora(instruccion, fechahora)  values ('" .$inst. "',CURRENT_TIMESTAMP());";
  mysqli_query($con,$bitacora);


  if(mysqli_query($con, $sql))
  {
    $sql1 = 'Call Proc_PrecioProductoEmpresa (' . $idproducto . ')';
	mysqli_query($con,$sql1);
    http_response_code(204);
  }
  else
  {
    return http_response_code(422);
  }  
}
?>