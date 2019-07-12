<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: Ordenes.class.php,v 1.7 2008/11/18 20:46:24 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : Ordenes
  * Clase encargada de hacer las consultas y actualizaciones de las ordenes de servicios
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Ordenes extends ConexionBD
  {
    /**
    * Variable para guardar los datos de las ordenes
    *
    * @var array
    * @access public
    */
    var $numero_orden = array();
    /**
    * Constructor de la clase
    */
    function Ordenes(){}
    /**
    * Funcion donde se llama a la funcion para la creacion de las ordenes de servicio
    *
    * @param array $datos Arreglo de datos para la creacion de las ordenes de servicio
    * @param string $empresa Identificador de la empresa
    * @param string $dias Numero de dias para el calculo de la fecha de vencimiento
    * @param array $cargos_add Arreglo de datos con la informacion de los cargos
    * @param array $medica_add Arreglo de datos con la informacion de los medicamentos
    *
    * @return array
    */
    function CrearOdenServicio($datos,$empresa,$dias,$cargos_add,$medica_add)
    {
      if(!$this->IngresarOrdenesServicio($datos,$empresa,$dias,$cargos_add,$medica_add))
      {
        $sql  = "SELECT SETVAL('eps_autorizaciones_autorizacion_id_seq',nextval('eps_autorizaciones_autorizacion_id_seq')-1); ";
        $sql .= "SELECT SETVAL('eps_ordenes_servicios_eps_orden_servicio_seq',nextval('eps_ordenes_servicios_eps_orden_servicio_seq')-1); ";
        $this->ConexionBaseDatos($sql);
        
        return false;
      }
      return $this->numero_orden;
    }
    /**
    * Funcion donde se crean de las ordenes de servicio
    *
    * @param array $datos Arreglo de datos para la creacion de las ordenes de servicio
    * @param string $empresa Identificador de la empresa
    * @param string $dias Numero de dias para el calculo de la fecha de vencimiento
    * @param array $cargos_add Arreglo de datos con la informacion de los cargos
    * @param array $medica_add Arreglo de datos con la informacion de los medicamentos
    *
    * @return boolean
    */
    function IngresarOrdenesServicio($datos,$empresa,$dias,$cargos_add,$medica_add)
    { 
      $fecha = date("Y-m-d", mktime(0, 0, 0,date('m'),intval(date('d')+$dias),date("Y")));

      $sql = "SELECT NEXTVAL('eps_autorizaciones_autorizacion_id_seq') AS indice ";
      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
			
			$numero = array();
			if(!$rst->EOF)
      {
      	$numero = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
      $autorizacion = $numero['indice'];
      
      $sql = "SELECT NEXTVAL('eps_ordenes_servicios_eps_orden_servicio_seq') AS indice ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
			
			if(!$rst->EOF)
      {
      	$numero = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
      $ordenes = $numero['indice'];
      
      foreach($datos['proveedores'] as $key => $dtl)
      {
        $sql  = "INSERT INTO eps_autorizaciones(";
        $sql .= "   autorizacion_id,";
        $sql .= "   empresa_id,";
        $sql .= "   codigo_proveedor_id,";
        $sql .= "   usuario_id ";
        $sql .= "   )";
        $sql .= "VALUES(";
        $sql .= "    ".$autorizacion.", ";
        $sql .= "   '".$empresa."', ";
        $sql .= "    ".$key.", ";
        $sql .= "    ".UserGetUID()." ";
        $sql .= ")";
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        $sql  = "INSERT INTO eps_ordenes_servicios(";
        $sql .= "   eps_orden_servicio,";
        $sql .= "   empresa_id ,";
        $sql .= "   autorizacion_id ,";
        $sql .= "   codigo_proveedor_id ,";
        $sql .= "   fecha_registro ,";
        $sql .= "   fecha_vencimiento ,";
        $sql .= "   tipo_id_paciente ,";
        $sql .= "   paciente_id ,";
        $sql .= "   observacion ,";
        $sql .= "   estamento_id ,";
        $sql .= "   usuario_id ) ";
        $sql .= "VALUES(";
        $sql .= "    ".$ordenes.", ";
        $sql .= "   '".$empresa."', ";
        $sql .= "    ".$autorizacion.", ";
        $sql .= "    ".$key.", ";
        $sql .= "    NOW(), ";
        $sql .= "    '".$fecha."', ";
        $sql .= "    '".$datos['tipo_id_paciente']."' ,";
        $sql .= "    '".$datos['paciente_id']."',";
        $sql .= "    '".$datos['observacion_orden']."',";
        ($datos['estamento_id'])? $sql .= "    '".$datos['estamento_id']."',":$sql .= "    NULL,";
        $sql .= "    ".UserGetUID()." ";
        $sql .= ") ";
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
        foreach($datos['cargos'] as $keyI => $dtli)
        {
          foreach($dtli as $keyC => $dtlc)
          {
            if($dtlc['proveedor'] == $key)
            {            
              foreach($cargos_add[$keyI][$dtlc['cargo']] as $c => $d)
              {
                $sql  = "INSERT INTO eps_ordenes_servicios_cargos (";
                $sql .= "   eps_orden_servicio_cargo,";
                $sql .= "   eps_orden_servicio ,";
                $sql .= "   tarifario_id 	,";
      	        $sql .= "   cargo_base ,";
      	        $sql .= "   cargo ,";
                $sql .= "   cantidad ,";
                $sql .= "   valor ,";
                $sql .= "   valor_cubierto ,";
                $sql .= "   numero_solicitud_orden";
                $sql .= "   )";
                $sql .= "VALUES (";
                $sql .= "     DEFAULT,";
                $sql .= "    ".$ordenes.", ";
                $sql .= "   '".$d['tarifario']."', ";
                $sql .= "   '".$dtlc['cargo']."', ";
                $sql .= "   '".$c."', ";
                $sql .= "   '".$d['cantidad']."', ";
                $sql .= "   '".$d['valor']."', ";
                $sql .= "   '".($d['valor']*$d['porcentaje']/100)."', ";
                $sql .= "   '".$datos['numero_solicitud_orden']."' ";
                $sql .= ")";
                if(!$rst = $this->ConexionTransaccion($sql)) return false;
                
                $sql  = "UPDATE eps_solicitudes_ordenes_cargos ";
                $sql .= "SET    eps_orden_servicio = ".$ordenes." ";
                $sql .= "WHERE  eps_solicitud_orden_cargo_id = ".$keyI." "; 
                $sql .= "AND    numero_solicitud_orden = ".$datos['numero_solicitud_orden']." "; 
                $sql .= "AND    eps_orden_servicio IS NULL "; 
                if(!$rst = $this->ConexionTransaccion($sql)) return false;
                
                $sql  = "UPDATE hc_os_solicitudes  ";
                $sql .= "SET    sw_estado = '0' ";
                $sql .= "WHERE  hc_os_solicitud_id = ( ";
                $sql .= "         SELECT  DISTINCT hc_os_solicitud_id  ";
                $sql .= "         FROM    eps_solicitudes_ordenes_cargos ";
                $sql .= "         WHERE   eps_solicitud_orden_cargo_id = ".$keyI." ) ";  

                if(!$rst = $this->ConexionTransaccion($sql)) return false;                
              }
            }
          }
        }
        
        foreach($datos['medicamento'] as $keyM => $dtlm)
        {
          if($dtlm['proveedor'] == $key)
          {
            $sql  = "INSERT INTO eps_ordenes_servicios_medicamentos (";
            $sql .= "   eps_orden_servicio_medicamento, ";
            $sql .= "   eps_orden_servicio , ";
            $sql .= "   codigo_medicamento , ";
            $sql .= "   cantidad , ";
            $sql .= "   valor , ";
            $sql .= "   numero_solicitud_orden";
            $sql .= "   )";
            $sql .= "VALUES (";
            $sql .= "     DEFAULT,";
            $sql .= "   ".$ordenes.", ";
            $sql .= "   '".$dtlm['producto']."', ";
            $sql .= "    ".$dtlm['cantidad'].", ";
            $sql .= "   '".$medica_add[$dtlm['producto']]['valor']."', ";
            $sql .= "   '".$datos['numero_solicitud_orden']."' ";
            $sql .= ")";
            if(!$rst = $this->ConexionTransaccion($sql)) return false;
            
            $sql  = "UPDATE eps_solicitudes_ordenes_medicamentos ";
            $sql .= "SET    eps_orden_servicio = ".$ordenes." ";
            $sql .= "WHERE  codigo_medicamento = '".$dtlm['producto']."' "; 
            $sql .= "AND    numero_solicitud_orden = ".$datos['numero_solicitud_orden']." "; 
            $sql .= "AND    eps_orden_servicio IS NULL ";
              
            if(!$rst = $this->ConexionTransaccion($sql)) return false;
          }
        }
        
        foreach($datos['conceptos'] as $keyP => $dtlp)
        {
          if($dtlp['proveedor'] == $key)
          {
            $sql  = "INSERT INTO eps_ordenes_servicios_conceptos (";
            $sql .= "   eps_orden_servicio_concepto,";
            $sql .= "   eps_orden_servicio,";
            $sql .= "   eps_solicitud_orden_concepto, ";
            $sql .= "   valor , ";
            $sql .= "   numero_solicitud_orden";
            $sql .= "   )";
            $sql .= "VALUES (";
            $sql .= "     DEFAULT,";
            $sql .= "   ".$ordenes.", ";
            $sql .= "   ".$dtlp['concepto'].", ";
            $sql .= "   ".$dtlp['valor'].", ";
            $sql .= "   '".$datos['numero_solicitud_orden']."' ";
            $sql .= ")";
            if(!$rst = $this->ConexionTransaccion($sql)) return false;
            
            $sql  = "UPDATE eps_solicitudes_ordenes_conceptos ";
            $sql .= "SET    eps_orden_servicio = ".$ordenes." ";
            $sql .= "WHERE  eps_solicitud_orden_concepto = ".$dtlp['concepto']." "; 
            $sql .= "AND    numero_solicitud_orden = ".$datos['numero_solicitud_orden']." "; 
            $sql .= "AND    eps_orden_servicio IS NULL ";
              
            if(!$rst = $this->ConexionTransaccion($sql)) return false;
          }
        }
        $this->numero_orden[$ordenes] = $ordenes;
        $ordenes++;
        $autorizacion++;
      }
      
      $sql  = "SELECT SETVAL('eps_autorizaciones_autorizacion_id_seq',".($autorizacion-1)."); ";
      $sql .= "SELECT SETVAL('eps_ordenes_servicios_eps_orden_servicio_seq',".($ordenes-1)."); ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $this->Commit();
      return true;
    }
    /**
    * Funcion donde se obtienen el detalle de las ordenes de servicio creadas
    *
    * @param array $datos Fitros para la busqueda de las ordenes de servicio
    * 
    * @return mixed
    */
    function ObtenerOrdenesServicioDetalle($datos)
    {
      $ordenes = array();
      
      $sql  = "SELECT DISTINCT ES.codigo_proveedor_id,";
      $sql .= "       ES.eps_orden_servicio, ";
      $sql .= "       ES.autorizacion_id, ";
      $sql .= "       EQ.descripcion_qx ,";
      $sql .= "       TE.cargo_base, ";
      $sql .= "       TD.cargo, ";
      $sql .= "       TD.tarifario_id, ";
      $sql .= "       TD.descripcion AS descripcion_equivalencia, ";
      $sql .= "       CU.descripcion AS descripcion_base, ";
      $sql .= "       EQ.cargo_qx ,";     
      $sql .= "       EC.cantidad ,";
      $sql .= "       EC.valor, ";
      $sql .= "       EC.valor_cubierto ";
      $sql .= "FROM   eps_ordenes_servicios_cargos EC ";
      $sql .= "       LEFT JOIN ( ";
      $sql .= "         SELECT  CU.cargo AS cargo_qx, ";
      $sql .= "                 CU.descripcion AS descripcion_qx, ";
      $sql .= "                 ES.eps_orden_servicio ";
      $sql .= "         FROM    eps_solicitudes_ordenes_cargos ES, ";
      $sql .= "                 cups CU ";
      $sql .= "         WHERE   ES.cargo_qx = CU.cargo ";
      if(is_array($datos['numeros_ordenes']))
        $sql .= "       AND     ES.eps_orden_servicio IN (".implode(",", $datos['numeros_ordenes']).") ";
      else if($datos['eps_orden_servicio'])
        $sql .= "       AND    ES.eps_orden_servicio = ".$datos['eps_orden_servicio']." "; 
      
      $sql .= "       ) AS EQ ";
      $sql .= "       ON (EC.eps_orden_servicio = EQ.eps_orden_servicio), ";
      $sql .= "       eps_ordenes_servicios ES, ";
 			$sql .= "				tarifarios_detalle TD, ";
 			$sql .= "				tarifarios_equivalencias TE, ";
 			$sql .= "				cups CU ";
      $sql .= "WHERE  ES.eps_orden_servicio = EC.eps_orden_servicio ";
      $sql .= "AND    TD.tarifario_id = EC.tarifario_id ";
      $sql .= "AND    TD.cargo = EC.cargo ";
      $sql .= "AND    TD.tarifario_id = TE.tarifario_id ";
      $sql .= "AND    TD.cargo = TE.cargo ";
      $sql .= "AND    Cu.cargo = TE.cargo_base ";
      if($datos['numero_solicitud_orden'])
        $sql .= "AND    EC.numero_solicitud_orden = ".$datos['numero_solicitud_orden']." "; 
      
      if(is_array($datos['numeros_ordenes']))
        $sql .= "AND    ES.eps_orden_servicio IN (".implode(",", $datos['numeros_ordenes']).") ";
      else if($datos['eps_orden_servicio'])
        $sql .= "AND    ES.eps_orden_servicio = ".$datos['eps_orden_servicio']." "; 
      
      if($datos['codigo_proveedor_id'] && $datos['codigo_proveedor_id'] != '-1')
        $sql .= "AND    ES.codigo_proveedor_id = ".$datos['codigo_proveedor_id']." "; 
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      while(!$rst->EOF)
			{
				$ordenes[$rst->fields[0]][$rst->fields[1]]['cargos'][$rst->fields[3]][$rst->fields[4]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
        
      $sql  = "SELECT ES.codigo_proveedor_id,";
      $sql .= "       ES.eps_orden_servicio, ";
      $sql .= "       ES.eps_orden_servicio, ";
      $sql .= "       ES.autorizacion_id, ";
      $sql .= "       EO.descripcion_concepto_adicional, ";
      $sql .= "       EO.tipo_concepto_id, ";
      $sql .= "       EC.valor ";
      $sql .= "FROM   eps_ordenes_servicios_conceptos EC, "; 
      $sql .= "       eps_ordenes_servicios ES, ";
      $sql .= "       eps_solicitudes_ordenes_conceptos EO ";
      $sql .= "WHERE  ES.eps_orden_servicio = EC.eps_orden_servicio ";
      $sql .= "AND    EC.eps_solicitud_orden_concepto = EO.eps_solicitud_orden_concepto ";
      
      if($datos['numero_solicitud_orden'])
        $sql .= "AND    EC.numero_solicitud_orden = ".$datos['numero_solicitud_orden']." "; 

      if(is_array($datos['numeros_ordenes']))
        $sql .= "AND    ES.eps_orden_servicio IN (".implode(",", $datos['numeros_ordenes']).") ";
      else if($datos['eps_orden_servicio'])
        $sql .= "AND    ES.eps_orden_servicio = ".$datos['eps_orden_servicio']." "; 

      if($datos['codigo_proveedor_id'] && $datos['codigo_proveedor_id'] != '-1')
        $sql .= "AND    ES.codigo_proveedor_id = ".$datos['codigo_proveedor_id']." "; 

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      while(!$rst->EOF)
			{
				$ordenes[$rst->fields[0]][$rst->fields[1]]['conceptos'][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
        
      $sql  = "SELECT ES.codigo_proveedor_id,";
      $sql .= "       ES.eps_orden_servicio, ";
      $sql .= "       ES.autorizacion_id, ";
      $sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion AS descripcion_producto, ";
			$sql .= "				EM.cantidad, ";
			$sql .= "				EM.valor ";
      $sql .= "FROM   eps_ordenes_servicios_medicamentos EM, ";
      $sql .= "       eps_ordenes_servicios ES, ";
      $sql .= "      	inventarios_productos IM ";
      $sql .= "WHERE  ES.eps_orden_servicio = EM.eps_orden_servicio ";
      $sql .= "AND	  IM.codigo_producto = EM.codigo_medicamento ";
      
      if($datos['numero_solicitud_orden'])
        $sql .= "AND    EM.numero_solicitud_orden = ".$datos['numero_solicitud_orden']." "; 
      
      if(is_array($datos['numeros_ordenes']))
        $sql .= "AND    ES.eps_orden_servicio IN (".implode(",", $datos['numeros_ordenes']).") ";
      else if($datos['eps_orden_servicio'])
        $sql .= "AND    ES.eps_orden_servicio = ".$datos['eps_orden_servicio']." "; 
      
      if($datos['codigo_proveedor_id'] && $datos['codigo_proveedor_id'] != '-1')
        $sql .= "AND    ES.codigo_proveedor_id = ".$datos['codigo_proveedor_id']." "; 

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      while(!$rst->EOF)
			{
				$ordenes[$rst->fields[0]][$rst->fields[1]]['medicamentos'][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $ordenes;
    }
    /**
    * Funcion donde se obtine la informacion de las ordenes de servicio 
    * junto con la informacion del proveedor y paciente involucrado
    *
    * @param string $empresa Identificador de la enpresa 
    * @param array $datos Fitros para la busqueda de las ordenes de servicio
    * @param array $off Offset para la busqueda
    *
    * @return mixed
    */
    function ObtenerOrdenesServicio($empresa,$datos,$off)
    {
      $sql  = "SELECT ES.codigo_proveedor_id,";
      $sql .= "       ES.eps_orden_servicio,";
      $sql .= " 	 	  ES.autorizacion_id,";
      $sql .= " 	    TO_CHAR(ES.fecha_registro,'DD/MM/YYYY') AS fecha_registro ,";
      $sql .= "	      TO_CHAR(ES.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento,";
      $sql .= "	      ES.tipo_id_paciente,";
      $sql .= " 	    ES.paciente_id, ";
      $sql .= "	      ES.observacion,";
      $sql .= "	      ES.empresa_id,";
      $sql .= "	      ES.observacion,";
      $sql .= " 	    EE.descripcion_estamento 	, ";
      $sql .= " 	    PA.primer_nombre, ";
      $sql .= " 	    PA.segundo_nombre, ";
      $sql .= " 	    PA.primer_apellido, ";
      $sql .= " 	    PA.segundo_apellido, ";
      $sql .= "       TE.tipo_id_tercero,  ";
      $sql .= "       TE.nombre_tercero,  ";
      $sql .= "       TE.telefono,  ";
      $sql .= "       TE.direccion,  ";
      $sql .= "       TE.tercero_id, ";
      $sql .= "       TE.tipo_id_tercero ";
      $sql .= "FROM   eps_ordenes_servicios ES ";
      $sql .= "       LEFT JOIN eps_estamentos EE ";
      $sql .= "       ON (EE.estamento_id = ES.estamento_id), ";
      $sql .= "       terceros_proveedores TP, ";
      $sql .= "       terceros TE, ";
      $sql .= "       pacientes PA ";
      $sql .= "WHERE  ES.empresa_id = '".$empresa."' ";
      $sql .= "AND 	  ES.codigo_proveedor_id = TP.codigo_proveedor_id ";
      $sql .= "AND    ES.tipo_id_paciente = PA.tipo_id_paciente ";
      $sql .= "AND 	  ES.paciente_id = PA.paciente_id ";
      $sql .= "AND    TE.tercero_id = TP.tercero_id ";
      $sql .= "AND    TE.tipo_id_tercero = TP.tipo_id_tercero ";
      
      if(is_array($datos['numeros_ordenes']))
        $sql .= "AND    ES.eps_orden_servicio IN (".implode(",", $datos['numeros_ordenes']).") ";
      else if($datos['eps_orden_servicio'])
          $sql .= "AND    ES.eps_orden_servicio = ".$datos['eps_orden_servicio']." "; 
      
      if($datos['codigo_proveedor_id'] && $datos['codigo_proveedor_id'] != '-1')
        $sql .= "AND    ES.codigo_proveedor_id = ".$datos['codigo_proveedor_id']." "; 
      
      if($datos['nombres1'] || $datos['nombres2'] || $datos['apellidos1'] || $datos['apellidos2'])
      {
        $cutil = AutoCarga::factory('ClaseUtil');
        $sql .= "AND  ".$cutil->FiltrarNombres(trim($datos['nombres1']." ".$datos['nombres2']),trim($datos['apellidos1']." ".$datos['apellidos2']),"PA");
      }
      
      if($datos['tipo_id_paciente'] != "-1" && $datos['paciente_id'])
      {
        $sql .= "AND    PA.tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
        $sql .= "AND    PA.paciente_id = '".$datos['paciente_id']."' ";
      }
      
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off);
      
      $sql .= "ORDER BY ES.codigo_proveedor_id ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $ordenes = array();
      while(!$rst->EOF)
			{
				$ordenes[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $ordenes;
    }
    /**
    * Funcion donde se obtiene el estamento de un afiliado
    *
    * @param string $empresa Identificador de la empresa
    * @param integer $cxp_orden_pago identificador de la preorden de pago
    *
    * @return mixed
    */
    function ObtenerEstamento($datos)
    {
      $sql  = "SELECT * ";
      $sql .= "FROM   ( ";
      $sql .= "           SELECT  'FAMILIAR '||EE.descripcion_estamento AS descripcion ";
      $sql .= "           FROM    eps_afiliados_beneficiarios EB,"; 
      $sql .= "                   eps_afiliados_cotizantes EC, " ;
      $sql .= "                   eps_estamentos EE " ;
      $sql .= "           WHERE   EB.cotizante_tipo_id = EC.afiliado_tipo_id";
      $sql .= "           AND     EB.cotizante_id = EC.afiliado_id ";
      $sql .= "           AND     EB.eps_afiliacion_id = EC.eps_afiliacion_id ";
      $sql .= "           AND     EC.estamento_id = EE.estamento_id ";
      $sql .= "           AND     EB.afiliado_id = '".$datos['paciente_id']."' ";
      $sql .= "           AND     EB.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
      $sql .= "           UNION ALL ";
      $sql .= "           SELECT  'FUNCIONARIO '||EE.descripcion_estamento AS descripcion ";
      $sql .= "           FROM    eps_afiliados_cotizantes EC, " ;
      $sql .= "                   eps_estamentos EE " ;
      $sql .= "           WHERE   EC.estamento_id = EE.estamento_id  ";
      $sql .= "           AND     EC.afiliado_id = '".$datos['paciente_id']."' ";
      $sql .= "           AND     EC.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
      $sql .= "           UNION ALL ";
      $sql .= "           SELECT  'ESTUDIANTE' AS descripcion ";
      $sql .= "           FROM    interfaz_uv.bd_estudiantes " ;
      $sql .= "           WHERE   paciente_id = '".$datos['paciente_id']."' ";
      $sql .= "           AND     tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
      $sql .= "         ) AS AF ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }
    /**
    * Funcion donde se obtienen el plan, asociado a la orden de servicio
    *
    * @param array $datos Fitros para la busqueda de las ordenes de servicio
    * 
    * @return mixed
    */
    function ObtenerCodigoPlanOrden($eps_orden_servicio)
    {
      $plan = array();
      
      $sql  = "SELECT EO.plan_id ";
      $sql .= "FROM   eps_ordenes_servicios_cargos EC, ";
      $sql .= "       eps_solicitudes_ordenes EO ";
      $sql .= "WHERE  EC.eps_orden_servicio = ".$eps_orden_servicio." ";
      $sql .= "AND    EO.numero_solicitud_orden = EC.numero_solicitud_orden "; 
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
			{
				$plan = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      if(!empty($plan)) return $plan['plan_id'];
      
      $sql  = "SELECT EO.plan_id ";
      $sql .= "FROM   eps_ordenes_servicios_conceptos EC, ";
      $sql .= "       eps_solicitudes_ordenes EO ";
      $sql .= "WHERE  EC.eps_orden_servicio = ".$eps_orden_servicio." ";
      $sql .= "AND    EO.numero_solicitud_orden = EC.numero_solicitud_orden "; 
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
			{
				$plan = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      if(!empty($plan)) return $plan['plan_id'];
      
      $sql  = "SELECT EO.plan_id ";
      $sql .= "FROM   eps_ordenes_servicios_medicamentos EC, ";
      $sql .= "       eps_solicitudes_ordenes EO ";
      $sql .= "WHERE  EC.eps_orden_servicio = ".$eps_orden_servicio." ";
      $sql .= "AND    EO.numero_solicitud_orden = EC.numero_solicitud_orden "; 
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
			{
				$plan = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

      return $plan['plan_id'];
    }
  }
?>