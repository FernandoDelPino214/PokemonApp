<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/estilosFormulario.css">
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

                $datosBasicos = mysqli_query($mysqli, $sql);
                $datosBasicos = mysqli_fetch_assoc($datosBasicos);

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
            }
        ?>  
        <title>Modificar Pokemon</title>
        <script>
            function enviar(){
                numPokedex.disabled = false;
                formulario.submit();
            }
        </script>
    </head>
    <body>
        <?php include "header.php"; ?>
        <div class="contenedor">
            <form id="formulario" action="modificarPokemon.php" method="post">
                <h1>Crea tu Pokémon</h1>
                <label for="numPokedex">Número de pokédex: </label>
                <input <?php if($pokeNum){echo "value='$pokeNum'";}; ?>disabled type="number" name="numPokedex" id="numPokedex" step="1" placeholder="Número en la pokédex">
                <br>
                <label for="nombre">Nombre: </label>
                <input <?php if($pokeNum){echo "value='$pokeNombre'";}; ?> type="text" name="nombre" id="nombre" maxlength="15" placeholder="Nombre del pokémon">

                <br><hr><br>

                <label for="peso">Peso: </label>
                <input <?php if($pokeNum){echo "value='$pokePeso'";}; ?>type="number" name="peso" id="peso" step="0.1" placeholder="Peso del Pokémon en Kg">
                <label for="altura">Altura: </label>
                <input <?php if($pokeNum){echo "value='$pokeAltura'";}; ?> type="number" name="altura" id="altura" step="0.1" placeholder="Altura del Pokémon en metros">
                <br>

                <h2>Estadísticas básicas</h2>

                <label for="ps">PS: </label>
                <input <?php if($pokeNum){echo "value='$pokePs'";}; ?>type="number" step="1" name="ps" id="ps" placeholder="Puntos de Salud">
                <br>
                <label for="ataque">Ataque: </label>
                <input <?php if($pokeNum){echo "value='$pokeAtaque'";}; ?>type="number" step="1" name="ataque" id="ataque" placeholder="Potencia de ataques físicos">
                <br>
                <label for="defensa">Defensa: </label>
                <input <?php if($pokeNum){echo "value='$pokeDefensa'";}; ?>type="number" step="1" name="defensa" id="defensa" placeholder="Defensa en combate">
                <br>
                <label for="especial">Especial: </label>
                <input <?php if($pokeNum){echo "value='$pokeEspecial'";}; ?>type="number" step="1" name="especial" id="especial" placeholder="Potencia de ataques especiales">
                <br>
                <label for="velocidad">Velocidad: </label>
                <input <?php if($pokeNum){echo "value='$pokeVelocidad'";}; ?>type="number" step="1" name="velocidad" id="velocidad" placeholder="Velocidad en combate">
                <br>
                <input type="button" value="Enviar" onclick="enviar()">
            </form>
        </div>
    </body>
</html>