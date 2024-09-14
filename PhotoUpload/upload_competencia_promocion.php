<?php
    require_once('../database.php');
    // *****************************
    // 
	// Procedimiento utilizado para la carga de información de competencia promoción
    // Estableciendo zona horario de México
    date_default_timezone_set('America/Mexico_City');

	if($_SERVER['REQUEST_METHOD']=='POST'){		            
            $idpromotor     		= $_POST['idpromotor'];
			$latitud        		= $_POST['latitud'];
            $longitud       		= $_POST['longitud'];
			$idusuario      		= $_POST['idusuario'];
            $idoperacion    		= $_POST['idoperacion'];
            $idruta         		= $_POST['idruta'];
            $fechahora      		= $_POST['fechahora'];						
			$image          		= $_POST['image'];
			$image1         		= $_POST['image1'];
            
			$con_sin_participacion  = $_POST['con_sin_participacion'];            
			$por_participacion   	= $_POST['por_participacion'];
            $no_frentes      		= $_POST['no_frentes'];                        
            $por_descuento      	= $_POST['por_descuento'];
            $comentarios        	= $_POST['comentarios'];
			$idproducto		      	= $_POST['idproducto'];
			$precio			      	= $_POST['precio'];
						
            $version_app 			= $_POST['version_app'];
			$sindatos 				= $_POST['sindatos'];
			                        
            $idusuario = 0;
            // *************************************
            // Obtener los datos del usuario   
            $sql = " Select IDUSUARIO from cat_promotor cp where idpromotor = " . $idpromotor. ";";
						
            
            $res = mysqli_query($con,$sql);
            while($row = mysqli_fetch_array($res))
            {
                $idusuario = $row['IDUSUARIO'];
            }
						                   
            // *************************************
            // Obtener los datos del promotor   
            $sql = " SELECT TIENDA as Tienda FROM vw_tiendas WHERE IDRUTA  = " . $idruta . ";";

            
            $res = mysqli_query($con,$sql);
            while($row = mysqli_fetch_array($res))
            {
                $tienda = $row['Tienda'];
            }
		
            // *************************************
            // Obtener los datos del promotor
            $sql = "    Select concat(Fn_DistanciaEntreLatLongs(cr.latitud,cr.longitud, " . $latitud . "," . $longitud . "),' metros') AS Distancia_m "
                    . " from cat_rutas cr where cr.idruta = " . $idruta;
            
            $res = mysqli_query($con,$sql);
            while($row = mysqli_fetch_array($res))
            {
                $Distancia_m = $row['Distancia_m'];
            }
			
			// if (strtolower($idusuario) == 'prueba1')
            {
    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$Distancia_m');";
    			
    			mysqli_query($con,$ins);
			} 
           

            // *************************************
            // Verifica si es una carga extemporanea
            if(is_null($_POST['version_app']))
            {
                $version_app = '0:0';
            }
            else{
                $version_app = $_POST['version_app'];
            }
           
             // *************************
	        // Distancia mayor a un 1/4 de kilometro
	        
	        //if (intval($Distancia_m)>=2500000000000000000)
	        if (intval($Distancia_m)>=250
	                && $idpromotor!=2757)
			{
	             $error = "El sitio desde donde tomo la foto rebasa la distancia permitida";
	              {
	    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
	    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$error');";
	    			
	    			mysqli_query($con,$ins);
				 } 
	        	 return;
	        }
	        else{
	        	 $error = "No hay error de distancia o pomotor, la distancia es $Distancia_m, el promotor es $idpromotor ";
	              {
	    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
	    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$error');";
	    			
	    			mysqli_query($con,$ins);
				 }

	        }
            
            
            // *************************************
            // Verifica si es una carga extemporanea
            if(is_null($_POST['sindatos']))
            {
                $sindatos = '0';
            }
            else{
                $sindatos = $_POST['sindatos'];
            }
			

 			$error = "Datos " . $sindatos;
	              {
	    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
	    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$error');";
	    			
	    			mysqli_query($con,$ins);
				 }

	
			// echo ("idpromotor " .$idpromotor);
            // *******************************
            // Verifica si trae datos la peticion
            if ($idpromotor==0  or $latitud==0 or $longitud==0 or $idoperacion==0 or $idruta==0)
            {
                    // echo "Los datos no pudieron ser enviados, favor de intentar nuevamente ";
                    // return;

					$error0 ="idoperacion: " . $idoperacion . ", latitud: " . $latitud . ", longitud: " . $longitud ;
					$error ="Los datos no pudieron ser enviados, favor de intentar nuevamente " . $error0;
					// if (strtolower($idusuario) == 'prueba1')
                    {
    					$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    						   " values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$error');";
    			
    					mysqli_query($con,$ins);
                    }

            }
            else
            {
					$error0 ="idoperacion: " . $idoperacion . ", latitud: " . $latitud . ", longitud: " . $longitud ;
					$error ="Los datos si pudieron ser enviados, favor de intentar nuevamente " . $error0;
					// if (strtolower($idusuario) == 'prueba1')
                    {
    					$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    						   " values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$error');";
    			
    					mysqli_query($con,$ins);
                    }
            }

            // ******************************
            // Consulta id
            $sql ="SELECT Max(id)+1 as id1, Max(id)+2 as id2 FROM photos ORDER BY id ASC";
            $res = mysqli_query($con,$sql);
            $id = 0;
            while($row = mysqli_fetch_array($res)){
                $idfoto1 = $row['id1'];
				$idfoto2 = $row['id2'];
            }

			$idoperacion1 = 7;
			$idoperacion2 = 8;
			
			// Cambiando el nombre del archivo
			$path1 = "fotos/" . $idfoto1 . ".png";
            $path2 = "fotos/" . $idfoto2 . ".png";

            // *****************************                   
			{
                $actualpath1 = "http://www.topmas.mx/TopMas/PhotoUpload/".$path1;
				$actualpath2 = "http://www.topmas.mx/TopMas/PhotoUpload/".$path2;
            }     

			$error = "Actualpath1 es $actualpath1, Actualpath2 es $actualpath2 ";
	              {
	    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
	    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$error');";
	    			
	    			mysqli_query($con,$ins);
				 }

                         					
			// *************************************
			// si tiene fecha la solicitud la inserta en lugar de la del sistema
			// Insertando los datos    
			if($sindatos==0){
				$sql1 = "INSERT INTO photos (image, idpromotor, latitud, longitud, idusuario, idoperacion, FechaHora, idruta, sindatos, appver) 
						VALUES ('" . $actualpath1 . "', '$idpromotor', '$latitud', '$longitud', '$idusuario', '" . $idoperacion1 . "', '$fechahora', '$idruta', $sindatos, '$version_app')";
						
				$sql2 = "INSERT INTO photos (image, idpromotor, latitud, longitud, idusuario, idoperacion, FechaHora, idruta, sindatos, appver) 
						VALUES ('" . $actualpath2 . "', '$idpromotor', '$latitud', '$longitud', '$idusuario', '" . $idoperacion2 . "', '$fechahora', '$idruta', $sindatos, '$version_app')";
			}
			else{
				$sql1 = "INSERT INTO photos (image, idpromotor, latitud, longitud, idusuario, idoperacion, FechaHora, idruta, sindatos, appver) 
						VALUES ('" . $actualpath1 . "', '$idpromotor', '$latitud', '$longitud', '$idusuario', '" . $idoperacion1 . "', '$fechahora', '$idruta', $sindatos, '$version_app')";

				$sql2 = "INSERT INTO photos (image, idpromotor, latitud, longitud, idusuario, idoperacion, FechaHora, idruta, sindatos, appver) 
						VALUES ('" . $actualpath2 . "', '$idpromotor', '$latitud', '$longitud', '$idusuario', '" . $idoperacion2 . "', '$fechahora', '$idruta', $sindatos, '$version_app')";                    
			}
			
			// *************************************
			// Insercion en la tabla competencia_promocion
			$sql3 = "INSERT INTO competencia_promocion(idruta, idpromotor, idfoto, idfoto1, fecha, por_participacion, no_frentes, con_sin_participacion, por_descuento, comentarios, idproducto, precio) 
					VALUES ('$idruta', '$idpromotor', '$idfoto1', '$idfoto2', CURRENT_TIMESTAMP, '$por_participacion', '$no_frentes', '$con_sin_participacion', '0', '$comentarios', '$idproducto', '$precio')";

/*
				$error = "sql1 " . $sql1;
	              {
	    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
	    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$error');";
	    			
	    			mysqli_query($con,$ins);
				 }
				 $error = "sql2 " . $sql2;
	              {
	    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
	    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$error');";
	    			
	    			mysqli_query($con,$ins);
				 }
				 $error = "sql3 " . $sql2;
	              {
	    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
	    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$error');";
	    			
	    			mysqli_query($con,$ins);
				 }
*/


			// *************************************
			// Función Log
			$Hora = date("d-m-Y H:i:s");
			$file = fopen("logs/log_competencia_promocion_" . date("dmY") . ".txt", "a");
			$texto = 'Consulta competencia_promocion';
			fwrite($file, "[" . $Hora . "] " . $texto . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $sql . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $idusuario . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $idruta . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $tienda . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $version_app . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $sindatos . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $path1 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $path2 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $cta . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $sindatos . PHP_EOL);						
			fwrite($file, "[" . $Hora . "] " . $sql1 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $sql2 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $sql3 . PHP_EOL);
			if(mysqli_query($con,$sql1)=== TRUE)
			{
				$texto = "Primer consulta ejecutada";
				fwrite($file, "[" . $Hora . "] " . $texto . PHP_EOL);
				if(mysqli_query($con,$sql2)=== TRUE)
				{
					$texto = "Segunda consulta ejecutada";
					fwrite($file, "[" . $Hora . "] " . $texto . PHP_EOL);
					if(mysqli_query($con,$sql3)=== TRUE)
					{
						$texto = "Tercera consulta ejecutada";
						fwrite($file, "[" . $Hora . "] " . $texto . PHP_EOL);
			
						// Subiendo archivos			
						file_put_contents($path1,base64_decode($image));
						file_put_contents($path2,base64_decode($image1)); 
						echo "Imagen cargada correctamente";
					}
				}
			}
			fclose($file);
			// *************************************
						
            mysqli_close($con);
	}
    else{
		echo "Error";
	}
 ?>