<?php

/**
 * $Id: FacturaControl.report.php,v 1.5 2010/11/18 14:18:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class FacturaControl_report
{

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function FacturaControl_report($datos=array())
	{
	$this->datos=$datos;
			return true;
	}

	var $datos;

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();


	function GetMembrete()
	{
		$Membrete = array('file'=>'MembreteLogosSOS','datos_membrete'=>array('titulo'=>GetVarConfigAplication('Cliente'),
																'subtitulo'=>'FACTURA CAMBIARIA DE COMPRAVENTA ',
																'logo'=>'logocliente.png',
																'align'=>'center'));
		return $Membrete;
	}

    /**
    *
    */
//     function CrearReporte()
//     {
// 					include_once("classes/fpdf/conversor.php");
// 					
// 					$arr=$this->GetDatosFactura($this->datos['cuenta']);
// 
// 					if($this->datos['sw_copia']==TRUE)
// 					{
// 						$copia='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COPIA';
// 					}
// 					else
// 					{
// 						$copia='';
// 					}
// 
// 					/***** generamos el html ********/
// 
// 					//$salida="<table width='100%' border=1>";
// 					$salida.="<table width='100%' border=5>";
// 					$salida.="  <TR><b>";
// 					$salida.="<TD COLSPAN='2' WIDTH='70'><label><font size='4'><b>RECIBO DE CAJA NO</b></font><font size='3'> :&nbsp;".$arr[1][prefijo]."-&nbsp;".$arr[1][factura_fiscal]." </label></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$copia</TD>";
// 					$salida.="  </TR>";
// 					
// 					$salida.="  <TR><b>";
// 					$salida.="<TD  WIDTH='60%'><label ><b><font size='2'>FECHA</b> :&nbsp;".date('d/m/Y h:i')."</label></font></TD>";
// 					$salida.="<TD  WIDTH='40%'><label><b><font size='2'>CENTRO DE ATENCION</b> :&nbsp;".$arr[0][descripcion]."</label></font></TD>";
// 					$salida.="  </TR>";
// 
// 					$salida.="  <TR>";
// 					$salida.="<TD  WIDTH='60%'><label><b><font size='2'>NOMBRE PACIENTE</b>:&nbsp;".$arr[0][nombre]."</label></font></TD>";
// 					$salida.="<TD  WIDTH='40%'><label><b><font size='2'>NO.IDENTIFICACION</b>:&nbsp;".$arr[0][tipo_id_paciente]."-
// 					&nbsp;".$arr[0][paciente_id]."</label></font></TD>";
// 					
// 					$salida.="  </TR>";
// 					$salida.="</table>";
// 
// 
// 

// /*if( $i % 2){ $estilo2='#CCCCCC';}
// 						else {$estilo2='#DDDDDD';}*/
// 
// 					$salida.="<table width='100%' border=5>";
// 					$salida.="  <TR><font size='2'><b>";
// 					$salida.="<TD colspan='5' WIDTH='60%'><label>DETALLE</label></TD>";
// 					$salida.="<TD  WIDTH='40%'>VALOR</TD>";
// 					$salida.="  </b></font></TR>";
// 
// 					$salida.="<TR>";
// 					$salida.="<TD colspan='5'>";
// 					$salida.="<table width='100%' border=0>";
// 					for($i=1;$i<sizeof($arr);$i++)
// 					{
//             			//factura cliente
// 									if($arr[1][sw_tipo]==1)
// 									{
// 											$salida.=" <TR><TD WIDTH='60%'><font size='1'>".$arr[$i][desccargo]."</font></TD></TR>";
// 											 											
// 									}
// 									else
// 									{   //factura paciente	
// 											$salida.=" <TR><TD WIDTH='60%'><font size='1'>".$arr[$i][desccargo]."</font></TD></TR>";
// 									}
// 					}
// 						$salida.="</table>";
// 						$salida.="</TD>";
// 
// 							$salida.="  <TD  ROWSPAN='3' WIDTH='40%'><font size='3'>";
// 							if($arr[1][valor_cuota_paciente]>0)
// 							{
// 								$salida.=$arr[0][nombre_copago].":&nbsp;$&nbsp;".FormatoValor($arr[1][valor_cuota_paciente])."<br>";
// 							}
// 
// 							if($arr[1][valor_cuota_moderadora]>0)
// 							{
// 									$salida.=$arr[0][nombre_cuota_moderadora].":&nbsp;$&nbsp;".FormatoValor($arr[1][valor_cuota_moderadora])."<br>";
// 							}
// 
// 							if($arr[1][valor_cargo]>0)
// 							{
// 									$salida.="Valor no Cubierto :&nbsp;$&nbsp;".FormatoValor($datos[1][valor_cargo])."<br>";
// 							}
// 
// 							if($arr[1][gravamen] > 0)
// 							{
// 									$salida.="Valor no Cubierto :&nbsp;$&nbsp;".FormatoValor($arr[1][gravamen])."<br>";
// 							}
// 
// 							$salida.="VALOR A PAGAR:&nbsp;$&nbsp;".FormatoValor($arr[1][total_factura])."<br>";
// 
// 							$salida.="  </TD>";	
// 
// 						$salida.="</TR>";
// 
// 						$salida.="</table>";
// 
// 			$total=str_replace(".","",FormatoValor($arr[1][total_factura]));
//      
// 					
// 			if($total >0)
// 			{
// 				$salida.="<table width='100%' border=5>";
// 				$salida.="  <TR><font size='3'><b>";
// 				$salida.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH='70'><label><font size='2'><b>TOTAL EN LETRAS</b> </font>:&nbsp;".convertir_a_letras($total)."******************** </label></TD>";
// 				$salida.="  </TR>";
// 				$salida.="  </table>";
// 			}
// 						 
// 			$salida.="<table width='100%' border=5>";
// 			$salida.="  <TR><font size='3'><b>";
// 			$salida.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH='70'><label><font size='2'><b>ATENDIO</b> </font>:&nbsp;".$arr[0][usuario_id]."-&nbsp;".$arr[0][usuario]." </label></TD>";
// 			$salida.="  </TR>";
// 			$salida.="  </table>";
// 			//$salida.="  </table>";
// 						
//        return $salida;
//     }

    function CrearReporte()
    {
					include_once("classes/fpdf/conversor.php");
					
					$arr=$this->GetDatosCierre($this->datos['cierre']);

					if($this->datos['sw_copia']==TRUE)
					{
						$copia='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COPIA';
					}
					else
					{
						$copia='';
					}
					if($this->datos['cuentatipo']=='03')
					{
						$tipocliente='TERCERO';
					}
					else
					{
						$tipocliente='PACIENTE';
					}

					/***** generamos el html ********/

				$salida = "		<br><table width='100%' border=5>";
				$salida .= "			<tr>";
				$impresion="IMPRESION :";
				$salida .= "				<td align='center' width=70 ><font size='4'><b>".$datos['razon_social']."$space $space $space".$datos['tipo_id_tercero']."$space".$datos['id']."$space $space $space $space $impresion $space".date("Y-m-d H:i")."</b></font></td>";
				$salida.= "			</tr>";
				$salida .= "			<tr>";
				$salida.= "				<td align='center' width=70 ><font size='4'><b>".$datos['descripcion']."</b></font></td>";
				$salida.= "			</tr>";
				$salida .= "			<tr>";
				$salida.= "				<td align='center' width=70 ><font size='4'><b>".$info['usuario_id']."$space $space".$info['nombre']."</b></font></td>";
				$salida.= "			</tr>";
				$datos_cierre=$this->TraerDatoCierreDeCaja($this->datos['cierre']);
				$salida .= "			<tr>";
				$fech=explode(".",$datos_cierre[fecha_registro]);
				$TITULO='REPORTE DE CIERRE DE CAJA :'." ".strtoupper($datos_cierre['descuenta'])."";
				$salida.= "				<td width=70 ><font size='4'><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";
				//esta parte es nueva de sos


				$salida .= "			<tr>";
				$TITULO='REPORTE DE CIERRE DE CAJA No :'." ".$datos_cierre[cierre_de_caja_id]."";
				$salida.= "				<td width=70 ><font size='4'><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";


				$salida .= "			<tr>";
				$TITULO='FECHA DE CIERRE DE CAJA :'."$fech[0]";
				$salida.= "				<td width=780 ><font size='4'><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";	
				$salida .= "			<tr>";

				$fech1=explode(".",$datos_cierre[fecha_confirmacion]);
				$TITULO='FECHA CONFIRMACIÓN CIERRE DE CAJA :'."$fech1[0]";
				$salida.= "				<td width=780 ><font size='4'><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";	
				
				$salida.="</table>";
				$salida .= "		<table width='100%' border=5 >";
				$salida.= "				<tr><td  width='68' ><font color='black'><b>RECIBO</b></font></td>";
				$salida.= "				<td width='80'><font color='black'><b>FECHA</b></font></td>";
				$salida.= "				<td  width='310'><font color='black'><b>".$tipocliente."</b></font></td>";
				$salida.= "				<td width='65'><font color='black'><b>EFECTIVO</b></font></td>";
				$salida.= "				<td width='65'><font color='black'><b>CHEQUE</b></font></td>";
				$salida.= "				<td width='65'><font color='black'><b>TARJETAS</b></font></td>";
				$salida.= "				<td width='65'><font color='black'><b>BONOS</b></font></td>";

				/*if(!empty($_SESSION['REF_DPTO']))
				{
					$html.= "				<td width='67'><font color='white'><b>DESCUENTO</b></font></td>";
				}*/
				$salida.= "				<td width='60'><font color='black'><b>SUBTOTAL</b></font></td></tr>";
				$salida.="</tr>";
				/*$salida.="<tr>";*/
					//$salida="<table width='100%' border=1>";
					for($j=0;$j<sizeof($arr);$j++)
					{
								//factura cliente
						$ar=$this->GetDatosFactura($arr[$j][cierre_caja_id]);
						$obsevaciones=$arr[$j][observaciones];
						$observaciones_confirmacion=$arr[$j][observaciones_confirmacion];
						for($i=0;$i<sizeof($ar);$i++)
						{
							if( $i % 2){ $estilo='#CCCCCC';}
							else {$estilo='#DDDDDD';}
							$salida.="<tr>";
							$salida.="  <td  width='58' bgcolor=$estilo>".$ar[$i][prefijo]."$sp $sp".$ar[$i][factura_fiscal]."</td>";

							//if($_SESSION['CAJA']['CIERRE']['DEPTO'])
/*							if(empty($_SESSION['REF_DPTO']))
							{	//echo REF_DPTO;exit;
								//si entra aqui es por q ws cierre normal osea hospitalario
								$pac=TraerDatosPaciente($arr[$i][recibo_caja],$arr[$i][prefijo]);
							}	
							else
							{//echo NOREF_DPTO;
								//si entra aqui es por q ws cierre es de cualquiere caja rapida
								$pac=TraerDatosPacienteCajaR($arr[$i][recibo_caja],$arr[$i][prefijo]);
							}*/
							if($this->datos['cuentatipo']=='08' || $this->datos['cuentatipo']=='03')
								$pac=$this->TraerDatosTercero($ar[$i][factura_fiscal],$ar[$i][prefijo]);
							else
								$pac=$this->TraerDatosPaciente($ar[$i][factura_fiscal],$ar[$i][prefijo]);
							if(empty($pac)){$pac="-------";}
							$fecha=explode(" ",$ar[$i][fecha_registro])	;
							$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
							$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']." ".$pac['nombre']."</td>";
							$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($ar[$i][total_efectivo])."</td>";
							$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($ar[$i][total_cheques])."</td>";
							$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($ar[$i][total_tarjetas])."</td>";
							$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($ar[$i][total_bonos])."</td>";
				
				
							/*if(!empty($_SESSION['REF_DPTO']))
							{	
								$descuento=TraerDescuento($arr[$i][numerodecuenta]);
								$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
							}	*/
							//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
							if($ar[$i][total_abono]==-1)
							{
								$salida.="  <td width='60' bgcolor=$estilo><font color='red'>ANULADO</font></td>";
							}
							else
							{
								$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($ar[$i][total_abono])."</td>";
							}
							if($ar[$i][total_abono]!=-1)
							{
								$bon=$bon+$ar[$i][total_bonos];
								$efe=$efe+$ar[$i][total_efectivo];
								$che=$che+$ar[$i][total_cheques];
								$tar=$tar+$ar[$i][total_tarjetas];
								$tbon=$tbon+$ar[$i][total_bonos];
								$tdes=$tdes+$descuento;
								//$sum=$sum + $arr[$i][suma];
								$sum=$sum + $ar[$i][total_abono];
							}
							$salida.="</tr>";
						}
					}
					$salida.="</table>";
					$salida .= "		<br><table width='100%' border='3' align='center' >";
					$salida .= "			<tr>";
					$salida.= "				<td align='center' width='120' bgcolor=$estilo><font size='2'>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</font></td>";
					$salida.= "				<td align='center' width='120' bgcolor=$estilo><font size='2'>TOTAL CHEQUES :"." ".FormatoValor($che)."</font></td>";
					$salida.= "				<td align='center' width='120' bgcolor=$estilo ><font size='2'>TOTAL TARJETAS :"." ".FormatoValor($tar)."</font></td>";
					$salida.= "				<td align='center' width='120' bgcolor=$estilo ><font size='2'>TOTAL BONOS :"." ".FormatoValor($tbon)."</font></td>";
			
					/*if(!empty($_SESSION['REF_DPTO']))
					{	
						$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
					}	*/
					$salida.= "				<td align='center' width='120' bgcolor=$estilo ><font color='red'>TOTAL :".FormatoValor($sum)."</font></td>";
					$salida.= "			</tr>";
					$salida.="</table>";
					//OBSERVACIONES
					$salida .= "		<br><br><table width='100%' border='3' align='center' >";
					$salida .= "			<tr>";
					$salida.= "				<td align='left' width='120' bgcolor=$estilo><font size='2'>___________________________________</font></td>";
					$salida.= "				<td align='center' width='640' bgcolor=$estilo><font size='2'>&nbsp;</font></td>";
					$salida.= "			</tr>";
					$salida .= "			<tr>";
					$salida.= "				<td align='center' width='120' bgcolor=$estilo><font size='1'>FIRMA </font></td>";
					$salida.= "				<td align='center' width='640' bgcolor=$estilo><font size='2'>&nbsp;</font></td>";
					$salida.= "			</tr>";
					if($obsevaciones)
					{
						$salida .= "			<tr>";
						$salida.= "				<td align='left' width='760' bgcolor=$estilo colspan='2'><font size='1'><b>OBSERVACIONES CIERRE : </b>".$obsevaciones."</font></td>";
						$salida.= "			</tr>";
					}
					if($observaciones_confirmacion)
					{
						$salida .= "			<tr>";
						$salida.= "				<td align='left' width='760' bgcolor=$estilo colspan='2'><font size='1'><b>OBSERVACIONES CONFIRMACION : </b>".$observaciones_confirmacion."</font></td>";
						$salida.= "			</tr>";
					}
					$salida.="</table>";
					return $salida;
    }

function TraerDatoCierreDeCaja($secuencia)
{
		list($dbconn) = GetDBconn();
					$query = "SELECT a.fecha_registro,a.observaciones,
										a.cierre_de_caja_id,
										a.fecha_confirmacion,
										b.descripcion AS descuenta
										FROM cierre_de_caja a, cajas_rapidas b 
										WHERE 
											a.caja_id=b.caja_id 
										AND a.cierre_de_caja_id=$secuencia";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer datos del usuario";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$var=$resulta->GetRowAssoc($ToUpper = false);
					return $var;
}

function TraerDatosPaciente($recibo,$prefijo)
{
	list($dbconn) = GetDBconn();
			$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,fac_facturas_cuentas a
                WHERE a.factura_fiscal=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente;";
									
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}


	function GetDatosCierre($cierre)
	{
				unset($_SESSION['CAJA']['FACTURA']['encabezado']);
				$var[0]=$this->EncabezadoFactura($cuenta);
				$_SESSION['CAJA']['FACTURA']['encabezado']=$var[0];

        list($dbconn) = GetDBconn();
        
				//siempre se hace la del paciente
/*			echo	$query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
									a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
									b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
									e.texto1, e.texto2, e.mensaje, f.*
									from cuentas_detalle as a, tarifarios_detalle as b,
									fac_facturas_cuentas as c, documentos as e, fac_facturas as f
									where a.numerodecuenta=$cuenta and a.cargo=b.cargo
									and a.tarifario_id=b.tarifario_id
									and a.cargo!='DESCUENTO'
									and c.numerodecuenta=a.numerodecuenta
									and c.sw_tipo=0
									and a.empresa_id=e.empresa_id
									and c.prefijo=e.prefijo
									and c.prefijo=f.prefijo
									and c.factura_fiscal=f.factura_fiscal
									order by b.grupo_tipo_cargo desc ";*/
				$query = "SELECT b.cierre_caja_id, a.observaciones, a.observaciones_confirmacion
								FROM cierre_de_caja a, cierre_de_caja_detalle b
								WHERE a.cierre_de_caja_id=".$cierre."
								AND a.cierre_de_caja_id=b.cierre_de_caja_id;";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				$i=0;
				while(!$result->EOF)
				{
					$var[$i]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
					$i++;
				}
				//$result->Close();
				
/*				$j=0;
				for ($i=0;$i<sizeof($var);$i++)
				{
				echo	$query = "SELECT a.total_abono,a.total_efectivo,a.total_cheques,
										a.total_tarjetas,a.total_bonos,a.prefijo,a.factura_fiscal,
										a.caja_id,a.usuario_id,
										btrim(e.primer_nombre||' '||e.segundo_nombre||' ' ||
										e.primer_apellido||' '||e.segundo_apellido,'') as nombre,
										e.tipo_id_paciente||' '||e.paciente_id as id
								FROM fac_facturas_contado a, fac_facturas_cuentas b,
										cuentas c,ingresos d, pacientes e
								WHERE a.cierre_caja_id=".$var[$i][cierre_caja_id]."
								AND a.empresa_id=b.empresa_id
								AND a.prefijo=b.prefijo
								AND a.factura_fiscal=b.factura_fiscal
								AND b.numerodecuenta=c.numerodecuenta
								AND c.ingreso=d.ingreso
								AND d.tipo_id_paciente=e.tipo_id_paciente
								AND d.paciente_id=e.paciente_id;";
					$resulta = $dbconn->Execute($query);
					while(!$resulta->EOF)
					{
						$var2[$j]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$j++;
					}
				}*/

        return $var;    
	}
		
		function TraerDatosTercero($recibo,$prefijo)
		{
			list($dbconn) = GetDBconn();
					$query = "SELECT  b.nombre_tercero as nombre,
										b.tipo_id_tercero||' '||b.tercero_id as id
		
										FROM fac_facturas_contado a, terceros b
										WHERE a.factura_fiscal=".$recibo." 
										AND a.prefijo='".$prefijo."'
										AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."' 
										AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
										AND a.tipo_id_tercero=b.tipo_id_tercero
										AND a.tercero_id=b.tercero_id;";
											
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer datos del usuario";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$var=$resulta->GetRowAssoc($ToUpper = false);
					return $var;
		}
	
	function GetDatosFactura($cierre)
	{
			list($dbconn) = GetDBconn();
			//, a.total_abono
			$query = "SELECT a.factura_fiscal,a.prefijo, a.fecha_registro, a.total_efectivo,
										a.total_tarjetas, a.total_cheques, 
										a.total_bonos, a.tipo_id_tercero, a.tercero_id,a.caja_id,
										c.nombre, d.descripcion as caja,b.sw_facturado, d.descripcion as caja,
										CASE WHEN e.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
								FROM fac_facturas_contado a, recibos_caja_cierre as b,
											system_usuarios as c, cajas_rapidas d, fac_facturas e
								WHERE a.cierre_caja_id=".$cierre."
								AND a.usuario_id=c.usuario_id
								AND a.cierre_caja_id=b.cierre_caja_id
								AND a.prefijo=e.prefijo
								AND a.factura_fiscal=e.factura_fiscal
								AND b.sw_facturado='1'
								AND d.caja_id=a.caja_id;";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
					}
				$i=0;
				while (!$resulta->EOF)
				{
					$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
					$i++;
				}
			return $var;
	}
		
// 	function GetDatosFactura($cuenta)
// 	{
// 				unset($_SESSION['CAJA']['FACTURA']['encabezado']);
//          $var[0]=$this->EncabezadoFactura($cuenta);
// 				$_SESSION['CAJA']['FACTURA']['encabezado']=$var[0];
// 
//         list($dbconn) = GetDBconn();
//         
// 				//siempre se hace la del paciente
// 			echo	$query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
// 									a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
// 									b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
// 									e.texto1, e.texto2, e.mensaje, f.*
// 									from cuentas_detalle as a, tarifarios_detalle as b,
// 									fac_facturas_cuentas as c, documentos as e, fac_facturas as f
// 									where a.numerodecuenta=$cuenta and a.cargo=b.cargo
// 									and a.tarifario_id=b.tarifario_id
// 									and a.cargo!='DESCUENTO'
// 									and c.numerodecuenta=a.numerodecuenta
// 									and c.sw_tipo=0
// 									and a.empresa_id=e.empresa_id
// 									and c.prefijo=e.prefijo
// 									and c.prefijo=f.prefijo
// 									and c.factura_fiscal=f.factura_fiscal
// 									order by b.grupo_tipo_cargo desc ";exit;
// 				$result = $dbconn->Execute($query);
// 				if ($dbconn->ErrorNo() != 0) {
// 								$this->error = "Error al Cargar el Modulo";
// 								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 								return false;
// 				}
// 				while(!$result->EOF)
// 				{
// 								$var[]=$result->GetRowAssoc($ToUpper = false);
// 								$result->MoveNext();
// 				}
// 				$result->Close();
// 				
// 				
// 				

//         
//         return $var;
//     
// 	}



function GetFacturaXEmpresa($switche,$cuenta)
{
	list($dbconn) = GetDBconn();
	if(!empty($switche))
        {
							//$var[0]=$this->EncabezadoFactura($cuenta);
							$var[0]=$_SESSION['CAJA']['FACTURA']['encabezado'];
							$query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
												a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
												b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
												e.texto1, e.texto2, e.mensaje, f.*
												from cuentas_detalle as a, tarifarios_detalle as b,
												fac_facturas_cuentas as c, documentos as e, fac_facturas as f
												where a.numerodecuenta=$cuenta and a.cargo=b.cargo
												and a.tarifario_id=b.tarifario_id
												and a.cargo!='DESCUENTO'
												and c.numerodecuenta=a.numerodecuenta
												and c.sw_tipo=1
												and a.empresa_id=e.empresa_id
												and c.prefijo=e.prefijo
												and c.prefijo=f.prefijo
												and c.factura_fiscal=f.factura_fiscal
												order by b.grupo_tipo_cargo desc ";
              $result = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Cargar el Modulo";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
              }
              while(!$result->EOF)
              {
                      $var[]=$result->GetRowAssoc($ToUpper = false);
                      $result->MoveNext();
              }
							$result->Close();

        }
return $var;

}





function EncabezadoFactura($cuenta)
  {
        list($dbconn) = GetDBconn();
      $query = "select (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos) as abonos,
                  a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
                  c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
                  e.primer_nombre||' '||e.segundo_nombre||' '||e.primer_apellido||' '||e.segundo_apellido as nombre,
                  e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
                  i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid,
                  i.id, j.departamento, k.municipio, d.fecha_registro, a.rango, Z.tipo_afiliado_nombre,
                  b.nombre_cuota_moderadora, b.nombre_copago, x.nombre as nombre_usuario, x.usuario_id,x.usuario,
									a.valor_cuota_moderadora, a.valor_cuota_paciente, a.valor_nocubierto,
									a.valor_total_paciente, a.valor_total_empresa, a.valor_descuento_paciente,
									a.valor_descuento_empresa, a.valor_cubierto
                  from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
                  empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d,
                  system_usuarios as x, tipos_afiliado as Z
                  where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                  and b.tipo_tercero_id=c.tipo_id_tercero
                  and x.usuario_id=".UserGetUID()."
                  and a.tipo_afiliado_id=Z.tipo_afiliado_id
                  and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
                  and d.paciente_id=e.paciente_id
                  and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                  and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                  and d.departamento_actual=h.departamento";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
  }


	
}
?>

