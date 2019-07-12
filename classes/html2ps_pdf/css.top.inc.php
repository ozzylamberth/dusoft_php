<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.top.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

require_once(HTML2PS_DIR.'value.top.php');

class CSSTop extends CSSPropertyHandler {
  function CSSTop() { 
    $this->CSSPropertyHandler(false, false); 
    $this->_autoValue = ValueTop::fromString('auto');
  }

  function _getAutoValue() {
    return $this->_autoValue->copy();
  }

  function default_value() { 
    return $this->_getAutoValue();
  }

  function getPropertyCode() {
    return CSS_TOP;
  }

  function getPropertyName() {
    return 'top';
  }

  function parse($value) { 
    return ValueTop::fromString($value);
  }
}

CSS::register_css_property(new CSSTop);

?>