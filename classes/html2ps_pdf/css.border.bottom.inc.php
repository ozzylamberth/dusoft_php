<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.border.bottom.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSBorderBottom extends CSSSubFieldProperty {
  function getPropertyCode() {
    return CSS_BORDER_BOTTOM;
  }

  function getPropertyName() {
    return 'border-bottom';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    $border = CSSBorder::parse($value);
    return $border->bottom;
  }
}

?>