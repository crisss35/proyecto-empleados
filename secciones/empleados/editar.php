<?php

    include("../../bd.php");

    if(isset($_GET["txtID"])){
        $txtID = (isset($_GET["txtID"]))?$_GET["txtID"]:"";
        $sentencia = $conexion->prepare("SELECT * FROM tbl_empleados WHERE id = :id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);

        $primernombre = $registro["primernombre"];
        $segundonombre = $registro["segundonombre"];
        $primerapellido = $registro["primerapellido"];
        $segundoapellido = $registro["segundoapellido"];

        $foto = $registro["foto"];
        $cv = $registro["cv"];

        $idpuesto = $registro["idpuesto"];
        $fechadeingreso = $registro["fechadeingreso"];

        $sentencia = $conexion->prepare("SELECT * FROM tbl_puestos");
        $sentencia->execute();
        $lista_tbl_puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            
    }
    

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Recepcionar la informacion

        $txtID = (isset($_POST["txtID"]))?$_POST["txtID"]: "";

        $primernombre = (isset($_POST["primernombre"])?$_POST["primernombre"]: "");
        $segundonombre = (isset($_POST["segundonombre"])?$_POST["segundonombre"]: "");
        $primerapellido = (isset($_POST["primerapellido"])?$_POST["primerapellido"]: "");
        $segundoapellido = (isset($_POST["segundoapellido"])?$_POST["segundoapellido"]: "");

        $idpuesto = (isset($_POST["idpuesto"])?$_POST["idpuesto"]: "");
        $fechadeingreso = (isset($_POST["fechadeingreso"])?$_POST["fechadeingreso"]: "");

        // Preparar la insercion
        $sentencia = $conexion->prepare("UPDATE tbl_empleados 
        SET primernombre = :primernombre, segundonombre = :segundonombre,
        primerapellido = :primerapellido, segundoapellido = :segundoapellido,
        idpuesto = :idpuesto, fechadeingreso = :fechadeingreso WHERE id = :id");

        $sentencia->bindParam(":primernombre", $primernombre);
        $sentencia->bindParam(":segundonombre", $segundonombre);
        $sentencia->bindParam(":primerapellido", $primerapellido);
        $sentencia->bindParam(":segundoapellido", $segundoapellido);

        $sentencia->bindParam(":idpuesto", $idpuesto);
        $sentencia->bindParam(":fechadeingreso", $fechadeingreso);
        $sentencia->bindParam(":id", $txtID);

        $sentencia->execute();


        $foto = (isset($_FILES["foto"]["name"])?$_FILES["foto"]["name"]: "");

        $fecha_ = new DateTime(); // Obtener el tiempo
        
        // Subir la Foto
        // Armar el nuevo nombre del archivo, utilizar el timestamp para que no se sobrescriba con otros existentes
        $nombreArchivo_foto = ($foto != "")?$fecha_->getTimestamp()."_".$_FILES["foto"]["name"]:"";

        // Obtener el nombre temporal de la foto
        $tmp_foto = $_FILES["foto"]["tmp_name"];

        if($tmp_foto != "") {
            move_uploaded_file($tmp_foto,"./".$nombreArchivo_foto);

            $sentencia = $conexion->prepare("SELECT foto FROM tbl_empleados WHERE id = :id");
            $sentencia->bindParam(":id", $txtID);
            $sentencia->execute();
            $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

            // Verificar si la foto existe
            if( isset($registro_recuperado["foto"]) && $registro_recuperado["foto"] != "" ) {
            
                if(file_exists("./".$registro_recuperado["foto"])) {
                unlink("./".$registro_recuperado["foto"]); // borrar la foto anterior
                }
            }

            // Actualizar con la foto nueva
            $sentencia = $conexion->prepare("UPDATE tbl_empleados 
            SET foto = :foto WHERE id = :id");

            $sentencia->bindParam(":foto", $nombreArchivo_foto);
            $sentencia->bindParam(":id", $txtID);
            $sentencia->execute();
        }


        $cv = (isset($_FILES["cv"]["name"])?$_FILES["cv"]["name"]: "");

        $nombreArchivo_cv = ($cv != "")?$fecha_->getTimestamp()."_".$_FILES["cv"]["name"]:"";

        // Obtener el nombre temporal del curriculum
        $tmp_cv = $_FILES["cv"]["tmp_name"];

        if($tmp_cv != "") {
            move_uploaded_file($tmp_cv,"./".$nombreArchivo_cv);

            // Buscar los archivos relacionados con el empleado
            $sentencia = $conexion->prepare("SELECT cv FROM tbl_empleados WHERE id = :id");
            $sentencia->bindParam(":id", $txtID);
            $sentencia->execute();
            $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

            // Verificar si el cv existe
            if( isset($registro_recuperado["cv"]) && $registro_recuperado["cv"] != "" ) {
                
                if(file_exists("./".$registro_recuperado["cv"])) {
                    unlink("./".$registro_recuperado["cv"]); // unlink para borrar el archivo
                }
            }

            // Actualizar con el cv nuevo
            $sentencia = $conexion->prepare("UPDATE tbl_empleados 
            SET cv = :cv WHERE id = :id");

            $sentencia->bindParam(":cv", $nombreArchivo_cv);
            $sentencia->bindParam(":id", $txtID);
            $sentencia->execute();
        }

        

        $mensaje = "Registro Actualizado";
        header("Location: index.php?mensaje=".$mensaje);
    }
?>
<?php include("../../templates/header.php"); ?>

<br/>
<div class="card">
    <div class="card-header">
        Datos del Empleado
    </div>
    <div class="card-body">
        
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="primernombre" class="form-label">ID</label>
                <input type="text" value="<?php echo $txtID; ?>" class="form-control" readonly name="primernombre" id="primernombre" 
                aria-describedby="helpId" placeholder="Primer Nombre">
            </div>

            <div class="mb-3">
                <label for="primernombre" class="form-label">Primer Nombre</label>
                <input type="text" value="<?php echo $primernombre; ?>" class="form-control" name="primernombre" id="primernombre" 
                aria-describedby="helpId" placeholder="Primer Nombre">
            </div>

            <div class="mb-3">
                <label for="segundonombre" class="form-label">Segundo Nombre</label>
                <input type="text" value="<?php echo $segundonombre; ?>" class="form-control" name="segundonombre" id="segundonombre" 
                aria-describedby="helpId" placeholder="Segundo Nombre">
            </div>

            <div class="mb-3">
                <label for="primerapellido" class="form-label">Primer Apellido</label>
                <input type="text" value="<?php echo $primerapellido; ?>" class="form-control" name="primerapellido" id="primerapellido" 
                aria-describedby="helpId" placeholder="Primer Apellido">
            </div>

            <div class="mb-3">
                <label for="segundoapellido" class="form-label">Segundo Apellido</label>
                <input type="text" value="<?php echo $segundoapellido; ?>" class="form-control" name="segundoapellido" id="segundoapellido" 
                aria-describedby="helpId" placeholder="Segundo Apellido">
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <br/>

                <img width="100" src="<?php echo $foto; ?>" class="rounded" alt="" />
                <br/>
                <br/>
                <input type="file" class="form-control" name="foto" id="foto" 
                aria-describedby="helpId" placeholder="Foto">
            </div>

            <div class="mb-3">
                <label for="cv" class="form-label">CV(PDF):</label>
                <br>
                <!-- Link para mostrar el nombre del cv y abrir el archivo -->
                <a href="<?php echo $cv; ?>" target="_blank"> <?php echo $cv; ?> </a>
                <input type="file" class="form-control" name="cv" id="cv" 
                aria-describedby="helpId" placeholder="CV">
            </div>

            <div class="mb-3">
                <label for="idpuesto" class="form-label">Puesto:</label>
                <select class="form-select" name="idpuesto" id="idpuesto">
                    <!-- Cada vez que encuentre un registro va a iterar -->
                    <?php foreach($lista_tbl_puestos as $registro) { ?>
                        <!-- Si el idpuesto existe entonces lo dejo seleccionado -->
                        <option <?php echo ($idpuesto == $registro["id"])?"selected":""; ?> value="<?php echo $registro["id"]; ?>">
                            <?php echo $registro["nombredelpuesto"]; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
              <label for="fechadeingreso" class="form-label">Fecha de Ingreso:</label>
              <input type="date" value="<?php echo $fechadeingreso; ?>" class="form-control" name="fechadeingreso" id="fechadeingreso" aria-describedby="emailHelpId" placeholder="Fecha de Ingreso">
            </div>

            <button type="submit" class="btn btn-success">Actualizar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>


    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../templates/footer.php"); ?>