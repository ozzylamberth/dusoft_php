<?php

/**
 * $Id: enviosHTM.report.php,v 1.5 2010/12/06 22:13:37 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class enviosHTM_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
	var $total_nota  = 0;
	var $total_notad = 0;
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function enviosHTM_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}


	function GetMembrete()
	{
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
												'subtitulo'=>'','logo'=>'','align'=>'left'));
			return $Membrete;
	}

	/**
	* Contiene los diferentes tipos de reportes segun la consulta en la tabla "reportes_envios"
	*/
	function CrearReporte()
	{
		$tipo_reporte = $this->datos[tipo_reporte];
		ECHO "-->".$tipo_reporte;
		switch($tipo_reporte){
		/**
		* Reportes para Cali
		*/
			case 'DEFAULT':
			{
				$Salida = $this->ReporteDefault();
				break;
			}
			case 'SOAT':
			{
				$Salida = $this->ReporteSoat();
				break;
			}
			case 'COOMEVAPREPAGO':
			{
				$Salida = $this->ReporteCoomevaPrepago();
				break;
			}
			/**
			*Reportes generados para tulua
			*/
			case 'DEFAULT_TULUA':
			{
				$Salida = $this->ReporteDefaultTulua();
				break;
			}
			case 'SOAT_TULUA':
			{
				$Salida = $this->ReporteSoatTulua();
				break;
			}
			case 'COOMEVAPREPAGO_TULUA':
			{
				$Salida = $this->ReporteCoomevaPrepagoTulua();
				break;
			}
			
			/*
			*REPORTES PARA BUDA
			*/
			case 'DEFAULT_BUGA':
			{
				$Salida = $this->ReporteDefaultBUGA();
				break;
			}
			case 'ENVIO_VALORES_PACIENTE_BUGA':
			{
				$Salida = $this->ReporteValoresPacienteBuga();
				break;
			}
			
			case '':
			{
				$Salida = $this->ReporteDefault();
				break;
			}
		}
		return $Salida;
	}
	
	/**
	******************************************************************************************************
	* REPORTES EXCLUSIVOS PARA CALI
	******************************************************************************************************
	*/
	
	/**
	* REPORTE PARA Coomeva Prepago
	*/
	function ReporteCoomevaPrepago()
	{
			IncludeLib("tarifario");
			IncludeLib("funciones_facturacion");
			$this->ActualizarNroAutorizacion($this->datos[envio]);
			$arr=$this->DetalleEnvio($this->datos[envio]);
			$datos_empresa = $this->DatosEmpresa($arr[0][empresa_id]);
			$nom_empresa = $datos_empresa[razon_social];
			$tipo_id_empresa = $datos_empresa[tipo_id_tercero];
			$id_empresa = $datos_empresa[id];
			$cuentas='';
			$cont=0;
			$Salida .= "<br><br><br>";
			$Salida .= "<table border=0 width=100% align='center'>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$arr[0][envio_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][nombre_tercero]."   ".$arr[0][tipo_tercero_id]." ".$arr[0][tercero_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">DEBE A:</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$nom_empresa."<br>".$tipo_id_empresa."-".$id_empresa."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">POR SERVICIOS PRESTADOS EN: </td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			IF(empty($arr[0][departamento]))
			{  $dpto='TODOS'; }
			else
			{
					list($dbconn) = GetDBconn();
					$query = "select descripcion from departamentos
										where departamento='".$arr[0][departamento]."'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Tabal autorizaiones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					$result->Close();
					$dpto=$result->fields[0];
			}
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td><tr>\n";
			
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">Fecha elaboración:</td>";
			$Salida .= "			</tr>";
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][fecha_registro]."</td>";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><br>\n";

			//$Salida .= "<p align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$this->datos[envio]."</p>";
			$Salida.="<table border=0 WIDTH=100% align=\"CENTER\">\n";
			$total=$k=0;

			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10N\">FACTURA</td>\n";
			$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10N\">VALOR</td>\n";
			$Salida .= "				<td WIDTH=100 class=\"normal_10N\">IDENTIFICACION</td>\n";
			$Salida .= "				<td WIDTH=175 class=\"normal_10N\">USUARIO</td>\n";
			$Salida .= "				<td WIDTH=215 class=\"normal_10N\">AUTORIZACION</td>\n";
			$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10N\">PLAN</td>\n";
			$Salida .= "			</tr>\n";
			for($i=0; $i<sizeof($arr);)
			{
							//$poliza = $this->ConsultaAutorizacion($arr[$i][numerodecuenta]);
							$poliza = $this->ConsultaAutorizacionIngreso($arr[$i][ingreso]);
							$x=0;
							$d=$i+1;
							//es agrupada
							if($arr[$i][prefijo]==$arr[$d][prefijo]
								 AND $arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
							{
									$total+=$arr[$i][total_factura];
									while($arr[$i][prefijo]==$arr[$d][prefijo] AND
												$arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
									{
											if($x==0)
											{
													$Salida .= "			<tr>\n";
													$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
													$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
													$Salida .= "				<td WIDTH=130>&nbsp;</td>\n";
													$Salida .= "				<td WIDTH=215 class=\"normal_10\">AGRUPADA</td>\n";
													$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
													$Salida .= "			</tr>";
													$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
													$x++;
											}
											if($cont==0)
											{  $cuentas .=$arr[$d][numerodecuenta];  $cont++;}
											else
											{  $cuentas .=','.$arr[$d][numerodecuenta];  }
											$d++;
									}
									$i=$d;
							}
							else
							{	//no es agrupada
									$Salida .= "			<tr>\n";
									$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
									$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
									$Salida .= "				<td WIDTH=100 class=\"normal_10\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>\n";
									$Salida .= "				<td WIDTH=175 class=\"normal_10\">".$arr[$i][nombre]."</td>\n";
									$Salida .= "				<td WIDTH=150>$poliza</td>\n";
									$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
									$Salida .= "			</tr>\n";
									
									$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									
									$total+=$arr[$i][total_factura];
									if($cont==0)
									{  $cuentas .=$arr[$i][numerodecuenta];  $cont++;}
									else
									{  $cuentas .=','.$arr[$i][numerodecuenta];  }
									$i++;
							}
							$k++;
			}
			$Salida .= "	</table>\n";
			$Salida .= "		 <br><table WIDTH=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=\"55%\" class=\"normal_10\">TOTAL DOCUMENTOS: </td>\n";
			$Salida .= "				<td>$k</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL FACTURAS ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total)."</td>\n";
			$Salida .= "			</tr>\n";
			if($this->total_nota > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS CREDITO($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_nota)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->total_notad > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS DEBITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_notad)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL ENVIO ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total - $this->total_nota + $this->total_notad)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><BR>\n";
			//profesionales
			//$pro=DatosHonorariosVariasCuentas($cuentas);
			if(!empty($pro))
			{
					$total=0;
					$Salida .= "		 <br><br><br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"left\" class=\"normal_10\">\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"3\">INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "			</tr>\n";
					for($i=0; $i<sizeof($pro);)
					{
							$Salida .= "			<tr>\n";
							$Salida .= "				<td WIDTH=\"5%\" align=\"left\">".$pro[$i][tercero_id]."</td>\n";
							$Salida .= "				<td WIDTH=\"30%\" align=\"left\">".$pro[$i][nombre]."</td>\n";
							$d=$i;
							$valor=0;
							while($pro[$i][tercero_id]==$pro[$d][tercero_id]
							   AND	$pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
							{   $valor += $pro[$d][valor];  $d++; }
							$total+=$valor;
							$i=$d;
							$Salida .= "				<td WIDTH=\"15%\" align=\"left\">".FormatoValor($valor)."</td>\n";
							$Salida .= "			</tr>\n";
					}
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "	</table><BR>\n";
			}
      
      //OBSERVACIONES DEL ENVIO
      if(!empty($arr[0][observaciones]))
      {
        $Salida .= "<br><br>";
        $Salida .= "<table WIDTH=\"60%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
        $Salida .= "      <tr>\n";
        $Salida .= "        <td WIDTH=\"55%\" class=\"normal_10N\">OBSERVACIONES: </td>\n";
        $Salida .= "        <td>".$arr[0][observaciones]."</td>\n";
        $Salida .= "      </tr>\n";
        $Salida .= "  </table>";
      }
      //FIN OBSERVACIONES DEL ENVIO
      
			$Salida .= "<br><br>";
			$Salida .= "<table WIDTH=\"40%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
			$Salida .= "	<tr>";
			$Salida .= "		<td WIDTH=\"10%\" class=\"normal_10\"align=\"left\">Usuario: </td>";
			$Salida .= "		<td class=\"normal_10N\"align=\"left\">".$arr[0][usuario_id]."-".$arr[0][nomusuario]."</td>";
			$Salida .= "	</tr>";
			$Salida .= "	</table>";

			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<table width=\"100%\" border =\"0\"  align=\"left\">\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td>".$this->NombreEmpresa($arr[0][empresa_id])."</td>\n";
			$Salida .= "		<td>Recibi </td>";
			$Salida .= "		<td colspan=\"3\" align=\"left\" valign=\"bottom\"><hr align=\"left\" valign=\"bottom\" width=\"80%\"></hr></td>";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td width =\"35%\">DEPARTAMENTO COBRANZAS</td>\n";
			$Salida .= "		<td width =\"15%\">No. Radica </td>\n";
			$Salida .= "		<td width =\"20%\" align=\"left\" valign=\"bottom\"><hr align=\"left\" width=\"90%\"></hr></td>\n";
			$Salida .= "		<td width =\"10%\">Fecha </td>";
			$Salida .= "		<td  width =\"20%\" align=\"left\" valign=\"bottom\"><hr align=\"left\" width=\"90%\"></hr></td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";
			return $Salida;
	}
	
	
	/**
	* REPORTE PARA SOAT
	*/
	function ReporteSoat()
	{
			IncludeLib("tarifario");
			IncludeLib("funciones_facturacion");
			$arr=$this->DetalleEnvio($this->datos[envio]);
			$datos_empresa = $this->DatosEmpresa($arr[0][empresa_id]);
			$nom_empresa = $datos_empresa[razon_social];
			$tipo_id_empresa = $datos_empresa[tipo_id_tercero];
			$id_empresa = $datos_empresa[id];
			$cuentas='';
			$cont=0;
			$Salida .= "";
			$Salida .= "<table border=0 width=100% align='center'>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$arr[0][envio_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][nombre_tercero]."   ".$arr[0][tipo_tercero_id]." ".$arr[0][tercero_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">DEBE A:</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$nom_empresa."<br>".$tipo_id_empresa."-".$id_empresa."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">POR SERVICIOS PRESTADOS EN: </td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			IF(empty($arr[0][departamento]))
			{  $dpto='TODOS'; }
			else
			{
					list($dbconn) = GetDBconn();
					$query = "select descripcion from departamentos
										where departamento='".$arr[0][departamento]."'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Tabal autorizaiones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					$result->Close();
					$dpto=$result->fields[0];
			}
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td><tr>\n";
			
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">Fecha elaboración:</td>";
			$Salida .= "			</tr>";
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][fecha_registro]."</td>";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><br>\n";

			//$Salida .= "<p align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$this->datos[envio]."</p>";
			$Salida.="<table border=0 WIDTH=100% align=\"CENTER\">\n";
			$total=$k=0;

			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10N\">FACTURA</td>\n";
			$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10N\">VALOR</td>\n";
			$Salida .= "				<td WIDTH=100 class=\"normal_10N\">IDENTIFICACION</td>\n";
			$Salida .= "				<td WIDTH=175 class=\"normal_10N\">USUARIO</td>\n";
			$Salida .= "				<td WIDTH=215 class=\"normal_10N\">POLIZA</td>\n";
			$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10N\">PLAN</td>\n";
			$Salida .= "			</tr>\n";
			for($i=0; $i<sizeof($arr);)
			{
							$poliza = $this->ConsultaPoliza($arr[$i][numerodecuenta]);
							$x=0;
							$d=$i+1;
							//es agrupada
							if($arr[$i][prefijo]==$arr[$d][prefijo]
								 AND $arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
							{
									$total+=$arr[$i][total_factura];
									while($arr[$i][prefijo]==$arr[$d][prefijo] AND
												$arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
									{
											if($x==0)
											{
													$Salida .= "			<tr>\n";
													$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
													$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
													$Salida .= "				<td WIDTH=130>&nbsp;</td>\n";
													$Salida .= "				<td WIDTH=215 class=\"normal_10\">AGRUPADA</td>\n";
													$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
													$Salida .= "			</tr>";
													$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
													$x++;
											}
											
											if($cont==0)
											{  $cuentas .=$arr[$d][numerodecuenta];  $cont++;}
											else
											{  $cuentas .=','.$arr[$d][numerodecuenta];  }
											$d++;
									}
									$i=$d;
							}
							else
							{	//no es agrupada
									$Salida .= "			<tr>\n";
									$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
									$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
									$Salida .= "				<td WIDTH=100 class=\"normal_10\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>\n";
									$Salida .= "				<td WIDTH=175 class=\"normal_10\">".$arr[$i][nombre]."</td>\n";
									$Salida .= "				<td WIDTH=150>$poliza</td>\n";
									$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
									$Salida .= "			</tr>\n";
									$total+=$arr[$i][total_factura];
									
									$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									
									if($cont==0)
									{  $cuentas .=$arr[$i][numerodecuenta];  $cont++;}
									else
									{  $cuentas .=','.$arr[$i][numerodecuenta];  }
									$i++;
							}
							$k++;
			}
			$Salida .= "	</table>\n";
			$Salida .= "		 <br><table WIDTH=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=\"55%\" class=\"normal_10\">TOTAL DOCUMENTOS: </td>\n";
			$Salida .= "				<td>$k</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL FACTURAS ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total)."</td>\n";
			$Salida .= "			</tr>\n";
			if($this->total_nota > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS CREDITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_nota)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->total_notad > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS DEBITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_notad)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL ENVIO ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total - $this->total_nota + $this->total_notad)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><BR>\n";
			//profesionales
			//$pro=DatosHonorariosVariasCuentas($cuentas);
			if(!empty($pro))
			{
					$total=0;
					$Salida .= "		 <br><br><br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"left\" class=\"normal_10\">\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"3\">INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "			</tr>\n";
					for($i=0; $i<sizeof($pro);)
					{
							$Salida .= "			<tr>\n";
							$Salida .= "				<td WIDTH=\"5%\" align=\"left\">".$pro[$i][tercero_id]."</td>\n";
							$Salida .= "				<td WIDTH=\"30%\" align=\"left\">".$pro[$i][nombre]."</td>\n";
							$d=$i;
							$valor=0;
							while($pro[$i][tercero_id]==$pro[$d][tercero_id]
							   AND	$pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
							{   $valor += $pro[$d][valor];  $d++; }
							$total+=$valor;
							$i=$d;
							$Salida .= "				<td WIDTH=\"15%\" align=\"left\">".FormatoValor($valor)."</td>\n";
							$Salida .= "			</tr>\n";
					}
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "	</table><BR>\n";
			}
      
      //OBSERVACIONES DEL ENVIO
      if(!empty($arr[0][observaciones]))
      {
        $Salida .= "<br><br>";
        $Salida .= "<table WIDTH=\"60%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
        $Salida .= "      <tr>\n";
        $Salida .= "        <td WIDTH=\"55%\" class=\"normal_10N\">OBSERVACIONES: </td>\n";
        $Salida .= "        <td>".$arr[0][observaciones]."</td>\n";
        $Salida .= "      </tr>\n";
        $Salida .= "  </table>";
      }
      //FIN OBSERVACIONES DEL ENVIO
      
			$Salida .= "<br><br>";
			$Salida .= "<table WIDTH=\"40%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
			$Salida .= "	<tr>";
			$Salida .= "		<td WIDTH=\"10%\" class=\"normal_10\"align=\"left\">Usuario: </td>";
			$Salida .= "		<td class=\"normal_10N\"align=\"left\">".$arr[0][usuario_id]."-".$arr[0][nomusuario]."</td>";
			$Salida .= "	</tr>";
			$Salida .= "	</table>";
            
			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<table width=\"100%\" border =\"0\"  align=\"left\">\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td>".$this->NombreEmpresa($arr[0][empresa_id])."</td>\n";
			$Salida .= "		<td>Recibi </td>";
			$Salida .= "		<td colspan=\"3\" align=\"left\" valign=\"bottom\"><hr align=\"left\" valign=\"bottom\" width=\"80%\"></hr></td>";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td width =\"35%\">DEPARTAMENTO COBRANZAS</td>\n";
			$Salida .= "		<td width =\"15%\">No. Radica </td>\n";
			$Salida .= "		<td width =\"20%\" align=\"left\" valign=\"bottom\"><hr align=\"left\" width=\"90%\"></hr></td>\n";
			$Salida .= "		<td width =\"10%\">Fecha </td>";
			$Salida .= "		<td  width =\"20%\" align=\"left\" valign=\"bottom\"><hr align=\"left\" width=\"90%\"></hr></td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";
			return $Salida;
	}
	/**
	* REPORTE DEFAULT
	*/
	function ReporteDefault()
	{
			IncludeLib("tarifario");
			IncludeLib("funciones_facturacion");
			$arr=$this->DetalleEnvio($this->datos[envio]);
			$datos_empresa = $this->DatosEmpresa($arr[0][empresa_id]);
			$nom_empresa = $datos_empresa[razon_social];
			$tipo_id_empresa = $datos_empresa[tipo_id_tercero];
			$id_empresa = $datos_empresa[id];
			$cuentas='';
			$cont=0;
			$Salida .= "<br><br><br><br><br><br>";
			$Salida .= "<table border=0 width=100% align='center'>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$arr[0][envio_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][nombre_tercero]."   ".$arr[0][tipo_tercero_id]." ".$arr[0][tercero_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">DEBE A:</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$nom_empresa."<br>".$tipo_id_empresa."-".$id_empresa."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">POR SERVICIOS PRESTADOS EN: </td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			IF(empty($arr[0][departamento]))
			{  $dpto='TODOS'; }
			else
			{
					list($dbconn) = GetDBconn();
					$query = "select descripcion from departamentos
										where departamento='".$arr[0][departamento]."'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Tabal autorizaiones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					$result->Close();
					$dpto=$result->fields[0];
			}
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td><tr>\n";
			
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">Fecha elaboración:</td>";
			$Salida .= "			</tr>";
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][fecha_registro]."</td>";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><br>\n";

			//$Salida .= "<p align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$this->datos[envio]."</p>";
			$Salida.="<table border=0 WIDTH=100% align=\"CENTER\">\n";
			$total=$k=0;

			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10N\">FACTURA</td>\n";
			$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10N\">VALOR</td>\n";
			$Salida .= "				<td WIDTH=130 class=\"normal_10N\">IDENTIFICACION</td>\n";
			$Salida .= "				<td WIDTH=215 class=\"normal_10N\">USUARIO</td>\n";
			$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10N\">PLAN</td>\n";
			$Salida .= "			</tr>\n";
			for($i=0; $i<sizeof($arr);)
			{
							$x=0;
							$d=$i+1;
							//es agrupada
							if($arr[$i][prefijo]==$arr[$d][prefijo]
								 AND $arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
							{
									$total+=$arr[$i][total_factura];
									while($arr[$i][prefijo]==$arr[$d][prefijo] AND
												$arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
									{
											if($x==0)
											{
													$Salida .= "			<tr>\n";
													$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
													$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
													$Salida .= "				<td WIDTH=130>&nbsp;</td>\n";
													$Salida .= "				<td WIDTH=215 class=\"normal_10\">AGRUPADA</td>\n";
													$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
													$Salida .= "			</tr>";
													$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
													$x++;
											}
											
											if($cont==0)
											{  $cuentas .=$arr[$d][numerodecuenta];  $cont++;}
											else
											{  $cuentas .=','.$arr[$d][numerodecuenta];  }
											$d++;
									}
									$i=$d;
							}
							else
							{	//no es agrupada
									$Salida .= "			<tr>\n";
									$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
									$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
									$Salida .= "				<td WIDTH=130 class=\"normal_10\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>\n";
									$Salida .= "				<td WIDTH=215 class=\"normal_10\">".$arr[$i][nombre]."</td>\n";
									$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
									$Salida .= "			</tr>\n";
									$total+=$arr[$i][total_factura];
									
									$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									
									if($cont==0)
									{  $cuentas .=$arr[$i][numerodecuenta];  $cont++;}
									else
									{  $cuentas .=','.$arr[$i][numerodecuenta];  }
									$i++;
							}
							$k++;
			}
			$Salida .= "	</table>\n";
			$Salida .= "		 <br><table WIDTH=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=\"55%\" class=\"normal_10\">TOTAL DOCUMENTOS: </td>\n";
			$Salida .= "				<td>$k</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL FACTURAS ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total)."</td>\n";
			$Salida .= "			</tr>\n";
			if($this->total_nota > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS CREDITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_nota)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->total_notad > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS DEBITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_notad)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL ENVIO ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total - $this->total_nota + $this->total_notad)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><BR>\n";

			//DESCUENTOS
			if($arr[0][porcentaje_descuento]>0)
			{
				$Salida .= "<br><br><br><table WIDTH=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\">\n";
				$Salida .= "<tr>\n";
				$valorDescuento = (($total - $this->total_nota + $this->total_notad)*$arr[0][porcentaje_descuento])/100;
				$Salida .= "<td class=\"normal_10N\" WIDTH=\"70%\">Descuento especial ".$arr[0][porcentaje_descuento]."% facturas de urgencias:&nbsp;</td><td align=\"right\">".FormatoValor($valorDescuento)."</td>\n";
				$Salida .= "</tr>\n";
				$valorNeto = ($total - $this->total_nota + $this->total_notad)-$valorDescuento;
				$Salida .= "<tr>\n";
				$Salida .= "<td class=\"normal_10N\" WIDTH=\"70%\">Valor neto si paga antes del VENCIMIENTO:&nbsp;</td><td align=\"right\">".FormatoValor($valorNeto)."</td>\n";
				$Salida .= "</tr>\n";
				$Salida .= "</table><BR>\n";
			}
			//FIN DESCUENTOS

			//profesionales
			//$pro=DatosHonorariosVariasCuentas($cuentas);
			if(!empty($pro))
			{
					$total=0;
					$Salida .= "		 <br><br><br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"left\" class=\"normal_10\">\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"3\">INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "			</tr>\n";
					for($i=0; $i<sizeof($pro);)
					{
							$Salida .= "			<tr>\n";
							$Salida .= "				<td WIDTH=\"5%\" align=\"left\">".$pro[$i][tercero_id]."</td>\n";
							$Salida .= "				<td WIDTH=\"30%\" align=\"left\">".$pro[$i][nombre]."</td>\n";
							$d=$i;
							$valor=0;
							while($pro[$i][tercero_id]==$pro[$d][tercero_id]
							   AND	$pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
							{   $valor += $pro[$d][valor];  $d++; }
							$total+=$valor;
							$i=$d;
							$Salida .= "				<td WIDTH=\"15%\" align=\"left\">".FormatoValor($valor)."</td>\n";
							$Salida .= "			</tr>\n";
					}
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "	</table><BR>\n";
			}
      
      //OBSERVACIONES DEL ENVIO
      if(!empty($arr[0][observaciones]))
      {
        $Salida .= "<BR><BR><table WIDTH=\"60%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
        $Salida .= "      <tr>\n";
        $Salida .= "        <td WIDTH=\"55%\" class=\"normal_10N\">OBSERVACIONES: </td>\n";
        $Salida .= "        <td>".$arr[0][observaciones]."</td>\n";
        $Salida .= "      </tr>\n";
        $Salida .= "  </table><BR>";
      }
      //FIN OBSERVACIONES DEL ENVIO

			$Salida .= "<BR><BR>";
			$Salida .= "<table WIDTH=\"40%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
			$Salida .= "	<tr>";
			$Salida .= "		<td WIDTH=\"10%\" class=\"normal_10\"align=\"left\">Usuario: </td>";
			$Salida .= "		<td class=\"normal_10N\" align=\"left\">".$arr[0][usuario_id]."-".$arr[0][nomusuario]."</td>";
			$Salida .= "	</tr>";
			$Salida .= "	</table>";

			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<table width=\"100%\" border =\"0\"  align=\"left\">\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td>".$this->NombreEmpresa($arr[0][empresa_id])."</td>\n";
			$Salida .= "		<td>Recibi </td>";
			$Salida .= "		<td colspan=\"3\" align=\"left\" valign=\"bottom\"><hr align=\"left\" valign=\"bottom\" width=\"80%\"></hr></td>";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td width =\"35%\">DEPARTAMENTO COBRANZAS</td>\n";
			$Salida .= "		<td width =\"15%\">No. Radica </td>\n";
			$Salida .= "		<td width =\"20%\" align=\"left\" valign=\"bottom\"><hr align=\"left\" width=\"90%\"></hr></td>\n";
			$Salida .= "		<td width =\"10%\">Fecha </td>";
			$Salida .= "		<td  width =\"20%\" align=\"left\" valign=\"bottom\"><hr align=\"left\" width=\"90%\"></hr></td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";
			return $Salida;
	}

	/**
	******************************************************************************************************
	* REPORTES EXCLUSIVOS PARA TULUA
	******************************************************************************************************
	*/
	
		
	/**
	* REPORTE PARA Coomeva Prepago
	*/
	function ReporteCoomevaPrepagoTulua()
	{
			IncludeLib("tarifario");
			IncludeLib("funciones_facturacion");
			$arr=$this->DetalleEnvio($this->datos[envio]);
			$datos_empresa = $this->DatosEmpresa($arr[0][empresa_id]);
			$nom_empresa = $datos_empresa[razon_social];
			$tipo_id_empresa = $datos_empresa[tipo_id_tercero];
			$id_empresa = $datos_empresa[id];
			$cuentas='';
			$cont=0;
			$Salida .= "";
			$Salida .= "<table border=0 width=100% align='center'>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$arr[0][envio_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][nombre_tercero]."   ".$arr[0][tipo_tercero_id]." ".$arr[0][tercero_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">DEBE A:</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$nom_empresa."<br>".$tipo_id_empresa."-".$id_empresa."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">POR SERVICIOS PRESTADOS EN: </td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			IF(empty($arr[0][departamento]))
			{  $dpto='TODOS'; }
			else
			{
					list($dbconn) = GetDBconn();
					$query = "select descripcion from departamentos
										where departamento='".$arr[0][departamento]."'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Tabal autorizaiones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					$result->Close();
					$dpto=$result->fields[0];
			}
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td><tr>\n";
			
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">Fecha elaboración:</td>";
			$Salida .= "			</tr>";
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][fecha_registro]."</td>";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><br>\n";

			//$Salida .= "<p align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$this->datos[envio]."</p>";
			$Salida.="<table border=0 WIDTH=100% align=\"CENTER\">\n";
			$total=$k=0;

			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10N\">FACTURA</td>\n";
			$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10N\">VALOR</td>\n";
			$Salida .= "				<td WIDTH=100 class=\"normal_10N\">IDENTIFICACION</td>\n";
			$Salida .= "				<td WIDTH=175 class=\"normal_10N\">USUARIO</td>\n";
			$Salida .= "				<td WIDTH=215 class=\"normal_10N\">AUTORIZACION</td>\n";
			$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10N\">PLAN</td>\n";
			$Salida .= "			</tr>\n";
			for($i=0; $i<sizeof($arr);)
			{
							$poliza = $this->ConsultaAutorizacion($arr[$i][numerodecuenta]);
							//$poliza = $this->ConsultaAutorizacionIngreso($arr[$i][ingreso]);
							$x=0;
							$d=$i+1;
							//es agrupada
							if($arr[$i][prefijo]==$arr[$d][prefijo]
								 AND $arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
							{
									$total+=$arr[$i][total_factura];
									while($arr[$i][prefijo]==$arr[$d][prefijo] AND
												$arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
									{
											if($x==0)
											{
													$Salida .= "			<tr>\n";
													$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
													$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
													$Salida .= "				<td WIDTH=130>&nbsp;</td>\n";
													$Salida .= "				<td WIDTH=215 class=\"normal_10\">AGRUPADA</td>\n";
													$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
													$Salida .= "			</tr>";
													$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
													$x++;
											}
											
											if($cont==0)
											{  $cuentas .=$arr[$d][numerodecuenta];  $cont++;}
											else
											{  $cuentas .=','.$arr[$d][numerodecuenta];  }
											$d++;
									}
									$i=$d;
							}
							else
							{	//no es agrupada
									$Salida .= "			<tr>\n";
									$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
									$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
									$Salida .= "				<td WIDTH=100 class=\"normal_10\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>\n";
									$Salida .= "				<td WIDTH=175 class=\"normal_10\">".$arr[$i][nombre]."</td>\n";
									$Salida .= "				<td WIDTH=150>$poliza</td>\n";
									$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
									$Salida .= "			</tr>\n";
									
									$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									
									$total+=$arr[$i][total_factura];
									if($cont==0)
									{  $cuentas .=$arr[$i][numerodecuenta];  $cont++;}
									else
									{  $cuentas .=','.$arr[$i][numerodecuenta];  }
									$i++;
							}
							$k++;
			}
			$Salida .= "	</table>\n";
			$Salida .= "		 <br><table WIDTH=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=\"55%\" class=\"normal_10\">TOTAL DOCUMENTOS: </td>\n";
			$Salida .= "				<td>$k</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL FACTURAS ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total)."</td>\n";
			$Salida .= "			</tr>\n";
			if($this->total_nota > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS CREDITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_nota)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->total_notad > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS DEBITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_notad)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL ENVIO ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total - $this->total_nota + $this->total_notad)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><BR>\n";
			//profesionales
			$pro=DatosHonorariosVariasCuentas($cuentas);
			if(!empty($pro))
			{
					$total=0;
					$Salida .= "		 <br><br><br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"left\" class=\"normal_10\">\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"3\">INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "			</tr>\n";
					for($i=0; $i<sizeof($pro);)
					{
							$Salida .= "			<tr>\n";
							$Salida .= "				<td WIDTH=\"5%\" align=\"left\">".$pro[$i][tercero_id]."</td>\n";
							$Salida .= "				<td WIDTH=\"30%\" align=\"left\">".$pro[$i][nombre]."</td>\n";
							$d=$i;
							$valor=0;
							while($pro[$i][tercero_id]==$pro[$d][tercero_id]
							   AND	$pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
							{   $valor += $pro[$d][valor];  $d++; }
							$total+=$valor;
							$i=$d;
							$Salida .= "				<td WIDTH=\"15%\" align=\"left\">".FormatoValor($valor)."</td>\n";
							$Salida .= "			</tr>\n";
					}
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "	</table><BR>\n";
			}
			$Salida .= "<br><br><br><br>";
			$Salida .= "<table WIDTH=\"40%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
			$Salida .= "	<tr>";
			$Salida .= "		<td WIDTH=\"10%\" class=\"normal_10\"align=\"left\">Usuario: </td>";
			$Salida .= "		<td class=\"normal_10N\"align=\"left\">".$arr[0][usuario_id]."-".$arr[0][nomusuario]."</td>";
			$Salida .= "	</tr>";
			$Salida .= "	</table>";

			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<table width=\"100%\" border =\"0\"  align=\"left\">\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td>".$this->NombreEmpresa($arr[0][empresa_id])."</td>\n";
			$Salida .= "		<td>Recibi </td>";
			$Salida .= "		<td colspan=\"3\" align=\"left\" valign=\"bottom\"><hr align=\"left\" valign=\"bottom\" width=\"80%\"></hr></td>";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td colspan=\"3\" align=\"center\" valign=\"bottom\" width=\"80%\">Recibo a satisfacción copia de originales de todas y cada una de las facturas relacionadas en este envío.</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";
			return $Salida;
	}
	
	
	/**
	* REPORTE PARA SOAT
	*/
	function ReporteSoatTulua()
	{
			IncludeLib("tarifario");
			IncludeLib("funciones_facturacion");
			$arr=$this->DetalleEnvio($this->datos[envio]);
			$datos_empresa = $this->DatosEmpresa($arr[0][empresa_id]);
			$nom_empresa = $datos_empresa[razon_social];
			$tipo_id_empresa = $datos_empresa[tipo_id_tercero];
			$id_empresa = $datos_empresa[id];
			$cuentas='';
			$cont=0;
			$Salida .= "";
			$Salida .= "<table border=0 width=100% align='center'>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$arr[0][envio_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][nombre_tercero]."   ".$arr[0][tipo_tercero_id]." ".$arr[0][tercero_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">DEBE A:</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$nom_empresa."<br>".$tipo_id_empresa."-".$id_empresa."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">POR SERVICIOS PRESTADOS EN: </td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			IF(empty($arr[0][departamento]))
			{  $dpto='TODOS'; }
			else
			{
					list($dbconn) = GetDBconn();
					$query = "select descripcion from departamentos
										where departamento='".$arr[0][departamento]."'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Tabal autorizaiones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					$result->Close();
					$dpto=$result->fields[0];
			}
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td><tr>\n";
			
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">Fecha elaboración:</td>";
			$Salida .= "			</tr>";
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][fecha_registro]."</td>";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><br>\n";

			//$Salida .= "<p align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$this->datos[envio]."</p>";
			$Salida.="<table border=0 WIDTH=100% align=\"CENTER\">\n";
			$total=$k=0;

			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10N\">FACTURA</td>\n";
			$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10N\">VALOR</td>\n";
			$Salida .= "				<td WIDTH=100 class=\"normal_10N\">IDENTIFICACION</td>\n";
			$Salida .= "				<td WIDTH=175 class=\"normal_10N\">USUARIO</td>\n";
			$Salida .= "				<td WIDTH=215 class=\"normal_10N\">POLIZA</td>\n";
			$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10N\">PLAN</td>\n";
			$Salida .= "			</tr>\n";
			for($i=0; $i<sizeof($arr);)
			{
							$poliza = $this->ConsultaPoliza($arr[$i][numerodecuenta]);
							$x=0;
							$d=$i+1;
							//es agrupada
							if($arr[$i][prefijo]==$arr[$d][prefijo]
								 AND $arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
							{
									$total+=$arr[$i][total_factura];
									while($arr[$i][prefijo]==$arr[$d][prefijo] AND
												$arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
									{
											if($x==0)
											{
													$Salida .= "			<tr>\n";
													$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
													$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
													$Salida .= "				<td WIDTH=130>&nbsp;</td>\n";
													$Salida .= "				<td WIDTH=215 class=\"normal_10\">AGRUPADA</td>\n";
													$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
													$Salida .= "			</tr>";
													$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
													$x++;
											}
											
											if($cont==0)
											{  $cuentas .=$arr[$d][numerodecuenta];  $cont++;}
											else
											{  $cuentas .=','.$arr[$d][numerodecuenta];  }
											$d++;
									}
									$i=$d;
							}
							else
							{	//no es agrupada
									$Salida .= "			<tr>\n";
									$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
									$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
									$Salida .= "				<td WIDTH=100 class=\"normal_10\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>\n";
									$Salida .= "				<td WIDTH=175 class=\"normal_10\">".$arr[$i][nombre]."</td>\n";
									$Salida .= "				<td WIDTH=150>$poliza</td>\n";
									$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
									$Salida .= "			</tr>\n";
									
									$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									
									$total+=$arr[$i][total_factura];
									if($cont==0)
									{  $cuentas .=$arr[$i][numerodecuenta];  $cont++;}
									else
									{  $cuentas .=','.$arr[$i][numerodecuenta];  }
									$i++;
							}
							$k++;
			}
			$Salida .= "	</table>\n";
			$Salida .= "		 <br><table WIDTH=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=\"55%\" class=\"normal_10\">TOTAL DOCUMENTOS: </td>\n";
			$Salida .= "				<td>$k</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL FACTURAS ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total)."</td>\n";
			$Salida .= "			</tr>\n";
			if($this->total_nota > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS CREDITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_nota)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->total_notad > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS DEBITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_notad)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL ENVIO ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total - $this->total_nota + $this->total_notad)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><BR>\n";
			//profesionales
			$pro=DatosHonorariosVariasCuentas($cuentas);
			if(!empty($pro))
			{
					$total=0;
					$Salida .= "		 <br><br><br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"left\" class=\"normal_10\">\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"3\">INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "			</tr>\n";
					for($i=0; $i<sizeof($pro);)
					{
							$Salida .= "			<tr>\n";
							$Salida .= "				<td WIDTH=\"5%\" align=\"left\">".$pro[$i][tercero_id]."</td>\n";
							$Salida .= "				<td WIDTH=\"30%\" align=\"left\">".$pro[$i][nombre]."</td>\n";
							$d=$i;
							$valor=0;
							while($pro[$i][tercero_id]==$pro[$d][tercero_id]
							   AND	$pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
							{   $valor += $pro[$d][valor];  $d++; }
							$total+=$valor;
							$i=$d;
							$Salida .= "				<td WIDTH=\"15%\" align=\"left\">".FormatoValor($valor)."</td>\n";
							$Salida .= "			</tr>\n";
					}
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "	</table><BR>\n";
			}
			$Salida .= "<br><br><br><br>";
			$Salida .= "<table WIDTH=\"40%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
			$Salida .= "	<tr>";
			$Salida .= "		<td WIDTH=\"10%\" class=\"normal_10\"align=\"left\">Usuario: </td>";
			$Salida .= "		<td class=\"normal_10N\"align=\"left\">".$arr[0][usuario_id]."-".$arr[0][nomusuario]."</td>";
			$Salida .= "	</tr>";
			$Salida .= "	</table>";

			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<table width=\"100%\" border =\"0\"  align=\"left\">\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td>".$this->NombreEmpresa($arr[0][empresa_id])."</td>\n";
			$Salida .= "		<td>Recibi </td>";
			$Salida .= "		<td colspan=\"3\" align=\"left\" valign=\"bottom\"><hr align=\"left\" valign=\"bottom\" width=\"80%\"></hr></td>";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td colspan=\"3\" align=\"right\" valign=\"bottom\" width=\"80%\">Recibo a satisfacción copia de originales de todas y cada una de las facturas relacionadas en este envío.</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";
			return $Salida;
	}
	/**
	* REPORTE DEFAULT
	*/
	function ReporteDefaultTulua()
	{
			IncludeLib("tarifario");
			IncludeLib("funciones_facturacion");
			$arr=$this->DetalleEnvio($this->datos[envio]);
			$datos_empresa = $this->DatosEmpresa($arr[0][empresa_id]);
			$nom_empresa = $datos_empresa[razon_social];
			$tipo_id_empresa = $datos_empresa[tipo_id_tercero];
			$id_empresa = $datos_empresa[id];
			$cuentas='';
			$cont=0;
			$Salida .= "";
			$Salida .= "<table border=0 width=100% align='center'>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$arr[0][envio_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][nombre_tercero]."   ".$arr[0][tipo_tercero_id]." ".$arr[0][tercero_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">DEBE A:</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$nom_empresa."<br>".$tipo_id_empresa."-".$id_empresa."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">POR SERVICIOS PRESTADOS EN: </td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			IF(empty($arr[0][departamento]))
			{  $dpto='TODOS'; }
			else
			{
					list($dbconn) = GetDBconn();
					$query = "select descripcion from departamentos
										where departamento='".$arr[0][departamento]."'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Tabal autorizaiones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					$result->Close();
					$dpto=$result->fields[0];
			}
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td><tr>\n";
			
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">Fecha elaboración:</td>";
			$Salida .= "			</tr>";
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][fecha_registro]."</td>";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><br>\n";

			//$Salida .= "<p align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$this->datos[envio]."</p>";
			$Salida.="<table border=0 WIDTH=100% align=\"CENTER\">\n";
			$total=$k=0;

			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10N\">FACTURA</td>\n";
			$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10N\">VALOR</td>\n";
			$Salida .= "				<td WIDTH=130 class=\"normal_10N\">IDENTIFICACION</td>\n";
			$Salida .= "				<td WIDTH=215 class=\"normal_10N\">USUARIO</td>\n";
			$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10N\">PLAN</td>\n";
			$Salida .= "			</tr>\n";
			for($i=0; $i<sizeof($arr);)
			{
							$x=0;
							$d=$i+1;
							//es agrupada
							if($arr[$i][prefijo]==$arr[$d][prefijo]
								 AND $arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
							{
									$total+=$arr[$i][total_factura];
									while($arr[$i][prefijo]==$arr[$d][prefijo] AND
												$arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
									{
											if($x==0)
											{
													$Salida .= "			<tr>\n";
													$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
													$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
													$Salida .= "				<td WIDTH=130>&nbsp;</td>\n";
													$Salida .= "				<td WIDTH=215 class=\"normal_10\">AGRUPADA</td>\n";
													$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
													$Salida .= "			</tr>";
													$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
													$x++;
											}
											
											if($cont==0)
											{  $cuentas .=$arr[$d][numerodecuenta];  $cont++;}
											else
											{  $cuentas .=','.$arr[$d][numerodecuenta];  }
											$d++;
									}
									$i=$d;
							}
							else
							{	//no es agrupada
									$Salida .= "			<tr>\n";
									$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
									$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
									$Salida .= "				<td WIDTH=130 class=\"normal_10\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>\n";
									$Salida .= "				<td WIDTH=215 class=\"normal_10\">".$arr[$i][nombre]."</td>\n";
									$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
									$Salida .= "			</tr>\n";
									
									$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									
									$total+=$arr[$i][total_factura];
									if($cont==0)
									{  $cuentas .=$arr[$i][numerodecuenta];  $cont++;}
									else
									{  $cuentas .=','.$arr[$i][numerodecuenta];  }
									$i++;
							}
							$k++;
			}
			$Salida .= "	</table>\n";
			$Salida .= "		 <br><table WIDTH=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=\"55%\" class=\"normal_10\">TOTAL DOCUMENTOS: </td>\n";
			$Salida .= "				<td>$k</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL FACTURAS ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total)."</td>\n";
			$Salida .= "			</tr>\n";
			if($this->total_nota > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS CREDITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_nota)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->total_notad > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS DEBITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_notad)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL ENVIO ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total - $this->total_nota + $this->total_notad)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><BR>\n";
			//profesionales
			$pro=DatosHonorariosVariasCuentas($cuentas);
			if(!empty($pro))
			{
					$total=0;
					$Salida .= "		 <br><br><br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"left\" class=\"normal_10\">\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"3\">INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "			</tr>\n";
					for($i=0; $i<sizeof($pro);)
					{
							$Salida .= "			<tr>\n";
							$Salida .= "				<td WIDTH=\"5%\" align=\"left\">".$pro[$i][tercero_id]."</td>\n";
							$Salida .= "				<td WIDTH=\"30%\" align=\"left\">".$pro[$i][nombre]."</td>\n";
							$d=$i;
							$valor=0;
							while($pro[$i][tercero_id]==$pro[$d][tercero_id]
							   AND	$pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
							{   $valor += $pro[$d][valor];  $d++; }
							$total+=$valor;
							$i=$d;
							$Salida .= "				<td WIDTH=\"15%\" align=\"left\">".FormatoValor($valor)."</td>\n";
							$Salida .= "			</tr>\n";
					}
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "	</table><BR>\n";
			}
      
      //OBSERVACIONES DEL ENVIO
      if(!empty($arr[0][observaciones]))
      {
        $Salida .= "<table WIDTH=\"60%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
        $Salida .= "      <tr>\n";
        $Salida .= "        <td WIDTH=\"55%\" class=\"normal_10N\">OBSERVACIONES: </td>\n";
        $Salida .= "        <td>".$arr[0][observaciones]."</td>\n";
        $Salida .= "      </tr>\n";
        $Salida .= "  </table><BR><BR><BR>";
      }
      //FIN OBSERVACIONES DEL ENVIO
      $Salida .= "<P><BR></P>";
			$Salida .= "<BR><BR><BR>";
			$Salida .= "<table WIDTH=\"40%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
			$Salida .= "	<tr>";
			$Salida .= "		<td WIDTH=\"10%\" class=\"normal_10\"align=\"left\">Usuario: </td>";
			$Salida .= "		<td class=\"normal_10N\"align=\"left\">".$arr[0][usuario_id]."-".$arr[0][nomusuario]."</td>";
			$Salida .= "	</tr>";
			$Salida .= "	</table>";

			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<table width=\"100%\" border =\"0\"  align=\"left\">\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td>".$this->NombreEmpresa($arr[0][empresa_id])."</td>\n";
			$Salida .= "		<td>Recibi </td>";
			$Salida .= "		<td colspan=\"3\" align=\"left\" valign=\"bottom\"><hr align=\"left\" valign=\"bottom\" width=\"80%\"></hr></td>";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td colspan=\"3\" align=\"right\" valign=\"bottom\" width=\"80%\">Recibo a satisfacción copia de originales de todas y cada una de las facturas relacionadas en este envío.</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";
			return $Salida;
	}
	
  //REPORTE DEFAULT BUGA
	/**
	* REPORTE DEFAULT
	*/
	
	function ReporteDefaultBUGA()
	{
			IncludeLib("tarifario");
			IncludeLib("funciones_facturacion");
			$arr=$this->DetalleEnvio($this->datos[envio]);
			$datos_empresa = $this->DatosEmpresa($arr[0][empresa_id]);
			$nom_empresa = $datos_empresa[razon_social];
			$tipo_id_empresa = $datos_empresa[tipo_id_tercero];
			$id_empresa = $datos_empresa[id];
			$cuentas='';
			$cont=0;
			$Salida .= "";
			$Salida .= "<table border=0 width=100% align='center'>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"titulo2\">CUENTA DE COBRO No. ".$arr[0][envio_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][tipo_tercero_id]." - ".$arr[0][tercero_id]." ".$arr[0][nombre_tercero]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10\">".$arr[0][municipio]." ".$arr[0][direccion]." ".$arr[0][telefono]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">DEBE A:</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$tipo_id_empresa."-".$id_empresa." ".$nom_empresa."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10\">".$datos_empresa[direccion]." ".$datos_empresa[telefonos]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">POR SERVICIOS PRESTADOS EN: </td>\n";
			$Salida .= "			</tr>\n";
			IF(empty($arr[0][departamento]))
			{  $dpto='TODOS'; }
			else
			{	
				$array = $this->ObtenerDepartamentos($this->datos[envio]);
				foreach($array AS $i => $v)
				{
					list($dbconn) = GetDBconn();
					$query = "select descripcion from departamentos
										where departamento='".$v[departamento]."'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Tabal autorizaiones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					$result->Close();
					$dpto=$result->fields[0];
					
					$Salida .= "			<tr>\n";
					$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td>\n";
					$Salida .= "			</tr>\n";
				}
			}
			if($dpto=='TODOS')
			{
				$Salida .= "			<tr>\n";			
				$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$d = explode('-',$arr[0][fecha_inicial]);
			//$dat = ucwords(strftime("%A %d de %B de %Y",mktime(0,0,0,$d[1],$d[2],$d[0])));
			$mes = ucwords(strftime("%B",mktime(0,0,0,$d[1],$d[2],$d[0])));
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">MES FACTURADO: ".$mes."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">PERIODO DESDE: ".$arr[0][fecha_inicial]." HASTA: ".$arr[0][fecha_final]."</td>\n";
			$Salida .= "			</tr>\n";
			
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">Fecha elaboración:</td>";
			$Salida .= "			</tr>";
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][fecha_registro]."</td>";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><br>\n";

			//$Salida .= "<p align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$this->datos[envio]."</p>";
			$Salida.="<table border=0 WIDTH=100% align=\"CENTER\">\n";
			$total=$k=0;

			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10N\">FACTURA</td>\n";
			$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10N\">VALOR</td>\n";
			$Salida .= "				<td WIDTH=100 class=\"normal_10N\">IDENTIFICACION</td>\n";
			$Salida .= "				<td width=\"15\" align=\"center\"><b>Nº NOTA</b></td>\n";
			$Salida .= "				<td width=\"15\" align=\"center\"><b>VALOR</b></td>\n";
			$Salida .= "				<td WIDTH=215 class=\"normal_10N\">USUARIO</td>\n";
			//$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10N\">PLAN</td>\n";
			$Salida .= "			</tr>\n";
			for($i=0; $i<sizeof($arr);)
			{
							$x=0;
							$d=$i+1;
							//es agrupada
							if($arr[$i][prefijo]==$arr[$d][prefijo]
								 AND $arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
							{
									$total+=$arr[$i][total_factura];
									while($arr[$i][prefijo]==$arr[$d][prefijo] AND
												$arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
									{
											if($x==0)
											{
													$Salida .= "			<tr>\n";
													$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
													$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
													$Salida .= "				<td WIDTH=130>&nbsp;</td>\n";
													$Salida .= "				<td WIDTH=115 class=\"normal_10\">AGRUPADA</td>\n";
													//$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
													$Salida .= $this-> ObtenerTablaNotasBuga($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
/*													$Usuario = $this-> ObtenerUsuario($arr[$i][usuario_id]);
													$Salida .= "				<td WIDTH=115 class=\"normal_10\">$Usuario</td>\n";*/
													$Salida .= "			</tr>";
													//$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
													$x++;
											}
											
											if($cont==0)
											{  $cuentas .=$arr[$d][numerodecuenta];  $cont++;}
											else
											{  $cuentas .=','.$arr[$d][numerodecuenta];  }
											$d++;
									}
									$i=$d;
							}
							else
							{	//no es agrupada
									$Salida .= "			<tr>\n";
									$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
									$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
									$Salida .= "				<td WIDTH=130 class=\"normal_10\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>\n";
									//$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
									$Salida .= $this-> ObtenerTablaNotasBuga($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									$Salida .= "				<td WIDTH=115 class=\"normal_10\">".$arr[$i][nombre]."</td>\n";
/*									$Usuario = $this-> ObtenerUsuario($arr[$i][usuario_id]);
									$Salida .= "				<td WIDTH=115 class=\"normal_10\">$Usuario</td>\n";*/
									$Salida .= "			</tr>\n";
									
									//$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									
									$total+=$arr[$i][total_factura];
									if($cont==0)
									{  $cuentas .=$arr[$i][numerodecuenta];  $cont++;}
									else
									{  $cuentas .=','.$arr[$i][numerodecuenta];  }
									$i++;
							}
							$k++;
			}
			$Salida .= "	</table>\n";
			$Salida .= "		 <br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=\"55%\" class=\"normal_10\">TOTAL DOCUMENTOS: </td>\n";
			$Salida .= "				<td>$k</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL FACTURAS ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total)."</td>\n";
			$Salida .= "			</tr>\n";
			if($this->total_nota > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS CREDITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_nota)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->total_notad > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS DEBITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_notad)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL ENVIO ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total - $this->total_nota + $this->total_notad)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\" WIDTH=\"5%\">SON: </td>\n";
			$totall=$total - $this->total_nota + $this->total_notad;
			$Salida .= "				<td class=\"normal_10\" WIDTH=\"95%\" colspan=\"2\">".ValorEnLetras($totall)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><BR>\n";
			//profesionales
			$pro=DatosHonorariosVariasCuentas($cuentas);
			if(!empty($pro))
			{
					$total=0;
					$Salida .= "		 <br><br><br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"left\" class=\"normal_10\">\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"3\">INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "			</tr>\n";
					for($i=0; $i<sizeof($pro);)
					{
							$Salida .= "			<tr>\n";
							$Salida .= "				<td WIDTH=\"5%\" align=\"left\">".$pro[$i][tercero_id]."</td>\n";
							$Salida .= "				<td WIDTH=\"30%\" align=\"left\">".$pro[$i][nombre]."</td>\n";
							$d=$i;
							$valor=0;
							while($pro[$i][tercero_id]==$pro[$d][tercero_id]
							   AND	$pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
							{   $valor += $pro[$d][valor];  $d++; }
							$total+=$valor;
							$i=$d;
							$Salida .= "				<td WIDTH=\"15%\" align=\"left\">".FormatoValor($valor)."</td>\n";
							$Salida .= "			</tr>\n";
					}
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA LA ENTIDAD</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($totall-$total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "	</table><BR>\n";
			} 

      //OBSERVACIONES DEL ENVIO
      if(!empty($arr[0][observaciones]))
      {
        $Salida .= "<table WIDTH=\"60%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
        $Salida .= "      <tr>\n";
        $Salida .= "        <td WIDTH=\"55%\" class=\"normal_10N\">OBSERVACIONES: </td>\n";
        $Salida .= "        <td>".$arr[0][observaciones]."</td>\n";
        $Salida .= "      </tr>\n";
        $Salida .= "  </table><BR><BR><BR>";
      }
      //FIN OBSERVACIONES DEL ENVIO
      $Salida .= "<P><BR><BR></P>";
			$Salida .= "<BR><BR><BR><BR><BR>";
			$Salida .= "<table WIDTH=\"40%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
			$Salida .= "	<tr>";
			$Salida .= "		<td WIDTH=\"10%\" class=\"normal_10\"align=\"left\">Usuario: </td>";
			$Salida .= "		<td class=\"normal_10N\"align=\"left\">".$arr[0][usuario_id]."-".$arr[0][nomusuario]."</td>";
			$Salida .= "	</tr>";
			$Salida .= "	</table>";

			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<table width=\"100%\" border =\"0\"  align=\"left\">\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td>".$this->NombreEmpresa($arr[0][empresa_id])."</td>\n";
			$Salida .= "		<td>Recibi </td>";
			$Salida .= "		<td colspan=\"3\" align=\"left\" valign=\"bottom\"><hr align=\"left\" valign=\"bottom\" width=\"80%\"></hr></td>";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td colspan=\"3\" align=\"right\" valign=\"bottom\" width=\"80%\">Recibo a satisfacción copia de originales de todas y cada una de las facturas relacionadas en este envío.</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td colspan=\"3\" align=\"right\" valign=\"bottom\" width=\"80%\">Nota: Al cancelar hacer referencia al No. de la factura por paciente o al No. de la Cuenta de Cobro</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td colspan=\"3\" align=\"right\" valign=\"bottom\" width=\"80%\">La Entidad de Régimen Especial no efectúa ninguna retención según artículo 369 ESTATUTO TRIBUTARIO</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";
			return $Salida;
	}

	function ReporteValoresPacienteBuga()
	{
			IncludeLib("tarifario");
			IncludeLib("funciones_facturacion");
			$arr=$this->DetalleEnvio($this->datos[envio]);
			$datos_empresa = $this->DatosEmpresa($arr[0][empresa_id]);
			$nom_empresa = $datos_empresa[razon_social];
			$tipo_id_empresa = $datos_empresa[tipo_id_tercero];
			$id_empresa = $datos_empresa[id];
			$cuentas='';
			$cont=0;
			$Salida .= "";
			$Salida .= "<table border=0 width=100% align='center'>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr></tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"titulo2\">CUENTA DE COBRO No. ".$arr[0][envio_id]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][tipo_tercero_id]." - ".$arr[0][tercero_id]." ".$arr[0][nombre_tercero]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10\">".$arr[0][municipio]." ".$arr[0][direccion]." ".$arr[0][telefono]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">DEBE A:</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$tipo_id_empresa."-".$id_empresa." ".$nom_empresa."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10\">".$datos_empresa[direccion]." ".$datos_empresa[telefonos]."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">POR SERVICIOS PRESTADOS EN: </td>\n";
			$Salida .= "			</tr>\n";
			IF(empty($arr[0][departamento]))
			{  $dpto='TODOS'; }
			else
			{
				$array = $this->ObtenerDepartamentos($this->datos[envio]);
				foreach($array AS $i => $v)
				{
					list($dbconn) = GetDBconn();
					$query = "select descripcion from departamentos
										where departamento='".$v[departamento]."'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Tabal autorizaiones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					$result->Close();
					$dpto=$result->fields[0];
					$Salida .= "			<tr>\n";
					$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td>\n";
					$Salida .= "			</tr>\n";
				}
			}
			if($dpto=='TODOS')
			{
				$Salida .= "	<tr>\n";
				$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\"> - ".$dpto."</td>\n";
				$Salida .= "	<tr>\n";
			}
			$d = explode('-',$arr[0][fecha_inicial]);
			//$dat = ucwords(strftime("%A %d de %B de %Y",mktime(0,0,0,$d[1],$d[2],$d[0])));
			$mes = ucwords(strftime("%B",mktime(0,0,0,$d[1],$d[2],$d[0])));
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">MES FACTURADO: ".$mes."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">PERIODO DESDE: ".$arr[0][fecha_inicial]." HASTA: ".$arr[0][fecha_final]."</td>\n";
			$Salida .= "			</tr>\n";
			
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">Fecha elaboración:</td>";
			$Salida .= "			</tr>";
			$Salida .= "			<tr>";
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][fecha_registro]."</td>";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><br>\n";

			//$Salida .= "<p align=\"CENTER\" class=\"titulo2\">ENVIO No. ".$this->datos[envio]."</p>";
			$Salida.="<table border=0 WIDTH=100% align=\"CENTER\">\n";
			$total=$k=0;

			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10N\">FACTURA</td>\n";
			$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10N\">VALOR</td>\n";
			$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10N\">LETRAS</td>\n";
			$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10N\">PAGOS PAC.</td>\n";
			$Salida .= "				<td WIDTH=100 class=\"normal_10N\">IDENTIFICACION</td>\n";
			$Salida .= "				<td width=\"15\" align=\"center\"><b>Nº NOTA</b></td>\n";
			$Salida .= "				<td width=\"15\" align=\"center\"><b>VALOR</b></td>\n";
			$Salida .= "				<td WIDTH=215 class=\"normal_10N\">USUARIO</td>\n";
			//$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10N\">PLAN</td>\n";
			$Salida .= "			</tr>\n";
			for($i=0; $i<sizeof($arr);)
			{
							$x=0;
							$d=$i+1;
							//es agrupada
							if($arr[$i][prefijo]==$arr[$d][prefijo]
								 AND $arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
							{
									$total+=$arr[$i][total_factura];
									while($arr[$i][prefijo]==$arr[$d][prefijo] AND
												$arr[$i][factura_fiscal]==$arr[$d][factura_fiscal])
									{
											if($x==0)
											{
													$Salida .= "			<tr>\n";
													$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
													$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
													$Salida .= $this-> ObtenerTablaLetras($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
													$Salida .= $this-> ObtenerTablaPagos($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);													
													$Salida .= "				<td WIDTH=130>&nbsp;</td>\n";
													$Salida .= "				<td WIDTH=115 class=\"normal_10\">AGRUPADA</td>\n";
													//$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
													$Salida .= $this-> ObtenerTablaNotasBuga($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
/*													$Usuario = $this-> ObtenerUsuario($arr[$i][usuario_id]);
													$Salida .= "				<td WIDTH=115 class=\"normal_10\">$Usuario</td>\n";*/
													$Salida .= "			</tr>";
													//$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
													$x++;
											}
											
											if($cont==0)
											{  $cuentas .=$arr[$d][numerodecuenta];  $cont++;}
											else
											{  $cuentas .=','.$arr[$d][numerodecuenta];  }
											$d++;
									}
									$i=$d;
							}
							else
							{	//no es agrupada
									$Salida .= "			<tr>\n";
									$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
									$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
									$Salida .= $this-> ObtenerTablaLetras($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									$Salida .= $this-> ObtenerTablaPagos($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);													
									$Salida .= "				<td WIDTH=130 class=\"normal_10\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>\n";
									//$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
									$Salida .= $this-> ObtenerTablaNotasBuga($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									$Salida .= "				<td WIDTH=115 class=\"normal_10\">".$arr[$i][nombre]."</td>\n";
/*									$Usuario = $this-> ObtenerUsuario($arr[$i][usuario_id]);
									$Salida .= "				<td WIDTH=115 class=\"normal_10\">$Usuario</td>\n";*/
									$Salida .= "			</tr>\n";
									
									//$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									
									$total+=$arr[$i][total_factura];
									if($cont==0)
									{  $cuentas .=$arr[$i][numerodecuenta];  $cont++;}
									else
									{  $cuentas .=','.$arr[$i][numerodecuenta];  }
									$i++;
							}
							$k++;
			}
			$Salida .= "	</table>\n";
			$Salida .= "		 <br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td WIDTH=\"55%\" class=\"normal_10\">TOTAL DOCUMENTOS: </td>\n";
			$Salida .= "				<td>$k</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL FACTURAS ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total)."</td>\n";
			$Salida .= "			</tr>\n";
			if($this->total_nota > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS CREDITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_nota)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->total_notad > 0)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"normal_10\">TOTAL NOTAS DEBITO ($): </td>\n";
				$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($this->total_notad)."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\">TOTAL ENVIO ($): </td>\n";
			$Salida .= "				<td class=\"normal_10\" align=\"right\">".FormatoValor($total - $this->total_nota + $this->total_notad)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"normal_10\" WIDTH=\"5%\">SON: </td>\n";
			$totall=$total - $this->total_nota + $this->total_notad;
			$Salida .= "				<td class=\"normal_10\" WIDTH=\"95%\" colspan=\"2\">".ValorEnLetras($totall)."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><BR>\n";
			//profesionales
			$pro=DatosHonorariosVariasCuentas($cuentas);
			if(!empty($pro))
			{
					$total=0;
					$Salida .= "		 <br><br><br><table WIDTH=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"left\" class=\"normal_10\">\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"3\">INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "			</tr>\n";
					for($i=0; $i<sizeof($pro);)
					{
							$Salida .= "			<tr>\n";
							$Salida .= "				<td WIDTH=\"5%\" align=\"left\">".$pro[$i][tercero_id]."</td>\n";
							$Salida .= "				<td WIDTH=\"30%\" align=\"left\">".$pro[$i][nombre]."</td>\n";
							$d=$i;
							$valor=0;
							while($pro[$i][tercero_id]==$pro[$d][tercero_id]
							   AND	$pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
							{   $valor += $pro[$d][valor];  $d++; }
							$total+=$valor;
							$i=$d;
							$Salida .= "				<td WIDTH=\"15%\" align=\"left\">".FormatoValor($valor)."</td>\n";
							$Salida .= "			</tr>\n";
					}
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA TERCEROS</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "			<tr>\n";
					$Salida .= "				<td class=\"normal_10N\" colspan=\"2\">TOTAL INGRESOS PARA LA ENTIDAD</td>\n";
					$Salida .= "				<td class=\"normal_10N\">".FormatoValor($totall-$total)."</td>\n";
					$Salida .= "			</tr>\n";
					$Salida .= "	</table><BR>\n";
			} 

      //OBSERVACIONES DEL ENVIO
      if(!empty($arr[0][observaciones]))
      {
        $Salida .= "<table WIDTH=\"60%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
        $Salida .= "      <tr>\n";
        $Salida .= "        <td WIDTH=\"55%\" class=\"normal_10N\">OBSERVACIONES: </td>\n";
        $Salida .= "        <td>".$arr[0][observaciones]."</td>\n";
        $Salida .= "      </tr>\n";
        $Salida .= "  </table><BR><BR><BR>";
      }
      //FIN OBSERVACIONES DEL ENVIO
      $Salida .= "<P><BR><BR></P>";
			$Salida .= "<BR><BR><BR><BR><BR>";
			$Salida .= "<table WIDTH=\"40%\" border=\"0\"  align=\"left\" class=\"normal_10\">";
			$Salida .= "	<tr>";
			$Salida .= "		<td WIDTH=\"10%\" class=\"normal_10\"align=\"left\">Usuario: </td>";
			$Salida .= "		<td class=\"normal_10N\"align=\"left\">".$arr[0][usuario_id]."-".$arr[0][nomusuario]."</td>";
			$Salida .= "	</tr>";
			$Salida .= "	</table>";

			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<br>";
			$Salida .= "<table width=\"100%\" border =\"0\"  align=\"left\">\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td>".$this->NombreEmpresa($arr[0][empresa_id])."</td>\n";
			$Salida .= "		<td>Recibi </td>";
			$Salida .= "		<td colspan=\"3\" align=\"left\" valign=\"bottom\"><hr align=\"left\" valign=\"bottom\" width=\"80%\"></hr></td>";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td colspan=\"3\" align=\"right\" valign=\"bottom\" width=\"80%\">Recibo a satisfacción copia de originales de todas y cada una de las facturas relacionadas en este envío.</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td colspan=\"3\" align=\"right\" valign=\"bottom\" width=\"80%\">Nota: Al cancelar hacer referencia al No. de la factura por paciente o al No. de la Cuenta de Cobro</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr class=\"normal_10N\" >\n";
			$Salida .= "		<td colspan=\"3\" align=\"right\" valign=\"bottom\" width=\"80%\">La Entidad de Régimen Especial no efectúa ninguna retención según artículo 369 ESTATUTO TRIBUTARIO</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";
			return $Salida;
	}
	//
	/**
  * Funciones Necesarias
  */
	function DetalleEnvio($envio)
	{
			list($dbconn) = GetDBconn();
			$query = "
						SELECT 	a.*, 
										b.*, 
										c.numerodecuenta, 
										d.total_factura,
										d.valor_cuota_paciente, 
										d.plan_id, 
										e.plan_descripcion, 
										g.tipo_id_paciente,
										g.paciente_id, 
										h.primer_nombre||' '||h.segundo_nombre||' '||h.primer_apellido||' '||h.segundo_apellido as nombre,
										i.nombre_tercero, 
										e.tipo_tercero_id, 
										e.tercero_id, 
										a.fecha_registro,
										j.usuario_id,
										j.nombre as nomusuario,
										i.direccion,
										i.telefono,
										k.municipio,
										g.departamento_actual as departamento,
										g.ingreso
						FROM 		envios as a, 
										envios_detalle as b, 
										fac_facturas_cuentas as c,
										fac_facturas as d, 
										planes as e, 
										cuentas as f, 
										ingresos as g, 
										pacientes as h, 
										terceros as i, 
										system_usuarios j,
										tipo_mpios k
						WHERE 	a.envio_id=".$envio."
										AND a.envio_id=b.envio_id 
										AND b.prefijo=c.prefijo
										AND b.factura_fiscal=c.factura_fiscal 
										AND d.prefijo=c.prefijo
										AND d.factura_fiscal=c.factura_fiscal 
										AND d.plan_id=e.plan_id
										AND c.numerodecuenta=f.numerodecuenta 
										AND f.ingreso=g.ingreso 
										AND g.tipo_id_paciente=h.tipo_id_paciente 
										AND g.paciente_id=h.paciente_id
										AND e.tipo_tercero_id=i.tipo_id_tercero 
										AND e.tercero_id=i.tercero_id
										AND a.usuario_id=j.usuario_id
										AND i.tipo_pais_id = k.tipo_pais_id 
										AND i.tipo_dpto_id = k.tipo_dpto_id
										AND i.tipo_mpio_id = k.tipo_mpio_id
						ORDER BY b.prefijo, b.factura_fiscal";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar  las Tablas de envios";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			if(!$result->EOF)
			{
					while(!$result->EOF)
					{
									$arr[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
					}
			}

			return $arr;
	}

	function ObtenerDepartamentos($envio)
	{
			list($dbconn) = GetDBconn();
			$query = "
						SELECT 	g.departamento_actual as departamento
						FROM 		envios as a, 
										envios_detalle as b, 
										fac_facturas_cuentas as c,
										fac_facturas as d, 
										planes as e, 
										cuentas as f, 
										ingresos as g, 
										pacientes as h, 
										terceros as i, 
										system_usuarios j,
										tipo_mpios k
						WHERE 	a.envio_id=".$envio."
										AND a.envio_id=b.envio_id 
										AND b.prefijo=c.prefijo
										AND b.factura_fiscal=c.factura_fiscal 
										AND d.prefijo=c.prefijo
										AND d.factura_fiscal=c.factura_fiscal 
										AND d.plan_id=e.plan_id
										AND c.numerodecuenta=f.numerodecuenta 
										AND f.ingreso=g.ingreso 
										AND g.tipo_id_paciente=h.tipo_id_paciente 
										AND g.paciente_id=h.paciente_id
										AND e.tipo_tercero_id=i.tipo_id_tercero 
										AND e.tercero_id=i.tercero_id
										AND a.usuario_id=j.usuario_id
										AND i.tipo_pais_id = k.tipo_pais_id 
										AND i.tipo_dpto_id = k.tipo_dpto_id
										AND i.tipo_mpio_id = k.tipo_mpio_id
						GROUP BY g.departamento_actual";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar  las Tablas de envios";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			if(!$result->EOF)
			{
					while(!$result->EOF)
					{
									$arr[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
					}
			}

			return $arr;
	}

	function NombreEmpresa($empresa)
	{
			list($dbconn) = GetDBconn();
			$query = "select razon_social from empresas
								where empresa_id='$empresa'";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			$result->Close();
			return $result->fields[0];
	}

	function DatosEmpresa($empresa)
	{
			global $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query = "select 	razon_social,
												tipo_id_tercero,
												id,
												direccion,
												telefonos
								from empresas
								where empresa_id= '".$empresa."'";
								
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;					
			$result=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			$arr= $result->FetchRow();
			$result->Close();

			return $arr;
	}
 function FechaStamp($fecha)
 {
		if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}

	function ConsultaPoliza($numerodecuenta)
	{
		list($dbconn) = GetDBconn();
			$query = "select c.poliza
								from 	cuentas a,
											ingresos_soat b,
											soat_eventos c
								where a.numerodecuenta = '".$numerodecuenta."'
											and a.ingreso = b.ingreso
											and b.evento = c.evento
								";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al consultar poliza soat";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			$result->Close();
			return $result->fields[0];
	}
	
	/**
	***ActualizarNroAutorizacion
	**/
	function ActualizarNroAutorizacion($envio)
	{
		$query = "SELECT
								HCA.autorizacion_int,
								HCA.autorizacion_ext,
								C.ingreso
							FROM	cuentas C,
										ingresos I,
										cuentas_detalle CD,
										fac_facturas_cuentas FFC,
										envios_detalle ED,
										hc_os_solicitudes_manuales HCSM,
										hc_os_autorizaciones HCA
							WHERE ED.envio_id = $envio
							AND FFC.prefijo = ED.prefijo
							AND FFC.factura_fiscal = ED.factura_fiscal
							AND CD.numerodecuenta = FFC.numerodecuenta
							AND CD.numerodecuenta = C.numerodecuenta
							AND C.ingreso = I.ingreso
							AND I.tipo_id_paciente = HCSM.tipo_id_paciente
							AND I.paciente_id = HCSM.paciente_id
							AND CD.cargo_cups IS NOT NULL
							AND HCSM.hc_os_solicitud_id = HCA.hc_os_solicitud_id;";
		list($dbconn) = GetDBconn();
		$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				echo " ERROR AL SELECCIONAR AUTORIZACION_escritas: " . $dbconn->ErrorMsg()."<BR>";
			}
			while(!$resulta->EOF)
			{
				$var1[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}

			for($i=0;$i<sizeof($var1);$i++)
			{
						$query1= "UPDATE autorizaciones
											SET ingreso = ".$var1[$i][ingreso]."
											WHERE autorizacion = ".$var1[$i][autorizacion_int]." AND ingreso IS NULL;";
						$resulta=$dbconn->Execute($query1);
						if ($dbconn->ErrorNo() != 0) {
							echo " ERROR UPDATE autorizaciones: " . $dbconn->ErrorMsg()."<BR>";
						}
			}
		return true;
	}

	/**
	***
	**/
	function ConsultaAutorizacion($numerodecuenta)
	{
		list($dbconn) = GetDBconn();
			$query = "(
								select c.codigo_autorizacion
								from 	cuentas a,
											autorizaciones b,
											autorizaciones_escritas c
								
								where
											a.numerodecuenta= '".$numerodecuenta."'
											and a.ingreso = b.ingreso
											and b.autorizacion = c.autorizacion
								)
								UNION
								(
								select c.codigo_autorizacion
								from 	cuentas a,
											autorizaciones b,
											autorizaciones_telefonicas c
								
								where
											a.numerodecuenta= '".$numerodecuenta."'
											and a.ingreso = b.ingreso
											and b.autorizacion = c.autorizacion
								)
								";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al consultar autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			while(!$result->EOF)
			{
						$vars=$result->FetchRow();
			}
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al consultar autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			while(!$result->EOF)
			{
						$vars=$result->FetchRow();
			}
			$result->Close();
			return $vars[0];
	}
	
	function ConsultaAutorizacionIngreso($ingreso)
	{
		if($ingreso)
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT   codigo_autorizacion
				FROM    autorizaciones
				WHERE ingreso = $ingreso ";
			global $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al consultar autoriacion";
				$this->mensajeDeError = "Error DB medicamentos: " . $dbconn->ErrorMsg();
				echo $this->mensajeDeError;
				return false;
			}
			$auto = $result->FetchRow();
			return $auto[codigo_autorizacion];
		}
		return true;
	}
	
	/***
	*
	****/
	function ObtenerTablaNotas($prefijo,$factura,$empresa,&$k)
	{
		$tabla = "";
		$sd = "style=\"border-left:0px;border-top:0px;border-right:0px;border-bottom:1px solid #000000\"";
		$notas = $this->ConsultarInformacionNotas($prefijo,$factura,$empresa);
		$notasd = $this->ObtenerValorNotaDebito($prefijo,$factura,$empresa);
		if(!empty($notas) || !empty($notasd))
		{
			$tabla .= "			<tr>\n";
			$tabla .= "				<td colspan=\"2\" >\n";
			$tabla .= "					<table width=\"100%\" class=\"normal_10\" >\n";
			$tabla .= "						<tr>\n";
			$tabla .= "							<td width=\"110\" align=\"center\"><b>Nº NOTA</b></td>\n";
			$tabla .= "							<td width=\"100\" align=\"center\"><b>VALOR</b></td>\n";
			$tabla .= "						</tr>\n";

			for($n=0; $n < sizeof($notas); $n++)
			{
				$tabla .= "						<tr>\n";
				$tabla .= "							<td width=\"110\" align=\"center\">".$notas[$n]['prefijo_nota']." ".$notas[$n]['nota_credito_ajuste']."</td>\n";
				$tabla .= "							<td width=\"100\" align=\"center\">".FormatoValor($notas[$n]['abono'])."</td>\n";
				$tabla .= "						</tr>\n";
				$this->total_nota += $notas[$n]['abono'];
				$k++;
			}
			
			for($n=0; $n < sizeof($notasd); $n++)
			{
				$tabla .= "						<tr>\n";
				$tabla .= "							<td width=\"110\" align=\"center\">".$notasd[$n]['prefijo_nota']." ".$notasd[$n]['nota_credito_ajuste']."</td>\n";
				$tabla .= "							<td width=\"100\" align=\"center\">".FormatoValor($notasd[$n]['abono'])."</td>\n";
				$tabla .= "						</tr>\n";
				$this->total_notad += $notasd[$n]['abono'];
				$k++;
			}
			
			$tabla .= "					</table>\n";
			$tabla .= "				</td>\n";
			$tabla .= "				<td colspan=\"3\">&nbsp;</td>\n";
			$tabla .= "			</tr>";
		}
		return $tabla;
	}
	/***
	*
	****/
	function ObtenerTablaNotasBuga($prefijo,$factura,$empresa,&$k)
	{
		$tabla = "";
		$sd = "style=\"border-left:0px;border-top:0px;border-right:0px;border-bottom:1px solid #000000\"";
		$notas = $this->ConsultarInformacionNotas($prefijo,$factura,$empresa);
		$notasd = $this->ObtenerValorNotaDebito($prefijo,$factura,$empresa);
		if(!empty($notas) || !empty($notasd))
		{
			//$tabla .= "			<tr>\n";
			$tabla .= "				<td colspan=\"2\">\n";
			$tabla .= "					<table width=\"20%\" class=\"normal_10\" >\n";
// 			$tabla .= "						<tr>\n";
// 			$tabla .= "							<td width=\"110\" align=\"center\"><b>Nº NOTA</b></td>\n";
// 			$tabla .= "							<td width=\"100\" align=\"center\"><b>VALOR</b></td>\n";
// 			$tabla .= "						</tr>\n";

			for($n=0; $n < sizeof($notas); $n++)
			{
				$tabla .= "						<tr>\n";
				$tabla .= "							<td width=\"110\" align=\"center\">".$notas[$n]['prefijo_nota']." ".$notas[$n]['nota_credito_ajuste']."</td>\n";
				$tabla .= "							<td width=\"100\" align=\"center\">".FormatoValor($notas[$n]['abono'])."</td>\n";
				$tabla .= "						</tr>\n";
				$this->total_nota += $notas[$n]['abono'];
				$k++;
			}
			
			for($n=0; $n < sizeof($notasd); $n++)
			{
				$tabla .= "						<tr>\n";
				$tabla .= "							<td width=\"110\" align=\"center\">".$notasd[$n]['prefijo_nota']." ".$notasd[$n]['nota_credito_ajuste']."</td>\n";
				$tabla .= "							<td width=\"100\" align=\"center\">".FormatoValor($notasd[$n]['abono'])."</td>\n";
				$tabla .= "						</tr>\n";
				$this->total_notad += $notasd[$n]['abono'];
				$k++;
			}
			
			$tabla .= "					</table>\n";
			$tabla .= "				</td>\n";
			//$tabla .= "				<td colspan=\"3\">&nbsp;</td>\n";
			//$tabla .= "			</tr>";
		}
		else
		{
			$tabla .= "				<td colspan=\"2\">&nbsp;\n";
			$tabla .= "				</td>\n";
		}
		return $tabla;
	}
	
	function ConsultarInformacionNotas($prefijo,$factura,$empresa)
	{
		$sql .= "	SELECT 	valor_nota AS abono,
											prefijo AS prefijo_nota,
											nota_credito_id AS nota_credito_ajuste
							FROM		notas_credito
							WHERE		prefijo_factura = '".$prefijo."'
							AND			factura_fiscal = ".$factura."
							AND			empresa_id = '".$empresa."'
							AND			estado IN ('1')
							UNION
							SELECT 	SUM(NF.valor_abonado) AS abono,
											NF.prefijo AS prefijo_nota,
											NF.nota_credito_ajuste
							FROM		notas_credito_ajuste_detalle_facturas AS NF,
											notas_credito_ajuste_detalle_conceptos AS NC
							WHERE		NF.prefijo_factura = '".$prefijo."'
							AND			NF.factura_fiscal = ".$factura."
							AND			NF.empresa_id = '".$empresa."'
							AND			NC.empresa_id = NF.empresa_id
							AND 		NC.nota_credito_ajuste = NF.nota_credito_ajuste
							AND			NC.prefijo = NF.prefijo
							AND			NC.concepto_id != 246
							GROUP BY 2,3
							UNION
							SELECT 	SUM(NG.abono) AS abono,
											NG.prefijo AS prefijo_nota,
											NG.numero AS nota_credito_ajuste
							FROM		glosas G,
											(	SELECT 	glosa_id,
																prefijo,
																numero,
																SUM(valor_aceptado) AS abono
												FROM 		notas_credito_glosas
												GROUP BY 1,2,3
												UNION
												SELECT 	glosa_id,
																prefijo,
																numero,
																SUM(valor_aceptado) AS abono
												FROM 		notas_credito_glosas_detalle_cargos
												GROUP BY 1,2,3
												UNION
												SELECT 	glosa_id,
																prefijo,
																numero,
																SUM(valor_aceptado) AS abono
												FROM		notas_credito_glosas_detalle_inventarios
												GROUP BY 1,2,3
											)	AS NG
							WHERE		G.prefijo = '".$prefijo."'
							AND			G.factura_fiscal = ".$factura."
							AND			G.empresa_id = '".$empresa."'
							AND			G.sw_estado != '0'
							AND			NG.abono > 0
							AND			NG.glosa_id = G.glosa_id
							GROUP BY 2,3 
							ORDER BY 2,3";
		list($dbconn) = GetDBconn();
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al consultar autorizaiones";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$arr = array();
		while(!$result->EOF)
		{
			$arr[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		return $arr;
	}
	
	function ObtenerValorNotaDebito($prefijo,$factura,$empresa)
	{
		$sql .= "	SELECT 	valor_nota AS abono,
											prefijo AS prefijo_nota,
											nota_debito_id AS nota_credito_ajuste
							FROM		notas_debito
							WHERE		prefijo_factura = '".$prefijo."'
							AND			factura_fiscal = ".$factura."
							AND			empresa_id = '".$empresa."'
							AND		estado IN ('1')";
		
		list($dbconn) = GetDBconn();
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al consultar autorizaiones";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$arr = array();
		while(!$result->EOF)
		{
			$arr[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		return $arr;
	}
	
	function ObtenerPagosPaciente($prefijo,$factura,$empresa)
	{//
	//echo '<br><br><br>';
		$sql .= "SELECT c.total_factura
							FROM (SELECT c.numerodecuenta
									FROM 
									--envios as a, 
									envios_detalle as b, fac_facturas_cuentas as c, fac_facturas as d
									WHERE 
									--a.envio_id=18805
									--AND a.envio_id=b.envio_id 
									b.prefijo = '$prefijo' 
									AND b.factura_fiscal = $factura
									AND b.empresa_id = '$empresa'
									AND b.prefijo=c.prefijo 
									AND b.factura_fiscal=c.factura_fiscal
									AND d.prefijo=c.prefijo 
									AND d.factura_fiscal=c.factura_fiscal) AS a, 
							fac_facturas_cuentas as b,
							fac_facturas c
							WHERE a.numerodecuenta = b.numerodecuenta 
							AND b.sw_tipo IN ('0')
							AND b.prefijo=c.prefijo 
							AND b.factura_fiscal=c.factura_fiscal 
							";
		
		list($dbconn) = GetDBconn();
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al consultar autorizaiones";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$arr = array();
		while(!$result->EOF)
		{
			$arr[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		return $arr;
	}
	
	function ObtenerValorLetras($prefijo,$factura,$empresa)
	{//
		$sql .= "SELECT b.valor
							FROM (SELECT c.numerodecuenta
									FROM 
									--envios as a, 
									envios_detalle as b, fac_facturas_cuentas as c, fac_facturas as d
									WHERE 
									--a.envio_id=18805
									--AND a.envio_id=b.envio_id 
									b.prefijo = '$prefijo' 
									AND b.factura_fiscal = $factura
									AND b.empresa_id = '$empresa'
									AND b.prefijo=c.prefijo 
									AND b.factura_fiscal=c.factura_fiscal
									AND d.prefijo=c.prefijo 
									AND d.factura_fiscal=c.factura_fiscal) AS a, 
							pagares b
							WHERE a.numerodecuenta = b.numerodecuenta 
							AND b.sw_estado IN ('1')
							";
		
		list($dbconn) = GetDBconn();
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al consultar autorizaiones";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$arr = array();
		while(!$result->EOF)
		{
			$arr[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		return $arr;
	}
	
	function ObtenerUsuario($usuario)
	{//ObtenerUsuario
		$sql .= "SELECT nombre
							FROM system_usuarios
							WHERE usuario_id=$usuario";
		
		list($dbconn) = GetDBconn();
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al consultar system_usuarios";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if(!$result->EOF)
		{
			$arr=$result->GetRowAssoc($ToUpper = false);
		}
		return $arr[nombre];	
	}
	
	function ObtenerTablaPagos($prefijo,$factura,$empresa,&$k)
	{
		$tabla = "";
		$sd = "style=\"border-left:0px;border-top:0px;border-right:0px;border-bottom:1px solid #000000\"";
		$pagos_paciente = $this->ObtenerPagosPaciente($prefijo,$factura,$empresa);
		if(!empty($pagos_paciente))
		{
			//$tabla .= "			<tr>\n";
			$tabla .= "				<td>\n";
			$tabla .= "					<table width=\"100%\" class=\"normal_10\" >\n";
			//$tabla .= "						<tr>\n";
			//$tabla .= "							<td width=\"110\" align=\"center\"><b>Nº NOTA</b></td>\n";
			//$tabla .= "							<td width=\"100\" align=\"center\"><b>VALOR</b></td>\n";
			//$tabla .= "						</tr>\n";

			for($n=0; $n < sizeof($pagos_paciente); $n++)
			{
				$tabla .= "						<tr>\n";
				//$tabla .= "							<td width=\"110\" align=\"center\">".$pagos_paciente[$n]['prefijo_nota']." ".$pagos_paciente[$n]['nota_credito_ajuste']."</td>\n";
				$tabla .= "							<td width=\"100\" align=\"center\">".FormatoValor($pagos_paciente[$n]['total_factura'])."</td>\n";
				$tabla .= "						</tr>\n";
				//$this->total_nota += $notas[$n]['abono'];
				$k++;
			}
			
			$tabla .= "					</table>\n";
			$tabla .= "				</td>\n";
			//$tabla .= "				<td colspan=\"3\">&nbsp;</td>\n";
			//$tabla .= "			</tr>";
		}
		else
		{
			$tabla .= "				<td>&nbsp;\n";
			$tabla .= "				</td>\n";
		}
		return $tabla;
	}
	
	function ObtenerTablaLetras($prefijo,$factura,$empresa,&$k)
	{
		$tabla = "";
		$sd = "style=\"border-left:0px;border-top:0px;border-right:0px;border-bottom:1px solid #000000\"";
		//$notas = $this->ConsultarInformacionNotas($prefijo,$factura,$empresa);
		$pagares = $this->ObtenerValorLetras($prefijo,$factura,$empresa);
		if(!empty($pagares))
		{
			//$tabla .= "			<tr>\n";
			$tabla .= "				<td>\n";
			$tabla .= "					<table width=\"100%\" class=\"normal_10\" >\n";
// 			$tabla .= "						<tr>\n";
// 			$tabla .= "							<td width=\"110\" align=\"center\"><b>Nº NOTA</b></td>\n";
// 			$tabla .= "							<td width=\"100\" align=\"center\"><b>VALOR</b></td>\n";
// 			$tabla .= "						</tr>\n";

			for($n=0; $n < sizeof($pagares); $n++)
			{
				$tabla .= "						<tr>\n";
				$tabla .= "							<td width=\"100\" align=\"center\">".FormatoValor($pagares[$n]['valor'])."</td>\n";
				$tabla .= "						</tr>\n";
				//$this->total_nota += $notas[$n]['abono'];
				$k++;
			}
						
			$tabla .= "					</table>\n";
			$tabla .= "				</td>\n";
			//$tabla .= "				<td colspan=\"3\">&nbsp;</td>\n";
			//$tabla .= "			</tr>";
		}
		else
		{
			$tabla .= "				<td>&nbsp;\n";
			$tabla .= "				</td>\n";
		}
		return $tabla;
	}
}

?>
