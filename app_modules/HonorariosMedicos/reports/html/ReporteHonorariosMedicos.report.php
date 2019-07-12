<?php

/**
 * $Id: ReporteHonorariosMedicos.report.php,v 1.10 2007/04/18 19:17:07 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteHonorariosMedicos_report
{
	var $datos;
	
	function ReporteHonorariosMedicos_report($datos=array())
	{
		$this->datos=$datos;
		return true;
	}

	
	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	function CrearReporte()
	{
		$HTML_WEB_PAGE .="<HTML><BODY>";         
		$HTML_WEB_PAGE .= "<br><br><center>";
		$HTML_WEB_PAGE .= "<label><font size='2' face='arial'>VOUCHER DE HONORARIOS MEDICOS</font></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";
		
		if($this->datos['T']=='0')
		{
			$nombre_paciente="";

			$nombre_paciente.=$this->datos['lista']['primer_apellido']." ";
			$nombre_paciente.=$this->datos['lista']['segundo_apellido'].", ";
			$nombre_paciente.=$this->datos['lista']['primer_nombre']." ";
			$nombre_paciente.=$this->datos['lista']['segundo_nombre'];
			
			$HTML_WEB_PAGE.="<TABLE WIDTH='85%' BORDER='1' ALIGN='CENTER'>";

			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='15%' ALIGN='LEFT'><FONT SIZE='1'>EMPRESA:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['razon_social']."</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'>NIT:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['id']."</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='2' WIDTH='40%' ALIGN='LEFT'><FONT SIZE='1'>No: ".$this->datos['lista']['prefijo']." - ".$this->datos['lista']['numero']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
			
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>FECHA:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'>".substr($this->datos['lista']['fecha_registro'],0,19)."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
			
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>PROFESIONAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['nombre']."</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['tipo_id_profesional'].":</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['profesional_id']."</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TIPO PROFESIONAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['desc_tipo_pro']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
			
			if($this->datos['lista']['profesional_id']!=$this->datos['lista']['tercero_id'])
			{
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TERCERO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['nombre_tercero']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'> - ".$this->datos['lista']['tipo_id_tercero'].":</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['tercero_id']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
			}
			
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>PLAN:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['plan_descripcion']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";	
			
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>CUENTA:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='2' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['numerodecuenta']."</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TRANSACCIÓN:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='2' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['transaccion']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";	
			
			if(!empty($this->datos['lista']['numero_factura_id']) OR !empty($this->datos['lista']['fecha_rad']) OR !empty($this->datos['lista']['numero_recibo']))
				{
					$HTML_WEB_PAGE.="<TR>";
					if(!empty($this->datos['lista']['numero_factura_id']))
					{
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>FACTURA MEDICA:</FONT></TD>";
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>&nbsp;".$this->datos['lista']['numero_factura_id']."</FONT></TD>";
					}
					if(!empty($this->datos['lista']['fecha_rad']))
					{	
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>FECHA RAD:</FONT></TD>";
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>&nbsp;".$this->datos['lista']['fecha_rad']."</FONT></TD>";
					}
					if(!empty($this->datos['lista']['numero_recibo']))
					{
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>NUMERO RECIBO:</FONT></TD>";
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>&nbsp;".$this->datos['lista']['numero_recibo']."</FONT></TD>";
					}
					$HTML_WEB_PAGE.="</TR>";
				}
			
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>PACIENTE:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$nombre_paciente."</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['tipo_id_paciente'].":</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='3' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['lista']['paciente_id']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";		
			
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>CARGO:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'> (".$this->datos['lista']['tarifario_id'].") ".$this->datos['lista']['cargo']."   -   ".strtoupper($this->datos['lista']['desc_cargo'])."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
			
			if(!empty($this->datos['lista']['consecutivo_procedimiento']))
			{
				$procedimientos=$this->GetDetalleProcedimiento($this->datos['lista']['consecutivo_procedimiento']);
				foreach($procedimientos as $proc)
					$prts=$proc['descripcion']."";
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>PROCEDIMIENTO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'> ".$prts."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";	
			}
			
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>VALOR CARGO:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'> $ ".$this->datos['lista']['valor_cargo']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>% HONORARIO:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'>  ".($this->datos['lista']['porcentaje_liquidacion'])." %</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
			
			if($this->datos['lista']['valor_honorario']==$this->datos['lista']['valor_real'])
				$valor=$this->datos['lista']['valor_honorario'];
			else
				$valor=$this->datos['lista']['valor_real'];
			
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TOTAL ACTUAL HONORARIO:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'> $ ".$valor."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
			
			if($this->datos['lista']['valor_nc']>0)
			{
				/*$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>NOTA CREDITO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='5'>";
				$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER='1' HEIGHT='100%'>";
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>PREFIJO :</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'> ".$this->datos['lista']['prefijo_nc']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>NUMERO :</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'> ".$this->datos['lista']['numero_nc']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>VALOR NOTA CREDITO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='50%' ALIGN='LEFT'><FONT SIZE='1'> $ ".$this->datos['lista']['valor_nc']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				$HTML_WEB_PAGE.="</TABLE>";
				$HTML_WEB_PAGE.="</TD>";
				$HTML_WEB_PAGE.="</TR>";*/
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>VALOR NOTA CREDITO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'> $ ".$this->datos['lista']['valor_nc']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'  COLSPAN='4'><FONT SIZE='1'>No: ".$this->datos['lista']['prefijo_nc']." - ".$this->datos['lista']['numero_nc']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
			}
			if($this->datos['lista']['valor_nd']>0)
			{
				/*$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>NOTA DEBITO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='5'>";
				$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER='1' HEIGHT='100%'>";
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>PREFIJO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'> ".$this->datos['lista']['prefijo_nd']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>NUMERO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'> ".$this->datos['lista']['numero_nd']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>VALOR NOTA DEBITO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='50%' ALIGN='LEFT'><FONT SIZE='1'> $ ".$this->datos['lista']['valor_nd']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				$HTML_WEB_PAGE.="</TABLE>";
				$HTML_WEB_PAGE.="</TD>";
				$HTML_WEB_PAGE.="</TR>";
				*/
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>VALOR NOTA DEBITO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'> $ ".$this->datos['lista']['valor_nd']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'  COLSPAN='4'><FONT SIZE='1'>No: ".$this->datos['lista']['prefijo_nd']." - ".$this->datos['lista']['numero_nd']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
			}
			
			$HTML_WEB_PAGE.="</TABLE>";
		}
		else if($this->datos['T']=='1')
		{
			$i=0;
			foreach($_SESSION['listado'] as $datos)
			{
				$nombre_paciente="";
				$nombre_paciente.=$datos['primer_apellido']." ";
				$nombre_paciente.=$datos['segundo_apellido'].", ";
				$nombre_paciente.=$datos['primer_nombre']." ";
				$nombre_paciente.=$datos['segundo_nombre'];
				
				$HTML_WEB_PAGE.="<TABLE WIDTH='85%' BORDER=1 ALIGN='CENTER'>";
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD WIDTH='15%' ALIGN='LEFT'><FONT SIZE='1'>EMPRESA:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos['razon_social']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'>NIT:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>".$datos['id']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='2' WIDTH='40%' ALIGN='LEFT'><FONT SIZE='1'>No: ".$datos['prefijo']." - ".$datos['numero']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>FECHA:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'>".substr($datos['fecha_registro'],0,19)."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>PROFESIONAL:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['nombre']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['tipo_id_tercero'].":</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['tercero_id']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TIPO PROFESIONAL:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['desc_tipo_pro']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				
				if($datos['profesional_id']!=$datos['tercero_id'])
				{
					$HTML_WEB_PAGE.="<TR>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TERCERO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['nombre_tercero']."</FONT></TD>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'> - ".$datos['tipo_id_tercero'].":</FONT></TD>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['tercero_id']."</FONT></TD>";
					$HTML_WEB_PAGE.="</TR>";
				}
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>PLAN:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'>".$datos['plan_descripcion']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";	
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>CUENTA:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='2' ALIGN='LEFT'><FONT SIZE='1'>".$datos['numerodecuenta']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TRANSACCIÓN:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='2' ALIGN='LEFT'><FONT SIZE='1'>".$datos['transaccion']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				
				if(!empty($datos['numero_factura_id']) OR !empty($datos['fecha_rad']) OR !empty($datos['numero_recibo']))
				{
					$HTML_WEB_PAGE.="<TR>";
					if(!empty($datos['numero_factura_id']))
					{
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>FACTURA MEDICA:</FONT></TD>";
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['numero_factura_id']."</FONT></TD>";
					}
					if(!empty($datos['fecha_rad']))
					{	
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>FECHA RAD:</FONT></TD>";
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['fecha_rad']."</FONT></TD>";
					}
					if(!empty($datos['numero_recibo']))
					{
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>NUMERO RECIBO:</FONT></TD>";
						$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['numero_recibo']."</FONT></TD>";
					}
					$HTML_WEB_PAGE.="</TR>";
				}
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>PACIENTE:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$nombre_paciente."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$datos['tipo_id_paciente'].":</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='3' ALIGN='LEFT'><FONT SIZE='1'>".$datos['paciente_id']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";

				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>CARGO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'> (".$datos['tarifario_id'].") ".$datos['cargo']."   -   ".strtoupper($datos['desc_cargo'])."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				
				if(!empty($datos['consecutivo_procedimiento']))
				{
					$procedimientos=$this->GetDetalleProcedimiento($datos['consecutivo_procedimiento']);
					foreach($procedimientos as $proc)
						$prts=$proc['descripcion']."";
					
					
					$HTML_WEB_PAGE.="<TR>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>PROCEDIMIENTO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'> ".$prts."</FONT></TD>";
					$HTML_WEB_PAGE.="</TR>";	
				}
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>VALOR CARGO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'> $ ".$datos['valor_cargo']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>% HONORARIO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'>  ".($datos['porcentaje_liquidacion'])." %</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				
				if($datos['valor_honorario']==$datos['valor_real'])
					$valor=$datos['valor_honorario'];
				else
					$valor=$datos['valor_real'];
				
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TOTAL ACTUAL HONORARIO:</FONT></TD>";
				$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='1'> $ ".$valor."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				
				if($datos['valor_nc']>0)
				{
					/*$HTML_WEB_PAGE.="<TR>";
					$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>NOTA CREDITO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD COLSPAN='5'>";
					$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER='1' HEIGHT='100%'>";
					$HTML_WEB_PAGE.="<TR>";
					$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>PREFIJO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'> ".$datos['prefijo_nc']."</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>NUMERO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'> ".$datos['numero_nc']."</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>VALOR NOTA CREDITO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='50%' ALIGN='LEFT'><FONT SIZE='1'> $ ".$datos['valor_nc']."</FONT></TD>";
					$HTML_WEB_PAGE.="</TR>";
					$HTML_WEB_PAGE.="</TABLE>";
					$HTML_WEB_PAGE.="</TD>";
					$HTML_WEB_PAGE.="</TR>";*/
					
					$HTML_WEB_PAGE.="<TR>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>VALOR NOTA CREDITO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'> $ ".$datos['valor_nc']."</FONT></TD>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'  COLSPAN='4'><FONT SIZE='1'>No: ".$datos['prefijo_nc']." - ".$datos['numero_nc']."</FONT></TD>";
					$HTML_WEB_PAGE.="</TR>";
				}
				if($datos['valor_nd']>0)
				{
					/*$HTML_WEB_PAGE.="<TR>";
					$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>NOTA DEBITO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD COLSPAN='5'>";
					$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER='1' HEIGHT='100%'>";
					$HTML_WEB_PAGE.="<TR>";
					$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>PREFIJO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'> ".$datos['prefijo_nd']."</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='LEFT'><FONT SIZE='1'>NUMERO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='LEFT'><FONT SIZE='1'> ".$datos['numero_nd']."</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>VALOR NOTA DEBITO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD WIDTH='50%' ALIGN='LEFT'><FONT SIZE='1'> $ ".$datos['valor_nd']."</FONT></TD>";
					$HTML_WEB_PAGE.="</TR>";
					$HTML_WEB_PAGE.="</TABLE>";
					$HTML_WEB_PAGE.="</TD>";
					$HTML_WEB_PAGE.="</TR>";*/
					
					$HTML_WEB_PAGE.="<TR>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>VALOR NOTA CREDITO:</FONT></TD>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'> $ ".$datos['valor_nd']."</FONT></TD>";
					$HTML_WEB_PAGE.="<TD ALIGN='LEFT'  COLSPAN='4'><FONT SIZE='1'>No: ".$datos['prefijo_nd']." - ".$datos['numero_nd']."</FONT></TD>";
					$HTML_WEB_PAGE.="</TR>";
				}
				
				$HTML_WEB_PAGE.="</TABLE>";
				
				$i++;
				$HTML_WEB_PAGE.="</TABLE><BR><BR><BR>";
			}
		}
		
		$HTML_WEB_PAGE.="</BODY></HTML>";
		
		return $HTML_WEB_PAGE;
	}
	
	
	function GetDetalleProcedimiento($consecutivo_procedimiento)
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		$query="SELECT a.cargo_cups,b.descripcion
						FROM cuentas_liquidaciones_qx_procedimientos as a
						JOIN cups as b ON(a.cargo_cups=b.cargo)
						WHERE a.consecutivo_procedimiento=$consecutivo_procedimiento";
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "HonorariosMedicos - MostrarHonorarios - SQL ERROR 2";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while($res=$resultado->FetchRow()) 
			$filas[]=$res;
		
		$resultado->Close();
	
		return $filas;
	}
}
?>
