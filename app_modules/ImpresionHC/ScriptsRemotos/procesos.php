<?php	/*	* incluimos rs_server.class.php que contiene la class rs_server que ser� la que "extenderemos"	*/			$VISTA = "HTML";     $_ROOT = "../../../";	include  "../../../classes/rs_server/rs_server.class.php";     include  "../../../includes/enviroment.inc.php";        class procesos_admin extends rs_server {        /*    * Definimos tantos m�todos como funciones queremos que nuestro servidor "sirva"    */            /*     * EstablecerFactorConversion     *     * Funcion que permite parametrizar el factor de conversion de un medicamento     */     function PintarHTML_Impresion( $parametros )     {          if($parametros[0] == 'hosp')          {               $vector1 = SessionGetVar("VectorHosp");          }          else          {          	$vector1 = SessionGetVar("VectorAmb");          }                              $ruta =  SessionGetVar("rutaimages");                  $reporte = new GetReports();                    $html .= "		<table align=\"center\" border=\"0\" class=\"hc_table_submodulo_list_title\" width=\"100%\">";          $html .= "            <tr class=\"modulo_table_list_title\">\n";          $html .= "                 <td colspan=\"3\">MEDICAMENTOS DE USO CONTROLADO</td>\n";          $html .= "            </tr>\n";                              foreach($vector1 as $k => $vectorM)          {               for($i=0;$i<sizeof($vectorM);$i++)               {                    if($vectorM[$i]['sw_uso_controlado'] == '1')                    {                         $html.="		<tr class=\"modulo_list_claro\">";                         $html.="			<td colspan=\"3\" align=\"left\"><B>".$vectorM[$i]['producto']."</B> - ( ".$vectorM[$i]['codigo_producto']." - ";                         if(empty($vectorM[$i]['codigo_pos']))                         {                              $html.="".$vectorM[$i]['item']." )";                         }else{                              $html.="".$vectorM[$i]['codigo_pos']." )";                         }                         if($vectorM[$i]['sw_uso_controlado'] == 1)                         {                              $html.="&nbsp;&nbsp;&nbsp;<img src=\"".$ruta."/images/endturn.png\" border='0' width=\"15\" title=\"Medicamento de Uso Controlado\">";                         }                         $html.="				</td>";                         $html.="		</tr>";                         $html.="		<tr class=\"modulo_list_claro\">";                         if ($parametros[0] == 'amb')                         {                              $modulo = 'Central_de_Autorizaciones';                              $nombre_reporte = 'formula_medica_html';                         }                         elseif ($parametros[0] == 'hosp')                         {                              $modulo = 'ImpresionHC';                              $nombre_reporte = 'formula_medica_hosp_html';                         }                                                  //reporte pos                         $accion1=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>SessionGetVar('ingreso'), 'evolucion_id'=>SessionGetVar('evolucion'), 'rango'=>'uso_controlado', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'soluciones'=>'1', 'modulo_invoca'=>'impresionhc', 'parametro_retorno'=>'1','impresion_pos'=>'1'));					$accion1 = str_replace("app_modules/ImpresionHC/ScriptsRemotos/", "", $accion1);                         $html.="  			<td align=\"center\" width=\"33%\"><a href='$accion1'><img src=\"".$ruta."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";                         //reporte pdf y html                         //$mostrar=$reporte->GetJavaReport('app',$modulo,$nombre_reporte,array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>SessionGetVar('ingreso'), 'evolucion_id'=>SessionGetVar('evolucion'), 'rango'=>'uso_controlado', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'modulo_invoca'=>'impresionhc'),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));                         //$nombre_funcion=$reporte->GetJavaFunction();                         //$html .=$mostrar;                         //$html.="				<td align=\"center\" width=\"33%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".$ruta."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";                         $html.="				<td align=\"center\" width=\"33%\">&nbsp;</td>";                                        //reporte media carta                         $accion2=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>SessionGetVar('ingreso'), 'evolucion_id'=>SessionGetVar('evolucion'), 'rango'=>'uso_controlado', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'soluciones'=>'1', 'modulo_invoca'=>'impresionhc', 'parametro_retorno'=>'1'));                         $accion2 = str_replace("app_modules/ImpresionHC/ScriptsRemotos/", "", $accion2);                         $html.="  			<td align=\"center\" width=\"33%\"><a href='$accion2'><img src=\"".$ruta."/images/imprimir.png\" border='0'>IMPRIMIR MEDIA CARTA</a></td>";                         $html.="		</tr>";                    }               }          }          $html.="		</table><br><br>";                    $html.="		<table align=\"center\" border=\"0\" class=\"hc_table_submodulo_list_title\" width=\"100%\">";          $html .= "            <tr class=\"modulo_table_list_title\">\n";          $html .= "                 <td colspan=\"3\">MEDICAMENTOS NO POS</td>\n";          $html .= "            </tr>\n";          foreach($vector1 as $k => $vectorM)          {                         for($i=0;$i<sizeof($vectorM);$i++)               {                    if($vectorM[$i]['item'] == 'NO POS')                    {                         $html.="		<tr class=\"modulo_list_oscuro\">";                         $html.="			<td colspan=\"3\" align=\"left\"><B>".$vectorM[$i]['producto']."</B> - ( ".$vectorM[$i]['codigo_producto']." - ";                         if(empty($vectorM[$i]['codigo_pos']))                         {                              $html.="".$vectorM[$i]['item']." )";                         }else{                              $html.="".$vectorM[$i]['codigo_pos']." )";                         }                         if($vectorM[$i]['sw_uso_controlado'] == 1)                         {                              $html.="&nbsp;&nbsp;&nbsp;<img src=\"".$ruta."/images/endturn.png\" border='0' width=\"15\" title=\"Medicamento de Uso Controlado\">";                         }                         $html.="				</td>";                         $html.="		</tr>";                         $html.="			<tr class=\"modulo_list_oscuro\">";                         if ($parametros[0] == 'amb')                         {                              $modulo = 'Central_de_Autorizaciones';                              $nombre_reporte = 'formula_medica_html';                         }                         elseif ($parametros[0] == 'hosp')                         {                              $modulo = 'ImpresionHC';                              $nombre_reporte = 'formula_medica_hosp_html';                         }                                                  //reporte pos                         $accion1=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>SessionGetVar('ingreso'), 'evolucion_id'=>SessionGetVar('evolucion'), 'rango'=>'no_pos', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'soluciones'=>'1', 'modulo_invoca'=>'impresionhc', 'parametro_retorno'=>'1','impresion_pos'=>'1'));                         $accion1 = str_replace("app_modules/ImpresionHC/ScriptsRemotos/", "", $accion1);                         $html.="  			<td align=\"center\" width=\"33%\"><a href='$accion1'><img src=\"".$ruta."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";                                        //reporte pdf y html                         //$mostrar=$reporte->GetJavaReport('app',$modulo,$nombre_reporte,array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>SessionGetVar('evolucion'), 'evolucion_id'=>SessionGetVar('evolucion'), 'rango'=>'no_pos', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'modulo_invoca'=>'impresionhc'),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));                         //$nombre_funcion=$reporte->GetJavaFunction();                         //$html .=$mostrar;                         //$html.="				<td align=\"center\" width=\"33%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".$ruta."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";                         $html.="				<td align=\"center\" width=\"33%\">&nbsp;</td>";                                        //reporte media carta                         $accion2=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>SessionGetVar('ingreso'), 'evolucion_id'=>SessionGetVar('evolucion'), 'rango'=>'no_pos', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'soluciones'=>'1', 'modulo_invoca'=>'impresionhc', 'parametro_retorno'=>'1'));                         $accion2 = str_replace("app_modules/ImpresionHC/ScriptsRemotos/", "", $accion2);                         $html.="  				<td align=\"center\" width=\"33%\"><a href='$accion2'><img src=\"".$ruta."/images/imprimir.png\" border='0'>IMPRIMIR MEDIA CARTA</a></td>";                         $html.="			</tr>";                    }               }          }          $html.="		</table>";          return $html;     }   }//end of class    /*        cuando creamos el objeto que tiene los procesos debemos indicar como �nico par�metro un        array con todas las funciones posibles ... esto se hace para evitar que se pueda llamar        a cualquier m�todo del objeto.    */    $oRS = new procesos_admin( array( 'PintarHTML_Impresion' ));    // el metodo action es el que recoge los datos (POST) y actua en consideraci�n ;-)    $oRS->action();?>