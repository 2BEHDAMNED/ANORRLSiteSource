<?php
	if(isset($_GET['json'])) {
		$result = json_decode(file_get_contents("result.json"), true);

		if(isset($_GET['type'])) {
			$type = $_GET['type'];

			if($type == "obj") {
				header("Content-Type: text/plain");
				
				//die(print_r($result));
				die(base64_decode($result['files']['scene.obj']['content']));
			} 
			else if($type == "mtl") {
				header("Content-Type: text/plain");
				
				die(base64_decode($result['files']['scene.mtl']['content']));
			
			}
			else if($type == "png") {
				header("Content-Type: image/png");
				
				die(base64_decode($result['files']['Player11Tex.png']['content']));
			
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<script></script>
	</head>
	<body>

		<script type="importmap">
		{
			"imports": {
				"three": "https://cdn.jsdelivr.net/npm/three/build/three.module.js",
				"three/addons/": "https://cdn.jsdelivr.net/npm/three/examples/jsm/"
			}
		}
		</script>
		<script type="module">
			import * as THREE from 'three';
			import {OrbitControls} from 'three/addons/controls/OrbitControls.js';
			import {OBJLoader} from 'three/addons/loaders/OBJLoader.js';
			import {MTLLoader} from 'three/addons/loaders/MTLLoader.js';

			const scene = new THREE.Scene();
			const camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.0001, 1000 );

			const renderer = new THREE.WebGLRenderer({alpha: true});
			renderer.setSize( window.innerWidth, window.innerHeight );
			document.body.appendChild( renderer.domElement );

			camera.position.x = -2.0279;
			camera.position.y = 107.028;
			camera.position.z = 21.4042;
			// raw radians to degrees calculations i pulled out of my ass (result.json)
			camera.rotation.x = -23.23802;
			camera.rotation.y = 23.23802;
			camera.rotation.z = -46.93395;

			//const controls = new OrbitControls( camera, renderer.domElement );

			const objLoader = new OBJLoader();
			
			const mtlLoader = new MTLLoader();

			const loader = new THREE.TextureLoader();
			const texture = loader.load('testrender.php?json&type=png');

			mtlLoader.load('testrender.php?json&type=mtl', (mtl) => {
				mtl.preload();
				
				objLoader.setMaterials(mtl);
				objLoader.load('testrender.php?json&type=obj', (root) => {
					scene.add(root);
				});
			});
			//renderer.setClearColor( 0xffffff, 0);
			
			function animate() {
				

					
			//	controls.update();
				renderer.render(scene, camera);
			}

			animate();
			
		//	renderer.setAnimationLoop(animate);

			
		</script>
	</body>
</html>