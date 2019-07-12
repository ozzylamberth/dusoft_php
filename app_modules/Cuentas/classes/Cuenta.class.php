<?php
	/*********************************************************************************************
	* $Id: Cuenta.class.php,v 1.8 2011/07/13 13:30:27 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.8 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	class Cuenta
	{
		function Cuenta(){}
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
			$sql .= "				UC.documento_id, ";
			$sql .= "				UC.sw_unificar ";
			$sql .= "FROM		empresas EM,";
			$sql .= "				departamentos DE, ";
			$sql .= "				centros_utilidad CU, ";
			$sql .= "				userpermisos_cuentas UC ";
			$sql .= "WHERE	UC.usuario_id = ".$usuario." ";
			$sql .= "AND		UC.departamento = DE.departamento ";
			$sql .= "AND		DE.centro_utilidad = CU.centro_utilidad ";
			$sql .= "AND		CU.empresa_id = EM.empresa_id ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			$deptno = array();

			while (!$rst->EOF)
			{
				$deptno[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
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
			
			$sql .= "SELECT EM.razon_social,";
			$sql .= "				DC.descripcion||' - '||CU.descripcion as desc_utilidad, ";
			$sql .= "				DC.documento_id, ";
			$sql .= "				UC.empresa_id, ";
			$sql .= "				UC.sw_unificar, ";
			$sql .= "				CU.centro_utilidad, ";
			$sql .= "				PF.prefijo_fac_credito, ";
			$sql .= "				PF.prefijo_fac_contado, ";
			$sql .= "				PF.documento_recibo_caja, ";
			$sql .= "				PF.punto_facturacion_id ";
			$sql .= "FROM 	empresas EM, ";
			$sql .= "				userpermisos_cuentas UC LEFT JOIN ";
			$sql .= "				puntos_facturacion PF ";
			$sql .= "				ON (PF.punto_facturacion_id = UC.punto_facturacion_id), ";
			$sql .= "				documentos DC, ";
			$sql .= "				departamentos DE, ";
			$sql .= "				centros_utilidad CU ";
			$sql .= "WHERE 	UC.usuario_id = ".UserGetUID()." ";
			//$sql .= "AND		UC.empresa_id = '".$empresa."' ";
			$sql .= "AND		DC.empresa_id = UC.empresa_id ";
			$sql .= "AND		DC.documento_id = UC.documento_id ";
			$sql .= "AND		DC.empresa_id = EM.empresa_id ";
			$sql .= "AND		DE.empresa_id = EM.empresa_id ";
			$sql .= "AND		DE.empresa_id = CU.empresa_id ";
			$sql .= "AND		UC.departamento = DE.departamento ";
			$sql .= "AND		DE.centro_utilidad = CU.centro_utilidad ";
			$sql .= "AND		DC.sw_estado IN ('0','1') ";
			$sql .= "ORDER BY 2 ";
					
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
			$j=0;
			$doc = $emp = "";
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				
				if($doc != $rst->fields[1] && $emp != $rst->fields[0])
				{
					if($i > 0)
					{
						$cadena = trim($cadena);
						$cadena = str_replace(" ","','",$cadena);
						$datos[$emp][$doc]['documento_id'] = $cadena;
						$cadena = "";
					}
					$doc = $rst->fields[1];
					$emp = $rst->fields[0];
				}
				$cadena .= $rst->fields[2]." ";					
				$rst->MoveNext();
				$i++;
		  }
			$cadena = trim($cadena);
			$cadena = str_replace(" ","','",$cadena);
			$datos[$emp][$doc]['documento_id'] = $cadena;
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
    
    /* Funcion que cuenta todas las órdenes de servicio sin autorizar del paciente.
    *  integer $ingreso Ingreso del paciente al que se le buscarán las órdenes no autorizadas. 
    *  return int Corresponde al número de solicitudes de órdenes de servicio sin autorizar.
    */
    function ContarSolicitudesNoAutorizadasPaciente($ingreso)
    {
			$sql  = " SELECT   count(hcos.hc_os_solicitud_id) 
                   FROM      hc_os_solicitudes as hcos,
                                 os_tipos_solicitudes as osts, 
                                 ingresos as i,
                                 hc_evoluciones as hce,
                                 cups as c 
                   WHERE    i.ingreso = ".$ingreso." AND
                                 i.ingreso = hce.ingreso AND                               
                                 hce.evolucion_id = hcos.evolucion_id AND
                                 hcos.cargo = c.cargo AND
                                 hcos.sw_estado in ('1','3') AND
                                 osts.os_tipo_solicitud_id = hcos.os_tipo_solicitud_id ; ";
              
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['count'];    
    } 
    
    /* Funcion que busca todas las órdenes solicitadas al paciente, las cuales no han sido autorizadas para su respectiva realización.
    *  integer $ingreso Ingreso del paciente al que se le buscarán las órdenes no autorizadas. 
    *  return array con los datos de las órdenes no autorizadas.
    */
    function BuscarSolicitudesNoAutorizadasPaciente($ingreso)
    {
			$sql  = " SELECT   distinct osts.descripcion as descripcion_tipo_solicitud, 
                                 hcos.fecha_solicitud,
                                 hcos.hc_os_solicitud_id,
                                 hcos.cargo,
                                 hcos.cantidad,
                                 c.descripcion as descripcion_cargo_cups
                   FROM      hc_os_solicitudes as hcos,
                                 os_tipos_solicitudes as osts, 
                                 ingresos as i,
                                 hc_evoluciones as hce,
                                 cups as c 
                   WHERE    i.ingreso = ".$ingreso." AND
                                 i.ingreso = hce.ingreso AND                               
                                 hce.evolucion_id = hcos.evolucion_id AND
                                 hcos.cargo = c.cargo AND
                                 hcos.sw_estado in ('1','3') AND
                                 osts.os_tipo_solicitud_id = hcos.os_tipo_solicitud_id 
                   ORDER BY descripcion_tipo_solicitud ASC, hcos.fecha_solicitud ASC ; ";
            
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

    /* Funcion que cuenta todas las órdenes de servicio sin resultado del paciente.
    *  integer $ingreso Ingreso del paciente al que se le buscarán las órdenes autorizadas sin resultados. 
    *  return int Corresponde al número de solicitudes de órdenes de servicio sin autorizar.
    */
    function  ContarOrdenesSinResultadoPaciente($ingreso)
    {
			$sql  = " SELECT   count(osm.numero_orden_id)
                   FROM      hc_os_solicitudes as hcos,
                                 os_tipos_solicitudes as osts,
                                 ingresos as i,
                                 hc_evoluciones as hce,
                                 os_maestro as osm,
                                 cups as c 
                   WHERE    i.ingreso = ".$ingreso." AND
                                 i.ingreso = hce.ingreso AND                               
                                 hce.evolucion_id = hcos.evolucion_id AND
                                 osm.sw_estado in ('1', '2', '3', '5', '7') AND
                                 hcos.hc_os_solicitud_id = osm.hc_os_solicitud_id AND
                                 osm.cargo_cups = c.cargo AND
                                 osts.os_tipo_solicitud_id = hcos.os_tipo_solicitud_id ; ";
            
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['count'];    
    }  

    /* Funcion que busca todas las órdenes autorizadas del paciente pero que aún no tienen resultados.
    *  integer $ingreso Ingreso del paciente al que se le buscarán las órdenes autorizadas sin resultados. 
    *  return array con los datos de las órdenes sin resultado.
    */
    function BuscarOrdenesSinResultadoPaciente($ingreso)
    {
			$sql  = " SELECT   distinct osts.descripcion as descripcion_tipo_solicitud, 
                                 osm.numero_orden_id,
                                 osm.fecha_activacion,
                                 hcos.hc_os_solicitud_id,
                                 hcos.cargo,
                                 hcos.cantidad,
                                 c.descripcion as descripcion_cargo_cups
                   FROM      hc_os_solicitudes as hcos,
                                 os_tipos_solicitudes as osts,
                                 ingresos as i,
                                 hc_evoluciones as hce,
                                 os_maestro as osm,
                                 cups as c 
                   WHERE    i.ingreso = ".$ingreso." AND
                                 i.ingreso = hce.ingreso AND                               
                                 hce.evolucion_id = hcos.evolucion_id AND
                                 osm.sw_estado in ('1', '2', '3', '5', '7') AND
                                 hcos.hc_os_solicitud_id = osm.hc_os_solicitud_id AND
                                 osm.cargo_cups = c.cargo AND
                                 osts.os_tipo_solicitud_id = hcos.os_tipo_solicitud_id 
                   ORDER BY descripcion_tipo_solicitud ASC, osm.fecha_activacion ASC ; ";
              
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
    
    function BuscarMotivosNoEjecutar()
    {
			$sql  = " SELECT    *
                   FROM       motivos_cambios_estado_solicitudes_ordenes_no_ejecutadas ; ";

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
		function ObtenerCuentas($datos,$emp,$dptno,$offset,$cant,$centro_utilidad=null)
		{
			if(empty($emp))
			{
				$emp = SessionGetVar("DatosEmpresaId");
			}
			
			if(empty($centro_utilidad))
			{
				$centro_utilidad = SessionGetVar("DatosCentroUtilidadId");
			}
			
			$sql .= "SELECT	A.numerodecuenta,";
			$sql .= "				A.ingreso,";
			$sql .= "				A.total_cuenta,";
			$sql .= "				A.valor_nocubierto,";
			$sql .= "				D.plan_descripcion,";
			$sql .= "				TO_CHAR(A.fecha_registro, 'DD/MM/YYYY') AS fecha,  ";
			$sql .= "				C.tipo_id_paciente,  ";
			$sql .= "				C.paciente_id,  ";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre,   ";
			$sql .= "				(A.valor_total_paciente - (A.abono_efectivo + A.abono_cheque + A.abono_tarjetas + A.abono_chequespf + A.abono_letras)) AS saldo,  ";
			$sql .= "				A.estado, ";
			$sql .= "				CASE 	WHEN A.estado = '1' THEN 'CUENTA ACTIVA' ";
			$sql .= "							WHEN A.estado = '2' THEN 'CUENTA INACTIVA' ";
			$sql .= "							WHEN A.estado = '3' THEN 'CUENTA CUADRADA' END AS desc_estado  ";
			$sql .= "FROM 	cuentas A, ";
			$sql .= "				ingresos B,";
			$sql .= "				pacientes C, ";
			$sql .= "				planes D ";
			$sql .= "WHERE	A.estado IN('1','2') ";
			$sql .= "AND 		A.ingreso = B.ingreso ";
			$sql .= "AND 		A.plan_id = D.plan_id ";
			$sql .= "AND 		B.tipo_id_paciente = C.tipo_id_paciente  ";
			$sql .= "AND 		B.paciente_id = C.paciente_id ";

			if($emp)
				$sql .= "AND	 	A.empresa_id = '".$emp."' ";
      if($centro_utilidad)
				$sql .= "AND	 	A.centro_utilidad = '".$centro_utilidad."' ";  
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
		function ObtenerCuentasXIngreso($datos,$emp,$dptno,$offset,$centro_utilidad)
		{
			if(empty($emp))
			{
				$emp = SessionGetVar("DatosEmpresaId");
			}
			
			if(empty($centro_utilidad))
			{
				$centro_utilidad = SessionGetVar("DatosCentroUtilidadId");
			}
			
			
			$sql .= "SELECT	A.numerodecuenta,";
			$sql .= "				A.ingreso,";
			$sql .= "				A.total_cuenta,";
			$sql .= "				A.valor_nocubierto,";
			$sql .= "				D.plan_descripcion,";
			$sql .= "				TO_CHAR(A.fecha_registro, 'DD/MM/YYYY') AS fecha,  ";
			$sql .= "				C.tipo_id_paciente,  ";
			$sql .= "				C.paciente_id,  ";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre,   ";
			$sql .= "				(A.valor_total_paciente - (A.abono_efectivo + A.abono_cheque + A.abono_tarjetas + A.abono_chequespf + A.abono_letras)) AS saldo,  ";
			$sql .= "				A.estado, ";
			$sql .= "				CASE 	WHEN A.estado = '1' THEN 'CUENTA ACTIVA' ";
			$sql .= "							WHEN A.estado = '2' THEN 'CUENTA INACTIVA' ";
			$sql .= "							WHEN A.estado = '3' THEN 'CUENTA CUADRADA' END AS desc_estado  ";
			$from .= "FROM 		cuentas A, ";
			$from .= "				ingresos B,";
			$from .= "				pacientes C, ";
			$from .= "				planes D ";
			$from .= "WHERE		A.estado IN('1','2') ";
			$from .= "AND 		A.ingreso = B.ingreso ";
			$from .= "AND 		B.tipo_id_paciente = C.tipo_id_paciente  ";
			$from .= "AND 		B.paciente_id = C.paciente_id ";
			$from .= "AND 		A.plan_id = D.plan_id ";
			if($dptno)
				$from .= "AND			B.departamento_actual IN (".$dptno.") ";

			if($emp)
				$from .= "AND		 	A.empresa_id = '".$emp."' ";
      
      if($centro_utilidad)
				$from .= "AND		 	A.centro_utilidad = '".$centro_utilidad."' ";

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
		function ObtenerCuentasXIdPaciente($datos,$emp,$dptno,$offset,$centro_utilidad)
		{
			if(empty($emp))
			{
				$emp = SessionGetVar("DatosEmpresaId");
			}
			
			if(empty($centro_utilidad))
			{
				$centro_utilidad = SessionGetVar("DatosCentroUtilidadId");
			}
			
			$sql .= "SELECT	C.numerodecuenta,";
			$sql .= "				C.ingreso,";
			$sql .= "				C.total_cuenta,";
			$sql .= "				C.valor_nocubierto,";
			$sql .= "				C.plan_descripcion,";
			$sql .= "				C.fecha,";
			$sql .= "				C.paciente_id,";
			$sql .= "				C.tipo_id_paciente,";
			$sql .= "				(C.valor_total_paciente - (C.abono_efectivo + C.abono_cheque + C.abono_tarjetas + C.abono_chequespf + C.abono_letras)) as saldo, ";
			$sql .= "				C.nombre||' '||C.apellido AS nombre, ";
			$sql .= "				C.estado, ";
			$sql .= "				CASE 	WHEN C.estado = '1' THEN 'CUENTA ACTIVA' ";
			$sql .= "							WHEN C.estado = '2' THEN 'CUENTA INACTIVA' ";
			$sql .= "							WHEN C.estado = '3' THEN 'CUENTA CUADRADA' END AS desc_estado  ";
			$sql .= "FROM 	(	SELECT	P.primer_nombre||' '||P.segundo_nombre AS nombre,";
			$sql .= "									P.primer_apellido||' '||P.segundo_apellido AS apellido,";
			$sql .= "									P.paciente_id,";
			$sql .= "									P.tipo_id_paciente,";
			$sql .= "									D.plan_descripcion,";
			$sql .= "									TO_CHAR(C.fecha_registro, 'DD/MM/YYYY') AS fecha,  ";
			$sql .= "									C.* ";
			$sql .= "					FROM		pacientes P,";
			$sql .= "									ingresos I,";
			$sql .= "									cuentas C, ";
			$sql .= "									planes D ";
			$sql .= "					WHERE 	I.tipo_id_paciente = P.tipo_id_paciente ";
			$sql .= "					AND 		I.paciente_id = P.paciente_id ";
			$sql .= "					AND 		C.plan_id = D.plan_id ";
			$sql .= "					AND 		C.estado IN('1','2') ";
			$sql .= "					AND 		C.ingreso = I.ingreso ";
			$sql .= "					AND 		P.tipo_id_paciente = '".$datos['TipoDocumento']."'  ";
			$sql .= "					AND 		P.paciente_id = '".$datos['Documento']."' ";

			if($emp)
				$sql .= "					AND 		C.empresa_id = '".$emp."' ";
      
      if($centro_utilidad)
				$sql .= "					AND 		C.centro_utilidad = '".$centro_utilidad."' ";
      
			if($dptno)
				$sql .= "					AND			I.departamento_actual IN (".$dptno.") ";
			$sql .= "				)AS C  ";
			$sql .= "ORDER BY C.numerodecuenta ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$cont = 0;
			if(!$rst->EOF) $cont = $rst->RecordCount();

			if($cont > 0)
			{
				$this->ProcesarSqlConteo($sql2,$cont,$offset);

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
		*
		*********************************************************************************/
		function ObtenerCuentasXNombrePaciente($datos,$emp,$dptno,$offset,$centro_utilidad)
		{
			$filtro = $this->FiltrarNombres($datos['Nombres'],$datos['Apellidos'],$alias);

			if(empty($emp))
			{
				$emp = SessionGetVar("DatosEmpresaId");
			}
			
			if(empty($centro_utilidad))
			{
				$centro_utilidad = SessionGetVar("DatosCentroUtilidadId");
			}
			
			$sql .= "SELECT	C.*, ";
			$sql .= "				C.nombre||' '||C.apellido AS nombre, ";
			$sql .= "				CASE 	WHEN C.estado = '1' THEN 'CUENTA ACTIVA' ";
			$sql .= "							WHEN C.estado = '2' THEN 'CUENTA INACTIVA' ";
			$sql .= "							WHEN C.estado = '3' THEN 'CUENTA CUADRADA' END AS desc_estado  ";
			$sql .= "FROM 	(	SELECT	P.primer_nombre||' '||P.segundo_nombre AS nombre,";
			$sql .= "									P.primer_apellido||' '||P.segundo_apellido AS apellido,";
			$sql .= "									P.paciente_id,";
			$sql .= "									P.tipo_id_paciente,";
			$sql .= "									TO_CHAR(C.fecha_registro, 'DD/MM/YYYY') AS fecha,  ";
			$sql .= "									C.numerodecuenta ,";
			$sql .= "									C.ingreso ,";
			$sql .= "									C.total_cuenta,";
			$sql .= "									C.valor_nocubierto,";
			$sql .= "									D.plan_descripcion,";
			$sql .= "									C.estado, ";
			$sql .= "									(C.valor_total_paciente - (C.abono_efectivo + C.abono_cheque + C.abono_tarjetas + C.abono_chequespf + C.abono_letras)) as saldo ";

			$sql .= "					FROM		pacientes P,";
			$sql .= "									ingresos I,";
			$sql .= "									cuentas C, ";
			$sql .= "									planes D ";
			$sql .= "					WHERE 	I.tipo_id_paciente = P.tipo_id_paciente ";
			$sql .= "					AND 		C.plan_id = D.plan_id ";
			$sql .= "					AND 		I.paciente_id = P.paciente_id ";
			$sql .= "					AND 		C.estado IN('1','2') ";
			$sql .= "					AND 		C.ingreso = I.ingreso ";
			$sql .= "					AND 		".$filtro." ";

			if($emp)
				$sql .= "					AND 		C.empresa_id = '".$emp."' ";
      if($centro_utilidad)
				$sql .= "					AND 		C.centro_utilidad = '".$centro_utilidad."' ";
			if($dptno)
				$sql .= "					AND			I.departamento_actual IN (".$dptno.") ";

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
    
    function RegistrarSolicitudesOrdenesANoEjecutar($datos)
    {
      $cantidadNoEjecutada=0;
      
      $sql="SELECT nextval('solicitudes_ordenes_no_ejecut_os_solicitudes_ordenes_no_eje_seq')";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $valorSiguiente =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      } 
      $rst->Close();
          
      $valorSiguiente=$valorSiguiente['nextval'];        
            
      $sql="    INSERT  INTO      solicitudes_ordenes_no_ejecutadas
                                              (
                                                  os_solicitudes_ordenes_no_ejecutadas_id,
                                                  usuario_id,
                                                  observacion
                                              )  
                                 VALUES  (
                                                  ".$valorSiguiente.",
                                                  '".UserGetUID()."',
                                                  '".$datos['observacion']."'
                                              ); ";
                                              
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $NoOrdenesSinAutorizar = $datos['cantidadOrdenesSinAutorizar'];
      
      if($NoOrdenesSinAutorizar>0)
      {
        $sql="";
        for($i=0; $i<$NoOrdenesSinAutorizar; $i++)
        {
          if(!empty($datos['ordenSinAutorizar_'.$i]))
          {
            $sql .= "    INSERT  INTO      hc_os_solicitudes_no_autorizadas_no_ejecutadas
                                                    (
                                                        hc_os_solicitudes_no_autorizadas_no_ejecutadas_id,
                                                        os_solicitudes_ordenes_no_ejecutadas_id,
                                                        hc_os_solicitud_id
                                                    )  
                                       VALUES  (
                                                        default,
                                                        ".$valorSiguiente.",
                                                        ".$datos['ordenSinAutorizar_'.$i]."
                                                    ); ";
                            
             $sql .= "    UPDATE      hc_os_solicitudes   SET   sw_estado = '4' WHERE   hc_os_solicitud_id=".$datos['ordenSinAutorizar_'.$i]." ; ";
             $cantidadNoEjecutada++;
          }    
        }    
        if(!empty($sql))
          if(!$rst = $this->ConexionBaseDatos($sql))
            return false;
      }
      $NoOrdenesSinResultado = $datos['cantidadOrdenesSinResultados'];
      
      if($NoOrdenesSinResultado>0)
      {
        $sql="";
        for($i=0; $i<$NoOrdenesSinResultado; $i++)
        {
          if(!empty($datos['ordenSinResultado_'.$i]))
          {
            $sql .="    INSERT  INTO      os_solicitudes_sin_resultado_no_ejecutadas
                                                    (
                                                        os_solicitudes_sin_resultado_no_ejecutadas_id,
                                                        os_solicitudes_ordenes_no_ejecutadas_id,
                                                        numero_orden_id
                                                    )  
                                       VALUES  (
                                                        default,
                                                        ".$valorSiguiente.",
                                                        ".$datos['ordenSinResultado_'.$i]."
                                                    );   ";
                                                    
            $sql .= "    UPDATE os_maestro   SET   sw_estado = 'n' WHERE   numero_orden_id = ".$datos['ordenSinResultado_'.$i]." ; ";                                                  
            
            $cantidadNoEjecutada++;
          }    
        }  
        if(!empty($sql))          
          if(!$rst = $this->ConexionBaseDatos($sql))
            return false;
     }
    
      $totalSolicitudesOrdenes = $NoOrdenesSinAutorizar + $NoOrdenesSinResultado; 
      
      if($cantidadNoEjecutada!=$totalSolicitudesOrdenes)
      {
        return 1; 
      }
      else
      {
        return 2;
      }      
			return true;    
    }
    
		/**********************************************************************************
		* Funcion donde se obtiene la informacion de una cuenta especifica
		*
		* @params int $cuenta Numero de cuenta que se buscara
		* @params String $emp Indice de la empresa a la cual pertenece la factura
		* @params String $dptno Identificador del departamento al cual pertenece la empresa,
		*					es opcional
		* @returns array Arreglo de datos asociativo de los datos de la cuenta
		***********************************************************************************/
		function ObtenerInformacionCuenta($cuenta,$emp,$dptno)
		{
			$sql .= "SELECT	A.numerodecuenta,";
			$sql .= "				A.ingreso,";
			$sql .= "				A.total_cuenta,";
			$sql .= "				A.valor_cubierto,";
			$sql .= "				A.valor_nocubierto,";
			$sql .= "				A.valor_total_paciente,";
      $sql .= "				(A.abono_efectivo + A.abono_cheque + A.abono_tarjetas + A.abono_chequespf + A.abono_letras) as valor_total_pagado_paciente, ";
			$sql .= " 			A.valor_cuota_moderadora,";
			$sql .= " 			A.valor_cuota_paciente,";
			$sql .= " 			A.valor_total_empresa,";
			$sql .= " 			A.abono_cheque,";
			$sql .= " 			A.abono_tarjetas, ";
			$sql .= " 			A.abono_chequespf,";
			$sql .= " 			A.abono_letras,  ";
			$sql .= "				A.estado, ";
      $sql .= "       A.rango, ";
			$sql .= "				(A.valor_total_paciente - (A.abono_efectivo + A.abono_cheque + A.abono_tarjetas + A.abono_chequespf + A.abono_letras)) AS saldo,  ";
			$sql .= "				TO_CHAR(A.fecha_registro, 'DD/MM/YYYY') AS fecha,  ";
			$sql .= "				EXTRACT(hour from A.fecha_registro)||':'||EXTRACT(min from A.fecha_registro) AS hora, ";
			$sql .= "				B.sw_ambulatorio,";
			$sql .= "				D.plan_descripcion,";
			$sql .= "				D.sw_tipo_plan,";
			$sql .= "				D.sw_facturacion_agrupada,";
			$sql .= "				D.plan_id,";
			$sql .= "				C.tipo_id_paciente,  ";
			$sql .= "				C.paciente_id,  ";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre, ";
			$sql .= "				CM.modifica_cuota, ";
			$sql .= "				CP.modifica_copago, ";
			$sql .= "				TA.tipo_afiliado_nombre, ";
			$sql .= "				T.tipo_id_tercero, ";
			$sql .= "				T.tercero_id, ";
			$sql .= "				T.nombre_tercero ";
			$sql .= "FROM 	cuentas A LEFT JOIN ";
			$sql .= "				(	SELECT 	numerodecuenta,";
			$sql .= "									COUNT(*) AS modifica_cuota ";
			$sql .= "					FROM		cuentas_modificacion_cuota_moderadora ";
			$sql .= "					WHERE 	numerodecuenta = ".$cuenta." ";
			$sql .= "					GROUP BY numerodecuenta  ";
			$sql .= "				) AS CM ";
			$sql .= "				ON (A.numerodecuenta = CM.numerodecuenta) ";
			$sql .= "				LEFT JOIN ";
			$sql .= "				(	SELECT 	numerodecuenta,";
			$sql .= "									COUNT(*) AS modifica_copago ";
			$sql .= "					FROM		cuentas_modificacion_copago ";
			$sql .= "					WHERE 	numerodecuenta = ".$cuenta." ";
			$sql .= "					GROUP BY numerodecuenta  ";
			$sql .= "				) AS CP ";
			$sql .= "				ON (A.numerodecuenta = CP.numerodecuenta),";
			$sql .= "				ingresos B,";
			$sql .= "				pacientes C, ";
			$sql .= "				planes D, ";
			$sql .= "				tipos_afiliado TA, ";
			$sql .= "				terceros T ";
			$sql .= "WHERE	A.ingreso = B.ingreso ";
			$sql .= "AND 		B.tipo_id_paciente = C.tipo_id_paciente  ";
			$sql .= "AND 		B.paciente_id = C.paciente_id ";
			$sql .= "AND 		A.plan_id = D.plan_id ";
			$sql .= "AND 		A.tipo_afiliado_id = TA.tipo_afiliado_id ";
			$sql .= "AND 		D.tipo_tercero_id = T.tipo_id_tercero ";
			$sql .= "AND 		D.tercero_id = T.tercero_id ";
			$sql .= "AND 		A.numerodecuenta = ".$cuenta." ";
			
			if($dptno)
				$sql .= "AND			B.departamento_actual IN (".$dptno.") ";

			if($emp)
				$sql .= "AND		 	A.empresa_id = '".$emp."' ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
		}
		/**********************************************************************************
		* Funcion donde se obtienen los motivos por los cuales se hace el cambio del copago
		*
		* returns array Arreglo asociativo de datos de los motivos
		***********************************************************************************/
		function ObtenerMotivosCambioCopago()
		{
			$sql  = "SELECT	motivo_cambio_copago_id AS motivo_id,";
			$sql .= "				descripcion ";
			$sql .= "FROM		motivos_cambio_copago ";
			$sql .= "ORDER BY descripcion ";

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
		* Funcion donde se obtienen los motivos por los cuales se hace el cambio dela cuota
		* moderadora
		*
		* returns array Arreglo asociativo de datos de los motivos
		***********************************************************************************/
		function ObtenerMotivosCambioCuota()
		{
			$sql  = "SELECT motivo_cambio_cuota_moderadora_id AS motivo_id,";
			$sql .= "				descripcion ";
			$sql .= "FROM		motivos_cambio_cuota_moderadora ";
			$sql .= "ORDER BY descripcion ";
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
		
		/*
		**
		*/
		function ObtenerDatosCuenta($Cuenta)
		{
			$sql  = "SELECT empresa_id, centro_utilidad ";
			$sql .= "FROM cuentas ";
			$sql .= "WHERE numerodecuenta = $Cuenta ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			if(!$rst->EOF)
			{
				$retorno = $rst->GetRowAssoc($ToUpper = false);
			}
			$rst->Close();
			return $retorno;
		}
		
		/**********************************************************************************
		* Funcion que permite la creacion de la cadena que hace el filtrado de nombres en
		* el sql
		*
		* @params String	$nombres Cadena donde esta el nombre(s) a buscar
		* @params String	$apellidos Cadena donde esta el apellido(s) buscar
		* @params String 	$alias Idenficador del alias de la tabla, silo tiene, es opcional
		*
		* @returns String Cadena con la parte sql para hacer el filtrado del nombre
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