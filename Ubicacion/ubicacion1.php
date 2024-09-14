<?php
 
 	date_default_timezone_set('America/Mexico_City');

	//if($_SERVER['REQUEST_METHOD']=='GET'){
		
		$idpromotor = $_GET['idpromotor'];
		$latitud = $_GET['latitud'];
		$longitud = $_GET['longitud'];
		
		require_once('../database.php');
        
        /*
		//Insertando los datos en la tabla de ubicacion
		$sql = "INSERT INTO ubicacion (idpromotor, latitud, longitud, FechaHora) 
				VALUES ('" . $idpromotor . "', '" . $latitud . "', '" . $longitud . "', CURRENT_TIMESTAMP);";
		
		if(mysqli_query($con,$sql)){
            $arreglo = array("respuesta"=>"1", "idpromotor"=>$idpromotor, "latitud"=>$latitud, "longitud"=>$longitud);
		}
		else{
            $arreglo = array("respuesta"=>"0","idpromotor"=>$idpromotor, "latitud"=>$latitud, "longitud"=>$longitud);
		}
        mysqli_close($con);
        echo json_encode($arreglo);
        */
    ?>