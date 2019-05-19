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
        
        <script type="text/javascript">
        var scene;
        var camera;
        var renderer;   
        var clock;
	    var deltaTime;	
        var particles = new THREE.Geometry;
        var particleTexture =THREE.ImageUtils.loadTexture('../images/Hoja.png');
        var particleMaterial = new THREE.ParticleBasicMaterial({ map: particleTexture, transparent: true, size: 5 });
        var particleSystem = new THREE.ParticleSystem(particles, particleMaterial);
         
       

   
        $(document).ready(function() 
	{
        setupScene();       
       
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

		var ambientLight = new THREE.AmbientLight(new THREE.Color(1.0, 1.0, 1.0), 0.5);
		scene.add(ambientLight);

        var grid = new THREE.GridHelper(50, 10, 0xffffff, 0xffffff);
		grid.position.y = -1;
		scene.add(grid);
        
        
   
        
        for (var p = 0; p < 2000; p++) 
        {
            var particle = new THREE.Vector3(Math.random() * 500 - 250, Math.random() * 500 - 250, Math.random() * 500 - 250);
            particles.vertices.push(particle);
        }
        
      
        particleSystem.rotation.y += clock.getDelta();
      
        scene.add(particleSystem);
        $("#scene-sectionNv1").append(renderer.domElement);
        }
        render();
        });

        function render() {
            requestAnimationFrame(render);
            var delta = clock.getDelta();
            particleSystem.rotation.y += delta;
            renderer.render(scene, camera);
        }

        </script>
    </head>

    <body>
    <div id="scene-sectionNv1"/>
    </body>
</html>