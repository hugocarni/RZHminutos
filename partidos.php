<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partidos - RZHminutos</title>

    <style>
        /* --- Mejora de tabla responsive --- */
        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background-color: #bbb;
            border-radius: 4px;
        }

        .table th {
            white-space: nowrap;
            position: sticky;
            top: 0;
            background: #212529;
            color: white;
            z-index: 2;
        }

        .table td {
            white-space: nowrap;
        }

        /* En móviles, el nombre de la jugadora fijo a la izquierda */
        @media (max-width: 768px) {
            .table td:first-child,
            .table th:first-child {
                position: sticky;
                left: 0;
                background: #fff;
                z-index: 3;
            }
        }
    </style>
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
    $minutos = [];
    while ($m = mysqli_fetch_assoc($resultadoMinutos)) {
        $minutos[$m['id_jugadora']][$m['id_partido']] = $m['minutos'];
    }
    ?>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Minutos jugados por partido</h2>

        <div class="table-container shadow-sm rounded">
            <table class="table table-striped table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <?php foreach ($partidos as $p): ?>
                            <th><?= htmlspecialchars($p['rival']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jugadoras as $j): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($j['nombre']) ?></td>
                            <?php foreach ($partidos as $p): ?>
                                <?php
                                $valor = isset($minutos[$j['id']][$p['id']]) ? $minutos[$j['id']][$p['id']] : '-';
                                ?>
                                <td><?= htmlspecialchars($valor) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php mysqli_close($conexion); ?>
</body>

</html>
