<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.border.collapse.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

define('BORDER_COLLAPSE', 1);
define('BORDER_SEPARATE', 2);

class CSSBorderCollapse extends CSSPropertyStringSet {
  function CSSBorderCollapse() { 
    $this->CSSPropertyStringSet(true, 
                                true,
                                array('inherit'  => CSS_PROPERTY_INHERIT,
                                      'collapse' => BORDER_COLLAPSE,
                                      'separate' => BORDER_SEPARATE)); 
  }

  function default_value() { 
    return BORDER_SEPARATE; 
  }

  function getPropertyCode() {
    return CSS_BORDER_COLLAPSE;
  }

  function getPropertyName() {
    return 'border-collapse';
  }
}

CSS::register_css_property(new CSSBorderCollapse);

?>