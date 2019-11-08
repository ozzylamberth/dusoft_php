<?php

/* * ************************************************************************************
 * GENERA REPORTE TEMPORAL DE LOS RECIBOS DE CAJA CON TABLAS tmp
 * 
 * @author: Steven H. Gamboa
 * @fecha: 16-XI-2012
 * 
 * ************************************************************************************ */

class recibosxfactura_report {

    //VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
    var $datos;
    var $menu;
    //PARAMETROS PARA LA CONFIGURACION DEL REPORTE
    //NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
    var $title = '';
    var $author = '';
    var $sizepage = 'leter';
    var $Orientation = '';
    var $grayScale = false;
    var $headers = array();
    var $footers = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function recibosxfactura_report($datos=array()) {
        $this->datos = $datos;
        if (!$this->datos['empresa_id'])
            $this->datos['empresa_id'] = $_SESSION['RCFactura']['empresa'];
        return true;
    }

    function GetMembrete() {
        $this->ObtenerObservacion();
        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
//        $titulo .= "<b $estilo>" . strtoupper(trim($this->menu['descripcion'])) . " Nº " . $this->datos['numero_documento'] . " " . $this->datos['recibo_caja'] . "</b>";

        $Membrete = array('file' => false, 'datos_membrete' => array('titulo' => $titulo,
                'subtitulo' => ' ',
                'logo' => 'logocliente.png',
                'align' => 'left'));
        return $Membrete;
    }

    //FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    function CrearReporte() {

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px\"";
        $estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px;\"";
        $estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px; text-align:center\"";
        /*
          $this->ObtenerObservacion();
          $label = "";
          switch ($this->menu['tipo_pago']) {
          case '0':
          $this->ObtenerFechaCheque();
          $label = "FECHA CHEQUE:";
          break;
          case '1':
          $this->ObtenerFechaConsignacion();
          $label = "FECHA TRANSACCION:";
          break;
          }
         */
        $nombre = $this->ObtenerTercero($this->datos[empresa_id], $this->datos[prefijo], $this->datos[factura_fiscal]);

        $usuario_descripcion = $this->ObtenerDescripcionUsuario($this->datos['usuario']);
        $Salida .= "<table border=\"0\" width=\"100%\" align=\"center\">\n";
        $Salida .= "	<tr height=\"21\">\n";
        $Salida .= "		<td $estilo2 width=\"15%\"><b>CLIENTE:&nbsp;</b></td>\n";
        $Salida .= "		<td $estilo2 colspan=\"3\"><b>" . $this->datos['tipo_id_tercero'] . "</b>\n";
        $Salida .= "		<b>" . $nombre['tercero_id'] . "</b>\n";
        $Salida .= "		<b>" . $nombre['nombre_tercero'] . "</b></td>\n";
        $Salida .= "	</tr>\n";

        $Salida .= "	<tr height=\"21\">\n";
        $Salida .= "		<td $estilo2 width=\"10%\"><b>FECHA CREACION:&nbsp;</b></td>\n";
        $Salida .= "		<td $estilo2 width=\"15%\"><b>" . $this->datos['fecha_creacion'] . "</b></td>\n";
        $Salida .= "		<td $estilo2 width=\"18%\"><b>" . $label . "&nbsp;</b></td>\n";
        $Salida .= "		<td $estilo2 ><b>" . $this->fecha_transaccion . "&nbsp;</b></td>\n";
        $Salida .= "	</tr>\n";

        $Salida .= "	<tr height=\"21\">\n";
        $Salida .= "		<td $estilo2 width=\"10%\"><b>USUARIO:&nbsp;</b></td>\n";
        $Salida .= "		<td $estilo2 colspan=\"3\"><b>" . $usuario_descripcion[nombre] . "</b></td>\n";
        $Salida .= "	</tr>\n";

        /* if($this->menu['nombre_tercero'])
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
          } */
        $Salida .= "</table><br>\n";

        $Salida .= "<table width=\"100%\" border=\"1\" bordercolor=\"#000000\"  align=\"center\" cellpading=\"0\" cellspacing=\"0\">\n";

        $c = $d = 0;

		echo'<pre>';
		print_r($this->datos);
		echo'<pre>';
		
        $d += $this->datos['valor_documento'];
        $ConceptosV = $this->ObtenerDetallePagoFactura($this->datos[numero_documento], $this->datos[empresa_id]);
        //$ConceptosV = $this->ObtenerValorConceptos();
        $Facturas = $this->ObtenerFacturasTmp($this->datos[empresa_id], $this->datos[prefijo], $this->datos[factura_fiscal]);
        //$Facturas = $this->ObtenerFacturasCruzadasRC();			
        //$Facturas = $this->ObtenerFacturasTmp($this->datos[numero_documento]);

        if (sizeof($Facturas) > 0) {
            $total_factura = $retencion = 0;

            $Salida .= "<br><b  $estilo3><center>DETALLE DEL CRUCE DE FACTURAS</center></b>\n"; //-21-1
            $Salida .= "<table width=\"100%\" align=\"center\" border=\"1\" bordercolor=\"#000000\" cellpading=\"0\" cellspacing=\"0\" >\n";
            $Salida .= "	<tr $estilo3 height=\"21\">\n";
            $Salida .= "		<td width=\"16%\"><b>FACTURA</b></td>\n";
            $Salida .= "		<td width=\"16%\"><b>FECHA</b></td>\n";
            $Salida .= "		<td width=\"17%\"><b>TOTAL</b></td>\n";
            $Salida .= "		<td width=\"17%\"><b>RETENCION</b></td>\n";
            $Salida .= "		<td width=\"17%\"><b>ABONO</b></td>\n";
            $Salida .= "		<td width=\"17%\"><b>SALDO</b></td>\n";
            $Salida .= "	</tr>\n";

            for ($i = 0; $i < sizeof($Facturas); $i++) {
                $saldo = $Facturas[$i]['saldo'] - $Facturas[$i]['valor_abonado'];
                if ($saldo < 0)
                    $saldo = 0;

                $Salida .= "	<tr $estilo height=\"18\">\n";
                $Salida .= "		<td aling=\"left\"  >" . $Facturas[$i]['prefijo_factura'] . " " . $Facturas[$i]['factura_fiscal'] . "</td>\n";
                $Salida .= "		<td align=\"center\">" . $Facturas[$i]['fecha_registro'] . "</td>\n";
                $Salida .= "		<td align=\"right\" >" . formatoValor($Facturas[$i]['saldo']) . "&nbsp;</td>\n";
                $Salida .= "		<td align=\"right\" >" . formatoValor($Facturas[$i]['retencion_fuente']) . "&nbsp;</td>\n";
                $Salida .= "		<td align=\"right\" >" . formatoValor($Facturas[$i]['valor_abonado']) . "&nbsp;</td>\n";
                $Salida .= "		<td align=\"right\" >" . formatoValor($saldo) . "&nbsp;</td>\n";
                $Salida .= "	</tr>";

                $sld += $saldo;
                $total += $Facturas[$i]['saldo'];
                $retencion += $Facturas[$i]['retencion_fuente'];
                $total_factura += $Facturas[$i]['valor_abonado'];
            }

            $Salida .= "	<tr $estilo3 height=\"21\">\n";
            $Salida .= "		<td colspan=\"2\"><b>TOTALES</b></td>\n";
            $Salida .= "		<td align=\"right\"><b>$" . FormatoValor($total) . "&nbsp;</b></td>\n";
            $Salida .= "		<td align=\"right\"><b>$" . FormatoValor($retencion) . "&nbsp;</b></td>\n";
            $Salida .= "		<td align=\"right\"><b>$" . FormatoValor($total_factura) . "&nbsp;</b></td>\n";
            $Salida .= "		<td align=\"right\"><b>$" . FormatoValor($sld) . "&nbsp;</b></td>\n";
            $Salida .= "	</tr>\n";
            $Salida .= "</table><br>\n";
        }

        $usuario = $this->ObtenerUsuarioNombre(UserGetUID());
        $Salida .= "	<br><table border='0' width=\"100%\">\n";
        $Salida .= "		<tr>\n";
        $Salida .= "			<td align=\"justify\" width=\"50%\">\n";
        $Salida .= "				<font size='1' face='arial'>\n";
        $Salida .= "					Imprimió:&nbsp;" . $usuario['nombre'] . "\n";
        $Salida .= "				</font>\n";
        $Salida .= "			</td>\n";
        $Salida .= "			<td align=\"right\" width=\"50%\">\n";
        $Salida .= "				<font size='1' face='arial'>\n";
        $Salida .= "					Fecha Impresión :&nbsp;&nbsp;" . date("d/m/Y - h:i a") . "\n";
        $Salida .= "				</font>\n";
        $Salida .= "			</td>\n";
        $Salida .= "		</tr>\n";
        $Salida .= "	</table>\n";

        return $Salida;
    }

    /*
     */

    function ObtenerDetallePagoFactura($tmp_recibo_id, $empresa_id) {
        $sql .= "SELECT	COALESCE(RCT.valor,0) AS valor,";
        $sql .= " 			RCT.naturaleza, ";
        $sql .= " 			RC.descripcion, ";
        $sql .= " 			RCT.tmp_rc_id, ";
        $sql .= " 			COALESCE(DE.descripcion,'NO ASOCIADO') AS departamento  ";
        $sql .= "FROM 	tmp_rc_detalle_tesoreria_conceptos RCT ";
        $sql .= "				LEFT JOIN departamentos DE ";
        $sql .= "				ON(DE.departamento = RCT.departamento), ";
        $sql .= "				rc_conceptos_tesoreria RC ";
        $sql .= "WHERE	RCT.tmp_recibo_id = '" . $tmp_recibo_id . "' ";
        $sql .= "AND		RCT.empresa_id = '" . $empresa_id . "' ";
        $sql .= "AND		RCT.concepto_id = RC.concepto_id ";
        $sql .= "AND		RCT.empresa_id = RC.empresa_id; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        //echo $sql;         
        return $datos;
    }

    /*
     * Funcion que trae las facturas asociadas al recibo temporal
     * INPUT: recibo_id
     * 
     * @author: Steven H. Gamboa
     * @fecha: 16-XI-2012
     */

    function ObtenerFacturasTmp($empresa_id, $prefijo, $factura_fiscal) {
        $sql .= "   SELECT TRC.tmp_recibo_id, 
                                       TRDTF.prefijo_factura, 
                                       TRDTF.factura_fiscal, 
                                       TRDTF.valor_abonado , 
                                       FF.total_factura, 
                                       TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') as fecha_registro, 
                                       FF.retencion_fuente, 
                                       FF.saldo 
                                FROM   tmp_recibos_caja TRC, 
                                       tmp_rc_detalle_tesoreria_facturas TRDTF, 
                                       fac_facturas FF 
                                WHERE  FF.empresa_id = '" . $empresa_id . "'
                                AND    FF.prefijo = '" . $prefijo . "'
                                AND    FF.factura_fiscal = '" . $factura_fiscal . "'
                                AND    TRC.tmp_recibo_id = TRDTF.tmp_recibo_id
                                AND    TRDTF.prefijo_factura = FF.prefijo 
                                AND    TRDTF.factura_fiscal = FF.factura_fiscal";
		print_r($sql);

        /*
          $sql .= "   SELECT TRC.tmp_recibo_id,
          TRDTF.prefijo_factura,
          TRDTF.factura_fiscal,
          TRDTF.valor_abonado ,
          FF.total_factura,
          TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') as fecha_registro,
          FF.retencion_fuente,
          FF.saldo
          FROM   tmp_recibos_caja TRC,
          tmp_rc_detalle_tesoreria_facturas TRDTF,
          fac_facturas FF
          WHERE  TRC.tmp_recibo_id = " . $tmp_recibo_id . "
          AND    TRC.tmp_recibo_id = TRDTF.tmp_recibo_id
          AND    TRDTF.prefijo_factura = FF.prefijo
          AND    TRDTF.factura_fiscal = FF.factura_fiscal";
         */
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /*     * *************************************************************************************
     * Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
     * 
     * @return array datos de los conceptos 
     * ************************************************************************************** */

    function ObtenerObservacion() {
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
        $sql .= "WHERE	RC.recibo_caja = " . $this->datos['recibo_caja'] . " ";
        $sql .= "AND		RC.prefijo = '" . $this->datos['prefijo'] . "' ";
        $sql .= "AND		RC.empresa_id = '" . $this->datos['empresa_id'] . "' ";
        $sql .= "AND		RC.rc_tipo_documento = TD.rc_tipo_documento ";
        $sql .= "AND		RC.usuario_id = SU.usuario_id ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$rst->EOF) {
            $this->Observacion = $rst->fields[0];
            $this->menu['descripcion'] = $rst->fields[1];
            $this->menu['fecha_ingcaja'] = $rst->fields[2];
            $this->menu['usuario'] = $rst->fields[3];
            $this->menu['nombre_tercero'] = $rst->fields[4];
            $this->menu['tipo_pago'] = $rst->fields[5];
            $rst->MoveNext();
        }
//echo "ObtenerObservacion ".$sql;
        $rst->Close();
    }

    /*     * *************************************************************************************
     * Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
     * 
     * @return array datos de los conceptos 
     * ************************************************************************************** */

    function ObtenerFechaCheque() {
        $sql .= "SELECT	TO_CHAR(fecha_cheque,'DD/MM/YYYY') ";
        $sql .= "FROM 	cheques_mov ";
        $sql .= "WHERE	recibo_caja = " . $this->datos['recibo_caja'] . " ";
        $sql .= "AND		prefijo = '" . $this->datos['prefijo'] . "' ";
        $sql .= "AND		empresa_id = '" . $this->datos['empresa_id'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$rst->EOF) {
            $this->fecha_transaccion = $rst->fields[0];
            $rst->MoveNext();
        }
        $rst->Close();
    }

    /*     * *************************************************************************************
     * Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
     * 
     * @return array datos de los conceptos 
     * ************************************************************************************** */

    function ObtenerFechaConsignacion() {
        $sql .= "SELECT	TO_CHAR(fecha_transaccion,'DD/MM/YYYY') ";
        $sql .= "FROM 	bancos_consignaciones ";
        $sql .= "WHERE	recibo_caja = " . $this->datos['recibo_caja'] . " ";
        $sql .= "AND		prefijo = '" . $this->datos['prefijo'] . "' ";
        $sql .= "AND		empresa_id = '" . $this->datos['empresa_id'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$rst->EOF) {
            $this->fecha_transaccion = $rst->fields[0];
            $rst->MoveNext();
        }
        $rst->Close();
    }

    /*     * *************************************************************************************
     * Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
     * 
     * @return array datos de los conceptos 
     * ************************************************************************************** */

    function ObtenerValorConceptos() {
        $sql .= "SELECT	COALESCE(RCT.valor,0) AS valor,";
        $sql .= " 			RCT.naturaleza, ";
        $sql .= " 			RC.descripcion, ";
        $sql .= " 			COALESCE(DE.descripcion,'NO ASOCIADO') AS departamento  ";
        $sql .= "FROM 	rc_conceptos_tesoreria RC, ";
        $sql .= "				rc_detalle_tesoreria_conceptos RCT ";
        $sql .= "				LEFT JOIN departamentos DE ";
        $sql .= "				ON(DE.departamento = RCT.departamento) ";
        $sql .= "WHERE	RCT.recibo_caja = " . $this->datos['recibo_caja'] . " ";
        $sql .= "AND		RCT.prefijo = '" . $this->datos['prefijo'] . "' ";
        $sql .= "AND		RCT.empresa_id = '" . $this->datos['empresa_id'] . "' ";
        $sql .= "AND		RCT.concepto_id = RC.concepto_id ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
//echo $sql;			
        return $datos;
    }

    /*     * **********************************************************************************
     *
     * *********************************************************************************** */

    function ObtenerFacturasCruzadasRC() {
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
        $sql .= "WHERE	RC.recibo_caja = " . $this->datos['recibo_caja'] . " ";
        $sql .= "AND		RC.prefijo = '" . $this->datos['prefijo'] . "' ";
        $sql .= "AND		RC.empresa_id = '" . $this->datos['empresa_id'] . "' ";
        $sql .= "AND		RC.prefijo_factura = FF.prefijo ";
        $sql .= "AND		RC.factura_fiscal = FF.factura_fiscal ";
        $sql .= "AND		RC.empresa_id = FF.empresa_id ";
        $sql .= "GROUP BY 1,2,3,4,5,6,7 ";
        $sql .= "ORDER BY FF.prefijo,FF.factura_fiscal ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
//echo "ObtenerFacturasCruzadasRC ".$sql;
        $this->TotalFactura = 0;
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $this->TotalFactura += $rst->fields[7];
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Trae el nombre del Usuario
     */

    function ObtenerDescripcionUsuario($usuario_id) {
        $sql .= "SELECT nombre 
                             FROM   system_usuarios 
                             WHERE  usuario_id = " . $usuario_id . "; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
        $rst->Close();
        return $datos;
    }

    function ObtenerTercero($empresa_id, $prefijo, $factura_fiscal) {
        $sql .= "SELECT t.tercero_id, t.nombre_tercero
                             FROM   terceros t INNER JOIN fac_facturas f ON (f.tipo_id_tercero = t.tipo_id_tercero AND f.tercero_id = t.tercero_id) 
                             WHERE  	
                                f.empresa_id = '" . $empresa_id . "'
                                AND f.prefijo = '" . $prefijo . "'
                                AND f.factura_fiscal = " . $factura_fiscal . "
                                 

; ";
//        print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $usuario = array();
        if (!$rst->EOF) {
            $usuario = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $usuario;
    }

    /*     * ************************************************************************************
     *
     * ************************************************************************************* */

    function ObtenerUsuarioNombre($id) {
        $sql = "SELECT U.nombre, A.usuario_id ";
        $sql .= "FROM 	system_usuarios U LEFT JOIN auditores_internos A ";
        $sql .= "				ON(U.usuario_id = A.usuario_id) ";
        $sql .= "WHERE 	U.usuario_id = " . $id;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $usuario = array();
        if (!$rst->EOF) {
            $usuario = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $usuario;
    }

    /*     * **********************************************************************************
     *
     * *********************************************************************************** */

    function ConexionBaseDatos($sql) {
        list($dbconn) = GetDBConn();
        //$dbconn->debug = true;
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
            return false;
        }

        return $rst;
    }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA
    //---------------------------------------
}

?>