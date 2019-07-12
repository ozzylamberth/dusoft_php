<?php
require_once("../xajax_core/xajax.inc.php");

function test2ndFunction($formData, $objResponse)
{
	$objResponse->alert("formData: " . print_r($formData, true));
	$objResponse->assign("submittedDiv", "innerHTML", nl2br(print_r($formData, true)));
	return $objResponse;
}

function myCatchAllFunction($funcName, $args)
{
	$objResponse = new xajaxResponse();
	$objResponse->alert("This is from the catch all function");
	return test2ndFunction($args[0], $objResponse);
}

function testForm($formData)
{
	$objResponse = new xajaxResponse();
	$objResponse->alert("This is from the regular function");
	return test2ndFunction($formData, $objResponse);
}
$xajax = new xajax();
$xajax->setFlag("errorHandler", true);
$xajax->registerEvent("myCatchAllFunction", "onMissingFunction");
//$xajax->registerFunction("testForm");
$xajax->processRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>onMissingFunction Event (used to be catch-all) Function Test | xajax Tests</title>
<?php $xajax->printJavascript("../") ?>
</head>
<body>

<h2><a href="index.php">xajax Tests</a></h2>
<h1>onMissingFunction Event (used to be catch-all) Function Test</h1>

<form id="testForm1" onsubmit="return false;">
<p><input type="text" id="textBox1" name="textBox1" value="This is some text" /></p>
<p><input type="submit" value="Submit Normal" onclick="xajax.call('testForm', { parameters: [xajax.getFormValues('testForm1')] }); return false;" /></p>
</form>

<div id="submittedDiv"></div>

</body>
</html>