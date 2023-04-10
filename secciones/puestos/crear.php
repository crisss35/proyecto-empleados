<?php

    include("../../bd.php");

    // echo "<pre>";
    // var_dump($_SERVER);
    // echo "</pre>";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        print_r($_POST);

        // Recolectar datos del elemento post
        // Validar que exista la informacion enviada y si existe se asignara. si no existe lo dejara vacio
        $nombredelpuesto = (isset($_POST["nombredelpuesto"])?$_POST["nombredelpuesto"]: "");

        // Preparar la insercion
        $sentencia = $conexion->prepare("INSERT INTO tbl_puestos(id, nombredelpuesto) VALUES (null, :nombredelpuesto)");

        // Asignando valores que vienen del post (del formulario)
        $sentencia->bindParam(":nombredelpuesto", $nombredelpuesto);
        $sentencia->execute();
        $mensaje = "Registro Agregado";
        header("Location: index.php?mensaje=".$mensaje);
    }
?>
<?php include("../../templates/header.php"); ?>

<br/>

<div class="card">
    <div class="card-header">
        Puestos
    </div>
    <div class="card-body">
    
        <form action="" method="post" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="nombredelpuesto" class="form-label">Nombre del Puesto:</label>
                <input type="text" class="form-control" name="nombredelpuesto" id="nombredelpuesto" 
                aria-describedby="helpId" placeholder="Nombre del Puesto">
            </div>

            <button type="submit" class="btn btn-success">Agregar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../templates/footer.php"); ?>