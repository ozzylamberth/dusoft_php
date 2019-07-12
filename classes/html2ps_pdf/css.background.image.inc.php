<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/css.background.image.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

class CSSBackgroundImage extends CSSSubFieldProperty {
  function getPropertyCode() {
    return CSS_BACKGROUND_IMAGE;
  }

  function getPropertyName() {
    return 'background-image';
  }

  function default_value() { 
    return new BackgroundImage(null, null); 
  }

  function parse($value, &$pipeline) {
    global $g_config;
    if (!$g_config['renderimages']) {
      return CSSBackgroundImage::default_value();
    };

    if ($value === 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    // 'url' value
    if (preg_match("/url\((.*[^\\\\]?)\)/is",$value,$matches)) {
      $url = $matches[1];

      $full_url = $pipeline->guess_url(css_remove_value_quotes($url));
      return new BackgroundImage($full_url,
                                 Image::get($full_url, $pipeline));
    }

    // 'none' and unrecognzed values
    return CSSBackgroundImage::default_value();
  }
}

?>