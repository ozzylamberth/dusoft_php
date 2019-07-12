<?php

	/**************************************************************************************
	* $Id: todosrecibos.report.php,v 1.2 2010/03/29 16:21:41 sandra Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/

	class todosrecibos_report 
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
	  function todosrecibos_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b $estilo>REPORTE DE COBROS</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px; text-align:center\""; 

			$recibo = $this->ObtenerRecibosCajaCerrados();
			
			$Salida .= "<table border=\"1\" width=\"100%\" align=\"center\" cellpading= \"0\" cellspacing=\"0\" $estilo>\n";
			$Salida .= "	<tr align=\"center\">\n";
			$Salida .= "		<td width=\"8%\" ><b>Nº DOC</b></td>\n";
			$Salida .= "		<td width=\"22%\"><b>CLIENTE</b></td>\n";
			$Salida .= "		<td width=\"8%\" ><b>FECHA</b></td>\n";
			$Salida .= "		<td width=\"22%\"><b>BANCO</b></td>\n";
			$Salida .= "		<td width=\"8%\"><b>CUENTA</b></td>\n";
			$Salida .= "		<td width=\"8%\" ><b>CHEQUE</b></td>\n";
			$Salida .= "		<td width=\"8%\" ><b>V. DOCUMENTO</b></td>\n";
			$Salida .= "		<td width=\"8%\" ><b>FACTURAS</b></td>\n";
			$Salida .= "		<td width=\"8%\" ><b>CREDITOS</b></td>\n";
			$Salida .= "		<td width=\"8%\" ><b>DEBITOS</b></td>\n";
			$Salida .= "	</tr>\n";
			
			$total1 = $total2 = $total3 = $total4 = 0;
			$numero = "";
			for($i=0; $i<sizeof($recibo); $i++)
			{
				$Salida .= "	<tr>\n";
				$Salida .= "		<td>".$recibo[$i]['prefijo']." ".$recibo[$i]['recibo_caja']."</td>\n";
				$Salida .= "		<td>".$recibo[$i]['nombre_tercero']."</td>\n";
				$Salida .= "		<td align=\"center\">".$recibo[$i]['fecha_registro']."</td>\n";
				if($recibo[$i]['banco'])
				{
					$Salida .= "		<td>".$recibo[$i]['banco']."</td>\n";
					$Salida .= "		<td>".$recibo[$i]['cta_cte']."</td>\n";
					$Salida .= "		<td>".$recibo[$i]['cheque']."</td>\n";
				}
				else if($recibo[$i]['banco2'])
					{
						$Salida .= "		<td>".$recibo[$i]['banco2']."</td>\n";
						$Salida .= "		<td>".$recibo[$i]['numero_cuenta']."</td>\n";
						$Salida .= "		<td>".$recibo[$i]['forma_pago']."</td>\n";
					}
					else
						{
							$Salida .= "		<td colspan=\"3\">".$recibo[$i]['forma_pago']."</td>\n";
						}
				
				if($numero != $recibo[$i]['prefijo']." ".$recibo[$i]['recibo_caja'])
				{
					$total1 += $recibo[$i]['total_abono']; 
					$total2 += $recibo[$i]['valor_debitos'];
					$total3 += $recibo[$i]['valor_creditos'];
					$total4 += $recibo[$i]['valor_facturas'];
				}
				
				$numero = $recibo[$i]['prefijo']." ".$recibo[$i]['recibo_caja'];
				
				$Salida .= "		<td align=\"right\">".formatoValor($recibo[$i]['total_abono'])."</td>\n";
				$Salida .= "		<td align=\"right\">".formatoValor($recibo[$i]['valor_facturas'])."</td>\n";
				$Salida .= "		<td align=\"right\">".formatoValor($recibo[$i]['valor_facturas']+$recibo[$i]['valor_creditos'])."</td>\n";
				$Salida .= "		<td align=\"right\">".formatoValor($recibo[$i]['valor_debitos']+$recibo[$i]['total_abono'])."</td>\n";
				$Salida .= "	</tr>\n";
			}
			
			$Salida .= "	<tr $estilo2 class=\"label\">\n";
			$Salida .= "		<td align=\"left\" colspan=\"6\"><b>TOTAL</b></td>\n";
			$Salida .= "		<td align=\"right\">".formatoValor($total1 )."</td>\n";
			$Salida .= "		<td align=\"right\">".formatoValor($total4)."</td>\n";
			$Salida .= "		<td align=\"right\">".formatoValor($total3 + $total4)."</td>\n";
			$Salida .= "		<td align=\"right\">".formatoValor($total1 + $total2)."</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table><br>\n";
			
	    return $Salida;
		}
		/***************************************************************************************
		*
		****************************************************************************************/
		function ObtenerRecibosCajaCerrados()
		{			
 			$sql .= "SELECT RC.prefijo,";
			$sql .= "				RC.recibo_caja,";
			$sql .= "				RC.total_abono,";
			$sql .= " 			RC.total_efectivo,";
			$sql .= " 			RC.total_cheques,";
			$sql .= " 			RC.total_tarjetas,";
			$sql .= "				RC.total_consignacion,";
			$sql .= "				TO_CHAR(RC.fecha_ingcaja,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "				TE.nombre_tercero, ";
			$sql .= "				BC.descripcion AS banco,";
			$sql .= "				CH.cheque,";
			$sql .= "				CH.cta_cte, ";
			$sql .= "				CG.banco AS banco2,";
			$sql .= "				CG.numero_cuenta, ";
			$sql .= "				COALESCE(RD.valor_debito,0) AS valor_debitos, ";
			$sql .= "				COALESCE(RR.valor_credito,0) AS valor_creditos, ";
			$sql .= "				COALESCE(RF.valor_facturas,0) AS valor_facturas, ";
			$sql .= "				RC.otros ";
			$sql .= "FROM		recibos_caja RC ";
			$sql .= "				LEFT JOIN ( SELECT 	SUM(valor) AS valor_debito,";
			$sql .= "														recibo_caja, ";
			$sql .= "														prefijo, ";
			$sql .= "														empresa_id ";
			$sql .= "										FROM 		rc_detalle_tesoreria_conceptos ";
			$sql .= "										WHERE		naturaleza = 'D' ";
			$sql .= "										GROUP BY 2,3,4) AS RD ";
			$sql .= "				ON(	RD.recibo_caja = RC.recibo_caja AND ";
			$sql .= "						RD.prefijo = RC.prefijo AND ";
			$sql .= "						RD.empresa_id = RC.empresa_id ) ";
			$sql .= "				LEFT JOIN ( SELECT 	SUM(valor) AS valor_credito,";
			$sql .= "														recibo_caja, ";
			$sql .= "														prefijo, ";
			$sql .= "														empresa_id ";
			$sql .= "										FROM 		rc_detalle_tesoreria_conceptos ";
			$sql .= "										WHERE 	naturaleza = 'C' ";
			$sql .= "										GROUP BY 2,3,4) AS RR "; 
			$sql .= "				ON(	RR.recibo_caja = RC.recibo_caja AND ";
			$sql .= "						RR.prefijo = RC.prefijo AND ";
			$sql .= "						RR.empresa_id = RC.empresa_id ) ";
			$sql .= "				LEFT JOIN ( SELECT	SUM(valor_abonado) AS valor_facturas,";
			$sql .= "														recibo_caja, ";
			$sql .= "														prefijo, ";
			$sql .= "														empresa_id, ";
			$sql .= "														centro_utilidad ";
			$sql .= "										FROM		rc_detalle_tesoreria_facturas ";
			$sql .= "										WHERE		sw_estado = '0' ";
			$sql .= "										GROUP BY 2,3,4,5) AS RF ";
			$sql .= "				ON(	RF.recibo_caja = RC.recibo_caja AND ";
			$sql .= "						RF.prefijo = RC.prefijo AND ";
			$sql .= "						RF.centro_utilidad = RC.centro_utilidad AND ";
			$sql .= "						RF.empresa_id = RC.empresa_id ) ";
			$sql .= "				LEFT JOIN cheques_mov CH ";
			$sql .= "				ON( CH.centro_utilidad = RC.centro_utilidad AND ";
			$sql .= " 					CH.recibo_caja = RC.recibo_caja AND ";
			$sql .= " 					CH.prefijo = RC.prefijo)";
			$sql .= "				LEFT JOIN bancos BC ON(BC.banco = CH.banco)";
			$sql .= "				LEFT JOIN ( ";
			$sql .= "							SELECT	BG.numero_cuenta,";
			$sql .= "											BA.descripcion AS banco,";
			$sql .= "											BG.prefijo,";
			$sql .= "											BG.recibo_caja,";
			$sql .= "											BG.centro_utilidad ";
			$sql .= "							FROM		bancos BA,";
			$sql .= "											bancos_cuentas BC,";
			$sql .= "											bancos_consignaciones BG ";
			$sql .= "							WHERE		BA.banco = BC.banco ";
			$sql .= "							AND			BC.numero_cuenta = BG.numero_cuenta) AS CG";
			$sql .= "				ON( CG.centro_utilidad = RC.centro_utilidad AND ";
			$sql .= " 					CG.recibo_caja = RC.recibo_caja AND ";
			$sql .= " 					CG.prefijo = RC.prefijo),";
			$sql .= "				terceros TE,"; 
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	RC.empresa_id = '".$_SESSION['RCFactura']['empresa']."' ";
			$sql .= "AND		SU.usuario_id = RC.usuario_id ";
			$sql .= "AND		RC.estado = '2' ";
			$sql .= "AND		TE.tercero_id = RC.tercero_id ";
			$sql .= "AND		TE.tipo_id_tercero = RC.tipo_id_tercero ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			
			if($this->datos['numero_recibo'])
				$sql .= "AND		RC.recibo_caja = ".$this->datos['numero_recibo']." ";
			
			if($this->datos['fecha_inicio'])
			{
				$arr = explode("/",$this->datos['fecha_inicio']);
				$sql .= "AND		RC.fecha_ingcaja::date >= '".$arr[2]."-".$arr[1]."-".$arr[0]."' ";
			}
			
			if($this->datos['fecha_fin'])
			{
				$arr = explode("/",$this->datos['fecha_fin']);
				$sql .= "AND		RC.fecha_ingcaja::date <= '".$arr[2]."-".$arr[1]."-".$arr[0]."' ";
			}
			
			if($this->datos['usuario'] != 0 || $this->datos['usuario'])
				$sql .= "AND		RC.usuario_id = ".$this->datos['usuario']." ";
				
		//	$sql .= "GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,RC.otros ";
			$sql .= "ORDER BY 1,2 DESC "; 
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i=0;
			while (!$rst->EOF)
			{
				$pago = "";
				$recibos[$i]  = $rst->GetRowAssoc($ToUpper = false);

				if($recibos[$i]['otros'] > 0)	$pago = "OTRO CONCEPTO ";
				if($recibos[$i]['total_cheques'] > 0)	$pago = "CHEQUE ";
				if($recibos[$i]['total_efectivo'] > 0) $pago = "EFECTIVO ";
				if($recibos[$i]['total_tarjetas'] > 0) $pago = "TARJETA ";
				if($recibos[$i]['total_consignacion'] > 0) $pago = "CONSIGNACIÓN ";
				
				$recibos[$i]['forma_pago'] = $pago;
				
				$rst->MoveNext();
				$i++;
		  }
			$rst->Close();
			
			return $recibos;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return $rst;
		}
	}
?>