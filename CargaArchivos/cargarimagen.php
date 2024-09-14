<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once '../PHPExcel/Classes/PHPExcel.php';
require '../database.php';

if(isset($_FILES['imagenPropia'])){

		$imagen_tipo = $_FILES['imagenPropia']['type'];
		$imagen_nombre = $_FILES['imagenPropia']['name'];
		$fileNameCmps = explode(".", $imagen_nombre);
	    $fileExtension = strtolower(end($fileNameCmps));
		
		$nombrearchivo = md5(time() . $imagen_nombre);
		$newFileName = $nombrearchivo . '.' . $fileExtension;
		$archivo = "./Datos/".$newFileName; 

		// Trunca la tabla temporal
		$sql = "Truncate table tmp_importa_excel;";
		mysqli_query($con,$sql);
		if( $imagen_tipo == "application/vnd.ms-excel" || 
			$imagen_tipo == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){

			if(move_uploaded_file($_FILES['imagenPropia']['tmp_name'], $archivo))
			{
				// *********************
				// Importación de datos								
				$inputFileType = PHPExcel_IOFactory::identify($archivo);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($archivo);
				$sheet = $objPHPExcel->getSheet(0); 
				$highestRow = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();
				
				for ($row = 2; $row <= $highestRow; $row++){
					 				
					$determinante = $sheet->getCell("A".$row)->getValue();
					$idformato	= $sheet->getCell("B".$row)->getValue();
					$formato	= $sheet->getCell("C".$row)->getValue();
					$Tienda		= $sheet->getCell("D".$row)->getValue();
					$direccioncompleta	= $sheet->getCell("E".$row)->getValue();
					$municipio	= $sheet->getCell("F".$row)->getValue();
					$estado		= $sheet->getCell("G".$row)->getValue();
					$cluster	= $sheet->getCell("H".$row)->getValue();
					$latitud	= $sheet->getCell("I".$row)->getValue();
					$longitud	= $sheet->getCell("J".$row)->getValue();
					$intensidad	= $sheet->getCell("K".$row)->getValue();
					$Valor 		= $sheet->getCell("L".$row)->getValue();
					
					$sql = "INSERT INTO tmp_importa_excel
					(determinante, idformato, formato, Tienda, direccioncompleta, municipio, 
					estado, cluster, latitud, longitud, intensidad, Valor) 
					VALUES 
					('$determinante', '$idformato', '$formato', '$Tienda', '$direccioncompleta', '$municipio', 
					'$estado', '$cluster', '$latitud', '$longitud', '$intensidad', '$Valor');";
					mysqli_query($con,$sql);

					/**/
					$num++;
				}
				
				// **************************
				// Ejecución
				$sql = "Call Proc_ImportaTiendas();";
				mysqli_query($con,$sql);
				// **************************				
				
				$data = array(
					'status' => 'success',
					'code' => 200,
					'msj' => 'Imagen subida'
				);
				$format = (object) $data;
				$json = json_encode($format); 
				echo $json; 

			}else{

				$data = array(
					'status' => 'error',
					'code' => 400,
					'msj' => 'Error al mover imagen al servidor'
				);
				$format = (object) $data;
				$json = json_encode($format); 
				echo $json; 

			}

		}else{

			$data = array(
				'status' => 'error',
				'code' => 500,
				'msj' => 'Formato no soportado'
			);
			$format = (object) $data;
			$json = json_encode($format); 
			echo $json; 

		}

	}else{

		$data = array(
			'status' => 'error',
			'code' => 400,
			'msj' => 'No se recibio ninguna imagen'
		);
		$format = (object) $data;
		$json = json_encode($format); 
		echo $json; 
	}	
?>