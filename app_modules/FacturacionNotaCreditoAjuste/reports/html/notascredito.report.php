<?php
	/**
	* $Id: notascredito.report.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
	include_once "./app_modules/FacturacionNotaCreditoAjuste/classes/NotasCredito.class.php";
	class notascredito_report 
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
	  function notascredito_report($datos=array())
	  {
			$this->datos=$datos;
			if(!$this->datos['prefijo_nota'])
			{
				$this->datos['prefijo_nota'] = SessionGetVar("PrefijoNotaCredito");
				$this->datos['numero_nota'] = SessionGetVar("NumeroNotaCredito");
			}
			
			$this->datos['id_s'] =	$_SESSION['NotasAjuste']['id'];
			$this->datos['razon_social'] =	$_SESSION['NotasAjuste']['rz_social'];
			$this->datos['tipo_tercero_s'] =	$_SESSION['NotasAjuste']['tipo_id_tercero'];
			
	    return true;
	  }
		
		function GetMembrete()
		{
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:10pt\"";
			$titulo .= "<b $est >".$this->datos['razon_social']."<br>";
			$titulo .= $this->datos['tipo_tercero_s']." ".$this->datos['id_s']."<br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nc = new NotasCredito();
			
			$Notas = $nc->ObtenerInformacionNotaCreditoCerrada($this->datos['prefijo_nota'],$this->datos['numero_nota'],$this->datos['empresa']);
			$paciente = $nc->ObtenerInformacionCuentas($Notas['prefijo_factura'],$Notas['factura_fiscal'],$this->datos['empresa']);
			$valores = $nc->ObtenerValorNotaDebito($Notas['prefijo_factura'],$Notas['factura_fiscal'],$this->datos['empresa']);
			$creditos = $nc->ObtenerValoresFactura($Notas['prefijo_factura'],$Notas['factura_fiscal'],$this->datos['empresa']);
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			
			$Salida .= "<table border=\"0\" width=\"100%\">\n";
			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td class=\"label\" width=\"12%\">Nota Credito:</td>\n";			
			$Salida .= "		<td class=\"label\" style=\"text-indent:10pt;text-align:left\">".$this->datos['prefijo_nota']." ".$this->datos['numero_nota']."</td>\n";			
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td class=\"label\" width=\"12%\">Fecha</td>\n";			
			$Salida .= "		<td class=\"label\" style=\"text-indent:10pt;text-align:left\">".$Notas['fecha']."</td>\n";			
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td class=\"label\" width=\"16%\">Cliente:</td>\n";			
			$Salida .= "		<td class=\"normal_10\" style=\"text-indent:10pt;text-align:left\">".$Notas['tipo_id_tercero']." ".$Notas['tercero_id']." - ".$Notas['nombre_tercero']."</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";

			if(!empty($paciente))
			{
				$Salida .= "	<table width=\"100%\" align=\"center\" >\n";		
				$Salida .= "		<tr class=\"label\">\n";
				$Salida .= "			<td width=\"16%\"><b>Nº Cuenta</b></td>\n";
				$Salida .= "			<td style=\"text-indent:10pt;text-align:justify\" width=\"%\"><b>Paciente</b></td>\n";
				$Salida .= "		</tr>\n";
				foreach($paciente as $key => $datos)
				{
					$Salida .= "		<tr class=\"normal_10\" height=\"21\">\n";
					$Salida .= "			<td >".$datos['numerodecuenta']."</td>\n";
					$Salida .= "			<td style=\"text-indent:10pt;text-align:justify\" >".$datos['tipo_id_paciente']." ".$datos['paciente_id']." - ".$datos['nombre']." ".$datos['apellido']."</td>\n";
					$Salida .= "		</tr>\n";
					
					$soat = $nc->ObtenerInformacionSoat($datos['ingreso']);
					if(!empty($soat))
					{
						$Salida .= "	<tr height=\"21\">\n";
						$Salida .= "		<td class=\"label\">Poliza Nº:</td>\n";
						$Salida .= "		<td class=\"label\" style=\"text-indent:10pt;text-align:justify\">".$soat['poliza']."</b></td>\n";				
						$Salida .= "	</tr>\n";
					}
				}				
				$Salida .= "		</table>\n";
			}
			
			$Salida .= "<table border=\"0\" width=\"100%\">\n";
			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td class=\"label\" width=\"8%\">Factura Nº:</td>\n";			
			$Salida .= "		<td class=\"normal_10\"  width=\"8%\">".$Notas['prefijo_factura']." ".$Notas['factura_fiscal']."</td>\n";
			$Salida .= "		<td class=\"label\" width=\"16%\" style=\"text-indent:10pt\">Fecha Factura:</td>\n";			
			$Salida .= "		<td class=\"normal_10\" style=\"text-indent:10pt\">".$Notas['fecha_factura']."</td>\n";
			$Salida .= "	</tr>\n";	
			$Salida .= "</table>\n";	
			$Salida .= "<table border=\"0\" width=\"100%\">\n";			
			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td colspan=\"2\" class=\"label\" width=\"16%\" >Total Factura:</td>\n";			
			$Salida .= "		<td colspan=\"2\" class=\"label\" width=\"8%\" style=\"text-indent:10pt\" align=\"right\">$".formatoValor($Notas['total_factura'])."</td>\n";
			$Salida .= "		<td></td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td colspan=\"2\" class=\"label\">Valor Nota Credito:</td>\n";			
			$Salida .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt\" align=\"right\">$".formatoValor($Notas['valor_nota'])."</td>\n";
			$Salida .= "		<td></td>\n";
			$Salida .= "	</tr>\n";
			
			if($creditos)
			{
				$credito = 0;
				foreach($creditos as $key => $valor)
					$credito += $valor['abono'];
					
				if(($credito - $Notas['valor_nota']) > 0)
				{
					$Salida .= "	<tr height=\"21\">\n";
					$Salida .= "		<td colspan=\"2\" class=\"label\">Otras Notas Credito:</td>\n";			
					$Salida .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt\" align=\"right\">$".formatoValor($credito-$Notas['valor_nota'])."</td>\n";
					$Salida .= "		<td></td>\n";
					$Salida .= "	</tr>\n";
				}
			}
			
			if($valores['abono'] > 0)
			{
				$Salida .= "	<tr height=\"21\">\n";
				$Salida .= "		<td colspan=\"2\" class=\"label\">Valor Notas Debito:</td>\n";			
				$Salida .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt\" align=\"right\">$".formatoValor($valores['abono'])."</td>\n";
				$Salida .= "		<td></td>\n";
				$Salida .= "	</tr>\n";
			}

			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td colspan=\"2\" class=\"label\">Saldo Factura:</td>\n";			
			$Salida .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt\" align=\"right\">$".formatoValor($Notas['saldo'])."</td>\n";
			$Salida .= "		<td></td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table>\n";	
			$Salida .= "<table border=\"0\" width=\"100%\">\n";			
			if($Notas['auditor'])
			{
				$Salida .= "	<tr height=\"21\">\n";
				$Salida .= "		<td colspan=\"2\" class=\"label\" width=\"16%\">Auditor(a):</td>\n";			
				$Salida .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt\">".$Notas['auditor']."</td>\n";
				$Salida .= "	</tr>\n";
			}
			
			if($Notas['observacion'])
			{
				$Salida .= "	<tr height=\"21\">\n";
				$Salida .= "		<td colspan=\"2\" class=\"label\" width=\"16%\">Observación:</td>\n";
				$Salida .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt;text-align:justify\">".$Notas['observacion']."</b></td>\n";				
				$Salida .= "	</tr>\n";
			}
			
			$Salida .= "</table>\n";


			$Salida .= "	<br>\n";
			$Salida .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
			$Salida .= "		<tr class=\"label\">\n";
			$Salida .= "			<td align=\"center\" width=\"45%\"><b>CONCEPTO</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"%\"><b>DEPARTAMENTO / TERCERO</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"8%\"><b>VALOR</b></td>\n";
			$Salida .= "		</tr>\n";
			
			$suma = 0;
			
			$Conceptos = $nc->ObtenerConceptosNotaCredito($this->datos['prefijo_nota'],$this->datos['numero_nota'],$this->datos['empresa']);
			foreach($Conceptos as $key => $Concep)
			{
				$Salida .= "		<tr class=\"normal_10\">\n";
				$Salida .= "			<td >".$Concep['descripcion']."</td>\n";
				$Salida .= "			<td >".$Concep['departamento']."</td>\n";
				$Salida .= "			<td align=\"right\">$".formatoValor($Concep['valor'])."</td>\n";
				$Salida .= "		</tr>\n";
			}			
			$Salida .= "	</table><br><br><br>\n";
			
			$Salida .= "	<table style=\"border-top:1px solid #000000\" width=\"30%\">\n";		
			$Salida .= "		<tr class=\"label\">";
			$Salida .= "			<td>".$Notas['nombre']."</td>\n";
			$Salida .= "		</tr>";
			$Salida .= "	</table>";
			
			$usuario = $nc->ObtenerInformacionUsuario(UserGetUID());
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