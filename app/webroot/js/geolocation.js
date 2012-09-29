var $button = $('button.geolocation-get');
var $container = $('.geolocation-container');
var $sendTime = $('button.geolocation-send-time');

$(document).ready(function(){	
	$button.click(function(){
		$container.slideUp();
		
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(success, error);
		} else {
			error('Eroare! Browserul tau nu suporta geolocatia.');
		}
	});	
	
	$sendTime.live('click', function(){
		$now = new Date();
		$this = $(this);
		$.post(app.url + '/admin/times/add_gps', {
			station_line_id: $(this).attr('data-station-line-id'),
			time: $now.getHours() + ':' + $now.getMinutes()
		}, function(data){
			$this.replaceWith(data.response);
		}, 'json');
		
		return false;
	});
});

function success(position) {
	$.getJSON(app.url + '/admin/stations/near_stations/' + position.coords.latitude + '/' + position.coords.longitude, function(data){
		$.each(data.stationLines, function(key, value){
			$container.html('<tr><td>' + value.Line.name + '</td><td>' + value.Station.name + ' <small class="muted">&rarr; ' + value.Station.direction + '</small></td><td><button class="geolocation-send-time btn btn-small" data-station-line-id="' + value.StationLine.id + '"><i class="icon-time"></i></button></td></tr>');
		});
	});
	
	$container.slideDown();
}

function error(message) {
	if (typeof message === 'undefined') {
		message = 'Eroare! Nu te-am putut gasi.';
	}
	$container.html('<span class="text-danger">' + message + '</span>');
}