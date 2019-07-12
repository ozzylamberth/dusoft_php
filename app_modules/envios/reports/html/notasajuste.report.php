<?php

	/**************************************************************************************
	* $Id: notasajuste.report.php,v 1.6 2007/08/09 19:44:11 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	IncludeClass('app_Cartera_Notas','','app','Cartera');
	class notasajuste_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
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
		
		//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	  function notasajuste_report($datos=array())
	  { 
			$this->datos = $datos;
	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b $estilo>NOTA DE AJUSTE Nº ".$this->datos['prefijo_nota']." ".$this->datos['nota_credito_ajuste']."</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$c = $d = 0;
			
			$nts = new app_Cartera_Notas();
			$this->Notas = $nts->ObtenerInformacionNota($this->datos,$this->datos['empresa']);
			$Facturas = $nts->ObtenerFacturasCruzadasNA($this->datos,$this->datos['empresa']);
			$ConceptosV = $nts->ObtenerValorConceptosNA($this->datos,$this->datos['empresa']);

			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px; text-indent:6pt\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px; text-align:center\""; 
			
			$Salida .= "<table border=\"0\" width=\"90%\" align=\"center\">\n";
			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td $estilo2 width=\"15%\"><b>".$this->Notas['tipo_id_tercero']." ".$this->Notas['tercero_id']."</b></td>\n";			
			$Salida .= "		<td $estilo2 align=\"left\"><b>".$this->Notas['nombre_tercero']."</b></td>\n";
			$Salida .= "	</tr>\n";
			if($this->Notas['observacion'])
			{
				$Salida .= "	<tr height=\"21\">\n";
				$Salida .= "		<td $estilo2><b>OBSERVACIÓN:</b></td>\n";
				$Salida .= "		<td $estilo2 align=\"justify\">".$this->Notas['observacion']."</b></td>\n";				
				$Salida .= "	</tr>\n";
			}
			$Salida .= "</table>\n";
			$Salida .= "<table width=\"98%\" border=\"1\" bordercolor=\"#000000\"  align=\"center\" cellpading=\"0\" cellspacing=\"0\">\n";
			$Salida .= "	<tr $estilo3 height=\"16\">\n";
			$Salida .= "		<td width=\"45%\"><b>CONCEPTOS</b></td>\n";
			$Salida .= "		<td width=\"35%\"><b>DEPARTAMENTO/TERCERO</b></td>\n";
			$Salida .= "		<td width=\"10%\"><b>DEBITO</b></td>\n";
			$Salida .= "		<td width=\"10%\"><b>CREDITO</b></td>\n";
			$Salida .= "	</tr>\n";				
			
			if(sizeof($ConceptosV) > 0)
			{				
				for($i=0; $i<sizeof($ConceptosV); $i++)
				{
					$Celdas = $ConceptosV[$i];
					$credito = $debito = "0";
					switch($Celdas['naturaleza'])
					{
						case 'C':	$credito = formatoValor($Celdas['valor']); $c += $Celdas['valor']; break;
						case 'D':	$debito = formatoValor($Celdas['valor']);  $d += $Celdas['valor'];	break;
					}
					
					$Salida .= "	<tr $estilo2 height=\"19\">\n";
					$Salida .= "		<td ><b>".$Celdas['descripcion']."</b></td>\n";
					$Salida .= "		<td ><b>".$Celdas['departamento']."</b></td>\n";
					$Salida .= "		<td align=\"right\"><b>".$debito."</b></td>\n";
					$Salida .= "		<td align=\"right\"><b>".$credito."</b></td>\n";
					$Salida .= "	</tr>\n";
				}
			}
			
			$Salida .= "	<tr $estilo2 height=\"19\">\n";
			$Salida .= "		<td colspan=\"2\"><b>TOTAL ABONO FACTURAS</b></td>\n";
			$Salida .= "		<td align=\"right\"><b>0</b></td>\n";
			$Salida .= "		<td align=\"right\"><b>".formatoValor($Facturas[0]['total'])."</b></td>\n";
			$Salida .= "	</tr>\n";
			
			$c += $Facturas[0]['total'];
			
			$Salida .= "	<tr $estilo2 height=\"19\">\n";
			$Salida .= "		<td colspan = \"2\"><b>TOTAL</b></td>\n";
			$Salida .= "		<td align=\"right\"><b>".formatoValor($d)."</b></td>\n";
			$Salida .= "		<td align=\"right\"><b>".formatoValor($c)."</b></td>\n";
			$Salida .= "	</tr>\n";
			
			$total = $c;
			if($c == 0) $total = $d;
			
			$Salida .= "	<tr $estilo2 height=\"19\">\n";
			$Salida .= "		<td colspan=\"2\"><b>VALOR NOTA</b></td>\n";
			$Salida .= "		<td colspan=\"2\" align=\"right\"><b>".formatoValor($total)."</b></td>\n";
			$Salida .= "	</tr>\n";

			$Salida .= "</table><br>\n";
			
			if(sizeof($Facturas) > 0)
			{
				$total_factura = 0;
				
				$Salida .= "<table width=\"98%\" align=\"center\" border=\"1\" bordercolor=\"#000000\" cellpading=\"0\" cellspacing=\"0\" >\n";
				$Salida .= "	<tr $estilo3 height=\"21\">\n";
				$Salida .= "		<td width=\"20%\"><b>FACTURA</b></td>\n";
				$Salida .= "		<td width=\"20%\"><b>FECHA</b></td>\n";
				$Salida .= "		<td width=\"20%\"><b>TOTAL</b></td>\n";
				$Salida .= "		<td width=\"20%\"><b>SALDO</b></td>\n";
				$Salida .= "		<td width=\"20%\"><b>ABONO</b></td>\n";
				$Salida .= "	</tr>";
				
				for($i=0; $i<sizeof($Facturas); $i++)
				{						
					$saldo = 0;
					if($Facturas[$i]['saldo'] < 0 ) $Facturas[$i]['saldo'] = 0;
					
					$Salida .= "	<tr $estilo height=\"18\">\n";					
					$Salida .= "		<td aling=\"left\"  >".$Facturas[$i]['prefijo']." ".$Facturas[$i]['factura_fiscal']."</td>\n";
					$Salida .= "		<td align=\"center\">".$Facturas[$i]['registro']."</td>\n";
					$Salida .= "		<td align=\"right\" >".formatoValor($Facturas[$i]['total_factura'])."&nbsp;</td>\n";
					$Salida .= "		<td align=\"right\" >".formatoValor($saldo)."&nbsp;</td>\n";
					$Salida .= "		<td align=\"right\" >".formatoValor($Facturas[$i]['abono'])."&nbsp;</td>\n";
					$Salida .= "	</tr>";
				}
								
				$Salida .= "</table><br>\n";
			}
			
			$usuario = $nts->ObtenerUsuarioNombre(UserGetUID());
			$Salida .= "	<br><table border='0' width=\"100%\">\n";
			$Salida .= "		<tr>\n";
			$Salida .= "			<td align=\"justify\" width=\"50%\">\n";
			$Salida .= "				<font size='1' face='arial'>\n";
			$Salida .= "					Imprimió:&nbsp;".$usuario['nombre']."\n";
			$Salida .= "				</font>\n";
			$Salida .= "			</td>\n";
			$Salida .= "			<td align=\"right\" width=\"50%\">\n";
			$Salida .= "				<font size='1' face='arial'>\n";
			$Salida .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
			$Salida .= "				</font>\n";
			$Salida .= "			</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "	</table>\n";
				
	    return $Salida;
		}
	}

?>
