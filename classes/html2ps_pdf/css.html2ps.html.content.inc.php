<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.html2ps.html.content.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSHTML2PSHTMLContent extends CSSPropertyHandler {
  function CSSHTML2PSHTMLContent() { $this->CSSPropertyHandler(false, false); }

  function default_value() { return ""; }

  // CSS 2.1 p 12.2: 
  // Value: [ <string> | <uri> | <counter> | attr(X) | open-quote | close-quote | no-open-quote | no-close-quote ]+ | inherit
  //
  // TODO: process values other than <string>
  //
  function parse($value) {
    return $value;
    // return css_process_escapes(css_remove_value_quotes($value));
  }

  function getPropertyCode() {
    return CSS_HTML2PS_HTML_CONTENT;
  }

  function getPropertyName() {
    return '-html2ps-html-content';
  }
}

CSS::register_css_property(new CSSHTML2PSHTMLContent);

?>