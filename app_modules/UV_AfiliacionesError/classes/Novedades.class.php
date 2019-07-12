<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Novedades.class.php,v 1.2 2009/10/05 18:27:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: Novedades
  * Clase encargada de hacer las consultas y las actualizaciones para 
  * las novedades de los afiliados
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
  class Novedades extends Afiliaciones
  {
    /**
    * Constructor de la clase
    */
    function Novedades(){}
    /**
    * Funcion donde se sube los archivos de pila de salud
    *
    * @return boolean
    */
    function SubirRegistrosSalud() 
    {
      $ruta_archivo = "cache/tmp_".UserGetUID();
      mkdir($ruta_archivo);
      
      if (is_uploaded_file($_FILES['archivo_encabezado']['tmp_name']) && is_uploaded_file($_FILES['archivo_novedades']['tmp_name']))
      {
        $arch_encabezado = $_FILES ['archivo_encabezado']['name'];
        $arch_novedades = $_FILES ['archivo_novedades']['name'];
        
        move_uploaded_file ( $_FILES['archivo_encabezado']['tmp_name'], $ruta_archivo."/".$arch_encabezado ); 
        move_uploaded_file ( $_FILES['archivo_novedades']['tmp_name'], $ruta_archivo."/".$arch_novedades ); 
        $this->ConexionTransaccion();
        
        $periodos = $this->IngresarArchivoEncabezadoSalud($ruta_archivo."/".$arch_encabezado);
        
        if(empty($periodos))
        {
          $this->error = "EL ARCHIVO DE ENCABEZADO NO POSEE DATOS";
          $this->BorrarArchivos($ruta_archivo);
          return false;
        }
        
        $rst = $this->IngresarArchivoNovedadesSalud($ruta_archivo."/".$arch_novedades,$periodos);
        if($rst === false) 
        {
          $this->BorrarArchivos($ruta_archivo);
          //$this->error = $this->ErrMsg();
          return false;
        }
        $this->dbconn->CommitTrans();
      }
      else
      {
        $this->BorrarArchivos($ruta_archivo);
        $this->error = "HA OCURRIDO UN ERROR MIENTRAS SE SUBIA EL ARCHIVO AL SERVIDOR";
        return false;
      }    
      
      $this->BorrarArchivos($ruta_archivo);
      return true;
    }
    /**
    * Funcion donde se hace el borrado de una carpeta y todo su contenido 
    * fisico
    *
    * @param String $path Direccion donde se encuentra la carpeta
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
    * Funcion donde se hace el registro en la BD de los datos del 
    * archivo de encabezado de salud
    *
    * @param String $archivo Nombre y ruta del archivo a leer
    *
    * @return boolean
    */
    function IngresarArchivoEncabezadoSalud($archivo)
    {
      $periodos = array();
      $lines = file($archivo);
      $i=0;
      foreach ($lines as $line_num => $line)
      { 
        if($line)
        {
          $sw = "0";
          if(substr($line,226,1) != " ") $sw = "1";
          
          $sql = "SELECT NEXTVAL('eps_afiliados_pila_salud_enca_eps_afiliados_pila_salud_enca_seq') 	";
          if(!$rst = $this->ConexionTransaccion($sql)) return false;
          
          $indice = $rst->fields[0]; 
          
          $sql  = "INSERT INTO eps_afiliados_pila_salud_encabezado (";
          $sql .= "   eps_afiliados_pila_salud_encabezado_id 	,"; 
          $sql .= "   periodo_pago,"; 
          $sql .= "   sw_correccion,"; 
          $sql .= "   usuario_registro "; 
          $sql .= "   ) "; 
          $sql .= "VALUES ( "; 
          $sql .= "    ".$indice.", "; 
          $sql .= "   '".substr($line,311,7)."', "; 
          $sql .= "   '".$sw."', "; 
          $sql .= "    ".UserGetUID()." ";
          $sql .= "); "; 
          if(!$rst = $this->ConexionTransaccion($sql)) return false;
          $periodos[$i]['indice'] = $indice;
          $periodos[$i]['periodo'] = substr($line,311,7);
          $periodos[$i++]['numero_lineas'] = intval(substr($line,338,5));
        }
      }
      
      if(empty($periodos[0]))  return false;
      
      return $periodos[0];
    }
    /**
    * Funcion donde se hace el registro en la BD de los datos del 
    * archivo de novedades de salud
    *
    * @param String $archivo Nombre y ruta del archivo a leer
    * @param array $periodos Vector con los datos del periodo al cual pertenece
    *              el archivo de novedades
    * @return boolean
    */
    function IngresarArchivoNovedadesSalud($archivo,$periodos)
    {
      $noexistentes = array();
      $lines = file($archivo);

      if($periodos['numero_lineas'] != sizeof($lines))
      {
        $this->error = "EL NUMERO DE REGISTROS DEL ENCABEZADO NO CONCUERDA CON EL NUMERO DE REGISTROS TOTALES DEL ARCHIVO";
        return false;
      }
      
      foreach ($lines as $line_num => $line)
      { 
        $afiliado_id = trim(substr($line,9,16));
        $afiliado_tipo_id = trim(substr($line,7,2));
        $incapacidad = substr($line,151,1);
        $ingreso = substr($line,210,9);
        $sw_cotiza_pension = $sw_residencia_exterior = "0";
        $nvd = array("0","0","0","0","0","0","0","0","0","0","0","0","0","0","0");
        
        if(substr($line,29,1)!= " ") $sw_cotiza_pension = "1";
        if(substr($line,30,1)!= " ") $sw_residencia_exterior = "1";
        
        if(substr($line,136,1) != " ") $nvd[0] = "1";
        if(substr($line,137,1) != " ") $nvd[1] = "1";
        if(substr($line,138,1) != " ") $nvd[2] = "1";
        if(substr($line,139,1) != " ") $nvd[3] = "1";
        if(substr($line,140,1) != " ") $nvd[4] = "1";
        if(substr($line,141,1) != " ") $nvd[5] = "1";
        if(substr($line,142,1) != " ") $nvd[6] = "1";
        if(substr($line,143,1) != " ") $nvd[7] = "1";
        if(substr($line,144,1) != " ") $nvd[8] = "1";
        if(substr($line,145,1) != " ") $nvd[9] = "1";
        if(substr($line,146,1) != " ") $nvd[10] = "1";
        if(substr($line,147,1) != " ") $nvd[11] = "1";
        if(substr($line,148,1) != " ") $nvd[12] = "1";
        if(substr($line,149,1) != " ") $nvd[13] = "1";
        if(substr($line,150,1) != " ") $nvd[14] = "1";
        if(!$incapacidad) $incapacidad = 0;
        if(!$ingreso) $ingreso = 0;
        
        $sql  = "INSERT INTO eps_afiliados_pila_salud  "; 
        $sql .= "(  ";
        $sql .= "   eps_afiliados_pila_salud_encabezado_id,  ";
        $sql .= "   secuencia , ";
        $sql .= "   afiliado_id , ";
        $sql .= "   afiliado_tipo_id , ";
        $sql .= "   tipo_cotizante , ";
        $sql .= "   sub_tipo_cotizante, ";
        $sql .= "   sw_cotiza_pension , ";
        $sql .= "   sw_residencia_exterior , ";
        $sql .= "   tipo_dpto_id 	, 	 ";
        $sql .= "   tipo_mpio_id 	, 	 ";
        $sql .= "   primer_apellido , ";
        $sql .= "   segundo_apellido , ";
        $sql .= "   primer_nombre , ";
        $sql .= "   segundo_nombre , ";
        $sql .= "   novedad_ingreso , ";
        $sql .= "   novedad_retiro , ";
        $sql .= "   novedad_traslado_otra_eps , ";
        $sql .= "   novedad_traslado_eps , ";
        $sql .= "   novedad_traslado_otra_afp , ";
        $sql .= "   novedad_traslado_afp , ";
        $sql .= "   novedad_variacion_salario , ";
        $sql .= "   novedad_tarifa_especial , ";
        $sql .= "   novedad_variacion_transitoria , ";
        $sql .= "   novedad_suspension_contrato , ";
        $sql .= "   novedad_incapacidad_temporal , ";
        $sql .= "   novedad_licencia_maternidad , ";
        $sql .= "   novedad_vacaciones , ";
        $sql .= "   novedad_aporte_voluntario , ";
        $sql .= "   novedad_variacion_centro_laboral , ";
        $sql .= "   novedad_incapacidad_accidente , ";
        $sql .= "   afiliado_eps_id , ";
        $sql .= "   afiliado_eps_id_destino , ";
        $sql .= "   dias_cotizados , ";
        $sql .= "   ingreso_base_cotizacion ";
        $sql .= ") ";
        $sql .= "VALUES( ";
        $sql .= "    ".$periodos['indice'].",";
        $sql .= "    ".intval(substr($line,2,5)).",";
        $sql .= "   '".$afiliado_id."',";
        $sql .= "   '".$afiliado_tipo_id."',";
        $sql .= "   '".trim(substr($line,25,1))."', ";
        $sql .= "   '".trim(substr($line,27,1))."', ";
        $sql .= "   '".$sw_cotiza_pension."', ";
        $sql .= "   '".$sw_residencia_exterior."', ";
        $sql .= "   '".trim(substr($line,31,1))."', ";
        $sql .= "   '".trim(substr($line,33,3))."', ";
        $sql .= "   '".strtoupper(trim(str_replace("'","''",substr($line,36,20))))."', ";
        $sql .= "   '".strtoupper(trim(str_replace("'","''",substr($line,56,30))))."', ";
        $sql .= "   '".strtoupper(trim(str_replace("'","''",substr($line,86,20))))."', ";
        $sql .= "   '".strtoupper(trim(str_replace("'","''",substr($line,106,30))))."', ";
        $sql .= "   '".$nvd[0]."', ";
        $sql .= "   '".$nvd[1]."', ";
        $sql .= "   '".$nvd[2]."', ";
        $sql .= "   '".$nvd[3]."', ";
        $sql .= "   '".$nvd[4]."', ";
        $sql .= "   '".$nvd[5]."', ";
        $sql .= "   '".$nvd[6]."', ";
        $sql .= "   '".$nvd[7]."', ";
        $sql .= "   '".$nvd[8]."', ";
        $sql .= "   '".$nvd[9]."', ";
        $sql .= "   '".$nvd[10]."', ";
        $sql .= "   '".$nvd[11]."', ";
        $sql .= "   '".$nvd[12]."', ";
        $sql .= "   '".$nvd[13]."', ";
        $sql .= "   '".$nvd[14]."', ";
        $sql .= "    ".$incapacidad.", ";
        $sql .= "   '".trim(substr($line,165,6))."', ";
        $sql .= "   '".trim(substr($line,171,6))."', ";
        $sql .= "    ".intval(substr($line,185,2)).", ";
        $sql .= "    ".$ingreso." ";
        $sql .= "); ";
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      return true;
    }
    /**
    * Funcion donde se sube los archivos de pila de pension
    *
    * @return boolean
    */
    function SubirRegistrosPension() 
    {
      $ruta_archivo = "cache/tmp_".UserGetUID();
      mkdir($ruta_archivo);
      
      if (is_uploaded_file($_FILES['archivo_encabezado']['tmp_name']) && is_uploaded_file($_FILES['archivo_novedades']['tmp_name']))
      {
        $arch_encabezado = $_FILES ['archivo_encabezado']['name'];
        $arch_novedades = $_FILES ['archivo_novedades']['name'];
        
        move_uploaded_file ( $_FILES['archivo_encabezado']['tmp_name'], $ruta_archivo."/".$arch_encabezado ); 
        move_uploaded_file ( $_FILES['archivo_novedades']['tmp_name'], $ruta_archivo."/".$arch_novedades ); 
        
        $this->ConexionTransaccion();
        
        $periodos = $this->IngresarArchivoEncabezadoPension($ruta_archivo."/".$arch_encabezado);
        if(!$periodos)
        {
          $this->error = "EL ARCHIVO DE ENCABEZADO NO POSEE DATOS";
          $this->BorrarArchivos($ruta_archivo);
          return false;
        }
        
        $rst = $this->IngresarArchivoNovedadesPension($ruta_archivo."/".$arch_novedades,$periodos);
        if(!$rst) 
        {
          $this->BorrarArchivos($ruta_archivo);
          return false;
        }
        $this->dbconn->CommitTrans();
      }
      else
      {
        $this->BorrarArchivos($ruta_archivo);
        $this->error = "HA OCURRIDO UN ERROR MIENTRAS SE SUBIA EL ARCHIVO AL SERVIDOR";
        return false;
      }
      
      $this->BorrarArchivos($ruta_archivo);
      return true;
    }
    /**
    * Funcion donde se hace el registro en la BD de los datos del 
    * archivo de encabezado de pension
    *
    * @param String $archivo Nombre y ruta del archivo a leer
    *
    * @return boolean
    */
    function IngresarArchivoEncabezadoPension($archivo)
    {
      $periodos = array();
      $lines = file($archivo);
      $i=0;
      foreach ($lines as $line_num => $line)
      { 
        $sql = "SELECT NEXTVAL('eps_afiliados_pila_pension_en_eps_afiliados_pila_pension_en_seq') 	";
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $indice = $rst->fields[0]; 

        $sql  = "INSERT INTO eps_afiliados_pila_pension_encabezado (";
        $sql .= "   eps_afiliados_pila_pension_encabezado_id 	,"; 
        $sql .= "   periodo_pago,"; 
        $sql .= "   usuario_registro "; 
        $sql .= "   ) "; 
        $sql .= "VALUES ( "; 
        $sql .= "     ".$indice.", "; 
        $sql .= "    '".substr($line,311,7)."', "; 
        $sql .= "     ".UserGetUID()." ";
        $sql .= "); "; 
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $periodos[$i]['indice'] = $indice;
        $periodos[$i]['periodo'] = substr($line,227,7);
        $periodos[$i++]['numero_lineas'] = intval(substr($line,262,7));
      }
      
      if(empty($periodos[0]))  return false;
      
      return $periodos[0];
    }
    /**
    * Funcion donde se hace el registro en la BD de los datos del 
    * archivo de novedades de pension
    *
    * @param String $archivo Nombre y ruta del archivo a leer
    * @param array $periodos Vector con los datos del periodo al cual pertenece
    *              el archivo de novedades
    *
    * @return boolean
    */
    function IngresarArchivoNovedadesPension($archivo,$periodos)
    {
      $noexistentes = array();
      $lines = file($archivo);
      
      foreach ($lines as $line_num => $line)
      { 
        $afiliado_id = trim(substr($line,109,2));
        $afiliado_tipo_id = trim(substr($line,111,16));
        $ingreso = substr($line,302,9);
        
        $sw_cotiza_pension = $sw_residencia_exterior = "0";
        $nvd = array("0","0","0","0","0","0","0","0");
        
        if(substr($line,256,1) != " ") $nvd[0] = "1";
        if(substr($line,257,1) != " ") $nvd[1] = "1";
        if(substr($line,258,1) != " ") $nvd[2] = "1";
        if(substr($line,259,1) != " ") $nvd[3] = "1";
        if(substr($line,260,1) != " ") $nvd[4] = "1";
        if(substr($line,261,1) != " ") $nvd[5] = "1";
        if(substr($line,262,1) != " ") $nvd[6] = "1";
        if(substr($line,263,1) != " ") $nvd[7] = "1";
        
        if(!$ingreso) $ingreso = 0;
        
        $sql  = "INSERT INTO eps_afiliados_pila_pension  "; 
        $sql .= "(  ";
        $sql .= "   eps_afiliados_pila_pension_encabezado_id ,  ";
        $sql .= "   secuencia , ";
        $sql .= "   afiliado_id , ";
        $sql .= "   afiliado_tipo_id , ";
        $sql .= "   primer_apellido , ";
        $sql .= "   segundo_apellido , ";
        $sql .= "   primer_nombre , ";
        $sql .= "   segundo_nombre , ";          
        $sql .= "   tipo_pensionado, ";  
        $sql .= "   sw_residencia_exterior, ";  
        $sql .= "   tipo_dpto_id 	, "; 
        $sql .= "   tipo_mpio_id 	, "; 
        $sql .= "   novedad_ingreso , "; 
        $sql .= "   novedad_retiro , "; 
        $sql .= "   novedad_traslado_otra_eps, ";  
        $sql .= "   novedad_traslado_eps , "; 
        $sql .= "   novedad_traslado_otra_afp , "; 
        $sql .= "   novedad_traslado_afp , "; 
        $sql .= "   novedad_variacion_mesada , "; 
        $sql .= "   novedad_suspension , "; 
        $sql .= "   afiliado_afp_id  , "; 
        $sql .= "   afiliado_afp_id_destino, ";  
        $sql .= "   afiliado_eps_id  , "; 
        $sql .= "   afiliado_eps_id_destino, ";  
        $sql .= "   dias_cotizados_pension , "; 
        $sql .= "   dias_cotizados_salud , "; 
        $sql .= "   valor_mesada_pensional  "; 
        $sql .= ") ";
        $sql .= "VALUES( ";
        $sql .= "    ".$periodos['indice'].",";
        $sql .= "    ".intval(substr($line,2,7)).",";
        $sql .= "   '".$afiliado_id."',";
        $sql .= "   '".$afiliado_tipo_id."',";
        $sql .= "   '".strtoupper(trim(str_replace("'","''",substr($line,9,20))))."', ";
        $sql .= "   '".strtoupper(trim(str_replace("'","''",substr($line,29,30))))."', ";
        $sql .= "   '".strtoupper(trim(str_replace("'","''",substr($line,59,20))))."', ";
        $sql .= "   '".strtoupper(trim(str_replace("'","''",substr($line,69,30))))."', ";          
        $sql .= "    ".intval(substr($line,248,1)).", ";
        $sql .= "   '".trim(substr($line,249,1))."', ";
        $sql .= "   '".trim(substr($line,250,2))."', ";
        $sql .= "   '".trim(substr($line,252,3))."', ";
        $sql .= "   '".$nvd[0]."', ";
        $sql .= "   '".$nvd[1]."', ";
        $sql .= "   '".$nvd[2]."', ";
        $sql .= "   '".$nvd[3]."', ";
        $sql .= "   '".$nvd[4]."', ";
        $sql .= "   '".$nvd[5]."', ";
        $sql .= "   '".$nvd[6]."', ";
        $sql .= "   '".$nvd[7]."', ";
        $sql .= "   '".trim(substr($line,264,6))."', ";
        $sql .= "   '".trim(substr($line,270,6))."', ";
        $sql .= "   '".trim(substr($line,276,6))."', ";
        $sql .= "   '".trim(substr($line,282,6))."', ";
        $sql .= "    ".intval(substr($line,294,2)).", ";
        $sql .= "    ".intval(substr($line,298,2)).", ";
        $sql .= "    ".$ingreso." ";
        $sql .= "); ";
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      return $noexistentes;
    }
    /**
    * Funcion donde se obtiene la lista de novedades no procesadas 
    * para el archivo de pila de salud
    * 
    * @param int $pg_siguiente Numero de la pagina que se esta 
    *         visualizando actualmente
    * @param int $fecha Filtro de la fecha de registro para filtrar la busqueda
    *
    * @return array
    */
    function ObtenerListaNovedadesPILA($pg_siguiente,$fecha)
    {
      $sql  = "SELECT  PE.periodo_pago ,";
      $sql .= "        PE.sw_correccion , ";
      $sql .= "        TO_CHAR(PE.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";      
      $sql .= "        PR.afiliado_tipo_id, ";
      $sql .= "        PR.afiliado_id, ";
      $sql .= "        PR.primer_apellido ||' '|| PR.segundo_apellido AS apellidos, ";
      $sql .= "        PR.primer_nombre ||' '|| PR.segundo_nombre AS nombres, ";
      $sql .= "        PR.dias_cotizados, ";
      $sql .= "        PR.ingreso_base_cotizacion ";
      $whr  = "FROM    eps_afiliados_pila_salud_encabezado PE, ";
      $whr .= "        eps_afiliados_pila_salud PR ";
      $whr .= "WHERE   PE.eps_afiliados_pila_salud_encabezado_id = PR.eps_afiliados_pila_salud_encabezado_id ";
      $whr .= "AND     PR.sw_interfazado = '0' ";
      
      if($fecha)
      {
        $whr .= "AND      PE.fecha_registro::date = '".$this->DividirFecha($fecha)."'::date ";      
      }
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente))
        return false;
      
      $whr .= "ORDER BY fecha_registro,apellidos ";
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se sube al servidor el archivo de novedades
    *
    * @return boolean
    */
    function SubirRegistrosNovedades() 
    {
      $ruta_archivo = "cache/tmp_".UserGetUID();
      mkdir($ruta_archivo);
      
      if (is_uploaded_file($_FILES['archivo_novedades']['tmp_name']) )
      {
        $arch_novedades = $_FILES ['archivo_novedades']['name'];
        
        move_uploaded_file ( $_FILES['archivo_novedades']['tmp_name'], $ruta_archivo."/".$arch_novedades ); 
        $this->ConexionTransaccion();
        
        $periodos = $this->IngresarArchivoNovedades($ruta_archivo."/".$arch_encabezado);
        
        if($rst === false) 
        {
          $this->BorrarArchivos($ruta_archivo);
          //$this->error = $this->ErrMsg();
          return false;
        }
        $this->dbconn->CommitTrans();
      }
      else
      {
        $this->BorrarArchivos($ruta_archivo);
        $this->error = "HA OCURRIDO UN ERROR MIENTRAS SE SUBIA EL ARCHIVO AL SERVIDOR";
        return false;
      }    
      
      $this->BorrarArchivos($ruta_archivo);
      return true;
    }
    /**
    * funcion donde se hace el registro en la BD de los datos contenidos en el archivo 
    * de novedades
    *
    * @param String $archivo Nombre y ruta del archivo
    *
    * @return boolean
    */
    function IngresarArchivoNovedades($archivo)
    {
      $sql = "";
      $lines = file($archivo);
      
      foreach ($lines as $line_num => $line)
      {
        $datos = explode(",",$line);
        
        $sql .= "INSERT INTO eps_novedades_ingresos  "; 
        $sql .= "(  ";
        $sql .= "   codigo_sgss_entidad ,";	
        $sql .= "   afiliado_tipo_id ,";
        $sql .= "   afiliado_id ,";
        $sql .= "   primer_apellido ,";
        $sql .= "   segundo_apellido ,";	
        $sql .= "   primer_nombre ,";
        $sql .= "   segundo_nombre ,";
        $sql .= "   fecha_nacimiento ,";
        $sql .= "   codigo_novedad ,";
        $sql .= "   fecha_inicio_novedad ,";
        $sql .= "   nuevo_valor_1 ,	";
        $sql .= "   nuevo_valor_2 ,	";
        $sql .= "   nuevo_valor_3 ,	";
        $sql .= "   nuevo_valor_4 ,	";
        $sql .= "   nuevo_valor_5 ,	";
        $sql .= "   nuevo_valor_6 ,	";
        $sql .= "   nuevo_valor_7 , ";
        $sql .= "   usuario_registro  ";
        $sql .= ") ";
        $sql .= "VALUES( ";
        $sql .= "   '".$datos[0]."',";
        $sql .= "   '".$datos[1]."',";
        $sql .= "   '".$datos[2]."',";
        $sql .= "   '".strtoupper($datos[3])."',";
        $sql .= "   '".strtoupper($datos[4])."',";
        $sql .= "   '".strtoupper($datos[5])."',";
        $sql .= "   '".strtoupper($datos[6])."',";
        $sql .= "   '".$this->DividirFecha($datos[7])."', ";
        $sql .= "   '".$datos[8]."', ";
        $sql .= "   '".$this->DividirFecha($datos[9])."', ";
        $sql .= "   '".trim($datos[10])."', ";
        $sql .= "   '".trim($datos[11])."', ";
        $sql .= "   '".trim($datos[12])."', ";
        $sql .= "   '".trim($datos[13])."', ";
        $sql .= "   '".trim($datos[14])."', ";
        $sql .= "   '".trim($datos[15])."', ";
        $sql .= "   '".trim($datos[16])."', ";
        $sql .= "     ".UserGetUID()." ";
        $sql .= "); ";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      return true;
    }
    /**
    * Funcion donde se obtiene la lista de novedades no procesadas 
    * para el archivo de novedades
    * 
    * @param int $pg_siguiente Numero de la pagina que se esta 
    *         visualizando actualmente
    * @param int $fecha Filtro de la fecha de registro para filtrar la busqueda
    *
    * @return array
    */
    function ObtenerListaNovedades($pg_siguiente,$fecha)
    {
      $sql  = "SELECT  EI.eps_novedad_ingreso_id,";
      $sql .= "	       EI.afiliado_tipo_id,";
      $sql .= "	       EI.afiliado_id,";
      $sql .= "	       EI.primer_apellido||' '||EI.segundo_apellido AS apellidos,";
      $sql .= "	       EI.primer_nombre||' '||EI.segundo_nombre AS nombres,";
      $sql .= "	     	 EI.codigo_novedad,";
      $sql .= "	       SU.nombre 	,";
      $sql .= "	     	 TO_CHAR(EI.fecha_inicio_novedad,'DD/MM/YYYY') AS fecha_novedad ,";
      $sql .= "	       TO_CHAR(EI.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
      $whr  = "FROM    eps_novedades_ingresos EI, ";
      $whr .= "        system_usuarios SU ";
      $whr .= "WHERE   EI.usuario_registro = SU.usuario_id ";
      $whr .= "AND     EI.sw_interfazado = '0' ";
      if($fecha)
      {
        $whr .= "AND      EI.fecha_registro::date = '".$this->DividirFecha($fecha)."' ";      
      }
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente))
        return false;
      
      $whr .= "ORDER BY apellidos ";
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se obtienen el listado de cambios en las fechas de convenio de una persona
    *
    * @param array $datos Vector con los filtros que aplica el usuario a la busquedad
    * @param int $pg_siguiente Numero de la pagina que se esta 
    *         visualizando actualmente
    * @return array
    */
    function ObtenerHistorialFechasConvenio($datos,$pg_siguiente)
    {
      $sql  = "SELECT AD.afiliado_tipo_id, ";
      $sql .= "       AD.afiliado_id, ";
      $sql .= "       AD.primer_apellido||' '||AD.segundo_apellido AS apellidos, ";
      $sql .= "       AD.primer_nombre ||' '||AD.segundo_nombre AS nombres, ";
      $sql .= "       TO_CHAR(HE.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= "       TO_CHAR(HE.anterior_fecha_inicio,'DD/MM/YYYY') AS anterior_fecha_inicio, ";
      $sql .= "       TO_CHAR(HE.anterior_fecha_fin,'DD/MM/YYYY') AS anterior_fecha_fin, ";
      $sql .= "       TO_CHAR(HE.nueva_fecha_inicio,'DD/MM/YYYY') AS nueva_fecha_inicio, ";
      $sql .= "       TO_CHAR(HE.nueva_fecha_fin,'DD/MM/YYYY') AS nueva_fecha_fin, ";
      $sql .= "       SU.nombre ";
      $whr  = "FROM   eps_afiliados_datos AD,";
      $whr .= "       eps_afiliados_cotizantes_convenios AF,";
      $whr .= "       eps_historico_fechas_convenio HE, ";
      $whr .= "       system_usuarios SU ";
      $whr .= "WHERE  AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $whr .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $whr .= "AND    AF.afiliado_tipo_id = HE.afiliado_tipo_id ";
      $whr .= "AND    AF.afiliado_id = HE.afiliado_id ";
      $whr .= "AND    AF.eps_afiliacion_id = HE.eps_afiliacion_id ";
      $whr .= "AND    HE.usuario_registro = SU.usuario_id ";
      
      if($datos['fecha_registro'])
        $whr .= "AND    HE.fecha_registro::date = '".$this->DividirFecha($datos['fecha_registro'])."' ";
      if($datos['buscador']['afiliado_tipo_id'] != "-1" && $datos['buscador']['afiliado_tipo_id'])
      {
        $whr .= "AND    AD.afiliado_tipo_id = '".$datos['buscador']['afiliado_tipo_id']."' ";
        $whr .= "AND    AD.afiliado_id = '".$datos['buscador']['afiliado_id']."' ";
      }

      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente))
        return false;
      
      $whr .= "ORDER BY apellidos,nombres,fecha_registro ";
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";

      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }   
    /**
    * Funcion 
    */
    function ObtenerPeriodos()
    {
      $sql  = "SELECT periodo_arch_ministerio_id,";
      $sql .= "       descripcion,";
      $sql .= "       intervalo_inicial ,";  
      $sql .= "       intervalo_final ";
      $sql .= "FROM   empresas ";
      $sql .= "WHERE  sw_activo = '1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->field[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }    
    /**
    * Funcion 
    */
    function ObtenerCodigoSGSS($empresa)
    {
      $sql  = "SELECT codigo_sgsss, ";
      $sql .= "       tipo_id_tercero, ";
      $sql .= "       id, ";
      $sql .= "       esp_tipo_aportante_id, ";
      $sql .= "       esp_sector_aportante_id, ";
      $sql .= "       digito_verificacion, ";
      $sql .= "       ciiu_r3_clase, ";
      $sql .= "       razon_social ";
      $sql .= "FROM   empresas ";
      $sql .= "WHERE  empresa_id = '".$empresa."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se obtiene el listado de novedades
    *
    * @param date $fechai Fecha de inicio
    * @param date $fechaf Fecha de finalizacion
    * @param array $plan Arreglo de datos de los planes seleccionados
    *
    * @return object
    */
    function ObtenerListadoNovedades($fechai,$fechaf,$plan)
    {
      $sql = "SELECT SETVAL('arch_novedades',1)";
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $sql  = "SELECT DISTINCT NEXTVAL('arch_novedades')-1 AS consecutivo ,";	
      $sql .= "       ER.codigo_sgss_entidad ,";	
      $sql .= "       ER.afiliado_tipo_id ,";
      $sql .= "       ER.afiliado_id ,";
      $sql .= "       ER.primer_apellido ,";
      $sql .= "       ER.segundo_apellido ,";	
      $sql .= "       ER.primer_nombre ,";
      $sql .= "       ER.segundo_nombre ,";
      $sql .= "       TO_CHAR(ER.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento ,";
      $sql .= "       ER.codigo_novedad ,";
      $sql .= "       TO_CHAR(ER.fecha_inicio_novedad,'DD/MM/YYYY') AS fecha_inicio_novedad ,";
      $sql .= "       ER.nuevo_valor_1 ,	";
      $sql .= "       ER.nuevo_valor_2 ,	";
      $sql .= "       ER.nuevo_valor_3 ,	";
      $sql .= "       ER.nuevo_valor_4 ,	";
      $sql .= "       ER.nuevo_valor_5 ,	";
      $sql .= "       ER.nuevo_valor_6 ,	";
      $sql .= "       ER.nuevo_valor_7  ";
      $sql .= "FROM   eps_novedades_registros ER, ";
      $sql .= "       eps_afiliados EA ";
      $sql .= "WHERE  ER.fecha_registro::date <= '".$this->DividirFecha($fechaf)."' ";
      $sql .= "AND    ER.fecha_registro::date >= '".$this->DividirFecha($fechai)."' ";
      $sql .= "AND    ER.afiliado_id = EA.afiliado_id ";
      $sql .= "AND    ER.afiliado_tipo_id = EA.afiliado_tipo_id ";
      if($plan)
      {
        $query = "";
        foreach($plan as $k)
          ($query == "" )? $query .= $k: $query .= ",".$k;
          
        $sql .= "           AND    EA.plan_atencion IN (".$query.") ";
      }
      $sql;
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      return $rst;
    }
  }
?>