<?php

/**
 * $Id: facturaconceptos.inc.php,v 1.3 2005/11/23 20:32:32 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function CargosFacturaCon($cuenta)
  {
        list($dbconn) = GetDBconn();
        $querys = "select a.*, b.grupo_tipo_cargo, b.descripcion as desccargo,
										c.descripcion
                    from tarifarios_detalle as b,
										cuentas_detalle as a left join cuentas_codigos_agrupamiento as c on(a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
                    where a.numerodecuenta=$cuenta and a.cargo=b.cargo
										and a.tarifario_id=b.tarifario_id
                    and a.cargo!='DIMD' and a.cargo!='DCTOREDON'
										and a.cargo!='APROVREDON'
                    order by a.codigo_agrupamiento_id asc";
        $result = $dbconn->Execute($querys);
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
        return $var;
  }

  function GenerarFacturaConceptos($datos,$fact)
  {
      $_SESSION['REPORTES']['VARIABLE']='factura';
      $_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$datos;
      IncludeLib("tarifario");
	IncludeLib("funciones_admision");
	IncludeLib("funciones_facturacion");
      $Dir="cache/facturaconceptos".$datos[numerodecuenta].".pdf";
/*      if(empty($datos[cuentas]) OR  empty($fact))
      {
      require("classes/fpdf/html_class.php");
      include("classes/fpdf/conversor.php");
      }*/
      require_once("classes/fpdf/html_class.php");
      include_once("classes/fpdf/conversor.php");
      define('FPDF_FONTPATH','font/');
      $pdf=new PDF();
      $pdf->AddPage();
      //$pdf->SetFont('Arial','',7);
      //$pdf->Cell(0,2,'Pagina No '.$pdf->PageNo());
      //$html="".$pdf->image('images/logocliente.png',170,6,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
      //$usu=NombreUsuario();
			$usu=UserGetUID();
      $html.="<table border=0 width=100 align='center' border=0>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $html.="<tr><td width=660> CONCEPTO DE FACTURACION</td><td width=100 align=\"CENTER\">VALOR</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $var=CargosFacturaCon($datos[numerodecuenta]);
      $total=$descuentos=$pagado=0;
      $direc='';
      for($i=0; $i<sizeof($var);)
      {
						if(!empty($var[$i][codigo_agrupamiento_id]) AND empty($var[$i][consecutivo]))
						{
								$cant=$valor=0;
								$d=$i;
								while($var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
								{
										$cant+=$var[$d][cantidad];
										$valor+=$var[$d][valor_cargo];
										$d++;
								}
              $html.="<tr><td width=60 align='center'>&nbsp;</td><td width=550>".substr($var[$i][descripcion],0,90)."</td><td width=50 align=CENTER>".FormatoValor($cant)."</td><td width=80 align=\"RIGHT\">".FormatoValor($valor)."</td></tr>";
								$i=$d;
						}
						elseif(!empty($var[$i][codigo_agrupamiento_id]) AND !empty($var[$i][consecutivo]))
						{
								$cant=$valor=0;
								$d=$i;
								while($var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
								{
										$cant+=$var[$d][cantidad];
										$valor+=$var[$d][valor_cargo];
										$d++;
								}
              $html.="<tr><td width=60 align='center'>&nbsp;</td><td width=550>".substr($var[$i][descripcion],0,90)."</td><td width=50 align=CENTER>".FormatoValor($cant)."</td><td width=80 align=\"RIGHT\">".FormatoValor($valor)."</td></tr>";
								$i=$d;
						}
          elseif(empty($var[$i][codigo_agrupamiento_id]))
          {
              $direc.="<tr><td width=60 align='center'>".$var[$i][cargo]."</td><td width=550>".substr($var[$i][desccargo],0,90)."</td><td width=50 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=80 align=\"RIGHT\">".FormatoValor($var[$i][precio])."</td></tr>";
							$i++;
          }
      }
     	$html.=$direc;
			$descuentopac=$datos[valor_descuento_paciente];
			$descuentocliente=$datos[valor_descuento_empresa];
			$pagado=$datos[abono_efectivo]+$datos[abono_cheque]+$datos[abono_tarjetas]+$datos[abono_chequespf];
			$valpagarpaciente=$datos[valor_total_paciente];
			$totalpac=$datos[valor_total_paciente]-$pagado;
			$valpagarcliente=$datos[valor_total_empresa];
			$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL FACTURADO</td><td width=80 align=RIGHT>".FormatoValor($datos[total_cuenta])."</td></tr>";
			//sw_tipo 1->cliente 0->paciente 2->particular
			if($datos[sw_tipo]==0 OR $datos[sw_tipo]==2)
			{
					$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL A PAGAR</td><td width=80 align=RIGHT>".FormatoValor($valpagarpaciente)."</td></tr>";
					$html.="<tr><td width=530>&nbsp;</td><td width=130>DESCUENTOS</td><td width=80 align=RIGHT>$descuentopac</td></tr>";
					$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL PAGADO</td><td width=80 align=RIGHT>$pagado</td></tr>";
					//$total=str_replace(".","",FormatoValor($valpagarpaciente));
					$total=FormatoValor($totalpac);
					$totall=str_replace(".","",$total);
					$html.="<tr><td width=530>SON :"."  ".convertir_a_letras($totall)."</td><td width=130>VALOR A PAGAR</td><td width=80 align=RIGHT>".$total."</td></tr>";
			}
			elseif($datos[sw_tipo]==1)
			{
					$html.="<tr><td width=530>&nbsp;</td><td width=130>DESCUENTOS</td><td width=80 align=RIGHT>$descuentocliente</td></tr>";
					//$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL PAGADO</td><td width=80 align=RIGHT>$pagado</td></tr>";
					//$totalpagar=$total-$descuentos-$pagar;
					$total=FormatoValor($valpagarcliente);
					$totall=str_replace(".","",$total);
					$html.="<tr><td width=530>SON :"."  ".convertir_a_letras($totall)."</td><td width=130>VALOR A PAGAR</td><td width=80 align=RIGHT><b>".$total."</b></td></tr>";
			}
			$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			$html.="<tr><td width=760>&nbsp;</td></tr>";
			$html.="<tr><td width=170>&nbsp;</td><td width=180>----------------------------------------------------</td><td width=100>&nbsp;</td><td width=310>----------------------------------------------------</td></tr>";
			$html.="<tr><td width=200>&nbsp;</td><td width=130>FIRMA PACIENTE</td><td width=150>&nbsp;</td><td width=280>ELABORADO POR  ".$usu[usuario]."  C = ".$datos[numerodecuenta]."</td></tr>";
			$html.="</table>";
			$pdf->WriteHTML($html);
			//$pdf->SetLineWidth(0.5);
			//$pdf->RoundedRect(7, 7, 196, 284, 3.5, '');
			$pdf->Output($Dir,'F');
			return true;
  }

?>
