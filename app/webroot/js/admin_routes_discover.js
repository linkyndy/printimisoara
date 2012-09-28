var $from_map;
var $to_map
var $routes_map;
var $routes_bounds = new google.maps.LatLngBounds();
var $from_marker = null;
var $to_marker = null;
var $routes_marker = null;
var $routes_polyline;
var $center = new google.maps.LatLng(45.756084, 21.228776);
var $from_info_window;
var $to_info_window;
var $routes_info_window;
var $from_radius;
var $to_radius;
var $directions_service = new google.maps.DirectionsService();
var $directions_renderer;
var $directions = [];

var $colours = ['#A7CC95', '#F6444E', '#39DBCC', '#143990', '#F2BF24'];

$(document).ready(function(){
	$from_map = new google.maps.Map(document.getElementById('from_map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	$to_map = new google.maps.Map(document.getElementById('to_map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	$routes_map = new google.maps.Map(document.getElementById('routes_map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	placeMarkers();
	placeRoutes();
});

function placeMarkers(){
	addFromMarker(new google.maps.LatLng(data.coords.from.lat, data.coords.from.lng));
	addFromRadius(new google.maps.LatLng(data.coords.from.lat, data.coords.from.lng), data.radius.from);
	
	addToMarker(new google.maps.LatLng(data.coords.to.lat, data.coords.to.lng));
	addToRadius(new google.maps.LatLng(data.coords.to.lat, data.coords.to.lng), data.radius.to);
	
	$.each(data.stations.from, function(index, station){
		addFromMarker(new google.maps.LatLng(station.Station.lat, station.Station.lng), station.Station.name_direction);
	});
	$.each(data.stations.to, function(index, station){
		addToMarker(new google.maps.LatLng(station.Station.lat, station.Station.lng), station.Station.name_direction);
	});
	
	$from_map.fitBounds($from_radius.getBounds());
	$to_map.fitBounds($to_radius.getBounds());
}

function addFromMarker(latLng, text){
	$from_marker = new google.maps.Marker({
		position: latLng,
		map: $from_map,
	});
	
	google.maps.event.addListener($from_marker, 'mouseover', function(event){
		$from_info_window = new google.maps.InfoWindow({
			content: text,
			position: event.latLng
		});
		$from_info_window.open($from_map);
	});
	google.maps.event.addListener($from_marker, 'mouseout', function(event){
		$from_info_window.close($from_map);
	});	
}

function addToMarker(latLng, text){
	$to_marker = new google.maps.Marker({
		position: latLng,
		map: $to_map,
	});
	
	google.maps.event.addListener($to_marker, 'mouseover', function(event){
		$to_info_window = new google.maps.InfoWindow({
			content: text,
			position: event.latLng
		});
		$to_info_window.open($to_map);
	});
	google.maps.event.addListener($to_marker, 'mouseout', function(event){
		$to_info_window.close($to_map);
	});	
}

function addFromRadius(latLng, radius){
	$from_radius= new google.maps.Circle({
		map: $from_map,
		center: latLng,
		radius:	parseInt(radius),
		clickable: false,
		strokeColor: '#AAA',
		fillOpacity: 0
	});	
}

function addToRadius(latLng, radius){
	$to_radius= new google.maps.Circle({
		map: $to_map,
		center: latLng,
		radius:	parseInt(radius),
		clickable: false,
		strokeColor: '#AAA',
		fillOpacity: 0
	});	
}

function placeRoutes(){
	$.each(data.routes, function(routeId, route){
		$.each(route.route, function(stationId, station){
			placeRouteMarker(new google.maps.LatLng(station.Station.lat, station.Station.lng), station.Station.name_direction);
			
			if(stationId != route.route.length - 1){//if it is not the last station in list
				/*var $polyline_exists = 0;
				if(station.Station.FromStationConnection.length){
					$.each(station.Station.FromStationConnection, function(index, connection){
						if(connection.to_station_id == route.route[stationId + 1].Station.id){
							placeRoutePolyline(connection.polyline);
							$polyline_exists = 1;
							return true;	
						}
					});	
				}
				
				if($polyline_exists){
					return true;//skip to the next station
				}*/
				
				//else, prepare to create directions with google
				$directions.push({
					start: new google.maps.LatLng(station.Station.lat, station.Station.lng),
					end: new google.maps.LatLng(route.route[stationId + 1].Station.lat, route.route[stationId + 1].Station.lng)
				});
			}
		});
		$routes_map.fitBounds($routes_bounds);
		drawRoute($colours[routeId]);
	});
}

function placeRouteMarker(latLng, text){
	$routes_marker = new google.maps.Marker({
		position: latLng,
		map: $routes_map,
	});
	$routes_bounds.extend(latLng);
	
	google.maps.event.addListener($routes_marker, 'mouseover', function(event){
		$routes_info_window = new google.maps.InfoWindow({
			content: text,
			position: event.latLng
		});
		$routes_info_window.open($routes_map);
	});
	google.maps.event.addListener($routes_marker, 'mouseout', function(event){
		$routes_info_window.close($routes_map);
	});
}

function placeRoutePolyline(encoded_polyline){
	$routes_polyline = new google.maps.Polyline({
		map: $routes_map,
		path: google.maps.geometry.encoding.decodePath(encoded_polyline),
	});	
}

function drawRoute(colour){
	$.each($directions, function(index, direction){
		$directions_service = new google.maps.DirectionsService();
		
		$directions_service.route({
			origin: direction.start,
			destination: direction.end,
			travelMode: google.maps.TravelMode.DRIVING	
		}, function(result, status){
			if(status == google.maps.DirectionsStatus.OK){
				$directions_renderer = new google.maps.DirectionsRenderer({
					map: $routes_map,
					draggable: false,
					hideRouteList: true,
					suppressMarkers: true,
					suppressInfoWindows: true,
					preserveViewport: true,
					polylineOptions: {
						strokeColor: colour,
						strokeOpacity: 0.3
					}
				});
				$directions_renderer.setDirections(result);	
			}
		});
	});
}