<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/box.inline.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

require_once(HTML2PS_DIR.'encoding.inc.php');

define('SYMBOL_SHY', code_to_utf8(0xAD));
define('BROKEN_SYMBOL', chr(0xC2));

class LineBox {
  var $top;
  var $right;
  var $bottom;
  var $left;

  function copy() {
    $box = new LineBox;
    $box->top    = $this->top;
    $box->right  = $this->right;
    $box->bottom = $this->bottom;
    $box->left   = $this->left;
    return $box;
  }

  function offset($dx, $dy) {
    $this->top    += $dy;
    $this->bottom += $dy;
    $this->left   += $dx;
    $this->right  += $dx;
  }

  function create(&$box) {
    $lbox = new LineBox;
    $lbox->right  = $box->get_right();
    $lbox->bottom = $box->get_top() - $box->get_baseline() - $box->get_descender();
    $lbox->left   = $box->get_left();
    $lbox->top    = $box->get_top() - $box->get_baseline() + $box->get_ascender();
    return $lbox;
  }

  function LineBox() { }

  function extend(&$box) {
    $base = $box->get_top() - $box->get_baseline();

    $this->top    = max($this->top,    $base + $box->get_ascender());
    $this->right  = max($this->right,  $box->get_right());
    $this->bottom = min($this->bottom, $base - $box->get_descender());

    // Left edge of the line box should never be modified
  }

  function fake_box(&$box) {
    // Create the fake box object

    $fake_state = new CSSState(CSS::get());
    $fake_state->pushState();
    
    $fake = null;
    $fake_box = new BlockBox($fake);
    $fake_box->readCSS($fake_state);

    // Setup fake box size
    $fake_box->put_left($this->left);
    $fake_box->put_width($this->right - $this->left);
    $fake_box->put_top($this->top - $box->baseline);
    $fake_box->put_height($this->top - $this->bottom);

    // Setup padding value
    $fake_box->setCSSProperty(CSS_PADDING, $box->getCSSProperty(CSS_PADDING));

    // Setup fake box border and background
    $fake_box->setCSSProperty(CSS_BACKGROUND, $box->getCSSProperty(CSS_BACKGROUND));
    $fake_box->setCSSProperty(CSS_BORDER, $box->getCSSProperty(CSS_BORDER));
    
    return $fake_box;
  }
}

class InlineBox extends GenericInlineBox {
  function &create(&$root, &$pipeline) {
    // Create contents of this inline box
    if ($root->node_type() == XML_TEXT_NODE) {
      $css_state =& $pipeline->getCurrentCSSState();
      return InlineBox::create_from_text($root->content, 
                                         $css_state->getProperty(CSS_WHITE_SPACE), 
                                         $pipeline);

    } else {
      $box =& new InlineBox();

      $css_state =& $pipeline->getCurrentCSSState();

      $box->readCSS($css_state);

      // Initialize content
      $child = $root->first_child();
      while ($child) {
        $child_box =& create_pdf_box($child, $pipeline);
        $box->add_child($child_box);
        $child = $child->next_sibling();
      };

      // Add fake whitespace box with zero size for the anchor spans 
      // We need this, as "reflow" functions will automatically remove empty inline boxes from the 
      // document tree
      //
      if ($box->is_null()) {
        $css_state->pushState();
        $css_state->setProperty(CSS_FONT_SIZE, Value::fromData(0.01, UNIT_PT));

        $whitespace = WhitespaceBox::create($pipeline);
        $whitespace->readCSS($css_state);

        $box->add_child($whitespace);        

        $css_state->popState();
      };
    }

    return $box;
  }

  function &create_from_text($text, $white_space, &$pipeline) {
    $box =& new InlineBox();
    $box->readCSS($pipeline->getCurrentCSSState());

    // Apply/inherit text-related CSS properties 
    $css_state =& $pipeline->getCurrentCSSState();
    $css_state->pushDefaultTextState();
    
    if ($white_space == WHITESPACE_PRE) {
      $box->init_white_space_pre($text, $pipeline);
    } else {
      $box->init_white_space_normal($text, $pipeline);
    };
    
    // Clear the CSS stack
    $css_state->popState();

    return $box;
  }

  function InlineBox() {
    // Clear the content
    //    $this->content = array();

    // Clear the list of line boxes inside this box
    $this->_lines = array();

    // Call parent's constructor
    $this->GenericInlineBox();
  }

  function init_white_space_pre($raw_content, &$pipeline) {
    // Remove the newfeed at the very beginning / end of the text block
    $raw_content = preg_replace("/^[\r\n]*/", "", $raw_content);
    $raw_content = preg_replace("/[\r\n]*$/", "", $raw_content);

    // Convert text content to series of lines
    $lines = preg_split("/[\r\n]/",$raw_content);

    $size = count($lines);
    for ($i=0; $i<$size; $i++) {
      $line = $lines[$i];
      $this->process_word($line, $pipeline);

      $box =& new BRBox();
      $box->readCSS($pipeline->getCurrentCSSState());
      $this->add_child($box);      
    };
  }

  // Note: as we're trying to use unicode, we must beware that a part of unicode character can match generic \s 
  // declaration; we'll limit ourselves by [\r\n\t ] set!
  //
  function init_white_space_normal($raw_content, &$pipeline) {
    $content = preg_replace("/[\r\n\t ]/",' ',$raw_content);

    // Whitespace-only text nodes sill result on only one whitespace box
    if (trim($content) === "") {
      $this->add_child(WhitespaceBox::create($pipeline));
      return;
    }

    if (preg_match("# #",substr($content,0,1))) {
      $this->add_child(WhitespaceBox::create($pipeline));
    }

    $words = preg_split("/ /",$content);     
    $prefix = "";
    
    $size = count($words);
    
    for ($i=0; $i<$size; $i++) {
      $word = $prefix.$words[$i];

      // Skip zero-length words
      if ($word === "") { continue; }

      // Check if this word is terminated by a partially-completed 
      // unicode symbol; in this case we've made a break here incorrectly on
      // the non-breaking space
      // 
      // So, we'll concatenate whis with with the next word
      // dropping partially parsed unicode symbol and replacing it by a space
      //
      if ($word{strlen($word)-1} == BROKEN_SYMBOL) {
        $prefix = substr($word,0,strlen($word)-1)." ";
        continue;
      };
      $prefix = "";
      
      if ($word !== "") {
        $this->process_word($word, $pipeline);
        
        // we need to make space between words in 2 cases: 
        // 1. if there will be another words in the same text node
        // 2. if it is the last words AND there's space(s) at the end of the text content.
        //    e.g.: text<b>xxx </font>some more text
        if ($i < ($size - 1) || preg_match("#\s#",substr($content,strlen($content)-1,1))) { 
          $this->add_child(WhitespaceBox::create($pipeline));
        };
      };
    };
  }

  // Inherited from GenericFormattedBox

  function process_word($raw_content, &$pipeline) {
    if ($raw_content === "") { return false; }

    $ptr      = 0;
    $word     = "";
    $hyphens  = array();
    $encoding = "iso-8859-1";

    $manager_encoding =& ManagerEncoding::get();

    $text_box = TextBox::create_empty($pipeline);

    $len = strlen($raw_content);
    while ($ptr < $len) {
      $char = $manager_encoding->getNextUTF8Char($raw_content, $ptr);

      // Check if current  char is a soft hyphen  character. It it is,
      // remove it from  the word (as it should  not be draw normally)
      // and store its location
      if ($char == SYMBOL_SHY) {
        $hyphens[] = strlen($word);
      } else {
        $mapping = $manager_encoding->getMapping($char);

        /**
         * If this character is not found in predefined encoding vectors,
         * we'll use "Custom" encoding and add single-character TextBox
         *
         * @TODO: handle characters without known glyph names
         */
        if (is_null($mapping)) {
          /**
           * No mapping to default encoding vectors found for this character
           */
          
          /**
           * Add last word
           */
          if ($word !== "") { 
            $text_box->add_subword($word, $encoding, $hyphens);
          };

          /**
           * Add current symbol
           */
          $custom_char = $manager_encoding->addCustomChar(utf8_to_code($char));
          $text_box->add_subword($custom_char, $manager_encoding->getCustomEncodingName(), $hyphens);
          
          $word = "";
        } else {
          if (isset($mapping[$encoding])) {
            $word .= $mapping[$encoding];
          } else {
            // This condition prevents empty text boxes from appearing; say, if word starts with a national 
            // character, an () - text box with no letters will be generated, in rare case causing a random line 
            // wraps, if container is narrow
            if ($word !== "") {
              $text_box->add_subword($word, $encoding, $hyphens);
            };
            
            $encodings = array_keys($mapping);
            $encoding = $encodings[0];
            
            $add = $mapping[$encoding];

            $word = $add;
            $hyphens = array();
          };
        };
      };
    };

    if ($word !== "") {
      $text_box->add_subword($word, $encoding, $hyphens);
    };

    $this->add_child($text_box);
    return true;
  }

  function show(&$driver) {
    if ($this->getCSSProperty(CSS_POSITION) == POSITION_RELATIVE) {
      // Postpone
      return true;
    };

    return $this->_show($driver);
  }

  function show_postponed(&$driver) {
    return $this->_show($driver);
  }

  function _show(&$driver) {
    // Show line boxes background and borders
    $size = count($this->_lines);
    for ($i=0; $i<$size; $i++) {
      $fake_box = $this->_lines[$i]->fake_box($this);

      $background = $this->getCSSProperty(CSS_BACKGROUND);
      $border     = $this->getCSSProperty(CSS_BORDER);

      $background->show($driver, $fake_box);
      $border->show($driver, $fake_box);
    };

    // Show content
    $size = count($this->content);
    for ($i=0; $i < $size; $i++) {
      if (is_null($this->content[$i]->show($driver))) {
        return null;
      };
    }

    return true;
  }

  // Initialize next line box inside this inline 
  //
  // Adds the next element to _lines array inside the current object and initializes it with the 
  // $box parameters
  // 
  // @param $box child box which will be first in this line box
  // @param $line_no number of line box
  //
  function init_line(&$box, &$line_no) {
    $line_box = LineBox::create($box);
    $this->_lines[$line_no] = $line_box;
  }

  // Extends the existing line box to include the given child 
  // OR starts new line box, if current child is to the left of the box right edge 
  // (which should not happen white the line box is filled)
  //
  // @param $box child box which will be first in this line box
  // @param $line_no number of line box
  //
  function extend_line(&$box, $line_no) {
    if (!isset($this->_lines[$line_no])) {
      // New line box started
      $this->init_line($box, $line_no);
      
      return $line_no;
    };

    // Check if this box starts a new line
    if ($box->get_left() < $this->_lines[$line_no]->right) {
      $line_no++;
      $this->init_line($box, $line_no);
      return $line_no;
    };

    $this->_lines[$line_no]->extend($box);

    return $line_no;
  }

  function merge_line(&$box, $line_no) {
    $start_line = 0;

    if ($line_no > 0 && count($box->_lines) > 0) {
      if ($this->_lines[$line_no-1]->right + EPSILON > $box->_lines[0]->left) {
        $this->_lines[$line_no-1]->right  = max($box->_lines[0]->right,  $this->_lines[$line_no-1]->right);
        $this->_lines[$line_no-1]->top    = max($box->_lines[0]->top,    $this->_lines[$line_no-1]->top);
        $this->_lines[$line_no-1]->bottom = min($box->_lines[0]->bottom, $this->_lines[$line_no-1]->bottom);
        $start_line = 1;
      };
    };

    $size = count($box->_lines);
    for ($i=$start_line; $i<$size; $i++) {
      $this->_lines[] = $box->_lines[$i]->copy();
    };

    return count($this->_lines);
  }
  
  function reflow_static(&$parent, &$context) {
    GenericFormattedBox::reflow($parent, $context);

    // Note that inline boxes (actually SPANS)
    // are never added to the parent's line boxes

    // Move current box to the parent's current coordinates
    // Note that span box will start at the far left of the parent, NOT on its current X!
    // Also, note that inline box can have margins, padding and borders!

    $this->put_left($parent->get_left());
    $this->put_top($parent->get_top() - $this->get_extra_top());

    // first line of the SPAN will be offset to its parent current-x
    // PLUS the left padding of current span!
    $parent->_current_x += $this->get_extra_left();
    $this->_current_x = $parent->_current_x;

    // Note that the same operation IS NOT applied to parent current-y!
    // The padding space is just extended to the top possibly OVERLAPPING the above boxes.

    $this->width = 0;

    // Reflow contents
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      $child =& $this->content[$i];

      // Add current element into _parent_ line box and reflow it
      $child->reflow($parent, $context);
     
      // In general, if inline box centained whitespace box only, 
      // it could be removed during reflow function call;
      // let's check it and skip to next child
      // 
      // if no children left AT ALL (so this box is empty), just exit
      
      // Track the real height of the inline box; it will be used by other functions 
      // (say, functions calculating content height)

      $this->extend_height($child->get_bottom_margin());
    };

    // Apply right extra space value (padding + border + margin)
    $parent->_current_x += $this->get_extra_right();

    // Margins of inline boxes are not collapsed

    if ($this->get_first_data()) {
      $context->pop_collapsed_margin();
      $context->push_collapsed_margin( 0 );
    };
  }

  function reflow_inline() {
    $line_no = 0;
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      $child =& $this->content[$i];

      $child->reflow_inline();

      if (!$child->is_null()) {
        if (is_a($child,"InlineBox")) {
          $line_no = $this->merge_line($child, $line_no);
        } else {
          $line_no = $this->extend_line($child, $line_no);        
        };
      };
    };
  }

  function reflow_whitespace(&$linebox_started, &$previous_whitespace) {
    /**
     * Anchors could have no content at all (like <a name="test"></a>).
     * We should not remove such anchors, as this will break internal links 
     * in the document.
     */
    $dest = $this->getCSSProperty(CSS_HTML2PS_LINK_DESTINATION);
    if ($dest != "") { return; };

    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      $child =& $this->content[$i];
      $child->reflow_whitespace($linebox_started, $previous_whitespace);      
    };

    if ($this->is_null()) {
      $this->parent->remove($this);
    };
  }

  function get_extra_line_left() { 
    return $this->get_extra_left() + ($this->parent ? $this->parent->get_extra_line_left() : 0);
  }

  function get_extra_line_right() { 
    return $this->get_extra_right() + ($this->parent ? $this->parent->get_extra_line_right() : 0);
  }

  /**
   * As "nowrap" properties applied to block-level boxes only, we may use simplified version of
   * 'get_min_width' here
   */
  function get_min_width(&$context) {
    if (isset($this->_cache[CACHE_MIN_WIDTH])) {
      return $this->_cache[CACHE_MIN_WIDTH];
    }

    $content_size = count($this->content);

    /**
     * If box does not have any content, its minimal width is determined by extra horizontal space
     */
    if ($content_size == 0) { 
      return $this->_get_hor_extra(); 
    };

    $minw = $this->content[0]->get_min_width($context);

    for ($i=1; $i<$content_size; $i++) {
      $item = $this->content[$i];
      if (!$item->out_of_flow()) {
        $minw = max($minw, $item->get_min_width($context));
      };
    }

    // Apply width constraint to min width. Return maximal value
    $wc = $this->getCSSProperty(CSS_WIDTH);
    $min_width = max($minw, $wc->apply($minw, $this->parent->get_width())) + $this->_get_hor_extra();

    $this->_cache[CACHE_MIN_WIDTH] = $min_width;
    return $min_width;
  }

  // Restore default behaviour, as this class is a ContainerBox descendant
  function get_max_width_natural(&$context, $limit=10E6) {
    return $this->get_max_width($context, $limit);
  }

  function offset($dx, $dy) {
    $size = count($this->_lines);
    for ($i=0; $i<$size; $i++) {
      $this->_lines[$i]->offset($dx, $dy);
    };
    GenericInlineBox::offset($dx, $dy);

  }
};

?>