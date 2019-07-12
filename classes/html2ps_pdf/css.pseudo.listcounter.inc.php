<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.pseudo.listcounter.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSPseudoListCounter extends CSSPropertyHandler {
  function CSSPseudoListCounter() { $this->CSSPropertyHandler(true, false); }
  function default_value() { return 0; }

  function getPropertyCode() {
    return CSS_HTML2PS_LIST_COUNTER;
  }

  function getPropertyName() {
    return '-html2ps-list-counter';
  }

}

CSS::register_css_property(new CSSPseudoListCounter);

?>