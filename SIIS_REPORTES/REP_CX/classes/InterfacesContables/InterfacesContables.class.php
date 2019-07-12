<?php
/**
* $Id: InterfacesContables.class.php,v 1.12 2007/06/27 19:05:46 alexgiraldo Exp $
*/

/**
* Clase para la generacion de interfaces contables.
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.12 $
* @package SIIS
*/
class InterfacesContables
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
    * Objecto de Interfaz
    *
    * @var object
    * @access private
    */
    var $OBJ;

    /**
    * Vector con informacion de la Interfaz Activa
    *
    * @var array
    * @access private
    */
    var $OBJ_DESCRIPCION;

    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function InterfacesContables()
    {
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
    * Metodo que retorna las interfaces contables de un empresa
    *
    * @param string $empresa_id
    * @return array
    * @access public
    */
    function GetListadoInterfaces($empresa_id)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT * FROM cg_conf.interfaces_contables WHERE empresa_id = '$empresa_id' AND sw_estado='1';";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACES CONTABLES - GetListadoInterfaces - E1";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while($fila=$result->FetchRow())
        {
            $retorno[$fila['interface_id']] = $fila;
        }
        $result->Close();

        return $retorno;
    }

    /**
    * Metodo que retorna un vector con la Interface Contable activa
    *
    * @return array
    * @access public
    */
    function GetDatosInterface()
    {
        if(!is_object($this->OBJ))
        {
            return null;
        }
        return $this->OBJ_DESCRIPCION;
    }


    /**
    * Metodo que configurar la Interface Contable a trabajar
    *
    * @param string $empresa_id
    * @param string $interface_id
    * @return array
    * @access public
    */
    function SetInterface($empresa_id,$interface_id)
    {
        unset($this->OBJ);
        unset($this->OBJ_DESCRIPCION);

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT * FROM cg_conf.interfaces_contables WHERE empresa_id = '$empresa_id' AND interface_id = '$interface_id';";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACES CONTABLES - SetInterface - E1";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "INTERFACES CONTABLES - SetInterface - E2";
            $this->mensajeDeError = "LA INTERFAZ [$interface_id] NO ESTA CONFIGURADA PARA LA EMPRESA [$empresa_id] EN LA TABLA [cg_conf.interfaces_contables].";
            return false;
        }

        $DatosInterfaz = $result->FetchRow();
        $result->Close();

        $CLASS_FILE = $interface_id . ".class.php";

        if (!IncludeClass($interface_id, 'InterfacesContables/INTERFACES'))
        {
            $this->error = "INTERFACES CONTABLES - SetInterface - E1";
            $this->mensajeDeError = "NO SE PUDO INCLUIR EL ARCHIVO DE LA CLASE [InterfacesContables/INTERFACES/$CLASS_FILE] .";
            return false;
        }

        if(!class_exists($interface_id))
        {
            $this->error = "INTERFACES CONTABLES - SetInterface - E2";
            $this->mensajeDeError = "NO EXISTE LA CLASE [$interface_id].";
            return false;
        }

        $this->OBJ = new $interface_id;

        if(!is_object($this->OBJ))
        {
            $this->error = "INTERFACES CONTABLES - SetInterface - E3";
            $this->mensajeDeError = "NO SE PUDO CREAR EL OBJETO DE INTERFAZ.";
            return false;
        }

        $this->OBJ_DESCRIPCION = $DatosInterfaz;

        return true;
    }

    /**
    * Metodo para generar la Interface Contable de un documento(prefijo) en un lapso contable
    *
    * @param string $prefijo
    * @param string $lapso
    * @param string $directorio (OPCIONAL) directorio para creacion de los archivos
    * @param string $nombre_archivo (OPCIONAL) nombre del archivo a crear.
    * @return array
    * @access public
    */
    function GenerarInterfaceDocumentoLapso($prefijo, $lapso, $dia_inicial=null, $dia_final=null, $directorio=null, $nombre_archivo=null)
    {
        if(!is_object($this->OBJ))
        {
            $this->error = "INTERFACES CONTABLES - GenerarInterfaceDocumentoLapso - E1";
            $this->mensajeDeError = "NO SE HA CONFIGURADO EL OBJETO DE INTERFAZ, UTILICE PRIMERO EL METODO SetInterface(empresa_id,interface_id).";
            return false;
        }

        $salida = $this->OBJ->GetInterfaceDocumentoLapso($this->OBJ_DESCRIPCION['empresa_id'], $prefijo, $lapso, $dia_inicial, $dia_final, $directorio=null, $nombre_archivo=null);

        if($salida===false)
        {
            if(empty($this->OBJ->error))
            {
                $this->error = "INTERFACES CONTABLES - GenerarInterfaceDocumentoLapso - E2";
                $this->mensajeDeError = "El metodo () del objeto de Interfaz Contable [".$this->OBJ_DESCRIPCION['interface_id']."] retorno false";
            }
            else
            {
                $this->error = $this->OBJ->Err();
                $this->mensajeDeError = $this->OBJ->ErrMsg();
            }
            return false;
        }

        if(file_exists($salida))
        {
            return $salida;
        }
        else
        {
            $this->error = "INTERFACES CONTABLES - GenerarInterfaceDocumentoLapso - E3";
            $this->mensajeDeError = "EL ARCHIVO QUE SE GENERO NO EXISTE [$salida].";
            return false;
        }

    }


    /**
    * Metodo para interfazar los documentos de un lapso contable.
    *
    * @param string $lapso
    * @param string $directorio (OPCIONAL) directorio para creacion de los archivos
    * @return array
    * @access public
    */
    function GenerarInterfaceLapso($lapso,$dia_inicial=null,$dia_final=null,$directorio=null)
    {
        if(!is_object($this->OBJ))
        {
            $this->error = "INTERFACES CONTABLES - GenerarInterfaceLapso - E1";
            $this->mensajeDeError = "NO SE HA CONFIGURADO EL OBJETO DE INTERFAZ, UTILICE PRIMERO EL METODO SetInterface(empresa_id,interface_id).";
            return false;
        }

        $empresa_id = $this->OBJ_DESCRIPCION['empresa_id'];


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

        $sql = "SELECT DISTINCT a.prefijo, b.descripcion
                FROM cg_mov_$empresa_id.cg_mov_contable_$empresa_id as a,
                     public.documentos as b
                WHERE a.empresa_id = '$empresa_id'
                      AND a.lapso = '$lapso'
                      $filtro_dias
                      AND b.documento_id = a.documento_id
                ;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACES CONTABLES - GetDocumentosInterfazarLapaso - E2";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "INTERFACES CONTABLES - GetDocumentosInterfazarLapaso - E3";
            $this->mensajeDeError = "NO HAY DOCUMENTOS PARA INTERFAZAR, EMPRESA [$empresa_id] LAPSO[$lapso].";
            return false;
        }

        $salidas = array();

        while($fila = $result->FetchRow())
        {
            $salidas[$fila['prefijo']]['descripcion'] = $fila['descripcion'];
            $salidas[$fila['prefijo']]['FILE'] = $this->GenerarInterfaceDocumentoLapso($fila['prefijo'], $lapso, $dia_inicial=null, $dia_final=null, $directorio);
            if($salidas[$fila['prefijo']]['FILE']===false)
            {
                $salidas[$fila['prefijo']]['Err'] = $this->error;
                $salidas[$fila['prefijo']]['ErrMsg'] = $this->mensajeDeError;
                $this->error = '';
                $this->mensajeDeError = '';
            }
        }

        $result->Close();

        return $salidas;
    }


    /**
    * Metodo para interfazar un grupo de documentos de un lapso contable.
    *
    * @param array $prefijos array con los prefijos de los documentos a interfazar.
    * @param string $lapso
    * @param string $directorio (OPCIONAL) directorio para creacion de los archivos
    * @return array
    * @access public
    */
    function GenerarInterfaceDocumentosLapso($prefijos,$lapso,$dia_inicial=null,$dia_final=null,$directorio=null)
    {
        if(!is_object($this->OBJ))
        {
            $this->error = "INTERFACES CONTABLES - GenerarInterfaceDocumentosLapso - E1";
            $this->mensajeDeError = "NO SE HA CONFIGURADO EL OBJETO DE INTERFAZ, UTILICE PRIMERO EL METODO SetInterface(empresa_id,interface_id).";
            return false;
        }

        if(!is_array($prefijos))
        {
            $this->error = "INTERFACES CONTABLES - GenerarInterfaceDocumentosLapso - E2";
            $this->mensajeDeError = "EL ARGUMENTO prefijos NO ES UN ARREGLO DE PREFIJOS.";
            return false;
        }

        $empresa_id = $this->OBJ_DESCRIPCION['empresa_id'];

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql= "SELECT prefijo,descripcion FROM public.documentos;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACES CONTABLES - GenerarInterfaceDocumentosLapso - E3";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $DESCRIPCION_DOC=array();
        while($file = $result->FetchRow())
        {
            $DESCRIPCION_DOC[$file['prefijo']]=$file['descripcion'];
        }
        $result->Close();


        $salidas = array();

        foreach($prefijos as $prefijo)
        {
            $salidas[$prefijo]['descripcion'] = $DESCRIPCION_DOC[$prefijo];
            $salidas[$prefijo]['FILE'] = $this->GenerarInterfaceDocumentoLapso($prefijo, $lapso, $dia_inicial, $dia_final, $directorio);
            if($salidas[$prefijo]['FILE']===false)
            {
                $salidas[$prefijo]['Err'] = $this->error;
                $salidas[$prefijo]['ErrMsg'] = $this->mensajeDeError;
                $this->error = '';
                $this->mensajeDeError = '';
            }
        }

        $result->Close();

        return $salidas;
    }


    /**
    * Metodo para obtener la informacion de los documentos creados en un lapso contable.
    *
    * @param string $empresa_id
    * @param string $lapso
    * @return array
    * @access public
    */
    function GetInformacionDocumentosLapso($empresa_id,$lapso,$dia_inicial=null,$dia_final=null)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql= "SELECT prefijo,descripcion FROM public.documentos;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACES CONTABLES - GetInformacionDocumentosLapso - E2";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $DESCRIPCION_DOC=array();
        while($file = $result->FetchRow())
        {
            $DESCRIPCION_DOC[$file['prefijo']]=$file['descripcion'];
        }
        $result->Close();

        $sql="
                SELECT DISTINCT
                    a.tipo_doc_general_id,
                    a.descripcion
                FROM
                    public.tipos_doc_generales as a,
                    public.documentos as b
                WHERE
                    a.sw_doc_sistema = '1'
                    AND b.empresa_id = '$empresa_id'
                    AND b.tipo_doc_general_id = a.tipo_doc_general_id
                    AND b.sw_contabiliza = '1'
                    AND b.sw_estado = '1'
            ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACES CONTABLES - GetInformacionDocumentosLapso - E3";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $salidas = array();


        if(is_numeric($dia_inicial) && $dia_inicial>0)
        {
            $dia_inicial_f = substr($lapso, 0, 4)."-".substr($lapso, 4, 2)."-".str_pad($dia_inicial, 2, "0", STR_PAD_LEFT);

            if(is_numeric($dia_final) && $dia_final>0)
            {
                $dia_final_f = substr($lapso, 0, 4)."-".substr($lapso, 4, 2)."-".str_pad($dia_final, 2, "0", STR_PAD_LEFT);

                $filtro_dias = "AND (fecha_documento >= '$dia_inicial_f' AND fecha_documento <= '$dia_final_f')";

            }
            else
            {
                $filtro_dias = "AND fecha_documento = '$dia_inicial_f' ";
            }
        }
        else
        {
            $filtro_dias = "";
        }

        while($fila = $result->FetchRow())
        {
            $sql = "
                    SELECT
                    prefijo,
                    COUNT(*) as cantidad,
                    MIN(numero) as numero_inicial,
                    MAX(numero) as numero_final,
                    (COUNT(*) - (MAX(numero) -  MIN(numero) + 1)) as huecos
                    FROM cg_mov_$empresa_id.\"cg_mov_contable_$empresa_id"."_".$fila['tipo_doc_general_id']."\"
                    WHERE prefijo IN (SELECT DISTINCT prefijo FROM public.documentos WHERE sw_contabiliza = '1' AND sw_estado = '1' AND tipo_doc_general_id = '".$fila['tipo_doc_general_id']."')
                    AND lapso = '$lapso'
                    $filtro_dias
                    GROUP BY prefijo
                    ORDER BY prefijo
            ";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $res_doc = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $salidas[$fila['tipo_doc_general_id']] = "ERROR 1 : ".$dbconn->ErrorMsg();
            }
            elseif(!$res_doc->EOF)
            {
                while($fila2 = $res_doc->FetchRow())
                {
                    $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']] = $fila2;

                    $sql = "SELECT COUNT(*) as sin_contabilizar FROM cg_mov_$empresa_id.\"cg_mov_contable_$empresa_id"."_".$fila['tipo_doc_general_id']."\"
                            WHERE prefijo = '".$fila2['prefijo']."'
                            AND lapso = '$lapso'
                            $filtro_dias
                            AND documento_contable_id IS NULL; ";

                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $res_sin = $dbconn->Execute($sql);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                    if($dbconn->ErrorNo() != 0)
                    {
                        $salidas[$fila['tipo_doc_general_id']] = "ERROR 2 : ".$dbconn->ErrorMsg();
                    }
                    else
                    {
                        $fila3 = $res_sin->FetchRow();
                        $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']]['descuadrados']=0;
                        $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']]['sin_contabilizar']=$fila3['sin_contabilizar'];
                        $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']]['descripcion']= $DESCRIPCION_DOC[$fila2['prefijo']];
                        $res_sin->Close();

                        $sql = "SELECT COUNT(*) as descuadrados FROM cg_mov_$empresa_id.\"cg_mov_contable_$empresa_id"."_".$fila['tipo_doc_general_id']."\"
                                WHERE prefijo = '".$fila2['prefijo']."'
                                AND lapso = '$lapso'
                                $filtro_dias
                                AND sw_estado = '1'; ";

                        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                        $res_sin = $dbconn->Execute($sql);
                        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                        if($dbconn->ErrorNo() != 0)
                        {
                            $salidas[$fila['tipo_doc_general_id']] = "ERROR 3 : ".$dbconn->ErrorMsg();
                        }
                        else
                        {
                            $fila4 = $res_sin->FetchRow();
                            $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']]['descuadrados']=$fila4['descuadrados'];
                            $res_sin->Close();
                        }
                    }
                }
               $res_doc->Close();
            }
        }
        $result->Close();

        $sql = "SELECT
                    prefijo,
                    COUNT(*) as cantidad,
                    MIN(numero) as numero_inicial,
                    MAX(numero) as numero_final,
                    (COUNT(*) - (MAX(numero) -  MIN(numero) + 1)) as huecos
                FROM cg_mov_$empresa_id.\"cg_mov_contable_$empresa_id\"
                WHERE prefijo IN (
                    SELECT DISTINCT
                        b.prefijo
                    FROM
                        public.tipos_doc_generales as a,
                        public.documentos as b
                    WHERE
                        a.sw_doc_sistema = '0'
                        AND b.empresa_id = '$empresa_id'
                        AND b.tipo_doc_general_id = a.tipo_doc_general_id
                        AND b.sw_contabiliza = '1'
                        AND b.sw_estado = '1'
                )
                AND lapso = '$lapso'
                $filtro_dias
                GROUP BY prefijo;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "INTERFACES CONTABLES - GetInformacionDocumentosLapso - E4";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        while($fila = $result->FetchRow())
        {
            $salidas['MANUALES'][$fila['prefijo']] = $fila;
            $salidas['MANUALES'][$fila['prefijo']]['sin_contabilizar'] = 0;
            $salidas['MANUALES'][$fila['prefijo']]['descripcion']= $DESCRIPCION_DOC[$fila['prefijo']];
        }

        return $salidas;
    }

}
