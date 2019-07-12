<?
/**
* Submodulo para la Solicitud de Procedimientos Quirurgicos.
*
* Submodulo para manejar la solicitud de procedimientos quirurgicos.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Solicitud_De_Procedimientos_Qx.php,v 1.17 2007/02/13 20:22:16 tizziano Exp $
*/

class Solicitud_De_Procedimientos_Qx extends hc_classModules
{
    var $limit;
    var $conteo;


     function Solicitud_De_Procedimientos_Qx()
     {
          $this->limit=GetLimitBrowser();
          return true;
     }

     //clzc-dd-ok  // no la he cudrado con las nuevas bases de datos
     function GetConsulta()
     {
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
			FROM hc_os_solicitudes AS A 
			JOIN hc_os_solicitudes_acto_qx AS B ON (A.hc_os_solicitud_id=B.hc_os_solicitud_id)
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


     //cor - clzc - spqx
     function GetForma(){
          $pfj=$this->frmPrefijo;
     
          if($_REQUEST['accion'.$pfj]=='FormaPrincipal' OR empty($_REQUEST['accion'.$pfj])){
     
		unset($_SESSION['DIAGNOSTICOS'.$pfj]);
          unset($_SESSION['APOYOS'.$pfj]);
          unset($_SESSION['PROCEDIMIENTO'.$pfj]);
          unset($_SESSION['MODIFICANDO'.$pfj]);
          unset($_SESSION['PASO']);
          unset($_SESSION['PASO1']);
          //unset ($_SESSION['CARGAR_DATOS_PROCEDIMIENTOS'.$pfj]);
          //unset($_SESSION['VECTOR1'.$pfj]);
          unset($_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj]);
          unset($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]);
          unset($_SESSION['SOLICITUD_PROCEDIMIENTO_QX_PRINCIPAL'.$pfj]['PRINCIPAL']);
          unset($_SESSION['SOLICITUD_DIAGNOSTICOS_QX'.$pfj]);
          unset($_SESSION['SOLICITUD_APOYOS_QX'.$pfj]);
          unset($_SESSION['SOLICITUD_MATERALES_QX'.$pfj]);
          unset($_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj]);
          unset($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj]);
          unset($_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj]);
	     unset($_SESSION['SOLICITUD_RESERVA_SANGRE_QX_APOYOS'.$pfj]);
          unset($_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']);
          unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']);
          //unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);
          //unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);
          //unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);
          unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);
          unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']);
          unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);
          unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);
          unset($_SESSION['SOLICITUD_QX'.$pfj]['INGRESO']);
          unset($_SESSION['SOLICITUD_QX'.$pfj]['EVOLUCION']);
          unset($_SESSION['SOLICITUD_DIAGNOSTICOS_QX_PRINCIPAL'.$pfj]);
          unset($_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']);
               
	     if(empty($_REQUEST['accion'.$pfj])){
     
          if($this->ConfirmarIgualEvolucion()==1){
                    $this->TraerVariablesdeSession();
          $this->Llenar_Procedimiento();
                         return $this->salida;
                    }elseif(!empty($_REQUEST['SolicitudId'.$pfj])){
     
                    $_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']=$_REQUEST['SolicitudId'.$pfj];				
          $this->TraerVariablesdeSession();
          $this->Llenar_Procedimiento();
                         return $this->salida;
                    }
               }
               $vector=$this->SolicitudesQXPaciente();
               if(sizeof($vector)>0 && $_REQUEST['centinela'.$pfj]!=1){
     
          $this->frmConsultaSolicitudes($vector);
                    return $this->salida;
               }
               $this->frmForma();
          }else{
     	if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada'){
          if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}
          unset($_REQUEST['cargos'.$pfj]);
                    $vectorA= $this->Busqueda_Avanzada();
               $this-> frmForma_Seleccion_Avanzada($vectorA);
               }
     	if($_REQUEST['accion'.$pfj]=='FormaAvanzada'){
               $this-> frmForma_Seleccion_Avanzada($vectorA);
               }
     
               //cuando por primera vez va a solicitar el procedimiento
               if($_REQUEST['accion'.$pfj]=='llenarprocedimiento'){
                    unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
                    unset ($_SESSION['APOYOS'.$pfj]);
                    unset ($_SESSION['PROCEDIMIENTO'.$pfj]);
                    $prequirurgicos = $this->Apoyos_Prequirurgicos($_REQUEST['cargos'.$pfj]);
                    if($prequirurgicos){
                         for($i=0;$i<sizeof($prequirurgicos);$i++){
                                   $_SESSION['APOYOS'.$pfj][$prequirurgicos[$i][cargopreqx]]=$prequirurgicos[$i][descripcion];
                         }
                    }
                    //$this->TraerVariablesdeSession();
                    $this->Llenar_Procedimiento($_REQUEST['tipo'.$pfj],$_REQUEST['cargos'.$pfj],$_REQUEST['procedimiento'.$pfj]);
               }
               //va al pantallazo de la modificacion
               if($_REQUEST['accion'.$pfj]=='modificarprocedimiento'){
                    unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
                    unset ($_SESSION['APOYOS'.$pfj]);
                    $_SESSION['MODIFICANDO'.$pfj]=1;
                    $apoyos =$this->Apoyos_Del_Procedimiento($_REQUEST['hc_os_solicitud_id'.$pfj]);
                    if($apoyos){
                         for($j=0;$j<sizeof($apoyos);$j++){
                                   $_SESSION['APOYOS'.$pfj][$apoyos[$j][cargo]]= $apoyos[$j][descripcion];
                         }
                    }
                    $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
               }
               //elimina un procedimiento ok
               if($_REQUEST['accion'.$pfj]=='eliminarprocedimiento'){
                    $this->Eliminar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
                    $this->frmForma();
               }
               if($_REQUEST['accion'.$pfj]=='ConsultaAutorizacionesSolicitud'){
          $this->formaConsultaAutorizaciones($_REQUEST['SolicitudId'.$pfj],$_REQUEST['CargoPrincipal'.$pfj],$_REQUEST['NombreCargo'.$pfj],$_REQUEST['ingresoId'.$pfj],$_REQUEST['EvolucionId'.$pfj]);
               }
     
               if($_REQUEST['accion'.$pfj]=='OpcionesProcedimiento'){
                    if(!empty($_REQUEST['BuscarDiag'.$pfj]) OR !empty($_REQUEST['opc'.$pfj])){
                         $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                         $this->Llenar_Procedimiento($_REQUEST['tipo'.$pfj], $_REQUEST['cargos'.$pfj], $_REQUEST['procedimiento'.$pfj], $vectorD);
                    }
                    if(!empty($_REQUEST['guardarprocedimiento'.$pfj])){
     
          //if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}
     
                    if(!empty($_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA'])){
               if($this->Modificar_Solicitud_Procedimiento() == true){
               $this->Llenar_Procedimiento();
                              }
                         }elseif($this->Insertar_Solicitud_Procedimiento() == true){
                              $this->Llenar_Procedimiento();
                              return true;
                         }
                    }
                    if(!empty($_REQUEST['guardarDiag'.$pfj])){
                         $this->Insertar_Varios_Diagnosticos();
                         $this->Llenar_Procedimiento($_REQUEST['tipo'.$pfj], $_REQUEST['cargos'.$pfj], $_REQUEST['procedimiento'.$pfj], $vectorD);
          }
                    if(!empty($_REQUEST['eliminardiagnostico'.$pfj])){
                         unset ($_SESSION['DIAGNOSTICOS'.$pfj][$_REQUEST['k'.$pfj]]);
                         $this->Llenar_Procedimiento($_REQUEST['tipo'.$pfj], $_REQUEST['cargos'.$pfj], $_REQUEST['procedimiento'.$pfj],$vectorD);
                    }
     }
     
     if($_REQUEST['accion'.$pfj]=='OpcionesModificacionProcedimiento'){
                    if(!empty($_REQUEST['guardarmodificacionprocedimiento'.$pfj])){
                              $this->Modificar_Procedimiento($_REQUEST['hc_os_solicitud_id'.$pfj]);
                              $this->frmForma();
                    }
                    if(!empty($_REQUEST['eliminardiagnosticobd'.$pfj])){
                              $this->EliminarDiagnosticoBD($_REQUEST['hc_os_solicitud_id'.$pfj], $_REQUEST['t'.$pfj]);
                              $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
                    }
                    if(!empty($_REQUEST['BuscarDiag'.$pfj]) OR !empty($_REQUEST['opc'.$pfj]))	{
                         $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                         $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj],$vectorD);
                    }
                    if(!empty($_REQUEST['minsertardiagnostico'.$pfj])){
                         $this->InsertarDiagnosticoBD();
                         $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
                    }
               }
               //lo nuevo de los apoyos
          if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Apoyos'){
          if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}
     
          if($_SESSION['PROCEDIMIENTO'.$pfj]==''){
               $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id]    =    $_REQUEST['hc_os_solicitud_id'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][tipo]                =    $_REQUEST['tipo'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][cargos]            =    $_REQUEST['cargos'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento]    =    $_REQUEST['procedimiento'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][observacion]    =    $_REQUEST['observacion'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][cirugia]            =    $_REQUEST['cirugia'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][ambito]            =    $_REQUEST['ambito'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][finalidad]        =    $_REQUEST['finalidad'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][movil]                =    $_REQUEST['movil'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][fijo]                =    $_REQUEST['fijo'.$pfj];
          }
          if(sizeof($_REQUEST['op'.$pfj]) > 0){
          if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
                         unset($_SESSION['Apoyos_Procedimientos'.$pfj][$paso]);
                         $vector=$_REQUEST['op'.$pfj];
                         foreach($_REQUEST['op'.$pfj] as $cargo=>$valor){
                              $_SESSION['Apoyos_Procedimientos'.$pfj][$paso][$cargo][$valor]=$_REQUEST[$cargo.$pfj];
                         }
                    }
                    $vectorAPD= $this->Busqueda_Avanzada_Apoyos();
                    $this->frmForma_Seleccion_Apoyos($vectorAPD);
               }
     
               //lo nuevo de los apoyos
               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos_Medicos'){
          if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}
     
          if($_SESSION['PROCEDIMIENTO'.$pfj]==''){
               $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id]    =    $_REQUEST['hc_os_solicitud_id'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][tipo]                =    $_REQUEST['tipo'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][cargos]            =    $_REQUEST['cargos'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento]    =    $_REQUEST['procedimiento'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][observacion]    =    $_REQUEST['observacion'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][cirugia]            =    $_REQUEST['cirugia'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][ambito]            =    $_REQUEST['ambito'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][finalidad]        =    $_REQUEST['finalidad'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][movil]                =    $_REQUEST['movil'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][fijo]                =    $_REQUEST['fijo'.$pfj];
          }
          if(sizeof($_REQUEST['opD'.$pfj]) > 0){
          $TiposDiagnosticos=$_REQUEST['dx'.$pfj];
          if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
                         unset($_SESSION['Diagnosticos_QX'.$pfj][$paso]);
                         foreach($_REQUEST['opD'.$pfj] as $cargo=>$descripcion){
                              $_SESSION['Diagnosticos_QX'.$pfj][$paso][$cargo][$descripcion]=$TiposDiagnosticos[$cargo];
                         }
                    }
                    $vectorAPD= $this->Busqueda_Avanzada_Diagnosticos();
                    $this->frmForma_Seleccion_Diagnosticos_Medicos($vectorAPD);
               }
     
		//los materiales de inventarios
          if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Materiales'){
          if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}
     
          if($_SESSION['PROCEDIMIENTO'.$pfj]==''){
               $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id]    =    $_REQUEST['hc_os_solicitud_id'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][tipo]                =    $_REQUEST['tipo'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][cargos]            =    $_REQUEST['cargos'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento]    =    $_REQUEST['procedimiento'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][observacion]    =    $_REQUEST['observacion'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][cirugia]            =    $_REQUEST['cirugia'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][ambito]            =    $_REQUEST['ambito'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][finalidad]        =    $_REQUEST['finalidad'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][movil]                =    $_REQUEST['movil'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][fijo]                =    $_REQUEST['fijo'.$pfj];
          }
          if(sizeof($_REQUEST['seleccion'.$pfj]) > 0){
          if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
          unset($_SESSION['Insumos'.$pfj][$paso]);
          $vector=$_REQUEST['seleccion'.$pfj];
          foreach($_REQUEST['seleccion'.$pfj] as $codigo=>$descripcion){
               $_SESSION['Insumos'.$pfj][$paso][$codigo][$descripcion]=$_REQUEST[$codigo.$pfj];
                         }
                    }
                    $vectorMat= $this->Busqueda_Avanzada_Materiales();
                    $this->frmForma_Seleccion_Materiales($vectorMat);
               }
     
               //los equipos quirurgicos
               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_EquiposQX'){
     
          if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}
     
          if($_SESSION['PROCEDIMIENTO'.$pfj]==''){
               $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id]    =    $_REQUEST['hc_os_solicitud_id'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][tipo]                =    $_REQUEST['tipo'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][cargos]            =    $_REQUEST['cargos'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento]    =    $_REQUEST['procedimiento'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][observacion]    =    $_REQUEST['observacion'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][cirugia]            =    $_REQUEST['cirugia'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][ambito]            =    $_REQUEST['ambito'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][finalidad]        =    $_REQUEST['finalidad'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][movil]                =    $_REQUEST['movil'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][fijo]                =    $_REQUEST['fijo'.$pfj];
          }
          if(sizeof($_REQUEST['seleccionEquipos'.$pfj]) > 0){
          if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
          unset($_SESSION['Equipos'.$pfj][$paso]);
          $vector=$_REQUEST['seleccionEquipos'.$pfj];
          foreach($_REQUEST['seleccionEquipos'.$pfj] as $codigo=>$descripcion){
               $_SESSION['Equipos'.$pfj][$paso][$codigo][$descripcion]=$_REQUEST[$codigo.$pfj];
                         }
                    }
                    $vectorEQX= $this->Busqueda_Avanzada_EquiposQX();
                    $this->frmForma_Seleccion_EquiposQX($vectorEQX);
               }
     
               //los equipos quirurgicos
               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_EstanciaQX'){
     
          if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}
     
          if($_SESSION['PROCEDIMIENTO'.$pfj]==''){
               $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id]    =    $_REQUEST['hc_os_solicitud_id'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][tipo]                =    $_REQUEST['tipo'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][cargos]            =    $_REQUEST['cargos'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento]    =    $_REQUEST['procedimiento'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][observacion]    =    $_REQUEST['observacion'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][cirugia]            =    $_REQUEST['cirugia'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][ambito]            =    $_REQUEST['ambito'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][finalidad]        =    $_REQUEST['finalidad'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][movil]                =    $_REQUEST['movil'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][fijo]                =    $_REQUEST['fijo'.$pfj];
          }
          if(sizeof($_REQUEST['seleccionEstacion'.$pfj]) > 0){
          if(empty($_REQUEST['paso1'.$pfj])){$paso=1;}else{$paso=$_REQUEST['paso1'.$pfj];}
          unset($_SESSION['Estacion'.$pfj][$paso]);
          $vector=$_REQUEST['seleccionEstacion'.$pfj];
          $vectorPos=$_REQUEST['seleccionPOSEstacion'.$pfj];
          $vectorPre=$_REQUEST['seleccionPREEstacion'.$pfj];
          foreach($_REQUEST['seleccionEstacion'.$pfj] as $codigo=>$descripcion){
               $_SESSION['Estacion'.$pfj][$paso][$codigo][$descripcion]=$_REQUEST[$codigo.$pfj];
                              $_SESSION['Estacion'.$pfj][$paso][$codigo]['PRE']=$vectorPre[$codigo];
                              $_SESSION['Estacion'.$pfj][$paso][$codigo]['POS']=$vectorPos[$codigo];
                         }
                    }
                    $vectorEQX= $this->Busqueda_Avanzada_EstanciaQX();
                    $this->frmForma_Seleccion_EstanciaQX($vectorEQX);
               }
     
               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_BSangreQX'){
     
          if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}
     
          if($_SESSION['PROCEDIMIENTO'.$pfj]==''){
               $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id]    =    $_REQUEST['hc_os_solicitud_id'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][tipo]                =    $_REQUEST['tipo'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][cargos]            =    $_REQUEST['cargos'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento]    =    $_REQUEST['procedimiento'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][observacion]    =    $_REQUEST['observacion'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][cirugia]            =    $_REQUEST['cirugia'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][ambito]            =    $_REQUEST['ambito'.$pfj];
               //$_SESSION['PROCEDIMIENTO'.$pfj][finalidad]        =    $_REQUEST['finalidad'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][movil]                =    $_REQUEST['movil'.$pfj];
               $_SESSION['PROCEDIMIENTO'.$pfj][fijo]                =    $_REQUEST['fijo'.$pfj];
          }
          if($_REQUEST['cargoSeleccionado'.$pfj]){
          $_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj][$_REQUEST['cargoSeleccionado'.$pfj]]=$_REQUEST['descripcionSelect'.$pfj];
                         unset($_REQUEST['cargoSeleccionado'.$pfj]);
                    }
                    if(sizeof($_REQUEST['cantidades'.$pfj])>0){
                    foreach($_REQUEST['cantidades'.$pfj] as $componente=>$cantidad){
               $_SESSION['SELECCION_COMPONENTES_SANGRE_QX'.$pfj][$componente]=$cantidad;
                         }
                    }
                    $vectorBanco= $this->Busqueda_Avanzada_ComponentesBanco();
                    $this->frmForma_Seleccion_ComponentesBanco($vectorBanco);
               }
     
               if($_REQUEST['accion'.$pfj]=='insertar_varias'){
                    $this->Insertar_Varias_Solicitudes();
          
                    $_REQUEST['hc_os_solicitud_id'.$pfj] = $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id];
                    $_REQUEST['tipo'.$pfj]                    = $_SESSION['PROCEDIMIENTO'.$pfj][tipo];
                    $_REQUEST['cargos'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cargos];
                    $_REQUEST['procedimiento'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento];
                    $_REQUEST['observacion'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][observacion];
                    //$_REQUEST['cirugia'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cirugia];
                    //$_REQUEST['ambito'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][ambito];
                    //$_REQUEST['finalidad'.$pfj]         = $_SESSION['PROCEDIMIENTO'.$pfj][finalidad];
                    $_REQUEST['movil'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][movil];
                    $_REQUEST['fijo'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][fijo];
                    unset($_SESSION['PROCEDIMIENTO'.$pfj]);
                    if($_SESSION['MODIFICANDO'.$pfj]==1){
                         $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
                    }else{
                         $this->Llenar_Procedimiento($_REQUEST['tipo'.$pfj], $_REQUEST['cargos'.$pfj], $_REQUEST['procedimiento'.$pfj]);
                    }
               }
     /*if($_REQUEST['accion'.$pfj]=='eliminarapoyo'){
                    unset ($_SESSION['APOYOS'.$pfj][$_REQUEST['apoyo'.$pfj]]);
                    if($_SESSION['MODIFICANDO'.$pfj]==1){
                         $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
                    }else{
                         $this->Llenar_Procedimiento($_REQUEST['tipo'.$pfj], $_REQUEST['cargos'.$pfj], $_REQUEST['procedimiento'.$pfj]);
                    }
               }*/
               if($_REQUEST['accion'.$pfj]=='volver_de_solicitud_de_apoyos'){
                    $_REQUEST['hc_os_solicitud_id'.$pfj] = $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id];
                    $_REQUEST['tipo'.$pfj]                    = $_SESSION['PROCEDIMIENTO'.$pfj][tipo];
                    $_REQUEST['cargos'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cargos];
                    $_REQUEST['procedimiento'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento];
                    $_REQUEST['observacion'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][observacion];
                    //$_REQUEST['cirugia'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cirugia];
                    //$_REQUEST['ambito'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][ambito];
                    //$_REQUEST['finalidad'.$pfj]         = $_SESSION['PROCEDIMIENTO'.$pfj][finalidad];
                    $_REQUEST['movil'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][movil];
                    $_REQUEST['fijo'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][fijo];
                    foreach($_SESSION['Apoyos_Procedimientos'.$pfj] as $paso=>$vector){
          foreach($vector as $codigo=>$vector1){
                         foreach($vector1 as $descripcion=>$cantidad){
                              if(empty($cantidad)){$cantidad=1;}
               $_SESSION['SOLICITUD_APOYOS_QX'.$pfj][$codigo][$descripcion]=$cantidad;
                              }
                         }
                    }
                    unset($_SESSION['Apoyos_Procedimientos'.$pfj]);
                    $this->Llenar_Procedimiento();
               }
               if($_REQUEST['accion'.$pfj]=='volver_de_solicitud_materiales'){
          $_REQUEST['hc_os_solicitud_id'.$pfj] = $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id];
          $_REQUEST['tipo'.$pfj]                    = $_SESSION['PROCEDIMIENTO'.$pfj][tipo];
          $_REQUEST['cargos'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cargos];
          $_REQUEST['procedimiento'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento];
          $_REQUEST['observacion'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][observacion];
          //$_REQUEST['cirugia'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cirugia];
          //$_REQUEST['ambito'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][ambito];
          //$_REQUEST['finalidad'.$pfj]         = $_SESSION['PROCEDIMIENTO'.$pfj][finalidad];
          $_REQUEST['movil'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][movil];
          $_REQUEST['fijo'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][fijo];
          foreach($_SESSION['Insumos'.$pfj] as $paso=>$vector){
          foreach($vector as $codigo=>$vector1){
                         foreach($vector1 as $descripcion=>$cantidad){
                              if(empty($cantidad)){$cantidad=1;}
               $_SESSION['SOLICITUD_MATERALES_QX'.$pfj][$codigo][$descripcion]=$cantidad;
                              }
                         }
                    }
                    unset($_SESSION['Insumos'.$pfj]);
                    $this->Llenar_Procedimiento();
               }
     
               if($_REQUEST['accion'.$pfj]=='volver_de_solicitud_componentes_sangre'){
	          $_REQUEST['hc_os_solicitud_id'.$pfj] = $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id];
               $_REQUEST['tipo'.$pfj]                    = $_SESSION['PROCEDIMIENTO'.$pfj][tipo];
               $_REQUEST['cargos'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cargos];
               $_REQUEST['procedimiento'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento];
               $_REQUEST['observacion'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][observacion];
               //$_REQUEST['cirugia'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cirugia];
               //$_REQUEST['ambito'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][ambito];
               //$_REQUEST['finalidad'.$pfj]         = $_SESSION['PROCEDIMIENTO'.$pfj][finalidad];
               $_REQUEST['movil'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][movil];
               $_REQUEST['fijo'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][fijo];
               if(sizeof($_SESSION['SELECCION_COMPONENTES_SANGRE_QX'.$pfj])>0){
               $_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj]=$_SESSION['SELECCION_COMPONENTES_SANGRE_QX'.$pfj];
               }
               unset($_SESSION['SELECCION_COMPONENTES_SANGRE_QX'.$pfj]);
               if(sizeof($_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj])>0){
               $_SESSION['SOLICITUD_RESERVA_SANGRE_QX_APOYOS'.$pfj]=$_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj];
               }
               unset($_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj]);
               $this->Llenar_Procedimiento();
          }
     
          if($_REQUEST['accion'.$pfj]=='volver_de_solicitud_equipos'){
          $_REQUEST['hc_os_solicitud_id'.$pfj] = $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id];
          $_REQUEST['tipo'.$pfj]                    = $_SESSION['PROCEDIMIENTO'.$pfj][tipo];
          $_REQUEST['cargos'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cargos];
          $_REQUEST['procedimiento'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento];
          $_REQUEST['observacion'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][observacion];
          //$_REQUEST['cirugia'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cirugia];
          //$_REQUEST['ambito'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][ambito];
          //$_REQUEST['finalidad'.$pfj]         = $_SESSION['PROCEDIMIENTO'.$pfj][finalidad];
          $_REQUEST['movil'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][movil];
          $_REQUEST['fijo'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][fijo];
          foreach($_SESSION['Equipos'.$pfj] as $paso=>$vector){
          foreach($vector as $codigo=>$vector1){
          foreach($vector1 as $descripcion=>$cantidad){
          if(empty($cantidad)){$cantidad=1;}
               $_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj][$codigo][$descripcion]=$cantidad;
                              }
                         }
                    }
                    unset($_SESSION['Equipos'.$pfj]);
                    $this->Llenar_Procedimiento();
               }
     
          if($_REQUEST['accion'.$pfj]=='volver_de_solicitud_estacion'){
          $_REQUEST['hc_os_solicitud_id'.$pfj] = $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id];
          $_REQUEST['tipo'.$pfj]                    = $_SESSION['PROCEDIMIENTO'.$pfj][tipo];
          $_REQUEST['cargos'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cargos];
          $_REQUEST['procedimiento'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento];
          $_REQUEST['observacion'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][observacion];
          //$_REQUEST['cirugia'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cirugia];
          //$_REQUEST['ambito'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][ambito];
          //$_REQUEST['finalidad'.$pfj]         = $_SESSION['PROCEDIMIENTO'.$pfj][finalidad];
          $_REQUEST['movil'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][movil];
          $_REQUEST['fijo'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][fijo];
          foreach($_SESSION['Estacion'.$pfj] as $paso=>$vector){
          foreach($vector as $codigo=>$vector1){
          foreach($vector1 as $inidice=>$valor){
               $_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$codigo][$inidice]=$valor;
                              }
                         }
                    }
                    unset($_SESSION['Estacion'.$pfj]);
                    $this->Llenar_Procedimiento();
               }
     
          if($_REQUEST['accion'.$pfj]=='volver_de_solicitud_diagnosticos'){
          $_REQUEST['hc_os_solicitud_id'.$pfj] = $_SESSION['PROCEDIMIENTO'.$pfj][hc_os_solicitud_id];
          $_REQUEST['tipo'.$pfj]                    = $_SESSION['PROCEDIMIENTO'.$pfj][tipo];
          $_REQUEST['cargos'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cargos];
          $_REQUEST['procedimiento'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][procedimiento];
          $_REQUEST['observacion'.$pfj]     = $_SESSION['PROCEDIMIENTO'.$pfj][observacion];
          //$_REQUEST['cirugia'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][cirugia];
          //$_REQUEST['ambito'.$pfj]             = $_SESSION['PROCEDIMIENTO'.$pfj][ambito];
          //$_REQUEST['finalidad'.$pfj]         = $_SESSION['PROCEDIMIENTO'.$pfj][finalidad];
          $_REQUEST['movil'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][movil];
          $_REQUEST['fijo'.$pfj]                 = $_SESSION['PROCEDIMIENTO'.$pfj][fijo];
          foreach($_SESSION['Diagnosticos_QX'.$pfj] as $paso=>$vector){
          foreach($vector as $codigo=>$vector1){
          foreach($vector1 as $descripcion=>$tipoDiag){
               $_SESSION['SOLICITUD_DIAGNOSTICOS_QX'.$pfj][$codigo][$descripcion]=$tipoDiag;
               }
                         }
                    }
                    unset($_SESSION['Diagnosticos_QX'.$pfj]);
                    $this->Llenar_Procedimiento();
               }
               //Elimina Procedimiento de la variable de session
               if($_REQUEST['accion'.$pfj]=='Eliminar_Procedimientos'){
     
          if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}
          
          unset($_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj][$_REQUEST['cargoEliminar'.$pfj]]);
          unset($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$_REQUEST['cargoEliminar'.$pfj]]);
          unset($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargoEliminar'.$pfj]]);
          unset($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['OBSERVACIONES'][$_REQUEST['cargoEliminar'.$pfj]]);
          if($_SESSION['SOLICITUD_PROCEDIMIENTO_QX_PRINCIPAL'.$pfj]['PRINCIPAL']==$_REQUEST['cargoEliminar'.$pfj]){
          unset($_SESSION['SOLICITUD_PROCEDIMIENTO_QX_PRINCIPAL'.$pfj]['PRINCIPAL']);
                         foreach($_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj] as $cargos=>$datos){
                         $_SESSION['SOLICITUD_PROCEDIMIENTO_QX_PRINCIPAL'.$pfj]['PRINCIPAL']=$cargos;
                              break;
                         }
                    }
                    $this->Llenar_Procedimiento();
               }
               //Modifica Procedimiento de la variable de session
               if($_REQUEST['accion'.$pfj]=='Modificar_Procedimientos'){
                    if($_REQUEST['observacion'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$_REQUEST['observacion'.$pfj];}
          //if($_REQUEST['cirugia'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']);}elseif(!empty($_REQUEST['cirugia'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$_REQUEST['cirugia'.$pfj];}
          //if($_REQUEST['ambito'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']);}elseif(!empty($_REQUEST['ambito'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$_REQUEST['ambito'.$pfj];}
          //if($_REQUEST['finalidad'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']);}elseif(!empty($_REQUEST['finalidad'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$_REQUEST['finalidad'.$pfj];}
          if($_REQUEST['solicitudAmbulatoria'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';}else{$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';}
          if($_REQUEST['nivel'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']);}elseif(!empty($_REQUEST['nivel'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$_REQUEST['nivel'.$pfj];}
          if($_REQUEST['hora'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']);}elseif(!empty($_REQUEST['hora'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$_REQUEST['hora'.$pfj];}
          if($_REQUEST['minutos'.$pfj]==-1){unset($_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']);}elseif(!empty($_REQUEST['minutos'.$pfj])){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$_REQUEST['minutos'.$pfj];}
          if($_REQUEST['FechaCirugiaTentativa'.$pfj]){$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$_REQUEST['FechaCirugiaTentativa'.$pfj];}      
                    
                    $this->Frm_Modificar_Procedimiento($_REQUEST['cargoModificar'.$pfj],$_REQUEST['descripcion'.$pfj]);	
                    return true;
               }
               
               if($_REQUEST['accion'.$pfj]=='Buscador_Modificar_Procedimientos'){
                    $_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['OBSERVACIONES'][$_REQUEST['cargoModificar'.$this->frmPrefijo]]=$_REQUEST['obs'.$this->frmPrefijo];
                    if($_REQUEST['buscar'.$pfj]){
                         $this->Frm_Modificar_Procedimiento($_REQUEST['cargoModificar'.$pfj],$_REQUEST['descripcion'.$pfj]);	
                         return true;	
                    }elseif($_REQUEST['Volver'.$pfj]){
                         $this->Llenar_Procedimiento();
                         return true;	
                    }elseif($_REQUEST['guardar'.$pfj]){
                         $vector=$_REQUEST['opD'.$pfj];
                         $vectorDiags=$_REQUEST['dx'.$pfj];
                         foreach($vector as $cargoProc=>$Vecdiagnostico){
                              foreach($Vecdiagnostico as $codiag=>$nombreDiag){											
                                   if(empty($vectorDiags[$cargoProc][$codiag])){
                                        $tipoDiag='1';
                                   }else{
                                        $tipoDiag=$vectorDiags[$cargoProc][$codiag];
                                   }
                                   $_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$cargoProc][$codiag][$tipoDiag]=$nombreDiag;
                                   if(empty($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICO_PRINCIPAL'][$cargoProc])){
                                        $_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICO_PRINCIPAL'][$cargoProc]=$codiag;
                                   }
                              }	
                         }
                         $this->Frm_Modificar_Procedimiento($_REQUEST['cargoModificar'.$pfj],$_REQUEST['descripcion'.$pfj]);	
                         return true;	
                    }elseif($_REQUEST['EliminacionDiagnostico'.$pfj]){
                         unset($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICOS'][$_REQUEST['cargoModificar'.$this->frmPrefijo]][$_REQUEST['codiag'.$this->frmPrefijo]]);
                         if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargoModificar'.$this->frmPrefijo]]==$_REQUEST['codiag'.$this->frmPrefijo]){
                              unset($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargoModificar'.$this->frmPrefijo]]);
                              $_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargoModificar'.$this->frmPrefijo]]=$_REQUEST['codiag_uno'.$this->frmPrefijo];
                         }
                         $this->Frm_Modificar_Procedimiento($_REQUEST['cargoModificar'.$pfj],$_REQUEST['descripcion'.$pfj]);	
                         return true;		
                    }elseif($_REQUEST['CambioDiagPrincipal'.$pfj]){
                         $_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargoModificar'.$this->frmPrefijo]]=$_REQUEST['codiag'.$this->frmPrefijo];						
                         $this->Frm_Modificar_Procedimiento($_REQUEST['cargoModificar'.$pfj],$_REQUEST['descripcion'.$pfj]);	
                         return true;
                    }elseif($_REQUEST['guardarVolver'.$pfj]){				
                         $this->Llenar_Procedimiento();
                         return true;
                    }
               }
     
               //Elimina Material de la variable de session
               if($_REQUEST['accion'.$pfj]=='Eliminar_Materiales'){
               unset($_SESSION['SOLICITUD_MATERALES_QX'.$pfj][$_REQUEST['productoEliminar'.$pfj]]);
                    $this->Llenar_Procedimiento();
               }
     
               //Elimina Equipo de la variable de session
               if($_REQUEST['accion'.$pfj]=='Eliminar_Tipo_Equipo'){
               unset($_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj][$_REQUEST['equipoEliminar'.$pfj]]);
                    $this->Llenar_Procedimiento();
               }
               if($_REQUEST['accion'.$pfj]=='eliminarapoyo'){
                    unset($_SESSION['SOLICITUD_APOYOS_QX'.$pfj][$_REQUEST['cargoEliminar'.$pfj]]);
                    unset($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$_REQUEST['cargoEliminar'.$pfj]]);
                    unset($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargoEliminar'.$pfj]]);
                    unset($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['OBSERVACIONES'][$_REQUEST['cargoEliminar'.$pfj]]);
                    $this->Llenar_Procedimiento();
               }
               if($_REQUEST['accion'.$pfj]=='Eliminar_Tipo_Estancia'){
               unset($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$_REQUEST['estanciaEliminar'.$pfj]]);
                    $this->Llenar_Procedimiento();
               }
               if($_REQUEST['accion'.$pfj]=='Eliminar_Diagnostico'){
               unset($_SESSION['SOLICITUD_DIAGNOSTICOS_QX'.$pfj][$_REQUEST['diagnosticoEliminar'.$pfj]]);
                    $this->Llenar_Procedimiento();
               }
		     if($_REQUEST['accion'.$pfj]=='Eleccion_Procedimientos_Principal'){
               $_SESSION['SOLICITUD_PROCEDIMIENTO_QX_PRINCIPAL'.$pfj]['PRINCIPAL']=$_REQUEST['cargoPrincipal'.$pfj];
                    $this->Llenar_Procedimiento();
               }
               if($_REQUEST['accion'.$pfj]=='Eliminar_Apoyo_Banco_Sangre'){
          	unset($_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj][$_REQUEST['cargoEliminar'.$pfj]]);
                    $_REQUEST['accion'.$pfj]='Busqueda_Avanzada_BSangreQX';
          	$this->GetForma();
               }
               if($_REQUEST['accion'.$pfj]=='Eliminar_Componente_Sangre'){
               unset($_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj][$_REQUEST['componenteEliminar'.$pfj]]);
               $this->Llenar_Procedimiento();
               }
               if($_REQUEST['accion'.$pfj]=='Eliminar_Apoyo_Sangre'){
               unset($_SESSION['SOLICITUD_RESERVA_SANGRE_QX_APOYOS'.$pfj][$_REQUEST['ApoyoEliminar'.$pfj]]);
               $this->Llenar_Procedimiento();
               }
               if($_REQUEST['accion'.$pfj]=='InsertarObservacionesSolicitud'){
          $this->InsertarObservacionesSolicitud($_REQUEST['SolicitudId'.$pfj]);
               }
     
               if($_REQUEST['accion'.$pfj]=='InsertarObservacionesSolicitud'){
          $this->InsertarObservacionesSolicitud($_REQUEST['SolicitudId'.$pfj]);
               }
     
               if($_REQUEST['accion'.$pfj]=='Guardar_Observaciones_Solicitud'){
          if($_REQUEST['Salir'.$pfj]){
                    $_REQUEST['accion'.$pfj]='FormaPrincipal';
          $this->GetForma();
                    }elseif($_REQUEST['Modificar'.$pfj]){
                         if($this->ModificarDatosObservacionesSolicitud()==true){
                              $this->InsertarObservacionesSolicitud($_REQUEST['SolicituId'.$pfj]);
                         }
                    }else{
          if($this->InsertarDatosObservacionesSolicitud()==true){
                              $this->InsertarObservacionesSolicitud($_REQUEST['SolicituId'.$pfj]);
                         }
                    }
               }
               if($_REQUEST['accion'.$pfj]=='EliminarObservacionSolicitud'){
          if($this->EliminarObservacionSolicitud()==true){
                    $this->InsertarObservacionesSolicitud($_REQUEST['SolicitudId'.$pfj]);
                    }
               }
               if($_REQUEST['accion'.$pfj]=='EditaObservacionSolicitud'){
                    $this->InsertarObservacionesSolicitud($_REQUEST['SolicitudId'.$pfj],$_REQUEST['observacionId'.$pfj],$_REQUEST['observacion'.$pfj]);
               }
     
          if($_REQUEST['accion'.$pfj]=='Eleccion_Diagnostico_Principal'){
               $_SESSION['SOLICITUD_DIAGNOSTICOS_QX_PRINCIPAL'.$pfj]=$_REQUEST['diagnostico'.$pfj];
                    $this->Llenar_Procedimiento();
                    }
     
                    //fin
          }
          return $this->salida;
     }

     function Busqueda_Avanzada_Diagnosticos($codigo,$diagnostico){
          
          $pfj=$this->frmPrefijo;
          $FechaInicio = $this->datosPaciente[fecha_nacimiento];
          $FechaFin = date("Y-m-d");
          $edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
          list($dbconn) = GetDBconn();
		$codigo = STRTOUPPER ($codigo);
          $diagnostico  =STRTOUPPER($diagnostico);

          $busqueda1 = '';
          $busqueda2 = '';

          if ($codigo != ''){
               $busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
          }

          if (($diagnostico != '') AND ($codigo != '')){
               if (eregi('%',$diagnostico)){
                    $busqueda2 ="AND diagnostico_nombre LIKE '$diagnostico'";      
               }else{
                    $busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
               }
          }

          if (($diagnostico != '') AND ($codigo == '')){
               if(eregi('%',$diagnostico)){
                         $busqueda2 ="WHERE diagnostico_nombre LIKE '$diagnostico'";
               }else{
                         $busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
               }
          }
          //filtro por clasificacion de diagnosticos
          $filtro='';
          if(empty($busqueda1) AND empty($busqueda2)){
          
               $filtro = "WHERE (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
                         AND   (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
                         AND   (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
          }else{
               $filtro = "AND (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
                         AND (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
                         AND (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
          }

          $filtro1='';
          if(!empty($this->capitulo)){
               $filtro1 = " AND (B.capitulo='".$this->capitulo."' OR B.capitulo is null)";
          }
          if(!empty($this->grupo)){
               $filtro1 .= " AND (B.grupo='".$this->grupo."' OR B.grupo is null)";
          }
          if(!empty($this->categoria)){
               $filtro1 .= " AND (B.categoria='".$this->categoria."' OR B.categoria is null)";
          }

          $query = "SELECT diagnostico_id, diagnostico_nombre
                    FROM diagnosticos
                    $busqueda1 $busqueda2
                    $filtro $filtro1";									
          
          list($dbconn) = GetDBconn();
          
          if(empty($_REQUEST['conteo'])){
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               $this->conteo=$result->RecordCount();
          }else{
               $this->conteo=$_REQUEST['conteo'];
          }
          $query.=" ORDER BY diagnostico_id";
          $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
          
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
               while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
          }    
     	if($this->conteo==='0'){
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
               return false;
          }
          $result->Close();
          return $vars;	
     }


//clzc - si
     function InsertarDiagnosticoBD(){
     
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          foreach($_REQUEST['opD'.$pfj] as $index=>$codigo){
                    $arreglo=explode(",",$codigo);
          $TiposDiagnosticos=$_REQUEST['dx'.$pfj];
          if($_SESSION['SOLICITUD_DIAGNOSTICOS_QX_PRINCIPAL'.$pfj]==$codigo){
          $sw_principal=1;
          }else{
          $sw_principal=0;
          }
               $query="INSERT INTO hc_os_solicitudes_diagnosticos
                                                                           (hc_os_solicitud_id, diagnostico_id,
                              tipo_diagnostico,sw_principal)
                                                                           VALUES
                                        ('".$arreglo[0]."', '".$arreglo[1]."',
               '".$TiposDiagnosticos[$arreglo[1]]."','$sw_principal'
               )";
               $resulta=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al insertar en hc_os_solicitudes";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
		$dbconn->CommitTrans();
          $this->RegistrarSubmodulo($this->GetVersion());            
     	return true;
     }



	//cor - clzc-jea - spqx
	function Busqueda_Avanzada()
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$opcion      = ($_REQUEST['criterio1'.$pfj]);
		$cargos       = ($_REQUEST['cargos'.$pfj]);
		$descripcion =STRTOUPPER($_REQUEST['descripcion'.$pfj]);
		$filtroTipoCargo = '';
		$busqueda1 = '';
		$busqueda2 = '';
		if($opcion != '-1' && !empty($opcion)){
               $filtroTipoCargo=" AND a.tipo_cargo = '$opcion'";
		}
		if($cargos != ''){
               $busqueda1 =" AND a.cargo LIKE '$cargos%'";
		}
		if ($descripcion != ''){
			if (eregi('%',$descripcion)){
				$busqueda2 ="AND a.descripcion LIKE '$descripcion'";
			}else{
				$busqueda2 ="AND a.descripcion LIKE '%$descripcion%'";
			}
		}
		
          if(empty($_REQUEST['conteo'.$pfj])){
			$query = "SELECT count(*) FROM (SELECT DISTINCT a.cargo,
                         a.descripcion, a.grupo_tipo_cargo,
                         c.descripcion as tipo, d.tipo_cargo
                         FROM cups a, qx_grupos_tipo_cargo b, grupos_tipos_cargo c,
                         tipos_cargos d
                         WHERE a.grupo_tipo_cargo = c.grupo_tipo_cargo 
                         AND b.grupo_tipo_cargo = c.grupo_tipo_cargo
                         AND a.sw_estado = '1'
                         AND a.tipo_cargo = d.tipo_cargo AND c.grupo_tipo_cargo = d.grupo_tipo_cargo
                         $filtroTipoCargo$busqueda1$busqueda2)as a";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
				$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo){
                    $Of=0;
                    $_REQUEST['Of'.$pfj]=0;
                    $_REQUEST['paso1'.$pfj]=1;
			}
		}
		$query = "SELECT DISTINCT a.cargo, a.descripcion, a.grupo_tipo_cargo,
                    c.descripcion as tipo, d.tipo_cargo
                    FROM cups a, qx_grupos_tipo_cargo b, grupos_tipos_cargo c,
                    tipos_cargos d
                    WHERE a.grupo_tipo_cargo = c.grupo_tipo_cargo 
                    AND b.grupo_tipo_cargo = c.grupo_tipo_cargo
                    AND a.sw_estado = '1'
                    AND a.tipo_cargo = d.tipo_cargo AND c.grupo_tipo_cargo = d.grupo_tipo_cargo
                    $filtroTipoCargo$busqueda1$busqueda2
                    LIMIT ".$this->limit." OFFSET $Of;";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF){
               $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
               $i++;
		}
		if($this->conteo==='0'){
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
	}


     //cor - clzc - spqx
     function Consulta_Procedimientos_Solicitados(){
		$pfj=$this->frmPrefijo;
		//definiendo el tipo de usuario que esta ingresando a la aplicacion
		if (($this->tipo_profesional=='1') OR ($this->tipo_profesional=='2')){
			$_SESSION['PROFESIONAL'.$pfj]=1;//usuario medico
		}
		//fin del tipo de usuario
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconnect) = GetDBconn();
		//$this->ingreso = 757;
		//query por una evolucion del paciente ok 23/jul/04
		$query1= "SELECT a.hc_os_solicitud_id, a.evolucion_id, a.cargo, b.observacion,
		--b.tipo_cirugia_id, b.ambito_cirugia_id, b.finalidad_procedimiento_id,j.descripcion as cirugia,k.descripcion as ambito,l.descripcion as finalidad, 
		c.diagnostico_id,d.tipo_equipo_fijo_id, e.tipo_equipo_id, g.diagnostico_nombre, 
		h.descripcion as fijo,i.descripcion as movil, m.descripcion, 
		n.descripcion as tipo 
		
		FROM hc_os_solicitudes a,
		hc_os_solicitudes_procedimientos b
		--left join qx_tipos_cirugia j on (b.tipo_cirugia_id = j.tipo_cirugia_id) 
		left join hc_os_solicitudes_diagnosticos c on (b.hc_os_solicitud_id = c.hc_os_solicitud_id)  
		left join diagnosticos g on (c.diagnostico_id = g.diagnostico_id) 
		left join hc_os_solicitudes_requerimientos_equipo_quirofano d on (b.hc_os_solicitud_id = d.hc_os_solicitud_id) 
		left join qx_tipo_equipo_fijo h on (d.tipo_equipo_fijo_id = h.tipo_equipo_fijo_id)
		left join hc_os_solicitudes_requerimientos_equipos_moviles e on (b.hc_os_solicitud_id = e.hc_os_solicitud_id) 
		left join qx_tipo_equipo_movil i on (e.tipo_equipo_id = i.tipo_equipo_id), 
		--qx_ambitos_cirugias k,qx_finalidades_procedimientos l,
		cups m, grupos_tipos_cargo n
		
		WHERE a.hc_os_solicitud_id = b.hc_os_solicitud_id AND a.evolucion_id = ".$this->evolucion."
		--AND b.ambito_cirugia_id = k.ambito_cirugia_id AND b.finalidad_procedimiento_id = l.finalidad_procedimiento_id
		AND a.cargo  = m.cargo  AND m.grupo_tipo_cargo = n.grupo_tipo_cargo order by a.hc_os_solicitud_id";
		$criterio='';
		if(!empty($this->plan_id)){
			$criterio = ",informacion_cargo('".$this->plan_id."',a.cargo,'".$this->departamento."')";
		}
		//query igual a la anterior solo que busca todas las evoluciones d un ingreso
		$query= "SELECT a.hc_os_solicitud_id, a.evolucion_id, a.cargo, b.observacion,
		--b.tipo_cirugia_id, b.ambito_cirugia_id, b.finalidad_procedimiento_id,j.descripcion as cirugia,k.descripcion as ambito,l.descripcion as finalidad
		c.diagnostico_id,d.tipo_equipo_fijo_id, e.tipo_equipo_id, g.diagnostico_nombre, 
		h.descripcion as fijo,i.descripcion as movil, m.descripcion, n.descripcion as tipo
		$criterio
		FROM hc_os_solicitudes a,
		hc_os_solicitudes_procedimientos b 
		--left join qx_tipos_cirugia j on (b.tipo_cirugia_id = j.tipo_cirugia_id) 
		left join hc_os_solicitudes_diagnosticos c on (b.hc_os_solicitud_id = c.hc_os_solicitud_id)  
		left join diagnosticos g on (c.diagnostico_id = g.diagnostico_id) 
		left join hc_os_solicitudes_requerimientos_equipo_quirofano d on (b.hc_os_solicitud_id = d.hc_os_solicitud_id) 
		left join qx_tipo_equipo_fijo h on (d.tipo_equipo_fijo_id = h.tipo_equipo_fijo_id)
		left join hc_os_solicitudes_requerimientos_equipos_moviles e on (b.hc_os_solicitud_id = e.hc_os_solicitud_id) 
		left join qx_tipo_equipo_movil i on (e.tipo_equipo_id = i.tipo_equipo_id), 
		--qx_ambitos_cirugias k,qx_finalidades_procedimientos l, 
		cups m, grupos_tipos_cargo n, hc_evoluciones p
		WHERE a.hc_os_solicitud_id = b.hc_os_solicitud_id
		AND p.ingreso = ".$this->ingreso." AND a.evolucion_id = p.evolucion_id
		--AND b.ambito_cirugia_id = k.ambito_cirugia_id AND b.finalidad_procedimiento_id = l.finalidad_procedimiento_id
		AND a.cargo  = m.cargo  AND m.grupo_tipo_cargo = n.grupo_tipo_cargo order by a.hc_os_solicitud_id";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconnect->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error al ejecutar la Consulta";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}else{
			if($result->RecordCount()<1){
					return $vector;
			}
			$i=0;
			while($arr=$result->FetchRow()){
                    $vector0[$arr['hc_os_solicitud_id']]=$arr;
                    $vector1[$arr['hc_os_solicitud_id']][$arr['diagnostico_id']]=$arr['diagnostico_nombre'];
                    $vector2[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_fijo_id']]=$arr['fijo'];
                    $vector3[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_id']]=$arr['movil'];
			}
		}
		$vector[]=$vector0;
		$vector[]=$vector1;
		$vector[]=$vector2;
		$vector[]=$vector3;
		$result->Close();
		return $vector;
  }

     //cor - clzc - spqx
     function Insertar_Solicitud_Procedimiento(){

		$pfj=$this->frmPrefijo;	
		
		list($dbconn) = GetDBconn();
		if($_REQUEST['solicitudAmbulatoria'.$pfj]){
			$solicitudAmbulatoria=1;
		}else{
			$solicitudAmbulatoria=0;
		}
		$dbconn->BeginTrans();			
		
		if(!empty($_REQUEST['FechaCirugiaTentativa'.$pfj]) && $_REQUEST['hora'.$pfj]!=-1 && $_REQUEST['minutos'.$pfj]!=-1){
		(list($dia,$mes,$ano)=explode('/',$_REQUEST['FechaCirugiaTentativa'.$pfj]));
			$fechaTentativa="'".$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['hora'.$pfj].':'.$_REQUEST['minutos'.$pfj].':'.'00'."'";
		}else{
			$fechaTentativa='NULL';
		}
		
		$query="SELECT nextval('hc_os_solicitudes_datos_acto_qx_acto_qx_id_seq')";
		$result=$dbconn->Execute($query);
		$acto_qx_id=$result->fields[0];	
		
		$query="INSERT INTO hc_os_solicitudes_datos_acto_qx(acto_qx_id, nivel_autorizacion,fecha_tentativa_cirugia,evolucion_id)
							VALUES('".$acto_qx_id."', '".$_REQUEST['nivel'.$pfj]."',$fechaTentativa,'".$this->evolucion."');";
		
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			//Insert de los procedimientos
			foreach($_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj] as $cargos=>$datos){
			
				$query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
				$result=$dbconn->Execute($query);
				$hc_solicitud_id=$result->fields[0];
				
				$query="INSERT INTO hc_os_solicitudes(hc_os_solicitud_id,cargo,evolucion_id,plan_id,os_tipo_solicitud_id,
				sw_estado,cantidad,sw_programado,sw_ambulatorio,sw_no_autorizado,paciente_id,tipo_id_paciente)
				VALUES('".$hc_solicitud_id."','".$cargos."','".$this->evolucion."','".$this->plan_id."','".ModuloGetVar('','','TipoSolicitudProcedimientos')."',
				'1','1','0','".$solicitudAmbulatoria."','0','".$this->paciente."','".$this->tipoidpaciente."')";
				
				$result=$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
					if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$cargos]){
						foreach($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICOS'][$cargos] as $codigoDiagnostico=>$vectorDiag){			
							foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
								$principal='0';
								if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$cargos]==$codigoDiagnostico){
									$principal='1';
								}
								$query="INSERT INTO hc_os_solicitudes_diagnosticos(hc_os_solicitud_id,diagnostico_id,tipo_diagnostico,sw_principal)
								VALUES('".$hc_solicitud_id."','".$codigoDiagnostico."','".$tipoDiagnostico."','".$principal."')";
								
								$result=$dbconn->Execute($query);
								if($dbconn->ErrorNo() != 0){
									$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
								}
							}
						}		
					}
					$query="INSERT INTO hc_os_solicitudes_acto_qx(hc_os_solicitud_id,observacion,acto_qx_id)
					VALUES('".$hc_solicitud_id."','".$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['OBSERVACIONES'][$cargos]."','".$acto_qx_id."')";
					
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}	
				}
			}
			
			//Insert de los apoyos			
			foreach ($_SESSION['SOLICITUD_APOYOS_QX'.$pfj] as $codigo=>$vect){
			  foreach ($vect as $descripcion=>$cantidad){
					(list($descript,$tipo)=explode('||//',$descripcion));
					$query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
					$result=$dbconn->Execute($query);
					$hc_solicitud_id=$result->fields[0];
					
					$query="INSERT INTO hc_os_solicitudes(hc_os_solicitud_id,cargo,evolucion_id,plan_id,os_tipo_solicitud_id,
					sw_estado,cantidad,sw_programado,sw_ambulatorio,sw_no_autorizado,paciente_id,tipo_id_paciente)
					VALUES('".$hc_solicitud_id."','".$codigo."','".$this->evolucion."','".$this->plan_id."','".ModuloGetVar('','','TipoSolicitudProcedimientos')."',
					'1','$cantidad','0','".$solicitudAmbulatoria."','0','".$this->paciente."','".$this->tipoidpaciente."')";
					
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
						if ($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$codigo]){
							foreach($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag){			
								foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
									$principal='0';
									if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico){
										$principal='1';
									}
									$query="INSERT INTO hc_os_solicitudes_diagnosticos(hc_os_solicitud_id,diagnostico_id,tipo_diagnostico,sw_principal)
									VALUES('".$hc_solicitud_id."','".$codigoDiagnostico."','".$tipoDiagnostico."','".$principal."')";
									
									$result=$dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0){
										$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
									}
								}
							}		
						}
						$query="INSERT INTO hc_os_solicitudes_acto_qx(hc_os_solicitud_id,observacion,acto_qx_id)
						VALUES('".$hc_solicitud_id."','".$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."','".$acto_qx_id."')";
						
						$result=$dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}	
					}
				}	
			}
			//insert de materiales
			
			foreach($_SESSION['SOLICITUD_MATERALES_QX'.$pfj] as $codigo=>$vector){
				foreach($vector as $descripcion=>$cantidad){
					$query="INSERT INTO hc_os_solicitudes_otros_productos_inv
									(acto_qx_id, codigo_producto,cantidad)
										VALUES('".$acto_qx_id."', '".$codigo."','".$cantidad."');";
						
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_otros_productos_inv";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			//insert de equipos
			
			foreach($_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj] as $codigo=>$vector){
				foreach($vector as $descripcion=>$cantidad){
					(list($Equipo,$Tipo)=explode('||//',$codigo));
					if($Tipo=='FIJO'){
						$query="INSERT INTO hc_os_solicitudes_requerimientos_equipo_quirofano
												(acto_qx_id, tipo_equipo_fijo_id,cantidad)
												VALUES('".$acto_qx_id."','".$Equipo."','".$cantidad."');";

						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en hc_os_solicitudes_requerimientos_equipo_quirofano";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}else{
						$query="INSERT INTO hc_os_solicitudes_requerimientos_equipos_moviles
						(acto_qx_id, tipo_equipo_id,cantidad)
						VALUES('".$acto_qx_id."', '".$Equipo."','".$cantidad."');";
						
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en hc_os_solicitudes_requerimientos_equipos_moviles";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
			}
			//insert de estancia		
			$vector= $this->Busqueda_Avanzada_EstanciaQX();
			if($vector){
				unset($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj]);	
				for($i=0;$i<sizeof($vector);$i++){
					$pre='0';
					$pos='0';
					if($_REQUEST[$vector[$i]['tipo_clase_cama_id'].'PRE'.$pfj]==1){
						$_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']]['PRE']=1;
						$pre='1';
					}
					if($_REQUEST[$vector[$i]['tipo_clase_cama_id'].'POS'.$pfj]==1){
						$_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']]['POS']=1;
						$pos='1';
					}
					if($pre=='1' || $pos=='1'){
						$query="INSERT INTO hc_os_solicitudes_estancia
						(acto_qx_id, tipo_clase_cama_id,sw_pre_qx,sw_pos_qx)
						VALUES('".$acto_qx_id."','".$vector[$i]['tipo_clase_cama_id']."','$pre','$pos');";
						
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en hc_os_solicitudes_estancia";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
			}
			//insert de bolsas de sagre						
			if(sizeof($_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj])>0){
				$query=" SELECT nextval('banco_sangre_reserva_solicitud_reserva_sangre_id_seq')";
				$resulta=$dbconn->Execute($query);
				$SolitudReserva=$resulta->fields[0];
				$query ="INSERT INTO banco_sangre_reserva(solicitud_reserva_sangre_id,paciente_id,tipo_id_paciente,
				ubicacion_paciente,responsable_solicitud,departamento,sw_urgencia,grupo_sanguineo,
				rh,fecha_hora_reserva,transfuciones_ant,reacciones_adv,
				descripcion_reac,embarazos_previos,fecha_ultimo_embarazo,motivo_reserva,
				sw_estado,estado_gestacion,usuario_id,fecha_registro)VALUES(
				'$SolitudReserva','".$this->paciente."','".$this->tipoidpaciente."',
				'','','".$this->departamento."','0',
				NULL,NULL,$fechaTentativa,'0','0','','0',NULL,
				'','1','0','".UserGetUID()."','".date('Y-m-d H:i:s')."')";
				
				$resulta=$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en banco_sangre_reserva";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				foreach($_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj] as $componente=>$cantidad){
					if($cantidad){
						(list($componente_id,$nomcomponente)=explode('||//',$componente));
						$query ="INSERT INTO banco_sangre_reserva_detalle(solicitud_reserva_sangre_id,tipo_componente_id,cantidad_componente,sw_estado)
						VALUES('$SolitudReserva','$componente_id','$cantidad','1')";
						
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en banco_sangre_reserva_detalle";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
				foreach($_SESSION['SOLICITUD_RESERVA_SANGRE_QX_APOYOS'.$pfj] as $cargo=>$descripcion){
					$query ="INSERT INTO banco_sangre_reserva_otros_servicios(solicitud_reserva_sangre_id,cargo)
					VALUES('$SolitudReserva','$cargo')";
					
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en Modulo banco_sangre_reserva_otros_servicios";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$query ="INSERT INTO banco_sangre_reserva_hc(solicitud_reserva_sangre_id,evolucion_id,ingreso,hc_os_solicitud_id,acto_qx_id)
				VALUES('$SolitudReserva','".$this->evolucion."','".$this->ingreso."',NULL,'".$acto_qx_id."');";
				
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en Modulo banco_sangre_reserva_hc";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$dbconn->CommitTrans();
			$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']=$acto_qx_id;
               $this->RegistrarSubmodulo($this->GetVersion());            
			return true;			
		}		
		return false;
	}

	function Modificar_Solicitud_Procedimiento(){

		$pfj=$this->frmPrefijo;	
		
		list($dbconn) = GetDBconn();
		if($_REQUEST['solicitudAmbulatoria'.$pfj]){
			$solicitudAmbulatoria=1;
		}else{
			$solicitudAmbulatoria=0;
		}
		$dbconn->BeginTrans();
		
		if(!empty($_REQUEST['FechaCirugiaTentativa'.$pfj]) && $_REQUEST['hora'.$pfj]!=-1 && $_REQUEST['minutos'.$pfj]!=-1){
		(list($dia,$mes,$ano)=explode('/',$_REQUEST['FechaCirugiaTentativa'.$pfj]));
			$fechaTentativa="'".$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['hora'.$pfj].':'.$_REQUEST['minutos'.$pfj].':'.'00'."'";
		}else{
			$fechaTentativa='NULL';
		}		
		$acto_qx_id=$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA'];
		$query="UPDATE hc_os_solicitudes_datos_acto_qx 
		SET nivel_autorizacion='".$_REQUEST['nivel'.$pfj]."',
          fecha_tentativa_cirugia=$fechaTentativa 
		WHERE acto_qx_id='".$acto_qx_id."'";					
		
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}	
		
		$query="SELECT hc_os_solicitud_id
		FROM hc_os_solicitudes_acto_qx
		WHERE acto_qx_id='".$acto_qx_id."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
				$this->error = "Error al cargar los tipos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}else{
			while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		//borra todas las solicitudes
		for($i=0;$i<sizeof($vector);$i++){
			$query="DELETE 
			FROM hc_os_solicitudes_diagnosticos 
			WHERE hc_os_solicitud_id='".$vector[$i]['hc_os_solicitud_id']."'";					
			
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$query="DELETE 
				FROM hc_os_solicitudes_acto_qx 
				WHERE hc_os_solicitud_id='".$vector[$i]['hc_os_solicitud_id']."'";					
				
				$result=$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
					$query="DELETE 
					FROM hc_os_solicitudes 
					WHERE hc_os_solicitud_id='".$vector[$i]['hc_os_solicitud_id']."'";										
					
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}				
				}
			}
		}	
		
		
		
		//Insert de los procedimientos
		if($_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj]){		
			foreach($_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj] as $cargos=>$datos){
			
				$query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
				$result=$dbconn->Execute($query);
				$hc_solicitud_id=$result->fields[0];
				
				$query="INSERT INTO hc_os_solicitudes(hc_os_solicitud_id,cargo,evolucion_id,plan_id,os_tipo_solicitud_id,
				sw_estado,cantidad,sw_programado,sw_ambulatorio,sw_no_autorizado,paciente_id,tipo_id_paciente)
				VALUES('".$hc_solicitud_id."','".$cargos."','".$this->evolucion."','".$this->plan_id."','".ModuloGetVar('','','TipoSolicitudProcedimientos')."',
				'1','1','0','".$solicitudAmbulatoria."','0','".$this->paciente."','".$this->tipoidpaciente."')";
				
				$result=$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
					if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$cargos]){
						foreach($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICOS'][$cargos] as $codigoDiagnostico=>$vectorDiag){			
							foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
								$principal='0';
								if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$cargos]==$codigoDiagnostico){
									$principal='1';
								}
								$query="INSERT INTO hc_os_solicitudes_diagnosticos(hc_os_solicitud_id,diagnostico_id,tipo_diagnostico,sw_principal)
								VALUES('".$hc_solicitud_id."','".$codigoDiagnostico."','".$tipoDiagnostico."','".$principal."')";
								
								$result=$dbconn->Execute($query);
								if($dbconn->ErrorNo() != 0){
									$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
								}
							}
						}		
					}
					$query="INSERT INTO hc_os_solicitudes_acto_qx(hc_os_solicitud_id,observacion,acto_qx_id)
					VALUES('".$hc_solicitud_id."','".$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['OBSERVACIONES'][$cargos]."','".$acto_qx_id."')";
					
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}	
				}
			}
		}
		
		//Insert de los apoyos			
		if($_SESSION['SOLICITUD_APOYOS_QX'.$pfj]){
			foreach ($_SESSION['SOLICITUD_APOYOS_QX'.$pfj] as $codigo=>$vect){
			  foreach ($vect as $descripcion=>$cantidad){
					(list($descript,$tipo)=explode('||//',$descripcion));
					$query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
					$result=$dbconn->Execute($query);
					$hc_solicitud_id=$result->fields[0];
					
					$query="INSERT INTO hc_os_solicitudes(hc_os_solicitud_id,cargo,evolucion_id,plan_id,os_tipo_solicitud_id,
					sw_estado,cantidad,sw_programado,sw_ambulatorio,sw_no_autorizado,paciente_id,tipo_id_paciente)
					VALUES('".$hc_solicitud_id."','".$codigo."','".$this->evolucion."','".$this->plan_id."','".ModuloGetVar('','','TipoSolicitudProcedimientos')."',
					'1','$cantidad','0','".$solicitudAmbulatoria."','0','".$this->paciente."','".$this->tipoidpaciente."')";
					
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
						if ($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$codigo]){
							foreach($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag){			
								foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
									$principal='0';
									if($_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico){
										$principal='1';
									}
									$query="INSERT INTO hc_os_solicitudes_diagnosticos(hc_os_solicitud_id,diagnostico_id,tipo_diagnostico,sw_principal)
									VALUES('".$hc_solicitud_id."','".$codigoDiagnostico."','".$tipoDiagnostico."','".$principal."')";
									
									$result=$dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0){
										$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
									}
								}
							}		
						}
						$query="INSERT INTO hc_os_solicitudes_acto_qx(hc_os_solicitud_id,observacion,acto_qx_id)
						VALUES('".$hc_solicitud_id."','".$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."','".$acto_qx_id."')";
						
						$result=$dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}	
					}
				}	
			}
		}
		
		//delete de materiales
		$query="DELETE 
		FROM hc_os_solicitudes_otros_productos_inv 
		WHERE acto_qx_id='".$acto_qx_id."'";					
		
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
			
		foreach($_SESSION['SOLICITUD_MATERALES_QX'.$pfj] as $codigo=>$vector){
			foreach($vector as $descripcion=>$cantidad){
				$query="INSERT INTO hc_os_solicitudes_otros_productos_inv
								(acto_qx_id, codigo_producto,cantidad)
									VALUES('".$acto_qx_id."', '".$codigo."','".$cantidad."');";
				
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en hc_os_solicitudes_otros_productos_inv";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
		}
		//insert de equipos
		//delete de materiales
		$query="DELETE 
		FROM hc_os_solicitudes_requerimientos_equipo_quirofano 
		WHERE acto_qx_id='".$acto_qx_id."'";					
		
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			$query="DELETE 
			FROM hc_os_solicitudes_requerimientos_equipos_moviles 
			WHERE acto_qx_id='".$acto_qx_id."'";					
			
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
			
		foreach($_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj] as $codigo=>$vector){
			foreach($vector as $descripcion=>$cantidad){
				(list($Equipo,$Tipo)=explode('||//',$codigo));
				if($Tipo=='FIJO'){
					$query="INSERT INTO hc_os_solicitudes_requerimientos_equipo_quirofano
											(acto_qx_id, tipo_equipo_fijo_id,cantidad)
											VALUES('".$acto_qx_id."','".$Equipo."','".$cantidad."');";

					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_requerimientos_equipo_quirofano";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}else{
					$query="INSERT INTO hc_os_solicitudes_requerimientos_equipos_moviles
					(acto_qx_id, tipo_equipo_id,cantidad)
					VALUES('".$acto_qx_id."', '".$Equipo."','".$cantidad."');";
					
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_requerimientos_equipos_moviles";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
		}
		
		
		//delete de estancia		
		$query="DELETE 
		FROM hc_os_solicitudes_estancia 
		WHERE acto_qx_id='".$acto_qx_id."'";					
		
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		//insert de estancia		
		$vector= $this->Busqueda_Avanzada_EstanciaQX();
		if($vector){
			unset($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj]);	
			for($i=0;$i<sizeof($vector);$i++){
				$pre='0';
				$pos='0';
				if($_REQUEST[$vector[$i]['tipo_clase_cama_id'].'PRE'.$pfj]==1){
					$_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']]['PRE']=1;
					$pre='1';
				}
				if($_REQUEST[$vector[$i]['tipo_clase_cama_id'].'POS'.$pfj]==1){
					$_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector[$i]['tipo_clase_cama_id']]['POS']=1;
					$pos='1';
				}
				if($pre=='1' || $pos=='1'){
					$query="INSERT INTO hc_os_solicitudes_estancia
					(acto_qx_id, tipo_clase_cama_id,sw_pre_qx,sw_pos_qx)
					VALUES('".$acto_qx_id."','".$vector[$i]['tipo_clase_cama_id']."','$pre','$pos');";
					
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_estancia";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
		}
		
		//delete de bolsas de sagre			
		$query="DELETE 
		FROM banco_sangre_reserva_otros_servicios 
		WHERE solicitud_reserva_sangre_id IN (SELECT solicitud_reserva_sangre_id FROM banco_sangre_reserva_hc WHERE acto_qx_id='".$acto_qx_id."')";
		
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			$query="DELETE 
			FROM banco_sangre_reserva_detalle 
			WHERE solicitud_reserva_sangre_id IN (SELECT solicitud_reserva_sangre_id FROM banco_sangre_reserva_hc WHERE acto_qx_id='".$acto_qx_id."')";					
			
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$query="DELETE 
				FROM banco_sangre_reserva 
				WHERE solicitud_reserva_sangre_id IN (SELECT solicitud_reserva_sangre_id FROM banco_sangre_reserva_hc WHERE acto_qx_id='".$acto_qx_id."')";					
				
				$result=$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
					$query="DELETE 
					FROM banco_sangre_reserva_hc 
					WHERE acto_qx_id='".$acto_qx_id."'";					
					
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en hc_os_solicitudes_datos_acto_qx";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}	
				}
			}
		}
		
		//insert de bolsas de sagre						
		if(sizeof($_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj])>0){
			$query=" SELECT nextval('banco_sangre_reserva_solicitud_reserva_sangre_id_seq')";
			$resulta=$dbconn->Execute($query);
			$SolitudReserva=$resulta->fields[0];
			$query ="INSERT INTO banco_sangre_reserva(solicitud_reserva_sangre_id,paciente_id,tipo_id_paciente,
			ubicacion_paciente,responsable_solicitud,departamento,sw_urgencia,grupo_sanguineo,
			rh,fecha_hora_reserva,transfuciones_ant,reacciones_adv,
			descripcion_reac,embarazos_previos,fecha_ultimo_embarazo,motivo_reserva,
			sw_estado,estado_gestacion,usuario_id,fecha_registro)VALUES(
			'$SolitudReserva','".$this->paciente."','".$this->tipoidpaciente."',
			'','','".$this->departamento."','0',
			NULL,NULL,$fechaTentativa,'0','0','','0',NULL,
			'','1','0','".UserGetUID()."','".date('Y-m-d H:i:s')."')";
			
			$resulta=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en banco_sangre_reserva";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			foreach($_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj] as $componente=>$cantidad){
				if($cantidad){
					(list($componente_id,$nomcomponente)=explode('||//',$componente));
					$query ="INSERT INTO banco_sangre_reserva_detalle(solicitud_reserva_sangre_id,tipo_componente_id,cantidad_componente,sw_estado)
					VALUES('$SolitudReserva','$componente_id','$cantidad','1')";
					
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar en banco_sangre_reserva_detalle";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			foreach($_SESSION['SOLICITUD_RESERVA_SANGRE_QX_APOYOS'.$pfj] as $cargo=>$descripcion){
				$query ="INSERT INTO banco_sangre_reserva_otros_servicios(solicitud_reserva_sangre_id,cargo)
				VALUES('$SolitudReserva','$cargo')";
				
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en Modulo banco_sangre_reserva_otros_servicios";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$query ="INSERT INTO banco_sangre_reserva_hc(solicitud_reserva_sangre_id,evolucion_id,ingreso,hc_os_solicitud_id,acto_qx_id)
			VALUES('$SolitudReserva','".$this->evolucion."','".$this->ingreso."',NULL,'".$acto_qx_id."');";
			
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en Modulo banco_sangre_reserva_hc";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}				
		$dbconn->CommitTrans();
		return true;
  }

	/*function Insertar_Solicitud_Procedimiento($cargos){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if($_REQUEST['cirugia'.$pfj] == -1 OR $_REQUEST['ambito'.$pfj] == -1	OR $_REQUEST['finalidad'.$pfj] == -1)	{
			if($_REQUEST['cirugia'.$pfj] == -1){
				$this->frmError['cirugia']=1;
			}
			if($_REQUEST['ambito'.$pfj] == -1){
				$this->frmError['ambito']=1;
			}
			if($_REQUEST['finalidad'.$pfj] == -1){
				$this->frmError['finalidad']=1;
			}

			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			$this->Llenar_Procedimiento($_REQUEST['tipo'.$pfj], $_REQUEST['cargos'.$pfj], $_REQUEST['procedimiento'.$pfj]);
			return false;
		}
		$dbconn->BeginTrans();
		//realiza el id manual de la tabla
		$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
		$result=$dbconn->Execute($query1);
		$hc_os_solicitud_id=$result->fields[0];
		//fin de la operacion
		$query2="INSERT INTO hc_os_solicitudes
												(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id)
											VALUES  (
																".$hc_os_solicitud_id.", ".$this->evolucion.", '".$cargos."',
											'".ModuloGetVar('','','TipoSolicitudProcedimientos')."', ".$this->plan_id.");";
		$resulta=$dbconn->Execute($query2);
		if($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en hc_os_solicitudes";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
		}else{
			$query3="INSERT INTO hc_os_solicitudes_procedimientos
							(hc_os_solicitud_id, observacion, tipo_cirugia_id,
									ambito_cirugia_id, finalidad_procedimiento_id)
								VALUES  (".$hc_os_solicitud_id.", '".$_REQUEST['observacion'.$pfj]."',
									'".$_REQUEST['cirugia'.$pfj]."','".$_REQUEST['ambito'.$pfj]."',
									'".$_REQUEST['finalidad'.$pfj]."');";
			$resulta1=$dbconn->Execute($query3);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en hc_os_solicitudes_procedimientos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				if($_SESSION['DIAGNOSTICOS'.$pfj]){
					foreach ($_SESSION['DIAGNOSTICOS'.$pfj] as $k=>$v){
						$query4="INSERT INTO hc_os_solicitudes_diagnosticos
										(hc_os_solicitud_id, diagnostico_id)
											VALUES  (".$hc_os_solicitud_id.", '".$k."');";

						$resulta2=$dbconn->Execute($query4);
					  if($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}else{
							$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
						}
					}
				}
				if($_SESSION['APOYOS'.$pfj]){
					foreach($_SESSION['APOYOS'.$pfj] as $k=>$v){
						$query4="INSERT INTO hc_os_solicitudes_procedimientos_apoyos
										(hc_os_solicitud_id, cargo)
											VALUES  (".$hc_os_solicitud_id.", '".$k."');";
						$resulta2=$dbconn->Execute($query4);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en hc_os_solicitudes_procedimientos_apoyos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}else{
							$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
						}
					}
				}
				if($_REQUEST['fijo'.$pfj]){
					foreach ($_REQUEST['fijo'.$pfj] as $index=>$equipo){
						$arreglo=explode(",",$equipo);
						$query4="INSERT INTO hc_os_solicitudes_requerimientos_equipo_quirofano
													(hc_os_solicitud_id, tipo_equipo_fijo_id)
														VALUES  (".$hc_os_solicitud_id.", '".$arreglo[0]."');";

						$resulta2=$dbconn->Execute($query4);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en hc_os_solicitudes_requerimientos_equipo_quirofano";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$dbconn->RollbackTrans();
							return false;
						}else{
							$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
						}
					}
				}
				if($_REQUEST['movil'.$pfj]){
					foreach ($_REQUEST['movil'.$pfj] as $index=>$equipo){
						$arreglo=explode(",",$equipo);
						$query4="INSERT INTO hc_os_solicitudes_requerimientos_equipos_moviles
						(hc_os_solicitud_id, tipo_equipo_id)
						VALUES  (".$hc_os_solicitud_id.", '".$arreglo[0]."');";
						$resulta2=$dbconn->Execute($query4);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en hc_os_solicitudes_requerimientos_equipos_moviles";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}else{
							$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
						}
					}
				}
				$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
			}
		}
		$dbconn->CommitTrans();
		unset ($_SESSION['APOYOS'.$pfj]);
		$_REQUEST['cargos'.$pfj]= '';
		return true;
  }*/
//cor - clzc - spqx
	function Consulta_Modificar_Procedimiento($hc_os_solicitud_id){
		$pfj=$this->frmPrefijo;
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconnect) = GetDBconn();
		$query= "SELECT a.hc_os_solicitud_id, n.descripcion as tipo, a.cargo,b.observacion, 
		--b.tipo_cirugia_id, b.ambito_cirugia_id,b.finalidad_procedimiento_id, j.descripcion as cirugia, k.descripcion as ambito,l.descripcion as finalidad 
		c.diagnostico_id, d.tipo_equipo_fijo_id,e.tipo_equipo_id, g.diagnostico_nombre, h.descripcion as fijo,
		i.descripcion as movil,m.descripcion FROM hc_os_solicitudes a,
		hc_os_solicitudes_procedimientos b 
		--left join qx_tipos_cirugia j on (b.tipo_cirugia_id = j.tipo_cirugia_id) 
		left join hc_os_solicitudes_diagnosticos c on (b.hc_os_solicitud_id = c.hc_os_solicitud_id)  
		left join diagnosticos g on (c.diagnostico_id = g.diagnostico_id) 
		left join hc_os_solicitudes_requerimientos_equipo_quirofano d on (b.hc_os_solicitud_id = d.hc_os_solicitud_id) 
		left join qx_tipo_equipo_fijo h on (d.tipo_equipo_fijo_id = h.tipo_equipo_fijo_id) 
		left join hc_os_solicitudes_requerimientos_equipos_moviles e on (b.hc_os_solicitud_id = e.hc_os_solicitud_id) 
		left join qx_tipo_equipo_movil i on (e.tipo_equipo_id = i.tipo_equipo_id), 
		--qx_ambitos_cirugias k,qx_finalidades_procedimientos l, 
		cups m, grupos_tipos_cargo n
		WHERE a.hc_os_solicitud_id = b.hc_os_solicitud_id AND
		a.evolucion_id = ".$this->evolucion."  AND
		--b.ambito_cirugia_id = k.ambito_cirugia_id AND b.finalidad_procedimiento_id = l.finalidad_procedimiento_id
		AND a.cargo  = m.cargo  AND m.grupo_tipo_cargo = n.grupo_tipo_cargo AND a.hc_os_solicitud_id =  ".$hc_os_solicitud_id."";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconnect->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error en la consulta de la solicitud";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}else{
		  $i=0;
			while ($arr=$result->FetchRow()){
				$vector0[$arr['hc_os_solicitud_id']]=$arr;
				if(!empty($arr['diagnostico_id'])){
						$vector1[$arr['hc_os_solicitud_id']][$arr['diagnostico_id']]=$arr['diagnostico_nombre'];
				}
				$vector2[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_fijo_id']]=$arr['fijo'];
				$vector3[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_id']]=$arr['movil'];
			}
		}
		$vector[0]=$vector0;
		if(!empty($vector1)){
				$vector[1]=$vector1;
		}
		$vector[2]=$vector2;
		$vector[3]=$vector3;

		$result->Close();
		return $vector;
	}


//cor - clzc - spqx
	function EliminarDiagnosticoBD($hc_os_solicitud_id, $diagnostico_id){
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="DELETE FROM hc_os_solicitudes_diagnosticos
											WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."
											AND diagnostico_id = '".$diagnostico_id."'";

		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
				return false;
		}
		return true;
	}

	function niveles_Autorizacion(){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT nivel,descripcion
		FROM hc_os_solicitudes_niveles_autorizacion
		ORDER BY indice_de_orden";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
				$this->error = "Error al cargar los tipos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}else{
			while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vector;
	}



//cor  - clzc - spqx
	function Eliminar_Procedimiento_Solicitado($hc_os_solicitud_id){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query="DELETE FROM hc_os_solicitudes_requerimientos_equipos_moviles
										WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
				$dbconn->RollbackTrans();
				return false;
		}else{
			$query="DELETE FROM hc_os_solicitudes_requerimientos_equipo_quirofano
			WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
				$dbconn->RollbackTrans();
				return false;
			}else{
				$query="DELETE FROM hc_os_solicitudes_diagnosticos
				WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
					$dbconn->RollbackTrans();
					return false;
				}else{
					$query="DELETE FROM hc_os_solicitudes_procedimientos_apoyos
					WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
						$dbconn->RollbackTrans();
						return false;
					}else{
						$query="DELETE FROM hc_os_solicitudes_procedimientos
						WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
							$dbconn->RollbackTrans();
							return false;
						}else{
							$query="DELETE FROM hc_os_solicitudes
							WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
							$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
								$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}
				}
			}
		}
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="SOLICITUD ELIMINADA.";
		return true;
	}


//mirar para ver si ok
//cor - clzc - spqx
	function Modificar_Procedimiento($hc_os_solicitud_id){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();

		$observacion = $_REQUEST['observacion'.$pfj];
		/*if($_REQUEST['cirugia'.$pfj] == -1 OR $_REQUEST['ambito'.$pfj] == -1
			OR $_REQUEST['finalidad'.$pfj] == -1){

			if($_REQUEST['cirugia'.$pfj] == -1){
				$this->frmError['cirugia'.$pfj]=1;
			}
			if($_REQUEST['ambito'.$pfj] == -1){
				$this->frmError['ambito'.$pfj]=1;
			}
			if($_REQUEST['finalidad'.$pfj] == -1){
				$this->frmError['finalidad'.$pfj]=1;
			}
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			$this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_id'.$pfj]);
			return false;
		}*/

		$dbconn->BeginTrans();

		$query= "UPDATE hc_os_solicitudes_procedimientos
		SET observacion = '".$observacion."',
		--tipo_cirugia_id = '".$_REQUEST['cirugia'.$pfj]."',
		--ambito_cirugia_id = '".$_REQUEST['ambito'.$pfj]."',
		--finalidad_procedimiento_id = '".$_REQUEST['finalidad'.$pfj]."'
		WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al modificar";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
		}else{
			$query1="DELETE FROM hc_os_solicitudes_requerimientos_equipo_quirofano
			WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
			$resulta=$dbconn->Execute($query1);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al modificar";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				foreach ($_REQUEST['fijo'.$pfj] as $index=>$equipo){
					$arreglo=explode(",",$equipo);
					$query2="INSERT INTO hc_os_solicitudes_requerimientos_equipo_quirofano
				  (hc_os_solicitud_id, tipo_equipo_fijo_id)
					VALUES  (".$hc_os_solicitud_id.", '".$arreglo[0]."');";
					$resulta2=$dbconn->Execute($query2);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al modificar";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$query3="DELETE FROM hc_os_solicitudes_requerimientos_equipos_moviles
				WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
				$resulta=$dbconn->Execute($query3);
				if($dbconn->ErrorNo() != 0){
					$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
					$dbconn->RollbackTrans();
					return false;
				}else{
					foreach ($_REQUEST['movil'.$pfj] as $index=>$equipo){
						$arreglo=explode(",",$equipo);
						$query4="INSERT INTO hc_os_solicitudes_requerimientos_equipos_moviles
						(hc_os_solicitud_id, tipo_equipo_id)
					  VALUES  (".$hc_os_solicitud_id.", '".$arreglo[0]."');";
						$resulta2=$dbconn->Execute($query4);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al modificar";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
							return false;
						}else{
							$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
						}
					}
					$query5="DELETE FROM hc_os_solicitudes_procedimientos_apoyos
					WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
					$resulta=$dbconn->Execute($query5);
					if ($dbconn->ErrorNo() != 0){
						$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
						$dbconn->RollbackTrans();
						return false;
					}else{
					  if($_SESSION['APOYOS'.$pfj]){
							foreach ($_SESSION['APOYOS'.$pfj] as $k=>$v){
								$query6="INSERT INTO hc_os_solicitudes_procedimientos_apoyos
								(hc_os_solicitud_id, cargo)
								VALUES  (".$hc_os_solicitud_id.", '".$k."');";
								$resulta2=$dbconn->Execute($query6);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al insertar en hc_os_solicitudes_procedimientos_apoyos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
								}else{
								  $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
								}
							}
						}
					}
					/* foreach ($_SESSION['DIAGNOSTICOS'.$pfj] as $k=>$v)
					{
				$query5="INSERT INTO hc_os_solicitudes_diagnosticos
								(hc_os_solicitud_id, diagnostico_id)
									VALUES  ($hc_os_solicitud_id, '$k');";

				$resulta2=$dbconn->Execute($query5);
								if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al modificar";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															$dbconn->RollbackTrans();
								return false;
						}
				else
							{
									$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
							}
							}*/
				}
			}
		}
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		return true;
	}


//cor - clzc - spqx
	function tipos(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT a.tipo_cargo, a.descripcion
										FROM tipos_cargos a, qx_grupos_tipo_cargo b
										WHERE a.grupo_tipo_cargo = b.grupo_tipo_cargo ";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error al cargar los tipos";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}else{
			while (!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vector;
	}

//cor - clzc - spqx
	function tipocirugia(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT * FROM qx_tipos_cirugia";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error al buscar en la tabla qx_tipos_cirugia";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}else{
			while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vector;
	}

//cor - clzc - spqx
	function tipoambito(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT * FROM qx_ambitos_cirugias";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
				$this->error = "Error al buscar en la tabla qx_ambitos_cirugias";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}else{
			while (!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vector;
	}

//cor - clzc - spqx
	function tipofinalidad(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT * FROM qx_finalidades_procedimientos";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla qx_finalidades_procedimientos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vector;
	}

//cor - clzc - spqx
	function tipoequipofijo(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT * FROM qx_tipo_equipo_fijo order by tipo_equipo_fijo_id";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla qx_tipo_equipo_fijo";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			while (!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vector;
	}

//cor - clzc - spqx
	function tipoequipomovil(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT * FROM qx_tipo_equipo_movil order by tipo_equipo_id";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error al buscar en la tabla qx_tipo_equipo_movil";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}else{
			while (!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vector;
	}
//DE AQUI VA LO MIO
    //cor - clzc-jea - ads
	/*function Busqueda_Avanzada_Diagnosticos(){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$codigo       = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);
		$busqueda1 = '';
		$busqueda2 = '';
		if ($codigo != ''){
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}
		if (($diagnostico != '') AND ($codigo != ''))	{
			$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
		}

		if (($diagnostico != '') AND ($codigo == ''))	{
			$busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
		}

		if(empty($_REQUEST['conteo'.$pfj])){
			$query = "SELECT count(*)
			FROM diagnosticos
			$busqueda1 $busqueda2";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
			$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo){
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
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		while(!$resulta->EOF){
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
	}*/


	function Busqueda_Avanzada_ComponentesBanco(){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query = "SELECT hc_tipo_componente,componente FROM hc_tipos_componentes";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}else{
			while(!$resulta->EOF){
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		}
		$resulta->Close();
		return $var;
	}

//cor - clzc - ads
	function Insertar_Varios_Diagnosticos(){
		$pfj=$this->frmPrefijo;
		foreach($_REQUEST['opD'.$pfj] as $index=>$codigo){
			$arreglo=explode(",",$codigo);
		  $_SESSION['DIAGNOSTICOS'.$pfj][$arreglo[0]]=$arreglo[1];
			$i++;
		}
	}

     //funciones para los apoyos
     //cor - clzc-jea - ads
	function Busqueda_Avanzada_Apoyos(){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$opcion      = ($_REQUEST['criterio1'.$pfj]);
		$cargo       = ($_REQUEST['cargo'.$pfj]);
		$descripcion = STRTOUPPER($_REQUEST['descripcion'.$pfj]);
		$filtroTipoCargo = '';
		$busqueda1 = '';
		$busqueda2 = '';
     	if($opcion != '001' && !empty($opcion) && $opcion != '002'){
			$filtroTipoCargo=" AND a.grupo_tipo_cargo = '$opcion'";
		}
		if ($cargo != ''){
			$busqueda1 =" AND a.cargo LIKE '$cargo%'";
		}
		if ($descripcion != ''){
			$busqueda2 ="AND a.descripcion LIKE '%$descripcion%'";
		}
		if($opcion == '002'){
			$dpto = '';
			$espe = '';
			if($this->departamento != '' ){
				$dpto = "AND a.departamento = '".$this->departamento."'";
			}
			if ($this->especialidad != '' )	{
				$espe = "AND a.especialidad = '".$this->especialidad."'";
			}
			if ($dpto == '' AND $espe == ''){
				return false;
			}
		}
		if(empty($_REQUEST['conteo'.$pfj])){
			if($opcion == '002'){
                    $query= "SELECT count(*)
                             FROM apoyod_solicitud_frecuencia a, cups b,
                             hc_os_solicitudes_qx_grupos_cargos c,grupos_tipos_cargo d
                             WHERE a.cargo = b.cargo
                             AND b.sw_estado = '1'
                             AND b.grupo_tipo_cargo = c.grupo_tipo_cargo 
                             AND c.grupo_tipo_cargo=d.grupo_tipo_cargo
                             $dpto $espe $busqueda1 $busqueda2";
			}else{
				$query = "SELECT count(*)
						FROM cups a,hc_os_solicitudes_qx_grupos_cargos b,grupos_tipos_cargo d
						WHERE a.grupo_tipo_cargo = b.grupo_tipo_cargo 
                              AND b.grupo_tipo_cargo=d.grupo_tipo_cargo
                              AND a.sw_estado = '1'
                              $filtroTipoCargo $busqueda1 $busqueda2";
			}
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
			$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo){
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
		if($opcion == '002'){
		  $cont=0;
			$query= "SELECT DISTINCT a.cargo, b.descripcion, c.grupo_tipo_cargo as apoyod_tipo_id,
			d.descripcion as tipo";
			if(sizeof($_SESSION['SOLICITUD_APOYOS_QX'.$pfj])>0){
				$query.= ",(CASE WHEN (";
				if(sizeof($_SESSION['SOLICITUD_APOYOS_QX'.$pfj])>1){
					foreach($_SESSION['SOLICITUD_APOYOS_QX'.$pfj] as $codigo=>$vector){
						if($cont>0 && $cont<sizeof($_SESSION['SOLICITUD_APOYOS_QX'.$pfj])){
							$query.=" OR";
						}
						$cont++;
						$query.=" a.cargo = '".$codigo."'";
					}
				}else{
					foreach($_SESSION['SOLICITUD_APOYOS_QX'.$pfj] as $codigo=>$vector){
						$query.=" a.cargo = '".$codigo."' ";
					}
				}
				$query.=" ) THEN '1'
							ELSE '0'
				END) as ordenamiento";
			}
	  	$query.=" FROM apoyod_solicitud_frecuencia a, cups b,
			hc_os_solicitudes_qx_grupos_cargos c,grupos_tipos_cargo d
			WHERE a.cargo = b.cargo
               AND b.sw_estado = '1'
			AND b.grupo_tipo_cargo = c.grupo_tipo_cargo AND
			c.grupo_tipo_cargo = d.grupo_tipo_cargo
			$dpto $espe $busqueda1 $busqueda2
			order by";
			if(sizeof($_SESSION['SOLICITUD_APOYOS_QX'.$pfj])>0){
        $query.=" ordenamiento,";
			}
			$query.=" d.descripcion, a.cargo
			LIMIT ".$this->limit." OFFSET $Of;";
		}else{
			$query = "
			SELECT a.cargo, a.descripcion, b.grupo_tipo_cargo as apoyod_tipo_id,
			d.descripcion as tipo";
			if(sizeof($_SESSION['SOLICITUD_APOYOS_QX'.$pfj])>0){
				$query.=",(CASE WHEN (";
				if(sizeof($_SESSION['SOLICITUD_APOYOS_QX'.$pfj])>1){
					foreach($_SESSION['SOLICITUD_APOYOS_QX'.$pfj] as $codigo=>$vector){
						if($cont>0 && $cont<sizeof($_SESSION['SOLICITUD_APOYOS_QX'.$pfj])){
							$query.=" OR";
						}
						$cont++;
						$query.=" a.cargo = '".$codigo."'";
					}
				}else{
					foreach($_SESSION['SOLICITUD_APOYOS_QX'.$pfj] as $codigo=>$vector){
						$query.=" a.cargo = '".$codigo."' ";
					}
				}
				$query.=" ) THEN '1'
             ELSE '2'
        END) as ordenamiento";
			}
			$query.=" FROM cups a,hc_os_solicitudes_qx_grupos_cargos b,grupos_tipos_cargo d
			WHERE a.grupo_tipo_cargo = b.grupo_tipo_cargo 
               AND b.grupo_tipo_cargo = d.grupo_tipo_cargo
               AND a.sw_estado = '1'
			$filtroTipoCargo    $busqueda1 $busqueda2 order by ";
			if(sizeof($_SESSION['SOLICITUD_APOYOS_QX'.$pfj])>0){
        $query.=" ordenamiento,";
			}
			$query.=" a.descripcion,a.cargo,b.grupo_tipo_cargo
			LIMIT ".$this->limit." OFFSET $Of;";
		}
		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		while(!$resulta->EOF){
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
  }

	function Busqueda_Avanzada_Materiales(){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'.$pfj])){
			$query= "SELECT count(*)
			FROM inventarios_productos a,hc_os_solicitudes_grupos_inventarios b
			WHERE a.grupo_id = b.grupo_id AND a.estado='1'";
			if($_REQUEST['grupo'.$pfj]!=-1 && !empty($_REQUEST['grupo'.$pfj])){
			  $query.=" AND a.grupo_id = '".$_REQUEST['grupo'.$pfj]."'";
			}
			if(!empty($_REQUEST['codigoProducto'.$pfj])){
				$query.=" AND a.codigo_producto LIKE '".$_REQUEST['codigoProducto'.$pfj]."%'";
			}
			if (!empty($_REQUEST['descripcion'.$pfj])){
				$descripcion =STRTOUPPER($_REQUEST['descripcion'.$pfj]);
				$query.="AND a.descripcion LIKE '%$descripcion%'";
			}
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
			$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo){
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
		$query= "SELECT a.codigo_producto,a.descripcion,a.grupo_id,c.descripcion as desgrupo
		FROM inventarios_productos a,hc_os_solicitudes_grupos_inventarios b,inv_grupos_inventarios c
		WHERE a.grupo_id = b.grupo_id AND b.grupo_id=c.grupo_id AND a.estado='1'";
		if($_REQUEST['grupo'.$pfj]!=-1 && !empty($_REQUEST['grupo'.$pfj])){
			$query.=" AND a.grupo_id = '".$_REQUEST['grupo'.$pfj]."'";
		}
		if(!empty($_REQUEST['codigoProducto'.$pfj])){
			$query.=" AND a.codigo_producto LIKE '".$_REQUEST['codigoProducto'.$pfj]."%'";
		}
		if (!empty($_REQUEST['descripcion'.$pfj])){
			$descripcion =STRTOUPPER($_REQUEST['descripcion'.$pfj]);
			$query.="AND a.descripcion LIKE '%$descripcion%'";
		}
		$query.=" LIMIT ".$this->limit." OFFSET $Of";
		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		$i=0;
		while(!$resulta->EOF){
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
		}
		if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
  }

	function Busqueda_Avanzada_EstanciaQX(){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'.$pfj])){
			$query= "SELECT count(*)
			FROM tipos_clases_camas a";
			if (!empty($_REQUEST['descripcionEstancia'.$pfj])){
				$descripcion =STRTOUPPER($_REQUEST['descripcionEstancia'.$pfj]);
				$query.=" WHERE a.descripcion LIKE '%$descripcion%'";
			}
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
			$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo){
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
		$query= "SELECT a.tipo_clase_cama_id,a.descripcion";
    if(sizeof($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj])>0){
		  $query.= " ,(CASE WHEN ";
		  if(sizeof($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj])>1){
		    $cont=0;
        foreach($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj] as $codigo=>$vector){
			    if($cont>0 && $cont<sizeof($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj])){
            $query.=" OR";
				  }
				  $query.=" a.tipo_clase_cama_id='".$codigo."'";
          $cont++;
		    }
		  }else{
		    foreach($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj] as $codigo=>$vector){
          $query.=" a.tipo_clase_cama_id='".$codigo."'";
			  }
		  }
		  $query.=" THEN '1' ELSE '2' END) as ordenamiento ";
		}
    $query.= " FROM tipos_clases_camas a";
		if (!empty($_REQUEST['descripcionEstancia'.$pfj])){
			$descripcion =STRTOUPPER($_REQUEST['descripcionEstancia'.$pfj]);
			$query.=" WHERE a.descripcion LIKE '%$descripcion%'";
		}
		$query.=" ORDER BY ";
		if(sizeof($_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj])>0){
      //$query.=" ordenamiento,";
		}
		$query.=" a.descripcion";
		$query.=" LIMIT ".$this->limit." OFFSET $Of";
		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		$i=0;
		while(!$resulta->EOF){
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
		}
		if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
  }

  function QuirofanosTotal(){
    list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT a.quirofano,a.descripcion
		FROM qx_quirofanos a,qx_equipos_quirofanos b
		WHERE a.quirofano=b.quirofano_id";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($resulta->RecordCount()>0){
				while(!$resulta->EOF){
					$var[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
			}
		}
		return $var;
	}

	function Busqueda_Avanzada_EquiposQX(){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if($_REQUEST['Quirofano'.$pfj]!=-1 && !empty($_REQUEST['Quirofano'.$pfj])){
			$concat=" AND x.quirofano_id='".$_REQUEST['Quirofano'.$pfj]."'";
		}
		if($_REQUEST['descripcionEquipo'.$pfj]){
		  $concat3=" WHERE a.descripcion LIKE '%".$_REQUEST['descripcionEquipo'.$pfj]."%'";
      $concat2=" AND b.descripcion LIKE '%".$_REQUEST['descripcionEquipo'.$pfj]."%'";
		}
		if(empty($_REQUEST['conteo'.$pfj])){
			if($_REQUEST['tipoEquipo'.$pfj]!=-1 && !empty($_REQUEST['tipoEquipo'.$pfj])){
			  if($_REQUEST['tipoEquipo'.$pfj]=='M'){
          $query= "SELECT DISTINCT 'MOVIL' as tipo,a.tipo_equipo_id as tipoid,a.descripcion
				  FROM qx_tipo_equipo_movil a $concat3";
				}elseif($_REQUEST['tipoEquipo'.$pfj]=='F'){
          $query= "SELECT 'FIJO' as tipo,b.tipo_equipo_fijo_id as tipoid,b.descripcion
          FROM qx_tipo_equipo_fijo b,qx_equipos_quirofanos x
          WHERE
          b.tipo_equipo_fijo_id=x.tipo_equipo_fijo_id $concat $concat2";
				}
			}else{
        $query= "SELECT DISTINCT 'MOVIL' as tipo,a.tipo_equipo_id as tipoid,a.descripcion
				FROM qx_tipo_equipo_movil a $concat3
				UNION
				SELECT 'FIJO' as tipo,b.tipo_equipo_fijo_id as tipoid,b.descripcion
        FROM qx_tipo_equipo_fijo b,qx_equipos_quirofanos x
        WHERE
        b.tipo_equipo_fijo_id=x.tipo_equipo_fijo_id $concat $concat2";
			}
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->conteo=$resulta->RecordCount();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
			$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo){
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
		if($_REQUEST['tipoEquipo'.$pfj]!=-1 && !empty($_REQUEST['tipoEquipo'.$pfj])){
			if($_REQUEST['tipoEquipo'.$pfj]=='M'){
				$query= "SELECT DISTINCT 'MOVIL' as tipo,a.tipo_equipo_id as tipoid,a.descripcion
				FROM qx_tipo_equipo_movil a $concat3";
			}elseif($_REQUEST['tipoEquipo'.$pfj]=='F'){
				$query= "SELECT 'FIJO' as tipo,b.tipo_equipo_fijo_id as tipoid,b.descripcion
        FROM qx_tipo_equipo_fijo b,qx_equipos_quirofanos x
        WHERE
        b.tipo_equipo_fijo_id=x.tipo_equipo_fijo_id $concat $concat2";
			}
		}else{
			$query= "SELECT DISTINCT 'MOVIL' as tipo,a.tipo_equipo_id as tipoid,a.descripcion
			FROM qx_tipo_equipo_movil a $concat3
			UNION
			SELECT 'FIJO' as tipo,b.tipo_equipo_fijo_id as tipoid,b.descripcion
      FROM qx_tipo_equipo_fijo b,qx_equipos_quirofanos x
      WHERE
      b.tipo_equipo_fijo_id=x.tipo_equipo_fijo_id $concat $concat2";
		}
		$query.=" ORDER BY tipo,descripcion";
		$query.=" LIMIT ".$this->limit." OFFSET $Of";
		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		while(!$resulta->EOF){
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
		}
		if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
  }

//cor - clzc- ads
	function tipos_apoyos(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT apoyod_tipo_id, descripcion
										FROM apoyod_tipos";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla apoyod_tipos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  $i=0;
			while (!$result->EOF){
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		$result->Close();
		return $vector;
	}

	function GruposTiposCargos(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT a.grupo_tipo_cargo, b.descripcion
						FROM hc_os_solicitudes_qx_grupos_cargos a,grupos_tipos_cargo b
						WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla apoyod_tipos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  if($result->RecordCount()){
				while (!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		return $vector;
	}

//cor - clzc - ads
	function Insertar_Varias_Solicitudes(){
		$pfj=$this->frmPrefijo;
		foreach($_REQUEST['op'.$pfj] as $index=>$codigo){
			$arreglo=explode(",",$codigo);
		  $_SESSION['APOYOS'.$pfj][$arreglo[0]]=$arreglo[1];
		}
	}
//cor - clzc - spqx
	function Apoyos_Del_Procedimiento($hc_os_solicitud_id){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT a.cargo, b.descripcion FROM hc_os_solicitudes_procedimientos_apoyos as a, cups as b WHERE a.hc_os_solicitud_id = ".$hc_os_solicitud_id." AND a.cargo = b.cargo";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error al buscar en la tabla hc_os_solicitudes_procedimientos_apoyos";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}else{
		  $i=0;
			while (!$result->EOF){
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		$result->Close();
		return $vector;
	}

  function Apoyos_Prequirurgicos($cargo){

		$pfj=$this->frmPrefijo;
		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
		list($dbconnect) = GetDBconn();
		$query= "SELECT a.cargo, a.cargopreqx, b.descripcion, a.sexo_id, a.edad_min, a.edad_max from hc_apoyos_prequirurgicos as a, cups as b where a.cargo = '".$cargo."' AND a.cargopreqx = b.cargo AND (a.sexo_id='".$this->datosPaciente['sexo_id']."' OR a.sexo_id is null OR a.sexo_id = '0') AND (a.edad_max>=".$edad_paciente[anos]." OR a.edad_max is null) AND (a.edad_min<=".$edad_paciente[anos]." OR a.edad_min is null)";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  $i=0;
			while (!$result->EOF){
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		$result->Close();
	  return $vector;
  }

	function GrupoInventarios(){

		list($dbconnect) = GetDBconn();
    $query= "SELECT a.grupo_id,b.descripcion
		FROM hc_os_solicitudes_grupos_inventarios a,inv_grupos_inventarios b
		WHERE a.grupo_id=b.grupo_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
				$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}else{
			while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
	  return $vector;
	}

	function CargoXDefecto($cargo){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT hc.cargo_asociado,cp.descripcion,cp.grupo_tipo_cargo,hc.cantidad
		FROM hc_os_solicitudes_procedimientos_y_apoyos_asociados_cargos hc
    LEFT JOIN tipos_planes tp ON (hc.tipo_plan=tp.sw_tipo_plan)
		,cups cp
		WHERE hc.cargo='".$cargo."' AND  hc.cargo_asociado=cp.cargo AND
		(hc.plan_id='".$this->plan_id."' OR hc.plan_id IS NULL)";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_os_solicitudes_procedimientos_y_apoyos_asociados_cargos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
			  if(!in_array($result->fields[0],$_SESSION['SOLICITUD_APOYOS_QX'.$pfj])){
			    $_SESSION['SOLICITUD_APOYOS_QX'.$pfj][$result->fields[0]][$result->fields[1].'||//'.$result->fields[2]]=$result->fields[3];
				}
				$result->MoveNext();
			}
		}
		$result->Close();
	  return true;
	}

	function ApoyosDiagnosticosBanco($codigo,$descripcion){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.cargo,b.descripcion
		FROM hc_os_solicitudes_apoyos_banco_sangre a,cups b
		WHERE a.cargo=b.cargo ";
		if(sizeof($_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj]) > 0){
      foreach($_SESSION['SELECCION_APOYOS_BANCO_QX'.$pfj] as $codigoElegido=>$descripcionElegido){
			  $query.=" AND a.cargo!='".$codigoElegido."'";
			}
		}
		if($codigo){
      $query.= " AND a.cargo LIKE '$codigo%'";
		}
		if($descripcion){
      $query.= " AND b.descripcion LIKE '%".strtoupper($descripcion)."%'";
		}
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_os_solicitudes_apoyos_banco_sangre";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
	  return $vector;
	}

	function EstanciaXDefecto($cargo){
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT hc.tipo_clase_cama_id,a.descripcion,hc.sw_postqx,hc.dias
		FROM hc_os_solicitudes_cargos_asociados_tipos_camas hc,tipos_clases_camas a
		WHERE hc.cargo='$cargo' AND hc.tipo_clase_cama_id=a.tipo_clase_cama_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
			  if(!$_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$result->fields[0]]){
			    $_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$result->fields[0]][$result->fields[1]]=$result->fields[3];
					if($result->fields[2]=='1'){
            $_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$result->fields[0]]['POS']=1;
						$_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$result->fields[0]]['PRE']=0;
					}else{
            $_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$result->fields[0]]['PRE']=1;
						$_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$result->fields[0]]['POS']=0;
					}
				}else{
          if($result->fields[2]=='1'){
            $_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$result->fields[0]]['POS']=1;
					}else{
            $_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$result->fields[0]]['PRE']=1;
					}
				}
				$result->MoveNext();
			}
		}
		$result->Close();
	  return true;
	}

	function ConfirmarIgualEvolucion(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT acto_qx_id
		FROM hc_os_solicitudes_datos_acto_qx a,hc_evoluciones b
		WHERE a.evolucion_id='".$this->evolucion."' AND a.evolucion_id=b.evolucion_id AND 
		b.usuario_id='".UserGetUID()."'";

		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
      if($result->RecordCount()>0){
				$vector=$result->GetRowAssoc($ToUpper = false);
        $_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']=$vector['acto_qx_id'];				
				return 1;
			}
		}
		return 0;
	}

	function TraerVariablesdeSession(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT 
		a.nivel_autorizacion,a.fecha_tentativa_cirugia
		FROM hc_os_solicitudes_datos_acto_qx a
		WHERE a.acto_qx_id='".$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']."'";
		
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vectorUno=$result->GetRowAssoc($ToUpper = false);
					//$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['OBSERVACIONES']=$vectorUno['observacion'];
					//$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['CIRUGIA']=$vectorUno['tipo_cirugia_id'];
					//$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['AMBITO']=$vectorUno['ambito_cirugia_id'];
					//$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FINALIDAD']=$vectorUno['finalidad_procedimiento_id'];
					$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['NIVEL']=$vectorUno['nivel_autorizacion'];
					if($vectorUno['fecha_tentativa_cirugia']){
						(list($fecha,$horas)=explode(' ',$vectorUno['fecha_tentativa_cirugia']));
						(list($ano,$mes,$dia)=explode('-',$fecha));
						(list($hora,$minutos)=explode(':',$horas));					
						$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['FECHA']=$dia.'/'.$mes.'/'.$ano;					
						$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['HORA']=$hora;
						$_SESSION['SOLICITUD_QX'.$pfj]['DATOS']['MINUTOS']=$minutos;
					}
          $_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']=$vectorUno['sw_ambulatorio'];
					$result->MoveNext();
				}
			}
		}
		$query= "SELECT a.hc_os_solicitud_id,a.observacion,b.cargo,c.descripcion,d.descripcion as tipo,b.cantidad,b.sw_ambulatorio,
		(CASE WHEN c.grupo_tipo_cargo IN (SELECT grupo_tipo_cargo FROM qx_grupos_tipo_cargo) THEN 'PROC' ELSE 'APOY' END) as procedimiento 
		FROM hc_os_solicitudes_acto_qx a,hc_os_solicitudes b,cups c,tipos_cargos d
		WHERE a.acto_qx_id='".$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']."' AND 
		a.hc_os_solicitud_id=b.hc_os_solicitud_id AND b.cargo=c.cargo AND 
		c.tipo_cargo=d.tipo_cargo AND c.grupo_tipo_cargo=d.grupo_tipo_cargo";
		
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				$i=0;
				while(!$result->EOF){
					$vector1[]=$result->GetRowAssoc($ToUpper = false);					
					$result->MoveNext();					
				}
			}
		}
		
		for($i=0;$i<sizeof($vector1);$i++){
			if($vector1[$i]['sw_ambulatorio']){
				$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='1';
			}else{
				$_SESSION['SOLICITUD_QX'.$pfj]['SOLICITUD_AMBULATORIA']='0';
			}				
			if($vector1[$i]['procedimiento']=='PROC'){
				$_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj][$vector1[$i]['cargo']]['tipo']=$vector1[$i]['tipo'];
				$_SESSION['SOLICITUD_PROCEDIMIENTOS_QX'.$pfj][$vector1[$i]['cargo']]['descripcion']=$vector1[$i]['descripcion'];				
			}else{
				$_SESSION['SOLICITUD_APOYOS_QX'.$pfj][$vector1[$i]['cargo']][$vector1[$i]['descripcion'].'||//'.$vector1[$i]['tipo']]=$vector1[$i]['cantidad'];
			}
			$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['OBSERVACIONES'][$vector1[$i]['cargo']]=$vector1[$i]['observacion'];
			$query= "SELECT b.diagnostico_id,c.diagnostico_nombre,b.tipo_diagnostico,b.sw_principal
			FROM hc_os_solicitudes a,hc_os_solicitudes_diagnosticos b,diagnosticos c
			WHERE a.hc_os_solicitud_id='".$vector1[$i]['hc_os_solicitud_id']."' AND a.cargo='".$vector1[$i]['cargo']."' AND 
			a.hc_os_solicitud_id=b.hc_os_solicitud_id AND b.diagnostico_id=c.diagnostico_id";
			
			$result = $dbconnect->Execute($query);
			if($dbconnect->ErrorNo() != 0){
				$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}else{
				if($result->RecordCount()>0){		
					$vect='';			
					while(!$result->EOF){
						$vect[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();					
					}				
					for($j=0;$j<sizeof($vect);$j++){
						$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICOS'][$vector1[$i]['cargo']][$vect[$j]['diagnostico_id']][$vect[$j]['tipo_diagnostico']]=$vect[$j]['diagnostico_nombre'];	
						if($vect[$j]['sw_principal']=='1'){
							$_SESSION['SOLICITUD_QX_DIAGNOSTICOS'.$pfj]['DIAGNOSTICO_PRINCIPAL'][$vector1[$i]['cargo']]=$vect[$j]['diagnostico_id'];
						}
					}
				}	
			}						
		}	
		$query= "SELECT a.codigo_producto,b.descripcion,a.cantidad
		FROM hc_os_solicitudes_otros_productos_inv a,inventarios_productos b
		WHERE a.acto_qx_id='".$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']."' AND
		a.codigo_producto=b.codigo_producto";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				$i=0;
				while(!$result->EOF){
					$vector5[]=$result->GetRowAssoc($ToUpper = false);
					$vectorM=array($vector5[$i]['descripcion']=>$vector5[$i]['cantidad']);
					$_SESSION['SOLICITUD_MATERALES_QX'.$pfj][$vector5[$i]['codigo_producto']]=$vectorM;
					$result->MoveNext();
					$i++;
				}
			}
		}
		$query= "SELECT 'FIJO' as tipo,a.tipo_equipo_fijo_id as codigo,b.descripcion,a.cantidad
		FROM hc_os_solicitudes_requerimientos_equipo_quirofano a,qx_tipo_equipo_fijo b
		WHERE a.acto_qx_id='".$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']."' AND
		a.tipo_equipo_fijo_id=b.tipo_equipo_fijo_id
		UNION
		SELECT 'MOVIL' as tipo,a.tipo_equipo_id as codigo,b.descripcion,a.cantidad
		FROM hc_os_solicitudes_requerimientos_equipos_moviles a,qx_tipo_equipo_movil b
		WHERE a.acto_qx_id='".$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']."' AND
		a.tipo_equipo_id=b.tipo_equipo_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				$i=0;
				while(!$result->EOF){
					$vector6[]=$result->GetRowAssoc($ToUpper = false);
					$vectEqui=array($vector6[$i]['descripcion']=>$vector6[$i]['cantidad']);
					$_SESSION['SOLICITUD_EQUIPOS_QX'.$pfj][$vector6[$i]['codigo'].'||//'.$vector6[$i]['tipo']]=$vectEqui;
					$result->MoveNext();
					$i++;
				}
			}
		}
		$query= "SELECT a.tipo_clase_cama_id,b.descripcion,a.sw_pre_qx,a.sw_pos_qx
		FROM hc_os_solicitudes_estancia a,tipos_clases_camas b
		WHERE a.acto_qx_id='".$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']."' AND
		a.tipo_clase_cama_id=b.tipo_clase_cama_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				$i=0;
				while(!$result->EOF){
					$vector7[]=$result->GetRowAssoc($ToUpper = false);
					$vectEstancia=array('PRE'=>$vector7[$i]['sw_pre_qx'],'POS'=>$vector7[$i]['sw_pos_qx']);
					$_SESSION['SOLICITUD_ESTANCIA_QX'.$pfj][$vector7[$i]['tipo_clase_cama_id']]=$vectEstancia;
					$result->MoveNext();
					$i++;
				}
			}
		}
		$query= "SELECT b.tipo_componente_id,b.cantidad_componente,c.componente
		FROM banco_sangre_reserva_hc a,banco_sangre_reserva_detalle b,hc_tipos_componentes c
		WHERE a.acto_qx_id='".$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']."' AND
		a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND b.sw_estado='1' AND
		b.tipo_componente_id=c.hc_tipo_componente";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				$i=0;
				while(!$result->EOF){
					$vector8[]=$result->GetRowAssoc($ToUpper = false);
					$_SESSION['SOLICITUD_RESERVA_SANGRE_QX'.$pfj][$vector8[$i]['tipo_componente_id'].'||//'.$vector8[$i]['componente']]=$vector8[$i]['cantidad_componente'];
					$result->MoveNext();
					$i++;
				}
			}
		}
		$query= "SELECT b.cargo,c.descripcion
		FROM banco_sangre_reserva_hc a,banco_sangre_reserva_otros_servicios b,cups c
		WHERE a.acto_qx_id='".$_SESSION['SOLICITUD_QX'.$pfj]['INSERTADA']."' AND
		a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND
		b.cargo=c.cargo";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla hc_apoyos_prequirurgicos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				$i=0;
				while(!$result->EOF){
					$vector9[]=$result->GetRowAssoc($ToUpper = false);
					$_SESSION['SOLICITUD_RESERVA_SANGRE_QX_APOYOS'.$pfj][$vector9[$i]['cargo']]=$vector9[$i]['descripcion'];
					$result->MoveNext();
					$i++;
				}
			}
		}
		return true;
	}

	function SolicitudesQXPaciente(){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.acto_qx_id,b.hc_os_solicitud_id,date(d.fecha) as fecha,
		c.cargo,e.ingreso,d.evolucion_id,c.sw_estado,c.sw_ambulatorio,
		(CASE WHEN d.usuario_id='".UserGetUID()."' THEN '1' ELSE '0' END) as propio,
		f.descripcion,b.observacion,ter.nombre_tercero,
		(CASE WHEN f.grupo_tipo_cargo IN (SELECT grupo_tipo_cargo FROM qx_grupos_tipo_cargo) THEN 'A' ELSE 'B' END) as tipo_proc,
		c.sw_ambulatorio,h.descripcion as tipo  
		
		FROM hc_os_solicitudes_datos_acto_qx a,hc_os_solicitudes_acto_qx b,hc_os_solicitudes c,
		hc_evoluciones d,ingresos e,cups f,profesionales_usuarios g,terceros ter,tipos_cargos h
		WHERE a.acto_qx_id=b.acto_qx_id AND b.hc_os_solicitud_id=c.hc_os_solicitud_id AND c.sw_estado<>'2' AND 
		c.evolucion_id=d.evolucion_id AND d.ingreso=e.ingreso AND e.tipo_id_paciente='".$this->tipoidpaciente."' AND 
		e.paciente_id='".$this->paciente."' AND d.evolucion_id <> '".$this->evolucion."' AND
    e.ingreso='".$this->ingreso."' AND c.cargo=f.cargo AND d.usuario_id=g.usuario_id AND g.tipo_tercero_id=ter.tipo_id_tercero AND g.tercero_id=ter.tercero_id AND 
		h.tipo_cargo=f.tipo_cargo AND h.grupo_tipo_cargo=f.grupo_tipo_cargo
		ORDER BY tipo_proc,d.fecha DESC";
    $result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
		}
		return $vector;
	}

  function SolicitudesQXPacientefrmConsulta(){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.hc_os_solicitud_id,date(b.fecha) as fecha,e.nombre_tercero,a.cargo,f.descripcion,g.descripcion as tipo,b.ingreso,a.evolucion_id,a.sw_programado,a.sw_estado,a.sw_ambulatorio
		FROM hc_os_solicitudes a,hc_evoluciones b,ingresos c,profesionales_usuarios d,terceros e,cups f,grupos_tipos_cargo g
		WHERE a.evolucion_id=b.evolucion_id AND a.sw_estado<>'2' AND b.ingreso=c.ingreso AND
		c.tipo_id_paciente='".$this->tipoidpaciente."' AND c.paciente_id='".$this->paciente."' AND b.ingreso = '".$this->ingreso."' AND b.usuario_id=d.usuario_id AND
		d.tipo_tercero_id=e.tipo_id_tercero AND d.tercero_id=e.tercero_id AND a.cargo=f.cargo AND
		a.os_tipo_solicitud_id='".ModuloGetVar('','','TipoSolicitudProcedimientos')."' AND f.grupo_tipo_cargo=g.grupo_tipo_cargo ORDER BY b.fecha DESC";
    $result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
		}
		return $vector;
	}

	function OtrosProcedimientosSolicitud($SolicitudId){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.procedimiento_id,b.descripcion,a.autorizacion as sw_estado,c.observacion
		FROM hc_os_solicitudes_otros_procedimientos_qx a,cups b,hc_os_solicitudes_datos_acto_qx c
		WHERE a.hc_os_solicitud_id='".$SolicitudId."' AND a.procedimiento_id=b.cargo AND
    a.hc_os_solicitud_id=c.hc_os_solicitud_id";

		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
		}
		return $vector;
	}

	function ObservacionesSolicitud($SolicitudId){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.observacion_id,a.observacion,a.fecha,a.usuario_id,a.fecha_ultima_modificacion,(CASE WHEN a.usuario_id='".UserGetUID()."' THEN 1 ELSE 2 END) as propio,
    c.nombre_tercero
		FROM hc_os_solicitudes_observaciones a
		LEFT JOIN profesionales_usuarios b ON(a.usuario_id=b.usuario_id)
		LEFT JOIN terceros c ON(b.tipo_tercero_id=c.tipo_id_tercero AND b.tercero_id=c.tercero_id)
		WHERE acto_qx_id='".$SolicitudId."' ORDER BY a.fecha_ultima_modificacion DESC";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
		}
		return $vector;
	}

	function InsertarDatosObservacionesSolicitud(){
    $pfj=$this->frmPrefijo;
		if(!empty($_REQUEST['observaciones'.$pfj])){
			list($dbconnect) = GetDBconn();
			$query= "INSERT INTO hc_os_solicitudes_observaciones(acto_qx_id,observacion,fecha,usuario_id,fecha_ultima_modificacion)
			VALUES('".$_REQUEST['SolicituId'.$pfj]."','".$_REQUEST['observaciones'.$pfj]."','".date("Y-m-d H:i:s")."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
			$result = $dbconnect->Execute($query);
			if($dbconnect->ErrorNo() != 0){
				$this->error = "Error al buscar en la tabla";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
		}
		return true;
	}

	function ModificarDatosObservacionesSolicitud(){
    $pfj=$this->frmPrefijo;
		if(!empty($_REQUEST['observaciones'.$pfj])){
			list($dbconnect) = GetDBconn();
			$query= "UPDATE hc_os_solicitudes_observaciones
			SET observacion='".$_REQUEST['observaciones'.$pfj]."',fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
			WHERE acto_qx_id='".$_REQUEST['SolicituId'.$pfj]."' AND observacion_id='".$_REQUEST['observacionId'.$pfj]."'";
			$result = $dbconnect->Execute($query);
			if($dbconnect->ErrorNo() != 0){
				$this->error = "Error al buscar en la tabla";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
		}
		return true;
	}

	function EliminarObservacionSolicitud(){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "DELETE FROM hc_os_solicitudes_observaciones WHERE observacion_id='".$_REQUEST['observacionId'.$pfj]."'";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		return true;
	}

	function TotalObservacionesSolicitud($SolicitudId){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT count(*) FROM hc_os_solicitudes_observaciones WHERE acto_qx_id='".$SolicitudId."'";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			return $result->fields[0];
		}
		return 0;
	}

	function ProcedimientosAutorizados($SolicitudId){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.procedimiento_id,c.descripcion,b.observaciones,b.usuario_id,d.nombre as usuario,b.fecha_registro,b.sw_estado,a.autorizacion
		FROM hc_os_solicitudes z,hc_os_solicitudes_otros_procedimientos_qx a,autorizaciones_qx b,cups c,system_usuarios d
		WHERE z.hc_os_solicitud_id='$SolicitudId' AND (z.sw_estado='0' OR z.sw_estado='3') AND z.hc_os_solicitud_id=a.hc_os_solicitud_id AND a.autorizacion IS NOT NULL AND
		a.autorizacion=b.autorizacion AND a.procedimiento_id=c.cargo AND b.usuario_id=d.usuario_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  if($result->RecordCount()>0){
        while(!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();
			}
		}
		return $vector;
	}

	function ProcedimientosApoyosAutorizados($SolicitudId){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.cargo,c.descripcion,a.cantidad,e.descripcion as tipocargo,b.observaciones,b.usuario_id,d.nombre as usuario,b.fecha_registro,b.sw_estado,a.autorizacion
		FROM hc_os_solicitudes z,hc_os_solicitudes_procedimientos_apoyos a,autorizaciones_qx b,cups c,system_usuarios d,grupos_tipos_cargo e
		WHERE z.hc_os_solicitud_id='$SolicitudId' AND (z.sw_estado='0' OR z.sw_estado='3') AND z.hc_os_solicitud_id=a.hc_os_solicitud_id AND a.autorizacion IS NOT NULL AND
		a.autorizacion=b.autorizacion AND a.cargo=c.cargo AND b.usuario_id=d.usuario_id AND c.grupo_tipo_cargo=e.grupo_tipo_cargo";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  if($result->RecordCount()>0){
        while(!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();
			}
		}
		return $vector;
	}

  function MaterialesAutorizados($SolicitudId){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.codigo_producto,c.descripcion,a.cantidad,b.observaciones,b.usuario_id,d.nombre as usuario,b.fecha_registro,b.sw_estado,a.autorizacion
		FROM hc_os_solicitudes z,hc_os_solicitudes_otros_productos_inv a,autorizaciones_qx b,inventarios_productos c,system_usuarios d
		WHERE z.hc_os_solicitud_id='$SolicitudId' AND (z.sw_estado='0' OR z.sw_estado='3') AND z.hc_os_solicitud_id=a.hc_os_solicitud_id AND a.autorizacion IS NOT NULL AND
		a.autorizacion=b.autorizacion AND a.codigo_producto=c.codigo_producto AND b.usuario_id=d.usuario_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  if($result->RecordCount()>0){
        while(!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();
			}
		}
		return $vector;
	}

	function EstanciaAutorizada($SolicitudId){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.tipo_clase_cama_id,c.descripcion as clasecama,a.cantidad_dias,a.sw_pre_qx,a.sw_pos_qx,b.observaciones,b.usuario_id,d.nombre as usuario,b.fecha_registro,b.sw_estado,a.autorizacion,e.descripcion as tipocama
		FROM hc_os_solicitudes z,hc_os_solicitudes_estancia a,autorizaciones_qx b,tipos_clases_camas c,system_usuarios d,tipos_camas e
		WHERE z.hc_os_solicitud_id='$SolicitudId' AND (z.sw_estado='0' OR z.sw_estado='3') AND z.hc_os_solicitud_id=a.hc_os_solicitud_id AND a.autorizacion IS NOT NULL AND
		a.autorizacion=b.autorizacion AND a.tipo_clase_cama_id=c.tipo_clase_cama_id AND b.usuario_id=d.usuario_id AND a.tipo_cama_id=e.tipo_cama_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  if($result->RecordCount()>0){
        while(!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();
			}
		}
		return $vector;
	}

	function DatosSolicitudQX($SolicitudId){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT a.observacion,
		--b.descripcion as finalidad,c.descripcion as ambito,d.descripcion as tipocirugia,
		a.fecha_tentativa_cirugia,e.descripcion as nivel
		FROM hc_os_solicitudes z,hc_os_solicitudes_datos_acto_qx a
		--LEFT JOIN qx_finalidades_procedimientos b ON(a.finalidad_procedimiento_id=b.finalidad_procedimiento_id)
		--LEFT JOIN qx_ambitos_cirugias c ON(a.ambito_cirugia_id=c.ambito_cirugia_id)
		--LEFT JOIN qx_tipos_cirugia d ON(a.tipo_cirugia_id=d.tipo_cirugia_id)
		LEFT JOIN hc_os_solicitudes_niveles_autorizacion e ON(a.nivel_autorizacion=e.nivel)
		WHERE z.hc_os_solicitud_id='$SolicitudId' AND (z.sw_estado='0' OR z.sw_estado='3') AND z.hc_os_solicitud_id=a.hc_os_solicitud_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  if($result->RecordCount()>0){
				$vector=$result->GetRowAssoc($ToUpper = false);
			}
		}
		return $vector;
	}

	function DiagnosticosSolicitudQX($SolicitudId){
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
    $query= "SELECT b.diagnostico_id,b.diagnostico_nombre,a.tipo_diagnostico,a.sw_principal
		FROM hc_os_solicitudes z,hc_os_solicitudes_diagnosticos a,diagnosticos b
		WHERE z.hc_os_solicitud_id='$SolicitudId' AND z.hc_os_solicitud_id=a.hc_os_solicitud_id AND
		a.diagnostico_id=b.diagnostico_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  if($result->RecordCount()>0){
			  while(!$result->EOF){
				  $vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $vector;
	}

}
?>






