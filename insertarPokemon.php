<html>
<head>
    <meta charset="UTF-8">
    <title>Insertando Pokémon...</title>
    <?php 

        function exception_handler(Throwable $exception) {
            echo "Ha habido un error y puede que el pokémon no se haya añadido. \n
            Uncaught exception: " , $exception->getMessage(), "\n";
        }

        set_exception_handler('exception_handler');

        $numPokedex = $_POST['numPokedex'];
        $nombre = $_POST['nombre'];
        $peso = $_POST['peso'];
        $altura = $_POST['altura'];
        $ps = $_POST['ps'];
        $ataque = $_POST['ataque'];
        $defensa = $_POST['defensa'];
        $especial = $_POST['especial'];
        $velocidad = $_POST['velocidad'];

        $sql = "CALL insertarPokemon($numPokedex, '$nombre', $peso, $altura, $ps, $ataque, $defensa, $especial, $velocidad);";
    ?>

</head>
<body>
    <?php
    if($numPokedex && $nombre && $peso && $altura && $ps && $ataque && $defensa && $especial && $velocidad){
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
        echo "<h1>¡Pokémon Añadido!</h1>";
        echo "<script>window.location.href = 'listaPokemon.php';</script>";
    }
    else{
        echo "Alguno de los parámetros no ha llegado correctamente. <br><a href='formularioInsertarPokemon.php'>Volver</a>";
    }
    ?>
</body>
</html>