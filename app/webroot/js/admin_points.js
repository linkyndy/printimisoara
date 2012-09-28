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
	
	checkPlacedPoints();
	
	$("button#StationPointLoad").click(function(){
		if($directions_renderer){
			$directions_renderer.setMap(null);
		}
		loadStations();	
	});
});

function checkPlacedPoints(){
	var $encoded_points = $("input#StationPointPoints").val();
	
	if($encoded_points != ''){
		var $pointsArray = google.maps.geometry.encoding.decodePath($encoded_points);
		
		$.each($pointsArray, function(key, value){
			$points.push({location: value});	
		});
		
		var $fromStationLat = $("input#StationPointFromStationLat").val();
		var $fromStationLng = $("input#StationPointFromStationLng").val();
		var $toStationLat = $("input#StationPointToStationLat").val();
		var $toStationLng = $("input#StationPointToStationLng").val();
		
		var $latLngFrom = new google.maps.LatLng($fromStationLat, $fromStationLng);
		var $latLngTo = new google.maps.LatLng($toStationLat, $toStationLng);
		
		$bounds.extend($latLngFrom);
		$bounds.extend($latLngTo);
		$map.fitBounds($bounds);
		
		drawRoute($latLngFrom, $latLngTo, $points);
	}
}

function loadStations(){
	var $data = {
		from: $("input#StationPointFrom").val(),
		to: $("input#StationPointTo").val()
	};
	
	if($data.from == '' || $data.to == ''){
		return;	
	}
	
	$("button#StationPointLoad").text('Sterge punctele curente si incarca statiile');

	$.post('./stations/', $data, function(data){
		$("input#StationPointFromStationId").val(data.from.Station.id);
		$("input#StationPointToStationId").val(data.to.Station.id);
		
		$("input#StationPointFromStationLat").val(data.from.Station.lat);
		$("input#StationPointFromStationLng").val(data.from.Station.lng);
		$("input#StationPointToStationLat").val(data.to.Station.lat);
		$("input#StationPointToStationLng").val(data.to.Station.lng);
		
		var $latLngFrom = new google.maps.LatLng(data.from.Station.lat, data.from.Station.lng);
		var $latLngTo = new google.maps.LatLng(data.to.Station.lat, data.to.Station.lng);
		
		$bounds.extend($latLngFrom);
		$bounds.extend($latLngTo);
		$map.fitBounds($bounds);
		
		drawRoute($latLngFrom, $latLngTo);
	}, 'json');	
}

function updatePoints(){
	$("input#StationPointPoints").val(google.maps.geometry.encoding.encodePath($points));
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
				draggable: true,
				hideRouteList: true,
				//suppressMarkers: true,
				suppressInfoWindows: true,
				preserveViewport: true
			});
			$directions_renderer.setDirections(result);
			
			google.maps.event.addListener($directions_renderer, 'directions_changed', function(){
				$points = $directions_renderer.directions.routes[0].legs[0].via_waypoints;
				updatePoints();
			});
		}
	});
}