<?php

/**
* $Id: LiquidacionCargosInventario.class.php,v 1.3 2006/01/11 01:26:04 lorena Exp $
*/

/**
* Clase para la liquidacion de Cargos de Insumos y Medicamentos
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.3 $
* @package SIIS
*/
class LiquidacionCargosInventario
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
    * Empresa sobre la que se va realizar la Liquidacion
    *
    * @var string
    * @access private
    */
    var $empresa_id;

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
    function LiquidacionCargosInventario()
    {
        $this->error = '';
        $this->mensajeDeError = '';
        $this->empresa_id = '';
        $this->datosPlan = array();
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
    * Metodo para establecer los datos de la cuenta con la que se va a liquidar
    *
    * @param array $datosPlan vector con los datos del plan
    * @return array
    * @access public
    */
    function SetDatosCuenta($numerodecuenta)
    {
        static $datosCuentas;

        if(empty($numerodecuenta))
        {
            $this->error = "CLASS LiquidacionCargosInventario -  SetDatosCuenta - 001";
            $this->mensajeDeError = "El parametro numerodecuenta es necesario.";
            return false;
        }

        if($datosCuentas[$numerodecuenta])
        {
            if($datosCuentas[$numerodecuenta]['plan_id'] == $this->GetDatosPlan('plan_id'))
            {
                return true;
            }
            else
            {
                if(!$this->SetDatosPlan($datosCuentas[$numerodecuenta]['plan_id']))
                {
                    if(empty($this->error))
                    {
                        $this->error = "CLASS LiquidacionCargosInventario - SetDatosCuenta - R01";
                        $this->mensajeDeError = "Error retornado por el metodo SetDatosPlan()";
                    }
                    return false;
                }
                return true;
            }
        }

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "SELECT plan_id FROM cuentas WHERE numerodecuenta = $numerodecuenta";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "CLASS LiquidacionCargosInventario - SetDatosCuenta - 002";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $this->error = "CLASS LiquidacionCargosInventario - SetDatosCuenta  - 003";
            $this->mensajeDeError = "La cuenta [$numerodecuenta] no existe.";
            return false;
        }

        $datosCuentas[$numerodecuenta] = $resultado->FetchRow();
        $resultado->Close();

        if(!$this->SetDatosPlan($datosCuentas[$numerodecuenta]['plan_id']))
        {
            if(empty($this->error))
            {
                $this->error = "CLASS LiquidacionCargosInventario - SetDatosCuenta - R02";
                $this->mensajeDeError = "Error retornado por el metodo SetDatosPlan()";
            }
            return false;
        }
        return true;
    }


    /**
    * Metodo para establecer los datos del plan con el que se va a liquidar
    *
    * @param array $datosPlan vector con los datos del plan
    * @return array
    * @access public
    */
    function SetDatosPlan($plan_id)
    {
        static $datosPlanes;

        if(empty($plan_id))
        {
            $this->error = "CLASS LiquidacionCargosInventario - SetDatosPlan  - 001";
            $this->mensajeDeError = "El parametro plan_id es necesario.";
            return false;
        }

        if($plan_id == $this->datosPlan['plan_id']) return true;

        if($datosPlanes[$plan_id])
        {
            $this->datosPlan = &$datosPlanes[$plan_id];
            $this->empresa_id = &$this->datosPlan['empresa_id'];
            return true;
        }

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "  SELECT plan_id,
                        empresa_id,
                        tipo_tercero_id,
                        tercero_id,
                        plan_descripcion,
                        tipo_cliente,
                        sw_paragrafados_imd,
                        sw_base_liquidacion_imd,
                        tipo_para_imd,
                        lista_precios,
                        porcentaje_utilidad
                    FROM planes
                    WHERE plan_id = $plan_id;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "CLASS LiquidacionCargosInventario - SetDatosPlan - 002";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $this->error = "CLASS LiquidacionCargosInventario - SetDatosPlan  - 003";
            $this->mensajeDeError = "El plan_id [$plan_id] no existe.";
            return false;
        }

        $datosPlanes[$plan_id] = $resultado->FetchRow();
        $resultado->Close();

        $this->datosPlan = &$datosPlanes[$plan_id];
        $this->empresa_id = &$this->datosPlan['empresa_id'];
        return true;
    }

    /**
    * Metodo para obtener uno o todos los datos del plan con el que se va a liquidar
    *
    * @param array $dato opcional dato que se quiere recuperar
    * @return array
    * @access public
    */
    function GetDatosPlan($dato=null)
    {
        if(empty($this->datosPlan)) return null;

        if($dato)
        {
            return $this->datosPlan[$dato];
        }
        else
        {
            return $this->datosPlan;
        }
    }//fin del metodo

    /**
    * Metodo para borrar el parametro datosPlan
    *
    * @return boolean
    * @access public
    */
    function DelDatosPlan()
    {
        unset($this->datosPlan);
    }//fin del metodo


    /**
    * Metodo para obtener los parametros generales de inventarios de una empresa
    *
    * @param string $empresa_id
    * @return array
    * @access public
    */
    function GetParametrosEmpresa($empresa_id)
    {
        static $datosEmpresas;

        if(empty($empresa_id))
        {
            $this->error = "CLASS LiquidacionCargosInventario - GetParametrosEmpresa - 001";
            $this->mensajeDeError = "Empty empresa_id.";
            return false;
        }

        if($datosEmpresas[$empresa_id]) return $datosEmpresas[$empresa_id];

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "  SELECT sw_base_liquidacion_imd, codigo_lista, porcentaje_utilidad
                    FROM inv_parametros_generales
                    WHERE empresa_id = '$empresa_id';
                 ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "CLASS LiquidacionCargosInventario - GetParametrosEmpresa - 002";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF) return null;

        $datosEmpresas[$empresa_id] = $resultado->FetchRow();
        $resultado->Close();

        return $datosEmpresas[$empresa_id];

    }//fin del metodo


    /**
    * Metodo para obtener uno o todos los datos del plan con el que se va a liquidar
    *
    * @param array $dato opcional dato que se quiere recuperar
    * @return array
    * @access public
    */
    function SetEmpresaID($empresa_id)
    {
        if(!empty($this->datosPlan['empresa_id']))
        {
            if($this->datosPlan['empresa_id'] != $empresa_id)
            {
                $this->DelDatosPlan();
            }
        }

        $this->empresa_id = $empresa_id;

        return true;

    }//fin del metodo

    /**
    * Metodo para obtener el precio de un producto en una lista de precios
    *
    * @param string $producto
    * @param string $lista_precios
    * @return array
    * @access public
    */
    function GetPrecioLista($empresa_id,$producto,$lista_precios)
    {
        static $preciosListas;

        if(empty($empresa_id) || empty($producto) || empty($lista_precios))
        {
            $this->error = "CLASS LiquidacionCargosInventario - GetPrecioLista - 001";
            $this->mensajeDeError = "Empty parametro (empresa_id, producto ? lista_precios).";
            return false;
        }

        if($preciosListas[$empresa_id][$lista_precios][$producto]=='NULL') return null;
        if(is_numeric($preciosListas[$empresa_id][$lista_precios][$producto])) return $preciosListas[$empresa_id][$lista_precios][$producto];

        list($dbconn) = GetDBconn();

        $query = "  SELECT precio
                    FROM listas_precios_detalle
                    WHERE   empresa_id = '$empresa_id'
                            AND codigo_lista = '$lista_precios'
                            AND codigo_producto = '$producto';";

        $resultado = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "CLASS LiquidacionCargosInventario - GetPrecioLista - 002";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $preciosListas[$empresa_id][$lista_precios][$producto] = 'NULL';
            return null;
        }

        list($preciosListas[$empresa_id][$lista_precios][$producto]) = $resultado->FetchRow();

        return $preciosListas[$empresa_id][$lista_precios][$producto];
    }


    /**
    * Metodo para obtener los datos de un producto
    *
    * @param string $producto
    * @param string $empresa_id
    * @return array
    * @access public
    */
    function GetDatosProducto($producto,$empresa_id=NULL,$servicio=NULL)
    {
        static $productos;

        if(empty($empresa_id))
        {
            if(empty($this->empresa_id))
            {
                $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - 001";
                $this->mensajeDeError = "Empty empresa_id.";
                return false;
            }
            $empresa_id = &$this->empresa_id;
        }

        if($productos[$producto][$empresa_id])
        {
            return $productos[$producto][$empresa_id];
        }

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "  SELECT *
                    FROM
                        inventarios_productos a LEFT JOIN medicamentos b ON (a.codigo_producto = b.codigo_medicamento),
                        inventarios c
                    WHERE
                        a.codigo_producto = '$producto'
                        AND c.empresa_id = '$empresa_id'
                        AND c.codigo_producto = a.codigo_producto
                        ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - 002";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF) return NULL;

        $productos[$producto][$empresa_id] = $resultado->FetchRow();

        $resultado->Close();

        $DatosPlan = &$this->GetDatosPlan();
        if($DatosPlan===false)
        {
            if(empty($this->error))
            {
                $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - R01";
                $this->mensajeDeError = "Error retornado por el metodo GetDatosPlan()";
            }
            return false;
        }

        $DatosContratacion = NULL;

        if($DatosPlan)
        {
            if($servicio && ($productos[$producto][$empresa_id]['grupo_contratacion_id']!='0'))
            {
                $query =   "SELECT porcentaje,
                                por_cobertura,
                                sw_descuento,
                                sw_copago,
                                sw_cuota_moderadora,
                                porcentaje_nopos_autorizado,
                                'excepciones_inv_copagos' as contratacion
                            FROM excepciones_inv_copagos
                            WHERE plan_id = ".$DatosPlan['plan_id']."
                            AND empresa_id = '$empresa_id'
                            AND codigo_producto = '$producto'
                            AND servicio = '$servicio'
                           ";

                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $resultado = $dbconn->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - 003E";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                if($resultado->EOF)
                {
                    $resultado->Close();

                    $query =   "SELECT porcentaje,
                                    por_cobertura,
                                    sw_descuento,
                                    sw_copago,
                                    sw_cuota_moderadora,
                                    porcentaje_nopos_autorizado,
                                    'plan_tarifario_inv_copagos' as contratacion
                                FROM plan_tarifario_inv_copagos
                                WHERE plan_id = ".$DatosPlan['plan_id']."
                                AND empresa_id = '$empresa_id'
                                AND grupo_contratacion_id = '".$productos[$producto][$empresa_id]['grupo_contratacion_id']."'
                                AND servicio = '$servicio'
                                ";

                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - 003";
                        $this->mensajeDeError = $dbconn->ErrorMsg();
                        return false;
                    }
                }

                if(!$resultado->EOF)
                {
                    $DatosContratacion = $resultado->FetchRow();
                }
                $resultado->Close();
            }

            $productos[$producto][$empresa_id]['sw_base_liquidacion_imd'] = $DatosPlan['sw_base_liquidacion_imd'];

            switch($DatosPlan['sw_base_liquidacion_imd'])
            {
                case '1': //COSTO PROMEDIO

                    if($DatosContratacion['porcentaje'])
                    {
                        $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['costo'] + ($productos[$producto][$empresa_id]['costo'] * $DatosContratacion['porcentaje'] / 100);
                        $productos[$producto][$empresa_id]['porcentaje_utilidad'] = $DatosContratacion['porcentaje'];
                        $productos[$producto][$empresa_id]['origen_parametrizacion'] = $DatosContratacion['contratacion'];
                    }
                    else
                    {
                        $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['costo'] + ($productos[$producto][$empresa_id]['costo'] * $DatosPlan['porcentaje_utilidad'] / 100);
                        $productos[$producto][$empresa_id]['porcentaje_utilidad'] = $DatosPlan['porcentaje_utilidad'];
                        $productos[$producto][$empresa_id]['origen_parametrizacion'] = 'tabla planes';
                    }

                break;

                case '2': //COSTO ULTIMA COMPRA

                    if($DatosContratacion['porcentaje'])
                    {
                        $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['costo_ultima_compra'] + ($productos[$producto][$empresa_id]['costo_ultima_compra'] * $DatosContratacion['porcentaje'] / 100);
                        $productos[$producto][$empresa_id]['porcentaje_utilidad'] = $DatosContratacion['porcentaje'];
                        $productos[$producto][$empresa_id]['origen_parametrizacion'] = $DatosContratacion['contratacion'];
                    }
                    else
                    {
                        $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['costo_ultima_compra'] + ($productos[$producto][$empresa_id]['costo_ultima_compra'] * $DatosPlan['porcentaje_utilidad'] / 100);
                        $productos[$producto][$empresa_id]['porcentaje_utilidad'] = $DatosPlan['porcentaje_utilidad'];
                        $productos[$producto][$empresa_id]['origen_parametrizacion'] = 'tabla planes';
                    }

                break;

                case '3': //LISTA DE PRECIOS

                    if($DatosPlan['lista_precios']==='0000' || empty($DatosPlan['lista_precios']))
                    {
                        $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['precio_venta'];
                        $productos[$producto][$empresa_id]['lista_de_precios'] = 'LISTA [0000]';
                    }
                    else
                    {
                        $precio = &$this->GetPrecioLista($empresa_id,$producto,$DatosPlan['lista_precios']);
                        if($precio===false)
                        {
                            if(empty($this->error))
                            {
                                $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - R02";
                                $this->mensajeDeError = "Error retornado por el metodo GetPrecioLista()";
                            }
                            return false;
                        }
                        elseif(!$precio)
                        {
                            $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['precio_venta'];
                            $productos[$producto][$empresa_id]['lista_de_precios'] = "LISTA [0000] EXCEPCION A LA LISTA PARAMETRIZADA [".$DatosPlan['lista_precios']."]";
                        }
                        else
                        {
                            $productos[$producto][$empresa_id]['PRECIO'] = $precio;
                            $productos[$producto][$empresa_id]['lista_de_precios'] = $DatosPlan['lista_precios'];
                        }
                    }

                break;

                default:

                    $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - 004";
                    $this->mensajeDeError = "El valor del campo sw_base_liquidacion_imd = [".$DatosPlan['sw_base_liquidacion_imd']."] del plan_id = [".$DatosPlan['plan_id']."] en la tabla planes no es valido.";
                    return false;
            }

            if(is_array($DatosContratacion))
            {
                 $productos[$producto][$empresa_id]['por_cobertura'] =  $DatosContratacion['por_cobertura'];
                 $productos[$producto][$empresa_id]['sw_descuento'] =  $DatosContratacion['sw_descuento'];
                 $productos[$producto][$empresa_id]['sw_copago'] =  $DatosContratacion['sw_copago'];
                 $productos[$producto][$empresa_id]['sw_cuota_moderadora'] =  $DatosContratacion['sw_cuota_moderadora'];
                 $productos[$producto][$empresa_id]['porcentaje_nopos_autorizado'] =  $DatosContratacion['porcentaje_nopos_autorizado'];
            }
						else
						{
									$productos[$producto][$empresa_id]['por_cobertura'] =  0;
									$productos[$producto][$empresa_id]['sw_descuento'] =  0;
									$productos[$producto][$empresa_id]['sw_copago'] =  0;
									$productos[$producto][$empresa_id]['sw_cuota_moderadora'] =  0;
									$productos[$producto][$empresa_id]['porcentaje_nopos_autorizado'] =  0;						
						}

        }
        elseif($DatosEmpresa = &$this->GetParametrosEmpresa($empresa_id))
        {
            switch($DatosEmpresa['sw_base_liquidacion_imd'])
            {
                case '1':

                    $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['costo'] + ($productos[$producto][$empresa_id]['costo'] * $DatosEmpresa['porcentaje_utilidad'] / 100);
                    $productos[$producto][$empresa_id]['porcentaje_utilidad'] = $DatosEmpresa['porcentaje_utilidad'];

                break;

                case '2':

                    $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['costo_ultima_compra'] + ($productos[$producto][$empresa_id]['costo_ultima_compra'] * $DatosEmpresa['porcentaje_utilidad'] / 100);
                    $productos[$producto][$empresa_id]['porcentaje_utilidad'] = $DatosEmpresa['porcentaje_utilidad'];
                break;

                case '3':

                    if($DatosEmpresa['lista_precios']==='0000' || empty($DatosEmpresa['lista_precios']))
                    {
                        $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['precio_venta'];
                        $productos[$producto][$empresa_id]['lista_de_precios'] = 'LISTA [0000]';
                    }
                    else
                    {
                        $precio = &$this->GetPrecioLista($empresa_id,$producto,$DatosEmpresa['codigo_lista']);
                        if($precio===false)
                        {
                            if(empty($this->error))
                            {
                                $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - R03";
                                $this->mensajeDeError = "Error retornado por el metodo GetPrecioLista()";
                            }
                            return false;
                        }
                        elseif(!$precio)
                        {
                            $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['precio_venta'];
                            $productos[$producto][$empresa_id]['lista_de_precios'] = "LISTA [0000] EXCEPCION A LA LISTA PARAMETRIZADA [".$DatosEmpresa['codigo_lista']."]";
                        }
                        else
                        {
                            $productos[$producto][$empresa_id]['PRECIO'] = $precio;
                            $productos[$producto][$empresa_id]['lista_de_precios'] = $DatosEmpresa['codigo_lista'];
                        }
                    }

                break;

                default:

                    $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - 005";
                    $this->mensajeDeError = "El valor del campo sw_base_liquidacion_imd = [".$DatosEmpresa['sw_base_liquidacion_imd']."] de la empresa [$empresa_id] en la tabla inv_parametros_generales no es valido.";
                    return false;
                }
            $productos[$producto][$empresa_id]['sw_base_liquidacion_imd'] = $DatosEmpresa['sw_base_liquidacion_imd'];
            $productos[$producto][$empresa_id]['origen_parametrizacion'] = 'tabla inv_parametros_generales';
            $productos[$producto][$empresa_id]['por_cobertura'] =  0;
            $productos[$producto][$empresa_id]['sw_descuento'] =  0;
            $productos[$producto][$empresa_id]['sw_copago'] =  0;
            $productos[$producto][$empresa_id]['sw_cuota_moderadora'] =  0;
            $productos[$producto][$empresa_id]['porcentaje_nopos_autorizado'] =  0;

        }
        else
        {
            if($DatosEmpresa===false)
            {
                if(empty($this->error))
                {
                    $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - R04";
                    $this->mensajeDeError = "Error retornado por el metodo GetPrecioLista()";
                }
            }
            else
            {
                $this->error = "CLASS LiquidacionCargosInventario - GetDatosProducto - 006";
                $this->mensajeDeError = "El sistema no tiene parametrizada la tabla inv_parametros_generales para la empresa [$empresa_id]";
            }
            return false;
        }

        if($productos[$producto][$empresa_id]['PRECIO'] < $productos[$producto][$empresa_id]['precio_minimo'])
        {
            $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['precio_minimo'];
            $productos[$producto][$empresa_id]['origen_parametrizacion'] = 'precio_minimo';
        }

        if($productos[$producto][$empresa_id]['precio_maximo']>0)
        {
            if($productos[$producto][$empresa_id]['PRECIO'] > $productos[$producto][$empresa_id]['precio_maximo'])
            {
                $productos[$producto][$empresa_id]['PRECIO'] = $productos[$producto][$empresa_id]['precio_maximo'];
                $productos[$producto][$empresa_id]['origen_parametrizacion'] = 'precio_maximo';
            }
        }

        return $productos[$producto][$empresa_id];

    }//fin del metodo

    /**
    * Metodo para obtener la liquidacion de un producto
    *
    * @param string $producto
    * @param string $empresa_id
    * @return array
    * @access public
    */
    function GetLiquidacionProducto($cod_producto, $empresa_id=NULL, $cantidad=1, $datosAdicionales = array())
    {

        if(empty($producto))
        {
            $this->error = "CLASS LiquidacionCargosInventario - GetLiquidacionProducto - 001";
            $this->mensajeDeError = "Empty parametro $cod_producto";
        }

        $OPCIONES['cuenta'] = NULL;
        $OPCIONES['plan_id'] = NULL;
        $OPCIONES['precio'] = NULL;
        $OPCIONES['departamento'] = NULL;
        $OPCIONES['servicio'] = NULL;
        $OPCIONES['evolucion_id'] = NULL;
        $OPCIONES['descuento_manual_empresa'] = 0;
        $OPCIONES['descuento_manual_paciente'] = 0;
        $OPCIONES['aplicar_descuento_empresa'] = TRUE;
        $OPCIONES['aplicar_descuento_paciente'] = TRUE;

        foreach($datosAdicionales AS $k=>$v)
        {
            $OPCIONES[$k] = $v;
        }

        if($OPCIONES['cuenta'])
        {
            if($this->SetDatosCuenta($OPCIONES['cuenta'])===false)
            {
                if(empty($this->error))
                {
                    $this->error = "CLASS LiquidacionCargosInventario - GetLiquidacionProducto - R01";
                    $this->mensajeDeError = "Error retornado por el metodo SetDatosCuenta()";
                }
                return false;
            }
        }
        elseif($OPCIONES['plan_id'])
        {
            if(!$this->SetDatosPlan($OPCIONES['plan_id']))
            {
                if(empty($this->error))
                {
                    $this->error = "CLASS LiquidacionCargosInventario - GetLiquidacionProducto - R02";
                    $this->mensajeDeError = "Error retornado por el metodo SetDatosPlan()";
                }
                return false;
            }
        }
        elseif($empresa_id)
        {
            $this->SetEmpresaID($empresa_id);
        }

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;


        if($OPCIONES['departamento'])
        {
            $query = "  SELECT servicio FROM departamentos WHERE departamento = '".$OPCIONES['departamento']."';";

            $resultado = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "CLASS LiquidacionCargosInventario - GetLiquidacionProducto - 002";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if($resultado->EOF)
            {
                $servicio = NULL;
            }
            else
            {
                list($servicio)=$resultado->FetchRow();
                $resultado->Close();
            }
        }
        elseif($OPCIONES['servicio'])
        {
            $servicio = $OPCIONES['servicio'];
        }

        $producto = $this->GetDatosProducto($cod_producto,$empresa_id=NULL,$servicio);

        if($producto===false)
        {
            if(empty($this->error))
            {
                $this->error = "CLASS LiquidacionCargosInventario - GetLiquidacionProducto - R03";
                $this->mensajeDeError = "Error retornado por el metodo GetDatosProducto()";
            }
            return false;
        }
        if(!is_array($producto))
        {
            $this->error = "CLASS LiquidacionCargosInventario - GetLiquidacionProducto - 003";
            $this->mensajeDeError = "El codigo de producto [$cod_producto] no existe en la empresa [".$this->empresa_id."]";
            return false;
        }

        if($OPCIONES['precio'])
        {
            $valor['precio_plan'] = round($OPCIONES['precio'],GetDigitosRedondeo());
        }
        else
        {
            $valor['precio_plan'] = round($producto['PRECIO'],GetDigitosRedondeo());
        }


        //VALORES LIQUIDACION
        $valor['cantidad'] = $cantidad;
        $valor['valor_cargo'] = round(($valor['precio_plan'] * $cantidad),GetDigitosRedondeo());

        if(($producto['sw_pos']==='0' && $OPCIONES['evolucion_id']))
        {
            list($dbconn) = GetDBconn();
            $sql="  (
                        SELECT    hc_justificaciones_no_pos_hosp as numero_justificacion, 'hosp' as tipo
                        FROM      hc_justificaciones_no_pos_hosp
                        WHERE     evolucion_id=".$OPCIONES['evolucion_id']." AND codigo_producto='".$cod_producto."'
                    )
                    UNION
                    (
                        SELECT    hc_justificaciones_no_pos_amb as numero_justificacion, 'amb' as tipo
                        FROM      hc_justificaciones_no_pos_amb
                        WHERE     evolucion_id=".$OPCIONES['evolucion_id']." AND codigo_producto='".$cod_producto."'
                    )";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resultado = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "CLASS LiquidacionCargosInventario - GetLiquidacionProducto - 004";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if(!$resultado->EOF)
            {
                $producto['por_cobertura'] = $producto['porcentaje_nopos_autorizado'];
                $valor['porcentaje_nopos_autorizado']= 'OK';
            }

            $resultado->Close();

        }

        $valor['valor_cubierto'] = round(($valor['valor_cargo'] * $producto['por_cobertura'] /100),GetDigitosRedondeo());
        $valor['valor_nocubierto'] = round(($valor['valor_cargo'] - $valor['valor_cubierto']),GetDigitosRedondeo());
        $valor['porcentaje_gravamen'] = $producto['porc_iva'];
        $valor['descripcion'] = $producto['descripcion'];
        $valor['codigo_producto'] = $producto['codigo_producto'];

        //PARAMETROS DE LA LIQUIDACION (INVENTERIOS) - PARA MONITOREAR LA LIQUIDACION
        $valor['inventarios_origen_parametrizacion'] = $producto['origen_parametrizacion'];
        $valor['inventarios_costo'] = $producto['costo'];


        switch($producto['sw_base_liquidacion_imd'])
        {
            case '1':
                $valor['inventarios_sw_base_liquidacion_imd'] = '1 : COSTO PROMEDIO';
                $valor['inventarios_porcentaje_utilidad'] = $producto['porcentaje_utilidad'];
            break;
            case '2':
                $valor['inventarios_sw_base_liquidacion_imd'] = '2 : COSTO ULTIMA COMPRA';
                $valor['inventarios_porcentaje_utilidad'] = $producto['porcentaje_utilidad'];
                $valor['inventarios_costo_ultima_compra'] = $producto['costo_ultima_compra'];
            break;
            case '3':
                $valor['inventarios_sw_base_liquidacion_imd'] = '3 : LISTA DE PRECIOS';
                $valor['inventarios_lista_de_precios'] = $producto['lista_de_precios'];
            break;
            default:
                $valor['inventarios_sw_base_liquidacion_imd'] = 'INDETERMINADO';
        }


        //Por defecto inicializo algunos valores
        $valor['facturado']=1;

        if(!$producto['sw_descuento'])
        {
            $valor['valor_descuento_empresa']=0;
            $valor['valor_descuento_paciente']=0;
            $valor['porcentaje_descuento_empresa']=0;
            $valor['porcentaje_descuento_paciente']=0;
        }
        else //pendiente aplicacion de descuentos en inventarios
        {
            $valor['valor_descuento_empresa']=0;
            $valor['valor_descuento_paciente']=0;
            $valor['porcentaje_descuento_empresa']=0;
            $valor['porcentaje_descuento_paciente']=0;
        }


        // DE LA CONTRATACION
        $valor['sw_cuota_paciente'] = $producto['sw_copago'];
        $valor['sw_cuota_moderadora'] = $producto['sw_cuota_moderadora'];
        $valor['sw_paragrafados_imd'] = '0';
        $valor['tipo_para_imd'] = '0';
        $valor['sw_descuento'] = '0';


        //PARAGRAFADOS POR DEPARTAMENTO
        if($OPCIONES['departamento'])
        {
            if($this->GetDatosPlan('sw_paragrafados_imd')=='1')
            {
                if($this->GetDatosPlan('tipo_para_imd')==0)
                {
                    $query="SELECT COUNT(*)
                            FROM planes_paragrafados_medicamentos
                            WHERE plan_id = ".$this->GetDatosPlan('plan_id')."
                            AND codigo_producto = '$cod_producto'
                            AND departamento = '".$OPCIONES['departamento']."';";
                }
                if($this->GetDatosPlan('tipo_para_imd')>0)
                {
                    $query="SELECT COUNT(*)
                            FROM tipos_paragrafados_imd_detalle
                            WHERE tipo_para_imd = ".$this->GetDatosPlan('tipo_para_imd')."
                            AND codigo_producto = '$cod_producto'
                            AND departamento = '".$OPCIONES['departamento']."';";
                }

                $resultado = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "CLASS LiquidacionCargosInventario - GetLiquidacionProducto - 005";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                list($cargo_paragrafado) = $resultado->FetchRow();
                $resultado->Close();
            }
            //retorno 0 si el cargo es un paragrafado, retorno 1 si el campo se debe facturar (No paragrafado)
            if($cargo_paragrafado)
            {
                $valor['facturado']=0;
            }
        }


        return $valor;
    }

}//fin de la clase

?>


