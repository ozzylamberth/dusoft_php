<?php 
 	/**
  * $Id: InformeOrdenPedido_fpdf.class.php,v 1.1 2009/10/21 22:04:39 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Sandra Viviana Pantoja
  */
  class InformeOrdenPedido_fpdf
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function InformeOrdenPedido_fpdf(){}
    /**
    * Funcion para generar el archivo xml
    *
    * @param array $parametros Arreglo de oarametros del request
    *
    * @return boolean
    */
    function GetReporteFPDF($datos,$nombre)
    {
	
	$mdl = AutoCarga::factory("Compras_orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
   // $inf=$mdl->ConsultarDetalleCompra($datos['orden_pedido_id']);
   	$tipoid=$datos['tipo_id_tercero'];
	$id=$datos['tercero_id'];
					
    $empresaG=$mdl->SeleccionarInformacionEmpresa($datos['empresa_id']);
	$proveedor=$mdl->ConsultarInformacionProveedor($datos['codigo_proveedor_id']);
	$condicione=$mdl->ConsultarCondicionesCompra($datos['orden_pedido_id']);
	$detalle=$mdl->ConsultarDetalleDeOrdenCompra($datos['orden_pedido_id']);
	$usuario=$mdl->consultarInformacionUsuarioActual();
	$razon_social=$empresaG[0]['razon_social'];
	$direccion=$empresaG[0]['direccion'];
	$telefonos=$empresaG[0]['telefonos'];
	$id=$empresaG[0]['id'];
	$tipo_id_tercero=$empresaG[0]['tipo_id_tercero'];
	
	

	
	
     /* $nvd = AutoCarga::factory('Resolucion3047','classes','app','ReclamacionServicios');
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
      $destino      = $mdl->ObtenerDestinoPaciente($rst['ingreso']);
      $coberturas   = $mdl->ConsCoberturaSalud($rst['ingreso']);

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
      
      $datos['diagnosticos'] = $diagnosticos;*/
      
      $this->GenerarAtencionUrgencias($nombre,$datos,$empresaG,$proveedor,$condicione,$detalle,$usuario);
      return true;
    }
    /**
    *
    */
    function GenerarAtencionUrgencias($Dir,$datos,$empresaG,$proveedor,$condicione,$detalle,$usuario)
    {
     
      define('FPDF_FONTPATH', 'font/');
      $pdf=new PDF('P', 'mm', 'letter');//legal
	   
	 //$pdf->Image('images/escudo-colombia.jpg',15,10,10);
      $pdf->AddPage();
     
      $pdf->SetFont('Arial','',7);
	 
		$today = date("Y-m-d"); 
		$fdatos=explode("-", $today);
		$fedatos= $fdatos[0]."-".$fdatos[1]."-".$fdatos[2];
		
		 $hora = date("H:i");
		
	$html.="<TABLE WIDTH='780'>";
    /**/
    $html .= "<TR>";
    $html .= "<TD WIDTH='780' HEIGHT=25 ALIGN='CENTER'>";
    $html .= "<b>ORDEN DE COMPRA</b>";
    $html .= "</TD>";
    $html .= "</TR>";  
    $html .= "<TR>";
    $html .= "<TD WIDTH='500' ALIGN='RIGHT'>";
    $html .= "<b>NUMERO ORDEN</b> ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='80' ALIGN='RIGHT'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
    $html .= "".$datos['orden_pedido_id']."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='50' ALIGN='RIGHT'>";
    $html .= "<b>Fecha:</b> ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='200' ALIGN='RIGHT'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='80' ALIGN='CENTER'>";
    $html .= "".$fedatos."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
	 $html .= "<TD WIDTH='50' ALIGN='RIGHT'>";
    $html .= "<b>Hora:</b> ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='200' ALIGN='RIGHT'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='80' ALIGN='CENTER'>";
    $html .= "".$hora."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    
    $html .= "</TABLE>";
	
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='200' ALIGN='LEFT'>";
    $html .= "<b>INFORMACION DE LA EMPRESA</b>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='480' ALIGN='LEFT'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='480' ALIGN='LEFT'>";
    $html .= "Empresa  ".$empresaG[0]['razon_social']."";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
    $html .= "<b>NIT</b>  ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
    if($empresaG[0]['tipo_id_tercero']=='NIT')
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
    $emp_id = str_pad($empresaG[0]['id'], 12, "-", STR_PAD_LEFT);
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
    if($empresaG[0]['tipo_id_tercero']=='CC')
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
    if($empresaG[0]['tipo_id_tercero']=='CC')
    {
      $html .= "Número      ".$empresaG[0]['id']."";
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
    $cod_sgsss = str_pad($empresaG[0]['codigo_sgsss'], 12, "-", STR_PAD_LEFT);
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
    $html .= "Dirección: ".$empresaG[0]['direccion']."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='60'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='60' ALIGN='center'>";
    $html .= "<b>Teléfono:</b>";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='240'>";
    $html .= "<TABLE BORDER='1'>";
    $tel_emp = str_pad($empresaG[0]['telefonos'], 7, "-", STR_PAD_LEFT);
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
    $html .= "<TD WIDTH='580'>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
	$html .= "</TR>";
	 $html .= "<TR>";
    $html .= "<TD WIDTH='200' ALIGN='LEFT'>";
    $html .= "<b>INFORMACION DEL PROVEEDOR </b>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='480' ALIGN='LEFT'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='480' ALIGN='LEFT'>";
    $html .= "Proveedor: ".$proveedor[0]['nombre_tercero']."";
    $html .= "</TD>";    
    $html .= "</TABLE>";    
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
    $html .= "<b>NIT</b>  ";
    $html .= "</TD>";
    $html .= "<TD WIDTH='20' ALIGN='LEFT'>";
    if($proveedor[0]['tipo_id_tercero']=='NIT')
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
    $emp_id = str_pad($proveedor[0]['tercero_id'], 12, "-", STR_PAD_LEFT);
    for($i=0; $i<12; $i++)
    {
      $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
      if($proveedor[0]['tipo_id_tercero']=='NIT' && $emp_id[$i]!='-')
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
    if($proveedor[0]['tipo_id_tercero']=='CC')
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
    if($proveedor[0]['tipo_id_tercero']=='CC')
    {
      $html .= "Número      ".$proveedor[0]['tercero_id']."";
    }
	else{
      $html .= "Número                                             DV ".$proveedor[0]['dv']."";
    }
    $html .= "</TD>";    
    $html .= "</TABLE>";
    $html .= "</TD>";
     
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='60' ALIGN='LEFT'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='480'>";
    $html .= "Dirección: ".$proveedor[0]['direccion']."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='60'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='60' ALIGN='center'>";
    $html .= "<b>Teléfono:</b>";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='240'>";
    $html .= "<TABLE BORDER='1'>";
    $tel_emp = str_pad($proveedor[0]['telefono'], 7, "-", STR_PAD_LEFT);
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
    $html .= "<TD WIDTH='580'>";
    $html .= "&nbsp;";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TR>";
	$html .= "</TR>";
	
	$html .= "<TR>";
    $html .= "<TD WIDTH='200' ALIGN='LEFT'>";
    $html .= "<b>CONDICIONES DE COMPRA </b>";
    $html .= "</TD>";
    $html .= "</TR>";
    $html .= "<TABLE BORDER='1'>";
	
	foreach($condicione as $key => $dtl)
	{
    $html .= "<TR>";
    $html .= "<TD WIDTH='780'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='780' ALIGN='LEFT'>";    
    $html .= "".$dtl['condicion']."";
    $html .= "</TD>";
     $html .= "</TABLE>";
    $html .= "</TD>";
    $html .= "</TR>";
	}
	 
	$html .= "<TR>";
    $html .= "<TD WIDTH='780' ALIGN='CENTER'>";
    $html .= "<b>DETALLE DE LA ORDEN DE COMPRA</b>";
    $html .= "</TD>";
    $html .= "</TR>";
	
    $html .= "</TABLE>";
  	$html .= "<TABLE BORDER='1'>";
    $html .= "<TR>";
    $html .= "<b>";
    $html .= "<TD WIDTH='150' ALIGN='CENTER'>";
    $html .= "CODIGO";
    $html .= "</TD>";
    $html .= "<TD WIDTH='250' ALIGN='CENTER'>";
    $html .= "DESCRIPCION";
    $html .= "</TD>";
    $html .= "<TD WIDTH='90' ALIGN='CENTER'>";
    $html .= "CANTIDAD";
    $html .= "</TD>";
    $html .= "<TD WIDTH='90' ALIGN='CENTER'>";
    $html .= "V.UNITARIO";
    $html .= "</TD>";
	$html .= "<TD WIDTH='90' ALIGN='CENTER'>";
    $html .= " V.TOTAL";
    $html .= "</TD>";
	$html .= "<TD WIDTH='110' ALIGN='CENTER'>";
    $html .= " IVA";
    $html .= "</TD>";
    $html .= "</b>";
    $html .= "</TR>";
 
    $html .= "<TR>";
    $html .= "<TD WIDTH='20'>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='20' ALIGN='CENTER'>";
	$subtotal=0;
	$totaliva=0;
	foreach($detalle as $key3 => $fila)
	{
	$html .= "<TABLE BORDER='1'>";
    $html .= "<TR>";
    $html .= "<TD WIDTH='150' ALIGN='CENTER'>";
    $html .= "".$fila['codigo_producto']."";
    $html .= "</TD>";
    $html .= "<TD WIDTH='250' ALIGN='CENTER'>";
    $html .= "".$fila['producto']." ".$fila['abreviatura']." x ".$fila['cantidad']."";
    $html .= "</TD>";
    $html .= "<TD WIDTH='90' ALIGN='CENTER'>";
    $html .= "".$fila['numero_unidades']."";
    $html .= "</TD>";
    $html .= "<TD WIDTH='90' ALIGN='CENTER'>";
    $html .= "".$fila['valor_unitario']."";
    $html .= "</TD>";
	$html .= "<TD WIDTH='90' ALIGN='CENTER'>";
    $html .= " ".$fila['valor']." ";
    $html .= "</TD>";
	$html .= "<TD WIDTH='110' ALIGN='CENTER'>";
	$valoriva=(($fila['valor'] * $fila['porc_iva'])/100);
	
	$html .= " ".$valoriva."";
    $html .= "</TD>";
    $html .= "</b>";
    $subtotal=$subtotal+ $fila['valor'];
	$totaliva=$totaliva + $valoriva;
    $html .= "</TR>";
	}
	
	if($proveedor[0]['porcentaje_ica']!=0)
	{
	
	$html .= "<TR>";
	$html .= "</TR>";
    $html .= "</TABLE>";
  	$html .= "<TABLE BORDER='1'>";
    $html .= "<TR>";
    $html .= "<b>";
    $html .= "<TD WIDTH='200' ALIGN='CENTER'>";
    $html .= "<b>SUBTOTAL : ".$subtotal." </b>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='180' ALIGN='CENTER'>";
    $html .= "<b>IVA : ".$totaliva." </b>";
    $html .= "</TD>";
	$subtotaliva= $subtotal + $totaliva;
	$ica=(($subtotal * $proveedor[0]['porcentaje_ica'])/1000);
	$retefuente=(($subtotal * $proveedor[0]['porcentaje_rtf'])/100);
	$stotal=$subtotaliva-$retefuente;
	$total=$stotal-$ica;
    $html .= "<TD WIDTH='200' ALIGN='CENTER'>";
    $html .= "<b>RETEFUENTE  : ".$retefuente."</b>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='200' ALIGN='CENTER'>";
    $html .= "<b>TOTAL ; ".$total."</b>";
    $html .= "</TD>";
	$html .= "</b>";
    $html .= "</TR>";
	$html .= "</TABLE>";
	}
	else 
	{
	$html .= "<TR>";
	$html .= "</TR>";
    $html .= "</TABLE>";
  	$html .= "<TABLE BORDER='1'>";
    $html .= "<TR>";
    $html .= "<b>";
    $html .= "<TD WIDTH='200' ALIGN='CENTER'>";
    $html .= "<b>SUBTOTAL : ".$subtotal." </b>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='180' ALIGN='CENTER'>";
    $html .= "<b>IVA : ".$totaliva."</b>";
    $html .= "</TD>";
	$subtotaliva= $subtotal + $totaliva;
	$retefuente=(($subtotal * $proveedor[0]['porcentaje_rtf'])/100);
	$total=$subtotaliva- $retefuente;
    $html .= "<TD WIDTH='200' ALIGN='CENTER'>";
    $html .= "<b>RETEFUENTE  : ".$retefuente."</b>";
    $html .= "</TD>";
    $html .= "<TD WIDTH='200' ALIGN='CENTER'>";
    $html .= "<b>TOTAL : ".$total."</b>";
    $html .= "</TD>";
	$html .= "</b>";
    $html .= "</TR>";
	$html .= "</TABLE>";
	}
	
	
    $html .= "<TABLE BORDER='0'>";
  
    $html .= "<TD WIDTH='180' ALIGN='CENTER'>";
    $html .= "<b>PERSONA QUE REALIZA EL PEDIDO</b>";
    $html .= "</TD>";
  
	 
	
    $html .= "</TABLE>";
    $html .= "</TR>";
    $html .= "<TR>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='380' ALIGN='LEFT'>";
    $html .= "Nombre ".$usuario[0]['nombre']."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "<TABLE BORDER='1'>";
    $html .= "<TD WIDTH='400' ALIGN='LEFT'>";
    $html .= "Cargo o actividad:  ".$usuario[0]['descripcion']."";
    $html .= "</TD>";
    $html .= "</TABLE>";
    $html .= "</TR>";
    $html .= "</TABLE>";
   
      $pdf->WriteHTML($html);
     $pdf->SetLineWidth(0.5);
      $pdf->Rect(8, 5, 200, 268, '');
      $pdf->Output($Dir, 'F');
      
      return true;
    }
  }
?>