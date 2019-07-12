<?php
  /******************************************************************************
  * $Id: ReportesCensoHTML.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.7 $ 
	* 
  ********************************************************************************/
  IncludeClass('ReportesCenso','','app','Cuentas');
  
	class ReportesCensoHTML
	{
		function ReportesCensoHTML(){}
  /**
  *
  */
		function FrmGeneracionReportes($accionV)
		{
			//SI NO LLEGA $_REQUEST[EmpresaId], se puede manejar la variable $_SESSION[DatosEmpresaId]
			if($_REQUEST[EmpresaId])
			{SessionSetVar("CensoEmpresaId",$_REQUEST[EmpresaId]);}
			$accion1=ModuloGetURL('app','Cuentas','user','LlamaFrmMenuCenso');
			$accion2=ModuloGetURL('app','Cuentas','user','LlamaFrmListadoCenso',array('opcion'=>1,'enlace'=>2));
			$accion3=ModuloGetURL('app','Cuentas','user','LlamaFrmListadoCenso',array('opcion'=>2,'enlace'=>2));
			$accion4=ModuloGetURL('app','Cuentas','user','LlamaFrmListadoCenso',array('opcion'=>3,'enlace'=>2));
			$accion5=ModuloGetURL('app','Cuentas','user','LlamaFrmConsultaPacientesTP');
			$accion6=ModuloGetURL('app','Cuentas','user','LlamaFrmTotalFacturaCredito');
			
			$html = ThemeAbrirTabla("REPORTES");
			$html .= "<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
			$html .= "	<tr class=\"modulo_table_list_title\">";
			$html .= "		<td  align=\"center\">REPORTES</td>";
			$html .= "	</tr>";
			$html .= "	<tr class=\"modulo_list_claro\">";
			$html .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion1\">REPORTE CENSO</a></label></td>";
			$html .= "	</tr>";
			$html .= "	<tr class=\"modulo_list_oscuro\">";
			$html .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion5\">REPORTE PACIENTES - CUENTAS</a></label></td>";
			$html .= "	</tr>";
			$html .= "	<tr class=\"modulo_list_claro\">";
			$html .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion2\">REPORTE CUENTAS ACTIVAS</a></label></td>";
			$html .= "	</tr>";
			$html .= "	<tr class=\"modulo_list_oscuro\">";
			$html .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion3\">REPORTE CUENTAS INACTIVAS</a></label></td>";
			$html .= "	</tr>";
			$html .= "	<tr class=\"modulo_list_claro\">";
			$html .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion4\">REPORTE CUENTAS ACTIVAS E INACTIVAS</a></label></td>";
			$html .= "	</tr>";
			$html .= "	<tr class=\"modulo_list_oscuro\">";
			$html .= "		<td class=\"label\" align=\"center\"><label 		class=\"label\"><a href=\"$accion6\">REPORTE TOTAL FACTURAS CREDITO</a></label></td>";
			$html .= "	</tr>";
			$html .= "</table>";
			
			$html .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
			$html .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
			$html .= "</form>";
			$html .= ThemeCerrarTabla();
			
			return $html;
		}

		function FrmMenuCenso($accionV)
		{
			$accion1=ModuloGetURL('app','Cuentas','user','LlamaFrmListadoCenso',array('opcion'=>0,'enlace'=>1));
			$accion2=ModuloGetURL('app','Cuentas','user','LlamaFrmListadoCenso',array('opcion'=>1,'enlace'=>1));
			
			$html = ThemeAbrirTabla("REPORTES MENU CENSO");
			$html .= "<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
			$html .= "	<tr class=\"modulo_table_list_title\">";
			$html .= "		<td  align=\"center\">REPORTES CENSO</td>";
			$html .= "	</tr>";
			$html .= "	<tr class=\"modulo_list_claro\">";
			$html .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion1\">LISTADO DE PACIENTES HOSPITALIZADOS</a></label></td>";
			$html .= "	</tr>";
			$html .= "	<tr class=\"modulo_list_oscuro\">";
			$html .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion2\">LISTADO DE PACIENTES EN OBSERVACION DE URGENCIAS</a></label></td>";
			$html .= "	</tr>";
			$html .= "</table>";
			
			$html .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
			$html .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
			$html .= "</form>";
			
			$html .= ThemeCerrarTabla();
			
			return $html;
		}

		function FrmListadoCenso()
		{
			if($_REQUEST['enlace']==1)
			{
				if(!$_REQUEST['opcion'])
				{
					$pacientes=$this->LlamaListadoHospitalizados();
					$titulo="LISTADO DE PACIENTES HOSPITALIZADOS";
				}
				else
				{
					$pacientes=$this->LlamaListadoObservacionUrgencias();
					$titulo="LISTADO DE PACIENTES EN OBSERVACION URGENCIAS";
				}
				$accionV = ModuloGetURL('app','Cuentas','user','LlamaFrmMenuCenso');
			}
			if($_REQUEST['enlace']==2)
			{
				switch($_REQUEST['opcion'])
				{
					case 1:
						$titulo="REPORTES CUENTAS ACTIVAS";
					break;
					case 2:
						$titulo="REPORTES CUENTAS INACTIVAS";
					break;
					case 3:
						$titulo="REPORTES CUENTAS ACTIVAS E INACTIVAS";
					break;
				}
				
				$pacientes=$this->LlamaReportesCuentas($_REQUEST['opcion']);
				$accionV = ModuloGetURL('app','Cuentas','user','LlamaFrmGeneracionReportes');
			}
			
			$html = ThemeAbrirTabla($titulo);
			$cont=0;
			$entidad=array();
			$html .= "<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			foreach($pacientes as $key=>$valor)
			{
				$html .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$html .= "		<td align =\"center\" colspan=\"4\">DEPARTAMENTO  -  $key</td>";
				$html .= "	</tr>\n";
				foreach($valor as $key1=>$valor1)
				{
					$html .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
					$html .= "		<td align =\"center\" colspan=\"4\">ESTACION  -  $key1</td>";
					$html .= "	</tr>\n";
					$html .= "	<tr>\n";
					$html .= "		<td colspan=\"4\">\n";
					$html .= "			<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
					$html .= "				<tr class=\"modulo_table_list_title\" align=\"center\">\n";
					$html .= "					<td width=\"5%\">CUENTA</td>\n";
					$html .= "					<td width=\"10%\">ID</td>\n";
					$html .= "					<td width=\"15%\">PACIENTE</td>\n";
					$html .= "					<td width=\"10%\">AFILIACION</td>\n";
					$html .= "					<td width=\"5%\">RANGO</td>\n";
					$html .= "					<td width=\"5%\">HAB.</td>\n";
					$html .= "					<td width=\"5%\">CAMA</td>\n";
					$html .= "					<td width=\"10%\">FECHA INGRESO</td>\n";
					$html .= "					<td width=\"5%\">TIEMPO<BR>HOSP (DIAS)</td>\n";
					$html .= "					<td width=\"10%\">TERCERO</td>\n";
					$html .= "					<td width=\"10%\">PLAN</td>\n";
					if($_REQUEST['enlace']==2 AND $_REQUEST['opcion']==3)
						$html .= "					<td>ESTADO CUENTA</td>\n";
					$html .= "					<td width=\"15%\">VALOR CUBIERTO + HAB</td>\n";
					$html .= "				</tr>\n";
					
					$k=0;
					foreach($valor1 as $key2=>$valor2)
					{
						if($k%2==0)
						{
							$estilo="modulo_list_oscuro";
						}
						else
						{
							$estilo="modulo_list_claro";
						}
	
						$vc_pac=$valor2['valor_cubierto'];
						$vnc_pac=$this->LlamaGetEstancia($valor2['numerodecuenta']);
						
						$entidad[$valor2['nombre_tercero']]['valor_cuenta']+=$vc_pac+$vnc_pac;
						$entidad[$valor2['nombre_tercero']]['contador']+=1;
						
						$pacientes[$key][$key1][$key2]['t_vc_apc']=$vc_pac;
						$pacientes[$key][$key1][$key2]['t_vnc_apc']=$vnc_pac;
	
						$html .= "				<tr class=\"$estilo\" align=\"center\">\n";
						$html .= "					<td>".$valor2['numerodecuenta']."</td>\n";
						$html .= "					<td>".$valor2['tipo_id_paciente']." - ".$valor2['paciente_id']."</td>\n";
						$html .= "					<td>".$valor2['nombre_completo']."</td>\n";
						$html .= "					<td>".strtoupper($valor2['tipo_afiliado_nombre'])."</td>\n";
						$html .= "					<td>".$valor2['rango']."</td>\n";
						$html .= "					<td>".$valor2['pieza']."</td>\n";
						$html .= "					<td>".$valor2['cama']."</td>\n";
						$html .= "					<td>".date('Y-m-d g:i a',strtotime($valor2['fecha_ingreso']))."</td>\n";
						$html .= "					<td>".$this->GetDiasHospitalizacion($valor2['fecha_ingreso'])."</td>\n";
						$html .= "					<td>".$valor2['nombre_tercero']."</td>\n";
						$html .= "					<td>".$valor2['plan_descripcion']."</td>\n";
						if($_REQUEST['enlace']==2 AND $_REQUEST['opcion']==3)
							$html .= "					<td>".$valor2['estado_cuenta']."</td>\n";
						$html .= "					<td> $ ".FormatoValor($vc_pac+$vnc_pac)."</td>\n";
						$html .= "				</tr>\n";
						$sum+=$vc_pac+$vnc_pac;
						$cont++;
						$k++;
					}
					if($_REQUEST['enlace']==1)
					{
						$co=$this->LlamaGetCamas($valor[$key1][$key2]['estacion_id'],'0');
						$cd=$this->LlamaGetCamas($valor[$key1][$key2]['estacion_id'],'1');
						
						$html .= "				<tr class=\"hc_table_submodulo_list_title\">\n";
						$html .= "					<td  colspan=\"12\" align=\"right\">CAMAS DISPONIBLES : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$cd</label> &nbsp;&nbsp;&nbsp; CAMAS OCUPADAS : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$co</label> &nbsp;&nbsp;&nbsp; CANTIDAD : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$k</label> </td>\n";
						$html .= "				</tr>\n";
					}
					$html .= "			</table>\n";
					$html .= "		</td>\n";
					$html .= "	</tr>\n";
				}
			}
			
			$html .= "	<tr class=\"modulo_list_oscuro\" align=\"center\">\n";
			$html .= "		<td class=\"label\">ENTIDAD</td>\n";
			$html .= "		<td class=\"label\">CANTIDAD</td>\n";
			$html .= "		<td class=\"label\">VALOR CUENTA</td>\n";
			$html .= "	</tr>\n";
			foreach($entidad as $key=>$valor_ent)
			{
				$html .= "	<tr class=\"modulo_list_claro\">\n";
				$html .= "		<td align=\"right\" class=\"label\">$key</td>\n";
				$html .= "		<td align=\"right\" class=\"label\">".$valor_ent['contador']."</td>\n";
				$html .= "		<td align=\"right\" class=\"label\"> $ ".FormatoValor($valor_ent['valor_cuenta'])."</td>\n";
				$html .= "	</tr>\n";
			}
			
			$html .= "	<tr class=\"hc_table_submodulo_list_title\">\n";
			$html .= "		<td align=\"right\" class=\"label\">TOTAL : </td>\n";
			$html .= "		<td align=\"right\" class=\"label\">$cont</td>";
			$html .= "		<td align=\"right\" class=\"label\"> $ ".FormatoValor($sum)."</td>\n";
			$html .= "	</tr>\n";
			
			$html .= "</table>\n";
			
			$_SESSION['listado']=$pacientes;
			
			/*$reporte=new GetReports();
			$mostrarT=$reporte->GetJavaReport('app','Facturacion','ReporteImpCuentas',array('enlace'=>$_REQUEST['enlace'],'opcion'=>$_REQUEST['opcion'],'titulo'=>$titulo),array('rpt_name'=>'ReporteCuentas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcionT=$reporte->GetJavaFunction();
	
			$html.="		<br><center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:$funcionT\"> IMPRIMIR </a></label></center>";	
	
			$html .= "$mostrarT";
			*/
			$direccion="app_modules/Facturacion/reports/html/ReporteImpCuentas.php?";
			$html.="		<br><center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion','".$_REQUEST['enlace']."','".$_REQUEST['opcion']."','$titulo');\"> IMPRIMIR </a></label></center>";	
			
			$html .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
			$html .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
			$html .= "</form>";
			
			$html .= "<script>\n";
			$html .= "	function reportecuentas(dir,enl,op,tit)\n";
			$html .= "	{\n";
			$html .= "		var url=dir+'enlace='+enl+'&opcion='+op+'&titulo='+tit;\n";
			$html .= "		window.open(url,'REPORTE CUENTAS','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			
			$html .= ThemeCerrarTabla();
			
			return $html;
		}

		function FrmConsultaPacientesTP() 
		{
			$accionR=ModuloGetURL('app','Cuentas','user','LlamaFrmListadoPacientesUHA');
			$accionV=ModuloGetURL('app','Cuentas','user','LlamaFrmGeneracionReportes');
			
			$planes=$this->LlamaGetPlanes();
			
			if(!$_REQUEST['estado_plan'])
			{
				$check1="checked";
				$_REQUEST['estado_plan']=1;
			}
			else
			{
				switch($_REQUEST['estado_plan'])
				{
					case 1:
						$check1="checked";
					break;
					
					case 2:
						$check2="checked";
					break;
					
					case 3:
						$check3="checked";
					break;
				}
			
			}
				
			$html = "				<script>\n";
			$html .= "					xajax_GetEstadoPlanes('".$_REQUEST['estado_plan']."');";
			$html .= "				</script>\n";
			
			$html .= ThemeAbrirTabla('BUSQUEDA PACIENTES - CUENTAS');
			$html .= "<form name=\"forma_reporte\" action=\"$accionR\" method=\"post\">\n";
			$html .= "	<table align=\"center\" width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "		<tr align=\"center\">\n";
			$html .= "			<td align=\"center\" width=\"30%\" class=\"modulo_table_list_title\">FECHAS : </td>";
			$html .= "			<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">";
			$html .= "				DE <input type=\"text\" name=\"fecha_ini\" size=\"10\" readonly value=\"".$_REQUEST['fecha_ini']."\" class=\"input-text\">";
			$html .= "				<sub>".ReturnOpenCalendario("forma_reporte","fecha_ini","-")."</sub>";
			$html .= "				A <input type=\"text\" name=\"fecha_fin\" size=\"10\" readonly value=\"".$_REQUEST['fecha_fin']."\" class=\"input-text\">";
			$html .= "				<sub>".ReturnOpenCalendario("forma_reporte","fecha_fin","-")."</sub>";
			$html .= "			</td>";
			$html .= "		</tr>\n";
			$html .= "		<tr align=\"center\">\n";
			$html .= "			<td align=\"center\" class=\"modulo_table_list_title\" width=\"30%\">ESTADO PLAN : </td>";
			$html .= "			<td align=\"center\" class=\"modulo_list_claro\" width=\"25%\"><input type=\"radio\" name=\"estado_plan\" value=\"1\" $check1 onclick=\"xajax_GetEstadoPlanes('1');\"> ACTIVOS </td>";
			$html .= "			<td align=\"center\" class=\"modulo_list_claro\" width=\"25%\"><input type=\"radio\" name=\"estado_plan\" value=\"2\" $check2 onclick=\"xajax_GetEstadoPlanes('2');\"> INACTIVOS</td>";
			$html .= "			<td align=\"center\" class=\"modulo_list_claro\" width=\"20%\"><input type=\"radio\" name=\"estado_plan\" value=\"3\" $check3 onclick=\"xajax_GetEstadoPlanes('3');\"> TODOS</td>";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" align=\"center\">\n";
			$html .= "			<td align=\"center\" width=\"30%\">PLAN : </td>";
			$html .= "			<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\" id=\"capa_plan\">";
	
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\" align=\"center\">\n";
			$html .= "			<td align=\"center\" colspan=\"5\"><input class=\"input-submit\" type=\"submit\" name=\"filtrar\" value=\"FILTRAR\">";
			$html .= "			&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"LIMPIAR\" onclick=\"this.form.fecha_ini.value='';this.form.fecha_fin.value='';this.form.planes.value='';this.form.estado_plan[0].checked=true;this.form.estado_plan[1].checked=false;this.form.estado_plan[2].checked=false;xajax_GetEstadoPlanes('1');\"></td>";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			
			$html .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
			$html .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
			$html .= "</form>";
		
			$html .= ThemeCerrarTabla();
			
			return $html;
		}

		function FrmTotalFacturaCredito($error)
		{
			$accion1= ModuloGetURL('app','Cuentas','user','LlamaFrmReporteFC');
			$accion2=ModuloGetURL('app','Cuentas','user','LlamaFrmGeneracionReportes');
			$planes=$this->LlamaGetPlanes();
			$html = "<label class=\"label_error\">$error</label>\n";
			$html .= ThemeAbrirTabla("REPORTES TOTAL FACTURAS CREDITO");
			$html .= "            <form name=\"BUSCAR1\" action=\"".$accion1."\" method=\"post\">\n";
			$html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";         
			$html .= "			<tr align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "                       <td>";
			$html .= "                         DIGITE EL PLAN A BUSCAR ";
			$html .= "                       </td>\n";
			$html .= "			<td>";
			$html .= "		            <select width=\"40%\" name=\"planes\" class=\"select\">";
			$html .= "			    <option value=\"\">--PLAN--</option>\n";
			foreach($planes as $plan)
			{
				$sel="";
				if($plan['plan_id']==$_REQUEST['planes'])
				$sel="selected";
				$html .= "					<option value=\"".$plan['plan_id']."\" $sel>".$plan['plan_descripcion']."</option>\n";
			}
			$html .= "			    </select>\n";
			$html .= "			</td>\n";
			$html .= "			</tr>\n";
					
			$html .= "			<tr align=\"center\" class=\"modulo_list_claro\">\n";
			if(!empty($_REQUEST['FechaI']))
			{
				$f=explode('-',$_REQUEST['FechaI']);
				$i=$f[2].'/'.$f[1].'/'.$f[0];
			}
			$html .= "                    <td class=\"".$this->SetStyle("FechaI")."\">DESDE: </td>";
			$html .= "                    <td <input type=\"text\" class=\"input-text\" name=\"FechaI\" value=\"".$i."\">".ReturnOpenCalendario('BUSCAR1','FechaI','/')."</td>";
			$html .= "			</tr>";	
			
			$html .= "			<tr align=\"center\" class=\"modulo_list_claro\">\n";
			if(!empty($_REQUEST['FechaF']))
			{
				$f=explode('-',$_REQUEST['FechaF']);
				$fi=$f[2].'/'.$f[1].'/'.$f[0];
			}
			$html .= "                    <td class=\"".$this->SetStyle("FechaF")."\">HASTA: </td>";
			$html .= "                    <td <input type=\"text\" class=\"input-text\" name=\"FechaF\" value=\"".$fi."\">".ReturnOpenCalendario('BUSCAR1','FechaF','/')."</td>";
			$html .= "			</tr>\n";
			$html .= "                       <tr colspan=\"11\" class=\"modulo_list_claro\" align=\"center\">\n";
			$html .= "                       <td>";
			$html .= "                          <input type=\"submit\" class=\"input-submit\" value=\"BUSCAR\">\n";
			$html .= "                       </td>";
			$html .= "		</form>\n";
			$html .= "		<form name=\"VOLVER\" action=\"".$accion2."\" method=\"post\">\n";
			$html .= "                       <td>";
			$html .= "                          <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
			$html .= "                       </td>\n";
			$html .= "                     </tr>\n";
			$html .= "                 </table>\n";         
			$html .= "		</form>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}

		function FrmListadoPacientesUHA()
		{
			$html = ThemeAbrirTabla('LISTADO PACIENTES HOSPITALIZACION - URGENCIAS - AMBULATORIO');
	
			$accionV=ModuloGetURL('app','Cuentas','user','LlamaFrmConsultaPacientesTP',array('fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin'],'planes'=>$_REQUEST['planes'],'estado_plan'=>$_REQUEST['estado_plan']));
			
			$fecha_ini=str_replace("/","-",$this->FechaStamp($_REQUEST['fecha_ini']));
			$fecha_fin=str_replace("/","-",$this->FechaStamp($_REQUEST['fecha_fin']));
	
			$pacientes[0]=$this->LlamaListadoPacientesAtendidos($fecha_ini,$fecha_fin,$_REQUEST['planes'],1);
			$pacientes[1]=$this->LlamaListadoPacientesAtendidos($fecha_ini,$fecha_fin,$_REQUEST['planes'],2);
			$pacientes[2]=$this->LlamaListadoPacientesAtendidos($fecha_ini,$fecha_fin,$_REQUEST['planes'],3);
			
			$html .= "<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			if($pacientes[0])
			{
				$html .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$html .= "		<td colspan=\"6\">PACIENTES HOSPITALIZADOS</td>";
				$html .= "	</tr>\n";
				$html .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$html .= "		<td width=\"10%\">CUENTA</td>";
				$html .= "		<td width=\"15%\">IDENTIFICACION</td>";
				$html .= "		<td width=\"30%\">NOMBRE PACIENTE</td>";
				$html .= "		<td width=\"15%\">VALOR CARGOS</td>";
				$html .= "		<td width=\"15%\">CARGOS HABITACION</td>";
				$html .= "		<td width=\"15%\">VALOR CARGO + HAB</td>";
				$html .= "	</tr>\n";
				$a=0;
				
				foreach($pacientes[0] as $key=>$valor)
				{
					if($a%2==0)
						$estilo="modulo_list_oscuro";
					else
						$estilo="modulo_list_claro";
					
	
					$html .= "	<tr class=\"$estilo\" align=\"center\">\n";
					$html .= "		<td>".$valor['numerodecuenta']."</td>";
					$html .= "		<td>".$valor['tipo_id_paciente']."-".$valor['paciente_id']."</td>";
					$html .= "		<td>".$valor['nombre_completo']."</td>";
					
					$val1=$this->LlamaGetEstancia($valor['numerodecuenta'],$fecha_ini,$fecha_fin);
					
					$html .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto'])."</td>";
					$html .= "		<td align=\"right\"> $ ".FormatoValor($val1)."</td>";
					$html .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto']+$val1)."</td>";
					
					$html .= "	</tr>\n";
					
					$pacientes[0][$key]['sum_a']=$valor['valor_cubierto'];
					$pacientes[0][$key]['habitacion']=$val1;
					$pacientes[0][$key]['cargo_mas_hab']=$valor['valor_cubierto']+$val1;
					
					$sum_a+=$valor['valor_cubierto'];
					$s_per_a+=$val1;
					$cargo_mas_hab_a+=$valor['valor_cubierto']+$val1;
					$a++;
				}
				$html .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
				$html .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES HOSPITALIZADOS : $a</td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($sum_a)."</td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($s_per_a)."</td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($cargo_mas_hab_a)."</td>";
				$html .= "	</tr>\n";
			}
			if($pacientes[1])
			{
				$html .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$html .= "		<td colspan=\"6\">PACIENTES EN URGENCIAS</td>";
				$html .= "	</tr>\n";
				$html .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$html .= "		<td>CUENTA</td>";
				$html .= "		<td>IDENTIFICACION</td>";
				$html .= "		<td>NOMBRE PACIENTE</td>";
				$html .= "		<td>VALOR CARGOS</td>";
				$html .= "		<td>CARGOS HABITACION</td>";
				$html .= "		<td>VALOR CARGO + HAB</td>";
				$html .= "	</tr>\n";
				$b=0;
				
				foreach($pacientes[1] as $key=>$valor)
				{
					if($b%2==0)
						$estilo="modulo_list_oscuro";
					else
						$estilo="modulo_list_claro";
					
					$html .= "	<tr class=\"$estilo\" align=\"center\">\n";
					$html .= "		<td>".$valor['numerodecuenta']."</td>";
					$html .= "		<td>".$valor['tipo_id_paciente']."-".$valor['paciente_id']."</td>";
					$html .= "		<td>".$valor['nombre_completo']."</td>";
					
					$val2=$this->LlamaGetEstancia($valor['numerodecuenta'],$fecha_ini,$fecha_fin);
	
					$html .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto'])."</td>";
					$html .= "		<td align=\"right\"> $ ".FormatoValor($val2)."</td>";
					$html .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto']+$val2)."</td>";
					
					$html .= "	</tr>\n";
	
					$pacientes[1][$key]['sum_b']=$valor['valor_cubierto'];
					$pacientes[1][$key]['habitacion']=$val2;
					$pacientes[1][$key]['cargo_mas_hab']=$valor['valor_cubierto']+$val2;
					
					$sum_b+=$valor['valor_cubierto'];
					$s_per_b+=$val2;
					$cargo_mas_hab_b+=$valor['valor_cubierto']+$val2;
					$b++;
				}
				$html .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
				$html .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES EN URGENCIAS : $b </td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($sum_b)."</td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($s_per_b)."</td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($cargo_mas_hab_b)."</td>";
				$html .= "	</tr>\n";
			}
			if($pacientes[2])
			{
				$html .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$html .= "		<td colspan=\"6\">PACIENTES AMBULATORIOS</td>";
				$html .= "	</tr>\n";
				$html .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$html .= "		<td>CUENTA</td>";
				$html .= "		<td>IDENTIFICACION</td>";
				$html .= "		<td>NOMBRE PACIENTE</td>";
				$html .= "		<td>VALOR CARGOS</td>";
				$html .= "		<td>CARGOS HABITACION</td>";
				$html .= "		<td>VALOR CARGO + HAB</td>";
				$html .= "	</tr>\n";
				$c=0;
				
				foreach($pacientes[2] as $key=>$valor)
				{
					
					if($c%2==0)
						$estilo="modulo_list_oscuro";
					else
						$estilo="modulo_list_claro";
					
					$html .= "	<tr class=\"$estilo\" align=\"center\">\n";
					$html .= "		<td>".$valor['numerodecuenta']."</td>";
					$html .= "		<td>".$valor['tipo_id_paciente']."-".$valor['paciente_id']."</td>";
					$html .= "		<td>".$valor['nombre_completo']."</td>";
					
					$val3=$this->LlamaGetEstancia($valor['numerodecuenta'],$fecha_ini,$fecha_fin);
					
					$html .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto'])."</td>";
					$html .= "		<td align=\"right\"> $ ".FormatoValor($val3)."</td>";
					$html .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto']+$val3)."</td>";
					$html .= "	</tr>\n";
	
					$pacientes[2][$key]['sum_b']=$valor['valor_cubierto'];
					$pacientes[2][$key]['habitacion']=$val3;
					$pacientes[2][$key]['cargo_mas_hab']=$valor['valor_cubierto']+$val3;
					
					$sum_c+=$valor['valor_cubierto'];
					$s_per_c+=$val3;
					$cargo_mas_hab_c+=$valor['valor_cubierto']+$val3;
					$c++;
				}
				$html .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
				$html .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES EN AMBULATORIO : $c </td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($sum_c)."</td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($s_per_c)."</td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($cargo_mas_hab_c)."</td>";
				$html .= "	</tr>\n";
				$n_total=$a+$b+$c;
				$suma_total=$sum_a+$sum_b+$sum_c;
				$s_per_total=$s_per_a+$s_per_b+$s_per_c;
				$car_hab_total=$cargo_mas_hab_a+$cargo_mas_hab_b+$cargo_mas_hab_c;
				$html .= "	<tr class=\"hc_table_submodulo_list_title\" align=\"center\">\n";
				$html .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES : $n_total </td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($suma_total)."</td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($s_per_total)."</td>";
				$html .= "		<td align=\"right\"> $ ".FormatoValor($car_hab_total)."</td>";
				$html .= "	</tr>\n";
			}
			
			$html .= "</table>\n";
	
			if(empty($pacientes[0]) AND empty($pacientes[1]) AND empty($pacientes[2]))
				$html .= "<p align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS</p>";
		
			$_SESSION['list_1']=$pacientes;
	
			$direccion="app_modules/Facturacion/reports/html/ReportePacientesCuentas.php";
			$html.="		<br><center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion');\"> IMPRIMIR </a></label></center>";	
			
			$html .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
			$html .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
			$html .= "</form>";
			
			$html .= "<script>\n";
			$html .= "	function reportecuentas(url)\n";
			$html .= "	{\n";
			$html .= "		window.open(url,'REPORTE CUENTAS','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
			$html .= "	}\n";
			$html .= "</script>\n";
	
			$html .= ThemeCerrarTabla();
			return $html;
		}

		function FrmReporteFC ()
		{
			IncludeClass('Facturacion','','app','Facturacion');
			$consulta=new Facturacion();
			$vector = $consulta->Totalfacturascredito($_REQUEST['planes'],$_REQUEST['FechaI'],$_REQUEST['FechaF']);
			$accion1= ModuloGetURL('app','Cuentas','user','LlamaFrmTotalFacturaCredito');
			$RUTA = "app_modules/Facturacion/reports/html/Total_FacturasCredito.report.php?plan=".$_REQUEST['planes']."&fechai=".$_REQUEST['FechaI']."&fechaf=".$_REQUEST['FechaF']."";
			$mostrar.="   <script>";
			$mostrar.="  function abreVentanaTotalFC(){\n";
			$mostrar.="    var nombre=\"\"\n";
			$mostrar.="    var url2=\"\"\n";
			$mostrar.="    var str=\"\"\n";
			$mostrar.="    var ALTO=screen.height\n";
			$mostrar.="    var ANCHO=screen.width\n";
			$mostrar.="    var nombre=\"REPORTE\";\n";
			$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes,toolbar=1\";\n";
			$mostrar.="    var url2 ='$RUTA';\n";
			$mostrar.="    window.open(url2, nombre, str)};\n";
			$mostrar.="   </script>";
			$html .= $mostrar;
			$html .= ThemeAbrirTabla("REPORTE TOTAL FACTURAS CREDITO");
			$html .= "            <form name=\"VOLVER2\" action=\"".$accion1."\" method=\"post\">\n";
			$html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
			$html .= $this->DatosEmpresa();
			$html .= " </table>";
			$html .= "<br><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
			$html .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
			$html .= "        <td>INGRESO No.</td>";
			$html .= "        <td>PACIENTE</td>";
			$html .= "        <td>IDENTIFICACION</td>";
			$html .= "        <td>CUENTA No.</td>";
			$html .= "        <td>FECHA INGRESO</td>";
			$html .= "        <td>FECHA EGRESO</td>";
			$html .= "        <td>FACTURA No.</td>";
			$html .= "        <td>VALOR FACTURA</td>";
			$html .= "        <td>VALOR PAGADO PACIENTE</td>";
			$html .= "        <td>ESTADO FACTURA</td>";
			$html .= "        <td>PLAN</td>";
			$html .= "      </tr>";
			for($i=0;$i<sizeof($vector);$i++)
			{
					if( $i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$pago= $vector[$i]['abono_efectivo']+$vector[$i]['abono_cheque']+$vector[$i]['abono_tarjetas']+$vector[$i]['abono_chequespf']+$vector[$i]['abono_letras']+$vector[$i]['valor_cuota_paciente'];
					$html .= "      <tr class=\"$estilo\">";
					$html .= "        <td align=\"center\">".$vector[$i]['ingreso']."</td>";
					$html .= "        <td align=\"center\">".$vector[$i]['primer_nombre']."  ".  $vector[$i]['segundo_nombre']."  ".  $vector[$i]['primer_apellido']."  ".  $vector[$i]['segundo_apellido']."</td>";
					$html .= "        <td align=\"center\">".$vector[$i]['paciente_id']."</td>";
					$html .= "        <td align=\"center\">".$vector[$i]['numerodecuenta']."</td>";
					$html .= "        <td align=\"center\">".$vector[$i]['fecha_ingreso']."</td>";
					$html .= "        <td align=\"center\">".$vector[$i]['fecha_cierre']."</td>";
					$html .= "        <td align=\"center\">".$vector[$i]['factura_fiscal']."</td>";
					$html .= "        <td align=\"center\">".$vector[$i]['total_factura']."</td>";
					$html .= "        <td align=\"center\">".$pago."</td>";
					$html .= "        <td align=\"center\">".$vector[$i]['estado']."</td>";
					$html .= "        <td align=\"center\">".$vector[$i]['plan_descripcion']."</td>";
					$html .= "      </tr>";
			}
		
			$html .= " </table><br>";
			$html .= "		</form>\n";
			$direccion="app_modules/Facturacion/reports/html/Total_FacturasCredito.report.php";
			$html.="		<center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:abreVentanaTotalFC('$direccion');\"> IMPRIMIR </a></label></center><br>";
			$html .= "            <form name=\"VOLVER2\" action=\"".$accion1."\" method=\"post\">\n";
			$html .= "              <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
			$html .= "            </form>";
			$html .= ThemeCerrarTabla();
			return $html;
		}

		function DatosEmpresa()
		{
			$datos=$this->LlamaDatosEncabezadoEmpresa();
			$this->salida .= "<br>\n";
			$this->salida .= "	<table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >\n";
			$this->salida .= " 		<tr class=\"modulo_table_title\" height=\"21\">\n";
			$this->salida .= " 			<td width=\"10%\">EMPRESA</td>\n";
			$this->salida .= " 			<td class=\"modulo_list_claro\" >".$datos[razon_social]."</td>\n";
			$this->salida .= " 		</tr>\n";
			$this->salida .= " </table>\n";
		}

		function LlamaDatosEncabezadoEmpresa()
		{
			$censo = new ReportesCenso();
			$dat = $censo->DatosEncabezadoEmpresa();
			return $dat;
		}

		function GetDiasHospitalizacion($fecha_ingreso)
		{
			$date1=date('Y-m-d H:i:s');
			$fecha_in=explode(".",$fecha_ingreso);
			$fecha_ingreso=$fecha_in[0];
			$date2=$fecha_ingreso;
			$s = strtotime($date1)-strtotime($date2);
			$d = intval($s/86400);
			$s -= $d*86400;
			$h = intval($s/3600);
			$s -= $h*3600;
			$m = intval($s/60);
			$s -= $m*60;
			$dif= (($d*24)+$h).hrs." ".$m."min";
			$dif2= $d;
			return $dif2;
		}//Fin GetDiasHospitalizacion
	
		function LlamaListadoHospitalizados()
		{
			$censo = new ReportesCenso();
			$dat = $censo->ListadoHospitalizados();
			return $dat;
		}
	
		function LlamaListadoObservacionUrgencias()
		{
			$censo = new ReportesCenso();
			$dat = $censo->ListadoObservacionUrgencias();
			return $dat;
		}
	
		function LlamaReportesCuentas($opcion)
		{
			$censo = new ReportesCenso();
			$dat = $censo->ReportesCuentas($opcion);
			return $dat;
		}
	
		function LlamaGetEstancia($cuenta)
		{
			$censo = new ReportesCenso();
			$dat = $censo->GetEstancia($cuenta);
			return $dat;
		}
	
		function LlamaGetCamas($estacionId,$op)
		{
			$censo = new ReportesCenso();
			$dat = $censo->GetCamas($estacionId,$op);
			return $dat;
		}
	
		function LlamaListadoPacientesAtendidos($fecha_ini,$fecha_fin,$tplan,$tipo)
		{
			$censo = new ReportesCenso();
			$dat = $censo->ListadoPacientesAtendidos($fecha_ini,$fecha_fin,$tplan,$tipo);
			return $dat;
		}

		function LlamaGetPlanes()
		{
			$censo = new ReportesCenso();
			$dat = $censo->GetPlanes();
			return $dat;
		}
		/**
		* Se encarga de separar la fecha del formato timestamp
		* @access private
		* @return string
		* @param date fecha
		*/
		function FechaStamp($fecha)
		{
			if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				//return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
				return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
			}
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
	}
?>
