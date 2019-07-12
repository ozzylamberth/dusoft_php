<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.border.left.style.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSBorderLeftStyle extends CSSSubProperty {
  function CSSBorderLeftStyle(&$owner) {
    $this->CSSSubProperty($owner);
  }

  function setValue(&$owner_value, &$value) {
    $owner_value->left->style = $value;
  }

  function getValue(&$owner_value) {
    return $owner_value->left->style;
  }

  function getPropertyCode() {
    return CSS_BORDER_LEFT_STYLE;
  }

  function getPropertyName() {
    return 'border-left-style';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    return CSSBorderStyle::parse_style($value);
  }
}

?>