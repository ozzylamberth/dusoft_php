<?php 
 	/**
  * $Id: InformeUrgencias_fpdf.class.php,v 1.1 2009/10/21 22:04:39 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class InformeUrgencias_fpdf
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function InformeUrgencias_fpdf(){}
    /**
    * Funcion para generar el archivo xml
    *
    * @param array $parametros Arreglo de oarametros del request
    *
    * @return boolean
    */
    function GetReporteFPDF($parametros,$nombre,$pathImagen)
    {
      $nvd = AutoCarga::factory('Resolucion3047','classes','app','ReclamacionServicios');
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");

      
      if(!$rst = $nvd->ObtenerDatosInformeUrgencias($parametros))
      {
        $this->error = $nvd->mensajeDeError;
        return false;
      }
      $empresa      = $mdl->ConsultarEmpresa($rst['plan_id']);
      $tercero      = $mdl->ConsultarTerceros($rst['plan_id']);
      $paciente     = $mdl->ConsultarPaciente($rst['paciente_id'], $rst['tipo_id_paciente']);
      $usuario      = $mdl->ConsultarUsuario($rst['usuario_id']);
      $orig_aten    = $mdl->ConsultarCausaIng($rst['ingreso']);
      $ing_urg      = $mdl->ConsIngresoUrg($rst['ingreso']);
      $niv_triages  = $mdl->ConsultarTriageIng($rst['ingreso']);
      $pac_rem      = $mdl->ConsPacienteRemitido($rst['ingreso']);
      $diagnosticos = $mdl->ConsultarDiagnosticos($rst['ingreso']);
      $coberturas   = $mdl->ConsCoberturaSalud($rst['ingreso']);
      $destino      = $mdl->ObtenerDestinoPaciente($rst['ingreso']);
     
      $info=$mdl->ConsultarUltimaEvolucion($rst['ingreso']);
      $evolucion_id=$info[0]['evolucion'];
      $destino2=$mdl->ConsultarDestinoPaciente($evolucion_id,$rst['ingreso']);
         
      $datos = $rst;
      $datos = array_merge($datos, $empresa);
      $datos = array_merge($datos, $tercero);
      $datos = array_merge($datos, $paciente);
      $datos = array_merge($datos, $usuario);
      $datos = array_merge($datos, $coberturas);
      $datos = array_merge($datos, $orig_aten);
      $datos = array_merge($datos, $ing_urg);
      $datos = array_merge($datos, $pac_rem);
      $datos = array_merge($datos, $destino);
      $datos = array_merge($datos, $niv_triages);
      $datos['diagnosticos'] = $diagnosticos;
     // print_r($datos);
      $this->GenerarAtencionUrgencias($datos,$nombre,$pathImagen,$destino2);
      return true;
    }
    /**
    *
    */
    function GenerarAtencionUrgencias($datos,$Dir,$pathImagen,$destino2)
    {

      define('FPDF_FONTPATH', 'font/');
      $pdf=new PDF('P', 'mm', 'letter');//legal
      $pdf->AddPage();
      $pdf->Image($pathImagen.'/escudo-colombia.jpg', 15, 10, 10);
      $pdf->SetFont('Arial','',7);
      
      $html .="<TABLE WIDTH='780'>";
      /**/
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28 ALIGN='CENTER'>";
      $html .= "<b>MINISTERIO DE LA PROTECCION SOCIAL</b>";
      $html .= "</TD>";
      $html .= "</TR>";  
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28 ALIGN='CENTER'>";
      $html .= "<FONT SIZE='16'><b>INFORME DE LA ATENCION INICIAL DE URGENCIAS</b></FONT>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='300' ALIGN='RIGHT' HEIGHT=28>";
      $html .= "<b>NUMERO INFORME</b> ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='80' ALIGN='RIGHT' HEIGHT=28>";

      $numero = str_pad($datos['num_atencion'], 4, "-", STR_PAD_LEFT);
      $html .= "<TABLE BORDER='1'>";
      for($j=0; $j<4; $j++)
      {
        if($numero[$j]!='-')
        {
          $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
          $html .= "".$numero[$j]."";
          $html .= "</TD>";
        }else{
          $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
          $html .= "&nbsp;";
          $html .= "</TD>";
        }
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='50' ALIGN='RIGHT' HEIGHT=28>";
      $html .= "<b>Fecha:</b> ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='200' ALIGN='RIGHT' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      for($i=0; $i<10; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        $html .= "".$datos['fecha'][$i]."";
        $html .= "</TD>";
      }    
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='50' ALIGN='RIGHT' HEIGHT=28>";
      $html .= "<b>Hora:</b> ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='100' ALIGN='RIGHT' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      for($j=0; $j<5; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        $html .= "".$datos['hora'][$j]."";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='200' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<b>INFORMACION DEL PRESTADOR</b>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='480' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='480' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Nombre   ".$datos['razon_social']."";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<b>NIT</b>  ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=28>";
      if($datos['tipo_id_tercero']=='NIT')
      {
        $html .= "<TABLE BORDER='1'>";      
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        $html .= "X";
        $html .= "</TD>";            
        $html .= "</TABLE>";
      }else{
        $html .= "<TABLE BORDER='1'>";      
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        $html .= "&nbsp;";
        $html .= "</TD>";            
        $html .= "</TABLE>";
      }
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='240' ALIGN='RIGHT' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $emp_id = str_pad($datos['id_emp'], 12, "-", STR_PAD_LEFT);
      for($i=0; $i<12; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($datos['tipo_id_tercero']=='NIT' && $emp_id[$i]!='-')
        {        
          $html .= "".$emp_id[$i]."";
        }else{
          $html .= "&nbsp;";
        }
        $html .= "</TD>";
      }    
      $html .= "</TABLE>";
      $html .= "</TD>";    
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='480' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='480' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<b>CC</b>  ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=28>";
      if($datos['tipo_id_tercero']=='CC')
      {
        $html .= "<TABLE BORDER='1'>";
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        $html .= "X";
        $html .= "</TD>";
        $html .= "</TABLE>";
      }else{
        $html .= "<TABLE BORDER='1'>";      
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        $html .= "&nbsp;";
        $html .= "</TD>";            
        $html .= "</TABLE>";
      }
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='240' ALIGN='RIGHT' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='240' HEIGHT=28>";
      if($datos['tipo_id_tercero']=='CC')
      {
        $html .= "Número      ".$datos['id_emp']."";
      }else{
        $html .= "Número                                             DV ".$datos['dv_emp']."";
      }
      $html .= "</TD>";    
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='60' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='60' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<b>Código</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $cod_sgsss = str_pad($datos['codigo_sgsss'], 12, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='240' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      for($j=0; $j<12; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($cod_sgsss[$j]!="-")
        {        
          $html .= "".$cod_sgsss[$j]."";        
        }else{
          $html .= "&nbsp;";
        }
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD ALIGN='LEFT' WIDTH='480' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='480' HEIGHT=28>";
      $html .= "Dirección prestador: ".$datos['direccion_emp']."";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='60' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $ind_emp = str_pad($datos['indicativo_emp'], 5, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='60' ALIGN='center' HEIGHT=28>";
      $html .= "<b>Teléfono:</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='240' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      for($i=0; $i<5; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($ind_emp[$i]!="-")
        {
          $html .= "".$ind_emp[$i]."";
        }else{
          $html .= "&nbsp;";   
        }
        $html .= "</TD>";
      }
      $tel_emp = str_pad($datos['telefonos_emp'], 7, "-", STR_PAD_LEFT);
      for($j=0; $j<7; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";    
        if($tel_emp[$j]!="-")
        {
          $html .= "".$tel_emp[$j]."";
        }else{
          $html .= "&nbsp;";
        }
        $html .= "</TD>";
      }    
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='480' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='480' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";    
      $html .= "<TD WIDTH='760' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='60' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='100' ALIGN='CENTER' HEIGHT=28>";
      $html .= "indicativo";
      $html .= "</TD>";
      $html .= "<TD WIDTH='140' ALIGN='CENTER' HEIGHT=28>";
      $html .= "número";
      $html .= "</TD>";
      $html .= "<TD WIDTH='190' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Departamento: ".$datos['departamento_emp']."";
      $html .= "</TD>";
      $dept_emp = str_pad($datos['tipo_dpto_id_emp'], 2, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($dept_emp[0]!="-")
      {      
        $html .= "".$dept_emp[0]."";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";    
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($dept_emp[1]!="-")
      {      
        $html .= "".$dept_emp[1]."";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";    
      $html .= "<TD WIDTH='190' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Municipio: ".$datos['municipio_emp']."";
      $html .= "</TD>";
      $mpio_emp = str_pad($datos['tipo_mpio_id_emp'], 3, "-", STR_PAD_LEFT);
      for($i=0; $i<3; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($mpio_emp[$i]!="-")
          $html .= "".$mpio_emp[$i]."";
        else
          $html .= "&nbsp;";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='760' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='490' ALIGN='LEFT' HEIGHT=28>";    
      $nom_pag = substr($datos['nombre_tercero'], 0, 30);
      $html .= "ENTIDAD A LA QUE SE LE INFORMA (PAGADOR): ".$nom_pag."";
      $html .= "</TD>";
      $html .= "<TD WIDTH='50' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<b>CODIGO:</b>";
      $html .= "</TD>";
      $id_pagador = str_pad($datos['codigo_sgsss_p'], 12, "-", STR_PAD_LEFT);
      for($i=0; $i<12; $i++)
      {
        if($id_pagador[$i]!="-")
        {
          $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=28>";
          $html .= "".$id_pagador[$i]."";
          $html .= "</TD>";
        }else{
          $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=28>";
          $html .= "&nbsp;";
          $html .= "</TD>";
        }
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=28>";
      $html .= "<b>DATOS DEL USUARIO (como aparece en la base de datos)</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      if($datos['primer_apellido_u']!="")
      {
        $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
        $html .= "".$datos['primer_apellido_u']."";
        $html .= "</TD>";  
      }else{
        $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
        $html .= "&nbsp;";
        $html .= "</TD>";
      }
      if($datos['segundo_apellido_u']!="")
      {
        $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
        $html .= "".$datos['segundo_apellido_u']."";
        $html .= "</TD>";  
      }else{
        $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
        $html .= "no tiene";
        $html .= "</TD>";
      }
      if($datos['primer_nombre_u']!="")
      {
        $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
        $html .= "".$datos['primer_nombre_u']."";
        $html .= "</TD>";  
      }else{
        $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
        $html .= "&nbsp;";
        $html .= "</TD>";
      }
      if($datos['segundo_nombre_u']!="")
      {
        $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
        $html .= "".$datos['segundo_nombre_u']."";
        $html .= "</TD>";  
      }else{
        $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
        $html .= "no tiene";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<b>";
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
      $html .= "1er Apellido";
      $html .= "</TD>";
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
      $html .= "2do Apellido";
      $html .= "</TD>";
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
      $html .= "1er Nombre";
      $html .= "</TD>";
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=28>";
      $html .= "2do Nombre";
      $html .= "</TD>";
      $html .= "</b>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='190' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<b>Tipo Documento de Identificación</b>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['tipo_id_paciente']=="RC")
      {        
        $html .= "X";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='150' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Registro Civil";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['tipo_id_paciente']=="PA")
      {
        $html .= "X"; 
      }else{
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='180' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Pasaporte";
      $html .= "</TD>";
      $html .= "<TD WIDTH='340' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $id_u = str_pad($datos['paciente_id'], 17, "-", STR_PAD_LEFT);
      for($j=0; $j<18; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($id_u[$j]!="-")
        {
          $html .= "".$id_u[$j]."";
        }else{
          $html .= "&nbsp;";
        }
        $html .= "</TD>";
      }    
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['tipo_id_paciente']=="TI")
      {        
        $html .= "X";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='150' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Tarjeta de identidad";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['tipo_id_paciente']=="AS")
      {
        $html .= "X"; 
      }else{
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='180' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Adulto sin identificación";
      $html .= "</TD>";
      $html .= "<TD WIDTH='340' ALIGN='CENTER' HEIGHT=28>";
      $html .= "Numero documento de identificación";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['tipo_id_paciente']=="CC")
      {        
        $html .= "X";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='150' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Cédula de ciudadanía";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['tipo_id_paciente']=="MS")
      {
        $html .= "X"; 
      }else{
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='180' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Menor sin identificacíon";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['tipo_id_paciente']=="CE")
      {        
        $html .= "X";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='150' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Cédula de extranjería";
      $html .= "</TD>";
      $html .= "<TD WIDTH='200' ALIGN='CENTER' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='130' ALIGN='CENTER' HEIGHT=28>";
      $html .= "<b>Fecha de Nacimiento</b>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='260' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      for($i=0; $i<10; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        $html .= "".$datos['fecha_nacimiento_u'][$i]."";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT' HEIGHT=28>";
      if($datos['residencia_direccion_u']!="")
        $html .= "Dirección de Residencia Habitual: ".$datos['residencia_direccion_u']."";
      else
        $html .= "Dirección de Residencia Habitual: no tiene";
      $html .= "</TD>";
      $html .= "<TD WIDTH='80' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<b>Telefono:</b> ";
      $html .= "</TD>";
      $len_tel_u = strlen($datos['residencia_telefono_u']);
      
      for($i=0; $i<$len_tel_u;$i++)
      {
        if(is_numeric($datos['residencia_telefono_u'][$i]))
          $num_tel_u = $num_tel_u.$datos['residencia_telefono_u'][$i];
        else
          break;
      }
      $numtelu = str_pad($num_tel_u, 10, "-", STR_PAD_LEFT);
      for($j=0; $j<10; $j++)
      {  
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($numtelu[$j]!="-")
        {        
          $html .= "".$numtelu[$j]."";
        }else{
          $html .= "&nbsp;";        
        }
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='340' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Departamento: ".$datos['departamento_u']."";
      $html .= "</TD>";
      $dept_u = str_pad($datos['tipo_dpto_id_u'], 2, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($dept_u[0]!="-")
      {      
        $html .= "".$dept_u[0]."";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($dept_u[1]!="-")
      {      
        $html .= "".$dept_u[1]."";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "<TD WIDTH='340' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Municipio: ".$datos['municipio_u']."";
      $html .= "</TD>";
      $mpio_u = str_pad($datos['tipo_mpio_id_u'], 3, "-", STR_PAD_LEFT);
      for($i=0; $i<3; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($mpio_u[$i]!="-")
          $html .= "".$mpio_u[$i]."";
        else
          $html .= "&nbsp;";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT'>";
      $html .= "<b>Cobertura en salud</b>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['regimen_res_3047']=="RCT")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='135' ALIGN='LEFT'>";
      $html .= "Regimen Contributivo";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['regimen_res_3047']=="RSP")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='205' ALIGN='LEFT'>";
      $html .= "Regimen Subsidiado - parcial";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['regimen_res_3047']=="PPS")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='205' ALIGN='LEFT'>";
      $html .= "Poblacion pobre no Asegurada sin SISBEN";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['regimen_res_3047']=="PAS")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='135' ALIGN='LEFT'>";
      $html .= "Plan adicional de salud";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['regimen_res_3047']=="RST")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='135' ALIGN='LEFT'>";
      $html .= "Regimen Subsidiado - total";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['regimen_res_3047']=="PPC")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='205' ALIGN='LEFT'>";
      $html .= "Poblacion pobre no Asegurada con SISBEN";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['regimen_res_3047']=="DES")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='205' ALIGN='LEFT'>";
      $html .= "Desplazado";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['regimen_res_3047']=="OTR")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='135' ALIGN='LEFT'>";
      $html .= "Otro";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=28><b>INFORMACION DE LA ATENCION</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<b>Origen de la atención</b>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      $cont=0;
      if($datos['origen_atencion']=="13")
      {
        $html .= "X";
      }else{
        $cont++;
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='140' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Enfermedad General";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['origen_atencion']=="01")
      {
        $html .= "X";
      }else{
        $html .= "&nbsp;";
        $cont++;
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='140' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Accidente de trabajo";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['origen_atencion']=="06")
      {
        $html .= "X";
      }else{
        $html .= "&nbsp;";
        $cont++;
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='140' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Evento Catastrófico";
      $html .= "</TD>";
      $html .= "<TD WIDTH='160' ALIGN='CENTER' HEIGHT=28>";
      $html .= "<b>Clasificación Triage</b>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['nivel_triage_id']=="1")
      {
        $html .= "X";
      }else{
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='120' ALIGN='LEFT' HEIGHT=28>";
      $html .= "1. Rojo";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['origen_atencion']=="14")
      {
        $html .= "X";
      }else{
        $html .= "&nbsp;";
        $cont++;
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='140' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Enfermedad Profesional";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['origen_atencion']=="02")
      {
        $html .= "X";
      }else{
        $html .= "&nbsp;";
        $cont++;
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='140' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Accidente de Tránsito";
      $html .= "</TD>";
      if($cont==5)
      {
        $html .= "<TD WIDTH='20' HEIGHT=28>";
        $html .= "<TABLE BORDER='1'>";
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        $html .= "X";
        $html .= "</TD>";
        $html .= "</TABLE>";
        $html .= "</TD>";
        $html .= "<TD WIDTH='140' ALIGN='LEFT' HEIGHT=28>";
        $html .= "Otro";
        $html .= "</TD>";
      }
      else
      {
        $html .= "<TD WIDTH='160' ALIGN='LEFT' HEIGHT=28>";
        $html .= "&nbsp;";
        $html .= "</TD>";
      }
      $html .= "<TD WIDTH='160' ALIGN='LEFT' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['nivel_triage_id']=="2")
      {
        $html .= "X";
      }else{
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='120' ALIGN='LEFT' HEIGHT=28>";
      $html .= "2. Amarillo";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='640' ALIGN='LEFT' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['nivel_triage_id']=="3")
      {
        $html .= "X";
      }else{
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='120' ALIGN='LEFT' HEIGHT=28>";
      $html .= "3. Verde";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Ingreso a Urgencias";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='40' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Fecha: ";
      $html .= "</TD>";
      list($datos['fIngUrg'],$datos['hIngUrg']) = explode(" ",$datos['fecha_ingreso']);
      for($i=0; $i<10; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($datos['fIngUrg'][$i]!="")
          $html .= "".$datos['fIngUrg'][$i]."";
        else
          $html .= "&nbsp;";
        $html .= "</TD>";
      }    
      $html .= "<TD WIDTH='40' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Hora: ";
      $html .= "</TD>";
      for($j=0; $j<5; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($datos['hIngUrg'][$j]!="")
          $html .= "".$datos['hIngUrg'][$j]."";
        else
          $html .= "&nbsp;";
        $html .= "</TD>";
      }
      $html .= "<TD WIDTH='160' ALIGN='CENTER' HEIGHT=28>";
      $html .= "Paciente Viene Remitido";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if($datos['paciente_remitido_id'])
        $html .= "X";    
      else
        $html .= "&nbsp;";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='40' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Si";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
      if(!$datos['paciente_remitido_id'])
        $html .= "X";    
      else
        $html .= "&nbsp;";

      $html .= "</TD>";
      $html .= "<TD WIDTH='40' ALIGN='LEFT' HEIGHT=28>";
      $html .= "No";
      $html .= "</TD>";
      $html .= "<TD WIDTH='120' ALIGN='LEFT' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT' HEIGHT=28>";
      $len_nomb_rem = strlen($datos['nomb_rem']);
      
      $html .= "Nombre del prestador de servicios que remite   ";
      for($j=0; $j<39; $j++)
      {
        $html .= "".$datos['nomb_rem'][$j]."";
      }
      $html .= "</TD>";
      $html .= "<TD WIDTH='40' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Código";
      $html .= "</TD>";
      $cen_rem = str_pad($datos['centro_remision'], 12, "-", STR_PAD_LEFT);
      for($i=0; $i<12; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($cen_rem[$i]!="-")
          $html .= "".$cen_rem[$i]."";
        else
          $html .= "&nbsp;";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      if($len_nomb_rem > 39)
        for($j=39; $j<99; $j++)
          $html .= "".$datos['nomb_rem'][$j]."";    
      else
          $html .= "&nbsp;";
      
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='340' HEIGHT=28>Departamento:  ".$datos['departamento_pr']."";
      $html .= "</TD>";
      $num_dpto_pr = str_pad($datos['tipo_dpto_id_pr'], 2, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='20' HEIGHT=28>";    
      if($num_dpto_pr[0]!="-")
        $html .= "".$num_dpto_pr[0]."";
      else
        $html .= "&nbsp;";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=28>";    
      if($num_dpto_pr[1]!="-")
        $html .= "".$num_dpto_pr[1]."";
      else
        $html .= "&nbsp;";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='340' HEIGHT=28>Municipio:  ".$datos['municipio_pr']."";
      $html .= "</TD>";
      $num_mpio_pr = str_pad($datos['tipo_mpio_id_pr'], 3, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='20' HEIGHT=28>";    
      if($num_mpio_pr[0]!="-")
        $html .= "".$num_mpio_pr[0]."";
      else
        $html .= "&nbsp;";
        $html .= "<TD WIDTH='20' HEIGHT=28>";    
      if($num_mpio_pr[1]!="-")
        $html .= "".$num_mpio_pr[1]."";
      else
        $html .= "&nbsp;";
      $html .= "<TD WIDTH='20' HEIGHT=28>";    
      if($num_mpio_pr[2]!="-")
        $html .= "".$num_mpio_pr[2]."";
      else
        $html .= "&nbsp;";       
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      
      $html .= "<TR>";

      $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=28>";
      $len_mot = strlen($datos['desc_motivo']);    
      if($datos['desc_motivo']!="" && $len_mot > 100)
      {
        $html .= "Motivo de la consulta  ";
        for($i=0; $i<100; $i++)  
          $html .= "".$datos['desc_motivo'][$i]."";
      }else{
        $html .= "Motivo de la consulta  ".$datos['desc_motivo']."";
      }
      $html .= "</TD>";
      $html .= "</TR>";
      if($len_mot > 100)
      {
        $html .= "<TR>";
        $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=28>";        
        for($j=100; $j<220; $j++)
          $html .= "".$datos['desc_motivo'][$j]."";
        $html .= "</TD>";
        $html .= "</TR>";
      }
      if($len_mot > 220)
      {
        $html .= "<TR>";
        $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=28>";        
        for($i=220; $i<340; $i++)
          $html .= "".$datos['desc_motivo'][$i]."";
        $html .= "</TD>";
        $html .= "</TR>";
      }
      if($len_mot > 340)
      {
        $html .= "<TR>";
        $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=28>";        
        for($i=340; $i<460; $i++)
          $html .= "".$datos['desc_motivo'][$i]."";
        $html .= "</TD>";
        $html .= "</TR>";
      }
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=28>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='130' ALIGN='LEFT' HEIGHT=28>";
      $html .= "<b>Impresion diagnostica</b>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='80' ALIGN='CENTER' HEIGHT=28>";
      $html .= "<b>Codigo CIE10</b>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='570' ALIGN='CENTER' HEIGHT=28>";
      $html .= "<b>Descripcion</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $cant_d = count($datos['diagnosticos']);

      for($j=0; $j<$cant_d; $j++)
      { 
        $html .= "<TR>";
        $tipodiag = str_pad($datos['diagnosticos'][$j]['diagnostico_id'], 4, "-", STR_PAD_LEFT);
        if($j!=0)
        {
          $html .= "<TD WIDTH='130' ALIGN='CENTER' HEIGHT=28>";
          $html .= "Diagnostico relacionado ".$j."";
          $html .= "</TD>";
        }else{
          $html .= "<TD WIDTH='130' ALIGN='CENTER' HEIGHT=28>";
          $html .= "Diagnostico principal";
          $html .= "</TD>";
        }
        $html .= "<TABLE BORDER='1'>";
        for($i=0; $i<4; $i++)
        {
          $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
          if($tipodiag[$i]!="-")
          {
            $html .= "".$tipodiag[$i]."";
          }else{
            $html .= "&nbsp;";
          }
          $html .= "</TD>";
        }
        $html .= "</TABLE>";
        $html .= "<TD WIDTH='10' ALIGN='LEFT' HEIGHT=28>";
        $html .= "&nbsp;";
        $html .= "</TD>";
        $html .= "<TD WIDTH='560' ALIGN='LEFT' HEIGHT=28>";
        for($k=0; $k<90; $k++)
        {
          $html .= "".$datos['diagnosticos'][$j]['diagnostico_nombre'][$k]."";
        }
        $html .= "</TD>";
        $html .= "</TR>";
        if($j==3)
          break;
      }
      
      $html .= "<TR>";

      $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780'  HEIGHT='1'>&nbsp;</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "<b>Destino del Paciente</b>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='39' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT='28'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT='28'>";
      if(!empty($datos['destino_paciente_id']))
      {      
        $html .= (($datos['destino_paciente_id'] == '1')? "X":"&nbsp;");
      }else 
      {
        $html .= (($destino2[0]['destino_paciente_id'] == '1')? "X":"&nbsp;");
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='227' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "Domicilio";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT='28'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT='28'>";
       if(!empty($datos['destino_paciente_id']))
      {    
         $html .= (($datos['destino_paciente_id'] == '3')? "X":"&nbsp;");
      }
      else 
      {
         $html .= (($destino2[0]['destino_paciente_id'] == '3')? "X":"&nbsp;");
      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='227' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "Internación";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT='28'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT='28'>";
       if(!empty($datos['destino_paciente_id']))
      { 
         $html .= (($datos['destino_paciente_id'] == '5')? "X":"&nbsp;");
      }
      else
      {
          $html .= (($destino2[0]['destino_paciente_id'] == '5')? "X":"&nbsp;");
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='227' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "Contraremisión";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='39' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT='28'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT='28'>";
        if(!empty($datos['destino_paciente_id']))
      { 
          $html .= (($datos['destino_paciente_id'] == '2')? "X":"&nbsp;");
      }
      else
      {
           $html .= (($destino2[0]['destino_paciente_id'] == '2')? "X":"&nbsp;");
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='227' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "Observación";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT='28'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT='28'>";
        if(!empty($datos['destino_paciente_id']))
      {       
          $html .= (($datos['destino_paciente_id'] == '4')? "X":"&nbsp;");
      }
      else 
      {
        $html .= (($destino2[0]['destino_paciente_id'] == '4')? "X":"&nbsp;");
      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='227' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "Remisión";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT='28'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT='28'>";
      if(!empty($datos['destino_paciente_id']))
      { 
          $html .= (($datos['destino_paciente_id'] == '6')? "X":"&nbsp;");
      }
      else 
      {
        $html .= (($destino2[0]['destino_paciente_id'] == '6')? "X":"&nbsp;");
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='227' ALIGN='LEFT' HEIGHT='28'>";
      $html .= "Otro";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=28>";
      $html .= "<b>INFORMACION DE LA PERSONA QUE INFORMA</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='360' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Nombre de quien reporta  ".$datos['nombre_us']."";
      $html .= "</TD>";
      $html .= "<TD WIDTH='60' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Teléfono  ";
      $html .= "</TD>";
      $len_tel_us = strlen($datos['telefono_us']);
      for($i=0; $i<$len_tel_us;$i++)
      {
        if(is_numeric($datos['telefono_us'][$i]))
          $num_tel_us = $num_tel_us.$datos['telefono_us'][$i];
        else
          break;
      }
      $numtelus = str_pad($num_tel_us, 7, "-", STR_PAD_LEFT);
      $ind_us = str_pad($datos['indicativo_us'], 5, "-", STR_PAD_LEFT);
      $ext_us = str_pad($datos['extension_us'], 6, "-", STR_PAD_LEFT);
      for($j=0; $j<5; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($ind_us[$j]!="-")
        {
          $html .= "".$ind_us[$j]."";
        }else{
          $html .= "&nbsp;";   
        }
        $html .= "</TD>";
      }
      for($i=0; $i<7; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($numtelus[$i]!="-")
        {
          $html .= "".$numtelus[$i]."";
        }else{
          $html .= "&nbsp;";   
        }
        $html .= "</TD>";
      }
      for($j=0; $j<6; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($ext_us[$j]!="-")
        {
          $html .= "".$ext_us[$j]."";
        }else{
          $html .= "&nbsp;";   
        }
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='360' ALIGN='CENTER' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "<TD WIDTH='60' ALIGN='CENTER' HEIGHT=28>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='100' ALIGN='CENTER' HEIGHT=28>";
      $html .= "indicativo";
      $html .= "</TD>";
      $html .= "<TD WIDTH='140' ALIGN='CENTER' HEIGHT=28>";
      $html .= "número";
      $html .= "</TD>";
      $html .= "<TD WIDTH='120' ALIGN='CENTER' HEIGHT=28>";
      $html .= "extensión";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='360' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Cargo o actividad:  ".$datos['descripcion_us']."";
      $html .= "</TD>";
      $html .= "<TD WIDTH='220' ALIGN='LEFT' HEIGHT=28>";
      $html .= "Teléfono celular:  ";
      $html .= "</TD>";
      for($j=0; $j<10; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=28>";
        if($datos['tel_celular_us'][$j])
          $html .= "".$datos['tel_celular_us'][$j]."";
        else
          $html .= "&nbsp;";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TR>";
      /**/
      $html .= "</TABLE>";
      
      $pdf->WriteHTML($html);
      $pdf->SetLineWidth(0.3);
      $pdf->Rect(10, 5, 195, 263, '');
      $pdf->Output($Dir, 'F');
      
      return true;
    }
  }
?>