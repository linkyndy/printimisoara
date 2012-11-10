$(document).ready(function(){	
	$("ul#stations").sortable();
	
	$("ul#stations li > a").live("click", function(e){
		e.preventDefault();
    	e.stopPropagation();
	});
	
	$("button#StationLineAddButton").click(function(){
		addStation();
	});	
	
	$("ul#stations li small").live("click", function(){
		deleteStation($(this));
	});
	
	$("input#StationLineSubmit").click(function(){
		buildData();
		return true;
	});
});

function addStation(){
	var $input = $("input#StationLineAddStation");
	
	if($input.val() == ''){
		return false;	
	}
	
	$("#stations").append('<li><button class="btn" data-toggle="button"></button> <span>' + $input.val() + '</span> <small>&times;</small></li>');
		
	$input.val('');
}

function deleteStation($small){
	$small.closest("li").remove();
}

function buildData(){
	var $form = $("form#StationLineAdminEditForm");
	
	$("ul#stations li").each(function(index){
		$form.append('<input type="hidden" id="Station' + index + 'NameDirection" name="data[Station][' + index + '][name_direction]" value="' + $(this).find("span").text() + '">');
		$form.append('<input type="hidden" id="Station' + index + 'Order" name="data[Station][' + index + '][order]" value="' + (index+1) + '">');
		$form.append('<input type="hidden" id="Station' + index + 'End" name="data[Station][' + index + '][end]" value="' + ($(this).find("button").hasClass("active") ? 1 : 0) + '">');
	});
	return true;
}