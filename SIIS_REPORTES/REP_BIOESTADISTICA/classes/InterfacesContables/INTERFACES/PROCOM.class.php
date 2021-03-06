<?php
/**
* $Id: PROCOM.class.php,v 1.3 2007/04/26 16:08:36 alexgiraldo Exp $
*/

/**
* Clase para la generacion de interfaces contables.
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.3 $
* @package SIIS
*/
class PROCOM
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
    * indica si debe colocar titulos al archivo de interfaces creadas en la ejecucion actual.
    *
    * @var string
    * @access private
    */
    var $FILE_RESUMEN_TITLE;

   /**
    * Salto de Linea
    *
    * @var string
    * @access private
    */
    var $EOL;

   /**
    * Recurso del archivo de interface
    *
    * @var string
    * @access private
    */
    var $handle;

   /**
    * Recurso del archivo de interface
    *
    * @var string
    * @access private
    */
    var $handle_resumen;

    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function PROCOM()
    {
        $this->handle = null;
        $this->handle_resumen = null;
        $this->DIR_DEFAULT  = "Interface_Files/PROCOM";
        $this->FILE_DEFAULT = "PROCOM";
        $this->FILE_BATCH = '';
        $this->FILE_RESUMEN = '';
        $this->FILE_RESUMEN_TITLE = true;
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

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT tipo_doc_general_id FROM public.documentos WHERE empresa_id = '$empresa_id' AND prefijo = '$prefijo';";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACE CONTABLE PROCOM - GetInterfaceDocumentoLapso - E1";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "INTERFACE CONTABLE PROCOM - GetInterfaceDocumentoLapso - E2";
            $this->mensajeDeError = "NO SE ENCONTRO EL PREFIJO [$prefijo] DE LA EMPRESA [$empresa_id] EN LA TABLA DOCUMENTOS.";
            return false;
        }

        list($tipo_doc_general_id) = $result->FetchRow();
        $result->Close();

        switch($tipo_doc_general_id)
        {
            case 'FV01':
            break;

            default:
            $this->error = "INTERFACE CONTABLE PROCOM - GetInterfaceDocumentoLapso - E3";
            $this->mensajeDeError = "EL DOCUMENTO CON PREFIJO [$prefijo] DE LA EMPRESA [$empresa_id] NO GENERA INTERFACE CON PROCOM.";
            return false;
        }


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

        $sql = "
                SELECT x.*,
                btrim(p.primer_nombre||' '||p.segundo_nombre||' ' ||p.primer_apellido||' '||p.segundo_apellido,'') as paciente

                FROM
                (
                    SELECT
                            btrim(a.prefijo || a.numero) as documento,
                            a.fecha_documento,
                            d.fecha_vencimiento_factura,
                            e.num_contrato,
                            a.total_debitos,
                            d.total_factura,
                            COALESCE(b.debito,0) AS cxc,
                            round((d.total_factura - COALESCE(b.debito,0)) / d.total_factura * 100) AS porcentaje,
                            c.envio_id,
                            d.sw_clase_factura,
                            d.empresa_id,
                            d.prefijo,
                            d.factura_fiscal

                    FROM    $SHEMA.$TBL_MOV as a
                            LEFT JOIN $SHEMA.$TBL_MOV_D as b ON (a.documento_contable_id = b.documento_contable_id AND cuenta LIKE '1305%')
                            LEFT JOIN
                                (
                                    SELECT DISTINCT env_d.empresa_id,
                                                    env_d.prefijo,
                                                    env_d.factura_fiscal as numero,
                                                    env_d.envio_id
                                    FROM
                                        envios_detalle as env_d,
                                        envios as env
                                    WHERE
                                        env.sw_estado != '2'
                                        AND env.envio_id = env_d.envio_id
                                ) as c
                                ON (c.empresa_id = a.empresa_id AND c.prefijo = a.prefijo AND c.numero = a.numero),
                            fac_facturas d LEFT JOIN planes e ON (e.plan_id = d.plan_id)

                    WHERE   a.lapso = '$lapso'
                            AND a.sw_estado = '2'
                            $filtro_dias
                            AND (a.empresa_id = '$empresa_id' AND a.prefijo = '$prefijo')
                            AND d.empresa_id = a.empresa_id
                            AND d.prefijo = a.prefijo
                            AND d.factura_fiscal = a.numero
                            AND d.sw_clase_factura = '1'
                    ORDER BY documento
                ) AS x,
                fac_facturas_cuentas as fc,
                cuentas ct,
                ingresos i,
                pacientes p

                WHERE
                fc.empresa_id=x.empresa_id
                AND fc.prefijo = x.prefijo
                AND fc.factura_fiscal = x.factura_fiscal
                AND ct.numerodecuenta=fc.numerodecuenta
                AND i.ingreso = ct.ingreso
                AND p.paciente_id = i.paciente_id
                AND p.tipo_id_paciente = i.tipo_id_paciente;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACE CONTABLE PROCOM - GetInterfaceDocumentoLapso - E4";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "INTERFACE CONTABLE PROCOM - GetInterfaceDocumentoLapso - E5";
            $this->mensajeDeError = "NO SE ENCONTRARON FACTURAS CREDITO PARA INTERFAZAR.";
            return false;
        }

        $REGISTROS = $result->GetRows();
        $result->Close();

        if($this->SetInterfaceFile($empresa_id, $prefijo, $lapso, $directorio, $nombre_archivo)===false)
        {
            if(empty($this->error))
            {
                $this->error = "INTERFACE CONTABLE PROCOM - GetInterfaceDocumentoLapso - E6";
                $this->mensajeDeError = "El metodo SetInterfaceFile() retorno false";
            }
            return false;
        }

        foreach($REGISTROS as $NUM_REG=>$REG)
        {
            if(!$this->AddLine($NUM_REG,&$REG))
            {
                if(empty($this->error))
                {
                    $this->error = "INTERFACE CONTABLE PROCOM - GetInterfaceDocumentoLapso - E7";
                    $this->mensajeDeError = "El metodo AddLine() retorno false";
                }

                $this->CerrarArchivo();
                return false;
            }
        }

        $this->CerrarArchivo(&$this->handle_resumen);
        $this->CerrarArchivo(&$this->handle);

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
        if($NUM_REG === 0)
        {
            $line_titulos = '';

            foreach($REG as $k=>$v)
            {
                $line_titulos .= "\"$k\",";
            }

            if(substr($line_titulos, -1, 1)== ',')
            {
                $line_titulos = substr($line_titulos, 0, -1);
            }

            $line_titulos .= $this->EOL;

            if(!fwrite($this->handle,$line_titulos))
            {
                $this->error = "INTERFACE CONTABLE PROCOM - AddLine - E1";
                $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [".$this->FILE_BATCH."].";
                return false;
            }

            if($this->FILE_RESUMEN_TITLE)
            {
                if(!fwrite($this->handle_resumen,$line_titulos))
                {
                    $this->error = "INTERFACE CONTABLE PROCOM - AddLine - E1";
                    $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO DE RESUMEN [".$this->FILE_RESUMEN."].";
                    return false;
                }

                $this->FILE_RESUMEN_TITLE = false;
            }

        }

        $line = '';

        foreach($REG as $k=>$v)
        {
            $line .= "\"$v\",";
        }


        if(substr($line, -1, 1)== ',')
        {
            $line = substr($line, 0, -1);
        }

        $line .= $this->EOL;

        if(!fwrite($this->handle,$line))
        {
            $this->error = "INTERFACE CONTABLE PROCOM - AddLine - E1";
            $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [".$this->FILE_BATCH."].";
            return false;
        }

        if(!fwrite($this->handle_resumen,$line))
        {
            $this->error = "INTERFACE CONTABLE PROCOM - AddLine - E1";
            $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO DE RESUMEN[".$this->FILE_RESUMEN."].";
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
            $this->CerrarArchivo(&$this->handle);
        }

        if($this->handle_resumen)
        {
            $this->CerrarArchivo(&$this->handle_resumen);
        }

        $this->FILE_BATCH = '';

        $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

        if(!is_dir($directorio))
        {
            $this->error = "INTERFACE CONTABLE PROCOM - SetInterfaceFile - E3";
            $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
            return false;
        }

        if(!is_writable($directorio))
        {
            $this->error = "INTERFACE CONTABLE PROCOM - SetInterfaceFile - E4";
            $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
            return false;
        }

        $dir_lapso = $directorio . "/" . $empresa_id ."_".$lapso;

        if(!is_dir($dir_lapso))
        {
           // umask(0777);
            if(!mkdir($dir_lapso, 0777))
            {
                $this->error = "INTERFACE CONTABLE PROCOM - SetInterfaceFile - E5";
                $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_lapso";
                return false;
            }
        }

        if(!is_writable($dir_lapso))
        {
            $this->error = "INTERFACE CONTABLE PROCOM - SetInterfaceFile - E6";
            $this->mensajeDeError = "EL DIRECTORIO $dir_lapso SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
            return false;
        }

        $nombre_archivo = $this->FILE_DEFAULT.".$prefijo";

        $FILE_BATCH = $dir_lapso . "/" . $nombre_archivo;

        $archivo = fopen($FILE_BATCH,'w');

        if(!$archivo)
        {
            $this->error = "INTERFACE CONTABLE PROCOM - SetInterfaceFile - E7";
            $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $FILE_BATCH;
            return false;
        }

        $this->FILE_BATCH = $FILE_BATCH;
        $this->handle = $archivo;

        //ARCHIVO DE RESUMEN
        $this->FILE_RESUMEN = '';
        $FILE_BATCH_RESUMEN = $dir_lapso . "/PROCOM.CSV" ;

        if(!$this->FILE_RESUMEN_TITLE)
        {
            $archivo = fopen($FILE_BATCH_RESUMEN,'a+');
        }
        else
        {
            $archivo = fopen($FILE_BATCH_RESUMEN,'w');
        }

        if(!$archivo)
        {
            $this->error = "INTERFACE CONTABLE PROCOM - SetInterfaceFile - E8";
            $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO DE RESUMEN fopen() no pudo abrir : ' . $FILE_BATCH_RESUMEN;
            return false;
        }

        $this->FILE_RESUMEN = $FILE_BATCH_RESUMEN;
        $this->handle_resumen = $archivo;

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
    function CerrarArchivo($handle)
    {
        if(!fclose($handle))
        {
            $handle = null;
            return false;
        }

        $handle = null;
        return true;
    }

}
