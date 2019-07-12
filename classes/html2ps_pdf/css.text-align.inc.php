<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.text-align.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

define('TA_LEFT',0);
define('TA_RIGHT',1);
define('TA_CENTER',2);
define('TA_JUSTIFY',3);

class CSSTextAlign extends CSSPropertyStringSet {
  function CSSTextAlign() { 
    $this->CSSPropertyStringSet(true, 
                                true,
                                array('inherit' => CSS_PROPERTY_INHERIT,
                                      'left'    => TA_LEFT,
                                      'right'   => TA_RIGHT,
                                      'center'  => TA_CENTER,
                                      'middle'  => TA_CENTER,
                                      'justify' => TA_JUSTIFY)); 
  }
  
  function default_value() { return TA_LEFT; }

  function value2pdf($value) { 
    switch ($value) {
    case TA_LEFT:
      return "ta_left";
    case TA_RIGHT:
      return "ta_right";
    case TA_CENTER:
      return "ta_center";
    case TA_JUSTIFY:
      return "ta_justify";
    default:
      return "ta_left";
    }
  }

  function getPropertyCode() {
    return CSS_TEXT_ALIGN;
  }

  function getPropertyName() {
    return 'text-align';
  }
}

CSS::register_css_property(new CSSTextAlign);

?>