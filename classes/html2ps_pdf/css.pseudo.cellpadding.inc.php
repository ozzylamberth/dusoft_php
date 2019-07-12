<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.pseudo.cellpadding.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSCellPadding extends CSSPropertyHandler {
  function CSSCellPadding() { 
    $this->CSSPropertyHandler(true, false); 
  }

  function default_value() { 
    return Value::fromData(1, UNIT_PX);
  }

  function parse($value) { 
    return Value::fromString($value);
  }

  function getPropertyCode() {
    return CSS_HTML2PS_CELLPADDING;
  }

  function getPropertyName() {
    return '-html2ps-cellpadding';
  }
}

CSS::register_css_property(new CSSCellPadding);

?>