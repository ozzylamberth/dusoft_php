<?php

/**
* $Id: InterfaseCG1.class.php,v 1.11 2005/09/15 16:08:11 darling Exp $
*/

/**
* Clase para la generación de la interfase con CG-UNO
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.11 $
* @package SIIS
*/
class InterfaseCG1
{

    /**
    * Identificador de archivo
    *
    * @var integer
    * @access private
    */
    var $archivo;

    /**
    * Nombre del archivo CGBATCH1
    *
    * @var string
    * @access private
    */
    var $nameFileCGBATCH1='CGBATCH1';

    /**
    * Nombre del archivo CGBATCH2
    *
    * @var string
    * @access private
    */
    var $nameFileCGBATCH2='CGBATCH2';

    /**
    * Nombre del archivo de descarga (tar.gz)
    *
    * @var string
    * @access private
    */
    var $nameFileCompress='';

    /**
    * Documento Contable
    *
    * @var array
    * @access private
    */
    var $documento_id=array();

    /**
    * Vector con los datos de los terceros (beneficiarios)
    *
    * @var array
    * @access private
    */
    var $terceros=array();

    /**
    * Año del lapso contable
    *
    * @var string
    * @access private
    */
    var $lapsoAnho='';

    /**
    * Mes del lapso contable
    *
    * @var string
    * @access private
    */
    var $lapsoMes='';

    /**
    * FechaInicial
    *
    * @var string
    * @access private
    */
    var $fechaInicial='';

    /**
    * FechaFinal
    *
    * @var string
    * @access private
    */
    var $fechaFinal='';

    /**
    * Codigo de error
    *
    * @var string
    * @access private
    */
    var $error='';

    /**
    * Mensaje de error
    *
    * @var string
    * @access private
    */
    var $mensajeDeError='';

    /**
    * Mensaje de error
    *
    * @var string
    * @access private
    */
    var $codigosError=array();

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function InterfaseCG1()
    {
        $this->error='';
        $this->mensajeDeError='';
        $this->documento_id=null;

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql="SELECT cg_contabilizacion_estado_id,descripcion FROM cg_contabilizacion_estados ORDER BY cg_contabilizacion_estado_id";
        $result = $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));
        while($fila=$result->FetchRow())
        {
            $this->codigosError[$fila[0]]=$fila[1];
        }
        $result->Close();
        return true;
    }

    /**
    * Retorna Titulo o codigo de error
    *
    * @return string
    * @access public
    */
    function GetDetalleError($codigo)
    {
        return $this->codigosError[$codigo];
    }

    /**
    * Retorna Titulo o codigo de error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }

    /**
    * Retorna mensaje de error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }

    /**
    * Retorna la fecha inicial
    *
    * @return string
    * @access public
    */
    function getFechaInicial()
    {
        return $this->fechaInicial;
    }

    /**
    * Retorna la fecha final
    *
    * @return string
    * @access public
    */
    function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
    * Establecer el lapso contable para la generacion de la interfase
    *
    * @param integer $anho
    * @param integer $mes
    * @param integer $diaInicial - opcional
    * @param integer $diaFinal - opcional
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function SetLapsoContable($anho,$mes,$diaInicial=null,$diaFinal=null)
    {

        if(!(is_numeric($anho) && $anho > 1900 && $anho < 3000))
        {
            return false;
        }

        if(!(is_numeric($mes) && $mes >= 1 && $mes <= 12))
        {
            return false;
        }

        $this->fechaFinal = '';

        if(is_numeric($diaInicial) && $diaInicial >= 1 && $diaInicial <= 31)
        {
            if(checkdate( $mes, $diaInicial, $anho ))
            {
                $this->lapsoAnho = date("Y",mktime(0,0,0,$mes,$diaInicial,$anho));
                $this->lapsoMes  = date("m",mktime(0,0,0,$mes,$diaInicial,$anho));
                $this->fechaInicial = date("Y-m-d",mktime(0,0,0,$mes,$diaInicial,$anho));

                if(is_numeric($diaFinal) &&  $diaFinal>= 1 &&  $diaFinal<= 31)
                {
                    if(checkdate( $mes, $diaFinal, $anho ))
                    {
                        if(mktime(0,0,0,$mes,$diaInicial,$anho)<=mktime(0,0,0,$mes,$diaFinal,$anho))
                        {
                            $this->fechaFinal = date("Y-m-d",mktime(0,0,0,$mes,$diaFinal,$anho));
                        }
                    }
                    else
                    {
                        if($diaFinal=29 || $diaFinal=30 || $diaFinal=31)
                        {
                            $this->fechaFinal = date("Y-m-d",mktime(0,0,0,$mes+1,0,$anho));
                        }
                    }
                }
                if(empty($this->fechaFinal))
                {
                    $this->fechaFinal=$this->fechaInicial;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            if(checkdate( $mes, 1, $anho ))
            {
                $this->lapsoAnho = date("Y",mktime(0,0,0,$mes,1,$anho));
                $this->lapsoMes  = date("m",mktime(0,0,0,$mes,1,$anho));
                $this->fechaInicial = date("Y-m-d",mktime(0,0,0,$mes,1,$anho));
                $this->fechaFinal   = date("Y-m-d",mktime(0,0,0,$mes+1,0,$anho));
            }
            else
            {
                return false;
            }
        }

        return true;
    }

    /**
    * Establecer el tipo de documento al que se le va generar la interfase
    *
    * @param $empresa_id
    * @param $documento_id
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function setTipoDeDocumento($empresa_id,$documento_id)
    {
        $this->documento_id=null;

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql="SELECT b.documento_id, b.empresa_id, b.descripcion, b.prefijo, b.sw_estado,
                b.numero_digitos, b.texto1, b.texto2, b.texto3, b.mensaje,
                c.tipo_doc_general_id, c.descripcion as descripcion_doc_general, c.tipo_movimiento_id
                FROM interfase_cguno_documentos a, documentos b, tipos_doc_generales c
                    WHERE a.empresa_id='$empresa_id'
                    AND a.documento_id=$documento_id
                    AND a.documento_id=b.documento_id
                    AND a.empresa_id=b.empresa_id
                    AND b.sw_contabiliza='1'
                    AND c.tipo_doc_general_id=b.tipo_doc_general_id
                    ;";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));
        if($result->EOF)
        {
            return false;
        }
        $this->documento_id=$result->FetchRow();
        $result->Close();
        return true;
    }


    /**
    * Obtener datos del tipo de documento configurado para la interfase
    *
    * @return array datos del tipo de documento configurado
    * @access public
    */
    function getTipoDeDocumento()
    {
        if(!empty($this->documento_id))
        {
            return $this->documento_id;
        }
        else
        {
            return false;
        }
    }
    /**
    * Obtener los tipos de documentos disponibles para la interfase
    *
    * @param $empresa_id
    * @return array Tipos de Documentos disponibles para la interfase
    * @access public
    */
    function getTiposDeDocumentos($empresa_id)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="SELECT b.documento_id, b.empresa_id, b.descripcion, b.prefijo, b.sw_estado,
                b.numero_digitos, b.texto1, b.texto2, b.texto3, b.mensaje,
                c.tipo_doc_general_id, c.descripcion as descripcion_doc_general, c.tipo_movimiento_id
                FROM interfase_cguno_documentos a, documentos b, tipos_doc_generales c
                    WHERE a.empresa_id='$empresa_id'
                    AND a.documento_id=b.documento_id
                    AND a.empresa_id=b.empresa_id
                    AND b.sw_contabiliza='1'
                    AND c.tipo_doc_general_id=b.tipo_doc_general_id
                    ;";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));
        $filas=$result->GetRows();
        $result->Close();
        return $filas;
    }

    /**
    * Obtener arreglo con informacion de cada uno de los cargos
    *
    * @return array
    * @access public
    */
    function GetDocumentos($numero,$pasoInterfaz)
    {
        $this->error='';
        $this->mensajeDeError='';

        if(empty($this->fechaInicial) || empty($this->fechaFinal) ||empty($this->documento_id))
        {
            $this->error='Interfase sin configurar';
            $this->mensajeDeError='Inicialize los metodos SetLapsoContable($anho,$mes,$diaInicial=null,$diaFinal=null) y setTipoDeDocumento($empresa_id,$documento_id)';
            return false;
        }

        if(empty($this->documento_id['tipo_movimiento_id']))
        {
            $this->error='TIPO DE DOCUMENTO NO IDENTIFICADO';
            $this->mensajeDeError='No esta parametrizado el tipo tipo_movimiento_id para el tipo de documento general.';
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        switch($this->documento_id['tipo_movimiento_id'])
        {
            case 'FV':
                if($numero)
                {
                    $filtro="AND a.factura_fiscal=$numero";
                }
                else
                {
                    $filtro="";
                }
								//solo traer todos los registros asi hayan pasado
								if(empty($pasoInterfaz))
								{  $filtroP=" AND (e.tipo_bloqueo_id ISNULL OR e.tipo_bloqueo_id<>'04')";  }
								else
								{  $filtroP='';  }
								 
										$sql = "SELECT a.*,b.cg_contabilizacion_estado_id, e.tipo_bloqueo_id
														FROM fac_facturas a LEFT JOIN cg_temp_contabilizacion_facturas b ON (a.empresa_id=b.empresa_id AND a.prefijo=b.prefijo AND a.factura_fiscal=b.numero) 
														LEFT JOIN cg_movimientos_contables e ON (a.documento_contable_id=e.documento_contable_id)
														WHERE date_trunc('day',a.fecha_registro)>='".$this->fechaInicial."' 
														AND date_trunc('day',a.fecha_registro)<='".$this->fechaFinal."' 
														AND a.empresa_id='".$this->documento_id['empresa_id']."' 
														AND a.prefijo='".$this->documento_id['prefijo']."' 
														AND a.documento_id='".$this->documento_id['documento_id']."' 
														$filtro $filtroP
														ORDER BY factura_fiscal"; 								
								
         /*       $sql="SELECT a.*,b.cg_contabilizacion_estado_id 
												FROM fac_facturas a LEFT JOIN cg_temp_contabilizacion_facturas b ON (a.empresa_id=b.empresa_id AND a.prefijo=b.prefijo AND a.factura_fiscal=b.numero)
                        WHERE date_trunc('day',a.fecha_registro)>='".$this->fechaInicial."'
                        AND date_trunc('day',a.fecha_registro)<='".$this->fechaFinal."'
                        AND a.empresa_id='".$this->documento_id['empresa_id']."'
                        AND a.prefijo='".$this->documento_id['prefijo']."'
                        AND a.documento_id='".$this->documento_id['documento_id']."'
                        $filtro
                        ORDER BY factura_fiscal;";*/
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));

                if($result->EOF)
                {
                    $this->error='NO HAY DOCUMENTOS PARA LA INTERFASE';
                    $this->mensajeDeError='No existen documentos registrados del tipo y en lapso seleccionado';
                    return false;
                }

                $retorno['ERRORES'] = FALSE;
                $doc_cg=true;

                while($fila=$result->FetchRow())
                {
                    $documentos[$fila['factura_fiscal']]=$fila;
                    if(empty($fila['documento_contable_id']))
                    {
                        $doc_cg = false;
                    }
                }
                $result->Close();

                if(!$doc_cg)
                {
                    $retorno['ERRORES'][]='DOCUMENTOS SIN CONTABILIZAR';
                }
                reset($documentos);
                $consecutivo_inicial= key($documentos);
                end($documentos);
                $consecutivo_ultimo = key($documentos);
                $consecutivo_ok=true;

                for($i=$consecutivo_inicial;$i<$consecutivo_ultimo;$i++)
                {
                    if(empty($documentos[$i]))
                    {
                        $documentos[$i] = null;
                        $consecutivo_ok = false;
                    }
                }
                if(!$consecutivo_ok)
                {
                    $retorno['ERRORES'][]='LOS DOCUMENTOS NO SON CONSECUTIVOS';
                }
                $retorno['EMPRESA'] = $this->documento_id['empresa_id'];
                $retorno['PREFIJO'] = $this->documento_id['prefijo'];
                ksort($documentos);
                $retorno['DOCUMENTOS'] =  $documentos;


            break;//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

            default:

            $this->error='TIPO DE DOCUMENTO NO IDENTIFICADO';
            $this->mensajeDeError='No esta definido en la clase de interfase CG1 este tipo_movimiento_id.';
            return false;

        }
        return $retorno;
    }


    /**
    *
    *
    * @param integer numero del documento
    * @return array
    * @access public
    */
    function GetDetalleDocumento($numero)
    {
        $this->error='';
        $this->mensajeDeError='';
        $detalleDoc = $this->GetDocumentos($numero);

        if(!is_array($detalleDoc))
        {
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        switch($this->documento_id['tipo_movimiento_id'])
        {
            case 'FV':
                $sql="SELECT a.numerodecuenta, a.cg_contabilizacion_estado_id as estado_cuenta,
                        b.transaccion, b.cg_contabilizacion_estado_id as estado_transaccion,
                        c.tarifario_id, c.cargo, c.consecutivo
                        FROM cg_temp_contabilizacion_facturas_cuentas a
                        LEFT JOIN cg_temp_contabilizacion_facturas_cargos b ON
                            ( b.tipo_factura=a.tipo_factura
                            AND b.numerodecuenta=a.numerodecuenta
                            AND b.cg_contabilizacion_estado_id <> 'OK')
                            JOIN cuentas_detalle c ON (b.transaccion=c.transaccion)
                        WHERE
                        a.empresa_id='".$detalleDoc['EMPRESA']."'
                        AND a.prefijo='".$detalleDoc['PREFIJO']."'
                        AND a.numero=$numero
                        AND a.cg_contabilizacion_estado_id <> 'OK'";

                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));

                while($fila=$result->FetchRow())
                {
                    $CUENTA[$fila['numerodecuenta']][]=$fila;
                }
                $result->Close();

                $I=0;
                foreach ($CUENTA as $NumCuenta=>$datos)
                {
                    $J=0;
                    foreach($datos as $k=>$v)
                    {
                        $vT[$J]['LLAVE']['TRANSACCION'] = $v['transaccion'];
                        $vT[$J]['ESTADO'] = $v['estado_transaccion'];
                        $vT[$J]['DETALLE']['TITULO'] = "CARGO";
                        $vT[$J]['DETALLE']['REGISTROS'][0]['LLAVE']['TARIFARIO']=$v['tarifario_id'];
                        $vT[$J]['DETALLE']['REGISTROS'][0]['LLAVE']['CARGO']=$v['cargo'];
                        $vT[$J]['DETALLE']['REGISTROS'][0]['LLAVE']['CONSECUTIVO DOCUMENTO BODEGA']=$v['consecutivo'];
                        $J++;
                    }
                    $vC[$I]['LLAVE']['CUENTA'] = $NumCuenta;
                    $vC[$I]['ESTADO'] = $datos[0]['estado_cuenta'];
                    $vC[$I]['DETALLE']['TITULO'] = "TRANSACCION";
                    $vC[$I]['DETALLE']['REGISTROS'] = $vT;
                    $I++;
                }

                $vF[0]['LLAVE']['EMPRESA'] = $detalleDoc['EMPRESA'];
                $vF[0]['LLAVE']['PREFIJO'] = $detalleDoc['PREFIJO'];
                $vF[0]['LLAVE']['FACTURA'] = $numero;
                $vF[0]['ESTADO']  = $detalleDoc['DOCUMENTOS'][$numero]['cg_contabilizacion_estado_id'];
                $vF[0]['DETALLE']['TITULO'] = "CUENTA";
                $vF[0]['DETALLE']['REGISTROS'] = $vC;

                $retorno['TITULO'] = "FACTURA DE VENTA";
                $retorno['REGISTROS'] = $vF;

            break;//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

            default:

            $this->error='TIPO DE DOCUMENTO NO IDENTIFICADO';
            $this->mensajeDeError='No esta definido en la clase de interfase CG1 este tipo_movimiento_id.';
            return false;

        }
        return $retorno;
    }

    /**
    * Funcion para contabilizar un documento
    *
    * @return boolean
    * @access public
    */
    function ContabilizarDocumento($numero,$reprocesar=false)
    {
        if(empty($this->documento_id['tipo_movimiento_id']))
        {
            $this->error='TIPO DE DOCUMENTO NO IDENTIFICADO';
            $this->mensajeDeError='No esta parametrizado el tipo tipo_movimiento_id para el tipo de documento general.';
            return false;
        }


        switch($this->documento_id['tipo_movimiento_id'])
        {
            case 'FV':
                if (!IncludeFile("classes/ContabilidadGeneral/ContabilizarFacturas.class.php"))
                {
                    $this->error='NO SE PUDO INCLUIR ARCHIVO DE CLASE';
                    $this->mensajeDeError='El archivo classes/ContabilidadGeneral/ContabilizarFacturas.class.php no pudo ser incluido';
                    return false;
                }

                if(!class_exists('ContabilizarFacturas'))
                {
                    $this->error='NO SE PUDO INCLUIR ARCHIVO DE CLASE';
                    $this->mensajeDeError='No existe la clase ContabilizarFacturas';
                    return false;
                }

                $a = new ContabilizarFacturas;
                if(!$a->ContabilizarFactura($this->documento_id['empresa_id'],$this->documento_id['prefijo'],$numero,$reprocesar))
                {
                    $this->error = $a->Err();
                    $this->mensajeDeError = $a->ErrMsg();

                    return false;
                }
                else
                {
                    return true;
                }

            break;

            default:

            $this->error='TIPO DE DOCUMENTO NO IDENTIFICADO';
            $this->mensajeDeError='No esta definido en la clase de interfase CG1 este tipo_movimiento_id.';
            return false;
        }

    }

    /**
    * Funcion para generar los archivos de interfase
    *
    * @return string
    * @access public
    */
    function GenerarInterfase()
    {
        $this->error='';
        $this->mensajeDeError='';
        $documentos=$this->GetDocumentos();
        if(empty($documentos))
        {
            if(empty($this->error))
            {
                $this->error='NO SE PUEDE GENERAR LA INTERFASE';
                $this->mensajeDeError='No se pudieron obtener los documentos para pasar por la interfase.';
            }
            return false;
        }

        if(is_array($documentos['ERRORES']) && !empty($documentos['ERRORES']))
        {
            $this->error='NO SE PUEDE GENERAR LA INTERFASE';
            foreach($documentos['ERRORES'] as $k=>$error)
            {
                $this->mensajeDeError .= $error . " - ";
            }
            return false;
        }

        if(empty($this->documento_id['tipo_movimiento_id']))
        {
            $this->error='TIPO DE DOCUMENTO NO IDENTIFICADO';
            $this->mensajeDeError='No esta parametrizado el tipo tipo_movimiento_id para el tipo de documento general.';
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="SELECT tipo_comprobante, codigo_empresa, codigo_cu, numero_lote FROM interfase_cguno_documentos
                WHERE documento_id = ".$this->documento_id['documento_id']."
                AND empresa_id ='".$this->documento_id['empresa_id']."';";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;


        if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));

        if($result->EOF)
        {
            $this->error='FALTA PARAMETRIZACION DE DOCUMENTO';
            $this->mensajeDeError="EL DOCUMENTO ".$this->documento_id['documento_id']." DE LA EMPRESA ".$this->documento_id['empresa_id']." NO ESTA PARAMETRIZADO EN LA TABLA interfase_cguno_documentos";
            return false;
        }

        $parametrosDocumento = $result->FetchRow();
        $result->Close();

        $sql="SELECT COUNT(*) as numero_documentos, SUM(total_debitos) as total_debitos, SUM(total_creditos) as total_creditos
                FROM cg_movimientos_contables
                WHERE empresa_id = '".$this->documento_id['empresa_id']."'
                AND documento_id = ".$this->documento_id['documento_id']."
                AND date_trunc('day',fecha_documento)>= '".$this->fechaInicial."'
                AND date_trunc('day',fecha_documento)<= '".$this->fechaFinal."';";


        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));

        $totales = $result->FetchRow();
        $result->Close();

        if(empty($totales['numero_documentos']))
        {
            $this->error='NO HAY DOCUMENTOS';
            $this->mensajeDeError="No se encontraron documentos en el lapso indicado para generar la interface.";
            return false;
        }

        $sql="SELECT COUNT(*) FROM
                (SELECT DISTINCT b.departamento
                FROM cg_movimientos_contables a, cg_movimientos_contables_facturas_d b
                                WHERE a.empresa_id = '".$this->documento_id['empresa_id']."'
                                AND a.documento_id = ".$this->documento_id['documento_id']."
                                AND date_trunc('day',a.fecha_documento)>= '".$this->fechaInicial."'
                                AND date_trunc('day',a.fecha_documento)<= '".$this->fechaFinal."'
                AND a.documento_contable_id=b.documento_contable_id
                AND b.departamento IS NOT NULL) AS x LEFT JOIN interfase_cguno_centros_de_costos z
                ON (x.departamento = z.departamento)
                WHERE z.codigo_centro_de_costo IS NULL;";


        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));

        list($verificarCC) = $result->FetchRow();
        $result->Close();

        if(!empty($verificarCC))
        {
            $this->error='CENTROS DE COSTOS SIN PARAMETRIZAR';
            $this->mensajeDeError="Existen Departamentos sin parametrizar en la tabla interfase_cguno_centros_de_costos";
            return false;
        }


        $sql="SELECT z.* FROM
                (SELECT DISTINCT b.departamento
                FROM cg_movimientos_contables a, cg_movimientos_contables_facturas_d b
                                WHERE a.empresa_id = '".$this->documento_id['empresa_id']."'
                                AND a.documento_id = ".$this->documento_id['documento_id']."
                                AND date_trunc('day',a.fecha_documento)>= '".$this->fechaInicial."'
                                AND date_trunc('day',a.fecha_documento)<= '".$this->fechaFinal."'
                AND a.documento_contable_id=b.documento_contable_id
                AND b.departamento IS NOT NULL) AS x LEFT JOIN interfase_cguno_centros_de_costos z
                ON (x.departamento = z.departamento);";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));

        while($cc=$result->FetchRow())
        {
            $CentrosDeCosto[$cc['departamento']]=$cc['codigo_centro_de_costo'];
        }
        $result->Close();

        switch($this->documento_id['tipo_movimiento_id'])
        {
            case 'FV':
                $sql="SELECT *
                        FROM cg_movimientos_contables a, cg_movimientos_contables_facturas b, cg_movimientos_contables_facturas_d c
                        WHERE a.empresa_id = '".$this->documento_id['empresa_id']."'
                        AND a.documento_id = ".$this->documento_id['documento_id']."
                        AND date_trunc('day',a.fecha_documento)>= '".$this->fechaInicial."'
                        AND date_trunc('day',a.fecha_documento)<= '".$this->fechaFinal."'
                        AND b.documento_contable_id=a.documento_contable_id
                        AND c.documento_contable_id=a.documento_contable_id";

            break;

            default:

            $this->error='TIPO DE DOCUMENTO NO IDENTIFICADO';
            $this->mensajeDeError='No esta definido en la clase de interfase CG1 este tipo_movimiento_id.';
            return false;
        }

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));

        $numRegistros=0;

        //GENERACION DEL ARCHIVO CGBATCH2
        if(!$this->AbrirArchivo($this->nameFileCGBATCH2,'w'))
        {
            if(empty($this->error))
            {
                $this->error='NO SE PUEDE GENERAR LA INTERFASE';
                $this->mensajeDeError='No se pudo generar el archivo CGBATCH2';
            }
            return false;
        }

        while($reg=$result->FetchRow())
        {
            $numRegistros++;

            $line = $this->FormatearText2BATCH($reg['cuenta'],8,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);

            if(!empty($reg['tercero_id']))
            {
                $line .= $this->FormatearText2BATCH($reg['tercero_id'],9,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
            }
            else
            {
                $line .= str_repeat (" ", 9);
            }

            $line .= $this->FormatearText2BATCH($parametrosDocumento['codigo_cu'],2,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
            $line .= substr ($this->lapsoAnho, -2) . $this->lapsoMes;
            $line .= $this->FormatearText2BATCH($parametrosDocumento['codigo_empresa'],2,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
            $line .= $this->FormatearText2BATCH($parametrosDocumento['codigo_cu'],2,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
            $line .= $this->FormatearText2BATCH($parametrosDocumento['tipo_comprobante'],2,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
            $line .= $this->FormatearText2BATCH($parametrosDocumento['numero_lote'],2,$relleno='0',$tipo_relleno=STR_PAD_LEFT);
            $line .= $this->FormatearText2BATCH($numRegistros,4,$relleno='0',$tipo_relleno=STR_PAD_LEFT);
            $line .= $this->FormatearText2BATCH($reg['prefijo'],2,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
            $line .= $this->FormatearText2BATCH($reg['numero'],5,$relleno='0',$tipo_relleno=STR_PAD_LEFT);

            $FechaDoc=str_replace("/","-",$reg['fecha_documento']);
            $FechaDoc=explode(" ",$FechaDoc);
            $FechaDoc=explode("-",$FechaDoc[0]);
            $line .= date("ymd",mktime(0,0,0,$FechaDoc[1],$FechaDoc[2],$FechaDoc[0]));

            if($reg['debito']<>0)
            {
                $line .= 'D';
                $valorParteEntera  = round($reg['debito'],0 );
                $valorParteDecimal = round(((abs($reg['debito']) - round(abs($reg['debito']),0 ))*100),0);
            }
            else
            {
                $line .= 'C';
                $valorParteEntera  = round($reg['credito'],0 );
                $valorParteDecimal = round(((abs($reg['credito']) - round(abs($reg['credito']),0 ))*100),0);
            }

            $line .= $this->FormatearText2BATCH($valorParteEntera,11,$relleno='0',$tipo_relleno=STR_PAD_LEFT);
            $line .= $this->FormatearText2BATCH($valorParteDecimal,2,$relleno='0',$tipo_relleno=STR_PAD_RIGHT);

            if($valorParteEntera>=0)
            {
                $line .= "+";
            }
            else
            {
                $line .= "-";
            }

            $detalle = trim($reg['prefijo']). "-" . trim($reg['numero']);
            $line .= $this->FormatearText2BATCH($detalle,80,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);

            if(!empty($reg['departamento']))
            {
                $line .= $this->FormatearText2BATCH($CentrosDeCosto[$reg['departamento']],8,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
            }
            else
            {
                $line .= str_repeat (" ", 8);
            }

            $line .= str_repeat (" ", 2);
            $line .= str_repeat ("0", 4);
            $line .= str_repeat ("0", 11);
            $line .= "+";
            $line .= str_repeat ("0", 9);
            $line .= "+";
            $line .= str_repeat (" ", 2);
            $line .= str_repeat ("0", 5);
            $line .= str_repeat ("0", 2);
            $line .= str_repeat (" ", 6);
            $line .= str_repeat ("0", 3);
            $line .= $this->GetTercero(trim($reg['tipo_id_tercero']),trim($reg['tercero_id']));
            $line .= "\n";
            if(!$this->EscribirArchivo($line))
            {
                if(empty($this->error))
                {
                    $this->error = "NO SE PUDO ESCRIBIR EN EL ARCHIVO";
                    $this->mensajeDeError = 'fwrite() no pudo escribir en el archivo CGBATCH2';
                }
                return false;
            }
        }
        $result->Close();

        if(!$this->CerrarArchivo())
        {
            if(empty($this->error))
            {
                $this->error = "NO SE PUDO CERRAR EL ARCHIVO";
                $this->mensajeDeError = 'fclose() retorno false cerrando el archivo CGBATCH2';
            }
            return false;
        }

        unset($this->archivo);

        $cgbatch1  = substr ($this->lapsoAnho, -2) . $this->lapsoMes;
        $cgbatch1 .= $this->FormatearText2BATCH($parametrosDocumento['codigo_empresa'],2,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
        $cgbatch1 .= $this->FormatearText2BATCH($parametrosDocumento['codigo_cu'],2,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
        $cgbatch1 .= $this->FormatearText2BATCH($parametrosDocumento['tipo_comprobante'],2,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
        $cgbatch1 .= $this->FormatearText2BATCH($parametrosDocumento['numero_lote'],2,$relleno='0',$tipo_relleno=STR_PAD_LEFT);
        $cgbatch1 .= $this->FormatearText2BATCH($numRegistros,4,$relleno='0',$tipo_relleno=STR_PAD_LEFT);

        $FechaLote=str_replace("/","-",$this->fechaFinal);
        $FechaLote=explode(" ",$FechaLote);
        $FechaLote=explode("-",$FechaLote[0]);
        $cgbatch1 .= date("ymd",mktime(0,0,0,$FechaLote[1],$FechaLote[2],$FechaLote[0]));

        $cgbatch1 .= $this->FormatearText2BATCH('SIIS',15,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);

        $valorParteEntera  = round($totales['total_debitos'],0 );
        $valorParteDecimal = round(((abs($totales['total_debitos']) - round(abs($totales['total_debitos']),0 ))*100),0);

        $cgbatch1 .= $this->FormatearText2BATCH($valorParteEntera,11,$relleno='0',$tipo_relleno=STR_PAD_LEFT);
        $cgbatch1 .= $this->FormatearText2BATCH($valorParteDecimal,2,$relleno='0',$tipo_relleno=STR_PAD_RIGHT);

        if($valorParteEntera>=0)
        {
            $cgbatch1 .= "+";
        }
        else
        {
            $cgbatch1 .= "-";
        }

        $valorParteEntera  = round($totales['total_creditos'],0 );
        $valorParteDecimal = round(((abs($totales['total_creditos']) - round(abs($totales['total_creditos']),0 ))*100),0);

        $cgbatch1 .= $this->FormatearText2BATCH($valorParteEntera,11,$relleno='0',$tipo_relleno=STR_PAD_LEFT);
        $cgbatch1 .= $this->FormatearText2BATCH($valorParteDecimal,2,$relleno='0',$tipo_relleno=STR_PAD_RIGHT);

        if($valorParteEntera>=0)
        {
            $cgbatch1 .= "+";
        }
        else
        {
            $cgbatch1 .= "-";
        }

        $cgbatch1 .= $this->FormatearText2BATCH('CONTABILIZACION SIIS',30,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
        $cgbatch1 .= str_repeat (" ", 30);
        $cgbatch1 .= '0';
        $cgbatch1 .= str_repeat (" ", 16);
        $cgbatch1 .= "\n";


        //GENERACION DEL ARCHIVO CGBATCH1

        if(!$this->AbrirArchivo($this->nameFileCGBATCH1,'w'))
        {
            if(empty($this->error))
            {
                $this->error='NO SE PUEDE GENERAR LA INTERFASE';
                $this->mensajeDeError='No se pudo generar el archivo CGBATCH1';
            }
            return false;
        }

        if(!$this->EscribirArchivo($cgbatch1))
        {
            if(empty($this->error))
            {
                $this->error = "NO SE PUDO ESCRIBIR EN EL ARCHIVO";
                $this->mensajeDeError = 'fwrite() no pudo escribir en el archivo CGBATCH1';
            }
            return false;
        }

        if(!$this->CerrarArchivo())
        {
            if(empty($this->error))
            {
                $this->error = "NO SE PUDO CERRAR EL ARCHIVO";
                $this->mensajeDeError = 'fclose() retorno false cerrando el archivo CGBATCH1';
            }
            return false;
        }

				$batch = $this->GuardarBatchGenerados();
        if(!$batch)
        {
						$this->error = "NO SE PUDO GUARDAR EL REGISTRO";
						$this->mensajeDeError = 'no se guardo el registro del batch generado';
            return false;
        }				
				
        return $batch;
    }//fin del metodo GenerarInterfase()

    /**
    * Metodo para guardar los batch que se generaron
    *
    * @param $name nombre del archivo
    * @param $modo modo de apertura
    * @return boolean True si se ejecuto correctamente de lo contrario false.
    * @access private
    */
		function GuardarBatchGenerados()
		{
				list($dbconn) = GetDBconn();		
        $query=" SELECT nextval('interfase_cg1_batch_generados_interfase_cg1_batch_generado__seq')";
        $result=$dbconn->Execute($query);
        $batch = $result->fields[0];
								
				$query = "INSERT INTO interfase_cg1_batch_generados(
														interfase_cg1_batch_generado_id,
														empresa_id,
														documento_id,
														fecha_inicial,
														fecha_final,
														estado,
														fecha_registro,
														usuario_id)
									VALUES($batch,'".$this->documento_id['empresa_id']."',".$this->documento_id['documento_id'].",
									'".$this->fechaInicial."','".$this->fechaFinal."','1','now()',".UserGetUID().")";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						return false;
				}		
								
				return $batch;							
		}
		
		
    /**
    * Metodo para abrir o crear un archivo
    *
    * @param $name nombre del archivo
    * @param $modo modo de apertura
    * @return boolean True si se ejecuto correctamente de lo contrario false.
    * @access private
    */
    function AbrirArchivo($name,$modo)
    {
        $path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/InterfaseCG1/";

        if(!file_exists($path))
        {
            $this->error = "NO EXISTE EL DIRECTORIO DE INTERFACES";
            $this->mensajeDeError = $path;
            return false;
        }

        $file = $path . $name;

        $this->archivo = fopen($file,$modo);
        if(!$this->archivo)
        {
            $this->error = "NO SE PUDO CREAR EL ARCHIVO";
            $this->mensajeDeError = 'fopen() no pudo abrir :'.$file;
            return false;
        }

        if(feof($this->archivo))
        {
            $this->error = "EL ARCHIVO ESTA EN EOF";
            $this->mensajeDeError = 'No se puede escribir en el archivo :'.$file;
            return false;
        }

        return true;
    }


    /**
    * Metodo para escribir en un archivos
    *
    * @param $texto texto a escribir en el archivo (linea)
    * @return boolean True si se ejecuto correctamente de lo contrario false.
    * @access private
    */
    function EscribirArchivo($texto)
    {
        if(!fwrite($this->archivo,$texto))
        {
            return false;
        }
        return true;
    }



    /**
    * Metodo para cerrar archivos
    *
    * @return boolean True si se ejecuto correctamente de lo contrario false.
    * @access private
    */
    function CerrarArchivo()
    {
        if(!fclose($this->archivo))
        {
            return false;
        }
        return true;
    }


    /**
    * Funcion para obtener un tercero del vector de beneficiarios
    *
    * @param string $tipo_id_tercero Tipo de Id del tercero
    * @param string $tercero_id No. de Id del tercero
    * @param string dato solicitado default arreglo con todos los datos
    * @return string/array el valor del dato solicitado o un vector con todods los datos.
    * @access public
    */
    function GetTercero($tipo_id_tercero,$tercero_id,$dato=null)
    {
        static $filaVacia;

        if(empty($tipo_id_tercero) || empty($tercero_id))
        {
            if($filaVacia)
            {
                return $filaVacia;
            }

            //CONSTRUIR FILA VACIA
            $filaVacia  = str_repeat (" ", 11);
            $filaVacia .= str_repeat (" ", 40);
            $filaVacia .= str_repeat (" ", 4);
            $filaVacia .= str_repeat (" ", 4);
            $filaVacia .= str_repeat (" ", 4);
            $filaVacia .= " ";
            $filaVacia .= str_repeat (" ", 25);
            $filaVacia .= str_repeat (" ", 12);
            $filaVacia .= str_repeat (" ", 12);
            $filaVacia .= str_repeat (" ", 10);
            $filaVacia .= str_repeat (" ", 15);
            $filaVacia .= str_repeat (" ", 40);
            $filaVacia .= " ";
            $filaVacia .= " ";
            $filaVacia .= " ";
            $filaVacia .= " ";
            $filaVacia .= " ";
            $filaVacia .= " ";
            $filaVacia .= " ";
            $filaVacia .= " ";
            $filaVacia .= str_repeat ("0", 9);
            $filaVacia .= str_repeat ("0", 4);
            $filaVacia .= " ";
            $filaVacia .= str_repeat (" ", 10);
            $filaVacia .= " ";
            $filaVacia .= "000";

            //RELLENO SUCURSAL
            $filaVacia .= str_repeat (" ", 168);
            $filaVacia .= str_repeat ("0", 11) .'+';
            $filaVacia .= str_repeat (" ", 13);
            $filaVacia .= str_repeat (" ", 4);
            $filaVacia .= str_repeat ("0", 8);

            return $filaVacia;
        }

        if(empty($this->terceros[$tipo_id_tercero][$tercero_id]))
        {
            GLOBAL $ADODB_FETCH_MODE;
            list($dbconn) = GetDBconn();
            $sql = "SELECT a.*, b.municipio, c.departamento, d.pais
                    FROM terceros as a ,tipo_mpios b, tipo_dptos c, tipo_pais  d
                    WHERE a.tipo_id_tercero='$tipo_id_tercero'
                    AND a.tercero_id='$tercero_id'
                    AND b.tipo_pais_id=a.tipo_pais_id
                    AND b.tipo_dpto_id=a.tipo_dpto_id
                    AND b.tipo_mpio_id=a.tipo_mpio_id
                    AND c.tipo_pais_id=a.tipo_pais_id
                    AND c.tipo_dpto_id=a.tipo_dpto_id
                    AND d.tipo_pais_id=a.tipo_pais_id";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if($dbconn->ErrorNo() != 0)
            {
                die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));
            }
            if($result->EOF)
            {echo "ERR<BR>";
                return false;
            }
            $datosTercero = $result->FetchRow();
            $result->Close();

            $this->terceros[$tipo_id_tercero][$tercero_id]['tipo_id_tercero']=$datosTercero['tipo_id_tercero'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['tercero_id']=$datosTercero['tercero_id'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['nombre_tercero']=$datosTercero['nombre_tercero'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['tipo_pais_id']=$datosTercero['tipo_pais_id'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['pais']=$datosTercero['pais'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['tipo_dpto_id']=$datosTercero['tipo_dpto_id'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['departamento']=$datosTercero['departamento'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['tipo_mpio_id']=$datosTercero['tipo_mpio_id'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['municipio']=$datosTercero['municipio'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['direccion']=$datosTercero['direccion'];
            $this->terceros[$tipo_id_tercero][$tercero_id]['telefono']=$datosTercero['telefono'];

            // EQUIVALENCIAS ESTATICAS ------------------------------------------------------------------------
            //-------------------------------------------------------------------------------------------------
            switch($tipo_id_tercero)
            {
                CASE 'CC':
                    $this->terceros[$tipo_id_tercero][$tercero_id]['clase_beneficiario']='C';
                break;

                CASE 'NIT':
                    $this->terceros[$tipo_id_tercero][$tercero_id]['clase_beneficiario']='N';
                break;

                CASE 'TI':
                    $this->terceros[$tipo_id_tercero][$tercero_id]['clase_beneficiario']='C';
                break;

                CASE 'PA':
                    $this->terceros[$tipo_id_tercero][$tercero_id]['clase_beneficiario']='C';
                break;

                CASE 'CE':
                    $this->terceros[$tipo_id_tercero][$tercero_id]['clase_beneficiario']='C';
                break;

                default:
                    $this->terceros[$tipo_id_tercero][$tercero_id]['clase_beneficiario']='C';

            }

            // CONCATENACION DE CGBATCH2 CAMPOS DEL BENEFICIARIO
            $datosBeneficiario  = $this->FormatearText2BATCH($this->terceros[$tipo_id_tercero][$tercero_id]['tercero_id'],11,' ',STR_PAD_RIGHT);
            $datosBeneficiario .= $this->FormatearText2BATCH($this->terceros[$tipo_id_tercero][$tercero_id]['nombre_tercero'] ,40,' ',STR_PAD_RIGHT);
            $datosBeneficiario .= str_repeat (" ", 4);
            $datosBeneficiario .= str_repeat (" ", 4);
            $datosBeneficiario .= str_repeat (" ", 4);
            $datosBeneficiario .= $this->terceros[$tipo_id_tercero][$tercero_id]['clase_beneficiario'];
            $datosBeneficiario .= $this->FormatearText2BATCH($this->terceros[$tipo_id_tercero][$tercero_id]['direccion'] ,25,' ',STR_PAD_RIGHT);
            $datosBeneficiario .= $this->FormatearText2BATCH($this->terceros[$tipo_id_tercero][$tercero_id]['municipio'] ,12,' ',STR_PAD_RIGHT);
            $datosBeneficiario .= $this->FormatearText2BATCH($this->terceros[$tipo_id_tercero][$tercero_id]['departamento'] ,12,' ',STR_PAD_RIGHT);
            $datosBeneficiario .= str_repeat (" ", 10);
            $datosBeneficiario .= $this->FormatearText2BATCH($this->terceros[$tipo_id_tercero][$tercero_id]['telefono'] ,15,' ',STR_PAD_RIGHT);
            $datosBeneficiario .= str_repeat (" ", 40);
            $datosBeneficiario .= "0";
            $datosBeneficiario .= "0";
            $datosBeneficiario .= "0";
            $datosBeneficiario .= "0";
            $datosBeneficiario .= "0";
            $datosBeneficiario .= "0";
            $datosBeneficiario .= "0";
            $datosBeneficiario .= "0";
            $datosBeneficiario .= str_repeat ("0", 9);
            $datosBeneficiario .= str_repeat ("0", 4);
            $datosBeneficiario .= "0";
            $datosBeneficiario .= str_repeat (" ", 10);
            $datosBeneficiario .= "A";
            $datosBeneficiario .= "000";

            //RELLENO SUCURSAL
            $datosBeneficiario .= str_repeat (" ", 168);
            $datosBeneficiario .= str_repeat ("0", 11) .'+';
            $datosBeneficiario .= str_repeat (" ", 13);
            $datosBeneficiario .= str_repeat (" ", 4);
            $datosBeneficiario .= str_repeat ("0", 8);

            $this->terceros[$tipo_id_tercero][$tercero_id]['CG1_BATCH2_BENEFICIARIO']=$datosBeneficiario;

        }

        if($dato)
        {
            return $this->terceros[$tipo_id_tercero][$tercero_id][$dato];
        }
        else
        {
            return $this->terceros[$tipo_id_tercero][$tercero_id]['CG1_BATCH2_BENEFICIARIO'];
        }

    }

     /**
    * Funcion para dar formato a los campos del CGBATCH
    *
    * @param string $cadena Cadena a formatear
    * @param integer $len tamño de salida de la cadena
    * @param string $relleno caracter de relleno default espacios
    * @param integer $tipo_relleno uno de las constantes STR_PAD_RIGHT,STR_PAD_LEFT default relleno RIGHT
    * @return string Cadena formateada
    * @access private
    */
    function FormatearText2BATCH($cadena,$len,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT)
    {
        $cadena=trim($cadena);
        if(strlen($cadena)<$len)
        {
            $cadena = str_pad($cadena, $len, $relleno, $tipo_relleno);
        }
        else
        {
            $cadena = substr($cadena,0,$len);
        }

        return $cadena;
    }

}//fin de la class


?>
