<?php
  /**************************************************************************************
  * $Id: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.1 $   
  * @author Manuel Ruiz Fernandez
  *
  ***************************************************************************************/
  IncludeClass("ClaseHTML");
  
  class RiesgoFamiliar_HTML extends RiesgoFamiliar
  {
    function RiesgoFamiliar_HTML()
    {
      $this->RiesgoFamiliar();
      return true;
    }
    function GetForma()
    {
      $pfj = $this->frmPrefijo;
      $evento = $_REQUEST['accion'.$pfj];
      $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
      $mensaje = "Ingreso Riesgo Familiar";
      $this-salida = $this->frmMensajeIngreso($action, $mensaje);
    }
    
    function frmMensajeIngreso($action,$mensaje)
    {
    $html  = ThemeAbrirTabla('MENSAJE');
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "  <tr>\n";
    $html .= "    <td>\n";
    $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
    $html .= "        <tr class=\"normal_10AN\">\n";
    $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
    $html .= "        </tr>\n";
    $html .= "      </table>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\"><br>\n";
    $html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
    $html .= "      </form>";
    $html .= "    </td>";
    $html .= "  </tr>";
    $html .= "</table>";
    $html .= ThemeCerrarTabla();      
    return $html;
  } 
  }
 
?>
