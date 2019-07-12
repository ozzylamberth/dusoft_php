<?php

/**
 * $Id: ReciboPagare.report.php,v 1.5 2010/11/18 14:18:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de recibodevolucion para PDF
 */

class ReciboPagare_report extends pos_reports_class
{
    
    //constructor por default
    function ReciboPagare_report()
    {
        $this->pos_reports_class();
        return true;
    }
		
    /**
    *
    */
    function CrearReporte()
    {
        IncludeLib("tarifario");
				//include_once("classes/fpdf/conversor.php");
        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.
        $reporte->PrintFTexto($datos[razon_social],true,$align='center',false,true);
        //reporte->SaltoDeLinea();
        //$reporte->PrintFTexto($datos[tipo_id_tercero].' '.$datos[id],false,'center',false,false);
        $reporte->PrintFTexto($datos[direccion],false,'center',false,false);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('RECIBO PAGARE',true,'center',false,false);
        $reporte->PrintFTexto('No. '.$datos[prefijo]."-".$datos[recibo_caja],true,'center',false,false);        
        $reporte->SaltoDeLinea();
				//$reporte->PrintFTexto($datos[1][texto1],true,'center',false,false);                
        //$reporte->SaltoDeLinea();        
        $reporte->PrintFTexto('Fecha  : '.date('d/m/Y H:i'),false,'left',false,false);
        $cad1=substr('Atendio: '.$datos[usuario_id].' - '.$datos[usuario],0,42);
        //$reporte->PrintFTexto('Atendio  : '.$datos[0][usuario_id].' - '.$datos[0][usuario],false,'left',false,false);
        $reporte->PrintFTexto($cad1,false,'left',false,false);        
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('Identifi: '.$datos[id],false,'left',false,false);
        $reporte->PrintFTexto('Paciente: '.$datos[nombre],false,'left',false,false);
				//la factura para el paciente
				$arr=$this->detalles($datos[recibo_caja],$datos[prefijo],$datos[empresa_id]);
				$reporte->PrintFTexto('Plan    : '.$datos[plan_descripcion],false,'left',false,false);
				$reporte->PrintFTexto('Cuenta Nro : '.$arr[cuenta]);
        //$reporte->PrintFTexto('Tipo Afi: '.$arr[tipo_afiliado_nombre].'     Rango: '.$arr[rango],false,'left',false,false);
        $total=0;
				$moderadora=$copago=$nocub=0;
     		$reporte->SaltoDeLinea();	
				$efectivo=$datos[total_efectivo];
				$cheque=$datos[total_cheques];
				$tarjeta=$datos[total_tarjetas];
				$bono=$datos[total_bonos];
				$total=$efectivo + $cheque + $tarjeta + $bono;
				$reporte->PrintFTextoValor('TOTAL EFECTIVO',$datos[total_efectivo],0,true,11,true,'right');
				$reporte->PrintFTextoValor('TOTAL CHEQUES',$datos[total_cheques],0,true,11,true,'right');
				$reporte->PrintFTextoValor('TOTAL TARJETAS',$datos[total_tarjetas],0,true,11,true,'right');
				$reporte->PrintFTextoValor('TOTAL BONO',$datos[total_bonos],0,true,11,true,'right');
              
				$reporte->PrintFTextoValor('TOTAL ABONO',$datos[total_abono],0,true,11,true,'right');
				$reporte->SaltoDeLinea();
				$reporte->SaltoDeLinea();
/*				$reporte->PrintFTexto('FIRMA BENEFICIARIO ',false,'left',false,false,'_');
				$reporte->PrintFTexto('NOMBRE BENEFICIARIO',false,'left',false,false,'_');
				$reporte->PrintFTexto('NUMERO DE ID',false,'left',false,false,'_');
				$reporte->PrintFTexto('TELEFONO',false,'left',false,false,'_');*/
				$reporte->PrintEnd();
				//$reporte->OpenCajaMonedera();
				$reporte->PrintCutPaper();
				return true;
    }
		
		/*FUCNION Q ESTRAE INFORMACION ACERCA DE LA CUENTA Y EL TIPO DE PAGO QUE
	SE EFECTUO*/
	function detalles($recibo,$prefijo,$empresa)
	{
			list($dbconn) = GetDBconn();

			$query = "SELECT b.numerodecuenta as cuenta,c.tipo_afiliado_nombre,b.rango
										FROM  rc_detalle_pagare a,cuentas b, tipos_afiliado c,
													recibos_caja d, pagares e
										WHERE b.tipo_afiliado_id=c.tipo_afiliado_id
											AND e.numerodecuenta=b.numerodecuenta
											AND a.empresa_id=d.empresa_id
											AND a.prefijo=d.prefijo
											AND a.recibo_caja=d.recibo_caja
											AND a.empresa_id=e.empresa_id
											AND a.prefijo=e.prefijo
											AND a.numero=e.numero
											AND a.recibo_caja=$recibo
											AND a.prefijo='$prefijo'
											AND a.empresa_id='$empresa'";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al buscar los abonos de la cuenta";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var1= $result->GetRowAssoc($ToUpper = false);
			return $var1;
	}
		
		
		
}
?>
