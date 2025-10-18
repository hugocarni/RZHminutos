<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partidos - RZHminutos</title>
</head>

<body>
    <?php
    include('menu.php');
    require_once('conexion.php');

    // === 1️⃣ OBTENER JUGADORAS ===
    $queryJugadoras = "SELECT id, nombre FROM jugadoras ORDER BY nombre ASC";
    $resultadoJugadoras = mysqli_query($conexion, $queryJugadoras);
    if (!$resultadoJugadoras) {
        die("Error en la consulta de jugadoras: " . mysqli_error($conexion));
    }

    // Guardamos las jugadoras en un array
    $jugadoras = [];
    while ($j = mysqli_fetch_assoc($resultadoJugadoras)) {
        $jugadoras[] = $j;
    }

    // === 2️⃣ OBTENER PARTIDOS ===
    $queryPartidos = "SELECT id, rival FROM partidos ORDER BY id ASC";
    $resultadoPartidos = mysqli_query($conexion, $queryPartidos);
    if (!$resultadoPartidos) {
        die("Error en la consulta de partidos: " . mysqli_error($conexion));
    }

    // Guardamos los partidos en un array
    $partidos = [];
    while ($p = mysqli_fetch_assoc($resultadoPartidos)) {
        $partidos[] = $p;
    }

    // === 3️⃣ OBTENER MINUTOS JUGADOS ===
    $queryMinutos = "SELECT id_jugadora, id_partido, minutos FROM minutos_jugados";
    $resultadoMinutos = mysqli_query($conexion, $queryMinutos);
    if (!$resultadoMinutos) {
        die("Error en la consulta de minutos: " . mysqli_error($conexion));
    }

    // Guardar los minutos en un array asociativo
    $minutos = [];
    while ($m = mysqli_fetch_assoc($resultadoMinutos)) {
        $minutos[$m['id_jugadora']][$m['id_partido']] = $m['minutos'];
    }
    ?>

    <div class="container mt-5 d-flex flex-column align-items-center">
        <h2 class="mb-4 text-center">Minutos jugados por partido</h2>

        <div class="table-responsive" style="max-width: 1000px;">
            <table class="table table-striped table-bordered text-center shadow-sm align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <?php
                        // Encabezados con los rivales
                        foreach ($partidos as $p) {
                            echo "<th>" . htmlspecialchars($p['rival']) . "</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Una fila por jugadora
                    foreach ($jugadoras as $j) {
                        echo "<tr>";
                        echo "<td class='fw-semibold'>" . htmlspecialchars($j['nombre']) . "</td>";

                        // Una columna por partido
                        foreach ($partidos as $p) {
                            $valor = isset($minutos[$j['id']][$p['id']]) ? $minutos[$j['id']][$p['id']] : '-';
                            echo "<td>" . htmlspecialchars($valor) . "</td>";
                        }

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php mysqli_close($conexion); ?>
</body>

</html>
