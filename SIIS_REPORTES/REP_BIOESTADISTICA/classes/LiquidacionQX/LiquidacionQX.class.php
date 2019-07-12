<?php

/**
* $Id: LiquidacionQX.class.php,v 1.13 2006/08/18 14:48:10 alex Exp $
*/

/**
* Clase para la liquidacion de Procedimientos QX
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.13 $
* @package SIIS
*/
class LiquidacionQX
{
    //------------------
    // PARAMETROS
    //------------------

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
    * Almacena los cargos de la liquidacion
    *
    * @var array
    * @access private
    */
    var $datosQX=array();

   /**
    * Tipo de procedimiento a liquidar
    *
    * (unico,bilateral,multiple etc...)
    *
    * @var integer
    * @access private
    */
    var $tipoProcedimiento=0;

   /**
    * Numero de ID de la liquidacion
    *
    * @var integer
    * @access private
    */
    var $LiquidacionId=0;

   /**
    * Tipo de Liquidacion
    *
    * Liquidacion = FALSE
    * Presupuesto = TRUE
    *
    * @var array
    * @access private
    */
    var $sw_presupuesto=false;

   /**
    * Tarifario de los cragos a liquidar
    *
    * @var string
    * @access private
    */
    var $tarifario_id;

   /**
    * Datos de la liquidacion
    *
    * @var array
    * @access private
    */
    var $datosActoQX=array();

   /**
    * Datos del plan(contrato) sobre la que se esta liquidando
    *
    * @var array
    * @access private
    */
    var $datosPlan=array();


    //------------------
    // METODOS
    //------------------

   /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function LiquidacionQX()
    {
        $this->error='';
        $this->mensajeDeError='';
        $this->datosQX=array();
        $this->tipoProcedimiento=0;
        $this->datosActoQX=array();
        $this->datosPlan=array();
        return true;
    }//fin del metodo

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
    * Metodo para establecer la informacion del actoQX a liquidar
    *
    * @param integer $LiquidacionId Numero de liquidacion de ActoQX a realizar
    * @param boolean $sw_presupuesto Indica si es una liquidacion=false o un Presupuesto=true
    * @return boolean
    * @access public
    */
    function SetDatosLiquidacion($LiquidacionId, $sw_presupuesto=false)
    {

        $this->LiquidacionId  = $LiquidacionId;
        $this->sw_presupuesto = $sw_presupuesto;

        if(!$PresupuestoQX)
        {
            if($this->SetDatosActoQx($LiquidacionId)===false)
            {
                if(!$this->error)
                {
                    $this->error = "CLASS LiquidacionQX - SetDatosLiquidacion - ERROR 01";
                    $this->mensajeDeError = "No se pudieron establecer los datos para la liquidacion";
                }
                return false;
            }
        }
        else
        {
            if($this->SetDatosPresupuestQx($PresupuestoQX)===false)
            {
                if(!$this->error)
                {
                    $this->error = "CLASS LiquidacionQX - SetDatosLiquidacion - ERROR 02";
                    $this->mensajeDeError = "No se pudieron establecer los datos para la liquidacion";
                }
                return false;
            }
        }

        return true;

    }//fin del metodo



    /**
    * Metodo para establecer los datos para liquidar un acto QX
    *
    * @return boolean
    * @access private
    */
    function SetDatosActoQx()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql = "SELECT * FROM cuentas_liquidaciones_qx
                WHERE cuenta_liquidacion_qx_id=".$this->LiquidacionId.";";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 02";
            $this->mensajeDeError = "No existe la liquidacion No." . $this->LiquidacionId;
            return false;
        }

        $this->datosActoQX = $result->FetchRow();
        $result->Close();

        if(empty($this->datosActoQX['numerodecuenta']))
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 03";
            $this->mensajeDeError = "El acto quirurgico no presenta un numero de cuenta para hacer la liquidacion.";
            return false;
        }

        $sql = "SELECT plan_id,tipo_afiliado_id,semanas_cotizadas,rango,ingreso FROM cuentas
                WHERE numerodecuenta=" . $this->datosActoQX['numerodecuenta'];

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 04";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 05";
            $this->mensajeDeError = "No existe la Cuenta No." . $this->datosActoQX['numerodecuenta'];
            return false;
        }

        $this->datosPlan = $result->FetchRow();
        $result->Close();


        $sql = "SELECT a.departamento_actual, b.servicio FROM ingresos a, departamentos b
                WHERE a.ingreso=" . $this->datosPlan['ingreso'] ."
                AND b.departamento = a.departamento_actual";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 04A";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 05A";
            $this->mensajeDeError = "No existe el Ingreso No." . $this->datosPlan['ingreso'];
            return false;
        }

        list($this->datosActoQX['departamento_actual'],$this->datosActoQX['servicio_actual']) = $result->FetchRow();
        $result->Close();

        $sql = "SELECT * FROM cuentas_liquidaciones_qx_procedimientos a, cuentas_liquidaciones_qx_procedimientos_cargos b
                WHERE a.cuenta_liquidacion_qx_id = " . $this->datosActoQX['cuenta_liquidacion_qx_id'] . "
                AND b.consecutivo_procedimiento = a.consecutivo_procedimiento";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 06";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 07";
            $this->mensajeDeError = "No hay procedimientos para liquidar.";
            return false;
        }

        $datosqx_bd=$result->GetRows();
        $result->Close();

        $NumEspecialista=1;
        $Especialistas=array();
        $tarifarios=array();

        foreach($datosqx_bd as $k=>$v)
        {
            if(!isset($Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]))
            {
                $Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]=$NumEspecialista;
                $NumEspecialista++;
                $I=1;
            }

            $tarifarios[$v['tarifario_id']]=$v['tarifario_id'];

            $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]['tipo_id_cirujano']=$v['tipo_id_cirujano'];
            $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]['cirujano_id']=$v['cirujano_id'];
            $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]['consecutivo_procedimiento']=$v['consecutivo_procedimiento'];
            $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]['cargo_cups']=$v['cargo_cups'];
            $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]['autorizacion_int']=$v['autorizacion_int'];
            $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]['autorizacion_ext']=$v['autorizacion_ext'];
            $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]['tarifario_id']=$v['tarifario_id'];
            $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]['cargo']=$v['cargo'];
            $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]['sw_bilateral']=$v['sw_bilateral'];

            if($v['sw_bilateral']==1)
            {
                $I++;
                $this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I]=$this->datosQX[$Especialistas[$v['tipo_id_cirujano']][$v['cirujano_id']]][$I-1];
            }
            $I++;
        }
        unset($datosqx_bd);
        unset($Especialistas);

        if(sizeof($tarifarios)!=1)
        {
            $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 08";
            $this->mensajeDeError = "Los cargos a liquidar son de distintos tarifarios.";
            return false;
        }

        $this->tarifario_id=key($tarifarios);
        unset($tarifarios);

        if($this->SetTipoProcedimiento()===false)
        {
            if(!$this->error)
            {
                $this->error = "CLASS LiquidacionQX - SetDatosActoQx - ERROR 09";
                $this->mensajeDeError = "Error al configurar el tipo de procedimiento a liquidar.";
            }
            return false;
        }

        $this->datosActoQX['Tipo_Procedimiento']=$this->GetTipoProcedimiento();

        return true;

    }//fin del metodo


    /**
    * Metodo para establecer los datos para liquidar un presupuesto
    *
    * @return boolean
    * @access private
    */
    function SetDatosPresupuestQx()
    {
        return true;
    }//fin del metodo


     /**
    * Metodo que retorna un vector con la liquidacion de los cargos de un actoQX
    *
    * @return array
    * @access public
    */
    function GetLiquidacion()
    {
        list($dbconn) = GetDBconn();

        $sql = "SELECT tipo_tarifario_id FROM tarifarios WHERE tarifario_id='".$this->tarifario_id."';";
        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionQX - GetLiquidacion ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "CLASS LiquidacionQX - GetLiquidacion ERROR 02";
            $this->mensajeDeError = "No se pudo obtener el tipo de tarifario";
            return false;
        }

        list($tipo_tarifario) = $result->FetchRow();
        $result->Close();

        $tiposDeCargo['DC']=$this->datosActoQX['sw_derechos_cirujano'];
        $tiposDeCargo['DA']=$this->datosActoQX['sw_derechos_anestesiologo'];
        $tiposDeCargo['DY']=$this->datosActoQX['sw_derechos_ayudante'];
        $tiposDeCargo['DS']=$this->datosActoQX['sw_derechos_sala'];


       $sql = "SELECT COUNT(*) FROM tarifarios_planes_materiales_al_consumo a, cuentas b
                WHERE   b.numerodecuenta   = " . $this->datosActoQX['numerodecuenta'] . "
                        AND a.tarifario_id = '" . $this->tarifario_id . "'
                        AND a.plan_id = b.plan_id;";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionQX - GetLiquidacion ERROR 01-A";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        list($DMalConsumo) = $result->FetchRow();
        $result->Close();

        if($DMalConsumo)
        {
            $tiposDeCargo['DM'] = '0';
        }
        else
        {
            $tiposDeCargo['DM'] = '1';
        }

        //$tiposDeCargo['DM']=$this->datosActoQX['sw_derechos_materiales'];

        if(!IncludeClass("LiquidacionCargos"))
        {
            $this->error = "CLASS LiquidacionQX - GetLiquidacion ERROR 03";
            $this->mensajeDeError = "No se pudo incluir la clase de Liquidacion de Cargos";
            return false;
        }

        if(!IncludeClass($tipo_tarifario,"LiquidacionQX/TiposTarifarios"))
        {
            $this->error = "CLASS LiquidacionQX - GetLiquidacion - ERROR 04";
            $this->mensajeDeError = "No se pudo incluir la clase de Liquidacion ".$tipo_tarifario;
            return false;
        }

        $class_name = 'TipoLiquidacionQX_'.$tipo_tarifario;
        if(!class_exists($class_name))
        {
            $this->error = "CLASS LiquidacionQX - GetLiquidacion ERROR 05";
            $this->mensajeDeError = "No se pudo Instanciar la clase de Liquidacion ".$tipo_tarifario;
            return false;
        }
        $obj=new $class_name();
        if(($obj->LiquidarProcedimientos($this->tarifario_id, &$this->datosQX, $this->datosPlan, $tiposDeCargo, $this->datosActoQX))===false)
        {
            $this->error = $obj->Err();
            $this->mensajeDeError = $obj->ErrMsg();
            return false;
        }
        unset($obj);

        return $this->datosQX;

    }//fin metodo



    /**
    * Metodo para liquidar los equipos que se utilizaron
    *
    * @return array
    * @access private
    */
    function GetLiquidacionEquiposQX()
    {
        if(empty($this->datosActoQX))
        {
            $this->error = "CLASS LiquidacionQX - GetLiquidacionEquiposQX - ERROR 01";
            $this->mensajeDeError = "DatosQx no Inicializado.";
            return false;
        }

        if(!$this->datosActoQX['sw_equipos_medicos']) return true;

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "
                (
                    SELECT  'fijo' as tipo_equipo, b.equipo_id, b.descripcion, c.cargo_cups, a.duracion as cantidad
                    FROM    cuentas_liquidaciones_qx_equipos_fijos a,
                            qx_equipos_quirofanos b,
                            qx_tipo_equipo_fijo c
                    WHERE
                    a.cuenta_liquidacion_qx_id = " .$this->datosActoQX['cuenta_liquidacion_qx_id'] . "
                    AND a.equipo_id = b.equipo_id
                    AND c.tipo_equipo_fijo_id = b.tipo_equipo_fijo_id
                    AND c.cargo_cups IS NOT NULL
                )
                UNION ALL
                (
                    SELECT 'movil' as tipo_equipo, b.equipo_id, b.descripcion, c.cargo_cups, a.duracion as cantidad
                    FROM    cuentas_liquidaciones_qx_equipos_moviles a,
                            qx_equipos_moviles b,
                            qx_tipo_equipo_movil c
                    WHERE
                    a.cuenta_liquidacion_qx_id = " .$this->datosActoQX['cuenta_liquidacion_qx_id'] . "
                    AND a.equipo_id = b.equipo_id
                    AND c.tipo_equipo_id = b.tipo_equipo_id
                    AND c.cargo_cups IS NOT NULL
                )
                ;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS LiquidacionQX - LiquidarEquiposQX - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF) return true;

        $obj = new LiquidacionCargos;

        $LiqEquipos=array();
        $I=0;
        $Servicio = $this->datosActoQX['servicio_actual'];
        if(empty($Servicio)) $Servicio='3';

        while($Fila = $result->FetchRow())
        {
            $cargos_contratados_plan = $obj->GetCargoplan($Fila['cargo_cups'],$this->datosPlan['plan_id']);

            switch(count($cargos_contratados_plan))
            {
                case 1:

                    $FilaEquivalente = current($cargos_contratados_plan);

                    if(($CargoLiquidado = $obj->LiquidarCargoCuenta($cuenta=0 ,
                                                $FilaEquivalente['tarifario_id'],
                                                $FilaEquivalente['cargo'],
                                                $Fila['cantidad'],
                                                $descuento_manual_empresa=0 ,
                                                $descuento_manual_paciente=0 ,
                                                $aplicar_descuento_empresa=true ,
                                                $aplicar_descuento_paciente=true ,
                                                $precio=NULL,
                                                $FilaEquivalente['plan_id'],
                                                $Servicio,
                                                $semanas_cotizacion,
                                                $tipo_empleador_id=null,
                                                $empleador=null,
                                                $tipo_id_paciente=null,
                                                $paciente_id=null,
                                                $tipoUninadTiempo='M'))===false)
                    {
                        $this->error = $obj->Err();
                        $this->mensajeDeError = $obj->ErrMsg();
                        return false;
                    }

                    $CargoLiquidado['tipo_equipo']=$Fila['tipo_equipo'];
                    $CargoLiquidado['equipo_id']=$Fila['equipo_id'];
                    $CargoLiquidado['descripcion_equipo']=$Fila['descripcion'];
                    $CargoLiquidado['duracion']=$Fila['cantidad'];
                    $CargoLiquidado['cargo_cups']=$Fila['cargo_cups'];

                    $LiqEquipos[] = $CargoLiquidado;

                break;
                default:
            }
        }
        return $LiqEquipos;
    }






   /**
    * Metodo que retorna el tipo de procedimiento establecido(unico,bilateral,multiple etc...)
    *
    * @param integer
    * @return boolean
    * @access private
    */
    function GetTipoProcedimiento()
    {
        if(empty($this->tipoProcedimiento))
        {
            if($this->SetTipoProcedimiento()===false)
            {
                return false;
            }
        }
        return $this->tipoProcedimiento;
    }//fin del metodo


   /**
    * Metodo que retorna la descripcion del tipo de procedimiento establecido
    *
    * @param integer
    * @return boolean
    * @access private
    */
    function GetDetalleTipoProcedimiento($TipoProcedimiento)
    {
        if(empty($TipoProcedimiento)) $TipoProcedimiento = $this->tipoProcedimiento;

        static $Detalle = array(1=>'1. CIRUGIA UNICA',
                                2=>'2. MULTIPLE, MISMA VIA, DIFERENTE ESPECIALIDAD',
                                3=>'3. MULTIPLE, MISMA VIA, IGUAL ESPECIALIDAD',
                                4=>'4. MULTIPLE, DIFERENTE VIA, DIFERENTE ESPECIALIDAD',
                                5=>'5. MULTIPLE, DIFERENTE VIA, IGUAL ESPECIALIDAD',
                                6=>'6. BILATERAL',
                                7=>'7. POLITRAUMA, MISMA VIA, IGUAL ESPECIALIDAD',
                                8=>'8. POLITRAUMA, DIFERENTE VIA, IGUAL ESPECIALIDAD',
                                9=>'9. POLITRAUMA, MISMA VIA, DIFERENTE ESPECIALIDAD',
                                10=>'10. POLITRAUMA, DIFERENTE VIA, DIFERENTE ESPECIALIDAD'
                                );

        return $Detalle[$TipoProcedimiento];

    }//fin del metodo


   /**
    * Metodo que establece el tipo de procedimiento (unico,bilateral,multiple etc...)
    *
    * @return boolean
    * @access private
    */
    function SetTipoProcedimiento()
    {
        //SI MAS DE UNA ESPECIALIDAD
        if(count($this->datosQX)>1)
        {
            //SI ES UN POLITRAUMATISMO
            if($this->datosActoQX['sw_politrauma'])
            {
                //SI EL POLITRAUMATISMO ES POR LA MISMA VIA (DIFERENTE ESPECIALIDAD)
                if($this->datosActoQX['via_acceso']==2 || $this->datosActoQX['via_acceso']==9) // FALTA CUANDO ES 9 O 10
                {
                    $this->tipoProcedimiento=9;
                }
                else //SI EL POLITRAUMATISMO ES POR DIFERENTE VIA (DIFERENTE ESPECIALIDAD)
                {
                    $this->tipoProcedimiento=10;
                }
            }
            else // SI NO ES UN POLITRAUMATISMO
            {
                // SI ES POR LA MISMA VIA (DIFERENTE ESPECIALIDAD)
                if($this->datosActoQX['via_acceso']==2)
                {
                    $this->tipoProcedimiento=2;
                }
                else // SI ES POR DIFERENTE VIA (DIFERENTE ESPECIALIDAD)
                {
                    $this->tipoProcedimiento=4;
                }
            }
        }
        //SI ES POR LA MISMA ESPECIALIDAD
        elseif(count($this->datosQX)==1)
        {
            // SI ES UN UNICO PROCEDIMIENTO
            if(count($this->datosQX[1])==1)
            {
                $this->tipoProcedimiento=1;
            }
            elseif(count($this->datosQX[1])==2)
            {
                // SI SON DOS PROCEDIMIENTOS Y SON IGUALES ES BILATERAL
                if(count($diferencias = array_diff_assoc ($this->datosQX[1][1], $this->datosQX[1][2]))==0)
                {
                    $this->tipoProcedimiento=6;
                }
                else // EN CASO DE QUE NO SEAN BILATERALES EXAMINA SI SON DE POLITRAUMA O EL TIPO DE VIA
                {
                    if($this->datosActoQX['sw_politrauma'])
                    {
                        if($this->datosActoQX['via_acceso']==3)
                        {
                            $this->tipoProcedimiento=3;
                        }
                        else
                        {
                            $this->tipoProcedimiento=5;
                        }
                    }
                    elseif($this->datosActoQX['via_acceso']==3)
                    {
                        $this->tipoProcedimiento=3;
                    }
                    else
                    {
                        $this->tipoProcedimiento=5;
                    }
                }
            }
            elseif(count($this->datosQX[1])>1)
            {
                if($this->datosActoQX['sw_politrauma'])
                {
                    if($this->datosActoQX['via_acceso']==3)
                    {
                        $this->tipoProcedimiento=3;
                    }
                    else
                    {
                        $this->tipoProcedimiento=5;
                    }
                }
                elseif($this->datosActoQX['via_acceso']==3)
                {
                    $this->tipoProcedimiento=3;
                }
                else
                {
                    $this->tipoProcedimiento=5;
                }
            }
            else
            {
                $this->error = "CLASS  LiquidacionQX - SetTipoProcedimientos - ERROR 01";
                $this->mensajeDeError = "No se encontraron procedimientos para liquidar.";
                return false;
            }

        }
        else
        {
            $this->error = "CLASS  LiquidacionQX - SetTipoProcedimientos - ERROR 02";
            $this->mensajeDeError = "No se encontraron procedimientos para liquidar.";
            return false;
        }

        if(($this->tipoProcedimiento < 1) || ($this->tipoProcedimiento > 10))
        {
            $this->error = "CLASS  LiquidacionQX - SetTipoProcedimientos - ERROR 03";
            $this->mensajeDeError = "Tipo de Procedimiento fuera de rango (1-10)";
            return false;
        }

        return true;
    }//fin del metodo

}//fin de la clase

?>
