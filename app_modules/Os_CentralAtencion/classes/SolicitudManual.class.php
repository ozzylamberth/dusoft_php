<?php
	/**************************************************************************************
	* $Id: SolicitudManual.class.php,v 1.1 2010/01/20 20:58:30 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F. Manrique
	***************************************************************************************/
	class SolicitudManual
	{
		function SolicitudManual(){}
		/***************************************************************************************
		* Funcion donde se obtienen los tipos de servicios asistenciales
		*
		* @return array Arreglo de datos con el servicio y la descripcion 
		***************************************************************************************/
		function ObtenerTiposServicios()
		{
			$sql  = "SELECT	servicio,";
			$sql .= "				descripcion ";
			$sql .= "FROM		servicios ";
			$sql .= "WHERE	sw_asistencial = '1' ";
			
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
		/***************************************************************************************
		* Funcion donde se obtienen los tipos de servicios asistenciales
		*
		* @return array Arreglo de datos con el servicio y la descripcion 
		***************************************************************************************/
		function ObtenerServicioDepartamento($departamento)
		{
			$sql  = "SELECT	SE.servicio,";
			$sql .= "				SE.descripcion ";
			$sql .= "FROM		departamentos DE, ";
			$sql .= "				servicios SE ";
			$sql .= "WHERE	SE.sw_asistencial = '1' ";
			$sql .= "AND		SE.servicio = DE.servicio ";
			$sql .= "AND		DE.departamento = '".$departamento."' ";
			
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
		/***************************************************************************************
		* Funcion donde se hace la busqueda de cargos que pertenecen a un departamento en 
		* especial
		*
		* @params $cargo String Id del cargo que se buscara
		* @params $descripcion String Descripcion del cargo que se buscara
		* @params $opcion String Indica si se buscaran dentro de los cargos frecuentes o todos
		* @params $departamento String Identificador del departamento
		* @params $off String Indica la pagina en la que se encuentra el buscador actualmente
		*
		* @return array Arreglo de datos con la informacion de los cargos 
		***************************************************************************************/
		function ObtenerCargos($cargo,$descripcion,$opcion,$departamento = null,$off)
		{
			$sql  = "SELECT DISTINCT CU.cargo,";
			$sql .= "				CU.descripcion, ";
			$sql .= "				AT.apoyod_tipo_id, ";
			$sql .= "				AT.descripcion as tipo, ";
			$sql .= "				CU.sw_cantidad ";
			$sql .= "FROM		cups CU,";
			$sql .= "				apoyod_tipos AT, ";
			$sql .= "				departamentos_cargos DC ";
			if($opcion == "002")
				$sql .= "				,apoyod_solicitud_frecuencia AF ";
			
			$sql .= "WHERE	CU.grupo_tipo_cargo = AT.apoyod_tipo_id ";
			$sql .= "AND		CU.cargo = DC.cargo ";			
			$sql .= "AND		CU.cargo ILIKE '".$cargo."%'";
			$sql .= "AND		CU.descripcion ILIKE '%".$descripcion."%'";
			$sql .= "AND 		CU.sw_activo = '1' ";
			
			if($departamento)
				$sql .= "AND		DC.departamento = '".$departamento."' ";
			
			if($opcion == "002")
			{
				$sql .= "AND		AF.departamento = '".$departamento."' ";
				$sql .= "AND		CU.cargo = AF.cargo ";
			}
			
			$cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$cant,$off);
			
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
		}
		/***************************************************************************************
		* Funcion donde se crea la solicitud manual, con los cargos adicionados
		* @params $datos Arreglo de datos con la informacion del paciente y los cargos 
		*		de la solicitud
		* @params $crgadd Arreglo de datos con la informacion de los cargos
		* @params $dpto Arreglo de daros con la informacion de la empresa y el departamento
		* 
		* @return array Arreglo de datos donde se asocia la solicitud o solicitudes 
		*		con los cargos de las mismas
		***************************************************************************************/
		function IngresarSolictudManual($datos,$crgadd,$dpto)
		{
			$solicitudes = array();
			$hc_os_solicitud_id = "";
			$cargosadd = $datos['cargosadd'];
			$f = explode("/",$datos['fecha']);
			
			foreach($crgadd as $key => $cargos)
      {
				$crg = $cargos[0];
				
        $sql = "SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				$retorno = array();
				if(!$rst->EOF)
				{
					$hc_os_solicitud_id = $rst->fields[0];
					$rst->MoveNext();
				}
				
				$sql  = "INSERT INTO hc_os_solicitudes ";
				$sql .= "		(	hc_os_solicitud_id, ";
				$sql .= "			cargo,";
				$sql .= "			os_tipo_solicitud_id, ";
				$sql .= "			plan_id,";
				$sql .= "			paciente_id, ";
				$sql .= "			tipo_id_paciente )";
				$sql .= "VALUES(";
				$sql .= "			".$hc_os_solicitud_id.",";
				$sql .= "			'".$key."',";
				$sql .= "			'APD',";
				$sql .= "			 ".$datos['plan_id'].", ";
				$sql .= "			'".$datos['paciente_id']."', ";
				$sql .= "			'".$datos['tipo_id_paciente']."'); ";

				$sql .= "INSERT INTO hc_os_solicitudes_apoyod ";
				$sql .= "		(	hc_os_solicitud_id, ";
				$sql .= "			apoyod_tipo_id ) ";
				$sql .= "VALUES(";
				$sql .= "		 ".$hc_os_solicitud_id.", ";
				$sql .= "		'".$crg['apoyo_id']."'); ";

				$sql .= "INSERT INTO hc_os_solicitudes_manuales(";
				$sql .= "			hc_os_solicitud_id,";
				$sql .= "			fecha,";
				$sql .= "			servicio,";
				$sql .= "			profesional,";
				$sql .= "			prestador,";
				$sql .= "			observaciones,";
				$sql .= "			tipo_id_paciente,";
				$sql .= "			paciente_id,";
				$sql .= "			fecha_resgistro,";
				$sql .= "			usuario_id,";
				$sql .= "			empresa_id,";
				$sql .= "			departamento,";
				$sql .= " 		semanas_cotizadas,";
				$sql .= " 		rango,";
				$sql .= "			tipo_afiliado_id,";
				$sql .= " 		evento_soat)";
				$sql .= "VALUES(";
				$sql .= "		 ".$hc_os_solicitud_id.",";
				$sql .= " 	'".$f[2]."-".$f[1]."-".$f[0]."',";
				$sql .= "		'".$datos['servicio']."',";
				$sql .= "		'',";
				$sql .= "		'',";
				$sql .= "		'".$datos['observacion']."',";
				$sql .= "		'".$datos['tipo_id_paciente']."',";
				$sql .= "		'".$datos['paciente_id']."',";
				$sql .= "		NOW(),";
				$sql .= "		".UserGetUID().",";
				$sql .= "		'".$dpto['empresa_id']."',";
				if($dpto['departamento'])
					$sql .= "		'".$dpto['departamento']."',";
				else
					$sql .= "		 NULL,";
				$sql .= "		  ".$datos['afiliado']['Semanas'].",";
				$sql .= "		 '".$datos['afiliado']['rango']."',";
				$sql .= "		 '".$datos['afiliado']['tipoafiliado']."',";
				if($datos['evento_soat'])
					$sql .= "		 ".$datos['evento_soat'].");";
				else
					$sql .= "		 NULL);";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				$solicitudes[$hc_os_solicitud_id][$key][$cargosadd[$key]['cargo']] = $cargosadd[$key]['cantidad'];
      }
			
			return $solicitudes;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerPacientes($datos,$empresa,$offset)
		{
			$sql  = "SELECT	P.primer_nombre||' '||P.segundo_nombre AS nombre,";
			$sql .= "				P.primer_apellido||' '||P.segundo_apellido AS apellido,";
			$sql .= "				P.paciente_id,";
			$sql .= "				P.tipo_id_paciente,";
			$sql .= "				D.plan_descripcion,";
			$sql .= "				C.numerodecuenta, ";
			$sql .= "				C.ingreso, ";
			$sql .= "				C.plan_id, ";
			$sql .= "				C.rango, ";
			$sql .= "				C.tipo_afiliado_id, ";
			$sql .= "				C.semanas_cotizadas, ";
			$sql .= "				I.departamento_actual ";
			$sql .= "FROM		pacientes P,";
			$sql .= "				ingresos I,";
			$sql .= "				cuentas C, ";
			$sql .= "				planes D ";
			$sql .= "WHERE 	I.tipo_id_paciente = P.tipo_id_paciente ";
			$sql .= "AND 		I.paciente_id = P.paciente_id ";
			$sql .= "AND 		C.plan_id = D.plan_id ";
			$sql .= "AND 		D.sw_tipo_plan NOT IN('1') ";
			$sql .= "AND 		C.estado = '1' ";
			$sql .= "AND 		I.estado = '1' ";
			$sql .= "AND 		C.ingreso = I.ingreso ";
			$sql .= "AND 		C.empresa_id = '".$empresa."' ";
			
			if($datos['numerodecuenta'])
				$sql .= "AND		C.numerodecuenta = ".$datos['numerodecuenta']." ";
			
			if($datos['ingreso'])
				$sql .= "AND		I.ingreso = ".$datos['ingreso']." ";

			if($datos['paciente_id'])
			{
				$sql .= "AND 		P.tipo_id_paciente = '".$datos['tipo_id_paciente']."'  ";
				$sql .= "AND 		P.paciente_id = '".$datos['paciente_id']."' ";
			}
			
			if(!empty($datos['nombres']) || !empty($datos['apellidos']))
			{
				IncludeClass('ClaseUtil');
				$cl = new ClaseUtil();
				$sql .= "AND		".$cl->FiltrarNombres($datos['nombres'],$datos['apellidos'],"P");
			}
			$sql .= "ORDER BY I.tipo_id_paciente,I.paciente_id ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$cont = 0;
			if(!$rst->EOF) $cont = $rst->RecordCount();

			if($cont > 0)
			{
				$this->ProcesarSqlConteo($sql2,$cont,$offset,20);
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
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
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
	}
?>