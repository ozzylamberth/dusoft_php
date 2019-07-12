<?php
require_once("../xajax_core/xajax.inc.php");

class myObjectTest {
	var $myNumber = 42;
	function testInstanceMethod($formData)
	{
		$objResponse = new xajaxResponse();
		$objResponse->alert("My object number is: {$this->myNumber}");
		$objResponse->alert("formData: " . print_r($formData, true));
		$objResponse->assign("submittedDiv", "innerHTML", nl2br(print_r($formData, true)));
		return $objResponse;
	}
	function testClassMethod($formData)
	{
		$objResponse = new xajaxResponse();
		$objResponse->alert("This is a class method.");
		$objResponse->alert("formData: " . print_r($formData, true));
		$objResponse->assign("submittedDiv", "innerHTML", nl2br(print_r($formData, true)));
		return $objResponse;
	}
}

class objectMethodsTest
{
	var $myNumber = 30;
	function firstMethod() {
		$objResponse = new xajaxResponse();
		$objResponse->alert("In firstMethod. My object number is: {$this->myNumber}");
		return $objResponse;
	}
	function second_method() {
		$objResponse = new xajaxResponse();
		$objResponse->alert("In second_method. My object number is: {$this->myNumber}");
		return $objResponse;
	}
}
class objectMethodsTest2
{
	var $myNumber = 30;
	function thirdMethod($arg1)
	{
		$objResponse = new xajaxResponse();
		$objResponse->alert("In thirdMethod. My object number is: {$this->myNumber} and arg1: $arg1");
		return $objResponse;
	}	
}

$xajax = new xajax();
//$xajax->setFlag("debug", true);
$myObj = new myObjectTest();
$myObj->myNumber = 50;
$xajax->registerFunction(array("testForm", &$myObj, "testInstanceMethod"));
$xajax->registerFunction(array("testForm2", "myObjectTest", "testClassMethod"));
$myObj->myNumber = 56;
$myObj2 = new objectMethodsTest();
$myObj3 = new objectMethodsTest2();
$xajax->registerCallableObject($myObj2);
$xajax->registerCallableObject($myObj3);
$myObj2->myNumber = 60;
$myObj3->myNumber = 89;
$xajax->processRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Register Object Test | xajax Tests</title>
<?php $xajax->printJavascript("../") ?>
</head>
<body>

<h2><a href="index.php">xajax Tests</a></h2>
<h1>Register Object Test</h1>

<p><a href="#" onclick="xajax.call('firstMethod');return false;">Test First Callable Object 1</a><br />
   <a href="#" onclick="xajax.call('second_method');return false;">Test First Callable Object 2</a><br />
   <a href="#" onclick="xajax.call('thirdMethod', {parameters: ['howdy']});return false;">Test Second Callable Object 1</a></p>

<form id="testForm1" onsubmit="return false;">
<p><input type="text" id="textBox1" name="textBox1" value="This is some text" /></p>
<p><input type="submit" value="Submit to Instance Method" onclick="xajax_testForm(xajax.getFormValues('testForm1')); return false;" /></p>
<p><input type="submit" value="Submit to Class Method" onclick="xajax_testForm2(xajax.getFormValues('testForm1')); return false;" /></p>
</form>

<div id="submittedDiv"></div>

</body>
</html>