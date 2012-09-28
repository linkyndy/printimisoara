var $map;
var $marker = null;
var $center = new google.maps.LatLng(45.756084, 21.228776); 

$(document).ready(function(){
	$map = new google.maps.Map(document.getElementById('map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	placeMarker();
});

function placeMarker(){
	var $lat = $("td#lat").text();
	var $lng = $("td#lng").text();
	
	if($lat && $lng){
		var $latLng = new google.maps.LatLng($lat, $lng);
		$marker = new google.maps.Marker({
			position: $latLng,
			map: $map
		});
		$map.setCenter($latLng);
		$map.setZoom(18);	
	}
}