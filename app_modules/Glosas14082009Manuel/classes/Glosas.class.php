<?php
  /******************************************************************************
  * $Id: Glosas.class.php,v 1.5 2009/03/19 20:07:27 cahenao Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.5 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	
	class Glosas
	{
		function Glosas(){}
		/***************************************************************************************
		*
		****************************************************************************************/
		function ObtenerGlosas($datos,$empresa,$opcion)
		{	
			$terceros = SessionGetVar("TercerosGlosas");
			
			$sql .= "SELECT F.tipo_id_tercero,";
			$sql .= "				F.tercero_id, ";	
			$sql .= "				G.glosa_id,";
			$sql .= "				G.usuario_id,";
			$sql .= "				G.auditor_id,";
			$sql .= "				G.valor_glosa,";
			$sql .= "				F.sistema, ";
			$sql .= "				F.prefijo, ";
			$sql .= "				F.factura_fiscal, ";
			$sql .= "				F.total_factura, ";
			$sql .= "				F.saldo, ";
			$sql .= "				SU.nombre, ";
			$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "				TO_CHAR(G.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa, ";
			$sql .= "				G.fecha_glosa AS fecha, ";
			$sql .= "				G.sw_estado, ";
			$sql .= "				ED.envio_id, ";
			$sql .= "				ED.fecha_radicacion ";
			$sql .= "FROM 	view_fac_facturas ";
			$sql .= "				AS F LEFT JOIN ";
			$sql .= "				(	SELECT	ED.prefijo, ";
			$sql .= "									ED.factura_fiscal, ";
			$sql .= "									TO_CHAR(E.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion, ";
			$sql .= "									E.envio_id, ";
			$sql .= "									E.sw_estado, ";
			$sql .= "									ED.empresa_id ";
			$sql .=	"					FROM 		envios_detalle ED, ";
			$sql .= "									envios E ";
			$sql .= "					WHERE		ED.envio_id = E.envio_id  ";
			if ($datos['tipo_documento'] == '0')
				$sql .= "					AND		E.sw_estado = '1'::bpchar ";
				
			$sql .= "					AND			ED.empresa_id = '".$empresa."') AS ED ";
			$sql .= "				ON( ED.prefijo = F.prefijo AND ";
			$sql .= "						ED.factura_fiscal = F.factura_fiscal AND ";
			$sql .= "						ED.empresa_id = F.empresa_id ) ";
			$sql .= "				LEFT JOIN glosas G ";
			$sql .= "				ON(	G.prefijo = F.prefijo AND G.factura_fiscal = F.factura_fiscal AND ";	
			$sql .= "						G.empresa_id = F.empresa_id) ";
			$sql .= "				LEFT JOIN system_usuarios SU ";
			$sql .= "				ON(G.auditor_id = SU.usuario_id) "; 
			
			//if($datos['nombre_tercero'])
				//$sql .= "				,terceros TE ";
			
			if($opcion == "0" )	
				$sql .= "	,userpermisos_glosas_clientes G ";
			
			$sql .= "WHERE 	COALESCE(ED.sw_estado,'0') IN ('0'::bpchar,'1'::bpchar) ";
			$sql .= "AND		F.empresa_id = '".$empresa."' ";
			$sql .= "AND	 	F.estado = '0'::bpchar ";
			
			switch($datos['estado_glosa'])
			{
				case '1':	$sql .= "AND 		G.sw_estado IN ('1'::bpchar,'2'::bpchar) ";	break;
				case '2': $sql .= "AND 		G.sw_estado = '2'::bpchar "; break;
				case '3':	$sql .= "AND 		G.sw_estado = '1'::bpchar "; break;
				case '4':	$sql .= "AND 		G.sw_estado = '0'::bpchar "; break;
				case '5':	$sql .= "AND 		G.sw_estado = '3'::bpchar "; break;
				default:	$sql .= "AND 		COALESCE(G.sw_estado,'1') IN ('1'::bpchar,'2'::bpchar) ";		break;
			}
			
			$sql .= "AND		F.saldo > 0 ";
			
			if($datos['tipo_id_tercero']!= '0' && $datos['tipo_id_tercero'] != "")
				$sql .= "AND F.tipo_id_tercero  = '".$datos['tipo_id_tercero']."' ";
			
			if($datos['tercero_id'] != "")
				$sql .= "AND F.tercero_id LIKE '".$datos['tercero_id']."' ";
			
			if($datos['nombre_tercero'])
			{
				$tid = $ttp = "";
				foreach($terceros as $key => $id)
				{
					foreach($id as $keyI => $nombre)
					{
						if(substr_count(strtoupper($nombre),strtoupper($datos['nombre_tercero'])) > 0)
						{
							($tid == "" )? $tid .= "'".$keyI."'": $tid .= ",'".$keyI."'";
							($ttp == "" )? $ttp .= "'".$key."'": $ttp .= ",'".$key."'";
						}
					}
				}
				if($tid == "")	return array();
				
				$sql .= "AND		F.tercero_id IN (".$tid.") ";
				$sql .= "AND		F.tipo_id_tercero IN (".$ttp.") ";
				
				//$sql .= "AND 		TE.nombre_tercero ILIKE '%".$datos['nombre_tercero']."%' ";
				//$sql .= "AND		TE.tercero_id = F.tercero_id ";
				//$sql .= "AND		TE.tipo_id_tercero = F.tipo_id_tercero ";
			}
			
			$tipoDc = "0";
			if ($datos['tipo_documento'])	$tipoDc = $datos['tipo_documento'];

			switch($tipoDc)
			{
				case '0':
					$sql .= "AND ED.sw_estado = '1'::bpchar ";
				break;
				case '1':case '2':
					$where .= "AND coalesce(ED.envio_id,0) != 0 ";
				break;
				case '3':
					$sql .= "AND coalesce(ED.envio_id,0) = 0 ";
				break;
			}
			
			switch($tipoDc)
			{
				case '0':case '1':case '2':
					
					if($datos['fecha_inicio'] != "")
					{
						$arr = explode("/",$datos['fecha_inicio']);
						$sql .= "AND ED.fecha_radicacion::date >= '".$arr[2]."-".$arr[1]."-".$arr[0]."' ";
					}
					if($datos['fFin'] != "")
					{
						$arr = explode("/",$datos['fecha_fin']);
						$sql .= "AND ED.fecha_radicacion::date <= '".$arr[2]."-".$arr[1]."-".$arr[0]."' ";
					}
				break;
				case '3': case '4':
					if($datos['fecha_inicio'] != "")
					{
						$arr = explode("/",$datos['fecha_inicio']);
						$sql .= "AND F.fecha_registro::date >= '".$arr[2]."-".$arr[1]."-".$arr[0]."' ";
					}
					if($datos['fecha_fin'] != "")
					{
						$arr = explode("/",$datos['fecha_fin']);
						$sql .= "AND F.fecha_registro::date <= '".$arr[2]."-".$arr[1]."-".$arr[0]."' ";
					}
				break;	
			}

			if($opcion == "0" )
			{
				$sql .= "AND 	 G.empresa_id = '".$empresa."' ";
				$sql .= "AND 	 G.usuario_id = ".UserGetUID()." ";
				$sql .= "AND 	 G.tercero_id = F.tercero_id ";
				$sql .= "AND 	 G.tipo_id_tercero = F.tipo_id_tercero ";
			}
			
			if($datos['numero'])
				$sql .= "AND ED.envio_id = '".$datos['numero']."' ";	
			
			if($datos['auditor_sel'])
				$sql .= "AND		G.auditor_id = ".$datos['auditor']." ";
			
			if(!$datos['cantidad'])
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				$datos['cantidad'] = $rst->RecordCount();
			}
			
			$this->ProcesarSqlConteo("",$datos['offset'],$datos['cantidad']);
			
			$sql .= "ORDER BY G.glosa_id DESC ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if($datos['cantidad'] > 0)
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				while (!$rst->EOF)
				{
					$glosas[$terceros[$rst->fields[0]][$rst->fields[1]]][] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
			  }
				$rst->Close();			
			}
			return $glosas;
		}
		/***************************************************************************************
		*
		****************************************************************************************/
		function ObtenerGlosasPorFactura($datos,$empresa_id,$sw_clientes)
		{
			$terceros = SessionGetVar("TercerosGlosas");
			
			$sql .= "SELECT 	F.tipo_id_tercero,";
			$sql .= "					F.tercero_id, ";			
			$sql .= "					F.prefijo, ";			
			$sql .= "					F.factura_fiscal, ";
			$sql .= "					F.total_factura, "; 
			$sql .= "					GL.sw_estado, ";
			$sql .= "					GL.glosa_id, ";
			$sql .= "					TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "					CASE 	WHEN coalesce(E.envio_id,0) = 0 THEN 0 ";
			$sql .= "								ELSE E.envio_id END  AS envio_id, ";
			$sql .= "					CASE 	WHEN E.fecha_radicacion IS NULL THEN '0' ";
			$sql .= "								ELSE TO_CHAR(E.fecha_radicacion,'DD/MM/YYYY') END AS fecha_radicacion, ";
			$sql .= "					F.sistema, ";
			$sql .= "					F.saldo ";			
			$sql .= "FROM 	view_fac_facturas ";
			$sql .= "				AS F LEFT JOIN ";
			$sql .= "				(	SELECT 	E.envio_id, ";
			$sql .= "									E.fecha_radicacion, ";
			$sql .= "									E.sw_estado, ";
			$sql .= "									ED.empresa_id, ";
			$sql .= "									ED.prefijo, ";
			$sql .= "									ED.factura_fiscal ";
			$sql .= "					FROM		envios_detalle ED, ";
			$sql .= "									envios E ";
			$sql .= "					WHERE		ED.envio_id = E.envio_id ";	
			$sql .= "					AND			E.sw_estado != '2'::bpchar ";			
			$sql .= "					AND 	 	ED.prefijo = '".$datos['prefijo_factura']."' ";			
			$sql .= "					AND			ED.empresa_id = '".$empresa_id."' ";	
			if($datos['factura_fiscal'] != "")
				$sql .= "				AND			ED.factura_fiscal = ".$datos['factura_fiscal']." ";
			
			$sql .= "				) AS E ";		
			$sql .= "				ON( E.prefijo = F.prefijo AND ";
			$sql .= "						E.factura_fiscal = F.factura_fiscal AND ";
			$sql .= "						E.empresa_id = F.empresa_id) ";
			$sql .= "				LEFT JOIN glosas GL ";
			$sql .= "					ON(GL.prefijo = F.prefijo AND ";
			$sql .= "						 GL.factura_fiscal = F.factura_fiscal AND ";			
			$sql .= "						 GL.empresa_id = F.empresa_id AND ";
			$sql .= "						 GL.sw_estado IN ('1','2') ";
			$sql .= "				) ";

			if($sw_clientes == "0")
				$sql .= "		,userpermisos_glosas_clientes G ";
						
			$sql .= "WHERE 	COALESCE(E.sw_estado,'0') IN ('0','1','3') ";
			$sql .= "AND		F.empresa_id = '".$empresa_id."' ";
			$sql .= "AND	 	F.estado = '0'::bpchar ";
			$sql .= "AND 	 	F.prefijo = '".$datos['prefijo_factura']."' ";

			if($datos['factura_fiscal'] != "")
				$sql .= "AND F.factura_fiscal = ".$datos['factura_fiscal']." ";
				
			if($sw_clientes == "0")
			{
				$sql .= "AND 	 G.empresa_id = '".$empresa_id."' ";
				$sql .= "AND 	 G.usuario_id = ".UserGetUID()." ";
				$sql .= "AND 	 G.tercero_id = F.tercero_id ";
				$sql .= "AND 	 G.tipo_id_tercero = F.tipo_id_tercero ";
			}
			
			if(!$datos['cantidad'])
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				$datos['cantidad'] = $rst->RecordCount();
			}
			
			$glosas = array();
			
			if($datos['cantidad'] > 0)
			{
				$this->ProcesarSqlConteo("",$datos['offset'],$datos['cantidad']);
				
				$sql .= "ORDER BY GL.glosa_id DESC ";
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				while (!$rst->EOF)
				{
					$glosas[$terceros[$rst->fields[0]][$rst->fields[1]]][] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
			  }
				$rst->Close();			
			}
			
			return $glosas;
		}
		/************************************************************************************ 
		* Funcion donde se seleccionan el nombre de los terceros que son clientes y 
		* tienen envios radicados para que se pueda filtrar por ellos en una busqueda  
		* 
		* @return array datos de tipo_id_terceros 
		*************************************************************************************/
		function ObtenerNombresTerceros()
		{
			$sql	= "SELECT nombre_tercero, ";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id ";
			$sql .= "FROM		terceros TE ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$nombre = array();
			while(!$rst->EOF)
			{
				$nombre[$rst->fields[1]][$rst->fields[2]] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
						
			return $nombre;
		}
		/***************************************************************************************
		*
		****************************************************************************************/
		function ObtenerGlosasReporte($datos,$empresa,$opcion)
		{			
			$sql .= "SELECT F.tipo_id_tercero,";
			$sql .= "				F.tercero_id, ";	
			$sql .= "				G.glosa_id,";
			$sql .= "				G.usuario_id,";
			$sql .= "				G.auditor_id,";
			$sql .= "				G.valor_glosa,";
			$sql .= "				F.sistema, ";
			$sql .= "				F.prefijo, ";
			$sql .= "				F.factura_fiscal, ";
			$sql .= "				F.total_factura, ";
			$sql .= "				SU.nombre, ";
			$sql .= "				TO_CHAR(G.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa, ";
			$sql .= "				G.fecha_glosa AS fecha, ";
			$sql .= "				GM.motivo_glosa_descripcion ";
			$sql .= "FROM 	view_fac_facturas ";
			$sql .= "				AS F LEFT JOIN ";
			$sql .= "				(	SELECT	ED.prefijo, ";
			$sql .= "									ED.factura_fiscal, ";
			$sql .= "									E.fecha_radicacion, ";
			$sql .= "									E.envio_id, ";
			$sql .= "									E.sw_estado ";
			$sql .=	"					FROM 		envios_detalle ED, ";
			$sql .= "									envios E ";
			$sql .= "					WHERE		ED.envio_id = E.envio_id  ";
			$sql .= "					AND			ED.empresa_id = '".$empresa."') AS ED ";
			$sql .= "				ON( ED.prefijo = F.prefijo AND ";
			$sql .= "						ED.factura_fiscal = F.factura_fiscal ) ";
			$sql .= "				LEFT JOIN glosas G ";
			$sql .= "				ON(	G.prefijo = F.prefijo AND G.factura_fiscal = F.factura_fiscal AND ";	
			$sql .= "						G.empresa_id = F.empresa_id) ";
			$sql .= "				LEFT JOIN system_usuarios SU ";
			$sql .= "				ON(G.auditor_id = SU.usuario_id)";
			$sql .= "				LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON(GM.motivo_glosa_id = G.motivo_glosa_id) "; 
			//if($datos['nombre_tercero'])
			//	$sql .= "				,terceros TE ";
			
			if($opcion == "0" )	
				$sql .= "	,userpermisos_glosas_clientes G ";
			
			$sql .= "WHERE 	COALESCE(ED.sw_estado,'0') IN ('0','1') ";
			$sql .= "AND		F.empresa_id = '".$empresa."' ";
			$sql .= "AND	 	F.estado = '0' ";
			
			switch($datos['estado_glosa'])
			{
				case '1': $sql .= "AND 		G.sw_estado IN ('1'::bpchar,'2'::bpchar) ";	break;
				case '2': $sql .= "AND 		G.sw_estado = '2'::bpchar "; break;
				case '3':	$sql .= "AND 		G.sw_estado = '1'::bpchar "; break;
				case '4':	$sql .= "AND 		G.sw_estado = '0'::bpchar "; break;
				case '5':	$sql .= "AND 		G.sw_estado = '3'::bpchar "; break;
			}
			
			$sql .= "AND		F.saldo > 0 ";
			
			if($datos['tipo_id_tercero']!= '0' && $datos['tipo_id_tercero'] != "")
				$sql .= "AND F.tipo_id_tercero  = '".$datos['tipo_id_tercero']."' ";
			
			if($datos['tercero_id'] != "")
				$sql .= "AND F.tercero_id LIKE '".$datos['tercero_id']."' ";
			
			$terceros = SessionGetVar("TercerosGlosas");
			if($datos['nombre_tercero'])
			{
				$tid = $ttp = "";
				foreach($terceros as $key => $id)
				{
					foreach($id as $keyI => $nombre)
					{
						if(substr_count(strtoupper($nombre),strtoupper($datos['nombre_tercero'])) > 0)
						{
							($tid == "" )? $tid .= "'".$keyI."'": $tid .= ",'".$keyI."'";
							($ttp == "" )? $ttp .= "'".$key."'": $ttp .= ",'".$key."'";
						}
					}
				}
				if($tid == "")	return array();
				
				$sql .= "AND		F.tercero_id IN (".$tid.") ";
				$sql .= "AND		F.tipo_id_tercero IN (".$ttp.") ";
				
				//$sql .= "AND 		TE.nombre_tercero ILIKE '%".$datos['nombre_tercero']."%' ";
				//$sql .= "AND		TE.tercero_id = F.tercero_id ";
				//$sql .= "AND		TE.tipo_id_tercero = F.tipo_id_tercero ";
			}
			
			$tipoDc = "0";
			if ($datos['tipo_documento'])	$tipoDc = $datos['tipo_documento'];

			switch($tipoDc)
			{
				case '1':case '2':
					$where .= "AND coalesce(ED.envio_id,0) != 0 ";
				break;
				case '3':
						$sql .= "AND coalesce(ED.envio_id,0) = 0 ";
				break;
			}
			
			switch($tipoDc)
			{
				case '0':case '1':case '2':
					
					if($datos['fecha_inicio'] != "")
					{
						$arr = explode("/",$datos['fecha_inicio']);
						$sql .= "AND ED.fecha_radicacion::date >= '".$arr[2]."-".$arr[1]."-".$arr[0]." 00:00:00' ";
					}
					if($datos['fFin'] != "")
					{
						$arr = explode("/",$datos['fecha_fin']);
						$sql .= "AND ED.fecha_radicacion::date <= '".$arr[2]."-".$arr[1]."-".$arr[0]." 00:00:00' ";
					}
				break;
				case '3': case '4':
					if($datos['fecha_inicio'] != "")
					{
						$arr = explode("/",$datos['fecha_inicio']);
						$sql .= "AND F.fecha_registro::date >= '".$arr[2]."-".$arr[1]."-".$arr[0]." 00:00:00' ";
					}
					if($datos['fecha_fin'] != "")
					{
						$arr = explode("/",$datos['fecha_fin']);
						$sql .= "AND F.fecha_registro::date <= '".$arr[2]."-".$arr[1]."-".$arr[0]." 00:00:00' ";
					}
				break;	
			}

			if($opcion == "0" )
			{
				$sql .= "AND 	 G.empresa_id = '".$empresa."' ";
				$sql .= "AND 	 G.usuario_id = ".UserGetUID()." ";
				$sql .= "AND 	 G.tercero_id = F.tercero_id ";
				$sql .= "AND 	 G.tipo_id_tercero = F.tipo_id_tercero ";
			}
			
			if($datos['numero'])
				$sql .= "AND ED.envio_id = '".$datos['numero']."' ";	
			
			if($datos['auditor_sel'])
				$sql .= "AND		G.auditor_id = ".$datos['auditor']." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$sql .= "ORDER BY 1 DESC ";
			
			
			while (!$rst->EOF)
			{
				$glosas[$terceros[$rst->fields[0]][$rst->fields[1]]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $glosas;
		}
		/***************************************************************************************************
		* 
		* @return array
		****************************************************************************************************/
		function ObtenerNumeroCuenta($datos)
		{	
			$sql  = "SELECT C.numerodecuenta ";
			$sql .= "FROM		fac_facturas_cuentas F,";
			$sql .= "				cuentas C ";
			$sql .= "WHERE 	F.empresa_id = '".$datos['empresa_id']."' ";
			$sql .= "AND 		F.prefijo = '".$datos['prefijo']."' ";
			$sql .= "AND		F.factura_fiscal = ".$datos['factura_fiscal']." ";
			$sql .= "AND 		C.numerodecuenta = F.numerodecuenta ";
			$sql .= "AND 		C.estado != '5'::bpchar ";
					
			if(!$rst= $this->ConexionBaseDatos($sql))	return false;
				
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		/*****************************************************************************
		* Funcion donde se cuentan el numero total de cuentas y cargos glosados de 
		* una factura 
		* 
		* @param  int numeor de la glosa 
		* @return array datos de las cuentas, cargos e insumos de la factura  
		******************************************************************************/
		function ObtenerCantidadGlosas($glosa,$factura_fiscal,$prefijo)
		{
			$sql  = "SELECT A.cont + B.cont AS cargos_insumos_activo, ";
			$sql .= "				A1.cont + B1.cont AS cargos_insumos_cierre, ";
			$sql .= "				C.cont AS cuentas_glosa, ";
			$sql .= "				D.cont AS cuentas_numero ";
			$sql .= "FROM		(	SELECT 	COUNT(*) AS cont ";
			$sql .= "					FROM    glosas_detalle_cargos ";
			$sql .= "					WHERE   glosa_id = ".$glosa." ";
			$sql .= "					AND 		sw_estado IN ('1','2')";
			$sql .= "				) AS A,";
			$sql .= "     	(	SELECT 	COUNT(*) AS cont";
			$sql .= "					FROM    glosas_detalle_inventarios";
			$sql .= "					WHERE   glosa_id = ".$glosa."  ";
			$sql .= "					AND 		sw_estado IN ('1','2')";
			$sql .= "				) AS B,";
			$sql .= "				(	SELECT 	COUNT(*) AS cont ";
			$sql .= "					FROM    glosas_detalle_cargos ";
			$sql .= "					WHERE   glosa_id = ".$glosa." ";
			$sql .= "					AND 		sw_estado IN ('3')";
			$sql .= "				) AS A1,";
			$sql .= "     	(	SELECT 	COUNT(*) AS cont";
			$sql .= "					FROM    glosas_detalle_inventarios";
			$sql .= "					WHERE   glosa_id = ".$glosa."  ";
			$sql .= "					AND 		sw_estado IN ('3')";
			$sql .= "				) AS B1,";
			$sql .= "     	(	SELECT 	COUNT(*) AS cont ";
			$sql .= "					FROM    glosas_detalle_cuentas ";
			$sql .= "					WHERE   glosa_id = ".$glosa."  ";
			$sql .= "					AND 		sw_estado IN ('1','2')";
			$sql .= "      		AND 		sw_glosa_total_cuenta = '1'";
			$sql .= "				) AS C, ";
			$sql .= "     	(	SELECT COUNT(*) AS cont ";
			$sql .= "     		FROM 		fac_facturas_cuentas FC,";
			$sql .= "     						cuentas CU";
			$sql .= "     		WHERE 	FC.prefijo = '".$prefijo."'";
			$sql .= "     		AND 		FC.factura_fiscal = ".$factura_fiscal." ";
			$sql .= "     		AND			FC.numerodecuenta = CU.numerodecuenta";
			$sql .= "     		AND			CU.estado != '5'::bpchar ";
			$sql .= "				) AS D ";
		
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/**********************************************************************************
		*
		************************************************************************************/
		function ObtenerUsuarioNombre($id)
		{
			$sql  = "SELECT nombre FROM system_usuarios WHERE usuario_id = ".$id;
				
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			if (!$rst->EOF)
			{
				$UsuarioNombre = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			return $UsuarioNombre;
	 	}
		/****************************************************************************************
		* Funcion mediante la cual se buscan los cargos glosados de las cuentas pertenecientes 
		* a una factura 
		* 
		* @param string identificador de la glosa 
		* @return array datos de los cargos glosados  
		*****************************************************************************************/
		function ObtenerCargosGlosados($glosaId,$estado)
		{
			$sql  = "SELECT	C.numerodecuenta, ";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				CASE	WHEN GC.sw_glosa_total_cuenta = '0' ";
			$sql .= " 						THEN GC.valor_glosa_copago + GC.valor_glosa_cuota_moderadora ";
			$sql .= "							WHEN GC.sw_glosa_total_cuenta = '1' THEN C.total_cuenta END AS valor_glosa,";
			$sql .= "				PA.tipo_id_paciente||' '||PA.paciente_id AS asociado, ";
			$sql .= "				PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido AS descripcion_asociado, ";
			$sql .= "				CASE WHEN GC.sw_glosa_total_cuenta = '0' THEN 'DA' ";
			$sql .= "		    		 WHEN GC.sw_glosa_total_cuenta = '1' THEN 'DT' END AS tipo ";
			$sql .= "FROM	planes P, ";
			$sql .= "			ingresos I,";
			$sql .= "			pacientes PA,"; 
			$sql .= "			cuentas C,";
			$sql .= "			glosas_detalle_cuentas GC LEFT JOIN glosas_motivos GM ";
			$sql .= "			ON(GM.motivo_glosa_id = GC.motivo_glosa_id) ";
			$sql .= "WHERE	GC.glosa_id = ".$glosaId." ";
			$sql .= "AND 		C.numerodecuenta = GC.numerodecuenta ";
			$sql .= "AND 		C.ingreso = I.ingreso ";
			$sql .= "AND 		I.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND 		I.paciente_id = PA.paciente_id ";
			$sql .= "AND 		C.plan_id = P.plan_id ";
			$sql .= "AND		GC.sw_estado = '".$estado."' ";
			$sql .= "UNION  ";
			$sql .= "SELECT	CD.numerodecuenta, ";
			$sql .= "				GM.motivo_glosa_descripcion, ";
			$sql .= "				GC.valor_glosa, ";
			$sql .= "				CD.cargo_cups AS asociado,  ";
			$sql .= "				TD.descripcion AS descripcion_asociado, ";
			$sql .= "				'DC' AS tipo ";
			$sql .= "FROM 	glosas_detalle_cargos GC, ";
			$sql .= "		cuentas_detalle CD, ";
			$sql .= "		glosas_motivos GM,";
			$sql .= "		glosas_detalle_cuentas GD, ";
			$sql .= "		tarifarios_detalle TD ";
			$sql .= "WHERE 	GC.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 	GC.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 	GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	GC.transaccion = CD.transaccion ";
			$sql .= "AND 	GC.sw_estado = '".$estado."' ";
			$sql .= "AND 	GD.sw_estado = '".$estado."' ";
			$sql .= "AND 	GC.glosa_id = ".$glosaId." ";
			$sql .= "AND 	TD.cargo = CD.cargo ";
			$sql .= "AND 	TD.tarifario_id = CD.tarifario_id ";
			$sql .= "UNION ";
			$sql .= "SELECT CD.numerodecuenta, ";
			$sql .= "				GM.motivo_glosa_descripcion, ";
			$sql .= "				GI.valor_glosa, ";
			$sql .= "				ID.codigo_producto AS asociado, ";
			$sql .= "				ID.descripcion AS descripcion_asociado, ";
			$sql .= "				'DI' AS tipo ";
			$sql .= "FROM 	glosas_detalle_inventarios GI, ";
			$sql .= "		cuentas CD, ";
			$sql .= "		glosas_motivos GM, ";
			$sql .= "		glosas_detalle_cuentas GD, ";
			$sql .= "		inventarios_productos ID ";
			$sql .= "WHERE	GI.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 	GI.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 	GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	GI.glosa_id = ".$glosaId." ";
			$sql .= "AND	GI.codigo_producto = ID.codigo_producto ";
			$sql .= "AND 	GI.sw_estado = '".$estado."' ";
			$sql .= "AND 	GD.sw_estado = '".$estado."' ";
			$sql .= "AND 	GD.glosa_id = GI.glosa_id ";
			$sql .= "ORDER BY tipo ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$cargos = array();
			while (!$rst->EOF)
			{
				$cargos[$rst->fields[0]][$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $cargos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function IngresarGlosaCuenta($datos)
		{
			if(!$datos['mayor_valor']) $datos['mayor_valor'] = 0;
			if(!$datos['menor_valor']) $datos['menor_valor'] = 0;
		
			if(!$datos['glosa_cuota_moderadora'])	$datos['cantidad_cuota_moderadora'] = 0;
			if(!$datos['glosa_copago'])	$datos['cantidad_copago'] = 0;
			if(!$datos['glosa_cuenta'])	$datos['glosa_cuenta'] = 0;
			
			if(!$datos['detalle_cuenta'])
			{
				$sql = "SELECT NEXTVAL('glosas_detalle_cuentas_glosa_detalle_cuenta_id_seq') ";
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				$secuencia = $rst->fields[0];
				$rst->MoveNext();
				$rst->Close();
			
				$sql  = "INSERT INTO glosas_detalle_cuentas( ";
				$sql .= "			glosa_id,";
				$sql .= "			numerodecuenta,";
				$sql .= "			valor_glosa_copago,";
				$sql .= "			valor_glosa_cuota_moderadora,";
				if($datos['glosa_cuenta'] == 1)
					$sql .= "			valor_glosa,";
				
				$sql .= "			mayor_valor,";
				$sql .= "			menor_valor,";
				$sql .= " 		sw_glosa_total_cuenta,";
				$sql .= "			sw_estado,";
				$sql .= " 		usuario_id,";
				$sql .= "			fecha_registro, ";
				$sql .= "			motivo_glosa_id, ";
				$sql .= "			observacion, ";
				$sql .= "			glosa_detalle_cuenta_id) ";
				$sql .= "VALUES(".$datos['datos_glosa']['glosa_id'].",";
				$sql .= "				".$datos['datos_glosa']['numerodecuenta'].",";
				$sql .= "				".$datos['cantidad_copago'].",";
				$sql .= "				".$datos['cantidad_cuota_moderadora'].",";
				if($datos['glosa_cuenta'] == 1)
				{
					$sql .= "				(	SELECT 	total_cuenta ";
					$sql .= "					FROM 		cuentas ";
					$sql .= "					WHERE 	numerodecuenta = ".$datos['datos_glosa']['numerodecuenta']." ";
					$sql .= "					AND 		empresa_id = '".$datos['datos_glosa']['empresa_id']."' ),";
				}
				$sql .= "				".$datos['mayor_valor'].",";
				$sql .= "				".$datos['menor_valor'].",";
				$sql .= "				'".$datos['glosa_cuenta']."',";
				$sql .= "	   		'1',";
				$sql .= "				".UserGetUID().",";
				$sql .= "		  	NOW(),";
				$sql .= "				'".$datos['motivos_glosa']."',";				
				$sql .= "				'".$datos['observacion_glosa']."',";				
				$sql .= "				".$secuencia.");";
			}
			else
			{
				$sql  = "UPDATE glosas_detalle_cuentas ";
				$sql .= "SET		valor_glosa_copago = ".$datos['cantidad_copago'].",";
				$sql .= "				valor_glosa_cuota_moderadora = ".$datos['cantidad_cuota_moderadora'].", ";
				$sql .= "				motivo_glosa_id = '".$datos['motivos_glosa']."', ";
				$sql .= "				observacion = '".$datos['observacion_glosa']."' ";
				$sql .= "WHERE	glosa_detalle_cuenta_id = ".$datos['detalle_cuenta']." ";
				$sql .= "AND		glosa_id = ".$datos['datos_glosa']['glosa_id']."; ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function IngresarGlosaCargosInsumos($datos,$observaciones)
		{
			if(!$datos['auditor']) $datos['auditor'] = "NULL";
			if(!$datos['mayor_valor']) $datos['mayor_valor'] = 0;
			if(!$datos['menor_valor']) $datos['menor_valor'] = 0;
			$secuencia = $datos['detalle_cuenta'];
			$set = "";
			if($datos['auditor'] <> 'S' AND $datos['auditor'] <> ''){$set = ",auditor_id = ".$datos['auditor']."";}
			
			if(!$datos['detalle_cuenta'])
			{
				$sql = "SELECT NEXTVAL('glosas_detalle_cuentas_glosa_detalle_cuenta_id_seq') ";
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				$secuencia = $rst->fields[0];
				$rst->MoveNext();
				$rst->Close();
			
				$codigo_concepto_general = "NULL";
				$codigo_concepto_especifico = "NULL";

				$dat = explode("||//",$datos[concepto_especifico]);
				if($dat[1])
				{
					$codigo_concepto_general = "'".$dat[0]."'";
					$codigo_concepto_especifico = "'".$dat[1]."'";
				}
				
				$sql  = "INSERT INTO glosas_detalle_cuentas( ";
				$sql .= "			glosa_id,";
				$sql .= "			numerodecuenta,";
				$sql .= "			mayor_valor,";
				$sql .= "			menor_valor,";
				$sql .= " 		sw_glosa_total_cuenta,";
				$sql .= "			sw_estado,";
				$sql .= " 		usuario_id,";
				$sql .= "			fecha_registro, ";
				$sql .= "			glosa_detalle_cuenta_id, ";
				$sql .= "			codigo_concepto_general, ";
				$sql .= "			codigo_concepto_especifico) ";
				$sql .= "VALUES(".$datos['datos_glosa']['glosa_id'].",";
				$sql .= "				".$datos['datos_glosa']['numerodecuenta'].",";
				$sql .= "				".$datos['mayor_valor'].",";
				$sql .= "				".$datos['menor_valor'].",";
				$sql .= "				'0',";
				$sql .= "	   		'1',";
				$sql .= "				".UserGetUID().",";
				$sql .= "		  	NOW(),";
				$sql .= "				".$secuencia.", ";	
				$sql .= "		  	".$codigo_concepto_general.", ";
				$sql .= "			  ".$codigo_concepto_especifico." );";
			}
			else
			{
				$sql  = "UPDATE glosas_detalle_cuentas ";
				$sql .= "SET		sw_glosa_total_cuenta = '0' $set ";
				$sql .= "WHERE	glosa_detalle_cuenta_id = ".$datos['detalle_cuenta']." ";
				$sql .= "AND		glosa_id = ".$datos['datos_glosa']['glosa_id']."; ";
			}

			$this->ConexionTransaccion();
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			
			$sql = "";
			foreach($datos['cargos'] as $key => $transaccion)
			{
				foreach($transaccion as $keyI => $cargos)
				{
					if($cargos['checbox'])
					{
						$cargos[motivo_glosa] = '-1';
						$codigo_concepto_general = "-1";
						$codigo_concepto_especifico = "-1";
						$dat = explode("||//",$cargos[conceptos]);
						if($dat[1])
						{
							$codigo_concepto_general = $dat[0];
							$codigo_concepto_especifico = $dat[1];
						}
						$campo = $value = "";
						if($datos['auditor'] <> 'S' AND $datos['auditor'] <> '')
						{
							$campo = "auditor_id,";
							$value = " ".$datos['auditor'].",";
						}
						$sql .= "INSERT INTO glosas_detalle_cargos( ";
						$sql .= "			glosa_id,";
						$sql .= "			transaccion,";
						$sql .= "			motivo_glosa_id,";
						$sql .= "			observacion,";
						$sql .= "			valor_glosa,";
						$sql .= 			$campo;
						$sql .= "			sw_estado,";
						$sql .= "			usuario_id,";
						$sql .= "			fecha_registro,";
						$sql .= "			glosa_detalle_cuenta_id, ";
						$sql .= "			codigo_concepto_general, ";
						$sql .= "			codigo_concepto_especifico) ";
						$sql .= "VALUES( ".$datos['datos_glosa']['glosa_id'].",";
						$sql .= "				 ".$key.",";
						$sql .= "	   		'".$cargos['motivo_glosa']."',";
						$sql .= "	   		'".utf8_decode($observaciones['cargo_'.$key])."',";
						$sql .= "				 ".$cargos['valor_glosa'].",";
						$sql .=						$value;
						$sql .= "	   		'1',";
						$sql .= "				 ".UserGetUID().",";
						$sql .= "		  	 NOW(),";
						$sql .= "				 ".$secuencia.", ";
						$sql .= "		  	 '".$codigo_concepto_general."',";
						$sql .= "			'".$codigo_concepto_especifico."' );";
					}
				}
			}

			if($sql != "")
				if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
				
			$sql = "";
			foreach($datos['insumos'] as $key => $insumos)
			{
				if($insumos['checbox'])
				{
					$insumos['motivo_glosa'] = '-1';
					$codigo_concepto_general = "-1";
					$codigo_concepto_especifico = "-1";
						
					$dat = explode("||//",$insumos[conceptos]);
					if($dat[1])
					{
						$codigo_concepto_general = $dat[0];
						$codigo_concepto_especifico = $dat[1];
					}
					$campo = $value = "";
					if($datos['auditor'] <> 'S' AND $datos['auditor'] <> '')
					{
						$campo = "auditor_id,";
						$value = " ".$datos['auditor'].",";
					}
					$sql .= "INSERT INTO glosas_detalle_inventarios( ";
					$sql .= "			glosa_id,";
					$sql .= "			codigo_producto,";
					$sql .= "			motivo_glosa_id,";
					$sql .= "			observacion,";
					$sql .= "			valor_glosa,";
					$sql .= 			$campo;
					$sql .= "			sw_estado,";
					$sql .= "			usuario_id,";
					$sql .= "			fecha_registro ,";
					$sql .= "			glosa_detalle_cuenta_id, ";
					$sql .= "			codigo_concepto_general, ";
					$sql .= "			codigo_concepto_especifico) ";
					$sql .= "VALUES( ".$datos['datos_glosa']['glosa_id'].",";
					$sql .= "				'".$key."',";
					$sql .= "	   		'".$insumos['motivo_glosa']."',";
					$sql .= "	   		'".utf8_decode($observaciones['insumo_'.$key])."',";
					$sql .= "				 ".$insumos['valor_glosa'].",";
					$sql .= 				 $value;
					$sql .= "	   		'1',";
					$sql .= "				 ".UserGetUID().",";
					$sql .= "		  	 NOW(),";
					$sql .= "				 ".$secuencia.", ";
					$sql .= "		  	 '".$codigo_concepto_general."',";
					$sql .= "			'".$codigo_concepto_especifico."' );";
				}	
			}
			
			if($sql != "")
				if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			
			$this->dbconn->CommitTrans();
			return true;
		}
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b><br>".$sql;
				return false;
			}
			return $rst;
		}
		/****************************************************************************************
		* Funcion donde se obtienen las glosas que una factura ha tenido
		*****************************************************************************************/
		function ObtenerGlosasAnteriores($prefijo,$numero,$empresa)
		{
			$sql .= "SELECT GL.glosa_id,";
			$sql .= "				TO_CHAR(GL.fecha_registro,'DD/MM/YYYY') AS registro,  ";
			$sql .= "				GL.valor_glosa, ";
			$sql .= "				GL.valor_aceptado, ";
			$sql .= "				GL.valor_no_aceptado, ";
			$sql .= "				GM.motivo_glosa_descripcion, ";
			$sql .= "				SU.nombre, ";
			$sql .= "				CG.descripcion_concepto_general, ";
			$sql .= "				CE.descripcion_concepto_especifico ";
			$sql .= "FROM 	glosas GL ";
			$sql .= "				LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON(GL.motivo_glosa_id = GM.motivo_glosa_id) ";
			$sql .= "				LEFT JOIN system_usuarios SU ";
			$sql .= "				ON(GL.auditor_id = SU.usuario_id) ";
			
			$sql .= "				LEFT JOIN glosas_concepto_general CG ";
			$sql .= "				ON(CG.codigo_concepto_general = GL.codigo_concepto_general) ";
			$sql .= "				LEFT JOIN glosas_concepto_especifico CE ";
			$sql .= "				ON(CE.codigo_concepto_especifico = GL.codigo_concepto_especifico) ";
			$sql .= "WHERE 	GL.prefijo = '".$prefijo."' ";
			$sql .= "AND 		GL.factura_fiscal = ".$numero." ";
			$sql .= "AND	 	GL.empresa_id = '".$empresa."' ";
			$sql .= "AND		GL.sw_estado = '3'::bpchar ";
			$sql .= "ORDER BY GL.glosa_id ";
			
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
		
		/****************************************************************************************
		* Funcion donde se obtienen las glosas que una factura ha tenido
		*****************************************************************************************/
		function ObtenerConceptosGenerales()
		{
			$sql .= "SELECT DISTINCT GCG.codigo_concepto_general, GCG.descripcion_concepto_general, ";
			$sql .= "	CGE.codigo_concepto_especifico, CGE.descripcion_concepto_especifico ";
			$sql .= "FROM 	glosas_concepto_general GCG, ";
			$sql .= "	glosas_concepto_especifico CGE, ";
			$sql .= "	glosas_concepto_general_especifico GCGE ";
			$sql .= "WHERE GCG.codigo_concepto_general = GCGE.codigo_concepto_general ";
			$sql .= "AND GCGE.codigo_concepto_especifico = CGE.codigo_concepto_especifico ";
			$sql .= "AND CGE.codigo_concepto_especifico <> '-1'; ";
			
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
		/****************************************************************************************
		* Funcion donde se obtienen las glosas que una factura ha tenido
		*****************************************************************************************/
		function ActualizarValor($detalle_cuenta,$valor,$campo)
		{
			$sql .= "UPDATE glosas_detalle_cuentas ";
			$sql .= "SET		$campo = $valor ";
			$sql .= "WHERE 	glosa_detalle_cuenta_id = $detalle_cuenta ";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			return true;
		}
		/**********************************************************************************
		* Funcion que permite crear una transaccion 
		* @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
		* @param char $num Numero correspondiente a la sentecia sql - por defect es 1
		*
		* @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
		*								 se devuelve nada
		***********************************************************************************/
		function ConexionTransaccion($sql,$num = '1')
		{
			if(!$sql)
			{
				list($this->dbconn) = GetDBconn();
				
				$this->dbconn->BeginTrans();
			}
			else
			{
				$rst = $this->dbconn->Execute($sql);
				if ($this->dbconn->ErrorNo() != 0)
				{
					$this->frmError['MensajeError'] = "ERROR DB : " . $this->dbconn->ErrorMsg();
					echo "<b class=\"label\">Trasaccion: $num - ".$this->frmError['MensajeError']."</b><br>".$sql;
					$this->dbconn->RollbackTrans();
					return false;
				}
				return $rst;
			}
		}
		/******************************************************************************* 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		********************************************************************************/
		function ProcesarSqlConteo($sqlCont,$offset=null,$cant=null,$limite=null)
		{
			$this->paginaActual = 1;
			$this->offset = 0;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
				if(!$this->limit)	$this->limit = 20;
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
			if(!$cant)
			{
				if(!$result = $this->ConexionBaseDatos($sqlCont))
					return false;
	
				if(!$result->EOF)
				{
					$this->conteo = $result->fields[0];
					$result->MoveNext();
					$result->Close();
				}
			}
			else
			{
				$this->conteo = $cant;
			}
			return true;
		}
	}
?>