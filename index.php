<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RZHminutos</title>
</head>
<body>
<?php
require_once('conexion.php');

$sql = "SELECT nombre FROM jugadoras";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    echo "<ul>";
    while($fila = $resultado->fetch_assoc()) {
        echo "<li>" . $fila["nombre"] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No hay registros";
}
?>
</body>
</html>