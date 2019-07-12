<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: PlanosSQL.class.php,v 1.1 2010/12/17 19:20:05 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */
  /**
  * Clase: PlanosSQL
  * Clase para consulta de afiliados al sistema EPS
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */
  class PlanosSQL extends ConexionBD
  {
    /**
    * @var array $bodegas_documentos
    *
    * Variable donde se almacena el documento_id de bodega
    * para cada bodega del sistema
    */
    var $bodegas_documentos = array();
    /**
    * Constructor de la clase
    */
    function PlanosSQL(){}
    /**
    * Metodo donde se obtienen las empresas en la que un determinado
    * usuario tiene permiso de operar el modulo
    *
    * @param integer $usuario Identificador del usuario
    *
    * @return boolean
    */
    function ObtenerPermisos($usuario)
    {
      $sql  = "SELECT EM.empresa_id, ";
      $sql .= "       EM.razon_social ";
      $sql .= "FROM   userpermisos_subir_planos AC, ";
      $sql .= "       empresas EM ";
      $sql .= "WHERE  EM.empresa_id = AC.empresa_id ";
      $sql .= "AND    AC.usuario_id = ".$usuario." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      return $datos;
    }    
    /**
    * Metodo donde se verifica si un archivo plano ya ha sido subido al
    * sistema
    *
    * @param string $descripcion Nombre del archivo a subir
    *
    * @return boolean
    */
    function ValidarArchivoPlano($descripcion)
    {
      $sql  = "SELECT TO_CHAR(AC.fecha,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= "       SU.nombre ";
      $sql .= "FROM   archivos_cargados AC, ";
      $sql .= "       system_usuarios SU ";
      $sql .= "WHERE  AC.descripcion ILIKE '".$descripcion."' ";
      $sql .= "AND    AC.usuario_id = SU.usuario_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      return $datos;
    }
    /**
    * Metodo donde se hace el ingreso de los pacientes
    *
    * @param array $datos Arreglo de datos con la informacion del request
    *
    * @return boolean
    */
    function SubirArchivoPlanoAfiliados($datos) 
    {
      if($_FILES['archivo_capitado']['error'] != 0)
      {
        switch ($_FILES['archivo_capitado']['error']) 
        {
          case UPLOAD_ERR_INI_SIZE:
            $this->mensajeDeError = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO";
          break;
          case UPLOAD_ERR_FORM_SIZE:
            $this->mensajeDeError = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO EN LA FORMA";
          break;
          case UPLOAD_ERR_PARTIAL:
            $this->mensajeDeError = "EL ARCHIVO SOLO FUE SUBIDO PARCIALMENTE";
          break;
          case UPLOAD_ERR_NO_FILE:
            $this->mensajeDeError = "EL ARCHIVO NO FUE SUBIDO";
          break;
          case UPLOAD_ERR_NO_TMP_DIR:
            $this->mensajeDeError = "NO HAY DIRECTORIO TEMPORAL PARA SUBIR EL ARCHIVO";
          break;
          case UPLOAD_ERR_CANT_WRITE:
            $this->mensajeDeError = "HA OCURRIDO UN ERROR AL MOMENTO DE COPIAR EL ARCHIVO A DISCO";
          break;
          case UPLOAD_ERR_EXTENSION:
            $this->mensajeDeError = "HA OCURRIDO UN ERROR CON LA EXTENSION DEL ARCHIVO";
          break;
          default:
            $this->mensajeDeError = "HA OCURRIDO UN ERROR DESCONOCIDO MIENTRAS SE REALIZABA EL PROCESO";
          break;
        }
        
        return false;
      }
      
      if (is_uploaded_file($_FILES['archivo_capitado']['tmp_name']))
      {        
        $errores = array();
        $dir_siis = GetVarConfigAplication('DIR_SIIS');
        $nombre_archivo = $_FILES ['archivo_capitado']['name'];
        
        $this->BorrarArchivos( $dir_siis."tmp/".$nombre_archivo);
        move_uploaded_file ( $_FILES['archivo_capitado']['tmp_name'], $dir_siis."tmp/".$nombre_archivo ); 
        
        $arch = parse_ini_file($dir_siis."app_modules/".$datos['modulo']."/config/afiliados_externos.ini",true);
        
        $this->ConexionTransaccion();
        $sql  = "INSERT INTO archivos_cargados";
        $sql .= "   (";
        $sql .= "     archivo_cargado_id,";
        $sql .= "     usuario_id,";
        $sql .= "     fecha,";
        $sql .= "     descripcion ";
        $sql .= "   )";
        $sql .= "VALUES";
        $sql .= "   (";
        $sql .= "     DEFAULT, ";
        $sql .= "     ".$datos['usuario_id'].", ";
        $sql .= "     NOW(), ";
        $sql .= "     '".$nombre_archivo."' ";
        $sql .= "   )";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
        
        $sql  = "DELETE FROM interfaces_planes.afiliados_externos ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
        
        $lines = fopen($dir_siis."tmp/".$nombre_archivo,"r");
        $flag = ($datos['encabezado'] == '1')? true:false;
        
        $enc = array();
        if(!$flag)
        {
          foreach($arch['campos'] as $k => $dtl)
            $enc[$dtl] = $k;
          
          $flag = false;
        }
        
        $afiliados = array();
        $tipo_id_afiliado = array();
        $generos = array();
        $tipos_afiliados = array();
        if($datos['separador'] == "t") $datos['separador'] = "\t";
        
        while (($tmp = fgetcsv($lines, 4096, $datos['separador'])) !== FALSE) 
        {
          if(sizeof($tmp) > 1)
          {
            if($flag)
            {
              foreach($tmp as $k => $dtl)
                $enc[$arch['campos'][$dtl]] = $k;
              
              $flag = false;
            }
            else
            {
              $contin = true;
              if($datos['encabezado'] == '1' && $arch['campos'][$tmp[$enc['afiliado_tipo_id']]] == "afiliado_tipo_id")
                $contin = false;
             
              if($contin)
              { 
                if(empty($afiliados[$tmp[$enc['afiliado_tipo_id']]][$tmp[$enc['afiliado_id']]]))
                {
                  $afiliados[$tmp[$enc['afiliado_tipo_id']]][$tmp[$enc['afiliado_id']]] = '1';
                  
                  $primer_nombre = str_replace("'","\'",$tmp[$enc['primer_nombre']]);
                  $segundo_nombre = str_replace("'","\'",$tmp[$enc['segundo_nombre']]);
                  if(!$enc['segundo_nombre'])
                  {
                    $aux = explode(" ",$tmp[$enc['primer_nombre']]);
                    $primer_nombre = $aux[0];
                    
                    for($i=1;$i<sizeof($aux);$i++)
                      $segundo_nombre .= ($i == 1)? $aux[$i]:" ".$aux[$i];
                  }
                  
                  if(!$tipo_id_afiliado[$tmp[$enc['afiliado_tipo_id']]])
                    $tipo_id_afiliado[$tmp[$enc['afiliado_tipo_id']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['afiliado_tipo_id']]),$arch['equival']['afiliado_tipo_id'],$datos['plan_id']);
                  
                  if(!$generos[$tmp[$enc['sexo_id']]])
                    $generos[$tmp[$enc['sexo_id']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['sexo_id']]),$arch['equival']['sexo_id'],$datos['plan_id']);
                  
                  if(!$tipos_afiliados[$tmp[$enc['tipo_afiliado_id']]])
                    $tipos_afiliados[$tmp[$enc['tipo_afiliado_id']]] = $this->EvaluarEquivalencia($tmp[$enc['tipo_afiliado_id']],$arch['equival']['tipo_afiliado_id'],$datos['plan_id']);
                  
                  $sql  = "INSERT INTO interfaces_planes.afiliados_externos ";
                  $sql .= " ( ";
                  $sql .= "   afiliado_tipo_id, ";
                  $sql .= "   afiliado_id, ";
                  $sql .= "   primer_apellido, ";
                  $sql .= "   segundo_apellido, ";
                  $sql .= "   primer_nombre, ";
                  $sql .= "   segundo_nombre, ";
                  $sql .= "   fecha_nacimiento, ";
                  $sql .= "   sexo_id, ";
                  $sql .= "   tipo_pais_id, ";
                  $sql .= "   tipo_dpto_id, ";
                  $sql .= "   tipo_mpio_id, ";
                  $sql .= "   zona_residencia, ";
                  $sql .= "   direccion_residencia, ";
                  $sql .= "   telefono_residencia, ";
                  $sql .= "   telefono_movil, ";
                  $sql .= "   tipo_estrato_id, ";
                  $sql .= "   tipo_estado_civil_id, ";
                  $sql .= "   tipo_afiliado_id ";
                  $sql .= " )"; 
                  $sql .= "VALUES"; 
                  $sql .= " (";
                  $sql .= "    ".$tipo_id_afiliado[$tmp[$enc['afiliado_tipo_id']]].", ";
                  $sql .= "   '".str_replace("'","",$tmp[$enc['afiliado_id']])."', ";
                  $sql .= "   '".trim(str_replace("'","\'",$tmp[$enc['primer_apellido']]))."', ";
                  $sql .= "   '".trim(str_replace("'","\'",$tmp[$enc['segundo_apellido']]))."', ";
                  $sql .= "   '".trim($primer_nombre)."', ";
                  $sql .= "   '".trim($segundo_nombre)."', ";
                  $sql .= "    ".$this->EvaluarFormato($tmp[$enc['fecha_nacimiento']],$arch['formatos']['fecha_nacimiento']).",";
                  $sql .= "    ".$generos[$tmp[$enc['sexo_id']]].", ";
                  $sql .= "    ".$this->EvaluarEquivalencia($tmp[$enc['tipo_pais_id']],$arch['equival']['tipo_pais_id'],$datos['plan_id']).", ";
                  $sql .= "    ".$this->EvaluarEquivalencia($tmp[$enc['tipo_dpto_id']],$arch['equival']['tipo_dpto_id'],$datos['plan_id']).", ";
                  $sql .= "    ".$this->EvaluarEquivalencia($tmp[$enc['tipo_mpio_id']],$arch['equival']['tipo_mpio_id'],$datos['plan_id']).", ";
                  $sql .= "    ".$this->EvaluarEquivalencia($tmp[$enc['zona_residencia']],$arch['equival']['zona_residencia'],$datos['plan_id']).", ";
                  $sql .= "   '".str_replace("'","",$tmp[$enc['direccion_residencia']])."', ";
                  $sql .= "   '".$tmp[$enc['telefono_residencia']]."', ";
                  $sql .= "   '".$tmp[$enc['telefono_movil']]."', ";
                  $sql .= "    ".$this->EvaluarEquivalencia($tmp[$enc['tipo_estrato_id']],$arch['equival']['tipo_estrato_id'],$datos['plan_id']).", ";
                  $sql .= "    ".$this->EvaluarEquivalencia($tmp[$enc['tipo_estado_civil_id']],$arch['equival']['tipo_estado_civil_id'],$datos['plan_id']).", ";
                  $sql .= "    ".$tipos_afiliados[$tmp[$enc['tipo_afiliado_id']]]." ";
                  $sql .= " )";
                  
                  if(!$rst = $this->ConexionTransaccion($sql))
                    return false;
                }
              }
            }
          }
        }
        
        $this->Commit();
        fclose($lines);
        
        $sql  = "INSERT INTO pacientes ";
        $sql .= "   (";
        $sql .= "     tipo_id_paciente,";
        $sql .= "     paciente_id,";
        $sql .= "     primer_apellido,";
        $sql .= "     segundo_apellido,";
        $sql .= "     primer_nombre,";
        $sql .= "     segundo_nombre,";
        $sql .= "     fecha_nacimiento,";
        $sql .= "     fecha_registro,";
        $sql .= "     sexo_id,";
        $sql .= "     usuario_id ";
        $sql .= "   ) ";
        $sql .= "SELECT IF.afiliado_tipo_id, ";
        $sql .= "       IF.afiliado_id, ";
        $sql .= "       IF.primer_apellido, ";
        $sql .= "       IF.segundo_apellido, ";
        $sql .= "       IF.primer_nombre, ";
        $sql .= "       IF.segundo_nombre, ";
        $sql .= "       IF.fecha_nacimiento, ";
        $sql .= "       NOW() AS fecha_registro, ";
        $sql .= "       IF.sexo_id, ";
        $sql .= "       ".$datos['usuario_id']." AS usuario_id ";
        $sql .= "FROM   interfaces_planes.afiliados_externos IF ";
        $sql .= "       LEFT JOIN pacientes PA ";
        $sql .= "       ON (IF.afiliado_tipo_id = PA.tipo_id_paciente AND ";
        $sql .= "           IF.afiliado_id = PA.paciente_id) ";
        $sql .= "WHERE  PA.paciente_id IS NULL ";
        
        if(!$rst = $this->ConexionBaseDatos($sql))
          return false;
      }
      return true;
    }
    /**
    * Metodo donde se hace el ingreso del archivo de capitacion
    *
    * @param array $datos Arreglo de datos con la informacion del request
    *
    * @return boolean
    */
    function SubirArchivoPlanoMedicos($datos) 
    {
      if($_FILES['archivo_capitado']['error'] != 0)
      {
        switch ($_FILES['archivo_capitado']['error']) 
        {
          case UPLOAD_ERR_INI_SIZE:
            $this->mensajeDeError = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO";
          break;
          case UPLOAD_ERR_FORM_SIZE:
            $this->mensajeDeError = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO EN LA FORMA";
          break;
          case UPLOAD_ERR_PARTIAL:
            $this->mensajeDeError = "EL ARCHIVO SOLO FUE SUBIDO PARCIALMENTE";
          break;
          case UPLOAD_ERR_NO_FILE:
            $this->mensajeDeError = "EL ARCHIVO NO FUE SUBIDO";
          break;
          case UPLOAD_ERR_NO_TMP_DIR:
            $this->mensajeDeError = "NO HAY DIRECTORIO TEMPORAL PARA SUBIR EL ARCHIVO";
          break;
          case UPLOAD_ERR_CANT_WRITE:
            $this->mensajeDeError = "HA OCURRIDO UN ERROR AL MOMENTO DE COPIAR EL ARCHIVO A DISCO";
          break;
          case UPLOAD_ERR_EXTENSION:
            $this->mensajeDeError = "HA OCURRIDO UN ERROR CON LA EXTENSION DEL ARCHIVO";
          break;
          default:
            $this->mensajeDeError = "HA OCURRIDO UN ERROR DESCONOCIDO MIENTRAS SE REALIZABA EL PROCESO";
          break;
        } 
        return false;
      }
      
      if (is_uploaded_file($_FILES['archivo_capitado']['tmp_name']))
      {        
        $errores = array();
        $dir_siis = GetVarConfigAplication('DIR_SIIS');
        $nombre_archivo = $_FILES ['archivo_capitado']['name'];
        
        $this->BorrarArchivos( $dir_siis."tmp/".$nombre_archivo);
        move_uploaded_file ( $_FILES['archivo_capitado']['tmp_name'], $dir_siis."tmp/".$nombre_archivo ); 
        
        $arch = parse_ini_file($dir_siis."app_modules/".$datos['modulo']."/config/medicos_externos.ini",true);
        
        $this->ConexionTransaccion();
        $sql  = "INSERT INTO archivos_cargados";
        $sql .= "   (";
        $sql .= "     archivo_cargado_id,";
        $sql .= "     usuario_id,";
        $sql .= "     fecha,";
        $sql .= "     descripcion ";
        $sql .= "   )";
        $sql .= "VALUES";
        $sql .= "   (";
        $sql .= "     DEFAULT, ";
        $sql .= "     ".$datos['usuario_id'].", ";
        $sql .= "     NOW(), ";
        $sql .= "     '".$nombre_archivo."' ";
        $sql .= "   )";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
        
        $sql  = "DELETE FROM interfaces_planes.medicos_externos_tmp ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
        
        $lines = fopen($dir_siis."tmp/".$nombre_archivo,"r");
        $flag = ($datos['encabezado'] == '1')? true:false;
        
        $enc = array();
        if(!$flag)
        {
          foreach($arch['campos'] as $k => $dtl)
            $enc[$dtl] = $k;
          
          $flag = false;
        }
        
        $afiliados = array();
        if($datos['separador'] == "t") $datos['separador'] = "\t";
        
        $especialidades = array();
        $identificacion = array();
        
        while (($tmp = fgetcsv($lines, 4096, $datos['separador'])) !== FALSE) 
        {
          if(sizeof($tmp) > 1)
          {
            if($flag)
            {
              foreach($tmp as $k => $dtl)
                $enc[$arch['campos'][$dtl]] = $k;
              
              $flag = false;
            }
            else
            {
              $contin = true;
              if($datos['encabezado'] == '1' && $arch['campos'][$tmp[$enc['tipo_id_tercero']]] == "tipo_id_tercero")
                $contin = false;
             
              if($contin)
              { 
                if(empty($afiliados[$tmp[$enc['tipo_id_tercero']]][$tmp[$enc['tercero_id']]]))
                {
                  if(empty($identificacion[$tmp[$enc['tipo_id_tercero']]]))           
                    $identificacion[$tmp[$enc['tipo_id_tercero']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['tipo_id_tercero']]),$arch['equival']['tipo_id_tercero']);

                  if(empty($especialidades[$tmp[$enc['especialidad']]]))
                    $especialidades[$tmp[$enc['especialidad']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['especialidad']]),$arch['equival']['especialidad']);

                  $afiliados[$tmp[$enc['tipo_id_tercero']]][$tmp[$enc['tercero_id']]] = '1';
                                   
                  $sql  = "INSERT INTO interfaces_planes.medicos_externos_tmp ";
                  $sql .= " ( ";
                  $sql .= "   medicos_externos_tmp_id, ";
                  $sql .= "   tipo_id_tercero, ";
                  $sql .= "   tercero_id, ";
                  $sql .= "   especialidad, ";
                  $sql .= "   nombre_profesional, ";
                  $sql .= "   apellido_profesional ";
                  $sql .= " )"; 
                  $sql .= "VALUES"; 
                  $sql .= " (";
                  $sql .= "    DEFAULT,";
                  $sql .= "    ".$identificacion[$tmp[$enc['tipo_id_tercero']]].", ";
                  $sql .= "   '".trim($tmp[$enc['tercero_id']])."', ";
                  $sql .= "    ".$especialidades[$tmp[$enc['especialidad']]].", ";
                  $sql .= "   '".trim(str_replace("'","\'",$tmp[$enc['nombre_profesional']]))."', ";
                  $sql .= "   '".trim(str_replace("'","\'",$tmp[$enc['apellido_profesional']]))."' ";
                  $sql .= " )";
                  
                  if(!$rst = $this->ConexionTransaccion($sql))
                    return false;
                }
              }
            }
          }
        }
        
        $this->Commit();
        fclose($lines);
        
        $sql  = "INSERT INTO medicos_externos ";
        $sql .= "   (";
        $sql .= "     tipo_id_tercero, ";
        $sql .= "     tercero_id, ";
        $sql .= "     especialidad, ";
        $sql .= "     nombre_profesional, ";
        $sql .= "     apellido_profesional, ";
        $sql .= "     fecha_registro, ";
        $sql .= "     usuario_id ";
        $sql .= "   ) ";
        $sql .= "SELECT IF.tipo_id_tercero, ";
        $sql .= "       IF.tercero_id, ";
        $sql .= "       IF.especialidad, ";
        $sql .= "       IF.nombre_profesional, ";
        $sql .= "       IF.apellido_profesional, ";
        $sql .= "       NOW() AS fecha_registro, ";
        $sql .= "       ".$datos['usuario_id']." AS usuario_id ";
        $sql .= "FROM   interfaces_planes.medicos_externos_tmp IF ";
        $sql .= "       LEFT JOIN medicos_externos PA ";
        $sql .= "       ON (IF.tipo_id_tercero = PA.tipo_id_tercero AND ";
        $sql .= "           IF.tercero_id = PA.tercero_id) ";
        $sql .= "WHERE  PA.tercero_id IS NULL ";
        $sql .= "AND    PA.tipo_id_tercero IS NULL ";
        
        if(!$rst = $this->ConexionBaseDatos($sql))
          return false;
      }
      return true;
    }
    /**
    * Metodo donde se hace el ingreso del archivo de capitacion
    *
    * @param array $datos Arreglo de datos con la informacion del request
    *
    * @return boolean
    */
    function SubirArchivoPlanoDespachos($datos) 
    {
      if($_FILES['archivo_capitado']['error'] != 0)
      {
        switch ($_FILES['archivo_capitado']['error']) 
        {
          case UPLOAD_ERR_INI_SIZE:
            $this->mensajeDeError = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO";
          break;
          case UPLOAD_ERR_FORM_SIZE:
            $this->mensajeDeError = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO EN LA FORMA";
          break;
          case UPLOAD_ERR_PARTIAL:
            $this->mensajeDeError = "EL ARCHIVO SOLO FUE SUBIDO PARCIALMENTE";
          break;
          case UPLOAD_ERR_NO_FILE:
            $this->mensajeDeError = "EL ARCHIVO NO FUE SUBIDO";
          break;
          case UPLOAD_ERR_NO_TMP_DIR:
            $this->mensajeDeError = "NO HAY DIRECTORIO TEMPORAL PARA SUBIR EL ARCHIVO";
          break;
          case UPLOAD_ERR_CANT_WRITE:
            $this->mensajeDeError = "HA OCURRIDO UN ERROR AL MOMENTO DE COPIAR EL ARCHIVO A DISCO";
          break;
          case UPLOAD_ERR_EXTENSION:
            $this->mensajeDeError = "HA OCURRIDO UN ERROR CON LA EXTENSION DEL ARCHIVO";
          break;
          default:
            $this->mensajeDeError = "HA OCURRIDO UN ERROR DESCONOCIDO MIENTRAS SE REALIZABA EL PROCESO";
          break;
        } 
        return false;
      }
      
      if (is_uploaded_file($_FILES['archivo_capitado']['tmp_name']))
      {        
        $errores = array();
        $dir_siis = GetVarConfigAplication('DIR_SIIS');
        $nombre_archivo = $_FILES ['archivo_capitado']['name'];
        
        $validacion = $this->ValidarArchivoPlano($nombre_archivo);
        
        if(!empty($validacion))
        {
          $this->mensajeDeError = "EL ARCHIVO PLANO ".$nombre_archivo.", YA FUE SUIDO AL SISTEMA, EL DIA ".$validacion['fecha_registro'].", POR EL UAUSIO ".$validacion['nombre']."";
          return false;
        }
        
        $this->BorrarArchivos( $dir_siis."tmp/".$nombre_archivo);
        move_uploaded_file ( $_FILES['archivo_capitado']['tmp_name'], $dir_siis."tmp/".$nombre_archivo ); 
        
        $arch = parse_ini_file($dir_siis."app_modules/".$datos['modulo']."/config/despachos_externos.ini",true);
        
        $this->ConexionTransaccion();

        $sql  = "INSERT INTO archivos_cargados";
        $sql .= "   (";
        $sql .= "     archivo_cargado_id,";
        $sql .= "     usuario_id,";
        $sql .= "     fecha,";
        $sql .= "     descripcion ";
        $sql .= "   )";
        $sql .= "VALUES";
        $sql .= "   (";
        $sql .= "     DEFAULT, ";
        $sql .= "     ".$datos['usuario_id'].", ";
        $sql .= "     NOW(), ";
        $sql .= "     '".$nombre_archivo."' ";
        $sql .= "   )";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
                
        $lines = fopen($dir_siis."tmp/".$nombre_archivo,"r");
        $flag = ($datos['encabezado'] == '1')? true:false;
        
        $enc = array();
        if(!$flag)
        {
          foreach($arch['campos'] as $k => $dtl)
            $enc[$dtl] = $k;
          
          $flag = false;
        }
        
        $afiliados = array();
        $datos_farmacia = array();
        $identificacion = array();
        $servicios = array();
        $usuarios = array();
        $moleculas = array();
        $laboratorios = array();
        $especialidades = array();
        $productos = array();
        
        if($datos['separador'] == "t") $datos['separador'] = "\t";
        $documentos = array();
        while (($tmp = fgetcsv($lines, 4096, $datos['separador'])) !== FALSE) 
        {
          if(sizeof($tmp) > 1)
          {
            if($flag)
            {
              foreach($tmp as $k => $dtl)
                $enc[$arch['campos'][$dtl]] = $k;
              
              $flag = false;
            }
            else
            {
              $contin = true;
              if($datos['encabezado'] == '1' && $arch['campos'][$tmp[$enc['despacho_identificador']]] == "despacho_identificador")
                $contin = false;

              if($contin)
              {
                if(empty($datos_farmacia[$tmp[$enc['farmacia_id']]]))
                {
                  $datos_farmacia[$tmp[$enc['farmacia_id']]] = $this->ObtenerEquivalenciaFarmacia($tmp[$enc['farmacia_id']],$datos['empresa_id']);
                  $documentos[$datos['empresa_id']][$datos_farmacia[$tmp[$enc['farmacia_id']]]['centro_utilidad']][$datos_farmacia[$tmp[$enc['farmacia_id']]]['bodega']] = '1';
                }
                if(empty($identificacion[$tmp[$enc['tipo_id_paciente']]]))           
                  $identificacion[$tmp[$enc['tipo_id_paciente']]] = $this->EvaluarEquivalencia($tmp[$enc['tipo_id_paciente']],$arch['equival']['tipo_id_paciente']);
                
                if(empty($identificacion[$tmp[$enc['tipo_id_tercero']]]))           
                  $identificacion[$tmp[$enc['tipo_id_tercero']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['tipo_id_tercero']]),$arch['equival']['tipo_id_tercero']);
                
                if(empty($servicios[$tmp[$enc['servicio']]]))           
                  $servicios[$tmp[$enc['servicio']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['servicio']]),$arch['equival']['servicio']);
                
                if(empty($usuarios[$tmp[$enc['usuario_id']]]))
                  $usuarios[$tmp[$enc['usuario_id']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['usuario_id']]),$arch['equival']['usuario_id']);
                
                if(empty($usuarios[$tmp[$enc['usuario_id_despacho']]]))
                  $usuarios[$tmp[$enc['usuario_id_despacho']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['usuario_id_despacho']]),$arch['equival']['usuario_id_despacho']);
                
                if(empty($moleculas[$tmp[$enc['molecula_id']]]))
                  $moleculas[$tmp[$enc['molecula_id']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['molecula_id']]),$arch['equival']['molecula_id']);
                
                if(empty($laboratorios[$tmp[$enc['laboratorio_id']]]))
                  $laboratorios[$tmp[$enc['laboratorio_id']]] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['laboratorio_id']]),$arch['equival']['laboratorio_id']);
                
                if(empty($especialidades[$tmp[$enc['especialidad']]]))
                  $especialidades[$tmp[$enc['especialidad']]] = $this->EvaluarEquivalencia(strtoupper(trim($tmp[$enc['especialidad']])),$arch['equival']['especialidad']);
                
                if(empty($tmp[$enc['lote']]))
                {
                  $lote = "'".$datos_farmacia[$tmp[$enc['farmacia_id']]]['lote_default']."'";
                  $fecha_vencimiento = "'".$datos_farmacia[$tmp[$enc['farmacia_id']]]['fecha_vencimiento_default']."'";
                }
                else
                {
                  $lote = "'".$tmp[$enc['lote']]."'";
                  $fecha_vencimiento = $this->EvaluarFormato(trim($tmp[$enc['fecha_vencimiento']]),$arch['formatos']['fecha_vencimiento']);
                }
                
                if(empty($productos[$tmp[$enc['codigo_producto']]][$lote][$fecha_vencimiento]))           
                {
                  $productos[$tmp[$enc['codigo_producto']]][$lote][$fecha_vencimiento]['codigo'] = $this->EvaluarEquivalencia(strtoupper($tmp[$enc['codigo_producto']]),$arch['equival']['codigo_producto'],null,true);
                  $productos[$tmp[$enc['codigo_producto']]][$lote][$fecha_vencimiento]['cantidad'] = $tmp[$enc['cantidad_entregada']];
                  $productos[$tmp[$enc['codigo_producto']]][$lote][$fecha_vencimiento]['movimiento'] = $tmp[$enc['sw_tipo_mov']];
                  $productos[$tmp[$enc['codigo_producto']]][$lote][$fecha_vencimiento]['centro_utilidad'] = $datos_farmacia[$tmp[$enc['farmacia_id']]]['centro_utilidad'];
                  $productos[$tmp[$enc['codigo_producto']]][$lote][$fecha_vencimiento]['bodega'] = $datos_farmacia[$tmp[$enc['farmacia_id']]]['bodega'];
                }
                else
                {
                  $productos[$tmp[$enc['codigo_producto']]][$lote][$fecha_vencimiento]['cantidad'] += $tmp[$enc['cantidad_entregada']];
                }
                
                $sql  = "INSERT INTO interfaces_planes.despacho_formulas ";
                $sql .= " ( ";
                $sql .= "   despacho_formula_id,";
                $sql .= "   archivo_cargado_id ,";
                $sql .= "   despacho_identificador ,";
                $sql .= "   despacho_secuencia ,";
                $sql .= "   numero_paciente_sisap ,";
                $sql .= "   sw_encuentro_paciente ,";
                $sql .= "   tipo_documento_formula ,";
                $sql .= "   formula_id ,";
                $sql .= "   fecha_formula ,";
                $sql .= "   formula_digital_id ,";
                $sql .= "   tipo_id_paciente,";
                $sql .= "   paciente_id ,";
                $sql .= "   tipo_id_tercero,";
                $sql .= "   tercero_id ,";
                $sql .= "   grupo_especialidad ,";
                $sql .= "   especialidad ,";
                $sql .= "   fecha_radicacion ,";
                $sql .= "   sw_transcripcion ,";
                $sql .= "   autorizacion ,";
                $sql .= "   servicio ,";
                $sql .= "   ips_ponal ,";
                $sql .= "   usuario_id ,";
                $sql .= "   diagnostico_id,";
                $sql .= "   producto_detalle ,";
                $sql .= "   codigo_producto ,";
                $sql .= "   codigo_cssfmpn ,";
                $sql .= "   molecula_id ,";
                $sql .= "   laboratorio_id ,";
                $sql .= "   codigo_estado ,";
                $sql .= "   descripcion_estado ,";
                $sql .= "   codigo_autorizacion ,";
                $sql .= "   accion_seguir ,";
                $sql .= "   fecha_entrega ,";
                $sql .= "   cantidad_formula ,";
                $sql .= "   cantidad_entregada ,";
                $sql .= "   valor_unitario ,";
                $sql .= "   valor_total ,";
                $sql .= "   usuario_id_despacho ,";
                $sql .= "   codigo_licitacion ,";
                $sql .= "   descripcion_licitacion ,";
                $sql .= "   empresa_id ,";
                $sql .= "   sw_tipo_mov ,";
                $sql .= "   farmacia_id ,";
                $sql .= "   centro_utilidad ,";
                $sql .= "   bodega, ";
                $sql .= "   lote, ";
                $sql .= "   fecha_vencimiento ";
                $sql .= " )"; 
                $sql .= "VALUES"; 
                $sql .= " (";
                $sql .= "    DEFAULT,";
                $sql .= "     CURRVAL('archivos_cargados_archivo_cargado_id_seq'::regclass), ";
                $sql .= "    ".$tmp[$enc['despacho_identificador']].", ";
                $sql .= "   '".$tmp[$enc['despacho_secuencia']]."', ";
                $sql .= "    ".$tmp[$enc['numero_paciente_sisap']].", ";
                $sql .= "   '".$tmp[$enc['sw_encuentro_paciente']]."', ";
                $sql .= "   '".$tmp[$enc['tipo_documento_formula']]."', ";
                $sql .= "   '".$tmp[$enc['formula_id']]."', ";
                $sql .= "    ".$this->EvaluarFormato($tmp[$enc['fecha_formula']],$arch['formatos']['fecha_formula']).",";
                $sql .= "   '".$tmp[$enc['formula_digital_id']]."', ";
                $sql .= "    ".$identificacion[$tmp[$enc['tipo_id_paciente']]].", ";
                $sql .= "   '".trim($tmp[$enc['paciente_id']])."', ";
                $sql .= "    ".$identificacion[$tmp[$enc['tipo_id_tercero']]].", ";
                $sql .= "   '".trim($tmp[$enc['tercero_id']])."', ";
                $sql .= "   '".$tmp[$enc['grupo_especialidad']]."', ";
                $sql .= "    ".$especialidades[$tmp[$enc['especialidad']]].", ";
                $sql .= "    ".$this->EvaluarFormato($tmp[$enc['fecha_radicacion']],$arch['formatos']['fecha_radicacion']).",";
                $sql .= "   '".$tmp[$enc['sw_transcripcion']]."', ";
                $sql .= "   '".$tmp[$enc['autorizacion']]."', ";
                $sql .= "    ".$servicios[$tmp[$enc['servicio']]].", ";
                $sql .= "   '".$tmp[$enc['ips_ponal']]."', ";
                $sql .= "    ".$usuarios[$tmp[$enc['usuario_id']]].", ";
                $sql .= "   '".trim($tmp[$enc['diagnostico_id']])."', ";
                $sql .= "   '".$tmp[$enc['producto_detalle']]."', ";
                $sql .= "    ".$productos[$tmp[$enc['codigo_producto']]][$lote][$fecha_vencimiento]['codigo'].", ";
                $sql .= "   '".$tmp[$enc['codigo_cssfmpn']]."', ";
                $sql .= "    ".$moleculas[$tmp[$enc['molecula_id']]].", ";
                $sql .= "    ".$laboratorios[$tmp[$enc['laboratorio_id']]].", ";
                $sql .= "   '".$tmp[$enc['codigo_estado']]."', ";
                $sql .= "   '".$tmp[$enc['descripcion_estado']]."', ";
                $sql .= "   '".$tmp[$enc['codigo_autorizacion']]."', ";
                $sql .= "   '".$tmp[$enc['accion_seguir']]."', ";
                $sql .= "    ".$this->EvaluarFormato($tmp[$enc['fecha_entrega']],$arch['formatos']['fecha_entrega']).",";
                $sql .= "   '".$tmp[$enc['cantidad_formula']]."', ";
                $sql .= "   '".$tmp[$enc['cantidad_entregada']]."', ";
                $sql .= "    ".$tmp[$enc['valor_unitario']].", ";
                $sql .= "    ".$tmp[$enc['valor_total']].", ";
                $sql .= "    ".$usuarios[$tmp[$enc['usuario_id_despacho']]].", ";
                $sql .= "   '".$tmp[$enc['codigo_licitacion']]."', ";
                $sql .= "   '".$tmp[$enc['descripcion_licitacion']]."', ";
                $sql .= "   '".$datos['empresa_id']."', ";
                $sql .= "   '".$tmp[$enc['sw_tipo_mov']]."', ";
                $sql .= "   '".$tmp[$enc['farmacia_id']]."', ";
                $sql .= "   '".$datos_farmacia[$tmp[$enc['farmacia_id']]]['centro_utilidad']."', ";
                $sql .= "   '".$datos_farmacia[$tmp[$enc['farmacia_id']]]['bodega']."', ";
                $sql .= "    ".$lote.", ";
                $sql .= "    ".$fecha_vencimiento." ";
                $sql .= " )";
                
                $movimientos[$datos_farmacia[$tmp[$enc['farmacia_id']]]['bodega']][$tmp[$enc['sw_tipo_mov']]] = 1;
                if(!$rst = $this->ConexionTransaccion($sql))
                  return false;
              }
            }
          }
        }
        fclose($lines);
        
        $sql  = "SELECT DISTINCT IF.codigo_producto, ";
        $sql .= "       IF.lote, ";
        $sql .= "       IF.fecha_vencimiento ";
        $sql .= "FROM   interfaces_planes.despacho_formulas IF LEFT JOIN";
        $sql .= "       existencias_bodegas_lote_fv LF ";
        $sql .= "       ON (";
        $sql .= "         IF.empresa_id = LF.empresa_id AND";
        $sql .= "         IF.centro_utilidad = LF.centro_utilidad AND";
        $sql .= "         IF.bodega = LF.bodega AND";
        $sql .= "         IF.lote = LF.lote AND";
        $sql .= "         IF.fecha_vencimiento = LF.fecha_vencimiento AND";
        $sql .= "         IF.codigo_producto = LF.codigo_producto ";
        $sql .= "       ) ";
        $sql .= "WHERE  IF.archivo_cargado_id = CURRVAL('archivos_cargados_archivo_cargado_id_seq'::regclass) ";
        $sql .= "AND    LF.codigo_producto IS NULL ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
                  
        $faltantes = array();

        while(!$rst->EOF)
        {
          $faltantes[] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        
        if(!empty($faltantes))
        {
          $msg  = "<table width=\"98%\" rules=\"all\" border=\"1\" align=\"center\" >\n";
          $msg .= " <tr align=\"center\">\n";
          $msg .= "   <td colspan=\"4\" class=\"label_error\">LOS SIGUIENTE PRODUCTOS CON SUS RESPECTIVOS LOTES Y FECHAS DE VENCIMIENTO NO EXISTEN EN EL SISTEMA</td>\n";
          $msg .= " </tr>\n";
          $msg .= " <tr class=\"formulacion_table_list\">\n";
          $msg .= "   <td width=\"2\">#</td>\n";
          $msg .= "   <td>CODIGO</td>\n";
          $msg .= "	  <td>LOTE</td>\n";
          $msg .= "	  <td>FECHA VENCIMIENTO</td>\n";
          $msg .= " </tr>\n";
          foreach($faltantes as $ky => $dtl)
          {
            $msg .= " <tr class=\"normal_10AN\" >\n";
            $msg .= "   <td align=\"right\">".($ky+1)."</td>\n";
            $msg .= "   <td>".$dtl['codigo_producto']."</td>\n";
            $msg .= "	  <td>".$dtl['lote']."</td>\n";
            $msg .= "	  <td>".$dtl['fecha_vencimiento']."</td>\n";
            $msg .= " </tr>\n";
          }
          $msg .= "</table>\n";
          $this->mensajeDeError = $msg;
          $this->Rollback();
          return false;
        }
        
        $sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE";
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
        
        foreach($this->bodegas_documentos as $key => $centros)
        {
          foreach($centros as $k1 => $bodega)
          {
            foreach($bodega as $k2 => $tipo_documento)
            {
              foreach($tipo_documento as $k3 => $dtl)
              {
                $sw_tipo_movimiento = ($k3 == 'E')? '0':'1';
                if($movimientos[$k2][$sw_tipo_movimiento])
                {
                  $sql  = "SELECT numeracion, ";
                  $sql .= "       prefijo ";
                  $sql .= "FROM   documentos ";
                  $sql .= "WHERE  documento_id = ".$dtl['bodegas_doc_id']." ";
                  $sql .= "AND    empresa_id = '".$key."' ";

                  if(!$rst = $this->ConexionTransaccion($sql))
                    return false;
                  
                  $numeraciones = array();
      
                  if(!$rst->EOF)
                  {
                    $numeraciones = $rst->GetRowAssoc($ToUpper = false);
                    $rst->MoveNext();
                  }
                  
                  $sql  = "INSERT INTO bodegas_documentos";
                  $sql .= "   ( ";
                  $sql .= "     bodegas_doc_id,";
                  $sql .= "     numeracion,";
                  $sql .= "     fecha,";
                  $sql .= "     total_costo,";
                  $sql .= "     observacion,";
                  $sql .= "     usuario_id,";
                  $sql .= "     fecha_registro";
                  $sql .= "   ) ";
                  $sql .= "SELECT ".$dtl['bodegas_doc_id']." AS bodegas_doc_id,";
                  $sql .= "       ".$numeraciones['numeracion']." AS numeracion, ";
                  $sql .= "       NOW() AS fecha, ";
                  $sql .= "       SUM(valor_total) AS total_costo,";
                  $sql .= "       'MOVIMIENTO DE INVENTARIOS POR ARCHIVOS PLANOS' AS observacion,";
                  $sql .= "       ".$datos['usuario_id']." AS usuario_id, ";
                  $sql .= "       NOW() AS fecha_registro ";
                  $sql .= "FROM   interfaces_planes.despacho_formulas ";
                  $sql .= "WHERE  empresa_id = '".$key."' ";
                  $sql .= "AND    centro_utilidad = '".$k1."' ";
                  $sql .= "AND    bodega = '".$k2."' ";
                  $sql .= "AND    sw_tipo_mov = '".$sw_tipo_movimiento."' ";

                  if(!$rst = $this->ConexionTransaccion($sql))
                    return false;

                  $sql  = "INSERT INTO bodegas_documentos_d";
                  $sql .= "   (";
                  $sql .= "     codigo_producto,";
                  $sql .= "     cantidad,";
                  $sql .= "     total_costo,";
                  $sql .= "     bodegas_doc_id,";
                  $sql .= "     numeracion,";
                  $sql .= "     fecha_vencimiento, 	";
                  $sql .= "     lote 	";
                  $sql .= "   ) ";
                  $sql .= "SELECT codigo_producto, ";
                  $sql .= "       cantidad_entregada::integer, ";
                  $sql .= "       valor_unitario,";
                  $sql .= "       ".$dtl['bodegas_doc_id']." AS bodegas_doc_id,";
                  $sql .= "       ".$numeraciones['numeracion']." AS numeracion, ";
                  $sql .= "       fecha_vencimiento, 	";
                  $sql .= "       lote 	";
                  $sql .= "FROM   interfaces_planes.despacho_formulas ";
                  $sql .= "WHERE  empresa_id = '".$key."' ";
                  $sql .= "AND    centro_utilidad = '".$k1."' ";
                  $sql .= "AND    bodega = '".$k2."' ";
                  $sql .= "AND    sw_tipo_mov = '".$sw_tipo_movimiento."' ";

                  if(!$rst = $this->ConexionTransaccion($sql))
                    return false;

                  $sql  = "UPDATE documentos ";
                  $sql .= "SET    numeracion = numeracion+1 ";
                  $sql .= "WHERE  documento_id = ".$dtl['bodegas_doc_id']." ";

                  if(!$rst = $this->ConexionTransaccion($sql))
                    return false;
                    
                  $sql  = "UPDATE bodegas_doc_numeraciones ";
                  $sql .= "SET    numeracion = ".($numeraciones['numeracion'] +1)." ";
                  $sql .= "WHERE  bodegas_doc_id = ".$dtl['bodegas_doc_id']." ";

                  if(!$rst = $this->ConexionTransaccion($sql))
                    return false;

                  unset($movimientos[$k2][$sw_tipo_movimiento]);
                }
              }
            }
          }
        }
        
        foreach($productos as $k1 => $lotes)
        {
          foreach($lotes as $k2 => $fechas)
          {
            foreach($fechas as $k3 => $dtl)
            {
              $sql  = "UPDATE existencias_bodegas_lote_fv ";
              $sql .= "SET    existencia_actual = existencia_actual ".(($dtl['movimiento'] == '1')? "+":"-")." ".$dtl['cantidad']." ";
              if($dtl['movimiento'] == '1')
                $sql .= "       , existencia_inicial = existencia_inicial + ".$dtl['cantidad']." ";
              
              $sql .= "WHERE  empresa_id = '".$datos['empresa_id']."' ";
              $sql .= "AND    centro_utilidad = '".$dtl['centro_utilidad']."' ";
              $sql .= "AND    bodega = '".$dtl['bodega']."' ";
              $sql .= "AND    lote = ".$k2." ";
              $sql .= "AND    fecha_vencimiento = ".$k3." ";
              $sql .= "AND    codigo_producto =  ".$dtl['codigo']." ";
              
              if(!$rst = $this->ConexionTransaccion($sql))
                return false;
                
              $affec = $this->dbconn->Affected_Rows();
              if(!($affec === 0))
              {
                $sql  = "UPDATE existencias_bodegas ";
                $sql .= "SET    existencia = existencia ".(($dtl['movimiento'] == '1')? "+":"-")." ".$dtl['cantidad']." ";
                $sql .= "WHERE  empresa_id = '".$datos['empresa_id']."' ";
                $sql .= "AND    centro_utilidad = '".$dtl['centro_utilidad']."' ";
                $sql .= "AND    bodega = '".$dtl['bodega']."' ";
                $sql .= "AND    codigo_producto = ".$dtl['codigo']." ";
                
                if(!$rst = $this->ConexionTransaccion($sql))
                  return false;
              }
            }
          }
        }
        $this->Commit();
      }
      return true;
    }
    /**
    * Funcion que permite hacer un borrado de los archivos temporales
    *
    * @param string $path Ruta del archivo temporal
    *
    * @return boolean
    */
    function BorrarArchivos($file)
    {
      unlink($path."/".$file);
      return true;
    }
    /**
    * Metodo para hacer la evaluacion de la equivalencia de un campo
    *
    * @param string $valorCampo Cadena con el valor a evlauar
    * @param string $equivalencia Cadena separada por puntos que contiene 
    *               <nombre_tabla>:<nombre_campo_comparacion>:<nombre_campo_devolver>
    * @param integer $plan_id Identficador del plan si lo hay
    * @param boolena $no_plan Indica si la tabla de equivalencia maneja plan 
    *
    * @return string
    */
    function EvaluarEquivalencia($valorCampo,$equivalencia,$plan_id,$no_plan = false)
    {
      if($equivalencia == "")
        return ((trim($valorCampo))? "'".$valorCampo."'":"NULL");
      
      list($tabla,$campo,$posicion,$tipo_dato,$esquema) = explode(":",$equivalencia);
      
      $s = "'";
      if($tipo_dato == 'integer')
        $s = "";
        
      if(!$tabla || !$campo || !$posicion)
        return ((trim($valorCampo))? $s.$valorCampo.$s:"NULL");
      
      if(!$esquema)
        $esquema = "interfaces_planes";
        
      $sql  = "SELECT * ";
      $sql .= "FROM   ".$esquema.".".$tabla." ";
      $sql .= "WHERE  ".$campo." = '".$valorCampo."' ";
      if(!$no_plan)
      {
        if($plan_id)
          $sql .= "AND    plan_id = ".$plan_id." ";
        else
          $sql .= "AND    plan_id IS NULL ";
      }
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      return (($datos[$posicion])? $s."".$datos[$posicion]."".$s : "NULL" );
    }    
    /**
    * Metodo donde se obtiene la bodega asociada a la farmacia entrada por 
    * archivo plano
    *
    * @param string $farmacia Identificador de la farmacia, segun archivo
    * @param string $empresa_id Identificador de la empresa
    *
    * @return string
    */
    function ObtenerEquivalenciaFarmacia($farmacia,$empresa_id)
    {
      $sql  = "SELECT centro_utilidad ,";
      $sql .= "       bodega, ";
      $sql .= "       lote_default, ";
      $sql .= "       fecha_vencimiento_default ";
      $sql .= "FROM   interfaces_planes.equivalencia_farmacia ";
      $sql .= "WHERE  farmacia_id = '".$farmacia."' ";
      $sql .= "AND    empresa_id = '".$empresa_id."' "; 
            
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      if(!empty($datos))
      {
        $sql  = "SELECT tipo_movimiento,";
        $sql .= "       bodegas_doc_id ";
        $sql .= "FROM   bodegas_doc_numeraciones ";
        $sql .= "WHERE  sw_transaccion_medicamentos = '1' ";
        $sql .= "AND    sw_estado = '1' ";
        $sql .= "AND    empresa_id = '".$empresa_id."' ";
        $sql .= "AND    centro_utilidad = '".$datos['centro_utilidad']."' ";
        $sql .= "AND    bodega = '".$datos['bodega']."' ";
        $sql .= "ORDER BY bodegas_doc_id ";
        
        if(!$rst = $this->ConexionBaseDatos($sql))
          return false;
              
        while(!$rst->EOF)
        {
          $this->bodegas_documentos[$empresa_id][$datos['centro_utilidad']][$datos['bodega']][$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
      }
      return $datos;
    }
    /**
    * Metodo para poner el formato a las fechas
    *
    * @param string $valorCampo Cadena con el valor a evlauar
    * @param string $formato cadena que contiene el formato de la fecha
    * 
    * @return string
    */
    function EvaluarFormato($valorCampo,$formato)
    {
      if($formato == "")
        return (($valorCampo)? "'".$valorCampo."'":"NULL");
      
      $valor = "";
      switch($formato)
      {
        case 'dd/mm/yyyy':
          $valor = "'".$this->DividirFecha($valorCampo)."'";
        break;         
        case 'dd/mm/yyyy hh:mi:ss':
          $aux = explode(" ",$valorCampo); 
          $valor = "'".$this->DividirFecha($aux[0])." ".$aux[1]."'";
        break;        
        case 'mm/dd/yyyy':
          $a = explode("/",$valorCampo);
          $valor = "'".$a[2]."-".$a[0]."-".$a[1]."'";
        break;
        default:
          $valor = "'".$valorCampo."'";
        break;
      }
      
      return $valor;
    }
  }
?>