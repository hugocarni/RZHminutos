<?php
require_once('conexion.php');

// Leer datos JSON enviados desde fetch
$data = json_decode(file_get_contents("php://input"), true);

$id_jugadora = intval($data['id_jugadora'] ?? 0);
$id_partido = intval($data['id_partido'] ?? 0);
$minutos = intval($data['minutos'] ?? 0);

header('Content-Type: application/json');

if ($id_jugadora > 0 && $id_partido > 0 && $minutos > 0) {
    $sql = "INSERT INTO minutos_jugados (id_jugadora, id_partido, minutos) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iii", $id_jugadora, $id_partido, $minutos);
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Error en ejecución: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Error en preparación: " . $conexion->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Datos inválidos"]);
}
?>
