<?php

/**
 * $Id: FacturaConcepto.report.php,v 1.5 2010/11/18 14:18:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class FacturaConcepto_report
{

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function FacturaConcepto_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}

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


	function GetMembrete()
	{
		$Membrete = array('file'=>'MembreteLogosSOS','datos_membrete'=>array('titulo'=>GetVarConfigAplication('Cliente'),
																'subtitulo'=>'FACTURA CAMBIARIA DE COMPRAVENTA ',
																'logo'=>'logocliente.png',
																'align'=>'center'));
		return $Membrete;
	}

    /**
    *
    */
    function CrearReporte()
    {
				$dat = $this->DatosFactura($this->datos['empresa'],$this->datos['prefijo'],$this->datos['factura']);

				$salida .= "<table width='80%' border=0>";
        $salida .=  "<tr>";				
        $salida .=  "<td align=\"center\" class=\"titulo2\">".$dat[0][razon_social]."</td>";
				$salida .=  "</tr>";
				$salida .=  "<tr>";	
        $salida .=  "<td align=\"center\" class=\"normal_10\">".$dat[0][tipoid].' '.$dat[0][id]."</td>";
				$salida .=  "</tr>";
				$salida .=  "<tr>";	
        $salida .=  "<td align=\"center\" class=\"normal_10\">".$dat[0][direccion].' '.$dat[0][municipio].' - '.$dat[0][departamento]."</td>";
				$salida .=  "</tr>";
				$salida .= "<tr><td><br></td></tr>";
				$salida .=  "<tr>";	
        $salida .=  "<td align=\"center\" class=\"normal_10N\">FACTURA CAMBIARIA DE COMPRAVENTA</td>";
				$salida .=  "</tr>";		
				$salida .=  "<tr>";	
        $salida .=  "<td align=\"center\" class=\"normal_10N\">No. ".$dat[0][prefijo]."".$dat[0][factura_fiscal]."</td>";
				$salida .=  "</tr>";	
				$salida .= "<tr><td><br></td></tr>";	
				$salida .=  "<tr>";	
        $salida .=  "<td align=\"center\" class=\"normal_10N\">".$dat[0][texto1]."</td>";
				$salida .=  "</tr>";	
				$salida .= "<tr><td><br></td></tr>";	
				$salida .=  "<tr>";	
        $salida .=  "<td align=\"left\" class=\"normal_10\">Fecha  : ".date('d/m/Y h:i')."</td>";
				$salida .=  "</tr>";											
				$salida .=  "<tr>";	
        $salida .=  "<td align=\"left\" class=\"normal_10\">".substr('Atendio: '.$dat[0][usuario_id].' - '.$dat[0][nombre],0,42)."</td>";
				$salida .=  "</tr>";	
				$salida .= "<tr><td><br></td></tr>";		
				$salida .=  "<tr>";	
        $salida .=  "<td align=\"left\" class=\"normal_10\">Identificación: ".$dat[0][tipotercero].' '.$dat[0][tercero_id]."</td>";
				$salida .=  "</tr>";					
				$salida .=  "<tr>";	
        $salida .=  "<td align=\"left\" class=\"normal_10N\">Cliente : ".$dat[0][nombre_tercero]."</td>";
				$salida .=  "</tr>";
				$salida .= "<tr><td><br></td></tr>";	
				$salida .=  "</table>";			
				
				$salida .= "<table width='80%' border=0>";
        $salida .=  "<tr>";		
        $salida .=  "<td align=\"left\" class=\"normal_10N\" width='80%'>DETALLE</td>";				
        $salida .=  "<td align=\"left\" class=\"normal_10N\" width='20%'>VALOR</td>";
				$salida .=  "</tr>";
				for($i=1; $i<sizeof($dat); $i++)
        {        
						$salida .=  "<tr>";
						if(empty($dat[$i][concepto]))
							$salida .=  "<td align=\"left\" class=\"normal_10\" width='80%'>".$dat[$i][descripcion]."</td>";
						else
							$salida .=  "<td align=\"left\" class=\"normal_10\" width='80%'>".$dat[$i][descripcion]."--".$dat[$i][concepto]."</td>";
						$salida .=  "<td align=\"left\" class=\"normal_10\" width='20%'>$ &nbsp;".FormatoValor($dat[$i][valor_total])."</td>";
						$salida .=  "</tr>";				
        }//fin for
				$salida .= "<tr><td colspan=\"2\"><br></td></tr>";
        $salida .=  "<tr>";		
        $salida .=  "<td align=\"left\" class=\"normal_10N\" width='80%'>TOTAL</td>";				
        $salida .=  "<td align=\"left\" class=\"normal_10N\" width='20%'>$ &nbsp;".FormatoValor($dat[0][total_factura])."</td>";
				$salida .=  "</tr>";	
				if(!empty($dat[0][texto2]))
        {
						$salida .= "<tr><td colspan=\"2\"><br></td></tr>";
						$salida .= "<tr><td colspan=\"2\"><br>".$dat[1][texto2]."<br></td></tr>"; 
        }
        if(!empty($dat[0][mensaje]))
        {
						$salida .= "<tr><td colspan=\"2\"><br></td></tr>";
						$salida .= "<tr><td colspan=\"2\"><br>".$dat[1][mensaje]."<br></td></tr>"; 
        } 		
				$salida .=  "</table>";										
				return $salida;
    }

		function DatosFactura($empresa,$prefijo,$factura)
		{
					//DATOS GENERALES
					list($dbconn) = GetDBconn();
					$query = "SELECT i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid,
										i.id, j.departamento, k.municipio, f.*, e.texto1, e.texto2, 
										e.mensaje, d.nombre, g.nombre_tercero, g.tercero_id, g.tipo_id_tercero as tipotercero
										FROM empresas as i, tipo_dptos as j, tipo_mpios as k,
										documentos as e,fac_facturas as f, system_usuarios as d, terceros as g          
										WHERE f.empresa_id='$empresa'
										and f.prefijo='$prefijo'
										and f.factura_fiscal=$factura
										and f.usuario_id=d.usuario_id
										and i.empresa_id=f.empresa_id
										and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
										and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id 
										and i.tipo_mpio_id=k.tipo_mpio_id
										and f.documento_id=e.documento_id
										and f.tipo_id_tercero=g.tipo_id_tercero
										and f.tercero_id=g.tercero_id";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$var[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->Close();
					
					//DATOS Y DETALLE FACTURA (solo conceptos no inventarios)
/*					$query = "SELECT a.*, 
										case when a.concepto isnull then c.descripcion else a.concepto  end as descripcion
										FROM fac_facturas_conceptos as a,
										fac_facturas_conceptos_dc as b, conceptos_caja_conceptos as c
										WHERE a.empresa_id='$empresa'
										and a.prefijo='$prefijo'
										and a.factura_fiscal=$factura
										and a.fac_factura_concepto_id=b.fac_factura_concepto_id
										and b.concepto_id=c.concepto_id
										and b.grupo_concepto=c.grupo_concepto 
										and b.empresa_id=c.empresa_id";*/
					$query = "SELECT a.*, 
										a.concepto,c.descripcion
										FROM fac_facturas_conceptos as a,
										fac_facturas_conceptos_dc as b, conceptos_caja_conceptos as c
										WHERE a.empresa_id='$empresa'
										and a.prefijo='$prefijo'
										and a.factura_fiscal=$factura
										and a.fac_factura_concepto_id=b.fac_factura_concepto_id
										and b.concepto_id=c.concepto_id
										and b.grupo_concepto=c.grupo_concepto 
										and b.empresa_id=c.empresa_id";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					if(!$result->EOF)
					{
							while(!$result->EOF)
							{
											$var[]=$result->GetRowAssoc($ToUpper = false);
											$result->MoveNext();
							}
					}		
					$result->Close();
					return $var;
		}
		
//----------------------------	
}
?>

