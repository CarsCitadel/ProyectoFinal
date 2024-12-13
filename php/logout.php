<?php
session_start();  // Inicia la sesión si no está iniciada
session_unset();  // Elimina todas las variables de sesión
session_destroy();  // Destruye la sesión

header("Location: /index.php");  // Redirige al login o a la página principal
exit;
?>