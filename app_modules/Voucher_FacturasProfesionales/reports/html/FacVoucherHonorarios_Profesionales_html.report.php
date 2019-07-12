<?php

/**
 * $Id: FacVoucherHonorarios_Profesionales_html.report.php,v 1.8 2007/07/12 14:09:34 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class FacVoucherHonorarios_Profesionales_html_report
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
	function FacVoucherHonorarios_Profesionales_html_report($datos=array())
	{

			$this->datos=$datos;
			return true;
	}


	 function GetMembrete()
	 {
		  $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																  'subtitulo'=>'',
																  'logo'=>'logocliente.png',
																  'align'=>'left'));
		  return $Membrete;
	 }

//FUNCION CrearReporte()
//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
function CrearReporte()
{
//*******************************************terminos
				$datos=$this->ObtenerVoucherAsociadosFactura($this->datos['NoFactura'],$this->datos['Profesional'],$this->datos['fecha_ini'],$this->datos['fecha_fin']);
        if($datos)
				{
          (list($tipoProf,$Prof,$nomProf)=explode('||//',$this->datos['Profesional']));  
					
					if(!$_SESSION['op'])
					{
						$Salida .= "    <table border=\"1\" width=\"80%\" align=\"center\">";    
						$Salida .= "    <tr>";
						$Salida .= "    <td width=\"20%\" class=\"normal_10N\" align=\"center\">No. FACTURA</td>";
						$Salida .= "    <td width=\"30%\" class=\"normal_10\">".$this->datos['NoFactura']."</td>";
						$Salida .= "    </tr>";
						$Salida .= "    <tr>";    
						$Salida .= "    <td width=\"20%\" class=\"normal_10N\" align=\"center\">PROFESIONAL</td>";
						$Salida .= "    <td width=\"30%\" class=\"normal_10\">$nomProf</td>";
						$Salida .= "    </tr>";   
						$Salida .= "    <tr><td colspan=\"2\" class=\"normal_10N\" align=\"center\">VOUCHER ASOCIADOS A LA FACTURA</td></tr>";
						$Salida .= "    <tr><td width=\"90%\" colspan=\"2\" class=\"normal_10\" align=\"center\">";
						$Salida .= "        <table border=\"0\" width=\"100%\" align=\"center\">";    
						$Salida .= "        <tr class=\"normal_10N\">";
						$Salida .= "        <td>PF</td>";
						$Salida .= "        <td>NUMERO</td>";
						$Salida .= "        <td>CUENTA</td>";
						$Salida .= "        <td align=\"right\">VALOR NOTA CREDITO</td>";
						$Salida .= "        <td align=\"right\">VALOR NOTA DEBITO</td>";
						$Salida .= "        <td align=\"right\">VALOR ACTUAL HONORARIO</td>";              
						$Salida .= "        </tr>";
						for($i=0;$i<sizeof($datos);$i++)
						{
							$Salida .= "        <tr class=\"normal_10\">";
							$Salida .= "        <td>".$datos[$i]['prefijo']."</td>";
							$Salida .= "        <td>".$datos[$i]['numero']."</td>";
							$Salida .= "        <td>".$datos[$i]['numerodecuenta']."</td>";
							
							$val1="0.00";
							if($datos[$i]['valor_nc']>0)
								$val1=$datos[$i]['valor_nc'];
							
							$val2="0.00";
							if($datos[$i]['valor_nd']>0)
								$val2=$datos[$i]['valor_nd'];
								
							$Salida.= "    <td align=\"right\"> $ ".$val1."</td>\n";
							$Salida.= "    <td align=\"right\"> $ ".$val2."</td>\n";
							$Salida .= "   <td align=\"right\"> $ ".$datos[$i]['valor_real']."</td>";                
							$Salida .= "  </tr>";
						}                    
						$Salida .= "        </table>";
						$Salida .= "    </td></tr>";      
						$Salida .= "    </table>";
					}
					else
					{
						if($datos)
						{
							$b=true;
							foreach($datos as $key=>$valor)
							{
								$Salida .= "<table border=\"1\" width=\"80%\" align=\"center\">";    
								if(!empty($this->datos['fecha_ini']) && !empty($this->datos['fecha_fin']) && $b)
								{
									$Salida .= "	<tr>";    
									$Salida .= "		<td width=\"20\" class=\"normal_10N\">FECHA</td>";
									$Salida .= "		<td width=\"80\" class=\"normal_10\"> DESDE <b>".$this->datos['fecha_ini']."</b> HASTA <b>".$this->datos['fecha_fin']."</b></td>";
									$Salida .= "	</tr>";
									$b=false;
								}
							
								$Salida .= "	<tr>";    
								$Salida .= "		<td width=\"20\" class=\"normal_10N\">PROFESIONAL</td>";
								$Salida .= "		<td width=\"80\" class=\"normal_10\">$key</td>";
								$Salida .= "	</tr>";
									
								foreach($valor as $key1=>$valor1)
								{
									$Salida .= "	<tr>";
									$Salida .= "		<td width=\"20\" class=\"normal_10N\">No. FACTURA</td>";
									$Salida .= "		<td width=\"80\" class=\"normal_10\">".$key1."</td>";
									$Salida .= "	</tr>";

									$rep= new GetReports();
									$Salida .= "    <tr><td colspan=\"2\" class=\"normal_10N\" align=\"center\">VOUCHER ASOCIADOS A LA FACTURA</td></tr>";
									$Salida .= "    <tr><td width=\"100%\" colspan=\"2\" align=\"center\">";
									$Salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";    
									$Salida .= "        <tr class=\"normal_10N\">";
									$Salida .= "        <td>PF</td>";
									$Salida .= "        <td>NUMERO</td>";
									$Salida .= "        <td>CUENTA</td>";
									$Salida .= "        <td align=\"right\">VALOR NOTA CREDITO</td>";
									$Salida .= "        <td align=\"right\">VALOR NOTA DEBITO</td>";
									$Salida .= "        <td align=\"right\">VALOR ACTUAL HONORARIO</td>";  
									foreach($valor1 as $valor2)
									{        
										$Salida .= "        </tr>";
										$Salida .= "        <tr class=\"normal_10\">";
										$Salida .= "        <td>".$valor2['prefijo']."</td>";
										$Salida .= "        <td>".$valor2['numero']."</td>";
										$Salida .= "        <td>".$valor2['numerodecuenta']."</td>";
										
										$val1="0.00";
										if($valor2['valor_nc']>0)
											$val1=$valor2['valor_nc'];
										
										$val2="0.00";
										if($valor2['valor_nd']>0)
											$val2=$valor2['valor_nd'];
											
										$Salida.= "    <td align=\"right\"> $ ".$val1."</td>\n";
										$Salida.= "    <td align=\"right\"> $ ".$val2."</td>\n";
										$Salida .= "   <td align=\"right\"> $ ".$valor2['valor_real']."</td>"; 
										$Salida .= "        </tr>";
									}
									$Salida .= "        </table>";
									$Salida .= "    </td></tr>";
								}
							}
								$Salida .= "</table>";
						}
					}
        }	      
  	    return $Salida;
//*****************************************fin de termino
 }
	
	function ObtenerVoucherAsociadosFactura($NoFactura=null,$Profesional=null,$fecha_ini=null,$fecha_fin=null)
	{
    list($dbconn) = GetDBconn();
    
		if(!empty($NoFactura))
		{
			$datFactura=" AND f.numero_factura_id='".$NoFactura."' "; 
		}
		
		if(!empty($Profesional))
		{
			(list($tipoProf,$Prof)=explode('||//',$Profesional));   
			$datprofesional="AND f.tipo_id_tercero='".$tipoProf."' AND f.tercero_id='".$Prof."'"; 
		}
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$datFechas="AND date(f.fecha_registro)>='".$fecha_ini."' 
									AND date(f.fecha_registro)<='".$fecha_fin."'";
		}
		
		$query = "SELECT f.numero_factura_id,
										b.prefijo,
										b.numero,
										b.valor_honorario,
										b.numerodecuenta,
										f.tipo_id_tercero,
										f.tercero_id,
										c.nombre_tercero,
										b.valor_real,
										d.valor as valor_nc,
										e.valor as valor_nd,
										f.prefijo as prefijo_cxp,
										f.numero as numero_cxp
             FROM 
						 			voucher_honorarios_cuentas_x_pagar as f,
									voucher_honorarios_facturas_profesionales a,
						 			voucher_honorarios b
									LEFT JOIN voucher_honorarios_nc as d
									ON
									(
										b.empresa_id=d.empresa_id
										AND b.prefijo=d.prefijo_voucher 
										AND b.numero=d.numero_voucher 
										AND d.estado='1'
									)
									LEFT JOIN voucher_honorarios_nd as e
									ON
									(
										b.empresa_id=e.empresa_id
										AND b.prefijo=e.prefijo_voucher 
										AND b.numero=e.numero_voucher 
										AND e.estado='1'
									)
									, 
									terceros as c
             WHERE a.prefijo=b.prefijo 
						 AND a.numero=b.numero 
						 AND a.empresa_id=b.empresa_id 
						 AND f.tipo_id_tercero=c.tipo_id_tercero 
						 AND f.tercero_id=c.tercero_id
						 AND a.prefijo_cxp=f.prefijo
						 AND a.numero_cxp=f.numero 
						 AND a.empresa_id=f.empresa_id 
						 AND f.estado='1'
						 AND b.valor_real>0
             $datFactura 
             $datprofesional
						 $datFechas
             ORDER BY f.fecha_registro DESC";         
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - ObtenerVoucherAsociadosFactura";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
        if($result->RecordCount()>0)
				{ 
					if(!$_SESSION['op'])
					{
						while(!$result->EOF)
						{
							$vars[]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
					else
					{
						while(!$result->EOF)
						{
							$vars[$result->fields[7]][$result->fields[0]][]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
        }
    }
    $result->Close();
    return $vars;                     
  }
    //---------------------------------------
}

?>
