<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.min-width.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSMinWidth extends CSSSubFieldProperty {
  function CSSMinWidth(&$owner, $field) {
    $this->CSSSubFieldProperty($owner, $field);
  }

  function getPropertyCode() {
    return CSS_MIN_WIDTH;
  }

  function getPropertyName() {
    return 'min-width';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }
    
    return Value::fromString($value);
  }
}

?>