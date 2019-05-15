<!doctype html>
<html lang="en">
    <head>
        <title> Puntuaciones </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,600" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="../images/favicon.ico"/>  
        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
        <!-- JS -->
        <script type="text/javascript" src="../js/bootstrap.js"></script>
        <script type="text/javascript" src="../js/libs/jquery/jquery-3.3.1.min.js"></script>
  <script>
  
    function obtenerPuntuaciones()
    {
        var dataToSend =
    {
        action: 'obtenerPuntuaciones'
    };

    $.ajax({
            url: 'php/conexion.php',
            async: 'true',
            type: 'POST',
            data: dataToSend,
            dataType: 'json',

            success: function (respuesta) {
                for (index = 0; index < respuesta.length; ++index)
                {
                    $("#Punt").append("<tr><td><h6>"+respuesta[index].nivel+"</h6></td><td><h6>"+respuesta[index].jugador1+"</h6></td><td><h6>"+respuesta[index].puntuacion1+"</h6></td></tr><tr><td><h6>"+respuesta[index].nivel+"</h6></td><td><h6>"+respuesta[index].jugador2+"</h6></td><td><h6>"+respuesta[index].puntuacion2+"</h6></td><tr>");
                }
            },
            error: function (x, h, r) {
                alert("Error: " + x + h + r);
            }
        });  
    };
</script>
  
  <title>jQuery Example</title>
  <script>

    $(document).ready(function() {
        obtenerPuntuaciones();
    });
  </script>    
    </head>

    <body class="text-center">

        <!--Navbar-->
    <div class="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column">
    <?php include('navbar.php');?>

        <!--Contenido-->
        <div class="card text-white bg-primary">     
            <div class="card-body">  
                <div class="justify-content-md-center politica">  
                    <div class="tabla-puntuaciones">     
                    <h2>PUNTUACIONES</h2> <br>
                    <table id="Punt" style="width:100%;">
                            <tr>
                                    <th><h4>Nivel</h4></th>
                                    <!--<th><h4>&nbsp&nbsp&nbsp&nbsp&nbspJugadores</h4></th>-->
                                    <th><h4>Jugadores</h4></th>
                                    <th><h4>Puntuación</h4></th> 
                            </tr>
                    </table>
                   
                    </div>
                </div>
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