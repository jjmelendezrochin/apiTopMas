<?php
/**
 * Regresa el usuario.
 */
require '../database.php';
require '../config.php';

$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$idempresa=(isset($_GET['idempresa']))? mysqli_real_escape_string($con, (int)$_GET['idempresa']) : 0;
$idcadena=(isset($_GET['idcadena']))? mysqli_real_escape_string($con, (int)$_GET['idcadena']) : 0;
$PRODUCTO=(isset($_GET['PRODUCTO']))? mysqli_real_escape_string($con, (string)$_GET['PRODUCTO']) : false;
$CATEGORIA=(isset($_GET['CATEGORIA']))? mysqli_real_escape_string($con, (string)$_GET['CATEGORIA']) : false;
$UPC=(isset($_GET['UPC']))? mysqli_real_escape_string($con, (string)$_GET['UPC']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;


// Condicion de ordenamiento
switch (intval($ord)){
  case 0:
      $orden = " order by p.descripcion asc ";
      break;
  case 1:
      $orden = " order by p.descripcion asc ";
      break;
  case 2:
      $orden = " order by p.upc desc";
      break;
  case 3:
       $orden = " order by p.descripcion asc";
      break;
  case 4:
      $orden = " order by p.descripcion desc";
      break;
  case 5:
      $orden = " order by p.categoria1 asc";
      break;  
  case 6:
      $orden = " order by p.categoria1 desc";
      break;
  case 7:
      $orden = " order by p.categoria2 asc";
      break;  
  case 8:
      $orden = " order by p.categoria2 desc";
      break;
  case 9:
      $orden = " order by c.nombrecorto asc";
      break;  
  case 10:
      $orden = " order by c.nombrecorto desc";
      break;
}

 /*if(intval($idcadena) > 0){
  $condicion .= " and p.idcadena='{$idcadena}'"; 
 }*/
 
 if($PRODUCTO != ""){
  $condicion .= " and (p.descripcion like '%" . $PRODUCTO . "%' or p.descripcion1 like '%" . $PRODUCTO . "%')";
 }

 if($CATEGORIA != ""){
  $condicion .= " and (p.categoria1 like '%" . $CATEGORIA . "%' or p.categoria2 like '%" . $CATEGORIA . "%')";
 }
  
 if($UPC != ""){
    $condicion .= " and (p.upc like '%" . $UPC . "%')";
   }
  


//$usuario = [];
$sql = "Select p.idproducto,
p.upc,
p.descripcion,
p.descripcion1,
p.cantidad_caja,
p.cantidad_kgs,
p.idempresa,
p.categoria1,
p.categoria2,
p.udc,
p.fdc,
DATE_FORMAT(p.fdc,'%d/%m/%Y') as fdc1,
p.uda,
p.fda,
DATE_FORMAT(p.fda,'%d/%m/%Y') as fda1,
p.ruta,
p.idestatus from cat_productos p
where p.idestatus = 1 and p.idempresa = '{$idempresa}' $condicion $orden;";
//echo($sql);
//echo ('<br>');
         
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idproducto']   =   $row['idproducto'];
    $cat[$i]['upc']   =   $row['upc'];
    $cat[$i]['descripcion']   =   $row['descripcion'];
    $cat[$i]['descripcion1']   =   $row['descripcion1'];
    $cat[$i]['cantidad_caja']   =   $row['cantidad_caja'];
    $cat[$i]['cantidad_kgs']   =   $row['cantidad_kgs'];
    $cat[$i]['idempresa']   =   $row['idempresa'];
    $cat[$i]['categoria1']   =   $row['categoria1'];
    $cat[$i]['categoria2']   =   $row['categoria2'];
    $cat[$i]['idcadena']   =  0;
    $cat[$i]['precio']   =   0;
    $cat[$i]['udc']   =   $row['udc'];
    $cat[$i]['fdc']   =   $row['fdc'];
    $cat[$i]['fdc_m']   =   $row['fdc1'];
    $cat[$i]['uda']   =   $row['uda'];
    $cat[$i]['fda']   =   $row['fda'];
    $cat[$i]['fda_m']   =   $row['fda1'];
	$cat[$i]['ruta']   =   $row['ruta'];
    $cat[$i]['idestatus']   =   $row['idestatus'];
    $cat[$i]['nombreempresa']   =   '';
    $cat[$i]['cadena']   =   '';
    $i++;
  }
    //$cat[]['sql'] = $sql;
  echo json_encode($cat);
}
else
{
  $i = 0;
  ///  $cat[$i]['sql']      =  $sql;        
    echo $sql;    
 // http_response_code(404);
}
?>