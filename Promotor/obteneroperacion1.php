<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

// Extract, usuario y contraseña
$idruta = ($_GET['idruta'] !== null && strlen($_GET['idruta']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idruta']) : false;
$idpromotor = ($_GET['idpromotor'] !== null && strlen($_GET['idpromotor']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idpromotor']) : false;
$idoperacion = ($_GET['idoperacion'] !== null && strlen($_GET['idoperacion']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idoperacion']) : false;
$idempresa = ($_GET['idempresa'] !== null && strlen($_GET['idempresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idempresa']) : false;
$servidor = $_SERVER['SERVER_NAME'];

 

if(!$idruta || !$idpromotor || !$idoperacion)
{
  return http_response_code(400);
}
 $Hora = date("d-m-Y H:i:s");
  $file = fopen("log_" . date("dmY") . ".txt", "a");
  fwrite($file, "[" . $Hora . "] " . $idruta . PHP_EOL);
  fwrite($file, "[" . $Hora . "] " . $idpromotor . PHP_EOL);
  fwrite($file, "[" . $Hora . "] " . $idoperacion . PHP_EOL);
  fwrite($file, "[" . $Hora . "] " . $idempresa . PHP_EOL);
  fwrite($file, "[" . $Hora . "] " . $servidor . PHP_EOL);
  fclose($file);
// ****************************
// Inicializando respuesta
// Valida si es checkin y ya habia realizado esta operaciòn
$resultado['RESPUESTA']=0;
if($idoperacion==1)
 {
    if ($servidor== "www.jjcorp.com.mx"){
    $sql = "Select COUNT(*) as Cta from photos where idruta = " .$idruta ." and idPromotor = " .$idpromotor ." and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 and idoperacion=1 and cast(FechaHora as date) = cast(DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -6 hour) AS DATE)";
    }
    else {
    $sql = "Select COUNT(*) as Cta from photos where idruta = " .$idruta ." and idPromotor = " .$idpromotor ." and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 and idoperacion=1 and cast(FechaHora as date) = cast(CURRENT_TIMESTAMP AS DATE)";
    }

  $Hora = date("d-m-Y H:i:s");
  $file = fopen("log_" . date("dmY") . ".txt", "a");
  fwrite($file, "[" . $Hora . "] " . $sql . PHP_EOL);
  fclose($file);

    
//    echo $sql . "<br>";
    if($result = mysqli_query($con,$sql))
    {
      $i = 0;
        while($row = mysqli_fetch_assoc($result))
        {
          if ($row['Cta']==1){
            $resultado['RESPUESTA']  = 1; // Usted ya realizó esta operación
            echo json_encode($resultado);
            return;
          }
        }
    }
}
// Valida si es checkout y ya habia realizado esta operaciòn
if($idoperacion==2)
 {
    if ($servidor== "www.jjcorp.com.mx"){
    $sql = "Select COUNT(*) as Cta from photos where idruta = " .$idruta ." and idPromotor = " .$idpromotor ." and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 and idoperacion=2 and cast(FechaHora as date) = cast(DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -6 hour) AS DATE)";
    }
    else {
    $sql = "Select COUNT(*) as Cta from photos where idruta = " .$idruta ." and idPromotor = " .$idpromotor ." and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 and idoperacion=2 and cast(FechaHora as date) = cast(CURRENT_TIMESTAMP AS DATE)";
    }
//    echo $sql . "<br>";
    if($result = mysqli_query($con,$sql))
    {
      $i = 0;
        while($row = mysqli_fetch_assoc($result))
        {
          if ($row['Cta']==1){
            $resultado['RESPUESTA']  = 1; // Usted ya realizó esta operación
            echo json_encode($resultado);
            return;
          }
        }
    }
}

// ****************************
//Verificando el maxid operacion para saber si hicieron checkin sin chechout en otra tienda no permita nuevo checkin
if ($servidor== "www.jjcorp.com.mx"){
$sql1 = "Select max(idoperacion) as MaxidOperacion from photos where idPromotor = " .$idpromotor . " and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 and idOperacion<3 and cast(FechaHora as date) = cast(DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -6 hour) AS DATE)";
}
else {
$sql1 = "Select max(idoperacion) as MaxidOperacion from photos where idPromotor = " .$idpromotor . " and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 and idOperacion<3 and cast(FechaHora as date) = cast(CURRENT_TIMESTAMP AS DATE)";
}


$Hora = date("d-m-Y H:i:s");
$file = fopen("log_" . date("dmY") . ".txt", "a");
fwrite($file, "[" . $Hora . "] " . $sql1 . PHP_EOL);
fclose($file);


//echo($sql1);
// echo ("<p>");
if($result1 = mysqli_query($con,$sql1))
{
    $i = 0;
    while($row1 = mysqli_fetch_assoc($result1))
    {
      $maxidoperacion  = $row1['MaxidOperacion'];
    }
    // No se puede insertar un checkin si no se tiene el checkout
    if($maxidoperacion==1 and $idoperacion==1 and $resultado['RESPUESTA']==0){
        $resultado['RESPUESTA']  = 2;
        // Debe de realizar Checkout en la tienda anterior antes de realizar Checkin en esta
        echo json_encode($resultado);
        return;
    }
}

// ****************************
// Si el valor de la operacion es 2 entonces debe de verificar si en esa ruta ya hay 1,  
// si lo hay lo permite, si no no lo permite
if ($idoperacion==2)
{   
	$ctasihay1=0;
    if ($servidor== "www.jjcorp.com.mx"){
    $sql2 = "Select COUNT(*) as Cta from photos where idruta = " .$idruta ." and idPromotor = " .$idpromotor ." and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 and idoperacion=1 and cast(FechaHora as date) = cast(DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -6 hour) AS DATE)";
    }
    else {
    $sql2 = "Select COUNT(*) as Cta from photos where idruta = " .$idruta ." and idPromotor = " .$idpromotor ." and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 and idoperacion=1 and cast(FechaHora as date) = cast(CURRENT_TIMESTAMP AS DATE)";
    }
    
    if($result2 = mysqli_query($con,$sql2))
    {
      $i = 0;
      while($row2 = mysqli_fetch_assoc($result2))
      {
        $ctasihay1  = $row2['Cta'];
      }
    }
    if ($ctasihay1==0){
        // Como hay cero entonces no se debe de poder insertar checkout sin checkin
      $resultado['RESPUESTA']  = 3;
      // No se puede realizar Checkout sin haber realizado Checkin en una tienda
      echo json_encode($resultado);
      return;
    }
}

// ****************************
// Si el valor de la operacion es 3 o 4 entonces debe de verificar si en esa ruta ya hay 1,  
// si lo hay lo permite, si no no lo permite
// ******** ESTA VALIDACION ESTA CONFLICTUANDO EL PROCESO POR ESE MOTIVO YA LA QUITE 28/10/2021 ********
/*
if ($idoperacion==3 or $idoperacion==4){
	$ctasihay1=0;
    if ($servidor== "www.jjcorp.com.mx"){
    $sql3 = "Select COUNT(*) as Cta from photos 
      where idruta = " .$idruta ." and idPromotor = " .$idpromotor ." and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 AND idoperacion=1 
      and cast(FechaHora as date) = cast(DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -6 hour) AS DATE)";
    }
    else {
    $sql3 = "Select COUNT(*) as Cta from photos 
      where idruta = " .$idruta ." and idPromotor = " .$idpromotor ." and (Select cp.idempresa from cat_promotor cp where cp.idpromotor = photos.idPromotor and cp.idempresa = '" . $idempresa . "') > 0 AND idoperacion=1 
      and cast(FechaHora as date) = cast(CURRENT_TIMESTAMP AS DATE)";    
    }
    if($result3 = mysqli_query($con,$sql3))
    {
      $i = 0;
      while($row3 = mysqli_fetch_assoc($result3))
      {
        $ctasihay1  = $row3['Cta'];
      }
    }
    if ($ctasihay1==0){
       // Como hay cero entonces no se debe de poder insertar checkout sin checkin
      $resultado['RESPUESTA']  = 4;
      // No se puede realizar Inventario Entrada/Salida sin haber realizado Checkin en esta tienda
      echo json_encode($resultado);
      return;
    }
}
*/
echo json_encode($resultado);

?>