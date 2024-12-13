
<?php
session_start(); // Iniciar la sesión
?>

<!DOCTYPE html> 
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/Styles.css">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" href="/icon/Logo Parqueadero.png">
    <title> Cars Citadel </title>
</head>

<body>

<!-- HTML -->
    <!--Inicio de navbar-->
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid contenedor1">
            <a class="navbar-brand logo_Titulo" href="#"><img src="/img/logo Parqueadero.png" width="100px" alt="">
                <h1>Cars Citadel</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> 
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse contendor__list" id="navbarNav">
                <ul class="navbar1">
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Si el usuario está logueado, mostrar el perfil y la opción de cerrar sesión -->
                    <li class="nav-item"><a href="/mapa.html"> <img class="icon" src="/icon/reserva.png" width="23"> Reserva ahora! </a></li>
                    <li><h6 class="nombre-navbar"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : '' ?></h6></li>
                    <div class="navbar-profile">
                    <img src="/icon/usuario-de-perfil.png" alt="Foto de perfil" class="profile-picture" id="profileButton">
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li><a href="/php/perfil.php">Perfil</a></li>
                        <li><a href="/php/Historial.php">Historial</a></li>
                        <li><a href="/php/logout.php" id="logout">Cerrar Sesión</a></li>
                    </ul>
                    </div>
                <?php else: ?>
                    <!-- Si el usuario no está logueado, mostrar solo inicio de sesión y reserva -->
                    <li class="nav-item"><a href="/php/conexion.php"> <img class="icon" src="/icon/Inicio De Sesiвn.jpeg" width="23"> Inicio de sesión </a></li>
                    <li class="nav-item"><a href="/php/conexion.php"> <img class="icon" src="/icon/reserva.png" width="23"> Reserva ahora! </a></li>
                <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <!--Fin navbar-->

    <section class="home-section d-flex justify-content-center align-items-center">
        <div class="container4">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-lg-6 contenido__encabezados">
                    <center>
                        <h2>Hola, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : '' ?> Bienvenid@ a Cars Citadel</h2>
                        <h3>¡Bienvenid@ a nuestro Parqueadero!</h3>
                        <h3>Nos alegra tenerte aquí. En nuestro parqueadero, nos esforzamos por ofrecerte la mejor experiencia en comodidad, seguridad y servicio.</h3>
                        <h3>Tu vehículo estará siempre en buenas manos, con personal capacitado y atento a cada duda</h3>
                        <h2 >¡Gracias por confiar en nosotros! Si tienes alguna pregunta o necesitas asistencia, no dudes enconsultarnos.</h2>
                    </center>
                </div>
                <div class="col-lg-6 d-flex justify-content-center align-items-center">
                    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" style="margin: 200px;" >
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <video width="800" height="552" autoplay loop muted>
                                    <source src="/Videos/Carrusel 1.mp4" type="video/mp4">Tu navegador no soporta el elemento <code>video</code>
                                </video>
                            </div>
                            <div class="carousel-item">
                                <video width="800" height="552" autoplay loop muted>
                                    <source src="/Videos/Carrusel 2.mp4" type="video/mp4">Tu navegador no soporta el elemento <code>video</code>
                                </video>
                            </div>
                            <div class="carousel-item">
                                <video width="800" height="552" autoplay loop muted>
                                    <source src="/Videos/Carrusel 3.mp4" type="video/mp4">Tu navegador no soporta el elemento <code>video</code>
                                </video>
                            </div>                     
                        </div>
                    </div>   
                </div>  
            </div> 
        </div>
    </section>
    
    <div class="wrapper">
            <h2 class="Servicio_h2">Servicios que ofrecemos</h2>
            <h3 class="Servicio_h3">Nuestro sistema cuenta con un amplio equipo de trabajo, lo que nos permite ofrecerte la mejor experiencia a través de cada una de estas opciones.</h3>
        
       <div class="content-box">
            <div class="card">
                <i class="bx bx-camera bx-md"></i>
                <h2>Camaras 24/7</h2>
                <p>Contamos con camaras 24/7 para la seguridad de su vehiculo </p>
            </div>
            <div class="card">
                <i class="bx bx-laptop bx-md"></i>
                <h2>Reservas</h2>
                <p>Haz Tus Reservas En Menos De 5 Minutos! </p>
                <a href="/mapa.html" class="boton_Soporte">Reserva Justo Aqui</a>
            </div>
            <div class="card">
                <i class="bx bx-line-chart bx-md"></i>
                <h2>Cajero</h2>
                <p>Contamos en nuestro punto fisico, cajeros de bancos para su comodidad</p>
            </div>
            <div class="card">
                <i class="bx bx-mail-send bx-md"></i>
                <h2>Soporte</h2>
                <p>Tienes algun problema dejamelo saber aqui! </p>
                <a href="/php/soporte.php" class="boton_Soporte">Soporte</a>
            </div>
            <div class="card">
                <i class="bx bx-bar-chart-alt bx-md"></i>
                <h2>Tienda</h2>
                <p>Contamos en nuestro punto fisico, una tienda para sus compras</p>
            </div>
            <div class="card">
                <i class="bx bx-paint bx-md"></i>
                <h2>Test</h2>
                <p>Dejanos saber tu experiencia en nuestra pagina web </p>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSd9szBa_hyglZghmtPk5lsvdKnF0VN1xyad_nA9BBmtszgtBA/viewform?usp=sf_link" class="boton_Soporte">Test</a>
            </div>
        </div>
    </div>

    <section class="home-section d-flex justify-content-center align-items-center">
        <div class="container3">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-lg-8 contenido_Publicidad">
                    <center>
                        <h2>Estaciona con tranquilidad,tu seguridad es nuestra prioridad</h2>
                        <h3>¿Quieres saber mas de nuestro parquedero?</h3>
                        <h3>Da clic en Sobre Nosotros</h3>
                    </center>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="/php/sobrenosotros.php" class="boton_Publicidad">Sobre Nosotros</a>
                    </div>
                    <center>
                        <h2 style="font-size: 34px; margin-top: 50px;">Tambien puedes encontrarnos en.</h2>
                    </center>
                    <div class="container d-flex justify-content-center align-items-center botones__redes">
                        <div class="d-flex justify-content-center align-items-center">
                            <a href="https://www.facebook.com/profile.php?id=61569585433061" class="boton_Publicidad"><i class="fab fa-facebook"></i>Facebook</a>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <a href="https://x.com/CarCitadel176" class="boton_Publicidad"><i class="fab fa-twitter"></i>Twitter</a>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <a href="https://www.instagram.com/cars_citadel176/" class="boton_Publicidad"><i class="fab fa-instagram"></i>Instagram</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-flex justify-content-center align-items-center">
                    <article class="article__img">
                        <img src="/img/logo Parqueadero.png" alt="" width="350" height="350">
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section>
        <h2 class="Mapa_h2">Nos Puedes Encontrar</h2>
        <div class="map-responsive">
            <div class="col d-flex justify-content-center align-items-center">
                <iframe class="Mapa"
                    src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d38952.856959755925!2d-75.20116263464429!3d4.438137629281894!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1724790619456!5m2!1ses-419!2sco"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <section>
        <div class="Resenas">
            <h1 class="Resenas_Title">Les Gusta CarCitadel</h1>
            <div class="reviews">
                <div class="review-card">
                    <div class="stars">★★★★★</div>
                    <h3>Excelente ubicación y seguridad</h3>
                    <p>Este parqueadero es ideal si buscas un lugar seguro para dejar tu vehículo. Está bien iluminado y con cámaras de seguridad en todos los niveles. Además, el personal siempre está atento y dispuesto a ayudar. La ubicación es muy conveniente, cerca de centros comerciales y restaurantes, lo que facilita moverse sin preocupaciones.</p>
                    <div class="autor">
                        <img src="/img/FotoD.jpeg" alt="">
                        <div class="autor-info">
                            <span>Andres David</span>
                            <p>Cliente Frecuente</p>
                        </div>
                    </div>
                </div>
                <div class="review-card">
                    <div class="stars">★★★★★</div>
                    <h3>Fácil acceso y tarifas accesibles</h3>
                    <p>Lo que más me gusta de este parqueadero es lo sencillo que es entrar y salir. Las senalizaciones son claras y las rampas son amplias, lo que hace que incluso los autos más grandes puedan maniobrar sin problemas. Además, las tarifas son muy razonables comparadas con otros estacionamientos de la zona. ¡Definitivamente lo recomendaría!</p>
                    <div class="autor">
                        <img src="/img/FotoT.jpeg" alt="">
                        <div class="autor-info">
                            <span>Lesly Tatiana</span>
                            <p>Propietaria De Una Camioneta</p>
                        </div>
                    </div>
                </div>
                <div class="review-card">
                    <div class="stars">★★★★★</div>
                    <h3>Servicio rápido y eficiente</h3>
                    <p>Siempre que uso este parqueadero, encuentro espacio disponible, lo que es un gran alivio. El proceso de ingreso y salida es rápido gracias a la tecnología de lectura automática de matrículas, y el personal de la caseta es muy amable. El lugar está limpio y bien mantenido. Es mi opción favorita cuando necesito un lugar seguro y confiable para estacionar.</p>
                    <div class="autor">
                        <img src="/img/FotoA.jpeg" alt="">
                        <div class="autor-info">
                            <span>Abab Yusseff</span>
                            <p>Propietario De Una Moto</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="margin: 0 auto;">
        <div class="contenido_preguntas_h2">
            <h2>Tú preguntas, nosotros respondemos</h2>
        </div>
        <div class="accordion accordion-flush responsive-accordion" id="accordionFlushExample" style="width:25rem; margin: 0 auto;">
            <div class="accordion-item"1>
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                ¿Cómo puedo saber si hay espacios disponibles en el parqueadero?
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Puedes verificar la disponibilidad en tiempo real a través de nuestra página web o contactarnos por nuestras redes sociales y servicios de mensajería como WhatsApp</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                ¿Qué medidas de seguridad ofrecen en el parqueadero?
                </button>
              </h2>
              <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Contamos con cámaras de seguridad las 24 horas, personal de vigilancia, y acceso controlado para garantizar la seguridad de tu vehículo.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    ¿Cuáles son las tarifas del parqueadero?
                </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Nuestras tarifas varían según la duración de la estancia y el tipo de vehículo. Puedes consultar nuestros precios en la sección de tarifas en nuestra página web.</div>
              </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseThree">
                    ¿Puedo reservar un espacio con anticipación?
                  </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">Sí, ofrecemos la opción de reserva anticipada a través de nuestro sistema en línea o por medio de una llamada o mensaje en WhatsApp.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseThree">
                    ¿Qué sucede si pierdo mi ticket de estacionamiento?
                  </button>
                </h2>
                <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">En caso de pérdida del ticket, deberás presentar una identificación válida y realizar el pago correspondiente a la tarifa máxima del día para poder retirar tu vehículo.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseThree">
                    ¿Aceptan tarjetas de crédito o solo pagos en efectivo?
                  </button>
                </h2>
                <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">Aceptamos pagos en efectivo, tarjetas de crédito y débito, así como transferencias bancarias.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseThree">
                    ¿El parqueadero cuenta con espacios para vehículos grandes o camionetas?
                  </button>
                </h2>
                <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">Sí, tenemos espacios amplios disenados para acomodar vehículos más grandes como camionetas o SUV.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEigth" aria-expanded="false" aria-controls="flush-collapseThree"> 
                    ¿Qué horario tiene el parqueadero?
                  </button>
                </h2>
                <div id="flush-collapseEigth" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">Nuestro parqueadero está abierto las 24 horas del día, los 7 días de la semana.</div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-ligth text-dark pt-5 pb-4">
        <div class="container text-center text-md-start">
            <div class="row text-center text-md-start">
                <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3 text-center">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Nosotros</h5>
                    <hr class="mb-4">
                    <p>
                        Contamos con un apartado que permite que nuestros usuarios conoscan un poco nuestro parqueadero
                        para asi generar una chispa de confianza en ellos, recuerda que tu vehiculo siempre sera nuestra
                        prioridad
                    </p>
                </div>

                <div class=" text-center col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Dejanos ayudarte</h5>
                    <hr class="mb-4">
                    <p><a href="#" class="text-foot"> tu cuenta </a></p>
                    <p><a href="#" class="text-foot"> tus ordenes </a></p>
                    <p><a href="#" class="text-foot">Manejo tu cuenta </a></p>
                </div>

                <div class=" text-center col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Sobre Nosotros</h5>
                    <hr class="mb-4">
                    <p><a href="https://www.google.com/maps" class="text-foot">Nos Ubicamos    </a></p>
                    <p><a href="/php/index.php" class="text-foot">Quienes Somos   </a></p>
                    <p><a href="/php/soporte.php" class="text-foot">Ayuda           </a></p>
                </div>
                <div class=" text-center col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Reserva</h5>
                    <hr class="mb-4">
                    <p><li class="fas fa-home me-3">      </li> Ibague - Tolima           </p>
                    <p><li class="fas fa-envelope hme-3"> </li> carcitadel.1@gmail.com     </p>
                    <p><li class="fas fa-phone me-3">     </li> +57 3168497181 (Colombia) </p>
                </div>
                <hr class="mb-4">
                <div class="text-center mb-2">
                    <p> Dejanos Saber Que Tal Fue Tu Experiencia <a href="https://docs.google.com/forms/d/e/1FAIpQLSd9szBa_hyglZghmtPk5lsvdKnF0VN1xyad_nA9BBmtszgtBA/viewform?usp=sf_link"> <strong class="text-primary">test</strong></a></p>
                </div>
                <div class="text-center">
                    <ul class="list-unstyled list-inline">
                        <li class="list-inline-item"> <a href="https://www.facebook.com/profile.php?id=61569585433061" class="text-foot"> <i class="fab fa-facebook"> </i></a></li>
                        <li class="list-inline-item"> <a href="https://x.com/CarCitadel176" class="text-foot"> <i class="fab fa-twitter">  </i></a></li>
                        <li class="list-inline-item"> <a href="https://www.instagram.com/cars_citadel176/" class="text-foot"> <i class="fab fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script src="/Script.js"></script>  
    <script src="/Perfil.js"></script>
    
</body>
</html>