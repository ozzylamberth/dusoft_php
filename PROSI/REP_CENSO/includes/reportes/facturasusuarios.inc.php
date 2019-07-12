<?php

/**
 * $Id: facturasusuarios.inc.php,v 1.2 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
	function GenerarFacturasUsuarios($arr)
	{
			$_SESSION['REPORTES']['VARIABLE']='facturas_usuario';
			$_SESSION['REPORTES']['FACUSUARIOS']['ARREGLO']=$arr;
			IncludeLib("tarifario");
			$Dir="cache/facturasusuarios.pdf";
			require("classes/fpdf/html_class.php");
			//include("classes/fpdf/conversor.php");
			define('FPDF_FONTPATH','font/');
			$pdf2=new PDF();
			$pdf2->AddPage();
			$pdf2->SetFont('Arial','',7);
			IncludeLib("tarifario");
			$html.="<BR><table border=\"0\" width=\"200\" align=\"CENTER\"  class=\"modulo_list_claro\">";
			$html.="<tr><td width=760 align=\"CENTER\">USUARIO: ".$arr[0][usuario]." - ".$arr[0][nombreu]."  -  Fecha Facturas: ".$arr[0][fecha]."</td></tr>";
			$total=0;
			$cuentas=$cant=0;
			$totalfact=$totalcuenta=0;
			for($i=0; $i<sizeof($arr); )
			{
					$html.="<tr class=\"modulo_table_title\">";
					$html.="<td><B>".$arr[$i][nombre_tercero]."</B></td>";
					$html.="</tr>";
					$d=$i;
					while($arr[$i][tipo_id_tercero]==$arr[$d][tipo_id_tercero]
					AND $arr[$i][tercero_id]==$arr[$d][tercero_id])
					{
							$html.="<tr>";
							$html.="<td class=\"normal_10n\">".$arr[$d][plan_descripcion]."</td>";
							$html.="</tr>";
							$html.="<tr>";
							$html.="<td>";
							//tabla con el cabezote del listado
							$html.="<table border=\"0\" width=\"100\" align=\"CENTER\">";
							$html.="<tr align=\"CENTER\" class=\"modulo_table_list_title\">";
							$html.="<td width=\"120\" align=\"CENTER\">FACTURA</td>";
							$html.="<td width=\"125\" align=\"CENTER\">IDENTIFICACION</td>";
							$html.="<td width=\"110\" align=\"CENTER\">PACIENTE</td>";
							$html.="<td width=\"90\" align=\"CENTER\">CUENTA</td>";
							$html.="<td width=\"85\" align=\"CENTER\">BONOS</td>";
  						$html.="<td width=\"85\">VAL PACIE.</td>";
          		$html.="<td width=\"85\">VAL CLIEN.</td>";
							$html.="<td width=\"85\" align=\"CENTER\">TOTAL</td>";
							$html.="</tr>";
							$html.="<tr><td width=770>---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
							$j=$d;
							$sub=$total=$valpac=$valcli=$bono0;
							while($arr[$d][plan_id]==$arr[$j][plan_id])
							{
									$html.="<tr class=\"modulo_list_oscuro\">";
									if(!empty($arr[$j][factura_fiscal]))
									{
											if($arr[$j][factura_fiscal]==$arr[$j-1][factura_fiscal]
													AND $arr[$j][prefijo]==$arr[$j-1][prefijo])
											{  $html.="<td align=\"CENTER\" width=\"120\">&nbsp;</td>";  }
											else
											{
													$html.="<td align=\"CENTER\" width=\"120\">".$arr[$j][prefijo]." ".$arr[$j][factura_fiscal]."</td>";
													$cant++;
											}
											$totalfact+=str_replace(".","",FormatoValor($arr[$j][valor_total_empresa]));
											$valpagarpaciente=$arr[$j][valor_total_paciente];
											$valpagarcliente=$arr[$j][valor_total_empresa];
											$sub+=$arr[$j][valor_nocubierto];
											$total+=str_replace(".","",FormatoValor($arr[$j][total_cuenta]));
											$valpac+=str_replace(".","",FormatoValor($valpagarpaciente));
											$valcli+=str_replace(".","",FormatoValor($valpagarcliente));
											$bono+=$arr[$j][total_bonos];
									}
									else
									{
												$html.="<td align=\"CENTER\" width=\"120\">--cerrada--</td>";
												$cuentas++;
												$totalcuenta+=$arr[$j][total_cuenta];
									}
									$html.="<td width=\"125\">".$arr[$j][tipo_id_paciente]." ".$arr[$j][paciente_id]."</td>";
									$html.="<td width=\"110\">".$arr[$j][nombre]."</td>";
									$html.="<td align=\"CENTER\" width=\"90\">".$arr[$j][numerodecuenta]."</td>";
									$html.="<td align=\"RIGHT\" width=\"85\">".FormatoValor($arr[$j][total_bonos])."</td>";
									$moderadora=$arr[$j][valor_cuota_moderadora];
									$valpagarpaciente=$arr[$j][valor_total_paciente];
									$valpagarcliente=$arr[$j][valor_total_empresa];
									$html.="<td align=\"RIGHT\" width=\"85\">".FormatoValor($valpagarpaciente)."</td>";
									$html.="<td align=\"RIGHT\" width=\"85\">".FormatoValor($valpagarcliente)."</td>";
									$html.="<td align=\"RIGHT\" width=\"85\">".FormatoValor($arr[$j][total_cuenta])."</td>";
									$html.="</tr>";
									$j++;
							}
							$html.="<tr class=\"modulo_list_oscuro\">";
							$html.="<td colspan=\"3\" width=\"325\">&nbsp;</td>";
							$html.="<td align=\"CENTER\" class=\"modulo_table_list_title\" width=\"120\">SUB TOTAL ---></td>";
							$html.="<td class=\"normal_10n\" align=\"RIGHT\" width=\"85\">".FormatoValor($bono)."</td>";
							$html.="<td class=\"normal_10n\" align=\"RIGHT\" width=\"85\">".FormatoValor($valpac)."</td>";
							$html.="<td class=\"normal_10n\" align=\"RIGHT\" width=\"85\">".FormatoValor($valcli)."</td>";
							$html.="<td class=\"normal_10n\" align=\"RIGHT\" width=\"85\">".FormatoValor($total)."</td>";
							$html.="</tr>";
							$html.="</table>";
							$html.="</td>";
							$html.="</tr>";
							$d=$j;
					}
					$i=$d;
			}
			$html.="<tr>";
			$html.="<td class=\"normal_10n\">VALOR TOTAL ($):  ".FormatoValor($total)."";
			$html.="</td>";
			$html.="</tr>";
			if(!empty($cant))
			{
					$html.="<tr>";
					$html.="<td class=\"normal_10n\">VALOR TOTAL FACTURAS ($):  ".FormatoValor($totalfact)."";
					$html.="</td>";
					$html.="</tr>";
					$html.="<tr>";
					$html.="<td class=\"normal_10n\">TOTAL FACTURAS :  $cant";
					$html.="</td>";
					$html.="</tr>";
			}
			if(!empty($cuentas))
			{
					$html.="<tr>";
					$html.="<td class=\"normal_10n\">VALOR TOTAL CUENTAS ($):  ".FormatoValor($totalcuenta)."";
					$html.="</td>";
					$html.="</tr>";
					$html.="<tr>";
					$html.="<td class=\"normal_10n\">TOTAL CUENTAS  :  $cuentas";
					$html.="</td>";
					$html.="</tr>";
			}
			$html.="</table>";
			//$pdf2->SetFont('Arial','B',18);
			//$pdf2->SetTextColor(203,203,203);
			//$pdf2->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
			//$pdf2->SetFont('Arial','',8);
			$pdf2->WriteHTML($html);
			//$pdf2->SetLineWidth(0.5);
			//$pdf2->RoundedRect(7, 7, 196, 284, 3.5, '');
			$pdf2->Output($Dir,'F');
			return true;
	}

?>
