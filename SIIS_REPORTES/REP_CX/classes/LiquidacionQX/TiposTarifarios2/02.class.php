 <?php

/**
* $Id: 02.class.php,v 1.13 2006/07/31 20:05:12 alex Exp $
*/

/**
* Clase para la liquidacion de Cargos Qx para tipo de tarifarios 02 : ISS2000 - ISS2001
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.13 $
* @package SIIS
*/
class TipoLiquidacionQX_02 extends LiquidacionCargos
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
    function TipoLiquidacionQX_02()
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
        $mayorUVR=0;

        foreach($this->datosQX  as $NumCirujano=>$Procedimientos)
        {
            $v=array();
            foreach($Procedimientos as $NumConsecutivoProcedimiento=>$datos_procedimiento)
            {
                if(($uvrs=$this->GetUVRS(&$datos_procedimiento))===false)
                {
                    if(!$this->error)
                    {
                        $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionProcedimientos - ERROR 01";
                        $this->mensajeDeError = "Llamado al metodo GetUVRS() retorno false";
                    }
                    return false;
                }

                //Genero variable con la mayor UVR para tener en cuenta en la liquidacion de Derechos de Materiales.
                if($uvrs > $mayorUVR) $mayorUVR = $uvrs;

                if(($porcentajePlan = $this->GetPreciosPlanTarifario($datos_procedimiento['tarifario_id'],$datos_procedimiento['cargo']))===false) return false;

                $this->datosQX[$NumCirujano][$NumConsecutivoProcedimiento]['uvrs']=$uvrs;
                $this->datosQX[$NumCirujano][$NumConsecutivoProcedimiento]['porcentaje']    = $porcentajePlan['porcentaje'];
                $this->datosQX[$NumCirujano][$NumConsecutivoProcedimiento]['uvr_ponderada'] = ($uvrs + ($uvrs * $porcentajePlan['porcentaje'] / 100 ));

                $this->datosQX[$NumCirujano][$NumConsecutivoProcedimiento]['sw_medicamentos_consumo'] = '1';

                $v[$NumConsecutivoProcedimiento] = $this->datosQX[$NumCirujano][$NumConsecutivoProcedimiento]['uvr_ponderada'];
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
            $porc_mayor_cirujano[$NumCirujano]=$proc_liq[$NumCirujano][1]['uvr_ponderada'];
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
                $w[$NumCirujano.'|'.$NumProcedimiento] = $datos['uvr_ponderada'];
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
            if(!$this->GetLiquidacionHonorarios('DC'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetLiquidacionDC() retorno false";
                }
                return false;
            }
        }

        if($tiposDeCargo['DA'])
        {
            if(!$this->GetLiquidacionHonorarios('DA'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetLiquidacionDA() retorno false";
                }
                return false;
            }
        }

        if($tiposDeCargo['DY'])
        {
            if(!$this->GetLiquidacionHonorarios('DY'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetLiquidacionDY() retorno false";
                }
                return false;
            }
        }

        if($tiposDeCargo['DS'])
        {
            if(!$this->GetLiquidacionDerechos('DS'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetLiquidacionDS() retorno false";
                }
                return false;
            }
        }

        //SEGUN TARIFARIO SI LAS UVRS SON SUPERIORES A 170 DE ALGUNO DE LOS CARGOS SE COBRAN AL CONSUMO
        if($mayorUVR <= 170)
        {
            if(!$this->GetLiquidacionDerechos('DM'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionProcedimientos - ERROR 02";
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
    * Metodo que calcula la liquidacion de Honorarios de Cirujano, Anestesista y Ayudante de un acto QX
    *
    * @param string $tipo_cargo_qx_id tipo de liquidacion (DC,DA,DY)
    * @return boolean
    * @access private
    */
    function GetLiquidacionHonorarios($tipo_cargo_qx_id)
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
                    if(($valor_uvrs = $this->GetValoresUVRSxPlan($this->GetDatosPlan('plan_id'), $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'], $clase=$tipo_cargo_qx_id))===false) return false;
                    $valor = $this->datosQX[$NumCirujano][$NumProcedimiento]['uvrs'] * $valor_uvrs['VALOR'] * ($porcentaje / 100);
                    $profesional=$this->GetTerceroActoQX($tipo_cargo_qx_id,$NumCirujano);
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo_cups'] = $valor_uvrs['CUPS'];
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_cargo'] = $valor_uvrs['CARGO'];
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
                                                                $precio=round($valor,GetDigitosRedondeo()),
                                                                $this->GetDatosPlan('plan_id'),
                                                                $Servicio = $this->datosActoQX['servicio_actual'],
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
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_uvrs['CARGO'];

                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['VALOR_UVR'] = $valor_uvrs['VALOR'];
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = $porcentaje;
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
                    if(($valor_uvrs = $this->GetValoresUVRSxPlan($this->GetDatosPlan('plan_id'), $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'], $clase=$tipo_cargo_qx_id))===false) return false;
                    $valor = $this->datosQX[$NumCirujano][$NumProcedimiento]['uvrs'] * $valor_uvrs['VALOR'] * ($porcentaje / 100);
                    $profesional=$this->GetTerceroActoQX($tipo_cargo_qx_id,$NumCirujano);
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_uvrs['CARGO'];
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo_cups'] = $valor_uvrs['CUPS'];
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cantidad'] = 1;
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_cargo'] = $valor_uvrs['CARGO'];
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
                                                                $precio=round($valor,GetDigitosRedondeo()),
                                                                $this->GetDatosPlan('plan_id'),
                                                                $Servicio = $this->datosActoQX['servicio_actual'],
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
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_uvrs['CARGO'];

                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['VALOR_UVR'] = $valor_uvrs['VALOR'];
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = $porcentaje;
                 }
            }
        }

        return true;

    }//fin del metodo


   /**
    * Metodo que calcula la liquidacion de los derechos de Sala y Materiales
    *
    * @return boolean
    * @access private
    */
    function GetLiquidacionDerechos($tipo_cargo_qx_id)
    {
        if($tipo_cargo_qx_id!='DS' && $tipo_cargo_qx_id!='DM')
        {
            $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionDerechos - ERROR 01";
            $this->mensajeDeError = "Llamado al metodo con argumento '$tipo_cargo_qx_id' valores posibles :(DS,DM)";
            return false;
        }

        if(($tV = $this->GetTipoOrdenPorTodosLosCargos($this->tarifario_id,$tipo_cargo_qx_id,$this->tipoProcedimiento))===false) return false;

        if($tV)
        {
            foreach($this->datosQX as $NumCirujano=>$procedimientos)
            {
                foreach($procedimientos as $NumProcedimiento=>$datos)
                {
                    $n=array_search($NumCirujano.'|'.$NumProcedimiento,$this->proc_liq_rsort );
                    if(($porcentaje = $this->GetPorcentajeLiquidacion($this->GetDatosPlan('plan_id'), $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],$tipo_cargo_qx_id,$this->tipoProcedimiento,$n))===false) return false;
                    if($tipo_cargo_qx_id=='DS')
                    {
                        if(($valor_uvrs = $this->GetValor_DS($this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],$this->datosQX[$NumCirujano][$NumProcedimiento]['uvrs'],$this->datosActoQX['tipo_sala_id'],$this->datosQX[$NumCirujano][$NumProcedimiento]['porcentaje']))===false) return false;
                    }
                    else
                    {
                        if(($valor_uvrs = $this->GetValor_DM($this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],$this->datosQX[$NumCirujano][$NumProcedimiento]['uvrs'],$this->datosQX[$NumCirujano][$NumProcedimiento]['porcentaje']))===false) return false;
                        if($valor_uvrs===true)break;
                    }
                    $valor =  $valor_uvrs['VALOR'] * ($porcentaje / 100);
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_uvrs['CARGO'];
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo_cups'] = $valor_uvrs['CUPS'];
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cantidad'] = 1;
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_cargo'] = $valor_uvrs['CARGO'];

                    if(($CargoLiquidado = $this->LiquidarCargoCuenta($cuenta=0 ,
                                                                $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],
                                                                $this->datosQX[$NumCirujano][$NumProcedimiento]['cargo'],
                                                                $cantidad=1,
                                                                $descuento_manual_empresa=0 ,
                                                                $descuento_manual_paciente=0 ,
                                                                $aplicar_descuento_empresa=true ,
                                                                $aplicar_descuento_paciente=true ,
                                                                $precio=round($valor,GetDigitosRedondeo()),
                                                                $this->GetDatosPlan('plan_id'),
                                                                $Servicio = $this->datosActoQX['servicio_actual'],
                                                                $semanas_cotizacion,
                                                                $tipo_empleador_id=null,
                                                                $empleador=null,
                                                                $tipo_id_paciente=null,
                                                                $paciente_id=null))===false) return false;

                    $this->datosQX[$NumCirujano][$NumProcedimiento]['descripcion']=$CargoLiquidado['descripcion'];
                    unset($CargoLiquidado['descripcion']);

                    if($tipo_cargo_qx_id=='DM')
                    {
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['sw_medicamentos_consumo']= '0';
                    }

                    foreach($CargoLiquidado as $k=>$v)
                    {
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id][$k]=$v;
                    }
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_uvrs['CARGO'];

                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = $porcentaje;

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
                    if($tipo_cargo_qx_id=='DS')
                    {
                        if(($valor_uvrs = $this->GetValor_DS($this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],$this->datosQX[$NumCirujano][$NumProcedimiento]['uvrs'],$this->datosActoQX['tipo_sala_id'],$this->datosQX[$NumCirujano][$NumProcedimiento]['porcentaje']))===false) return false;
                    }
                    else
                    {
                        if(($valor_uvrs = $this->GetValor_DM($this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],$this->datosQX[$NumCirujano][$NumProcedimiento]['uvrs'],$this->datosQX[$NumCirujano][$NumProcedimiento]['porcentaje']))===false) return false;
                        if($valor_uvrs===true)break;
                    }
                    $valor = $valor_uvrs['VALOR'] * ($porcentaje / 100);

                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_uvrs['CARGO'];
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo_cups'] = $valor_uvrs['CUPS'];
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cantidad'] = 1;
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_cargo'] = $valor_uvrs['CARGO'];

                    if(($CargoLiquidado = $this->LiquidarCargoCuenta($cuenta=0 ,
                                                                $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],
                                                                $this->datosQX[$NumCirujano][$NumProcedimiento]['cargo'],
                                                                $cantidad=1,
                                                                $descuento_manual_empresa=0 ,
                                                                $descuento_manual_paciente=0 ,
                                                                $aplicar_descuento_empresa=true ,
                                                                $aplicar_descuento_paciente=true ,
                                                                $precio=round($valor,GetDigitosRedondeo()),
                                                                $this->GetDatosPlan('plan_id'),
                                                                $Servicio = $this->datosActoQX['servicio_actual'],
                                                                $semanas_cotizacion,
                                                                $tipo_empleador_id=null,
                                                                $empleador=null,
                                                                $tipo_id_paciente=null,
                                                                $paciente_id=null))===false) return false;

                    $this->datosQX[$NumCirujano][$NumProcedimiento]['descripcion']=$CargoLiquidado['descripcion'];
                    unset($CargoLiquidado['descripcion']);

                    if($tipo_cargo_qx_id=='DM')
                    {
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['sw_medicamentos_consumo']= '0';
                    }

                    foreach($CargoLiquidado as $k=>$v)
                    {
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id][$k]=$v;
                    }
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_uvrs['CARGO'];

                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = $porcentaje;
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
            $this->error = "CLASS  TipoLiquidacionQX_02 - GetTipoOrdenPorTodosLosCargos - ERROR 01";
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
                $this->error = "CLASS  TipoLiquidacionQX_02 - GetTipoOrdenPorTodosLosCargos - ERROR 02";
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
            $this->error = "CLASS  TipoLiquidacionQX_02 - GetTipoOrdenPorTodosLosCargos - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        list($salida)=$result->FetchRow();
        $result->Close();

        return $salida;

    }//fin del metodo


   /**
    * Metodo que calcula la liquidacion de los derechos de Sala
    *
    * @return boolean
    * @access private
    */
    function GetValor_DS($tarifario_id,$uvrs,$tipo_sala_id,$porcentaje)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT * FROM tarifarios_uvrs_ds_rangos
                WHERE tarifario_id='$tarifario_id'
                AND tipo_sala_id='$tipo_sala_id'
                AND uvrs_min <= $uvrs
                AND uvrs_max >= $uvrs";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionDS - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionDS - ERROR 02";
            $this->mensajeDeError = "No esta parametrizado el tarifario para uvrs.";
            return false;
        }

        $uvrs_tarifario=$result->FetchRow();
        $result->Close();

        if($uvrs_tarifario['sw_valor_por_uvr'])
        {
            $valor['VALOR'] = ($uvrs * $uvrs_tarifario['ds_valor']) + (($uvrs * $uvrs_tarifario['ds_valor']) * ($porcentaje/100));
        }
        else
        {
            $valor['VALOR'] = ($uvrs_tarifario['ds_valor'] + ($uvrs_tarifario['ds_valor'] * ($porcentaje/100)));
        }

        $valor['CARGO']=$uvrs_tarifario['ds_cargo'];
        $valor['CUPS']=$uvrs_tarifario['ds_cups'];
        return $valor;

    }//fin del metodo


   /**
    * Metodo que calcula la liquidacion de los derechos de Sala
    *
    * @return boolean
    * @access private
    */
    function GetValor_DM($tarifario_id,$uvrs,$porcentaje)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT * FROM tarifarios_uvrs_dm_rangos
                WHERE tarifario_id='$tarifario_id'
                AND uvrs_min <= $uvrs
                AND uvrs_max >= $uvrs;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_02 - GetLiquidacionDS - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            return true;
        }

        $uvrs_tarifario=$result->FetchRow();
        $result->Close();

        $valor['VALOR'] = ($uvrs_tarifario['dm_valor'] + ($uvrs_tarifario['dm_valor'] * ($porcentaje/100)));
        $valor['CARGO'] = $uvrs_tarifario['dm_cargo'];
        $valor['CUPS'] = $uvrs_tarifario['dm_cups'];

        return $valor;

    }//fin del metodo


   /**
    * Metodo para obtener los UVR por cada procedimiento
    *
    * @param array $procedimiento datos del procedimiento
    * @return numeric uvrs del procedimiento
    * @access public
    */
    function GetUVRS($procedimiento)
    {
        $v = $this->GetPreciosPlanTarifario($procedimiento['tarifario_id'],$procedimiento['cargo']);
        if(!$v) return false;

        if($v['tipo_unidad_id']!='02')
        {
            $this->error = "CLASS TipoLiquidacionQX_02 - GetUVRS - ERROR 09";
            $this->mensajeDeError = "El cargo :".$procedimiento['cargo'] ." del tarifario :".$procedimiento['tarifario_id'] . " No esta en UVRS";
            return false;
        }

        return $v['precio'];

    }//fin del metodo


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

}//fin de la clase
?>
