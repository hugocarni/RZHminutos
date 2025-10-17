<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Partido - RZHminutos</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .selector {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }

        select:focus {
            border-color: #27ae60;
            box-shadow: 0 0 5px rgba(39,174,96,0.5);
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #1e8449;
        }

        .mensaje {
            margin-top: 15px;
            color: #e74c3c;
            font-weight: 600;
            display: none;
        }
    </style>
</head>
<body>

<!-- 🔹 Incluimos el menú -->
<?php 
include('menu.php'); 
?>

<!-- 🔹 Contenido principal -->
<main>
    <h1>Seleccionar Partido</h1>

    <div class="selector">
        <form action="jugadoras.php" method="GET">
            <label for="partido">Elige un partido:</label><br><br>
            <select name="id" id="partido" required>
                <option value="">-- Selecciona un partido --</option>
                <?php
                require_once('conexion.php');

                $sql = "SELECT id, rival, fecha FROM partidos ORDER BY fecha ASC";
                $resultado = $conexion->query($sql);

                if ($resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        $nombre = htmlspecialchars($fila['rival']);
                        $fecha = date('d/m/Y', strtotime($fila['fecha']));
                        echo "<option value='{$fila['id']}'>$nombre - $fecha</option>";
                    }
                } else {
                    echo "<option disabled>No hay partidos registrados</option>";
                }
                ?>
            </select>

            <button type="submit" style="padding-top: 10px; margin-top: 20px;">Continuar</button>
        </form>

        <div class="mensaje" id="mensajeError">Debes seleccionar un partido.</div>
    </div>
</main>

<script>
    const form = document.querySelector('form');
    const select = document.getElementById('partido');
    const mensaje = document.getElementById('mensajeError');

    form.addEventListener('submit', (e) => {
        if (!select.value) {
            e.preventDefault();
            mensaje.style.display = 'block';
        }
    });
</script>

</body>
</html>
