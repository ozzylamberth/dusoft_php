<?php

require_once(HTML2PS_DIR.'value.generic.php');

class BorderWidth extends CSSValue {
  var $left;
  var $right;
  var $top;
  var $bottom;

  function copy() {
    return new BorderWidth($this->top, $this->right, $this->bottom, $this->left);
  }

  function BorderWidth($top, $right, $bottom, $left) {
    $this->left   = $left->copy();
    $this->right  = $right->copy();
    $this->top    = $top->copy();
    $this->bottom = $bottom->copy();
  }
}

?>