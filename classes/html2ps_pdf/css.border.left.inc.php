<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.border.left.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSBorderLeft extends CSSSubFieldProperty {
  function getPropertyCode() {
    return CSS_BORDER_LEFT;
  }

  function getPropertyName() {
    return 'border-left';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    $border = CSSBorder::parse($value);
    return $border->left;
  }
}

?>