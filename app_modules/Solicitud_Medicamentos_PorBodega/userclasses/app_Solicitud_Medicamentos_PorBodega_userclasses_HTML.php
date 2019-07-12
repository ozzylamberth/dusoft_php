<?php

/**
 * $Id: app_Solicitud_Medicamentos_PorBodega_userclasses_HTML.php,v 1.11 2007/06/28 20:39:17 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class app_Solicitud_Medicamentos_PorBodega_userclasses_HTML extends app_Solicitud_Medicamentos_PorBodega_user
{

  function app_Solicitud_Medicamentos_PorBodega_userclasses_HTML()
	{
		$this->salida='';
		$this->app_Solicitud_Medicamentos_PorBodega_user();
		return true;
	}
  
	function SetStyle($campo)
	{
		if($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}
	
	
	function Principal()
	{
		if($this->PermisosUsuarios()==false)
		{
			return false;
		}
		return true;
	}
	
  /**
  * Function que muestra al menu con la opciones que puede seleccionar para trabajar
  * @return boolean
  */
	function frmCuadroSolicitudes()
	{
		$this->IncludeJS("RemoteScripting");
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");

		if($_REQUEST['PermisoSolMed'])
		{
			$_SESSION['Bodegas']['empresa_id']=$_REQUEST['PermisoSolMed']['empresa_id'];
			$_SESSION['Bodegas']['empresa_desc']=$_REQUEST['PermisoSolMed']['descripcion1'];
			$_SESSION['Bodegas']['centro_id']=$_REQUEST['PermisoSolMed']['centro_utilidad'];
			$_SESSION['Bodegas']['centro_desc']=$_REQUEST['PermisoSolMed']['descripcion2'];
			$_SESSION['Bodegas']['bodega']=$_REQUEST['PermisoSolMed']['bodega'];
			$_SESSION['Bodegas']['bodega_desc']=$_REQUEST['PermisoSolMed']['descripcion3'];
		}
		
		$this->SetXajax(array("Solicitudes","Despachos","Devoluciones","ExistenciasMinimas","ConfirmacionTransferencias","DetConfirmarTrans","DetalleSolicitudInd","Despachar","ConfirmarDespacho","DetalleSolicitudDev","RealizarDevolucion","CancelarProDevolucion","CancelarProDev","DespacharDpto","DespacharDptoSC","ConfirmarDespachoDpto","DevolucionDpto","ConfirmarDevolucionDpto","MostrarLotesPtosDevols","InsertarFechaVenLotedev","EliminarFechaVDevol","GetMedicamentoSimil"),"app_modules/Solicitud_Medicamentos_PorBodega/RemoteXajax/Sol_Med_PorBodega_xajax.php");

		$this->salida .= ThemeAbrirTabla('CUADRO SOLICITUDES');
		
		$this->salida.="	<table border=\"0\" width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$this->salida.="		<tr align=\"center\">";
    $this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				EMPRESA";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"25%\">";
		$this->salida.="				".$_REQUEST['PermisoSolMed']['descripcion1']."";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				CENTRO_UTILIDAD";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"20%\">";
		$this->salida.="				".$_REQUEST['PermisoSolMed']['descripcion2']."";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				BODEGA";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"25%\">";
		$this->salida.="				".$_REQUEST['PermisoSolMed']['descripcion3']."";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table><br>";
		
		$this->salida.="	<div id=\"mensaje_error\" class=\"label_error\" align=\"center\"></div>";

		$this->salida.="	<table border=\"0\" width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$this->salida.="		<tr class=\"modulo_table_list_title\" >";
    $this->salida.="			<td class=\"hc_table_submodulo_list_title\" id=\"cont_sol\" align=\"left\">";
		$this->salida.="			</td>";
		$this->salida.="			<td width=\"100%\" align=\"left\">";
		$this->salida.="				<a href=\"javascript:showhide('capa_sol');Recargar('F1');\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a> &nbsp;&nbsp; SOLICITUDES";
		$this->salida.="			</td>";
		$this->salida.="		<tr class=\"modulo_list_claro\" align=\"center\">";
		$this->salida.="			<td valign=\"top\" colspan=\"2\" id=\"capa_sol\" style=\"display:none\">";
		$this->salida.="				<script>";
		$this->salida.="					xajax_Solicitudes('".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida.="				</script>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"modulo_table_list_title\">";
		$this->salida.="			<td class=\"hc_table_submodulo_list_title\" id=\"cont_des\" align=\"left\">";
		$this->salida.="			</td>";
		$this->salida.="			<td width=\"100%\" align=\"left\">";
		$this->salida.="				<sub><a href=\"javascript:showhide('capa_des');Recargar('F2');\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a> &nbsp;&nbsp; DESPACHOS SIN CONFIRMAR</sub>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"modulo_list_claro\">";
		$this->salida.="			<td valign=\"top\" colspan=\"2\" id=\"capa_des\" style=\"display:none\">";
		$this->salida.="				<script>";
		$this->salida.="					xajax_Despachos('".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida.="				</script>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"modulo_table_list_title\">";
    $this->salida.="			<td class=\"hc_table_submodulo_list_title\" id=\"cont_dev\" align=\"left\">";
		$this->salida.="			</td>";
		$this->salida.="			<td width=\"100%\" align=\"left\">";
		$this->salida.="				<sub><a href=\"javascript:showhide('capa_dev');Recargar('F3');\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a> &nbsp;&nbsp; DEVOLUCIONES POR CONFIRMAR</sub>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"modulo_list_claro\" align=\"left\">";
		$this->salida.="			<td valign=\"top\" colspan=\"2\" id=\"capa_dev\" style=\"display:none\">";
		$this->salida.="				<script>";
		$this->salida.="					xajax_Devoluciones('".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida.="				</script>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"modulo_table_list_title\">";
		$this->salida.="			<td class=\"hc_table_submodulo_list_title\" id=\"cont_exi\" align=\"left\">";
		$this->salida.="			</td>";
		$this->salida.="			<td width=\"100%\" align=\"left\">";
		$this->salida.="				<sub><a href=\"javascript:showhide('capa_exi');Recargar('F4');\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a> &nbsp;&nbsp; EXISTENCIAS MINIMAS</sub>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"modulo_list_claro\" align=\"center\">";
		$this->salida.="			<td valign=\"top\" colspan=\"2\" id=\"capa_exi\" style=\"display:none\">";
		$this->salida.="				<script>";
		$this->salida.="				xajax_ExistenciasMinimas('1','".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida.="				</script>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		/*$this->salida.="		<tr class=\"modulo_table_list_title\">";
		$this->salida.="			<td class=\"hc_table_submodulo_list_title\" id=\"cont_contrans\" align=\"left\">";
		$this->salida.="			</td>";
		$this->salida.="			<td width=\"100%\" align=\"left\">";
		$this->salida.="				<sub><a href=\"javascript:showhide('capa_contrans');Recargar('F5');\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a> &nbsp;&nbsp; CONFIRMACION DE TRANSFERENCIAS</sub>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"modulo_list_claro\" align=\"center\">";
		$this->salida.="			<td valign=\"top\" colspan=\"2\" id=\"capa_contrans\" style=\"display:none\">";
		$this->salida.="				<script>";
		$this->salida.="				xajax_ConfirmacionTransferencias('".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida.="				</script>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";*/
		$this->salida.="	</table>";
		
		$actionMenu=ModuloGetURL('app','Solicitud_Medicamentos_PorBodega','user','Principal');
		$this->salida .= "<br><form name=\"formavolver\" action=\"$actionMenu\" method=\"post\">";
    $this->salida .= "	<table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "		<tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
		$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='d2Contents' class=\"d2Content\">\n";
		$this->salida .= "	</div>\n"; 
		$this->salida .= "</div>\n";
		
		$this->salida .= "<div id=\"aqui\"></div>\n";
		
		$this->salida .= "<script>\n";
		$this->salida .= "	var hiZ=2;\n";
		$this->salida .= "	var datos_med='';\n";
		
		$this->salida .= "	var capas1 = new Array('capa_sol','capa_des','capa_dev','capa_exi');\n";
		
		$this->salida .= "	function Recargar(param)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		switch(param)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			case 'F1':\n";
		$this->salida.="					xajax_Solicitudes('".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida .= "			break;\n";
		$this->salida .= "			case 'F2':\n";
		$this->salida.="					xajax_Despachos('".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida .= "			break;\n";
		$this->salida .= "			case 'F3':\n";
		$this->salida.="					xajax_Devoluciones('".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida .= "			break;\n";
		$this->salida .= "			case 'F4':\n";
		$this->salida.="					xajax_ExistenciasMinimas('1','".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida .= "			break;\n";
		/*$this->salida .= "			case 'F5':\n";
		$this->salida.="					xajax_ConfirmacionTransferencias('".$_REQUEST['PermisoSolMed']['empresa_id']."','".$_REQUEST['PermisoSolMed']['centro_utilidad']."','".$_REQUEST['PermisoSolMed']['bodega']."');";
		$this->salida .= "			break;\n";*/
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Busqueda(pag,emp,centro,bod)\n";
		$this->salida .= "	{\n";
		$this->salida.="				xajax_ExistenciasMinimas(pag,emp,centro,bod);\n";
		$this->salida .= "	}\n";

		$this->salida .= "	function showhide(Seccion)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		for(i=0; i<capas1.length; i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			e = xGetElementById(capas1[i]);\n";
		$this->salida .= "			if(capas1[i] != Seccion)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				e.style.display = \"none\";\n";
		$this->salida .= "			}\n";
		$this->salida .= "			else\n";
		$this->salida .= "			{\n";
		$this->salida .= "				if(e.style.display == \"none\")\n";
		$this->salida .= "				{\n";
		$this->salida .= "					e.style.display = \"\";\n";
		$this->salida .= "				}\n";
		$this->salida .= "				else \n";
		$this->salida .= "				{\n";
		$this->salida .= "					e.style.display = \"none\";\n";
		$this->salida .= "				}\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function FuncionDetalleInd(codigo,sw,ing,est,nom_est,fecha,usu,nom_pac,tpd,pd,ca,dpid,dpto,estado)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		if(sw==0)\n";
		$this->salida .= "			xajax_DetalleSolicitudInd(codigo,sw,ing,est,nom_est,fecha,usu,nom_pac,tpd,pd,ca,dpid,dpto,estado);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function FuncionDetalleDev(codigo,ing,est,nom_est,fecha,usu,nom_pac,tpd,pd,cama,pieza,dpid,dpto,estado,observ,param)\n";
		$this->salida .= "	{\n";
		$this->salida .= "			xajax_DetalleSolicitudDev(codigo,ing,est,nom_est,fecha,usu,nom_pac,tpd,pd,cama,pieza,dpid,dpto,estado,observ,param);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function DetConfirmarTrans(codigo,bodega,fecha,centro)\n";
		$this->salida .= "	{\n";
		$this->salida .= "			xajax_DetConfirmarTrans(codigo,bodega,fecha,centro);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function AceptarDev(EstacionId,NombreEstacion,Documento,Fecha,Ingreso,observaciones,cont)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	 	var datos=new Array();\n";
		$this->salida .= "	 	var ban=0;\n";
		$this->salida .= "		for(var i=0;i<cont;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(xGetElementById('checkboxDevol'+i).checked)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				datos[datos.length]=xGetElementById('checkboxDevol'+i).value;\n";
		$this->salida .= "				ban=1;\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(ban==1)\n";
		$this->salida .= "			xajax_RealizarDevolucion(datos,EstacionId,NombreEstacion,Documento,Fecha,Ingreso,observaciones);\n";
		$this->salida .= "		else\n";
		$this->salida .= "			document.getElementById('error').innerHTML='<center>DEBE SELECCIONAR UN MEDICAMENTO/INSUMO</center>';\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function CancelarPro(EstacionId,NombreEstacion,Documento,Fecha,Ingreso,observaciones,tpd,td,nombre,cama,pieza,dpto,desdpto,estado,param,usu,cont)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	 	var datos=new Array();\n";
		$this->salida .= "	 	var ban=0;\n";
		$this->salida .= "		for(var i=0;i<cont;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(xGetElementById('checkboxDevol'+i).checked)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				datos[datos.length]=xGetElementById('checkboxDevol'+i).value;\n";
		$this->salida .= "				ban=1;\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(ban==1)\n";
		$this->salida .= "			xajax_CancelarProDevolucion(datos,EstacionId,NombreEstacion,Fecha,Documento,Ingreso,observaciones,tpd,td,nombre,cama,pieza,dpto,desdpto,estado,param,usu);\n";
		$this->salida .= "		else\n";
		$this->salida .= "			document.getElementById('error').innerHTML='<center>DEBE SELECCIONAR UN MEDICAMENTO/INSUMO</center>';\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Iniciar()\n";
		$this->salida .= "	{\n";
		$this->salida .= "	 	contenedor = 'd2Container';\n";
		$this->salida .= "		titulo = 'titulo';\n";
		$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
		$this->salida .= "		document.getElementById(titulo).innerHTML = '';\n";
		$this->salida .= "		document.getElementById('d2Contents').innerHTML = '';\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+10);\n";
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
		
		$this->salida .= "	function AbrirVentanaImpresion(url)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		window.open(url,'SOLICITUDES DE MEDICAMENTOS E INSUMOS','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
		$this->salida .= "	}\n";
		
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
		
		$this->salida .= "	function ConfirmacionDespacho(tipo,sol,estacion,nomestacion,ingreso,fecha,concepto,usu,datos,cont)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var cad=new Array();\n";
		$this->salida .= "		var cadena=datos.split('||');\n";
		$this->salida .= "		var des='';\n";
		$this->salida .= "		var ban=0;\n";
		$this->salida .= "		for(var i=0;i<cont;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(xGetElementById('cancela'+i).checked)\n";
		$this->salida .= "				des=xGetElementById('cancela'+i).value;\n";
		$this->salida .= "			else\n";
		$this->salida .= "				des=xGetElementById('pendiente'+i).value;\n";
		$this->salida .= "			cad[cad.length]=cadena[i]+'__'+des+'__'+xGetElementById('motivo'+i).value+'__'+xGetElementById('observa'+i).value;\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(ban==0)\n";
		$this->salida .= "			xajax_ConfirmarDespacho(tipo,sol,estacion,nomestacion,ingreso,fecha,concepto,usu,cad);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function DespachoDpto(departamento,desc_dpto)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_DespacharDpto(departamento,desc_dpto);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function DevolucionDpto(departamento,desc_dpto)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_DevolucionDpto(departamento,desc_dpto);\n";
		$this->salida .= "	}\n";

		$this->salida .= "	function DespacharNC(cont,sw,tipo,codigo,ing,est,nom_est,fecha,usu,nom_pac,tpd,pd,ca,dpid,dpto,estado)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var datos=new Array();\n";
		$this->salida .= "		var ban=0;\n";
		$this->salida .= "		for(var i=0;i<cont;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(xGetElementById('CheckDespachar'+i).checked)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				if(xGetElementById('CantDespachar'+i).value=='')\n";
		$this->salida .= "				{\n";
		$this->salida .= "					document.getElementById('error').innerHTML='<center>DEBE INGRESAR UNA CANTIDAD</center>';\n";
		$this->salida .= "					ban=2;\n";
		$this->salida .= "					break;\n";
		$this->salida .= "				}\n";
		$this->salida .= "				if(parseFloat(xGetElementById('CantExistente'+i).value) <= 0)\n";
		$this->salida .= "				{\n";
		$this->salida .= "					document.getElementById('error').innerHTML='<center>NO HAY EXISTENCIAS</center>';\n";
		$this->salida .= "					ban=2;\n";
		$this->salida .= "					break;\n";
		$this->salida .= "				}\n";
		$this->salida .= "				datos[datos.length]=xGetElementById('CheckDespachar'+i).value+'__'+xGetElementById('CantDespachar'+i).value+'__'+xGetElementById('SelectMedicamentos'+i).value;\n";
		$this->salida .= "				ban=1;\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(ban==0)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			document.getElementById('error').innerHTML='<center>DEBE SELECCIONAR UN MEDICAMENTO/INSUMO</center>';\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(ban==1)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			xajax_Despachar(datos,sw,tipo,codigo,ing,est,nom_est,fecha,usu,nom_pac,tpd,pd,ca,dpid,dpto,estado);\n";
		$this->salida .= "			document.getElementById('error').innerHTML='';\n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function DespacharDptoNC(cont,dpid,dpto,concep)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var datos=new Array();\n";
		$this->salida .= "		var datosCan=new Array();\n";
		$this->salida .= "		var datosMed=new Array();\n";
		$this->salida .= "		var ban=0;\n";
		$this->salida .= "		for(var i=0;i<cont;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(xGetElementById('CheckDespachar'+i).checked)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				if(xGetElementById('CantDespachar'+i).value=='')\n";
		$this->salida .= "				{\n";
		$this->salida .= "					document.getElementById('error').innerHTML='<center>DEBE INGRESAR UNA CANTIDAD</center>';\n";
		$this->salida .= "					ban=2;\n";
		$this->salida .= "					break;\n";
		$this->salida .= "				}\n";
		$this->salida .= "				if(parseFloat(xGetElementById('CantExistente'+i).value) <= 0)\n";
		$this->salida .= "				{\n";
		$this->salida .= "					document.getElementById('error').innerHTML='<center>NO HAY EXISTENCIAS</center>';\n";
		$this->salida .= "					ban=2;\n";
		$this->salida .= "					break;\n";
		$this->salida .= "				}\n";
		$this->salida .= "				datos[datos.length]=xGetElementById('CheckDespachar'+i).value+'.-.'+xGetElementById('CantDespachar'+i).value+'.-.'+xGetElementById('SelectMedicamentos'+i).value;\n";
		$this->salida .= "				ban=1;\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(ban==1)\n";
		$this->salida .= "			xajax_DespacharDptoSC(datos,dpid,dpto,concep);\n";
		$this->salida .= "		if(ban==0)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			document.getElementById('error').innerHTML='<center>DEBE SELECCIONAR UN MEDICAMENTO/INSUMO</center>';\n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function ConfirmacionDespachoDpto(datos,depid,dpto,concep,cont)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var pendiente=new Array();\n";
		$this->salida .= "		var cancelar=new Array();\n";
		$this->salida .= "		var motivo=new Array();\n";
		$this->salida .= "		var observa=new Array();\n";
		$this->salida .= "		for(var i=0;i<cont;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(xGetElementById('cancelar'+i).checked)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				cancelar[cancelar.length]=xGetElementById('cancelar'+i).value;\n";
		$this->salida .= "				motivo[motivo.length]=xGetElementById('motivo_can'+i).value;\n";
		$this->salida .= "				observa[observa.length]=xGetElementById('observaciones'+i).value;\n";
		$this->salida .= "			}\n";
		$this->salida .= "			else{\n";
		$this->salida .= "				pendiente[pendiente.length]=xGetElementById('pendiente'+i).value;\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		xajax_ConfirmarDespachoDpto(datos,pendiente,cancelar,motivo,observa,depid,dpto,concep);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function ValidarCantidad(campo,valor,cant_sol)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		document.getElementById(campo).style.background='';\n";
		$this->salida .= "		document.getElementById('error').innerHTML='';\n";
		$this->salida .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
		$this->salida .= "		{\n";
		$this->salida .= "			document.getElementById(campo).value='';\n";
		$this->salida .= "			document.getElementById(campo).style.background='#ff9595';\n";
		$this->salida .= "			document.getElementById('error').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function ConfirmarDevolucionDptoSI(departamento,concepto,cont)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var datosCheck=new Array();\n";
		$this->salida .= "		var ban=1;\n";
		$this->salida .= "		for(var i=0;i<cont;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(xGetElementById('checkboxDevol'+i).checked)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				datosCheck[datosCheck.length]=xGetElementById('checkboxDevol'+i).value;\n";
		$this->salida .= "				ban=0;\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		if(ban==0)\n";
		$this->salida .= "			xajax_ConfirmarDevolucionDpto(datosCheck,departamento,concepto);\n";
		$this->salida .= "		else\n";
		$this->salida .= "			document.getElementById('error').innerHTML='<center>DEBE SELECCIONAR UN MEDICAMENTO / INSUMO</center>';\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function MostrarLotesPtosDevols(EstacionId,NombreEstacion,Documento,Ingreso,observaciones,codigo_producto,descripcion,cantidad,consecutivo,tipo_id_paciente,paciente_id,nombrepac,cama,pieza,parametro,departamento,fecha,usuarioetacion,descdpto,sw)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_MostrarLotesPtosDevols(EstacionId,NombreEstacion,Documento,Ingreso,observaciones,codigo_producto,descripcion,cantidad,consecutivo,tipo_id_paciente,paciente_id,nombrepac,cama,pieza,parametro,departamento,fecha,usuarioetacion,descdpto,sw);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "function LlamarCalendariofechaVencimiento()\n";
		$this->salida .= "{\n";
		$this->salida .= "	window.open('classes/calendariopropio/Calendario.php?forma=formaUno&campo=fechaVencimiento&separador=/','CALENDARIO_SIIS','width=450,height=250,resizable=no,status=no,scrollbars=yes');\n";
		$this->salida .= "}\n";
		
		$this->salida .= "function FuncionEliminar(fecha_ven,lote,CantidadLote,consecutivo,codigo_pro,InsLote,ElimLote)\n";
		$this->salida .= "{\n";
		$this->salida .= "	xajax_EliminarFechaVDevol(consecutivo,codigo_pro,CantidadLote,fecha_ven,lote,InsLote,ElimLote);";
		$this->salida .= "}\n";
		
		$this->salida .= "</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
  }
	
}//fin clase user
?>