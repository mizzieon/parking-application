$(document).ready(function(){
	$(".remove-permit-button").focus(function(){
		var id = $(this).attr("data-id");
		$(".permit-to-remove").attr("value", id);
	});
	$(".remove-violation-button").focus(function(){
		var id = $(this).attr("data-id");
		$(".violation-to-remove").attr("value", id);
	});
});