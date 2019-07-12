<?php
/**
* Submodulo para la Solicitud de Interconsultas.
*
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Interconsulta.php,v 1.22 2007/02/13 20:22:16 tizziano Exp $
*/

class Interconsulta extends hc_classModules
{
	var $limit;
	var $conteo;
	var $PermitirNoProfesionales='1';
	var $capitulo='';
	var $grupo='';
	var $categoria='';

//clzc - si
	function Interconsulta()
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
// 				$PermitirNoProfesionales='1';  //usuario medico o no profesional permitido
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
			   FROM hc_os_solicitudes AS A JOIN
			        hc_os_solicitudes_interconsultas AS B
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
													$this->error = "Error UPDATE hc_os_solicitudes1";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$this->fileError = __FILE__;
													$this->lineError = __LINE__;
													$dbconn->RollbackTrans();
													return false;
									}										
									$this->frmError["MensajeError"]="CAMBIO EXITOSO.";
									$this->frmForma();
							}			
							if($_REQUEST['accion'.$pfj]=='ambulatorio')
							{		//pasarlo a ambulatorio
								list($dbconn) = GetDBconn();
									$query = "UPDATE hc_os_solicitudes SET sw_ambulatorio='1' 
														WHERE hc_os_solicitud_id=".$_REQUEST['hc_os_solicitud_id'.$pfj]."";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error UPDATE hc_os_solicitudes2";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$this->fileError = __FILE__;
													$this->lineError = __LINE__;
													$dbconn->RollbackTrans();
													return false;
									}										
									$this->frmError["MensajeError"]="CAMBIO EXITOSO.";
															
									$this->frmForma();
							}
							//fin cambio dar
											
               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Especialidad')
               {
                    $vectorA= $this->Busqueda_Avanzada_Especialidad();
                    $this-> frmForma_Seleccion_Especialidades($vectorA);
               }

               if($_REQUEST['accion'.$pfj]=='insertar_varias_especialidades')
               {
                    $this->Insertar_Varias_Especialidades();
                    $this->frmForma();
               }

               if($_REQUEST['accion'.$pfj]=='eliminar')
               {
                    $this->Eliminar_Interconsulta_Solicitada($_REQUEST['hc_os_solicitud_id'.$pfj]);
                    $this->frmForma();
               }

               if($_REQUEST['accion'.$pfj]=='observacion')
               {
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['codigo_esp'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj], $_REQUEST['sw_cantidad'.$pfj], $_REQUEST['cantidad'.$pfj],'',$_REQUEST['obs'.$pfj],$_REQUEST['ambulatorio'.$pfj]);
               }
               
               if($_REQUEST['accion'.$pfj]=='cambiar_diagnostico')
               {
                    $this->CambiarDiagnosticos();
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['codigo_esp'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj], $_REQUEST['sw_cantidad'.$pfj], $_REQUEST['cantidad'.$pfj],'',$_REQUEST['obs'.$pfj],$_REQUEST['ambulatorio'.$pfj]);
               }

               if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico')
               {
                    $this->Eliminar_Diagnostico_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj], $_REQUEST['codigo'.$pfj]);
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['codigo_esp'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj], $_REQUEST['sw_cantidad'.$pfj], $_REQUEST['cantidad'.$pfj],'',$_REQUEST['obs'.$pfj],$_REQUEST['ambulatorio'.$pfj]);
               }
               //cambio dar
               if($_REQUEST['accion'.$pfj]=='modificar')
               {
                    if(!empty($_REQUEST['volver'.$pfj]))
                    {		
                         if($_REQUEST['observacion'.$pfj] == $_REQUEST['obs'.$pfj])
                         {
                              $this->frmForma();
                         }
                         else
                         {
                              $this->frmError["MensajeError"]="RECUERDE GUARDAR LA OBSERVACION";
                              $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['codigo_esp'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj], $_REQUEST['sw_cantidad'.$pfj], $_REQUEST['cantidad'.$pfj],'',$_REQUEST['obs'.$pfj],$_REQUEST['ambulatorio'.$pfj]);
                         }
                    }	
                    elseif(!empty($_REQUEST['guardardiag'.$pfj]))
                    {		
                         $this->Insertar_Varios_Diagnosticos();
                         $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['codigo_esp'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj], $_REQUEST['sw_cantidad'.$pfj], $_REQUEST['cantidad'.$pfj],'',$_REQUEST['obs'.$pfj],$_REQUEST['ambulatorio'.$pfj]);
                    }														
                    elseif((!empty($_REQUEST['buscar'.$pfj]) OR !empty($_REQUEST['guardar'.$pfj]))  AND (!empty($_REQUEST['codigo'.$pfj]) OR !empty($_REQUEST['diagnostico'.$pfj])))
                    {	
                         $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                         $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['codigo_esp'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj], $_REQUEST['sw_cantidad'.$pfj], $_REQUEST['cantidad'.$pfj], $vectorD,$_REQUEST['obs'.$pfj],$_REQUEST['ambulatorio'.$pfj]);
                    }	
                    elseif(!empty($_REQUEST['buscar'.$pfj]))
                    {	
                         $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                         $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['codigo_esp'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj], $_REQUEST['sw_cantidad'.$pfj], $_REQUEST['cantidad'.$pfj], $vectorD,$_REQUEST['obs'.$pfj],$_REQUEST['ambulatorio'.$pfj]);
                    }														
                    elseif(!empty($_REQUEST['guardar'.$pfj]))
                    {
                         $this->Modificar_Interconsulta_Solicitada($_REQUEST['hc_os_solicitud_id'.$pfj]);
                         $this->frmForma();								
                    }									
               }	
               //fin cambio dar					
						
               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
               {
                    $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                    $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_id'.$pfj],$_REQUEST['codigo_esp'.$pfj],$_REQUEST['descripcion'.$pfj], $_REQUEST['observacion'.$pfj], $_REQUEST['sw_cantidad'.$pfj], $_REQUEST['cantidad'.$pfj], $vectorD,$_REQUEST['obs'.$pfj],$_REQUEST['ambulatorio'.$pfj]);
               }
          }
          return $this->salida;
	}


//clzc - si
	function Busqueda_Avanzada_Especialidad()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$opcion      = ($_REQUEST['criterio1'.$pfj]);
		$codigo_esp       = ($_REQUEST['codigo_esp'.$pfj]);
		$especialidad =STRTOUPPER($_REQUEST['especialidad'.$pfj]);

		$conector   = '';
		$busqueda1  = '';
		$busqueda2  = '';

          if ($codigo_esp != '')
          {
               $conector = "WHERE ";
               $busqueda1 =" a.especialidad LIKE '$codigo_esp%'";
          }

          if ($especialidad != '')
          {
               if ($conector  == '')
               {
                    $conector = "WHERE ";
                    if (eregi('%',$especialidad))
                    {
                         $busqueda2 ="a.descripcion LIKE '$especialidad'";
                    }
                    else
                    {
                         $busqueda2 ="a.descripcion LIKE '%$especialidad%'";
                    }
               }
               else
               {
                    if (eregi('%',$especialidad))
                    {
	                    $busqueda2 ="AND a.descripcion LIKE '$especialidad'";
                    }
                    else
                    {
     				$busqueda2 ="AND a.descripcion LIKE '%$especialidad%'";
                    }
               }
          }

		if(empty($_REQUEST['conteo'.$pfj]))
		{
               $query = "SELECT count(*)
            			FROM especialidades as a join especialidades_cargos as b
						on (a.especialidad = b.especialidad)
						left join tipos_consulta as c on (a.especialidad = c.especialidad)
			          	left join cups as d on (b.cargo = d.cargo AND d.sw_estado = '1')
		               $conector $busqueda1 $busqueda2";
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
          
          $query = "SELECT a.especialidad, a.descripcion, b.cargo, c.tipo_consulta_id,
                    	  d.sw_cantidad	
                    FROM especialidades as a join especialidades_cargos as b
                    on (a.especialidad = b.especialidad)
                    left join tipos_consulta as c on (a.especialidad = c.especialidad)
                    left join cups as d on (b.cargo = d.cargo AND d.sw_estado = '1')
                    $conector $busqueda1 $busqueda2 order by a.especialidad
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


//clzc - si
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
			$busqueda1 =" WHERE a.diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != ''))
		{
               if (eregi('%',$diagnostico))
               {
			    					$busqueda2 ="AND a.diagnostico_nombre LIKE '$diagnostico'";
               }
               else
               {
                    $busqueda2 ="AND a.diagnostico_nombre LIKE '%$diagnostico%'";
               }
		}

		if (($diagnostico != '') AND ($codigo == ''))
		{
               if (eregi('%',$diagnostico))
               {
                    $busqueda2 ="WHERE a.diagnostico_nombre LIKE '$diagnostico'";
               }
               else
               {
                    $busqueda2 ="WHERE a.diagnostico_nombre LIKE '%$diagnostico%'";
               }
		}
		
		//--cambio dar:verifica si hay diagnosticos especificos para la historia
		$sql = "SELECT count(*) FROM diagnosticos_historias_templates 
						WHERE hc_modulo='".$this->hc_modulo."'";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$template = $result->fields[0];
		$result->Close();		
		$template=0;
		if(empty($_REQUEST['conteo'.$pfj]))
		{
				if($template > 0)
				{			//hay diagnosticos especificos para el tipo de historia
							$query =" SELECT count(b.*) 
												FROM diagnosticos as a join diagnosticos_historias_templates as b 
												ON(b.hc_modulo='".$this->hc_modulo."' AND a.diagnostico_id=b.diagnostico_id)
												$busqueda1 $busqueda2";					
				}
				else
				{			//no hay diagnsoticos especificos					
							$query ="SELECT count(a.*) FROM diagnosticos as a $busqueda1 $busqueda2";	
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


		//filtro por clasificacion de diagnosticos
		$filtro='';
		if(empty($busqueda1) AND empty($busqueda2))
		{
			$filtro = "WHERE (a.sexo_id='".$this->datosPaciente['sexo_id']."' OR a.sexo_id is null)
					 AND   (a.edad_max>=".$edad_paciente[edad_en_dias]." OR a.edad_max is null)
					 AND   (a.edad_min<=".$edad_paciente[edad_en_dias]." OR a.edad_min is null)";
		}
		else
		{
			$filtro = "AND (a.sexo_id='".$this->datosPaciente['sexo_id']."' OR a.sexo_id is null)
					 AND (a.edad_max>=".$edad_paciente[edad_en_dias]." OR a.edad_max is null)
					 AND (a.edad_min<=".$edad_paciente[edad_en_dias]." OR a.edad_min is null)";
		}

		$filtro1='';
		if(!empty($this->capitulo))
		{
			$filtro1 = " AND (a.capitulo='".$this->capitulo."' OR a.capitulo is null)";
		}
		if(!empty($this->grupo))
		{
			$filtro1 .= " AND (a.grupo='".$this->grupo."' OR a.grupo is null)";
		}
		if(!empty($this->categoria))
		{
			$filtro1 .= " AND (a.categoria='".$this->categoria."' OR a.categoria is null)";
		}

		if($template > 0)
		{			//hay diagnosticos especificos para el tipo de historia
					$query =" SELECT a.diagnostico_id, a.diagnostico_nombre
										FROM diagnosticos as a join
										diagnosticos_historias_templates as b ON(b.hc_modulo='".$this->hc_modulo."' AND a.diagnostico_id=b.diagnostico_id)
										$busqueda1 $busqueda2	$filtro $filtro1
										order by a.diagnostico_id
										LIMIT ".$this->limit." OFFSET $Of;";			
		}
		else
		{			//no hay diagnsoticos especificos			
					$query = "SELECT a.diagnostico_id,a.diagnostico_nombre
										FROM diagnosticos as a
										$busqueda1 $busqueda2 $filtro $filtro1
										order by a.diagnostico_id
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
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		return $var;
	}	

     
//clzc - si
	function Insertar_Varias_Especialidades()
	{
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          foreach($_REQUEST['opE'.$pfj] as $index=>$codigo)
          {
               $arreglo=explode(",",$codigo);
               if(!empty($_REQUEST['cantidad'.$pfj.$arreglo[1]]))
               {
                    $cantidad =$_REQUEST['cantidad'.$pfj.$arreglo[1]];
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
               //realiza el id manual de la tabla
		     $query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
               $result=$dbconn->Execute($query1);
		     $hc_os_solicitud_id=$result->fields[0];
         //fin de la operacion               
               $query2="INSERT INTO hc_os_solicitudes
                         (hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, cantidad, paciente_id, tipo_id_paciente)
                         VALUES
                         ($hc_os_solicitud_id,".$this->evolucion.",
                         '".$arreglo[0]."', 'INT',
                         ".$this->plan_id.", ".$cantidad.",
                         '".$this->paciente."',
                         '".$this->tipoidpaciente."')";
               $result=$dbconn->Execute($query2);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar en hc_os_solicitudes";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    $query3="INSERT INTO  hc_os_solicitudes_interconsultas
						(hc_os_solicitud_id, especialidad)
		  			    VALUES  ($hc_os_solicitud_id, '".$arreglo[1]."');";
                    $result=$dbconn->Execute($query3);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_os_solicitudes_interconsultas";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    else
                    {
                         if($arreglo[2]!=NULL )
                         {
                              $query4="INSERT INTO hc_os_solicitudes_citas
                                   (hc_os_solicitud_id, tipo_consulta_id)
                                   VALUES  ($hc_os_solicitud_id, '".$arreglo[2]."');";

                              $result=$dbconn->Execute($query4);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->error = "Error al insertar en hc_os_solicitudes_citas";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                         }
                         //insertado por lorena
                         $diagnostico=$this->VerificarDiagnosticoPrincipal();                         
                         if(is_array($diagnostico)){
                          $query5="INSERT INTO hc_os_solicitudes_diagnosticos
                          (hc_os_solicitud_id,diagnostico_id,tipo_diagnostico,sw_principal)
                          VALUES  ($hc_os_solicitud_id,'".$diagnostico['diagnostico_id']."',
                          '".$diagnostico['tipo_diagnostico']."','1');";
                          $result=$dbconn->Execute($query5);
                          if ($dbconn->ErrorNo() != 0)
                          {
                              $this->error = "Error al insertar en hc_os_solicitudes_interconsultas";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                          }
                         }
                         //fin insercion
                    }
               }
          }
          $dbconn->CommitTrans();
          $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
           $this->RegistrarSubmodulo($this->GetVersion());            
          return true;
	}
    
      function VerificarDiagnosticoPrincipal(){
        
        $pfj=$this->frmPrefijo;
        list($dbconnect) = GetDBconn();
        $query = "SELECT b.diagnostico_id, b.diagnostico_nombre,      
        a.descripcion, a.tipo_diagnostico 
        FROM hc_diagnosticos_ingreso as a, diagnosticos as b,
        hc_evoluciones c
        WHERE a.tipo_diagnostico_id=b.diagnostico_id
        AND a.evolucion_id = c.evolucion_id
        AND c.ingreso = ".$this->ingreso."
        AND sw_principal='1'";
        $result = $dbconnect->Execute($query);
        if ($dbconnect->ErrorNo() != 0){
            $this->error = "Error al buscar en la tabla hc_tipos_incapacidad";
            $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
            return false;
        }else{   
          if(!$result->EOF){     
            $vector=$result->GetRowAssoc($ToUpper = false);          
          }
        }
        $result->Close();
        return $vector;    
        
      }

     //clzc - si //query alterado
     function Consulta_Solicitud_Interconsulta()
     {
		$pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();

          $criterio='';
		if(!empty($this->plan_id)){
		  $criterio = ",informacion_cargo('".$this->plan_id."',a.cargo,'".$this->departamento."')";
		}

          $query = "
               SELECT a.hc_os_solicitud_id, 
                    a.cantidad, 
                    a.evolucion_id, 
                    a.cargo, 
                    b.descripcion, 
                    d.fecha, 
                    e.especialidad, 
                    e.observacion, 
                    d.ingreso, 
                    g.sw_cantidad, 
                    a.sw_ambulatorio 
                    $criterio
               from hc_os_solicitudes a 
                    LEFT JOIN hc_os_solicitudes_interconsultas e 
                         ON (a.hc_os_solicitud_id = e.hc_os_solicitud_id ) 
                    LEFT JOIN cups as g 
                         ON (a.cargo = g.cargo), 
                    especialidades b, 
                    hc_evoluciones d 
               WHERE a.evolucion_id = d.evolucion_id 
                    AND d.ingreso = ".$this->ingreso."
                    AND b.especialidad = e.especialidad 
                    AND a.tipo_id_paciente =  '".$this->tipoidpaciente."'
               AND a.paciente_id = '".$this->paciente."'
               ORDER BY a.hc_os_solicitud_id
          ";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de solicitud de interconsultas";
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

     //clzc - si
     function Eliminar_Interconsulta_Solicitada($hc_os_solicitud_id)
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
               $query="DELETE FROM hc_os_solicitudes_citas
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
                    $query1="DELETE FROM hc_os_solicitudes_interconsultas
                    WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
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
                         WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
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
          }
          return true;
	}

     //clzc - si
     function Modificar_Interconsulta_Solicitada($hc_os_solicitud_id)
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
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

	          $query= "UPDATE hc_os_solicitudes_interconsultas SET observacion = '".$_REQUEST['obs'.$pfj]."'
                         WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";

          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al actualizar la observacion en hc_os_solicitudes_interconsultas";
               $this->frmError["MensajeError"]="NO SE LOGRO ACTUALIZAR LA INTERCONSULTA";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          else
          {
										//el sw_ambulatorio
										if(empty($_REQUEST['ambulatorio'.$pfj]))
										{		$_REQUEST['ambulatorio'.$pfj]=0;	}		
													
                    $query= "UPDATE hc_os_solicitudes SET cantidad = ".$cantidad.",sw_ambulatorio='".$_REQUEST['ambulatorio'.$pfj]."'
                         WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al actualizar la cantidad en hc_os_solicitudes";
                         $this->frmError["MensajeError"]="NO SE LOGRO ACTUALIZAR LA INTERCONSULTA";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
          }
          $dbconn->CommitTrans();
          $this->frmError["MensajeError"]="INTERCONSULTA MODIFICADA SATISFACTORIAMENTE.";
           $this->RegistrarSubmodulo($this->GetVersion());            
          return true;
     }

/*
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
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $vector;
}

*/
     
     //clzc - si
     function Diagnosticos_Solicitados($hc_os_solicitud_id)
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();
          $query= "SELECT a.diagnostico_id, a.diagnostico_nombre, b.tipo_diagnostico,
          			 b.sw_principal
                    FROM diagnosticos a, hc_os_solicitudes_diagnosticos b
                    WHERE b.hc_os_solicitud_id = ".$hc_os_solicitud_id." AND a.diagnostico_id = b.diagnostico_id;";

          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar los diagnosticos asignados";
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

     //clzc - si
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
                AND diagnostico_id='".$_REQUEST['cod_diag'.$pfj]."';";
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
				 $this->RegistrarSubmodulo($this->GetVersion());            
        return true;
			}
		}
		$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." FUE ELIMINADO SATISFACTORIAMENTE.";
		return true;
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
}
?>
