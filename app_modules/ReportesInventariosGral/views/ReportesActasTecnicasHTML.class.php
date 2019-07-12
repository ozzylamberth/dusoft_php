<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ReportesActasTecnicasHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReportesActasTecnicasHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class ReportesActasTecnicasHTML	
	{
    /**
    * Constructor de la clase
    */
    function ReportesActasTecnicasHTML(){}
    /**
    *
    * @return string
    */
    function Forma($action)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      
 			$html  = $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptDate('/');
      $html .= ThemeAbrirTabla('ACTAS - TECNICAS');
      $html .= "<script>";
      $html .= "
                function Imprimir(direccion,empresa_id,prefijo,numero)
                {
                var url=direccion+\"?empresa_id=\"+empresa_id+\"&prefijo=\"+prefijo+\"&numero=\"+numero;
                window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
                }
                ";
			$html .= "</script>";
      $html .= " <script>";
      $html .= "  function Paginador(variable,offset)";
      $html .= "  {";
      $html .= "   xajax_ActasTecnicas(xajax.getFormValues('productos'),offset)";
      $html .= "  }";
      $html .= " </script>";
      $html .= "<form name=\"productos\" id=\"productos\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"fecha_inicio\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
      $html .= "              </td>\n";
 			$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
      $html .= "            </tr>\n";
		  $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"fecha_final\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\">\n";
      $html .= "              </td>\n";
 			$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
      $html .= "            </tr>\n";
			$html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">NUMERO DE ACTA</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"acta_tecnica_id\" id=\"acta_tecnica_id\" class=\"input-text\">";
      $html .= "              </td>";
      $html .= "			      </tr>\n";
      
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\" onclick=\"xajax_ActasTecnicas(xajax.getFormValues('productos'));\">\n";
   		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
      $html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "		      </table>\n";
			$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      
      $html .= "<div id=\"Contenido\"></div>";
            
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }
       
  }
?>