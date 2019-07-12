<?php

/**
 * $Id: app_Notas_y_Monitoreo_user.php,v 1.10 2005/11/22 15:02:50 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos para realizar las autorizaciones.
 */

class app_ModuloRepPsicologia_user extends classModulo
{

	var $limit;
	var $conteo;
	
	function app_ModuloRepPsicologia_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
	
	/**
	*
	*/
	function main()
	{
		$this->Menu();
		return true;
	}
	
	
	function Menu()
	{
		if(!$this->FormaInicial())
		{
			return false;
		}
		return true;
	}
     
     
     
	/**
	* Funcion que busca en los profesionales especialistas psicologos
	* @return array
	*/
	function profesionalesPsicologos()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id, x.tipo_id_tercero,
					c.nombre_tercero as nombre, 
				p.usuario_id
			FROM  profesionales x,
				especialidades z,
				profesionales_especialidades l, 
				terceros c,
				profesionales_usuarios p
			WHERE (x.tipo_profesional = '1' OR x.tipo_profesional = '2') 
			AND x.tercero_id = l.tercero_id 
			AND x.tipo_id_tercero = l.tipo_id_tercero
			AND x.tercero_id = c.tercero_id 
			AND x.tipo_id_tercero = c.tipo_id_tercero
			AND x.tercero_id = p.tercero_id 
			AND x.tipo_id_tercero = p.tipo_tercero_id
			AND z.especialidad = '049'
			AND z.especialidad = l.especialidad
			ORDER BY c.nombre_tercero";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		return $vars;
	}
     
   function Llama_RepConsultaGestionPsicologos()
   {
		if((!empty($_REQUEST['feinictra']) AND empty($_REQUEST['fefinctra'])) OR (empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))){
               $this->frmError["feinictra"]=1;
               $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
               $this->RepGestionPsicologos();
               return true;
          }
          $this->RepConsultaGestionPsicologos($_REQUEST['responsable'],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);
          return true;
     }
     
     function ConsultaEstadisticaRendimientoProf($profesional_escojer,$feinictra,$fefinctra)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          $sql_centro = " AND dpto.centro_utilidad = '05'";

          $sql_unidad = " AND dpto.unidad_funcional = 'SA'";
          
          $sql_dpto = " AND dpto.departamento = '05SA02'";
          
          if($profesional_escojer != '-1'){
                    $sql_usuario = " AND y.usuario_id = ".$profesional_escojer."";
          }
          
          if(!empty($feinictra) AND !empty($fefinctra)){
               $feinictra = $this->FechaStamp($feinictra);
               $fefinctra = $this->FechaStamp($fefinctra);
               if(!empty($feinictra) AND !empty($fefinctra)){
                    $sql_fecha = " AND date(a.fecha_turno) >= '".$feinictra."' AND date(a.fecha_turno) <= '".$fefinctra."'";
               }
          }		
          
          //CAMBIO DAR
          $query = "SELECT a.tipo_id_profesional,a.profesional_id,count(c.agenda_cita_asignada_id) as asignadas, count(f.agenda_cita_asignada_id) as canceladas 
                    
                    FROM agenda_turnos a,agenda_citas b,
                         agenda_citas_asignadas c 
                    LEFT JOIN agenda_citas_asignadas_cancelacion as f 
                    ON(c.agenda_cita_asignada_id=f.agenda_cita_asignada_id),
                          tipos_consulta z,
                          departamentos dpto, userpermisos_reportpsicologia rep,
                          profesionales_usuarios as y						
                    
                    WHERE a.agenda_turno_id=b.agenda_turno_id 
                    $sql_fecha
                    AND c.cargo_cita IN ('890208','890308')
                    AND b.agenda_cita_id=c.agenda_cita_id
                    AND c.agenda_cita_id=c.agenda_cita_id_padre
                    AND a.tipo_id_profesional=y.tipo_tercero_id 
                    AND a.profesional_id=y.tercero_id
                    $sql_usuario
                    AND a.tipo_consulta_id=z.tipo_consulta_id
                    AND z.departamento=dpto.departamento
                    $sql_centro $sql_unidad $sql_dpto
                    AND dpto.empresa_id='01'
                    AND dpto.empresa_id=rep.empresa_id
                    AND dpto.centro_utilidad=rep.centro_utilidad
                    AND dpto.unidad_funcional=rep.unidad_funcional
                    AND dpto.departamento=rep.departamento
                    AND rep.usuario_id='".UserGetUID()."'
                    
                    GROUP BY a.tipo_id_profesional,a.profesional_id
                    ORDER BY a.tipo_id_profesional,a.profesional_id";
          
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if(!$result->EOF)
          {
               while(!$result->EOF)
               {
                    //------trae el nombre del tercero
                    $sql = "SELECT a.nombre_tercero, b.usuario_id 
                            FROM terceros as a, profesionales_usuarios as b
                            WHERE a.tipo_id_tercero='".$result->fields[0]."' AND a.tercero_id='".$result->fields[1]."'
                                  and a.tipo_id_tercero=b.tipo_tercero_id and a.tercero_id=b.tercero_id";
                    $resul = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }	
                    $nombre=$resul->fields[0];
                    $usuario=$resul->fields[1];
                    $resul->Close();					
                    //busca atendidas 0 evolucion cerras
                    $atendidas = $this->CitasAtendidasoAbiertas(0,$result->fields[0],$result->fields[1],$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto);
                    //busca abiertas 1 evolucion abiertas
                    //$abiertas = $this->CitasAtendidasoAbiertas(1,$result->fields[0],$result->fields[1],$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto);
                    $var[]=array('usuario'=>$usuario,'nombre'=>$nombre,'asignadas'=>$result->fields[2],'canceladas'=>$result->fields[3],'atendidas'=>$atendidas[total],'promedio'=>$atendidas[promedio],'abiertas'=>$abiertas[total]);
                    $result->MoveNext();
               }
               $result->Close();													
          }
          return $var;
	}	


     function CitasAtendidasoAbiertas($tipo,$tipo_profesional,$id_profesional,$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto)
     {		
     	//tipo 0 atendidad 1 abiertas
          $var='';
          //abiertas
          if($tipo==1)
          {
                    $x='';
                    $y= " AND f.estado='1'";
          }
          else
          {		//son atendidad
                    $x= ", (sum(f.fecha_cierre - f.fecha)/count(c.agenda_cita_asignada_id)) as promedio";
                    $y= " AND f.estado='0'";
          }
          list($dbconn) = GetDBconn();
          $query = "SELECT count(c.agenda_cita_asignada_id) as total $x 
                         FROM agenda_turnos a,
                         agenda_citas b,
                         agenda_citas_asignadas c,
                         os_cruce_citas d,
                         os_maestro e,
                         hc_evoluciones f,
                         tipos_consulta z,
                         departamentos dpto,
                         userpermisos_repconsultaexterna rep 
                         WHERE a.tipo_id_profesional='$tipo_profesional' 
                         AND a.profesional_id='$id_profesional'
                         $sql_fecha
                         AND a.tipo_consulta_id='49'
                         AND a.tipo_consulta_id=z.tipo_consulta_id 
                         AND z.departamento=dpto.departamento
                         $sql_centro $sql_unidad $sql_dpto
                         AND a.agenda_turno_id=b.agenda_turno_id 
                         AND a.sw_estado_cancelacion='0'
                         AND c.cargo_cita IN ('890208','890308') 
                         AND b.agenda_cita_id=c.agenda_cita_id 
                         AND b.agenda_cita_id=c.agenda_cita_id_padre 
                         AND (b.sw_estado='1' OR b.sw_estado='2') 
                         AND c.agenda_cita_asignada_id 
                         NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) 
                         AND c.agenda_cita_asignada_id=d.agenda_cita_asignada_id 
                         AND d.numero_orden_id=e.numero_orden_id 
                         AND e.numerodecuenta=f.numerodecuenta
                         AND dpto.empresa_id='01'
                         AND dpto.empresa_id=rep.empresa_id
                         AND dpto.centro_utilidad=rep.centro_utilidad
                         AND dpto.unidad_funcional=rep.unidad_funcional
                         AND dpto.departamento=rep.departamento
                         AND rep.usuario_id='".UserGetUID()."'
                    $y"; 
          $resul = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }	
          if(!$result->EOF)
          {
                    $var=$resul->GetRowAssoc($ToUpper = false);
                    $resul->Close();									
          }
          return $var;
     }

     function DiasLaboradosProfesional($feinictra,$fefinctra,$profesional_escojer)
     {
          list($dbconn) = GetDBconn();
          $feinictra = $this->FechaStamp($feinictra);
          $fefinctra = $this->FechaStamp($fefinctra);
          $query="SELECT count(*) as total
                    FROM
                              (SELECT date(con.fecha_turno)
                              FROM agenda_turnos con,profesionales_usuarios a
                              WHERE date(con.fecha_turno) BETWEEN '".$feinictra."' AND '".$fefinctra."' AND
                              con.tipo_id_profesional=a.tipo_tercero_id AND con.profesional_id=a.tercero_id AND
                              a.usuario_id='".$profesional_escojer."'
                              GROUP BY date(con.fecha_turno)
                              ) as diaslab";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
               return $result->fields[0];
          }
     }

     function Llama_RepConsultaGestionPacientes()
     {
          if((!empty($_REQUEST['feinictra']) AND empty($_REQUEST['fefinctra'])) OR (empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))){
               $this->frmError["feinictra"]=1;
               $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
               $this->RepGestionPacientes();
               return true;
          }
          if(empty($_REQUEST['tipo_id']) OR empty($_REQUEST['identificacion'])){
               $this->frmError["MensajeError"]="DEBE LLENAR LOS DATOS DE IDENTIFICACION DEL PACIENTE.";
               $this->RepGestionPacientes();
               return true;
          }

          $this->RepConsultaGestionPacientes($_REQUEST['responsable'],$_REQUEST['feinictra'],$_REQUEST['fefinctra'],$_REQUEST['tipo_id'],$_REQUEST['identificacion']);
          return true;
     }
     
     function ConsultaEstadisticaRendimientoPac($profesional_escojer,$feinictra,$fefinctra,$tipo_paciente,$paciente)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          $sql_centro = " AND dpto.centro_utilidad = '05'";

          $sql_unidad = " AND dpto.unidad_funcional = 'SA'";
          
          $sql_dpto = " AND dpto.departamento = '05SA02'";
          
          if($profesional_escojer != '-1'){
                    $sql_usuario = " AND y.usuario_id = ".$profesional_escojer."";
          }
          
          if($tipo_paciente != '-1' AND !empty($paciente)){
                    $sql_pac = " AND c.paciente_id = '".$paciente."' AND c.tipo_id_paciente = '".$tipo_paciente."'";
          }
          
          if(!empty($feinictra) AND !empty($fefinctra)){
               $feinictra = $this->FechaStamp($feinictra);
               $fefinctra = $this->FechaStamp($fefinctra);
               if(!empty($feinictra) AND !empty($fefinctra)){
                    $sql_fecha = " AND date(a.fecha_turno) >= '".$feinictra."' AND date(a.fecha_turno) <= '".$fefinctra."'";
               }
          }		
          
          //CAMBIO DAR
          $query = "SELECT a.tipo_id_profesional,a.profesional_id,count(c.agenda_cita_asignada_id) as asignadas, count(f.agenda_cita_asignada_id) as canceladas 
                    
                    FROM agenda_turnos a,agenda_citas b,
                         agenda_citas_asignadas c 
                    LEFT JOIN agenda_citas_asignadas_cancelacion as f 
                    ON(c.agenda_cita_asignada_id=f.agenda_cita_asignada_id),
                          tipos_consulta z,
                          departamentos dpto, userpermisos_reportpsicologia rep,
                          profesionales_usuarios as y						
                    
                    WHERE a.agenda_turno_id=b.agenda_turno_id 
                    $sql_fecha
                    AND c.cargo_cita IN ('890208','890308')
                    $sql_pac
                    AND b.agenda_cita_id=c.agenda_cita_id
                    AND c.agenda_cita_id=c.agenda_cita_id_padre
                    AND a.tipo_id_profesional=y.tipo_tercero_id 
                    AND a.profesional_id=y.tercero_id
                    $sql_usuario
                    AND a.tipo_consulta_id=z.tipo_consulta_id
                    AND z.departamento=dpto.departamento
                    $sql_centro $sql_unidad $sql_dpto
                    AND dpto.empresa_id='01'
                    AND dpto.empresa_id=rep.empresa_id
                    AND dpto.centro_utilidad=rep.centro_utilidad
                    AND dpto.unidad_funcional=rep.unidad_funcional
                    AND dpto.departamento=rep.departamento
                    AND rep.usuario_id='".UserGetUID()."'
                    
                    GROUP BY a.tipo_id_profesional,a.profesional_id
                    ORDER BY a.tipo_id_profesional,a.profesional_id";

          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if(!$result->EOF)
          {
               while(!$result->EOF)
               {
                    //------trae el nombre del tercero
                    $sql = "SELECT a.nombre_tercero, b.usuario_id 
                            FROM terceros as a, profesionales_usuarios as b
                            WHERE a.tipo_id_tercero='".$result->fields[0]."' AND a.tercero_id='".$result->fields[1]."'
                                  and a.tipo_id_tercero=b.tipo_tercero_id and a.tercero_id=b.tercero_id";
                    $resul = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }	
                    $nombre=$resul->fields[0];
                    $usuario=$resul->fields[1];
                    $resul->Close();					
                    //busca atendidas 0 evolucion cerras
                    $atendidas = $this->CitasAtendidasoAbiertasPac(0,$result->fields[0],$result->fields[1],$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto,$sql_pac);
                    //busca abiertas 1 evolucion abiertas
                    //$abiertas = $this->CitasAtendidasoAbiertas(1,$result->fields[0],$result->fields[1],$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto);
                    $var[]=array('usuario'=>$usuario,'nombre'=>$nombre,'asignadas'=>$result->fields[2],'canceladas'=>$result->fields[3],'atendidas'=>$atendidas[total],'promedio'=>$atendidas[promedio],'abiertas'=>$abiertas[total]);
                    $result->MoveNext();
               }
               $result->Close();													
          }
          return $var;
	}	

     function CitasAtendidasoAbiertasPac($tipo,$tipo_profesional,$id_profesional,$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto,$sql_pac)
     {		
     	//tipo 0 atendidad 1 abiertas
          $var='';
          //abiertas
          if($tipo==1)
          {
                    $x='';
                    $y= " AND f.estado='1'";
          }
          else
          {		//son atendidad
                    $x= ", (sum(f.fecha_cierre - f.fecha)/count(c.agenda_cita_asignada_id)) as promedio";
                    $y= " AND f.estado='0'";
          }
          list($dbconn) = GetDBconn();
          $query = "SELECT count(c.agenda_cita_asignada_id) as total $x 
                         FROM agenda_turnos a,
                         agenda_citas b,
                         agenda_citas_asignadas c,
                         os_cruce_citas d,
                         os_maestro e,
                         hc_evoluciones f,
                         tipos_consulta z,
                         departamentos dpto,
                         userpermisos_repconsultaexterna rep 
                         WHERE a.tipo_id_profesional='$tipo_profesional' 
                         AND a.profesional_id='$id_profesional'
                         $sql_fecha
                         AND a.tipo_consulta_id='49'
                         AND a.tipo_consulta_id=z.tipo_consulta_id 
                         AND z.departamento=dpto.departamento
                         $sql_centro $sql_unidad $sql_dpto
                         AND a.agenda_turno_id=b.agenda_turno_id 
                         AND a.sw_estado_cancelacion='0'
                         AND c.cargo_cita IN ('890208','890308')
                         $sql_pac 
                         AND b.agenda_cita_id=c.agenda_cita_id 
                         AND b.agenda_cita_id=c.agenda_cita_id_padre 
                         AND (b.sw_estado='1' OR b.sw_estado='2') 
                         AND c.agenda_cita_asignada_id 
                         NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) 
                         AND c.agenda_cita_asignada_id=d.agenda_cita_asignada_id 
                         AND d.numero_orden_id=e.numero_orden_id 
                         AND e.numerodecuenta=f.numerodecuenta
                         AND dpto.empresa_id='01'
                         AND dpto.empresa_id=rep.empresa_id
                         AND dpto.centro_utilidad=rep.centro_utilidad
                         AND dpto.unidad_funcional=rep.unidad_funcional
                         AND dpto.departamento=rep.departamento
                         AND rep.usuario_id='".UserGetUID()."'
                    $y"; 
          $resul = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }	
          if(!$result->EOF)
          {
                    $var=$resul->GetRowAssoc($ToUpper = false);
                    $resul->Close();									
          }
          return $var;
     }
	
     /**
     * Funcion que busca los tipos de identificacion de los pacientes.
     * @return array
     */
	function ConsultaTipos_ID()
     {
		list($dbconn) = GetDBconn();
          $query = "SELECT *
                    FROM tipos_id_pacientes;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
 		return $vars;
	}
     
	
     function FormateoFechaLocal($fecha)
	{
          if(!empty($fecha))
          {
               $f=explode(".",$fecha);
               $fecha_arreglo=explode(" ",$f[0]);
               $fecha_real=explode("-",$fecha_arreglo[0]);
               return strftime("%A, %d de %B de %Y",strtotime($fecha_arreglo[0]));
          }
          else
          {
               return "-----";
          }

          return true;
	}
     

     /*		FechaStamp
     *
     *		Convierte los datos en Fechas a partir de la Fecha Registro.
     *
     *		@Author Alexander Giraldo.
     *		@access Public
     *		@param integer => fecha_registro
     */

    	function FechaStamp($fecha)
	{
     	$fecha = explode ('/',$fecha);
          $fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
          return $fecha;
          
	}


//------------------------------------------------------------------------------
}//fin clase user
?>