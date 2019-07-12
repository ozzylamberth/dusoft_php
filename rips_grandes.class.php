 <?php
// rips.class.php  10/02/2004
// -------------------------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 IPSOFT SA.
// Email: mail@ipsoft-sa.com
// -------------------------------------------------------------------------------------
// Autor:  Tizziano Perea - Darling Liliana Dorado
// Proposito del Archivo: Clase para creacion de archivos rips.
// -------------------------------------------------------------------------------------

/** $Id: rips.class.php,v 1.41 2006/05/23 18:54:40 carlos Exp $
*/
class rips
{
    var $error;
    var $mensajeDeError;
        var $archivo;
        var $ruta;
    /**
     * Almacena el caracter para una Nueva linea o salto de linea
     *
     * @var char
     */
    var $nl;
    
    function rips()
    {
        $this->nl = "\n";
          return true;
    }


    function GetError()
    {
        return $this->error;
    }


    function MensajeDeError()
    {
        return $this->mensajeDeError;
    }
   /**
    * Cambia el formato de la fecha de dd/mm/YY a YY/mm/dd
    * @access private
    * @return string
    * @param date fecha
    * @var    cad   Cadena con el nuevo formato de la fecha
    */
    function ConvFecha($fecha)
    {   
        if($fecha){
            $fech = strtok ($fecha,"/");
            for($i=0;$i<3;$i++)
            {
                $date[$i]=$fech;
                $fech = strtok ("/");
            }
            $cad = $date[2]."-".$date[1]."-".$date[0];
            return $cad;
        }
    }

 /**
  * Se encarga de separar la fecha del formato timestamp
  *
  * @param date fecha
  * @access private
  * @return string
  */
 function FechaStamp($fecha)
 {
   if($fecha){
        list($fecha1) = explode(" ",$fecha);
        return date("d/m/Y",strtotime($fecha1));
   }
 }

    function HoraStamp($hora)
    {
        $hor = strtok ($hora," ");
        for($l=0;$l<4;$l++)
        {
            $time[$l]=$hor;
            $hor = strtok (":");
        }
        $x=explode('.',$time[3]);
        return  $time[1].":".$time[2];
    }
 
    
     /**
    Metodo  para abrir el archivo de rips dependiendo su tipo
    */
    function AbrirArchivo($name,$modo,$envio)
    {
                $ruta=GetVarConfigAplication('DirGeneracionRips');
                if(!file_exists($ruta))
                {
                        return false;
                }
                if(!file_exists($ruta.'/ENVIO'.$envio))
                {
                            mkdir($ruta.'/ENVIO'.$envio,0777);
                }
                $ruta=$ruta.'/ENVIO'.$envio;
                $file=$ruta.'/'.$name;
            /**
//              if(!file_exists($file))
//              {
//                      $this->error = "Error Rips";
//                      $this->mensajeDeError = 'No se pudo crear:'.$file;
//                      return false;
//              }
*/
                $this->archivo = fopen($file,$modo);
                if(!$this->archivo)
                {
                        $this->error = "Error Rips";
                        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO.';
                        return false;
                }

                if(feof($this->archivo))
                {
                        $this->error = "Error Rips";
                        $this->mensajeDeError = 'Fin del Archivo...';
                        return false;
                }
            return true;

                if(feof($this->archivo))
                {
                        $this->error = "Error Rips";
                        $this->mensajeDeError = 'Fin del Archivo...';
                        return false;
                }
            return true;
    }



    /**
    Metodo que escribe en el archivo
    */
    function EscribirArchivo($texto)
    {
                fwrite($this->archivo,$texto);
                return true;
    }


    function CerrarArchivo()
    {
      if(!fclose($this->archivo))
      {
                        $this->error = "Error Rips";
                        $this->mensajeDeError = 'No pude cerrar El archivo...';
                        return false;
      }
        return true;
    }

    /**
     * Retorna de la cadena str la cantidad de caracteres desde el primer
     * caracter hasta la longitud que indica long
     *
     * @param string str
     * @param int long
     * @return string
     */
    function GetFormatoCampoTexto($str,$long)
    {
        $str = trim($str);
        if($long > 0 && strlen($str) > 0)
            $str = substr($str,0,$long);
        return $str;
    }
    
        /**
        * trae los datos del prestador del servicio
        * ejemplo: Clinica de Ocidente
        * @param string empresa,  codigo de la empresa
        * */
    function GetDatosPrestadorServicio($empresa)
    { 
            list($dbconn) = GetDBconn();
             $query = "select   c.codigo_sgsss,
                                                c.razon_social,
                                                case c.tipo_id_tercero when 'NIT' then 'NI' else c.tipo_id_tercero end as tipo_id_tercero, 
                                                c.id
                                from        empresas as c 
                                where   c.empresa_id='$empresa'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $var=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $var;
    }
        /**
        * trae los datos de la entidad administradora que presta el cargo cobrado
        * ejemplo: Coomeca EPS prepago
        * 
        * */
    function GetDatosEntidadAdministradora($plan_id)
    { 
        static $datosentidad;
        
        if(!empty($datosentidad[$plan_id])) 
        {
            return $datosentidad[$plan_id];
        }
        else
        {
            list($dbconn) = GetDBconn();
             $query = "
                            SELECT  P.num_contrato, 
                                            P.plan_descripcion,
                                            P.sw_rips_con_cargo_cups,
                                            P.sw_tipo_plan,
                                            TS.codigo_sgsss,
                                            T.nombre_tercero
                            FROM        planes P
                                            LEFT JOIN terceros_sgsss as TS
                                                ON( P.tipo_tercero_id = TS.tipo_id_tercero
                                                        AND P.tercero_id = TS.tercero_id),
                                            terceros T
                            WHERE       P.plan_id = '".$plan_id."'
                                            AND T.tipo_id_tercero = P.tipo_tercero_id
                                            AND T.tercero_id = P.tercero_id
            "; 
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos de la entidad administradora";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $datosentidad[$plan_id] = $result->FetchRow();
            $result->Close();
            return $datosentidad[$plan_id];
        }
    }
    /**
    *
    */
    function GetDetalleEnvio($envio,$aq)
    {
        if($aq)
        {
            $sql=" AND CD.tarifario_id<>'SYS'";
        }
        else
        {
            $sql="";
        }
        list($dbconn) = GetDBconn();
     echo   $query = 
        "SELECT A.*,
            BDD.consecutivo as consecutivo1,
            BDD.codigo_producto,
            BDD.cantidad as cantBDD,
            BDD.total_costo,
            M.codigo_medicamento,
            
            RCD.cargo_cups As cargo_manual,
            RCD.autorizacion,
            RCD.AC_FechaConsulta,
            RCD.AC_tipofinalidad,
            RCD.AC_CausaExterna,
            RCD.AC_Diagnostico,
            RCD.AC_TipoDiagnostico,
            RCD.AP_FechaProcedimiento,
            RCD.AP_AmbitoProcedimiento,
            RCD.AP_FinalidadProcedimiento,
            RCD.AU_FechaIngreso,
            RCD.AU_HoraIngreso,
            RCD.AU_CausaExterna,
            RCD.AU_DiagnosticoSalida,
            RCD.AU_DestinoSalida,
            RCD.AU_EstadoSalida,
            RCD.AU_FechaSalida,
            RCD.AU_HoraSalida,
            RCD.AH_ViaIngreso,
            RCD.AH_FechaIngreso,
            RCD.AH_horarioingreso,
            RCD.AH_CausaExterna,
            RCD.AH_DiagnosticoIngreso,
            RCD.AH_DiagnosticoSalida,
            RCD.AH_EstadoSalida,
            RCD.AH_FechaSalida,
            RCD.AH_horariosalida,
            C.cargo
FROM
        (
        SELECT ED.*,
                        FF.fecha_registro, 
                        FF.total_factura,
                        FF.valor_cuota_paciente,
                        FF.valor_cuota_moderadora,
                        FF.descuento,
                        FFC.prefijo,
                        FFC.factura_fiscal,
                        FFC.empresa_id,
                        FFC.numerodecuenta,
                        C.ingreso,
                        C.plan_id,
                        C.valor_total_empresa,
                        CD.transaccion,
                        CD.tarifario_id,
                        CD.cargo AS cargo_tarifario,
                        CD.cargo_cups,
                        CD.fecha_cargo,
                        CD.cantidad,
                        CD.precio,
                        CD.valor_cubierto,
                        CD.autorizacion_int,
                        CD.autorizacion_ext,
                        CD.servicio_cargo,
                        CD.consecutivo,
            CD.codigo_agrupamiento_id
        FROM
                        (
                            SELECT E.envio_id,
                                            E.fecha_inicial, 
                                            E.fecha_final,
                                            ED.empresa_id AS EDempresa_id,
                                            ED.prefijo AS EDprefijo,
                                            ED.factura_fiscal AS EDfactura_fiscal
                            FROM        envios E,
                                            envios_detalle ED
                            WHERE   E.envio_id='".$envio ."'
                                            AND E.sw_estado in(1,0,3) 
                                            AND E.envio_id = ED.envio_id
                        ) AS ED,
                        fac_facturas FF,
                        fac_facturas_cuentas FFC,
                        cuentas C,
                        cuentas_detalle CD
        WHERE
                        ED.EDempresa_id = FF.empresa_id
                        AND ED.EDprefijo = FF.prefijo
                        AND ED.EDfactura_fiscal = FF.factura_fiscal
                        AND FF.empresa_id = FFC.empresa_id
                        AND FF.prefijo = FFC.prefijo
                        AND FF.factura_fiscal = FFC.factura_fiscal
                        AND FFC.numerodecuenta = CD.numerodecuenta
                        AND FFC.numerodecuenta = C.numerodecuenta
                        AND (CD.valor_cubierto > 0 OR (CD.valor_cubierto <= 0 AND CD.cargo='DIMD'))
                        --AND CD.valor_cubierto > 0
                        AND CD.facturado = '1'
                        $sql
        ) AS A
                LEFT JOIN bodegas_documentos_d BDD
            ON(BDD.consecutivo = A.consecutivo)
        LEFT JOIN medicamentos M
            ON (BDD.codigo_producto = M.codigo_medicamento)
        LEFT JOIN rips_cuentas_detalle RCD
            ON(RCD.numerodecuenta = A.numerodecuenta
                AND RCD.cargo_cups = A.cargo_cups)
        LEFT JOIN cuentas_cargos_qx_procedimientos  CCQP 
            ON(CCQP.transaccion=A.transaccion)
        LEFT JOIN cuentas_liquidaciones_qx_procedimientos CLQP
            ON (CLQP.consecutivo_procedimiento=CCQP.consecutivo_procedimiento)
        LEFT JOIN cups C
            ON (CLQP.cargo_cups=C.cargo)
       --LIMIT 2000 OFFSET 0
       --LIMIT 3000 OFFSET 2000
       --LIMIT 3000 OFFSET 5000
       --LIMIT 4000 OFFSET 8000
       --LIMIT 1000 OFFSET 12000
       LIMIT 1000 OFFSET 13000
       --LIMIT 2000 OFFSET 12000
       --LIMIT 2000 OFFSET 14000
       --LIMIT 2000 OFFSET 16000
       --LIMIT 2000 OFFSET 18000
       --LIMIT 2000 OFFSET 20000
       --LIMIT 2000 OFFSET 22000
       --LIMIT 2000 OFFSET 24000
       --LIMIT 2000 OFFSET 26000
";
        $dbconn->debug = false;
        global $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Consultar los detales del envio";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            echo $this->mensajeDeError;
            return false;
        }
        
//         while(!$result->EOF)
//         {
//            $var[]= $result->FetchRow();
//         }
//         $result->Close();
         echo $result->_numOfRows.'<BR><BR>';
        for ($i=0; $i < $result->_numOfRows; $i++)
        { 
         $var[$i]=$result->FetchRow();
         //$var[$i]=$result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return  $var;
    }
    
            /**
        * trae los datos de la entidad administradora que presta el cargo cobrado
        * ejemplo: Coomeva EPS prepago
        * 
        * */
    function GetPolizaSoat($ingreso)
    { 
        static $datospoliza;
        
        if(!empty($datospoliza[$ingreso])) 
        {
            return $datospoliza[$ingreso];
        }
        else
        {
            list($dbconn) = GetDBconn();
             $query = "
                            SELECT  SE.poliza
                            FROM        
                                            ingresos_soat as IS
                                                LEFT JOIN soat_eventos as SE
                                                    ON(IS.evento = SE.evento)
                            WHERE       IS.ingreso = '".$ingreso."'
            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos de la poliza";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $datospoliza[$ingreso] = $result->FetchRow();
            $result->Close();
            return $datospoliza[$ingreso];
        }
    }
    
    /**
    *
    **/
    function GetDatosCups($cargo_cups)
    {
        static $datoscups;
        
        if(!empty($datoscups[$cargo_cups])) 
        {
            return $datoscups[$cargo_cups];
        }
        else
        {
            list($dbconn) = GetDBconn();
            $query = "
                            SELECT  A.descripcion AS descripcion_cups,
                                            A.grupo_tarifario_id,
                                            A.subgrupo_tarifario_id,
                                            A.grupo_tipo_cargo,
                                            A.tipo_cargo,
                                            A.concepto_rips,
                                            B.descripcion,
                                            B.tipo_servicio,
                                            B.tipo_servicio_descripcion
                            FROM        cups A,
                                            rips_conceptos B
                            WHERE       A.cargo = '".$cargo_cups."'
                                            AND A.concepto_rips = B.concepto_rips
            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos del cups";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $datoscups[$cargo_cups] = $result->FetchRow();
            $result->Close();
            return $datoscups[$cargo_cups];
        }
    }
    /**
    *   Consulta el tipo de rips que se debe trabajar segun el cargo
    */
    function GetTipoRips($ConsultaTipoRips,$tipo_cargo,$grupo_tipo_cargo,$grupo,$subgrupo,$tarifario,$cargo,$empresa)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT rips_tipo_id
                                FROM rips_parametros_tipos_excepciones 
                                WHERE cargo='$cargo' and tarifario_id='$tarifario' and empresa_id='$empresa'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }
            if(!$result->EOF)
            {       //tiene excepcion
                    return $result->fields[0];
            }
            else
            {//caso para Tulua Tulua
                if($ConsultaTipoRips == '1')
                {
                    $query = "SELECT rips_tipo_id
                                        FROM    rips_parametros_tipos
                                        WHERE empresa_id='$empresa' 
                                                    AND grupo_tarifario_id='$grupo' 
                                                    AND subgrupo_tarifario_id='$subgrupo'";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error  al Consultar rips_parametros_tipos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        echo $this->mensajeDeError;
                        return false;
                    }   
                    if(!$result->EOF)
                    {       return $result->fields[0];      }   
                    else                    
                    {   //no estan llenas las tablas
                            return '0';   
                    }
                }
                else
                {//caso para Cali
                    $query = "SELECT rips_tipos_id
                                        FROM rips_tipos_cargos
                                        WHERE tipo_cargo='$tipo_cargo' 
                                                    AND grupo_tipo_cargo='$grupo_tipo_cargo'";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Consultar rips_tipos_cargos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        echo $this->mensajeDeError;
                        return false;
                    }   
                    
                    if(!$result->EOF)
                    {       return $result->fields[0];      }   
                    else                    
                    {   //no estan llenas las tablas
                            return '0';   
                    }
                }
            }
    }
    /**
    * Consulta los datos del paciente dado el numero de ingreso
    **/
    function GetDatosUsuario($ingreso)
    {
        static $datosusuario;
        
        if(!empty($datosusuario[$ingreso])) 
        {
            return $datosusuario[$ingreso];
        }
        else
        {
            list($dbconn) = GetDBconn();
            $query = "
                            SELECT  P.tipo_id_paciente,
                                            P.paciente_id,
                                            P.primer_nombre,
                                            P.segundo_nombre,
                                            P.primer_apellido,
                                            P.segundo_apellido,
                                            P.primer_nombre ||' '||P.segundo_nombre ||' '|| P.primer_apellido ||' '|| P.segundo_apellido AS nombre,
                                            P.fecha_nacimiento,
                                            P.sexo_id,
                                            P.tipo_dpto_id,
                                            P.tipo_mpio_id,
                                            P.zona_residencia
                            FROM        ingresos I,
                                            pacientes  P
                            WHERE       ingreso = '".$ingreso."'
                                            AND I.tipo_id_paciente = P.tipo_id_paciente
                                            AND I.paciente_id = P.paciente_id
            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos del Usuario";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }

            $datosusuario[$ingreso] = $result->FetchRow();
            $result->Close();
            return $datosusuario[$ingreso];
        }
    }
        /**
    *
    */
    function GetAutorizacionExterna($auto)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT  c.codigo_autorizacion as codelect,
                                                d.codigo_autorizacion as codelectsos, 
                                                e.codigo_autorizacion as codescr,
                                                f.codigo_autorizacion as codcert, 
                                                g.codigo_autorizacion as codtel
                                FROM        hc_os_autorizaciones as b
                                                left join autorizaciones_electronicas as c on(b.autorizacion_ext=c.autorizacion)
                                                left join autorizaciones_electronicas_sos as d on(b.autorizacion_ext=d.autorizacion)
                                                left join autorizaciones_escritas as e on(b.autorizacion_ext=e.autorizacion)
                                                left join autorizaciones_certificados as f on(b.autorizacion_ext=f.autorizacion)
                                                left join autorizaciones_telefonicas as g on(b.autorizacion_ext=g.autorizacion)

                                WHERE b.autorizacion_ext=$auto
                                            AND b.autorizacion_ext IS NOT NULL";

            $dbconn-> debug = false;
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $var;
    }
    /**
    * Retorna el numero de autorizacion
    * Si no hay retorna uno (1) como valor por defecto
    * @param autorizacion_ext String
    * @return auto String
    */
    function GetAutorizacion($d_envio,$autorizacion_ext)
    {
        $auto='1';
        if((!empty($autorizacion_ext)) && ($autorizacion_ext != '1'))
        {
                $aut='1';
                $aut=$this->GetAutorizacionExterna($autorizacion_ext);

                if(!empty($aut[codelect]))
                {  $auto=$aut[codelect];  }
                elseif(!empty($aut[codelectsos]))
                {  $auto=$aut[codelectsos];  }
                elseif(!empty($aut[codelescr]))
                {  $auto=$aut[codelescr];  }
                elseif(!empty($aut[codcert]))
                {  $auto=$aut[codcert];  }
                elseif(!empty($aut[codtel]))
                {  $auto=$aut[codtel];  }
        }
        else
        {
            if(!empty($d_envio[autorizacion_int]))
            {
                $auto = $d_envio[autorizacion_int];
            }
        }
        return $auto;
    }
    /**
    * Consulta los datos del medicamento
    */
    function GetDatosMedicamento($cod_medicamento,$cod_producto)
    {
        static $datosmedicamento;
        
        if(!empty($datosmedicamento[$cod_medicamento][$cod_producto])) 
        {
            return $datosmedicamento[$cod_medicamento][$cod_producto];
        }
        else
        {
            list($dbconn) = GetDBconn();
            $query = "SELECT    e.descripcion_abreviada, 
                                                b.descripcion as farmaco,
                                                case a.sw_pos when 0 then 2 else 1 end as sw_pos,
                                                case a.sw_pos when 0 then '' 
                                                    else a.cod_anatomofarmacologico||''||a.cod_principio_activo||''||a.cod_forma_farmacologica||''||a.cod_concentracion
                                                        end as codigo_producto,
                                                d.descripcion as unidad, 
                                                a.concentracion_forma_farmacologica
                                FROM    medicamentos as a, 
                                            inv_med_cod_forma_farmacologica as b,
                                            inv_unidades_medida_medicamentos as d,
                                            inventarios_productos as e
                                WHERE a.codigo_medicamento = '".$cod_medicamento."'
                                            AND a.cod_forma_farmacologica = b.cod_forma_farmacologica
                                            AND a.unidad_medida_medicamento_id = d.unidad_medida_medicamento_id
                                            AND e.codigo_producto = '".$cod_producto."'
                                            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos del Medicamento";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }

            $datosmedicamento[$cod_medicamento][$cod_producto] = $result->FetchRow();
            $result->Close();
            return $datosmedicamento[$cod_medicamento][$cod_producto];
        }
    }
    
    /**
    * Consulta los datos del insumo
    */
    function GetDatosInsumo($cod_producto)
    {
        static $datosinsumo;
        
        if(!empty($datosinsumo[$cod_producto])) 
        {
            return $datosinsumo[$cod_producto];
        }
        else
        {
            list($dbconn) = GetDBconn();
            $query = "SELECT A.descripcion_abreviada,
                                            A.unidad_id,
                                            A.cod_ihosp
                                FROM    inventarios_productos as A
                                WHERE A.codigo_producto = '".$cod_producto."'
                                            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos del insumo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }

            $datosinsumo[$cod_producto] = $result->FetchRow();
            $result->Close();
            return $datosinsumo[$cod_producto];
        }
    }
    /**
    *
    */
    function GetDatosPrincipalesCita($ingreso)
    {
        static $datoscita;
        
        if(!empty($datoscita[$ingreso])) 
        {
            return $datoscita[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "  SELECT  --a.tipo_finalidad_id, 
                                                                --b.tipo_atencion_id,
                                                                case when a.tipo_finalidad_id='NULL' then '10' when a.tipo_finalidad_id<>'NULL' then a.tipo_finalidad_id end as tipo_finalidad_id,
                                                                case when b.tipo_atencion_id='NULL' then '15' when b.tipo_atencion_id<>'NULL' then b.tipo_atencion_id end as tipo_atencion_id,
                                                                c.tipo_diagnostico_id, 
                                                                c.sw_principal, 
                                                                c.tipo_diagnostico,
                                                                d.evolucion_id
                                            FROM    hc_diagnosticos_ingreso as c,
                                                        hc_evoluciones as d 
                                                            left join hc_finalidad as a 
                                                                on(a.evolucion_id=d.evolucion_id)
                                                            left join hc_atencion as b 
                                                                on(b.evolucion_id=d.evolucion_id)
                                            WHERE d.ingreso=$ingreso 
                                                        and c.evolucion_id=d.evolucion_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }

            while(!$result->EOF)
            {
                    $datoscita[$ingreso][]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            $result->Close();
            return $datoscita[$ingreso];
        }
    }
    
    /**
    *
    */
    function FechaCita($transaccion)
    {
        static $fechacita;
        
        if(!empty($fechacita[$transaccion])) 
        {
            return $fechacita[$transaccion];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT e.fecha_turno
                                FROM    os_cruce_citas as a, 
                                            agenda_citas_asignadas as b,
                                            agenda_citas as c, 
                                            agenda_turnos as e,
                                            os_maestro_cargos f
                                WHERE a.numero_orden_id = f.numero_orden_id
                                and a.agenda_cita_asignada_id = b.agenda_cita_asignada_id
                                and b.agenda_cita_id = c.agenda_cita_id
                                and c.agenda_turno_id = e.agenda_turno_id
                                AND f.transaccion = $transaccion";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                $fechacita[$transaccion] = $result->fields[0];
                $result->Close();
                return $fechacita[$transaccion];
            }
        }
    }
    
    /**
    *
    */
    function    GetAmbito($servicio)
    {
        static $ambito;
        
        if(!empty($ambito[$servicio])) 
        {
            return $ambito[$servicio];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT    ambito_rips_id 
                                FROM        servicios 
                                WHERE   servicio = '".$servicio."'
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar ambito";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }
            if(!$result->EOF)
            {
                    $ambito[$servicio] = $result->fields[0];
            }
            $result->Close();
            return $ambito[$servicio];
        }
    }
    
    /**
    *
    */
        function GetDiagnosticosOdontologiaAP($transaccions)
    {
            list($dbconn) = GetDBconn();
            
            $query = "SELECT    a.numero_orden_id
                                FROM        os_maestro_cargos as a  
                                WHERE   a.transaccion=$transaccions;
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }   
            $numeroOs=$result->fields[0];
            $result->Close();   
            
            if(!empty($numeroOs))
            {
                $query = "SELECT    hc_os_solicitud_id 
                                    FROM        os_maestro
                                    WHERE   numero_orden_id = $numeroOs;
                                    ";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }   
                $solicitud=$result->fields[0];
                $result->Close();   
                
                $query = "SELECT b.diagnostico_id, b.sw_principal
                                    FROM hc_odontogramas_primera_vez_detalle  as a, hc_odontogramas_tratamientos_evolucion_primera_vez as b
                                    WHERE a.hc_os_solicitud_id=$solicitud and a.hc_odontograma_primera_vez_detalle_id=b.hc_odontograma_primera_vez_detalle_id
                                    order by b.sw_principal desc;";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }               
                //encontro los diagnosticos
                if(!$result->EOF)   
                {       $diag='';
                        $i=0;
                        while(!$result->EOF AND $i<=1)
                        {
                                if($i==0)
                                {  $diag=$result->fields[0];  }
                                elseif($i==1)
                                {  $diag.=','.$result->fields[0];  }
                                $i++;
                                $result->MoveNext();
                        }
                        if($i==1)
                        { $diag.=',';}
                        return  $diag;
                }
                else
                {
                        $query = "SELECT b.diagnostico_id, b.sw_principal
                                            FROM hc_odontogramas_tratamientos_detalle  as a, hc_odontogramas_tratamientos_evolucion_tratamiento as b
                                            WHERE a.hc_os_solicitud_id=$solicitud and a.hc_odontograma_tratamiento_detalle_id=b.hc_odontograma_tratamiento_detalle_id
                                            order by b.sw_principal desc;";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }       
                        //encontro los diagnosticos
                        if(!$result->EOF)   
                        {       $diag='';
                                $i=0;
                                while(!$result->EOF AND $i<=1)
                                {
                                        if($i==0)
                                        {  $diag=$result->fields[0];  }
                                        elseif($i==1)
                                        {  $diag.=','.$result->fields[0];  }
                                        $i++;
                                        $result->MoveNext();
                                }
                                if($i==1)
                                { $diag.=',';}
                                return  $diag;                  
                        }           
                        else
                        {
                            $query = "SELECT diagnostico_id, sw_principal
                                                    FROM hc_odontogramas_tratamientos_evolucion_presupuesto
                                                    WHERE hc_os_solicitud_id=$solicitud order by sw_principal desc;";
                                $result = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                                }   
                                //encontro los diagnosticos
                                if(!$result->EOF)   
                                {       $diag='';
                                        $i=0;
                                        while(!$result->EOF AND $i<=1)
                                        {
                                                if($i==0)
                                                {  $diag=$result->fields[0];  }
                                                elseif($i==1)
                                                {  $diag.=','.$result->fields[0];  }
                                                $i++;
                                                $result->MoveNext();
                                        }
                                        if($i==1)
                                        { $diag.=',';}
                                        return  $diag;                          
                                }   
                                else
                                {
                                    $query = "SELECT diagnostico_id, sw_principal
                                                            FROM hc_odontogramas_tratamientos_evolucion_apoyod
                                                            WHERE hc_os_solicitud_id=$solicitud order by sw_principal desc;";
                                        $result = $dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Cargar el Modulo";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                                        }
                                        //encontro los diagnosticos
                                        if(!$result->EOF)   
                                        {       $diag='';
                                                $i=0;
                                                while(!$result->EOF AND $i<=1)
                                                {
                                                        if($i==0)
                                                        {  $diag=$result->fields[0];  }
                                                        elseif($i==1)
                                                        {  $diag.=','.$result->fields[0];  }
                                                        $i++;
                                                        $result->MoveNext();
                                                }
                                                if($i==1)
                                                { $diag.=',';}
                                                return  $diag;                          
                                        }
                                        else
                                        { return "error no se guardaron solicitud=>$solicitud"; }                                                                       
                                }                                           
                        }       
                }
            }
    }
    
    /**
    *
    */
    function GetDatosCama($transaccion)
    {
        static $datoscama;
        
        if(!empty($datoscama[$transaccion])) 
        {
            return $datoscama[$transaccion];
        }
        else
        {   
            list($dbconn) = GetDBconn();
/*          $query = "SELECT    a.fecha_ingreso, 
                                                a.fecha_egreso, 
                                                b.tipo_clase_cama_id
                                FROM    movimientos_habitacion as a, 
                                            tipos_camas as b
                                WHERE a.transaccion = $transaccion
                                            and a.tipo_cama_id = b.tipo_cama_id;";*/
            $query = "SELECT    a.fecha_ingreso, 
                                                a.fecha_egreso, 
                                                b.tipo_clase_cama_id
                                FROM    movimientos_habitacion as a, 
                                            tipos_camas as b
                                WHERE a.numerodecuenta = $transaccion
                                            and a.tipo_cama_id = b.tipo_cama_id;";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $datoscama[$transaccion]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $datoscama[$transaccion];
        }
    }

    /**
    *
    */
    function GetCausaExterna($ingreso)
    {
        static $causaexterna;
        
        if(!empty($causaexterna[$ingreso])) 
        {
            return $causaexterna[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT    tipo_atencion_id 
                                FROM        hc_atencion 
                                WHERE   ingreso=$ingreso";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $result->Close();
            $causaexterna[$ingreso] = $result->fields[0];
            return $causaexterna[$ingreso];
        }
    }
    
    /**
    *
    */
    function GetDatosEgresoPaciente($ingreso)
    {
        static $egresopaciente;
        
        if(!empty($egresopaciente[$ingreso])) 
        {
            return $egresopaciente[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "  SELECT c.tipo_diagnostico_id, c.sw_principal, c.tipo_diagnostico
                                    FROM hc_diagnosticos_egreso as c, hc_evoluciones as d
                                    WHERE d.ingreso=$ingreso
                                    and c.evolucion_id=d.evolucion_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $egresopaciente[$ingreso][]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $egresopaciente[$ingreso];
        }
    }
    
    /**
    *
    */
    function GetDatosDefuncion($ingreso)
    {
        static $datosdefuncion;
        
        if(!empty($datosdefuncion[$ingreso])) 
        {
            return $datosdefuncion[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "  SELECT c.diagnostico_defuncion_id
                                    FROM    hc_conducta_defuncion as a, 
                                                hc_conducta_diagnosticos_defuncion as c
                                    WHERE a.ingreso=$ingreso 
                                                and a.evolucion_id=c.evolucion_id
                                                and c.sw_principal='1'
                                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {
                    $datosdefuncion[$ingreso]=$result->fields[0];
            }

            $result->Close();
            return $datosdefuncion[$ingreso];
        }
    }


    /**
    *
    */
    function GetDestinoPaciente($ingreso,$cuenta)
    {
            list($dbconn) = GetDBconn();
            $var='';
            //alta de urgencias
            $query = "  SELECT a.sw_estado FROM pacientes_urgencias as a
                                    WHERE a.ingreso=$ingreso AND a.sw_estado IN('4','2')";
            /*$query = "  SELECT case when a.sw_estado=2 then 3 when a.sw_estado=4 then 1 end as estado
                                    FROM pacientes_urgencias as a WHERE a.ingreso=$ingreso";*/
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if(!$result->EOF)
            {
                    if($result->fields[0]==4)
                    {   $var=1;  }
                    else
                    {       //hospitalizado
                            $query = "SELECT b.tipo_clase_cama_id
                                                FROM movimientos_habitacion as a, tipos_camas as b
                                                WHERE a.numerodecuenta=$cuenta
                                                and a.tipo_cama_id=b.tipo_cama_id and b.tipo_clase_cama_id<>'3'";
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            if(!$result->EOF)
                            {
                                    $var=3;
                            }
                            else
                            {       //remirido
                                    $query = "  SELECT ingreso FROM hc_conducta_remision WHERE ingreso=$ingreso";
                                    $result = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                                    }
                                    if(!$result->EOF)
                                    {
                                            $var=2;
                                    }
                                    else
                                    {  $var=1;  }
                            }
                    }
            }
            else
            {       //remirido
                    $query = "  SELECT ingreso FROM hc_conducta_remision WHERE ingreso=$ingreso";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    if(!$result->EOF)
                    {
                            $var=2;
                    }
                    else
                    {       //hospitalizado
                            $query = "SELECT b.tipo_clase_cama_id
                                                FROM movimientos_habitacion as a, tipos_camas as b
                                                WHERE a.numerodecuenta=$cuenta
                                                and a.tipo_cama_id=b.tipo_cama_id and b.tipo_clase_cama_id<>'3'";
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            if(!$result->EOF)
                            {
                                    $var=3;
                            }
                    }
            }

            $result->Close();
            return $var;
    }

    /**
    *
    */
    function GetRegimen($plan)
    {
        static $regimen;
        
        if(!empty($regimen[$plan])) 
        {
            return $regimen[$plan];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT b.regimen_id
                                FROM    planes as a 
                                            left join tipos_cliente as b 
                                                on(a.tipo_cliente=b.tipo_cliente)
                                WHERE a.plan_id=$plan  ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $regimen[$plan]=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $regimen[$plan];
        }
    }
        /**
    *
    */
    function GetViasIngreso()
    {
        list($dbconn) = GetDBconn();
        $query = "select    via_ingreso_id,
                                            via_ingreso_nombre
                            from        vias_ingreso
                            ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Consultar datos del vias_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $datos=$result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $datos;
    }
    /**
    *
    */
    function GetDatosIngreso($ingreso)
    {
        static $datosingreso;
        
        if(!empty($datosingreso[$ingreso])) 
        {
            return $datosingreso[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "select    A.tipo_id_paciente, 
                                                A.paciente_id, 
                                                B.tipo_afiliado_id, 
                                                B.numerodecuenta,
                                                A.ingreso, 
                                                A.via_ingreso_id, 
                                                A.autorizacion_ext, 
                                                A.causa_externa_id,
                                                D.tipo_diagnostico_id,
                                                A.fecha_registro,
                                                A.fecha_cierre
                                from        ingresos as A,
                                                cuentas as B,
                                                hc_evoluciones C,
                                                hc_diagnosticos_ingreso D
                                where   A.ingreso = '".$ingreso."'
                                                AND A.ingreso = B.ingreso
                                                AND A.ingreso = C.ingreso
                                                AND C.evolucion_id = D.evolucion_id
                                                AND D.sw_principal  = '1';";
            $dbconn->debug = false;
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar datos del ingreso";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $datosingreso[$ingreso]=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $datosingreso[$ingreso];
        }
    }

    /**
    *
    */
    function GetTipoProfesional($transaccion)
    {
        static $tipoprofesional;
        
        if(!empty($tipoprofesional[$transaccion])) 
        {
            return $tipoprofesional[$transaccion];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT    TP.codigo_rips
                                FROM        cuentas_detalle_profesionales CDP,
                                                profesionales P,
                                                tipos_profesionales TP
                                WHERE       CDP.transaccion = ".$transaccion."
                                                AND CDP.tipo_tercero_id = P.tipo_id_tercero
                                                AND CDP.tercero_id = P.tercero_id
                                                AND P.tipo_profesional = TP.tipo_profesional
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar tipo profesional";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $tipoprofesional[$transaccion]=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $tipoprofesional[$transaccion];
        }
    }
    
    /**
    *
    */
    function GetSwDatosComplementarios($cargo_cups,$numerocuenta)
    {
        static $datoscomplementarios;
        
        if(!empty($datoscomplementarios[$numerocuenta][$cargo_cups])) 
        {
            return $datoscomplementarios[$numerocuenta][$cargo_cups];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT    sw_au,
                                                sw_ah,
                                                sw_an
                                FROM        rips_datos_complementarios
                                WHERE       cargo  ='".$cargo_cups."'
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar  rips_datos_complementarios";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $datoscomplementarios[$numerocuenta][$cargo_cups]=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $datoscomplementarios[$numerocuenta][$cargo_cups];
        }
    }
    
    /**
    *
    */
    function InsertaRipsCuentasDetalle()
    {
        $cargo_cups = $_SESSION['TMP_DATOS']['cargos_cups'];
    
        $ac_fechaconsulta = $this->ConvFecha($_REQUEST['ac_fechaconsulta']);
        $ap_fechaprocedimiento = $this->ConvFecha($_REQUEST['ap_fechaprocedimiento']);
        $au_fechaingreso = $this->ConvFecha($_REQUEST['au_fechaingreso']);
        $au_fechasalida = $this->ConvFecha($_REQUEST['au_fechasalida']);
        $ah_fechaingreso = $this->ConvFecha($_REQUEST['ah_fechaingreso']);
        $ah_fechasalida = $this->ConvFecha($_REQUEST['ah_fechasalida']);
        $au_horarioingreso = $_REQUEST['au_horarioingreso'];
        $au_minuteroingreso =$_REQUEST['au_minuteroingreso'];
        $au_horariosalida = $_REQUEST['au_horariosalida'];
        $au_minuterosalida = $_REQUEST['au_minuterosalida'];
        $ah_horariosalida =$_REQUEST['ah_horariosalida'];
        $ah_minuterosalida = $_REQUEST['ah_minuterosalida'];
        $ah_horarioingreso =$_REQUEST['ah_horarioingreso'];
        $ah_minuteroingreso = $_REQUEST['ah_minuteroingreso'];
        
        ( $ac_fechaconsulta== '')?      $ac_fechaconsulta= 'NULL': $ac_fechaconsulta = "'$ac_fechaconsulta 00:00:00'";
        ( $ap_fechaprocedimiento== '')? $ap_fechaprocedimiento= 'NULL':$ap_fechaprocedimiento = "'$ap_fechaprocedimiento 00:00:00'";
        ($au_fechaingreso == '')?       $au_fechaingreso= 'NULL':$au_fechaingreso = "'$au_fechaingreso 00:00:00'";
        ( $au_fechasalida== '')?        $au_fechasalida = 'NULL':$au_fechasalida = "'$au_fechasalida 00:00:00'";
        ($ah_fechaingreso == '')?               $ah_fechaingreso= 'NULL':$ah_fechaingreso = "'$ah_fechaingreso 00:00:00'";
        ($ah_fechasalida == '')?                $ah_fechasalida= 'NULL':$ah_fechasalida = "'$ah_fechasalida 00:00:00'";
        ($au_horarioingreso == '')?         $au_horarioingreso = 'NULL':$au_horarioingreso = "'$au_horarioingreso:$au_minuteroingreso'";
        ($au_horariosalida == '')?          $au_horariosalida = 'NULL':$au_horariosalida = "'$au_horariosalida:$au_minuterosalida'";
        ($ah_horariosalida == '')?          $ah_horariosalida ='NULL' :$ah_horariosalida = "'$ah_horariosalida:$ah_minuterosalida'";
        ($ah_horarioingreso == '')?         $ah_horarioingreso = 'NULL' : $ah_horarioingreso ="'$ah_horarioingreso:$ah_minuteroingreso'";
        
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "INSERT INTO rips_cuentas_detalle 
                                                    (numerodecuenta, autorizacion, cargo_cups,ac_fechaconsulta, ac_tipofinalidad, ac_causaexterna, ac_diagnostico, ac_tipodiagnostico, ap_fechaprocedimiento, ap_ambitoprocedimiento, ap_finalidadprocedimiento, au_fechaingreso, au_horaingreso, au_causaexterna, au_diagnosticosalida, au_destinosalida, au_estadosalida, au_fechasalida, au_horasalida, ah_viaingreso, ah_fechaingreso, ah_horarioingreso, ah_causaexterna, ah_diagnosticoingreso, ah_diagnosticosalida, ah_estadosalida, ah_fechasalida, ah_horariosalida, usuario_id)
                                                    VALUES (".$_REQUEST['numerodecuenta'].", 
                                                                    ".$_REQUEST['autorizacion'].", 
                                                                    '".$cargo_cups."',
                                                                    ".$ac_fechaconsulta.",
                                                                    '".$_REQUEST['ac_tipofinalidad']."', 
                                                                    '".$_REQUEST['ac_causaexterna']."', 
                                                                    '".$_REQUEST['ac_diagnostico']."', 
                                                                    '".$_REQUEST['ac_tipodiagnostico']."', 
                                                                    ".$ap_fechaprocedimiento.", 
                                                                    '".$_REQUEST['ap_ambitoprocedimiento']."', 
                                                                    '".$_REQUEST['ap_finalidadprocedimiento']."', 
                                                                    ".$au_fechaingreso.", 
                                                                    ".$au_horarioingreso.", 
                                                                    '".$_REQUEST['au_causaexterna']."', 
                                                                    '".$_REQUEST['au_diagnosticosalida']."', 
                                                                    '".$_REQUEST['au_destinosalida']."', 
                                                                    '".$_REQUEST['au_estadosalida']."', 
                                                                    ".$au_fechasalida.", 
                                                                    ".$au_horariosalida.", 
                                                                    '".$_REQUEST['ah_viaingreso']."', 
                                                                    ".$ah_fechaingreso.", 
                                                                    ".$ah_horarioingreso.", 
                                                                    '".$_REQUEST['ah_causaexterna']."', 
                                                                    '".$_REQUEST['ah_diagnosticoingreso']."', 
                                                                    '".$_REQUEST['ah_diagnosticosalida']."', 
                                                                    '".$_REQUEST['ah_estadosalida']."', 
                                                                    ".$ah_fechasalida.", 
                                                                    ".$ah_horariosalida.",
                                                                    ".UserGetUID().");

                                ";
        $dbconn->debug = false;
        $result = $dbconn->Execute($query);
        
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en rips_cuentas_detalle";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
                return false;
            }
        
        $dbconn->CommitTrans();
        $result->Close();
        return true;
    }
    /**
    * Metodo encargado de construir la cadena de informacion para el Archivo AF
    * @param $d_envio Array informacio basica de la factura realcionada en el envio
    * @param $datos_prestador_servicio Array Informacion del prestador de Servicios
    * @param $datos_entidad_administradora Array Informacion de la entidad adminitradora
    */
    function CallConstruyeAF(&$arregloAF,$d_envio,$datos_prestador_servicio,$datos_entidad_administradora)
    {
        $arregloAF.=$datos_prestador_servicio[codigo_sgsss].",";//R codigo_sgsss del prestador de servicio
        $arregloAF.=$this->GetFormatoCampoTexto($datos_prestador_servicio[razon_social],30).",";//R razon_social del prestador de servicio
        $arregloAF.=$datos_prestador_servicio[tipo_id_tercero].",";//R tipo_id_tercero del prestador de servicio
        $arregloAF.=$datos_prestador_servicio[id].",";//R tipo_id_tercero del prestador de servicio
        $arregloAF.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R numero de factura
        $arregloAF.=$this->FechaStamp($d_envio[fecha_registro]).",";//R
        $arregloAF.=$this->FechaStamp($d_envio[fecha_inicial]).",";//R
        $arregloAF.=$this->FechaStamp($d_envio[fecha_final]).",";//R
        $arregloAF.=$datos_entidad_administradora[codigo_sgsss].",";//R codigo_sgsss de la entidad administradora
        $arregloAF.=$this->GetFormatoCampoTexto($datos_entidad_administradora[nombre_tercero],30).",";// R nombre  de la entidad administradora
        $arregloAF.=$datos_entidad_administradora[num_contrato].",";//num_contrato de la entidad administradora
        $arregloAF.=$this->GetFormatoCampoTexto($datos_entidad_administradora[plan_descripcion],30).",";//plan_descripcion de la entidad administradora
        $arregloAF.=$this->GetPolizaSoat($d_envio[ingreso]).",";//numero de poliza si el ingreso fue por SOAT
        $arregloAF.=($d_envio[valor_cuota_paciente] + $d_envio[valor_cuota_moderadora]).","; //R valor del copago
        $arregloAF.='0.00'.",";//valor de la comision
        $arregloAF.=$d_envio[descuento].",";//descuento dado a la factura
        $arregloAF.=$d_envio[total_factura];//R valor total de la factura
        $arregloAF.=$this->nl;
        return true;
    }
    /**.
    *
    */
    function CallConstruyeAM(&$arregloAM,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD)
    {
        $datos_medicamento = $this->GetDatosMedicamento($d_envio[codigo_medicamento],$d_envio[codigo_producto]);
        
        $arregloAM.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R
        $arregloAM.=$datos_prestador_servicio[codigo_sgsss].",";//R
        $arregloAM.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloAM.=$datos_usuario[paciente_id].",";// R 
        $arregloAM.=$autorizacion.",";
        $arregloAM.=$datos_medicamento[codigo_producto].",";
        $arregloAM.=$datos_medicamento[sw_pos].",";//R tipo medicamento
        $arregloAM.=$datos_medicamento[descripcion_abreviada].",";
        $arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[farmaco],20).",";
        $arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[concentracion_forma_farmacologica],20).",";
        $arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[unidad],5).",";
        $arregloAM.=$d_envio[cantidad].",";//R  
        $arregloAM.=$d_envio[precio].",";//R
        $arregloAM.=$d_envio[valor_cubierto];//R
        $arregloAM.=$this->nl;

        $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],'09',$d_envio[cantidad],$d_envio[valor_cubierto]);
//          //entra si es insumo y no medicamento
//      if(!empty($datos_cups[concepto_rips]))
//      {
//          $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$d_envio[cantidad],$d_envio[valor_cubierto]);
//      }
        return true;
    }
    
    /**
    *
    */
    function CallConstruyeAT(&$arregloAT,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD,$datos_entidad_administradora)
    {
        $codigo_servivio ='';
        $tipo_servicio ='';
        $descripcion ='';
        if(!empty($d_envio[codigo_medicamento]))
        {
            $datos_medicamento = $this->GetDatosMedicamento($d_envio[codigo_medicamento],$d_envio[codigo_producto]);
            $descripcion=$this->GetFormatoCampoTexto($datos_medicamento[descripcion_abreviada],60);
        }
        else//es un insumo
        {
            $cod_producto=$d_envio[codigo_producto];//Nuevo 11/05/2006
            $datos_medicamento = $this->GetDatosInsumo($cod_producto);
            $tipo_servicio = '1';
            $codigo_servivio = $d_envio[cargo_tarifario];
            $descripcion=$this->GetFormatoCampoTexto($datos_medicamento[descripcion_abreviada],60);
        }

        if($datos_cups[tipo_servicio]=='2' || $datos_cups[tipo_servicio]=='3' || $datos_cups[tipo_servicio]=='4')
        {
            $descripcion = $datos_cups[descripcion_cups];
            $tipo_servicio = $datos_cups[tipo_servicio];
        }

        if($datos_entidad_administradora[sw_tipo_plan] == '3')
        {
            $valor_cubierto = 0;
            $precio = 0;
        }
        else
        {
            $valor_cubierto =$d_envio[valor_cubierto];
            $precio =$d_envio[precio];
        }
    
        $arregloAT.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R
        $arregloAT.=$datos_prestador_servicio[codigo_sgsss].",";//R
        $arregloAT.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloAT.=$datos_usuario[paciente_id].",";//R
        $arregloAT.=$autorizacion.",";
        $arregloAT.=$tipo_servicio.",";//R
        $arregloAT.=$codigo_servivio.",";
        $arregloAT.=$descripcion.",";
        $arregloAT.=$d_envio[cantidad].",";//R
        $arregloAT.=$precio.",";//R
        $arregloAT.=$valor_cubierto;//R
        $arregloAT.=$this->nl;
        $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$d_envio[cantidad],$d_envio[valor_cubierto]);

        return true;
    }
    
    /**
    *
    */
    function CallConstruyeAC(&$arregloAC,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_entidad_administradora,$datos_cups,&$vectorAD)
    {//echo $d_envio[numerodecuenta]; exit;
        static $cuotamoderadora;
        $valor_cuota_moderadora = 0;
        
        if(empty($cuotamoderadora[$d_envio[numerodecuenta]])) 
        {
            $cuotamoderadora[$d_envio[numerodecuenta]] = true;
            $valor_cuota_moderadora = $d_envio[valor_cuota_moderadora];
        }
        if($datos_entidad_administradora[sw_tipo_plan] == '3')
        {
            $valor_cubierto = 0;
            $valor_cuota_moderadora = 0;
            $valor_total_empresa = 0;
        }
        else
        {
            $valor_cubierto =$d_envio[valor_cubierto];
            $valor_cuota_moderadora = $d_envio[valor_cuota_moderadora];
            //$valor_total_empresa = $d_envio[valor_total_empresa];
            //$valor_total_empresa = ($d_envio[valor_cubierto] - $d_envio[valor_cuota_moderadora]);
            //$valor_cuota_moderadora = 0;
            $valor_total_empresa = ($d_envio[valor_cubierto] - $valor_cuota_moderadora);
        }
        //parametro segun la contratacion.Se debe trabajar con cargo tarifario o cargo cups
        if($datos_entidad_administradora[sw_rips_con_cargo_cups] == '1')
            $cod_cargo = $d_envio[cargo_cups];
        else
            $cod_cargo = $d_envio[cargo_tarifario];
            
        if(empty($d_envio[cargo_manual]))
        {
            $datoscita = $this -> GetDatosPrincipalesCita($d_envio[ingreso]);
            $diagp=$diag='';
            for($m=0; $m<sizeof($datoscita); $m++)
            {
                        if(!empty($datoscita[$m][sw_principal]))
                        {
                                $diagp=$datoscita[$m][tipo_diagnostico_id];
                                $tipo=$datoscita[$m][tipo_diagnostico];
                        }
                        else
                        {  $diag[]=$datoscita[$m][tipo_diagnostico_id];  }
            }
            
            $FC = $this->FechaCita($d_envio[transaccion]);
            if(empty($FC))
            {  
                $FC=$d_envio[fecha_cargo];  
            }
            
            //Por si no se esta llenando la finalidad de la evolucion por
            //tener el modulo de finalidad como no obligatorio
            if(empty($cit[0][tipo_finalidad_id]))
                $tipo_finalidad_id = '10';
            else
                $tipo_finalidad_id = $datoscita[0][tipo_finalidad_id];
            
            if(empty($datoscita[0][tipo_atencion_id]))
                $tipo_atencion_id = '';
            else
                $tipo_atencion_id = $datoscita[0][tipo_atencion_id];
        }
        else
        {//cargos ingresados manualmente
            $FC = $d_envio[ac_FechaConsulta];
            $autorizacion = $d_envio[autorizacion];
            $tipo_finalidad_id = $d_envio[ac_tipofinalidad];
            $tipo_atencion_id = $d_envio[ac_causaexterna];
            $diagp = $d_envio[ac_diagnostico];
            $diag[0] = '';
            $diag[1] = '';
            $diag[2] = '';
            $tipo = $d_envio[ac_tipodiagnostico];
        }
        $arregloAC.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";
        $arregloAC.=$datos_prestador_servicio[codigo_sgsss].",";
        $arregloAC.=$datos_usuario[tipo_id_paciente].",";
        $arregloAC.=$datos_usuario[paciente_id].",";
        $arregloAC.=$this->FechaStamp($FC).",";//Fecha de la consulta
        $arregloAC.=$autorizacion.",";//numero de autorizacion
        $arregloAC.=$cod_cargo.",";//codigo procedimiento
        $arregloAC.=$tipo_finalidad_id.",";//finalidad de la consulta
        $arregloAC.=$tipo_atencion_id.",";//causa externa
        $arregloAC.=$diagp.",";//cod diag ppal
        $arregloAC.=$diag[0].",";//cod diag relacionado No.1
        $arregloAC.=$diag[1].",";//cod diag relacionado No.2
        $arregloAC.=$diag[2].",";//cod diag relacionado No.3
        $arregloAC.=$tipo.",";//tipo diag ppal
        $arregloAC.=$valor_cubierto.",";//valor consulta
        $arregloAC.=$valor_cuota_moderadora.",";
        $arregloAC.=$valor_total_empresa;
        $arregloAC.=$this->nl;
        $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$d_envio[cantidad],$valor_total_empresa);
        return true;
    }
    
    /**
    *
    */
    function CallConstruyeAP(&$arregloAP,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$tipo_rips,$datos_cups,&$vectorAD,$datos_entidad_administradora)
    {
            $ambito='';
            //arciho AP
            if($datos_entidad_administradora[sw_rips_con_cargo_cups] == '1')
                $cod_cargo = $d_envio[cargo_cups];
            else
                $cod_cargo = $d_envio[cargo_tarifario];
            $diag_odontologicoOP = '';
            $atendido_por ='5';// este coresponde a "otro", revizar por que no esta parametrizado
            if($tipo_rips == 'OP')
            {
                $diag_odontologico = GetDiagnosticosOdontologiaAP($d_envio[transaccion]);
                $atendido_por ='5';
            }
            else
            {
                $atendido_por = $this -> GetTipoProfesional($d_envio[transaccion]);
            }
            if(empty($d_envio[cargo_manual]))
            {
                $ambito=$this->GetAmbito($d_envio[servicio_cargo]);
                $finalidad_proc = "1";
                $fecha_proc = $this->FechaStamp($d_envio[fecha_cargo]);
            }
            else
            {//cargos ingresados manualmente
                $fecha_proc         = $d_envio[AP_FechaProcedimiento];
                $autorizacion   = $d_envio[autorizacion];
                $ambito                 = $d_envio[AP_AmbitoProcedimiento];
                $finalidad_proc = $d_envio[AP_FinalidadProcedimiento];
            }

                        if($datos_entidad_administradora[sw_tipo_plan] == '3')
                        {
                                $valor_cubierto = 0;
                        }
                        else
                        {
                                $valor_cubierto =$d_envio[valor_cubierto];
                        }
            $arregloAP.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R
            $arregloAP.=$datos_prestador_servicio[codigo_sgsss].",";
            $arregloAP.=$datos_usuario[tipo_id_paciente].",";//R
            $arregloAP.=$datos_usuario[paciente_id].",";//R
            $arregloAP.=$fecha_proc.",";//R fecha procedimiento
            $arregloAP.=$autorizacion.",";//numero autorizacion
            $arregloAP.=$cod_cargo.",";//R codigo procedimiento
            $arregloAP.=$ambito.",";//R ambito de realizacion del procedimiento
            $arregloAP.=$finalidad_proc.",";//R finalidad del procedimeinto
            $arregloAP.=$atendido_por[codigo_rips].",";//personal que atiende
            $arregloAP.=$diag_odontologicoOP.",";//diag ppal
            $arregloAP.=",";//diag relacionado
            $arregloAP.=",";//complicacion
            $arregloAP.=",";//forma de realizacion del acto quirurgico
            $arregloAP.=$valor_cubierto;//R
            $arregloAP.=$this->nl;
            $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$d_envio[cantidad],$d_envio[valor_cubierto]);
            return true;
    }
    
    /**
    *
    */
    function CallConstruyeAU(&$arregloAU,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion)
    {
        $destino='';
        $diagEp=$diagE='';
        $datoscama = $this ->GetDatosCama($transaccion);
        $causaExt=$this->GetCausaExterna($d_envio[ingreso]);
        $diagEgre=$this->GetDatosEgresoPaciente($d_envio[ingreso]);
        if(empty($d_envio[cargo_manual]))
        {
            for($m=0; $m<sizeof($diagEgre); $m++)
            {
                        if(!empty($diagEgre[$m][sw_principal]))
                        {
                                $diagEp=$diagEgre[$m][tipo_diagnostico_id];
                        }
                        else
                        {  $diagE[]=$diagEgre[$m][tipo_diagnostico_id];  }
            }
    
            $defuncion=$this->GetDatosDefuncion($d_envio[ingreso]);
            if(!empty($defuncion))
            {  $estado=2;   }//muerto
            else
            {  $estado=1;   }//vivo
            
            if($datoscama[tipo_clase_cama_id] == 3)
            {   
                    $destino = $this->GetDestinoPaciente($d_envio[ingreso],$d_envio[numerodecuenta]);
            }
        }
        else
        {//cargos ingresados manualmente
            $datoscama[fecha_ingreso] = $d_envio[au_FechaIngreso];
            $datoscama[fecha_ingreso] = $d_envio[au_HoraIngreso];
            $autorizacion = $d_envio[autorizacion];
            $causaExt = $d_envio[au_CausaExterna];
            $diagEp = $d_envio[au_DiagnosticoSalida];
            $diagE[0] = '';
            $diagE[1] = '';
            $diagE[2] = '';
            $destino = $d_envio[au_DestinoSalida];
            $estado = $d_envio[au_EstadoSalida];
            $defuncion = '';//ojo mirar esto
            $datoscama[fecha_egreso] = $d_envio[au_FechaSalida];
            $datoscama[fecha_egreso] = $d_envio[au_HoraSalida];
        }
        $arregloAU.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";
        $arregloAU.=$datos_prestador_servicio[codigo_sgsss].",";
        $arregloAU.=$datos_usuario[tipo_id_paciente].",";
        $arregloAU.=$datos_usuario[paciente_id].",";
        $arregloAU.=$this->FechaStamp($datoscama[fecha_ingreso]).",";//R fecha ingreso
        $arregloAU.=$this->HoraStamp($datoscama[fecha_ingreso]).",";//R hora de egreso
        $arregloAU.=$autorizacion.",";//numero autorizacion
        $arregloAU.=$causaExt.",";//R causa externa
        $arregloAU.=$diagEp.",";//R diag salida
        $arregloAU.=$diagE[0].",";//diag salida No1
        $arregloAU.=$diagE[1].",";//diag salida No2
        $arregloAU.=$diagE[2].",";//diag salida No3
        $arregloAU.=$destino.",";//R destino Salida
        $arregloAU.=$estado.",";//R estado salida
        $arregloAU.=$defuncion.",";//causa muerte
        $arregloAU.=$this->FechaStamp($datoscama[fecha_egreso]).",";//R fecha salida
        $arregloAU.=$this->HoraStamp($datoscama[fecha_egreso]);//r hora Salida
        $arregloAU.=$this->nl;
        return true;
    }
    
    /**
    *
    */
    function CallConstruyeUS(&$arregloUS,$d_envio,$datos_entidad_administradora,$datos_usuario)
    {
        $regimen = $this->GetRegimen($d_envio[plan_id]);
        $Edad=CalcularEdad($datos_usuario[fecha_nacimiento],date('Y-m-d'));
        
        $arregloUS.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloUS.=$datos_usuario[paciente_id].",";//R
        $arregloUS.=$datos_entidad_administradora[codigo_sgsss].",";//R
        $arregloUS.=$regimen[regimen_id].",";//R
        $arregloUS.=$datos_usuario[primer_apellido].",";
        $arregloUS.=$datos_usuario[segundo_apellido].",";
        $arregloUS.=$datos_usuario[primer_nombre].",";
        $arregloUS.=$datos_usuario[segundo_nombre].",";
        $arregloUS.=$Edad[edad_rips].",";
        $arregloUS.=$Edad[unidad_rips].",";
        $arregloUS.=$datos_usuario[sexo_id].",";
        $arregloUS.=$datos_usuario[tipo_dpto_id].",";
        $arregloUS.=$datos_usuario[tipo_mpio_id].",";
        $arregloUS.=$datos_usuario[zona_residencia];
        $arregloUS.=$this->nl;
        return true;
    }
    
    /**
    *
    */
    function CallConstruyeAH(&$arregloAH,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion)
    {
        $fechas='';
        $datosingreso = $this -> GetDatosIngreso($d_envio[ingreso]);
        $causaExt=$this->GetCausaExterna($d_envio[ingreso]);
        $diagEgre=$this->GetDatosEgresoPaciente($d_envio[ingreso]);
        //$datoscama = $this ->GetDatosCama($transaccion);
        $datoscama = $this ->GetDatosCama($d_envio[numerodecuenta]);
        if(empty($d_envio[cargo_manual]))
        {
            //$DatosIngreso=$this->GetDatosIngreso($ing[0][ingreso]);
            $DatosIngreso=$this->GetDatosIngreso($d_envio[ingreso]);
            for($m=0; $m<sizeof($diagEgre); $m++)
            {
                        if(!empty($diagEgre[$m][sw_principal]))
                        {
                                $diagEp=$diagEgre[$m][tipo_diagnostico_id];
                        }
                        else
                        {  $diagE[]=$diagEgre[$m][tipo_diagnostico_id];  }
            }
            $defuncion=$this->GetDatosDefuncion($d_envio[ingreso]);
            if(!empty($defuncion))
            {  $estado=2;   }//muerto
            else
            {  $estado=1;   }//vivo
        }
        else
        {//cargos ingresados manualmente
            $DatosIngreso[via_ingreso_id] = $d_envio[ah_viaingreso];
            $datoscama[fecha_ingreso] = $d_envio[ah_FechaIngreso];
            $datoscama[fecha_ingreso] = $d_envio[ah_horarioingreso];
            $autorizacion = $d_envio[autorizacion];
            $causaExt = $d_envio[ah_CausaExterna];
            $DatosIngreso[tipo_diagnostico_id] = $d_envio[ah_DiagnosticoIngreso];
            $diagEp = $d_envio[ah_DiagnosticoSalida];
            $diagE[0] = '';
            $diagE[1] = '';
            $diagE[2] = '';
            $estado = $d_envio[aah_EstadoSalida];
            $datoscama[fecha_egreso] = $d_envio[ah_FechaSalida];
            $datoscama[fecha_egreso] = $d_envio[ah_horariosalida];
        }
        $arregloAH.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R
        $arregloAH.=$datos_prestador_servicio[codigo_sgsss].",";//R
        $arregloAH.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloAH.=$datos_usuario[paciente_id].",";//R
        $arregloAH.=$datosingreso[via_ingreso_id].",";//R
        $arregloAH.=$this->FechaStamp($datoscama[fecha_ingreso]).",";
        $arregloAH.=$this->HoraStamp($datoscama[fecha_ingreso]).",";//R
        $arregloAH.=$autorizacion.",";
        $arregloAH.=$causaExt.",";//R
        $arregloAH.=$DatosIngreso[tipo_diagnostico_id].",";//R
        $arregloAH.=$diagEp.",";//R
        $arregloAH.=$diagE[0].",";
        $arregloAH.=$diagE[1].",";
        $arregloAH.=$diagE[2].",";
        $arregloAH.=",";
        $arregloAH.=$estado.",";//R
        $arregloAH.=$defuncion.",";
        $arregloAH.=$this->FechaStamp($datoscama[fecha_egreso]).",";//R
        $arregloAH.=$this->HoraStamp($datoscama[fecha_egreso]);//R
        $arregloAH.=$this->nl;
        return true;
    }
        /**
    *
    */
    function SetAcumuladorAD(&$vectorAD,$factura,$cod_prestador,$cod_concepto,$cantidad,$vlr_tot_concepto)
    {
        $vectorAD['VECTOR'][$factura][$cod_concepto]['factura']=$factura;
        $vectorAD['VECTOR'][$factura][$cod_concepto]['codigo_sgsss']=$cod_prestador;
        $vectorAD['VECTOR'][$factura][$cod_concepto]['concepto_rips']=$cod_concepto;
        $vectorAD['VECTOR'][$factura][$cod_concepto]['cantidad']+=$cantidad;
        $vectorAD['VECTOR'][$factura][$cod_concepto]['valor_cubierto']+=$vlr_tot_concepto;
        
        return true;
    }
    /**
    *
    */
    function CallConstruyeAD(&$arregloAD,&$vectorAD,&$ad)
    {
        foreach($vectorAD as $vAD => $AD1)
        {
            foreach($AD1 as $AD2 => $AD3)
            {
                foreach($AD3 as $AD => $datos)
                {
                    $arregloAD.=$datos[factura].",";
                    $arregloAD.=$datos[codigo_sgsss].",";
                    $arregloAD.=$datos[concepto_rips].",";
                    $arregloAD.=$datos[cantidad].",";
                    $arregloAD.=0 .",";
                    $arregloAD.=$datos[valor_cubierto];
                    $arregloAD.=$this->nl;
                    $ad++;
                }
            }
        }
        return true;
    }
    
    /**
    *
    */
    function CallConstuyeArchivos($codigo_sgsss,$envio,
                                                                $arregloAF,$arregloAT,$arregloAM,$arregloAC,$arregloAP,$arregloAH,$arregloAD,$arregloUS,
                                                                $ap,$ac,$ad,$us,$af,$at,$ah,$am,$au)
    {
                    $envio=str_pad($envio, 6, "0", STR_PAD_LEFT);

            if(!$this->AbrirArchivo('AF'.$envio.'.txt','w+',$envio))
            {
                    return false;
            }
            $this->EscribirArchivo($arregloAF);
            $this->CerrarArchivo();
            $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AF".$envio.",".$af.$this->nl;

            if(!$this->AbrirArchivo('US'.$envio.'.txt','w+',$envio))
            {
                    return false;
            }
            $this->EscribirArchivo($arregloUS);
            $this->CerrarArchivo();
            $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",US".$envio.",".$us.$this->nl;

            if(!empty($arregloAD))
            {
                    if(!$this->AbrirArchivo('AD'.$envio.'.txt','w+',$envio))
                    {
                            return false;
                    }
                    $this->EscribirArchivo($arregloAD);
                    $this->CerrarArchivo();
                    $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AD".$envio.",".$ad.$this->nl;
            }
            if(!empty($arregloAC))
            {
                    if(!$this->AbrirArchivo('AC'.$envio.'.txt','w+',$envio))
                    {
                            return false;
                    }
                    $this->EscribirArchivo($arregloAC);
                    $this->CerrarArchivo();
                    $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AC".$envio.",".$ac.$this->nl;
            }
            if(!empty($arregloAP))
            {
                    if(!$this->AbrirArchivo('AP'.$envio.'.txt','w+',$envio))
                    {
                            return false;
                    }

                    $this->EscribirArchivo($arregloAP);
                    $this->CerrarArchivo();
                    $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AP".$envio.",".$ap.$this->nl;
            }
            if(!empty($arregloAM))
            {
                    if(!$this->AbrirArchivo('AM'.$envio.'.txt','w+',$envio))
                    {
                            return false;
                    }
                    $this->EscribirArchivo($arregloAM);
                    $this->CerrarArchivo();
                    $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AM".$envio.",".$am.$this->nl;
            }
            if(!empty($arregloAT))
            {
                    if(!$this->AbrirArchivo('AT'.$envio.'.txt','w+',$envio))
                    {
                            return false;
                    }
                    $this->EscribirArchivo($arregloAT);
                    $this->CerrarArchivo();
                    $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AT".$envio.",".$at.$this->nl;
            }
            if(!empty($arregloAU))
            {
                    if(!$this->AbrirArchivo('AU'.$envio.'.txt','w+',$envio))
                    {
                            return false;
                    }
                    $this->EscribirArchivo($arregloAU);
                    $this->CerrarArchivo();
                    $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AU".$envio.",".$au.$this->nl;
            }
            if(!empty($arregloAH))
            {
                    if(!$this->AbrirArchivo('AH'.$envio.'.txt','w+',$envio))
                    {
                            return false;
                    }
                    $this->EscribirArchivo($arregloAH);
                    $this->CerrarArchivo();
                    $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AH".$envio.",".$ah.$this->nl;
            }
            if(!$this->AbrirArchivo('CT'.$envio.'.txt','w+',$envio))
            {
                    return false;
            }
            //echo "<br>CT-> ".$arregloCT;
            $this->EscribirArchivo($arregloCT);
            $this->CerrarArchivo();
            return true;
    }
//---------------------ENVIOS-----------------------------------------------------

    //con envios el vector es asi=> [envio] [empresa]
    /**
    *   @var ConsultaTipoRips = character
    *       Esta variable determina el modo por el cual se va a consutar el tipo de rips
    *       con el que se crera el archivo. Se maneja por defecto '1' que es como se maneja
    *       en Tulua consultando por 'grupo_tarifario' y 'subgrupo_tarifario'. En cali, por problemas
    *       de  parametrizacion se va a menejar con 'tipo_cargo' y 'grupo_tipo_cargo' 
    */
    function Envios($tipo,$id,$arregloenvio)
    {
echo "<pre>";
        $ap=$ac=$ad=$us=$af=$at=$ah=$au=$am=0;
        $arregloAF=$arregloAM=$arregloAT=$arregloAC=$arregloAP=$arregloAU=$arregloAN=$arregloAH=$arregloUS=$arregloCT=$vectorAD=$arregloCT='';
        $ConsultaTipoRips =ModuloGetVar('app','Facturacion_Fiscal','ConsultaTipoRips');
        $datos_prestador_servicio = $this->GetDatosPrestadorServicio($arregloenvio[0][empresa]);
        $detalle_envio = $this->GetDetalleEnvio($arregloenvio[0][envio],'');

//        $dat=$this->GetActoQuirurjico($arregloenvio[0][envio],$ConsultaTipoRips,$arregloenvio,$datos_prestador_servicio,&$arregloAP,&$vectorAD);
//        $dat1=explode('\*/', $dat);
//        $arregloAP=$dat1[0];//actos para ap
//        $ap=$dat1[1];       //lineas de ap
/*        if (!$this->ConstruirIMD(&$detalle,$datos_prestador_servicio,&$am,&$at,&$arregloAM,&$arregloAT,&$vectorAD)) 
        {
            $this->error = "No se generó el archivo AM/AT";
            $this->mensajeDeError = "Error al generar archivos AM/AT ";
            echo $this->mensajeDeError;
            return false;
        }
*/
        foreach($detalle_envio AS $det_envio => $d_envio)
        {
            $datos_entidad_administradora = $this->GetDatosEntidadAdministradora($d_envio[plan_id]);
            $datos_cups                                     = $this->GetDatosCups($d_envio[cargo_cups]);
            $tipo_rips                                      = $this->GetTipoRips($ConsultaTipoRips,$datos_cups[tipo_cargo],$datos_cups[grupo_tipo_cargo],$datos_cups[grupo_tarifario_id],$datos_cups[subgrupo_tarifario_id],$d_envio[tarifario_id],$d_envio[cargo_cups],$arregloenvio[0][empresa]);
            $datos_usuario                              = $this->GetDatosUsuario($d_envio[ingreso]);
            $autorizacion                               = $this->GetAutorizacion($d_envio,$d_envio[autorizacion_ext]);
            $sw_dato_complementario             = $this->GetSwDatosComplementarios($d_envio[cargo_cups]);

            //Se crea el Archivo AF
            if(empty($sw_AF[$d_envio[prefijo]][$d_envio[factura_fiscal]]))
            {
                $this->CallConstruyeAF(&$arregloAF,$d_envio,$datos_prestador_servicio,$datos_entidad_administradora);
                $sw_AF[$d_envio[prefijo]][$d_envio[factura_fiscal]] = true;
                $af++;
            }
            
            //Se crea el Archivo US
            if( empty( $sw_US[$datos_usuario[tipo_id_paciente]][$datos_usuario[paciente_id]] ) )
            {
                $this->CallConstruyeUS(&$arregloUS,$d_envio,$datos_entidad_administradora,$datos_usuario);
                $sw_US[$datos_usuario[tipo_id_paciente]][$datos_usuario[paciente_id]] = true;
                $us++;
            }
            
          if(!empty($d_envio[consecutivo1]))
            {
                if(!empty($d_envio[codigo_medicamento]))
                {
                    //ingresa medicamentos
                    $this->CallConstruyeAM(&$arregloAM,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
                    $am++;
                }
                elseif(empty($d_envio[codigo_medicamento]))
                {
                    //ingresa insumos
                    $this->CallConstruyeAT(&$arregloAT,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
                    $at++;
                }
            }
                switch ($tipo_rips)
                {
                    case 'AT':
                    {
                                $this->CallConstruyeAT(&$arregloAT,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
                                $at++;
                                break;
                    }
                    case 'AC':
                    {
                                $this->CallConstruyeAC(&$arregloAC,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_entidad_administradora,$datos_cups,&$vectorAD);
                                $ac++;
                                break;
                    }
                    case 'AP':
                    {
                        if($d_envio[consecutivo]==NULL AND $d_envio[cargo]==NULL)
                        { 
                                $this->CallConstruyeAP(&$arregloAP,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$tipo_rips,$datos_cups,&$vectorAD);
                                $ap++;
                                break;
                        }
                        else
                        {
                                break;
                        }
                    }
                    case 'OP':
                    {
                                $this->CallConstruyeAP(&$arregloAP,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$tipo_rips,$datos_cups,&$vectorAD);
                                $ap++;
                                break;
                    }
                    case 'AU':
                    {
                                $this->CallConstruyeAU(&$arregloAU,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion);
                                $au++;
                                break;
                    }
                    case 'AN':
                    {
                                break;
                    }
                    case 'AH':
                    {
                                $this->CallConstruyeAH(&$arregloAH,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion);
                                $ah++;
                                break;
                    }
                    default:
                    {
                                break;
                    }
                }//fin switch
                //si se inserta informacion en los archivos Ac,AP,AT, puede ser necesario 
                //el ingreso de informacion necesaria dentro de AU,AN,AH segun se parametrize se la tabla rips_datos_complementarios
            if($sw_dato_complementario)
            {
                if($sw_dato_complementario[sw_au] == '1')
                {
                    $this->CallConstruyeAU(&$arregloAU,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion);
                    $au++;
                }
                if($sw_dato_complementario[sw_an] == '1')
                {
                    //construir archivo AN
                }
                if($sw_dato_complementario[sw_ah] == '1')
                { 
                    $this->CallConstruyeAH(&$arregloAH,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion);
                    $ah++;
                }
            }
        }//fin foreach
        
        $this -> CallConstruyeAD(&$arregloAD,&$vectorAD,&$ad);
        
        echo "<br>AF-> ".$arregloAF;
        echo "<br>AT-> ".$arregloAT;
        echo "<br>AM-> ".$arregloAM;
        echo "<br>AC-> ".$arregloAC;
        echo "<br>AP-> ".$arregloAP;
        echo "<br>AH-> ".$arregloAH;
        echo "<br>AD-> ".$arregloAD;
        echo "<br>US-> ".$arregloUS;
echo "</pre>";      
        if($this -> CallConstuyeArchivos( $datos_prestador_servicio[codigo_sgsss],$arregloenvio[0][envio],
                                                                            $arregloAF,$arregloAT,$arregloAM,$arregloAC,$arregloAP,$arregloAH,$arregloAD,$arregloUS,
                                                                            $ap,$ac,$ad,$us,$af,$at,$ah,$am,$au) )
            return true;
        else
            return false;
    }
//**************************************************************************************

    function ConstruirIMD(&$det,$datos_prestador_servicio,&$am,&$at,&$arregloAM,&$arregloAT,&$vectorAD)
    {
        $lim=sizeof($det);
        $j=$k=$l=$j1=$k1=$l1=$cantidadMD=$precioMD=$cantidadDMD=$precioDMD=$cantidadI=$precioI=$cantidadDI=$precioDI=0;
        for($i=0; $i<=$lim; $i++)
        {
            if(empty($det[$i][consecutivo1]))
            {
               UNSET($det[$i]);
            }
        }
 echo sizeof($det);
/*        for($i=0; $i<=$lim; $i++)
        {
            if($det[$i][cargo_tarifario]!='IMD')
            {
               UNSET($det[$i]);
            }
        }*/
        $lim=sizeof($det);
        for($i=0; $i<=$lim; $i++)
        {
            if(!empty($det[$i]))
            {
                //MEDICAMENTOS
                if(!empty($det[$i][codigo_medicamento]))
                {
                    $j=$i;
                    while($det[$i][prefijo]==$det[$j][prefijo] 
                                AND $det[$i][factura_fiscal]==$det[$j][factura_fiscal]
                                AND $det[$j][cargo_tarifario]=='IMD'
                                AND $det[$i][codigo_medicamento]==$det[$j][codigo_medicamento]
                                )
                    {
                        if (!empty($det[$j][codigo_medicamento]))
                        {
                            $cantidadMD+=$det[$j][cantidad];
                            $precioMD+=$det[$j][valor_cubierto];
                        }
                        $det[$j][cantidad]=$cantidadMD;
                        $det[$j][valor_cubierto]=$precioMD;
                        $j++;
                    }
                    if($cantidadMD>0)
                    {
                    $vectMD[$k]=$det[$j-1];
                    $k++;
                    $cantidadMD=$precioMD=0;
                    }
                    while($det[$i][prefijo]==$det[$j][prefijo] 
                                AND $det[$i][factura_fiscal]==$det[$j][factura_fiscal]
                                AND $det[$j][cargo_tarifario]=='DIMD'
                                AND $det[$i][codigo_medicamento]==$det[$j][codigo_medicamento]
                                )
                    {
                        if (!empty($det[$j][codigo_medicamento]))
                        {
                            $cantidadDMD+=$det[$j][cantidad];
                            $precioDMD+=$det[$j][valor_cubierto];
                        }
                        $det[$j][cantidad]=$cantidadDMD;
                        $det[$j][valor_cubierto]=$precioDMD;
                        $j++;
                    }
                    if($cantidadDMD>0)
                    {
                    $vectDMD[$l]=$det[$j-1];
                    $l++;
                    $cantidadDMD=$precioDMD=0;
                    }
                    //ingresa medicamentos
                    //$this->CallConstruyeAM(&$arregloAM,$det[$i],$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
                    //$am++;
                    $i=$j-1;
                    UNSET($det[$i]);
                }
                //INSUMOS
/*                elseif(empty($det[$i][codigo_medicamento]))
                {
                        $j1=$i;
                        while($det[$i][prefijo]==$det[$j1][prefijo] 
                                    AND $det[$i][factura_fiscal]==$det[$j1][factura_fiscal]
                                    AND $det[$j1][cargo_tarifario]=='IMD'
                                    AND $det[$i][codigo_producto]==$det[$j1][codigo_producto]
                                    )
                        {
                            $cantidadI+=$det[$j1][cantidad];
                            $precioI+=$det[$j1][valor_cubierto];
                            $j1++;
                        }
                        if($cantidadI>0)
                        {
                            $det[$j1-1][cantidad]=$cantidadI;
                            $det[$j1-1][valor_cubierto]=$precioI;
                            $vectI[$k1]=$det[$j1-1];
                            $k1++;
                            $cantidadI=$precioI=0;
                        }
                        while($det[$i][prefijo]==$det[$j1][prefijo] 
                                    AND $det[$i][factura_fiscal]==$det[$j1][factura_fiscal]
                                    AND $det[$j1][cargo_tarifario]=='DIMD'
                                    AND $det[$i][codigo_producto]==$det[$j1][codigo_producto]
                                    )
                        {
                            $cantidadDI+=$det[$j1][cantidad];
                            $precioDI+=$det[$j1][valor_cubierto];
                            $j1++;
                        }
                        if($cantidadDI>0)
                        {
                            $det[$j1-1][cantidad]=$cantidadDI;
                            $det[$j1-1][valor_cubierto]=$precioDI;
                            $vectDI[$l1]=$det[$j1-1];
                            $l1++;
                            $cantidadDI=$precioDI=0;
                        }
                        //ingresa insumos
                        //$this->CallConstruyeAT(&$arregloAT,$det[$i],$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
                        //$at++;
                    $i=$j1-1;
                }*/
            }
        }

        for($i=0; $i<=$lim; $i++)
        {
            if(!empty($det[$i][codigo_medicamento]))
            {
               UNSET($det[$i]);
            }
        }
        $lim=sizeof($det);
/*        for($i=0; $i<=$lim; $i++)
        {
            if($det[$i][cargo_tarifario]!='IMD')
            {
               UNSET($det[$i]);
            }
        }
       $lim=sizeof($det);*/
echo '-->'.$lim.'<br>';
        foreach($det as $i=>$v)
        {
                            //INSUMOS
                            $j1=$i;
                            while($v[prefijo]==$det[$j1][prefijo] 
                                                    AND $v[factura_fiscal]==$det[$j1][factura_fiscal]
                                                    AND $det[$j1][cargo_tarifario]=='IMD'
                                                    AND $v[codigo_producto]==$det[$j1][codigo_producto]
                                        )
                            {//echo $det[$i][consecutivo1]; exit;
                                    $cantidadI+=$det[$j1][cantidad];
                                    $precioI+=$det[$j1][valor_cubierto];
                                    $j1++;
                            }
                            if($cantidadI>0)
                            {
                                $j2=$j1-1;
                                $tmp_IMD[$k1]=$cantidadI.'//'.$precioI.'//'.$j2; 
                                //$det[$j1-1][cantidad]=$cantidadI;
                                //$det[$j1-1][valor_cubierto]=$precioI;
                                //$vectI[$k1]=$det[$j1-1];
                                $k1++;
                                $cantidadI=$precioI=0;
                            }
                        while($det[$i][prefijo]==$det[$j1][prefijo] 
                                                    AND $det[$i][factura_fiscal]==$det[$j1][factura_fiscal]
                                                    AND $det[$j1][cargo_tarifario]=='DIMD'
                                                    AND $det[$i][codigo_producto]==$det[$j1][codigo_producto]
                                                    )
                            {
                                    $cantidadDI+=$det[$j1][cantidad];
                                    $precioDI+=$det[$j1][valor_cubierto];
                                    $j1++;
                            }
                            if($cantidadDI>0)
                            {
                                    $det[$j1-1][cantidad]=$cantidadDI;
                                    $det[$j1-1][valor_cubierto]=$precioDI;
                                    $vectDI[$l1]=$det[$j1-1];
                                    $l1++;
                                    $cantidadDI=$precioDI=0;
                            }
                        /*ingresa insumos
                            $this->CallConstruyeAT(&$arregloAT,$det[$i],$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
                            $at++;*/
                    $i=$j1-1;
                }
        $lim1=sizeof($det);
                $val=intval($lim1/1000);
                $mod=$lim1%1000;
                $lim2=sizeof($tmp_IMD);
//print_r($tmp_IMD);exit;
                $k2=0;
if($val>1)
{
$r1=0;
$r2=1000;
$val2=1;
                foreach($det as $i=>$v)
                {//echo $i;print_r($v); exit;
while($val2<=$val)
{
                    if($i>=1001 and $i<=2000)
                    {
                        for($j=0; $j<=$lim2; $j++)
                        {
                                $datos=explode('//',$tmp_IMD[$j]);
                                if($datos[2]==$i)
                                {
                                    $det[$i][cantidad]=$datos[0];
                                    $det[$i][valor_cubierto]=$datos[1];
                                    $vectI[$k2]=$det[$i];
                                    $k2++;
                                }
                        }
                    }
$r1=$r2+1;
$r2+=1000;
$val2++;
}
                }
  print_r($vectI);exit;
echo '--->'.$lim.'--->'.$val; exit;

}
  print_r($vectI);exit;
echo '--->'.$lim.'--->'.$val; exit;
        foreach($vectMD as $i=>$v)
        {
            foreach($vectDMD as $i1=>$v1)
            {
                if($v[prefijo]==$v1[prefijo]
                    AND $v[factura_fiscal]==$v1[factura_fiscal]
                    AND $v[codigo_producto]==$v1[codigo_producto]
                    AND $v[codigo_medicamento]==$v1[codigo_medicamento])
                    {
                        $v[cantidad]-=$v1[cantidad];
                        $v[valor_cubierto]+=$v1[valor_cubierto];
                        if($v[cantidad]==0 AND $v[valor_cubierto]==0)
                        {
                            UNSET($vectMD[$i]);
                        }
                        else
                        {
                            $vectMD[$i][cantidad]=$v[cantidad];
                            $vectMD[$i][valor_cubierto]=$v[valor_cubierto];
                        }
                    }
            }
        }
        foreach($vectMD as $i=>$v)
        {
            $datos_usuario                              = $this->GetDatosUsuario($v[ingreso]);
            $datos_cups                                     = $this->GetDatosCups($v[cargo_cups]);
            $autorizacion                               = $this->GetAutorizacion($v,$v[autorizacion_ext]);
            //ingresa medicamentos
            $this->CallConstruyeAM(&$arregloAM,$v,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
            $am++;
        }
        foreach($vectI as $i=>$v)
        {
            foreach($vectDI as $i1=>$v1)
            {
                if($v[prefijo]==$v1[prefijo]
                    AND $v[factura_fiscal]==$v1[factura_fiscal]
                    AND $v[codigo_producto]==$v1[codigo_producto])
                    {
                        $v[cantidad]-=$v1[cantidad];
                        $v[valor_cubierto]+=$v1[valor_cubierto];
                        if($v[cantidad]==0 AND $v[valor_cubierto]==0)
                        {
                            UNSET($vectI[$i]);
                        }
                        else
                        {
                            $vectI[$i][cantidad]=$v[cantidad];
                            $vectI[$i][valor_cubierto]=$v[valor_cubierto];
                        }
                    }
            }
        }
        foreach($vectI as $i=>$v)
        {
            $datos_usuario                              = $this->GetDatosUsuario($v[ingreso]);
            $datos_cups                                     = $this->GetDatosCups($v[cargo_cups]);
            $autorizacion                               = $this->GetAutorizacion($v,$v[autorizacion_ext]);
            //ingresa insumos
            $this->CallConstruyeAT(&$arregloAT,$v,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
            $at++;
        }
       UNSET($det);
       return true;
    }

    function XXDiagnosticoIngreso($ingreso)
    {
            list($dbconn) = GetDBconn();
            $query = "  SELECT c.tipo_diagnostico_id
                                    FROM    hc_diagnosticos_ingreso as c, hc_evoluciones as d
                                    WHERE d.ingreso=$ingreso and c.evolucion_id=d.evolucion_id
                                    and c.sw_principal='1'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {
                    $var=$result->fields[0];
            }

            $result->Close();
            return $var;
    }


    /**
    *
    */
    function XXBuscarNumeroOrden($transaccion)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.numero_orden_id
                                FROM os_maestro_cargos as a WHERE a.transaccion=$transaccion";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {  $var=$result->fields[0];  }
            $result->Close();
            return $var;
    }

    function XXDatosHonorarios($transaccion)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT b.valor
                                FROM cuentas_detalle_honorarios as b
                                WHERE b.transaccion=$transaccion";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {
                    $var=$result->GetRowAssoc($ToUpper = false);
            }

            $result->Close();
            return $var;
    }










    /**
    *
    */
    function XXBuscarProcedimientos($transaccion)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT c.autorizacion_int, c.autorizacion_ext, b.numero_orden_id
                                FROM os_maestro_cargos as a, os_maestro as b, os_ordenes_servicios as c
                                WHERE a.transaccion=$transaccion
                                and a.numero_orden_id=b.numero_orden_id
                                and b.orden_servicio_id=c.orden_servicio_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {  $var=$result->GetRowAssoc($ToUpper = false);  }
            $result->Close();
            return $var;
    }



    /**
    *
    */
    function XXDatosRipsEnvios($envio)
    {
    //, e.codigo_sgsss
    //left join terceros_sgsss as e on(c.tipo_id_tercero=e.tipo_id_tercero and c.tercero_id=e.tercero_id)
            list($dbconn) = GetDBconn();
            echo "<pre>".$query = "
                                SELECT  a.envio_id, 
                                                a.fecha_inicial, 
                                                a.fecha_final, 
                                                b.prefijo, 
                                                b.factura_fiscal,
                                                c.fecha_registro, 
                                                x.codigo_sgsss,
                                                --c.total_factura, 
                                                --c.valor_cuota_paciente, 
                                                --c.valor_cuota_moderadora, 
                                                --c.descuento,
                                                CASE g.sw_tipo_plan WHEN 3 THEN 0 ELSE c.total_factura END AS total_factura,
                                                CASE g.sw_tipo_plan WHEN 3 THEN 0 ELSE c.valor_cuota_paciente END AS valor_cuota_paciente,
                                                CASE g.sw_tipo_plan WHEN 3 THEN 0 ELSE c.valor_cuota_moderadora END AS valor_cuota_moderadora,
                                                CASE g.sw_tipo_plan WHEN 3 THEN 0 ELSE c.descuento END AS descuento,
                                                g.plan_descripcion,
                                                CASE C.TIPO_ID_TERCERO WHEN 'NIT' THEN 'NI' ELSE C.TIPO_ID_TERCERO END AS tipo_id_tercero,
                                                c.tercero_id, 
                                                g.num_contrato, 
                                                k.poliza, 
                                                i.ingreso, 
                                                i.tipo_afiliado_id, 
                                                i.numerodecuenta, 
                                                i.plan_id
                                FROM    envios as a, 
                                            envios_detalle as b, 
                                            fac_facturas as c,
                                            planes as g 
                                                LEFT JOIN terceros_sgsss as x
                                                    ON( x.tipo_id_tercero=g.tipo_tercero_id 
                                                            AND x.tercero_id=g.tercero_id),
                                            fac_facturas_cuentas as h,
                                            cuentas as i 
                                                LEFT JOIN ingresos_soat as j 
                                                    ON (i.ingreso=j.ingreso)
                                                LEFT JOIN soat_eventos as k 
                                                    ON(j.evento=k.evento)
                                WHERE a.envio_id=$envio 
                                            AND a.sw_estado in(1,0) 
                                            AND a.envio_id=b.envio_id
                                            AND b.prefijo=c.prefijo 
                                            AND b.factura_fiscal=c.factura_fiscal
                                            AND c.plan_id=g.plan_id 
                                            AND h.numerodecuenta=i.numerodecuenta
                                            AND h.prefijo=c.prefijo 
                                            AND h.factura_fiscal=c.factura_fiscal
                                ORDER BY c.prefijo,c.factura_fiscal
                                
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $var;
    }




//-------------------------------FACTURAS-------------------------------------
//EL vector es asi array('prefijo'=>$a[0],'factura'=>$a[1],'plan'=>$a[2],'empresa'=>$a[3]);
//quite
    /*function Facturas($arregloenvio)
    {
            $dat=$this->DatosPrestadorServicio($arreglofactura[0][empresa]);
            for($i=0; $i<sizeof($arreglofactura); $i++)
            {
                    $this->DatosRipsFacturas($arreglofactura[$i][prefijo],$arreglofactura[$i][factura]);
            }
    }

    function DatosRipsFacturas($prefijo,$factura)
    {
            list($dbconn) = GetDBconn();
            $query = "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $var;
    }*/

//-----------------------------------------------------------------------------------



    function XXNombreTercero($tipo,$id)
    {
            list($dbconn) = GetDBconn();
            if($tipo == 'NI') $tipo = 'NIT';
            $query = "  select  a.nombre_tercero, 
                                                            b.codigo_sgsss
                                            from        terceros as a 
                                                            left join terceros_sgsss as b
                                                                on( a.tipo_id_tercero=b.tipo_id_tercero 
                                                                        and a.tercero_id=b.tercero_id)
                                            where   a.tipo_id_tercero='$tipo' and a.tercero_id='$id'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $var=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $var;
    }

    function XXDatosUsuario($tipo,$id)
    {
            list($dbconn) = GetDBconn();
            $query = "select b.* from  pacientes as b
                                where b.tipo_id_paciente='$tipo' and b.paciente_id='$id'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $var=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $var;
    }




//-------------------------------DATOS CONSULTA---------------------------------

    function XXDatosConsulta($cuenta)
    {
            $des=ModuloGetVar('app','Facturacion_Fiscal','CargoDescuento');
            $apr=ModuloGetVar('app','Facturacion_Fiscal','CargoAprovechamiento');
            list($dbconn) = GetDBconn();
            $query = "
            SELECT 
                a.cantidad, 
                case when b.concepto_rips IS NULL then e.concepto_rips ELSE b.concepto_rips end as concepto_rips,
                d.sw_tipo_plan,
                --case d.sw_tipo_plan when 3 then 0 else a.valor_cargo end as valor_cargo,
                case d.sw_tipo_plan when 3 then 0 else a.valor_cubierto end as valor_cargo,
                case d.sw_tipo_plan when 3 then 0 else a.precio end as precio,
                case d.sw_tipo_plan when 3 then 0 else c.valor_cuota_moderadora end as valor_cuota_moderadora,
                case d.sw_tipo_plan when 3 then 0 else c.valor_cuota_paciente end as valor_cuota_paciente,
                case d.sw_tipo_plan when 3 then 0 else c.valor_total_empresa end as valor_total_empresa,
                a.transaccion,
                a.cargo_cups,
                a.fecha_cargo,
                a.tarifario_id,
                a.cargo,
                c.empresa_id,
                
                a.consecutivo,
                a.autorizacion_ext,
                a.autorizacion_int,
                
                e.descripcion,
                a.servicio_cargo,
                
                b.tipo_cargo,
                b.grupo_tipo_cargo,
                b.grupo_tarifario_id,
                b.subgrupo_tarifario_id,
                
                d.sw_rips_con_cargo_cups
                
            FROM 
                cuentas_detalle as a
                    LEFT JOIN cups as b 
                        ON(a.cargo_cups = b.cargo),
                cuentas as c, 
                planes as d, 
                tarifarios_detalle as e
            WHERE 
                a.numerodecuenta=$cuenta
                AND a.facturado='1'$usu
                and a.cargo not in('$des','$apr')
                and a.numerodecuenta=c.numerodecuenta
                and c.plan_id=d.plan_id
                and a.cargo=e.cargo
                and a.tarifario_id=e.tarifario_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $var;
    }


    function XXBuscaCita($cups)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT count(cargo_cita)
                                FROM cargos_citas WHERE cargo_cita='$cups'";
            /*$query = "SELECT count(cargo)
                                FROM cups
                                WHERE cargo='$cups' and grupo_tipo_cargo='CM'";*/
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $var=$result->fields[0];
            
            $result->Close();
            return $var;
    }



    function XXDatosInsumos($consecutivo)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT e.descripcion_abreviada
                                FROM bodegas_documentos_d as c, inventarios_productos as e
                                WHERE c.consecutivo=$consecutivo and c.codigo_producto=e.codigo_producto
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $var=$result->GetRowAssoc($ToUpper = false);

            $result->Close();
            return $var;
    }

    function GetActoQuirurjico($detalle_envio1,$ConsultaTipoRips,$arregloenvio,$datos_prestador_servicio,&$arregloAP,&$vectorAD)
    {
        $detalle1=$this->GetDetalleEnvio($detalle_envio1,'aq');

    //
//echo $detalle1->_numOfRows;exit;
        for ($i=0; $i < $detalle1->_numOfRows; $i++)
        { 
         //$_SESSION['RIPS'][$i]=$result->FetchRow();
         //$var[$i]=&$detalle1->FetchRow();
         $var[$i]=&$detalle1->GetRowAssoc($ToUpper = false);
         $detalle1->MoveNext();
        }
//echo '<--2';exit;
        $detalle=$var;
        //print_r($detalle); exit;
    //
        //echo '-->'.sizeof($detalle);exit;
         //$detalle=$_SESSION['RIPS']['VECT'];
         //print_r($detalle,false); exit;
         //UNSET($_SESSION['RIPS']['VECT']);
       //echo sizeof($detalle); exit;
        

        $precio=$k=$lineas=0;
        $tmpcargo=$tmpcodigo_agrupamiento='';
        for($i=0; $i<sizeof($detalle); $i++)
        {
            //si es un acto quirurjico
            while($detalle[$i][consecutivo]==NULL AND $detalle[$i][codigo_agrupamiento_id]!=NULL AND $detalle[$i][cargo]!=NULL)
            {
                $k=$i;
                while($detalle[$i][codigo_agrupamiento_id]==$detalle[$k][codigo_agrupamiento_id])
                {
                    $l=0;
                    $l=$k;
                    while($detalle[$k][cargo]==$detalle[$l][cargo])
                    {
                        $precio+=$detalle[$l][valor_cubierto];
                        $l++;
                    }
                    $k=$l-1;
                    $ambito='';
                    //arciho AP
                    $datos_entidad_administradora = $this->GetDatosEntidadAdministradora($detalle[$l-1][plan_id]);
                    $datos_cups                                     = $this->GetDatosCups($detalle[$l-1][cargo_cups]);
                    $tipo_rips                                      = $this->GetTipoRips($ConsultaTipoRips,$datos_cups[tipo_cargo],$datos_cups[grupo_tipo_cargo],$datos_cups[grupo_tarifario_id],$datos_cups[subgrupo_tarifario_id],$detalle[$l-1][tarifario_id],$detalle[$l-1][cargo_cups],$arregloenvio[0][empresa]);
                    $datos_usuario                              = $this->GetDatosUsuario($detalle[$l-1][ingreso]);
                    $autorizacion                               = $this->GetAutorizacion($detalle_envio,$detalle[$l-1][autorizacion_ext]);
                    $sw_dato_complementario             = $this->GetSwDatosComplementarios($detalle[$l-1][cargo_cups]);

                    if($datos_entidad_administradora[sw_rips_con_cargo_cups] == '1')
                        $cod_cargo = $detalle[$l-1][cargo_cups];
                    else
                        $cod_cargo = $detalle[$l-1][cargo_tarifario];
                    $diag_odontologicoOP = '';
                    $atendido_por ='5';// este coresponde a "otro", revizar por que no esta parametrizado
                    if($tipo_rips == 'OP')
                    {
                        $diag_odontologico = GetDiagnosticosOdontologiaAP($detalle[$l-1][transaccion]);
                        $atendido_por ='5';
                    }
                    else
                    {
                        $atendido_por = $this -> GetTipoProfesional($detalle[$l-1][transaccion]);
                    }

                    if(empty($detalle[$l-1][cargo_manual]))
                    {
                        $ambito=$this->GetAmbito($detalle[$l-1][servicio_cargo]);
                        $finalidad_proc = "1";
                        $fecha_proc = $this->FechaStamp($detalle[$l-1][fecha_cargo]);
                    }
                    else
                    {//cargos ingresados manualmente
                        $fecha_proc         = $detalle[$l-1][AP_FechaProcedimiento];
                        $autorizacion   = $detalle[$l-1][autorizacion];
                        $ambito                 = $detalle[$l-1][AP_AmbitoProcedimiento];
                        $finalidad_proc = $detalle[$l-1][AP_FinalidadProcedimiento];
                    }
                    $arregloAP.=$detalle[$l-1][prefijo]."".$detalle[$l-1][factura_fiscal].",";//R
                    $arregloAP.=$datos_prestador_servicio[codigo_sgsss].",";
                    $arregloAP.=$datos_usuario[tipo_id_paciente].",";//R
                    $arregloAP.=$datos_usuario[paciente_id].",";//R
                    $arregloAP.=$fecha_proc.",";//R fecha procedimiento
                    $arregloAP.=$autorizacion.",";//numero autorizacion
                    $arregloAP.=$detalle[$l-1][cargo].",";;//R
                    $arregloAP.=$ambito.",";//R ambito de realizacion del procedimiento
                    $arregloAP.=$finalidad_proc.",";//R finalidad del procedimeinto
                    $arregloAP.=$atendido_por[codigo_rips].",";//personal que atiende
                    $arregloAP.=$diag_odontologicoOP.",";//diag ppal
                    $arregloAP.=",";//diag relacionado
                    $arregloAP.=",";//complicacion
                    $arregloAP.=",";//forma de realizacion del acto quirurgico
                    $arregloAP.=$precio;
                    $arregloAP.=$this->nl;
                    $k++;
                    $precio=0;
                    $lineas++;
                }
                $i=$k;
            }
            //$this -> SetAcumuladorAD(&$vectorAD,$detalle[$i][prefijo]."".$detalle[$i][factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$detalle[$i][cantidad],$detalle[$i][valor_cubierto]);
        }
        //print_r($arregloAP); echo 'lineas-->'.$lineas; exit;
        return $arregloAP.'\*/'.($lineas);
    }//fin construye ACTO QUIRURJICO -- AP()

}//fin clase rips

?>
