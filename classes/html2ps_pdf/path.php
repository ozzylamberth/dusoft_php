<?php

require_once(HTML2PS_DIR.'path.point.php');
require_once(HTML2PS_DIR.'path.rectangle.php');

class Path {
  var $_points;

  /**
   * Returns a bounding box rectangle object
   *
   * Pre-conditions:
   * - there's at least one point in the path
   */
  function getBbox() {
    if (count($this->_points) < 1) {
      die("Path::getBbox() called for path without points");
    }

    $rect = new Rectangle($this->_points[0]->_clone(),
                          $this->_points[0]->_clone());

    foreach ($this->_points as $point) {
      $rect->ur->x = max($rect->ur->x, $point->x);
      $rect->ur->y = max($rect->ur->y, $point->y);
      $rect->ll->x = min($rect->ll->x, $point->x);
      $rect->ll->y = min($rect->ll->y, $point->y);
    };

    return $rect;
  }

  function Path() {
    $this->clear();
  }

  function clear() {
    $this->_points = array();
  }

  function addPoint($point) {
    $this->_points[] = $point;
  }

  function getPoint($index) {
    return $this->_points[$index];
  }

  function getPoints() {
    return $this->_points;
  }

  function getPointArray() {
    $result = array();
    foreach ($this->_points as $point) {
      $result[] = $point->x;
      $result[] = $point->y;
    };
    return $result;
  }

  function close() {
    $this->addPoint($this->getPoint(0));
  }

  function getPointCount() {
    return count($this->_points);
  }
}

?>