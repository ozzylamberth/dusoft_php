<?php

  /**
  * $Id: funciones_datalab.inc.php,v 1.7 2009/04/21 22:18:38 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * Funciones para el manejo de equivalencias de datalab
  */
  //funciones de equivalencia para datalab
  function Get_Tarifa($numero_orden_id)
  {
  		list($dbconn) = GetDBconn();
  		$query="select tarifario_id
  		from os_maestro_cargos where numero_orden_id = ".$numero_orden_id."";
  		$result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  				$this->error = "Error en la consulta del Tarifario";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  		}
  		if (!$result->EOF)
  		{
  			list($tarifario_id)=$result->FetchRow();
  			$result->Close();
  		}else{
          return "ERROR";
  		}

      $query="SELECT equivalencia FROM
  		interface_datalab_tarifario WHERE tarifario_id = '".$tarifario_id."'";
      $result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  				$this->error = "Error en la consulta de la equivalencia del Tarifario";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  		}
  		if (!$result->EOF)
  		{
  			list($equivalencia)=$result->FetchRow();
  			$result->Close();
  		}
      return $equivalencia;
  }

  function Get_Pagador($numero_orden_id)
  {//esta funcion cuadrarla para que devuelva
  //elm plan y la descripcion del plan y e inserta orden1, y orden1char.
  		list($dbconn) = GetDBconn();
  		$query="select c.plan_id, c.plan_descripcion, c.num_contrato
  		from os_maestro a, os_ordenes_servicios b, planes c
  		where a.numero_orden_id = ".$numero_orden_id." and
  		a.orden_servicio_id = b.orden_servicio_id
  		and b.plan_id = c.plan_id";
  		$result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  				$this->error = "Error en la consulta del Pagador";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  		}
  		/*if (!$result->EOF)
  		{
  			list($plan_id, $num_contrato)=$result->FetchRow();
  			$result->Close();
  		}else{
          return "ERROR";
  		}

      $query="SELECT equivalencia FROM
  		interface_datalab_pagador  WHERE
  		plan_id = ".$plan_id."
  		and num_contrato = '".$num_contrato."'";
      $result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  				$this->error = "Error en la consulta de la equivalencia del Pagador";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  		}
  		if (!$result->EOF)
  		{
  			list($equivalencia)=$result->FetchRow();
  			$result->Close();
  		}
      return $equivalencia;*/
  		if (!$result->EOF)
  		{
  					$datos=$result->GetRowAssoc($ToUpper = false);
  					$result->Close();
  		}
  		return $datos;
  }

  function Get_Servicio($numero_orden_id)
  {
  		list($dbconn) = GetDBconn();
  		$query="select  c.servicio, 
                      c.descripcion,
                      i.equivalencia
              from    os_maestro a, 
                      os_ordenes_servicios b, 
                      servicios c, 	
                      interface_datalab_servicio i
              where		a.numero_orden_id = ".$numero_orden_id." 
              and     a.orden_servicio_id = b.orden_servicio_id
              and     b.servicio = c.servicio
              and     c.servicio = i.servicio ";
  		$result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  				$this->error = "Error en la consulta del servicio";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  		}/*
  		if (!$result->EOF)
  		{
  			list($servicio)=$result->FetchRow();
  			$result->Close();
  		}else{
          return "ERROR";
  		}
      $query="SELECT equivalencia FROM
  		interface_datalab_servicio  WHERE
  		servicio = ".$servicio."";
      $result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  				$this->error = "Error en la consulta de la equivalencia del servicio";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  		}
  		if (!$result->EOF)
  		{
  			list($equivalencia)=$result->FetchRow();
  			$result->Close();
  		}
      return $equivalencia;*/
  		if (!$result->EOF)
  		{
        $datos=$result->GetRowAssoc($ToUpper = false);
        $result->Close();
  		}
  		return $datos;
  }

  /*
  function Get_Medico($usuario_id)
  {
  		list($dbconn) = GetDBconn();
      $query="SELECT equivalencia FROM
  		interface_datalab_medico  WHERE
  		usuario_id = ".$usuario_id."";
      $result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  				$this->error = "Error en la consulta de la equivalencia del medico";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  		}
  		if (!$result->EOF)
  		{
  			list($equivalencia)=$result->FetchRow();
  			$result->Close();
  		}
      return $equivalencia;
  }*/

  function Get_Datos_Paciente($tipo_id_paciente, $paciente_id)
  {
  		list($dbconn) = GetDBconn();
      $query="SELECT  b.historia_numero, 
                      b.historia_prefijo,
                  		a.paciente_id, a.tipo_id_paciente,
                  		btrim(a.primer_nombre||' '||a.segundo_nombre, '') as nombres,
                  		btrim(a.primer_apellido||' '||a.segundo_apellido, '') as apellidos,
                  		a.fecha_nacimiento, 
                      a.residencia_telefono, 
                      a.sexo_id,
                      a.residencia_direccion,
                      a.zona_residencia
          		FROM    pacientes a 
                      LEFT JOIN historias_clinicas b 
                      ON (a.paciente_id = b.paciente_id AND 
                          a.tipo_id_paciente = b.tipo_id_paciente )
          		WHERE   a.tipo_id_paciente = '".$tipo_id_paciente."'
          		AND     a.paciente_id = '".$paciente_id."' ";

      $result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  				{
  						$this->error = "Error en la consulta de lecturas profesionales";
  						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  						return false;
  				}
  		if (!$result->EOF)
  		{
  					$datos=$result->GetRowAssoc($ToUpper = false);
  					$result->Close();
  		}
  		return $datos;
  }

  function Get_Sexo($sexo_id)
  {
  		list($dbconn) = GetDBconn();
      $query="SELECT equivalencia FROM
  		interface_datalab_sexo  WHERE
  		sexo_id = '".$sexo_id."'";
      $result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  				$this->error = "Error en la consulta de la equivalencia del medico";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  		}
  		if (!$result->EOF)
  		{
  			list($equivalencia)=$result->FetchRow();
  			$result->Close();
  		}
      return $equivalencia;
  }

  function Get_Cama($numero_orden_id)
  {
  		list($dbconn) = GetDBconn();
  		$query="select c.cama from os_maestro a, ingresos_departamento b,
  		movimientos_habitacion c where a.numero_orden_id = ".$numero_orden_id." and
  		a.numerodecuenta = b.numerodecuenta and
  		b.ingreso_dpto_id = c.ingreso_dpto_id and c.fecha_egreso is NULL ";
  		$result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  				$this->error = "Error en la consulta del Pagador";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  		}
  		if (!$result->EOF)
  		{
  			list($cama)=$result->FetchRow();
  			$result->Close();
  		}
      return $equivalencia;
  }

  function Get_Datos_Solicitud($numero_orden_id)
  {
  //g.usuario_id es el medico de la evolucion que hizo la solicitud
  //i.nombre es el nombre del medico de la evolucion que hizo la solicitud
  //e.profesional es el nombre del medico que hizo la solicitud manualmente
  		list($dbconn) = GetDBconn();
  		$query="select i.nombre_tercero as nombre, g.usuario_id, f.*, e.profesional, b.cargo, c.observacion
  		FROM os_maestro a left join hc_os_solicitudes_manuales_datos_adicionales f on
  		(a.orden_servicio_id = f.orden_servicio_id),
  		hc_os_solicitudes b left join hc_os_solicitudes_manuales e on
  		(b.hc_os_solicitud_id = e.hc_os_solicitud_id) left join hc_evoluciones g on
  		(b.evolucion_id = g.evolucion_id) left join profesionales_usuarios h on
  		(g.usuario_id = h.usuario_id) left join terceros i on
  		(h.tipo_tercero_id = i.tipo_id_tercero and h.tercero_id = i.tercero_id),
  		hc_os_solicitudes_apoyod c
  		where a.numero_orden_id = ".$numero_orden_id."
  		and a.hc_os_solicitud_id = b.hc_os_solicitud_id
  		and b.hc_os_solicitud_id = c.hc_os_solicitud_id";
  		$result = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  				{
  						$this->error = "Error en la consulta de lecturas profesionales";
  						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  						return false;
  				}
  		if (!$result->EOF)
  		{
  					$datos=$result->GetRowAssoc($ToUpper = false);
  					$result->Close();
  		}
  		return $datos;
  }
  //FUNCIONES DATALAB DESDE HISTORIA CLINICA Y LISTAS DE TRABAJO
  //CASOS PARA DATALAB
  /**
  * Funcion donde se consulta el detalle de los examnes de datalab
  *
  * @param string $resultado_id Numero del resultado del examen
  * @param string $numero_orden Numero de orden del resultado
  * @param string $sw_origen Parametro de la funcion original
  *
  * @return mixed
  */
  function ConsultaExamenesMaquinas($resultado_id, $numero_orden_id,$sw_origen)
  {
  	list($dbcon) = GetDBconn();
    
    $sql  = "SELECT  nombre_examen, ";
    $sql .= "        resultado,  ";
    $sql .= "        unidades,  ";
    $sql .= "        normal_minima, "; 
    $sql .= "        normal_maxima, ";
    $sql .= "        patologico,  ";
    $sql .= "        comentario,  ";
    $sql .= "        TO_CHAR(fecha_resultado,'DD/MM/YYYY') as fecha_resultado, ";
    $sql .= "        TO_CHAR(fecha_resultado,'HH:MI') AS hora_resultado ";
    $sql .= "FROM    interface_datalab_resultados  ";
    $sql .= "WHERE   numero_orden_id = ".$numero_orden_id."  "; 
    $sql .= "AND     resultado_id = ".$resultado_id." ";
  			
    $rst = $dbcon->Execute($sql);
    if ($dbcon->ErrorNo() != 0)
    {
      $this->error = "Error al consultar los resultados de los examenes";
      $this->mensajeDeError = "Error DB : " . $dbcon->ErrorMsg();
      return false;
    }
    
    $retorno = array();
    
    while (!$rst->EOF)
    {
      $retorno[]=$rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    
    $rst->Close();
    return $retorno;
  }
  /**
  *
  */
  function Get_CodigoDatalab($cups)
  {
    $sql  = "SELECT codigo_datalab ";
    $sql .= "FROM   interface_datalab_codigos ";
    $sql .= "WHERE  codigo_cups = '".$cups."' ";
    
    list($dbconn) = GetDBconn();
    $rst = $dbconn->Execute($sql);
    
    if ($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al consultar los resultados de los examenes";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    
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
  function Get_DatalabTiposIdentificacion($tipo_id)
  {
    $sql  = "SELECT equivalencia ";
    $sql .= "FROM   interface_datalab_tipos_id ";
    $sql .= "WHERE  tipo_id_paciente = '".$tipo_id."' ";
    
    list($dbconn) = GetDBconn();
    $rst = $dbconn->Execute($sql);
    
    if ($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al consultar los resultados de los examenes";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    
    $datos = array();
    if(!$rst->EOF)
    {
      $datos = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $datos['equivalencia'];
  }
  /**
  *
  */
  function Get_DatalabZonas($zona)
  {
    $sql  = "SELECT equivalencia ";
    $sql .= "FROM   interface_datalab_zonas ";
    $sql .= "WHERE  zona_residencia = '".$zona."' ";
    
    list($dbconn) = GetDBconn();
    $rst = $dbconn->Execute($sql);
    
    if ($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al consultar los resultados de los examenes";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    
    $datos = array();
    if(!$rst->EOF)
    {
      $datos = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $datos['equivalencia'];
  }
  /**
  * Funcion para obtener la identificacion del profesional
  *
  * @param integer $usuario Identificador del usuario
  *
  * @return mixed
  */
  function Get_IdentificacionProfesional($usuario)
  {
    if($usuario == '')  return 1;
    
    $sql  = "SELECT tercero_id ";
    $sql .= "FROM   profesionales_usuarios ";
    $sql .= "WHERE  usuario_id = ".$usuario." ";
    
    list($dbconn) = GetDBconn();
    $rst = $dbconn->Execute($sql);
    
    $datos = array();
    if(!$rst->EOF)
    {
      $datos = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $datos['tercero_id'];
  }
  /**
  * Funcion donde se obtiene el valor del cargo
  *
  * @param integer $numero_orden_id Numero de orden del cargo
  * @param string $cargo Identificador del cargo
  *
  * @return mixed
  */
  function Get_ValorCargo($numero_orden_id,$cargo)
  {
  	$sql  = "SELECT CD.precio ";
    $sql .= "FROM   os_maestro OM,";
    $sql .= "       os_maestro_cargos OC, ";
    $sql .= "       cuentas_detalle CD ";
    $sql .= "WHERE  OM.numero_orden_id = ".$numero_orden_id." ";
    $sql .= "AND    OM.numero_orden_id = OC.numero_orden_id ";
    $sql .= "AND    OC.transaccion = CD.transaccion ";
    //$sql .= "AND    OC.cargo = CD.cargo ";
    $sql .= "AND    OM.cargo_cups = '".$cargo."' ";
    
  	list($dbconn) = GetDBconn();
  	$rst = $dbconn->Execute($sql);
  	
    if ($dbconn->ErrorNo() != 0)
  	{
  		$error = "Error en la consulta de lecturas profesionales";
  		echo $mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() ." <br> ".$sql;
  		return false;
  	}
  	
    if (!$rst->EOF)
  	{
  		$datos = $rst->GetRowAssoc($ToUpper = false);
  		$rst->Close();
  	}
  	return $datos;
  }
  
?>