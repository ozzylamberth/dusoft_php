<?php
  /**
  * $Id: RipsEPS.class.php,v 1.4 2009/02/16 21:15:09 hugo Exp $
  *
  * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
  * @package IPSOFT-SIIS-CLASSES
  * 
  * Clase para generar los rips de EPS's
  */
  /**
  * Clase padre para generar los rips de EPS's
  * para cada archivo de rips se debe
  * crear una clase que extienda de esta clase
  *
  * @author    Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  * @version   $Revision: 1.4 $
  * @package   IPSOFT-SIIS-CLASSES
  */
  class RipsEPS
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
    * Archivos que forman parte del envio
    *
    * @var array
    * @acces public
    */
    var $archivoControl = array();    
    /**
    * Constructor de la clase
    */
    function RipsEPS()
    {
      $this->e = " ";
      $this->c = "0";
      $this->DIR_DEFAULT  = "RIPS_EPS";
      $this->EOL = "\n";
      return true;	
    }
		/*
    * Metodo para validar si el usuario actual tiene permisos de generar RIPS de EPS
    *
    * @return boolean
    * @access private
    */
    function ValidarPermisos()
    {
      $usuario_id = UserGetUID();
		
      if(empty($usuario_id))
      {
        $this->error = "CLASS RipsEPS - ValidarPermisos - ERROR 01";
        $this->mensajeDeError = "Usuario no logueado.";	
        return false;
      }
		
	    list($dbconn) = GetDBconn();

	    $query = "SELECT count(*) FROM userpermisos_eps_rips WHERE usuario_id = $usuario_id AND sw_activo = '1'; ";

	    $result = $dbconn->Execute($query);

      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "CLASS RipsEPS - ValidarPermisos - ERROR 02";
        $this->mensajeDeError = $dbconn->ErrorMsg();
        return false;
      }
		
      list($cantidad) = $result->FetchRow();
			return $cantidad;
		}
    /**
    *
    */
    function ObtenerEstados_cxp()
    {
		list($dbconn) = GetDBconn();
		
		$sql  = "SELECT cxp_estado, cxp_estado_descripcion FROM cxp_estados";
		
		$result = $dbconn->Execute($sql);
        
		if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS RipsEPS - ObtenerTiposIdentificacion - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

		$datos = array();

		while (!$result->EOF)
		{
		  $datos[$result->fields[0]] = $result->GetRowAssoc($ToUpper = false);
		  $result->MoveNext();
		}
		$result->Close();

		return $datos;
    }	
	
	
	function GenerarEnvio($request)
	{
		$usuario_id = UserGetUID();
		
		list($dbconn) = GetDBconn();
		
		$sql  = "SELECT nextval('rips_eps_control_rips_eps_id_seq');";

		$result = $dbconn->Execute($sql);
        
		if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS RipsEPS - GenerarEnvio - ERROR 01";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
		list($envio_id) = $result->FetchRow();
		$result->Close();
		
		$sql = "
				INSERT INTO public.rips_eps_control 
				(
					rips_eps_id, 
					fecha_inicial, 
					fecha_final, 
					usuario_id, 
					fecha_registro, 
					cxp_estados, 
					proveedor_id, 
					resultado
				) 
				VALUES 
				(
					$envio_id, 
					'2008-01-01', 
					'2008-01-01', 
					'$usuario_id', 
					now(), 
					NULL, 
					NULL, 
					NULL
				);	
		";
		
		$dbconn->Execute($sql);
        
		if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS RipsEPS - GenerarEnvio - ERROR 02";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
		
		return $envio_id;			
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
    *
    * @return boolean
    */
    function GenerarArchivoAC($id,$datos,$codigo_sgsss)
    {
      if($this->handle)
      {
        $this->CerrarArchivo();
      }
      
      $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

      if(!is_dir($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAC - E1";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
        return false;
      }

      if(!is_writable($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAC - E2";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }

      $dir_id = $directorio . "/" . $id;
		
      if(!is_dir($dir_id))
      {
        if(!mkdir($dir_id, 0777))
        {
          $this->error = "RIPS EPS - GenerarArchivoAC - E3";
          $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_id";
          return false;
        }
      }

      if(!is_writable($dir_id))
      {
        $this->error = "RIPS EPS - GenerarArchivoAC - E4";
        $this->mensajeDeError = "EL DIRECTORIO $dir_id SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }		
      
      $name = "AC".$this->FormatearText2BATCH($id,6,$this->c,$tipo_relleno=STR_PAD_LEFT);
      $nombre_archivo = $dir_id . "/".$name.".TXT";

      $archivo = fopen($nombre_archivo,'w');

      if(!$archivo)
      {
        $this->error = "RIPS EPS - GenerarArchivoAC - E5";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $nombre_archivo;
        return false;
      }
      
      $i=0;
      foreach($datos as $k=>$v)
      {
  			$linea  = $codigo_sgsss;
  			$linea .= ",".$v['codigo_sgss'];
  			$linea .= ",".$v['numero_factura'];
  			$linea .= ",".$v['usuario_tipo_identificacion'];
  			$linea .= ",".$v['usuario_identificacion'];
  			$linea .= ",".$v['fecha_consulta'];
  			$linea .= ",".$v['codigo_consulta'];
  			$linea .= ",".$v['finalidad_consulta'];
  			$linea .= ",".$v['causa_externa'];
  			$linea .= ",".$v['codigo_diagnostico_principal'];
  			$linea .= ",".$v['codigo_diagnostico_relacionado_1'];
  			$linea .= ",".$v['codigo_diagnostico_relacionado_2'];
  			$linea .= ",".$v['codigo_diagnostico_relacionado_3'];
  			$linea .= ",".$v['tipo_disgnostico_principal'];
  			$linea .= ",".$v['valor_consulta'];
  			$linea .= ",".$v['valor_cuota_moderadora'];
  			$linea .= ",".$v['valor_neto'];
  			$linea .= $this->EOL;

	      if(!fwrite($archivo,$linea))
	      {
          $this->error = "RIPS EPS - GenerarArchivoAC - E6";
          $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [$archivo].";
          return false;
	      }
        $i++;
      }

      $this->archivoControl[$name]['archivo'] = $name;
      $this->archivoControl[$name]['codigo_sgsss'] = $codigo_sgsss;
      $this->archivoControl[$name]['cantidad'] = $i;
      
      if(!fclose($archivo))  return false;
      
      return true;	
    }	
    /**
    *
    */
    function GenerarArchivoAP($id,$datos,$codigo_sgsss)
    {
      if($this->handle)
      {
        $this->CerrarArchivo();
      }
      
      $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

      if(!is_dir($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAP - E1";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
        return false;
      }

      if(!is_writable($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAP - E2";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }
      
      $dir_id = $directorio . "/" . $id;
		
      if(!is_dir($dir_id))
      {
        if(!mkdir($dir_id, 0777))
        {
          $this->error = "RIPS EPS - GenerarArchivoAP - E3";
          $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_id";
          return false;
        }
      }

      if(!is_writable($dir_id))
      {
        $this->error = "RIPS EPS - GenerarArchivoAP - E4";
        $this->mensajeDeError = "EL DIRECTORIO $dir_id SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }		
      
      $name = "AP".$this->FormatearText2BATCH($id,6,$this->c,$tipo_relleno=STR_PAD_LEFT);
      $nombre_archivo = $dir_id . "/".$name.".TXT";

      $archivo = fopen($nombre_archivo,'w');

      if(!$archivo)
      {
        $this->error = "RIPS EPS - GenerarArchivoAP - E5";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $nombre_archivo;
        return false;
      }
		
      $i = 0;
      foreach($datos as $k=>$v)
      {
  			$linea  = $codigo_sgsss;
  			$linea .= ",".$v['codigo_sgss'];
  			$linea .= ",".$v['numero_factura'];
  			$linea .= ",".$v['usuario_tipo_identificacion'];
  			$linea .= ",".$v['usuario_identificacion'];
  			$linea .= ",".$v['fecha_procedimiento'];
  			$linea .= ",".$v['codigo_procedimiento'];
  			$linea .= ",".$v['ambito_procedimiento'];
  			$linea .= ",".$v['finalidad_procedimiento'];
  			$linea .= ",".$v['profesional_atiende'];
  			$linea .= ",".$v['codigo_diagnostico_principal'];
  			$linea .= ",".$v['codigo_diagnostico_relacionado'];
  			$linea .= ",".$v['codigo_complicacion'];
  			$linea .= ",".$v['valor_procedimiento'];
  			$linea .= $this->EOL;
			
	      if(!fwrite($archivo,$linea))
	      {
          $this->error = "RIPS EPS - GenerarArchivoAP - E6";
          $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [$archivo].";
          return false;
	      }
        $i++;
      }
		  
      $this->archivoControl[$name]['archivo'] = $name;
      $this->archivoControl[$name]['codigo_sgsss'] = $codigo_sgsss;
      $this->archivoControl[$name]['cantidad'] = $i;
      
      if(!fclose($archivo))  return false;

      return true;	
    }	
    /**
    *
    */
    function GenerarArchivoAU($id,$datos,$codigo_sgsss)
    {
      if($this->handle)
      {
        $this->CerrarArchivo();
      }
      
      $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

      if(!is_dir($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E1";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
        return false;
      }

      if(!is_writable($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E2";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }

      $dir_id = $directorio . "/" . $id;
		
      if(!is_dir($dir_id))
      {
        if(!mkdir($dir_id, 0777))
        {
          $this->error = "RIPS EPS - GenerarArchivoAU - E3";
          $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_id";
          return false;
        }
      }

      if(!is_writable($dir_id))
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E4";
        $this->mensajeDeError = "EL DIRECTORIO $dir_id SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }		
      
      $name = "AU".$this->FormatearText2BATCH($id,6,$this->c,$tipo_relleno=STR_PAD_LEFT);
      $nombre_archivo = $dir_id . "/".$name.".TXT";

      $archivo = fopen($nombre_archivo,'w');

      if(!$archivo)
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E5";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $nombre_archivo;
        return false;
      }
		
      $i =0 ;
      foreach($datos as $k=>$v)
      {
  			$linea  = $codigo_sgsss;
  			$linea .= ",".$v['codigo_sgss'];
  			$linea .= ",".$v['numero_factura'];
  			$linea .= ",".$v['usuario_tipo_identificacion'];
  			$linea .= ",".$v['usuario_identificacion'];
  			$linea .= ",".$v['fecha_ingreso'];
  			$linea .= ",".$v['causa_externa'];
  			$linea .= ",".$v['codigo_diagnostico_salida'];
  			$linea .= ",".$v['codigo_diagnostico_salida_relacionado_1'];
  			$linea .= ",".$v['codigo_diagnostico_salida_relacionado_2'];
  			$linea .= ",".$v['codigo_diagnostico_salida_relacionado_3'];
  			$linea .= ",".$v['codigo_destino_salida'];
  			$linea .= ",".$v['estado_salida'];
  			$linea .= ",".$v['codigo_causa_muerte'];		
  			$linea .= ",".$v['fecha_salida_observacion'];
  			$linea .= $this->EOL;
			
	      if(!fwrite($archivo,$linea))
	      {
	        $this->error = "RIPS EPS - GenerarArchivoAU - E6";
	        $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [$archivo].";
	        return false;
	      }
        $i++;
      }
		
      $this->archivoControl[$name]['archivo'] = $name;
      $this->archivoControl[$name]['codigo_sgsss'] = $codigo_sgsss;
      $this->archivoControl[$name]['cantidad'] = $i;
      
      if(!fclose($archivo))  return false;
     
      return true;	
    }	
    /**
    *
    */
    function GenerarArchivoUS($id,$datos,$codigo_sgsss)
    {
      if($this->handle)
      {
        $this->CerrarArchivo();
      }
      
      $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

      if(!is_dir($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E1";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
        return false;
      }

      if(!is_writable($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E2";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }

      $dir_id = $directorio . "/" . $id;
		
      if(!is_dir($dir_id))
      {
        if(!mkdir($dir_id, 0777))
        {
          $this->error = "RIPS EPS - GenerarArchivoAU - E3";
          $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_id";
          return false;
        }
      }

      if(!is_writable($dir_id))
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E4";
        $this->mensajeDeError = "EL DIRECTORIO $dir_id SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }		
      
      $name = "US".$this->FormatearText2BATCH($id,6,$this->c,$tipo_relleno=STR_PAD_LEFT);
      $nombre_archivo = $dir_id . "/".$name.".TXT";

      $archivo = fopen($nombre_archivo,'w');

      if(!$archivo)
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E5";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $nombre_archivo;
        return false;
      }
		
      $i =0 ;
      foreach($datos as $k=>$v)
      {
  			$linea  = $codigo_sgsss;
  			$linea .= ",".$v['usuario_tipo_identificacion'];
  			$linea .= ",".$v['usuario_identificacion'];
  			$linea .= ",".$v['tipo_usuario'];
  			$linea .= ",";
        $linea .= ",";
        $linea .= ",".$v['edad'];
  			$linea .= ",".$v['unidad_medida_edad'];
  			$linea .= ",".$v['tipo_sexo'];
  			$linea .= ",".$v['tipo_dpto_id'];
  			$linea .= ",".$v['tipo_mpio_id'];
  			$linea .= ",".$v['zona_residencia'];
  			$linea .= $this->EOL;
			
	      if(!fwrite($archivo,$linea))
	      {
	        $this->error = "RIPS EPS - GenerarArchivoAU - E6";
	        $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [$archivo].";
	        return false;
	      }
        $i++;
      }
		
      $this->archivoControl[$name]['archivo'] = $name;
      $this->archivoControl[$name]['codigo_sgsss'] = $codigo_sgsss;
      $this->archivoControl[$name]['cantidad'] = $i;
      
      if(!fclose($archivo))  return false;
     
      return true;	
    }	    
    /**
    *
    */
    function GenerarArchivoAD($id,$datos,$codigo_sgsss)
    {
      if($this->handle)
      {
        $this->CerrarArchivo();
      }
      
      $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

      if(!is_dir($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAD - E1";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
        return false;
      }

      if(!is_writable($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAD - E2";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }

      $dir_id = $directorio . "/" . $id;
		
      if(!is_dir($dir_id))
      {
        if(!mkdir($dir_id, 0777))
        {
          $this->error = "RIPS EPS - GenerarArchivoAD - E3";
          $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_id";
          return false;
        }
      }

      if(!is_writable($dir_id))
      {
        $this->error = "RIPS EPS - GenerarArchivoAD - E4";
        $this->mensajeDeError = "EL DIRECTORIO $dir_id SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }		
      
      $name = "AD".$this->FormatearText2BATCH($id,6,$this->c,$tipo_relleno=STR_PAD_LEFT);
      $nombre_archivo = $dir_id . "/".$name.".TXT";

      $archivo = fopen($nombre_archivo,'w');

      if(!$archivo)
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E5";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $nombre_archivo;
        return false;
      }
		
      $linea  = $codigo_sgsss;
      $linea .= ",".date('Y');
      $linea .= ",".date('m');
      $linea .= ",".$datos['01']['valor'];
      $linea .= ",".$datos['02']['valor'];
      $linea .= ",".$datos['04']['valor'];
      $linea .= ",".$datos['03']['valor'];
      $linea .= ",".$datos['05']['valor'];
      $linea .= ",".$datos['06']['valor'];
      $linea .= ",".$datos['07']['valor'];
      $linea .= ",".$datos['08']['valor'];
      $linea .= ",".$datos['09']['valor'];
      $linea .= ",".$datos['10']['valor'];
      $linea .= ",".$datos['11']['valor'];
      $linea .= ",".$datos['12']['valor'];
      $linea .= ",".$datos['13']['valor'];
      $linea .= ",".$datos['14']['valor'];
      $linea .= $this->EOL;
			
      if(!fwrite($archivo,$linea))
      {
        $this->error = "RIPS EPS - GenerarArchivoAD - E6";
        $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [$archivo].";
        return false;
      }
		
      $this->archivoControl[$name]['archivo'] = $name;
      $this->archivoControl[$name]['codigo_sgsss'] = $codigo_sgsss;
      $this->archivoControl[$name]['cantidad'] = 1;
      
      if(!fclose($archivo))  return false;
     
      return true;	
    }	
    /**
    *
    */
    function GenerarArchivoCT($id)
    {
      if($this->handle)
      {
        $this->CerrarArchivo();
      }
      
      $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

      if(!is_dir($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E1";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
        return false;
      }

      if(!is_writable($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E2";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }

      $dir_id = $directorio . "/" . $id;

      if(!is_writable($dir_id))
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E4";
        $this->mensajeDeError = "EL DIRECTORIO $dir_id SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }		
      
      $name = "CT".$this->FormatearText2BATCH($id,6,$this->c,$tipo_relleno=STR_PAD_LEFT);
      $nombre_archivo = $dir_id . "/".$name.".TXT";

      $archivo = fopen($nombre_archivo,'w');

      if(!$archivo)
      {
        $this->error = "RIPS EPS - GenerarArchivoAU - E5";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $nombre_archivo;
        return false;
      }
      
      $fecha = date("d/m/Y");
      foreach($this->archivoControl as $k1 => $dtl)
      {
        $linea  = $dtl['codigo_sgsss'];
  			$linea .= ",".$fecha;
  			$linea .= ",".$dtl['archivo'];
  			$linea .= ",".$dtl['cantidad'];
  			$linea .= $this->EOL;
			
	      if(!fwrite($archivo,$linea))
	      {
	        $this->error = "RIPS EPS - GenerarArchivoAU - E6";
	        $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [$archivo].";
	        return false;
	      }
      }
		
      if(!fclose($archivo))  return false;
      
      return true;	
    }	
    /**
    *
    */
    function GenerarArchivoAH($id,$datos,$codigo_sgsss)
    {
      if($this->handle)
      {
        $this->CerrarArchivo();
      }

      $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

      if(!is_dir($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAH - E1";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
        return false;
      }

      if(!is_writable($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAH - E2";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }

      $dir_id = $directorio . "/" . $id;
		
      if(!is_dir($dir_id))
      {
        if(!mkdir($dir_id, 0777))
        {
          $this->error = "RIPS EPS - GenerarArchivoAH - E3";
          $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_id";
          return false;
        }
      }

      if(!is_writable($dir_id))
      {
        $this->error = "RIPS EPS - GenerarArchivoAH - E4";
        $this->mensajeDeError = "EL DIRECTORIO $dir_id SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }		

      $name = "AH".$this->FormatearText2BATCH($id,6,$this->c,$tipo_relleno=STR_PAD_LEFT);
      $nombre_archivo = $dir_id . "/".$name.".TXT";

      $archivo = fopen($nombre_archivo,'w');

      if(!$archivo)
      {
        $this->error = "RIPS EPS - GenerarArchivoAH - E5";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $nombre_archivo;
        return false;
      }
      
      $i = 0;
      foreach($datos as $k=>$v)
      {
  			$linea  = $codigo_sgsss;
  			$linea .= ",".$v['codigo_sgss'];
  			$linea .= ",".$v['numero_factura'];
  			$linea .= ",".$v['usuario_tipo_identificacion'];
  			$linea .= ",".$v['usuario_identificacion'];
  			$linea .= ",".$v['via_ingreso'];
  			$linea .= ",".$v['fecha_ingreso'];
  			$linea .= ",".$v['hora_ingreso'];
  			$linea .= ",".$v['causa_externa'];			
  			$linea .= ",".$v['codigo_diagnostico_ingreso'];
  			$linea .= ",".$v['codigo_diagnostico_egreso'];
  			$linea .= ",".$v['codigo_diagnostico_egreso_relacionado_1'];
  			$linea .= ",".$v['codigo_diagnostico_egreso_relacionado_2'];
  			$linea .= ",".$v['codigo_diagnostico_egreso_relacionado_3'];
  			$linea .= ",".$v['codigo_diagnostico_complicacion'];
  			$linea .= ",".$v['estado_salida'];
  			$linea .= ",".$v['codigo_causa_muerte'];		
  			$linea .= ",".$v['fecha_egreso'];
  			$linea .= ",".$v['hora_egreso'];
  			$linea .= $this->EOL;
					
	      if(!fwrite($archivo,$linea))
	      {
	        $this->error = "RIPS EPS - GenerarArchivoAH - E6";
	        $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [$archivo].";
	        return false;
	      }
        $i++;
      }
		
      $this->archivoControl[$name]['archivo'] = $name;
      $this->archivoControl[$name]['codigo_sgsss'] = $codigo_sgsss;
      $this->archivoControl[$name]['cantidad'] = $i;
      
      if(!fclose($archivo))  return false;
      
      return true;	
    }	
    /**
    *
    */
    function GenerarArchivoAN($id,$datos,$codigo_sgsss)
    {
      if($this->handle)
      {
        $this->CerrarArchivo();
      }
      $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

      if(!is_dir($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAN - E1";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
        return false;
      }

      if(!is_writable($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAN - E2";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }

      $dir_id = $directorio . "/" . $id;
		
      if(!is_dir($dir_id))
      {
        if(!mkdir($dir_id, 0777))
        {
          $this->error = "RIPS EPS - GenerarArchivoAN - E3";
          $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_id";
          return false;
        }
      }

      if(!is_writable($dir_id))
      {
        $this->error = "RIPS EPS - GenerarArchivoAN - E4";
        $this->mensajeDeError = "EL DIRECTORIO $dir_id SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }		
      
      $name = "AN".$this->FormatearText2BATCH($id,6,$this->c,$tipo_relleno=STR_PAD_LEFT);
      $nombre_archivo = $dir_id . "/".$name.".TXT";

      $archivo = fopen($nombre_archivo,'w');

      if(!$archivo)
      {
        $this->error = "RIPS EPS - GenerarArchivoAN - E5";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $nombre_archivo;
        return false;
      }
      
      $i =0;
      foreach($datos as $k=>$v)
      {
  			$linea  = $codigo_sgsss;
  			$linea .= ",".$v['codigo_sgss'];
  			$linea .= ",".$v['numero_factura'];
  			$linea .= ",".$v['usuario_tipo_identificacion'];
  			$linea .= ",".$v['usuario_identificacion'];
  			$linea .= ",".$v['fecha_nacimiento'];
  			$linea .= ",".$v['hora_nacimiento'];
  			$linea .= ",".$v['edad_gestacional'];
  			$linea .= ",".$v['control_prenatal'];
  			$linea .= ",".$v['tipo_sexo'];
  			$linea .= ",".$v['peso'];
  			$linea .= ",".$v['codigo_diagnostico'];
  			$linea .= ",".$v['codigo_causa_muerte'];		
  			$linea .= ",".$v['fecha_muerte'];
  			$linea .= ",".$v['hora_muerte'];
  			$linea .= $this->EOL;
	 		
        if(!fwrite($archivo,$linea))
        {
          $this->error = "RIPS EPS - GenerarArchivoAN - E6";
          $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [$archivo].";
          return false;
        }
        $i++;
      }
		
      $this->archivoControl[$name]['archivo'] = $name;
      $this->archivoControl[$name]['codigo_sgsss'] = $codigo_sgsss;
      $this->archivoControl[$name]['cantidad'] = $i;
      
      if(!fclose($archivo))  return false;

      return true;	
    }	
    /**
    *
    */
    function GenerarArchivoAM($id,$datos,$codigo_sgsss)
    {
      if($this->handle)
      {
        $this->CerrarArchivo();
      }

      $directorio = GetVarConfigAplication('DIR_SIIS').$this->DIR_DEFAULT;

      if(!is_dir($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAM - E1";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO EXISTE.";
        return false;
      }

      if(!is_writable($directorio))
      {
        $this->error = "RIPS EPS - GenerarArchivoAM - E2";
        $this->mensajeDeError = "EL DIRECTORIO $directorio NO TIENE PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }

      $dir_id = $directorio . "/" . $id;
		
      if(!is_dir($dir_id))
      {
        if(!mkdir($dir_id, 0777))
        {
          $this->error = "RIPS EPS - GenerarArchivoAM - E3";
          $this->mensajeDeError = "NO SE PUDO CREAR EL DIRECTORIO $dir_id";
          return false;
        }
      }

      if(!is_writable($dir_id))
      {
        $this->error = "RIPS EPS - GenerarArchivoAM - E4";
        $this->mensajeDeError = "EL DIRECTORIO $dir_id SE CREO SIN PERMISOS DE ESCRITURA PARA EL USUARIO DEL SERVIDOR DE LA APLICACION.";
        return false;
      }		

      $name = "AM".$this->FormatearText2BATCH($id,6,$this->c,$tipo_relleno=STR_PAD_LEFT);
      $nombre_archivo = $dir_id . "/".$name.".TXT";

      $archivo = fopen($nombre_archivo,'w');

      if(!$archivo)
      {
        $this->error = "RIPS EPS - GenerarArchivoAM - E5";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO fopen() no pudo abrir : ' . $nombre_archivo;
        return false;
      }
      
      $i = 0;
      foreach($datos as $k=>$v)
      {
        $linea  = $codigo_sgsss;
  			$linea .= ",".$v['codigo_sgss'];
  			$linea .= ",".$v['numero_factura'];
  			$linea .= ",".$v['usuario_tipo_identificacion'];
  			$linea .= ",".$v['usuario_identificacion'];
  			$linea .= ",".$v['edad'];
  			$linea .= ",".$v['unidad_medida_edad'];
  			$linea .= ",".$v['nombre_generico_medicamento'];
  			$linea .= ",".$v['tipo_medicamento'];
  			$linea .= ",".substr($v['forma_farmaceutica'],0,20);
  			$linea .= ",".$v['concentracion_medicamento'];
  			$linea .= ",".$v['unidad_medida'];
  			$linea .= ",".$v['numero_unidades'];
  			$linea .= ",".$v['valor_unitario'];		
  			$linea .= ",".$v['valor_total'];
  			$linea .= $this->EOL;
			
	      if(!fwrite($archivo,$linea))
	      {
	        $this->error = "RIPS EPS - GenerarArchivoAM - E6";
	        $this->mensajeDeError = "NO SE PUDO ESCRIBIR EN EL ARCHIVO [$archivo].";
	        return false;
	      }
        $i++;
      }
		  
      $this->archivoControl[$name]['archivo'] = $name;
      $this->archivoControl[$name]['codigo_sgsss'] = $codigo_sgsss;
      $this->archivoControl[$name]['cantidad'] = $i;
      
      if(!fclose($archivo)) return false;
      
      return true;	
    }
  }
?>