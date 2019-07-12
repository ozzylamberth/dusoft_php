<?
/**
* Submodulo para la Solicitud de Apoyos Diagnosticos.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Apoyos_Diagnosticos_Solicitud.php,v 1.15 2007/02/13 20:22:16 tizziano Exp $
*/

//ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));

class Apoyos_Diagnosticos_Solicitud extends hc_classModules
{
	var $limit;
	var $conteo;
	var $PermitirNoProfesionales='1';
	var $capitulo='';
	var $grupo='';
	var $categoria='';



//clzc - ads
	function Apoyos_Diagnosticos_Solicitud()
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
			FROM hc_os_solicitudes AS A JOIN
			hc_os_solicitudes_apoyod AS B
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
               //cambio dar
               if($_REQUEST['accion'.$pfj]=='noAmbulatorio')
               {		//pasarlo a no ambulatorio					
	               list($dbconn) = GetDBconn();
                    $query = "UPDATE hc_os_solicitudes SET sw_ambulatorio='0' 
                                             WHERE hc_os_solicitud_id=".$_REQUEST['hc_os_solicitud_id'.$pfj]."";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error INSERT INTO pagares";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->fileError = __FILE__;
                                        $this->lineError = __LINE__;
                                        $dbconn->RollbackTrans();
                                        return false;
                    }										
                    $this->frmError["MensajeError"]="CAMBIO EXITOSO.";
                    $this->frmForma();
                    $this->RegistrarSubmodulo($this->GetVersion());
               }			
               if($_REQUEST['accion'.$pfj]=='ambulatorio')
               {		//pasarlo a ambulatorio
		          list($dbconn) = GetDBconn();
                    $query = "UPDATE hc_os_solicitudes SET sw_ambulatorio='1' 
                                             WHERE hc_os_solicitud_id=".$_REQUEST['hc_os_solicitud_id'.$pfj]."";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error INSERT INTO pagares";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->fileError = __FILE__;
                                        $this->lineError = __LINE__;
                                        $dbconn->RollbackTrans();
                                        return false;
                    }										
                    $this->frmError["MensajeError"]="CAMBIO EXITOSO.";
                    $this->frmForma();
                    $this->RegistrarSubmodulo($this->GetVersion());
               }								
               //fin cambio dar
               
	          if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada')
     	     {
          	     $vectorA= $this->Busqueda_Avanzada();
			     $this-> frmForma_Seleccion_Apoyos($vectorA);
               }

               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
               {
                    $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
               	$this-> frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj],$vectorD, $_REQUEST['ambulatorio'.$pfj]);
               }

               if($_REQUEST['accion'.$pfj]=='eliminar')
               {
                    $this->Eliminar_Apoyod_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
                    $this->frmForma();
               }

               if($_REQUEST['accion'.$pfj]=='observacion')
               {
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj],'',$_REQUEST['ambulatorio'.$pfj]);
               }

			if($_REQUEST['accion'.$pfj]=='modificar')
               {
                    $this->Modificar_Apoyod_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
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
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj]);
               }
						
               if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico')
               {
               	$this->Eliminar_Diagnostico_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj], $_REQUEST['codigo'.$pfj]);
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj]);
               }

          }
          return $this->salida;
	}


	//cor - clzc-jea - ads
	function Busqueda_Avanzada()
	{   
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$opcion      = ($_REQUEST['criterio1'.$pfj]);
		$cargo       = ($_REQUEST['cargo'.$pfj]);
		$descripcion =STRTOUPPER($_REQUEST['descripcion'.$pfj]);

    		$filtroTipoCargo = '';
		$busqueda1 = '';
		$busqueda2 = '';

   		if($opcion != '001' && !empty($opcion) && $opcion != '002')
          {
               $filtroTipoCargo=" AND a.grupo_tipo_cargo = '$opcion'";
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

          if($opcion == '002')
          {
               $dpto = '';
               $espe = '';
               if ($this->departamento != '' )
               {
                    $dpto = "AND a.departamento = '".$this->departamento."'";
               }
               
               if ($this->especialidades_SQL_IN != '' )
               {
                    $espe = "AND a.especialidad IN (".$this->especialidades_SQL_IN.")";
               }
               
               if ($dpto == '' AND $espe == '')
               {
                    return false;
               }
          }

          if(empty($_REQUEST['conteo'.$pfj]))
          {
               if($opcion == '002')
               {
                    $query= "SELECT count(*)
                              FROM apoyod_solicitud_frecuencia a, cups b,
                                   apoyod_tipos c
                              WHERE a.cargo = b.cargo
                              AND b.sw_estado = '1'
                              AND b.grupo_tipo_cargo = c.apoyod_tipo_id
                              $dpto $espe $busqueda1 $busqueda2";
        		}
               else
               {
                    $query = "SELECT count(*)
                              FROM cups a,apoyod_tipos b
                              WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                              AND a.sw_estado = '1'
                              $filtroTipoCargo $busqueda1 $busqueda2";
               }

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
      	if($opcion == '002')
          {
               $query= "SELECT DISTINCT a.cargo, b.descripcion, c.apoyod_tipo_id,
                         c.descripcion as tipo
                         FROM apoyod_solicitud_frecuencia a, cups b,
                         apoyod_tipos c
                         WHERE a.cargo = b.cargo
                         AND b.sw_estado = '1'
                         AND b.grupo_tipo_cargo = c.apoyod_tipo_id
                         $dpto $espe $busqueda1 $busqueda2
                         order by c.descripcion, a.cargo
                         LIMIT ".$this->limit." OFFSET $Of;";
	     }
          else
          {
                    $query = "SELECT a.cargo, a.descripcion, b.apoyod_tipo_id,
                         b.descripcion as tipo
                         FROM cups a, apoyod_tipos b
                         WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                         AND a.sw_estado = '1'
                         $filtroTipoCargo	$busqueda1 $busqueda2 order by b.apoyod_tipo_id, a.cargo
                         LIMIT ".$this->limit." OFFSET $Of;";
          }
		
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

		
		//MauroB
		//Se concatenan examenes que pueden hacer parte del examen buscado
		//ejemplo los que hacen parte de un perfil lipidico
		$var=$this->ConsultarSugeridos($var);
		//fin MauroB
		
   		if($this->conteo==='0')
		{	$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
               return false;
          }
		$resulta->Close();
		return $var;
	}

     //Responsable Tizziano Perea
     function ReconocerProfesional($usuario_solicitud)
	{
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
          
          if($usuario_solicitud)
          {
          	$criterio = "WHERE usuario_id = ".$usuario_solicitud."";
          }     
          
     	$sql="SELECT usuario_id, nombre
                FROM profesionales
                $criterio
                ORDER BY nombre ASC;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;		
          if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer profesional";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while($data = $result->FetchRow())
          {
          	$profesional[] = $data;
          }
          return $profesional;
	}
	
     
     //MauroB
	/**
	* Consulta sql de los cargos sugeridos
	*/
	function ConsultarSugeridosCargo($cargo){
		
		$query="SELECT	d.cargo, 
                         '<b>xx CARGO SUGERIDO xx </b>' || d.descripcion as descripcion,
                         d.grupo_tipo_cargo as apoyod_tipo_id, 
                         c.descripcion as tipo
			   FROM   interface_vitros_cargo as b,
                         apoyod_tipos as c,
                         cups as d,
                         (	SELECT examen_agrupado_id
                              FROM   interface_vitros_examen_agrupado
                              WHERE  codigo_cups = '$cargo' ) AS e
			   WHERE  b.examen_agrupado_id = e.examen_agrupado_id AND
                         b.codigo_cups = d.cargo AND
                         c.apoyod_tipo_id = d.grupo_tipo_cargo AND
                         b.codigo_cups <> '$cargo'";
          list($dbconn) = GetDBconn();
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al consultar sugeridos al cargo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }
          $vars='';
          while (!$result->EOF){
               $vars[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
          return $vars;
	}
	
     /**
	* Consulta lo si el o los apoyos diagnosticos solicitados pertenesen a un grupo
	* de examenes. Si si pertenese los muestra como sugeridos.
	* @param	array $var
	* @return array $var
	*/
	function ConsultarSugeridos($var){
		for($i=0;$i<sizeof($var);$i++){
			$sug_cargo=$this->ConsultarSugeridosCargo($var[$i]['cargo']);
			if($sug_cargo){
				$temp_var=array_merge($temp_var,$sug_cargo);
			}
		}
		$var=array_merge($var,$temp_var);
		return $var;
	}
	//fin MAuroB
	
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

/*
//cor - clzc - ads
function Insertar_Solicitud($cargo,$apoyod_tipo_id)
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();

		$dbconn->BeginTrans();

    //realiza el id manual de la tabla
		$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
		$result=$dbconn->Execute($query1);
		$hc_os_solicitud_id=$result->fields[0];
    //fin de la operacion

		$query2="INSERT INTO hc_os_solicitudes
						(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id)
		  			 VALUES  (
					             $hc_os_solicitud_id, ".$this->evolucion.", '$cargo',
                       '".ModuloGetVar('','','TipoSolicitudApoyod')."', ".$this->plan_id.");";
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
        $query3="INSERT INTO hc_os_solicitudes_apoyod
				(hc_os_solicitud_id, apoyod_tipo_id)
		  			 VALUES  ($hc_os_solicitud_id, '$apoyod_tipo_id');";

         $resulta1=$dbconn->Execute($query3);
				 if ($dbconn->ErrorNo() != 0)
		      {
			     $this->error = "Error al insertar en hc_os_solicitudes_apoyod";
			     $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					 $dbconn->RollbackTrans();
			     return false;
		      }
         else
		      {
				   $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
				   $dbconn->CommitTrans();
					 $this->frmForma();
		       return true;
			    }
	    }
}*/
/*
//cor - clzc - ads
function Insertar_Diagnosticos($hc_os_solicitud_id, $diagnostico_id)
{

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
     $query="INSERT INTO hc_os_solicitudes_diagnosticos
						(hc_os_solicitud_id, diagnostico_id)
		  			 VALUES  ($hc_os_solicitud_id, '$diagnostico_id')";
    $resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		 {
			$this->error = "Error al insertar en hc_os_solicitudes";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		 }
     else
		 {
				   $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
					 $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['cargo'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj]);
		       return true;
		  }
}
*/
//cor - clzc - ads
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

               $arreglo=explode(",",$codigo);

               	$query2="INSERT INTO hc_os_solicitudes
                                   (hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, paciente_id, tipo_id_paciente)
                              VALUES
			                    ($hc_os_solicitud_id,".$this->evolucion.",
                                   '".$arreglo[0]."', '".ModuloGetVar('','','TipoSolicitudApoyod')."',
                                   ".$this->plan_id.",
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
        			 $query3="INSERT INTO hc_os_solicitudes_apoyod
								(hc_os_solicitud_id, apoyod_tipo_id)
                              	VALUES ($hc_os_solicitud_id, '".$arreglo[1]."');";

         			$resulta1=$dbconn->Execute($query3);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_os_solicitudes_apoyod";
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

    //cor - clzc -ads
    function Consulta_Solicitud_Apoyod()
    {
        $pfj=$this->frmPrefijo;
        list($dbconnect) = GetDBconn();

        $criterio='';
        if(!empty($this->plan_id)){
            $criterio=",informacion_cargo('".$this->plan_id."',a.cargo,'".$this->departamento."')";
        }
     
        $query = "SELECT 
                        a.cargo, a.hc_os_solicitud_id, a.sw_ambulatorio,
                        b.descripcion, 
                        d.evolucion_id, d.ingreso,
                        c.descripcion as tipo, 
                        d.fecha, 
                        e.observacion, e.usuario_solicitud 
                        $criterio
                        
                    FROM
                        hc_os_solicitudes a 
                        left join hc_os_solicitudes_apoyod e on (a.hc_os_solicitud_id = e.hc_os_solicitud_id), 
                        cups b, 
                        apoyod_tipos c, 
                        hc_evoluciones d 
                        
                    WHERE 
                        a.paciente_id = '".$this->paciente."'
                        AND a.tipo_id_paciente = '".$this->tipoidpaciente."'
                        AND a.evolucion_id = d.evolucion_id 
                        AND d.ingreso = ".$this->ingreso."
                        AND a.cargo = b.cargo 
                        AND e.apoyod_tipo_id = c.apoyod_tipo_id 
                        ORDER BY d.evolucion_id DESC, a.hc_os_solicitud_id ASC;";
                        
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


     //cor - clzc - ads
     function Eliminar_Apoyod_Solicitado($hc_os_solicitud_id)
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
               $query1="DELETE FROM hc_os_solicitudes_apoyod
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

	//cor - clzc - ads
	function Modificar_Apoyod_Solicitado($hc_os_solicitud_id)
	{
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();

          //el sw_ambulatorio
          if(empty($_REQUEST['ambulatorio'.$pfj]))
          {		$_REQUEST['ambulatorio'.$pfj]=0;	}
			
          $query= "UPDATE hc_os_solicitudes SET sw_ambulatorio='".$_REQUEST['ambulatorio'.$pfj]."'
                    WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
     	$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al actualizar la observacion en hc_os_solicitudes_apoyod";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
						
          $obs = $_REQUEST['obs'.$pfj];			
          $query= "UPDATE hc_os_solicitudes_apoyod SET observacion = '$obs'
                    WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
     	$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al actualizar la observacion en hc_os_solicitudes_apoyod";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          if(!empty($_REQUEST['ordeno'.$pfj]) AND $_REQUEST['ordeno'.$pfj] != '-1')
          {
          	$usuario_solicitud = $_REQUEST['ordeno'.$pfj];
               $query= "UPDATE hc_os_solicitudes_apoyod SET usuario_solicitud = ".$usuario_solicitud."
                        WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al actualizar el usuario de la solicitud en hc_os_solicitudes_apoyod";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
          }
          $this->RegistrarSubmodulo($this->GetVersion());
          return true;
	}


     //cor - clzc- ads
     function tipos()
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		 $query= "SELECT apoyod_tipo_id, descripcion
                    FROM apoyod_tipos";

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



     //cor - clzc- ads
     function Diagnosticos_Solicitados($hc_os_solicitud_id)
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT a.diagnostico_id, a.diagnostico_nombre, b.tipo_diagnostico,
          			 b.sw_principal
                   FROM diagnosticos a, hc_os_solicitudes_diagnosticos b
                   WHERE b.hc_os_solicitud_id = $hc_os_solicitud_id AND a.diagnostico_id = b.diagnostico_id";

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


/*provisional....... el lunes se soluciona
     
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
                AND diagnostico_id='".$_REQUEST['cod_diag'.$pfj]."';";
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
*/

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
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}
}
?>
