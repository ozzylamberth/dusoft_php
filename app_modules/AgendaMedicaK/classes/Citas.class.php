<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: Citas.class.php,v 1.8 2010/03/16 18:41:57 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**  
  * Clase : Citas
  * Clase encargada de hacer las consultas
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.8 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Citas extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function Citas(){}
    /**
    * Obtiene la informacion de los tipos de identificación
    *
    * @return array
    */
    function ObtenerTiposIdPaciente()
    {
      $sql  = "SELECT tipo_id_paciente, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipos_id_pacientes ";
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
    /**
		* Funcion donde se obtiene la lista de citas, segun los filtros
    *
    * @param array $filtros Arreglo de datos con los filtros
    *
    * @return mixed
		*/
		function ObtenerListadocitas($filtros)
		{
			$sql  = "SELECT AA.agenda_cita_asignada_id AS idcita,";
      $sql .= "       AA.plan_id,";
      $sql .= "       AA.usuario_id,";
      $sql .= "       AA.sw_anestesiologo,";
      $sql .= "       TO_CHAR(AU.fecha_turno,'DD/MM/YYYY') AS fecha_turno,";
      $sql .= "       AC.hora,";
      $sql .= "       AU.fecha_turno || ' ' || AC.hora AS fecha_cita,";
      $sql .= "       AU.empresa_id,";
      $sql .= "       AU.consultorio_id AS consultorio,";
      $sql .= "       OS.sw_estado,";
      $sql .= "       OS.cargo,";
      $sql .= "       OS.tarifario_id,";
      $sql .= "       OS.numero_orden_id,";
      $sql .= "       OS.os_maestro_cargos_id,";
      $sql .= "       OS.orden_servicio_id,";
      $sql .= "       OS.cargo_cups,";
      $sql .= "       OS.tipo_afiliado_id,";
      $sql .= "       OS.rango, ";
      $sql .= "       OS.semanas_cotizadas AS semanas,";
      $sql .= "       OS.cantidad,";
      $sql .= "       OS.autorizacion_ext,";
      $sql .= "       OS.autorizacion_int,";
      $sql .= "       TE.nombre_tercero AS profesional,";
      $sql .= "       DE.servicio,";
      $sql .= "       DE.descripcion AS departamento,";
      $sql .= "       UF.ubicacion AS departamentoubicacion,";
      $sql .= "       UF.text1 AS telefonocancelacion,";
      $sql .= "       TL.descripcion AS ubicacion,";
      $sql .= "       TS.descripcion AS tipoconsulta,";
      $sql .= "       PL.telefono_cancelacion_cita,";
      $sql .= "       PL.plan_descripcion AS responsable,";
      $sql .= "       PL.horas_cancelacion AS diascancelacion,";
      $sql .= "       PA.tipo_id_paciente||' '||PA.paciente_id AS identificacion,";
      $sql .= "       PA.primer_nombre ||' '|| PA.segundo_nombre ||' '|| PA.primer_apellido ||' '|| PA.segundo_apellido AS paciente ";
      $sql .= "FROM   agenda_citas_asignadas AA LEFT JOIN ";
      $sql .= "       ( ";
      $sql .= "         SELECT  F.sw_estado,";
      $sql .= "                 H.cargo,";
      $sql .= "                 H.tarifario_id,";
      $sql .= "                 F.numero_orden_id,";
      $sql .= "                 H.os_maestro_cargos_id,";
      $sql .= "                 J.orden_servicio_id,";
      $sql .= "                 J.tipo_afiliado_id,";
      $sql .= "                 J.rango, ";
      $sql .= "                 J.semanas_cotizadas,";
      $sql .= "                 J.autorizacion_ext,";
      $sql .= "                 J.autorizacion_int,";
      $sql .= "                 F.cargo_cups, ";
      $sql .= "                 F.cantidad, ";
      $sql .= "                 G.agenda_cita_asignada_id ";
      $sql .= "         FROM    os_cruce_citas G, ";
      $sql .= "                 os_maestro F, ";
      $sql .= "                 os_ordenes_servicios J, ";
      $sql .= "                 os_maestro_cargos H ";
      $sql .= "         WHERE   G.numero_orden_id = F.numero_orden_id ";
      $sql .= "         AND     F.orden_servicio_id = J.orden_servicio_id ";
      $sql .= "         AND     G.numero_orden_id = H.numero_orden_id "; 
      if($filtros['tipo_id_paciente'] != '-1')
        $sql .= "         AND    J.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
      
      if($filtros['paciente_id'])
        $sql .= "         AND    J.paciente_id = '".$filtros['paciente_id']."' ";

      $sql .= "       ) OS "; 
      $sql .= "       ON (AA.agenda_cita_asignada_id = OS.agenda_cita_asignada_id)"; 
      $sql .= "       LEFT JOIN agenda_citas_asignadas_cancelacion AN "; 
      $sql .= "       ON (AN.agenda_cita_asignada_id = AA.agenda_cita_asignada_id),"; 
      $sql .= "       agenda_citas AC, "; 
      $sql .= "       agenda_turnos AU "; 
      $sql .= "       LEFT JOIN tipos_consultorios TL "; 
      $sql .= "       ON(AU.consultorio_id = TL.tipo_consultorio),"; 
      $sql .= "       tipos_consulta TC,"; 
      $sql .= "       tipos_servicios_ambulatorios TS,"; 
      $sql .= "       departamentos DE,"; 
      $sql .= "       unidades_funcionales UF,"; 
      $sql .= "       terceros TE,"; 
      $sql .= "       planes PL,"; 
      $sql .= "       pacientes PA "; 
      $sql .= "WHERE  AU.tipo_consulta_id = TC.tipo_consulta_id "; 
      $sql .= "AND 		AA.agenda_cita_id = AC.agenda_cita_id  "; 
      $sql .= "AND    AC.agenda_turno_id = AU.agenda_turno_id "; 
      $sql .= "AND    AU.profesional_id = TE.tercero_id "; 
      $sql .= "AND    AU.tipo_id_profesional = TE.tipo_id_tercero  "; 
      $sql .= "AND    DE.departamento = TC.departamento "; 
      $sql .= "AND    DE.unidad_funcional = UF.unidad_funcional "; 
      $sql .= "AND    AA.plan_id = PL.plan_id "; 
      $sql .= "AND    PA.paciente_id = AA.paciente_id "; 
      $sql .= "AND    PA.tipo_id_paciente = AA.tipo_id_paciente ";
      $sql .= "AND    TC.tipo_consulta_id = TS.tipo_servicio_amb_id ";
      $sql .= "AND    AN.agenda_cita_asignada_id IS NULL ";
      
      if($filtros['tipo_id_paciente'] != '-1')
        $sql .= "AND    PA.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
      
      if($filtros['paciente_id'])
        $sql .= "AND    PA.paciente_id = '".$filtros['paciente_id']."' ";

      if($filtros['fecha_cita'])
        $sql .= "AND    AU.fecha_turno = '".$this->DividirFecha($filtros['fecha_cita'])."'::date  "; 
      else
        $sql .= "AND    AU.fecha_turno >= NOW()::date  "; 
      
      if($filtros['nombres'] != "" || $filtros['apellidos'] != "")
      {
        $ctl = new ClaseUtil();
        $sql .= " AND ".$ctl->FiltrarNombres($filtros['nombres'],$filtros['apellidos'],"PA")." ";
      }      
      
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A ",$filtros['offset']))
        return false;
      
      $sql .= "ORDER BY fecha_cita DESC ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
			
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
		* Funcion donde se obtiene el listado de cargos
    *
    * @param array $filtros Arreglo de datos con los filtros
    *
    * @return mixed
		*/
		function ObtenerListadoCargos($filtros)
		{
      //$this->debug = true;
			$sql  = "SELECT cargo,";
      $sql .= " 	    descripcion ";
      $sql .= "FROM   cups ";
      $sql .= "WHERE  sw_estado = '1' ";
      
      if($filtros['cargo'])
        $sql .= "AND   cargo = '".$filtros['cargo']."' ";
      
      if($filtros['descripcion'])
        $sql .= "AND    descripcion ILIKE '%".$filtros['descripcion']."%' ";

      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A ",$filtros['offset'],null,$filtros['num_reg']))
        return false;
      
      $sql .= "ORDER BY descripcion ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
			
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
  }
?>