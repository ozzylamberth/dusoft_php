<?php
  /******************************************************************************
  * $Id: InformacionCuentasHTML.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.7 $ 
	* 
	* @autor
  ********************************************************************************/
	IncludeClass('InformacionCuentas','','app','Cuentas');
	class InformacionCuentasHTML
	{
		function InformacionCuentasHTML(){}
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

		///***
		///FormacionCuentas
		///***
/*
		function FormaInformacionCuentas($EmpresaId,$Cuenta) 
		{
						global $VISTA;
						//factura detalleda
						$RUTA = $_ROOT ."cache/factura$Cuenta.pdf";
						$mostrar ="\n<script>\n";
						$mostrar.="var rem=\"\";\n";
						$mostrar.="  function abreVentana(){\n";
						$mostrar.="    var nombre=\"\"\n";
						$mostrar.="    var url2=\"\"\n";
						$mostrar.="    var str=\"\"\n";
						$mostrar.="    var ALTO=screen.height\n";
						$mostrar.="    var ANCHO=screen.width\n";
						$mostrar.="    var nombre=\"REPORTE\";\n";
						$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
						$mostrar.="    var url2 ='$RUTA';\n";
						$mostrar.="    rem = window.open(url2, nombre, str)};\n";
						//factura conceptos
						$RUTA = $_ROOT ."cache/facturaconceptos.pdf";
						$mostrar.="var rem=\"\";\n";
						$mostrar.="  function abreVentana2(){\n";
						$mostrar.="    var nombre=\"\"\n";
						$mostrar.="    var url2=\"\"\n";
						$mostrar.="    var str=\"\"\n";
						$mostrar.="    var ALTO=screen.height\n";
						$mostrar.="    var ANCHO=screen.width\n";
						$mostrar.="    var nombre=\"REPORTE\";\n";
						$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
						$mostrar.="    var url2 ='$RUTA';\n";
						$mostrar.="    rem = window.open(url2, nombre, str)};\n";
						$mostrar.="</script>\n";
						$html ="$mostrar";
						$var=$this->LlamaDatosFactura($Cuenta);
  			//if($_SESSION['CUENTAS']['SWCUENTAS']=='Cerradas')//CUENTAS ACTIVAS - INACTIVAS
				//{
						$html .= "<table width=\"65%\" align=\"center\">\n";
						$html .= "    <tr>";
						IncludeLib("reportes/factura");
						GenerarFactura($var);
						//$html .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA\" onclick=\"javascript:abreVentana()\"></td>";
						$html .= "      <td class=\"label_mark\"><a href=\"javascript:abreVentana()\">FACTURA</a></td>";
						IncludeLib("reportes/facturaconceptos");
						GenerarFacturaConceptos($var);
						//$html .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CONCEPTOS\" onclick=\"javascript:abreVentana2()\"></td>";
						$html .= "      <td class=\"label_mark\"><a href=\"javascript:abreVentana2()\">FACTURA CONCEPTOS</a></td>";

				//}

					$Detalle=$this->LlamaBuscarDetalleCuenta($Cuenta);
  				if($Detalle)
					{
						$acchoja=ModuloGetURL('app','Cuentas','user','LlamarVentanaFinal',array('numerodecuenta'=>$Cuenta,'plan_id'=>$PlanId,'tipoid'=>$TipoId,'pacienteid'=>$PacienteId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Transaccion'=>$Transaccion,'Dev'=>$Dev,'vars'=>$vars,'Estado'=>$Estado,'tiporeporte'=>'reportes'));
						//$html .= "             <form name=\"reportes\" action=\"$acchoja\" method=\"post\">";
						$html .= "               <td class=\"label_mark\"><label class='label_mark'>Tipo Hoja Cargos: </label><select name=\"reporteshojacargos\" class=\"select\">";
						//$html .=" <option value='-1'>----SELECCIONE----</option>";
						$reportes=$this->LlamaTraerReportesHojaCargos($EmpresaId);
						for($i=0; $i<sizeof($reportes); $i++)
						{
										$html .=" <option value=\"".$reportes[$i][ruta_reporte].",".$reportes[$i][titulo]."\">".$reportes[$i][titulo]."</option>";
						}
						$html .= "              </select>";
						$html .= "              <a href=\"$acchoja\">VER</a></td>";
						//$html .= "<td align = \"left\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VER\"><br></td></form>";

					}
			$html .= "    </tr>";
			$html .= "</table>\n";
			return $html;
		}
*/
		/**
		**
		**/
		function FormaInformacionCuentas($EmpresaId,$Ingreso,$Cuenta,$PlanId,&$obj,$sw_tipo_plan) 
		{
			if($EmpresaId AND $Cuenta AND $PlanId)
			{
				SessionSetVar('EmpresaId',$EmpresaId);
				SessionSetVar('Cuenta',$Cuenta);
				SessionSetVar('PlanId',$PlanId);
			}
			else
			{
				$EmpresaId = SessionGetVar('EmpresaId');
				$Cuenta = SessionGetVar('Cuenta');
				$PlanId = SessionGetVar('PlanId');
			}
			$var = $this->LlamaBuscarFacturas($EmpresaId,$Cuenta);
			foreach($var AS $i => $v)
			{
				if($v[sw_clase_factura]=='0' AND $v[tipo_factura]=='0')//contado - paciente
				{
					$prefijoPac = $v[prefijo];
					$facturaPac = $v[factura_fiscal];
					$facPac=true;
				}
				if($var[sw_clase_factura]=='0' AND $v[tipo_factura]=='2')//contado - particular
				{
					$prefijoPac = $v[prefijo];
					$facturaPac = $v[factura_fiscal];
					$facPac=true;
				}
				if($v[sw_clase_factura]=='1' AND $v[tipo_factura]=='1')//credito - cliente
				{
					$prefijoCli = $v[prefijo];
					$facturaCli = $v[factura_fiscal];
					$facClie=true;
				}
			}
			if(sizeof($var)==0)
			{
				$estado = $this->LlamaBuscarFacturas($EmpresaId,$Cuenta,'Estado');
				$mensaje ='La Cuenta No. '.$Cuenta.' esta ';

				if($estado[0][estado]=='ACTIVA')
					$mensaje .= 'activa.'; 
				elseif($estado[0][estado]=='INACTIVA')
					$mensaje .= 'inactiva.'; 
				elseif($estado[0][estado]=='CUADRADA')
					$mensaje .= 'cuadrada.'; 
				elseif($estado[0][estado]=='FACTURADA')
					$mensaje .= 'facturada.'; 
			}
			else
			if(!empty($facClie) AND !empty($facPac))
			{  $mensaje='La Cuenta No. '.$Cuenta.' ha sido FACTURADA, el Número de Factura Cliente Asignada fue: '.$prefijoCli.' '.$facturaCli.', el Número de Factura Paciente Asignada fue: '.$prefijoPac.' '.$facturaPac.'';   }
			elseif(!empty($facClie))
			{  $mensaje='La Cuenta No. '.$Cuenta.' ha sido FACTURADA, el Número de Factura Cliente Asignada fue: '.$prefijoCli.' '.$facturaCli.', NO SE GENERO FACTURA PARA EL PACIENTE';   }
			elseif(!empty($facPac))
			{  $mensaje='La Cuenta No. '.$Cuenta.' ha sido FACTURADA, el Número de Factura Paciente Asignada fue: '.$prefijoPac.' '.$facturaPac.', NO SE GENERO FACTURA PARA EL CLIENTE';   }
			elseif(empty($facClie) AND empty($facPac))
			{  $mensaje='La Cuenta No. '.$Cuenta.' ha sido CERRADA, no se genera Factura Cliente ni Factura Paciente, debido a que el valor a pagar es cero.';   }
			$html = $this->LlamaFormaImpresionFacturas($EmpresaId,$mensaje,$Cuenta,$prefijoPac,$facturaPac,$prefijoCli,$facturaCli,$PlanId,&$obj,$sw_tipo_plan,$Ingreso);
			return $html;
		}

		/**
		**
		**/
		function LlamaBuscarFacturas($EmpresaId,$Cuenta,$estado)
		{
			$fact = new InformacionCuentas();
			$dat = $fact->BuscarFacturas($EmpresaId,$Cuenta,$estado);
			return $dat;
		}

		/**
		**
		**/
		function LlamaFormaImpresionFacturas($EmpresaId,$mensaje,$Cuenta,$prefijoPac,$facturaPac,$prefijoCli,$facturaCli,$PlanId,&$obj,$sw_tipo_plan,$Ingreso)
		{
	
/*
			IncludeFile('app_Facturar_user','','app','Facturar');
			$facturas = new app_Facturar_user();
*/
			$rutaVolver = SessionGetVar("AccionVolverCargos");
			$acc = $obj->ReturnModuloExterno('app','Facturar','user');
			$acc->SetActionVolver($rutaVolver);

			IncludeClass('FacturarHTML','','app','Facturar');
			$fact = new FacturarHTML();
			//$facturas = $fact->ReturnModuloExterno('app','Facturar','user');
			$html = $fact->FormaFacturarImpresion($EmpresaId,$mensaje,$Cuenta,$prefijoPac,$facturaPac,$prefijoCli,$facturaCli,$PlanId,&$obj,$sw_tipo_plan,$Ingreso);
			return $html;
		}

		/**
		**
		**/
		function LlamaDatosFactura($Cuenta)
		{
			$fact = new InformacionCuentas();
			$dat = $fact->DatosFactura($Cuenta);
			return $dat;
		}

		/**
		**
		**/
		function LlamaBuscarDetalleCuenta($Cuenta)
		{
			$fact = new InformacionCuentas();
			$dat = $fact->BuscarDetalleCuenta($Cuenta);
			return $dat;
		}

		/**
		**
		**/
		function LlamaTraerReportesHojaCargos($EmpresaId)
		{
			$fact = new InformacionCuentas();
			$dat = $fact->TraerReportesHojaCargos($EmpresaId);
			return $dat;
		}
	}
?>