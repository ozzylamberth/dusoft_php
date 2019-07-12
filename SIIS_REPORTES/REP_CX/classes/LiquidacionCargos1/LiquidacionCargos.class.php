<?php

/**
* $Id: LiquidacionCargos.class.php,v 1.2 2005/10/13 21:27:27 alex Exp $
*/

/**
* Clase para la liquidacion de Cargos
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.2 $
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

        if(empty($plan_id)){
            $this->error = "CLASS LiquidacionCargos - PlanTarifario - ERROR 01";
            $this->mensajeDeError = "Empty Plan_id";
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

    function LiquidarCargo($plan_id=null,$tarifario,$cargo,$cantidad=1,$precio=null,$excepcionesAdicionales=array())
    {
        if(empty($plan_id))
        {
            $plan_id = $this->datosPlan['plan_id'];
        }

        if(empty($plan_id) || empty($tarifario) || empty($cargo)){
            $this->error = "CLASS LiquidacionCargos - LiquidarCargo - ERROR 01";
            $this->mensajeDeError = '';
            return false;
        }

        $result = $this->PlanTarifario($plan_id,$tarifario,$cargo,'','','','','','*',true,'','',true);

        if($result->EOF){
            $this->error = "CLASS LiquidacionCargos - LiquidarCargo - ERROR 02";
            $this->mensajeDeError = '';
            return false;
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
            if ($dbconn->ErrorNo() == 0)
            {
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
                    if ($dbconn->ErrorNo() == 0)
                    {
                        list($numero_veces)=$resultado->FetchRow();
                        $resultado->Close();
                    }

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

            if ($dbconn->ErrorNo() == 0)
            {
                if(!$resultado->EOF)
                {
                    $excepcion=$resultado->FetchRow();

                    if(is_numeric($excepcion['porcentaje'])) $cargo_info['porcentaje']=$excepcion['porcentaje'];
                    if(is_numeric($excepcion['por_cobertura'])) $cargo_info['por_cobertura']=$excepcion['por_cobertura'];
                    if(is_numeric($excepcion['precio'])) $cargo_info['precio']=$excepcion['precio'];
                    if(is_numeric($excepcion['sw_copago'])) $valor['sw_copago_excepcion']=$excepcion['sw_copago'];
                    if(is_numeric($excepcion['sw_cuota_moderadora'])) $valor['sw_cuota_moderadora_excepcion']=$excepcion['sw_cuota_moderadora'];
                }
                $resultado->Close();
            }
        }

        if($precio===null){
            $valor['precio_plan'] = round(($cargo_info['precio'] + ($cargo_info['precio'] * $cargo_info['porcentaje'] / 100)),GetDigitosRedondeo());
        }else{
            $valor['precio_plan'] = round($precio,GetDigitosRedondeo());
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
    * @param integer cantidad del cargo para liquidar
    */

    function LiquidarCargoCuenta($cuenta=0 ,$tarifario ,$cargo ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true, $precio=null, $planId='', $Servicio='', $semanas_cotizacion='', $tipo_empleador_id = '', $empleador = '',$tipo_id_paciente = '', $paciente_id = '')
    {

    //obligatorios opcion 1 : cuenta, tarifario y cargo
    //obligatorios opcion 2 : plan, tarifario, cargo y servicio.
    //echo "tari=>$tarifario cargo=$cargo ser=>$Servicio plan=<$planId";
        if((empty($cuenta) && (empty($planId) || $Servicio==='')) || empty($tarifario) || empty($cargo)){
    //         echo "plan=>$planId   tarifario=>$tarifario  crago=>$cargo  servicio=>$Servicio  cuenta=>$cuenta";
    //         echo "<br>LiquidarCargoCuenta Salida 1 <br> ";
            return false;
        }

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        if(!empty($cuenta)){

            //opcion 1 llega como parametro el numero de cuenta.
            // Buscar datos de la cuenta para la opcion 1
            $query = "SELECT a.plan_id, c.servicio, a.semanas_cotizadas,e.tipo_id_empleador,e.empleador_id,d.paciente_id,d.tipo_id_paciente,d.fecha_nacimiento,d.sexo_id,a.fecha_registro
                        FROM cuentas a,ingresos b left join ingresos_empleadores as e on(b.ingreso=e.ingreso),departamentos c,pacientes d
                        WHERE a.numerodecuenta = $cuenta AND
                        b.ingreso=a.ingreso AND
                        c.departamento=b.departamento_actual
                        AND d.paciente_id= b.paciente_id
                        AND d.tipo_id_paciente= b.tipo_id_paciente";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resultado = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if ($dbconn->ErrorNo() != 0) {
                die($dbconn->ErrorMsg());
                return false;
            }

            if($resultado->EOF){
                echo "<br>LiquidarCargoCuenta Salida 2 <br> ";
                return false; // si la cuenta no existe
            }

            $DatosCuenta=$resultado->FetchRow();
            $resultado->Close();

            $datosExcepcionesAdicionales['fecha_nacimiento']=$DatosCuenta['fecha_nacimiento'];
            $datosExcepcionesAdicionales['fecha_registro']=$DatosCuenta['fecha_registro'];
            $datosExcepcionesAdicionales['paciente_id']=$DatosCuenta['paciente_id'];
            $datosExcepcionesAdicionales['tipo_id_paciente']=$DatosCuenta['tipo_id_paciente'];
            $datosExcepcionesAdicionales['sexo_id']=$DatosCuenta['sexo_id'];

        }else{
        //datos ingresados por parametro opcion 2

            if(!empty($paciente_id) && !empty($tipo_id_paciente))
            {

                $query = "SELECT fecha_nacimiento,sexo_id FROM pacientes WHERE paciente_id='$paciente_id' AND tipo_id_paciente='$tipo_id_paciente';";
                $resultado = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() == 0 && !$resultado->EOF)
                {
                    list($datosExcepcionesAdicionales['fecha_nacimiento'],$datosExcepcionesAdicionales['sexo_id'])=$resultado->FetchRow();
                    $resultado->Close();
                    $datosExcepcionesAdicionales['paciente_id']=$paciente_id;
                    $datosExcepcionesAdicionales['tipo_id_paciente']=$tipo_id_paciente;
                    $datosExcepcionesAdicionales['fecha_registro']=date("Y-m-d");
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

        $valor = $this->LiquidarCargo($DatosCuenta['plan_id'],$tarifario,$cargo,$cantidad,$precio,$datosExcepcionesAdicionales);

        if(!is_array($valor))    {
            echo "<br>LiquidarCargoCuenta Salida 3 <br> ";
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

        if ($dbconn->ErrorNo() != 0) {
            die($dbconn->ErrorMsg());
            return false;
        }

        list($cargo_paragrafado)=$resultado->FetchRow();

        $resultado->Close();


        //retorno 0 si el cargo es un paragrafado, retorno 1 si el campo se debe facturar (No paragrafado)
        if($cargo_paragrafado){
            $valor['facturado']=0;
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
            if($valor['sw_copagos']){
                $servicio_copago = $DatosCuenta['servicio'];
            }else{
                $servicio_copago = '0';
            }
            //traer el copago y la cuota moderadora del cargo
            $query = "(
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

            if ($dbconn->ErrorNo() != 0) {
                die($dbconn->ErrorMsg());
                return false;
            }

            if(!$resultado->EOF){
                list($sw_copago,$sw_cuota_moderadora)=$resultado->FetchRow();
            }

            $resultado->Close();

            if($sw_copago){
                $valor['sw_cuota_paciente']=1;
            }

            if($sw_cuota_moderadora){
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
    function GetCargoplan($cargo_cups,$plan_id)
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
            $this->error = "CLASS LiquidacionQX -  - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $cargos_contratados_plan=$result->GetRows();

        $result->Close();

        return $cargos_contratados_plan;
    }

}//fin de la clase

?>


