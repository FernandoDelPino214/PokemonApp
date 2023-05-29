<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/estilosInspeccionar.css">
        <?php
            function exception_handler(Throwable $exception) {
                echo "Uncaught exception: " , $exception->getMessage(), "\n";
            }
            
            set_exception_handler('exception_handler');
    
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

            $numPokedex = $_GET['numPokedex'];
            if($numPokedex){
                $sql = "SELECT p.numero_pokedex , p.nombre , p.peso , p.altura , eb.ps , eb.ataque , eb.defensa , eb.especial , eb.velocidad 
                FROM pokemon p, estadisticas_base eb 
                WHERE p.numero_pokedex = eb.numero_pokedex 
                AND p.numero_pokedex = $numPokedex";

                $sqlPreevolucion = "SELECT p.numero_pokedex AS numPokedexPre, p.nombre AS nombrePre
                FROM pokemon p 
                WHERE p.numero_pokedex = preevolucionPokemon($numPokedex)";

                $sqlEvolucion = "SELECT p.numero_pokedex AS numPokedexEvo, p.nombre AS nombreEvo
                FROM pokemon p 
                WHERE p.numero_pokedex = evolucionPokemon($numPokedex)";

                $datosBasicos = mysqli_query($mysqli, $sql);
                $datosBasicos = mysqli_fetch_assoc($datosBasicos);

                $datosPreevolucion = mysqli_query($mysqli, $sqlPreevolucion);
                $datosEvolucion = mysqli_query($mysqli, $sqlEvolucion);
                $datosPreevolucion = mysqli_fetch_assoc($datosPreevolucion);
                $datosEvolucion = mysqli_fetch_assoc($datosEvolucion);
            }

            if($datosBasicos){
                $pokeNum = $datosBasicos['numero_pokedex'];
                $pokeNombre = $datosBasicos['nombre'];
                $pokePeso = $datosBasicos['peso'];
                $pokeAltura = $datosBasicos['altura'];
                $pokePs = $datosBasicos['ps'];
                $pokeAtaque = $datosBasicos['ataque'];
                $pokeDefensa = $datosBasicos['defensa'];
                $pokeEspecial = $datosBasicos['especial'];
                $pokeVelocidad = $datosBasicos['velocidad'];

                echo "<title>$pokeNum. $pokeNombre</title>";
            }

            if($datosPreevolucion){
                $numPokedexPre = $datosPreevolucion['numPokedexPre'];
                $nombrePre = $datosPreevolucion['nombrePre'];
            }
            if($datosEvolucion){
                $numPokedexEvo = $datosEvolucion['numPokedexEvo'];
                $nombreEvo = $datosEvolucion['nombreEvo'];
            }

            mysqli_close($mysqli);

        ?>
        <title>Inspeccionar</title>
    </head>
    <body>
        <?php include "header.php"; ?>
        <div class="contenedor">
            <div class="tarjetaPrincipal">
                <span><h2>Pokémon nº <?php echo $pokeNum;?>:</h2></span>
                <span><h1><?php echo $pokeNombre;?></h1></span>
                <hr width="90%">
                <div class="enLinea">
                    <div class="burbuja">
                        <p>Peso: </p>
                        <p><?php echo $pokePeso;?></p>
                    </div>
                    <div class="burbuja">
                        <p>Altura: </p> 
                        <p><?php echo $pokeAltura;?></p>
                    </div>
                </div>
            </div>
            <div class="tarjetaEstadisticas">
                <h2>Estadísticas base</h2>
                <hr width="80%">
                <div class="estadistica">
                    <h3>PS: </h3><h3><?php echo $pokePs;?></h3>
                </div>
                <div class="estadistica">
                    <h3>Ataque: </h3><h3><?php echo $pokeAtaque;?></h3>
                </div>
                <div class="estadistica">
                    <h3>Defensa: </h3><h3><?php echo $pokeDefensa;?></h3>
                </div>
                <div class="estadistica">
                    <h3>Especial: </h3><h3><?php echo $pokeEspecial;?></h3>
                </div>
                <div class="estadistica">
                    <h3>Velocidad: </h3><h3><?php echo $pokeVelocidad;?></h3>
                </div>
            </div>
            <div class="tarjetaEvoluciones">
                <h4>Preevolución:</h4><h4>Evoluciona a:</h4>
                <hr width="90%">
                <h4><?php if($numPokedexPre){echo "$numPokedexPre. $nombrePre";}else{echo "NaN";} ?></h4>
                <h4><?php if($numPokedexEvo){echo "$numPokedexEvo. $nombreEvo";}else{echo "NaN";} ?></h4>
            </div>
            <div class="botones">
                <?php
                    echo "<a class='burbuja' href='formularioModificarPokemon.php?numPokedex=$pokeNum'><p>Modificar</p></a>";
                    echo "<a class='burbuja' href='preguntaEliminarPokemon.php?numPokedex=$pokeNum'><p>¡Eliminar!</p></a>";
                ?>
            </div>
        </div>
    </body>
</html>