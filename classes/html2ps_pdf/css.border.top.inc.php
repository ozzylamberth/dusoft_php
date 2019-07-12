<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.border.top.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSBorderTop extends CSSSubFieldProperty {
  function getPropertyCode() {
    return CSS_BORDER_TOP;
  }

  function getPropertyName() {
    return 'border-top';
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