<?php

require_once(HTML2PS_DIR.'value.border.class.php');
require_once(HTML2PS_DIR.'value.border.edge.class.php');

class CSSPseudoTableBorder extends CSSPropertyHandler {
  var $_defaultValue;

  function CSSPseudoTableBorder() {
    $this->CSSPropertyHandler(false, false);

    $this->_defaultValue = BorderPDF::create(array('top'    => array('width' => Value::fromString('2px'), 
                                                                     'color' => array(0,0,0), 
                                                                     'style' => BS_NONE),
                                                   'right'  => array('width' => Value::fromString('2px'), 
                                                                     'color' => array(0,0,0), 
                                                                     'style' => BS_NONE),
                                                   'bottom' => array('width' => Value::fromString('2px'), 
                                                                     'color' => array(0,0,0), 
                                                                     'style' => BS_NONE),
                                                   'left'   => array('width' => Value::fromString('2px'), 
                                                                     'color' => array(0,0,0), 
                                                                     'style' => BS_NONE)));
  }

  function default_value() {
    return $this->_defaultValue;
  }

  function getPropertyCode() {
    return CSS_HTML2PS_TABLE_BORDER;
  }

  function getPropertyName() {
    return '-html2ps-table-border';
  }
}

CSS::register_css_property(new CSSPseudoTableBorder());

?>