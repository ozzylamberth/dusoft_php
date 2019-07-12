<?php

/**
 * $Id: app_OrdenesdeCompra_userclasses_HTML.php,v 1.8 2007/08/13 13:16:47 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */


class app_OrdenesdeCompra_userclasses_HTML extends app_OrdenesdeCompra_user
{
	
	
	function app_OrdenesdeCompra_userclasses_HTML()
	{
		$this->app_OrdenesdeCompra_user();
		
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("RemoteScripting");
		
		$this->salida='';
		return true;
	}
	
	function Principal()
	{
		unset($_SESSION['OC']);
		if($this->UsuariosOrdenesdeCompra()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de OrdenesdeCompra
	function PrincipalOC()//Llama a todas las opciones posibles
	{
		if($_REQUEST['PermisoOC']['empresa_id'])
		{
			$_SESSION['OC']['empresa_id']=$_REQUEST['PermisoOC']['empresa_id'];
			$_SESSION['OC']['razonso']=$_REQUEST['PermisoOC']['descripcion1'];
			$_SESSION['OC']['centroutil']=$_REQUEST['PermisoOC']['centro_utilidad'];
			$_SESSION['OC']['descentro']=$_REQUEST['PermisoOC']['descripcion2'];
			$_SESSION['OC']['usuario_id']=$_REQUEST['PermisoOC']['usuario_id'];
			$_SESSION['OC']['usuariodes']=$_REQUEST['PermisoOC']['nombre'];
		}
		
		$this->salida  = ThemeAbrirTabla('ORDENES DE COMPRA - OPCIONES');
		
		$actionA=ModuloGetURL('app','OrdenesdeCompra','user','GenerarOrdenCompraReq');
		$actionB=ModuloGetURL('app','OrdenesdeCompra','user','ConsultaOrdenCompra');
		
		$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">";
		$this->salida .= "		<td align=\"center\">MENU</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><a href=\"$actionA\">GENERAR ORDEN DE COMPRA</a></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><a href=\"$actionB\">CONSULTA ORDEN DE COMPRA</a></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
	
		$accion=ModuloGetURL('app','OrdenesdeCompra','user','Principal');
		
		$this->salida .= "<form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table width=\"100%\">";
		$this->salida .= "  	<tr>";
		$this->salida .= "				<td width=\"50%\" align=\"center\">";
		$this->salida .= "  			<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"VOLVER\">";
		$this->salida .= "  			</td>";
		$this->salida .= "  		</tr>";
		$this->salida .= "  	</table>";
		$this->salida .= "  </form>";

		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	function GenerarOrdenCompraReq()
	{
		$this->salida .= ThemeAbrirTabla("GENERAR ORDEN DE COMPRA - SELECCION DE REQUISICIONES");

		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=modulo_list_claro>";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "		".$_SESSION['OC']['razonso']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=modulo_list_claro>";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "			".$_SESSION['OC']['usuariodes']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		
		$this->SetXajax(array("ListadoProductosRequisicion"),"app_modules/OrdenesdeCompra/RemoteXajax/OrdenesdeCompra_Xajax.php");
		
		$this->salida .= "<div id=\"mensaje\" class=\"label_error\" align=\"center\"></div>";
		
		$requis=$this->BuscarReq($_SESSION['OC']['empresa_id']);
		
		$accion=ModuloGetURL('app','OrdenesdeCompra','user','GenerarOrdenCompraPro');
		$this->salida .= "<form name=\"forma_gc\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">";
		$this->salida .= "		<td width=\"10%\">REQUISICIÓN</td>";
		$this->salida .= "		<td width=\"10%\">FECHA</td>";
		$this->salida .= "		<td width=\"20%\">DEPARTAMENTO</td>";
		$this->salida .= "		<td width=\"40%\">NOMBRE DEL USUARIO</td>";
		$this->salida .= "		<td width=\"10%\"># PRODUCTOS</td>";
		$this->salida .= "		<td width=\"10%\">DETALLE PRODUCTOS</td>";
		$this->salida .= "		<td width=\"10%\"><input type=\"checkbox\" name=\"todosReq\" value=\"1\" onclick=\"SeleccionarTodos(this.form,this.checked)\"></td>";
		$this->salida .= "	</tr>";
		$j=0;
		$ciclo=sizeof($requis);
		for($i=0;$i<$ciclo;$i++)
		{
			if($j==0)
			{
				$color="class=\"modulo_list_claro\"";
				$j=1;
			}
			else
			{
				$color="class=\"modulo_list_oscuro\"";
				$j=0;
			}
			$this->salida .= "<tr $color>";
			$this->salida .= "	<td align=\"center\">";
			$this->salida .= "		".$requis[$i]['requisicion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$requis[$i]['fecha_requisicion']);
			$this->salida .= "		".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "		".$requis[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "		".$requis[$i]['nombre']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "		".$requis[$i]['cantidad']." / ".$requis[$i]['cantidad2'];
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "		<a href=\"javascript:Iniciar('PRODUCTOS REQUISICION   '+".$requis[$i]['requisicion_id'].");MostrarSpan('d2Container');VerProductos('".$requis[$i]['requisicion_id']."');\"><img src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "		<input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$requis[$i]['requisicion_id']."\">";
			$this->salida .= "  </td>";
			$this->salida .= "</tr>";
		}
		if(empty($requis))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"7\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA REQUISICIÓN'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "			<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "				<td colspan=\"7\" align=\"right\">";
		$this->salida .= "					<input type=\"button\" name=\"continuar\" value=\"CONTINUAR\" class=\"input-submit\" onclick=\"Continuar(document.forma_gc);\">";
		$this->salida .= "				</td>";
		$this->salida .= "			</tr>";
		$this->salida .= "		</table>";
		$this->salida .= "	</form>";
		
		$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
		$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='d2Contents' class=\"d2Content\">\n";
		$this->salida .= "	</div>\n"; 
		$this->salida .= "</div>\n";
		
		$accionV=ModuloGetURL('app','OrdenesdeCompra','user','PrincipalOC');
		$this->salida .= "<form name=\"forma2\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "  <br><table align=\"center\">";
		$this->salida .= "  	<tr>";
		$this->salida .= "				<td width=\"50%\" align=\"center\">";
		$this->salida .= "  			<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  			</td>";
		$this->salida .= "  	</tr>";
		$this->salida .= "  </table>";
		$this->salida .= "</form>";
		
		$this->salida .= "<script>\n";
		$this->salida .= "	var hiZ = 2;\n";
		$this->salida .= "	var contenedor = '';\n";
		$this->salida .= "	var titulo = '';\n";
		
		$this->salida .= "  function SeleccionarTodos(frm,x){\n";
		$this->salida .= "    if(x==true){\n";
		$this->salida .= "      for(i=0;i<frm.elements.length;i++){\n";
		$this->salida .= "        if(frm.elements[i].type=='checkbox'){\n";
		$this->salida .= "          frm.elements[i].checked=true;\n";
		$this->salida .= "        }\n";
		$this->salida .= "      }\n";
		$this->salida .= "    }else{\n";
		$this->salida .= "      for(i=0;i<frm.elements.length;i++){\n";
		$this->salida .= "        if(frm.elements[i].type=='checkbox'){\n";
		$this->salida .= "          frm.elements[i].checked=false;\n";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "    }\n";
		$this->salida .= "  }\n";
		
		$this->salida .= "	function Iniciar(tit)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	 	contenedor = 'd2Container';\n";
		$this->salida .= "		titulo = 'titulo';\n";
		$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
		$this->salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+30);\n";
		$this->salida .= "	  xResizeTo(ele,500,'auto');\n";
		$this->salida .= "		ele = xGetElementById('d2Contents');\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
		$this->salida .= "	  xResizeTo(ele,500, 260);\n";
		$this->salida .= "		ele = xGetElementById(titulo);\n";
		$this->salida .= "	  xResizeTo(ele,480, 20);\n";
		$this->salida .= "		xMoveTo(ele, 0, 0);\n";
		$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$this->salida .= "		ele = xGetElementById('cerrar');\n";
		$this->salida .= "	  xResizeTo(ele,20, 20);\n";
		$this->salida .= "		xMoveTo(ele, 480, 0);\n";
		$this->salida .= "	}\n";

		$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  window.status = '';\n";
		$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
		$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
		$this->salida .= "	  ele.myTotalMX = 0;\n";
		$this->salida .= "	  ele.myTotalMY = 0;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  if (ele.id == titulo) {\n";
		$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
		$this->salida .= "	  }\n";
		$this->salida .= "	  else {\n";
		$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
		$this->salida .= "	  }  \n";
		$this->salida .= "	  ele.myTotalMX += mdx;\n";
		$this->salida .= "	  ele.myTotalMY += mdy;\n";
		$this->salida .= "	}\n";
		$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
		$this->salida .= "	{}\n";
		
		$this->salida .= "	function MostrarSpan(Seccion)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"\";\n";
		$this->salida .= "	}\n";
		$this->salida .= "	function Cerrar(Seccion)\n";
		$this->salida .= "	{ \n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"none\";\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function VerProductos(requi)\n";
		$this->salida .= "	{ \n";
		$this->salida .= "		xajax_ListadoProductosRequisicion(requi);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Continuar(frm)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var ban=1;\n";
		$this->salida .= "		for(i=0;i<frm.elements.length;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='Seleccion[]'){\n";
		$this->salida .= "				if(frm.elements[i].checked)\n";
		$this->salida .= "					ban=0;\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(ban==0)\n";
		$this->salida .= "			frm.submit();\n";
		$this->salida .= "		else\n";
		$this->salida .= "			document.getElementById('mensaje').innerHTML = 'SELECCIONE REQUISICION(ES)<br>';\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	function GenerarOrdenCompraPro()
	{
		$this->salida .= ThemeAbrirTabla("GENERAR ORDEN DE COMPRA - SELECCION DE PRODUCTOS");

		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "		".$_SESSION['OC']['razonso']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "			".$_SESSION['OC']['usuariodes']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		
		$proveedores=$this->GetProveedores();
		
		$this->salida .= "<div id=\"mensaje\" class=\"label_error\" align=\"center\"></div>";
		
		$accion=ModuloGetURL('app','OrdenesdeCompra','user','ConfirmacionOrdendeCompra',array('Seleccion'=>$_REQUEST['Seleccion']));
		
		$this->salida .= "<form name=\"formaPro\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<br><table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"8%\" >PROVEEDOR</td>";
		$this->salida .= "		<td class=\"modulo_list_claro\" width=\"56%\">";
		$this->salida .= "			<select id=\"proveedor\" name=\"proveedor\" class=\"select\" onclick=\"document.getElementById('proveedor').style.background='';\">";
		$this->salida .= "				<option value=\"\">--SELECCIONE PROVEEDOR--</option>";
		foreach($proveedores as $proveedor)
		{
			$sel="";
			if($_REQUEST['Datos']['proveedor']==$proveedor['tipo_id_tercero'].".-.".$proveedor['tercero_id'].".-.".$proveedor['nombre_tercero'].".-.".$proveedor['codigo_proveedor_id'])
				$sel="selected";
			$this->salida .= "				<option value=\"".$proveedor['tipo_id_tercero'].".-.".$proveedor['tercero_id'].".-.".$proveedor['nombre_tercero'].".-.".$proveedor['codigo_proveedor_id']."\" $sel>".$proveedor['nombre_tercero']."</option>";
		}
		$this->salida .= "			</select>";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">";
		$this->salida .= "		<td width=\"10%\">CÓDIGO</td>";
		$this->salida .= "		<td width=\"10%\">DESCRIPCIÓN</td>";
		$this->salida .= "		<td width=\"10%\">UNIDAD</td>";
		$this->salida .= "		<td width=\"10%\">CONTENIDO</td>";
		$this->salida .= "		<td width=\"10%\">PRECIO VENTA</td>";
		$this->salida .= "		<td width=\"10%\">VALOR</td>";
		$this->salida .= "		<td width=\"10%\">CANTIDAD REQUERIDA</td>";
		$this->salida .= "		<td width=\"5%\">CANTIDAD</td>";
		$this->salida .= "		<td width=\"10%\">VALOR NETO</td>";
		$this->salida .= "		<td width=\"5%\"> % IVA</td>";
		$this->salida .= "		<td width=\"10%\">VALOR TOTAL</td>";
		$this->salida .= "		<td width=\"5%\"><input type=\"checkbox\" name=\"todos\" value=\"1\" onclick=\"SeleccionarTodos(this.form,this.checked);\"></td>";
		$this->salida .= "	</tr>";
		
		$checks=$_REQUEST['Seleccion'];
		
		$productos=$this->ListarProductosCompra($_SESSION['OC']['empresa_id'],$checks);

		for($j=0;$j<sizeof($productos);$j++)
		{
			if($j%2==0)
			{
				$estilo="modulo_list_claro";
			}
			else
			{
				$estilo="modulo_list_oscuro";
			}
			
			$this->salida .= "<tr class=\"$estilo\">";
			$this->salida .= "	<td align=\"center\">";
			$this->salida .= "		".$productos[$j]['codigo_producto']."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td>";
			$this->salida .= "		".$productos[$j]['descripcion']."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td>";
			$this->salida .= "		".$productos[$j]['desunidad']."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td>";
			$this->salida .= "		".$productos[$j]['contenido_unidad_venta']."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\">";
			$this->salida .= "		$ ".$productos[$j]['precio_venta']."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\">";
			$this->salida .= "		$ <input type=\"text\" id=\"valor_costo$j\" name=\"valor_costo[]\" value=\"".$_REQUEST['Datos']["valor_costo"][$j]."\" maxlength=\"20\" size=\"10\" class=\"input-text\" onkeyup=\"CalcularValor(xGetElementById('uncantidad$j').value,xGetElementById('valor_costo$j').value,'".$productos[$j]['porcentaje_iva']."','valor_neto$j','valor_total$j','v_neto$j','v_total$j'); document.getElementById('valor_costo$j').style.background='';\">";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"center\">";
			$this->salida .= "		".FormatoValor($productos[$j]['cantidad'])."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"center\">";
			if($_REQUEST['Datos']["uncantidad"][$j])
				$can=$_REQUEST['Datos']["uncantidad"][$j];
			else
				$can=$productos[$j]['cantidad'];
			$this->salida .= "		<input type=\"text\" id=\"uncantidad$j\" name=\"uncantidad[]\" value=\"".$can."\" maxlength=\"10\" size=\"10\" class=\"input-text\" onkeyup=\"ValidarCantidad('uncantidad$j',xGetElementById('uncantidad$j').value,'".$productos[$j]['cantidad']."'); CalcularValor(xGetElementById('uncantidad$j').value,xGetElementById('valor_costo$j').value,'".$productos[$j]['porcentaje_iva']."','valor_neto$j','valor_total$j','v_neto$j','v_total$j');\">";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\" id=\"valor_neto$j\">";
			$this->salida .= "		$ ".FormatoValor($_REQUEST['Datos']["v_neto"][$j])."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\">";
			$this->salida .= "		".FormatoValor($productos[$j]['porcentaje_iva'])." %";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\" id=\"valor_total$j\">";
			$this->salida .= "		$ ".FormatoValor($_REQUEST['Datos']["v_total"][$j])."";
			$this->salida .= "	</td>";
			$this->salida .= "  <td align=\"center\">";
			
			$descripcion= str_replace("\"","",$productos[$j]['descripcion']);
			$desc_unidad= str_replace("\"","",$productos[$j]['desunidad']);
			$conten= str_replace("\"","",$productos[$j]['contenido_unidad_venta']);

			$this->salida .= "		<input type=\"checkbox\" id=\"SeleccionPro$j\" name=\"SeleccionPro[]\" value=\"".$productos[$j]['codigo_producto'].".-.".$descripcion.".-.".$productos[$j]['porcentaje_iva'].".-.".$desc_unidad.".-.".$conten.".-.".$j."\" onclick=\"Checkeo(this.checked,'$j');\" $check>";
			$this->salida .= "  </td>";
			$this->salida .= "</tr>";
			$this->salida .= "<input type=\"hidden\" id=\"v_total$j\" name=\"v_total[]\" value=\"".$_REQUEST['Datos']["v_total"][$j]."\">";
			$this->salida .= "<input type=\"hidden\" id=\"v_neto$j\" name=\"v_neto[]\" value=\"".$_REQUEST['Datos']["v_neto"][$j]."\">";
		}
		$this->salida .= "	<input type=\"hidden\" name=\"todo\" value=\"\">";
		
		$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "  	<td align=\"right\" colspan=\"12\"><input type=\"button\" class=\"input-submit\" name=\"generarcompra\" value=\"GENERAR ORDEN COMPRA\" onclick=\"ConfirmacionOrdenCompra(this.form);\"></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		$this->salida .= "</form>";
		
		$this->salida .= "<div id=\"mensaje1\" class=\"label_error\" align=\"center\"></div>";
		
		$accionV=ModuloGetURL('app','OrdenesdeCompra','user','GenerarOrdenCompraReq');
		$this->salida .= "<form name=\"forma2\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "  <br><table align=\"center\">";
		$this->salida .= "  	<tr>";
		$this->salida .= "				<td width=\"50%\" align=\"center\">";
		$this->salida .= "  			<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  			</td>";
		$this->salida .= "  	</tr>";
		$this->salida .= "  </table>";
		$this->salida .= "</form>";
		
		$this->salida .= "<script>\n";
		$this->salida .= "  var contador=0;\n";
		$this->salida .= "  var DatosPos=new Array();\n";
		$this->salida .= "  var todos=0;\n";
		
		$this->salida .= "  function SeleccionarTodos(frm,x){\n";
		$this->salida .= "  	var j=0;\n";
		$this->salida .= "    if(x==true){\n";
		$this->salida .= "      for(i=0;i<frm.elements.length;i++){\n";
		$this->salida .= "        if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='SeleccionPro[]'){\n";
		$this->salida .= "          frm.elements[i].checked=true;\n";
		$this->salida .= "        	Checkeo(x,j++);\n";
		$this->salida .= "        }\n";
		$this->salida .= "      }\n";
		$this->salida .= "    	todos=j;\n";
		$this->salida .= "    }else{\n";
		$this->salida .= "    	j=todos;\n";
		$this->salida .= "      for(i=0;i<frm.elements.length;i++){\n";
		$this->salida .= "        if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='SeleccionPro[]'){\n";
		$this->salida .= "          frm.elements[i].checked=false;\n";
		$this->salida .= "        	Checkeo(x,j--);\n";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "    }\n";
		$this->salida .= "  }\n";
		
		$this->salida .= "	function ValidarCantidad(campo,valor,cant_sol)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		document.getElementById(campo).style.background='';\n";
		$this->salida .= "		document.getElementById('mensaje').innerHTML='';\n";
		$this->salida .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
		$this->salida .= "		{\n";
		$this->salida .= "			document.getElementById(campo).value='';\n";
		$this->salida .= "			document.getElementById(campo).style.background='#ff9595';\n";
		$this->salida .= "			document.getElementById('mensaje').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";

		$this->salida .= "	function CalcularValor(cantidad,valor_costo,porc_iva,capaNeto,capaTot,capaV,capaX)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var valor_neto=cantidad*valor_costo;\n";
		$this->salida .= "		var valor_total=valor_neto+((porc_iva*valor_neto)/100);\n";
		$this->salida .= "		document.getElementById(capaNeto).innerHTML=' $ '+valor_neto;\n";
		$this->salida .= "		document.getElementById(capaTot).innerHTML=' $ '+valor_total;\n";
		$this->salida .= "		document.getElementById(capaV).value=valor_neto;\n";
		$this->salida .= "		document.getElementById(capaX).value=valor_total;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Checkeo(x,pos)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		if(x==true)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			contador++;\n";
		$this->salida .= "			DatosPos[DatosPos.length]=pos;\n";
		$this->salida .= "		}\n";
		$this->salida .= "		else\n";
		$this->salida .= "		{\n";
		$this->salida .= "			contador--;\n";
		$this->salida .= "			j=0;\n";
		$this->salida .= "			DatosPos1=new Array();\n";
		$this->salida .= "			for(var i=0;i<DatosPos.length;i++)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				if(i!=pos)\n";
		$this->salida .= "				{\n";
		$this->salida .= "					DatosPos1[j++]=DatosPos[i];\n";
		$this->salida .= "				}\n";
		$this->salida .= "			}\n";
		$this->salida .= "			DatosPos=DatosPos1;\n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function ConfirmacionOrdenCompra(forma)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		document.getElementById('mensaje').innerHTML='';\n";
		$this->salida .= "		var ban=0;\n";
		$this->salida .= "		var arreglo='';\n";
		$this->salida .= "		var separador='||';\n";
		$this->salida .= "		for(var i=0;i<DatosPos.length;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(xGetElementById('SeleccionPro'+DatosPos[i]).checked)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				if(xGetElementById('valor_costo'+DatosPos[i]).value=='' || xGetElementById('valor_costo'+DatosPos[i]).value < 0 || isNaN(xGetElementById('valor_costo'+DatosPos[i]).value))\n";
		$this->salida .= "				{\n";
		$this->salida .= "					document.getElementById('valor_costo'+DatosPos[i]).value='';\n";
		$this->salida .= "					document.getElementById('valor_costo'+DatosPos[i]).style.background='#ff9595';\n";
		$this->salida .= "					ban=1;\n";
		$this->salida .= "				}\n";
		$this->salida .= "				if(xGetElementById('uncantidad'+DatosPos[i]).value=='' || xGetElementById('uncantidad'+DatosPos[i]).value < 0 || isNaN(xGetElementById('uncantidad'+DatosPos[i]).value))\n";
		$this->salida .= "				{\n";
		$this->salida .= "					document.getElementById('uncantidad'+DatosPos[i]).value='';\n";
		$this->salida .= "					document.getElementById('uncantidad'+DatosPos[i]).style.background='#ff9595';\n";
		$this->salida .= "					ban=1;\n";
		$this->salida .= "				}\n";
		$this->salida .= "				if(i==DatosPos.length-1) separador='';\n";
		$this->salida .= "					arreglo+=xGetElementById('SeleccionPro'+DatosPos[i]).value+'.-.'+xGetElementById('valor_costo'+DatosPos[i]).value+'.-.'+xGetElementById('uncantidad'+DatosPos[i]).value+'.-.'+xGetElementById('v_neto'+DatosPos[i]).value+'.-.'+xGetElementById('v_total'+DatosPos[i]).value+separador;\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(xGetElementById('proveedor').value=='')\n";
		$this->salida .= "		{\n";
		$this->salida .= "			document.getElementById('proveedor').style.background='#ff9595';\n";
		$this->salida .= "			ban=1;\n";
		$this->salida .= "		}\n";
		$this->salida .= "		document.formaPro.todo.value=arreglo;\n";
		$this->salida .= "		if(ban==0)\n";
		$this->salida .= "			forma.submit();\n";
		$this->salida .= "		else{\n";
		$this->salida .= "			document.getElementById('mensaje').innerHTML='FALTA DATOS POR INGRESAR';\n";
		$this->salida .= "			document.getElementById('mensaje1').innerHTML='FALTA DATOS POR INGRESAR';\n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
			
		$this->salida .= "</script>\n";
		
		$this->salida .= ThemeCerrarTabla();

		return true;
	}
	
	function ConfirmacionOrdendeCompra()
	{
		$this->salida .= ThemeAbrirTabla("GENERAR ORDEN DE COMPRA - CONFIRMACION ORDEN DE COMPRA");

		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "		".$_SESSION['OC']['razonso']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "			".$_SESSION['OC']['usuariodes']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";	
		
		$datos=explode("||",$_REQUEST['todo']);
		
		list($tipo_id,$provee,$nombre,$codigo)=explode(".-.",$_REQUEST['proveedor']);
		
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">";
		$this->salida .= "		<td width=\"20%\">PROVEEDOR</td>";
		$this->salida .= "		<td class=\"modulo_list_claro\" width=\"60%\">$tipo_id - $provee &nbsp;&nbsp;&nbsp;$nombre</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">";
		$this->salida .= "		<td width=\"20%\">FECHA COMPRA</td>";
		$this->salida .= "		<td class=\"modulo_list_claro\" width=\"60%\">".DATE("Y / m / d")."</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		
		$accion=ModuloGetURL('app','OrdenesdeCompra','user','GuardarDatosOrdenCompra',array('proveedor'=>$_REQUEST['proveedor'],'datosTodo'=>$datos));
		
		$this->salida .= "<form name=\"formaConfir\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "		<tr class=\"modulo_table_list_title\">";
		$this->salida .= "			<td width=\"10%\">CÓDIGO</td>";
		$this->salida .= "			<td width=\"10%\">DESCRIPCIÓN</td>";
		$this->salida .= "			<td width=\"10%\">UNIDAD</td>";
		$this->salida .= "			<td width=\"10%\">CONTENIDO</td>";
		$this->salida .= "			<td width=\"10%\">VALOR</td>";
		$this->salida .= "			<td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "			<td width=\"10%\">VALOR NETO</td>";
		$this->salida .= "			<td width=\"5%\"> % IVA</td>";
		$this->salida .= "			<td width=\"10%\">VALOR TOTAL</td>";
		$this->salida .= "		</tr>";
		$j=0;
		
		$TotalCompra=0;
		foreach($datos as $key=>$valor)
		{
			list($codigoPro,$descripcion,$porcentaje_iva,$descunidad,$contenidoPre,$pos,$valor_costo,$cantidad,$valor_neto,$valor_total)=explode(".-.",$valor);
			
			if($j%2==0)
			{
				$estilo="modulo_list_claro";
			}
			else
			{
				$estilo="modulo_list_oscuro";
			}
			
			$this->salida .= "<tr class=\"$estilo\">";
			$this->salida .= "	<td align=\"center\">";
			$this->salida .= "		".$codigoPro."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td>";
			$this->salida .= "		".$descripcion."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td>";
			$this->salida .= "		".$descunidad."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td>";
			$this->salida .= "		".$contenidoPre."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\">";
			$this->salida .= "		$ ".FormatoValor($valor_costo)."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"center\">";
			$this->salida .= "		".$cantidad."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\">";
			$this->salida .= "		$ ".FormatoValor($valor_neto)."";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\">";
			$this->salida .= "		".$porcentaje_iva." %";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\">";
			$this->salida .= "		$ ".FormatoValor($valor_total)."";
			$this->salida .= "	</td>";
			$this->salida .= "</tr>";
			$TotalCompra+=$valor_total;
			$arr[$pos]=$pos;
			$j++;
		}
		$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "		<td colspan=\"8\" align=\"right\">TOTAL VALOR COMPRA</td>";
		$this->salida .= "		<td align=\"right\">";
		$this->salida .= "			$ ".FormatoValor($TotalCompra)."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		
		$this->salida .= "<table align=\"center\" width=\"50%\">";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td align=\"center\">";
		$this->salida .= "  		<input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "		</td>";
		$this->salida .= "</form>";
		
		$accionV=ModuloGetURL('app','OrdenesdeCompra','user','GenerarOrdenCompraPro',array('Seleccion'=>$_REQUEST['Seleccion'],'Datos'=>$_REQUEST,'Posicion'=>$arr));
		
		$this->salida .= "<form name=\"formaV\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "		<td align=\"center\">";
		$this->salida .= "  		<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table>";
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTabla();

		return true;
	}
	
	function ConsultaOrdenCompra()
	{
		$this->salida .= ThemeAbrirTabla('CONSUNLTA ORDENES DE COMPRA');
		
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "		".$_SESSION['OC']['razonso']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "			".$_SESSION['OC']['usuariodes']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";	
		
		$this->SetXajax(array("OrdenesCompra","GetOrdenes","EnviarOrden","ConfirmEnviarOrden"),"app_modules/OrdenesdeCompra/RemoteXajax/OrdenesdeCompra_Xajax.php");
		
		$proveedores=$this->GetProveedoresOrdenCompra();
		
		$this->salida .= "<table width=\"50%\" class=\"modulo_table_list\" align=\"center\">";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"15%\">PROVEEDOR</td>";
		$this->salida .= "		<td class=\"modulo_list_claro\" width=\"85%\">";
		$this->salida .= "			<select id=\"proveedor\" name=\"proveedor\" class=\"select\" onchange=\"xajax_OrdenesCompra(xGetElementById('proveedor').value)\">";
		$this->salida .= "				<option value=\"\">--SELECCIONE PROVEEDOR--</option>";
		foreach($proveedores as $proveedor)
		{
			$this->salida .= "				<option value=\"".$proveedor['codigo_proveedor_id']."\" $sel>".$proveedor['nombre_tercero']."</option>";
		}
		$this->salida .= "			</select>";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"15%\">ORDEN DE COMPRA</td>";
		$this->salida .= "		<td class=\"modulo_list_claro\" width=\"85%\">";
		$this->salida .= "			<select id=\"ordenI\" name=\"ordenI\" class=\"select\">";
		$this->salida .= "				<option value=\"\">--SELECCIONE ORDEN DE COMPRA--</option>";
		$this->salida .= "			</select>";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td align=\"center\" colspan=\"2\" class=\"modulo_list_claro\" width=\"100%\"><input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"BUSCAR\" onclick=\"xajax_GetOrdenes('1',xGetElementById('proveedor').value,xGetElementById('ordenI').value);\"></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table>";
		
		$this->salida .= "	<div id=\"ListadoOrdenes\">";
		$this->salida .= "	</div>";
		
		$this->salida .= "<div id=\"d2Container\" class=\"d2Container\" style=\"display:none\">";
		$this->salida .= "    <div id=\"titulo\" class=\"draggable\" style=\"text-transform: uppercase;\"></div>\n";
		$this->salida .= "    <div id=\"cerrar\" class=\"draggable\"> <a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$this->salida .= "    <div id=\"error\" class=\"label_error\" style=\"text-transform: uppercase; text-align:center;\"></div>\n";
		$this->salida .= "    <div id=\"d2Contents\">\n";
		$this->salida .= "    </div>\n";
		$this->salida .= "</div>\n";
		
		$accionV=ModuloGetURL('app','OrdenesdeCompra','user','PrincipalOC');
		
		$this->salida .= "<form name=\"formaV\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "	<br><table width=\"100%\">";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td align=\"center\">";
		$this->salida .= "  			<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"VOLVER\">";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "<script>";
		$this->salida .= "	function BusquedaOrden(pagina,prov,orden)";
		$this->salida .= "	{";
		$this->salida .= "		xajax_GetOrdenes(pagina,prov,orden);";
		$this->salida .= "	}";
		
		$this->salida .= "	function AbrirVentanaImpresion(url)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		window.open(url,'ORDEN DE COMPRA','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function EnviarOrden(prov,orden,estado,envio)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_EnviarOrden(prov,orden,estado,envio);";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Iniciar(tit)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	 	contenedor = 'd2Container';\n";
		$this->salida .= "		titulo = 'titulo';\n";
		$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
		$this->salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+50);\n";
		$this->salida .= "	  xResizeTo(ele,300,'auto');\n";
		$this->salida .= "		ele = xGetElementById('d2Contents');\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
		$this->salida .= "	  xResizeTo(ele,300, 'auto');\n";
		$this->salida .= "		ele = xGetElementById(titulo);\n";
		$this->salida .= "	  xResizeTo(ele,280, 20);\n";
		$this->salida .= "		xMoveTo(ele, 0, 0);\n";
		$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$this->salida .= "		ele = xGetElementById('cerrar');\n";
		$this->salida .= "	  xResizeTo(ele,20, 20);\n";
		$this->salida .= "		xMoveTo(ele, 280, 0);\n";
		$this->salida .= "	}\n";

		$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  window.status = '';\n";
		$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
		$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
		$this->salida .= "	  ele.myTotalMX = 0;\n";
		$this->salida .= "	  ele.myTotalMY = 0;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  if (ele.id == titulo) {\n";
		$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
		$this->salida .= "	  }\n";
		$this->salida .= "	  else {\n";
		$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
		$this->salida .= "	  }  \n";
		$this->salida .= "	  ele.myTotalMX += mdx;\n";
		$this->salida .= "	  ele.myTotalMY += mdy;\n";
		$this->salida .= "	}\n";
		$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
		$this->salida .= "	{}\n";
		
		$this->salida .= "	function MostrarSpan(Seccion)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"\";\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Cerrar(Seccion)\n";
		$this->salida .= "	{ \n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"none\";\n";
		$this->salida .= "	}\n";

		
		$this->salida .= "</script>";
		
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	
	function FormaMensaje($valor)
	{
		$this->salida .= ThemeAbrirTabla('ORDEN DE COMPRA N.'.$valor['orden_pedido_id']);
		
		$this->salida .= "<table align=\"center\" width=\"50%\">";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td align=\"center\" class=\"modulo_list_claro\">";
		$this->salida .= "  		<label class=\"label_error\">SE HA GENERADO UNA ORDEN DE COMPRA N. ".$valor['orden_pedido_id']."</label>";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$direccion="app_modules/OrdenesdeCompra/reports/html/OrdenesdeCompra_html.report.php?orden_pedido_id=".$valor['orden_pedido_id']."&proveedor=".$valor['nombre_proveedor']."&fecha_orden=".$valor['fecha_orden']."&usuario=".$valor['nombre_usuario'];
		$this->salida .= "	<tr>";
		$this->salida .= "		<td align=\"center\" class=\"label\"><br><a href=\"javascript:AbrirVentanaImpresion('$direccion');\"><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" title=\"IMPRESION DE LA ORDEN DE COMPRA\"> IMPRIMIR </a></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		
		$accionV=ModuloGetURL('app','OrdenesdeCompra','user','PrincipalOC');
	
		$this->salida .= "<form name=\"formaV\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "	<table align=\"center\" width=\"50%\">";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td align=\"center\">";
		$this->salida .= "  			<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "<script>";
		
		$this->salida .= "	function AbrirVentanaImpresion(url)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		window.open(url,'ORDEN DE COMPRA','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "</script>";
		
		$this->salida .= ThemeCerrarTabla();
	
	
	}
	
/*************************************************************************************************************/
}//fin de la clase
?>