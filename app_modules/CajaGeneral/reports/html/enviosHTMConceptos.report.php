<?php

/**
 * $Id: enviosHTMConceptos.report.php,v 1.5 2010/11/18 14:18:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class enviosHTMConceptos_report
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
	function enviosHTMConceptos_report($datos=array())
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
		$Salida = $this->ReporteDefault();
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
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">".$arr[0][nombre_tercero]."   ".$arr[0][tipo_id_tercero]." ".$arr[0][tercero_id]."</td>\n";
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
			$Salida .= "				<td align=\"CENTER\" class=\"normal_10N\">Fecha elaboraci�n:</td>";
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
			//$Salida .= "				<td WIDTH=215 class=\"normal_10N\">USUARIO</td>\n";
			//$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10N\">PLAN</td>\n";
			$Salida .= "			</tr>\n";
			for($i=0; $i<sizeof($arr);)
			{
							$x=0;
							$d=$i;
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
													$Salida .= "				<td WIDTH=130 class=\"normal_10\">".$arr[0][tipo_id_tercero]." ".$arr[0][tercero_id]."</td>\n";
													//$Salida .= "				<td WIDTH=215 class=\"normal_10\">AGRUPADA</td>\n";
													//$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
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
/*							else
							{	//no es agrupada
									$Salida .= "			<tr>\n";
									$Salida .= "				<td WIDTH=110 align=\"CENTER\" class=\"normal_10\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>\n";
									$Salida .= "				<td WIDTH=100 align=\"CENTER\" class=\"normal_10\">".FormatoValor($arr[$i][total_factura])."</td>\n";
									$Salida .= "				<td WIDTH=100 class=\"normal_10\">".$arr[$i][tipo_id_tercero]." ".$arr[$i][tercero_id]."</td>\n";
									$Salida .= "				<td WIDTH=215 class=\"normal_10\">".$arr[$i][nomusuario]."</td>\n";
									//$Salida .= "				<td WIDTH=205 align=\"CENTER\" class=\"normal_10\">".$arr[$i][plan_descripcion]."</td>\n";
									$Salida .= "			</tr>\n";
									$total+=$arr[$i][total_factura];
									
									$Salida .= $this-> ObtenerTablaNotas($arr[$i][prefijo],$arr[$i][factura_fiscal],$arr[$i][empresa_id],&$k);
									
									if($cont==0)
									{  $cuentas .=$arr[$i][numerodecuenta];  $cont++;}
									else
									{  $cuentas .=','.$arr[$i][numerodecuenta];  }
									$i++;
							}*/
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
  *****************************************************************************************************
	* Funciones Necesarias
  */
	function DetalleEnvio($envio)
	{
			list($dbconn) = GetDBconn();
			$query = "
						SELECT 	a.*, 
										b.*, 
										d.total_factura,
										d.valor_cuota_paciente, 
										i.nombre_tercero, 
										i.tipo_id_tercero, 
										i.tercero_id, 
										a.fecha_registro,
										j.usuario_id,
										j.nombre as nomusuario
						FROM 		envios as a, 
										envios_detalle as b, 
										fac_facturas_conceptos as c,
										fac_facturas as d, 
										terceros as i, 
										system_usuarios j
						WHERE 	a.envio_id=".$envio."
										AND a.envio_id=b.envio_id 
										AND b.prefijo=c.prefijo
										AND b.factura_fiscal=c.factura_fiscal 
										AND d.prefijo=c.prefijo
										AND d.factura_fiscal=c.factura_fiscal 
										AND d.tipo_id_tercero=i.tipo_id_tercero 
										AND d.tercero_id=i.tercero_id
										AND a.usuario_id=j.usuario_id
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
												id
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
	
	
	function ConsultaAutorizacion($numerodecuenta)
	{
		list($dbconn) = GetDBconn();
			$query = "
								select c.codigo_autorizacion
								from 	cuentas a,
											autorizaciones b,
											autorizaciones_escritas c
								
								where
											a.numerodecuenta= '".$numerodecuenta."'
											and a.ingreso = b.ingreso
											and b.autorizacion = c.autorizacion
								";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al consultar autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			$result->Close();
			return $result->fields[0];
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
			$tabla .= "							<td width=\"110\" align=\"center\"><b>N� NOTA</b></td>\n";
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
	function ConsultarInformacionNotas($prefijo,$factura,$empresa)
	{
		$sql .= "	SELECT 	valor_nota AS abono,
											prefijo AS prefijo_nota,
											nota_credito_id AS nota_credito_ajuste
							FROM		notas_credito
							WHERE		prefijo_factura = '".$prefijo."'
							AND			factura_fiscal = ".$factura."
							AND			empresa_id = '".$empresa."'
							UNION
							SELECT 	SUM(valor_abonado) AS abono,
											prefijo AS prefijo_nota,
											nota_credito_ajuste
							FROM		notas_credito_ajuste_detalle_facturas
							WHERE		prefijo_factura = '".$prefijo."'
							AND			factura_fiscal = ".$factura."
							AND			empresa_id = '".$empresa."'
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
							AND			empresa_id = '".$empresa."' ";
		
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
	
	}

?>
