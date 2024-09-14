<?php
require '../database.php';
// *****************************
//
// Estableciendo zona horario de México
date_default_timezone_set('America/Mexico_City');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Sanitize.
    $idproducto     = $_POST['idproducto'];
    $idruta         = $_POST['idruta'];
    $idpromotor     = $_POST['idPromotor'];
    $precio         = $_POST['precio'];    
    $invinicial     = $_POST['invinicial'];
    $invfinal       = $_POST['invfinal'];
    $idobs          = $_POST['idobs'];
    $fda            = date('Y-m-d H:i');

    // ****************************
    // Antes de insertar debe verificar si ya existe un reegisro de ese producto en esa ruta en esa fecha
    // Si es asi entonces actualiza si no inserta
    $consulta = "Select count(*) as cta from vw_producto_ruta_fecha where idproducto = '" . $idproducto . "' " .
                " and idruta = '" . $idruta . "'  and Cast(fda as date) = Cast(CURDATE() as date);";
      if ($result = mysqli_query($con, $consulta)) {
        while ($row = mysqli_fetch_assoc($result)) {
          $cta = $row['cta'];
        }
    }

//    if($cta==0)
//    {
      // Inserción.
      $sql = "  INSERT INTO `producto_ruta_fecha`(`idproducto`,`idruta`,`idpromotor`,`precio`,`fda`,`idObs`,`invinicial`,`invfinal`)" .
        " VALUES ('{$idproducto}','{$idruta}','{$idpromotor}','{$precio}','{$fda}','{$idobs}','{$invinicial}','{$invfinal}');";
//    }
//    else{
//      // Actualización solo si se cumple la condición
//      $sql =  "  UPDATE producto_ruta_fecha SET precio = '{$precio}', idObs = '{$idobs}', invinicial = '{$invinicial}', invfinal = '{$invfinal}'" .
//              "  Where idproducto = '{$idproducto}' and idruta = '{$idruta}' and Cast(fda as date) = Cast(CURDATE() as date);";
//    } 
    // ****************************


    // Inserción en tabla de productos
    if (mysqli_query($con, $sql)) {
        http_response_code(201);
        // ***************************
        // Se obtiene el id insertado
        $consulta = "Select max(idproductoruta) as maximo from producto_ruta_fecha;";
        if ($result = mysqli_query($con, $consulta)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $maximo = $row['maximo'];
            }
        }

        // ***************************
        $resultado = "1";
        echo json_encode(array("RESPUESTA" => $maximo)); // Envìa el resultado
    } else {
    }
}
