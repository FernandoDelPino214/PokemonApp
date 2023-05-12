<html>
    <head>
        <title><?php echo $title; ?></title>
        <style type="text/css">
            header{
                background-color: rgb(69, 200, 252);
                position:fixed;
                width:98%;
                min-height: 100px;
                height: 100px;
                max-height: 200px;
                float: right;
                top: 0;
                border: solid black 3px;
                border-radius: 0px 0px 12px 12px;
                
            }


            header a{
                width: 50px;
            }

            header a>div{   
                border-width: 3px;
                border-radius: 8px;
                border: solid;
                border-color: black;
                width: 15%;
                height: min-content;
                float: right;
                margin-top: 15px;
                margin-left: 15px;
                margin-right: 15px;
                padding-left:25px;
                background-color: rgb(45, 17, 228);
                color: white;
                font-family:Verdana, Geneva, Tahoma, sans-serif;
            }

            header a>div:hover{
                cursor: pointer;
                animation: animacionBoton linear 0.3s both;
            }

            @keyframes animacionBoton {
                0%{
                    background-color:rgb(45, 17, 228);
                    border-color: black;
                    color: white;
                }

                100%{
                    border-color:rgb(45, 17, 228);
                    background-color: white;
                    color: black;
                }
            }

            .headerLogo{
                float: left;
                height: 100%;
            }
        </style>
    </head>
    <body>
        <header>
            <div>
                <img src="../img/pokemonLogo.png" class="headerLogo">
            <div>
            
            <a>
                <div>
                    <p>Movimientos</p>
                </div>
            </a>
            
            <a>
                <div>
                    <p>Estadísticas de Combate</p>
                </div>
            </a>
            
            <a href="listaPokemon.php">
                <div>
                    <p>Pokémon</p>
                </div>
            </a>
        </header>
    </body>
</html>