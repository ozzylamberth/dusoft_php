<?php
  /******************************************************************************
  * $Id: Movimientos.class.php,v 1.1 2007/05/24 21:43:06 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
  ********************************************************************************/
	class Movimientos
	{
		function Movimientos(){}
		/********************************************************************************
		* @return array
		*********************************************************************************/
		function ObtenerPermisos($usuario)
		{
			$sql  = "SELECT DISTINCT EM.razon_social,";
			$sql .= "				EM.empresa_id, ";
			$sql .= "				EM.tipo_id_tercero, ";
			$sql .= "				EM.id ";
			$sql .= "FROM		empresas EM, ";
			$sql .= "				fac_grupos_usuarios FU ";
			$sql .= "WHERE	EM.empresa_id = FU.empresa_id ";
			$sql .= "AND		FU.usuario_id = ".$usuario." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		* @return array
		*********************************************************************************/
		function ObtenerGrupos($empresa,$usuario,$grupo)
		{
			$sql  = "SELECT DISTINCT FG.fac_grupo_id, ";
			$sql .= "				FG.descripcion AS grupo, ";
			$sql .= "				FG.sw_estado, ";
			$sql .= "				FG.sw_estado_busqueda ";
			$sql .= "FROM		fac_grupos FG, ";
			$sql .= "				fac_grupos_usuarios FU ";
			$sql .= "WHERE	FU.empresa_id = '".$empresa."' ";
			$sql .= "AND		FU.fac_grupo_id = FG.fac_grupo_id ";
			$sql .= "AND		FG.sw_estado NOT IN ('0','1') ";
			
			if($usuario) $sql .= "AND		FU.usuario_id = ".$usuario." ";
			
			if($grupo) $sql .= "AND		FU.fac_grupo_id = ".$grupo." ";
				
			$sql .= "ORDER BY grupo ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		* @return array
		*********************************************************************************/
		function ObtenerUsuariosGrupos($grupoid,$empresa,$usuario)
		{
			$sql  = "SELECT FU.fac_grupo_id, ";
			$sql .= "				FU.usuario_id, ";
			$sql .= "				SU.nombre ";
			$sql .= "FROM		fac_grupos_usuarios FU, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	FU.empresa_id = '".$empresa."' ";
			$sql .= "AND		FU.usuario_id = SU.usuario_id ";
			$sql .= "AND		FU.fac_grupo_id = ".$grupoid." ";
			if($usuario)
				$sql .= "AND		FU.usuario_id <> ".$usuario." ";
				
			$sql .= "ORDER BY SU.nombre ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		* @return 
		*********************************************************************************/
		function ObtenerFacturas($empresa,$estado,$offset,$factura,$usuario)
		{
			$sql  = "SELECT	MAX(TO_CHAR(FL.fecha_movimento,'DD/MM/YYYY')) AS fecha_movimiento,";
			$sql .= "				PL.plan_descripcion, "; 
			$sql .= " 			FF.prefijo, "; 
			$sql .= "				FF.factura_fiscal, "; 
			$sql .= "				FF.sw_estado, "; 
			$sql .= "				FF.total_factura, "; 
			$sql .= "				SU.nombre, "; 
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha_factura "; 
			$sql .= "FROM 	fac_facturas FF,"; 
			$sql .= "				planes PL, "; 
			$sql .= "				system_usuarios SU, "; 
			$sql .= "				fac_log_movimientos FL "; 
			$sql .= "WHERE 	FF.empresa_id = '".$empresa."' "; 
			$sql .= "AND		FF.sw_clase_factura = '1'::bpchar "; 
			$sql .= "AND		FF.tipo_factura IN ('1','2') ";  
			$sql .= "AND		FF.sw_estado IN (".$estado.") "; 
			$sql .= "AND		FL.sw_estado IN (".$estado.") "; 
			$sql .= "AND		FF.plan_id = PL.plan_id "; 
			$sql .= "AND		FF.empresa_id = FL.empresa_id  "; 
			$sql .= "AND		FF.prefijo = FL.prefijo  "; 
			$sql .= "AND		FF.factura_fiscal = FL.factura_fiscal ";  
			$sql .= "AND		FF.usuario_id_recepcion = SU.usuario_id ";
			if($factura['prefijo'])
				$sql .= "AND		FF.prefijo = '".$factura['prefijo']."'  ";
			if($factura['factura_fiscal'])
				$sql .= "AND		FF.factura_fiscal = ".$factura['factura_fiscal']." ";
			if($usuario)
				$sql .= "AND		FF.usuario_id_asignacion = ".$usuario." ";

			$sql .= "GROUP BY FF.prefijo,FF.factura_fiscal,FF.sw_estado,FF.total_factura,PL.plan_descripcion,SU.nombre,fecha_factura ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$cont = 0;
			$datos = array();
			if(!$rst->EOF) $cont = $rst->RecordCount();

			if($cont > 0)
			{
				$this->ProcesarSqlConteo("",$cont,$offset);
				$sql .= "ORDER BY FF.sw_estado,FF.prefijo,FF.factura_fiscal ";
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				while (!$rst->EOF)
				{
					$datos[$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				
				$rst->Close();
			}
			
			return $datos;
		}
		/********************************************************************************
		* @return 
		*********************************************************************************/
		function ObtenerPrefijosFacturas($empresa,$estado)
		{
			$sql  = "SELECT	FF.prefijo "; 
			$sql .= "FROM 	fac_facturas FF,"; 
			$sql .= "				fac_log_movimientos FL "; 
			$sql .= "WHERE 	FF.empresa_id = '".$empresa."' "; 
			$sql .= "AND		FF.sw_clase_factura = '1'::bpchar "; 
			$sql .= "AND		FF.tipo_factura IN ('1','2') ";  
			$sql .= "AND		FF.sw_estado IN (".$estado.") "; 
			$sql .= "AND		FL.sw_estado IN (".$estado.") "; 
			$sql .= "AND		FF.empresa_id = FL.empresa_id  "; 
			$sql .= "AND		FF.prefijo = FL.prefijo  "; 
			$sql .= "AND		FF.factura_fiscal = FL.factura_fiscal ";  
			$sql .= "GROUP BY FF.prefijo ";
			$sql .= "ORDER BY FF.prefijo ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$datos = array();			
			
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			$rst->Close();
						
			return $datos;
		}
		/********************************************************************************
		* @return 
		*********************************************************************************/
		function ActualizarFacturas($datos,$empresa,$usuario,$grupo,$estado)
		{
			foreach($datos as $key => $prefijo)
			{
				foreach($prefijo as $keyI => $factura)
				{
					$sql  = "UPDATE	fac_facturas ";
					$sql .= "SET		sw_estado = '".$estado."',";
					$sql .= "				observacion_movimiento = '', ";
					$sql .= "				fac_grupo_id_asignacion = ".$grupo.", ";
					$sql .= "				usuario_id_asignacion = ".$usuario." ";
					if(trim($estado) == "	3")
					{
						$sql .= "				,fac_grupo_id_recepcion = ".$grupo.", ";
						$sql .= "				usuario_id_recepcion = ".$usuario." ";
					}
					
					$sql .= "WHERE 		empresa_id = '".$empresa."' ";
					$sql .= "AND 			prefijo = '".$key."' ";
					$sql .= "AND 			factura_fiscal = ".$keyI." ;";

					if(!$rst = $this->ConexionBaseDatos($sql))	return false;
				}
			}
			return true;
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
		function ProcesarSqlConteo($consulta,$num_reg = null,$offset=null,$limite=null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
				if(!$this->limit) $this->limit = 20;
			}
			else
			{
				$this->limit = $limite;
			}

			if($offset)
			{
				$this->paginaActual = intval($offset);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}

			if(!$num_reg)
			{
				if(!$result = $this->ConexionBaseDatos($consulta))
					return false;

				if(!$result->EOF)
				{
					$this->conteo = $result->fields[0];
					$result->MoveNext();
				}
				$result->Close();
			}
			else
			{
				$this->conteo = $num_reg;
			}
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