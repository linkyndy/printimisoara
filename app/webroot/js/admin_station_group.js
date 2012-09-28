var $map;
var $marker;
var $info_window;
var $center = new google.maps.LatLng(45.756084, 21.228776);
var $bounds = new google.maps.LatLngBounds();
var $station_group_id = $("input#StationGroupId").val();

$(document).ready(function(){
	$map = new google.maps.Map(document.getElementById('map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
		
	$.getJSON('../map/' + $station_group_id, function(data){
		$.each(data[0].Station, function(key, value){
			placeMarker(new google.maps.LatLng(value.lat, value.lng), value.name_direction);
		});
		$map.fitBounds($bounds);
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