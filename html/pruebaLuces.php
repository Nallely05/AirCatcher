<!doctype html>
<html lang="en">
    <head>
        <title> Prueba Luces </title>
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
    var light1, light2, light3, light4, stats;
    var deltaTime;
    var delta1;
    var object;
    var clock = new THREE.Clock();

    $(document).ready(function() 
	{	
		setupScene();


        loadOBJWithMTL("../assets/", "Nivel1.obj", "Nivel1.mtl", (object) => {	
			object.rotation.y=THREE.Math.degToRad(270);	
			object.scale.set(0.4,0.4,0.4);
			scene.add(object);
			isWorldReady[0] = true;
		});

        function loadOBJWithMTL(path, objFile, mtlFile, onLoadCallback) 
        {
            var mtlLoader = new THREE.MTLLoader();
            mtlLoader.setPath(path);
            mtlLoader.load(mtlFile, (materials) => 
            {
                
                var objLoader = new THREE.OBJLoader();
                objLoader.setMaterials(materials);
                objLoader.setPath(path);
                objLoader.load(objFile, (object) => {
                    onLoadCallback(object);
                });

            });
	    }
        function setupScene() 
        {   		
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

            var directionalLight = new THREE.DirectionalLight(new THREE.Color(1.0, 0.2, 0.2), 0.3);//0.4
            directionalLight.position.set(0, 0, 1);
            scene.add(directionalLight)

           //---------------------------
           var sphere = new THREE.SphereBufferGeometry( 0.5, 16, 8 );
				//lights
				light1 = new THREE.PointLight( 0xff0040, 2, 50 );
				light1.add( new THREE.Mesh( sphere, new THREE.MeshBasicMaterial( { color: 0xff0040 } ) ) );
				scene.add( light1 );
				light2 = new THREE.PointLight( 0x0040ff, 2, 50 );
				light2.add( new THREE.Mesh( sphere, new THREE.MeshBasicMaterial( { color: 0x0040ff } ) ) );
				scene.add( light2 );
				light3 = new THREE.PointLight( 0x80ff80, 2, 50 );
				light3.add( new THREE.Mesh( sphere, new THREE.MeshBasicMaterial( { color: 0x80ff80 } ) ) );
				scene.add( light3 );
				light4 = new THREE.PointLight( 0xffaa00, 2, 50 );
				light4.add( new THREE.Mesh( sphere, new THREE.MeshBasicMaterial( { color: 0xffaa00 } ) ) );
				scene.add( light4 );
				//renderer
				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				document.body.appendChild( renderer.domElement );
				//stats
				var stats = new Stats();
				document.body.appendChild( stats.dom ); 

            $("#scene-sectionNv1").append(renderer.domElement);
        }

        function animate() 
        {
				requestAnimationFrame( animate );
				render();
				stats.update();
		}

        function render() 
        {
            var time = Date.now() * 0.0005;
				var delta1 = clock.getdelta1();
				if ( object ) object.rotation.y -= 0.5 * delta;
				light1.position.x = Math.sin( time * 0.7 ) * 30;
				light1.position.y = Math.cos( time * 0.5 ) * 40;
				light1.position.z = Math.cos( time * 0.3 ) * 30;
				light2.position.x = Math.cos( time * 0.3 ) * 30;
				light2.position.y = Math.sin( time * 0.5 ) * 40;
				light2.position.z = Math.sin( time * 0.7 ) * 30;
				light3.position.x = Math.sin( time * 0.7 ) * 30;
				light3.position.y = Math.cos( time * 0.3 ) * 40;
				light3.position.z = Math.sin( time * 0.5 ) * 30;
				light4.position.x = Math.sin( time * 0.3 ) * 30;
				light4.position.y = Math.cos( time * 0.7 ) * 40;
				light4.position.z = Math.sin( time * 0.5 ) * 30;
				renderer.render( scene, camera );
        }
        render();
    });

</script>

    </head>
    <body class="text-center">
    <div id="scene-sectionNv1"/>
    </body>
    </html>