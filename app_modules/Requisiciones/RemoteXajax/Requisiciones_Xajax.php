<?php
	
	function GetDatos($grupo,$clase,$sw)
	{
		$objResponse=new xajaxResponse();
	
		$objClass=new app_Requisiciones_user;

		if($sw==1)
				$datosPre=$objClass->GetInvClases($grupo);
		elseif($sw==2)
				$datosPre=$objClass->GetInvSubClases($grupo,$clase);
		
		$salida = GenerarHTML($datosPre,$sw);
		
		if(!$grupo)
		{
			$salida="<select name=\"clase\" class=\"select\" id=\"clase\" onChange=\"xajax_GetDatos(xGetElementById('grupo').value,xGetElementById('clase').value,'2');\">";
			$salida.="	<option value=\"\">--SELECCIONE CLASE--</option>";
			$salida.="</select>";
			
			$objResponse->assign("clase_td","innerHTML",$salida);
		
			$salida="<select name=\"subclase\" id=\"subclase\" class=\"select\">";
			$salida.="<option value=\"\">--SELECCIONE SUBCLASE--</option>";
			$salida.="</select>";
			
			$objResponse->assign("subclase_td","innerHTML",$salida);
		}
		else
		{
			if($sw==1)
			{
				$objResponse->assign("clase_td","innerHTML",$salida);
				
				$salida="<select name=\"subclase\" id=\"subclase\" class=\"select\">";
				$salida.="<option value=\"\">--SELECCIONE SUBCLASE--</option>";
				$salida.="</select>";
			
				$objResponse->assign("subclase_td","innerHTML",$salida);
			}
			elseif($sw==2)
				$objResponse->assign("subclase_td","innerHTML",$salida);
		}
		
		return $objResponse;
	}
	
	function GenerarHTML($datosPre,$sw)
	{
		$salida="";
		switch($sw)
		{
			case 1:
				$salida="<select name=\"clase\" class=\"select\" id=\"clase\" onChange=\"xajax_GetDatos(xGetElementById('grupo').value,xGetElementById('clase').value,'2');\">";
				$salida.="<option value=\"\">--SELECCIONE CLASE--</option>";
				foreach($datosPre as $valor)
					$salida.="<option value=\"".$valor['clase_id']."\">".strtoupper($valor['descripcion'])."</option>";
				$salida.="</select>";
			break;
			
			case 2:
				$salida="<select name=\"subclase\" class=\"select\" id=\"subclase\">";
				$salida.="<option value=\"\">--SELECCIONE SUBCLASE--</option>";
				foreach($datosPre as $valor)
					$salida.="<option value=\"".$valor['subclase_id']."\">".strtoupper($valor['descripcion'])."</option>";
				$salida.="</select>";
			break;
		}

		return $salida;
	}
	
	function GenerarBusqueda($pagina,$codigo,$descripcion,$grupo,$clase,$subclase)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		if(!$codigo)
			$codigo="";
			
		if(!$descripcion)
			$descripcion="";
		
		$grupos=$objClass->GetInvGrupos();
		
		$salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$salida .= "	<tr>";
		$salida .= "		<td width=\"20%\" class=\"modulo_table_list_title\">CÓDIGO</td>";
		$salida .= "		<td width=\"80%\" class=\"modulo_list_oscuro\"><input id=\"codigo\" type=\"text\" name=\"codigo\" class=\"input-text\" size=\"15\" maxlength=\"15\" value=\"$codigo\"></td>";
		$salida .= "	</tr>";
		$salida .= "	<tr>";
		$salida .= "		<td class=\"modulo_table_list_title\">DESCRIPCIÓN</td>";
		$salida .= "		<td class=\"modulo_list_oscuro\"><input type=\"text\" id=\"descripcion\" name=\"descripcion\" class=\"input-text\" size=\"60\" value=\"$descripcion\"></td>";
		$salida .= "	</tr>";
		$salida .= "	<tr>";
		$salida .= "		<td class=\"modulo_table_list_title\">GRUPO</td>";
		$salida .= "		<td class=\"modulo_list_oscuro\">";
		$salida .= "			<select name=\"grupo\" class=\"select\" id=\"grupo\" onChange=\"xajax_GetDatos(xGetElementById('grupo').value,'0','1');\">";
		$salida .= "				<option value=\"\">--SELECCIONE GRUPO--</option>";
		foreach($grupos as $valor)
		{
			$salida .= "				<option value=\"".$valor['grupo_id']."\">".strtoupper($valor['descripcion'])."</option>";
		}
		$salida .= "			</select>";
		$salida .= "		</td>";
		$salida .= "	</tr>";
		$salida .= "	<tr>";
		$salida .= "		<td class=\"modulo_table_list_title\">CLASE</td>";
		$salida .= "		<td class=\"modulo_list_oscuro\" id=\"clase_td\">";
		$salida .= "			<select name=\"clase\" id=\"clase\" class=\"select\">";
		$salida .= "				<option value=\"\">--SELECCIONE CLASE--</option>";
		$salida .= "			</select>";
		$salida .= "		</td>";
		$salida .= "	</tr>";
		$salida .= "	<tr>";
		$salida .= "		<td class=\"modulo_table_list_title\">SUBCLASE</td>";
		$salida .= "		<td class=\"modulo_list_oscuro\" id=\"subclase_td\">";
		$salida .= "			<select name=\"subclase\" id=\"subclase\" class=\"select\">";
		$salida .= "				<option value=\"\">--SELECCIONE SUBCLASE--</option>";
		$salida .= "			</select>";
		$salida .= "		</td>";
		$salida .= "	</tr>";
		$salida .= "	<tr align=\"center\">";
		$salida .= "		<td colspan=\"2\" class=\"modulo_list_oscuro\"><input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"BUSCAR\" onclick=\"Busqueda('1',xGetElementById('codigo').value,xGetElementById('descripcion').value,xGetElementById('grupo').value,xGetElementById('clase').value,xGetElementById('subclase').value);\">";
		$salida .= "		&nbsp;&nbsp;<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"LIMPIAR CAMPOS\" onclick=\"LimpiarBusquedaPro();\"></td>";
		$salida .= "	</tr>";
		$salida .= "</table><br>";
		
		$salida .= "<center><a href=\"javascript:Iniciar2('INGRESAR PRODCUTO');IngProNoCatalog();MostrarSpan('d2Container');\">SOLICITAR PRODUCTO NO CATALOGADO</a></center><br>";

		$_SESSION['Req']['listaprodu']=$objClass->ListaCotizarCompra($_SESSION['Req']['empresa_id'],$_SESSION['Req']['requisicio'],$codigo,$descripcion,$grupo,$clase,$subclase,"LEFT",$pagina);
		$slc=$objClass->conteo;
		
		$salida.=CrearHtml($pagina,$codigo,$descripcion,$grupo,$clase,$subclase,$slc);
		
		$objResponse->assign("d2Contents","innerHTML",$objResponse->setTildes($salida));
		
		return $objResponse;
	}
	
	function IngProNoCatalog()
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		$salida = "<form name=\"forma_ncp\" action=\"\" method=\"post\">";
		$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td colspan=\"2\">PRODUCTOS NO CATALOGADOS</td>";
		$salida .= "	</tr>";
		$salida .= "	<tr>";
		$salida .= "		<td class=\"modulo_table_list_title\" width=\"20%\">NOMBRE PRODUCTO:</td>";
		$salida .= "		<td width=\"80%\" class=\"modulo_list_claro\"><input type=\"text\" name=\"producto\" class=\"input-text\" size=\"60\"></td>";
		$salida .= "	</tr>";
		$salida .= "	<tr>";
		$salida .= "		<td class=\"modulo_table_list_title\" width=\"20%\">PROVEEDOR:</td>";
		$salida .= "		<td width=\"80%\" class=\"modulo_list_claro\"><input type=\"text\" name=\"proveedor\" class=\"input-text\" size=\"60\"></td>";
		$salida .= "	</tr>";
		$salida .= "	<tr>";
		$salida .= "		<td class=\"modulo_table_list_title\" width=\"20%\">NOMBRE GENERICO:</td>";
		$salida .= "		<td width=\"80%\" class=\"modulo_list_claro\"><input type=\"text\" name=\"generico\" class=\"input-text\" size=\"60\"></td>";
		$salida .= "	</tr>";
		$salida .= "	<tr>";
		$salida .= "		<td class=\"modulo_table_list_title\" width=\"20%\">CANTIDAD:</td>";
		$salida .= "		<td width=\"80%\" class=\"modulo_list_claro\"><input type=\"text\" name=\"cantidad\" class=\"input-text\" size=\"10\"></td>";
		$salida .= "	</tr>";
		$salida .= "	<tr>";
		$salida .= "		<td class=\"modulo_table_list_title\" width=\"20%\">JUSTIFICACION:</td>";
		$salida .= "		<td width=\"80%\" class=\"modulo_list_claro\"><textarea name=\"justif\" class=\"input-text\" cols=\"30\" rows=\"3\"></textarea></td>";
		$salida .= "	</tr>";
		$salida .= "	<tr align=\"center\">";
		$salida .= "		<td colspan=\"2\" class=\"modulo_list_claro\"><input type=\"button\" name=\"aceptar\" class=\"input-submit\" value=\"Aceptar\" onclick=\"GuardarNPC(document.forma_ncp.producto.value,document.forma_ncp.proveedor.value,document.forma_ncp.generico.value,document.forma_ncp.cantidad.value,document.forma_ncp.justif.value);xajax_ListadoProNoCatalog();\">";
		$salida .= "		&nbsp;&nbsp;&nbsp;<input type=\"button\" name=\"cancelar\" class=\"input-submit\" value=\"Cancelar\" onclick=\"Iniciar('BUSCADOR DE PRODUCTOS');Busqueda('1','0','0','0','0','0');MostrarSpan('d2Container');\"></td>";
		$salida .= "	</tr>";
		$salida .= "</table>";
		$salida .= "</form>";
		
		$objResponse->assign("d2Contents","innerHTML",$objResponse->setTildes($salida));
		
		return $objResponse;
	}
	
	function GuardarNPC($producto,$proveedor,$generico,$cantidad,$justif)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		if(empty($producto))
		{
			$objResponse->assign("error","innerHTML","<center>DEBE INGRESAR EL NOMBRE DEL PRODUCTO</center>");
		}
		else
		{
			if(empty($cantidad))
			{
				$objResponse->assign("error","innerHTML","<center>DEBE INGRESAR LA CANTIDAD</center>");
			}
			else
			{
				if(empty($justif))
				{
					$objResponse->assign("error","innerHTML","<center>DEBE INGRESAR LA JUSTIFICACION</center>");
				}
				else
				{
					if($objClass->GuardarNoProdCatalog($_SESSION['Req']['requisicio'],$_SESSION['Req']['empresa_id'],$_SESSION['Req']['departaide'],$producto,$proveedor,$generico,$cantidad,$justif))
					{
						$objResponse->assign("error","innerHTML","<center>DATOS GUARDADOS EXISTOSAMENTE</center>");
						
						$salida = "	<table border=\"0\" width=\"40%\" align=\"center\">";
						$salida .= "    <tr><td align=\"center\">";    
						$salida .= "    <input type=\"button\" class=\"input-submit\" id=\"volver\" name=\"Acept\" value=\"Aceptar\" onclick=\"Iniciar('BUSCADOR PRODCUTOS');Busqueda('1','0','0','0','0','0');MostrarSpan('d2Container');\">";
						$salida .= "    </td></tr>";
						$salida .= "	</table>";
							
						$objResponse->assign("d2Contents","innerHTML",$salida);
					}
					else
					{
						$objResponse->assign("error","innerHTML",$objClass->mensajeDeError);
					}
				}
			}
		}
		return $objResponse;
	}
	
	function ListadoProNoCatalog()
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		$_SESSION["Req"]["listado2"]=$objClass->GetListadoProNoCatalog($_SESSION['Req']['requisicio']);

		$salida=NPC_Html($_SESSION["Req"]["listado2"]);
		
		$sal=explode("ç",$salida);
		
		$objResponse->assign("arr2","value",$sal[1]);
		$objResponse->call("ObtenerArr2");
		$objResponse->assign("listado_NPC","innerHTML",$sal[0]);

		return $objResponse;
	}
	
	
	function EliminarProducto($codigo,$empresa,$requisicion,$capa)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		if($objClass->EliminarPro($codigo,$requisicion))
		{
			$objResponse->assign($capa,"style.display","none");
			$_SESSION["Req"]["listado"]=$objClass->GetListadoProNoCatalog($_SESSION['Req']['empresa_id'],$_SESSION['Req']['requisicio']);
		}
		else
			$objResponse->assign("mensaje","innerHTML","Error al Eliminar Producto");
		
		return $objResponse;
	}
	
	function EliminarProductoNoCatalogado($codigo,$empresa,$requisicion,$capa)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		if($objClass->EliminarProNoCatalog($codigo,$requisicion))
		{
			$objResponse->assign($capa,"style.display","none");
			$listado=$objClass->GetListadoProNoCatalog($_SESSION['Req']['requisicio']);
			
			$salida=NPC_Html($listado);
			$sal=explode("ç",$salida);
			
			$objResponse->assign("listado_NPC","innerHTML",$sal[0]);
		}
		else
			$objResponse->assign("mensaje","innerHTML","Error al Eliminar Producto No Catalogado");
		
		return $objResponse;
	}
	
	function NPC_Html($listado)
	{
		$objClass=new app_Requisiciones_user;
		
		$salida = "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td colspan=\"6\">PRODUCTOS NO CATALOGADOS</td>";
		$salida .= "	</tr>";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td width=\"56%\">NOMBRE PRODUCTO</td>";
		$salida .= "		<td width=\"10%\">NOMBRE GENERICO</td>";
		$salida .= "		<td width=\"16%\">PROVEEDOR</td>";
		$salida .= "		<td width=\"10%\">CANTIDAD</td>";
		if(!$_SESSION['Req']['evento'] OR $_SESSION['Req']['evento']==1)
			$salida .= "		<td width=\"10%\">ELIMINAR</td>";
		$salida .= "	</tr>";
			
		if($listado)
		{	
			$i=0;
			foreach($listado as $key=>$valor)
			{
				if($i%2==0)
					$estilo="modulo_list_claro";
				else
					$estilo="modulo_list_oscuro";	
			
				$salida .= "<tr class=\"$estilo\" id=\"capa$i\">";
				$salida .= "	<td>".$valor['nombre_producto']."</td>";
				$salida .= "	<td>".$valor['nombre generico']."</td>";
				$salida .= "	<td>".$valor['nombre_proveedor']."</td>";
				//$salida .= "	<td>".$valor['cantidad']."</td>";
				if(intval($valor['cantidad'])==0)
					$valor['cantidad']="";
				
				if(!$_SESSION['Req']['evento'] OR $_SESSION['Req']['evento']==1)
				{	
					$salida .= "	<td><input type=\"text\" id=\"uncantidadX$i\" name=\"uncantidad2X$i\" value=\"".$valor['cantidad']."\" maxlength=\"10\" size=\"10\" class=\"input-text\"></td>";
					$salida .= "	<td align=\"center\"><a href=\"javascript:EliminarProNoCatalog('".$valor['numero']."','".$_SESSION['Req']['empresa_id']."','".$_SESSION['Req']['requisicio']."','capa$i');\"><img src=\"".GetThemePath()."/images/delete.gif\" border=\"0\"></a></td>";
				}
				else
				{
					$salida .= "	<td>".$valor['cantidad']."</td>";
				}
				$salida .= "</tr>";
				
				if($i==sizeof($listado)-1)
					$arr2.="uncantidadX$i"."";
				else
					$arr2.="uncantidadX$i"."__";
				
				$i++;
			}
		}
		else
		{
			$salida .= "	<tr class=\"label_error\">";
			$salida .= "		<td width=\"100%\" colspan=\"6\">NO HAY PRODUCTOS SELECCIONADOS</td>";
			$salida .= "	</tr>";
		
		}
		$salida .= "</table>";
		return $salida."ç".$arr2;
	}
	
	function GuardarDatosN($cantidades1,$cantidades2)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		$ban=0;
		$i=0;
		$b1=true;
		$b2=true;
		$a=0;
		
		foreach($cantidades1 as $valor)
		{
			if(empty($valor))
				$b1=false;
		}
		
		if($b1)
		{
			foreach($_SESSION["Req"]["listado"] as $valor)
			{
				if(!$objClass->UpdateCantidadRequisicion($valor['codigo_producto'],$valor['requisicion_id'],$cantidades1[$i],"1"))	
					$ban=1;
				$i++;
			}
		}
		else
		{
			$objResponse->assign("mensaje","innerHTML","DEBE INGRESAR LAS CANTIDADES CORRESPONDIENTES");
			$a=1;
		}
		
		foreach($cantidades2 as $valor)
		{
			if(empty($valor))
				$b2=false;
		}
		
		$i=0;
		if($b2)
		{
			foreach($_SESSION["Req"]["listado2"] as $valor)
			{
				if(!$objClass->UpdateCantidadRequisicion($valor['numero'],$valor['requisicion_id'],$cantidades2[$i],"2"))	
					$ban=1;
				$i++;
			}
		}
		else
		{
			$objResponse->assign("mensaje","innerHTML","DEBE INGRESAR LAS CANTIDADES CORRESPONDIENTES(PRODUCTOS NO CATALOGADOS)");
			$a=1;
		}
		
		if(!$a)
		{
			if($ban==0)
				$objResponse->assign("mensaje","innerHTML","DATOS GUARDADOS SATISFACTORIAMENTE");
			else
				$objResponse->assign("mensaje","innerHTML",$objClass->error."<br>".$objClass->mensajeDeError);
		}
			
		return $objResponse;
	}
	
	function ListadoProductosRequisicion($requisicion)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		$datosList=$objClass->ListaCotizarCompra($_SESSION['Req']['empresa_id'],$requisicion);
		$salida=ListadoProductosRequisicion_HTML($datosList,$requisicion);
		$objResponse->assign("d2Contents","innerHTML",$objResponse->setTildes($salida));
		
		return $objResponse;
	}
	
	function Agregar($codigo,$requisicion,$cantidad,$capa)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		$img="<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\" width=\"15\" height=\"15\">";
		
		if(!empty($codigo) AND !empty($requisicion))
		{
			if(!$objClass->GuardarX($codigo,$requisicion,$cantidad))
					$objResponse->alert($objClass->mensajeDeError);
					
			$objResponse->assign($capa,"innerHTML",$img);
		}

		$_SESSION["Req"]["listado"]=$objClass->ListaCotizarCompra($_SESSION['Req']['empresa_id'],$_SESSION['Req']['requisicio']);

		$salida=Listado($_SESSION["Req"]["listado"]);
		
		$sal=explode("ç",$salida);
		
		$objResponse->assign("arr1","value",$sal[1]);
		$objResponse->call("ObtenerArr1");
		$objResponse->assign("listC","innerHTML",$objResponse->setTildes($sal[0]));
		
		
		return $objResponse;
	}
	
	
	function Listado($listado_compra)
	{
		$objClass=new app_Requisiciones_user;

		$salida = "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td colspan=\"6\">PRODUCTOS SELECCIONADOS PARA COTIZAR LA COMPRA</td>";
		$salida .= "	</tr>";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td width=\"8%\" >CÓDIGO</td>";
		$salida .= "		<td width=\"56%\">DESCRIPCIÓN</td>";
		$salida .= "		<td width=\"10%\">UNIDAD</td>";
		$salida .= "		<td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$salida .= "		<td width=\"10%\">CANTIDAD</td>";
		
		if(!$_SESSION['Req']['evento'] OR $_SESSION['Req']['evento']==1)
			$salida .= "		<td width=\"10%\">ELIMINAR</td>";
		$salida .= "	</tr>";
		if($listado_compra)
		{
			$i=0;
			foreach($listado_compra as $key=>$valor)
			{
				if($i%2==0)
					$estilo="modulo_list_claro";
				else
					$estilo="modulo_list_oscuro";	
			
				$salida .= "<tr class=\"$estilo\" id=\"capa1$i\">";
				$salida .= "	<td>".$valor['codigo_producto']."</td>";
				$salida .= "	<td>".$valor['descripcion']."</td>";
				$salida .= "	<td>".$valor['desunidad']."</td>";
				$salida .= "	<td>".$valor['contenido_unidad_venta']."</td>";
				if(intval($valor['cantidad'])==0)
					$valor['cantidad']="";
				if(!$_SESSION['Req']['evento'] OR $_SESSION['Req']['evento']==1)
				{
					$salida .= "	<td><input type=\"text\" id=\"uncantidad$i\" name=\"uncantidad$i\" value=\"".$valor['cantidad']."\" maxlength=\"10\" size=\"10\" class=\"input-text\"></td>";
					$salida .= "	<td align=\"center\"><a href=\"javascript:EliminarPro('".$valor['codigo_producto']."','".$_SESSION['Req']['empresa_id']."','".$_SESSION['Req']['requisicio']."','capa1$i');\"><img src=\"".GetThemePath()."/images/delete.gif\" border=\"0\"></a></td>";
				}
				else
				{
					$salida .= "	<td>".$valor['cantidad']."</td>";
				}
				$salida .= "</tr>";
				
				if($i==sizeof($listado_compra)-1)
					$arr1.="uncantidad$i"."";
				else
					$arr1.="uncantidad$i"."__";
				$i++;
			}
		}
		else
		{
			$salida .= "	<tr class=\"label_error\">";
			$salida .= "		<td width=\"100%\" colspan=\"6\">NO HAY PRODUCTOS SELECCIONADOS</td>";
			$salida .= "	</tr>";
		}
		
		$salida .= "</table><br>";
		
		return $salida."ç".$arr1;
	
	}
	
	function ListadoProductosRequisicion_HTML($listadoPro,$requisicion=null)
	{
		$objClass=new app_Requisiciones_user;

		$salida = "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td colspan=\"6\">PRODUCTOS DE LA REQUISICION  $requisicion</td>";
		$salida .= "	</tr>";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td width=\"8%\" >CÓDIGO</td>";
		$salida .= "		<td width=\"56%\">DESCRIPCIÓN</td>";
		$salida .= "		<td width=\"10%\">UNIDAD</td>";
		$salida .= "		<td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$salida .= "		<td width=\"10%\">CANTIDAD</td>";
		if($listadoPro)
		{
			$i=0;
			foreach($listadoPro as $key=>$valor)
			{
				if($i%2==0)
					$estilo="modulo_list_claro";
				else
					$estilo="modulo_list_oscuro";	
			
				$salida .= "<tr class=\"$estilo\" id=\"capa1$i\">";
				$salida .= "	<td>".$valor['codigo_producto']."</td>";
				$salida .= "	<td>".$valor['descripcion']."</td>";
				$salida .= "	<td>".$valor['desunidad']."</td>";
				$salida .= "	<td>".$valor['contenido_unidad_venta']."</td>";
				$salida .= "	<td>".$valor['cantidad']."</td>";
				$salida .= "</tr>";
			}
		}
		else
		{
			$salida .= "	<tr class=\"label_error\">";
			$salida .= "		<td width=\"100%\" colspan=\"6\">NO HAY PRODUCTOS SELECCIONADOS</td>";
			$salida .= "	</tr>";
		}
		
		$salida .= "</table><br>";
		
		return $salida;
	}
	
	
	function CrearHtml($pagina,$codigo,$descripcion,$grupo,$clase,$subclase,$slc)
	{
		$pathImg=SessionGetVar("ImgRuta");
		
		$objClass=new app_Requisiciones_user;
		
		if(!$codigo)
			$codigo="0";
		
		if(!$descripcion)
			$descripcion="0";
		
		if(!$grupo)
			$grupo="0";
			
		if(!$clase)
			$clase="0";
			
		if(!$subclase)
			$subclase="0";

		$salida = "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td width=\"8%\" >CÓDIGO</td>";
		$salida .= "		<td width=\"56%\">DESCRIPCIÓN</td>";
		$salida .= "		<td width=\"10%\">UNIDAD</td>";
		$salida .= "		<td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$salida .= "		<td width=\"10%\">SELECCIONAR</td>";
		$salida .= "	</tr>";
			
		$j=0;
		
		$ciclo=sizeof($_SESSION['Req']['listaprodu']);
		
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
			$salida .= "<tr $color>";
			$salida .= "<td align=\"center\">";
			$salida .= "".$_SESSION['Req']['listaprodu'][$i]['codigo_producto']."";
			$salida .= "</td>";
			$salida .= "<td>";
			$salida .= "".$_SESSION['Req']['listaprodu'][$i]['descripcion']."";
			$salida .= "</td>";
			$salida .= "<td>";
			$salida .= "".$_SESSION['Req']['listaprodu'][$i]['desunidad']."";
			$salida .= "</td>";
			$salida .= "<td>";
			$salida .= "".$_SESSION['Req']['listaprodu'][$i]['contenido_unidad_venta']."";
			$salida .= "</td>";
			$salida .= "<td align=\"center\" id=\"itemX$pagina$i\">";
			//$salida .= "<input type=\"text\" name=\"uncantidad".$i."\" value=\"".$_SESSION['compr2']['listaprodu'][$i]['cantidad']."\" maxlength=\"10\" size=\"10\" class=\"input-text\">";
			if($objClass->GetProductoSel($_SESSION['Req']['listaprodu'][$i]['codigo_producto'],$_SESSION['Req']['requisicio']))
				$salida .= "	<img src=\"".$pathImg."/images/checksi.png\" border=\"0\" width=\"15\" height=\"15\">";
			else
				$salida .= "	<a href=\"javascript:AgregarEnLista('".$_SESSION['Req']['listaprodu'][$i]['codigo_producto']."','".$_SESSION['Req']['requisicio']."','0','itemX$pagina$i');\"><img src=\"".$pathImg."/images/checkno.png\" border=\"0\" width=\"15\" height=\"15\"></a>";
			$salida .= "</td>";
			$salida .= "</tr>";
		}
		if(empty($_SESSION['Req']['listaprodu']))
		{
			$salida .= "<tr class=\"modulo_list_claro\">";
			$salida .= "<td colspan=\"5\" align=\"center\">";
			$salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO RELACIONADO A LA EMPRESA'";
			$salida .= "</td>";
			$salida .= "</tr>";
		}
		$salida .= "      </table><br>";
		
		$op="1";
		
		$salida.= "".ObtenerPaginado($pagina,$pathImg,$slc,$op,$codigo,$descripcion,$grupo,$clase,$subclase);
		
		return $salida;
	}
	
	function ObtenerPaginado($pagina,$path,$slc,$op,$codigo,$descripcion,$grupo,$clase,$subclase)
	{
		$TotalRegistros = $slc;
		$TablaPaginado = "";
			
		if($limite == null)
		{
			$uid = UserGetUID();
				$LimitRow = intval(GetLimitBrowser());
		}
		else
		{
			$LimitRow = $limite;
		}
		if ($TotalRegistros > 0)
		{
			$columnas = 1;
			$NumeroPaginas = intval($TotalRegistros/$LimitRow);
			
				if($TotalRegistros%$LimitRow > 0)
			{
				$NumeroPaginas++;
			}
					
			$Inicio = $pagina;
			if($NumeroPaginas - $pagina < 9 )
			{
				$Inicio = $NumeroPaginas - 9;
			}
			elseif($pagina > 1)
			{
				$Inicio = $pagina - 1;
			}
			
			if($Inicio <= 0)
			{
				$Inicio = 1;
			}
				
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 
	
			$TablaPaginado .= "<tr>\n";
			if($NumeroPaginas > 1)
			{
				$TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
				if($pagina > 1)
				{
					$TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
																																															//     na,criterio1,criterio2,criterio,div,forma
					$TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Busqueda('1','".$codigo."','".$descripcion."','".$grupo."','".$clase."','".$subclase."')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Busqueda('".($pagina-1)."','".$codigo."','".$descripcion."','".$grupo."','".$clase."','".$subclase."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "   </td>\n";
					$columnas +=2;
				}
				$Fin = $NumeroPaginas + 1;
				if($NumeroPaginas > 10)
				{
					$Fin = 10 + $Inicio;
				}
					
				for($i=$Inicio; $i< $Fin ; $i++)
				{
					if ($i == $pagina )
					{
						$TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
					}
					else
					{
						$TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Busqueda('".$i."','".$codigo."','".$descripcion."','".$grupo."','".$clase."','".$subclase."')\">".$i."</a></td>\n";
					}
					$columnas++;
				}
			}
			if($pagina <  $NumeroPaginas )
			{
				$TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
				$TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Busqueda('".($pagina+1)."','".$codigo."','".$descripcion."','".$grupo."','".$clase."','".$subclase."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
				$TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
				$TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Busqueda('".$NumeroPaginas."','".$codigo."','".$descripcion."','".$grupo."','".$clase."','".$subclase."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
				$TablaPaginado .= "   </td>\n";
				$columnas +=2;
			}
			$aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
			$aviso .= "     Pagina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
			$aviso .= "   </tr>\n";
			
			if($op == 2)
			{
				$TablaPaginado .= $aviso;
			}
			else
			{
				$TablaPaginado = $aviso.$TablaPaginado;
			}
		}
		$Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
		$Tabla .= $TablaPaginado;
		$Tabla .= "</table>";
		
	
		return $Tabla;
	}
	
	function BuscarRequisicion($pagina,$requisicion,$fecha_ini,$fecha_fin,$departamento,$evento,$est1,$est2,$est3)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$fecha_ini=$objClass->FechaStamp($fecha_ini);
			$fecha_fin=$objClass->FechaStamp($fecha_fin);
		}
		
		$_SESSION['Req']['modrequisi']=$objClass->BuscarRequisicionCompra($_SESSION['Req']['empresa_id'],$pagina,$requisicion,$fecha_ini,$fecha_fin,$departamento,$evento,$est1,$est2,$est3);
		
		$slc=$objClass->conteo;
		$salida=ListadoRequisicion($_SESSION['Req']['modrequisi'],$pagina,$slc,$requisicion,$fecha_ini,$fecha_fin,$departamento,$evento,$est1,$est2,$est3);
		
		$objResponse->assign("requis","innerHTML",$objResponse->setTildes($salida));
		
		return $objResponse;
	}
	
	function ReqLista($requi)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		$salida = ReqLista_HTML($requi);
		
		$objResponse->assign("d2Contents2","innerHTML",$objResponse->setTildes($salida));
		
		return $objResponse;
	}
	
	function ReqLista_HTML($requi)
	{
		$salida.="<table align=\"center\">";
		$salida.="	<tr>";
		$salida.="		<td class=\"label_error\" align=\"center\">";
		$salida.="			CONFIRMA QUE LA REQUISICION # ".$requi." SE ENCUENTRA LISTA ?";
		$salida.="		</td>";
		$salida.="	</tr>";
		$salida.="	<tr align=\"center\">";
		$salida.="		<td>";
		$salida.="			<input type=\"button\" name=\"aceptar\" value=\"ACEPTAR\" class=\"input-submit\" onclick=\"xajax_RequisicionValidada('$requi');\">";
		$salida.="			&nbsp;&nbsp;<input type=\"button\" name=\"cancelar\" value=\"CANCELAR\" class=\"input-submit\" onclick=\"Cerrar('d2Container2');\">";
		$salida.="		</td>";
		$salida.="	</tr>";
		$salida.="</table>";
		
		return $salida;
	}
	
	function RequisicionValidada($requi)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		if(($objClass->ConfirmaRequisicion($requi))==false)
			$objResponse->alert("error2".$objClass->error."<br>".$objClass->mensajeDeError);
			
		$objResponse->assign("d2Container2","style.display","none");
		$objResponse->call("JsVolver");
		
		return $objResponse;
	}
	
	function ListadoRequisicion($list_req,$pagina,$slc,$req,$fecha_ini,$fecha_fin,$dept,$evento)
	{
		$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td width=\"10%\">REQUISICIÓN</td>";
		$salida .= "		<td width=\"10%\">FECHA</td>";
		$salida .= "		<td width=\"20%\">DEPARTAMENTO</td>";
		$salida .= "		<td width=\"30%\">NOMBRE DEL USUARIO</td>";
		$salida .= "		<td width=\"10%\"># PRODUCTOS (CATALOG / NO CATALOG)</td>";
		$salida .= "		<td width=\"10%\">ESTADO</td>";
		$salida .= "		<td width=\"10%\">&nbsp;</td>";
		$salida .= "		<td width=\"10%\">DETALLE PRODUCTOS</td>";
		$salida .= "	</tr>";
		$j=0;
		$ciclo=sizeof($list_req);
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
			$salida .= "  <tr $color>";
			$salida .= "  <td align=\"center\">";
			$salida .= "".$_SESSION['Req']['modrequisi'][$i]['requisicion_id']."";
			$salida .= "  </td>";
			$salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['Req']['modrequisi'][$i]['fecha_requisicion']);
			$salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$salida .= "  </td>";
			$salida .= "  <td>";
			$salida .= "".$_SESSION['Req']['modrequisi'][$i]['descripcion']."";
			$salida .= "  </td>";
			$salida .= "  <td>";
			$salida .= "".$_SESSION['Req']['modrequisi'][$i]['nombre']."";
			$salida .= "  </td>";
			$salida .= "  <td align=\"center\">";
			$salida .= "".$_SESSION['Req']['modrequisi'][$i]['cantidad']." / ".$_SESSION['Req']['modrequisi'][$i]['cantidad2'];
			$salida .= "  </td>";
			$est="ACTIVA";
			if($_SESSION['Req']['modrequisi'][$i]['estado']=='0')
				$est="CANCELADA";
			$salida .= "	<td align=\"center\">$est</td>";
			$sw_lista="";
			if($_SESSION['Req']['modrequisi'][$i]['estado']=='1')
			{
				$sw_lista="OK";
				if($_SESSION['Req']['modrequisi'][$i]['sw_listo']=='0')
					$sw_lista="PENDIENTE";
			}
			$salida .= "	<td align=\"center\">$sw_lista</td>";
			$salida .= "  <td align=\"center\">";
			$salida .= "<a href=\"".ModuloGetURL('app','Requisiciones','user','CrearRequisicionProCompra',
			array('evento'=>$evento,'requisicion'=>$_SESSION['Req']['modrequisi'][$i]['requisicion_id'],'usuario'=>$_SESSION['Req']['modrequisi'][$i]['nombre'],'depto'=>$_SESSION['Req']['modrequisi'][$i]['descripcion'],'fecha'=>$_SESSION['Req']['modrequisi'][$i]['fecha_requisicion'],'observacion'=>$_SESSION['Req']['modrequisi'][$i]['observacion'],'estado'=>$_SESSION['Req']['modrequisi'][$i]['estado']))."\"><img src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>";
			$salida .= "  </td>";
			$salida .= "  </tr>";
		}
		if(empty($_SESSION['Req']['modrequisi']))
		{
			$salida .= "<tr class=\"modulo_list_claro\">";
			$salida .= "<td colspan=\"7\" align=\"center\">";
			$salida .= "'NO SE ENCONTRÓ NINGÚNA REQUISICIÓN'";
			$salida .= "</td>";
			$salida .= "</tr>";
		}
		$salida .= "      </table>";
		
		$objClass=new app_Requisiciones_user;
		
		$pathImg=GetThemePath();
		$op="1";
		$limite=20;
		$salida.= "".ObtenerPaginadoReq($pagina,$pathImg,$slc,$op,$req,$fecha_ini,$fecha_fin,$dept,$evento,$est1,$est2,$est3,$limite);
		
		return $salida;
	}
	
	function CancelarRequisicion($justif,$requi)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;

		if($justif)
		{
			if($objClass->GuardarRazonCancelacion($justif,$requi))
			{
				$objResponse->assign("mensaje","innerHTML","LA REQUISICION N. ".$requi." HA SIDO CANCELADA EXISTOSAMENTE");
				$objResponse->assign("canreq","innerHTML",$justif);
				$objResponse->assign("tdguar","style.display","none");
			}
			else
			{
				$objResponse->assign("mensaje","innerHTML",$objClass->error."<br>".$objClass->mensajeDeError);
			}
		}
		else
		{
			$objResponse->assign("mensaje","innerHTML","DEBE INGRESAR LA JUSTIFICACION PARA CANCELAR LA SOLICITUD");
		}
		
		return $objResponse;
	}
	
	function ObtenerPaginadoReq($pagina,$path,$slc,$op,$req,$fecha_ini,$fecha_fin,$dept,$evento,$est1,$est2,$est3,$limite)
	{
		$TotalRegistros = $slc;
		$TablaPaginado = "";
			
		if($limite == null)
		{
			$uid = UserGetUID();
			$LimitRow = intval(GetLimitBrowser());
		}
		else
		{
			$LimitRow = $limite;
		}
		if ($TotalRegistros > 0)
		{
			$columnas = 1;
			$NumeroPaginas = intval($TotalRegistros/$LimitRow);
			
				if($TotalRegistros%$LimitRow > 0)
			{
				$NumeroPaginas++;
			}
					
			$Inicio = $pagina;
			if($NumeroPaginas - $pagina < 9 )
			{
				$Inicio = $NumeroPaginas - 9;
			}
			elseif($pagina > 1)
			{
				$Inicio = $pagina - 1;
			}
			
			if($Inicio <= 0)
			{
				$Inicio = 1;
			}
				
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 
	
			$TablaPaginado .= "<tr>\n";
			if($NumeroPaginas > 1)
			{
				$TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
				if($pagina > 1)
				{
					$TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BusquedaReq('1','".$req."','".$fecha_ini."','".$fecha_fin."','".$dept."','$evento','$est1','$est2','$est3')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BusquedaReq('".($pagina-1)."','".$req."','".$fecha_ini."','".$fecha_fin."','".$dept."','$evento','$est1','$est2','$est3')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "   </td>\n";
					$columnas +=2;
				}
				$Fin = $NumeroPaginas + 1;
				if($NumeroPaginas > 10)
				{
					$Fin = 10 + $Inicio;
				}
					
				for($i=$Inicio; $i< $Fin ; $i++)
				{
					if ($i == $pagina )
					{
						$TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
					}
					else
					{
						$TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BusquedaReq('".$i."','".$req."','".$fecha_ini."','".$fecha_fin."','".$dept."','$evento','$est1','$est2','$est3')\">".$i."</a></td>\n";
					}
					$columnas++;
				}
			}
			if($pagina <  $NumeroPaginas )
			{
				$TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
				$TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BusquedaReq('".($pagina+1)."','".$req."','".$fecha_ini."','".$fecha_fin."','".$dept."','$evento','$est1','$est2','$est3')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
				$TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
				$TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BusquedaReq('".$NumeroPaginas."','".$req."','".$fecha_ini."','".$fecha_fin."','".$dept."','$evento','$est1','$est2','$est3')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
				$TablaPaginado .= "   </td>\n";
				$columnas +=2;
			}
			$aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
			$aviso .= "     Pagina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
			$aviso .= "   </tr>\n";
			
			if($op == 2)
			{
				$TablaPaginado .= $aviso;
			}
			else
			{
				$TablaPaginado = $aviso.$TablaPaginado;
			}
		}
		$Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
		$Tabla .= $TablaPaginado;
		$Tabla .= "</table>";
		
		return $Tabla;
	}
	
	function BuscarRequisicionCompras($pagina,$requisicion,$fecha_ini,$fecha_fin,$departamento,$evento,$est1,$est2,$est3)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_Requisiciones_user;
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$fecha_ini=$objClass->FechaStamp($fecha_ini);
			$fecha_fin=$objClass->FechaStamp($fecha_fin);
		}
		
		$_SESSION['Req']['modrequisi']=$objClass->BuscarRequisicionCompra($_SESSION['Req']['empresa_id'],$pagina,$requisicion,$fecha_ini,$fecha_fin,$departamento,$evento,$est1,$est2,$est3);
		
		$slc=$objClass->conteo;
		$salida=ListadoRequisicion($_SESSION['Req']['modrequisi'],$pagina,$slc,$requisicion,$fecha_ini,$fecha_fin,$departamento,$evento);
		
		$objResponse->assign("requis","innerHTML",$objResponse->setTildes($salida));
		
		return $objResponse;
	}
?>