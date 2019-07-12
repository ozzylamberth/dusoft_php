<?php

/**
 * $Id: ReporteAtencion_Soat.report.php,v 1.1 2007/05/11 15:35:06 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 */

 
IncludeClass("Certificado",null,"hc","RegistroAtencion_Soat"); 

 
class ReporteAtencion_Soat_report
{
	var $datos;
	
	function ReporteAtencion_Soat_report($datos=array())
	{
		$this->datos=$datos;
		return true;
	}

	
	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	
	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
													 'subtitulo'=>'',
													 'logo'=>'logocliente.png',
													 'align'=>'left'));
		return $Membrete;
	}
	 
	function CrearReporte()
	{
          
          $obj_certificado = new Certificado();
          
          //Datos del ingreso
          $Datos_Ingreso = $obj_certificado->GetRegistroIngreso();
          
          //Fecha Accidente
          $DatosFechaAc = $obj_certificado->GetFechaAccidenteSoat();
          
          //Fecha Ingreso
          $DatosFechaIn = $obj_certificado->GetFechaAtencionSoat();
          
          //Signos Vitales.
          $Signos = $obj_certificado->GetSignosVitalesLocales();
               
          //Estado de conciencia
          $Conciencia = $obj_certificado->GetNivelesConciencia();
          
          //Expedicion docuemnto
          $Expedicion = $obj_certificado->LugarExpedicionDocumento();

          //Examenes Fisicos
          $Examenes = $obj_certificado->GetRegistroExamenesFisicos();
          
          $Diagnosticos = $obj_certificado->GetRegistroDiagnosticosI();

          $DiagnosticosE = $obj_certificado->GetRegistroDiagnosticosE();

          $SwImprime = $obj_certificado->GetActivacionImpresion();
          
          $Empresa = $this->GetInformacionEmpresa(SessionGetVar("Departamento"));

		$HTML_WEB_PAGE.="<HTML><BODY>";
		$HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD ALIGN=\"center\" CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="CERTIFICADO DE ATENCION MEDICA PARA VICTIMAS DE ACCIDENTES DE TRANSITO<BR>";
		$HTML_WEB_PAGE.="EXPEDIDA POR LA INSTITUCION PRESTADORA DE SALUD<BR>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD>";
          $HTML_WEB_PAGE.="&nbsp;";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
		
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD ALIGN=\"justify\" CLASS=\"normal_10\">";
          $HTML_WEB_PAGE.="El suscrito médico del servicio de Urgencias de la Institución prestadora de servicios<BR>";
		$HTML_WEB_PAGE.="<LABEL CLASS=\"normal_10N\">".$Empresa['razon_social']."</LABEL><BR>";
          $HTML_WEB_PAGE.="Con domicilio en <LABEL CLASS=\"normal_10N\">".$Empresa['direccion']."</LABEL>  Ciudad  <LABEL CLASS=\"normal_10N\">".$Empresa['municipio']."</LABEL><BR>";
          $HTML_WEB_PAGE.="Departamento  <LABEL CLASS=\"normal_10N\">".$Empresa['departamento']."</LABEL> Teléfono  <LABEL CLASS=\"normal_10N\">".$Empresa['telefonos']."</LABEL><BR>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $datosPaciente = SessionGetVar("DatosPaciente");
          $nombrePaciente = $datosPaciente['primer_nombre']." ".$datosPaciente['segundo_nombre']." ".$datosPaciente['primer_apellido']." ".$datosPaciente['segundo_apellido'];
          $ResidenciaPaciente = $this->ConversionDomicilio($datosPaciente['tipo_pais_id'], $datosPaciente['tipo_dpto_id'], $datosPaciente['tipo_mpio_id']);
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD ALIGN=\"justify\" CLASS=\"normal_10\">";
          $HTML_WEB_PAGE.="Certifica que atendió en el servicio de Urgencias al Señor(a)  <LABEL CLASS=\"normal_10N\">".$nombrePaciente."</LABEL><BR>";
		$HTML_WEB_PAGE.="Identificado con  <LABEL CLASS=\"normal_10N\">".SessionGetVar("tipoidpaciente")."</LABEL>  número  <LABEL CLASS=\"normal_10N\">".SessionGetVar("paciente")."</LABEL>  de  <LABEL CLASS=\"normal_10N\">".$Expedicion."</LABEL><BR>";
          $HTML_WEB_PAGE.="Residente en:  <LABEL CLASS=\"normal_10N\">".$datosPaciente['residencia_direccion']."</LABEL>  Ciudad  <LABEL CLASS=\"normal_10N\">".$ResidenciaPaciente['municipio']."</LABEL><BR>";
          $HTML_WEB_PAGE.="Departamento  <LABEL CLASS=\"normal_10N\">".$ResidenciaPaciente['departamento']."</LABEL>  Teléfono  <LABEL CLASS=\"normal_10N\">".$datosPaciente['residencia_telefono']."</LABEL><BR>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $fecha = explode(" ", $DatosFechaAc['fecha_accidente']);
          $fechaAC = explode("-", $fecha[0]);
          $horaAC  = explode(":", $fecha[1]);
          
          $fecha = explode(" ", $DatosFechaIn['fecha_ingreso']);
          $fechaIN = explode("-", $fecha[0]);
          $horaIN  = explode(":", $fecha[1]);

          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD ALIGN=\"justify\" CLASS=\"normal_10\">";
          $HTML_WEB_PAGE.="Quien según declaración de  <LABEL CLASS=\"normal_10N\">".$Datos_Ingreso['nombre_acudiente']."</LABEL><BR>";
		$HTML_WEB_PAGE.="Identificado con  <LABEL CLASS=\"normal_10N\">".$Datos_Ingreso['tipo_id_acudiente']."</LABEL>  No.  <LABEL CLASS=\"normal_10N\">".$Datos_Ingreso['id_acudiente']."</LABEL>  Expedida en:  <LABEL CLASS=\"normal_10N\">".$Datos_Ingreso['expedicion_identificacion']."</LABEL>  Fue víctima del accidente de <BR>";
          $HTML_WEB_PAGE.="Tránsito ocurrido el día  <LABEL CLASS=\"normal_10N\">".$fechaAC[2]."</LABEL>  mes  <LABEL CLASS=\"normal_10N\">".$fechaAC[1]."</LABEL>  año  <LABEL CLASS=\"normal_10N\">".$fechaAC[1]."</LABEL>  a las  <LABEL CLASS=\"normal_10N\">".$horaAC[0]."</LABEL>  :  <LABEL CLASS=\"normal_10N\">".$horaAC[1]."</LABEL>  horas ingresando al servicio de urgencias<BR>";
          $HTML_WEB_PAGE.="de esta institucion el día  <LABEL CLASS=\"normal_10N\">".$fechaIN[2]."</LABEL>  mes  <LABEL CLASS=\"normal_10N\">".$fechaIN[1]."</LABEL>  año  <LABEL CLASS=\"normal_10N\">".$fechaIN[1]."</LABEL>  a las  <LABEL CLASS=\"normal_10N\">".$horaIN[0]."</LABEL>  :  <LABEL CLASS=\"normal_10N\">".$horaIN[1]."</LABEL>  horas con los siguientes hallazgos:";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $fc = $Signos['fc'];
          $fr = $Signos['fr'];
          $temp = $Signos['temperatura'];
          $t_alta = $Signos['t_alta'];
          $t_baja = $Signos['t_baja'];
          
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>SIGNOS VITALES:</LABEL></TD>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>FC:</LABEL>&nbsp;&nbsp;&nbsp;".$fc."&nbsp;&nbsp;x min.</td>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>FR:</LABEL>&nbsp;&nbsp;&nbsp;".$fr."&nbsp;&nbsp;x min.</td>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>Tº:</LABEL>&nbsp;&nbsp;&nbsp;".$temp."&nbsp;&nbsp;ºC</td>";
          $HTML_WEB_PAGE.="<TD COLSPAN=\"2\" class='normal_10'><LABEL class='normal_10N'>TENSION:</LABEL>&nbsp;&nbsp;&nbsp;".$t_alta."&nbsp;&nbsp;&nbsp;<B>/</B>&nbsp;&nbsp;&nbsp;";
          $HTML_WEB_PAGE.="".$t_baja."&nbsp;&nbsp;mmHg</td>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="</TABLE>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $glasgow = $Conciencia['glasgow'];
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD CLASS='normal_10'><LABEL CLASS='normal_10N'>ESTADO DE CONCIENCIA:</LABEL></TD>";
          $checked = "";
          if($Conciencia['alerta'] == 1){$checked = "X";}
          $HTML_WEB_PAGE.="<TD CLASS='normal_10'><LABEL CLASS='normal_10N'>ALERTA:</LABEL>&nbsp;&nbsp;&nbsp;$checked</TD>";
          $checked = "";
          if($Conciencia['obnubilado'] == 1){$checked = "X";}
          $HTML_WEB_PAGE.="<TD CLASS='normal_10'><LABEL CLASS='normal_10N'>OBNUBILADO:</LABEL>&nbsp;&nbsp;&nbsp;$checked</TD>";
          $checked = "";
          if($Conciencia['estuporoso'] == 1){$checked = "X";}
          $HTML_WEB_PAGE.="<TD CLASS='normal_10'><LABEL CLASS='normal_10N'>ESTUPOROSO:</LABEL>&nbsp;&nbsp;&nbsp;$checked</TD>";
          $checked = "";
          if($Conciencia['comatoso'] == 1){$checked = "X";}
          $HTML_WEB_PAGE.="<TD CLASS='normal_10'><LABEL CLASS='normal_10N'>COMA:</LABEL>&nbsp;&nbsp;&nbsp;$checked</TD>";
          $checked = "";
          $HTML_WEB_PAGE.="<TD CLASS='normal_10'><LABEL CLASS='normal_10N'>GLASGOW (7):</LABEL>&nbsp;&nbsp;&nbsp;$glasgow</TD>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="</TABLE>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\" CLASS=\"normal_10N\">&nbsp;<LABEL>ESTADO DE EMBRIAGUEZ:</LABEL>";
          if($Datos_Ingreso['estado_embriaguez'] == "1"){$checked = "X";}else{$checked = "";}
          $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;<B>SI</B>&nbsp;&nbsp;&nbsp;(&nbsp;$checked&nbsp;)";
          if($Datos_Ingreso['estado_embriaguez'] == "0"){$checked = "X";}else{$checked = "";}          
          $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NO</B>&nbsp;&nbsp;&nbsp;(&nbsp;$checked&nbsp;)";
          $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;<LABEL CLASS=\"NORMAL_10N\">(&nbsp;En caso de positivo tomar muestra para alcoholemia u otras drogas&nbsp;).</LABEL><BR>";
          $HTML_WEB_PAGE.="</TD>";
		$HTML_WEB_PAGE.="</TR>";

          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"100%\"><BR>";
          $HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD><LABEL class='normal_10N'>DATOS POSITIVOS</LABEL></TD>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>CABEZA Y ORGANOS DE LOS SENTIDOS:</LABEL>&nbsp;&nbsp;&nbsp;".$Examenes['cabeza']."</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>CUELLO:</LABEL>&nbsp;&nbsp;&nbsp;".$Examenes['cuello']."</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>TORAX Y CARDIOPULMONAR:</LABEL>&nbsp;&nbsp;&nbsp;".$Examenes['torax']."</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>ABDOMEN:</LABEL>&nbsp;&nbsp;&nbsp;".$Examenes['abdomen']."</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>GENITOURINARIO</LABEL>&nbsp;&nbsp;&nbsp;".$Examenes['genitourinario']."</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>PELVIS:</LABEL>&nbsp;&nbsp;&nbsp;".$Examenes['pelvis']."</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>DORSO Y EXTREMIDADES:</LABEL>&nbsp;&nbsp;&nbsp;".$Examenes['dorso']."</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>NEUROLOGICO:</LABEL>&nbsp;&nbsp;&nbsp;".$Examenes['neurologico']."</td>";
          $HTML_WEB_PAGE.="</TR>";          
          $HTML_WEB_PAGE.="</TABLE>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";

          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"100%\"><BR>";
          if($Diagnosticos)
          {
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"center\" WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TR CLASS='normal_10N'>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"left\" COLSPAN=\"2\">IMPRESION DIAGNOSTICA:</TD>";
               $HTML_WEB_PAGE.="</TR>";
               for($i=0;$i<sizeof($Diagnosticos);$i++)
               {
                    $HTML_WEB_PAGE.="<TR>";
                    $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10'>".$Diagnosticos[$i][diagnostico_id]."</td>";
                    $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10'>".$Diagnosticos[$i][diagnostico_nombre]."</td>";
                    $HTML_WEB_PAGE.="</TR>";
               }
               $HTML_WEB_PAGE.="</TABLE><BR>";
          }
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";

          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          if($DiagnosticosE)
          {
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"center\" WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TR CLASS='normal_10N'>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"left\" COLSPAN=\"2\">DIAGNOSTICO DEFINITIVO:</TD>";
               $HTML_WEB_PAGE.="</TR>";
               for($i=0;$i<sizeof($DiagnosticosE);$i++)
               {
                    $HTML_WEB_PAGE.="<TR>";
                    $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10'>".$DiagnosticosE[$i][diagnostico_id]."</td>";
                    $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10'>".$DiagnosticosE[$i][diagnostico_nombre]."</td>";
                    $HTML_WEB_PAGE.="</TR>";
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
          }
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          
          $this->GetDatosProfesional();
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<BR><BR><TABLE ALIGN=\"center\" WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10N'>Nombres y Apellidos del Médico:&nbsp;&nbsp;".$this->datosProfesional['nombre']."</td>";
          $HTML_WEB_PAGE.="<TD ALIGN=\"right\" CLASS='normal_10N'>Firma y Sello&nbsp;&nbsp;<LABEL>____________________________________</LABEL></td>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10N'>Registro Médico No.:&nbsp;&nbsp;".$this->datosProfesional['tarjeta_profesional']."</td>";
          $HTML_WEB_PAGE.="<TD ALIGN=\"right\" CLASS='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="</TABLE>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="</BODY></HTML>";
		
		return $HTML_WEB_PAGE;
	}

     
	function GetInformacionEmpresa($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="SELECT b.*, c.municipio, d.departamento
          		 FROM departamentos as a, 
                          empresas as b,
                          tipo_mpios as c,
                          tipo_dptos as d
                     WHERE a.departamento='$departamento' 
                     AND a.empresa_id=b.empresa_id
                     AND b.tipo_mpio_id = c.tipo_mpio_id
                     AND b.tipo_dpto_id = d.tipo_dpto_id;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0) {
               return false;
          }
		while(!$result->EOF)
		{
			$dato=$result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $dato;
	}
     
     
     function ConversionDomicilio($pais, $depto, $mupio)
     {
		list($dbconn) = GetDBconn();
		$sql="SELECT a.pais, c.municipio, d.departamento
          	 FROM tipo_pais AS a,
                	 tipo_mpios AS c,
                     tipo_dptos AS d
                WHERE a.tipo_pais_id = '".$pais."'
                AND d.tipo_dpto_id = '".$depto."'
                AND d.tipo_pais_id = a.tipo_pais_id
                AND c.tipo_mpio_id = '".$mupio."'
                AND c.tipo_dpto_id = d.tipo_dpto_id
                AND c.tipo_pais_id = a.tipo_pais_id;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0) {
               return false;
          }
		while(!$result->EOF)
		{
			$dato=$result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $dato;
     }
     
	
     function GetDatosProfesional()
	{
          list($dbconn) = GetDBconn();
          $sql="SELECT A.tipo_id_tercero, A.tercero_id, A.nombre,
               	   A.tarjeta_profesional, B.especialidad, C.descripcion
                FROM profesionales AS A,
               	 profesionales_usuarios AS E
                LEFT JOIN profesionales_especialidades AS B
                ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                WHERE A.usuario_id =".SessionGetVar("Usuario")."
                AND A.usuario_id = E.usuario_id
                AND E.tercero_id = A.tercero_id
                AND E.tipo_tercero_id = A.tipo_id_tercero;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
		while(!$result->EOF)
		{
			$this->datosProfesional = $result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $this->datosProfesional;
	}

     
	/*function CargarVariables()
	{
     	$this->realimprimir=1;
          list($dbconn) = GetDBconn();
		$query = "SELECT MAX(A.evolucion_id) 
                    FROM hc_evoluciones AS A,
                         profesionales AS B 
                    WHERE A.ingreso='".$this->ingreso."'
                    AND A.usuario_id = B.usuario_id
                    AND B.tipo_profesional IN ('1','2');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
 		list($evolucion) = $result->FetchRow();
          $this->evolucion = $evolucion;
          
          if(!$evolucion)
          {
               $query = "SELECT MAX(evolucion_id)
                         FROM hc_evoluciones
                         WHERE ingreso='".$this->ingreso."';";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               list($evolucion) = $result->FetchRow();
               $this->evolucion = $evolucion;
          }

		if(!IncludeLib('datospaciente'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de pacientes.";
      		return false;
          }
          
		if(!IncludeLib('historia_clinica'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de Historia Clinica";
			return false;
          }

		if(!IncludeFile('classes/modules/hc_classmodules.class.php',true))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El archivo 'includes/historia_clinica.inc.php' no existe.";
			return false;
          }

          $this->Datosingreso = GetDatosIngreso($this->evolucion,$this->ingreso);

          $this->datosPaciente = GetDatosPaciente("","",$this->ingreso);

     	$this->EvolucionGeneral = GetDatosEvolucion($this->evolucion);

          $this->tipo_profesional = $this->GetTipoProfesional();
          
		$this->Datos_Ingreso();
 
          $this->GetDatosResponsable();
          
          $this->GetDatosProfesional();
          
          $this->User = $this->GetDatosUsuarioSistema(UserGetUID());
          
          return true;
     }
		 
	function GetDatosResponsable()
	{
		list($dbconn) = GetDBconn();
         
          $sql="SELECT a.plan_id, a.tipo_afiliado_id, a.rango, a.semanas_cotizadas, b.plan_descripcion, b.tipo_tercero_id, b.tercero_id, b.num_contrato, c.nombre_tercero, X.tipo_afiliado_nombre
               FROM cuentas as a LEFT JOIN tipos_afiliado AS X ON (a.tipo_afiliado_id = X.tipo_afiliado_id), planes as b, terceros as c
               WHERE
               a.plan_id = b.plan_id
               AND b.tercero_id = c.tercero_id
               AND b.tercero_id = c.tercero_id
               AND a.numerodecuenta = ".$this->EvolucionGeneral['numerodecuenta'].";";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta";
			$this->mensajeDeError = $sql.$dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
			return false;
		}
		if(!$resultado->EOF)
		{
			$this->Responsable = $resultado->FetchRow();
		}
		return $this->Responsable;
	}

     function GetTipoProfesional()
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT tipo_profesional
               FROM profesionales_usuarios a, profesionales b
               WHERE a.tipo_tercero_id=b.tipo_id_tercero and
               a.tercero_id=b.tercero_id and
               a.usuario_id=".UserGetUID()."";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
          list($this->tipo_profesional) = $result->FetchRow();
          return $this->tipo_profesional;
     }

	function GetServicio($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.descripcion from departamentos as a, servicios as b where a.servicio=b.servicio and a.departamento='".$departamento."';";
		$result = $dbconn->Execute($sql);
		return $result->fields[0];
	}

	function GetDepartamento($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select a.descripcion from departamentos as a where a.departamento='".$departamento."';";
		$result = $dbconn->Execute($sql);
		return $result->fields[0];
	}


     function Consulta_NotasMedicas()
	{
		list($dbconn) = GetDBconn();
		$query= "SELECT A.ingreso,
					 A.fecha_registro, A.evolucion_id,
					 A.nota_medica, B.nombre, B.usuario
				FROM notas_medicas AS A,
					system_usuarios AS B
				WHERE A.ingreso='".$this->ingreso."'
				AND B.usuario_id=A.usuario_id
				ORDER BY fecha_registro DESC;";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while(!$resulta->EOF)
			{
				$datosfila=$resulta->GetRowAssoc($ToUpper = false);
				list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$datosfila[hora]=$hora.":".$min;
				$fecha = $fecha;
				$notas[$fecha][]=$datosfila;
				$resulta->MoveNext();
			}
		}
		return $notas;
	}

     
	function Datos_Ingreso()
	{
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
		$query = "SELECT A.fecha_registro, A.departamento, A.departamento_actual,
                           A.fecha_cierre, B.fecha, B.fecha_cierre AS cierre_evolucion,
                           C.descripcion 
                    FROM ingresos AS A
                    LEFT JOIN hc_evoluciones AS B ON (A.ingreso = B.ingreso)
                    LEFT JOIN departamentos AS C ON (A.departamento_actual = C.departamento)
                    WHERE A.ingreso='".$this->ingreso."'
                    AND B.evolucion_id = (SELECT MAX(evolucion_id) 
                    				  FROM hc_evoluciones 
                                          WHERE ingreso = '".$this->ingreso."' 
                                          AND fecha_cierre IS NOT NULL);";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->DatosIngreso_Paciente = $result->FetchRow();
			return $this->DatosIngreso_Paciente;
		}
	}
     
     function BuscarCamaActiva($ingreso)
     {
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
		$query = "SELECT fecha_egreso, cama
          		FROM movimientos_habitacion
				WHERE ingreso='".$this->ingreso."';";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->DatosCama = $result->FetchRow();
			return true;
		}
     }
     
		 function GetFechaEgreso()
     {
			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;
			
			$query = "SELECT fecha_registro
								FROM ingresos_salidas
								WHERE ingreso=".$this->ingreso.";";
			
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$this->datosFE = $result->FetchRow();
				return true;
			}
     }
*/		 
	 
     /*		GetDatosUsuarioSistema
     *
     *		Obtiene el nombre de usuario del sistema
     *
     *		@Author Rosa Maria Angel
     *		@access Public
     *		@return bool
     *		@param integer => usuario_id
     */
     /*
     function GetDatosUsuarioSistema($usuario)
     {
          $pfj=$this->frmPrefijo;
          $query = "SELECT usuario,
                    nombre
                    FROM system_usuarios
                    WHERE usuario_id = $usuario";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    return "ShowMensaje";
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $DatosUser[] = $data;
                    }
                    return $DatosUser;
               }
          }
     }/// GetDatosUsuarioSistema


	function IniciarImprimir()
	{
		$this->CabeceraImprimir();
          $vista_Nota = $this->Vista_NotaMedica();
          list($dbconn) = GetDBconn();
		$query = "select distinct submodulo, paso from 
				(SELECT  a.submodulo, a.secuencia, a.paso
				FROM historias_clinicas_templates a, hc_evoluciones as b, system_hc_submodulos as c
				WHERE b.ingreso =".$this->EvolucionGeneral['ingreso']."
				and b.hc_modulo=a.hc_modulo and a.submodulo=c.submodulo and c.sw_imprime='1'
				ORDER BY a.paso,a.secuencia) as a ORDER BY a.paso;";	
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
		}

		if ($result->EOF) 
		{
			$this->imprimir .= "NO HAY DATOS VALIDOS PARA IMPRESION";
			return true;
		}
		else
		{
               while(!$result->EOF)
               {
                         $var[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();					
               }
					
               foreach($var as $k => $v)
               {						
                    $submodulo_obj=IncluirSubModuloHC($v[submodulo]);
                    if(!is_object($submodulo_obj)){
                         $this->error = "Error al cargar el submodulo";
                         $this->mensajeDeError = $submodulo_obj;
                    }
                    else
                    {
                         $submodulo_obj->InicializarSubmodulo($this->EvolucionGeneral,'',$this->datosPaciente,'','','',$this->paso,'frm_'.$v[submodulo],'','','','');
                         if(method_exists($submodulo_obj,'GetReporte_Html'))
                         {
                              $dato1=$submodulo_obj->GetReporte_Html();
                              if($dato1!=1)
                              {
                                   $this->realimprimir=2;
                                   $this->imprimir.=$dato1;
                              }
                         }
                    }
                    unset($dato1);
                    unset($submodulo_obj);
               }
               $result->close();
               $this->imprimir.=$vista_Nota;
               if($this->realimprimir==2)
               {
                    $this->PiePaginaImprimir();
               }
		}
		$this->GetImpresion();
		return true;
     }

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
	}*/

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
     
  function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}
	
}
?>
