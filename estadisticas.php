<?php require('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - RZHminutos</title>
</head>

<body>
    <?php include('menu.php'); ?>
    <br><br>
    <h2 class="text-center">Estadísticas</h2>
    <div class="estadisticas text-center">
        <?php
        // Primero obtenemos el número total de partidos distintos
        $consulta_partidos = "SELECT COUNT(DISTINCT id_partido) AS total_partidos FROM minutos_jugados";
        $resultado_partidos = $conexion->query($consulta_partidos);
        $total_partidos = 0;
        if ($resultado_partidos && $fila_partidos = $resultado_partidos->fetch_assoc()) {
            $total_partidos = (int)$fila_partidos['total_partidos'];
        }

        // Si hay al menos un partido, ejecutamos la consulta principal
        if ($total_partidos > 0) {
            $sql = "
                SELECT 
                    j.nombre AS nombre_jugadora,
                    SUM(m.minutos) AS total_minutos
                FROM minutos_jugados m
                INNER JOIN jugadoras j ON m.id_jugadora = j.id
                GROUP BY j.nombre
                ORDER BY total_minutos DESC
            ";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                echo '<div class="table-responsive my-3 text-center" style="margin: 0 auto; width: 70%;">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre de la Jugadora</th>
                                    <th>Total de Minutos</th>
                                    <th>% Minutos Jugados</th>
                                </tr>
                            </thead>
                            <tbody>';
                while ($fila = $resultado->fetch_assoc()) {
                    $total_minutos = (float)$fila['total_minutos'];
                    $porcentaje = ($total_minutos / ($total_partidos * 60)) * 100;
                    echo '
                        <tr>
                            <td>' . htmlspecialchars($fila['nombre_jugadora']) . '</td>
                            <td>' . htmlspecialchars($total_minutos) . '</td>
                            <td>' . number_format($porcentaje, 2) . '%</td>
                        </tr>';
                }
                echo '      </tbody>
                        </table> 
                    </div>';
            } else {
                echo "No hay estadísticas disponibles";
            }
        } else {
            echo "No hay partidos registrados en la tabla minutos_jugados.";
        }
        ?>
    </div>
</body>

</html>
