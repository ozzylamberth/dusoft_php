<?php

function ThemeAbrirTabla($titulo){
 return ("<table width=\"95%\"  border=\"1\" align=\"center\" class=\"hc_table\">
  <tr>
    <td align=\"center\" class=\"hc_table_title\">$titulo</td>
  </tr>
<tr>
    <td>");
}


function ThemeCerrarTabla(){
return ("</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<br>");
}

function ThemeAbrirTablaSubModulo($titulo){
 return ("<table width=\"100%\"  border=\"5\" align=\"center\" class=\"hc_table_submodulo\">
  <tr>
    <td align=\"center\" class=\"hc_table_submodulo_title\">$titulo</td>
  </tr>
<tr>
    <td>");
}


function ThemeCerrarTablaSubModulo(){
return ("</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<br>");
}
?>
