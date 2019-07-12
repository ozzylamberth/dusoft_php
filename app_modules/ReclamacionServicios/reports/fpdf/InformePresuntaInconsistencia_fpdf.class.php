<?php
  /**
  * $Id: InformePresuntaInconsistencia_fpdf.class.php,v 1.1 2009/10/21 22:04:38 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class InformePresuntaInconsistencia_fpdf
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function InformePresuntaInconsistencia_fpdf(){}
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
      
      if(!$rst = $nvd->ObtenerDatosInformePresuntaInconsistencia($parametros))
      {
        $this->error = $nvd->mensajeDeError;
        return false;
      }
      
      $ing = $mdl->ObtenerDatosIngreso($rst['ingreso']);
      
      $empresa = $mdl->ConsultarEmpresa($rst['plan_id']);
      $tercero = $mdl->ConsultarTerceros($rst['plan_id']);
      $paciente = $mdl->ConsultarPaciente($ing['paciente_id'], $ing['tipo_id_paciente']);
      $usuario = $mdl->ConsultarUsuario($rst['usuario_id']);
      $coberturas = $mdl->ConsCoberturaSalud($rst['ingreso']); 

      $datos = $empresa;
      $datos = array_merge($datos, $ing);
      $datos = array_merge($datos, $tercero);
      $datos = array_merge($datos, $paciente);
      $datos = array_merge($datos, $usuario);
      $datos = array_merge($datos, $coberturas);
      $datos = array_merge($datos, $rst);
      
      $this->GenerarInconsistPagador($datos,$nombre,$pathImagen);

      return true;
    }
    /**
    *
    */
    function GenerarInconsistPagador($datos,$Dir,$pathImagen)
    {
      define('FPDF_FONTPATH','font/');
      $pdf=new PDF('P','mm','letter');
      $pdf->AddPage();
      $pdf->Image($pathImagen.'/escudo-colombia.jpg',15,10,10);
      $pdf->SetFont('Arial','',7);
      
      $html.="<TABLE WIDTH='780'>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=25 ALIGN='CENTER'>";
      $html .= "<b>MINISTERIO DE LA PROTECCION SOCIAL</b>";
      $html .= "</TD>";
      $html .= "</TR>";  
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=25 ALIGN='CENTER'>";
      $html .= "<FONT SIZE='16'><b>INFORME DE POSIBLES INCONSISTENCIAS EN LA BASE DE DATOS DE LA ENTIDAD RESPONSABLE DEL PAGO</b></FONT>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='300' ALIGN='RIGHT'>";
      $html .= "<b>NUMERO INFORME</b> ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='80' ALIGN='RIGHT'>";

      $numero = str_pad($datos['num_informe'], 4, "-", STR_PAD_LEFT);
      $html .= "<TABLE BORDER='1'>";
      for($j=0; $j<4; $j++)
      {
        if($numero[$j]!='-')
        {
          $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
          $html .= "".$numero[$j]."";
          $html .= "</TD>";
        }else{
          $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
          $html .= "&nbsp;";
          $html .= "</TD>";
        }
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='50' ALIGN='RIGHT'>";
      $html .= "<b>Fecha:</b> ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='200' ALIGN='RIGHT'>";
      $html .= "<TABLE BORDER='1'>";
      for($i=0; $i<10; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
        $html .= "".$datos['fecha'][$i]."";
        $html .= "</TD>";
      }    
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='50' ALIGN='RIGHT'>";
      $html .= "<b>Hora:</b> ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='100' ALIGN='RIGHT'>";
      $html .= "<TABLE BORDER='1'>";
      for($j=0; $j<5; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
        $html .= "".$datos['hora'][$j]."";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='200' ALIGN='LEFT'>";
      $html .= "<b>INFORMACION DEL PRESTADOR</b>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='480' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='480' ALIGN='LEFT'>";
      $html .= "Nombre   ".$datos['razon_social']."";
      $html .= "</TD>";    
      $html .= "</TABLE>";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
      $html .= "<b>NIT</b>  ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
      if($datos['tipo_id_tercero']=='NIT')
      {
        $html .= "<TABLE BORDER='1'>";      
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
        $html .= "X";
        $html .= "</TD>";            
        $html .= "</TABLE>";
      }else{
        $html .= "<TABLE BORDER='1'>";      
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
        $html .= "&nbsp;";
        $html .= "</TD>";            
        $html .= "</TABLE>";
      }
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='240' ALIGN='RIGHT'>";
      $html .= "<TABLE BORDER='1'>";
      $emp_id = str_pad($datos['id_emp'], 12, "-", STR_PAD_LEFT);
      for($i=0; $i<12; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
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
      $html .= "<TD WIDTH='480'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='480'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
      $html .= "<b>CC</b>  ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
      if($datos['tipo_id_tercero']=='CC')
      {
        $html .= "<TABLE BORDER='1'>";
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
        $html .= "X";
        $html .= "</TD>";
        $html .= "</TABLE>";
      }else{
        $html .= "<TABLE BORDER='1'>";      
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
        $html .= "&nbsp;";
        $html .= "</TD>";            
        $html .= "</TABLE>";
      }
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='240' ALIGN='RIGHT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='240'>";
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
      $html .= "<TD WIDTH='60' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='60' ALIGN='LEFT'>";
      $html .= "<b>Código</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $cod_sgsss = str_pad($datos['codigo_sgsss'], 12, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='240'>";
      $html .= "<TABLE BORDER='1'>";
      for($j=0; $j<12; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
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
      $html .= "<TD ALIGN='LEFT' WIDTH='480'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='480'>";
      $html .= "Dirección prestador: ".$datos['direccion_emp']."";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='60'>";
      $html .= "<TABLE BORDER='1'>";
      $ind_emp = str_pad($datos['indicativo_emp'], 5, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='60' ALIGN='center'>";
      $html .= "<b>Teléfono:</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='240'>";
      $html .= "<TABLE BORDER='1'>";
      for($i=0; $i<5; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
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
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";    
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
      $html .= "<TD WIDTH='480'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='480'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";    
      $html .= "<TD WIDTH='760'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='60'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='100' ALIGN='CENTER'>";
      $html .= "indicativo";
      $html .= "</TD>";
      $html .= "<TD WIDTH='140' ALIGN='CENTER'>";
      $html .= "número";
      $html .= "</TD>";
      $html .= "<TD WIDTH='190' ALIGN='LEFT'>";
      $html .= "Departamento: ".$datos['departamento_emp']."";
      $html .= "</TD>";
      $dept_emp = str_pad($datos['tipo_dpto_id_emp'], 2, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($dept_emp[0]!="-")
      {      
        $html .= "".$dept_emp[0]."";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";    
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($dept_emp[1]!="-")
      {      
        $html .= "".$dept_emp[1]."";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";    
      $html .= "<TD WIDTH='190' ALIGN='LEFT'>";
      $html .= "Municipio: ".$datos['municipio_emp']."";
      $html .= "</TD>";
      $mpio_emp = str_pad($datos['tipo_mpio_id_emp'], 3, "-", STR_PAD_LEFT);
      for($i=0; $i<3; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
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
      $html .= "<TD WIDTH='760'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='490' ALIGN='LEFT'>";    
      $nom_pag = substr($datos['nombre_tercero'], 0, 30);
      $html .= "ENTIDAD A LA QUE SE LE INFORMA (PAGADOR): ".$nom_pag."";
      $html .= "</TD>";
      $html .= "<TD WIDTH='50' ALIGN='LEFT'>";
      $html .= "<b>CODIGO:</b>";
      $html .= "</TD>";
      $id_pagador = str_pad($datos['codigo_sgsss_p'], 12, "-", STR_PAD_LEFT);
      for($i=0; $i<12; $i++)
      {
        if($id_pagador[$i]!="-")
        {
          $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
          $html .= "".$id_pagador[$i]."";
          $html .= "</TD>";
        }else{
          $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
          $html .= "&nbsp;";
          $html .= "</TD>";
        }
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";    
      $html .= "<TD WIDTH='135' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='115' ALIGN='LEFT'>";
      $html .= "<b>Tipo de inconsistencia</b>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['inconsistencia_id'] == "1")
      {      
        $html .= "X";
      }else{
        $html .= "&nbsp;"; 
      }
      $html .= "</TD>";    
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='645' ALIGN='LEFT'>";
      $html .= "El usuario no existe en la base de datos";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";    
      $html .= "<TD WIDTH='135' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='115' ALIGN='LEFT'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['inconsistencia_id'] == "2")
      {      
        $html .= "X";
      }else{
        $html .= "&nbsp;"; 
      }
      $html .= "</TD>";    
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='645' ALIGN='LEFT'>";
      $html .= "Los datos del usuario no corresponden con los del documento de identificación presentado";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER'>";
      $html .= "<b>DATOS DEL USUARIO (como aparece en la base de datos)</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      if($datos['primer_apellido_u']!="")
      {
        $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
        $html .= "".strtoupper($datos['primer_apellido_u'])."";
        $html .= "</TD>";  
      }else{
        $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
        $html .= "&nbsp;";
        $html .= "</TD>";
      }
      if($datos['segundo_apellido_u']!="")
      {
        $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
        $html .= "".strtoupper($datos['segundo_apellido_u'])."";
        $html .= "</TD>";  
      }else{
        $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
        $html .= "no tiene";
        $html .= "</TD>";
      }
      if($datos['primer_nombre_u']!="")
      {
        $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
        $html .= "".strtoupper($datos['primer_nombre_u'])."";
        $html .= "</TD>";  
      }else{
        $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
        $html .= "&nbsp;";
        $html .= "</TD>";
      }
      if($datos['segundo_nombre_u']!="")
      {
        $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
        $html .= "".strtoupper($datos['segundo_nombre_u'])."";
        $html .= "</TD>";  
      }else{
        $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
        $html .= "no tiene";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<b>";
      $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
      $html .= "1er Apellido";
      $html .= "</TD>";
      $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
      $html .= "2do Apellido";
      $html .= "</TD>";
      $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
      $html .= "1er Nombre";
      $html .= "</TD>";
      $html .= "<TD WIDTH='195' ALIGN='CENTER'>";
      $html .= "2do Nombre";
      $html .= "</TD>";
      $html .= "</b>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='190' ALIGN='LEFT'>";
      $html .= "<b>Tipo Documento de Identificación</b>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['tipo_id_paciente']=="RC")
      {        
        $html .= "X";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='150' ALIGN='LEFT'>";
      $html .= "Registro Civil";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['tipo_id_paciente']=="PA")
      {
        $html .= "X"; 
      }else{
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='180' ALIGN='LEFT'>";
      $html .= "Pasaporte";
      $html .= "</TD>";
      $html .= "<TD WIDTH='340' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $id_u = str_pad($datos['paciente_id'], 17, "-", STR_PAD_LEFT);
      for($j=0; $j<18; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
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
      $html .= "<TD WIDTH='20'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['tipo_id_paciente']=="TI")
      {        
        $html .= "X";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='150' ALIGN='LEFT'>";
      $html .= "Tarjeta de identidad";    
      $html .= "</TD>";
      $html .= "<TD WIDTH='20'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['tipo_id_paciente']=="AS")
      {
        $html .= "X"; 
      }else{
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='180' ALIGN='LEFT'>";
      $html .= "Adulto sin identificación";
      $html .= "</TD>";
      $html .= "<TD WIDTH='340' ALIGN='CENTER'>";
      $html .= "Numero documento de identificación";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['tipo_id_paciente']=="CC")
      {        
        $html .= "X";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='150' ALIGN='LEFT'>";
      $html .= "Cédula de ciudadanía";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['tipo_id_paciente']=="MS")
      {
        $html .= "X"; 
      }else{
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='180' ALIGN='LEFT'>";
      $html .= "Menor sin identificacíon";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['tipo_id_paciente']=="CE")
      {        
        $html .= "X";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='150' ALIGN='LEFT'>";
      $html .= "Cédula de extranjería";
      $html .= "</TD>";
      $html .= "<TD WIDTH='200' ALIGN='CENTER'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='130' ALIGN='CENTER'>";
      $html .= "<b>Fecha de Nacimiento</b>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='260'>";
      $html .= "<TABLE BORDER='1'>";
      for($i=0; $i<10; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
        $html .= "".$datos['fecha_nacimiento_u'][$i]."";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='780'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT'>";
      if($datos['residencia_direccion_u']!="")
        $html .= "Dirección de Residencia Habitual: ".$datos['residencia_direccion_u']."";
      else
        $html .= "Dirección de Residencia Habitual: no tiene";
      $html .= "</TD>";
      $html .= "<TD WIDTH='80' ALIGN='LEFT'>";
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
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
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
      $html .= "<TD WIDTH='780'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='340' ALIGN=LEFT>";
      $html .= "Departamento: ".$datos['departamento_u']."";
      $html .= "</TD>";
      $dept_u = str_pad($datos['tipo_dpto_id_u'], 2, "-", STR_PAD_LEFT);
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($dept_u[0]!="-")
      {      
        $html .= "".$dept_u[0]."";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($dept_u[1]!="-")
      {      
        $html .= "".$dept_u[1]."";      
      }else{
        $html .= "&nbsp;";      
      }
      $html .= "</TD>";
      $html .= "<TD WIDTH='340' ALIGN='LEFT'>";
      $html .= "Municipio: ".$datos['municipio_u']."";
      $html .= "</TD>";
      $mpio_u = str_pad($datos['tipo_mpio_id_u'], 3, "-", STR_PAD_LEFT);
      for($i=0; $i<3; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
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
      if($datos['regimen_res_3047']=="PPC")
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
      $html .= "<TD WIDTH='780' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER'>";
      $html .= "<b>INFORMACION DE LA POSIBLE INCONSISTENCIA</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='280' ALIGN='CENTER'>";
      $html .= "<b>VARIABLE PRESUNTAMENTE INCORRECTA</b>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='500' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='500' ALIGN='CENTER'>";
      $html .= "<b>DATOS SEGÚN DOCUMENTO DE IDENTIFICACION (fisico)</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['sw_primer_apellido'] == "1")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='260' ALIGN='LEFT'>";
      $html .= "Primer Apellido";
      $html .= "</TD>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='80' ALIGN='LEFT'>";
      $html .= "Primer Apellido: ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='420' ALIGN='LEFT'>";
      if($datos['sw_primer_apellido'] == "1")
        $html .= "".strtoupper($datos['primer_apellido_d'])."";
      else
        $html .= "&nbsp;";    
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['sw_segundo_apellido'] == "1")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='260' ALIGN='LEFT'>";
      $html .= "Segundo Apellido";
      $html .= "</TD>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='100' ALIGN='LEFT'>";
      $html .= "Segundo Apellido: ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='400' ALIGN='LEFT'>";
      if($datos['sw_segundo_apellido'] == "1")
        $html .= "".strtoupper($datos['segundo_apellido_d'])."";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['sw_primer_nombre'] == "1")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='260' ALIGN='LEFT'>";
      $html .= "Primer Nombre";
      $html .= "</TD>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='80' ALIGN='LEFT'>";
      $html .= "Primer Nombre: ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='420' ALIGN='LEFT'>";
      if($datos['sw_primer_nombre'] == "1")
        $html .= "".strtoupper($datos['primer_nombre_d'])."";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['sw_segundo_nombre'] == "1")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='260' ALIGN='LEFT'>";
      $html .= "Segundo Nombre";
      $html .= "</TD>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='100' ALIGN='LEFT'>";
      $html .= "Segundo Nombre: ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='400' ALIGN='LEFT'>";
      if($datos['sw_segundo_nombre'] == "1")
        $html .= "".strtoupper($datos['segundo_nombre_d'])."";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['sw_tipo_id_paciente'] == "1")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='260' ALIGN='LEFT'>";
      $html .= "Tipo Documento de Identificación";
      $html .= "</TD>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='160' ALIGN='LEFT'>";
      $html .= "Tipo Documento de Identificación: ";
      $html .= "</TD>";
      $html .= "<TD WIDTH='340' ALIGN='LEFT'>";
      if($datos['sw_tipo_id_paciente']!="")
        $html .= "".$datos['descripcion_documento']."";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['sw_paciente_id'] == "1")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='260' ALIGN='LEFT'>";
      $html .= "Número Documento de Identificación";
      $html .= "</TD>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='180' ALIGN='LEFT'>";
      $html .= "Número Documento de Identificación: ";
      $html .= "</TD>";    
      for($j=0; $j<16; $j++)
      { 
        $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
        if($datos['paciente_id_d'][$j]!="")    
          $html .= "".$datos['paciente_id_d'][$j]."";
        else
          $html .= "&nbsp;";
        $html .= "</TD>";
      }    
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($datos['sw_fecha_nacimiento'] == "1")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='260' ALIGN='LEFT'>";
      $html .= "Fecha de Nacimiento";
      $html .= "</TD>";
      $html .= "<TD WIDTH='500' ALIGN='LEFT'>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='180' ALIGN='LEFT'>";
      $html .= "Fecha de Nacimiento: ";
      $html .= "</TD>";
      if($datos['sw_fecha_nacimiento'] == "1")
      { 
        $fNac = explode("-", $datos['fecha_nacimiento_d']);
        $fn = $fNac[2]."-".$fNac[1]."-".$fNac[0];
        for($i=0; $i<10; $i++)
        { 
          $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
          if($fn[$i]!="")    
            $html .= "".$fn[$i]."";
          else
            $html .= "&nbsp;";
          $html .= "</TD>";
        }
      }else{
        for($i=0; $i<10; $i++)
        { 
          $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
          $html .= "&nbsp;";
          $html .= "</TD>";
        }
      }
      $html .= "<TD WIDTH='120' ALIGN='CENTER'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $len_obs = strlen($datos['observaciones']);
      $html .= "<TD WIDTH='780' ALIGN='LEFT'>";
      if($datos['observaciones']!="" && $len_obs > 84)
      {
        $html .= "Observaciones  ";
        for($j=0; $j<84; $j++)
        {
          $html .= "".$datos['observaciones'][$j]."";
        }
      }else 
      {
        $html .= "Observaciones  ".$datos['observaciones']." ";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT'>";
      if($datos['observaciones']!="" && $len_obs > 84)
      {
        for($i=84; $i<176; $i++)
        {
          $html .= "".$datos['observaciones'][$i]."";
        }
      }else 
      {
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT'>";
      if($datos['observaciones']!="" && $len_obs > 176)
      {
        for($j=176; $j<268; $j++)
        {
          $html .= "".$datos['observaciones'][$j]."";
        } 
      }else 
      {
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT'>";//92
      if($datos['observaciones']!="" && $len_obs > 268)
      {
        for($j=268; $j<360; $j++)
        {
          $html .= "".$datos['observaciones'][$j]."";
        }
      }else 
      {
        $html .= "&nbsp;";
      }
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='CENTER'>";
      $html .= "<b>INFORMACION DE LA PERSONA QUE REPORTA</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='360' ALIGN='LEFT'>";
      $html .= "Nombre de quien reporta  ".$datos['nombre_us']."";
      $html .= "</TD>";
      $html .= "<TD WIDTH='60' ALIGN='LEFT'>";
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
        $html .= "<TD WIDTH='20'>";
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
        $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
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
        $html .= "<TD WIDTH='20'>";
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
      $html .= "<TD WIDTH='360' ALIGN='CENTER'>";
      $html .= "&nbsp;";
      $html .= "</TD>";    
      $html .= "<TD WIDTH='60' ALIGN='CENTER'>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='100' ALIGN='CENTER'>";
      $html .= "indicativo";
      $html .= "</TD>";
      $html .= "<TD WIDTH='140' ALIGN='CENTER'>";
      $html .= "número";
      $html .= "</TD>";
      $html .= "<TD WIDTH='120' ALIGN='CENTER'>";
      $html .= "extensión";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='360' ALIGN='LEFT'>";
      $html .= "Cargo o actividad:  ".$datos['descripcion_us']."";
      $html .= "</TD>";
      $html .= "<TD WIDTH='220' ALIGN='LEFT'>";
      $html .= "Teléfono celular:  ";
      $html .= "</TD>";
      for($j=0; $j<10; $j++)
      {
        $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
        $html .= "".$datos['tel_celular_us'][$j]."";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TR>";
      $html .= "</TABLE>";
      $pdf->WriteHTML($html);
      $pdf->SetLineWidth(0.3);
      $pdf->Rect(9, 5, 195, 253, '');
      $pdf->Output($Dir,'F');
      return True;
    }
  }
?>