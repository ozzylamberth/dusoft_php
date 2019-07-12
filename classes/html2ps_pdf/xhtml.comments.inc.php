<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/xhtml.comments.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

function remove_comments(&$html) {
  $html = preg_replace("#<!--.*?-->#is","",$html);
  $html = preg_replace("#<!.*?>#is","",$html);
}

?>