<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Untitled</title>
<style type="text/css">	
input[type="radio"].toggle {
    display: none;
}

input[type="radio"].toggle:checked + label {
    cursor: pointer;
    color: #666666;
    border:1px solid #999;
}

input[type="radio"].toggle + label {
    width: 3em;
    padding: 10px;
}

input[type="radio"].toggle:checked + label.btn:hover {
    background-color: inherit;
    background-position: 0 0;
}

input[type="radio"].toggle-left + label {
    border-right: 0;
    -webkit-border-top-right-radius: 0;
    -webkit-border-bottom-right-radius: 0;
    -moz-border-top-right-radius: 0;
    -moz-border-bottom-right-radius: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

input[type="radio"].toggle-right + label {
color:#999;
}
</style>
</head>

<body style="background-color:#fff">

<input id="toggle-on" class="toggle toggle-left" name="toggle" value="false" type="radio" checked
><label for="toggle-on" class="btn">ASSIGNMENTS</label
><input id="toggle-off" class="toggle toggle-right" name="toggle" value="true" type="radio"
><label for="toggle-off" class="btn">CONCEPTS</label>

</body>
</html>
