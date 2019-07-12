<?php
  /** 
  * $Id: hc_InformacionInicialPaciente.php,v 1.3 2008/11/18 16:31:48 hugo Exp $
  * 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.3 $ 
  * 
  * @autor J gomez
  */
  session_start();
  IncludeClass("DatosInformacion",NULL,"hc","InformacionInicialPaciente");
  IncludeClass("DatosInformacion_HTML","HTML","hc","InformacionInicialPaciente");
  class InformacionInicialPaciente extends hc_classModules
  {
    /**
    * Esta funci� Inicializa las variable de la clase
    *
    * @access public
    * @return boolean Para identificar que se realizo.
    */
  	function InformacionInicialPaciente()
  	{
  		return true;
  	}
    /**
    * Esta funci� verifica si este submodulo fue utilizado para la atencion de un paciente.
    *
    * @access private
    * @return text Datos HTML de la pantalla.
    */
  	function GetEstado()
  	{
  		return true;
  	}
    /**
    *
    */
    function GetConsulta()
    {
      return true;
    }
    /**
    * Esta metodo captura los datos de la impresi� de la Historia Clinica.
    * @access private
    * @return text Datos HTML de la pantalla.
    */
    function GetReporte_Html()
    { 
      return true;
    }
    /**
    * Esta funci� retorna la presentaci� del submodulo (consulta o inserci�).
    *
    * @access public
    * @return text Datos HTML de la pantalla.
    * @param text Determina la acci� a realizar.
    */
  	function GetForma()
  	{
        SessionSetVar("rutaImagenes",GetThemePath());
        $Datos_Html=new DatosInformacion_HTML($this);
        $Datos_sql=new DatosInformacion();

        $filtro=array();
        $filtro['afiliado_tipo_id']=$this->datosPaciente['tipo_id_paciente'];
        $filtro['afiliado_id']=$this->datosPaciente['paciente_id'];
        $es_cotizante = $Datos_sql->GetAfiliados($filtro);
        $diagnosticosC = $Datos_sql->ObtenerDiagnosticosCronicos($this->datosPaciente);
        $programas = $Datos_sql->ObtenerProgramasPyP($this->datosPaciente);
        $incapacidades = $Datos_sql->ObtenerIncapacidades($this->datosPaciente);
        $medicamentos = $Datos_sql->ObtenerMedicamentosUsuario($this->datosPaciente);
        
        $riesgos = $Datos_sql->ObtenerDatosCicloFamiliar($this->datosPaciente);
        $indiv = $Datos_sql->ObtenerDatosCicloIndividual($this->datosPaciente);
        
        $vectores = count($es_cotizante);
      
        $es_cotizante = $es_cotizante[(count($es_cotizante)-1)];
        $historia = $Datos_sql->HistoriaClinicaCompleta($this->datosPaciente['paciente_id'],$this->datosPaciente['tipo_id_paciente']);
        
        $this->salida .= "<script language='javascript' src='hc_modules/InformacionInicialPaciente/RemoteXajax/Info.js'></script>";
        $this->salida .= ThemeAbrirTabla("RESUMEN DE HISTORIA CLINICA");
        $this->salida .= "<table width=\"100%\" align=\"center\">\n";
        $this->salida .= "  <tr>\n";
        $this->salida .= "    <td>\n";
        $this->salida .= "			<div class=\"tab-pane\" id=\"inicial_paciente\">\n";
        $this->salida .= "				<script>	tabPane = new WebFXTabPane( document.getElementById( \"inicial_paciente\" ), false); </script>\n";
        $this->salida .= "				<div class=\"tab-page\" id=\"informacion_inicial\">\n";
        $this->salida .= "					<h2 class=\"tab\">INFORMACION PACIENTE</h2>\n";
        $this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"informacion_inicial\")); </script>\n";
        if($es_cotizante['eps_tipo_afiliado_id']=='C')
        {
          $cotizante = $Datos_sql->GetDatosAfiliado($es_cotizante['eps_afiliacion_id'],$es_cotizante['afiliado_tipo_id'],$es_cotizante['afiliado_id']);
          $afiliados = $Datos_sql->ObtenerBeneficiariosCotizante($filtro);
          $this->salida.=$Datos_Html->BuscarBeneficiarios($cotizante,$afiliados);
        }
        elseif($es_cotizante['eps_tipo_afiliado_id']=='B')//obtener los datos del cotizante
        {
          $datos_afiliado=$Datos_sql->GetDatosAfiliado($es_cotizante['eps_afiliacion_id'],$es_cotizante['afiliado_tipo_id'],$es_cotizante['afiliado_id']);
          $filtro['afiliado_tipo_id']=$datos_afiliado["DATOS_BENEFICIARIO"]['cotizante_tipo_id'];
          $filtro['afiliado_id']=$datos_afiliado["DATOS_BENEFICIARIO"]['cotizante_id'];
          $cotizante = $Datos_sql->GetDatosAfiliado($datos_afiliado["DATOS_BENEFICIARIO"]['eps_afiliacion_id'],$datos_afiliado["DATOS_BENEFICIARIO"]['cotizante_tipo_id'],$datos_afiliado["DATOS_BENEFICIARIO"]['cotizante_id']);
          $afiliados = $Datos_sql->ObtenerBeneficiariosCotizante($filtro);

          $this->salida.=$Datos_Html->BuscarBeneficiarios1($cotizante,$afiliados,$es_cotizante['afiliado_tipo_id'],$es_cotizante['afiliado_id']);
        }
        
        $this->salida .= "<br>";
        $this->salida .= $Datos_Html->FormaProgramasPyP_Incapacidades($programas,$incapacidades);
        
        $this->salida .= "        </div>\n";
        $this->salida .= "				<div class=\"tab-page\" id=\"diagnosticos\">\n";
        $this->salida .= "					<h2 class=\"tab\">DIAGNOSTICOS</h2>\n";
        $this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"diagnosticos\")); </script>\n";
        $this->salida .= $Datos_Html->FormaDiagnosticosCronicos($diagnosticosC);

        $this->salida .= "        </div>\n";
        $this->salida .= "				<div class=\"tab-page\" id=\"riesgos\">\n";
        $this->salida .= "					<h2 class=\"tab\">RIESGOS</h2>\n";
        $this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"riesgos\")); </script>\n";
        $this->salida .= $Datos_Html->FormaCiclos($riesgos,$indiv);

        $this->salida .= "        </div>\n";
        if(!empty($medicamentos))
        {        
          $this->salida .= "				<div class=\"tab-page\" id=\"medicamentos\">\n";
          $this->salida .= "					<h2 class=\"tab\">MEDICAMENTOS</h2>\n";
          $this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"medicamentos\")); </script>\n";
          $this->salida .= $Datos_Html->FormaMedicamentosFormuladosHTML($medicamentos);
          $this->salida .= "        </div>\n";
        }
        $this->salida .= "				<div class=\"tab-page\" id=\"historial\">\n";
        $this->salida .= "					<h2 class=\"tab\">HISTORIAL</h2>\n";
        $this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"historial\")); </script>\n";
        $this->salida .= $Datos_Html->HistoriaClinicaPaciente($historia, $this->datosEvolucion['evolucion_id'],'' );

        $this->salida .= "        </div>\n";
        $this->salida .= "      </div>\n";
        $this->salida .= "    </td>\n";
        $this->salida .= "  </tr>\n";
        $this->salida .= "</table>\n";
        $this->salida .= "<BR>";
        
        unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);                         
        $this->salida .= themeCerrarTabla();
        return true;
    }
  }
?>