<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.pseudo.nowrap.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

define('NOWRAP_NORMAL',0);
define('NOWRAP_NOWRAP',1);

class CSSPseudoNoWrap extends CSSPropertyHandler {
  function CSSPseudoNoWrap() { $this->CSSPropertyHandler(false, false); }
  function default_value() { return NOWRAP_NORMAL; }

  function getPropertyCode() {
    return CSS_HTML2PS_NOWRAP;
  }

  function getPropertyName() {
    return '-html2ps-nowrap';
  }
}

CSS::register_css_property(new CSSPseudoNoWrap);
  
?>