<?php

/**
 * $Id: app_Voucher_FacturasProfesionales_userclasses_HTML.php,v 1.9 2007/04/09 16:10:21 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Inventario del Sistema
 */

/**
*Contiene los metodos visuales para realizar la relacion de voucher de honorarios medicos con las facturas de los profesionales
*/


class app_Voucher_FacturasProfesionales_userclasses_HTML extends app_Voucher_FacturasProfesionales_user
{
	/**
	*Constructor de la clase app_Voucher_FacturasProfesionales_user_HTML
	*El constructor de la clase app_Voucher_FacturasProfesionales_user_HTML se encarga de llamar
	*a la clase app_Voucher_FacturasProfesionales_user que se encarga del tratamiento
	* de la logica del programa.
	*/

  function app_Voucher_FacturasProfesionales_user_HTML()
	{
		$this->salida='';
		$this->app_Voucher_FacturasProfesionales_user();
		return true;
	}
  /**
	* Function que muestra al usuario la diferentes opciones de empresas en las que puede trabajar	
	* @return boolean
	*/

	function FrmLogueoEmpresa(){

    $Empresas=$this->LogueoEmpresa();
		if(sizeof($Empresas)>0){
			$url[0]='app';
			$url[1]='Voucher_FacturasProfesionales';
			$url[2]='user';
			$url[3]='LlamaFormaMenu';
			$url[4]='datos_query';
			$this->salida .= gui_theme_menu_acceso("SELECCION DE EMPRESA",$Empresas[0],$Empresas[1],$url,ModuloGetURL('system','Menu'));
		}else{
      $mensaje = "EL USUARIO NO TIENE PERMISOS PARA ACCESAR A ESTE MODULO.";
			$titulo = "RELACION VOUCHER DE HONORARIOS CON FACTURAS";
			$boton = "";//REGRESAR
			$accion="";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		return true;
	}
  
  function Encabezado(){
    
      $this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr><td class=\"modulo_table_list_title\" align=\"center\"><b>EMPRESA</b></td>";    
      $this->salida .= "      <tr><td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['VOUCHER_FACTURAS']['NombreEmp']."</b></td>";    
      $this->salida .= "    </table><BR>";
    
    return true;
    
  }
	
  /**
  * Function que muestra al menu con la opciones que puede seleccionar para trabajar
  * @return boolean
  */
	function Menu(){

		$this->salida .= ThemeAbrirTabla('MENU');
		$actionMenu=ModuloGetURL('app','Voucher_FacturasProfesionales','user','main');
		$this->salida .= "    <form name=\"forma\" action=\"$actionMenu\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$action=ModuloGetURL('app','Voucher_FacturasProfesionales','user','RelacionarFactura');
		$actionP=ModuloGetURL('app','Voucher_FacturasProfesionales','user','ImpresionFactura');
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">&nbsp;</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action\"><b>RELACIONAR FACTURA</b></a></td></tr>";		
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$actionP\"><b>IMPRESION FACTURA</b></a></td></tr>";		
		$this->salida .= "			     </table><BR>";
    $this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"MENU\"></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }
  
  function FrmRelacionarFactura()
	{
    $this->salida .= ThemeAbrirTabla('DATOS PRINCIPALES');
		
		$this->salida .= "<script>";

    $this->salida .= "function mOvr(src,clrOver){";
    $this->salida .= "  src.style.background = clrOver;";
    $this->salida .= "}";
    $this->salida .= "function mOut(src,clrIn){";
    $this->salida .= "  src.style.background = clrIn;";
    $this->salida .= "}";

    $this->salida .= "</script>";
    $action=ModuloGetURL('app','Voucher_FacturasProfesionales','user','SeleccionarRelacionFactura');
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->Encabezado();
    $this->salida .= "    <table border=\"0\" width=\"50%\" align=\"center\">";    
    $this->salida .= "    <tr><td colspan=\"2\" align=\"center\">";
    $this->salida .=      $this->SetStyle("MensajeError");
    $this->salida .= "    </td></tr>";
    $this->salida .= "    <tr><td width=\"20%\" class=\"modulo_table_list_title\" align=\"center\">No. FACTURA</td><td width=\"30%\" class=\"modulo_list_claro\"><input type=\"input\" class=\"input-text\" name=\"NoFactura\" value=\"".$_REQUEST['NoFactura']."\" class=\"input-submit\" size=\"30\"></td></tr>";
    $this->salida .= "    </table><BR>";
    $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";    
    $this->salida .= "    <tr><td colspan=\"8\" width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">BUSQUEDA DEL PROFESIONAL</td></tr>";
    $this->salida .= "    <tr>";
    $this->salida .= "    <td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">NOMBRE</td>";
    $this->salida .= "    <td class=\"modulo_list_claro\"><input size=\"50\" type=\"text\" class=\"input-text\" name=\"nombreProf\" value=\"".$_REQUEST['nombreProf']."\"></td>";
    $this->salida .= "    <td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">UID</td>";
    $this->salida .= "    <td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"uidProf\" value=\"".$_REQUEST['uidProf']."\"></td>";
    $this->salida .= "    <td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">LOGIN</td>";
    $this->salida .= "    <td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"loginProf\" value=\"".$_REQUEST['loginProf']."\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr>";    
    $this->salida .= "    <td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">TIPO ID</td>";
    $this->salida .= "    <td class=\"modulo_list_claro\"><select name=\"TipoIdProf\" class=\"select\">";
    $tipos=$this->tipo_id_paciente();
    foreach($tipos as $value=>$titulo){
      if($value==$_REQUEST['TipoIdProf']){
        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
      }else{
        $this->salida .=" <option value=\"$value\">$titulo</option>";
      }
    }
    $this->salida .= "    </select></td>";        
    $this->salida .= "    <td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">IDENTIFICACION</td>";
    $this->salida .= "    <td class=\"modulo_list_claro\"><input size=\"20\" class=\"input-text\" type=\"text\" name=\"IdProf\" value=\"".$_REQUEST['IdProf']."\"></td>";    
    $this->salida .= "    <td align=\"center\" class=\"modulo_list_claro\" colspan=\"2\"><input type=\"submit\" class=\"input-submit\" name=\"Filtrar\" value=\"FILTRAR\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    </table><BR>";
    
    if($_REQUEST['nombreProf'] || $_REQUEST['uidProf'] || $_REQUEST['loginProf'] || $_REQUEST['IdProf'])
		{
      $profesionales=$this->BusquedaProfesionales($_REQUEST['nombreProf'],$_REQUEST['uidProf'],$_REQUEST['loginProf'],$_REQUEST['TipoIdProf'],$_REQUEST['IdProf']);
      $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\">";    
      $this->salida .= "    <tr>";
      $this->salida .= "    <td width=\"20\" class=\"modulo_table_list_title\" align=\"center\">IDENTIFICACION</td>";
      $this->salida .= "    <td class=\"modulo_table_list_title\" align=\"center\">NOMBRE</td>";
      $this->salida .= "    <td width=\"20\" class=\"modulo_table_list_title\" align=\"center\">UID</td>"; 
      $this->salida .= "    <td width=\"20\" class=\"modulo_table_list_title\" align=\"center\">LOGIN</td>"; 
      $this->salida .= "    <td width=\"5\" class=\"modulo_table_list_title\">&nbsp;</td>"; 
      $this->salida .= "    </tr>";    
      for($i=0;$i<sizeof($profesionales);$i++)
			{
        $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";        
        $this->salida .= "    <td>".$profesionales[$i]['tipo_tercero_id']." ".$profesionales[$i]['tercero_id']."</td>";        
        $this->salida .= "    <td>".$profesionales[$i]['nombre_tercero']."</td>"; 
        $this->salida .= "    <td>".$profesionales[$i]['usuario_id']."</td>"; 
        $this->salida .= "    <td>".$profesionales[$i]['usuario']."</td>";         
        $this->salida .= "    <td align=\"center\"><input type=\"radio\" id=\"radio_pro\" name=\"Profesional\" value=\"".$profesionales[$i]['tipo_tercero_id']."||//".$profesionales[$i]['tercero_id']."||//".$profesionales[$i]['nombre_tercero']."\"></td>";
        $this->salida .= "    </tr>"; $y++;
      }
      $this->salida .= "    <tr>";
      $this->salida .= "    <td colspan=\"5\" align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"Guardar\" value=\"SELECCIONAR\"></td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    </table>";
    }
       
    $this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";    
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\">";
    $this->salida .= "    </td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "    </form>";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }
	
	function FrmImpresionFactura($_REQUEST)
	{
    $this->salida .= ThemeAbrirTabla('BUSQUEDA');
    
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("RemoteScripting");
		$this->salida .=	"<script>\n";
		
		$this->salida .= "var profesio='';";
    
		$this->salida .= "	function mOvr(src,clrOver){\n";
    $this->salida .= "  	src.style.background = clrOver;\n";
    $this->salida .= "	}\n";
    
		$this->salida .= "	function mOut(src,clrIn){\n";
    $this->salida .= "  	src.style.background = clrIn;\n";
    $this->salida .= "	}\n";
		
		$this->salida .= "	function ObtenerPro(){\n";
		$this->salida .= "		capa.style.display='';\n";
		$this->salida .= "		document.forma_imp.Filtrar.focus();\n";
		$this->salida .= "		capa_pro.style.display='none';\n";
		$this->salida .= "		document.forma_imp.Profesional.value=profesio;\n";
		$this->salida .= "		profesional=jsrsArrayFromString( profesio, '||//' );\n";
    $this->salida .= "		document.getElementById('tdoc').innerHTML = profesional[0];\n";
		$this->salida .= "		document.getElementById('doc').innerHTML = profesional[1];\n";
		$this->salida .= "		document.getElementById('nom').innerHTML = profesional[2];\n";
		
    $this->salida .= "	}\n";
		
		$this->salida .= "function GetProfesional(prof)";
		$this->salida .= "{";
		$this->salida .= "	profesio=prof;";
		$this->salida .= "}";

    $this->salida .= "</script>\n";
		
		$action=ModuloGetURL('app','Voucher_FacturasProfesionales','user','ResultadoImpresion');
		$this->Encabezado();
		
		$this->salida .= "<form name=\"forma_imp\" action=\"$action\" method=\"post\">";
    $this->salida .= "	<table border=\"0\" width=\"50%\" align=\"center\">";    
    $this->salida .= "		<tr>";
		$this->salida .= "			<td colspan=\"2\" align=\"center\">";
    $this->salida .=      		$this->SetStyle("MensajeError");
    $this->salida .= "  		</td>";
		$this->salida .= "  	</tr>";
		$this->salida .= "	</table>";
		
		$fecha_ini=$_REQUEST['fecha_ini'];
		$fecha_fin=$_REQUEST['fecha_fin'];
		
		$this->salida.= "	<table border=\"0\" width=\"70%\" align=\"center\"  class=\"modulo_table_list\" cellspacing=\"1\" cellpadding=\"0\">";  
		$this->salida.= "		<tr>";
		$this->salida.= "			<td>";
		$this->salida.="					<table align=\"left\" border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"0\">";
		$this->salida.="						<tr height=\"25\">";
		$this->salida.="							<td class=\"modulo_table_list_title\" align=\"center\">No. FACTURA</td>";
		$this->salida.="							<td class=\"modulo_list_claro\"><input type=\"input\" name=\"NoFactura\" value=\"".$_REQUEST['NoFactura']."\" class=\"input-text\" size=\"30\"></td>";
		$this->salida.="						</tr>";
		$this->salida.="					</table>";
		$this->salida.= "			</td>";
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr>";
		$this->salida.= "			<td>";
		$this->salida.="					<table align=\"left\" border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"0\">";
		$this->salida.="						<tr height=\"25\">";
		$this->salida.="							<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">DESDE</td>";
		$this->salida.="							<td width=\"30%\" class=\"modulo_list_claro\"><input type=\"input\" name=\"fecha_ini\" value=\"".$fecha_ini."\" size=\"10\" class=\"input-text\" maxlength=\"10\" readonly><sub>".ReturnOpencalendario("forma_imp","fecha_ini","-")."</sub></td>";
    $this->salida.="							<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">HASTA</td>";
		$this->salida.="							<td width=\"30%\" class=\"modulo_list_claro\"><input type=\"input\" name=\"fecha_fin\" value=\"".$fecha_fin."\" class=\"input-text\" size=\"10\" maxlength=\"10\" readonly><sub>".ReturnOpencalendario("forma_imp","fecha_fin","-")."</sub></td>";
  	$this->salida.="						</tr>";
		$this->salida.="					</table>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_claro\">";
		$this->salida.= "			<td id=\"capa\" style=\"display:none\" colspan=\"4\">";
		$this->salida.= "				<table align=\"left\" border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"0\">";
		$this->salida.="					<tr height=\"25\">";
		$this->salida.="						<td class=\"modulo_table_list_title\">TIPO DOCUMENTO</td>";
		$this->salida.= "						<td class=\"modulo_list_claro\" id=\"tdoc\"> </td>";
		$this->salida.="						<td class=\"modulo_table_list_title\">DOCUMENTO</td>";
		$this->salida.= "						<td class=\"modulo_list_claro\" id=\"doc\"> </td>";
		$this->salida.="						<td class=\"modulo_table_list_title\">NOMBRE</td>";
		$this->salida.= "						<td class=\"modulo_list_claro\" id=\"nom\"> </td>";
		$this->salida.= "						<input type=\"hidden\" name=\"Profesional\">";
		$this->salida.="					</tr>";
		$this->salida.= "				</table>";
		$this->salida.= "			</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr>";
		$this->salida.="			<td class=\"modulo_list_claro\">";
		$this->salida.="    		<table border=\"0\" width=\"100%\"  align=\"center\" cellspacing=\"1\" cellpadding=\"0\">";   
		$this->salida.="						<td  align=\"center\"><input type=\"submit\" class=\"input-submit\"  name=\"Filtrar\" value=\"FILTRAR\"></td>";
		$this->salida.="				</table>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table><br>"; 
		 
		$this->salida.="<table border=\"0\" width=\"70%\" class=\"modulo_table_list\" align=\"center\" cellspacing=\"1\">";    
    $this->salida.="	<tr><td colspan=\"8\" width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">BUSQUEDA DEL PROFESIONAL</td></tr>";
    $this->salida.="	<tr>";
		$this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">NOMBRE</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"50\" type=\"text\" class=\"input-text\" name=\"nombreProf\" value=\"".$_REQUEST['nombreProf']."\"></td>";
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">UID</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"uidProf\" value=\"".$_REQUEST['uidProf']."\"></td>";
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">LOGIN</td>";
    $this->salida.="		<td class=\"modulo_list_claro\"><input size=\"10\" type=\"text\" class=\"input-text\" name=\"loginProf\" value=\"".$_REQUEST['loginProf']."\"></td>";
    $this->salida.="	</tr>";
    $this->salida.="	<tr>";    
    $this->salida.="		<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">TIPO ID</td>";
    $this->salida.="    <td class=\"modulo_list_claro\"><select name=\"TipoIdProf\" class=\"select\">";
    $tipos=$this->tipo_id_paciente();
    foreach($tipos as $value=>$titulo){
      if($value==$_REQUEST['TipoIdProf']){
        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
      }else{
        $this->salida .=" <option value=\"$value\">$titulo</option>";
      }
    }
    $this->salida .= "    </select></td>";        
    $this->salida .= "    <td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">IDENTIFICACION</td>";
    $this->salida .= "    <td class=\"modulo_list_claro\"><input size=\"20\" type=\"text\" class=\"input-text\" name=\"IdProf\" value=\"".$_REQUEST['IdProf']."\"></td>";    
    $this->salida .= "    <td align=\"center\" class=\"modulo_list_claro\" colspan=\"2\"><input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"BUSCAR\"></td>";
    $this->salida .= "	</tr>";
    
    if($_REQUEST['nombreProf'] || $_REQUEST['uidProf'] || $_REQUEST['loginProf'] || $_REQUEST['IdProf'])
		{
      $this->salida .= "	<tr>";
    	$this->salida .= "		<td colspan=\"6\" class=\"modulo_list_claro\" id=\"capa_pro\">";
			$profesionales=$this->BusquedaProfesionales($_REQUEST['nombreProf'],$_REQUEST['uidProf'],$_REQUEST['loginProf'],$_REQUEST['TipoIdProf'],$_REQUEST['IdProf']);
      $this->salida .= "	<br><table border=\"0\" width=\"100%\" align=\"center\">";    
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
        $this->salida .= "		<td>".$profesionales[$i]['tipo_tercero_id']." ".$profesionales[$i]['tercero_id']."</td>";        
        $this->salida .= "		<td>".$profesionales[$i]['nombre_tercero']."</td>"; 
        $this->salida .= "		<td>".$profesionales[$i]['usuario_id']."</td>"; 
        $this->salida .= "		<td>".$profesionales[$i]['usuario']."</td>";         
        $this->salida .= "		<td align=\"center\"><input type=\"radio\" name=\"ProfSel[]\" value=\"".$profesionales[$i]['tipo_tercero_id']."||//".$profesionales[$i]['tercero_id']."||//".$profesionales[$i]['nombre_tercero']."\" onclick=\"GetProfesional('".$profesionales[$i]['tipo_tercero_id']."||//".$profesionales[$i]['tercero_id']."||//".$profesionales[$i]['nombre_tercero']."');\"></td>";
        $this->salida .= "	</tr>"; $y++;
      }
			$this->salida .= "	<tr>";
			$this->salida .= "		<td align=\"right\" colspan=\"6\">";
			$this->salida .= "			<input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"SELECCIONAR\" onclick=\"ObtenerPro();\">";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
      $this->salida .= "	</table>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
    }
		$this->salida .= "</table>";
    $this->salida .= "</form>";
		
		$actionV=ModuloGetURL('app','Voucher_FacturasProfesionales','user','Menu');
		$this->salida .= "<form name=\"forma_vol\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= "	<br><table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";    
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\">";
    $this->salida .= "    </td></tr>";
    $this->salida .= "	</table>";
		$this->salida .= "</form>";
		
    $this->salida .= ThemeCerrarTabla();
		
    return true;
  }
	
	function FrmResultadoImpresion($busqueda,$NoFactura=null,$Profesional=null,$fecha_ini=null,$fecha_fin=null)
	{
    $this->salida .= ThemeAbrirTabla('VOUCHER HONORARIOS FACTURAS');
    
		$this->salida .= "<script language='javascript'>";
    $this->salida .= "	function mOvr(src,clrOver){";
    $this->salida .= "  	src.style.background = clrOver;";
    $this->salida .= "	}";
    $this->salida .= "	function mOut(src,clrIn){";
    $this->salida .= "  	src.style.background = clrIn;";
    $this->salida .= "	}";
    $this->salida .= "</script>";
		
		$permisoCrear=1;
    $permisoEliminar=1;
    $permisoImprimir=1;

		(list($tipoProf,$Prof,$nomProf)=explode('||//',$Profesional)); 
		
		$datos=$this->ObtenerVoucherAsociadosFactura($NoFactura,$Profesional,$fecha_ini,$fecha_fin);
		
		if($datos)
		{
			$b=true;
			foreach($datos as $key=>$valor)
			{
				$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
				if(!empty($fecha_ini) && !empty($fecha_fin) && $b)
				{
					$this->salida .= "	<tr>";
					$this->salida .= "		<td width=\"20%\" class=\"modulo_table_list_title\" align=\"center\">FECHA</td>";
					$this->salida .= "		<td width=\"30%\" class=\"modulo_list_claro\">DESDE <b>$fecha_ini</b> HASTA <b>$fecha_fin</b></td>";
					$this->salida .= "	</tr>";
					$b=false;
				}
				
				$this->salida .= "	<tr>";    
				$this->salida .= "		<td width=\"20%\" class=\"modulo_table_list_title\" align=\"center\">PROFESIONAL</td>";
				$this->salida .= "		<td width=\"30%\" class=\"modulo_list_claro\">".$key."</td>";
				$this->salida .= "	</tr>";
				foreach($valor as $key1=>$valor1)
				{
					$this->salida .= "	<tr>";
					$this->salida .= "		<td width=\"20%\" class=\"modulo_table_list_title\" align=\"center\">No. FACTURA</td>";
					$this->salida .= "		<td width=\"30%\" class=\"modulo_list_claro\">".$key1."</td>";
					$this->salida .= "	</tr>";
					
					$rep= new GetReports();
					$this->salida .= "    <tr><td colspan=\"2\" class=\"modulo_table_list_title\" align=\"center\">VOUCHER ASOCIADOS A LA FACTURA</td></tr>";
					$this->salida .= "    <tr><td width=\"90%\" colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\">";
					$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";    
					$this->salida .= "        <tr class=\"modulo_table_list_title\">";
					$this->salida .= "        <td>PF</td>";
					$this->salida .= "        <td>NUMERO</td>";
					$this->salida .= "        <td>CUENTA</td>";
					$this->salida .= "        <td>VALOR NOTA CREDITO</td>";
					$this->salida .= "        <td>VALOR NOTA DEBITO</td>";
					$this->salida .= "        <td>VALOR ACTUAL HONORARIO</td>";
					foreach($valor1 as $valor2)
					{
						$this->salida .= "        </tr>";
						$this->salida .= "        <tr class=\"modulo_list_claro\">";
						$this->salida .= "        <td>".$valor2['prefijo']."</td>";
						$this->salida .= "        <td>".$valor2['numero']."</td>";
						$this->salida .= "        <td>".$valor2['numerodecuenta']."</td>";
						$val1="0.00";
						if($valor2['valor_nc']>0)
							$val1=$valor2['valor_nc'];
						
						$val2="0.00";
						if($valor2['valor_nd']>0)
							$val2=$valor2['valor_nd'];
							
						$this->salida .= "        <td align=\"right\"> $ ".FormatoValor($val1)."</td>"; 
						$this->salida .= "        <td align=\"right\"> $ ".FormatoValor($val2)."</td>"; 
						$this->salida .= "        <td align=\"right\"> $ ".FormatoValor($valor2['valor_real'])."</td>";  
						  
						$this->salida .= "        </tr>";
					}
					$this->salida .= "        </table>";
					$this->salida .= "    </td></tr>";
				}
			}
			if($permisoImprimir)
			{
				//$mostrar=$rep->GetJavaReport('app','Voucher_FacturasProfesionales','FacVoucherHonorarios_Profesionales_html',array('NoFactura'=>$NoFactura,'Profesional'=>$valor2['tipo_id_profesional']."||//".$valor2['profesional_id']."||//".$valor2['nombre'],'fecha_ini'=>$fecha_ini,'fecha_fin'=>$fecha_fin),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
				$mostrar=$rep->GetJavaReport('app','Voucher_FacturasProfesionales','FacVoucherHonorarios_Profesionales_html',array('NoFactura'=>$NoFactura,'Profesional'=>$Profesional,'fecha_ini'=>$fecha_ini,'fecha_fin'=>$fecha_fin),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
				$nombre_funcion=$rep->GetJavaFunction();
				$this->salida .=$mostrar;
				$this->salida .= "          <tr><td colspan=\"4\" align=\"center\"><a class=\"label\" title=\"Imprimir\"  href=\"javascript:$nombre_funcion\"><b>IMPRIMIR</b>&nbsp;&nbsp;<img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"></td></tr>";            
			}
			$this->salida .= "    </table>";
		}
		else
		{
			$this->salida.="<center><label class=\"label_error\">NO SE ENCONTRARON REGISTROS DE LA BUSQUEDA<label></center><br>";
		}
    
		$actionV=ModuloGetURL('app','Voucher_FacturasProfesionales','user','ImpresionFactura',array("busqueda_por"=>$busqueda));
		$this->salida .= "<form name=\"forma_volver\" action=\"$actionV\" method=\"post\">";  
		$this->salida .= " <br><table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";    
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\">";
    $this->salida .= "    </td></tr>";
    $this->salida .= "</table>";
		$this->salida .= "</form>";
		
    $this->salida .= ThemeCerrarTabla();
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
  
  function SeleccionarVoucherHonorarios($NoFactura,$Profesional)
	{
    (list($tipoProf,$Prof,$nomProf)=explode('||//',$Profesional));  
    //$permisoCrear=SIIS_Perfiles::GetPermiso('Voucher_FacturasProfesionales','app','01','CREAR');
    //$permisoEliminar=SIIS_Perfiles::GetPermiso('Voucher_FacturasProfesionales','app','01','ELMINAR');
    //$permisoImprimir=SIIS_Perfiles::GetPermiso('Voucher_FacturasProfesionales','app','01','IMPRIMIR');
   
    
		
		
    $this->salida .= ThemeAbrirTabla('RELACIONAR VOUCHER DE HONORARIOS A LA FACTURA');          
    $action=ModuloGetURL('app','Voucher_FacturasProfesionales','user','TmpGuardarRelacionVoucherFactura',array('NoFactura'=>$NoFactura,'Profesional'=>$Profesional));
		
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "	<tr align=\"center\">";
		$this->salida .= "		<td class=\"label_error\">".$this->mensaje."</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table>";
		
		$this->mensaje="";
		
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->Encabezado();
    $this->salida .= "    <table border=\"0\" width=\"50%\" align=\"center\">";    
    $this->salida .= "    <tr>";
    $this->salida .= "    <td width=\"20%\" class=\"modulo_table_list_title\" align=\"center\">No. FACTURA</td>";
    $this->salida .= "    <td width=\"30%\" class=\"modulo_list_claro\">$NoFactura</td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr>";    
    $this->salida .= "    <td width=\"20%\" class=\"modulo_table_list_title\" align=\"center\">PROFESIONAL</td>";
    $this->salida .= "    <td width=\"30%\" class=\"modulo_list_claro\">$nomProf</td>";
    $this->salida .= "    </tr>";
    $datos=$this->ObtenerVoucherAsociadosFactura($NoFactura,$Profesional);
		
		$permisoEliminar=$this->PermisoE;
		$permisoCrear=1;
		$permisoImprimir=1;
		
		if($datos)
		{
      $rep= new GetReports();
      $this->salida .= "    <tr><td colspan=\"2\" class=\"modulo_table_list_title\" align=\"center\">VOUCHER ASOCIADOS A LA FACTURA</td></tr>";
      $this->salida .= "    <tr><td width=\"90%\" colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\">";
      $this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\">";    
      $this->salida .= "        <tr class=\"modulo_table_list_title\">";
      $this->salida .= "        <td>PF</td>";
      $this->salida .= "        <td>NUMERO</td>";
      $this->salida .= "        <td>CUENTA</td>";
			$this->salida .= "        <td>VALOR NOTA CREDITO</td>";
      $this->salida .= "        <td>VALOR NOTA DEBITO</td>";
			$this->salida .= "        <td>VALOR ACTUAL HONORARIO</td>";          
      if($permisoEliminar){
        $this->salida .= "        <td width=\"5%\">&nbsp;</td>";    
      }
      $this->salida .= "        </tr>";
			$valor_fact=0;
      for($i=0;$i<sizeof($datos);$i++)
			{
        $this->salida .= "        <tr class=\"modulo_list_claro\">";
        $this->salida .= "        <td>".$datos[$i]['prefijo']."</td>";
        $this->salida .= "        <td>".$datos[$i]['numero']."</td>";
        $this->salida .= "        <td>".$datos[$i]['numerodecuenta']."</td>";
				
				$val1="0.00";
				if($datos[$i]['valor_nc']>0)
					$val1=$datos[$i]['valor_nc'];
				
				$val2="0.00";
				if($datos[$i]['valor_nd']>0)
					$val2=$datos[$i]['valor_nd'];
					
				$this->salida .= "        <td align=\"right\"> $ ".FormatoValor($val1)."</td>"; 
				$this->salida .= "        <td align=\"right\"> $ ".FormatoValor($val2)."</td>"; 
				$this->salida .= "        <td align=\"right\"> $ ".FormatoValor($datos[$i]['valor_real'])."</td>"; 
				  
        if($permisoEliminar)
				{
          $accionElimina=ModuloGetURL('app','Voucher_FacturasProfesionales','user','EliminarVoucherFactura',array("prefijo"=>$datos[$i]['prefijo'],'numero'=>$datos[$i]['numero'],'NoFactura'=>$NoFactura,'Profesional'=>$Profesional));
          $this->salida .= "        <td><a href=\"$accionElimina\"><img title=\"Eliminar\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
        }
        $this->salida .= "        </tr>";
      	$valor_fact+=$datos[$i]['valor_real'];
			}
			$this->salida .= " <input type=\"hidden\" name=\"valor_fact\" value=\"$valor_fact\">";
			           
     	if($permisoEliminar)
				$this->salida .= "          <tr><td colspan=\"7\" align=\"right\"><input type=\"submit\" name=\"generar_cxp\" value=\"GENERAR CUENTA POR PAGAR\" class=\"input-submit\"></td></tr>";            
     
      if($permisoImprimir){
        $mostrar=$rep->GetJavaReport('app','Voucher_FacturasProfesionales','FacVoucherHonorarios_Profesionales_html',array('NoFactura'=>$NoFactura,'Profesional'=>$Profesional),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
        $nombre_funcion=$rep->GetJavaFunction();
        $this->salida .=$mostrar;
        $this->salida .= "          <tr><td colspan=\"7\" align=\"center\"><a class=\"Menu\" title=\"Imprimir\"  href=\"javascript:$nombre_funcion\"><b>IMPRIMIR</b>&nbsp;&nbsp;<img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"></td></tr>";            
      }
      $this->salida .= "        </table>";
      $this->salida .= "    </td></tr>";      
      $this->salida .= "    </table>";
    }
    $this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";    
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\">";
    
    $this->salida .= "    </td></tr>";
    $this->salida .= "    </table>";
    if($permisoCrear){
      $this->salida.= "  <BR><table class=\"normal_10\" align=\"center\" width=\"90%\">\n";
      $this->salida.= "  <tr>";
      $this->salida.= "  <td class=\"modulo_table_list_title\" align=\"center\">PF</td>";
      $this->salida.= "  <td class=\"modulo_list_claro\"><input type=\"text\" name=\"pffiltro\" value=\"".$_REQUEST['pffiltro']."\" size=\"15\" class=\"input-text\"></td>";
      $this->salida.= "  <td class=\"modulo_table_list_title\" align=\"center\">NUMERO</td>";
      $this->salida.= "  <td class=\"modulo_list_claro\"><input type=\"text\" name=\"numfiltro\" value=\"".$_REQUEST['numfiltro']."\" size=\"15\" class=\"input-text\"></td>";
      $this->salida.= "  <td class=\"modulo_table_list_title\" align=\"center\">FECHA</td>";
      $this->salida.= "  <td class=\"modulo_list_claro\"><input type=\"text\" name=\"fechaFil\" size=\"10\" readonly value=\"".$_REQUEST['fechaFil']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
      $this->salida .= " ".ReturnOpenCalendario('forma','fechaFil','/')."</td>";
      $this->salida.= "  <td class=\"modulo_list_claro\" align=\"center\"><input type=\"submit\" name=\"filtrar\" value=\"FILTRAR\" class=\"input-submit\"></td>";
      $this->salida.= "  </tr>\n";     
      $this->salida.= "  </table>\n";     
      $datos=$this->ObtenerVoucher($NoFactura,$Profesional,$_REQUEST['pffiltro'],$_REQUEST['numfiltro'],$_REQUEST['fechaFil']);
      if($datos){
        $this->salida.= "  <table class=\"normal_10\" align=\"center\" width=\"90%\">\n";
        $this->salida.= "  <tr class=\"modulo_table_list_title\"><td colspan=\"9\" align=\"center\">VOUCHER ASOCIADOS AL PROFESIONAL</td></tr>\n";
        $this->salida.= "  <tr class=\"modulo_table_list_title\">\n";
        $this->salida.= "    <td width=\"5%\">PF</td>\n";
        $this->salida.= "    <td width=\"7%\">NUM</td>\n";      
        $this->salida.= "    <td width=\"20%\">CARGO</td>\n";
        $this->salida.= "    <td width=\"10%\">FECHA</td>\n";
        $this->salida.= "    <td width=\"10%\">CUENTA</td>\n";
				$this->salida.= "    <td width=\"15%\">VALOR NOTA CREDITO</td>\n";
				$this->salida.= "    <td width=\"15%\">VALOR NOTA DEBITO</td>\n";
        $this->salida.= "    <td width=\"15%\">VALOR ACTUAL HONORARIO</td>\n";
        $this->salida.= "    <td width=\"5%\">&nbsp;</td>\n";
        $this->salida.= "  </tr>\n";    
        for($i=0;$i< sizeof($datos);$i++){          
          $this->salida.= "  <tr class=\"modulo_list_claro\">\n";
          $this->salida.= "    <td>".$datos[$i]['prefijo']."</td>\n";
          $this->salida.= "    <td>".$datos[$i]['numero']."</td>\n";
          $this->salida.= "    <td>".$datos[$i]['descripcion']."</td>\n";
          $this->salida.= "    <td align=\"center\">".$datos[$i]['fecha']."</td>\n";
          $this->salida.= "    <td>".$datos[$i]['numerodecuenta']."</td>\n";
          $val1="0.00";
					if($datos[$i]['valor_nc']>0)
						$val1=$datos[$i]['valor_nc'];
					
					$val2="0.00";
					if($datos[$i]['valor_nd']>0)
						$val2=$datos[$i]['valor_nd'];
					
					$this->salida.= "    <td align=\"right\"> $ ".FormatoValor($val1)."</td>\n";
					$this->salida.= "    <td align=\"right\"> $ ".FormatoValor($val2)."</td>\n";
					
					$this->salida.= "    <td align=\"right\"> $ ".FormatoValor($datos[$i]['valor_real'])."</td>\n";
          $this->salida.= "    <td align=\"center\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$datos[$i]['prefijo']."||//".$datos[$i]['numero']."\"></td>\n";
          $this->salida.= "  </tr>\n";
        }
        $this->salida.= "  <tr><td colspan=\"9\" align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"Seleccionar\" value=\"SELECCIONAR\"><td></tr>";
        $this->salida.= "  </table>\n";
      }
			else
			{
        $this->salida.= "  <table class=\"normal_10\" align=\"center\" width=\"90%\">\n";
        $this->salida.= "  <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON VOUCHER ASOCIADOS AL PROFESIONAL</td></tr>\n";
        $this->salida.= "  </table>\n";
      }
    }
    $this->salida .= "    </form>";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }
}//fin clase user
?>