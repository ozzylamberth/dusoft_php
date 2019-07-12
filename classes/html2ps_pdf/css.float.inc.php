<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.float.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

define('FLOAT_NONE',0);
define('FLOAT_LEFT',1);
define('FLOAT_RIGHT',2);

class CSSFloat extends CSSPropertyStringSet {
  function CSSFloat() { 
    $this->CSSPropertyStringSet(false, 
                                false,
                                array('left'  => FLOAT_LEFT,
                                      'right' => FLOAT_RIGHT,
                                      'none'  => FLOAT_NONE)); 
  }

  function default_value() { 
    return FLOAT_NONE; 
  }

  function getPropertyCode() {
    return CSS_FLOAT;
  }

  function getPropertyName() {
    return 'float';
  }
}

CSS::register_css_property(new CSSFloat);

?>