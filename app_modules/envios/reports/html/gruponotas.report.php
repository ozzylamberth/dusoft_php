<?php
	/**************************************************************************************
	* $Id: notacredito.report.php,v 1.5 2007/02/07 18:52:53 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	**************************************************************************************/
	IncludeClass('app_Cartera_Notas','','app','Cartera');
	class gruponotas_report 
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
		function gruponotas_report($datos=array())
		{
			$this->datos=$datos;
			return true;
		}
		
		function GetMembrete()
		{
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo = "<b $estilo>";
			switch($this->datos['tipo_nota'])
			{
				case 'NA':	$titulo .= "NOTAS DE AJUSTE"; break;
				case 'NC':	$titulo .= "NOTAS CREDITO"; break;
				case 'ND':	$titulo .= "NOTAS DEBITO"; break;
				case 'NG':	$titulo .= "NOTAS CREDITO GLOSAS"; break;
			}
			$titulo .= "<br>DE ".$this->datos['fecha_inicio']." A ".$this->datos['fecha_fin']." ";
			$titulo .= "</b>";
			$Membrete = array(
								'file'=>false,
							  'datos_membrete'=>array(
										'titulo'=>$titulo,
										'subtitulo'=>'',
										'logo'=>'logocliente.png',
										'align'=>'left'));
			return $Membrete;
		}
	  //FUNCION CrearReporte()
		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
		{
			$nts = new app_Cartera_Notas();
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px; text-indent:7pt\"";
			$notas = $nts->ObtenerNotasDeAjuste($this->datos,$this->datos['empresa'],$contar = "0");

			$html .= "	<table border=\"0\" width=\"100%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<fieldset class=\"label\"><legend class=\"label\">NOTAS CREADAS:</legend>\n";
			$html .= "					<table width=\"100%\" border=\"1\" bordercolor=\"#000000\"  align=\"center\" cellpading=\"0\" cellspacing=\"0\">\n";
			$html .= "						<tr height=\"21\" class=\"label\" $estilo align=\"center\">\n";
			$html .= "							<td width=\"10%\" >Nº NOTA</td>\n";
			$html .= "							<td width=\"10%\">FACTURA</td>\n";
			$html .= "							<td width=\"10%\">VALOR</td>\n";
			$html .= "							<td width=\"10%\">REGISTRO</td>\n";
			$html .= "							<td width=\"%\"  >RESPONSABLE</td>\n";
			$html .= "						</tr>\n";
			
			$valor = 0;
			foreach($notas as $key => $detalle1 )
			{
				foreach ($detalle1 as $keyI => $detalle)
				{				
					$html .= "						<tr $estilo>\n";
					$html .= "							<td align=\"left\" class=\"label\">".$detalle['prefijo_nota']." ".$detalle['nota_credito_ajuste']."</td>\n";
					if($detalle['prefijo'])
						$html .= "							<td align=\"left\" class=\"label\">".$detalle['prefijo']." ".$detalle['factura_fiscal']."</td>\n";
					else
					{
						$facturas = $nts->ObtenerFacturasCruzadasNA($detalle,$this->datos['empresa']);
						$html .= "							<td align=\"left\" class=\"label\">\n";
						foreach($facturas as $ky => $dtl)
							$html .= "							".$dtl['prefijo']." ".$dtl['factura_fiscal']."<br>";
						$html .= "							</td>\n";
					}
					$html .= "							<td align=\"right\" class=\"label\">$".FormatoValor($detalle['abono'])."</td>\n";
					$html .= "							<td align=\"center\">".$detalle['fecha_registro']."</td>\n";
					$html .= "							<td align=\"left\"  >".$detalle['nombre']."</td>\n";
					$html .= "						</tr>\n";
					
					$valor += $detalle['abono'];
				}
			}
			
			$html .= "						<tr $estilo>\n";
			$html .= "							<td align=\"left\" class=\"label\" colspan=\"2\">TOTAL</td>\n";
			$html .= "							<td align=\"right\" class=\"label\">$".FormatoValor($valor)."</td>\n";
			$html .= "							<td colspan=\"2\">&nbsp;</td>\n";
			$html .= "						</tr>\n";
			$html .= "					</table>\n";
			$html .= "				</fieldset>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
		  $usuario = $nts->ObtenerUsuarioNombre(UserGetUID());
			$html .= "	<br><table border='0' width=\"100%\">\n";
			$html .= "		<tr>\n";
      $html .= "			<td align=\"justify\" width=\"50%\">\n";
			$html .= "				<font size='1' face='arial'>\n";
			$html .= "					Imprimió:&nbsp;".$usuario['nombre']."\n";
			$html .= "				</font>\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"right\" width=\"50%\">\n";
			$html .= "				<font size='1' face='arial'>\n";
			$html .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
			$html .= "				</font>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			return $html;
		}		
	} 
?>