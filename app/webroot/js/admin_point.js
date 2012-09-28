var $map;
var $points = [];
var $center = new google.maps.LatLng(45.756084, 21.228776);
var $bounds = new google.maps.LatLngBounds();
var $directions_service = new google.maps.DirectionsService();
var $directions_renderer;

$(document).ready(function(){
	$map = new google.maps.Map(document.getElementById('map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	placePoints();
});

function placePoints(){
	var $encoded_points = $("code#points").text();
	
	if($encoded_points != ''){
		var $pointsArray = google.maps.geometry.encoding.decodePath($encoded_points);
		
		$.each($pointsArray, function(key, value){
			$points.push({location: value});	
		});
		
		var $latLngFrom = new google.maps.LatLng($fromStationLat, $fromStationLng);
		var $latLngTo = new google.maps.LatLng($toStationLat, $toStationLng);
		
		$bounds.extend($latLngFrom);
		$bounds.extend($latLngTo);
		$map.fitBounds($bounds);
		
		drawRoute($latLngFrom, $latLngTo, $points);
	}
}

function drawRoute(start, end, points){
	$directions_service = new google.maps.DirectionsService();
	
	$directions_service.route({
		origin: start,
		destination: end,
		waypoints: points,
		travelMode: google.maps.TravelMode.DRIVING	
	}, function(result, status){
		if(status == google.maps.DirectionsStatus.OK){
			$directions_renderer = new google.maps.DirectionsRenderer({
				map: $map,
				draggable: false,
				hideRouteList: true,
				//suppressMarkers: true,
				suppressInfoWindows: true,
				preserveViewport: true
			});
			$directions_renderer.setDirections(result);
		}
	});
}