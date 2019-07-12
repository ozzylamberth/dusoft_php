<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: ESM_Facturacion_MenuHTML
  * Clase Contiene Metodos para el Ingreso de Parametros Iniciales de Inventario
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class ESM_Facturacion_MenuHTML
	{
		/**
		* Constructor de la clase
		*/
		function ESM_Facturacion_MenuHTML(){}
		 
     
		function Menu($action)
		{
		$accion=$action['volver'];
		$html  = ThemeAbrirTabla('FACTURACION');
		$html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\">";
		$html .= "      MENÚ";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_Facturacion','controller','Crear_Factura')."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."\">CREAR FACTURA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
   
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_Facturacion','controller','Glosas')."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."\">GLOSAS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_Facturacion','controller','Cortes_diarios')."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."\">CORTES DIARIOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
   
        $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_Facturacion','controller','Cortes_diarios_Descarga')."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."\">DESCARGAR ARCHIVO</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
       
		$html .= "      </table>\n";
		$html .= "  </td></tr>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
		$html .= ThemeCerrarTabla();
		
		return $html;
	
		}
   
 
	}
?>