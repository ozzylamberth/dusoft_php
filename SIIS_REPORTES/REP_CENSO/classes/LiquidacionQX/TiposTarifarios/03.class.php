 <?php

/**
* $Id: 03.class.php,v 1.4 2006/08/18 14:46:11 alex Exp $
*/

/**
* Clase para la liquidacion de Cargos Qx para tipo de tarifarios 03 : PARTICULARES Y PREPAGADAS (POR TIEMPO)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.4 $
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

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        foreach($this->datosQX as $NumCirujano=>$procedimientos)
        {
            foreach($procedimientos as $NumProcedimiento=>$datos)
            {
                $sql="SELECT descripcion FROM tarifarios_detalle WHERE tarifario_id = '".$datos['tarifario_id']."' AND cargo = '".$datos['cargo']."';";

                $result = $dbconn->Execute($sql);

                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_03 - LiquidarProcedimientos - ERROR 01";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                if(!$result->EOF)
                {
                    list($this->datosQX[$NumCirujano][$NumProcedimiento]['descripcion'])=$result->FetchRow();
                }

            }
        }

        if(!$this->GetLiquidacionDerechoDS())
        {
            if(!$this->error)
            {
                $this->error = "CLASS  TipoLiquidacionQX_03 - LiquidarProcedimientos - ERROR 01";
                $this->mensajeDeError = "Llamado al metodo GetLiquidacionDerechosDS() retorno false";
            }
            return false;
        }

        if($tiposDeCargo['DC'])
        {
            if(!$this->GetCargoEnCero('DC'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_03 - LiquidarProcedimientos - ERROR 02";
                    $this->mensajeDeError = "Llamado al metodo GetCargoEnCero() retorno false";
                }
                return false;
            }
        }

        if($tiposDeCargo['DA'])
        {
            if(!$this->GetCargoEnCero('DA'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_03 - LiquidarProcedimientos - ERROR 03";
                    $this->mensajeDeError = "Llamado al metodo GetCargoEnCero() retorno false";
                }
                return false;
            }
        }

        if($tiposDeCargo['DY'])
        {
            if(!$this->GetCargoEnCero('DY'))
            {
                if(!$this->error)
                {
                    $this->error = "CLASS  TipoLiquidacionQX_03 - LiquidarProcedimientos - ERROR 04";
                    $this->mensajeDeError = "Llamado al metodo GetCargoEnCero() retorno false";
                }
                return false;
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
    function GetLiquidacionDerechoDS()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $NumCirujano = 1;
        $NumProcedimiento = 1;
        $tipo_cargo_qx_id = 'DS';

        $time = explode(":",$this->datosActoQX['duracion_cirugia']);
        $minutos = $time[0]*60 + $time[1];
        if(!is_numeric($minutos))
        {
            $minutos = 0;
        }

        //CARGO POR RANGO DE TIEMPO
        $sql = "SELECT ds_cups,ds_cargo FROM tarifarios_por_tiempo_ds
                WHERE tarifario_id = '".$this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id']."'
                AND tipo_sala_id = '".$this->datosActoQX['tipo_sala_id']."'
                AND (ambito_cirugia_id = '".$this->datosActoQX['ambito_cirugia_id']."' OR ambito_cirugia_id IS NULL)
                AND rango_min < $minutos
                AND (rango_max >= $minutos OR rango_max IS NULL)
                AND sw_minuto_adicional = '0'";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_03 - GetLiquidacionDerechoDS - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if(!$result->EOF)
        {
            $DATOS_CARGO = $result->FetchRow();
            $result->Close();

            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $DATOS_CARGO['ds_cargo'];
            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo_cups'] = $DATOS_CARGO['ds_cups'];
            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cantidad'] = 1;

            if(($CargoLiquidado = $this->LiquidarCargoCuenta($cuenta=0 ,
                                                        $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],
                                                        $DATOS_CARGO['ds_cargo'],
                                                        $cantidad=1,
                                                        $descuento_manual_empresa=0 ,
                                                        $descuento_manual_paciente=0 ,
                                                        $aplicar_descuento_empresa=true ,
                                                        $aplicar_descuento_paciente=true ,
                                                        $precio=NULL,
                                                        $this->GetDatosPlan('plan_id'),
                                                        $Servicio = $this->datosActoQX['servicio_actual'],
                                                        $semanas_cotizacion,
                                                        $tipo_empleador_id=null,
                                                        $empleador=null,
                                                        $tipo_id_paciente=null,
                                                        $paciente_id=null))===false) return false;

            foreach($CargoLiquidado as $k=>$v)
            {
                $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id][$k]=$v;
            }
            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = 100;
        }



        //CARGO POR MINUTO ADICIONAL
        $sql = "SELECT ds_cups,ds_cargo,($minutos-rango_min) as cantidad  FROM tarifarios_por_tiempo_ds
                WHERE tarifario_id = '".$this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id']."'
                AND tipo_sala_id = '".$this->datosActoQX['tipo_sala_id']."'
                AND (ambito_cirugia_id = '".$this->datosActoQX['ambito_cirugia_id']."' OR ambito_cirugia_id IS NULL)
                AND rango_min < $minutos
                AND (rango_max >= $minutos OR rango_max IS NULL)
                AND sw_minuto_adicional = '1'";


        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_03 - GetLiquidacionDerechoDS - ERROR 02";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if(!$result->EOF)
        {
            $DATOS_CARGO = $result->FetchRow();
            $result->Close();

            $tipo_cargo_qx_id = 'TS';

            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $DATOS_CARGO['ds_cargo'];
            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo_cups'] = $DATOS_CARGO['ds_cups'];
            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cantidad'] = $DATOS_CARGO['cantidad'];

            if(($CargoLiquidado = $this->LiquidarCargoCuenta($cuenta=0 ,
                                                        $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'],
                                                        $DATOS_CARGO['ds_cargo'],
                                                        $DATOS_CARGO['cantidad'],
                                                        $descuento_manual_empresa=0 ,
                                                        $descuento_manual_paciente=0 ,
                                                        $aplicar_descuento_empresa=true ,
                                                        $aplicar_descuento_paciente=true ,
                                                        $precio=NULL,
                                                        $this->GetDatosPlan('plan_id'),
                                                        $Servicio = $this->datosActoQX['servicio_actual'],
                                                        $semanas_cotizacion,
                                                        $tipo_empleador_id=null,
                                                        $empleador=null,
                                                        $tipo_id_paciente=null,
                                                        $paciente_id=null))===false) return false;

            foreach($CargoLiquidado as $k=>$v)
            {
                $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id][$k]=$v;
            }
            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
            $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = 100;
        }

        return true;
    }

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
    * Metodo que retorna un cargo en ceros para ser modificado.
    *
    * @return boolean
    * @access private
    */
    function GetCargoEnCero($tipo_cargo_qx_id)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql= "SELECT * FROM tarifarios_por_tiempo_cargos WHERE tarifario_id = '".$this->datosQX[1][1]['tarifario_id']."';";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS  TipoLiquidacionQX_03 - GetCargoEnCero - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if(!$result->EOF)
        {
            $DATOS_CARGOS = $result->FetchRow();
            $result->Close();

            foreach($this->datosQX as $NumCirujano=>$procedimientos)
            {
                foreach($procedimientos as $NumProcedimiento=>$datos)
                {

                    $profesional=$this->GetTerceroActoQX($tipo_cargo_qx_id,$NumCirujano);

                    $SALIR = false;

                    switch($tipo_cargo_qx_id)
                    {
                        case 'DC':
                            if(!empty($DATOS_CARGOS['dc_cargo']))
                            {
                                if(!empty($DATOS_CARGOS['dg_cargo']))
                                {
                                    $tipo_profesional = '1';

                                    $sql = "SELECT tipo_profesional FROM profesionales WHERE tipo_id_tercero='".$profesional['tipo_id_profesional']."' AND tercero_id='".$profesional['profesional_id']."';";
                                    $result = $dbconn->Execute($sql);
                                    if(!$dbconn->ErrorNo() != 0)
                                    {
                                        if(!$result->EOF)
                                        {
                                            list($tipo_profesional)=$result->FetchRow();
                                            $result->Close();
                                        }

                                        if($tipo_profesional == '1')
                                        {
                                            $cargo      = $DATOS_CARGOS['dc_cargo'];
                                            $cargo_cups = $DATOS_CARGOS['dc_cups'];
                                        }
                                        else
                                        {
                                            $cargo      = $DATOS_CARGOS['dg_cargo'];
                                            $cargo_cups = $DATOS_CARGOS['dg_cups'];
                                        }
                                    }
                                    else
                                    {
                                        $this->error = "CLASS  TipoLiquidacionQX_03 - GetCargoEnCero - ERROR 02";
                                        $this->mensajeDeError = $dbconn->ErrorMsg();
                                        return false;
                                    }
                                }
                                else
                                {
                                    $cargo      = $DATOS_CARGOS['dc_cargo'];
                                    $cargo_cups = $DATOS_CARGOS['dc_cups'];
                                }
                            }
                            else
                            {
                                $SALIR = true;
                            }
                        break;

                        case 'DA':
                            if(!empty($DATOS_CARGOS['da_cargo']))
                            {
                                $cargo      = $DATOS_CARGOS['da_cargo'];
                                $cargo_cups = $DATOS_CARGOS['da_cups'];
                            }
                            else
                            {
                                $SALIR = true;
                            }
                        break;

                        case 'DY':
                            if(!empty($DATOS_CARGOS['dy_cargo']))
                            {
                                $cargo      = $DATOS_CARGOS['dy_cargo'];
                                $cargo_cups = $DATOS_CARGOS['dy_cups'];
                            }
                            else
                            {
                                $SALIR = true;
                            }
                        break;

                        DEFAULT:
                        $SALIR = true;
                    }

                    if($SALIR===false)
                    {
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['tarifario_id'] = $this->datosQX[$NumCirujano][$NumProcedimiento]['tarifario_id'];
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo'] = $cargo;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cargo_cups'] = $cargo_cups;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['cantidad'] = 1;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['precio_plan'] = 0;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_cargo'] = 0;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_cubierto'] = 0;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_no_cubierto'] = 0;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['porcentaje_gravamen'] = 0;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['facturado']= '1';
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['sw_cuota_paciente'] = '0';
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['sw_cuota_moderadora'] = '0';
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_descuento_empresa'] = 0;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['valor_descuento_paciente'] = 0;
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['tipo_id_tercero']= $profesional['tipo_id_profesional'];
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['tercero_id']= $profesional['profesional_id'];
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['SECUENCIA'] = "$NumCirujano-$NumProcedimiento";
                        $this->datosQX[$NumCirujano][$NumProcedimiento]['liquidacion'][$tipo_cargo_qx_id]['PORCENTAJE'] = 100;
                    }
                }
            }
        }

        return true;
    }





}//fin de la clase
?>
