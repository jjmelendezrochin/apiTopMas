<?php 
     require 'database.php';

// Get the posted data.

$postdata["ruta"] = $_GET["ruta"]; //Obtiene ruta donde se va a subir

$postdata["idpromocion"] = $_GET["idpromocion"]; //Obtiene idproducto

if(isset($postdata["idpromocion"]))
{
  // Extract the data.
  
  
  
  $request = json_encode($postdata);
  
  $request = json_decode($request);

if(trim($request->ruta) == "" && (int)$request->idpromocion > 0){
   return http_response_code(400);
}

            $idpromocion = $request->idpromocion;
			$arr = explode("/",$request->ruta);
			$ruta_relativa = "/" . $arr[sizeof($arr)-2] . "/" . $arr[sizeof($arr)-1];
            $ruta  = getcwd() . $ruta_relativa;// Construye ruta fisica donde se subira el archivo

    /*$arr = array("archivo" => $archivo,
             "Tamaño" => $tamanio
			 "tipo" => $tipo,
			 "nombre" => $nombre,
			 "titulo" => $ruta);*/
	
	/*Borra el archivo*/
	if(file_exists($ruta)){
		unlink($ruta);
	}
	
    $sql = "update promocion set ruta = '' where idpromocion = '{$idpromocion}';";       
  
  	$ruta_host = "http://" . $servidor . "/TopMas" . $ruta_relativa; // Construye url
  
  if(mysqli_query($con, $sql))
  {
      echo json_encode(array(
               "status" => "Archivo borrado con exito",
			   "ruta" => $ruta_host
           ));
  }
  else
  {
    return http_response_code(422);
  }
}
?>