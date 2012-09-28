var $map;
var $marker;
var $polyline;
var $info_window;
var $center = new google.maps.LatLng(45.756084, 21.228776);
var $bounds = new google.maps.LatLngBounds();
var $directions_service = new google.maps.DirectionsService();
var $directions_renderer;
var $directions = [];
var $line_id = $("input#LineId").val();

$(document).ready(function(){
	$map = new google.maps.Map(document.getElementById('map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	
	$.getJSON('../map/' + $line_id, function(data){
		if(data.StationLine.length < 2){
			return;
		}
		
		data.StationLine.push(data.StationLine[0]);//link the last station with the first station to create a loop
		
		$.each(data.StationLine, function(key, value){
			if(key != data.StationLine.length - 1){//if it is not the last station in list
				placeMarker(new google.maps.LatLng(value.Station.lat, value.Station.lng), value.Station.name_direction);
			
				var $polyline_exists = 0;
				if(value.Station.FromStationConnection.length){
					$.each(value.Station.FromStationConnection, function(index, connection){
						if(connection.to_station_id == data.StationLine[key + 1].Station.id){
							placePolyline(connection.polyline);
							$polyline_exists = 1;
							return true;	
						}
					});	
				}
				
				if($polyline_exists){
					return true;//skip to the next station
				}
				
				//else, prepare to create directions with google
				var $encoded_points = '';
				if(value.Station.FromStationPoint.length){
					$.each(value.Station.FromStationPoint, function(index, point){
						if(point.to_station_id == data.StationLine[key + 1].Station.id){
							$encoded_points = point.points;
							return true;	
						}
					});	
				}
				//if additional points are found, add them as waypoints; else, draq normal direction between start and end
				var $points = [];
				if($encoded_points != ''){
					var $pointsArray = google.maps.geometry.encoding.decodePath($encoded_points);
					
					$.each($pointsArray, function(key, value){
						$points.push({location: value});	
					});	
				}
				
				$directions.push({
					start: new google.maps.LatLng(value.Station.lat, value.Station.lng),
					end: new google.maps.LatLng(data.StationLine[key + 1].Station.lat, data.StationLine[key + 1].Station.lng),
					points: $points
				});
			}
		});
		$map.fitBounds($bounds);
		drawRoute();
	});
});

function placeMarker(latLng, text){
	$marker = new google.maps.Marker({
		position: latLng,
		map: $map,
	});
	$bounds.extend(latLng);
	
	google.maps.event.addListener($marker, 'mouseover', function(event){
		$info_window = new google.maps.InfoWindow({
			content: text,
			position: event.latLng
		});
		$info_window.open($map);
	});
	google.maps.event.addListener($marker, 'mouseout', function(event){
		$info_window.close($map);
	});
}

function placePolyline(encoded_polyline){
	$polyline = new google.maps.Polyline({
		map: $map,
		path: google.maps.geometry.encoding.decodePath(encoded_polyline),
	});	
}

function drawRoute(){
	$.each($directions, function(index, direction){
		$directions_service = new google.maps.DirectionsService();
		
		$directions_service.route({
			origin: direction.start,
			destination: direction.end,
			waypoints: direction.points,
			travelMode: google.maps.TravelMode.DRIVING	
		}, function(result, status){
			if(status == google.maps.DirectionsStatus.OK){
				$directions_renderer = new google.maps.DirectionsRenderer({
					map: $map,
					draggable: false,
					hideRouteList: true,
					suppressMarkers: true,
					suppressInfoWindows: true,
					preserveViewport: true
				});
				$directions_renderer.setDirections(result);
			}
		});
	});
}