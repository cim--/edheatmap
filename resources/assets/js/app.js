
function initHeatmap(THREE, mode, systemdatastr) {
	var systemdata = JSON.parse(atob(systemdatastr));

	var width = 1200;
	var height = 800;
	
	var scene = new THREE.Scene();
	var camera = new THREE.PerspectiveCamera( 75, width / height, 0.1, 1000 );
	camera.position.z = 300;
	var clight = new THREE.PointLight(0xc0c0c0, 2, 700 );
	clight.position = camera.position;
	scene.add( clight );
	
	var light = new THREE.AmbientLight( 0x060606 ); // soft white light
	scene.add( light );

	var loader = new THREE.FontLoader();
	var renderer = new THREE.WebGLRenderer();
	renderer.setSize( width, height );
	document.getElementById('mapcontainer').appendChild( renderer.domElement );

	var labelMaterial = new THREE.MeshLambertMaterial( {color: 0x30d030 });
	
	function addLabel(sphere, system) {
		loader.load( 'fonts/helvetiker_regular.typeface.json', function ( font ) {
			var geometry = new THREE.TextGeometry( system.name, {
				font: font,
				size: 2.5,
				height: 0.2,
				curveSegments: 12,
				bevelEnabled: false,
			} );
			var name = new THREE.Mesh( geometry, labelMaterial );
			name.position.x = sphere.position.x;
			name.position.y = sphere.position.y;
			name.position.z = sphere.position.z;
			scene.add(name);
		} );
	}

	var starGreyMaterial = new THREE.MeshLambertMaterial(
		{ color: 0x404040, transparent: true, opacity: 0.6 }
	);
	var starRedMaterial = new THREE.MeshLambertMaterial(
		{ color: 0x900000, transparent: true, opacity: 0.6 }
	);
	var starOrangeMaterial = new THREE.MeshLambertMaterial(
		{ color: 0xa05000, transparent: true, opacity: 0.6 }
	);
	var starYellowMaterial = new THREE.MeshLambertMaterial(
		{ color: 0xb0b000, transparent: true, opacity: 0.6 }
	);
	var starWhiteMaterial = new THREE.MeshLambertMaterial(
		{ color: 0xc0c0c0, transparent: true, opacity: 0.6 }
	);
	var starCyanMaterial = new THREE.MeshLambertMaterial(
		{ color: 0x00d0d0, transparent: true, opacity: 0.6 }
	);
	var starBlueMaterial = new THREE.MeshLambertMaterial(
		{ color: 0x3030f0, transparent: true, opacity: 0.6 }
	);

    
    var heatmapColour;
    if (mode == "traffic") {
	heatmapColour = function heatmapColour(amount) {
		if (amount == 0) {
			return starGreyMaterial;
		} else if (amount < 3) {
			return starRedMaterial;
		} else if (amount < 10) {
			return starOrangeMaterial;
		} else if (amount < 100) {
			return starYellowMaterial;
		} else if (amount < 250) {
			return starWhiteMaterial;
		} else if (amount < 1000) {
			return starCyanMaterial;
		} else {
			return starBlueMaterial;
		}
	}
    } else if (mode == "colonisation") {
	heatmapColour = function heatmapColour(amount) {
		if (cstate == 'claim') {
		    return starRedMaterial;
		} else if (cstate == 'new') {
		    return starYellowMaterial;
		} else {
		    return starWhiteMaterial;
		}
	}
    }

	
	var systemidx = {};

	var geometry = new THREE.OctahedronGeometry( 1 );
	
	for (var i=0;i<systemdata.length;i++) {
		var system = systemdata[i];
		systemidx[system.name] = system;

		var sphere = new THREE.Mesh( geometry, heatmapColour(system.amount) );

		var size = Math.pow(parseFloat(system.amount)+1, 1/3) / 3;
		sphere.scale.x = sphere.scale.y = sphere.scale.z = size;
		
		sphere.position.x = parseFloat(system.x);
		sphere.position.y = parseFloat(system.y);
		sphere.position.z = parseFloat(system.z);
		scene.add( sphere );

		if(i<100) {
			addLabel(sphere, system);
		}
		
	}

	var mv = [0,0,0,0,0,0];
	
	function animate() {
		requestAnimationFrame( animate );

		camera.position.x += mv[0];
		camera.position.y += mv[1];
		camera.position.z += mv[2];
		clight.position.x = camera.position.x+2;
		clight.position.y = camera.position.y+2;
		clight.position.z = camera.position.z+2;
		
		renderer.render( scene, camera );
	}
	animate();

	$('#ctrl_forward').mousedown(function() {
		mv[2] = -1;
	}).mouseup(function() {
		mv[2] = 0;
	});
	$('#ctrl_backward').mousedown(function() {
		mv[2] = 1;
	}).mouseup(function() {
		mv[2] = 0;
	});
	$('#ctrl_left').mousedown(function() {
		mv[0] = -1;
	}).mouseup(function() {
		mv[0] = 0;
	});
	$('#ctrl_right').mousedown(function() {
		mv[0] = 1;
	}).mouseup(function() {
		mv[0] = 0;
	});
	$('#ctrl_up').mousedown(function() {
		mv[1] = 1;
	}).mouseup(function() {
		mv[1] = 0;
	});
	$('#ctrl_down').mousedown(function() {
		mv[1] = -1;
	}).mouseup(function() {
		mv[1] = 0;
	});
	$('#ctrl_go').click(function() {
		var name = $('#ctrl_system').val();
		if (systemidx[name]) {
			camera.position.x = parseFloat(systemidx[name].x);
			camera.position.y = parseFloat(systemidx[name].y);
			camera.position.z = parseFloat(systemidx[name].z) + 20;
		}
	});
	$('#ctrl_reset').click(function() {
		camera.position.x = 0;
		camera.position.y = 0;
		camera.position.z = 300;
	});


	
}
