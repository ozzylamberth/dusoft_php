<?php

/**
* $Id: ContabilizarDocumento.class.php,v 1.18 2008/06/05 14:27:45 cahenao Exp $
*/

/**
* Clase con metdos comunespara la contabilizacion de documentos
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.18 $
* @package SIIS
*/
class ContabilizarDocumento
{
    /**
    * Codigo de error
    *
    * @var string
    * @access private
    */
    var $error;

    /**
    * Mensaje de error
    *
    * @var string
    * @access private
    */
    var $mensajeDeError;

    /**
    * Datos de la contabilizacion existente de un documento
    *
    * @var array
    * @access private
    */
    var $DatosContabilizacionExistente;

    /**
    * Datos de la contabilizacion realizada de un documento
    *
    * @var array
    * @access private
    */
    var $DatosContabilizacionFinal;

    /**
    * Datos documento
    *
    * @var array
    * @access private
    */
    var $DatosDocumento;

    /**
    * Vector con los centros de costo por empresa.
    *
    * @var array
    * @access private
    */
    var $CentrosDeCosto;

    /**
    * Vector para almacenar los registros de contabilizacion.
    *
    * @var array
    * @access private
    */
    var $MOV;

    /**
    * Estructura contable del documento
    *
    * @var array
    * @access private
    */
    var $ShemaContable;

    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function ContabilizarDocumento()
    {
        return true;
    }


    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }


    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }


    /**
    * Metodo para resetear el registro de asientos conatables
    *
    * @return void
    * @access private
    */
    function DelMOV()
    {
        unset($this->MOV);
        return true;
    }


    /**
    * Metodo para adicionar un registro de un asiento conatable
    *
    * @return void
    * @access private
    */
    function AddMOV($datos=array())
    {
        //if($datos['debito']>0 || $datos['credito']>0)
        if($datos['debito']<>0 || $datos['credito']<>0)
        {
            $this->MOV[] = $datos;
        }
        return true;
    }

    /**
    * Metodo para adicionar un registro de un asiento conatable
    *
    * @return void
    * @access private
    */
    function SetDocumento($datos=array(),$nuevo=false)
    {
        unset($this->DatosDocumento);
        unset($this->MOV);

        if(empty($datos['empresa_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [empresa_id] es nulo.";
            return false;
        }
        else
        {
            $this->DatosDocumento['empresa_id'] =$datos['empresa_id'];
        }

        if(!$nuevo)
        {
            if(empty($datos['prefijo']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El parametro [prefijo] es nulo.";
                return false;
            }
            else
            {
                $this->DatosDocumento['prefijo'] =$datos['prefijo'];
            }

            if(empty($datos['numero']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El parametro [numero] es nulo.";
                return false;
            }
            else
            {
                $this->DatosDocumento['numero'] = $datos['numero'];
            }

            $this->DatosDocumento['nuevo_documento'] = false;

        }
        else
        {
            $this->DatosDocumento['nuevo_documento'] = true;
        }

        if(empty($datos['documento_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [documento_id] es nulo.";
            return false;
        }
        else
        {
            $this->DatosDocumento['documento_id'] =$datos['documento_id'];
        }

        if(empty($datos['fecha_documento']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [fecha_documento] es nulo.";
            return false;
        }
        else
        {
            $this->DatosDocumento['fecha_documento'] =$datos['fecha_documento'];
        }

        $fh=explode(" ",$this->DatosDocumento['fecha_documento']);
        $f=explode("-",$fh[0]);
        $this->DatosDocumento['lapso'] = $f[0].$f[1];
        if(strlen($this->DatosDocumento['lapso'])!=6)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "La fecha del Documento [fecha_documento] no es correcta YYYY-MM-DD o YYYY-MM-DD HH:MM:SS";
            return false;
        }

        if(empty($datos['tipo_id_tercero']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [tipo_id_tercero] es nulo.";
            return false;
        }
        else
        {
            $this->DatosDocumento['tipo_id_tercero'] =$datos['tipo_id_tercero'];
        }

        if(empty($datos['tercero_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [tercero_id] es nulo.";
            return false;
        }
        else
        {
            $this->DatosDocumento['tercero_id'] =$datos['tercero_id'];
        }

        if(empty($datos['tipo_doc_general_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [tipo_doc_general_id] es nulo.";
            return false;
        }
        else
        {
            $this->DatosDocumento['tipo_doc_general_id'] =$datos['tipo_doc_general_id'];
        }

        //ESTABLECER LA ESTRUCTURA DE CONTABILIZACION (tablas y shemas)
        if($this->SetEstructuraContable()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo setEstructuraContable() retorno false";
            }
            return false;
        }

        return true;
    }

    /**
    * Metodo para adicionar un registro de un asiento conatable
    *
    * @return void
    * @access private
    */
    function GetInfoCuentaContable($empresa_id,$cuenta)
    {
        static $InfoCuentaContable;
        if(empty($InfoCuentaContable[$empresa_id][$cuenta]))
        {
            GLOBAL $ADODB_FETCH_MODE;
            list($dbconn) = GetDBconn();

            $sql = "SELECT * FROM cg_conf.cg_plan_de_cuentas WHERE empresa_id='$empresa_id' AND  cuenta = '$cuenta';";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
            if($result->EOF)
            {
                return null;
            }

            $InfoCuentaContable[$empresa_id][$cuenta] = $result->FetchRow();
            $result->Close();

        }
        return $InfoCuentaContable[$empresa_id][$cuenta];
    }

    /**
    * Metodo para adicionar un registro de un asiento conatable
    *
    * @return void
    * @access private
    */
    function GetCentroDeCostoDepartamento($empresa_id,$departamento)
    {
        static $CC;

        if(empty($CC))
        {
            GLOBAL $ADODB_FETCH_MODE;
            list($dbconn) = GetDBconn();

            $sql = "SELECT * FROM cg_conf.centros_de_costo_departamentos;";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
            if($result->EOF)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "LA TABLA [cg_conf.centros_de_costo_departamentos] NO TIENE DATOS.";
                return false;
            }

            while($fila=$result->FetchRow())
            {
                $CC[$fila['empresa_id']][$fila['departamento']]=$fila;
            }
            $result->Close();

        }

        if(empty($CC[$empresa_id][$departamento]))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL DEPARTAMENTO [$departamento] NO TIENE ASOCIADO UN CENTRO DE COSTOS EN LA TABLA [cg_conf.centros_de_costo_departamentos].";
            return false;
        }
        else
        {
            return $CC[$empresa_id][$departamento];
        }
    }

    /**
    * Metodo para establecer la estructura contable del documento
    *
    * @return boolean
    * @access public
    */
    function SetEstructuraContable()
    {
        if(empty($this->DatosDocumento))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El vector DatosDocumento no esta inicializado.";
            return false;
        }

        unset($this->ShemaContable);

        $empresa_id = $this->DatosDocumento['empresa_id'];
        $lapso      = $this->DatosDocumento['lapso'];

        $this->ShemaContable['cg_shema']           = "cg_mov_$empresa_id";
        $this->ShemaContable['tbl_cg_mov']         = "cg_mov_contable_$empresa_id";
        $this->ShemaContable['tbl_cg_mov_detalle'] = "cg_mov_contable_$empresa_id" . "_$lapso";
        $this->ShemaContable['cg_mov']             = $this->ShemaContable['cg_shema'] . "." . $this->ShemaContable['tbl_cg_mov'];
        $this->ShemaContable['cg_mov_detalle']     = $this->ShemaContable['cg_shema'] . "." . $this->ShemaContable['tbl_cg_mov_detalle'];
        $this->ShemaContable['seq_documento_contable_id'] = $this->ShemaContable['cg_mov'] ."_documento_contable_id_seq";

        list($dbconn) = GetDBconn();
        $sql = "SELECT tablename FROM pg_catalog.pg_tables WHERE tablename IN('".$this->ShemaContable['tbl_cg_mov']."','".$this->ShemaContable['tbl_cg_mov_detalle']."')  AND schemaname = '".$this->ShemaContable['cg_shema']."'";
        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE ENCONTRO EL SHEMA DE CONTABILIZACION : " . $this->ShemaContable['cg_shema'];
            return false;
        }

        while($fila=$result->FetchRow())
        {
            $V_TABLAS[$fila[0]]=true;
        }

        $result->Close();

        if (!$V_TABLAS[$this->ShemaContable['tbl_cg_mov']])
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO EXISTE LA TABLA DE MOVIMIENTO CONTABLE1  : " . $this->ShemaContable['cg_mov'];
            return false;
        }

        if (!$V_TABLAS[$this->ShemaContable['tbl_cg_mov_detalle']])
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO EXISTE LA TABLA DE MOVIMIENTO CONTABLE2  : " . $this->ShemaContable['cg_mov_detalle'];
            return false;
        }
        
				return true;
    }


    /**
    * Metodo para obtener los datos del documento contabilizado
    *
    * @param string  $empresa_id
    * @param string  $prefijo
    * @param string  $numero
    * @return array
    * @access public
    */
    function GetDatosDocumentoContabilizado($empresa_id=null,$prefijo=null,$numero=null,$detalle=false)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        if(empty($this->ShemaContable))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El ShemaContable no esta inicializado.";
            return false;
        }

        if(empty($empresa_id) || empty($prefijo) || empty($numero))
        {
            $empresa_id = $this->DatosDocumento['empresa_id'];
            $prefijo    = $this->DatosDocumento['prefijo'];
            $numero     = $this->DatosDocumento['numero'];
              
        }

        if(empty($prefijo) && empty($numero) && $this->DatosDocumento['nuevo_documento'])
        {
            return null;
        }

        //CONSULTAR SI EL DOCUMENTO ESTA CONTABILIZADO
//echo '<br><br>';
        $sql = "SELECT * FROM " . $this->ShemaContable['cg_mov'] . "
                WHERE empresa_id = '$empresa_id'
                        AND prefijo = '$prefijo'
                        AND numero = $numero;";
//echo '<br><br>';
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {   
            return null;
        }
        else
        {
            $retorno = $result->FetchRow();
            $result->Close();

            if($detalle)
            {
//echo '<br><br>';
                $sql = "SELECT * FROM " . $this->ShemaContable['cg_mov'] ."_". $retorno['lapso'] ."
                        WHERE documento_contable_id = ".$retorno['documento_contable_id'].";";

//echo '<br><br>';			

                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                while($fila_detalle = $result->FetchRow())
                {
                    $retorno['DETALLE'][]=$fila_detalle;
                }

                $result->Close();
            }
            return $retorno;
        }
    }


    /**
    * Metodo para contabilizar un documento anulado
    *
    * @return boolean
    * @access private
    */
    function GenerarDocumentoAnulado()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $dbconn->BeginTrans();

        $documento_contable_id = $this->DatosContabilizacionExistente['documento_contable_id'];

        if(is_numeric($documento_contable_id))
        {
            $sql  = "DELETE FROM " . $this->ShemaContable['cg_mov_detalle'] . "
                        WHERE documento_contable_id = $documento_contable_id; ";

            $sql .= "DELETE FROM " . $this->ShemaContable['cg_mov'] . "
                        WHERE documento_contable_id = $documento_contable_id; ";

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }

        if($this->DatosDocumento['nuevo_documento'])
        {
            $sql  = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE; ";
            $sql .= "SELECT prefijo,numeracion FROM documentos ";
            $sql .= "WHERE documento_id = ".$this->DatosDocumento['documento_id']." AND empresa_id = '".$this->DatosDocumento['empresa_id']."'; ";

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
            if($result->EOF)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "No se pudo obtener la numercion del nuevo documento a crear.";
                return false;
            }

            list($this->DatosDocumento['prefijo'],$this->DatosDocumento['numero']) = $result->FetchRow();
            $result->Close();

            $sql  = "UPDATE documentos ";
            $sql .= "SET numeracion = numeracion + 1 ";
            $sql .= "WHERE documento_id = ".$this->DatosDocumento['documento_id']." AND empresa_id = '".$this->DatosDocumento['empresa_id']."'; ";

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }
             if(empty($this->DatosDocumento['lapso'])) 
             {
               $fh=explode(" ",$this->DatosDocumento['fecha_documento']);
               $f=explode("-",$fh[0]);
               $this->DatosDocumento['lapso'] = $f[0].$f[1];
               if(strlen($this->DatosDocumento['lapso'])!=6)
               {
                  $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                  $this->mensajeDeError = "La fecha del Documento [fecha_documento] no es correcta YYYY-MM-DD o YYYY-MM-DD HH:MM:SS";
                  return false;
               }
             }

        $sql =" INSERT INTO ".$this->ShemaContable['cg_mov']."(
                    lapso,
                    fecha_documento,
                    empresa_id,
                    prefijo,
                    numero,
                    documento_id,
                    sw_estado,
                    tipo_id_tercero,
                    tercero_id,
                    usuario_id
                )VALUES(
                    '".$this->DatosDocumento['lapso']."',
                    '".$this->DatosDocumento['fecha_documento']."',
                    '".$this->DatosDocumento['empresa_id']."',
                    '".$this->DatosDocumento['prefijo']."',
                    ".$this->DatosDocumento['numero'].",
                    ".$this->DatosDocumento['documento_id'].",
                    '0',
                    '".$this->DatosDocumento['tipo_id_tercero']."',
                    '".$this->DatosDocumento['tercero_id']."',
                    ".UserGetUID()."
                )";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $dbconn->CommitTrans();

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        return true;
    }


    /**
    * Metodo para generar los datos contables.
    *
    * @return boolean
    * @access private
    */
    function GenerarDocumentoContable()
    {
        if(empty($this->MOV))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "No hay registros contables para contabilizar el documento.";
            return false;
        }

        $SumDebitos  = 0;
        $SumCreditos = 0;
        $sql_detalle = '';

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

  //echo      $sql = "CREATE TEMP TABLE cg_mov_detalle_tmp
/*        $sql = "SELECT  pc.relname
                FROM pg_catalog.pg_class pc
                WHERE pc.relkind = 'r' 
                AND pc.relname = 'cg_mov_detalle_tmp' 
                ";
       $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            //$dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->RecordCount()===0)
        {         */
					  $sql = "CREATE TABLE cg_mov_detalle_tmp
										(
												documento_cruce_id integer,
												empresa_id character(2) NOT NULL,
												cuenta character varying(32) NOT NULL,
												tipo_id_tercero character(3),
												tercero_id character(32),
												debito numeric(12,2) DEFAULT 0 NOT NULL,
												credito numeric(12,2) DEFAULT 0 NOT NULL,
												detalle character varying(80) DEFAULT ''::character varying NOT NULL,
												centro_de_costo_id character varying(12),
												base_rtf numeric(12,2) DEFAULT 0 NOT NULL,
												porcentaje_rtf numeric(9,4) DEFAULT 0 NOT NULL,
												documento_cxc integer,
												documento_cxp integer,
												centro_de_operacion_id character varying(12)
										);
						";
		
						$dbconn->Execute($sql);
		
						if($dbconn->ErrorNo() != 0)
						{
								$dbconn->RollbackTrans();
								$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
								$this->mensajeDeError = $dbconn->ErrorMsg();
								return false;
						}
				//}	

        foreach($this->MOV as $k => $v)
        {
            $SumDebitos += $v['debito'];
            $SumCreditos += $v['credito'];

            if($v['documento_cruce_id'] == 'NUM_DOC')
            {
                $documento_cruce_id = -1;
            }
            elseif(is_numeric($v['documento_cruce_id']))
            {
                $documento_cruce_id = $v['documento_cruce_id'];
            }
            else
            {
                $documento_cruce_id = "NULL";
            }

            if($v['documento_cxp'] == 'NUM_DOC')
            {
                $documento_cxp = -1;
            }
            elseif(is_numeric($v['documento_cxp']))
            {
                $documento_cxp = $v['documento_cxp'];
            }
            else
            {
                $documento_cxp = "NULL";
            }

            if($v['documento_cxc'] == 'NUM_DOC')
            {
                $documento_cxc = -1;
            }
            elseif(is_numeric($v['documento_cxc']))
            {
                $documento_cxc = $v['documento_cxc'];
            }
            else
            {
                $documento_cxc = "NULL";
            }

            if(empty($v['tipo_id_tercero']))
            {
                $tipo_id_tercero = "NULL";
            }
            else
            {
                $tipo_id_tercero = "'".$v['tipo_id_tercero']."'";
            }

            if(empty($v['tercero_id']))
            {
                $tercero_id = "NULL";
            }
            else
            {
                $tercero_id = "'".$v['tercero_id']."'";
            }

            if(empty($v['centro_de_costo_id']))
            {
                $centro_de_costo_id = "NULL";
            }
            else
            {
                $centro_de_costo_id = "'".$v['centro_de_costo_id']."'";
            }

            if(empty($v['centro_de_operacion_id']))
            {
                $centro_de_operacion_id = "NULL";
            }
            else
            {
                $centro_de_operacion_id = "'".$v['centro_de_operacion_id']."'";
            }


            $sql = "INSERT INTO cg_mov_detalle_tmp
                    (
                        documento_cruce_id,
                        empresa_id,
                        cuenta,
                        tipo_id_tercero,
                        tercero_id,
                        debito,
                        credito,
                        detalle,
                        centro_de_costo_id,
                        base_rtf,
                        porcentaje_rtf,
                        documento_cxc,
                        documento_cxp,
                        centro_de_operacion_id
                    )VALUES(
                        $documento_cruce_id,
                        '".$v['empresa_id']."',
                        '".$v['cuenta']."',
                        $tipo_id_tercero,
                        $tercero_id,
                        ".$v['debito'].",
                        ".$v['credito'].",
                        '".substr(trim($v['detalle']), 0, 80)."',
                        $centro_de_costo_id,
                        ".$v['base_rtf'].",
                        ".$v['porcentaje_rtf'].",
                        $documento_cxc,
                        $documento_cxp,
                        $centro_de_operacion_id
                    );
            ";
            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                $sqlx="DROP TABLE cg_mov_detalle_tmp;";
                $result = $dbconn->Execute($sqlx);
                return false;
            }
        }

        if($SumDebitos == 0 && $SumCreditos == 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "La contabilizacion del documento tiene valor de cero(0).";
            $sqlx="DROP TABLE cg_mov_detalle_tmp;";
            $result = $dbconn->Execute($sqlx);
            return false;
        }

        //INICIO LA TRANSACCION
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();

        $documento_contable_id = $this->DatosContabilizacionExistente['documento_contable_id'];

        //SI VOY A RECONTABILIZAR SUPONGO QUE DEBO TENER LOS DATOS DEL DOCUMENTO.
        if(is_numeric($documento_contable_id))
        {
            $sql  = "DELETE FROM " . $this->ShemaContable['cg_mov_detalle'] . "
                        WHERE documento_contable_id = $documento_contable_id; ";

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                $sqlx="DROP TABLE cg_mov_detalle_tmp;";
                $result = $dbconn->Execute($sqlx);
                return false;
            }
        }
        else
        {
            $sql = "SELECT nextval('".$this->ShemaContable['seq_documento_contable_id']."');";
            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                $sqlx="DROP TABLE cg_mov_detalle_tmp;";
                $result = $dbconn->Execute($sqlx);
                return false;
            }
            if($result->EOF)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "Error no retorno valor la secuencia : [" . $this->ShemaContable['seq_documento_contable_id'] . "]";
                $sqlx="DROP TABLE cg_mov_detalle_tmp;";
                $result = $dbconn->Execute($sqlx);
                return false;
            }

            list($documento_contable_id) = $result->FetchRow();
            $result->Close();


            if($this->DatosDocumento['nuevo_documento'])
            {
                $sql  = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE; ";
                $sql .= "SELECT prefijo,numeracion FROM documentos ";
                $sql .= "WHERE documento_id = ".$this->DatosDocumento['documento_id']." AND empresa_id = '".$this->DatosDocumento['empresa_id']."'; ";

                $result = $dbconn->Execute($sql);

                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    $sqlx="DROP TABLE cg_mov_detalle_tmp;";
                    $result = $dbconn->Execute($sqlx);
                    return false;
                }
                if($result->EOF)
                {
                    $dbconn->RollbackTrans();
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "No se pudo obtener la numercion del nuevo documento a crear.";
                    $sqlx="DROP TABLE cg_mov_detalle_tmp;";
                    $result = $dbconn->Execute($sqlx);
                    return false;
                }

                list($this->DatosDocumento['prefijo'],$this->DatosDocumento['numero']) = $result->FetchRow();
                $result->Close();

                $sql  = "UPDATE documentos ";
                $sql .= "SET numeracion = numeracion + 1 ";
                $sql .= "WHERE documento_id = ".$this->DatosDocumento['documento_id']." AND empresa_id = '".$this->DatosDocumento['empresa_id']."'; ";

                $result = $dbconn->Execute($sql);

                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    $sqlx="DROP TABLE cg_mov_detalle_tmp;";
                    $result = $dbconn->Execute($sqlx);
                    return false;
                }
            }
             if(empty($this->DatosDocumento['lapso'])) 
             {
               $fh=explode(" ",$this->DatosDocumento['fecha_documento']);
               $f=explode("-",$fh[0]);
               $this->DatosDocumento['lapso'] = $f[0].$f[1];
               if(strlen($this->DatosDocumento['lapso'])!=6)
               {
                  $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                  $this->mensajeDeError = "La fecha del Documento [fecha_documento] no es correcta YYYY-MM-DD o YYYY-MM-DD HH:MM:SS";
                  return false;
               }
             }
           $sql =" INSERT INTO ".$this->ShemaContable['cg_mov']."(
                        documento_contable_id,
                        lapso,
                        fecha_documento,
                        empresa_id,
                        prefijo,
                        numero,
                        documento_id,
                        sw_estado,
                        tipo_id_tercero,
                        tercero_id,
                        usuario_id
                    )VALUES(
                        $documento_contable_id,
                        '".$this->DatosDocumento['lapso']."',
                        '".$this->DatosDocumento['fecha_documento']."',
                        '".$this->DatosDocumento['empresa_id']."',
                        '".$this->DatosDocumento['prefijo']."',
                        ".$this->DatosDocumento['numero'].",
                        ".$this->DatosDocumento['documento_id'].",
                        '1',
                        '".$this->DatosDocumento['tipo_id_tercero']."',
                        '".$this->DatosDocumento['tercero_id']."',
                        ".UserGetUID()."
                    )";

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                $sqlx="DROP TABLE cg_mov_detalle_tmp;";
                $result = $dbconn->Execute($sqlx);
                return false;
            }
        }

        $sql = "INSERT INTO ".$this->ShemaContable['cg_mov_detalle']."
                (   documento_contable_id,
                    documento_cruce_id,
                    empresa_id,
                    cuenta,
                    tipo_id_tercero,
                    tercero_id,
                    debito,
                    credito,
                    detalle,
                    centro_de_costo_id,
                    base_rtf,
                    porcentaje_rtf,
                    documento_cxc,
                    documento_cxp,
                    centro_de_operacion_id
                )

                SELECT
                    $documento_contable_id as documento_contable_id,
                    CASE WHEN a.documento_cruce_id = -1 THEN $documento_contable_id ELSE a.documento_cruce_id END as documento_cruce_id,
                    a.empresa_id,
                    a.cuenta,
                    a.tipo_id_tercero,
                    a.tercero_id,
                    a.debito,
                    a.credito,
                    a.detalle,
                    a.centro_de_costo_id,
                    a.base_rtf,
                    a.porcentaje_rtf,
                    CASE WHEN a.documento_cxc = -1 THEN $documento_contable_id ELSE a.documento_cxc END as documento_cxc,
                    CASE WHEN a.documento_cxp = -1 THEN $documento_contable_id ELSE a.documento_cxp END as documento_cxp,
                    a.centro_de_operacion_id

                FROM
                (
                    (
                        SELECT
                            documento_cruce_id,
                            empresa_id,
                            cuenta,
                            tipo_id_tercero,
                            tercero_id,
                            debito,
                            SUM(credito) AS credito,
                            detalle,
                            centro_de_costo_id,
                            base_rtf,
                            porcentaje_rtf,
                            documento_cxc,
                            documento_cxp,
                            centro_de_operacion_id

                        FROM cg_mov_detalle_tmp
                        WHERE debito = 0
                        GROUP BY 1,2,3,4,5,6,8,9,10,11,12,13,14
                    )
                    UNION
                    (
                        SELECT
                            documento_cruce_id,
                            empresa_id,
                            cuenta,
                            tipo_id_tercero,
                            tercero_id,
                            SUM(debito) AS debito,
                            credito,
                            detalle,
                            centro_de_costo_id,
                            base_rtf,
                            porcentaje_rtf,
                            documento_cxc,
                            documento_cxp,
                            centro_de_operacion_id

                        FROM cg_mov_detalle_tmp
                        WHERE credito = 0
                        GROUP BY 1,2,3,4,5,7,8,9,10,11,12,13,14
                    )
                ) as a;

                DROP TABLE cg_mov_detalle_tmp;
        ";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            $sqlx="DROP TABLE cg_mov_detalle_tmp;";
            $result = $dbconn->Execute($sqlx);
            return false;
        }

        $dbconn->CommitTrans();

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            $sqlx="DROP TABLE cg_mov_detalle_tmp;";
            $result = $dbconn->Execute($sqlx);
            return false;
        }

        if($SumDebitos != $SumCreditos)
        {
            $this->error = "CONTABILIZACION NO CUADRADA";
            $this->mensajeDeError = "DOCUMENTO SIN CUADRAR : DEBITOS[ $SumDebitos ] CREDITOS[ $SumCreditos ] DIFERENCIA[ " .abs($SumDebitos - $SumCreditos). " ]";
            return false;
        }

        return true;
    }


    /**
    * Metodo para retornar el documento contabilizado.
    *
    * @return array
    * @access private
    */
    function RetornarDocumentoContable()
    {
        $this->DatosContabilizacionFinal = $this->GetDatosDocumentoContabilizado();
        if($this->DatosContabilizacionFinal === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetDatosDocumentoContabilizado() retorno false";
            }
            return false;
        }

        if(is_array($this->DatosContabilizacionExistente))
        {
            $this->DatosContabilizacionFinal['RESULTADO']   = 'A';
            $this->DatosContabilizacionFinal['RESULTADO_D'] = 'CONTABILIZACION ACTUALIZADA.';
        }
        else
        {
            $this->DatosContabilizacionFinal['RESULTADO']   = 'C';
            $this->DatosContabilizacionFinal['RESULTADO_D'] = 'CONTABILIZACION EXITOSA.';
        }
        return $this->DatosContabilizacionFinal;
    }


    /**
    * Metodo para validar la existencia y actualizacion de un documento.
    *
    * @param boolean $actualizar
    * @return array
    * @access private
    */
    function ValidarActualizacionDelDocumentoContable($actualizar=false)
    {
        
        $this->DatosContabilizacionExistente = $this->GetDatosDocumentoContabilizado();
        
        if($this->DatosContabilizacionExistente === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetDatosDocumentoContabilizado() retorno false";
            }
            return false;
        }
            
        //RETORNAR SI EL DOCUMENTO YA ESTA CONTABILIZADO Y NO SE REQUIERE ACTUALIZAR
        if(is_array($this->DatosContabilizacionExistente) && !$actualizar)
        {
            $this->DatosContabilizacionFinal = $this->DatosContabilizacionExistente;
            $this->DatosContabilizacionFinal['RESULTADO']   = 'E';
            $this->DatosContabilizacionFinal['RESULTADO_D'] = 'CONTABILIZACION EXISTENTE (NO SE ACTUALIZA).';
            return $this->DatosContabilizacionFinal;
        }
        
        return null;
    }


    /**
    * Metodo para validar y generar un vector de movimiento contable.
    *
    * @param array $Datos Informacion para generar el vector de movimiento contable
    * @param boolean $error TRUE = Contabilizacion OK,  FALSE = error.
    * @return boolean
    * @access private
    */
    function GenerarVectorMovimiento($Datos = array('cuenta'=>'','naturaleza'=>'','valor'=>0,'centro_de_costo_id'=>'','centro_de_operacion_id'=>null,'documento_cruce_id'=>null,'documento_cxc'=>null,'documento_cxp'=>null,'base_rtf'=>0,'porcentaje_rtf'=>0,'tipo_id_tercero'=>'','tercero_id'=>'','detalle'=>'','paquete_codigo_id'=>'','sw_paquete_facturado'=>'0'))
    {
        if(!(abs($Datos['valor'])>0))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El valor del movimiento no es valido [".$Datos['valor']."].";
            return false;
        }

        $InfoCuenta = $this->GetInfoCuentaContable($this->DatosDocumento['empresa_id'],$Datos['cuenta']);
        if($InfoCuenta===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetInfoCuentaContable() retorno false";
            }
            return false;
        }
        elseif(!is_array($InfoCuenta))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "La cuenta contable [".$this->DatosDocumento['empresa_id']."][".$Datos['cuenta']."] no retorno informacion.";
            }
            return false;
        }

        if(!$InfoCuenta['sw_cuenta_movimiento'])
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "La cuenta contable [".$this->DatosDocumento['empresa_id']."][".$Datos['cuenta']."] no es de movimiento.";
            return false;
        }

        //VECTOR DE CONTABILIZACION
        $C = array();

        $C['empresa_id'] = $InfoCuenta['empresa_id'];
        $C['cuenta']     = $InfoCuenta['cuenta'];

        if($InfoCuenta['sw_tercero'])
        {
            if($Datos['tipo_id_tercero'] && $Datos['tercero_id'])
            {
                $C['tipo_id_tercero'] = $Datos['tipo_id_tercero'];
                $C['tercero_id']      = $Datos['tercero_id'];
            }
            else
            {
                $C['tipo_id_tercero'] = $this->DatosDocumento['tipo_id_tercero'];
                $C['tercero_id']      = $this->DatosDocumento['tercero_id'];
            }
        }
        else
        {
            $C['tipo_id_tercero'] = '';
            $C['tercero_id']      = '';
        }

        if($InfoCuenta['sw_centro_costo'])
        {
            if(empty($Datos['centro_de_costo_id']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "La cuenta contable [".$this->DatosDocumento['empresa_id']."][".$Datos['cuenta']."] exige centro de costos.";
                return false;
            }
            $C['centro_de_costo_id'] = $Datos['centro_de_costo_id'];
        }
        else
        {
            $C['centro_de_costo_id'] = '';
        }

        //SOPORTE PARA CGUUNO
        $C['centro_de_operacion_id'] = $Datos['centro_de_operacion_id'];

        if($InfoCuenta['sw_documento_cruce'])
        {
            if(empty($Datos['documento_cruce_id']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "La cuenta contable [".$this->DatosDocumento['empresa_id']."][".$Datos['cuenta']."] exige documento cruce.";
                return false;
            }
            if(!is_numeric($Datos['documento_cruce_id']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El valor del documento cruce no es valido [".$Datos['documento_cruce_id']."]";
                return false;
            }
            $C['documento_cruce_id'] = $Datos['documento_cruce_id'];
        }
        else
        {
            $C['documento_cruce_id'] = '';
        }


        if($InfoCuenta['sw_cuenta_por_pagar'])
        {
            if(empty($Datos['documento_cxp']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "La cuenta contable [".$this->DatosDocumento['empresa_id']."][".$Datos['cuenta']."] exige documento de CxP.";
                return false;
            }
            if(!is_numeric($Datos['documento_cxp']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El valor del documento CxP no es valido [".$Datos['documento_cxp']."]";
                return false;
            }
            $C['documento_cxp'] = $Datos['documento_cxp'];
        }
        else
        {
            $C['documento_cxp'] = '';
        }


        if($InfoCuenta['sw_cuenta_por_cobrar'])
        {
            if(empty($Datos['documento_cxc']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "La cuenta contable [".$this->DatosDocumento['empresa_id']."][".$Datos['cuenta']."] exige documento de CxC.";
                return false;
            }
            if(!is_numeric($Datos['documento_cxc']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El valor del documento CxC no es valido [".$Datos['documento_cxc']."]";
                return false;
            }
            $C['documento_cxc'] = $Datos['documento_cxc'];
        }
        else
        {
            $C['documento_cxc'] = '';
        }


        if($InfoCuenta['sw_impuesto_rtf'])
        {
            if(empty($Datos['base_rtf']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "La cuenta contable [".$this->DatosDocumento['empresa_id']."][".$Datos['cuenta']."] exige base y porcentaje de RTF.";
                return false;
            }

            $C['base_rtf'] = $Datos['base_rtf'];
            $C['porcentaje_rtf'] = $Datos['porcentaje_rtf'];
        }
        else
        {
            $C['base_rtf'] = 0;
            $C['porcentaje_rtf'] = 0;
        }


        if($Datos['naturaleza']=='D')
        {
            if($Datos['valor']>0)
            {
                $C['debito']  = $Datos['valor'];
                $C['credito'] = 0;
            }
            else
            {
                $C['debito']  = 0;
                $C['credito'] = abs($Datos['valor']);
            }
        }
        elseif($Datos['naturaleza']=='C')
        {
            if($Datos['valor']>0)
            {
                $C['debito']  = 0;
                $C['credito'] = $Datos['valor'];
            }
            else
            {
/*                $C['debito']  = abs($Datos['valor']);
                $C['credito'] = 0;
                if($this->GetCuentaDevol())
                {
                 $C['cuenta']  = $this->GetCuentaDevol();
                }*/
/*								if($Datos['valor']==-93800)
								{
									print_r($Datos);
								}*/
                $C['debito']  = 0;
                $C['credito'] = $Datos['valor'];
            }
        }
        else
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "La naturaleza de la cuenta no es valida [".$Datos['naturaleza']."].";
            return false;
        }

        $C['detalle'] = substr(trim("[".$this->DatosDocumento['prefijo']." ".$this->DatosDocumento['numero']."]  " . $Datos['detalle']), 0, 80);

        if(!empty($Datos['paquete_codigo_id']))
        {
            $C['paquete_codigo_id'] = $Datos['paquete_codigo_id'];
            $C['sw_paquete_facturado'] = $Datos['sw_paquete_facturado'];
        }

        return $C;
    }

    /**
    * Metodo para obtener un parametro del la configuracion de documentos
    *
    * @param string  $parametro
    * @param string  $empresa_id
    * @param string  $tipo_doc_general_id
    * @return boolean
    * @access private
    */
    function GetParametizacionDoc($parametro, $tipo_doc_general_id=null, $empresa_id=null)
    {
        static $I;

        if(empty($I))
        {
            GLOBAL $ADODB_FETCH_MODE;
            list($dbconn) = GetDBconn();

            $sql = "SELECT * FROM cg_conf.doc_parametros;";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if($result->EOF)
            {
                $I = "NO HAY PARAMETROS";
            }
            else
            {
                while($fila =$result->FetchRow())
                {
                    $I[$fila['empresa_id']][$fila['tipo_doc_general_id']][$fila['parametro']] = $fila;
                    if(!empty($fila['argumentos']))
                    {
                        $argumentos = ExplodeArrayAssoc($fila['argumentos']);
                        $I[$fila['empresa_id']][$fila['tipo_doc_general_id']][$fila['parametro']]['ARGUMENTOS'] = $argumentos;
                    }
                }
            }
            $result->Close();
        }

        if(empty($empresa_id))
        {
            $empresa_id = $this->DatosDocumento['empresa_id'];
        }
        if(empty($tipo_doc_general_id))
        {
           $tipo_doc_general_id  = $this->DatosDocumento['tipo_doc_general_id'];
        }

        if(!is_array($I[$empresa_id][$tipo_doc_general_id][$parametro]))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE ENCONTRO EL PARAMETRO [$parametro] PARA EL TIPO DE DOCUMENTO [$tipo_doc_general_id] DE LA EMPRESA [$empresa_id] EN LA TABLA [cg_conf.doc_parametros].";
            return false;
        }

        return $I[$empresa_id][$tipo_doc_general_id][$parametro];
    }



    function GetCuentaDevol()
    {
       list($dbconn) = GetDBconn();
       $sql = "SELECT cuenta FROM cg_conf.doc_fv01_inv_devoluciones
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."';
              ";
       $result = $dbconn->Execute($sql);
       if($dbconn->ErrorNo() != 0)
       {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = $dbconn->ErrorMsg();
          return false;
       }
       return $result->fields[0];
    }

}//fin de la clase
