$(document).ready(function(){
	console.log("watiing for the document to ge tready");
	$("#IRM").click(function(event){
		$("#navListQC > li > a").attr("id","");
		$("#IRM > a").attr("id","current");
		$("#contentContainer").children("*").remove();
		$("#contentContainer").append($('<div id="IRM-content"/>').load("Main.php"));
		$('#IRM-content').css("position", 'relative');
		$('#IRM-content').css("top", '5px');
		$("#IRM-content").css('margin-bottom', '10px');
		
	});
});

console.log("hello there");
