<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/stubs.common.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

if (!function_exists('file_get_contents')) {
  require_once(HTML2PS_DIR.'stubs.file_get_contents.inc.php');
}

if (!function_exists('file_put_contents')) {
  require_once(HTML2PS_DIR.'stubs.file_put_contents.inc.php');
}

if (!function_exists('is_executable')) {
  require_once(HTML2PS_DIR.'stubs.is_executable.inc.php');
}

if (!function_exists('memory_get_usage')) {
  require_once(HTML2PS_DIR.'stubs.memory_get_usage.inc.php');
}

if (!function_exists('_')) {
  require_once(HTML2PS_DIR.'stubs._.inc.php');
}

?>