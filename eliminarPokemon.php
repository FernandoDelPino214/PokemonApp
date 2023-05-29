<html>
<head>
    <meta charset="UTF-8">
    <title>Insertando Pokémon...</title>
    <?php 

        function exception_handler(Throwable $exception) {
            echo "No es posible eliminar los pokémon originales. \n
            Ha habido un error y puede que el pokémon no se haya eliminado. \n
            Uncaught exception: " , $exception->getMessage(), "\n";
        }

        set_exception_handler('exception_handler');

        $numPokedex = $_GET['numPokedex'];
        
        $sql = "CALL eliminarPokemon($numPokedex);";
    ?>

</head>
<body>
    <?php
    if($numPokedex){
        try {
            $db = parse_ini_file("../archivoConexion/config.ini");
            $user = $db['user'];
            $pass = $db['pass'];
            $name = $db['name'];
            $host = $db['host'];

            $mysqli = mysqli_connect("$host", "$user", "$pass", "$name");
        } catch (Exception $th) {
            throw $th;
        }
        mysqli_query($mysqli, $sql);
        mysqli_close($mysqli);
        echo "<h1>¡Pokémon eliminado!</h1>";
        echo "<script>window.location.href = 'listaPokemon.php';</script>";
    }
    else{
        echo "Alguno de los parámetros no ha llegado correctamente. <br><a href='listaPokemon.php'>Volver</a>";
    }
    ?>
</body>
</html>