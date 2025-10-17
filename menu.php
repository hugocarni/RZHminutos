<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .navbar {
        background: linear-gradient(90deg, #0062E6, #000000ff);
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
        color: white !important;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        transition: color 0.3s;
    }

    .nav-link:hover {
        color: #fff !important;
    }

    .btn-cta {
        background-color: #fff;
        color: #0062E6;
        font-weight: 600;
        border-radius: 30px;
        padding: 8px 20px;
        transition: all 0.3s;
    }

    .btn-cta:hover {
        background-color: #e8f0ff;
        color: #004bb5;
    }
</style>


<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">RZHminutos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav me-3">
                <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="estadisticas.php">Estadisticas</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Proyectos</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Sobre Nosotros</a></li>
            </ul>
            <!-- <a href="#" class="btn btn-cta">Contáctanos</a> -->
        </div>
    </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>