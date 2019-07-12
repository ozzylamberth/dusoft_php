<?php

/**
* $Id: 0001.class.php,v 1.1 2005/09/21 18:05:07 alex Exp $
*/

/**
* Clase para la liquidacion de Habitaciones
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1 $
* @package SIIS
*/
class TipoLiquidacionHabitacion_0001
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
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function TipoLiquidacionHabitacion_0001()
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


    function GetCargosLiqHabitaciones($tipo_clase_cama_id,$datos,$datos_plan,$hospitalizacion=0)
    {
        if($tipo_clase_cama_id!='3')
        {
            $this->error = "CLASS TipoLiquidacionHabitacion_0001 - GetCargosLiqHabitaciones - ERROR 01";
            $this->mensajeDeError = 'Esta clase solo soporta la liquidacion de cargos de observacion de urgencias.';
            return false;
        }

        $numero_horas = ($datos['FINAL']['MKTIME'] - $datos['INICIO']['MKTIME'])/3600;

        if($numero_horas<2) return true;
        if($numero_horas<24)
        {
            if($hospitalizacion>0) return true;
            if(!$cargos[] = $this->GetCargoPlan($datos,$datos_plan))
            {
                if($this->error)
                {
                    return false;
                }
                else
                {
                    $this->error = "CLASS TipoLiquidacionHabitacion_0001 - GetCargosLiqHabitaciones - ERROR 02";
                    $this->mensajeDeError = 'No se pudo obtener el cargo del tipo de camas.';
                    return false;
                }
            }

            return $cargos;
        }
        else
        {
            $numero_dias=($numero_horas/24);
            $horas_restantes=$numero_horas - ($numero_dias*24);
        }
    }

    function &GetCargoPlan($datos, $datos_plan, $cantidad=1)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        foreach($datos['MOVIMIENTO'] as $mv_id=>$v)
        {
            $tipos_camas[]=$v['tipo_cama_id'];
        }

        if(sizeof($tipos_camas)==1)
        {
             $filtro = "=$tipos_camas[0]";
        }
        elseif($tipos_camas>1)
        {
            $filtro = " IN (";
            for($i=0;$i < sizeof($tipos_camas);$i++)
            {
                $filtro .= $tipos_camas[$i];
                if($i < (sizeof($tipos_camas)-1))
                {
                    $filtro .= ",";
                }
            }
            $filtro .= ")";
        }
        else
        {
            $this->error = "CLASS TipoLiquidacionHabitacion_0001 - GetCargoPlan - ERROR 01";
            $this->mensajeDeError = 'El vector de tipos de camas para liquidar esta nulo';
            return false;
        }

        $sql = "SELECT *, CASE WHEN a.valor_lista > 0 THEN ROUND(a.valor_lista,get_digitos_redondeo()) ELSE ROUND((b.precio + (b.precio * a.porcentaje /100)),get_digitos_redondeo()) END as valor_unidad
                FROM planes_tipos_camas a, tarifarios_detalle b
                WHERE tipo_cama_id $filtro
                AND a.empresa_id = '".$datos_plan['empresa_id']."'
                AND a.plan_id = ".$datos_plan['plan_id']."
                AND b.tarifario_id = a.tarifario_id
                AND b.cargo = a.cargo
                ORDER BY valor_unidad,valor_excedente DESC;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionHabitacion_0001- GetCargoPlan - ERROR 02";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        //SI NO HAY CARGOS DE HABITACIONES
        if($result->EOF)
        {
            $this->error = "CLASS TipoLiquidacionHabitacion_0001 - GetCargoPlan - ERROR 03";
            $this->mensajeDeError = "No hay cargos parametrizados para el plan $datos_plan[plan_id] para los tipos camas $filtro";
            return false;
        }

        $cargo= $result->FetchRow();
        $result->Close();

        $cargo['cantidad']=$cantidad;

        return $cargo;
    }
}
?>