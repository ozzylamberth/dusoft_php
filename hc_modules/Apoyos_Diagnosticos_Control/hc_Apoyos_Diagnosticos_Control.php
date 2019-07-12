<?
/**
* Submodulo de Apoyos Diagnosticos.
*
* Submodulo para manejar los apoyos diagnosticos, permite la captura de resultados de los examenes, 
* y la lectura por parte del profesional.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Apoyos_Diagnosticos_Control.php,v 1.1 2009/07/30 12:38:06 johanna Exp $
*/

class Apoyos_Diagnosticos_Control extends hc_classModules
{
		var $limit;
		var $conteo;

//ad*
function Apoyos_Diagnosticos_Control()
{
		$this->limit=GetLimitBrowser();
			return true;
}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

// 	function GetVersion()
// 	{
// 		$informacion=array(
// 		'version'=>'1',
// 		'subversion'=>'0',
// 		'revision'=>'0',
// 		'fecha'=>'',
// 		'autor'=>'CLAUDIA LILIANA ZUÑIGA CAÑON',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}

//ad*
function verificar($cadena)
{
		return isNaN($cadena);
}

/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

		function GetEstado()
		{
					return true;
		}


//ad*
    function GetConsulta()
    {
      /*$pfj=$this->frmPrefijo;
      $accion='accion'.$pfj;
      if(empty($_REQUEST[$accion]))
      {
          $this->frmConsulta_Apoyod_leyenda();
      }
      return $this->salida;*/
      return true;
    }
    /**
    * Esta metodo captura los datos de la impresión de la Historia Clinica.
    * @access private
    * @return text Datos HTML de la pantalla.
    */
		function GetReporte_Html()
		{
			return true;
      // $imprimir=$this->frmHistoria();
      // if($imprimir==false)
      // {
          // return true;
      // }
      // return $imprimir;
		}
//cor - clzc - ad  ojo validar true y false al retornar
function GetForma()
{
		$pfj=$this->frmPrefijo;    
		if(empty($_REQUEST['accion'.$pfj]))
		{
				$this->frmForma_Apoyod_leyenda();
		}
		else
		{
				if($_REQUEST['accion'.$pfj]=='consulta_resultados')
				{
						$this->Consulta_Resultados($_REQUEST['resultado_id'.$pfj], $_REQUEST['sw_modo_resultado'.$pfj]);
				}
				      
        if($_REQUEST['accion'.$pfj]=='capturar_resultados')//ok
        {
            $this->Capturar_Resultados($this->tipoidpaciente, $this->paciente, $_REQUEST['evolucion_id'.$pfj]);
        }
        
				if($_REQUEST['accion'.$pfj]=='insertarmanuales')
				{	                                        
            if($_REQUEST['posicion'.$pfj] OR $_REQUEST['posicion'.$pfj] == '0')
            {
                if($_REQUEST['opcion'.$pfj] == 'capturar_observacion')//ok
                {
                    $this->Constructor_Session_Apoyo_Mto($this->tipoidpaciente, $this->paciente);
                    $this->frmForma_Observacion_Prestador_Servicio($this->paciente, $this->tipoidpaciente, $_REQUEST['evolucion_id'.$pfj], $_REQUEST['posicion'.$pfj]);
                }
                elseif($_REQUEST['opcion'.$pfj] == 'cambio_tecnica')//ok
                {
                    $this->Capturar_Resultados($this->tipoidpaciente, $this->paciente, $_REQUEST['evolucion_id'.$pfj]);
                }
            }
            else
            {               
                if ($this->InsertarManuales($this->tipoidpaciente, $this->paciente, $_REQUEST['evolucion_id'.$pfj])==false)
						    { 
							     $this->Capturar_Resultados($this->tipoidpaciente, $this->paciente, $_REQUEST['evolucion_id'.$pfj]);
						    }
						    else
						    {
							     $this->frmForma_Apoyod_leyenda();
						    }
            }  
				}
                
        if($_REQUEST['accion'.$pfj]=='insertar_observacion_prestador_servicio')
        { 
            $_SESSION['APOYO'][$_REQUEST['tipo_id_paciente'.$pfj]][$_REQUEST['paciente_id'.$pfj]][$_REQUEST['indice'.$pfj]]['observacion'] = $_REQUEST['observacion'.$pfj];
            $this->Capturar_Resultados($this->tipoidpaciente, $this->paciente, $_REQUEST['evolucion_id'.$pfj]);
        }
        
				if($_REQUEST['accion'.$pfj]=='for')
				{
						$this->frmForma();
				}

				if($_REQUEST['accion'.$pfj]=='forma')
				{
            $this->Plantillas_Examenes();
				}

				if($_REQUEST['accion'.$pfj]=='plant_forma')
				{
			 		  $_SESSION['LISTA']['APOYO']['tecnica_id']=$_REQUEST['selector_multitecnica'.$pfj];
						$this->frmCrearFormaE();
			  }

				if($_REQUEST['accion'.$pfj]=='insertar')
				{
						if ($this->Insertar()==false)
						{
							$this->frmCrearFormaE();
						}
						else
						{
							$this->frmForma_Apoyod_leyenda();
						}
				}
				if($_REQUEST['accion'.$pfj]=='Buscar')
				{
						$vector= $this->Buscar();
						$this->frmForma($vector);
				}

				//casos para la nueva version
				if($_REQUEST['accion'.$pfj]=='lectura_resultados_grupo')
				{
					$this->Lectura_Resultados_Grupo($_REQUEST['evolucion_id'.$pfj]);
				}

				if($_REQUEST['accion'.$pfj]=='observacion_medico')
				{
					$this->frmForma_Observacion($_REQUEST['evolucion_id'.$pfj], $_REQUEST['resultado_id'.$pfj]);
				}

				if($_REQUEST['accion'.$pfj]=='Lectura_Resultados_Grupo')
				{
					$this->Lectura_Resultados_Grupo($_REQUEST['evolucion_id'.$pfj]);
				}

				if($_REQUEST['accion'.$pfj]=='insertar_lectura_examen')
				{
						if ($this->InsertarLecturaExamen($_REQUEST['evolucion_id'.$pfj], $_REQUEST['resultado_id'.$pfj])==false)
						{
							$this->frmForma_Observacion($_REQUEST['evolucion_id'.$pfj], $_REQUEST['resultado_id'.$pfj]);
						}
						else
						{
							$this->Lectura_Resultados_Grupo($_REQUEST['evolucion_id'.$pfj]);
						}
				}

				if($_REQUEST['accion'.$pfj]=='insertar_lectura_grupo')
				{
						if ($this->InsertarLecturaGrupo($_REQUEST['evolucion_id'.$pfj])==false)
						{
								$this->Lectura_Resultados_Grupo($_REQUEST['evolucion_id'.$pfj]);
						}
						else
						{
								$this->frmForma_Apoyod_leyenda();
						}
				}
		}
		return $this->salida;
}

//ad-jea*
function Buscar()
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sexo = $this->datosPaciente[sexo_id];
		if($_REQUEST['criterio1'.$pfj]==1)
		{
				$codigo=STRTOUPPER($_REQUEST['busqueda'.$pfj]);
				$busqueda="AND UPPER(titulo_examen) LIKE '%$codigo%'";
		}
		else if($_REQUEST['criterio1'.$pfj]==2)
		{
				$codigo=$_REQUEST['busqueda'.$pfj];
				$busqueda="AND cargo LIKE '%$codigo%'";
		}
		else
		{
				$codigo=STRTOUPPER($_REQUEST['busqueda'.$pfj]);
				$codigo1=$_REQUEST['busqueda'.$pfj];
				$busqueda="AND (titulo_examen LIKE '%$codigo%' OR cargo LIKE '%$codigo1%')";
		}
		if($_REQUEST['criterio2'.$pfj]<>1)
		{
				$codigo2=$_REQUEST['criterio2'.$pfj];
				$busqueda2="AND apoyod_tipo_id='".$codigo2."'";
		}
		else
		{
				$busqueda2='';
		}
		if(empty($_REQUEST['conteo'.$pfj]))
		{
				$query = "SELECT count(*)    FROM apoyod_cargos
									WHERE (sexo_id = '".$sexo."' OR sexo_id = '0')
									$busqueda    $busqueda2";

				$resulta = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error en la Busqueda";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
				$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
				$Of='0';
		}
		else
		{
				$Of=$_REQUEST['Of'.$pfj];
				if($Of > $this->conteo)
				{
						$Of=0;
						$_REQUEST['Of'.$pfj]=0;
						$_REQUEST['paso1'.$pfj]=1;
				}
		}
		$query = "SELECT cargo, titulo_examen, informacion
							FROM apoyod_cargos WHERE (sexo_id = '".$sexo."' OR sexo_id = '0')
							$busqueda    $busqueda2    ORDER BY cargo
							LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la Busqueda";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}

		while(!$resulta->EOF)
		{
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
		}
		if($this->conteo==='0')
		{
				$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
				return false;
		}

		/*    if(is_object($resulta))
				{
						$resulta->Close();
				}*/
		$resulta->Close();
		return $var;
}

//QUERY ALTERADO PARA LOS PNQ
//ad*funcion que pinta el listado de apoyos diagnosticos en la pantalla inicial
function Consulta_Apoyod_delMedico()
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		//query original y que funciona ok al 22 de julio/2004 OJO ALERTA ESTA TRAYENDO LO DE TODOS
		//LOS INGRESOS

		//query igual al anterior pero trayendo el codigo y nombre de la especialidad, viene null cuando
		//no es una interconsulta (x.descripcion as especialidad_nombre y z.especialidad),
		//ADEMAS SE LE AGREGO LA LINEA PARA QUE SOLO TRAIGA LOS APD Y LOS INT(AND (a.os_tipo_solicitud_id = 'APD' OR a.os_tipo_solicitud_id = 'INT))
		//Y LOS pnq ademas corregi para que el titulo no saliera vacio

		//PENDIENTE DEFINIR QUE EXAMEN MOSTRAR (CRITERIO DE VENCIMIENTO)
    
   			$query ="SELECT	A.evolucion_id,
                                   A.hc_os_solicitud_id,
                                   A.cargo,
                                   A.os_tipo_solicitud_id, 
                                   C.usuario_id, 
                                   C.departamento, 
                                   C.fecha, 
                                   B.numero_orden_id,
                                   CASE WHEN B.sw_estado IS NULL THEN '0' ELSE B.sw_estado END AS realizacion,
                                   D.descripcion AS titulo_examenes
                         
                         FROM 	hc_os_solicitudes A,
                                   os_maestro B, 
                                   hc_evoluciones C, 
                                   cups D
                         
                         WHERE	A.tipo_id_paciente='".$this->tipoidpaciente."' 
                                   AND A.paciente_id='".$this->paciente."'
                                   AND A.os_tipo_solicitud_id in('APD','PNQ') 
                                   AND A.hc_os_solicitud_id = B.hc_os_solicitud_id 
                                   AND B.sw_estado < 7
                                   AND A.evolucion_id = C.evolucion_id 
                                   AND A.cargo = D.cargo
                         ORDER BY A.evolucion_id, A.os_tipo_solicitud_id, A.hc_os_solicitud_id;";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de Apoyos Diagnosticos";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
						$fact[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
		}

		if ($fact)
		{
				$query = "select sw_ingreso_manual from hc_resultados_manuales_parametros
				where empresa_id = '".$this->empresa_id."'";
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
						$this->error = "Error en la consulta de Apoyos Diagnosticos";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						return false;
				}
				else
				{
						list($parametro_resultado_manual)=$result->FetchRow();
				}				
				unset($_SESSION['RESULTADOS_MANUALES']['sw_ingreso_manual']);
				$_SESSION['RESULTADOS_MANUALES']['sw_ingreso_manual'] = $parametro_resultado_manual;
		}
		$result->Close();
		return $fact;
}

function ConsultaSolicitudesManuales()
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
          
          global $ADODB_FETCH_MODE;

//  		echo $query = "
// 		select h.sw_modo_resultado, d.autorizacion_int, c.fecha, d.autorizacion_ext, a.hc_os_solicitud_id, a.cargo,
// 		z.especialidad, x.descripcion as especialidad_nombre, a.os_tipo_solicitud_id,
// 		h.resultado_id, k.informacion, case when j.sw_estado is null then '0' when
// 		j.sw_estado='' then '0' when j.sw_estado='1' then '2' when j.sw_estado='0'
// 		then '1' end as autorizado, case when e.sw_estado is null then '0' else
// 		e.sw_estado end as realizacion, case when f.resultado_id is null then '0'
// 		else f.resultado_id end as resultados_sistema, case when g.resultado_id is
// 		null then '0' else g.resultado_id    end as resultado_manual,
// 		e.numero_orden_id, h.fecha_realizado,
// 
// 		case when (k.titulo_examen = '' or k.titulo_examen ISNULL) then l.descripcion
// 		else k.titulo_examen end as titulo_examenes,
// 
// 		f.usuario_id_profesional
// 		FROM hc_os_solicitudes as a left join apoyod_cargos as k on (a.cargo = k.cargo)
// 		left join cups as l on (a.cargo=l.cargo) left join hc_os_autorizaciones as d on
// 		(a.hc_os_solicitud_id=d.hc_os_solicitud_id) left join autorizaciones as j on(d.autorizacion_int=j.autorizacion) left join os_maestro as e on
// 		(a.hc_os_solicitud_id=e.hc_os_solicitud_id) left join hc_resultados_sistema as
// 		f on(e.numero_orden_id=f.numero_orden_id) left join hc_resultados_manuales as
// 		g on(e.numero_orden_id=g.numero_orden_id) left join hc_resultados as h
// 		on ((h.tipo_id_paciente='".$this->tipoidpaciente."' and h.paciente_id='".$this->paciente."') and (f.resultado_id=h.resultado_id or g.resultado_id=h.resultado_id))
// 		left join hc_apoyod_resultados_detalles as i on (h.resultado_id=i.resultado_id)
// 
// 		left join hc_os_solicitudes_interconsultas as z on
// 		(a.hc_os_solicitud_id = z.hc_os_solicitud_id) left join especialidades as x
// 		on (z.especialidad = x.especialidad),
// 
// 		hc_os_solicitudes_manuales c WHERE a.hc_os_solicitud_id = c.hc_os_solicitud_id
// 		AND    c.tipo_id_paciente='".$this->tipoidpaciente."' and
// 		c.paciente_id='".$this->paciente."' and (k.validez is null or
// 		h.fecha_realizado+k.validez>now() or h.fecha_realizado is null) and
// 		(e.sw_estado='4')
// 
// 		AND (a.os_tipo_solicitud_id = 'APD' OR a.os_tipo_solicitud_id = 'INT'
// 		OR a.os_tipo_solicitud_id = 'PNQ')
// 
// 		order by a.os_tipo_solicitud_id, a.hc_os_solicitud_id";

          $query = "
          
               SELECT 	L.*,
                         CASE WHEN K.sw_estado IS NULL THEN '0' 
                              WHEN K.sw_estado=''  THEN '0' 
                              WHEN K.sw_estado='1' THEN '2' 
                              WHEN K.sw_estado='0' THEN '1' END AS autorizado,
                         J.autorizacion_int ,
                         J.autorizacion_ext,
                         N.especialidad, 
                         N.descripcion AS especialidad_nombre
               FROM
                    (
                    SELECT 	A.*,
                              B.informacion,
                              CASE WHEN F.resultado_id IS NULL THEN '0' 
                                   ELSE F.resultado_id END AS resultados_sistema, 
                              CASE WHEN G.resultado_id IS NULL THEN '0' 
                                   ELSE G.resultado_id END AS resultado_manual,
                              C.resultado_id,
                              C.fecha_realizado,
                              C.sw_modo_resultado,
                              F.usuario_id_profesional, 
                              CASE WHEN (B.titulo_examen = '' OR B.titulo_examen IS NULL) then H.descripcion 
                                   ELSE B.titulo_examen END AS titulo_examenes
                    FROM
                         (SELECT	A.hc_os_solicitud_id, 
                                   A.cargo, 
                                   A.os_tipo_solicitud_id,
                                   C.sw_estado as realizacion,
                                   A.tipo_id_paciente ,
                                   B.paciente_id,
                                   C.numero_orden_id,
                                   B.fecha
                         FROM		hc_os_solicitudes A,
                                   hc_os_solicitudes_manuales B,
                                   os_maestro C
                         WHERE	A.tipo_id_paciente='".$this->tipoidpaciente."'
                                   AND A.paciente_id='".$this->paciente."' 
                                   AND (A.os_tipo_solicitud_id = 'APD' OR A.os_tipo_solicitud_id = 'INT' OR A.os_tipo_solicitud_id = 'PNQ')           
                                   AND A.hc_os_solicitud_id = B.hc_os_solicitud_id 
                                   AND A.hc_os_solicitud_id = C.hc_os_solicitud_id
                                   AND C.sw_estado < '7'
                         ) A
                         LEFT JOIN hc_resultados_sistema AS F
                              ON(A.numero_orden_id=F.numero_orden_id) 
                         LEFT JOIN hc_resultados_manuales AS G
                              ON(A.numero_orden_id=G.numero_orden_id) 
                         LEFT JOIN hc_resultados AS C
                              ON ((A.tipo_id_paciente = C.tipo_id_paciente
                                   AND A.paciente_id = C.paciente_id) 
                                   AND (F.resultado_id = C.resultado_id 
                                        OR G.resultado_id = C.resultado_id)
                                   ),
                         apoyod_cargos B,
                         cups H
                    WHERE
                         A.cargo = B.cargo
                         AND B.cargo = H.cargo 
                         AND (B.validez IS NULL 
                              OR C.fecha_realizado + B.validez > now() 
                              OR C.fecha_realizado IS NULL) 
                    ) L
                    LEFT JOIN hc_os_autorizaciones J
                         ON (J.hc_os_solicitud_id =  L.hc_os_solicitud_id)
                    LEFT JOIN autorizaciones K
                         ON (J.autorizacion_int = K.autorizacion)
                    LEFT JOIN hc_os_solicitudes_interconsultas AS M 
                         ON (L.hc_os_solicitud_id = M.hc_os_solicitud_id) 
                    LEFT JOIN especialidades AS N ON (N.especialidad = M.especialidad)";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconnect->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta datos de examenes de solicitudes manuales";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          {
               while($data = $result->FetchRow())
               {
                    $fact[] = $data;
               }
          }
		$result->Close();
		return $fact;
	}


//ad*
function ConsultaNombreMedico($usuario_id_evolucion)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= " SELECT d.nombre_tercero, c.descripcion
							FROM profesionales_usuarios a, profesionales b, tipos_profesionales c,
							terceros d
							WHERE a.tipo_tercero_id = b.tipo_id_tercero AND
							a.tercero_id = b.tercero_id AND
							a.tipo_tercero_id = d.tipo_id_tercero AND
							a.tercero_id = d.tercero_id AND
							a.usuario_id = ".$usuario_id_evolucion." AND
							b.tipo_profesional = c.tipo_profesional";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al buscar el nombre del profesional";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
		$result->Close();
		return $a;
}


//ad*
function ConsultaObservaciones($resultado_id)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query =" SELECT a.resultado_id, a.evolucion_id, a.observacion_prof, d.nombre, e.descripcion
							FROM hc_apoyod_lecturas_profesionales as a, hc_evoluciones as b,
							profesionales_usuarios as c, profesionales d, tipos_profesionales e
							WHERE a.resultado_id = ".$resultado_id." AND a.evolucion_id = b.evolucion_id
							AND b.usuario_id = c.usuario_id AND c.tipo_tercero_id = d.tipo_id_tercero
							AND    c.tercero_id = d.tercero_id AND d.tipo_profesional = e.tipo_profesional";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al consultar las observaciones realizadas al Examen";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
		}
		$result->Close();
		return $vector;
}


//ad- esta fiuncion se llama en el programa como tal *
function ConsultaResultadosNoSolicitados()
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		$query = "
          SELECT 	b.sw_modo_resultado, 
          		b.cargo, 
                    b.fecha_realizado, b.resultado_id,
		c.titulo_examen, c.informacion, d.sw_prof, d.evolucion_id, e.fecha
		FROM hc_resultados_nosolicitados as a
		left join hc_resultados as b on (a.resultado_id = b.resultado_id)
		left join apoyod_cargos as c    on (b.cargo = c.cargo) left join
		hc_apoyod_lecturas_profesionales as d on (b.resultado_id = d.resultado_id)
		left join hc_evoluciones as e on (d.evolucion_id = e.evolucion_id)
		WHERE b.tipo_id_paciente = '".$this->tipoidpaciente."' AND
		b.paciente_id = '".$this->paciente."'";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de examenes no solicitados";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
		}
		$result->Close();
		return $vector;
}

//ad*  esta funcion inserta todos los resultados cuyo modo resultado es 3
//el cual corresponde a resultados no solicitados.
function Insertar()
{
		$pfj=$this->frmPrefijo;
		$k=0;
		$fecha= $_REQUEST['fecha_realizado'.$pfj];
		if($fecha =='')
		{
				$this->frmError['fecha_realizado'.$k.$pfj]=1;
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS, SELECCIONE UNA FECHA.";
				return false;
		}
		else
		{
				$cad=explode ('-',$fecha);
				$dia = $cad[0];
				$mes = $cad[1];
				$ano = $cad[2];
				$fecha=$cad[2].'-'.$cad[1].'-'.$cad[0];
				if (date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano)) > date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('Y'))))
				{
						$this->frmError['fecha_realizado'.$k.$pfj]=1;
						$this->frmError["MensajeError"]="FECHA INVALIDA, SELECCIONE UNA FECHA INFERIOR O IGUAL A LA ACTUAL .";
						return false;
				}
		}

		if (!$_REQUEST['items'.$k.$pfj])
		{
				$this->frmError["MensajeError"]="CAMPOS DE RESULTADO INEXISTENTES.";
				return false;
		}

		$subindice = $_REQUEST['items'.$k.$pfj];
		for ($i=0; $i< $subindice; $i++)
		{
			if ($_REQUEST['resultado'.$k.$i.$pfj] === '' or $_REQUEST['resultado'.$k.$i.$pfj] == -1)
			{
				$this->frmError['resultado'.$k.$i.$pfj]=1;
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS, DEBE ESCRIBIR UN RESULTADO.";
				return false;
			}
		}

		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

    //OBTENER EL TIPO DE RESULTADO - CAMBIO GENERADO PARA INSERTAR RESULTADOS DE PNQ
		$query= "SELECT c.apoyod_tipo_id as tipo_resultadoapd,
						d.grupo_tipo_cargo as tipo_resultadonqx
						FROM cups a, grupos_noqx_apoyod b left join apoyod_tipos c
						on (b.grupo_tipo_cargo = c.apoyod_tipo_id) left join
						no_qx_grupos_tipo_cargo d on (b.grupo_tipo_cargo = d.grupo_tipo_cargo)
						WHERE a.cargo = '".$_SESSION['LISTA']['APOYO']['cargo']."'
						AND a.grupo_tipo_cargo = b.grupo_tipo_cargo";
						
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar el tipo de resultado para el examen";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$tipo_resultado=$result->GetRowAssoc($ToUpper = false);
		
		if ($tipo_resultado)
		{
				if ($tipo_resultado[tipo_resultadoapd]!= NULL OR $tipo_resultado[tipo_resultadoapd]!= '')
				{
						$os_tipo_resultado = ModuloGetVar('','','TipoSolicitudApoyod');
				}
				else
				{
						if ($tipo_resultado[tipo_resultadonqx]!= NULL OR $tipo_resultado[tipo_resultadonqx]!= '')
						{
							$os_tipo_resultado = 'PNQ';
						}
						else
						{
							$this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO.";
							return false;
						}
				}
		}
		else
		{
				$this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO.";
				return false;
		}
		//FIN

		//realiza el id manual de la tabla
		$query="SELECT nextval('hc_resultados_resultado_id_seq')";
		$result=$dbconn->Execute($query);
		$resultado_id=$result->fields[0];
		//fin de la operacion

		$query="INSERT INTO hc_resultados (resultado_id,
		        cargo, tecnica_id,
						fecha_registro, usuario_id, tipo_id_paciente, paciente_id,
						fecha_realizado,
						os_tipo_resultado,
						observacion_prestacion_servicio, sw_modo_resultado)
						VALUES(".$resultado_id.",
						'".$_SESSION['LISTA']['APOYO']['cargo']."', ".$_SESSION['LISTA']['APOYO']['tecnica_id'].",
						now(), ".UserGetUID().", '".$_SESSION['LISTA']['APOYO']['tipo_id_paciente']."',
						'".$_SESSION['LISTA']['APOYO']['paciente_id']."',
						'".$fecha."', '".$os_tipo_resultado."', '".$_REQUEST['observacion'.$pfj]."', '3')";

		$resulta1=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
  			$this->error = "Error al insertar en hc_resultados";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	  		$dbconn->RollbackTrans();
				return false;
		}
		else
		{  //bloque pendiente despues de bogota
				$sw_prof = '1';
				$sw_prof_dpto = '0';
				$sw_prof_todos = '0';
				$query="INSERT INTO hc_apoyod_lecturas_profesionales
								(resultado_id, evolucion_id, sw_prof, sw_prof_dpto, sw_prof_todos,
								observacion_prof)
								VALUES  (".$resultado_id.", ".$this->evolucion.", '".$sw_prof."',
								'".$sw_prof_dpto."', '".$sw_prof_todos."', '".$_REQUEST['observaciones_medicas'.$pfj]."')";
				$resulta2=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al insertar en hc_apoyod_lecturas_profesionales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}//fin del bloque pendiente
				else
				{
						$query="INSERT INTO hc_resultados_nosolicitados
						(resultado_id, laboratorio, profesional)
						VALUES  (".$resultado_id.", '".$_REQUEST['laboratorio'.$pfj]."', '".$_REQUEST['profesional'.$pfj]."')";
						$resulta3=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al insertar en hc_resultados_nosolicitados";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}
						else
						{
								for ($i=0; $i< $subindice; $i++)
								{
										$lab_examen='lab_examen'.$k.$i.$pfj;
										$res='resultado'.$k.$i.$pfj;

										if ($_REQUEST['sw_patologico'.$k.$i.$pfj]!='')
										{
												$sw_alerta = '1';
										}
										else
										{
												if ((($_REQUEST['rmin'.$k.$i.$pfj]) != '') and (($_REQUEST['rmax'.$k.$i.$pfj]) != ''))
												{
														if (($_REQUEST[$res]>= $_REQUEST['rmin'.$k.$i.$pfj]) and ($_REQUEST[$res] <= $_REQUEST['rmax'.$k.$i.$pfj]))
														{
																$sw_alerta = '0';
														}
														else
														{
																$sw_alerta = '1';
														}
												}
												else
												{
														$sw_alerta = '0';
												}
										}
										if ($_REQUEST['rmin'.$k.$i.$pfj] == 'NULL'){$_REQUEST['rmin'.$k.$i.$pfj]='';}
										if ($_REQUEST['rmax'.$k.$i.$pfj] == 'NULL'){$_REQUEST['rmax'.$k.$i.$pfj]='';}
										if ($_REQUEST['unidades'.$k.$i.$pfj] == 'NULL'){$_REQUEST['unidades'.$k.$i.$pfj]='';}


										$query="INSERT INTO hc_apoyod_resultados_detalles
										(cargo, tecnica_id, lab_examen_id, resultado_id, resultado,
										sw_alerta, rango_min, rango_max, unidades)
										VALUES  ('".$_SESSION['LISTA']['APOYO']['cargo']."',
										".$_SESSION['LISTA']['APOYO']['tecnica_id'].",
										".$_REQUEST[$lab_examen].",".$resultado_id.",'".$_REQUEST[$res]."', '".$sw_alerta."',
										'".$_REQUEST['rmin'.$k.$i.$pfj]."',	'".$_REQUEST['rmax'.$k.$i.$pfj]."',	'".$_REQUEST['unidades'.$k.$i.$pfj]."')";

										$resulta4=$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
												$this->error = "Error al insertar en hc_apoyod_resultados_detalles";
												$this->frmError["MensajeError"]="ERROR AL INSERTAR EL RESULTADO.";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
										}
								}
						}
				}
		}
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		$dbconn->CommitTrans();
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
}


//ad*  esta funcion inserta todos los resultados cuyo modo resultado es 2
//el cual corresponde a resultados manuales.

//continuar estoy en proceso de migrar toda esta funcion basado en las listas del modulo.
//hasta aqui quede sep-06-2005

function InsertarManuales($tipo_id_paciente, $paciente_id, $evolucion_id)
{
		$pfj=$this->frmPrefijo;
    $indice = $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['apoyos'];
    for ($k=0; $k<$indice; $k++)
    {
        if ((!empty ($_REQUEST['insertar_resultado'.$k.$pfj])) OR (!empty ($_REQUEST['insertar_todos'.$pfj])))
        {
            $fecha = $_REQUEST['fecha_realizado'.$k.$pfj];
            if($fecha == '')
            {
                $this->frmError['fecha_realizado'.$k.$pfj]=1;
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS, SELECCIONE UNA FECHA.";
                return false;
            }
            else
            {
                $cad=explode ('-',$fecha);
                $dia = $cad[0];
                $mes = $cad[1];
                $ano = $cad[2];
                $fecha=$cad[2].'-'.$cad[1].'-'.$cad[0];
                $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['fecha_realizado_formateada'] = $fecha;
                if (date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano)) > date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('Y'))))
                {
                    $this->frmError['fecha_realizado'.$k.$pfj]=1;
                    $this->frmError["MensajeError"]="FECHA INVALIDA, SELECCIONE UNA FECHA INFERIOR O IGUAL A LA ACTUAL .";
                    return false;
                }
            }

            if (!$_REQUEST['items'.$k.$pfj])
            {
                $this->frmError["MensajeError"]="CAMPOS DE RESULTADO INEXISTENTES.";
                return false;
            }

            $subindice = $_REQUEST['items'.$k.$pfj];
            for ($i=0; $i<$subindice; $i++)
            {
                if ($_REQUEST['resultado'.$k.$i.$pfj] === '' or $_REQUEST['resultado'.$k.$i.$pfj] == -1)
                {
                    $this->frmError['resultado'.$k.$i.$pfj]=1;
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS, DEBE ESCRIBIR UN RESULTADO.";
                    return false;
                }
            }
        }
    }
		list($dbconn) = GetDBconn();
    $dbconn->BeginTrans();
    
    $cont=0;
		for ($k=0; $k<$indice; $k++)
    {
        if ((!empty ($_REQUEST['insertar_resultado'.$k.$pfj])) OR (!empty ($_REQUEST['insertar_todos'.$pfj])))
        {
	         //OBTENER EL TIPO DE RESULTADO - CAMBIO GENERADO PARA INSERTAR RESULTADOS DE PNQ
		        $query= "SELECT c.apoyod_tipo_id as tipo_resultadoapd,
			              d.grupo_tipo_cargo as tipo_resultadonqx
					          FROM cups a, grupos_noqx_apoyod b left join apoyod_tipos c
						        on (b.grupo_tipo_cargo = c.apoyod_tipo_id) left join
						        no_qx_grupos_tipo_cargo d on (b.grupo_tipo_cargo = d.grupo_tipo_cargo)
                    WHERE a.cargo = '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."'
                    AND a.grupo_tipo_cargo = b.grupo_tipo_cargo";
		        $result=$dbconn->Execute($query);
		        if ($dbconn->ErrorNo() != 0)
		        {
				        $this->error = "Error al Consultar el tipo de resultado para el examen";
				        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				        return false;
		        }
		        $tipo_resultado=$result->GetRowAssoc($ToUpper = false);
		        if ($tipo_resultado)
		        {
				        if ($tipo_resultado[tipo_resultadoapd]!= NULL OR $tipo_resultado[tipo_resultadoapd]!= '')
				        {
						        $os_tipo_resultado = ModuloGetVar('','','TipoSolicitudApoyod');
				        }
				        else
				        {
						        if ($tipo_resultado[tipo_resultadonqx]!= NULL OR $tipo_resultado[tipo_resultadonqx]!= '')
						        {
								        $os_tipo_resultado = 'PNQ';
						        }
						        else
						        {
								        $this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO PRESIONE EL BOTON LISTA DE APOYO DIAG.";
								        return false;
						        }
				        }
		        }
		        else
		        {
				        $this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO PRESIONE EL BOTON LISTA DE APOYO DIAG.";
				        return false;
		        }
	          //FIN

		        //realiza el id manual de la tabla
		        $query="SELECT nextval('hc_resultados_resultado_id_seq')";
		        $result=$dbconn->Execute($query);
		        $resultado_id=$result->fields[0];
	          //fin de la operacion.

		        $query="INSERT INTO hc_resultados (resultado_id,
                    cargo, tecnica_id,
                    fecha_registro, usuario_id, tipo_id_paciente, paciente_id,
                    fecha_realizado,
                    os_tipo_resultado,
                    observacion_prestacion_servicio, sw_modo_resultado)
						
                    VALUES(".$resultado_id.",
                    '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."',
                    ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada'].",
                    now(), ".UserGetUID().", '".$tipo_id_paciente."','".$paciente_id."',
                    '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['fecha_realizado_formateada']."',
                    '".$os_tipo_resultado."',
                    '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']."', '2')";

		        $resulta1=$dbconn->Execute($query);
		        if ($dbconn->ErrorNo() != 0)
		        {
				        $this->error = "Error al insertar en hc_resultados";
				        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				        $dbconn->RollbackTrans();
				        return false;
		        }
		        else
		        {
				        $sw_prof = '1';
				        $sw_prof_dpto = '0';
				        $sw_prof_todos = '0';
                
				        $query="INSERT INTO hc_apoyod_lecturas_profesionales
				        (resultado_id, evolucion_id, sw_prof, sw_prof_dpto, sw_prof_todos,
				        observacion_prof)
				        VALUES  (".$resultado_id.", ".$this->evolucion.", '".$sw_prof."',
				        '".$sw_prof_dpto."', '".$sw_prof_todos."', '".$_REQUEST['observacion_medico'.$pfj]."')";
                
                //OJO CLAUDIA PENDIENTE SI ESTA OBSERVACION LLEga.
                
				        $resulta2=$dbconn->Execute($query);
				        if ($dbconn->ErrorNo() != 0)
				        {
						        $this->error = "Error al insertar en hc_apoyod_lecturas_profesionales";
						        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						        $dbconn->RollbackTrans();
						        return false;
				        }
				        else
				        {
						        $query="INSERT INTO hc_resultados_manuales
						        (resultado_id, numero_orden_id, profesional)
						        VALUES  (".$resultado_id.", 
                    ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['numero_orden_id'].",
                    '".$_REQUEST['profesional'.$pfj]."')";
                    
                    //ojo claudia verificar si este profesional esta llegando.
						        
                    $resulta3=$dbconn->Execute($query);
						        if ($dbconn->ErrorNo() != 0)
						        {
								        $this->error = "Error al insertar en hc_resultados_manuales";
								        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								        $dbconn->RollbackTrans();
								        return false;
						        }
						        else
						        {   //se metio esta parte para actualizar el estado de os_maestro a 4.
								        $query="UPDATE os_maestro SET sw_estado = '4'
								        WHERE numero_orden_id = ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['numero_orden_id']."";
								        $resulta5=$dbconn->Execute($query);
								        if ($dbconn->ErrorNo() != 0)
								        {
										        $this->error = "Error al actualizar el estado en os_maestro";
										        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										        $dbconn->RollbackTrans();
										        return false;
								        }//fin de os_maestro
								        else
								        {
										        $subindice = $_REQUEST['items'.$k.$pfj];
                            for ($i=0; $i<$subindice; $i++)
                            {                            
                                $lab_examen='lab_examen'.$k.$i.$pfj;
                                $res='resultado'.$k.$i.$pfj;
                                if ($_REQUEST['sw_patologico'.$k.$i.$pfj]!='')
                                {
                                    $sw_alerta = '1';
                                }
                                else
                                {
                                    if ((($_REQUEST['rmin'.$k.$i.$pfj]) != '') and (($_REQUEST['rmax'.$k.$i.$pfj]) != ''))
                                    {
                                        if (($_REQUEST[$res]>= $_REQUEST['rmin'.$k.$i.$pfj]) and ($_REQUEST[$res] <= $_REQUEST['rmax'.$k.$i.$pfj]))
                                        {
                                            $sw_alerta = '0';
                                        }
                                        else
                                        {
                                            $sw_alerta = '1';
                                        }
                                    }
                                    else
                                    {
                                        $sw_alerta = '0';
                                    }
                                }
												        if ($_REQUEST['rmin'.$k.$i.$pfj] == 'NULL'){$_REQUEST['rmin'.$k.$i.$pfj]='';}
                                if ($_REQUEST['rmax'.$k.$i.$pfj] == 'NULL'){$_REQUEST['rmax'.$k.$i.$pfj]='';}
                                if ($_REQUEST['unidades'.$k.$i.$pfj] == 'NULL'){$_REQUEST['unidades'.$k.$i.$pfj]='';}

												        $query="INSERT INTO hc_apoyod_resultados_detalles
												        (cargo, tecnica_id, lab_examen_id, resultado_id, resultado, 
                                sw_alerta, rango_min, rango_max, unidades)
												        VALUES  (
                                '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."',
                                ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada'].",
                                ".$_REQUEST[$lab_examen].",".$resultado_id.",'".$_REQUEST[$res]."', '".$sw_alerta."',
                                '".$_REQUEST['rmin'.$k.$i.$pfj]."',  '".$_REQUEST['rmax'.$k.$i.$pfj]."',  '".$_REQUEST['unidades'.$k.$i.$pfj]."')";

												        $resulta4=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0)
                                {
                                    $this->error = "Error al insertar en hc_apoyod_resultados_detalles";
                                    $this->frmError["MensajeError"]="ERROR AL INSERTAR EL RESULTADO1.";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                                }//
										         }//
								         }//
						        }//
				        }
		        }
        }
    }
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		$dbconn->CommitTrans();
    $this->RegistrarSubmodulo($this->GetVersion());
		return true;
}


    //ad
    function Consulta_General()
    {
      $pfj=$this->frmPrefijo;
      list($dbconnect) = GetDBconn();

         $query =
          
          		"SELECT B.*,
                         C.*, 
                         D.sw_prof, 
                         D.sw_prof_dpto,
                         D.sw_prof_todos,
                         CASE WHEN E.titulo_examen IS NOT NULL 
                              THEN E.titulo_examen
                              ELSE F.descripcion END AS titulo_examenes,
                         CASE WHEN M.hc_os_solicitud_id IS NOT NULL 
                              THEN '1' 
                              ELSE '0' END AS autorizado
                    
                    FROM 
                    ( 
                         (
                              SELECT A.*,
                                   H.sw_modo_resultado,
                                   H.fecha_realizado,
                                   H.resultado_id ,
                                   CASE WHEN F.resultado_id IS NULL THEN '0' 
                                        ELSE F.resultado_id END AS resultados_sistema, 
                                   CASE WHEN G.resultado_id IS NULL THEN '0' 
                                        ELSE G.resultado_id END AS resultado_manual
                              FROM
                                   (
                                        SELECT	B.usuario_id,
                                                  B.departamento, 
                                                  B.fecha, 
                                                  A.hc_os_solicitud_id, 
                                                  A.cargo, 
                                                  A.os_tipo_solicitud_id,
                                                  case when C.sw_estado is null then '0' 
                                                       else C.sw_estado end as realizacion,
                                                  C.numero_orden_id,
                                                  A.evolucion_id,
                                                  D.*
                                        FROM	 hc_os_solicitudes A,
                                                  hc_apoyod_lectura_grupal_detalle D,
                                                  hc_evoluciones B,
                                                  os_maestro C
                                        WHERE	 A.tipo_id_paciente = '".$this->tipoidpaciente."'
                                                  AND A.paciente_id = '".$this->paciente."'
                                                  AND A.evolucion_id = A.evolucion_id
                                                  --AND A.evolucion_id =".$this->evolucion."
                                                  AND D.evolucion_id = ".$this->evolucion." 
                                                  AND A.evolucion_id = D.evolucion_id_solicitud                         
                                                  AND B.evolucion_id = A.evolucion_id
                                                  AND A.hc_os_solicitud_id = C.hc_os_solicitud_id              
                                   ) AS A
                                   LEFT JOIN hc_resultados_sistema AS F 
                                        ON(A.numero_orden_id = F.numero_orden_id)
                                   LEFT JOIN hc_resultados_manuales AS G 
                                        ON(A.numero_orden_id = G.numero_orden_id)
                                   LEFT JOIN hc_resultados as H
                                        ON ( G.resultado_id = H.resultado_id)
                              WHERE H.fecha_realizado IS NOT NULL
                         )
                                   
                         UNION
                              
                         (  SELECT A.*,
                                   H.sw_modo_resultado,
                                   H.fecha_realizado,
                                   H.resultado_id ,
                                   CASE WHEN F.resultado_id IS NULL THEN '0' 
                                        ELSE F.resultado_id END AS resultados_sistema, 
                                   CASE WHEN G.resultado_id IS NULL THEN '0' 
                                        ELSE G.resultado_id END AS resultado_manual
                              FROM
                                   (    SELECT	B.usuario_id,
                                                  B.departamento, 
                                                  B.fecha, 
                                                  A.hc_os_solicitud_id, 
                                                  A.cargo, 
                                                  A.os_tipo_solicitud_id,
                                                  case when C.sw_estado is null then '0' 
                                                       else C.sw_estado end as realizacion,
                                                  C.numero_orden_id,
                                                  A.evolucion_id,
                                                  D.*
                                        FROM	 hc_os_solicitudes A,
                                                  hc_apoyod_lectura_grupal_detalle D,
                                                  hc_evoluciones B,
                                                  os_maestro C
                                        WHERE	 A.tipo_id_paciente = '".$this->tipoidpaciente."'
                                                  AND A.paciente_id = '".$this->paciente."'
                                                  AND A.evolucion_id = A.evolucion_id
                                                  --AND A.evolucion_id = ".$this->evolucion."
                                                  AND D.evolucion_id = ".$this->evolucion." 
                                                  AND A.evolucion_id = D.evolucion_id_solicitud                         
                                                  AND B.evolucion_id = A.evolucion_id
                                                  AND A.hc_os_solicitud_id = C.hc_os_solicitud_id              
                                   ) AS A
                                   LEFT JOIN hc_resultados_sistema AS F 
                                        ON(A.numero_orden_id = F.numero_orden_id)
                                   LEFT JOIN hc_resultados_manuales AS G 
                                        ON(A.numero_orden_id = G.numero_orden_id)
                                   LEFT JOIN hc_resultados as H
                                        ON (F.resultado_id = H.resultado_id)
                              WHERE H.fecha_realizado IS NOT NULL
                         )
                    ) 
                    AS B
                    LEFT JOIN hc_apoyod_resultados_detalles C ON (B.resultado_id = C.resultado_id AND C.cargo = B.cargo)
                    LEFT JOIN hc_apoyod_lecturas_profesionales D ON (D.resultado_id = C.resultado_id),
                    apoyod_cargos E,
                    cups F,
                    apoyod_cargos_tecnicas G,
                    lab_examenes H,
                    hc_os_autorizaciones M
                    WHERE
                    
                              B.cargo = E.cargo
                              AND E.cargo = F.cargo
                              AND G.cargo = B.cargo
                              AND C.tecnica_id = G.tecnica_id
                              AND H.cargo = C.cargo
                              AND H.tecnica_id = C.tecnica_id
                              AND H.lab_examen_id = C.lab_examen_id
                              AND B.hc_os_solicitud_id = M.hc_os_solicitud_id;";
          
          /*
          
          "SELECT h.sw_modo_resultado, a.hc_os_solicitud_id, a.cargo, a.os_tipo_solicitud_id,
		b.usuario_id, b.departamento, b.fecha, k.informacion, case when d.hc_os_solicitud_id is
		not null then '1' else '0' end as autorizado, case when e.sw_estado is null
		then '0' else e.sw_estado end as realizacion, case when f.resultado_id is null
		then '0' else f.resultado_id end as resultados_sistema, case when g.resultado_id
		is null then '0' else g.resultado_id end as resultado_manual, e.numero_orden_id,
		h.fecha_realizado, case when k.titulo_examen is not null then k.titulo_examen
		else l.descripcion end as titulo_examenes, i.*, j.sw_prof, j.sw_prof_dpto,
		j.sw_prof_todos FROM hc_os_solicitudes as a left join apoyod_cargos as k on
		(a.cargo = k.cargo) left join cups as l on (a.cargo=l.cargo) left join
		hc_os_autorizaciones as d on (a.hc_os_solicitud_id=d.hc_os_solicitud_id)
		left join os_maestro as e on (a.hc_os_solicitud_id=e.hc_os_solicitud_id)
		left join hc_resultados_sistema as f on(e.numero_orden_id=f.numero_orden_id)
		left join hc_resultados_manuales as g on(e.numero_orden_id=g.numero_orden_id)
		left join hc_resultados as h on ((h.tipo_id_paciente='".$this->tipoidpaciente."' and h.paciente_id='".$this->paciente."') and
		(f.resultado_id=h.resultado_id or g.resultado_id=h.resultado_id))

		left join hc_apoyod_resultados_detalles
		as i on (h.resultado_id=i.resultado_id) left join hc_apoyod_lecturas_profesionales
		as j on (h.resultado_id=j.resultado_id), hc_evoluciones as b, ingresos as c
		WHERE a.evolucion_id=b.evolucion_id and b.ingreso=c.ingreso and
		c.tipo_id_paciente='".$this->tipoidpaciente."' and c.paciente_id='".$this->paciente."'
	--	and j.evolucion_id =  ".$this->evolucion." 
          order  by a.os_tipo_solicitud_id";/**/


		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta general";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$fact[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $fact;
}


//ad - esta funcion se llama para la consulta general
function ConsultaResultadosNoSolicitadosLeidos()
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		$query = "
          SELECT b.cargo, 
            	  b.fecha_realizado, 
                 b.resultado_id,
			  c.titulo_examen, 
                 d.sw_prof,    
                 d.sw_prof_dpto,
                 d.sw_prof_todos,
                 b.sw_modo_resultado,
                 d.evolucion_id, 
                 e.fecha    
          FROM hc_resultados_nosolicitados as a
          	left join hc_resultados as b 
               	on (a.resultado_id = b.resultado_id)
			left join apoyod_cargos as c    
               	on (b.cargo = c.cargo) 
               left join hc_apoyod_lecturas_profesionales as d 
                	on (b.resultado_id = d.resultado_id)
			left join hc_evoluciones as e 
               	on (d.evolucion_id = e.evolucion_id)
		WHERE b.tipo_id_paciente = '".$this->tipoidpaciente."'    
          AND b.paciente_id = '".$this->paciente."'
		AND d.evolucion_id = ".$this->evolucion."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta general de apoyos no solictados";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
		}
		$result->Close();
		return $vector;
}

//ad*
//funcion de la consulta que me convierte la opcion escogida en numero por el valor en letras pos, neg, reac, no reac, etc
function ConversionOpcion($resultado, $id)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		$query= "SELECT opcion FROM lab_plantilla2
		WHERE lab_examen_id= ".$id." AND lab_examen_opcion_id= '".$resultado."'";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al realizar la convercion";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
					$fact[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
		}
		$result->Close();
		return $fact;
}

//ad*

//SI QUEREMOS INSERTAR PNQ DESDE AQUI SE DEBE ALTERAR ESTE QUERY
function tipos()
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT apoyod_tipo_id, descripcion FROM apoyod_tipos";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al cargar las opciones de busqueda";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
		}
		$result->Close();
		return $vector;
}

//DARLING
		/**
		* Separa la fecha del formato timestamp
		* @access private
		* @return string
		* @param date fecha
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

function FechaStampMostrar($fecha)
{
		if($fecha){
						$fech = strtok ($fecha,"-");
						for($l=0;$l<3;$l++)
						{
								$date[$l]=$fech;
								$fech = strtok ("-");
						}
						$mes = str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT);
						$dia = str_pad(ceil($date[2]), 2, 0, STR_PAD_LEFT);
						return  ceil($date[0])."-".$mes."-".$dia;
		}
}

/**
* Separa la hora del formato timestamp
* @access private
* @return string
* @param date hora
*/
function HoraStamp($hora)
{
		$hor = strtok ($hora," ");
		for($l=0;$l<4;$l++)
		{
				$time[$l]=$hor;
				$hor = strtok (":");
		}

		$x = explode (".",$time[3]);
		return  $time[1].":".$time[2].":".$x[0];
}


//FUNCIONES DE ANUEVA VERSION
function ConsultaGrupoLectura($evolucion_id)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query =  "	SELECT a.resultado_id, a.sw_modo_resultado, e.evolucion_id
								FROM hc_resultados a, hc_resultados_sistema b, os_maestro d, hc_os_solicitudes e
								WHERE a.resultado_id = b.resultado_id and b.numero_orden_id = d.numero_orden_id
											and d.hc_os_solicitud_id = e.hc_os_solicitud_id and d.sw_estado = '4'
											and e.evolucion_id = ".$evolucion_id."
								UNION
								SELECT a.resultado_id, a.sw_modo_resultado, e.evolucion_id
								FROM hc_resultados a, hc_resultados_manuales b, os_maestro d, hc_os_solicitudes e
								WHERE a.resultado_id = b.resultado_id and	b.numero_orden_id = d.numero_orden_id
											and d.hc_os_solicitud_id = e.hc_os_solicitud_id
											and d.sw_estado = '4' and e.evolucion_id = ".$evolucion_id."
								UNION
								SELECT a.resultado_id, a.sw_modo_resultado, e.evolucion_id
								FROM hc_resultados a, hc_resultados_sistema b, os_maestro d, hc_os_solicitudes e
								WHERE a.resultado_id = b.resultado_id and b.numero_orden_id = d.numero_orden_id
											and d.hc_os_solicitud_id = e.hc_os_solicitud_id
											and e.evolucion_id = ".$evolucion_id." and b.sw_consulta_examen_sin_firmar = '1'
											";

		//comentado por MauroB, para permitir la lectura sin firma
		
// 		$query =  "SELECT a.resultado_id, a.sw_modo_resultado, e.evolucion_id
// 		FROM hc_resultados a, hc_resultados_sistema b, os_maestro d, hc_os_solicitudes e
// 		WHERE a.resultado_id = b.resultado_id and b.numero_orden_id = d.numero_orden_id
// 		and d.hc_os_solicitud_id = e.hc_os_solicitud_id and d.sw_estado = '4'
// 		and e.evolucion_id = ".$evolucion_id."
// 		UNION
// 		SELECT a.resultado_id, a.sw_modo_resultado, e.evolucion_id
// 		FROM hc_resultados a, hc_resultados_manuales b, os_maestro d, hc_os_solicitudes e
// 		WHERE a.resultado_id = b.resultado_id and	b.numero_orden_id = d.numero_orden_id
// 		and d.hc_os_solicitud_id = e.hc_os_solicitud_id
// 		and d.sw_estado = '4' and e.evolucion_id = ".$evolucion_id."";
		

//IGUAL AL ANTERIOR PERO USANDO LOS TIPOS DE LISTAS DEL DPTO 010601
// 				$query =  "SELECT DISTINCT a.resultado_id,
//
// 				f.tipo_os_lista_id, f.nombre_lista
//
// 				FROM hc_resultados a left join hc_resultados_sistema b
// 				on (a.resultado_id = b.resultado_id) left join hc_resultados_manuales e on
// 				(a.resultado_id = e.resultado_id), os_maestro c, hc_os_solicitudes d
//
//         ,tipos_os_listas_trabajo f, tipos_os_listas_trabajo_detalle g, cups h
//
// 				WHERE b.numero_orden_id = c.numero_orden_id and c.hc_os_solicitud_id = d.hc_os_solicitud_id
// 				and d.evolucion_id = ".$evolucion_id." and c.sw_estado = '4'
//
// 				and c.cargo_cups = h.cargo and h.tipo_cargo = g.tipo_cargo and
// 				h.grupo_tipo_cargo = g.grupo_tipo_cargo and g.tipo_os_lista_id = f.tipo_os_lista_id
// 				and f.departamento = '010601'
//
//
//
// 				order by f.tipo_os_lista_id
//
// 				";
//OJO ALEX DE DONDE ME SACO EL DEPARTAMENTO



		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de Apoyos Diagnosticos";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
						{
								$fact[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
		}
		$result->Close();
		return $fact;
}

function InsertarLecturaExamen($evolucion_id, $resultado_id)
{
		$pfj=$this->frmPrefijo;
		if ($_SESSION['APOYO']['usuario_id'.$evolucion_id] == UserGetUID())
		{
			$sw_prof='1';
			$sw_prof_dpto = '0';
			$sw_prof_todos = '0';
		}
		if (($_SESSION['APOYO']['usuario_id'.$evolucion_id] != UserGetUID()) AND ($_SESSION['APOYO']['departamento'.$evolucion_id] == $this->departamento))
		{
			$sw_prof='0';
			$sw_prof_dpto = '1';
			$sw_prof_todos = '0';
		}
		if (($_SESSION['APOYO']['usuario_id'.$evolucion_id] != UserGetUID()) AND ($_SESSION['APOYO']['departamento'.$evolucion_id] != $this->departamento))
		{
			$sw_prof='0';
			$sw_prof_dpto = '0';
			$sw_prof_todos = '1';
		}
		list($dbconn) = GetDBconn();
			$query="INSERT INTO hc_apoyod_lecturas_profesionales (resultado_id,
						evolucion_id, sw_prof, sw_prof_dpto, sw_prof_todos, observacion_prof)
						VALUES(".$resultado_id.", ".$this->evolucion.", '".$sw_prof."','".$sw_prof_dpto."','".$sw_prof_todos."','".$_REQUEST['obs'.$pfj]."')";
		$resulta1=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al insertar en hc_apoyod_lecturas_profesionales";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]="EL EXAMEN YA FUE LEIDO CON ESTA EVOLUCION";
				return false;
		}
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
}

function InsertarLecturaGrupo($evolucion_id)
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if ($_REQUEST['update'.$evolucion_id.$pfj] == 1)
		{
				$query="UPDATE hc_apoyod_lectura_grupal_detalle SET fecha_registro = now(),
								observacion_prof = '".$_REQUEST['observacion_prof'.$pfj]."'
								WHERE evolucion_id_solicitud = ".$evolucion_id." and
								evolucion_id	= ".$this->evolucion."";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al insertar en hc_apoyod_lecturas_profesionales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->frmError["MensajeError"]="NO FUE POSIBLE LA ACTUALIZACION DE LA OBSERVACION.";
						return false;
				}
		}
		else
		{
				$dbconn->BeginTrans();
				$query = "SELECT COUNT (*) FROM hc_apoyod_lectura_grupal WHERE evolucion_id_solicitud = ".$evolucion_id."";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error en la consulta de lecturas grupales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				else
				{
						list($conteo)=$result->fetchRow();
				}

				if ($_SESSION['APOYO']['usuario_id'.$evolucion_id] == UserGetUID())
				{
					$criterio = "(evolucion_id_solicitud, sw_prof) VALUES(".$evolucion_id.", '1')";
					$criterio1 = "sw_prof ='1'";
				}
				if (($_SESSION['APOYO']['usuario_id'.$evolucion_id] != UserGetUID()) AND ($_SESSION['APOYO']['departamento'.$evolucion_id] == $this->departamento))
				{
					$criterio = "(evolucion_id_solicitud, sw_prof_dpto) VALUES(".$evolucion_id.", '1')";
					$criterio1 = "sw_prof_dpto = '1'";
				}
				if (($_SESSION['APOYO']['usuario_id'.$evolucion_id] != UserGetUID()) AND ($_SESSION['APOYO']['departamento'.$evolucion_id] != $this->departamento))
				{
					$criterio = "(evolucion_id_solicitud, sw_prof_todos) VALUES(".$evolucion_id.", '1')";
					$criterio1 = "sw_prof_todos = '1'";
				}

				if ($conteo == 0)
				{
						$query="INSERT INTO hc_apoyod_lectura_grupal $criterio";
				}
				else
				{
						$query="UPDATE hc_apoyod_lectura_grupal SET $criterio1
										WHERE evolucion_id_solicitud = ".$evolucion_id."";
				}

				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al insertar en hc_apoyod_lecturas_profesionales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->frmError["MensajeError"]="EL GRUPO DE EXAMENES YA FUE LEIDO CON ESTA EVOLUCION";
						$dbconn->RollbackTrans();
						return false;
				}
				else
				{
						$query="INSERT INTO hc_apoyod_lectura_grupal_detalle (evolucion_id_solicitud, evolucion_id,
						usuario_id, fecha_registro, observacion_prof)
									VALUES(".$evolucion_id.", ".$this->evolucion.",
									".UserGetUID().", now(), '".$_REQUEST['observacion_prof'.$pfj]."')";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al insertar en hc_apoyod_lecturas_profesionales";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->frmError["MensajeError"]="NO SE PUDO INSERTAR LA OBSERVACION PARA EL GRUPO DE EXAMENES.";
								$dbconn->RollbackTrans();
								return false;
						}
				}
				$dbconn->CommitTrans();
        $this->RegistrarSubmodulo($this->GetVersion());
		}
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		return true;
}


function ConsultaApoyosSinResultado($evolucion_id)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		$query = "
          
          SELECT
               a.evolucion_id,
               a.hc_os_solicitud_id,
               a.cargo,
               a.os_tipo_solicitud_id, 
               b.usuario_id, 
               b.departamento, 
               b.fecha, 
               e.numero_orden_id,
               case when e.sw_estado is null 
                    then '0' else e.sw_estado end as realizacion,
               l.descripcion as titulo_examenes
          
          FROM 
               os_maestro e, 
               hc_os_solicitudes a,
               hc_evoluciones b, 
               cups l
          WHERE
               a.tipo_id_paciente='".$this->tipoidpaciente."'
               and a.paciente_id='".$this->paciente."'
               and e.hc_os_solicitud_id=a.hc_os_solicitud_id 
               and a.os_tipo_solicitud_id in('APD','PNQ') 
               and a.evolucion_id=b.evolucion_id 
               AND a.cargo=l.cargo
               and (e.sw_estado != '4') 
               and a.evolucion_id = ".$evolucion_id."
          order by a.evolucion_id, a.os_tipo_solicitud_id, a.hc_os_solicitud_id
          ";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de Apoyos Diagnosticos";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
						$fact[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
		}
		$result->Close();
		return $fact;
}

function ConsultaObservacionesGrupales($evolucion_id)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query ="
          SELECT 	a.evolucion_id_solicitud, 
          		a.evolucion_id, 
                    a.usuario_id,
				a.fecha_registro, 
                    a.observacion_prof, 
                    c.nombre_tercero
		FROM 	hc_apoyod_lectura_grupal_detalle as a, 
          		profesionales_usuarios as b, 
                    terceros as c
		WHERE 	a.evolucion_id_solicitud = ".$evolucion_id." 
          		AND a.usuario_id = b.usuario_id
				AND b.tipo_tercero_id = c.tipo_id_tercero 
                    AND b.tercero_id = c.tercero_id";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al consultar las observaciones realizadas al Examen";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
		}
		$result->Close();
		return $vector;
}

/*ad*
esta funcion busca en la tabla hc_apoyod_lectura_grupal el registro de las lecturas
realizadas para los examenes solicitados en una evolucion.*/
function RegistroLecturasGrupo($evolucion_id_solicitud)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "
          SELECT 	evolucion_id_solicitud, 
          		sw_prof, 
                    sw_prof_dpto, 
                    sw_prof_todos
		FROM 	hc_apoyod_lectura_grupal 
          WHERE evolucion_id_solicitud = ".$evolucion_id_solicitud."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de lecturas profesionales";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
			$a=$result->GetRowAssoc($ToUpper = false);
		}
		$result->Close();
		return $a;
}

function Get_Nombre_Examen($resultado_id)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		$query="SELECT b.descripcion FROM hc_resultados a, cups b WHERE
		a.resultado_id = ".$resultado_id." and a.cargo = b.cargo";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al crear subexamen generico";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
		$result->Close();
		return $a;
}


//NUEVAS FUNCIONES PARA AJUSTAR LA ULTIMA VERSION DE LISTAS DE TRABAJO. AGO-13-2005

//ALTERADA
	function Plantillas_Examenes()
	{
			$pfj=$this->frmPrefijo;
			//cargando datos a la variable de session $_SESSION['LISTA']['APOYO']
			if ($_REQUEST['retorno'.$pfj] != '1')
			{
				$_SESSION['LISTA']['APOYO']['tipo_id_paciente']=$this->tipoidpaciente;
				$_SESSION['LISTA']['APOYO']['paciente_id']=$this->paciente;
				//*$_SESSION['LISTA']['APOYO']['nombre']=$_REQUEST['nombre'];
				$_SESSION['LISTA']['APOYO']['cargo']=$_REQUEST['cargo'.$pfj];
				//*$_SESSION['LISTA']['APOYO']['evolucion_id']=$_REQUEST['evolucion_id'];
				$_SESSION['LISTA']['APOYO']['numero_orden_id']=$_REQUEST['numero_orden_id'];
				$_SESSION['LISTA']['APOYO']['hc_os_solicitud_id']=$_REQUEST['hc_os_solicitud_id'];
				$informacion_examen = $this->GetInfoExamen($_SESSION['LISTA']['APOYO']['cargo']);
				if (!empty($informacion_examen))
				{
						$_SESSION['LISTA']['APOYO']['titulo']= $informacion_examen['titulo'];
						$_SESSION['LISTA']['APOYO']['informacion']= $informacion_examen['informacion'];
				}
				else
				{
						$_SESSION['LISTA']['APOYO']['titulo']=$_REQUEST['titulo'];
						$_SESSION['LISTA']['APOYO']['informacion']= '';
				}
			}

			$multitecnica= $this->Consultar_Tecnicas_Examen('','','',$_SESSION['LISTA']['APOYO']['cargo']);

			if (sizeof($multitecnica)>1)
			{
					$this->frmSeleccion_Tecnica($multitecnica);
					return true;
			}
			else
			{
					$_SESSION['LISTA']['APOYO']['tecnica_id']=$multitecnica[0][tecnica_id];
					$this->frmCrearFormaE();
					return true;
			}
	}

	//IGUAL
	function GetInfoExamen($cargo)
	{
			list($dbconn) = GetDBconn();
			$query="SELECT case when (a.titulo_examen = '' or a.titulo_examen ISNULL)
			then b.descripcion else a.titulo_examen end as titulo, a.informacion
			FROM apoyod_cargos a, cups b
			WHERE a.cargo = b.cargo and a.cargo = '".$cargo."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al consultar los datos del examen";
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

	//equivalente a consultar plantillas examen
	//IGUAL
	function Consultar_Tecnicas_Examen($tipo_id_paciente, $paciente_id, $k, $cargo)
	{
			list($dbconnect) = GetDBconn();
			$query = "SELECT cargo, tecnica_id, nombre_tecnica, sw_predeterminado
			FROM apoyod_cargos_tecnicas WHERE cargo = '".$cargo."' order by sw_predeterminado desc";

			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error en la consulta de tecnicas";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
			}
			else
			{
					while (!$result->EOF)
					{
							$fact[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}

      if ($_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] == '2')
      {
					for($j=0;$j<sizeof($fact); $j++)
					{
							$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['tecnica_id']= $fact[$j]['tecnica_id'];
							$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['nombre_tecnica']= $fact[$j]['nombre_tecnica'];
							if($j==0)
							{
									$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']= $fact[$j]['tecnica_id'];
							}
					}
			}
			return $fact;
	}

  //IGUAL
	function GetSexo($tipo_id_paciente, $paciente_id)
	{
			list($dbconn) = GetDBconn();
			$query="SELECT sexo_id FROM pacientes
			WHERE  paciente_id = '".$paciente_id."' AND
			tipo_id_paciente =  '".$tipo_id_paciente."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al consultar el sexo del paciente";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			if (!$result->EOF)
			{
					list($sexo)=$result->FetchRow();
					$result->Close();
			}
			return $sexo;
	}

	//IGUAL
	function Obtener_Edad($tipo_id_paciente, $paciente_id)
	{
			list($dbconn) = GetDBconn();
			$query="SELECT fecha_nacimiento FROM pacientes
			WHERE  paciente_id = '".$paciente_id."' AND
			tipo_id_paciente =  '".$tipo_id_paciente."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al consultar la tabla hc_medicamentos_recetados_amb";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$a=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			$edad_paciente = CalcularEdad($a[fecha_nacimiento],date("Y-m-d"));
			return $edad_paciente;
	}

//igual
	 function ConsultaComponentesExamen($cargo, $tecnica_id, $sexo_id , $edad, $k, $tipo_id_paciente, $paciente_id, $indice, $sw_plantilla0)
	{
			list($dbconnect) = GetDBconn();
			/*
		  
							
							/**/
			//Como contingencia para la SOS se corrigio este query
			//Se corrigio cuando el cargo tiene varios subexamenes
			//no trae el lab_examen_id adecuado o duplica los
			//examenes traidos
			//cuando la pantilla es != 0 se ejecutael primer query
			//si el = 0   se ejecuta el segundo
			//replantear estructura MB. Hay momentos enque el query viejo
			//(segundo) si funciona con cualquier plantilla
			//examinar si es por parametrizacion
			
			if($sw_plantilla0=='1')
			{
				$query = "
						(SELECT  a.cargo, a.tecnica_id, a.lab_examen_id,
											a.lab_plantilla_id, a.nombre_examen, a.indice_de_orden,
											b.sexo_id, b.rango_min, b.rango_max,
											b.edad_min, b.edad_max, b.unidades as unidades_1,
											NULL AS opcion,  NULL AS unidades_2, NULL AS detalle
											
							FROM 
									lab_examenes a ,
									lab_plantilla1 b 
							WHERE 	a.cargo='".$cargo."' 
											and a.cargo = b.cargo 
											and a.tecnica_id = '".$tecnica_id."'
											and a.tecnica_id = b.tecnica_id 
											and a.lab_examen_id = b.lab_examen_id 
											and (b.sexo_id = '".$sexo_id."'  OR b.sexo_id = '0')
											and (".$edad." >= b.edad_min  OR b.edad_min = 0)
											and (".$edad." <= b.edad_max  OR b.edad_min = 0)
							)
							UNION
							(
							SELECT  a.cargo, a.tecnica_id, a.lab_examen_id,
											a.lab_plantilla_id, a.nombre_examen, a.indice_de_orden,
											NULL AS sexo_id,NULL AS rango_min,NULL AS rango_max,
											NULL AS edad_min,NULL AS edad_max,NULL AS unidades_1,
											c.opcion, c.unidades as unidades_2, NULL AS detalle
							FROM 
									lab_examenes a ,
									lab_plantilla2 c
							WHERE 	a.cargo='".$cargo."' 
											and a.cargo = c.cargo 
											and a.tecnica_id = '".$tecnica_id."'
											and a.tecnica_id = c.tecnica_id 
											and a.lab_examen_id = c.lab_examen_id 
							)		
							UNION
							(
							SELECT  a.cargo, a.tecnica_id, a.lab_examen_id,
											a.lab_plantilla_id, a.nombre_examen, a.indice_de_orden,
											NULL AS sexo_id,NULL AS rango_min,NULL AS rango_max,
											NULL AS edad_min,NULL AS edad_max,NULL AS unidades_1,
											NULL AS opcion, NULL AS unidades_2,
											d.detalle
							FROM 
									lab_examenes a ,
									lab_plantilla3 d
							WHERE 	a.cargo='".$cargo."' 
											and a.cargo = d.cargo 
											and a.tecnica_id = '".$tecnica_id."'
											and a.tecnica_id = d.tecnica_id 
											and a.lab_examen_id = d.lab_examen_id 
							)
			";
			}
			else
			{
				$query = "SELECT  a.cargo, a.tecnica_id, a.lab_examen_id,
							a.lab_plantilla_id, a.nombre_examen, a.indice_de_orden,
							b.sexo_id, b.rango_min, b.rango_max,
							b.edad_min, b.edad_max, b.unidades as unidades_1,
							c.opcion,	c.unidades as unidades_2,	d.detalle
							FROM lab_examenes a left join lab_plantilla1 b on
							(a.cargo = b.cargo and a.tecnica_id = b.tecnica_id and
							a.lab_examen_id = b.lab_examen_id and (b.sexo_id = '".$sexo_id."' OR
							b.sexo_id isNULL OR b.sexo_id = '0')
							and (".$edad." >= b.edad_min OR b.edad_min isNULL OR b.edad_min = 0)
							and (".$edad." <= b.edad_max OR b.edad_max isNULL OR b.edad_min = 0))
							left join lab_plantilla2 c on (a.cargo = c.cargo and a.tecnica_id = c.tecnica_id
							and a.lab_examen_id = c.lab_examen_id)
							left join lab_plantilla3 d  on (a.cargo = d.cargo and a.tecnica_id = d.tecnica_id
							and a.lab_examen_id = d.lab_examen_id)
							WHERE a.cargo='".$cargo."' and a.tecnica_id = ".$tecnica_id."
							order by a.indice_de_orden";
			}

			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error al consultar los componentes del examen";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
			}
			else
			{
					while (!$result->EOF)
					{
							$fact[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}


			if (($_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] == '2'))
      {
					if($indice == $k OR empty($indice))
					{
              if($_SESSION['CONSTRUCTOR_REQUEST']!=1)
							{
									for($i=0;$i<sizeof($fact); $i++)
									{
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']=$fact[$i]['lab_examen_id'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['nombre_examen']=$fact[$i]['nombre_examen'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_plantilla_id']=$fact[$i]['lab_plantilla_id'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']=$fact[$i]['rango_min'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['sexo_id']=$fact[$i]['sexo_id'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']=$fact[$i]['rango_max'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['edad_min']=$fact[$i]['edad_min'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['edad_max']=$fact[$i]['edad_max'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']=$fact[$i]['unidades_1'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_2']=$fact[$i]['unidades_2'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']=$fact[$i]['opcion'];
											$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['detalle']=$fact[$i]['detalle'];
									}
							}
					}
			}
			return $fact;
	}


//igual
function CrearGenerico($cargo, $titulo)
	{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query="SELECT COUNT(*) FROM apoyod_cargos_tecnicas
			WHERE cargo = '".$cargo."' and tecnica_id = 1";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error en la consulta de apoyod_cargos_tecnicas";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			list($var_existe_apoyo_tecnica)=$result->FetchRow();

			if ($var_existe_apoyo_tecnica == 0)
			{
					$query="SELECT COUNT(*) FROM apoyod_cargos
					WHERE cargo = '".$cargo."'";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error en la consulta del Pagador";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					list($var_existe_apoyo)=$result->FetchRow();
					if ($var_existe_apoyo == 0)
					{
							$query="INSERT INTO apoyod_cargos
							(cargo,titulo_examen, sexo_id, apoyod_tipo_id)
							VALUES  ('".$cargo."', '".$titulo."', 0,
							(SELECT grupo_tipo_cargo FROM cups WHERE cargo = '".$cargo."'))";

							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al insertar en apoyod_cargos";
									$this->frmError["MensajeError"]="Error al insertar en apoyod_cargos.";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}

							$query="INSERT INTO apoyod_cargos_tecnicas
							(tecnica_id, cargo, nombre_tecnica, sw_predeterminado)
							VALUES  (1, '".$cargo."', 'Tecnica Generica', 0)";

							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al insertar en apoyod_cargos_tecnicas";
									$this->frmError["MensajeError"]="Error al insertar en apoyod_cargos_tecnicas.";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
					}
					else
					{
							$query="INSERT INTO apoyod_cargos_tecnicas
							(tecnica_id, cargo, nombre_tecnica, sw_predeterminado)
							VALUES  (1, '".$cargo."', 'Tecnica Generica', 0)";

							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al insertar en apoyod_cargos_tecnicas";
									$this->frmError["MensajeError"]="Error al insertar en apoyod_cargos_tecnicas.";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
					}
			}
			$query="SELECT COUNT(*) FROM lab_examenes
			WHERE tecnica_id = 1 and cargo = '".$cargo."' and
			lab_examen_id = 0";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error en la consulta del lab_examenes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			list($var_existe_lab_examen)=$result->FetchRow();
			if ($var_existe_lab_examen == 0)
			{
					$query="INSERT INTO lab_examenes
					(tecnica_id, cargo, lab_examen_id, lab_plantilla_id, nombre_examen)
					VALUES  (1, '".$cargo."', 0, 0, 'GENERICO')";

					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al insertar en lab_examenes";
							$this->frmError["MensajeError"]="Error al insertar en lab_examenes.";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}
			}
			$dbconn->CommitTrans();
      $this->RegistrarSubmodulo($this->GetVersion());
			return true;
	}
  
  /* NUEVAS FUNCIONES QUE ESTOY PEGANDO PARA EL AGRUPADO DEL MODULO DE APOYOS */
  //DIFERENTE
  function ConsultaOrdenesPaciente($paciente_id, $tipo_id_paciente, $evolucion_id, $sexo_paciente)
  { 
      list($dbconnect) = GetDBconn();
      $query = "SELECT b.numero_orden_id, b.cargo_cups as cargo, 
                b.hc_os_solicitud_id, c.evolucion_id,
                case when (e.titulo_examen = '' or e.titulo_examen ISNULL)
                then d.descripcion else e.titulo_examen end as titulo, e.informacion
                     
                FROM os_ordenes_servicios a, os_maestro b, 
                hc_os_solicitudes c, cups as d, apoyod_cargos e
                      
                WHERE a.paciente_id = '".$paciente_id."'
                and a.tipo_id_paciente = '".$tipo_id_paciente."'     
                and a.orden_servicio_id = b.orden_servicio_id
                and b.sw_estado = '1'                 
                and b.cargo_cups=d.cargo
                and d.cargo = e.cargo         
                and b.hc_os_solicitud_id = c.hc_os_solicitud_id   
                and c.evolucion_id = ".$evolucion_id."   
                and (e.sexo_id = '".$sexo_paciente."' OR e.sexo_id = '0')";

      $result = $dbconnect->Execute($query);
      if ($dbconnect->ErrorNo() != 0)
      {
          $this->error = "Error en la busqueda";
          $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
          return false;
      }
      else
      {
          while (!$result->EOF)
          {
              $fact[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
          }
      }
      if(empty($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]))
      { 
          $this->Constructor_Session_Apoyo($tipo_id_paciente, $paciente_id, $fact);
      }
      return $fact;
  }
  
  //IGUAL
  //construye las variables de session iniciales
  function Constructor_Session_Apoyo($tipo_id_paciente, $paciente_id, $fact)
  {
      $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['apoyos'] = sizeof($fact);      
      for($k=0; $k<sizeof($fact); $k++)
      {
          $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']= $fact[$k]['cargo'];
          $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['numero_orden_id']= $fact[$k]['numero_orden_id'];
          $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['hc_os_solicitud_id']= $fact[$k]['hc_os_solicitud_id'];
          $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['evolucion_id']= $fact[$k]['evolucion_id'];
          if (!empty($fact[$k]['usuario_id']))
          {
              $profesional_honorarios = GetDatosProfesional($fact[$k]['usuario_id']);
              $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['profesional_honorario'] = $profesional_honorarios[nombre_tercero];
          }
          $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['titulo']= $fact[$k]['titulo'];
          $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['informacion']= $fact[$k]['informacion'];
          $this->Consultar_Tecnicas_Examen($tipo_id_paciente, $paciente_id, $k, $fact[$k]['cargo']);
      }
      return true;
  }
  
   //IGUAL - ALTERADA CON EL PFJ y con la $_REQUEST['observacion_medico'.$pfj], $_REQUEST['profesional'.$pfj]
  function Constructor_Session_Apoyo_Mto($tipo_id_paciente, $paciente_id)
  { 
    $pfj=$this->frmPrefijo;         
    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['observacion_medico'] = $_REQUEST['observacion_medico'.$pfj];    
    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['profesional'] = $_REQUEST['profesional'.$pfj];        
    for($k=0; $k<$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['apoyos']; $k++)
    {
        $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['fecha_realizado']= $_REQUEST['fecha_realizado'.$k.$pfj];                        
        $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['firma'] = $_REQUEST['firma'.$k];
        $e = 0;
        for($i=0; $i<$_REQUEST['vector'.$k.$pfj]; $i++)
        {
            if ($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_plantilla_id']=='2')
            {
                if($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']==$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i+1]['lab_examen_id'])
                {
                    $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['resultado']=$_REQUEST['resultado'.$k.$e.$pfj];
                }
                else
                {
                    $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']=$_REQUEST['rmin'.$k.$e.$pfj];
                    $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']=$_REQUEST['rmax'.$k.$e.$pfj];
                    $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_2']=$_REQUEST['unidades'.$k.$e.$pfj];
                    $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['resultado']=$_REQUEST['resultado'.$k.$e.$pfj];
                    $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['sw_patologico']=$_REQUEST['sw_patologico'.$k.$e.$pfj];
                    $e++;
                }
            }
            else
            {
                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']=$_REQUEST['rmin'.$k.$e.$pfj];
                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']=$_REQUEST['rmax'.$k.$e.$pfj];
                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']=$_REQUEST['unidades'.$k.$e.$pfj];
                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['resultado']=$_REQUEST['resultado'.$k.$e.$pfj];
                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['sw_patologico']=$_REQUEST['sw_patologico'.$k.$e.$pfj];
                $e++;
            }
        }
    }
    $_SESSION['CONSTRUCTOR_REQUEST']=1;
    return true;
  }

	//MAuroB
	function PermisoConsultaSinFirma($n_orden_id){
		list($dbconnect) = GetDBconn();
      $query = "SELECT	sw_consulta_examen_sin_firmar
								FROM		 	hc_resultados_sistema
								WHERE		 numero_orden_id = $n_orden_id
					";

      $result = $dbconnect->Execute($query);
      if ($dbconnect->ErrorNo() != 0)
      {
          $this->error = "Error en la busqueda en hc_resultados_sistema";
          $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
          return false;
      }
			$res=$result->fields[0];
			$result->Close();
			return $res;
	}
	//finMauroB
 
}
?>






