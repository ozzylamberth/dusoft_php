<?php

/**
 * $Id: app_Pagares_userclasses_HTML.php,v 1.7 2006/09/20 15:29:32 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_Pagares_userclasses_HTML extends app_Pagares_user
{
	/**
	*Constructor
	*/

  function app_Pagares_user_HTML()
	{
				$this->salida='';
				$this->app_Pagares_user();
				return true;
	}


  function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}
	
  	function FormaPrincipalPagares()
  	{
			$this->salida .= ThemeAbrirTabla('PAGARES CUENTA No. '.$_SESSION['PAGARES']['PACIENTES']['Cuenta']);
			$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$_SESSION['PAGARES']['PACIENTES']['PlanId'],'TipoId'=>$_SESSION['PAGARES']['PACIENTES']['TipoId'],'PacienteId'=>$_SESSION['PAGARES']['PACIENTES']['PacienteId'],'Ingreso'=>$_SESSION['PAGARES']['PACIENTES']['Ingreso'],'Nivel'=>$_SESSION['PAGARES']['PACIENTES']['Nivel'],'Fecha'=>$_SESSION['PAGARES']['PACIENTES']['FechaC']));
			$this->ReturnMetodoExterno('app','Facturacion','user','LlamaTotalesCuenta',array('Cuenta'=>$_SESSION['PAGARES']['PACIENTES']['Cuenta']));
			$this->salida .= "           </table>";	
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "           </table>";				
			$datos=$this->BuscarPagaresCuenta($_SESSION['PAGARES']['PACIENTES']['Cuenta']);
			if(!empty($datos))
			{
					IncludeLib('funciones_admision');
					$this->salida.=" <table border=\"0\" width=\"90%\" align=\"center\">";
					$this->salida.=" <tr><td><fieldset><legend class=\"field\">PAGARES DE LA CUENTA </legend>";
					$this->salida.="<br><table  align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list_title \">";
					$this->salida.="<tr class=\"modulo_table_list_title \">";
					$this->salida.="  <td width=\"10%\" nowrap>Pagare</td>";
					$this->salida.="  <td width=\"12%\" nowrap>Valor</td>";
					$this->salida.="  <td width=\"10%\" nowrap>Vencimiento</td>";
					$this->salida.="  <td width=\"15%\" nowrap>Forma Pago</td>";
					$this->salida.="  <td width=\"13%\" nowrap>Fecha Creacion</td>";
					$this->salida.="  <td width=\"15%\" nowrap>Usuario</td>";
					$this->salida.="  <td width=\"7%\" nowrap></td>";
					$this->salida.="  <td width=\"7%\" nowrap></td>";
					$this->salida.="  <td width=\"7%\" nowrap></td>";
					$this->salida.="</tr>";
					$reporte= new GetReports();					
					for($i=0;$i<sizeof($datos);$i++)
					{
							$this->salida.="<tr>";
							$this->salida.="  <td class=\"modulo_list_claro \">".$datos[$i][prefijo]."".$datos[$i][numero]."</td>";
							$this->salida.="  <td class=\"modulo_list_claro \">".FormatoValor($datos[$i][valor])."</td>";
							$this->salida.="  <td class=\"modulo_list_claro \">".$datos[$i][vencimiento]."</td>";
							$this->salida.="  <td class=\"modulo_list_claro \">".$datos[$i][formapago]."</td>";
							$this->salida.="  <td class=\"modulo_list_claro \">".FechaStamp($datos[$i][fecha_registro])." ".HoraStamp($datos[$i][fecha_registro])."</td>";
							$this->salida.="  <td class=\"modulo_list_claro \">".$datos[$i][nombre]."</td>";
							$mostrar=$reporte->GetJavaReport('app','Pagares','Pagare',array('empresa'=>$datos[$i][empresa_id],'prefijo'=>$datos[$i][prefijo],'numero'=>$datos[$i][numero]),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							$funcion=$reporte->GetJavaFunction();
							$this->salida .=$mostrar;												
							$this->salida.="  <td class=\"modulo_list_claro \"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"IMPRIMIR\"></a></td>";
							$accionM=ModuloGetURL('app','Pagares','user','LlamarModificarPagare',array('vector'=>$datos[$i]));
							$this->salida.="  <td class=\"modulo_list_claro \"><a href=\"$accionM\">Modificar</a></td>";
							$accionA=ModuloGetURL('app','Pagares','user','LlamarAnulacionPagare',array('prefijo'=>$datos[$i][prefijo],'numero'=>$datos[$i][numero],'empresa'=>$datos[$i][empresa_id],'valor'=>FormatoValor($datos[$i][valor])));
							$this->salida.="  <td class=\"modulo_list_claro \"><a href=\"$accionA\">Anular</a></td>";
							$this->salida.="</tr>";		
					}
					unset($reporte);
					$this->salida.="</table>";
					$this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
					$this->salida.="</table>";
					$this->salida.="</fieldset></td></tr></table><br>";
			}
			//revisa si el paiente tiene otros pagares
			//$this->BuscarPagaresPaciente($_SESSION['PAGARES']['PACIENTES']['Cuenta']);
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list_claro\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= " <tr>";
      
      if($_REQUEST['SaldoC'] == 1)
        $saldo = $_REQUEST['SaldoC'];
      else
      {
        IncludeLib("funciones_facturacion");
        $saldo=SaldoCuentaPaciente($_SESSION['PAGARES']['PACIENTES']['Cuenta']);
			}
      
      if($saldo <= 0)
			{   	$this->salida .= "  <td align=\"center\" class=\"label_mark\">NO SE PUEDE CREAR PAGARE<BR>EL SALDO DE LA CUENTA ES $ $saldo</td>";		}
			else
			{
					$accion=ModuloGetURL('app','Pagares','user','LlamarPedirDatosPagare');
					$this->salida .= "  <td align=\"center\" class=\"label\"><a href=\"$accion\">CREAR PAGARE</a></td>";
			}
      
			$accion = "";
      if(SessionIsSetVar("ActionVolver_Pagares"))
        $accion = SessionGetVar("ActionVolver_Pagares");
      else
        $accion=ModuloGetURL('app','Pagares','user','Principal');
        
			$this->salida .= "    <td align=\"center\">\n";
      $this->salida .= "      <a href=\"".$accion."\" class=\"label_error\">VOLVER</a>\n";
      $this->salida .= "    </td>\n";
			$this->salida .= "  </tr>";			
			$this->salida .= "</table>\n";					
			$this->salida .= ThemeCerrarTabla();
			return true;					
    }
	
	function FormaPrueba()
	{
			$this->salida .= ThemeAbrirTabla('NOTAS MEDICAS');
			$accion=ModuloHCGetURL(500,'','','','');
			$this->salida .= "<IFRAME  border=\"1\" SRC='$accion' TITLE='HISTORIA CLINICA' width=\"100%\" height=\"600\">";	
			$this->salida .= "</IFRAME>	";	
			$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list_claro\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= " <tr>";
			$this->salida .= "  <td align=\"center\" class=\"label\">NOTA MEDICA</td>";						
			$this->salida .= " </tr>";				
			$this->salida .= " <tr>";
			$this->salida .= "  <td align=\"center\"><textarea cols=\"90\" rows=\"3\" class=\"textarea\" name=\"nota\"></textarea></td>";						
			$this->salida .= " </tr>";			
			$this->salida .= "</table>";				
			$this->salida .= ThemeCerrarTabla();
			return true;		
	}
	
	function FormaPedirDatosPagare()
	{
			$this->salida .= ThemeAbrirTabla('CREAR PAGARE CUENTA No. '.$_SESSION['PAGARES']['PACIENTES']['Cuenta']);	
			$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$_SESSION['PAGARES']['PACIENTES']['PlanId'],'TipoId'=>$_SESSION['PAGARES']['PACIENTES']['TipoId'],'PacienteId'=>$_SESSION['PAGARES']['PACIENTES']['PacienteId'],'Ingreso'=>$_SESSION['PAGARES']['PACIENTES']['Ingreso'],'Nivel'=>$_SESSION['PAGARES']['PACIENTES']['Nivel'],'Fecha'=>$_SESSION['PAGARES']['PACIENTES']['FechaC']));
			$this->ReturnMetodoExterno('app','Facturacion','user','LlamaTotalesCuenta',array('Cuenta'=>$_SESSION['PAGARES']['PACIENTES']['Cuenta']));
			$this->salida .= "           </table>";	
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "           </table>";	
			$accion=ModuloGetURL('app','Pagares','user','GuardarDatosInicialesPagare');
			$this->salida .= "            <form name=\"formadatos\" action=\"$accion\" method=\"post\">";
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"center\" colspan=\"2\" class=\"label_mark\">DATOS PARA LA GENERACION DEL PAGARE</td>";
			$this->salida .= "               </tr>";			
			$this->salida .= "               <tr>";
			if(empty($_REQUEST['valor']))
			{
					IncludeLib("funciones_facturacion");
					$_REQUEST['valor']=SaldoCuentaPaciente($_SESSION['PAGARES']['PACIENTES']['Cuenta']);
			}
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("valor")."\">VALOR: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"valor\" size=\"12\" value=\"".$_REQUEST['valor']."\"></td>";			
			$this->salida .= "               </tr>";
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("codigo")."\">CODIGO ALTERNO: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"codigo\" size=\"12\"  maxlength=\"10\" value=\"".$_REQUEST['codigo']."\"></td>";			
			$this->salida .= "               </tr>";			
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("vencimiento")."\">VENCIMIENTO: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"vencimiento\" size=\"12\" value=\"".$_REQUEST['vencimiento']."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";			
			$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('formadatos','vencimiento','/')."</td>";			
			$this->salida .= "               </tr>";	
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("pago")."\">FORMA PAGO: </td>";
			$this->salida .= "                  <td align=\"left\"><select name=\"pago\" class=\"select\">";	
			$cons = $this->TiposPagos();
			$this->salida .=" <option value=\"\">---Seleccione---</option>";
			for($i=0; $i<sizeof($cons); $i++)
			{
					if($_REQUEST['pago']==$cons[$i][tipo_forma_pago_id])
					{  $this->salida .=" <option value=\"".$cons[$i][tipo_forma_pago_id]."\" selected>".$cons[$i][descripcion]."</option>";	}
					else
					{  $this->salida .=" <option value=\"".$cons[$i][tipo_forma_pago_id]."\">".$cons[$i][descripcion]."</option>";	}
			}
			$this->salida .= "              </select></td></tr>";
			
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("observacion")."\">OBSERVACION: </td>";
			$this->salida .= "                  <td align=\"left\"><textarea cols=\"45\" rows=\"3\" class=\"textarea\" name=\"observacion\">".$_REQUEST['observacion']."</textarea></td>";			
			$this->salida .= "               </tr>";			
								
			$this->salida .= "           </table>";				
			$this->salida .= "			      <BR><table width=\"50%\" align=\"center\" border=0>";
			$this->salida .= "				       <tr><td align=\"center\" width=\"50%\"><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"ACEPTAR\"></td>";
			$this->salida .= "			     </form>";			
			$accion=ModuloGetURL('app','Pagares','user','BotonCancelar');		
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td></tr>";
			$this->salida .= "			     </form>";
			$this->salida .= "			     </table>";						
			$this->salida .= ThemeCerrarTabla();
			return true;	
	}
	
	function FormaPedirDatosResponsable()
	{
			$this->salida .= ThemeAbrirTabla('RESPONSABLES PAGARE CUENTA No. '.$_SESSION['PAGARES']['PACIENTES']['Cuenta']);		
			$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$_SESSION['PAGARES']['PACIENTES']['PlanId'],'TipoId'=>$_SESSION['PAGARES']['PACIENTES']['TipoId'],'PacienteId'=>$_SESSION['PAGARES']['PACIENTES']['PacienteId'],'Ingreso'=>$_SESSION['PAGARES']['PACIENTES']['Ingreso'],'Nivel'=>$_SESSION['PAGARES']['PACIENTES']['Nivel'],'Fecha'=>$_SESSION['PAGARES']['PACIENTES']['FechaC']));
			$this->ReturnMetodoExterno('app','Facturacion','user','LlamaTotalesCuenta',array('Cuenta'=>$_SESSION['PAGARES']['PACIENTES']['Cuenta']));
			$this->salida .= "           </table>";	
			global $VISTA;
			$ru='classes/BuscadorDestino/selectorCiudad.js';
			$rus='classes/BuscadorDestino/selector.php';
			$this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "           </table>";	
      
			if(!empty($_SESSION['PAGARES']['DATOS']['VECTOR']) AND empty($_SESSION['PAGARES']['MODIFICARTMP']))
			{
					$this->salida .= "            <table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
					$this->salida .= "               <tr>";
					$this->salida .= "                  <td align=\"center\" width=\"15%\">IDENTIFICACION</td>";
					$this->salida .= "                  <td align=\"center\" width=\"25%\">NOMBRE</td>";
					$this->salida .= "                  <td align=\"center\">PARENTESCO</td>";					
					$this->salida .= "                  <td align=\"center\">DIRECCION</td>";
					$this->salida .= "                  <td align=\"center\">OBSERVACION</td>";
					$this->salida .= "                  <td align=\"center\">DEUDOR</td>";
					$this->salida .= "                  <td align=\"center\"></td>";		
					$this->salida .= "                  <td align=\"center\"></td>";									
					$this->salida .= "               </tr>";
					foreach($_SESSION['PAGARES']['DATOS']['VECTOR'] as $k => $v)
					{
              $marca = "";
              if($v['deudor'] == "1") 
              {
                $this->sw_deudor = "X";
                $marca = "X";
              }
              $this->salida .= "               <tr>";
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\">".$v['tipoId']." ".$v['documento']."</td>";
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\">".$v['nombre']."</td>";					
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\">".$v['nomparentesco']."</td>";					
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\">".$v['direccion']."</td>";												
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\">".$v['observacion']."</td>";										
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\">".$marca."</td>";										
							$accionM=ModuloGetURL('app','Pagares','user','LlamarModificarDatosTmpResponsables',array('datos'=>$v));
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionM\"><img src=\"".GetThemePath()."/images/editar.png\" border='0' title=\"Modificar\"></a></td>";	
							$accionE=ModuloGetURL('app','Pagares','user','EliminarTmpResponsablesPagare',array('tipoId'=>$v['tipoId'],'documento'=>$v['documento']));
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionE\"><img src=\"".GetThemePath()."/images/elimina.png\" border='0' title=\"Eliminar\"></a></td>";	
							$this->salida .= "               </tr>";					
					}
					$this->salida .= "           </table>";							
			}			
			$accion=ModuloGetURL('app','Pagares','user','GuardarDatosResponsablesPagare');
      $this->salida .= "<table width=\"70%\" align=\"center\">\n";
      $this->salida .= "  <tr>\n";
      $this->salida .= "    <td>\n";
      $this->salida .= "      <fieldset class=\"fieldset\">\n";
      $this->salida .= "        <legend class=\"label_mark\">DATOS RESPONSABLE(S) DEL PAGARE</legend>\n";
			$this->salida .= "        <form name=\"forma\" action=\"$accion\" method=\"post\">\n";
			$this->salida .= "          <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">\n";
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"center\" colspan=\"2\" class=\"label_mark\"></td>";
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("tipoId")."\">*TIPO DOCUMENTO: </td>";
			$this->salida .= "                  <td align=\"left\"><select name=\"tipoId\" class=\"select\">";	
			$cons = $this->TiposTerceros();
			$this->salida .=" <option value=\"\">---Seleccione---</option>";
			for($i=0; $i<sizeof($cons); $i++)
			{
					if($_REQUEST['tipoId']==$cons[$i][tipo_id_tercero])
					{  $this->salida .=" <option value=\"".$cons[$i][tipo_id_tercero]."\" selected>".$cons[$i][descripcion]."</option>";	}
					else
					{  $this->salida .=" <option value=\"".$cons[$i][tipo_id_tercero]."\">".$cons[$i][descripcion]."</option>";	}
			}
			$this->salida .= "              </select></td>";						
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("documento")."\">*DOCUMENTO: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"documento\" size=\"15\" value=\"".$_REQUEST['documento']."\"></td>";			
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("nombre")."\">*NOMBRE: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"nombre\" size=\"30\" value=\"".$_REQUEST['nombre']."\"></td>";			
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("telefono")."\">*TELEFONO RESIDENCIA: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"telefono\" size=\"30\" value=\"".$_REQUEST['telefono']."\"></td>";			
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("celular")."\">CELULAR: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"celular\" size=\"30\" value=\"".$_REQUEST['celular']."\"></td>";			
			$this->salida .= "            </tr>\n";
			if(!$_REQUEST['pais'])
			{
					$Pais=GetVarConfigAplication('DefaultPais');
					$Dpto=GetVarConfigAplication('DefaultDpto');
					$Mpio=GetVarConfigAplication('DefaultMpio');
			}
			else
			{
					$Pais=$_REQUEST['pais'];
					$Dpto=$_REQUEST['dpto'];
					$Mpio=$_REQUEST['mpio'];
			}		
			$this->salida .= "            <tr>\n";
			$this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">* PAIS: </td>";
			$NomPais=$this->nombre_pais($Pais);
			$this->salida .= "      <td><input type=\"text\" name=\"npais\" value=\"$NomPais\" class=\"input-text\" readonly size=\"30\">";
			$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
			$this->salida .= "       <td>  </td>";
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">* DEPARTAMENTO: </td>";
			$NomDpto=$this->nombre_dpto($Pais,$Dpto);
			$this->salida .= "      <td><input type=\"text\" name=\"ndpto\" value=\"$NomDpto\" class=\"input-text\" readonly size=\"30\">";
			$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
			$this->salida .= "       <td>  </td>";
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "              <td class=\"".$this->SetStyle("mpio")."\">* CIUDAD: </td>\n";
			$NomCiudad=$this->nombre_ciudad($Pais,$Dpto,$Mpio);
			$this->salida .= "              <td>\n";
      $this->salida .= "                <input type=\"text\" name=\"nmpio\"  value=\"$NomCiudad\" class=\"input-text\" readonly size=\"30\">\n";
			$this->salida .= "                <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$this->salida .= "                <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">\n";
      $this->salida .= "              </td>\n";
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("direccion")."\">*DIRECCION RESIDENCIA: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"direccion\" size=\"30\" value=\"".$_REQUEST['direccion']."\"></td>";			
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("parentesco")."\">*PARENTESCO: </td>";
			$this->salida .= "                  <td align=\"left\"><select name=\"parentesco\" class=\"select\">";	
			$cons = $this->TiposParentescos();
			$this->salida .=" <option value=\"\">---Seleccione---</option>";
			for($i=0; $i<sizeof($cons); $i++)
			{		$p=explode('||',$_REQUEST['parentesco']);
					if($p[0]==$cons[$i][tipo_parentesco_id])
					{  $this->salida .=" <option value=\"".$cons[$i][tipo_parentesco_id]."||".$cons[$i][descripcion]."\" selected>".$cons[$i][descripcion]."</option>";	}
					else
					{  $this->salida .=" <option value=\"".$cons[$i][tipo_parentesco_id]."||".$cons[$i][descripcion]."\">".$cons[$i][descripcion]."</option>";	}
			}
			$this->salida .= "              </select></td>";
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("direccionT")."\">DIRECCION TRABAJO: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"direccionT\" size=\"30\" value=\"".$_REQUEST['direccionT']."\"></td>";			
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("telefonoT")."\">TELEFONO TRABAJO: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"telefonoT\" size=\"30\" value=\"".$_REQUEST['telefonoT']."\"></td>";			
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr>\n";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("observacion")."\">OBSERVACION: </td>";
			$this->salida .= "                  <td align=\"left\"><textarea cols=\"45\" rows=\"3\" class=\"textarea\" name=\"observacion\">".$_REQUEST['observacion']."</textarea></td>";			
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr class=\"label\">\n";
			$this->salida .= "              <td>PACIENTE: </td>\n";			
			$this->salida .= "              <td>\n";
      $this->salida .= "                <input type=\"checkbox\" name=\"paciente\" value=\"1\">\n";
      $this->salida .= "              </td>\n";	
			$this->salida .= "            </tr>\n";			
			$this->salida .= "            <tr class=\"label\">\n";      
			$this->salida .= "              <td colspan=\"2\" class=\"".$this->SetStyle("deudor")."\">*TIPO DE DEUDOR</td>\n";      
			$this->salida .= "            </tr>\n";
  		$this->salida .= "            <tr class=\"label\">\n";      
  		$this->salida .= "              <td colspan=\"2\">\n";
      if($this->sw_deudor == "")
      {
        $slec[$_REQUEST['deudor']] = "checked";
        $this->salida .= "                <input type=\"radio\" name=\"deudor\" value=\"1\" ".$slec[1].">DEUDOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        $this->salida .= "                <input type=\"radio\" name=\"deudor\" value=\"0\" ".$slec[0].">COODEUDOR\n";
		
			}
      else
      {
        $this->salida .= "                <input type=\"hidden\" name=\"deudor\" value=\"0\" >COODEUDOR\n";
      }
      
      $this->salida .= "              </td>\n";	      
  		$this->salida .= "            </tr>\n";	
      $this->salida .= "           </table>\n";	
			//botones			
			$this->salida .= "			      <BR><table width=\"50%\" align=\"center\" border=0>";
			if(!empty($_SESSION['PAGARES']['MODIFICAR']) OR !empty($_SESSION['PAGARES']['MODIFICARTMP']))
			{  $this->salida .= "				       <tr><td align=\"center\"><input class=\"input-submit\" name=\"Responsable\" type=\"submit\" value=\"MODIFICAR RESPONSABLE\"></td>";  }
			else
			{  $this->salida .= "				       <tr><td align=\"center\"><input class=\"input-submit\" name=\"Responsable\" type=\"submit\" value=\"CREAR RESPONSABLE\"></td>";  }			
			$this->salida .= "			     </form>";	
			if(!empty($_SESSION['PAGARES']['DATOS']['VECTOR']) AND empty($_SESSION['PAGARES']['MODIFICARTMP']))		
			{
					$accion=ModuloGetURL('app','Pagares','user','GuardarPagare');		
					$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Pagare\" value=\"CREAR PAGARE\"></td>";
					$this->salida .= "			     </form>";
			}
			$accion=ModuloGetURL('app','Pagares','user','BotonCancelar');		
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			$this->salida .= "			      </form>\n";
			$this->salida .= "			    </table>\n";
      $this->salida .= "      </fieldset>\n";      
      $this->salida .= "    </td>\n";
      $this->salida .= "  </tr>\n";
      $this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;	
	}
	
	function FormaConfirmaResponsable($datos,$tipoId,$id,$nombre)
	{		
			IncludeLib('funciones_admision');
			$this->SetJavaScripts('DatosPaciente');
			$this->salida .= ThemeAbrirTabla('RESPONSABLE PAGARE CUENTA No. '.$_SESSION['PAGARES']['PACIENTES']['Cuenta']);		
			$this->salida .= "<table width=\"85%\" align=\"center\" border=0>";
			$this->salida .= " <tr>";	
			$this->salida .= "   <td align=\"center\" class=\"label_mark\">$nombre  APARECE COMO RESPONSABLE DE LOS SIGUIENTES PAGARES: </td>";		
			$this->salida .= " </tr>";
			$this->salida .= "</table><br>";						
			$this->salida .= "<table width=\"90%\" align=\"center\" border=0 class=\"modulo_table_list_title \">";
			$this->salida.="<tr class=\"modulo_table_list_title \">";
			$this->salida.="  <td width=\"20%\" nowrap>Paciente</td>";
			$this->salida.="  <td width=\"10%\" nowrap>Pagare</td>";
			$this->salida.="  <td width=\"12%\" nowrap>Valor</td>";
			$this->salida.="  <td width=\"10%\" nowrap>Vencimiento</td>";
			$this->salida.="  <td width=\"15%\" nowrap>Forma Pago</td>";
			$this->salida.="  <td width=\"13%\" nowrap>Fecha Creacion</td>";
			$this->salida.="  <td width=\"15%\" nowrap>Obs. Responsable</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($datos);$i++)
			{
					$this->salida.="<tr>";
					$this->salida.="  <td class=\"modulo_list_claro \">".RetornarWinOpenDatosPaciente($datos[$i][tipo_id_paciente],$datos[$i][paciente_id],$datos[$i][nombre])."</td>";
					$this->salida.="  <td class=\"modulo_list_claro \">".$datos[$i][prefijo]."".$datos[$i][numero]."</td>";
					$this->salida.="  <td class=\"modulo_list_claro \">".FormatoValor($datos[$i][valor])."</td>";
					$this->salida.="  <td class=\"modulo_list_claro \">".$datos[$i][vencimiento]."</td>";
					$this->salida.="  <td class=\"modulo_list_claro \">".$datos[$i][formapago]."</td>";
					$this->salida.="  <td class=\"modulo_list_claro \">".FechaStamp($datos[$i][fecha_registro])." ".HoraStamp($datos[$i][fecha_registro])."</td>";
					$this->salida.="  <td class=\"modulo_list_claro \">".$datos[$i][observacion]."</td>";
					$this->salida.="</tr>";		
			}
			$this->salida .= "</table>";					
			//botones			
			$this->salida .= "			      <BR><table width=\"50%\" align=\"center\" border=0>";
			$this->salida .= "				       <tr>";
			$accion=ModuloGetURL('app','Pagares','user','FinValidarResponsable',array('tipoId'=>$tipoId,'id'=>$id));		
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Continuar\" value=\"CONTINUAR\"></td>";
			$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			$this->salida .= "			     </form>";
			$this->salida .= "			     </table>";		
			$this->salida .= ThemeCerrarTabla();
			return true;		
	}
	
	function FormaAnularPagare($prefijo,$numero,$valor,$empresa)
	{
			$this->salida .= ThemeAbrirTabla('ANULAR PAGARE DE LA CUENTA No. '.$_SESSION['PAGARES']['PACIENTES']['Cuenta']);		
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "           </table>";				
			$accion=ModuloGetURL('app','Pagares','user','AnularPagare',array('prefijo'=>$prefijo,'valor'=>$valor,'numero'=>$numero,'empresa'=>$empresa));		
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "<table width=\"50%\" align=\"center\" border=0>";
			$this->salida .= "	<tr>";
			$this->salida .= "	  <td colspan=\"2\" class=\"label_mark\" align=\"center\">VA A ANULAR EN PAGARE No. $prefijo$numero POR UN VALOR DE $ $valor</td>";				
			$this->salida .= "	</tr>";						
			$this->salida .= "	<tr>";					
			$this->salida .= "	  <td class=\"".$this->SetStyle("observacion")."\">OBSERVACION: </td>";					
			$this->salida .= "    <td align=\"left\"><textarea cols=\"45\" rows=\"3\" class=\"textarea\" name=\"observacion\"></textarea></td>";						
			$this->salida .= "	</tr>";		
			$this->salida .= "</table>";								
			//botones			
			$this->salida .= "			      <BR><table width=\"50%\" align=\"center\" border=0>";
			$this->salida .= "				       <tr>";
			$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Anular\" value=\"ANULAR\"></td>";
			$this->salida .= "			     </form>";			
			$accion=ModuloGetURL('app','Pagares','user','BotonCancelar');		
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			$this->salida .= "			     </form>";
			$this->salida .= "			     </table>";		
			$this->salida .= ThemeCerrarTabla();
			return true;		
	}
	
	function FormaModificarPagare($vector)
	{
			if(empty($_REQUEST['valor']))
			{   $_REQUEST['valor']=$vector['valor'];		}
			if(empty($_REQUEST['vencimiento']))
			{   $_REQUEST['vencimiento']=$vector['vencimiento'];		}
			if(empty($_REQUEST['pago']))
			{   $_REQUEST['pago']=$vector['tipo_forma_pago_id'];		}			
			if(empty($_REQUEST['codigo']))
			{   $_REQUEST['codigo']=$vector['codigo_alterno'];		}		
			if(empty($_REQUEST['observacion']))
			{   $_REQUEST['observacion']=$vector['observacion'];		}							
			$this->salida .= ThemeAbrirTabla('MODIFICAR PAGARE No. '.$vector['prefijo'].$vector['numero']);		
			$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$_SESSION['PAGARES']['PACIENTES']['PlanId'],'TipoId'=>$_SESSION['PAGARES']['PACIENTES']['TipoId'],'PacienteId'=>$_SESSION['PAGARES']['PACIENTES']['PacienteId'],'Ingreso'=>$_SESSION['PAGARES']['PACIENTES']['Ingreso'],'Nivel'=>$_SESSION['PAGARES']['PACIENTES']['Nivel'],'Fecha'=>$_SESSION['PAGARES']['PACIENTES']['FechaC']));
			$this->ReturnMetodoExterno('app','Facturacion','user','LlamaTotalesCuenta',array('Cuenta'=>$_SESSION['PAGARES']['PACIENTES']['Cuenta']));
			$this->salida .= "           </table>";	
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "           </table>";				
			$accion=ModuloGetURL('app','Pagares','user','ModificarDatosPagare',array('vector'=>$vector,'prefijo'=>$vector['prefijo'],'numero'=>$vector['numero'],'empresa'=>$vector['empresa_id']));
			$this->salida .= "            <form name=\"formadatos\" action=\"$accion\" method=\"post\">";
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"center\" colspan=\"2\" class=\"label_mark\">DATOS DEL PAGARE No. ".$vector['prefijo']."".$vector['numero']."</td>";
			$this->salida .= "               </tr>";			
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("valor")."\">VALOR: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"valor\" size=\"12\" value=\"".$_REQUEST['valor']."\"></td>";			
			$this->salida .= "               </tr>";
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("codigo")."\">CODIGO ALTERNO: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"codigo\" size=\"12\"  maxlength=\"10\" value=\"".$_REQUEST['codigo']."\"></td>";			
			$this->salida .= "               </tr>";			
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("vencimiento")."\">VENCIMIENTO: </td>";
			$this->salida .= "                  <td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"vencimiento\" size=\"12\" value=\"".$_REQUEST['vencimiento']."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";			
			$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('formadatos','vencimiento','/')."</td>";			
			$this->salida .= "               </tr>";	
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("pago")."\">FORMA PAGO: </td>";
			$this->salida .= "                  <td align=\"left\"><select name=\"pago\" class=\"select\">";	
			$cons = $this->TiposPagos();
			$this->salida .=" <option value=\"\">---Seleccione---</option>";
			for($i=0; $i<sizeof($cons); $i++)
			{
					if($_REQUEST['pago']==$cons[$i][tipo_forma_pago_id])
					{  $this->salida .=" <option value=\"".$cons[$i][tipo_forma_pago_id]."\" selected>".$cons[$i][descripcion]."</option>";	}
					else
					{  $this->salida .=" <option value=\"".$cons[$i][tipo_forma_pago_id]."\">".$cons[$i][descripcion]."</option>";	}
			}
			$this->salida .= "              </select></td></tr>";	
			
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"left\" class=\"".$this->SetStyle("observacion")."\">OBSERVACION: </td>";
			$this->salida .= "                  <td align=\"left\"><textarea cols=\"45\" rows=\"3\" class=\"textarea\" name=\"observacion\">".$_REQUEST['observacion']."</textarea></td>";			
			$this->salida .= "               </tr>";
										
			$this->salida .= "           </table>";				
			$this->salida .= "			      <BR><table width=\"50%\" align=\"center\" border=0>";
			$this->salida .= "				       <tr><td align=\"center\" width=\"50%\"><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"MODIFICAR DATOS\"></td>";
			$this->salida .= "			     </form>";						
			$accion=ModuloGetURL('app','Pagares','user','LlamarFormaModificarResponsablesPagare',array('vector'=>$vector));
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"MODIFICAR RESPONSABLES\"></td>";			
			$this->salida .= "			     </form>";							
			$accion=ModuloGetURL('app','Pagares','user','BotonCancelar');		
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td></tr>";
			$this->salida .= "			     </form>";
			$this->salida .= "			     </table>";			
			$this->salida .= ThemeCerrarTabla();
			return true;		
	}
	
	function FormaModificarResponsablesPagare()	
	{
			$this->salida .= ThemeAbrirTabla('RESPONSABLES PAGARE No. '.$_SESSION['PAGARES']['DATOS']['prefijo'].$_SESSION['PAGARES']['DATOS']['numero']);		
			global $VISTA;
			$ru='classes/BuscadorDestino/selectorCiudad.js';
			$rus='classes/BuscadorDestino/selector.php';
			$this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
			$this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "  </table>";
      $this->sw_deudor = "";
			if(!empty($_SESSION['PAGARES']['DATOS']['RESPONSABLES']))
			{
					$res=$_SESSION['PAGARES']['DATOS']['RESPONSABLES'];
					$this->salida .= "  <table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
					$this->salida .= "    <tr>\n";
					$this->salida .= "      <td align=\"center\" width=\"25%\">RESPONSABLE</td>\n";
					$this->salida .= "      <td align=\"center\">PARENTESCO</td>";					
					$this->salida .= "      <td align=\"center\">DIRECCION</td>";
					$this->salida .= "      <td align=\"center\">OBSERVACION</td>";
					$this->salida .= "      <td align=\"center\">DEUDOR</td>";
					$this->salida .= "      <td align=\"center\" width=\"5%\"></td>";	
					$this->salida .= "      <td align=\"center\" width=\"5%\"></td>";										
					$this->salida .= "    </tr>";
					for($i=0; $i<sizeof($res); $i++)
					{
              $marca = "";
              if($res[$i]['sw_deudor'] == "1")
              {
                $this->sw_deudor = "X";
                $marca = "X";
              }
							$this->salida .= "    <tr class=\"modulo_list_claro\">\n";
							$this->salida .= "      <td align=\"center\" >".$res[$i]['tipo_id_tercero']." ".$res[$i]['tercero_id']." - ".$res[$i]['nombre']."</td>";
							$this->salida .= "      <td align=\"center\" >".$res[$i]['descripcion']."</td>";					
							$this->salida .= "      <td align=\"center\" >".$res[$i]['direccion_residencia']."</td>";	
							$this->salida .= "      <td align=\"center\" >".$res[$i]['observacion']."</td>";										
              $this->salida .= "      <td align=\"center\" >".$marca."</td>";												
							$accionE=ModuloGetURL('app','Pagares','user','LlamarFormaEliminarResponsable',array('vector'=>$res[$i]));
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionE\"><img src=\"".GetThemePath()."/images/elimina.png\" border='0' title=\"Eliminar\"></a></td>";	
							$accionM=ModuloGetURL('app','Pagares','user','LlamarModificarDatosResponsables',array('datos'=>$res[$i]));
							$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionM\"><img src=\"".GetThemePath()."/images/editar.png\" border='0' title=\"Modificar\"></a></td>";	
							$this->salida .= "               </tr>";					
					}
					$this->salida .= "           </table>";							
			}			
			//botones			
			$this->salida .= "			      <BR><table width=\"50%\" align=\"center\" border=0>";
			$this->salida .= "				       <tr>";
			$accion=ModuloGetURL('app','Pagares','user','FormaPedirDatosResponsable');		
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "               <td align=\"center\"><input class=\"input-submit\" name=\"Responsable\" type=\"submit\" value=\"CREAR RESPONSABLE\"></td>";
			$this->salida .= "			     </form>";	
			$accion=ModuloGetURL('app','Pagares','user','FormaPrincipalPagares');		
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Pagare\" value=\"MODIFICAR PAGARE\"></td>";
			$this->salida .= "			     </form>";
			$accion=ModuloGetURL('app','Pagares','user','BotonCancelar');		
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			$this->salida .= "			     </form>";
			$this->salida .= "			     </table>";					
			$this->salida .= ThemeCerrarTabla();
			return true;		
	}
	
	function FormaEliminarResponsable($vector)
	{
			$this->salida .= ThemeAbrirTabla('ELIMINAR RESPONSABLE PAGARE No. '.$_SESSION['PAGARES']['DATOS']['prefijo'].$_SESSION['PAGARES']['DATOS']['numero']);		
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "           </table>";		
			$accion=ModuloGetURL('app','Pagares','user','EliminarResponsable',array('vector'=>$vector));		
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= " <table width=\"60%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= " <tr>";	
			$this->salida .= "  <td align=\"center\" colspan=\"2\" class=\"label_mark\">VA A ELIMINAR EL RESPONSABLE: ".$vector['tipo_id_tercero']." ".$vector['tercero_id']." - ".$vector['nombre_tercero']."</td>";			
			$this->salida .= " </tr>";					
			$this->salida .= " <tr>";
			$this->salida .= "  <td class=\"label\">OBSERVACIONES: </td>";
			$this->salida .= "  <td align=\"center\"><textarea cols=\"55\" rows=\"3\" class=\"textarea\" name=\"observacion\"></textarea></td>";						
			$this->salida .= " </tr>";
			$this->salida .= " </table>";				
			//botones			
			$this->salida .= "			      <BR><table width=\"50%\" align=\"center\" border=0>";
			$this->salida .= "				       <tr>";
			$this->salida .= "               <td align=\"center\"><input class=\"input-submit\" name=\"Responsable\" type=\"submit\" value=\"ELIMINAR RESPONSABLE\"></td>";
			$this->salida .= "			     </form>";	
			$accion=ModuloGetURL('app','Pagares','user','FormaModificarResponsablesPagare');		
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			$this->salida .= "			     </form>";
			$this->salida .= "			     </table>";		
			$this->salida .= ThemeCerrarTabla();
			return true;		
	}
	

//----------------------------------------------------------------------------------------------------

}//fin clase

?>

