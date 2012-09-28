var $map;
var $polyline = null;
var $center = new google.maps.LatLng(45.756084, 21.228776);
var $bounds = new google.maps.LatLngBounds();

$(document).ready(function(){
	$map = new google.maps.Map(document.getElementById('map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	placePolyline();
});

function placePolyline(){
	var $encoded_polyline = $("code#polyline").text();
	
	if($encoded_polyline != ''){
		$polyline = new google.maps.Polyline({
			map: $map,
			path: google.maps.geometry.encoding.decodePath($encoded_polyline),
		});	
		
		var $path = $polyline.getPath();
		$bounds.extend($path.getAt(0));
		$bounds.extend($path.getAt($path.getLength() - 1));
		$map.fitBounds($bounds);
	}
}