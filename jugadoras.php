<?php
$id_partido = $_GET['id'] ?? null;
if (!$id_partido) {
    header('Location: index.php');
    exit;
}
?>

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
            min-height: 100vh;
        }

        h2 {
            margin-top: 25px;
            color: #2c3e50;
            text-align: center;
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

        #btnComenzar, #btnFinalizar {
            margin-top: 20px;
            padding: 10px 20px;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: background 0.3s;
        }

        #btnComenzar { background-color: #27ae60; }
        #btnComenzar:hover { background-color: #1e8449; }

        #btnFinalizar { background-color: #e74c3c; display:none; }
        #btnFinalizar:hover { background-color: #c0392b; }

        .contenedor {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            width: 95%;
            max-width: 900px;
            margin-top: 20px;
        }

        .zona {
            flex: 1;
            min-width: 280px;
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

        li:last-child { border-bottom: none; }

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

        .btn-cambio:hover { background-color: #0056b3; }

        /* Iconos de cambio */
        .icono-cambio {
            margin-left: 8px;
            font-size: 16px;
        }
        .icono-verde { color: #27ae60; }
        .icono-rojo { color: #e74c3c; }

        /* Efecto de resaltar suplentes */
        .zona.resaltada {
            box-shadow: 0 0 15px 3px #3498db;
            transform: scale(1.02);
        }

        /* Mensaje flotante */
        #mensajeCambio {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #3498db;
            color: white;
            padding: 12px 25px;
            border-radius: 20px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
            font-size: 15px;
            display: none;
            z-index: 1000;
        }

        /* Reloj */
        #reloj {
            background-color: #2c3e50;
            color: white;
            font-size: 18px;
            padding: 8px 20px;
            border-radius: 20px;
            margin-top: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            display: none;
        }

        /* Mensaje de error */
        #mensajeError {
            margin-top: 10px;
            color: #e74c3c;
            font-weight: 600;
            display: none;
            text-align: center;
        }

        @media (max-width: 600px) {
            .contenedor {
                flex-direction: column;
                align-items: center;
            }
            .zona {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<h2>Seleccionar Titulares</h2>

<div id="listaJugadoras">
<?php
require_once('conexion.php');
$sql = "SELECT id, nombre FROM jugadoras";
$resultado = $conexion->query($sql);
if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        echo '<div class="jugadora">';
        echo '<label><input type="checkbox" class="checkTitular" data-id="' . $fila["id"] . '" value="' . htmlspecialchars($fila["nombre"]) . '"> ' . htmlspecialchars($fila["nombre"]) . '</label>';
        echo '</div>';
    }
} else {
    echo "No hay jugadoras registradas.";
}
?>
</div>

<div id="mensajeError"></div>
<button id="btnComenzar" style="display:none;">Comenzar</button>

<div id="reloj"></div>
<h4 id="datospartido">
    <?php 
    $sql = "SELECT rival FROM partidos WHERE id = $id_partido";
    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        echo "Rival: " . htmlspecialchars($fila["rival"]);
    }
    ?>
</h4>
<button id="btnFinalizar">Finalizar Partido</button>

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
const idPartido = <?php echo intval($id_partido); ?>;
const checkboxes = document.querySelectorAll('.checkTitular');
const btnComenzar = document.getElementById('btnComenzar');
const btnFinalizar = document.getElementById('btnFinalizar');
const listaJugadoras = document.getElementById('listaJugadoras');
const resultado = document.getElementById('resultado');
const listaTitulares = document.getElementById('listaTitulares');
const listaSuplentes = document.getElementById('listaSuplentes');
const zonaSuplentes = document.getElementById('zonaSuplentes');
const mensajeCambio = document.getElementById('mensajeCambio');
const mensajeError = document.getElementById('mensajeError');
const reloj = document.getElementById('reloj');

let titulares = [];
let suplentes = [];
let titularSeleccionado = null;
let tiempoInicio = null;
let intervaloReloj = null;
let partidoActivo = false;

let jugadorasData = {}; // Guardará datos de tiempos por jugadora

// -----------------------------
// SELECCION DE TITULARES
// -----------------------------
checkboxes.forEach(chk => {
    chk.addEventListener('change', () => {
        const seleccionadas = document.querySelectorAll('.checkTitular:checked').length;
        if (seleccionadas > 7) {
            chk.checked = false;
            mostrarError("Solo puedes seleccionar 7 titulares.");
            return;
        }
        mensajeError.style.display = 'none';
        btnComenzar.style.display = (seleccionadas === 7) ? 'block' : 'none';
    });
});

// -----------------------------
// COMIENZA EL PARTIDO
// -----------------------------
btnComenzar.addEventListener('click', () => {
    listaJugadoras.style.display = 'none';
    btnComenzar.style.display = 'none';
    resultado.style.display = 'flex';
    reloj.style.display = 'block';
    btnFinalizar.style.display = 'block';
    partidoActivo = true;

    titulares = [];
    suplentes = [];

    checkboxes.forEach(chk => {
        const id = chk.dataset.id;
        if (chk.checked) {
            titulares.push({ id, nombre: chk.value, cambios: [] });
            jugadorasData[id] = { jugando: true, tiempoEntrada: Date.now(), tiempoAcumulado: 0 };
        } else {
            suplentes.push({ id, nombre: chk.value, cambios: [] });
            jugadorasData[id] = { jugando: false, tiempoEntrada: null, tiempoAcumulado: 0 };
        }
    });

    renderListas();
    iniciarReloj();
});

// -----------------------------
// FINALIZAR PARTIDO
// -----------------------------
btnFinalizar.addEventListener('click', async () => {
    clearInterval(intervaloReloj);
    reloj.textContent += " 🏁";
    partidoActivo = false;
    document.querySelectorAll('.btn-cambio').forEach(btn => btn.disabled = true);

    // Guardar minutos finales de todas las que estén jugando
    for (let id in jugadorasData) {
        const j = jugadorasData[id];
        if (j.jugando && j.tiempoEntrada) {
            const minutos = Math.floor((Date.now() - j.tiempoEntrada) / 60000);
            await guardarMinutos(id, minutos);
            j.jugando = false;
        }
    }
    mostrarMensaje('Partido finalizado y minutos guardados ✅');
});

// -----------------------------
// CAMBIOS
// -----------------------------
function cambiarTitular(nombreTitular) {
    titularSeleccionado = nombreTitular;
    mostrarMensaje(`Selecciona una suplente para reemplazar a ${nombreTitular}`);
    zonaSuplentes.classList.add('resaltada');
}

async function cambiarSuplente(nombreSuplente) {
    if (!titularSeleccionado) {
        mostrarMensaje('Primero selecciona una titular a reemplazar.');
        return;
    }

    let t = titulares.find(j => j.nombre === titularSeleccionado);
    let s = suplentes.find(j => j.nombre === nombreSuplente);

    if (t && s) {
        // Jugadora que SALE
        t.cambios.push('sale');
        const jugOut = jugadorasData[t.id];
        if (jugOut.jugando && jugOut.tiempoEntrada) {
            const minutos = Math.floor((Date.now() - jugOut.tiempoEntrada) / 60000);
            await guardarMinutos(t.id, minutos);
            jugOut.jugando = false;
            jugOut.tiempoEntrada = null;
        }

        // Jugadora que ENTRA
        s.cambios.push('entra');
        const jugIn = jugadorasData[s.id];
        jugIn.jugando = true;
        jugIn.tiempoEntrada = Date.now();

        // Actualizar arrays
        titulares = titulares.filter(j => j.nombre !== titularSeleccionado);
        suplentes = suplentes.filter(j => j.nombre !== nombreSuplente);
        titulares.push(s);
        suplentes.push(t);
    }

    titularSeleccionado = null;
    zonaSuplentes.classList.remove('resaltada');
    renderListas();
    mostrarMensaje('Cambio realizado correctamente ✅');
}

// -----------------------------
// RENDER Y RELOJ
// -----------------------------
function renderListas() {
    listaTitulares.innerHTML = '';
    listaSuplentes.innerHTML = '';

    const crearItem = (jug, tipo) => {
        const li = document.createElement('li');
        const nombreDiv = document.createElement('span');
        nombreDiv.textContent = jug.nombre;
        jug.cambios.forEach(tipoCambio => {
            const icono = document.createElement('span');
            icono.classList.add('icono-cambio', tipoCambio === 'entra' ? 'icono-verde' : 'icono-rojo');
            icono.textContent = tipoCambio === 'entra' ? '⬆️' : '⬇️';
            nombreDiv.appendChild(icono);
        });
        const btn = document.createElement('button');
        btn.textContent = tipo === 'titular' ? 'Cambiar' : 'Entrar';
        btn.classList.add('btn-cambio');
        btn.disabled = !partidoActivo;
        btn.addEventListener('click', () => {
            tipo === 'titular' ? cambiarTitular(jug.nombre) : cambiarSuplente(jug.nombre);
        });
        li.appendChild(nombreDiv);
        li.appendChild(btn);
        return li;
    };

    titulares.forEach(j => listaTitulares.appendChild(crearItem(j, 'titular')));
    suplentes.forEach(j => listaSuplentes.appendChild(crearItem(j, 'suplente')));
}

function iniciarReloj() {
    tiempoInicio = new Date();
    intervaloReloj = setInterval(actualizarReloj, 1000);
}

function actualizarReloj() {
    const ahora = new Date();
    const segundosTotales = Math.floor((ahora - tiempoInicio) / 1000);
    const minutos = Math.floor(segundosTotales / 60);
    const segundos = segundosTotales % 60;
    reloj.textContent = `⏱ ${minutos.toString().padStart(2,'0')}:${segundos.toString().padStart(2,'0')}`;
}

// -----------------------------
// UTILIDADES
// -----------------------------
async function guardarMinutos(id_jugadora, minutos) {
    await fetch('guardar_minutos.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id_jugadora, id_partido: idPartido, minutos })
    });
}

function mostrarMensaje(texto) {
    mensajeCambio.textContent = texto;
    mensajeCambio.style.display = 'block';
    setTimeout(() => mensajeCambio.style.display = 'none', 2500);
}

function mostrarError(texto) {
    mensajeError.textContent = texto;
    mensajeError.style.display = 'block';
    setTimeout(() => mensajeError.style.display = 'none', 3000);
}
</script>
</body>
</html>
