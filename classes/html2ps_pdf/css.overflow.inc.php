<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.overflow.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

define('OVERFLOW_VISIBLE',0);
define('OVERFLOW_HIDDEN',1);

class CSSOverflow extends CSSPropertyStringSet {
  function CSSOverflow() { 
    $this->CSSPropertyStringSet(false, 
                                false,
                                array('inherit' => CSS_PROPERTY_INHERIT,
                                      'hidden'  => OVERFLOW_HIDDEN,
                                      'scroll'  => OVERFLOW_HIDDEN,
                                      'auto'    => OVERFLOW_HIDDEN,
                                      'visible' => OVERFLOW_VISIBLE)); 
  }

  function default_value() { 
    return OVERFLOW_VISIBLE; 
  }

  function getPropertyCode() {
    return CSS_OVERFLOW;
  }

  function getPropertyName() {
    return 'overflow';
  }
}

CSS::register_css_property(new CSSOverflow);

?>
