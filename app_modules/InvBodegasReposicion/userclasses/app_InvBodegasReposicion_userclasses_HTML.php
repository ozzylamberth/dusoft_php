<?php

/**
* $Id: app_InvBodegasReposicion_userclasses_HTML.php,v 1.3 2007/07/10 13:47:32 luis Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
*
* Modulo para el Manejo de la reposicion de productos entre bodegas
*/

IncludeClass("ClaseHTML");
if(!IncludeClass("BodegasDocumentos"))
{
	$this->mensaje_error="ERROR: NO SE PUEDE INCLUIR DE LA CLASE [BodegasDocumentos]";
}
class app_InvBodegasReposicion_userclasses_HTML extends app_InvBodegasReposicion_user
{
	/**
	*	Constructor de la clase app_InvBodegasReposicion_userclasses_HTML
	*	El constructor de la clase app_InvBodegasReposicion_userclasses_HTML se encarga de llamar
	*	a la clase app_InvBodegasReposicion_user que se encarga del tratamiento
	* de la base de datos.
	*/

  function app_InvBodegasReposicion_user_HTML()
	{
		$this->salida='';
		$this->app_InvBodegasReposicion_user();
		return true;
	}
	
	function Principal()
	{
		if($this->PermisosUsuarios()==false)
		{
			return false;
		}
		return true;
	}
	
	function FrmBodegasReposicion()
	{
		$this->mensaje_error="";
		
		$OBJ=new BodegasDocumentos();
		if(!is_object($OBJ))
		{
			$this->mensaje_error.="ERROR: NO SE PUEDE INSTANCIAR EL OBJETO DE LA CLASE";
		}
	
		if($_REQUEST['PermisoReposicion'])
		{
			$_SESSION['BodegasReposicion']['empresa_id']=$_REQUEST['PermisoReposicion']['empresa_id'];
			$_SESSION['BodegasReposicion']['empresa_desc']=$_REQUEST['PermisoReposicion']['descripcion1'];
			$_SESSION['BodegasReposicion']['centro_id']=$_REQUEST['PermisoReposicion']['centro_utilidad'];
			$_SESSION['BodegasReposicion']['centro_desc']=$_REQUEST['PermisoReposicion']['descripcion2'];
			$_SESSION['BodegasReposicion']['bodega']=$_REQUEST['PermisoReposicion']['bodega'];
			$_SESSION['BodegasReposicion']['bodega_desc']=$_REQUEST['PermisoReposicion']['descripcion3'];
		}
		
		$this->salida .= ThemeAbrirTabla('LISTADO DE BODEGAS PARA REPOSICION');
		
		$this->salida.="	<table border=\"0\" width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$this->salida.="		<tr align=\"center\">";
    $this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				EMPRESA";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"25%\">";
		$this->salida.="				".$_SESSION['BodegasReposicion']['empresa_desc']."";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				CENTRO_UTILIDAD";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"20%\">";
		$this->salida.="				".$_SESSION['BodegasReposicion']['centro_desc']."";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				BODEGA";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"25%\">";
		$this->salida.="				".$_SESSION['BodegasReposicion']['bodega_desc']."";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table><br>";
		
		$this->salida.="	<table border=\"0\" width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$this->salida.="		<tr align=\"center\">";
    $this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				BODEGA";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"70%\">";
		$this->salida.="				DESCRIPCION";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				REPORTE";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				PRODUCTOS";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		
		$bodegas=$this->GetBodegasReposicion($_SESSION['BodegasReposicion']['bodega']);
		$i=0;
		foreach($bodegas as $valor)
		{
			if($i%2==0)
			{
				$estilo="modulo_list_oscuro";
				$background = "#CCCCCC";
			}
			else
			{
				$estilo="modulo_list_claro";
				$background = "#DDDDDD";
			}
			
			$direccionR="app_modules/InvBodegasReposicion/reports/html/BodegasReposiciones.report.php?bodega=".$valor['bodega_destino']."&descripcion=".$valor['descripcion'];
				
			$this->salida.="		<tr align=\"left\" class=\"$estilo\" onmouseout=\"mOut(this,'$background');\" onmouseover=\"mOvr(this,'#FFFFFF');\">";
			$this->salida.="			<td>";
			$this->salida.="				".$valor['bodega_destino']."";
			$this->salida.="			</td>";
			$this->salida.="			<td>";
			$this->salida.="				".$valor['descripcion']."";
			$this->salida.="			</td>";
			$this->salida.="			<td align=\"center\">";
			$this->salida.="				<a href=\"javascript:AbrirVentanaImpresion('$direccionR','".str_replace("'","",$valor['descripcion'])."');\"><img src=\"".GetThemePath()."/images/imprimir.png\" title=\"IMPRIMIR REPORTE DE REPOSICIONES [".$valor['descripcion']."]\"  border=\"0\" width=\"20\" height=\"20\"></a>";
			$this->salida.="			</td>";
			$this->salida.="			<td align=\"center\">";
			$vars=$OBJ->GetDocumentosReposicion($valor['empresa_id'],$valor['centro_utilidad_destino'],$valor['bodega_destino']);
			if($vars===false)
			{
				$this->mensaje_error.="ERROR : CLASE ".$OBJ->error." ".$OBJ->mensajeDeError." - ".$valor['descripcion'];
			}
			elseif($vars)
			{
				$accion=ModuloGetURL("app","InvBodegasReposicion","user","FrmReposicionesProductos",array("bodega"=>$valor['bodega_destino'],"descripcion"=>$valor['descripcion']));
				$this->salida.="				<a href=\"$accion\"><img src=\"".GetThemePath()."/images/pconsultar.png\" title=\" REPOSICIONES [".$valor['descripcion']."]\" border=\"0\" width=\"20\" height=\"20\"></a>";
			}
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$i++;
		}
		
		$this->salida.="	</table><br>";
		
		$accionV= ModuloGetURL('app','InvBodegasReposicion','user','Principal');
		
		$this->salida .= "<form action=\"$accionV\" name=\"formaV\" method=\"post\">";
		$this->salida .= "	<br><table width=\"100%\" align=\"center\">";
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "			<td><input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "	<script>";
		
		$this->salida .= "	function mOvr(src,clrOver)";
		$this->salida .= "	{";
		$this->salida .= "		src.style.background = clrOver;";
		$this->salida .= "	}";
		$this->salida .= "	function mOut(src,clrIn)";
		$this->salida .= "	{";
		$this->salida .= "		src.style.background = clrIn;";
		$this->salida .= "	}";
		
		$this->salida .= "	function AbrirVentanaImpresion(url,bodega)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		window.open(url,'REPORTE REPOSICIONES BODEGA['+bodega+']','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	</script>";

		$this->salida.="	<br><table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida.="		<tr align=\"center\">";
    $this->salida.="			<td width=\"100%\" class=\"label_error\">";
		$this->salida.="				".$this->mensaje_error."";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table>";
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}
	
	function FrmReposicionesProductos()
	{
		$this->IncludeJS("RemoteScripting");
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");
		
		$this->salida .= ThemeAbrirTabla('LISTADO PRODUCTOS PARA REPOSICION');
		
		SessionDelVar("SelectPro");
		SessionDelVar("CantReponer");
		
		$datosProductos=$this->GetProductosBodegasReposicion($_REQUEST['bodega']);
		
		$this->salida.="	<table border=\"0\" width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$this->salida.="		<tr align=\"center\">";
    $this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				EMPRESA";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"25%\">";
		$this->salida.="				".$_SESSION['BodegasReposicion']['empresa_desc']."";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				CENTRO_UTILIDAD";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"20%\">";
		$this->salida.="				".$_SESSION['BodegasReposicion']['centro_desc']."";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				BODEGA";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"25%\">";
		$this->salida.="				".$_SESSION['BodegasReposicion']['bodega_desc']."";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table><br>";
		
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida.="	<tr class=\"modulo_table_list_title\">";
		$this->salida.="		<td width=\"40%\">";
		$this->salida.="			BODEGA ORIGEN";
		$this->salida.="		</td>";
		$this->salida.="		<td width=\"40%\">";
		$this->salida.="			BODEGA DESTINO";
		$this->salida.="		</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"modulo_list_claro\">";
		$this->salida.="		<td>";
		$this->salida.="			".$_SESSION['BodegasReposicion']['bodega']." - ".$_SESSION['BodegasReposicion']['bodega_desc'];
		$this->salida.="		</td>";
		$this->salida.="		<td>";
		$this->salida.="			".$_REQUEST['bodega']." - ".$_REQUEST['descripcion']."";
		$this->salida.="		</td>";
		$this->salida.="	</tr>";
		$this->salida.="</table>";
		
		$accion=ModuloGetURL("app","InvBodegasReposicion","user","FrmConfirmaReposicion",array("bodega"=>$_REQUEST['bodega'],"descripcion"=>$_REQUEST['descripcion']));
		SessionSetVar("datosPro",$datosProductos);
		$this->salida .= "	<form name=\"forma_repo\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	<br><table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "		<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida .= "			<td width=\"30%\" colspan=\"3\">&nbsp;</td>";
		$this->salida .= "			<td width=\"20%\" colspan=\"2\">".$_SESSION['BodegasReposicion']['bodega_desc']."</td>";
		$this->salida .= "			<td width=\"30%\" colspan=\"3\">".$_REQUEST['descripcion']."</td>";
		$this->salida .= "			<td width=\"30%\" colspan=\"3\">&nbsp;</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida .= "			<td width=\"10%\">CODIGO PRODUCTO</td>";
		$this->salida .= "			<td width=\"10%\">DESCRIPCION</td>";
		$this->salida .= "			<td width=\"10%\">UNIDAD</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA MINIMA</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA MINIMA</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA MAXIMA</td>";
		$this->salida .= "			<td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "			<td width=\"10%\">CANTIDAD A REPONER</td>";
		$this->salida .= "			<td width=\"10%\"><input type=\"checkbox\" name=\"todos\" value=\"1\" onclick=\"SeleccionarTodos(this.form,this.checked);\"></td>";
		$this->salida .= "		</tr>";
		if($datosProductos)
		{
			$i=0;
			foreach($datosProductos as $valor)
			{
				if($i%2==0)
				{
					$estilo="modulo_list_oscuro";
					$background = "#CCCCCC";
				}
				else
				{
					$estilo="modulo_list_claro";
					$background = "#DDDDDD";
				}
	
				$this->salida .= "		<tr class=\"$estilo\" onmouseout=\"mOut(this,'$background');\" onmouseover=\"mOvr(this,'#FFFFFF');\">";
				$this->salida .= "			<td>".$valor['codigo_producto']."</td>";
				$this->salida .= "			<td>".$valor['descripcion']."</td>";
				$this->salida .= "			<td>".$valor['descripcion_unidad']."</td>";
				$this->salida .= "			<td>".$valor['existencia_o']."</td>";
				$this->salida .= "			<td>".$valor['existencia_minima_o']."</td>";
				$this->salida .= "			<td>".$valor['existencia_d']."</td>";
				$this->salida .= "			<td>".$valor['existencia_minima_d']."</td>";
				$this->salida .= "			<td>".$valor['existencia_maxima_d']."</td>";
				$this->salida .= "			<td align=\"right\">".FormatoValor($valor['pedido'])."</td>";
				
				$cantidad=$valor['pedido'];
				if($cantidad > ($valor['existencia_o']-$valor['existencia_minima_o']))
					$cantidad=$valor['existencia_o']-$valor['existencia_minima_o'];
	
				if($valor['existencia_o'] > 0)
				{
					$this->salida .= "			<td align=\"center\"><input type=\"text\" id=\"CantReponer$i\" name=\"CantReponer[".$valor['codigo_producto']."]\" size=\"10\" class=\"input-text\" style=\"text-align: right\" value=\"".$cantidad."\" onkeyup=\"ValidarCantidad('CantReponer$i',xGetElementById('CantReponer$i').value,'".$valor['pedido']."','".$valor['existencia_o']."')\"></td>";
					$this->salida .= "			<td><input type=\"checkbox\" id=\"SelectPro$i\"  name=\"SelectPro[]\" value=\"".$valor['codigo_producto'].".-.".$valor['porc_iva'].".-.".$valor['costo']."\"></td>";
				}
				else
				{
					$this->salida .= "			<td>&nbsp;</td>";
					$this->salida .= "			<td>&nbsp;</td>";
				}
				$this->salida .= "		</tr>";
				$i++;
			}
			$this->salida .= "			<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .= "				<td colspan=\"11\" align=\"right\"><input type=\"submit\" name=\"continuar\" value=\"CONTINUAR\" class=\"input-submit\"></td>";
			$this->salida .= "			</tr>";
		}
		else
		{
			$this->salida .= "			<tr class=\"modulo_list_claro\">";
			$this->salida .= "				<td colspan=\"11\" align=\"center\" class=\"label_error\">NO EXISTEN PRODUCTOS DE LA BODEGA PARA ".$_REQUEST['descripcion'] ."REPOSICION</td>";
			$this->salida .= "			</tr>";
		}
		$this->salida .= "		</table>";
		$this->salida .= "	</form>";
		
		$accionV= ModuloGetURL('app','InvBodegasReposicion','user','FrmBodegasReposicion');
		
		$this->salida .= "<form action=\"$accionV\" name=\"formaV\" method=\"post\">";
		$this->salida .= "	<br><table width=\"100%\" align=\"center\">";
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "			<td><input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "  <script>\n";
		
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
		
		$this->salida .= "	function ValidarCantidad(campo,valor,cant_sol,existencia)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		document.getElementById(campo).style.background='';\n";
		//$this->salida .= "		document.getElementById('error').innerHTML='';\n";
		$this->salida .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='' || parseFloat(valor) > parseFloat(existencia))\n";
		$this->salida .= "		{\n";
		$this->salida .= "			document.getElementById(campo).value='';\n";
		$this->salida .= "			document.getElementById(campo).style.background='#ff9595';\n";
		//$this->salida .= "			document.getElementById('error').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function mOvr(src,clrOver)";
		$this->salida .= "	{";
		$this->salida .= "		src.style.background = clrOver;";
		$this->salida .= "	}";
		$this->salida .= "	function mOut(src,clrIn)";
		$this->salida .= "	{";
		$this->salida .= "		src.style.background = clrIn;";
		$this->salida .= "	}";
		$this->salida .= "  </script>\n";
		
		$this->salida.= ThemeCerrarTabla();
		return true;
	}
	
	function FrmConfirmaReposicion($datos)
	{
		$this->salida .= ThemeAbrirTabla('CONFIRMACION - LISTADO PRODUCTOS PARA REPOSICION');
		if(is_array($datos))
			$_REQUEST=$datos;
		
		$datosPro=SessionGetVar("datosPro");
		$codigosPro=$_REQUEST['SelectPro'];
		$cantid=$_REQUEST['CantReponer'];
		
		$this->salida.="	<table border=\"0\" width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$this->salida.="		<tr align=\"center\">";
    $this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				EMPRESA";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"25%\">";
		$this->salida.="				".$_SESSION['BodegasReposicion']['empresa_desc']."";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				CENTRO_UTILIDAD";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"20%\">";
		$this->salida.="				".$_SESSION['BodegasReposicion']['centro_desc']."";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\">";
		$this->salida.="				BODEGA";
		$this->salida.="			</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"25%\">";
		$this->salida.="				".$_SESSION['BodegasReposicion']['bodega_desc']."";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table><br>";
		
		$this->salida.="		<table align=\"center\" border=\"0\">";
		$this->salida.="			<tr>";
		$this->salida.="				<td class=\"label_error\">".$this->FrmError['MensajeError']."</td>";
		$this->salida.="			</tr>";
		$this->salida.="		</table>";
		
		SessionSetVar("SelectPro",$codigosPro);
		SessionSetVar("CantReponer",$cantid);
		
		$accion = ModuloGetURL('app','InvBodegasReposicion','user','CrearDocumento',array("bodega"=>$_REQUEST['bodega'],"descripcion"=>$_REQUEST['descripcion']));
		
		$this->salida .= "	<form name=\"forma_repo\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	<br><table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "		<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida .= "			<td width=\"30%\" colspan=\"3\">&nbsp;</td>";
		$this->salida .= "			<td width=\"30%\" colspan=\"2\">".$_SESSION['BodegasReposicion']['bodega_desc']."</td>";
		$this->salida .= "			<td width=\"30%\" colspan=\"3\">".$_REQUEST['descripcion']."</td>";
		$this->salida .= "			<td width=\"10%\" colspan=\"1\">&nbsp;</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida .= "			<td width=\"10%\">CODIGO PRODUCTO</td>";
		$this->salida .= "			<td width=\"10%\">DESCRIPCION</td>";
		$this->salida .= "			<td width=\"10%\">UNIDAD</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA MINIMA</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA MINIMA</td>";
		$this->salida .= "			<td width=\"10%\">EXISTENCIA MAXIMA</td>";
		$this->salida .= "			<td width=\"10%\">CANTIDAD A REPONER</td>";
		$this->salida .= "		</tr>";
		$i=0;
		
		if($codigosPro)
		{
			$datosPro=$this->GetProductosBodegasReposicion($_REQUEST['bodega']);
			foreach($datosPro as $valor1)
			{
				foreach($codigosPro as $valor2)
				{
					list($codigoProducto,$gravamen,$costo)=explode(".-.",$valor2);
					
					if($valor1['codigo_producto']==$codigoProducto)
					{
						
						if($i%2==0)
						{
							$estilo="modulo_list_oscuro";
						}
						else
						{
							$estilo="modulo_list_claro";
						}
						$a="";
						if($this->vector[$codigoProducto])
							$a="class=\"label_error\"";
						
						$existencia=$this->GetExistenciasBodegas($_REQUEST['bodega'],$valor1['codigo_producto']);
						
						if($existencia['existencia_real'] < $cantid[$valor1['codigo_producto']])
							$a="class=\"label_error\"";
						
						$this->salida .= "		<tr class=\"$estilo\">";
						$this->salida .= "			<td $a>".$valor1['codigo_producto']."</td>";
						$this->salida .= "			<td $a>".$valor1['descripcion']."</td>";
						$this->salida .= "			<td $a>".$valor1['descripcion_unidad']."</td>";
						$this->salida .= "			<td $a>".$valor1['existencia_o']."</td>";
						$this->salida .= "			<td $a>".$valor1['existencia_minima_o']."</td>";
						$this->salida .= "			<td $a>".$valor1['existencia_d']."</td>";
						$this->salida .= "			<td $a>".$valor1['existencia_minima_d']."</td>";
						$this->salida .= "			<td $a>".$valor1['existencia_maxima_d']."</td>";
						$this->salida .= "			<td align=\"right\" $a>".FormatoValor($cantid[$valor1['codigo_producto']])."</td>";
						$this->salida .= "		</tr>";
						$i++;
					}
				}
			}
		}
		else
		{
			$this->salida .= "		<tr class=\"modulo_list_claro\">";
			$this->salida .= "			<td class=\"label_error\" colspan=\"9\" align=\"center\">NO SELECCIONO NINGUN PRODUCTO PARA REALIZAR LA REPOSICION</td>";
			$this->salida .= "		</tr>";
		}
		$this->salida .= "		</table>";
		
		
		$OBJ=new BodegasDocumentos();
		if(!is_object($OBJ))
		{
			$this->mensaje_error.="ERROR: NO SE PUEDE INSTANCIAR EL OBJETO DE LA CLASE";
		}
		
		$vars=$OBJ->GetDocumentosReposicion($_SESSION['BodegasReposicion']['empresa_id'],$_SESSION['BodegasReposicion']['centro_id'],$_SESSION['BodegasReposicion']['bodega']);
		
		//$vars=$OBJ->GetDocumentosTMP_BodegaUsuario($_SESSION['BodegasReposicion']['empresa_id'],$_SESSION['BodegasReposicion']['centro_id'],$_SESSION['BodegasReposicion']['bodega']);
		if($vars===false)
		{
			$this->mensaje_error.="ERROR : CLASE ".$OBJ->error." ".$OBJ->mensajeDeError." - ".$valor['descripcion'];
		}
		
		foreach($vars as $key=>$valor)
		{
			foreach($valor as $valor1)
			{
				$bodegas_doc_id=$valor1['bodegas_doc_id'];
			}
		}
		$this->salida .= "	<br><table width=\"30%\" align=\"center\">";
		$this->salida .= "		<tr align=\"center\" class=\"\">";
		$this->salida .= "			<td class=\"label\">";
		$this->salida .= "			<fieldset>";
		$this->salida .= "				<legend>OBSERVACION</legend>";
		$this->salida .= "				<textarea name=\"observacion\" id=\"observacion\" cols=\"50\" rows=\"4\" class=\"textarea\"></textarea>";
		$this->salida .= "			</fieldset><br>";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "	<input type=\"hidden\" name=\"bodega_doc_id\" id=\"bodega_doc_id\" value=\"".$bodegas_doc_id."\">";
		$this->salida .= "	<input type=\"hidden\" name=\"tipo_doc_bodega_id\" id=\"tipo_doc_bodega_id\" value=\"T001\">";
		
		/*if($vars)
		{
			$this->salida .= "	<br><table width=\"50%\" align=\"center\">";
			$this->salida .= "		<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .= "			<td>DOCS TMP</td>";
			$this->salida .= "			<td>ACCION</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "			<td class=\"modulo_list_oscuro\">";
			$this->salida .= "				<select name=\"docs_tmp\" id=\"docs_tmp\" class=\"select\">";
			if(sizeof($vars)>1)
				$this->salida .= "			<option value=\"\">---SELECCIONE---</option>";	
			
			foreach($vars as $key=>$valor)
			{
				foreach($valor as $key1=>$valor1)
				{
					$this->salida .= "			<option value=\"".$valor1['doc_tmp_id']."\">".$valor1['doc_tmp_id']." - ".$valor1['tipo_movimiento']." - ".$valor1['tipo_doc_bodega_id']." - ".$valor1['descripcion']."</option>";	
				}
			}
			
			$this->salida .= "				</select>";
			$this->salida .= "			</td>";
			$this->salida .= "			<td><input type=\"submit\" name=\"crearDoc\" value=\"CREAR DOCUMENTO\" class=\"input-submit\"></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "	</table>";
		}
		else
		{*/
			$this->salida .= "	<table width=\"50%\" align=\"center\">";
			$this->salida .= "		<tr align=\"center\">";
			$this->salida .= "			<td><input type=\"submit\" name=\"crearDoc\" value=\"CREAR DOCUMENTO\" class=\"input-submit\"></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "	</table>";
		//}
		
		$this->salida .= "	</form>";
		$accionV= ModuloGetURL('app','InvBodegasReposicion','user','FrmReposicionesProductos',array("bodega"=>$_REQUEST['bodega'],"descripcion"=>$_REQUEST['descripcion']));
		
		$this->salida .= "<form action=\"$accionV\" name=\"formaV\" method=\"post\">";
		$this->salida .= "	<br><table width=\"50%\" align=\"center\">";
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "			<td><input type=\"submit\" name=\"cancelar\" value=\"CANCELAR\" class=\"input-submit\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}
	
	function FormaMensaje($infoDoc)
	{
		$this->salida.= ThemeAbrirTabla();
		
		$this->salida .= "	<br><table width=\"80%\" align=\"center\">";
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "			<td class=\"label_error\" colspan=\"5\">".$this->FrmError['MensajeError']."</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida .= "			<td width=\"10%\">DOC ID</td>";
		$this->salida .= "			<td width=\"20%\">PREFIJO</td>";
		$this->salida .= "			<td width=\"40%\">OBSERVACION</td>";
		$this->salida .= "			<td width=\"20%\">FECHA</td>";
		$this->salida .= "			<td width=\"10%\">ACCION</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr align=\"center\" class=\"modulo_list_claro\">";
		$this->salida .= "			<td>".$infoDoc['documento_id']."</td>";
		$this->salida .= "			<td>".$infoDoc['prefijo']." - ".$infoDoc['numero']."</td>";
		$this->salida .= "			<td>".$infoDoc['observacion']."</td>";
		$this->salida .= "			<td>".substr($infoDoc['fecha_registro'],0,10)."</td>";
		$this->salida .= "			<td align=\"center\">\n";
		$direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I001/imprimir/imprimir_docI001.php";
		$javas = "javascript:Imprimir('$direccion','".$_SESSION['BodegasReposicion']['empresa_id']."','".$infoDoc['prefijo']."','".$infoDoc['numero']."');";
		$this->salida .= "				<a title='IMPRIMIR DOCUMENTO' href=\"".$javas."\">\n";
		$this->salida .= "					<sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
		$this->salida .= "				</a>\n";
		$this->salida .= "			</td>\n";
		$this->salida .= "		</tr>";
		
		$accionAc= ModuloGetURL('app','InvBodegasReposicion','user','FrmBodegasReposicion');
		
		$this->salida .= "		<form name=\"formulario\" action=\"$accionAc\" method=\"post\">";
		$this->salida .= "			<tr align=\"center\">";
		$this->salida .= "				<td colspan=\"5\"><input type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\" class=\"input-submit\"></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "		</form>";
		$this->salida .= "	</table>";
		
		$this->salida .= "	<script>";
		$this->salida .= "	function Imprimir(direccion,empresa_id,prefijo,numero)";
		$this->salida .= "	{";
		$this->salida .= "		var url=direccion+\"?empresa_id=\"+empresa_id+\"&prefijo=\"+prefijo+\"&numero=\"+numero;";
		$this->salida .= "		window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');";
		$this->salida .= "	}";
		$this->salida .= "	</script>";
		
		$this->salida.= ThemeCerrarTabla();
		return true;
	}
}
?>