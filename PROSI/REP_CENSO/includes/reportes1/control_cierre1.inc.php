<?php

/**
 * $Id: control_cierre.inc.php,v 1.10 2006/01/06 13:45:31 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * funcion q trae los decuestos de las facturas
 */

	function TraerDescuento($no_cuenta)
	{
			list($dbconn) = GetDBconn();
			$query="SELECT precio
											FROM cuentas_detalle 
											WHERE 
											empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
											AND centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
											AND departamento='".$_SESSION['REF_DPTO']."'
											AND numerodecuenta=$no_cuenta
											AND cargo='DESCUENTO'
											AND tarifario_id='SYS'";
			$resulta=$dbconn->Execute($query);							
			if(!$resulta->fields[0])
			{
			 return 0;
			}
			else
			{
				return $resulta->fields[0];
			}
	}
	
/*
*
* FUNCION QUE POR MEDIO DE LA SECUENCIA DEL CIERRE DE CAJA SACA LA OBSERVACIONES DE LA IMPRESION
*
*/

function TraerDatoCierre()
{
		$secuencia=$_SESSION['TMP']['CONTROL_CIERRE']['CIERRE'];
		list($dbconn) = GetDBconn();
					$query = "SELECT fecha_registro,observaciones,cierre_caja_id
										FROM recibos_caja_cierre WHERE cierre_caja_id=$secuencia ";	
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer datos del usuario";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$var=$resulta->GetRowAssoc($ToUpper = false);
					return $var;
}

/*
*funcion que trae el nombre del usuasrio para el reporte de cierre de caja
*
*/
function TraerDatoUsuario()
{
			list($dbconn) = GetDBconn();
			$query = "SELECT usuario,nombre,usuario_id from system_usuarios WHERE usuario_id=".$_SESSION['TMP']['CONTROL_CIERRE']['ID']."";	
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}

/*
*funcion que trae el nombre del usuasrio para el reporte de cierre de caja general(hosp))
*
*/
function TraerDatosPaciente($recibo,$prefijo,$cuenta)
{
			list($dbconn) = GetDBconn();
			if ($cuenta=='08')
			{
				$query = "SELECT  b.nombre_tercero as nombre,
									b.tipo_id_tercero||' '||b.tercero_id as id
									FROM fac_facturas_contado a, terceros b
									WHERE a.factura_fiscal=".$recibo." 
									AND a.prefijo='".$prefijo."'
									AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
									AND a.tipo_id_tercero=b.tipo_id_tercero
									AND a.tercero_id=b.tercero_id;"; 
			}
			else
			{
				$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
									f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
									f.tipo_id_paciente||' '||f.paciente_id as id
	
									FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
									WHERE a.recibo_caja=".$recibo." 
									AND a.prefijo='".$prefijo."'
									AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."' 
									AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
									AND a.numerodecuenta=x.numerodecuenta
									AND x.ingreso=s.ingreso
									AND s.paciente_id=f.paciente_id
									AND s.tipo_id_paciente=f.tipo_id_paciente";	
			}
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}

/*
*funcion que trae el nombre del usuasrio para el reporte de cierre de caja rapida
*$recibo <-- esta variable en realidad traera la el No.de factura fiscal.
*/
function TraerDatosPacienteCajaR($recibo,$prefijo,$cuenta)
{
			list($dbconn) = GetDBconn();
			/*btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,*/
			if ($cuenta=='08')
			{
			$query = "SELECT  b.nombre_tercero as nombre,
                b.tipo_id_tercero||' '||b.tercero_id as id
								FROM fac_facturas_contado a, terceros b
                WHERE a.factura_fiscal=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.tipo_id_tercero=b.tipo_id_tercero
								AND a.tercero_id=b.tercero_id;"; 
			}
			else
			{
				$query = "SELECT  btrim(f.primer_nombre||'  '|| f.primer_apellido,'') as nombre,
									f.tipo_id_paciente||' '||f.paciente_id as id
	
									FROM pacientes f,ingresos s,cuentas x,fac_facturas_cuentas a
									WHERE a.factura_fiscal=".$recibo." 
									AND a.prefijo='".$prefijo."'
									AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
									AND a.numerodecuenta=x.numerodecuenta
									AND x.ingreso=s.ingreso
									AND s.paciente_id=f.paciente_id
									AND s.tipo_id_paciente=f.tipo_id_paciente";	
			}	
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}



function DatosEncabezadoEmpresa()
	{
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
			list($dbconn) = GetDBconn();
			$query = "SELECT a.descripcion as descuenta, c.descripcion, b.razon_social,b.tipo_id_tercero,b.id
								FROM cajas_rapidas as a, empresas as b,centros_utilidad as c
								WHERE  a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND c.empresa_id=b.empresa_id
								AND a.empresa_id=b.empresa_id
								AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
								and a.caja_id='".$_SESSION['TMP']['CONTROL_CIERRE']['CAJA_ID']."' ";
			
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}

	
	function DatosEncabezadoEmpresaRecibo()
	{
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
			list($dbconn) = GetDBconn();
			$query = "SELECT a.descripcion as descuenta, c.descripcion, b.razon_social,b.tipo_id_tercero,b.id
								FROM cajas as a, empresas as b,centros_utilidad as c
								WHERE  a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND c.empresa_id=b.empresa_id
								AND a.empresa_id=b.empresa_id
								AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
								and a.caja_id='".$_SESSION['TMP']['CONTROL_CIERRE']['CAJA_ID']."' ";
			
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}

function TraerPacienteDev($recibo,$prefijo)
{
		list($dbconn) = GetDBconn();
		$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id
								FROM pacientes f,ingresos s,cuentas x,
										rc_devoluciones a
                WHERE a.devolucion_id=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente
								AND a.empresa_id=a.empresa_id 
								AND a.prefijo=a.prefijo";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			//return $resulta->fields[1]."&nbsp;-&nbsp;".$resulta->fields[0];
			return $var;
}



	function GenerarControlCierreCaja($arr,$id,$caja_id,$dpto,$cierre,$dev,$cuenta)
	{
		//echo "-->".print_r($dev);
		$_SESSION['TMP']['CONTROL_CIERRE']['CAJA_ID']=$caja_id;
		$_SESSION['TMP']['CONTROL_CIERRE']['ID']=$id;
		$_SESSION['TMP']['CONTROL_CIERRE']['DPTO']=$dpto;
		$_SESSION['TMP']['CONTROL_CIERRE']['CIERRE']=$cierre;
		$_SESSION['TMP']['CONTROL_CIERRE']['CUENTA']=$cuenta;
		IncludeLib("tarifario");
		$Dir="cache/control_cierre".UserGetUID()."_".$_SESSION['TMP']['CONTROL_CIERRE']['ID'].".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='cierre_caja';
		$pdf=new PDF();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
		for($i=0;$i<sizeof($arr);$i++)
		{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}
					
					$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";
					if(empty($_SESSION['TMP']['CONTROL_CIERRE']['DPTO']))
					{
						//si entra aqui es por q ws cierre normal osea hospitalario
						$pac=TraerDatosPaciente($arr[$i][recibo_caja],$arr[$i][prefijo],$cuenta);
					}
					else
					{
						//si entra aqui es por q ws cierre es de cualquiere caja rapida
						$pac=TraerDatosPacienteCajaR($arr[$i][recibo_caja],$arr[$i][prefijo],$cuenta);
					}
					if(empty($pac)){$pac="-------";}
					$fecha=explode(" ",$arr[$i][fecha_ingcaja])	;
					$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
					$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
					$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_bonos])."</td>";


					/*if(!empty($_SESSION['TMP']['CONTROL_CIERRE']['DPTO']))
					{	
						$descuento=TraerDescuento($arr[$i][numerodecuenta]);
						$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
					}	*/
					$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
					$bon=$bon+$arr[$i][total_bonos];
					$efe=$efe+$arr[$i][total_efectivo];
					$che=$che+$arr[$i][total_cheques];
					$tar=$tar+$arr[$i][total_tarjetas];
					$tbon=$tbon+$arr[$i][total_bonos];
					$tdes=$tdes+$descuento;
					$sum=$sum + $arr[$i][suma];
					$salida.="</tr>";
		}
		$salida.="</table>";
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";

		/*if(!empty($_SESSION['REF_DPTO']))
		{	
			$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
		}	*/
		$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL :"." ".FormatoValor($sum)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
		//INICIO DEVOLUCIONES
		if(sizeof($dev)>0)
		{
				$efed=0;
				$ciclo=sizeof($dev);
				$salida.="<br><br><table  align=\"center\" border=\"1\" width=\"100%\" >";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width='75'>DEVOLUCIÓN</td>";
				$salida.="  <td width='100'>FECHA</td>";
				$salida.="  <td width=75'>CAJA</td>";
				$salida.="  <td width='310'>PACIENTE</td>";
				$salida.="  <td width='57'>TOTAL</td>";
				$salida.="</tr>";
				for($i=0;$i<$ciclo;$i++)
				{
						$salida.="  <td  width='75'>".$dev[$i][prefijo]."$sp $sp".$dev[$i][devolucion_id]."</td>";
						$pac=TraerPacienteDev($dev[$i][devolucion_id],$dev[$i][prefijo]);
						if(empty($pac)){$pac="-------";}
						$fecha=explode(" ",$dev[$i][fecha_registro])	;
						$salida.="  <td  width='100'>".$fecha[0]."</td>";
						$salida.="  <td  width='75'>".$dev[$i][caja_id]."</td>";
						$salida.="  <td  width='310'>".$pac['id']."$spa".$pac['nombre']."</td>";
						$salida.="  <td  width='57'>".FormatoValor($dev[$i][total_devolucion])."</td>";
						$efed=$efed+$dev[$i][total_devolucion];
						$salida.="</tr>";
				}
				$salida.="</table>";
				$salida .= "		<br><table width=90 border=\"1\" align=\"left\" >";
				$salida .= "			<tr>";
				$salida.= "				<td align='center' width='180'><font color='black'><b>TOTAL DEVOLUCIONES:</b></font>"."<font color='red'> ".FormatoValor($efed)."</font></td>";
				$salida.= "			</tr>";
				$salida.="</table>";
		}
		//FIN DEVOLUCIONES
		
		//TOTALES
		if(sizeof($dev>0))
		{
			$salida .= "		<br><table width=90 border=\"1\" align=\"left\" >";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="  <td width='100'><font color='black' size='12'>TOTAL RECIBOS</font></td>";
			$salida.="  <td width='150'><font color='red' size='12'>TOTAL DEVOLUCIÓN</font></td>";
			$salida.="  <td width='100'><font color='black' size='12'>TOTAL</font></td>";
			$salida.="</tr>";
			$cierre=$sum-$efed;
			$salida .= "			<tr class=\"hc_table_submodulo_list_title\">";
			$salida.= "				<td align='center' width='100'><font color='black' size='12'>".FormatoValor($sum)."</font></td>";
			$salida.= "				<td align='center' width='150'><font color='red' size='12'>".FormatoValor($efed)."</font></td>";
			$salida.= "				<td align='center' width='100'><font color='black' size='12'>".FormatoValor($cierre)."</font></td>";
			$salida.= "			</tr>";
			$salida.="</table>";
		}
		//FIN TOTALES 
		
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>FIRMA DEL USUARIO  $s $s $s ------------------------------------------------------"." </b></font></td>";
  	$salida.= "			</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
  	$salida.= "			</tr>";
		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
 		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
   	//$pdf->RotatedText(60,80,'C O N T R O L  D E  C I E R R E S',35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

  	if($_SESSION['observa'])
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACION :".$_SESSION['observa'],0,'J',0);
			$pdf->WriteHTML($salida1);
		}

		
		 unset($_SESSION['observa']);
	//	unset($_SESSION['CAJA']['VECTOR_CIERRE']);
	//	unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		unset($_SESSION['TMP']['CONTROL_CIERRE']);//borrado de Temporales de cierre.
		return true;
	}

	//}
?>
