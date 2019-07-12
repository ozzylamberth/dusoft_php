<?php
/**
 * The simplest example. We convert an HTML file into a PDF file.
 * We also add a few custom headers/footers to the PDF.
 */
?>
<html>
<head>
  <title>Testing HTML_ToPDF</title>
</head>
<body>
  Creating the PDF from local HTML file....  Note that we customize the headers and footers!<br />
<?php
// require the class
require_once dirname(__FILE__) . '/../HTML_ToPDF.php';

// full path to the file to be converted
$htmlFile = dirname(__FILE__) . '/hola.html';
// the default domain for images that use a relative path
// (you'll need to change the paths in the test.html page 
// to an image on your server)
$defaultDomain = 'www.rustyparts.com';
// full path to the PDF we are creating
$pdfFile = dirname(__FILE__) . '/timecard.pdf';
// remove old one, just to make sure we are making it afresh
@unlink($pdfFile);

/*$htmlFile="<html><head><body><table border=1><tr><td>ESTE ES UN EJEMPLO DE PROGRAMACION WEB</td></tr></table></body></head></html>";*/


// instnatiate the class with our variables
$pdf =& new HTML_ToPDF($htmlFile, $defaultDomain, $pdfFile);
// set headers/footers
$pdf->setHeader('color', 'blue');
$pdf->setFooter('left', 'Generated by HTML_ToPDF');
$pdf->setFooter('right', '$D');
$result = $pdf->convert();

// check if the result was an error
if (PEAR::isError($result)) {
    die($result->getMessage());
}
else {
    echo "PDF file created successfully: $result";
    echo '<br />Click <a href="' . basename($result) . '">here</a> to view the PDF file.';
}
?>
</body>
</html> 
