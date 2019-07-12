<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.right.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

require_once(HTML2PS_DIR.'value.right.php');

class CSSRight extends CSSPropertyHandler {
  function CSSRight() { 
    $this->CSSPropertyHandler(false, false); 
    $this->_autoValue = ValueRight::fromString('auto');
  }

  function _getAutoValue() {
    return $this->_autoValue->copy();
  }

  function default_value() { 
    return $this->_getAutoValue();
  }

  function parse($value) { 
    return ValueRight::fromString($value);
  }

  function getPropertyCode() {
    return CSS_RIGHT;
  }

  function getPropertyName() {
    return 'right';
  }
}

CSS::register_css_property(new CSSRight);

?>