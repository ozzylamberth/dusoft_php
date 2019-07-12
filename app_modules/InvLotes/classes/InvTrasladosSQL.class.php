<?php

class InvTrasladosSQL extends ConexionBD {

    /**
     * Constructor de la clase
     */
    function InvTrasladosSQL()
    {
        
    }

    /**
     * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
     *
     * @return array $datos vector que contiene la informacion de la consulta del codigo de
     * la empresa y la razon social
     */
    function ObtenerPermisos()
    {
        //$this->debug = true;
        $sql = "SELECT   EM.empresa_id AS empresa, ";
        $sql .= "         EM.razon_social AS razon_social ";
        $sql .= "FROM     userpermisos_parametrizacion CP, empresas EM ";
        $sql .= "WHERE    CP.usuario_id = " . UserGetUID() . " ";
        $sql .= "         AND CP.empresa_id = EM.empresa_id ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function ObtenerBodegas($empresa)
    {
        //$this->debug = true;
        $sql = "SELECT 
                CU.descripcion,
                ITU.bodega,
                ITU.centro_utilidad,
                B.descripcion AS descbodega
                FROM inv_trasladosexistencias_userpermisos ITU
                    INNER JOIN bodegas B
                        ON ITU.empresa_id=B.empresa_id AND ITU.centro_utilidad=B.centro_utilidad AND ITU.bodega=B.bodega
                    INNER JOIN centros_utilidad CU 
                        ON ITU.empresa_id=CU.empresa_id AND ITU.centro_utilidad=CU.centro_utilidad
                WHERE ITU.usuario_id=" . UserGetUID() . " AND ITU.empresa_id='" . $empresa . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**
     * Funcion donde se consulta los medicamentos dependiendo los criterios de busqueda
     *
     * @param array $empresa_id variable donde se encuentra el id de la empresa 
     * @param string $descripcion_producto variable donde se guarda la descripcion
     * @param string $codigo_producto variable donde se guarda el codigo del medicamento
     * @return array $datos retorna la consulta.
     */
    function BuscarMedicamento($empresa, $num_pag, $codigo_producto = null, $descripcion_producto = null, $bodega, $centro_utilidad)
    {

        $sql = " SELECT 
            ELF.codigo_producto,
            fc_descrip_producto(ELF.codigo_producto) AS nombre,
            IP.descripcion,
            SUM(ELF.existencia_actual) AS cantidad
            FROM inventarios_productos IP
            INNER JOIN existencias_bodegas_lote_fv ELF
                ON IP.codigo_producto=ELF.codigo_producto
            WHERE TRUE";
        
        if (!empty($descripcion_producto))
        {
            $sql .= " AND IP.descripcion like '%" . $descripcion_producto . "%'";
        }
        if (!empty($codigo_producto))
        {
            $sql .= " AND ELF.codigo_producto like '%" . $codigo_producto . "%' ";
        }

        $sql.= " AND ELF.empresa_id='" . $empresa . "'
            AND ELF.centro_utilidad='" . $centro_utilidad . "'
            AND ELF.bodega='" . $bodega . "'    
            GROUP BY 1,2,3
            ORDER BY ELF.codigo_producto ";

        /*   if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") as data",$num_pag))
          return false;
          $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." "; */

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

    /**
     * Funcion que busca un medicamento en base al codigo enviado como parametro
     *
     * @param string $empresa variable que trae la empresa
     * @param string $codigo_producto variable donde se guarda el codigo del medicamento
     * @return array $datos variable que contiene los datos de la consulta del medicamento.
     */
    function BuscarMedicamentoPorId($empresa, $codigo_producto, $bodega, $centro_utilidad)
    {

        $sql = " SELECT 
            ELF.codigo_producto,
            fc_descrip_producto(ELF.codigo_producto) AS nombre,
            ELF.lote,
            TO_CHAR(ELF.fecha_vencimiento, 'dd-mm-yyyy') AS fecha_vencimiento,
            ELF.empresa_id,
            ELF.bodega,
            ELF.centro_utilidad,
            IP.descripcion,
            ELF.existencia_actual AS cantidad
            FROM inventarios_productos IP
            INNER JOIN existencias_bodegas_lote_fv ELF
                ON IP.codigo_producto=ELF.codigo_producto
            WHERE ELF.codigo_producto = '$codigo_producto'
            AND ELF.empresa_id='" . $empresa . "'
            AND ELF.centro_utilidad='" . $centro_utilidad . "'
            AND ELF.bodega='" . $bodega . "'    
            ORDER BY ELF.codigo_producto ";
        
            //echo $sql;

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

    /**
     * Funcion que realiza actualizacion segun los cambios realizados e inserta en la tabla de log para llevar control de los cambios.
     *
     * @param Array $request trae todos los campos necesarios para la actualizacion
     * @return boolean, true o false, si se ejecutan todas las consultas correctamente
     */
    function ActualizarSaldos($request)
    {

        for ($i = 0; $i < sizeof($request['cantidad']); $i++)
        {

            $sql = " SELECT * FROM existencias_bodegas_lote_fv
                WHERE 	codigo_producto='" . $request['codPro'][$i] . "' AND  empresa_id='" . $request['empresa'][$i] . "'  AND   centro_utilidad='" . $request['cut'][$i] . "'
                AND bodega='" . $request['bodega'][$i] . "' AND lote='" . $request['loteOld'][$i] . "' AND  fecha_vencimiento='" . $request['fVenc'][$i] . "'";
            //AND bodega='".$request['bodega'][$i]."' AND lote='".$request['lote'][$i]."' AND  fecha_vencimiento='".$request['fVenc'][$i]."'";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            $datosOld = array();
            while (!$rst->EOF)
            {
                $datosOld[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();

            $sql = "UPDATE existencias_bodegas_lote_fv
                SET existencia_actual=" . $request['cantidad'][$i] . ", lote = '" . $request['lote'][$i] . "'
                WHERE 	codigo_producto='" . $request['codPro'][$i] . "' AND  empresa_id='" . $request['empresa'][$i] . "'  AND   centro_utilidad='" . $request['cut'][$i] . "'
                AND bodega='" . $request['bodega'][$i] . "' AND lote='" . $request['loteOld'][$i] . "' AND  fecha_vencimiento='" . $request['fVenc'][$i] . "'
              ";
            //$this->debug = true;
            // $sql="UPDATE existencias_bodegas_lote_fv
            // SET existencia_actual=".$request['cantidad'][$i]."
            // WHERE 	codigo_producto='".$request['codPro'][$i]."' AND  empresa_id='".$request['empresa'][$i]."'  AND   centro_utilidad='".$request['cut'][$i]."'
            // AND bodega='".$request['bodega'][$i]."' AND lote='".$request['lote'][$i]."' AND  fecha_vencimiento='".$request['fVenc'][$i]."'
            // ";


            $this->ConexionTransaccion();

            if (!$rst = $this->ConexionTransaccion($sql))
            {
                //return $this->mensajeDeError;
                return false;
            }

            $sql = " SELECT * FROM existencias_bodegas_lote_fv
                WHERE 	codigo_producto='" . $request['codPro'][$i] . "' AND  empresa_id='" . $request['empresa'][$i] . "'  AND   centro_utilidad='" . $request['cut'][$i] . "'
                AND bodega='" . $request['bodega'][$i] . "' AND lote='" . $request['lote'][$i] . "' AND  fecha_vencimiento='" . $request['fVenc'][$i] . "'";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            $datosNew = array();
            while (!$rst->EOF)
            {
                $datosNew[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();

            // $sql="INSERT INTO log_existencias_bodegas_lote_fv VALUES(statement_timestamp(),('".$datosOld[0]['empresa_id']."','".$datosOld[0]['centro_utilidad']."','".$datosOld[0]['codigo_producto']."','".$datosOld[0]['bodega']."','".$datosOld[0]['fecha_vencimiento']."','".$datosOld[0]['lote']."',".$datosOld[0]['existencia_inicial'].",".$datosOld[0]['existencia_actual'].",".$datosOld[0]['estado'].")::existencias_bodegas_lote_fv,
            // ('".$datosNew[0]['empresa_id']."','".$datosNew[0]['centro_utilidad']."','".$datosNew[0]['codigo_producto']."','".$datosNew[0]['bodega']."','".$datosNew[0]['fecha_vencimiento']."','".$datosNew[0]['lote']."',".$datosNew[0]['existencia_inicial'].",".$datosNew[0]['existencia_actual'].",".$datosNew[0]['estado'].")::existencias_bodegas_lote_fv,
            // ".$_SESSION['SYSTEM_USUARIO_ID'].") 
            // ";
            // $this->ConexionTransaccion();
            // if(!$rst = $this->ConexionTransaccion($sql))
            // {
            ///return $this->mensajeDeError;
            // return false;
            // }
        }


        $this->Commit();

        return $bool;
    }

    function Exist_Bod_general($codigo, $empresa, $bod, $cu)
    {
        $sql = "SELECT existencia FROM existencias_bodegas WHERE ";
        $sql .= "              empresa_id = '" . $empresa . "' ";
        $sql .= "    AND   codigo_producto = '" . $codigo . "' ";
        $sql .= "    AND   centro_utilidad = '" . $cu . "' ";
        $sql .= "    AND   bodega = '" . $bod . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $ex = array();
        $ex = $rst->GetRowAssoc($ToUpper = false);

        return $ex;
    }
    
    function EditarFechaVencimiento($empresa_id, $centro_utilidad, $codigo_producto, $bodega, $fecha_vencimiento, $lote, $campo_fecha_vencimiento)
    {
        $this->ConexionTransaccion();
        
        $sql = "UPDATE existencias_bodegas_lote_fv
                    SET fecha_vencimiento = '" . $campo_fecha_vencimiento . "'
                    WHERE 
                        empresa_id = '" . $empresa_id . "' 
                        AND centro_utilidad = '" . $centro_utilidad . "' 
                        AND codigo_producto = '" . $codigo_producto . "' 
                        AND bodega = '" . $bodega . "' 
                        AND  fecha_vencimiento = '" . $fecha_vencimiento . "' 
                        AND lote = '" . $lote . "'";
        
        //echo $sql;
        
        if(!$rst = $this->ConexionTransaccion($sql))
        {
            return false;
        }

        $this->Commit();
        return true;
    }
    
    function GuardarExistenciaBodega($empresa_id, $centro_utilidad, $codigo_producto, $bodega, $fecha_vencimiento, $lote)
    {
        $this->ConexionTransaccion();
        
        $sql = "INSERT INTO existencias_bodegas_lote_fv
                    (empresa_id, centro_utilidad, codigo_producto, bodega, fecha_vencimiento, lote, existencia_inicial, existencia_actual)
                    VALUES ('" . $empresa_id . "', '" . $centro_utilidad . "', '" . $codigo_producto . "', '" . $bodega . "', '" . $fecha_vencimiento . "', '" . $lote . "', 0, 0)";
        
        //echo $sql;
        
        if(!$rst = $this->ConexionTransaccion($sql))
        {
            return false;
        }

        $this->Commit();
        return true;
    }

}

?>
