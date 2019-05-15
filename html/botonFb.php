<html>
<head>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <link rel="stylesheet" href="style.css" />
  <title>jQuery Example</title>
  <script>
    $(document).ready(function() {
        $.ajaxSetup({ cache: true });
        $.getScript('https://connect.facebook.net/en_US/sdk.js', function(){
            FB.init({
            appId: '416423365785590',
            version: 'v2.7' // or v2.1, v2.2, v2.3, ...
            });     
            $('#loginbutton,#feedbutton').removeAttr('disabled');
            FB.getLoginStatus(updateStatusCallback);
        });
     
    });
  </script>
</head>
<body>
    <div class="fb-share-button" data-href="http://crystalscollecters.twicky.com.mx" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fcrystalscollecters.twicky.com.mx%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
</body>
</html>