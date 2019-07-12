<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.white-space.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

define('WHITESPACE_NORMAL',0);
define('WHITESPACE_PRE',   1);
define('WHITESPACE_NOWRAP',2);

class CSSWhiteSpace extends CSSPropertyStringSet {
  function CSSWhiteSpace() { 
    $this->CSSPropertyStringSet(true, 
                                true,
                                array('normal' => WHITESPACE_NORMAL,
                                      'pre'    => WHITESPACE_PRE,
                                      'nowrap' => WHITESPACE_NOWRAP)); 
  }

  function parse($value) {
    $result = parent::parse($value);
    return $result;
  }

  function default_value() { 
    return WHITESPACE_NORMAL; 
  }

  function getPropertyCode() {
    return CSS_WHITE_SPACE;
  }

  function getPropertyName() {
    return 'white-space';
  }
}

CSS::register_css_property(new CSSWhiteSpace);
  
?>