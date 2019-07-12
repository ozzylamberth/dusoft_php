<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/ps.utils.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

function trim_ps_comments($data) {
  $data = preg_replace("/(?<!\\\\)%.*/","",$data);
  return preg_replace("/ +$/","",$data);
}

function format_ps_color($color) {
  return sprintf("%.3f %.3f %.3f",$color[0]/255,$color[1]/255,$color[2]/255);
}
?>