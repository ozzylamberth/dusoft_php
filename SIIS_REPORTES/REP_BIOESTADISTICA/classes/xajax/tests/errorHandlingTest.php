<?php
require_once("../xajax_core/xajax.inc.php");

$xajax = new xajax();
//$xajax->setFlag("debug", true);
$xajax->setFlags(array("errorHandler" => true));
$xajax->setLogFile("xajax_error_log.log");

function myErrorRiddenFunction()
{
	$value = $silly['nuts'];
	$objResponse = new xajaxResponse();
	$objResponse->alert("Bad array value: $value");
	include("file_doesnt_exist.php");
	return $objResponse;
}

$xajax->registerFunction("myErrorRiddenFunction");
$xajax->processRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Error Handling Test | xajax Tests</title>
<?php $xajax->printJavascript("../") ?>
</head>
<body>

<h2><a href="index.php">xajax Tests</a></h2>
<h1>Error Handling Test</h1>

<form id="testForm1" onsubmit="return false;">
<p><input type="submit" value="Call Error Ridden Function" onclick="xajax_myErrorRiddenFunction(); return false;" /></p>
</form>

<div id="submittedDiv"></div>

</body>
</html>