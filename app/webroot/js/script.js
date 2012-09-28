$(document).ready(function(){
	if($("h1.logo").length){
		$("h1.logo").lettering();
	}
	if($("h3.logo").length){
		$("h3.logo").lettering();
	}
	
	if($("a[rel=tooltip]").length){
		$("a[rel=tooltip]").tooltip();
	}
	
	if($("input[data-provide=typeahead]").length){
		$("input[data-provide=typeahead]").typeahead({source: jQuery.parseJSON($("input[data-provide=typeahead]").attr('data-source'))});
	}
	
	// Make the JS radio buttons from bootstrap work
	$('div.btn-group[data-toggle=buttons-radio] button').click(function(){
		$('input#' + $(this).parent().attr('data-hidden')).val($(this).val());
	});
	
	//$(".accordion").accordion();
	
	/*mapStyle();
	$(window).resize(function(){
		mapStyle();
	});*/
	
	toggleHeaderShadow();
	$(window).scroll(function(){
		toggleHeaderShadow();
	});
});

function toggleHeaderShadow(){
	if($(window).scrollTop() > 0){
		if(!$("#header-container").hasClass("shadow")){
			$("#header-container").addClass("shadow");
		}
	} else {
		if($("#header-container").hasClass("shadow")){
			$("#header-container").removeClass("shadow");
		}
	}	
}

function mapStyle(){
	if($map && $("#map")){
		if(Modernizr.mq('only screen and (max-width: 767px)')){//shrink map and enable click-to-view
			$("#map").addClass("no-expanded");
			$map.setOptions({
				mapTypeControl: false,
				zoomControl: false	
			});
			$("#map").click(function(){
				$("#map").removeClass("no-expanded");
				$("#map").css({height: ($(window).height() - 72), width: $(window).width()});
				$(this).after("<figcaption>Inchide harta</figcaption>");
				$(this).unbind("click");
			});
		} else {
			$map.setOptions({
				mapTypeControl: true,
				zoomControl: true	
			});	
		}
	}
}