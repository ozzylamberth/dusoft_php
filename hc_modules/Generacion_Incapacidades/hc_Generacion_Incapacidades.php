<?
/**
* Submodulo para la Generacion de Incapacidades Medicas.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Generacion_Incapacidades.php,v 1.11 2008/07/18 21:22:45 cahenao Exp $
*/

//ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));

class Generacion_Incapacidades extends hc_classModules
{

	var $limit;
	var $conteo;
	
	function Generacion_Incapacidades()
	{
          $this->limit=GetLimitBrowser();	
     	return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/
/*
	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/27/2005',
		'autor'=>'CLAUDIA LILIANA ZUÑIGA CAÑON',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}
*/
////////////////////////
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
			FROM hc_incapacidades
			WHERE evolucion_id=".$this->evolucion.";";
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
               //adicionado por Lorena
               unset($_SESSION['DIAGNOSTICOS'.$pfj]);
               $diagPrincipal=$this->VerificarDiagnosticoPrincipal();
               if($diagPrincipal){
                $_SESSION['DIAGNOSTICOS'.$pfj][$diagPrincipal['diagnostico_id']][$diagPrincipal['tipo_diagnostico']]=$diagPrincipal['diagnostico_nombre'];
               }
               //fin
               $this->frmForma();
               
          }
          else
          {
               //----------------nuevo dar diagnosticos						
               if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico')
               {	
                              unset($_SESSION['DIAGNOSTICOS'.$pfj][$_REQUEST['codigo'.$pfj]]);
                              $this->frmForma();	
               }				
               
               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
               {
                              $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                              $this->frmForma($vectorD);						
               }
               
               //----------------fin nuevo dar diagnosticos										
               if($_REQUEST['accion'.$pfj]=='insertar_incapacidad')
               {
                    //----------------nuevo dar diagnosticos		
                    if(!empty($_REQUEST['buscar'.$pfj]))
                    {
                         $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                         $this->frmForma($vectorD);					
                    }				
                    elseif(!empty($_REQUEST['guardardiag'.$pfj]))
                    {
                         $v=explode("||",$_REQUEST['opD'.$pfj]);
                         $tipo=$_REQUEST['dx'.$v[0].$pfj];
                         $_SESSION['DIAGNOSTICOS'.$pfj][$v[0]][$tipo]=$v[1];
                         $this->frmForma();
                    }														
                    //----------------fin nuevo dar diagnosticos										
                    else
                    {					
                         if($this->Insertar_Incapacidad()==true)
                         {
                              $_REQUEST='';
                              $this->frmForma();
                         }
                         else
                         {
                              $this->frmForma();
                         }
                    }
               }

               if($_REQUEST['accion'.$pfj]=='eliminar_incapacidad')
               {
		          $this->Eliminar_Apoyod_Solicitado();
                    $this->frmForma();
               }
					
               if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico_real')
               {
          		$this->Eliminar_Diagnostico_Incapcidad();
                    $this->frmForma_Modificar_Observacion();
               }							

          	if($_REQUEST['accion'.$pfj]=='consultar_incapacidad')
               {
                    $this->frmForma_Modificar_Observacion();
               }

               if($_REQUEST['accion'.$pfj]=='modificar_incapacidad')
               {
                    if(!empty($_REQUEST['buscar'.$pfj]))
                    {
                              $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                              $this->frmForma_Modificar_Observacion($vectorD);					
                    }	
                    elseif(!empty($_REQUEST['guardardiag'.$pfj]))
                    {	
                              $this->Insertar_Diagnostico_Incapcidad();
                              $this->frmForma_Modificar_Observacion();
                    }													
                    elseif($this->Modificar_Apoyod_Solicitado()==true)
                    {
		              $this->frmForma();
                    }
                    else
                    {
                         $this->frmForma_Modificar_Observacion();
                    }
               }
          }
          return $this->salida;
	}


     //cor - clzc - ads
     function Insertar_Incapacidad()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();	
          if ($_REQUEST['tipo_incapacidad'.$pfj] == -1 OR $_REQUEST['dias_incapacidad'.$pfj] == '' OR is_numeric($_REQUEST['dias_incapacidad'.$pfj])==0)
     	{
     		if ($_REQUEST['tipo_incapacidad'.$pfj] == -1)
               {
     			$this->frmError["tipo_incapacidad"]=1;
               }

               if ($_REQUEST['dias_incapacidad'.$pfj] == '')
               {
                    $this->frmError["dias_incapacidad"]=1;
     		}

               if(is_numeric($_REQUEST['dias_incapacidad'.$pfj])==0)
               {
          		$this->frmError["dias_incapacidad"]=1;
                    $this->frmError["MensajeError"]="DIGITE NUMERO DE DIAS DE INCAPACIDAD.";
               }
               else
               {
                    if($_REQUEST['dias_incapacidad'.$pfj]===0)
                    {
            			$this->frmError["MensajeError"]="CERO NO ES UNA CANTIDAD VALIDA PARA DIAS DE INCAPACIDAD.";
                    }
                    else
                    {
                         $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                    }
          	}
          	return false;
	    }
		//cambio dar
		if($_REQUEST['clase'.$pfj] == -1)
		{
               $this->frmError["clase"]=1;
               $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
               return false;
		}
		//fin cambio dar
		
		if(empty($_REQUEST['fechainicio'.$pfj]))
		{
               $this->frmError["fechainicio"]=1;
               $this->frmError["MensajeError"]="LA FECHA DE INICIO ES OBLIGATORIA.";
               return false;		
		}
          
		$fecha = $this->ValidarFecha($_REQUEST['fechainicio'.$pfj]);

          if(empty($fecha))
		{
               $this->frmError["fechainicio".$pfj]=1;
               $this->frmError["MensajeError"]="FORMATO DE FECHA INCORRECTO.";
               return false;
		}

          if(($_REQUEST['prorroga'.$pfj] == '0') AND (strtotime($fecha) < strtotime(date("y-m-d"))))
          {
               $this->frmError["fechainicio".$pfj]=1;
               $this->frmError["MensajeError"]="PARA ESTA FECHA, LA INCAPACIDAD DEBERIA DE TENER PRORROGA.";
               return false;
          }
     	
          $query="select nextval('hc_incapacidades_hc_incapacidad_id_seq')";
		$serial=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_incapacidades";
			$this->frmError["MensajeError"]="NO SE LOGRO INSERTAR LA INCAPACIDAD";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();			
			return false;
		}	
		
		if(!empty($_SESSION['DIAGNOSTICOS'.$pfj]))
		{
               foreach($_SESSION['DIAGNOSTICOS'.$pfj] as $k => $vector){
                $diag="'".$k."'"; 
                foreach($vector as $tipo => $v){
                  $tipo="'".$tipo."'"; 
                }
               }
               
		}
		else
		{ $diag='NULL'; }
					
		$query="INSERT INTO hc_incapacidades
						(evolucion_id, 
                               tipo_incapacidad_id, 
                               observacion_incapacidad, 
                               dias_de_incapacidad,
                               sw_prorroga,
                               tipo_atencion_incapacidad_id,
                               tipo_id_paciente, 
                               paciente_id, 
                               fecha_inicio, 
                               hc_incapacidad_id, 
                               diagnostico_id,
                               tipo_diagnostico)
					VALUES
                         	(".$this->evolucion.", 
                               '".$_REQUEST['tipo_incapacidad'.$pfj]."',
                               '".$_REQUEST['observacion_incapacidad'.$pfj]."', 
                               '".$_REQUEST['dias_incapacidad'.$pfj]."',
                               '".$_REQUEST['prorroga'.$pfj]."',
                               ".$_REQUEST['clase'.$pfj].",
                               '".$this->tipoidpaciente."',
                               '".$this->paciente."',
                               '$fecha',
                               ".$serial->fields[0].",
                               $diag,
                               $tipo)";

          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar en hc_incapacidades";
               $this->frmError["MensajeError"]="NO SE LOGRO INSERTAR LA INCAPACIDAD";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();			
               return false;
          }
          
          $dbconn->CommitTrans();
          unset($_SESSION['DIAGNOSTICOS'.$pfj]);		
          $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
          $this->RegistrarSubmodulo($this->GetVersion());
          return true;
     }

     function Insertar_Diagnostico_Incapcidad()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();			
          if(!empty($_REQUEST['opD'.$pfj]))
          {		
                    $codigo=$_REQUEST['opD'.$pfj];
                    $query="UPDATE hc_incapacidades SET diagnostico_id='".$_REQUEST['opD'.$pfj]."', tipo_diagnostico='".$_REQUEST['dx'.$codigo.$pfj]."'
                    WHERE evolucion_id=".$this->evolucion."";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_incapacidades";
                         $this->frmError["MensajeError"]="NO SE LOGRO INSERTAR LA INCAPACIDAD";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }		
                    $this->frmError["MensajeError"]="DIAGNOSTICO CREADO SATISFACTORIAMENTE.";
                    $this->RegistrarSubmodulo($this->GetVersion());
          }	
          else
          {
                    $this->frmError["MensajeError"]="DEBE ELEGIR ALGUN DIAGNOSTICO";
          }		
          return true; 
     }

	
     //cambio dar
     function ValidarFecha($fecha)
     {
          $x=explode("/",$fecha);
          if(strlen ($x[2])!=4 OR is_numeric($x[2])==0)
          {
               $this->frmError["MensajeError"]="Formato de Fecha Incorrecto ";
               return false;
          }
          if(strlen ($x[1])>2 OR is_numeric($x[1])==0 OR $x[1]==0)
          {
               $this->frmError["MensajeError"]="Formato de Fecha Incorrecto ";
               return false;
          }
          if(strlen ($x[0])>2 OR is_numeric($x[0])==0 OR $x[0]==0)
          {
               $this->frmError["MensajeError"]="Formato de Fecha Incorrecto ";
               return false;
          }
          return $x[2].'-'.$x[1].'-'.$x[0];
     }
     
          
	function BuscarClaseAtencion()
	{
		list($dbconnect) = GetDBconn();
		$query= "SELECT * FROM hc_tipos_atencion_incapacidad";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla hc_tipos_incapacidad";
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

//finca cambio dar

     //cor - clzc - ads
     function Modificar_Apoyod_Solicitado()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $E=0;
          if ($_REQUEST['tipo_incapacidad'.$pfj] == -1 OR $_REQUEST['dias_incapacidad'.$pfj] == '' OR
		    is_numeric($_REQUEST['dias_incapacidad'.$pfj])==0)
     	{
          	if ($_REQUEST['tipo_incapacidad'.$pfj] == -1)
               {
		          $this->frmError["tipo_incapacidad"]=1;
               }

               if ($_REQUEST['dias_incapacidad'.$pfj] == '')
               {
                    $this->frmError["dias_incapacidad"]=1;
        		}

               if(is_numeric($_REQUEST['dias_incapacidad'.$pfj])==0)
               {
		          $this->frmError["dias_incapacidad"]=1;
                    $this->frmError["MensajeError"]="DIGITE NUMERO DE DIAS DE INCAPACIDAD.";
               }
               else
               {
                    if($_REQUEST['dias_incapacidad'.$pfj]===0)
                    {
                         $this->frmError["MensajeError"]="CERO NO ES UNA CANTIDAD VALIDA PARA DIAS DE INCAPACIDAD.";
                    }
                    else
                    {
                         $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                    }
               }
               return false;
	    }
          //cambio dar
          if($_REQUEST['clase'.$pfj] == -1)
          {
               $this->frmError["clase"]=1;
               $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
               return false;
          }
          //fin cambio dar

          $query= "UPDATE hc_incapacidades SET tipo_incapacidad_id = '".$_REQUEST['tipo_incapacidad'.$pfj]."',
                         observacion_incapacidad = '".$_REQUEST['observacion_incapacidad'.$pfj]."',
                         dias_de_incapacidad = '".$_REQUEST['dias_incapacidad'.$pfj]."',
                         tipo_atencion_incapacidad_id = ".$_REQUEST['clase'.$pfj].",
                         sw_prorroga = '".$_REQUEST['prorroga'.$pfj]."'
                  WHERE evolucion_id = ".$this->evolucion."";

          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al actualizar la observacion en hc_os_solicitudes_apoyod";
               $this->frmError["MensajeError"]="ERROR AL MODIFICAR LA INCAPACIDAD";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
	          $this->frmError["MensajeError"]="INCAPACIDAD MODIFICADA SATISFACTORIAMENTE.";
          }
          $this->RegistrarSubmodulo($this->GetVersion());
          return true;
	}


     //cor - clzc -ads
     function Consulta_Incapacidades_Generadas()
     {
          $pfj=$this->frmPrefijo;
	     list($dbconnect) = GetDBconn();

          $query= " SELECT a.*, c.descripcion as tipo_incapacidad_descripcion, b.fecha , e.diagnostico_id, e.diagnostico_nombre,
	  		d.nombre, f.descripcion as especialidad
                    FROM hc_incapacidades as a left join diagnosticos as e on( a.diagnostico_id = e.diagnostico_id), 
                         hc_evoluciones as b, hc_tipos_incapacidad as c,
			 profesionales d, profesionales_especialidades es, especialidades f
                    WHERE b.ingreso = ".$this->ingreso." 
                    AND a.evolucion_id = b.evolucion_id 
                    AND a.tipo_incapacidad_id = c.tipo_incapacidad_id
		    AND b.usuario_id = d.usuario_id 
		    AND d.tipo_id_tercero = es.tipo_id_tercero
		    AND d.tercero_id = es.tercero_id
		    AND es.especialidad = f.especialidad";
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de solictud de apoyos";
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

	function DiagnosticosIncapcidad()
	{
     	list($dbconnect) = GetDBconn();
		$query= " SELECT e.diagnostico_id, e.diagnostico_nombre,a.tipo_diagnostico
                    FROM hc_incapacidades as a , diagnosticos as e 
                    WHERE a.evolucion_id = ".$this->evolucion." and a.diagnostico_id=e.diagnostico_id";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de solictud de apoyos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
		}
 
		if(!$result->EOF)
		{	
               $vector=$result->GetRowAssoc($ToUpper = false);			
          }
          $result->Close();
          return $vector;	
     }

     //cor - clzc - ads
     function Eliminar_Apoyod_Solicitado()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();			
                    
          $query="DELETE FROM hc_incapacidades
                  WHERE evolucion_id = ".$this->evolucion." and hc_incapacidad_id=".$_REQUEST['id_incapacidad'.$pfj]."";
          $resulta=$dbconn->Execute($query);					
          if ($dbconn->ErrorNo() != 0)
          {
               $dbconn->RollbackTrans();
               $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR LA INCAPACIDAD";
               return false;
          }

          $this->frmError["MensajeError"]="SOLICITUD ELIMINADA SATISFACTORIAMENTE.";
          $dbconn->CommitTrans();
          return true;
	}

     function Eliminar_Diagnostico_Incapcidad()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query="UPDATE hc_incapacidades SET diagnostico_id=NULL WHERE evolucion_id = ".$this->evolucion."";
          $resulta=$dbconn->Execute($query);					
          if ($dbconn->ErrorNo() != 0)
          {
               $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL DIAGNOSTICO";
               return false;
          }	

          $this->frmError["MensajeError"]="DIAGNOSTICO ELIMINADO SATISFACTORIAMENTE.";
          $this->RegistrarSubmodulo($this->GetVersion());
          return true; 
     }

     function tipos_incapacidad()
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();
          $query= "SELECT * FROM hc_tipos_incapacidad";
     
          $result = $dbconnect->Execute($query);
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la tabla hc_tipos_incapacidad";
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

	//----------------nuevo dar diagnosticos	
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
          $query ="SELECT diagnostico_id, diagnostico_nombre
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

          while(!$resulta->EOF)
          {
               $var[]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }

          if($this->conteo==='0')
          {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                              return false;
          }
          $resulta->Close();
          return $var;
	}
	//----------------fin nuevo dar diagnosticos	

}
?>
