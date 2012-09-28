var $map;
var $polyline;
var $center = new google.maps.LatLng(45.756084, 21.228776); 
var $bounds = new google.maps.LatLngBounds();
var $saveTimer;

$(document).ready(function(){
	$map = new google.maps.Map(document.getElementById('map'), {
		center: $center,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
	});
	
	checkPlacedPolyline();
	
	$("button#StationConnectionLoad").click(function(){
		if($polyline){
			$polyline.setMap(null);
			clearInterval($saveTimer);
		}
		loadStations();	
	});
});

function checkPlacedPolyline(){
	var $encoded_polyline = $("input#StationConnectionPolyline").val();
	
	if($encoded_polyline != ''){
		$polyline = new google.maps.Polyline({
			map: $map,
			path: google.maps.geometry.encoding.decodePath($encoded_polyline),
			editable: true	
		});	
		
		var $path = $polyline.getPath();
		$bounds.extend($path.getAt(0));
		$bounds.extend($path.getAt($path.getLength() - 1));
		$map.fitBounds($bounds);
		
		$saveTimer = setInterval(function(){
			updatePolyline();
		}, 3000);
	}
}

function loadStations(){
	var $data = {
		from: $("input#StationConnectionFrom").val(),
		to: $("input#StationConnectionTo").val()
	};
	
	if($data.from == '' || $data.to == ''){
		return;	
	}
	
	$("button#StationConnectionLoad").text('Sterge conexiunea curenta si incarca statiile');

	$.post('./stations/', $data, function(data){
		$("input#StationConnectionFromStationId").val(data.from.Station.id);
		$("input#StationConnectionToStationId").val(data.to.Station.id);
		
		var $latLngFrom = new google.maps.LatLng(data.from.Station.lat, data.from.Station.lng);
		var $latLngTo = new google.maps.LatLng(data.to.Station.lat, data.to.Station.lng);
		
		$bounds.extend($latLngFrom);
		$bounds.extend($latLngTo);
		$map.fitBounds($bounds);
		
		addPolyline($latLngFrom, $latLngTo);
	}, 'json');	
}

function addPolyline(latLngFrom, latLngTo){
	$polyline = new google.maps.Polyline({
		map: $map,
		path: [latLngFrom, latLngTo],
		editable: true	
	});	
	
	$saveTimer = setInterval(function(){
		updatePolyline();
	}, 3000);
}

function updatePolyline(){
	$("input#StationConnectionPolyline").val(google.maps.geometry.encoding.encodePath($polyline.getPath()));
}