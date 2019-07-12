<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.border.bottom.width.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSBorderBottomWidth extends CSSSubProperty {
  function CSSBorderBottomWidth(&$owner) {
    $this->CSSSubProperty($owner);
  }

  function setValue(&$owner_value, &$value) {
    if ($value != CSS_PROPERTY_INHERIT) {
      $owner_value->bottom->width = $value->copy();
    } else {
      $owner_value->bottom->width = $value;
    };
  }

  function getValue(&$owner_value) {
    return $owner_value->bottom->width;
  }

  function getPropertyCode() {
    return CSS_BORDER_BOTTOM_WIDTH;
  }

  function getPropertyName() {
    return 'border-bottom-width';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    return Value::fromString($value);
  }
}

?>