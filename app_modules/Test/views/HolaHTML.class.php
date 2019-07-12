<?php
	
		class HolaHTML
	{
	
	function  HolaHTML()
	{}
	
		function FormaMenu($action)
		{
			$html .= ThemeAbrirTabla('Hola MUNDO');
			$html .="<table>";
      $html .="<tr>";
        $html .="<td>";
        $html .="Hola";
        $html .="</td>";
      $html .="</tr>";
      $html .="</table>";
      $html .= ThemeCerrarTabla();
			return $html;
		}
	
    }
?>