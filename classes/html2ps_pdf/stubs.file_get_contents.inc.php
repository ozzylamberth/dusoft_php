<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/stubs.file_get_contents.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

function file_get_contents($file) {
  $lines = file($file);
  if ($lines) {
    return implode('',$lines);
  } else {
    return "";
  };
}
?>