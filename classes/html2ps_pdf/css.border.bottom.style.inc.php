<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.border.bottom.style.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSBorderBottomStyle extends CSSSubProperty {
  function CSSBorderBottomStyle(&$owner) {
    $this->CSSSubProperty($owner);
  }

  function setValue(&$owner_value, &$value) {
    $owner_value->bottom->style = $value;
  }

  function getValue(&$owner_value) {
    return $owner_value->bottom->style;
  }

  function getPropertyCode() {
    return CSS_BORDER_BOTTOM_STYLE;
  }

  function getPropertyName() {
    return 'border-bottom-style';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    return CSSBorderStyle::parse_style($value);
  }
}

?>