<!doctype html>
<html lang="en">
    <head>
        <title> Juego </title>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,600" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="../images/favicon.ico"/>
        <!-- JS -->
        <script type="text/javascript" src="../js/bootstrap.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        
        <!-- Styles -->
        
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
        <script type="text/javascript" src="../js/libs/jquery/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/libs/three/three.js"></script>
		<script type="text/javascript" src="../js/libs/three/RenderPass.js"></script>
		<script type="text/javascript" src="../js/libs/three/ShaderPass.js"></script>
        <script type="text/javascript" src="../js/libs/three/MTLLoader.js"></script>
        <script type="text/javascript" src="../js/libs/three/OBJLoader.js"></script>
		<script src="../js/facebook.js"></script>
    	<script type="text/javascript">

    var scene;
	var camera;
	var renderer;
	var controls;
	var objects = [];
	var clock;
	var deltaTime;	
	var keys = {};
	var LimitesEscenarioColision=[];
	var basesColision=[];
	var ColisionCristales=[];
	var raycast;
	var objJugador=[];
	var JuegoEnProceso= false;
	var yaw = 0;
	var forward = 0;
	var yaw2 = 0;
	var forward2 = 0;
	var particles = new THREE.Geometry;
	var particleTexture =THREE.ImageUtils.loadTexture('../images/luciernaga.png');
	var particleMaterial = new THREE.ParticleBasicMaterial({ map: particleTexture, transparent: true, size: 0.4 });
	var particleSystem = new THREE.ParticleSystem(particles, particleMaterial);
	var cristalesEnJuego=20;
	var tiempoEspera=0;
	localStorage.setItem("puntuacionJ1Nv3",0);
	localStorage.setItem("puntuacionJ2Nv3",0);
	objJugador[0]={};
	objJugador[1]={};
	objJugador[0].puntuacion=localStorage.getItem("puntuacionJ1Nv3");
	objJugador[1].puntuacion=localStorage.getItem("puntuacionJ2Nv3");
	
	var hasGP = false;
    var repGP;
 
    function canGame() {
        return "getGamepads" in navigator;
    }
	
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
						//alert("Error: " + x + h + r);
					}
				});  
		} 

    function reportOnGamepad() 
	{
        var gp = navigator.getGamepads()[0];
        var gp1 = navigator.getGamepads()[1];
 		
		 //Jugador 1

		yaw = 0;
		forward = 0;
		yaw2 = 0;
		forward2 = 0;
		
		if (gp.buttons[0].pressed) {
			forward = -10;
			} else if (gp.buttons[1].pressed) {
				forward = 10;
			}
		if (gp.buttons[14].pressed) {
			
			yaw = 2;
		} else if (gp.buttons[15].pressed) {
			yaw = -2;
		}

		if (gp1.buttons[0].pressed) {
			forward2 = -10;
			} else if (gp1.buttons[1].pressed) {
				forward2 = 10;
			}
		if (gp1.buttons[14].pressed) {
			
			yaw2 = 2;
		} else if (gp1.buttons[15].pressed) {
			yaw2 = -2;
		}

			//Pausa

			if(gp.buttons[9].pressed)
			{
				JuegoEnProceso=false;	
				$(".GUI").hide();
				$("#Menu-Pausa").show();
			}

			if(gp1.buttons[9].pressed)
			{
				JuegoEnProceso=false;	
				$(".GUI").hide();
				$("#Menu-Pausa").show();
			}	
    }


	function finDelJuego()
		{
			if(!finalizarCompletamente)
			{
			//objJugador[0].puntuacion=5;
			//objJugador[1].puntuacion=15;
			localStorage.setItem("puntuacionJ1Nv3",objJugador[0].puntuacion);
            localStorage.setItem("puntuacionJ2Nv3",objJugador[1].puntuacion);

			var username1=localStorage.getItem("Jugador1");
			var username2=localStorage.getItem("Jugador2");
			var score1n1=localStorage.getItem("puntuacionJ1");
			var score2n1=localStorage.getItem("puntuacionJ2");
			agregarPuntuaciones(username1,username2,1,score1n1,score2n1);

			var score1n2=localStorage.getItem("puntuacionJ1Nv2");
			var score2n2=localStorage.getItem("puntuacionJ2Nv2");
			agregarPuntuaciones(username1,username2,2,score1n2,score2n2);

			var score1n3=localStorage.getItem("puntuacionJ1Nv3");
			var score2n3=localStorage.getItem("puntuacionJ2Nv3");
			agregarPuntuaciones(username1,username2,3,score1n3,score2n3);
			var puntosJ1 = score1n1 + score1n2 + score1n3;
			var puntosJ2 = score2n1 + score2n2 + score2n3;

			if(puntosJ1>puntosJ2)
			{
				$("#ganador").html(localStorage.getItem("Jugador1"));
			}
			if(puntosJ2>puntosJ1)
			{
				$("#ganador").html(localStorage.getItem("Jugador2"));
			}
			if(puntosJ1==puntosJ2)
			{
				$("#ganador").html("EMPATE");
			}

			$(".GUI").hide();
			$("#Menu-FinNivel3").show();
			$("#GUI_FinalJ1Nv3").html(localStorage.getItem("Jugador1")+": "+objJugador[0].puntuacion+" puntos");
			$("#GUI_FinalJ2Nv3").html(localStorage.getItem("Jugador2")+": "+objJugador[1].puntuacion+" puntos");
			finalizarCompletamente = true;
			 }
		}

	function actualizarGUI()
		{
			localStorage.setItem("puntuacionJ1Nv3",objJugador[0].puntuacion);
			localStorage.setItem("puntuacionJ2Nv3",objJugador[1].puntuacion);
			$("#GUIpuntos1Nv3").html(objJugador[0].puntuacion);
			$("#GUIpuntos2Nv3").html(objJugador[1].puntuacion);
		}

	function getRandomPos(max, min) 
	{
		var nAux = 0;
		nAux = Math.random() * (max - min) + min;
		//if (nAux > (max / 2))
		//	nAux = (nAux / 2 * -1);
		return nAux.toFixed(2);
	}
	
	var isWorldReady = [ false, false ];
	$(document).ready(function() 
	{
		$("#cancion").trigger('play');
		$("#Menu-Pausa").hide();
		$(".GUI").hide();
		$("#Menu-Sonido").hide();
		$("#Menu-FinNivel3").hide();
		$("#Boton-IniciarJuego").hide();

		$("#cancion1").click(function(){
			$("#cancion").attr("src","../music/0.mp3");
			$("#cancion").trigger('play');
		});
		$("#cancion2").click(function(){
			$("#cancion").attr("src","../music/1.mp3");
			$("#cancion").trigger('play');
		});
		$("#cancion3").click(function(){
			$("#cancion").attr("src","../music/2.mp3");
			$("#cancion").trigger('play');
		});

		$("#silencio").click(function(){
			$("#cancion").trigger('pause');
		});

		$('#compartirFB').click(function(){
			debugger;
			var username1=$('#GUIplayer1Nv3').val();
			var username2=$('#GUIplayer2Nv3').val();
			var score1 = 0;
			var score2 = 0;
			score1 =localStorage.getItem("puntuacionJ1") + localStorage.getItem("puntuacionJ1Nv2") + localStorage.getItem("puntuacionJ1Nv3");
			score2 =localStorage.getItem("puntuacionJ2") + localStorage.getItem("puntuacionJ2Nv2") + localStorage.getItem("puntuacionJ2Nv3");
			shareScore(true,username1,score1,username2,score2);
		});
		
		
        if(canGame()) 
		{
 
			var prompt = "To begin using your gamepad, connect it and press any button!";
			$("#gamepadPrompt").text(prompt);

			$(window).on("gamepadconnected", function() {
				hasGP = true;
				//alert("Gamepad connected!");
				//console.log("connection event");
				repGP = window.setInterval(reportOnGamepad,100);
			});

			$(window).on("gamepaddisconnected", function() {
				//console.log("disconnection event");
				//$("#gamepadPrompt").text(prompt);
				window.clearInterval(repGP);
			});

			//setup an interval for Chrome
			var checkGP = window.setInterval(function() {
				//console.log('checkGP');
				if(navigator.getGamepads()[0] && navigator.getGamepads()[1]) {
					if(!hasGP) $(window).trigger("gamepadconnected");
					window.clearInterval(checkGP);
				}
			}, 500);
		}


		$("#Boton-IniciarJuego").click(function()
		{
			$("#Menu-Instrucciones").hide();
			$(".GUI").show();
			JuegoEnProceso=true;
		});
		
		$("#Boton-ContinuarPartida").click(function()
		{
			$("#Menu-Pausa").hide();
			$(".GUI").show();
			JuegoEnProceso=true;
		});

		$("#Boton-OpcSonido").click(function()
		{
			$("#Menu-Pausa").hide();
			$("#Menu-Sonido").show();
		});

		$("#CerrarMenuSonido").click(function()
		{
			$("#Menu-Sonido").hide();
			$("#Menu-Pausa").show();
		});

		function obtener()
            {
				objJugador[0].puntuacion=localStorage.getItem("puntuacionJ1Nv3");
				objJugador[1].puntuacion=localStorage.getItem("puntuacionJ2Nv3");
				$("#GUIplayer1Nv3").html(localStorage.getItem("Jugador1"));
				$("#GUIplayer2Nv3").html(localStorage.getItem("Jugador2")); 
				$("#GUIpuntos1Nv3").html(objJugador[0].puntuacion);
				$("#GUIpuntos2Nv3").html(objJugador[1].puntuacion);
            }
			obtener();
		
		setupScene();

		objJugador[0].raycast = new THREE.Raycaster();
		objJugador[1].raycast = new THREE.Raycaster();

		objJugador[0].misRayos=[
		new THREE.Vector3(1,0,0),
		new THREE.Vector3(-1,0,0),
		new THREE.Vector3(0,0,1),
		new THREE.Vector3(0,0,-1),
		new THREE.Vector3(0,1,0),
		new THREE.Vector3(0,-1,0)
		];

		objJugador[1].misRayos=[
		new THREE.Vector3(1,0,0),
		new THREE.Vector3(-1,0,0),
		new THREE.Vector3(0,0,1),
		new THREE.Vector3(0,0,-1),
		new THREE.Vector3(0,1,0),
		new THREE.Vector3(0,-1,0)
		];

		//--------------------------------------CUBOS LIMITES DE ESCENARIO-------------------------------------------------------
		var geometry = new THREE.BoxGeometry(1,1,1);
		var material = new THREE.MeshLambertMaterial({
				color: new THREE.Color(0.1294117647058824,0.5882352941176471,0.9529411764705882),opacity:0.0,transparent:true
			});
		var cube1 = new THREE.Mesh(geometry, material);
		var cube2 = cube1.clone();
		var cube3 = cube1.clone();
		var cube4 = cube1.clone();
		var cube5 = cube1.clone();
		var cube6 = cube1.clone();
		var cube7 = cube1.clone();
		var cube8 = cube1.clone();
		var cube9 = cube1.clone();
		var cube10 = cube1.clone();
		var cube11 = cube1.clone();
		var cube12 = cube1.clone();

		cube1.name="Limite1";
		cube2.name="Limite2";
		cube3.name="Limite3";
		cube4.name="Limite4";
		cube5.name="BaseJugador1";
		cube6.name="BaseJugador2";
		cube7.name="Pino1";
		cube8.name="Pino2";
		cube9.name="Pino3";
		cube10.name="Pino4";
		cube11.name="Pino5";
		cube12.name="Pino6";
		
		cube1.position.y = 2;
		cube1.position.z = -5.4;
		cube1.scale.set(19,5,0.5);
		LimitesEscenarioColision.push(cube1);

		cube2.rotation.y=THREE.Math.degToRad(90);
		cube2.position.y = 2;
		cube2.position.x = -9.6;
		cube2.scale.set(18,5,0.5);
		LimitesEscenarioColision.push(cube2);

		
		cube3.rotation.y=THREE.Math.degToRad(90);
		cube3.position.y = 2;
		cube3.position.x = 9.6;
		cube3.scale.set(18,5,0.5);
		LimitesEscenarioColision.push(cube3);
		
		cube4.position.y = 2;
		cube4.position.z = 10;
		cube4.scale.set(19,5,0.5);
		LimitesEscenarioColision.push(cube4);

		cube7.position.set(-7.8,2,-1.5);
		cube7.scale.y=4;
		LimitesEscenarioColision.push(cube7);

		cube8.position.set(4.4,2,-2.3);
		cube8.scale.set(1.5,4,1.5);
		LimitesEscenarioColision.push(cube8);

		cube9.position.set(-2.2,2,1);
		cube9.scale.set(1.5,4,1.5);
		LimitesEscenarioColision.push(cube9);

		cube10.position.set(7.8,2,5);
		cube10.scale.set(1.5,4,1.5);
		LimitesEscenarioColision.push(cube10);

		cube11.position.set(1.1,2,7.4);
		cube11.scale.set(1.5,4,1.5);
		LimitesEscenarioColision.push(cube11);

		cube12.position.set(-5.3,2,7.7);
		cube12.scale.set(1.5,4,1.5);
		LimitesEscenarioColision.push(cube12);

		//------------BASES-----------
		cube5.position.y = 1;
		cube5.position.z = 8;
		cube5.position.x = -7.8;
		cube5.scale.set(1.15,1.3,1.1);
		basesColision.push(cube5);

		cube6.position.y = 1;
		cube6.position.z = -3.5;
		cube6.position.x = 8;
		cube6.scale.set(1.15,1.3,1.1);
		basesColision.push(cube6);
		//------------BASES-----------
		
		scene.add(cube1);
		scene.add(cube2);
		scene.add(cube3);
		scene.add(cube4);
		scene.add(cube5);
		scene.add(cube6);
		scene.add(cube7);
		scene.add(cube8);
		scene.add(cube9);
		scene.add(cube10);
		scene.add(cube11);
		scene.add(cube12);
		//------------------------------------------------------------------------------------------------------------------------

		loadOBJWithMTL("../assets/", "Nivel3.obj", "Nivel3.mtl", (object) => {	
			object.rotation.y=THREE.Math.degToRad(270);	
			object.scale.set(0.4,0.4,0.4);
			scene.add(object);
			isWorldReady[0] = true;
		});

		loadOBJWithMTL("../assets/", "J1.obj", "J1.mtl", (object) => {
			object.name="jugador1";
			object.position.y = 0.5;
			object.position.z = 8;
			object.position.x = -7.8;
			object.scale.set(0.3,0.3,0.3);
			objJugador[0].Globo=object;
			scene.add(objJugador[0].Globo);

		//	scene.add(object);
			isWorldReady[1] = true;
		});

		loadOBJWithMTL("../assets/", "J2.obj", "J2.mtl", (object) => {
			object.name="jugador2";
			object.scale.set(0.3,0.3,0.3);
			object.position.y = 0.5;
			object.position.z = -3.5;
			object.position.x = 8;
			objJugador[1].Globo=object;
			scene.add(objJugador[1].Globo);
		//	scene.add(object);
			isWorldReady[2] = true;
		});

		loadOBJWithMTL("../assets/", "Cristal.obj", "Cristal.mtl", (object) => {	
			//object.rotation.y=THREE.Math.degToRad(270);	
			
			object.position.y = 0.5;
			for(var i=0; i<20; i++)
			{
				object.position.x=getRandomPos(7,-7.6);
				object.position.z=getRandomPos(8,2);
				object.scale.set(0.03,0.03,0.03);
				object.name="Cristal-"+i;
				ColisionCristales.push(object.clone());
				scene.add(ColisionCristales[i]);
			}
			isWorldReady[3] = true;
		});
		
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
	var segundos=60;
	var minutos=3600;
	var tiempoJuegoMinutos=1;
	var tiempoJuegoSegundos=0;
	var contTiempoJuegoMinutos=minutos;
	var contTiempoJuegoSegundos=segundos;
	var finalizarCompletamente = false;
	function juegoTime()
	{	
		if(JuegoEnProceso)
		{
			contTiempoJuegoMinutos--;
			contTiempoJuegoSegundos--;
		}
			if(contTiempoJuegoSegundos<=0)
			{
				if(tiempoJuegoSegundos<=0)
				{
					tiempoJuegoSegundos=59;
				}
				tiempoJuegoSegundos--;
				contTiempoJuegoSegundos=segundos;
			}
			
			
			if(contTiempoJuegoMinutos<=0)
			{
				if(tiempoJuegoMinutos<=0)
				{
					tiempoJuegoMinutos=59;
				}
				tiempoJuegoMinutos--;
				contTiempoJuegoMinutos=minutos;
			}
			if(tiempoJuegoMinutos<=0 && tiempoJuegoSegundos<=0)
			{
				$("#Tiempo").html("00:00");
				JuegoEnProceso=false;
				finDelJuego();
			}
			if(JuegoEnProceso)
			$("#Tiempo").html("0"+tiempoJuegoMinutos+":"+tiempoJuegoSegundos);	

	}
	var ocultar=false;
	function render() {
		
		tiempoEspera=requestAnimationFrame(render);
		if(tiempoEspera>=60 && ocultar==false)
		{
			$("#Boton-IniciarJuego").show();
			$("#loader-4").hide();
			ocultar=true;
			
		}

			deltaTime = clock.getDelta();	
            particleSystem.rotation.y += deltaTime;
			juegoTime();
			

			//console.log("Tiempo:"+tiempoJuegoSegundos);
			//------------------------JUGADOR1-----------------------------
			
			yaw = 0;
			forward = 0;
			yaw2 = 0;
			forward2 = 0;

			if (keys["N"]) {
				JuegoEnProceso=false;
				finDelJuego();
			}
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

			//-----------------------PAUSA------------------------------
			if (keys["G"]) { //32
				JuegoEnProceso=false;
				$(".GUI").hide();
				$("#Menu-Pausa").show();
			}
			//pausarJuego()
		
			if (isWorldReady[0] && isWorldReady[1] && isWorldReady[2] && isWorldReady[3]) {

				var j1=scene.getObjectByName("jugador1");
				var j2=scene.getObjectByName("jugador2");
				if(JuegoEnProceso){
				objJugador[0].Globo.rotation.y += yaw * deltaTime;
				objJugador[0].Globo.translateZ(forward * deltaTime);
				objJugador[1].Globo.rotation.y += yaw2 * deltaTime;
				objJugador[1].Globo.translateZ(forward2 * deltaTime);

					if(cristalesEnJuego<=0)
						{
							JuegoEnProceso=false;
							finDelJuego();
						}		
				}

			//console.log("Jugador"+objJugador[0].Globo.position);
			for(var i=0; i<2; i++)
			{
				for(var j=0; j<objJugador[i].misRayos.length; j++)
				{
					var rayo=objJugador[i].misRayos[j];
					objJugador[i].raycast.set(objJugador[i].Globo.position,rayo);
					var colisionCristal= objJugador[i].raycast.intersectObjects(ColisionCristales,true);//El true es para buscar a los hijos de los modelos por si no estan combine
					if(colisionCristal.length>0 && colisionCristal[0].distance<2000)
						{
							var cristalTomado=colisionCristal[0].object;
							cristalTomado.scale.set(0.03,0.03,0.03);
							cristalTomado.position.y = 1;
							objJugador[i].Globo.add(cristalTomado);	
							
						}
					var colisiones = objJugador[i].raycast.intersectObjects(LimitesEscenarioColision, true); //Detecta si hay colisión o no. Regresa un arreglo con los objetos que tocó
					if(colisiones.length>0 && colisiones[0].distance<1)
						{
							if(i==0)
							{
							objJugador[0].Globo.translateZ(-(forward * deltaTime));
							}
							else{
								objJugador[1].Globo.translateZ(-(forward2 * deltaTime));

								}
						}
						var colisionConBase = objJugador[i].raycast.intersectObjects(basesColision, true); //Detecta si hay colisión o no. Regresa un arreglo con los objetos que tocó
						if(colisionConBase.length>0 && colisionConBase[0].distance<1)
						{
							var contarCristal;
							for(var n=0; n< objJugador[i].Globo.children.length;n++)
							{
								var nombreHijo=objJugador[i].Globo.children[n].name;
								if(nombreHijo.substring(0,8) == "Diamonds")
								{
									contarCristal=n;
									scene.remove(objJugador[i].Globo.children[n]);
									objJugador[i].puntuacion++;
									objJugador[i].Globo.children.splice(n,1);
									cristalesEnJuego--;	
									actualizarGUI();
								}
							} //debugger;
						}
					//	{
							//alert("Colisiona");
							/*if(i==0)
							{
							objJugador[0].Globo.translateZ(-(forward * deltaTime));
							}
							else{
								objJugador[1].Globo.translateZ(-(forward2 * deltaTime));
							}*/
						//}
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

		var ambientLight = new THREE.AmbientLight(new THREE.Color(1.0, 1.0, 1.0), 0.3);
		scene.add(ambientLight);

		var directionalLight = new THREE.DirectionalLight(new THREE.Color(0.2, 0.2, 1.0), 0.4);//0.4
		directionalLight.position.set(0, 0, 1);
		scene.add(directionalLight);

		var Light =new THREE.HemisphereLight(0x000051, 0x2D3E94, 1);
		scene.add(Light);


		for (var p = 0; p < 60; p++) 
        {
            var particle = new THREE.Vector3(Math.random() * 40 - 10, Math.random() * 40 - 10, Math.random() * 40 - 10);
            particles.vertices.push(particle);
        }
        particleSystem.rotation.y += clock.getDelta();
      
        scene.add(particleSystem);
		$("#scene-sectionNv3").append(renderer.domElement);
	}


	</script>

    </head>

    <body class="text-center">
	<audio autoplay loop controls hidden id="cancion" src="../music/2.mp3" style="z-index:99; color:white;">
    </audio>
				<!--Navbar-->
			<div class="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column">
			<?php include('navbar.php');?>

				<!--Contenido-->

				<!-- ************************MENUS*********************** -->
				<div class="modalPausa" id="Menu-Instrucciones" style="top:10%;">
					<div class="justify-content-center">
					<!--<button type="button" class="close" data-dismiss="modal1" style="color:white;" aria-label="Close" id="Cerrar-Instrucciones">X</button><br>-->
					<h2 style="color:white;">NIVEL 3</h2><br><br>
						<h5 style="color:white;">Recolecta la mayor cantidad de gemas posibles llevándolas a tu base para ganar a tu contrincante.</h5><br>
						<h5 style="color:white;">Para que los cristales cuenten cada jugador debe volver a su base.</h5><br>
						<h5 style="color:white;">Para pausar el juego pulsa "G"</h5><br>
						<h4 style="color:red;">ATENCIÓN:</h4><h4 style="color:white;">Cuidado con colisionar con los pinos del área de juego.</h4>
						<button class="BtnBegin" id="Boton-IniciarJuego"><h3>Empezar</h3></button><br><br>
						<div class="loader" id="loader-4">
						<span></span>
						<span></span>
						<span></span>
						</div><br>
					</div>
				</div>


				<div class="modalPausa" id="Menu-Pausa">
								<div class="justify-content-center">
								<button type="button" class="close" data-dismiss="modal1" style="color:white;" id="CerrarPausaYcontinuar" aria-label="Close">X</button><br>
									<h1 style="color:white;">PAUSA</h1>
									<br>
									<button class="BtnOpcion" id="Boton-ContinuarPartida"><h4>Continuar partida</h4></button><br><br>
									<button class="BtnOpcion" id="Boton-Reiniciar" onclick="location='ventanaJuegoNv3.php'"><h4>Reiniciar nivel</h4></button><br><br>
									<button class="BtnOpcion" id="Boton-OpcSonido"><h4>Opciones de sonido</h4></button><br><br>
									<button class="BtnOpcion" id="Boton-Salir" onclick="location='index.php'"><h4>Salir del juego</h4></button><br><br><br><br> <!--Volver al inicio-->
								</div>
							</div>

				<div class="modalPausa" id="Menu-Sonido">
					<div class="justify-content-center">
					<button type="button" class="close" id="CerrarMenuSonido"  data-dismiss="modal1" style="color:white;" aria-label="Close">X</button><br>
						<h1 style="color:white;">SONIDO</h1>
						<br>
						<button class="BtnOpcion" id="cancion1"><h4>Canción 1</h4></button><br><br>
						<button class="BtnOpcion" id="cancion2"><h4>Canción 2</h4></button><br><br>
						<button class="BtnOpcion" id="cancion3"><h4>Canción 3</h4></button><br><br>
						<button class="BtnOpcion" id="silencio"><h4>Silenciar</h4></button><br>
					<br><br><br><br>
					</div>
				</div>

				<div class="modalPausa" id="Menu-FinNivel3">
					<div class="justify-content-center">
						<h1 style="color:white;">RESULTADOS DE CRYSTALS COLLECTERS</h1>
						<br><br><br> 
						<h1 style="color:red;">Ganador:</h1> <h1 style="color:white;" id="ganador"></h1><br>
						<!--<h1 id="GUI_FinalJ1Nv3"style="color:white;">Jugador 1:</h1> <h1 id="GUI_FinalJ2Nv3" style="color:white;">Jugador 2:</h1> <br>-->
						<button class="BtnOpcion" style="margin:8px;" id="compartirFB"><h4>Compartir partida <i class="fab fa-facebook" style="color: white;"></i></h4></button><br>
						<div id="fb-root"></div>
						<button class="BtnOpcion" id="Boton-ContinuarNv2" onclick="location='Puntuaciones.php'" style="margin:8px;"><h4>Ver puntuaciones</h4></button><br>
						<button class="BtnOpcion" onclick="location='ventanaJuego.php'" style="margin:8px;"><h4>Reiniciar juego</h4></button><br>
						<button class="BtnOpcion" onclick="location='index.php'" style="margin:8px;"><h4>Salir del juego</h4></button><br><br>
					</div>
				</div>
				<!-- ************************GUI*********************** -->
				<div class="card text-white bg-primary">     
						<br><br>    
						<h5 class="GUI" id="GUIplayer1Nv3" style="color: rgb(132, 0, 255);font-family: Hobo Std;"></h5>
						<h6 class="GUI" id="GUIpuntos1Nv3" style="color: rgb(243, 222, 203);"></h6>
						<h3 class="GUI" id="Tiempo" style="color: black;"></h3>
						<!--<button id="BotonTiempo">Pausa</button>-->
						<h5 class="GUI" id="GUIplayer2Nv3" style="color: rgb(255, 123, 0);font-family: Hobo Std;"></h5>
						<h6 class="GUI" id="GUIpuntos2Nv3" style="color: rgb(243, 222, 203);"></h6>
					<main role="main" class="inner cover">
						<div id="scene-sectionNv3"/>
				</main>
					<br><br> <br><br> <br>  
				</div>
			</div>
    </body>
</html>