<html>
<head>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <link rel="stylesheet" href="style.css" />
  <title>jQuery Example</title>
  <script src="../js/facebook.js"></script>
  <script>

    $(document).ready(function() {
     $('#btnCompartir').click(function(){
      var username1=$('#jj1').val();
      var username2=$('#jj2').val();
      var score1=$('#ppuntuacion1').val();
      var score2=$('#ppuntuacion2').val();
      shareScore(true,username1,score1,username2,score2);
    });

    });
  </script>
</head>
<body>
  <!--  <div class="fb-share-button" data-href="http://crystalscollecters.twicky.com.mx" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fcrystalscollecters.twicky.com.mx%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
  -->
  <input type="text" id="jj1" placeholder="Jugador1">
  <input type="text" id="jj2" placeholder="Jugador2">
  <input type="text" id="ppuntuacion1" placeholder="Puntuacion1">
  <input type="text" id="ppuntuacion2" placeholder="Puntuacion2">
<button id="btnCompartir">COMPARTIR</button>
</body>
</html>