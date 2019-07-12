<?php
  /**
  * $Id: ImprimirHTML.class.php
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor
  */
  IncludeClass('Habitaciones','','app','Cuentas');
	IncludeClass('ImprimirSQL','','app','Cuentas');
	IncludeClass('LiquidacionHabitacionesCorte');
	class ImprimirHTML
	{
    /**
    * Constructor de la clase
    */
		function ImprimirHTML(){}
		/**
		* Funcion donde se imprime el recibo de caja
		* 
		* @return array 
		*/
		function FormaImprimir($PlanId,$Cuenta,$Ingreso,$Estado,$TipoId,$PacienteId)
		{
      $consulta_imprime= new ImprimirSQL();
      $datos = $consulta_imprime->ConsultaPagosCaja($Cuenta);
      $datos1 = $consulta_imprime->BuscarDatosCajaRapida($Cuenta);
      
      $html  = "<script language='javascript'>\n";
      $html .= "  var rem=\"\";\n";
      $html .= "  function abreVentana(url2)\n";
      $html .= "  {\n";
      $html .= "    var width=\"400\"\n";
      $html .= "    var height=\"300\"\n";
      $html .= "    var winX=Math.round(screen.width/2)-(width/2);\n";
      $html .= "    var winY=Math.round(screen.height/2)-(height/2);\n";
      $html .= "    var nombre=\"Printer_Mananger\";\n";
      $html .= "    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
      $html .= "    window.open(url2, nombre, str).focus();\n";
      $html .= "  };\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {;\n";
      $html .= "   src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<table border=\"0\" width=\"100%\" align=\"center\">";
      $html .= " <tr><td><fieldset><legend class=\"field\">CONSULTAS ANTERIORES CAJA</legend>";
      $html .= "<br><table  align=\"center\" border=\"0\" width=\"85%\">";
      $html .= "<tr class=\"modulo_table_list_title \">";
      $html .= "  <td>Recibo de Caja</td>";
      $html .= "  <td>Fecha de Registro</td>";
      $html .= "  <td>Total Efectivos</td>";
      $html .= "  <td>Total Cheques</td>";
      $html .= "  <td>Total Tarjetas</td>";
      $html .= "  <td>Total Bonos</td>";
      $html .= "  <td>Total</td>";
			//$html .= "  <td>Info</td>"; 
			$html .= "  <td>PDF</td>";
			//$html .= "  <td>POS</td>";
      $html .= "</tr>";
      //$html .= "<pre>".print_r($_SESSION,true)."</pre>";
      for($i=0;$i<sizeof($datos);$i++)
      {
        $rcaja=$datos[$i][recibo_caja];
        $empresa=$datos[$i][empresa_id];
        $centro=$datos[$i][centro_utilidad];
        $fech=$datos[$i][fecha_registro];
        $Te=FormatoValor($datos[$i][total_efectivo]);
        $Tc=FormatoValor($datos[$i][total_cheques]);
        $Tt=FormatoValor($datos[$i][total_tarjetas]);
        $Tb=FormatoValor($datos[$i][total_bonos]);

        $TOTAL=FormatoValor($datos[$i][total_abono]);
        if( $i % 2){ $estilo='modulo_list_claro';}
        else {$estilo='modulo_list_oscuro';}
        $html.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
        $html.="  <td>".$datos[$i][prefijo]." - ".$rcaja."</td>";
        $html.="  <td>$fech</td>";
        $html.="  <td>$Te</td>";
        $html.="  <td>$Tc</td>";
        $html.="  <td>$Tt</td>";
        $html.="  <td>$Tb</td>";
        $html.="  <td class=\"label_error\">$TOTAL</td>";
       
        
        $url_pos=ModuloGetURL('app','CajaGeneral','user','Imprimir_POS_Recibo_Hosp',array('prefijo'=>$datos[$i][prefijo],'cajaid'=>$datos[$i][caja_id],'Recibo'=>$rcaja,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'empresa'=>$empresa,'cu'=>$centro,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'NombrePaciente'=>$NombrePaciente));
        $action_pdf=ModuloGetURL('app','Cuentas','user','ImprimirRecibocaja',array('prefijo'=>$datos[$i][prefijo],'cajaid'=>$datos[$i][caja_id],'Recibo'=>$rcaja,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'empresa'=>$empresa,'cu'=>$centro,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'NombrePaciente'=>$NombrePaciente));
       
        //$html.="  <td><a href=\"$accion\">VER</a></td>";
        $html.="  <td><a href=\"javascript:abreVentana('".$action_pdf."')\">IMPRIMIR</a></td>";
        //$html.="  <td><a href=\"$url_pos\">IMPRIMIR POS</a></td>";
        $html.="</tr>";
      }
        $html .= "</table>\n";
        $html.="</tr>";
        
      $html .= " <tr><td><fieldset><legend class=\"field\">RECIBOS CAJAS RAPIDAS</legend>";
      $html .= "<br><table  align=\"center\" border=\"0\" width=\"85%\">";
      $html .= "<tr class=\"modulo_table_list_title \">";
      $html .= "  <td>Recibo de Caja</td>";
      $html .= "  <td>Fecha de Registro</td>";
      $html .= "  <td>Total Efectivos</td>";
      $html .= "  <td>Total Cheques</td>";
      $html .= "  <td>Total Tarjetas</td>";
      $html .= "  <td>Total Bonos</td>";
      $html .= "  <td>Total</td>";
			//$html .= "  <td>Info</td>"; 
			$html .= "  <td>PDF</td>";
			//$html .= "  <td>POS</td>";
      $html .= "</tr>";
      //$html .= "<pre>".print_r($datos1,true)."</pre>";
	  $reporte = new GetReports();
      for($i=0;$i<sizeof($datos1);$i++)
      {
        $rcaja=$datos1[$i][factura_fiscal];
        $empresa=$datos1[$i][empresa_id];
        $centro=$datos1[$i][centro_utilidad];
        $fech=$datos1[$i][fecha_registro];
        $Te=FormatoValor($datos1[$i][total_efectivo]);
        $Tc=FormatoValor($datos1[$i][total_cheques]);
        $Tt=FormatoValor($datos1[$i][total_tarjetas]);
        $Tb=FormatoValor($datos1[$i][total_bonos]);

        $TOTAL=FormatoValor($datos1[$i][total_abono]);
        if( $i % 2){ $estilo='modulo_list_claro';}
        else {$estilo='modulo_list_oscuro';}
        $html.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
        $html.="  <td>".$datos1[$i][prefijo]." - ".$rcaja."</td>";
        $html.="  <td>$fech</td>";
        $html.="  <td>$Te</td>";
        $html.="  <td>$Tc</td>";
        $html.="  <td>$Tt</td>";
        $html.="  <td>$Tb</td>";
        $html.="  <td class=\"label_error\">$TOTAL</td>";
       
        
        
		$html.= $reporte->GetJavaReport('app','Facturacion_Fiscal','FacturaRC',
		array('cuenta'=>$datos1[$i][numerodecuenta],'prefijo'=>$datos1[$i][prefijo],'factura_fiscal'=>$datos1[$i][factura_fiscal]),array('rpt_dir'=>'cache','rpt_name'=>'recibo'.$datos1[$i][prefijo].$datos1[$i][factura_fiscal],'rpt_rewrite'=>FALSE));
		$funcion=$reporte->GetJavaFunction();
		$html.="  <td><a href=\"javascript:$funcion\">IMPRIMIR</a></td>";
		$html.="</tr>";
      }
        $html .= "</table>\n";
        $html.="</tr>";
        
        $html .= "</table>\n";
        //$html .= "</table>\n";
        return $html;
		}
  }
?>