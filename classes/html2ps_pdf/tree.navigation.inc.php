<?php
// $Header: /var/cvs/SIIS/classes/html2ps_pdf/tree.navigation.inc.php,v 1.1 2006/11/27 22:27:48 hugo Exp $

function traverse_dom_tree_pdf(&$root) {
  switch ($root->node_type()) {
  case XML_DOCUMENT_NODE:
    $child = $root->first_child();
    while($child) {
      $body = traverse_dom_tree_pdf($child);
      if ($body) { return $body; }
      $child = $child->next_sibling();
    };
    break;
  case XML_ELEMENT_NODE:    
    if (strtolower($root->tagname()) == "body") { return $root; }

    $child = $root->first_child(); 
    while ($child) {
      $body = traverse_dom_tree_pdf($child);
      if ($body) { return $body; }
      $child = $child->next_sibling();
    };
    
    return null;
  default:
    return null;
  }
};

function dump_tree(&$box, $level) {
  print(str_repeat(" ", $level));
  print(get_class($box).":".$box->uid."\n");

  if (isset($box->content)) {
    for ($i=0; $i<count($box->content); $i++) {
      dump_tree($box->content[$i], $level+1);
    };
  };
};

?>