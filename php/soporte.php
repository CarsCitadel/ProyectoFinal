

<?php
session_start(); // Iniciar la sesión
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="/Styles.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"><meta charset="UTF-8">
  <link rel="icon" href="/icon/Logo Parqueadero.png">
  <link rel="icon" href="/icon/Reseña.gif">    
  <title>soporte</title>
</head>

<body class="body-contactanos">

<!-- HTML -->
    <!--Inicio de navbar-->
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid contenedor1">
            <a class="navbar-brand logo_Titulo" href="/php/index.php"><img src="/img/logo Parqueadero.png" width="100px" alt="">
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
                    <li class="nav-item"><a href="/index.php"> <img class="icon" src="/icon/casa-icono-silueta.png" width="23"> Inicio </a></li>
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
                    <li class="nav-item"><a href="/php/conexion.php"> <img class="icon" src="/icon/Inicio De Sesión.jpeg" width="23"> Inicio de sesión </a></li>
                    <li class="nav-item"><a href="/php/conexion.php"> <img class="icon" src="/icon/reserva.png" width="23"> Reserva ahora! </a></li>
                <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
    <section >
        <div class="form-contact d-flex flex-column justify-content-center align-items-center">
            <h2>Contactanos</h2>
            <p>
            Dejanos Saber Justo Aqui Como te podemos ayudar! No olvides especificar de forma detallada tu inquietud
            </p>
            <form
                action="https://formspree.io/f/mqakgaar"
                    method="POST">
                    <h6 class="mensajesoporte">Escribe tu correo</h6>
                    <label>
                        <input type="email" name="email">
                    </label>
                    <h6 class="mensajesoporte">Escribe tu mensaje</h6>
                    <label>
                        <textarea name="message"></textarea>
                    </label>
                    <!-- your other form fields go here -->
                    <button class="boton_Soporte" type="submit">Enviar</button>
            </form>
        </div>
    </section>
    
    <footer class="bg-ligth text-dark pt-5 pb-4" style="width: 100%;">
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
                    <p><a href="#" class="text-foot"> ayuda </a></p>
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