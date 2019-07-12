<?php

/**
* $Id: LiquidacionCargos.class.php,v 1.7 2006/10/25 22:06:14 alex Exp $
*/

/**
* Clase para la liquidacion de Cargos
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.7 $
* @package SIIS
*/
class LiquidacionCargos
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
    * Datos del plan(contrato) sobre la que se esta liquidando
    *
    * @var array
    * @access private
    */
    var $datosPlan=array();

   /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function LiquidacionCargos()
    {
        $this->error='';
        $this->mensajeDeError='';
        $this->datosPlan=array();
        return true;
    }//end of method

    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }//fin del metodo

    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }//fin del metodo

    /**
    * Metodo para establecer los datos del plan con el que se va a liquidar
    *
    * @param array $datosPlan vector con los datos del plan
    * @return array
    * @access public
    */
    function SetDatosPlan($datosPlan)
    {
        $this->datosPlan=&$datosPlan;
        return true;
    }

    /**
    * Metodo para obtener uno o todos los datos del plan con el que se va a liquidar
    *
    * @param array $dato opcional dato que se quiere recuperar
    * @return array
    * @access public
    */
    function GetDatosPlan($dato=null)
    {
        if($dato)
        {
            return $this->datosPlan[$dato];
        }
        else
        {
            return $this->datosPlan;
        }
    }//fin del metodo


    /**
    * Metodo para obtener los precios de un cargo en un plan.
    *
    * @param array $dato opcional dato que se quiere recuperar
    * @return array
    * @access public
    */
    function GetPreciosPlanTarifario($tarifario_id,$cargo)
    {
        static $datos;

        if($datos[$this->datosPlan['plan_id']][$tarifario_id][$cargo])
        {
            return $datos[$this->datosPlan['plan_id']][$tarifario_id][$cargo];
        }

        if(empty($tarifario_id) || empty($cargo))
        {
            $this->error = "CLASS LiquidacionCargos - GetPreciosPlanTarifario - ERROR 01";
            $this->mensajeDeError = "Empty tarifario_id y/o cargo en la llamada del metodo";
            return false;
        }
        if(!$v = $this->PlanTarifario(null, &$tarifario_id, &$cargo, '', '', '', '', '', '*', true, '', '',false))
        {
            if(empty($this->error))
            {
                $this->error = "CLASS LiquidacionCargos - GetPreciosPlanTarifario - ERROR 01";
                $this->mensajeDeError = "Empty tarifario_id y/o cargo en la llamada del metodo";
            }
            return false;
        }

        $datos[$this->datosPlan['plan_id']][$tarifario_id][$cargo] = $v[0];
        return $datos[$this->datosPlan['plan_id']][$tarifario_id][$cargo];
    }//fin del metodo

    /**
    * Metodo para obtener los precios de uno o varios cargos en un plan.
    *
    * @param array $dato opcional dato que se quiere recuperar
    * @return array
    * @access public
    */
    function PlanTarifario($plan_id=null, $tarifario_id='', $cargo='', $grupo_tarifario_id='', $subgrupo_tarifario_id='', $grupo_tipo_cargo='', $tipo_cargo='', $filtro_adicional='', $campos_select='*', $fetch_mode_assoc=false, $Of='',$limit='', $recordSet=true)
    {

        if(empty($plan_id))
        {
            $plan_id = $this->datosPlan['plan_id'];
        }

        if(empty($plan_id)){
            $this->error = "CLASS LiquidacionCargos - PlanTarifario - ERROR 01";
            $this->mensajeDeError = "Empty Plan_id";
        }

        if(!is_numeric($Of) || !is_numeric($limite))
        {
            $limite='';
        }
        else
        {
            $limite="LIMIT $limit OFFSET $Of";
        }

        $filtros = array();
        $filtro ='';
        $filtroCargo ='';
        $filtroCargoExcepcion ='';

        if(!empty($tarifario_id) && !empty($cargo)){
            $filtroCargo = "a.tarifario_id = '$tarifario_id' AND
                            a.cargo = '$cargo' AND ";
        }
        else
        {
            if(!empty($tarifario_id)){
                $filtros[] = "tarifario_id = '$tarifario_id'";
            }

            if(!empty($cargo)){
                $filtros[] = "cargo = '$cargo'";
            }
        }

        if(!empty($tarifario_id)){
            $filtros[] = "tarifario_id = '$tarifario_id'";
        }

        if(!empty($cargo)){
            $filtros[] = "cargo = '$cargo'";
        }

        if(!empty($grupo_tarifario_id)){
            $filtros[] = "grupo_tarifario_id = '$grupo_tarifario_id'";
        }

        if(!empty($subgrupo_tarifario_id)){
            $filtros[] = "subgrupo_tarifario_id = '$subgrupo_tarifario_id'";
        }

        if(!empty($grupo_tipo_cargo)){
            $filtros[] = "grupo_tipo_cargo = '$grupo_tipo_cargo'";
        }

        if(!empty($tipo_cargo)){
            $filtros[] = "tipo_cargo = '$tipo_cargo'";
        }


        if(!empty($filtro_adicional)){
            $filtros[] = "$filtro_adicional";
        }

        foreach($filtros as $v){
            if(!$where){
                $filtro = "WHERE $v";
            }else{
                $filtro .= " AND $v";
            }
            $where=true;
        }

        $query = "SELECT $campos_select
                    FROM (
                        (
                            SELECT b.plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id, a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad, a.tipo_unidad_id, e.sw_copagos
                            FROM tarifarios_detalle a, plan_tarifario b, subgrupos_tarifarios e$filtro_tipo_solicitud_tablas
                            WHERE
                            b.plan_id = ".$plan_id." AND
                            b.grupo_tarifario_id = a.grupo_tarifario_id AND
                            b.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                            b.tarifario_id = a.tarifario_id AND
                            $filtroCargo
                            a.grupo_tarifario_id<>'00' AND
                            a.grupo_tipo_cargo<>'SYS' AND
                            e.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                            e.grupo_tarifario_id = a.grupo_tarifario_id AND
                            excepciones(b.plan_id,b.tarifario_id, a.cargo) = 0
                        )
                            UNION
                        (
                            SELECT b.plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id, a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad, a.tipo_unidad_id, e.sw_copagos
                            FROM tarifarios_detalle a, excepciones b, subgrupos_tarifarios e
                            WHERE
                            $filtroCargo
                            b.plan_id = ".$plan_id." AND
                            b.tarifario_id = a.tarifario_id AND
                            b.sw_no_contratado = 0 AND
                            b.cargo = a.cargo AND
                            e.grupo_tarifario_id = a.grupo_tarifario_id AND
                            e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                        )
                ) AS A $filtro $limite ";

        list($dbconn) = GetDBconn();

        GLOBAL $ADODB_FETCH_MODE;

        if ($fetch_mode_assoc){
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        }else{
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        }

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "CLASS LiquidacionCargos - PlanTarifario - ERROR 02";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($recordSet)
        {
            $salida = &$result;
        }
        else
        {
            $salida = $result->GetRows();
            $result->Close();
        }

        return $salida;

    }//fin del metodo


    /**
    * Metodo para obtener los precios de uno o varios cargos por default.
    *
    * @param array $dato opcional dato que se quiere recuperar
    * @return array
    * @access public
    */
    function DefaultTarifario($plan_id=null, $tarifario_id='', $cargo='', $grupo_tarifario_id='', $subgrupo_tarifario_id='', $grupo_tipo_cargo='', $tipo_cargo='', $filtro_adicional='', $campos_select='*', $fetch_mode_assoc=false, $Of='',$limit='', $recordSet=true)
    {

        if(empty($plan_id))
        {
            $plan_id = $this->datosPlan['plan_id'];
        }

        if(!is_numeric($Of) || !is_numeric($limite))
        {
            $limite='';
        }
        else
        {
            $limite="LIMIT $limit OFFSET $Of";
        }

        $filtros = array();
        $filtro ='';
        $filtroCargo ='';
        $filtroCargoExcepcion ='';

        if(!empty($tarifario_id) && !empty($cargo))
        {
            $filtroCargo = "a.tarifario_id = '$tarifario_id' AND
                            a.cargo = '$cargo' AND ";
        }
        else
        {
            if(!empty($tarifario_id))
            {
                $filtros[] = "a.tarifario_id = '$tarifario_id'";
            }

            if(!empty($cargo))
            {
                $filtros[] = "a.cargo = '$cargo'";
            }
        }

        if(!empty($tarifario_id))
        {
            $filtros[] = "a.tarifario_id = '$tarifario_id'";
        }

        if(!empty($cargo))
        {
            $filtros[] = "a.cargo = '$cargo'";
        }

        if(!empty($grupo_tarifario_id))
        {
            $filtros[] = "a.grupo_tarifario_id = '$grupo_tarifario_id'";
        }

        if(!empty($subgrupo_tarifario_id))
        {
            $filtros[] = "a.subgrupo_tarifario_id = '$subgrupo_tarifario_id'";
        }

        if(!empty($grupo_tipo_cargo))
        {
            $filtros[] = "a.grupo_tipo_cargo = '$grupo_tipo_cargo'";
        }

        if(!empty($tipo_cargo))
        {
            $filtros[] = "a.tipo_cargo = '$tipo_cargo'";
        }


        if(!empty($filtro_adicional))
        {
            $filtros[] = "$filtro_adicional";
        }

        foreach($filtros as $v)
        {
            if(!$where)
            {
                $filtro = "WHERE $v";
            }
            else
            {
                $filtro .= " AND $v";
            }
            $where=true;
        }


        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;

        $PorcentajePorPlan = FALSE;

        if(!empty($plan_id))
        {
            $query = "SELECT $campos_select
                        FROM (
                                SELECT b.plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id, a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje_default as porcentaje, 0 as por_cobertura, '0' as sw_descuento, a.sw_cantidad, a.tipo_unidad_id, e.sw_copagos
                                FROM tarifarios_detalle a, tarifarios_porcentajes_planes_default b, subgrupos_tarifarios e$filtro_tipo_solicitud_tablas
                                WHERE
                                b.plan_id = ".$plan_id." AND
                                $filtroCargo
                                a.grupo_tarifario_id<>'00' AND
                                a.grupo_tipo_cargo<>'SYS' AND
                                e.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                                e.grupo_tarifario_id = a.grupo_tarifario_id
                            ) AS a $filtro $limite ";

            if ($fetch_mode_assoc)
            {
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            }
            else
            {
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            }

            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "CLASS LiquidacionCargos - PlanTarifario - ERROR 01";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {
                $PorcentajePorPlan = TRUE;
            }
            else
            {
                $result->Close;
            }
        }


        if(!$PorcentajePorPlan)
        {
            if(!empty($plan_id))
            {
                $campo_plan_id = "$plan_id as plan_id";
            }
            else
            {
                $campo_plan_id = "NULL as plan_id";
            }

            $query = "SELECT $campos_select
                        FROM (
                                SELECT $campo_plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id, a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, a.porcentaje_default as porcentaje, 0 as por_cobertura, '0' as sw_descuento, a.sw_cantidad, a.tipo_unidad_id, e.sw_copagos
                                FROM tarifarios_detalle a, subgrupos_tarifarios e$filtro_tipo_solicitud_tablas
                                WHERE
                                a.grupo_tarifario_id<>'00' AND
                                a.grupo_tipo_cargo<>'SYS' AND
                                $filtroCargo
                                e.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                                e.grupo_tarifario_id = a.grupo_tarifario_id
                            ) AS a $filtro $limite ";

            if ($fetch_mode_assoc)
            {
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            }
            else
            {
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            }

            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "CLASS LiquidacionCargos - PlanTarifario - ERROR 02";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }

        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($recordSet)
        {
            $salida = &$result;
        }
        else
        {
            $salida = $result->GetRows();
            $result->Close();
        }

        return $salida;

    }//fin del metodo



    /**
    * Metodo para obtener los precios de un cargo.
    *
    * @param integer $plan_id
    * @param string $tarifario
    * @param string $cargo
    * @param integer $cantidad
    * @param numeric $precio
    * @param array $excepcionesAdicionales
    * @param string $tipoUninadTiempo
    * @param numeric $porcentajeDelcargo
    * @return array
    * @access public
    */
    function LiquidarCargo($plan_id=NULL,$tarifario,$cargo,$cantidad=1,$precio=NULL,$excepcionesAdicionales=array(),$tipoUninadTiempo=NULL,$porcentajeDelcargo=NULL)
    {
        if(empty($plan_id))
        {
            $plan_id = $this->datosPlan['plan_id'];
        }

        if(empty($plan_id) || empty($tarifario) || empty($cargo))
        {
            $this->error = "CLASS LiquidacionCargos - LiquidarCargo - ERROR 01";
            $this->mensajeDeError = 'FALTAN ARGUMENTOS PARA EL LLAMADO DEL METODO';
            return false;
        }

        $result = $this->PlanTarifario($plan_id,$tarifario,$cargo,'','','','','','*',true,'','',true);
        if($result===false)
        {
            if(empty($this->error))
            {
                $this->error = "CLASS LiquidacionCargos - LiquidarCargo - ERROR 02";
                $this->mensajeDeError = 'Llamado al metodo PlanTarifario() retorno False';
            }
            return false;
        }

        if($result->EOF)
        {
            $result = $this->DefaultTarifario($plan_id,$tarifario,$cargo,'','','','','','*',true,'','',true);

            if($result===false)
            {
                if(empty($this->error))
                {
                    $this->error = "CLASS LiquidacionCargos - LiquidarCargo - ERROR 03";
                    $this->mensajeDeError = 'Llamado al metodo PlanTarifario() retorno False';
                }
                return false;
            }

            if($result->EOF)
            {
                $this->error = "CLASS LiquidacionCargos - LiquidarCargo - ERROR 04";
                $this->mensajeDeError = "No se pudo obtener un valor para el cargo [$tarifario][$cargo]";
                return false;
            }
        }

        $cargo_info = $result->FetchRow();
        $result->Close();

        if(!empty($excepcionesAdicionales))
        {
            list($dbconn) = GetDBconn();
            global $ADODB_FETCH_MODE;

            $datosEdad=CalcularEdad($excepcionesAdicionales['fecha_nacimiento'],$excepcionesAdicionales['fecha_registro']);
            $edad=$datosEdad['edad_en_dias'];
            $valorFecha=str_replace("/","-",$excepcionesAdicionales['fecha_registro']);
            $valorFecha=explode(" ",$valorFecha);
            $valorFecha=explode("-",$valorFecha[0]);
            if(checkdate($valorFecha[1],$valorFecha[2],$valorFecha[0]))
            {
                $anho = $valorFecha[0];
            }
            else
            {
                $anho = date('Y');
            }

            $query = "SELECT count(*) FROM planes_excepciones_adicionales
                        WHERE plan_id=".$plan_id."
                        AND tarifario_id='".$tarifario."'
                        AND cargo='".$cargo."'
                        AND (numero_veces  IS NOT NULL)
                        ";
            $resultado = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "CLASS LiquidacionCargos - LiquidarCargo - ERROR 05";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            list($existeExcepcioVeces)=$resultado->FetchRow();
            $resultado->Close();

            if($existeExcepcioVeces)
            {

                $query = "SELECT SUM(c.cantidad) FROM ingresos a,cuentas b,cuentas_detalle c
                            WHERE a.tipo_id_paciente = '".$excepcionesAdicionales['tipo_id_paciente']."'
                            AND a.paciente_id = '".$excepcionesAdicionales['paciente_id']."'
                            AND b.ingreso=a.ingreso
                            AND c.numerodecuenta=b.numerodecuenta
                            AND c.tarifario_id='".$tarifario."'
                            AND c.cargo='".$cargo."'
                            AND date_part('year', b.fecha_registro )='".$anho."'
                        ";

                $resultado = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CLASS LiquidacionCargos - LiquidarCargo - ERROR 06";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                if (!$result->EOF)
                {
                    list($numero_veces)=$resultado->FetchRow();
                    $resultado->Close();
                }

            }

            if(!is_numeric($edad)) $edad=0;
            if(!is_numeric($numero_veces)) $numero_veces=0;

            $query = " (
                            SELECT porcentaje,por_cobertura,precio,sw_copago,sw_cuota_moderadora
                            FROM  planes_excepciones_adicionales
                            WHERE plan_id = ".$plan_id."
                            AND tarifario_id='".$tarifario."'
                            AND cargo='".$cargo."'
                            AND (edad_min <= ".$edad." OR edad_min ISNULL)
                            AND (edad_max >= ".$edad." OR edad_max ISNULL)
                            AND (numero_veces <= ".$numero_veces." OR numero_veces ISNULL)
                            AND sw_excluir_rango_edad='0'
                        )
                        UNION
                        (
                            SELECT porcentaje,por_cobertura,precio,sw_copago,sw_cuota_moderadora
                            FROM  planes_excepciones_adicionales
                            WHERE plan_id = ".$plan_id."
                            AND tarifario_id = '".$tarifario."'
                            AND cargo = '".$cargo."'
                            AND NOT((edad_min <= ".$edad." OR edad_min ISNULL)
                            AND (edad_max >= ".$edad." OR edad_max ISNULL))
                            AND (numero_veces <= ".$numero_veces." OR numero_veces ISNULL)
                            AND sw_excluir_rango_edad='1'
                        ) ";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resultado = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "CLASS LiquidacionCargos - LiquidarCargo - ERROR 07";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if(!$resultado->EOF)
            {
                $excepcion = $resultado->FetchRow();

                if(is_numeric($excepcion['porcentaje'])) $cargo_info['porcentaje']=$excepcion['porcentaje'];
                if(is_numeric($excepcion['por_cobertura'])) $cargo_info['por_cobertura']=$excepcion['por_cobertura'];
                if(is_numeric($excepcion['precio'])) $cargo_info['precio']=$excepcion['precio'];
                if(is_numeric($excepcion['sw_copago'])) $valor['sw_copago_excepcion']=$excepcion['sw_copago'];
                if(is_numeric($excepcion['sw_cuota_moderadora'])) $valor['sw_cuota_moderadora_excepcion']=$excepcion['sw_cuota_moderadora'];
            }
            $resultado->Close();
        }

        switch($cargo_info['tipo_unidad_id'])
        {
            case '02' :
                //UNIDAD UVRS
                //echo $cargo_info['precio'].'UVRS';
            break;

            case '03' :
                //UNIDAD EN SMMLV
                $salario=GetSalarioMinimo(date('Y'));
                $cargo_info['precio']=$cargo_info['precio']*$salario;
            break;

            case '04' :
                //UNIDAD EN GRUPOS QUIRURJICOS
                //echo $cargo_info['precio'].'GQ';
            break;

            case '05' :
                //UNIDAD UVRS
                $uvr = $this->UVRPaquetePlan($plan_id,$tarifario);
                $cargo_info['precio'] = $cargo_info['precio'] * $uvr;
            break;

            case '06' :
                //UNIDAD PESOS X MINUTO
                if($tipoUninadTiempo == 'H') // si la cantida viene en horas convierto el precio a HORAS
                {
                    $cargo_info['precio'] = $cargo_info['precio']*60;
                }
            break;

            case '07' :
                //UNIDAD PESOS X HORA
                if($tipoUninadTiempo != 'H') // si la cantida no viene en horas convierto el precio a minutos
                {
                    $cargo_info['precio'] = $cargo_info['precio']/60;
                }
            break;
            default;
                //EN PESOS
                $cargo_info['precio']=$cargo_info['precio'];
            break;
        }

        if($precio===null)
        {
            $valor['precio_plan'] = round(($cargo_info['precio'] + ($cargo_info['precio'] * $cargo_info['porcentaje'] / 100)),GetDigitosRedondeo());
        }
        else
        {
            $valor['precio_plan'] = round($precio,GetDigitosRedondeo());
        }

        if($porcentajeDelcargo != NULL)
        {
            $valor['precio_plan'] = round(($valor['precio_plan'] * $porcentajeDelcargo / 100 ),GetDigitosRedondeo());
        }

        $valor['cantidad'] = $cantidad;
        $valor['valor_cargo'] = round(($valor['precio_plan'] * $cantidad),GetDigitosRedondeo());
        $valor['valor_cubierto'] = round(($valor['valor_cargo'] * $cargo_info['por_cobertura'] /100),GetDigitosRedondeo());
        $valor['valor_no_cubierto'] = round(($valor['valor_cargo'] - $valor['valor_cubierto']),GetDigitosRedondeo());
        $valor['porcentaje_gravamen'] = $cargo_info['gravamen'];
        $valor['descripcion'] = $cargo_info['descripcion'];
        $valor['tarifario_id'] = $cargo_info['tarifario_id'];
        $valor['cargo'] = $cargo_info['cargo'];
        $valor['sw_copagos'] = $cargo_info['sw_copagos'];


        return($valor);

    }


    /**
    * Metodo para liquidar el valor de un cargo en una cuenta (tiene en cuenta el tipo de afiliado y el rango para
    * el manejo de copagos y cuotas moderadoras.
    *
    * @return array
    * @param integer Numero de la cuenta
    * @param string tarifario_id y cargo
    * @param string tarifario_id y cargo
    * @param string $tipoUninadTiempo Cuando la cantidad se refiere a tiempo. (H : Horas,  M: Minutos) Por Default M:Minutos
    * @param integer cantidad del cargo para liquidar
    */

    function LiquidarCargoCuenta($cuenta=0 ,$tarifario ,$cargo ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true, $precio=null, $planId='', $Servicio='', $semanas_cotizacion='', $tipo_empleador_id = '', $empleador = '',$tipo_id_paciente = '', $paciente_id = '', $tipoUninadTiempo = NULL, $porcentajeDelcargo = NULL)
    {
        if((empty($cuenta) && (empty($planId) || $Servicio==='')) || empty($tarifario) || empty($cargo))
        {
            $this->error = "CLASS LiquidacionCargos - LiquidarCargoCuenta - ERROR 01";
            $this->mensajeDeError = 'FALTAN ARGUMENTOS PARA LLAMAR EL METODO';
            return false;
        }

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        //SI SE PASO EL NUMERO DE LA CUENTA
        if(!empty($cuenta))
        {
//             $query = "  SELECT
//                             a.plan_id,
//                             c.servicio,
//                             a.semanas_cotizadas,
//                             e.tipo_id_empleador,
//                             e.empleador_id,
//                             d.paciente_id,
//                             d.tipo_id_paciente,
//                             d.fecha_nacimiento,
//                             d.sexo_id,
//                             a.fecha_registro
//
//                         FROM
//                             cuentas a,
//                             ingresos b LEFT JOIN ingresos_empleadores as e ON(b.ingreso=e.ingreso),
//                             departamentos c,
//                             pacientes d
//
//                         WHERE a.numerodecuenta = $cuenta
//                         AND b.ingreso=a.ingreso
//                         AND c.departamento=b.departamento_actual
//                         AND d.paciente_id= b.paciente_id
//                         AND d.tipo_id_paciente= b.tipo_id_paciente";


//desnormalizar ingresos_empleadores porque deja muy lento este query
//por el momento queda desabilitado
//ADICIONALMENTE NO SE ESTA HACIENDO NADA CON ESTO.
            $query = "
                        SELECT
                            a.plan_id,
                            c.servicio,
                            a.semanas_cotizadas,
                            d.paciente_id,
                            d.tipo_id_paciente,
                            d.fecha_nacimiento,
                            d.sexo_id,
                            a.fecha_registro

                        FROM
                            cuentas a,
                            ingresos b,
                            departamentos c,
                            pacientes d

                        WHERE a.numerodecuenta = $cuenta
                        AND b.ingreso=a.ingreso
                        AND c.departamento=b.departamento_actual
                        AND d.paciente_id= b.paciente_id
                        AND d.tipo_id_paciente= b.tipo_id_paciente

            ";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resultado = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "CLASS LiquidacionCargos - LiquidarCargoCuenta - ERROR 02";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if($resultado->EOF)
            {
                $this->error = "CLASS LiquidacionCargos - LiquidarCargoCuenta - ERROR 03";
                $this->mensajeDeError = "LA CUENTA No. $cuenta NO EXISTE.";
                return false;
            }

            $DatosCuenta=$resultado->FetchRow();
            $resultado->Close();

            if(!empty($Servicio))
            {
                $DatosCuenta['servicio']=$Servicio;
            }

            $datosExcepcionesAdicionales['fecha_nacimiento']=$DatosCuenta['fecha_nacimiento'];
            $datosExcepcionesAdicionales['fecha_registro']=$DatosCuenta['fecha_registro'];
            $datosExcepcionesAdicionales['paciente_id']=$DatosCuenta['paciente_id'];
            $datosExcepcionesAdicionales['tipo_id_paciente']=$DatosCuenta['tipo_id_paciente'];
            $datosExcepcionesAdicionales['sexo_id']=$DatosCuenta['sexo_id'];

        }
        else
        {
            //datos ingresados por parametro opcion 2

            if(!empty($paciente_id) && !empty($tipo_id_paciente))
            {

                $query = "  SELECT  fecha_nacimiento,
                                    sexo_id
                            FROM pacientes
                            WHERE paciente_id='$paciente_id' AND tipo_id_paciente='$tipo_id_paciente';";

                $resultado = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CLASS LiquidacionCargos - LiquidarCargoCuenta - ERROR 04";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                if (!$resultado->EOF)
                {
                    list($datosExcepcionesAdicionales['fecha_nacimiento'],$datosExcepcionesAdicionales['sexo_id'])=$resultado->FetchRow();
                    $resultado->Close();

                    $datosExcepcionesAdicionales['paciente_id'] = $paciente_id;
                    $datosExcepcionesAdicionales['tipo_id_paciente'] = $tipo_id_paciente;
                    $datosExcepcionesAdicionales['fecha_registro'] = date("Y-m-d");
                }
                else
                {
                    $datosExcepcionesAdicionales=array();
                }
            }

            $DatosCuenta['plan_id']=$planId;
            $DatosCuenta['servicio']=$Servicio;
            $DatosCuenta['semanas_cotizadas']=$semanas_cotizacion;
            $DatosCuenta['empleador_id']=$empleador;
            $DatosCuenta['tipo_id_empleador']=$tipo_empleador_id;
        }

        $valor = $this->LiquidarCargo($DatosCuenta['plan_id'],$tarifario,$cargo,$cantidad,$precio,$datosExcepcionesAdicionales,$tipoUninadTiempo,$porcentajeDelcargo);

        if($valor===false)
        {
            if(empty($this->error))
            {
                $this->error = "CLASS LiquidacionCargos - LiquidarCargoCuenta - ERROR 05";
                $this->mensajeDeError = 'Llamado al metodo LiquidarCargo() retorno False';
            }
            return false;
        }

        if(!is_array($valor))
        {
            $this->error = "CLASS LiquidacionCargos - LiquidarCargoCuenta - ERROR 06";
            $this->mensajeDeError = "EL METODO LiquidarCargo() NO RETORNO UN ARREGLO.(PLAN : $DatosCuenta[plan_id], TARIFARIO : $tarifario, CARGO : $cargo)";
            return false;
        }

        //Por defecto inicializo algunos valores

        $valor['facturado']=1;
        $valor['sw_cuota_paciente']=0;
        $valor['sw_cuota_moderadora']=0;
        $valor['valor_descuento_empresa']=0;
        $valor['valor_descuento_paciente']=0;


        //verificar si el cargo esta paragrafado por servicio

        $query="SELECT COUNT(*)
                FROM planes_paragrafados_cargos
                WHERE
                plan_id = $DatosCuenta[plan_id] AND
                tarifario_id = '$tarifario' AND
                cargo = '$cargo' AND
                servicio = '$DatosCuenta[servicio]'
                ";
        $resultado = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionCargos - LiquidarCargoCuenta - ERROR 07";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        list($cargo_paragrafado) = $resultado->FetchRow();
        $resultado->Close();


        //retorno 0 si el cargo es un paragrafado, retorno 1 si el campo se debe facturar (No paragrafado)
        if($cargo_paragrafado)
        {
            $valor['facturado']= 0;
        }

        if (isset($valor['sw_copago_excepcion']) || isset($valor['sw_cuota_moderadora_excepcion']))
        {
            if(is_numeric($valor['sw_copago_excepcion'])) $valor['sw_cuota_paciente']=$valor['sw_copago_excepcion'];
            if(is_numeric($valor['sw_cuota_moderadora_excepcion'])) $valor['sw_cuota_moderadora']=$valor['sw_cuota_moderadora_excepcion'];
            unset($valor['sw_copago_excepcion']);
            unset($valor['sw_cuota_moderadora_excepcion']);
        }
        else
        {

            //Si el subgrupo_tarifario del cargo en la tabla planes_copagos esta parametrizado por servicio
            //sw_copagos=0 indica que no que es en general 1 indica que es por servicio
            if($valor['sw_copagos'])
            {
                $servicio_copago = $DatosCuenta['servicio'];
            }
            else
            {
                $servicio_copago = '0';
            }
            //traer el copago y la cuota moderadora del cargo
            $query = "  (
                            SELECT a.sw_copago, a.sw_cuota_moderadora
                            FROM planes_copagos a, tarifarios_detalle b
                            WHERE
                            b.tarifario_id = '$tarifario' AND
                            b.cargo = '$cargo' AND
                            a.grupo_tarifario_id = b.grupo_tarifario_id AND
                            a.subgrupo_tarifario_id = b.subgrupo_tarifario_id AND
                            a.plan_id = $DatosCuenta[plan_id] AND
                            a.servicio='$servicio_copago' AND
                            excepciones_copago($DatosCuenta[plan_id],'$tarifario','$cargo','$servicio_copago') = 0
                        )
                        UNION
                        (
                            SELECT sw_copago, sw_cuota_moderadora
                            FROM excepciones_copagos
                            WHERE
                            plan_id = $DatosCuenta[plan_id] AND
                            tarifario_id = '$tarifario' AND
                            cargo = '$cargo' AND
                            servicio='$servicio_copago'
                        )";

            $resultado = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "CLASS LiquidacionCargos - LiquidarCargoCuenta - ERROR 08";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if(!$resultado->EOF)
            {
                list($sw_copago,$sw_cuota_moderadora)=$resultado->FetchRow();
            }

            $resultado->Close();

            if($sw_copago)
            {
                $valor['sw_cuota_paciente']=1;
            }

            if($sw_cuota_moderadora)
            {
                $valor['sw_cuota_moderadora']=1;
            }
        }

        unset($valor['sw_copagos']);

        return($valor);

    }//fin de LiquidarCargoCuenta


    /**
    * Metodo para Obtener los cargos contratados para un cargo cups
    *
    * @return array
    * @access private
    */
    function GetCargoPlan($cargo_cups,$plan_id)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "(
                    SELECT b.plan_id, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad
                    FROM tarifarios_detalle a, plan_tarifario b, tarifarios_equivalencias c

                    WHERE b.plan_id = $plan_id
                    AND a.grupo_tarifario_id = b.grupo_tarifario_id
                    AND a.subgrupo_tarifario_id = b.subgrupo_tarifario_id
                    AND a.tarifario_id = b.tarifario_id
                    AND c.cargo_base = '$cargo_cups'
                    AND c.tarifario_id = a.tarifario_id
                    AND c.cargo=a.cargo
                    AND excepciones(b.plan_id,b.tarifario_id, a.cargo) = 0
                )
                    UNION
                (
                    SELECT b.plan_id, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad
                    FROM tarifarios_detalle a, excepciones b, tarifarios_equivalencias c

                    WHERE c.cargo_base = '$cargo_cups'
                    AND b.plan_id = $plan_id
                    AND b.tarifario_id = c.tarifario_id
                    AND b.cargo = c.cargo
                    AND a.tarifario_id = c.tarifario_id
                    AND a.cargo = c.cargo
                    AND b.sw_no_contratado = 0
                )";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "CLASS LiquidacionQX - GetCargoPlan - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $cargos_contratados_plan=$result->GetRows();

        $result->Close();

        return $cargos_contratados_plan;
    }

   /**
    * Metodo que retorna los valores de las uvrs para el plan
    *
    * @return boolean
    * @access private
    */
    function GetValoresUVRSxPlan($plan_id, $tarifario_id, $clase='', $tipo='')
    {
        static $ValoresUVRS;

        if($clase)
        {
            if($tipo)
            {
                if($ValoresUVRS[$plan_id][$tarifario_id][$clase][$tipo]) return $ValoresUVRS[$plan_id][$tarifario_id][$clase][$tipo];
            }
            else
            {
                if($ValoresUVRS[$plan_id][$tarifario_id][$clase]) return $ValoresUVRS[$plan_id][$tarifario_id][$clase];
            }
        }
        else
        {
            if($ValoresUVRS[$plan_id][$tarifario_id]) return $ValoresUVRS[$plan_id][$tarifario_id];
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT * FROM tarifarios_uvrs WHERE tarifario_id='$tarifario_id';";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_02 - GetUVRS_Plan - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "CLASS  TipoLiquidacionQX_02 - GetUVRS_Plan - ERROR 02";
            $this->mensajeDeError = "No esta parametrizado el tarifario $tarifario_id para uvrs.";
            return false;
        }

        $uvrs_tarifario=$result->FetchRow();
        $result->Close();

        $sql = "SELECT * FROM planes_uvrs WHERE tarifario_id='$tarifario_id' AND plan_id=$plan_id;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_02 - GetUVRS_Plan - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if(!$result->EOF)
        {
            $v=$result->FetchRow();
            $ValoresUVRS[$plan_id][$tarifario_id]['DC']['VALOR']=$v['dc_valor'];
            if($v['da_valor']) $ValoresUVRS[$plan_id][$tarifario_id]['DA']['VALOR']=$v['da_valor'];
            if($v['dy_valor']) $ValoresUVRS[$plan_id][$tarifario_id]['DY']['VALOR']=$v['dy_valor'];
            unset($v);
        }
        $result->Close();

        if(empty($ValoresUVRS[$plan_id][$tarifario_id]['DC']['VALOR'])) $ValoresUVRS[$plan_id][$tarifario_id]['DC']['VALOR']=$uvrs_tarifario['dc_valor'];
        if(empty($ValoresUVRS[$plan_id][$tarifario_id]['DA']['VALOR'])) $ValoresUVRS[$plan_id][$tarifario_id]['DA']['VALOR']=$uvrs_tarifario['da_valor'];
        if(empty($ValoresUVRS[$plan_id][$tarifario_id]['DY']['VALOR'])) $ValoresUVRS[$plan_id][$tarifario_id]['DY']['VALOR']=$uvrs_tarifario['dy_valor'];
        $ValoresUVRS[$plan_id][$tarifario_id]['DC']['CARGO']=$uvrs_tarifario['dc_cargo'];
        $ValoresUVRS[$plan_id][$tarifario_id]['DA']['CARGO']=$uvrs_tarifario['da_cargo'];
        $ValoresUVRS[$plan_id][$tarifario_id]['DY']['CARGO']=$uvrs_tarifario['dy_cargo'];
        $ValoresUVRS[$plan_id][$tarifario_id]['DC']['CUPS']=$uvrs_tarifario['dc_cups'];
        $ValoresUVRS[$plan_id][$tarifario_id]['DA']['CUPS']=$uvrs_tarifario['da_cups'];
        $ValoresUVRS[$plan_id][$tarifario_id]['DY']['CUPS']=$uvrs_tarifario['dy_cups'];
        unset($uvrs_tarifario);

        if($clase)
        {
            if($tipo)
            {
                if($ValoresUVRS[$plan_id][$tarifario_id][$clase][$tipo]) return $ValoresUVRS[$plan_id][$tarifario_id][$clase][$tipo];
            }
            else
            {
                if($ValoresUVRS[$plan_id][$tarifario_id][$clase]) return $ValoresUVRS[$plan_id][$tarifario_id][$clase];
            }
        }

       return $ValoresUVRS[$plan_id][$tarifario_id];

    }//fin del metodo

    function UVRPaquetePlan($plan,$tarifario)
    {
        $uvr=&$this->GetValoresUVRSxPlan(&$plan, &$tarifario, $clase='DC', $tipo='VALOR');
        return $uvr;
    }


    function InsertarCuentasDetalle($EmpresaId,$CUtilidad,$cuenta,$plan,$arr,$sql,&$dbconn)
    {
                //arr es asociativo cargo,tarifario,servicio, aut_int, aut_ext, cups,
                //cantidad, departamento, sw_cargue *tipo_tercero y tercero son utilizados para honorarios
                IncludeLib("tarifario_cargos");
                $x='';
                if(empty($dbconn))
                {
                        list($dbconn) = GetDBconn();
                        $dbconn->BeginTrans();
                        $x=1;
                }

                for($i=0; $i<sizeof($arr); $i++)
                {
                        $query=" SELECT nextval('cuentas_detalle_transaccion_seq')";
                        $result=$dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                                $dbconn->RollbackTrans();
                                return false;
                        }
                        $Transaccion=$result->fields[0];
                        $result->Close();

                                                                //($cuenta=0 ,$tarifario ,$cargo ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true, $precio=0, $planId='', $Servicio='', $semanas_cotizacion='')
                        $liq=LiquidarCargoCuenta($cuenta,$arr[$i][tarifario],$arr[$i][cargo],$arr[$i][cantidad],0,0,true,true,0,$plan,$arr[$i][servicio],'');
                        $codigo='NULL';
                        //$agru=BuscarGrupoTipoCargo($arr[$i][cups]);
                        $agru = BuscarGrupoTipoCargo($arr[$i][cargo],$arr[$i][tarifario],&$dbconn);
                        if(!empty($agru))
                        {  $codigo=$agru;  }
                        //{  $codigo=$agru[codigo_agrupamiento_id];  }

                        if($arr[$i][aut_int]==='0' OR $arr[$i][aut_intcion_int] >0)
                        {   $arr[$i][aut_int]=$arr[$i][aut_int];   }
                        else
                        {   $arr[$i][aut_int]='NULL';   }
                        if($arr[$i][aut_ext]==='0' OR $arr[$i][aut_ext] >0)
                        {   $arr[$i][aut_ext]=$arr[$i][aut_ext];   }
                        else
                        {   $arr[$i][aut_ext]='NULL';   }

            if(!empty($arr[$i][fecha_cargo])){
              $fecha_cargo=$arr[$i][fecha_cargo];
            }else{
              $fecha_cargo=date("Y-m-d");
            }
                $query = "INSERT INTO cuentas_detalle (
                                    transaccion,
                                    empresa_id,
                                    centro_utilidad,
                                    numerodecuenta,
                                    departamento,
                                    tarifario_id,
                                    cargo,
                                    cantidad,
                                    precio,
                                    valor_cargo,
                                    valor_nocubierto,
                                    valor_cubierto,
                                    usuario_id,
                                    facturado,
                                    fecha_cargo,
                                    valor_descuento_empresa,
                                    valor_descuento_paciente,
                                    servicio_cargo,
                                    autorizacion_int,
                                    autorizacion_ext,
                                    porcentaje_gravamen,
                                    sw_cuota_paciente,
                                    sw_cuota_moderadora,
                                    codigo_agrupamiento_id,
                                    fecha_registro,
                                    cargo_cups,
                                    sw_cargue)
                        VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$cuenta,'".$arr[$i][departamento]."','".$arr[$i][tarifario]."','".$arr[$i][cargo]."',".$arr[$i][cantidad].",".$liq[precio_plan].",".$liq[valor_cargo].",".$liq[valor_no_cubierto].",".$liq[valor_cubierto].",".UserGetUID().",".$liq[facturado].",'$fecha_cargo',".$liq[valor_descuento_paciente].",".$liq[valor_descuento_empresa].",".$arr[$i][servicio].",".$arr[$i][aut_int].",".$arr[$i][aut_ext].",".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',".$codigo.",'now()','".$arr[$i][cups]."','".trim($arr[$i][sw_cargue])."');";

                        $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                                $dbconn->RollbackTrans();
                                return false;
                        }

                        if(!empty($arr[$i][tipo_tercero]) AND !empty($arr[$i][tercero]))
                        {
                                    $query = "INSERT INTO cuentas_detalle_profesionales(
                                                                                                                                    transaccion,
                                                                                                                                    tipo_tercero_id,
                                                                                                                                    tercero_id)
                                                        VALUES($Transaccion,'".$arr[$i][tipo_tercero]."','".$arr[$i][tercero]."')";
                                    $dbconn->Execute($query);
                                    if($dbconn->ErrorNo() != 0) {
                                            $dbconn->RollbackTrans();
                                            return false;
                                    }
                        }

                        //es de atencion de ordenes de servicio (Os_Atencion)
                        if(!empty($arr[$i][numero_orden_id]))
                        {
                                $sql = "UPDATE os_maestro_cargos SET transaccion=$Transaccion
                                            WHERE numero_orden_id=".$arr[$i][numero_orden_id]."
                                            AND cargo='".$arr[$i][cargo]."'
                                            AND tarifario_id='".$arr[$i][tarifario]."';";
                                $dbconn->Execute($sql);
                                if($dbconn->ErrorNo() != 0) {
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                        }
                }

                if(!empty($sql))
                {
                        $dbconn->Execute($sql);
                        if($dbconn->ErrorNo() != 0) {
                                $dbconn->RollbackTrans();
                                return false;
                        }
                }
                if(!empty($x))
                {   $dbconn->CommitTrans();     }
                return true;
    }

}//fin de la clase

?>


