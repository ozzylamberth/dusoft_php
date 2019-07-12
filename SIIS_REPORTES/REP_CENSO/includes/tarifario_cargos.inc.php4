<?php

/**
 * $Id: tarifario_cargos.inc.php,v 1.26 2006/01/12 16:31:00 mauricio Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

//Funcion que calcula el copago acumulado año de un paciente en un plan (excluye la cuenta actual).
function GetCopagoAcumuladoAno($plan_id,$tipo_id_paciente,$paciente_id)
{
    if(empty($plan_id) && empty($tipo_id_paciente) && empty($paciente_id)){
        return 0;
    }
    $copago_acumulado=0;
    $ano = date("Y");
    $ano_siguinte = $ano+1;

    list($dbconn) = GetDBconn();

    $query = "SELECT CASE WHEN sum(a.valor_cuota_paciente) IS NULL THEN 0 ELSE sum(a.valor_cuota_paciente) END
                FROM cuentas a, ingresos b

                WHERE
                a.plan_id = $plan_id AND
                a.ingreso = b.ingreso AND
                b.tipo_id_paciente = '$tipo_id_paciente' AND
                b.paciente_id = '$paciente_id' AND
                a.fecha_cierre = '$ano-01-01 00:00' AND
                a.fecha_cierre < '$ano_siguinte-01-01 00:00'
                ";

//SELECT CASE WHEN sum(a.valor_cuota_paciente) IS NULL THEN 0 ELSE sum(a.valor_cuota_paciente) END FROM cuentas a, ingresos b WHERE a.plan_id = 56 AND a.ingreso = b.ingreso AND b.tipo_id_paciente = 'CC' AND b.paciente_id = '16352206' AND (date_trunc('year',a.fecha_cierre)= date_trunc('year',now()))
//SELECT CASE WHEN fecha_cierre ISNULL THEN now() ELSE fecha_cierre END FROM cuentas

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return 0;
    }

    if($resultado->EOF){
        $resultado->Close();
        return 0;
    }else{
        list($copago_acumulado)=$resultado->FetchRow();
    }
    $resultado->Close();

    return $copago_acumulado;
}


function GetSemanasCotizadasCargo($plan_id,$tarifario_id,$cargo)
{
    if(empty($plan_id) || empty($tarifario_id) || empty($cargo)){
        return $copago_acumulado0;
    }

    list($dbconn) = GetDBconn();
    $query = "(SELECT A.semanas_cotizadas
                FROM planes_semanas_cotizadas AS A, tarifarios_detalle AS B
                WHERE A.plan_id = $plan_id AND
                B.tarifario_id = '$tarifario_id' AND
                B.cargo = '$cargo' AND
                B.grupo_tarifario_id = A.grupo_tarifario_id AND
                B.subgrupo_tarifario_id = A.subgrupo_tarifario_id AND
                excepciones_semanas($plan_id,'$tarifario_id','$cargo')=0)
                ";
    $resultado = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        return 0;
    }
    if($resultado->EOF){
        $resultado->Close();
        return 0;
    }else{
        list($semanas)=$resultado->FetchRow();
    }
    $resultado->Close();

    return $semanas;
}


function LiquidarCargosCuentaVirtual($cargos=array(),$inasistencias=array(),$otrosCargos=array(),$modificacionCopagosYCuotasM=array(), $planId, $tipo_afiliado_id='', $rango, $semanas_cotizacion=0, $Servicio, $tipo_id_paciente = '', $paciente_id = '', $tipo_empleador_id = '', $empleador = '')
{

//echo "<br><br>cargos  :<br><br>";
 //print_r($cargos);
 //echo "<br><br>inasistencias  :<br><br>";
 //print_r($inasistencias);
 //echo "<br><br>otroscargos  :<br><br>";
 //print_r($otrosCargos);
 //echo "<br><br>decuentos  :<br><br>";
 //print_r($descuentos);
 //echo "<br><br>planId = $planId, tipo_afiliado_id= $tipo_afiliado_id, rango= $rango, semanas_cotizacio= $semanas_cotizacion, Servicio= $Servicio, tipo_id_paciente= $tipo_id_paciente , paciente_id= $paciente_id, empleador= $empleador <br><br>";

    list($dbconn) = GetDBconn();
//if(empty($tipo_afiliado_id)){echo "esta vacio";}
        //echo "plan=>$planId  afi=>$tipo_afiliado_id  ran=>$rango";
    if(empty($cargos) || !is_array($cargos) || empty($planId) || $tipo_afiliado_id==='' || $rango==='')
        {
                //echo "faltan datos";
                return false;
    }

    $cargos_liquidados = array();
    $sw_copagos=0;
    $sw_cm=0;
    foreach($cargos as $item => $cargoliquidar)
    {
        $cargo_liquidado = LiquidarCargoCuenta($cuenta=0 ,$cargoliquidar['tarifario_id'] ,$cargoliquidar['cargo'] ,$cargoliquidar['cantidad'] ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true ,$precio=0, $planId, $Servicio, $semanas_cotizacion, $tipo_empleador_id, $empleador, $tipo_id_paciente, $paciente_id);

        if(!empty($cargo_liquidado)){
            if($cargo_liquidado['facturado'] && $cargo_liquidado['valor_cubierto']>0){
                $sw_copagos += $cargo_liquidado['sw_cuota_paciente'];
                $sw_cm += $cargo_liquidado['sw_cuota_moderadora'];
            }

            $cargo_liquidado['autorizacion_int']=$cargoliquidar['autorizacion_int'];
            $cargo_liquidado['autorizacion_ext']=$cargoliquidar['autorizacion_ext'];
            $cargos_liquidados[] = $cargo_liquidado;
        }
    }
    if(!empty($otrosCargos['IYM']))
    {
        foreach($otrosCargos['IYM'] as $k => $IM)
        {
            $cargoIyM['precio_plan']= $IM['precio_plan'];
            $cargoIyM['valor_descuento_empresa']= $IM['valor_descuento_empresa'];
            $cargoIyM['valor_descuento_paciente']= $IM['valor_descuento_paciente'];
            $cargoIyM['porcentaje_gravamen']= $IM['porcentaje_gravamen'];
            $cargoIyM['valor_no_cubierto']= $IM['valor_no_cubierto'];
            $cargoIyM['valor_cubierto']= $IM['valor_cubierto'];
            $cargoIyM['valor_cargo']= $IM['valor_cargo'];
            $cargoIyM['cantidad']= $IM['cantidad'];
            $cargoIyM['descripcion']= $IM['descripcion'];

            $cargoIyM['tarifario_id'] = 'SYS';
            $cargoIyM['cargo'] = 'IMD';

            $cargoIyM['sw_cuota_paciente']= $IM['sw_cuota_paciente'];
            $cargoIyM['sw_cuota_moderadora']= $IM['sw_cuota_moderadora'];
            $cargoIyM['facturado'] = $IM['facturado'];

            $cargoIyM['autorizacion_int']=1;
            $cargoIyM['autorizacion_ext']=1;

            $cargos_liquidados[] = $cargoIyM;
        }
    }

    foreach($inasistencias as  $k => $v){

        $query = "SELECT valor,tarifario_id,cargo FROM planes_incumplimientos_citas WHERE plan_id = $v[plan_id] AND cargo_cita = '$v[cargo_cita]';";
        $resultado = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            die("inasistencias  ".$dbconn->ErrorMsg());
        }

        if(!$resultado->EOF){
            list($valor,$tarifario_id,$cargo)=$resultado->FetchRow();

            $query = "SELECT descripcion FROM tarifarios_detalle WHERE tarifario_id='$tarifario_id' AND cargo='$cargo';";
            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                die("inasistencias  ".$dbconn->ErrorMsg());
            }

            if(!$result->EOF){

                list($descripcion)=$result->FetchRow();

                $cargo_liquidado['precio_plan']=round($valor,GetDigitosRedondeo());
                $cargo_liquidado['valor_descuento_empresa']=0;
                $cargo_liquidado['valor_descuento_paciente']=0;
                $cargo_liquidado['porcentaje_gravamen']=0;
                $cargo_liquidado['valor_no_cubierto']=round($valor,GetDigitosRedondeo());
                $cargo_liquidado['valor_cubierto']=0;
                $cargo_liquidado['valor_cargo']=round($valor,GetDigitosRedondeo());
                $cargo_liquidado['cantidad']=1;
                $cargo_liquidado['descripcion']=$descripcion;
                $cargo_liquidado['tarifario_id']=$tarifario_id;
                $cargo_liquidado['cargo']=$cargo;
                $cargo_liquidado['sw_cuota_paciente']=0;
                $cargo_liquidado['sw_cuota_moderadora']=0;
                $cargo_liquidado['facturado']=1;
                $cargo_liquidado['autorizacion_int']=1;
                $cargo_liquidado['autorizacion_ext']=1;

                $cargos_liquidados[] = $cargo_liquidado;
            }
            $result->Close();
        }
        $resultado->Close();
    }


    $varRetornoCuentaVirtual = LiquidarTotalesCuentaVirtual($cargos_liquidados, $modificacionCopagosYCuotasM, $sw_copagos, $sw_cm, $planId, $tipo_afiliado_id, $rango, $semanas_cotizacion=0, $Servicio, $tipo_id_paciente, $paciente_id,$tipo_empleador_id,$empleador);

    return $varRetornoCuentaVirtual;
}



function LiquidarTotalesCuentaVirtual($cargos_liquidados, $modificacionCopagosYCuotasM, $sw_copagos, $sw_cuota_moderadora, $planId, $tipo_afiliado_id, $rango, $semanas_cotizacion=0, $Servicio, $tipo_id_paciente, $paciente_id,$tipo_empleador_id = '', $empleador = '')
{
    list($dbconn) = GetDBconn();
    global $ADODB_FETCH_MODE;

    $varRetorno['cargos'] = $cargos_liquidados;
    $varRetorno['valor_cuota_moderadora']= 0;
    $varRetorno['valor_cuota_paciente']= 0;
    $varRetorno['valor_descuento_empresa']= 0;
    $varRetorno['valor_descuento_paciente']= 0;
    $varRetorno['valor_cubierto']= 0;
    $varRetorno['valor_no_cubierto']= 0;
    $varRetorno['valor_total_paciente']= 0;
    $varRetorno['valor_total_empresa']= 0;
    $varRetorno['valor_gravamen_paciente']= 0;
    $varRetorno['valor_gravamen_empresa']= 0;
    $varRetorno['valor_total_cuenta']= 0;

//     if(!$sw_copagos && !$sw_cuota_moderadora){
//         return $varRetorno;
//     }

    $query = "SELECT tipo_liquidacion_cargo FROM planes WHERE plan_id=$planId;";
    $result = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }

    if($result->EOF){
        $varRetorno['tipo_liquidacion_cargo'] = 'default';
    }else{
        list($varRetorno['tipo_liquidacion_cargo'])=$result->FetchRow();
    }

    $result->Close();

        $DatosCopago='';
    //busca si es un empleador especial dar
        //echo "<br><br> tipo<$tipo_empleador_id em=>$empleador";
        if(!empty($tipo_empleador_id) AND !empty($empleador))
        {
                    $query = "SELECT copago,cuota_moderadora,copago_minimo,copago_maximo,copago_maximo_ano
                                        FROM planes_rangos_empresas_especiales
                                        WHERE empleador_id='$empleador'
                                        AND tipo_empleador_id='$tipo_empleador_id'
                                        AND plan_id = $planId AND rango = '$rango'
                                        AND tipo_afiliado_id='$tipo_afiliado_id'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if ($dbconn->ErrorNo() != 0) {
                            die($dbconn->ErrorMsg());
                            return false;
                    }

                    if(!$resultado->EOF)
                    {        //es un empleador especial
                            $DatosCopago=$resultado->FetchRow();
                    }
        }
        //fin empelador especial dar

        //si no es espacial o no llego el empelador
    if(!is_array($DatosCopago))
        {
                $query = "SELECT copago,cuota_moderadora,copago_minimo,copago_maximo,copago_maximo_ano
                                        FROM planes_rangos
                                        WHERE
                                        plan_id = $planId AND
                                        rango = '$rango' AND
                                        tipo_afiliado_id='$tipo_afiliado_id'";
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $resultado = $dbconn->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                if ($dbconn->ErrorNo() != 0) {
                        die($dbconn->ErrorMsg());
                        return false;
                }

                if($resultado->EOF){
                        return $varRetorno;
                }else{
                        $DatosCopago=$resultado->FetchRow();
                }
        }
    $resultado->Close();
    switch($varRetorno['tipo_liquidacion_cargo'])
    {
        case 1:
            foreach($cargos_liquidados as $k=>$v)
            {
                    if($v['facturado']){
                        $varRetorno['valor_descuento_empresa'] += $v['valor_descuento_empresa'];
                        $varRetorno['valor_descuento_paciente'] += $v['valor_descuento_paciente'];
                        $varRetorno['valor_cubierto'] += $v['valor_cubierto'];
                        $varRetorno['valor_no_cubierto'] += $v['valor_no_cubierto'];
                    }
            }

            if($sw_copagos)
            {
                $totalCopago=0;
                foreach($cargos_liquidados as $k=>$v)
                {
                    if($v['facturado'] && ($v['sw_cuota_moderadora'] || $v['sw_cuota_paciente'])){
                        $totalCopago +=  (($v['valor_cubierto'] + $v['valor_cubierto'] * $v['porcentaje_gravamen'] /100) * $DatosCopago['copago'] / 100);
                    }

                }

                if(($DatosCopago['copago_minimo'] > 0) && ($DatosCopago['copago_minimo'] > $totalCopago)){
                    $totalCopago = $DatosCopago['copago_minimo'];
                }

                if(($DatosCopago['copago_maximo'] > 0) && ($DatosCopago['copago_maximo'] < $totalCopago)){
                    $totalCopago = $v['copago_maximo'];
                }

//                 if(($DatosCopago['copago_maximo_ano'] > 0) && !empty($tipo_id_paciente) && !empty($paciente_id)){
//                     $CopagoAcumuladoAno = GetCopagoAcumuladoAno($planId,$tipo_id_paciente,$paciente_id);
//                     if($CopagoAcumuladoAno >= $DatosCopago['copago_maximo_ano']){
//                         $totalCopago=0;
//                     }elseif(($CopagoAcumuladoAno + $totalCopago) > $DatosCopago['copago_maximo_ano']){
//                         $totalCopago = $DatosCopago['copago_maximo_ano'] - $CopagoAcumuladoAno;
//                     }
//
//                 }

                $varRetorno['valor_cuota_paciente']= round($totalCopago,GetDigitosRedondeo());

            }
            elseif($sw_cuota_moderadora)
            {
                $varRetorno['valor_cuota_moderadora']= round($DatosCopago['cuota_moderadora'],GetDigitosRedondeo());
            }
                break;

        case 2:
            foreach($cargos_liquidados as $k=>$v)
            {
                    if($v['facturado']){
                        $varRetorno['valor_descuento_empresa'] += $v['valor_descuento_empresa'];
                        $varRetorno['valor_descuento_paciente'] += $v['valor_descuento_paciente'];
                        $varRetorno['valor_cubierto'] += $v['valor_cubierto'];
                        $varRetorno['valor_no_cubierto'] += $v['valor_no_cubierto'];
                    }
            }

            if($sw_copagos){

                $totalCopago=0;
                foreach($cargos_liquidados as $k=>$v)
                {
                    if($v['facturado'] && ( $v['sw_cuota_paciente'])){
                        $totalCopago +=  (($v['valor_cubierto'] + $v['valor_cubierto'] * $v['porcentaje_gravamen'] /100) * $DatosCopago['copago'] / 100);
                    }

                }

                if(($DatosCopago['copago_minimo'] > 0) && ($DatosCopago['copago_minimo'] > $totalCopago)){
                    $totalCopago = $DatosCopago['copago_minimo'];
                }

                if(($DatosCopago['copago_maximo'] > 0) && ($DatosCopago['copago_maximo'] < $totalCopago)){
                    $totalCopago = $v['copago_maximo'];
                }

//                 if(($DatosCopago['copago_maximo_ano'] > 0) && !empty($tipo_id_paciente) && !empty($paciente_id)){
//                     $CopagoAcumuladoAno = GetCopagoAcumuladoAno($planId,$tipo_id_paciente,$paciente_id);
//                     if($CopagoAcumuladoAno >= $DatosCopago['copago_maximo_ano']){
//                         $totalCopago=0;
//                     }elseif(($CopagoAcumuladoAno + $totalCopago) > $DatosCopago['copago_maximo_ano']){
//                         $totalCopago = $DatosCopago['copago_maximo_ano'] - $CopagoAcumuladoAno;
//                     }
//
//                 }

                $varRetorno['valor_cuota_paciente']= round($totalCopago,GetDigitosRedondeo());
            }

            if($sw_cuota_moderadora){
                $varRetorno['valor_cuota_moderadora']= round($DatosCopago['cuota_moderadora'],GetDigitosRedondeo());
            }

           break;

        default: //caso 3

            foreach($cargos_liquidados as $k=>$v)
            {
                if($v['facturado']){
                    $varRetorno['valor_descuento_empresa'] += $v['valor_descuento_empresa'];
                    $varRetorno['valor_descuento_paciente'] += $v['valor_descuento_paciente'];
                    $varRetorno['valor_cubierto'] += $v['valor_cubierto'];
                    $varRetorno['valor_no_cubierto'] += $v['valor_no_cubierto'];
                }
            }
            $varRetorno['valor_cuota_paciente']= round(0,GetDigitosRedondeo());
            $varRetorno['valor_cuota_moderadora']= round(0,GetDigitosRedondeo());
        break;
    }


    if(is_array($modificacionCopagosYCuotasM['cuota_paciente']))
    {
        if(($modificacionCopagosYCuotasM['cuota_paciente']['valormodi'] >= 0))
        {
                        $varRetorno['descuentos']['cuota_paciente']=$modificacionCopagosYCuotasM['cuota_paciente'];
                $varRetorno['valor_cuota_paciente'] = $modificacionCopagosYCuotasM['cuota_paciente']['valormodi'];
                }
    }

    if(is_array($modificacionCopagosYCuotasM['cuota_moderadora']))
    {
        if(($modificacionCopagosYCuotasM['cuota_moderadora']['valormodi'] >= 0))
        {
                        $varRetorno['descuentos']['cuota_moderadora']=$modificacionCopagosYCuotasM['cuota_moderadora'];
                $varRetorno['valor_cuota_moderadora'] = $modificacionCopagosYCuotasM['cuota_moderadora']['valormodi'];
        }
    }

        $varRetorno['valor_total_paciente'] = round($varRetorno['valor_cuota_moderadora'] + $varRetorno['valor_cuota_paciente'] + $varRetorno['valor_no_cubierto'] + $varRetorno['valor_gravamen_paciente'] ,GetDigitosRedondeo());

    if ($varRetorno['valor_total_paciente'] < 0)
    {
        $varRetorno['valor_total_paciente'] = round(0,GetDigitosRedondeo());
    }

    $varRetorno['valor_total_empresa'] = round($varRetorno['valor_cubierto'] + $varRetorno['valor_gravamen_empresa'] - $varRetorno['valor_cuota_moderadora'] - $varRetorno['valor_cuota_paciente'] ,GetDigitosRedondeo());

        if ($varRetorno['valor_total_empresa'] < 0)
    {
        $varRetorno['valor_total_empresa'] = round(0,GetDigitosRedondeo());
    }

    $varRetorno['valor_total_cuenta'] = round($varRetorno['valor_total_paciente'] + $varRetorno['valor_total_empresa'],GetDigitosRedondeo());

    return $varRetorno;
}


/**
* Funcion para liquidar el valor de un cargo en una cuenta (tiene en cuenta el tipo de afiliado y el rango para
* el manejo de copagos y cuotas moderadoras.
*
* @return array
* @param integer Numero de la cuenta
* @param string tarifario_id y cargo
* @param string tarifario_id y cargo
* @param integer cantidad del cargo para liquidar
*/

function LiquidarCargoCuenta($cuenta=0 ,$tarifario ,$cargo ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true, $precio=0, $planId='', $Servicio='', $semanas_cotizacion='', $tipo_empleador_id = '', $empleador = '',$tipo_id_paciente = '', $paciente_id = '')
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

    $valor = LiquidarCargo($DatosCuenta['plan_id'],$tarifario,$cargo,$cantidad,$precio=0,$datosExcepcionesAdicionales);

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



//Funcion para liquidar un cargo
function LiquidarCargo($plan,$tarifario,$cargo,$cantidad=1,$precio=0,$excepcionesAdicionales=array())
{
    list($dbconn) = GetDBconn();
		if(empty($plan) || empty($tarifario) || empty($cargo)){
        echo "<br> LiquidarCargo Salida 1 <br> ";
        return false;
    }

    $result = PlanTarifario($plan,$tarifario,$cargo,'','','','','','*',true,'','');

    if($result->EOF){
        echo "<br> LiquidarCargo Salida 2 <br> ";
        return false;
    }

    $cargo_info = $result->FetchRow();

    $result->Close();

    if(!empty($excepcionesAdicionales))
    {
        
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
                    WHERE plan_id=$plan
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
                                        SELECT porcentaje,por_cobertura,precio,sw_copago,sw_cuota_moderadora  FROM  planes_excepciones_adicionales
                                        WHERE plan_id=$plan
                                        AND tarifario_id='".$tarifario."'
                                        AND cargo='".$cargo."'
                                        AND (edad_min <= ".$edad." OR edad_min ISNULL)
                                        AND (edad_max >= ".$edad." OR edad_max ISNULL)
                                        AND (numero_veces <= ".$numero_veces." OR numero_veces ISNULL)
                                        AND sw_excluir_rango_edad='0'
                                    )
                                    UNION
                                    (
                                        SELECT porcentaje,por_cobertura,precio,sw_copago,sw_cuota_moderadora  FROM  planes_excepciones_adicionales
                                        WHERE plan_id=$plan
                                        AND tarifario_id='".$tarifario."'
                                        AND cargo='".$cargo."'
                                        AND NOT((edad_min <= ".$edad." OR edad_min ISNULL)
                                        AND (edad_max >= ".$edad." OR edad_max ISNULL))
                                        AND (numero_veces <= ".$numero_veces." OR numero_veces ISNULL)
                                        AND sw_excluir_rango_edad='1'
                                    )
                                        ";

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
        switch($cargo_info['tipo_unidad_id'])
        {
            case '02' :
                //UNIDAD UVRS
                echo $cargo_info['precio'].'UVRS';
            break;
            case '03' :
                //UNIDAD EN SMMLV
                $salario=GetSalarioMinimo(date('Y'));
                $cargo_info['precio']=$cargo_info['precio']*$salario;
            break;
            case '04' :
                //UNIDAD EN GRUPOS QUIRURJICOS
                echo $cargo_info['precio'].'GQ';
            break;
            case '05' :
                //UNIDAD UVRS
                $uvr=UVRPaquetePlan($plan,$tarifario);
                $cargo_info['precio']=$cargo_info['precio']*$uvr;
            break;
            default;
                //EN PESOS
                $cargo_info['precio']=$cargo_info['precio'];
            break;
        }
    if(empty($precio))
        {
            $valor['precio_plan'] = round(($cargo_info['precio'] + ($cargo_info['precio'] * $cargo_info['porcentaje'] / 100)),GetDigitosRedondeo());
        }
        else
        {
            $valor['precio_plan'] = round($precio,GetDigitosRedondeo());
    }

 //---PRUEBA PLANES PARTICULARES
    $query = "SELECT sw_tipo_plan FROM planes
                WHERE plan_id=$plan";

    $resultado = $dbconn->Execute($query);
		
    if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
        return false;
    }
    if(!$resultado->EOF){
        list($sw_tipo_plan)=$resultado->FetchRow();
    }
    $resultado->Close();

    if($sw_tipo_plan=='2') $cargo_info['por_cobertura']=0;

 //---FIN CONSULTA PRUEBA PLANES PARTICULARES

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


// funcion para obtener los precios de uno o varios cargos en un plan.
function PlanTarifario($plan_id, $tarifario_id='', $cargo='', $grupo_tarifario_id='', $subgrupo_tarifario_id='', $grupo_tipo_cargo='', $tipo_cargo='', $filtro_adicional='', $campos_select='*', $fetch_mode_assoc=false, $Of='',$limit='')
{

    if($Of==='' ||$Of===' ')
    {
        $Of='';
        $limite='';
    }
    else
    {
    $limite="LIMIT $limit OFFSET $Of";
    }

    if(empty($plan_id)){
        return false;
    }

    $filtros = array();
    $filtro ='';

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

    foreach($filtros as $k=>$v){
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
                        SELECT b.plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id, a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad, e.sw_copagos,
                                                            a.tipo_unidad_id
                        FROM tarifarios_detalle a, plan_tarifario b, subgrupos_tarifarios e$filtro_tipo_solicitud_tablas
                        WHERE

                        b.plan_id = $plan_id and
                        b.grupo_tarifario_id = a.grupo_tarifario_id AND
                        b.subgrupo_tarifario_id    = a.subgrupo_tarifario_id AND
                        b.tarifario_id = a.tarifario_id AND
                        a.grupo_tarifario_id<>'00' AND
                        a.grupo_tipo_cargo<>'SYS' AND
                        e.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                        e.grupo_tarifario_id = a.grupo_tarifario_id AND
                        excepciones(b.plan_id,b.tarifario_id, a.cargo) = 0
                    )
                       UNION
                    (
                        SELECT b.plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id, a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad, e.sw_copagos,
                                                        a.tipo_unidad_id
                        FROM tarifarios_detalle a, excepciones b, subgrupos_tarifarios e
                        WHERE

                        b.plan_id = $plan_id AND
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
        die($dbconn->ErrorMsg());
        return false;
    }

    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

    $salida = &$result;

    return $salida;

}


function GetPlanTarifario($plan_id, $tarifario_id='', $grupo_tarifario_id='', $subgrupo_tarifario_id='', $grupo_tipo_cargo='', $tipo_cargo='', $cargo='',$tipo_solicitud='',$counta=false, $fetch_mode_assoc=false, $buscador='',$select_campo='*',$Of='',$limit='')
{

  if($Of==='' || $Of===' ')
    {
        $Of='';
        $limite='';
    }
    else
    {
        $limite="LIMIT $limit OFFSET $Of";
    }

    GLOBAL $ADODB_FETCH_MODE;

  if(empty($plan_id)){
    return false;
  }

  $filtros = "";

  if(!empty($tarifario_id)){
    $filtros .= " AND a.tarifario_id = '$tarifario_id'";
  }

  if(!empty($grupo_tarifario_id)){
    $filtros .= " AND a.grupo_tarifario_id = '$grupo_tarifario_id'";
  }

  if(!empty($subgrupo_tarifario_id)){
    $filtros .= " AND a.subgrupo_tarifario_id = '$subgrupo_tarifario_id'";
  }

  if(!empty($grupo_tipo_cargo)){
    $filtros .= " AND a.grupo_tipo_cargo = '$grupo_tipo_cargo'";
  }

  if(!empty($tipo_cargo)){
    $filtros .= " AND a.tipo_cargo = '$tipo_cargo'";
  }

  if(!empty($cargo)){
    $filtros .= " AND a.cargo = '$cargo'";
  }

  if(!empty($tipo_solicitud)){
    $filtro_tipo_solicitud_campos = ", d.descripcion as descripcion_tipo_cargo";
    $filtro_tipo_solicitud_tablas = ", grupos_tipos_cargo c, tipos_cargos d";
    $filtro_tipo_solicitud = " AND c.tipo_solicitud_id = $tipo_solicitud
                               AND d.grupo_tipo_cargo = c.grupo_tipo_cargo
                               AND a.grupo_tipo_cargo = c.grupo_tipo_cargo
                               AND a.tipo_cargo = d.tipo_cargo";
  }else{
    $filtro_tipo_solicitud_tablas = "";
    $filtro_tipo_solicitud = "";
  }

    if(!$counta){
            $select = "a.tarifario_id,a.grupo_tarifario_id,a.subgrupo_tarifario_id,a.grupo_tipo_cargo,a.tipo_cargo,a.cargo,a.descripcion$filtro_tipo_solicitud_campos";
    }else{
            $select .= "count(*)";
    }


    $query = "SELECT $select_campo FROM (
                        SELECT $select

            FROM tarifarios_detalle a, plan_tarifario b$filtro_tipo_solicitud_tablas

            WHERE   b.plan_id = '$plan_id' AND
                    b.tarifario_id = a.tarifario_id AND
                    b.grupo_tarifario_id = a.grupo_tarifario_id AND
                    a.grupo_tarifario_id<>'00' AND
                    a.grupo_tipo_cargo<>'SYS' AND
                    b.subgrupo_tarifario_id    = a.subgrupo_tarifario_id $filtro_tipo_solicitud $filtros
                        ) AS A $buscador $limite";
//    echo "<br>Q->".$query;
  list($dbconn) = GetDBconn();
    if ($fetch_mode_assoc){
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    }
  $result = $dbconn->Execute($query);

  if ($dbconn->ErrorNo() != 0) {
    return false;
  }
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

     if(!$counta){
    $salida = &$result;
    return $salida;
  }else{
        list($valor)=$result->FetchRow();
      return $valor;
  }
}

//----------------DARLING--------------------------

//function para buscar los cargos cups para agregar a la cuenta
//function BuscardoCargosCups($plan_id, $grupo_tipo_cargo='', $filtro_adicional='', $campos_select='*', $fetch_mode_assoc=false, $Of='',$limit='')
function BuscardoCargosCups($filtro_adicional='', $campos_select='*', $Of='',$limit='',$filtro_sql='')
{

    if($Of==='' ||$Of===' ')
    {
        $Of='';
        $limite='';
    }
    else
    {
    $limite="LIMIT $limit OFFSET $Of";
    }

    /*if(empty($plan_id)){
        return false;
    }*/

    $filtros = array();
    $filtro ='';

   /* if(!empty($grupo_tipo_cargo)){
        $filtros[] = "grupo_tipo_cargo = '$grupo_tipo_cargo'";
    }*/

    if(!empty($filtro_adicional)){
        $filtros[] = "$filtro_adicional";
    }

    foreach($filtros as $k=>$v){
        if(!$where){
            $filtro = "WHERE $v";
        }else{
            $filtro .= " AND $v";
        }
        $where=true;
    }

    $query = "SELECT $campos_select
                FROM cups a $filtro_sql $filtro $limite";
    /*$query = "SELECT $campos_select
                FROM (
                    (   SELECT b.plan_id, f.grupo_tarifario_id, f.subgrupo_tarifario_id, f.grupo_tipo_cargo,
                                                f.descripcion, a.precio, f.sw_cantidad, g.cargo_base
                                                FROM tarifarios_detalle a, plan_tarifario b,
                                                subgrupos_tarifarios e, cups as f, tarifarios_equivalencias as g
                                                WHERE f.cargo=g.cargo_base and g.cargo=a.cargo
                                                and g.tarifario_id=a.tarifario_id and
                                                b.plan_id = $plan_id and
                                                b.grupo_tarifario_id = a.grupo_tarifario_id AND
                                                b.subgrupo_tarifario_id    = a.subgrupo_tarifario_id AND
                                                b.tarifario_id = a.tarifario_id AND
                                                a.grupo_tarifario_id<>'00' AND
                                                a.grupo_tipo_cargo<>'SYS' AND
                                                e.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                                                e.grupo_tarifario_id = a.grupo_tarifario_id AND
                                                excepciones(b.plan_id,b.tarifario_id, a.cargo) = 0
                    )
                    UNION
                    (
                        SELECT b.plan_id, f.grupo_tarifario_id, f.subgrupo_tarifario_id, f.grupo_tipo_cargo,
                                                f.descripcion, a.precio, f.sw_cantidad, g.cargo_base
                                                FROM tarifarios_detalle a, excepciones b, subgrupos_tarifarios e, cups as f, tarifarios_equivalencias as g
                                                WHERE f.cargo=g.cargo_base and g.cargo=a.cargo
                                                and g.tarifario_id=a.tarifario_id and
                                                b.plan_id = $plan_id AND
                        b.tarifario_id = a.tarifario_id AND
                        b.sw_no_contratado = 0 AND
                        b.cargo = a.cargo AND
                        e.grupo_tarifario_id = a.grupo_tarifario_id AND
                        e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                    )
            ) AS A $filtro $limite ";*/

    list($dbconn) = GetDBconn();

    GLOBAL $ADODB_FETCH_MODE;

    if ($fetch_mode_assoc){
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    }else{
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    }

    $result = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
        return false;
    }

    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

    $salida = &$result;

    return $salida;
}

    function UVRPaquetePlan($plan,$tarifario)
    {
        list($dbconn) = GetDBconn();
        $sql="SELECT uvr_valor
                    FROM tarifarios_uvrs_paquetes_excepciones
                    WHERE plan_id=".$plan."
                        AND tarifario_id='".$tarifario."';";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if(!$resultado->EOF)
        {
            $sql="SELECT uvr_valor
                        FROM tarifarios_uvrs_paquetes
                        WHERE tarifario_id='".$tarifario."';";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resultado = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if(!$resultado->EOF)
            {
                $uvr['uvr_valor']=$resultado['uvr_valor'];
                $resultado->Close();
                return $uvr;
            }
        }
        else
        {
            $uvr['uvr_valor']=$resultado['uvr_valor'];
            $resultado->Close();
            return $uvr;
        }

    }

    /**
    * Funcion para liquidar un producto de inventarios.
    *
    * @param string $cuenta
    * @param string $producto
    * @param string $cantidad
    * @param string $descuento_manual_empresa
    * @param string $descuento_manual_paciente
    * @param string $aplicar_descuento_empresa
    * @param string $aplicar_descuento_paciente
    * @param string $precio
    * @param string $planId
    * @param string $autorizar
    * @param string $departamento
    * @param string $empresa
    * @param string $evolucion_id
    * @return array
    * @access public
    */
    function LiquidarIyM($cuenta=NULL ,$producto='' ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=TRUE ,$aplicar_descuento_paciente=TRUE ,$precio=NULL ,$planId=NULL,$autorizar=FALSE,$departamento=NULL,$empresa=NULL,$evolucion_id=NULL)
    {

        static $objLIM;

        if(!is_object($objLIM))
        {
            if (!IncludeClass("LiquidacionCargosInventario"))
            {
                die(MsgOut("NO SE PUDO INCLUIR LA CLASE [LiquidacionCargosInventario]"));
            }
            $objLIM = new LiquidacionCargosInventario;
            if(!is_object($objLIM))
            {
                die(MsgOut("No se pudo crear el objecto[LiquidacionCargosInventario]"));
            }
        }

        $datosAdicionales['cuenta'] = $cuenta;
        $datosAdicionales['plan_id'] = $planId;
        $datosAdicionales['precio'] = $precio;
        $datosAdicionales['departamento'] = $departamento;
        $datosAdicionales['servicio'] = NULL;
        $datosAdicionales['evolucion_id'] = $evolucion_id;
        $datosAdicionales['descuento_manual_empresa'] = $descuento_manual_empresa;
        $datosAdicionales['descuento_manual_paciente'] = $descuento_manual_paciente;
        $datosAdicionales['aplicar_descuento_empresa'] = $aplicar_descuento_empresa;
        $datosAdicionales['aplicar_descuento_paciente'] = $aplicar_descuento_paciente;

        if(($retorno = $objLIM->GetLiquidacionProducto($producto, $empresa, $cantidad, $datosAdicionales))===false)
        {
            echo "No se pudo liquidar el producto [$producto] : ".$objLIM->Err() . " - " .$objLIM->ErrMsg();
        }
        return $retorno;
    }

?>
