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
        <!-- Styles -->
        
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">

    </head>

    <body class="text-center">

        <!--Navbar-->
    <div class="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column">
    <?php include('navbar.php');?>

 <!-- Large modal -->

 <div class="modalPausa">
     <div class="justify-content-center">
     <button type="button" class="close" data-dismiss="modal1" style="color:white;" aria-label="Close">X</button><br>
          <h1 style="color:white;">GANADOR</h1>
          <br><br><br> 
          <h1 style="color:rgb(255, 123, 0);">Jugador 2</h1> <br>
          <button style=" color: #fff; background-color:rgba(41, 7, 71,0.5);  border-color: #ffffff; border-width: 30%; font-weight: 400; border-radius: 0.60rem;"><h4>Compartir resultado <i class="fab fa-facebook" style="color: white;"></i></h4></button><br>
          <button class="BtnOpcion1"><h4>Reiniciar juego</h4></button>
          <button class="BtnOpcion1"><h4>Salir del juego</h4></button><br><br>
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