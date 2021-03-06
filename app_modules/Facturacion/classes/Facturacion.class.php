<?php
  /******************************************************************************
  * $Id: Facturacion.class.php,v 1.9 2007/04/03 21:42:05 cjrodriguez Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.9 $ 
	* 
	* @autor Hugo F  Manrique 
  * Proposito del Archivo:	Manejo logico de la logica del modulo de 
	*													anulacion de facturas
  ********************************************************************************/
	class Facturacion
	{
		var $offset = 0;
		
		function Facturacion(){}
		/**********************************************************************************
		* 
		* 
		* @params int $usuario Identificador del usuario
		* @return 
		***********************************************************************************/
		function ObtenerPermisos($usuario)
		{
			$sql  = "SELECT	EM.razon_social,";
			$sql .= "				DE.descripcion, ";
			$sql .= "				EM.empresa_id, ";
			$sql .= "				DE.departamento, ";
			$sql .= "				CU.descripcion AS centro, ";
			$sql .= "				CU.centro_utilidad, ";
			$sql .= "				UC.documento_id ";
			$sql .= "FROM		empresas EM,";
			$sql .= "				departamentos DE, ";
			$sql .= "				centros_utilidad CU, ";
			$sql .= "				userpermisos_cuentas UC ";
			$sql .= "WHERE	UC.usuario_id = ".$usuario." ";
			$sql .= "AND		UC.departamento = DE.departamento ";
			$sql .= "AND		DE.empresa_id = EM.empresa_id ";
			$sql .= "AND		CU.empresa_id = EM.empresa_id ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			$deptno = array();

			while (!$rst->EOF)
			{
				$deptno[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				//$datos['segurid'][$rst->fields[2]][$rst->fields[3]] = 1;
				//$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			foreach($deptno as $key => $var)
			{
				$cadena = array();
				$i=0;
				foreach($var as $keyI => $varI)
				{
					$cadena[$i]['departamento'] = $varI['departamento']; 
					$cadena[$i]['descripcion'] = $varI['descripcion'];
					if($varI['documento_id'])
					{
					$datos['documento'][$i] = $varI['documento_id']; 
					$datos['empresa'][] = $varI['empresa_id']; 
					}
					$i++;
				}
				$datos[$key]['departamento'] = $cadena; 
			}
			
			return $datos;
		}
		/**********************************************************************************
		* Funcion donde se seleccionan los tipos de documentos de la base de datos, 
		* su descripcion el documento asignado y el prefijo asociado
		*
		* @params	char $empresa Empresa relacionada a los documentos
		* @params char $tipodc	Tipo de documento que servira como filtro
		* @return array datos de los documentos
		***********************************************************************************/
		function ObtenerTiposDocumentos($empresa,$tipodc)
		{
			$doc = "";
			$datos = array();
			
			$sql .= "SELECT DC.documento_id, ";
			$sql .= "				DC.descripcion, ";
			$sql .= "				UC.empresa_id, ";
			$sql .= "				CU.centro_utilidad ";
			$sql .= "FROM 	empresas EM, ";
			$sql .= "				userpermisos_cuentas UC, ";
			$sql .= "				documentos DC, ";
			$sql .= "				departamentos DE, ";
			$sql .= "				centros_utilidad CU ";
			$sql .= "WHERE 	UC.usuario_id = ".UserGetUID()." ";
			$sql .= "AND		UC.empresa_id = '".$empresa."' ";
			$sql .= "AND		DC.empresa_id = UC.empresa_id ";
			$sql .= "AND		DC.documento_id = UC.documento_id ";
			$sql .= "AND		DC.empresa_id = EM.empresa_id ";
			$sql .= "AND		DE.empresa_id = EM.empresa_id ";
			$sql .= "AND		UC.departamento = DE.departamento ";
			$sql .= "AND		CU.empresa_id = EM.empresa_id ";
			$sql .= "AND		DC.sw_estado IN ('0','1') ";
			$sql .= "ORDER BY 2 ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
			$todos = "";
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				
				if($doc != $datos[$rst->fields[1]]['descripcion'] )
				{
					if($i > 0)
					{
						$cadena = trim($cadena);
						$cadena = str_replace(" ",",",$cadena);
						$datos[$doc]['documento_id'] = $cadena;
						$todos .= $cadena." ";
						$cadena = "";
					}
					$doc = $rst->fields[1];
				}
				$cadena .= "'".$rst->fields[0]."' ";					
				$rst->MoveNext();
				$i++;
		  }
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);
			$datos[$doc]['documento_id'] = $cadena;
			
			$todos .= $cadena;
			$todos = trim($todos);
			$todos = str_replace(" ",",",$todos);
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerDatosEmpresa($emp,$ctu,$dpt,$op)
		{
			if($op == 1)
			{
				$sql  = "SELECT	  ";
				$sql .= "				c.descripcion, ";
				$sql .= "				e.razon_social ";
				$sql .= "FROM 	empresas e, ";
				
				$sql .= "				centros_utilidad c ";
				$sql .= "WHERE  e.empresa_id = '".$emp."'  ";
				$sql .= "AND 		c.centro_utilidad = '".$ctu."' ";
				//$sql .= "AND		d.departamento = '".$dpt."' ";
				//$sql .= "AND		d.empresa_id = e.empresa_id ";
				$sql .= "AND		c.empresa_id = e.empresa_id ";
			}
			else
			{
				$sql .= "SELECT a.descripcion AS descripcion1,  ";
				$sql .= "				c.descripcion,  ";
				$sql .= "				b.razon_social ";
		    $sql .= "FROM		cajas a,  ";
				$sql .= "				empresas b, ";
				$sql .= "				centros_utilidad c ";
		    $sql .= "WHERE  c.empresa_id = '".$emp."' ";
				$sql .= "AND 		c.centro_utilidad = '".$ctu."' ";
		    $sql .= "AND 		a.caja_id='".$dpt."' ";
				$sql .= "AND 		a.empresa_id = b.empresa_id ";
				$sql .= "AND 		b.empresa_id = c.empresa_id ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
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
		*
		***********************************************************************************/
		function ObtenerCuentas($datos,$emp,$dptno,$offset,$cant)
		{
			$sql .= "SELECT	A.*,";
			$sql .= "				TO_CHAR(A.fecha_registro, 'DD/MM/YYYY') AS fecha1,  ";
			$sql .= "				TO_CHAR(A.fecha_registro, 'HH:MI AM') AS hora1,  ";
			$sql .= "				I.cama,  ";
			$sql .= "				I.pieza,  ";
			$sql .= "				C.tipo_id_paciente,  ";
			$sql .= "				C.paciente_id,  ";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre,   ";
			$sql .= "				(A.valor_total_paciente - (A.abono_efectivo + A.abono_cheque + A.abono_tarjetas + A.abono_chequespf + A.abono_letras)) AS saldo,  ";
			$sql .= "				CASE 	WHEN A.estado = '1' THEN 'A'  ";
			$sql .= "							WHEN A.estado = '2' THEN 'I' END AS estado  ";
			$sql .= "FROM 	cuentas A ";
			$sql .= "				LEFT JOIN ";
			$sql .= "				( SELECT	MH.cama,";
			$sql .= "									CA.pieza,";
			$sql .= "									MH.numerodecuenta ";
			$sql .= "					FROM		movimientos_habitacion MH,";
			$sql .= "									camas CA ";
			$sql .= "					WHERE		MH.fecha_egreso IS NULL ";
			$sql .= "					AND			MH.cama = CA.cama ";
			$sql .= "				) AS I ";
			$sql .= "				ON(I.numerodecuenta = A.numerodecuenta),";
			$sql .= "				ingresos B,";
			$sql .= "				pacientes C ";
			$sql .= "WHERE 	A.empresa_id = '".$emp."' ";
			$sql .= "AND 		A.estado IN('1','2') ";
			$sql .= "AND 		A.ingreso = B.ingreso ";
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
			$sql .= "SELECT	A.*,";
			$sql .= "				TO_CHAR(A.fecha_registro, 'DD/MM/YYYY') AS fecha1,  ";
			$sql .= "				TO_CHAR(A.fecha_registro, 'HH:MI AM') AS hora1,  ";
			$sql .= "				I.cama,  ";
			$sql .= "				I.pieza,  ";
			$sql .= "				C.tipo_id_paciente,  ";
			$sql .= "				C.paciente_id,  ";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre,   ";
			$sql .= "				(A.valor_total_paciente - (A.abono_efectivo + A.abono_cheque + A.abono_tarjetas + A.abono_chequespf + A.abono_letras)) AS saldo,  ";
			$sql .= "				CASE 	WHEN A.estado = '1' THEN 'A'  ";
			$sql .= "							WHEN A.estado = '2' THEN 'I' END AS estado  ";
			$from .= "FROM 	cuentas A ";
			$from .= "				LEFT JOIN ";
			$from .= "				( SELECT	MH.cama,";
			$from .= "									CA.pieza,";
			$from .= "									MH.numerodecuenta ";
			$from .= "					FROM		movimientos_habitacion MH,";
			$from .= "									camas CA ";
			$from .= "					WHERE		MH.fecha_egreso IS NULL ";
			$from .= "					AND			MH.cama = CA.cama ";
			$from .= "				) AS I ";
			$from .= "				ON(I.numerodecuenta = A.numerodecuenta),";
			$from .= "				ingresos B,";
			$from .= "				pacientes C ";
			$from .= "WHERE 	A.empresa_id = '".$emp."' ";
			$from .= "AND 		A.estado IN('1','2') ";
			$from .= "AND 		A.ingreso = B.ingreso ";
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
			$sql .= "			I.cama, ";
			$sql .= "			I.pieza, ";
			$sql .= "			(C.valor_total_paciente - (C.abono_efectivo + C.abono_cheque + C.abono_tarjetas + C.abono_chequespf + C.abono_letras)) as saldo, ";
			$sql .= "			C.nombre||' '||C.apellido AS nombre, ";
			$sql .= "			CASE 	WHEN C.estado = '1' THEN 'A' ";
			$sql .= "						WHEN C.estado = '2' THEN 'I' END AS estado ";
			$sql .= "FROM 	(	SELECT	P.primer_nombre||' '||P.segundo_nombre AS nombre,";
			$sql .= "									P.primer_apellido||' '||P.segundo_apellido AS apellido,";
			$sql .= "									P.paciente_id,";
			$sql .= "									P.tipo_id_paciente,";
			$sql .= "									TO_CHAR(C.fecha_registro, 'DD/MM/YYYY') AS fecha1,  ";
			$sql .= "									TO_CHAR(C.fecha_registro, 'HH:MI AM') AS hora1,  ";
			$sql .= "									C.* ";
			$sql .= "					FROM		pacientes P,";
			$sql .= "									ingresos I,";
			$sql .= "									cuentas C ";
			$sql .= "					WHERE 	I.tipo_id_paciente = P.tipo_id_paciente ";
			$sql .= "					AND 		I.paciente_id = P.paciente_id ";
			$sql .= "					AND 		C.estado IN('1','2') ";
			$sql .= "					AND 		C.ingreso = I.ingreso ";
			$sql .= "					AND 		C.empresa_id = '".$emp."' ";
			$sql .= "					AND 		P.tipo_id_paciente = '".$datos['TipoDocumento']."'  ";
			$sql .= "					AND 		P.paciente_id = '".$datos['Documento']."' ";
			if($dptno)
				$sql .= "					AND			I.departamento_actual IN (".$dptno.") ";
			$sql .= "				)AS C  ";
			$sql .= "				LEFT JOIN  ";
			$sql .= "				(	SELECT	MH.cama, ";
			$sql .= "									CA.pieza, ";
			$sql .= "									MH.numerodecuenta ";
			$sql .= "					FROM		movimientos_habitacion MH, ";
			$sql .= "									camas CA ";
			$sql .= "					WHERE		MH.fecha_egreso IS NULL ";
			$sql .= "					AND			MH.cama = CA.cama ";
			$sql .= "				) AS i ";
			$sql .= "				ON(I.numerodecuenta = C.numerodecuenta) ";
			$sql .= "ORDER BY C.numerodecuenta ";			

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$cont = 0;
			if(!$rst->EOF) $cont = $rst->RecordCount();
			
			$this->ProcesarSqlConteo($sql,$cont,$offset);

			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			//if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
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
			$nombres = strtoupper($datos['Nombres']);
			$apellidos = strtoupper($datos['Apellidos']);
			
			if ($nombres != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$nombres));//QUITA DOBLE ESPACIOS INTERNOS

				switch(count($a))
				{
					case 1:
						$filtroNombres .= " (primer_nombre  SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)' OR segundo_nombre SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)')";
					break;
					case 2:
						$filtroNombres  = " primer_nombre SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						next($a);
						$filtroNombres .= " AND ((primer_nombre SIMILAR TO '%[[:space:]]".current($a)."') OR (segundo_nombre ILIKE '".current($a)."'))";
					break;
					default:
						$filtroNombres = " primer_nombre SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						for($i=2;$i<count($a);$i++)
						{
							next($a);
							$filtroNombres .= " AND ((primer_nombre SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																	OR (segundo_nombre SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
						}
						next($a);
						$filtroNombres .= " AND ((primer_nombre SIMILAR TO '%[[:space:]]".current($a)."')  OR  (segundo_nombre SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
					break;
				}
			}

			if ($apellidos != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$apellidos));

				switch(count($a))
				{
					case 1:
							$filtroApellidos  = " primer_apellido ILIKE '".current($a)."'";
					break;

					case 2:
							$filtroApellidos  = " primer_apellido SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							next($a);
							$filtroApellidos .= " AND ((primer_apellido SIMILAR TO '%[[:space:]]".current($a)."') OR (segundo_apellido ILIKE '".current($a)."'))";
					break;

					default:
							$filtroApellidos  = " primer_apellido SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							for($i=2;$i<count($a);$i++)
							{
								next($a);
								$filtroApellidos .= " AND ((primer_apellido SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																						OR (segundo_apellido SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
							}
							next($a);
							$filtroApellidos .= " AND ((primer_apellido SIMILAR TO '%[[:space:]]".current($a)."')  OR  (segundo_apellido SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
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

			$sql .= "SELECT	C.*, ";
			$sql .= "			I.cama, ";
			$sql .= "			I.pieza, ";
			$sql .= "			(C.valor_total_paciente - (C.abono_efectivo + C.abono_cheque + C.abono_tarjetas + C.abono_chequespf + C.abono_letras)) as saldo, ";
			$sql .= "			C.nombre||' '||C.apellido AS nombre, ";
			$sql .= "			CASE 	WHEN C.estado = '1' THEN 'A' ";
			$sql .= "						WHEN C.estado = '2' THEN 'I' END AS estado ";
			$sql .= "FROM 	(	SELECT	P.primer_nombre||' '||P.segundo_nombre AS nombre,";
			$sql .= "									P.primer_apellido||' '||P.segundo_apellido AS apellido,";
			$sql .= "									P.paciente_id,";
			$sql .= "									P.tipo_id_paciente,";
			$sql .= "									TO_CHAR(C.fecha_registro, 'DD/MM/YYYY') AS fecha1,  ";
			$sql .= "									TO_CHAR(C.fecha_registro, 'HH:MI AM') AS hora1,  ";
			$sql .= "									C.* ";
			$sql .= "					FROM		pacientes P,";
			$sql .= "									ingresos I,";
			$sql .= "									cuentas C ";
			$sql .= "					WHERE 	I.tipo_id_paciente = P.tipo_id_paciente ";
			$sql .= "					AND 		I.paciente_id = P.paciente_id ";
			if($dptno)
				$sql .= "					AND			I.departamento_actual IN (".$dptno.") ";
			$sql .= "					AND 		C.estado IN('1','2') ";
			$sql .= "					AND 		C.ingreso = I.ingreso ";
			$sql .= "					AND 		C.empresa_id = '".$emp."' ";
			$sql .= "					AND 		$filtroPrincipalTipo2 ";
			$sql .= "				)AS C  ";
			$sql .= "				LEFT JOIN  ";
			$sql .= "				(	SELECT	MH.cama, ";
			$sql .= "									CA.pieza, ";
			$sql .= "									MH.numerodecuenta ";
			$sql .= "					FROM		movimientos_habitacion MH, ";
			$sql .= "									camas CA ";
			$sql .= "					WHERE		MH.fecha_egreso IS NULL ";
			$sql .= "					AND			MH.cama = CA.cama ";
			$sql .= "				) AS i ";
			$sql .= "				ON(I.numerodecuenta = C.numerodecuenta) ";
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
		

		
		function Totalfacturascredito($planes,$FechaI,$FechaF)
		{	
		       $sql="SELECT a.factura_fiscal, a.estado, c.fecha_ingreso, a.total_factura, b.numerodecuenta, b.ingreso, d.paciente_id, d.primer_apellido, d.segundo_apellido, d.primer_nombre, d.segundo_nombre, 
		       p.plan_descripcion, d.paciente_id, c.fecha_cierre, b.abono_efectivo, b.abono_cheque, b.abono_tarjetas, b.abono_chequespf, b.abono_letras, a.valor_cuota_paciente
		       
			FROM fac_facturas a,
			fac_facturas_cuentas f, 
			cuentas b, 
			ingresos c, 
			pacientes d, 
			planes p
			
			WHERE a.tipo_factura='1' 
			AND a.sw_clase_factura='1' 
			AND f.empresa_id=a.empresa_id
			AND f.prefijo=a.prefijo
			AND f.factura_fiscal=a.factura_fiscal
			AND b.numerodecuenta=f.numerodecuenta
			AND b.ingreso=c.ingreso 
			AND c.paciente_id=d.paciente_id
			AND c.tipo_id_paciente=d.tipo_id_paciente
			AND b.plan_id=p.plan_id
			AND ".$planes."=p.plan_id
			AND c.fecha_ingreso BETWEEN ('".$FechaI."') AND ('".$FechaF."')";
				 
		 
		 if(!$resultado = $this->ConexionBaseDatos($sql))
    		     return false;
		        $vector=array();
      			while(!$resultado->EOF)
      			{
        		$vector[]= $resultado->GetRowAssoc($ToUpper = false);
        		$resultado->MoveNext();
     		 	}
    		  $resultado->Close();
      		//return $sql;
     		 return $vector;
		}
		
	
		
			
	function DatosEncabezadoEmpresa()
  	{
      	list($dbconn) = GetDBconn();
      	$query = "select *
                from empresas as b
                where  b.empresa_id='".$_SESSION['FACTURACION']['EMPRESA']."'";
      	$resulta=$dbconn->Execute($query);
      	if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      	}
      	$var=$resulta->GetRowAssoc($ToUpper = false);
      	return $var;
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