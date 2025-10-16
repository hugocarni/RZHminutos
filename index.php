<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RZHminutos</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f6fa;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            margin-top: 30px;
            color: #2c3e50;
        }

        #listaJugadoras {
            background: white;
            border-radius: 12px;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .jugadora {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
        }

        .jugadora:last-child {
            border-bottom: none;
        }

        #btnComenzar {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #27ae60;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: background 0.3s;
        }

        #btnComenzar:hover {
            background-color: #1e8449;
        }

        .contenedor {
            display: flex;
            justify-content: center;
            gap: 40px;
            width: 90%;
            max-width: 900px;
            margin-top: 30px;
        }

        .zona {
            flex: 1;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .zona h3 {
            text-align: center;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
        }

        li:last-child {
            border-bottom: none;
        }

        .btn-cambio {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.3s;
        }

        .btn-cambio:hover {
            background-color: #0056b3;
        }

        /* Efecto de resaltar suplentes */
        .zona.resaltada {
            box-shadow: 0 0 15px 3px #3498db;
            transform: scale(1.02);
        }

        /* Mensaje flotante */
        #mensajeCambio {
            position: fixed;
            bottom: 20px;
            background: #3498db;
            color: white;
            padding: 12px 25px;
            border-radius: 20px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
            font-size: 15px;
            display: none;
        }
    </style>
</head>
<body>

<h2>Seleccionar Titulares</h2>

<div id="listaJugadoras">
<?php
require_once('conexion.php');

$sql = "SELECT * FROM jugadoras";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        echo '<div class="jugadora">';
        echo '<label><input type="checkbox" class="checkTitular" value="' . $fila["nombre"] . '"> ' . htmlspecialchars($fila["nombre"]) . '</label>';
        echo '</div>';
    }
} else {
    echo "No hay jugadoras registradas.";
}
?>
</div>

<button id="btnComenzar" style="display:none;">Comenzar</button>

<div id="resultado" class="contenedor" style="display:none;">
    <div class="zona" id="zonaTitulares">
        <h3>Titulares</h3>
        <ul id="listaTitulares"></ul>
    </div>
    <div class="zona" id="zonaSuplentes">
        <h3>Suplentes</h3>
        <ul id="listaSuplentes"></ul>
    </div>
</div>

<div id="mensajeCambio"></div>

<script>
    const checkboxes = document.querySelectorAll('.checkTitular');
    const btnComenzar = document.getElementById('btnComenzar');
    const listaJugadoras = document.getElementById('listaJugadoras');
    const resultado = document.getElementById('resultado');
    const listaTitulares = document.getElementById('listaTitulares');
    const listaSuplentes = document.getElementById('listaSuplentes');
    const zonaSuplentes = document.getElementById('zonaSuplentes');
    const mensajeCambio = document.getElementById('mensajeCambio');

    let titulares = [];
    let suplentes = [];
    let titularSeleccionado = null;

    checkboxes.forEach(chk => {
        chk.addEventListener('change', () => {
            const seleccionadas = document.querySelectorAll('.checkTitular:checked').length;
            
            if (seleccionadas > 7) {
                alert('Solo puedes seleccionar 7 titulares.');
                chk.checked = false;
            }
            btnComenzar.style.display = (seleccionadas === 7) ? 'block' : 'none';
        });
    });

    btnComenzar.addEventListener('click', () => {
        listaJugadoras.style.display = 'none';
        btnComenzar.style.display = 'none';
        resultado.style.display = 'flex';

        titulares = [];
        suplentes = [];

        checkboxes.forEach(chk => {
            if (chk.checked) {
                titulares.push(chk.value);
            } else {
                suplentes.push(chk.value);
            }
        });

        renderListas();
    });

    function renderListas() {
        listaTitulares.innerHTML = '';
        listaSuplentes.innerHTML = '';

        titulares.forEach(nombre => {
            const li = document.createElement('li');
            li.textContent = nombre + ' ';
            const btn = document.createElement('button');
            btn.textContent = 'Cambiar';
            btn.classList.add('btn-cambio');
            btn.addEventListener('click', () => cambiarTitular(nombre));
            li.appendChild(btn);
            listaTitulares.appendChild(li);
        });

        suplentes.forEach(nombre => {
            const li = document.createElement('li');
            li.textContent = nombre + ' ';
            const btn = document.createElement('button');
            btn.textContent = 'Entrar';
            btn.classList.add('btn-cambio');
            btn.addEventListener('click', () => cambiarSuplente(nombre));
            li.appendChild(btn);
            listaSuplentes.appendChild(li);
        });
    }

    function cambiarTitular(nombreTitular) {
        titularSeleccionado = nombreTitular;

        // Mostrar mensaje y resaltar suplentes
        mostrarMensaje(`Selecciona una suplente para reemplazar a ${nombreTitular}`);
        zonaSuplentes.classList.add('resaltada');
    }

    function cambiarSuplente(nombreSuplente) {
        if (!titularSeleccionado) {
            mostrarMensaje('Primero selecciona una titular a reemplazar.');
            return;
        }

        // Realizar el cambio
        titulares = titulares.filter(n => n !== titularSeleccionado);
        suplentes = suplentes.filter(n => n !== nombreSuplente);

        titulares.push(nombreSuplente);
        suplentes.push(titularSeleccionado);

        titularSeleccionado = null;
        zonaSuplentes.classList.remove('resaltada');
        renderListas();

        mostrarMensaje('Cambio realizado correctamente ✅');
    }

    function mostrarMensaje(texto) {
        mensajeCambio.textContent = texto;
        mensajeCambio.style.display = 'block';
        setTimeout(() => {
            mensajeCambio.style.display = 'none';
        }, 2500);
    }
</script>

</body>
</html>
