<?php
	
	class BuscadorLocalizacionSql
	{
		function BuscadorLocalizacionSql(){}
		/*************************************************************************************
		*
		**************************************************************************************/
		function ObtenerPaises($pais = null)
		{
			$sql  = "SELECT	bloqueado_edicion, ";
			$sql .= "				tipo_pais_id,";
			$sql .= "				pais ";
			$sql .= "FROM 	tipo_pais ";
			if($pais != null)
				$sql .= "WHERE	tipo_pais_id = '".$pais."' ";
				
			$sql .= "ORDER BY pais ";
			
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
		/*************************************************************************************
		*
		**************************************************************************************/
		function ObtenerDepartamentos($pais,$desc_departamento = null)
		{
			$sql  = "SELECT	tipo_dpto_id,";
			$sql .= "				departamento ";
			$sql .= "FROM		tipo_dptos ";
			$sql .= "WHERE	tipo_pais_id ='".$pais."' ";
			
			if($desc_departamento)
				$sql .= "AND		departamento ILIKE '".$desc_departamento."' ";
				
			$sql .= "ORDER BY departamento ";
			
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
		/*************************************************************************************
		*
		**************************************************************************************/
		function ObtenerCiudades($pais,$deptno,$desc_municipio = null)
		{
			$sql  = "SELECT	tipo_mpio_id,";
			$sql .= "				municipio ";
			$sql .= "FROM		tipo_mpios ";
			$sql .= "WHERE	tipo_pais_id = '".$pais."' ";
			$sql .= "AND		tipo_dpto_id = '".$deptno."' ";
			
			if($desc_municipio)
				$sql .= "AND		municipio ILIKE '".$desc_municipio."' ";
				
			$sql .= "ORDER BY municipio ";
			
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
		/*************************************************************************************
		*
		**************************************************************************************/
		function ObtenerComunas($pais, $deptno, $mnpio, $des_comuna=null){
			
			//$this->debug = true;
 		
			$sql = "SELECT tipo_comuna_id, comuna  
				FROM tipo_comunas  
				WHERE tipo_pais_id = '".$pais."' AND tipo_dpto_id = '".$deptno."' AND tipo_mpio_id = '".$mnpio."' " ;
			
			if($des_comuna)	
				$sql .= "AND comuna ILIKE '".$des_comuna."' ";
		
				
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
		/*************************************************************************************
		*
		**************************************************************************************/
    function ObtenerLocalizacion($val){
    
      $sql = "SELECT tipo_pais_id, equiv_municipio, equiv_departamento, equiv_comuna  
             FROM buscador_localizacion 
             WHERE tipo_pais_id = '".$val."' ;";
    
    
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
    /*************************************************************************************
    *
    **************************************************************************************/    
    function IngresarDepartamentos($pais,$departamento,$municipio)
		{
			$sql  = "SELECT	COALESCE(TO_NUMBER(MAX(tipo_dpto_id),99999),0)+1 AS codigo ";
			$sql .= "FROM		tipo_dptos ";
			$sql .= "WHERE	tipo_pais_id = '".$pais."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$codigo = array();
			if(!$rst->EOF)
			{
				$codigo = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$sql  = "INSERT INTO tipo_dptos (";
			$sql .= "			tipo_dpto_id,";
			$sql .= " 		tipo_pais_id,";
			$sql .= " 		departamento";
			$sql .= "		) ";
			$sql .= "VALUES";
			$sql .= "(";
			$sql .= "			'".$codigo['codigo']."',";
			$sql .= "			'".$pais."',";
			$sql .= "			'".strtoupper($departamento)."' ";
			$sql .= ");";
			
			$sql .= "INSERT INTO tipo_mpios (";
			$sql .= "				tipo_pais_id,";
			$sql .= " 			tipo_dpto_id,";
			$sql .= " 			tipo_mpio_id,";
			$sql .= " 			municipio ";
			$sql .= "		) ";
			$sql .= "VALUES";
			$sql .= "(";
			$sql .= "			'".$pais."',";
			$sql .= "			'".$codigo['codigo']."',";
			$sql .= "			'1',";
			$sql .= "			'".strtoupper($municipio)."' ";
			$sql .= ");";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos['departamento'] = $codigo['codigo'];
			$datos['municipio'] = '1';
			
			return $datos;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function IngresarMunicipios($pais,$departamento,$municipio)
		{
			$sql  = "SELECT	COALESCE(TO_NUMBER(MAX(tipo_mpio_id),999999),0)+1 AS codigo ";
			$sql .= "FROM		tipo_mpios ";
			$sql .= "WHERE	tipo_pais_id = '".$pais."' ";
			$sql .= "AND		tipo_dpto_id = '".$departamento."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$codigo = array();
			if(!$rst->EOF)
			{
				$codigo = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$sql  = "INSERT INTO tipo_mpios (";
			$sql .= "				tipo_pais_id,";
			$sql .= " 			tipo_dpto_id,";
			$sql .= " 			tipo_mpio_id,";
			$sql .= " 			municipio ";
			$sql .= "		) ";
			$sql .= "VALUES";
			$sql .= "(";
			$sql .= "			'".$pais."',";
			$sql .= "			'".$departamento."',";
			$sql .= "			'".$codigo['codigo']."',";
			$sql .= "			'".strtoupper($municipio)."' ";
			$sql .= ");";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos['municipio'] = $codigo['codigo'];
			
			return $datos;
		}
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
		
	}
?>