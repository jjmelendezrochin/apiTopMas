<?php
    require_once('../database.php');
    // *****************************
    // 
	// Procedimiento utilizado para la carga de información de canjes
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
			$llave	         		= $_POST['llave'];
			$comentarios         	= $_POST['comentarios'];
            
			$arregloproductos       = $_POST['arregloproductos'];            
			
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
                            
            // *************************************
            // Verifica si es una carga extemporanea
            if(is_null($_POST['sindatos']))
            {
                $sindatos = '0';
            }
            else{
                $sindatos = $_POST['sindatos'];
            }


			// echo ("idpromotor " .$idpromotor);
            // *******************************
            // Verifica si trae datos la peticion
            if ($idpromotor==0  or $latitud==0 or $longitud==0 or $idoperacion==0 or $idruta==0 or trim($arregloproductos)=="")
            {
                    echo "Los datos no pudieron ser enviados, favor de intentar nuevamente ";
                    return;
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

			$idoperacion1 = 9;
			$idoperacion2 = 10;
			
			// Cambiando el nombre del archivo
			$path1 = "fotos/" . $idfoto1 . ".png";
            $path2 = "fotos/" . $idfoto2 . ".png";

            // *****************************
            // Ingresar al servidor correspondiente
            $servidor = $_SERVER['SERVER_NAME'];
            
            if ($servidor== "www.jjcorp.com.mx")
			{
                $actualpath1 = "http://www.jjcorp.com.mx/TopMkt/PhotoUpload/".$path1;
				$actualpath2 = "http://www.jjcorp.com.mx/TopMkt/PhotoUpload/".$path2;
            }
            else 
			{
                $actualpath1 = "http://www.topmas.mx/TopMas/PhotoUpload/".$path1;
				$actualpath2 = "http://www.topmas.mx/TopMas/PhotoUpload/".$path2;
            }       

            // *************************************
            // Arreglo de producto valor
            $arregloproductos = trim($arregloproductos); 
			$arregloupcvalor = explode("|", $arregloproductos);

			foreach ($arregloupcvalor as &$upcvalor) {
			    $upc_valor = explode("=", $upcvalor);
			    $upc = $upc_valor[0];
			    $valor = $upc_valor[1];

			    // *************************
			    // Consulta el idproducto con el upc que viene en el campo producto
			    $sql = "SELECT idproducto FROM cat_productos where upc = '" . $upc  . "';";
			    $Hora = date("d-m-Y H:i:s");
				$file = fopen("logs/log_canjes_" . date("dmY") . ".txt", "a");
				$texto = 'Consulta canjes';
				fwrite($file, "[" . $Hora . "] " . $texto . PHP_EOL);
				fwrite($file, "[" . $Hora . "] " . $sql . PHP_EOL);
				if($result = mysqli_query($con,$sql))
				{
				  $i = 0;
				  while($row = mysqli_fetch_assoc($result))
				  {
				    $idproducto = $row['idproducto'];
				    
				    // *************
				    // Inserción de valores en la tabla canjes_productos
				    $sqlIns = " Insert into canjes_productos(idruta, idpromotor, idproducto, fecha, cantidad, llave) " .
				    		"   values('" . $idruta . "','" . $idpromotor . "','" . $idproducto . "',CURRENT_TIMESTAMP(),'" . $valor . "','" . $llave . "')" ;
					mysqli_query($con,$sqlIns);
					$texto = 'Inserta canjes';
					fwrite($file, "[" . $Hora . "] " . PHP_EOL);
					fwrite($file, "[" . $Hora . "] ----------------------------" . PHP_EOL);
					fwrite($file, "[" . $Hora . "] " . $texto . PHP_EOL);
					fwrite($file, "[" . $Hora . "] " . $sqlIns . PHP_EOL);
					$i++;
				  }
				}	
				fclose($file);			
			}	
	    
                        
            // *************************
	        // Distancia mayor a un 1/4 de kilometro
	        
	        //if (intval($Distancia_m)>=2500000000000000000)
	        if (intval($Distancia_m)>=250)
			{
	             echo "El sitio desde donde tomo la foto rebasa la distancia permitida";
	        	 return;
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
			$sql3 = "INSERT INTO canjes(idruta, idpromotor, idfoto, idfoto1, fecha, llave, comentarios) VALUES ('$idruta', '$idpromotor', '$idfoto1', '$idfoto2', 
				CURRENT_TIMESTAMP,'" . $llave . "','" . $comentarios . "')";

			// *************************************
			// Función Log
			$Hora = date("d-m-Y H:i:s");
			$file = fopen("logs/log_canjes_" . date("dmY") . ".txt", "a");
			$texto = 'Datos canjes';
			fwrite($file, "[" . $Hora . "] *******************************" . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $texto . PHP_EOL);
			//fwrite($file, "[" . $Hora . "] " . $sql . PHP_EOL);
			fwrite($file, "[" . $Hora . "] idusuario: " . $idusuario . PHP_EOL);
			fwrite($file, "[" . $Hora . "] idruta:    " . $idruta . PHP_EOL);
			fwrite($file, "[" . $Hora . "] tienda:    " . $tienda . PHP_EOL);
			fwrite($file, "[" . $Hora . "] version:   " . $version_app . PHP_EOL);
			fwrite($file, "[" . $Hora . "] sindatos:  " . $sindatos . PHP_EOL);
			fwrite($file, "[" . $Hora . "] ruta1 :    " . $path1 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] ruta2 :    " . $path2 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] cta:       " . $cta . PHP_EOL);						
			fwrite($file, "[" . $Hora . "] ssql1:     " . $sql1 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] ssql2:     " . $sql2 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] ssql3:     " . $sql3 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] ----------------------------" . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . PHP_EOL);

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