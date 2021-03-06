<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/box.utils.text-align.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

function ta_left(&$box, &$context, $lastline) {
  // Do nothing; text is left-aligned by default
}

function ta_center(&$box, &$context, $lastline) {
  $delta = $box->_line_length_delta($context) / 2;

  $size = count($box->_line);
  for ($i=0; $i< $size; $i++) {
    $box->_line[$i]->offset($delta, 0);
  };

  $last_box =& $box->_line[$size-1];
  if (isset($last_box->wrapped) && !is_null($last_box->wrapped)) {
    $last_box->offset_wrapped($delta, 0);
  };
}

function ta_right(&$box, &$context, $lastline) {
  $delta = $box->_line_length_delta($context);

  $size = count($box->_line);
  for ($i=0; $i<$size; $i++) {
    $box->_line[$i]->offset($delta, 0);
  };

  $last_box =& $box->_line[$size-1];
  if (isset($last_box->wrapped) && !is_null($last_box->wrapped)) {
    $last_box->offset_wrapped($delta, 0);
  };
}

function ta_justify(&$box, &$context, $lastline) {
  // last line is never justified
  if ($lastline) { return; }

  // If line box contains less that two items, no justification can be done, just return
  if (count($box->_line) < 2) { return; }

  // Calculate extra space to be filled by this line
  $delta = $box->_line_length_delta($context);

  // note that if it is the very first line inside the container, 'text-indent' value
  // should not be taken into account while calculating delta value
  if (count($box->content) > 0) {
    if ($box->content[0]->uid === $box->_line[0]->uid) {
      $delta -= $box->text_indent->calculate($box);
    };
  };

  // if line takes less that MAX_JUSTIFY_FRACTION of available space, no justtification should be done
  if ($delta > $box->_line_length() * MAX_JUSTIFY_FRACTION) {
    return;
  };

  // Calculate offset for each whitespace box
  $whitespace_count = 0;
  $size = count($box->_line);

  // Why $size-1? Ignore whitespace box, if it is located at the very end of 
  // line box

  // Also, ignore whitespace box at the very beginning of the line
  for ($i=1; $i<$size-1; $i++) {
    if (is_a($box->_line[$i],"WhitespaceBox")) {
      $whitespace_count++;
    };
  };

  if ($whitespace_count > 0) {
    $offset = $delta / $whitespace_count;
  } else {
    $offset = 0;
  };
  $num_whitespaces = 0;

  // Offset all boxes in current line box
  $size = count($box->_line);
  for ($i=1; $i < $size; $i++) {
    if (is_a($box->_line[$i],"WhitespaceBox")) {
      $num_whitespaces++;
    };
    $box->_line[$i]->offset($offset*$num_whitespaces, 0);
  };

  $last_box =& $box->_line[$size-1];
  if (isset($last_box->wrapped) && !is_null($last_box->wrapped)) {
    $last_box->offset_wrapped($offset*$num_whitespaces, 0);
  };
}
?>