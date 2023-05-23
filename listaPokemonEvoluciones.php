<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/estilosGeneral.css">
        <title>Los Pokémon</title>
        <meta charset="UTF-8">

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

        
        if (!$mysqli){
            echo "<script>console.log('Conexion a BBDD fallida');</script>";
        }
        else{
            echo "<script>console.log('Conexion a BBDD exitosa');</script>";

            $sql = "SELECT p.nombre AS pokemon, puedeEvolucionar(p.numero_pokedex) AS evoluciona FROM pokemon p";
            echo "<script>console.log('$sql');</script>";

            $resultado = mysqli_query($mysqli, $sql);
            
        }
        
        mysqli_close($mysqli);

        ?>

        <script>
            function ordenar(orden, asc){
                window.location.replace(window.location.href.split('?')[0] + "?orden=" + orden + "&asc=" + asc);
            }

            function filtrar(filtro, max, min, cadena){
                window.location.replace(window.location.href.split('?')[0] + "?filtro=" + filtro + "&max=" + max + "&min=" + min + "&cadena=" + cadena);
            }
        </script>

    </head>
    <body>

        <?php
            include "header.php";
        ?>

        <nav class="barraLateralNavegacion">
            <ul>
                <li><a href='listaPokemon.php'>Pokemon</a></li>
                <li>
                    <ul>
                        <li><a>Ver tipos</a></li>
                        <li><a href="listaPokemonEvoluciones.php">Ver evoluciones</a></li>
                    </ul>
                </li>
                <li></li>
                <li><a href='index.php'>Inicio</a></li>
            </ul>
        </nav>
        
        
        <div class="contenedor">
            <table>
                <tr>
                    <th width="30%">Primera Forma</th>
                    <th width="30%">Primera Evolución</th>
                    <th width="30%">Segunda evolución</th>
                </tr>
                <?php
                $index_evolucion = 0;

                while($fila = mysqli_fetch_assoc($resultado)){
                    if($indexEvolucion == 0){
                        echo "<tr>";
                    }

                    echo "<td>" . $fila["pokemon"] . "</td>";
                    // si el pokemon no puede evolucionar o es ya el tercero de la línea (failsafe) se cierra la fila y se reinicia el contador
                    if($fila["evoluciona"] == 0 || $indexEvolucion == 2){ // la fila 'evoluciona' es 1 si el pokemon puede evolucionar y 0 si no puede
                        echo "</tr>";
                        $indexEvolucion = 0;
                    }
                    else{
                        $indexEvolucion++;
                    }
                }
                
                ?>
            </table>
        </div>
    </body>
</html>