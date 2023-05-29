<html>
    <head>
        <meta charset="UTF-8">
        <title>Crear un Pokémon</title>
        <link rel="stylesheet" type="text/css" href="css/estilosFormulario.css">
    </head>
    <body>
        <?php include "header.php"; ?>
        <div class="contenedor">
            <form action="insertarPokemon.php" method="post">
                <h1>Crea tu Pokémon</h1>
                <label for="numPokedex">Número de pokédex: </label>
                <input type="number" name="numPokedex" id="numPokedex" step="1" placeholder="Número en la pokédex">
                <br>
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" id="nombre" maxlength="15" placeholder="Nombre del pokémon">

                <br><hr><br>

                <label for="peso">Peso: </label>
                <input type="number" name="peso" id="peso" step="0.1" placeholder="Peso del Pokémon en Kg">
                <label for="altura">Altura: </label>
                <input type="number" name="altura" id="altura" step="0.1" placeholder="Altura del Pokémon en metros">
                <br>

                <h2>Estadísticas básicas</h2>

                <label for="ps">PS: </label>
                <input type="number" step="1" name="ps" id="ps" placeholder="Puntos de Salud">
                <br>
                <label for="ataque">Ataque: </label>
                <input type="number" step="1" name="ataque" id="ataque" placeholder="Potencia de ataques físicos">
                <br>
                <label for="defensa">Defensa: </label>
                <input type="number" step="1" name="defensa" id="defensa" placeholder="Defensa en combate">
                <br>
                <label for="especial">Especial: </label>
                <input type="number" step="1" name="especial" id="especial" placeholder="Potencia de ataques especiales">
                <br>
                <label for="velocidad">Velocidad: </label>
                <input type="number" step="1" name="velocidad" id="velocidad" placeholder="Velocidad en combate">
                <br>
                <input type="submit" value="Enviar">
            </form>
        </div>
    </body>
</html>