var $map;
var $from_marker = null;
var $to_marker = null;
var $zoom;
var $center = new google.maps.LatLng(45.756084, 21.228776);
var $bounds = new google.maps.LatLngBounds(); 

$(document).ready(function(){
	$map = new google.maps.Map(document.getElementById('map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	checkPlacedMarkers();
	
	google.maps.event.addListener($map, 'click', function(event){
		$zoom = $map.getZoom();
		if(!$from_marker)
			setTimeout(function(){addFromMarker(event.latLng)}, 500);
		else if(!$to_marker)
			setTimeout(function(){addToMarker(event.latLng)}, 500);
	});
	//var geocoder = new google.maps.Geocoder();

	/*geocoder.geocode({ 
		'address': 'Timisoara, Romania'
	}, function(results, status){
		if(status == google.maps.GeocoderStatus.OK){
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location
			});
			console.log("Timisoara: " + results[0].geometry.location);
		}
		else{
			console.log("Geocode was not successful for the following reason: " + status);
		}
	});*/
});

function checkPlacedMarkers(){
	var $from_lat = $("input#FromLat").val();
	var $from_lng = $("input#FromLng").val();
	var $to_lat = $("input#ToLat").val();
	var $to_lng = $("input#ToLng").val();
	
	if($from_lat && $from_lng){
		$zoom = $map.getZoom();
		var $latLng = new google.maps.LatLng($from_lat, $from_lng);
		addFromMarker($latLng);
		//$map.setCenter($latLng);
		//$map.setZoom(16);
		$bounds.extend($latLng);
		$map.fitBounds($bounds);
	}
	
	if($to_lat && $to_lng){
		$zoom = $map.getZoom();
		var $latLng = new google.maps.LatLng($to_lat, $to_lng);
		addToMarker($latLng);
		//$map.setCenter($latLng);
		//$map.setZoom(16);
		$bounds.extend($latLng);
		$map.fitBounds($bounds);
	}
}

function addFromMarker(latLng){
	if($zoom == $map.getZoom()){
		//console.log('Put at: Lat: ' + latLng.lat() + ' Long: ' + latLng.lng());
		$from_marker = new google.maps.Marker({
			position: latLng,
			map: $map,
			draggable: true,
			animation: 'DROP',
			title: 'Plecare'
		});
		updateFromPosition(latLng);
		
		google.maps.event.addListener($from_marker, 'dragend', function(event){
			//console.log('Dragged at: Lat: ' + event.latLng.lat() + ' Long: ' + event.latLng.lng());
			updateFromPosition(event.latLng);
		});
	}
}

function addToMarker(latLng){
	if($zoom == $map.getZoom()){
		//console.log('Put at: Lat: ' + latLng.lat() + ' Long: ' + latLng.lng());
		$to_marker = new google.maps.Marker({
			position: latLng,
			map: $map,
			draggable: true,
			animation: 'DROP',
			title: 'Sosire'
		});
		updateToPosition(latLng);
		
		google.maps.event.addListener($to_marker, 'dragend', function(event){
			//console.log('Dragged at: Lat: ' + event.latLng.lat() + ' Long: ' + event.latLng.lng());
			updateToPosition(event.latLng);
		});
	}
}

function updateFromPosition(latLng){
	$("input#FromLat").val(latLng.lat().toFixed(6)); $("span#FromLatText").text(latLng.lat().toFixed(6));
	$("input#FromLng").val(latLng.lng().toFixed(6)); $("span#FromLngText").text(latLng.lng().toFixed(6));
}

function updateToPosition(latLng){
	$("input#ToLat").val(latLng.lat().toFixed(6)); $("span#ToLatText").text(latLng.lat().toFixed(6));
	$("input#ToLng").val(latLng.lng().toFixed(6)); $("span#ToLngText").text(latLng.lng().toFixed(6));
}