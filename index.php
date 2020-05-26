<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gimnasio Fitness</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Bienvenido al Gimnasio virtual "FITNESS", podrá registrarse, consultar su rutina y escribir un comentario</h2>

                        <a href="crear_cliente.php" class="btn btn-success pull-right">Agregar usuario</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "base_datos_configuracion.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM empleados";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Nombre</th>";
                                        echo "<th>Dirección</th>";
                                        echo "<th>Membresia</th>";
                                        echo "<th>Rutina</th>";
                                        echo "<th>Comentarios</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id_usuario'] . "</td>";
                                        echo "<td>" . $row['nombre_usuario'] . "</td>";
                                        echo "<td>" . $row['direccion_usuario'] . "</td>";
                                        echo "<td>" . $row['membresia_usuario'] . "</td>";
                                        echo "<td>" . $row['rutina_usuario'] . "</td>";
                                        echo "<td>" . $row['comentario_usuario'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='actualizar_usuario.php?id=". $row['id_usuario'] ."' title='Actulizar registro' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='eliminar.php?id=". $row['id_usuario'] ."' title='Borrar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No se encontraron registros.</em></p>";
                        }
                    } else{
                        echo "ERROR: No se pudo conectar a base de datos $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>