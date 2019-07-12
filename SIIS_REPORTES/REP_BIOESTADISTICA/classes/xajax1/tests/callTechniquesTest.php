<?php
require_once("../xajax_core/xajax.inc.php");

function saySomething()
{
	$objResponse = new xajaxResponse();
	$objResponse->alert("Hello world!");
	return $objResponse;
}

function testForm($formData, $doDelay=false)
{
	if ($doDelay) {
		sleep(5);
	}
	$objResponse = new xajaxResponse();
	$objResponse->alert("POST\nformData: " . print_r($formData, true));
	$objResponse->assign("submittedDiv", "innerHTML", nl2br(print_r($formData, true)));
	return $objResponse;
}

function testForm2($formData)
{
	$objResponse = new xajaxResponse();
	$objResponse->alert("GET\nformData: " . print_r($formData, true));
	$objResponse->assign("submittedDiv", "innerHTML", nl2br(print_r($formData, true)));
	return $objResponse;
}

$xajax = new xajax();
//$xajax->setFlag("debug", true);
$xajax->registerFunction("saySomething");
$xajax->registerFunction("testForm");
$xajax->registerFunction("testForm2");
$xajax->processRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Call Techniques Test | xajax Tests</title>
<?php $xajax->printJavascript("../", "xajax_js/xajax_uncompressed.js") ?>
</head>
<body>

<h2><a href="index.php">xajax Tests</a></h2>
<h1>Call Techniques Test</h1>

<p><a href="#" onclick="xajax.call('saySomething');return false;">Say Something</a>

<form id="testForm1" onsubmit="return false;">
<p><input type="text" id="textBox1" name="textBox1" value="Here is some text." /></p>
<p><input type="submit" value="Simple Form Call" onclick="xajax.call('testForm', {parameters:[xajax.getFormValues('testForm1')]}); return false;" /></p>
<p><input type="submit" value="Form Call via get" onclick="xajax.call('testForm2', {method: 'get', parameters:[xajax.getFormValues('testForm1')]}); return false;" /></p>
<p><input type="submit" value="Form Call with Callbacks" onclick="xajax.eventFunctions.globalRequestDelay = function(){alert('In globalRequestDelay');};xajax.eventFunctions.globalRequestComplete=function(){alert('In globalRequestComplete');};xajax.call('testForm', {parameters:[xajax.getFormValues('testForm1'), true], onRequestDelay: function() { alert('In onRequestDelay'); }, beforeResponse: function() { alert('In beforeResponse'); }, onResponse: function() { alert('In onResponse'); } }); return false;" /></p>
</form>

<div id="submittedDiv"></div>

</body>
</html>