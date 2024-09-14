<?php 
     require 'database.php';

// Sube imagen de promocion

$postdata["imagen"] = $_FILES["imagen"]; //Obtiene archivo

$postdata["ruta"] = $_POST["ruta"]; //Obtiene ruta donde se va a subir

$postdata["idpromocion"] = $_POST["idpromocion"]; //Obtiene idpromocion

if(sizeof($_FILES) == 1 && sizeof($_POST) == 2)
{
  // Extract the data.
  
  
  
  $request = json_encode($postdata);
  
  $request = json_decode($request);

if(sizeof($request->imagen) == 0 && trim($request->ruta) == "" && (int)$request->idpromocion > 0){
   return http_response_code(400);
}

            $idpromocion = $request->idpromocion;
            $archivo = $request->imagen->tmp_name; 
			$tamanio = $request->imagen->size;
			$tipo    = $request->imagen->type;
			$nombre  = $request->imagen->name;
			$ruta  = getcwd() . $request->ruta;// Construye ruta fisica donde se subira el archivo

    /*$arr = array("archivo" => $archivo,
             "Tamaño" => $tamanio,
			 "tipo" => $tipo,
			 "nombre" => $nombre,
			 "titulo" => $ruta);*/
	
	/*Si existe una version anterior del archivo lo borra*/
	if(file_exists($ruta)){
		unlink($ruta);
	}
	
	$ruta_host = "http://" . $servidor . "/TopMas" . $request->ruta; // Construye url
	
	/*Sube el archivo al servidor*/
	if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta)) {
		
		/*Inserta la url en la base de datos*/
    $sql = "update promocion set ruta = '" . $ruta_host .  "' where idpromocion = '{$idpromocion}';";       
  
  if(mysqli_query($con, $sql))
  {
      echo json_encode(array(
               "status" => "Archivo subido con exito",
			   "ruta" => $ruta_host
           ));
  }
  else
  {
    return http_response_code(422);
  }

   }else{
           echo json_encode(array(
               "status" => "Ocurrio un error al subir el archivo",
			   "ruta" => $ruta
         ));
       }
}
?>