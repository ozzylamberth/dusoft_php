<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: Modelo.class.php,v 1.10 2008/04/07 13:27:47 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : Modelo
	* Clase que permite hacer un manejo generico de las consultas, sobre una tabla
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.10 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Modelo
  {
    /**
    * Vector de las llaves primarias
    *
    * @var array
    * @access public
    */
    var $primarykey = array();
    /**
    * Vector de las llaves foraneas
    * ej. array(<nombre_tabla_padre> => array(<nombre_campo_tabla_padre> => <nombre_campo_tabla_hija>))
    *
    * @var array
    * @access public
    */
    var $foreignkey = array();
    /**
    * Limite de la busqueda 
    *
    * @var int
    * @access public
    */
    var $limit ;
    /**
    * Cantidad de datos de la busqueda
    *
    * @var int
    * @access public
    */
    var $conteo ;
    /**
    * Pagina actual de la busqueda
    *
    * @var int
    * @access public
    */
    var $pagina ;
    /**
    * Offset de la busqueda 
    *
    * @var int
    * @access public
    */
    var $offset ;
    /**
    * Esquema de la clase 
    *
    * @var string
    * @access public
    */
    var $esquema = "public" ;
    /**
    * Mensaje de error
    *
    * @var string
    * @access public
    */
    var $mensajeDeError;
    /**
    * Constructor de la clase
    */
    function Modelo(){}
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
    * Funcion donde se verifica si una tabla existe o no
    *
    * @param String $nombre_tabla  nombre de la tabla a consultar
    *
    * @return array 
    */
    function IsTableExist($nombre_tabla)
    {
      $sql  = "SELECT  table_name as name "; 
      $sql .= "FROM    INFORMATION_SCHEMA.tables ";
      $sql .= "WHERE   table_schema = '".$this->esquema."' ";
      $sql .= "AND    table_name ILIKE '".$nombre_tabla."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se obtienen los campos, de los cuales se compone la tabla
    *
    * @param String $nombre_tabla  nombre de la tabla a consultar
    *
    * @return array 
    */
    function ObtenerCamposTabla($nombre_tabla)
    {
      $sql  = "SELECT DISTINCT c.column_name AS name, ";
      $sql .= "       c.data_type AS type, ";
      $sql .= "       case when c.is_nullable = 'NO' THEN 1 else 0 end AS null, ";
      $sql .= "       c.column_default AS default,";
      $sql .= "       c.ordinal_position AS position, ";
      $sql .= "       c.character_maximum_length AS char_length, ";
      $sql .= "       c.character_octet_length AS oct_length, "; 
      $sql .= "       col_description(a.attrelid, a.attnum) AS comment "; 
      $sql .= "FROM   information_schema.columns c, ";
      $sql .= "       pg_attribute a, ";
      $sql .= "       pg_class p ";
      $sql .= "WHERE  table_name ILIKE '".$nombre_tabla."' "; 
      $sql .= "AND    p.relname = c.table_name ";
      $sql .= "AND    p.oid = a.attrelid ";
      $sql .= "AND    c.column_name = a.attname ";
      $sql .= "ORDER BY position ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtienen todos los registro de la tabla 
    *
    * @param array $datos_tabla Vector con los datos generales de la tabla
    * @param int $pagina  pagina actual que se esta trabajando
    * @param array $buscador Vector con los filtros de busqueda
    *
    * @return array 
    */
    function ObtenerDatos($datos_tabla,$pagina,$buscador = array())
    {
      $columnas_select = "";
      $tablas_select = "";
      $order_by = "";
      $condicion_select = "";
      
      foreach($datos_tabla as $key0 => $nombre_tabla)
      {
        foreach($nombre_tabla as $keyI => $columnas)
        {
          ($columnas_select == "")? $columnas_select .= $key0.".".$columnas['name']." ": $columnas_select .= ",".$key0.".".$columnas['name']." ";
          if(array_key_exists($columnas['name'],$buscador))
          {
            if($buscador[$columnas['name']] != "")
            {
              $separador = $this->ObtenerSeparador($columnas['type']);
              $comodin = $this->ObtenerComodin($columnas['type']);
              $operador = $this->ObtenerOperador($columnas['type']);
              
              ($condicion_select == "")? $condicion_select .= "WHERE ".$key0.".".$columnas['name']." $operador $separador".$comodin.$buscador[$columnas['name']].$comodin."$separador ":$condicion_select .= "AND ".$key0.".".$columnas['name']." $operador $separador".$comodin.$buscador[$columnas['name']].$comodin."$separador ";
            }
          }
        }
        ($tablas_select == "")? $tablas_select .= $this->esquema.".".$key0." ": $tabla_select .= ",".$this->esquema.".".$key0." ";
        
        if(!empty($this->primarykey))
        {
          foreach($this->primarykey as $key => $valor)
          {
            ($order_by == "")? $order_by .= $key0.".".$valor." ":$order_by .= ",".$key0.".".$valor." ";
          }
        }      
      }
      
      $sql  = "SELECT ".$columnas_select;
      $sql .= "FROM ".$tablas_select;
      $sql .= " ".$condicion_select." ";
      if($order_by != "")
        $sql .= "ORDER BY ".$order_by;
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      if(!$rst->EOF) $cont = $rst->RecordCount();

			if($cont > 0)
			{
				$this->ProcesarSqlConteo($cont,$pagina);

				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			}
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
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
    * Funcion donde se obtiene un registro especifico de la tabla filtrado 
    * por llave primaria
    *
    * @param array $datos_tabla  Vector con los datos generales de la tabla
    * @param array $restricciones  Vector con los datos de la llave primaria
    *
    * @return array 
    */
    function ObtenerRegistro($datos_tabla,$restricciones)
    {
      $columnas_select = "";
      $tablas_select = "";
      $condicion_select = "";
      
      foreach($datos_tabla as $key0 => $nombre_tabla)
      {
        foreach($nombre_tabla as $keyI => $columnas)
        {
          ($columnas_select == "")? $columnas_select .= $this->esquema.".".$key0.".".$columnas['name']." ": $columnas_select .= ",".$this->esquema.".".$key0.".".$columnas['name']." ";
        
          $separador = $this->ObtenerSeparador($columnas['type']);
          
          if(!empty($this->primarykey))
          {
            if(array_key_exists($columnas['name'],$restricciones))
            {
              ($condicion_select == "")? $condicion_select .= "WHERE ".$key0.".".$columnas['name']." = $separador".$restricciones[$columnas['name']]."$separador ":$condicion_select .= "AND ".$key0.".".$columnas['name']." = $separador".$restricciones[$columnas['name']]."$separador ";
            }
          } 
        }
        ($tablas_select == "")? $tablas_select .= $key0." ": $tabla_select .= ",".$key0." ";
      }
      
      $sql  = "SELECT ".$columnas_select;
      $sql .= "FROM ".$tablas_select;
      $sql .= $condicion_select;
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se actualizan registros en la tabla
    *
    * @param array $datos_tabla  Vector con los datos generales de la tabla
    * @param array $restricciones  Vector con los datos de la llave primaria
    * @param array $datos  Vector con los datos que se actualizaran en la tabla
    *
    * @return boolean 
    */
    function ActualizarRegistro($datos_tabla,$restricciones,$datos)
    {
      $columnas_select = "";
      $condicion_select = "";
      
      foreach($datos_tabla as $key0 => $nombre_tabla)
      {
        foreach($nombre_tabla as $keyI => $columnas)
        {
          $separador = $this->ObtenerSeparador($columnas['type']);
          if($columnas['type'] == 'date' || $columnas['type'] == 'timestamp without time zone')
          {
            $f = explode("/",$datos[$columnas['name']]);
            $datos[$columnas['name']] = $f[2]."-".$f[1]."-".$f[0]; 
          }
          
          if($datos['chk_'.$columnas['name']] == "on")
          {
            $separador = "";
            $datos[$columnas['name']] = "NULL";
          }
          
          ($columnas_select == "")? $columnas_select .= $columnas['name']." = $separador".$datos[$columnas['name']]."$separador ": $columnas_select .= ", ".$columnas['name']." = $separador".$datos[$columnas['name']]."$separador ";
          if(!empty($this->primarykey))
          {
            if(array_key_exists($columnas['name'],$restricciones))
            {
              ($condicion_select == "")? $condicion_select .= "WHERE ".$columnas['name']." = $separador".$restricciones[$columnas['name']]."$separador ":$condicion_select .= "AND ".$columnas['name']." = $separador".$restricciones[$columnas['name']]."$separador ";
            }
          }
        }      
      }
    
      $sql  = "UPDATE ".$this->esquema.".".$datos['nombre_tabla']." ";
      $sql .= "SET ".$columnas_select;
      $sql .= $condicion_select;

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      return true;
    }
    /**
    * Funcion donde se ingresa un nuevo registro a la tabla
    *
    * @param array $datos_tabla Vector con los datos generales de la tabla
    * @param array $datos Vector con los datos que se ingresaran a la tabla
    *
    * @return boolean 
    */
    function IngresarRegistro($datos_tabla,$datos)
    { 
      $columnas_insert = "";
      $values_select = "";

      foreach($datos_tabla as $key0 => $nombre_tabla)
      {
        foreach($nombre_tabla as $keyI => $columnas)
        {
          $separador = $this->ObtenerSeparador($columnas['type']);
          if($columnas['type'] == 'date' || $columnas['type'] == 'timestamp without time zone')
          {
            $separador = "";                
            $f = explode("/",$datos[$columnas['name']]);
            $datos[$columnas['name']] = " '".$f[2]."-".$f[1]."-".$f[0]."' "; 
          }
          
          ($columnas_insert == "")? $columnas_insert .= $columnas['name']." ": $columnas_insert .= ", ".$columnas['name']." ";
          
          if($datos['chk_'.$columnas['name']] == "on")
          {
            $separador = "";
            $datos[$columnas['name']] = "NULL";
          }
          ($values_insert == "")? $values_insert .= " $separador".$datos[$columnas['name']]."$separador ":$values_insert .= ", $separador".$datos[$columnas['name']]."$separador ";
        }      
      }
    
      $sql  = "INSERT INTO ".$this->esquema.".".$datos['nombre_tabla']." ";
      $sql .= "( ".$columnas_insert." ) ";
      $sql .= "VALUES (".$values_insert."); ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      //$rst->Close();
      return true;
    }
    /**
    * Funcion donde se elimina un registro de la tabla que se este trabajando en 
    * el momento
    *
    * @param array $datos_tabla Vector con los datos generales de la tabla
    * @param array $datos Vector con los datos de la llave primararia, para
    *         eliminar el registro
    * @return boolean 
    */
    function Eliminarregistro($datos_tabla,$datos)
    {
      $columnas_select = "";
      $tablas_select = "";
      $order_by = "";
      
      foreach($datos_tabla as $key0 => $nombre_tabla)
      {
        foreach($nombre_tabla as $keyI => $columnas)
        {
          if(!empty($this->primarykey))
          {
            if(array_key_exists($columnas['name'],$datos['pkey']))
            {
              $separador = $this->ObtenerSeparador($columnas['type']);
              
              if($columnas['type'] == 'date' || $columnas['type'] == 'timestamp without time zone')
              {
                $f = explode("/",$datos['pkey'][$columnas['name']]);
                $datos['pkey'][$columnas['name']] = $f[2]."-".$f[1]."-".$f[0]; 
              }
              
              ($condicion_select == "")? $condicion_select .= "WHERE ".$columnas['name']." = $separador".$datos['pkey'][$columnas['name']]."$separador ":$condicion_select .= "AND ".$columnas['name']." = $separador".$datos['pkey'][$columnas['name']]."$separador ";
            }
          }        
        }  
      }
      
      $sql  = "DELETE ";
      $sql .= "FROM ".$datos['nombre_tabla']." ";
      $sql .= $condicion_select;
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      return true;
    }
    /**
    * Funcion donde se obtiene el separador, segun el tipo de dato
    * @param String $tipo Tipo de dato (ej. character, integer)
    *
    * @returns String 
    */
    function ObtenerSeparador($tipo)
    {
      $separador = "";
      switch($tipo)
      {
        case 'text':
        case 'character':
        case 'character varying':
        case 'date':
        case 'timestamp without time zone':
          $separador = "'";
        break;
        case 'smallint':
        case 'integer':
        case 'numeric':
          $separador = "";
        break;
        
      }
      return $separador;
    }
    /**
    * Funcion donde se obtiene el separador, segun el tipo de dato
    * @param String $tipo Tipo de dato (ej. character, integer)
    *
    * @returns String 
    */
    function ObtenerComodin($tipo)
    {
      $comodin = "";
      switch($tipo)
      {
        case 'text':
        case 'character':
        case 'character varying':
        case 'date':
        case 'timestamp without time zone':
          $comodin = "%";
        break;
        case 'smallint':
        case 'integer':
        case 'numeric':
          $comodin = "";
        break;
        
      }
      return $comodin;
    }
    /**
    * Funcion donde se obtiene el separador, segun el tipo de dato
    * @param String $tipo Tipo de dato (ej. character, integer)
    *
    * @returns String 
    */
    function ObtenerOperador($tipo)
    {
      $operador = "=";
      switch($tipo)
      {
        case 'text':
        case 'character':
        case 'character varying':
        case 'date':
        case 'timestamp without time zone':
          $operador = "ILIKE";
        break;
        case 'smallint':
        case 'integer':
        case 'numeric':
          $operador = "=";
        break;
      }
      return $operador;
    }
    /**
    * Funcion donde se obtiene el comentario de una tabla
    * 
    * @param String $nombre_tabla Nombre de la tabla
    * 
    * @returns array
    */
    function ObtenerComentarioTabla($nombre_tabla)
    {
      $sql  = "SELECT obj_description(oid, 'pg_class') AS comment ";
      $sql .= "FROM   pg_class ";
      $sql .= "WHERE  relname ILIKE '".$nombre_tabla."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
    /**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param String $sql sentencia sql a ejecutar
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                      por defecto es false
    * @return object $rst
    */
    function ConexionBaseDatos($sql,$asoc = false)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn)=GetDBConn();
        //$dbconn->debug=true;

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

        $rst = $dbconn->Execute($sql);

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = $dbconn->ErrorMsg();
          return false;
        }
        return $rst;
    }
    /**
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		*
		* @param String $num_reg Cadena que contiene la consulta sql del conteo
		* @param int $offset numero que define el limite de datos,cuando no se desa el del
		* 			 usuario,si no se pasa se tomara por defecto el del usuario
		* @return boolean
		*/
		function ProcesarSqlConteo($num_reg = null,$offset=null)
		{
			$this->offset = 0;
			$this->pagina = 1;
		
			$this->limit = GetLimitBrowser();
			if(!$this->limit) $this->limit = 20;
			
      if($offset)
			{
				$this->pagina = intval($offset);
				if($this->pagina > 1)
				{
					$this->offset = ($this->pagina - 1) * ($this->limit);
				}
			}
			
      $this->conteo = $num_reg;
			return true;
		}
    /**
    * Funcion donde se parte la fecha y se devuelve la fecha en formato yyyy-MM-DD
    *
    * @param strinmg $fecha Fecha pasada por parametro 
    *
    * @return string
    */
    function DividirFecha($fecha)
    {
      $f = explode("/",$fecha);
      if(sizeof($f) == 3 )
        $fecha = $f[2]."-".$f[1]."-".$f[0];
      
      return $fecha;
    }
  }
?>