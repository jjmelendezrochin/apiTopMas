<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$cat = [];
// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;


/*if(!$idEmpresa)
{
  return http_response_code(400);
}*/

//$usuario = [];
if(intval($idEmpresa) > 0){
	$condicion = $idEmpresa;
}

$sql = "Select p.idproducto,
p.descripcion
from cat_productos p where p.idestatus = 1 
and p.idempresa = " . $condicion . "  
order by p.descripcion asc";
//echo($sql);
//echo ('<br>');
         
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idproducto']   =   $row['idproducto'];
    $cat[$i]['descripcion']   =   $row['descripcion'];
    $i++;
  }

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