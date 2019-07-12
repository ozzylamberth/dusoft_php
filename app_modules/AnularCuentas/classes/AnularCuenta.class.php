<?php
  /******************************************************************************
  * $Id: AnularCuenta.class.php,v 1.1 2007/02/12 14:50:35 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
  * Proposito del Archivo:	Manejo logico de la logica del modulo de 
	*													anulacion de cuentas
  ********************************************************************************/
	class AnularCuenta
	{
		function AnularCuenta(){}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerPermisos($usuario)
		{
			$sql  = "SELECT	UC.empresa_id, ";
			$sql .= "				EM.razon_social ";
			$sql .= "FROM		empresas EM, ";
			$sql .= "				userpermisos_anulacion_cuentas UC ";
			$sql .= "WHERE	UC.usuario_id = ".$usuario." ";
			$sql .= "AND		UC.empresa_id = EM.empresa_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
		function ObtenerTipoIdPaciente()
		{
			$sql  = "SELECT tipo_id_paciente,";
			$sql .= "				descripcion ";
			$sql .= "FROM 	tipos_id_pacientes ";
			$sql .= "ORDER BY indice_de_orden	";
			
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
		*
		***********************************************************************************/
		function ObtenerCuentas($datos,$emp,$dptno,$offset,$cant)
		{
			$sql .= "SELECT	A.numerodecuenta,";
			$sql .= "				A.ingreso,";
			$sql .= "				A.total_cuenta,";
			$sql .= "				D.plan_descripcion,";
			$sql .= "				TO_CHAR(A.fecha_registro, 'DD/MM/YYYY HH:MI AM') AS fecha,  ";
			$sql .= "				I.cama,  ";
			$sql .= "				I.pieza,  ";
			$sql .= "				C.tipo_id_paciente,  ";
			$sql .= "				C.paciente_id,  ";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre,   ";
			$sql .= "				CASE 	WHEN A.estado = '0' THEN 'FACTURADA' ";
			$sql .= "							WHEN A.estado = '1' THEN 'ACTIVA' ";
			$sql .= "							WHEN A.estado = '2' THEN 'INACTIVA' ";
			$sql .= "							WHEN A.estado = '3' THEN 'CUADRADA' ";
			$sql .= "							WHEN A.estado = '4' THEN 'ANTICIPOS' ";
			$sql .= "							WHEN A.estado = '5' THEN 'ANULADA' END AS estado  ";
			$sql .= "FROM 	cuentas A ";
			$sql .= "				ingresos B,";
			$sql .= "				pacientes C, ";
			$sql .= "				planes D ";
			$sql .= "WHERE 	A.empresa_id = '".$emp."' ";
			//$sql .= "AND 		A.estado NOT IN('0','5') ";
			$sql .= "AND 		A.ingreso = B.ingreso ";
			$sql .= "AND 		A.plan_id = D.plan_id ";
			$sql .= "AND 		B.tipo_id_paciente = C.tipo_id_paciente  ";
			$sql .= "AND 		B.paciente_id = C.paciente_id ";
			if($dptno)
				$sql .= "AND		B.departamento_actual IN (".$dptno.") ";
			$sql .= "ORDER BY A.numerodecuenta ";

			$cont  = "SELECT COUNT(*) FROM ($sql) AS A ";

			$this->ProcesarSqlConteo($cont,$cant,$offset);

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
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerCuentasXIngreso($datos,$emp,$dptno,$offset)
		{
			$sql .= "SELECT	A.numerodecuenta,";
			$sql .= "				A.ingreso,";
			$sql .= "				A.total_cuenta,";
			$sql .= "				D.plan_descripcion,";
			$sql .= "				TO_CHAR(A.fecha_registro, 'DD/MM/YYYY HH:MI AM') AS fecha,  ";
			$sql .= "				C.tipo_id_paciente,  ";
			$sql .= "				C.paciente_id,  ";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre,   ";
			$sql .= "				CASE 	WHEN A.estado = '0' THEN 'FACTURADA' ";
			$sql .= "							WHEN A.estado = '1' THEN 'ACTIVA' ";
			$sql .= "							WHEN A.estado = '2' THEN 'INACTIVA' ";
			$sql .= "							WHEN A.estado = '3' THEN 'CUADRADA' ";
			$sql .= "							WHEN A.estado = '4' THEN 'ANTICIPOS' ";
			$sql .= "							WHEN A.estado = '5' THEN 'ANULADA' END AS estado  ";
			$from .= "FROM 		cuentas A, ";
			$from .= "				ingresos B,";
			$from .= "				pacientes C, ";
			$from .= "				planes D ";
			$from .= "WHERE 	A.empresa_id = '".$emp."' ";
			//$from .= "AND 		A.estado NOT IN('0','5') ";
			$from .= "AND 		A.ingreso = B.ingreso ";
			$from .= "AND 		D.plan_id = A.plan_id ";
			$from .= "AND 		B.tipo_id_paciente = C.tipo_id_paciente  ";
			$from .= "AND 		B.paciente_id = C.paciente_id ";
			if($dptno)
				$from .= "AND			B.departamento_actual IN (".$dptno.") ";
				
			if($datos['Cuenta'])
				$from .= "AND 		A.numerodecuenta = ".$datos['Cuenta']." ";
				
			if($datos['Ingreso'])
				$from .= "AND 		B.ingreso = ".$datos['Ingreso']." ";
			
			$cont  = "SELECT COUNT(*) $from ";

			$this->ProcesarSqlConteo($cont,null,$offset);

			$sql .= "$from ";
			$sql .= "ORDER BY A.numerodecuenta ";
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
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerCuentasXIdPaciente($datos,$emp,$dptno,$offset)
		{
			$sql .= "SELECT	C.*, ";
			$sql .= "				C.nombre||' '||C.apellido AS nombre, ";
			$sql .= "				CASE 	WHEN C.estado = '0' THEN 'FACTURADA' ";
			$sql .= "							WHEN C.estado = '1' THEN 'ACTIVA' ";
			$sql .= "							WHEN C.estado = '2' THEN 'INACTIVA' ";
			$sql .= "							WHEN C.estado = '3' THEN 'CUADRADA' ";
			$sql .= "							WHEN C.estado = '4' THEN 'ANTICIPOS' ";
			$sql .= "							WHEN C.estado = '5' THEN 'ANULADA' END AS estado  ";
			$sql .= "FROM 	(	SELECT	P.primer_nombre||' '||P.segundo_nombre AS nombre,";
			$sql .= "									P.primer_apellido||' '||P.segundo_apellido AS apellido,";
			$sql .= "									P.paciente_id,";
			$sql .= "									P.tipo_id_paciente,";
			$sql .= "									TO_CHAR(C.fecha_registro, 'DD/MM/YYYY HH:MI AM') AS fecha,  ";
			$sql .= "									C.numerodecuenta,";
			$sql .= "									C.ingreso,";
			$sql .= "									C.estado,";
			$sql .= "									C.total_cuenta, ";
			$sql .= "									L.plan_descripcion ";			
			$sql .= "					FROM		pacientes P,";
			$sql .= "									ingresos I,";
			$sql .= "									cuentas C, ";
			$sql .= "									planes L ";
			$sql .= "					WHERE 	I.tipo_id_paciente = P.tipo_id_paciente ";
			$sql .= "					AND 		I.paciente_id = P.paciente_id ";
			//$sql .= "					AND 		C.estado NOT IN('0','5') ";
			$sql .= "					AND 		C.ingreso = I.ingreso ";
			$sql .= "					AND 		C.plan_id = L.plan_id ";
			$sql .= "					AND 		C.empresa_id = '".$emp."' ";
			$sql .= "					AND 		P.tipo_id_paciente = '".$datos['TipoDocumento']."'  ";
			$sql .= "					AND 		P.paciente_id = '".$datos['Documento']."' ";
			if($dptno)
				$sql .= "					AND			I.departamento_actual IN (".$dptno.") ";
			$sql .= "				)AS C  ";
			$sql .= "ORDER BY C.numerodecuenta ";			

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$cont = 0;
			if(!$rst->EOF) $cont = $rst->RecordCount();
			
			if($cont > 0)	$this->ProcesarSqlConteo($sql2,$cont,$offset);

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
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerCuentasXNombrePaciente($datos,$emp,$dptno,$offset)
		{
			$filtro = $this->FiltrarNombres($datos['Nombres'],$datos['Apellidos'],'P');
			
			$sql .= "SELECT	C.*, ";
			$sql .= "				C.nombre||' '||C.apellido AS nombre, ";
			$sql .= "				CASE 	WHEN C.estado = '0' THEN 'FACTURADA' ";
			$sql .= "							WHEN C.estado = '1' THEN 'ACTIVA' ";
			$sql .= "							WHEN C.estado = '2' THEN 'INACTIVA' ";
			$sql .= "							WHEN C.estado = '3' THEN 'CUADRADA' ";
			$sql .= "							WHEN C.estado = '4' THEN 'ANTICIPOS'  ";
			$sql .= "							WHEN C.estado = '5' THEN 'ANULADA' END AS estado  ";
			$sql .= "FROM 	(	SELECT	P.primer_nombre||' '||P.segundo_nombre AS nombre,";
			$sql .= "									P.primer_apellido||' '||P.segundo_apellido AS apellido,";
			$sql .= "									P.paciente_id,";
			$sql .= "									P.tipo_id_paciente,";
			$sql .= "									TO_CHAR(C.fecha_registro, 'DD/MM/YYYY HH:MI AM') AS fecha,  ";
			$sql .= "									C.numerodecuenta,";
			$sql .= "									C.ingreso,";
			$sql .= "									C.estado,";
			$sql .= "									C.total_cuenta, ";
			$sql .= "									L.plan_descripcion";
			$sql .= "					FROM		pacientes P,";
			$sql .= "									ingresos I,";
			$sql .= "									cuentas C, ";
			$sql .= "									planes L ";
			$sql .= "					WHERE 	I.tipo_id_paciente = P.tipo_id_paciente ";
			$sql .= "					AND 		I.paciente_id = P.paciente_id ";
			$sql .= "					AND 		C.plan_id = L.plan_id ";
			if($dptno)
				$sql .= "					AND			I.departamento_actual IN (".$dptno.") ";
			//$sql .= "					AND 		C.estado NOT IN('0','5') ";
			$sql .= "					AND 		C.ingreso = I.ingreso ";
			$sql .= "					AND 		C.empresa_id = '".$emp."' ";
			$sql .= "					AND 		".$filtro." ";
			$sql .= "				)AS C  ";
			$sql .= "ORDER BY C.numerodecuenta ";			

			$sql2 = "SELECT COUNT(*) FROM ($sql) AS A ";
			$this->ProcesarSqlConteo($sql2,null,$offset);

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
		/**********************************************************************************
		*
		***********************************************************************************/
		function RegistrarAnulacionCuenta($datos,$empresa)
		{
			$sql  = "SELECT COUNT(*) AS cantidad ";
			$sql .= "FROM		cuentas ";
			$sql .= "WHERE	ingreso = ".$datos['ingreso']." ";
			$sql .= "AND		estado NOT IN('0','5') ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$cantidad = array();
			if(!$rst->EOF)
			{
				$cantidad = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			$sql = "";
			if($cantidad['cantidad'] == 1)
			{
				$sql .= "UPDATE	ingresos ";
				$sql .= "SET		estado = '0',";
				$sql .= "				fecha_cierre = NOW()";
				$sql .= "WHERE	ingreso = ".$datos['ingreso']."; ";
				
			}

			$sql .= "UPDATE	cuentas ";
			$sql .= "SET		estado = '5' ";
			$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";
			
			$sql .= "INSERT INTO auditoria_anulacion_cuentas( ";
			$sql .= "			empresa_id,";
			$sql .= "			numerodecuenta,";
			$sql .= "			observacion,";
			$sql .= "			fecha_registro,";
			$sql .= "			usuario_id ";
			$sql .= "		)";
			$sql .= "VALUES (";
			$sql .= "		'".$empresa."',";
			$sql .= "		 ".$datos['numerodecuenta'].",";
			$sql .= "		'".$datos['observacion']."',";
			$sql .= "		 NOW(),";
			$sql .= "		 ".UserGetUID()." ";
			$sql .= "		);";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FiltrarNombres($nombres,$apellidos,$alias)
		{
			$nombres = trim(strtoupper($nombres));
			$apellidos = trim(strtoupper($apellidos));
			if($alias) $alias .= ".";
			
			if ($nombres != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$nombres));//QUITA DOBLE ESPACIOS INTERNOS

				switch(count($a))
				{
					case 1:
						$filtroNombres .= " (".$alias."primer_nombre  SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)' OR ".$alias."segundo_nombre SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)')";
					break;
					case 2:
						$filtroNombres  = " ".$alias."primer_nombre SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						next($a);
						$filtroNombres .= " AND ((".$alias."primer_nombre SIMILAR TO '%[[:space:]]".current($a)."') OR (".$alias."segundo_nombre ILIKE '".current($a)."'))";
					break;
					default:
						$filtroNombres = " ".$alias."primer_nombre SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						for($i=2;$i<count($a);$i++)
						{
							next($a);
							$filtroNombres .= " AND ((".$alias."primer_nombre SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																	OR (".$alias."segundo_nombre SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
						}
						next($a);
						$filtroNombres .= " AND ((".$alias."primer_nombre SIMILAR TO '%[[:space:]]".current($a)."')  OR  (".$alias."segundo_nombre SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
					break;
				}
			}

			if ($apellidos != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$apellidos));

				switch(count($a))
				{
					case 1:
							$filtroApellidos  = " ".$alias."primer_apellido ILIKE '".current($a)."'";
					break;

					case 2:
							$filtroApellidos  = " ".$alias."primer_apellido SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							next($a);
							$filtroApellidos .= " AND ((".$alias."primer_apellido SIMILAR TO '%[[:space:]]".current($a)."') OR (".$alias."segundo_apellido ILIKE '".current($a)."'))";
					break;

					default:
							$filtroApellidos  = " ".$alias."primer_apellido SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							for($i=2;$i<count($a);$i++)
							{
								next($a);
								$filtroApellidos .= " AND ((".$alias."primer_apellido SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																						OR (".$alias."segundo_apellido SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
							}
							next($a);
							$filtroApellidos .= " AND ((".$alias."primer_apellido SIMILAR TO '%[[:space:]]".current($a)."')  OR  (".$alias."segundo_apellido SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
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