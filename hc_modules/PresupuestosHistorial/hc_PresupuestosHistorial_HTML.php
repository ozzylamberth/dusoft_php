
<?php

// $Id: hc_PresupuestosHistorial_HTML.php,v 1.2 2005/07/07 21:29:01 jorge Exp $

class PresupuestosHistorial_HTML extends PresupuestosHistorial
{

	function PresupuestosHistorial_HTML()
	{
		$this->PresupuestosHistorial();//constructor del padre
		return true;
	}

	function SetStyle($campo)
	{
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr><td align=\"center\" class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("hc_tderror");
		}
		return ("hc_tdlabel");
	}

	function frmForma()//Desde esta funcion es de JORGE ELIÉCER AVILA
	{
		$pfj=$this->frmPrefijo;
		$this->salida =ThemeAbrirTablaSubModulo('PLAN DE TRATAMIENTO Y PRESUPUESTO - HISTORIAL');
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=\"hc_table_list_title\">";
		$this->salida.="<td width=\"10%\" align=\"center\">";
		$this->salida.="PLAN TTO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"8%\" align=\"center\">";
		$this->salida.="CARGO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"41%\" align=\"center\">";
		$this->salida.="DESCRIPCIÓN ACTIVIDAD";
		$this->salida.="</td>";
		$this->salida.="<td width=\"5%\" align=\"center\">";
		$this->salida.="CANT.";
		$this->salida.="</td>";
		$this->salida.="<td width=\"9%\" align=\"center\">";
		$this->salida.="V. UNITARIO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"9%\" align=\"center\">";
		$this->salida.="V. TOTAL";
		$this->salida.="</td>";
		$this->salida.="<td width=\"9%\" align=\"center\">";
		$this->salida.="V. USUARIO CM/CPG";
		$this->salida.="</td>";
		$this->salida.="<td width=\"9%\" align=\"center\">";
		$this->salida.="V. S.O.S.";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		IncludeLib("funciones_facturacion");
		IncludeLib("tarifario_cargos");
		$cuentas=$this->BuscarCuentas($this->cuenta);
		$tiposplanes=$this->BuscarTiposPlanTratamiento();
		$valorcubierto=$valornocubierto=$valorcuota=$valorcopago=$valorcuenta=0;
		$ciclo=sizeof($tiposplanes);
		$j=0;
		for($i=0;$i<$ciclo;$i++)
		{/*2,3,4,8*/
			if($j==0)
			{
				$color="class=\"hc_submodulo_list_claro\"";
				$j=1;
			}
			else
			{
				$color="class=\"hc_submodulo_list_oscuro\"";
				$j=0;
			}
			$this->salida.="<tr $color>";
			if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==1)
			{
				$this->salida.="<td rowspan=\"2\" align=\"center\" class=\"hc_table_list_title\">";
			}
			else
			{
				$this->salida.="<td align=\"center\" class=\"hc_table_list_title\">";
			}
			$this->salida.="".$tiposplanes[$i]['descripcion']."";
			$this->salida.="</td>";
			if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==1 OR
			$tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==3 OR
			$tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==4 OR
			$tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==8)
			{
				$this->salida.="<td align=\"center\" $color colspan=\"7\">";
				$cargos =$this->BuscarCargosPresActivo($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']);
				$ciclo1=sizeof($cargos);
				for($k=0;$k<$ciclo1;$k++)
				{
					$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
					if(!empty($validados))
					{
						$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
						$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
						$this->salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
						$this->salida.="	<tr $color>";
						$this->salida.="	<td align=\"center\" width=\"8%\">";
						$this->salida.="".$cargos[$k]['cargo']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"center\" width=\"46%\">";
						$this->salida.="".$cargos[$k]['descripcion']."";
						$detalle=$this->BuscarCargosActivo($cargos[$k]['cargo']);
						$ciclo2=sizeof($detalle);
						for($w=0;$w<$ciclo2;$w++)
						{
							if($detalle[$w]['estado']=='0')
							{
								$this->salida.="".' -- '."".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."";
							}
							else if($detalle[$w]['estado']=='1' OR $detalle[$w]['estado']=='4')
							{
								$this->salida.="".' -- '."<label class=\"label_mark\">".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."</label>";
							}
							else if($detalle[$w]['estado']=='2')
							{
								$this->salida.="".' -- '."<strike>".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."</strike>";
							}
							else
							{
								$this->salida.="".' -- '."<label class=\"label_error\">".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."</label>";
							}
						}
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"center\" width=\"6%\">";
						$this->salida.="".$cargos[$k]['cantidad']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['cargos'][0]['precio_plan']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['cargos'][0]['valor_cargo']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['valor_total_paciente']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['valor_total_empresa']."";
						$this->salida.="	</td>";
						$this->salida.="	</tr>";
						$this->salida.="	</table>";
						$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
						$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
						$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
						$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
						$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
					}
				}
				if($ciclo1==0 OR $cargos==NULL)
				{
					$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
					$this->salida.="	<tr $color>";
					$this->salida.="	<td align=\"center\" width=\"100%\">";
					$this->salida.="	</td>";
					$this->salida.="	</tr>";
					$this->salida.="	</table>";
				}
				if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==1)
				{
					$this->salida.="<tr $color>";
					$this->salida.="<td align=\"center\" $color colspan=\"7\">";
					$cargos =$this->BuscarPlanTratamientoCargo($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']);
					$ciclo1=sizeof($cargos);
					for($k=0;$k<$ciclo1;$k++)
					{
						$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
						if(!empty($validados))
						{
							$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
							$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
							$this->salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
							$this->salida.="	<tr $color>";
							$this->salida.="	<td align=\"center\" width=\"8%\">";
							$this->salida.="".$cargos[$k]['cargo']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"center\" width=\"46%\">";
							$this->salida.="".$cargos[$k]['descripcion']."";
							if($cargos[$k]['estado']=='0')
							{
								$this->salida.="".' -- '."SOLUCIONADO";
							}
							else if($cargos[$k]['estado']=='1')
							{
								$this->salida.="".' -- '."<label class=\"label_mark\">POR REALIZAR</label>";
							}
							else if($cargos[$k]['estado']=='4')
							{
								$this->salida.="".' -- '."<label class=\"label_mark\">EN TRATAMI.</label>";
							}
							else if($cargos[$k]['estado']=='2')
							{
								$this->salida.="".' -- '."<strike>CANCELADO</strike>";
							}
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"center\" width=\"6%\">";
							$this->salida.="".$cargos[$k]['cantidad']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['cargos'][0]['precio_plan']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['cargos'][0]['valor_cargo']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['valor_total_paciente']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['valor_total_empresa']."";
							$this->salida.="	</td>";
							$this->salida.="	</tr>";
							$this->salida.="	</table>";
							$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
							$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
							$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
							$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
							$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
						}
					}
					if($ciclo1==0 OR $cargos==NULL)
					{
						$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
						$this->salida.="	<tr $color>";
						$this->salida.="	<td align=\"center\" width=\"100%\">";
						$this->salida.="	</td>";
						$this->salida.="	</tr>";
						$this->salida.="	</table>";
					}
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
			}/*otro*/
			else if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==2)
			{
				$this->salida.="<td align=\"center\" $color colspan=\"7\">";
				$cargos =$this->BuscarApoyosOdontologia();
				$ciclo1=sizeof($cargos);
				for($k=0;$k<$ciclo1;$k++)
				{
					$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
					if(!empty($validados))
					{
						$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
						$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
						$this->salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
						$this->salida.="	<tr $color>";
						$this->salida.="	<td align=\"center\" width=\"8%\">";
						$this->salida.="".$cargos[$k]['cargo']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"center\" width=\"46%\">";
						$this->salida.="".$cargos[$k]['descripcion']."";
						if($cargos[$k]['estado']=='0')
						{
							$this->salida.="".' -- '."SOLUCIONADO";
						}
						else if($cargos[$k]['estado']=='1')
						{
							$this->salida.="".' -- '."<label class=\"label_mark\">POR REALIZAR</label>";
						}
						else if($cargos[$k]['estado']=='4')
						{
							$this->salida.="".' -- '."<label class=\"label_mark\">EN TRATAMI.</label>";
						}
						else if($cargos[$k]['estado']=='2')
						{
							$this->salida.="".' -- '."<strike>CANCELADO</strike>";
						}
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"center\" width=\"6%\">";
						$this->salida.="".$cargos[$k]['cantidad']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['cargos'][0]['precio_plan']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['cargos'][0]['valor_cargo']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['valor_total_paciente']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['valor_total_empresa']."";
						$this->salida.="	</td>";
						$this->salida.="	</tr>";
						$this->salida.="	</table>";
						$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
						$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
						$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
						$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
						$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
					}
				}
				if($ciclo1==0 OR $cargos==NULL)
				{
					$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
					$this->salida.="	<tr $color>";
					$this->salida.="	<td align=\"center\" width=\"100%\">";
					$this->salida.="	</td>";
					$this->salida.="	</tr>";
					$this->salida.="	</table>";
				}
			}
			else
			{
				$this->salida.="<td align=\"center\" $color colspan=\"7\">";
				$cargos =$this->BuscarPlanTratamientoCargo($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']);
				$ciclo1=sizeof($cargos);
				for($k=0;$k<$ciclo1;$k++)
				{
					$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
					if(!empty($validados))
					{
						$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
						$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
						$this->salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
						$this->salida.="	<tr $color>";
						$this->salida.="	<td align=\"center\" width=\"8%\">";
						$this->salida.="".$cargos[$k]['cargo']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"center\" width=\"46%\">";
						$this->salida.="".$cargos[$k]['descripcion']."";
						if($cargos[$k]['estado']=='0')
						{
							$this->salida.="".' -- '."SOLUCIONADO";
						}
						else if($cargos[$k]['estado']=='1')
						{
							$this->salida.="".' -- '."<label class=\"label_mark\">POR REALIZAR</label>";
						}
						else if($cargos[$k]['estado']=='4')
						{
							$this->salida.="".' -- '."<label class=\"label_mark\">EN TRATAMI.</label>";
						}
						else if($cargos[$k]['estado']=='2')
						{
							$this->salida.="".' -- '."<strike>CANCELADO</strike>";
						}
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"center\" width=\"6%\">";
						$this->salida.="".$cargos[$k]['cantidad']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['cargos'][0]['precio_plan']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['cargos'][0]['valor_cargo']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['valor_total_paciente']."";
						$this->salida.="	</td>";
						$this->salida.="	<td align=\"right\" width=\"10%\">";
						$this->salida.="".$resul['valor_total_empresa']."";
						$this->salida.="	</td>";
						$this->salida.="	</tr>";
						$this->salida.="	</table>";
						$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
						$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
						$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
						$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
						$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
					}
				}
				if($ciclo1==0 OR $cargos==NULL)
				{
					$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
					$this->salida.="	<tr $color>";
					$this->salida.="	<td align=\"center\" width=\"100%\">";
					$this->salida.="	</td>";
					$this->salida.="	</tr>";
					$this->salida.="	</table>";
				}
			}
		}
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		$this->salida.="VALOR CUBIERTO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		//$this->salida.="".$valorcubierto."";
		$this->salida.="".$valorcuenta."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		$this->salida.="VALOR NO CUBIERTO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		$this->salida.="".$valornocubierto."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		$this->salida.="VALOR CUOTA MODERADORA";
		$this->salida.="</td>";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		$this->salida.="".$valorcuota."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		$this->salida.="VALOR COPAGO";
		$this->salida.="</td>";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		$this->salida.="".$valorcopago."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		$this->salida.="TOTAL CUENTA";
		$this->salida.="</td>";
		$this->salida.="<td width=\"50%\" align=\"right\">";
		//$salida.="".$valorcuenta."";
		$this->salida.="".$valorcuenta+$valorcopago+$valorcuota+$valornocubierto."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmCancelar()
	{
		$pfj=$this->frmPrefijo;
		$this->salida =ThemeAbrirTablaSubModulo('CANCELAR UN PROCEDIMIENTO DE PLAN DE TRATAMIENTO Y PRESUPUESTO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'insertarjustif',
		'odondetadi'.$pfj=>$_REQUEST['odondetadi'.$pfj]));
		$datos=$this->BuscarParaCancelar($_REQUEST['odondetadi'.$pfj]);
		$this->salida.="<form name=\"forma1$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="PLAN DE TRATAMIENTO";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td width=\"20%\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="UBICACIÓN";
		$this->salida.="</td>";
		$this->salida.="<td width=\"80%\" align=\"left\">".$datos['hc_tipo_ubicacion_diente_id']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="SUPERFICIE";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$datos['des1']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="HALLAZGO";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$datos['des2']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="SOLUCIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"left\">".$datos['des3']."";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
		$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.="JUSTIFICACIÓN";
		$this->salida.="</td>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<textarea class=\"textarea\" name=\"justificac".$pfj."\" cols=\"100\" rows=\"3\">".$_REQUEST['justificac'.$pfj]."</textarea>";
		$this->salida.="</td>" ;
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table width=\"10%\" align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR JUSTIFICACIÓN\" class=\"input-submit\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\" width=\"40%\">";
		$this->salida.="<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmCargosPlan()
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('MODIFICAR CARGOS DEL PLAN DE TRATAMIENTO Y PRESUPUESTO');
		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'modificarotros',
		'tipo_plan_tratam'.$pfj=>$_REQUEST['tipo_plan_tratam'.$pfj],
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
		$this->salida.="<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA DESCRIPCIÓN DE ACTIVIDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">CODIGO:";
		$this->salida.="</td>";
		$this->salida.="<td width=\"5%\" align='center'>";
		$this->salida.="<input type='text' class='input-text' size=6 maxlength=6 name='codigo$pfj'>";
		$this->salida.="</td>" ;
		$this->salida.="<td width=\"9%\">ACTIVIDAD:";
		$this->salida.="</td>";
		$this->salida.="<td width=\"46%\" align='center'>";
		$this->salida.="<input type='text' size=50 class='input-text' name='diagnostico$pfj' value=\"".$_REQUEST['diagnostico'.$pfj]."\">";
		$this->salida.="</td>" ;
		$this->salida.="<td width=\"5%\" align='center'>";
		$this->salida.="<input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$vectorD=$this->BuscarCargosPlan();
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table><br>";
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'insertaraotros',
		'tipo_plan_tratam'.$pfj=>$_REQUEST['tipo_plan_tratam'.$pfj],
		'vector'.$pfj=>$vectorD));
		if($vectorD)
		{
			$this->salida.="<form name=\"formades2$pfj\" action=\"$accionI\" method=\"post\">";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">CARGO</td>";
			$this->salida.="<td width=\"62%\">DESCRIPCIÓN</td>";
			$this->salida.="<td width=\"8%\" >CANTIDAD</td>";
			$this->salida.="<td width=\"8%\" >OPCIÓN</td>";
			$this->salida.="<td width=\"12%\">PLAN</td>";
			$this->salida.="</tr>";
			$busquedas=$this->BuscarTiposPlanTratamiento();
			for($i=0;$i<sizeof($vectorD);$i++)
			{
				if( $i % 2)
				{
					$estilo='modulo_list_claro';
				}
				else
				{
					$estilo='modulo_list_oscuro';
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\">".$vectorD[$i]['cargo']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"left\">".$vectorD[$i]['descripcion']."";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				$this->salida.="<input type=\"text\" class=\"input-text\" name=\"cantidad".$i.$pfj."\" value=\"".$vectorD[$i]['cantidad']."\" maxlength=\"5\" size=\"5\">";
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				if($vectorD[$i]['guarda']==NULL)
				{
					$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['cargo'].">";
				}
				else if($vectorD[$i]['descripcion']<>NULL)
				{
					$this->salida.="<input type=checkbox name=\"ayudas".$i.$pfj."\" value=".$vectorD[$i]['cargo']." checked>";
				}
				$this->salida.="</td>";
				$this->salida.="<td align=\"center\">";
				for($m=0;$m<sizeof($busquedas);$m++)
				{
					if($vectorD[$i]['hc_tipo_plan_tratamiento_id']==$busquedas[$m]['hc_tipo_plan_tratamiento_id'])
					{
						$this->salida.="".$busquedas[$m]['descripcion']."";
					}
				}
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"right\" colspan=\"5\">";
			$this->salida.="<input class=\"input-submit\" name=\"guardar".$pfj."\" type=\"submit\" value=\"GUARDAR\">";
			$this->salida.="</td>";
			$this->salida.="</form>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraDiagnosticos_Avanzada();
			if(!empty($var))
			{
				$this->salida.="<table border=\"0\" width=\"60%\" align=\"center\">";
				$this->salida.="<tr>";
				$this->salida.="<td width=\"100%\" align=\"center\">";
				$this->salida.=$var;
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
			}
		}
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.="<table align=\"center\" border=\"0\"  width=\"40%\">";
		$this->salida.="<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida.="</td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array(
		'accion'.$pfj=>'modificarotros',
		'tipo_plan_tratam'.$pfj=>$_REQUEST['tipo_plan_tratam'.$pfj],
		'conteo'.$pfj=>$this->conteo,
		'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'area'.$pfj=>$_REQUEST['area'.$pfj]));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function frmConsulta()
	{
		$cuentas=$this->BuscarCuentas($this->cuenta);
		////////////////////////////////////////////
		//SOLUCION TEMPORAL PARA LA LLEGADA DEL PLAN
		////////////////////////////////////////////
		$this->plan = $this->BuscarPlan();
		////////////////////////////////////////////
		if($cuentas===false)
		{
			return false;
		}
		if(sizeof($cuentas)!=0)
		{
			$this->salida.="<br>";
			$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td colspan=\"9\" align=\"center\">PRESUPUESTO ODONTOLOGICO";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\" align=\"center\">";
			$this->salida.="PLAN TTO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"8%\" align=\"center\">";
			$this->salida.="CARGO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"41%\" align=\"center\">";
			$this->salida.="DESCRIPCIÓN ACTIVIDAD";
			$this->salida.="</td>";
			$this->salida.="<td width=\"5%\" align=\"center\">";
			$this->salida.="CANT.";
			$this->salida.="</td>";
			$this->salida.="<td width=\"9%\" align=\"center\">";
			$this->salida.="V. UNITARIO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"9%\" align=\"center\">";
			$this->salida.="V. TOTAL";
			$this->salida.="</td>";
			$this->salida.="<td width=\"9%\" align=\"center\">";
			$this->salida.="V. USUARIO CM/CPG";
			$this->salida.="</td>";
			$this->salida.="<td width=\"9%\" align=\"center\">";
			$this->salida.="V. S.O.S.";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			IncludeLib("funciones_facturacion");
			IncludeLib("tarifario_cargos");
			$tiposplanes=$this->BuscarTiposPlanTratamiento();
			$valorcubierto=$valornocubierto=$valorcuota=$valorcopago=$valorcuenta=0;
			$ciclo=sizeof($tiposplanes);
			$j=0;
			for($i=0;$i<$ciclo;$i++)
			{/*2,3,4,8*/
				if($j==0)
				{
					$color="class=\"hc_submodulo_list_claro\"";
					$j=1;
				}
				else
				{
					$color="class=\"hc_submodulo_list_oscuro\"";
					$j=0;
				}
				$this->salida.="<tr $color>";
				if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==1)
				{
					$this->salida.="<td rowspan=\"2\" align=\"center\" class=\"hc_table_list_title\">";
				}
				else
				{
					$this->salida.="<td align=\"center\" class=\"hc_table_list_title\">";
				}
				$this->salida.="".$tiposplanes[$i]['descripcion']."";
				$this->salida.="</td>";
				if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==1 OR
				$tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==3 OR
				$tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==4 OR
				$tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==8)
				{
					$this->salida.="<td align=\"center\" $color colspan=\"7\">";
					$cargos =$this->BuscarCargosPresActivo($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']);
					$ciclo1=sizeof($cargos);
					for($k=0;$k<$ciclo1;$k++)
					{
						$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
						if(!empty($validados))
						{
							$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
							$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
							$this->salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
							$this->salida.="	<tr $color>";
							$this->salida.="	<td align=\"center\" width=\"8%\">";
							$this->salida.="".$cargos[$k]['cargo']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"center\" width=\"46%\">";
							$this->salida.="".$cargos[$k]['descripcion']."";
							$detalle=$this->BuscarCargosActivo($cargos[$k]['cargo']);
							$ciclo2=sizeof($detalle);
							for($w=0;$w<$ciclo2;$w++)
							{
								if($detalle[$w]['estado']=='0')
								{
									$this->salida.="".' -- '."".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."";
								}
								else if($detalle[$w]['estado']=='1' OR $detalle[$w]['estado']=='4')
								{
									$this->salida.="".' -- '."<label class=\"label_mark\">".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."</label>";
								}
								else if($detalle[$w]['estado']=='2')
								{
									$this->salida.="".' -- '."<strike>".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."</strike>";
								}
								else
								{
									$this->salida.="".' -- '."<label class=\"label_error\">".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."</label>";
								}
							}
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"center\" width=\"6%\">";
							$this->salida.="".$cargos[$k]['cantidad']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['cargos'][0]['precio_plan']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['cargos'][0]['valor_cargo']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['valor_total_paciente']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['valor_total_empresa']."";
							$this->salida.="	</td>";
							$this->salida.="	</tr>";
							$this->salida.="	</table>";
							$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
							$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
							$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
							$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
							$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
						}
					}
					if($ciclo1==0 OR $cargos==NULL)
					{
						$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
						$this->salida.="	<tr $color>";
						$this->salida.="	<td align=\"center\" width=\"100%\">";
						$this->salida.="	</td>";
						$this->salida.="	</tr>";
						$this->salida.="	</table>";
					}
					if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==1)
					{
						$this->salida.="<tr $color>";
						$this->salida.="<td align=\"center\" $color colspan=\"6\">";
						$cargos =$this->BuscarPlanTratamientoCargo($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']);
						$ciclo1=sizeof($cargos);
						for($k=0;$k<$ciclo1;$k++)
						{
							$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
							if(!empty($validados))
							{
								$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
								$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
								$this->salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
								$this->salida.="	<tr $color>";
								$this->salida.="	<td align=\"center\" width=\"8%\">";
								$this->salida.="".$cargos[$k]['cargo']."";
								$this->salida.="	</td>";
								$this->salida.="	<td align=\"center\" width=\"46%\">";
								$this->salida.="".$cargos[$k]['descripcion']."";
								if($cargos[$k]['estado']=='0')
								{
									$this->salida.="".' -- '."SOLUCIONADO";
								}
								else if($cargos[$k]['estado']=='1')
								{
									$this->salida.="".' -- '."<label class=\"label_mark\">POR REALIZAR</label>";
								}
								else if($cargos[$k]['estado']=='4')
								{
									$this->salida.="".' -- '."<label class=\"label_mark\">EN TRATAMI.</label>";
								}
								else if($cargos[$k]['estado']=='2')
								{
									$this->salida.="".' -- '."<strike>CANCELADO</strike>";
								}
								$this->salida.="	</td>";
								$this->salida.="	<td align=\"center\" width=\"6%\">";
								$this->salida.="".$cargos[$k]['cantidad']."";
								$this->salida.="	</td>";
								$this->salida.="	<td align=\"right\" width=\"10%\">";
								$this->salida.="".$resul['cargos'][0]['precio_plan']."";
								$this->salida.="	</td>";
								$this->salida.="	<td align=\"right\" width=\"10%\">";
								$this->salida.="".$resul['cargos'][0]['valor_cargo']."";
								$this->salida.="	</td>";
								$this->salida.="	<td align=\"right\" width=\"10%\">";
								$this->salida.="".$resul['valor_total_paciente']."";
								$this->salida.="	</td>";
								$this->salida.="	<td align=\"right\" width=\"10%\">";
								$this->salida.="".$resul['valor_total_empresa']."";
								$this->salida.="	</td>";
								$this->salida.="	</tr>";
								$this->salida.="	</table>";
								$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
								$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
								$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
								$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
								$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
							}
						}
						if($ciclo1==0 OR $cargos==NULL)
						{
							$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
							$this->salida.="	<tr $color>";
							$this->salida.="	<td align=\"center\" width=\"100%\">";
							$this->salida.="	</td>";
							$this->salida.="	</tr>";
							$this->salida.="	</table>";
						}
						$this->salida.="</td>";
						$this->salida.="</tr>";
					}
				}/*otro*/
				else if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==2)
				{
					$this->salida.="<td align=\"center\" $color colspan=\"7\">";
					$cargos =$this->BuscarApoyosOdontologia();
					$ciclo1=sizeof($cargos);
					for($k=0;$k<$ciclo1;$k++)
					{
						$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
						if(!empty($validados))
						{
							$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
							$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
							$this->salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
							$this->salida.="	<tr $color>";
							$this->salida.="	<td align=\"center\" width=\"8%\">";
							$this->salida.="".$cargos[$k]['cargo']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"center\" width=\"46%\">";
							$this->salida.="".$cargos[$k]['descripcion']."";
							if($cargos[$k]['estado']=='0')
							{
								$this->salida.="".' -- '."SOLUCIONADO";
							}
							else if($cargos[$k]['estado']=='1')
							{
								$this->salida.="".' -- '."<label class=\"label_mark\">POR REALIZAR</label>";
							}
							else if($cargos[$k]['estado']=='4')
							{
								$this->salida.="".' -- '."<label class=\"label_mark\">EN TRATAMI.</label>";
							}
							else if($cargos[$k]['estado']=='2')
							{
								$this->salida.="".' -- '."<strike>CANCELADO</strike>";
							}
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"center\" width=\"6%\">";
							$this->salida.="".$cargos[$k]['cantidad']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['cargos'][0]['precio_plan']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['cargos'][0]['valor_cargo']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['valor_total_paciente']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['valor_total_empresa']."";
							$this->salida.="	</td>";
							$this->salida.="	</tr>";
							$this->salida.="	</table>";
							$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
							$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
							$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
							$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
							$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
						}
					}
					if($ciclo1==0 OR $cargos==NULL)
					{
						$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
						$this->salida.="	<tr $color>";
						$this->salida.="	<td align=\"center\" width=\"100%\">";
						$this->salida.="	</td>";
						$this->salida.="	</tr>";
						$this->salida.="	</table>";
					}
				}
				else
				{
					$this->salida.="<td align=\"center\" $color colspan=\"6\">";
					$cargos =$this->BuscarPlanTratamientoCargo($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']);
					$ciclo1=sizeof($cargos);
					for($k=0;$k<$ciclo1;$k++)
					{
						$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
						if(!empty($validados))
						{
							$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
							$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
							$this->salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
							$this->salida.="	<tr $color>";
							$this->salida.="	<td align=\"center\" width=\"8%\">";
							$this->salida.="".$cargos[$k]['cargo']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"center\" width=\"46%\">";
							$this->salida.="".$cargos[$k]['descripcion']."";
							if($cargos[$k]['estado']=='0')
							{
								$this->salida.="".' -- '."SOLUCIONADO";
							}
							else if($cargos[$k]['estado']=='1')
							{
								$this->salida.="".' -- '."<label class=\"label_mark\">POR REALIZAR</label>";
							}
							else if($cargos[$k]['estado']=='4')
							{
								$this->salida.="".' -- '."<label class=\"label_mark\">EN TRATAMI.</label>";
							}
							else if($cargos[$k]['estado']=='2')
							{
								$this->salida.="".' -- '."<strike>CANCELADO</strike>";
							}
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"center\" width=\"6%\">";
							$this->salida.="".$cargos[$k]['cantidad']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['cargos'][0]['precio_plan']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['cargos'][0]['valor_cargo']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['valor_total_paciente']."";
							$this->salida.="	</td>";
							$this->salida.="	<td align=\"right\" width=\"10%\">";
							$this->salida.="".$resul['valor_total_empresa']."";
							$this->salida.="	</td>";
							$this->salida.="	</tr>";
							$this->salida.="	</table>";
							$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
							$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
							$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
							$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
							$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
						}
					}
					if($ciclo1==0 OR $cargos==NULL)
					{
						$this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
						$this->salida.="	<tr $color>";
						$this->salida.="	<td align=\"center\" width=\"100%\">";
						$this->salida.="	</td>";
						$this->salida.="	</tr>";
						$this->salida.="	</table>";
					}
				}
			}
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="<table width=\"60%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			$this->salida.="VALOR CUBIERTO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			//$this->salida.="".$valorcubierto."";
			$this->salida.="".$valorcuenta."";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			$this->salida.="VALOR NO CUBIERTO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			$this->salida.="".$valornocubierto."";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			$this->salida.="VALOR CUOTA MODERADORA";
			$this->salida.="</td>";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			$this->salida.="".$valorcuota."";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			$this->salida.="VALOR COPAGO";
			$this->salida.="</td>";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			$this->salida.="".$valorcopago."";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			$this->salida.="TOTAL CUENTA";
			$this->salida.="</td>";
			$this->salida.="<td width=\"50%\" align=\"right\">";
			//$salida.="".$valorcuenta."";
			$this->salida.="".$valorcuenta+$valorcopago+$valorcuota+$valornocubierto."";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="<br>";
		}
		else
		{
			return false;
		}
		return true;
	}


	function frmHistoria()
	{
		$cuentas=$this->BuscarCuentas($this->cuenta);
		////////////////////////////////////////////
		//SOLUCION TEMPORAL PARA LA LLEGADA DEL PLAN
		////////////////////////////////////////////
		$this->plan = $this->BuscarPlan();
		////////////////////////////////////////////
		if($cuentas===false)
		{
			return false;
		}
		if(sizeof($cuentas)!=0)
		{
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="<td colspan=\"9\" align=\"center\">PRESUPUESTO ODONTOLOGICO";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td width=\"10%\" align=\"center\">";
			$salida.="PLAN TTO";
			$salida.="</td>";
			$salida.="<td width=\"8%\" align=\"center\">";
			$salida.="CARGO";
			$salida.="</td>";
			$salida.="<td width=\"41%\" align=\"center\">";
			$salida.="DESCRIPCIÓN ACTIVIDAD";
			$salida.="</td>";
			$salida.="<td width=\"5%\" align=\"center\">";
			$salida.="CANT.";
			$salida.="</td>";
			$salida.="<td width=\"9%\" align=\"center\">";
			$salida.="V. UNITARIO";
			$salida.="</td>";
			$salida.="<td width=\"9%\" align=\"center\">";
			$salida.="V. TOTAL";
			$salida.="</td>";
			$salida.="<td width=\"9%\" align=\"center\">";
			$salida.="V. USUARIO CM/CPG";
			$salida.="</td>";
			$salida.="<td width=\"9%\" align=\"center\">";
			$salida.="V. S.O.S.";
			$salida.="</td>";
			$salida.="</tr>";
			IncludeLib("funciones_facturacion");
			IncludeLib("tarifario_cargos");
			$tiposplanes=$this->BuscarTiposPlanTratamiento();
			$valorcubierto=$valornocubierto=$valorcuota=$valorcopago=$valorcuenta=0;
			$ciclo=sizeof($tiposplanes);
			$j=0;
			for($i=0;$i<$ciclo;$i++)
			{/*2,3,4,8*/
				if($j==0)
				{
					$color="class=\"hc_submodulo_list_claro\"";
					$j=1;
				}
				else
				{
					$color="class=\"hc_submodulo_list_oscuro\"";
					$j=0;
				}
				$salida.="<tr $color>";
				if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==1)
				{
					$salida.="<td rowspan=\"2\" align=\"center\" class=\"hc_table_list_title\">";
				}
				else
				{
					$salida.="<td align=\"center\" class=\"hc_table_list_title\">";
				}
				$salida.="".$tiposplanes[$i]['descripcion']."";
				$salida.="</td>";
				if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==1 OR
				$tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==3 OR
				$tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==4 OR
				$tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==8)
				{
					$salida.="<td align=\"center\" $color colspan=\"7\">";
					$cargos =$this->BuscarCargosPresActivo($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']);
					$ciclo1=sizeof($cargos);
					for($k=0;$k<$ciclo1;$k++)
					{
						$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
						if(!empty($validados))
						{
							$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
							$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
							$salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
							$salida.="	<tr $color>";
							$salida.="	<td align=\"center\" width=\"8%\">";
							$salida.="".$cargos[$k]['cargo']."";
							$salida.="	</td>";
							$salida.="	<td align=\"center\" width=\"46%\">";
							$salida.="".$cargos[$k]['descripcion']."";
							$detalle=$this->BuscarCargosActivo($cargos[$k]['cargo']);
							$ciclo2=sizeof($detalle);
							for($w=0;$w<$ciclo2;$w++)
							{
								if($detalle[$w]['estado']=='0')
								{
									$salida.="".' -- '."".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."";
								}
								else if($detalle[$w]['estado']=='1' OR $detalle[$w]['estado']=='4')
								{
									$salida.="".' -- '."<label class=\"label_mark\">".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."</label>";
								}
								else if($detalle[$w]['estado']=='2')
								{
									$salida.="".' -- '."<strike>".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."</strike>";
								}
								else
								{
									$salida.="".' -- '."<label class=\"label_error\">".$detalle[$w]['hc_tipo_ubicacion_diente_id']."".'-'."".$detalle[$w]['descripcion']."</label>";
								}
							}
							$salida.="	</td>";
							$salida.="	<td align=\"center\" width=\"6%\">";
							$salida.="".$cargos[$k]['cantidad']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['cargos'][0]['precio_plan']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['cargos'][0]['valor_cargo']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['valor_total_paciente']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['valor_total_empresa']."";
							$salida.="	</td>";
							$salida.="	</tr>";
							$salida.="	</table>";
							$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
							$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
							$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
							$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
							$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
						}
					}
					if($ciclo1==0 OR $cargos==NULL)
					{
						$salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
						$salida.="	<tr $color>";
						$salida.="	<td align=\"center\" width=\"100%\">";
						$salida.="	</td>";
						$salida.="	</tr>";
						$salida.="	</table>";
					}
					if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==1)
					{
						$salida.="<tr $color>";
						$salida.="<td align=\"center\" $color colspan=\"6\">";
						$cargos =$this->BuscarPlanTratamientoCargo($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']);
						$ciclo1=sizeof($cargos);
						for($k=0;$k<$ciclo1;$k++)
						{
							$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
							if(!empty($validados))
							{
								$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
								$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
								$salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
								$salida.="	<tr $color>";
								$salida.="	<td align=\"center\" width=\"8%\">";
								$salida.="".$cargos[$k]['cargo']."";
								$salida.="	</td>";
								$salida.="	<td align=\"center\" width=\"46%\">";
								$salida.="".$cargos[$k]['descripcion']."";
								if($cargos[$k]['estado']=='0')
								{
									$salida.="".' -- '."SOLUCIONADO";
								}
								else if($cargos[$k]['estado']=='1')
								{
									$salida.="".' -- '."<label class=\"label_mark\">POR REALIZAR</label>";
								}
								else if($cargos[$k]['estado']=='4')
								{
									$salida.="".' -- '."<label class=\"label_mark\">EN TRATAMI.</label>";
								}
								else if($cargos[$k]['estado']=='2')
								{
									$salida.="".' -- '."<strike>CANCELADO</strike>";
								}
								$salida.="	</td>";
								$salida.="	<td align=\"center\" width=\"6%\">";
								$salida.="".$cargos[$k]['cantidad']."";
								$salida.="	</td>";
								$salida.="	<td align=\"right\" width=\"10%\">";
								$salida.="".$resul['cargos'][0]['precio_plan']."";
								$salida.="	</td>";
								$salida.="	<td align=\"right\" width=\"10%\">";
								$salida.="".$resul['cargos'][0]['valor_cargo']."";
								$salida.="	</td>";
								$salida.="	<td align=\"right\" width=\"10%\">";
								$salida.="".$resul['valor_total_paciente']."";
								$salida.="	</td>";
								$salida.="	<td align=\"right\" width=\"10%\">";
								$salida.="".$resul['valor_total_empresa']."";
								$salida.="	</td>";
								$salida.="	</tr>";
								$salida.="	</table>";
								$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
								$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
								$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
								$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
								$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
							}
						}
						if($ciclo1==0 OR $cargos==NULL)
						{
							$salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
							$salida.="	<tr $color>";
							$salida.="	<td align=\"center\" width=\"100%\">";
							$salida.="	</td>";
							$salida.="	</tr>";
							$salida.="	</table>";
						}
						$salida.="</td>";
						$salida.="</tr>";
					}
				}/*otro*/
				else if($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']==2)
				{
					$salida.="<td align=\"center\" $color colspan=\"7\">";
					$cargos =$this->BuscarApoyosOdontologia();
					$ciclo1=sizeof($cargos);
					for($k=0;$k<$ciclo1;$k++)
					{
						$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
						if(!empty($validados))
						{
							$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
							$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
							$salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
							$salida.="	<tr $color>";
							$salida.="	<td align=\"center\" width=\"8%\">";
							$salida.="".$cargos[$k]['cargo']."";
							$salida.="	</td>";
							$salida.="	<td align=\"center\" width=\"46%\">";
							$salida.="".$cargos[$k]['descripcion']."";
							if($cargos[$k]['estado']=='0')
							{
								$salida.="".' -- '."SOLUCIONADO";
							}
							else if($cargos[$k]['estado']=='1')
							{
								$salida.="".' -- '."<label class=\"label_mark\">POR REALIZAR</label>";
							}
							else if($cargos[$k]['estado']=='4')
							{
								$salida.="".' -- '."<label class=\"label_mark\">EN TRATAMI.</label>";
							}
							else if($cargos[$k]['estado']=='2')
							{
								$salida.="".' -- '."<strike>CANCELADO</strike>";
							}
							$salida.="	</td>";
							$salida.="	<td align=\"center\" width=\"6%\">";
							$salida.="".$cargos[$k]['cantidad']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['cargos'][0]['precio_plan']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['cargos'][0]['valor_cargo']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['valor_total_paciente']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['valor_total_empresa']."";
							$salida.="	</td>";
							$salida.="	</tr>";
							$salida.="	</table>";
							$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
							$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
							$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
							$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
							$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
						}
					}
					if($ciclo1==0 OR $cargos==NULL)
					{
						$salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
						$salida.="	<tr $color>";
						$salida.="	<td align=\"center\" width=\"100%\">";
						$salida.="	</td>";
						$salida.="	</tr>";
						$salida.="	</table>";
					}
				}
				else
				{
					$salida.="<td align=\"center\" $color colspan=\"6\">";
					$cargos =$this->BuscarPlanTratamientoCargo($tiposplanes[$i]['hc_tipo_plan_tratamiento_id']);
					$ciclo1=sizeof($cargos);
					for($k=0;$k<$ciclo1;$k++)
					{
						$validados=ValdiarEquivalencias($this->plan,$cargos[$k]['cargo']);
						if(!empty($validados))
						{
							$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$cargos[$k]['cantidad'],'autorizacion_int'=>'','autorizacion_ext'=>'');
							$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$cuentas[0],$cuentas[1],$cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
							$salida.="	<table width=\"100%\" border=\"1\" align=\"center\" $color>";
							$salida.="	<tr $color>";
							$salida.="	<td align=\"center\" width=\"8%\">";
							$salida.="".$cargos[$k]['cargo']."";
							$salida.="	</td>";
							$salida.="	<td align=\"center\" width=\"46%\">";
							$salida.="".$cargos[$k]['descripcion']."";
							if($cargos[$k]['estado']=='0')
							{
								$salida.="".' -- '."SOLUCIONADO";
							}
							else if($cargos[$k]['estado']=='1')
							{
								$salida.="".' -- '."<label class=\"label_mark\">POR REALIZAR</label>";
							}
							else if($cargos[$k]['estado']=='4')
							{
								$salida.="".' -- '."<label class=\"label_mark\">EN TRATAMI.</label>";
							}
							else if($cargos[$k]['estado']=='2')
							{
								$salida.="".' -- '."<strike>CANCELADO</strike>";
							}
							$salida.="	</td>";
							$salida.="	<td align=\"center\" width=\"6%\">";
							$salida.="".$cargos[$k]['cantidad']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['cargos'][0]['precio_plan']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['cargos'][0]['valor_cargo']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['valor_total_paciente']."";
							$salida.="	</td>";
							$salida.="	<td align=\"right\" width=\"10%\">";
							$salida.="".$resul['valor_total_empresa']."";
							$salida.="	</td>";
							$salida.="	</tr>";
							$salida.="	</table>";
							$valorcubierto=$valorcubierto+$resul['valor_cubierto'];
							$valornocubierto=$valornocubierto+$resul['valor_no_cubierto'];
							$valorcuota=$valorcuota+$resul['valor_cuota_moderadora'];
							$valorcopago=$valorcopago+$resul['valor_cuota_paciente'];
							$valorcuenta=$valorcuenta+$resul['valor_total_empresa'];
						}
					}
					if($ciclo1==0 OR $cargos==NULL)
					{
						$salida.="	<table width=\"100%\" border=\"0\" align=\"center\" $color>";
						$salida.="	<tr $color>";
						$salida.="	<td align=\"center\" width=\"100%\">";
						$salida.="	</td>";
						$salida.="	</tr>";
						$salida.="	</table>";
					}
				}
			}
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="</table>";
			$salida.="<table width=\"60%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$salida.="<td width=\"50%\" align=\"right\">";
			$salida.="VALOR CUBIERTO";
			$salida.="</td>";
			$salida.="<td width=\"50%\" align=\"right\">";
			//$salida.="".$valorcubierto."";
			$salida.="".$valorcuenta."";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$salida.="<td width=\"50%\" align=\"right\">";
			$salida.="VALOR NO CUBIERTO";
			$salida.="</td>";
			$salida.="<td width=\"50%\" align=\"right\">";
			$salida.="".$valornocubierto."";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$salida.="<td width=\"50%\" align=\"right\">";
			$salida.="VALOR CUOTA MODERADORA";
			$salida.="</td>";
			$salida.="<td width=\"50%\" align=\"right\">";
			$salida.="".$valorcuota."";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$salida.="<td width=\"50%\" align=\"right\">";
			$salida.="VALOR COPAGO";
			$salida.="</td>";
			$salida.="<td width=\"50%\" align=\"right\">";
			$salida.="".$valorcopago."";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$salida.="<td width=\"50%\" align=\"right\">";
			$salida.="TOTAL CUENTA";
			$salida.="</td>";
			$salida.="<td width=\"50%\" align=\"right\">";
			//$salida.="".$valorcuenta."";
			$salida.="".$valorcuenta+$valorcopago+$valorcuota+$valornocubierto."";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="</table>";
			$salida.="<br>";
		}
		else
		{
			return false;
		}
		return $salida;
	}

}
?>
