function heatMapColour(amount) {
	if (amount == 0) {
		return 0x404040;
	} else if (amount < 3) {
		return 0x900000;
	} else if (amount < 10) {
		return 0xa05000;
	} else if (amount < 100) {
		return 0xb0b000;
	} else if (amount < 250) {
		return 0xc0c0c0;
	} else if (amount < 1000) {
		return 0x00d0d0;
	} else {
		return 0x0000f0;
	}
}

function initHeatmap(THREE, systemdatastr) {
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
	document.body.appendChild( renderer.domElement );

	function addLabel(sphere, system) {
		loader.load( 'fonts/helvetiker_regular.typeface.json', function ( font ) {
			var geometry = new THREE.TextGeometry( system.name, {
				font: font,
				size: 2.5,
				height: 0.2,
				curveSegments: 12,
				bevelEnabled: false,
			} );
			var material = new THREE.MeshLambertMaterial( {color: 0x30d030 });
			var name = new THREE.Mesh( geometry, material );
			name.position.x = sphere.position.x;
			name.position.y = sphere.position.y;
			name.position.z = sphere.position.z;
			scene.add(name);
		} );
	}

	var systemidx = {};

	for (var i=0;i<systemdata.length;i++) {
		var system = systemdata[i];
		systemidx[system.name] = system;

		var geometry = new THREE.SphereGeometry( Math.pow(parseFloat(system.amount)+1, 1/3) / 3 );

		
		var material = new THREE.MeshLambertMaterial(
			{
				color: heatMapColour(system.amount),
				transparent: true,
				opacity: 0.6
			}
		);
		var sphere = new THREE.Mesh( geometry, material );
	
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
