<?php
/**
 * @version $Id: Security.class.php,v 1.7 2007/11/20 14:23:30 hugo Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Componente de bajo nivel para los privilegios de la base de datos
 * de la aplicacion SIIS
 */
/**
 * Clase responsable de revisar, modificar y crear los usuarios y los
 * privilegios de estos en la base de datos de la aplicación
 *
 * @author    Hugo F  Manrique
 * @version   $Revision: 1.7 $
 * @package   IPSOFT-SIIS-SECURITY
 */
  class Security
  {
    /**
    * @var object
    */
    var $dbconn;
    /**
    * @var array
    */
    var $esquemas;
    /**
    * @var array
    */
    var $grupos;
    /**
    * @var String
    */
    var $dbName;
    /**
    * Constructor de la clase
    *
    * @param string dbType
    * @param string UserDB
    * @param string Passs
    * @param string dbHost
    * @param string dbName
    * @param string UserApp
    * @param string UserPass
    * @access public
    */
    function Security($dbType,$UserDB,$Pass,$dbHost,$dbName,$UserApp,$UserPass)
    {
      $this->dbName = $dbName;
      if(!$this->Conexion($dbType,$UserDB,$Pass,$dbHost,$dbName))
        return false;
      
      if(!$this->VerificarUsuario($UserDB))
        return false;
    }
    /**
    * Hace la ejecion del security, una vez se ha verificado los prmisos
    *
    * @return boolean
    */
    function EjecutarSecurity()
    {
      if(!$this->CargarConfiguracion())
      {
        $this->ImprimirError();
        return false;
      }
      
      $this->dbconn->StartTrans();
      
      if(!$this->BaseDatos($this->dbName))
      {
        $this->ImprimirError();
        return false;
      }
      /*
      if(!$this->Esquema())
      {
        $this->ImprimirError();
        return false;
      }*/
      
      if(!$this->Grupos())
      {
        $this->ImprimirError();
        return false;
      }
      
      if(!$this->Tablas())
      {
        $this->ImprimirError();
        return false;
      }
      
      if(!$this->Funciones())
      {
        $this->ImprimirError();
        return false;
      }
      
      if(!$this->Vistas())
      {
        $this->ImprimirError();
        return false;
      }
      
      if(!$this->Secuencias())
      {
        $this->ImprimirError();
        return false;
      }
      
      $this->dbconn->CompleteTrans();
      
      $this->ImprimirResumen();
      return true;
    }
    /**
    * Hace la verificacion del usuario, para determinar si el usuario 
    * puede o no hacer este proceso
    *
    * @params String $User nombre del usuario
    *
    * @return boolean
    */
    function VerificarUsuario($User)
    {
      $sql =  "SELECT usesuper FROM pg_user WHERE usename='".$User."';";
  		$rst = $this->EjecutarSql($sql);
      $SuperUsuario = array();
      if(!$rst->EOF)
      {
        $SuperUsuario = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      if($SuperUsuario['usesuper'] =='f')
  		{
  			$this->error=3;
  			$this->msjError=MsgOut("ERROR SECURITY : No se puede correr el Security, el usuario $User no es superusuario");
        return false;
  		}
      
  		return true;
    }
    /**
    * Carga en arreglos la informacion que esta contenida en los 
    * archivos de configuracion
    *
    * @return boolean
    */
    function CargarConfiguracion()
    {
      $archivoConfiguracion = "Security.ini";
      $directoriosconfig = array();
      
      $directoriosconfig['tablas'] = "Tablas";
      $directoriosconfig['vistas'] = "Vistas";
      $directoriosconfig['funciones'] = "Funciones";
      $directoriosconfig['basedatos'] = "BaseDatos";
      $directoriosconfig['secuencias'] = "Secuencias";
      $directoriosconfig['esquemas'] = "Esquemas";
      
      $configuracion = parse_ini_file($archivoConfiguracion,true);
      $this->esquemas = $configuracion['esquemas'];
      $this->SetResumen('Ambiente',$configuracion['security']['ambiente']);
      
      unset($configuracion['esquemas']);
      unset($configuracion['security']);
      
      $this->usuarios = $configuracion;
      
      foreach($this->usuarios as $key0 => $grupo)
      {
  			foreach($directoriosconfig as $key => $directorio)
        {
          if(!empty($grupo[$key]))
          {
            if(!file_exists($directorio."/".$grupo[$key].".ini"))
            {
              $this->msjError="ERROR SECURITY :  En el archivo de configuración '".$archivoConfiguracion."' en la seccion '".$key0."' el atributo '".$key."' indica un archivo que no existe";
              return false;
            }
            $this->usuarios[$key0][$key] = parse_ini_file($directorio."/".$grupo[$key].".ini",true);
          }
        }
      }
      return true;
    }
    /**
    * Otorga los permisos de los grupos existentes en el archivo
    *
    * @return boolean
    */
  	function Grupos()
  	{
  		//if(!$this->ObtenerGruposBD()) return false;
  		
      $sql = "";
  		$grupos_modificados = "";
  		$grupos_noexistentes = "";
      
  		foreach($this->usuarios as $key => $grupo)
  		{
  			/*if(in_array($grupo['nombre'],$this->grupos))
  			{*/
  				$sql .= "ALTER ROLE ";
  				$grupos_modificados .= "'".$key."' ";
          $sql .= $key ." WITH ";
    			if($grupo['createuser'])
            $sql .= " CREATEUSER ";
          else
            $sql .= " NOCREATEUSER ";
    			
    			if($grupo['createdb'])
    				$sql .= " CREATEDB ";
    			else
            $sql .= " NOCREATEDB ";
    			          
    			if($grupo['createrole'])
    			  $sql .= " CREATEROLE; ";
    			else
            $sql .= " NOCREATEROLE; ";
  			/*}
  			else
  			{
  				$grupos_noexistentes .= "'".$grupo['nombre']."' ";
  			}*/
  		}
  		if(!$this->EjecutarSql($sql))
        return false;
  		$this->SetResumen('Grupos Invalidos:',$grupos_noexistentes);
  		$this->SetResumen('Grupos Modificados:',$grupos_modificados);
      return true;
  	}   
  	/**
    * Obtiene los grupos existentes en la bse de datos
    * 
    * @return boolean
  	*/
  	function ObtenerGruposBD()
  	{
      $sql = "SELECT groname FROM pg_group;";
  		$rst = $this->dbconn->Execute($sql);
      while(!$rst->EOF)
      {
        $this->grupos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
  		$rst->Close();
      return true;
  	}
    /**
    * Otorga los permisos de los grupos existentes en el archivo, 
    * para la base de datos
    *
    * @return boolean
    */
    function BaseDatos($dbname)
    {
      $sql = "";
      foreach($this->usuarios as $key => $grupo)
      {
        if($grupo['basedatos'])
        {
          $sql  = "REVOKE ALL ON DATABASE \"".$dbname."\" ";
          $sql .= "FROM GROUP \"".$grupo['nombre']."\"; ";
        
          $sql .= "GRANT ".$grupo['basedatos']['todos']['privilegios']." ";
          $sql .= "ON DATABASE \"".$dbname."\" TO GROUP \"".$grupo['nombre']."\"; ";
        
          if(!$this->EjecutarSql($sql))  return false;
        }
      }
      return true;
    }
    /**
    * Otorga los permisos de los grupos existentes en el archivo, 
    * para la base de datos
    *
    * @return boolean
    */
    function Esquema()
    {
      $sql = "";

      foreach($this->esquemas as $key => $esquema)
      {
        foreach($this->usuarios as $key => $grupo)
        {
          $sql  = "REVOKE ALL ON SCHEMA \"".$esquema."\" ";
          $sql .= "FROM GROUP \"".$grupo['nombre']."\" ; ";
        
          $sql .= "GRANT ".$grupo['esquemas']['todos']['privilegios']." ";
          $sql .= "ON SCHEMA \"".$esquema."\" TO GROUP \"".$grupo['nombre']."\"; ";
        
          if(!$this->EjecutarSql($sql))  return false;
        }
      }
      return true;
    }
    /**
    * Otorga los permisos de los grupos existentes en el archivo, para las 
    * tablas de cada esquema
    *
    * @return boolean
    */
    function Tablas()
    {
      $sql = "";
      $tablas = array();
      $grupo_nombres = "";
      
      foreach($this->usuarios as $key => $grupo)
        ($grupo_nombres == "")? $grupo_nombres = $grupo['nombre']:$grupo_nombres .= ",".$grupo['nombre'];
      
      $cont = 0;
      foreach($this->esquemas as $key => $esquema)
      {
        $sql  = "SELECT tablename "; 
        $sql .= "FROM   pg_catalog.pg_tables  ";
        $sql .= "WHERE  schemaname = '".$esquema."' ";
        $sql .= "ORDER  BY tablename "; 
        
        if(!$rst = $this->EjecutarSql($sql)) return false;
        
        while(!$rst->EOF)
        {
          $tablas[$esquema][] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        $sql = "";
        
        if(!empty($tablas[$esquema]))
        {
          foreach($tablas[$esquema] as $keyI => $tabla)
          {
            $sql .= "REVOKE ALL PRIVILEGES ";
            $sql .= "ON TABLE \"".$esquema."\".\"".$tabla['tablename']."\" ";
            $sql .= "FROM GROUP ".$grupo_nombres."; ";
          }
          
          if(!$this->EjecutarSql($sql)) return false;
        
          $sql = "";
          foreach($this->usuarios as $keyU => $grupo)
          {
            if(!empty($grupo['tablas']['todos']))
            {
              foreach($tablas[$esquema] as $keyI => $tabla)
              {
                $sql .= "GRANT ".$grupo['tablas']['todos']['privilegios']." ";
                $sql .= "ON TABLE \"".$esquema."\".\"".$tabla['tablename']."\" ";
                $sql .= "TO GROUP ".$grupo['nombre']." ; ";
                $cont++;
              }
              if(!$this->EjecutarSql($sql))  return false;
            }
            else if (!empty($grupo['tablas'][$esquema]))
            {            
              if(empty($grupo['tablas'][$esquema]['tablas']))
              {
                foreach($tablas[$esquema] as $keyI => $tabla)
                {
                  $sql .= "GRANT ".$grupo['tablas'][$esquema]['privilegios']." ";
                  $sql .= "ON TABLE \"".$esquema."\".\"".$tabla['tablename']."\" ";
                  $sql .= "TO GROUP ".$grupo['nombre']." ; ";
                  $cont++;
                }
              }
              else
              {
                $sql .= "GRANT ".$grupo['tablas'][$esquema]['privilegios']." ";
                $sql .= "ON TABLE ".$grupo['tablas'][$esquema]['tablas']." ";
                $sql .= "TO GROUP ".$grupo['nombre']."; ";
                $cont++;
              }
              if(!$this->EjecutarSql($sql))  return false;
            }
          }
        }
      }
      
      $this->SetResumen("Tablas:",$cont);
      return true;
    }
    /**
    * Otorga los permisos de los grupos existentes en el archivo, para las 
    * vistas de cada esquema
    *
    * @return boolean
    */
    function Vistas()
    {
      $sql = "";
      $tablas = array();
      $grupo_nombres = "";
      
      foreach($this->usuarios as $key => $grupo)
        ($grupo_nombres == "")? $grupo_nombres = $grupo['nombre']:$grupo_nombres .= ",".$grupo['nombre'];
        
      $cont = 0;      
      foreach($this->esquemas as $key => $esquema)
      {
        $sql  = "SELECT viewname "; 
        $sql .= "FROM   pg_catalog.pg_views  ";
        $sql .= "WHERE  schemaname = '".$esquema."' ";
        $sql .= "ORDER  BY viewname "; 
        
        if(!$rst = $this->EjecutarSql($sql)) return false;
        
        while(!$rst->EOF)
        {
          $tablas[$esquema][] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        $sql = "";

        if(!empty($tablas[$esquema]))
        {
          foreach($tablas[$esquema] as $keyI => $tabla)
          {
            $sql .= "REVOKE ALL PRIVILEGES ";
            $sql .= "ON \"".$esquema."\".\"".$tabla['viewname']."\" ";
            $sql .= "FROM GROUP ".$grupo_nombres."; ";
            
            $cont++;
          }
          
          if(!$this->EjecutarSql($sql)) return false;
        
          foreach($this->usuarios as $keyU => $grupo)
          {
            $sql = "";
            if(!empty($grupo['vistas']['todos']))
            {
              foreach($tablas[$esquema] as $keyI => $tabla)
              {
                $sql .= "GRANT ".$grupo['vistas']['todos']['privilegios']." ";
                $sql .= "ON  \"".$esquema."\".\"".$tabla['viewname']."\" ";
                $sql .= "TO GROUP ".$grupo['nombre']." ; ";
                
                $cont++;
              }
              if(!$this->EjecutarSql($sql))  return false;
            }
            else if(!empty($grupo['vistas'][$esquema]))
            {
              if(empty($grupo['vistas'][$esquema]['vistas']))
              {
                foreach($tablas[$esquema] as $keyI => $tabla)
                {
                  $sql .= "GRANT ".$grupo['vistas'][$esquema]['privilegios']." ";
                  $sql .= "ON \"".$esquema."\".\"".$tabla['viewname']."\" ";
                  $sql .= "TO GROUP ".$grupo['nombre']." ; ";
                  $cont++;
                }
              }
              else
              {
                $sql .= "GRANT ".$grupo['tablas'][$esquema]['privilegios']." ";
                $sql .= "ON ".$grupo['vistas'][$esquema]['vistas']." ";
                $sql .= "TO GROUP ".$grupo['nombre']."; ";
                $cont++;
              }
              if(!$this->EjecutarSql($sql))  return false;
            }
          }
        }
      }
      $this->SetResumen("Vistas:",$cont);
      return true;
    }
    /**
    * Otorga los permisos de los grupos existentes en el archivo, para las 
    * secuencias de cada esquema
    *
    * @return boolean
    */
    function Secuencias()
    {
      $sql = "";
      $secuencias = array();
      $grupo_nombres = "";
      
      foreach($this->usuarios as $key => $grupo)
        ($grupo_nombres == "")? $grupo_nombres = $grupo['nombre']:$grupo_nombres .= ",".$grupo['nombre'];
      
      $cont = 0;
      foreach($this->esquemas as $key => $esquema)
      {
        $sql  = "SELECT c.relname "; 
        $sql .= "FROM  pg_catalog.pg_class c, "; 
        $sql .= "      pg_catalog.pg_user u,  ";
        $sql .= "      pg_catalog.pg_namespace n  ";
        $sql .= "WHERE	c.relowner=u.usesysid  ";
        $sql .= "AND   c.relnamespace=n.oid  ";
        $sql .= "AND   c.relkind = 'S'  ";
        $sql .= "AND   n.nspname= '".$esquema."' ";
        $sql .= "ORDER BY relname "; 
        
        if(!$rst = $this->EjecutarSql($sql)) return false;
        
        while(!$rst->EOF)
        {
          $secuencias[$esquema][] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        $sql = "";
        
        if(!empty($secuencias[$esquema]))
        {
          foreach($secuencias[$esquema] as $keyI => $secuencia)
          {
            $sql .= "REVOKE ALL PRIVILEGES ";
            $sql .= "ON SEQUENCE \"".$esquema."\".\"".$secuencia['relname']."\" ";
            $sql .= "FROM GROUP ".$grupo_nombres."; ";
          }
          
          if(!$this->EjecutarSql($sql)) return false;
        
          foreach($this->usuarios as $keyU => $grupo)
          {
            $sql = "";
            if(!empty($grupo['secuencias']['todos']))
            {
              foreach($secuencias[$esquema] as $keyI => $secuencia)
              {
                $sql .= "GRANT ".$grupo['secuencias']['todos']['privilegios']." ";
                $sql .= "ON SEQUENCE \"".$esquema."\".\"".$secuencia['relname']."\" ";
                $sql .= "TO GROUP ".$grupo['nombre']." ; ";
                $cont++;
              }
              if(!$this->EjecutarSql($sql))  return false;
            }
            else if(!empty($grupo['secuencias'][$esquema]))
            {
              if(empty($grupo['tablas'][$esquema]['secuencias']))
              {
                foreach($secuencias[$esquema] as $keyI => $secuencia)
                {
                  $sql .= "GRANT ".$grupo['secuencias'][$esquema]['privilegios']." ";
                  $sql .= "ON SEQUENCE \"".$esquema."\".\"".$secuencia['relname']."\" ";
                  $sql .= "TO GROUP ".$grupo['nombre']." ; ";
                  $cont++;
                }
              }
              else
              {
                $sql .= "GRANT ".$grupo['secuencias'][$esquema]['privilegios']." ";
                $sql .= "ON SEQUENCE ".$grupo['secuencias'][$esquema]['secuencias']." ";
                $sql .= "TO GROUP ".$grupo['nombre']."; ";
                $cont++;
              }
              if(!$this->EjecutarSql($sql))  return false;
            }
          }
        }
      }
      $this->SetResumen("secuencias:",$cont);
      return true;
    }
    /**
    * Otorga los permisos de los grupos existentes en el archivo, para las 
    * funciones de cada esquema
    *
    * @return boolean
    */
    function Funciones()
    {
      $sql = "";
      $funciones = array();
      $grupo_nombres = "";
      
      foreach($this->usuarios as $key => $grupo)
        ($grupo_nombres == "")? $grupo_nombres = "\"".$grupo['nombre']."\"":$grupo_nombres .= ",\"".$grupo['nombre']."\"";
      
      $cont = 0;
      foreach($this->esquemas as $key => $esquema)
      {
        $sql  = "SELECT DISTINCT p.proname, ";
        $sql .= "       pg_catalog.oidvectortypes(p.proargtypes) AS argumentos  ";
        $sql .= "FROM   pg_catalog.pg_proc p  ";
        $sql .= "       LEFT JOIN pg_catalog.pg_namespace n ON n.oid = p.pronamespace  ";
        $sql .= "WHERE  p.prorettype <> 'pg_catalog.cstring'::pg_catalog.regtype  ";
        $sql .= "AND    p.proargtypes[0] <> 'pg_catalog.cstring'::pg_catalog.regtype "; 
        $sql .= "AND    NOT p.proisagg "; 
        $sql .= "AND    n.nspname = '".$esquema."' ";
        //$sql .= "ORDER BY	p.proname "; 
        
        if(!$rst = $this->EjecutarSql($sql)) return false;
        
        while(!$rst->EOF)
        {
          $funciones[$esquema][] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        $sql = "";
        
        if(!empty($funciones[$esquema]))
        {
          foreach($funciones[$esquema] as $keyI => $funcion)
          {
            $sql .= "REVOKE ALL PRIVILEGES ";
            $sql .= "ON FUNCTION \"".$esquema."\".".$funcion['proname']."(".$funcion['argumentos'].") ";
            $sql .= "FROM GROUP ".$grupo_nombres." CASCADE; ";
          }
          
          if(!$this->EjecutarSql($sql)) return false;
          
          foreach($this->usuarios as $keyU => $grupo)
          {
            $sql = "";
            if(!empty($grupo['funciones']['todos']))
            {
              foreach($funciones[$esquema] as $keyI => $funcion)
              {
                $sql .= "GRANT ".$grupo['funciones']['todos']['privilegios']." ";
                $sql .= "ON FUNCTION \"".$esquema."\".".$funcion['proname']."(".$funcion['argumentos'].") ";
                $sql .= "TO GROUP ".$grupo['nombre']." ; ";
                $cont++;
              }
              if(!$this->EjecutarSql($sql))  return false;
            }
            else if(!empty($grupo['funciones'][$esquema]))
            {
              if(empty($grupo['funciones'][$esquema]['funciones']))
              {
                foreach($funciones[$esquema] as $keyI => $funcion)
                {
                  $sql .= "GRANT ".$grupo['funciones'][$esquema]['privilegios']." ";
                  $sql .= "ON FUNCTION \"".$esquema."\".".$funcion['proname']."(".$funcion['argumentos'].") ";
                  $sql .= "TO GROUP ".$grupo['nombre']." ; ";
                  $cont++;
                }
              }
              else
              {
                $sql .= "GRANT ".$grupo['funciones'][$esquema]['privilegios']." ";
                $sql .= "ON FUNCTION ".$grupo['funciones'][$esquema]['funciones']."() ";
                $sql .= "TO GROUP ".$grupo['nombre']."; ";
                $cont++;
              }
              if(!$this->EjecutarSql($sql))  return false;
            }
          }
        }
      }
      $this->SetResumen("Funciones:",$cont);
      return true;
    }
    /**
    * Agrega una posicion al resumen del security 
    *
    * @param string Concepto
    * @param string Detalle
    */
    function SetResumen($Concepto,$Detalle)
    {
      $this->Resumen[]=array('concepto'=>$Concepto,'detalle'=>$Detalle);
    }
    /**
    * Hace la conexion a la base de datos
    *
    * @param string dbType
    * @param string UserDB
    * @param string Passs
    * @param string dbHost
    * @param string dbName
    * 
    * @return boolean
    */
    function Conexion($dbType,$User,$Pass,$dbHost,$dbName)
    {
      $this->dbconn = ADONewConnection($dbType);
    
  		if (!($this->dbconn->Connect($dbHost,$User,$Pass,$dbName))) 
  		{
  			$this->error=1;
  			$this->msjError= "PERMISOS DB : Error en la Conexión a la Base de Datos <br>".$this->dbconn->ErrorMsg();
  			return false;
  		}
      
      $this->SetResumen('Host',$dbHost);
  		$this->SetResumen('Base de Datos',$dbName);
  		$this->SetResumen('Usuario Conexión',$this->UserAdmin);
  		$this->SetResumen('Usuario Aplicación',$this->UserApp);
      
      return true;
    }
    /**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
    * @return object $rst
    */
    function EjecutarSql($sql)
    {
      $rst = $this->dbconn->Execute($sql);
      
      if ($this->dbconn->ErrorNo() != 0)
      {
        
        $this->msjError = $this->dbconn->ErrorMsg()."<br>".$sql;
        return false;
      }
      return $rst;
    }
    /**
    * Imprime el mensaje de error generado surante la ejecucion de los querys
    **/
    function ImprimirError()
    {
      $Salida .= "<html>\n";
  		$Salida .= "  <head>\n";
  		$Salida .= "    <title>Security</title>\n";
  		$Salida .= "    <meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>\n";
  		$Salida .= "    <link href=\"../themes/HTML/AzulXp/style/style.css\" rel=\"stylesheet\" type=\"text/css\">\n";
  		$Salida .= "  </head>\n";
  		$Salida .= "  <br>\n";
  		$Salida .= "  <body>\n";
  		$Salida .= "    <center><h1>SECURITY-SIIS</h1></center>\n";
      $Salida .= "    <table align=\"center\" width=\"60%\" border=\"0\" class=\"label_error\">\n";
      $Salida .= "      <tr>\n";
      $Salida .= "        <td aling=\"justify\">".$this->msjError."</td>\n";
      $Salida .= "      <tr>\n";
      $Salida .= "    </table>\n";
      $Salida .= "  </body>\n";
      $Salida .= "</html>\n";
      echo $Salida;
    }
    /**
    * Imprime el resumen de ejecución del Security
    * 
    * @access 
    */
  	function ImprimirResumen()
  	{
  		$Salida .= "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
  		$Salida .= "<html>\n";
  		$Salida .= "<head>\n";
  		$Salida .= "  <title>Security</title>\n";
  		$Salida .= "  <meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>\n";
  		$Salida .= "  <link href=\"../themes/HTML/AzulXp/style/style.css\" rel=\"stylesheet\" type=\"text/css\">\n";
  		$Salida .= "</head>\n";
  		$Salida .= "<br>\n";
  		$Salida .= "<body>\n";
  		$Salida .= "<center><h1>SECURITY-SIIS</h1></center>\n";
  		$Salida .= "<table align=\"center\" width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
  		$Salida .= "	<tr class=\"modulo_table_list_title\" >\n";
  		$Salida .= "		<td align=\"center\">Concepto</td>\n";
  		$Salida .= "		<td align=\"center\">Detalle</td>\n";
  		$Salida .= "	</tr>\n";
  		foreach($this->Resumen as $Fila)
  		{
  			$Salida .= "<tr class=\"modulo_list_claro\">";
  			$Salida .= "	<td align=\"left\" class=\"label\">".$Fila['concepto']."</td>\n";
  			$Salida .= "	<td align=\"center\" class=\"label\">".$Fila['detalle']."</td>\n";
  			$Salida .= "</tr>\n";
  		}
  		$Salida .= "</table>\n";
  		$Salida .= "</body>\n";
  		$Salida .= "</html>";
  		echo $Salida;
  	}
  }
?>