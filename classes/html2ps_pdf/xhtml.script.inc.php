<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/xhtml.script.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

function process_script($sample_html) {
  return preg_replace("#<script.*?</script>#is","",$sample_html);
}

?>