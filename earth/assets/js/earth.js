;(function ($, window, document, undefined) {


	var star_mesh = 'o Star\nv 0.169 0.000 0.087\nv 0.442 0.000 -0.111\nv 0.104 0.000 -0.111\nv -0.000 0.000 -0.433\nv -0.104 0.000 -0.111\nv -0.443 0.000 -0.111\nv -0.169 0.000 0.087\nv -0.274 0.000 0.409\nv -0.000 0.000 0.210\nv 0.273 0.000 0.409\nv 0.115 0.104 0.070\nv 0.303 0.104 -0.065\nv 0.071 0.104 -0.065\nv -0.000 0.104 -0.286\nv -0.071 0.104 -0.065\nv -0.303 0.104 -0.065\nv -0.116 0.104 0.070\nv -0.187 0.104 0.290\nv -0.000 0.104 0.154\nv 0.187 0.104 0.290\nvn 0.00 -1.00 0.00\nvn 0.00 1.00 -0.00\nvn 0.00 0.99 -0.02\nvn 0.53 0.39 0.74\nvn 0.87 0.39 -0.28\nvn 0.00 0.39 -0.91\nvn -0.87 0.39 -0.28\nvn -0.87 0.39 -0.28\nvn -0.53 0.39 0.74\nvn 0.87 0.39 -0.28\nvn -0.53 0.39 0.74\ns off\nf 8//1 1//1 9//1\nf 15//2 13//2 14//2\nf 17//2 15//2 16//2\nf 17//2 13//2 15//2\nf 17//2 12//2 13//2\nf 17//2 11//2 12//2\nf 18//2 11//2 17//2\nf 18//3 19//3 11//3\nf 19//2 20//2 11//2\nf 9//4 18//4 8//4\nf 1//5 20//5 10//5\nf 3//6 12//6 2//6\nf 7//7 18//7 17//7\nf 5//8 14//8 4//8\nf 5//6 16//6 15//6\nf 7//9 16//9 6//9\nf 1//4 12//4 11//4\nf 3//10 14//10 13//10\nf 9//11 20//11 19//11\nf 9//4 19//4 18//4\nf 1//5 11//5 20//5\nf 3//6 13//6 12//6\nf 7//7 8//7 18//7\nf 5//8 15//8 14//8\nf 5//6 6//6 16//6\nf 7//9 17//9 16//9\nf 1//4 2//4 12//4\nf 3//10 4//10 14//10\nf 9//11 10//11 20//11\n';


	var myearth;
	var markers = [];

	window.addEventListener("earthjsload", function () {
		var CountryColor = '';

		let precent = 0.5;

		$('.ki-earth-container').each(function () {

			myearth = new Earth(this, {

				location: {lat: 18, lng: 50},
				zoom    : 1.05,
				light   : 'none',

				transparent   : true,
				mapSeaColor   : 'RGBA(0,148,255,0.4)',
				mapLandColor  : '#005656',
				mapBorderColor: '#00FF21',
				mapBorderWidth: 0.25,
				mapHitTest    : true,

				autoRotate     : true,
				autoRotateSpeed: 0.5,
				autoRotateDelay: 4000,

			});
		});


		myearth.addEventListener("ready", function () {
			myearth.mapStyles = ' #UA { fill: blue; stroke: green; } ',
					this.startAutoRotate();


		});

		Earth.addMesh(star_mesh);


		var startLocation, rotationAngle;

		myearth.addEventListener("dragstart", function () {

			startLocation = myearth.location;

		});

		myearth.addEventListener("dragend", function () {

			rotationAngle = Earth.getAngle(startLocation, myearth.location);

		});

		var selectedCountry;

		myearth.addEventListener('click', function (event) {


			console.log(event);


			myearth.goTo(event.location, {duration: 250, relativeDuration: 70});


			//event.stopPropagation();


		});

	});

	function AddEarthMarker(_mrk) {

		let i = markers.length;
		markers[i] = myearth.addMarker({

			mesh         : "Star",
			color        : '#ffcc00',
			location     : {lat: _mrk.lat, lng: _mrk.lng},
			scale        : 0.35,
			hotspotRadius: 0.75,
			hotspotHeight: 0.1,
			content      : _mrk.name,


		});

		let Overlay = myearth.addOverlay({
			location    : {lat: _mrk.lat, lng: _mrk.lng},
			content     : _mrk.name,
			depthScale  : 1,
			elementScale: 1,
			visible     : true,
			className   : 'earth-tip-overlay',
			color       : '#fff',

		});
		markers[i].addEventListener('click', function (event) {
			console.log(event);
			//alert(event.target.name);
			//event.stopPropagation();

		});
	}


	const lerpHex = (a, b, amount) => {
		let ah = +a.replace('#', '0x'),
				ar = ah >> 16,
				ag = (ah >> 8) & 0xff,
				ab = ah & 0xff,
				bh = +b.replace('#', '0x'),
				br = bh >> 16,
				bg = (bh >> 8) & 0xff,
				bb = bh & 0xff,
				rr = ar + amount * (br - ar),
				rg = ag + amount * (bg - ag),
				rb = ab + amount * (bb - ab);

		return (
				'#' +
				(((1 << 24) + (rr << 16) + (rg << 8) + rb) | 0).toString(16).slice(1)
		);
	};


	setTimeout(function () {
		AddEarthMarker({'lat': '42.933334', 'lng': '76.566666', 'name': 'User Name'});
		AddEarthMarker({'lat': 27, 'lng': 5, 'name': 'User Name 2'});
		AddEarthMarker({'lat': 49, 'lng': 30, 'name': 'User Name 3'});
	}, 500);

})(jQuery, window, document);
