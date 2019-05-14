<!doctype html>
<html lang="en">
    <head>
        <title> Juego </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,600" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="../images/favicon.ico"/>
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

 <div class="modalPausa" style="top:12%;">
     <div class="justify-content-center">
     <button type="button" class="close" data-dismiss="modal1" style="color:white;" aria-label="Close">X</button><br>
          <h2 style="color:white;">INSTRUCCIONES</h2><br>
          <h6 style="color:white;">Recolecta la mayor cantidad de gemas posibles llevándolas a tu base para ganar a tu contrincante.</h6><br>
          <h5 style="color:white;">El jugador 1 deberá utilizar las teclas ASDW para moverse, mientras el jugador 2 deberá utilizar JKLI</h5><br>
          <img src="../images/teclas.png"><br><br>
          <button class="BtnBegin"><h3>Empezar</h3></button><br><br>
    </div>
</div>

 <!-- <button onclick="myFunction()">Try it</button>
    <script>
        function myFunction() {
          var x = document.getElementById("Intento");
          if (x.style.display === "none") {
            x.style.display = "block";
          } else {
            x.style.display = "none";
          }
        }
</script>-->


 <!--Intento Pop up-->


        <!--Contenido-->
        <div class="card text-white bg-primary">     
                <br><br>    
            <main role="main" class="inner cover">
                <canvas id="canvas" width="1100" height="500">
                Tu navegador no admite el elemento &lt;canvas&gt;.
                </canvas>
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