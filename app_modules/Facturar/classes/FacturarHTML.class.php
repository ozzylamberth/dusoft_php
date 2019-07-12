<?php
	/**************************************************************************************
	* $Id: FacturarHTML.class.php,v 1.2 2010/11/25 18:24:34 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 	
	*
	* Manejar la forma HTML del proceso de facturaci� e Impresi�
	***************************************************************************************/
  IncludeClass('Facturar','','app','Facturar');
	class FacturarHTML
	{
		function FacturarHTML(){}
		/**********************************************************************************
		*@acess private 
		***********************************************************************************/
	function FormaFacturarImpresion($EmpresaId,$mensaje,$Cuenta,$prefijoPac,$facturaPac,$prefijoCli,$facturaCli,$PlanId,&$FormaMensaje,$sw_tipo_plan,$Ingreso)
  {
        IncludeLib('funciones_facturacion');
        global $VISTA;
        //factura detalleda
        
        $mostrar ="\n<script>\n";
        
        $RUTA = $_ROOT ."cache/factura".$Cuenta.".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        //factura conceptos
        $RUTA = $_ROOT ."cache/facturaconceptos".$Cuenta.".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana2(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        
        $RUTA = $_ROOT ."cache/facturapaciente".$Cuenta."".$prefijoPac."".$facturaPac.".pdf";        
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana3(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        //factura conceptos
        $RUTA = $_ROOT ."cache/facturaconceptos".$Cuenta."".$prefijoPac."".$facturaPac.".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana4(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n"; 
        //cuenta de cobro       
        $RUTA1 = $_ROOT ."cache/cuentacobro".$Cuenta.".pdf";
        $mostrar.="  function abreVentanaCC(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA1';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $mostrar.="</script>\n";
        $html.="$mostrar";
						
				$html .= ThemeAbrirTabla('IMPRESIONES FACTURACION CUENTA No. '.$Cuenta);
				$html .= "            <table width=\"30%\" align=\"center\" border=\"0\">";
        $html .= "               <tr><td class=\"label_mark\" align=\"center\" colspan=\"3\">$mensaje</td></tr>";
        $html .= "               <tr><td align=\"center\" colspan=\"3\">&nbsp;</td></tr>";
//IMPRESION HC / EPICRISIS
				//Impresion Epicrisis por Ingreso.
				if($Ingreso)
				{
					$reporte = new GetReports();
					$mostrarEPI2 = $reporte->GetJavaReport_Epicrisis($Ingreso,array());
					$funcionEPI2 = $reporte->GetJavaFunction();
					$html .=$mostrarEPI2;
					//NUENA EPICRISIS
					$html .="<tr>";
					$fact = new Facturar();
					$epi = $fact->GetDatosEpicrisis($Ingreso);
					if($epi)
					{
						$mostrarT=$reporte->GetJavaReport('hc','Epicrisis','ReporteEpicrisis',array('ingreso'=>$Ingreso,'evolucion'=>$evolucion_id,array('rpt_name'=>'Epicrisis'.$Ingreso,'rpt_dir'=>'cache','rpt_rewrite'=>TRUE)));
						$funcionT=$reporte->GetJavaFunction();
						$html .= "<td align=\"right\" width=\"30%\"><a href=\"javascript:$funcionT\"><img src=\"". GetThemePath() ."/images/pplan.png\" border='0' title=\"NUEVA EPICRISIS\"></a></td>\n";
						$html .= "$mostrarT";
					}
					else
					$html .= "<td align=\"center\" width=\"10%\">&nbsp;</td>\n";
		
					//FIN NUENA EPICRISIS
					$html .= "<td width=\"30%\" align=\"center\"><a href=\"javascript:$funcionEPI2\"><img src=\"". GetThemePath() ."/images/imprimir.png\" border='0' title='RESUMEN EPICRISIS'></a>";
					$mostrar3=$reporte->GetJavaReport_HC($Ingreso,array());
					$funcion2=$reporte->GetJavaFunction();
					$html .=$mostrar3;
					$html .= "</td>";
					$html .="<td width=\"30%\" align=\"left\"><a href=\"javascript:$funcion2\"><img src=\"". GetThemePath() ."/images/historial.png\" border='0' title='HISTORIA CLINICA'></a>";
					$html .= "</td>";
					$html .= "</tr>";
          //NOTA OPERATORIA
          $notas_OP = $this->LlamaGet_Info_NotasOperatorias($Ingreso);
          if(is_array($notas_OP))
          {
                $arr = $this->LlamaGetDatosIngreso($Ingreso);
                for($f=0;$f<sizeof($notas_OP);$f++)
                {
                    $html .= "<tr align=\"center\" >";
                //imprimir nota operatoria
                    $mostrar = $reporte->GetJavaReport('app','BioEstadistica','reporteNotaOperatoria_html',array('programacion'=>$notas_OP[$f][programacion_id],'tipoidpaciente'=>$arr[tipo_id_paciente],'paciente'=>$arr[paciente_id],"ingreso"=>$Ingreso),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                    $nombre_funcionOP = $reporte->GetJavaFunction();
                    $html .=$mostrar;
                                                        
                    $html .= "<td colspan='3' class='modulo_list_claro' width=\"25%\" align=\"center\">";
                    $html .= "<a href=\"javascript:$nombre_funcionOP\">Evolución: ".$notas_OP[$f][evolucion_id]."&nbsp&nbsp&nbsp;<img src=\"".GetThemePath()."/images/traslado.png\" border='0' title=\"Nota Operatoria\"></a></td></tr>";
                }
          }          
          //FIN NOTA OPERATORIA
				}
//FIN IMPRESION HC / EPICRISIS
				$html .= " <tr>";
				//IncludeLib("reportes/hojacargos");
				//GenerarHojaCargos(array('numerodecuenta'=>$Cuenta));
				//$html .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA CARGOS\" onclick=\"javascript:abreVentanaHC()\"></td>";
				//$html .= "               </tr>";
				//$html .= "               <tr>";				
				//IncludeLib("reportes/hojacargos2");
				//GenerarHojaCargos2(array('numerodecuenta'=>$Cuenta));
				//$html .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA CARGOS2\" onclick=\"javascript:abreVentanaHT()\"></td>";

//SELECT PARA LAS HOJAS CARGOS
				$acchoja=ModuloGetURL('app','Facturar','user','LlamarVentanaFinal',array('EmpresaId'=>$EmpresaId,'numerodecuenta'=>$Cuenta,'plan_id'=>$PlanId,'tipoid'=>$TipoId,'pacienteid'=>$PacienteId,'prefijoPac'=>$prefijoPac,'facturaPac'=>$facturaPac,'prefijoCli'=>$prefijoCli,'facturaCli'=>$facturaCli,'PlanId'=>$PlanId,'tiporeporte'=>'reportes','Mensaje'=>$mensaje));
        $html .= "             <form name=\"reportes\" action=\"$acchoja\" method=\"post\">";
        //$html .= "               <td><label class='label_mark'>Tipo Hoja Cargos: </label><select name=\"reporteshojacargos\" class=\"select\">";
        $html .= "               <td class=\"label_mark\" colspan=\"3\" align=\"center\"><select name=\"reporteshojacargos\" class=\"select\">";
				$fact = new Facturar();
        $reportes=$fact->TraerReportesHojaCargos($EmpresaId);
        for($i=0; $i<sizeof($reportes); $i++)
        {
            $html .=" <option value=\"".$reportes[$i][ruta_reporte].",".$reportes[$i][titulo]."\">".$reportes[$i][titulo]."</option>";
        }
        $html .= "              </select>";
        $html.= "              <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VER\"><br></td></form>";
        //$html.= "              <a href=\"$acchoja\">VER</a><br></td></form>";
//FIN SELECT PARA LAS HOJAS CARGOS
				$html .= " </tr>";
												
				if(!empty($facturaPac))
				{
						$fact = new Facturar();
						$var = $fact->DatosFactura($EmpresaId,$prefijoPac,$facturaPac,$Cuenta);
						//IncludeLib("reportes/factura");
						$ruta=EncontrarFormatoFactura($EmpresaId,$PlanId,'factura','paciente');
						IncludeLib($ruta);
						GenerarFacturaPaciente($var,$swTipoFactura=1);
						$html .= " <tr>";						
						//$html .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA PACIENTE\" onclick=\"javascript:abreVentana3()\"></td>";
						$html .= "      <td class='label_mark'><a href=\"javascript:abreVentana3()\">FACTURA PACIENTE</a></td>";
						$html .= " </tr>";		
						
						//IncludeLib("reportes/facturaconceptos");
						$ruta=EncontrarFormatoFactura($EmpresaId,$PlanId,'conceptos');
						IncludeLib($ruta);
						GenerarFacturaConceptos($var,$swTipoFactura=1);
						$html .= "               <tr>";								
						//$html .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CONCEPTOS PACIENTE\" onclick=\"javascript:abreVentana4()\"></td>";
						$html .= "      <td class='label_mark'><a href=\"javascript:abreVentana4()\">FACTURA CONCEPTOS PACIENTE</a></td>";
						$html .= "               </tr>";											
				}
				if(!empty($facturaCli))
				{
						//IncludeLib("reportes/factura");
						$ruta_reporte_cliente=EncontrarFormatoFactura($EmpresaId,$PlanId,'factura','cliente');
						IncludeLib($ruta_reporte_cliente);
						$fact = new Facturar();
						$var = $fact->DatosFactura($EmpresaId,$prefijoCli,$facturaCli,$Cuenta);
						GenerarFactura($var);
						$html .= "               <tr>";						
						//$html .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CLIENTE\" onclick=\"javascript:abreVentana()\"></td>";
						$html .= "      <td class='label_mark'><a href=\"javascript:abreVentana()\">FACTURA CLIENTE</a></td>";
						$html .= "               </tr>";
						
						//IncludeLib("reportes/facturaconceptos");
						$ruta=EncontrarFormatoFactura($EmpresaId,$PlanId,'conceptos');
						IncludeLib($ruta);
						GenerarFacturaConceptos($var);
							$html .= "               <tr>";								
							//$html .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CONCEPTOS CLIENTE\" onclick=\"javascript:abreVentana2()\"></td>";
						$html .= "      <td class='label_mark'><a href=\"javascript:abreVentana2()\">FACTURA CONCEPTOS CLIENTE</a></td>";
						$html .= "               </tr>";													
				}
				if($prefijoCli AND $facturaCli AND $sw_tipo_plan=='1')//PLAN SOAT - IMPRIMIR CUENTA COBRO
				{
						IncludeLib("reportes/cuentacobro");
						GenerarCuentaCobro(array('PlanId'=>$PlanId,'Fecha'=>$Fecha,
									'Ingreso'=>$Ingreso,'numero'=>$facturaCli,
									'prefijo'=>$prefijoCli,'empresa'=>$EmpresaId,
									'tipo_factura'=>$arreglo['tipo_factura'],'cuenta'=>$Cuenta));
						$html .= "               <tr>";								
						$html .= "      <td class='label_mark'><a href=\"javascript:abreVentanaCC()\">CUENTA DE COBRO</a></td>";
						$html .= "               </tr>";													
				}
				$accion=SessionGetVar('ActionVolver');
				$html .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$html .= "               <tr><td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
				$html .= "           </form>";
				$html .= "           </table>";
				$html .= ThemeCerrarTabla();
				return $html;
  }
  
    function LlamaGet_Info_NotasOperatorias($Ingreso)
    {
      $Facturar = new Facturar();
      $NOperatoria = $Facturar->Get_Info_NotasOperatorias($Ingreso);
      return $NOperatoria;
    
    }
    
    function LlamaGetDatosIngreso($Ingreso)
    {
      $Facturar = new Facturar();
      $datosingreso = $Facturar->GetDatosIngreso($Ingreso);
      return $datosingreso;
    
    }
	}
?>