<?php 

    include("../../bd.php");

    if(isset($_GET["txtID"])){
        $txtID = (isset($_GET["txtID"]))?$_GET["txtID"]:"";
        $sentencia = $conexion->prepare("DELETE FROM tbl_puestos WHERE id = :id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $mensaje = "Registro Eliminado";
        header("Location: index.php?mensaje=".$mensaje);
    }

    $sentencia = $conexion->prepare("SELECT * FROM tbl_puestos");
    $sentencia->execute();
    $lista_tbl_puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    

?>
<?php include("../../templates/header.php"); ?>


    <br/>
    <h3>Puestos</h3>
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
                            <th scope="col">Nombre del Puesto</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Cada vez que encuentre un registro va a iterar -->
                        <?php foreach($lista_tbl_puestos as $registro) { ?>

                            <tr class="">
                            <td scope="row"><?php echo $registro["id"]; ?></td>
                            <td><?php echo $registro["nombredelpuesto"]; ?></td>
                            <td>
                                <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro["id"];?>" role="button">Editar</a>
                                | 
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