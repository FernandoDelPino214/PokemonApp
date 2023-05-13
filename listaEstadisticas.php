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
        

        if($_GET['orden']){
            $orden = $_GET['orden'];
            if($_GET['asc'] == "true"){
                $asc = true;
            }
        }

        
        if (!$mysqli){
            echo "<script>console.log('Conexion a BBDD fallida');</script>";
        }
        else{
            echo "<script>console.log('Conexion a BBDD exitosa');</script>";

            $sql = "SELECT p.numero_pokedex , p.nombre , eb.ps , eb.ataque ,eb.defensa , eb.especial , eb.velocidad 
                FROM pokemon p INNER JOIN estadisticas_base eb ON p.numero_pokedex = eb.numero_pokedex ";
            if($orden){
                $sql = $sql . " ORDER BY $orden " . ($asc ? "ASC" : "DESC");
            }

            $resultado = mysqli_query($mysqli, $sql);
            
        }
        
        mysqli_close($mysqli);

        ?>

        <script>
            function recargar(orden, asc){
                window.location.replace("listaEstadisticas.php?orden=" + orden + "&asc=" + asc);
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
                        <li><a>Ver evoluciones</a></li>
                    </ul>
                </li>
                <li></li>
                <li><a href='index.php'>Inicio</a></li>
            </ul>
        </nav>
        <div class="contenedor">
            <table>
                <tr>
                    <?php
                    // codigo para hacer cabezeras clickables para la ordenación por columnas de manera procedural a partir de los arrays

                    // en este array se pone el nombre de las columnas en la BBDD
                    $columnas = array("numero_pokedex","nombre","ps","ataque","defensa","especial","velocidad");

                    // en este array se pone el nombre que aparecerá en la página
                    $nomColumnas = array("Nº Pokedex", "Pokemon", "PS", "Ataque","Defensa","Especial","Velocidad");

                    // en este array se pone los valores en porcentaje que se usaran en el width
                    $widthColumnas = array(15, 15, 12, 12, 12, 12, 12);

                    // este index es necesario para que todos los arrays estén coordinados
                    $index = 0;

                    foreach($columnas as $col){
                        echo "<th width='$widthColumnas[$index]%' name='$col' id='$col'
                        onclick='recargar(\"$col\", \"" . ($asc && $orden == $col ? "false" : "true") . "\")'>";
                        /*                                                                      ^
                        //                                                                      |
                        //                                                                      |
                        si se está ordenando la tabla por esta columna y además está ordenando -/
                        ascendentemente entonces al clickar otra vez ordenará descendientemente.
                        en cualquier otro case se ordena ascendientemente
                        */
                        echo $nomColumnas[$index] . "  ";
                        

                        // si esta columna es la que se está usando actualmente para ordenar, pon la imagen de la flecha
                        // mirando hacia arriba o hacia abajo
                        if($orden == $col){
                            echo "<img width='15' src='../img/" . ($asc ? "up" : "down") .".png'>";
                        }
                        echo "</th>";

                        $index ++;
                    }

                    ?>
                </tr>
                <?php
                // codigo para el resto de la tabla
                while($fila = mysqli_fetch_assoc($resultado)){
                    echo "<tr height='25px'>";
                    foreach ($fila as $campo) {
                        echo "<td>$campo</td>";
                    }
                    //echo "<td><a href='prueba4.php?numPokedex=$numPokedex&pokemon=$pokemon&peso=$peso&altura=$altura'><img src='../img/config.png' width='25'></a></td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </body>
</html>