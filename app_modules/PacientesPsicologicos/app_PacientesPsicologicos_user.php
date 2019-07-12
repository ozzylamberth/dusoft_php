<?php

/**
 * $Id: app_Notas_y_Monitoreo_user.php,v 1.10 2005/11/22 15:02:50 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos para realizar las autorizaciones.
 */

class app_PacientesPsicologicos_user extends classModulo
{

    var $limit;
    var $conteo;

     function app_PacientesPsicologicos_user()
     {
          $this->limit=GetLimitBrowser();
          return true;
     }

     /**
     *
     */
     function main()
     {
          unset($_SESSION['PacientesPsico']['EMPRESA']);
          unset($_SESSION['PacientesPsico']['EMPRESA_ID']);

          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          $query = "SELECT DISTINCT C.empresa_id, C.razon_social 
                    FROM system_usuarios_empresas AS A,
                         empresas AS C
                    WHERE A.usuario_id = ".UserGetUID()."
                    AND A.empresa_id = C.empresa_id
                    ORDER BY C.empresa_id;";                      
          
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al ejecutar el query de permisos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
          }
          while ($data = $resultado->FetchRow()) {
               $empresa[$data['razon_social']]= $data;
          }
     
          $url[0]='app';
          $url[1]='PacientesPsicologicos';
          $url[2]='user';
          $url[3]='Menu';
          $url[4]='PacPsico';
     
          $arreglo[0]='EMPRESA';
          $this->salida.= gui_theme_menu_acceso('CONSULTA PACIENTES DE PSICOLOGIA',$arreglo,$empresa,$url,ModuloGetURL('system','Menu'));
          return true;
     }


     function Menu()
     {
          if(empty($_SESSION['PacientePsico']['EMPRESA']))
          {
               $_SESSION['PacientePsico']['EMPRESA_ID']=$_REQUEST['PacPsico']['empresa_id'];
               $_SESSION['PacientePsico']['EMPRESA']=$_REQUEST['PacPsico']['razon_social'];
          }
          if(!$this->FormaInicial()){
               return false;
          }
          return true;
     }
     
     
     function LlamarFormaUpdateEncuesta()
     {
          $ingreso     = $_REQUEST['ingreso'];
          $evolucion   = $_REQUEST['evolucion'];
          $tipo_pac    = $_REQUEST['TipoId'];
          $paciente_id = $_REQUEST['PacienteId'];
          
          $this->frmFormaUpdateEncuesta($ingreso, $evolucion, $tipo_pac, $paciente_id);
          return true;
     }
     
     function InsertarDatos_Encuesta()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_encuesta_inicial_encuesta_id_seq');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar Secuencia";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          $conducta_id = $result->fields[0];
          
          //Validaciones
          if($_REQUEST['asistencia'] == '1')
          { $asistencia = '1'; }else{ $asistencia = '0'; }

          if($_REQUEST['avisos'] == '1')
          { $avisos = '1'; }else{ $avisos = '0'; }

          if($_REQUEST['suspension'] == '1')
          { $suspension = '1'; }else{ $suspension = '0'; }

          if($_REQUEST['compromiso'] == '1')
          { $compromiso = '1'; }else{ $compromiso = '0'; }
          
          //Insert
          $query = "INSERT INTO hc_psicologia_encuesta_inicial
          		VALUES (".$conducta_id.", ".$_REQUEST['Ingreso'].", ".$_REQUEST['Evolucion'].",
                    	   '".$_REQUEST['motivo']."', '".$_REQUEST['objetivo']."', '".$asistencia."',
                            '".$avisos."', '".$suspension."', '".$compromiso."', '".$_REQUEST['concepto']."',
                    	   '".$_REQUEST['otros']."', ".UserGetUID().", 'now()');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_concepto_personal";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

          $dbconn->CommitTrans();
          $dbconn->Close();
          $_SESSION['Ingreso_E'] = $_REQUEST['Ingreso'];
          $_SESSION['Evolucion_E'] = $_REQUEST['Evolucion'];
          $this->FrmViewEncuestaPaciente($_REQUEST['Ingreso'], $_REQUEST['Evolucion'], $_REQUEST['TipoId'], $_REQUEST['PacienteId']);
          return true;
	}
	
     /**
     * Get_DatosEncuesta
     * Metodo para obtener los datos de la encuesta inicial diligenciada por el paciente.
     *
     * @return array.
     * @access public
     */
     function Get_DatosEncuesta($ingreso, $evolucion)
     {
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          //Consulta de datos
          $query="SELECT * FROM hc_psicologia_encuesta_inicial
          	   WHERE ingreso = ".$ingreso."
                  AND evolucion_id = ".$evolucion.";";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_psicologia_encuesta_inicial";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          
          while(!$result->EOF)
          {
               $Vector = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          $dbconn->Close();
          return $Vector;
     }
     
     /**
     * Metodo para obtener los pacientes con procesos abiertos
     *
     * @return array
     * @access public
     */
     function GetPacientesPendienteEncuesta()
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;

          $query = "SELECT X.*, c.encuesta_id
                    FROM (
                              SELECT DISTINCT a.paciente_id, a.tipo_id_paciente, b.ingreso, b.evolucion_id, d.primer_nombre ||' '|| d.segundo_nombre ||' '|| d.primer_apellido ||' '|| d.segundo_apellido as nombre, d.fecha_nacimiento FROM hc_psicologia_sesiones AS a, 
                              hc_psicologia_sesiones_evolucion as b, 
                              pacientes as d 
                              WHERE a.estado = '1' 
                              AND a.evolucion_id = b.evolucion_id 
                              AND a.paciente_id = d.paciente_id 
                              AND a.tipo_id_paciente = d.tipo_id_paciente 
                    	) AS X
                    LEFT JOIN hc_psicologia_encuesta_inicial AS c ON (X.ingreso = c.ingreso);";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          
          while (!$resultado->EOF) {
               $vars[]=$resultado->GetRowAssoc($ToUpper = false);
               $resultado->MoveNext();
          }

          return $vars;
     }//fin del metodo
	//UserGetUID()

     
	/**
     * Metodo para obtener los pacientes con procesos abiertos
     *
     * @return array
     * @access public
     */
     function GetPacientesProcesoSeguimiento()
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;

          $query = "SELECT DISTINCT a.paciente_id, a.tipo_id_paciente,
          			  a.ingreso, a.evolucion_id, a.sesion_id,
                           d.primer_nombre ||' '|| d.segundo_nombre ||' '|| d.primer_apellido ||' '|| d.segundo_apellido as nombre,
                           d.fecha_nacimiento
          		FROM hc_psicologia_sesiones AS a,
                         pacientes as d
                    WHERE a.estado = '1'
                    AND a.paciente_id = d.paciente_id
                    AND a.tipo_id_paciente = d.tipo_id_paciente;";
     
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          
          while (!$resultado->EOF) {
               $vars[]=$resultado->GetRowAssoc($ToUpper = false);
               $resultado->MoveNext();
          }

          return $vars;
     }//fin del metodo
	
     
	/**
     * Metodo para obtener las evoluciones de los procesos de seguimiento.
     *
     * @param string $sesion_id
     * @return array
     * @access public
     */
     function GetEvolucionesProceso($sesion_id)
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;

          $query = "SELECT DISTINCT A.ingreso, A.evolucion_id
          		FROM hc_psicologia_sesiones_evolucion AS A,
                         hc_evoluciones AS B
                    WHERE A.sesion_id = ".$sesion_id."
                    AND A.evolucion_id = B.evolucion_id
                    AND B.estado = '0';";
     
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          
          while (!$resultado->EOF) {
               $vars[]=$resultado->GetRowAssoc($ToUpper = false);
               $resultado->MoveNext();
          }

          return $vars;
     }
     
     /**
     * Metodo para obtener los pacientes con procesos abiertos
     *
     * @param string $estacion_id
     * @return array
     * @access public
     */
     /*function GetPacientesPendienteCambio($estacion_id)
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;

          $query = "SELECT DISTINCT a.paciente_id, a.tipo_id_paciente,
          			  b.ingreso, b.evolucion_id, c.encuesta_id, 
                           d.primer_nombre ||' '|| d.segundo_nombre ||' '|| d.primer_apellido ||' '|| d.segundo_apellido as nombre,
                           d.fecha_nacimiento
          		FROM hc_psicologia_sesiones AS a,
                    	hc_psicologia_sesiones_evolucion as b
                         LEFT JOIN hc_psicologia_evaluacion_final AS c ON (b.ingreso = c.ingreso),
                         Tablehc_psicologia_evaluacion_final_cambios
                         pacientes as d
                    WHERE a.estado = '0'
                    AND a.ingreso = b.ingreso
                    AND a.evolucion_id = b.evolucion_id
                    AND a.paciente_id = d.paciente_id
                    AND a.tipo_id_paciente = d.tipo_id_paciente;";
     
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          
          while (!$result->EOF) {
               $vars[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }

          return $vars;
     }//fin del metodo*/
	//UserGetUID()

    
    /**
    * Metodo para obtener los contactos de un paciente ingresado
    *
    * @param string $ingreso
    * @return array
    * @access public
    */
    function &GetContactosPaciente($ingreso)
    {
        if(empty($ingreso)) return null;
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $query = "SELECT
                        C.nombre_completo,
                        C.telefono,
                        C.direccion,
                        T.descripcion AS parentesco

                  FROM  hc_contactos_paciente C,
                        tipos_parentescos T

                  WHERE C.ingreso = $ingreso
                        AND T.tipo_parentesco_id = C.tipo_parentesco_id";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al ejecutar la conexion";
            $this->mensajeDeError = "Ocurriï¿½un error al intentar seleccionar el contacto del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
            return false;
        }

        if($result->EOF) return null;
        $ContactosPaciente = $result->GetRows();
        $result->Close();
        return $ContactosPaciente;
    }
         
     /**
     * Metodo para obtener los datos de un paciente ingresado
     *
     * @param string $ingreso
     * @return array
     * @access public
     */
     function GetDatosPaciente($ingreso)
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;
     
          $query="SELECT a.ingreso, b.historia_numero, b.historia_prefijo,c.primer_apellido,
               c.segundo_apellido, c.primer_nombre, c.segundo_nombre, sexo_id, c.fecha_nacimiento,
               c.residencia_direccion, c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id,
               c.tipo_mpio_id, i.pais, j.departamento, h.municipio,e.tercero_id, e.tipo_tercero_id,
               g.nombre_tercero, e.plan_id, e.plan_descripcion, f.tipo_afiliado_nombre, c.paciente_id,
               c.tipo_id_paciente, a.estado, gestacion.estado as gestacion
               FROM ingresos as a, historias_clinicas as b
               left join gestacion on
               (b.paciente_id=gestacion.paciente_id and b.tipo_id_paciente=gestacion.tipo_id_paciente),
               pacientes as c
               left join tipo_mpios as h on (c.tipo_pais_id=h.tipo_pais_id and c.tipo_dpto_id=h.tipo_dpto_id and   c.tipo_mpio_id=h.tipo_mpio_id)
               left join tipo_pais as i on (c.tipo_pais_id=i.tipo_pais_id)
               left join tipo_dptos as j on (c.tipo_pais_id=j.tipo_pais_id and
               c.tipo_dpto_id=j.tipo_dpto_id),
               cuentas as d left join tipos_afiliado as f on (d.tipo_afiliado_id=f.tipo_afiliado_id),
               planes as e, terceros as g
               WHERE a.ingreso=".$ingreso." and a.tipo_id_paciente=b.tipo_id_paciente and
               a.paciente_id=b.paciente_id and a.tipo_id_paciente=c.tipo_id_paciente and
               a.paciente_id=c.paciente_id and d.ingreso=a.ingreso and d.plan_id=e.plan_id and
               e.tipo_tercero_id=g.tipo_id_tercero and e.tercero_id=g.tercero_id;";
     
               $result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    return false;
               }
               else {
                    if (!$result) {
                         $this->error = "Error al tratar de realizar la consulta.<br>";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    $paciente = $result->GetRowAssoc($ToUpper = false);
               }
               return $paciente;
     }

     
     function TiposIdPacientes()
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while (!$result->EOF) {
               $vars[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }

          $result->Close();
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
     

     function CallDesplegarInfo()
     {
     	$ingreso = $_REQUEST['ingreso'];
          $servicio_id = $_REQUEST['servicio_id'];
          $servicio = $_REQUEST['servicio'];
          if(!$this->DesplegarInfo($ingreso,$servicio_id,$servicio))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"DesplegarInfo\"";
               return false;
          }
          return true;
     }
     
     
     function Atenciones_X_Servicio_Totales($servicio_id)
     {          
     	GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          if(empty($servicio_id))
          {
	          $servicio_id = $_REQUEST['servicio_id'];
          }
          
          if(empty($_REQUEST['conteo']))
		{
			$query="SELECT count(*)
                       
                       FROM pacientes as b, ingresos a, hc_evoluciones c,
                            departamentos d, servicios e

                       WHERE d.empresa_id = '".$_SESSION['NYM']['EMPRESA_ID']."'
                       AND d.departamento=c.departamento
                       AND a.tipo_id_paciente=b.tipo_id_paciente
                       AND a.paciente_id=b.paciente_id
                       AND c.ingreso=a.ingreso
                       AND c.estado='0'
                       AND d.servicio=e.servicio
                       AND e.servicio='".$servicio_id."'
                       AND c.usuario_id = ".UserGetUID().";";

			$resulta = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
      		if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of']=0;
				$_REQUEST['paso1']=1;
			}
		}
          
          $sql = "SELECT DISTINCT btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id,e.descripcion,d.descripcion as desc,
                         a.ingreso,a.estado,a.fecha_ingreso,e.servicio,c.fecha_cierre, 
                         c.evolucion_id, c.fecha, e.servicio

                       FROM pacientes as b, ingresos a, hc_evoluciones c,
                            departamentos d, servicios e

                       WHERE d.empresa_id = '".$_SESSION['NYM']['EMPRESA_ID']."'
                       AND d.departamento=c.departamento
                       AND a.tipo_id_paciente=b.tipo_id_paciente
                       AND a.paciente_id=b.paciente_id
                       AND c.ingreso=a.ingreso
                       AND c.estado='0'
                       AND d.servicio=e.servicio
                       AND e.servicio='".$servicio_id."'
                       AND c.usuario_id = ".UserGetUID()."

				   ORDER BY c.evolucion_id DESC, c.fecha_cierre DESC
                       LIMIT ".$this->limit." OFFSET $Of";
                         
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          while ($data = $result->FetchRow())
          {
               $atenciones[]=$data;
          }
          
          if($this->conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
		     return false;
		}

          $result->Close();
          return $atenciones;     
     }
	
     function CallIngresarNota()
     {
     	$ingreso = $_REQUEST['ingreso'];
          $evolucion = $_REQUEST['evolucion'];
          $nombre = $_REQUEST['nombre'];
          
          if(empty($ingreso) AND empty($evolucion))
          {
               $ingreso = $_SESSION['INSERTAR']['AGENDAMEDICA']['INGRESO'];
               $evolucion = $_SESSION['INSERTAR']['AGENDAMEDICA']['EVOLUCION'];
               $nombre = $_SESSION['INSERTAR']['AGENDAMEDICA']['NOMBRE'];
          }
                    
          if(!$this->IngresarNota($ingreso,$evolucion,$nombre))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"IngresarNota\"";
               return false;
          }
          return true;
     }
     
     function InsertNotaMedica()
     {
     	list($dbconn)=GetDBconn();
          
          $nota_medica = $_REQUEST['nota_medica'];
          $ingreso = $_REQUEST['ingreso'];
          $evolucion = $_REQUEST['evolucion_id'];
          $nombre = $_REQUEST['nombre'];
          $Evolucion_Para_Modulo = $_REQUEST['Evolucion_Para_Modulo'];
          $monitorizar = $_REQUEST['monitorizar'];
                              
          if(empty($evolucion))
          {
          	$evolucion = 'NULL';
          }else{
          	$evolucion = $evolucion;
          }

          $query="INSERT INTO notas_medicas (ingreso,evolucion_id,
          							usuario_id,nota_medica,
                                             fecha_registro)
          					VALUES   (".$ingreso.",".$evolucion.",
                                   		".UserGetUID().",'$nota_medica',
                                             now());";
     	$resultado = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          if($monitorizar == '1')
          {
          	$evo = 'NULL';
	          $query2 ="INSERT INTO notas_hc_monitorizadas (ingreso,evolucion_id,
                                                           usuario_id,tipo_monitoreo)
                                                  VALUES  (".$ingreso.",".$evo.",
                                                           ".UserGetUID().",'1');";
          }elseif($monitorizar == '2')
          {
               $query2 ="INSERT INTO notas_hc_monitorizadas (ingreso,evolucion_id,
                                                           usuario_id,tipo_monitoreo)
                                                  VALUES  (".$ingreso.",".$Evolucion_Para_Modulo.",
                                                           ".UserGetUID().",'1');";
          }
     	$resultado = $dbconn->Execute($query2);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          if($_REQUEST['forma_volver'] == "Ok")
          {
			$this->Informacion_NotaAuditoria();
          }else{
          	$this->IngresarNota($ingreso,$evolucion,$nombre,$Evolucion_Para_Modulo);
          }
     	return true;
     }
     
     
	function Get_UltimasAtenciones_X_Servicios()
	{
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT(b.servicio),b.descripcion
                         FROM
                              servicios b, departamentos a
                         WHERE 
                              a.empresa_id= '".$_SESSION['NYM']['EMPRESA_ID']."'
                              AND a.servicio=b.servicio;";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
               
          while ($data = $result->FetchRow())
          {
               $servicio[]=$data;
          }
               
          foreach ($servicio as $k => $v)
          {
               $sql = "SELECT DISTINCT btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
						b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                        		b.tipo_id_paciente,b.paciente_id,e.descripcion,d.descripcion as desc,
						a.ingreso,a.estado,a.fecha_ingreso,e.servicio,c.fecha_cierre, 
                              c.evolucion_id, c.fecha, e.servicio

                       FROM pacientes as b, ingresos a, hc_evoluciones c,
                            departamentos d, servicios e

                       WHERE d.empresa_id = '".$_SESSION['NYM']['EMPRESA_ID']."'
                       AND d.departamento=c.departamento
                       AND a.tipo_id_paciente=b.tipo_id_paciente
                       AND a.paciente_id=b.paciente_id
                       AND c.ingreso=a.ingreso
                       AND c.estado='0'
                       AND d.servicio=e.servicio
                       AND e.servicio='".$v[0]."'
                       AND c.usuario_id = ".UserGetUID()."

				   ORDER BY c.evolucion_id DESC, c.fecha_cierre DESC
				   LIMIT 5 OFFSET 0;";
                         
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($sql);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               
               while ($data = $result->FetchRow())
               {
                    $atenciones[]=$data;
               }
          }
          $result->Close();
          return $atenciones;
	}
     
     function Get_MisHistorias_Monitoreadas()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query="SELECT btrim(A.primer_nombre||' '||A.segundo_nombre||' '
          			||A.primer_apellido||' '||A.segundo_apellido,'') as nombre,
                         B.*
     			FROM pacientes AS A, notas_hc_monitorizadas AS B, ingresos AS I
                    
                    WHERE B.usuario_id=".UserGetUID()."
                    AND B.ingreso=I.ingreso
                    AND I.paciente_id=A.paciente_id
                    AND I.tipo_id_paciente=A.tipo_id_paciente
                    AND B.tipo_monitoreo='1'

				ORDER BY B.hc_monitoreo_id DESC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          while ($data = $result->FetchRow())
          {
               $monitor[]=$data;
          }
          $result->Close();
          return $monitor;
     }
     
     function Desmonitorizar_Monitorizados()
     {
          list($dbconn) = GetDBconn();
          $monitor_id = $_REQUEST['hc_monitoreo_id'];
		$query="UPDATE notas_hc_monitorizadas
          	   SET tipo_monitoreo = '0'
                  WHERE hc_monitoreo_id = ".$monitor_id.";";
                  
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $this->FormaInicial();
          return true;
     }
     
     
     function Get_hc_modulos()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();		

		$query="SELECT * 
          	   FROM system_hc_modulos;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
				
          while ($data = $result->FetchRow()){
               $vars[] = $data;
          }
               
          $result->Close();
          return $vars;
     }
	
	
	
/**
	* La funcion tipo_id_paciente se encarga de obtener de la base de datos
	* los diferentes tipos de identificacion de los paciente.
	* @access public
	* @return array
	*/
	function tipo_id_paciente()
  	{
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else{
               if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
                    return false;
               }
                    while (!$result->EOF) {
                         $vars[$result->fields[0]]=$result->fields[1];
                         $result->MoveNext();
                    }
          }
          $result->Close();
          return $vars;
	}

     function Get_Servicios()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT(b.servicio),b.descripcion
                         FROM
                              departamentos a,
                              servicios b
                         WHERE 
                              a.empresa_id='".$_SESSION['NYM']['EMPRESA_ID']."'
                          AND a.servicio=b.servicio
                          AND b.sw_asistencial='1';";
		
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
               
          while (!$result->EOF) {
               $vars[$result->fields[0]]=$result->fields[1];
               $result->MoveNext();
          }
               
          $result->Close();
          return $vars;
	}

		
     /**
	* Realiza la busqueda según el plan,documento .. de los pacientes que
	* tienen ordenes de servicios pendientes
	* @access private
	* @return boolean
	*/
     function BuscarOrden()
	{
          /********descarga de objetos de la forma***********/
          $Buscar1=$_REQUEST['Busc'];
          $Buscar=$_REQUEST['Buscar'];
          $Busqueda=$_REQUEST['TipoBusqueda'];
          $TipoBuscar=$_REQUEST['TipoBuscar'];
          $arreglo=$_REQUEST['arreglo'];
          $TipoCuenta=$_REQUEST['TipoCuenta'];
          $NUM=$_REQUEST['Of'];
          
          if($Buscar)
          {   unset($_SESSION['SPY']);  }
          if(!$Busqueda)
          {$new=$TipoBuscar;}
          if(!$NUM)
          {   $NUM='0';   }
          foreach($_REQUEST as $v=>$v1)
          {
               if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
               {   $vec[$v]=$v1;   }
          }
          $_REQUEST['Of']=$NUM;
          if($Buscar1){
                    $this->FormaMetodoBuscar($Busqueda,$arr,$f);
                    return true;
          }
     
          /***********conexion a db***********/
          list($dbconn) = GetDBconn();
          unset($_SESSION['SPY']);
          
          /******descarga de variables******/
          $fechaIni=$_REQUEST['fechaini'];
          $fechaFin=$_REQUEST['fechafin'];
          $TipoId=$_REQUEST['TipoDocumento'];
          $PacienteId=$_REQUEST['Documento'];
          $evolucion=$_REQUEST['evo_oculto'];
          $ingreso=$_REQUEST['ing_oculto'];
          $cuenta=$_REQUEST['cuenta_oculto'];
          $pre_factura=$_REQUEST['pre_oculto'];
          $factura=$_REQUEST['fac_oculto'];     
          $servicio=$_REQUEST['servicio'];
         
          $nom = explode(" ",$_REQUEST['nombres']);
          
          /*********Armada de sqls********/
          if($TipoId <> -1){ $tipo=" AND b.tipo_id_paciente='$TipoId' ";}
          if($PacienteId){ $paciente="AND b.paciente_id LIKE('$PacienteId%')";}
          
          if(!empty($_REQUEST['nombres']))
          {
               $nombre=" AND (UPPER(b.primer_nombre) LIKE('".strtoupper($nom[0])."%') OR UPPER(b.segundo_nombre) LIKE('".strtoupper($nom[0])."%'))"; 
               if($nom[1] != "")
               {
                    $nombre.=" AND (UPPER(b.primer_apellido) LIKE('".strtoupper($nom[1])."%') OR UPPER(b.segundo_apellido) LIKE('".strtoupper($nom[1])."%'))";
               }
          }
          
          if($servicio <> -1){ $sql_serv=" AND e.servicio='$servicio' ";}
          
          if(!empty($evolucion))
          {$sql_evol="AND c.evolucion_id='$evolucion'";}

           if(!empty($ingreso))
          {$sql_ing="AND c.ingreso='$ingreso'";}
         
          if(!empty($cuenta))
          {$sql_cuenta="AND c.numerodecuenta='$cuenta'";}

          if(!empty($pre_factura) OR !empty($factura))
          { 	
          	if(empty($pre_factura) AND !empty($factura))
               {
               	$sql_factura="AND i.factura_fiscal='$factura' AND i.numerodecuenta=c.numerodecuenta";
               }elseif(!empty($pre_factura) AND empty($factura))
               {
               	$pre_factura = strtoupper($pre_factura);
               	$sql_factura="AND i.prefijo='$pre_factura' AND i.numerodecuenta=c.numerodecuenta";
               }elseif(!empty($pre_factura) AND !empty($factura))
               {
               	$pre_factura = strtoupper($pre_factura);
               	$sql_factura="AND i.factura_fiscal='$factura' AND i.prefijo='$pre_factura' AND i.numerodecuenta=c.numerodecuenta";
               }
          }
          
          if($fechaIni)
          {
               $fechaIni=$this->Change_Formatt_Date($fechaIni);
               //$sql_fi="AND date(a.fecha_ingreso)>= '$fechaIni' ";
							 $sql_fi="AND date(c.fecha)>= '$fechaIni' ";
          }
          if($fechaFin)
          {
               $fechaFin=$this->Change_Formatt_Date($fechaFin);
               //$sql_ff="AND date(a.fecha_ingreso)<= '$fechaFin' ";
							 $sql_ff="AND date(c.fecha)<= '$fechaFin' ";
          }

     
          $dat = $this->Buscar1($tipo,$paciente,$nombre,$sql_evol,$NUM,$sql_ing,$sql_cuenta,$sql_factura,$sql_serv,$sql_fi,$sql_ff);
					if(!empty($dat))
					{		//si encontro algo lo ejecuto con limit porque si no no hace falta
          		$datos=$this->Buscar1($tipo,$paciente,$nombre,$sql_evol,$NUM,$sql_ing,$sql_cuenta,$sql_factura,$sql_serv,$sql_fi,$sql_ff);
					}
          if($datos){
               $this->FormaMetodoBuscar($Busqueda='',$datos,$f=true);
               return true;
          }
          else{
               $this->uno=1;
               $this->frmError["MensajeError"]='LA BÚSQUEDA NO ARROJO RESULTADOS.';
               $this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
               return true;
          }
	}

	
	/****cambiamos la ubicacion de las fechas********/
	function Change_Formatt_Date($fecha)
	{
		$f=explode("-",$fecha);
		return $f[2]."-".$f[1]."-".$f[0];
	}
	
	

	/**
	* funcion buscar1 es la que se filtra por el tipo del paciente y la identificacion del
	* paciente.
	* @access private
	* @return array
	*/
	function Buscar1($tipo,$paciente,$nombre,$sql_evol,$NUM,$sql_ing,$sql_cuenta,$sql_factura,$sql_serv,$sql_fi,$sql_ff)
	{
          list($dbconn) = GetDBconn();
          $limit=$this->limit;
          list($dbconn) = GetDBconn();
          if(!empty($_SESSION['SPY']))
          {   $x=" LIMIT ".$this->limit." OFFSET $NUM";   }
          else
          {   $x='';   }

            if(!empty($sql_evol))
            {
                 $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e, cuentas x
     
                         WHERE
                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $tipo $paciente
                         AND a.ingreso=c.ingreso                         
                         AND c.estado='0'
                         AND c.usuario_id=".UserGetUID()."
                         AND d.departamento=c.departamento
                         AND d.servicio=e.servicio
                         $sql_fi
                         $sql_ff
                         $nombre
                         $sql_evol
                         ORDER BY a.ingreso ASC
                         $x";
            }elseif(!empty($sql_serv))
            {
               $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e
     
                         WHERE
                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $tipo $paciente
                         AND c.ingreso=a.ingreso
                         AND c.usuario_id=".UserGetUID()."                         
                         $sql_fi
                         $sql_ff
                         AND d.departamento=c.departamento
                         AND c.estado='0'
                         AND d.servicio=e.servicio
                         $sql_serv
                         $nombre
                         ORDER BY a.ingreso ASC
                         $x";
            }elseif(!empty($sql_ing))
            {
                 $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e
     
                         WHERE
                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $tipo $paciente
                         AND a.ingreso=c.ingreso
                         AND c.usuario_id=".UserGetUID()."                                                  
                         $sql_fi
                         $sql_ff
                         AND c.estado='0'
                         $sql_ing
                         AND d.departamento=c.departamento
                         AND d.servicio=e.servicio
                         $nombre
                         ORDER BY a.ingreso ASC
                         $x";
            }elseif(!empty($sql_cuenta))
            {
                 $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e
     
                         WHERE
                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $tipo $paciente
                         AND c.ingreso=a.ingreso
                         AND c.usuario_id=".UserGetUID()."                         
                         $sql_fi
                         $sql_ff
                         AND c.estado='0'
                         $sql_cuenta
                         AND d.departamento=c.departamento
                         AND d.servicio=e.servicio
                         $nombre
                         ORDER BY a.ingreso ASC
                         $x";
            }elseif(!empty($sql_factura))
            {
                 $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e,
                         fac_facturas_cuentas i
     
                         WHERE
                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $tipo $paciente
                         $sql_factura
                         AND c.ingreso=a.ingreso
                         $sql_fi
                         $sql_ff
                         AND c.estado='0'
                         AND c.usuario_id=".UserGetUID()."                         
                         AND d.departamento=c.departamento
                         AND d.servicio=e.servicio
                         $nombre
                         ORDER BY a.ingreso ASC
                         $x";
            }else
            {
               $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e
     
                         WHERE
                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $tipo $paciente
                         AND c.ingreso=a.ingreso
                         AND c.usuario_id=".UserGetUID()."
                         AND c.estado='0'
                         $sql_fi
                         $sql_ff
                         AND d.departamento=c.departamento
                         AND d.servicio=e.servicio
                         $nombre
                         ORDER BY a.ingreso ASC
                         $x";
             }

   		$result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "ERROR AL CONSULTAR POR EL TIPO Y LA IDENTIFICACIÓN DEL PACIENTE";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if(!empty($_SESSION['SPY']))
          {
               while(!$result->EOF)
               {
                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          else
          {
               $vars=$result->RecordCount();
               $_SESSION['SPY']=$vars;
          }
          $result->Close();
		return $vars;
	}
     
     function Get_NotasAuditoria_Asignadas()
     {
     			GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $sql = "SELECT DISTINCT btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
			               b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                        		b.tipo_id_paciente, b.paciente_id, c.evolucion_id as hc_evolucion,
                              d.nota_auditoria_id, d.ingreso, d.evolucion_id, d.fecha_registro,
                              d.sw_prioridad, d.nota,d.sw_responder

                       FROM pacientes as b, ingresos a, hc_evoluciones c 
                            LEFT JOIN notas_auditoria d ON (d.evolucion_id = c.evolucion_id OR d.evolucion_id IS NULL)

                       WHERE a.tipo_id_paciente = b.tipo_id_paciente
                       AND a.paciente_id = b.paciente_id
                       AND d.ingreso = c.ingreso
                       AND c.estado = '0'
                       AND c.ingreso = a.ingreso
                       AND c.usuario_id = ".UserGetUID()."
                       AND d.estado = '1'
                       --AND ((d.sw_privada = '0' AND d.sw_responder = '1') OR d.sw_privada = '1')
											 --AND sw_medico = '1'
											 AND (d.sw_responder = '1' OR d.sw_privada = '1' OR sw_medico = '1')

				   ORDER BY d.fecha_registro DESC, d.sw_prioridad DESC, c.evolucion_id DESC;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     
          while ($data = $result->FetchRow())
          {
               $Mis_notas[] = $data;
          }
          
          $result->Close();
          return $Mis_notas;
     }
     
	
     function GetInformacion_NotaAuditoria($nota_auditoria_id)
     {
					GLOBAL $ADODB_FETCH_MODE;
					list($dbconn) = GetDBconn();
					$sql = "SELECT A.*, B.descripcion AS descripcion_tipo_nota
									FROM notas_auditoria A, notas_auditoria_tipo B, notas_auditoria_tipos_seleccion C
									WHERE A.nota_auditoria_id = ".$nota_auditoria_id."
									AND C.nota_auditoria_id=A.nota_auditoria_id
									AND C.nota_auditoria_tipo_id=B.nota_auditoria_tipo_id;";						
					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$result = $dbconn->Execute($sql);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					
					if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
					}
			
					while ($data = $result->FetchRow())
					{
								$Info_notas[] = $data;
					}
					
					$result->Close();
					return $Info_notas;
     }
     
     function GetRespuesta_NotaAuditoria($nota_auditoria_id)
     {
     			GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		
          $sql = "SELECT * FROM notas_auditoria_respuestas
									WHERE nota_auditoria_id = ".$nota_auditoria_id."
									ORDER BY fecha_registro ASC;";
                  
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     
          while ($data = $result->FetchRow())
          {
               $Info_respuestas[] = $data;
          }
          
          $result->Close();
          return $Info_respuestas;
     }
     
     function InsertarRespuesta_NotaAuditoria()
     {
     	$nota_auditoria_id = $_REQUEST['nota_auditoria_id'];
          $respuesta = $_REQUEST['respuesta'];

          list($dbconn) = GetDBconn();
		
          $sql = "SELECT tipo_profesional 
          	   FROM profesionales WHERE usuario_id = ".UserGetUID().";";
                  
          $result = $dbconn->Execute($sql);
          
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     
          list ($tipo_profesional) = $result->FetchRow();
          
          if($tipo_profesional == '5' OR empty($tipo_profesional))
          {
          	$sw_tipo_usuario = '2';
          }else
          {
          	$sw_tipo_usuario = '1';
          }

          
          $queryInsert = "INSERT INTO notas_auditoria_respuestas (nota_auditoria_id,
          											 fecha_registro,
                                                                  usuario_id,
                                                                  respuesta,
                                                                  sw_tipo_usuario)
          									VALUES    (".$nota_auditoria_id.",
                                                       		 now(),
                                                                  ".UserGetUID().",
                                                                  '$respuesta',
                                                                  '$sw_tipo_usuario');";
		$result = $dbconn->Execute($queryInsert);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
              
          $this->frmError["MensajeError"]='DATOS INSERTADOS SATISFACTORIAMENTE.';
          $this->Informacion_NotaAuditoria();
          $result->Close();
          return true;
     
     }
     
     
	//******funcion q prepara la cadena para ejecutarse en el sql*******/
	/*****mediante la palabra reservada IN()********/
	function Prepar_Cadena($codigos)
	{
		$cadena="";
		$tok = strtok($codigos, ",");
		while ($tok) {
          if($tok)
          {
               $cadena="'".$tok."'".",";
          }
               $tok = strtok(",");
          }
          return $cadena."0";
 	}

     	
     function TraerUsuario($usuario)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query ="SELECT usuario, nombre
          		FROM system_usuarios
                    WHERE usuario_id = ".$usuario.";";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $data = $result->FetchRow();
          return $data;
     }
     
         
     function TraerEvolucion($ingreso)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query ="SELECT evolucion_id
          		FROM hc_evoluciones
                    WHERE ingreso = ".$ingreso.";";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while ($data = $result->FetchRow())
          {
          	$evolucion[] = $data;
          }
          return $evolucion;
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
		if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}

//------------------------------------------------------------------------------
}//fin clase user
?>

