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
  * @version $Revision: 1.2 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class DefinirCostosDeVentaProductos_HTML
	{
		/**
		* Constructor de la clase
		*/
		function DefinirCostosDeVentaProductos_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		   
    function main($action,$request,$Empresas)
    {
    $CU=AutoCarga::factory("ClaseUtil");
    
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
    
    $html .= $CU->AcceptNum();
    
    $html .= "<script>";
					$html .= "function AdicionarProducto(codigo_producto,Descripcion,precio,sw_porcentaje,porcentaje,valor_inicial,modifica)";
					$html .= "{";
					$html .= "	document.getElementById('codigo_producto').value=codigo_producto;";
					$html .= "	document.getElementById('DescripcionProducto').innerHTML=Descripcion;";
					$html .= "	document.getElementById('precio').value=precio;";
          $html .= "	document.getElementById('valor_inicial').value=precio;";
          
          $html .= "  if(sw_porcentaje == '1')";
          $html .= "	document.getElementById('sw_porcentaje').checked=true;";
          $html .= "    else";
          $html .= "	        document.getElementById('sw_porcentaje').checked=false;";
          
          $html .= "	document.getElementById('porcentaje').value=porcentaje;"; 
          
          $html .= "	document.getElementById('modifica').value=modifica;"; 
          
					$html .= "	OcultarSpan();";
					$html .= "}";
					$html .= "</script>";
					
					$html .= "<script>";
					$html .= "function QuitarProducto()";
					$html .= "{";
					$html .= "	document.getElementById('codigo_producto').value='';";
					$html .= "	document.getElementById('DescripcionProducto').innerHTML='';";
					$html .= "	document.getElementById('precio').value='';";
          $html .= "	document.getElementById('valor_inicial').value='';";
          $html .= "	document.getElementById('modifica').value='';";
          $html .= "	document.getElementById('porcentaje').value='';";
          $html .= "	document.getElementById('sw_porcentaje').checked=false;";
          $html .= "}";
					$html .= "</script>";
					
          
          //$CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CentroUtilidad,$Bodega,$CodigoLista,$offset
					$html .= "<script>";
					$html .= "function Paginador(CodigoProducto,Descripcion,Concentracion,Empresa_Id,Centro_Utilidad,Bodega,CodigoLista,offset)";
					$html .= "{";
					$html .= "	xajax_BuscarProductos(CodigoProducto,Descripcion,Concentracion,Empresa_Id,Centro_Utilidad,Bodega,CodigoLista,offset);";
					$html .= "}";
					$html .= "function PaginadorEmp(offset)";
					$html .= "{";
					$html .= "	xajax_EmpresasT_2(offset);";
					$html .= "}";
					$html .= "</script>";
          
          $html .= "<script>";
					$html .= "function Paginador2(CodigoProducto,Descripcion,Concentracion,Empresa_Id,CodigoLista,offset)";
					$html .= "{";
					$html .= "	xajax_BuscarProductosListaPrecios(CodigoProducto,Descripcion,Concentracion,Empresa_Id,CodigoLista,offset);";
					$html .= "}";
					$html .= "</script>";
    
    
    
    $accion=$action['volver'];
    
    
    $html .= "<script>";
    $html .= "    function ValidarDatos(Formulario)";
    $html .= "    {";
    $html .= "        if(Formulario.codigo_lista==\"\")";
    $html .= "          {";
    $html .= "          document.getElementById('error').innerHTML=\"Error: No se Ha Diligenciado El Codigo de La Lista\";";
    $html .= "          return false;";
    $html .= "          }";
    
    $html .= "        if(Formulario.descripcion==\"\")";
    $html .= "          {";
    $html .= "          document.getElementById('error').innerHTML=\"Error: No se Ha Diligenciado la Descripcion\";";
    $html .= "          return false;";
    $html .= "          }";
    
    $html .= "        xajax_RegistrarListaPrecios(Formulario);";
    
    $html .= "    ";
    $html .= "    }";
    $html .= "</script>";
    
    $html .= "<script>";
    $html .= "    function SeleccionarProducto(Formulario)";
    $html .= "    {";
    $html .= "        if(Formulario.codigo_producto==\"\")";
    $html .= "          {";
    $html .= "          document.getElementById('error').innerHTML=\"Error: No Se Ha Seleccionado Un Producto\";";
    $html .= "          return false;";
    $html .= "          }";
    
    $html .= "        if(Formulario.sw_porcentaje==1 && Formulario.porcentaje==\"\")";
    $html .= "          {";
    $html .= "          document.getElementById('error').innerHTML=\"Error: Si se ha seleccionado que Tiene Porcentaje Adicional, tiene que ingresar el valor.\";";
    $html .= "          return false;";
    $html .= "          }";
    
    $html .= "        if(Formulario.precio==\"\")";
    $html .= "          {";
    $html .= "          document.getElementById('error').innerHTML=\"Error: Tiene Que Estar Registrado Un Precio De Venta.\";";
    $html .= "          return false;";
    $html .= "          }";
    
    //$html .= "          Formulario.precio=parseInt(Formulario.precio);";
    //$html .= "          Formulario.porcentaje=parseInt(Formulario.porcentaje);";
    //$html .= "          Formulario.valor_inicial=parseInt(Formulario.valor_inicial);";
    
    $html .= "        if(isNaN(Formulario.precio))";
    $html .= "          {";
    $html .= "          document.getElementById('error').innerHTML=\"Error: Numero no valido.\";";
    $html .= "          return false;";
    $html .= "          }";
    
    $html .= "        if(isNaN(Formulario.porcentaje))";
    $html .= "          {";
    $html .= "          document.getElementById('error').innerHTML=\"Error: Numero no valido en el Porcentaje.\";";
    $html .= "          return false;";
    $html .= "          }";
    
    
    $html .= "        if(Formulario.precio<=0)";
    $html .= "          {";
    $html .= "          document.getElementById('error').innerHTML=\"Error: El precio No puede ser menor o igual a Cero.\";";
    $html .= "          return false;";
    $html .= "          }";
    
    $html .= "        if(Formulario.precio<Formulario.valor_inicial)";
    $html .= "          {";
    $html .= "          document.getElementById('error').innerHTML=\"Error: El precio No puede ser menor al del Inventario.\";";
    $html .= "          return false;";
    $html .= "          }";
    
    $html .= "          document.getElementById('error').innerHTML=\"\";";
    $html .= "          xajax_RegistrarItemListaPrecios(Formulario);";
    $html .= "    ";
    $html .= "    }";
    $html .= "</script>";
    
    
		$html .="<script>";
    $html .= "function buscar()";
    $html .="{";
    $html .="var grupo_id= document.BuscadorProductos.grupo_id.value;";
    $html .="var clase_id= document.BuscadorProductos.clase_id.value;";
    $html .="var subclase_id= document.BuscadorProductos.subclase_id.value;";
    $html .="var descripcion= document.BuscadorProductos.descripcion.value;";
    $html .="var codigo_barras= document.BuscadorProductos.codigo_barras.value;";
    $html .="var empresa_id= document.BuscadorProductos.empresa_id.value;";
    $html .="var nombre_empresa= document.BuscadorProductos.nombre_empresa.value;";
    $html .="xajax_Productos_CreadosBuscados(grupo_id,clase_id,subclase_id,descripcion,'',codigo_barras,empresa_id,nombre_empresa);";
    $html .="}";
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
    
    
    
    $html .= ThemeAbrirTabla('% COSTOS DE VENTAS');
		$html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\">";
		$html .= "      MENÚ";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    /*
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"#\" onclick=\"xajax_EmpresasT()\"> ASIGNAR POR TIPO DE PRODUCTO</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    */
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"#\" onclick=\"xajax_EmpresasT_2()\">LISTA DE PRECIOS POR EMPRESA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      </table>";
		$html .= "  </td></tr>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
    
    
    //Se Desplegará acá la pantalla para asignar los %de costos de venta tanto a un tipo de producto como a un producto en particular
    $html .= "    <div id=\"sub_pantalla\">";
    $html .= "    </div>";
    $html .= ThemeCerrarTabla();
    $html .= $this->CrearVentana(670,"ASIGNAR POCENTAJE DE COSTO VENTA");
		
    return($html);
    }
   
   
  
  // CREAR LA CAPITA
	function CrearVentana($tmn,$Titulo)
    {
      $html .= "<script>\n";
      $html .= "  var contenedor = 'Contenedor';\n";
      $html .= "  var titulo = 'titulo';\n";
      $html .= "  var hiZ = 4;\n";
      $html .= "  function OcultarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"none\";\n";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "  }\n";
      //Mostrar Span
	  $html .= "  function MostrarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"\";\n";
      $html .= "      Iniciar();\n";
      $html .= "    }\n";
      $html .= "    catch(error){alert(error)}\n";
      $html .= "  }\n";

      $html .= "  function MostrarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xShow(Seccion);\n";
      $html .= "  }\n";
      $html .= "  function OcultarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xHide(Seccion);\n";
      $html .= "  }\n";

      $html .= "  function Iniciar()\n";
      $html .= "  {\n";
      $html .= "    contenedor = 'Contenedor';\n";
      $html .= "    titulo = 'titulo';\n";
      $html .= "    ele = xGetElementById('Contenido');\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    ele = xGetElementById(contenedor);\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
      $html .= "    ele = xGetElementById(titulo);\n";
      $html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
      $html .= "    xMoveTo(ele, 0, 0);\n";
      $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $html .= "    ele = xGetElementById('cerrar');\n";
      $html .= "    xResizeTo(ele,20, 20);\n";
      $html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
      $html .= "  }\n";

      $html .= "  function myOnDragStart(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "    window.status = '';\n";
      $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
      $html .= "    else xZIndex(ele, hiZ++);\n";
      $html .= "    ele.myTotalMX = 0;\n";
      $html .= "    ele.myTotalMY = 0;\n";
      $html .= "  }\n";
      $html .= "  function myOnDrag(ele, mdx, mdy)\n";
      $html .= "  {\n";
      $html .= "    if (ele.id == titulo) {\n";
      $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
      $html .= "    }\n";
      $html .= "    else {\n";
      $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
      $html .= "    }  \n";
      $html .= "    ele.myTotalMX += mdx;\n";
      $html .= "    ele.myTotalMY += mdy;\n";
      $html .= "  }\n";
      $html .= "  function myOnDragEnd(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "  }\n";
      
      
      $html.= "function Cerrar(Elemento)\n";
           $html.= "{\n";
           $html.= "    capita = xGetElementById(Elemento);\n";
           $html.= "    capita.style.display = \"none\";\n";
           $html.= "}\n";
      
      
      
      $html .= "</script>\n";
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";


    
      return $html;
    }    
    
    
  
  }
?>