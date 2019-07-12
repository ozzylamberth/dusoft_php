<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ficha_datos_basicos.report.php,v 1.2 2009/11/06 14:51:34 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  /**
  * Clase Reporte: ficha_datos_basicos 
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  includeClass("AutoCarga");
  includeClass("ConexionBD");
	class ficha_datos_basicos_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
		var $datos;
		
		//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
		//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
		var $title       = '';
		var $author      = '';
		var $sizepage    = 'leter';
		var $Orientation = '';
		var $grayScale   = false;
		var $headers     = array();
		var $footers     = array();
		
		/**
    * Contructor de la clase
    * 
    * @param array $datos
    *
    * @return boolean
    */
    function ficha_datos_basicos_report($datos=array())
		{
			$this->datos=$datos;
			return true;
		}
    /**
    * Funcion que coloca el menbrete del reporte
    *
    * @return array
    **/
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$html,
							  'subtitulo'=>' ',
							  'logo'=>'',
							  'align'=>'left'));
			return $Membrete;
		}
		/**
    * Funcion que retorna el html del reporte (lo que va dentro del tag <body>)
		*
    * @return String
    */
    function CrearReporte()
		{
      $mdl = AutoCarga::factory('DiagnosticosdeVIHSQL', '', 'hc1', 'DiagnosticosdeVIH');
      
      $ficha = $mdl->ObtenerFichaNotificacion($this->datos['paciente']['paciente_id'],$this->datos['paciente']['tipo_id_paciente']);
      $area = $mdl->ConsultarAreasProcedencia($ficha['area_procedencia_id']);
      $fichavih = $mdl->ObtenerFichaVIH($ficha['ficha_notificacion_id']);
      $mecanismo = $mdl->ObtenerMecanismo($fichavih['ficha_notif_det_id']);
      
      
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
      $sty = " style=\"text-align:left;text-indent:8pt\" ";
      $stl = " style=\"border-collapse:collapse\" ";
      $sts = " style=\"border: medium none\" ";
      $td = " style=\"border: 1pt solid; padding: 0cm 0cm;\" ";
      $td1 = " style=\"border-top: 1pt solid #000000\" ";
      $td2 = " style=\"border-left: 1pt solid #000000\" ";
      $td3 = " style=\"border-left: 1pt solid #000000; border-bottom: 1pt solid #000000\" ";
      $td4 = " style=\"border-left: 1pt solid #000000; border-bottom: 1pt solid #000000; border-right: 1pt solid #000000\" ";
      
      $st3 = " style=\"font-size:80%; font-family: sans_serif, Verdana, helvetica, Arial;\" ";
      $html .= "<table width=\"100%\" class=\"normal_10\" $td>\n";
      $html .= "  <tr>\n";
      $html .= "    <td ><img src=\"images/inds.png\" height=\"55\"></td>\n";
      $html .= "    <td align=\"center\"><b>SISTEMA NACIONAL DE VIGILANCIA EN SALUD PUBLICA</b><BR>SUBSISTEMA DE INFORMACION<BR>FICHA DE NOTIFICACION DE DATOS BASICOS</td>\n";
      $html .= "    <td align=\"left\"><b>Ministerio de la proteccion social</b><br>República de Colombia</td>\n";
      $html .= "    <td ><img src=\"images/escudo.png\" height=\"55\"></td>\n";
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
      $html .= "<table width=\"100%\" class=\"label\" border=\"1\" $sts rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"20\" class=\"label\" align=\"center\">1. INFORMACIÓN GENERAL</td>\n";
      $html .= "  </tr>\n";      
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"7\" rowspan=\"3\" valign=\"top\" class=\"label\">1.1. Nombre del evento:<br><label class=\"normal_10\">".$ficha['nombre_evento']."</label></td>\n";
      $html .= "    <td colspan=\"4\">&nbsp</td>\n";
      $html .= "    <td $td colspan=\"8\" class=\"label\">1.2. Fecha de notificación:</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $longitud = strlen($ficha['cod_evento']);
      if($longitud < 4)
      {
        for($i = 0; $i<(4-$longitud) ; $i++ )
          $html .= "    <td $td width=\"3%\"></td>\n"; 
      }

      for($i = 0; $i<$longitud ; $i++ )
        $html .= "    <td $td width=\"3%\" align=\"center\"><label class=\"normal_10\">".$ficha['cod_evento']{$i}."</label></td>\n";  
      
      $longitud = strlen($ficha['fecha_notificacion']);
      for($i = 0; $i<$longitud ; $i++ )
        $html .= "    <td $td width=\"3%\" align=\"center\"><label class=\"normal_10\">".$ficha['fecha_notificacion']{$i}."</label></td>\n";  
        
      $html .= "  </tr>\n";   
      $html .= "  <tr ".$st3.">\n";
      $html .= "    <td $td colspan=\"4\" align=\"center\">Código</td>\n";         
      $html .= "    <td $td colspan=\"2\" align=\"center\">Día</td>\n";         
      $html .= "    <td $td colspan=\"2\" align=\"center\">Mes</td>\n";         
      $html .= "    <td $td colspan=\"4\" align=\"center\">Año</td>\n";         
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"2\" width=\"17%\" class=\"label\">1.3. Semana*</td>\n";
      $html .= "    <td $td colspan=\"4\" width=\"17%\" class=\"label\">1.4. Año:</td>\n";
      $html .= "    <td $td rowspan=\"3\" class=\"label\" valign=\"top\" width=\"30%\">1.5 Departamento que notifica<br><label class=\"normal_10\">".$ficha['departamento_notifica']."</label></td>\n";
      $html .= "    <td $td rowspan=\"3\" class=\"label\" valign=\"top\" colspan=\"12\">1.6 Municipio que notifica<br><label class=\"normal_10\">".$ficha['municipio_notifica']."</label></td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      
      $longitud = strlen($ficha['semana']);
      if($longitud < 2)
        $html .= "    <td $td width=\"3%\"></td>\n"; 
      
      for($i = 0; $i<$longitud ; $i++ )
        $html .= "    <td $td width=\"3%\" align=\"center\"><label class=\"normal_10\">".$ficha['semana']{$i}."</label></td>\n";  
      
      $longitud = strlen($ficha['anyo']);
      for($i = 0; $i<$longitud ; $i++ )
        $html .= "    <td $td width=\"3%\" align=\"center\"><label class=\"normal_10\">".$ficha['anyo']{$i}."</label></td>\n";  
         
      $html .= "  </tr>\n";
      $html .= "  <tr ".$st3.">\n";
      $html .= "    <td $td colspan=\"2\" align=\"center\">*Epidemiológica</td>\n";         
      $html .= "    <td $td colspan=\"4\" align=\"center\">Año</td>\n";         
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"7\" rowspan=\"3\" valign=\"top\" class=\"label\">1.7. Razón social de la unidad primaria generadora del dato(UPGD)<br><label class=\"normal_10\">".$ficha['razon_social']."</label></td>\n";
      $html .= "    <td $td colspan=\"12\" class=\"label\">1.8 Código de la UPGD</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $longitud = strlen($ficha['codigo_sgsss']);

      for($i = 0; $i<$longitud ; $i++ )
        $html .= "    <td $td width=\"3%\" align=\"center\"><label class=\"normal_10\">".$ficha['codigo_sgsss']{$i}."</label></td>\n";  
      
      if($longitud < 12)
      {
        for($i = 0; $i<(12-$longitud) ; $i++ )
          $html .= "    <td $td width=\"3%\"></td>\n"; 
      }
      $html .= "  </tr>\n";  
      $html .= "  <tr ".$st3.">\n";
      $html .= "    <td $td colspan=\"2\" align=\"center\">Depto.</td>\n";         
      $html .= "    <td $td colspan=\"3\" align=\"center\">Municipio</td>\n";         
      $html .= "    <td $td colspan=\"5\" align=\"center\">Código</td>\n";         
      $html .= "    <td $td colspan=\"2\" align=\"center\">Sub.</td>\n";         
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
      $html .= "<table width=\"100%\" border=\"1\" $sts rules=\"all\" cellpading=\"-1\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"13\" class=\"label\" align=\"center\">2. IDENTIFICACIÓN DEL PACIENTE</td>\n";
      $html .= "  </tr>\n";       
      $html .= "  <tr>\n"; 
      $html .= "    <td $td class=\"label\" valign=\"top\" width=\"34%\" colspan=\"2\">2.1 Primer nombre:<br><label class=\"normal_10\">".$this->datos['paciente']['primer_nombre']."</label></td>\n";
      $html .= "    <td $td class=\"label\" valign=\"top\" width=\"33%\" colspan=\"10\">2.2 Segundo nombre:<br><label class=\"normal_10\">".$this->datos['paciente']['segundo_nombre']."</label></td>\n";
      $html .= "    <td $td class=\"label\" valign=\"top\" width=\"33%\">2.3 Pimer apellido:<br><label class=\"normal_10\">".$this->datos['paciente']['primer_apellido']."</label></td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n"; 
      $html .= "    <td $td class=\"label\" valign=\"top\" colspan=\"2\">2.4 Segundo apellido:<br><label class=\"normal_10\">".$this->datos['paciente']['segundo_apellido']."&nbsp;</label></td>\n";
      $html .= "    <td $td class=\"label\" valign=\"top\" colspan=\"10\">2.5 Teléfono:<br><label class=\"normal_10\">".$ficha['residencia_telefono']."</label></td>\n";
      $html .= "    <td $td class=\"label\" valign=\"top\" >\n";
      $html .= "      2.6 Fecha de nacimiento:<br>\n";
      $html .= "      <table width=\"80%\" align=\"center\" $sts rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "        <tr class=\"normal_10\">\n";
      $f = explode("-",$this->datos['paciente']['fecha_nacimiento']);
      
      $html .= "          <td $td width=\"12%\" align=\"center\">".$f[2]{0}."</td>\n";  
      $html .= "          <td $td width=\"13%\" align=\"center\">".$f[2]{1}."</td>\n";  
      $html .= "          <td $td width=\"12%\" align=\"center\">".$f[1]{0}."</td>\n";  
      $html .= "          <td $td width=\"13%\" align=\"center\">".$f[1]{1}."</td>\n";       
      $html .= "          <td $td width=\"12%\" align=\"center\">".$f[0]{0}."</td>\n";  
      $html .= "          <td $td width=\"13%\" align=\"center\">".$f[0]{1}."</td>\n";  
      $html .= "          <td $td width=\"12%\" align=\"center\">".$f[0]{2}."</td>\n";  
      $html .= "          <td $td width=\"13%\" align=\"center\">".$f[0]{3}."</td>\n";       
      $html .= "        </tr>\n";   
      $html .= "        <tr ".$st3.">\n";
      $html .= "          <td $td colspan=\"2\" align=\"center\">Día</td>\n";         
      $html .= "          <td $td colspan=\"2\" align=\"center\">Mes</td>\n";         
      $html .= "          <td $td colspan=\"4\" align=\"center\">Año</td>\n";         
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n"; 
      $html .= "    <td $td class=\"label\" valign=\"top\" colspan=\"12\">2.7 Tipo de documento de identificación:<br>".$ficha['tipo_de_documento']."</td>\n";
      $html .= "    <td $td class=\"normal_10\" valign=\"top\" ><b>2.8 Número de identificación:</b><br>".$this->datos['paciente']['paciente_id']."</td>\n";
      $html .= "  </tr>\n";
      
      $label_edad = $label_unidad = "";
      $edad = explode(":",$ficha['edad']);
      if($edad[0] == 0)
      {
        if($edad[1] == 0)
        {
          $label_edad = $edad[2];
          $label_unidad = "Días";
        }
        else
        {
          $label_edad = $edad[1];
          $label_unidad = "Meses";
        }
      }
      else
      {
        $label_edad = $edad[0];
        $label_unidad = "Años";
      }
      $html .= "  <tr>\n"; 
      $html .= "    <td $td class=\"normal_10\" valign=\"top\" width=\"10%\"><b>2.9 Edad:</b><br>".$label_edad."</td>\n";
      $html .= "    <td $td class=\"normal_10\" valign=\"top\" ><b>2.10 Unidad de medidad de la edad:</b><br>".$label_unidad."</td>\n";
      $html .= "    <td $td class=\"normal_10\" valign=\"top\" colspan=\"10\" ><b>2.11 Sexo:</b><br>".$mdl->ObtenerDescripcionGenero($this->datos['paciente']['sexo_id'])."</td>\n";
      $html .= "    <td $td class=\"normal_10\" valign=\"top\" ><b>2.12 País de procedencia del caso:</b><br>".$ficha['pais_procedencia']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"normal_10\">\n"; 
      $html .= "    <td $td valign=\"top\" colspan=\"2\" rowspan=\"2\"><b>2.13 Departamento/municipio procedencia del caso:</b><br>".$ficha['departamento_procedencia']." - ".$ficha['municipio_procedencia']."</td>\n";
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['proce_tipo_dpto_id']{0}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['proce_tipo_dpto_id']{1}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['proce_tipo_mpio_id']{0}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['proce_tipo_mpio_id']{1}."</td>\n";       
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['proce_tipo_mpio_id']{2}."</td>\n";  
      $html .= "    <td $td valign=\"top\" rowspan=\"2\" colspan=\"5\"><b>2.14 Area procedencia del caso:</b><br>".$area[0]['descripcion']."</td>\n";
      $html .= "    <td $td valign=\"top\" rowspan=\"2\"><b>2.15 Barrio/localidad procedencia:</b><br>".$ficha['proce_barrio']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr ".$st3.">\n";
      $html .= "    <td $td colspan=\"2\" align=\"center\">Depto.</td>\n";         
      $html .= "    <td $td colspan=\"3\" align=\"center\">Municipio</td>\n";         
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"normal_10\">\n"; 
      $html .= "    <td $td valign=\"top\" colspan=\"2\" rowspan=\"2\"><b>2.16 Direccion residencia:</b><br>".$ficha['residencia_direccion']."&nbsp;</td>\n";
      $html .= "    <td $td valign=\"top\" colspan=\"6\" rowspan=\"2\"><b>2.17 Ocupacion del paciente:</b><br>".$mdl->ObtenerDescripcionOcupacion($ficha['ocupacion_id'])."</td>\n";
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['ocupacion_id']{0}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['ocupacion_id']{1}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['ocupacion_id']{2}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['ocupacion_id']{3}."</td>\n";
      $html .= "    <td $td valign=\"top\" rowspan=\"2\" ><b>2.18 Tipo de régimen en salud:</b><br>".$mdl->ObtenerDescripcionRegimen($ficha['tipo_regimen_id'])."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr ".$st3.">\n";
      $html .= "    <td $td colspan=\"4\" align=\"center\">Código</td>\n";  
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"normal_10\">\n"; 
      $html .= "    <td $td valign=\"top\" colspan=\"6\" rowspan=\"2\"><b>2.19 Nombre de la administradora de servicios de salud:</b><br>".$ficha['nombre_admin_serv']."</td>\n";
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['cod_admin_serv']{0}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['cod_admin_serv']{1}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['cod_admin_serv']{2}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['cod_admin_serv']{3}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['cod_admin_serv']{4}."</td>\n";
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['cod_admin_serv']{5}."</td>\n";
      $html .= "    <td $td valign=\"top\" rowspan=\"2\" ><b>2.20 Pertenencia étnica:</b><br>".$mdl->ObtenerDescripcionEtnia($ficha['pert_etnica_id'])."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr ".$st3.">\n";
      $html .= "    <td $td colspan=\"6\" align=\"center\">Código</td>\n";  
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"normal_10\">\n";
      $html .= "    <td $td colspan=\"13\" ><b>2.21 Grupo poblacional:</b><br>".$mdl->ObtenerDescripcionGrupoPoblacional($ficha['grupo_poblacional_id'])."</td>\n";
      $html .= "  </tr>\n"; 
      $html .= "</table><br>\n";
      $html .= "<table width=\"100%\" border=\"1\" $sts rules=\"all\" cellpading=\"-1\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"30\" class=\"label\" align=\"center\">3. NOTIFICACIÓN</td>\n";
      $html .= "  </tr>\n";       
      $html .= "  <tr class=\"normal_10\">\n"; 
      $html .= "    <td $td valign=\"top\" colspan=\"9\" rowspan=\"3\"><b>3.1 Departamento y municipio de residencia del paciente:</b><br>".$ficha['departamento_residencia']." - ".$ficha['municipio_residencia']."</td>\n";
      $html .= "    <td $td colspan=\"5\">&nbsp;</td>\n";  
      $html .= "    <td $td class=\"label\" valign=\"top\" colspan=\"8\">3.2 Fecha de consulta</td>\n";
      $html .= "    <td $td class=\"label\" valign=\"top\" colspan=\"8\">3.3 Inicio de sintomas</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"normal_10\">\n"; 
      $html .= "    <td $td width=\"3%\">".$ficha['tipo_dpto_id']{0}."</td>\n";  
      $html .= "    <td $td width=\"3%\">".$ficha['tipo_dpto_id']{1}."</td>\n";  
      $html .= "    <td $td width=\"3%\">".$ficha['tipo_mpio_id']{0}."</td>\n";  
      $html .= "    <td $td width=\"3%\">".$ficha['tipo_mpio_id']{1}."</td>\n";       
      $html .= "    <td $td width=\"3%\">".$ficha['tipo_mpio_id']{2}."</td>\n";  
      for($i=0; $i<8 ;$i++)
        $html .= "    <td $td width=\"3%\" align=\"center\" >".$ficha['fecha_consulta']{$i}."</td>\n";       
      
      for($i=0; $i<8 ;$i++)
        $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['fecha_inicio_sintomas']{$i}."</td>\n";  
 
      $html .= "  </tr>\n";
      $html .= "  <tr ".$st3.">\n";
      $html .= "    <td $td colspan=\"2\" align=\"center\">Depto.</td>\n";         
      $html .= "    <td $td colspan=\"3\" align=\"center\">Municipio</td>\n";
      $html .= "    <td $td colspan=\"2\" align=\"center\">Día</td>\n";         
      $html .= "    <td $td colspan=\"2\" align=\"center\">Mes</td>\n";         
      $html .= "    <td $td colspan=\"4\" align=\"center\">Año</td>\n";       
      $html .= "    <td $td colspan=\"2\" align=\"center\">Día</td>\n";         
      $html .= "    <td $td colspan=\"2\" align=\"center\">Mes</td>\n";         
      $html .= "    <td $td colspan=\"4\" align=\"center\">Año</td>\n";        
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"label\">\n"; 
      $html .= "    <td $td valign=\"top\" colspan=\"17\" rowspan=\"3\"><b>3.4 Clasificación inicial del caso</b><br>".$mdl->ObtenerDescripcionCaso($ficha['caso_sintoma_id'])."</td>\n";
      $html .= "    <td $td valign=\"top\" colspan=\"5\" rowspan=\"3\"><b>3.5 Hospitalizado</b><br>".$ficha['estado_hospi']."</td>\n";
      $html .= "    <td $td valign=\"top\" colspan=\"8\"><b>3.3 Fecha de hospitalización</b></td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      if($ficha['fecha_hospitalizacion'])
      {
        for($i=0; $i<8 ;$i++)
          $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['fecha_hospitalizacion']{$i}."</td>\n";  
      }
      else
      {
        for($i=0; $i<8 ;$i++)
          $html .= "    <td $td width=\"3%\">&nbsp;</td>\n";  
      }       
      $html .= "  </tr>\n";
      $html .= "  <tr ".$st3.">\n";     
      $html .= "    <td $td colspan=\"2\" align=\"center\">Día</td>\n";         
      $html .= "    <td $td colspan=\"2\" align=\"center\">Mes</td>\n";         
      $html .= "    <td $td colspan=\"4\" align=\"center\">Año</td>\n";        
      $html .= "  </tr>\n";
      $html .= "  <tr >\n"; 
      $html .= "    <td $td class=\"normal_10\" valign=\"top\" rowspan=\"3\"><b>3.7 Condición final</b><br>".$ficha['condicion_final']."</td>\n";
      $html .= "    <td $td class=\"normal_10\" valign=\"top\" colspan=\"8\"><b>3.8 Fecha de defunción</b></td>\n";
      $html .= "    <td $td class=\"normal_10\" valign=\"top\" colspan=\"8\" rowspan=\"3\"><b>3.9 No. certificado defunción</b><br>".$ficha['certificado_defuncion']."</td>\n";
      $html .= "    <td $td class=\"normal_10\" valign=\"top\" colspan=\"9\" rowspan=\"3\"><b>3.10 Causa básica de muerte</b><br>".$ficha['causa_muerte']."</td>\n";
      $html .= "    <td $td ".$st3." align=\"center\" colspan=\"4\">CIE10</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"normal_10\">\n";
      if($ficha['fecha_defuncion'])
      {
        for($i=0; $i<8 ;$i++)
          $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['fecha_defuncion']{$i}."</td>\n";  
      }
      else
      {
        for($i=0; $i<8 ;$i++)
          $html .= "    <td $td width=\"3%\">&nbsp;</td>\n";  
      }
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['codigo_cie10']{0}."&nbsp;</td>\n";       
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['codigo_cie10']{1}."</td>\n";      
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['codigo_cie10']{2}."</td>\n";  
      $html .= "    <td $td width=\"3%\" align=\"center\">".$ficha['codigo_cie10']{3}."</td>\n";       
      $html .= "  </tr>\n";
      $html .= "  <tr ".$st3.">\n";     
      $html .= "    <td $td colspan=\"2\" align=\"center\">Día</td>\n";         
      $html .= "    <td $td colspan=\"2\" align=\"center\">Mes</td>\n";         
      $html .= "    <td $td colspan=\"4\" align=\"center\">Año</td>\n";        
      $html .= "    <td $td colspan=\"4\" align=\"center\">&nbsp;</td>\n";        
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
			$html .= "<table width=\"100%\" border=\"0\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"33\" class=\"label\" align=\"center\">ESPACIO EPARA USO EXCLUSIVO DE LOS ENTES TERRITORIALES - AJUSTES</td>\n";
      $html .= "  </tr>\n";       
      $html .= "  <tr>\n";
      $html .= "    <td $td2 colspan=\"25\" class=\"label\" >A. Seguimiento y clasificación final del caso</td>\n";
      $html .= "    <td $td colspan=\"8\" class=\"label\" align=\"center\">B. Fecha de ajuste</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td2 width=\"3%\">&nbsp;</td>\n";  
      $html .= "    <td $td align=\"center\" width=\"3%\" class=\"label\">0</td>\n";  
      $html .= "    <td width=\"9%\" colspan=\"3\" ".$st3.">No aplica</td>\n";  
      $html .= "    <td $td align=\"center\" width=\"3%\" class=\"label\">3</td>\n";  
      $html .= "    <td width=\"9%\" colspan=\"3\" ".$st3.">Conf. Laboratorio</td>\n";  
      $html .= "    <td $td align=\"center\" width=\"3%\" class=\"label\">4</td>\n";  
      $html .= "    <td width=\"9%\" colspan=\"3\" ".$st3.">Conf. Clinica</td>\n";  
      $html .= "    <td $td align=\"center\" width=\"3%\" class=\"label\">5</td>\n";  
      $html .= "    <td width=\"9%\" colspan=\"3\" ".$st3.">Conf. Nexo epidemiológico</td>\n";  
      $html .= "    <td $td align=\"center\" width=\"3%\" class=\"label\">6</td>\n";  
      $html .= "    <td width=\"9%\" colspan=\"3\" ".$st3.">Descartado</td>\n";  
      $html .= "    <td $td align=\"center\" width=\"3%\" class=\"label\">7</td>\n";  
      $html .= "    <td colspan=\"3\" ".$st3.">Otra actualización</td>\n";  
      $html .= "    <td $td3 width=\"3%\">&nbsp;</td>\n";       
      $html .= "    <td $td3 width=\"3%\">&nbsp;</td>\n";      
      $html .= "    <td $td3 width=\"3%\">&nbsp;</td>\n";  
      $html .= "    <td $td3 width=\"3%\">&nbsp;</td>\n";       
      $html .= "    <td $td3 width=\"3%\">&nbsp;</td>\n";       
      $html .= "    <td $td3 width=\"3%\">&nbsp;</td>\n";      
      $html .= "    <td $td3 width=\"3%\">&nbsp;</td>\n";  
      $html .= "    <td $td4 width=\"3%\">&nbsp;</td>\n";       
      $html .= "  </tr>\n";     
      $html .= "  <tr ".$st3.">\n"; 
      $html .= "    <td $td3 colspan=\"25\" >&nbsp;</td>\n";
      $html .= "    <td $td3 colspan=\"2\" align=\"center\">Día</td>\n";         
      $html .= "    <td $td3 colspan=\"2\" align=\"center\">Mes</td>\n";         
      $html .= "    <td $td4 colspan=\"4\" align=\"center\">Año</td>\n";        
      $html .= "  </tr>\n";      
      $html .= "</table><br>\n";
      $html .= "<div style=\"page-break-before: always\">\n";
      $html .= "<table width=\"100%\" class=\"normal_10\" $td>\n";
      $html .= "  <tr>\n";
      $html .= "    <td ><img src=\"images/inds.png\" height=\"55\"></td>\n";
      $html .= "    <td align=\"center\"><b>SISTEMA NACIONAL DE VIGILANCIA EN SALUD PUBLICA</b><BR>SUBSISTEMA DE INFORMACION<BR>FICHA DE NOTIFICACIÓN DE DATOS COMPLEMENTARIOS</td>\n";
      $html .= "    <td align=\"left\"><b>Ministerio de la proteccion social</b><br>República de Colombia</td>\n";
      $html .= "    <td ><img src=\"images/escudo.png\" height=\"55\"></td>\n";
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
      $html .= "<table width=\"100%\" border=\"1\" $sts rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td class=\"label\" align=\"center\">VIH/Sida | Código INS 850</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<center ".$st3.">\n";
      $html .= "Diligencie esta ficha para solicitar una prueba confirmatoria de VIH, para solicitar una prueba confirmatoria de VIH se requieren dos pruebas de tamizaje previamente reactivas\n";
      $html .= "</center>\n";
      $html .= "<table width=\"100%\" border=\"1\" $sts rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"3\" class=\"label\" align=\"center\">RELACIÓN CON DATOS BASICOS</td>\n";
      $html .= "  </tr>\n";      
      $html .= "  <tr class=\"normal_10\">\n";
      $html .= "    <td $td valign=\"top\" ><b>A. Nombres y apellidos del paciente:</b><br>".$this->datos['paciente']['primer_nombre']." ".$this->datos['paciente']['segundo_nombre']." ".$this->datos['paciente']['primer_apellido']." ".$this->datos['paciente']['segundo_apellido']."</td>\n";
      $html .= "    <td $td valign=\"top\" ><b>B. Tipo de ID*</b><br>".$this->datos['paciente']['tipo_id_paciente']."</td>\n";
      $html .= "    <td $td valign=\"top\" ><b>C. No. de identificación</b><br>".$this->datos['paciente']['paciente_id']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"3\">\n";
      $html .= "      <label ".$st3."> * TIPO DE ID: 1-RC: REGISTRO CIVIL | 2-TI: TARJETA DE ID | 3-CC: CÉDULA EXTRANJERIA | 5-PA: PASAPORTE | 6-MS: MENOR SIN ID | 7-AS: ADULTO SIN ID</label>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n"; 
      $html .= "</table><br>\n";
      $html .= "<table width=\"100%\" border=\"1\" $sts rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"2\" class=\"label\" align=\"center\">4. ANTECEDENTES EPIDEMIOLOGICOS</td>\n";
      $html .= "  </tr>\n";       
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"2\" class=\"label\" >4.1 Mecanismos probable de transmisión</td>\n";
      $html .= "  </tr>\n";      
      $html .= "  <tr class=\"normal_10\">\n";
      $html .= "    <td $td valign=\"top\" ><b>Sexual<br>Tendencia</b> ".$mecanismo['sexual']['descripcion']."&nbsp;</td>\n";
      $html .= "    <td $td valign=\"top\" ><b>Perinatal</b><br>".$mecanismo['perinatal']['descripcion']."&nbsp;</td>\n";
      $html .= "  </tr>\n";      
      $html .= "  <tr class=\"normal_10\">\n";
      $html .= "    <td $td valign=\"top\" ><b>Parenteral</b><br>".$mecanismo['parenteral']['descripcion']."&nbsp;</td>\n";
      $html .= "    <td $td valign=\"top\" ><b>Otros</b><br>".$mecanismo['otros']['descripcion']."&nbsp;</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";

      $html .= "<table width=\"100%\" border=\"1\" $sts rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"10\" class=\"label\" align=\"center\">5. DIAGNÓSTICO DE LABORATORIO</td>\n";
      $html .= "  </tr>\n";            
      $html .= "  <tr class=\"normal_10\">\n";
      $html .= "    <td $td valign=\"top\" rowspan=\"3\" ><b>5.1 Tipo de prueba</b><br>".$mdl->ObtenerDescripcionTipoPrueba($fichavih['ficha_notif_det_id'])."</td>\n";
      $html .= "    <td $td valign=\"top\" class=\"label\" colspan=\"8\">5.2 Fecha de resultado<br></td>\n";
      $html .= "    <td $td valign=\"top\" rowspan=\"3\" ><b>5.3 Valor de la carga viral</b><br>".$fichavih['val_carga_viral']."</td>\n";
      $html .= "  </tr>\n";      
      $html .= "  <tr class=\"normal_10\">\n";
      for($i=0; $i<8; $i++)
        $html .= "    <td $td width=\"3%\">".$fichavih['fecha_resultado']{$i}."</td>\n";  

      $html .= "  </tr>\n";
      $html .= "  <tr ".$st3.">\n";     
      $html .= "    <td $td colspan=\"2\" align=\"center\">Día</td>\n";         
      $html .= "    <td $td colspan=\"2\" align=\"center\">Mes</td>\n";         
      $html .= "    <td $td colspan=\"4\" align=\"center\">Año</td>\n";        
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
      
      $html .= "<table width=\"100%\" border=\"1\" $sts rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"3\" class=\"label\" align=\"center\">6. INFORMACIÓN CLÍNICA</td>\n";
      $html .= "  </tr>\n";         
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"3\" class=\"label\" >A. Estado clínico</td>\n";
      $html .= "  </tr>\n";            
      $html .= "  <tr class=\"normal_10\">\n";
      $html .= "    <td $td valign=\"top\" width=\"30%\"><b>6.1 Estado clínico</b><br>".$mdl->ObtenerDescripcionEC($fichavih['ficha_notif_det_id'])."</td>\n";
      $html .= "    <td $td valign=\"top\" width=\"70%\">\n";
      $html .= "      <b>6.2 Número de hijos menores de 18 años</b><br>\n";
      $html .= "      <table width=\"100%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "        <tr ".$st3.">\n";
      $html .= "          <td width=\"70%\" rowspan=\"2\">\n";
      $html .= "            Indique el número de hijos del paciente; menores de 18 años hombres y mujeres, según corresponda a los espacios señalados\n";  
      $html .= "          </td>\n";  
      $html .= "          <td $td2 width=\"15%\" class=\"normal_10\" align=\"center\">".(($fichavih['no_hijos_menores'] == 0)? "&nbsp":$fichavih['no_hijos_menores'])."</td>\n";  
      $html .= "          <td $td2 width=\"15%\" class=\"normal_10\" align=\"center\">".(($fichavih['no_hijas_menores'] == 0)? "&nbsp":$fichavih['no_hijas_menores'])."</td>\n";     
      $html .= "        </tr>\n";   
      $html .= "        <tr ".$st3.">\n";
      $html .= "          <td $td2 align=\"center\" valign=\"bottom\">Hombres</td>\n";         
      $html .= "          <td $td2 align=\"center\" valign=\"bottom\">Mujeres</td>\n";         
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"3\" class=\"label\" >B. Situación de embarazo</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"normal_10\" >\n";
      $html .= "    <td $td valign=\"top\" width=\"30%\"><b>6.3 ¿Embarazo?</b><br>".$fichavih['s_embarazo']."</td>\n";
      $html .= "    <td $td valign=\"top\" width=\"70%\">\n";
      $html .= "      6.4 Indique el numero de semanas de embarazo al diagnóstico<br>\n";
      $html .= "      <table width=\"100%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "        <tr ".$st3.">\n";
      $html .= "          <td width=\"70%\">\n";
      $html .= "            Indique el número de semanas de embarazo en el espacio señalado\n";  
      $html .= "          </td>\n";  
      $html .= "          <td $td2 width=\"30%\" class=\"normal_10\" align=\"center\">".(($fichavih['no_sem_embarazo'] == 0)? "&nbsp":$fichavih['no_sem_embarazo'])."</td>\n";  
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"3\" class=\"label\" >C. Enfermedades asociadas</td>\n";
      $html .= "  </tr>\n";      
      $html .= "  <tr>\n";
      $html .= "    <td $td colspan=\"3\" class=\"label\" >\n";
      $html .= "      6.5 Enfermedades asociadas que presenta el paciente (en caso de sida)<br>\n";
      
      $enfermedades = $mdl->ConsultarEnfermedades();
      $enfermedades_asoc = $mdl->ObtenerEnfermedadesAsociadas($fichavih['ficha_notif_det_id']);
      $longitud = sizeof($enfermedades)%3;
      
      $html .= "      <table width=\"96%\" align=\"center\" class=\"normal_10\">\n";
      
      $i= 0;
      foreach($enfermedades as $key => $dtl)
      {
        if($i %3 == 0)   $html .= "      <tr class=\"normal_10\">\n";
        
        $html .= "        <td $td width=\"2%\" align=\"center\"><b>".(($enfermedades_asoc[$dtl['enfermedad_id']])? "X":"&nbsp;")."</b></td>\n";
        $html .= "        <td >".$dtl['descripcion']."</td>\n";
        
        if(($i+1)%3 == 0) $html .= "      </tr>\n";
        
        $i++;
      }
      
      if($i%3 != 0) 
      {
        $html .= "              <td colspan=\"".((3-$longitud)*2)."\"></td>\n";
        $html .= "            </tr>\n";
      }
      $html .= "          </table>\n";
      
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
      $html .= "<table width=\"100%\" border=\"1\" $sts rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td $td class=\"label\" align=\"center\">VIH/Sida | Código INS 850</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "</div>\n";
      return $html;
		}
	}
?>