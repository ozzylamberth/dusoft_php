<?php
/**
 * $Id: InterfaseCG1_v_8_5_Financiero.class.php,v 1.4 2006/07/31 13:56:34 alex Exp $
 */

/**
 * Clase para la generación de la interfase con CG-UNO versino 8.5
 *
 * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
 * @version $Revision: 1.4 $
 * @package SIIS
 */
class InterfaseCG1_v_8_5_Financiero extends InterfaseCG1_Financiero
{
   /**
    * Espacio en blanco
    */
    var $e;

   /**
    * Caracter cero
    */
    var $c;


    /**
    * Nombre del archivo CGBATCH
    *
    * @var string
    * @access private
    */
    var $nameFileCGBATCH='CGBATCH.DAT';


    /**
    * Constructor de la clase
    */
    function InterfaseCG1_v_8_5_Financiero()
    {
        //Constructor del padre
        $this->InterfaseCG1_Financiero();

        $this->e = " ";
        $this->c = "0";
        $this->nameFileCGBATCH1 ='CGBATCH.DAT';

        return true;

    }//Fin constructor



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
            $this->error='TIPO DE DOCUMENTO NO IDENTIFICADO X';
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

        switch($this->documento_id['tipo_movimiento_id'])
        {
            case 'FV':

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
            break;

            case 'HM':

            $sql="SELECT COUNT(*) FROM
                    (SELECT DISTINCT b.departamento
                    FROM cg_movimientos_contables a, cg_movimientos_contables_honorarios_d b
                                    WHERE a.empresa_id = '".$this->documento_id['empresa_id']."'
                                    AND a.documento_id = ".$this->documento_id['documento_id']."
                                    AND date_trunc('day',a.fecha_documento)>= '".$this->fechaInicial."'
                                    AND date_trunc('day',a.fecha_documento)<= '".$this->fechaFinal."'
                    AND a.documento_contable_id=b.documento_contable_id
                    AND b.departamento IS NOT NULL) AS x LEFT JOIN interfase_cguno_centros_de_costos z
                    ON (x.departamento = z.departamento)
                    WHERE z.codigo_centro_de_costo IS NULL;";
        }

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

        switch($this->documento_id['tipo_movimiento_id'])
        {
            case 'FV':
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
            break;

            case 'HM':
            $sql="SELECT z.* FROM
                    (SELECT DISTINCT b.departamento
                    FROM cg_movimientos_contables a, cg_movimientos_contables_honorarios_d b
                                    WHERE a.empresa_id = '".$this->documento_id['empresa_id']."'
                                    AND a.documento_id = ".$this->documento_id['documento_id']."
                                    AND date_trunc('day',a.fecha_documento)>= '".$this->fechaInicial."'
                                    AND date_trunc('day',a.fecha_documento)<= '".$this->fechaFinal."'
                    AND a.documento_contable_id=b.documento_contable_id
                    AND b.departamento IS NOT NULL) AS x LEFT JOIN interfase_cguno_centros_de_costos z
                    ON (x.departamento = z.departamento);";

        }

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
                $sql="SELECT
                            a.documento_contable_id,
                            a.fecha_documento,
                            a.fecha_registro,
                            a.empresa_id,
                            a.centro_utilidad,
                            a.unidad_funcional,
                            a.departamento,
                            a.documento_id,
                            a.tipo_movimiento_id,
                            a.sw_estado,
                            a.tipo_bloqueo_id,
                            a.total_debitos,
                            a.total_creditos,
                            a.tipo_id_tercero,
                            a.tercero_id,
                            a.usuario_id,
                            b.prefijo,
                            b.numero,
                            c.movimiento_contable_id,
                            c.cuenta,
                            c.tipo_id_tercero as tipo_id_tercero_mov ,
                            c.tercero_id as tercero_id_mov,
                            c.debito,
                            c.credito,
                            c.detalle as detalle_mov,
                            c.departamento as departamento_mov,
                            c.unidad_funcional as unidad_funcional_mov,
                            c.centro_utilidad as centro_utilidad_mov,
                            c.base_rtf,
                            c.porcentaje_rtf,
                            d.sw_documento_cruce,
                            d.sw_impuesto_rtf

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

            case 'HM':
                $sql="SELECT
                            a.documento_contable_id,
                            a.fecha_documento,
                            a.fecha_registro,
                            a.empresa_id,
                            a.centro_utilidad,
                            a.unidad_funcional,
                            a.departamento,
                            a.documento_id,
                            a.tipo_movimiento_id,
                            a.sw_estado,
                            a.tipo_bloqueo_id,
                            a.total_debitos,
                            a.total_creditos,
                            a.tipo_id_tercero,
                            a.tercero_id,
                            a.usuario_id,
                            b.prefijo,
                            b.numero,
                            c.movimiento_contable_id,
                            c.cuenta,
                            c.tipo_id_tercero as tipo_id_tercero_mov ,
                            c.tercero_id as tercero_id_mov,
                            c.debito,
                            c.credito,
                            c.detalle as detalle_mov,
                            c.departamento as departamento_mov,
                            c.unidad_funcional as unidad_funcional_mov,
                            c.centro_utilidad as centro_utilidad_mov,
                            c.base_rtf,
                            c.porcentaje_rtf,
                            d.sw_documento_cruce,
                            d.sw_impuesto_rtf

                        FROM cg_movimientos_contables a,
                        cg_movimientos_contables_honorarios b,
                        cg_movimientos_contables_honorarios_d c,
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

        //GENERACION DEL ARCHIVO CGBATCH2
        if(!$archivo = $this->AbrirArchivo($this->nameFileCGBATCH,'w'))
        {
            if(empty($this->error))
            {
                $this->error='NO SE PUEDE GENERAR LA INTERFASE';
                $this->mensajeDeError='No se pudo generar el archivo CGBATCH';
            }
            return false;
        }

        $numRegistros=0;

        while($reg=$result->FetchRow())
        {
            $numRegistros++;

            // DATOS REFERENTES AL DOCUMENTO
            //--------------------------------
            $line  = $this->FormatearText2BATCH($numRegistros,9,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//K1-CONSECUTIVO
            $line .= $this->FormatearText2BATCH($parametrosDocumento['codigo_empresa'],2,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//EMP-MOV
            $line .= $this->FormatearText2BATCH($parametrosDocumento['codigo_cu'],3,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//CO-MOV
            $line .= $this->FormatearText2BATCH($reg['prefijo'],2,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//TIPO-MOV

            $numero_doc = (string) $reg['numero'];
            $numero_doc = trim($numero_doc);
            if(strlen($numero_doc)>6) $numero_doc = substr($numero_doc,-6);

            $line .= $this->FormatearText2BATCH($numero_doc,6,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//NRO-MOV
            $line .= $this->FormatearText2BATCH($reg['tercero_id'],13,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//TERC-DOC
            $line .= str_repeat ($this->c, 2);//SUC-DOC

            $FechaDoc=str_replace("/","-",$reg['fecha_documento']);
            $FechaDoc=explode(" ",$FechaDoc);
            $FechaDoc=explode("-",$FechaDoc[0]);
            $line .= date("Ymd",mktime(0,0,0,$FechaDoc[1],$FechaDoc[2],$FechaDoc[0]));//FECHA-MOV

            $line .= $this->lapsoAnho . $this->lapsoMes;//LAPSO-MOV
            $line .= str_repeat($this->e,8);//LOTE-DOC
            $line .= str_repeat($this->e,8);//DCTO-ALT-DOC
            $line .= str_repeat($this->e,40);//NOMCLI-DOC
            $line .= str_repeat($this->e,13);//NITCLI-DOC

            //VALOR-DOC


            $valorX = explode(".",$reg['total_debitos']);
            $valorParteEntera  = $valorX[0];
            $valorParteDecimal = $valorX[1];

            $line .= $this->FormatearText2BATCH($valorParteEntera,15,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);
            $line .= $this->FormatearText2BATCH($valorParteDecimal,2,$relleno=$this->c,$tipo_relleno=STR_PAD_RIGHT);
            if($valorParteEntera>=0)
            {
                $line .= "+";
            }
            else
            {
                $line .= "-";
            }

            $line .= str_repeat($this->e,20);//FILLER-DOC
            $line .= str_repeat($this->e,3);//ORIGEN-DOC


            // DATOS REFERENTES A LAS TRANSACCIONES DE MOVIMIENTO
            //-----------------------------------------------------

            $line .= $this->FormatearText2BATCH($reg['cuenta'],8,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//CUENTA-MOV

            //TERC-MOV
            if(!empty($reg['tercero_id_mov']))
            {
                $line .= $this->FormatearText2BATCH($reg['tercero_id_mov'],13,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);
            }
            else
            {
                $line .= str_repeat ($this->e, 13);
            }

            $line .= str_repeat ($this->c, 2);//SUC-MOV

            $line .= $this->FormatearText2BATCH($parametrosDocumento['codigo_cu'],3,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//CO-MOVTO-MOV

            //DC-MOV
            if($reg['debito']<>0)
            {
                $line .= 'D';
                $valorX = explode(".",$reg['debito']);
                $valorParteEntera  = $valorX[0];
                $valorParteDecimal = $valorX[1];

                //$valorParteDecimal = round(((abs($reg['debito']) - round(abs($reg['debito']),0 ))*100),0);
         echo $reg['debito']." - ".$valorParteEntera ." - " . $valorParteDecimal . "<br>";

            }
            else
            {
                $line .= 'C';
                $valorX = explode(".",$reg['credito']);
                $valorParteEntera  = $valorX[0];
                $valorParteDecimal = $valorX[1];

            }

            //VALOR-MOV
            $line .= $this->FormatearText2BATCH($valorParteEntera,15,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);
            $line .= $this->FormatearText2BATCH($valorParteDecimal,2,$relleno=$this->c,$tipo_relleno=STR_PAD_RIGHT);
            if($valorParteEntera>=0)
            {
                $line .= "+";
            }
            else
            {
                $line .= "-";
            }

            $line .= str_repeat ($this->c, 17);//VALOR-ME-MOV
            $line .= "+";

            $line .= str_repeat ($this->c, 11);//TASA-CONVER-MOV
            $line .= "+";

            $line .= str_repeat ($this->c, 11);//TASA-CAMBIO-MOV
            $line .= "+";

            if($reg['sw_impuesto_rtf'])
            {

                $valorX = explode(".",$reg['base_rtf']);
                $valorParteEntera  = $valorX[0];
                $valorParteDecimal = $valorX[1];
                $line .= $this->FormatearText2BATCH($valorParteEntera,15,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);
                $line .= $this->FormatearText2BATCH($valorParteDecimal,2,$relleno=$this->c,$tipo_relleno=STR_PAD_RIGHT);
                if($valorParteEntera>=0)
                {
                    $line .= "+";
                }
                else
                {
                    $line .= "-";
                }

                $valorX = explode(".",$reg['porcentaje_rtf']);
                $valorParteEntera  = $valorX[0];
                $valorParteDecimal = $valorX[1];
                $line .= $this->FormatearText2BATCH($valorParteEntera,2,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);
                $line .= $this->FormatearText2BATCH($valorParteDecimal,4,$relleno=$this->c,$tipo_relleno=STR_PAD_RIGHT);
            }
            else
            {
                $line .= str_repeat ($this->c, 17);//BASE-IVARET-MOV
                $line .= "+";

                $line .= str_repeat ($this->c, 6);//TASA-IMPRET-MOV
            }

            $line .= str_repeat ($this->c, 6);//TASA-BSEIMP-MOV

            $line .= str_repeat ($this->c, 13);//CANTIDAD-MOV
            $line .= "+";


            switch($this->documento_id['tipo_movimiento_id'])
            {
                case 'FV':
                    $detalle = trim($reg['prefijo']). "-" . trim($reg['numero']);
                break;

                case 'HM':
                    $detalle = "VOUCHER ".trim($reg['prefijo']). "-" . trim($reg['numero']);
                break;

                default:
                $detalle = 'CONTABILIZACION SIIS';
            }

            //DEALLE1-MOV Y DETALLE2-MOV
            $line .= $this->FormatearText2BATCH($detalle,80,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);

            //CCOSTO-MOV
            if(!empty($reg['departamento_mov']))
            {

                $line .= $this->FormatearText2BATCH($CentrosDeCosto[$reg['departamento_mov']],8,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);
            }
            else
            {
                $line .= str_repeat ($this->e, 8);
            }

            $line .= str_repeat ($this->e, 8);//CPTOFC-MOV
            $line .= str_repeat ($this->e,40);//DESC-CPTOFC-MOV
            $line .= str_repeat ($this->e,10);//PROYECTO-MOV
            $line .= str_repeat ($this->e,40);//DESC-PROYECTO-MOV
            $line .= str_repeat ($this->e, 2);//TIPO-BANCO-MOV
            $line .= str_repeat ($this->c, 6);//NRO-BANCO-MOV
            $line .= str_repeat ($this->e, 4);//PREFIJO-PROV-MOV
            $line .= str_repeat ($this->c,12);//NRO-PROV-MOV
            $line .= str_repeat ($this->e,30);//FILLER-MOV


            // DATOS CXC/CXP
            //-----------------------------------------------------


            if($reg['sw_documento_cruce'] && $this->documento_id['tipo_movimiento_id']) //SOLO ESTA FUNCIONANDO PARA FACTURAS
            {
                $line .= $this->FormatearText2BATCH($reg['prefijo'],2,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//TIPO-MOV

                $numero_doc = (string) $reg['numero'];
                $numero_doc = trim($numero_doc);
                if(strlen($numero_doc)>6) $numero_doc = substr($numero_doc,-6);

                $line .= $this->FormatearText2BATCH($numero_doc,6,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//NRO-MOV
                $line .= str_repeat ($this->c, 2);//NRO-CUOTA-CRU-CRUC
                $line .= date("Ymd",mktime(0,0,0,$FechaDoc[1]+4,$FechaDoc[2],$FechaDoc[0]));
            }
            else
            {
                $line .= str_repeat ($this->e, 2);//TIPO-CRUCE
                $line .= str_repeat ($this->c, 6);//NRO-CRUCE
                $line .= str_repeat ($this->c, 2);//NRO-CUOTA-CRU-CRUC
                $line .= str_repeat ($this->c, 8);//FECHA-VCTO-CRUCE
            }


            $line .= str_repeat ($this->e,13);//
            $line .= str_repeat ($this->e,13);//
            $line .= str_repeat ($this->c, 2);//
            $line .= str_repeat ($this->c, 1);//
            $line .= str_repeat ($this->e,11);//
            $line .= str_repeat ($this->c, 3);//
            $line .= str_repeat ($this->c, 8);//
            $line .= str_repeat ($this->c,13);//
            $line .= "+";
            $line .= str_repeat ($this->c,13);//
            $line .= "+";
            $line .= str_repeat ($this->e,20);//


            // DATOS A MOVIMIENTOS DE DIFERIDOS
            //-----------------------------------------------------

            $line .= str_repeat ($this->e,12);//
            $line .= str_repeat ($this->c, 2);//
            $line .= str_repeat ($this->c, 8);//
            $line .= str_repeat ($this->c, 8);//
            $line .= str_repeat ($this->e, 8);//
            $line .= str_repeat ($this->e,13);//
            $line .= str_repeat ($this->c, 2);//
            $line .= str_repeat ($this->c, 3);//
            $line .= str_repeat ($this->e, 8);//
            $line .= str_repeat ($this->e,10);//
            $line .= str_repeat ($this->e,40);//
            $line .= str_repeat ($this->c, 8);//
            $line .= str_repeat ($this->c, 8);//
            $line .= str_repeat ($this->e,20);//


            // DATOS REFERENTES A TESORERIA
            //-----------------------------------------------------
            $line .= str_repeat ($this->e, 3);//
            $line .= str_repeat ($this->e, 3);//
            $line .= str_repeat ($this->e, 1);//
            $line .= str_repeat ($this->c, 8);//
            $line .= str_repeat ($this->e, 1);//
            $line .= str_repeat ($this->e, 1);//
            $line .= str_repeat ($this->e, 1);//
            $line .= str_repeat ($this->e, 3);//
            $line .= str_repeat ($this->e, 4);//
            $line .= str_repeat ($this->c, 6);//
            $line .= str_repeat ($this->e, 3);//
            $line .= str_repeat ($this->c, 6);//
            $line .= str_repeat ($this->e,10);//
            $line .= str_repeat ($this->e,20);//
            $line .= str_repeat ($this->e,15);//
            $line .= str_repeat ($this->e,20);//
            $line .= str_repeat ($this->c, 8);//
            $line .= str_repeat ($this->e,20);//

            // DATOS TERCEROS
            //-----------------------------------------------------
            $line .= $this->GetTercero(trim($reg['tipo_id_tercero_mov']),trim($reg['tercero_id_mov']));
            $line .= $this->GetTeceroCliente(trim($reg['tipo_id_tercero_mov']),trim($reg['tercero_id_mov']));
            $line .= $this->GetTerceroProveedor(trim($reg['tipo_id_tercero_mov']),trim($reg['tercero_id_mov']));
            $line .= $this->GetTerceroEmpleado(trim($reg['tipo_id_tercero_mov']),trim($reg['tercero_id_mov']));
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
    * Retorna los datos de un tercero/sucursales en el formato CGBATCH
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
        static $terceros=array();

        if(empty($tipo_id_tercero) || empty($tercero_id))
        {
            return $this->GetTerceroNULL();
        }


        if(empty($terceros[$tipo_id_tercero][$tercero_id]))
        {
            GLOBAL $ADODB_FETCH_MODE;
            list($dbconn) = GetDBconn();
            $sql = "
                SELECT
                    a.*
                FROM
                    terceros as a
                WHERE
                    a.tipo_id_tercero='$tipo_id_tercero'
                    AND a.tercero_id='$tercero_id'";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if($dbconn->ErrorNo() != 0)
            {
                die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));
            }
            if($result->EOF)
            {
                return $this->GetTerceroNULL();
            }
            $datosTercero = $result->FetchRow();
            $result->Close();


            $datosBeneficiario  = $this->FormatearText2BATCH($datosTercero['tercero_id'],15,$this->e,STR_PAD_RIGHT);//NIT-TER
            $datosBeneficiario .= str_repeat($this->c,1);//NIT-DV-TER
            $datosBeneficiario .= str_repeat('A',1);//IDENT-TERCERO-TER
            $datosBeneficiario .= $this->FormatearText2BATCH($datosTercero['nombre_tercero'] ,50,$this->e,STR_PAD_RIGHT);
            $datosBeneficiario .= str_repeat($this->e,50);//K4-NOMBRE2-TER
            $datosBeneficiario .= str_repeat($this->e,15);//APELLIDO1-TER
            $datosBeneficiario .= str_repeat($this->e,15);//APELLIDO2-TER
            $datosBeneficiario .= str_repeat($this->e,20);//NOMBRES-TER

            //TIPO-TERC-TER
            switch($datosTercero['tipo_id_tercero'])
            {
                CASE 'NIT':
                    $datosBeneficiario .= '1';
                break;

                CASE 'CC':
                CASE 'TI':
                CASE 'PA':
                CASE 'CE':
                    $datosBeneficiario .= '0';
                break;

                default:
                    $datosBeneficiario .= '9';
            }

            $datosBeneficiario .= str_repeat($this->c,1);//TIPO-IDENT-TER
            $datosBeneficiario .= str_repeat($this->c,1);//IND-CLI-TER
            $datosBeneficiario .= str_repeat($this->c,1);//IND-PRO-TER
            $datosBeneficiario .= str_repeat($this->c,1);//IND-EMPL-TER
            $datosBeneficiario .= str_repeat($this->c,1);//IND-ACCIO-TER
            $datosBeneficiario .= str_repeat($this->c,1);//IND-VAR-TER
            $datosBeneficiario .= str_repeat($this->c,1);//IND-INT-TER
            $datosBeneficiario .= str_repeat($this->c,1);//IND-TARJ-TER
            $datosBeneficiario .= str_repeat($this->e,1);//ESTADO-TER
            $datosBeneficiario .= str_repeat($this->c,8);//FECHA-CRE-TER

            $datosBeneficiario .= str_repeat($this->c,3);//PAIS-TER
            $datosBeneficiario .= str_repeat($this->c,2);//DPTO-TER
            $datosBeneficiario .= str_repeat($this->c,3);//CIUDAD-TER

            $datosBeneficiario .= $this->FormatearText2BATCH($datosTercero['direccion'] ,40,$this->e,STR_PAD_RIGHT);//DIRECCION-1-TER
            $datosBeneficiario .= str_repeat($this->e,40);//DIRECCION-2-TER
            $datosBeneficiario .= str_repeat($this->e,40);//DIRECCION-3-TER
            $datosBeneficiario .= $this->FormatearText2BATCH($datosTercero['telefono'] ,15,$this->e,STR_PAD_RIGHT);//TELEFONO-TER
            $datosBeneficiario .= $this->FormatearText2BATCH($datosTercero['fax'] ,15,$this->e,STR_PAD_RIGHT);//FAX-TER
            $datosBeneficiario .= str_repeat($this->e,10);//COD-POSTAL-TER
            $datosBeneficiario .= $this->FormatearText2BATCH($datosTercero['email'] ,50,$this->e,STR_PAD_RIGHT);//EMAIL-TER
            $datosBeneficiario .= str_repeat($this->c,1);//IND-SUC-TER
            $datosBeneficiario .= str_repeat($this->e,15);//BARRIO-TER
            $datosBeneficiario .= str_repeat($this->e,15);//TELEFONO-TER
            $datosBeneficiario .= str_repeat($this->c,1);//IND-SUC-P-TER
            $datosBeneficiario .= str_repeat($this->e,6);//CIIU-TER
            $datosBeneficiario .= str_repeat($this->e,58);//FILLER-TER

            $terceros[$tipo_id_tercero][$tercero_id] = $datosBeneficiario;
        }
        return $terceros[$tipo_id_tercero][$tercero_id];
    }//Fin GetTeceroSucursal

    /**
    * Retorna una fila vacia para los campos de tercero en el formato CGBATCH
    *
    * @return string
    * @access private
    */
    function GetTerceroNULL()
    {
        static $filaVacia;

        if(empty($filaVacia))
        {
            //CONSTRUIR FILA VACIA
            $filaVacia  = str_repeat($this->e,15);//NIT-TER
            $filaVacia .= str_repeat($this->c,1);//NIT-DV-TER
            $filaVacia .= str_repeat('A',1);//IDENT-TERCERO-TER
            $filaVacia .= str_repeat($this->e,50);//K3-NOMBRE1-TER
            $filaVacia .= str_repeat($this->e,50);//K4-NOMBRE2-TER
            $filaVacia .= str_repeat($this->e,15);//APELLIDO1-TER
            $filaVacia .= str_repeat($this->e,15);//APELLIDO2-TER
            $filaVacia .= str_repeat($this->e,20);//NOMBRES-TER
            $filaVacia .= str_repeat($this->c,1);//TIPO-TERC-TER
            $filaVacia .= str_repeat($this->c,1);//TIPO-IDENT-TER
            $filaVacia .= str_repeat($this->c,1);//IND-CLI-TER
            $filaVacia .= str_repeat($this->c,1);//IND-PRO-TER
            $filaVacia .= str_repeat($this->c,1);//IND-EMPL-TER
            $filaVacia .= str_repeat($this->c,1);//IND-ACCIO-TER
            $filaVacia .= str_repeat($this->c,1);//IND-VAR-TER
            $filaVacia .= str_repeat($this->c,1);//IND-INT-TER
            $filaVacia .= str_repeat($this->c,1);//IND-TARJ-TER
            $filaVacia .= str_repeat($this->e,1);//ESTADO-TER
            $filaVacia .= str_repeat($this->c,8);//FECHA-CRE-TER
            $filaVacia .= str_repeat($this->c,3);//PAIS-TER
            $filaVacia .= str_repeat($this->c,2);//DPTO-TER
            $filaVacia .= str_repeat($this->c,3);//CIUDAD-TER
            $filaVacia .= str_repeat($this->e,40);//DIRECCION-1-TER
            $filaVacia .= str_repeat($this->e,40);//DIRECCION-2-TER
            $filaVacia .= str_repeat($this->e,40);//DIRECCION-3-TER
            $filaVacia .= str_repeat($this->e,15);//TELEFONO-TER
            $filaVacia .= str_repeat($this->e,15);//FAX-TER
            $filaVacia .= str_repeat($this->e,10);//COD-POSTAL-TER
            $filaVacia .= str_repeat($this->e,50);//EMAIL-TER
            $filaVacia .= str_repeat($this->c,1);//IND-SUC-TER
            $filaVacia .= str_repeat($this->e,15);//BARRIO-TER
            $filaVacia .= str_repeat($this->e,15);//TELEFONO-TER
            $filaVacia .= str_repeat($this->c,1);//IND-SUC-P-TER
            $filaVacia .= str_repeat($this->e,6);//CIIU-TER
            $filaVacia .= str_repeat($this->e,58);//FILLER-TER
        }
        return $filaVacia;
    }

    /**
     * Retorna los datos de un tercero/cliente en el formato CGBATCH
     *
     * @param string $tipo_id_tercero Tipo de Id del tercero
     * @param string $tercero_id No. de Id del tercero
     * @param string dato solicitado default arreglo con todos los datos
     * @return string/array el valor del dato solicitado o un vector con todods los datos.
     * @access public
     */
    function GetTeceroCliente($tipo_id_tercero,$tercero_id,$dato=null)
    {
        static $filaVacia;
        if(empty($filaVacia))
        {
            //CONSTRUIR FILA VACIA
            $filaVacia  = str_repeat($this->e, 6);//CLASE-C-TER
            $filaVacia .= str_repeat($this->c, 3);//CO-C-TER
            $filaVacia .= str_repeat($this->e,6);//ZONA-C-TER
            $filaVacia .= str_repeat($this->e,4);//VEND-C-TER
            $filaVacia .= str_repeat($this->e,40);//CONTACTO-C-TER
            $filaVacia .= str_repeat($this->e,1);//CALIFICA-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-RETIVA-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-RETFTE-C-TER
            $filaVacia .= str_repeat($this->c,3);//IND-FILLER-C-TER

            $filaVacia .= str_repeat($this->e,2);//COND-PAGO-C-TER

            $filaVacia .= str_repeat($this->e,40);//OBSERVA-C-TER
            $filaVacia .= str_repeat($this->c,3);//DIAGS-C-TER
            $filaVacia .= str_repeat($this->c,11);//CUPO-CR-C-TER
            $filaVacia .= str_repeat($this->e,4);//CRITERIO1-C-TER
            $filaVacia .= str_repeat($this->e,4);//CRITERIO2-C-TER
            $filaVacia .= str_repeat($this->e,4);//CRITERIO3-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-BCUPO-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-BMORA-C-TER
            $filaVacia .= str_repeat($this->e,1);//ESTADO-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-OC-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-REMISION-C-TER
            $filaVacia .= str_repeat($this->e,3);//LISTA-PRECIO-C-TER
            $filaVacia .= str_repeat($this->e,2);//LISTA-DSCTO-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-DSCTOG-C-TER
            $filaVacia .= str_repeat($this->c,4);//DSCTOG1-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-EXCLUIDO-C-TER
            $filaVacia .= str_repeat($this->c,1);//FORMA-PAGO-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-ANTICIPO-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-CHEPOS-C-TER
            $filaVacia .= str_repeat($this->e,2);//MONEDA-C-TER
            $filaVacia .= str_repeat($this->e,40);//OBSERVA-VTA-C-TER
            $filaVacia .= str_repeat($this->e,40);//OBSERVA-CRE-C-TER
            $filaVacia .= str_repeat($this->e,2);//DCTOPP-C-TER
            $filaVacia .= str_repeat($this->c,1);//IND-RETICA-C-TER
            $filaVacia .= str_repeat($this->c,4);//RUTA-VIS-C-TER
            $filaVacia .= str_repeat($this->c,4);//RUTA-TRA-C-TER
            $filaVacia .= str_repeat($this->e,15);//BARRIO-C-TER
            $filaVacia .= str_repeat($this->e,1);//ESTADO-V-C-TER
            $filaVacia .= str_repeat($this->c,4);//CONTACTO-NAC-C-TER
            $filaVacia .= str_repeat($this->e,40);//CONTACTO-CRE-C-TER
            $filaVacia .= str_repeat($this->c,4);//CONT-NAC-CRE-C-TER
            $filaVacia .= str_repeat($this->c,4);//DSCTOG2-C-TER
            $filaVacia .= str_repeat($this->c,8);//FECHA-BLOQ-C-TER
            $filaVacia .= str_repeat($this->e,4);//PUNTO-ENVIO-C-TER
        }
        return $filaVacia;
    }//Fin GetTeceroCliente

    /**
     * Retorna los datos de un tercero/proveedor en el formato CGBATCH
     *
     * @param string $tipo_id_tercero Tipo de Id del tercero
     * @param string $tercero_id No. de Id del tercero
     * @param string dato solicitado default arreglo con todos los datos
     * @return string/array el valor del dato solicitado o un vector con todods los datos.
     * @access public
     */
    function GetTerceroProveedor($tipo_id_tercero,$tercero_id,$dato=null)
    {
        static $filaVacia;
        if(empty($filaVacia))
        {
            //CONSTRUIR FILA VACIA
            $filaVacia  = str_repeat($this->e,6);//CLASE-P-TER
            $filaVacia .= str_repeat($this->e,40);//CONTACTO-P-TER
            $filaVacia .= str_repeat($this->c,1);//IND-REGIM-P-TER
            $filaVacia .= str_repeat($this->c,1);//IND-AUTORET-P-TER
            $filaVacia .= str_repeat($this->c,1);//IND-RETIVA-P-TER
            $filaVacia .= str_repeat($this->c,1);//IND-RETICA-P-TER
            $filaVacia .= str_repeat($this->e,2);//COND-PAGO-P-TER
            $filaVacia .= str_repeat($this->c,3);//DIASG-P-TER
            $filaVacia .= str_repeat($this->c,11);//CUPO-CR-P-TER
            $filaVacia .= str_repeat($this->e,2);//MONEDA-P-TER
            $filaVacia .= str_repeat($this->e,1);//ESTADO-P-TER
            $filaVacia .= str_repeat($this->e,40);//OBSERVA-P-TER
            $filaVacia .= str_repeat($this->c,1);//IND-CONSIGNA-P-TER
            $filaVacia .= str_repeat($this->e,2);//COD-RETICA-P-TER
            $filaVacia .= str_repeat($this->e,2);//DESA-FE-P
            $filaVacia .= str_repeat($this->e,8);//DEST-FE-P
            $filaVacia .= str_repeat($this->c,4);//DSCTO-OTORG-P
            $filaVacia .= str_repeat($this->c,1);//METODO-PAGO-P
            $filaVacia .= str_repeat($this->c,2);//NRO-CTA-PAGO-P
            $filaVacia .= str_repeat($this->c,1);//FORMA-PAGO-P
        }
        return $filaVacia;
    }//GetTecerceroProveedor

    /**
     * Retorna los datos de un tercero/empleado en el formato CGBATCH
     *
     * @param string $tipo_id_tercero Tipo de Id del tercero
     * @param string $tercero_id No. de Id del tercero
     * @param string dato solicitado default arreglo con todos los datos
     * @return string/array el valor del dato solicitado o un vector con todods los datos.
     * @access public
     */
    function GetTerceroEmpleado($tipo_id_tercero,$tercero_id,$dato=null)
    {
        static $filaVacia;
        if(empty($filaVacia))
        {
            //CONSTRUIR FILA VACIA
            $filaVacia  = str_repeat($this->e,20);//CIUDAD-EXP-E-TER
            $filaVacia .= str_repeat($this->c,8);//FECHA-ING-E-TER
            $filaVacia .= str_repeat($this->c,8);//FECHA-NAC-E-TER
            $filaVacia .= str_repeat($this->c,3);//PAIS-E-TER
            $filaVacia .= str_repeat($this->c,2);//DPTO-E-TER
            $filaVacia .= str_repeat($this->c,3);//CIUDAD-E-TER
            $filaVacia .= str_repeat($this->e,1);//SEXO-E-TER
            $filaVacia .= str_repeat($this->e,1);//EST-CIVIL-E-TER
            $filaVacia .= str_repeat($this->e,1);//RUTA-E-TER
            $filaVacia .= str_repeat($this->c,3);//RUTA-ORD-E-TER
            $filaVacia .= str_repeat($this->e,4);//CARGO-E-TER
            $filaVacia .= str_repeat($this->e,50);//FILLER-E-TER
        }
        return $filaVacia;
    }//Fin GetTecerceroEmpleado

}//Fin de la clase
?>
