<?php
/**
* $Id: CGUNO85.class.php,v 1.6 2007/04/26 16:08:36 alexgiraldo Exp $
*/

/**
* Clase para la generacion de interfaces contables.
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.6 $
* @package SIIS
*/
class CGUNO85
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
    * Espacio en blanco
    *
    * @var string
    * @access private
    */
    var $e;

   /**
    * Caracter cero
    *
    * @var string
    * @access private
    */
    var $c;

   /**
    * Salto de Linea
    *
    * @var string
    * @access private
    */
    var $EOL;

   /**
    * Directorio por defecto para la creacion de las interfaces
    *
    * @var string
    * @access private
    */
    var $DIR_DEFAULT;

   /**
    * Nombre por defecto para los archivos de las interfaces (sin extencion)
    *
    * @var string
    * @access private
    */
    var $FILE_DEFAULT;

   /**
    * Nombre del archivo de interface creado.
    *
    * @var string
    * @access private
    */
    var $FILE_BATCH;

   /**
    * Nombre del archivo de interfaces creadas en la ejecucion actual.
    *
    * @var string
    * @access private
    */
    var $FILE_RESUMEN;

    /**
    * Configuracion de la interfaz.
    *
    * @var array
    * @access private
    */
    var $CONF_CGUNO85;

   /**
    * Recurso del archivo de interface
    *
    * @var string
    * @access private
    */
    var $handle;

    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function CGUNO85()
    {
        $this->e = " ";
        $this->c = "0";
        $this->handle = null;
        $this->DIR_DEFAULT  = "Interface_Files/InterfaceCG1";
        $this->FILE_DEFAULT = "CGBATCH";
        $this->FILE_BATCH = '';
        $this->FILE_RESUMEN = '';
        $this->EOL = "\n";
        return true;
    }


    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }


    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }


    /**
    * Metodo para generar la Interface Contable de un documento(prefijo) en un lapso contable
    *
    * @param string $empresa_id
    * @param string $prefijo
    * @param string $lapso
    * @param string $directorio dafault null
    * @param string $nombre_archivo dafault null
    * @return array
    * @access public
    */
    function GetInterfaceDocumentoLapso($empresa_id, $prefijo, $lapso, $dia_inicial=null, $dia_final=null, $directorio=null, $nombre_archivo=null)
    {
        $SHEMA     = "cg_mov_$empresa_id";
        $TBL_MOV   = "cg_mov_contable_$empresa_id";
        $TBL_MOV_D = "cg_mov_contable_$empresa_id" . "_$lapso";

        if(is_numeric($dia_inicial) && $dia_inicial>0)
        {
            $dia_inicial_f = substr($lapso, 0, 4)."-".substr($lapso, 4, 2)."-".str_pad($dia_inicial, 2, "0", STR_PAD_LEFT);

            if(is_numeric($dia_final) && $dia_final>0)
            {
                $dia_final_f = substr($lapso, 0, 4)."-".substr($lapso, 4, 2)."-".str_pad($dia_final, 2, "0", STR_PAD_LEFT);

                $filtro_dias = "AND (a.fecha_documento >= '$dia_inicial_f' AND a.fecha_documento <= '$dia_final_f')";

            }
            else
            {
                $filtro_dias = "AND a.fecha_documento = '$dia_inicial_f' ";
            }
        }
        else
        {
            $filtro_dias = "";
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                    a.lapso,
                    a.fecha_documento,
                    a.empresa_id,
                    a.prefijo,
                    a.numero,
                    a.documento_id,
                    a.sw_estado,
                    a.tipo_bloqueo_id,
                    a.total_debitos,
                    a.total_creditos,
                    a.tipo_id_tercero,
                    a.tercero_id,
                    a.fecha_registro,
                    a.usuario_id,
                    b.documento_cruce_id,
                    b.cuenta,
                    b.tipo_id_tercero as tipo_id_tercero_mov,
                    b.tercero_id as tercero_id_mov,
                    b.debito,
                    b.credito,
                    b.detalle,
                    b.centro_de_costo_id,
                    b.base_rtf,
                    b.porcentaje_rtf,
                    c.empresa_id as empresa_id_cruce,
                    c.prefijo as prefijo_cruce,
                    c.numero as numero_cruce

                FROM $SHEMA.$TBL_MOV as a, $SHEMA.$TBL_MOV_D as b LEFT JOIN  $SHEMA.$TBL_MOV as c ON (c.documento_contable_id = b.documento_cruce_id)
                WHERE a.documento_contable_id = b.documento_contable_id
                    AND a.lapso = '$lapso'
                    $filtro_dias
                    AND a.sw_estado = '2'
                    AND (a.empresa_id = '$empresa_id' AND a.prefijo = '$prefijo')
                ORDER BY numero;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACE CONTABLE CGUNO85 - GetInterfaceDocumentoLapso - E1";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "INTERFACE CONTABLE CGUNO85 - GetInterfaceDocumentoLapso - E2";
            $this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA INTERFAZAR.";
            return false;
        }

        $REGISTROS = $result->GetRows();
        $result->Close();

        if($this->SetInterfaceFile($empresa_id, $prefijo, $lapso, $directorio, $nombre_archivo)===false)
        {
            if(empty($this->error))
            {
                $this->error = "INTERFACE CONTABLE CGUNO85 - GetInterfaceDocumentoLapso - E3";
                $this->mensajeDeError = "El metodo SetInterfaceFile() retorno false";
            }
            return false;
        }

        $TOTAL_C = 0;
        $TOTAL_D = 0;

        foreach($REGISTROS as $NUM_REG=>$REG)
        {
            if(!$this->AddLine($NUM_REG,&$REG))
            {
                if(empty($this->error))
                {
                    $this->error = "INTERFACE CONTABLE CGUNO85 - GetInterfaceDocumentoLapso - E4";
                    $this->mensajeDeError = "El metodo AddLine() retorno false";
                }

                $this->CerrarArchivo();
                return false;
            }
            $TOTAL_C += $REG['debito'];
            $TOTAL_D += $REG['credito'];
        }

        $this->CerrarArchivo();

        //CREAR RESUMEN POR DOCUMENTO
        $DATOS['EMPRESA'] = $this->CONF_CGUNO85['empresa_id'];
        $DATOS['LAPSO'] = $this->CONF_CGUNO85['lapso'];
        $DATOS['PREFIJO'] = $this->CONF_CGUNO85['prefijo'];
        $DATOS['DOCUMENTO INICIAL'] = trim($REGISTROS[0]['prefijo']) . ' ' . trim($REGISTROS[0]['numero']);
        $DATOS['DOCUMENTO FINAL']   = trim($REGISTROS[count($REGISTROS)-1]['prefijo']) . ' ' . trim($REGISTROS[count($REGISTROS)-1]['numero']);
        $DATOS['NUMERO DE DOCUMENTOS'] = count($REGISTROS);
        $DATOS['TOTAL CREDITOS'] = "$".FormatoValor($TOTAL_C);
        $DATOS['TOTAL DEBITOS'] = "$".FormatoValor($TOTAL_D);

        $this->GenerarResumen($DATOS);

        return $this->FILE_BATCH;
    }


    /**
    * Metodo agregar un registro al archivo de Interface
    *
    * @param integer $NUM_REG
    * @param array $REG
    * @return boolean True si se ejecuto correctamente de lo contrario false.
    * @access private
    */
    function AddLine($NUM_REG,$REG)
    {
        // DATOS REFERENTES AL DOCUMENTO
        //--------------------------------
        $line  = $this->FormatearText2BATCH($NUM_REG+1,9,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//K1-CONSECUTIVO
        $line .= $this->FormatearText2BATCH($this->CONF_CGUNO85['codigo_empresa'],2,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//EMP-MOV
        $line .= $this->FormatearText2BATCH($this->CONF_CGUNO85['codigo_cu'],3,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//CO-MOV
        $line .= $this->FormatearText2BATCH($REG['prefijo'],2,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//TIPO-MOV

        $numero_doc = (string) $REG['numero'];
        $numero_doc = trim($numero_doc);
        if(strlen($numero_doc)>6) $numero_doc = substr($numero_doc,-6);

        $line .= $this->FormatearText2BATCH($numero_doc,6,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//NRO-MOV
        $line .= $this->FormatearText2BATCH($REG['tercero_id'],13,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//TERC-DOC
        $line .= str_repeat ($this->c, 2);//SUC-DOC

        $FechaDoc=str_replace("/","-",$REG['fecha_documento']);
        $FechaDoc=explode(" ",$FechaDoc);
        $FechaDoc=explode("-",$FechaDoc[0]);
        $line .= date("Ymd",mktime(0,0,0,$FechaDoc[1],$FechaDoc[2],$FechaDoc[0]));//FECHA-MOV

        $line .= $this->CONF_CGUNO85['lapso'];//LAPSO-MOV
        $line .= str_repeat($this->e,8);//LOTE-DOC
        $line .= str_repeat($this->e,8);//DCTO-ALT-DOC
        $line .= str_repeat($this->e,40);//NOMCLI-DOC
        $line .= str_repeat($this->e,13);//NITCLI-DOC

        //VALOR-DOC

        $valorX = explode(".",$REG['total_debitos']);
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

        $line .= $this->FormatearText2BATCH($REG['cuenta'],8,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//CUENTA-MOV

        //TERC-MOV
        if(!empty($REG['tercero_id_mov']))
        {
            $line .= $this->FormatearText2BATCH($REG['tercero_id_mov'],13,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);
        }
        else
        {
            $line .= str_repeat ($this->e, 13);
        }

        $line .= str_repeat ($this->c, 2);//SUC-MOV

        $line .= $this->FormatearText2BATCH($this->CONF_CGUNO85['codigo_cu'],3,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//CO-MOVTO-MOV

        //DC-MOV
        if($REG['debito']<>0)
        {
            $line .= 'D';
            $valorX = explode(".",$REG['debito']);
            $valorParteEntera  = $valorX[0];
            $valorParteDecimal = $valorX[1];

            //$valorParteDecimal = round(((abs($REG['debito']) - round(abs($REG['debito']),0 ))*100),0);
            // echo $REG['debito']." - ".$valorParteEntera ." - " . $valorParteDecimal . "<br>";

        }
        else
        {
            $line .= 'C';
            $valorX = explode(".",$REG['credito']);
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

        if($REG['base_rtf'])
        {

            $valorX = explode(".",$REG['base_rtf']);
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

            $valorX = explode(".",$REG['porcentaje_rtf']);
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


        $detalle1 = $REG['detalle'];
        $detalle2 = "";

        //DEALLE1-MOV Y DETALLE2-MOV
        $line .= $this->FormatearText2BATCH($detalle1,40,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);
        $line .= $this->FormatearText2BATCH($detalle2,40,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);

        //CCOSTO-MOV
        if(!empty($REG['centro_de_costo_id']))
        {

            $line .= $this->FormatearText2BATCH($REG['centro_de_costo_id'],8,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);
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


        if($REG['empresa_id_cruce'])
        {
            $line .= $this->FormatearText2BATCH($REG['prefijo_cruce'],2,$relleno=$this->e,$tipo_relleno=STR_PAD_RIGHT);//TIPO-MOV

            $numero_doc = (string) $REG['numero_cruce'];
            $numero_doc = trim($numero_doc);
            if(strlen($numero_doc)>6) $numero_doc = substr($numero_doc,-6);

            $line .= $this->FormatearText2BATCH($numero_doc,6,$relleno=$this->c,$tipo_relleno=STR_PAD_LEFT);//NRO-MOV
            $line .= str_repeat ($this->c, 2);//NRO-CUOTA-CRU-CRUC
            $line .= date("Ymd",mktime(0,0,0,$FechaDoc[1]+1,$FechaDoc[2],$FechaDoc[0]));
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
        $line .= $this->GetTercero(trim($REG['tipo_id_tercero_mov']),trim($REG['tercero_id_mov']));
        $line .= $this->GetTeceroCliente(trim($REG['tipo_id_tercero_mov']),trim($REG['tercero_id_mov']));
        $line .= $this->GetTerceroProveedor(trim($REG['tipo_id_tercero_mov']),trim($REG['tercero_id_mov']));
        $line .= $this->GetTerceroEmpleado(trim($REG['tipo_id_tercero_mov']),trim($REG['tercero_id_mov']));
        $line .= $this->EOL;

        if(!fwrite($this->handle,$line))
        {
            $this->error = "INTERFACE CONTABLE CGUNO85 - AddLine - E1";
            $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [".$this->FILE_BATCH."].";
            return false;
        }

        return true;
    }


    /**
    * Metodo para configurar los archivos de Interface
    *
    * @param string $empresa_id
    * @param string $prefijo
    * @param string $lapso
    * @param string $directorio dafault null
    * @param string $nombre_archivo dafault null
    * @return boolean True si se ejecuto correctamente de lo contrario false.
    * @access private
    */
    function SetInterfaceFile($empresa_id, $prefijo, $lapso, $directorio=null, $nombre_archivo=null)
    {
        if($this->handle)
        {
            $this->CerrarArchivo();
        }

        $this->FILE_BATCH = '';
        $this->CONF_CGUNO85 = null;

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="SELECT * FROM cg_conf.interface_contable_cguno85 WHERE empresa_id='$empresa_id';";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACES CONTABLES - SetInterfaceFile - E1";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "INTERFACES CONTABLES - SetInterfaceFile - E2";
            $this->mensajeDeError = "La tabla [cg_conf.interface_contable_cguno85] de configuracion de la interface no esta llena para la empresa [$empresa_id].";
            return false;
        }

        $this->CONF_CGUNO85 = $result->FetchRow();
        $result->Close();

        $this->CONF_CGUNO85['lapso'] = $lapso;
        $this->CONF_CGUNO85['prefijo'] = $prefijo;


        if(!$directorio)
        {
            $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;
        }

        if(!is_dir($directorio))
        {
            $this->error = "INTERFACE CONTABLE CGUNO85 - SetInterfaceFile - E3";
            $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
            return false;
        }

        if(!is_writable($directorio))
        {
            $this->error = "INTERFACE CONTABLE CGUNO85 - SetInterfaceFile - E4";
            $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
            return false;
        }

        $dir_lapso = $directorio . "/" . $empresa_id ."_".$lapso;

        if(!is_dir($dir_lapso))
        {
           // umask(0777);
            if(!mkdir($dir_lapso, 0777))
            {
                $this->error = "INTERFACE CONTABLE CGUNO85 - SetInterfaceFile - E5";
                $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_lapso";
                return false;
            }
        }

        if(!is_writable($dir_lapso))
        {
            $this->error = "INTERFACE CONTABLE CGUNO85 - SetInterfaceFile - E6";
            $this->mensajeDeError = "EL DIRECTORIO $dir_lapso SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
            return false;
        }

        if(!$nombre_archivo)
        {
            $nombre_archivo = $this->FILE_DEFAULT.".$prefijo";
        }

        $FILE_BATCH = $dir_lapso . "/" . $nombre_archivo;

        $archivo = fopen($FILE_BATCH,'w');

        if(!$archivo)
        {
            $this->error = "INTERFACE CONTABLE CGUNO85 - SetInterfaceFile - E7";
            $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $FILE_BATCH;
            return false;
        }

        $this->FILE_BATCH = $FILE_BATCH;
        $this->handle = $archivo;

        return true;
    }


    /**
    * Metodo para generar los archivos de resumen
    *
    * @param array $DATOS
    * @return boolean True si se ejecuto correctamente de lo contrario false.
    * @access private
    */
    function GenerarResumen($DATOS)
    {
        $partes_ruta = pathinfo($this->FILE_BATCH);

        $texto = '';
        foreach($DATOS as $v=>$k)
        {
            $texto .= $this->FormatearText2BATCH($v,30,' ');
            $texto .= ":  ";
            $texto .= $k;
            $texto .= $this->EOL;
        }
        $texto .= $this->EOL;
        $texto .= $this->EOL;

        if(empty($this->FILE_RESUMEN))
        {
            $this->FILE_RESUMEN = $partes_ruta['dirname'] . "/RESUMEN.TXT";
            $archivo = fopen($this->FILE_RESUMEN,'w');
        }
        else
        {
            $archivo = fopen($this->FILE_RESUMEN,'a+');
        }

        if(!$archivo) return false;
        fwrite($archivo,$texto);
        fclose($archivo);

        $FILE .= $partes_ruta['dirname'] . "/RESUMEN_" . $partes_ruta['extension'] . ".TXT";
        $archivo = fopen($FILE,'w');
        if(!$archivo) return false;
        fwrite($archivo,$texto);
        fclose($archivo);

        return true;
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
    function CerrarArchivo()
    {
        if(!fclose($this->handle))
        {
            $this->handle = null;
            return false;
        }

        $this->handle = null;
        return true;
    }

    /**
    * Funcion para dar formato a los campos del CGBATCH
    *
    * @param string $cadena Cadena a formatear
    * @param integer $len tam� de salida de la cadena
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
}
