<?php
/**
 * Regresa el usuario.
 */
require '../database.php';
//echo 'inicio';
date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
    // Extract the data.
    $request = json_decode($postdata);
    //echo 'si';
    
    $condicion = "";
    $cat = [];

    // Sanitize Parameters
    $FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
    $FechaFinal = mysqli_real_escape_string($con, trim($request->FechaFinal));
    $idpromotor = mysqli_real_escape_string($con, (int)$request->idpromotor);
    $idcadena = mysqli_real_escape_string($con, (int)$request->idcadena);
   
    // ****************************************
    // Ejecución de procedimiento alamacenado
    $sql .= " Call Proc_Reporte_Completo('" . $FechaInicial . "', '" . $FechaFinal . "', " . $idpromotor . ", " . $idcadena . ");";
    $bitacora = "Insert into bitacora (instruccion) values ('" . str_replace("'", "|", $sql) . "')";
    mysqli_query($con,$bitacora);       // Inserción en bitacora
    if($result = mysqli_query($con,$sql))
    {
        $i = 0;
        while($row = mysqli_fetch_assoc($result))
        {
          $cat[$i]['Nombre']        =  $row['Nombre'];
          $cat[$i]['Tienda']        =  $row['Tienda'];
          $cat[$i]['Cadena']     =  $row['cadena'];
          $cat[$i]['Fecha']     =  $row['Fecha'];
          $cat[$i]['Formato']     =  $row['formato'];
          $cat[$i]['Objetivo']    =  $row['objetivo'];
          $cat[$i]['Checkin']    =  $row['checkin'];
          $cat[$i]['Checkout']    =  $row['checkout'];
          $i++;
        }
        echo json_encode($cat);
    }
    else
    {
        $cat = ["sql" => $sql];
        echo json_encode($cat);
        //http_response_code(404);
    }
}
else{
 echo "O";
}
?>