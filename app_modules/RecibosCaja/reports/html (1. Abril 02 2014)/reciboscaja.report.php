<?php

	/**************************************************************************************
	* $Id: reciboscaja.report.php,v 1.2 2010/03/29 16:21:36 sandra Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/

	class reciboscaja_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
		var $datos;
		var $menu;
		
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
	  function reciboscaja_report($datos=array())
	  {
			$this->datos=$datos;
			if(!$this->datos['empresa_id'])
				$this->datos['empresa_id'] = $_SESSION['RCFactura']['empresa'];
	    return true;
	  }
		
		function GetMembrete()
		{
			$this->ObtenerObservacion();
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b $estilo>".strtoupper(trim($this->menu['descripcion']))." Nº ".$this->datos['prefijo']." ".$this->datos['recibo_caja']."</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px;\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px; text-align:center\""; 
			$this->ObtenerObservacion();
			$label = "";
			switch ($this->menu['tipo_pago'])
			{
				case '0':
					$this->ObtenerFechaCheque();
					$label = "FECHA CHEQUE:";
				break;
				case '1':
					$this->ObtenerFechaConsignacion();
					$label = "FECHA TRANSACCION:";
				break;
			}
                        
                        /*$estilo_agua .= "style = '
                                        color: #d0d0d0;
                                        font-size: 80pt;
                                        -webkit-transform: rotate(-45deg);
                                        -moz-transform: rotate(-45deg);
                                        position: absolute;
                                        width: 100%;
                                        height: 100%;
                                        margin: 100;
                                        z-index: -1;
                                        left:-100px;
                                        top:-200px;
                                        '";
                        $Salida .= "<div $estilo_agua> ANULADO </div>";*/
			$Salida .= "<table border=\"0\" width=\"100%\" align=\"center\">\n";
			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td $estilo2 width=\"15%\"><b>CLIENTE:&nbsp;</b></td>\n";			
			$Salida .= "		<td $estilo2 colspan=\"3\"><b>".$this->datos['tipo_id_tercero']."</b>\n";
			$Salida .= "		<b>".$this->datos['tercero_id']."</b>\n";
			$Salida .= "		<b>".$this->datos['tercero_nombre']."</b></td>\n";
			$Salida .= "	</tr>\n";

			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td $estilo2 width=\"10%\"><b>FECHA CREACION:&nbsp;</b></td>\n";			
			$Salida .= "		<td $estilo2 width=\"15%\"><b>".$this->menu['fecha_ingcaja']."</b></td>\n";
			$Salida .= "		<td $estilo2 width=\"18%\"><b>".$label."&nbsp;</b></td>\n";			
			$Salida .= "		<td $estilo2 ><b>".$this->fecha_transaccion."&nbsp;</b></td>\n";
			$Salida .= "	</tr>\n";
			
			$Salida .= "	<tr height=\"21\">\n";
			$Salida .= "		<td $estilo2 width=\"10%\"><b>USUARIO:&nbsp;</b></td>\n";			
			$Salida .= "		<td $estilo2 colspan=\"3\"><b>".$this->menu['usuario']."</b></td>\n";
			$Salida .= "	</tr>\n";
			
			if($this->menu['nombre_tercero'])
			{
				$Salida .= "	<tr height=\"21\">\n";
				$Salida .= "		<td $estilo2 width=\"10%\"><b>TERCERO ENDOSO:&nbsp;</b></td>\n";			
				$Salida .= "		<td $estilo2 colspan=\"3\"><b>".$this->menu['nombre_tercero']."</b></td>\n";
				$Salida .= "	</tr>\n";
			}
			
			if($this->Observacion)
			{
				$Salida .= "	<tr>\n";
				$Salida .= "		<td $estilo2 ><b>OBSERVACIÓN:</b></td>\n";
				$Salida .= "		<td $estilo2 colspan=\"3\">".$this->Observacion."</td>\n";
				$Salida .= "	</tr>\n";
			}
                        $cuenta_bancaria = $this->getCuentaBancaria($this->datos['recibo_caja'], $this->datos['empresa_id'], $this->datos['prefijo']);
                        $Salida .= "	<tr>\n";
                        $Salida .= "		<td $estilo2 ><b>CUENTA BANCARIA:</b></td>\n";
                        $Salida .= "		<td $estilo2 colspan=\"3\">".$cuenta_bancaria['numero_cuenta']."</td>\n";
                        $Salida .= "	</tr>\n";
                        
			$Salida .= "</table><br>\n";
			
			$Salida .= "<table width=\"100%\" border=\"1\" bordercolor=\"#000000\"  align=\"center\" cellpading=\"0\" cellspacing=\"0\">\n";
			$Salida .= "	<tr $estilo3 height=\"16\">\n";
			$Salida .= "		<td width=\"50%\"><b>CONCEPTOS</b></td>\n";
			$Salida .= "		<td width=\"30%\"><b>DEPARTAMENTO</b></td>\n";
			$Salida .= "		<td width=\"10%\"><b>DEBITO</b></td>\n";
			$Salida .= "		<td width=\"10%\"><b>CREDITO</b></td>\n";
			$Salida .= "	</tr>\n";				
			$Salida .= "	<tr $estilo height=\"19\">\n";
			$Salida .= "		<td colspan=\"2\"><b>VALOR ".strtoupper(trim($this->menu['descripcion']))."</b></td>\n";
			$Salida .= "		<td align=\"right\">".formatoValor($this->datos['valor_recibo'])."</td>\n";
			$Salida .= "		<td align=\"right\">0</td>\n";
			$Salida .= "	</tr>\n";

			$c = $d = 0;
			$d += $this->datos['valor_recibo'];
			$ConceptosV = $this->ObtenerValorConceptos();
			$Facturas = $this->ObtenerFacturasCruzadasRC();			
			
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
					
					$Salida .= "	<tr $estilo height=\"19\">\n";
					$Salida .= "		<td ><b>".$Celdas['descripcion']."</b></td>\n";
					$Salida .= "		<td ><b>".$Celdas['departamento']."</b></td>\n";
					$Salida .= "		<td align=\"right\">".$debito."</td>\n";
					$Salida .= "		<td align=\"right\">".$credito."</td>\n";
					$Salida .= "	</tr>\n";
				}
			}
			
			$c += $this->TotalFactura;
			
			$Salida .= "	<tr $estilo height=\"19\">\n";
			$Salida .= "		<td colspan=\"2\"><b>TOTAL ABONO FACTURAS</b></td>\n";
			$Salida .= "		<td align=\"right\">0</td>\n";
			$Salida .= "		<td align=\"right\">".formatoValor($this->TotalFactura)."</td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "	<tr $estilo height=\"19\">\n";
			$Salida .= "		<td $estilo3 colspan=\"2\"><b>TOTAL</b></td>\n";
			$Salida .= "		<td align=\"right\"><b>$".formatoValor($d)."</b></td>\n";
			$Salida .= "		<td align=\"right\"><b>$".formatoValor($c)."</b></td>\n";
			$Salida .= "	</tr>\n";
			$Salida .= "</table><br>\n";

			if(sizeof($Facturas) > 0)
			{
				$total_factura = $retencion = 0;
				
				$Salida .= "<br><b  $estilo3><center>DETALLE DEL CRUCE DE FACTURAS</center></b>\n";
				$Salida .= "<table width=\"100%\" align=\"center\" border=\"1\" bordercolor=\"#000000\" cellpading=\"0\" cellspacing=\"0\" >\n";
				$Salida .= "	<tr $estilo3 height=\"21\">\n";
				$Salida .= "		<td width=\"16%\"><b>FACTURA</b></td>\n";
				$Salida .= "		<td width=\"10%\"><b>RECIBO CAJA</b></td>\n";
				$Salida .= "		<td width=\"6%\"><b>VALOR RECIBO CAJA</b></td>\n";
				$Salida .= "		<td width=\"16%\"><b>FECHA</b></td>\n";
				$Salida .= "		<td width=\"17%\"><b>TOTAL</b></td>\n";
				$Salida .= "		<td width=\"17%\"><b>RETENCION</b></td>\n";
				$Salida .= "		<td width=\"17%\"><b>ABONO</b></td>\n";
				$Salida .= "		<td width=\"17%\"><b>SALDO</b></td>\n";
				$Salida .= "	</tr>\n";
				
				for($i=0; $i<sizeof($Facturas); $i++)
				{
                                    $datos_rc = "";
                                    $datos_rc_1 = "";
                                    $datos_rc_2 = "";
                                    $detalles_rc_fac = $this->obtenerDetalleRcFacturas($this->datos['recibo_caja'],$this->datos['prefijo'],$Facturas[$i]['prefijo'],$Facturas[$i]['factura_fiscal']);
					$saldo = $Facturas[$i]['saldo'];				
					if($saldo < 0) $saldo = 0;
										
					$Salida .= "	<tr $estilo height=\"18\">\n";					
					$Salida .= "		<td rowspan='".count($detalles_rc_fac)."' aling=\"left\"  >".$Facturas[$i]['prefijo']." ".$Facturas[$i]['factura_fiscal']."</td>\n";
                                        
                                        for($f=0;$f<count($detalles_rc_fac);$f++)
                                        {
                                            if($f==0)
                                            {
                                                $datos_rc_1 .= $detalles_rc_fac[$f]['prefijo_rc']." ".$detalles_rc_fac[$f]['recibo_caja'];
                                                $datos_rc_2 .= formatoValor($detalles_rc_fac[$f]['valor_detalle']);
                                            }
                                            else
                                            {
                                                $datos_rc .= "<tr $estilo height='18'>
                                                                <td align=\"center\" >".$detalles_rc_fac[$f]['prefijo_rc']." ".$detalles_rc_fac[$f]['recibo_caja']."</td>
                                                                <td align=\"center\" >".formatoValor($detalles_rc_fac[$f]['valor_detalle'])."</td>
                                                              </tr>";
                                            }
                                        }
                                        $Salida .= "            <td align=\"center\" >$datos_rc_1</td>";
                                        $Salida .= "            <td align=\"center\" >$datos_rc_2</td>";
                                        
					$Salida .= "		<td rowspan='".count($detalles_rc_fac)."' align=\"center\">".$Facturas[$i]['registro']."</td>\n";
					$Salida .= "		<td rowspan='".count($detalles_rc_fac)."' align=\"right\" >".formatoValor($Facturas[$i]['total_factura'])."&nbsp;</td>\n";
					$Salida .= "		<td rowspan='".count($detalles_rc_fac)."' align=\"right\" >".formatoValor($Facturas[$i]['retencion_fuente'])."&nbsp;</td>\n";
					$Salida .= "		<td rowspan='".count($detalles_rc_fac)."' align=\"right\" >".formatoValor($Facturas[$i]['abono'])."&nbsp;</td>\n";
					$Salida .= "		<td rowspan='".count($detalles_rc_fac)."' align=\"right\" >".formatoValor($saldo)."&nbsp;</td>\n";
					$Salida .= "	</tr>";
                                        $Salida .= $datos_rc;
					
					$sld += $saldo;
					$total += $Facturas[$i]['total_factura'];
					$retencion += $Facturas[$i]['retencion_fuente'];
					$total_factura += $Facturas[$i]['abono'];
				}
				
				$Salida .= "	<tr $estilo3 height=\"21\">\n";
				$Salida .= "		<td colspan=\"4\"><b>TOTALES</b></td>\n";
				$Salida .= "		<td align=\"right\"><b>$".FormatoValor($total)."&nbsp;</b></td>\n";
				$Salida .= "		<td align=\"right\"><b>$".FormatoValor($retencion)."&nbsp;</b></td>\n";
				$Salida .= "		<td align=\"right\"><b>$".FormatoValor($total_factura)."&nbsp;</b></td>\n";
				$Salida .= "		<td align=\"right\"><b>$".FormatoValor($sld)."&nbsp;</b></td>\n";
				$Salida .= "	</tr>\n";
				$Salida .= "</table><br>\n";
			}
			
			$usuario = $this->ObtenerUsuarioNombre(UserGetUID());
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
                
                //STEVEN
                
                function getCuentaBancaria($id,$empresa,$prefijo)
                {
                    $sql = "";
                    $sql .= "SELECT numero_cuenta 
                             FROM   bancos_consignaciones 
                             WHERE  empresa_id = '".$empresa."' 
                             AND    recibo_caja = '".$id."' 
                             AND    prefijo = '".$prefijo."' ; ";
                    //echo $sql;
                    if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
                    if(!$rst->EOF)
                    {
                        $datos =  $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
                    }
                    
                    $rst->Close();
                    return $datos;
                }
                
                function obtenerDetalleRcFacturas($rc_id_tras,$rc_prefijo_tras,$prefijo_factura,$factura_fiscal)
                {
                    $sql = "";
                    $sql .= "   SELECT  recibo_caja, 
                                        prefijo_rc, 
                                        valor_detalle
                                FROM    facturas_rc_detalles
                                WHERE   rc_id_tras = '".$rc_id_tras."'
                                AND	rc_prefijo_tras = '".$rc_prefijo_tras."'
                                AND     prefijo_factura = '".$prefijo_factura."' 
                                AND     factura_fiscal = '".$factura_fiscal."' 
                                ORDER BY recibo_caja; ";
                    
                    if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
                    while(!$rst->EOF)
                    {
                        $datos[] =  $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
                    }
                    
                    $rst->Close();
                    return $datos;
                }
                //FIN ST
		/***************************************************************************************
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
		* 
		* @return array datos de los conceptos 
		****************************************************************************************/
		function ObtenerObservacion()
		{
			$sql .= "SELECT	RC.observacion, ";			
			$sql .= "				TD.descripcion, ";			
			$sql .= "				TO_CHAR(RC.fecha_ingcaja,'DD/MM/YYYY') AS fecha_ingcaja, ";			
			$sql .= "				SU.nombre, ";			
			$sql .= "				TE.nombre_tercero, ";			
			$sql .= "				CASE 	WHEN total_cheques > 0 THEN '0' ";			
			$sql .= "							WHEN total_consignacion > 0 THEN '1' ";			
			$sql .= "							ELSE '2' END AS tipo_pago ";			
			$sql .= "FROM 	recibos_caja RC ";
			$sql .= "				LEFT JOIN terceros TE ";
			$sql .= "				ON(	RC.tercero_id_endoso = TE.tercero_id AND ";
			$sql .= "						RC.tipo_id_tercero_endoso = TE.tipo_id_tercero), ";
			$sql .= "				rc_tipos_documentos TD, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	RC.recibo_caja = ".$this->datos['recibo_caja']." ";
			$sql .= "AND		RC.prefijo = '".$this->datos['prefijo']."' ";	
			$sql .= "AND		RC.empresa_id = '".$this->datos['empresa_id']."' ";
			$sql .= "AND		RC.rc_tipo_documento = TD.rc_tipo_documento ";
			$sql .= "AND		RC.usuario_id = SU.usuario_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
//echo "ObtenerObservacion ".$sql;
			if(!$rst->EOF)
			{
				$this->Observacion =  $rst->fields[0];
				$this->menu['descripcion'] =  $rst->fields[1];
				$this->menu['fecha_ingcaja'] =  $rst->fields[2];
				$this->menu['usuario'] =  $rst->fields[3];
				$this->menu['nombre_tercero'] =  $rst->fields[4];
				$this->menu['tipo_pago'] =  $rst->fields[5];
				$rst->MoveNext();
		  }
			$rst->Close();
		}
		/***************************************************************************************
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
		* 
		* @return array datos de los conceptos 
		****************************************************************************************/
		function ObtenerFechaCheque()
		{
			$sql .= "SELECT	TO_CHAR(fecha_cheque,'DD/MM/YYYY') ";
			$sql .= "FROM 	cheques_mov ";
			$sql .= "WHERE	recibo_caja = ".$this->datos['recibo_caja']." ";
			$sql .= "AND		prefijo = '".$this->datos['prefijo']."' ";	
			$sql .= "AND		empresa_id = '".$this->datos['empresa_id']."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$this->fecha_transaccion = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
		}
		/***************************************************************************************
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
		* 
		* @return array datos de los conceptos 
		****************************************************************************************/
		function ObtenerFechaConsignacion()
		{
			$sql .= "SELECT	TO_CHAR(fecha_transaccion,'DD/MM/YYYY') ";
			$sql .= "FROM 	bancos_consignaciones ";
			$sql .= "WHERE	recibo_caja = ".$this->datos['recibo_caja']." ";
			$sql .= "AND		prefijo = '".$this->datos['prefijo']."' ";	
			$sql .= "AND		empresa_id = '".$this->datos['empresa_id']."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$this->fecha_transaccion = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
		}
		/***************************************************************************************
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
		* 
		* @return array datos de los conceptos 
		****************************************************************************************/
		function ObtenerValorConceptos()
		{
			$sql .= "SELECT	COALESCE(RCT.valor,0) AS valor,";
			$sql .= " 			RCT.naturaleza, ";
			$sql .= " 			RC.descripcion, ";
			$sql .= " 			COALESCE(DE.descripcion,'NO ASOCIADO') AS departamento  ";			
			$sql .= "FROM 	rc_conceptos_tesoreria RC, ";
			$sql .= "				rc_detalle_tesoreria_conceptos RCT ";
			$sql .= "				LEFT JOIN departamentos DE ";
			$sql .= "				ON(DE.departamento = RCT.departamento) ";
			$sql .= "WHERE	RCT.recibo_caja = ".$this->datos['recibo_caja']." ";
			$sql .= "AND		RCT.prefijo = '".$this->datos['prefijo']."' ";	
			$sql .= "AND		RCT.empresa_id = '".$this->datos['empresa_id']."' ";
			$sql .= "AND		RC.empresa_id = '".$this->datos['empresa_id']."' ";
			$sql .= "AND		RCT.concepto_id = RC.concepto_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[] =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
//echo $sql;			
			return $datos;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ObtenerFacturasCruzadasRC()
		{
			$sql .= "SELECT	FF.prefijo, ";
			$sql .= "				FF.factura_fiscal, ";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				FF.retencion_fuente, ";
			$sql .= "				FF.estado, ";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS registro, ";
			$sql .= "				SUM(RC.valor_abonado) AS abono ";
			$sql .= "FROM		view_fac_facturas FF, "; 
			$sql .= "				rc_detalle_tesoreria_facturas RC ";
			$sql .= "WHERE	RC.recibo_caja = ".$this->datos['recibo_caja']." ";
			$sql .= "AND		RC.prefijo = '".$this->datos['prefijo']."' ";
			$sql .= "AND		RC.empresa_id = '".$this->datos['empresa_id']."' ";
			$sql .= "AND		RC.prefijo_factura = FF.prefijo ";
			$sql .= "AND		RC.factura_fiscal = FF.factura_fiscal ";			
			$sql .= "AND		RC.empresa_id = FF.empresa_id ";			
			$sql .= "GROUP BY 1,2,3,4,5,6,7 ";
			$sql .= "UNION ALL ";
			$sql .= "SELECT IFD.prefijo as prefijo, 
                                        IFD.factura_fiscal as factura_fiscal,
                                        IFD.valor_total as total_factura,
                                        IFD.saldo as saldo,
                                        0.00 as retencion_fuente,
                                        '0' as estado,
                                        TO_CHAR(IFD.fecha_registro,'DD/MM/YYYY') AS registro,
                                        SUM(RC.valor_abonado) AS abono 
                                 FROM   inv_facturas_despacho IFD 
                                 INNER JOIN rc_detalle_tesoreria_facturas RC ON(RC.prefijo_factura = IFD.prefijo AND RC.factura_fiscal=IFD.factura_fiscal) 
                                 WHERE RC.recibo_caja = ".$this->datos['recibo_caja']." 
                                 AND   RC.prefijo = '".$this->datos['prefijo']."' 
                                 AND   RC.empresa_id = '".$this->datos['empresa_id']."' ";
                        $sql .= " GROUP BY 1,2,3,4,5,6,7 ";
			$sql .= " ORDER BY 1,2 ";
			//$sql .= " ORDER BY FF.prefijo,FF.factura_fiscal ";
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
//echo "ObtenerFacturasCruzadasRC ".$sql;
			$this->TotalFactura = 0;
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$this->TotalFactura += $rst->fields[7];
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ObtenerUsuarioNombre($id)
		{
			$sql  = "SELECT U.nombre, A.usuario_id ";
			$sql .= "FROM 	system_usuarios U LEFT JOIN auditores_internos A ";
			$sql .= "				ON(U.usuario_id = A.usuario_id) ";
			$sql .= "WHERE 	U.usuario_id = ".$id;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$usuario = array();
			if (!$rst->EOF)
			{
				$usuario = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $usuario;
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
	    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	    //---------------------------------------
	}
?>