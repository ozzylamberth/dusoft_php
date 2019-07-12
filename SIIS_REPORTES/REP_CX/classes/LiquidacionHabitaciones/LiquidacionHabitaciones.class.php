<?php

/**
* $Id: LiquidacionHabitaciones.class.php,v 1.12 2007/05/29 13:54:34 alexgiraldo Exp $
*/

/**
* Clase para la liquidacion de Habitaciones
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.12 $
* @package SIIS
*/
class LiquidacionHabitaciones
{

   /**
    * Numero de Ingreso del paciente
    *
    * @var integer
    * @access private
    */
    var $ingreso=0;

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
    * Almacena los cargos de habitacion de un ingreso
    *
    * @var string
    * @access private
    */
    var $CargosRetorno=array();


   /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function LiquidacionHabitaciones()
    {
        $this->CargosRetorno=array();
        $this->error='';
        $this->mensajeDeError='';
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
    }//end of method


    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }//end of method


    /**
    * Retorna un vector con los totales de los cargos liquidados de un ingreso
    *
    * @return array
    * @access public
    */
    function GetTotalCargosHabitacion()
    {
        $valor_no_cubierto= 0;
        $valor_cubierto = 0;
        $valor_cargo = 0;

        foreach($this->CargosRetorno as $k=>$v)
        {
            $valores['valor_no_cubierto'] += $v['valor_no_cubierto'];
            $valores['valor_cubierto'] += $v['valor_cubierto'];
            $valores['valor_cargo'] += $v['valor_cargo'];
        }

        return $valores;
    }//end of method


   /**
    * Liquidar Cargos de Habitaciones de un Ingreso
    *
    * @param integer $ingreso Numero de Ingreso de paciente al que se le va liquidar
    * @return boolean
    * @access public
    */
    function LiquidarCargosInternacion($cuenta,$reliquidar=false,$fecha_inicial=null,$fecha_final=null)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT sw_liquidacion_manual_habitaciones FROM cuentas WHERE numerodecuenta=$cuenta;";
        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 01-A";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            return true;
        }

        list($liq_manual) = $result->FetchRow();
        $result->Close();


        if($liq_manual)
        {
            $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 01-B";
            $this->mensajeDeError = "Los cargos de internacion de esta cuenta se estan liquidando manualmente.";
            return false;
        }

        if($fecha_inicial && $fecha_final)
        {
            if(!$reliquidar)
            {
                $sql = "SELECT a.*,b.tipo_clase_cama_id,
                            CASE WHEN a.fecha_ingreso::date >= '$fecha_inicial' THEN a.fecha_ingreso ELSE '$fecha_inicial 00:00:00'::timestamp without time zone END AS fecha_ingreso,
                            CASE WHEN a.fecha_egreso::date <= '$fecha_final' THEN a.fecha_egreso ELSE '$fecha_final 23:59:59'::timestamp without time zone END AS fecha_egreso
                        FROM  movimientos_habitacion a, tipos_camas b
                        WHERE a.ingreso=(SELECT ingreso FROM cuentas WHERE numerodecuenta=$cuenta)
                            AND b.tipo_cama_id = a.tipo_cama_id
                            AND ((a.fecha_egreso::date >= '$fecha_inicial' OR a.fecha_egreso ISNULL) AND (a.fecha_ingreso::date <= '$fecha_final'))
                        ORDER BY a.fecha_ingreso, b.tipo_clase_cama_id, a.tipo_cama_id;";
            }
            else
            {
                $sql = "SELECT a.*,b.tipo_clase_cama_id,
                            CASE WHEN a.fecha_ingreso::date >= '$fecha_inicial' THEN a.fecha_ingreso ELSE '$fecha_inicial 00:00:00'::timestamp without time zone END AS fecha_ingreso,
                            CASE WHEN a.fecha_egreso::date <= '$fecha_final' THEN a.fecha_egreso ELSE '$fecha_final 23:59:59'::timestamp without time zone END AS fecha_egreso
                        FROM  movimientos_habitacion a, tipos_camas b
                        WHERE a.ingreso=(SELECT ingreso FROM cuentas WHERE numerodecuenta=$cuenta)
                            AND a.transaccion ISNULL
                            AND b.tipo_cama_id = a.tipo_cama_id
                            AND ((a.fecha_egreso::date >= '$fecha_inicial' OR a.fecha_egreso ISNULL) AND (a.fecha_ingreso::date <= '$fecha_final'))
                        ORDER BY a.fecha_ingreso, a.fecha_egreso;";
            }
        }
        else
        {
            if(!$reliquidar)
            {
                $sql = "SELECT a.*, b.tipo_clase_cama_id
                        FROM  movimientos_habitacion a, tipos_camas b
                        WHERE a.ingreso=(SELECT ingreso FROM cuentas WHERE numerodecuenta=$cuenta)
                        AND b.tipo_cama_id = a.tipo_cama_id
                        ORDER BY a.fecha_ingreso, b.tipo_clase_cama_id, a.tipo_cama_id;";

            }
            else
            {
                $sql = "SELECT a.*, b.tipo_clase_cama_id
                        FROM  movimientos_habitacion a, tipos_camas b
                        WHERE a.ingreso=(SELECT ingreso FROM cuentas WHERE numerodecuenta=$cuenta)
                        AND a.transaccion ISNULL
                        AND b.tipo_cama_id = a.tipo_cama_id
                        ORDER BY a.fecha_ingreso, a.fecha_egreso;";
            }
        }

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        //SI NO HAY CARGOS DE HABITACIONES
        if($result->EOF)
        {
            return true;
        }
        while($movimiento =$result->FetchRow())
        {
            $tipos_clases[$movimiento['tipo_clase_cama_id']]['NumeroDeMovimientos'] += 1;
            $Fecha=$this->getDate($movimiento['fecha_ingreso']);
            $datos_mov[$Fecha][$movimiento['movimiento_id']][$movimiento['tipo_clase_cama_id']][$movimiento['tipo_cama_id']]=$movimiento;
            $dptos_mvto[$movimiento['tipo_cama_id']]=$movimiento['departamento'];
        }
        $numTotalMov=$result->RecordCount();
        $result->Close();

        $sql = "SELECT b.tipo_clase_cama_id, b.tipo_liq_habitacion, a.empresa_id, a.plan_id
                FROM cuentas a, planes_tipos_liq_habitacion b
                WHERE a.numerodecuenta=$cuenta
                AND b.plan_id=a.plan_id;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 02";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 03";
            $this->mensajeDeError = "No hay parametrizacion de Tipos de Liquidacion para el plan de la cuenta $cuenta";
            return false;
        }

        while($tcp = $result->FetchRow())
        {
            if(empty($datos_plan))
            {
                $datos_plan['empresa_id']=$tcp['empresa_id'];
                $datos_plan['plan_id']=$tcp['plan_id'];
            }
            $tipos_clases_plan[$tcp['tipo_clase_cama_id']]=$tcp['tipo_liq_habitacion'];
        }

        $result->Close();

        //Revisar que esten parametrizados todos los tipos_clases_camas en el plan
        foreach($tipos_clases as $k=>$v)
        {
            if(!empty($tipos_clases_plan[$k]))
            {
                $tipos_clases[$k]['tipo_liq_habitacion']=$tipos_clases_plan[$k];
                $tipos_liq_hab[$tipos_clases_plan[$k]] = 1;
            }
            else
            {
                $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 04";
                $this->mensajeDeError = "No hay parametrizacion de Tipos de Liquidacion para el tipo clase cama $k";
                return false;
            }
        }
        unset($tipos_clases_plan);

        foreach($tipos_liq_hab as $k=>$v)
        {
            if(!IncludeClass($k,"LiquidacionHabitaciones/TiposLiqHabitaciones/$k"))
            {
                $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 05";
                $this->mensajeDeError = "No se pudo incluir la clase de Liquidacion $k";
                return false;
            }

            $obj='TipoLiqHabitaciones_'.$k;
            $class_name='TipoLiquidacionHabitacion_'.$k;

            if(!class_exists($class_name))
            {
                $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 06";
                $this->mensajeDeError = "No se pudo Instanciar la clase de Liquidacion $k";
                return false;
            }
            $$obj=new $class_name;
        }

        //ARMAR EL VECTOR PARA LIQUIDACION
        $clase_cama_no_observacion=false;
        $v_urgencias=array();
        $v_hospitalizacion=array();
        $ultima_fecha_egreso=null;
        $numero_de_movimiento=0;
        $ultima_clase_cama=null;
        $ultimo_tipo_cama=null;
        $ultima_fecha_mov=null;
                unset($this->CargosRetorno);

        foreach($datos_mov as $fecha => $datos_tipo_clase_cama_id)
        {
            foreach($datos_tipo_clase_cama_id as $tipo_clase_cama_id =>$datos_tipo_cama_id)
            {
                foreach($datos_tipo_cama_id as $tipo_cama_id => $datos_movimiento_id)
                {
                    foreach($datos_movimiento_id as  $movimiento_id => $datos)
                    {
                        $numero_de_movimiento++;

                        //controlar que los registros sean consecuentes.
                        if(($datos['fecha_ingreso']!=$ultima_fecha_egreso) && ($numero_de_movimiento>1))
                        {
                            $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 07";
                            $this->mensajeDeError = "Error de datos una fecha_ingreso no coincide con la anterior fecha_egreso.";
                            return false;
                        }

                        $ultima_fecha_egreso=$datos['fecha_egreso'];

                        //armo vector de observacion de urgencias
                        if($tipo_clase_cama_id==3)
                        {
                            //Impedir cargo de observacion en el intermedio
                            if($clase_cama_no_observacion)
                            {
                                $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 08";
                                $this->mensajeDeError = "Error de datos un cargo de observacion de urgencias despues de uno de internacion.";
                                return false;
                            }
                            if(empty($v_urgencias['INICIO']))
                            {
                                $v_urgencias['INICIO']['FECHA']=$datos['fecha_ingreso'];
                                $v_urgencias['INICIO']['MKTIME']=$this->getMktime($datos['fecha_ingreso']);
                            }

                            if(empty($datos['fecha_egreso']))
                            {
                                $ahora=date('Y-m-d H:i:s');
                                $v_urgencias['FINAL']['FECHA']=$ahora;
                                $v_urgencias['FINAL']['MKTIME']=$this->getMktime($ahora);
                            }
                            else
                            {
                                $v_urgencias['FINAL']['FECHA']=$datos['fecha_egreso'];
                                $v_urgencias['FINAL']['MKTIME']=$this->getMktime($datos['fecha_egreso']);
                            }

                            $v_urgencias['MOVIMIENTO'][$movimiento_id]=$datos;
                            //$v_urgencias['TIPOS_CAMAS'][$datos['tipo_cama_id']]=$datos['tipo_cama_id'];
                        }
                        else // para cargos de hospitalizacion y uci
                        {
                            //esto es para contol de la secuencia de cargos de observacion
                            $clase_cama_no_observacion=true;

                            //agrupamiento cronologico de tipo de camas iguales
//                             if(empty($ultima_fecha_mov))
//                             {
//                                 $ultima_fecha_mov = $fecha;
//                             }

                            if(!($ultima_clase_cama==$tipo_clase_cama_id && $ultimo_tipo_cama==$movimiento_id))
                            {
                                if(!empty($ultima_fecha_mov))
                                {
                                    $numero_dias = round((($this->getMktime($fecha) - $this->getMktime($ultima_fecha_mov)) / (24 * 60 * 60)),2);
                                    $v_hospitalizacion[$ultima_fecha_mov][$ultima_clase_cama][$ultimo_tipo_cama]['FINAL']['NUM_DIAS']=$numero_dias;
                                }
                                $ultima_fecha_mov = $fecha;
                                $ultima_clase_cama = $tipo_clase_cama_id;
                                $ultimo_tipo_cama = $movimiento_id;
                            }

                            if(empty($v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['INICIO']))
                            {
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['INICIO']['FECHA']=$datos['fecha_ingreso'];
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['INICIO']['MKTIME']=$this->getMktime($datos['fecha_ingreso']);
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['INICIO']['DATE']=$this->getDate($datos['fecha_ingreso']);
                            }


                            if(empty($datos['fecha_egreso']))
                            {
                                $ahora=date('Y-m-d H:i:s');
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['FINAL']['FECHA']=$ahora;
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['FINAL']['MKTIME']=$this->getMktime($ahora);
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['FINAL']['DATE']=date('Y-m-d');
                            }
                            else
                            {
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['FINAL']['FECHA']=$datos['fecha_egreso'];
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['FINAL']['MKTIME']=$this->getMktime($datos['fecha_egreso']);
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['FINAL']['DATE']=$this->getDate($datos['fecha_egreso']);
                            }

                            if($numTotalMov==$numero_de_movimiento)
                            {
                                if(empty($datos['fecha_egreso']))
                                {
                                    $fecha_final = $ahora=date('Y-m-d');
                                }
                                else
                                {
                                    $fecha_final = $this->getDate($datos['fecha_egreso']);
                                }
                                $numero_dias = round((($this->getMktime($fecha_final) - $this->getMktime($ultima_fecha_mov)) / (24 * 60 * 60)),2);
                                $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['FINAL']['NUM_DIAS']=$numero_dias;
                            }

                            //$v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$tipo_cama_id]['MOVIMIENTO'][$movimiento_id]=$datos;
                            $v_hospitalizacion[$ultima_fecha_mov][$tipo_clase_cama_id][$movimiento_id]['MOVIMIENTO'][$tipo_cama_id]=$datos;
                        }
                    }
                }
            }
        }//fin de la armada del vector de liquidacion

        if($v_urgencias)
        {
            $obj='TipoLiqHabitaciones_'.$tipos_clases['3']['tipo_liq_habitacion'];
            //echo $obj;

                        $CargosUrgencias = $$obj->GetCargosLiqHabitaciones(3,$v_urgencias,$datos_plan,count($v_hospitalizacion));
            if($CargosUrgencias===false)
            {
                $this->error = $$obj->Err();
                $this->mensajeDeError = $$obj->ErrMsg();
                return false;
            }

            unset($v_urgencias);
            unset($$obj);
            if($this->GenerarCargosRetorno($CargosUrgencias,$dptos_mvto)===false)
            {
                if($this->error)
                {
                    return false;
                }
                else
                {
                    $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 08";
                    $this->mensajeDeError = "Llamado al metodo GenerarCargosRetorno() retorno false";
                    return false;
                }
            }
        }

        //print_r($v_hospitalizacion);
        if($v_hospitalizacion)
        {
            if($this->LiqTMPHosp(&$v_hospitalizacion,$datos_plan,$dptos_mvto)===false)
            {
                if($this->error)
                {
                    return false;
                }
                else
                {
                    $this->error = "CLASS LiquidacionHabitaciones - LiquidarIngreso - ERROR 08";
                    $this->mensajeDeError = "Llamado al metodo GenerarCargosRetorno() retorno false";
                    return false;
                }
            }
        }

        if(!empty($this->CargosRetorno))
        {
            return $this->CargosRetorno;
        }
        else
        {
            return true;
        }
    }//end of method


    /**
    * Metodo temporal
    *
    * @param array $datos_hosp
    * @return integer
    * @access public
    */
    function LiqTMPHosp($datos_hosp,$datos_plan,$dptos_mvto)
    {
        foreach($datos_hosp as $fecha_mov=>$tipos_clases_cama)
        {
            foreach($tipos_clases_cama as $tipo_clase_cama_id=>$tipos_cama)
            {
                foreach($tipos_cama as $tipo_cama_id=>$datos)
                {
                    if($datos['FINAL']['NUM_DIAS']>0)
                    {
                        if(!$cargos[] = $this->GetCargoPlan($tipo_cama_id,&$datos_plan,$datos['FINAL']['NUM_DIAS']))
                        {
                            if($this->error)
                            {
                                return false;
                            }
                            else
                            {
                                $this->error = "CLASS LiquidacionHabitaciones - LiqTMPHosp - ERROR 01";
                                $this->mensajeDeError = 'No se pudo obtener el cargo del tipo de camas.';
                                return false;
                            }
                        }
                    }
                }
            }
        }

        if($this->GenerarCargosRetorno(&$cargos,$dptos_mvto)===false)
        {
            if($this->error)
            {
                return false;
            }
            else
            {
                $this->error = "CLASS LiquidacionHabitaciones - LiqTMPHosp - ERROR 02";
                $this->mensajeDeError = "Llamado al metodo GenerarCargosRetorno() retorno false";
                return false;
            }
        }

    }//end of method


    function &GetCargoPlan($tipo_cama_id, $datos_plan, $cantidad=1)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT *, CASE WHEN a.valor_lista > 0 THEN ROUND(a.valor_lista,get_digitos_redondeo()) ELSE ROUND((b.precio + (b.precio * a.porcentaje /100)),get_digitos_redondeo()) END as valor_unidad
                FROM planes_tipos_camas a, tarifarios_detalle b
                WHERE a.tipo_cama_id = $tipo_cama_id
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
            $this->error = "CLASS  LiquidacionHabitaciones- GetCargoPlan - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        //SI NO HAY CARGOS DE HABITACIONES
        if($result->EOF)
        {
            $this->error = "CLASS  LiquidacionHabitaciones- GetCargoPlan - ERROR 02";
            $this->mensajeDeError = "No hay cargos parametrizados para el plan $datos_plan[plan_id] para los tipos camas $filtro";
            return false;
        }

        $cargo= $result->FetchRow();
        $result->Close();

        $cargo['cantidad']=$cantidad;

        return $cargo;
    }//end of method




    /**
    * Metodo para retornar el mktime de una fecha timestam
    *
    * @param date $fecha
    * @return integer
    * @access public
    */
    function GenerarCargosRetorno($datos,$dptos_mvto)
    {

        if(!is_array($datos)) return true;

        foreach($datos as $k=>$v)
        {
            $cargo=$v;
            $v['valor_unidad']=$v['valor_unidad']+($v['valor_unidad']*$v['porcentaje']/100);
            $cargo_liquidado['precio_plan']=round($v['valor_unidad'],GetDigitosRedondeo());
            $cargo_liquidado['valor_descuento_empresa']=0;
            $cargo_liquidado['valor_descuento_paciente']=0;
            $cargo_liquidado['porcentaje_gravamen']=0;
            $cargo_liquidado['valor_no_cubierto']=round(($v['valor_excedente'] * $v['cantidad']),GetDigitosRedondeo());
            $cargo_liquidado['valor_cubierto']=round(($v['valor_unidad'] * $v['cantidad']),GetDigitosRedondeo());
            $cargo_liquidado['valor_cargo']=round(($cargo_liquidado['valor_cubierto']+$cargo_liquidado['valor_no_cubierto']),GetDigitosRedondeo());
            $cargo_liquidado['cantidad']=$v['cantidad'];
            $cargo_liquidado['descripcion']=$v['descripcion'];
            $cargo_liquidado['tarifario_id']=$v['tarifario_id'];
            $cargo_liquidado['cargo']=$v['cargo'];
            $cargo_liquidado['sw_cuota_paciente']=0;
            $cargo_liquidado['sw_cuota_moderadora']=0;
            $cargo_liquidado['facturado']=1;
            $cargo_liquidado['cargo_cups']=$v['cargo_cups'];
            $cargo_liquidado['tipo_cama_id']=$v['tipo_cama_id'];
            $cargo_liquidado['departamento']=$dptos_mvto[$v['tipo_cama_id']];
            $this->CargosRetorno[]=$cargo_liquidado;
            unset($cargo_liquidado);
        }

        return true;
    }//end of method

    /**
    * Metodo para retornar el mktime de una fecha timestam
    *
    * @param date $fecha
    * @return integer
    * @access public
    */
    function getMktime($fecha)
    {
        $fecha=str_replace("/","-",$fecha);
        $fecha=explode(" ",$fecha);
        $f_date=explode("-",$fecha[0]);
        $f_time=explode(":",$fecha[1]);
        for($i=0;$i<3;$i++)
        {
            if(!isset($f_time[$i]))
            {
                $f_time[$i]=0;
            }
        }
        return mktime($f_time[0],$f_time[1],$f_time[2],$f_date[1],$f_date[2],$f_date[0]);
    }//end of method

    /**
    * Metodo para retornar el mktime de una fecha timestam
    *
    * @param date $fecha
    * @return integer
    * @access public
    */
    function getDate($fecha)
    {
        $fecha=str_replace("/","-",$fecha);
        $fecha=explode(" ",$fecha);
        if(is_array($fecha))
        {
            $fecha = $fecha[0];
        }
        return trim($fecha);
    }

}//end of class.

/*
       //esto es para contol de la secuencia de cargos de observacion
                            $clase_cama_no_observacion=true;

                            //
                            if(empty($v_hospitalizacion[$fecha][$tipo_clase_cama_id][$tipo_cama_id]['INICIO']))
                            {
                                $v_hospitalizacion[$fecha][$tipo_clase_cama_id][$tipo_cama_id]['INICIO']['FECHA']=$datos['fecha_ingreso'];
                                $v_hospitalizacion[$fecha][$tipo_clase_cama_id][$tipo_cama_id]['INICIO']['MKTIME']=$this->getMktime($datos['fecha_ingreso']);
                            }


                            if(empty($datos['fecha_egreso']))
                            {
                                $ahora=date('Y-m-d H:i:s');
                                $v_hospitalizacion[$fecha][$tipo_clase_cama_id][$tipo_cama_id]['FINAL']['FECHA']=$ahora;
                                $v_hospitalizacion[$fecha][$tipo_clase_cama_id][$tipo_cama_id]['FINAL']['MKTIME']=$this->getMktime($ahora);
                            }
                            else
                            {
                                $v_hospitalizacion[$fecha][$tipo_clase_cama_id][$tipo_cama_id]['FINAL']['FECHA']=$datos['fecha_egreso'];
                                $v_hospitalizacion[$fecha][$tipo_clase_cama_id][$tipo_cama_id]['FINAL']['MKTIME']=$this->getMktime($datos['fecha_egreso']);
                            }


                            $v_hospitalizacion[$fecha][$tipo_clase_cama_id][$tipo_cama_id]['MOVIMIENTO'][$movimiento_id]=$datos;

*/

?>
