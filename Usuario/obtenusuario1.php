<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);

if($request->username == "" || $request->password == "")
{
  return http_response_code(400);
}

//$usuario = [];
$sql = "Select u.idUsuario, u.usuario, u.activo, 
u.idperfil, p.perfil, u.idempresa 
from usuarios u inner join perfiles p 
on u.idperfil = p.idperfil  
where u.usuario = '" .$request->username ."' 
and u.clave = '" . $request->password . "' 
and u.activo = 1;";
//echo($sql);
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $usr['idUsuario']   = $row['idUsuario'];
    $usr['usuario'] 	= $row['usuario'];
    $usr['activo'] 		= $row['activo'];
    $usr['idperfil'] 	= $row['idperfil'];
    $usr['perfil'] 		= $row['perfil'];
    $usr['idempresa'] 	= $row['idempresa'];
    $i++;
  }

  if($i==0)
    {
      $usr['idUsuario']    = 0;
      $usr['usuario'] = '';
      $usr['activo'] = 0;
    }
    else  {
      // Eliminar duplicados
      $sql = " Delete from photos where id in (Select Maximo from Vw_FotosDuplicadas);";
      mysqli_query($con,$sql);
    }
    echo json_encode($usr);
    //echo json_encode($usuario['usuario'] );
}
else
{
  http_response_code(404);
}
}
?>