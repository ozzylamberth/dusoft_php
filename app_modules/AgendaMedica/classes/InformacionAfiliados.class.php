<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: InformacionAfiliados.class.php,v 1.14 2010/03/11 21:17:43 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique 
  */
  /**
  * Clase : InformacionAfiliados
  * Clase encargada de hacer las consultas
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.14 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class InformacionAfiliados extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function InformacionAfiliados(){}
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
      $sql .= "       TO_CHAR(AD.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "       AF.plan_atencion,";
      $sql .= " 	    AF.tipo_afiliado_atencion,";
      $sql .= " 	    AF.rango_afiliado_atencion, ";
      $sql .= " 	    PL.plan_descripcion ";
      $sql .= "FROM   eps_afiliados_datos AD,";
      $sql .= "       eps_afiliados AF, ";
      $sql .= "       planes PL ";
      $sql .= "WHERE  AD.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
      $sql .= "AND    AD.afiliado_id = '".$datos['paciente_id']."' ";
      $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AF.estado_afiliado_id IN ('AC') ";
      $sql .= "AND    AF.plan_atencion = PL.plan_id ";

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
		*
		*/
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
    
    /**
		* Obtiene la informacion de tipos de afiliados
		*/
		function ObtenerTiposAfiliados($plan)
		{
			$sql  = "SELECT DISTINCT TA.tipo_afiliado_nombre,";
			$sql .= "				TA.tipo_afiliado_id ";
			$sql .= "FROM		tipos_afiliado TA,";
			$sql .= "				planes_rangos PR ";
			$sql .= "WHERE 	PR.plan_id = ".$plan." ";
			$sql .= "AND		PR.tipo_afiliado_id = TA.tipo_afiliado_id ";
			
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
    
    /**
    * Obtiene la informacion de un tiempo de la cita
    */
    function TiempoCita($request)
    {
      $sql  = " SELECT tiempo_cita ";
      $sql .= " FROM   tiempocitaxplanes "; 
      $sql .= " WHERE  tipo_afiliado_id='".$request['tipoafiliado']."' "; 
      $sql .= " AND    plan_id=".$request['Responsable']." ";
      $sql .= " AND    rango='".$request['rango']."' ";
        
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        if(!$resultado->EOF)
        {
            $vars=$resultado->GetRowAssoc($toUpper=false);
            $resultado->MoveNext();
        }        
        $resultado->Close();    
        return $vars;
    }
    
    /**
    * Obtiene la informacion de la fecha de turno y numero de citas
    */
    function FechaTurno($plan,$paciente_id,$tipo_id_paciente)
    {
       $sql .= "SELECT MAX(a.fecha_turno) AS fecha_turno "; 
       $sql .= "FROM   agenda_turnos a, ";
       $sql .= "       agenda_citas b, ";
       $sql .= "       agenda_citas_asignadas c ";       
       $sql .= "WHERE  b.agenda_cita_id=c.agenda_cita_id "; 
       $sql .= "AND    a.agenda_turno_id=b.agenda_turno_id ";       
       $sql .= "AND    c.plan_id=".$plan."  ";
       $sql .= "AND    c.paciente_id='".$paciente_id."' ";       
       $sql .= "AND    c.tipo_id_paciente='".$tipo_id_paciente."' ";
       $sql .= "AND    c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion); ";
         
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
      if(!$resultado->EOF)
      {
        $vars=$resultado->GetRowAssoc($toUpper=false);
        $resultado->MoveNext();
      }        
      $resultado->Close();    
      return $vars;
    }
    
    /*
    * Obtiene la informacion de prioridad de las citas
    *
    * @param integer $paciente_id Identificador del id del paciente, $tipo_id_paciente el tipo de id
    *
    * @return array
    */
    function PrioridadCitas($paciente_id,$tipo_id_paciente)
    {
      //$this->debug=true;
      $sql .= "SELECT count(c.paciente_id) AS NumeroCitas, ";
      $sql .= "       MAX(a.fecha_turno) AS fecha_turno ";
      $sql .= "FROM   agenda_turnos a, ";
      $sql .= "       agenda_citas b, ";
      $sql .= "       agenda_citas_asignadas c ";
      $sql .= "WHERE  b.agenda_cita_id=c.agenda_cita_id "; 
      $sql .= "AND    a.agenda_turno_id=b.agenda_turno_id ";      
      $sql .= "AND    c.paciente_id='".$paciente_id."' ";       
      $sql .= "AND    c.tipo_id_paciente='".$tipo_id_paciente."' ";
      $sql .= "AND    c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion); ";
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
      if(!$resultado->EOF)
      {
        $vars=$resultado->GetRowAssoc($toUpper=false);
        $resultado->MoveNext();
      }        
      $resultado->Close();    
      return $vars;
    }
    
    function PrioridadCitasConsulta($paciente_id,$tipo_id_paciente, $tipo_consulta_id)
    {
      //$this->debug=true;
      $sql .= "SELECT count(c.paciente_id) AS NumeroCitas, ";
      $sql .= "       MAX(a.fecha_turno) AS fecha_turno ";
      $sql .= "FROM   agenda_turnos a, ";
      $sql .= "       agenda_citas b, ";
      $sql .= "       agenda_citas_asignadas c, ";
      $sql .= "       os_cruce_citas OC, ";
      $sql .= "       os_maestro OS ";
      $sql .= "WHERE  b.agenda_cita_id=c.agenda_cita_id "; 
      $sql .= "AND    c.agenda_cita_asignada_id = OC.agenda_cita_asignada_id ";
      $sql .= "AND    OC.numero_orden_id = OS.numero_orden_id ";
      $sql .= "AND    OS.sw_estado IN ('3') ";
      $sql .= "AND    a.agenda_turno_id=b.agenda_turno_id ";
      $sql .= "AND    a.tipo_consulta_id=".$tipo_consulta_id." ";      
      $sql .= "AND    c.paciente_id='".$paciente_id."' ";       
      $sql .= "AND    c.tipo_id_paciente='".$tipo_id_paciente."' ";
      $sql .= "AND    c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion); ";
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
      if(!$resultado->EOF)
      {
        $vars=$resultado->GetRowAssoc($toUpper=false);
        $resultado->MoveNext();
      }        
      $resultado->Close();    
      return $vars;
    }
    
    /**
    * Obtiene la informacion de tiempo de los cargos
    */
    function Tiempocargos($tipo_consulta_id,$empresa_id)
    {
    
      //$this->debug=true;
      $sql .= "SELECT tiempo_cargo, ";
      $sql .= "       prioridad, ";
      $sql .= "       cargo_cups ";
      $sql .= "FROM   tiempoxcargo ";
      $sql .= "WHERE  tipo_consulta_id=".$tipo_consulta_id." ";
      $sql .= "AND    empresa_id='".$empresa_id."' ";
      $sql .= "ORDER BY prioridad ";
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
      
      while(!$resultado->EOF)
      {
        $vars[]=$resultado->GetRowAssoc($toUpper=false);
        $resultado->MoveNext();
      }        
      $resultado->Close();    
      return $vars;
    }
    
    function Datoscargo($tipo_consulta_id,$empresa_id, $tiempo)
    {
    
      //$this->debug=true;
      $sql .= "SELECT tiempo_cargo, ";
      $sql .= "       prioridad, ";
      $sql .= "       cargo_cups ";
      $sql .= "FROM   tiempoxcargo ";
      $sql .= "WHERE  tipo_consulta_id=".$tipo_consulta_id." ";
      $sql .= "AND    empresa_id='".$empresa_id."' ";
      $sql .= "AND    tiempo_cargo<= ".$tiempo." ";
      $sql .= "ORDER BY prioridad ";
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
      
      while(!$resultado->EOF)
      {
        $vars[]=$resultado->GetRowAssoc($toUpper=false);
        $resultado->MoveNext();
      }        
      $resultado->Close();    
      return $vars;
    }
    
     /**
    * Obtiene la informacion de tiempo de los cargos
    */
    function ExisteTiempo($tipo_consulta_id,$empresa_id)
    {
      //$this->debug=true;
      $sql .= "SELECT tipo_consulta_id ";
      $sql .= "FROM   tiempoxcargo ";
      $sql .= "WHERE  tipo_consulta_id=".$tipo_consulta_id." ";
      $sql .= "AND    empresa_id='".$empresa_id."' ";
      $sql .= "GROUP BY tipo_consulta_id ";
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        if(!$resultado->EOF)
        {
            $vars=$resultado->GetRowAssoc($toUpper=false);
            $resultado->MoveNext();
        }        
        $resultado->Close();    
        return $vars;
    }
    /**
    * Funcion para obtener la informacion del plan
    *
    * @param integer $plan Identificador del plan
    *
    * @return array
    */
    function ObtenerInformacionPlan($plan)
    {
       //$this->debug=true;
      $sql  = "SELECT plan_id,";
      $sql .= "       plan_descripcion,";
      $sql .= "       sw_afiliados, ";
      $sql .= "       sw_tipo_plan ";
      $sql .= "FROM   planes ";
      $sql .= "WHERE  plan_id = ".$plan." "; 
      $sql .= "ORDER BY plan_descripcion ";
      
      if(!$result = $this->ConexionBaseDatos($sql,__LINE__))
        return false;

      $datos = array();
      if (!$result->EOF) 
      {
        $datos = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
      $result->Close();
      return $datos;
    }
	
	
	/**
	* Funcion Para Obtener el plan de un Afiliado
	*
	*/
	function ObtenerPlanAfiliado($datos)
    {

        $sql  = "SELECT PL.plan_id,";
		$sql .= "       PLL.plan_descripcion,";
        $sql .= " 	    PL.rango, ";
        $sql .= " 	    TA.tipo_afiliado_id,";
        $sql .= " 	    TA.tipo_afiliado_nombre, ";
        $sql .= " 	    EP.eps_punto_atencion_id, ";
        $sql .= " 	    EP.eps_punto_atencion_nombre ";
        $sql .= "FROM   eps_afiliados AF ";
        $sql .= "LEFT JOIN eps_puntos_atencion EP ";
        $sql .= "ON (AF.eps_punto_atencion_id = EP.eps_punto_atencion_id), ";
        $sql .= "       planes_rangos PL INNER JOIN planes PLL ON PL.plan_id = PLL.plan_id, ";
        $sql .= "       tipos_afiliado TA ";
        $sql .= "WHERE  AF.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
        $sql .= "AND    AF.afiliado_id = '".$datos['paciente_id']."' ";
        //$sql .= "AND    AF.plan_atencion = '".$datos['plan_id']."' ";
        $sql .= "AND    AF.estado_afiliado_id IN ('AC') ";
        $sql .= "AND    AF.plan_atencion = PL.plan_id ";
        $sql .= "AND    AF.tipo_afiliado_atencion = PL.tipo_afiliado_id ";
        $sql .= "AND    AF.rango_afiliado_atencion = PL.rango ";
        $sql .= "AND    TA.tipo_afiliado_id = PL.tipo_afiliado_id ";
      
      
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
	* Funcion Para Obtener el plan ultima cita
	*
	*/
	function ObtenerPlanAfiliadoUltimaCita($datos)
    {

        $sql  = "SELECT pa.paciente_id,cu.fecha_registro,cu.ingreso, plan.plan_descripcion,plan.plan_id ";
		$sql .= "FROM ";
		$sql .= "pacientes pa ";
		$sql .= "JOIN agenda_citas_asignadas agca ON (pa.paciente_id = agca.paciente_id ) ";
		$sql .= "JOIN os_cruce_citas occ ON (agca.agenda_cita_asignada_id = occ.agenda_cita_asignada_id) ";
		$sql .= "JOIN os_maestro omae ON (occ.numero_orden_id = omae.numero_orden_id) ";
		$sql .= "JOIN cuentas cu ON (omae.numerodecuenta = cu.numerodecuenta) ";
		$sql .= "JOIN planes AS plan ON(cu.plan_id=plan.plan_id) ";
		$sql .= "WHERE ";
		$sql .= "pa.paciente_id like '%".$datos['paciente_id']."%' AND pa.tipo_id_paciente like '%".$datos['tipo_id_paciente']."%'  order by cu.fecha_registro DESC";  
      
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
    * Funcion donde se obtienen los datos del plan de afiliacion
    * para un afiliado determinado
    *
    * @param array $datos Arreglo de datos con la informacion del afiliado
    *
    * @return mixed
    */
    function ObtenerDatosPlanAfiliado($datos)
    {
      $sql  = "SELECT * ";
      $sql .= "FROM   planes ";
      $sql .= "WHERE  plan_id = ".$datos['plan_id']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $plan = array();
      if(!$rst->EOF)
      {
        $plan = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      $plan['sw_afilidos_siis'] = '1';
      
      if($plan['sw_afilidos_siis'] == '1')
      {
        $sql  = "SELECT PL.plan_id,";
        $sql .= " 	    PL.rango, ";
        $sql .= " 	    TA.tipo_afiliado_id,";
        $sql .= " 	    TA.tipo_afiliado_nombre, ";
        $sql .= " 	    EP.eps_punto_atencion_id, ";
        $sql .= " 	    EP.eps_punto_atencion_nombre ";
        $sql .= "FROM   eps_afiliados AF ";
        $sql .= "LEFT JOIN eps_puntos_atencion EP ";
        $sql .= "ON (AF.eps_punto_atencion_id = EP.eps_punto_atencion_id), ";
        $sql .= "       planes_rangos PL, ";
        $sql .= "       tipos_afiliado TA ";
        $sql .= "WHERE  AF.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
        $sql .= "AND    AF.afiliado_id = '".$datos['paciente_id']."' ";
        $sql .= "AND    AF.plan_atencion = '".$datos['plan_id']."' ";
        $sql .= "AND    AF.estado_afiliado_id IN ('AC') ";
        $sql .= "AND    AF.plan_atencion = PL.plan_id ";
        $sql .= "AND    AF.tipo_afiliado_atencion = PL.tipo_afiliado_id ";
        $sql .= "AND    AF.rango_afiliado_atencion = PL.rango ";
        $sql .= "AND    TA.tipo_afiliado_id = PL.tipo_afiliado_id ";
      }
      else
      {
        $sql  = "SELECT PL.plan_id,";
        $sql .= " 	    PL.rango, ";
        $sql .= " 	    TA.tipo_afiliado_id,";
        $sql .= " 	    TA.tipo_afiliado_nombre ";
        $sql .= "FROM   planes_rangos PL, ";
        $sql .= "       tipos_afiliado TA ";
        $sql .= "WHERE  PL.plan_id = ".$datos['plan_id']." ";
        $sql .= "AND    TA.tipo_afiliado_id = PL.tipo_afiliado_id ";
      }
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
    * Funcion donde se obtiene el tipo de plan
    *
    * @param integer $plan Identificador del plan
    *
    * @return mixed
    */
    function ObtenerTipoPlan($plan)
    {
      $sql  = "SELECT sw_tipo_plan ";
      $sql .= "FROM   planes ";
      $sql .= "WHERE  estado = '1' ";
      $sql .= "AND    plan_id = ".$plan." ";
      $sql .= "AND    fecha_final >= now() ";
      $sql .= "AND    fecha_inicio <= now() ";

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
    * Funcion para calcular si la cita se asigno a futuro
    * y no antes de la fecha actual.
    **/
    function ObtenerFecha($turno)
    {
	$sql="select fecha_turno as fecha, hora
	from agenda_citas as a,agenda_turnos as b
	where agenda_cita_id=$turno and a.agenda_turno_id=b.agenda_turno_id;";
	
	if(!$rst = $this->ConexionBaseDatos($sql)) return false;

	$datos = array();
	if(!$rst->EOF)
	{
		$datos[] = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
	}
      	$rst->Close();
	//CAMBIO DAR

	if(date($datos[0]['fecha'])==date('Y-m-d')){
		if($datos[0]['hora']<= date('H:i')){
				return false;
		}
	}

	
	return true;
    }
    /**
    * Funcion donde se obtiene el tipo de plan
    *
    * @return mixed
    */
    function ObtenerUltimaCita($tipo_consulta_id, $tipo_id_paciente, $paciente_id)
    {
      
      $sql  = "SELECT   MAX(ATR.fecha_turno||' '||AC.hora) AS Cita, ";
      $sql .= "         PR.nombre, ";
      $sql .= "         CU.descripcion ";
      $sql .= "FROM     agenda_turnos ATR, ";
      $sql .= "         agenda_citas  AC, ";
      $sql .= "         agenda_citas_asignadas ACA, ";
      $sql .= "         os_cruce_citas OC, ";
      $sql .= "         os_maestro OS, ";
      $sql .= "         profesionales PR, ";
      $sql .= "         cups          CU ";
      $sql .= "WHERE    ATR.tipo_consulta_id = ".$tipo_consulta_id." ";
      $sql .= "AND      ATR.tipo_id_profesional = PR.tipo_id_tercero ";
      $sql .= "AND      ATR.profesional_id = PR.tercero_id ";
      $sql .= "AND      ATR.agenda_turno_id = AC.agenda_turno_id ";
      $sql .= "AND      AC.sw_estado NOT IN ('3') ";
      $sql .= "AND      AC.agenda_cita_id = ACA.agenda_cita_id ";
      $sql .= "AND      ACA.agenda_cita_asignada_id = OC.agenda_cita_asignada_id ";
      $sql .= "AND      OC.numero_orden_id = OS.numero_orden_id ";
      $sql .= "AND      OS.sw_estado IN ('3') ";
      $sql .= "AND      ACA.cargo_cita = CU.cargo ";
      $sql .= "AND      ACA.tipo_id_paciente = '".$tipo_id_paciente."' ";
      $sql .= "AND      ACA.paciente_id = '".$paciente_id."' ";
      $sql .= "GROUP BY 2,3 ";
      $sql .= "ORDER BY Cita DESC";
      
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
	/**
	* Funcion que retorna true si la agenda es exclusiva
	* de lo controario false.
	**/
	function ObtenerValidacionTurno($turno,$plan)
	{
	  $sql  = "select sw_generica,at.agenda_turno_id ";
	  $sql .= "from agenda_citas ac inner join  agenda_turnos at on ac.agenda_turno_id = at.agenda_turno_id ";
	  $sql .= "where agenda_cita_id = ".$turno." ";
	  
	  if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      $datos = array();
      if(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
		
	  if(empty($datos))
		return false;
		
	 if($datos[0]['sw_generica'] == 1 )
		return false;
	
	  $sql  = "select * ";
	  $sql .= "from planes pl inner join programas_agenda_turnos pat on pl.programa_consulta_externa_id =  pat.programa_consulta_externa_id ";
	  $sql .= "where plan_id = ".$plan." and agenda_turno_id = ".$datos[0]['agenda_turno_id']." ";
	  $agenda_turno_id = $datos[0]['agenda_turno_id'];
	  if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      $datos = array();
      if(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

	  if(empty($datos))
	  {
		$sql  = "select * ";
		$sql .= "from programas_agenda_turnos pat inner join programas_consulta_externa pce on pat.programa_consulta_externa_id =pce.programa_consulta_externa_id ";
		$sql .= "where agenda_turno_id = ".$agenda_turno_id;
		
		if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		$datos = array();
		if(!$rst->EOF)
		{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		return $datos[0]['descripcion'];
	  }
	  
      $rst->Close();
      return false;
    }

	//Nuevo
   function ObtenerRangoPlanPaciente($datos)
    { 
      $sql  = "SELECT os.tipo_afiliado_id, pa.paciente_id, os.rango ";
      $sql .= "FROM   pacientes pa, os_ordenes_servicios os ";
      $sql .= "WHERE  pa.paciente_id = os.paciente_id AND pa.paciente_id='".$datos['paciente_id']."' ";

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
  }
?>