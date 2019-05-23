var activeFacebook = true;
window.fbAsyncInit = function () {
    FB.init({
        appId: '416423365785590', /* Pegar aqui el número de identificador de facebook*/
        xfbml: true,
        version: 'v3.2'
    });
    FB.AppEvents.logPageView();
};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) { return; }
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function shareScore(active, username1, score1, username2, score2) {
    if(active)
    {
            FB.ui({
                method: 'share_open_graph',
                action_type: 'og.likes',
                action_properties: JSON.stringify({
                    object: {
                        'og:url': "http://crystalscollecters.twicky.com.mx",
                        'og:title': "Resultados de Crystals Collecters",
                        'og:description': username1 + " obtuvo: " + score1 + " puntos y " + username2 + ' obtuvo: ' + score2 + " puntos",
                        'og:image': "http://games.twicky.com.mx/crystal-bit/public/images/Juego.png",
                        'og:image:width': 320,
                        'og:image:height': 240,
                        'og:image:type': "image/png"
                    }
                })
            },
                function (response) {
                    // Action after response

                });
        /*
        FB.ui({
            method: 'feed', /* method: 'feed'  solo en el muro
            link: 'http://games.twicky.com.mx/crystal-bit/public/index.php',
            quote: 'Jugador 1: ' + username1 + ': ' + score1 + '\n Jugador 2: ' + username2 + ' : ' + score2,
            picture: 'http://games.twicky.com.mx/crystal-bit/public/media/img/gamercat.jpg'
        });*/
    }
}