<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="../js/libs/jquery/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="../js/libs/three/three.js"></script>
    <script type="text/javascript" src="../js/libs/three/MTLLoader.js"></script>
    <script type="text/javascript" src="../js/libs/three/OBJLoader.js"></script>
</head> 


<script type="x-shader/x-vertex" id="vertexShader">
        attribute float vertexDisplacement;
        uniform float delta;
        varying float vOpacity;
        varying vec3 vUv;

        void main()
        {
        vUv=position;
        vOpacity= vertexDisplacement;
        vec3 p= position;

        p.x += sin(vertexDisplacement) * 50.0;
        p.y += cos(vertexDisplacement) * 50.0;


        vec4 modelViewPosition = modelViewMatrix * vec4(position, 1.0);
        gl_Position= projectionMatrix * modelViewPosition;
        }
    </script>

    <script type="x-shader/x-fragment" id="fragmentShader">
        uniform float delta;
        varying float vOpacity;
        varying vec3 vUv;
        void main()
        {
        float r=0.5 + cos(vUv.y * delta);
        float g=0.0; //sin(delta) * 0.5;
        float b=0.0;

        gl_FragColor = vec4(r, g, b, vOpacity);
        }
    </script>

    <script type="text/javascript">
    var renderer;
    var scene;
    var camera;
    var clock;
	var deltaTime;
    //var myCanvas=document.getElementById('myCanvas');

    $(document).ready(function() 
	{
    loadOBJWithMTL("../assets/", "Cristal.obj", "Cristal.mtl", (object) => {	
			//object.rotation.y=THREE.Math.degToRad(270);	
			object.position.set(0,0,0);
		});
        render();		
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

		var ambientLight = new THREE.AmbientLight(new THREE.Color(1.0, 1.0, 1.0), 0.2);
		scene.add(ambientLight);

		var directionalLight = new THREE.DirectionalLight(new THREE.Color(1.0, 0.2, 0.2), 0.4);//0.4
		directionalLight.position.set(0, 0, 1);
		scene.add(directionalLight);
		
		var Light =new THREE.HemisphereLight(0x00320B, 0xCBEBFF, 1);
		scene.add(Light);
        
        var geometry=new THREE.BoxBufferGeometry(100, 100, 100, 10, 10, 10);
        var mesh=new THREE.Mesh(geometry, material);
        mesh.position.z=-1000;
        mesh.position.x=-100;
        scene.add(mesh);

        var uniforms={
        delta: {value:0}
        };

        var material= new THREE.ShaderMaterial({
        uniforms: uniforms,
        vertexShader: document.getElementById('vertexShader').textContent,
        fragmentShader: document.getElementById('fragmentShader').textContent
        });

        var vertexDisplacement = new Float32Array(geometry.attributes.position.count);
        for (var i=0; i<vertexDisplacement.length; i+= 1){
        vertexDisplacement[i] = Math.sin(i);
        }

        geometry.addAttribute('vertexDisplacement', new THREE.BufferAttribute(vertexDisplacement,1));

		$("#scene-section").append(renderer.domElement);
	}

    //Renderer
    /*renderer =new THREE.WebGLRenderer({
    canvas: myCanvas,
    antialis: true
    });
    renderer.setClearColor(0xffffff);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);*/

    //Camera
    //camera=new THREE.PerspectiveCamera(35,window.innerWidth/window.innerHeight,300,10000);

    //Scene
    //scene=new THREE.Scene();

    //Lights
    /*var light=new THREE.AmbientLight(0xffffff,0.5);
    scene.add(light);
    var light2=new THREE.PointLight(0xffffff,0.5);
    scene.add(light2);*/




    /*var geometry2=new THREE.SphereGeometry(50, 20, 20);
    var mesh2=new THREE.Mesh(geometry2, material);
    mesh.position.z=-1000;
    mesh2.position.x=100;
    scene.add(mesh2);*/

   /* var geometry3 =new THREE.PlaneGeometry(10000, 10000, 100, 100);
    var mesh3 =new THREE.Mesh(geometry3, material);
    mesh3.rotation.x=-90* Math.PI/180;
    mesh3.position.y=-100;
    scene.add(mesh3);*/


    //RENDER LOOP
    //render();

    //var delta=0;

    function render()
    {
        debugger;
        deltaTime = clock.getDelta();
        delta +=0.1;
        mesh.material.uniforms.delta.value= 0.5 + Math.sin(delta) * 0.5;
            for (var i=0; i<vertexDisplacement.length; i+=1){
            vertexDisplacement[i]=0.5 + Math.sin(i*delta) *0.25;
            }
        mesh.geometry.attributes.vertexDisplacement.needsUpdate=true;
        renderer.render(scene, camera);

        requestAnimationFrame(render); 
    }
    </script>
    <body>
    <!--
    <canvas id="myCanvas"></canvas>
    <script scr="three.js"></script>-->
    <div id="scene-section"/>
    </body>
</html>

