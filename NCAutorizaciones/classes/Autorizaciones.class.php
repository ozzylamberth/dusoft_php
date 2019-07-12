<?php
	/**************************************************************************************
	* $Id: Autorizaciones.class.php,v 1.2 2009/11/04 19:08:36 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 	
	* @author Hugo Freddy Manrique Arango
	***************************************************************************************/
	class Autorizaciones
	{
		var $conteo = 0;
		var $pagina = 1;
		var $offset = 0;
		var $limit = 10;
		
		function Autorizaciones(){}
		/**********************************************************************************
		* Funcion donde se obtienen los datos de las autorizaciones realizadas sobre las 
		* ordenes de servicio realizads sobre el ingreso
		*
		* $returns $datos array Datos de las ordenes de servicio 
		***********************************************************************************/
    function ObtenerAutizacionesOS($ingreso,$offset,$idp,$tid)
		{
			$sql .= "SELECT	AU.autorizacion, ";
			$sql .= "				SU.nombre AS funcionario_registra, ";
			$sql .= "				OS.sw_estado, ";
			$sql .= "				OS.orden_servicio_id, ";
			$sql .= "				DE.descripcion AS deptno_descripcion, ";
			$sql .= "				AU.observaciones, ";
			$sql .= "				TO_CHAR(AU.fecha_registro,'DD/MM/YYYY HH:MI AM') AS fecha_autorizacion ";
			$where .= "FROM		os_ordenes_servicios OS, ";
			$where .= "				autorizaciones AU, ";
			$where .= "				system_usuarios SU, ";
			$where .= "				departamentos DE ";
			$where .= "WHERE	OS.autorizacion_int = AU.autorizacion  ";
			$where .= "AND		AU.usuario_id = SU.usuario_id ";
			$where .= "AND		OS.ingreso = ".$ingreso." ";
			$where .= "AND		OS.autorizacion_int != 1 ";
			$where .= "AND		OS.departamento IS NOT NULL ";
			$where .= "AND		OS.departamento = DE.departamento ";
			$where .= "AND		OS.tipo_id_paciente = '".$tid."' ";
			$where .= "AND		OS.paciente_id = '".$idp."' ";
			
			$this->requestoff = $offset;
			
			if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $where",25))
				return false;
			
			$sql .= $where;
			$sql .= "ORDER BY AU.autorizacion ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
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
		* Funcion donde se obtienen los tipos de autorizacion del sistema
		*
		* @returns $datos array Arreglo de datos con los tipo de autorizacion  
		***********************************************************************************/
		function TiposAutorizacion()
    {
      $sql  = "SELECT tipo_autorizacion,";
			$sql .= "				descripcion, ";
			$sql .= "				sw_tipo_autorizador_interno, ";
			$sql .= "				sw_tipo_autorizador_externo ";
			$sql .= "FROM		autorizaciones_tipos ";
			$sql .= "WHERE	sw_tipo_autorizador_interno != '0' ";
			$sql .= "OR			sw_tipo_autorizador_externo != '0' ";
			$sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[2]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

      return $datos;
    }		
    /**********************************************************************************
		* Funcion donde se obtienen los tipos de autorizacion del sistema
		*
		* @returns $datos array Arreglo de datos con los tipo de autorizacion  
		***********************************************************************************/
		function ObtenerSolicitudesIngreso($ingreso,$paciente_id,$tipo_id_paciente)
    {
      $sql  = "SELECT numero_solicitud ";
			$sql .= "FROM		solicitud_autorizacion_serv ";
			$sql .= "WHERE	paciente_id = '".$paciente_id."' ";
			$sql .= "AND  	tipo_id_paciente = '".$tipo_id_paciente."' ";
			$sql .= "AND    autorizacion IS NULL ";
      if($ingreso)
      {
        $sql .= "UNION DISTINCT ";
        $sql .= "SELECT numero_solicitud ";
        $sql .= "FROM		solicitud_autorizacion_serv ";
        $sql .= "WHERE	ingreso = ".$ingreso." ";
        $sql .= "AND    autorizacion IS NULL ";
			}
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
		* Funcion donde se obtienen los auditores internos que pueden autorizar, para un 
		* plan determinado
		*
		* @params $plan int Numero del plan 
		* @returns $datos array Arreglo con los auditores internos del plan 
		***********************************************************************************/
    function ObtenerUsuariosAutorizacion($plan,$uid)
    {
			$sql  = "SELECT	SU.nombre,";
			$sql .= "				SU.usuario_id ";
			$sql .= "FROM		planes_auditores_int PI, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	PI.plan_id = ".$plan." ";
			$sql .= "AND		SU.usuario_id = PI.usuario_id ";
			if($uid) $sql .= "AND		SU.usuario_id = ".$uid." ";
			
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
    * Metodo para obtener los datos de un paciente ingresado
    *
    * @param string $ingreso
    * @return array
    ***********************************************************************************/
    function GetDatosPaciente($ingreso)
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
			$sql .= "				A.estado "; 
			$sql .= "FROM		ingresos A  LEFT JOIN ";
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
			$sql .= "				 	AND			CU.ingreso = ".$ingreso." ";	
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
    * Metodo para obtener los datos de un paciente
    *
    * @param string $tid
    * @param string $idp
    * @return array
    ***********************************************************************************/
    function OtenerDatosPacienteXId($tid,$idp)
    {
			$sql  = "SELECT	C.primer_apellido||' '||C.segundo_apellido AS apellido, "; 
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre AS nombre, "; 
			$sql .= "				C.residencia_direccion,"; 
			$sql .= "				C.residencia_telefono, "; 
			$sql .= "				C.tipo_id_paciente, ";
			$sql .= "				C.paciente_id, ";
			$sql .= "				TO_CHAR(C.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento "; 
			$sql .= "FROM		pacientes C ";
			$sql .= "WHERE	C.tipo_id_paciente  = '".$tid."' ";
			$sql .= "AND		C.paciente_id = '".$idp."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$paciente = array();
			while (!$rst->EOF)
			{
				$paciente = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $paciente;
    }
		/************************************************************************************ 
		*
		*************************************************************************************/
		function ObtenerDatosPlan($plan)
		{
			$sql .= "SELECT	PL.plan_id, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				TE.tercero_id, "; 
			$sql .= "				TE.tipo_id_tercero,"; 
			$sql .= "				TE.nombre_tercero  "; 
			$sql .= "FROM		planes PL, ";
			$sql .= "				terceros TE ";
			$sql .= "WHERE	PL.plan_id = ".$plan." ";	
			$sql .= "AND		TE.tercero_id = PL.tercero_id ";
			$sql .= "AND		TE.tipo_id_tercero = PL.tipo_tercero_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$planes = array();
			while (!$rst->EOF)
			{
				$planes = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $planes;
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function ObtenerTiposAfiliados($plan,$tipo_id = null)
		{
			$sql  = "SELECT DISTINCT TA.tipo_afiliado_nombre,";
			$sql .= "				TA.tipo_afiliado_id ";
			$sql .= "FROM		tipos_afiliado TA,";
			$sql .= "				planes_rangos PR ";
			$sql .= "WHERE 	PR.plan_id = ".$plan." ";
			$sql .= "AND		PR.tipo_afiliado_id = TA.tipo_afiliado_id ";
			if($tipo_id)
				$sql .= "AND		TA.tipo_afiliado_id = '".$tipo_id."' ";
			
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
		/************************************************************************************ 
		*
		*************************************************************************************/
		function ObtenerRangosNiveles($plan)
		{
			$sql  = "SELECT DISTINCT rango ";
			$sql .= "FROM		planes_rangos ";
			$sql .= "WHERE 	plan_id = ".$plan." ";
			
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
		function ObtenerProtocolos($plan)
		{
			$sql  = "SELECT protocolos ";
			$sql .= "FROM		planes ";
			$sql .= "WHERE	plan_id = ".$plan." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$protocolo = array();
			while (!$rst->EOF)
			{
				$protocolo = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $protocolo;
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function ObtenerNivelAutorizacion($uid)
		{
			$sql  = "SELECT nivel_autorizador_id ";
			$sql .= "FROM		userpermisos_centro_autorizacion ";
			$sql .= "WHERE 	usuario_id = ".$uid." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$nivel = array();
			while (!$rst->EOF)
			{
				$nivel = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $nivel;
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function ObtenerTiposPlanes($plan)
		{
			$sql  = "SELECT sw_tipo_plan, ";
			$sql .= "				sw_afiliacion, ";
			$sql .= "				protocolos, ";
			$sql .= "				sw_autoriza_sin_bd, ";
			$sql .= "				sw_solicita_autorizacion_admision AS sw_autorizacion ";
			$sql .= "FROM		planes ";
			$sql .= "WHERE 	estado = '1' ";
			$sql .= "AND 		plan_id = ".$plan." ";
			$sql .= "AND 		fecha_final >= now() ";
			$sql .= "AND		fecha_inicio <= now() ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$planes = array();
			while (!$rst->EOF)
			{
				$planes = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $planes;
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function CrearAutorizacion($datos,$uid,$clase,$plan,$ingreso,$cargos)
		{
			$tipo_autoriza = "";
			$fecha_validez = "NULL";
			
			$fec = explode('/',$datos['fecha']);
			$fecha_autoriza = "'".$fec[2]."-".$fec[1]."-".$fec[0]." ".$datos['hora'].":".$datos['minuto']."'" ;
			
			if($datos['tipoautoriza_interna'])
				$tipo_autoriza = $datos['tipoautoriza_interna'];
			else
				$tipo_autoriza = $datos['tipoautoriza_externa'];
			
			if($datos['fecha_validez'])
			{
				$fec = explode('/',$datos['fecha_validez']);
				$fecha_validez = "'".$fec[2]."-".$fec[1]."-".$fec[0]."'" ;
			}
			
			$autoid = "";
			$sql  = "SELECT NEXTVAL('autorizaciones_autorizacion_seq') ";
			$this->ConexionTransaccion();
			
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			
			if(!$rst->EOF) $autoid = $rst->fields[0];

			if(!$ingreso) $ingreso = "NULL";
			
			$sql  = "INSERT INTO autorizaciones( ";
			$sql .= "				autorizacion, ";
			$sql .= "				fecha_autorizacion, ";
			$sql .= "				observaciones, ";
			$sql .= "				usuario_id, ";
			$sql .= "				fecha_registro, ";
			$sql .= "				sw_estado, ";
			$sql .= "				ingreso, ";
			$sql .= "				clase_autorizacion, ";
			$sql .= "				tipo_autorizacion, ";
			$sql .= "				tipo_autorizador, ";
			$sql .= "				codigo_autorizacion, ";
			$sql .= "				codigo_autorizacion_generador, ";
			$sql .= "				descripcion_autorizacion, ";
			$sql .= "				fecha_vencimiento, ";
			$sql .= "				plan_id, ";
			$sql .= "				tipo_afiliado_id, ";
			$sql .= "				semanas_cotizadas, ";
			$sql .= "				rango) ";
			$sql .= "VALUES ( ";
			$sql .= "				".$autoid.", ";
			$sql .= "				".$fecha_autoriza.", ";
			$sql .= "				'".$datos['observacion_general']."', ";
			$sql .= "				".$uid.", ";
			$sql .= "				NOW(), ";
			$sql .= "				'1', ";
			$sql .= "				".$ingreso.", ";
			$sql .= "				'".$clase."', ";
			$sql .= "				'".$tipo_autoriza."', ";
			$sql .= "				'".$datos['tipo_autorizacion']."', ";
			$sql .= "				'".$datos['codigoau']."', ";
			$sql .= "				'".$datos['codigo_generador']."', ";
			$sql .= "				'".$datos['observacion']."', ";
			$sql .= "				 ".$fecha_validez." , ";
			$sql .= "				 ".$plan.", ";
			$sql .= "				'".$datos['tipoafiliado']."', ";
			$sql .= "				 ".$datos['Semanas'].",";
			$sql .= "				'".$datos['rango']."' ";
			$sql .= "				) ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$sql = "";
			
			if(!empty($cargos))
			{
				foreach($cargos as $keyI => $cargosI)
				{
					foreach($cargosI as $keyII => $cantidad)
					{
						$sql .= "UPDATE hc_os_solicitudes ";
						$sql .= "SET		autorizacion = ".$autoid.", ";
						$sql .= "				sw_estado = '0' ";
						$sql .= "WHERE	hc_os_solicitud_id = ".$keyI." ";
						$sql .= "AND		cargo = '".$keyII."'; ";
					}
				}
				if($sql != "")
					if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			}
      
      if($datos['solicitud_autorizacion'])
      {
        $sql  = "UPDATE solicitud_autorizacion_serv ";
        $sql .= "SET    autorizacion = ".$autoid." ";
        $sql .= "WHERE  numero_solicitud = ".$datos['solicitud_autorizacion']." ";
        $sql .= "AND    autorizacion IS NULL ";
        
        if(!$rst = $this->ConexionTransaccion($sql,'4')) return false;
        
        $sql  = "UPDATE solicitud_autorizacion_serv ";
        $sql .= "SET    ingreso = ".$ingreso." ";
        $sql .= "WHERE  numero_solicitud = ".$datos['solicitud_autorizacion']." ";
        $sql .= "AND    ingreso IS NULL ";
        
        if(!$rst = $this->ConexionTransaccion($sql,'5')) return false;
      }
			$this->dbconn->CommitTrans();
			
			return $autoid;
		}
		/** 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*/
		function ProcesarSqlConteo($consulta,$limite=null)
		{
			$this->offset = 0;
			$this->pagina = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($this->requestoff)
			{
				$this->pagina = intval($this->requestoff);
				if($this->pagina > 1)
				{
					$this->offset = ($this->pagina - 1) * ($this->limit);
				}
			}		
			
			if(!$_REQUEST['registros'])
			{
				if(!$rst = $this->ConexionBaseDatos($consulta))
					return false;
	
				if(!$rst->EOF)
				{
					$this->conteo = $rst->fields[0];
					$rst->MoveNext();
				}
				$rst->Close();
			}
			else
			{
				$this->conteo = $_REQUEST['registros'];
			}
			return true;
		}
    /**
    * Funcion donde se obtiene la informacion de la autorizacion de la orden de
    * servicio
    *
    * @param array $datos Filtros de busqueda
    *
    * @return mixed
    */
    function ObtenerAutorizacionesOS($datos)
    {
      $sql  = "SELECT autorizacion_ext,";
      $sql .= "       autorizacion_int,";
      $sql .= "       tipo_afiliado_id, ";
      $sql .= "       rango, ";
      $sql .= "       semanas_cotizadas ";
      $sql .= "FROM   os_ordenes_servicios as b ";
      $sql .= "WHERE  orden_servicio_id = ".$datos['orden_servicio_id']." ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
    }
    /**
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		*/
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
					echo "<b class=\"label\">Trasaccion: $num - ".$this->frmError['MensajeError']."</b>";
					$this->dbconn->RollbackTrans();
					return false;
				}
				return $rst;
			}
		}
	}
?>