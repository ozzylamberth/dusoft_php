<?php
  class Resultados extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function Resultados(){}
    /**
    *
    */
    function ObtenerLecturaApoyos($paciente)
    {
      $sql  = "SELECT DISTINCT B.*,";
      $sql .= "       C.resultado_id ,";
      $sql .= "       C.tecnica_id ,";
      $sql .= "       D.sw_prof,";
      $sql .= "       D.sw_prof_dpto,";
      $sql .= "       D.sw_prof_todos,";
      $sql .= "       E.informacion,";
      $sql .= "       E.apoyod_tipo_id,";
      $sql .= "       CASE WHEN E.titulo_examen IS NOT NULL THEN E.titulo_examen";
      $sql .= "            ELSE F.descripcion END AS titulo,";
      $sql .= "       CASE WHEN M.hc_os_solicitud_id IS NOT NULL THEN '1'";
      $sql .= "            ELSE '0' END AS autorizado, ";
      $sql .= "       PL.plan_descripcion ";
      $sql .= "FROM   ( ";
      $sql .= "         ( ";
      $sql .= "           SELECT  A.*,";
      $sql .= "                   H.sw_modo_resultado,";
      $sql .= "                   H.fecha_realizado,";
      $sql .= "                   H.resultado_id ,";
      $sql .= "                   CASE WHEN F.resultado_id IS NULL THEN '0'";
      $sql .= "                        ELSE F.resultado_id END AS resultados_sistema,";
      $sql .= "                   CASE WHEN G.resultado_id IS NULL THEN '0'";
      $sql .= "                        ELSE G.resultado_id END AS resultado_manual";
      $sql .= "           FROM    (";
      $sql .= "                     SELECT	B.usuario_id,";
      $sql .= "                             B.departamento,";
      $sql .= "                             B.fecha,";
      $sql .= "                             A.hc_os_solicitud_id,";
      $sql .= "                             A.cargo,";
      $sql .= "                             A.os_tipo_solicitud_id,";
      $sql .= "                             A.plan_id,";
      $sql .= "                             CASE WHEN C.sw_estado IS NULL THEN '0' ";
      $sql .= "                                  ELSE C.sw_estado END AS realizacion,";
      $sql .= "                             C.numero_orden_id,";
      $sql .= "                             A.evolucion_id,";
      $sql .= "                             D.*";
      $sql .= "                     FROM	  hc_os_solicitudes A LEFT JOIN ";
      $sql .= "                             hc_apoyod_lectura_grupal_detalle D ";
      $sql .= "                             ON( A.evolucion_id = D.evolucion_id_solicitud ";
      if($paciente['evolucion'])
        $sql .= "                               AND D.evolucion_id = ".$paciente['evolucion']." ";
      
      $sql .= "                             ), ";
      $sql .= "                             hc_evoluciones B,";
      $sql .= "                             os_maestro C";
      $sql .= "                     WHERE	  A.tipo_id_paciente = '".$paciente['tipo_id_paciente']."'";
      $sql .= "                     AND     A.paciente_id = '".$paciente['paciente_id']."'";
      if($paciente['evolucion_solicitud'])
        $sql .= "                     AND     A.evolucion_id = ".$paciente['evolucion_solicitud']." ";
      
      $sql .= "                     AND     B.evolucion_id = A.evolucion_id";
      $sql .= "                     AND     A.hc_os_solicitud_id = C.hc_os_solicitud_id";
      $sql .= "                   ) A";
      $sql .= "                   LEFT JOIN hc_resultados_sistema F ";
      $sql .= "                   ON(A.numero_orden_id = F.numero_orden_id)";
      $sql .= "                   LEFT JOIN hc_resultados_manuales G ";
      $sql .= "                   ON(A.numero_orden_id = G.numero_orden_id)";
      $sql .= "                   LEFT JOIN hc_resultados H ";
      $sql .= "                   ON ( G.resultado_id = H.resultado_id)";
      $sql .= "           WHERE H.fecha_realizado IS NOT NULL";
      $sql .= "         )";
      $sql .= "         UNION ALL ";
      $sql .= "         (";
      $sql .= "           SELECT  A.*,";
      $sql .= "                   H.sw_modo_resultado,";
      $sql .= "                   H.fecha_realizado,";
      $sql .= "                   H.resultado_id ,";
      $sql .= "                   CASE WHEN F.resultado_id IS NULL THEN '0' ";
      $sql .= "                        ELSE F.resultado_id END AS resultados_sistema, ";
      $sql .= "                   CASE WHEN G.resultado_id IS NULL THEN '0' ";
      $sql .= "                        ELSE G.resultado_id END AS resultado_manual";
      $sql .= "           FROM  (";
      $sql .= "                   SELECT	B.usuario_id,";
      $sql .= "                           B.departamento, ";
      $sql .= "                           B.fecha, ";
      $sql .= "                           A.hc_os_solicitud_id, ";
      $sql .= "                           A.cargo, ";
      $sql .= "                           A.os_tipo_solicitud_id,";
      $sql .= "                           A.plan_id,";
      $sql .= "                           CASE WHEN C.sw_estado IS NULL THEN '0'";
      $sql .= "                                ELSE C.sw_estado END AS realizacion,";
      $sql .= "                           C.numero_orden_id,";
      $sql .= "                           A.evolucion_id,";
      $sql .= "                           D.*";
      $sql .= "                   FROM	  hc_os_solicitudes A LEFT JOIN ";
      $sql .= "                           hc_apoyod_lectura_grupal_detalle D ";
      $sql .= "                             ON( A.evolucion_id = D.evolucion_id_solicitud ";
      if($paciente['evolucion'])
        $sql .= "                               AND D.evolucion_id = ".$paciente['evolucion']." ";
      
      $sql .= "                             ), ";
      $sql .= "                           hc_evoluciones B,";
      $sql .= "                           os_maestro C";
      $sql .= "                   WHERE	  A.tipo_id_paciente = '".$paciente['tipo_id_paciente']."'";
      $sql .= "                   AND     A.paciente_id = '".$paciente['paciente_id']."'";
      if($paciente['evolucion_solicitud'])
        $sql .= "                     AND     A.evolucion_id = ".$paciente['evolucion_solicitud']." ";
      $sql .= "                   AND     B.evolucion_id = A.evolucion_id";
      $sql .= "                   AND     A.hc_os_solicitud_id = C.hc_os_solicitud_id ";
      $sql .= "                 ) A";
      $sql .= "                 LEFT JOIN hc_resultados_sistema AS F ";
      $sql .= "                 ON(A.numero_orden_id = F.numero_orden_id)";
      $sql .= "                 LEFT JOIN hc_resultados_manuales AS G ";
      $sql .= "                 ON(A.numero_orden_id = G.numero_orden_id)";
      $sql .= "                 LEFT JOIN hc_resultados as H";
      $sql .= "                 ON (F.resultado_id = H.resultado_id)";
      $sql .= "           WHERE H.fecha_realizado IS NOT NULL";
      $sql .= "         )";
      $sql .= "       ) B";
      $sql .= "       LEFT JOIN hc_apoyod_resultados_detalles C ";
      $sql .= "       ON (B.resultado_id = C.resultado_id AND C.cargo = B.cargo)";
      $sql .= "       LEFT JOIN hc_apoyod_lecturas_profesionales D ";
      $sql .= "       ON (D.resultado_id = C.resultado_id),";
      $sql .= "       apoyod_cargos E,";
      $sql .= "       cups F,";
      $sql .= "       apoyod_cargos_tecnicas G,";
      $sql .= "       lab_examenes H,";
      $sql .= "       planes PL,";
      $sql .= "       hc_os_autorizaciones M ";
      $sql .= "WHERE  B.cargo = E.cargo ";
      $sql .= "AND    E.cargo = F.cargo ";
      $sql .= "AND    G.cargo = B.cargo ";
      $sql .= "AND    C.tecnica_id = G.tecnica_id ";
      $sql .= "AND    PL.plan_id = B.plan_id ";
      $sql .= "AND    H.cargo = C.cargo ";
      $sql .= "AND    H.tecnica_id = C.tecnica_id ";
      $sql .= "AND    H.lab_examen_id = C.lab_examen_id ";
      $sql .= "AND    B.hc_os_solicitud_id = M.hc_os_solicitud_id ";
      $sql .= "ORDER BY apoyod_tipo_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
      
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
    *
    */
    function ConsultaResultadosNoSolicitados($paciente)
    {
   		$sql  = "SELECT b.sw_modo_resultado,";
      $sql .= "    		b.cargo,";
      $sql .= "    		b.fecha_realizado, ";
      $sql .= "    		b.resultado_id,";
      $sql .= "    		c.titulo_examen, ";
      $sql .= "    		c.informacion, ";
      $sql .= "    		d.sw_prof, ";
      $sql .= "    		d.evolucion_id, ";
      $sql .= "    		e.fecha ";
      $sql .= "FROM   hc_resultados_nosolicitados a ";
      $sql .= "	      LEFT JOIN hc_resultados b  ";
      $sql .= "       ON (a.resultado_id = b.resultado_id) ";
      $sql .= "  		  LEFT JOIN apoyod_cargos c ";
      $sql .= "       ON (b.cargo = c.cargo)  ";
      $sql .= "       LEFT JOIN hc_apoyod_lecturas_profesionales d ";
      $sql .= "       ON (b.resultado_id = d.resultado_id) ";
      $sql .= "  		  LEFT JOIN hc_evoluciones e ";
      $sql .= "       ON (d.evolucion_id = e.evolucion_id) ";
      $sql .= "WHERE	b.tipo_id_paciente = '".$paciente['tipo_id_paciente']."'";
      $sql .= "AND    B.paciente_id = '".$paciente['paciente_id']."' ";

      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
      
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
    *
    */
    function ConsultaNombreMedico($usuario)
    {
      $sql  = "SELECT d.nombre_tercero,";
      $sql .= "       c.descripcion ";
      $sql .= "FROM   profesionales_usuarios a, ";
      $sql .= "       profesionales b, ";
      $sql .= "       tipos_profesionales c, ";
      $sql .= "       terceros d ";
      $sql .= "WHERE  a.tipo_tercero_id = b.tipo_id_tercero  ";
      $sql .= "AND    a.tercero_id = b.tercero_id  ";
      $sql .= "AND    a.tipo_tercero_id = d.tipo_id_tercero  ";
      $sql .= "AND    a.tercero_id = d.tercero_id  ";
      $sql .= "AND    a.usuario_id = ".$usuario."  ";
      $sql .= "AND    b.tipo_profesional = c.tipo_profesional ";

      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

      $datos = $rst->GetRowAssoc($ToUpper = false);
      $rst->Close();
      
      return $datos;
    }
    /**
    *
    */
    function ObtenerDetalles($dts)
    {
      $sql  = "SELECT DISTINCT a.lab_examen_id, ";
      $sql .= "       a.resultado_id, ";
      $sql .= "       a.cargo, ";
      $sql .= "       a.tecnica_id, ";
      $sql .= "       a.resultado, ";
      $sql .= "       a.sw_alerta, ";
      $sql .= "       a.rango_min, ";
      $sql .= "       a.rango_max, ";
      $sql .= "       a.unidades, ";
      $sql .= "       b.lab_plantilla_id, ";
      $sql .= "       b.nombre_examen ";
      $sql .= "FROM   hc_apoyod_resultados_detalles a, ";
      $sql .= "       lab_examenes b ";
      $sql .= "WHERE  a.resultado_id = ".$dts['resultado_id']."  ";
      $sql .= "AND    a.cargo= '".$dts['cargo']."' ";
      $sql .= "AND    a.tecnica_id = ".$dts['tecnica_id']."  ";
      $sql .= "AND    a.tecnica_id = b.tecnica_id ";
      $sql .= "AND    a.cargo = b.cargo ";
      $sql .= "AND    a.lab_examen_id = b.lab_examen_id ";
      $sql .= "ORDER BY b.lab_plantilla_id ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			while (!$rst->EOF)
			{
				$datos['detalle'][]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->close();
      
		  $sql  = "SELECT b.profesional, ";
      $sql .= "       d.*, ";
      $sql .= "       g.descripcion as servicio, ";
      $sql .= "       e.historia_prefijo, ";
      $sql .= "       e.historia_numero, ";
      $sql .= "       idr.fecha_resultado, ";
      $sql .= "       idr.comentario ";
      $sql .= "FROM   os_maestro f, ";
      $sql .= "       os_ordenes_servicios c ";
      $sql .= "       LEFT JOIN hc_os_solicitudes_manuales_datos_adicionales d  ";
      $sql .= "       ON( c.orden_servicio_id = d.orden_servicio_id), ";
      $sql .= "       hc_os_solicitudes a  ";
      $sql .= "       LEFT JOIN hc_os_solicitudes_manuales b  ";
      $sql .= "       ON( a.hc_os_solicitud_id = b.hc_os_solicitud_id), ";
      $sql .= "       historias_clinicas e, ";
      $sql .= "       servicios g, ";
      $sql .= "       os_cumplimientos_detalle h, ";
      $sql .= "       interface_datalab_resultados idr ";
      $sql .= "WHERE  f.numero_orden_id = '".$dts['numero_orden_id']."' ";
      $sql .= "AND    f.orden_servicio_id = c.orden_servicio_id ";
      $sql .= "AND    f.hc_os_solicitud_id = a.hc_os_solicitud_id ";
      $sql .= "AND    c.tipo_id_paciente = e.tipo_id_paciente ";
      $sql .= "AND    c.paciente_id = e.paciente_id ";
      $sql .= "AND    f.numero_orden_id = h.numero_orden_id ";
      $sql .= "AND    f.numero_orden_id = idr.numero_orden_id";

      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if (!$rst->EOF)
			{
				$datos['datos_adicionales']=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->close();
	    //cargando las observaciones adicionales
      $sql  = "SELECT  a.resultado_id,";
      $sql .= "        a.observacion_adicional,";
      $sql .= "        a.fecha_registro_observacion,";
      $sql .= "        c.nombre_tercero as usuario_observacion ";
      $sql .= "FROM    hc_resultados_observaciones_adicionales a,";
      $sql .= "        profesionales_usuarios b,";
      $sql .= "        terceros c ";
      $sql .= "WHERE   resultado_id = ".$dts['resultado_id']." ";
      $sql .= "AND     a.usuario_id = b.usuario_id ";
      $sql .= "AND     b.tipo_tercero_id = c.tipo_id_tercero  ";
      $sql .= "AND     b.tercero_id = c.tercero_id ";
      $sql .= "ORDER BY a.observacion_resultado_id ";

      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			while (!$rst->EOF)
			{
				$datos['observaciones_adicionales'][]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->close();
      
      return $datos;
    }
    /**
    *
    */
    function ObtenerResultados($resultados)
    {
      $sql  = "SELECT	a.*, ";
      $sql .= "       e.empresa_id, ";
      $sql .= "       CASE WHEN (g.titulo_examen = '' or g.titulo_examen ISNULL) THEN h.descripcion ";
      $sql .= "            ELSE g.titulo_examen END as titulo, ";
      $sql .= "       g.informacion, ";
      $sql .= "       l.nombre_tercero, ";
      $sql .= "       m.tarjeta_profesional, ";
      $sql .= "       r.descripcion, ";
      $sql .= "       p.plan_descripcion ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  a.resultado_id, ";
      $sql .= "                 a.paciente_id, ";
      $sql .= "                 a.tipo_id_paciente, ";
      $sql .= "                 a.cargo, ";
      $sql .= "                 a.tecnica_id, ";
      $sql .= "                 a.fecha_realizado, ";
      $sql .= "                 a.usuario_id, ";
      $sql .= "                 a.observacion_prestacion_servicio, ";
      $sql .= "                 b.numero_orden_id, ";
      $sql .= "                 b.usuario_id_profesional_autoriza, ";
      $sql .= "                 b.usuario_id_profesional, ";
      $sql .= "                 c.orden_servicio_id, ";
      $sql .= "                 d.departamento ";
      $sql .= "         FROM    hc_resultados as a, ";
      $sql .= "                 hc_resultados_sistema as b, ";
      $sql .= "                 os_maestro as c, ";
      $sql .= "                 os_internas as d ";
      $sql .= "         WHERE   b.resultado_id = a.resultado_id ";
      $sql .= "				  AND     c.numero_orden_id = b.numero_orden_id ";
      $sql .= "				  AND     d.numero_orden_id = c.numero_orden_id ";
      $sql .= "         AND     a.resultado_id IN (".$resultados.") ";
      $sql .= "			  ) a, ";
      $sql .= "       departamentos e, ";
      $sql .= "       apoyod_cargos g, ";
      $sql .= "       cups h, ";
      $sql .= "       profesionales_usuarios k, ";
      $sql .= "       profesionales m, ";
      $sql .= "       terceros l, ";
      $sql .= "       tipos_profesionales r, ";
      $sql .= "       os_ordenes_servicios o, ";
      $sql .= "       planes p ";
      $sql .= "WHERE	e.departamento = a.departamento ";
      $sql .= "AND 	  g.cargo = a.cargo ";
      $sql .= "AND 	  h.cargo = a.cargo ";
      $sql .= "AND 	  k.usuario_id = a.usuario_id_profesional ";
      $sql .= "AND 	  m.tipo_id_tercero = k.tipo_tercero_id ";
      $sql .= "AND 	  m.tercero_id = k.tercero_id ";
      $sql .= "AND 	  l.tipo_id_tercero = m.tipo_id_tercero ";
      $sql .= "AND 	  l.tercero_id = m.tercero_id ";
      $sql .= "AND 	  r.tipo_profesional = m.tipo_profesional ";
      $sql .= "AND 	  o.orden_servicio_id = a.orden_servicio_id ";
      $sql .= "AND 	  p.plan_id = o.plan_id ";
      $sql .= "ORDER BY a.resultado_id DESC ";
			
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			while (!$rst->EOF)
			{
				$datos[]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->close();
      return $datos;
    }
    /**
    *
    */
    function ConsultaNombreUsuario($usuario_id)
    {
    	$sql  = "SELECT usuario,";
      $sql .= "       nombre ";
      $sql .= "FROM   system_usuarios ";
      $sql .= "WHERE  usuario_id = ".$usuario_id." ";

      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if(!$rst->EOF)
			{
				$datos=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->close();
      return $datos;
    }
    /**
    *
    */
    function ConsultaExamenesMaquinas($resultado)
    {
    	$sql  = "SELECT * ";
      $sql .= "FROM   interface_datalab_resultados ";
      $sql .= "WHERE  numero_orden_id = ".$resultado." ";

    	$datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			while (!$rst->EOF)
			{
				$datos[]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->close();
      return $datos;
    }
    /**
    *
    */
    function ConsultaFirmaMedico($usuario_id)
    {
      $sql  = "SELECT	a.nombre,";
      $sql .= "				a.descripcion,";
      $sql .= "				b.tarjeta_profesional ";
      $sql .= "FROM		system_usuarios a, ";
      $sql .= " 			profesionales b, ";
      $sql .= "				tipos_profesionales c ";
      $sql .= "WHERE	a.usuario_id = ".$usuario_id." ";
      $sql .= "AND    a.usuario_id  = b.usuario_id  ";
      $sql .= "AND		b.tipo_profesional  =  c.tipo_profesional ";
          
    	$datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if (!$rst->EOF)
			{
				$datos=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->close();
      return $datos;
    }
    /**
    *
    */
    function ObtenerInformacionAdicional($dts)
    {
      $datos = array();
      
      $sql  = "SELECT tipo_id_tercero,";
      $sql .= "       id, ";
      $sql .= "       razon_social ";
      $sql .= "FROM   empresas ";
      $sql .= "WHERE  empresa_id = '".$dts['empresa_id']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if (!$rst->EOF)
			{
				$datos=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      $rst->close();
      
      $sql  = "SELECT	paciente_id,";
			$sql .= " 			tipo_id_paciente,";
			$sql .= " 			primer_apellido,";
			$sql .= " 			segundo_apellido,";
			$sql .= " 			primer_nombre,";
			$sql .= " 			segundo_nombre,";
			$sql .= " 			fecha_nacimiento ";
			$sql .= "FROM		pacientes ";
			$sql .= "WHERE 	tipo_id_paciente = '".$dts['tipo_id_paciente']."' ";
			$sql .= "AND		paciente_id = '".$dts['paciente_id']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if (!$rst->EOF)
			{
				$datos = array_merge($datos,$rst->GetRowAssoc($ToUpper = false));
				$rst->MoveNext();
			}
      $rst->close();
      
      return $datos;
    }
  }
?>