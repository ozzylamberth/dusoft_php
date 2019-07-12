<?php
/**
* Submodulo para la Solicitud de Medicamentos.
*
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_PlanTerapeuticoCExterna.php,v 1.3 2006/12/19 21:00:14 jgomez Exp $
*/

class PlanTerapeuticoCExterna extends hc_classModules
{
  var $limit;
	var $conteo;

//clzc - ptce
function PlanTerapeuticoCExterna() //Constructor
	{
    $this->hc_classModules(); //constructor del padre
		$this->limit=GetLimitBrowser();
    return true;
	}




	function GetConsulta()
	{
		$pfj=$this->frmPrefijo;
		$accion='accion'.$pfj;
		if(empty($_REQUEST[$accion]))
		{
			$this->frmConsulta();
		}
	  return $this->salida;
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

/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetReporte_Html()
	{
		$imprimir=$this->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
		return $imprimir;
	}


//cor - clzc - ads
function GetForma()
{
		$pfj=$this->frmPrefijo;
		if(empty($_REQUEST['accion'.$pfj]))
		{
      unset ($_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']); //carga los datos de cabecera del medicamento elegido
			unset ($_SESSION['MEDICAMENTOS'.$pfj]);//carga los datos del medicamento que se va a insertar o que se va a modificar cuando en no pos
			unset ($_SESSION['POSOLOGIA4'.$pfj]);//carga la posologia tipo 4 de medicamentos
			unset ($_SESSION['DIAGNOSTICOS'.$pfj]);//carga los diagnosticos de ingreso o los de la base de datos
			unset ($_SESSION['JUSTIFICACION'.$pfj]);//carga los datos de la justificacion para que no se pierdan al insertar diagnosticos
			unset ($_SESSION['DIAGNOSTICOSM'.$pfj]);//carga los diagnosticos de la base de datos para la modificacion de la justificacion
			unset ($_SESSION['MEDICAMENTOSM'.$pfj]);//carga los datos del medicamento de la base de datos
			unset ($_SESSION['MODIFICANDO'.$pfj]);
			$this->frmForma();
		}
		else
		{
			if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Medicamentos')
			{
				 	$vectorA= $this->Busqueda_Avanzada_Medicamentos();
         	$this-> frmForma_Seleccion_Medicamentos($vectorA);
			}


			if($_REQUEST['accion'.$pfj]=='llenar_solicitud_medicamento')
			{
				//variables de sesion usadas en el proceso de medicamentos no pos con justificacion
				//se setean cada vez que se escoja un medicamento nuevo
				unset ($_SESSION['MEDICAMENTOS'.$pfj]);
				unset ($_SESSION['POSOLOGIA4'.$pfj]);
				unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
				unset ($_SESSION['JUSTIFICACION'.$pfj]);
				if ($_REQUEST['opE'.$pfj] != '')
					{
						$_REQUEST['opE'.$pfj]=urldecode($_REQUEST['opE'.$pfj]);
            $_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']= $_REQUEST['opE'.$pfj];
						$arreglo=explode('|/',$_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);				
						$existe_medicamento = $this->Verificacion_Existe_Medicamento($arreglo[1]);
						if($existe_medicamento[codigo_producto]=='')
						{
							$this->frmForma_Llenar_Solicitud_Medicamento();
						}
						else
						{
							$this->frmError["MensajeError"]="ESTE MEDICAMENTO YA FUE FORMULADO EN ESTA EVOLUCION";
							$this->frmForma();
						}
					}
				else
					{
						$this->frmError["MensajeError"]="PARA FORMULAR DEBE SELECCIONAR UN MEDICAMENTO DE LA LISTA";
						$this->frmForma();
					}
			}

			//forma pendiente
			if($_REQUEST['accion'.$pfj]=='justificacion_no_pos')
			{
				if(!empty($_REQUEST['guardar_formula'.$pfj]))
				{
						if ($_REQUEST['item'.$pfj] == 'NO POS' AND $_REQUEST['no_pos_paciente'.$pfj] == NULL)
							{
								//$no_pos_paciente = '0';
								if ($this->Verificacion_Previa_Insertar_Medicamentos() == true)
									{
										$this->Justificacion_Medicamentos_No_Pos();
									}
								else
									{
										$this->frmForma_Llenar_Solicitud_Medicamento($_REQUEST['datos_m'.$pfj]);
									}
							}
						else
							{
								$no_pos_paciente = '1';
								if ($this->Insertar_Medicamentos($no_pos_paciente) == true)
								{
										unset ($_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);
										$this->frmForma();
								}
								else
								{
										$this->frmForma_Llenar_Solicitud_Medicamento($_REQUEST['datos_m'.$pfj]);
								}
							}
				}
			}

			if($_REQUEST['accion'.$pfj]=='volver')
			{
						//variables de sesion son seteadas al dar volver estando en la justificacion
						unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
						unset ($_SESSION['JUSTIFICACION'.$pfj]);
						//cargo los request de la forma de solicitud de medicamento con la sesion
						$_REQUEST['via_administracion'.$pfj]= $_SESSION['MEDICAMENTOS'.$pfj]['via_administracion_id'];
						$_REQUEST['dosis'.$pfj]							= $_SESSION['MEDICAMENTOS'.$pfj]['dosis'];
						$_REQUEST['unidad_dosis'.$pfj]			=	$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion'];
						$_REQUEST['opcion'.$pfj]						= $_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'];
						$_REQUEST['cantidad'.$pfj]					= $_SESSION['MEDICAMENTOS'.$pfj]['cantidad'];
						$_REQUEST['observacion'.$pfj]				=	$_SESSION['MEDICAMENTOS'.$pfj]['observacion'];
						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='1')
						{
							$_REQUEST['periocidad'.$pfj] 				= $_SESSION['MEDICAMENTOS'.$pfj]['periocidad_id'];
							$_REQUEST['tiempo'.$pfj]						= $_SESSION['MEDICAMENTOS'.$pfj]['tiempo'];
						}
						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='2')
						{
							$_REQUEST['duracion'.$pfj]					= $_SESSION['MEDICAMENTOS'.$pfj]['duracion_id'];
						}
						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='3')
						{
							$_REQUEST['momento'.$pfj]						= $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_momento'];
							$_REQUEST['desayuno'.$pfj]					= $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_desayuno'];
							$_REQUEST['almuerzo'.$pfj]					= $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_almuerzo'];
							$_REQUEST['cena'.$pfj]							= $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_cena'];
						}
						//si escoje la opcion 4 y regresa desde la justificacion se pierden los datos de la opcion 4
						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='4')
						{
							for ($i=0;$i<25;$i++)
							{
								$_REQUEST['opH'.$pfj][$i] = $_SESSION['POSOLOGIA4'.$pfj]['hora_especifica'][$i];
							}
						}
						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='5')
						{
							$_REQUEST['frecuencia_suministro'.$pfj]	= $_SESSION['MEDICAMENTOS'.$pfj]['frecuencia_suministro'];
						}
						$this->frmForma_Llenar_Solicitud_Medicamento($_REQUEST['datos_m'.$pfj]);
			}

			if($_REQUEST['accion'.$pfj]=='insertar_justificacion_no_pos')
			{
					if ($this->Insertar_Justificacion_No_Pos()== false)
					{
							$this->Justificacion_Medicamentos_No_Pos();
					}
					else
					{
							$this->frmForma();
					}

			}

			if($_REQUEST['accion'.$pfj]=='agregar_diagnosticos')
			{
				if ($_SESSION['MODIFICANDO'.$pfj]!=1)
					{
							//**********creacion de la variable de sesion con los datos de la justificacion**
							$_SESSION['JUSTIFICACION'.$pfj]['dosis_dia']								=$_REQUEST['dosis_dia'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento']			=$_REQUEST['duracion_tratamiento'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['descripcion_caso_clinico']	=$_REQUEST['descripcion_caso_clinico'.$pfj];
							for ($j=1;$j<3;$j++)
								{
										$_SESSION['JUSTIFICACION'.$pfj]['medicamento_pos'.$j]						=$_REQUEST['medicamento_pos'.$j.$pfj];
										$_SESSION['JUSTIFICACION'.$pfj]['principio_activo_pos'.$j]			=$_REQUEST['principio_activo_pos'.$j.$pfj];
										$_SESSION['JUSTIFICACION'.$pfj]['dosis_dia_pos'.$j]							=$_REQUEST['dosis_dia_pos'.$j.$pfj];
										$_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento_pos'.$j]	=$_REQUEST['duracion_tratamiento_pos'.$j.$pfj];
										$_SESSION['JUSTIFICACION'.$pfj]['sw_no_mejoria'.$j]							=$_REQUEST['sw_no_mejoria'.$j.$pfj];
										$_SESSION['JUSTIFICACION'.$pfj]['sw_reaccion_secundaria'.$j]		=$_REQUEST['sw_reaccion_secundaria'.$j.$pfj];
										$_SESSION['JUSTIFICACION'.$pfj]['reaccion_secundaria'.$j]				=$_REQUEST['reaccion_secundaria'.$j.$pfj];
										$_SESSION['JUSTIFICACION'.$pfj]['sw_contraindicacion'.$j]				=$_REQUEST['sw_contraindicacion'.$j.$pfj];
										$_SESSION['JUSTIFICACION'.$pfj]['contraindicacion'.$j]					=$_REQUEST['contraindicacion'.$j.$pfj];
										$_SESSION['JUSTIFICACION'.$pfj]['otras'.$j]											=$_REQUEST['otras'.$j.$pfj];
								}
							$_SESSION['JUSTIFICACION'.$pfj]['justificacion_solicitud']	=$_REQUEST['justificacion_solicitud'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['ventajas_medicamento']			=$_REQUEST['ventajas_medicamento'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['ventajas_tratamiento']			=$_REQUEST['ventajas_tratamiento'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['precauciones']							=$_REQUEST['precauciones'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['controles_evaluacion_efectividad']=$_REQUEST['controles_evaluacion_efectividad'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['tiempo_respuesta_esperado']=$_REQUEST['tiempo_respuesta_esperado'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['sw_riesgo_inminente']			=$_REQUEST['sw_riesgo_inminente'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['riesgo_inminente']					=$_REQUEST['riesgo_inminente'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['sw_agotadas_posibilidades_existentes']=$_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj] ;
							$_SESSION['JUSTIFICACION'.$pfj]['sw_homologo_pos']					=$_REQUEST['sw_homologo_pos'.$pfj] ;
							$_SESSION['JUSTIFICACION'.$pfj]['sw_comercializacion_pais']	=$_REQUEST['sw_comercializacion_pais'.$pfj];
							$_SESSION['JUSTIFICACION'.$pfj]['pare']=0;
							//**********fin******************************************************************
					}
				$this->frmFormaDiagnosticos();
			}

			if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
						{
									 $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                   $this->frmFormaDiagnosticos($vectorD);
						}

			if($_REQUEST['accion'.$pfj]=='insertar_varios_diagnosticos')
			{
					{
						$this->Insertar_Varios_Diagnosticos();
						if ($_SESSION['MODIFICANDO'.$pfj]!=1)
						{
							$this->Justificacion_Medicamentos_No_Pos();
						}
						else
						{
							$this->Consultar_Justificacion_Medicamentos_No_Pos($_REQUEST['codigo_p'.$pfj]);
						}
					}
			}
			if($_REQUEST['accion'.$pfj]=='eliminardiagnostico')
			{
					unset ($_SESSION['DIAGNOSTICOS'.$pfj][$_REQUEST['diagnostico'.$pfj]]);
					$_SESSION['JUSTIFICACION'.$pfj]['pare']=1;
					$this->Justificacion_Medicamentos_No_Pos();
			}
			if($_REQUEST['accion'.$pfj]=='eliminardiagnosticom')
			{
					unset ($_SESSION['DIAGNOSTICOSM'.$pfj][$_REQUEST['diagnostico'.$pfj]]);
					$this->Consultar_Justificacion_Medicamentos_No_Pos($_REQUEST['codigo_p'.$pfj]);
			}


			if($_REQUEST['accion'.$pfj]=='Consultar_Justificacion')
			{
				$_SESSION['MEDICAMENTOSM'.$pfj][codigo_producto]=$_REQUEST['codigo_p'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][evolucion]=$_REQUEST['evolucion'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][producto]=$_REQUEST['product'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][principio_activo]=$_REQUEST['principio_a'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][via]=$_REQUEST['via'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][dosis]=$_REQUEST['dosis'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][unidad_dosificacion]=$_REQUEST['unidad'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][cantidad]=$_REQUEST['canti'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]=$_REQUEST['desc'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][contenido_unidad_venta]=$_REQUEST['contenido_u_v'.$pfj];
				$_SESSION['MEDICAMENTOSM'.$pfj][observacion]=$_REQUEST['obs'.$pfj];
				$_SESSION['MODIFICANDO'.$pfj]=1;
				$this->Consultar_Justificacion_Medicamentos_No_Pos();
			}

			if($_REQUEST['accion'.$pfj]=='modificar_justificacion_no_pos')
			{
				$this->Modificacion_Justificacion_Medicamentos_No_Pos($_REQUEST['hc_justificaciones_no_pos_amb'.$pfj]);
				$this->frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='eliminar')
			{
				$this->Eliminar_Medicamento_Solicitada($_REQUEST['codigo_producto'.$pfj], $_REQUEST['opcion_posologia'.$pfj]);
				$this->frmForma();
				//no necesito setear ninguna variable ok
			}

			if($_REQUEST['accion'.$pfj]=='forma_modificar_medicamento')
			{
				unset($_SESSION['SPIA'.$pfj]);
				$this->frmForma_Modificar_Solicitud_Medicamento($_REQUEST['codigo_producto'.$pfj]);
			}

			if($_REQUEST['accion'.$pfj]=='modificar_datos')
			{
				if ($_REQUEST['item'.$pfj] == 'POS')
					{
						$_REQUEST['no_pos_paciente'.$pfj] = '1';
						$this->Modificar_Medicamento_Solicitado($_REQUEST['codigo_producto'.$pfj], $_REQUEST['opcion_posol'.$pfj]);
						$this->frmForma();
					}
				else
					{
						if (empty($_REQUEST['no_pos_paciente'.$pfj]))
						{
								//si llega aqui es porque el medicamento se iba a pagar y se modifico para justificarlo
								$_SESSION['SPIA'.$pfj]=1;
								if ($this->Verificacion_Previa_Insertar_Medicamentos() == true)
									{
										$this->Justificacion_Medicamentos_No_Pos();
									}
								else
									{
										$this->frmForma_Modificar_Solicitud_Medicamento($_REQUEST['codigo_producto'.$pfj]);
									}
						}
						else
						{
							$this->Modificar_Medicamento_Solicitado($_REQUEST['codigo_producto'.$pfj], $_REQUEST['opcion_posol'.$pfj]);
							$this->frmForma();
						}
					}
			}
}

	return $this->salida;
}

function Insertar_Varios_Diagnosticos()
{
		 $pfj=$this->frmPrefijo;
		 if ($_SESSION['MODIFICANDO'.$pfj]!=1)
		 {
		 		foreach($_REQUEST['opD'.$pfj] as $index=>$codigo)
		    {
				 $arreglo=explode(",",$codigo);
				 $_SESSION['DIAGNOSTICOS'.$pfj][$arreglo[0]]= $arreglo[1];
				}
		 }
		 else
		 {
				foreach($_REQUEST['opD'.$pfj] as $index=>$codigo)
		    {
				 $arreglo=explode(",",$codigo);
				 $_SESSION['DIAGNOSTICOSM'.$pfj][$arreglo[0]]= $arreglo[1];
				}
		 }
}
	//cor - clzc-jea - ads - *
function Busqueda_Avanzada_Diagnosticos()
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
    $codigo       = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != '')
		{
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != ''))
		{
			$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
		}

		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
						FROM diagnosticos
						$busqueda1 $busqueda2";

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
			   $query = "
						SELECT diagnostico_id, diagnostico_nombre
						FROM diagnosticos
						$busqueda1 $busqueda2 order by diagnostico_id
						LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

   	if($this->conteo==='0')
		  {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			        return false;
		  }
		$resulta->Close();
		return $var;
}

//clzc - si - *
	function Busqueda_Avanzada_Medicamentos()
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$opcion      = ($_REQUEST['criterio1'.$pfj]);
		$producto =STRTOUPPER($_REQUEST['producto'.$pfj]);
		$principio_activo =STRTOUPPER($_REQUEST['principio_activo'.$pfj]);

		$busqueda1  = '';
		$busqueda2  = '';
		$dpto = '';
		$espe = '';
		$declaracion = '';
		$condicion = '';

		if ($producto != '')
		{
			  $busqueda1 =" AND a.descripcion LIKE '%$producto%'";
		}

		if ($principio_activo != '')
		{
				$busqueda2 ="AND c.descripcion LIKE '%$principio_activo%'";
		}

		if($opcion == '002')
			{
				$declaracion = ", inv_solicitud_frecuencia as m ";
				$condicion   = "AND a.codigo_producto = m.codigo_producto";
				if ($this->departamento != '' )
					{
						$dpto = "AND m.departamento = '".$this->departamento."'";
					}
				if ($this->especialidad != '' )
					{
						$espe = "AND m.especialidad = '".$this->especialidad."'";
					}
				if ($dpto == '' AND $espe == '')
					{
						return false;
					}
			}

		if(empty($_REQUEST['conteo'.$pfj]))
			{

					if ($this->bodega == '')
					{
					  $query = "SELECT count(*)
            FROM inventarios_productos as a, medicamentos as b,
						inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d $declaracion
						where a.codigo_producto = b.codigo_medicamento
						AND b.cod_principio_activo = c.cod_principio_activo
						AND b.cod_forma_farmacologica = d.cod_forma_farmacologica AND a.estado = '1'
            $condicion $dpto $espe $busqueda1 $busqueda2";
					}
					else
					{
					  $query = "SELECT count(*)
            FROM inventarios_productos as a left join
						hc_bodegas_consultas as e on(e.bodega_unico = '".$this->bodega."') left join existencias_bodegas as f
						on(e.empresa_id=f.empresa_id and e.centro_utilidad=f.centro_utilidad and
						e.bodega=f.bodega and a.codigo_producto=f.codigo_producto), medicamentos as b,
						inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d $declaracion
						where a.codigo_producto = b.codigo_medicamento
						AND b.cod_principio_activo = c.cod_principio_activo
						AND b.cod_forma_farmacologica = d.cod_forma_farmacologica AND a.estado = '1'
            $condicion $dpto $espe $busqueda1 $busqueda2";
					}
					//echo $query;

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
          if ($this->bodega == '')
					{
						$query = "
            select case when b.sw_pos = 1 then 'POS' else 'NO POS' end as item,
						a.codigo_producto, a.descripcion as producto, c.descripcion as principio_activo,
						d.descripcion as forma, d.unidad_dosificacion, b.concentracion_forma_farmacologica, b.unidad_medida_medicamento_id,
						b.factor_conversion, b.factor_equivalente_mg, d.cod_forma_farmacologica FROM inventarios_productos as a, medicamentos as b,
						inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d $declaracion
						where a.codigo_producto = b.codigo_medicamento
						AND b.cod_principio_activo = c.cod_principio_activo
						AND b.cod_forma_farmacologica = d.cod_forma_farmacologica AND a.estado = '1'
            $condicion $dpto $espe $busqueda1 $busqueda2 order by a.codigo_producto
						LIMIT ".$this->limit." OFFSET $Of;";
					}
					else
					{
					   $query = "
            select case when b.sw_pos = 1 then 'POS' else 'NO POS' end as item,
						a.codigo_producto, a.descripcion as producto, c.descripcion as principio_activo,
						d.descripcion as forma, f.existencia, b.concentracion_forma_farmacologica, b.unidad_medida_medicamento_id,	b.factor_conversion, b.factor_equivalente_mg, d.unidad_dosificacion FROM inventarios_productos as a left join
						hc_bodegas_consultas as e on(e.bodega_unico='".$this->bodega."') left join existencias_bodegas as f
						on(e.empresa_id=f.empresa_id and e.centro_utilidad=f.centro_utilidad and
						e.bodega=f.bodega and a.codigo_producto=f.codigo_producto), medicamentos as b,
						inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d $declaracion
						where a.codigo_producto = b.codigo_medicamento
						AND b.cod_principio_activo = c.cod_principio_activo
						AND b.cod_forma_farmacologica = d.cod_forma_farmacologica AND a.estado = '1'
            $condicion $dpto $espe $busqueda1 $busqueda2 order by a.codigo_producto
						LIMIT ".$this->limit." OFFSET $Of;";
					}

		$resulta = $dbconn->Execute($query);

		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

   	if($this->conteo==='0')
		  {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			        return false;
		  }
		$resulta->Close();
		return $var;
}

//clzc - si - *
function Insertar_Medicamentos($no_pos_paciente)
{
//echo 'variable de chulo'.$no_pos_paciente;
//inserta un 1 si es pos o si es no pos y selecciono el check
		 $pfj=$this->frmPrefijo;
		 list($dbconn) = GetDBconn();
     $dbconn->BeginTrans();

		 $_REQUEST['no_pos_paciente'.$pfj] = $no_pos_paciente;

		/*	if ($_REQUEST['cantidad'.$pfj] == '' OR $_REQUEST['via_administracion'.$pfj] == -1 OR
			$_REQUEST['dosis'.$pfj] == '' OR $_REQUEST['unidad_dosis'.$pfj] == -1)*/

			if ($_REQUEST['cantidad'.$pfj] == '' OR $_REQUEST['via_administracion'.$pfj] == -1 OR
					$_REQUEST['unidad_dosis'.$pfj] == -1 OR empty($_REQUEST['unidad_dosis'.$pfj]))
			{
				if($_REQUEST['via_administracion'.$pfj] == '-1')
				 {
		        $this->frmError["via_administracion"]=1;
				 }
	      if($_REQUEST['cantidad'.$pfj] == '')
				 {
		        $this->frmError["cantidad"]=1;
				 }

			/*	 if($_REQUEST['dosis'.$pfj] == '')
				 {
		        $this->frmError["dosis"]=1;
				 }*/

				 if(($_REQUEST['unidad_dosis'.$pfj] == '-1') OR (empty($_REQUEST['unidad_dosis'.$pfj])))
				 {
		        $this->frmError["unidad_dosis"]=1;
				 }

				 $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				 return false;
			}

			if($_REQUEST['dosis'.$pfj] == '')
			{
					$_REQUEST['dosis'.$pfj]='NULL';
			}
			else
			{
 					if (is_numeric($_REQUEST['dosis'.$pfj])==0)
					{
						$this->frmError["dosis"]=1;
						$this->frmError["MensajeError"]="DOSIS INVALIDA, DIGITE SOLO NUMEROS.";
						return false;
					}
			}

			//controlando la insercion de la via
			$via = $_REQUEST['via_administracion'.$pfj];
			if($via == '')
			{
					$via = 'NULL';
			}
			else
			{
        $via ="'$via'";
			}
			//fin del control

			//OBLIGATORIEDAD DE LA FRECUENCIA
			if($_REQUEST['opcion'.$pfj]=='')
				 {
				 		$this->frmError["frecuencia"]=1;
						$this->frmError["MensajeError"]="SELECCIONE UNA OPCION DE FRECUENCIA PARA LA FORMULACION.";
						return false;
				 }
			//fin de obligatoriedad

			if (empty($_REQUEST['opcion'.$pfj]))
			{
					$_REQUEST['opcion'.$pfj] = 0;
			}
	 	 	  $query="INSERT INTO hc_medicamentos_recetados_amb
							(codigo_producto, evolucion_id, cantidad, observacion, sw_paciente_no_pos, via_administracion_id,
						 	dosis, unidad_dosificacion, tipo_opcion_posologia_id)
							VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
										 ".$_REQUEST['cantidad'.$pfj].", '".$_REQUEST['observacion'.$pfj]."',
										 $no_pos_paciente, ".$via.",
										 ".$_REQUEST['dosis'.$pfj].",'".$_REQUEST['unidad_dosis'.$pfj]."',
										 ".$_REQUEST['opcion'.$pfj].")";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al insertar en hc_medicamentos_recetados_amb";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]="EL MEDICAMENTO YA HA SIDO FORMULADO EN ESTA EVOLUCION.";
					$dbconn->RollbackTrans();
						//caso especial que retorne true porque si ya existe elmedicamento no debe volver
						//a la forma de llenado si no a la forma principal
					return true;
			}
		else
			{
       //esto se hace por si retorna error despues de haber convertido a dosis en null para la insercion
				if ($_REQUEST['dosis'.$pfj]=='NULL')
				{
						$_REQUEST['dosis'.$pfj]='';
				}

				if ($_REQUEST['opcion'.$pfj] == '1')
			   {
						if (($_REQUEST['periocidad'.$pfj]=='-1') OR ($_REQUEST['tiempo'.$pfj]=='-1'))
						{
						  $this->frmError["opcion1"]=1;
							$this->frmError["MensajeError"]="PARA OPCION 1 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
							return false;
						}
						else
						{
								$query="INSERT INTO hc_posologia_horario_op1
												(codigo_producto, evolucion_id, periocidad_id, tiempo)
												VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
										 		".$_REQUEST['periocidad'.$pfj].", '".$_REQUEST['tiempo'.$pfj]."')";
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al insertar en hc_posologia_horario_op1";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 1.";
									$dbconn->RollbackTrans();
									//caso especial que retorne true porque si ya existe elmedicamento no debe volver
									//a la forma de llenado si no a la forma principal
									return true;
								}
						}
				 }
				if ($_REQUEST['opcion'.$pfj] == '2')
			   {
						if ($_REQUEST['duracion'.$pfj]=='-1')
							{
							  $this->frmError["opcion2"]=1;
								$this->frmError["MensajeError"]="PARA OPCION 2 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
								return false;
							}
						else
							{
								$query="INSERT INTO hc_posologia_horario_op2
												(codigo_producto, evolucion_id, duracion_id)
												VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
												'".$_REQUEST['duracion'.$pfj]."')";
												$resulta=$dbconn->Execute($query);

									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al insertar en hc_posologia_horario_op2";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 2.";
										$dbconn->RollbackTrans();
										//caso especial que retorne true porque si ya existe elmedicamento no debe volver
										//a la forma de llenado si no a la forma principal
										return true;
									}
							}
					}
				if ($_REQUEST['opcion'.$pfj] == '3')
			      {
							if (empty($_REQUEST['momento'.$pfj]))
								{
									  $this->frmError["opcion3"]=1;
										$this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE DILIGENCIAR LOS CAMPOS.";
										return false;
								}
							else
								{
									if ((!empty($_REQUEST['desayuno'.$pfj])) OR	(!empty($_REQUEST['almuerzo'.$pfj])) OR (!empty($_REQUEST['cena'.$pfj])))
									{
										 	$query="INSERT INTO hc_posologia_horario_op3
														(codigo_producto, evolucion_id, sw_estado_momento,
														sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena)
														VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
														'".$_REQUEST['momento'.$pfj]."', '".$_REQUEST['desayuno'.$pfj]."',
														'".$_REQUEST['almuerzo'.$pfj]."', '".$_REQUEST['cena'.$pfj]."')";
														$resulta=$dbconn->Execute($query);

											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al insertar en hc_posologia_horario_op3";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 3.";
												$dbconn->RollbackTrans();
												//caso especial que retorne true porque si ya existe elmedicamento no debe volver
												//a la forma de llenado si no a la forma principal
												return true;
											}
									}
									else
									{
											$this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE ESCOGER UNA COMIDA.";
											return false;
									}
								}
					  }
          if ($_REQUEST['opcion'.$pfj] == '4')
			      {
						  if (empty($_REQUEST['opH'.$pfj]))
							{
									$this->frmError["opcion4"]=1;
									$this->frmError["MensajeError"]="PARA OPCION 4 DE FRECUENCIA DEBE SELECCIONAR UNA HORA ESPECIFICA.";
									return false;
							}
							else
              {
									foreach($_REQUEST['opH'.$pfj] as $index=>$codigo)
									{
										$arreglo=explode(",",$codigo);
										$query="INSERT INTO hc_posologia_horario_op4
														(codigo_producto, evolucion_id, hora_especifica)
														VALUES ('".$_REQUEST['codigo_producto'.$pfj]."',
														 ".$this->evolucion.", '".$arreglo[0]."')";
										$resulta=$dbconn->Execute($query);

										if ($dbconn->ErrorNo() != 0)
											{
													$this->error = "Error al insertar en hc_posologia_horario_op4";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA HORA ESPECIFICA.";
													$dbconn->RollbackTrans();
													//caso especial que retorne true porque si ya existe elmedicamento no debe volver
													//a la forma de llenado si no a la forma principal
													return true;
											}
									}
							}
						}
					if ($_REQUEST['opcion'.$pfj] == '5')
			      {
							if (empty($_REQUEST['frecuencia_suministro'.$pfj]))
								{
										$this->frmError["opcion5"]=1;
										$this->frmError["MensajeError"]="PARA OPCION 5 DE FRECUENCIA DEBE ESCRIBIR EN LA FRECUENCIA DE SUMINISTRO.";
										return false;
								}
							else
								{
									 $query="INSERT INTO hc_posologia_horario_op5
													(codigo_producto, evolucion_id, frecuencia_suministro)
													VALUES ('".$_REQUEST['codigo_producto'.$pfj]."', ".$this->evolucion.",
													'".$_REQUEST['frecuencia_suministro'.$pfj]."')";
										$resulta=$dbconn->Execute($query);

										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al insertar en hc_posologia_horario_op5";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 5.";
											$dbconn->RollbackTrans();
											//caso especial que retorne true porque si ya existe elmedicamento no debe volver
											//a la forma de llenado si no a la forma principal
											return true;
										}
								}
					  }
			}
	$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
  $dbconn->CommitTrans();
	 $this->RegistrarSubmodulo($this->GetVersion());            
  return true;
}

//esta funcion se ejecuta cuando el medico despues de solicitar el medicamento va a
//justificarlo para asegurarnos de que los datos minimos para mas adelante insertar
//el medicamento existen.
//*
function Verificacion_Previa_Insertar_Medicamentos()
{
				$pfj=$this->frmPrefijo;
				/*if ($_REQUEST['cantidad'.$pfj] == '' OR $_REQUEST['via_administracion'.$pfj] == -1 OR
				$_REQUEST['dosis'.$pfj] == '' OR $_REQUEST['unidad_dosis'.$pfj] == -1)*/

				if ($_REQUEST['cantidad'.$pfj] == '' OR $_REQUEST['via_administracion'.$pfj] == -1 OR
				$_REQUEST['unidad_dosis'.$pfj] == -1 OR empty($_REQUEST['unidad_dosis'.$pfj]) OR
				$_REQUEST['unidad_dosis'.$pfj] == -1 )
				{
					if($_REQUEST['via_administracion'.$pfj] == '-1')
					{
							$this->frmError["via_administracion"]=1;
					}
					if($_REQUEST['cantidad'.$pfj] == '')
					{
							$this->frmError["cantidad"]=1;
					}

				/*	if($_REQUEST['dosis'.$pfj] == '')
					{
							$this->frmError["dosis"]=1;
					}*/

					if(($_REQUEST['unidad_dosis'.$pfj] == '-1') OR (empty($_REQUEST['unidad_dosis'.$pfj])))
					{
							$this->frmError["unidad_dosis"]=1;
					}

					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
					return false;
				}

				if($_REQUEST['dosis'.$pfj] != '')
					{
 						if (is_numeric($_REQUEST['dosis'.$pfj])==0)
							{
								$this->frmError["dosis"]=1;
								$this->frmError["MensajeError"]="DOSIS INVALIDA, DIGITE SOLO NUMEROS.";
								return false;
							}
					}

				if (empty($_REQUEST['opcion'.$pfj]))
				{
						$_REQUEST['opcion'.$pfj] = 0;
				}
				if ($_SESSION['SPIA'.$pfj]==1)
				{
					$_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd']= $_REQUEST['opcion_posol'.$pfj];
					$_SESSION['MEDICAMENTOS'.$pfj]['producto']=$_REQUEST['producto'.$pfj];
					$_SESSION['MEDICAMENTOS'.$pfj]['principio_activo']=$_REQUEST['principio_activo'.$pfj];
					$_SESSION['MEDICAMENTOS'.$pfj]['concentracion_forma_farmacologica']=$_REQUEST['concentracion_forma_farmacologica'.$pfj];
					$_SESSION['MEDICAMENTOS'.$pfj]['unidad_medida_medicamento_id']=$_REQUEST['unidad_medida_medicamento_id'.$pfj];
					$_SESSION['MEDICAMENTOS'.$pfj]['forma']=$_REQUEST['forma'.$pfj];
				}
				$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']=$_REQUEST['codigo_producto'.$pfj];
				$_SESSION['MEDICAMENTOS'.$pfj]['cantidad']=$_REQUEST['cantidad'.$pfj];
				$_SESSION['MEDICAMENTOS'.$pfj]['observacion']=$_REQUEST['observacion'.$pfj];
				$_SESSION['MEDICAMENTOS'.$pfj]['via_administracion_id']=$_REQUEST['via_administracion'.$pfj];
				$_SESSION['MEDICAMENTOS'.$pfj]['dosis']=$_REQUEST['dosis'.$pfj];
				$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']=$_REQUEST['unidad_dosis'.$pfj];
				$_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=$_REQUEST['opcion'.$pfj];
				//sw_paciente_no_pos y evolucion_id no se metio en session

				if ($_REQUEST['opcion'.$pfj] == '1')
			   {
						if (($_REQUEST['periocidad'.$pfj]=='-1') OR ($_REQUEST['tiempo'.$pfj]=='-1'))
						{
								$this->frmError["opcion1"]=1;
								$this->frmError["MensajeError"]="PARA OPCION 1 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
								return false;
						}
						else
						{
							$_SESSION['MEDICAMENTOS'.$pfj]['periocidad_id']=$_REQUEST['periocidad'.$pfj];
							$_SESSION['MEDICAMENTOS'.$pfj]['tiempo']=$_REQUEST['tiempo'.$pfj];
     				}
				 }
				if ($_REQUEST['opcion'.$pfj] == '2')
			   {
						if ($_REQUEST['duracion'.$pfj]=='-1')
							{
								$this->frmError["opcion2"]=1;
								$this->frmError["MensajeError"]="PARA OPCION 2 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
								return false;
							}
						else
							{
								$_SESSION['MEDICAMENTOS'.$pfj]['duracion_id']=$_REQUEST['duracion'.$pfj];
							}
					}
				if ($_REQUEST['opcion'.$pfj] == '3')
			      {
							if (empty($_REQUEST['momento'.$pfj]))
								{
										$this->frmError["opcion3"]=1;
										$this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE DILIGENCIAR LOS CAMPOS.";
										return false;
								}
							else
								{
									if ((!empty($_REQUEST['desayuno'.$pfj])) OR	(!empty($_REQUEST['almuerzo'.$pfj])) OR (!empty($_REQUEST['cena'.$pfj])))
									{
											$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_momento']=$_REQUEST['momento'.$pfj];
											$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_desayuno']=$_REQUEST['desayuno'.$pfj];
											$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_almuerzo']=$_REQUEST['almuerzo'.$pfj];
											$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_cena']=$_REQUEST['cena'.$pfj];
									}
									else
									{
											$this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE ESCOGER UNA COMIDA.";
											return false;
									}
								}
					  }
          if ($_REQUEST['opcion'.$pfj] == '4')
			      {
						  if (empty($_REQUEST['opH'.$pfj]))
							{
									$this->frmError["opcion4"]=1;
									$this->frmError["MensajeError"]="PARA OPCION 4 DE FRECUENCIA DEBE SELECCIONAR UNA HORA ESPECIFICA.";
									return false;
							}
							else
              {
									$i= 0;
									foreach($_REQUEST['opH'.$pfj] as $index=>$codigo)
									{
										$arreglo=explode(",",$codigo);
										$_SESSION['POSOLOGIA4'.$pfj]['hora_especifica'][$i]=$arreglo[0];
										$i++;
									}
									$_SESSION['POSOLOGIA4'.$pfj]['cantidad_hora_especifica'] = $i;
							}
						}
					if ($_REQUEST['opcion'.$pfj] == '5')
			      {
							if (empty($_REQUEST['frecuencia_suministro'.$pfj]))
								{
                    $this->frmError["opcion5"]=1;
										$this->frmError["MensajeError"]="PARA OPCION 5 DE FRECUENCIA DEBE ESCRIBIR EN LA FRECUENCIA DE SUMINISTRO.";
										return false;
								}
							else
								{
											$_SESSION['MEDICAMENTOS'.$pfj]['frecuencia_suministro']=$_REQUEST['frecuencia_suministro'.$pfj];
								}
					  }

	//cargo si existe una justificacion existente
		$justificacion_existente =$this->Consulta_Justificacion_Almacenada($_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']);
		if ($justificacion_existente)
		{
				$_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']	=$justificacion_existente[0][hc_justificaciones_no_pos_amb];
				$_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento']			=$justificacion_existente[0][duracion];
				$_SESSION['JUSTIFICACION'.$pfj]['dosis_dia']			=$justificacion_existente[0][dosis_dia];
				$_SESSION['JUSTIFICACION'.$pfj]['justificacion_solicitud']	=$justificacion_existente[0][justificacion];
				$_SESSION['JUSTIFICACION'.$pfj]['ventajas_medicamento']			=$justificacion_existente[0][ventajas_medicamento];
				$_SESSION['JUSTIFICACION'.$pfj]['ventajas_tratamiento']			=$justificacion_existente[0][ventajas_tratamiento];
				$_SESSION['JUSTIFICACION'.$pfj]['precauciones']							=$justificacion_existente[0][precauciones];
				$_SESSION['JUSTIFICACION'.$pfj]['controles_evaluacion_efectividad']=$justificacion_existente[0][controles_evaluacion_efectividad];
				$_SESSION['JUSTIFICACION'.$pfj]['tiempo_respuesta_esperado']=$justificacion_existente[0][tiempo_respuesta_esperado];
				$_SESSION['JUSTIFICACION'.$pfj]['sw_riesgo_inminente']			=$justificacion_existente[0][sw_riesgo_inminente];
				$_SESSION['JUSTIFICACION'.$pfj]['riesgo_inminente']					=$justificacion_existente[0][riesgo_inminente];
				$_SESSION['JUSTIFICACION'.$pfj]['sw_agotadas_posibilidades_existentes']=$justificacion_existente[0][sw_agotadas_posibilidades_existentes] ;
				$_SESSION['JUSTIFICACION'.$pfj]['sw_homologo_pos']					=$justificacion_existente[0][sw_homologo_pos] ;
				$_SESSION['JUSTIFICACION'.$pfj]['sw_comercializacion_pais']	=$justificacion_existente[0][sw_comercializacion_pais];
				$_SESSION['JUSTIFICACION'.$pfj]['descripcion_caso_clinico']	=$justificacion_existente[0][descripcion_caso_clinico];
				$_SESSION['JUSTIFICACION'.$pfj]['sw_existe_alternativa_pos']=$justificacion_existente[0][sw_existe_alternativa_pos];
				if($justificacion_existente[0][sw_existe_alternativa_pos]=='1')
				{
					$alternativas_pos =$this->Consulta_Alternativas_Pos($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']);
					for ($j=1;$j<3;$j++)
								{
										$_SESSION['JUSTIFICACION'.$pfj]['medicamento_pos'.$j]						=$alternativas_pos[$j-1][medicamento_pos];
										$_SESSION['JUSTIFICACION'.$pfj]['principio_activo_pos'.$j]			=$alternativas_pos[$j-1][principio_activo_pos];
										$_SESSION['JUSTIFICACION'.$pfj]['dosis_dia_pos'.$j]							=$alternativas_pos[$j-1][dosis_dia_pos];
										$_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento_pos'.$j]	=$alternativas_pos[$j-1][duracion_tratamiento_pos];
										$_SESSION['JUSTIFICACION'.$pfj]['sw_no_mejoria'.$j]							=$alternativas_pos[$j-1][sw_no_mejoria];
										$_SESSION['JUSTIFICACION'.$pfj]['sw_reaccion_secundaria'.$j]		=$alternativas_pos[$j-1][sw_reaccion_secundaria];
										$_SESSION['JUSTIFICACION'.$pfj]['reaccion_secundaria'.$j]				=$alternativas_pos[$j-1][reaccion_secundaria];
										$_SESSION['JUSTIFICACION'.$pfj]['sw_contraindicacion'.$j]				=$alternativas_pos[$j-1][sw_contraindicacion];
										$_SESSION['JUSTIFICACION'.$pfj]['contraindicacion'.$j]					=$alternativas_pos[$j-1][contraindicacion];
										$_SESSION['JUSTIFICACION'.$pfj]['otras'.$j]											=$alternativas_pos[$j-1][otras];
								}
				}
		}
		else
		{
		//cargo la plantilla del medicamento que se va a justificar
			$plantilla =$this->Consulta_Plantillas_Justificacion($_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']);
			if ($plantilla)
			{
					$_SESSION['JUSTIFICACION'.$pfj]['justificacion_solicitud']	=$plantilla[0][justificacion];
					$_SESSION['JUSTIFICACION'.$pfj]['ventajas_medicamento']			=$plantilla[0][ventajas_medicamento];
					$_SESSION['JUSTIFICACION'.$pfj]['ventajas_tratamiento']			=$plantilla[0][ventajas_tratamiento];
					$_SESSION['JUSTIFICACION'.$pfj]['precauciones']							=$plantilla[0][precauciones];
					$_SESSION['JUSTIFICACION'.$pfj]['controles_evaluacion_efectividad']=$plantilla[0][controles_evaluacion_efectividad];
					$_SESSION['JUSTIFICACION'.$pfj]['tiempo_respuesta_esperado']=$plantilla[0][tiempo_respuesta_esperado];
					$_SESSION['JUSTIFICACION'.$pfj]['sw_riesgo_inminente']			=$plantilla[0][sw_riesgo_inminente];
					$_SESSION['JUSTIFICACION'.$pfj]['riesgo_inminente']					=$plantilla[0][riesgo_inminente];
					$_SESSION['JUSTIFICACION'.$pfj]['sw_agotadas_posibilidades_existentes']=$plantilla[0][sw_agotadas_posibilidades_existentes] ;
					$_SESSION['JUSTIFICACION'.$pfj]['sw_homologo_pos']					=$plantilla[0][sw_homologo_pos] ;
					$_SESSION['JUSTIFICACION'.$pfj]['sw_comercializacion_pais']	=$plantilla[0][sw_comercializacion_pais];
			}
		}

		//cargo la sesion de diagnosticos de ingreso
		if(empty($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']))
		{
			if (empty($_SESSION['DIAGNOSTICOS'.$pfj]))
			{
				$diag =$this->Diagnosticos_de_Ingreso();
				if ($diag)
				{
					for($j=0;$j<sizeof($diag);$j++)
					{
						$_SESSION['DIAGNOSTICOS'.$pfj][$diag[$j][diagnostico_id]]= $diag[$j][diagnostico_nombre];
					}
				}
			}
		}
		else
		{
			if (empty($_SESSION['DIAGNOSTICOS'.$pfj]))
			{
				$diag =$this->Consulta_Diagnosticos_Justificacion($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']);
				if ($diag)
				{
					for($j=0;$j<sizeof($diag);$j++)
					{
						$_SESSION['DIAGNOSTICOS'.$pfj][$diag[$j][diagnostico_id]]= $diag[$j][diagnostico_nombre];
					}
				}
			}

		}
	return true;
}

//clzc - si - *
function Insertar_Justificacion_No_Pos()
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
    $dbconn->BeginTrans();

		/*if ($_REQUEST['duracion_tratamiento'.$pfj] == '' OR $_REQUEST['dosis_dia'.$pfj] == '' OR
				(empty($_SESSION['DIAGNOSTICOS'.$pfj])))*/
		if ($_REQUEST['duracion_tratamiento'.$pfj] == '' OR
				(empty($_SESSION['DIAGNOSTICOS'.$pfj])))
			{
				if($_REQUEST['duracion_tratamiento'.$pfj] == '')
				{
						$this->frmError["duracion_tratamiento"]=1;
				}
				/*if($_REQUEST['dosis_dia'.$pfj] == '')
				{
						$this->frmError["dosis_dia"]=1;
				}*/

				if((empty($_SESSION['DIAGNOSTICOS'.$pfj])))
				{
						$this->frmError["diagnostico_id"] = 1;
				}

				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				return false;
			}

			if($_SESSION['MEDICAMENTOS'.$pfj]['dosis']=='')
			{
				$_SESSION['MEDICAMENTOS'.$pfj]['dosis']='NULL';
			}

			//controlando la insercion de la via
			$via = $_SESSION['MEDICAMENTOS'.$pfj]['via_administracion_id'];
			if($via == '')
			{
					$via = 'NULL';
			}
			else
			{
        $via ="'$via'";
			}
			//fin del control

//.....................................
//PROCESO PARA LA INSERCCION DEL MEDICAMENTO NO POS COMO TAL
//.....................................
			$no_pos_paciente = '0';
			if ($_SESSION['SPIA'.$pfj]==1)
			{
						$query="UPDATE hc_medicamentos_recetados_amb SET
						cantidad = ".$_SESSION['MEDICAMENTOS'.$pfj]['cantidad'].",
						observacion = '".$_SESSION['MEDICAMENTOS'.$pfj]['observacion']."',
						sw_paciente_no_pos = '".$no_pos_paciente."',
						via_administracion_id = ".$via.",
						dosis =  ".$_SESSION['MEDICAMENTOS'.$pfj]['dosis'].",
						unidad_dosificacion = '".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']."',
						tipo_opcion_posologia_id =  ".$_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']."
						WHERE	codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
			}
			else
			{
	 	 				$query="INSERT INTO hc_medicamentos_recetados_amb
						(codigo_producto, evolucion_id, cantidad, observacion, sw_paciente_no_pos, via_administracion_id,
						dosis, unidad_dosificacion, tipo_opcion_posologia_id)
						VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',	".$this->evolucion.",
						".$_SESSION['MEDICAMENTOS'.$pfj]['cantidad'].",
						'".$_SESSION['MEDICAMENTOS'.$pfj]['observacion']."',
						'".$no_pos_paciente."', ".$via.",
						".$_SESSION['MEDICAMENTOS'.$pfj]['dosis'].",
						'".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']."',
						".$_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'].")";
			}
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al insertar en hc_medicamentos_recetados_amb";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]="EL MEDICAMENTO YA HA SIDO FORMULADO EN ESTA EVOLUCION.";
					$dbconn->RollbackTrans();
					return false;
			}
			else
			{
//proceso que solo se ejecuta si es una modificacion
					$query= '';
					if ($_SESSION['SPIA'.$pfj]==1)
					{
							if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 1)
							{
											$query ="DELETE FROM hc_posologia_horario_op1
											WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
							}
							if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 2)
							{
											$query ="DELETE FROM hc_posologia_horario_op2
											WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
							}
							if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 3)
							{
											$query ="DELETE FROM hc_posologia_horario_op3
											WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
							}
							if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 4)
							{
											$query ="DELETE FROM hc_posologia_horario_op4
											WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
							}
							if ($_SESSION['MEDICAMENTOS'.$pfj]['posologia_bd'] == 5)
							{
											$query ="DELETE FROM hc_posologia_horario_op5
											WHERE codigo_producto = '".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."' AND evolucion_id = ".$this->evolucion."";
							}

							$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
								$dbconn->RollbackTrans();
								return false;
							}
					}
//fin del proceo de borrado

				if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '1')
			   {
						$query="INSERT INTO hc_posologia_horario_op1
												(codigo_producto, evolucion_id, periocidad_id, tiempo)
												VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
												".$this->evolucion.",	".$_SESSION['MEDICAMENTOS'.$pfj]['periocidad_id'].",
												'".$_SESSION['MEDICAMENTOS'.$pfj]['tiempo']."')";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
         					$this->error = "Error al insertar en hc_posologia_horario_op1";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 1.";
									$dbconn->RollbackTrans();
									return false;
						}
				 }

				if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '2')
			   {
								$query="INSERT INTO hc_posologia_horario_op2
												(codigo_producto, evolucion_id, duracion_id)
												VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
												".$this->evolucion.",	'".$_SESSION['MEDICAMENTOS'.$pfj]['duracion_id']."')";
												$resulta=$dbconn->Execute($query);

									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al insertar en hc_posologia_horario_op2";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 2.";
										$dbconn->RollbackTrans();
										return false;
									}
					}
				if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '3')
			      {
											$query="INSERT INTO hc_posologia_horario_op3
														(codigo_producto, evolucion_id, sw_estado_momento,
														sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena)
														VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
														 ".$this->evolucion.",
														'".$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_momento']."',
														'".$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_desayuno']."',
														'".$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_almuerzo']."',
														'".$_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_cena']."')";
														$resulta=$dbconn->Execute($query);

											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al insertar en hc_posologia_horario_op3";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 3.";
												$dbconn->RollbackTrans();
												return false;
											}
					  }
        if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '4')
			      {
						  for($i=0;$i<$_SESSION['POSOLOGIA4'.$pfj]['cantidad_hora_especifica'];$i++)
									{
										$query="INSERT INTO hc_posologia_horario_op4
														(codigo_producto, evolucion_id, hora_especifica)
														VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
														 ".$this->evolucion.", '".$_SESSION['POSOLOGIA4'.$pfj]['hora_especifica'][$i]."')";
										$resulta=$dbconn->Execute($query);

										if ($dbconn->ErrorNo() != 0)
											{
													$this->error = "Error al insertar en hc_posologia_horario_op4";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA HORA ESPECIFICA.";
													$dbconn->RollbackTrans();
													return false;
											}
									}

						}
					if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'] == '5')
			      {
										$query="INSERT INTO hc_posologia_horario_op5
													(codigo_producto, evolucion_id, frecuencia_suministro)
													VALUES ('".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
													".$this->evolucion.",
													'".$_SESSION['MEDICAMENTOS'.$pfj]['frecuencia_suministro']."')";
										$resulta=$dbconn->Execute($query);

										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al insertar en hc_posologia_horario_op5";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 5.";
											$dbconn->RollbackTrans();
											return false;
										}
						}
			 }
//....................................
//fin del proceso de inserccion del medicamento
//.....................................

//ojo este dato no estan llegando
$sw_existe_alternativa_pos = 1;
		if (empty($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']))
		{
			//realiza el id manual de la tabla
				$query1="SELECT nextval('hc_justificaciones_no_pos_amb_hc_justificaciones_no_pos_amb_seq')";
				$result=$dbconn->Execute($query1);
				$hc_justificaciones_no_pos_amb=$result->fields[0];
			//fin de la operacion

			 $query=	"INSERT INTO hc_justificaciones_no_pos_amb (hc_justificaciones_no_pos_amb,
							evolucion_id, codigo_producto, usuario_id_autoriza, duracion, dosis_dia,
							justificacion, ventajas_medicamento, ventajas_tratamiento, precauciones,
							controles_evaluacion_efectividad,	tiempo_respuesta_esperado, riesgo_inminente,
							sw_riesgo_inminente, sw_agotadas_posibilidades_existentes, sw_comercializacion_pais,
							sw_homologo_pos, descripcion_caso_clinico,
							sw_existe_alternativa_pos)
							VALUES (".$hc_justificaciones_no_pos_amb.", ".$this->evolucion.",
							'".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."',
							".$this->usuario_id.",
							'".$_REQUEST['duracion_tratamiento'.$pfj]."',
							'".$_REQUEST['dosis_dia'.$pfj]."',
							'".$_REQUEST['justificacion_solicitud'.$pfj]."',
							'".$_REQUEST['ventajas_medicamento'.$pfj]."',
							'".$_REQUEST['ventajas_tratamiento'.$pfj]."',
							'".$_REQUEST['precauciones'.$pfj]."',
							'".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."',
							'".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."',
							'".$_REQUEST['riesgo_inminente'.$pfj]."',
							'".$_REQUEST['sw_riesgo_inminente'.$pfj]."',
							'".$_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj]."',
							'".$_REQUEST['sw_comercializacion_pais'.$pfj]."',
							'".$_REQUEST['sw_homologo_pos'.$pfj]."',
							'".$_REQUEST['descripcion_caso_clinico'.$pfj]."',
							'".$sw_existe_alternativa_pos."')";
		}
		else
		{
				$query=	"UPDATE hc_justificaciones_no_pos_amb SET usuario_id_autoriza =".$this->usuario_id.",
							duracion = '".$_REQUEST['duracion_tratamiento'.$pfj]."',
							dosis_dia = 		'".$_REQUEST['dosis_dia'.$pfj]."',
							justificacion = '".$_REQUEST['justificacion_solicitud'.$pfj]."',
							ventajas_medicamento = '".$_REQUEST['ventajas_medicamento'.$pfj]."',
							ventajas_tratamiento = '".$_REQUEST['ventajas_tratamiento'.$pfj]."',
							precauciones = '".$_REQUEST['precauciones'.$pfj]."',
							controles_evaluacion_efectividad = '".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."',
							tiempo_respuesta_esperado = '".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."',
							riesgo_inminente = '".$_REQUEST['riesgo_inminente'.$pfj]."',
							sw_riesgo_inminente = '".$_REQUEST['sw_riesgo_inminente'.$pfj]."',
							sw_agotadas_posibilidades_existentes = '".$_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj]."',
							sw_comercializacion_pais = '".$_REQUEST['sw_comercializacion_pais'.$pfj]."',
							sw_homologo_pos = '".$_REQUEST['sw_homologo_pos'.$pfj]."',
							descripcion_caso_clinico = '".$_REQUEST['descripcion_caso_clinico'.$pfj]."',
							sw_existe_alternativa_pos = '".$sw_existe_alternativa_pos."' WHERE
							hc_justificaciones_no_pos_amb = ".$_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']."";
		}
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al insertar en hc_justificaciones_no_pos_amb";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR LA JUSTIFICACION";
			    $dbconn->RollbackTrans();
					return false;
			}
		else
			{
				if (!empty($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']))
				{
					 $hc_justificaciones_no_pos_amb =$_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb'];
					 $query=	"DELETE FROM hc_justificaciones_no_pos_respuestas_pos
									WHERE hc_justificaciones_no_pos_amb = ".$_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']."";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al insertar en hc_justificaciones_no_pos_amb";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR LA JUSTIFICACION";
						$dbconn->RollbackTrans();
						return false;
					}
				}

				for ($j=1;$j<3;$j++)
					{
							if(($_REQUEST['medicamento_pos'.$j.$pfj]) != '' OR ($_REQUEST['principio_activo_pos'.$j.$pfj] != ''))
							{
									 $query=	"INSERT INTO hc_justificaciones_no_pos_respuestas_pos
													(hc_justificaciones_no_pos_amb, medicamento_pos, principio_activo,
													dosis_dia_pos, duracion_pos, sw_no_mejoria, sw_reaccion_secundaria,
													reaccion_secundaria, sw_contraindicacion, contraindicacion, otras)
													VALUES (".$hc_justificaciones_no_pos_amb.",
													'".$_REQUEST['medicamento_pos'.$j.$pfj]."',
													'".$_REQUEST['principio_activo_pos'.$j.$pfj]."',
													'".$_REQUEST['dosis_dia_pos'.$j.$pfj]."',
													'".$_REQUEST['duracion_tratamiento_pos'.$j.$pfj]."',
													'".$_REQUEST['sw_no_mejoria'.$j.$pfj]."',
													'".$_REQUEST['sw_reaccion_secundaria'.$j.$pfj]."',
													'".$_REQUEST['reaccion_secundaria'.$j.$pfj]."',
													'".$_REQUEST['sw_contraindicacion'.$j.$pfj]."',
													'".$_REQUEST['contraindicacion'.$j.$pfj]."',
													'".$_REQUEST['otras'.$j.$pfj]."')";
									$resulta=$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al insertar en hc_justificaciones_no_pos_amb";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR LA JUSTIFICACION3";
										$dbconn->RollbackTrans();
										return false;
									}
							}
					}
						if (!empty($_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']))
						{
						 	$query=	"DELETE FROM hc_justificaciones_no_pos_amb_diagnostico
										WHERE hc_justificaciones_no_pos_amb = ".$_SESSION['JUSTIFICACION'.$pfj]['hc_justificaciones_no_pos_amb']."";
							$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al insertar en hc_justificaciones_no_pos_amb";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR LA JUSTIFICACION4";
								$dbconn->RollbackTrans();
								return false;
							}
						}

						foreach ($_SESSION['DIAGNOSTICOS'.$pfj] as $k=>$v)
						{
								 $query="INSERT INTO hc_justificaciones_no_pos_amb_diagnostico
								(hc_justificaciones_no_pos_amb, diagnostico_id)
								VALUES (".$hc_justificaciones_no_pos_amb.",'".$k."')";
							  $resulta=$dbconn->Execute($query);

								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al insertar en hc_justificaciones_no_pos_amb_diagnostico";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LOS DIAGNOSTICOS";
									$dbconn->RollbackTrans();
									return false;
								}
						}

			}

	$this->frmError["MensajeError"]="JUSTIFICACION GUARDADA SATISFACTORIAMENTE";
	$dbconn->CommitTrans();
	unset($_SESSION['SPIA'.$pfj]);
	 $this->RegistrarSubmodulo($this->GetVersion());            
  return true;
}

function Diagnosticos_de_Ingreso()
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select b.diagnostico_id, b.diagnostico_nombre from hc_diagnosticos_ingreso as a,
		 				diagnosticos as b where evolucion_id = ".$this->evolucion." AND a.tipo_diagnostico_id = b.diagnostico_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los diagnosticos asignados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}

//*
function ConsultaGeneralModificacionMedicamento($codigo_producto)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select a.evolucion_id, a.codigo_producto, a.sw_paciente_no_pos, case when k.sw_pos = 1 then 'POS'
		else 'NO POS' end as item, a.cantidad, a.dosis, a.via_administracion_id, m.nombre as via,
		a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id, h.descripcion as producto,
		c.descripcion as principio_activo, h.contenido_unidad_venta, l.descripcion, n.descripcion as forma,
		n.unidad_dosificacion as unidad_dosificacion_forma, k.concentracion_forma_farmacologica,
		k.unidad_medida_medicamento_id from hc_medicamentos_recetados_amb as a left join
		hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
		inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k, unidades as l,
		inv_med_cod_forma_farmacologica as n where a.evolucion_id = ".$this->evolucion." and
		k.cod_principio_activo = c.cod_principio_activo and h.codigo_producto = k.codigo_medicamento
		and a.codigo_producto = h.codigo_producto and h.codigo_producto = a.codigo_producto and
		h.unidad_id = l.unidad_id and k.cod_forma_farmacologica = n.cod_forma_farmacologica and
		a.codigo_producto = '$codigo_producto' ";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los diagnosticos asignados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}

//clzc - si - *
function Consulta_Solicitud_Medicamentos()
{
		$pfj=$this->frmPrefijo;
    list($dbconnect) = GetDBconn();

//query con la unidad de venta y la via de admon
//este query esta ok jul/23/04 pero lista solo lo de una evolucion
		 $query1= "select k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS' else 'NO POS' end as item,
		 a.codigo_producto, a.sw_paciente_no_pos, a.cantidad, a.dosis, m.nombre as via,
		 a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id, h.descripcion
		 as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
		 l.descripcion, a.evolucion_id from hc_medicamentos_recetados_amb as a left join
		 hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
		 inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
		 unidades as l

		 where a.evolucion_id = ".$this->evolucion." and

		 k.cod_principio_activo = c.cod_principio_activo and
		 h.codigo_producto = k.codigo_medicamento and
		 a.codigo_producto = h.codigo_producto and
		 h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id order
		 by k.sw_pos, a.sw_paciente_no_pos";

//igual que el query anterior pero lista todos las evoluciones de un ingreso
		  $query= "select k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
			else 'NO POS' end as item, a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
			a.dosis, m.nombre as via, a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
			h.descripcion as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
		  l.descripcion, a.evolucion_id from hc_medicamentos_recetados_amb as a left join
		  hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
		  inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
		  unidades as l,

		  hc_evoluciones n

			where n.ingreso = ".$this->ingreso."
		  and a.evolucion_id = n.evolucion_id and

		  k.cod_principio_activo = c.cod_principio_activo and
		  h.codigo_producto = k.codigo_medicamento and
		  a.codigo_producto = h.codigo_producto and
		  h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
			order  by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}
//*
function Consulta_Solicitud_Medicamentos_Posologia($codigo_producto, $tipo_posologia, $evolucion_id)
{
		$pfj=$this->frmPrefijo;
    list($dbconnect) = GetDBconn();
		$query == '';
		if ($tipo_posologia == 1)
		{
				$query= "select periocidad_id, tiempo from hc_posologia_horario_op1 where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 2)
		{
				$query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2 as a, hc_horario as b where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto' and a.duracion_id = b.duracion_id";
		}
		if ($tipo_posologia == 3)
		{
    		$query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3 where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 4)
		{
    		$query= "select hora_especifica from hc_posologia_horario_op4 where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 5)
		{
    		$query= "select frecuencia_suministro from hc_posologia_horario_op5 where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}

		if ($query!='')
		{
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al buscar en la consulta de medicamentos recetados";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
				}
				else
				{
					if ($tipo_posologia != 4)
					{
						while (!$result->EOF)
						{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
					}
					else
					{
						while (!$result->EOF)
						{
							$vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
					}
				}
				//$result->Close();
		}
	  return $vector;
}


//clzc - si - *
function Eliminar_Medicamento_Solicitada($codigo_producto, $opcion_posologia)
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$query= '';
		if ($opcion_posologia == 1)
		{
						$query ="DELETE FROM hc_posologia_horario_op1
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}
		if ($opcion_posologia == 2)
		{
						$query ="DELETE FROM hc_posologia_horario_op2
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}
		if ($opcion_posologia == 3)
		{
						$query ="DELETE FROM hc_posologia_horario_op3
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}
		if ($opcion_posologia == 4)
		{
						$query ="DELETE FROM hc_posologia_horario_op4
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}
		if ($opcion_posologia == 5)
		{
						$query ="DELETE FROM hc_posologia_horario_op5
						WHERE codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		}

		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
				 $query="DELETE FROM hc_medicamentos_recetados_amb
							WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
          $dbconn->RollbackTrans();
					return false;
				}
		}
	$dbconn->CommitTrans();
	$this->frmError["MensajeError"]="MEDICAMENTO ELIMINADO.";

 return true;
}

//*
function Modificacion_Justificacion_Medicamentos_No_Pos($hc_justificaciones_no_pos_amb)
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
    $dbconn->BeginTrans();

		/*if ($_REQUEST['duracion_tratamiento'.$pfj] == '' OR $_REQUEST['dosis_dia'.$pfj] == '' OR
				(empty($_SESSION['DIAGNOSTICOSM'.$pfj])))*/
		if ($_REQUEST['duracion_tratamiento'.$pfj] == '' OR
				(empty($_SESSION['DIAGNOSTICOSM'.$pfj])))
			{
				if($_REQUEST['duracion_tratamiento'.$pfj] == '')
				{
						$this->frmError["duracion_tratamiento"]=1;
				}
				/*if($_REQUEST['dosis_dia'.$pfj] == '')
				{
						$this->frmError["dosis_dia"]=1;
				}*/

				if((empty($_SESSION['DIAGNOSTICOSM'.$pfj])))
				{
						$this->frmError["diagnostico_id"] = 1;
				}

				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				return false;
			}

//ojo este dato no estan llegando
		$sw_existe_alternativa_pos = 1;
	   $query=	"UPDATE hc_justificaciones_no_pos_amb SET
						usuario_id_autoriza = ".$this->usuario_id.",
						duracion = '".$_REQUEST['duracion_tratamiento'.$pfj]."',
						dosis_dia = '".$_REQUEST['dosis_dia'.$pfj]."',
						justificacion = '".$_REQUEST['justificacion_solicitud'.$pfj]."',
						ventajas_medicamento = '".$_REQUEST['ventajas_medicamento'.$pfj]."',
						ventajas_tratamiento = '".$_REQUEST['ventajas_tratamiento'.$pfj]."',
						precauciones = '".$_REQUEST['precauciones'.$pfj]."',
						controles_evaluacion_efectividad = '".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."',
						tiempo_respuesta_esperado = '".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."',
						riesgo_inminente = '".$_REQUEST['riesgo_inminente'.$pfj]."',
						sw_riesgo_inminente = '".$_REQUEST['sw_riesgo_inminente'.$pfj]."',
						sw_agotadas_posibilidades_existentes = '".$_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj]."',
						sw_comercializacion_pais = '".$_REQUEST['sw_comercializacion_pais'.$pfj]."',
						sw_homologo_pos = '".$_REQUEST['sw_homologo_pos'.$pfj]."',
						descripcion_caso_clinico = '".$_REQUEST['descripcion_caso_clinico'.$pfj]."',
						sw_existe_alternativa_pos = '".$sw_existe_alternativa_pos."' WHERE
						hc_justificaciones_no_pos_amb = ".$hc_justificaciones_no_pos_amb."";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al actualizar en hc_justificaciones_no_pos_amb";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]="NO HA SIDO POSIBLE MODIFICAR LA JUSTIFICACION";
			    $dbconn->RollbackTrans();
					return false;
			}
		else
			{
				 $query = "DELETE FROM hc_justificaciones_no_pos_respuestas_pos
									WHERE hc_justificaciones_no_pos_amb = ".$hc_justificaciones_no_pos_amb."";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al borrar los medicamentos no pos previos en hc_justificaciones_no_pos_respuestas_pos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					for ($j=1;$j<3;$j++)
					{
							if(($_REQUEST['medicamento_pos'.$j.$pfj]) != '' OR ($_REQUEST['principio_activo_pos'.$j.$pfj] != ''))
							{
								 $query=	"INSERT INTO hc_justificaciones_no_pos_respuestas_pos
												(hc_justificaciones_no_pos_amb, medicamento_pos, principio_activo,
												dosis_dia_pos, duracion_pos, sw_no_mejoria, sw_reaccion_secundaria,
												reaccion_secundaria, sw_contraindicacion, contraindicacion, otras)
												VALUES (".$hc_justificaciones_no_pos_amb.",
												'".$_REQUEST['medicamento_pos'.$j.$pfj]."',
												'".$_REQUEST['principio_activo_pos'.$j.$pfj]."',
												'".$_REQUEST['dosis_dia_pos'.$j.$pfj]."',
												'".$_REQUEST['duracion_tratamiento_pos'.$j.$pfj]."',
												'".$_REQUEST['sw_no_mejoria'.$j.$pfj]."',
												'".$_REQUEST['sw_reaccion_secundaria'.$j.$pfj]."',
												'".$_REQUEST['reaccion_secundaria'.$j.$pfj]."',
												'".$_REQUEST['sw_contraindicacion'.$j.$pfj]."',
												'".$_REQUEST['contraindicacion'.$j.$pfj]."',
												'".$_REQUEST['otras'.$j.$pfj]."')";
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al insertar en hc_justificaciones_no_pos_respuestas_pos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->frmError["MensajeError"]="NO HA SIDO POSIBLE GUARDAR ALTERNATIVAS POS PREVIOS";
									$dbconn->RollbackTrans();
									return false;
								}
							}
					}
				}
				 $query = "DELETE FROM hc_justificaciones_no_pos_amb_diagnostico
									WHERE hc_justificaciones_no_pos_amb = ".$hc_justificaciones_no_pos_amb."";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al borrar los diagnosticos de hc_justificaciones_no_pos_amb_diagnostico";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					foreach ($_SESSION['DIAGNOSTICOSM'.$pfj] as $k=>$v)
					{
						 $query="INSERT INTO hc_justificaciones_no_pos_amb_diagnostico
										(hc_justificaciones_no_pos_amb, diagnostico_id)
										VALUES (".$hc_justificaciones_no_pos_amb.",'".$k."')";
						$resulta=$dbconn->Execute($query);

						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al actualizar en hc_justificaciones_no_pos_amb_diagnostico";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->frmError["MensajeError"]="ERROR EN LA ACTUALIZACION DE LOS DIAGNOSTICOS";
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
			}
	$this->frmError["MensajeError"]="MODIFICACIONES DE LA JUSTIFICACION GUARDADAS SATISFACTORIAMENTE";
	$dbconn->CommitTrans();
	 $this->RegistrarSubmodulo($this->GetVersion());            
  return true;
}

//clzc - si - *
function Modificar_Medicamento_Solicitado($codigo_producto, $opcion_posol)
{

		//inserta un 1 si es pos o si es no pos y selecciono el check
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
    $dbconn->BeginTrans();

		/*if ($_REQUEST['cantidad'.$pfj] == '' OR $_REQUEST['via_administracion'.$pfj] == -1 OR
		$_REQUEST['dosis'.$pfj] == '' OR $_REQUEST['unidad_dosis'.$pfj] == -1)*/

		if ($_REQUEST['cantidad'.$pfj] == '' OR $_REQUEST['via_administracion'.$pfj] == -1 OR
				$_REQUEST['unidad_dosis'.$pfj] == -1 OR empty($_REQUEST['unidad_dosis'.$pfj]))
		{
			if($_REQUEST['via_administracion'.$pfj] == '-1')
			 {
		     $this->frmError["via_administracion"]=1;
			 }
	   if($_REQUEST['cantidad'.$pfj] == '')
			 {
		     $this->frmError["cantidad"]=1;
			 }
/*
			if($_REQUEST['dosis'.$pfj] == '')
			 {
		      $this->frmError["dosis"]=1;
			 }*/

			if(($_REQUEST['unidad_dosis'.$pfj] == '-1') OR (empty($_REQUEST['unidad_dosis'.$pfj])))
			 {
		      $this->frmError["unidad_dosis"]=1;
			 }
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			return false;
		}

		if($_REQUEST['dosis'.$pfj] == '')
		{
				$_REQUEST['dosis'.$pfj]='NULL';
		}
		else
		{
 				if (is_numeric($_REQUEST['dosis'.$pfj])==0)
				{
					$this->frmError["dosis"]=1;
					$this->frmError["MensajeError"]="DOSIS INVALIDA, DIGITE SOLO NUMEROS.";
					return false;
				}
		}

		//controlando la insercion de la via
			$via = $_REQUEST['via_administracion'.$pfj];
			if($via == '')
			{
					$via = 'NULL';
			}
			else
			{
        $via ="'$via'";
			}
			//fin del control

			//OBLIGATORIEDAD DE LA FRECUENCIA
			if($_REQUEST['opcion'.$pfj]=='')
				 {
				 		$this->frmError["frecuencia"]=1;
						$this->frmError["MensajeError"]="SELECCIONE UNA OPCION DE FRECUENCIA PARA LA FORMULACION.";
						return false;
				 }
			//fin de obligatoriedad

		if ($_REQUEST['opcion'.$pfj]=='')
		{
			$_REQUEST['opcion'.$pfj] = 0;
		}
	 	 $query="UPDATE hc_medicamentos_recetados_amb SET
						cantidad = ".$_REQUEST['cantidad'.$pfj].",
						observacion = '".$_REQUEST['observacion'.$pfj]."',
						sw_paciente_no_pos = '".$_REQUEST['no_pos_paciente'.$pfj]."',
						via_administracion_id = ".$via.",
						dosis = ".$_REQUEST['dosis'.$pfj].",
						unidad_dosificacion = '".$_REQUEST['unidad_dosis'.$pfj]."',
						tipo_opcion_posologia_id = ".$_REQUEST['opcion'.$pfj]." WHERE
						codigo_producto = '$codigo_producto' AND evolucion_id = ".$this->evolucion."";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al actualizar en hc_medicamentos_recetados_amb";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]="NO SE PUDO ALMACENAR LAS MODIFICACIONES.";
					$dbconn->RollbackTrans();
						//caso especial que retorne true porque si ya existe elmedicamento no debe volver
						//a la forma de llenado si no a la forma principal
					return true;
			}
		else
			{
			    $query= '';
					if ($opcion_posol == 1)
					{
									$query_posologia ="DELETE FROM hc_posologia_horario_op1
									WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
					}
					if ($opcion_posol == 2)
					{
									$query_posologia ="DELETE FROM hc_posologia_horario_op2
									WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
					}
					if ($opcion_posol == 3)
					{
									$query_posologia ="DELETE FROM hc_posologia_horario_op3
									WHERE codigo_producto = '".$codigo_producto."'' AND evolucion_id =".$this->evolucion."";
					}
					if ($opcion_posol == 4)
					{
									$query_posologia ="DELETE FROM hc_posologia_horario_op4
									WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
					}
					if ($opcion_posol == 5)
					{
									$query_posologia ="DELETE FROM hc_posologia_horario_op5
									WHERE codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
					}

					if ($query_posologia== '')
					{
						$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
						return false;
					}
					else
					{
						if ($_REQUEST['opcion'.$pfj] == '1')
			   			{
								if (($_REQUEST['periocidad'.$pfj]=='-1') OR ($_REQUEST['tiempo'.$pfj]=='-1'))
								{
									$this->frmError["opcion1"]=1;
									$this->frmError["MensajeError"]="PARA OPCION 1 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
									return false;
								}
								else
								{
										$resulta=$dbconn->Execute($query_posologia);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
											$dbconn->RollbackTrans();
											return false;
										}
										else
										{
											$query="INSERT INTO hc_posologia_horario_op1
															(codigo_producto, evolucion_id, periocidad_id, tiempo)
															VALUES ('".$codigo_producto."', ".$this->evolucion.",
															".$_REQUEST['periocidad'.$pfj].", '".$_REQUEST['tiempo'.$pfj]."')";
											$resulta=$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al insertar en hc_posologia_horario_op1";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 1.";
												$dbconn->RollbackTrans();
												//caso especial que retorne true porque si ya existe elmedicamento no debe volver
												//a la forma de llenado si no a la forma principal
												return true;
											}
										}
				 			  }
							}
						if ($_REQUEST['opcion'.$pfj] == '2')
							{
								if ($_REQUEST['duracion'.$pfj]=='-1')
									{
										$this->frmError["opcion2"]=1;
										$this->frmError["MensajeError"]="PARA OPCION 2 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
										return false;
									}
								else
									{
											$resulta=$dbconn->Execute($query_posologia);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
												$dbconn->RollbackTrans();
												return false;
											}
											else
											{
												$query="INSERT INTO hc_posologia_horario_op2
																(codigo_producto, evolucion_id, duracion_id)
																VALUES ('".$codigo_producto."', ".$this->evolucion.",
																'".$_REQUEST['duracion'.$pfj]."')";
																$resulta=$dbconn->Execute($query);

													if ($dbconn->ErrorNo() != 0)
													{
														$this->error = "Error al insertar en hc_posologia_horario_op2";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 2.";
														$dbconn->RollbackTrans();
														//caso especial que retorne true porque si ya existe elmedicamento no debe volver
														//a la forma de llenado si no a la forma principal
														return true;
													}
											}
									}
							}

						if ($_REQUEST['opcion'.$pfj] == '3')
							{
								if (empty($_REQUEST['momento'.$pfj]))
									{
											$this->frmError["opcion3"]=1;
											$this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE DILIGENCIAR LOS CAMPOS.";
											return false;
									}
								else
									{
										if ((!empty($_REQUEST['desayuno'.$pfj])) OR	(!empty($_REQUEST['almuerzo'.$pfj])) OR (!empty($_REQUEST['cena'.$pfj])))
										{
											$resulta=$dbconn->Execute($query_posologia);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
												$dbconn->RollbackTrans();
												return false;
											}
											else
											{
													$query="INSERT INTO hc_posologia_horario_op3
																(codigo_producto, evolucion_id, sw_estado_momento,
																sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena)
																VALUES ('".$codigo_producto."', ".$this->evolucion.",
																'".$_REQUEST['momento'.$pfj]."', '".$_REQUEST['desayuno'.$pfj]."',
																'".$_REQUEST['almuerzo'.$pfj]."', '".$_REQUEST['cena'.$pfj]."')";
																$resulta=$dbconn->Execute($query);

													if ($dbconn->ErrorNo() != 0)
													{
														$this->error = "Error al insertar en hc_posologia_horario_op3";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 3.";
														$dbconn->RollbackTrans();
														//caso especial que retorne true porque si ya existe elmedicamento no debe volver
														//a la forma de llenado si no a la forma principal
														return true;
													}
											}
										}
										else
										{
												$this->frmError["MensajeError"]="PARA OPCION 3 DE FRECUENCIA DEBE ESCOGER UNA COMIDA.";
												return false;
										}
									}
							}
          	if ($_REQUEST['opcion'.$pfj] == '4')
							{
								if (empty($_REQUEST['opH'.$pfj]))
								{
										$this->frmError["opcion4"]=1;
										$this->frmError["MensajeError"]="PARA OPCION 4 DE FRECUENCIA DEBE SELECCIONAR UNA HORA ESPECIFICA.";
										return false;
								}
								else
								{
									$resulta=$dbconn->Execute($query_posologia);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
										$dbconn->RollbackTrans();
										return false;
									}
									else
									{
										foreach($_REQUEST['opH'.$pfj] as $index=>$codigo)
										{
											$arreglo=explode(",",$codigo);
											$query="INSERT INTO hc_posologia_horario_op4
															(codigo_producto, evolucion_id, hora_especifica)
															VALUES ('".$codigo_producto."',
															".$this->evolucion.", '".$arreglo[0]."')";
											$resulta=$dbconn->Execute($query);

											if ($dbconn->ErrorNo() != 0)
												{
														$this->error = "Error al insertar en hc_posologia_horario_op4";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA HORA ESPECIFICA.";
														$dbconn->RollbackTrans();
														//caso especial que retorne true porque si ya existe elmedicamento no debe volver
														//a la forma de llenado si no a la forma principal
														return true;
												}
										}
									}
								}
							}
						if ($_REQUEST['opcion'.$pfj] == '5')
			      {
							if (empty($_REQUEST['frecuencia_suministro'.$pfj]))
								{
										$this->frmError["opcion5"]=1;
										$this->frmError["MensajeError"]="PARA OPCION 5 DE FRECUENCIA DEBE ESCRIBIR EN LA FRECUENCIA DE SUMINISTRO.";
										return false;
								}
							else
								{
										$resulta=$dbconn->Execute($query_posologia);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR";
											$dbconn->RollbackTrans();
											return false;
										}
										else
										{
											$query="INSERT INTO hc_posologia_horario_op5
															(codigo_producto, evolucion_id, frecuencia_suministro)
															VALUES ('".$codigo_producto."', ".$this->evolucion.",
															'".$_REQUEST['frecuencia_suministro'.$pfj]."')";
												$resulta=$dbconn->Execute($query);

												if ($dbconn->ErrorNo() != 0)
												{
													$this->error = "Error al insertar en hc_posologia_horario_op5";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$this->frmError["MensajeError"]="ERROR EN LA INSERCION DE LA FRECUENCIA OPCION 5.";
													$dbconn->RollbackTrans();
													//caso especial que retorne true porque si ya existe elmedicamento no debe volver
													//a la forma de llenado si no a la forma principal
													return true;
												}
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


function Unidades_Dosificacion()
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select unidad_dosificacion from hc_unidades_dosificacion";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los diagnosticos asignados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}

//cor - clzc - spqx - *
function Medicamentos_Frecuentes_Diagnostico()
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    if ($this->bodega == '')
					{
					  $query = "select distinct case when k.sw_pos = 1 then 'POS'
						else 'NO POS' end as item,a.codigo_producto, h.descripcion as producto,
						c.descripcion as principio_activo, d.descripcion as forma
						FROM medicamentos_diagnosticos_frecuentes as a, hc_diagnosticos_ingreso as b,
						medicamentos as k, inventarios_productos as h, inv_med_cod_principios_activos
						as c, inv_med_cod_forma_farmacologica as d where b.evolucion_id = '".$this->evolucion."'
						and b.tipo_diagnostico_id = a.diagnostico_id
						AND a.codigo_producto = h.codigo_producto
						and h.codigo_producto = k.codigo_medicamento
						AND k.cod_principio_activo = c.cod_principio_activo
						AND k.cod_forma_farmacologica = d.cod_forma_farmacologica AND h.estado = '1'
            order by a.codigo_producto";
					}
			else
					{
					  $query = "
            select distinct case when k.sw_pos = 1 then 'POS'
						else 'NO POS' end as item, a.codigo_producto, h.descripcion as producto,
						c.descripcion as principio_activo, d.descripcion as forma,
						f.existencia
						FROM medicamentos_diagnosticos_frecuentes as a, hc_diagnosticos_ingreso as b,
						inventarios_productos as h left join hc_bodegas_consultas as e on(e.bodega_unico='".$this->bodega."')
						left join existencias_bodegas as f
						on(e.empresa_id=f.empresa_id and e.centro_utilidad=f.centro_utilidad
						and						e.bodega=f.bodega
						and h.codigo_producto=f.codigo_producto), medicamentos as k,
						inv_med_cod_principios_activos as c, inv_med_cod_forma_farmacologica as d
						where b.evolucion_id = '".$this->evolucion."'
						and b.tipo_diagnostico_id = a.diagnostico_id
            AND a.codigo_producto = h.codigo_producto
						and h.codigo_producto = k.codigo_medicamento
						AND k.cod_principio_activo = c.cod_principio_activo
						AND k.cod_forma_farmacologica = d.cod_forma_farmacologica AND h.estado = '1'
            order by a.codigo_producto";
					}

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla qx_tipo_equipo_fijo";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
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

//cor - clzc - spqx - *
function tipo_via_administracion($codigo_producto)
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select b.via_administracion_id, b.nombre from inv_medicamentos_vias_administracion as a,
		 hc_vias_administracion as b where a.codigo_medicamento = '".$codigo_producto."' and
		 a.via_administracion_id = b.via_administracion_id order by b.via_administracion_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla hc_vias_administracion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}

//cor - clzc - ptce - *
function GetunidadesViaAdministracion($via_administracion)
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT unidad_dosificacion FROM hc_unidades_dosificacion_vias_administracion
		WHERE via_administracion_id = '".$via_administracion."'";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en las unidades de dosificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}

//cor - clzc - spqx - *
function Cargar_Periocidad()
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select periocidad_id from hc_periocidad order by periocidad_indice_orden";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla periocidad_id";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}

//cor - clzc - spqx - *
function horario()
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		  $query= "select duracion_id, descripcion from hc_horario order by duracion_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla hc_horarion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}


//cor - clzc - spqx - *
function Consulta_Datos_Justificacion($codigo_producto, $evolucion)
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "select a.hc_justificaciones_no_pos_amb, a.evolucion_id, n.descripcion as forma,
		k.concentracion_forma_farmacologica, k.unidad_medida_medicamento_id, a.codigo_producto,
		a.usuario_id_autoriza, a.duracion, a.dosis_dia, a.justificacion, a.ventajas_medicamento,
		a.ventajas_tratamiento, a.precauciones, a.controles_evaluacion_efectividad,
		a.tiempo_respuesta_esperado, a.riesgo_inminente, a.sw_riesgo_inminente,
		a.sw_agotadas_posibilidades_existentes, a.sw_comercializacion_pais, a.sw_homologo_pos,
		a.descripcion_caso_clinico, a.sw_existe_alternativa_pos from hc_justificaciones_no_pos_amb as a,
		medicamentos as k, inv_med_cod_forma_farmacologica as n where
		a.codigo_producto = '".$codigo_producto."' and a.evolucion_id = ".$evolucion." and
		a.codigo_producto = k.codigo_medicamento and k.cod_forma_farmacologica = n.cod_forma_farmacologica";
		/*echo $query= "select a.hc_justificaciones_no_pos_amb, a.evolucion_id,
		a.codigo_producto, a.usuario_id_autoriza, a.duracion, a.dosis_dia,
		a.justificacion, a.ventajas_medicamento, a.ventajas_tratamiento,
		a.precauciones, a.controles_evaluacion_efectividad,
		a.tiempo_respuesta_esperado, a.riesgo_inminente, a.sw_riesgo_inminente,
		a.sw_agotadas_posibilidades_existentes, a.sw_comercializacion_pais,
		a.sw_homologo_pos, a.descripcion_caso_clinico, a.sw_existe_alternativa_pos
		from hc_justificaciones_no_pos_amb as a
 		where (a.codigo_producto = '".$codigo_producto."' and
	 	a.evolucion_id = ".$this->evolucion.")";*/

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la justificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}

//*
function Consulta_Diagnosticos_Justificacion($hc_justificaciones_no_pos_amb)
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		$query= "select a.hc_justificaciones_no_pos_amb, a.diagnostico_id,
		b.diagnostico_nombre from hc_justificaciones_no_pos_amb_diagnostico as a,
		diagnosticos as b where a.diagnostico_id = b.diagnostico_id and
		a.hc_justificaciones_no_pos_amb = ".$hc_justificaciones_no_pos_amb."";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_amb_diagnostico";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		if ($_SESSION['SPIA'.$pfj] != 1)
		{
			for($j=0;$j<sizeof($vector);$j++)
			{
				$_SESSION['DIAGNOSTICOSM'.$pfj][$vector[$j][diagnostico_id]]= $vector[$j][diagnostico_nombre];
			}
		}
		$result->Close();
		return $vector;
}


function Consulta_Alternativas_Pos($hc_justificaciones_no_pos_amb)
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		 $query= "select a.alternativa_pos_id, a.medicamento_pos,
		a.principio_activo, a.dosis_dia_pos, a.duracion_pos,
		a.sw_no_mejoria, a.sw_reaccion_secundaria, a.reaccion_secundaria,
		a.sw_contraindicacion, a.contraindicacion,
		a.otras, a.hc_justificaciones_no_pos_amb
		from hc_justificaciones_no_pos_respuestas_pos as a
 		where (a.hc_justificaciones_no_pos_amb = ".$hc_justificaciones_no_pos_amb.")";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}

//*
function Consulta_Plantillas_Justificacion($codigo_medicamento)
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		 $query= "select codigo_medicamento, justificacion, ventajas_medicamento,
			ventajas_tratamiento, precauciones, controles_evaluacion_efectividad,
			tiempo_respuesta_esperado, riesgo_inminente, sw_riesgo_inminente,
			sw_agotadas_posibilidades_existentes, sw_comercializacion_pais,
			sw_homologo_pos from hc_justificaciones_no_pos_plantillas
			where codigo_medicamento = '".$codigo_medicamento."'";
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error al consultar hc_justificaciones_no_pos_plantillas";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			else
			{ $i=0;
				while (!$result->EOF)
				{
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
				}
			}
		$result->Close();
	  return $vector;
}

//*
function Consulta_Justificacion_Almacenada($codigo_medicamento)
{
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		 $query= "select
		hc_justificaciones_no_pos_amb, evolucion_id, codigo_producto, usuario_id_autoriza,
		duracion, dosis_dia, justificacion, ventajas_medicamento, ventajas_tratamiento,
		precauciones, controles_evaluacion_efectividad, tiempo_respuesta_esperado,
		riesgo_inminente, sw_riesgo_inminente, sw_agotadas_posibilidades_existentes,
		sw_comercializacion_pais, sw_homologo_pos, descripcion_caso_clinico,
		sw_existe_alternativa_pos from hc_justificaciones_no_pos_amb
		where codigo_producto = '".$codigo_medicamento."' and evolucion_id = ".$this->evolucion."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_plantillas";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		$result->Close();
	  return $vector;
}



//*
//como el codigo del producto en inventario_productos es unico el resultado del query es un solo item
function Unidad_Venta($codigo_producto)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

    $query="select a.codigo_producto, a.contenido_unidad_venta, b.descripcion from
		inventarios_productos as a, unidades as b where a.codigo_producto = '".$codigo_producto."'
		and a.unidad_id = b.unidad_id";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la tabla de unidades";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
		$result->Close();
		return $a;
}

//clzc-ptce //no se esta usando
function Peso_Paciente()
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

     $query="( select b.peso, b.evolucion_id as x from hc_signos_vitales_consultas as b
                   where b.evolucion_id=".$this->evolucion.")
                union ( select a.peso, a.ingreso as x from hc_signos_vitales as a
                where a.ingreso = '".$this->ingreso."' and a.fecha = (select max(a.fecha)
                from hc_signos_vitales as a where a.ingreso = '".$this->ingreso."'))";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la tabla profesionales";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
		$result->Close();
		return $a;
}


//clzc-ptce-*
function Verificacion_Existe_Medicamento($codigo_producto)
{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query="SELECT codigo_producto, evolucion_id FROM hc_medicamentos_recetados_amb
		where codigo_producto = '".$codigo_producto."' AND evolucion_id = ".$this->evolucion."";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la tabla hc_medicamentos_recetados_amb";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
		$result->Close();
		return $a;
}
//ojo en hospitalizacionntoca cuadrar lo mismo que se hizo aqui para qie solo elk medico puedad alterar
//lo que envio, para ello en el html se pone una condicion de que cuando consulte en una evolucion distinta se active una variable
//y se envie poor parametros, ademas ahora en ambos casos se envia la evolucion para al otro lado almacenarla en la variable de sesion
//y altera el query de datos de la justiçficacion consultando no con this->evoluicon sino con el contenido de la variable de sesion.
//el otro cambio es preguntar si la variable que se activo cuando solo es ver la justificacion
//es uno entonces que pinte en modo de solo lectura y de lo contrario que siga haciendo lo mismo.
//este provceso para consulta externa queda ok al lunes 26 de julio/2004 hora 12_21 pm
//quedadndo solo pendiente alterar con estos cambios el programa de hospitalizacion.

}
?>
