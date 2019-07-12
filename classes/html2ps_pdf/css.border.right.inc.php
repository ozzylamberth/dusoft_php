<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.border.right.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSBorderRight extends CSSSubFieldProperty {
  function getPropertyCode() {
    return CSS_BORDER_RIGHT;
  }

  function getPropertyName() {
    return 'border-right';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    $border = CSSBorder::parse($value);
    return $border->right;
  }
}

?>