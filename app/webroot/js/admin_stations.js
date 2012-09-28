var $map;
var $marker = null;
var $zoom;
var $center = new google.maps.LatLng(45.756084, 21.228776); 

$(document).ready(function(){
	$map = new google.maps.Map(document.getElementById('map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	//setRegions();
	checkPlacedMarker();
	
	google.maps.event.addListener($map, 'click', function(event){
		$zoom = $map.getZoom();
		if(!$marker)
			setTimeout(function(){addMarker(event.latLng)}, 500);
		else
			setTimeout(function(){moveMarker(event.latLng)}, 500);
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

function checkPlacedMarker(){
	var $lat = $("input#StationLat").val();
	var $lng = $("input#StationLng").val();
	
	if($lat && $lng){
		$zoom = $map.getZoom();
		var $latLng = new google.maps.LatLng($lat, $lng);
		addMarker($latLng);
		$map.setCenter($latLng);
		$map.setZoom(18);	
	}
}

function addMarker(latLng){
	if($zoom == $map.getZoom()){
		//console.log('Put at: Lat: ' + latLng.lat() + ' Long: ' + latLng.lng());
		$marker = new google.maps.Marker({
			position: latLng,
			map: $map,
			draggable: true,
			animation: 'DROP'
		});
		updatePosition(latLng);
		
		google.maps.event.addListener($marker, 'dragend', function(event){
			//console.log('Dragged at: Lat: ' + event.latLng.lat() + ' Long: ' + event.latLng.lng());
			updatePosition(event.latLng);
		});
	}
}

function moveMarker(latLng){
	if($zoom == $map.getZoom()){
		//console.log('Moved at: Lat: ' + latLng.lat() + ' Long: ' + latLng.lng());
		$marker.setPosition(latLng);
		updatePosition(latLng);
	}
}

function updatePosition(latLng){
	$("input#StationLat").val(latLng.lat().toFixed(6)); $("span#StationLatText").text(latLng.lat().toFixed(6));
	$("input#StationLng").val(latLng.lng().toFixed(6)); $("span#StationLngText").text(latLng.lng().toFixed(6));
	$("input#StationRegion").val(computeRegion(latLng)); $("span#StationRegionText").text(computeRegion(latLng));	
}

function setRegions(){
	var $inner_center = new google.maps.Circle({
		map: $map,
		center: $center,
		radius:	425,
		clickable: false,
		strokeColor: '#AAA',
		fillOpacity: 0
	});
	var $outer_center = new google.maps.Circle({
		map: $map,
		center: $center,
		radius:	975,
		clickable: false,
		strokeColor: '#AAA',
		fillOpacity: 0
	});
	var $city = new google.maps.Circle({
		map: $map,
		center: $center,
		radius:	16755,
		clickable: false,
		strokeColor: '#AAA',
		fillOpacity: 0
	});
	var $first_bisectrix = new google.maps.Polyline({
		map: $map,
		path: [$city.getBounds().getNorthEast(), $city.getBounds().getSouthWest()],
		clickable: false,
		strokeColor: '#AAA'
	});
	var $second_bisectrix = new google.maps.Polyline({
		map: $map,
		path: [new google.maps.LatLng($city.getBounds().getNorthEast().lat(), $city.getBounds().getSouthWest().lng()), new google.maps.LatLng($city.getBounds().getSouthWest().lat(), $city.getBounds().getNorthEast().lng())],
		clickable: false,
		strokeColor: '#AAA'
	});
}

function computeRegion(latLng){
	var $return = '';
	
	var $distance = google.maps.geometry.spherical.computeDistanceBetween($center, latLng);
	if($distance <= 425){
		return 'C';
	}
	else if($distance <= 975){
		$return += 'C';
	}
	else if($distance <= 16755){
	}
	else{
		return 'N/A';	
	}
	
	var $heading = google.maps.geometry.spherical.computeHeading($center, latLng);
	if($heading > -45 && $heading <= 45){
		$return += 'N';
	}
	else if($heading > 45 && $heading <= 135){
		$return += 'E';
	}
	else if($heading > -135 && $heading <= -45){
		$return += 'V';
	}
	else if($heading > -180 && $heading <= -125 || $heading > 135 && $heading <= 180){
		$return += 'S';
	}
	
	return $return;
}