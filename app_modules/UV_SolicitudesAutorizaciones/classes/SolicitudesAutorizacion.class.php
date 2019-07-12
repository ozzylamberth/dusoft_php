<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: SolicitudesAutorizacion.class.php,v 1.8 2008/11/18 20:46:24 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : SolicitudesAutorizacion
  * Clase encargada de hacer las consultas y actualizaciones de las solicitudes
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.8 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class SolicitudesAutorizacion extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function SolicitudesAutorizacion(){}
    /**
    * Funcion donde se validan los permisos de un usuario sobre el modulo
    * 
    * @return mixed
    */
    function ObtenerPermisos()
    {
      $sql  = "SELECT	EM.empresa_id AS empresa, ";
			$sql .= "				EM.razon_social AS razon_social, ";
			$sql .= "				US.nivel_autorizador_id ";
			$sql .= "FROM	  userpermisos_eps_solicitudes US,";
      $sql .= "       empresas EM ";
			$sql .= "WHERE	US.usuario_id = ".UserGetUID()." ";
			$sql .= "AND    US.empresa_id = EM.empresa_id ";
			$sql .= "AND    US.sw_activo = '1' ";
			
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
    }
    /**
    * Funcion donde se buscan los tipos de identificacion registrados 
    * en el sistema
    *
    * @return array
    */
    function ObtenerTiposIdentificacion()
    {
      $sql  = "SELECT tipo_id_paciente,";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipos_id_pacientes ";

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
    /**
    * Funcion donde se obtienen los planes registrados en el sistema
    * 
    * @param array $datos Vector con los datos de los filtros
    *
    * @return array
    */
    function ObtenerPlanes($datos)
    { 
      $sql  = "SELECT	plan_id,";
			$sql .= "       plan_descripcion, ";
			$sql .= "       mensaje_plan ";
			$sql .= "FROM 	planes ";
			$sql .= "WHERE 	fecha_final::date >= NOW()::date ";
			$sql .= "AND 		estado = '1' ";
			$sql .= "AND 		fecha_inicio::date <= NOW()::date ";
      if($datos['plan_id'])
        $sql .= "AND    plan_id = ".$datos['plan_id']." ";
			$sql .= "ORDER BY plan_descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
      
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
    }
    /**
    * Obtiene la informacion de un afiliado determinado
    *
    * @param array $datos Vector con la informacion del tipo e identificacion
    *               del afiliado
    * @return array
    */
    function ObtenerDatosAfiliados($datos)
    {
      $sql  = "SELECT AD.afiliado_tipo_id AS tipo_id_paciente , ";
      $sql .= "       AD.afiliado_id AS paciente_id, ";
      $sql .= "       AD.primer_apellido    , ";
      $sql .= "       AD.segundo_apellido   , ";
      $sql .= "       AD.primer_nombre  , ";
      $sql .= "       AD.segundo_nombre     , ";
      $sql .= "       AD.fecha_nacimiento, ";
      $sql .= "       AD.tipo_sexo_id   , ";
      $sql .= "       AD.tipo_pais_id   , ";
      $sql .= "       AD.tipo_dpto_id   , ";
      $sql .= "       AD.tipo_mpio_id   , ";
      $sql .= "       AD.zona_residencia    , ";
      $sql .= "       AD.direccion_residencia   , ";
      $sql .= "       AD.telefono_residencia, ";
      $sql .= "       AF.eps_tipo_afiliado_id AS tipo_afiliado_id, ";
      $sql .= "       AF.semanas_cotizadas, ";
      $sql .= "       AC.estamento_id, ";
      $sql .= "       'UNICO' AS rango ";
      $sql .= "FROM   eps_afiliados_datos AD,";
      $sql .= "       eps_afiliados AF LEFT JOIN ";
      $sql .= "       eps_afiliados_cotizantes AC ";
      $sql .= "       ON( ";
      $sql .= "         AC.eps_afiliacion_id = AF.eps_afiliacion_id  AND ";
      $sql .= "         AC.afiliado_tipo_id = AF.afiliado_tipo_id AND ";
      $sql .= "         AC.afiliado_id = AF.afiliado_id ";
      $sql .= "       ) ";
      $sql .= "WHERE  AD.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
      $sql .= "AND    AD.afiliado_id = '".$datos['paciente_id']."' ";
      $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AF.estado_afiliado_id IN ('AC') ";

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
    /**
		* Funcion donde se buscan los cargos registrados en el sistema
    * 
    * @param array $datos Vector con los filtros para la busqueda
    * @param int $off Parametro del offset, para la paginacion
    *
    * @return array
		*/
		function ObtenerCargos($datos,$off)
		{
			$sql  = "SELECT DISTINCT CU.cargo,";
			$sql .= "				CU.descripcion, ";
			$sql .= "				GT.grupo_tipo_cargo, ";
			$sql .= "				GT.descripcion as tipo, ";
      $sql .= "       CU.sw_cantidad,  ";
			$sql .= "				CU.nivel_autorizador_id ";
			$sql .= "FROM		cups CU,";
			$sql .= "   		tipos_cargos TC,";
			$sql .= "				grupos_tipos_cargo GT ";
			$sql .= "WHERE	CU.grupo_tipo_cargo = TC.grupo_tipo_cargo ";		
			$sql .= "AND	  CU.tipo_cargo = TC.tipo_cargo ";		
			$sql .= "AND	  GT.grupo_tipo_cargo = TC.grupo_tipo_cargo ";		
			$sql .= "AND 		CU.sw_activo = '1' ";
			if($datos['cargo'])
        $sql .= "AND		CU.cargo ILIKE '".$datos['cargo']."' ";
			if($datos['descripcion'])
        $sql .= "AND		CU.descripcion ILIKE '%".$datos['descripcion']."%' ";
      if($datos['grupo_tipo_cargo'] != '-1' && $datos['grupo_tipo_cargo'])
        $sql .= "AND    CU.grupo_tipo_cargo = '".$datos['grupo_tipo_cargo']."' ";
			
			$cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off,$cant);
      
      $sql .= "ORDER BY CU.descripcion ";
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
    /**
    * Funcion donde se obtienen los diferentes grupos a los cuales 
    * perteneecen lo cargos
    *
    * @return array
    */
    function ObtenerGruposTiposCargos()
    {
      $sql  = "SELECT grupo_tipo_cargo, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   grupos_tipos_cargo ";
      $sql .= "ORDER BY descripcion ";
      
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
    /***
		* Funcion donde se obtienen los tipos de servicios asistenciales
		*
		* @return array
		*/
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
    /**
    * Funcion donde se hace la validacion de un cargo, retornando si esta
    * contratado o no para el plan dado
    *
    * @param String $cargo Identificador del cargo
    * @param int $plan Identificador del plan
    *
    * @return mixed
    */
    function ObtenerValidacionContrato($cargo,$plan)
		{
			$sql  = "SELECT PT.plan_id ";
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
			
			if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;
			
      $conteo = $rst->RecordCount();
      
			return $conteo;
		}
    /**
		* Funcion donde se buscan los medicamentos registrados en el sistema
    * 
    * @param array $datos Vector con los filtros para la busqueda
    * @param String $empresa Identificador de la enpresa
    * @param int $off Parametro del offset, para la paginacion
    *
    * @return array
		*/
		function ObtenerMedicamentos($datos,$empresa,$off)
		{
			$sql  = "SELECT CASE WHEN ME.sw_pos = '1' THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion AS descripcion_producto, ";
			$sql .= "				IA.descripcion AS principio_activo,";
			$sql .= "				ME.nivel_autorizador_id, ";
      $sql .= "				IV.descripcion AS ummi ";
      $sql .= "FROM 	inventarios_productos IM, ";
			$sql .= "				inv_med_cod_principios_activos IA,  ";
			$sql .= "				medicamentos ME,  ";
			$sql .= "				inv_unidades_medida_medicamentos IV  ";
			$sql .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		IM.estado = '1' ";
			$sql .= "AND 		IV.unidad_medida_medicamento_id = ME.unidad_medida_medicamento_id ";
			//$sql .= "AND 		IT.empresa_id = '".$empresa."' ";
			//$sql .= "AND 		IT.codigo_producto = IM.codigo_producto ";
			
      if($datos['codigo']) 
        $sql .= "AND	  IM.codigo_producto = '".$datos['codigo']."' ";
      if($datos['descripcion']) 
        $sql .= "AND	  IM.descripcion ILIKE '%".$datos['descripcion']."%' ";
      if($datos['principio_activo']) 
        $sql .= "AND	  IA.descripcion ILIKE '%".$datos['principio_activo']."%' ";

      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off,$cant);
      
      $sql .= "ORDER BY descripcion_producto ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
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
    * Funcion donde se buscan los tipos de identificacion registrados 
    * en el sistema
    *
    * @param int $concepto Identificador del cocepto
    *
    * @return array
    */
    function ObtenerTiposConceptos($concepto)
    {
      $sql  = "SELECT tipo_concepto_id,";
      $sql .= "       sw_exige_valor, ";
      $sql .= "       descripcion_concepto, ";
      $sql .= "       nivel_autorizador_id ";
      $sql .= "FROM   eps_tipos_conceptos ";
      $sql .= "WHERE  sw_estado = '1' ";
      if($concepto)
        $sql .= "AND    tipo_concepto_id = ".$concepto." ";

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
    /**
    * Obtiene la descripcion de un estamento determinado
    *
    * @param int $estamento Identificador del estamento
    *
    * @return mixed
    */
    function ObtenerDescripcionEstamento($estamento)
    {
      $sql  = "SELECT descripcion_estamento ";
      $sql .= "FROM   eps_estamentos ";
      $sql .= "WHERE  estamento_id = '".$estamento."' ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      if(!$rst->EOF)
      {
          $datos = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
      }
      $rst->Close();
      return $datos['descripcion_estamento'];
    }
    /**
    * Obtiene la descripcion de un tipo de afiliado
    *
    * @param string $tipo_afiliado Identificador del tipo de afiliado
    *
    * @return array
    */
    function ObtenerDescripcionTiposAfiliados($tipo_afiliado)
    {
      $sql  = "SELECT descripcion_eps_tipo_afiliado ";
      $sql .= "FROM   eps_tipos_afiliados ";
      $sql .= "WHERE  eps_tipo_afiliado_id = '".$tipo_afiliado."'";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      if (!$rst->EOF)
      {
          $datos = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
      }
      $rst->Close();
      return $datos['descripcion_eps_tipo_afiliado'];
    }
    /**
    * Obtiene la descripcion de un tipo de afiliado
    *
    * @param string $tipo_afiliado Identificador del tipo de afiliado
    *
    * @return array
    */
    function ObtenerTiposCargosQX($grupo)
    {
      $sql  = "SELECT grupo_tipo_cargo ";
      $sql .= "FROM   qx_grupos_tipo_cargo ";
      $sql .= "WHERE  grupo_tipo_cargo = '".$grupo."'";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      if (!$rst->EOF)
      {
          $datos = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
      }
      $rst->Close();
      return $datos['grupo_tipo_cargo'];
    }
    /**
		* Funcion para obtener los datos de un paciente en particular, de acuerdo a los 
    * filtros pasados por parametros
    *
    * @param array $datos Filytro para obtener los datos del paciente 
    *              (tipo_id_paciente y paciente_id)
    *
    * @return array
		*/
		function ObtenerPaciente($datos)
		{
			$sql  = "SELECT	PA.paciente_id,";
			$sql .= " 			PA.tipo_id_paciente,";
			$sql .= " 			PA.primer_apellido,";
			$sql .= " 			PA.segundo_apellido,";
			$sql .= " 			PA.primer_nombre,";
			$sql .= " 			PA.segundo_nombre ";
			$sql .= "FROM		pacientes PA ";
			$sql .= "WHERE 	PA.tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
			$sql .= "AND		PA.paciente_id = '".$datos['paciente_id']."' ";
			
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
    /**
    * Funcion donde se realiza la creacion de la solicitud de servicios
    *
    * @param array $solicitud Arreglo con los datos basicos de la solicitud
    * @param array $cargos Arreglo con los datos de los cargos seleccionados
    * @param array $medicamentos Arreglo con los datos de los medicamentos seleccionados
    * @param array $conceptos Arreglo con los datos de los conceptos seleccionados
    *
    * @return mixed
    */
    function IngresarSolicitud($solicitud,$cargos,$medicamentos,$conceptos,$cargo_qx)
    {
      $indice = array();
      $sql = "SELECT NEXTVAL('eps_solicitudes_ordenes_numero_solicitud_orden_seq') AS sq";
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
            
      if (!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      $sqlerror = "SELECT setval('eps_solicitudes_ordenes_numero_solicitud_orden_seq',".($indice['sq']-1).") ";
      
      $sql = "LOCK TABLE eps_solicitudes_ordenes IN ROW EXCLUSIVE MODE; ";
      $this->ConexionTransaccion();
      
      if(!$rst = $this->ConexionTransaccion($sql)) 
      {
        if(!$rst = $this->ConexionBaseDatos($sqlerror)) return false;
        return false;
      }
      
      $sql  = "INSERT INTO eps_solicitudes_ordenes( ";
      $sql .= "     numero_solicitud_orden,";
      $sql .= "     tipo_id_paciente, ";
	    $sql .= "     paciente_id , ";
      $sql .= "     plan_id , ";
      $sql .= "     usuario_registro ";
      $sql .= "     )";
      $sql .= "VALUES( ";
      $sql .= "     ".$indice['sq'].", ";
      $sql .= "    '".$solicitud['tipo_id_paciente']."', ";
      $sql .= "    '".$solicitud['paciente_id']."', ";
      $sql .= "    '".$solicitud['plan_id']."', ";
      $sql .= "     ".UserGetUID()." ";
      $sql .= "     ); ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) 
      {
        if(!$rst = $this->ConexionBaseDatos($sqlerror)) return false;
        return false;
      }
      
      if(!empty($cargos))
      {
        foreach($cargos as $key => $dtl)
        {
          if($this->ObtenerTiposCargosQX($dtl['grupo_tipo_cargo']) == $dtl['grupo_tipo_cargo'])
            $sql  = "INSERT INTO eps_solicitudes_ordenes_cirugia ( ";
          else
            $sql  = "INSERT INTO eps_solicitudes_ordenes_cargos ( ";
          
          $sql .= "     cargo ,";
          $sql .= "     numero_solicitud_orden ,";
          $sql .= "     nivel_autorizador_id, ";
          $sql .= "     cantidad  ";
          $sql .= "     )";
          $sql .= "VALUES (";
          $sql .= "    '".$dtl['cargo']."', ";
          $sql .= "     ".$indice['sq'].", ";
          $sql .= "    '".$dtl['nivel_autorizador_id']."', ";
          $sql .= "     ".$dtl['cantidad']." ";
          $sql .= "     );";
          if(!$rst = $this->ConexionTransaccion($sql)) 
          {
            if(!$rst = $this->ConexionBaseDatos($sqlerror)) return false;
            return false;
          }
        }
      }
      
      if(!empty($medicamentos))
      {
        foreach($medicamentos as $key => $dtl)
        {
          $sql  = "INSERT INTO eps_solicitudes_ordenes_medicamentos ( ";
          $sql .= "     codigo_medicamento , ";
          $sql .= "     numero_solicitud_orden , ";
    	    $sql .= "     nivel_autorizador_id,  ";
    	    $sql .= "     cantidad  ";
          $sql .= "     )";
          $sql .= "VALUES (";
          $sql .= "    '".$dtl['codigo_producto']."', ";
          $sql .= "     ".$indice['sq'].", ";
          $sql .= "    '".$dtl['nivel_autorizador_id']."', ";
          $sql .= "     ".$dtl['cantidad']." ";
          $sql .= "     );";
          if(!$rst = $this->ConexionTransaccion($sql)) 
          {
            if(!$rst = $this->ConexionBaseDatos($sqlerror)) return false;
            return false;
          }
        }
      }
      
      if(!empty($conceptos))
      {
        foreach($conceptos as $key => $dtl)
        {
          $sql  = "INSERT INTO eps_solicitudes_ordenes_conceptos( ";
          $sql .= "     tipo_concepto_id ,";
          $sql .= "     numero_solicitud_orden ,";
          $sql .= "     nivel_autorizador_id ,";
          $sql .= "     descripcion_concepto_adicional "; 
          $sql .= "     )";
          $sql .= "VALUES (";
          $sql .= "     ".$dtl['tipo_concepto_id'].", ";
          $sql .= "     ".$indice['sq'].", ";
          $sql .= "    '".$dtl['nivel_autorizador_id']."', ";
          $sql .= "    '".$dtl['concepto_adicional']."' ";
          $sql .= "     );";
          if(!$rst = $this->ConexionTransaccion($sql)) 
          {
            if(!$rst = $this->ConexionBaseDatos($sqlerror)) return false;
            return false;
          }
        }
      }
      $this->Commit();
      return $indice['sq'];
    }
    /**
    * Funcion donde se obtiene la informacion de los cargos vinculados en una
    * solicitud de servicios
    * 
    * @param array $datos Filtros de la consulta
    * @param array $solicitud Arreglo donde se guardaran los datos 
    * @param string $fecha Fecha de filtro para el vencimiento de la solicitud
    * @param string $nivel Identificador del nivel del usuario
    *
    * @return mixed 
    */
    function ObtenerSolicitudesCargosPendientes($datos,$solicitud,$fecha,$nivel)
    {
      $sql  = "SELECT EO.numero_solicitud_orden,";
      $sql .= "				GT.grupo_tipo_cargo, 	 ";
      $sql .= "				GT.descripcion AS grupo_cargo_descripcion,";
      $sql .= "       CU.cargo, ";
      $sql .= "       CU.sw_cantidad, ";
			$sql .= "				CU.descripcion, ";
      if($nivel)
        $sql .= "       CASE WHEN EC.nivel_autorizador_id <= '".$nivel."' THEN '0' ELSE EC.nivel_autorizador_id END AS nivel_autorizador, ";
      else
        $sql .= "				EC.nivel_autorizador_id AS nivel_autorizador, ";
      $sql .= "				C1.descripcion AS qx_descripcion, ";
      $sql .= "				EC.nivel_autorizador_id, ";
      $sql .= "				EC.cantidad, ";
      $sql .= "				EC.eps_solicitud_orden_cargo_id, ";
      $sql .= "				PL.plan_descripcion, ";
      $sql .= "				TO_CHAR(EO.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
      $sql .= "FROM   eps_solicitudes_ordenes EO, ";
      $sql .= "       eps_solicitudes_ordenes_cargos EC ";
			$sql .= "				LEFT JOIN cups C1 ";
			$sql .= "				ON (C1.cargo = EC.cargo_qx), ";
			$sql .= "				cups CU, ";
      $sql .= "      	planes PL, ";
      $sql .= "      	grupos_tipos_cargo GT ";
			$sql .= "WHERE	CU.cargo = EC.cargo ";		
			$sql .= "AND 		EO.paciente_id = '".$datos['paciente_id']."' ";
      $sql .= "AND    EO.tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
      $sql .= "AND    EO.numero_solicitud_orden = EC.numero_solicitud_orden ";
      $sql .= "AND    EO.plan_id = PL.plan_id ";
      $sql .= "AND    CU.grupo_tipo_cargo = GT.grupo_tipo_cargo ";
      $sql .= "AND    EC.eps_orden_servicio IS NULL ";
      $sql .= "AND    EO.fecha_registro::date > '".$fecha."' ";
      if($datos['numero_solicitud_orden'])
        $sql .= "AND    EO.numero_solicitud_orden = ".$datos['numero_solicitud_orden']." ";
        
      $sql .= "ORDER BY grupo_cargo_descripcion ";
      if($nivel) $sql .= ",nivel_autorizador,EO.numero_solicitud_orden ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return $rst;
    }    
    /**
    * Funcion donde se obtiene la informacion de los cargos quirurgicos vinculados en una
    * solicitud de servicios
    * 
    * @param array $datos Filtros de la consulta
    * @param array $solicitud Arreglo donde se guardaran los datos 
    * @param string $dias 
    * @param string $nivel Identificador del nivel del usuario
    *
    * @return mixed 
    */
    function ObtenerSolicitudesCargosQXPendientes($datos,$solicitud,$dias,$nivel)
    {
      $fecha = date("Y-m-d", mktime(0, 0, 0,date('m'),intval(date('d')-$dias),date("Y")));
           
      $sql  = "SELECT EO.numero_solicitud_orden,";
      $sql .= "				GT.grupo_tipo_cargo, 	 ";
      $sql .= "				GT.descripcion AS grupo_cargo_descripcion,";
      $sql .= "       CU.cargo, ";
      $sql .= "       CU.sw_cantidad, ";
			$sql .= "				CU.descripcion, ";
      if($nivel)
        $sql .= "       CASE WHEN EC.nivel_autorizador_id <= '".$nivel."' THEN '0' ELSE EC.nivel_autorizador_id END AS nivel_autorizador, ";
      else
        $sql .= "				EC.nivel_autorizador_id AS nivel_autorizador, ";
      $sql .= "				EC.nivel_autorizador_id, ";
      $sql .= "				EC.cantidad, ";
      $sql .= "				PL.plan_descripcion, ";
      $sql .= "				TO_CHAR(EO.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
      $sql .= "FROM   eps_solicitudes_ordenes EO, ";
      $sql .= "       eps_solicitudes_ordenes_cirugia EC, ";
			$sql .= "				cups CU, ";
      $sql .= "      	planes PL, ";
      $sql .= "      	grupos_tipos_cargo GT ";
			$sql .= "WHERE	CU.cargo = EC.cargo ";		
			$sql .= "AND 		EO.paciente_id = '".$datos['paciente_id']."' ";
      $sql .= "AND    EO.tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
      $sql .= "AND    EO.numero_solicitud_orden = EC.numero_solicitud_orden ";
      $sql .= "AND    EO.plan_id = PL.plan_id ";
      $sql .= "AND    CU.grupo_tipo_cargo = GT.grupo_tipo_cargo ";
      $sql .= "AND    EC.eps_orden_servicio IS NULL ";
      $sql .= "AND    EC.sw_seleccion = '0' ";
      $sql .= "AND    EO.fecha_registro::date > '".$fecha."' ";
      if($datos['numero_solicitud_orden'])
        $sql .= "AND    EO.numero_solicitud_orden = ".$datos['numero_solicitud_orden']." ";
        
      $sql .= "ORDER BY grupo_cargo_descripcion ";
      if($nivel) $sql .= ",nivel_autorizador,EO.numero_solicitud_orden ";
      
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
    /**
    * Funcion donde se obtiene la informacion de los medicamentos vinculados en una
    * solicitud de servicios
    * 
    * @param array $datos Filtros de la consulta
    * @param array $solicitud Arreglo donde se guardaran los datos 
    * @param string $fecha Fecha de filtro para el vencimiento de la solicitud
    * @param string $nivel Identificador del nivel del usuario
    *
    * @return mixed 
    */
    function ObtenerSolicitudesMedicamentosPendientes($datos,$solicitud,$fecha,$nivel)
    {
      $sql  = "SELECT EO.numero_solicitud_orden,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion AS descripcion_producto, ";
      if($nivel)
        $sql .= "       CASE WHEN EC.nivel_autorizador_id <= '".$nivel."' THEN '0' ELSE EC.nivel_autorizador_id END AS nivel_autorizador, ";
      else
        $sql .= "				EC.nivel_autorizador_id AS nivel_autorizador, ";
			
      $sql .= "				PL.plan_descripcion, ";
      $sql .= "				EC.cantidad, ";
      $sql .= "				TO_CHAR(EO.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
      $sql .= "FROM   eps_solicitudes_ordenes EO, ";
      $sql .= "       eps_solicitudes_ordenes_medicamentos EC, ";
      $sql .= "      	inventarios_productos IM, ";
			$sql .= "				medicamentos ME,  ";
      $sql .= "      	planes PL ";
			$sql .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND	  IM.codigo_producto = EC.codigo_medicamento ";
			$sql .= "AND 		EO.paciente_id = '".$datos['paciente_id']."' ";
      $sql .= "AND    EO.tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
      $sql .= "AND    EO.numero_solicitud_orden = EC.numero_solicitud_orden ";
      $sql .= "AND    EO.plan_id = PL.plan_id ";
      $sql .= "AND    EC.eps_orden_servicio IS NULL ";
      $sql .= "AND    EO.fecha_registro::date > '".$fecha."' ";
      if($datos['numero_solicitud_orden'])
        $sql .= "AND    EO.numero_solicitud_orden = ".$datos['numero_solicitud_orden']." ";

      if($nivel) $sql .= "ORDER BY nivel_autorizador,EO.numero_solicitud_orden ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return $rst;
    }
    /**
    * Funcion donde se obtiene la informacion de los conceptos vinculados en una
    * solicitud de servicios
    * 
    * @param array $datos Filtros de la consulta
    * @param array $solicitud Arreglo donde se guardaran los datos 
    * @param string $fecha Fecha de filtro para el vencimiento de la solicitud
    * @param string $nivel Identificador del nivel del usuario
    *
    * @return mixed 
    */
    function ObtenerSolicitudesConceptosPendientes($datos,$solicitud,$fecha,$nivel)
    {
      $sql  = "SELECT EO.numero_solicitud_orden,";
			$sql .= "				ET.tipo_concepto_id, ";
			$sql .= "				ET.descripcion_concepto, ";
      if($nivel)
        $sql .= "       CASE WHEN ET.nivel_autorizador_id <= '".$nivel."' THEN '0' ELSE ET.nivel_autorizador_id END AS nivel_autorizador, ";
      else
        $sql .= "				ET.nivel_autorizador_id AS nivel_autorizador, ";

			$sql .= "				EC.descripcion_concepto_adicional, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				EC.eps_solicitud_orden_concepto, ";
      $sql .= "				TO_CHAR(EO.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
      $sql .= "FROM   eps_solicitudes_ordenes EO, ";
      $sql .= "       eps_solicitudes_ordenes_conceptos EC, ";
      $sql .= "      	eps_tipos_conceptos ET, ";
      $sql .= "      	planes PL ";
			$sql .= "WHERE	EO.paciente_id = '".$datos['paciente_id']."' ";
      $sql .= "AND    EO.tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
      $sql .= "AND    EO.numero_solicitud_orden = EC.numero_solicitud_orden ";
      $sql .= "AND    EC.tipo_concepto_id = ET.tipo_concepto_id ";
      $sql .= "AND    EO.plan_id = PL.plan_id ";
      $sql .= "AND    EC.eps_orden_servicio IS NULL ";
      $sql .= "AND    EO.fecha_registro::date > '".$fecha."' ";
      if($datos['numero_solicitud_orden'])
        $sql .= "AND    EO.numero_solicitud_orden = ".$datos['numero_solicitud_orden']." ";

      if($nivel) $sql .= "ORDER BY ET.nivel_autorizador_id,EO.numero_solicitud_orden ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return $rst;
    }
    /**
    * Funcion donde se obtiene la informacion de las solicitudes de servicios
    * 
    * @param array $datos Filtros de la consulta
    * @param string $dias Numero de dias para el calculo de la fecha de vencimiento
    *
    * @return mixed 
    */
    function ObtenerSolicitudesPendientes($datos,$dias)
    {
      $fecha = date("Y-m-d", mktime(0, 0, 0,date('m'),intval(date('d')-$dias),date("Y")));
      $solicitud = array();
      if(!$rst = $this->ObtenerSolicitudesCargosPendientes($datos,$solicitud,$fecha))
        return false;
      
      while(!$rst->EOF)
			{
				$solicitud[$rst->fields[0]]['C'][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      if(!$rst = $this->ObtenerSolicitudesMedicamentosPendientes($datos,$solicitud,$fecha))
        return false;
      
      while(!$rst->EOF)
			{
				$solicitud[$rst->fields[0]]['M'][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
        
      if(!$rst = $this->ObtenerSolicitudesConceptosPendientes($datos,$solicitud,$fecha))
        return false;
      
      while(!$rst->EOF)
			{
				$solicitud[$rst->fields[0]]['P'][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      ksort($solicitud);
      return $solicitud;
    }
    /**
    * Funcion donde se obtiene la informacion de la solicitud de servicios
    * 
    * @param array $datos Filtros de la consulta
    * @param string $dias Numero de dias para el calculo de la fecha de vencimiento
    * @param string $nivel Identificador del nivel del usuario
    *
    * @return mixed     
    */
    function ObtenerSolicitudesAutorizar($datos,$dias,$nivel)
    {
      $fecha = date("Y-m-d", mktime(0, 0, 0,date('m'),intval(date('d')-$dias),date("Y")));
      $solicitud = array();
      if(!$rst = $this->ObtenerSolicitudesCargosPendientes($datos,$solicitud,$fecha,$nivel))
        return false;
      
      while(!$rst->EOF)
			{
				$solicitud['CARGOS'][$rst->fields[2]][$rst->fields[6]][$rst->fields[7]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      if(!$rst = $this->ObtenerSolicitudesMedicamentosPendientes($datos,$solicitud,$fecha,$nivel))
        return false;
      
      while(!$rst->EOF)
			{
				$solicitud['MEDICAMENTOS']['0'][$rst->fields[3]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
        
      if(!$rst = $this->ObtenerSolicitudesConceptosPendientes($datos,$solicitud,$fecha,$nivel))
        return false;
      
      while(!$rst->EOF)
			{
				$solicitud['CONCEPTOS'][$rst->fields[2]][$rst->fields[3]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $solicitud;
    }
    /**
    * Funcion donde se obtine la lista de solicitudes no autorizadas
    *
    * @param array $filtros Arreglo de datos con los filtros para la busqueda
    * @param array $off Offset para la busqueda
    *
    * @return mixed
    */
    function ObtenerListaSolicitudesNoAutorizadas($filtros,$off)
    {
      
      $sql  = "SELECT PL.plan_descripcion, ";
      $sql .= "       PL.plan_id,";
      $sql .= "       PA.primer_nombre,";
      $sql .= "       PA.segundo_nombre,";
      $sql .= "       PA.primer_apellido,";
      $sql .= "       PA.segundo_apellido, ";
      $sql .= "       PA.paciente_id, ";
      $sql .= "       PA.tipo_id_paciente, ";
      $sql .= "       EO.numero_solicitud_orden, ";
      $sql .= "       TO_CHAR(EO.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
      $sql .= "FROM   planes PL, ";
      $sql .= "       pacientes PA, ";
      $sql .= "       eps_solicitudes_ordenes EO ";
      $sql .= "WHERE  PL.plan_id = EO.plan_id ";
      $sql .= "AND    EO.paciente_id = PA.paciente_id ";
      $sql .= "AND    EO.tipo_id_paciente = PA.tipo_id_paciente ";
      $sql .= "AND    EO.estado = '0' ";
      if($filtros['numero_solicitud_orden'])
        $sql .= "AND     EO.numero_solicitud_orden = ".$filtros['numero_solicitud_orden']." ";
      if($filtros['tipo_id_paciente'] != "-1" && $filtros['paciente_id'])
      {
        $sql .= "AND    PA.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
        $sql .= "AND    PA.paciente_id = '".$filtros['paciente_id']."' ";
      }
      if($filtros['plan_id'] && $filtros['plan_id'] != '-1')
        $sql .= "AND     PL.plan_id = ".$filtros['plan_id']."";
        
      if($filtros['nombres1'] || $filtros['nombres2'] || $filtros['apellidos1'] || $filtros['apellidos2'])
      {
        $cutil = AutoCarga::factory('ClaseUtil');
        $sql .= "AND  ".$cutil->FiltrarNombres(trim($filtros['nombres1']." ".$filtros['nombres2']),trim($filtros['apellidos1']." ".$filtros['apellidos2']),"PA");
      }
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off);
      
      $sql .= "ORDER BY EO.numero_solicitud_orden ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
    }
    /**
    * Funcion donde se obtienen los proveedores
    *
    * @param string $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function ObtenerProveedores($empresa)
    {      
      $sql  = "SELECT TP.codigo_proveedor_id, ";
      $sql .= "       TR.nombre_tercero  ";
      $sql .= "FROM   terceros_proveedores TP, ";
      $sql .= "       terceros TR ";
      $sql .= "WHERE  TR.tercero_id = TP.tercero_id ";
      $sql .= "AND    TR.tipo_id_tercero = TP.tipo_id_tercero ";
			
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
    /**
    *
    */
    function ObtenerCargosDC($plan)
    {
      $sql  = "SELECT DISTINCT CU.cargo AS dc_cargo, ";
      $sql .= "       CU.cargo, ";
      $sql .= "       CU.nivel_autorizador_id, ";
      $sql .= "       CU.descripcion ";
      $sql .= "FROM   tarifarios_uvrs TU, ";
      $sql .= "       cups CU, ";
      $sql .= "       plan_tarifario PL ";
      $sql .= "WHERE  CU.cargo = TU.dc_cups ";
      $sql .= "AND    PL.tarifario_id = TU.tarifario_id ";
      $sql .= "AND    PL.plan_id = ".$plan." ";
      
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
    /**
    *
    */
    function ObtenerCargosDA($plan)
    {
      $sql  = "SELECT DISTINCT CU.cargo AS da_cargo, ";
      $sql .= "       CU.cargo, ";
      $sql .= "       CU.nivel_autorizador_id, ";
      $sql .= "       CU.descripcion ";
      $sql .= "FROM   tarifarios_uvrs TU, ";
      $sql .= "       cups CU, ";
      $sql .= "       plan_tarifario PL ";
      $sql .= "WHERE  CU.cargo = TU.da_cups ";
      $sql .= "AND    PL.tarifario_id = TU.tarifario_id ";
      $sql .= "AND    PL.plan_id = ".$plan." ";
      
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
    /**
    *
    */
    function ObtenerCargosDY($plan)
    {
      $sql  = "SELECT DISTINCT CU.cargo AS dy_cargo, ";
      $sql .= "       CU.cargo, ";
      $sql .= "       CU.nivel_autorizador_id, ";
      $sql .= "       CU.descripcion ";
      $sql .= "FROM   tarifarios_uvrs TU, ";
      $sql .= "       cups CU, ";
      $sql .= "       plan_tarifario PL ";
      $sql .= "WHERE  CU.cargo = TU.dy_cups ";
      $sql .= "AND    PL.tarifario_id = TU.tarifario_id ";
      $sql .= "AND    PL.plan_id = ".$plan." ";
      
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
    /**
    *
    */
    function ObtenerCargosSala($plan)
    {
      $sql  = "SELECT DISTINCT CU.nivel_autorizador_id, ";
      $sql .= "       CU.cargo, ";
      $sql .= "       CU.descripcion ";
      $sql .= "FROM   tarifarios_uvrs_ds_rangos TU, ";
      $sql .= "       cups CU, ";
      $sql .= "       plan_tarifario PL ";
      $sql .= "WHERE  TU.ds_cups = CU.cargo ";
      $sql .= "AND    PL.tarifario_id = TU.tarifario_id ";
      $sql .= "AND    PL.plan_id = ".$plan." ";
      
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
    /**
    *
    */
    function ObtenerCargosMateriales($plan)
    {
      $sql  = "SELECT DISTINCT CU.nivel_autorizador_id, ";
      $sql .= "       CU.cargo, ";
      $sql .= "       CU.descripcion ";
      $sql .= "FROM   tarifarios_uvrs_dm_rangos TU, ";
      $sql .= "       cups CU , ";
      $sql .= "       plan_tarifario PL ";
      $sql .= "WHERE  TU.dm_cups = CU.cargo ";
      $sql .= "AND    PL.tarifario_id = TU.tarifario_id ";
      $sql .= "AND    PL.plan_id = ".$plan." ";
      
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
    /**
    *
    */
    function AdicionarCargosSolicitud($cargos,$solicitud,$cargo_qx)
    {
      $this->ConexionTransaccion();
      foreach($cargos as $key => $dtl)
      {     
        if($dtl['cargo'])
        {
          $sql  = "INSERT INTO eps_solicitudes_ordenes_cargos ( ";
          $sql .= "     cargo ,";
          $sql .= "     numero_solicitud_orden ,";
          $sql .= "     nivel_autorizador_id, ";
          $sql .= "     cargo_qx, ";
          $sql .= "     cantidad  ";
          $sql .= "     )";
          $sql .= "VALUES (";
          $sql .= "    '".$dtl['cargo']."', ";
          $sql .= "     ".$solicitud.", ";
          $sql .= "    '".$dtl['nivel']."', ";
          $sql .= "    '".$cargo_qx."', ";
          $sql .= "     ".$dtl['cantidad']." ";
          $sql .= "     );";
          
          if(!$rst = $this->ConexionTransaccion($sql)) return false;
        }
      }
      
      $sql  = "UPDATE eps_solicitudes_ordenes_cirugia ";
      $sql .= "SET    sw_seleccion = '1' ";
      $sql .= "WHERE  cargo = '".$cargo_qx."' ";
      $sql .= "AND    numero_solicitud_orden = ".$solicitud." ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $sql  = "UPDATE hc_os_solicitudes  ";
      $sql .= "SET    sw_estado = '0' ";
      $sql .= "WHERE  hc_os_solicitud_id = ( ";
      $sql .= "         SELECT DISTINCT hc_os_solicitud_id  ";
      $sql .= "         FROM   eps_solicitudes_ordenes_cirugia ";
      $sql .= "         WHERE  cargo = '".$cargo_qx."' ";
      $sql .= "         AND    numero_solicitud_orden = ".$solicitud." )";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $this->Commit();
      return true;
    }
  }
?>