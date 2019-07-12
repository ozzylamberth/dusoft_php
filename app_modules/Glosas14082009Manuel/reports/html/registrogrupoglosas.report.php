<?php

	/**************************************************************************************
	 * $Id: registrogrupoglosas.report.php,v 1.11 2007/06/12 18:50:48 hugo Exp $ 
	 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	 * @package IPSOFT-SIIS
	 * 
	 **************************************************************************************/
	include_once "./app_modules/Glosas/classes/Glosas.class.php";
	class registrogrupoglosas_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
		var $datos;
		
		//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
		//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
		var $title       = '';
		var $author      = 'SIIS';
		var $sizepage    = 'leter';
		var $Orientation = '';
		var $grayScale   = false;
		var $headers     = array();
		var $footers     = array();
		
	    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	  function registrogrupoglosas_report($datos=array())
	  {
			$this->datos=$datos;
	  	return true;
	  }
		
		function GetMembrete()
		{
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10pt\"";
			$idempresa = $_SESSION['glosas']['tipo_id']." ".$_SESSION['glosas']['id'];
			
			$titulo .= "<b $estilo >".$_SESSION['glosas']['razon_social']."<br>";
			$titulo .= $idempresa."<br>";
			$titulo .= "REGISTRO DE GLOSAS EN ESTADO: ";
			
			if($this->datos['estado'] == '2') 
				$titulo .= "POR CONTABILIZAR ";
			else if($this->datos['estado'] == '3')	
				$titulo .= "POR REVISAR ";
			else if($this->datos['estado'] == '4')	
				$titulo .= "ANULADAS ";
			else if($this->datos['estado'] == '5')	
				$titulo .= "CERRADAS ";
			$titulo .= "</b><br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}
	    //FUNCION CrearReporte()
		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$this->FechaFin = $this->datos['fFin'];
			$this->TerceroId = $this->datos['terceroId'];
			$this->FechaInicio = $this->datos['fInicio'];
			$this->FacturaFiscal = $this->datos['fFiscal'];
			$this->NombreTercero = $this->datos['nombre_tercero'];
			$this->TipoIdTercero = $this->datos['tipoIdTercero'];
			$this->PrefijoFactura = $this->datos['fPrefijo'];
		
			$empresa = $_SESSION['glosas']['empresa_id'];
			$opcion = $_SESSION['glosas']['sw_clientes'];
			
			$gls = new Glosas();
			$rgglosas = $gls->ObtenerGlosasReporte($this->datos,$empresa,$opcion);

			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$estilo2 = " style=\" PAGE BREAK AFTER:always\" ";
			
			$Salida = "";
			
			$fecha1 = date("Y-m-d");$fecha2 = "";
			$valortotalf = $valortotalg = 0;
			foreach($rgglosas as $key =>$glosas)
			{
				$Salida .= "		<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" >\n";
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"label\" width=\"30%\" height=\"25\">CLIENTE: ".$key."</td>";
				$Salida .= "			</tr>\n";
				$Salida .= "	</table>";
				
				$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" width=\"100%\" rules=\"all\" $estilo>\n";
				$Salida .= "		<tr>\n";
				$Salida .= "			<td align=\"center\" width=\"9%\"><b>FACTURA</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"9%\"><b>Nº GLOSA</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"9%\"><b>F. GLOSA</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"9%\"><b>V. FACTURA</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"9%\"><b>V. GLOSA</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"25%\"><b>MOTIVO</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"15%\"><b>AUDITOR</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"15%\"><b>RESPONSABLE</b></td>\n";
				$Salida .= "		</tr>\n";
				$valorf = $valorg = 0;

				for($k=0; $k<sizeof($glosas); $k++)
				{
					if($fecha1 >= $glosas[$k]['fecha'] ) $fecha1 = $glosas[$k]['fecha'];
					if($fecha2 <= $glosas[$k]['fecha'] ) $fecha2 = $glosas[$k]['fecha'];
					
					$Salida .= "			<tr>\n";
					$Salida .= "				<td >".$glosas[$k]['prefijo']." ".$glosas[$k]['factura_fiscal']."</td>\n";
					$Salida .= "				<td >".$glosas[$k]['glosa_id']."</td>\n";
					$Salida .= "				<td align=\"center\" >".$glosas[$k]['fecha_glosa']."</td>\n";
					$Salida .= "				<td align=\"right\"  >".formatoValor($glosas[$k]['total_factura'])."</td>\n";		
					$Salida .= "				<td align=\"right\"  >".formatoValor($glosas[$k]['valor_glosa'])."</td>\n";		
					$Salida .= "				<td >".$glosas[$k]['motivo_glosa_descripcion']."&nbsp;</td>\n";				
					$Salida .= "				<td >".$glosas[$k]['nombre']."&nbsp;</td>\n";				
					$Salida .= "				<td >".$gls->ObtenerUsuarioNombre($glosas[$k]['usuario_id'])."</td>\n";
					$Salida .= "			</tr>\n";
					
					$valorf += $glosas[$k]['total_factura'];
					$valorg += $glosas[$k]['valor_glosa'];
				}
				
				$Salida .= "		</table>\n";
				$Salida .= "		<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" >\n";
				$Salida .= "			<tr>\n";
				$Salida .= "				<td class=\"label\" width=\"27%\" height=\"25\">Sub Total ".$key."</td>";
				$Salida .= "				<td class=\"label\" width=\"9%\" align=\"right\" valign=\"buttom\">$".formatoValor($valorf) ."</td>";
				$Salida .= "				<td class=\"label\" width=\"9%\" align=\"right\" valign=\"buttom\">$".formatoValor($valorg) ."</td>";
				$Salida .= "				<td class=\"label\" width=\"55%\">&nbsp;</td>";
				$Salida .= "			</tr>\n";
				$Salida .= "	</table><br>";
				
				$valortotalf += $valorf;
				$valortotalg += $valorg;
			}
			
			$Salida .= "		<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" >\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td class=\"label\" width=\"27%\" height=\"25\">TOTAL GLOSAS.................................</td>";
			$Salida .= "				<td class=\"label\" width=\"9%\" align=\"right\" valign=\"buttom\">$".formatoValor($valortotalf) ."</td>";
			$Salida .= "				<td class=\"label\" width=\"9%\" align=\"right\" valign=\"buttom\">$".formatoValor($valortotalg) ."</td>";
			$Salida .= "				<td class=\"label\" width=\"55%\">&nbsp;</td>";
			$Salida .= "			</tr>\n";
			$Salida .= "	</table><br>";
			
			$Salida1 .= "	<center><b class=\"label\">PERIODO: ".str_replace("-","/",$fecha1)." - ".str_replace("-","/",$fecha2)."</b></center>";
			$Salida1 .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" $estilo >\n";
			$Salida1 .= "		<tr>";
			$Salida1 .= "			<td align=\"left\"><b>Fecha: ".date("d/m/Y")."</td>";
			$Salida1 .= "		</tr>";
			$Salida1 .= "		<tr>";
			$Salida1 .= "			<td align=\"left\"><b>Usuario: ".$gls->ObtenerUsuarioNombre(UserGetUID())."</td>";
			$Salida1 .= "		</tr>";
			$Salida1 .= "	</table><br>";
			$Salida1 .= "	".$Salida;
			
	    return $Salida1;
		}
	}

?>
