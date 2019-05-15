<html>
<head>
<script type="text/javascript" src="../js/libs/jquery/jquery-3.3.1.min.js"></script>
  <script>
  function agregarPuntuaciones(username1,username2,nivel_Act,score1,score2)
  {
    var dataToSend =
    {
        action: 'agregarPartida',
        gamename_01:username1,
        gamepoints_01: score1,
        nivel:nivel_Act,
        gamename_02: username2,
        gamepoints_02: score2
    };

    $.ajax({
            url: 'php/conexion.php',
            async: 'true',
            type: 'POST',
            data: dataToSend,
            dataType: 'json',

            success: function (respuesta) {
                debugger;
                //alert(respuesta);
            },
            error: function (x, h, r) {
                alert("Error: " + x + h + r);
            }
        });  
    } 
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
                    $("#Punt").append("<tr><td><h6>"+respuesta[index].jugador1+"</h6></td><td><h6>"+respuesta[index].jugador2+"</h6></td><td><h6>"+respuesta[index].puntuacion1+"</h6></td><td><h6>"+respuesta[index].puntuacion2+"</h6></td><td><h6>"+respuesta[index].nivel+"</h6></td><tr>");
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

     $('#btnCompartir').click(function(){
      var username1=$('#jugador1').val();
      var username2=$('#jugador2').val();
      var score1=$('#puntuacion1').val();
      var score2=$('#puntuacion2').val();
      var nivelAct=$('#nivelAct').val();
      agregarPuntuaciones(username1,username2,nivelAct,score1,score2);
        });
    });
  </script>
</head>
<body>
  <!--  <div class="fb-share-button" data-href="http://crystalscollecters.twicky.com.mx" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fcrystalscollecters.twicky.com.mx%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
  -->
  <h1>PRUEBAS PUNTUACIONES</h1>
  <input type="text" id="jugador1" placeholder="Jugador1">
  <input type="text" id="jugador2" placeholder="Jugador2">
  <input type="number" id="puntuacion1" placeholder="Puntuacion1">
  <input type="number" id="puntuacion2" placeholder="Puntuacion2">
  <input type="number" id="nivelAct" placeholder="nivel">
<button id="btnCompartir">COMPARTIR</button>
<br>

    <table id="Punt" style="width:100%; margin: 5em;">
        <tr>
                <th><h4>Jugador 1</h4></th>
                <th><h4>Jugador 2</h4></th>
                <th><h4>Puntuación 1</h4></th> 
                <th><h4>Puntuación 2</h4></th> 
                <th><h4>Nivel</h4></th>
        </tr>
    </table>
</body>
</html>