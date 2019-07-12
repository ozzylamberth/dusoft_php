<?php

/**
 * $Id: facturasusuarios.inc.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $
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
			$html.="<tr><td width=760 align=\"CENTER\">USUARIO: ".$arr[0][usuario]." - ".$arr[0][nombre]."</td></tr>";

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
							$html.="<td width=\"130\" align=\"CENTER\">IDENTIFICACION</td>";
							$html.="<td width=\"200\" align=\"CENTER\">PACIENTE</td>";
							$html.="<td width=\"120\" align=\"CENTER\">CUENTA</td>";
							$html.="<td width=\"90\" align=\"CENTER\">BONOS</td>";
							$html.="<td width=\"100\" align=\"CENTER\">VALOR</td>";
							$html.="</tr>";
							$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
							$j=$d;
							$sub=0;
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
											$totalfact+=$arr[$j][valor_nocubierto];
									}
									else
									{
												$html.="<td align=\"CENTER\" width=\"120\">---</td>";
												$cuentas++;
												$totalcuenta+=$arr[$j][valor_nocubierto];
									}
									/*if($arr[$j][factura_fiscal]==$arr[$j-1][factura_fiscal]
											AND $arr[$j][prefijo]==$arr[$j-1][prefijo])
									{  $html.="<td align=\"CENTER\" width=\"120\">&nbsp;</td>";  }
									else
									{
											$html.="<td align=\"CENTER\" width=\"120\">".$arr[$j][prefijo]." ".$arr[$j][factura_fiscal]."</td>";
											$cant++;
									}*/
									$html.="<td width=\"130\">".$arr[$j][tipo_id_paciente]." ".$arr[$j][paciente_id]."</td>";
									$html.="<td width=\"200\">".$arr[$j][nombre]."</td>";
									$html.="<td align=\"CENTER\" width=\"120\">".$arr[$j][numerodecuenta]."</td>";
									$html.="<td align=\"RIGHT\" width=\"90\">".FormatoValor($arr[$j][total_bonos])."</td>";
									$html.="<td align=\"RIGHT\" width=\"100\">".FormatoValor($arr[$j][valor_nocubierto])."</td>";
									$html.="</tr>";
									$sub+=$arr[$j][valor_nocubierto];
									$total+=$arr[$j][valor_nocubierto];
									$bono+=$arr[$j][total_bonos];
									$j++;
							}
							$html.="<tr class=\"modulo_list_oscuro\">";
							$html.="<td colspan=\"3\" width=\"450\">&nbsp;</td>";
							$html.="<td align=\"CENTER\" class=\"modulo_table_list_title\" width=\"120\">SUB TOTAL ---></td>";
							$html.="<td class=\"normal_10n\" align=\"RIGHT\" width=\"90\">".FormatoValor($bono)."</td>";
							$html.="<td class=\"normal_10n\" align=\"RIGHT\" width=\"100\">".FormatoValor($sub)."</td>";
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
