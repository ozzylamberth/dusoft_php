<?php
	/**************************************************************************************
	* $Id: hc_AntecedentesPersonales.php,v 1.9 2006/12/19 23:10:18 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	*
	* coduigo Tomado del Anterior Submodulo de Antecedentes autor:Jaime Andres Valencia Salazar
	*
	* Clase para accesar los metodos privados de la clase de presentacion, se compone de 
	* metodos publicos para insertar en la base de datos, actualizar y borrar de la base 
	* de datos, y mostrar la forma de insercion y la consulta del submodulo de 
	* antecedentes personales.
	**************************************************************************************/
	class AntecedentesPersonales extends hc_classModules
	{
		/**
		* Esta funcion Inicializa las variable de la clase
		*
		* @access public
		* @return boolean Para identificar que se realizo.
		*/
		function AntecedentesPersonales()
		{
			if(!empty($_REQUEST['psicosocial']))
			{
				$this->psicosocial=true;
			}
			else
			{
				$this->psicosocial=false;
			}
			SessionSetVar("IngresoPersonal",$this->ingreso);
      SessionSetVar("Submodulo",'AntecedentesPersonales');
			return true;
		}
  /**
	* Esta función retorna los datos de concernientes a la version del submodulo
	* @access private
	*/
// 		function GetVersion()
// 		{
// 			$informacion=array(
// 			'version'=>'1',
// 			'subversion'=>'0',
// 			'revision'=>'0',
// 			'fecha'=>'01/27/2005',
// 			'autor'=>'JAIME ANDRES VALENCIA',
// 			'descripcion_cambio' => '',
// 			'requiere_sql' => false,
// 			'requerimientos_adicionales' => '',
// 			'version_kernel' => '1.0'
// 			);
// 			return $informacion;
//     }
		/**
		* Esta funcion retorna los datos de la impresion de la consulta del submodulo.
		*
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
		function GetConsulta()
		{
			if($this->frmConsulta()==false)
			{
				return true;
			}
			return $this->salida;
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
		/**
		* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
		*
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
		function GetEstado()
		{
			$pfj=$this->frmPrefijo;
			$sql = "SELECT count(*) FROM hc_antecedentes_personales	WHERE evolucion_id=".$this->evolucion.";";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			while(!$rst->EOF)
			{
				$estado = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			if ($estado[count] == 0)
				return false;
			else
			 	return true;
		}
		/**
		* Esta funcion retorna la presentacion del submodulo (consulta o insercion).
		*
		* @access public
		* @return text Datos HTML de la pantalla.
		*/
		function GetForma()
		{
			$pfj=$this->frmPrefijo;
			if(empty($_REQUEST['accion'.$pfj]))
			{
				$this->frmForma();
			}
			else
			{
				if($_REQUEST['accion'.$pfj]=='insertar_infancia_niï¿½z')
				{
					$this->Insertar_Antecedentes_Infancia();
				}
				if($_REQUEST['accion'.$pfj]=='ocultar')
				{
					$this->frmForma();
				}
				else
				{
					if($_REQUEST['accion'.$pfj]=='modificar')
					{
						$this->UpdateDatos();
						$_REQUEST['accion'.$pfj]='ocultar';
						$this->frmForma();
					}
					else
					{
						if($_REQUEST['accion'.$pfj]=='insertar1')
						{
							if($this->InsertDatosSustAdic()==true)
							{
								$this->frmForma();
							}
						}
						else
						{
							if($_REQUEST['accion'.$pfj]=='insertar2')
							{
								if($this->InsertDatosInstitucion()==true)
								{
									$this->frmForma();
									return true;
								}
							}
							else
							{
								if($_REQUEST['accion'.$pfj]=='eliminar')
								{
									$this->EliminarDatos();
									$this->frmForma();
								}
								else
								{
									if($_REQUEST['accion'.$pfj]=='otros')
									{
										$_SESSION['ANTECEDENTES'.$pfj]['otros']=1;
										$this->frmForma();
									}
									else
									{
										if($_REQUEST['accion'.$pfj]=='ocultarotros')
										{
											unset($_SESSION['ANTECEDENTES'.$pfj]['otros']);
											$this->frmForma();
										}
										else
										{
											if($this->InsertDatos()==true)
											{
												$this->frmForma();
											}
										}
									}
								}
							}
						}
					}
				}
			}
			return $this->salida;
		}
		/**
		* Esta funcion inserta los datos del submodulo.
		*
		* @access private
		* @return boolean Informa si lo logro o no.
		*/
    function InsertDatosSustAdic()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $sustancias=$this->BusquedaSustanciasAdictivas();
        $dbconn->BeginTrans();
        foreach($sustancias as $k=>$v)
        {
            $sustancia_id = $v[hc_tipos_sustancias_adictivas_id];               	
            $patron='patron'.$sustancia_id.$pfj;
            $ultimo='ultimo'.$sustancia_id.$pfj;
            $problemasxconsumo='problemasxconsumo'.$sustancia_id.$pfj;
            $Einicio='Einicio'.$sustancia_id.$pfj;
            $tiempoconsumotexto='tiempoconsumotexto'.$sustancia_id.$pfj;
            $tiempoconsumo='tiempoconsumo'.$sustancia_id.$pfj;
            if($_REQUEST[$patron]=='-1')
            {
                $_REQUEST[$patron]='NULL';
            }
            if($_REQUEST[$ultimo]=='-1')
            {
                $_REQUEST[$ultimo]='NULL';
            }
            if($_REQUEST[$problemasxconsumo]=='-1')
            {
                $_REQUEST[$problemasxconsumo]='NULL';
            }
            if($_REQUEST[$Einicio]=='' OR !is_numeric($_REQUEST[$Einicio]))
            {
                $_REQUEST[$Einicio]='NULL';
            }
            if($_REQUEST[$tiempoconsumotexto]=='' OR !is_numeric($_REQUEST[$tiempoconsumotexto]))
            {
                $_REQUEST[$tiempoconsumotexto]='NULL';
                $_REQUEST[$tiempoconsumo]='NULL';
            }
            if($_REQUEST[$patron]!='NULL' or $_REQUEST[$ultimo]!='NULL' or $_REQUEST[$problemasxconsumo]!='NULL')
            {
                $sql="select a.hc_antecedentes_personales_toxico_alergicos_id from hc_antecedentes_personales_toxico_alergicos as a, hc_evoluciones as b, ingresos as c where hc_tipos_sustancias_adictivas_id=$v[hc_tipos_sustancias_adictivas_id] and c.paciente_id='".$this->paciente."' and c.tipo_id_paciente='".$this->tipoidpaciente."' and c.ingreso=b.ingreso and b.evolucion_id=a.evolucion_id;";
                $result=$dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                if(empty($result->fields[0]))
                {
                    if($_REQUEST[$tiempoconsumo]=='NULL')
                    {
                        $sql="insert into hc_antecedentes_personales_toxico_alergicos (hc_tipos_sustancias_adictivas_id, hc_tipos_patron_consumos_id, hc_tipos_ultimo_consumo_id, hc_tipos_problemasxconsumo_id, edad_inicio, tiempo_consumo, tiempo_consumo_tipo, evolucion_id) values ($v[hc_tipos_sustancias_adictivas_id], ".$_REQUEST[$patron].", ".$_REQUEST[$ultimo].", ".$_REQUEST[$problemasxconsumo].", ".$_REQUEST[$Einicio].", ".$_REQUEST[$tiempoconsumotexto].", ".$_REQUEST[$tiempoconsumo].", ".$this->evolucion.")";
                    }
                    else
                    {
                        $sql="insert into hc_antecedentes_personales_toxico_alergicos (hc_tipos_sustancias_adictivas_id, hc_tipos_patron_consumos_id, hc_tipos_ultimo_consumo_id, hc_tipos_problemasxconsumo_id, edad_inicio, tiempo_consumo, tiempo_consumo_tipo, evolucion_id) values ($v[hc_tipos_sustancias_adictivas_id], ".$_REQUEST[$patron].", ".$_REQUEST[$ultimo].", ".$_REQUEST[$problemasxconsumo].", ".$_REQUEST[$Einicio].", ".$_REQUEST[$tiempoconsumotexto].", '".$_REQUEST[$tiempoconsumo]."', ".$this->evolucion.")";
                    }
                }
                else
                {
                    if($_REQUEST[$tiempoconsumo]=='NULL')
                    {
                        $sql="update hc_antecedentes_personales_toxico_alergicos set hc_tipos_sustancias_adictivas_id=$v[hc_tipos_sustancias_adictivas_id], hc_tipos_patron_consumos_id=".$_REQUEST[$patron].", hc_tipos_ultimo_consumo_id=".$_REQUEST[$ultimo].", hc_tipos_problemasxconsumo_id=".$_REQUEST[$problemasxconsumo].", edad_inicio=".$_REQUEST[$Einicio].", tiempo_consumo=".$_REQUEST[$tiempoconsumotexto].", tiempo_consumo_tipo=".$_REQUEST[$tiempoconsumo]." where hc_antecedentes_personales_toxico_alergicos_id=".$result->fields[0].";";
                    }
                    else
                    {
                        $sql="update hc_antecedentes_personales_toxico_alergicos set hc_tipos_sustancias_adictivas_id=$v[hc_tipos_sustancias_adictivas_id], hc_tipos_patron_consumos_id=".$_REQUEST[$patron].", hc_tipos_ultimo_consumo_id=".$_REQUEST[$ultimo].", hc_tipos_problemasxconsumo_id=".$_REQUEST[$problemasxconsumo].", edad_inicio=".$_REQUEST[$Einicio].", tiempo_consumo=".$_REQUEST[$tiempoconsumotexto].", tiempo_consumo_tipo='".$_REQUEST[$tiempoconsumo]."' where hc_antecedentes_personales_toxico_alergicos_id=".$result->fields[0].";";
                    }
                }
                if(!$dbconn->Execute($sql))
                {
                    $dbconn->RollbackTrans();
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
        }
        $dbconn->CommitTrans();
        $this->RegistrarSubmodulo($this->GetVersion());
        return true;
    }


    function BusquedaAtencionRiesgo()
    {
			list($dbconn) = GetDBconn();
			$query = "select d.tipo_atencion_id, d.detalle, b.evolucion_id, date(b.fecha) from ingresos as a join hc_evoluciones as b on(a.paciente_id='".$this->paciente."' and a.tipo_id_paciente='".$this->tipoidpaciente."' and a.ingreso=b.ingreso and date(b.fecha)<date(now())) join hc_atencion as c on(b.evolucion_id=c.evolucion_id) join hc_tipos_atencion as d on(c.tipo_atencion_id=d.tipo_atencion_id and (d.tipo_atencion_id='14' or d.tipo_atencion_id='02'));";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
					if(!$result->EOF)
					{
							$i=0;
							while(!$result->EOF)
							{
									$atencion[0][$i]=$result->fields[0];
									$atencion[1][$i]=$result->fields[1];
									$atencion[2][$i]=$result->fields[2];
									$atencion[3][$i]=$result->fields[3];
									$i++;
									$result->MoveNext();
							}
					}
			}
			return $atencion;
    }
		//claudia
		function Tipos_Complicaciones_Embarazo()
		{
			$pfj=$this->frmPrefijo;
			list($dbconnect) = GetDBconn();
			$query= "SELECT * FROM hc_tipos_embarazo_complicado";
			$result = $dbconnect->Execute($query);

			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error al cargar las opciones de busqueda";
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

	  function PartirFecha($fecha)
	  {
	    $a=explode('-',$fecha);
	    $b=explode(' ',$a[2]);
	    $c=explode(':',$b[1]);
	    $d=explode('.',$c[2]);
	    return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	  }

		function Tipos_Complicacion()
		{
			$pfj=$this->frmPrefijo;
			list($dbconnect) = GetDBconn();
			$query= "SELECT * FROM hc_tipos_complicacion";
			$result = $dbconnect->Execute($query);

			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error al cargar las opciones de busqueda";
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
		//clzc - si - *
		function Insertar_Antecedentes_Infancia($no_pos_paciente)
		{
      $pfj=$this->frmPrefijo;
      list($dbconn) = GetDBconn();
      $dbconn->BeginTrans();

               $query="INSERT INTO hc_antecedentes_personales_infancia
                              (evolucion_id, sw_tipo_embarazo,
                                sw_sitio_parto,sitio_parto,
                              sw_complicacion,complicacion,
                                sw_alimentacion_materna, alimentacion_materna,
                                sw_gateo, sw_caminar,
                                sw_actividad_lucida_compartida,    actividad_lucida_compartida,
                                sw_angustia_separacion,    sw_comportamiento,
                                comportamiento, sw_aceptacion_autoridad,
                                aceptacion_autoridad, sw_rendimiento_academico,
                                rendimiento_academico)
                              VALUES (".$this->evolucion.", '".$_REQUEST['sw_tipo_embarazo'.$pfj]."',
                                '".$_REQUEST['sw_sitio_parto'.$pfj]."', '".$_REQUEST['sitio_parto'.$pfj]."',
                                '".$_REQUEST['sw_complicacion'.$pfj]."','".$_REQUEST['complicacion'.$pfj]."',
                                '".$_REQUEST['sw_alimentacion_materna'.$pfj]."','".$_REQUEST['alimentacion_materna'.$pfj]."',
                                '".$_REQUEST['sw_gateo'.$pfj]."','".$_REQUEST['sw_caminar'.$pfj]."',
                '".$_REQUEST['sw_actividad_lucida_compartida'.$pfj]."','".$_REQUEST['actividad_lucida_compartida'.$pfj]."',
                '".$_REQUEST['sw_angustia_separacion'.$pfj]."','".$_REQUEST['sw_comportamiento'.$pfj]."',
                                '".$_REQUEST['comportamiento'.$pfj]."','".$_REQUEST['sw_aceptacion_autoridad'.$pfj]."',
                '".$_REQUEST['aceptacion_autoridad'.$pfj]."','".$_REQUEST['sw_rendimiento_academico'.$pfj]."',
                                '".$_REQUEST['rendimiento_academico'.$pfj]."' )";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error al insertar en hc_antecedentes_personales_infancia";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->frmError["MensajeError"]="EL MEDICAMENTO YA HA SIDO FORMULADO EN ESTA EVOLUCION.";
                    $dbconn->RollbackTrans();
                        //caso especial que retorne true porque si ya existe elmedicamento no debe volver
                        //a la forma de llenado si no a la forma principal
                    return true;
            }
			$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
			$dbconn->CommitTrans();
			$this->RegistrarSubmodulo($this->GetVersion());
      return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BusquedaAntecedentes($dat)
    {
      $sql = "";
			if(empty($dat))
      {
        $sql .= "SELECT	HT.nombre_tipo,";
				$sql .= "				HT.riesgo,";
				$sql .= "				HA.detalle,";
				$sql .= "				HA.destacar,";
				$sql .= "				HE.evolucion_id,";
				$sql .= "				HT.hc_tipo_antecedente_personal_id AS hctap,";
				$sql .= "				HT.hc_tipo_antecedente_detalle_personal_id AS hctad,";
				$sql .= "				HD.descripcion,";
				$sql .= "				HA.ocultar,";
				$sql .= "				HA.hc_antecedente_personal_id AS hac,";
				$sql .= "				HA.sw_riesgo,";
				$sql .= "				HA.fecha_registro ";
				$sql .= "FROM		hc_evoluciones HE,";
				$sql .= "				ingresos IG, ";
				$sql .= "				hc_antecedentes_personales HA RIGHT JOIN hc_tipos_antecedentes_detalle_personales HT ";
				$sql .= "	 			ON(	HA.hc_tipo_antecedente_detalle_personal_id = HT.hc_tipo_antecedente_detalle_personal_id AND ";
				$sql .= "	 					HA.hc_tipo_antecedente_personal_id = HT.hc_tipo_antecedente_personal_id) ";
				$sql .= "	 			RIGHT JOIN hc_tipos_antecedentes_personales HD ";
				$sql .= "	 			ON(HT.hc_tipo_antecedente_personal_id = HD.hc_tipo_antecedente_personal_id) ";
				$sql .= "WHERE 	HE.evolucion_id<=".$this->evolucion." ";
				$sql .= "AND		HE.ingreso = IG.ingreso ";
				$sql .= "AND		IG.paciente_id='".$this->paciente."' ";
				$sql .= "AND		IG.tipo_id_paciente='".$this->tipoidpaciente."' ";
				$sql .= "AND		HE.evolucion_id = HA.evolucion_id ";
				$sql .= "ORDER BY HT.hc_tipo_antecedente_detalle_personal_id, HA.fecha_registro ASC;";
      }
      else
      {
        $sql = "select d.nombre_tipo, d.riesgo, c.detalle, c.destacar,
							 a.evolucion_id, d.hc_tipo_antecedente_personal_id,
							 d.hc_tipo_antecedente_detalle_personal_id, e.descripcion,
							 c.ocultar, c.hc_antecedente_personal_id, c.sw_riesgo, c.fecha_registro
					  from hc_evoluciones as a
					  join ingresos as b on(a.evolucion_id<=".$this->evolucion." and a.ingreso=b.ingreso and b.paciente_id='".$this->paciente."' and b.tipo_id_paciente='".$this->tipoidpaciente."')
					  join hc_antecedentes_personales as c on(a.evolucion_id=c.evolucion_id and c.ocultar=0)
					  right join hc_tipos_antecedentes_detalle_personales as d on(c.hc_tipo_antecedente_detalle_personal_id=d.hc_tipo_antecedente_detalle_personal_id and c.hc_tipo_antecedente_personal_id=d.hc_tipo_antecedente_personal_id)
					  right join hc_tipos_antecedentes_personales as e on(d.hc_tipo_antecedente_personal_id=e.hc_tipo_antecedente_personal_id)
					  order by d.hc_tipo_antecedente_detalle_personal_id, c.fecha_registro ASC;";
      }
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
			
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[$rst->fields[7]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}		
			return $antecedentes;
    }
		//fin claudia
		/************************************************************************************
		*
		*************************************************************************************/
		function BusquedaAntecedentesTotal()
    {
      $dato = CalcularEdad($this->datosPaciente['fecha_nacimiento']);
        
      $sql .= "SELECT	HD.nombre_tipo, ";
			$sql .= "				HD.riesgo,  ";
			$sql .= "				HA.detalle,  ";
			$sql .= "				HA.destacar, ";
			$sql .= "				HE.evolucion_id,  ";
			$sql .= "				HD.hc_tipo_antecedente_personal_id AS hctap, ";
			$sql .= "				HD.hc_tipo_antecedente_detalle_personal_id AS hctad, ";
			$sql .= " 			HZ.descripcion, ";
			$sql .= "				HZ.sexo,  ";
			$sql .= "				HZ.edad_min, ";
			$sql .= "				HZ.edad_max, ";
			$sql .= "				HA.sw_riesgo, "; 
			$sql .= "			 	TO_CHAR(HA.fecha_registro,'YYYY-MM-DD') AS fecha, ";
			$sql .= "				COALESCE(HA.ocultar,'0') AS ocultar, ";
			$sql .= "				HA.hc_antecedente_personal_id AS hcid ";
			$sql .= "FROM 	hc_evoluciones HE JOIN ingresos IG ";
			$sql .= "				ON(	HE.ingreso = IG.ingreso AND ";
			$sql .= " 					HE.evolucion_id <= ".$this->evolucion." AND ";
			$sql .= " 					IG.paciente_id = '".$this->paciente."' AND ";
			$sql .= " 					IG.tipo_id_paciente = '".$this->tipoidpaciente."') ";
			$sql .= "				JOIN hc_antecedentes_personales HA ";
			$sql .= "				ON(	HE.evolucion_id = HA.evolucion_id ) ";
			$sql .= "				RIGHT JOIN hc_tipos_antecedentes_personales_modulos HT ";
			$sql .= "				ON(	HA.hc_tipo_antecedente_detalle_personal_id = HT.hc_tipo_antecedente_detalle_personal_id AND";
			$sql .= " 					HA.hc_tipo_antecedente_personal_id = HT.hc_tipo_antecedente_personal_id ) ";
			$sql .= "				JOIN hc_tipos_antecedentes_detalle_personales HD ";
			$sql .= "				ON(	HT.hc_tipo_antecedente_detalle_personal_id = HD.hc_tipo_antecedente_detalle_personal_id AND ";
			$sql .= " 					HT.hc_tipo_antecedente_personal_id = HD.hc_tipo_antecedente_personal_id )";
			$sql .= "				JOIN hc_tipos_antecedentes_personales HZ ";
			$sql .= "				ON(	HD.hc_tipo_antecedente_personal_id = HZ.hc_tipo_antecedente_personal_id) ";
			$sql .= "WHERE 	HT.hc_modulo='".$this->datosEvolucion['hc_modulo']."' ";
			$sql .= "ORDER BY HD.hc_tipo_antecedente_personal_id, HD.nombre_tipo ASC;";
      
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
      
			if($rst->RecordCount()==0)
      {
        $rst->Close();
        $sql = "";
				$sql .= "SELECT	HD.nombre_tipo, ";
				$sql .= "				HD.riesgo,  ";
				$sql .= "				HA.detalle,  ";
				$sql .= "				HA.destacar, ";
				$sql .= "				HE.evolucion_id,  ";
				$sql .= "				HD.hc_tipo_antecedente_personal_id AS hctap, ";
				$sql .= "				HD.hc_tipo_antecedente_detalle_personal_id AS hctad, ";
				$sql .= " 			HZ.descripcion, ";
				$sql .= "			 	HZ.sexo, ";
				$sql .= "			 	HZ.edad_min, ";
				$sql .= "			 	HZ.edad_max, ";
				$sql .= "			 	HA.sw_riesgo, ";
				$sql .= "			 	TO_CHAR(HA.fecha_registro,'YYYY-MM-DD') AS fecha, ";
				$sql .= "				COALESCE(HA.ocultar,'0') AS ocultar, ";
				$sql .= "				HA.hc_antecedente_personal_id AS hcid ";
				$sql .= "FROM 	hc_evoluciones HE JOIN ingresos IG ";
				$sql .= "				ON(	HE.ingreso = IG.ingreso AND ";
				$sql .= " 					HE.evolucion_id <= ".$this->evolucion." AND ";
				$sql .= " 					IG.paciente_id = '".$this->paciente."' AND ";
				$sql .= " 					IG.tipo_id_paciente = '".$this->tipoidpaciente."') ";
				$sql .= "				JOIN hc_antecedentes_personales HA ";
				$sql .= "				ON(	HE.evolucion_id = HA.evolucion_id) ";
				$sql .= "				RIGHT JOIN hc_tipos_antecedentes_detalle_personales HD ";
				$sql .= "				ON(	HA.hc_tipo_antecedente_detalle_personal_id = HD.hc_tipo_antecedente_detalle_personal_id AND ";
				$sql .= "						HA.hc_tipo_antecedente_personal_id = HD.hc_tipo_antecedente_personal_id )";
				$sql .= "				RIGHT JOIN hc_tipos_antecedentes_personales HZ ";
				$sql .= "				ON(	HD.hc_tipo_antecedente_personal_id = HZ.hc_tipo_antecedente_personal_id) ";
				$sql .= "ORDER BY HD.hc_tipo_antecedente_personal_id, HD.nombre_tipo ASC; ";
				
				if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
      }
			
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[$rst->fields[7]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			return $antecedentes;
    }
		/************************************************************************************
		*
		*************************************************************************************/
		function BusquedaTotalToxicos()
    {
			$sql .= "SELECT	HS.descripcion, ";
			$sql .= "				HS.hc_tipos_sustancias_adictivas_id, ";
			$sql .= "				HS.tipo_patronconsumo, ";
			$sql .= "				HA.patron, ";
			$sql .= "				HA.ultimo_consumo,  ";
			$sql .= "				HA.problemas,  ";
			$sql .= "				HA.edad_inicio,  ";
			$sql .= "				HA.tiempo_consumo,  ";
			$sql .= "				HA.tiempo_consumo_tipo ";
			$sql .= "FROM		hc_tipos_sustancias_adictivas HS LEFT JOIN ";
			$sql .= "				(	SELECT  HT.descripcion AS patron, ";
			$sql .= "									HU.descripcion AS ultimo_consumo,  ";
			$sql .= "									HX.descripcion AS problemas,  ";
			$sql .= "									HA.edad_inicio ||' Años' AS edad_inicio,  ";
			$sql .= "									HA.tiempo_consumo,  ";
			$sql .= "									CASE 	WHEN HA.tiempo_consumo_tipo = 'A' THEN 'AÑOS'";
			$sql .= "												WHEN HA.tiempo_consumo_tipo = 'M' THEN 'MESES'";
			$sql .= "												WHEN HA.tiempo_consumo_tipo = 'D' THEN 'DIAS' END AS tiempo_consumo_tipo, ";
			$sql .= "									HA.hc_tipos_sustancias_adictivas_id ";
			$sql .= "					FROM		hc_antecedentes_personales_toxico_alergicos HA, ";
			$sql .= "									hc_evoluciones HE, ";
			$sql .= "									ingresos IG, ";
			$sql .= "									hc_tipos_patron_consumo HT, ";
			$sql .= "									hc_tipos_ultimo_consumo HU, ";
			$sql .= "									hc_tipos_problemasxconsumo HX ";
			$sql .= "					WHERE		IG.paciente_id = '".$this->paciente."'  ";
			$sql .= "					AND			IG.tipo_id_paciente='".$this->tipoidpaciente."'  ";
			$sql .= "					AND 		IG.ingreso = HE.ingreso  ";
			$sql .= "					AND			HT.hc_tipos_patron_consumos_id = HA.hc_tipos_patron_consumos_id ";
			$sql .= "					AND			HU.hc_tipos_ultimo_consumo_id = HA.hc_tipos_ultimo_consumo_id ";
			$sql .= "					AND			HX.hc_tipos_problemasxconsumo_id = HA.hc_tipos_problemasxconsumo_id ";
			$sql .= "					AND 		HE.evolucion_id = HA.evolucion_id) AS HA ";
			$sql .= "				ON(HS.hc_tipos_sustancias_adictivas_id = HA.hc_tipos_sustancias_adictivas_id) ";
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
		/************************************************************************************
		*
		*************************************************************************************/
		function BusquedaInstituciones()
		{
			$sql .= "SELECT	HI.hc_antecedente_personal_institucion_id, ";
			$sql .= "				HI.nombre_institucion,  ";
			$sql .= "				Hi.estancia_institucion,  ";
			$sql .= "				HI.tipo_estancia_institucion, "; 
			$sql .= "				CASE WHEN HI.evolucion_id=".$this->evolucion." THEN '1'  ";
			$sql .= "						 ELSE '0' END AS esta  ";
			$sql .= "FROM		hc_antecedentes_personales_instituciones HI,  ";
			$sql .= "				hc_evoluciones HE,  ";
			$sql .= "				ingresos IG ";
			$sql .= "WHERE 	IG.paciente_id='".$this->paciente."'  ";
			$sql .= "AND 		IG.tipo_id_paciente='".$this->tipoidpaciente."'  ";
			$sql .= "AND 		IG.ingreso = HE.ingreso  ";
			$sql .= "AND 		HE.evolucion_id = HI.evolucion_id; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BusquedaAntecedentesTotal2()
    {
			$sql = "";
			$sql .= "SELECT	HD.nombre_tipo, ";
			$sql .= "				HD.riesgo,  ";
			$sql .= "				HA.detalle,  ";
			$sql .= "				HA.destacar, ";
			$sql .= "				HE.evolucion_id,  ";
			$sql .= "				HD.hc_tipo_antecedente_personal_id AS hctap, ";
			$sql .= "				HD.hc_tipo_antecedente_detalle_personal_id AS hctad, ";
			$sql .= " 			HZ.descripcion, ";
			$sql .= "			 	HZ.sexo, ";
			$sql .= "			 	HZ.edad_min, ";
			$sql .= "			 	HZ.edad_max, ";
			$sql .= "			 	HA.sw_riesgo, ";
			$sql .= "			 	TO_CHAR(HA.fecha_registro,'YYYY-MM-DD') AS fecha, ";
			$sql .= "				COALESCE(HA.ocultar,'0') AS ocultar, ";
			$sql .= "				HA.hc_antecedente_personal_id AS hcid ";
			$sql .= "FROM 	hc_evoluciones HE JOIN ingresos IG ";
			$sql .= "				ON(	HE.ingreso = IG.ingreso AND ";
			$sql .= " 					HE.evolucion_id <= ".$this->evolucion." AND ";
			$sql .= " 					IG.paciente_id = '".$this->paciente."' AND ";
			$sql .= " 					IG.tipo_id_paciente = '".$this->tipoidpaciente."') ";
			$sql .= "				JOIN hc_antecedentes_personales HA ";
			$sql .= "				ON(	HE.evolucion_id = HA.evolucion_id) ";
			$sql .= "				JOIN hc_tipos_antecedentes_detalle_personales HD ";
			$sql .= "				ON(	HA.hc_tipo_antecedente_detalle_personal_id = HD.hc_tipo_antecedente_detalle_personal_id AND ";
			$sql .= "						HA.hc_tipo_antecedente_personal_id = HD.hc_tipo_antecedente_personal_id )";
			$sql .= "				JOIN hc_tipos_antecedentes_personales HZ ";
			$sql .= "				ON(	HD.hc_tipo_antecedente_personal_id = HZ.hc_tipo_antecedente_personal_id) ";
			$sql .= "ORDER BY HD.hc_tipo_antecedente_personal_id, HD.nombre_tipo ASC; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
			
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[$rst->fields[7]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			return $antecedentes;
    }
		/************************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta 
		* sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
?>
