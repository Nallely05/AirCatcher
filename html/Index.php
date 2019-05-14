<!doctype html>
<html lang="en">
    <head>
        <title> Crystals collecters </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,600" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="../images/favicon.ico"/>
        <!-- JS -->
        <script type="text/javascript" src="../js/bootstrap.js"></script>
        <script type="text/javascript" src="../js/libs/jquery/jquery-3.3.1.min.js"></script>
        <!-- Styles -->
        
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    </head>

    
    <script>
        $(document).ready(function(){
            function obtener()
            {
                $("#jugador1").val(localStorage.getItem("Jugador1"));
                $("#jugador2").val(localStorage.getItem("Jugador2"));
            }
            obtener();
            $('#btnJugar').click(function(){
               var j1 =$("#jugador1").val();
               var j2 =$("#jugador2").val(); 
               localStorage.setItem("Jugador1",j1);
               localStorage.setItem("Jugador2",j2);
            });
        });
    </script>

    <body class="text-center">

        <!--Navbar-->
    <div class="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column">
            <header class="masthead">
                <div class="inner">  
                    <nav class="nav nav-masthead navbar-expand-lg navbar-light bg-light justify-content-center">
                    <h3 class="masthead-brand" style="font-family: Hobo Std;">Crystals collecters</h3>
                    <a class="nav-link active" href="index">Inicio</a>
                    <a class="nav-link" href="politica">Política de privacidad</a>
                    <a class="nav-link" href="puntuaciones">Puntuaciones</a>
                    </nav>
                </div>
            </header>

        <!--Contenido-->
        <div class="card text-white bg-primary">     
            <div class="card-body">              
            <main role="main" class="inner cover">
                <br>
                <br>
                
                <h1 class="cover-heading">Bienvenido</h1>
                <p class="lead">Crystals collecterses un juego de 1 contra 1 de un mismo teclado. Por favor introduzcan a continuación el nombre con el que se desean identificar:</p>
                <br><br>
                     
                        <table class="tablaPrincipal" style="width:100%">
                            <tr>
                                <th><h3  class="FirstPage" style="color:rgb(132, 0, 255);">Jugador 1</h3></th>
                                <th><h3  class="FirstPage" style="color:rgb(255, 123, 0);">Jugador 2</h3></th>
                                
                            </tr>
                            <tr>
                                <th><input type="text" id="jugador1"></th>
                                <th><input type="text" id="jugador2"></th>
                            </tr>
                        </table><br><br>

                        <button href="ventanaJuego" class="btn btn-lg btn-secondary" id="btnJugar"><h4><strong>JUGAR</strong></h4></button>
                   
            </main>
            <br><br> <br><br> <br>  
            </div>
        </div>
    </div>

        <!--FOOTER-->
   <div class="container-fluid">
        <footer>
            <table class="navbar navbar-expand-lg navbar-light bg-light" style="width:100%">  
                <td><p class="ArrobaHispanofic" style="color:white;">© Crystals collecters 2019</p></td>
            </table>
        </footer>
    </div>

        </body>
</html>