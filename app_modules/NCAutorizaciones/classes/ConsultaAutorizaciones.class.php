<?php
	/**************************************************************************************
	* $Id: ConsultaAutorizaciones.class.php,v 1.6 2007/06/04 16:12:18 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.6 $ 	
	* @author Hugo Freddy Manrique Arango
	***************************************************************************************/
	class ConsultaAutorizaciones
	{
		function ConsultaAutorizaciones(){}
		/************************************************************************************ 
		* Funcion domde se seleccionan los tipos de id de los pacientes
		* 
		* @return array datos de tipo_id_terceros 
		*************************************************************************************/
		function ObtenerTipoIdPaciente()
		{
			$sql  = "SELECT tipo_id_paciente, ";
			$sql .= "				descripcion ";
			$sql .= "FROM		tipos_id_pacientes ";
			$sql .= "ORDER BY indice_de_orden ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$tipos = array();
			while (!$rst->EOF)
			{
				$tipos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $tipos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerIngresoCuenta($numrodecuenta)
		{
			$sql 	= "SELECT	ingreso ";
			$sql .= "FROM		cuentas ";
			$sql .= "WHERE	numerodecuenta = ".$numrodecuenta." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			$tipos = array();
			if (!$rst->EOF)
			{
				$ingreso = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $ingreso['ingreso'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerAutorizaciones($datos,$tipo)
		{
			$contar = true;
			$sql  = "SELECT IG.ingreso, ";
			$sql .= "				COALESCE(PL.plan_descripcion,'PLAN NO ESPECIFICADO') AS plan_descripcion, ";			
			$sql .= "				AU.autorizacion, ";
			$sql .= "				AU.observaciones, ";
			$sql .= "				AU.tipo_autorizador, ";
			$sql .= "				AU.codigo_autorizacion, ";
			$sql .= "				AU.codigo_autorizacion_generador, ";
			$sql .= "				AU.descripcion_autorizacion, ";
			$sql .= "				TO_CHAR(AU.fecha_registro,'DD/MM/YYYY HH24:MI') AS fecha, ";
			$sql .= "				TO_CHAR(AU.fecha_vencimiento,'DD/MM/YYYY') AS fecha_validez, ";
			$sql .= "				SU.nombre AS responsable, ";
			$sql .= "				AC.descripcion AS clase_autorizacion, ";
			$sql .= "				AT.descripcion AS tipo_autorizacion, ";
			$sql .= "				PA.primer_apellido||' '||PA.segundo_apellido AS apellidos, ";
			$sql .= "				PA.primer_nombre||' '||PA.segundo_nombre AS nombres, ";
			$sql .= "				PA.tipo_id_paciente, ";
			$sql .= "				PA.paciente_id ";
			$sql .= "FROM		autorizaciones AU ";
			$sql .= "				LEFT JOIN planes PL ";
			$sql .= "				ON(AU.plan_id = PL.plan_id), ";
			$sql .= "				ingresos IG, ";
			$sql .= "				pacientes PA, ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				autorizaciones_clases AC, ";
			$sql .= "				autorizaciones_tipos AT ";
			$sql .= "WHERE	AU.ingreso = IG.ingreso ";
			$sql .= "AND		AU.usuario_id = SU.usuario_id ";
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";
			$sql .= "AND		AU.clase_autorizacion = AC.clase_autorizacion ";
			$sql .= "AND		AU.tipo_autorizacion = AT.tipo_autorizacion ";
			if($tipo)
				$sql .= "AND		AU.clase_autorizacion NOT IN (".$tipo.") ";
			
			if($datos['ingreso'])
			{
				$contar = false;
				$sql .= "AND		IG.ingreso = ".$datos['ingreso']." ";
			}

							
			$sql .= "ORDER BY AU.autorizacion ";
			$cont = 0;
			if($contar)
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) 
					return false;
				
				if(!$rst->EOF) $cont = $rst->RecordCount();
			}				
			
			if($cont > 0 && $contar)
			{
				if(!$rst = $this->ProcesarSqlConteo($sql,$cont,$datos['offset'])) return false;
			
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			}
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$autorizaciones = array();
			while(!$rst->EOF)
			{
				$autorizaciones[$rst->fields[0]][$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $autorizaciones;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerAutorizacionesLista($datos)
		{
			$sql  = "SELECT IG.ingreso, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				PA.primer_apellido||' '||PA.segundo_apellido AS apellidos, ";
			$sql .= "				PA.primer_nombre||' '||PA.segundo_nombre AS nombres, ";
			$sql .= "				PA.tipo_id_paciente, ";
			$sql .= "				PA.paciente_id, ";
			$sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/MM/YYYY HH:MI AM') AS fecha ";
			$sql .= "FROM		cuentas CU, ";
			$sql .= "				planes PL, ";
			$sql .= "				ingresos IG, ";
			$sql .= "				(	SELECT	COUNT(*) AS cont,";
			$sql .= "									ingreso ";
			$sql .= "					FROM	 	autorizaciones ";
			$sql .= "					GROUP BY ingreso";
			$sql .= "				)AS AU, ";
			$sql .= "				pacientes PA ";
			$sql .= "WHERE	IG.ingreso = CU.ingreso ";
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";
			$sql .= "AND		AU.ingreso = IG.ingreso ";
			//$sql .= "AND		AU.cont > 0 ";
			$sql .= "AND		CU.plan_id = PL.plan_id ";
			if($datos['documento'])
			{
				$sql .= "AND		IG.tipo_id_paciente = '".$datos['tipodocumento']."' ";
				$sql .= "AND		IG.paciente_id = '".$datos['documento']."' ";
			}
			else
			{
				$sql .= "AND		".$this->FiltrarNombres($datos['nombres'],$datos['apellidos'],"PA")." ";
			}
		
			$cont = 0;
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
			if(!$rst->EOF) $cont = $rst->RecordCount();
			$ingresos = array();
			
			if($cont > 0)
			{
				if(!$rst = $this->ProcesarSqlConteo($sql,$cont,$datos['offset'])) return false;
				
				$sql .= "ORDER BY IG.ingreso ";				
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				while(!$rst->EOF)
				{
					$ingresos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
			}
			return $ingresos;
		}
		/**********************************************************************************
		* Funcion donde se obtienen los datos de las autorizaciones realizadas sobre las 
		* ordenes de servicio realizads sobre el ingreso
		*
		* $returns $datos array Datos de las ordenes de servicio 
		***********************************************************************************/
    function ObtenerAutizacionesOS($ingreso,$datos_paciente)
		{
			$sql .= "SELECT	AU.autorizacion, ";
			$sql .= "				COALESCE(PL.plan_descripcion,'PLAN NO ESPECIFICADO') AS plan_descripcion, ";			
			$sql .= "				SU.nombre AS responsable, ";
			$sql .= "				OS.sw_estado, ";
			$sql .= "				OS.orden_servicio_id, ";
			$sql .= "				DE.descripcion AS deptno_descripcion, ";
			$sql .= "				AU.autorizacion, ";
			$sql .= "				AU.observaciones, ";
			$sql .= "				AU.tipo_autorizador, ";
			$sql .= "				AU.codigo_autorizacion, ";
			$sql .= "				AU.codigo_autorizacion_generador, ";
			$sql .= "				AU.descripcion_autorizacion, ";
			$sql .= "				TO_CHAR(AU.fecha_registro,'DD/MM/YYYY HH24:MI') AS fecha, ";
			$sql .= "				TO_CHAR(AU.fecha_vencimiento,'DD/MM/YYYY') AS fecha_validez, ";
			$sql .= "				AC.descripcion AS clase_autorizacion, ";
			$sql .= "				AT.descripcion AS tipo_autorizacion ";
			$sql .= "FROM		os_ordenes_servicios OS, ";
			$sql .= "				autorizaciones AU ";
			$sql .= "				LEFT JOIN planes PL ";
			$sql .= "				ON(AU.plan_id = PL.plan_id), ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				departamentos DE, ";
			$sql .= "				autorizaciones_clases AC, ";
			$sql .= "				autorizaciones_tipos AT ";
			$sql .= "WHERE	OS.autorizacion_int = AU.autorizacion  ";
			$sql .= "AND		AU.usuario_id = SU.usuario_id ";
			$sql .= "AND		OS.fecha_registro::date >= '".$datos_paciente['registro_ingreso']."' ";
			$sql .= "AND		OS.autorizacion_int != 1 ";
			$sql .= "AND		OS.tipo_id_paciente = '".$datos_paciente['tipo_id_paciente']."' ";
			$sql .= "AND		OS.paciente_id = '".$datos_paciente['paciente_id']."' ";
			$sql .= "AND		OS.departamento IS NOT NULL ";
			$sql .= "AND		OS.departamento = DE.departamento ";
			$sql .= "AND		AU.clase_autorizacion = AC.clase_autorizacion ";
			$sql .= "AND		AU.tipo_autorizacion = AT.tipo_autorizacion ";
			$sql .= "AND		AU.clase_autorizacion IN ('OS') ";
			
			$this->requestoff = $offset;
			
			//if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $where",25))
			//	return false;
			
			$sql .= $where;
			$sql .= "ORDER BY AU.autorizacion ";
			//$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$ingreso][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
    * Metodo para obtener los datos de un paciente ingresado
    *
    * @param string $ingreso
    * @return array
    ***********************************************************************************/
    function ObtenerDatosPaciente($ingreso)
    {
			$sql  = "SELECT	A.ingreso,"; 
			$sql .= "				C.primer_apellido||' '||C.segundo_apellido AS apellido, "; 
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre AS nombre, "; 
			$sql .= "				TO_CHAR(C.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento,"; 
			$sql .= "				C.residencia_direccion,"; 
			$sql .= "				C.residencia_telefono, "; 
			$sql .= "				C.tipo_id_paciente, ";
			$sql .= "				C.paciente_id, ";
			$sql .= "				D.estado AS cuentaestado, "; 
			$sql .= "				D.numerodecuenta, "; 
			$sql .= "				D.tercero_id, "; 
			$sql .= "				D.tipo_id_tercero,"; 
			$sql .= "				D.nombre_tercero, "; 
			$sql .= "				D.rango,";
			$sql .= "				D.semanas_cotizadas,";
			$sql .= "				D.plan_descripcion, "; 
			$sql .= "				D.plan_id, "; 
			$sql .= "				D.tipo_afiliado_nombre,";
			$sql .= "				D.tipo_afiliado_id,";
			$sql .= "				A.estado, "; 
			$sql .= "				D.desc_estado, "; 
			$sql .= "				A.fecha_registro::date AS registro_ingreso, "; 
			$sql .= "				TO_CHAR(A.fecha_registro,'DD/MM/YYYY HH:MI AM') AS fecha_registro, ";
			$sql .= "				TO_CHAR(A.fecha_ingreso,'DD/MM/YYYY HH:MI AM') AS fecha_ingreso ";			
			$sql .= "FROM		ingresos A  LEFT JOIN ";
			$sql .= "				(	SELECT	TA.tipo_afiliado_nombre,";
			$sql .= "									CU.ingreso,";
			$sql .= "									CU.numerodecuenta,";
			$sql .= "									CU.estado,";
			$sql .= "									CU.rango,";
			$sql .= "									CU.semanas_cotizadas,";
			$sql .= "									CU.tipo_afiliado_id,";
			$sql .= "									PL.plan_id, ";
			$sql .= "									PL.plan_descripcion, ";
			$sql .= "									TE.tercero_id, "; 
			$sql .= "									TE.tipo_id_tercero,"; 
			$sql .= "									TE.nombre_tercero,  "; 
			$sql .= "									CE.descripcion  AS desc_estado "; 
			$sql .= "					FROM		cuentas CU LEFT JOIN ";
			$sql .= "				 					tipos_afiliado TA "; 
			$sql .= "									ON(	TA.tipo_afiliado_id = CU.tipo_afiliado_id),";
			$sql .= "									planes PL, ";
			$sql .= "									terceros TE, ";
			$sql .= "									cuentas_estados CE ";
			$sql .= "				 	WHERE		CU.plan_id = PL.plan_id ";
			$sql .= "				 	AND			CU.ingreso = ".$ingreso." ";	
			$sql .= "				 	AND			CU.estado = CE.estado ";	
			$sql .= "					AND			TE.tercero_id = PL.tercero_id ";
			$sql .= "					AND			TE.tipo_id_tercero = PL.tipo_tercero_id ";
			$sql .= "				) AS D ";
			$sql .= "				ON(	D.ingreso = A.ingreso ), ";				
			$sql .= "				pacientes C ";
			$sql .= "WHERE	A.tipo_id_paciente = C.tipo_id_paciente ";
			$sql .= "AND		A.paciente_id = C.paciente_id "; 
			$sql .= "AND		A.ingreso = ".$ingreso." ";
			$sql .= "AND		A.ingreso = D.ingreso ";
			$sql .= "ORDER BY D.numerodecuenta ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$ingreso = array();
			while (!$rst->EOF)
			{
				$ingreso[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return array("cuentas"=>$ingreso,"ingreso"=>$ingreso[0]);
    }
		/**********************************************************************************
    * Metodo para obtener los datos de un paciente ingresado
    *
    * @param string $ingreso
    * @return array
    ***********************************************************************************/
    function ObtenerCuentasIngreso($ingreso)
    {
			$cuentas = array();
			if(!$ingreso)
				$cuentas['conteo'] = 0;
			else
			{
				$sql  = "SELECT	COUNT(*) AS conteo ";
				$sql .= "FROM		cuentas ";
				$sql .= "WHERE	ingreso = ".$ingreso." ";
				$sql .= "AND		estado <> '5' ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				while (!$rst->EOF)
				{
					$cuentas = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
			}
			return $cuentas;
    }
		/**********************************************************************************
		*
		***********************************************************************************/
		function FiltrarNombres($nombres,$apellidos,$alias)
		{
			$nombres = strtoupper($nombres);
			$apellidos = strtoupper($apellidos);
			
			if ($nombres != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$nombres));//QUITA DOBLE ESPACIOS INTERNOS

				switch(count($a))
				{
					case 1:
						$filtroNombres .= " ($alias.primer_nombre  SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)' OR $alias.segundo_nombre SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)')";
					break;
					case 2:
						$filtroNombres  = " $alias.primer_nombre SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						next($a);
						$filtroNombres .= " AND (($alias.primer_nombre SIMILAR TO '%[[:space:]]".current($a)."') OR ($alias.segundo_nombre ILIKE '".current($a)."'))";
					break;
					default:
						$filtroNombres = " $alias.primer_nombre SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						for($i=2;$i<count($a);$i++)
						{
							next($a);
							$filtroNombres .= " AND (($alias.primer_nombre SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																	OR ($alias.segundo_nombre SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
						}
						next($a);
						$filtroNombres .= " AND (($alias.primer_nombre SIMILAR TO '%[[:space:]]".current($a)."')  OR  ($alias.segundo_nombre SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
					break;
				}
			}

			if ($apellidos != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$apellidos));

				switch(count($a))
				{
					case 1:
							$filtroApellidos  = " $alias.primer_apellido ILIKE '".current($a)."'";
					break;

					case 2:
							$filtroApellidos  = " $alias.primer_apellido SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							next($a);
							$filtroApellidos .= " AND (($alias.primer_apellido SIMILAR TO '%[[:space:]]".current($a)."') OR ($alias.segundo_apellido ILIKE '".current($a)."'))";
					break;

					default:
							$filtroApellidos  = " $alias.primer_apellido SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							for($i=2;$i<count($a);$i++)
							{
								next($a);
								$filtroApellidos .= " AND (($alias.primer_apellido SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																						OR ($alias.segundo_apellido SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
							}
							next($a);
							$filtroApellidos .= " AND (($alias.primer_apellido SIMILAR TO '%[[:space:]]".current($a)."')  OR  ($alias.segundo_apellido SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
					break;
				}
			}

			if(!empty($filtroNombres))
			{
				if(!empty($filtroApellidos))
				{
					$filtroPrincipalTipo2= $filtroNombres ." AND ".$filtroApellidos;
				}
				else
				{
					$filtroPrincipalTipo2 = $filtroNombres;
				}
			}
			else
			{
				if(!empty($filtroApellidos))
				{
					$filtroPrincipalTipo2 = $filtroApellidos;
				}
			}
			return $filtroPrincipalTipo2;
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
		/***************************************************************************************
		* Funcion donde se obtiene el nombre de un usuario
		* @param int $usuario Identificacion del usuario
		****************************************************************************************/
		function ObtenerInformacionUsuario($usuario)
		{
			$sql .= "SELECT	nombre ";
			$sql .= "FROM		system_usuarios "; 
			$sql .= "WHERE	usuario_id = ".$usuario." ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			if(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		* Funcion donde se obtienen los planes que pertenecen a una empresa y estan activos
		* @params $empresa Character Identificador de la empresa
		* 
		* @return array Arreglo de datos de los planes activos 
		************************************************************************************/
		function ObtenerPlanes($plan = null)
    {
			$sql  = "SELECT A.plan_id, ";
			$sql .= "				A.plan_descripcion, ";
			$sql .= "				A.sw_tipo_plan, ";
			$sql .= "				A.sw_afiliacion, ";
			$sql .= "				A.sw_autoriza_sin_bd ";
			$sql .= "FROM 	planes A ";
			$sql .= "WHERE 	A.fecha_final >= NOW() ";
			$sql .= "AND 		A.estado = '1' ";
			$sql .= "AND 		A.fecha_inicio <= NOW() ";
			
			if($plan)
				$sql .= "AND 		A.plan_id = ".$plan." ";
			
			$sql .= "ORDER BY A.plan_descripcion ";
			
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
		/**********************************************************************************
		* 
		* @return array Arreglo de datos de los planes activos 
		************************************************************************************/
		function ObtenerPlanesRestringido($datos)
    {
			$sql  = "SELECT PL.plan_id, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				PL.sw_tipo_plan ";
			$sql .= "FROM 	planes PL, ";
			$sql .= "				userpermisos_centro_autorizacion_planes UP ";
			$sql .= "WHERE 	PL.fecha_final >= NOW() ";
			$sql .= "AND 		PL.estado = '1' ";
			$sql .= "AND 		PL.fecha_inicio <= NOW() ";
			$sql .= "AND 		PL.plan_id = UP.plan_id ";
			$sql .= "AND 		UP.empresa_id = '".$datos['empresa_id']."' ";
			$sql .= "AND 		UP.usuario_id = ".$datos['usuario_id']." ";			
			$sql .= "ORDER BY PL.plan_descripcion ";
			
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
				//echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
	}
?>