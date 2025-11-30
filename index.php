<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEMA WIFI - Información</title>
    <link rel="stylesheet" href="./app/vista/css/bootstrap.min.css">
    <link rel="stylesheet" href="./app/vista/css/estilos.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand nav-link-brand" href="#">SISTEMA WIFI</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#features">Características</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#specs">Especificaciones</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-modern" href="./app/vista/pantallas/login.php">Iniciar sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container" style="padding-top: 100px;">
        <!-- Sección de información principal -->
        <section class="hero-section text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="hero-title display-4 fw-bold">Sistema de Gestión WiFi</h1>
                    <p class="lead fs-5">
                        Nuestro sistema de gestión WiFi ofrece una solución completa para la administración 
                        de redes inalámbricas, permitiendo un control total sobre usuarios, dispositivos 
                        y políticas de acceso.
                    </p>
                    <a href="#features" class="btn btn-modern mt-3">Descubre más</a>
                </div>
            </div>
        </section>
        
        <!-- Carousel -->
        <section class="my-5">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded-3">
                    <div class="carousel-item active">
                        <img src="./app/vista/img/1.png" class="d-block w-100" alt="Panel de administración del sistema WiFi">
                    </div>
                    <div class="carousel-item">
                        <img src="./app/vista/img/2.png" class="d-block w-100" alt="Estadísticas de uso de la red">
                    </div>
                    <div class="carousel-item">
                        <img src="./app/vista/img/3.png" class="d-block w-100" alt="Gestión de usuarios">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>
        
        <!-- Características del sistema -->
        <section id="features" class="my-5 py-5">
            <h2 class="section-title text-center display-5 fw-bold">Características Principales</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-wifi text-white" viewBox="0 0 16 16">
                                <path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.518.518 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.802 6.716 2.164.205.148.49.13.668-.049z"/>
                                <path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .707 0l.707-.707z"/>
                            </svg>
                        </div>
                        <h3 class="text-center fw-bold mb-3">Gestión de Red</h3>
                        <p class="text-center">
                            Administra múltiples puntos de acceso, configura SSID, establece políticas de seguridad 
                            y monitorea el estado de la red en tiempo real.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-people-fill text-white" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                                <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                            </svg>
                        </div>
                        <h3 class="text-center fw-bold mb-3">Control de Usuarios</h3>
                        <p class="text-center">
                            Gestiona usuarios, asigna permisos, crea grupos y establece límites de ancho de banda 
                            para diferentes tipos de usuarios.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-graph-up text-white" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07z"/>
                            </svg>
                        </div>
                        <h3 class="text-center fw-bold mb-3">Análisis y Reportes</h3>
                        <p class="text-center">
                            Obtén informes detallados sobre el uso de la red, tráfico generado, dispositivos conectados 
                            y estadísticas de rendimiento.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Especificaciones técnicas -->
        <section id="specs" class="my-5 py-5">
            <h2 class="section-title text-center display-5 fw-bold">Especificaciones Técnicas</h2>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="spec-card card h-100">
                        <div class="card-header text-center">Compatibilidad</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-transparent text-white border-secondary">Compatibilidad con estándares 802.11 a/b/g/n/ac/ax</li>
                                <li class="list-group-item bg-transparent text-white border-secondary">Soporte para múltiples puntos de acceso</li>
                                <li class="list-group-item bg-transparent text-white border-secondary">Integración con sistemas de autenticación externos</li>
                                <li class="list-group-item bg-transparent text-white border-secondary">Compatibilidad con dispositivos iOS, Android y Windows</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="spec-card card h-100">
                        <div class="card-header text-center">Seguridad</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-transparent text-white border-secondary">Autenticación WPA2/WPA3 Enterprise</li>
                                <li class="list-group-item bg-transparent text-white border-secondary">Firewall integrado</li>
                                <li class="list-group-item bg-transparent text-white border-secondary">Filtrado de contenido</li>
                                <li class="list-group-item bg-transparent text-white border-secondary">Políticas de acceso por horario</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
   
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <a href="#" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                        <span class="nav-link-brand fs-4">SISTEMA WIFI</span>
                    </a>
                    <p class="text-white-50">Solución completa para la gestión de redes inalámbricas.</p>
                    <p class="text-white-50">&copy; 2025 Company, Inc. Todos los derechos reservados.</p>
                    <p class="text-white-50"> Desarrollado por: Jose Gonzalez.</p>
                </div>
                <div class="col-md-5 offset-md-1 mb-3">
                    <h5 class="text-white">Síguenos</h5>
                    <ul class="list-unstyled d-flex">
                        <li class="me-3">
                            <a class="social-link text-white text-decoration-none" href="#" aria-label="Instagram">Instagram</a>
                        </li>
                        <li class="me-3">
                            <a class="social-link text-white text-decoration-none" href="#" aria-label="Facebook">Facebook</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="./app/vista/js/bootstrap.bundle.min.js"></script>
</body>
</html>