<html>
<head>
    <title>¿Borrar pokemon?</title>
    <script>
        function eliminar(numPokedex){
            window.location.href = "eliminarPokemon.php?numPokedex=" + numPokedex;
        }
    </script>
</head>
<body>
    <h1>¿Estás seguro?</h1>
    <button onclick="eliminar(<?php echo $_GET['numPokedex'];  ?>)">Sí</button>
</body>
</html>