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
        <!-- Styles -->
        
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
        <script type="text/javascript" src="../js/libs/jquery/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/libs/three/three.js"></script>
        <script type="text/javascript" src="../js/libs/three/MTLLoader.js"></script>
        <script type="text/javascript" src="../js/libs/three/OBJLoader.js"></script>

		<!-- SHADERS -->
	<script type="text/javascript">
	var colorcillo;

	</script>
	
	<script type="x-shader/x-vertex" id="vertexShaderBrillo">
		uniform vec3 viewVector;
		uniform float c;
		uniform float p;
		varying float intensity;
		void main() 
		{
    		vec3 vNormal = normalize( normalMatrix * normal );
			vec3 vNormel = normalize( normalMatrix * viewVector );
			intensity = pow( c - dot(vNormal, vNormel), p );
	
    		gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );
		}
	</script>

	<script type="x-shader/x-vertex" id="fragmentShaderBrillo">
		uniform vec3 glowColor;
		varying float intensity;
		void main() 
		{
			vec3 glow = glowColor * intensity;
    		gl_FragColor = vec4( glow, 1.0 );
		}
	</script>

	<script id="vertexShader" type="x-shader/x-vertex">
		varying vec2 vUv;
			void main()	{
				vUv = uv;
				gl_Position = vec4( position, 1.0 );
			}
	</script>

	<script id="fragmentShader" type="x-shader/x-fragment">
		varying vec2 vUv;
		uniform float time;
		void main()	{
			vec2 p = - 1.0 + 2.0 * vUv;
			float a = time * 40.0;
			float d, e, f, g = 1.0 / 40.0 ,h ,i ,r ,q;
			e = 400.0 * ( p.x * 0.5 + 0.5 );
			f = 400.0 * ( p.y * 0.5 + 0.5 );
			i = 200.0 + sin( e * g + a / 150.0 ) * 20.0;
			d = 200.0 + cos( f * g / 2.0 ) * 18.0 + cos( e * g ) * 7.0;
			r = sqrt( pow( abs( i - e ), 2.0 ) + pow( abs( d - f ), 2.0 ) );
			q = f / r;
			e = ( r * cos( q ) ) - a / 2.0;
			f = ( r * sin( q ) ) - a / 2.0;
			d = sin( e * g ) * 176.0 + sin( e * g ) * 164.0 + r;
			h = ( ( f + d ) + a / 2.0 ) * g;
			i = cos( h + r * p.x / 1.3 ) * ( e + e + a ) + cos( q * g * 6.0 ) * ( r + h / 3.0 );
			h = sin( f * g ) * 144.0 - sin( e * g ) * 212.0 * p.x;
			h = ( h + ( f - e ) * q + sin( r - ( a + h ) / 7.0 ) * 10.0 + i / 4.0 ) * g;
			i += cos( h * 2.3 * sin( a / 350.0 - q ) ) * 184.0 * sin( q - ( r * 4.3 + a / 12.0 ) * g ) + tan( r * g + h ) * 184.0 * cos( r * g + h );
			i = mod( i / 5.6, 256.0 ) / 64.0;
			if ( i < 0.0 ) i += 4.0;
			if ( i >= 2.0 ) i = 4.0 - i;
			d = r / 350.0;
			d += sin( d * d * 8.0 ) * 0.52;
			f = ( sin( a * g ) + 1.0 ) / 2.0;
			gl_FragColor = vec4( vec3( f * i / 1.6, i / 2.0 + d / 13.0, i ) * d * p.x + vec3( i / 1.3 + d / 8.0, i / 2.0 + d / 18.0, i ) * d * ( 1.0 - p.x ), 1.0 );
		}
	</script>


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
	var ColisionCristalesFalso=[];
	var raycast;
	var objJugador=[];
	var JuegoEnProceso= false;
	var yaw = 0;
	var forward = 0;
	var yaw2 = 0;
	var forward2 = 0;
	var particles = new THREE.Geometry;
	var particleTexture =THREE.ImageUtils.loadTexture('../images/arena1.png');
	var particleMaterial = new THREE.ParticleBasicMaterial({ map: particleTexture, transparent: true, size: 2 });
	var particleSystem = new THREE.ParticleSystem(particles, particleMaterial);
	var cristalesEnJuego=20;
	var tiempoEspera=0;

	//var puntuacionJ1Nv2,puntuacionJ2Nv2;

	localStorage.setItem("puntuacionJ1Nv2",0);
	localStorage.setItem("puntuacionJ2Nv2",0);
	objJugador[0]={};
	objJugador[1]={};
	objJugador[0].puntuacion=localStorage.getItem("puntuacionJ1Nv2");
	objJugador[1].puntuacion=localStorage.getItem("puntuacionJ2Nv2");
	
	var hasGP = false;
    var repGP;
 
    function canGame() {
        return "getGamepads" in navigator;
    }
 
    function reportOnGamepad() 
	{
        var gp = navigator.getGamepads()[0];
        var gp1 = navigator.getGamepads()[1];
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
			$(".GUI").hide();
			$("#Menu-FinNivel2").show();
			$("#GUI_FinalJ1Nv2").html(localStorage.getItem("Jugador1")+": "+objJugador[0].puntuacion+" puntos");
			$("#GUI_FinalJ2Nv2").html(localStorage.getItem("Jugador2")+": "+objJugador[1].puntuacion+" puntos");
			//objJugador[0].puntuacion=5;
			//objJugador[1].puntuacion=15;
			localStorage.setItem("puntuacionJ1Nv2",objJugador[0].puntuacion);
            localStorage.setItem("puntuacionJ2Nv2",objJugador[1].puntuacion);
		}

	function actualizarGUI()
		{
			localStorage.setItem("puntuacionJ1Nv2",objJugador[0].puntuacion);
			localStorage.setItem("puntuacionJ2Nv2",objJugador[1].puntuacion);
			$("#GUIpuntos1Nv2").html(objJugador[0].puntuacion);
			$("#GUIpuntos2Nv2").html(objJugador[1].puntuacion);
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
		$("#Menu-FinNivel2").hide();
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
					if(!hasGP)
					{$(window).trigger("gamepadconnected");} 
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
				objJugador[0].puntuacion=localStorage.getItem("puntuacionJ1Nv2");
				objJugador[1].puntuacion=localStorage.getItem("puntuacionJ1Nv2");
				$("#GUIplayer1Nv2").html(localStorage.getItem("Jugador1"));
				$("#GUIplayer2Nv2").html(localStorage.getItem("Jugador2")); 
				$("#GUIpuntos1Nv2").html(objJugador[0].puntuacion);
				$("#GUIpuntos2Nv2").html(objJugador[1].puntuacion);
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
		cube1.name="Limite1";
		cube2.name="Limite2";
		cube3.name="Limite3";
		cube4.name="Limite4";
		cube5.name="BaseJugador1";
		cube6.name="BaseJugador2";
		
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
		//------------------------------------------------------------------------------------------------------------------------

		loadOBJWithMTL("../assets/", "Nivel21.obj", "Nivel21.mtl", (object) => {	
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

		loadOBJWithMTL("../assets/", "CristalFalso.obj", "CristalFalso.mtl", (object) => {	
			
			object.position.y = 0.5;

			for(var i=0; i<10; i++)
			{
				object.position.x=getRandomPos(7,-7.6);
				object.position.z=getRandomPos(8,2);
				object.scale.set(0.03,0.03,0.03);
				object.name="Cristal-Falso-"+i;
				ColisionCristalesFalso.push(object.clone());
				scene.add(ColisionCristalesFalso[i]);
			}
			isWorldReady[4] = true;
		});

		//-------------------COSOS DE SHADER-------------------
		//var geometry = new THREE.PlaneBufferGeometry(2, 2);
		var geometry = new THREE.BoxGeometry( 2, 2, 2 );

		uniforms = {
					time: { value: 1.0 }
				};

		material = new THREE.ShaderMaterial( {
					uniforms: uniforms,
					vertexShader: document.getElementById( 'vertexShader' ).textContent,
					fragmentShader: document.getElementById( 'fragmentShader' ).textContent
				} );

		var mesh = new THREE.Mesh( geometry, material );
		mesh.name = "elmesh";
		scene.add( mesh );
		//-------------------COSOS DE SHADER-------------------

		render();

		document.addEventListener('keydown', onKeyDown);
		document.addEventListener('keyup', onKeyUp);		
	});
		
	function createElementMaterial() {

		var material = new THREE.MeshBasicMaterial(); // create a material

		var loader = new THREE.TextureLoader().load(
			// resource URL
			"css/arr.png",
			// Function when resource is loaded
			function ( texture ) {
				// do something with the texture
					texture.wrapS = THREE.RepeatWrapping;
					texture.wrapT = THREE.RepeatWrapping;
					texture.offset.x = 90/(2*Math.PI);
					material.map = texture; // set the material's map when when the texture is loaded
			},
			// Function called when download progresses
			function ( xhr ) {
				console.log( (xhr.loaded / xhr.total * 100) + '% loaded' );
			},
			// Function called when download errors
			function ( xhr ) {
				console.log( 'An error happened' );
			}
		);
		return material; // return the material
	}

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
			{$("#Tiempo").html("0"+tiempoJuegoMinutos+":"+tiempoJuegoSegundos);	}

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
		
			if (isWorldReady[0] && isWorldReady[1] && isWorldReady[2] && isWorldReady[3] && isWorldReady[4]) {

				//--------------------SHADER--------------------
				var em = scene.getObjectByName("elmesh");
				em.position.set(0,0,0);
				//--------------------SHADER--------------------

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
					var colisionCristalFalso= objJugador[i].raycast.intersectObjects(ColisionCristalesFalso,true);//El true es para buscar a los hijos de los modelos por si no estan combine
					if(colisionCristalFalso.length>0 && colisionCristalFalso[0].distance<2000)
						{
							var cristalTomado=colisionCristalFalso[0].object;
							cristalTomado.position.y = -2;
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
							for(var n=0; n<objJugador[i].Globo.children.length;n++)
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
							} 
						}
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

		var ambientLight = new THREE.AmbientLight(new THREE.Color(1.0, 1.0, 1.0), 0.6);
		scene.add(ambientLight);

		var directionalLight = new THREE.DirectionalLight(new THREE.Color(1.0, 0.2, 0.2), 0.3);//0.4
		directionalLight.position.set(0, 0, 1);
		scene.add(directionalLight);

		for (var p = 0; p < 180; p++) 
        {
            var particle = new THREE.Vector3(Math.random() * 40 - 10, Math.random() * 40 - 10, Math.random() * 40 - 10);
            particles.vertices.push(particle);
        }
        particleSystem.rotation.x += clock.getDelta();
      
        scene.add(particleSystem);
		$("#scene-sectionNv2").append(renderer.domElement);
	}

	</script>

    </head>

    <body class="text-center">
	<audio autoplay loop controls hidden id="cancion" src="../music/1.mp3" style="z-index:99; color:white;">
    </audio>
				<!--Navbar-->
			<div class="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column">
			<?php include('navbar.php');?>

				<!--Contenido-->

				<!-- ************************MENUS*********************** -->
				<div class="modalPausa" id="Menu-Instrucciones" style="top:16%;">
					<div class="justify-content-center">
					<!--<button type="button" class="close" data-dismiss="modal1" style="color:white;" aria-label="Close" id="Cerrar-Instrucciones">X</button><br>-->
						<h2 style="color:white;">NIVEL 2</h2><br><br>
						<h5 style="color:white;">Recolecta la mayor cantidad de gemas posibles llevándolas a tu base para ganar a tu contrincante.</h5><br>
						<h5 style="color:white;">Para que los cristales cuenten cada jugador debe volver a su base.</h5><br>
						<h5 style="color:white;">Para pausar el juego pulsa "G"</h5><br>
						<h4 style="color:red;">ATENCIÓN:</h4><h4 style="color:white;">Cuidado con los cristales falsos de este nivel.</h4>
						<button class="BtnBegin" id="Boton-IniciarJuego"><h3>Empezar</h3></button><br>
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
									<button class="BtnOpcion" id="Boton-Reiniciar" onclick="location='ventanaJuegoNv2.php'"><h4>Reiniciar nivel</h4></button><br><br>
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

				<div class="modalPausa" id="Menu-FinNivel2">
					<div class="justify-content-center">
						<h1 style="color:white;">RESULTADOS NIVEL 2</h1>
						<br><br><br> 
						<h1 id="GUI_FinalJ1Nv2"style="color:white;">Jugador 1:</h1> <h1 id="GUI_FinalJ2Nv2" style="color:white;">Jugador 2:</h1> <br>
						<!--<button style=" color: #fff; background-color:rgba(41, 7, 71,0.5);  border-color: #ffffff; border-width: 30%; font-weight: 400; border-radius: 0.60rem;"><h4>Compartir resultado <i class="fab fa-facebook" style="color: white;"></i></h4></button><br>
						--><div id="fb-root"></div>
						<button class="BtnOpcion1" id="Boton-ContinuarNv2" onclick="location='ventanaJuegoNv3.php'"><h4>Pasar a Nivel 3</h4></button>
						<button class="BtnOpcion1" onclick="location='ventanaJuegoNv2.php'"><h4>Reiniciar nivel</h4></button>
						<button class="BtnOpcion1" onclick="location='index.php'"><h4>Salir del juego</h4></button><br><br>
					</div>
				</div>
				<!-- ************************GUI*********************** -->


				<div class="card text-white bg-primary">     
						<br><br>    
						<h5 class="GUI" id="GUIplayer1Nv2" style="color: rgb(132, 0, 255);font-family: Hobo Std;"></h5>
						<h6 class="GUI" id="GUIpuntos1Nv2" style="color: rgb(243, 222, 203);"></h6>
						<h3 class="GUI" id="Tiempo" style="color: black;"></h3>
						<!--<button id="BotonTiempo">Pausa</button>-->
						<h5 class="GUI" id="GUIplayer2Nv2" style="color: rgb(255, 123, 0);font-family: Hobo Std;"></h5>
						<h6 class="GUI" id="GUIpuntos2Nv2" style="color: rgb(243, 222, 203);"></h6>
					<main role="main" class="inner cover">


						<!--<img src="../images/Juego.png" width="1100" height="500">-->
						<div id="scene-sectionNv2"/>
				</main>
					<br><br> <br><br>
					<h6>Crédito a <a href=https://es.pngtree.com>Gráficos de pngtree.com</a> por las partículas de este nivel</h6>
				</div>
			</div>

    </body>
</html>