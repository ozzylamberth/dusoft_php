<?php
  /******************************************************************************
  * $Id: EliminaCargoHTML.class.php,v 1.1 2007/02/21 16:35:53 lorena Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.1 $ 
  * 
  * @autor Lorena Aragon Galindo
  ********************************************************************************/
  
  
  class EliminaCargoHTML
  {
    
    function EliminaCargoHTML(){}
    
    /**********************************************************************************
    * Funcion donde se solicitan los datos para eliminar un cargo
    * 
    * @return array 
    ***********************************************************************************/
    function CrearFormaEliminaCargo($Cuenta,$accionE,$accionC,$mensaje){  
                                   
      
      $html .= ThemeAbrirTabla('ELIMINAR CARGO DE LA CUENTA No. '.$Cuenta);           
      $html .= "<form name=\"formabuscar\" action=\"$accionE\" method=\"post\">";
      $html .= "<table width=\"50%\" align=\"center\" border=0>";
      $html .= "<p class=\"label_error\" align=\"center\">$mensaje</p>";
      $html .= "  <tr>";
      $html .= "    <td colspan=\"2\" class=\"label_mark\" align=\"center\">VA A ELIMINAR UN CARGO DE LA CUENTA No. $Cuenta<BR></td>";
      $html .= "  </tr>";
      $html .= "  <tr>";
      $html .= "    <td class=\"label\">JUSTIFICACION: </td>";
      $html .= "    <td align=\"left\"><textarea cols=\"45\" rows=\"3\" class=\"textarea\" name=\"observacion\"></textarea></td>";
      $html .= "  </tr>";
      $html .= "</table>";      
      $html .= "<BR><table width=\"50%\" align=\"center\" border=0>";
      $html .= "<tr>";
      $html .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Anular\" value=\"ELIMINAR\"></td>";
      $html .= "</form>";      
      $html .= "<form name=\"formabuscar\" action=\"$accionC\" method=\"post\">";
      $html .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
      $html .= "</form>";
      $html .= "</table>";
      $html .= ThemeCerrarTabla();                  
      return $html;   
    }
       
  }
  
  
?>