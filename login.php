<?php
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        include("./bd.php");

        // Selecciona los registros y cuentalos
        $sentencia = $conexion->prepare("SELECT *, COUNT(*) as n_usuarios 
        FROM tbl_usuarios WHERE usuario = :usuario and password = :contrasenia");

        $usuario = $_POST["usuario"];
        $contrasenia = $_POST["contrasenia"];

        $sentencia->bindParam(":usuario", $usuario);
        $sentencia->bindParam(":contrasenia", $contrasenia);

        $sentencia->execute();

        $registro = $sentencia->fetch(PDO::FETCH_LAZY);
        
        // Cuando es > 1 implica que se puede loguar
        if($registro["n_usuarios"] > 0) { // Preguntar numero de usuarios (los que existen)
            $_SESSION["usuario"] = $registro["usuario"]; // Si existe guardar el registro en variable session
            $_SESSION["logueado"] = true;
            header("Location: index.php"); // Redireccionar al usuario al index
        }else{
            // Si no existe mostrara un mensaje de error
            $mensaje = "Error: El usuario o contraseña son incorrectos";
        }
    }
    

?>

<!doctype html>
<html lang="en">

<head>
  <title>Login</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main class="container">

    <div class="row">
        <div class="col-md-4">

        </div>

        <div class="col-md-4">
            <br><br>
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <?php if(isset($mensaje)) { // Si el mensaje existe se mostrara ?>
                    <div class="alert alert-danger" role="alert">
                        <strong><?php echo $mensaje; ?></strong>
                    </div>
                    <?php } ?>

                    <form action="" method="post">
                        <div class="mb-3">
                          <label for="usuario" class="form-label">Usuario:</label>
                          <input type="text"
                            class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Ingrese el Usuario">
                        </div>

                        <div class="mb-3">
                          <label for="contrasenia" class="form-label">Contraseña:</label>
                          <input type="password"
                            class="form-control" name="contrasenia" id="contrasenia" aria-describedby="helpId" placeholder="Ingrese la Contraseña">
                        </div>

                        <button type="submit" class="btn btn-primary">Entrar al Sistema</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            
        </div>    
    </div>
    


  </main>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>