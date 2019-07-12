<?php

/**
* Submodulo de Certificado Defuncion.
*
* Submodulo para manejar los certificados de defunciones.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Certificado_Defuncion.php,v 1.4 2006/12/19 21:00:13 jgomez Exp $
*/


/**
* Certificado Defuncion.
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
*/

class Certificado_Defuncion extends hc_classModules
{

      var $limit;
      var $conteo;
/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

     function Certificado_Defuncion()
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
// 		'fecha'=>'01/27/2005',
// 		'autor'=>'TIZZIANO PEREA OCORO',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT count(*)
				FROM hc_conducta_defuncion
				WHERE evolucion_id=".$this->evolucion."
				AND ingreso =".$this->ingreso.";";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$estado=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

		if ($estado[count] == 0)
		{
			return false;
		}
		else
		{
		 	return true;
		}
	}


/**
* Esta función retorna la presentación del submodulo (consulta o inserción).
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
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
			if($_REQUEST['accion'.$pfj]== 'Insert_Conducta')
			{
				if($_REQUEST['buscar'.$pfj] == 'BUSCAR')
				{
					$vectorD=$this->Busqueda_Avanzada_Diagnosticos();
					$this->frmForma($vectorD);
				}

				if($_REQUEST['guardardiagnostico'.$pfj] == 'GUARDAR')
				{
					$this->Insertar_Varios_Diagnosticos();
					$this-> frmForma();
				}

				if($_REQUEST['guardar_partida_defuncion'.$pfj] == 'GUARDAR')
				{
					if($this->Insert_Motivos_Defuncion()==true)
					{
						if ($_SESSION['INSERTO'] == '1')
						{
							list($dbconn) = GetDBconn();
                                   $sql="UPDATE pacientes SET paciente_fallecido = '1' WHERE tipo_id_paciente='".$this->tipoidpaciente."' AND paciente_id='".$this->paciente."';";
							$dbconn->Execute($sql);
							if($dbconn->ErrorNo() != 0) {
								$this->error="ERROR EN LA CONSULTA";
								$this->mensajeDeError="SQL : ".$sql;
								return false;
							}
							$this->RegistrarSubmodulo($this->GetVersion());
              $this->frmFormaConfirmacion();
						}
						else
						{
							$this->frmForma();
							unset ($_SESSION['INSERTO']);
						}
					}
					else
					{
						$this->frmForma();
					}
				}

			}

			if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
			{
				$vectorD=$this->Busqueda_Avanzada_Diagnosticos();
				$this->frmForma($vectorD);
			}

			if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico')
			{
				$this->Eliminar_Diagnostico_Solicitado($_REQUEST['diagnostico_id'.$pfj]);
				$this->frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='cambiar_diagnostico')
			{
				$this->CambiarDiagnosticos();
				$this-> frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='cambiar_descripcion')
			{
				$this->CambiarDescripcion();
			}

			if($_REQUEST['accion'.$pfj]=='Insertar_Descripcion')
			{
				$this->InsertarDescripcion();
				$this-> frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='Volver_Original')
			{
				$this-> frmForma();
			}
		}
		return $this->salida;
	}


	function GetConsulta()
	{
		$this->frmConsulta();
		return $this->salida;
	}

	//cor - clzc-jea - ads - *
	function Busqueda_Avanzada_Diagnosticos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();

		$codigo       = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$diagnostico  = STRTOUPPER($_REQUEST['diagnostico'.$pfj]);

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

		if (($diagnostico != '') AND ($codigo == ''))
		{
			$busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
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
          
          $query = "SELECT diagnostico_id, diagnostico_nombre
                    FROM diagnosticos
                    $busqueda1 $busqueda2 order by diagnostico_id
                    LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);

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
          {
          	$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
               return false;
          }
          $resulta->Close();
          return $var;
	}


	function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}

	/**
	* Esta función inserta los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/
	//cor - clzc - ads
	function Insertar_Varios_Diagnosticos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		foreach($_REQUEST['opD'.$pfj] as $index=>$codigo)
		{
			$arreglo=explode(",",$codigo);
			$sql="SELECT count(*)
				 FROM hc_conducta_diagnosticos_defuncion
				 WHERE evolucion_id =".$this->evolucion."
				 AND ingreso =".$this->ingreso.";";
			$resulta=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en hc_conducta_diagnosticos_defuncion";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
				return false;
			}
			if($resulta->fields[0]==0)
			{
			$query="INSERT INTO hc_conducta_diagnosticos_defuncion
					(ingreso, evolucion_id, diagnostico_defuncion_id, sw_principal)
					VALUES(".$this->ingreso.",".$this->evolucion.",'".$arreglo[0]."'
					,'1');";
			}
			else
			{
				$query="INSERT INTO hc_conducta_diagnosticos_defuncion
						(ingreso, evolucion_id, diagnostico_defuncion_id, sw_principal)
						VALUES(".$this->ingreso.",".$this->evolucion.",'".$arreglo[0]."'
						,'0');";
			}
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en hc_conducta_diagnosticos_defuncion";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
				return false;
			}
			else
			{
					
          $this->frmError["MensajeError"]="DIAGNOSTICOS GUARDADOS SATISFACTORIAMENTE.";
			}
		}
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}


	//cor - clzc -ads
	function ConsultaDiagnosticoI()
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "SELECT diagnostico_id,
						 diagnostico_nombre,
						 sw_principal,
    					 diagnostico_muerte
		    	  FROM hc_conducta_diagnosticos_defuncion,diagnosticos
				  WHERE hc_conducta_diagnosticos_defuncion.diagnostico_defuncion_id=diagnosticos.diagnostico_id
				  AND evolucion_id=".$this->evolucion."
				  AND ingreso =".$this->ingreso."
				  ORDER BY diagnostico_id;";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de diagnosticos de defunción";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		return $vector;
	}

	/**
	* Esta función borra los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	//cor - clzc - ads
	function Eliminar_Diagnostico_Solicitado($diagnostico_id)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="DELETE FROM hc_conducta_diagnosticos_defuncion
				WHERE diagnostico_defuncion_id = '$diagnostico_id'
				AND evolucion_id=".$this->evolucion."
				AND ingreso =".$this->ingreso.";";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL DIAGNOSTICO";
			return false;
		}
		else
		{
			$sql="SELECT diagnostico_defuncion_id, sw_principal
				  FROM hc_conducta_diagnosticos_defuncion
				  WHERE evolucion_id=".$this->evolucion."
				  AND ingreso =".$this->ingreso."
				  LIMIT 1 OFFSET 0;";
			$resulta=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "NO HAY DIAGNOSTICOS DISPONIBLES";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			else
			{
					$vector=$resulta->GetRowAssoc($ToUpper = false);
			}
			if ($_REQUEST['principal'.$pfj]=='1')
			{
				$sql2="UPADTE hc_conducta_diagnosticos_defuncion
					   SET sw_principal='1'
					   WHERE evolucion_id=".$this->evolucion."
					   AND ingreso =".$this->ingreso."
					   AND tipo_diagnostico_id='".$vector['diagnostico_defuncion_id']."';";
				$resulta=$dbconn->Execute($sql2);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al insertar en hc_conducta_diagnosticos_defuncion";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				return false;
			}
		}
		$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." FUE ELIMINADO SATISFACTORIAMENTE.";
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}


	function CambiarDiagnosticos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="UPDATE hc_conducta_diagnosticos_defuncion
			  SET sw_principal='0'
			  WHERE evolucion_id=".$this->evolucion."
			  AND ingreso =".$this->ingreso.";";
		$resulta=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_conducta_diagnosticos_defuncion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
			return false;
		}
		$sql="UPDATE hc_conducta_diagnosticos_defuncion
			  SET sw_principal='1'
			  WHERE evolucion_id=".$this->evolucion."
			  AND ingreso =".$this->ingreso."
			  AND diagnostico_defuncion_id='".$_REQUEST['diagnostico_id'.$pfj]."';";
		$resulta=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_diagnosticos_ingreso";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
			return false;
		}
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}

	function InsertarDescripcion()
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "UPDATE hc_conducta_diagnosticos_defuncion
				SET diagnostico_muerte = '".$_REQUEST['descripcion_diag'.$pfj]."'
				WHERE evolucion_id=".$this->evolucion."
				AND ingreso =".$this->ingreso."
				AND diagnostico_defuncion_id='".$_REQUEST['codigo'.$pfj]."';";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de diagnosticos de defuncion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			$this->RegistrarSubmodulo($this->GetVersion());
      return $_REQUEST['descripcion_diag'.$pfj];
		}
	}

     /**
     * Inserta todo lo relacionado con la defuncion del paciente
     * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
     * @access public
     * @return array
     * @param string plan_id
     */
     function Insert_Motivos_Defuncion()
     {
          $pfj=$this->frmPrefijo;

          $fecha= $_REQUEST['fechadef'.$pfj];

          $cad=explode ('/',$fecha);
          $dia = $cad[0];
          $mes = $cad[1];
          $ano = $cad[2];
          $fecha=$cad[2].'-'.$cad[1].'-'.$cad[0];

          $fechaHora = $fecha." ".$_REQUEST['selectHora'.$pfj].":".$_REQUEST['selectMinutos'.$pfj];
          $sitio_d = $_REQUEST['motivo'.$pfj];
          $certificado = $_REQUEST['certificado'.$pfj];
          $estado = $_REQUEST['estado'.$pfj];
          $semanas = $_REQUEST['semanas'.$pfj];
          $meses = $_REQUEST['meses'.$pfj];

          if (empty($sitio_d))
          {
               $this->frmError["MensajeError"] = "FALTA INFORMACION ACERCA DE SITIO DE DEFUNCION";
               $this->frmForma();
               return true;
          }

          if (empty($certificado))
          {
               $this->frmError["MensajeError"] = "FALTA INFORMACION DEL AUTOR DE EXPEDICION CERTIFICADO DE DEFUNCION";
               $this->frmForma();
               return true;
          }

          if ($fecha == '--')
          {
               $this->frmError["MensajeError"] = "POR FAVOR INTRODUZCA CORRECTAMENTE LA FECHA DE DEFUNCION";
               $this->frmForma();
               return true;
          }

          if ( strtotime($fecha) > strtotime(date("y-m-d")) )
          {
               $this->frmError["MensajeError"] = "LA FECHA DE DEFUNCION NO PUEDE SER MAYOR A LA FECHA ACTUAL";
               $this->frmForma();
               return true;
          }


          /*-----------------------------------------------------------------------------
          VERIFICA SI HAY DIAGNOSTICOS DE MUERTE PARA CREAR UN NUEVO REGISTRO
          -----------------------------------------------------------------------------*/

          list($dbconn) = GetDBconn();
          $query = "SELECT *
                    FROM hc_conducta_diagnosticos_defuncion
                    WHERE ingreso = ".$this->ingreso."
                    AND evolucion_id = ".$this->evolucion.";";

          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while(!$resulta->EOF)
          {
               $defuncion=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }

          if ($defuncion[ingreso] != $this->ingreso && $defuncion[evolucion_id] != $this->evolucion)
          {
               $this->frmError["MensajeError"] = "DEBE INGRESAR AL MENOS UN DIAGNOSTICO DE MUERTE O DEFUNCION";
               $this->frmForma();
               return true;
          }

          /*-----------------------------------------------------------------------------
          SELECCIONO UN NUEVO REGISTRO PARA ACTUALIZAR DATOS
          O PARA CREAR UN NUEVO REGISTRO
          -----------------------------------------------------------------------------*/
          list($dbconn) = GetDBconn();
          $query = "SELECT *
                    FROM hc_conducta_defuncion
                    WHERE ingreso = ".$this->ingreso."
                    AND evolucion_id = ".$this->evolucion.";";

          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while(!$resulta->EOF)
          {
               $indice=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }

          /*-----------------------------------------------------------------------------
          INSERTO EN CONDUCTA DEFUNCION TODOS LOS DATOS PERTIENTES A LA DEFUNCION
          -----------------------------------------------------------------------------*/

          if ($indice[ingreso] != $this->ingreso && $indice[evolucion_id] != $this->evolucion)
          {

               $query = "INSERT INTO hc_conducta_defuncion
                         (ingreso, evolucion_id, fecha, tipo_certificado_id, usuario_id)
                         VALUES
                         (".$this->ingreso.",".$this->evolucion.",'$fechaHora',$certificado, ".UserGetUID().")";

               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               else
               {
                $this->RegistrarSubmodulo($this->GetVersion());
                $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
               }
          }
          else
          {
               $query = "UPDATE hc_conducta_defuncion
                         SET fecha = '$fechaHora',
                              tipo_certificado_id = $certificado,
                              usuario_id = ".UserGetUID()."
                         WHERE ingreso = ".$this->ingreso."
                         AND evolucion_id = ".$this->evolucion.";";

               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               else
               {
               $this->RegistrarSubmodulo($this->GetVersion());
               $this->frmError["MensajeError"]="ACTUALIZACION SATISFACTORIA.";
               }
          }
     /*-----------------------------------------------------------------------------
          SELECCIONO UN NUEVO REGISTRO PARA ACTUALIZAR DATOS
          O PARA CREAR UN NUEVO REGISTRO
          -----------------------------------------------------------------------------*/

          list($dbconn) = GetDBconn();
          $query = "SELECT *
                    FROM hc_conducta_defuncion_mujeres
                    WHERE ingreso = ".$this->ingreso."
                    AND evolucion_id = ".$this->evolucion.";";

          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while(!$resulta->EOF)
          {
               $center=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }


          /*-----------------------------------------------------------------------------
          INSERTO EL CENTRO DE REMISION DONDE SERA ENVIADO EL PACIENTE
          -----------------------------------------------------------------------------*/
          if (!empty($estado))
          {
               $centro="'$estado'";
          }
          else
          {
               $estado= 'NULL';
          }

          if (!empty($semanas))
          {
               $semanas="'$semanas'";
          }
          else
          {
               $semanas= 'NULL';
          }

          if (!empty($meses))
          {
               $meses="'$meses'";
          }
          else
          {
               $meses= 'NULL';
          }

          if ($center[ingreso] != $this->ingreso && $center[evolucion_id] != $this->evolucion)
          {
               $query = "INSERT INTO hc_conducta_defuncion_mujeres
                         (ingreso, evolucion_id, sw_embarazada,
                         sw_semanas_embarazo, sw_meses_embarazo)
                         VALUES
                         (".$this->ingreso.",".$this->evolucion.",$estado,
                         $semanas,$meses)";

               $result=$dbconn->Execute($query);

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               else
               {
                    $this->RegistrarSubmodulo($this->GetVersion());
                    $this->frmError["MensajeError"]="LOS ESTADOS FUERON GUARDADOS SATISFACTORIA.";
               }
          }
          else
          {
               $query = "UPDATE hc_conducta_defuncion_mujeres
                         SET sw_embarazada = $estado,
                              sw_semanas_embarazo = $semanas,
                              sw_meses_embarazo = $meses
                         WHERE ingreso = ".$this->ingreso."
                         AND evolucion_id = ".$this->evolucion.";";

               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               else
               {
                    $this->RegistrarSubmodulo($this->GetVersion());
                    $this->frmError["MensajeError"]="ACTUALIZACION SATISFACTORIA.";
               }

          }
          /*-----------------------------------------------------------------------------
          SE UTILIZA PARA ACTUALIZAR LOS MOTIVOS DE REFERENCIA
          POR LOS CUALES LOS PACIENTES SON REMITIDOS
          -----------------------------------------------------------------------------*/
          list($dbconn) = GetDBconn();
          $query = "SELECT *
                    FROM hc_conducta_defuncion_motivo
                    WHERE ingreso = ".$this->ingreso."
                    AND evolucion_id = ".$this->evolucion.";";

          $resulta=$dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while(!$resulta->EOF)
          {
               $update=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }

          if (!empty ($update))
          {
               $query = "DELETE FROM hc_conducta_defuncion_motivo
                         WHERE evolucion_id=".$this->evolucion."
                         AND ingreso = ".$this->ingreso.";";

          $resulta=$dbconn->Execute($query);

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
          }

          /*-----------------------------------------------------------------------------
          INSERTO LOS MOTIVOS POR LOS CUALES LOS PACIENTES SON REMITIDOS
          -----------------------------------------------------------------------------*/

          $query="INSERT INTO hc_conducta_defuncion_motivo (ingreso,
                                                                      evolucion_id,
                                                                      motivo_defuncion_id)
                                                            VALUES (".$this->ingreso.",
                                                                      ".$this->evolucion.",
                                                                      $sitio_d);";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               $this->RegistrarSubmodulo($this->GetVersion());
               $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
          }
          $_SESSION['INSERTO'] = '1';
          return true;
     }


	/**
	* Busca los Motivos de Defuncion
	* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
	* @access public
	* @return array
	* @param string plan_id
	*/
	function Motivos_Defuncion()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query = "SELECT motivo_defuncion_id, descripcion
				FROM hc_motivo_defuncion;";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
		$motivos[$i]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		$i++;
		}
		return $motivos;
	}


	/**
	* Busca Autor de expedicion del certificado de defuncion
	* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
	* @access public
	* @return array
	* @param string plan_id
	*/
	function Tipos_Certificados()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_certificado_id, descripcion
				FROM hc_tipo_certificado
				ORDER BY indice_orden ASC;";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
		$tipos[$i]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		$i++;
		}
		return $tipos;
	}


     /**
     * Busca sexo del paciente del certificado de defuncion
     * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
     * @access public
     * @return array
     * @param string plan_id
     */
    	function SexodePaciente()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="SELECT sexo_id FROM pacientes
				WHERE tipo_id_paciente='".$this->tipoidpaciente."'
				AND paciente_id='".$this->paciente."';";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$sexpaciente[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		return $sexpaciente;
	}


	/**
	* Consulta de Expedicion de Cerificado de defuncion
	* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
	* @access public
	* @return array
	* @param string plan_id
	*/

	function GetDatos_Certificado()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT A.fecha, A.tipo_certificado_id, A.usuario_id,
					B.descripcion, C.nombre, C.descripcion
			FROM hc_conducta_defuncion AS A
			LEFT JOIN hc_tipo_certificado AS B ON(A.tipo_certificado_id = B.tipo_certificado_id)
			LEFT JOIN system_usuarios AS C ON(A.usuario_id = C.usuario_id)
			WHERE A.ingreso =".$this->ingreso."
			AND A.evolucion_id =".$this->evolucion.";";

		$resulta = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$info1 = $resulta->GetRows();
			return $info1;
		}
	}

	/**
	* Consulta de Expedicion de Cerificado de defuncion
	* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
	* @access public
	* @return array
	* @param string plan_id
	*/

	function GetDatos_Motivo()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT B.descripcion
				FROM hc_motivo_defuncion AS B, hc_conducta_defuncion_motivo AS A
				WHERE A.motivo_defuncion_id = B.motivo_defuncion_id
				AND A.ingreso =".$this->ingreso."
				AND A.evolucion_id =".$this->evolucion.";";

		$resulta = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			list ($info2) = $resulta->FetchRow();
			return $info2;
		}
	}

	/**
	* Consulta de Expedicion de Cerificado de defuncion
	* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
	* @access public
	* @return array
	* @param string plan_id
	*/

	function GetDatos_ConductaMujer()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT sw_embarazada,sw_semanas_embarazo,sw_meses_embarazo
				FROM hc_conducta_defuncion_mujeres
				WHERE ingreso =".$this->ingreso."
				AND evolucion_id =".$this->evolucion.";";

		$resulta = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$info3 = $resulta->GetRows();
			return $info3;
		}
	}
}
?>
