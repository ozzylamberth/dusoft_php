<?php
	/**************************************************************************************
	* $Id: AtencionOs.class.php,v 1.1 2010/01/20 20:58:30 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F. Manrique
	***************************************************************************************/
	class AtencionOs
	{
		/**********************************************************************************
		* Funcion donde se valida si un usuario determinado tiene o no permisos para el 
		* uso del modulo de OS_Central_atencion
		* @params $usuario Integer Identificador del usuario
		*
		* @return array Arreglo de datos con los permisos del usuario 
		************************************************************************************/
	  function BuscarPermisosOs($usuario)
    {
			$sql  = "SELECT	C.departamento,";
			$sql .= "				C.descripcion as dpto,";
			$sql .= "				D.descripcion as centro,";
			$sql .= "				D.centro_utilidad,";
			$sql .= "				E.empresa_id,";
			$sql .= "				E.razon_social as emp,";
			$sql .= "				B.usuario_id,";
			$sql .= "				B.sw_solo_cumplimiento,";
			$sql .= "				B.sw_honorario ";
			$sql .= "FROM		userpermisos_os_atencion B,";
			$sql .= "				departamentos C, ";
			$sql .= "				centros_utilidad D,";
			$sql .= "				empresas E ";
			$sql .= "WHERE	B.usuario_id = ".$usuario." ";
			$sql .= "AND		C.departamento = B.departamento ";
			$sql .= "AND		D.centro_utilidad = C.centro_utilidad ";
			$sql .= "AND		E.empresa_id = D.empresa_id ";
			$sql .= "AND		E.empresa_id = C.empresa_id ";
			//$sql .= "ORDER BY centro"; //jab
			$sql .= "ORDER BY dpto";
			//echo $sql;
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[5]][$rst->fields[2]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
		/**********************************************************************************
		* Funcion donde se obtuenen los tipos de identifiacion de los pacientes
		* 
		* @return array Datos de los tipo de identificaciones de los pacientes 
		************************************************************************************/
		function TiposIdPaciente()
    {
			$sql  = "SELECT tipo_id_paciente,";
			$sql .= "				descripcion ";
			$sql .= "FROM		tipos_id_pacientes ";
			$sql .= "ORDER BY indice_de_orden ";
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
		* Funcion donde se obtienen los planes que pertenecen a una empresa y estan activos
		* @params $empresa Character Identificador de la empresa
		* 
		* @return array Arreglo de datos de los planes activos 
		************************************************************************************/
		function Responsables($empresa,$tipo)
    {
			$sql  = "SELECT A.plan_id, ";
			$sql .= "				A.plan_descripcion, ";
			$sql .= "				A.sw_tipo_plan ";
/* 		$sql .= "				A.tercero_id, ";
			$sql .= "				A.tipo_tercero_id "; */
			$sql .= "FROM 	planes A ";
			$sql .= "WHERE 	A.fecha_final >= NOW() ";
			$sql .= "AND 		A.estado = '1' ";
			$sql .= "AND 		A.fecha_inicio <= NOW() ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
			
			if($tipo)	$sql .= "AND		sw_tipo_plan != '".$tipo."' ";
				
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
		* Funcion donde se Obtuienen las ordenes que se han hecho y estan por pagar o
		* por cumplir 
		* @params $departamentos String Identificador del departamento
		* @params $datos Arreglo de datos del buscador
		* @return array Arreglo de datos de las ordenes 
		************************************************************************************/
		function BuscarOrdenes($departamento,$datos)
		{
			$pac = "";
			if($datos['nombres1'])
				$pac .= "									AND	primer_nombre ILIKE '%".$datos['nombres1']."%' ";
			
			if($datos['nombres2'])
				$pac .= "									AND	segundo_nombre ILIKE '%".$datos['nombres2']."%' ";
				
			if($datos['apellidos1'])
				$pac .= "									AND	primer_apellido ILIKE '%".$datos['apellidos1']."%' ";
				
			if($datos['apellidos1'])
				$pac .= "									AND	segundo_apellido ILIKE '%".$datos['apellidos2']."%' ";

			if($datos['tipoDocumento'] != '-1')
				$pac .= "			 						AND	tipo_id_paciente = '".$datos['tipoDocumento']."' ";

			if($datos['responsable'] != '-1')
				$sol .= "									AND			B.plan_id = '".$datos['responsable']."' ";

			$sql .= "SELECT	A.paciente_id,";
			$sql .= "				A.tipo_id_paciente,";
			$sql .= "				A.nombre,";
			$sql .= "				A.apellido,";
			$sql .= "				SUM(A.por_pago) AS por_pago,";
			$sql .= "				SUM(A.por_cumplir) AS por_cumplir,";
			$sql .= "				SUM(A.por_atencion) AS por_atencion,";
			$sql .= "				SUM(A.solicitudes) AS solicitudes, ";
			$sql .= "				A.plan_id ";
			$sql .= "FROM		( ";
			$sql .= "			    ( ";
			$sql .= "						SELECT	A.tipo_id_paciente, ";
			$sql .= "										A.paciente_id, ";
			$sql .= "										A.nombre, ";
			$sql .= "										A.apellido, ";
			$sql .= "										CASE WHEN (B.sw_estado = '1') THEN 1 ELSE 0 END AS por_pago, ";
			$sql .= "										CASE WHEN (B.sw_estado = '2') THEN 1 ELSE 0 END AS por_cumplir, ";
			$sql .= "										CASE WHEN (B.sw_estado = '3') THEN 1 ELSE 0 END AS por_atencion, ";
			$sql .= "										0 AS solicitudes, ";
			$sql .= "										B.plan_id ";
			$sql .= "						FROM	( ";
			$sql .= "										SELECT	tipo_id_paciente, ";
			$sql .= "														paciente_id, ";
			$sql .= "														primer_nombre||' '||segundo_nombre AS nombre,";
			$sql .= "														primer_apellido||' '||segundo_apellido AS apellido ";
			$sql .= "										FROM		pacientes  ";
			$sql .= "										WHERE 	TRUE	".$pac;
			$sql .= "									) AS A, ";
			$sql .= "									os_ordenes_servicios B ";
			//$sql .= "									os_maestro C ";
			$sql .= "						WHERE	B.departamento = '".$departamento."' ";
			$sql .= "						AND		B.sw_estado IN('1','2') ";
			$sql .= "						AND		B.fecha_activacion <= NOW() ";
			$sql .= "						AND		B.fecha_vencimiento >= '".date('Y-m-d')."' ";
			$sql .= "						AND		B.tipo_id_paciente = A.tipo_id_paciente ";
			$sql .= "						AND		B.paciente_id = A.paciente_id ".$sol;
			$sql .= "			    ) ";
			$sql .= "			    UNION DISTINCT ";
			$sql .= "			    ( ";
			$sql .= "			    	SELECT  A.tipo_id_paciente,";
			$sql .= "			    					A.paciente_id,";
			$sql .= "			    					A.nombre, ";
			$sql .= "			    					A.apellido, ";
			$sql .= "			    					0 AS por_pago, ";
			$sql .= "			    					0 AS por_cumplir,";
			$sql .= "			    					0 AS por_atencion,";
			$sql .= "			    					1 AS solicitudes, ";
			$sql .= "			    					B.plan_id ";
			$sql .= "			    	FROM		( ";
			$sql .= "			    						SELECT	tipo_id_paciente, ";
			$sql .= "			    										paciente_id, ";
			$sql .= "			    										primer_nombre||' '||segundo_nombre AS nombre,";
			$sql .= "			    										primer_apellido||' '||segundo_apellido AS apellido ";
			$sql .= "			    						FROM		pacientes ";
			$sql .= "											WHERE 	TRUE	".$pac;
			$sql .= "										) AS A, ";
			$sql .= "										hc_os_solicitudes B, ";
			$sql .= "										departamentos_cargos E ";
			$sql .= "						WHERE 	B.paciente_id = A.paciente_id ";
			$sql .= "						AND 		B.tipo_id_paciente = A.tipo_id_paciente ";
			$sql .= "						AND 		B.sw_estado = '1' ";
			$sql .= "						AND 		E.departamento = '".$departamento."' ";
			$sql .= "						AND 		E.cargo = B.cargo ".$sol;
			$sql .= "					) ";
			$sql .= "				)AS A ";
			$sql .= "GROUP BY A.paciente_id,A.tipo_id_paciente,nombre,apellido,A.plan_id ";
			
			$this->registros = $datos['registros'];
			if(!$this->registros)
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				if(!$rst->EOF) $this->registros = $rst->RecordCount();
			}
			
			if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS MD ",$datos['offset']))
				return false;
			
			$sql .= "ORDER BY nombre,apellido ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

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
		/**********************************************************************************
		* Busqueda de las ordenes de servicio de un paciente por id
		*	@params char $departamento Codigo del departamento donde se buscaran las ordenes
		*	@params array $datos Arreglo de datos donde se encuentra el id del paciente
		*
		* @return array datos de las ordenes de servicio, (id,tipo_id,nombre,apellido...)
		************************************************************************************/
		function BuscarOrdenesPorId($departamento,$datos)
		{			
			$sql .= "SELECT A.paciente_id, ";
			$sql .= "				A.tipo_id_paciente, ";
			$sql .= "				B.primer_nombre||' '||B.segundo_nombre AS nombre, ";
			$sql .= "				B.primer_apellido||' '||B.segundo_apellido AS apellido, ";
			$sql .= "				SUM(A.por_pago) AS por_pago, ";
			$sql .= "				SUM(A.por_cumplir) AS por_cumplir, ";
			$sql .= "				SUM(A.por_atencion) AS por_atencion, ";
			$sql .= "				SUM(A.solicitudes) AS solicitudes, ";
			$sql .= "				A.plan_id ";
			$sql .= "FROM		( ";
			$sql .= "    			( ";
			$sql .= "    				SELECT	B.paciente_id, ";
			$sql .= "    								B.tipo_id_paciente, ";
			$sql .= "			   						CASE WHEN (B.sw_estado = '1') THEN 1 ELSE 0 END AS por_pago, ";
			$sql .= "			   						CASE WHEN (B.sw_estado = '2') THEN 1 ELSE 0 END AS por_cumplir, ";
			$sql .= "			   						CASE WHEN (B.sw_estado = '3') THEN 1 ELSE 0 END AS por_atencion, ";
			$sql .= "			   						0 AS solicitudes, ";
			$sql .= "			   						B.plan_id ";
			$sql .= "			   		FROM		os_ordenes_servicios B ";
			//$sql .= "			   	    	    os_maestro C ";
			$sql .= "			   		WHERE		B.departamento = '".$departamento."' ";
			$sql .= "			    	AND 		B.sw_estado IN('1','2') ";
			$sql .= "			 			AND 		B.fecha_activacion <= NOW() ";
			//$sql .= "			 			AND			B.fecha_vencimiento >= '".date('Y-m-d')."' ";
			$sql .= "			 			AND			B.paciente_id = '".$datos['documento']."' ";
			
			if($datos['tipoDocumento'] != '-1')
				$sql .= "			 			AND			B.tipo_id_paciente = '".$datos['tipoDocumento']."' ";
			if($datos['responsable'] != '-1')
				$sql .= "			 			AND			B.plan_id = '".$datos['responsable']."' ";

			$sql .= "			 		) ";
			$sql .= "			 		UNION ALL ";
			$sql .= "			 		( ";
			$sql .= "						SELECT 	B.paciente_id,"; 
			$sql .= "			 							B.tipo_id_paciente,";
			$sql .= "			 							0 AS por_pago,";
			$sql .= "			 							0 AS por_cumplir,";
			$sql .= "			 							0 AS por_atencion,";
			$sql .= "			 							1 AS solicitudes, ";
			$sql .= "			 							B.plan_id ";
			$sql .= "			 			FROM		hc_os_solicitudes B,";
			$sql .= "			 							departamentos_cargos E ";
			$sql .= "			 			WHERE 	B.paciente_id = '".$datos['documento']."' ";
			$sql .= "			 			AND 		B.sw_estado = '1' ";
			$sql .= "			 			AND 		E.cargo = B.cargo ";
			$sql .= "			 			AND 		E.departamento = '".$departamento."' ";
			
			if($datos['tipoDocumento'] != '-1')
				$sql .= "			 			AND			B.tipo_id_paciente = '".$datos['tipoDocumento']."' ";
			if($datos['responsable'] != '-1')
				$sql .= "			 			AND			B.plan_id = '".$datos['responsable']."' ";
				
			$sql .= "					)";
			$sql .= "				)AS A,";
			$sql .= "				pacientes B ";
			$sql .= "WHERE	A.tipo_id_paciente = B.tipo_id_paciente ";
			$sql .= "AND 		A.paciente_id = B.paciente_id ";
			$sql .= "GROUP BY A.paciente_id,A.tipo_id_paciente,nombre,apellido,A.plan_id ";			
			$sql .= "ORDER BY nombre,apellido ";
//echo $sql;
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$i] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		* @return rst 
		************************************************************************************/
		function BuscarOrdenesPorNumero($departamento,$datos)
		{
			$sql .= "SELECT A.paciente_id, ";
			$sql .= "				A.tipo_id_paciente, ";
			$sql .= "				A.nombre, ";
			$sql .= "				A.apellido, ";
			$sql .= "				SUM(A.por_pago) as por_pago, ";
			$sql .= "				SUM(A.por_cumplir) as por_cumplir, ";
			$sql .= "				SUM(A.por_atencion) as por_atencion, ";
			$sql .= "				SUM(A.solicitudes) as solicitudes, ";
			$sql .= "				A.plan_id ";
			$sql .= "FROM (";
			$sql .= "		SELECT	B.primer_nombre||' '||B.segundo_nombre AS nombre,";
			$sql .= "						B.primer_apellido||' '||B.segundo_apellido AS apellido,";
			$sql .= "						B.tipo_id_paciente,";
			$sql .= " 					B.paciente_id, ";
			$sql .= " 					CASE WHEN (A.sw_estado = '1') THEN 1 ELSE 0 END as por_pago, ";
			$sql .= " 					CASE WHEN (A.sw_estado = '2') THEN 1 ELSE 0 END as por_cumplir, ";
			$sql .= " 					CASE WHEN (A.sw_estado = '3') THEN 1 ELSE 0 END as por_atencion, ";
			$sql .= " 					0 AS solicitudes, ";
			$sql .= " 					A.plan_id ";
			$sql .= "		FROM		os_ordenes_servicios A,";
			$sql .= "						pacientes B";
			//$sql .= "						os_maestro C ";
			$sql .= "		WHERE		A.departamento = '".$departamento."' ";
			$sql .= "		AND 		A.sw_estado IN('1','2') ";
			$sql .= "		AND			A.fecha_activacion <= NOW() ";
			$sql .= "		AND			A.fecha_vencimiento >= '".date('Y-m-d')."' ";
			$sql .= "		AND			A.tipo_id_paciente = B.tipo_id_paciente ";
			$sql .= "		AND			A.paciente_id = B.paciente_id ";
			$sql .= "		AND			A.orden_servicio_id = ".$datos['numIngreso']." ";
			$sql .= "		) AS A ";
			$sql .= "GROUP BY A.paciente_id,A.tipo_id_paciente,nombre,apellido,A.plan_id ";
			$sql .= "ORDER BY nombre,apellido ";

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
		/**********************************************************************************
		* Busque de la lista de ordenes de servicio
		*	@paramas char $departamento Codigo del departamento donde se hara la busqueda de 
		*								de las ordenes de servicio
		*	@paramas array $datos Arrego donde se encuentra la informacion de la cantidad de registros 
		*									existentes
		*
		* @return array datos de las ordenes de servicio, (id,tipo_id,nombre,apellido...)
		***********************************************************************************/
		function ObtenerListadoOS($departamento,$datos)
		{
			$sql .= "SELECT	nombre,";
			$sql .= "				apellido,";
			$sql .= "				paciente_id,";
	    $sql .= "				tipo_id_paciente,";
	    $sql .= "				TO_CHAR(fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
	    $sql .= "				SUM(por_pago) AS por_pago,";
	    $sql .= "				SUM(por_cumplir) AS por_cumplir,";
	    $sql .= "				SUM(por_atencion) AS por_atencion, ";
	    $sql .= "				plan_id ";
	    $sql .= "FROM		( SELECT	P.primer_nombre||' '||P.segundo_nombre AS nombre,";
			$sql .= "									P.primer_apellido||' '||P.segundo_apellido AS apellido,";
			$sql .= "									A.paciente_id,";
	    $sql .= "									A.tipo_id_paciente,";
	    $sql .= "									A.fecha_registro,";
	    $sql .= "									CASE WHEN (A.sw_estado = '1') THEN 1 ELSE 0 END AS por_pago,";
	    $sql .= "									CASE WHEN (A.sw_estado = '2') THEN 1 ELSE 0 END AS por_cumplir,";
	    $sql .= "									CASE WHEN (A.sw_estado = '3') THEN 1 ELSE 0 END AS por_atencion,";
	    $sql .= "									A.plan_id ";
	    $sql .= "					FROM		os_ordenes_servicios A,";
	    $sql .= "    							servicios B,";
	    //$sql .= "    							os_maestro C, ";
	    $sql .= "    							pacientes P ";
	    $sql .= "					WHERE		A.servicio = B.servicio ";
	    $sql .= "					AND			A.departamento = '".$departamento."' ";
	    $sql .= "					AND			A.sw_atencion_interna = '0' ";
	    $sql .= "					AND			A.sw_estado IN('1','2') ";
	    $sql .= "					AND			A.fecha_activacion <= NOW() ";
	    $sql .= "					AND			A.fecha_vencimiento >= NOW() ";
			$sql .= "					AND			A.tipo_id_paciente = P.tipo_id_paciente ";
			$sql .= "					AND			A.paciente_id = P.paciente_id ";
	    $sql .= "					ORDER BY B.sw_prioridad DESC, A.fecha_registro,nombre,apellido ";
	    $sql .= "				) AS A ";
			$sql .= "GROUP BY nombre,apellido,paciente_id,tipo_id_paciente,fecha_registro,plan_id ";

			$this->registros = $datos['registros'];			
			if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS MD ",$datos['offset']))
				return false;
				
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

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
		/**********************************************************************************
		* Funcion donde se obtienen los datos del paciente y el ingreso (cuando el paciente 
		* esta ingresado).
		* @params $tipoid String Tipo de identificacion del paciente
		* @params $id String Identificacion del paciente
		*
		* @return $datos Arreglo de los datos del paciente
		**********************************************************************************/
		function ObtenerDatosPaciente($tipoid,$id)
		{
			$sql .= "SELECT	PA.primer_nombre||' '||PA.segundo_nombre AS nombre,";
			$sql .= "				PA.primer_apellido||' '||PA.segundo_apellido AS apellido,";
			$sql .= "				PA.tipo_id_paciente,";
			$sql .= "				PA.paciente_id, ";
			$sql .= "				IG.estado, ";
			$sql .= "				IG.ingreso ";
			$sql .= "FROM		pacientes PA ";
			$sql .= "				LEFT JOIN ingresos IG ";
			$sql .= "				ON( PA.tipo_id_paciente = IG.tipo_id_paciente AND";
			$sql .= "						PA.paciente_id = IG.paciente_id AND ";
			$sql .= "						IG.estado = '1' ) ";
			$sql .= "WHERE	PA.tipo_id_paciente = '".$tipoid."' ";
			$sql .= "AND		PA.paciente_id = '".$id."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		* @return rst 
		************************************************************************************/
		function ObtenerCamposObligatorios()
		{
			$sql  = "SELECT campo,";
			$sql .= "				sw_mostrar,";
			$sql .= "				sw_obligatorio ";
			$sql .= "FROM 	pacientes_campos_obligatorios ";
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
		* @return rst 
		************************************************************************************/
		function ObtenerHistoria($prefijo,$historia)
		{
			$sql  = "SELECT tipo_id_paciente, ";
			$sql .= "				paciente_id ";
			$sql .= "FROM		historias_clinicas ";
			$sql .= "WHERE	TRUE ";
			if($historia)
				$sql .= "AND		historia_numero = '".$historia."' ";
			if($prefijo)
				$sql .= "AND		historia_prefijo ILIKE '".$prefijo."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/**********************************************************************************
		* @return rst 
		************************************************************************************/
		function ObtenerPlanPaciente($tipodc,$doc)
		{
			$sql  = "SELECT	B.plan_id,";
			$sql .= "				C.plan_descripcion ";
			$sql .= "FROM		ingresos A, ";
			$sql .= "				cuentas B,";
			$sql .= "				planes C ";
			$sql .= "WHERE	A.tipo_id_paciente = 	'".$tipodc."' ";
			$sql .= "AND		A.paciente_id =	'".$doc."' ";
			$sql .= "AND		A.estado = '1'	";
			$sql .= "AND		A.ingreso = B.ingreso ";
			$sql .= "AND		B.estado IN (1,2) ";
			$sql .= "AND		B.plan_id = C.plan_id ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
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
    function GetDatosPaciente($ingreso,$tid,$id,$ubicacion = 1)
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
			$sql .= "				H.pais, "; 
			$sql .= "				H.departamento, "; 
			$sql .= "				H.municipio, "; 
			$sql .= "				TS.descripcion AS genero "; 
			$sql .= "FROM		ingresos A, ";
			$sql .= "				(	SELECT	TA.tipo_afiliado_nombre,";
			$sql .= "									CU.ingreso,";
			$sql .= "									CU.numerodecuenta,";
			$sql .= "									CU.estado,";
			$sql .= "									CU.rango,";
			$sql .= "									CU.semanas_cotizadas,";
			$sql .= "									PL.plan_id, ";
			$sql .= "									PL.plan_descripcion, ";
			$sql .= "									TE.tercero_id, "; 
			$sql .= "									TE.tipo_id_tercero,"; 
			$sql .= "									TE.nombre_tercero  "; 
			$sql .= "					FROM		cuentas CU LEFT JOIN ";
			$sql .= "				 					tipos_afiliado TA "; 
			$sql .= "									ON(	TA.tipo_afiliado_id = CU.tipo_afiliado_id),";
			$sql .= "									planes PL, ";
			$sql .= "									terceros TE ";
			$sql .= "				 	WHERE		CU.plan_id = PL.plan_id ";
			$sql .= "				 	AND			CU.estado IN ('1','2') ";
			$sql .= "				 	AND			CU.ingreso = ".$ingreso." ";	
			$sql .= "					AND			TE.tercero_id = PL.tercero_id ";
			$sql .= "					AND			TE.tipo_id_tercero = PL.tipo_tercero_id ";
			$sql .= "				) AS D, ";
			$sql .= "				pacientes C  LEFT JOIN ";
			$sql .= "				(	SELECT  MU.municipio, ";
			$sql .= "									DE.departamento, ";
			$sql .= "									PA.pais, ";
			$sql .= "									MU.tipo_pais_id, ";
			$sql .= "									MU.tipo_dpto_id, ";
			$sql .= "									MU.tipo_mpio_id ";
			$sql .= "					FROM		tipo_pais PA, ";
			$sql .= "									tipo_dptos DE, ";
			$sql .= "									tipo_mpios MU ";
			$sql .= "					WHERE 	MU.tipo_pais_id = DE.tipo_pais_id ";
			$sql .= "					AND			MU.tipo_dpto_id = DE.tipo_dpto_id ";
			$sql .= "					AND			DE.tipo_pais_id = PA.tipo_pais_id ";
			$sql .= "				) AS H ";
			$sql .= "				ON(	C.tipo_pais_id = H.tipo_pais_id AND "; 
			$sql .= "						C.tipo_dpto_id = H.tipo_dpto_id AND ";   
			$sql .= "						C.tipo_mpio_id = H.tipo_mpio_id), ";
			$sql .= "				tipo_sexo TS ";
			$sql .= "WHERE	C.tipo_id_paciente = 	'".$tid."' ";
			$sql .= "AND		C.paciente_id =	'".$id."' ";
			$sql .= "AND 		A.estado = '1' ";
			$sql .= "AND		A.tipo_id_paciente = C.tipo_id_paciente ";
			$sql .= "AND		A.paciente_id = C.paciente_id ";
			$sql .= "AND		C.sexo_id = TS.sexo_id "; 
			$sql .= "AND 		A.ingreso = ".$ingreso." ";
			$sql .= "AND		D.ingreso = A.ingreso  ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			if($ubicacion == 1) $datos['ubicacion'] = $this->UbicacionPaciente($ingreso,$tid,$id);
			return $datos;
    }
		/**********************************************************************************
    * Metodo para obtener los datos de un paciente ingresado
    *
    * @param string $ingreso
    * @return array
    ***********************************************************************************/
    function GetDatosPacienteOrden($ingreso,$tid,$id,$plan)
    {
			$sql  = "SELECT	A.ingreso,"; 
			$sql .= "				C.primer_apellido||' '||C.segundo_apellido AS apellido, "; 
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre AS nombre, "; 
			$sql .= "				TO_CHAR(C.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento,"; 
			$sql .= "				C.residencia_direccion,"; 
			$sql .= "				C.residencia_telefono, "; 
			$sql .= "				C.tipo_id_paciente, ";
			$sql .= "				C.paciente_id,";
			$sql .= "				D.tercero_id,"; 
			$sql .= "				D.tipo_id_tercero,"; 
			$sql .= "				D.nombre_tercero, "; 
			$sql .= "				D.plan_descripcion, "; 
			$sql .= "				D.plan_id, "; 
			$sql .= "				H.pais, "; 
			$sql .= "				H.departamento, "; 
			$sql .= "				H.municipio, "; 
			$sql .= "				TS.descripcion AS genero "; 
			$sql .= "FROM		hc_os_solicitudes A, ";
			$sql .= "				(	SELECT	PL.plan_id, ";
			$sql .= "									PL.plan_descripcion, ";
			$sql .= "									TE.tercero_id, "; 
			$sql .= "									TE.tipo_id_tercero,"; 
			$sql .= "									TE.nombre_tercero  "; 
			$sql .= "					FROM		planes PL, ";
			$sql .= "									terceros TE ";
			$sql .= "				 	WHERE		TE.tercero_id = PL.tercero_id ";
			$sql .= "					AND			TE.tipo_id_tercero = PL.tipo_tercero_id ";
			$sql .= "					AND			PL.plan_id = ".$plan." ";
			$sql .= "				) AS D, ";				
			$sql .= "				pacientes C  LEFT JOIN ";
			$sql .= "				(	SELECT  MU.municipio, ";
			$sql .= "									DE.departamento, ";
			$sql .= "									PA.pais, ";
			$sql .= "									MU.tipo_pais_id, ";
			$sql .= "									MU.tipo_dpto_id, ";
			$sql .= "									MU.tipo_mpio_id ";
			$sql .= "					FROM		tipo_pais PA, ";
			$sql .= "									tipo_dptos DE, ";
			$sql .= "									tipo_mpios MU ";
			$sql .= "					WHERE 	MU.tipo_pais_id = DE.tipo_pais_id ";
			$sql .= "					AND			MU.tipo_dpto_id = DE.tipo_dpto_id ";
			$sql .= "					AND			DE.tipo_pais_id = PA.tipo_pais_id ";
			$sql .= "				) AS H ";
			$sql .= "				ON(	C.tipo_pais_id = H.tipo_pais_id AND "; 
			$sql .= "						C.tipo_dpto_id = H.tipo_dpto_id AND ";   
			$sql .= "						C.tipo_mpio_id = H.tipo_mpio_id), ";
			$sql .= "				tipo_sexo TS ";
			$sql .= "WHERE	C.tipo_id_paciente = 	'".$tid."' ";
			$sql .= "AND		C.paciente_id =	'".$id."' ";
			$sql .= "AND		A.tipo_id_paciente = C.tipo_id_paciente ";
			$sql .= "AND		A.paciente_id = C.paciente_id ";
			$sql .= "AND		C.sexo_id = TS.sexo_id ";
			
			if($ingreso)
				$sql .= "AND 		A.ingreso = ".$ingreso." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
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
		function ProcesarSqlConteo($consulta,$offset=null,$limite=null)
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

			if(!$this->registros)
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
				$this->conteo = $this->registros;
			}
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function UbicacionPaciente($ingreso,$tid,$id)
		{
			$datos = array();
			/***************************************************************************
			* ingresos que estan relacionados con pacientes urgencias
			****************************************************************************/
			$sql  = "SELECT	DE.descripcion, ";
			$sql .= "				EF.descripcion AS estacion, ";
			$sql .= "				'URG' AS tabla ";
			$sql .= "FROM		ingresos IG, ";
			$sql .= "				pacientes_urgencias PU,";
			$sql .= "				departamentos DE, ";
			$sql .= "				estaciones_enfermeria EF ";
			$sql .= "WHERE	IG.ingreso = ".$ingreso." ";
			$sql .= "AND		IG.estado ='1' ";
			$sql .= "AND		IG.paciente_id = '".$id."' ";
			$sql .= "AND		IG.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		IG.ingreso = PU.ingreso ";
			$sql .= "AND		PU.sw_estado = '1' ";
			$sql .= "AND		IG.departamento_actual = DE.departamento ";
			$sql .= "AND		EF.departamento = DE.departamento ";
			$sql .= "AND		PU.estacion_id = EF.estacion_id ";
				
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
				
			while(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			if(sizeof($datos) == 0)
			{
				/***************************************************************************
				* ingresos que estan relacionados con esatciones enfernerias
				****************************************************************************/
				$sql  = "SELECT	DE.descripcion, ";
				$sql .= "				EF.descripcion AS estacion, ";
				$sql .= "				'EEF' AS tabla ";
				$sql .= "FROM		ingresos IG, ";
				$sql .= "				cuentas CU, ";
				$sql .= "				estaciones_enfermeria_ingresos_pendientes EP, ";
				$sql .= "				departamentos DE, estaciones_enfermeria EF ";
				$sql .= "WHERE	IG.ingreso = ".$ingreso." ";
				$sql .= "AND		IG.estado ='1' ";
				$sql .= "AND		IG.paciente_id = '".$id."' ";
				$sql .= "AND		IG.tipo_id_paciente = '".$tid."' ";
				$sql .= "AND		IG.ingreso = CU.ingreso ";
				$sql .= "AND		CU.numerodecuenta = EP.numerodecuenta ";
				$sql .= "AND		IG.departamento_actual = DE.departamento ";
				$sql .= "AND		EP.estacion_id = EF.estacion_id ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return true;

				while(!$rst->EOF)
				{
					$datos = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
				
				if(sizeof($datos) == 0)
				{
					/***************************************************************************
					* ingresos que estan relacionados con movimientos habitacion
					****************************************************************************/
					$sql  = "SELECT	DE.descripcion, ";
					$sql .= "				EF.descripcion AS estacion, ";
					$sql .= "				CA.pieza, CA.cama, CA.ubicacion, ";
					$sql .= "				'MVH' AS tabla ";
					$sql .= "FROM		ingresos IG, cuentas CU,";
					$sql .= "				estaciones_enfermeria_ingresos_pendientes EP, ";
					$sql .= "				movimientos_habitacion MH, camas CA, ";
					$sql .= "				departamentos DE,estaciones_enfermeria EF ";
					$sql .= "WHERE	IG.ingreso = ".$ingreso." ";
					$sql .= "AND		IG.estado ='1' ";
					$sql .= "AND		IG.paciente_id = '".$id."' ";
					$sql .= "AND		IG.tipo_id_paciente = '".$tid."' ";
					$sql .= "AND		MH.ingreso = IG.ingreso ";
					$sql .= "AND		IG.ingreso = CU.ingreso ";
					$sql .= "AND		IG.departamento_actual = DE.departamento ";
					$sql .= "AND		CU.numerodecuenta = EP.numerodecuenta ";
					$sql .= "AND		EP.estacion_id = EF.estacion_id ";
					$sql .= "AND		CA.cama = MH.cama ";
					$sql .= "AND		CU.estado = '1' ";
					
					if(!$rst = $this->ConexionBaseDatos($sql)) return true;

					while(!$rst->EOF)
					{
						$datos = $rst->GetRowAssoc($ToUpper = false);
						$rst->MoveNext();
					}
					$rst->Close();
				}
			}
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function OrdenesPorAutorizar($tid,$id,$departamento,$estado = "1")
		{
			$sql .= "SELECT COUNT(*) AS contador ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				departamentos_cargos DC, ";
			$sql .= "				cups CU ";
			$sql .= "WHERE 	HS.paciente_id = '".$id."' ";
			$sql .= "AND 		HS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND 		HS.sw_estado = '".$estado."' ";
			$sql .= "AND 		DC.departamento = '".$departamento."' ";
			$sql .= "AND 		DC.cargo = HS.cargo  ";
			$sql .= "AND 		DC.cargo = CU.cargo  ";
			//echo $sql.'<br><br>';
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerOrdenesC($departamento,$pid,$ptid,$int = "I",$orden_id)
		{
			$sql .= "SELECT	OS.sw_atencion_interna,";
			$sql .= "				OS.orden_servicio_id, ";
			$sql .= "				OS.evento_soat, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				OS.observacion, ";
			$sql .= "				OS.ingreso, ";
			$sql .= "				OS.servicio AS servicio_id, ";
			$sql .= "				TO_CHAR(OS.fecha_vencimiento,'DD/MM/YYYY') AS vencimiento, ";
			$sql .= "				TO_CHAR(OS.fecha_activacion,'DD/MM/YYYY') AS activacion, ";
			$sql .= "				TO_CHAR(OS.fecha_refrendar,'DD/MM/YYYY') AS refrendar, ";
			$sql .= "				SE.descripcion AS servicio, ";
			$sql .= "				OS.plan_id, ";
			$sql .= "				OS.autorizacion_int, ";
			$sql .= "				CITA.fecha_turno, ";
			$sql .= "				CITA.hora ";
			$sql .= "FROM		os_ordenes_servicios OS, ";
			$sql .= "		servicios SE, ";
			$sql .= "		planes PL, os_maestro OSM ";
			$sql .= "		LEFT JOIN (SELECT 	AT.fecha_turno,
												AC.hora,
												OCC.numero_orden_id
										FROM	agenda_turnos AT,
												agenda_citas AC,
												agenda_citas_asignadas ACA,
												os_cruce_citas OCC
										WHERE	AT.agenda_turno_id = AC.agenda_turno_id
										AND		AC.agenda_cita_id = ACA.agenda_cita_id
										AND		ACA.tipo_id_paciente = '".$ptid."'
										AND		ACA.paciente_id = '".$pid."'
										AND		ACA.agenda_cita_asignada_id = OCC.agenda_cita_asignada_id) AS CITA
										ON (CITA.numero_orden_id = OSM.numero_orden_id) ";
			$sql .= "WHERE	OS.servicio = SE.servicio ";
			$sql .= "AND		OS.departamento = '".$departamento."' ";
			$sql .= "AND		OS.sw_estado IN('1','2') ";
			
			$sql .= "AND		OS.orden_servicio_id = OSM.orden_servicio_id ";
			$sql .= "AND		OSM.sw_estado <> '9' ";
			//$sql .= "AND		OS.fecha_vencimiento >= NOW()::date ";
			$sql .= "AND		(OS.fecha_vencimiento >= NOW()::date OR OS.fecha_vencimiento ISNULL)";
			$sql .= "AND		OS.fecha_activacion <= NOW()::date ";
			$sql .= "AND		OS.paciente_id = '".$pid."' ";
			$sql .= "AND		OS.tipo_id_paciente = '".$ptid."' ";
			$sql .= "AND		OS.plan_id = PL.plan_id ";
			
			if($orden_id)
				$sql .= "AND		OS.orden_servicio_id = ".$orden_id." ";
			
			$sql .= "ORDER BY OS.orden_servicio_id,OS.fecha_vencimiento ASC ";
						
			//echo $sql.'<br><br>';
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[3]][$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				//$datos[$rst->fields[2]]['plan_descripcion'] = $rst->fields[9];
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerCargosOrdenesC($departamento,$pid,$ptid,$omci = null,$int,$plan_id,$orden_id = null)
		{
			$sql .= "SELECT	OS.orden_servicio_id, ";
			$sql .= "				OS.plan_id, ";
			$sql .= "				TO_CHAR(OS.fecha_vencimiento,'DD/MM/YYYY') AS vencimiento, ";
			$sql .= "				TO_CHAR(OS.fecha_activacion,'DD/MM/YYYY') AS activacion, ";
			$sql .= "				OM.cargo_cups, ";
			$sql .= "				OC.cantidad, ";
			$sql .= "				OC.cantidad_pendiente, ";
			$sql .= "				CS.descripcion AS descripcion_cups, ";
			$sql .= "				OC.tarifario_id, ";
			$sql .= "				OC.cargo, ";
			$sql .= "				OC.transaccion, ";
			$sql .= "				OC.os_maestro_cargos_id, ";
			$sql .= "				TD.descripcion AS descripcion_cargo, ";
			$sql .= "				TD.precio, ";
			$sql .= "				TA.descripcion AS tarifario_descripcion ";
			$sql .= "FROM		os_ordenes_servicios OS, ";
			$sql .= "				os_maestro OM, ";
			$sql .= "				cups CS, ";
			$sql .= "				os_maestro_cargos OC, ";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				tarifarios TA ";
			$sql .= "WHERE	OS.departamento = '".$departamento."' ";
			$sql .= "AND		OS.sw_estado IN('1','2') ";
			//$sql .= "AND		OM.sw_estado IN('1','2') ";
			$sql .= "AND		OC.cantidad_pendiente > 0 ";
			$sql .= "AND		OS.fecha_activacion <= NOW()::date ";
			//$sql .= "AND		OS.fecha_vencimiento >= NOW()::date ";
			$sql .= "AND		(OS.fecha_vencimiento >= NOW()::date OR OS.fecha_vencimiento ISNULL)";
			$sql .= "AND		OS.paciente_id = '".$pid."' ";
			$sql .= "AND		OS.tipo_id_paciente = '".$ptid."' ";
			$sql .= "AND		OM.orden_servicio_id = OS.orden_servicio_id ";
			$sql .= "AND		CS.cargo = OM.cargo_cups ";
			$sql .= "AND		OC.numero_orden_id = OM.numero_orden_id ";
			$sql .= "AND		OC.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		OC.cargo = TD.cargo ";
			$sql .= "AND		TA.tarifario_id = TD.tarifario_id ";
			
			if($omci != null)
				$sql .= "AND		OC.os_maestro_cargos_id IN (".$omci.") ";
			
			if($plan_id)
				$sql .= "AND 		OS.plan_id = ".$plan_id." ";
				
			if($orden_id)
				$sql .= "AND		OS.orden_servicio_id = ".$orden_id." ";
				
			$sql .= "ORDER BY OS.orden_servicio_id ASC";	
				//echo '<br><br>'.$sql;
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[0]][$rst->fields[4]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerCargosAutorizados($departamento,$pid,$ptid,$omci = null)
		{
			$sql  = "SELECT	TM.fecha, ";
			$sql .= "				TM.hc_os_solicitud_id,  ";
			$sql .= "				TM.cantidad,  ";
			$sql .= "				TM.cargos, ";
			$sql .= "				TM.plan_id,  ";
			$sql .= "				TM.os_tipo_solicitud_id,  ";
			$sql .= "				TM.evento_soat, ";
			$sql .= "				TE.tarifario_id,  ";
			$sql .= "				TE.cargo, ";
			$sql .= "				DE.servicio, ";
			$sql .= "				TD.descripcion, "; 
			$sql .= "				CP.descripcion AS cargo_cups  "; 
			$sql .= "FROM 	(	SELECT 	HS.cargo AS cargos,  ";
			$sql .= "									TO_CHAR(HE.fecha, 'DD/MM/YYYY HH24:MI') AS fecha,  ";
			$sql .= "									HS.hc_os_solicitud_id,  ";
			$sql .= "									HS.cantidad,  ";
			$sql .= "									HS.plan_id,  ";
			$sql .= "									HS.os_tipo_solicitud_id,  ";
			$sql .= "									NULL as servicio,  ";
			$sql .= "									HE.departamento,  ";
			$sql .= "									HS.cargo AS cargo_base, ";
			$sql .= "									IR.evento as evento_soat ";
			$sql .= "					FROM		hc_os_solicitudes HS, ";
			$sql .= "									hc_evoluciones HE  ";
			$sql .= "									LEFT JOIN ingresos_soat IR ";
			$sql .= "									ON(HE.ingreso = IR.ingreso) ";
			$sql .= "					WHERE		HS.autorizacion = ".$autorizacion." ";
			$sql .= "					AND 		HS.plan_id = ".$plan." ";
			$sql .= "					AND 		HS.evolucion_id = HE.evolucion_id ";
			$sql .= "					UNION ";
			$sql .= "					SELECT 	HS.cargo AS cargos, "; 
			$sql .= "									TO_CHAR(HE.fecha,'DD/MM/YYYY HH24:MI') AS fecha,  ";
			$sql .= "									HS.hc_os_solicitud_id,  ";
			$sql .= "									HS.cantidad,  ";
			$sql .= "									HS.plan_id,  ";
			$sql .= "									HS.os_tipo_solicitud_id,  ";
			$sql .= "									HE.servicio,  ";
			$sql .= "									HE.departamento,  ";
			$sql .= "									HS.cargo AS cargo_base, ";
			$sql .= "									HE.evento_soat ";
			$sql .= "					FROM		hc_os_solicitudes HS, ";
			$sql .= "									hc_os_solicitudes_manuales HE  ";
			$sql .= "					WHERE		HS.autorizacion = ".$autorizacion." ";
			$sql .= "					AND 		HS.plan_id = ".$plan." ";
			$sql .= "					AND 		HS.hc_os_solicitud_id = HE.hc_os_solicitud_id ";
			$sql .= "				) AS TM ";
			$sql .= "				LEFT JOIN departamentos DE  ";
			$sql .= "				ON (DE.departamento = TM.departamento), ";
			$sql .= "				tarifarios_equivalencias TE, ";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				cups CP, ";
			$sql .= "				plan_tarifario PT	 ";
			$sql .= "WHERE	TE.cargo_base = TM.cargo_base ";
			$sql .= "AND		CP.cargo = TE.cargo ";
			$sql .= "AND 		TE.tarifario_id = TD.tarifario_id  ";
			$sql .= "AND 		TD.cargo = TE.cargo ";
			$sql .= "AND 		PT.plan_id = ".$plan." ";
			$sql .= "AND 		PT.grupo_tarifario_id = TD.grupo_tarifario_id ";
			$sql .= "AND 		PT.subgrupo_tarifario_id = TD.subgrupo_tarifario_id ";
			$sql .= "AND 		TD.tarifario_id = PT.tarifario_id ";
			$sql .= "ORDER BY TM.hc_os_solicitud_id	";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[0]][$rst->fields[4]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}		
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerSolicitues($tid,$pid,$departamento,$ingreso)
		{
			$sql .= "SELECT DISTINCT HS.hc_os_solicitud_id, ";
			$sql .= "				HS.os_tipo_solicitud_id, ";
			$sql .= "				CASE WHEN HS.evolucion_id IS NULL THEN 'M'";
			$sql .= "					ELSE 'H' END AS tipo, ";
			$sql .= "				DC.departamento, ";
			$sql .= "				CU.cargo, ";
			$sql .= "				CU.descripcion AS cups, ";
			$sql .= "				HS.cantidad, ";
			$sql .= "				DE.servicio, ";
			$sql .= "				SE.descripcion, ";
			$sql .= "				TO_CHAR(HS.fecha_solicitud, 'DD/MM/YYYY HH:MI AM') AS fecha ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				departamentos_cargos DC, ";
			$sql .= "				departamentos DE, ";
			$sql .= "				servicios SE, ";
			$sql .= "				cups CU ";
			$sql .= "WHERE 	HS.paciente_id = '".$pid."' ";
			$sql .= "AND 		HS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND 		HS.sw_estado = '1' ";
			//$sql .= "AND 		HS.sw_no_autorizado = '1' ";
			$sql .= "AND 		DC.cargo = HS.cargo  ";
			$sql .= "AND 		DC.cargo = CU.cargo  ";
			$sql .= "AND 		DC.departamento = DE.departamento  ";
			$sql .= "AND 		DE.servicio = SE.servicio  ";
			
			if($departamento)	$sql .= "AND 		DC.departamento = '".$departamento."' ";
			
			if($ingreso)	$sql .= "AND 		HS.ingreso = ".$ingreso." ";
		
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[8]][$rst->fields[2]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerCargosSolicitudes($tid,$pid,$departamento,$ingreso,$plan,$estado = "1", $autorizacion)
		{
			$sql .= "SELECT CU.cargo, ";
			$sql .= "				TD.descripcion AS tarifario, ";
			$sql .= "				TD.tarifario_id, ";
			$sql .= "				TD.cargo, ";
			$sql .= "				TA.descripcion AS desc_tarifario, ";
			$sql .= "				DE.servicio, ";
			$sql .= "				CU.sw_cantidad ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				departamentos_cargos DC, ";
			$sql .= "				departamentos DE, ";
			$sql .= "				cups CU, ";
			$sql .= "				tarifarios_equivalencias TE, ";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				tarifarios TA, ";
			$sql .= "				plan_tarifario PT ";
			$sql .= "WHERE 	HS.paciente_id = '".$pid."' ";
			$sql .= "AND 		HS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND 		HS.sw_estado = '".$estado."' ";
			//$sql .= "AND 		HS.sw_no_autorizado = '1' ";
			$sql .= "AND 		DC.departamento = DE.departamento ";

			$sql .= "AND 		DC.cargo = HS.cargo  ";
			$sql .= "AND 		DC.cargo = CU.cargo  ";
			$sql .= "AND 		CU.cargo = TE.cargo_base ";
			$sql .= "AND 		CU.sw_activo = '1' ";
			$sql .= "AND		TE.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		TE.cargo = TD.cargo ";
			$sql .= "AND		TA.tarifario_id = TD.tarifario_id ";
			$sql .= "AND 		PT.plan_id = ".$plan." ";
      $sql .= "AND 		TD.grupo_tarifario_id = PT.grupo_tarifario_id ";
      $sql .= "AND		TD.subgrupo_tarifario_id = PT.subgrupo_tarifario_id ";
      $sql .= "AND		TD.tarifario_id = PT.tarifario_id ";
      $sql .= "AND 		excepciones(PT.plan_id,PT.tarifario_id, TD.cargo) = 0 ";
			
			if($departamento)
			{
				$sql .= "AND 		DC.departamento = '".$departamento."' ";
				$sql .= "AND 		DE.departamento = '".$departamento."' ";
			}
			
			if($ingreso)
				$sql .= "AND 		HS.ingreso = ".$ingreso." ";
			if($autorizacion)
				$sql .= "AND 		HS.autorizacion = ".$autorizacion." ";
			
			$sql .= "UNION ";
			$sql .= "SELECT CU.cargo, ";
			$sql .= "				TD.descripcion AS tarifario, ";
			$sql .= "				TD.tarifario_id, ";
			$sql .= "				TD.cargo, ";
			$sql .= "				TA.descripcion AS desc_tarifario, ";
			$sql .= "				DE.servicio, ";
			$sql .= "				CU.sw_cantidad ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				departamentos_cargos DC, ";
			$sql .= "				departamentos DE, ";
			$sql .= "				cups CU, ";
			$sql .= "				tarifarios_equivalencias TE, ";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				tarifarios TA, ";
			$sql .= "				excepciones EX ";
			$sql .= "				,plan_tarifario PT ";
			$sql .= "WHERE 	HS.paciente_id = '".$pid."' ";
			$sql .= "AND 		HS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND 		HS.sw_estado = '".$estado."' ";
			//$sql .= "AND 		HS.sw_no_autorizado = '1' ";
			$sql .= "AND 		DC.departamento = '".$departamento."' ";
			$sql .= "AND 		DC.departamento = DE.departamento ";
			$sql .= "AND 		DE.departamento = '".$departamento."' ";
			$sql .= "AND 		DC.cargo = HS.cargo  ";
			$sql .= "AND 		DC.cargo = CU.cargo  ";
			$sql .= "AND 		CU.cargo = TE.cargo_base ";
			$sql .= "AND 		CU.sw_activo = '1' ";
			$sql .= "AND		TE.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		TE.cargo = TD.cargo ";
			$sql .= "AND		TA.tarifario_id = TD.tarifario_id ";
			$sql .= "AND 		EX.plan_id = ".$plan." ";
      $sql .= "AND 		EX.tarifario_id = TE.tarifario_id ";
			//**************************
			$sql .= "AND TD.grupo_tarifario_id = PT.grupo_tarifario_id ";
			$sql .= "AND TD.subgrupo_tarifario_id = PT.subgrupo_tarifario_id ";  
			$sql .= "AND TD.tarifario_id = PT.tarifario_id ";
			$sql .= "AND PT.plan_id = EX.plan_id ";
			//***************************
			if($ingreso)
				$sql .= "AND 		HS.ingreso = ".$ingreso." ";
			if($autorizacion)
				$sql .= "AND 		HS.autorizacion = ".$autorizacion." ";
//echo '<br><br>'.$sql.'<br><br>';//exit;
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerValidacionContrato($cargo,$plan)
		{
			$sql .= "SELECT PT.plan_id ";
			$sql .= "FROM 	tarifarios_equivalencias TE, ";
			$sql .= "				tarifarios_detalle TD,";
			$sql .= "				plan_tarifario PT ";
			$sql .= "WHERE 	TE.cargo_base ='".$cargo."' ";
			$sql .= "AND 		PT.plan_id = ".$plan."  ";
			$sql .= "AND 		TD.cargo = TE.cargo ";
			$sql .= "AND 		TD.tarifario_id = TE.tarifario_id ";
			$sql .= "AND 		TD.grupo_tarifario_id = PT.grupo_tarifario_id ";
			$sql .= "AND 		TD.subgrupo_tarifario_id = PT.subgrupo_tarifario_id ";
			$sql .= "AND 		TD.tarifario_id = PT.tarifario_id ";
			$sql .= "AND 		excepciones(PT.plan_id,PT.tarifario_id,TD.cargo) = 0 ";
			$sql .= "UNION ";
			$sql .= "SELECT	EX.plan_id ";
			$sql .= "FROM		tarifarios_detalle TD, ";
			$sql .= "				excepciones EX, ";
			$sql .= "				subgrupos_tarifarios ST, ";
			$sql .= "				tarifarios_equivalencias TE ";
			$sql .= "WHERE 	TE.cargo_base = '".$cargo."' ";
			$sql .= "AND 		TD.cargo = TE.cargo ";
			$sql .= "AND 		TD.tarifario_id = TE.tarifario_id ";
			$sql .= "AND 		EX.plan_id = ".$plan." ";
			$sql .= "AND		EX.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		EX.sw_no_contratado = '0' ";
			$sql .= "AND		EX.cargo = TD.cargo ";
			$sql .= "AND		ST.grupo_tarifario_id = TD.grupo_tarifario_id ";
			$sql .= "AND		ST.subgrupo_tarifario_id = TD.subgrupo_tarifario_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$conteo = $rst->RecordCount();
			return $conteo;
		}
		/**********************************************************************************
		* Funcion donde se obtienen los numeros de autorizacion de las solicitudes que han
		* sido autorizadas pero no se han creado las ordenes
		* @params String $tid Tipo de identificacion del paciente
		* @params String $id Identificacion del paciente
		* @params String $departamento Identificador del departamento
		*
		* @returns Array Arreglo de datos que contienen el numero de autorizacion
		***********************************************************************************/
		function OrdenesPorRealizar($tid,$id,$departamento)
		{
			$sql  = "SELECT DISTINCT HS.autorizacion ";
			$sql .= "FROM   hc_os_solicitudes HS,";
			$sql .= "       departamentos_cargos DC "; 
			$sql .= "WHERE  HS.autorizacion IS NOT NULL ";  
			$sql .= "AND    HS.sw_estado = '0'  ";
			$sql .= "AND    HS.paciente_id = '".$id."' "; 
			$sql .= "AND    HS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND    DC.departamento = '".$departamento."' "; 
			$sql .= "AND    DC.cargo = HS.cargo  ";
			$sql .= "AND    HS.autorizacion <> 0 ";
			$sql .= "AND    HS.autorizacion <> 1 ";
			$sql .= "AND    HS.hc_os_solicitud_id NOT IN  ";
			$sql .= "				(	SELECT	OM.hc_os_solicitud_id  ";
			$sql .= "  				FROM   	hc_os_solicitudes HS, ";
			$sql .= "         				os_maestro OM ";
			$sql .= "  				WHERE   OM.hc_os_solicitud_id = HS.hc_os_solicitud_id ";
			$sql .= "  				AND     HS.paciente_id = '".$id."' "; 
			$sql .= "  				AND     HS.tipo_id_paciente = '".$tid."' "; 
			$sql .= "  				AND     HS.sw_estado = '0' ";
			$sql .= "				)";
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		* Funcion donde se obtienen aquellas solicitudes que ya han sido autorizadas
		* @params String $tid Tipo de idemntificacion del paciente
		* @params String $pid Numero de identificacion del paciente
		* @params String Numero de identificacion del departamento
		* @params int Numero de autorizacion
		*
		* @returns Array datos de las solicitudes
		***********************************************************************************/
		function ObtenerSolicituesAutorizadas($tid,$pid,$departamento,$autorizacion,$numero_orden_id)
		{
			$tmp_sql_from  = "";
			$tmp_sql_where = "AND 		HS.autorizacion =  ".$autorizacion."";
			if(!empty($numero_orden_id))
			{
				$tmp_sql_from = ", os_maestro OS ";
				$tmp_sql_where = "AND OS.numero_orden_id = ".$numero_orden_id."
				                  AND OS.hc_os_solicitud_id = HS.hc_os_solicitud_id ";
			}

			$sql .= "SELECT DISTINCT HS.hc_os_solicitud_id, ";
			$sql .= "				HS.os_tipo_solicitud_id, ";
			$sql .= "				CASE WHEN HS.evolucion_id IS NULL THEN 'M'";
			$sql .= "					ELSE 'H' END AS tipo, ";
			$sql .= "				DC.departamento, ";
			$sql .= "				CU.cargo, ";
			$sql .= "				CU.descripcion AS cups, ";
			$sql .= "				HS.cantidad, ";
			$sql .= "				DE.servicio, ";
			$sql .= "				SE.descripcion, ";
			$sql .= "				TO_CHAR(HS.fecha_solicitud, 'DD/MM/YYYY HH:MI AM') AS fecha ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				departamentos_cargos DC, ";
			$sql .= "				departamentos DE, ";
			$sql .= "				servicios SE, ";
			$sql .= "				cups CU ";
			$sql .= $tmp_sql_from;
			$sql .= "WHERE 	HS.paciente_id = '".$pid."' ";
			$sql .= "AND 		HS.tipo_id_paciente = '".$tid."' ";
			
			//$sql .= "AND 		HS.autorizacion =  ".$autorizacion." ";
			
			$sql .= "AND 		HS.sw_estado = '0' ";
			$sql .= "AND 		DC.departamento = '".$departamento."' ";
			$sql .= "AND 		DC.cargo = HS.cargo  ";
			$sql .= "AND 		DC.cargo = CU.cargo  ";
			$sql .= "AND 		DC.departamento = DE.departamento  ";
			$sql .= "AND 		DE.servicio = SE.servicio  ";
			$sql .= $tmp_sql_where;
//echo '<br><br>'.$sql;
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[8]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerDepartamentoPrestadorServicio($cargo)
		{
			$sql  = "SELECT	DC.departamento, ";
			$sql .= "				DE.descripcion ";
			$sql .= "FROM 	departamentos_cargos DC, ";
			$sql .= "				departamentos DE ";
			$sql .= "WHERE 	DC.cargo = '".$cargo."' ";
			$sql .= "AND 		DE.departamento = DC.departamento ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function IngresarOrdenServicio($ordenes,$datos,$departamento)
		{
			$cargos = "";
			$num_ordenes = "";
			$fechabase = date("Y-m-d");
			$dias_c = array();
			//31-10-2007
			//echo  '<pre>';
			//print_r($_REQUEST);
 			//print_r($ordenes);
			foreach($ordenes as $key => $servicio)
			{
				foreach($servicio['cargo_cup'] as $key => $cargo)
				{ 
					if(!empty($cargo[hc_soilicitud_id]))
					{
						$sql  = "SELECT OSO.orden_servicio_id, OS.numero_orden_id ";
						$sql .= "FROM 	os_maestro OS, 	os_ordenes_servicios OSO ";
						$sql .= "WHERE	OS.hc_os_solicitud_id = ".$cargo[hc_soilicitud_id]." ";
						$sql .= "AND OS.orden_servicio_id = OSO.orden_servicio_id ";
						
						if(!$rst = $this->ConexionBaseDatos($sql)) return false;
					
						if(!empty($rst->fields[0]) AND !empty($rst->fields[1]))
						{
							foreach($cargo[cargo] AS $key => $tarifario)
							{
								foreach($tarifario AS $key => $request)
								{
									//$request['cantidad']
									$sql = "UPDATE os_maestro_cargos ";
									
									if(ModuloGetVar('app','AgendaMedica','MenuAsignacionCitas')=='1'){
										$sql .= "		SET cantidad = 1, ";
										$sql .= "		cnt_citas_auto = ".$request['cantidad'].", ";
									}
									else{
										$sql .= "		SET cantidad = ".$request['cantidad'].", ";
									}
									$sql .= "		cantidad_pendiente = ".$request['cantidad']." ";
									$sql .= " WHERE numero_orden_id = ".$rst->fields[1]." ";
									$sql .= "	AND tarifario_id = '". $request['tarifario']."' ";
									$sql .= "	AND cargo = '".$request['cargo']."'; ";
									if(!$rst1 = $this->ConexionBaseDatos($sql)) return false;
								}
							}
							return $rst->fields[0];
						}
					}
				}
			}
			//FIN MODIFICACION
 
			foreach($ordenes as $key => $servicio)
			{
				foreach($servicio['cargo_cup'] as $key => $cargo)
				{
					($cargos == "")? $cargos = "'".$key."'" :	$cargos .= ",'".$key."'";
				}
				
				list($anyo,$mes,$dia) = split("-", $fechabase);
				
				$sql  = "SELECT MAX(dias_vigencia) AS dias_vigencia,";
				$sql .= "				MAX(dias_refrendar) AS dias_refrendar,";
				$sql .= "				MAX(dias_tramite_os) AS dias_tramite_os ";
				$sql .= "FROM 	os_tipos_periodos_planes ";
				$sql .= "WHERE	plan_id = ".$datos['plan_id']." ";
				$sql .= "AND 		cargo IN (".$cargos.") ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
				if(!$rst->EOF)
				{
					$dias_c = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				
				if(!$dias_c['dias_tramite_os'])
					$dias_c['dias_tramite_os'] = ModuloGetVar('app','CentroAutorizacion','dias_tramite_os');
			
				if(!$dias_c['dias_refrendar'])
					$dias_c['dias_refrendar'] = ModuloGetVar('app','CentroAutorizacion','dias_refrendar');
				
				if(!$dias_c['dias_vigencia'])
					$dias_c['dias_vigencia'] = ModuloGetVar('app','CentroAutorizacion','dias_vigencia');
			
				$fecha = mktime(0,0,0, $mes,$dia,$anyo) + $dias_c['dias_tramite_os'] * 24 * 60 * 60;
				$fecha_tramite = date("Y-m-d",$fecha);
				
				$fecha = mktime(0,0,0, $mes,$dia,$anyo) + $dias_c['dias_refrendar'] * 24 * 60 * 60;
				$fecha_refrendar = date("Y-m-d",$fecha);
				
				$fecha = mktime(0,0,0, $mes,$dia,$anyo) + $dias_c['dias_vigencia'] * 24 * 60 * 60;
				$fecha_vigencia = date("Y-m-d",$fecha);

				$orden = "";
				$sql = "SELECT NEXTVAL('os_ordenes_servicios_orden_servicio_id_seq');";
			
				$this->ConexionTransaccion();
				if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
				if(!$rst->EOF) $orden = $rst->fields[0];
				
				if(!$servicio['evento_soat']) $servicio['evento_soat'] = "NULL";
				if(!$datos['ingreso']) $datos['ingreso'] = "NULL";
			
				$atencion = '0';
				if($servicio['servicio'] != '3') $atencion = '1';
			
				$sql  = "INSERT INTO os_ordenes_servicios( ";
				$sql .= "			orden_servicio_id, ";
		 		$sql .= "			autorizacion_int,";
		 		$sql .= "			plan_id, ";
		 		$sql .= "			tipo_afiliado_id,";
				$sql .= "			rango,";
		 		$sql .= "			semanas_cotizadas,";
		 		$sql .= "			servicio,";
		 		$sql .= "			tipo_id_paciente,";
		 		$sql .= "			paciente_id,";
		 		$sql .= "			usuario_id,";
		 		$sql .= "			fecha_registro,";
		 		$sql .= "			observacion,";
		 		$sql .= "			evento_soat,";
		 		$sql .= "			departamento,";
		 		$sql .= "			sw_atencion_interna,";
		 		$sql .= "			ingreso,";
		 		$sql .= "			sw_estado,";
				$sql .= "			fecha_vencimiento,";
		 		$sql .= "			fecha_activacion,";
		 		$sql .= "			fecha_refrendar";
				$sql .= " )";
				$sql .= "VALUES ( ";
				$sql .= "			".$orden.", ";
				$sql .= "			".$datos['numero_autorizacion'].", ";
				$sql .= "			".$datos['plan_id'].", ";
				$sql .= "			'".$datos['tipo_afiliado_id']."', ";
				$sql .= "			'".$datos['rango']."', ";
				$sql .= "			".$datos['semanas'].", ";
				$sql .= "			'".$servicio['servicio']."',";
				$sql .= "			'".$datos['tipo_id_paciente']."',";
				$sql .= "			'".$datos['paciente_id']."',";
				$sql .= "			".UserGetUID().", ";
				$sql .= "			NOW(),";
				$sql .= "			'".$datos['observacion']."', ";
				$sql .= "			".$servicio['evento_soat'].", ";
				$sql .= "			'".$departamento."', ";
				$sql .= "			'".$atencion."',";
				$sql .= "			".$datos['ingreso'].",";
				$sql .= "			'1',";
				$sql .= "			'".$fecha_vigencia."',";
				$sql .= "			'".$fecha_tramite."',";
				$sql .= "			'".$fecha_refrendar."' ";
				$sql .= ")";

				if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
				
				$osmaestro = "";
				foreach($servicio['cargo_cup'] AS $key => $cargo)
				{
					$sql  = "SELECT NEXTVAL('os_maestro_numero_orden_id_seq');";
					if(!$rst = $this->ConexionTransaccion($sql,'3 Os_Maestro')) return false;
					if(!$rst->EOF) $osmaestro = $rst->fields[0];
				
					$sql  = "INSERT INTO os_maestro (	";
					$sql .= "				numero_orden_id,	";
					$sql .= "				orden_servicio_id,	";
					$sql .= "				cargo_cups,	";
					$sql .= "				cantidad,	";
					$sql .= "				hc_os_solicitud_id,	";
					$sql .= "				fecha_vencimiento,	";
					$sql .= "				fecha_activacion,	";
					$sql .= "				fecha_refrendar ";
					$sql .= "				) ";
					$sql .= "VALUES (";
					$sql .= "			".$osmaestro.",";
					$sql .= " 		".$orden.",";
					$sql .= " 		'".$key."',";
					$sql .= " 		".$cargo['cantidad'].",";
					$sql .= " 		".$cargo['hc_soilicitud_id'].",";
					$sql .= "			'".$fecha_vigencia."',";
					$sql .= "			'".$fecha_tramite."',";
					$sql .= "			'".$fecha_refrendar."' ";
					$sql .= "		) ";
				
					if(!$rst = $this->ConexionTransaccion($sql,'4 Os_Maestro')) return false;
					$sql  = "";
					
					//$parcial = $this->ObtenerDatosCumplimiento($key,$departamento);
					
					foreach($cargo['cargo'] as $keyI => $tarifarioe)
					{
						foreach($tarifarioe as $keyII => $cargoe)
						{
							if($cargoe['cargo'])
							{
								$sql .= "INSERT INTO os_maestro_cargos( ";
								$sql .= "			numero_orden_id,";
								$sql .= "			tarifario_id,";
								$sql .= "			cargo,";
								$sql .= "			cantidad, ";
								$sql .= "			cantidad_pendiente ";
								$sql .= "			) ";
								$sql .= "VALUES ( ";
								$sql .= "			".$osmaestro.",";
								$sql .= "			'".$cargoe['tarifario']."',";
								$sql .= "			'".$cargoe['cargo']."',";
								$sql .= "			".$cargoe['cantidad'].", ";
								$sql .= "			".$cargoe['cantidad']." ";
								$sql .= "		);	";
							}
						}
					}
				
					$sql .= "UPDATE hc_os_solicitudes ";
					$sql .= "SET  	sw_estado = '0' ";
					$sql .= "WHERE 	hc_os_solicitud_id = ".$cargo['hc_soilicitud_id']."; ";
				
					if(!$rst = $this->ConexionTransaccion($sql,'5 Os_Maestro')) return false;
				}
				$this->dbconn->CommitTrans();
				($num_ordenes == "")? $num_ordenes .= $orden : $num_ordenes .= ",".$orden;
			}
			
			return $num_ordenes;
		}
		
		/**********************************************************************************
		*
		* @return boolean
		***********************************************************************************/
		function ObtenerDatosCumplimiento($cargo,$departamento)
		{
			$sql  = "SELECT DC.sw_cumplimiento_parcial ";
			$sql .= "FROM 	departamentos_cargos DC ";
			$sql .= "WHERE 	DC.cargo = '".$cargo."' ";
			$sql .= "AND		DC.departamento = '".$departamento."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos['sw_cumplimiento_parcial'];
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function ObtenerCuentasOrdenesServicios($paciente_id,$tipo_id_paciente,$departamento)
		{
			$sql  = "SELECT	DISTINCT ";
			$sql .= "				CD.numerodecuenta ";
			$sql .= "FROM 	os_ordenes_servicios OS, ";
			$sql .= "				os_maestro OM,";
			$sql .= "				os_maestro_cargos OC, ";
			$sql .= "				cuentas_detalle CD, ";
			$sql .= "				cuentas CU ";
			$sql .= "WHERE 	OS.departamento = '".$departamento."' ";
			$sql .= "AND 		OS.sw_estado IN('1','2') ";
			$sql .= "AND 		OS.fecha_activacion <= NOW()::date ";
			$sql .= "AND 		OS.fecha_vencimiento >= NOW()::date "; 
			$sql .= "AND 		OS.paciente_id = '".$paciente_id."' ";
			$sql .= "AND 		OS.tipo_id_paciente = '".$tipo_id_paciente."' ";
			$sql .= "AND 		OM.orden_servicio_id = OS.orden_servicio_id "; 
			$sql .= "AND 		OC.numero_orden_id = OM.numero_orden_id ";
			$sql .= "AND		CD.transaccion = OC.transaccion ";
			$sql .= "AND		CD.numerodecuenta = Cu.numerodecuenta ";
			$sql .= "AND		CU.estado IN ('1','2') ";
			
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
				//$this->dbconn->debug=true;
				$this->dbconn->BeginTrans();
			}
			else
			{
				$rst = $this->dbconn->Execute($sql);
				if ($this->dbconn->ErrorNo() != 0)
				{
					$this->frmError['MensajeError'] = "ERROR DB : " . $this->dbconn->ErrorMsg();
					//echo "<b class=\"label\">Trasaccion: $num - ".$this->frmError['MensajeError']."</b>";
					$this->dbconn->RollbackTrans();
					return false;
				}
				return $rst;
			}
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
				echo "<b class=\"label\">".$this->frmError['MensajeError']." ".$sql."</b>";
				return false;
			}
			return $rst;
		}
	}
?>