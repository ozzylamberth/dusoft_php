<?php
/**
* Submodulo de Registro de Atencion de Soat
*
* @author Tizziano Perea Ocoro
* @version 1.0
* @package SIIS
* $Id: hc_RegistroAtencion_Soat.php
*/

IncludeClass("Certificado",null,"hc","RegistroAtencion_Soat");
IncludeClass("Certificado_HTML","html","hc","RegistroAtencion_Soat");

class RegistroAtencion_Soat extends hc_classModules
{
	function RegistroAtencion_Soat()
	{
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
		'fecha'=>'27/03/2007',
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
		return true;
	}
     
	/**
	* Esta metodo captura los datos de la impresión de la Historia Clinica.
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetReporte_Html()
	{
		return $salida;
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
	
	function GetForma($sw)
	{
     	$obj_certificado = new Certificado();
          $obj_certificado_HTML = new Certificado_HTML();
          
          if($sw == 1)
          { unset($_REQUEST['accion'.$this->frmPrefijo]); }
          //Variables
          SessionSetVar("Ingreso", $this->ingreso);
          SessionSetVar("Evolucion", $this->evolucion);
          SessionSetVar("Paso", $this->paso);
          SessionSetVar("DatosPaciente", $this->datosPaciente);
          SessionSetVar("tipoidpaciente", $this->tipoidpaciente);
          SessionSetVar("paciente", $this->paciente);
          SessionSetVar("prefijo", $this->frmPrefijo);
          SessionSetVar("Usuario", UserGetUID());
          SessionSetVar("RutaImg", GetThemePath());
          SessionSetVar("Departamento", $this->departamento);
          SessionSetVar("Limite", 5);

          //Datos del ingreso
          $Datos_Ingreso = $obj_certificado->GetRegistroIngreso();
          
          //Fecha Accidente
          $DatosFechaAc = $obj_certificado->GetFechaAccidenteSoat();
          
          //Fecha Ingreso
          $DatosFechaIn = $obj_certificado->GetFechaAtencionSoat();
          
          //Signos Vitales.
          $Signos = $obj_certificado->GetSignosVitalesLocales();
          if(empty($Signos))
               $Signos = $obj_certificado->GetSignosVitales();
               
          //Estado de conciencia
          $Conciencia = $obj_certificado->GetNivelesConciencia();
          
          //Expedicion docuemnto
          $Expedicion = $obj_certificado->LugarExpedicionDocumento();

          //Examenes Fisicos
          $Examenes = $obj_certificado->GetRegistroExamenesFisicos();
          
          //Diagnosticos
          include_once 'hc_modules/RegistroAtencion_Soat/RemoteXajax/RegistroAtencion_Xajax.php';
         
          $objClassModules=new hc_Classmodules(); 
          $objClassModules->SetXajax(array("BusquedaDX","VectorDX","OcultarDXAsignados","RegistrarVar"));
          
          
          $Diagnosticos = $obj_certificado->GetRegistroDiagnosticosI();
          $VectorI = $Diagnosticos;
          $enlace = "0";
          
          if(empty($Diagnosticos))
          {
			$diag = $obj_certificado->ConsultaDiagnosticoI();
               $VectorI = $diag;
               $enlace = "1";
               $_SESSION['diag_REGI'] = $diag;
               SessionSetVar('VectorDXEvoI', $diag);
          }

          $DiagnosticosE = $obj_certificado->GetRegistroDiagnosticosE();
          $VectorE = $DiagnosticosE;
          $enlace1 = "0";
          
          if(empty($DiagnosticosE))
          {
			$diag = $obj_certificado->ConsultaDiagnosticoE();
               $VectorE = $diag;
               $enlace1 = "1";
               $_SESSION['diag_REGE'] = $diag;
               SessionSetVar('VectorDXEvoE', $diag);
          }

          $SwImprime = $obj_certificado->GetActivacionImpresion();
          
          if(empty($Datos_Ingreso))
          	$VectorTipo = $obj_certificado->Get_Tipo_ID();
          else
          	$VectorTipo = $obj_certificado->Get_Tipo_ID();
          
          //$this->tipoidpaciente
          switch($_REQUEST['accion'.$this->frmPrefijo])
          {
          	case 'InsertarDatos':
                    
                    $DatosReg = array();
                    $DatosSignosV = array();
                    $DatosNivel = array();
                    
                    //Datos registro de Ingreso
                    $DatosReg['acudiente'] = $_REQUEST['acudiente'.$this->frmPrefijo];
                    $DatosReg['id_acudiente'] = $_REQUEST['id_acudiente'.$this->frmPrefijo];
                    $DatosReg['expedicion_doc'] = $_REQUEST['expedicion_doc'.$this->frmPrefijo];
                    $DatosReg['tipo_id_acudiente'] = $_REQUEST['tipo_id'.$this->frmPrefijo];
                    $fecha_ac = $_REQUEST['año_accidente'.$this->frmPrefijo]."-".$_REQUEST['mes_accidente'.$this->frmPrefijo]."-".$_REQUEST['dia_accidente'.$this->frmPrefijo]." ".$_REQUEST['selectHoraAccidente'.$this->frmPrefijo].":".$_REQUEST['selectMinutosAccidente'.$this->frmPrefijo];
                    $DatosReg['fecha_accidente'] = $fecha_ac;
                    $fecha_at = $_REQUEST['año_atencion'.$this->frmPrefijo]."-".$_REQUEST['mes_atencion'.$this->frmPrefijo]."-".$_REQUEST['dia_atencion'.$this->frmPrefijo]." ".$_REQUEST['selectHoraAtencion'.$this->frmPrefijo].":".$_REQUEST['selectMinutosAtencion'.$this->frmPrefijo];
                    $DatosReg['fecha_atencion'] = $fecha_at;
                    $DatosReg['estado_embriaguez'] = $_REQUEST['estado'.$this->frmPrefijo];

                    if($DatosReg['acudiente'] == "" OR $DatosReg['id_acudiente'] == ""  OR $DatosReg['expedicion_doc'] == "")
                    {
                         $obj_certificado_HTML->frmError["MensajeError"] = "USTED DEBE COMPLETAR TODA LA INFORMACION DEL ACUDIENTE.";
					$this->salida = $obj_certificado_HTML->frmForma($Signos, $Conciencia, $Expedicion, $Datos_Ingreso, $Examenes, $Diagnosticos, $VectorI, $enlace, $VectorE, $enlace1, $DatosFechaAc, $DatosFechaIn, $SwImprime, $VectorTipo);
                         return true;
                    }
                    
                    if(is_numeric($DatosReg['id_acudiente']) == false)
                    {
                         $obj_certificado_HTML->frmError["MensajeError"] = "EL CAMPO SOLO ACEPTA DATOS NUMERICOS.";
					$this->salida = $obj_certificado_HTML->frmForma($Signos, $Conciencia, $Expedicion, $Datos_Ingreso, $Examenes, $Diagnosticos, $VectorI, $enlace, $VectorE, $enlace1, $DatosFechaAc, $DatosFechaIn, $SwImprime, $VectorTipo);
                         return true;
                    }
                    
                    //Datos signos vitales
                    $DatosSignosV['fr'] = $_REQUEST['fr'.$this->frmPrefijo];
                    $DatosSignosV['fc'] = $_REQUEST['fc'.$this->frmPrefijo];
                    $DatosSignosV['temperatura'] = $_REQUEST['temp'.$this->frmPrefijo];
                    $DatosSignosV['t_alta'] = $_REQUEST['talta'.$this->frmPrefijo];
                    $DatosSignosV['t_baja'] = $_REQUEST['tbaja'.$this->frmPrefijo];
                    
                    //Datos niveles de conciencia
                    if($_REQUEST['nivel'.$this->frmPrefijo] == "alerta"){$alerta = '1';}else{$alerta = '0';}
                    if($_REQUEST['nivel'.$this->frmPrefijo] == "obnubilado"){$obnubilado = '1';}else{$obnubilado = '0';}
                    if($_REQUEST['nivel'.$this->frmPrefijo] == "estuporoso"){$estuporoso = '1';}else{$estuporoso = '0';}
                    if($_REQUEST['nivel'.$this->frmPrefijo] == "coma"){$coma = '1';}else{$coma = '0';}
                     
                    $DatosNivel['alerta'] = $alerta;
                    $DatosNivel['obnubilado'] = $obnubilado;
                    $DatosNivel['estuporoso'] = $estuporoso;
                    $DatosNivel['comatoso'] = $coma;
                    $DatosNivel['glasgow'] = $_REQUEST['glasgow'.$this->frmPrefijo];
                    
                    if($alerta == "0" AND $obnubilado == "0" AND $estuporoso == "0" AND $coma == "0" AND empty($DatosNivel['glasgow']))
                    {
                         $obj_certificado_HTML->frmError["MensajeError"] = "USTED DEBE DILIGENCIAR EL ESTADO DE CONCIENCIA DEL PACIENTE.";
					$this->salida = $obj_certificado_HTML->frmForma($Signos, $Conciencia, $Expedicion, $Datos_Ingreso, $Examenes, $Diagnosticos, $VectorI, $enlace, $VectorE, $enlace1, $DatosFechaAc, $DatosFechaIn, $SwImprime, $VectorTipo);
                         return true;
                    }
                    
                    if($DatosNivel['glasgow'] > 7)
                    {
                         $obj_certificado_HTML->frmError["MensajeError"] = "EL CAMPO GLASGOW NO PUEDE SER MAYOR A 7.";
					$this->salida = $obj_certificado_HTML->frmForma($Signos, $Conciencia, $Expedicion, $Datos_Ingreso, $Examenes, $Diagnosticos, $VectorI, $enlace, $VectorE, $enlace1, $DatosFechaAc, $DatosFechaIn, $SwImprime, $VectorTipo);
                         return true;
                    }

                    
                    //Datos examenes
                    $DatosExamen['cabeza'] = $_REQUEST['cabeza'.$this->frmPrefijo];
                    $DatosExamen['cuello'] = $_REQUEST['cuello'.$this->frmPrefijo];
                    $DatosExamen['torax'] = $_REQUEST['torax'.$this->frmPrefijo];
                    $DatosExamen['abdomen'] = $_REQUEST['abdomen'.$this->frmPrefijo];
                    $DatosExamen['genitourinario'] = $_REQUEST['urinario'.$this->frmPrefijo];
                    $DatosExamen['pelvis'] = $_REQUEST['pelvis'.$this->frmPrefijo];
                    $DatosExamen['dorso'] = $_REQUEST['dorso'.$this->frmPrefijo];
                    $DatosExamen['neurologico'] = $_REQUEST['neurologico'.$this->frmPrefijo];

                    if(empty($DatosExamen['cabeza']) AND empty($DatosExamen['cuello']) AND empty($DatosExamen['torax']) AND empty($DatosExamen['abdomen']) AND empty($DatosExamen['genitourinario']) AND empty($DatosExamen['pelvis']) AND empty($DatosExamen['dorso']) AND empty($DatosExamen['neurologico']))
                    {
                         $obj_certificado_HTML->frmError["MensajeError"] = "USTED DEBE DILIGENCIAR LOS DATOS POSITIVOS DEL PACIENTE.";
					$this->salida = $obj_certificado_HTML->frmForma($Signos, $Conciencia, $Expedicion, $Datos_Ingreso, $Examenes, $Diagnosticos, $VectorI, $enlace, $VectorE, $enlace1, $DatosFechaAc, $DatosFechaIn, $SwImprime, $VectorTipo);
                         return true;
                    }
                    
                    $obj_certificado->InsetarDatosIngresoSoat($DatosReg, $DatosSignosV, $DatosNivel, $DatosExamen);
               
               	$this->GetForma(1);
               break;
          }

		$this->salida = $obj_certificado_HTML->frmForma($Signos, $Conciencia, $Expedicion, $Datos_Ingreso, $Examenes, $Diagnosticos, $VectorI, $enlace, $VectorE, $enlace1, $DatosFechaAc, $DatosFechaIn, $SwImprime, $VectorTipo);
          return true;
	}
}
?>