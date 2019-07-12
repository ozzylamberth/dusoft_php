<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.left.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

require_once(HTML2PS_DIR.'value.left.php');

class CSSLeft extends CSSPropertyHandler {
  function CSSLeft() { 
    $this->CSSPropertyHandler(false, false); 
    $this->_autoValue = ValueLeft::fromString('auto');
  }

  function _getAutoValue() {
    return $this->_autoValue->copy();
  }

  function default_value() { 
    return $this->_getAutoValue();
  }

  function parse($value) { 
    return ValueLeft::fromString($value);
  }

  function getPropertyCode() {
    return CSS_LEFT;
  }

  function getPropertyName() {
    return 'left';
  }
}

CSS::register_css_property(new CSSLeft);

?>