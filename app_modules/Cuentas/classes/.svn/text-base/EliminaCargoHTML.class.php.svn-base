<?php
  /******************************************************************************
  * $Id: EliminaCargoHTML.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.7 $ 
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
      $html .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"VOLVER\"></td></tr>";
      $html .= "</form>";
      $html .= "</table>";
      $html .= ThemeCerrarTabla();                  
      return $html;   
    }
    
  /********************************************************************************************************************************
  * Muestra la forma que pregunta la anulacion o activacion de la orden de servicio que cuando de elimina el cargo desde la cuenta.
  
  * @access private
  * @return boolean
  *********************************************************************************************************************************/
  

    function FormaVerificacionAnulacionOS($accionE,$accionV){
        
        $this->salida .= ThemeAbrirTabla('VERIFICACION DEL ESTADO DE LA ORDEN DE SERVICIO');        
        $this->salida .= "<form name=\"formabuscar\" action=\"$accionE\" method=\"post\">";
        $this->salida .= "<table width=\"50%\" align=\"center\" border=0>";
        $this->salida .= "  <tr>";
        $this->salida .= "    <td class=\"label\" align=\"center\">LA ORDEN DE SERVICIO SE ENCUENTRA CUMPLIDA, SELECCIONE QUE DESEA REALIZAR CON LA ORDEN</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "</table>";
        //botones
        $this->salida .= "  <BR><table width=\"50%\" align=\"center\" border=0>";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Anular\" value=\"ANULAR ORDEN\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Activar\" value=\"ACTIVAR ORDEN\">";
        $this->salida .= "  </td></tr>";
        $this->salida .= "</form>";
        $this->salida .= "<form name=\"formabuscar\" action=\"$accionV\" method=\"post\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return $this->salida;
    }

       
  }
  
  
?>