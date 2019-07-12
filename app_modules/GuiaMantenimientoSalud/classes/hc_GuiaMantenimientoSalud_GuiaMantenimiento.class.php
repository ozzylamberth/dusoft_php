<?php
	
	class GuiaMantenimiento
	{
		

    /**
    * Codigo de error
    *
    * @var string
    * @access public
    */
    var $error;

    /**
    * Mensaje de error
    *
    * @var string
    * @access public
    */
    var $mensajeDeError;

    /**
    * Constructor de la clase
    */
		
		function GuiaMantenimiento(){}

    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }
		
		/**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                      por defecto es false
    * @return object $rst
    */
    function ConexionBaseDatos($sql,$asoc = false)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn)=GetDBConn();
        //$dbconn->debug=true;

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

        $rst = $dbconn->Execute($sql);

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0)
        {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        return $rst;
    }
		
		function GetActividades()
    {
        $sql  = "(SELECT tipoactividad, actividadid as id, descripcion, null as cargo ";
        $sql .= "FROM  mtosalud_actividad ";
				$sql .= "WHERE cargo IS NULL) ";
				$sql .= "UNION ";
				$sql .= "(SELECT a.tipoactividad, a.actividadid as id, b.descripcion, a.cargo ";
        $sql .= "FROM  mtosalud_actividad a, cups b ";
				$sql .= "WHERE a.cargo IS NOT NULL ";
				$sql .= "AND a.cargo=b.cargo)";
        				
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
            $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }
		
		function GetEtapas()
    {
				$sql  = "SELECT etapaid, descripcion, edadinicio, edadfin, tipoedad ";
        $sql .= "FROM  mtosalud_etapa ";				
				$sql .= "ORDER BY etapaid";				
        			
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
		}
		
		function GetParametrizacion()
	 	{
				$sql  = "SELECT etapaid,actividadid,edad ";
        $sql .= "FROM mtosalud_parametrizacion";								
        			
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
            $datos[$rst->fields[0]][$rst->fields[1]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
		}
		
		function IngresarDatosMtoSalud($datos,$evolucion,$ingreso,$pfj,$TipoPaciente,$PacienteId,$Plan)
		{				
			
			list($dbconn)=GetDBConn();
			$dbconn->BeginTrans();
			$hc_os_solicitud_id='';
			if($datos)
			{					
				$etapas = $this->GetEtapas();
				foreach($etapas as $etapaId=>$arrE)
				{				
					$arr = $datos[$pfj.'EtapaValor'.$etapaId];
					/*if($arr){
						echo $sql =" DELETE FROM mtosalud_parametrizacion_hc WHERE etapaid=$etapaId AND edad=".$datos['edadPac'.$pfj].";";					
						echo '<BR>';
						$rst = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
								$this->mensajeDeError = $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}
					}*/
					foreach($arr as $cont=>$vec)
					{
						$val=explode(',',$vec);
						if($val[2])
						{
							$sql = "SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";      	 			
							$rst = $dbconn->Execute($sql);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
									$this->mensajeDeError = $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
							$hc_os_solicitud_id=$rst->fields[0];
              $sql = "INSERT INTO hc_os_solicitudes
                                  (hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, paciente_id, tipo_id_paciente)
                      VALUES($hc_os_solicitud_id,".$evolucion.", '".$val[2]."', 
											'".ModuloGetVar('','','TipoSolicitudApoyod')."', ".$Plan.",
                      '".$PacienteId."', '".$TipoPaciente."')";
							
              $rst = $dbconn->Execute($sql);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
									$this->mensajeDeError = $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
              else
              {
								$sql = "SELECT b.apoyod_tipo_id FROM cups a, apoyod_tipos b WHERE a.cargo='".$val[2]."' AND a.grupo_tipo_cargo = b.apoyod_tipo_id";      	 											
								$rst = $dbconn->Execute($sql);
								if ($dbconn->ErrorNo() != 0)
								{
										$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
										$this->mensajeDeError = $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}
								$apoyod_tipo_id=$rst->fields[0];    
												 
												 
								$sql="INSERT INTO hc_os_solicitudes_apoyod (hc_os_solicitud_id, apoyod_tipo_id)
											VALUES ($hc_os_solicitud_id, '".$apoyod_tipo_id."');";
								
								$rst = $dbconn->Execute($sql);
								if ($dbconn->ErrorNo() != 0)
								{
										$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
										$this->mensajeDeError = $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}
							}	
						}
						if(!$hc_os_solicitud_id){$hc_os_solicitud_id='NULL';}
						$sql = "INSERT INTO mtosalud_parametrizacion_hc (etapaid,actividadid,edad,evolucion_id,ingreso,hc_os_solicitud_id)";
						$sql .= "VALUES($etapaId,".$val[0].",".$val[1].",$evolucion,$ingreso,$hc_os_solicitud_id);";						
						
						$rst = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
								$this->mensajeDeError = $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}						
					}					
				}
				$dbconn->CommitTrans();
				return true;				
			}	
	  } 
		
		function GetParametrizacionHC($TipoPaciente,$PacienteId)
	 	{
				$sql  = "SELECT a.etapaid,a.actividadid,a.edad ";
        $sql .= "FROM mtosalud_parametrizacion_hc a, ingresos b ";								
				$sql .= "WHERE a.ingreso=b.ingreso AND b.tipo_id_paciente='".$TipoPaciente."' ";								
				$sql .= "AND b.paciente_id='".$PacienteId."';";								
        			
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
            $datos[$rst->fields[0]][$rst->fields[1]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
		}
		
		function ConsultaResultados($TipoPaciente,$PacienteId)
		{
				$pfj=$this->frmPrefijo;
				list($dbconnect) = GetDBconn();
								
				$sql="SELECT a.etapaid,a.actividadid,a.edad,c.evolucion_id,h.cargo,f.sw_modo_resultado,f.resultado_id
				FROM mtosalud_parametrizacion_hc a, ingresos b, hc_evoluciones c,
				hc_apoyod_lecturas_profesionales d, hc_resultados_nosolicitados e,
				hc_resultados f,hc_apoyod_resultados_detalles g,apoyod_cargos h,
				mtosalud_actividad i
				
				WHERE a.hc_os_solicitud_id IS NOT NULL 
				AND	 a.ingreso=b.ingreso 
				AND b.tipo_id_paciente='".$TipoPaciente."'
				AND b.paciente_id='".$PacienteId."'
				AND a.evolucion_id=c.evolucion_id
				AND c.evolucion_id=d.evolucion_id
				AND d.resultado_id=e.resultado_id
				AND e.resultado_id=f.resultado_id
				AND f.tipo_id_paciente=b.tipo_id_paciente
				AND f.paciente_id=b.paciente_id
				AND f.resultado_id=g.resultado_id
				AND f.cargo=g.cargo
				AND g.cargo=h.cargo
				AND a.actividadid=i.actividadid
				AND i.cargo=h.cargo";																	
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
            $datos[$rst->fields[0]][$rst->fields[1]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
		}
		
}
?>