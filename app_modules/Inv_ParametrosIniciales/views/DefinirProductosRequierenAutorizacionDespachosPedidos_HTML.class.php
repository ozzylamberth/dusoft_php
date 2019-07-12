<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: DefinirCostosDeVentaProductos_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: DefinirCostosDeVentaProductos_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class DefinirProductosRequierenAutorizacionDespachosPedidos_HTML
	{
		/**
		* Constructor de la clase
		*/
		function DefinirProductosRequierenAutorizacionDespachosPedidos_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		
    
    function main($action,$Empresas,$Grupos)
    {
    
        //Prueba Lector de Codigos de Barras
    $html .= "
           <script language='javascript'>
                document.onkeyup = Buscar_CodigoBarras;   
                function Buscar_CodigoBarras(e)
                    {
                    var valor=document.BuscadorProductos.codigo_barras.value;
                    KeyID = (window.event) ? event.keyCode : e.keyCode;
                    //tecla=(document.all) ? e.keyCode : e.which;

                            if(KeyID==13) 
                            {
                              //window.e.keyCode=0;
                              xajax_Productos_CreadosBuscados('','','','','',valor);
                              //alert('has apretado intro');
                            }

                      }

           </script>"; 
    
    
    
    $accion=$action['volver'];
		$html .="<script>";
    $html .= "function buscar()";
    $html .="{";
    $html .="var grupo_id= document.BuscadorProductos.grupo_id.value;";
    $html .="var clase_id= document.BuscadorProductos.clase_id.value;";
    $html .="var subclase_id= document.BuscadorProductos.subclase_id.value;";
    $html .="var descripcion= document.BuscadorProductos.descripcion.value;";
    $html .="var codigo_barras= document.BuscadorProductos.codigo_barras.value;";
    $html .="xajax_Productos_CreadosBuscados(grupo_id,clase_id,subclase_id,descripcion,codigo_barras);";
    $html .="}";
    $html .="</script>";
    
    
    $html .= "<script>";
    $html .= "  function Paginador_2(offset)\n";
    $html .= "  {";
    $html .= "    xajax_ProductosT(offset);\n";
    $html .= "  }\n";   
    $html .="</script>";
    
    $html .= "<script>";
    $html .= "  function Paginador_3(Grupo_Id,Clase_Id,SubClase_Id,Descripcion,anato,CodigoBarras,offset)\n";
    $html .= "  {";
    $html .= "    xajax_Productos_CreadosBuscados(Grupo_Id,Clase_Id,SubClase_Id,Descripcion,'',CodigoBarras,offset);\n";
    $html .= "  }\n";   
    $html .="</script>";
    
    $html .='<script>
   function acceptNum(evt)
{ 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 46);
}
  
  </script>
  ';
    
    
    
    $html .= ThemeAbrirTabla('DEFINIR PRODUCTOS QUE REQUIEREN AUTORIZACION PARA DESPACHOS Y PEDIDOS');
		
    
 //Buscador de Productos Creados
    $html .= "<form name=\"BuscadorProductos\" method=\"POST\" action=\"#\">";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"2\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
        
    $SelectGrupos = '<SELECT NAME="grupo_id" SIZE="1" class="select" style="width:100%;height:100%">';
		$SelectGrupos .= '<OPTION VALUE="" onclick="xajax_buscar_clases_grupo(this.value);"></OPTION>';
    foreach($Grupos as $key => $gru)
      {	
				$SelectGrupos .= '<OPTION VALUE="'.$gru['grupo_id'].'" onclick="xajax_buscar_clases_grupo(this.value);">'.$gru['grupo_id']." ".$gru['descripcion'].'</OPTION>';
			}
			$SelectGrupos .='</SELECT>';
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\" width=\"40%\">";
    $html .= "GRUPO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= $SelectGrupos;
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "CLASE : ";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<div id=\"select_clases\">Seleccione Grupo...
    <input type=\"hidden\" name=\"clase_id\" value=\"\">
    </div> ";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "SUBCLASE : ";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<div id=\"select_subclases\">
    Seleccione Grupo y Clase...
    <input type=\"hidden\" name=\"subclase_id\" value=\"\">
    </div> ";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION : ";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"descripcion\" size=\"30\" maxlength=\"30\" onkeyup=\"this.value=this.value.toUpperCase();\" style=\"width:100%;height:100%\" >";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "CODIGO DE BARRAS : ";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"codigo_barras\" size=\"30\" maxlength=\"30\" onkeyup=\"Buscar_CodigoBarras(this.value);\" style=\"width:100%;height:100%\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .="       <input type=\"hidden\" name=\"empresa_id\" value='".$CodigoEmpresa."'>";
    $html .="       <input type=\"hidden\" name=\"nombre_empresa\" value='".$NombreEmpresa."'>";
    $html .= "     <input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" name=\"boton\" onclick=\"buscar();\" >";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    $html .="</table>
            </form>";
    
    
    //Se Desplegará acá la pantalla para asignar los %de costos de venta tanto a un tipo de producto como a un producto en particular
    $html .= "    <div id=\"listado_productos\">";
    $html .= "    </div>";
    
    $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
    $html .= ThemeCerrarTabla();
    $html .= "<script>";	
    $html .= "xajax_ProductosT('');";	
    $html .= "</script>";	
    return($html);
    }
  
  }
?>