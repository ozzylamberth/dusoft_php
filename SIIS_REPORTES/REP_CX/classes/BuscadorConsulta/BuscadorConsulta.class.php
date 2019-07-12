<?php
	/**************************************************************************************
	* $Id: BuscadorConsulta.class.php,v 1.2 2006/05/02 12:50:22 mauricio Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	class BuscadorConsulta
	{
		function BuscadorConsulta()
		{
			return true;
		}
		
		function ObtenerSql($opcion)
		{
			$sql = "";
			$datos = "";
			$this->adicional = "";
			switch($opcion)
			{
				case '0':				
					$sql .= "SELECT 	T.tipo_id_tercero||' '||T.tercero_id AS tercero, ";
					$sql .= "					T.nombre_tercero ";			
					$where .= "FROM 	terceros T ";
					$where .= "WHERE	tercero_id IS NOT NULL ";

					if($_REQUEST['nombre_tercero'] != "")
					{
						$where .= "AND T.nombre_tercero ILIKE '%".$_REQUEST['nombre_tercero']."%' ";
					}
					if($_REQUEST['tercero_id'] != "")
					{
						$where .= "AND T.tercero_id = '".$_REQUEST['tercero_id']."' ";
					}
						
					$this->ProcesarSqlConteo("SELECT COUNT(*) $where");
					
					$where .= "ORDER BY 2 ";
					$where .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
					
					$sql .= $where;
				break;
				case 'diagnosticos':
					$filtro = '';
					if($_REQUEST['diagnostico_id'])
					{
						$filtro = "diagnostico_id = '".$_REQUEST['diagnostico_id']."'";
					}
					if($_REQUEST['diagnostico_nombre'])
					{
						if($filtro != '')
							$filtro .= "AND diagnostico_nombre ILIKE '%".$_REQUEST['diagnostico_nombre']."%' ";
						else
							$filtro = "diagnostico_nombre ILIKE '%".$_REQUEST['diagnostico_nombre']."%' ";
					}
					if($filtro)
					{
						$this->adicional = "&diagnostico_id=".$_REQUEST['diagnostico_id']."&diagnostico_nombre=".$_REQUEST['diagnostico_nombre']."";
						$sql = "SELECT	diagnostico_id,
															diagnostico_nombre ";
						$where = "FROM		diagnosticos
											WHERE		".$filtro." ";
						$this->ProcesarSqlConteo("SELECT COUNT(*) $where");
	
						$where .= "ORDER BY 2 ";
						$where .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
						
						$sql .= $where;
					}
				break;
			}//fin switch
			if($sql != "")
			{
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
						
				while(!$rst->EOF)
				{				
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
			}
			
			return $datos;
		}
		/********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($_REQUEST['offset'])
			{
				$this->paginaActual = intval($_REQUEST['offset']);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$result = $this->ConexionBaseDatos($consulta))
				return false;

			if(!$result->EOF)
			{
				$this->conteo = $result->fields[0];
				$result->MoveNext();
			}
			$result->Close();
			return true;
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
			$dbconn->debug=true;
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
