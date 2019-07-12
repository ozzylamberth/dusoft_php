<?php

/**
* $Id: Cuentas.class.php,v 1.1 2010/01/20 21:03:35 hugo Exp $
*/

/**
* Clase para el manejo de cuentas(facturacion)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1 $
* @package SIIS
*/
class Cuentas
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
    * Datos de la cuenta sobre la que se esta liquidando
    *
    * @var array
    * @access private
    */
    var $datosCuenta=array();

   /**
    * Constructor
    * @access public
    */
    function Cuentas()
    {
        $this->error='';
        $this->mensajeDeError='';
        $this->datosCuenta=array();
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
    * Metodo para la creacion de una cuenta ambulatoria
    *
    * @param array $INFO datos necesarios para crear la cuenta. (tipo_id_paciente,paciente_id,departamento,plan_id,rango,tipo_afiliado_id,semanas_cotizadas)
    * @return string
    * @access public
    */
    function CrearCuentaAmbulatoria($INFO=array())
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        if(!is_numeric($INFO['semanas_cotizadas'])) $INFO['semanas_cotizadas'] = 0;

        $sql = "SELECT  a.paciente_id,
                        a.tipo_id_paciente,
                        b.plan_id,
                        b.empresa_id as empresa_id_plan,
                        c.centro_utilidad,
                        c.departamento,
                        c.empresa_id,
                        d.rango,
                        d.tipo_afiliado_id,
                        ".$INFO['semanas_cotizadas']." as semanas_cotizadas

                FROM    pacientes as a,
                        planes as b,
                        departamentos as c,
                        planes_rangos as d

                 WHERE  a.paciente_id='".$INFO['paciente_id']."'
                        AND a.tipo_id_paciente='".$INFO['tipo_id_paciente']."'
                        AND b.plan_id = ".$INFO['plan_id']."
                        AND c.departamento = '".$INFO['departamento']."'
                        AND d.plan_id = ".$INFO['plan_id']."
                        AND d.rango = '".$INFO['rango']."'
                        AND d.tipo_afiliado_id = '".$INFO['tipo_afiliado_id']."'
                                                AND d.plan_id = b.plan_id
                        ";

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
            $this->mensajeDeError = "ERROR AL VALIDAR LOS PARAMETROS, EL PACIENTE, EL PLAN O EL DEPARTAMENTO NO EXISTEN, O EL RANGO Y TIPO DE AFILIADO NO SON VALIDOS PARA EL PLAN.".$sql;
            return false;
        }

        $DATOS = $result->FetchRow();
        $result->Close();

        if($DATOS['empresa_id_plan'] != $DATOS['empresa_id'])
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL PLAN [".$DATOS['plan_id']."] Y  EL DEPARTAMENTO [".$DATOS['departamento']."] PERTENECEN A EMPRESAS DISTINTAS.";
            return false;
        }

        $sql = "SELECT nextval('ingresos_ingreso_seq');";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        list($INGRESO) = $result->FetchRow();
        $result->Close();

        if(is_numeric($INFO['autorizacion_int']))
        {
            $autorizacion_int = $INFO['autorizacion_int'];
        }
        else
        {
            $autorizacion_int = "NULL";
        }

        $dbconn->BeginTrans();

        $sql = "INSERT INTO ingresos
                    (
                        ingreso,
                        tipo_id_paciente,
                        paciente_id,
                        fecha_ingreso,
                        causa_externa_id,
                        via_ingreso_id,
                        comentario,
                        departamento,
                        estado,
                        departamento_actual,
                        fecha_registro,
                        usuario_id,
                        fecha_cierre,
                        autorizacion_int,
                        autorizacion_ext,
                        sw_apertura_admision,
                        sw_ambulatorio
                    )
                    VALUES
                    (
                        $INGRESO,
                        '".$DATOS['tipo_id_paciente']."',
                        '".$DATOS['paciente_id']."',
                        NOW(),
                        '15',
                        '2',
                        '',
                        '".$DATOS['departamento']."',
                        '0',
                        '".$DATOS['departamento']."',
                        NOW(),
                        ".UserGetUID().",
                        NOW(),
                        $autorizacion_int,
                        NULL,
                        '0',
                        '1'
                    );
        ";

        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $sql = "SELECT nextval('cuentas_numerodecuenta_seq');";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        list($CUENTA) = $result->FetchRow();
        $result->Close();

        $sql = "INSERT INTO cuentas
                    (
                        empresa_id,
                        centro_utilidad,
                        numerodecuenta,
                        ingreso,
                        plan_id,
                        fecha_cierre,
                        estado,
                        usuario_id,
                        fecha_registro,
                        tipo_afiliado_id,
                        semanas_cotizadas,
                        rango,
                        departamento
                    )
                    VALUES
                    (
                        '".$DATOS['empresa_id']."',
                        '".$DATOS['centro_utilidad']."',
                        $CUENTA,
                        $INGRESO,
                        ".$DATOS['plan_id'].",
                        NOW(),
                        '1',
                        ".UserGetUID().",
                        NOW(),
                        '".$DATOS['tipo_afiliado_id']."',
                        ".$DATOS['semanas_cotizadas'].",
                        '".$DATOS['rango']."',
                        '".$DATOS['departamento']."'
                    );
        ";

        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $dbconn->CommitTrans();

        return $CUENTA;

    }

    /**
    * Metodo para addicionar cargos a una cuenta.
    *
    * @param integer $cuenta
    * @param array $CARGOS arreglo con los os_maestro_cargos_id de cada cargo
    * @return string
    * @access public
    */
    function CargarOScargos($cuenta,$departamento,$CARGOS=array(),$departamento_al_cargar=null)
    {
        $vCargos = '';
        foreach($CARGOS as $CARGO)
        {
            $vCargos .= ' '.$CARGO;
        }

        $vCargos = trim($vCargos);

        if(empty($vCargos))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = 'NO HAY CARGOS PARA CARGAR.';
        }

        $vCargos = str_replace(" ", ",",$vCargos);
        $vCargos = "($vCargos)";

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT * FROM departamentos WHERE departamento = '$departamento';";

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
            $this->mensajeDeError = "NO SE ENCONTRO EL DEPARTAMENTO [$departamento].";
            return false;
        }

        $DatosDepartamento = $result->FetchRow();
        $result->Close();


        //DATOS DE LA CUENTA
        $sql = "SELECT a.estado, b.descripcion, a.plan_id, a.ingreso, a.semanas_cotizadas FROM cuentas as a, cuentas_estados as b WHERE a.numerodecuenta=$cuenta AND a.estado=b.estado;";

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
            $this->mensajeDeError = "LA CUENTA [$cuenta] NO EXISTE.";
            return false;
        }

        list($ESTADO,$ESTADO_D,$PLAN_ID,$INGRESO,$NUM_SEMANAS) = $result->FetchRow();
        $result->Close();

        if($ESTADO != '1' && $ESTADO != '2')
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "LA CUENTA [$cuenta] ESTA EN UN ESTADO QUE NO PERMITE ADICIONAR CARGOS [$ESTADO : $ESTADO_D].";
            return false;
        }


        //DATOS DEL INGRESO
        $sql = "SELECT a.ingreso, a.departamento_actual, a.sw_ambulatorio, b.servicio, a.tipo_id_paciente, a.paciente_id
                FROM ingresos as a, departamentos as b
                WHERE a.ingreso = $INGRESO
                AND b.departamento = a.departamento_actual;";

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
            $this->mensajeDeError = "EL INGRESO [$INGRESO] NO EXISTE.";
            return false;
        }

        $DATOS_INGRESO = $result->FetchRow();
        $result->Close();

        //DATOS DE LA OS
        $sql = "SELECT a.*, c.plan_id, b.cargo_cups, c.orden_servicio_id, c.autorizacion_int
                FROM os_maestro_cargos as a,
                     os_maestro as b,
                     os_ordenes_servicios as c

                WHERE
                a.os_maestro_cargos_id IN $vCargos
                AND b.numero_orden_id = a.numero_orden_id
                AND c.orden_servicio_id = b.orden_servicio_id
               ";

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
            $this->mensajeDeError = "NO SE ENCONTRARON LOS CARGOS EN [os_maestro_cargos].";
            return false;
        }

        $I=1;
        $CARGOS_LIQUIDADOS = array();

        while($fila = $result->FetchRow())
        {
            if($fila['plan_id'] != $PLAN_ID)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "EL PLAN [".$fila['plan_id']."] DE LA ORDEN DE SERVICIO [".$fila['orden_servicio_id']."] NO CORRESPONDE CON EL PLAN [$PLAN_ID] DE LA CUENTA [$cuenta], PARA EL CARGO CON [os_maestro_cargos_id = ".$fila['os_maestro_cargos_id']."].";
                return false;
            }

            if(!empty($fila['transaccion']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "EL CARGO CON [os_maestro_cargos_id = ".$fila['os_maestro_cargos_id']."], YA ESTA CARGADO A UNA CUENTA SU TRANSACION ES LA [".$fila['transaccion']."].";
                return false;
            }

            if($DATOS_INGRESO['sw_ambulatorio']=='1')
            {
                $SERVICIO = '3';
            }
            else
            {
                $SERVICIO = $DATOS_INGRESO['servicio'];
            }

            $Retorno = $this->GetLiquidacionCargo($cuenta,$fila['plan_id'],$fila['tarifario_id'],$fila['cargo'],$fila['cantidad'],$SERVICIO, $NUM_SEMANAS, $DATOS_INGRESO['tipo_id_paciente'], $DATOS_INGRESO['paciente_id']);

            if($Retorno===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetLiquidacionCargo() retorno false";
                }
                return false;
            }

            if(is_array($Retorno))
            {
                $CARGOS_LIQUIDADOS[$I] = $fila;
                $CARGOS_LIQUIDADOS[$I]['LIQUIDACION'] = $Retorno;
                $I++;
            }
        }
        $result->Close();

        if(!empty($departamento_al_cargar))
        {
            $departamento_al_cargar = "'$departamento_al_cargar'";
        }
        else
        {
            $departamento_al_cargar = "NULL";
        }

        $vResumen = array();

        //REALIZAR LOS CARGOS A LA CUENTA.

        $dbconn->BeginTrans();

        foreach($CARGOS_LIQUIDADOS as $K=>$V)
        {
            $sql = "SELECT nextval('cuentas_detalle_transaccion_seq')";

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }

            list($TRANSACCION) = $result->FetchRow();
            $result->Close();

            $sql="INSERT INTO cuentas_detalle
                                (
                                    transaccion,
                                    empresa_id,
                                    centro_utilidad,
                                    numerodecuenta,
                                    departamento,
                                    tarifario_id,
                                    cargo,
                                    cantidad,
                                    precio,
                                    porcentaje_descuento_empresa,
                                    valor_cargo,
                                    valor_nocubierto,
                                    valor_cubierto,
                                    facturado,
                                    fecha_cargo,
                                    usuario_id,
                                    fecha_registro,
                                    valor_descuento_empresa,
                                    valor_descuento_paciente,
                                    porcentaje_descuento_paciente,
                                    servicio_cargo,
                                    autorizacion_int,
                                    porcentaje_gravamen,
                                    sw_cuota_paciente,
                                    sw_cuota_moderadora,
                                    codigo_agrupamiento_id,
                                    consecutivo,
                                    cargo_cups,
                                    sw_cargue,
                                    departamento_al_cargar
                                )
                                VALUES
                                (
                                    $TRANSACCION,
                                    '".$DatosDepartamento['empresa_id']."',
                                    '".$DatosDepartamento['centro_utilidad']."',
                                    ".$cuenta.",
                                    '".$DatosDepartamento['departamento']."',
                                    '".$V['LIQUIDACION']['tarifario_id']."',
                                    '".$V['LIQUIDACION']['cargo']."',
                                    ".$V['LIQUIDACION']['cantidad'].",
                                    ".$V['LIQUIDACION']['precio_plan'].",
                                    ".$V['LIQUIDACION']['porcentaje_descuento_empresa'].",
                                    ".$V['LIQUIDACION']['valor_cargo'].",
                                    ".$V['LIQUIDACION']['valor_nocubierto'].",
                                    ".$V['LIQUIDACION']['valor_cubierto'].",
                                    '".$V['LIQUIDACION']['facturado']."',
                                    NOW(),
                                    ".UserGetUID().",
                                    NOW(),
                                    ".$V['LIQUIDACION']['valor_descuento_empresa'].",
                                    ".$V['LIQUIDACION']['valor_descuento_paciente'].",
                                    ".$V['LIQUIDACION']['porcentaje_descuento_paciente'].",
                                    '".$DatosDepartamento['servicio']."',
                                    '".$V['autorizacion_int']."',
                                    '".$V['LIQUIDACION']['porcentaje_gravamen']."',
                                    '".$V['LIQUIDACION']['sw_cuota_paciente']."',
                                    '".$V['LIQUIDACION']['sw_cuota_moderadora']."',
                                    NULL,
                                    NULL,
                                    '".$V['cargo_cups']."',
                                    '4',
                                    $departamento_al_cargar
                                )
            ";

            $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]".$sql;
                $this->mensajeDeError = $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }

            $sql="UPDATE os_maestro_cargos SET transaccion = $TRANSACCION WHERE os_maestro_cargos_id = ".$V['os_maestro_cargos_id'].";";

            $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }

            $vResumen[$V['os_maestro_cargos_id']]=$TRANSACCION;
        }

        $dbconn->CommitTrans();

        return $vResumen;

    }

    /**
    * Metodo para liquidar cargos.
    *
    * @param integer $cuenta
    * @param array $CARGOS array(array(),array())
    * @return string
    * @access public
    */
    function GetLiquidacionCargo($cuenta,$plan_id,$tarifario,$cargo,$cantidad=1,$servicio=null,$semanas_cotizacion=0, $tipo_id_paciente = '', $paciente_id = '')
    {
        static $OBJ;

        if(!is_object($OBJ))
        {
            if(!IncludeClass("LiquidacionCargos"))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "No se pudo incluir la clase de Liquidacion de Cargos";
                return false;
            }
            $OBJ = new LiquidacionCargos;
        }

        $retorno = $OBJ->LiquidarCargoCuenta($cuenta, $tarifario, $cargo, $cantidad, $descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true, $precio=null, $plan_id, $servicio, $semanas_cotizacion, $tipo_empleador_id = '', $empleador = '',$tipo_id_paciente = '', $paciente_id = '', $tipoUninadTiempo = NULL, $porcentajeDelcargo = NULL);

        if($retorno===false)
        {
            $x = $OBJ->Err();
                        if(!empty($x))
            {
                $this->error = $OBJ->Err();
                $this->mensajeDeError = $OBJ->ErrMsg();
            }
            else
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "La clase LiquidarCargo retorno false.";
            }

            return false;
        }

        return $retorno;
    }


}//fin de la clase

?>