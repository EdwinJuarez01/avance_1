<?php
// Include config file
require_once "base_datos_configuracion.php";
 
// Define variables and initialize with empty values
$nombre_usuario = $direccion_usuario = $membresia_usuario = $rutina_usuario = $comentarios_usuario = "";
$nombre_usuario_err = $direccion_usuario_err = $membresia_usuario_err = $rutina_usuario_err = $comentarios_usuario_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validar nombre
    $nombre = trim($_POST["name"]);
    if(empty($nombre)){
        $nombre_usuario_err = "Ingrese nombre";
    } elseif(!filter_var($nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nombre_usuario_err = "Ingrese nombre válido";
    } else{
        $nombre_usuario = $nombre;
    }
    
    // Validar dirección
    $direccion = trim($_POST["address"]);
    if(empty($direccion)){
        $direccion_usuario_err = "Ingrese una dirección";     
    } else{
        $direccion_usuario = $direccion;
    }
    
    // Validate membresia
    $membresia = trim($_POST["salary"]);
    if(empty($membresia)){
        $membresia_usuario_err = "Ingrese membresia";     
    } elseif(!ctype_digit($membresia)){
        $membresia_usuario_err = "Ingrese un valor entero positivo";
    } else{
        $membresia_usuario = $membresia;
    }
    $rutina = trim($_POST["rutina"]);
    $comentarios = trim($_POST["comentarios"]);
    
    // Check input errors before inserting in database
    if(empty($nombre_usuario_err) && empty($direccion_usuario_err) && empty($membresia_usuario_err)){
        // Prepare an update statement
        $sql = "UPDATE empleados SET nombre_usuario=?, direccion_usuario=?, membresia_usuario=?, rutina_usuario=?, comentario_usuario=? WHERE id_usuario=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssissi", $param_name, $param_address, $param_salary, $param_rutina, $param_comentarios, $param_id);
            
            // Set parameters
            $param_name = $nombre;
            $param_address = $direccion;
            $param_salary = $membresia;
            $param_rutina = $rutina;
            $param_comentarios = $comentarios;
            $param_id = $id;
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Error en conexión.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM empleados WHERE id_usuario = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $nombre = $row["nombre_usuario"];
                    $direccion = $row["direccion_usuario"];
                    $membresia = $row["membresia_usuario"];
                    $rutina_usuario = $row["rutina_usuario"];
                    $comentarios_usuario = $row["comentario_usuario"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Atualizar Registro</h2>
                    </div>
                    <p>Escriba valores a editar</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nombre_usuario_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $nombre; ?>">
                            <span class="help-block"><?php echo $nombre_usuario_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($direccion_usuario_err)) ? 'has-error' : ''; ?>">
                            <label>Dirección</label>
                            <textarea name="address" class="form-control"><?php echo $direccion; ?></textarea>
                            <span class="help-block"><?php echo $direccion_usuario_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($membresia_usuario_err)) ? 'has-error' : ''; ?>">
                            <label>Membresía</label>
                            <input type="text" name="salary" class="form-control" value="<?php echo $membresia; ?>">
                            <span class="help-block"><?php echo $membresia_usuario_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Rutina</label>
                            <input type="text" name="rutina" class="form-control" value="<?php echo $rutina_usuario; ?>">
                        </div>
                        <div class="form-group">
                            <label>Comentarios</label>
                            <input type="text" name="comentarios" class="form-control" value="<?php echo $comentarios_usuario; ?>">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Guardar">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>