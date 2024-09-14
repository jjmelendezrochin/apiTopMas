<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$condicion = "";
$cat = [];

//$usuario = [];
$sql = "SELECT p.* FROM perfiles p ORDER BY p.idperfil,p.perfil;";
//echo($sql);
//echo ('<br>');
if ($result = mysqli_query($con, $sql)) {
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $cat[$i]["idperfil"] = $row["idperfil"];
        $cat[$i]["perfil"] = $row["perfil"];
        $i++;
    }
    echo json_encode($cat);
} else {
    http_response_code(404);
}
