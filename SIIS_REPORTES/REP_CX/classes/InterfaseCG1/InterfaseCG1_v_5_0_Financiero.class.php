 <?php

/**
* $Id: InterfaseCG1_v_5_0_Financiero.class.php,v 1.2 2006/05/15 21:46:20 alex Exp $
*/

/**
* Clase de para la generación de la interfase con CG-UNO version 5.0
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.2 $
* @package SIIS
*/
class InterfaseCG1_v_5_0_Financiero extends InterfaseCG1_Financiero
{

    /**
    * Nombre del archivo CGBATCH1
    *
    * @var string
    * @access private
    */
    var $nameFileCGBATCH1='CGBATCH1.DAT';

    /**
    * Nombre del archivo CGBATCH2
    *
    * @var string
    * @access private
    */
    var $nameFileCGBATCH2='CGBATCH2.DAT';

    /**
    * Nombre del archivo de descarga (tar.gz)
    *
    * @var string
    * @access private
    */
    var $nameFileCompress='';

    /**
    * Vector con los datos de los terceros (beneficiarios)
    *
    * @var array
    * @access private
    */
    var $terceros=array();

    /**
    * Constructor
    *
    * @return boolean
    * @access public
    */
    function InterfaseCG1_v_5_0_Financiero()
    {
        $this->InterfaseCG1_Financiero();
        return true;
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
                        FROM cg_movimientos_contables a,
                        cg_movimientos_contables_facturas b,
                        cg_movimientos_contables_facturas_d c,
                        cg_plan_de_cuentas d
                        WHERE a.empresa_id = '".$this->documento_id['empresa_id']."'
                        AND a.documento_id = ".$this->documento_id['documento_id']."
                        AND date_trunc('day',a.fecha_documento)>= '".$this->fechaInicial."'
                        AND date_trunc('day',a.fecha_documento)<= '".$this->fechaFinal."'
                        AND b.documento_contable_id=a.documento_contable_id
                        AND c.documento_contable_id=a.documento_contable_id
                        AND d.empresa_id=c.empresa_id
                        AND d.cuenta=c.cuenta";
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
        if(!$archivo = $this->AbrirArchivo($this->nameFileCGBATCH2,'w'))
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

            $numero_doc = (string) $reg['numero'];
            $numero_doc = trim($numero_doc);
            if(strlen($numero_doc)>5) $numero_doc = substr($numero_doc,-5);

            $line .= $this->FormatearText2BATCH($numero_doc,5,$relleno='0',$tipo_relleno=STR_PAD_LEFT);



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
            if($reg['sw_documento_cruce'] && $reg['tipo_movimiento_id']=='FV') //SOLO ESTA FUNCIONANDO PARA FACTURAS
            {
                $line .= $this->FormatearText2BATCH($reg['prefijo'],2,$relleno=' ',$tipo_relleno=STR_PAD_RIGHT);
                $line .= $this->FormatearText2BATCH($reg['numero'],5,$relleno='0',$tipo_relleno=STR_PAD_LEFT);
                $line .= str_repeat ("0", 2);
                $line .= date("ymd",mktime(0,0,0,$FechaDoc[1]+4,$FechaDoc[2],$FechaDoc[0]));
            }
            else
            {
              $line .= str_repeat (" ", 2);
              $line .= str_repeat ("0", 5);
              $line .= str_repeat ("0", 2);
              $line .= str_repeat (" ", 6);
            }

            $line .= str_repeat ("0", 3);
            $line .= $this->GetTercero(trim($reg['tipo_id_tercero']),trim($reg['tercero_id']));
            $line .= "\n";
            if(!$this->EscribirArchivo($archivo,$line))
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

        if(!$this->CerrarArchivo($archivo))
        {
            if(empty($this->error))
            {
                $this->error = "NO SE PUDO CERRAR EL ARCHIVO";
                $this->mensajeDeError = 'fclose() retorno false cerrando el archivo CGBATCH2';
            }
            return false;
        }

       // unset($this->archivo);

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

        if(!$archivo = $this->AbrirArchivo($this->nameFileCGBATCH1,'w'))
        {
            if(empty($this->error))
            {
                $this->error='NO SE PUEDE GENERAR LA INTERFASE';
                $this->mensajeDeError='No se pudo generar el archivo CGBATCH1';
            }
            return false;
        }

        if(!$this->EscribirArchivo($archivo,$cgbatch1))
        {
            if(empty($this->error))
            {
                $this->error = "NO SE PUDO ESCRIBIR EN EL ARCHIVO";
                $this->mensajeDeError = 'fwrite() no pudo escribir en el archivo CGBATCH1';
            }
            return false;
        }

        if(!$this->CerrarArchivo($archivo))
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



}//End Class

?>
