$(document).ready(function(){

	$("#search_select").change(function(){
		var selection = $(this).val();

		if (selection == "plate") {
			$("#search_form").attr("action","searchbyplate.php");
		}else if (selection == "decal") {
			$("#search_form").attr("action","searchbydecal.php");
		}else if (selection == "first_name") {
			$("#search_form").attr("action", "searchbyfirstname.php");
		}else if (selection == "last_name") {
			$("#search_form").attr("action", "searchbylastname.php");
		}
	});
});