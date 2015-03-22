<?php 
/*
Authors: Sen Lin, Francisco
This file comprises the View of our MVC architecture
It diplays some simple HTML elements and creates unique DOM IDs for the reference of scripts.

*/
include 'Model.php';
use \Model\Model as Model;
Model::writeToJson(1243);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>ITS Tree</title>
		<link rel="stylesheet" href="style.css">
	</head>

	<body>
		<div class="row">
			<div id="title-area" style="float: left">
				<h1>ITS Tree</h1>
			</div>
			<div id="select-area" style="float: left">
				Base <select id = "menuSelectB"></select>
				Questions <select id = "menuSelectQ"></select>
				<br>
			</div>
		</div>
		<div class="row">
			<div id="mode-area" style="float: left;">
				<input type="radio" name="mode" id="manual" checked="true"> &nbsp Manual 
				<br><br>
				<input type="radio" name="mode" id="auto"> &nbsp Auto
			</div>
			<div id="bars-area" style="float: left;" style = svg"display: none;">
				<p>SubProgress</p>
				<progress max="6.25" id="subProgress" style="float: left;"></progress>
				<br>
				<p>Total Progress</p>
				<progress max="16" id="totalProgress" style="float: left;"></progress>
			</div>
<!-- 			<div id="score-area" style="float: left;">
				<div id="score">
					<p>sub-score</p>
				</div>
				<br><br>
				<div id="totalScore">
					<p>total score</p>
				</div>
				<br>
				<div class="col-md-3" id="tooltip">
				</div>
			</div> -->
			<div id="question-answer-area" style="float: left">
				<div id = "question-content" style="float: right; width: 500px;"></div>
				<br><br>
				<div id = "answer-content"></div>
			</div>
		</div>
		<div class="row">
			<div id="tree-area" style="float: left;">
			</div>
		<div class="row">
			<div id="buttons-area" style="float: left">
				<button type="button" id="pre-question">Previous Question</button>
				<button type="button" id="next-question">Next Question
				</button>
				<br>
				<button type="button" id="submit">Submit
				</button>
			</div>			
		</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<script src="d3/d3.min.js"></script>
		<script src="script.js"></script>
	</body>


</html>