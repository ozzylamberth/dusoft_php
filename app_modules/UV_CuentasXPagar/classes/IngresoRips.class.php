<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: IngresoRips.class.php,v 1.5 2009/01/14 22:22:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : IngresoRips
  * Clase en la cual se maneja la logica sportada por el ingreso de los archivos
  * Rips
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class IngresoRips extends ConexionBD
  {
    /**
    * Variable que almacena el numero del envio que se este procesando
    * @var String
    * @access public
    */
    var $indice = "";
    /**
    * Variable que almacena los archivos que deben ser solicitados
    * @var String
    * @access public
    */
    var $archivos = array();
    /**
    * Variable que almacena los archivos que deben ser solicitados
    * @var String
    * @access public
    */
    var $nombre_archivos = array();
    /**
    * Variable que los usuarios x facturas
    * @var array
    * @access public
    */
    var $usr_facturas = array();
    /**
    * Contructor de la clase
    */
    function IngresoRips(){}
    /**
    * Retorna el error generado en cualquiera de los proceso realizados por esta clase
    *
    * @return String
    */
    function ObtenerError()
    {
      return $this->error;
    }
    /**
    * Funcion para hacer la subida de los archivos Rips a un directorio temporal
    *
    * @return boolean
    */
    function SubirArchivoControl($datos) 
    {
      $radicacion = $this->ObtenerRadicacion($datos['radicacion_id']);
      
      if (is_uploaded_file($_FILES['archivo_control']['tmp_name']))
      {
        $errores = array();
        $nombre_archivo = $_FILES [ 'archivo_control' ][ 'name' ];
        
        $this->indice .= preg_replace("/.txt|.TXT|CT/", "",$nombre_archivo);
        
        $envio = UserGetUID()."_".$this->indice;
        $this->nombre_archivos[$envio]['CT'] = $nombre_archivo;
        global $ConfigAplication;
       
        $this->BorrarArchivos( $ConfigAplication['DIR_SIIS']."/rips/TMP_".$envio.'/'.$nombre_archivo);
        
        if(mkdir( $ConfigAplication['DIR_SIIS']."/rips/TMP_".$envio))
          move_uploaded_file ( $_FILES['archivo_control']['tmp_name'], $ConfigAplication['DIR_SIIS']."/rips/TMP_".$envio.'/'.$nombre_archivo ); 
                
        $ruta_archivo =  $ConfigAplication['DIR_SIIS']."/rips/TMP_".$envio.'/'.$nombre_archivo;
        $lines = file($ruta_archivo);
        
        $i = 0;
        $arch = "";
        $obligatorios = 0;
        $this->archivos = array();
        foreach ($lines as $line_num => $line) 
        {
          $datos = explode(",", $line);
          if($radicacion['codigo_sgsss'] == $datos[0])
          {
            $indice = preg_replace("/[0-9]/", "", $datos[2]);
            if(preg_match("/AF|US/",$datos[2]))
            {
              $this->archivos[$envio][$indice]['archivo'] = $datos[2];
              $this->archivos[$envio][$indice]['cantidad'] = $datos[3];
              $obligatorios++;
            }
            else if(preg_match("/AD|AC|AP|AH|AU|AN|AM|AT/",$datos[2]))
              {
                $this->archivos[$envio][$indice]['archivo'] = $datos[2];
                $this->archivos[$envio][$indice]['cantidad'] = $datos[3];
              }
              else
              {
                ($acrh == "")? $acrh = $datos[2]: $acrh .= ", ".$datos[2] ;
              }
          }
          else
          {
            $this->mensajeDeError .= "EL CODIGO DEL PROVEEDOR SELECCIONADO: ".$radicacion['codigo_sgsss'].", NO CORRESPONDE AL CODIGO DEL PROVEEDOR DEL ARCHIVO RIPS: ".$datos[0];
            return false;
          }
        }
        
        if(!empty($errores))
        {
          $this->mensajeDeError = "EN EL ARCHIVO DE CONTROL SE RELACIONAN LOS SIGUIENTES ARCHIVOS : $arch, QUE NO SON REQUERIDOS, POR FAVOR REVISAR";
          return false;
        }
        
        if($obligatorios != 2)
        {
          $this->mensajeDeError = "POR FAVOR REVISAR ARCHIVO DE CONTROL, ALGUNO DE LOS ARCHIVOS OBLIGATORIOS AF O US , NO ESTA REGISTRADO";
          return false;
        }
        if(empty($this->archivos))
        {
          $this->mensajeDeError = "POR FAVOR REVISAR ARCHIVO DE CONTROL, NO ESTA PRESENTE NINGUNO DE LOS ARCHIVOS AC,AP,AH,AU,AN,AM O AT";
          return false;
        }
        return true;
      }
      else
      {
        $this->error = "HA OCURRIDO UN ERROR MIENTRAS SE SUBIA EL ARCHIVO AL SERVIDOR";
        return false;
      }
    }
    /**
    * Funcion encargada de subir los demas archivos del envioa al servidor
    *
    * @param array $archivos Vector con los datos de los archivos que van a ser subidos
    * @param String $indice Cadena que contiene el indice de los archivos que se estan trabajando
    *
    * @return boolean
    */
    function SubirArchivosRips($archivos,$indice) 
    {
      global  $ConfigAplication;
      
      $envio = UserGetUID()."_".$indice;
      foreach($archivos[$envio] as $key => $valor)
      {      
        if (is_uploaded_file($_FILES[$valor['archivo']]['tmp_name']))
        {
          $nombre_archivo = $_FILES [$valor['archivo'] ][ 'name' ];
          $ruta_archivo =  $ConfigAplication['DIR_SIIS']."/rips/TMP_".$envio.'/'.$nombre_archivo;
          
          $this->nombre_archivos[$envio][$key] = $nombre_archivo;
          
          move_uploaded_file ( $_FILES[$valor['archivo']]['tmp_name'], $ruta_archivo ); 
          
          $lines = file($ruta_archivo);
          if(sizeof($lines) != $valor['cantidad'])
          {
            $this->error = "LA CANTIDAD DE DATOS QUE CONTIENE EL ARCHIVO ".$nombre_archivo.", NO CORRESPONDE A LAS DESCRIPTAS EN EL ARCHIVO DE CONTROL, FAVOR VERIFICAR EL ARCHIVO";
            return false;
          }
        }
        else
        {
          $this->error = "HA OCURRIDO UN ERROR MIENTRAS SE SUBIA EL ARCHIVO AL SERVIDOR";
          return false;
        }
      }
      return true;
    }
    /**
    * Funcion encargada de hacer el copiado del contenido de los archivos a la base de datos
    *
    * @param array $nombre_archivos Vector con los nombres de los archivos subidos al servidor
    * @param String $indice Cadena que contiene el indice de los archivos que se estan trabajando
    * @param String $documento Identificador del documento
    * @param String $empresa Identificador de la empresa
    * @param array $request Vector con los datos del $_REQUEST
    * @param date $dias_gracia Dias de gracia por defecto, para hecer el calculo de la fecha de vencimiento
    *
    * @return boolean
    */
    function SubirRegistrosRips($nombre_archivos,$indice,$documento,$empresa,$request,$dias_gracia) 
    {
      global  $ConfigAplication;
      $ruta_archivo =  $ConfigAplication['DIR_SIIS']."/rips/TMP_".UserGetUID()."_".$indice;
      $archivos = $nombre_archivos[UserGetUID()."_".$indice];
      
      $this->ConexionTransaccion();
      
      $sql = "SELECT NEXTVAL('rips_arch_control_rips_control_id_seq') ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
			
			$rips_id = array();
			if(!$rst->EOF)
      {
      	$rips_id = $rst->fields[0];				
      	$rst->MoveNext();
      }
      
      if(!$rst = $this->IngresarArchivoControl($ruta_archivo,$archivos['CT'],$rips_id,$indice,$dias_gracia,$empresa,$request))
        return false;
      
      $cod_sgss      = $rst['codigo_sgss'];   
      $fecha_venc    = $rst['fecha_vencimiento'];
      $radicacion_id = $request['radicacion_id'];
           
      $sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE "; 
			if(!$rst = $this->ConexionTransaccion($sql)) return false;

			$sql  = "SELECT prefijo,numeracion FROM documentos ";
			$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."' ";
					
			if(!$rst = $this->ConexionTransaccion($sql)) return false;
			
			$numero = array();
			if(!$rst->EOF)
      {
      	$numero = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
      $numero['documento'] = $documento;
      $numero['tipo_cuenta'] = $request['tipo_cuenta'];
            
      if(!$this->IngresarArchivoTransacciones($ruta_archivo,$archivos['AF'],$rips_id,$radicacion_id,$empresa,$numero,$request,$fecha_venc))
        return false;
      if(!$this->IngresarArchivoUsuarios($ruta_archivo,$archivos['US'],$rips_id,$cod_sgss))
        return false;
      if(!$this->IngresarArchivoDescripcionAgrupada($ruta_archivo,$archivos['AD'],$rips_id,$empresa))
        return false;
      
      if($archivos['AC'])
      {
        if(!$this->IngresarArchivoConsulta($ruta_archivo,$archivos['AC'],$rips_id,$empresa))
          return false;
      }
      if($archivos['AP'])
      {
        if(!$this->IngresarArchivoProcedimientos($ruta_archivo,$archivos['AP'],$rips_id,$empresa))
          return false;
      }
      if($archivos['AH'])
      {
        if(!$this->IngresarArchivoHospitalizacion($ruta_archivo,$archivos['AH'],$rips_id))
          return false;
      }
      if($archivos['AU'])
      {
        if(!$this->IngresarArchivoUrgencias($ruta_archivo,$archivos['AU'],$rips_id))
          return false;
      }
      if($archivos['AN'])
      {
        if(!$this->IngresarArchivoRecienNacidos($ruta_archivo,$archivos['AN'],$rips_id))
          return false;
      }
      if($archivos['AM'])
      {
        if(!$this->IngresarArchivoMedicamentos($ruta_archivo,$archivos['AM'],$rips_id,$empresa))
          return false;
      }
      if($archivos['AT'])
      {
        if(!$this->IngresarArchivoOtrosServicios($ruta_archivo,$archivos['AT'],$rips_id,$empresa))
          return false;
      }
      
      if(!$this->IngresarPacientesFacturas($empresa))
        return false;
      
      $this->Commit();
      
      global  $ConfigAplication;
      
      $path =  $ConfigAplication['DIR_SIIS']."/rips/TMP_".UserGetUID()."_".$indice;
      $this->BorrarArchivos($path);
      return true;
    }
    /**
    * Funcion que permite hacer un borrado de los archivos temporales
    *
    * @param string $path Ruta del archivo temporal
    *
    * @return boolean
    */
    function BorrarArchivos($path)
    {
      if(file_exists($path))
      {
        if ($handle = opendir($path)) 
        {
          while (false !== ($file = readdir($handle))) 
          {
            if ($file != '.' && $file != '..') 
            {
              unlink($path."/".$file);
            }
          }
          closedir($handle);
          rmdir($path);
          return true;
        }
      }
      return false;
    }
    /**
    * Funcion en la que se lee el archivo de control y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $rips_id Indice de la tabla
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    * @param date $dias_gracia Dias de gracia por defecto, para hecer el calculo de la fecha de vencimiento
    * @param date $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function IngresarArchivoControl($ruta,$archivo,$rips_id,$indice,$dias_gracia,$empresa,$request)
    {
      $sql = "";
      $proveedor = array();
      $lines = file($ruta."/".$archivo);
      
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        $f = explode("/",$datos[1]);
        
        if($sql == "")
        {
          $sql  = "INSERT INTO rips_arch_control ";
          $sql .= "( ";
          $sql .= "   rips_control_id , ";
          $sql .= "   codigo_envio , ";
          $sql .= "   codigo_sgss , ";
          $sql .= "   fecha_remision , ";
          $sql .= "   fecha_registro , ";
          $sql .= "   usuario_id ";
          $sql .= ") ";
          $sql .= "VALUES( ";
          $sql .= "   ".$rips_id.",";
          $sql .= "   ".$indice.",";
          $sql .= "   '".$datos[0]."',";
          $sql .= "   '".$f[2]."-".$f[1]."-".$f[0]."',";
          $sql .= "   NOW(),";
          $sql .= "   ".UserGetUID()." ";
          $sql .= "); ";
          
          if(!$rst = $this->ConexionTransaccion($sql)) return false;
          
          $sql  = "SELECT  TP.codigo_proveedor_id ";
          $sql .= "FROM    terceros_sgsss TS,";
          $sql .= "        terceros_proveedores TP ";
          $sql .= "WHERE   TS.codigo_sgsss = '".$datos[0]."' ";
          $sql .= "AND     TS.tercero_id = TP.tercero_id ";
          $sql .= "AND     TS.tipo_id_tercero = TP.tipo_id_tercero ";
          
          if(!$rst = $this->ConexionTransaccion($sql)) return false;
          
          if(!$rst->EOF)
          {
            $proveedor = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
          }
        }
        
        $sql  = "INSERT INTO rips_arch_control_archivos  "; 
        $sql .= "(  ";
        $sql .= "   rips_control_id,";
        $sql .= "   codigo_archivo , ";
        $sql .= "   total_registros  ";
        $sql .= ") ";
        $sql .= "VALUES( ";
        $sql .= "   ".$rips_id.",";
        $sql .= "   '".$datos[2]."',";
        $sql .= "    ".$datos[3]." ";
        $sql .= "); ";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
           
      $sql  = "UPDATE cxp_radicacion ";
      $sql .= "SET    rips_control_id = ".$rips_id." ";
      $sql .= "WHERE  cxp_radicacion_id = ".$request['radicacion_id']." ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $sql  = "SELECT dias_de_gracia ";
      $sql .= "FROM   cxp_proveedores_vencimiento ";
      $sql .= "WHERE  codigo_proveedor_id = ".$proveedor['codigo_proveedor_id']." ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $dias = array();
      if(!$rst->EOF)
      {
        $dias = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      if(empty($dias)) $dias['dias_de_gracia'] = $dias_gracia;
      
      $f = explode("/",$datos[1]);
 			$fecha_venc = date("Y-m-d", mktime(0, 0, 0,$f[1],intval($f[0]+$dias['dias_de_gracia']),$f[2]));
      
      return array("codigo_sgss"=>$datos[0],"fecha_vencimiento"=>$fecha_venc);
    }
    /**
    * Funcion en la que se lee el archivo de transacciones y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    * @param int $radicacion_id Identificador de la radicacion
    * @param String $empresa Identificador de la empresa
    * @param array $numero Vector con los datos del documento (numeracion, prefijo,documento)
    * @param array $request Vector con los datos del request
    * @param date $fecha_venc Fecha de vencimiento de la factura
    *
    * @return boolean
    */
    function IngresarArchivoTransacciones($ruta,$archivo,$indice,$radicacion_id,$empresa,$numero,$request,$fecha_venc)
    {
      $sql = "";
      $lines = file($ruta."/".$archivo);
      $fecha_inicial = $fecha_inicial = "";
      $i = 0;
      foreach ($lines as $line_num => $line)
      {
        $i++;
        $datos = explode(",",$line);
        $ff = explode("/",$datos[5]);
        $fi = explode("/",$datos[6]);
        $fn = explode("/",$datos[7]);
        
        if(!$datos[14]) $datos[14] = 0;
        if(!$datos[15]) $datos[15] = 0;
        if(!$datos[13]) $datos[13] = 0;
        
        $sql  = "INSERT INTO rips_arch_transacciones ";
        $sql .= "(";
        $sql .= "   rips_control_id ,";
        $sql .= "   codigo_sgss ,";
        $sql .= "   razon_social_sgss ,";
        $sql .= "   sgss_tipo_identificacion ,";
        $sql .= "   sgss_identificacion ,";
        $sql .= "   numero_factura ,";
        $sql .= "   fecha_factura ,";
        $sql .= "   fecha_inicio ,";
        $sql .= "   fecha_final ,";
        $sql .= "   codigo_sgss_administradora ,";
        $sql .= "   nombre_administradora ,";
        $sql .= "   numero_contrato ,";
        $sql .= "   plan_descripcion ,";
        $sql .= "   numero_poliza ,";
        $sql .= "   valor_copago ,";
        $sql .= "   valor_comision ,";
        $sql .= "   valor_descuento ,";
        $sql .= "   valor_neto ";
        $sql .= ") ";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        $sql .= "   '".$datos[4]."',";
        $sql .= "   '".$ff[2]."-".$ff[1]."-".$ff[0]."',";
        $sql .= "   '".$fi[2]."-".$fi[1]."-".$fi[0]."',";
        $sql .= "   '".$fn[2]."-".$fn[1]."-".$fn[0]."',";
        $sql .= "   '".$datos[8]."',";
        $sql .= "   '".$datos[9]."',";
        $sql .= "   '".$datos[10]."',";
        $sql .= "   '".$datos[11]."',";
        $sql .= "   '".$datos[12]."',";
        $sql .= "    ".$datos[13].",";
        $sql .= "    ".$datos[14].",";
        $sql .= "    ".$datos[15].",";
        $sql .= "    ".$datos[16]." ";
        $sql .= ");";
        
        $fecha_f = date("Y-m-d",strtotime($ff[1]."/".$ff[0]."/".$ff[2]));
        
        if($fecha_inicial == "")
        {
          $fecha_inicial = $fecha_f;
          $fecha_final = $fecha_f;
        }
        if($fecha_inicial > $fecha_f ) $fecha_inicial = $fecha_f;
        if($fecha_final < $fecha_f ) $fecha_final = $fecha_f;
          
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $sql  = "INSERT INTO cxp_facturas ";
        $sql .= "( ";
        $sql .= "    empresa_id , ";
        $sql .= "    prefijo , ";
        $sql .= "    numero , ";
        $sql .= "    documento_id, ";
        $sql .= "    cxp_radicacion_id , ";
        $sql .= "    tipo_id_tercero , ";
        $sql .= "    tercero_id , ";
        $sql .= "    prefijo_factura , ";
        $sql .= "    numero_factura , ";
        $sql .= "    numero_contrato , ";
        $sql .= "    fecha_documento, ";
        $sql .= "    fecha_vencimiento, ";
        $sql .= "    cxp_estado, ";
        $sql .= "    valor_total, ";
        $sql .= "    saldo, ";
        $sql .= "    tipo_cxp, ";
        $sql .= "    rips_control_id, ";
        $sql .= "    sw_rips, ";
        $sql .= "    usuario_registro ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "   '".$empresa."',";
        $sql .= "   '".$numero['prefijo']."',";
        $sql .= "    ".$numero['numeracion'].",";
        $sql .= "    ".$numero['documento'].",";
        $sql .= "    ".$radicacion_id.",";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        
        $prefijo = "";
        $numerod = $datos[4];
       
        if($request['numero_digitos'] > 0)
        {
          $prefijo = substr($datos[4],0,$request['numero_digitos']);
          $numerod = substr($datos[4],($request['numero_digitos']+1));
        }
        
        $sql .= "   '".$prefijo."',";
        $sql .= "   '".$numerod."',";
        $sql .= "   '".$datos[10]."',";
        $sql .= "   '".$this->DividirFecha($datos[5])."',";
        $sql .= "   '".$fecha_venc."'::date,";
        $sql .= "     'R',";
        $sql .= "    ".$datos[16].",";
        $sql .= "    ".$datos[16].",";
        $sql .= "    '".$numero['tipo_cuenta']."',";
        $sql .= "    ".$indice.",";
        $sql .= "    '1', ";
        $sql .= "    ".UserGetUID()." ";
        $sql .= ")";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        $this->usr_facturas[$datos[4]]['prefijo'] = $numero['prefijo'];
        $this->usr_facturas[$datos[4]]['numero'] = $numero['numeracion'];
        
        $numero['numeracion'] = $numero['numeracion']+1;
      }
      
      $sql  = "UPDATE cxp_radicacion ";
      $sql .= "SET    fecha_inicial = '".$fecha_inicial."'::date ,";
      $sql .= "       fecha_final = '".$fecha_final."'::date, ";
      $sql .= "       numero_cuentas =  ".$i." ";
      $sql .= "WHERE  cxp_radicacion_id = ".$radicacion_id."; ";
      
      $sql .= "UPDATE documentos ";
			$sql .= "SET 	numeracion = ".$numero['numeracion']." ";
			$sql .= "WHERE 	documento_id = ".$numero['documento']." AND empresa_id = '".$empresa."'; ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      return true;
    }
    /**
    * Funcion en la que se lee el archivo de usuarios y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    * @param String $cod_sgss Codigo del prestador del servicio de salud
    *
    * @return boolean
    */
    function IngresarArchivoUsuarios($ruta,$archivo,$indice,$cod_sgss)
    {
      $sql = "";
      $lines = file($ruta."/".$archivo);
      
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);

        $sql  = "INSERT INTO rips_arch_usuarios_ss ( ";
        $sql .= "  rips_control_id 	,";
        $sql .= "  codigo_sgss ,";
        $sql .= "  usuario_tipo_identificacion ,";
        $sql .= "  usuario_identificacion ,";
        $sql .= "  codigo_sgss_administradora ,";
        $sql .= "  tipo_usuario ,";
        $sql .= "  primer_apellido ,";
        $sql .= "  segundo_apellido ,";
        $sql .= "  primer_nombre ,";
        $sql .= "  segundo_nombre ,";
        $sql .= "  edad ,";
        $sql .= "  unidad_medida_edad ,";
        $sql .= "  tipo_sexo ,";
        $sql .= "  tipo_dpto_id ,";
        $sql .= "  tipo_mpio_id ,";
        $sql .= "  zona_residencia ";
        $sql .= ") ";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$cod_sgss."',";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        $sql .= "   '".$datos[4]."',";
        $sql .= "   '".$datos[5]."',";
        $sql .= "   '".$datos[6]."',";
        $sql .= "   '".$datos[7]."',";
        $sql .= "    ".$datos[8].",";
        $sql .= "   '".$datos[9]."',";
        $sql .= "   '".$datos[10]."',";
        $sql .= "   '".$datos[11]."',";
        $sql .= "   '".$datos[12]."',";
        $sql .= "   '".trim($datos[13])."' ";
        $sql .= ");";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      return true;
    }
    /**
    * Funcion en la que se lee el archivo de descripcion agrupada y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    * @param String $empresa Identificador de la empresa
    *
    * @return boolean
    */
    function IngresarArchivoDescripcionAgrupada($ruta,$archivo,$indice,$empresa)
    {
      $sql = "";
      $lines = file($ruta."/".$archivo);
      
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        
        if(!$datos[4]) $datos[4] = $datos[5] / $datos[3];
        
        $sql  = "INSERT INTO rips_arch_descripciones_agrupadas  ";
        $sql .= "( ";
        $sql .= "   rips_control_id 	, ";
        $sql .= "   numero_factura , ";
        $sql .= "   codigo_sgss , ";
        $sql .= "   codigo_concepto , ";
        $sql .= "   cantidad , ";
        $sql .= "   valor_unitario , ";
        $sql .= "   valor_total_concepto ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "    ".$datos[3].",";
        $sql .= "    ".$datos[4].",";
        $sql .= "    ".$datos[5]." ";
        $sql .= ");";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      return true;
    }
    /**
    * Funcion en la que se lee el archivo de consulta y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    * @param String $empresa Identificador de la empresa
    *
    * @return boolean
    */
    function IngresarArchivoConsulta($ruta,$archivo,$indice,$empresa)
    {
      $sql = "";
      $auto = "";
      $lines = file($ruta."/".$archivo);
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        $fc = explode("/",$datos[4]);
        ($datos[5])? $auto = $datos[5]: $auto = "NULL"; 
        
        $sql  = "INSERT INTO rips_arch_consultas  ";
        $sql .= "( ";
        $sql .= "    rips_control_id 	,";
        $sql .= "    numero_factura ,";
        $sql .= "    codigo_sgss ,";
        $sql .= "    usuario_tipo_identificacion ,";
        $sql .= "    usuario_identificacion ,";
        $sql .= "    fecha_consulta ,";
        $sql .= "    numero_autorizacion ,";
        $sql .= "    codigo_consulta ,";
        $sql .= "    finalidad_consulta ,";
        $sql .= "    causa_externa ,";
        $sql .= "    codigo_diagnostico_principal ,";
        $sql .= "    codigo_diagnostico_relacionado_1 ,";
        $sql .= "    codigo_diagnostico_relacionado_2 ,";
        $sql .= "    codigo_diagnostico_relacionado_3 ,";
        $sql .= "    tipo_disgnostico_principal ,";
        $sql .= "    valor_consulta ,";
        $sql .= "    valor_cuota_moderadora ,";
        $sql .= "    valor_neto ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        $sql .= "   '".$fc[2]."-".$fc[1]."-".$fc[0]."',";
        $sql .= "    ".$auto.", ";
        $sql .= "   '".$datos[6]."',";
        $sql .= "   '".$datos[7]."',";
        $sql .= "   '".$datos[8]."',";
        $sql .= "   '".$datos[9]."',";
        $sql .= "   '".$datos[10]."',";
        $sql .= "   '".$datos[11]."',";
        $sql .= "   '".$datos[12]."',";
        $sql .= "   '".$datos[13]."',";
        $sql .= "    ".$datos[14].",";
        $sql .= "    ".$datos[15].",";
        $sql .= "    ".$datos[16]." ";
        $sql .= ");";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
        $sql = "INSERT INTO cxp_detalle_facturas ";
        $sql .= "( ";
        $sql .= "    cxp_detalle_factura_id ,";
        $sql .= "    empresa_id ,";
        $sql .= "    prefijo ,";
        $sql .= "    numero ,";
        $sql .= "    cx_tipo_cargo_id ,";
        $sql .= "    referencia ,";
        $sql .= "    descripcion ,";
        $sql .= "    valor_unitario ,";
        $sql .= "    cantidad ,";
        $sql .= "    valor_total ,";
        $sql .= "    autorizacion ";
        $sql .= "    )";
        $sql .= "VALUES (";
        $sql .= "    DEFAULT,";
        $sql .= "   '".$empresa."',";
        $sql .= "   '".$this->usr_facturas[$datos[0]]['prefijo']."',";
        $sql .= "    ".$this->usr_facturas[$datos[0]]['numero'].",";
        $sql .= "    'OT',";
        $sql .= "   '".$datos[6]."',";
        $sql .= "   '".$datos[7]."',";
        $sql .= "    ".$datos[14].",";
        $sql .= "    1,";
        $sql .= "    ".$datos[16].", ";
        $sql .= "    ".$auto." ";
        $sql .= "    )";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $this->usr_facturas[$datos[0]]['usuarios'][$datos[2]][$datos[3]] = 1;
      }
      return true;
    }
    /**
    * Funcion en la que se lee el archivo de procedimientos y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    * @param String $empresa Identificador de la empresa
    *
    * @return boolean
    */
    function IngresarArchivoProcedimientos($ruta,$archivo,$indice,$empresa)
    {
      $sql = "";
      $auto = "";
      $lines = file($ruta."/".$archivo);
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        $fp = explode("/",$datos[4]);
        ($datos[5])? $auto = $datos[5]: $auto = "NULL"; 
        
        $sql  = "INSERT INTO rips_arch_procedimientos  ";
        $sql .= "( ";
        $sql .= "    rips_control_id 	 ,  ";
        $sql .= "    numero_factura ,  ";
        $sql .= "    codigo_sgss ,  ";
        $sql .= "    usuario_tipo_identificacion ,  ";
        $sql .= "    usuario_identificacion ,  ";
        $sql .= "    fecha_procedimiento ,  ";
        $sql .= "    numero_autorizacion ,  ";
        $sql .= "    codigo_procedimiento ,  ";
        $sql .= "    ambito_procedimiento ,  ";
        $sql .= "    finalidad_procedimiento ,  ";
        $sql .= "    profesional_atiende ,  ";
        $sql .= "    codigo_diagnostico_principal ,  ";
        $sql .= "    codigo_diagnostico_relacionado ,  ";
        $sql .= "    codigo_complicacion ,  ";
        $sql .= "    forma_acto_qx ,  ";
        $sql .= "    valor_procedimiento ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        $sql .= "   '".$fp[2]."-".$fp[1]."-".$fp[0]."',";
        $sql .= "    ".$auto.", ";
        $sql .= "   '".$datos[6]."',";
        $sql .= "   '".$datos[7]."',";
        $sql .= "   '".$datos[8]."',";
        $sql .= "   '".$datos[9]."',";
        $sql .= "   '".$datos[10]."',";
        $sql .= "   '".$datos[11]."',";
        $sql .= "   '".$datos[12]."',";
        $sql .= "   '".$datos[13]."',";
        $sql .= "    ".$datos[14]." ";
        $sql .= ");";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $sql = "INSERT INTO cxp_detalle_facturas ";
        $sql .= "( ";
        $sql .= "    cxp_detalle_factura_id ,";
        $sql .= "    empresa_id ,";
        $sql .= "    prefijo ,";
        $sql .= "    numero ,";
        $sql .= "    cx_tipo_cargo_id ,";
        $sql .= "    referencia ,";
        $sql .= "    descripcion ,";
        $sql .= "    valor_unitario ,";
        $sql .= "    cantidad ,";
        $sql .= "    valor_total ,";
        $sql .= "    autorizacion ";
        $sql .= "    )";
        $sql .= "VALUES (";
        $sql .= "    DEFAULT,";
        $sql .= "   '".$empresa."',";
        $sql .= "   '".$this->usr_facturas[$datos[0]]['prefijo']."',";
        $sql .= "    ".$this->usr_facturas[$datos[0]]['numero'].",";
        $sql .= "    'CC',";
        $sql .= "   '".$datos[6]."',";
        $sql .= "   '".$datos[7]."',";
        $sql .= "    ".$datos[14].",";
        $sql .= "    1,";
        $sql .= "    ".$datos[14].", ";
        $sql .= "    ".$auto." ";
        $sql .= "    )";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $this->usr_facturas[$datos[0]]['usuarios'][$datos[2]][$datos[3]] = 1;
      }
      return true;
    }
    /**
    * Funcion en la que se lee el archivo de hospitalizacion y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    *
    * @return boolean
    */
    function IngresarArchivoHospitalizacion($ruta,$archivo,$indice)
    {
      $sql = "";
      $auto = "";
      $lines = file($ruta."/".$archivo);
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        $fecha_ingreso = "NULL";
        $fecha_egreso = "NULL";
        if($datos[5])
        {
          $f = explode("/",$datos[5]);
          $fecha_ingreso = "'".$f[2]."-".$f[1]."-".$f[0]."'";
        }
        if($datos[17])
        {
          $f = explode("/",$datos[17]);
          $fecha_egreso = "'".$f[2]."-".$f[1]."-".$f[0]."'";
        }
        ($datos[7])? $auto = $datos[7]: $auto = "NULL"; 
        $sql  = "INSERT INTO rips_arch_hospitalizaciones  ";
        $sql .= "( ";
        $sql .= "    rips_control_id 	,  ";
        $sql .= "    numero_factura ,  ";
        $sql .= "    codigo_sgss ,  ";
        $sql .= "    usuario_tipo_identificacion ,  ";
        $sql .= "    usuario_identificacion ,  ";
        $sql .= "    via_ingreso ,";
        $sql .= "    fecha_ingreso , ";
        $sql .= "    hora_ingreso , ";
        $sql .= "    numero_autorizacion ,";
        $sql .= "    causa_externa ,";
        $sql .= "    codigo_diagnostico_ingreso ,";
        $sql .= "    codigo_diagnostico_egreso ,";
        $sql .= "    codigo_diagnostico_egreso_relacionado_1 ,";
        $sql .= "    codigo_diagnostico_egreso_relacionado_2 ,";
        $sql .= "    codigo_diagnostico_egreso_relacionado_3 ,";
        $sql .= "    codigo_diagnostico_complicacion ,";
        $sql .= "    estado_salida ,";
        $sql .= "    codigo_causa_muerte ,";
        $sql .= "    fecha_egreso ,";
        $sql .= "    hora_egreso ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        $sql .= "   '".$datos[4]."',";
        $sql .= "    ".$fecha_ingreso.",";
        $sql .= "   '".$datos[6]."',";
        $sql .= "    ".$auto.", ";
        $sql .= "   '".$datos[8]."',";
        $sql .= "   '".$datos[9]."',";
        $sql .= "   '".$datos[10]."',";
        $sql .= "   '".$datos[11]."',";
        $sql .= "   '".$datos[12]."',";
        $sql .= "   '".$datos[13]."',";
        $sql .= "   '".$datos[14]."',";
        $sql .= "   '".$datos[15]."',";
        $sql .= "   '".$datos[16]."',";
        $sql .= "    ".$fecha_egreso.",";
        $sql .= "   '".trim($datos[18])."' ";
        $sql .= ");";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      return true;
    }
    /**
    * Funcion en la que se lee el archivo de urgencias y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    *
    * @return boolean
    */
    function IngresarArchivoUrgencias($ruta,$archivo,$indice)
    {
      $sql = "";
      $auto = "";
      $lines = file($ruta."/".$archivo);
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        $fecha_ingreso = "NULL";
        $fecha_salida_observacion = "NULL";
        if($datos[4])
        {
          $f = explode("/",$datos[4]);
          $fecha_ingreso = "'".$f[2]."-".$f[1]."-".$f[0]."'";
        }
        if($datos[15])
        {
          $f = explode("/",$datos[15]);
          $fecha_salida_observacion = "'".$f[2]."-".$f[1]."-".$f[0]."'";
        }
        ($datos[6])? $auto = $datos[6]: $auto = "NULL"; 

        $sql  = "INSERT INTO rips_arch_urgencias  ";
        $sql .= "( ";
        $sql .= "    rips_control_id 	,  ";
        $sql .= "    numero_factura ,  ";
        $sql .= "    codigo_sgss ,  ";
        $sql .= "    usuario_tipo_identificacion ,  ";
        $sql .= "    usuario_identificacion ,  ";
        $sql .= "    fecha_ingreso , ";
        $sql .= "    hora_ingreso , ";
        $sql .= "    numero_autorizacion ,";
        $sql .= "    causa_externa ,";
        $sql .= "    codigo_diagnostico_salida ,";
        $sql .= "    codigo_diagnostico_salida_relacionado_1 ,";
        $sql .= "    codigo_diagnostico_salida_relacionado_2 ,";
        $sql .= "    codigo_diagnostico_salida_relacionado_3 ,";
        $sql .= "    codigo_destino_salida ,";
        $sql .= "    estado_salida ,";
        $sql .= "    codigo_causa_muerte ,";
        $sql .= "    fecha_salida_observacion ,";
        $sql .= "    hora_salida_observacion ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        $sql .= "    ".$fecha_ingreso.",";
        $sql .= "   '".$datos[5]."',";
        $sql .= "    ".$auto.", ";
        $sql .= "   '".$datos[7]."',";
        $sql .= "   '".$datos[8]."',";
        $sql .= "   '".$datos[9]."',";
        $sql .= "   '".$datos[10]."',";
        $sql .= "   '".$datos[11]."',";
        $sql .= "   '".$datos[12]."',";
        $sql .= "   '".$datos[13]."',";
        $sql .= "   '".$datos[14]."',";
        $sql .= "    ".$fecha_salida_observacion.",";
        $sql .= "   '".trim($datos[16])."' ";
        $sql .= ");";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      return true;
    }
    /**
    * Funcion en la que se lee el archivo de recien nacidos y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    *
    * @return boolean
    */
    function IngresarArchivoRecienNacidos($ruta,$archivo,$indice)
    {
      $sql = "";
      $lines = file($ruta."/".$archivo);
      
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        $fecha_muerte = "NULL";

        if($datos[12])
        {
          $f = explode("/",$datos[12]);
          $fecha_muerte = "'".$f[2]."-".$f[1]."-".$f[0]."'";
        }
        
        $fn = explode("/",$datos[4]);
        
        $sql  = "INSERT INTO rips_arch_recien_nacidos  ";
        $sql .= "( ";
        $sql .= "    rips_control_id 	 ,  ";
        $sql .= "    numero_factura ,  ";
        $sql .= "    codigo_sgss ,  ";
        $sql .= "    usuario_tipo_identificacion ,  ";
        $sql .= "    usuario_identificacion ,  ";
        $sql .= "    fecha_nacimiento , ";
        $sql .= "    hora_nacimiento , ";
        $sql .= "    edad_gestacional , ";
        $sql .= "    control_prenatal , ";
        $sql .= "    tipo_sexo , ";
        $sql .= "    peso , ";
        $sql .= "    codigo_diagnostico , ";
        $sql .= "    codigo_causa_muerte , ";
        $sql .= "    fecha_muerte , ";
        $sql .= "    hora_muerte ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        $sql .= "   '".$fn[2]."-".$fn[1]."-".$fn[0]."',";
        $sql .= "   '".$datos[5]."',";
        $sql .= "    ".$datos[6].",";
        $sql .= "   '".$datos[7]."',";
        $sql .= "   '".$datos[8]."',";
        $sql .= "    ".$datos[9].",";
        $sql .= "   '".$datos[10]."',";
        $sql .= "   '".$datos[11]."',";
        $sql .= "    ".$fecha_muerte.",";
        $sql .= "   '".trim($datos[13])."' ";
        $sql .= ");";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      return true;
    }
    /**
    * Funcion en la que se lee el archivo de medicamentos y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    * @param String $empresa Identificador de la empresa
    *
    * @return boolean
    */
    function IngresarArchivoMedicamentos($ruta,$archivo,$indice,$empresa)
    {
      $sql = "";
      $auto = "";
      $lines = file($ruta."/".$archivo);
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        ($datos[4])? $auto = $datos[4]: $auto = "NULL"; 

        $sql  = "INSERT INTO rips_arch_medicamentos  ";
        $sql .= "( ";
        $sql .= "    rips_control_id 	,  ";
        $sql .= "    numero_factura ,  ";
        $sql .= "    codigo_sgss ,  ";
        $sql .= "    usuario_tipo_identificacion ,  ";
        $sql .= "    usuario_identificacion ,  ";
        $sql .= "    numero_autorizacion , ";
        $sql .= "    codigo_medicamento , ";
        $sql .= "    tipo_medicamento , ";
        $sql .= "    nombre_generico_medicamento , ";
        $sql .= "    forma_farmaceutica , ";
        $sql .= "    concentracion_medicamento , ";
        $sql .= "    unidad_medida , ";
        $sql .= "    numero_unidades , ";
        $sql .= "    valor_unitario , ";
        $sql .= "    valor_total ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        $sql .= "    ".$auto.",";
        $sql .= "   '".$datos[5]."',";
        $sql .= "   '".$datos[6]."',";
        $sql .= "   '".$datos[7]."',";
        $sql .= "   '".$datos[8]."',";
        $sql .= "   '".$datos[9]."',";
        $sql .= "   '".$datos[10]."',";
        $sql .= "    ".$datos[11].",";
        $sql .= "    ".$datos[12].",";
        $sql .= "    ".$datos[13]." ";
        $sql .= ");";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $sql  = "INSERT INTO cxp_detalle_facturas ";
        $sql .= "( ";
        $sql .= "    cxp_detalle_factura_id ,";
        $sql .= "    empresa_id ,";
        $sql .= "    prefijo ,";
        $sql .= "    numero ,";
        $sql .= "    cx_tipo_cargo_id ,";
        $sql .= "    referencia ,";
        $sql .= "    descripcion ,";
        $sql .= "    valor_unitario ,";
        $sql .= "    cantidad ,";
        $sql .= "    valor_total ,";
        $sql .= "    autorizacion ";
        $sql .= "    )";
        $sql .= "VALUES (";
        $sql .= "    DEFAULT,";
        $sql .= "   '".$empresa."',";
        $sql .= "   '".$this->usr_facturas[$datos[0]]['prefijo']."',";
        $sql .= "    ".$this->usr_facturas[$datos[0]]['numero'].",";
        $sql .= "    'IM',";
        $sql .= "   '".$datos[5]."',";
        $sql .= "   '".$datos[7]."',";
        $sql .= "    ".$datos[12].",";
        $sql .= "    ".$datos[11].",";
        $sql .= "    ".$datos[13].", ";
        $sql .= "    ".$auto." ";
        $sql .= "    )";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $this->usr_facturas[$datos[0]]['usuarios'][$datos[2]][$datos[3]] = 1;
      }
      return true;
    }
    /**
    * Funcion en la que se lee el archivo de servicios y se copia su contenido a la tablas
    * en la base de datos
    *
    * @param String $ruta Carpeta donde se encuentra el archivo para su lectura
    * @param String $archivo Nombre del archivo que se esta subiendo
    * @param String $indice Indice del archivo, que identifica al envio que se esta trabajando
    * @param String $empresa Identificador de la empresa
    *
    * @return boolean
    */
    function IngresarArchivoOtrosServicios($ruta,$archivo,$indice,$empresa)
    {
      $sql = "";
      $auto = "";
      $lines = file($ruta."/".$archivo);
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        ($datos[4])? $auto = $datos[4]: $auto = "NULL"; 
        $sql  = "INSERT INTO rips_arch_otros_servicios  ";
        $sql .= "( ";
        $sql .= "    rips_control_id 	 ,  ";
        $sql .= "    numero_factura ,  ";
        $sql .= "    codigo_sgss ,  ";
        $sql .= "    usuario_tipo_identificacion ,  ";
        $sql .= "    usuario_identificacion ,  ";
        $sql .= "    numero_autorizacion , ";
        $sql .= "    tipo_servicio ,";
        $sql .= "    codigo_servicio ,";
        $sql .= "    nombre_servicio ,";
        $sql .= "    cantidad ,";
        $sql .= "    valor_unitario ,";
        $sql .= "    valor_total ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "    ".$indice.",";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".$datos[3]."',";
        $sql .= "    ".$auto.",";
        $sql .= "   '".$datos[5]."',";
        $sql .= "   '".$datos[6]."',";
        $sql .= "   '".$datos[7]."',";
        $sql .= "    ".$datos[8].",";
        $sql .= "    ".$datos[9].",";
        $sql .= "    ".$datos[10]." ";
        $sql .= ");";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $sql = "INSERT INTO cxp_detalle_facturas ";
        $sql .= "( ";
        $sql .= "    cxp_detalle_factura_id ,";
        $sql .= "    empresa_id ,";
        $sql .= "    prefijo ,";
        $sql .= "    numero ,";
        $sql .= "    cx_tipo_cargo_id ,";
        $sql .= "    referencia ,";
        $sql .= "    descripcion ,";
        $sql .= "    valor_unitario ,";
        $sql .= "    cantidad ,";
        $sql .= "    valor_total ,";
        $sql .= "    autorizacion ";
        $sql .= "    )";
        $sql .= "VALUES (";
        $sql .= "    DEFAULT,";
        $sql .= "   '".$empresa."',";
        $sql .= "   '".$this->usr_facturas[$datos[0]]['prefijo']."',";
        $sql .= "    ".$this->usr_facturas[$datos[0]]['numero'].",";
        $sql .= "    'OT',";
        $sql .= "   '".$datos[6]."',";
        $sql .= "   '".$datos[7]."',";
        $sql .= "    ".$datos[9].",";
        $sql .= "    ".$datos[8].",";
        $sql .= "    ".$datos[10].", ";
        $sql .= "    ".$auto." ";
        $sql .= "    )";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $this->usr_facturas[$datos[0]]['usuarios'][$datos[2]][$datos[3]] = 1;
      }
      
      return true;
    }
    /**
    * Funcion sonde se hace el ingreso de los pacientes relacionados en las facturas
    * @param String $empresa Identificador de la empresa
    *
    * @return boolean
    */
    function IngresarPacientesFacturas($empresa)
    {      
      foreach($this->usr_facturas as $key => $factura)
      {
        foreach($factura['usuarios'] as $keyI => $usr)
        {
          foreach($usr as $keyII => $identificacion)
          {
            $sql  = "INSERT INTO cxp_pacientes_facturas ";
            $sql .= "( ";
            $sql .= "   empresa_id, ";
            $sql .= "   prefijo, ";
            $sql .= "   numero, ";
            $sql .= "   tipo_id_paciente, ";
            $sql .= "   paciente_id ";
            $sql .= ")";
            $sql .= "VALUES (";
            $sql .= "   '".$empresa."', ";
            $sql .= "   '".$factura['prefijo']."',";
            $sql .= "    ".$factura['numero'].",";
            $sql .= "   '".$keyI."',";
            $sql .= "   '".$keyII."' ";
            $sql .= ")";
            
            if(!$rst = $this->ConexionTransaccion($sql)) return false;
          }
        }
      }
      return true;
    }
    /**
    *
    */
    function ObtenerRadicacion($radicacion)
    {
      $sql  = "SELECT TS.codigo_sgsss ";
      $sql .= "FROM   cxp_radicacion  CR,";
      $sql .= "       terceros_sgsss TS, ";
      $sql .= "       terceros_proveedores TP ";
      $sql .= "WHERE  CR.cxp_radicacion_id = ".$radicacion." ";
      $sql .= "AND    TP.codigo_proveedor_id = CR.proveedor_id ";
      $sql .= "AND    TS.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND    TS.tercero_id = TP.tercero_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      return $datos;
    } 
  }
?>