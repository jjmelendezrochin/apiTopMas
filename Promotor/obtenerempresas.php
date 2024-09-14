<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$resultado = array();

$sql = "Select idempresa, nombreempresa, alias from cat_empresa where idestatus = 1 order by idempresa;";
		
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $pro['idempresa']          = $row['idempresa'];
    $pro['nombreempresa']      = $row['nombreempresa']; 
    $pro['alias']              = $row['alias'];
    array_push($resultado, array('EMPRESA' => $pro));        
    $i++;
  }

  if($i==0)
    {
        $pro['idempresa']          = $row['idempresa'];
        $pro['nombreempresa']      = $row['nombreempresa']; 
        $pro['alias']              = $row['alias'];    
    }
        
    echo json_encode(array("RESPUESTA" => $resultado));
}
else
{
  http_response_code(404);
}
?>