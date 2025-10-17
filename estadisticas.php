<?php require('conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadisticas - RZHminutos</title>
</head>

<body>
    <?php include('menu.php'); ?>
<br><br>
    <h2 class="text-center">Estadisticas</h2>
    <div class="estadisticas text-center">
        <?php
        $sql = "
    SELECT 
        j.nombre AS nombre_jugadora,
        SUM(m.minutos) AS total_minutos
    FROM minutos_jugados m
    INNER JOIN jugadoras j ON m.id_jugadora = j.id
    GROUP BY j.nombre order by total_minutos desc
";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            echo '<div class="table-responsive my-3 text-center" style="margin: 0 auto; width: 50%;">
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Nombre de la Jugadora</th>
                <th>Total de Minutos</th>
            </tr>
        </thead>
        <tbody>';
            while ($fila = $resultado->fetch_assoc()) {
                echo '

            <tr>
                <td>' . htmlspecialchars($fila['nombre_jugadora']) . '</td>
                <td>' . htmlspecialchars($fila['total_minutos']) . '</td>
            </tr>
       
';
            }
            echo ' </tbody>
    </table> 
</div>';
        } else {
            echo "No hay estadísticas disponibles";
        }
        ?>

    </div>

</body>

</html>