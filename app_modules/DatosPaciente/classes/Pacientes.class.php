<?php
	/**************************************************************************************
	* $Id: Pacientes.class.php,v 1.1 2009/11/10 19:33:17 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class Pacientes extends ConexionBD
	{
		function Pacientes(){}
		/**********************************************************************************
    * Busca la identificacion del nn
    * @access public
    * @return int
		***********************************************************************************/
		function	ObtenerIdentifiacionNN($datos)
		{
			$sql  = "SELECT	TO_NUMBER(COALESCE(MAX(paciente_id),'0'),9999999999999999) +1 AS numero ";
			$sql .= "FROM		pacientes ";
			$sql .= "WHERE 	tipo_id_paciente IN ('MS','AS') ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;

			$id = array();
			if(!$rst->EOF)
			{
				$id = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos['tipo_id_paciente'].$id['numero'];
		}
		/**
		*
		*/
		Function ObtenerDatosPlan($plan) 
		{
			$sql  = "SELECT sw_tipo_plan,";
			$sql .= "				sw_afiliacion,";
			$sql .= "				protocolos,";
			$sql .= "				sw_afiliados, ";
			$sql .= "				sw_autoriza_sin_bd ";
			$sql .= "FROM		planes ";
			$sql .= "WHERE 	estado = '1' ";
			$sql .= "AND		plan_id = ".$plan." ";
			$sql .= "AND		fecha_final >= NOW() ";
			$sql .= "AND		fecha_inicio <= NOW() ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;

			$datos = array();
			if(!$rst->EOF)
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
		function ObtenerDatosPaciente($tid,$id)
		{
			$sql  = "SELECT	PA.paciente_id,";
			$sql .= " 			PA.tipo_id_paciente,";
			$sql .= " 			PA.primer_apellido,";
			$sql .= " 			PA.segundo_apellido,";
			$sql .= " 			PA.primer_nombre,";
			$sql .= " 			PA.segundo_nombre,";
			$sql .= " 			PA.fecha_nacimiento_es_calculada,";
			$sql .= " 			PA.residencia_direccion,";
			$sql .= " 			PA.residencia_telefono,";
			$sql .= " 			PA.zona_residencia,";
			$sql .= " 			PA.ocupacion_id,";
			$sql .= " 			PA.sexo_id,";
			$sql .= " 			PA.tipo_estado_civil_id,";
			$sql .= " 			PA.foto,";
			$sql .= " 			PA.tipo_pais_id,";
			$sql .= " 			PA.tipo_dpto_id,";
			$sql .= " 			PA.tipo_mpio_id,";
			$sql .= " 			PA.paciente_fallecido,";
			$sql .= " 			PA.usuario_id,";
			$sql .= " 			PA.nombre_madre,";
			$sql .= " 			PA.observaciones,";
			$sql .= " 			PA.tipo_comuna_id,";
			$sql .= " 			PA.tipo_barrio_id,";
			$sql .= " 			PA.tipo_estrato_id,";
			$sql .= " 			PA.lugar_expedicion_documento,";
			$sql .= " 			TO_CHAR(PA.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento,";
			$sql .= " 			TO_CHAR(PA.fecha_registro,'DD/MM/YYYY HH:MI AM') AS fecha_registro,";
			$sql .= "				HC.historia_numero,";
			$sql .= "				HC.historia_prefijo ";
			$sql .= "FROM		pacientes PA ";
			$sql .= "				LEFT JOIN historias_clinicas HC ";
			$sql .= "				ON(	HC.tipo_id_paciente = '".$tid."' ";
			$sql .= "						AND	HC.paciente_id = '".$id."' ";
			$sql .= "						AND HC.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "						AND	HC.paciente_id = PA.paciente_id ) ";
			$sql .= "WHERE 	PA.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		PA.paciente_id = '".$id."' ";

			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			while(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$sql  = "SELECT tipo_metrica_id, ";
			$sql .= " 			valor_metrica ";
			$sql .= "FROM		pacientes_metricas PM ";
			$sql .= "WHERE	tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		paciente_id = '".$id."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->fields[1];
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		
		function ObtenerDatosIngreso($tid,$id)
		{
			$sql  = "SELECT	I.ingreso,";
			$sql .= " 			TO_CHAR(I.fecha_registro,'DD/MM/YYYY') AS fecha_ingreso,";
			$sql .= " 			C.numerodecuenta,";
			$sql .= " 			C.total_cuenta,";
			$sql .= " 			P.plan_descripcion ";
			$sql .= "FROM		ingresos I, ";
			$sql .= "			cuentas C, ";
			$sql .= "			planes P ";
			$sql .= "WHERE 		I.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		I.paciente_id = '".$id."' ";
			$sql .= "AND		I.estado = '1' ";
			$sql .= "AND		C.ingreso = I.ingreso ";
			$sql .= "AND		P.plan_id = C.plan_id ";
			
			
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
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerUnidad($tipou)
		{
			$sql  = "SELECT unidad ";
			$sql .= "FROM		tipos_metricas ";
			$sql .= "WHERE 	tipo_metrica_id = '".$tipou."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;

			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos['unidad'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerCamposObligatorios()
		{
			$sql  = "SELECT	campo, ";
			$sql .= "				sw_mostrar,";
			$sql .= "				sw_obligatorio, ";
			$sql .= "				CASE WHEN sw_obligatorio = '1' THEN '*' ELSE '' END AS marca ";
			$sql .= "FROM 	pacientes_campos_obligatorios ";
			
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
		/**********************************************************************************
		*
		***********************************************************************************/
	  function ObtenerDatosPlanDescripcion($plan)
    {
			$sql  = "SELECT	plan_descripcion,";
			$sql .= "				sw_tipo_plan ";
			$sql .= "FROM 	planes ";
			$sql .= "WHERE 	plan_id =  ".$plan." ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
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
		function ObtenerDescripcionId($tid)
		{
			$sql  = "SELECT	descripcion ";
			$sql .= "FROM 	tipos_id_pacientes ";
			$sql .= "WHERE	tipo_id_paciente = '".$tid."' ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
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
		function ObtenerNombrePais($pais)
		{
			$sql  = "SELECT pais ";
			$sql .= "FROM		tipo_pais ";
			$sql .= "WHERE 	tipo_pais_id = '".$pais."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['pais'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerNombreDepartamento($pais,$dpto)
		{
			$sql  = "SELECT departamento ";
			$sql .= "FROM		tipo_dptos ";
			$sql .= "WHERE 	tipo_pais_id = '".$pais."' ";
			$sql .= "AND		tipo_dpto_id = '".$dpto."' ";
				
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['departamento'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerNombreCiudad($pais,$dpto,$mpio)
		{
			$sql  = "SELECT municipio ";
			$sql .= "FROM		tipo_mpios ";
			$sql .= "WHERE 	tipo_pais_id = '".$pais."' ";
			$sql .= "AND 		tipo_dpto_id = '".$dpto."' ";
			$sql .= "AND 		tipo_mpio_id = '".$mpio."' ";
		
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['municipio'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerNombreComuna($pais,$dpto,$mpio,$comuna)
		{
			$sql  = "SELECT	comuna ";
			$sql .= "FROM		tipo_comunas ";
			$sql .= "WHERE 	tipo_pais_id = '".$pais."' ";
			$sql .= "AND		tipo_dpto_id = '".$Dpto."' ";
			$sql .= "AND		tipo_mpio_id = '".$Mpio."' ";
			$sql .= "AND		tipo_comuna_id = '".$comuna."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['comuna'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerTiposSexo()
		{
			$sql  = "SELECT sexo_id, ";
			$sql .= "				descripcion ";
			$sql .= "FROM		tipo_sexo ";
			$sql .= "WHERE 	sexo_id <> '0' ";
			$sql .= "ORDER BY indice_de_orden ";
			
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
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerNombreBarrio($pais,$dpto,$mpio,$comuna,$barrio)
		{
			$sql  = "SELECT barrio ";
			$sql .= "FROM		tipo_barrios ";
			$sql .= "WHERE	tipo_pais_id = '".$pais."' ";
			$sql .= "AND		tipo_dpto_id = '".$dpto."' ";
			$sql .= "AND		tipo_mpio_id = '".$mpio."' ";
			$sql .= "AND 		tipo_comuna_id = '".$comuna."' ";
			$sql .= "AND		tipo_barrio_id = '".$barrio."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['barrio'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerNombreOcupacion($ocupa)
		{
			$sql  = "SELECT	ocupacion_descripcion ";
			$sql .= "FROM		ocupaciones ";
			$sql .= "WHERE	ocupacion_id = '".$ocupa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['ocupacion_descripcion'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerEstadoCivil()
		{
			$sql  = "SELECT tipo_estado_civil_id,";
			$sql .= "				descripcion ";
			$sql .= "FROM		tipo_estado_civil ";
			$sql .= "WHERE 	tipo_estado_civil_id !=0 ";
			$sql .= "ORDER BY indice_de_orden ";
			
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
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerZonasResidencia()
		{
			$sql = " SELECT zona_residencia,descripcion FROM zonas_residencia ";
			
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
		/**********************************************************************************
		*
		***********************************************************************************/
		function IngresarDatosPaciente($datos)
		{
			($datos['estadocivil'] == '-1' || !$datos['estadocivil'])? $datos['estadocivil'] = "NULL": $datos['estadocivil'] = "'".$datos['estadocivil']."'";
			(!$datos['ocupacion_id'])? $datos['ocupacion_id'] = "NULL": $datos['ocupacion_id'] = "'".$datos['ocupacion_id']."'";
			(!$datos['ncomuna'])? $datos['ncomuna'] = "NULL": $datos['ncomuna'] = "'".$datos['ncomuna']."'";
			(!$datos['nbarrio'])? $datos['nbarrio'] = "NULL": $datos['nbarrio'] = "'".$datos['nbarrio']."'";
    	(!$datos['estrato'])? $datos['estrato'] = "NULL": $datos['estrato'] = "'".$datos['estrato']."'";
			(!$datos['pais'])? $datos['pais'] = "NULL": $datos['pais'] = "'".$datos['pais']."'";
    	(!$datos['dpto'])? $datos['dpto'] = "NULL": $datos['dpto'] = "'".$datos['dpto']."'";
    	(!$datos['mpio'])? $datos['mpio'] = "NULL": $datos['mpio'] = "'".$datos['mpio']."'";
			
			if(!$datos['zona']) $datos['zona'] = "U";
			
			$calculada = "0";
			if(!$datos['fechanacimiento'])
			{
				$mes = date("m");
				$dias = date("d");
				$anyo = date("Y");
				$calculada = "1";
				switch($datos['edad'])
				{
					case '1':
						$dias = $dias - (int)$datos['edadcalculada'];
						if(strlen($dias) == 1 ) $dias = "0".$dias;
					break;
					case '2': 
						$mes = $mes - (int)$datos['edadcalculada'];
						if(strlen($mes) == 1 ) $mes = "0".$mes;
					break;
					case '3': 
						$anyo = $anyo - (int)$datos['edadcalculada']; 
					break;
				}
				
				$datos['fechanacimiento'] = $anyo."-".$mes."-".$dias;
			}
			else
			{
				$fecha = explode("/",$datos['fechanacimiento']);
				$datos['fechanacimiento'] = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
		
			$sql  = "INSERT INTO pacientes ( ";
			$sql .= "		paciente_id, ";
			$sql .= "		tipo_id_paciente, ";
			$sql .= "		primer_apellido, ";
			$sql .= "		segundo_apellido, ";
			$sql .= "		primer_nombre, ";
			$sql .= "		segundo_nombre, ";
			$sql .= "		fecha_nacimiento, ";
			$sql .= "		fecha_nacimiento_es_calculada, ";
			$sql .= "		residencia_direccion, ";
			$sql .= "		residencia_telefono, ";
			$sql .= "		zona_residencia, ";
			$sql .= "		ocupacion_id, ";
			$sql .= "		fecha_registro, ";
			$sql .= "		sexo_id, ";
			$sql .= "		tipo_estado_civil_id, ";
			$sql .= "		foto, ";
			$sql .= "		tipo_pais_id, ";
			$sql .= "		tipo_dpto_id, ";
			$sql .= "		tipo_mpio_id, ";
			$sql .= "		nombre_madre, ";
			$sql .= "		usuario_id, ";
			$sql .= "		observaciones, ";
			$sql .= "		tipo_comuna_id, ";
			$sql .= "		tipo_barrio_id, ";
			$sql .= "		tipo_estrato_id, ";
			$sql .= "		lugar_expedicion_documento ) ";
			$sql .= "VALUES ('".$datos['paciente_id']."',";
			$sql .= "				'".$datos['tipo_id_paciente']."',";
			$sql .= "				'".strtoupper($datos['primerapellido'])."',";
			$sql .= "				'".strtoupper($datos['segundoapellido'])."',";
			$sql .= "				'".strtoupper($datos['primernombre'])."',";
			$sql .= "				'".strtoupper($datos['segundonombre'])."',";
			$sql .= "				'".$datos['fechanacimiento']."',";
			$sql .= "				'".$calculada."',";
			$sql .= "				'".strtoupper($datos['Direccion'])."',";
			$sql .= "				'".$datos['Telefono']."',";
			$sql .= "				'".$datos['zona']."',";
			$sql .= "				".$datos['ocupacion_id'].",";
			$sql .= "				NOW(),";
			$sql .= "				'".$datos['Sexo']."',";
			$sql .= "				".$datos['estadocivil'].",";
			$sql .= "				'".$datos['foto']."',";
			$sql .= "				".$datos['pais'].",";
			$sql .= "				".$datos['dpto'].",";
			$sql .= "				".$datos['mpio'].",";
			$sql .= "				'".$datos['Mama']."',";
			$sql .= "				".UserGetUID().",";
			$sql .= "				'".$datos['Observaciones']."',";
			$sql .= "				".$datos['ncomuna'].",";
			$sql .= "				".$datos['nbarrio'].",";
			$sql .= "				".$datos['estrato'].",";
			$sql .= "				'".$datos['lugar_expedicion_documento']."'); ";
			
			$this->ConexionTransaccion();
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$sql  = "INSERT INTO historias_clinicas( ";
			$sql .= "			paciente_id,";
			$sql .= "			tipo_id_paciente,";
			$sql .= "			historia_numero,";
			$sql .= "			historia_prefijo,";
			$sql .= "			fecha_creacion ";
			$sql .= "		) ";
			$sql .= "VALUES (	'".$datos['paciente_id']."',";
			$sql .= "					'".$datos['tipo_id_paciente']."',";
			$sql .= "					'".$datos['historia']."',";
			$sql .= "					'".$datos['prefijo_historia']."',";
			$sql .= "					NOW()";
			$sql .= "	);";
			if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			
			$sql = "";
						
			foreach($datos['metrica'] as $key => $metrica)
			{
				if(trim($metrica) != "")
				{
					$sql .= "INSERT	INTO	pacientes_metricas ";
					$sql .= "				(	paciente_id,";
					$sql .= "					tipo_id_paciente,";
					$sql .= "					tipo_metrica_id,";
					$sql .= "					valor_metrica,";
					$sql .= "					fecha_registro,";
					$sql .= "					sw_calculada,";
					$sql .= "					usuario_id ";
					$sql .= "				) ";
					$sql .= "VALUES (";
					$sql .= "			'".$datos['paciente_id']."', ";
					$sql .= "			'".$datos['tipo_id_paciente']."',";
					$sql .= "			'".$key."', ";
					$sql .= "			".$metrica.",";
					$sql .= "			NOW(),";
					$sql .= "			'0',";
					$sql .= "			".UserGetUID()." ";
					$sql .= "		);";
				}
			}
			if($sql != "")
			{
				if(!$rst = $this->ConexionTransaccion($sql,'1')) 
					return false;
			}
			
			$this->dbconn->CommitTrans();
			
			//CONDICION DEL PACIENTE
			if($datos['condicionUsuario'] <> -1 AND !empty($datos['condicionUsuario']))
			{
				$sql  = "INSERT INTO  pacientes_datos_adicionales( ";
				$sql .= "			paciente_id,";
				$sql .= "			tipo_id_paciente,";
				$sql .= "			tipos_condicion_usuarios_planes_id";
				$sql .= "		) ";
				$sql .= "VALUES (	'".$datos['paciente_id']."',";
				$sql .= "		'".$datos['tipo_id_paciente']."',";
				$sql .= "		'".$datos['condicionUsuario']."'";
				$sql .= "	);";
	
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			}
			//FIN CONDICION DEL PACIENTE
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
		function ActualizarDatosPaciente($datos)
		{     
			($datos['estadocivil'] == '-1' || !$datos['estadocivil'])? $datos['estadocivil'] = "NULL": $datos['estadocivil'] = "'".$datos['estadocivil']."'";
			(!$datos['ocupacion_id'])? $datos['ocupacion_id'] = "NULL": $datos['ocupacion_id'] = "'".$datos['ocupacion_id']."'";
			(!$datos['ncomuna'])? $datos['ncomuna'] = "NULL": $datos['ncomuna'] = "'".$datos['ncomuna']."'";
			(!$datos['nbarrio'])? $datos['nbarrio'] = "NULL": $datos['nbarrio'] = "'".$datos['nbarrio']."'";
    	(!$datos['estrato'])? $datos['estrato'] = "NULL": $datos['estrato'] = "'".$datos['estrato']."'";
			(!$datos['pais'])? $datos['pais'] = "NULL": $datos['pais'] = "'".$datos['pais']."'";
    	(!$datos['dpto'])? $datos['dpto'] = "NULL": $datos['dpto'] = "'".$datos['dpto']."'";
    	(!$datos['mpio'])? $datos['mpio'] = "NULL": $datos['mpio'] = "'".$datos['mpio']."'";
			
			if(!$datos['zona']) $datos['zona'] = "U";
			
			$calculada = "0";
			if(!$datos['fechanacimiento'])
			{
				$mes = date("m");
				$dias = date("d");
				$anyo = date("Y");
				$calculada = "1";
				switch($datos['edad'])
				{
					case '1':
						$dias = $dias - (int)$datos['edadcalculada'];
						if(strlen($dias) == 1 ) $dias = "0".$dias;
					break;
					case '2': 
						$mes = $mes - (int)$datos['edadcalculada'];
						if(strlen($mes) == 1 ) $mes = "0".$mes;
					break;
					case '3': 
						$anyo = $anyo - (int)$datos['edadcalculada']; 
					break;
				}
				
				$datos['fechanacimiento'] = $anyo."-".$mes."-".$dias;
			}
			else
			{
				
				$fecha = explode("/",$datos['fechanacimiento']);
				$datos['fechanacimiento'] = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			
			$sql 	= "UPDATE pacientes "; 
			$sql .= "SET		primer_apellido = '".strtoupper($datos['primerapellido'])."', ";
			$sql .= "				segundo_apellido = '".strtoupper($datos['segundoapellido'])."', ";
			$sql .= "				primer_nombre = '".strtoupper($datos['primernombre'])."', ";
			$sql .= "				segundo_nombre = '".strtoupper($datos['segundonombre'])."', ";
			$sql .= "				fecha_nacimiento = '".$datos['fechanacimiento']."', ";
			$sql .= "				fecha_nacimiento_es_calculada = '".$calculada."', ";
			$sql .= "				residencia_direccion = '".strtoupper($datos['Direccion'])."', ";
			$sql .= "				residencia_telefono = '".$datos['Telefono']."', ";
			$sql .= "				zona_residencia = '".$datos['zona']."', ";
			$sql .= "				ocupacion_id = ".$datos['ocupacion_id'].", ";
			$sql .= "				sexo_id = '".$datos['Sexo']."', ";
			$sql .= "				tipo_estado_civil_id = ".$datos['estadocivil'].", ";
			$sql .= "				tipo_pais_id = ".$datos['pais'].", ";
			$sql .= "				tipo_dpto_id = ".$datos['dpto'].", ";
			$sql .= "				tipo_mpio_id = ".$datos['mpio'].", ";
			$sql .= "				nombre_madre = '".$datos['Mama']."', ";
			$sql .= "				usuario_id = ".UserGetUID().", ";
			$sql .= "				observaciones = '".$datos['Observaciones']."', ";
			$sql .= "				tipo_comuna_id = ".$datos['ncomuna'].", ";
			$sql .= "				tipo_barrio_id = ".$datos['nbarrio'].", ";
			$sql .= "				tipo_estrato_id = ".$datos['estrato'].", ";
			$sql .= "				lugar_expedicion_documento = '".$datos['lugar_expedicion_documento']."' ";
			$sql .= "WHERE	paciente_id = '".$datos['paciente_id']."' ";
			$sql .= "AND		tipo_id_paciente = '".$datos['tipo_id_paciente']."'; ";

			$this->ConexionTransaccion();
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			
			$sql  = "UPDATE	historias_clinicas ";
			$sql .= "SET		historia_prefijo = '".strtoupper($datos['prefijo_historia'])."',";
			$sql .= "				historia_numero = '".$datos['historia']."' ";
			$sql .= "WHERE	paciente_id = '".$datos['paciente_id']."' ";
			$sql .= "AND		tipo_id_paciente = '".$datos['tipo_id_paciente']."'; ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$sql = "";
			
			//CONDICION DEL PACIENTE
		
			if($datos['condicionUsuario'] <> -1 AND !empty($datos['condicionUsuario']))
			{
				$sql  = "UPDATE pacientes_datos_adicionales ";
				$sql .= "SET tipos_condicion_usuarios_planes_id = ".$datos['condicionUsuario']." ";
				$sql .= "WHERE paciente_id = ".$datos['paciente_id']." ";
				$sql .= "AND tipo_id_paciente = '".$datos['tipo_id_paciente']."';";

				if(!$rst = $this->ConexionTransaccion($sql,'4')) return false;
				if($this->dbconn->Affected_Rows() == 0)
				{
					$sql  = "INSERT INTO  pacientes_datos_adicionales( ";
					$sql .= "			paciente_id,";
					$sql .= "			tipo_id_paciente,";
					$sql .= "			tipos_condicion_usuarios_planes_id";
					$sql .= "		) ";
					$sql .= "VALUES (	".$datos['paciente_id'].",";
					$sql .= "		'".$datos['tipo_id_paciente']."',";
					$sql .= "		".$datos['condicionUsuario']."";
					$sql .= "	);";
					if(!$rst = $this->ConexionTransaccion($sql,'5')) return false;
				}
				
				$sql = "";
			}
			//FIN CONDICION DEL PACIENTE
			
			foreach($datos['metrica'] as $key => $metrica)
			{
				$sqli  = "SELECT * "; 
				$sqli .= "FROM		pacientes_metricas "; 
				$sqli .= "WHERE		paciente_id = '".$datos['paciente_id']."' ";
				$sqli .= "AND			tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
				$sqli .= "AND			tipo_metrica_id = '".$key."' ";
				if(!$rst = $this->ConexionTransaccion($sqli,'3')) return false;
				
				if($metrica != "")
				{
					if(empty($rst->fields) )
					{
						$sql .= "INSERT	INTO	pacientes_metricas ";
						$sql .= "				(	paciente_id,";
						$sql .= "					tipo_id_paciente,";
						$sql .= "					tipo_metrica_id,";
						$sql .= "					valor_metrica,";
						$sql .= "					fecha_registro,";
						$sql .= "					sw_calculada,";
						$sql .= "					usuario_id ";
						$sql .= "				) ";
						$sql .= "VALUES (";
						$sql .= "			'".$datos['paciente_id']."', ";
						$sql .= "			'".$datos['tipo_id_paciente']."',";
						$sql .= "			'".$key."', ";
						$sql .= "			".$metrica.",";
						$sql .= "			NOW(),";
						$sql .= "			'0',";
						$sql .= "			".UserGetUID()." ";
						$sql .= "		);";
					}
					else
					{
						$sql .= "UPDATE	pacientes_metricas ";
						$sql .= "SET		valor_metrica = ".$metrica.",";
						$sql .= "				usuario_id = ".UserGetUID()." ";
						$sql .= "WHERE	paciente_id = '".$datos['paciente_id']."' ";
						$sql .= "AND		tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
						$sql .= "AND		tipo_metrica_id = '".$key."'; ";
					}
				}
			}
			if($sql != "")
			{
				if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			}
			
		
						
			
			
			
			
			
			$this->dbconn->CommitTrans();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerPacientes($nombre,$apellido)
		{
			IncludeClass('ClaseUtil');
			$ctl = new ClaseUtil();

			$filtro = $ctl->FiltrarNombres($nombre,$apellido);
			
			$sql  = "SELECT	PA.paciente_id,";
			$sql .= " 			PA.tipo_id_paciente,";
			$sql .= " 			PA.primer_apellido,";
			$sql .= " 			PA.segundo_apellido,";
			$sql .= " 			PA.primer_nombre,";
			$sql .= " 			PA.segundo_nombre ";
			$sql .= "FROM		pacientes PA ";
			$sql .= "WHERE 	".$filtro." ";
			
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
		* Funcion donde se obtienen los tipos de afiliados de un plan
    *
    * @param interger $plan Identificador del plan
    * @param string $tipo_afiliado Identificador del tipo de afiliado
    *
    * @return mixed
		*/
		function ObtenerTiposAfiliados($plan,$tipo_afiliado)
		{
     
			$sql  = "SELECT DISTINCT TA.tipo_afiliado_nombre,";
			$sql .= "				TA.tipo_afiliado_id ";
			$sql .= "FROM		tipos_afiliado TA,";
			$sql .= "				planes_rangos PR ";
			$sql .= "WHERE 	PR.plan_id = ".$plan." ";
			$sql .= "AND		PR.tipo_afiliado_id = TA.tipo_afiliado_id ";
      if($tipo_afiliado != "")
        $sql .= "AND    TA.tipo_afiliado_id = '".$tipo_afiliado."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$tiposafiliados = array();
			while (!$rst->EOF)
			{
				$tiposafiliados[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $tiposafiliados;
		}
		/**
		* Funcion donde se obtienen los rangos asociados a un plan
    *
    * @param interger $plan Identificador del plan
    * @param string $rango Identificador del rango
    *
    * @return mixed
		*/
		function ObtenerRangosNiveles($plan, $rango)
		{
			$sql  = "SELECT DISTINCT rango ";
			$sql .= "FROM		planes_rangos ";
			$sql .= "WHERE 	plan_id = ".$plan." ";
      if($rango != "")
        $sql .= "AND    rango = '".$rango."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$rangos = array();
			while (!$rst->EOF)
			{
				$rangos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $rangos;
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function ObtenerTiposUsuariosPlan($plan)
		{
			$sql  = "SELECT b.descripcion, b.	tipos_condicion_usuarios_planes_id ";
			$sql .= "FROM	planes_condicion_usuario a, ";
			$sql .= "			tipos_condicion_usuarios_planes b, ";
			$sql .= "			planes c ";
			$sql .= "WHERE 	a.plan_id = ".$plan." ";
			$sql .= "AND 	a.tipos_condicion_usuarios_planes_id = b.tipos_condicion_usuarios_planes_id ";
			$sql .= "AND 	a.plan_id = c.plan_id ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$rangos = array();
			while (!$rst->EOF)
			{
				$rangos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $rangos;
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function ObtenerTiposEventos()
		{
			$sql  = "SELECT * ";
			$sql .= "FROM	soat_naturaleza_evento ";
			$sql .= "ORDER BY soat_naturaleza_evento_id ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$rangos = array();
			while (!$rst->EOF)
			{
				$rangos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $rangos;
		}
		
		function BuscarCondicion()//Busca las condiciones del accidentado
		{
			$query = "SELECT condicion_accidentado,
				descripcion
				FROM condicion_accidentados
				ORDER BY descripcion;";
			if(!$rst = $this->ConexionBaseDatos($query)) return false;
			$i=0;
			while(!$rst->EOF)
			{
				$var[$i]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}
			$rst->Close();
			return $var;
		}
		
		function IngresoActivo($tipo_id_paciente, $paciente_id)
		{
			$sql  = "SELECT	COUNT(*) ";
			$sql .= "FROM		ingresos ";
			$sql .= "WHERE	paciente_id = '".$paciente_id."' "; 
			$sql .= "AND		tipo_id_paciente = '".$tipo_id_paciente."' "; 
			$sql .= "AND		estado ='1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			if(!$rst->EOF)
			{
				$cantidad  = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $cantidad;
		}
	/* OBTENER DATOS DE PACIENTE SI ESTA AFILIADO A UNA ESM*/
		Function Validar_Paciente_ESM($tipo_id_paciente,$paciente_id)
	{
		$sql = " SELECT  tipo_id_paciente,
						 paciente_id,
						 tipo_id_tercero,
						 tercero_id
				 FROM    esm_pacientes
				 WHERE   tipo_id_paciente = '".$tipo_id_paciente."'
				 AND     paciente_id = '".$paciente_id."' ";
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
	/* SACAR LOS TERCEROS QUE ESTAN PARAMETRIZADOS COMO ESM */

		function _ESM()
		{
			$sql = " SELECT 	esm.tipo_id_tercero,
								esm.tercero_id,
								terc.nombre_tercero
					FROM        esm_empresas esm,
								terceros terc
					WHERE       esm.tipo_id_tercero=terc.tipo_id_tercero
								and     esm.tercero_id=terc.tercero_id
								order by terc.nombre_tercero	 ";
					 if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				      $datos = array();
				      while(!$rst->EOF)
				      {
				        $datos[]= $rst->GetRowAssoc($ToUpper = false);
				        $rst->MoveNext();
				      }
				      $rst->Close();
				      return $datos;
				
		}
	/* CONSULTAR SI EL PACIENTE ESTA ASOCIADO A UNA FUERZA */
	
		function Pacientes_Fuerza($tipo_id_paciente,$paciente_id)
		{
		
			$sql = " SELECT  tipo_fuerza_id
						FROM    esm_pacientes_fuerzas
						WHERE   tipo_id_paciente = '".$tipo_id_paciente."'
						AND     paciente_id = '".$paciente_id."' ";
					 if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				      $datos = array();
				      while(!$rst->EOF)
				      {
				        $datos[]= $rst->GetRowAssoc($ToUpper = false);
				        $rst->MoveNext();
				      }
				      $rst->Close();
				      return $datos;
				
		}
	/* CONSULTAR LOS TIPOS DE FUERZAS*/
	
	function Tipos_Fuerzas()
		{
			$sql = " SELECT 	tipo_fuerza_id,
							     descripcion
								
					FROM        esm_tipos_fuerzas esm
					WHERE       sw_activo = '1'	 ORDER BY 	descripcion ";
					
					 if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				      $datos = array();
				      while(!$rst->EOF)
				      {
				        $datos[]= $rst->GetRowAssoc($ToUpper = false);
				        $rst->MoveNext();
				      }
				      $rst->Close();
				      return $datos;
				
		}
	
		/* ingresar PACIENTES A UNA ESM */
			
		function Insertar_PacientesESM($datos)
		{
		
			$this->ConexionTransaccion();
			list($esm_tipo_id_tercero,$esm_tercero_id) = explode("@",$datos['esm_pac']);
	   			
	
			$sql  = "INSERT INTO esm_pacientes (";
			$sql .= "       tipo_id_paciente, ";
			$sql .= "       paciente_id, ";
			$sql .= "       tipo_id_tercero, ";
			$sql .= "       tercero_id ";
			$sql .= "          ) ";
			$sql .= "VALUES ( ";
			$sql .= "        '".$datos['tipo_id_paciente']."', ";
			$sql .= "        '".$datos['paciente_id']."', ";
			$sql .= "        '".$esm_tipo_id_tercero."', ";
			$sql .= "        '".$esm_tercero_id."'    ";
			$sql .= "       ); ";			
			   if(!$rst1 = $this->ConexionTransaccion($sql))
		      {
		      return false;
		      }
	
				
				
				$this->Commit();
				return true;
		}
		/* ASOCIAR A FUERZAS */
	function Insertar_FuerzasESM($datos)
	{	

		    	$this->ConexionTransaccion();
	        $sql  = "INSERT INTO esm_pacientes_fuerzas (";
			$sql .= "       tipo_fuerza_id, ";
			$sql .= "       tipo_id_paciente, ";
			$sql .= "       paciente_id ";
			$sql .= "          ) ";
			$sql .= "VALUES ( ";
			$sql .= "        ".$datos['tipo_fuerza_i'].", ";
			$sql .= "        '".$datos['tipo_id_paciente']."', ";
			$sql .= "        '".$datos['paciente_id']."' ";
			
			$sql .= "       ); ";			
			   if(!$rst1 = $this->ConexionTransaccion($sql))
		      {
		      return false;
		      }
	
				
				
				$this->Commit();
				return true;
	}
		
	}
?>