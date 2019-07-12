<?php

/**
 * $Id: app_OrdenesdePagos_userclasses_HTML.php,v 1.3 2007/09/10 15:13:58 jgomez Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Inventario del Sistema
 */

/**
*Contiene los metodos visuales para realizar la relacion de voucher de honorarios medicos con las facturas de los profesionales
*/

IncludeClass("ClaseHTML");
class app_OrdenesdePagos_userclasses_HTML extends app_OrdenesdePagos_user
{
	/**
	*Constructor de la clase app_Voucher_FacturasProfesionales_user_HTML
	*El constructor de la clase app_Voucher_FacturasProfesionales_user_HTML se encarga de llamar
	*a la clase app_Voucher_FacturasProfesionales_user que se encarga del tratamiento
	* de la logica del programa.
	*/

  function app_OrdenesdePagos_userclasses_HTML()
	{
		$this->salida='';
		$this->app_OrdenesdePagos_user();
		return true;
	}
  
	function SetStyle($campo){
    if($this->frmError[$campo] || $campo=="MensajeError"){
            if ($campo=="MensajeError"){
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
            }
            return ("label_error");
    }
    return ("label");
  }
	
	/**
	* Function que muestra al usuario la diferentes opciones de empresas en las que puede trabajar	
	* @return boolean
	*/
	function FrmLogueoEmpresa()
	{
		$Empresas=$this->LogueoEmpresa();
		
		if(sizeof($Empresas)>0)
		{
			if($this->cantidad_empresas!=1)
			{
				$url[0]='app';
				$url[1]='OrdenesdePagos';
				$url[2]='user';
				$url[3]='FrmMenu';
				$url[4]='Datos';
				$this->salida .= gui_theme_menu_acceso("SELECCION DE EMPRESA",$Empresas[0],$Empresas[1],$url,ModuloGetURL('system','Menu'));
			}
			else
			{
				$_SESSION['ORDEN_PAGO']['Empresa']=$Empresas[0];
				$_SESSION['ORDEN_PAGO']['NombreEmp']=$Empresas[1];  
				$this->FrmMenu();
			}
		}
		else
		{
      $mensaje = "EL USUARIO NO TIENE PERMISOS PARA ACCESAR A ESTE MODULO.";
			$titulo = "ORDENES DE PAGO";
			$boton = "";//REGRESAR
			$accion="";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		return true;
	}
  
  function Encabezado()
	{
		$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr><td class=\"modulo_table_list_title\" align=\"center\"><b>EMPRESA</b></td>";    
		$this->salida .= "	<tr><td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['ORDEN_PAGO']['NombreEmp']."</b></td>";    
		$this->salida .= "</table><BR>";
	
		return true;
	}
	
  /**
  * Function que muestra al menu con la opciones que puede seleccionar para trabajar
  * @return boolean
  */
	function FrmMenu()
	{
		$actionMenu=ModuloGetURL('system','Menu');
		
		if($_REQUEST['Datos'] OR $_SESSION['Datos'])
		{
			$_SESSION['ORDEN_PAGO']['Empresa']=$_REQUEST['Datos']['empresa_id'];
			$_SESSION['ORDEN_PAGO']['NombreEmp']=$_REQUEST['Datos']['descripcion1'];
			$_SESSION['Datos']=$_REQUEST['Datos'];
			$actionMenu=ModuloGetURL('app','OrdenesdePagos','user','main');
		}

		$action2=ModuloGetURL('app','OrdenesdePagos','user','FrmBuscadordeProfesional',array('op'=>2));
	
		$this->salida .= ThemeAbrirTabla('MENU');
		
		$this->Encabezado();
		
		$pmenu=$this->PermisoMenu();

		$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td class=\"modulo_table_list_title\" align=\"center\">MENU</td>";
		$this->salida .= "	</tr>";
		$i=1;
		$limite=2;
		foreach($pmenu as $key=>$v)
		{
			switch($i)
			{
				case 1:
					$metodo="FrmBuscadordeProfesional";
				breaK;
				case 2:
					$metodo="FrmBuscadordeProfesional";
				breaK;
				case 3:
					$metodo="FrmBuscadorFecha";
				breaK;
				case 4:
					$metodo="FrmOPCancelacion";
				breaK;
				
			}
			
			$action=ModuloGetURL('app','OrdenesdePagos','user',$metodo,array('op'=>$v['opcion_menu_id']));
	
			$this->salida .= "<tr>";
			$this->salida .= "	<td class=\"modulo_list_claro\" align=\"center\"><label class=\"label\"><a href=\"$action\">".$v['descripcion']."</a></label></td>";
			$this->salida .= "</tr>";
			
			$i++;
		}
		$this->salida .= "</table><br>";
		
		$this->salida .= "<form name=\"formavolver\" action=\"$actionMenu\" method=\"post\">";
    $this->salida .= "	<table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "		<tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
  }
  
  function FrmBuscadordeProfesional($_REQUEST)
	{
    
		$this->IncludeJS("RemoteScripting");
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");
		
		if($_REQUEST['op'])
			$_SESSION['op']=$_REQUEST['op'];
		
		$this->salida.= ThemeAbrirTabla('BUSCADOR');

		$this->salida.= "<script>\n";
		
		$this->salida.= " var prof;\n";
		$this->salida.= "function mOvr(src,clrOver){\n";
		$this->salida.= "  src.style.background = clrOver;\n";
		$this->salida.= "}\n";
		$this->salida.= "function mOut(src,clrIn){\n";
		$this->salida.= "  src.style.background = clrIn;\n";
		$this->salida.= "}\n";
		
		$this->salida.= "function LimpiarCampos(forma){\n";
		$this->salida.= "  	forma.nombreProf.value='';\n";
		$this->salida.= "		forma.uidProf.value='';\n";
		$this->salida.= "		forma.loginProf.value='';\n";
		$this->salida.= "		forma.IdProf.value='';\n";
		$this->salida.= "		forma.TipoIdProf.value='';\n";
		$this->salida.= "		xGetElementById('Tprof').style.display='none';\n";
		$this->salida.= "}";
		
		$this->salida.= "function LimpiarCampos1(){\n";
		$this->salida.= "  	document.forma_bus.fecha_ini.value='';\n";
		$this->salida.= "		document.forma_bus.fecha_fin.value='';\n";
		$this->salida.= "		document.forma_bus.plan.value='';\n";
		$this->salida.= "		document.forma_bus.radicado.checked=false;\n";
		$this->salida.= "		document.forma_bus.radicado.value=0;\n";
		$this->salida.= "		document.forma_bus.recaudo.checked=false;\n";
		$this->salida.= "		document.forma_bus.recaudo.value=0;\n";
		$this->salida.= "		document.forma_bus.Profesional.value='';\n";
		$this->salida.= "		xGetElementById('buscaPro').style.display='none';\n";
		$this->salida.= "		xGetElementById('Tprof').style.display='none';\n";
		$this->salida.= "  	document.getElementById('profesional2').innerHTML='';\n";
		$this->salida.= "  	document.getElementById('profesional1').innerHTML='';\n";
		$this->salida.= "}";
		
		$this->salida.= "function MostrarProf(){\n";
		$this->salida.= "  	xGetElementById('MostrarT1').style.display='';\n";
		$this->salida.= "  	xGetElementById('profesional1').style.display='';\n";
		$this->salida.= "  	xGetElementById('MostrarT2').style.display='';\n";
		$this->salida.= "  	xGetElementById('profesional2').style.display='';\n";
		$this->salida.= "		xGetElementById('Tprof').style.display='none';\n";
		$this->salida.= "		xGetElementById('buscaPro').style.display='none';\n";
		$this->salida.= "		document.getElementById('Profesional').value=prof;\n";
		$this->salida.= "		profesio=jsrsArrayFromString( prof, '||//' );\n";
		$this->salida.= "  	document.getElementById('profesional2').innerHTML=profesio[0]+' - '+profesio[1];\n";
		$this->salida.= "  	document.getElementById('profesional1').innerHTML=profesio[2];\n";
		$this->salida.= "}";
		
		$this->salida .= "	function MostrarSpan(Seccion)\n";
		$this->salida .= "	{ \n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"\";\n";
		$this->salida .= "	}\n";
		
		$this->salida.= "function RadioSelecionado(valor){\n";
		$this->salida.= "	prof=valor;\n";
		$this->salida.= "}\n";

		$this->salida.= "</script>\n";
		

		switch($_SESSION['op'])
		{
			case 1:
				$actionBus=ModuloGetURL('app','OrdenesdePagos','user','FrmEstadoCuentasProfesional',array('plan'=>$_REQUEST['plan'],'fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin'],'radicado'=>$_REQUEST['radicado']));
			break;
			
			case 2:
				$actionBus=ModuloGetURL('app','OrdenesdePagos','user','FrmReporteCuentasProfesional',array('plan'=>$_REQUEST['plan'],'fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin'],'radicado'=>$_REQUEST['radicado']));
			break;
		}
		
		
		$planes=$this->GetPlanes();
		$terceros=$this->GetTerceros();
		
		IncludeFile("app_modules/OrdenesdePagos/RemoteXajax/OrdenFacturaVouchers.php");
		$this->SetXajax(array("ObtenerPlan"));
		
		$this->salida.="<div id=\"Err\"></div>";
		$this->salida.="	<table border=\"0\" width=\"70%\" align=\"center\">";   
		$this->salida.= $this->SetStyle("MensajeError");    
		$this->salida.="</table><br>";            
		$check="";
		if($_REQUEST['radicado'])
			$check="checked";
		
		$check1="";
		if($_REQUEST['recaudo'])
			$check1="checked";
		
		$this->salida.="<form name=\"forma_bus\" action=\"$actionBus\" method=\"post\">";
		$this->salida.="	<table border=\"0\" width=\"70%\" class=\"modulo_table_list\" align=\"center\" cellspacing=\"1\">";    
    $this->salida.="		<tr><td colspan=\"5\" width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">BUSQUEDA</td></tr>";
    $this->salida.="		<tr id=\"trm\">";
		$this->salida.="			<td class=\"modulo_table_list_title\" id=\"MostrarT1\" width=\"10%\" align=\"center\">PROFESIONAL</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" id=\"profesional1\" width=\"47%\" align=\"left\"></td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" id=\"MostrarT2\" width=\"10%\" align=\"center\">IDENTIFICACION</td>";
		$this->salida.="			<td class=\"modulo_list_claro\" id=\"profesional2\" width=\"23%\" align=\"left\"></td>";
		$this->salida.="			<td class=\"modulo_list_claro\" width=\"10%\" align=\"center\"><a href=\"javascript:MostrarSpan('buscaPro');\">BUSCAR</a></td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr>";
		$this->salida.="			<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">FECHA DE RADICACION</td>";
    $this->salida.="			<td  width=\"100%\" class=\"modulo_list_claro\" colspan=\"2\">DESDE <input type=\"input\" name=\"fecha_ini\" readonly class=\"input-text\" maxlength=\"10\" size=\"10\" value=\"".$_REQUEST['fecha_ini']."\" ><sub>".ReturnOpenCalendario("forma_bus","fecha_ini","-")."</sub>";
		$this->salida .= "			HASTA <input type=\"input\" name=\"fecha_fin\" class=\"input-text\" readonly maxlength=\"10\" size=\"10\" value=\"".$_REQUEST['fecha_fin']."\"><sub>".ReturnOpenCalendario("forma_bus","fecha_fin","-")."</sub>";
		$this->salida.="			</td>";
		$this->salida.="			<td colspan=\"2\" class=\"modulo_list_claro\">";
		$this->salida .= "			<input type=\"checkbox\" name=\"radicado\" value=\"1\" $check> SOLO FACTURAS RADICADAS";
		$this->salida .= "			<br><input type=\"checkbox\" name=\"recaudo\" value=\"1\" $check1> SOLO LO RECAUDADO";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		/*
		$this->salida.="		<tr>";
		$this->salida.="			<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">TERCEROS</td>";
    $this->salida.="			<td class=\"modulo_list_claro\" colspan=\"4\">";
		$this->salida.="			<select name=\"tercero\" id=\"tercero\" class=\"select\" onchange=\"xajax_ObtenerPlan(document.getElementById('tercero').value);\">";
		$this->salida.="				<option value=\"\">--SELECCIONE--</option>";
		foreach($terceros as $tercero)
			$this->salida.="			<option value=\"".$tercero['tipo_id_tercero']."||//".$tercero['tercero_id']."\">".$tercero['nombre_tercero']."</option>";
		$this->salida.="			</select>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		*/
		$this->salida.="		<tr>";
		$this->salida.="			<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">PLAN</td>";
    $this->salida.="			<td class=\"modulo_list_claro\" colspan=\"4\">";
		$this->salida.="			<select name=\"plan\" id=\"plan\" class=\"select\">";
		$this->salida.="				<option value=\"\">--SELECCIONE PLAN--</option>";
		foreach($planes as $plan)
		{
			$sel="";
			if($plan['plan_id']."�".$plan['plan_descripcion']==$_REQUEST['plan'])
				$sel="selected";
			$this->salida.="			<option value=\"".$plan['plan_id']."�".$plan['plan_descripcion']."\" $sel>".$plan['plan_descripcion']."</option>";
		}
		$this->salida.="			</select>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "		<td align=\"center\" colspan=\"5\">";
		$this->salida .= "			<input type=\"submit\" class=\"input-submit\" name=\"busqueda\" value=\"BUSQUEDA\">";
		$this->salida .= "			&nbsp;&nbsp;&nbsp;<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"LIMPIAR\" onclick=\"LimpiarCampos1()\">";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida.="	</table>";
		$this->salida.= "<input type=\"hidden\" name=\"Profesional\" id=\"Profesional\">";
		$this->salida.= "</form>";
		
		$style1="style=\"display:none\"";
		$action=ModuloGetURL('app','OrdenesdePagos','user','FrmBuscadordeProfesional',array('Profesional'=>$_REQUEST['Profesional'],'plan'=>$_REQUEST['plan'],'fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin'],'radicado'=>$_REQUEST['radicado']));
		if($_REQUEST['nombreProf'] || $_REQUEST['uidProf'] || $_REQUEST['loginProf'] || $_REQUEST['IdProf'])
			$style1="";
		
		$this->salida.= "<div id=\"buscaPro\" $style1>";
		$this->salida.= "<form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida.="<table border=\"0\" width=\"70%\" class=\"modulo_table_list\" align=\"center\" cellspacing=\"1\">";    
    $this->salida.="	<tr><td colspan=\"8\" width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">BUSQUEDA DEL PROFESIONAL</td></tr>";
    $this->salida.="	<tr>";
		$this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">NOMBRE</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"40\" type=\"text\" class=\"input-text\" name=\"nombreProf\" value=\"".$_REQUEST['nombreProf']."\"></td>";
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">UID</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"uidProf\" value=\"".$_REQUEST['uidProf']."\"></td>";
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">LOGIN</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"loginProf\" value=\"".$_REQUEST['loginProf']."\"></td>";
    $this->salida.="	</tr>";
    $this->salida.="	<tr>";    
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">TIPO ID</td>";
    $this->salida.="    <td class=\"modulo_list_claro\"><select name=\"TipoIdProf\" class=\"select\">";
		$this->salida .=" 		<option value=\"\">--SELECCIONE--</option>";
    $tipos=$this->TiposTerceros();
    foreach($tipos as $value=>$titulo)
		{
      if($value==$_REQUEST['TipoIdProf'])
			{
        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
      }
			else
			{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
    $this->salida .= "    </select></td>";
    $this->salida .= "    <td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">IDENTIFICACION</td>";
    $this->salida .= "    <td class=\"modulo_list_claro\"><input size=\"20\" type=\"text\" class=\"input-text\" name=\"IdProf\" value=\"".$_REQUEST['IdProf']."\"></td>";    
    $this->salida .= "    <td align=\"center\" class=\"modulo_list_claro\" colspan=\"2\"><input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"BUSCAR\">&nbsp;&nbsp;<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"LIMPIAR\" onclick=\"LimpiarCampos(this.form);\"></td>";
    $this->salida .= "	</tr>";
    $this->salida .= "</table>";
		$this->salida .= "</form>";
		$this->salida .= "</div>";

		if($_REQUEST['nombreProf'] || $_REQUEST['uidProf'] || $_REQUEST['loginProf'] || $_REQUEST['IdProf'])
		{
			$profesionales=$this->BusquedaProfesionales($_REQUEST['nombreProf'],$_REQUEST['uidProf'],$_REQUEST['loginProf'],$_REQUEST['TipoIdProf'],$_REQUEST['IdProf']);
			if($profesionales)
			{
				$actionPr=ModuloGetURL('app','OrdenesdePagos','user','FrmBuscadordeProfesional',array('nombreProf'=>$_REQUEST['nombreProf'],'uidProf'=>$_REQUEST['uidProf'],'loginProf'=>$_REQUEST['loginProf'],'IdProf'=>$_REQUEST['IdProf'],'TipoIdProf'=>$_REQUEST['TipoIdProf'],'Profesional'=>$_REQUEST['Profesional'],'plan'=>$_REQUEST['plan'],'fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin'],'radicado'=>$_REQUEST['radicado']));

				$this->salida .= "<div id=\"Tprof\">";
				$this->salida .= "<form name=\"formasel\" action=\"$actionPr\" method=\"post\">";
				$this->salida .= "	<br><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";    
				$this->salida .= "    <tr>";
				$this->salida .= "    	<td width=\"20\" class=\"modulo_table_list_title\" align=\"center\">IDENTIFICACION</td>";
				$this->salida .= "    	<td width=\"35\" class=\"modulo_table_list_title\" align=\"center\">NOMBRE</td>";
				$this->salida .= "    	<td width=\"20\" class=\"modulo_table_list_title\" align=\"center\">UID</td>"; 
				$this->salida .= "			<td width=\"20\" class=\"modulo_table_list_title\" align=\"center\">LOGIN</td>"; 
				$this->salida .= "			<td width=\"5\" class=\"modulo_table_list_title\">ELEGIR</td>"; 
				$this->salida .= "    </tr>";    
				for($i=0;$i<sizeof($profesionales);$i++)
				{
					$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida .= "	<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";        
					$this->salida .= "		<td class=\"label\">".$profesionales[$i]['tipo_tercero_id']." ".$profesionales[$i]['tercero_id']."</td>";        
					$this->salida .= "		<td class=\"label\">".$profesionales[$i]['nombre']."</td>"; 
					$this->salida .= "		<td class=\"label\">".$profesionales[$i]['usuario_id']."</td>"; 
					$this->salida .= "		<td class=\"label\">".$profesionales[$i]['usuario']."</td>";
					$this->salida .= "		<td align=\"center\" class=\"label\"><input type=\"radio\" name=\"Profesional\" value=\"".$profesionales[$i]['tipo_tercero_id']."||//".$profesionales[$i]['tercero_id']."||//".$profesionales[$i]['nombre']."\" onclick=\"RadioSelecionado('".$profesionales[$i]['tipo_tercero_id']."||//".$profesionales[$i]['tercero_id']."||//".$profesionales[$i]['nombre']."')\"></td>";
					$this->salida .= "	</tr>"; 
					$y++;
				}
				$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida .= "		<td align=\"right\" colspan=\"6\">";
				$this->salida .= "			<input type=\"button\" class=\"input-submit\" name=\"seleccionar\" value=\"SELECCIONAR\" onclick=\"MostrarProf();\">";
				$this->salida .= "		</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
				$this->salida .= "</form>";
			}
			else
			{
				$this->salida .="<center><label class=\"label_error\">NO SE ENCONTRARON REGISTROS DEL PROFESIONAL</label></center><br>";
			}
		}
		
		$Paginador=new ClaseHTML();
		
		$accionPaginador = ModuloGetURL('app','OrdenesdePagos','user','FrmBuscadordeProfesional',$_REQUEST);
		$this->salida .= "".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$accionPaginador,$this->limit);

		$this->salida .= "</div>";
		
		$actionV=ModuloGetURL('app','OrdenesdePagos','user','FrmMenu',array());
		$this->salida .= "<form name=\"forma_vol\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= "	<br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";    
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		if($_REQUEST['volver1'])
		{
			$this->salida .= "<script>";
			$this->salida.= "		xGetElementById('buscaPro').style.display='none';\n";
			$this->salida.= "		xGetElementById('Tprof').style.display='none';\n";
			$this->salida.= "		document.getElementById('profesional2').innerHTML='".$_REQUEST['TipoIdProf']." - ".$_REQUEST['IdProf']."';\n";
			$this->salida.= "  	document.getElementById('profesional1').innerHTML='".$_REQUEST['nombreProf']."';\n";
			$this->salida.= "		document.getElementById('Profesional').value='".$_REQUEST['Profesional']."';\n";
			$this->salida .= "</script>";
		}
		
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	
	function FrmEstadoCuentasProfesional($ban)
	{
		$this->SetXajax(array("VouchersFactura"),"app_modules/OrdenesdePagos/RemoteXajax/OrdenFacturaVouchers.php");
		
		list($TipoidProf,$Prof,$nomPro)=explode("||//",$_REQUEST['Profesional']);
		
		if(empty($TipoidProf) AND empty($Prof) AND $_SESSION['op']==1)
		{
			$this->frmError["MensajeError"]="INGRESE EL PROFESIONAL";
			$this->FrmBuscadordeProfesional($_REQUEST);
			return true;
		}
			
		$this->salida .= ThemeAbrirTabla('ESTADO CUENTA PROFESIONAL');
    
		$fecha_ini=$this->FechaStamp($_REQUEST['fecha_ini']);
		$fecha_fin=$this->FechaStamp($_REQUEST['fecha_fin']);
		
		list($plan,$nom_plan)=explode("�",$_REQUEST['plan']);
		
		$datosCxp=$this->GetCuentasxPagar_Orden($TipoidProf,$Prof,$plan,$fecha_ini,$fecha_fin,$_REQUEST['radicado'],$_REQUEST['recaudo']);
		
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("RemoteScripting");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");
		
		if($ban==1)
			$this->salida .="<center><label class=\"label_error\">DATOS GUARDADOS SATISFACTORIAMENTE</label></center><br>";
		
		$actionO=ModuloGetURL('app','OrdenesdePagos','user','GuardarOrdenPago',array('Profesional'=>$_REQUEST['Profesional']));
		
		if($datosCxp)
		{
			if($Prof)
			{
				$this->salida .= "	<table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";    
				$this->salida .= "    <tr align=\"center\">";
				$this->salida .= "    	<td width=\"20%\" class=\"modulo_table_list_title\">IDENTIFICACION</td>";
				$this->salida .= "    	<td width=\"20%\" class=\"modulo_list_claro\">$TipoidProf - $Prof</td>";
				$this->salida .= "    	<td width=\"20%\" class=\"modulo_table_list_title\">PROFESIONAL</td>"; 
				$this->salida .= "			<td width=\"40%\" class=\"modulo_list_claro\">$nomPro</td>";
				$this->salida .= "    </tr>";  
				$this->salida .= "	</table>";
			}
			
			if(!empty($fecha_ini) AND !empty($fecha_fin))
			{
				$this->salida .= "	<table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";    
				$this->salida .= "    <tr>";
				$this->salida .= "    	<td align=\"center\" width=\"20%\" class=\"modulo_table_list_title\">FECHA RADICACION</td>";
				$this->salida .= "    	<td align=\"left\" width=\"80%\" class=\"modulo_list_claro\">DESDE $fecha_ini HASTA $fecha_fin</td>";
				$this->salida .= "    </tr>";  
				$this->salida .= "	</table>";
			}
			
			if(!empty($_REQUEST['plan']))
			{
				$this->salida .= "	<table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";    
				$this->salida .= "    <tr>";
				$this->salida .= "    	<td align=\"center\" width=\"20%\" class=\"modulo_table_list_title\">PLAN</td>";
				$this->salida .= "    	<td  align=\"left\" width=\"80%\" class=\"modulo_list_claro\">".$plan." - ".$nom_plan."</td>";
				$this->salida .= "    </tr>";  
				$this->salida .= "	</table>";
			}
			
			$this->salida .= "	<br>";
			
			$this->salida .= "	<table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";    
			$this->salida .= "    <tr  align=\"center\">";
			$this->salida .= "    	<td class=\"modulo_table_list_title\" width=\"35%\">VALOR ORDEN DE PAGO</td>";
			$this->salida .= "    	<td width=\"65%\" id=\"vFact\" class=\"label\"> $ 0 </td>";
			$this->salida .= "    </tr>";
			$this->salida .= "  </table><br>";
			
			$this->salida .= "<form name=\"forma_orden\" action=\"$actionO\" method=\"post\">";  
			$this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
			$this->salida .= "    <tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida .= "    	<td width=\"15%\">DOCUMENTO</td>";
			$this->salida .= "    	<td width=\"10%\">FACTURA</td>";
			$this->salida .= "			<td width=\"10%\">RECIBO</td>";
			$this->salida .= "    	<td width=\"15%\">FECHA</td>";
			$this->salida .= "    	<td width=\"15%\">VALOR</td>";
			$this->salida .= "			<td width=\"15%\">FECHA DE RADICACION</td>";
			$this->salida .= "			<td width=\"10%\">VOUCHER</td>";
			$this->salida .= "    	<td width=\"10%\"><input type=\"checkbox\" name=\"sw_todos\" onclick=\"CheckTodos(this.form,this.checked);\"> TODOS</td>"; 
			$this->salida .= "    </tr>";
			
			foreach($datosCxp as $key=>$valor)
			{
				foreach($valor as $key1=>$valor1)
				{
					if(!intval($valor1['valor']))
						$valor_factura=$this->GetValorFactura($valor1['empresa_id'],$valor1['prefijo'],$valor1['numero']);
					else
						$valor_factura=$valor1['valor'];
					if(!$valor_factura) $valor_factura =0;
						
					if($k%2==0)
					{
						$estilo="modulo_list_oscuro";
						$background = "#CCCCCC";
					}
					else
					{
						$estilo="modulo_list_claro";
						$background = "#DDDDDD";
					}
					$this->salida .= "    <tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$background'); onmouseover=mOvr(this,'#FFFFFF');>";
					$this->salida .= "    	<td>".$valor1['prefijo']."-".$valor1['numero']."</td>";
					$this->salida .= "    	<td>".$valor1['numero_factura_id']."</td>";
					$this->salida .= "    	<td>".$valor1['numero_recibo']."</td>";
					$this->salida .= "    	<td>".$valor1['fecha']."</td>";
					$this->salida .= "    	<td align=\"right\"> $ ".FormatoValor($valor_factura)."</td>"; 
					$this->salida .= "    	<td>".$valor1['fecha_rad']."</td>";
					$this->salida .= "			<td class=\"label\"><a href=\"javascript:VerVoucherFactura('".$valor1['empresa_id']."','".$valor1['prefijo']."','".$valor1['numero']."','".$valor1['numero_factura_id']."')\"><sub><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\" width=\"13\" height=\"13\"> VER </sub></a></td>"; 
					$check="";
					if($_REQUEST['SelFact']==$valor1['empresa_id']."__".$valor1['prefijo']."__".$valor1['numero']."__".$valor_factura)
						$check="checked";
					$this->salida .= "			<td><input type=\"checkbox\" name=\"SelFact[]\" value=\"".$valor1['empresa_id']."__".$valor1['prefijo']."__".$valor1['numero']."__".$valor_factura."\" $check onclick=\"CalculoValorFact(this.checked,$valor_factura);\"></td>"; 
					$this->salida .= "    </tr>";
					$k++;
					break;
				}
				
			}
			$this->salida .= "    <tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .= "    	<td colspan=\"8\" align=\"right\"><input type=\"submit\" name=\"GenerarOrdenPago\" value=\"Generar Orden de Pago\" class=\"input-submit\"></td>";
			$this->salida .= "    </tr>";
			$this->salida .= "	</table>";

			$this->salida .= "</form>";
		}
		$capas="var capas1 = new Array(";
		$b=true;
		$ini=10;
		$fin=0;
		
		$listado_ordenes=$this->GetOrdenesdePagoTotal($TipoidProf,$Prof,$plan,$fecha_ini,$fecha_fin,$_REQUEST['radicado'],$_REQUEST['recaudo']);

		if($listado_ordenes)
		{
			$i=0;
			foreach($listado_ordenes as $key=>$valor)
			{
				$salida1= "	<table align=\"center\" width=\"100%\" border=\"0\">\n";
				$salida1.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
				$salida1.= "			<td width=\"10%\">FACTURA</td>";
				$salida1.= "			<td width=\"10%\">VOUCHER</td>";
				$salida1.= "			<td width=\"20%\">CARGO</td>";
				$salida1.= "			<td width=\"10%\">RECIBO</td>";
				$salida1.= "			<td width=\"15%\">PACIENTE</td>";
				$salida1.= "			<td width=\"15%\">PLAN</td>";
				$salida1.= "			<td width=\"10%\">VALOR VOUCHER</td>";
				$salida1.= "			<td width=\"10%\">VALOR FACTURA</td>";
				$salida1.= "    </tr>";
				$j=0;
				foreach($valor as $key1=>$valor1)
				{
					$a=true;
					foreach($valor1 as $key2=>$valor2)
					{
						if($j % 2 == 0)
						{
							$estilo='modulo_list_oscuro';
						}
						else
						{
							$estilo='modulo_list_claro';
						}
						
						$salida1.= "		<tr class=\"$estilo\" align=\"center\">";
						$salida1.= "			<td>".$valor2['numero_factura_id']."</td>";
						$salida1.= "			<td>".$valor2['prefijo_v']." - ".$valor2['numero_v']."</td>";
						$salida1.= "			<td>".$valor2['desc_cargo']."</td>";
						$salida1.= "			<td>".$valor2['numero_recibo']."</td>";
						$salida1.= "			<td>".$valor2['nombre_paciente']."</td>";
						$salida1.= "			<td>".$valor2['plan_descripcion']."</td>";
						$salida1.= "			<td align=\"right\"> $ ".FormatoValor($valor2['valor_real'])."</td>";
						if($a)
						{
							$salida1.= "			<td align=\"right\" rowspan=\"".sizeof($valor1)."\"> $ ".FormatoValor($valor2['valor'])."</td>";
							$a=false;
						}
						$salida1.= "		</tr>";
							
						$j++;
					}
				}
				$salida1.= "	</table>";
				
				if($i % 2 == 0)
				{
					$estilo='modulo_list_oscuro';
					$background = "#CCCCCC";
				}
				else
				{
					$estilo='modulo_list_claro';
					$background = "#DDDDDD";
				}
				
				$style="";
				if($i>=$ini)
				{
					$style="style=\"display:none\"";
					$fin=$i+1;
				}
				$salida.= "	<div id=\"ver$i\" $style>";
				$salida.= "		<table align=\"center\" width=\"100%\" border=\"0\">\n";
				$salida.= "    	<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$background'); onmouseover=mOvr(this,'#FFFFFF');>";
				$salida.= "				<td width=\"5%\"><div id=\"mostrar$i\"><a href=\"javascript:showhide1('orden$i');\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a></div><div id=\"ocultar$i\" style=\"display:none\"><a href=\"javascript:showhide1('orden$i');\"><img src=\"".GetThemePath()."/images/arriba.png\" border=\"0\"></a></div></td>";
				$salida.= "				<td width=\"25%\" align=\"left\"><b>ORDEN DE PAGO</b> # :  $key</td>";
				$salida.= "				<td width=\"25%\" align=\"left\"><b>FECHA</b> : ".$valor[$key1][$key2]['fecha']."</td>";
				$salida.= "				<td width=\"25%\" align=\"left\"><b>VALOR</b> : $ ".FormatoValor($valor[$key1][$key2]['valor_total'])."</td>";
				$direccionP="app_modules/OrdenesdePagos/reports/html/OrdenPagos.report.php?TipoProf=$TipoidProf&Prof=$Prof&nombre=$nomPro&plan=".$_REQUEST['plan']."&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin&radicado=".$_REQUEST['radicado']."&recaudo=".$_REQUEST['recaudo']."&empresa_id=".$valor[$key1][$key2]['empresa_id']."&prefijo=".$valor[$key1][$key2]['prefijo_op']."&numero=".$valor[$key1][$key2]['numero_op'];
				$salida.="				<td width=\"20%\"><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccionP');\"> IMPRIMIR </a></td>";	
				$salida.= "    </tr>";
				$salida.= "</table>\n";
				$salida.= "</div>";
				$salida.= "<div id=\"orden$i\" style=\"display:none\">\n";
				$salida.= "  $salida1";
				$salida.= "</div>";
				
				$b? $capas.="'orden$i'":$capas.=",'orden$i'";
				$b=false;
				
				$i++;
			}
			$this->salida.="".$salida;
			if($i>$ini)
			{
				//$this->salida.= "			<br><center><div id=\"mostrar\"><label class=\"label\"><a href=\"javascript:MostrarOcultar('ver',$ini,$fin,0)\"> >> VER MAS</a></label></div><div id=\"ocultar\" style=\"display:none\"><label class=\"label\"><a href=\"javascript:MostrarOcultar('ver',$ini,$fin,1)\"> >> OCULTAR</a></label></div></center>";
			}
		}

		$direccion="app_modules/OrdenesdePagos/reports/html/OrdenPagos.report.php?TipoProf=$TipoidProf&Prof=$Prof&nombre=$nomPro&plan=".$_REQUEST['plan']."&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin&radicado=".$_REQUEST['radicado']."&recaudo=".$_REQUEST['recaudo']."";
		$this->salida.="		<br><center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion');\"> IMPRIMIR TODAS</a></label></center>";	
		
		$actionV=ModuloGetURL('app','OrdenesdePagos','user','FrmBuscadordeProfesional',array('nombreProf'=>$nomPro,'TipoIdProf'=>$TipoidProf,'IdProf'=>$Prof,'Profesional'=>$_REQUEST['Profesional'],'plan'=>$_REQUEST['plan'],'fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin'],'radicado'=>$_REQUEST['radicado'],'recaudo'=>$_REQUEST['recaudo']));
		$this->salida .= "<form name=\"forma_vol\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= "	<br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";    
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"volver1\" value=\"VOLVER\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
		$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='d2Contents' class=\"d2Content\">\n";
		$this->salida .= "	</div>\n"; 
		$this->salida .= "</div>\n";
		
		$this->salida .= "<script>\n";
		$this->salida .= "	$capas);\n";
		$this->salida .= "	var valor_fact=0;\n";
		
		$this->salida .= "	function reportecuentas(url)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		window.open(url,'REPORTE CIRUGIA','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function mOvr(src,clrOver)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		src.style.background = clrOver;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function mOut(src,clrIn)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		src.style.background = clrIn;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function MostrarOcultar(capa,ini,fin,op)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		if(!op)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			for(var i=ini;i<fin;i++)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				capita=xGetElementById(capa+''+i);\n";
		$this->salida .= "				capita.style.display=\"\"; \n";
		$this->salida .= "			}\n";
		$this->salida .= "			e=xGetElementById('mostrar');\n";
		$this->salida .= "			e.style.display=\"none\"; \n";
		$this->salida .= "			e=xGetElementById('ocultar');\n";
		$this->salida .= "			e.style.display=\"\"; \n";
		$this->salida .= "		}\n";
		$this->salida .= "		else\n";
		$this->salida .= "		{\n";
		$this->salida .= "			for(var i=ini;i<fin;i++)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				capita=xGetElementById(capa+''+i);\n";
		$this->salida .= "				capita.style.display=\"none\"; \n";
		$this->salida .= "			}\n";
		$this->salida .= "			e=xGetElementById('mostrar');\n";
		$this->salida .= "			e.style.display=\"\"; \n";
		$this->salida .= "			e=xGetElementById('ocultar');\n";
		$this->salida .= "			e.style.display=\"none\"; \n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
	
		$this->salida .= "	function showhide1(Seccion)\n";
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
		
		$this->salida .= "	function CalculoValorFact(x,vf)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		if(x==true)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			valor_fact+=parseFloat(vf);\n";
		$this->salida .= "		}\n";
		$this->salida .= "		else\n";
		$this->salida .= "		{\n";
		$this->salida .= "			valor_fact-=parseFloat(vf);\n";
		$this->salida .= "		}\n";
		$this->salida .= "		document.getElementById('vFact').innerHTML=' $ '+valor_fact;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function CheckTodos(frm,x){\n";
		$this->salida .= "  		var valF=new Array();\n";
		$this->salida .= "      valor_fact=0;\n";
		$this->salida .= "  		if(x==true){\n";
		$this->salida .= "    			for(i=0;i<frm.elements.length;i++){\n";
		$this->salida .= "      			if(frm.elements[i].type=='checkbox' &&  frm.elements[i].name == 'SelFact[]'){\n";
		$this->salida.= "									valF=jsrsArrayFromString( frm.elements[i].value, '__' );\n";
		$this->salida .= "        				CalculoValorFact(x,valF[3]);\n";
		$this->salida .= "        				frm.elements[i].checked=true;\n";
		$this->salida .= "				}\n";
		$this->salida .= "			}\n";
		$this->salida .= " 		}else{\n";
		$this->salida .= "    			for(i=0;i<frm.elements.length;i++){\n";
		$this->salida .= "      			if(frm.elements[i].type=='checkbox' && frm.elements[i].name == 'SelFact[]'){\n";
		$this->salida .= "        				frm.elements[i].checked=false;\n";
		$this->salida .= "        				CalculoValorFact(x,0);\n";
		$this->salida .= "      			}\n";
		$this->salida .= "    			}\n";
		$this->salida .= "  		}\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "</script>\n";
		
		$this->salida .= "<script>\n";
		$this->salida .= "	var contenedor = '';\n";
		$this->salida .= "	var titulo = '';\n";
		
		$this->salida .= "	function VerVoucherFactura(empresa,prefijo,numero,fact)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_VouchersFactura(empresa,prefijo,numero,fact);\n";
		$this->salida .= "		Iniciar();\n";
		$this->salida .= "		MostrarSpan('d2Container');\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Iniciar()\n";
		$this->salida .= "	{\n";
		$this->salida .= "	 	contenedor = 'd2Container';\n";
		$this->salida .= "		titulo = 'titulo';\n";
		$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+50);\n";
		$this->salida .= "	  xResizeTo(ele,500,'auto');\n";
		$this->salida .= "		ele = xGetElementById('d2Contents');\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
		$this->salida .= "	  xResizeTo(ele,500, 150);\n";
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

		$this->salida .= "</script>\n";
		$this->salida .= ThemeCerrarTabla();
    return true;
  }
	
	
	function FrmReporteCuentasProfesional()
	{
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("RemoteScripting");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");
		
		$this->salida .= ThemeAbrirTabla('REPORTE CUENTA PROFESIONAL');
		
		list($TipoidProf,$Prof,$nomPro)=explode("||//",$_REQUEST['Profesional']);
		list($plan,$nom_plan)=explode("�",$_REQUEST['plan']);
		$fecha_ini=$this->FechaStamp($_REQUEST['fecha_ini']);
		$fecha_fin=$this->FechaStamp($_REQUEST['fecha_fin']);
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
			$this->salida .= "    <tr>";
			$this->salida .= "    	<td align=\"center\" width=\"20%\" class=\"modulo_table_list_title\">FECHA RADICACION</td>";
			$this->salida .= "    	<td align=\"left\" width=\"80%\" class=\"modulo_list_claro\">DESDE $fecha_ini HASTA $fecha_fin</td>";
			$this->salida .= "    </tr>";  
			$this->salida .= "	</table>";
		}
		
		if(!empty($_REQUEST['plan']))
		{
			$this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
			$this->salida .= "    <tr>";
			$this->salida .= "    	<td align=\"center\" width=\"20%\" class=\"modulo_table_list_title\">PLAN</td>";
			$this->salida .= "    	<td  align=\"left\" width=\"80%\" class=\"modulo_list_claro\">".$plan." - ".$nom_plan."</td>";
			$this->salida .= "    </tr>";  
			$this->salida .= "	</table>";
		}
		
		$this->salida .= "	<br>";
		
		$listado_ordenes=$this->GetOrdenesdePagoTotal($TipoidProf,$Prof,$plan,$fecha_ini,$fecha_fin,$_REQUEST['radicado'],$_REQUEST['recaudo'],1);
		
		if($listado_ordenes)
		{
			foreach($listado_ordenes as $key=>$valor)
			{
				$salida1="";
				$salida="";
				foreach($valor as $key1=>$valor1)
				{
					$salida1= "	<table align=\"center\" width=\"100%\" border=\"0\">\n";
					$salida1.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
					$salida1.= "			<td width=\"10%\">FACTURA</td>";
					$salida1.= "			<td width=\"10%\">VOUCHER</td>";
					$salida1.= "			<td width=\"20%\">CARGO</td>";
					$salida1.= "			<td width=\"10%\">RECIBO</td>";
					$salida1.= "			<td width=\"15%\">PACIENTE</td>";
					$salida1.= "			<td width=\"15%\">PLAN</td>";
					$salida1.= "			<td width=\"10%\">VALOR VOUCHER</td>";
					$salida1.= "			<td width=\"10%\">VALOR FACTURA</td>";
					$salida1.= "    </tr>";
					
					foreach($valor1 as $key2=>$valor2)
					{
						$j=0;
						$a=true;
						foreach($valor2 as $key3=>$valor3)
						{
							if($j % 2 == 0)
							{
								$estilo='modulo_list_oscuro';
							}
							else
							{
								$estilo='modulo_list_claro';
							}
							
							$salida1.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
							$salida1.= "			<td>".$valor3['numero_factura_id']."</td>";
							$salida1.= "			<td>".$valor3['prefijo_v']." - ".$valor3['numero_v']."</td>";
							$salida1.= "			<td>".$valor3['desc_cargo']."</td>";
							$salida1.= "			<td>".$valor3['numero_recibo']."</td>";
							$salida1.= "			<td>".$valor3['nombre_paciente']."</td>";
							$salida1.= "			<td>".$valor3['plan_descripcion']."</td>";
							$salida1.= "			<td align=\"right\"> $ ".FormatoValor($valor3['valor_real'])."</td>";
							if($a)
							{
								$salida1.= "			<td align=\"right\" rowspan=\"".sizeof($valor2)."\"> $ ".FormatoValor($valor3['valor'])."</td>";
								$a=false;
							}
							$salida1.= "		</tr>";
								
							$j++;
						}
					}
					$salida1.= "	</table>";
					
					$salida.= "	<br><table align=\"center\" width=\"100%\" border=\"0\">\n";
					$salida.= "		<tr>\n";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"15%\" align=\"left\">ORDEN DE PAGO</td>";
					$salida.= "				<td class=\"modulo_list_claro\" width=\"15%\" align=\"left\">$key1</td>";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"15%\" align=\"left\">FECHA</td>";
					$salida.= "				<td class=\"modulo_list_claro\" width=\"20%\" align=\"left\">".$valor1[$key2][$key3]['fecha']."</td>";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"15%\" align=\"left\">VALOR</td>";
					$salida.= "				<td class=\"modulo_list_claro\" width=\"30%\" align=\"left\"> $ ".FormatoValor($valor1[$key2][$key3]['valor_total'])."</td>";
					$direccionP="app_modules/OrdenesdePagos/reports/html/OrdenPagos.report.php?TipoProf=".$valor1[$key2][$key3]['tipo_id_profesional']."&Prof=".$valor1[$key2][$key3]['profesional_id']."&nombre=".$valor1[$key2][$key3]['nombre']."&plan=".$_REQUEST['plan']."&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin&radicado=".$_REQUEST['radicado']."&recaudo=".$_REQUEST['recaudo']."&empresa_id=".$valor1[$key2][$key3]['empresa_id']."&prefijo=".$valor1[$key2][$key3]['prefijo_op']."&numero=".$valor1[$key2][$key3]['numero_op'];
					$salida.="				<td class=\"modulo_list_claro\" align=\"center\" width=\"20%\"><a href=\"javascript:reportecuentas('$direccionP');\"><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" title=\"IMPRIMIR\"></a></td>";	
					$salida.= "		</tr>\n";
					$salida.= "	</table>\n";
					$salida.= "	$salida1";
					$i++;
				}
				
				$salida0 .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
				$salida0 .= "		<tr align=\"center\">";
				$salida0 .= "			<td class=\"modulo_list_oscuro\" width=\"100%\">";
				$salida0 .= "				<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
				$salida0 .= "					<tr align=\"center\">";
				$salida0 .= "						<td width=\"20%\" class=\"modulo_table_list_title\">IDENTIFICACION</td>";
				$salida0 .= "						<td width=\"20%\" class=\"modulo_list_claro\">$key</td>";
				$salida0 .= "    				<td width=\"20%\" class=\"modulo_table_list_title\">PROFESIONAL</td>"; 
				$salida0 .= "						<td width=\"25%\" class=\"modulo_list_claro\">".$listado_ordenes[$key][$key1][$key2][$key3]['nombre']."</td>";
				$salida0 .= "					</tr>";
				$salida0 .= "				</table>";
				$salida0 .= "				$salida";
				$direccionP1="app_modules/OrdenesdePagos/reports/html/OrdenPagos.report.php?TipoProf=".$listado_ordenes[$key][$key1][$key2][$key3]['tipo_id_profesional']."&Prof=".$listado_ordenes[$key][$key1][$key2][$key3]['profesional_id']."&nombre=".$listado_ordenes[$key][$key1][$key2][$key3]['nombre']."&plan=".$_REQUEST['plan']."&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin&radicado=".$_REQUEST['radicado']."&recaudo=".$_REQUEST['recaudo'];
				$salida0.="		<br><center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccionP1');\"> IMPRIMIR </a></label></center>";	
				$salida0 .= "			</td>";
				$salida0 .= "		</tr>";
				$salida0 .= "	</table><br><br>";
			}
			$this->salida.=" $salida0";
		}
		
		if(!$Prof AND !$TipoidProf)
		{
			$direccion="app_modules/OrdenesdePagos/reports/html/OrdenPagos.report.php?TipoProf=$TipoidProf&Prof=$Prof&nombre=$nomPro&plan=".$_REQUEST['plan']."&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin&radicado=".$_REQUEST['radicado']."&recaudo=".$_REQUEST['recaudo']."";
			$this->salida.="		<center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion');\"> IMPRIMIR TODOS</a></label></center>";
		}
		
		$actionV=ModuloGetURL('app','OrdenesdePagos','user','FrmBuscadordeProfesional',array('nombreProf'=>$nomPro,'TipoIdProf'=>$TipoidProf,'IdProf'=>$Prof,'Profesional'=>$_REQUEST['Profesional'],'plan'=>$_REQUEST['plan'],'fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin'],'radicado'=>$_REQUEST['radicado'],'recaudo'=>$_REQUEST['recaudo']));
		$this->salida .= "<form name=\"forma_vol\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= "	<br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";    
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"volver1\" value=\"VOLVER\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "<script>\n";
		$this->salida .= "	function reportecuentas(url)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		window.open(url,'REPORTE ORDEN DE PAGO','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
		$this->salida .= "	}\n";
		$this->salida .= "</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
    return true;
	}
	
	
	function FrmBuscadorFecha()
	{
		$this->salida .= ThemeAbrirTabla('REPORTE ESTADO CUENTA TOTAL');
		
		$actionBus=ModuloGetURL('app','OrdenesdePagos','user','FrmReporteGeneral');
	
		$this->salida.="<form name=\"forma_bus\" action=\"$actionBus\" method=\"post\">";
		$this->salida.="	<table border=\"0\" width=\"70%\" class=\"modulo_table_list\" align=\"center\" cellspacing=\"1\">";    
    $this->salida.="		<tr><td colspan=\"5\" width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">BUSQUEDA POR FECHA</td></tr>";
		$this->salida.="		<tr>";
		$this->salida.="			<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">FECHAS:</td>";
    $this->salida.="			<td  width=\"100%\" class=\"modulo_list_claro\" colspan=\"2\"><b>DESDE</b> <input type=\"input\" name=\"fecha_ini\" readonly class=\"input-text\" maxlength=\"10\" size=\"10\" value=\"".$_REQUEST['fecha_ini']."\" ><sub>".ReturnOpenCalendario("forma_bus","fecha_ini","-")."</sub>";
		$this->salida.= "			<b>HASTA</b> <input type=\"input\" name=\"fecha_fin\" class=\"input-text\" readonly maxlength=\"10\" size=\"10\" value=\"".$_REQUEST['fecha_fin']."\"><sub>".ReturnOpenCalendario("forma_bus","fecha_fin","-")."</sub>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="    <td align=\"center\" class=\"modulo_list_claro\" colspan=\"2\"><input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"ACEPTAR\">&nbsp;&nbsp;<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"LIMPIAR\" onclick=\"document.forma_bus.fecha_ini.value='';document.forma_bus.fecha_fin.value=''\"></td>";
		$this->salida.="	</table>";
		$this->salida.="</form>";
		
		$actionV=ModuloGetURL('app','OrdenesdePagos','user','FrmMenu');
		$this->salida .= "<form name=\"forma_vol\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= "	<br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";    
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"volver1\" value=\"VOLVER\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTabla();
		
    return true;
	}
	
	function FrmReporteGeneral()
	{
		$this->salida .= ThemeAbrirTabla('REPORTE GENERAL');

		$fecha_ini=$this->FechaStamp($_REQUEST['fecha_ini']);
		$fecha_fin=$this->FechaStamp($_REQUEST['fecha_fin']);
		
		$datosG=$this->ReporteGeneral($fecha_ini,$fecha_fin);
		
		$this->salida.="	<table border=\"0\" width=\"50%\" class=\"modulo_table_list\" align=\"center\">";    
		$this->salida.="		<tr align=\"center\">";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"30%\">FECHAS: </td>";
		$this->salida.="			<td width=\"35%\"><b>DESDE &nbsp;&nbsp;$fecha_ini &nbsp;&nbsp;HASTA &nbsp;&nbsp;$fecha_fin</b></td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table><br>";
		
		$i=0;
		$total=0;
		
		//echo "<pre>";
		//print_r($datosG);
		//exit;
		if($datosG)
		{
			$this->salida.="	<table border=\"0\" width=\"100%\" class=\"modulo_table_list\" align=\"center\">";    
			$this->salida.="		<tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida.="			<td width=\"10%\">IDENTIFICACION</td>";
			$this->salida.="			<td width=\"20%\">NOMBRE PROFESIONAL</td>";
			$this->salida.="			<td width=\"55%\">VOUCHERS</td>";
			$this->salida.="			<td width=\"15%\">VALOR A PAGAR</td>";
			$this->salida.="		</tr>";
			foreach($datosG as $key=>$valor)
			{
				if($i%2==0)
					$estilo="modulo_list_claro";
				else
					$estilo="modulo_list_oscuro";
				
				$salida1="				<table border=\"0\" width=\"100%\">";
				$salida1.="					<tr class=\"modulo_table_list_title\">";
				$salida1.="						<td  align=\"center\" width=\"15%\">";
				$salida1.="							VOUCHER";
				$salida1.="						</td>";
				$salida1.="						<td  align=\"center\" width=\"10%\">";
				$salida1.="							FACTURA MEDICO";
				$salida1.="						</td>";
				$salida1.="						<td  align=\"center\" width=\"65%\">";
				$salida1.="							DESCRIPCION";
				$salida1.="						</td>";
				$salida1.="						<td  align=\"center\" width=\"10%\">";
				$salida1.="							VALOR VOUCHER";
				$salida1.="						</td>";
				$salida1.="					</tr>";

				$total_profesional=0;
				
				foreach($valor as $key1=>$valor1)
				{
					if($estilo=="modulo_list_claro")
						$estilo1="modulo_list_oscuro";
					else
						$estilo1="modulo_list_claro";
				
					$salida1.="					<tr class=\"$estilo1\">";
					$salida1.="						<td  align=\"center\">";
					$salida1.="							".$valor1['prefijo']." - ".$valor1['numero']."";
					$salida1.="						</td>";
					$salida1.="						<td  align=\"center\">";
					$salida1.="							".$valor1['numero_factura_id']."";
					$salida1.="						</td>";
					$salida1.="						<td  align=\"center\">";
					$salida1.="							".strtoupper($valor1['descripcion'])."";
					$salida1.="						</td>";
					$salida1.="						<td  align=\"center\">";
					$salida1.="							$ ".FormatoValor($valor1['valor_a_pagar'])."";
					$salida1.="						</td>";
					$salida1.="					</tr>";
					
					$total_profesional+=$valor1['valor_a_pagar'];
				}
				
				$salida1.="				</table>";
				
				$salida0.="		<tr class=\"$estilo\">";
				$partes=explode("-", $key);
        $tipo_id=$partes[0];
        $profesional_id=$partes[1];
        $direccion="app_modules/OrdenesdePagos/reports/html/ReportePorProfesional.report.php?fecha_ini=$fecha_ini&fecha_fin=".$fecha_fin."&tipo_id=".$tipo_id."&profesional_id=".$profesional_id;
			  $salida0.="<td  align=\"center\"> <center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion');\"> IMPRIMIR</a></label>";
        $salida0.="			".$key."</td>";
				$salida0.="			<td  align=\"center\">".$valor[$key1]['nombre']."</td>";
				$salida0.="			<td  align=\"center\">";
				$salida0.="				$salida1";
				$salida0.="			</td>";
				
				$salida0.="			<td  align=\"right\"> $ ".FormatoValor($total_profesional)."</td>";
				$salida0.="		</tr>";
				$total+=$total_profesional;
				$i++;
			}
			$this->salida.="		$salida0";
			$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="			<td colspan=\"3\" align=\"right\">VALOR TOTAL</td>";
			$this->salida.="			<td align=\"right\"> $ ".FormatoValor($total)."</td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table><br>";
			
			$_SESSION['ordenes_de_pago']['reporte_general']=$datosG;
		 
			$direccion="app_modules/OrdenesdePagos/reports/html/ReporteGeneral.report.php?fecha_ini=$fecha_ini&fecha_fin=".$fecha_fin."&sw=0";
			$this->salida.="		<center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion');\"> IMPRIMIR TOTAL</a></label>";
		
			$direccion2="app_modules/OrdenesdePagos/reports/html/ReporteGeneral.report.php?fecha_ini=$fecha_ini&fecha_fin=".$fecha_fin."&sw=1";
			$this->salida.="		&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion2');\"> IMPRIMIR DETALLADO</a></label></center>";
	
		}
		else
		{
			$this->salida.="	<center><label class=\"label_error\">NO SE ENCONTRARON REGISTROS EN ESTAS FECHAS</label><center>";
		}
		
		$actionV=ModuloGetURL('app','OrdenesdePagos','user','FrmBuscadorFecha',array('fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin']));
		$this->salida .= "<form name=\"forma_vol\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= "	<br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";    
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"volver1\" value=\"VOLVER\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "<script>\n";
		$this->salida .= "	function reportecuentas(url)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		window.open(url,'REPORTE GENERAL','screen.width,screen.height,resizable=no,location=yes,menubar=1,toolbar=1,status=no,scrollbars=yes');\n";
		$this->salida .= "	}\n";
		$this->salida .= "</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
    return true;
	}
	
	function FrmOPCancelacion()
	{
		$this->salida .= ThemeAbrirTabla('CANCELACION ORDENES DE PAGO');

		$this->salida.= "<form name=\"forma\" action=\"\" method=\"post\">";
    
		/*$this->salida.="<table border=\"0\" width=\"70%\" class=\"modulo_table_list\" align=\"center\" cellspacing=\"1\">";    
    $this->salida.="	<tr>";
		$this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">PREFIJO</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"prefijo\" value=\"".$_REQUEST['prefijo']."\"></td>";
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">NUMERO</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"numero\" value=\"".$_REQUEST['numero']."\"></td>";
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">FECHA</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"> DE <input size=\"10\" type=\"text\" class=\"input-text\" name=\"fecha_fin\" value=\"".$_REQUEST['fecha_ini']."\"><sub>".ReturnOpenCalendario("forma1","fecha_ini","-")."</sub> A <input size=\"10\" type=\"text\" class=\"input-text\" name=\"fecha_fin\" value=\"".$_REQUEST['fecha_ini']."\"><sub>".ReturnOpenCalendario("forma1","fecha_ini","-")."</sub></td>";
    $this->salida.="	</tr>";
		$this->salida.="</table><br>";*/
		
		$this->salida.="	<table border=\"0\" width=\"70%\" class=\"modulo_table_list\" align=\"center\" cellspacing=\"1\">";    
    $this->salida.="	<tr><td colspan=\"8\" width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">BUSQUEDA DEL PROFESIONAL</td></tr>";
    $this->salida.="	<tr>";
		$this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">NOMBRE</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"40\" type=\"text\" class=\"input-text\" name=\"nombreProf\" value=\"".$_REQUEST['nombreProf']."\"></td>";
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">UID</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"uidProf\" value=\"".$_REQUEST['uidProf']."\"></td>";
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">LOGIN</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"loginProf\" value=\"".$_REQUEST['loginProf']."\"></td>";
    $this->salida.="	</tr>";
    $this->salida.="	<tr>";    
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">TIPO ID</td>";
    $this->salida.="    <td class=\"modulo_list_claro\"><select name=\"TipoIdProf\" class=\"select\">";
		$this->salida .=" 		<option value=\"\">--SELECCIONE--</option>";
    $tipos=$this->TiposTerceros();
    foreach($tipos as $value=>$titulo)
		{
      if($value==$_REQUEST['TipoIdProf'])
			{
        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
      }
			else
			{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
    $this->salida .= "    </select></td>";
    $this->salida .= "    <td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">IDENTIFICACION</td>";
    $this->salida .= "    <td class=\"modulo_list_claro\"><input size=\"20\" type=\"text\" class=\"input-text\" name=\"IdProf\" value=\"".$_REQUEST['IdProf']."\"></td>";    
    $this->salida .= "    <td align=\"center\" class=\"modulo_list_claro\" colspan=\"2\"><input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"BUSCAR\">&nbsp;&nbsp;<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"LIMPIAR\" onclick=\"LimpiarCampos(this.form);\"></td>";
    $this->salida .= "	</tr>";
    $this->salida .= "</table>";
		$this->salida .= "</form>";
		$this->salida .= "</div>";

		if($_REQUEST['nombreProf'] || $_REQUEST['uidProf'] || $_REQUEST['loginProf'] || $_REQUEST['IdProf'])
		{
			$profesionales=$this->BusquedaProfesionales($_REQUEST['nombreProf'],$_REQUEST['uidProf'],$_REQUEST['loginProf'],$_REQUEST['TipoIdProf'],$_REQUEST['IdProf']);
			if($profesionales)
			{
				$actionPr=ModuloGetURL('app','OrdenesdePagos','user','FrmVisualizarOrdenesCancelacion',array('nombreProf'=>$_REQUEST['nombreProf'],'uidProf'=>$_REQUEST['uidProf'],'loginProf'=>$_REQUEST['loginProf'],'IdProf'=>$_REQUEST['IdProf'],'TipoIdProf'=>$_REQUEST['TipoIdProf'],'Profesional'=>$_REQUEST['Profesional']));

				$this->salida .= "<form name=\"formasel\" action=\"$actionPr\" method=\"post\">";
				$this->salida .= "	<br><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";    
				$this->salida .= "    <tr>";
				$this->salida .= "    	<td width=\"20\" class=\"modulo_table_list_title\" align=\"center\">IDENTIFICACION</td>";
				$this->salida .= "    	<td width=\"35\" class=\"modulo_table_list_title\" align=\"center\">NOMBRE</td>";
				$this->salida .= "    	<td width=\"20\" class=\"modulo_table_list_title\" align=\"center\">UID</td>"; 
				$this->salida .= "			<td width=\"20\" class=\"modulo_table_list_title\" align=\"center\">LOGIN</td>"; 
				$this->salida .= "			<td width=\"5\" class=\"modulo_table_list_title\">ELEGIR</td>"; 
				$this->salida .= "    </tr>";    
				for($i=0;$i<sizeof($profesionales);$i++)
				{
					$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida .= "	<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";        
					$this->salida .= "		<td class=\"label\">".$profesionales[$i]['tipo_tercero_id']." ".$profesionales[$i]['tercero_id']."</td>";        
					$this->salida .= "		<td class=\"label\">".$profesionales[$i]['nombre']."</td>"; 
					$this->salida .= "		<td class=\"label\">".$profesionales[$i]['usuario_id']."</td>"; 
					$this->salida .= "		<td class=\"label\">".$profesionales[$i]['usuario']."</td>";
					$this->salida .= "		<td align=\"center\" class=\"label\"><input type=\"radio\" name=\"Profesional\" value=\"".$profesionales[$i]['tipo_tercero_id']."||//".$profesionales[$i]['tercero_id']."||//".$profesionales[$i]['nombre']."\" onclick=\"RadioSelecionado('".$profesionales[$i]['tipo_tercero_id']."||//".$profesionales[$i]['tercero_id']."||//".$profesionales[$i]['nombre']."')\"></td>";
					$this->salida .= "	</tr>"; 
					$y++;
				}
				$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida .= "		<td align=\"right\" colspan=\"6\">";
				$this->salida .= "			<input type=\"submit\" class=\"input-submit\" name=\"seleccionar\" value=\"SELECCIONAR\">";
				$this->salida .= "		</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
				$this->salida .= "</form>";
			}
			else
			{
				$this->salida .="<center><label class=\"label_error\">NO SE ENCONTRARON REGISTROS DEL PROFESIONAL</label></center><br>";
			}
		}
		
		$Paginador=new ClaseHTML();
		
		$accionPaginador = ModuloGetURL('app','OrdenesdePagos','user','FrmOPCancelacion',$_REQUEST);
		$this->salida .= "".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$accionPaginador,$this->limit);
		
		$actionV=ModuloGetURL('app','OrdenesdePagos','user','FrmMenu');
		$this->salida .= "<form name=\"forma_vol\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= "	<br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";    
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"volver1\" value=\"VOLVER\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida.= "<script>\n";
		$this->salida.= " var prof='';\n";
		
		$this->salida.= "function RadioSelecionado(valor)\n";
		$this->salida.= "{\n";
		$this->salida.= "		prof=valor;\n";
		$this->salida.= "}\n";
		
		$this->salida.= "</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
    return true;
	}
	
	function FrmVisualizarOrdenesCancelacion()
	{
		$this->salida .= ThemeAbrirTabla('CANCELACION ORDENES DE PAGO');
		
		list($TipoidProf,$Prof,$nomPro)=explode("||//",$_REQUEST['Profesional']);
		
		SessionSetVar("TipoIdProf",$TipoidProf);
		SessionSetVar("IdProf",$Prof);
		
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("RemoteScripting");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");
		
		$this->SetXajax(array("Noseleccionar","Cancelar"),"app_modules/OrdenesdePagos/RemoteXajax/OrdenFacturaVouchers.php");
		global $xajax;
		$xajax->setFlag("debug",false);
		
		$this->salida.= "	<center><div id=\"error\" class=\"label_error\"></div></center>\n";
		$b=true;
		$capas="var capas1 = new Array(";
		$listado_ordenes=$this->GetOrdenesdePagoTotal($TipoidProf,$Prof,'','','','','',1);
		if($listado_ordenes)
		{
			foreach($listado_ordenes as $key=>$valor)
			{
				$salida1="";
				$salida="";
				$i=0;
				foreach($valor as $key1=>$valor1)
				{
					$salida1= "	<table align=\"center\" width=\"100%\" border=\"0\">\n";
					$salida1.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
					$salida1.= "			<td width=\"10%\">FACTURA</td>";
					$salida1.= "			<td width=\"10%\">VOUCHER</td>";
					$salida1.= "			<td width=\"20%\">CARGO</td>";
					$salida1.= "			<td width=\"10%\">RECIBO</td>";
					$salida1.= "			<td width=\"15%\">PACIENTE</td>";
					$salida1.= "			<td width=\"15%\">PLAN</td>";
					$salida1.= "			<td width=\"10%\">VALOR VOUCHER</td>";
					$salida1.= "			<td width=\"10%\">VALOR FACTURA</td>";
					$salida1.= "    </tr>";
					
					foreach($valor1 as $key2=>$valor2)
					{
						$j=0;
						$a=true;
						foreach($valor2 as $key3=>$valor3)
						{
							if($j % 2 == 0)
							{
								$estilo='modulo_list_oscuro';
							}
							else
							{
								$estilo='modulo_list_claro';
							}
							
							$salida1.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
							$salida1.= "			<td>".$valor3['numero_factura_id']."</td>";
							$salida1.= "			<td>".$valor3['prefijo_v']." - ".$valor3['numero_v']."</td>";
							$salida1.= "			<td>".$valor3['desc_cargo']."</td>";
							$salida1.= "			<td>".$valor3['numero_recibo']."</td>";
							$salida1.= "			<td>".$valor3['nombre_paciente']."</td>";
							$salida1.= "			<td>".$valor3['plan_descripcion']."</td>";
							$salida1.= "			<td align=\"right\"> $ ".FormatoValor($valor3['valor_real'])."</td>";
							if($a)
							{
								$salida1.= "			<td align=\"right\" rowspan=\"".sizeof($valor2)."\"> $ ".FormatoValor($valor3['valor'])."</td>";
								$a=false;
							}
							$salida1.= "		</tr>";
								
							$j++;
						}
					}
					$salida1.= "	</table>";
					$salida.= "	<div id=\"capilla$i\">\n";
					$salida.= "	<table align=\"center\" width=\"100%\" border=\"0\">\n";
					$salida.= "		<tr>\n";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"3%\" align=\"left\"><a href=\"javascript:showhide1('orden$i');\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a></td>";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"15%\" align=\"left\">ORDEN DE PAGO</td>";
					$salida.= "				<td class=\"modulo_list_claro\" width=\"15%\" align=\"left\">$key1</td>";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"15%\" align=\"left\">FECHA</td>";
					$salida.= "				<td class=\"modulo_list_claro\" width=\"20%\" align=\"left\">".$valor1[$key2][$key3]['fecha']."</td>";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"15%\" align=\"left\">VALOR</td>";
					$salida.= "				<td class=\"modulo_list_claro\" width=\"27%\" align=\"left\"> $ ".FormatoValor($valor1[$key2][$key3]['valor_total'])."</td>";
					$salida.="				<td class=\"modulo_list_claro\" align=\"center\" width=\"20%\"><input type=\"checkbox\" name=\"cancelacion\" value=\"$key1\" onclick=\"SelectCancelOP('".$valor1[$key2][$key3]['empresa_id']."','$key1',this.checked,'capilla$i');\"></td>";
					$salida.= "		</tr>\n";
					$salida.= "	</table>\n";
					$salida.= "	</div>\n";
					$salida.= "	<div id=\"orden$i\" style=\"display:none\">\n";
					$salida.= "	$salida1";
					$salida.= "	</div>\n";
					
					$b? $capas.="'orden$i'":$capas.=",'orden$i'";
					$b=false;
					
					$i++;
				}
				
				$salida0 .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
				$salida0 .= "		<tr align=\"center\">";
				$salida0 .= "			<td class=\"modulo_list_oscuro\" width=\"100%\">";
				$salida0 .= "				<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
				$salida0 .= "					<tr align=\"center\">";
				$salida0 .= "						<td width=\"20%\" class=\"modulo_table_list_title\">IDENTIFICACION</td>";
				$salida0 .= "						<td width=\"20%\" class=\"modulo_list_claro\">$key</td>";
				$salida0 .= "    				<td width=\"20%\" class=\"modulo_table_list_title\">PROFESIONAL</td>"; 
				$salida0 .= "						<td width=\"25%\" class=\"modulo_list_claro\">".$listado_ordenes[$key][$key1][$key2][$key3]['nombre']."</td>";
				$salida0 .= "					</tr>";
				$salida0 .= "				</table>";
				$salida0 .= "				$salida";
				$salida0 .= "			</td>";
				$salida0 .= "		</tr>";
				$salida0 .= "	</table>";
			}
			$this->salida.=" $salida0";
		}
		$this->salida .= "    <input type=\"hidden\" id=\"ordenpago\" value=\"\">";
		
		$this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"right\">";    
		$this->salida .= "    <input type=\"button\" class=\"input-submit\" name=\"Cancelar\" value=\"CANCELADO\" onclick=\"LlamarFucion();\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "	</table><br>";
		
		$this->salida .= "	<script>\n";
		$this->salida .= "	$capas);\n";
		$this->salida .= " var cancelacion='';\n";
		
		$this->salida .= "	function showhide1(Seccion)\n";
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
		$this->salida .= "	</script>\n";
		
		
		$this->salida .= "<div id=\"pagadas\">";
		
		$b2=true;
		$capas2="var capas2 = new Array(";
		$listado_ordenes=$this->GetOrdenesdePagoTotal($TipoidProf,$Prof,'','','','','',1,1);
		$salida0="";
		if($listado_ordenes)
		{
			foreach($listado_ordenes as $key=>$valor)
			{
				$salida1="";
				$salida="";
				$i=0;
				foreach($valor as $key1=>$valor1)
				{
					$salida1= "	<table align=\"center\" width=\"100%\" border=\"0\">\n";
					$salida1.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
					$salida1.= "			<td width=\"10%\">FACTURA</td>";
					$salida1.= "			<td width=\"10%\">VOUCHER</td>";
					$salida1.= "			<td width=\"20%\">CARGO</td>";
					$salida1.= "			<td width=\"10%\">RECIBO</td>";
					$salida1.= "			<td width=\"15%\">PACIENTE</td>";
					$salida1.= "			<td width=\"15%\">PLAN</td>";
					$salida1.= "			<td width=\"10%\">VALOR VOUCHER</td>";
					$salida1.= "			<td width=\"10%\">VALOR FACTURA</td>";
					$salida1.= "    </tr>";
					
					foreach($valor1 as $key2=>$valor2)
					{
						$j=0;
						$a=true;
						foreach($valor2 as $key3=>$valor3)
						{
							if($j % 2 == 0)
							{
								$estilo='modulo_list_oscuro';
							}
							else
							{
								$estilo='modulo_list_claro';
							}
							
							$salida1.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
							$salida1.= "			<td>".$valor3['numero_factura_id']."</td>";
							$salida1.= "			<td>".$valor3['prefijo_v']." - ".$valor3['numero_v']."</td>";
							$salida1.= "			<td>".$valor3['desc_cargo']."</td>";
							$salida1.= "			<td>".$valor3['numero_recibo']."</td>";
							$salida1.= "			<td>".$valor3['nombre_paciente']."</td>";
							$salida1.= "			<td>".$valor3['plan_descripcion']."</td>";
							$salida1.= "			<td align=\"right\"> $ ".FormatoValor($valor3['valor_real'])."</td>";
							if($a)
							{
								$salida1.= "			<td align=\"right\" rowspan=\"".sizeof($valor2)."\"> $ ".FormatoValor($valor3['valor'])."</td>";
								$a=false;
							}
							$salida1.= "		</tr>";
								
							$j++;
						}
					}
					$salida1.= "	</table>";
					$salida.= "	<div id=\"capillaC$i\">\n";
					$salida.= "	<table align=\"center\" width=\"100%\" border=\"0\">\n";
					$salida.= "		<tr>\n";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"3%\" align=\"left\"><a href=\"javascript:showhide2('ordenC$i');\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a></td>";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"15%\" align=\"left\">ORDEN DE PAGO</td>";
					$salida.= "				<td class=\"modulo_list_claro\" width=\"15%\" align=\"left\">$key1</td>";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"15%\" align=\"left\">FECHA</td>";
					$salida.= "				<td class=\"modulo_list_claro\" width=\"20%\" align=\"left\">".$valor1[$key2][$key3]['fecha']."</td>";
					$salida.= "				<td class=\"modulo_table_list_title\" width=\"15%\" align=\"left\">VALOR</td>";
					$salida.= "				<td class=\"modulo_list_claro\" width=\"27%\" align=\"left\"> $ ".FormatoValor($valor1[$key2][$key3]['valor_total'])."</td>";
					$salida.= "		</tr>\n";
					$salida.= "	</table>\n";
					$salida.= "	</div>\n";
					$salida.= "	<div id=\"ordenC$i\" style=\"display:none\">\n";
					$salida.= "	$salida1";
					$salida.= "	</div>\n";
					
					$b2? $capas2.="'ordenC$i'":$capas2.=",'ordenC$i'";
					$b2=false;
					
					$i++;
				}
				
				$salida0 .= " <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
				$salida0 .= "		<tr align=\"center\" class=\"modulo_table_list_title\">";
				$salida0 .= "			<td>ORDENES DE PAGO PAGADAS</td>";
				$salida0 .= "		</tr>";
				$salida0 .= "		<tr align=\"center\">";
				$salida0 .= "			<td class=\"modulo_list_oscuro\" width=\"100%\">";
				$salida0 .= "				<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
				$salida0 .= "					<tr align=\"center\">";
				$salida0 .= "						<td width=\"20%\" class=\"modulo_table_list_title\">IDENTIFICACION</td>";
				$salida0 .= "						<td width=\"20%\" class=\"modulo_list_claro\">$key</td>";
				$salida0 .= "    				<td width=\"20%\" class=\"modulo_table_list_title\">PROFESIONAL</td>"; 
				$salida0 .= "						<td width=\"25%\" class=\"modulo_list_claro\">".$listado_ordenes[$key][$key1][$key2][$key3]['nombre']."</td>";
				$salida0 .= "					</tr>";
				$salida0 .= "				</table>";
				$salida0 .= "				$salida";
				$salida0 .= "			</td>";
				$salida0 .= "		</tr>";
				$salida0 .= "	</table><br>";
			}
		
			$this->salida.=" $salida0";
		}
		
		$this->salida .= "</div>";
		
		$actionV=ModuloGetURL('app','OrdenesdePagos','user','FrmOPCancelacion');
		$this->salida .= "<form name=\"forma_vol\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= "	<br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";    
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"volver1\" value=\"VOLVER\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "	<script>\n";
		$this->salida .= "	$capas2);\n";
		
		$this->salida .= "	function showhide2(Seccion)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		for(i=0; i<capas2.length; i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			e = xGetElementById(capas2[i]);\n";
		$this->salida .= "			if(capas2[i] != Seccion)\n";
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
		
		$this->salida .= "	function ReemOrden(op)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		cancelacion=xGetElementById('ordenpago').value;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function SelectCancelOP(empresa_id,ordenpago,x,capa)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		if(x==true)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(!cancelacion)\n";
		$this->salida .= "				cancelacion+=empresa_id+'-'+ordenpago+'-'+capa;\n";
		$this->salida .= "			else\n";
		$this->salida .= "				cancelacion+='__'+empresa_id+'-'+ordenpago+'-'+capa;\n";
		$this->salida .= "		}\n";
		$this->salida .= "		else\n";
		$this->salida .= "		{\n";
		$this->salida .= "			xajax_Noseleccionar(cancelacion,empresa_id,ordenpago);\n";
		$this->salida .= "		}\n";
		
		$this->salida .= "	}\n";
		
		$this->salida .= "	function LlamarFucion()\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_Cancelar(cancelacion);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
    return true;
	}
	
}//fin clase user
?>
