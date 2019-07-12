<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/utils_text.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

function squeeze($string) {
  return preg_replace("![ \n\t]+!"," ",trim($string));
}

?>