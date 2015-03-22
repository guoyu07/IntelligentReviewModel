<!DOCTYPE html>
<html>
<head>
<script>
var myCount = 0;
function remove_everything(){
	//$("#contentContainer > *").remove();
//ob_get_contents();
//ob_clean();
}
function clear(){
	$("#contentContainer > *").remove();
}
function action(){
	clear();
	myFunction();
}
function myFunction(){	
//ncurses_clear();	
$("#contentContainer > *").remove();	
$("#contentContainer").append('<button onclick="action()">SUBMIT</button><br>');
	$.post(
		'external_display_reinforcement.php',
		{count: myCount},
		function(data){
			$("#contentContainer").append(data);
		}
	);	
	myCount++;
};
</script>
</head>
<body>

<button onclick="action()">Start the Reinforcement module</button>
</body>
</html>


