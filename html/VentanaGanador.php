<!doctype html>
<html lang="en">
    <head>
        <title> Ganador </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,600" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="../images/favicon.ico"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        
        <!-- JS -->
        <script type="text/javascript" src="../js/bootstrap.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="../js/facebook.js"></script>
        <!-- Styles -->
        
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">

    </head>

    <body class="text-center">

        <!--Navbar-->
    <div class="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column">
    <?php include('navbar.php');?>

 <!-- Large modal -->

 <div class="modalPausa" id="Menu-FinNivel3">
					<div class="justify-content-center">
						<h1 style="color:white;">RESULTADOS</h1>
						<br><br><br> 
						<h1 style="color:red;">Ganador:</h1> <h1 style="color:white;" id="ganador"></h1><br>
						<!--<h1 id="GUI_FinalJ1Nv3"style="color:white;">Jugador 1:</h1> <h1 id="GUI_FinalJ2Nv3" style="color:white;">Jugador 2:</h1> <br>-->
						<button class="BtnOpcion" style="margin:8px;"><h4>Compartir partida <i class="fab fa-facebook" style="color: white;"></i></h4></button><br>
						<div id="fb-root"></div>
						<button class="BtnOpcion" id="Boton-ContinuarNv2" onclick="location='Puntuaciones.php'" style="margin:8px;"><h4>Ver puntuaciones</h4></button><br>
						<button class="BtnOpcion" onclick="location='ventanaJuego.php'" style="margin:8px;"><h4>Reiniciar juego</h4></button><br>
						<button class="BtnOpcion" onclick="location='index.php'" style="margin:8px;"><h4>Salir del juego</h4></button><br><br>
					</div>
				</div>

 <!--Intento Pop up-->


        <!--Contenido-->
        <div class="card text-white bg-primary">     
                <br><br>    
                <main role="main" class="inner cover">
                        <img src="../images/Juego.png" width="1100" height="500">
                    </main>
            <br><br> <br><br> <br>  
          
        </div>
    </div>

  

        </body>
</html>