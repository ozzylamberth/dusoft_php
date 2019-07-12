<?php
  /**
  * @package DUANA & CIA LTDA
  * @version $Id: DefinirCostosDeVentaProductos_HTML.class.php,
  * @copyright (C) 2012 DUANA & CIA LTDA
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: DefinirCostosDeVentaProductos_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del M�dulo
  *
  * @package DUANA & CIA LTDA
  * @version $Revision: 1.0 $
  * @copyright (C) 2012 DUANA & CIA LTDA
  * @author Mauricio Adrian Medina Santacruz
  */

	class DefinirCostosDeVentaProductos_HTML
	{
		/**
		* Constructor de la clase
		*/
		function DefinirCostosDeVentaProductos_HTML(){ }
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		   
    function main($action,$request,$Empresas)
    {      
     $CU=AutoCarga::factory("ClaseUtil");
     if(!isset($html)){ $html = ''; }
    
     //Prueba Lector de Codigos de Barras
     $html .= "
           <script language='javascript'>
                document.onkeyup = Buscar_CodigoBarras;   
                function Buscar_CodigoBarras(e)
                    {
                    if(document.BuscadorProductos != undefined){
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
                    }                    
           </script>"; 
    
    $html .= $CU->AcceptNum();
    
    $html .= "<script>";
					$html .= "function AdicionarProducto(codigo_producto,Descripcion,precio,sw_porcentaje,porcentaje,valor_inicial,modifica)\n";
					$html .= "{\n";
					$html .= "	document.getElementById('codigo_producto').value=codigo_producto;\n";
					$html .= "	document.getElementById('DescripcionProducto').innerHTML=Descripcion;\n";
					$html .= "	document.getElementById('precio').value=precio;\n";

          $html .= "	document.getElementById('valor_inicial').value=precio;\n";
          
          $html .= "  if(sw_porcentaje == '1')\n";
          $html .= "	document.getElementById('sw_porcentaje').checked=true;\n";
          $html .= "    else\n";
          $html .= "	     document.getElementById('sw_porcentaje').checked=false;\n";
          
          $html .= "	document.getElementById('porcentaje').value=porcentaje;\n";
          
          $html .= "	document.getElementById('modifica').value=modifica;\n";
          
          $html .= "	OcultarSpan();\n";
          $html .= "}\n";
          $html .= "</script>\n";

          $html .= "<script>\n";
          $html .= "function QuitarProducto()\n";
          $html .= "{";
          $html .= "	document.getElementById('codigo_producto').value='';\n";
          $html .= "	document.getElementById('DescripcionProducto').innerHTML='';\n";
          $html .= "	document.getElementById('precio').value='';\n";
          $html .= "	document.getElementById('valor_inicial').value='';\n";
          $html .= "	document.getElementById('modifica').value='';\n";
          $html .= "	document.getElementById('porcentaje').value='';\n";
          $html .= "	document.getElementById('sw_porcentaje').checked=false;\n";
          $html .= "}\n";
          $html .= "</script>\n";
					
          
          //$CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CentroUtilidad,$Bodega,$CodigoLista,$offset
					$html .= "<script>\n";
					$html .= "function Paginador(CodigoProducto,Descripcion,Concentracion,Empresa_Id,Centro_Utilidad,Bodega,CodigoLista,offset)\n";
					$html .= "{\n";
					$html .= "	xajax_BuscarProductos(CodigoProducto,Descripcion,Concentracion,Empresa_Id,Centro_Utilidad,Bodega,CodigoLista,offset);\n";
					$html .= "}\n";
					$html .= "</script>\n";
          
          $html .= "<script>\n";
					$html .= "function Paginador2(CodigoProducto,Descripcion,Concentracion,Empresa_Id,CodigoLista,Centro,offset)\n";
					$html .= "{\n";
					$html .= "	xajax_BuscarProductosListaPrecios(CodigoProducto,Descripcion,Concentracion,Empresa_Id,CodigoLista,offset,Centro);\n";
					$html .= "}\n";
					$html .= "</script>\n";
    
    
    
    $accion=$action['volver'];
    
    
    $html .= "<script>";
    $html .= "    function ValidarDatos(Formulario)\n";
    $html .= "    {\n";
    $html .= "        if(Formulario.codigo_lista==\"\")\n";
    $html .= "          {\n";
    $html .= "          document.getElementById('error').innerHTML=\"Error: No se Ha Diligenciado El Codigo de La Lista\";\n";
    $html .= "          return false;\n";
    $html .= "          }\n";
    
    $html .= "        if(Formulario.descripcion==\"\")\n";
    $html .= "          {\n";
    $html .= "          document.getElementById('error').innerHTML=\"Error: No se Ha Diligenciado la Descripcion\";\n";
    $html .= "          return false;\n";
    $html .= "          }\n";
    
    $html .= "        xajax_RegistrarListaPrecios(Formulario);\n";
    
    $html .= "    \n";
    $html .= "    }\n";
    $html .= "</script>\n";
    
    $html .= "<script>\n";
    //$html .= "    console.log('Eyyy');";
    $html .= "    function SeleccionarProducto(Formulario)\n";
    $html .= "    {\n";
    $html .= "        var url = '/APP/PRUEBAS_LINA_OSPINA/app_modules/Inv_Listas_Precios/classes/VerificarPrecios.class.php';\n";
    $html .= "        var xhttp = '';\n";
    $html .= "        if (window.XMLHttpRequest) {\n";
    $html .= "            xhttp = new XMLHttpRequest();\n";
    $html .= "        } else {\n";
    $html .= "            xhttp = new ActiveXObject(\"Microsoft.XMLHTTP\");\n";
    $html .= "        }\n";
    $html .= "        xhttp.onreadystatechange = function() {\n";
    $html .= "          if (this.readyState == 4 && this.status == 200) {\n";
    $html .= "            ultima_compra_string = this.responseText;\n";
    $html .= "            ultima_compra_float = parseFloat(ultima_compra_string);\n";
    $html .= "            if(window.console !== undefined){\n";
    $html .= "               console.log('Precio: '+Formulario.precio+' - Ultima: '+ultima_compra_string);\n";
    $html .= "            }\n";
    $html .= "            if(Formulario.codigo_producto==\"\")\n";
    $html .= "            {\n";
    $html .= "              document.getElementById('error').innerHTML=\"Error: No Se Ha Seleccionado Un Producto\";\n";
    $html .= "              return false;\n";
    $html .= "            }\n";
    $html .= "            if(Formulario.sw_porcentaje==1 && Formulario.porcentaje==\"\")\n";
    $html .= "            {\n";
    $html .= "              document.getElementById('error').innerHTML=\"Error: Si se ha seleccionado que Tiene Porcentaje Adicional, tiene que ingresar el valor.\";\n";
    $html .= "              return false;\n";
    $html .= "            }\n";
    $html .= "            if(Formulario.precio==\"\")\n";
    $html .= "              {\n";
    $html .= "              document.getElementById('error').innerHTML=\"Error: Tiene Que Estar Registrado Un Precio De Venta.\";\n";
    $html .= "              return false;\n";
    $html .= "              }\n";
    $html .= "            if(isNaN(Formulario.precio))\n";
    $html .= "              {\n";
    $html .= "              document.getElementById('error').innerHTML=\"Error: Numero no valido.\";\n";
    $html .= "              return false;\n";
    $html .= "              }\n";
    $html .= "            if(isNaN(Formulario.porcentaje))\n";
    $html .= "              {\n";
    $html .= "              document.getElementById('error').innerHTML=\"Error: Numero no valido en el Porcentaje.\";\n";
    $html .= "              return false;\n";
    $html .= "              }\n";
    $html .= "            if(Formulario.precio<=0)\n";
    $html .= "              {\n";
    $html .= "              document.getElementById('error').innerHTML=\"Error: El precio No puede ser menor o igual a Cero.\";\n";
    $html .= "              return false;\n";
    $html .= "              }\n";
    $html .= "            if(Formulario.precio<=ultima_compra_float)\n";
    $html .= "            {\n";
    $html .= "              document.getElementById('error').innerHTML=\"Error: El precio Tiene que ser mayor al del Inventario.\";\n";
    $html .= "              return false;\n";
    $html .= "            }\n";

    $html .= "            document.getElementById('error').innerHTML=\"\";\n";
    $html .= "            xajax_RegistrarItemListaPrecios(Formulario);\n";
    $html .= "          }\n";
    $html .= "          \n";
    $html .= "        };\n";

    $html .= "        xhttp.open('POST', url, true);\n";
    $html .= "        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');\n";
    $html .= "        xhttp.send('accion=refreshInitialValue&producto='+Formulario.codigo_producto+'&empresa='+Formulario.empresa_id);\n";
    $html .= "    };\n";
    $html .= "</script>\n";
    
    
	$html .= "<script>\n";
    $html .= "   function buscar()\n";
    $html .= "   {\n";
    $html .= "      var grupo_id= document.BuscadorProductos.grupo_id.value;\n";
    $html .= "      var clase_id= document.BuscadorProductos.clase_id.value;\n";
    $html .= "      var subclase_id= document.BuscadorProductos.subclase_id.value;\n";
    $html .= "      var descripcion= document.BuscadorProductos.descripcion.value;\n";
    $html .= "      var codigo_barras= document.BuscadorProductos.codigo_barras.value;\n";
    $html .= "      var empresa_id= document.BuscadorProductos.empresa_id.value;\n";
    $html .= "      var nombre_empresa= document.BuscadorProductos.nombre_empresa.value;\n";
    $html .= "      xajax_Productos_CreadosBuscados(grupo_id,clase_id,subclase_id,descripcion,'',codigo_barras,empresa_id,nombre_empresa);\n";
    $html .= "   }\n";
    $html .= "</script>\n";

    $html .= "<script>\n";
    $html .= "  function updateList()\n";
    $html .= "  {\n";
    $html .= "      var lista_value = document.getElementById('select_listas_precios').value; \n";
    $html .= "      var empresa_id = document.getElementById(\"empresa_id\").value;  \n";
    $html .= "      var centro_utilidad = document.getElementById(\"centro_utilidad\").value; \n";
    $html .= "      var bodega = document.getElementById(\"bodega\").value; \n";
    $html .= "      var url = 'app_modules/Inv_Listas_Precios/classes/VerificarPrecios.class.php';\n";
    $html .= "      var xhttp;\n";
    $html .= "      xhttp = new XMLHttpRequest();\n";
    $html .= "      xhttp.onreadystatechange = function() {\n";
    $html .= "          if (this.readyState == 4 && this.status == 200) {\n";
    $html .= "            initForm();\n";
    $html .= "            document.getElementById(\"select_listas_precios\").innerHTML = this.responseText;\n";
    $html .= "            document.getElementById(\"lista_0\").removeAttribute(\"selected\");\n";
    $html .= "            document.getElementById(\"lista_1\").setAttribute(\"selected\", true);\n";
    $html .= "            \n";
    $html .= "            document.getElementById(\"btn_search2\").setAttribute(\"onclick\", \"xajax_SeleccionDeProductos('\"+empresa_id+\"', '\"+centro_utilidad+\"', '\"+bodega+\"', '\"+lista_value+\"')\");\n";
    $html .= "          }\n";
    $html .= "      };\n";
    $html .= "      xhttp.open('POST', url, true);\n";
    $html .= "      xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');\n";
    $html .= "      var empresa_id = document.getElementById(\"empresa_id\").value;\n";
    $html .= "      document.getElementById(\"DescripcionProducto\").style.cursor = \"context-menu\";\n";
    $html .= "      xhttp.send('accion=tipos_listas_precios&empresa='+empresa_id+'&centro='+centro_utilidad); \n";
    $html .= "  }\n";
    $html .= "  function AdicionarProducto_0(lista_codigo, lista_nombre, codigo_producto, descripcion, precio, sw_porcentaje, porcentaje, valor_inicial, n)\n";
    $html .= "  {\n";
    $html .= "      document.getElementById('select_listas_precios').innerHTML = \"<option value=\"+lista_codigo+\" selected>\"+lista_nombre+\"</option>\";\n";
    $html .= "      resetValueWithOutList();\n";
    $html .= "      btns_update();\n";
    $html .= "      document.getElementById(\"title_accion\").innerHTML = \"ACTUALIZANDO PRODUCTO\";\n";
    $html .= "      AdicionarProducto(codigo_producto, descripcion, precio, sw_porcentaje, porcentaje, valor_inicial, n);\n";
    $html .= "  }\n";

    $html .= "  function resetValueWithOutList()";
    $html .= "  {";
    $html .= "     document.getElementById(\"codigo_producto\").value = \"\";";
    $html .= "     document.getElementById(\"DescripcionProducto\").innerHTML = \"\";";
    $html .= "     document.getElementById(\"sw_porcentaje\").checked = false;";
    $html .= "     document.getElementById(\"porcentaje\").value = \"\";";
    $html .= "     document.getElementById(\"precio\").value = \"\";";
    $html .= "  }";

    $html .= "  function resetValueWithList()";
    $html .= "  {";
    $html .= "     resetValueWithOutList();";
    $html .= "     updateList();";
    $html .= "     btns_insert();";
    $html .= "     document.getElementById(\"btn_search\").style.display = \"inline-block\";";
    $html .= "     document.getElementById(\"btn_search2\").style.display = \"none\";";
    $html .= "     document.getElementById(\"btn_search2\").setAttribute(\"onclick\", \"\");";
    $html .= "     document.getElementById(\"title_accion\").innerHTML = \"AGREGANDO PRODUCTO\";";
    $html .= "     document.getElementById(\"error\").innerHTML = \"\";";
    $html .= "  }";

    $html .= "  function btns_insert()";
    $html .= "  {";
    $html .= "     var lista_value = document.getElementById('select_listas_precios').value; ";
    $html .= "     var empresa_id = document.getElementById(\"empresa_id\").value;  ";
    $html .= "     var centro_utilidad = document.getElementById(\"centro_utilidad\").value; ";
    $html .= "     var bodega = document.getElementById(\"bodega\").value; ";
    $html .= "     document.getElementById(\"btn_search2\").setAttribute(\"onclick\", \"xajax_SeleccionDeProductos('\"+empresa_id+\"', '\"+centro_utilidad+\"', '\"+bodega+\"', '\"+lista_value+\"')\");";

    $html .= "     document.getElementById(\"btn_adicionar_producto\").style.cursor = \"not-allowed\";";
    $html .= "     document.getElementById(\"btn_adicionar_producto\").style.background = \"#a9a6a6\";";
    $html .= "     document.getElementById(\"btn_adicionar_producto\").setAttribute(\"onclick\", \"\");";
    $html .= "     document.getElementById(\"btn_adicionar_producto\").setAttribute(\"value\", \"AGREGAR PRODUCTO\");";
    $html .= "  }";

    $html .= "  function btns_update()";
    $html .= "  {";
    $html .= "     document.getElementById(\"btn_adicionar_producto\").setAttribute(\"value\", \"ACTUALIZAR PRODUCTO\");";
    $html .= "     document.getElementById(\"btn_adicionar_producto\").setAttribute(\"onclick\", \"SeleccionarProducto(xajax.getFormValues('FormularioPrecioProducto'))\");";
    $html .= "     document.getElementById(\"btn_adicionar_producto\").style.cursor = \"pointer\";";
    $html .= "     document.getElementById(\"btn_adicionar_producto\").style.color = \"#FFF\";";
    $html .= "     document.getElementById(\"btn_adicionar_producto\").style.background = \"#405b7d\";";

    $html .= "     document.getElementById(\"btn_search\").style.display = \"inline-block\";";
    $html .= "     document.getElementById(\"btn_search2\").style.display = \"none\";";
    $html .= "     document.getElementById(\"btn_search2\").setAttribute(\"onclick\", \"\");";

    $html .= "  }";
    $html .= "</script> ";
        
    
    $html .= "<script>";
    $html .= "    function acceptNum(evt)";
    $html .= "    { ";
    $html .= "      var nav4 = window.Event ? true : false;";
    $html .= "      var key = nav4 ? evt.which : evt.keyCode;";
    $html .= "      return (key < 13 || (key >= 48 && key <= 57) || key == 46);";
    $html .= "    }";
    $html .= "</script>";
    
    
    
    $html .= ThemeAbrirTabla('% COSTOS DE VENTAS');    
		$html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\">";
		$html .= "      MEN&Uacute;";
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
		$html .= "  <script>xajax_EmpresasT_2();</script>";
        $host_1 = $_SERVER["HTTP_HOST"];
        $host_2 = $_SERVER["SCRIPT_NAME"];
        $volver = "http://".$host_1.$host_2.'?SIIS_SID=79004a7c2d5b450c601bc143343b966d&amp;modulo=Inv_Listas_Precios&amp;tipo=controller';
		//$volver = " http://10.0.2.237/APP/DUSOFT_DUANA/Contenido.php?SIIS_SID=79004a7c2d5b450c601bc143343b966d&amp;modulo=Inv_Listas_Precios&amp;tipo=controller";
        //$volver = " http://10.0.2.237/APP/PRUEBAS_LINA_OSPINA/Contenido.php?SIIS_SID=79004a7c2d5b450c601bc143343b966d&amp;modulo=Inv_Listas_Precios&amp;tipo=controller";
		$html .= ' 	<form name="forma" action='.$volver.' method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
    
    //Se Desplegar� ac� la pantalla para asignar los %de costos de venta tanto a un tipo de producto como a un producto en particular
    $html .= "    <div id=\"sub_pantalla\">";
    $html .= "    </div>";
    $html .= ThemeCerrarTabla();
    $html .= $this->CrearVentana(670,"ASIGNAR PORCENTAJE DE COSTO VENTA");
		
    return($html);
    }
   
   
  
  // CREAR LA CAPITA
	function CrearVentana($tmn,$Titulo)
    {
      if(!isset($html)){ $html = ''; }

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
      
      
      $html .= "  function Cerrar(Elemento)\n";
      $html .= "  {\n";
      $html .= "      capita = xGetElementById(Elemento);\n";
      $html .= "      capita.style.display = \"none\";\n";
      $html .= "  }\n";

      $html .= "  function initForm(){\n";
      $html .= "      document.getElementById(\"btn_search\").style.display = \"none\";\n";
      $html .= "      document.getElementById(\"btn_search2\").style.display = \"inline-block\";\n";
      $html .= "      resetValueWithOutList();\n";
      $html .= "      btns_insert();\n";
      $html .= "      document.getElementById(\"btn_adicionar_producto\").style.cursor = \"pointer\";\n";
      $html .= "      document.getElementById(\"btn_adicionar_producto\").style.color = \"#FFF\";\n";
      $html .= "      document.getElementById(\"btn_adicionar_producto\").style.background = \"#405b7d\";\n";
      $html .= "      document.getElementById(\"btn_adicionar_producto\").setAttribute(\"onclick\", \"SeleccionarProducto(xajax.getFormValues('FormularioPrecioProducto'))\");\n";
      $html .= "  }\n";

      $html .= "  function interval_function(existList){\n";
      $html .= "    if(document.getElementById(\"select_listas_precios\") != undefined){\n";
      //$html .= "      var btn_adicionar_producto = document.getElementById(\"btn_adicionar_producto\");";
      $html .= "      clearInterval(existList);\n";
      $html .= "      updateList();\n";
      $html .= "      document.getElementById(\"select_listas_precios\").addEventListener(\"change\", function(){\n";
      $html .= "      initForm();\n";
      //$html .= "          document.getElementById(\"btn_search2\").setAttribute(\"onclick\", \"xajax_SeleccionDeProductos('\"+empresa_id+\"', '\"+centro_utilidad+\"', '\"+bodega+\"', '\"+lista_value+\"')\");";
      $html .= "      }, false);\n";
      $html .= "    }\n";
      $html .= "    ";
      $html .= "  }\n";
      
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