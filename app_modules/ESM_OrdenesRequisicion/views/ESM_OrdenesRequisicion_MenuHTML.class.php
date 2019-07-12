<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: ESM_OrdenesRequisicion_MenuHTML
  * Clase Contiene Metodos para el Ingreso de Parametros Iniciales de Inventario
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class ESM_OrdenesRequisicion_MenuHTML
	{
		/**
		* Constructor de la clase
		*/
		function ESM_OrdenesRequisicion_MenuHTML(){}
		 
     
		function Menu($action)
		{
		$accion=$action['volver'];
    $html .= "<script>";
    $html .= " function Paginador(offset)";
    $html .= " { ";
    $html .= "  xajax_Listado_Temporales(offset);";
    $html .= " } ";
    $html .= "</script>";
    $html .= " <script>
            function Buscar(evt)
              {
              var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   
              var keyChar = String.fromCharCode(keyCode);
                  if(keyCode==13)  //Si se pulsa enter da directamente el resultado
                  {
                  xajax_Buscar_OrdenRequisicion(document.getElementById('orden_requisicion_id').value);
                  } 
              }   
             </script>
";
    $html .= "<script>";
    $html .= " function Imprimir(direccion,empresa_id,orden_requisicion_id)  ";
    $html .= "  { ";
    $html .= " var url=direccion+'?empresa_id='+empresa_id+'&orden_requisicion_id='+orden_requisicion_id; ";
    $html .= " window.open(url,'','width=800,height=600,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes'); ";
    $html .= "  }";
    $html .= "</script>";
		$html .= ThemeAbrirTabla('ORDENES DE REQUISICION');
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
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_OrdenesRequisicion','controller','Crear_OrdenesRequisicion')."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."\">CREAR ORDENES DE REQUISICION</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_OrdenesRequisicion','controller','Crear_OrdenesSuministro')."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."\">CREAR ORDENES DE SUMINISTRO</a>";
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
    
    $html .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "        <tr class=\"modulo_table_list_title\">";
    $html .= "          <td>";
    $html .= "            NUMERO DE ORDEN DE REQUISICION";
    $html .= "          </td>";
    $html .= "          <td>";
    $html .= "            <input type=\"text\" id=\"orden_requisicion_id\" name=\"orden_requisicion_id\" class=\"input-text\" style=\"width:100%\" onkeydown=\"Buscar(event)\"> ";
    $html .= "          </td>";
    $html .= "        </tr>";
    $html .= "      </table>";
    $html .= "      <br>";
    $html .= "        <div id=\"listado\"></div>";
    
		$html .= ThemeCerrarTabla();
		
		return $html;
	
		}
    
    
   
 
	}
?>