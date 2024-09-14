<?php

/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    $condicion = "";
    $estancia_regs = [];
    $cadenas_regs = [];
    $estancia_cadenas = [];
// Obteniendo la empresa
    /* $idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


      if(!$idEmpresa)
      {
      return http_response_code(400);
      } */

    if (trim($request->FechaInicial) === '' && trim($request->FechaFinal) === '') {
        //return http_response_code(400);
    }

// Sanitize.
    $FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
    $FechaFinal = mysqli_real_escape_string($con, trim($request->FechaFinal));
    $idTienda = mysqli_real_escape_string($con, (int) $request->idTienda);
    $idpromotor = mysqli_real_escape_string($con, (int) $request->idpromotor);
    $idcadena = mysqli_real_escape_string($con, (int) $request->idcadena);
    $idempresa = mysqli_real_escape_string($con, (int) $request->idempresa);	

//$fecha=date('Y/m/d');
//$usuario = [];
    $sql_estancia = "call proc_reporteestanciascadenas(cast('{$FechaInicial}' as date),cast('{$FechaFinal}' as date),{$idpromotor},{$idTienda},{$idempresa});";

    $Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
    fwrite($file, "[" . $Hora . "] " . $sql_estancia . PHP_EOL);
    fclose($file);

    /* call proc_reportecadenas(cast('{$FechaInicial}' as date),cast('{$FechaFinal}' as date),{$idcadena}); */
//-- between '{$FechaInicial}' and '{$FechaFinal}' $condicion -- 
//--// echo($sql);
//--// echo ('<br>');
    if ($result = mysqli_query($con, $sql_estancia)) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $estancia_regs[$i]['Fecha'] = $row['Fecha'];
            $estancia_regs[$i]['Promotor'] = $row['Promotor'];
            $estancia_regs[$i]['cadena'] = $row['cadena'];
            $estancia_regs[$i]['formato'] = $row['formato'];
            $estancia_regs[$i]['Tienda'] = $row['Tienda'];
            $estancia_regs[$i]['HoraCheckin'] = $row['HoraCheckin'];
            $estancia_regs[$i]['DistanciaCheckin'] = $row['DistanciaCheckin'];
            $estancia_regs[$i]['HoraCheckout'] = $row['HoraCheckout'];
            $estancia_regs[$i]['DistanciaCheckout'] = $row['DistanciaCheckout'];
            $estancia_regs[$i]['Estancia'] = $row['Estancia'];
            $estancia_regs[$i]['objetivo'] = $row['objetivo'];
            $estancia_regs[$i]['checkin'] = $row['checkin'];
            $estancia_regs[$i]['checkout'] = $row['checkout'];
            $i++;
        }
    } else {
        http_response_code(404);
    }

    /* mysqli_next_result($con);

      if($result1 = mysqli_query($con,$sql_cadenas)){
      $i = 0;
      while($row = mysqli_fetch_assoc($result1))
      {
      $cadenas_regs[$i]['Fecha'] = $row['Fecha'];
      $cadenas_regs[$i]['Promotor'] = $row['Promotor'];
      $cadenas_regs[$i]['formato'] = $row['formato'];
      $cadenas_regs[$i]['Tienda'] = $row['Tienda'];
      $cadenas_regs[$i]['Actividad'] = $row['Actividad'];
      $cadenas_regs[$i]['FechaHora'] = $row['FechaHora'];
      $cadenas_regs[$i]['Distancia_m'] = $row['Distancia_m'];
      $cadenas_regs[$i]['Cadena'] = $row['nombrecorto'];
      $i++;
      }
      $estancia_cadenas = ['Estancia' => $estancia_regs, 'Cadenas' => $cadenas_regs];
      echo json_encode($estancia_cadenas);
      }
      else{
      echo json_encode(array(['sql'=>mysqli_error($con)]));
      } */
    $estancia_cadenas = ['Estancia' => $estancia_regs, 'Cadenas' => $cadenas_regs];
    echo json_encode($estancia_cadenas);
}
?>