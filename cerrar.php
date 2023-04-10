<?php
    session_start();
    session_destroy(); // Destruir todas las variables de session
    header("Location: ./login.php"); // Redireccionar al login
?>