function lookup() {
	if($("#searchquerystring").attr("value").length == 0) {
		$('#suggestions').fadeOut(); // Hide the suggestions box
	} else {
		//$("#suggestions").append("<p id='searchresults'><a style='text-align:center;'><span class='searchheading'>Loading...</span><span></span></a>><br class='break' /></p>")
		$("#suggestions").fadeIn();
		var info = "controller=search&query="+$("#searchquerystring").attr("value");
		$.ajax({
			data: info,
			dataType: "html",
			success: function(data, textStatus){
				$("#suggestions").html(data);
				$("#suggestions").fadeIn();
			}
		});
	}
}
$(document).ready(function(){
	$("#searchquerystring").focus(function(event){
		$(this).attr("value", "");
		$(this).removeClass("presearch");
	});

	$("#searchquerystring").blur(function(event){
		if ($(this).attr("value") == ""){
			$(this).attr("value", "search...");
			$(this).addClass("presearch");
			$('#suggestions').fadeOut();
		}
	});

});

function toggleSearch(nameOfContainer){
	if ($("#"+nameOfContainer).prev().hasClass("searchClosed")){
		$("#"+nameOfContainer).prev().removeClass("searchClosed");
		$("#"+nameOfContainer).show();
	} else {
		$("#"+nameOfContainer).prev().addClass("searchClosed");
		$("#"+nameOfContainer).hide();
	}
	
}
