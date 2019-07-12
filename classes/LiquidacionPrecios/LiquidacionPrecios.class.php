<?php


/**
* Clase para la liquidacion de Precios Cargos-Apoyo Diagnostico
*
* @author Mauricio Bejarano l.
* @version $Revision: 1.1 $
* @package SIIS
*/
class LiquidacionPrecios
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
    function LiquidacionPrecios()
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
    * Metodo para obtener los precios de uno o varios cargos en un plan.
    *
    * @param array $dato opcional dato que se quiere recuperar
    * @return array
    * @access public
    */
		    function PlanTarifario($plan_id=null, $tarifario_id='', $cargo='', $grupo_tarifario_id='', $subgrupo_tarifario_id='', $grupo_tipo_cargo='', $tipo_cargo='', $filtro_adicional='', $campos_select='*', $fetch_mode_assoc=false, $Of='',$limit='', $recordSet=true)
    {




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

				$_SESSION['LIQ_PRECIOS']['QUERY']=$query;
				$_SESSION['LIQ_PRECIOS']['RESULT']=$result;
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

		}
}//fin de la clase

?>


