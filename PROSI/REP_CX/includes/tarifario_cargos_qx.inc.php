<?php

/**
 * $Id: tarifario_cargos_qx.inc.php,v 1.2 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

function LiquidarCirugia($procedimientos,$cuenta,$planId='', $tipo_afiliado_id='', $rango='', $semanas_cotizacion=0, $Servicio='', $tipo_id_paciente='', $paciente_id='')
{

    if((empty($cuenta) && (empty($planId) || $Servicio==='')) ){
        echo "<br>LiquidarCirugia Salida 1 <br> ";
        return false;
    }

    if(empty($cargos) || !is_array($cargos) || empty($planId) || empty($tipo_afiliado_id) || empty($rango)){
        return false;
    }

    return true;
}

function LiquidarCargosQX($procedimientos,$cuenta)
{
    if(empty($procedimientos) || !is_array($procedimientos) || empty($cuenta)){
        echo "<br>LiquidarCargosQX Salida 1 <br> ";
        return false;
    } 
    
    return true;    
}

//$procedimientos arreglo donde cada vector es un cargo devuelto por la funcion BuscarCargoEquivalente()
function PresupuestarCargosQX($procedimientos,$planId,$tipo_afiliado_id,$rango,$semanas_cotizacion=0,$tipo_id_paciente='', $paciente_id='')
{
    if(empty($procedimientos) || !is_array($procedimientos)){
        echo "<br>PresupuestarCargosQX Salida 1 <br> ";
        return false;
    } 
    
    //acomodar los procedimientos por tipo de liquidacion.
    foreach($procedimientos as $k=>$v){
        $vectorCargos[$v['tipo_liquidacion_qx']][]=$v;
    }
    
    return true;    
}

function BuscarCargoEquivalente($cargo_base,$plan_id)
{

    if(empty($cargo_base) || empty($plan_id)){
        return false;
    }

    $Salida=array();

    list($dbconn) = GetDBconn();
    global $ADODB_FETCH_MODE;

    $query = "SELECT a.tarifario_id, a.cargo, a.descripcion
            FROM tarifarios_detalle AS a, plan_tarifario AS b,
            (SELECT tarifario_id,cargo FROM tarifarios_equivalencias
            WHERE cargo_base='$cargo_base') AS c

            WHERE b.plan_id = $plan_id
            AND b.tarifario_id = a.tarifario_id
            AND b.grupo_tarifario_id = a.grupo_tarifario_id
            AND b.subgrupo_tarifario_id = a.subgrupo_tarifario_id
            AND c.tarifario_id =  a.tarifario_id
            AND c.cargo = a.cargo";

    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $resultado = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

    if ($dbconn->ErrorNo() != 0) {
        die("SQL " . $dbconn->ErrorMsg());
        return false;
    }

    $i=0;

    while($cargo_equivalente = $resultado->FetchRow())
    {

        $sql = "SELECT COUNT(*) FROM excepciones
                WHERE plan_id = $plan_id
                AND tarifario_id = '$cargo_equivalente[tarifario_id]'
                AND cargo = '$cargo_equivalente[cargo]'
                AND sw_no_contratado <> '0'";

        $resultado_count = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            die("SQL " . $dbconn->ErrorMsg());
            return false;
        }

        list($no_contratado) = $resultado_count->FetchRow();
        $resultado_count->Close();

        if(!$no_contratado){//un equivalente
            $Salida['cargos'][$i]['tarifario']=$cargo_equivalente['tarifario_id'];
            $Salida['cargos'][$i]['cargo']=$cargo_equivalente['cargo'];
            $Salida['cargos'][$i]['descripcion']=$cargo_equivalente['descripcion'];
            $Salida['cargos'][$i]['tipo_liquidacion_qx']=GetTipoLiquidacionQx($cargo_equivalente['tarifario_id'],$cargo_equivalente['cargo'],$plan_id);
            $i++;
        }

    }

    $resultado->Close();

    if($i==0){
        $Salida['cargo']=false;
        $Salida['tarifario']=false;
        $Salida['codigo_error']='1';
        $Salida['mensaje_error']="EL CARGO  '$cargo_base'  NO TIENE EQUIVALENCIAS O NO ESTA CONTRATADO";
        return $Salida;

    }

    if($i>1){
        $Salida['cargo']=false;
        $Salida['tarifario']=false;
        $Salida['codigo_error']='2';
        $Salida['mensaje_error']="EL CARGO $cargo_base TIENE $i EQUIVALENCIAS";
        return $Salida;
    }

    $Salida['cargo']=$Salida['cargos'][0]['cargo'];
    $Salida['tarifario']=$Salida['cargos'][0]['tarifario'];
    $Salida['tipo_liquidacion_qx']=$Salida['cargos'][0]['tipo_liquidacion_qx'];

    unset ($Salida['cargos']);

    return $Salida;
}

function GetTipoLiquidacionQx($tarifario_id,$cargo,$plan_id='')
{
    if(empty($tarifario_id) || empty($cargo)){
        return false;
    }

    list($dbconn) = GetDBconn();

    if(!empty($plan_id)){
        $query="SELECT tipo_liquidacion_qx FROM qx_excepciones_plan_tipo_liquidaciones
                WHERE plan_id=$plan_id AND tarifario_id='$tarifario_id';";

        $resultado = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            die("SQL EN GetTipoLiquidacionQx()" . $dbconn->ErrorMsg());
            return false;
        }

        if(!$resultado->EOF){
            list($TipoLiquidacionQx)=$resultado->FetchRow();
            $resultado->Close();
            return $TipoLiquidacionQx;
        }

        $resultado->Close();
    }

    $query="SELECT tipo_liquidacion_qx FROM tarifarios
                WHERE tarifario_id='$tarifario_id';";

    $resultado = $dbconn->Execute($query);
    
    if ($dbconn->ErrorNo() != 0) {
        die("SQL EN GetTipoLiquidacionQx()" . $dbconn->ErrorMsg());
        return false;
    }  
                    
    if($resultado->EOF){
        return false;
    }
    
    list($TipoLiquidacionQx)=$resultado->FetchRow();
    $resultado->Close();
    
    return $TipoLiquidacionQx;        
}








?>
