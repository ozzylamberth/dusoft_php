<?php
/**
* Submodulo de Evaluacion Preanestesica
*
* @author Tizziano Perea
* @version 1.0
* @package SIIS
* $Id: hc_EvaluacionPreanestesica.php,v 1.2 2007/11/13 21:29:49 tizziano Exp $
*/



IncludeClass("Preanestesia", null, "hc", "EvaluacionPreanestesica");
IncludeClass("Preanestesia_HTML", "html", "hc", "EvaluacionPreanestesica");

class EvaluacionPreanestesica extends hc_classModules
{
     
     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
     function EvaluacionPreanestesica()
	{
		$this->limit=GetLimitBrowser();
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
		'fecha'=>'19/09/2007',
		'autor'=>'TIZZIANO PEREA O',
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
		SessionSetVar("Ingreso",$this->ingreso);
		$Preanestesia_html = new Preanestesia_HTML();
		return $Preanestesia_html->frmConsulta();
	}
     
	/**
	* Esta metodo captura los datos de la impresión de la Historia Clinica.
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetReporte_Html()
	{
		SessionSetVar("Ingreso",$this->ingreso);
		$Preanestesia_html = new Preanestesia_HTML();
		return $Preanestesia_html->frmHistoria();
	}

	/**
	* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
	*
	* @access private
	* @return boolean True si fue utilizado el submodulo en la atencion.
	*/
	function GetEstado()
	{
          return true;
	}
	
	/**
	* Esta función instancia a las clases pertinentes para la construccion de la vista HTML.
	*
	* @access private
	* @return boolean True.
	*/
	function GetForma()
	{
		$evaluacion = new Preanestesia();
		$evaluacion_HTML = new Preanestesia_HTML();
		
		SessionSetVar("Ingreso",$this->ingreso);
		SessionSetVar("Evolucion",$this->evolucion);
		SessionSetVar("Paso",$this->paso);
		SessionSetVar("DatosPaciente",$this->datosPaciente);
		SessionSetVar("Usuario", UserGetUID());
		
          SessionSetVar("tipoidpaciente",$this->tipoidpaciente);
		SessionSetVar("paciente",$this->paciente);
		
		SessionSetVar("RutaImg",GetThemePath());
		SessionSetVar("Limite",GetLimitBrowser());
          
          $E_Ingreso = $evaluacion->Get_IngresoEvaluacion();
          if($E_Ingreso == 0)
          {
          	$evaluacion->Put_IngresoEvaluacion();
          }
          
          if($_REQUEST['accion_EvaluacionPre'] == 'InsertarDatos')
          {
               // Especialidad
               $Caracteristicas['Especialidad'] = $_REQUEST['especialidad'];
               // Signos Vitales
               $Signos['Peso']     = $_REQUEST['sig_peso'];
               $Signos['Estatura'] = $_REQUEST['sig_estatura'];
               $Signos['TA']       = $_REQUEST['sig_ta'];
               $Signos['TB']       = $_REQUEST['sig_tb'];
               $Signos['FC']       = $_REQUEST['sig_fc'];
               $Signos['FR']       = $_REQUEST['sig_fr'];
               $Signos['Temp']     = $_REQUEST['sig_temp'];
               // Evaluacion Cardiovascular
               $CardioDetalle      = $_REQUEST['e_cardio'];
               $CardioDescripcion = $_REQUEST['desc_cardio'];
               // Evaluacion Respiratorio
               $RespDetalle     = $_REQUEST['e_respiratorio'];
               $RespDescripcion = $_REQUEST['desc_respiratorio'];
               // Evaluacion Metabolico 
               $MetabDetalle     = $_REQUEST['e_metabolico'];
               $MetabDescripcion = $_REQUEST['desc_metabolico'];
               // Evaluacion Gastrointestinal
               $GastroDetalle     = $_REQUEST['e_gastro'];
               $GastroDescripcion = $_REQUEST['desc_gastro'];
               // Evaluacion Renal
               $RenalDetalle     = $_REQUEST['e_renal'];
               $RenalDescripcion = $_REQUEST['desc_renal'];
               // Evaluacion Neurologica
               $NeuroDetalle     = $_REQUEST['e_neuro'];
               $NeuroDescripcion = $_REQUEST['desc_neuro'];
               // Evaluacion Musculo Esqueletico
               $MusculoDetalle     = $_REQUEST['e_esqueletico'];
               $MusculoDescripcion = $_REQUEST['desc_esqueletico'];
               // Evaluacion Hematologica
			$HemaDetalle     = $_REQUEST['e_hematologico'];
               $HemaDescripcion = $_REQUEST['desc_hematologico'];
               // Evaluacion Hepatico
			$HepaDetalle     = $_REQUEST['e_hepatico'];
               $HepaDescripcion = $_REQUEST['desc_hepatico'];
               // Evaluacion Gineco-Obstetrica
			$GinecoDetalle     = $_REQUEST['e_gineco'];
               $GinecoDescripcion = $_REQUEST['desc_gineco'];
			// Evaluacion Intubacion
			$IntuDetalle     = $_REQUEST['e_intubacion'];
               $IntuDescripcion = $_REQUEST['desc_intubacion'];
               // Evaluacion Intubacion
			$OtrosDetalle     = $_REQUEST['e_otros'];
               $OtrosDescripcion = $_REQUEST['desc_otros'];
               // Anestesias Previas
               $Caracteristicas['AnevPrevia'] = $_REQUEST['desc_prevanestesia'];
               // Alergias
               $Caracteristicas['Alergias']   = $_REQUEST['desc_alergias'];
               // Drogas
               $Caracteristicas['Drogas']     = $_REQUEST['desc_drogas'];
               // Ayudas Dx
               $Caracteristicas['HelpDx']     = $_REQUEST['desc_ayudasdx'];
               // ASA
               $Caracteristicas['ASA']        = $_REQUEST['asa'];
               // Indice Trauma
               $Caracteristicas['IT']         = $_REQUEST['indice_t'];
               // Reserva
               $Caracteristicas['Reserva']    = $_REQUEST['reserva'];
               // Plan Anestesico
               $Caracteristicas['PlanAnes']   = $_REQUEST['plan_anes'];
               // Premedicacion
               $Caracteristicas['Premed']     = $_REQUEST['premedicacion'];
               
               $evaluacion->InsertarDatos_EvaluacionPreanestesica($Caracteristicas, $Signos, $CardioDetalle, $CardioDescripcion, $RespDetalle, $RespDescripcion, $MetabDetalle, $MetabDescripcion, $GastroDetalle, $GastroDescripcion, $RenalDetalle, $RenalDescripcion,
               										 $NeuroDetalle, $NeuroDescripcion, $MusculoDetalle, $MusculoDescripcion, $HemaDetalle, $HemaDescripcion, $HepaDetalle, $HepaDescripcion, $GinecoDetalle, $GinecoDescripcion, $IntuDetalle, $IntuDescripcion, $OtrosDetalle, $OtrosDescripcion);
               
               $this->RegistrarSubmodulo($this->GetVersion());
          }
		
		$E_Cardio = $evaluacion->Get_EvaluadoresCardiovasculares();

		$E_Respiratorio = $evaluacion->Get_EvaluadoresRespiratorios();
          
		$E_Metabolicos = $evaluacion->Get_EvaluadoresMetabolicos();
                    
          $E_Gastro = $evaluacion->Get_EvaluadoresGastrointestinal();
          
          $E_Renal = $evaluacion->Get_EvaluadoresRenal();
          
          $E_Neuro = $evaluacion->Get_EvaluadoresNeurologicos();
          
          $E_Esqueletico = $evaluacion->Get_EvaluadoresEsqueleticos();
          
          $E_Hematologica = $evaluacion->Get_EvaluadoresHematologicos();
          
          $E_Hepatico = $evaluacion->Get_EvaluadoresHepatico();
          
          $E_Gineco = $evaluacion->Get_EvaluadoresGinecos();

          $E_Intubacion = $evaluacion->Get_EvaluadoresIntubacion();
          
          $E_OtrosF = $evaluacion->Get_EvaluadoresOtrosFactores();
          
          //Diagnosticos
          include_once 'hc_modules/EvaluacionPreanestesica/RemoteXajax/EvaluacionPreanestesica_Xajax.php';
         
          $objClassModules=new hc_Classmodules(); 
          $objClassModules->SetXajax(array("BusquedaDX","BusquedaCups","VectorDX","VectorCups","OcultarDXAsignados","RegistrarVar"));
          
          
          $VectorI = $evaluacion->GetRegistroDiagnosticosI();
          if(empty($VectorI)){ $enlace = "1"; }else{ $enlace = "0"; }
           
           
           /*= $Diagnosticos;
          $enlace = "0";
          
          if(empty($Diagnosticos))
          {
			$diag = $obj_certificado->ConsultaDiagnosticoI();
               $VectorI = $diag;
               $enlace = "1";
               $_SESSION['diag_REGI'] = $diag;
               SessionSetVar('VectorDXEvoI', $diag);
          }*/

          $VectorCups = $evaluacion->GetRegistroCargosCirugia();
          if(empty($VectorCups)){ $enlace1 = "1"; }else{ $enlace1 = "0"; }

          /*$VectorE = $DiagnosticosE;
          $enlace1 = "0";
          
          if(empty($DiagnosticosE))
          {
			$diag = $obj_certificado->ConsultaDiagnosticoE();
               $VectorE = $diag;
               $enlace1 = "1";
               $_SESSION['diag_REGE'] = $diag;
               SessionSetVar('VectorDXEvoE', $diag);
          }*/
		
          $CaracteristicasDatos = $evaluacion->GetDatos_DescripcionCaracteristicas();
          
          $SignosDatos = $evaluacion->GetDatos_SignosVitales();
          
          $cardioDatos = $evaluacion->GetDatos_EvaluacionCardio();
          
          $RespDatos = $evaluacion->GetDatos_EvaluacionRespiratorio();
          
          $MetabDatos = $evaluacion->GetDatos_EvaluacionMetabolica();
          
          $GastroDatos = $evaluacion->GetDatos_EvaluacionGastroIntestinal();
          
          $RenalDatos = $evaluacion->GetDatos_EvaluacionRenal();
          
          $NeuroDatos = $evaluacion->GetDatos_EvaluacionNeurologica();
          
          $EsqueDatos = $evaluacion->GetDatos_EvaluacionEsqueletico();
          
          $HemaDatos = $evaluacion->GetDatos_EvaluacionHematologico();
          
          $HepaDatos = $evaluacion->GetDatos_EvaluacionHepatico();
          
          $GinecoDatos = $evaluacion->GetDatos_EvaluacionGineco();
          
          $IntuDatos = $evaluacion->GetDatos_EvaluacionIntubacion();
          
          $OtrosDatos = $evaluacion->GetDatos_EvaluacionOtrosFactores();
          
          $this->salida = $evaluacion_HTML->frmForma($E_Cardio, $E_Respiratorio, $E_Metabolicos, $E_Gastro, $E_Renal, $E_Neuro, $E_Esqueletico, $E_Hematologica, $E_Hepatico, $E_Gineco, $E_Intubacion, $E_OtrosF,
          								   $VectorI, $enlace, $VectorCups, $enlace1, $CaracteristicasDatos, $SignosDatos, $cardioDatos, $RespDatos, $MetabDatos, $GastroDatos, $RenalDatos, $NeuroDatos,
                                                     $EsqueDatos, $HemaDatos, $HepaDatos, $GinecoDatos, $IntuDatos, $OtrosDatos);
          return true;
	}
}

?>