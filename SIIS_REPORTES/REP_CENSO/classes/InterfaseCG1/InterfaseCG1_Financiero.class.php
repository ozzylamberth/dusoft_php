<?php

/**
* $Id: InterfaseCG1_Financiero.class.php,v 1.1 2006/05/12 22:41:43 alex Exp $
*/

/**
* Clase de para la generación de la interfase con CG-UNO
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/
class InterfaseCG1_Financiero
{

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
    * path de los archivos de interface
    *
    * @var string
    * @access private
    */
    var $path='';

    /**
    * Documento Contable
    *
    * @var array
    * @access private
    */
    var $documento_id=array();

    /**
    * Mensaje de error
    *
    * @var string
    * @access private
    */
    var $codigosError=array();

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
    * Constructor
    *
    * @return boolean
    * @access public
    */
    function InterfaseCG1_Financiero()
    {
        $this->error='';
        $this->mensajeDeError='';
        $this->documento_id=null;
        $this->path = GetVarConfigAplication('DIR_SIIS')."Interface_Files/InterfaseCG1/";


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
    * Retorna path de los archivos de interface
    *
    * @return string
    * @access public
    */
    function GetPathInterfaseCG1()
    {
        return $this->path;
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
    * Metodo para abrir o crear un archivo
    *
    * @param $name nombre del archivo
    * @param $modo modo de apertura
    * @return boolean True si se ejecuto correctamente de lo contrario false.
    * @access private
    */
    function AbrirArchivo($name,$modo)
    {
        if(!file_exists($this->path))
        {
            $this->error = "NO EXISTE EL DIRECTORIO DE INTERFACES";
            $this->mensajeDeError = $path;
            return false;
        }

        $file = $this->path . $name;

        $archivo = fopen($file,$modo);

        if(!$archivo)
        {
            $this->error = "NO SE PUDO CREAR EL ARCHIVO";
            $this->mensajeDeError = 'fopen() no pudo abrir :'.$file;
            return false;
        }

        if(feof($archivo))
        {
            $this->error = "EL ARCHIVO ESTA EN EOF";
            $this->mensajeDeError = 'No se puede escribir en el archivo :'.$file;
            return false;
        }

        return $archivo;
    }

    /**
    * Metodo para escribir en un archivos
    *
    * @param $texto texto a escribir en el archivo (linea)
    * @return boolean True si se ejecuto correctamente de lo contrario false.
    * @access private
    */
    function EscribirArchivo($archivo,$texto)
    {
        if(!fwrite($archivo,$texto))
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
    function CerrarArchivo($archivo)
    {
        if(!fclose($archivo))
        {
            return false;
        }
        return true;
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

}//End Class
?>
