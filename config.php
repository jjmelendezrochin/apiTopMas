<?php
/**
 * Regresa el usuario.
 */
if(isset($_GET["con"])){
require 'database.php';
}

$emp = [];
$cliente = 2;
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;
s

if(!$idEmpresa)
{
  return http_response_code(400);
}*/

//$usuario = [];
$sql = "Select c.* from configuracion c where c.idconf = 2;";
//echo($sql);
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $emp[$i]['idconf']     =   $row['idconf'];
    $emp[$i]['idempresa'] =   $row['idempresa'];
    $emp[$i]['id']     =   $row['id'];
	$emp[$i]['descripcion']     =   $row['descripcion'];
	$emp[$i]['valor']     =   $row['valor'];		
    if(strtolower(trim($emp[$i]['idconf'])) == '2'){
		$cliente = intval($emp[$i]['valor']);
	}	
	$i++;
  }
  if(isset($_GET["con"])){
  echo json_encode($emp);
  }
}
else
{
  http_response_code(404);
}

?>
