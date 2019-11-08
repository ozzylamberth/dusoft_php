<?php

/* * ****************************************************************************
 * $Id: app_RecibosCaja_Funciones.class.php,v 1.2 2010/03/29 16:21:07 sandra Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * $Revision: 1.2 $ 
 * 
 * @autor Hugo F  Manrique 
 * ****************************************************************************** */

class app_RecibosCaja_Funciones {

    function app_RecibosCaja_Funciones()
    {
        
    }

    /*     * ****************************************************************************
     *
     * ***************************************************************************** */

    function ObtenerInformacionRecibo($datos, $rc_tipo, $empresa)
    {
        $sql .= "SELECT	observacion, ";
        $sql .= "				TO_CHAR(fecha_ingcaja,'DD/MM/YYYY') AS fecha_registro, ";
        $sql .= "				fecha_ingcaja::date AS fecha ";
        $sql .= "FROM		tmp_recibos_caja ";
        $sql .= "WHERE	tmp_recibo_id = " . $datos['recibo_caja'] . " ";
        $sql .= "AND		empresa_id = '" . $empresa . "' ";
        $sql .= "AND		tercero_id = '" . $datos['tercero_id'] . "' ";
        $sql .= "AND		tipo_id_tercero = '" . $datos['tercero_tipo'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        $sql = "SELECT TO_CHAR(MAX(RC.fecha_ingcaja),'DD/MM/YYYY') AS fecha_limite, ";
        $sql .= "				MAX(RC.fecha_ingcaja)::date AS limite ";
        $sql .= "FROM 	recibos_caja RC ";
        $sql .= "WHERE	RC.rc_tipo_documento = " . $rc_tipo . " ";
        $sql .= "AND		RC.estado = '2' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos1 = array();
        if (!$rst->EOF)
        {
            $datos1 = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        $datos['fecha_limite'] = $datos1['fecha_limite'];
        $datos['limite'] = $datos1['limite'];
        return $datos;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ActualizarDatos($datos, $actualizar, $empresa)
    {
        $sql .= "UPDATE tmp_recibos_caja ";
        $sql .= "SET		observacion	= '" . $actualizar['observacion'] . "', ";
        $sql .= "				fecha_registro	= '" . $actualizar['fecha_registro'] . "' ";
        $sql .= "WHERE	tercero_id = '" . $datos['tercero_id'] . "' ";
        $sql .= "AND		tipo_id_tercero = '" . $datos['tercero_tipo'] . "' ";
        $sql .= "AND		tmp_recibo_id = " . $datos['recibo_caja'] . " ";
        $sql .= "AND		empresa_id = '" . $empresa . "' ";

        if (!$this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    /*     * ****************************************************************************
     *
     * ***************************************************************************** */

    function ObtenerMenuRecibos($empresa, $centro_utilidad)
    {
        $sql .= "SELECT	rc_tipo_documento, ";
        $sql .= "	descripcion, ";
        $sql .= "	documento_id, ";
        $sql .= "	sw_cruce_endosos, ";
        $sql .= "	sw_cruzar_anticipos, ";
        $sql .= "       sw_cruce_recibos ";
        $sql .= "FROM rc_tipos_documentos ";
        $sql .= "WHERE empresa_id = '" . $empresa . "' ";
        $sql .= "AND centro_utilidad = '" . $centro_utilidad . "' ";
        $sql .= "ORDER BY rc_tipo_documento ";

        //echo "sql:$sql<br>";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /*     * ****************************************************************************
     *
     * ***************************************************************************** */

    function ObtenerSaldoAnticipos($datos, $empresa, $documento)
    {
        $sql = "SELECT RA.saldo - COALESCE(TM.total_abono,0) AS saldo ";
        $sql .= "FROM		rc_control_anticipos RA ";
        $sql .= "				LEFT JOIN (";
        $sql .= "					SELECT 	SUM(total_abono) AS total_abono,tercero_id,tipo_id_tercero,empresa_id ";
        $sql .= "					FROM		tmp_recibos_caja  ";
        $sql .= "					WHERE		tipo_id_tercero = '" . $datos['tercero_tipo'] . "' ";
        $sql .= "					AND			tercero_id = '" . $datos['tercero_id'] . "' ";
        $sql .= "					AND			empresa_id = '" . $empresa . "' ";
        $sql .= "					AND			rc_tipo_documento = " . $documento . " ";
        $sql .= "					GROUP BY tercero_id,tipo_id_tercero,empresa_id ";
        $sql .= "				) AS TM ";
        $sql .= "				ON( RA.tipo_id_tercero = TM.tipo_id_tercero AND";
        $sql .= "						RA.tercero_id = TM.tercero_id AND";
        $sql .= "						RA.empresa_id = TM.empresa_id ) ";
        $sql .= "WHERE	RA.tipo_id_tercero = '" . $datos['tercero_tipo'] . "' ";
        $sql .= "AND		RA.tercero_id = '" . $datos['tercero_id'] . "' ";
        $sql .= "AND		RA.empresa_id = '" . $empresa . "' ";
        
        
//        echo $sql;
        
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos['saldo'];
    }

    /*     * *************************************************************************************
     * Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta sql 
     * 
     * @param string sentencia sql a ejecutar 
     * @return rst 
     * ************************************************************************************** */

    function ConexionBaseDatos($sql)
    {
        list($dbconn) = GetDBConn();
        //$dbconn->debug = true;
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->parametro = "MensajeError";
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg() . "<br>$sql";
            return false;
        }
        return $rst;
    }

    function getEncabezadoRCWSFI($rc_id, $prefijo)
    {
        $sql = "";
        $sql .= "   SELECT  RC.*,
                        TO_CHAR(RC.fecha_registro, 'DD-MM-YYYY') as fecha_registro 
                FROM    recibos_caja RC
                WHERE   RC.recibo_caja = '" . $rc_id . "'
                AND     RC.prefijo = '" . $prefijo . "'; ";

        // echo $sql;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function getFacturasDFIN1121($rc_id, $prefijo)
    {
        $sql = "SELECT 
                    '0' AS centro_costo,
                    '0' AS centro_utilidad,
                    '0' AS linea_costo,
                    RES.estado_1121,
                    RES.valor_abonado_rt
                  FROM (
                            SELECT u.prefijo
                                            , u.recibo_caja
                                            , 1 AS estado_1121        
                                            , SUM(valor_abonado) AS valor_abonado_rt
                                          FROM rc_detalle_tesoreria_facturas u
                                          WHERE u.prefijo = '" . $prefijo . "' 
                                            AND u.recibo_caja = " . $rc_id . " 
                                          GROUP BY 1, 2, 3
                  )AS RES 	";

        //  echo "SQL Detalle: " . $sql;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function getDetalleRCWSFI($rc_id, $prefijo)
    {
        $sql = "";

		$sql = "SELECT DISTINCT
					RCT.cuenta AS cuenta_credito,
					'1010' AS centro_costo,
					'03' AS centro_utilidad,
					'01' AS linea_costo,
					RCT.cuenta AS cuenta_concepto,
					RDTC.concepto_id,
					RDTC.valor,
					RCT.sw_naturaleza AS naturaleza
					FROM  rc_detalle_tesoreria_conceptos RDTC
					LEFT JOIN rc_conceptos_tesoreria RCT ON ( RCT.concepto_id = RDTC.concepto_id AND RCT.empresa_id = RDTC.empresa_id )
			WHERE RDTC.prefijo= '" . $prefijo . "'
				AND RDTC.recibo_caja = " . $rc_id . "; ";
		
        /*$sql = "SELECT  DISTINCT
				RCT1.cuenta AS cuenta_credito,
				'0' AS centro_costo,
				'0' AS centro_utilidad,
				'0' AS linea_costo,
				RCT.cuenta as cuenta_concepto,
				RDTC.concepto_id,
				RDTC.valor,
				RCT.sw_naturaleza as naturaleza
			FROM facturas_rc_detalles RDTF 
				LEFT JOIN fac_facturas FF ON (FF.empresa_id = RDTF.empresa_id AND FF.prefijo = RDTF.prefijo_factura AND FF.factura_fiscal = RDTF.factura_fiscal) 
				LEFT JOIN rc_detalle_tesoreria_conceptos RDTC ON (
					RDTC.empresa_id = RDTF.empresa_id
					AND RDTC.centro_utilidad = RDTF.centro_utilidad
					AND RDTC.prefijo=RDTF.rc_prefijo_tras 
					AND RDTC.recibo_caja=RDTF.rc_id_tras
				) 
				LEFT JOIN rc_conceptos_tesoreria RCT ON (RCT.concepto_id = RDTC.concepto_id
					AND RCT.empresa_id = RDTC.empresa_id)
				LEFT JOIN rc_detalle_tesoreria_conceptos RDTC1 ON (
					RDTC1.prefijo=RDTF.prefijo_rc
					AND RDTC1.recibo_caja=RDTF.recibo_caja
				) 
				LEFT JOIN rc_conceptos_tesoreria RCT1 ON (RCT1.concepto_id = RDTC1.concepto_id
					AND RCT1.empresa_id = RDTC1.empresa_id)
			WHERE RDTF.rc_prefijo_tras = '" . $prefijo . "'
				AND RDTF.rc_id_tras = " . $rc_id . "; ";*/
				
				
				
				


        //echo "SQL Detalle: ".$sql;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function getDocumentoEncabezadoFI($doc_id, $empresa_id)
    {
        $sql = "";
        $sql .= "SELECT prefijo_fi 
                 FROM   documentos
                 WHERE  documento_id = '" . $doc_id . "' 
                 AND    empresa_id = '" . $empresa_id . "'; ";

        // echo $sql;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$datos = array();
        if (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function getCuenta1121($tercero_id, $tipo_id)
    {
        $sql = "SELECT u.cuenta_contable as cuenta
                                FROM terceros_clientes u
                                WHERE u.tipo_id_tercero = '" . $tipo_id . "' 
                                  AND u.tercero_id = '" . $tercero_id . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function registrar_resultado_sincronizacion($prefijo, $numerodoc, $mensaje, $estado, $numero_fi = 0)
    {


        $sql = " select * from logs_recibos_ws_fi where prefijo = '{$prefijo}' and  numero_documento = {$numerodoc} ;";
        
        

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $datos = Array();
        while (!$resultado->EOF)
        {
            $datos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();


        $sql = " update logs_recibos_ws_fi set mensaje='{$mensaje}', estado='{$estado}' where prefijo = '{$prefijo}' and  numero_documento = {$numerodoc} ;";

        if (count($datos) == 0)
        {
            $sql = " INSERT INTO logs_recibos_ws_fi (prefijo, numero_documento, mensaje, estado,numero_fi ) VALUES ('{$prefijo}', {$numerodoc}, '{$mensaje}', '{$estado}', '{$numero_fi}'); ";
        }
        
      // echo $sql;


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

}

?>