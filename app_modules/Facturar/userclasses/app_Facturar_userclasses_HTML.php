<?php
	/**************************************************************************************
	* $Id: app_Facturar_userclasses_HTML.php,v 1.2 2010/11/25 18:24:34 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* $Revision: 1.2 $
	***************************************************************************************/
  IncludeClass('Facturar','','app','Facturar');
	class app_Facturar_userclasses_HTML extends app_Facturar_user
	{
		function app_Facturar_userclasses_HTML(){}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
//
	function PrincipalFacturar()//Informa que el modulo no tiene acceso directo
	{
		$acccion = SessionGetVar("ActionVolver");
		$this->salida  = ThemeAbrirTabla('FACTURACION E IMPRESION');
		$this->salida .= "<form name=\"formafacturar\" action=\"".$acccion."\" method=\"post\" >";
		$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr><td width=\"100%\">";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"10%\" class=\"label\" align=\"center\">";
		$this->salida .= "      <img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"90%\" class=\"label\" align=\"center\">";
		$this->salida .= "ESTE MODULO NO TIENE ACCESO DE MANERA DIRECTA";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td align=\"center\" >";
		$this->salida .= "			&nbsp;";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td align=\"center\" >";
		$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Volver\">";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "  </table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaMostrarBotonesFacturar($Cuenta,$PtoFacturacion)
	{
		//if(isset($_SESSION[FACTURACION][PUNTOFACTURACION]))
		//{
			IncludeLib('funciones_facturacion');
			$Estado = BuscarEstadoCuenta($Cuenta);
			$arreglo=$this->LlamaGetDatosCuenta($Cuenta);
			$PlanId=$arreglo[0][plan_id];
			$Empresa=$arreglo[0][empresa_id];
			$CentroUtilidad=$arreglo[0][centro_utilidad];

			$html = "<script>\n";    
			$html .= "function MostrarAjuste(valor){\n";           
			$html .= "  if(valor==1){\n";
			$html .= "    document.getElementById('Visualizar').style.display = 'none';\n";
			$html .= "    document.getElementById('NoVisualizar').style.display = 'block';\n";
			$html .= "    document.getElementById('AjustarCuenta').style.display = 'block';\n";
			$html .= "    var x;\n";      
			$html .= "  }\n";
			$html .= "  if(valor==2){\n";
			$html .= "    document.getElementById('Visualizar').style.display = 'block';\n";
			$html .= "    document.getElementById('NoVisualizar').style.display = 'none';\n";
			$html .= "    document.getElementById('AjustarCuenta').style.display = 'none';\n";
			$html .= "  }\n";
			$html .= " }\n";
			$html .= "</script>\n";    

			//estado=1 => ACTIVA - estado=2 => INACTIVA 
			//estado=3 => CUADRADA - estado=0 => FACTURADA 
			if($Estado == 'C')
			{
				//verifica que si ya esta la factura del paciente y si el plan es agrupado o capitacion no muestra facturar
				$x = $this->SetVerificarFactura($Cuenta,1);
				$tipo = $this->SetFacturaAgrupada($PlanId);//1 es agrupada

				$totalpac=$this->SetBuscarTotalPaciente($Cuenta);
				$abono=AbonoPaciente($Cuenta);
				//si no hay factura paciente y plan agrupado
				if(($x == 0 AND $tipo==1) OR ($x == 1 AND  $tipo==0) OR ($abono>0 AND $x == 0) OR ($x == 0 AND  $tipo==0))
				{
					if($totalpac==0 AND $abono==0)
					{
						$html .= "      <td class=\"label_mark\">EL VALOR A PAGAR PACIENTE ES 0<br>(NO SE GENERA FACTURA PACIENTE)</td>";
					}	
					//el plan es agrupado	 y ya hay factura del paciente
					if($tipo==1 AND ($x==1 OR $totalpac==0 AND $abono==0))
					{
						$html .= "<td class=\"label_mark\">EL PLAN ES AGRUPADO</td>";
					}
					elseif($arreglo[0][total_cuenta] > 0)
					{
						$TipoId=$arreglo[0][tipo_id_paciente];
						$PacienteId=$arreglo[0][paciente_id];
						$Ingreso=$arreglo[0][ingreso];
						$Estado=$arreglo[0][estado];
						$Fecha=$arreglo[0][fecha_registro];
						$msg='Esta seguro que desea FACTURAR la Cuenta No. '.$Cuenta;  
						$arreglo=array('Empresa'=>$Empresa,'CentroUtilidad'=>$CentroUtilidad,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Cuenta'=>$Cuenta,'Estado'=>$Estado,'PuntoFacturacion'=>$PtoFacturacion);
						//$accionT=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion_Fiscal','me2'=>'Facturacion','me'=>'FacturarCuenta','mensaje'=>$msg,'titulo'=>'FACTURAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
						$accionT=ModuloGetURL('app','Facturar','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturar','me2'=>'','me'=>'LlamaFacturarCuenta','mensaje'=>$msg,'titulo'=>'FACTURAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
						$html .= "           <form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
						$html .= "<td class=\"label_mark\"><a href=\"$accionT\">FACTURAR</a></td>";
						//$html .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"FACTURAR\"></td>";
						$html .= "    </form>";
					}
					//$html .= "    </tr>";
				}
				elseif($tipo==1)
				{
					if($totalpac==0 AND $abono==0)
					{
						$html .= "      <td class=\"label_mark\">EL VALOR A PAGAR PACIENTE ES 0<br>(NO SE GENERA FACTURA PACIENTE)</td>";
					}									
					$html .= "      <td class=\"label_mark\">EL PLAN ES AGRUPADO</td>";
					//$html .= "    </tr>";
				}
        //ACTIVAR CUENTA CUADRADA
        $permisosActivar = $this->LlamaPermisosActivarCuenta(UserGetUID());
        if($permisosActivar)
        {
          $accionAct=ModuloGetURL('app','Facturacion_Fiscal','user','LlamaActivarCuentaCuadrada',array('Cuenta'=>$Cuenta));
          $html .= "    <form name=\"formaAct\" action=\"$accionAct\" method=\"post\">";
          $html .= "  <td class=\"label_mark\"><a href=\"$accionAct\">ACTIVAR CUENTA</a></td>";
          $html .= "    </form>";
        }
        //FIN ACTIVAR CUENTA CUADRADA
			}
			elseif($Estado!='C' AND $Estado!='0' AND $Estado!='F')
			{
				$s=$this->SetSaldoPaciente($Empresa,$Cuenta,$PlanId);
				$des=BuscarCargoAjusteDes($Cuenta);//lo sumo porque el valor es negativo
				$apro=BuscarCargoAjusteApro($Cuenta);
				if(empty($s) OR $s==0 OR (($s+$apro['precio'])==0) OR ($s+$des['precio']==0))
				{
					//$accionT=ModuloGetURL('app','Facturacion_Fiscal','user','CerrarCuenta',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Cuenta'=>$Cuenta));
					$accionT =  ModuloGetURL('app','Facturar','user','LlamaCerrarCuenta',array('arreglo'=>$arreglo));
					$html .= "           <form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
					$html .= "<td class=\"label_mark\"><a href=\"$accionT\">CUADRAR FACTURA PARA CIERRE</a></td>";
					//$html .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CUADRAR FACTURA PARA CIERRE\"></td>";
					$html .= "    </form>";
				}
				elseif(!empty($s) && $s!=0)
					{
						$ajustar = $Automatico= true;
						if($s>0 AND $s==($arreglo[0][valor_cuota_moderadora]+$arreglo[0][valor_cuota_paciente]))
						{  
							$html .= "<td class=\"label_error\" style=\"cursor:help\">El ajuste debe ser Modificando Cuotas del paciente.</td>";
							$ajustar = false;
							$Automatico = false;
						}
						else
						if($s>0)
						{  
							$msg='La Cuenta No. '.$Cuenta.' tiene un Saldo de Descuento de $ '.$s.'. Esta Seguro de Realizar el Ajuste a la Cuenta.';  
						}
						if($s<0)
						{  
							$msg='La Cuenta No. '.$Cuenta.' tiene un Saldo de Aprovechamiento de $ '.$s.'. Esta Seguro de Realizar el Ajuste a la Cuenta.';  
							$ajustar = false;
						}
						$TipoId=$arreglo[0][tipo_id_paciente];
						$PacienteId=$arreglo[0][paciente_id];
						$Ingreso=$arreglo[0][ingreso];
						$Estado=$arreglo[0][estado];
						$Fecha=$arreglo[0][fecha_registro];
						if($ajustar)
						{
							//CAPA AJUSTE
							$html .= "      <tr align=\"center\"><td class=\"label_mark\"><div id=\"Visualizar\"><a href=\"javascript:MostrarAjuste(1);\">AJUSTAR</a></div></td></tr>";
							$html .= "       <tr align=\"center\"><td class=\"label_mark\"><div id=\"NoVisualizar\" style=\"display:none\"><a href=\"javascript:MostrarAjuste(2);\">AJUSTAR</div></a></td></tr>";
							$html .= "        <tr align=\"center\"><td class=\"modulo_table_list\">"; 
							$html .= "      <div id='AjustarCuenta' style=\"display:none\">";
							//$html .= $this->FormaAjusteCuenta($arreglo);
							$html .= $this->FormaAutenticarUsuario($arreglo);
							$html .= "      </div>";
							$html .= "        </td></tr>";

//							$html .= "      <td class=\"label_mark\"><div id=\"Visualizar\"><a href=\"javascript:MostrarAjuste(1);\">AJUSTAR</a></div></td>";
//							$html .= "       <td class=\"label_mark\"><div id=\"NoVisualizar\" style=\"display:none\"><a href=\"javascript:MostrarAjuste(2);\">AJUSTAR</div></a></td>";
//							$html .= "      <div id='AjustarCuenta' style=\"display:none\">";
							//$html .= $this->FormaAjusteCuenta($arreglo);
//							$html .= $this->FormaAutenticarUsuario($arreglo);
//							$html .= "      </div>";
							//$html .= "        </td>";

							//FIN CAPA AJUSTE
						}
						else
						if($Automatico)
						{
							$arreglo=array('Empresa'=>$Empresa,'CentroUtilidad'=>$CentroUtilidad,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado,'Saldo'=>$s);
							$accionT=ModuloGetURL('app','Facturar','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturar','me2'=>'','me'=>'LlamaAjustarCuenta','mensaje'=>$msg,'titulo'=>'AJUSTAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
							$html .= "           <form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
							$html .= "<td class=\"label_mark\"><a href=\"$accionT\">AJUSTE CUENTA</a></td>";
							$html .= "    </form>";
						}
					}
				//$html .= "    </tr>";
			}
		//}
		//else
		//{
				//$html .= "<td><label class=\"label_error\">Usuario no facturador</label></td>";
		//}
		return $html;
	}

		function FormaAutenticarUsuario($arreglo)
		{
			$accion = ModuloGetURL('app','Facturar','user','FormaAjusteCuenta',array('Arreglo'=>$arreglo));
			//$action = "window.open('".$accion."','cambiar','toolbar=no,width=350,height=350,resizable=no,scrollbars=yes').focus();";
			$html = "<script>\n";
			$html .= "	function EvaluarDatos(frm,usuario,pwd) \n";
			$html .= "	{\n";
			$html .= "		ele = document.getElementById('error1');\n";
			$html .= "		if(frm.usuario.value == '')\n";
			$html .= "		{\n";
			$html .= "			ele.innerHTML = 'SE DEBE INDICAR EL USUARIO AUTORIZADO PARA EL AJUSTE'\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(frm.password.value == '')\n";
			$html .= "		{\n";
			$html .= "			ele.innerHTML = 'SE DEBE INDICAR EL PASSWORD DEL USUARIO AUTORIZADO PARA REALIZAR AJUSTE'\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .="    var nombre=\"\"\n";
			$html .="    var url2=\"\"\n";
			$html .="    var width=\"400\"\n";
			$html .="    var height=\"300\"\n";
			$html .="    var winX=Math.round(screen.width/2)-(width/2);\n";
			$html .="    var winY=Math.round(screen.height/2)-(height/2);\n";
			$html .="    var nombre=\"XXX\";\n";
			$html .="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
			$html .="    var url2 ='$accion';\n";
			$html .='    var url = url2+"&usuario="+usuario+"&pwd="+pwd;';
			$html .="    rem = window.open(url, nombre, str);\n";
			//$html .= "		frm.action = \"".$action."\";\n";
			//$html .= "		frm.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";

			$html .="<div id=\"error1\" style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .="<br><table border=\"0\"  align=\"center\"   width=\"45%\" >";
			$html .= "<form name=\"cambiar\" action=\"javascript:EvaluarDatos(document.cambiar,document.getElementById('usuario').value,document.getElementById('password').value)\" method=\"post\">\n";
			$html .="<tr>";
			$html .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Autenticaci� de Usuario</td>";
			$html .="</tr>";
			$html .="<tr>";
			$html .="<tr class=\"modulo_list_claro\">";
			$html .= "<td   width=\"35%\" align=\"center\">Usuario :</td>";
			$html .= "<td  align=\"center\"><input id=\"usuario\" class=\"input-text\" type=\"text\" align=\"center\" name=\"usuario\"</td>";
			$html .="</tr>";
			$html .="<tr class=\"modulo_list_claro\">";
			$html .= "<td   width=\"35%\"  align=\"center\">Password :</td>";
			$html .= "<td  align=\"center\"><input id=\"password\" class=\"input-text\" type=\"password\" align=\"center\" name=\"pass\"</td>";
			$html .="</tr>";
			$html .="</table>";
			$html .= "	<table align=\"center\" width=\"100%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" class=\"input-bottom\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "			</td>\n";
			$html .= "		</form>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			return $html;
		}
//
//FORMA AJUSTE CUENTA
		function FormaAjusteCuenta($arreglo)
		{
			IncludeClass('Facturar','','app','Facturar');
			$cnt = new Facturar();
			if(!$cnt->ValidarUsuarioAjuste(UserGetUID(),$_REQUEST[usuario],$_REQUEST[pwd]))
			{
				$this->salida .= "<br><br><center><label class=\"label_error\">$cnt->mensaje</label></center>\n";
				$this->salida .= "	<table align=\"center\" width=\"100%\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td align=\"center\">&nbsp;\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"button\" class=\"input-bottom\" name=\"Cerrar\" value=\"Cerrar\" onclick = \"javascript:window.close();\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	</table>\n";
			}
			else
			{
				// ************* cuenta de pruebas 418221
				$motivos = array();
				$motivos = $cnt->ObtenerMotivosAjusteCuenta();
				
				$action = ModuloGetURL('app','Facturar','user','LlamaAjustarCuenta',array('REQUEST'=>$_REQUEST,'usuario_id'=>$cnt->usuario_id));
				$this->salida = "<script>\n";
				$this->salida .= "	function acceptNum(evt)\n";
				$this->salida .= "  {\n";
				$this->salida .= "    var nav4 = window.Event ? true : false;\n";
				$this->salida .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
				$this->salida .= "    return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
				$this->salida .= "  }\n";
				$this->salida .= "	function IsNumeric(valor)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		var log = valor.length; \n";
				$this->salida .= "		var sw='S';\n";
				$this->salida .= "		var puntos = 0;\n";
				$this->salida .= "		for (x=0; x<log; x++)\n";
				$this->salida .= "		{ \n";
				$this->salida .= "			v1 = valor.substr(x,1);\n";
				$this->salida .= "			v2 = parseInt(v1);\n";
				$this->salida .= "			//Compruebo si es un valor num�ico\n";
				$this->salida .= "			if(v1 == '.')\n";
				$this->salida .= "			{\n";
				$this->salida .= "				puntos ++;\n";
				$this->salida .= "			}\n";
				$this->salida .= "			else if (isNaN(v2)) \n";
				$this->salida .= "			{ \n";
				$this->salida .= "				sw= 'N';\n";
				$this->salida .= "				break;\n";
				$this->salida .= "			}\n";
				$this->salida .= "		}\n";
				$this->salida .= "		if(log == 0) sw = 'N';\n";
				$this->salida .= "		if(puntos > 1) sw = 'N';\n";
				$this->salida .= "		if(sw=='S')\n"; 
				$this->salida .= "			return true;\n";
				$this->salida .= "		return false;\n";
				$this->salida .= "	} \n";
				$this->salida .= "	function EvaluarDatos(frm) \n";
				$this->salida .= "	{\n";
				$this->salida .= "		ele = document.getElementById('error1');\n";
				$this->salida .= "		if(!IsNumeric(frm.Saldo.value))\n";
				$this->salida .= "		{\n";
				$this->salida .= "			ele.innerHTML = 'EL VALOR A CAMBIAR NO ES NUMERICO O POSEE UN FORMATO INCORRECTO'\n";
				$this->salida .= "			return;\n";
				$this->salida .= "		}\n";
				$this->salida .= "		if(frm.motivo_id.value == '-1')\n";
				$this->salida .= "		{\n";
				$this->salida .= "			ele.innerHTML = 'SE DEBE INDICAR EL MOTIVO POR EL CUAL SE ESTA AJUSTANDO LA CUENTA'\n";
				$this->salida .= "			return;\n";
				$this->salida .= "		}\n";
				$this->salida .= "		if(frm.observacion.value == '')\n";
				$this->salida .= "		{\n";
				$this->salida .= "			ele.innerHTML = 'SE DEBE INDICAR UNA OBSERVACION PARA EL AJUSTE DE CUENTA'\n";
				$this->salida .= "			return;\n";
				$this->salida .= "		}\n";
				$this->salida .= "		frm.action = \"".$action."\";\n";
				$this->salida .= "		frm.submit();\n";
				$this->salida .= "	}\n";
				$this->salida .= "</script>\n";
				//$this->salida .= "<pre>".print_r($datos,true)."</pre>";
				$this->salida .= "<br><br><div id=\"error1\" style=\"text-align:center\" class=\"label_error\"></div>\n";
				$this->salida .= "<form name=\"cambiar\" action=\"javascript:EvaluarDatos(document.cambiar)\" method=\"post\">\n";
				$this->salida .= "	<table width=\"100%\" align=\"center\" class=\"fieldset\" cellpadding=\"2\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td align=\"center\" colspan=\"2\">CARGO AJUSTE DE CUENTA</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td align=\"left\" width=\"25%\" >VALOR</td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" width=\"80%\">\n";
				$this->salida .= "				<input type=\"text\" name=\"Saldo\" class=\"input-text\" value=\"".$_REQUEST[Arreglo][0][saldo]."\" onkeypress=\"return acceptNum(event)\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td align=\"left\" >MOTIVO</td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "				<select name=\"motivo_id\" class=\"select\" >\n";
				$this->salida .= "					<option value=\"-1\" >----Seleccionar----</option>\n";
				
				foreach($motivos as $key => $mtv)
					$this->salida .= "					<option value=\"".$mtv['tarifario_id'].'||//'.$mtv['cargo'].'||//'.$mtv['motivo_id']."\" >".$mtv['descripcion']."</option>\n";
				
				$this->salida .= "				</select>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td colspan=\"2\">OBSERVACIONES</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "			<td colspan=\"2\">\n";
				$this->salida .= "				<textarea id=\"observacion\" name=\"observacion\" style=\"width:100%\" class=\"textarea\"></textarea>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
				$this->salida .= "	<table align=\"center\" width=\"100%\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-bottom\" name=\"aceptar\" value=\"Aceptar\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</form>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
			}
			return true;
		}

//
//
  function ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2)
  {
   //print_r($_REQUEST);
      if(empty($Titulo))
      {
        $arreglo=$_REQUEST['arreglo'];
        $Cuenta=$_REQUEST['Cuenta'];
        $c=$_REQUEST['c'];
        $m=$_REQUEST['m'];
        $me=$_REQUEST['me'];
        //$me2=$_REQUEST['me2'];
        $me2=SessionGetVar('ActionVolver');
        $mensaje=$_REQUEST['mensaje'];
        $Titulo=$_REQUEST['titulo'];
        $boton1=$_REQUEST['boton1'];
        $boton2=$_REQUEST['boton2'];
      }

        $this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
        return true;
  }

  /**
  * Forma para mensajes.
  * @access private
  * @return boolean
  * @param string mensaje
  * @param string nombre de la ventana
  * @param string accion de la forma
  * @param string nombre del boton
  */
  function FormaMensaje($mensaje,$titulo,$accion,$boton,$botonC,$arreglo)
  {
				$script = false;
				if($_REQUEST[Empresa]
					AND $_REQUEST[CentroUtilidad]
					AND $_REQUEST[Cuenta])
				{$script = true;}
        //factura detalleda
        IncludeLib('funciones_facturacion');
				$Cuenta=$arreglo['cuenta'];
				$RUTA = $_ROOT ."cache/factura".$Cuenta.".pdf";
        $mostrar = "<script>\n";
        $mostrar.= "	var rem=\"\";\n";
        $mostrar.= "  	function abreVentana()\n";
        $mostrar.= "  	{\n";
        $mostrar.= "    	var nombre = \"\"\n";
        $mostrar.= "    	var url2 = \"\"\n";
        $mostrar.= "    	var str = \"\"\n";
        $mostrar.= "    	var alto = screen.height\n";
        $mostrar.= "    	var ancho = screen.width\n";
        $mostrar.= "    	var nombre = \"REPORTE\";\n";
        $mostrar.= "    	var str = \"ancho,alto,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.= "    	var url2 = '$RUTA';\n";
        $mostrar.= "    	rem = window.open(url2, nombre, str);\n";
        $mostrar.= "    };\n";
       
        //factura conceptos
        $RUTA = $_ROOT ."cache/facturaconceptos".$Cuenta.".pdf";
        $mostrar.="		var rem=\"\";\n";
        $mostrar.="  	function abreVentana2()\n";
        $mostrar.="  	{\n";
        $mostrar.="			var nombre=\"\"\n";
        $mostrar.="			var url2=\"\"\n";
        $mostrar.="    		var str=\"\"\n";
        $mostrar.="    		var ALTO=screen.height\n";
        $mostrar.="    		var ANCHO=screen.width\n";
        $mostrar.="   		var nombre=\"REPORTE\";\n";
        $mostrar.="    		var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    		var url2 ='$RUTA';\n";
        $mostrar.="    		rem = window.open(url2, nombre, str);\n";
        $mostrar.="  	};\n";
                
        $RUTA = $_ROOT ."cache/hojatransaccion.pdf";
        $mostrar.="		var rem=\"\";\n";
        $mostrar.="  	function abreVentanaHT()\n";
        $mostrar.="  	{\n";
        $mostrar.="    		var nombre=\"\"\n";
        $mostrar.="    		var url2=\"\"\n";
        $mostrar.="    		var str=\"\"\n";
        $mostrar.="    		var ALTO=screen.height\n";
        $mostrar.="    		var ANCHO=screen.width\n";
        $mostrar.="    		var nombre=\"REPORTE\";\n";
        $mostrar.="    		var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    		var url2 ='$RUTA';\n";
        $mostrar.="    		rem = window.open(url2, nombre, str);\n";
        $mostrar.="  	}\n";
        
        //CUENTA COBRO	
        $RUTA1 = $_ROOT ."cache/cuentacobro".$Cuenta.".pdf";
        $mostrar.="  function abreVentanaCC(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA1';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        if(!$script)
        {
         $mostrar.="  function Finalizar(frm)\n";
         $mostrar.="  {\n";
         $mostrar.="    window.opener.location = '$accion';\n";
         $mostrar.="    window.close();\n";
         $mostrar.="  };\n";
         $scpt = "Finalizar(document.formabuscar)";
        }
        else
        {
         $mostrar.="  function FinalizarSinUsuario(frm)\n";
         $mostrar.="  {\n";
         $mostrar.="    frm.action = '$accion';\n";
         $mostrar.="    frm.submit();\n";
         $mostrar.="  };\n";
         $scpt = "FinalizarSinUsuario(document.formabuscar)";
        }
        $mostrar.="</script>\n";
        $this->salida.="$mostrar";
			if($botonC)
			{
				$this->salida .= ThemeAbrirTabla($titulo,"50%")."<br>";
				$this->salida .= "<table width=\"68%\" align=\"center\" class=\"normal_10\" border='0'>\n";
				$this->salida .= "    <form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
				$this->salida .= "        <tr><td colspan=\"4\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
				if(!empty($boton)){
						$this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"<<$boton\"></td>\n";
				}
				else{
						$this->salida .= "    <tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
				}
				$this->salida .= "    </form>\n";
				//este boton solo lo mostraria el reporte de cierre de caja.........
					if($botonC=='facturapaciente')
					{
						//para imprimir en pos
/*						$accion7=ModuloGetURL('app','Facturacion_Fiscal','user','Reportes');
						$this->salida .= "<form name=\"formabuscar\" action=\"$accion7\" method=\"post\">";
						$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir POS\"></td>\n";
						$this->salida .= "</form>";*/
	
						$reporte = new GetReports();
						$this->salida .= $reporte->GetJavaReport('app','CajaGeneral','Factura',
						array('cuenta'=>$arreglo['cuenta'],'switche_emp'=>$arreglo['switche_emp']),array('rpt_dir'=>'cache','rpt_name'=>'recibo'.$arreglo['cuenta'],'rpt_rewrite'=>FALSE));
						$funcion=$reporte->GetJavaFunction();
						$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir PDF\"  onclick=\"javascript:$funcion\"></td></tr>\n";
					}
					//CASO CUENTAS DE COBRO
					if($botonC=='cuentacobro')
					{
						IncludeLib("reportes/cuentacobro");
						GenerarCuentaCobro(array('PlanId'=>$arreglo['PlanId'],'Fecha'=>$arreglo['Fecha'],
									'Ingreso'=>$arreglo['Ingreso'],'numero'=>$arreglo['numero'],
									'prefijo'=>$arreglo['prefijo'],'empresa'=>$arreglo['empresa'],
									'tipo_factura'=>$arreglo['tipo_factura'],'cuenta'=>$arreglo['cuenta']));
						$this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"Imprimir PDF\" onclick=\"javascript:abreVentanaCC()\"></td>";
					}

					if($botonC=='reportes')
					{ 
						$reporte = explode('/',$arreglo['ruta_hoja']);
						
						$RUTA = $_ROOT ."cache/".$reporte[1].$Cuenta.".pdf";
						$mostrar = "<script>\n";
						$mostrar.="		var rem=\"\";\n";
						$mostrar.="  	function abreVentanaHC()\n";
						$mostrar.="  	{\n";
						$mostrar.="    		var nombre=\"\"\n";
						$mostrar.="    		var url2=\"\"\n";
						$mostrar.="    		var str=\"\"\n";
						$mostrar.="    		var ALTO=screen.height\n";
						$mostrar.="    		var ANCHO=screen.width\n";
						$mostrar.="    		var nombre=\"REPORTE\";\n";
						$mostrar.="    		var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
						$mostrar.="    		var url2 ='$RUTA';\n";
						$mostrar.="    		rem = window.open(url2, nombre, str);\n";
						$mostrar.="  	}\n";
						$mostrar.="</script>\n";
						$this->salida.="$mostrar";

						IncludeLib($arreglo['ruta_hoja']);
						$funcion = 'Generar'.$reporte[1];

            $funcion (array('numerodecuenta'=>$arreglo['cuenta'])); 
            $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"Vista Preliminar\" onclick=\"javascript:abreVentanaHC()\"></td>";
					}
//
					if($botonC=='factura')
					{
						$var=$this->DatosFactura($arreglo['cuenta'],$arreglo['plan_id'],$arreglo['tipoid'],$arreglo['pacienteid']);
						$var['facturas'] = $this->facturas;
						$ruta=EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'],$arreglo['plan_id'],$botonC);
						IncludeLib($ruta);
						GenerarFactura($var);
							
						$this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"IMPRIMIR FACTURA\" onclick=\"javascript:abreVentana()\"></td>";
								

					}
					if($botonC=='resumen')
					{
						$var = $this->DatosResumenFactura($arreglo['cuenta'],$arreglo['plan_id'],$arreglo['tipoid'],$arreglo['pacienteid']);
						$var['facturas'] = $this->facturas;
						//IncludeLib("reportes/factura");
						$ruta=EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'],$arreglo['plan_id'],$botonC);
						IncludeLib($ruta);
						GenerarFactura($var);
						$this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"RESUMEN FACTURA\" onclick=\"javascript:abreVentana()\"></td>";
					}
					if($botonC=='conceptos')
					{
						$var=$this->DatosFactura($arreglo['cuenta'],$arreglo['plan_id'],$arreglo['tipoid'],$arreglo['pacienteid']);
						//$var['facturas'] = $this->facturas;
						$var['facturaAnulada'] = $arreglo['prnAnuladas'];
						//IncludeLib("reportes/facturaconceptos");
						$ruta=EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'],$arreglo['plan_id'],$botonC);
						IncludeLib($ruta);
						GenerarFacturaConceptos($var);
						if($arreglo['prnAnuladas'])
						{$value = "value=\"Vista Previa\"";}
						else
						{$value = "value=\"FACTURA CONCEPTOS\"";}
						$this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" $value onclick=\"javascript:abreVentana2()\"></td>";
					}
					

				$this->salida .= "</table>\n";
				$this->salida .= themeCerrarTabla();
			}
			else
			{
				$this->salida .= ThemeAbrirTabla($titulo);
				$this->salida .= "            <table width=\"60%\" align=\"center\" >";
				//$this->salida .= "            <form name=\"formabuscar\" action=\"javascript:$scpt;\" method=\"post\">";
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
				$this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\" onclick = \"javascript:Finalizar(this);\"></td></tr>";
				$this->salida .= "           </form>";
				$this->salida .= "           </table>";
				$this->salida .= ThemeCerrarTabla();
			}
				return true;
	}

		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaMensajeError($titulo,$align,$action1,$action2 = null)
		{
			$this->salida .= ThemeAbrirTabla($titulo);
			$this->salida .= "	<script>\n";
			$this->salida .= "		function CerrarVentana(num_ingreso)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			window.opener.document.formabuscar.ingreso.value = num_ingreso;\n";
			$this->salida .= "			window.opener.document.formabuscar.submit();\n";
			$this->salida .= "			window.close();\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$action1."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" align=\"".$align."\" colspan=\"3\"><br>";
			$this->salida .= "				".$this->frmError['MensajeError']."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form><br>\n";
			
			if($action2)
			{
				$this->salida .= "		<form name=\"cancelar\" action=\"".$this->action2."\" method=\"post\">\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
				$this->salida .= "			</td></form>\n";
				
			}
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
    
    function LlamaPermisosActivarCuenta($usuario)
    {
      $fact = new Facturar();
      $dat = $fact->PermisosActivarCuenta($usuario);
      return $dat;
    }
    
	}
?>