<?php 
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/xhtml.deflist.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

function process_dd(&$sample_html, $offset) {
  return autoclose_tag($sample_html, $offset, "(dt|dd|dl|/dl|/dd)", array("dl" => "process_dl"), "/dd");
}

function process_dt(&$sample_html, $offset) {
  return autoclose_tag($sample_html, $offset, "(dt|dd|dl|/dl|/dd)", array("dl" => "process_dl"), "/dt");  
}

function process_dl(&$sample_html, $offset) {
  return autoclose_tag($sample_html, $offset, "(dt|dd|/dl)", 
                       array("dt" => "process_dt",
                             "dd" => "process_dd"), 
                       "/dl");  
};

function process_deflists(&$sample_html, $offset) {
  return autoclose_tag($sample_html, $offset, "(dl)", 
                       array("dl" => "process_dl"),
                       "");
};

?>