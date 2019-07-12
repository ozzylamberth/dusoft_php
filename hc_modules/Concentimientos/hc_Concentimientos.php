<?php

/**
* Submodulo de Concentimientos.
*
* Submodulo para manejar la información de los concentimientos en un ingreso odontologicos de un paciente en una evolución.
* @author Carlos A. Henao <carlosarturohenao@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Concentimientos.php,v 1.4 2007/07/09 19:20:53 tizziano Exp $
*/

/**
* Concentimientos
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de Concentimientos en un ingreso odontologico.
*/

class Concentimientos extends hc_classModules
{
	var $limit;
	var $conteo;
	var $capitulo='';
	var $grupo='';
	var $categoria='';

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function Concentimientos()
	{
		if(!empty($_REQUEST['capitulo']))
		{
			$this->capitulo=$_REQUEST['capitulo'];
		}
		if(!empty($_REQUEST['grupo']))
		{
			$this->grupo=$_REQUEST['grupo'];
		}
		if(!empty($_REQUEST['categoria']))
		{
			$this->categoria=$_REQUEST['categoria'];
		}
		
		$this->limit=GetLimitBrowser();
		$this->salida = '';
		return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/27/2005',
		'autor'=>'TIZZIANO PEREA OCORO',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


/**
* Esta función retorna los datos de la impresión de la consulta del submodulo.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

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
		return true;
	}


	/**
	* Esta función retorna la presentación del submodulo (consulta o inserción).
	*
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
			if($_REQUEST['accion'.$pfj]=='Busqueda_Dx')
			{
				$this->frm_Busqueda_Dx($_REQUEST['area'.$pfj]);
			}

			if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
			{
				$vectorD= $this->Busqueda_Avanzada_Diagnosticos($_REQUEST['area'.$pfj]);
				$this->frm_Busqueda_Dx($_REQUEST['area'.$pfj], $vectorD);
			}

			if($_REQUEST['accion'.$pfj]=='insertar_varios_diagnosticos')
			{
				$this->Insertar_Varios_Diagnosticos();
				$this-> frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico')
			{
				$this->Eliminar_Diagnostico_Solicitado($_REQUEST['diagnostico_id'.$pfj], $_REQUEST['principal'.$pfj], $_REQUEST['area_E'.$pfj]);
				$this->frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='cambiar_diagnostico')
			{
				$this->CambiarDiagnosticos();
				$this-> frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='Insertar_Descripcion')
			{
				$this->InsertarDescripcion();
				$this-> frmForma();
			}
               
      if($_REQUEST['accion'.$pfj]=='primera_vez')
			{
				$this->frm_Diagnostico_Odontologico_PrimeraVez();
			}
      if($_REQUEST['accion'.$pfj]=='BuscarItems')
			{ 
				$this->Get_Concentimientos_Items();
			}
		}
		return $this->salida;
	}


	function Get_Concentimientos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT id_concentimiento, descripcion, indice_orden, formato
								FROM hc_concentimientos;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al seleccionar en hc_concentimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="ERROR EN BUSQUEDA DE DATOS";
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

	function Get_Concentimientos_Items()
	{ 
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$con=$_REQUEST["con".$pfj];
		$query="SELECT id_concentimiento,descripcion,indice_orden,item_id
						FROM hc_concentimientos_items
						WHERE id_concentimiento=$con
						ORDER BY indice_orden DESC;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al seleccionar en hc_sub_diagnosticos_odontologicos_areas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="ERROR EN BUSQUEDA DE DATOS";
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
		$this-> frmForma($vector);
		return true;
	}

	function CambiarDiagnosticos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="UPDATE hc_diagnosticos_ingreso
			  SET sw_principal='0' WHERE evolucion_id=".$this->evolucion.";";
		$resulta=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_diagnosticos_ingreso";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
			return false;
		}
		$sql="UPDATE hc_diagnosticos_ingreso
			  SET sw_principal='1' WHERE evolucion_id=".$this->evolucion."
			  AND tipo_diagnostico_id='".$_REQUEST['diagnostico_id'.$pfj]."';";
		$resulta=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_diagnosticos_ingreso";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
			return false;
		}
		return true;
	}


	/**
	* Esta función inserta los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/
	function Insertar_Varios_Diagnosticos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();

		//Inicio de Insercion con RollbackTrans().
		$dbconn->BeginTrans();
		$area = $_REQUEST['area'.$pfj];

		foreach($_REQUEST['opD'.$pfj] as $index=>$codigo)
		{
			$tipo_dx = $_REQUEST['dx'.$index.$pfj];
			if($tipo_dx == '')
			{
				$tipo_dx = '1';
			}

			$query="INSERT INTO hc_sub_diagnosticos_odontologicos_diagnosticos (evolucion_id,
                                                                                   area_evaluada_id,
                                                                                   diagnostico_id,
                                                                                   tipo_diagnostico)
                                                                           VALUES(".$this->evolucion.",
                                                                                  '$area',
                                                                                  '".$codigo."',
                                                                                  '$tipo_dx');";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en  hc_sub_diagnosticos_odontologicos_diagnosticos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$codigo." YA FUE ASIGNADO.";
				$dbconn->RollbackTrans();
				return false;
			}

               $SQLDX = "SELECT count(*)
                        FROM hc_diagnosticos_ingreso
                        WHERE tipo_diagnostico_id='".$codigo."'
                        AND evolucion_id=".$this->evolucion.";";
               
               $resulta=$dbconn->Execute($SQLDX);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en  hc_sub_diagnosticos_odontologicos_diagnosticos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$codigo." YA FUE ASIGNADO.";
				$dbconn->RollbackTrans();
				return false;
			}
               list($numdx) = $resulta->FetchRow();
               
               if($numdx == 0)
               {
                    $sql="SELECT count(*) FROM hc_diagnosticos_ingreso WHERE evolucion_id=".$this->evolucion.";";
                    $resulta=$dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_diagnosticos_ingreso";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$codigo." YA FUE ASIGNADO.";
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    if($resulta->fields[0]==0)
                    {
                         $query="INSERT INTO hc_diagnosticos_ingreso (usuario_id,
                                                                      tipo_diagnostico_id,
                                                                      evolucion_id,
                                                                      sw_principal,
                                                                      descripcion,
                                                                      tipo_diagnostico)
                                                            VALUES( ".$this->usuario_id.",
                                                                      '".$codigo."',
                                                                      ".$this->evolucion.",
                                                                      '1',
                                                                      NULL,
                                                                      '$tipo_dx');";
                    }
                    else
                    {
                         $query="INSERT INTO hc_diagnosticos_ingreso (usuario_id,
                                                                      tipo_diagnostico_id,
                                                                      evolucion_id,
                                                                      sw_principal,
                                                                      descripcion,
                                                                      tipo_diagnostico)
                                                            VALUES( ".$this->usuario_id.",
                                                                      '".$codigo."',
                                                                      ".$this->evolucion.",
                                                                      '0',
                                                                      NULL,
                                                                      '$tipo_dx');";
                    }
     
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_diagnosticos_ingreso";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$codigo." YA FUE ASIGNADO.";
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    else
                    {
                         $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                    }
               }

		}
		
		$dbconn->CommitTrans();
		return true;
	}


	/**
	* Esta función borra los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	function Eliminar_Diagnostico_Solicitado($diagnostico_id, $principal, $area_E)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          $SQLDX = "SELECT count(*)
                    FROM hc_sub_diagnosticos_odontologicos_diagnosticos
                    WHERE diagnostico_id='".$diagnostico_id."'
                    AND evolucion_id=".$this->evolucion.";";
               
          $resulta=$dbconn->Execute($SQLDX);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar en  hc_sub_diagnosticos_odontologicos_diagnosticos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$codigo." YA FUE ASIGNADO.";
               $dbconn->RollbackTrans();
               return false;
          }
          list($numdx) = $resulta->FetchRow();

		if($numdx > 1)
          {
               $query="DELETE FROM hc_sub_diagnosticos_odontologicos_diagnosticos
                              WHERE diagnostico_id = '$diagnostico_id'
                              AND evolucion_id=".$this->evolucion."
                              AND area_evaluada_id = '$area_E';";
               $resulta=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL DIAGNOSTICO";
                    return false;
               }
          }
          elseif($numdx == 1)
          {
               $query="DELETE FROM hc_sub_diagnosticos_odontologicos_diagnosticos
                              WHERE diagnostico_id = '$diagnostico_id'
                              AND evolucion_id=".$this->evolucion."
                              AND area_evaluada_id = '$area_E';";
               $resulta=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL DIAGNOSTICO";
                    return false;
               }
               
               $query="DELETE FROM hc_diagnosticos_ingreso
                              WHERE tipo_diagnostico_id = '$diagnostico_id'
                              AND evolucion_id=".$this->evolucion.";";
               $resulta=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL DIAGNOSTICO";
                    return false;
               }
			
               if ($principal == '1')
			{
                   $sql="SELECT tipo_diagnostico_id, sw_principal
                         FROM hc_diagnosticos_ingreso
                         WHERE evolucion_id=".$this->evolucion." LIMIT 1 OFFSET 0;";
     
                   $resulta=$dbconn->Execute($sql);
                   if ($dbconn->ErrorNo() != 0)
                   {
                        $this->error = "NO HAY DIAGNOSTICOS DISPONIBLES";
                        $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                        return false;
                   }
                   else
                   {
                        list ($diagnostico) = $resulta->fetchRow();
                   }
                   
                   $sql2="UPDATE hc_diagnosticos_ingreso
                          SET sw_principal='1'
                          WHERE evolucion_id=".$this->evolucion."
                          AND tipo_diagnostico_id='".$diagnostico."';";
                   $resulta=$dbconn->Execute($sql2);
                   if ($dbconn->ErrorNo() != 0)
                   {
                        $this->error = "Error al insertar en hc_diagnosticos_ingreso";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                   }
 			}
          }

		$this->frmError["MensajeError"]="EL DIAGNOSTICO FUE ELIMINADO SATISFACTORIAMENTE.";
		return true;
	}

	/**
	* Esta función Consulta los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	function ConsultaDiagnosticoI()
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "SELECT A.diagnostico_id, A.evolucion_id, 
          		A.area_evaluada_id, A.tipo_diagnostico,
                    B.usuario_id, B.sw_principal, 
                    C.diagnostico_nombre

				FROM hc_sub_diagnosticos_odontologicos_diagnosticos AS A,
                    hc_diagnosticos_ingreso AS B, diagnosticos AS C,
                    hc_evoluciones AS D

                    WHERE A.diagnostico_id = B.tipo_diagnostico_id
                    AND A.evolucion_id = B.evolucion_id
                    AND A.diagnostico_id = C.diagnostico_id
                    AND A.evolucion_id = D.evolucion_id
                    AND D.ingreso = ".$this->ingreso."

				ORDER BY A.diagnostico_id;";
                    
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
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
	* Esta función Consulta descripcion los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	function ConsultarDescripcion()
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "SELECT detalle_diagnosticos
				  FROM hc_sub_diagnosticos_odontologicos_notas
				  WHERE evolucion_id = ".$this->evolucion.";";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}

		list($vector) = $result->fetchRow();
		return $vector;
	}

	/**
	* Esta función Consulta avanzada de DX.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	function Busqueda_Avanzada_Diagnosticos($area)
	{
		$pfj=$this->frmPrefijo;

		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);

		list($dbconn) = GetDBconn();
		$codigo = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != '')
		{
			$busqueda1 =" WHERE A.diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != ''))
		{
			if (eregi('%',$diagnostico))
			{
				$busqueda2 ="AND B.diagnostico_nombre LIKE '$diagnostico'";
        	}
			else
			{
				$busqueda2 ="AND B.diagnostico_nombre LIKE '%$diagnostico%'";
			}
		}

		if (($diagnostico != '') AND ($codigo == ''))
		{
        	if (eregi('%',$diagnostico))
			{
				$busqueda2 ="WHERE B.diagnostico_nombre LIKE '$diagnostico'";
			}
			else
			{
				$busqueda2 ="WHERE B.diagnostico_nombre LIKE '%$diagnostico%'";
			}
		}

		if (($diagnostico == '') AND ($codigo == ''))
		{
			$busqueda3 = "WHERE A.area_evaluada_id = '$area'";
		}

		if(($diagnostico != '') OR ($codigo != ''))
		{
			$busqueda3 = "AND A.area_evaluada_id = '$area'";
		}

		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query ="SELECT count(*)
					 FROM hc_sub_diagnosticos_odontologicos_maestro
					 WHERE area_evaluada_id = '$area';";

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

		//filtro por clasificacion de diagnosticos
		$filtro='';
		if(!empty($this->capitulo))
		{
			$filtro = " AND (B.capitulo='".$this->capitulo."' OR B.capitulo is null)";
		}
		if(!empty($this->grupo))
		{
			$filtro .= " AND (B.grupo='".$this->grupo."' OR B.grupo is null)";
		}
		if(!empty($this->categoria))
		{
			$filtro .= " AND (B.categoria='".$this->categoria."' OR B.categoria is null)";
		}

		$query =  "SELECT A.diagnostico_id, B.diagnostico_nombre
					FROM hc_sub_diagnosticos_odontologicos_maestro AS A, diagnosticos AS B
					$busqueda1 $busqueda2 $busqueda3
					AND A.diagnostico_id = B.diagnostico_id
					AND (B.sexo_id='".$this->datosPaciente['sexo_id']."' OR B.sexo_id is null)
					AND (B.edad_max>=".$edad_paciente[edad_en_dias]." OR B.edad_max is null)
					AND (B.edad_min<=".$edad_paciente[edad_en_dias]." OR B.edad_min is null )
					$filtro
					ORDER BY A.diagnostico_id
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
		{       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
				return false;
		}
		 return $var;
	}

	/**
	* Esta función Inserta datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	function InsertarDescripcion()
	{
		$id_con=$_SESSION['con']['busquedas'];
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();

		$busquedas=$_REQUEST['busquedas']; 
		if($_REQUEST['observacion'.$pfj]<>"")
			$observacion = $_REQUEST['observacion'.$pfj];
		else
			$observacion="";
		$usuario=UserGetUID();
		if($busquedas<>"")
		{
			for($i=0;$i<sizeof($busquedas);$i++)
			{//echo $_REQUEST['con'.$pfj]; exit;
				if($_REQUEST['con'.$pfj.$i])
				{
					if($consentimientosinformados=="")
						$consentimientosinformados=$_REQUEST['con'.$pfj.$i];
					else
						$consentimientosinformados=$consentimientosinformados.'-'.$_REQUEST['con'.$pfj.$i];
				}
				
			}
		}
		if(!$consentimientosinformados)
			$consentimientosinformados=null;

		if($_REQUEST['responsable']==1)
		{
			$query = "INSERT INTO hc_concentimientos_confirmaciones  
								(
								id_concentimiento,
								ingreso,
								evolucion_id,
								usuario_id,
								fecha_registro,
								observacion,
								tipo_id_reponsable,
								id_responsable,
								parentesco_responsable,
								nombre_responsable,
								concentimientos_informados)
								VALUES (
												".$id_con.",
												".$this->ingreso.",
												".$this->evolucion.",
												$usuario,
												now(),
												'$observacion',
												NULL,
												NULL,
												NULL,
												NULL,
												'$consentimientosinformados');";
			$result = $dbconnect->Execute($query); 
		}
		else
		{
			$tipoidrespon=$_REQUEST['TipoDocumentoResponsable'];
			$idrespon=$_REQUEST['DocumentoResponsable'];
			$nombrerespon=$_REQUEST['nombreResponsable'];
			$parenrespon=$_REQUEST['parentescoResponsable'];
			if($tipoidrespon=="")
			{
			$this->frmError["TipoDocumentoResponsable"]=1;
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			$this->frmForma();
			return true;
			}

			if($idrespon=="")
			{
			$this->frmError["DocumentoResponsable"]=1;
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			$this->frmForma();
			return true;
			}

			if($nombrerespon=="")
			{
			$this->frmError["nombreResponsable"]=1;
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			$this->frmForma();
			return true;
			}

			if($parenrespon=="" OR $parenrespon==-1)
			{
			$this->frmError["parentescoResponsable"]=1;
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			$this->frmForma();
			return true;
			}

			$query = "INSERT INTO hc_concentimientos_confirmaciones  
								(
								id_concentimiento,
								ingreso,
								evolucion_id,
								usuario_id,
								fecha_registro,
								observacion,
								tipo_id_reponsable,
								id_responsable,
								parentesco_responsable,
								nombre_responsable,
								concentimientos_informados)
								VALUES (
												".$id_con.",
												".$this->ingreso.",
												".$this->evolucion.",
												$usuario,
												now(),
												'$observacion',
												'".$tipoidrespon."',
												'".$idrespon."',
												'".$nombrerespon."',
												'".$parenrespon."',
												'$consentimientosinformados');";
			$result = $dbconnect->Execute($query);
		}
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_concentimientos_confirmaciones";
			$this->frmError["MensajeError"] = "Error DB : " . $dbconnect->ErrorMsg();
			$this->frmForma();
			return true;
		}
			$this->RegistrarSubmodulo($this->GetVersion());
               $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
			$this->frmForma();
			return true;
	}
     
     function ConsultaDiagnostico_PrimeraVez()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query= "SELECT evolucion_id 
                   FROM hc_odontogramas_primera_vez
                   WHERE tipo_id_paciente = '".$this->tipoidpaciente."'
                   AND paciente_id = '".$this->paciente."'
                   AND sw_activo = '1';";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($primera_Evo) = $resultado->FetchRow();
          $this->primera_Evo = $primera_Evo;
 
          if (!empty($primera_Evo))
          {
               $query = "SELECT A.diagnostico_id, A.evolucion_id, 
                         A.area_evaluada_id, A.tipo_diagnostico,
                         B.usuario_id, B.sw_principal, 
                         C.diagnostico_nombre
     
                         FROM hc_sub_diagnosticos_odontologicos_diagnosticos AS A,
                         hc_diagnosticos_ingreso AS B, diagnosticos AS C,
                         hc_evoluciones AS D
     
                         WHERE A.diagnostico_id = B.tipo_diagnostico_id
                         AND A.evolucion_id = B.evolucion_id
                         AND A.diagnostico_id = C.diagnostico_id
                         AND A.evolucion_id = D.evolucion_id
                         AND D.evolucion_id = ".$primera_Evo."
     
                         ORDER BY A.diagnostico_id;";
               $result = $dbconn->Execute($query);
     
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
     }

          
	function ConsultarDescripcion_PrimeraVez()
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "SELECT detalle_diagnosticos
				  FROM hc_sub_diagnosticos_odontologicos_notas
				  WHERE evolucion_id = ".$this->primera_Evo.";";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}

		list($vector) = $result->fetchRow();
		return $vector;
	}
/**
* Funcion que retorna los tipo de documentos de la base de datos que puede tener el paciente
* @return array
*/
	function tipo_id_paciente(){
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_paciente,descripcion FROM tipos_id_pacientes ORDER BY indice_de_orden";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
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

	function tiposParentescosPaciente(){
    list($dbconn) = GetDBconn();
    $query="SELECT tipo_parentesco_id,descripcion FROM tipos_parentescos";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
			}
		}
	  return $vars;
	}
}

?>
