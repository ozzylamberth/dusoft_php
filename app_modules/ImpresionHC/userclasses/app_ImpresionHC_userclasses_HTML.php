<?php

/**
 * $Id: app_ImpresionHC_userclasses_HTML.php,v 1.35 2009/08/11 21:28:23 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_ImpresionHC_userclasses_HTML extends app_ImpresionHC_user
{

     function app_ImpresionHC_user_HTML()
     {
          $this->app_ImpresionHC_user(); //Constructor del padre 'modulo'
          $this->salida='';
          return true;
     }

     function EncabezadoPac($ingreso,$evolucion,$reporte)
     {
          if(empty($_SESSION['IMPRESIONHC']['PACIENTE']))
          {
               if(!empty($ingreso))
               { $this->DatosPacienteIngreso($ingreso); }
               else
               {  $this->DatosPacienteEvolucion($evolucion); }
          }
          
          ModuloSetVar('app','ImpresionHC','ActivarDepurador',false);
          $this->salida .= "<br><table  class=\"modulo_table_list_title\" border=\"0\"  width=\"80%\" align=\"center\" >";
          $this->salida .= " <tr class=\"modulo_table_list_title\">";
          $this->salida .= " <td  width=\"18%\">IDENTIFICACION</td>";
          $this->salida .= " <td>PACIENTE..</td>";
          $this->salida .= " </tr>";
          $this->salida .= " <tr align=\"center\">";
          $this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente']."&nbsp;".$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id']."</td>";
          $this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['IMPRESIONHC']['PACIENTE']['nombre_paciente']."</td>";
          $this->salida .= " </tr>";

          if(!empty($ingreso))
          { $mostrar=$reporte->GetJavaReport_HC($ingreso,array()); }
          else
          { $mostrar=$reporte->GetJavaReport_HistoriaClinica($evolucion,array()); }
          $funcion=$reporte->GetJavaFunction();
          $this->salida .=$mostrar;

          $this->salida .= " <tr align=\"center\">";
          $this->salida .= " <td class=\"modulo_list_claro\" colspan=\"2\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR HISTORIA CLINICA</a></td>";
          $this->salida .= " </tr>";

          $this->salida .= " </table>";
          return true;
     }
    /**
    *
    */
    function FormaImpresionSolicitudes($datos='',$control='')
    {
      // Funciones para el manejo de capas.
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS("RemoteScripting");
     	$this->IncludeJS("ScriptsRemotos/misfunciones.js",'app','ImpresionHC');

                    
      IncludeLib("funciones_central_impresion");
      if(!empty($datos))
      {
           if($control==1)
           {
                $RUTA =  $_ROOT ."cache/incapacidad_medica".UserGetUID().".pdf";
           }
           else if($control==2)
           {
                $RUTA = $_ROOT  ."cache/solicitudes".UserGetUID().".pdf";
           }
           else if($control==3)
           {
                $RUTA = $_ROOT ."cache/ordenservicio".$datos['orden'].".pdf";
           }
           else
           {
                $RUTA = $_ROOT ."cache/formula_medica_hos".UserGetUID().".pdf";
           }
           $DIR="printer.php?ruta=$RUTA";
           $RUTA1= GetBaseURL() . $DIR;
           $mostrar ="\n<script language='javascript'>\n";
           $mostrar.="var rem=\"\";\n";
           $mostrar.="  function abreVentana(){\n";
           $mostrar.="    var nombre=\"\"\n";
           $mostrar.="    var url2=\"\"\n";
           $mostrar.="    var str=\"\"\n";
           $mostrar.="    var width=\"400\"\n";
           $mostrar.="    var height=\"300\"\n";
           $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
           $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
           $mostrar.="    var nombre=\"Printer_Mananger\";\n";
           $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
           $mostrar.="    var url2 ='$RUTA1';\n";
           $mostrar.="    rem = window.open(url2, nombre, str)};\n";
           $mostrar.="</script>\n";
           $this->salida.="$mostrar";
           $this->salida.="<BODY onload=abreVentana();>";
      }

      $this->salida .= ThemeAbrirTabla('IMPRESION SOLICITUDES MEDICAS');
      $reporte = new GetReports();
      $this->EncabezadoPac($_SESSION['IMPRESIONHC']['INGRESO'],$_SESSION['IMPRESIONHC']['EVOLUCION'],&$reporte);

//Formulacion de Medicamentos.
      $vectorOriginal = array();
      unset($_SESSION['MEDICAMENTOSAMB']);
      $_SESSION['MEDICAMENTOSAMB'] = array();
      
      //tipo 1 de formulacion
      $tipo_formulacion = 'amb';
      if(!empty($_SESSION['IMPRESIONHC']['EVOLUCION']))
      {  
  $vector1 = GetMedicamentosAmb($_SESSION['IMPRESIONHC']['EVOLUCION']);
      }

      // Variable de impresion de medicamentos especiales.
      unset($_SESSION['MED_NOPOS']);
      unset($_SESSION['MED_CONTROL']);
      
      if($vector1)
      {
           array_push($vectorOriginal, $vector1);
           array_push($_SESSION['MEDICAMENTOSAMB'], $vector1);
           $this->FrmMedicamentos($vectorOriginal, &$reporte, $_SESSION['IMPRESIONHC']['INGRESO'], $_SESSION['IMPRESIONHC']['EVOLUCION'], $tipo_formulacion);
      }

      unset($_SESSION['MEDICAMENTOSHOSP']);
      $vectorOriginal = "";
      
$vectorOriginal = array();
      $_SESSION['MEDICAMENTOSHOSP'] = array();
      
      // Variable de impresion de medicamentos especiales.
      unset($_SESSION['MED_NOPOS']);
      unset($_SESSION['MED_CONTROL']);
      
      //tipo 2 de formulacion
      $tipo_formulacion = 'hosp';
      if(!empty($_SESSION['IMPRESIONHC']['EVOLUCION']))
      {  
  $vector2 = GetMedicamentos($_SESSION['IMPRESIONHC']['EVOLUCION']);
      }
      else
      {  
  $vector2 = GetMedicamentosIngreso($_SESSION['IMPRESIONHC']['INGRESO']);
      }
      if($vector2)
      {
           array_push($vectorOriginal, $vector2);
           array_push($_SESSION['MEDICAMENTOSHOSP'], $vector2);
      }
      
      // Vector de consulta de soluciones
      if(!empty($_SESSION['IMPRESIONHC']['EVOLUCION']))
      {  
  $vector3 = GetSoluciones($_SESSION['IMPRESIONHC']['EVOLUCION']);
      }
      if($vector3)
      {
           array_push($vectorOriginal, $vector3);
           array_push($_SESSION['MEDICAMENTOSHOSP'], $vector3);
      }
      
      if($vectorOriginal)
      {
           $this->FrmMedicamentos($vectorOriginal, &$reporte, $_SESSION['IMPRESIONHC']['INGRESO'], $_SESSION['IMPRESIONHC']['EVOLUCION'], $tipo_formulacion);          
      }
      //fin claudia.
      
      $notas_OP = $this->Get_Info_NotasOperatorias($_SESSION['IMPRESIONHC']['INGRESO']);
      if(is_array($notas_OP))
      {
           $this->FrmNotasOperatorias($notas_OP, $_SESSION['IMPRESIONHC']['INGRESO'], $_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], $_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'], &$reporte);
      }
      
      $dtSession = SessionGetVar('IMPRESIONHC');
      
      if($dtSession['EVOLUCION'])
      {
        $lectura = $this->ObtenerLecturaApoyos($dtSession['EVOLUCION'],$dtSession['PACIENTE']);
        if(sizeof($lectura) <= 0 || !$lectura)
          $lectura = $this->ConsultaResultadosNoSolicitados($dtSession['PACIENTE']);
        
        if(sizeof($lectura) > 0)  
          $this->FormaLecturaApoyos($dtSession['EVOLUCION'],$dtSession['PACIENTE'],$lectura,$reporte);
      }
      
      if(!empty($_SESSION['IMPRESIONHC']['EVOLUCION']))
      {  $arr = BuscarSolicitudesEvolucion($_SESSION['IMPRESIONHC']['EVOLUCION']);  }
      else
      {  $arr = BuscarSolicitudesHospitalariasAmbulatorias($_SESSION['IMPRESIONHC']['INGRESO']);  }
      //{  $arr=BuscarSolicitudesIngreso($_SESSION['IMPRESIONHC']['INGRESO']);  }
      if(!empty($arr))
      {
           $this->FormaSolicitudes($arr,&$reporte);
      }
      $var='';

      if(!empty($_SESSION['IMPRESIONHC']['EVOLUCION']))
      {  $var=BuscarOrdenesSEvolucion($_SESSION['IMPRESIONHC']['EVOLUCION']);  }
      else
      {  $var=BuscarOrdenesIngreso($_SESSION['IMPRESIONHC']['INGRESO']);  }
      if(!empty($var))
      {
           $this->FormaOrdenes($var,&$reporte);
      }

      if(!empty($_SESSION['IMPRESIONHC']['EVOLUCION']))
      {  $vec=Consulta_Incapacidades_GeneradasEvolucion($_SESSION['IMPRESIONHC']['EVOLUCION']);  }
      else
      {  $vec=Consulta_Incapacidades_GeneradasIngreso($_SESSION['IMPRESIONHC']['INGRESO']);  }
      if(!empty($vec))
      {
           $this->FrmIncapacidad($vec,&$reporte);
      }
      unset($reporte);
      if(!$vector1 AND !$arr AND !$var AND !$vec AND !$vector2 AND !$notas_OP)
      {
           $this->salida.="<br><br><table align=\"center\" width='80%' border=\"0\">";
           $this->salida.="  <TR><td align=\"center\" width=\"9%\"><label class='label_mark'>EL PACIENTE NO TIENE NINGUNA SOLICITUD</label></td><TR>";
           $this->salida.="</table>";
      }
      $this->salida .= "</form>";

      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"40%\">";
      $this->salida .= "				       <tr>";
      $this->salida .= "            <form name=\"formabuscar\" action=\"".$_SESSION['IMPRESIONHC']['ACCION']."\" method=\"post\">";
      $this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></td>";
      $this->salida .= "              </tr>";
      $this->salida .= "			     </form>";
      $this->salida .= "			     </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }	

	function GetJavaFunctionModulo($tipo,$modulo,$nombrereporte,$datos,$opciones)
	{
		static $reporte;
		if(!is_object($reporte)){
			$reporte = new GetReports();
		}
		$this->salida .= $reporte->GetJavaReport($tipo,$modulo,$nombrereporte,$datos,$opciones);
		return $reporte->GetJavaFunction();
	}

     function SetStyle($campo)
	{
		if ($this->frmError[$campo] || $campo=="MensajeError"){
			if ($campo=="MensajeError"){
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}


	function FormaOrdenes($var,$reporte)
	{
          IncludeLib('funciones_central_impresion');
          IncludeLib("funciones_admision");
          $this->salida .= "    <br><table width=\"80%\" border=\"0\" align=\"center\" >";
          $this->salida .= "            <tr class=\"modulo_table_title\">";
          $this->salida .= "                <td colspan=\"5\" align=\"CENTER\">ORDENES</td>";
          $this->salida .= "            </tr>";
          $this->salida .= "             </table>";
          for($i=0; $i<sizeof($var);)
          {
               $d=$i;
               $this->salida .= "    <table width=\"80%\" border=\"1\" align=\"center\" >";
               $this->salida .= "            <tr class=\"modulo_table_title\">";
               $this->salida .= "                <td colspan=\"5\" align=\"left\">NUMERO DE ORDEN ".$var[$i][orden_servicio_id]."</td>";
               $this->salida .= "            </tr>";
               $this->salida .= "            <tr>";
               $this->salida .= "                <td colspan=\"5\" class=\"modulo_list_claro\">";
               $this->salida .= "                        <table width=\"100%\" border=\"1\" align=\"center\" class=\"\">";
               $this->salida .= "                                <tr>";
               $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">TIPO AFILIADO: </td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][tipo_afiliado_nombre]."</td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">RANGO: </td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][rango]."</td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">SEMANAS COT.: </td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][semanas_cotizadas]."</td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">SERVICIO: </td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][desserv]."</td>";
               $this->salida .= "                                </tr>";
               $this->salida .= "                                <tr>";
               $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">AUT. INT.: </td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_int]."</td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">AUT. EXT: </td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_ext]."</td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">AUTORIZADOR: </td>";
               $dat=BuscarAutorizador($var[$d][autorizacion_int],$var[$d][autorizacion_ext]);
               $this->salida .= "                                        <td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">".$dat."</td>";
               $this->salida .= "                                </tr>";
               $this->salida .= "                                <tr>";
               $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">PLAN: </td>";
               $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\" colspan=\"7\" align=\"left\">".$var[$d][plan_descripcion]."</td>";
               $this->salida .= "                                </tr>";
               $this->salida .= "                                <tr>";
               $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">OBSERVACIONES: </td>";
               $this->salida .= "                                        <td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\" align=\"left\">".$var[$d][observacion]."</td>";
               $this->salida .= "                                </tr>";
               $this->salida .= "                         </table>";
               $this->salida .= "                </td>";
               $this->salida .= "            </tr>";
               while($var[$i][orden_servicio_id]==$var[$d][orden_servicio_id])
               {
                    $this->salida .= "            <tr>";
                    $this->salida .= "                <td colspan=\"5\">";
                    $this->salida .= "                <table width=\"99%\" border=\"0\" align=\"center\">";
                    $this->salida .= "            <tr class=\"modulo_table_title\">";
                    $this->salida .= "                <td width=\"6%\">ITEM</td>";
                    $this->salida .= "                <td width=\"6%\">CANT.</td>";
                    $this->salida .= "                <td width=\"10%\">CARGO</td>";
                    $this->salida .= "                <td width=\"45%\">DESCRICPION</td>";
                    $this->salida .= "                <td width=\"20%\">PROVEEDOR</td>";
                    $this->salida .= "            </tr>";
                    if($d % 2) {  $estilo="modulo_list_claro";  }
                    else {  $estilo="modulo_list_oscuro";   }
                    $this->salida .= "            <tr class=\"$estilo\">";
                    $this->salida .= "                <td align=\"center\">".$var[$d][numero_orden_id]."</td>";
                    $this->salida .= "                <td align=\"center\">".$var[$d][cantidad]."</td>";
                    if(!empty($var[$d][cargo])){  $cargo=$var[$d][cargo];  }
                    else {  $cargo=$var[$d][cargoext];   }
                    $this->salida .= "                <td align=\"center\">".$cargo."</td>";
                    $this->salida .= "                <td>".$var[$d][descripcion]."</td>";
                    $p='';
                    if(!empty($var[$d][departamento]))
                    {  $p='DPTO. '.$var[$d][desdpto];  $id=$var[$d][departamento]; $tipo='i'; }
                    else
                    {  $p=$var[$d][planpro];  $id=$var[$d][plan_proveedor_id]; $tipo='e'; }
                    $this->salida .= "                <td align=\"center\">".$p."</td>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
                    $this->salida .= "                <td colspan=\"5\">";
                    $this->salida .= "                        <table width=\"100%\" border=\"0\" align=\"center\">";
                    $this->salida .= "                                <tr class=\"modulo_list_claro\">";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">ACTIVACION: </td>";
                    $this->salida .= "                                        <td width=\"5%\" colspan=\"2\">".FechaStamp($var[$d][fecha_activacion])."</td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">VENC.: </td>";
                    $x='';
                    if(date("Y-m-d") > $var[$d][fecha_vencimiento]) $x='VENCIDA';
                    $this->salida .= "                                        <td width=\"5%\" >".FechaStamp($var[$d][fecha_vencimiento])."</td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"label_error\" align=\"center\">".$x."</td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">REFRENDAR HASTA: </td>";
                    $this->salida .= "                                        <td width=\"5%\">".FechaStamp($var[$d][fecha_refrendar])."</td>";
                    $this->salida .= "                                </tr>";
                    $this->salida .= "                         </table>";
                    $this->salida .= "                </td>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "             </table>";
                    $this->salida .= "                </td>";
                    $this->salida .= "            </tr>";
                    $d++;
               }
               $this->salida .= "            <tr class=\"$estilo\">";
               $accion=ModuloGetURL('app','ImpresionHC','user','ReporteOrdenServicio',array('orden'=>$var[$i][orden_servicio_id],'evolucion'=>$var[$i][evolucion_id],'plan'=>$var[$i][plan_id],'tipoid'=>$var[$i][tipo_id_paciente],'paciente'=>$var[$i][paciente_id],'afiliado'=>$var[$i][tipo_afiliado_id],'pos'=>1));
               $this->salida .= "                <td align=\"center\" ><a href=\"$accion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
               $mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','ordenservicioHTM',array('orden'=>$var[$i][orden_servicio_id],'TipoDocumento'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'],'Nombres'=>$_SESSION['IMPRESIONHC']['PACIENTE']['nombre'],'evolucion'=>$_SESSION['IMPRESIONHC']['EVOLUCION'],'ingreso'=>$_SESSION['IMPRESIONHC']['INGRESO']),array('rpt_name'=>'ordenservicioHTM','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
               $funcion=$reporte->GetJavaFunction();
               $this->salida .=$mostrar;
               $this->salida.="  				 <td align=\"center\" width=\"43%\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";

               $accion=ModuloGetURL('app','ImpresionHC','user','ReporteOrdenServicio',array('orden'=>$var[$i][orden_servicio_id],'evolucion'=>$var[$i][evolucion_id],'plan'=>$var[$i][plan_id],'tipoid'=>$var[$i][tipo_id_paciente],'paciente'=>$var[$i][paciente_id],'afiliado'=>$var[$i][tipo_afiliado_id],'pos'=>0));
               $this->salida .= "                <td class=$estilo align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$accion\"> IMPRIMIR MEDIA CARTA</a></td></tr>";

               $this->salida .= "            </tr>";
               $i=$d;
               $this->salida .= "             </table>";
          }//fin for
	}


	/**
	*
	*/
	function FormaSolicitudes($arr,$reporte)
	{
          unset($_SESSION['IMPRESIONHC']['ARR_SOLICITUDES']);
          IncludeLib("malla_validadora");
          IncludeLib("funciones_admision");
          $this->salida .= "         <br><table width=\"80%\" border=\"0\" align=\"center\">";
          $this->salida .= "            <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">SOLICITUDES</td></tr>";
          for($i=0; $i<sizeof($arr);)
          {
               $d=$i;
               if($arr[$i][plan_id]==$arr[$d][plan_id] AND $arr[$i][servicio]==$arr[$d][servicio])
               {
                    $this->salida .= "            <tr><td colspan=\"5\" class=\"modulo_table_title\">PLAN:".$arr[$i][plan_descripcion]."</td></tr>";
                    $this->salida .= "            <tr>";
                    $this->salida .= "                <td class=\"modulo_table_title\" width=\"12%\">SERVICIO: </td>";
                    $this->salida .= "                <td class=\"modulo_list_claro\" width=\"13%\">".$arr[$i][desserv]."</td>";
                    $this->salida .= "                <td class=\"modulo_table_title\" width=\"11%\">DEPARTAMENTO: </td>";
                    $this->salida .= "                <td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">".$arr[$i][despto]."</td>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "            <tr class=\"modulo_table_title\">";
                    $this->salida .= "                <td>FECHA</td>";
                    $this->salida .= "                <td>CARGO</td>";
                    $this->salida .= "                <td colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
                    $this->salida .= "                <td width=\"10%\">TIPO</td>";
                    //$this->salida .= "                <td width=\"11%\">JUSTIF.</td>";
                    $this->salida .= "            </tr>";
               }
               while($arr[$i][plan_id]==$arr[$d][plan_id] AND $arr[$i][servicio]==$arr[$d][servicio])
               {
                    if($d % 2) {  $estilo="modulo_list_claro";  }
                    else {  $estilo="modulo_list_oscuro";   }
                    $this->salida .= "            <tr class=\"$estilo\">";
                    $this->salida .= "                <td>".FechaStamp($arr[$i][fecha])." ".HoraStamp($arr[$i][fecha])."</td>";
                    $this->salida .= "                <td align=\"center\">".$arr[$d][cargos]."</td>";
                    $this->salida .= "                <td colspan=\"2\">".$arr[$d][descar]."</td>";
                    $this->salida .= "                <td align=\"center\">".$arr[$d][desos]."</td>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "            <tr class=\"$estilo\">";
                    $this->salida .= "                <td width=\"11%\" class=\"modulo_table_title\" >JUSTIFICACION:</td>";
                    $x=MallaValidadoraValidarCargo($arr[$d][cargos],$arr[$d][plan_id],$arr[$d][servicio],$arr[$d][hc_os_solicitud_id],$arr[$d][cantidad]);
                    if(is_array($x))
                    {  $this->salida .= "                <td align=\"center\" colspan=\"4\">CARGO VALIDADO POR LA MALLA</td>";  }
                    else
                    {  $this->salida .= "                <td align=\"center\" colspan=\"4\">$x</td>";  }
                    $this->salida .= "            </tr>";
                    if($arr[$d][sw_ambulatorio]==1)
                    {
                         $this->salida .= "            <tr class=\"$estilo\">";
                         $this->salida .= "                <td align=\"center\" colspan=\"5\" class=\"label\">SOLICITUD AMBULATORIA</td>";
                         $this->salida .= "            </tr>";
                    }
                    $d++;
               }
               $i=$d;
          }
          //Variable de session que contiene el arreglo de las solicitudes para cuando se vayan a imprimir
          $_SESSION['IMPRESIONHC']['ARR_SOLICITUDES']=$arr;
          $go_to_url=ModuloGetURL('app','ImpresionHC','user','Reportesolicitudes',array('pos'=>1));
          $this->salida .= "                <tr><td class=$estilo colspan=\"2\" align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$go_to_url\"> IMPRIMIR POS</a></td>";

          $go_to_url=ModuloGetURL('app','ImpresionHC','user','Reportesolicitudes',array('pos'=>0));
          $this->salida .= "                <td class=$estilo colspan=\"2\" align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$go_to_url\"> IMPRIMIR MEDIA CARTA</a></td>";

          $go_to_url=ModuloGetURL('app','ImpresionHC','user','Reportesolicitudes',array('pos'=>0));
          $mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','solicitudesHTM',array('TipoDocumento'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'],'Nombres'=>$_SESSION['IMPRESIONHC']['PACIENTE']['nombre'],'evolucion'=>$_SESSION['IMPRESIONHC']['EVOLUCION'],'ingreso'=>$_SESSION['IMPRESIONHC']['INGRESO']),array('rpt_name'=>'solicitudesHTM','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $funcion=$reporte->GetJavaFunction();
          $this->salida .=$mostrar;
          $this->salida .= "                <td class=$estilo align=\"center\" width=\"15%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"javascript:$funcion\"> IMPRIMIR</a></td></tr>";


          $this->salida .= " </table>";
	}

	//***********************FUNCIONES DE CLAUDIA
	function FrmIncapacidad($vector1,$rep)
	{
          IncludeLib('funciones_admision');
          if($vector1)
          {
               $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"5\">INCAPACIDADES MEDICAS GENERADAS</td>";
     
               for($i=0;$i<sizeof($vector1);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"5%\">No. EVOLUCION</td>";
                    $this->salida.="  <td width=\"45%\">OBSERVACION DE LA INCAPACIDAD</td>";
                    $this->salida.="  <td width=\"10%\">TIPO DE INCAPACIDAD</td>";
                    $this->salida.="  <td width=\"10%\">DIAS DE INCAPACIDAD</td>";
                    $this->salida.="  <td width=\"10%\">FECHA DE EMISION</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"center\" width=\"5%\">".$vector1[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"45%\">".$vector1[$i][observacion_incapacidad]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][tipo_incapacidad_descripcion]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][dias_de_incapacidad]."</td>";
                    $fecha = FechaStamp($vector1[$i][fecha_inicio]);

                    $this->salida.="<td align=\"center\" width=\"10%\">".$fecha."</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"100%\" colspan=\"5\"><b>ORDENÓ:  ".$vector1[$i][nombre]." - ".$vector1[$i][especialidad]."</b></td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"$estilo\">";
     
               //reporte pos
                    $accion1=ModuloGetURL('app','Central_de_Autorizaciones','user','ReporteIncapacidadMedica',array('evolucion'=>$vector1[$i][evolucion_id], 'modulo_invoca'=>'impresionhc', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));
                    $this->salida.="  <td colspan = 2 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
     
               //reporte html
                    $mostrar=$rep->GetJavaReport('app','CentralImpresionHospitalizacion','incapacidad_html',array('evolucion_id'=>$vector1[$i][evolucion_id]),array('rpt_name'=>'incapacidad_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $nombre_funcion=$rep->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida.="<td colspan = 2 align=\"center\" width=\"23%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
     
               //reporte pdf en media carta
                    $accion1=ModuloGetURL('app','Central_de_Autorizaciones','user','ReporteIncapacidadMedica' ,array('evolucion'=>$vector1[$i][evolucion_id], 'modulo_invoca'=>'impresionhc', 'parametro_retorno'=>'1'));
                    $this->salida.="  <td colspan = 1 align=\"center\" width=\"20%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="</table><br>";
          }
          $this->salida .= "</form>";
          return true;
     }


     function FrmMedicamentos($vector1, $reporte, $ingreso, $evolucion, $tipo_formulacion)
     {
          $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

          if ($tipo_formulacion == "amb")
          { $tipo_medicamentos = "MEDICAMENTOS AMBULATORIOS"; }
          else
          { $tipo_medicamentos = "MEDICAMENTOS Y/O SOLUCIONES HOSPITALARIAS"; }
          
          $this->salida.="<table align=\"center\" border=\"0\" class=\"hc_table_submodulo_list_title\" width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="  <td>".$tipo_medicamentos."</td>";
          $this->salida.="</tr>";
          
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="  <td width=\"40%\">";
          
          //Variables que me indican si en la formulacion hubo med. No Pos o Controlados.
          $_SESSION['MED_NOPOS'] = 0;
          $_SESSION['MED_CONTROL'] = 0;
          
          
          
          $this->salida.="    <table  align=\"center\" border=\"0\"  width=\"100%\">";
          
          $this->salida.= $this->Pintar_FormulacionConsultada($vector1, $tipo_formulacion, &$reporte);
          
          $this->salida.="    </table>";
         
          $this->salida.="  </td>";
          $this->salida.="</tr>";
          
          $this->salida.="</table>";
          
          // Enlaces de Impresion de medicamentos en Papel.
          $this->salida.="<table align=\"center\" border=\"0\" class=\"hc_table_submodulo_list_title\" width=\"80%\">";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          
          if ($tipo_formulacion == 'amb')
          {
               $modulo = 'Central_de_Autorizaciones';
               $nombre_reporte = 'formula_medica_html';
          }
          elseif ($tipo_formulacion == 'hosp')
          {
               $modulo = 'ImpresionHC';
               $nombre_reporte = 'formula_medica_hosp_html';
          }
          
          //reporte pos
     	$accion1=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'modulo_invoca'=>'impresionhc', 'parametro_retorno'=>'1','impresion_pos'=>'1'));
          $this->salida.="  <td align=\"center\" width=\"33%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

          //reporte pdf y html
          $mostrar=$reporte->GetJavaReport('app',$modulo,$nombre_reporte,array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'modulo_invoca'=>'impresionhc'),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $nombre_funcion=$reporte->GetJavaFunction();
          $this->salida .=$mostrar;
          $this->salida.="<td align=\"center\" width=\"33%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";

          //reporte media carta
          $accion2=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'modulo_invoca'=>'impresionhc', 'parametro_retorno'=>'1'));
          $this->salida.="  <td align=\"center\" width=\"33%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR MEDIA CARTA</a></td>";

          $this->salida.="</tr>";
          
          if($_SESSION['MED_NOPOS'] == 1 OR $_SESSION['MED_CONTROL'] == 1)
          {
          	$this->salida.="<tr class=\"modulo_list_oscuro\">";
               $accionJava = "javascript:MostrarCapa('MedicamentosEspeciales');IniciarCapaMed('IMPRESION DE MEDICAMENTOS ESPECIALES','MedicamentosEspeciales','".$tipo_formulacion."');CargarContenedor('MedicamentosEspeciales');";
			$this->salida.="<td align=\"center\" colspan=\"3\"><a href=\"$accionJava\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR MEDICAMENTOS ESPECIALES</a></td>";          
               $this->salida.="</tr>";
		}
                    
          $this->salida.="</table>";    

          //Vector de medicamentos.
          if($tipo_formulacion == 'hosp')
          {
          	SessionSetVar("VectorHosp",$_SESSION['MEDICAMENTOSHOSP']);
          }else
          {
          	SessionSetVar("VectorAmb",$_SESSION['MEDICAMENTOSAMB']);
          }
          //Ruta de imagenes.
		SessionSetVar("rutaimages",GetThemePath());
          //Ingreso y evolucion.
          SessionSetVar("ingreso",$ingreso);
          SessionSetVar("evolucion",$evolucion);
          
          //Capa de Impresion               
          $this->salida.="<div id='MedicamentosEspeciales' class='d2Container' style=\"display:none\"><br>";
          $this->salida .= "    <div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('MedicamentosEspeciales');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoMedicamentosEspeciales' class='d2Content' style=\"height:250\">\n";
          $this->salida .= "    </div>\n";     
          $this->salida .= "</div>";

          
          $javaC = "<script>\n";
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          
          $javaC .= "   function IniciarCapaMed(tit, Elemento, tipoF)\n";
          $javaC .= "   {\n";
          $javaC .= "	   CargarFormaContenido(tipoF);\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "	   xResizeTo(Capa, 500, 'auto');\n";
          $javaC .= "       document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('error').innerHTML = '';\n";
          $javaC .= "       Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/4, xScrollTop()+24);\n";
          $javaC .= "       ele = xGetElementById('titulo');\n";
          $javaC .= "       xResizeTo(ele, 480, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC .= "       ele = xGetElementById('cerrar');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 480, 0);\n";
          $javaC .= "   }\n";         

          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "function MostrarCapa(Elemento)\n";
          $javaC.= "{\n;";
          $javaC.= "    capita = xGetElementById(Elemento);\n";
          $javaC.= "    capita.style.display = \"\";\n";
          $javaC.= "}\n";
          
          $javaC.= "function Cerrar(Elemento)\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(Elemento);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";          
          $javaC.= "}\n";                    
          
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
          
          return true;
     }// Fin FrmMedicamentos
     
     
     /*
     * Forma que permite dibujar la consulta de los medicamentos.
     *
     * @autor Tizziano Perea
     */
	function Pintar_FormulacionConsultada($vectorOriginal, $tipo_formulacion, $reporte)
     {
          foreach($vectorOriginal as $k => $vector1)
          {
               for($i=0;$i<sizeof($vector1);$i++)
               {
               	// Activo variable de uso controlado.
                    if($vector1[$i]['sw_uso_controlado'] == '1')
                    { $_SESSION['MED_CONTROL'] = 1; }
                    
                    // Activo variable de Med. No Pos.
                    if($vector1[$i]['item'] == 'NO POS' OR $vector1[$i]['codigo_pos'] == 'NO POS')
                    { $_SESSION['MED_NOPOS'] = 1; }
                    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    { $estilo = 'modulo_list_oscuro'; }else
                    { $estilo = 'modulo_list_claro'; }
                    
                    $salida.="<tr>";
                    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    { 
                    	$salida.="<td width=\"40%\" colspan=\"3\" class=\"modulo_list_claro\"><B>".$vector1[$i]['producto']."</B> - ( ".$vector1[$i]['codigo_producto']." - ";
                         if(empty($vector1[$i]['codigo_pos']))
                         {
                         	$salida.="".$vector1[$i]['item']." )";
                         }else{
                         	$salida.="".$vector1[$i]['codigo_pos']." )";
                         }
                         if($vector1[$i]['sw_uso_controlado'] == 1)
                         {
                         	$salida.="&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/endturn.png\" border='0' width=\"15\" title=\"Medicamento de Uso Controlado\">";
                         }
                         if($vector1[$i]['justificacion_no_pos_id'])
                         {
                              //Imprimir Justificación No Pos
                              $mostrar = $reporte->GetJavaReport('app','ImpresionHC','JustificacionMED_NO_POS_html',array('codigo_producto'=>$vector1[$i]['codigo_producto'],'justificacion_id'=>$vector1[$i]['justificacion_no_pos_id']),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                              $nombre_funcionOP = $reporte->GetJavaFunction();
                              $this->salida .=$mostrar;
                              $salida.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label align=\"right\"><a href=\"javascript:$nombre_funcionOP\"><img src=\"".GetThemePath()."/images/historial.png\" border='0' width=\"15\" title=\"Imprimir Justificación\"></label></a>";
                         }
                         $salida.="</td>";
                    }
                    else
                    {
                         if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                         {
                              $salida.="<td width=\"40%\" colspan=\"3\" class=\"modulo_list_oscuro\">";
                              for($j=0; $j<sizeof($vector1); $j++)
                              {
                                   if($vector1[$i]['num_mezcla'] == $vector1[$j]['num_mezcla'])
                                   {
                                        $salida.="<B>".$vector1[$j]['producto']."</B> - ( ".$vector1[$j]['codigo_producto']." - <label class=\"label_mark\">".$vector1[$j]['dosis']." ".$vector1[$j]['unidad_suministro']."</label>)<br>";
                                   }
                              }
                              $salida.="</td>";
                         }
                    }
    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    {
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td colspan=\"6\">";
                         $salida.="<table>";
                         
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td colspan = 3 align=\"left\" width=\"9%\"><b>Via de Administracion:</b> ".$vector1[$i][via]."</td>";
                         $salida.="</tr>";
     
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td align=\"left\" width=\"9%\"><b>Dosis:</b></td>";
                         $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                         if($e==1)
                         {
                              $salida.="  <td align=\"left\" width=\"10%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }
                         else
                         {
                              $salida.="  <td align=\"left\" width=\"10%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }
                         
                         if($tipo_formulacion == "amb")
                         {
						$vector_posologia = Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);             
                              
                              //pintar formula para opcion 1
                              if($vector1[$i][tipo_opcion_posologia_id]== 1)
                              {
                                   $salida.="  <td align=\"left\" width=\"20%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                              }
                              //pintar formula para opcion 2
                              if($vector1[$i][tipo_opcion_posologia_id]== 2)
                              {
                                   $salida.="  <td align=\"left\" width=\"20%\">".$vector_posologia[0][descripcion]."</td>";
                              }
                              //pintar formula para opcion 3
                              if($vector1[$i][tipo_opcion_posologia_id]== 3)
                              {
                                   $momento = '';
                                   if($vector_posologia[0][sw_estado_momento]== '1')
                                   {
                                        $momento = 'antes de ';
                                   }
                                   else
                                   {
                                        if($vector_posologia[0][sw_estado_momento]== '2')
                                        {
                                             $momento = 'durante ';
                                        }
                                        else
                                        {
                                             if($vector_posologia[0][sw_estado_momento]== '3')
                                             {
                                                       $momento = 'despues de ';
                                             }
                                        }
                                   }
                                   $Cen = $Alm = $Des= '';
                                   $cont= 0;
                                   $conector = '  ';
                                   $conector1 = '  ';
                                   if($vector_posologia[0][sw_estado_desayuno]== '1')
                                   {
                                        $Des = $momento.'el Desayuno';
                                        $cont++;
                                   }
                                   if($vector_posologia[0][sw_estado_almuerzo]== '1')
                                   {
                                        $Alm = $momento.'el Almuerzo';
                                        $cont++;
                                   }
                                   if($vector_posologia[0][sw_estado_cena]== '1')
                                   {
                                        $Cen = $momento.'la Cena';
                                        $cont++;
                                   }
                                   if ($cont== 2)
                                   {
                                        $conector = ' y ';
                                        $conector1 = '  ';
                                   }
                                   if ($cont== 1)
                                   {
                                        $conector = '  ';
                                        $conector1 = '  ';
                                   }
                                   if ($cont== 3)
                                   {
                                        $conector = ' , ';
                                        $conector1 = ' y ';
                                   }
                                   $salida.="  <td align=\"left\" width=\"20%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                              }
                              //pintar formula para opcion 4
                              if($vector1[$i][tipo_opcion_posologia_id]== 4)
                              {
                                   $conector = '  ';
                                   $frecuencia='';
                                   $j=0;
                                   foreach ($vector_posologia as $k => $v)
                                   {
                                        if ($j+1 ==sizeof($vector_posologia))
                                        {
                                             $conector = '  ';
                                        }
                                        else
                                        {
                                             if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                             else
                                             {
                                                  $conector = ' - ';
                                             }
                                        }
                                        $frecuencia = $frecuencia.$k.$conector;
                                        $j++;
                                   }
                                   $salida.="  <td align=\"left\" width=\"20%\">a la(s): $frecuencia</td>";
                              }
                              //pintar formula para opcion 5
                              if($vector1[$i][tipo_opcion_posologia_id]== 5)
                              {
                                   $salida.="  <td align=\"left\" width=\"20%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                              }

                         }else
                         {
                         	$salida.="<td align=\"left\" width=\"20%\">".$vector1[$i][frecuencia]."</td>";                         
                         }

                         $salida.="</tr>";
          
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="  <td align=\"left\" width=\"9%\"><b>Cantidad:</b></td>";
                         $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                         if($vector1[$i][contenido_unidad_venta])
                         {
                              if($e==1)
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                              else
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                         }
                         else
                         {
                              if($e==1)
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]."</td>";
                              }
                              else
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]."</td>";
                              }
                         }
                         $salida.="  <td colspan=\"2\" align=\"left\">DIAS TRATAMIENTO: ".$vector1[$i][dias_tratamiento]."</td>";
                         $salida.="</tr>";
                         if($vector1[$i][observacion] != "")
                         {
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="  <td align=\"left\" width=\"9%\"><b>Observación:</b></td>";
                              $salida.="  <td align=\"left\" colspan=\"2\">".$vector1[$i][observacion]."</td>";
                              $salida.="</tr>";
                         }
          
                         $Profesional = $this->ProfesionalFormulacion_Medicamento($vector1[$i][usuario_id]);
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td align=\"left\" width=\"9%\"><b>Formuló:</b></td>";
                         $salida.="<td align=\"left\" colspan=\"2\">".$Profesional."</td>";
                         $salida.="</tr>";
                         $salida.="</table>";
                         $salida.="</td>";
                         $salida.="</tr>";
     
                    }else
                    {
                         if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                         {
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="<td colspan=\"6\">";
                              $salida.="<table>";

                              $salida.="<tr class=\"$estilo\">";
                              $salida.="  <td align=\"left\" width=\"42%\"><b>Cantidad Total:</b></td>";
                              $salida.="  <td align=\"left\" colspan=\"2\">".floor($vector1[$i][cantidad])." SOLUCION(ES)</td>";
                              $salida.="</tr>";
                              
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="  <td align=\"left\" width=\"42%\"><b>Volumen de Infusión:</b></td>";
                              $salida.="  <td align=\"left\" colspan=\"2\">".floor($vector1[$i][volumen_infusion])." ".strtoupper($vector1[$i][unidad_volumen])."</td>";
                              $salida.="</tr>";
                         
                              if($vector1[$i][observacion] != "")
                              {
                                   $salida.="<tr class=\"$estilo\">";
                                   $salida.="  <td align=\"left\" width=\"9%\"><b>Observación:</b></td>";
                                   $salida.="  <td align=\"left\" colspan=\"2\">".$vector1[$i][observacion]."</td>";
                                   $salida.="</tr>";
                              }
               
                              $Profesional = $this->ProfesionalFormulacion_Medicamento($vector1[$i][usuario_id]);
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="<td align=\"left\" width=\"9%\"><b>Formuló:</b></td>";
                              $salida.="<td align=\"left\" colspan=\"2\">".$Profesional."</td>";
                              $salida.="</tr>";
                              $salida.="</table>";
		                    $salida.="</td>";
                              $salida.="</tr>";
                         }
                    }
               } //fin del for muy importante
          }
     	return $salida;
     }
     
    function FrmNotasOperatorias($notas_OP, $ingreso, $paciente_id, $tipo_id_paciente, $rep)
    {
      $this->salida .= "<br>";
      $this->salida .= "<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_list_claro\">";
      $this->salida .= "<tr align=\"center\" class=\"modulo_table_title\">";
      $this->salida .= "<td colspan=\"2\">NOTAS OPERATORIAS</td>";
      $this->salida .= "</tr>";
      for($f=0;$f<sizeof($notas_OP);$f++)
      {
        if( $i % 2) $estilo='modulo_list_claro';
        else $estilo='modulo_list_oscuro';

        $this->salida .= "<tr align=\"center\">";
        $this->salida .= "<td class=\"modulo_list_claro\"><b>NOTA OPERATORIA ".$notas_OP[$f]['hc_nota_operatoria_cirugia_id']."</b></td>";

        //imprimir nota operatoria
        $mostrar=$rep->GetJavaReport('app','ImpresionHC','reporteNotaOperatoria_html',array('hc_nota_operatoria_cirugia_id'=>$notas_OP[$f]['hc_nota_operatoria_cirugia_id'],'programacion'=>$notas_OP[$f][programacion_id],'tipoidpaciente'=>$tipo_id_paciente,'paciente'=>$paciente_id,"ingreso"=>$ingreso),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
        $nombre_funcionOP=$rep->GetJavaFunction();
        $this->salida .=$mostrar;
                                          
        $this->salida .= "<td class='$estilo' align=\"left\">";
        $this->salida .= "<a href=\"javascript:$nombre_funcionOP\"><b>EVOLUCION: ".$notas_OP[$f][evolucion_id]."&nbsp&nbsp&nbsp<img src=\"".GetThemePath()."/images/traslado.png\" border='0'></b></a></td></tr>";
      }
      $this->salida .= "</table>";
      return true;
    }     
    /**
    * Funcion donde se crea un link para el reporte de los resultados de los examenes
    *
    * @param integer $evolucion Identificador de la evolucion
    * @param array $paciente Arreglo de datos del paciente
    * @param array $$lectura 
    * @param object $rep Objeto del reporte
    *
    * @return boolean
    */
    function FormaLecturaApoyos($evolucion,$paciente,$lectura,&$rep)
    {
      ksort($lectura);
      $this->salida .= "  <br>\n";
      $this->salida .= "  <table align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"80%\" class=\"modulo_list_claro\">\n";
      $this->salida .= "    <tr align=\"center\" class=\"formulacion_table_list\">\n";
      $this->salida .= "      <td>IMPRIMIR RESULTADOS DE EXAMENES<td>\n";

      foreach($lectura as $key => $dtl)
      {
        $paciente['evolucion_solicitud'] = $key;
      
        $mostrar = $rep->GetJavaReport('app','ImpresionHC','examenesresultados',$paciente,array('rpt_name'=>'examenesresultado','rpt_dir'=>'','rpt_rewrite'=>TRUE));
        $funcion = $rep->GetJavaFunction();
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_title\">\n";
        $this->salida .= "      <td class=\"modulo_list_claro\">\n";
        $this->salida .= $mostrar;
        $this->salida .= "        <a href=\"javascript:".$funcion."\" class =\"label_error\">\n";
        $this->salida .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>EVOLUCION: ".$key."\n";
        $this->salida .= "        </a>\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
      }
      $this->salida .= "  </table>\n";
      return true;
    }
  }
?>