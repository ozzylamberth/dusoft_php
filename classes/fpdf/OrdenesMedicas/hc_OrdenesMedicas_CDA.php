<?php

/**
* $Id: hc_OrdenesMedicas_CDA.php,v 1.2 2010/12/06 22:12:22 hugo Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo OrdenesMedicas
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.2 $
* @package SIIS
*/ 
class OrdenesMedicas_CDA extends Extenciones_CDA_HC
{
    /**
    * Variable que contendra el Parametro de Busqueda
    *
    * @var $datos
    * @access private
    */
    var $datos;
    
    /**
    * Variable que contendra el Parametro para el Metodo Busqueda
    *
    * @var $TipoMetodo
    * @access private
    */
    var $TipoMetodo;
 
    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public    
    */  
    function OrdenesMedicas_CDA()
    {
        $this->Extenciones_CDA_HC();
        return true;
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una EVOLUCION
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Evolucion($evolucion_id)
    {
          if (empty($evolucion_id))
          {
               return '';
          }
          else
          {
			$this->datos[evolucion] = $evolucion_id;
			$this->BuscarIngreso($evolucion_id);
               $XML = $this->GetXML_Local();
               $this->salida = $XML;
               return $this->salida;
          }
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una EPICRISIS DE UN INGRESO
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Epicrisis($ingreso)
    {
          if (empty($ingreso))
          {
               return '';
          }
          else
          {
			$this->ingreso = $ingreso;
               $this->BuscarIngreso($evolucion_id);

               $XML = $this->GetXML_Local();
               $this->salida = $XML;
               return $this->salida;
          }
    }    
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para un INGRESO
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Full_Ingreso($ingreso)
    {
          if (empty($ingreso))
          {
               return '';
          }
          else
          {
			$this->ingreso = $ingreso;
               $XML = $this->GetXML_Local();
               $this->salida = $XML;
               return $this->salida;
          }
    }    
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una HISTORIA CLINICA DE UN PACIENTE
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Full_Historia($paciente_id,$tipoidpaciente)
    {
          if (empty($paciente_id) || empty($tipoidpaciente))
          {
               return '';
          }
          else
          {
               $this->datos[paciente_id] = $paciente_id;
               $this->datos[tipoidpaciente] = $tipoidpaciente;
               $this->TipoMetodo = '4';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para un RESUMEN DE ATENCIONES DE UN PACIENTE
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Resumen_Historia($paciente_id,$tipoidpaciente)
    { 
          if (empty($paciente_id) || empty($tipoidpaciente))
          {
               return '';
          }
          else
          {
               $this->datos[paciente_id] = $paciente_id;
               $this->datos[tipoidpaciente] = $tipoidpaciente;
               $this->TipoMetodo = '5';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }        

	/*		GetXML_Local
     *
     *		Crea la vista de los datos en XML para su posterior traspaso
     *		a HTML y generacion de impresion.
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@param array => $XML_Consulta - Vector de datos.
     */

    function GetXML_Local()
    {
          $ctrlPosicion=array();
          $href_add="AddCtrlGral";
          $href_edit="EditCtrlGral";
          $href_del="DelCtrlGral";
          $controles=$this->GetControles();

          if (!IncludeLib('datospaciente')){
               $this->error = "Error al cargar la libreria [datospaciente].";
               $this->mensajeDeError = "datospaciente";
               return false;
          }

          if (!empty ($controles))
          {
               $salida.="<CAPTION><B>CONTROLES DE PACIENTES</B></CAPTION>";
          }

//------
          $ctrlPosicion=$this->FindControles($controles,1,$this->ingreso);
          if ($ctrlPosicion===false){
               return false;
          }
          if(!empty($ctrlPosicion['evolucion_id']))
          {
               $salida.= $this->ControlPosicion($ctrlPosicion);
          }
//------          
          $ctrlOxig=$this->FindControles($controles,2,$this->ingreso);
          if ($ctrlOxig===false){
               return false;
          }
		if(!empty($ctrlOxig['evolucion_id']))
          {
               $salida.=$this->ControlOxig($ctrlOxig);
          }
//------
          $ctrlReposo=$this->FindControles($controles,3,$this->ingreso);
          if ($ctrlReposo===false){
               return false;
          }
		if(!empty($ctrlReposo['evolucion_id']))
          {
               $salida.=$this->ControlReposo($ctrlReposo);
          }
//------
          $control=$this->FindControles($controles,4,$this->ingreso);
          if ($control===false){
               return false;
          }
          $datos=$this->GetAllControles("hc_terapias_respiratorias",$control);
          if (!empty($datos))
          {
          	$title ="TERAPIA RESPIRATORIA";
               $salida.= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_terapia_respiratoria","hc_terapias_respiratorias",$title);
          }
//------
          $control=$this->FindControles($controles,5,$this->ingreso);
          if ($control===false){
               return false;
          }
          $datos=$this->GetAllControles("hc_curvas_termicas",$control);
          if (!empty($datos))
          {
               $title ="CURVA TERMICA";
               $salida.= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curva_termica","hc_curvas_termicas",$title);
          }
//------
          $ctrlLiquidos=$this->FindControles($controles,6,$this->ingreso);
          if ($ctrlLiquidos===false){
               return false;
          }
          if (!empty($ctrlLiquidos['evolucion_id']))
          {
               $salida.=$this->ControlLiquidos($ctrlLiquidos);
          }
 //------         
          $control=$this->FindControles($controles,7,$this->ingreso);
          if ($control===false){
               return false;
          }
          $datos=$this->GetAllControles("hc_control_tension_arterial",$control);
          if (!empty($datos))
          {
               $title ="TENSION ARTERIAL";
               $salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_ta","hc_control_tension_arterial",$title);
          }
 //------
          $control=$this->FindControles($controles,8,$this->ingreso);
          if ($control===false){
               return false;
          }
          $datos=$this->GetAllControles("hc_control_glucometria",$control);
          if (!empty($datos))
          {
          	$title ="GLUCOMETRíA";
               $salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_glucometrias","hc_control_glucometria",$title);
          }
 //------
          $control=$this->FindControles($controles,9,$this->ingreso);
          if ($control===false){
               return false;
          }
          $datos=$this->GetAllControles("hc_control_curaciones",$control);
          if (!empty($datos))
          {
          	$title ="CURACIONES";
               $salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_curaciones","hc_control_curaciones",$title);
          }
//------
          $control=$this->FindControles($controles,10,$this->ingreso);
          if ($control===false){
               return false;
          }
          $datos=$this->GetAllControles("hc_control_neurologico",$control);
          if (!empty($datos))
          {
          	$title ="NEUROLOGICO";
               $salida .= $this->FrmControles($control,$datos,"hc_tipos_frecuencia_control_neurologico","hc_control_neurologico",$title);
          }
//------
          $datos_hc=GetDatosPaciente("","",$this->ingreso,"","");
          $data=$this->Gestacion($datos_hc);
print_r($data);
          if($data->estado)
          {
               $ctrlParto=$this->FindControles($controles,11,$this->ingreso);
               if ($ctrlParto===false){
                    return false;
               }                    
               if (!empty($ctrlParto['evolucion_id']))
			{
               	$salida.=$this->ControlParto($ctrlParto);
               }
		}
//------
          $ctrlPerAbdominal=$this->FindControles($controles,12,$this->ingreso);
          if ($ctrlPerAbdominal===false){
               return false;
          }
		if(!empty($ctrlPerAbdominal['evolucion_id']))
          {
          	$salida.= $this->ControlPerAbdominal($ctrlPerAbdominal);
     	}
//------          
          $ctrlPerCefalico=$this->FindControles($controles,13,$this->ingreso);
          if ($ctrlPerCefalico===false){
               return false;
          }
		if (!empty($ctrlPerCefalico['evolucion_id']))
          {
               $salida.= $this->ControlPerCefalico($ctrlPerCefalico);
          }
//------
          $ctrlPerExtremidades=$this->FindControles($controles,14,$this->ingreso);
          if ($ctrlPerExtremidades===false){
               return false;
          }
		if (!empty($ctrlPerExtremidades['evolucion_id']))
          {
          	$salida.= $this->ControlPerExtremidades($ctrlPerExtremidades);
          }
//------
          $ctrlDietas=$this->FindControles($controles,25,$this->ingreso);
          if ($ctrlDietas===false){
               return false;
          }
		if (!empty($ctrlDietas['evolucion_id']))
          {
               $salida.= $this->ControlDietas($ctrlDietas);
          }

          $ctrlTransfusiones=$this->FindControles($controles,24,$this->ingreso);
          if ($ctrlTransfusiones===false){
               return false;
          }
		if (!empty($ctrlTransfusiones['evolucion_id']))
          {
               $salida.= $this->ControlTransfusiones($ctrlTransfusiones);
     	}

          $salida.="<br>";
          $img="<img src='".GetThemePath()."/images/folder_vacio.png' border='0'>";
          $img2="<img src='".GetThemePath()."/images/folder_lleno.png' border='0'>";
          $salida=str_replace("$img","",$salida);
          $salida=str_replace("$img2","",$salida);
          return $salida;
    }

    
     function ControlPosicion($control)
     {
          $data=$this->GetCControlPosicion($control);
          if (!$data)
               return false;
          $controles=$this->GetControlPosicion($data->posicion_id,0);
          if (!empty($data->posicion_id))
          {
               $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">POSICION DEL PACIENTE</TH>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Posición</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$controles[0]['descripcion']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               if(!empty($data->observaciones)){
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Observación</TH>";
                    $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$data->observaciones."</TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
               }
               $salida.="</TABLE>";
          }
          return $salida;
     }
          
     function ControlOxig($control)
     {
          $data=$this->GetCOxigenoterapia($control);
          if (!$data)
               return false;
          
          $metodo=$this->GetControlOxiMetodo($data->metodo_id,0);
          $concentracion=$this->GetControlOxiConcentraciones($data->concentracion_id,0);
          $flujo=$this->GetControlOxiFlujo($data->flujo_id,0);
          $contador=1;

          $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
          $salida.="<TBODY>";
          $salida.="<TR>";
          $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">OXIGENOTERAPIA</TH>";
          $salida.="</TR>";
          $salida.="</TBODY>";

          if (!empty($data->metodo_id))
          {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Método</TH>\n";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$metodo[0]['descripcion']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $contador++;
          }
          if (!empty($data->concentracion_id))
          {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Concentración</TH>\n";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$concentracion[0]['descripcion']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $contador++;
          }
          if (!empty($data->flujo_id))
          {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Flujo</TH>\n";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$flujo[0]['descripcion']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $contador++;
          }
          if (!empty($data->observaciones))
          {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Observación</TH>\n";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$data->observaciones."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
          }
          $salida.="</TABLE>";
          return $salida;
     }
     
     function ControlReposo($control)
     {
          $reposo_d=$this->GetCControlReposoDetalle($control);
          if ($reposo_d===false || !is_array($reposo_d))
               return false;

          $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
          $salida.="<TBODY>";
          $salida.="<TR>";
          $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">REPOSO DEL PACIENTE</TH>";
          $salida.="</TR>";
          $salida.="</TBODY>";

          $salida.="<TBODY>";
          $salida.="<TR>";
          $salida.="<TH WIDTH=\"100%\" ALIGN=\"left\" COLSPAN=\"2\">Tipo de Reposo</TH>";
          $salida.="</TR>";
          $salida.="</TBODY>";

          foreach ($reposo_d as $key => $value)
          {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD WIDTH=\"100%\" ALIGN=\"justify\" COLSPAN=\"2\">".$value['descripcion']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
          }

          $data=$this->GetCControlReposo($control);
          if (!$data)
               return false;

          if (!empty($data->observaciones)) {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Observación</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$data->observaciones."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
          }
          $salida.="</TABLE>";
          return $salida;
     }

     
     function FrmControles($control,$datos_controles,$tabla_tipo,$tabla,$title)
     {
          $salida="";
          $controles=$this->GetAllTipoControles($tabla_tipo,$datos_controles['frecuencia_id'],0);
          if ($controles===false){
               return false;
          }
          $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
          $salida.="<TBODY>";
          $salida.="<TR>";
          $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">$title</TH>";
          $salida.="</TR>";
          $salida.="</TBODY>";
          if (!empty($datos_controles['frecuencia_id']))
          {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Frecuencia</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$controles['descripcion']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
          }
          if (!empty($datos_controles['observaciones']))
          {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Observación</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$datos_controles['observaciones']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
          }
          $salida.="</TABLE>";
          return $salida;
     }

     function ControlLiquidos($control)
     {
          $salida="";
          $data=$this->GetCControlLiquidos($control);
          if (!$data)
               return false;

          $controles=$this->GetControlLiquidos($control['evolucion_id']);
          if (!empty($controles[0]['observaciones']))
          {          
               $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">CONTROL DE LIQUIDOS INGERIDOS Y ELIMINADOS</TH>";
               $salida.="</TR>";
               $salida.="</TBODY>";

               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Descripción</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$controles[0]['observaciones']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $salida.="</TABLE>";
          }
          return $salida;
     }


     function ControlParto($control)
     {
          $salida="";
          $data=$this->GetCControlParto($control);
          if (!$data)
               return false;
          $controles=$this->GetControlParto($control['evolucion_id']);
          if (!empty($controles[0]['observaciones']))
          {   
               $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">CONTROL PARTO</TH>";
               $salida.="</TR>";
               $salida.="</TBODY>";

               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Descripción</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$controles[0]['observaciones']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $salida.="</TABLE>";
          }
          return $salida;
     }

		
     function ControlPerAbdominal($control)
     {
          $salida="";
          $data=$this->GetCPerimetroAbdominal($control);
          if (!$data)
               return false;

          $controles=$this->GetControlPerAbdominal($control['evolucion_id']);
          if (!empty($controles[0]['observaciones']))
          {
               $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">PERIMETRO ABDOMINAL</TH>";
               $salida.="</TR>";
               $salida.="</TBODY>";

               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Descripción</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$controles[0]['observaciones']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $salida.="</TABLE>";
          }
          return $salida;
     }
     
     
     function ControlPerCefalico($control)
     {
          $salida="";
          $data=$this->GetCPerimetroCefalico($control);
          if (!$data)
               return false;
          $controles=$this->GetControlPerCefalico($control['evolucion_id']);
          if (!empty($controles[0]['observaciones']))
          {
               $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">PERIMETRO CEFALICO</TH>";
               $salida.="</TR>";
               $salida.="</TBODY>";

               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Descripción</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$controles[0]['observaciones']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $salida.="</TABLE>";
          }
          return $salida;
     }


     function ControlPerExtremidades($control)
     {
          $salida="";
          $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
          
          $salida.="<TBODY>";
          $salida.="<TR>";
          $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">PERIMETRO DE EXTREMIDADES</TH>";
          $salida.="</TR>";
          $salida.="</TBODY>";
          
          $resultado=$this->GetCPerimetroExtremidadesDetalle($control);
          if ($resultado===false || !is_array($resultado))
               return false;

          $salida.="<TBODY>";
          $salida.="<TR>";
          $salida.="<TH WIDTH=\"100%\" ALIGN=\"left\" COLSPAN=\"2\">Tipo de Perimetro de extremidad</TH>";
          $salida.="</TR>";
          $salida.="</TBODY>";

          foreach ($resultado as $key => $value)
          {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD WIDTH=\"100%\" ALIGN=\"left\" COLSPAN=\"2\">".$value['descripcion']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
          }

          $data=$this->GetCPerimetroExtremidades($control);
          if ($data===false)
               return false;

          if (!empty($data->observaciones)) {
               
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Observación</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$data->observaciones."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
          }
          $salida.="</TABLE>";
          return $salida;
     }
          
     
     function ControlDietas($control)
     {
          $salida="";
          $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
          $dietas_d=$this->GetCControlDietasDetalle($control);
          
          $salida.="<TBODY>";
          $salida.="<TR>";
          $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">DIETAS DEL PACIENTE</TH>";
          $salida.="</TR>";
          $salida.="</TBODY>";

          if(sizeof($dietas_d)>1)
          {
               foreach ($dietas_d as $key => $value)
               {
                    $datos.=$value['descripcion'].",";
               }
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Tipo de Dieta</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$datos."</TD>";
               $salida.="</TR>";
               unset($datos);
               $salida.="</TBODY>";
          }
          else{
               foreach ($dietas_d as $key => $value)
               {
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Tipo de Dieta</TH>";
                    $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$value['descripcion']."</TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
               }
          }
          
          $data=$this->GetCControlDietas($control);
          if (!empty($data['observaciones'])) {
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Observación</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$data['observaciones']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
          }
          $salida.="</TABLE>";
          return $salida;
     }

          
     function ControlTransfusiones($control)
     {
          $salida="";
          $data=$this->GetCControlTransfusiones($control);
          if (!$data)
               return false;

          $controles=$this->GetControlTransfusiones($control['evolucion_id']);
          if (!empty($controles[0]['observaciones']))
          {
               $salida.="<TABLE WIDTH=\"100%\" BORDER=\"0\">";
               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"100%\" ALIGN=\"center\" COLSPAN=\"2\">CONTROL DE TRANSFUSIONES</TH>";
               $salida.="</TR>";
               $salida.="</TBODY>";

               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TH WIDTH=\"20%\" ALIGN=\"left\">Descripción</TH>";
               $salida.="<TD WIDTH=\"80%\" ALIGN=\"justify\">".$controles[0]['observaciones']."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               $salida.="</TABLE>";
          }
          return $salida;
     }
     
    
    function BuscarIngreso($evolucion)
    {
          list($dbconn) = GetDBconn();
          if(!empty($evolucion))
          {
               $query="SELECT ingreso
                    FROM hc_evoluciones
                    WHERE evolucion_id = $evolucion;";
          }
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($this->ingreso) = $resultado->FetchRow();
          return true;
    }
    
    /*
     * function Verifica_Conexion($query)
     * $query es el query que se quiere verificar
     * Se ejecuta el query y si existe algun error => se retorna falso de los contrario se devuelve el obj resultado
     * retorna el resultado del query
     */
     function Verifica_Conexion($query,$dbconn)
     {
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0){
               return false;
          }
          return $resultado;
     }//End function

		
     function GetControles()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $controles=array();
          $query="SELECT c.*, a.descripcion
                  FROM hc_controles_paciente c,
                  	   hc_tipos_controles_paciente a
                  WHERE c.ingreso=".$this->ingreso." AND
                  	    c.control_id=a.control_id";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado) {
               $this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resultado->FetchRow()){
               $controles[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $controles;
     }

	/**
	*		function FindControles => Se encarga de verificar de la tabla controles del paciente
	*		si existe  armar la vista (HTML) de los controles del paciente
	*
	*		Llama al metodo GetExamen() El cual se encarga de traer los controles que se le han ordenado al paciente
	*		@Author Arley Velásquez C.
	*		@access Private
	*		@param array Id del control
	*		@paran
	*		@return array Los datos del control
	*/
     function FindControles($control,$control_id,$ingreso)
     {
          list($dbconn) = GetDBconn();
          $controles=array();
          $flag=0;
          foreach($control as $key =>$value){
               if ($value['control_id']==$control_id && $value['ingreso']==$ingreso){
                    $flag=1;
                    return $value;
               }
          }
          if (!$falg){
               return $this->GetFindControles($control_id);
          }
     }
          
     function GetFindControles($control_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $controles=array();
          $query="SELECT *
                  FROM hc_tipos_controles_paciente
                  WHERE control_id='$control_id'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado) {
               $this->error = "Error al buscar el tipo de control en \"hc_tipos_controles_paciente\"<br>";
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          $controles = $resultado->FetchRow();
          if ($controles===false){
               $this->error = "Error al consultar la tabla";
               $this->mensajeDeError = "No se encuentran registros en \"hc_tipos_controles_paciente\".";
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $controles;
     }

     function GetCControlPosicion($control)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * 
          	   FROM hc_posicion_paciente 
                  WHERE evolucion_id=".$control['evolucion_id'];
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar las posiciones del paciente en \"hc_posicion_paciente\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }

     function GetControlPosicion($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          switch ($valor)
          {
               case 0:
                    $posicion=array();
                    $query = "SELECT * FROM hc_tipos_posicion_paciente WHERE posicion_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$this->Verifica_Conexion($query,$dbconn);
                         if (!$resultado) {
                              $this->error = "Error, no se encuentra el registro en \"hc_tipos_posicion_paciente\" con la posicion \"$posicion_id\"";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              return false;
                         }
                    while ($data = $resultado->FetchRow()) {
                         $posicion[]=$data;
                    }
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    return $posicion;
               break;
          }
     }

     function GetCOxigenoterapia($control)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * 
          	   FROM hc_oxigenoterapia 
                  WHERE evolucion_id=".$control['evolucion_id'];
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar las posiciones del paciente en \"hc_oxigenoterapia\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }

     function GetControlOxiMetodo($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $metodo=array();
                    $query = "SELECT * FROM hc_tipos_metodos_oxigenoterapia WHERE metodo_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$this->Verifica_Conexion($query,$dbconn);
                         if (!$resultado) {
                              $this->error = "Error, no se encuentra el registro en \"hc_tipos_metodos_oxigenoterapia\" con el metodo_id \"$posicion_id\"";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              return false;
                         }
                    while ($data = $resultado->FetchRow()) {
                         $metodo[]=$data;
                    }
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    return $metodo;
               break;
          }
     }
     
     function GetControlOxiConcentraciones($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $conc=array();
                    $query = "SELECT * FROM hc_tipos_concentracion_oxigenoterapia WHERE concentracion_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$this->Verifica_Conexion($query,$dbconn);
                         if (!$resultado) {
                              $this->error = "Error, no se encuentra el registro en \"hc_tipos_concentracion_oxigenoterapia\" con la concentracion_id \"$posicion_id\"";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              return false;
                         }
                    while ($data = $resultado->FetchRow()) {
                         $conc[]=$data;
                    }
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    return $conc;
               break;
          }
     }
     
     function GetControlOxiFlujo($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $flujo=array();
                    $query = "SELECT * FROM hc_tipos_flujos_oxigenoterapia WHERE flujo_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$this->Verifica_Conexion($query,$dbconn);
                         if (!$resultado) {
                              $this->error = "Error, no se encuentra el registro en \"hc_tipos_flujos_oxigenoterapia\" con el flujo_id \"$posicion_id\"";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              return false;
                         }
                    while ($data = $resultado->FetchRow()) {
                         $flujo[]=$data;
                    }
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    return $flujo;
               break;
          }
     }

     function GetCControlReposoDetalle($control)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query="SELECT reposo_d.*,
                         tipo_r.descripcion
                  FROM hc_reposo_paciente_detalle reposo_d,
                       hc_tipos_reposo_paciente tipo_r
                  WHERE reposo_d.evolucion_id=".$control['evolucion_id']." AND
                        tipo_r.tipo_reposo_id=reposo_d.tipo_reposo_id;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resultado->FetchRow()) {
               $reposo_d[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $reposo_d;
     }

     function GetCControlReposo($control)
     {
          list($dbconn) = GetDBconn();
          $query2="SELECT * 
          	    FROM hc_reposo_paciente 
                   WHERE evolucion_id=".$control['evolucion_id'];
          $resultado2=$this->Verifica_Conexion($query2,$dbconn);
          if (!$resultado2)
          {
               $this->error = "Error al consultar la tabla \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado2->FetchNextObject($toUpper=false);
     }
     
     
     function GetAllControles($tabla,$control)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $data=array();
          if (is_array($control) && !empty($control['evolucion_id'])){
               $query="SELECT * 
               	   FROM $tabla 
                       WHERE evolucion_id=".$control['evolucion_id'];
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado)
               {
                    $this->error = "Error al consultar la tabla \"$tabla\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
               $data=$resultado->FetchRow();
               if ($data===false){
                    $this->error = "Error al consultar la tabla";
                    $this->mensajeDeError = "No se encuentran registros en \"$tabla\".";
               }
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          }
          return $data;
     }
     
     
     function GetAllTipoControles($tabla,$frecuencia_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          switch ($valor)
          {
               case 0:
                    $ctrl_gral=array();
                    $query = "SELECT *
                                   FROM $tabla
                                   WHERE frecuencia_id='".$frecuencia_id."'";
                    
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$this->Verifica_Conexion($query,$dbconn);
                         if (!$resultado) {
                              $this->error = "Error, no se encuentra el registro en \"$tabla\" con la frecuencia_id \"$frecuencia_id\"";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              return false;
                         }
                    $ctrl_gral = $resultado->FetchRow();
                    if ($ctrl_gral===false){
                         $this->error = "Error al consultar la tabla";
                         $this->mensajeDeError = "No se encuentran registros en \"$tabla\".";
                    }
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    return $ctrl_gral;
               break;
          }
     }


     
     function GetCControlLiquidos($control)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * FROM hc_control_liquidos WHERE evolucion_id=".$control['evolucion_id'];
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_liquidos\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }
     

     function GetControlLiquidos($evolucion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $liquidos=array();
          $query = "SELECT * FROM hc_control_liquidos WHERE evolucion_id='".$evolucion_id."'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado) {
                    $this->error = "Error, no se encuentra el registro en \"hc_control_liquidos\" con la evolucion_id \"$evolucion_id\"";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
          while ($data = $resultado->FetchRow()) {
               $liquidos[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $liquidos;
     }

     function Gestacion($datos_hc)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * FROM gestacion WHERE tipo_id_paciente='".$datos_hc['tipoidpaciente']."' AND paciente_id='".$datos_hc['paciente_id']."' ";

          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado) {
               $this->error = "Error al ejecutar el query <br>".$query;
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }
     
     function GetCControlParto($control)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * FROM hc_control_trabajo_parto WHERE evolucion_id=".$control['evolucion_id'];
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_trabajo_parto\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }
     
     function GetControlParto($evolucion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $parto=array();
          $query = "SELECT * FROM hc_control_trabajo_parto WHERE evolucion_id='".$evolucion_id."'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado) {
               $this->error = "Error, no se encuentra el registro en \"hc_control_trabajo_parto\" con la evolucion_id \"$evolucion_id\"";
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resultado->FetchRow()) {
               $parto[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $parto;
     }

     
     function GetCPerimetroAbdominal($control)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id=".$control['evolucion_id'];
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_perimetro_abdominal\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }

     
     function GetControlPerAbdominal($evolucion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $perAbd=array();
          $query = "SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id='".$evolucion_id."'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado) {
               $this->error = "Error, no se encuentra el registro en \"hc_control_perimetro_abdominal\" con la evolucion_id \"$evolucion_id\"";
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resultado->FetchRow()){
               $perAbd[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $perAbd;
     }
     
     function GetCPerimetroCefalico($control)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id=".$control['evolucion_id'];
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_perimetro_cefalico\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }

     
     function GetControlPerCefalico($evolucion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $perCefalico=array();
          $query = "SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id='".$evolucion_id."'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado) {
                    $this->error = "Error, no se encuentra el registro en \"hc_control_perimetro_cefalico\" con la evolucion_id \"$evolucion_id\"";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
          while ($data = $resultado->FetchRow()) {
               $perCefalico[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $perCefalico;
     }
     
     function GetCPerimetroExtremidadesDetalle($control)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query="SELECT per_d.*,
                         tipo_ext.descripcion
                    FROM hc_control_perimetro_extremidades_detalle per_d,
                         hc_tipos_extremidades_paciente tipo_ext
                    WHERE per_d.evolucion_id=".$control['evolucion_id']." AND
                         tipo_ext.tipo_extremidad_id=per_d.tipo_extremidad_id";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_perimetro_extremidades_detalle\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resultado->FetchRow()) {
               $extremidades_d[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $extremidades_d;
     }

     
     function GetCPerimetroExtremidades($control)
     {
          list($dbconn) = GetDBconn();
          $query2="SELECT * FROM hc_control_perimetro_extremidades WHERE evolucion_id=".$control['evolucion_id'];
          $resultado2=$this->Verifica_Conexion($query2,$dbconn);
          if (!$resultado2)
          {
               $this->error = "Error al consultar la tabla \"hc_control_perimetro_extremidades\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado2->FetchNextObject($toUpper=false);
     }
     

     function GetCControlDietasDetalle($control)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query="SELECT dietas_d.*,
                         dietas.descripcion
                  FROM hc_solicitudes_dietas dietas_d,
                       hc_tipos_dieta dietas
                  WHERE dietas_d.evolucion_id=".$control['evolucion_id']." AND
                        dietas.hc_dieta_id=dietas_d.hc_dieta_id";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_dietas\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               echo "<br><br>ERROR<br>";
               return false;
          }
          while ($data = $resultado->FetchRow()) {
               $dietas_d[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $dietas_d;
     }
     
     function GetCControlDietas($control)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query="SELECT *
                  FROM hc_solicitudes_dietas
                  WHERE evolucion_id=".$control['evolucion_id'];
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_dietas_detalle\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $resultado->FetchRow();
     }


     function GetCControlTransfusiones($control)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * FROM hc_transfusiones WHERE evolucion_id=".$control['evolucion_id'];
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_transfusiones\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }

     
     function GetControlTransfusiones($evolucion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $transfusiones=array();
          $query = "SELECT * FROM hc_transfusiones WHERE evolucion_id='".$evolucion_id."'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado) {
               $this->error = "Error, no se encuentra el registro en \"hc_transfusiones\" con la evolucion_id \"$evolucion_id\"";
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resultado->FetchRow()){
               $transfusiones[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $transfusiones;
     }

          
     /*		FechaStamp
     *
     *		Convierte los datos en Fechas a partir de la Fecha Registro.
     *
     *		@Author Alexander Giraldo.
     *		@access Public
     *		@param integer => fecha_registro
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

     /*		HoraStamp
     *
     *		Convierte los datos en Horas a partir de la Fecha Registro.
     *
     *		@Author Alexander Giraldo.
     *		@access Public
     *		@param integer => fecha_registro
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
     
     /*		GetDatosUsuarioSistema
     *
     *		Obtiene el nombre de usuario del sistema
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@return bool
     *		@param integer => usuario_id
     */
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

}//fin de la clase

?>
