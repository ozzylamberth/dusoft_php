<?php

/**
 * $Id: app_Requisiciones_userclasses_HTML.php,v 1.5 2007/06/26 13:19:46 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */


class app_Requisiciones_userclasses_HTML extends app_Requisiciones_user
{
	
	
	function app_Requisiciones_userclasses_HTML()
	{
		$this->app_Requisiciones_user();
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("RemoteScripting");
		
		$this->salida='';
		return true;
	}
	
	function Principal()
	{
		unset($_SESSION['Req']);
		if($this->UsuariosRequisiciones()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de Requisiciones
	function PrincipalRequisicion()//Llama a todas las opciones posibles
	{
		if($_REQUEST['PermisoReq']['empresa_id'])
		{
			$_SESSION['Req']['empresa_id']=$_REQUEST['PermisoReq']['empresa_id'];
			$_SESSION['Req']['razonso']=$_REQUEST['PermisoReq']['descripcion1'];
			$_SESSION['Req']['centroutil']=$_REQUEST['PermisoReq']['centro_utilidad'];
			$_SESSION['Req']['descentro']=$_REQUEST['PermisoReq']['descripcion2'];
			$_SESSION['Req']['usuario_id']=$_REQUEST['PermisoReq']['usuario_id'];
			$_SESSION['Req']['usuariodes']=$_REQUEST['PermisoReq']['nombre'];
		}
		
		$this->salida  = ThemeAbrirTabla('REQUISICIONES - OPCIONES');
		
		$actionA=ModuloGetURL('app','Requisiciones','user','CrearRequisicionCompra');
		$actionB=ModuloGetURL('app','Requisiciones','user','RequisicionCompra',array('evento'=>1));
		$actionC=ModuloGetURL('app','Requisiciones','user','RequisicionCompra',array('evento'=>2));
		$actionD=ModuloGetURL('app','Requisiciones','user','RequisicionCompra',array('evento'=>3));
		$actionE=ModuloGetURL('app','Requisiciones','user','GenerarOrdenCompraReq');
		
		$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">";
		$this->salida .= "		<td align=\"center\">MENU</td>";
		$this->salida .= "	</tr>";
		/*$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "		<td align=\"center\">REQUISICION</td>";
		$this->salida .= "	</tr>";*/
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><a href=\"$actionA\">CREAR UNA REQUISICIÓN</a></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><a href=\"$actionB\">MODIFICAR UNA REQUISICIÓN</a></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><a href=\"$actionC\">CANCELAR UNA REQUISICIÓN</a></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><a href=\"$actionD\">CONSULTAR REQUISICIÓN(ES)</a></td>";
		$this->salida .= "	</tr>";
		/*$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "		<td align=\"center\">ORDEN DE COMPRA</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><a href=\"$actionE\">GENERAR ORDEN DE COMPRA</a></td>";
		$this->salida .= "	</tr>";*/
		$this->salida .= "</table><br>";
	
		$accion=ModuloGetURL('app','Requisiciones','user','Principal');
		
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

	//Función que crea una requisición para uno o varios productos
	function CrearRequisicionCompra()//Válida los datos de quien solicita, y despues los productos y las cantidades
	{
		
		$this->salida  = ThemeAbrirTabla('CREAR UNA REQUISICIÓN');
		
		$accion=ModuloGetURL('app','Requisiciones','user','CrearRequisicionProCompra');
	
		
		$this->salida .= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:</td>";
		$this->salida .= "			<td class=\"modulo_list_claro\" align=\"center\" width=\"70%\">".$_SESSION['Req']['razonso']."</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table><br>";
		
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		
		$departamentos=$this->BuscarDepartamentos($_SESSION['Req']['empresa_id']);
		
		$this->salida .= "      <div id=\"mensaje1\" align=\"center\" class=\"label_error\"></div>";
		
		$this->salida .= "<form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  	<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"40%\">NOMBRE DEL USUARIO:</td>";
		$this->salida .= "			<td width=\"60%\">".$_SESSION['Req']['usuariodes']."</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"40%\">DEPARTAMENTO:</td>";
		$this->salida .= "			<td>";
		$this->salida .= "				<select name=\"departamento\" class=\"select\">";
		$this->salida .= "      		<option value=\"\">-- SELECCIONE DEPARTAMENTO --</option>";
		foreach($departamentos as $valor)
			$this->salida .="					<option value=\"".$valor['departamento']."__".$valor['descripcion']."\" >".$valor['descripcion']."</option>";
		$this->salida .= "				</select>";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"modulo_table_list_title\">FECHA:</td>";
		$this->salida .= "			<td>".date("d / m / Y")."</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"modulo_table_list_title\">OBSERVACIÓN SOBRE LA SOLICITUD:</td>";
		$this->salida .= "			<td><textarea class=\"input-text\" name=\"razonsod\" cols=\"60\" rows=\"4\"></textarea></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table><br>";

		$this->salida .= "	<table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "		<tr>";
		$this->salida .= "  		<td width=\"50%\" align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"guardar\" value=\"GUARDAR\" onclick=\"ValidarDatos();\"></td>";
		$this->salida .= "	</form>";
		$accionS=ModuloGetURL('app','Requisiciones','user','PrincipalRequisicion');
		
		$this->salida .= "  <script>\n";
		$this->salida .= "  	function ValidarDatos()\n";
		$this->salida .= "  	{\n";
		$this->salida .= "  		if(document.forma1.departamento.value==''){\n";
		$this->salida .= "  			document.getElementById('mensaje1').innerHTML='DEBE SELECCIONAR EL DEPARTAMENTO';}\n";
		$this->salida .= "  		else if(document.forma1.razonsod.value==''){\n";
		$this->salida .= "  			document.getElementById('mensaje1').innerHTML='DEBE INGRESAR LA OBSERVACION';}\n";
		$this->salida .= "  		else{\n";
		$this->salida .= "  			document.forma1.submit();}\n";
		$this->salida .= "  	}\n";
		$this->salida .= "  </script>\n";
		
		$this->salida .= "			<form name=\"forma2\" action=\"$accionS\" method=\"post\">";
		$this->salida .= "				<td width=\"50%\" align=\"center\">";
		$this->salida .= "  			<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  			</td>";
		$this->salida .= "  		</form>";
		$this->salida .= "  	</tr>";
		$this->salida .= "  </table>";
		
		$this->salida .= ThemeCerrarTabla();

		return true;
	}
	
/*************************************************************************************************************/
	
	function CrearRequisicionProCompra()//Hasta que no guarde no deja cancelar la transacción
	{
		SessionSetVar("ImgRuta",GetThemePath());

		if($_REQUEST['departamento'] AND $_REQUEST['razonsod'])
			$this->GuardarDatos_CrearReq($_REQUEST['departamento'],$_REQUEST['razonsod']);
			
		unset($_SESSION['Req']['evento']);
		
		if($_REQUEST['evento'])
		{
			$_SESSION['Req']['evento']=$_REQUEST['evento'];
			$_SESSION['Req']['requisicio']=$_REQUEST['requisicion'];
			$_SESSION['Req']['usuariodes']=$_REQUEST['usuario'];
			$_SESSION['Req']['departades']=$_REQUEST['depto'];
			$_SESSION['Req']['fecharequi']=$_REQUEST['fecha'];
			$_SESSION['Req']['razonsolco']=$_REQUEST['razonsod'];
		}
			
		$this->salida  = ThemeAbrirTabla('REQUISICIONES - LISTA DE PRODUCTOS PARA SOLICITAR');

		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['Req']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['Req']['usuariodes']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['Req']['departades']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['Req']['fecharequi']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">OBSERVACIÓN SOBRE LA SOLICITUD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['Req']['razonsod']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['Req']['requisicio']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		
		$this->SetXajax(array("GetDatos","EliminarProducto","GuardarDatosN","GenerarBusqueda","Agregar","IngProNoCatalog","GuardarNPC","ListadoProNoCatalog","EliminarProductoNoCatalogado","CancelarRequisicion","ReqLista","RequisicionValidada"),"app_modules/Requisiciones/RemoteXajax/Requisiciones_Xajax.php");
		
		$this->salida .= "	<center><div id=\"mensaje\" class=\"label_error\"></div></center>";
		if(!$_REQUEST['evento'] OR $_REQUEST['evento']==1)
		{
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .= "		<td align=\"left\"><a href=\"javascript:Iniciar('BUSCADOR PRODCUTOS');Busqueda('1','0','0','0','0','0');MostrarSpan('d2Container');\">BUSQUEDA DE PRODUCTOS</a></td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table><br>";
		}
		
		$this->salida .= "<input type=\"hidden\" id=\"arr1\" name=\"arr1\">";
		$this->salida .= "<input type=\"hidden\" id=\"arr2\" name=\"arr2\">";
		
		$this->salida .= "<div id=\"listC\">";
		$this->salida .= " <script>";
		$this->salida .= " 	xajax_Agregar('0','0','0');";
		$this->salida .= " </script>";
		$this->salida .= "</div>";
		
		$this->salida .= "<div id=\"listado_NPC\">";
		$this->salida .= " <script>";
		$this->salida .= " 	xajax_ListadoProNoCatalog();";
		$this->salida .= " </script>";
		$this->salida .= "</div>";
		
		if(!$_REQUEST['evento'])
		{
			$actionV=ModuloGetURL('app','Requisiciones','user','PrincipalRequisicion');
			$nombre="VOLVER AL MENU";
			$est_b="disabled";
		}
		else
		{
			$actionV=ModuloGetURL('app','Requisiciones','user','RequisicionCompra',array('evento'=>$_REQUEST['evento']));
			$nombre="VOLVER";
			$est_b="";
		}
		
		if($_REQUEST['evento']==2)
		{
			if($_REQUEST['estado']=='1')
			{
				$this->salida .= "<br><table border=\"0\" width=\"40%\" align=\"center\">";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">";   
				$this->salida .= "			<td>JUSTIFICACION PARA CANCELAR LA REQUISICION</td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr class=\"modulo_list_claro\">";
				$this->salida .= "			<td align=\"center\" id=\"canreq\">";    
				$this->salida .= "				<textarea class=\"textarea\" id=\"justifreq\" name=\"justifreq\" rows=\"4\" cols=\"50\"></textarea>";
				$this->salida .= "			</td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr class=\"modulo_list_claro\" id=\"tdguar\">";
				$this->salida .= "		<td align=\"center\"><input type=\"button\" class=\"input-submit\" name=\"guardarRaz\" value=\"GUARDAR\" onclick=\"xajax_CancelarRequisicion(xGetElementById('justifreq').value,'".$_SESSION['Req']['requisicio']."');\"></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "	</table>";
			}
			else
			{
				$this->salida .= "<br><table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "		<tr>";   
				$this->salida .= "			<td width=\"20%\" class=\"modulo_table_list_title\">JUSTIFICACION PARA CANCELAR LA REQUISICION</td>";
				$this->salida .= "			<td width=\"80%\" align=\"left\" class=\"modulo_list_claro\">".$_REQUEST['observacion']."</td>";
				$this->salida .= "		</tr>";
				$this->salida .= "	</table>";
			
			}
		}
		
		
		$this->salida .= "	<br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "   <tr>";  
		if(!$_REQUEST['evento'] OR $_REQUEST['evento']==1)
		{
			$this->salida .= "		<td><input type=\"button\" class=\"input-submit\" name=\"guardar_list\" value=\"GUARDAR\" onclick=\"FuncionG(); document.forma_vol.volver.disabled=false; document.getElementById('sw_lista').disabled=false;\"></td>";
		}
		$this->salida .= "<form name=\"forma_vol\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= "    <td align=\"center\">";    
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" id=\"volver\" name=\"volver\" value=\"$nombre\" $est_b>";
		$this->salida .= "    </td>";
		$this->salida .= "</form>";
		/*if(!$_REQUEST['evento'] OR $_REQUEST['evento']==1)
		{
			$this->salida .= "		<td><input type=\"button\" class=\"input-submit\" id=\"sw_lista\" name=\"sw_lista\" value=\"REQUISICION LISTA\" onclick=\"IniciarConf('CONFIRMACION');MostrarSpan('d2Container2');xajax_ReqLista('".$_SESSION['Req']['requisicio']."');\"></td>";
		}*/
		$this->salida .= "	</tr>";
		$this->salida .= "	</table>";
		
		$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
		$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='d2Contents' class=\"d2Content\">\n";
		$this->salida .= "	</div>\n"; 
		$this->salida .= "</div>\n";
		
		$this->salida .= "<div id='d2Container2' class='d2Container' style=\"display:none\">\n";
		$this->salida .= "	<div id='titulo2' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container2')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$this->salida .= "	<div id='error2' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='d2Contents2' class=\"d2Content\">\n";
		$this->salida .= "	</div>\n"; 
		$this->salida .= "</div>\n";
		
		$this->salida .= "<script>\n";
		
		$this->salida .= "	var contenedor = '';\n";
		$this->salida .= "	var titulo = '';\n";
		$this->salida .= "	var hiZ = 2;\n";
		$this->salida .= "	var datos1;\n";
		$this->salida .= "	var datos2;\n";
		
		$this->salida .= "	function LimpiarBusquedaPro()\n";
		$this->salida .= "	{\n";
		$this->salida .= "		document.getElementById('codigo').value='';\n";
		$this->salida .= "		document.getElementById('descripcion').value='';\n";
		$this->salida .= "		document.getElementById('grupo').value='';\n";
		$this->salida .= "		document.getElementById('clase').value='';\n";
		$this->salida .= "		document.getElementById('subclase').value='';\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function FuncionG()\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var cant1=new Array();\n";
		$this->salida .= "		var c1=new Array();\n";
		$this->salida .= "		var cant2=new Array();\n";
		$this->salida .= "		var c2=new Array();\n";
		$this->salida .= "		if(datos1!=''){\n";
		$this->salida .= "			c1=jsrsArrayFromString(datos1, '__' );\n";
		$this->salida .= "			for(var i=0;i<c1.length;i++){\n";
		$this->salida .= "				cant1[i]=xGetElementById(''+c1[i]).value;}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(datos2!=''){\n";
		$this->salida .= "			c2=jsrsArrayFromString(datos2, '__' );\n";
		$this->salida .= "			for(var i=0;i<c2.length;i++){\n";
		$this->salida .= "				cant2[i]=xGetElementById(''+c2[i]).value;}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		xajax_GuardarDatosN(cant1,cant2);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function EliminarPro(cod,emp,req,capa)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_EliminarProducto(cod,emp,req,capa);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function EliminarProNoCatalog(cod,emp,req,capa)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_EliminarProductoNoCatalogado(cod,emp,req,capa);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Busqueda(pag,cod,desc,gp,cl,subcl)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_GenerarBusqueda(pag,cod,desc,gp,cl,subcl);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function AgregarEnLista(cod,req,cant,cap)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_Agregar(cod,req,cant,cap);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function ObtenerArr1()\n";
		$this->salida .= "	{\n";
		$this->salida .= "		datos1=xGetElementById('arr1').value;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function ObtenerArr2()\n";
		$this->salida .= "	{\n";
		$this->salida .= "		datos2=xGetElementById('arr2').value;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function IngProNoCatalog()\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_IngProNoCatalog();\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function GuardarNPC(prod,prov,gene,cant,just)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_GuardarNPC(prod,prov,gene,cant,just);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function JsVolver()\n";
		$this->salida .= "	{\n";
		$this->salida .= "		document.forma_vol.submit();\n";
		$this->salida .= "	}\n";

		$this->salida .= "	function Iniciar(tit)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	 	contenedor = 'd2Container';\n";
		$this->salida .= "		titulo = 'titulo';\n";
		$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
		$this->salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+50);\n";
		$this->salida .= "	  xResizeTo(ele,700,'auto');\n";
		$this->salida .= "		ele = xGetElementById('d2Contents');\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
		$this->salida .= "	  xResizeTo(ele,700, 400);\n";
		$this->salida .= "		ele = xGetElementById(titulo);\n";
		$this->salida .= "	  xResizeTo(ele,680, 20);\n";
		$this->salida .= "		xMoveTo(ele, 0, 0);\n";
		$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$this->salida .= "		ele = xGetElementById('cerrar');\n";
		$this->salida .= "	  xResizeTo(ele,20, 20);\n";
		$this->salida .= "		xMoveTo(ele, 680, 0);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Iniciar2(tit)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	 	contenedor = 'd2Container';\n";
		$this->salida .= "		titulo = 'titulo';\n";
		$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
		$this->salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+50);\n";
		$this->salida .= "	  xResizeTo(ele,410,'auto');\n";
		$this->salida .= "		ele = xGetElementById('d2Contents');\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
		$this->salida .= "	  xResizeTo(ele,410, 260);\n";
		$this->salida .= "		ele = xGetElementById(titulo);\n";
		$this->salida .= "	  xResizeTo(ele,390, 20);\n";
		$this->salida .= "		xMoveTo(ele, 0, 0);\n";
		$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$this->salida .= "		ele = xGetElementById('cerrar');\n";
		$this->salida .= "	  xResizeTo(ele,20, 20);\n";
		$this->salida .= "		xMoveTo(ele, 390, 0);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function IniciarConf(tit)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	 	contenedor = 'd2Container2';\n";
		$this->salida .= "		titulo = 'titulo2';\n";
		$this->salida .= "		document.getElementById('error2').innerHTML = '';\n";
		$this->salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+50);\n";
		$this->salida .= "	  xResizeTo(ele,410,'auto');\n";
		$this->salida .= "		ele = xGetElementById('d2Contents');\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
		$this->salida .= "	  xResizeTo(ele,410, 260);\n";
		$this->salida .= "		ele = xGetElementById(titulo);\n";
		$this->salida .= "	  xResizeTo(ele,390, 20);\n";
		$this->salida .= "		xMoveTo(ele, 0, 0);\n";
		$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$this->salida .= "		ele = xGetElementById('cerrar2');\n";
		$this->salida .= "	  xResizeTo(ele,20, 20);\n";
		$this->salida .= "		xMoveTo(ele, 390, 0);\n";
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

		$this->salida .= "</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	
	function RequisicionCompra()//Busca la requisición a modificar
	{
		$ev=$_REQUEST['evento'];
		$cols="3";
		if($ev==1)
		{
			$titulo='MODIFICAR UNA REQUISICIÓN';
		}
		
		if($ev==2)
		{
			$titulo='CANCELAR UNA REQUISICIÓN';
		}
		
		if($ev==3)
		{
			$titulo='CONSULTAR UNA REQUISICIÓN';
			$cols="2";
		}
		
		$this->salida  = ThemeAbrirTabla($titulo);
		
		$this->SetXajax(array("BuscarRequisicion"),"app_modules/Requisiciones/RemoteXajax/Requisiciones_Xajax.php");
		
		$this->salida .= "			<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "				<tr class=modulo_list_claro>";
		$this->salida .= "					<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:</td>";
		$this->salida .= "					<td align=\"center\" width=\"70%\">".$_SESSION['Req']['razonso']."</td>";
		$this->salida .= "				</tr>";
		$this->salida .= "			</table><br>";
		
		$this->salida .= "<form name=\"formaB\" action=\"\" method=\"post\">";
		$this->salida .= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"10%\">REQUISICIÓN</td>";
		$this->salida .= "			<td class=\"modulo_list_oscuro\" width=\"10%\"><input type=\"text\" class=\"input-text\" name=\"requisicion\" value=\"\" maxlength=\"10\" size=\"10\"></td>";
		$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"10%\">FECHAS</td>";
		$this->salida .= "			<td class=\"modulo_list_oscuro\" width=\"40%\"> DE <input type=\"text\" readonly class=\"input-text\" name=\"fecha_ini\" value=\"\" maxlength=\"10\" size=\"10\"><sub>".ReturnOpenCalendario("formaB","fecha_ini","-")."</sub>";
		$this->salida .= "			A <input type=\"text\" readonly class=\"input-text\" name=\"fecha_fin\" value=\"\" maxlength=\"10\" size=\"10\"><sub>".ReturnOpenCalendario("formaB","fecha_fin","-")."</sub></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"20%\">DEPARTAMENTO</td>";
		$this->salida .= "			<td class=\"modulo_list_oscuro\" colspan=\"$cols\" width=\"80%\">";
		$departamento=$this->BuscarDepartamentos($_SESSION['Req']['empresa_id']);
		$this->salida .= "				<select name=\"departamento\" class=\"select\">";
		$this->salida .="<option value=\"\">--SELECCIONE DEPARTAMENTO--</option>";
		foreach($departamento as $valor)
		{
			$this->salida .="<option value=\"".$valor['departamento']."\">".$valor['descripcion']."</option>";
		}
		$this->salida .= "				</select>";
		$this->salida .= "			</td>";
		
		$style="display:none";
		if($_REQUEST['evento']==3)
				$style="";
		$this->salida .= "			<td class=\"modulo_list_oscuro\" style=\"$style\">ESTADO : <input type=\"radio\" id=\"estreq[]\" name=\"estreq\" value=\"1\" checked>TODAS <input type=\"radio\" id=\"estreq[]\" name=\"estreq\" value=\"2\">ACTIVAS <input type=\"radio\" id=\"estreq[]\" name=\"estreq\" value=\"3\">CANCELADAS</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "		<td class=\"modulo_list_oscuro\" colspan=\"4\"><input type=\"button\" class=\"input-submit\" name=\"Buscar\" value=\"BUSCAR\" onclick=\"xajax_BuscarRequisicion('1',this.form.requisicion.value,this.form.fecha_ini.value,this.form.fecha_fin.value,this.form.departamento.value,'$ev',this.form.estreq[0].checked,this.form.estreq[1].checked,this.form.estreq[2].checked);\">";
		$this->salida .= "		&nbsp;&nbsp;<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"LIMPIAR CAMPOS\" onclick=\"this.form.requisicion.value='';this.form.fecha_ini.value='';this.form.fecha_fin.value='';this.form.departamento.value='';this.form.estreq[0].checked=true;\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "	<div id=\"requis\">";
		$this->salida .= "	</div>";
		
		$this->salida .= "      <br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$accion=ModuloGetURL('app','Requisiciones','user','PrincipalRequisicion');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER AL MENU\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		
		$this->salida .= "	<script>\n";
		$this->salida .= "		function BusquedaReq(pag,req,fecha_i,fecha_f,dept,evento,est1,est2,est3)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			xajax_BuscarRequisicion(pag,req,fecha_i,fecha_f,dept,evento,est1,est2,est3);\n";
		$this->salida .= "		}\n";
		$this->salida .= "	</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	function GenerarOrdenCompraReq()
	{
		$this->salida .= ThemeAbrirTabla("GENERAR ORDEN DE COMPRA - SELECCION DE REQUISICIONES");

		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=modulo_list_claro>";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "		".$_SESSION['Req']['razonso']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=modulo_list_claro>";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "			".$_SESSION['Req']['usuariodes']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		
		$this->SetXajax(array("ListadoProductosRequisicion"),"app_modules/Requisiciones/RemoteXajax/Requisiciones_Xajax.php");
		
		$this->salida .= "<div id=\"mensaje\" class=\"label_error\" align=\"center\"></div>";
		
		$requis=$this->BuscarReq($_SESSION['Req']['empresa_id']);
		$accion=ModuloGetURL('app','Requisiciones','user','GenerarOrdenCompraPro');
		$this->salida .= "<form name=\"forma_gc\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">";
		$this->salida .= "		<td width=\"10%\">REQUISICIÓN</td>";
		$this->salida .= "		<td width=\"10%\">FECHA</td>";
		$this->salida .= "		<td width=\"20%\">DEPARTAMENTO</td>";
		$this->salida .= "		<td width=\"40%\">NOMBRE DEL USUARIO</td>";
		$this->salida .= "		<td width=\"10%\"># PRODUCTOS (CATALOG / NO CATALOG)</td>";
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
		
		$accionV=ModuloGetURL('app','Requisiciones','user','PrincipalRequisicion');
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
		$this->salida .= "		".$_SESSION['Req']['razonso']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "			".$_SESSION['Req']['usuariodes']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		
		$proveedores=$this->GetProveedores();
		
		$this->salida .= "<div id=\"mensaje\" class=\"label_error\" align=\"center\"></div>";
		
		$accion=ModuloGetURL('app','Requisiciones','user','ConfirmacionOrdendeCompra',array('Seleccion'=>$_REQUEST['Seleccion']));
		
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
		$this->salida .= "		<td width=\"10%\">VALOR</td>";
		$this->salida .= "		<td width=\"10%\">CANTIDAD REQUERIDA</td>";
		$this->salida .= "		<td width=\"5%\">CANTIDAD</td>";
		$this->salida .= "		<td width=\"10%\">VALOR NETO</td>";
		$this->salida .= "		<td width=\"5%\"> % IVA</td>";
		$this->salida .= "		<td width=\"10%\">VALOR TOTAL</td>";
		$this->salida .= "		<td width=\"5%\"><input type=\"checkbox\" name=\"todos\" value=\"1\" onclick=\"SeleccionarTodos(this.form,this.checked);\"></td>";
		$this->salida .= "	</tr>";
		
		$checks=$_REQUEST['Seleccion'];
		
		$productos=$this->ListarProductosCompra($_SESSION['Req']['empresa_id'],$checks);

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
			$this->salida .= "		$ <input type=\"text\" id=\"valor_costo$j\" name=\"valor_costo[]\" value=\"".$_REQUEST['Datos']["valor_costo"][$j]."\" maxlength=\"20\" size=\"10\" class=\"input-text\" onkeyup=\"CalcularValor(xGetElementById('uncantidad$j').value,xGetElementById('valor_costo$j').value,'".$productos[$j]['porcentaje_iva']."','valor_neto$j','valor_total$j','v_neto$j','v_total$j'); document.getElementById('valor_costo$j').style.background='';\">";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"center\">";
			$this->salida .= "		".$productos[$j]['cantidad']."";
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
			$this->salida .= "		".$productos[$j]['porcentaje_iva']." %";
			$this->salida .= "	</td>";
			$this->salida .= "	<td align=\"right\" id=\"valor_total$j\">";
			$this->salida .= "		$ ".FormatoValor($_REQUEST['Datos']["v_total"][$j])."";
			$this->salida .= "	</td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "		<input type=\"checkbox\" id=\"SeleccionPro$j\" name=\"SeleccionPro[]\" value=\"".$productos[$j]['codigo_producto'].".-.".$productos[$j]['descripcion'].".-.".$productos[$j]['porcentaje_iva'].".-.".$productos[$j]['desunidad'].".-.".$productos[$j]['contenido_unidad_venta'].".-.".$j."\" onclick=\"Checkeo(this.checked,'$j');\" $check>";
			$this->salida .= "  </td>";
			$this->salida .= "</tr>";
			$this->salida .= "<input type=\"hidden\" id=\"v_total$j\" name=\"v_total[]\" value=\"".$_REQUEST['Datos']["v_total"][$j]."\">";
			$this->salida .= "<input type=\"hidden\" id=\"v_neto$j\" name=\"v_neto[]\" value=\"".$_REQUEST['Datos']["v_neto"][$j]."\">";
		}
		$this->salida .= "	<input type=\"hidden\" name=\"todo\" value=\"\">";
		
		$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "  	<td align=\"right\" colspan=\"11\"><input type=\"button\" class=\"input-submit\" name=\"generarcompra\" value=\"GENERAR ORDEN COMPRA\" onclick=\"ConfirmacionOrdenCompra(this.form);\"></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		$this->salida .= "</form>";
		
		$this->salida .= "<div id=\"mensaje1\" class=\"label_error\" align=\"center\"></div>";
		
		$accionV=ModuloGetURL('app','Requisiciones','user','GenerarOrdenCompraReq');
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
		$this->salida .= "				arreglo+=xGetElementById('SeleccionPro'+DatosPos[i]).value+'.-.'+xGetElementById('valor_costo'+DatosPos[i]).value+'.-.'+xGetElementById('uncantidad'+DatosPos[i]).value+'.-.'+xGetElementById('v_neto'+DatosPos[i]).value+'.-.'+xGetElementById('v_total'+DatosPos[i]).value+separador;\n";
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
		$this->salida .= "		".$_SESSION['Req']['razonso']."";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td align=\"left\" width=\"70%\">";
		$this->salida .= "			".$_SESSION['Req']['usuariodes']."";
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
		
		$accion=ModuloGetURL('app','Requisiciones','user','GuardarDatosOrdenCompra',array('proveedor'=>$_REQUEST['proveedor'],'datosTodo'=>$datos));
		
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
		
		$accionV=ModuloGetURL('app','Requisiciones','user','GenerarOrdenCompraPro',array('Seleccion'=>$_REQUEST['Seleccion'],'Datos'=>$_REQUEST,'Posicion'=>$arr));
		
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
	
	function FormaMensaje($orden)
	{
		$this->salida .= ThemeAbrirTabla('ORDEN DE COMPRA N.'.$orden);
		
		$this->salida .= "<table align=\"center\" width=\"50%\">";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td align=\"center\" class=\"modulo_list_claro\">";
		$this->salida .= "  		<label class=\"label_error\">SE HA GENERADO UNA ORDEN DE COMPRA N. $orden</label>";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table><br>";
		
		$accionV=ModuloGetURL('app','Requisiciones','user','PrincipalRequisicion');
	
		$this->salida .= "<form name=\"formaV\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "	<table align=\"center\" width=\"50%\">";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td align=\"center\">";
		$this->salida .= "  			<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTabla();
	
	
	}
	
/*************************************************************************************************************/
}//fin de la clase
?>