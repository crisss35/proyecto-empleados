<?php
    include("../../bd.php");

    if(isset($_GET["txtID"])){
        $txtID = (isset($_GET["txtID"]))?$_GET["txtID"]:"";

        // Buscar los archivos relacionados con el empleado
        $sentencia = $conexion->prepare("SELECT foto, cv FROM tbl_empleados WHERE id = :id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

        // Verificar si la foto existe
        if( isset($registro_recuperado["foto"]) && $registro_recuperado["foto"] != "" ) {
            
            if(file_exists("./".$registro_recuperado["foto"])) {
                unlink("./".$registro_recuperado["foto"]); // unlink para borrar el archivo
            }
        }

        // Verificar si el cv existe
        if( isset($registro_recuperado["cv"]) && $registro_recuperado["cv"] != "" ) {
            
            if(file_exists("./".$registro_recuperado["cv"])) {
                unlink("./".$registro_recuperado["cv"]); // unlink para borrar el archivo
            }
        }

        $sentencia = $conexion->prepare("DELETE FROM tbl_empleados WHERE id = :id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $mensaje = "Registro Eliminado";
        header("Location: index.php?mensaje=".$mensaje);
    }

    // Select para empleados y Select para el nombre del puesto (Subconsulta), no altera al primer select
    $sentencia = $conexion->prepare("SELECT *,

    (SELECT nombredelpuesto FROM tbl_puestos WHERE tbl_puestos.id = tbl_empleados.idpuesto LIMIT 1) AS puesto
    
    FROM tbl_empleados");

    $sentencia->execute();
    $lista_tbl_empleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include("../../templates/header.php"); ?>

    <br/>
    <h3>Empleados</h3>
    <div class="card">
        <div class="card-header">
            <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Registro</a>
        </div>
        <div class="card-body">

            <div class="table-responsive-sm">
                <table class="table" id="tabla_id">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Foto</th>
                            <th scope="col">CV</th>
                            <th scope="col">Puesto</th>
                            <th scope="col">Fecha de Ingreso</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Cada vez que encuentre un registro va a iterar -->
                        <?php foreach($lista_tbl_empleados as $registro) { ?>
                            <tr class="">
                                <td><?php echo $registro["id"]; ?></td>
                                <td scope="row">
                                    <?php echo $registro["primernombre"]; ?>
                                    <?php echo $registro["segundonombre"]; ?>
                                    <?php echo $registro["primerapellido"]; ?>
                                    <?php echo $registro["segundoapellido"]; ?>
                                </td>
                                <td>
                                    <img width="65" 
                                    src="<?php echo $registro["foto"]; ?>" 
                                    class="img-fluid rounded" alt="" />
                                </td>
                                <td><a href="<?php echo $registro["cv"]; ?>" target="_blank">
                                    <?php echo $registro["cv"]; ?>
                                    </a>
                                </td>
                                <td><?php echo $registro["puesto"]; ?></td>
                                <td><?php echo $registro["fechadeingreso"]; ?></td>
                                <td>
                                    <a class="btn btn-success" href="carta_recomendacion.php?txtID=<?php echo $registro["id"];?>" target="_blank" role="button">Carta</a>
                                    <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro["id"];?>" role="button">Editar</a>
                                    <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro["id"];?>);" role="button">Eliminar</a>
                            </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

<?php include("../../templates/footer.php"); ?>