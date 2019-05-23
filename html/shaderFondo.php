<!doctype html>
<html lang="en">
    <head>
        <title> Juego </title>
         <!-- SHADER DE CUADRO CON COLORES EN LÍNEAS EN MOVIMIENTO -->
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
	var objJugador=[];
	var JuegoEnProceso= false;
	var particles = new THREE.Geometry;
	var particleTexture =THREE.ImageUtils.loadTexture('../images/arena1.png');
	var particleMaterial = new THREE.ParticleBasicMaterial({ map: particleTexture, transparent: true, size: 2 });
	var particleSystem = new THREE.ParticleSystem(particles, particleMaterial);
	var cristalesEnJuego=20;
	var tiempoEspera=0;
	var shader = true;
	var uniforms;
	var material;
	var pMaterial;
	
	var isWorldReady = [ false, false ];
	$(document).ready(function() 
	{
		setupScene();
		//-------------------SHADER-------------------
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
		//-------------------SHADER-------------------

		render();

		document.addEventListener('keydown', onKeyDown);
		document.addEventListener('keyup', onKeyUp);		
	});
		
	function createElementMaterial() {

		var material = new THREE.MeshBasicMaterial(); // create a material

		var loader = new THREE.TextureLoader().load(
			// resource URL
			"../css/arr.png",
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
	function render(timestamp) {
		
			tiempoEspera=requestAnimationFrame(render);
			uniforms.time.value = timestamp / 1000;
			
			var em = scene.getObjectByName("elmesh");
			var piedra = scene.getObjectByName("lunapiedra");
			var brillo = scene.getObjectByName("lunabrillo");
			em.translate.y += 0.1;
			piedra.rotation.y += 0.02;
			brillo.rotation.y += 0.02;

			if(tiempoEspera>=60 && ocultar==false)
			{
				$("#Boton-IniciarJuego").show();
				$("#loader-4").hide();
				ocultar=true;	
			}
			deltaTime = clock.getDelta();
			particleSystem.rotation.y += deltaTime;	
			juegoTime();
			
			if (keys["G"]) { //32
				JuegoEnProceso=false;
				$(".GUI").hide();
				$("#Menu-Pausa").show();
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
		
		var sphereGeom = new THREE.SphereGeometry(5, 16, 16);
    
		var moonTexture = THREE.ImageUtils.loadTexture( '../css/piedra.jpg' );
		var moonMaterial = new THREE.MeshBasicMaterial( { map: moonTexture } );
    	var moon = new THREE.Mesh(sphereGeom, moonMaterial);
		moon.position.set(0,5,0);
		moon.name = "lunapiedra";
    	scene.add(moon);

    	var customMaterial = new THREE.ShaderMaterial( 
			{
			    uniforms: 
				{ 
					"c":   { type: "f", value: 1.4 },
					"p":   { type: "f", value: 1.4 },
					glowColor: { type: "c", value: new THREE.Color(0xe5740b) },
					viewVector: { type: "v3", value: camera.position }
				},
				vertexShader:   document.getElementById( 'vertexShaderBrillo').textContent,
				fragmentShader: document.getElementById( 'fragmentShaderBrillo').textContent,
				side: THREE.FrontSide,
				blending: THREE.AdditiveBlending,
				transparent: true
			}   );
            
        this.moonGlow = new THREE.Mesh( sphereGeom.clone(), customMaterial.clone() );
        moonGlow.position.set(moon.position.x,moon.position.y, moon.position.z );
        moonGlow.scale.multiplyScalar(1.1);
        moonGlow.name = "lunabrillo";
        scene.add( moonGlow );
		
		
		$("#scene-sectionNv2").append(renderer.domElement);
	}

	</script>

    </head>

    <body class="text-center">
				<!--Navbar-->
			<div class="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column">
			<?php include('navbar.php');?>
						<!--<img src="../images/Juego.png" width="1100" height="500">-->
						<div id="scene-sectionNv2"/>
			</div>

    </body>
</html>