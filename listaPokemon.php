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


        // arrays usados para hacer cabezeras clickables para la ordenación por columnas de manera procedural a partir de los arrays

        // en este array se pone el nombre de las columnas en la BBDD
        $columnas = array("numero_pokedex","nombre","peso","altura");

        // en este array se pone el nombre que aparecerá en la página
        $nomColumnas = array("Nº Pokedex", "Pokemon", "Peso", "Altura");

        // en este array se pone los valores en porcentaje que se usaran en el width
        $widthColumnas = array(20, 40, 15, 15);

        // array usado para elegir que tipo de filtrado usará
        $tipoFiltrado = array("numero", "texto", "numero", "numero");


        
        if($_GET['filtro']){
            $filtro = $_GET['filtro'];
            for($i = 0; $i < count($tipoFiltrado); $i++){
                if($columnas[$i] == $_GET['filtro']){
                    $tipoFiltroActual = $tipoFiltrado[$i];
                    echo "<script>console.log('$tipoFiltroActual')</script>";
                }
            }

            if($tipoFiltroActual == "numero"){
                $max = $_GET['max'];
                $min = $_GET['min'];
            }
            else{
                $cadena = $_GET['cadena'];
            }
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

            $sql = "SELECT * FROM pokemon";

            if($tipoFiltroActual){
                if($tipoFiltroActual == "numero"){
                    if(!$max){
                        $max = 9999999;
                    }
                    if(!$min){
                        $min = 0;
                    }

                    $sql = $sql . " WHERE $filtro BETWEEN $min AND $max";
                }
            }

            if($orden){
                $sql = $sql . " ORDER BY $orden " . ($asc ? "ASC" : "DESC");
            }

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
                        <li><a>Ver evoluciones</a></li>
                    </ul>
                </li>
                <li></li>
                <li><a href='index.php'>Inicio</a></li>
            </ul>
        </nav>

        <nav class="barraLateralFiltros">
            <select name="slctFiltro" id="slctFiltro">
                <option disabled selected>Elige un campo: </option>
                <?php
                    for($i = 0; $i < count($columnas); $i++){
                        echo "<option value='$columnas[$i]'>$nomColumnas[$i]</option>";
                    }
                ?>
            </select>
            <br>
            <h4>Filtros numéricos</h4>
            <label for="inptMax">Valor máximo: </label>
            <input type="number" name="inptMax" id="inptMax" step="0.1">
            <br>
            <label for="inptMin">Valor mínimo: </label>
            <input type="number" name="inptMin" id="inptMin" step="0.1">
            <br><hr>
            <h4>Filtro de texto</h4>
            <label for="inptCadena">Contiene: </label>
            <input type="text" name="inptCadena" id="inptCadena" maxlenght="25">
            <br><hr>
            <button onclick="filtrar(slctFiltro.value, inptMax.value, inptMin.value, inptCadena.value)">Filtrar</button>
            <br><br>

        </nav>

        <div class="contenedor">
            <table>
                <tr>
                    <?php

                    // este index es necesario para que todos los arrays estén coordinados
                    $index = 0;

                    foreach($columnas as $col){
                        echo "<th width='$widthColumnas[$index]%' name='$col' id='$col'
                        onclick='ordenar(\"$col\", \"" . ($asc && $orden == $col ? "false" : "true") . "\")'>";
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
                    /*
                    $numPokedex = $fila["numero_pokedex"];
                    $pokemon = $fila["nombre"];
                    $peso = $fila["peso"];
                    $altura = $fila["altura"];
                    */
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