<?php 

    $servidor = "localhost"; // 127.0.0.1
    $baseDeDatos = "app_php";
    $usuario = "root";
    $password = "root";

    try {
        $conexion = new PDO("mysql:host=$servidor;dbname=$baseDeDatos",$usuario,$password);

    }catch(Exception $ex) {
        echo $ex->getMessage();
    }


?>