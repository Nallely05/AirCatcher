<!doctype html>
<html lang="en">
<head>
    <script type="text/javascript" src="../js/libs/jquery/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="../js/libs/three/three.js"></script>
    <script type="text/javascript" src="../js/libs/three/MTLLoader.js"></script>
    <script type="text/javascript" src="../js/libs/three/OBJLoader.js"></script>
</head> 
    <body>
    <canvas id="myCanvas"></canvas>
    <script scr="three.js"></script>

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
        p.x y= cos(vertexDisplacement) * 50.0;


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
        float r=1.0 + cos(vUv.x* delta);
        float g=0.5 + sin(delta) * 0.5;
        float b=0.0;

        gl_FragColor = vec4r, g, b, vOpacity);
        }
    </script>

    <script type="text/javascript">
    var renderer;
    var scene;
    var camera;
    var myCanvas=document.getElementById('myCanvas');

    //Renderer
    renderer =new THREE.WebGLRenderer({
    canvas: myCanvas,
    antialis: true
    });
    renderer.setClearColor(0xffffff);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);

    //Camera
    camera=new THREE.PerspectiveCamera(35,window.innerWidth/window.innerHeight,300,10000);

    //Scene
    scene=new THREE.Scene();

    //Lights
    var light=new THREE.AmbientLight(0xffffff,0.5);
    scene.add(light);
    var light2=new THREE.PointLight(0xffffff,0.5);
    scene.add(light2);

    var uniforms={
    delta: {value:0}
    };

    var material= new THREE.ShaderMaterial({
    uniforms: uniforms,
    vertexShader: document.getElementById('vertexShader').textContent,
    fragmentShader: document.getElementById('fragmentShader').textContent
    });

    var geometry=new THREE.BoxBufferGeometry(100, 100, 100, 10, 10, 10);
    var mesh=new THREE.Mesh(geometry, material);
    mesh.position.z=-1000;
    mesh.position.x=-100;
    scene.add(mesh);

    var geometry2=new THREE.SphereGeometry(50, 20, 20);
    var mesh2=new THREE.Mesh(geometry2, material);
    mesh.position.z=-1000;
    mesh2.position.x=100;
    scene.add(mesh2);

    var geometry3 =new THREE.PlaneGeometry(10000, 10000, 100, 100);
    var mesh3 =new THREE.Mesh(geometry3, material);
    mesh3.rotation.x=-90* Math.PI/180;
    mesh3.position.y=-100;
    scene.add(mesh3);
    
    debugger;

    var vertexDisplacement = new Float32Array(geometry.attributes.position.count);
    for (var i=0; i<vertexDisplacement.length; i+= 1){
    vertexDisplacement[i] = Math.sin(i);
    }

    geometry.addAttribute('vertexDisplacement', new THREE.BufferAttribute(vertexDisplacement,1));

    //RENDER LOOP
    render();

    var delta=0;

    function render()
    {
        delta +=0.1;
        mesh.material.uniforms.delta.value= 0.5 + Math.sin(delta) * 0.5;
            for (var i=0; i<vertexDisplacement.length; i+=1){
            vertexDisplacement[i]=0.5 + Math.sin(i*delta) *0.25;
            }
        mesh.geometry.attributes.vertexDisplacement.needsUpdate=true;
        renderer.render(scene, camera);

        requestAnimationFrame(render);
        debugger;
    }
    </script>
    </body>
</html>

