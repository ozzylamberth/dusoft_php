<?php

/**
 * $Id: envios.inc.php,v 1.2 2005/06/07 18:40:57 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

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

	/*function NombreDpto($dpto)
	{
			list($dbconn) = GetDBconn();
			$query = "select descripcion from departamentos
								where departamento='$dpto'";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			$result->Close();
			return $result->fields[0];
	}*/


	function GenerarEnvio($arr)
	{
			$_SESSION['REPORTES']['VARIABLE']='envios';
			$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$arr;
			IncludeLib("tarifario");
			$Dir="cache/envios.pdf";
			require("classes/fpdf/html_class.php");
			include("classes/fpdf/conversor.php");
			define('FPDF_FONTPATH','font/');
			$pdf=new PDF();
			$pdf->AddPage();
			$html.="<table border=0 WIDTH=100 align=\"CENTER\">";
			$total=$k=0;
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
													$html .= "			<tr>";
													$html .= "				<td WIDTH=110 align=\"CENTER\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>";
													$html .= "				<td WIDTH=100 align=\"CENTER\">".FormatoValor($arr[$i][total_factura])."</td>";
													$html .= "				<td WIDTH=130>&nbsp;</td>";
													$html .= "				<td WIDTH=215>AGRUPADA</td>";
													$html .= "				<td WIDTH=205 align=\"CENTER\">".$arr[$i][plan_descripcion]."</td>";
													$html .= "			</tr>";
													$x++;
											}
											$total+=$arr[$d][total_factura];
											$d++;
									}
									$i=$d;
							}
							else
							{	//no es agrupada
									$html .= "			<tr>";
									$html .= "				<td WIDTH=110 align=\"CENTER\">".$arr[$i][prefijo]." ".$arr[$i][factura_fiscal]."</td>";
									$html .= "				<td WIDTH=100 align=\"CENTER\">".FormatoValor($arr[$i][total_factura])."</td>";
									$html .= "				<td WIDTH=130>".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
									$html .= "				<td WIDTH=215>".$arr[$i][nombre]."</td>";
									$html .= "				<td WIDTH=205 align=\"CENTER\">".$arr[$i][plan_descripcion]."</td>";
									$html .= "			</tr>";
									$total+=$arr[$i][total_factura];
									$i++;
							}
							$k++;
			}
			$html .= "	</table>";
			$html .= "		 <br><table WIDTH=\"40\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">";
			$html .= "			<tr>";
			$html .= "				<td WIDTH=\"30\">&nbsp;</td>";
			$html .= "				<td WIDTH=\"130\">TOTAL DOCUMENTOS: </td>";
			$html .= "				<td>$k</td>";
			$html .= "			</tr>";
			$html .= "			<tr>";
			$html .= "				<td WIDTH=\"30\">&nbsp;</td>";
			$html .= "				<td WIDTH=\"130\">TOTAL ENVIO ($): </td>";
			$html .= "				<td>".FormatoValor($total)."</td>";
			$html .= "			</tr>";
			$html.="<tr><td WIDTH=\"30\">&nbsp;</td><td WIDTH=730>SON :"."  ".convertir_a_letras($total)."</td></tr>";
			//$html.="<tr><td WIDTH=\"30\">&nbsp;</td><td WIDTH=730>NOTA: Al cancelar hacer referencia al No. de la factura por paciente , o al No. del envio.</td></tr>";
			$html .= "	</table><BR>";
			$pdf->WriteHTML($html);
			//$pdf->SetLineWIDTH(0.5);
			//$pdf->RoundedRect(7, 7, 196, 284, 3.5, '');
			$pdf->Output($Dir,'F');
			return true;
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

?>
