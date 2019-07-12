<?php

/**
 * $Id: resultados.inc.php,v 1.2 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * FUCNION Q ESTRAE INFORMACION ACERCA DE LA CUENTA Y EL TIPO DE PAGO QUE SE EFECTUO
 */

	function Detalles($recibo,$prefijo)
	{
			list($dbconn) = GetDBconn();

      if($_SESSION['CAJA']['TIPOCUENTA']=='01')
			{
				$query = "SELECT numerodecuenta as cuenta
								FROM  rc_detalle_hosp WHERE recibo_caja=$recibo
								AND prefijo='$prefijo'";
      }

			if($_SESSION['CAJA']['TIPOCUENTA']=='02')
			{
			$query = "SELECT cuenta_pv as cuenta
								FROM rc_detalle_pto_vta WHERE recibo_caja=$recibo
								AND prefijo='$prefijo'";
			}
  		$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al buscar los abonos de la cuenta";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var= $result->GetRowAssoc($ToUpper = false);
			return $var;
	}



 			function GenerarReciboCaja($datos)
			{
		//	print_r($datos);EXIT;
					IncludeLib("tarifario");
					include("classes/fpdf/conversor.php");
					$Dir="cache/Recibo.pdf";
					require("classes/fpdf/html_class.php");
					define('FPDF_FONTPATH','font/');
     			$pdf=new PDF('P','mm','recibo');
					$pdf->AddPage();//4,4,4,4
					//$pdf->image('classes/fpdf/logo_grande.jpg',150,7,15);
					$html="".$pdf->image('images/logocliente.png',10,6,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
					$html ="<TABLE BORDER='0' WIDTH='1520'>";
					$html.="<TR>";
					$html.="<TD ALIGN='CENTER' WIDTH='760'><br><br>";
					$html.="<FONT SIZE='26'> </FONT>";
					$html.="</TD>";
					$html.="</TR>";
					$total=0;

					if($_SESSION['CAJA']['TIPOCUENTA']=='01')
					{
							$dat=Detalles($datos['recibo_caja'],$datos['prefijo']);
							$info='Abonos a la cuenta'." ".$dat['cuenta']." "."de la cuenta Hospitalaria";
					}

					if($_SESSION['CAJA']['TIPOCUENTA']=='02')
					{
							$dat=Detalles($datos['recibo_caja'],$datos['prefijo']);
							$info='Abonos a la cuenta'." ".$dat['cuenta']." "."de punto de venta";
					}

					if($_SESSION['CAJA']['TIPOCUENTA']=='03')
					{ $info='Abonos de conceptos';}

					if($_SESSION['CAJA']['TIPOCUENTA']>'03')
					{ $info='Abonos ambulatorios';}

					$nom.=$datos['nombre'];
					$html.="<TR><font color='#000000'><TD HEIGHT=30 WIDTH='380'><font size='24'><b>RECIBO DE CAJA</b></font></TD><TD WIDTH='380' HEIGHT=30>".$datos['recibo_caja']." "."-"." ".$datos['prefijo']."</TD></TR>";
					$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='380'>EMPRESA:</TD><TD WIDTH='380' HEIGHT=22>".$datos['razon_social']."</TD></TR>";
					$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='380'>CENTRO DE UTILIDAD:</TD><TD WIDTH='380' HEIGHT=22>".$datos['descripcion']."</TD></TR>";

					if($_SESSION['CAJA']['TIPOCUENTA']=='01' || $_SESSION['CAJA']['TIPOCUENTA']=='02')
					{
						$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='380'>PLAN:</TD><TD WIDTH='380' HEIGHT=22>".$datos['plan_descripcion']."</TD></TR>";
					}
					if($_SESSION['CAJA']['TIPOCUENTA']>='03')
					{
						$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='380'>RESPONSABLE:</TD><TD WIDTH='380' HEIGHT=22>[".$datos['tercero_id']."]  ".$datos['nombre_tercero']."</TD></TR>";
					}
					$fe=explode(" ",$datos['fecha_ingcaja']);
					$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='380'>FECHA :</TD><TD WIDTH='380' HEIGHT=22>".$fe[0]."</TD></TR>";

					if($_SESSION['CAJA']['TIPOCUENTA']!='03')
					{
						$html.="<TR><TD WIDTH='380' HEIGHT='22'>PACIENTE:</TD><TD WIDTH='380' HEIGHT='22'><b>".$nom."   CON ".$datos['id']."</b></TD></TR>";
					}

					$html.="<TR><TD WIDTH='380' HEIGHT='22'>CONCEPTO:</TD><TD WIDTH='380' HEIGHT='22'><b></b></TD>$info</TR>";
					$html.="</TABLE>";

					$html.="<TABLE WIDTH='1520' ALIGN='CENTER' border='1'>";
					$html.="<TR>";
					$html.="<TD WIDTH='200' HEIGHT='22'>TOTAL EFECTIVO:</TD><TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".FormatoValor($datos['total_efectivo'])."</TD>";
					$html.="</TR>";
					$html.="<TR>";
					$html.="<TD WIDTH='200' HEIGHT='22'>TOTAL CHEQUES:</TD><TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".FormatoValor($datos['total_cheques'])."</TD>";
					$html.="</TR>";
					$html.="<TR>";
					$html.="<TD WIDTH='200' HEIGHT='22'>TOTAL TAJETAS:</TD><TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".FormatoValor($datos['total_tarjetas'])."</TD>";
					$html.="</TR>";
					$html.="<TR>";
					$html.="<TD WIDTH='200' HEIGHT='22'>TOTAL BONOS:</TD><TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".FormatoValor($datos['total_bonos'])."</TD>";
					$html.="</TR>";
					$html.="<TR>";
					$totalpagar=$datos['total_abono'];
					$html.="<TD WIDTH='200' HEIGHT='22'>TOTAL:</TD><TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'><b>".FormatoValor($totalpagar)."</b></TD>";
					$html.="</TR>";
					$html.="</TABLE>";
					$html.="<TABLE WIDTH='1520' ALIGN='RIGHT' border='0'>";
					/*$html.="<TR>";
					$html.="<TD ALIGN='RIGHT' WIDTH='760'>";
					//$total=str_replace(".","",FormatoValor($totalpagar));
					//	echo $total;exit;
					$html.="<FONT SIZE='6'>".convertir_a_letras(25000)."</FONT>";
					$html.="</TD>";
					$html.="</TR>";
					*/$html.="<TR>";
					$html.="<TD WIDTH='150' HEIGHT='22'><b>NUMERO DE CAJA :"." ".$datos['caja_id']." </b></TD><TD WIDTH='150' HEIGHT='22'><b> USUARIO :"." ".$datos['usuario']."</b></TD>";
					$html.="</TR>";
					$html.="<TR>";
					$html.="<TD ALIGN='RIGHT' WIDTH='760'>";
					$html.="<FONT SIZE='6'>"._SIIS_APLICATION_TITLE."</FONT>";
					$html.="</TD>";
					$html.="</TR>";
					$html.="</TABLE>";
					$pdf->SetFont('Arial','B',18);
					$pdf->SetTextColor(203,203,203);
					$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
					$pdf->SetFont('Arial','',8);
					$pdf->WriteHTML($html);
					$pdf->SetLineWidth(0.7);
					$pdf->RoundedRect(7, 5, 202, 76, 3.5, '');
					$pdf->Output($Dir,'F');
					return true;
			}

	//}
?>
