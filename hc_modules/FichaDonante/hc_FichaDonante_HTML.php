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
  IncludeClass("ClaseUtil");
  
  class FichaDonante_HTML extends FichaDonante
  {
    function FichaDonante_HTML()
    {
      $this->FichaDonante();
      return true;
    }
    
    function GetForma()
    {
      $this->salida = $this->frmMensajeIngreso("PRUEBA");
      
      return $this->salida;
    }
    
    /**
    * Funcion donde se crea la forma para mostrar  
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $mensaje cadena con el mensaque se se va a mostrar
    * @return string $html cadena con el codigo html de la pagina
    */    
    function frmMensajeIngreso($mensaje)
    {
      $pfj = $this->frmPrefijo;
      $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'')); 
    
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