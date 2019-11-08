<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica_HTML
	{
		/**
		* Constructor de la clase
		*/
		function DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($action,$Empresa_id,$Empresa)
    {
    $accion=$action['volver'];
			  
    $html .= ThemeAbrirTabla('TIEMPO DE ENTREGA DE MEDICAMENTOS POR FARMACIA CON FORMULA MEDICA');
    
    //action del formulario= Donde van los datos del formulario.
		$html .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      FARMACIA";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"40%\">";
		$html .= "      NOMBRE :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .=        $Empresa[0]['empresa'];
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"40%\">";
		$html .= "      DIRECCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .=        $Empresa[0]['direccion'];
    $html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"40%\">";
		$html .= "      TELEFONO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .=        $Empresa[0]['telefonos'];
    $html .= "      </td>";
    $html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"40%\">";
		$html .= "      FAX :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .=        $Empresa[0]['fax'];
    $html .= "      </td>";
    $html .= "      </tr>";
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"40%\">";
		$html .= "      DEPARTAMENTO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .=        $Empresa[0]['departamento'];
    $html .= "      </td>";
    $html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"40%\">";
		$html .= "      MUNICIPIO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .=        $Empresa[0]['municipio'];
    $html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"40%\">";
		$html .= "      CODIGO SGSSS :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .=        $Empresa[0]['codigo_sgsss'];
    $html .= "      </td>";
    $html .= "      </tr>";
		
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"40%\">";
		$html .= "      TIEMPO DE ENTREGA DE MEDICAMENTOS(FORMULA MEDICA) :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .= "      <div id=\"tiempo_entrega\"></div>  ";
    $html .= "      </td>";
    $html .= "      </tr>";
		
		$html .= "      </table>";
          
    
    $html .= "<div id=\"ListadoTercerosProveedores\">\n"; //DIV PARA EL LISTADO DE USUARIOS CON PERMISOS DE DOCUMENTOS DE BODEGA
        
		$html .= "</div>"; //CIERRA DIV
    
    
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
    
    
    $html .= "<script>";
    $html .= "xajax_BuscarTiempoEntregaMedicamentos('".$Empresa_id."');";
    $html .= "</script>";
    
    
    
    
    
    $html .= ThemeCerrarTabla();
            
    return($html);
    }
  
  }
?>