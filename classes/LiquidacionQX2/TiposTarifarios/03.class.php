 <?php

/**
* $Id: 03.class.php,v 1.1 2006/01/30 16:04:46 lorena Exp $
*/

/**
* Clase para la liquidacion de Cargos Qx para tipo de tarifarios 03 : PARTICULARES (POR TIEMPO)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1 $
* @package SIIS
*/
class TipoLiquidacionQX_03 extends LiquidacionCargos
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
    function TipoLiquidacionQX_03()
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

        if($tiposDeCargo['DS'])
        {

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
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $valor_uvrs['CARGO'];

                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
                    $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = $porcentaje;
                 }
            }
        }

        return true;

    }//fin del metodo



}//fin de la clase
?>
