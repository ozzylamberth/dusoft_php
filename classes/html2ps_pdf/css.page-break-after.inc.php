<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.page-break-after.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSPageBreakAfter extends CSSPageBreak {
  function getPropertyCode() {
    return CSS_PAGE_BREAK_AFTER;
  }

  function getPropertyName() {
    return 'page-break-after';
  }
}

CSS::register_css_property( new CSSPageBreakAfter);

?>