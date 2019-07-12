<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/xhtml.style.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

function process_style(&$html) {
  if (preg_match('#^(.*<style[^>]*>)(.*?)(</style>.*)$#is', $html, $matches)) {
    process_style($matches[3]);
    $html = $matches[1].process_style_content($matches[2]).$matches[3];
  };
}

function process_style_content($html) {
  // Remove CDATA comment bounds inside the <style>...</style> 
//   $html = preg_replace("#(<style[^>]*>)\s*/\*<!\[CDATA\[\*/#","\\1",$html); 
//   $html = preg_replace("#/\*\]\]>\*/\s*(</style>)#is","\\1",$html);

  $html = preg_replace("#/\*<!\[CDATA\[\*/#","",$html); 
  $html = preg_replace("#/\*\]\]>\*/#is","",$html);

  // Remove HTML comment bounds inside the <style>...</style> 
//   $html = preg_replace("#(<style[^>]*>)\s*<!--#is","\\1",$html); 
//   $html = preg_replace("#-->\s*(</style>)#is","\\1",$html);

  $html = preg_replace("#<!--#is","",$html); 
  $html = preg_replace("#-->#is","",$html);

  // Remove CSS comments
//   while (preg_match("#(<style[^>]*>.*)/\*.*?\*/.*(</style>)#is",$html)) {
//     $html = preg_replace("#(<style[^>]*>.*)/\*.*\*/(.*</style>)#is","\\1\\2",$html);
//   };

  $html = preg_replace("#/\*.*?\*/#is","",$html);

  return $html;
}

?>