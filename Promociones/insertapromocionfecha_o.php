<?php
require '../database.php';
// *****************************
//
// Estableciendo zona horario de México
date_default_timezone_set('America/Mexico_City');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Sanitize.
    $idpromocion = $_POST['idpromocion'];
    $idpromotor = $_POST['idPromotor'];
    $idempresa = $_POST['idempresa'];
    $idruta = $_POST['idruta'];    
    $aplica = $_POST['aplica'];
    
    // ****************************
    // Antes de insertar debe verificar si ya existe un reegisro de ese producto en esa ruta en esa fecha
    // Si es asi entonces actualiza si no inserta
    $consulta = " Select count(*) as cta from promociones_tiendas " .
                " where idpromocion = '" . $idpromocion . "' " .
                " and idpromotor = '" . $idpromotor . "' " .
                " and idempresa = '" . $idempresa . "' " .            
                " and idruta = '" . $idruta . "' " .
                " and Cast(fecha as date) = Cast(CURDATE() as date);";
      if ($result = mysqli_query($con, $consulta)) {
        while ($row = mysqli_fetch_assoc($result)) {
          $cta = $row['cta'];
        }
    }

    if($cta==0)
    {
      // Inserción.
      $sql = "  INSERT INTO `promociones_tiendas`(`idpromocion`,`idpromotor`,`idempresa`,`idruta`,`fecha`,`aplica`)" .
        " VALUES ('{$idpromocion}','{$idpromotor}','{$idempresa}','{$idruta}',CURRENT_TIMESTAMP,'{$aplica}');";
    }
    else{
      // Actualización solo si se cumple la condición
      $sql =   " UPDATE promociones_tiendas SET idpromocion = '{$idpromocion}'," .
               " idpromotor = '{$idpromotor}', idempresa = '{$idempresa}'," .
               " idempresa = '{$idempresa}', idruta = '{$idruta}', " . 
               " fecha = CURRENT_TIMESTAMP, aplica = '{$aplica}' " .
               " where idpromocion = '" . $idpromocion . "' " .
               " and idpromotor = '" . $idpromotor . "' " .
               " and idempresa = '" . $idempresa . "' " .            
               " and idruta = '" . $idruta . "' " .
               " and Cast(fecha as date) = Cast(CURDATE() as date);";
    }
    
    $bitacora = "Insert into bitacora (instruccion) values ('" . str_replace("'", "|", $sql) . "')";
    mysqli_query($con,$bitacora);

    // ****************************


    // Inserción en tabla de productos
    if (mysqli_query($con, $sql)) {
        http_response_code(201);
        // ***************************
        // Se obtiene el id insertado
        $consulta = "Select max(idpromotor) as idpromotor from promociones_tiendas;";
        if ($result = mysqli_query($con, $consulta)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $respuesta = $row['idpromotor'];
            }
        }

        // ***************************
        echo $respuesta; // Envìa el resultado
    } else {
      echo "0"
    }
}
