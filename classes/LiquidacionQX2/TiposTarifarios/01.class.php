 <?php

/**
* $Id: 01.class.php,v 1.2 2006/01/30 16:03:02 lorena Exp $
*/

/**
* Clase para la liquidacion de Cargos Qx para tipo de tarifarios 01 : SOAT
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.2 $
* @package SIIS
*/
class TipoLiquidacionQX_01 extends LiquidacionCargos
{
    //------------------
    // PARAMETROS
    //------------------

   /**
    * Almacena los cargos de la liquidacion
    *
    * @var array
    * @access private
    */
    var $datosQX=array();

   /**
    * Almacena los datos del Acto QX
    *
    * @var array
    * @access private
    */
    var $datosActoQX=array();

   /**
    * Tipo de procedimiento a liquidar (unico,bilateral,multiple etc...)
    *
    * @var integer
    * @access private
    */
    var $tipoProcedimiento=0;

   /**
    * Tarifario con el que se va ha realizar la liquidacion
    *
    * @var integer
    * @access private
    */
    var $tarifario_id = null;

   /**
    * Indice de los cargos de la liquidacion de mas caro a menos caro
    *
    * @var integer
    * @access private
    */
    var $proc_liq_rsort=array();


    //------------------
    // METODOS
    //------------------


   /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function TipoLiquidacionQX_01()
    {
        $this->LiquidacionCargos();
        return true;
    }//fin del metodo


   /**
    * Metodo encargado de Liquidar cargos para una intervencion QX
    *
    * @return
    * @access public
    */
    function LiquidarProcedimientos($tarifario_id,$datosQX,$datosPlan,$tiposDeCargo,$datosActoQX)
    {
        $this->datosQX       =  &$datosQX;
        $this->tarifario_id  =  $tarifario_id;
        $this->datosActoQX   =  $datosActoQX;
        $this->tipoProcedimiento = $datosActoQX['Tipo_Procedimiento'];

        $this->SetDatosPlan($datosPlan);

        $proc_liq=array();
        $porc_mayor_cirujano=array();

        foreach($this->datosQX  as $NumCirujano=>$Procedimientos)
        {
            $v=array();
            foreach($Procedimientos as $NumConsecutivoProcedimiento=>$datos_procedimiento)
            {
                if(($gqx=$this->GetGrupoQX(&$datos_procedimiento))===false)
                {
                    if(!$this->error)
                    {
                        $this->error = "CLASS  TipoLiquidacionQX_01 - GetLiquidacionProcedimientos - ERROR 01";
                        $this->mensajeDeError = "Llamado al metodo GetGrupoQX() retorno false";
                    }
                    return false;
                }

                if(($porcentajePlan = $this->GetPreciosPlanTarifario($datos_procedimiento['tarifario_id'],$datos_procedimiento['cargo']))===false) return false;

                $this->datosQX[$NumCirujano][$NumConsecutivoProcedimiento]['grupo_qx'] = (int) $gqx;
                $this->datosQX[$NumCirujano][$NumConsecutivoProcedimiento]['porcentaje']    = $porcentajePlan['porcentaje'];
                $this->datosQX[$NumCirujano][$NumConsecutivoProcedimiento]['grupo_qx_ponderado'] = ($gqx + ($gqx * $porcentajePlan['porcentaje'] / 100 ));

                $v[$NumConsecutivoProcedimiento] = $this->datosQX[$NumCirujano][$NumConsecutivoProcedimiento]['grupo_qx_ponderado'];
            }
            arsort($v,SORT_NUMERIC);
            $vsort=array();
            $k=1;
            foreach($v as $i=>$valor)
            {
                $vsort[$k] = $this->datosQX[$NumCirujano][$i];
                $k++;
            }
            $proc_liq[$NumCirujano]=$vsort;
            $porc_mayor_cirujano[$NumCirujano]=$proc_liq[$NumCirujano][1]['grupo_qx_ponderado'];
            unset($vsort);
        }

        if(count($porc_mayor_cirujano)>1)
        {
            arsort($porc_mayor_cirujano,SORT_NUMERIC);
            $k=1;
            foreach($porc_mayor_cirujano as $i=>$valor)
            {
                $proc_liq_tmp[$k]=$proc_liq[$i];
                $k++;
            }
            $proc_liq = $proc_liq_tmp;
            unset($proc_liq_tmp);
            unset($porc_mayor_cirujano);
        }

        $this->datosQX = $proc_liq;

        foreach($proc_liq as $NumCirujano=>$procedimientos)
        {
            foreach($procedimientos as $NumProcedimiento=>$datos)
            {
                $w[$NumCirujano.'|'.$NumProcedimiento] = $datos['grupo_qx_ponderado'];
            }

        }

        arsort($w,SORT_NUMERIC);
        $k=1;
        foreach($w as $i=>$valor)
        {
            $this->proc_liq_rsort[$k]=$i;
            $k++;
        }

        unset($proc_liq);

        if($tiposDeCargo['DC'])
        {
            if(!$this->GetLiquidacionDerecho('DC'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_01 - GetLiquidacionProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetLiquidacionDC() retorno false";
                }
                return false;
            }
        }

        if($tiposDeCargo['DA'])
        {
            if(!$this->GetLiquidacionDerecho('DA'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_01 - GetLiquidacionProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetLiquidacionDA() retorno false";
                }
                return false;
            }
        }

        if($tiposDeCargo['DY'])
        {
            if(!$this->GetLiquidacionDerecho('DY'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_01 - GetLiquidacionProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetLiquidacionDY() retorno false";
                }
                return false;
            }
        }


        if($tiposDeCargo['DS'])
        {
            if(!$this->GetLiquidacionDerecho('DS'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_01 - GetLiquidacionProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetLiquidacionDS() retorno false";
                }
                return false;
            }
        }

        if($tiposDeCargo['DM'])
        {
            if(!$this->GetLiquidacionDerecho('DM'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_01 - GetLiquidacionProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetLiquidacionDM() retorno false";
                }
                return false;
            }
        }

        return true;
    }//fin del metodo

   /**
    * Retorna el profesional que realizo el cargo (Cirujano, Anestesista y Ayudante)
    *
    * @param string $tipo_cargo_qx_id tipo de liquidacion (DC,DA,DY)
    * @return array
    * @access private
    */
    function GetTerceroActoQX($tipo_cargo_qx_id,$NumCirujano)
    {
        switch($tipo_cargo_qx_id)
        {
            case 'DC';
                    $v['tipo_id_profesional'] = $this->datosQX[$NumCirujano][1]['tipo_id_cirujano'];
                    $v['profesional_id'] =  $this->datosQX[$NumCirujano][1]['cirujano_id'];
            break;
            case 'DA';
                    $v['tipo_id_profesional'] = $this->datosActoQX['tipo_id_anestesiologo'];
                    $v['profesional_id'] =  $this->datosActoQX['anestesiologo_id'];
            break;
            case 'DY';
                    $v['tipo_id_profesional'] = $this->datosActoQX['tipo_id_ayudante'];
                    $v['profesional_id'] =  $this->datosActoQX['ayudante_id'];
            break;
            default:
                    $v['tipo_id_profesional'] = '';
                    $v['profesional_id'] =  '';
        }

        return $v;

    }//fin del metodo


   /**
    * Metodo que calcula la liquidacion de Honorarios de Cirujano, Anestesista y Ayudante  y de lo sderechos de sala y de materialesde un acto QX
    *
    * @param string $tipo_cargo_qx_id tipo de liquidacion (DC,DA,DY,DS,DM)
    * @return boolean
    * @access private
    */
    function GetLiquidacionDerecho($tipo_cargo_qx_id)
    {

        if(($tV = $this->GetTipoOrdenPorTodosLosCargos($this->tarifario_id,$tipo_cargo_qx_id,$this->tipoProcedimiento))===false) return false;

        if($tV)
        {
            foreach($this->datosQX as $NumCirujano=>$procedimientos)
            {
                foreach($procedimientos as $NumProcedimiento=>$datos)
                {
                    $n=array_search($NumCirujano.'|'.$NumProcedimiento,$this->proc_liq_rsort );
                    if(($porcentaje = $this->GetPorcentajeLiquidacion($this->GetDatosPlan('plan_id'), $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],$tipo_cargo_qx_id,$this->tipoProcedimiento,$n))===false) return false;
                    if(($valor_gqx = $this->GetValorGrupQX($this->GetDatosPlan('plan_id'), $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'], $clase=$tipo_cargo_qx_id, $this->datosQX[$NumCirujano][$NumProcedimiento]['grupo_qx']))===false) return false;
                    if(is_array($valor_gqx))
                    {
                        $valor = $valor_gqx['precio'] * ($porcentaje / 100);
                        $profesional=$this->GetTerceroActoQX($tipo_cargo_qx_id,$NumCirujano);
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo_cups'] = $valor_gqx['cargo_cups'];
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_cargo'] = round($valor,GetDigitosRedondeo());
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['tipo_id_tercero']= $profesional['tipo_id_profesional'];
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['tercero_id']= $profesional['profesional_id'];

                        if(($CargoLiquidado = $this->LiquidarCargoCuenta($cuenta=0 ,
                                                                    $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],
                                                                    $this->datosQX[$NumCirujano][$NumProcedimiento]['cargo'],
                                                                    $cantidad=1,
                                                                    $descuento_manual_empresa=0 ,
                                                                    $descuento_manual_paciente=0 ,
                                                                    $aplicar_descuento_empresa=true ,
                                                                    $aplicar_descuento_paciente=true ,
                                                                    $precio=round(round($valor/100,0)*100,GetDigitosRedondeo()),
                                                                    $this->GetDatosPlan('plan_id'),
                                                                    $Servicio='3',
                                                                    $semanas_cotizacion,
                                                                    $tipo_empleador_id=null,
                                                                    $empleador=null,
                                                                    $tipo_id_paciente=null,
                                                                    $paciente_id=null))===false) return false;

                        $this->datosQX[$NumCirujano][$NumProcedimiento]['descripcion']=$CargoLiquidado['descripcion'];
                        unset($CargoLiquidado['descripcion']);

                        foreach($CargoLiquidado as $k=>$v)
                        {
                            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id][$k]=$v;
                        }
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_gqx['cargo'];

                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = $porcentaje;
                    }
                }
            }
        }
        else
        {
            foreach($this->datosQX as $NumCirujano=>$procedimientos)
            {
                foreach($procedimientos as $NumProcedimiento=>$datos)
                {
                    if(($porcentaje = $this->GetPorcentajeLiquidacion($this->GetDatosPlan('plan_id'), $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],$tipo_cargo_qx_id,$this->tipoProcedimiento,$NumProcedimiento))===false) return false;

                    if(($valor_gqx = $this->GetValorGrupQX($this->GetDatosPlan('plan_id'), $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'], $clase=$tipo_cargo_qx_id, $this->datosQX[$NumCirujano][$NumProcedimiento]['grupo_qx']))===false) return false;
                    if(is_array($valor_gqx))
                    {
                        $valor = $valor_gqx['precio'] * ($porcentaje / 100);

                        $profesional=$this->GetTerceroActoQX($tipo_cargo_qx_id,$NumCirujano);
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo_cups'] = $valor_gqx['cargo_cups'];
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_cargo'] = round($valor,GetDigitosRedondeo());
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['tipo_id_tercero']= $profesional['tipo_id_profesional'];
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['tercero_id']= $profesional['profesional_id'];

                        if(($CargoLiquidado = $this->LiquidarCargoCuenta($cuenta=0 ,
                                                                    $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],
                                                                    $this->datosQX[$NumCirujano][$NumProcedimiento]['cargo'],
                                                                    $cantidad=1,
                                                                    $descuento_manual_empresa=0 ,
                                                                    $descuento_manual_paciente=0 ,
                                                                    $aplicar_descuento_empresa=true ,
                                                                    $aplicar_descuento_paciente=true ,
                                                                    $precio=round(round($valor/100,0)*100,GetDigitosRedondeo()),
                                                                    $this->GetDatosPlan('plan_id'),
                                                                    $Servicio='3',
                                                                    $semanas_cotizacion,
                                                                    $tipo_empleador_id=null,
                                                                    $empleador=null,
                                                                    $tipo_id_paciente=null,
                                                                    $paciente_id=null))===false) return false;

                        $this->datosQX[$NumCirujano][$NumProcedimiento]['descripcion']=$CargoLiquidado['descripcion'];
                        unset($CargoLiquidado['descripcion']);

                        foreach($CargoLiquidado as $k=>$v)
                        {
                            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id][$k]=$v;
                        }
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_gqx['cargo'];

                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = $porcentaje;
                    }
                 }
            }
        }

        return true;

    }//fin del metodo



   /**
    * Metodo que retorna el porcentaje para la liquidacion de un cargo
    *
    * @param integer
    * @return boolean
    * @access private
    */
    function GetPorcentajeLiquidacion($plan_id,$tarifario_id,$tipo_cargo_qx_id,$via_acceso,$NumProcedimiento)
    {
        list($dbconn) = GetDBconn();

        $sql = "SELECT porcentaje
                FROM tarifarios_liq_qx_porcentajes_excepciones
                WHERE plan_id = $plan_id
                AND tarifario_id = '$tarifario_id'
                AND via_acceso = '$via_acceso'
                AND tipo_cargo_qx_id = '$tipo_cargo_qx_id'
                AND numero_qx_min <= $NumProcedimiento
                AND (numero_qx_max >= $NumProcedimiento OR numero_qx_max IS NULL);";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_01 - GetTipoOrdenPorTodosLosCargos - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $sql = "SELECT porcentaje
                    FROM tarifarios_liq_qx_porcentajes
                    WHERE tarifario_id = '$tarifario_id'
                    AND via_acceso = '$via_acceso'
                    AND tipo_cargo_qx_id = '$tipo_cargo_qx_id'
                    AND numero_qx_min <= $NumProcedimiento
                    AND (numero_qx_max >= $NumProcedimiento OR numero_qx_max IS NULL);";

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CLASS  TipoLiquidacionQX_01 - GetTipoOrdenPorTodosLosCargos - ERROR 02";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }

        if($result->EOF)
        {
            return 0.00;
        }

        list($salida)=$result->FetchRow();
        $result->Close();

        if(is_numeric($salida))
        {
            return $salida;
        }
        else
        {
            return 0.00;
        }

    }//fin del metodo


   /**
    * Metodo que retorna si el el orden de los cargos es entre todas las cirugias
    *
    * @param integer
    * @return boolean
    * @access private
    */
    function GetTipoOrdenPorTodosLosCargos($tarifario_id,$tipo_cargo_qx_id,$via_acceso)
    {
        list($dbconn) = GetDBconn();

        $sql = "SELECT (a.existe + b.existe) AS existe FROM
                (   SELECT COUNT(*) AS existe
                    FROM tarifarios_liq_qx_orden_todos_los_cargos
                    WHERE tarifario_id='$tarifario_id'
                    AND tipo_cargo_qx_id='$tipo_cargo_qx_id'
                    AND via_acceso='$via_acceso') AS a,
                (
                    SELECT COUNT(*) AS existe
                    FROM tarifarios_liq_qx_orden_todos_los_cargos_excepciones
                    WHERE plan_id=".$this->GetDatosPlan('plan_id')."
                    AND tarifario_id='$tarifario_id'
                    AND tipo_cargo_qx_id='$tipo_cargo_qx_id'
                    AND via_acceso='$via_acceso'
                ) AS b";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_01 - GetTipoOrdenPorTodosLosCargos - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        list($salida)=$result->FetchRow();
        $result->Close();

        return $salida;

    }//fin del metodo


   /**
    * Metodo para obtener el grupo Qx de cada procedimiento
    *
    * @param array $procedimiento datos del procedimiento
    * @return numeric grupo QX del procedimiento
    * @access public
    */
    function GetGrupoQX($procedimiento)
    {
        $v = $this->GetPreciosPlanTarifario($procedimiento['tarifario_id'],$procedimiento['cargo']);
        if(!$v) return false;

        if($v['tipo_unidad_id']!='04')
        {
            $this->error = "CLASS TipoLiquidacionQX_01 - GetGrupoQX - ERROR 09";
            $this->mensajeDeError = "El cargo :".$procedimiento['cargo'] ." del tarifario :".$procedimiento['tarifario_id'] . " No tiene asociado un grupo QX";
            return false;
        }

        return $v['precio'];

    }//fin del metodo


   /**
    * Metodo que retorna el valor de un derecho para un grupo Qx
    *
    * @param integer $plan_id
    * @param string  $tarifario_id
    * @param string  $tipo_cargo_qx_id Clase de derecho a liquidar (DC,DA,DY,DS,DM)
    * @return numeric valor del derecho
    * @access public
    */
    function GetValorGrupQX($plan_id, $tarifario_id, $tipo_cargo_qx_id, $grupo_qx)
    {
        static $datos_gqx;

        if(!empty($datos_gqx[$tarifario_id][$tipo_cargo_qx_id][$grupo_qx]))
        {
            return $datos_gqx[$tarifario_id][$tipo_cargo_qx_id][$grupo_qx];
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT * FROM tarifarios_grupos_qx_cargos a, tarifarios_detalle b
                WHERE a.tarifario_id='$tarifario_id'
                AND a.grupo_qx  = $grupo_qx
                AND b.tarifario_id = a.tarifario_id
                AND b.cargo = a.cargo;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_01 - GetValorGrupQX - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "CLASS  TipoLiquidacionQX_01 - GetValorGrupQX - ERROR 02";
            $this->mensajeDeError = "El grupo [$grupo_qx] para el tipo de honorario [$tipo_cargo_qx_id] en el tarifario [$tarifario_id] no se encuentra parametrizado";
            return false;
        }

        while($registros=$result->FetchRow())
        {
            switch ($registros['tipo_unidad_id'])
            {
             case '01':
             break;

             case '03':
                if(!$smmlv=GetSalarioMinimo(date('Y')))
                {
                    $this->error = "CLASS  TipoLiquidacionQX_01 - GetValorGrupQX - ERROR 03";
                    $this->mensajeDeError = "No se encuentra parametrizado el valor del SMDLV. para el año ".date('Y');
                    return false;
                }
                $registros['precio'] = $registros['precio'] * $smmlv;
             break;

             default:
                $this->error = "CLASS  TipoLiquidacionQX_01 - GetValorGrupQX - ERROR 04";
                $this->mensajeDeError = "Tipo de Unidad para el cargo [".$registros['tarifario_id']."-".$registros['cargo']."] no valido, valores permitos (Pesos y SMLV).";
                return false;
            }
            $datos_gqx[$tarifario_id][$registros['tipo_cargo_qx_id']][$grupo_qx]=$registros;
        }
        $result->Close();


        if(empty($datos_gqx[$tarifario_id]['DC'][$grupo_qx]))
        {
                $this->error = "CLASS  TipoLiquidacionQX_01 - GetValorGrupQX - ERROR 05";
                $this->mensajeDeError = "El grupo [$grupo_qx] para el tipo de honorario [DC] en el tarifario [$tarifario_id] no se encuentra parametrizado";
                return false;
        }
        if(empty($datos_gqx[$tarifario_id]['DA'][$grupo_qx]))  $datos_gqx[$tarifario_id]['DA'][$grupo_qx]= -1;
        if(empty($datos_gqx[$tarifario_id]['DY'][$grupo_qx]))  $datos_gqx[$tarifario_id]['DY'][$grupo_qx]= -1;
        if(empty($datos_gqx[$tarifario_id]['DM'][$grupo_qx]))  $datos_gqx[$tarifario_id]['DM'][$grupo_qx]= -1;
        if(empty($datos_gqx[$tarifario_id]['DS'][$grupo_qx]))  $datos_gqx[$tarifario_id]['DS'][$grupo_qx]= -1;

        return $datos_gqx[$tarifario_id][$tipo_cargo_qx_id][$grupo_qx];
    }//fin del metodo

}//fin de la clase
?>
