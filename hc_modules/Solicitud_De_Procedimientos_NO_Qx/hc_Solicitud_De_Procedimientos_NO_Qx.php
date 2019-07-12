<?
/**
* Submodulo para la Solicitud de Procedimientos NO Quirurgicos.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Solicitud_De_Procedimientos_NO_Qx.php,v 1.13 2007/02/13 20:22:16 tizziano Exp $
*/

//ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));

class Solicitud_De_Procedimientos_NO_Qx extends hc_classModules
{
	var $limit;
	var $conteo;
	var $PermitirNoProfesionales='1';
	var $capitulo='';
	var $grupo='';
	var $categoria='';

//clzc - ads
	function Solicitud_De_Procedimientos_NO_Qx()
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
          return true;
	}

/******************VA EN EL CONSTRUCTOR*************/
// 			//definiendo el tipo de usuario que esta ingresando a la aplicacion
// 			if (($this->tipo_profesional=='1') OR ($this->tipo_profesional=='2') OR ($_REQUEST['PermitirNoProfesionales']))
// 			{
//         $PermitirNoProfesionales='1'; //usuario medico o no profesional permitido
// 			}
// 			//fin del tipo de usuario
/******************VA EN EL CONSTRUCTOR*************/  
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
// 		'autor'=>'CLAUDIA LILIANA ZUÑIGA CAÑON',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


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
        	list($dbconn) = GetDBconn();
		$query="SELECT count(*)
			FROM hc_os_solicitudes AS A JOIN hc_os_solicitudes_no_quirurgicos AS B
			ON (A.hc_os_solicitud_id=B.hc_os_solicitud_id)
			WHERE A.evolucion_id=".$this->evolucion.";";
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


//cor - clzc - ads
     function GetForma()
     {
          $pfj=$this->frmPrefijo;
          if(empty($_REQUEST['accion'.$pfj]))
          {
               $this->frmForma();
          }
          else
          {
               if($_REQUEST['accion'.$pfj]=='cambiar_diagnostico')
               {
                    $this->CambiarDiagnosticos();
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj],'',$_REQUEST['cantidad'.$pfj],$_REQUEST['sw_cantidad'.$pfj]);
               }

			if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_NO_Quirurgicos')
               {
                    $vectorA= $this->Busqueda_Avanzada_NO_Quirurgicos();
                    $this-> frmForma_Seleccion_No_Qx($vectorA);
               }

               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
               {
                    $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                    $this-> frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj],$vectorD,$_REQUEST['cantidad'.$pfj],$_REQUEST['sw_cantidad'.$pfj]);
               }

               if($_REQUEST['accion'.$pfj]=='eliminar')
               {
                    $this->Eliminar_No_Qx_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
                    $this->frmForma();
               }

               if($_REQUEST['accion'.$pfj]=='observacion')
               {
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj],'',$_REQUEST['cantidad'.$pfj],$_REQUEST['sw_cantidad'.$pfj],$_REQUEST['solicitud_ambulatoria'.$pfj]);
               }

							if($_REQUEST['accion'.$pfj]=='modificar')
               {
                    $this->Modificar_NO_Qx_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['solicitud_ambulatoria'.$pfj]);
                    $this->frmForma();
               }

               if($_REQUEST['accion'.$pfj]=='CambioAmbulatorio')
               {
                    $this->CambioAmbulatorio_NO_QX($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['valor'.$pfj]);
                    $this->frmForma();
               }

			if($_REQUEST['accion'.$pfj]=='insertar_varias')
               {
          		$this->Insertar_Varias_Solicitudes();
                    $this->frmForma();
               }
               
               if($_REQUEST['accion'.$pfj]=='insertar_varios_diagnosticos')
               {
                    $this->Insertar_Varios_Diagnosticos();
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj],'',$_REQUEST['cantidad'.$pfj],$_REQUEST['sw_cantidad'.$pfj]);
               }
          
               if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico')
               {
	               $this->Eliminar_Diagnostico_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj], $_REQUEST['codigo'.$pfj]);
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj],'',$_REQUEST['cantidad'.$pfj],$_REQUEST['sw_cantidad'.$pfj]);
               }
          }
          return $this->salida;
	}


//OKI
	function Busqueda_Avanzada_NO_Quirurgicos()
	{   
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$opcion      = ($_REQUEST['criterio1'.$pfj]);
		$cargo       = ($_REQUEST['cargo'.$pfj]);
		$descripcion =STRTOUPPER($_REQUEST['descripcion'.$pfj]);

		$filtroTipoCargo = '';
		$busqueda1 = '';
		$busqueda2 = '';

   
		if($opcion != '-1' && !empty($opcion))
          {
               $filtroTipoCargo=" AND a.tipo_cargo = '$opcion'";
          }

          if ($cargo != '')
          {
               $busqueda1 =" AND a.cargo LIKE '$cargo%'";
          }

          if ($descripcion != '')
          {
               if (eregi('%',$descripcion))
               {
                    $busqueda2 ="AND a.descripcion LIKE '$descripcion'";
               }
               else
               {
				$busqueda2 ="AND a.descripcion LIKE '%$descripcion%'";
               }
          }

          if(empty($_REQUEST['conteo'.$pfj]))
          {													
               $query = "SELECT count(*) FROM (SELECT DISTINCT a.cargo,
                         a.descripcion, a.grupo_tipo_cargo, a.sw_cantidad,
                         c.descripcion as tipo, d.tipo_cargo
                         FROM cups a, no_qx_grupos_tipo_cargo b, grupos_tipos_cargo c,
                         tipos_cargos d					
                         WHERE a.grupo_tipo_cargo = c.grupo_tipo_cargo 
                         AND b.grupo_tipo_cargo = c.grupo_tipo_cargo 
                         AND a.tipo_cargo = d.tipo_cargo AND c.grupo_tipo_cargo = d.grupo_tipo_cargo
                         $filtroTipoCargo$busqueda1$busqueda2) as a";

		
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

		//SI QUIERO EL NOMBRE DEL TIPO CARGO CAMBIO c.descripcion as tipo POR d.descripcion as tipo
          $query = "SELECT DISTINCT a.sw_cantidad, a.cargo, a.descripcion, a.grupo_tipo_cargo, 
                         c.descripcion as tipo, d.tipo_cargo
                    FROM cups a, no_qx_grupos_tipo_cargo b, grupos_tipos_cargo c,
                         tipos_cargos d			
                    WHERE a.grupo_tipo_cargo = c.grupo_tipo_cargo 
                    AND b.grupo_tipo_cargo = c.grupo_tipo_cargo
                    AND a.sw_estado = '1'
                    AND a.tipo_cargo = d.tipo_cargo AND c.grupo_tipo_cargo = d.grupo_tipo_cargo
                    $filtroTipoCargo$busqueda1$busqueda2
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


	//cor - clzc-jea - ads
	function Busqueda_Avanzada_Diagnosticos()
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
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != ''))
		{
               if (eregi('%',$diagnostico))
               {
			    $busqueda2 ="AND diagnostico_nombre LIKE '$diagnostico'";
               }
               else
               {
                    $busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
               }
		}

		if (($diagnostico != '') AND ($codigo == ''))
		{
               if (eregi('%',$diagnostico))
               {
                    $busqueda2 ="WHERE diagnostico_nombre LIKE '$diagnostico'";
               }
               else
               {
                    $busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
               }
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


		//filtro por clasificacion de diagnosticos
		$filtro='';
		if(empty($busqueda1) AND empty($busqueda2))
		{
			$filtro = "WHERE (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
					 AND   (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
					 AND   (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
		}
		else
		{
			$filtro = "AND (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
					 AND (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
					 AND (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
		}

		$filtro1='';
		if(!empty($this->capitulo))
		{
			$filtro1 = " AND (B.capitulo='".$this->capitulo."' OR B.capitulo is null)";
		}
		if(!empty($this->grupo))
		{
			$filtro1 .= " AND (B.grupo='".$this->grupo."' OR B.grupo is null)";
		}
		if(!empty($this->categoria))
		{
			$filtro1 .= " AND (B.categoria='".$this->categoria."' OR B.categoria is null)";
		}

		$query = "SELECT diagnostico_id, diagnostico_nombre
                    FROM diagnosticos
                    $busqueda1 $busqueda2
                    $filtro $filtro1
                    order by diagnostico_id
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
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		return $var;
	}	


     //OKI
     function Insertar_Varias_Solicitudes()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          foreach($_REQUEST['op'.$pfj] as $index=>$codigo)
          {
          	//realiza el id manual de la tabla
               $query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
               $result=$dbconn->Execute($query1);
               $hc_os_solicitud_id=$result->fields[0];
     		//fin de la operacion
               //cambio dar
               $cant=$_REQUEST['cantidad'.$pfj.$index];
               //fin cambio dar
     
               $query2="INSERT INTO hc_os_solicitudes
                         (hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, cantidad, paciente_id, tipo_id_paciente)
                         VALUES
                         ($hc_os_solicitud_id,".$this->evolucion.",
                         '".$codigo."', 'PNQ',
                         ".$this->plan_id.",
                         $cant,
                         '".$this->paciente."',
                         '".$this->tipoidpaciente."')";
     
               $resulta=$dbconn->Execute($query2);
          	if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar en hc_os_solicitudes";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    $query3="INSERT INTO hc_os_solicitudes_no_quirurgicos
                             (hc_os_solicitud_id)
                             VALUES  ($hc_os_solicitud_id);";
     
                    $resulta1=$dbconn->Execute($query3);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_os_solicitudes_no_quirurgicos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
          $this->RegistrarSubmodulo($this->GetVersion());            
		return true;
     }

     //OKI
     function Consulta_Solicitud_No_Qx()
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();
          $criterio='';
          if(!empty($this->plan_id)){
               $criterio = ",informacion_cargo('".$this->plan_id."',a.cargo,'".$this->departamento."')";
          }
          $query= "select  sw_ambulatorio,d.evolucion_id, d.ingreso, a.cargo,a.cantidad, a.hc_os_solicitud_id,
                         b.descripcion, d.fecha, e.observacion, h.descripcion as tipo,b.sw_cantidad, e.solicitud_ambulatoria
                         $criterio
                    from hc_os_solicitudes a, hc_os_solicitudes_no_quirurgicos e,
                         cups b, hc_evoluciones d, grupos_tipos_cargo h
                    where a.paciente_id = '".$this->paciente."'
                    and a.tipo_id_paciente = '".$this->tipoidpaciente."'
                    and a.hc_os_solicitud_id = e.hc_os_solicitud_id
                    and d.ingreso= ".$this->ingreso."
                    and a.evolucion_id = d.evolucion_id
                    AND a.cargo = b.cargo 
                    AND b.grupo_tipo_cargo = h.grupo_tipo_cargo
                    order by a.hc_os_solicitud_id";
     
          $result = $dbconnect->Execute($query);
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de solictud de apoyos";
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
          $result->Close();
          return $vector;
     }


     //OKI
     function Eliminar_No_Qx_Solicitado($hc_os_solicitud_id)
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          $query="DELETE FROM hc_os_solicitudes_diagnosticos
	             WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
               $dbconn->RollbackTrans();
               return false;
          }
          else
          {
	          $query1="DELETE FROM hc_os_solicitudes_no_quirurgicos
                       WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
          	$resulta1=$dbconn->Execute($query1);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
               	$dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    $query2="DELETE FROM hc_os_solicitudes
                    	    WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                    $resulta1=$dbconn->Execute($query2);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    else
                    {
               		$dbconn->CommitTrans();
                         $this->frmError["MensajeError"]="SOLICITUD ELIMINADA SATISFACTORIAMENTE.";
                    }
               }
          }
 		return true;
     }	

     //OKI
     function Modificar_NO_Qx_Solicitado($hc_os_solicitud_id,$solicitud_ambulatoria)
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();

          //cambio dar
          if(!empty($_REQUEST['cantidad'.$pfj]))
          {
               $cantidad = $_REQUEST['cantidad'.$pfj];
               if (is_numeric($cantidad)==0)
               {
                    $this->frmError["MensajeError"]="DIGITE CANTIDADES VALIDAS.";
                    return false;
               }
          }
          else
          {
               $cantidad =1;
          }
          //fin cambi dar

          $obs = $_REQUEST['obs'.$pfj];
          if($solicitud_ambulatoria){$solicitud_ambulatoria=1;}else{$solicitud_ambulatoria=0;}
          $query= "UPDATE hc_os_solicitudes_no_quirurgicos SET observacion = '$obs'
                   WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al actualizar la observacion en hc_os_solicitudes_no_quirurgicos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          //cambio dar
          $query= "UPDATE hc_os_solicitudes SET cantidad = ".$cantidad.", sw_ambulatorio='$solicitud_ambulatoria'
	              WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al actualizar la cantidad en hc_os_solicitudes";
               $this->frmError["MensajeError"]="NO SE LOGRO ACTUALIZAR LA INTERCONSULTA";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          //fin cambi dar

          $dbconn->CommitTrans();
		$this->RegistrarSubmodulo($this->GetVersion());            
          return true;
	}

	function CambioAmbulatorio_NO_QX($hc_os_solicitud_id,$valor){
     	$pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query = "UPDATE hc_os_solicitudes SET sw_ambulatorio='$valor' 
                    WHERE hc_os_solicitud_id=$hc_os_solicitud_id";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al actualizar la observacion en hc_os_solicitudes_no_quirurgicos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $this->RegistrarSubmodulo($this->GetVersion());            
		return true;
     }


     //OKI
     function tipos()
     {
 		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		 $query= "SELECT a.tipo_cargo, a.descripcion
						 FROM tipos_cargos a, no_qx_grupos_tipo_cargo b
						 WHERE a.grupo_tipo_cargo = b.grupo_tipo_cargo";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al cargar los tipos";
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
          $result->Close();
          return $vector;
	}




//OKI
     function Diagnosticos_Solicitados($hc_os_solicitud_id)
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query="SELECT b.diagnostico_id, a.diagnostico_nombre, 
          	   		b.tipo_diagnostico, b.sw_principal
                  FROM diagnosticos a, hc_os_solicitudes_diagnosticos b
                  WHERE b.hc_os_solicitud_id = $hc_os_solicitud_id 
                  AND a.diagnostico_id = b.diagnostico_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla apoyod_tipos";
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
          $result->Close();
          return $vector;
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
			$tipo_dx = $_REQUEST['dx'.$index.$pfj];
			if($tipo_dx == '')
			{
				$tipo_dx = '1';
			}
               
               $arreglo=explode(",",$codigo);

               //BUSQUEDA DE DX REPETIDO EN SOLICITUD
               $query="SELECT count(*) 
                       FROM hc_os_solicitudes_diagnosticos
                       WHERE hc_os_solicitud_id = '".$arreglo[0]."'
                       AND diagnostico_id = '".$arreglo[1]."';";
              
               $resulta=$dbconn->Execute($query);
			if ($resulta->fields[0]==0)
               { 
                    //BUSQUEDA DE DX PRINCIPAL EN SOLICITUD
                    $sql="SELECT count(*) 
                            FROM hc_os_solicitudes_diagnosticos
                            WHERE hc_os_solicitud_id = '".$arreglo[0]."'
                            AND sw_principal = '1';";
                    $resulta=$dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
                         return false;
                    }
                    
                    //INSERCION DE 1 DX PRINCIPAL
                    if($resulta->fields[0]==0)
                    {
                         $query="INSERT INTO hc_os_solicitudes_diagnosticos
                                        (hc_os_solicitud_id, diagnostico_id, tipo_diagnostico, sw_principal)
                                 VALUES ('".$arreglo[0]."', '".$arreglo[1]."', '$tipo_dx', '1');";
                    }
                    //INSERCION DE LOS DEMAS DX'S (NO PRINCIPALES)
                    else
                    {
                         $query="INSERT INTO hc_os_solicitudes_diagnosticos
                                        (hc_os_solicitud_id, diagnostico_id, tipo_diagnostico, sw_principal)
                                 VALUES ('".$arreglo[0]."', '".$arreglo[1]."', '$tipo_dx', '0');";
                    }
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
                         return false;
                    }
                    else
                    {
                          $this->RegistrarSubmodulo($this->GetVersion());            
                         $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                    }
               }
               //FIN BUSQUEDA DE DX REPETIDO EN INGRESO
               else
               {
                    $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
               }
		}// Fin foreach
		return true;
	}

     
     function CambiarDiagnosticos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
     	$sql="UPDATE hc_os_solicitudes_diagnosticos
               SET sw_principal='0' 
               WHERE hc_os_solicitud_id='".$_REQUEST['hc_os_solicitud_id'.$pfj]."';";
          $resulta=$dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar en hc_diagnosticos_ingreso";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
               return false;
          }
     	$sql="UPDATE hc_os_solicitudes_diagnosticos 
                    SET sw_principal='1' 
                    WHERE hc_os_solicitud_id=".$_REQUEST['hc_os_solicitud_id'.$pfj]." 
                    AND diagnostico_id='".$_REQUEST['codigo'.$pfj]."';";
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


//cor - clzc - ads
	function Eliminar_Diagnostico_Solicitado($hc_os_solicitud_id, $codigo)
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="DELETE FROM hc_os_solicitudes_diagnosticos
          	   WHERE diagnostico_id = '$codigo'
                  AND hc_os_solicitud_id=".$hc_os_solicitud_id.";";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL DIAGNOSTICO";
			return false;
		}
		else
		{
			$sql="SELECT diagnostico_id, sw_principal
               	 FROM hc_os_solicitudes_diagnosticos
                     WHERE hc_os_solicitud_id =".$hc_os_solicitud_id."
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
				$sql2="UPDATE hc_os_solicitudes_diagnosticos
                    	  SET sw_principal='1' 
                           WHERE hc_os_solicitud_id =".$hc_os_solicitud_id."
                           AND diagnostico_id='".$vector['diagnostico_id']."';";
				$resulta=$dbconn->Execute($sql2);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al insertar en hc_diagnosticos_ingreso";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				 
        return true;
			}
		}
		$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." FUE ELIMINADO SATISFACTORIAMENTE.";
		return true;
	}

}
?>
