<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/output.png.class.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

require_once(HTML2PS_DIR.'ot.class.php');
require_once(HTML2PS_DIR.'path.php');
require_once(HTML2PS_DIR.'font_factory.class.php');

class OutputDriverPNG extends OutputDriverGeneric {
  var $_image;

  var $_clipping;

  var $_media;
  var $_heightPixels;
  var $_widthPixels;
  var $_color;
  var $_font;
  var $_path;

  /**
   * This variable  contains an  array of clipping  contexts. Clipping
   * context describes the "active area" and "base" image (image which
   * will take the changes drawn in clipped area).
   *
   * As GD does not support  clipping natively, when new clipping area
   * is  defined,  we  create  new  image. When  clipping  context  is
   * terminated (i.e. by establishing new clipping context, by call to
   * 'restore' or by finishing the image output), only area bounded by
   * clipping region  is copied  to the "base"  image. Note  that This
   * will  increase the  memory  consumption, as  we'll  need to  keep
   * several active images at once.
   */
  var $_clip;

  function _restoreColor() {
    imagecolordeallocate($this->_image, $this->_color[0]);
    array_shift($this->_color);
  }

  function _restoreClip() {
    /**
     * As clipping context images have the same size/scale, we may use
     * the simplest/fastest image copying function
     */
    $clip = $this->_clipping[0];
    imagecopy($clip['image'],
              $this->_image,
              $clip['box']->ll->x,
              $clip['box']->ll->y,
              $clip['box']->ll->x,
              $clip['box']->ll->y,
              $clip['box']->getWidth(),
              $clip['box']->getHeight());

    /**
     * Now we should free image allocated for the clipping context to avoid memory leaks
     */
    imagedestroy($this->_image);
    $this->_image = $clip['image'];

    /**
     * Remove clipping context from the stack
     */
    array_shift($this->_clipping);
  }

  function _saveColor($rgb) {
    $color = imagecolorallocate($this->_image, $rgb[0], $rgb[1], $rgb[2]);
    array_unshift($this->_color, array('rgb'    => $rgb,
                                       'object' => $color));
  }

  function _saveClip($box) {
    /**
     * Initialize clipping  context record and add it  to the clipping
     * stack
     */
    $clip = array('image' => $this->_image,
                  'box'   => $box);
    array_unshift($this->_clipping, $clip);

    /**
     * Create a copy of current image for the clipping context
     */
    $width  = imagesx($clip['image']);
    $height = imagesy($clip['image']);
    $this->_image = imagecreatetruecolor($width,
                                         $height);
    imagecopy($this->_image, 
              $clip['image'],
              0,0,
              0,0,
              $width, $height);
  }

  function _getCurrentColor() {
    return $this->_color[0]['object'];
  }

  function _setColor($color) {
    imagecolordeallocate($this->_image, $this->_color[0]['object']);
    $this->_color[0] = $color;
  }

  function _setFont($typeface, $encoding, $size) {
    global $g_font_resolver_pdf;
    $fontfile = $g_font_resolver_pdf->ttf_mappings[$typeface];

    $font = $this->_font_factory->getTrueType($typeface, $encoding);
    $ascender = $font->ascender() / 1000;

    $this->_font[0] = array('font'     => $typeface,
                            'encoding' => $encoding,
                            'size'     => $size,
                            'ascender' => $ascender);
  }

  function _getFont() {
    return $this->_font[0];
  }

  function _drawLine($x1, $y1, $x2, $y2) {
    imageline($this->_image, $x1, $y1, $x2, $y2, $this->_color[0]['object']);
  }
 
  /**
   * Note that "paper space" have Y coordinate axis directed to the bottom,
   * while images have Y coordinate axis directory to the top
   */
  function _fixCoords(&$x, &$y) {
    $x = $this->_fixCoordX($x);
    $y = $this->_fixCoordY($y);
  }

  function _fixCoordX($x) {
    return 
      floor($x * $this->_widthPixels / mm2pt($this->_media->width()));
  }

  function _fixCoordY($y) {
    return 
      floor($this->_heightPixels - 
            $y * $this->_heightPixels / mm2pt($this->_media->height()));
  }

  function _fixSizes(&$x, &$y) {
    $x = $this->_fixSizeX($x);
    $y = $this->_fixSizeY($y);
  }

  function _fixSizeX($x) {
    return ceil($x * $this->_widthPixels / mm2pt($this->_media->width()));
  }

  function _fixSizeY($y) {
    return ceil($y * $this->_heightPixels / mm2pt($this->_media->height()));
  }

  function OutputDriverPNG() {
    $this->OutputDriverGeneric();

    $this->_color    = array();
    $this->_font     = array();
    $this->_path     = new Path;
    $this->_clipping = array();

    $this->_font_factory = new FontFactory();
  }

  function reset(&$media) {
    parent::reset($media);

    /**
     * Store reference to media object for future use
     */
    $this->_media =& $media;

    /**
     * Here we use a small hack; media height and width (in millimetres) match
     * the size of screenshot (in pixels), so we take them as-is
     */
    $this->_heightPixels = $media->height();
    $this->_widthPixels  = $media->width();
    
    $this->_image = imagecreatetruecolor($this->_widthPixels, 
                                         $this->_heightPixels);
    /**
     * Render white background
     */
    $white = imagecolorallocate($this->_image, 255,255,255);
    imagefill($this->_image, 0,0,$white);
    imagecolordeallocate($this->_image, $white);

    $this->_color[0] = array('rgb'    => array(0,0,0),
                             'object' => imagecolorallocate($this->_image, 0,0,0));

    /**
     * Setup initial clipping region
     */
    $this->_saveClip(new Rectangle(new Point(0,
                                             0), 
                                   new Point($this->_widthPixels-1, 
                                             $this->_heightPixels-1)));
  }

  function add_link($x, $y, $w, $h, $target) { /* N/A */ }
  function add_local_link($left, $top, $width, $height, $anchor) { /* N/A */ }

  function circle($x, $y, $r) { 
    imageellipse($this->_image, 
                 $x,
                 $y,
                 $r*2,  // width = diameter
                 $r*2,  // height = diameter
                 $this->_getCurrentColor());
  }

  function clip() {
    /**
     * Only  rectangular  clipping  areas  are  supported;  we'll  use
     * bounding box of  current path for clipping. If  current path is
     * an rectangle, bounding box will match the path itself.
     */
    $box = $this->_path->getBbox();

    /**
     * Convert bounding from media coordinates
     * to output device coordinates
     */
    $this->_fixCoords($box->ll->x, $box->ll->y);
    $this->_fixCoords($box->ur->x, $box->ur->y);
    $box->normalize();

    /**
     * Add a clipping context information
     */
    $this->_restoreClip();
    $this->_saveClip($box);

    /**
     * Reset path after clipping have been applied
     */
    $this->_path = new Path;
  }

  function close() { 
    /**
     * A small hack; as clipping  context is save every time 'save' is
     * called, we may deterine the number of graphic contexts saved by
     * the size of clipping context stack
     */
    while (count($this->_clipping) > 0) {
      $this->restore();
    };

    imagepng($this->_image, $this->get_filename());
    imagedestroy($this->_image);
  }

  function closepath() {
    $this->_path->close();
  }

  function content_type() { 
    return ContentType::png();
  }

  function dash($x, $y) { }
  function decoration($underline, $overline, $strikeout) { }

  function error_message() { 
    return "OutputDriverPNG: generic error";
  }
  
  function field_multiline_text($x, $y, $w, $h, $value, $field_name) { /* N/A */ }
  function field_text($x, $y, $w, $h, $value, $field_name) { /* N/A */ }
  function field_password($x, $y, $w, $h, $value, $field_name) { /* N/A */ }
  function field_pushbutton($x, $y, $w, $h) { /* N/A */ }
  function field_pushbuttonimage($x, $y, $w, $h, $field_name, $value, $actionURL) { /* N/A */ }
  function field_pushbuttonreset($x, $y, $w, $h) { /* N/A */ }
  function field_pushbuttonsubmit($x, $y, $w, $h, $field_name, $value, $actionURL) { /* N/A */ }
  function field_checkbox($x, $y, $w, $h, $name, $value) { /* N/A */ }
  function field_radio($x, $y, $w, $h, $groupname, $value, $checked) { /* N/A */ }
  function field_select($x, $y, $w, $h, $name, $value, $options) { /* N/A */ }

  function fill() { 
    $coords = $this->_path->getPointArray();
    $size   = $this->_path->getPointCount();

    for ($i=0; $i<$size; $i++) {
      $this->_fixCoords($coords[$i*2], $coords[$i*2+1]);
    };

    imagefilledpolygon($this->_image, 
                       $coords,
                       $size,
                       $this->_color[0]['object']);
    $this->_path = new Path;
  }

  function font_ascender($name, $encoding) {
    $font = $this->_font_factory->getTrueType($name, $encoding);
    return $font->ascender() / 1000;
  }

  function font_descender($name, $encoding) {
    $font = $this->_font_factory->getTrueType($name, $encoding);
    return -$font->descender() / 1000;
  }

  function get_bottom() {}

  /**
   * Image output always contains only one page
   */
  function get_expected_pages() {
    return 1;
  }

  function image($image, $x, $y, $scale) {
    $this->image_scaled($image, $x, $y, $scale, $scale);
  }

  function image_scaled($image, $x, $y, $scale_x, $scale_y) {
    $this->_fixCoords($x, $y);

    $sx = imagesx($image);
    $sy = imagesy($image);

    /**
     * Get image size in device coordinates
     */
    $dx = $sx*$scale_x;
    $dy = $sy*$scale_y;
    $this->_fixSizes($dx, $dy);

    imagecopyresampled($this->_image, $image, 
                       $x, $y-$dy,
                       0, 0,
                       $dx, $dy,
                       $sx, $sy);
  }

  function image_ry($image, $x, $y, $height, $bottom, $ox, $oy, $scale) {
    $base_y = floor($this->_fixCoordY($bottom));
    $this->_fixCoords($x, $y);
    $dest_height = floor($this->_fixSizeY($height));
    $start_y = $y - $dest_height;

    $sx = imagesx($image);
    $sy = imagesy($image);
    $dx = $this->_fixSizeX($sx * $scale);
    $dy = $this->_fixSizeY($sy * $scale);

    $cx = $x;
    $cy = $start_y - ceil($this->_fixSizeY($oy) / $dest_height) * $dest_height;
    while ($cy < $base_y) {
      imagecopyresampled($this->_image, $image,
                         $cx, $cy,
                         0, 0,
                         $dx, $dy,
                         $sx, $sy);
      $cy += $dest_height;
    };
  }

  function image_rx($image, $x, $y, $width, $right, $ox, $oy, $scale) {
    $base_x = floor($this->_fixCoordX($right));
    $this->_fixCoords($x, $y);
    $dest_width = floor($this->_fixSizeX($width));
    $start_x = $x - $dest_width;

    $sx = imagesx($image);
    $sy = imagesy($image);
    $dx = $this->_fixSizeX($sx * $scale);
    $dy = $this->_fixSizeY($sy * $scale);

    $cx = $start_x - ceil($this->_fixSizeX($oy) / $dest_width) * $dest_width;

    $cy = $y - $dy;

    while ($cx < $base_x) {
      imagecopyresampled($this->_image, $image,
                         $cx, $cy,
                         0, 0,
                         $dx, $dy,
                         $sx, $sy);
      $cx += $dest_width;
    };
  }

  function image_rx_ry($image, $x, $y, $width, $height, $right, $bottom, $ox, $oy, $scale) {
    $base_x = floor($this->_fixCoordX($right));
    $base_y = floor($this->_fixCoordY($bottom));
    $this->_fixCoords($x, $y);
    $dest_width  = floor($this->_fixSizeX($width));
    $dest_height = floor($this->_fixSizeY($height));
    $start_x = $x - $dest_width;
    $start_y = $y - $dest_height;

    $sx = imagesx($image);
    $sy = imagesy($image);
    $dx = $this->_fixSizeX($sx * $scale);
    $dy = $this->_fixSizeY($sy * $scale);

    $cx = $start_x - ceil($this->_fixSizeX($ox) / $dest_width)  * $dest_width;
    $cy = $start_y - ceil($this->_fixSizeY($oy) / $dest_height) * $dest_height;

    while ($cy < $base_y) {
      while ($cx < $base_x) {
        imagecopyresampled($this->_image, $image,
                           $cx, $cy,
                           0, 0,
                           $dx, $dy,
                           $sx, $sy);
        $cx += $dest_width;
      };
      $cx = $start_x - ceil($this->_fixSizeX($ox) / $dest_width)  * $dest_width;
      $cy += $dest_height;
    };
  }

  function lineto($x, $y) { 
    $this->_path->addPoint(new Point($x, $y));
  }

  function moveto($x, $y) { 
    $this->_path->clear();
    $this->_path->addPoint(new Point($x, $y));
  }

  function new_form($name) { /* N/A */ }
  function next_page() { /* N/A */ }
  function release() { }

  /**
   * Note: _restoreClip  will change current image object,  so we must
   * release all  image-dependent objects before  call to _restoreClip
   * to ensure resources are released correctly
   */
  function restore() { 
    $this->_restoreColor();
    $this->_restoreClip();
  }

  /**
   * Note:  _saveClip will  change current  image object,  so  we must
   * create  all image-dependent  objects after  call to  _saveClip to
   * ensure resources are created correctly
   */
  function save() {
    $this->_saveClip($this->_clipping[0]['box']);
    $this->_saveColor($this->_color[0]['rgb']);
  }

  function setfont($name, $encoding, $size) {
    $this->_setFont($name, $encoding, $size);
    return true;
  }

  function setlinewidth($x) { 
    $dummy = 0;
    $this->_fixSizes($x, $dummy);
    imagesetthickness($this->_image, $x);
  }

  function setrgbcolor($r, $g, $b)  { 
    $color = array('rgb'    => array($r, $g, $b),
                   'object' => imagecolorallocate($this->_image, $r*255, $g*255, $b*255));
    $this->_setColor($color);
  }

  function set_watermark($text) { }

  function show_xy($text, $x, $y) {
    $this->_fixCoords($x, $y);

    $font = $this->_getFont();
    $converter = Converter::create();

    global $g_font_resolver_pdf;
    $fontFile = $g_font_resolver_pdf->ttf_mappings[$font['font']];

    $fontSize = $font['size'];
    $dummy = 0;
    $this->_fixSizes($dummy, $fontSize);

    $utf8_string = $converter->to_utf8($text, $font['encoding']);
    
    imagefttext($this->_image, 
                $fontSize * $font['ascender'], 
                0,
                $x,
                $y,
                $this->_getCurrentColor(),
                TTF_FONTS_REPOSITORY.$fontFile, 
                $utf8_string);
  }
  
  /**
   * Note: the koefficient is just a magic number; I'll need to examine the
   * imagefttext behavior more closely
   */
  function stringwidth($string, $name, $encoding, $size) { 
    $font = $this->_font_factory->getTrueType($name, $encoding);
    return Font::points($size, $font->stringwidth($string))*1.25;
  }

  function stroke() { 
    $coords = $this->_path->getPointArray();
    $size   = $this->_path->getPointCount();

    for ($i=0; $i<$size; $i++) {
      $this->_fixCoords($coords[$i*2], $coords[$i*2+1]);
    };

    imagepolygon($this->_image, 
                 $coords,
                 $size,
                 $this->_color[0]['object']);
    $this->_path = new Path;
  }
}
?>