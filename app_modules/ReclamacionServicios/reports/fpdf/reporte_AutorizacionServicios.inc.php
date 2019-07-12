<?php 
  /**
  * $Id: reporte_GenerarAutorizacionServicios.inc.php, v 1.0 2009/07/09 11:23:42 Manuel 
  * Exp $
  */
  
  function GenerarAutorizacionServicios($datos)
  {
    IncludeLib('funciones_admision');
    $Dir = "cache/AutorizacionServicios.pdf";
    require("classes/fpdf/html_class.php");
    define('FPDF_FONTPATH', 'font/');
    $pdf=new PDF('P', 'mm', 'letter');//legal
    $pdf->AddPage();
    $pdf->Image('images/escudo-colombia.jpg', 15, 10, 10);
    $pdf->SetFont('Arial','',7);
    
    $html .="<TABLE WIDTH='780'>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' HEIGHT=26 ALIGN='CENTER'>";
    $html .= "<b>MINISTERIO DE LA PROTECCION SOCIAL</b>";
    $html .= "</TD>";
    $html .= "</TR>";  
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' HEIGHT=26 ALIGN='CENTER'>";
    $html .= "<FONT SIZE='16'><b>SOLICITUD DE AUTORIZACION DE SERVICIOS DE SALUD</b></FONT>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='300' ALIGN='RIGHT' HEIGHT=26>";
    $html .= "<b>NUMERO SOLICITUD</b> ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='80' ALIGN='RIGHT' HEIGHT=26>";

    $numero = str_pad($datos['consec'], 4, "-", STR_PAD_LEFT);
    $html .= "<TABLE BORDER='1'>";
    for($j=0; $j<4; $j++)
    {
      if($numero[$j]!='-')
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
        $html .= "".$numero[$j]."";
        $html .= "</TD>";
      }else{
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
        $html .= "&nbsp;";
        $html .= "</TD>";
      }
    }
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='50' ALIGN='RIGHT' HEIGHT=26>";
    $html .= "<b>Fecha:</b> ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='200' ALIGN='RIGHT' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    for($i=0; $i<10; $i++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      $html .= "".$datos['fecha'][$i]."";
      $html .= "</TD>";
    }    
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='50' ALIGN='RIGHT' HEIGHT=26>";
    $html .= "<b>Hora:</b> ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='100' ALIGN='RIGHT' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    for($j=0; $j<5; $j++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      $html .= "".$datos['hora'][$j]."";
      $html .= "</TD>";
    }
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='200' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<b>INFORMACION DEL PRESTADOR (solicitante)</b>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='480' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='480' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Nombre   ".$datos['razon_social']."";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<b>NIT</b>  ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=26>";
    if($datos['tipo_id_tercero']=='NIT')
    {
      $html .= "<TABLE BORDER='1'>";      
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      $html .= "X";
      $html .= "</TD>";            
      $html .= "</TABLE>";
    }else{
      $html .= "<TABLE BORDER='1'>";      
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";            
      $html .= "</TABLE>";
    }
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "<TD WIDTH='240' ALIGN='RIGHT' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $emp_id = str_pad($datos['id_emp'], 12, "-", STR_PAD_LEFT);
    for($i=0; $i<12; $i++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='480' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='480' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<b>CC</b>  ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=26>";
    if($datos['tipo_id_tercero']=='CC')
    {
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      $html .= "X";
      $html .= "</TD>";
      $html .= "</TABLE>";
    }else{
      $html .= "<TABLE BORDER='1'>";      
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";            
      $html .= "</TABLE>";
    }
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "<TD WIDTH='240' ALIGN='RIGHT' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='240' HEIGHT=26>";
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
    $html .= "<TD WIDTH='60' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='60' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<b>Código</b>";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $cod_sgsss = str_pad($datos['codigo_sgsss'], 12, "-", STR_PAD_LEFT);
    $html .= "<TD WIDTH='240' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    for($j=0; $j<12; $j++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD ALIGN='LEFT' WIDTH='480' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='480' HEIGHT=26>";
    $html .= "Dirección prestador: ".$datos['direccion_emp']."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='60' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $ind_emp = str_pad($datos['indicativo_emp'], 5, "-", STR_PAD_LEFT);
    $html .= "<TD WIDTH='60' ALIGN='center' HEIGHT=26>";
    $html .= "<b>Teléfono:</b>";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='240' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    for($i=0; $i<5; $i++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";    
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
    $html .= "<TD WIDTH='480' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='480' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";    
    $html .= "<TD WIDTH='760' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='60' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "<TD WIDTH='100' ALIGN='CENTER' HEIGHT=26>";
    $html .= "indicativo";
    $html .= "</TD>";
    $html .= "<TD WIDTH='140' ALIGN='CENTER' HEIGHT=26>";
    $html .= "número";
    $html .= "</TD>";
    $html .= "<TD WIDTH='190' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Departamento: ".$datos['departamento_emp']."";
    $html .= "</TD>";
    $dept_emp = str_pad($datos['tipo_dpto_id_emp'], 2, "-", STR_PAD_LEFT);
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($dept_emp[0]!="-")
    {      
      $html .= "".$dept_emp[0]."";      
    }else{
      $html .= "&nbsp;";      
    }
    $html .= "</TD>";    
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($dept_emp[1]!="-")
    {      
      $html .= "".$dept_emp[1]."";      
    }else{
      $html .= "&nbsp;";      
    }
    $html .= "</TD>";    
    $html .= "<TD WIDTH='190' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Municipio: ".$datos['municipio_emp']."";
    $html .= "</TD>";
    $mpio_emp = str_pad($datos['tipo_mpio_id_emp'], 3, "-", STR_PAD_LEFT);
    for($i=0; $i<3; $i++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='760' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='490' ALIGN='LEFT' HEIGHT=26>";    
    $nom_pag = substr($datos['nombre_tercero'], 0, 30);
    $html .= "ENTIDAD A LA QUE SE LE INFORMA (PAGADOR): ".$nom_pag."";
    $html .= "</TD>";
    $html .= "<TD WIDTH='50' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<b>CODIGO:</b>";
    $html .= "</TD>";
    $id_pagador = str_pad($datos['codigo_sgsss_p'], 12, "-", STR_PAD_LEFT);
    for($i=0; $i<12; $i++)
    {
      if($id_pagador[$i]!="-")
      {
        $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=26>";
        $html .= "".$id_pagador[$i]."";
        $html .= "</TD>";
      }else{
        $html .= "<TD WIDTH='20' ALIGN='LEFT' HEIGHT=26>";
        $html .= "&nbsp;";
        $html .= "</TD>";
      }
    }
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<b>DATOS DEL PACIENTE</b>";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    if($datos['primer_apellido_u']!="")
    {
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
      $html .= "".$datos['primer_apellido_u']."";
      $html .= "</TD>";  
    }else{
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";
    }
    if($datos['segundo_apellido_u']!="")
    {
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
      $html .= "".$datos['segundo_apellido_u']."";
      $html .= "</TD>";  
    }else{
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
      $html .= "no tiene";
      $html .= "</TD>";
    }
    if($datos['primer_nombre_u']!="")
    {
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
      $html .= "".$datos['primer_nombre_u']."";
      $html .= "</TD>";  
    }else{
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";
    }
    if($datos['segundo_nombre_u']!="")
    {
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
      $html .= "".$datos['segundo_nombre_u']."";
      $html .= "</TD>";  
    }else{
      $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
      $html .= "no tiene";
      $html .= "</TD>";
    }
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<b>";
    $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
    $html .= "1er Apellido";
    $html .= "</TD>";
    $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
    $html .= "2do Apellido";
    $html .= "</TD>";
    $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
    $html .= "1er Nombre";
    $html .= "</TD>";
    $html .= "<TD WIDTH='195' ALIGN='CENTER' HEIGHT=26>";
    $html .= "2do Nombre";
    $html .= "</TD>";
    $html .= "</b>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='190' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<b>Tipo Documento de Identificación</b>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['tipoId_u']=="RC")
    {        
      $html .= "X";      
    }else{
      $html .= "&nbsp;";      
    }
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='150' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Registro Civil";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['tipoId_u']=="PA")
    {
      $html .= "X"; 
    }else{
      $html .= "&nbsp;";
    }
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='180' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Pasaporte";
    $html .= "</TD>";
    $html .= "<TD WIDTH='340' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $id_u = str_pad($datos['noId_u'], 17, "-", STR_PAD_LEFT);
    for($j=0; $j<18; $j++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['tipoId_u']=="TI")
    {        
      $html .= "X";      
    }else{
      $html .= "&nbsp;";      
    }
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='150' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Tarjeta de identidad";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['tipoId_u']=="AS")
    {
      $html .= "X"; 
    }else{
      $html .= "&nbsp;";
    }
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='180' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Adulto sin identificación";
    $html .= "</TD>";
    $html .= "<TD WIDTH='340' ALIGN='CENTER' HEIGHT=26>";
    $html .= "Numero documento de identificación";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['tipoId_u']=="CC")
    {        
      $html .= "X";      
    }else{
      $html .= "&nbsp;";      
    }
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='150' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Cédula de ciudadanía";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['tipoId_u']=="MS")
    {
      $html .= "X"; 
    }else{
      $html .= "&nbsp;";
    }
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='180' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Menor sin identificacíon";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['tipoId_u']=="CE")
    {        
      $html .= "X";      
    }else{
      $html .= "&nbsp;";      
    }
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='150' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Cédula de extranjería";
    $html .= "</TD>";
    $html .= "<TD WIDTH='200' ALIGN='CENTER' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "<TD WIDTH='130' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<b>Fecha de Nacimiento</b>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='260' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    for($i=0; $i<10; $i++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      $html .= "".$datos['fecha_nacimiento_u'][$i]."";
      $html .= "</TD>";
    }
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='500' ALIGN='LEFT' HEIGHT=26>";
    if($datos['residencia_direccion_u']!="")
      $html .= "Dirección de Residencia Habitual: ".$datos['residencia_direccion_u']."";
    else
      $html .= "Dirección de Residencia Habitual: no tiene";
    $html .= "</TD>";
    $html .= "<TD WIDTH='80' ALIGN='LEFT' HEIGHT=26>";
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
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='780' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='340' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Departamento: ".$datos['departamento_u']."";
    $html .= "</TD>";
    $dept_u = str_pad($datos['tipo_dpto_id_u'], 2, "-", STR_PAD_LEFT);
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($dept_u[0]!="-")
    {      
      $html .= "".$dept_u[0]."";      
    }else{
      $html .= "&nbsp;";      
    }
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($dept_u[1]!="-")
    {      
      $html .= "".$dept_u[1]."";      
    }else{
      $html .= "&nbsp;";      
    }
    $html .= "</TD>";
    $html .= "<TD WIDTH='340' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Municipio: ".$datos['municipio_u']."";
    $html .= "</TD>";
    $mpio_u = str_pad($datos['tipo_mpio_id_u'], 3, "-", STR_PAD_LEFT);
    for($i=0; $i<3; $i++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='780' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='100' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Teléfono celular";
    $html .= "</TD>";
    $tel_cel = str_pad($datos['celular_telefono'], 10, "-", STR_PAD_LEFT);
    for($i=0; $i<10; $i++)
    {
      $html .= "<TD WIDTH='20' HEIGHT=26>";
      if($tel_cel[$i]!="-")
        $html .= "".$tel_cel[$i]."";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
    }
    $html .= "<TD WIDTH='480' HEIGHT=26>";
    $html .= "Correo electrónico  ".$datos['email']."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<b>Cobertura en salud</b>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['regimen_id']=="1")
      $html .= "X";
    else
      $html .= "&nbsp;";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='135' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Regimen Contributivo";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['regimen_id']=="22")
      $html .= "X";
    else
      $html .= "&nbsp;";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='205' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Regimen Subsidiado - parcial";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['regimen_id']=="9")
      $html .= "X";
    else
      $html .= "&nbsp;";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='205' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Poblacion pobre no Asegurada sin SISBEN";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['regimen_id']=="10")
      $html .= "X";
    else
      $html .= "&nbsp;";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='135' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Plan adicional de salud";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['regimen_id']=="2")
      $html .= "X";
    else
      $html .= "&nbsp;";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='135' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Regimen Subsidiado - total";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['regimen_id']=="3")
      $html .= "X";
    else
      $html .= "&nbsp;";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='205' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Poblacion pobre no Asegurada con SISBEN";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['regimen_id']=="6" OR $datos['regimen_id']=="7" OR $datos['regimen_id']=="8")
      $html .= "X";
    else
      $html .= "&nbsp;";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='205' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Desplazado";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['regimen_id']=="5" OR $datos['regimen_id']=="4")
      $html .= "X";
    else
      $html .= "&nbsp;";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='135' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Otro";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=26><b>INFORMACION DE LA ATENCION</b>";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' HEIGHT=26>";
    $html .= "<TABLE>";
    $html .= "<TR>";
    $html .= "<TD WIDTH=390 HEIGHT=26><b>Origen de la atención</b></TD>";    
    $html .= "<TD WIDTH=200 HEIGHT=26><b>Tipo de servicios solicitados</b></TD>";  
    $html .= "<TD WIDTH=190 HEIGHT=26><b>Prioridad de la atencion</b></TD>";
    $html .= "</TR>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='120' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Enfermedad General";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='110' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Accidente de trabajo";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='100' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Evento Catastrófico";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['tipo_servicio']=="1")
      $html .= "X";
    else
      $html .= "&nbsp;";
    
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='180' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Posterior a la atencion de urgencias";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "".(($datos['prioridad_servicio']=="1")? "X":"&nbsp;")."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='170' ALIGN='LEFT' HEIGHT=26>Prioritaria</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='120' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Enfermedad Profesional";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='110' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Accidente de Tránsito";
    $html .= "</TD>";
    if($cont==5)
    {
      $html .= "<TD WIDTH='20' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      $html .= "X";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='100' ALIGN='LEFT' HEIGHT=26>";
      $html .= "Otro";
      $html .= "</TD>";
    }else{
      $html .= "<TD WIDTH='120' ALIGN='LEFT' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";
    }
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    if($datos['tipo_servicio']=="2")
      $html .= "X";
    else
      $html .= "&nbsp;";
  
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='180' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Servicios electivos";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "".(($datos['prioridad_servicio']=="2")? "X":"&nbsp;")."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='170' ALIGN='LEFT' HEIGHT=26>No prioritaria</TD>";
    $html .= "</TR>";
    if($datos['solicitud']=="manual")
    {
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=26>";
      $html .= "<b>Ubicación del Paciente al momento de la solicitud de autorización:</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      if($datos['desc_serv']=="AMBULATORIO")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='90' ALIGN='LEFT' HEIGHT=26>";
      $html .= "Consulta Externa";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      if($datos['desc_serv']=="HOSPITALARIO" || $datos['desc_serv']=="U.C.I." || $datos['desc_serv']=="CIRUGIA" || $datos['desc_serv']=="HOSPITALIZACION")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='70' ALIGN='LEFT' HEIGHT=26>";
      $html .= "Hospitalización";
      $html .= "</TD>";
      $html .= "<TD WIDTH='60' ALIGN='CENTER' HEIGHT=26>";
      $html .= "Servicio";
      $html .= "</TD>";
      $html .= "<TD WIDTH='360' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='360' ALIGN='LEFT' HEIGHT=26>";
      if($datos['desc_serv'])
        $html .= "".$datos['desc_serv']."";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='40' ALIGN='CENTER' HEIGHT=26>";
      $html .= "Cama";
      $html .= "</TD>";
      $html .= "<TD WIDTH='120' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $no_cama = str_pad($datos['cama'], 6, "-", STR_PAD_LEFT);
      for($i=0; $i<6; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
        if($no_cama[$i]!="-")
          $html .= "".$no_cama[$i]."";
        else
          $html .= "&nbsp;";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      if($datos['desc_serv']=="URGENCIAS")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='760' ALIGN='LEFT' HEIGHT=26>";
      $html .= "Urgencias";
      $html .= "</TD>";
      $html .= "</TR>";
    }else
    {
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=26>";
      $html .= "<b>Ubicación del Paciente al momento de la solicitud de autorización:</b>";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      /*if($datos['via_ingreso_nombre']=="Cosulta Externa")
        $html .= "X";
      else
        $html .= "&nbsp;";*/
      if($datos[0][0]['desc_servicio']=="AMBULATORIO")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='90' ALIGN='LEFT' HEIGHT=26>";
      $html .= "Consulta Externa";
      $html .= "</TD>";
      $html .= "<TD WIDTH='20' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      if($datos[0][0]['desc_servicio']=="HOSPITALARIO" || $datos[0][0]['desc_servicio']=="U.C.I." || $datos[0][0]['desc_servicio']=="CIRUGIA")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='70' ALIGN='LEFT' HEIGHT=26>";
      $html .= "Hospitalización";
      $html .= "</TD>";
      $html .= "<TD WIDTH='60' ALIGN='CENTER' HEIGHT=26>";
      $html .= "Servicio";
      $html .= "</TD>";
      $html .= "<TD WIDTH='360' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='360' ALIGN='LEFT' HEIGHT=26>";
      if($datos[0][0]['desc_servicio'])
        $html .= "".$datos[0][0]['desc_servicio']."";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='40' ALIGN='CENTER' HEIGHT=26>";
      $html .= "Cama";
      $html .= "</TD>";
      $html .= "<TD WIDTH='120' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $no_cama = str_pad($datos['cama'], 6, "-", STR_PAD_LEFT);
      for($i=0; $i<6; $i++)
      {
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
        if($no_cama[$i]!="-")
          $html .= "".$no_cama[$i]."";
        else
          $html .= "&nbsp;";
        $html .= "</TD>";
      }
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
      $html .= "<TR>";
      $html .= "<TD WIDTH='20' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      if($datos[0][0]['desc_servicio']=="URGENCIAS")
        $html .= "X";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "<TD WIDTH='760' ALIGN='LEFT' HEIGHT=26>";
      $html .= "Urgencias";
      $html .= "</TD>";
      $html .= "</TR>";
    }
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Manejo integral según Guia de: ";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='160' ALIGN='CENTER' HEIGHT=26>";
    $html .= "Código CUPS";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "<TD WIDTH='60' ALIGN='CENTER' HEIGHT=26>";
    $html .= "Cantidad";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "<TD WIDTH='520' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Descripción";
    $html .= "</TD>";
    $html .= "</TR>";
    /*if($datos['solicitud']=="manual")
    {*/
      $m = 1;
      foreach($datos['cargos'] as $indice => $valor)
      {
        $html .= "<TR>";
        $html .= "<TD WIDTH='20' ALIGN='RIGHT' HEIGHT=26>";
        $html .= "".$m."";
        $html .= "</TD>";
        $cargo = str_pad($valor['cargo'], 7, "-", STR_PAD_LEFT);
        $html .= "<TD WIDTH='20' HEIGHT=26>";
        $html .= "<TABLE BORDER='1'>";
        for($j=0; $j<7; $j++)
        {        
          $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
          if($cargo[$j]!="-")  
            $html .= "".$cargo[$j]."";
          else
            $html .= "&nbsp;";
          $html .= "</TD>";        
        }
        $html .= "</TABLE>";
        $html .= "</TD>";
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
        $html .= "&nbsp;";
        $html .= "</TD>";
        $cantidad = str_pad($valor['cantidad'], 3, "-", STR_PAD_LEFT);
        $html .= "<TD WIDTH='20' HEIGHT=26>";
        $html .= "<TABLE BORDER='1'>";
        for($k=0; $k<3; $k++)
        {
          $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
          if($cantidad[$k]!="-")  
            $html .= "".$cantidad[$k]."";
          else
            $html .= "&nbsp;";
          $html .= "</TD>";
        }
        $html .= "</TABLE>";
        $html .= "</TD>";
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
        $html .= "&nbsp;";
        $html .= "</TD>";
        $html .= "<TD WIDTH='520' ALIGN='LEFT' HEIGHT=26>";
        for($l=0; $l<80; $l++)
          $html .= "".$valor['desc_cargo'][$l]."";
        $html .= "</TD>";
        $html .= "</TR>";
        $m++;
      }
    /*}else
    {
      $num_cargos = count($datos['cargos']);
      for($i=0; $i<$num_cargos; $i++)
      {
        $html .= "<TR>";
        $html .= "<TD WIDTH='20' ALIGN='RIGHT' HEIGHT=26>";
        $cons = $i+1;
        $html .= "".$cons."";
        $html .= "</TD>";
        $cargo = str_pad($datos['cargos'][$i], 7, "-", STR_PAD_LEFT);
        $html .= "<TD WIDTH='20' HEIGHT=26>";
        $html .= "<TABLE BORDER='1'>";
        for($j=0; $j<7; $j++)
        {        
          $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
          if($cargo[$j]!="-")  
            $html .= "".$cargo[$j]."";
          else
            $html .= "&nbsp;";
          $html .= "</TD>";        
        }
        $html .= "</TABLE>";
        $html .= "</TD>";
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
        $html .= "&nbsp;";
        $html .= "</TD>";
        $cantidad = str_pad($datos[0][$i]['cantidad'], 3, "-", STR_PAD_LEFT);
        $html .= "<TD WIDTH='20' HEIGHT=26>";
        $html .= "<TABLE BORDER='1'>";
        for($k=0; $k<3; $k++)
        {
          $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
          if($cantidad[$k]!="-")  
            $html .= "".$cantidad[$k]."";
          else
            $html .= "&nbsp;";
          $html .= "</TD>";
        }
        $html .= "</TABLE>";
        $html .= "</TD>";
        $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
        $html .= "&nbsp;";
        $html .= "</TD>";
        $html .= "<TD WIDTH='520' ALIGN='LEFT' HEIGHT=26>";
        for($l=0; $l<80; $l++)
          $html .= "".$datos[0][$i]['desc_cargo'][$l]."";
        $html .= "</TD>";
        $html .= "</TR>";
      }
    }*/
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='780' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Justificación Clinica:";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' HEIGHT=26>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='130' ALIGN='LEFT' HEIGHT=26>";
    $html .= "<b>Impresion diagnostica</b>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='80' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<b>Codigo CIE10</b>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='570' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<b>descripcion</b>";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    if($datos['solicitud']=="manual")
    {
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='130' ALIGN='LEFT' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='80' ALIGN='CENTER' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='570' ALIGN='CENTER' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
    }
    if(!empty($datos['diagnosticos']))
    {
      $cant_d = count($datos['diagnosticos']);
      $j = 0;
      foreach($datos['diagnosticos'] as $k => $dat)
      //$j=0; $j<$cant_d; $j++)
      {
        
        $html .= "<TR>";
        $tipodiag = str_pad($dat['diagnostico_id'], 4, "-", STR_PAD_LEFT);
        $html .= "<TD WIDTH='130' ALIGN='CENTER' HEIGHT=26>";
        if($j!=0)
          $html .= "Diagnostico relacionado ".$j."";
        else
          $html .= "Diagnostico principal";
        
        $html .= "</TD>";
        $html .= "<TABLE BORDER='1'>";
        for($i=0; $i<4; $i++)
        {
          $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
          if($tipodiag[$i]!="-")
          {
            $html .= "".$tipodiag[$i]."";
          }else{
            $html .= "&nbsp;";
          }
          $html .= "</TD>";
        }
        $html .= "</TABLE>";
        $html .= "<TD WIDTH='10' ALIGN='LEFT' HEIGHT=26>";
        $html .= "&nbsp;";
        $html .= "</TD>";
        $html .= "<TD WIDTH='560' ALIGN='LEFT' HEIGHT=26>";
        for($k=0; $k<90; $k++)
        {
          $html .= "".$dat['diagnostico_nombre'][$k]."";
        }
        $html .= "</TD>";
        $html .= "</TR>";
        $j++;
        if($j == 3) break;
      }
    }else{
      $html .= "<TR>";
      $html .= "<TD WIDTH='780' HEIGHT=26>";
      $html .= "<TABLE BORDER='1'>";
      $html .= "<TD WIDTH='130' ALIGN='LEFT' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='80' ALIGN='CENTER' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "<TD WIDTH='570' ALIGN='CENTER' HEIGHT=26>";
      $html .= "&nbsp;";
      $html .= "</TD>";
      $html .= "</TABLE>";
      $html .= "</TD>";
      $html .= "</TR>";
    }
    $html .= "<TR>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='780' ALIGN='CENTER' HEIGHT=26>";
    $html .= "<b>INFORMACION DE LA PERSONA QUE SOLICITA</b>";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='360' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Nombre de quien reporta  ".$datos['nomb_prof']."";
    $html .= "</TD>";
    $html .= "<TD WIDTH='60' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Teléfono  ";
    $html .= "</TD>";
    $len_tel_us = strlen($datos['tel_prof']);
    for($i=0; $i<$len_tel_us;$i++)
    {
      if(is_numeric($datos['tel_prof'][$i]))
        $num_tel_us = $num_tel_us.$datos['tel_prof'][$i];
      else
        break;
    }
    $numtelus = str_pad($num_tel_us, 7, "-", STR_PAD_LEFT);
    $ind_us = str_pad($datos['indicativo_prof'], 5, "-", STR_PAD_LEFT);
    $ext_us = str_pad($datos['extencion_prof'], 6, "-", STR_PAD_LEFT);
    for($j=0; $j<5; $j++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
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
    $html .= "<TD WIDTH='360' ALIGN='CENTER' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";    
    $html .= "<TD WIDTH='60' ALIGN='CENTER' HEIGHT=26>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "<TD WIDTH='100' ALIGN='CENTER' HEIGHT=26>";
    $html .= "indicativo";
    $html .= "</TD>";
    $html .= "<TD WIDTH='140' ALIGN='CENTER' HEIGHT=26>";
    $html .= "número";
    $html .= "</TD>";
    $html .= "<TD WIDTH='120' ALIGN='CENTER' HEIGHT=26>";
    $html .= "extensión";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='360' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Cargo o actividad:  ".$datos['desc_prof']."";
    $html .= "</TD>";
    $html .= "<TD WIDTH='220' ALIGN='LEFT' HEIGHT=26>";
    $html .= "Teléfono celular:  ";
    $html .= "</TD>";
    for($j=0; $j<10; $j++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER' HEIGHT=26>";
      if($datos['tel_cel_prof'][$j]!="")
        $html .= "".$datos['tel_cel_prof'][$j]."";
      else
        $html .= "&nbsp;";
      $html .= "</TD>";
    }
    $html .= "</TABLE>";
    $html .= "</TR>";
    $html .= "</TABLE>";
    
    $pdf->WriteHTML($html);
    $pdf->SetLineWidth(0.3);
    $pdf->Rect(9, 5, 195, 263, '');
    $pdf->Output($Dir, 'F');
    
    return true;
  }
?>