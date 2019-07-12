<?php


class ParametrizacionMtoSalud
{
    /**
    * Codigo de error
    *
    * @var string
    * @access public
    */
    var $error;

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
    function ParametrizacionMtoSalud(){}

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
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
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
    * Copnsulta los permisos que posee el usuario que ingresa al modulo+
    *
    * @return array
    */
    function ObtenerPermisos()
    {
        $sql  = "SELECT usuario_id ";
        $sql .= "FROM   userpermisos_guiamtosalud ";
        $sql .= "WHERE  usuario_id = ".UserGetUID()." ";

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
		
		function GetCargosApoyos($filtros=array(), $count=null, $limit=null, $offset=null)
    {        
				
				$filtro = "";
        if($filtros['valor'])
        {
          if($filtros['tipo']==='1')
          {
             $filtro = " AND a.cargo = " . $filtros['valor'] . " ";
          }
          elseif($filtros['tipo']==='2')
          {
             $filtro = " AND b.descripcion ILIKE '%" . $filtros['valor'] . "%' ";
          }
        }
        

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = "";
        }

        if(empty($count))
        {
            $select = "                        
                        b.descripcion,
												a.cargo
            ";

            $filtro .= " ORDER BY a.cargo ";
        }
        else
        {
            $select = " COUNT(*) as cantidad";
        }
        $sql  = "
                    SELECT $select
                    FROM
                        apoyod_cargos as a, cups b

                    WHERE
										    a.cargo=b.cargo                        
                        $filtro
                    $filtro_limit;
        ";

        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        if(empty($count))
        {
            $retorno = array();

            while($fila = $result->FetchRow())
            {
                $retorno[]=$fila;
            }
            $result->Close();
        }
        else
        {
            $fila = $result->FetchRow();
            $retorno = $fila['cantidad'];
        }

        return  $retorno;
				

    }
		
		
		function IngresarDatosActividades($datos)
		{			
			if($datos['actividad'])
			{
				$actividad=$datos['actividad'];
				$tipo="'".$datos['tipoactividad']."'";
				$cargo='NULL';
			}
			else
			{
				$actividad="";
				$tipo="'PT'";
				$cargo=$datos['cargo'];
				
			}
			$sql  = "INSERT INTO mtosalud_actividad (descripcion,cargo,tipoactividad)";
      $sql .= "VALUES('$actividad',$cargo,$tipo);";
			
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      return true;
	 }	
	 
	 function GetActividades()
    {
        $sql  = "(SELECT tipoactividad, actividadid as id, descripcion, null as cargo ";
        $sql .= "FROM  mtosalud_actividad ";
				$sql .= "WHERE cargo IS NULL) ";
				$sql .= "UNION ";
				$sql .= "(SELECT a.tipoactividad, a.actividadid as id, b.descripcion, a.cargo ";
        $sql .= "FROM  mtosalud_actividad a, cups b ";
				$sql .= "WHERE a.cargo IS NOT NULL ";
				$sql .= "AND a.cargo=b.cargo)";
        				
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
            $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }
		
		function GetEtapas()
    {
				$sql  = "SELECT etapaid, descripcion, edadinicio, edadfin, tipoedad ";
        $sql .= "FROM  mtosalud_etapa ";				
				$sql .= "ORDER BY etapaid";				
        			
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
		
		function IngresarDatosParametrizacion($datos)
		{			
			
			$sql='';
			if($datos)
			{	
				$sql .= "DELETE FROM mtosalud_parametrizacion;";				
				$etapas = $this->GetEtapas();
				foreach($etapas as $etapaId=>$arrE)
				{				
					$arr = $datos['EtapaValor'.$etapaId];
					foreach($arr as $cont=>$vec)
					{
						$val=explode(',',$vec);
						$sql .= "INSERT INTO mtosalud_parametrizacion (etapaid,actividadid,edad)";
						$sql .= "VALUES($etapaId,".$val[0].",".$val[1].");";
					}					
				}					
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				return true;
			}	
	 }
	 
	 function GetParametrizacion()
	 {
				$sql  = "SELECT etapaid,actividadid,edad ";
        $sql .= "FROM mtosalud_parametrizacion";								
        			
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
            $datos[$rst->fields[0]][$rst->fields[1]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
		} 	
}
?>