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
        <script type="text/javascript" src="../js/libs/jquery/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/libs/three/three.js"></script>
        <script type="text/javascript" src="../js/libs/three/MTLLoader.js"></script>
        <script type="text/javascript" src="../js/libs/three/OBJLoader.js"></script>
        <script type="text/javascript">
        	var scene;
	var camera;
	var renderer;
	var controls;
	var objects = [];
	var clock;
	var deltaTime;	
	var keys = {};
	var objetosConColision;
	var raycast;
	

	var isWorldReady = [ false, false ];
	$(document).ready(function() {

		setupScene();

		raycast = new THREE.Raycaster();

		camera.misRayos=[
		new THREE.Vector3(1,0,0),
		new THREE.Vector3(-1,0,0),
		new THREE.Vector3(0,0,1),
		new THREE.Vector3(0,0,-1),
		new THREE.Vector3(0,1,0),
		new THREE.Vector3(0,-1,0)
		];

		loadOBJWithMTL("../assets/", "Nivel1.obj", "Nivel1.mtl", (object) => {	
			object.rotation.y=THREE.Math.degToRad(270);	
			object.scale.set(0.4,0.4,0.4);
			scene.add(object);
			isWorldReady[0] = true;
		});

		loadOBJWithMTL("../assets/", "Jugador1.obj", "Jugador1.mtl", (object) => {
			object.name="jugador1";
			object.position.y = 0.5;
			object.position.z = 8;
			object.position.x = -7.8;
			object.scale.set(0.3,0.3,0.3);
			scene.add(object);
			isWorldReady[1] = true;
		});

		loadOBJWithMTL("../assets/", "Jugador2.obj", "Jugador2.mtl", (object) => {
			object.name="jugador2";
			object.scale.set(0.3,0.3,0.3);
			object.position.y = 0.5;
			object.position.z = -3.5;
			object.position.x = 8;
			scene.add(object);
			isWorldReady[2] = true;
		});

		loadOBJWithMTL("../assets/", "Cristal.obj", "Cristal.mtl", (object) => {	
			//object.rotation.y=THREE.Math.degToRad(270);	
			object.scale.set(0.03,0.03,0.03);
			object.position.y = 1;

			objetosConColision.push(object);
			scene.add(object);
			isWorldReady[3] = true;
		});
/*
		loadOBJWithMTL("assets/", "jetski.obj", "jetski.mtl", (object) => {
			object.position.z = -10;
			object.rotation.x = THREE.Math.degToRad(-90);

			scene.add(object);
			isWorldReady[1] = true;
		});
*/
		render();

		document.addEventListener('keydown', onKeyDown);
		document.addEventListener('keyup', onKeyUp);		
	});

	function loadOBJWithMTL(path, objFile, mtlFile, onLoadCallback) {
		var mtlLoader = new THREE.MTLLoader();
		mtlLoader.setPath(path);
		mtlLoader.load(mtlFile, (materials) => {
			
			var objLoader = new THREE.OBJLoader();
			objLoader.setMaterials(materials);
			objLoader.setPath(path);
			objLoader.load(objFile, (object) => {
				onLoadCallback(object);
			});

		});
	}

	function onKeyDown(event) {
		keys[String.fromCharCode(event.keyCode)] = true;
	}
	function onKeyUp(event) {
		keys[String.fromCharCode(event.keyCode)] = false;
	}

	
	function render() {
		requestAnimationFrame(render);
		deltaTime = clock.getDelta();	
		//------------------------JUGADOR1-----------------------------
		var yaw = 0;
		var forward = 0;
		if (keys["A"]) {
			yaw = 2;
		} else if (keys["D"]) {
			yaw = -2;
		}
		if (keys["W"]) {
			forward = -10;
		} else if (keys["S"]) {
			forward = 10;
		}
		//------------------------JUGADOR2-----------------------------
		var yaw2 = 0;
		var forward2 = 0;
		if (keys["J"]) {
			yaw2 = 2;
		} else if (keys["L"]) {
			yaw2 = -2;
		}
		if (keys["I"]) {
			forward2 = -10;
		} else if (keys["K"]) {
			forward2 = 10;
		}
		if (isWorldReady[0] && isWorldReady[1] && isWorldReady[2]) {

			var j1=scene.getObjectByName("jugador1");
			var j2=scene.getObjectByName("jugador2");
			j1.rotation.y += yaw * deltaTime;
			j1.translateZ(forward * deltaTime);
			j2.rotation.y += yaw2 * deltaTime;
			j2.translateZ(forward2 * deltaTime);
		}
		

		for(var i=0; i<camera.misRayos.length; i++)
		{
			var rayo=camera.misRayos[i];
			raycast.set(camera.position,rayo);
			var colisiones = raycast.intersectObjects(objetosConColision, true); //Detecta si hay colisión o no. Regresa un arreglo con los objetos que tocó
			if(colisiones.length>0){

				if(colisiones[0].distance<1){
					camera.translateZ(-(forward * deltaTime));
					//console.log("colisionando");
				}
			}
		}
		renderer.render(scene, camera);
	}
    
	function setupScene() {		
		//var visibleSize = { width: window.innerWidth, height: window.innerHeight};
        var visibleSize = { width: 1100, height: 500};
		clock = new THREE.Clock();		
		scene = new THREE.Scene();
		camera = new THREE.PerspectiveCamera(38, visibleSize.width / visibleSize.height, 0.1, 100);
		camera.position.z = 25;
		camera.position.y = 8;
		camera.rotation.x=THREE.Math.degToRad(-8);

		renderer = new THREE.WebGLRenderer( {precision: "mediump" } );
		renderer.setClearColor(new THREE.Color(0, 0, 0));
		renderer.setPixelRatio(visibleSize.width / visibleSize.height);
		renderer.setSize(visibleSize.width, visibleSize.height);

		var ambientLight = new THREE.AmbientLight(new THREE.Color(1, 1, 1), 0.8);
		scene.add(ambientLight);

		var directionalLight = new THREE.DirectionalLight(new THREE.Color(1, 0.5, 0), 0.4);
		directionalLight.position.set(0, 0, 1);
		scene.add(directionalLight);

		var grid = new THREE.GridHelper(50, 10, 0xffffff, 0xffffff);
		grid.position.y = -1;
		scene.add(grid);

		$("#scene-section").append(renderer.domElement);
	}


	</script>

    </head>

    <body class="text-center">

        <!--Navbar-->
    <div class="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column">
            <header class="masthead">
                <div class="inner">  
                    <nav class="nav nav-masthead navbar-expand-lg navbar-light bg-light justify-content-center">
                            <h3 class="masthead-brand">Crystals collecters</h3>
                    <a class="nav-link active" href="#">Inicio</a>
                    <a class="nav-link" href="#">Política de privacidad</a>
                    <a class="nav-link" href="#">Puntuaciones</a>
                    </nav>
                </div>
            </header>

        <!--Contenido-->
        <div class="card text-white bg-primary">     
                <br><br>    
                <h5 class="GUI" id="GUI1">Jugador 1</h5>
                <h6 class="GUI" id="GUI2">30 puntos</h6>
                <h5 class="GUI" id="GUI3">Jugador 2</h5>
                <h6 class="GUI" id="GUI4">15 puntos</h6>
            <main role="main" class="inner cover">


                <!--<img src="../images/Juego.png" width="1100" height="500">-->
                <div id="scene-section"/>
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