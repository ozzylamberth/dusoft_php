<?php
require_once("../xajax_core/xajax.inc.php");

class testPlugin extends xajaxResponsePlugin
{
	var $sCallName = "testPlugin";
	
	function testMethod()
	{
		$this->addCommand(array("n"=>"test"), 'abcde]]>fg');	
	}
}
$instance = &xajaxPluginManager::getInstance();
$instance->registerResponsePlugin(new testPlugin());

function showOutput()
{
	$testResponse = new xajaxResponse();
	$testResponse->alert("Edit this test and uncomment lines in the showOutput() method to test plugin calling");
	// PHP4 & PHP5
	//$testResponse->plugin("testPlugin", "testMethod");
	
	// PHP5 ONLY - Uncomment to test
	//$testResponse->plugin("testPlugin")->testMethod();
	
	// PHP5 ONLY - Uncomment to test
	//$testResponse->testPlugin->testMethod();
	
	$testResponseOutput = htmlspecialchars($testResponse->getOutput());	
	
	$objResponse = new xajaxResponse();
	$objResponse->assign("submittedDiv", "innerHTML", $testResponseOutput);
	return $objResponse;
}
$xajax = new xajax();
//$xajax->setFlag("debug", true);
$xajax->registerFunction("showOutput");
$xajax->processRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Basic Plugin Test | xajax Tests</title>
<?php $xajax->printJavascript("../") ?>
</head>
<body>

<h2><a href="index.php">xajax Tests</a></h2>
<h1>Basic Plugin Test</h1>

<form id="testForm1" onsubmit="return false;">
<p><input type="submit" value="Show Response XML" onclick="xajax_showOutput(); return false;" /></p>
</form>

<div id="submittedDiv"></div>

</body>
</html>