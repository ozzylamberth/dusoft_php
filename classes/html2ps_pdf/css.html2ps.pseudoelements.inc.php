<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.html2ps.pseudoelements.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

define('CSS_HTML2PS_PSEUDOELEMENTS_NONE'  ,0);
define('CSS_HTML2PS_PSEUDOELEMENTS_BEFORE',1);
define('CSS_HTML2PS_PSEUDOELEMENTS_AFTER' ,2);

class CSSHTML2PSPseudoelements extends CSSPropertyHandler {
  function CSSHTML2PSPseudoelements() { 
    $this->CSSPropertyHandler(false, false); 
  }

  function default_value() { 
    return CSS_HTML2PS_PSEUDOELEMENTS_NONE; 
  }

  function parse($value) {
    return $value;
  }

  function getPropertyCode() {
    return CSS_HTML2PS_PSEUDOELEMENTS;
  }

  function getPropertyName() {
    return '-html2ps-pseudoelements';
  }
}

CSS::register_css_property(new CSSHTML2PSPseudoelements);

?>