<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: Formulacion_Externa_Facturacion_HTML.class.php
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: Parametrizar_Medico_ESM_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class ESM_CortesDiarios_HTML
	{
		/**
		* Constructor de la clase
		*/
		function ESM_CortesDiarios_HTML(){}
		
    
    function Vista_Formulario($action,$buscador,$DATOS,$DATOS_TRASLADOS,$DATOS_DESPACHOS,$DATOS_DISPENSADOS,$DATOS_PENDIENTES_DISPENSADOS,$plan)
    {
    $html .= " <script>";
    $html .= "  function Validar(Formulario)";
    $html .= "  {";
   /* $html .= "    if(Formulario.fecha_inicio.value>Formulario.fecha_final.value)";
    $html .= "    {";
    $html .= "     alert(\"Error: La Fecha Inicio No Puede Ser Mayor a La Final!!\");";
    $html .= "    return false;";
    $html .= "    }";*/
   
            $html .= "	    f = Formulario.fecha_inicio.value.split('/')\n";
			$html .= "	    f1 = new Date(f[2]+'/'+ f[1]+'/'+ f[0]); \n";
			$html .= "	    f = Formulario.fecha_final.value.split('/')\n";
			$html .= "	    f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
			$html .= "	    if(f1 > f2 )\n";
			$html .= "	    {\n";
		    $html .= "         alert(\"Error: La Fecha Inicio No Puede Ser Mayor a La Final!!\");";
			$html .= "      } \n";
   
   
    $html .= " document.factura.submit();";
    $html .= " }";
    $html .= " </script>";
        
    $ctl = AutoCarga::factory("ClaseUtil");
      
 			$html .= $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptDate('/');
			
			$today=date('d/m/Y');
		
      $html .= ThemeAbrirTabla('GENERAR CORTES');
      $html .= "<form name=\"factura\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">FILTRO PARA LA GENERACION DE CORTES</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$today."\">\n";
      $html .= "              </td>\n";
		$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
		$html .= "            </tr>\n";

		$html .= "            <tr>\n";
		$html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
		$html .= "              <td>\n";
		$html .= "                <input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$today."\">\n";
		$html .= "              </td>\n";
		$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
		$html .= "            </tr>\n";
		$html .= "			      <tr>\n";

		$html .= "			      <tr>\n";
		$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
		$html .= "					      <input type=\"hidden\" name=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\">\n";
		$html .= "					      <input type=\"hidden\" name=\"datos[ssiid]\" value=\"".$_REQUEST['datos']['ssiid']."\">\n";
		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\" onclick=\"Validar(document.factura)\">\n";
		$html .= "					      <input type=\"reset\" class=\"input-submit\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.factura);\">\n";
		$html .= "				      </td>\n";
		$html .= "			      </tr>\n";
		$html .= "		      </table>\n";
		$html .= "	      </fieldset>\n";
		$html .= "	    </td>\n";
		$html .= "	  </tr>\n";
		$html .= "	</table>\n";
		$html .= "</form>\n";
    
    
    if(empty($DATOS))
    $disabled=" disabled ";
      else
             $disabled = "";
             
    if(empty($DATOS_TRASLADOS) && empty($DATOS_DESPACHOS) && empty($DATOS_DISPENSADOS) && empty($DATOS_PENDIENTES_DISPENSADOS))
    $disabled=' disabled ';
    else
    $disabled='';
    
    $html .= "  <table border=\"0\" width=\"30%\" align=\"center\">";
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
    $html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </form>";
    $html .= "  </td>";
    $html .= "  <td align=\"center\"><br>";
		$html .= ' 	<form name="forma" action="'.$action['confirmar'].'" method="post">';
    $html .= '  <input '.$disabled.' class="input-submit" type="submit" value="CONFIRMAR: CORTE">';
		$html .= "  </form>";
    $html .= "  </td>";
		$html .= "  </tr>";
		$html .= "  </table>";
    
      if(!empty($DATOS))
      {
        $rpt  = new GetReports();
        $html .= $rpt->GetJavaReport('app','ReportesInventariosGral','reporte_general_auditoria',$request,
                                  array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();
 
        
        $html .= "                  <center>";
       /* $html .= "	                <fieldset class=\"fieldset\" style=\"width:60%\">\n";
        $html .= "                  <legend  class=\"label_error\">AGRUPACION POR DISTRIBUCION - TRASLADOS A BODEGAS SATELITES </legend>\n";
        $html .= "		                <table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "                      <tr class=\"formulacion_table_list\" >";
		 $html .= "                        <td width=\"15%\">";
        $html .= "                        DOCUMENTO";
        $html .= "                        </td>";
        $html .= "                        <td width=\"40%\">";
        $html .= "                        DESCRIPCION DEL DOCUMENTO";
        $html .= "                        </td>";
        $html .= "                        <td width=\"15%\">";
        $html .= "                        EMPRESA";
        $html .= "                        </td>";
		
		
        $html .= "                      </tr>";
      			$est = "modulo_list_claro";
            $bck = "#DDDDDD";
            $suma=0;
            foreach($DATOS_TRASLADOS as $k1 => $dtl)
            {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
           
			$html .= "		              <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
			$html .= "                     <td>";
			$html .= "                    ".$dtl['prefijo']." ".$dtl['numero'];
			$html .= "                    </td>";
			
			$html .= "                    <td>";
			$html .= "                    ".$dtl['documento_descripcion'];
			$html .= "                    </td>";
			$html .= "                     <td>";
			$html .= "                    ".$dtl['razon_social'];
			$html .= "                    </td>";
		
			$html .= "		              </tr>\n";
            }
      			
            $html .= "	                  </table>\n";
            $html .= "	                </fieldset>\n";
       */
         /*   $html .= "	                <fieldset class=\"fieldset\" style=\"width:60%\">\n";
            $html .= "                  <legend  class=\"label_error\">AGRUPACION POR DISTRIBUCION - DESPACHOS A ESM </legend>\n";
            $html .= "		                <table width=\"100%\" class=\"modulo_table_list\">\n";
            $html .= "                      <tr class=\"formulacion_table_list\" >";
			$html .= "                        <td width=\"15%\">";
        $html .= "                        DOCUMENTO";
        $html .= "                        </td>";
        $html .= "                        <td width=\"40%\">";
        $html .= "                        DESCRIPCION DEL DOCUMENTO";
        $html .= "                        </td>";
        $html .= "                        <td width=\"15%\">";
        $html .= "                        EMPRESA";
        $html .= "                        </td>";
            $html .= "                      </tr>";
      			$est = "modulo_list_claro";
            $bck = "#DDDDDD";
            $suma=0;
            foreach($DATOS_DESPACHOS as $k1 => $dtl)
            {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
            
			$html .= "		              <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
			$html .= "                     <td>";
			$html .= "                    ".$dtl['prefijo']." ".$dtl['numero'];
			$html .= "                    </td>";
			
			$html .= "                    <td>";
			$html .= "                    ".$dtl['documento_descripcion'];
			$html .= "                    </td>";
			$html .= "                     <td>";
			$html .= "                    ".$dtl['razon_social'];
			$html .= "                    </td>";
            $html .= "		              </tr>\n";
            }
      		
            $html .= "	                  </table>\n";
            $html .= "	                </fieldset>\n";*/
    
        $html .= "	                <fieldset class=\"fieldset\" style=\"width:60%\">\n";
        $html .= "                  <legend  class=\"label_error\">AGRUPACION POR PRODUCTOS DISPENSADOS </legend>\n";
        $html .= "		                <table width=\"100%\" class=\"modulo_table_list\">\n";
      
		$html .= "                      <tr class=\"formulacion_table_list\" >";
			$html .= "                        <td width=\"15%\">";
        $html .= "                        DOCUMENTO";
        $html .= "                        </td>";
		$html .= "                        <td width=\"15%\">";
		$html .= "                        FORMULA";
		$html .= "                        </td>";
		 $html .= "                        <td width=\"15%\">";
        $html .= "                        EMPRESA";
        $html .= "                        </td>";
		
        $html .= "                        <td width=\"40%\">";
        $html .= "                        DESCRIPCION DEL DOCUMENTO";
        $html .= "                        </td>";
		
		$html .= "                        <td width=\"15%\">";
        $html .= "                        TOTAL";
        $html .= "                        </td>";
       
		$html .= "                      </tr>";
      			$est = "modulo_list_claro";
            $bck = "#DDDDDD";
            $suma=0;
            foreach($DATOS_DISPENSADOS as $k1 => $dtl)
            {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
 
           
			$html .= "		              <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
			$html .= "                     <td>";
			$html .= "                    ".$dtl['prefijo']." ".$dtl['numeracion'];
			$html .= "                    </td>";
			
			
			$html .= "                    <td>";
			$html .= "                    ".$dtl['formula_papel'];
			$html .= "                    </td>";
			
			$html .= "                     <td>";
			$html .= "                    ".$dtl['razon_social'];
			$html .= "                    </td>";
			
			$html .= "                    <td>";
			$html .= "                    ".$dtl['documento_descripcion'];
			$html .= "                    </td>";
			
			  $html .= "                    <td>";
            $html .= "                    $".FormatoValor($dtl['total_costo'],2);
      			$html .= "                    </td>";
			
		
			$html .= "		              </tr>\n";
			}
		
			$html .= "	                  </table>\n";
			$html .= "	                </fieldset>\n";
       
            $html .= "	                <fieldset class=\"fieldset\" style=\"width:60%\">\n";
            $html .= "                  <legend  class=\"label_error\">AGRUPACION POR PENDIENTES DISPENSADOS</legend>\n";
            $html .= "		                <table width=\"100%\" class=\"modulo_table_list\">\n";
            
             $html .= "                      <tr class=\"formulacion_table_list\" >";
			$html .= "                        <td width=\"15%\">";
			$html .= "                        DOCUMENTO";
			$html .= "                        </td>";
			$html .= "                        <td width=\"15%\">";
			$html .= "                        FORMULA";
			$html .= "                        </td>";
			$html .= "                        <td width=\"15%\">";
			$html .= "                        EMPRESA";
			$html .= "                        </td>";

			$html .= "                        <td width=\"40%\">";
			$html .= "                        DESCRIPCION DEL DOCUMENTO";
			$html .= "                        </td>";

			$html .= "                        <td width=\"15%\">";
			$html .= "                        TOTAL";
			$html .= "                        </td>";

		
            $html .= "                      </tr>";
      			$est = "modulo_list_claro";
            $bck = "#DDDDDD";
            $suma=0;
            foreach($DATOS_PENDIENTES_DISPENSADOS as $k1 => $dtl)
            {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
       
          
            $html .= "		              <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
      		$html .= "                     <td>";
			$html .= "                    ".$dtl['prefijo']." ".$dtl['numeracion'];
			$html .= "                    </td>";
			
				$html .= "                     <td>";
			$html .= "                    ".$dtl['formula_papel'];
			$html .= "                    </td>";
			
				$html .= "                     <td>";
			$html .= "                    ".$dtl['razon_social'];
			$html .= "                    </td>";
			$html .= "                    <td>";
			$html .= "                    ".$dtl['documento_descripcion'];
			$html .= "                    </td>";
		
			
			  $html .= "                    <td>";
            $html .= "                    $".FormatoValor($dtl['total_costo'],2);
      			$html .= "                    </td>";
			
            $html .= "		              </tr>\n";
            }
      			
            $html .= "	                  </table>\n";
            $html .= "	                </fieldset>\n";
            $html .= "                  </center>";
 
      }
      
      
      $html .= ThemeCerrarTabla();

    return $html;
    }
    
    
    function Vista_Facturas($accion,$DATOS)
    {
    $html .= " <script>";
    $html .= "  function Validar(Formulario)";
    $html .= "  {";
    $html .= "    if(Formulario.fecha_inicio.value>Formulario.fecha_final.value)";
    $html .= "    {";
    $html .= "     alert(\"Error: La Fecha Inicio No Puede Ser Mayor a La Final!!\");";
    $html .= "    return false;";
    $html .= "    }";
    
    $html .= " document.factura.submit();";
    $html .= " }";
    $html .= " </script>";
        
    $ctl = AutoCarga::factory("ClaseUtil");
      
 			$html .= $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptDate('/');
      $html .= ThemeAbrirTabla('GENERAR FACTURA');
      $html .= "<form name=\"factura\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">FILTRO PARA LA GENERACION DE FACTURAS</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['buscador']['fecha_inicio']."\">\n";
      $html .= "              </td>\n";
 			$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
      $html .= "            </tr>\n";
			
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['buscador']['fecha_final']."\">\n";
      $html .= "              </td>\n";
 			$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
      $html .= "            </tr>\n";
      
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FACTURA (Prefijo-Numero)</td>\n";
      $html .= "              <td colspan=\"2\">\n";
      $html .= "                <input type=\"text\" name=\"buscador[prefijo]\" id=\"factura\" class=\"input-text\" value=\"".$_REQUEST['buscador']['prefijo']."\">-<input size=\"3\" type=\"text\" name=\"buscador[numero]\" id=\"numero\" class=\"input-text\" value=\"".$_REQUEST['buscador']['numero']."\">\n";
      $html .= "              </td>\n";
 	$html .= "            </tr>\n";
	
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"hidden\" name=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\">\n";
			$html .= "					      <input type=\"hidden\" name=\"datos[ssiid]\" value=\"".$_REQUEST['datos']['ssiid']."\">\n";
			$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\" onclick=\"Validar(document.factura)\">\n";
   		$html .= "					      <input type=\"reset\" class=\"input-submit\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.factura);\">\n";
      $html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "		      </table>\n";
			$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      $html .= "  <br>";
    if(!empty($DATOS))
    {
        $html .= "                  <center>";
        $html .= "	                <fieldset class=\"fieldset\" style=\"width:60%\">\n";
        $html .= "                  <legend  class=\"label_error\">FACTURAS </legend>\n";
        $html .= "		                <table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "                      <tr class=\"formulacion_table_list\" >";
        $html .= "                        <td width=\"10%\">";
        $html .= "                        #FACTURA";
        $html .= "                        </td>";
        $html .= "                        <td width=\"25%\">";
        $html .= "                        TERCERO";
        $html .= "                        </td>";
        $html .= "                        <td width=\"15%\">";
        $html .= "                        TOTAL";
        $html .= "                        </td>";
        $html .= "                        <td width=\"15%\">";
        $html .= "                        SALDO";
        $html .= "                        </td>";
        $html .= "                        <td width=\"3%\">";
        $html .= "                        OP";
        $html .= "                        </td>";
        $html .= "                      </tr>";
      			$est = "modulo_list_claro";
            $bck = "#DDDDDD";
            $suma=0;
            foreach($DATOS as $k1 => $dtl)
            {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
			$html .= "		              <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
			$html .= "                    <td>";
			$html .= "                    ".$dtl['prefijo']."-".$dtl['factura_fiscal'];
			$html .= "                    </td>";
			$html .= "                     <td>";
			$html .= "                    ".$dtl['tipo_id_tercero']."-".$dtl['tercero_id']." ".$dtl['nombre_tercero'];
			$html .= "                    </td>";
			$html .= "                    <td>";
			$html .= "                    $".FormatoValor($dtl['total_factura'],3);
			$html .= "                    </td>";
			$html .= "                    <td>";
			$html .= "                    $".FormatoValor($dtl['saldo'],3);
			$html .= "                    </td>";
			$html .= "                    <td align=\"center\">";
			$html .= "      				<a href=\"".$accion['crear_glosa']."&prefijo=".$dtl['prefijo']."&factura_fiscal=".$dtl['factura_fiscal']."\" >";
			$html .= "						 <img title=\"CREAR GLOSA\" src=\"".GetThemePath()."/images/pplan.png\" border=\"0\">";
			$html .= "						</a>";
			$html .= "                    </td>";
			$html .= "		              </tr>\n";
            }
          $html .= "               </table>\n";
          $html .= "	                </fieldset>\n";    
    }
    
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= ThemeCerrarTabla();
    return $html;
    }
    
     
    
    function Forma_NuevaGlosa($accion,$MotivoGlosa,$DATOS,$glosas_concepto_general)
    {
    $ctl = AutoCarga::factory("ClaseUtil");
 	$html .= $ctl->LimpiarCampos();
 	$html .= $ctl->RollOverFilas();
 	$html .= $ctl->AcceptDate('/');
      
	  $Select_MotivoGlosa  = "	<select class=\"select\" name=\"motivo_glosa_id\"name=\"motivo_glosa_id\" style=\"width:50%\">";
	  $Select_MotivoGlosa .= "	<option value=\"\">-- SELECCIONAR --</option>";
	  foreach($MotivoGlosa as $key => $valor)
	  $Select_MotivoGlosa .= "	<option value=\"".$valor['motivo_glosa_id']."\">".$valor['motivo_glosa_id']." - ".$valor['motivo_glosa_descripcion']."</option>";
	  $Select_MotivoGlosa .= "	</select>";
	  
	  $Select_GlosaConceptoGeneral  = "	<select class=\"select\" name=\"codigo_concepto_general\"name=\"codigo_concepto_general\" style=\"width:50%\" onchange=\"xajax_Listado_ConceptoEspecifico(this.value);\">";
	  $Select_GlosaConceptoGeneral .= "	<option value=\"\">-- SELECCIONAR --</option>";
	  foreach($glosas_concepto_general as $key => $valor)
	  $Select_GlosaConceptoGeneral .= "	<option value=\"".$valor['codigo_concepto_general']."\">".$valor['codigo_concepto_general']." - ".$valor['descripcion_concepto_general']."</option>";
	  $Select_GlosaConceptoGeneral .= "	</select>";
            
      $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
	  $html .= " <script>";
    $html .= "  function Validar(Formulario)";
    $html .= "  {";
    $html .= "    if(Formulario.fecha_glosa.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: La Fecha Glosa No Puede Estar Vacia!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.motivo_glosa_id.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Motivo Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.codigo_concepto_general.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Concepto General Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.codigo_concepto_especifico.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Concepto Especifico Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    
    $html .= " document.esm_glosa.submit();";
    $html .= " }";
    $html .= " </script>";
    $html .= $ctl->AcceptNum(false);
    $html .= ThemeAbrirTabla('CREAR NUEVA GLOSA',"70%");
    $html .= "  <center>";
    $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FACTURA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['prefijo']."-".$DATOS['factura_fiscal'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FECHA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['fecha_registro'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      USUARIO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['nombre'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TERCERO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['tipo_id_tercero']." ".$DATOS['tercero_id']."-".$DATOS['nombre_tercero'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      PLAN";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['plan_descripcion'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      SALDO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['saldo'],2);
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";
	$html .= "  <center>";
	$html .= "	<form name=\"esm_glosa\" action=\"".$accion['guardar']."\" id=\"esm_glosa\" method=\"POST\">";
    $html .= "  <table border=\"1\" width=\"60%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      FECHA GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <input type=\"text\" name=\"fecha_glosa\" id=\"fecha_glosa\" readonly=\"true\" class=\"input-text\"> ".ReturnOpenCalendario('esm_glosa','fecha_glosa','-',1)."";
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      MOTIVO GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$Select_MotivoGlosa;
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      CONCEPTO GENERAL";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$Select_GlosaConceptoGeneral;
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      CONCEPTO ESPECIFICO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <select name=\"codigo_concepto_especifico\" id=\"codigo_concepto_especifico\" style=\"width:50%\" class=\"select\">";
    $html .= "          <option value=\"\">-- SELECCIONAR --</option>";
    $html .= "          </select>";
    $html .= "      </td>";
    $html .= "    </tr>";
	
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      # DOCUMENTO CLIENTE";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <input type=\"text\" name=\"documento_interno_cliente_id\" id=\"documento_interno_cliente_id\" class=\"input-text\">";
    $html .= "      </td>";
    $html .= "    </tr>";
	
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      GLOSA TODO EL DOCUMENTO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <input type=\"checkbox\" name=\"sw_glosa_total_factura\" id=\"sw_glosa_total_factura\" value=\"1\" class=\"input-checbox\">";
    $html .= "      </td>";
    $html .= "    </tr>";
	
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      GLOSA POR MAYOR VALOR";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <input type=\"checkbox\" name=\"sw_mayor_valor\" id=\"sw_mayor_valor\" value=\"1\" class=\"input-checbox\">";
    $html .= "      </td>";
    $html .= "    </tr>";
    
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "      OBSERVACION";
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "      <textarea class=\"textarea\" style=\"width:100%\" name=\"observacion\" id=\"observacion\"></textarea>";
    $html .= "      </td>";
    $html .= "    </tr>";
    
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "			<input type=\"hidden\" name=\"prefijo\" id=\"prefijo\" value=\"".$_REQUEST['prefijo']."\">";
    $html .= "			<input type=\"hidden\" name=\"factura_fiscal\" id=\"factura_fiscal\" value=\"".$_REQUEST['factura_fiscal']."\">";
    $html .= "			<input type=\"hidden\" name=\"datos[empresa_id]\" id=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\">";
    $html .= "			<input type=\"hidden\" name=\"datos[ssiid]\" id=\"datos[ssiid]\" value=\"".$_REQUEST['datos']['ssiid']."\">";
	$html .= "          <input type=\"button\" value=\"REGISTRAR GLOSA\" class=\"input-submit\" onclick=\"Validar(document.esm_glosa);\">";
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "  </table>\n";
    $html .= " </form>";
	$html .= "</center>";
    
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= "  <script>";
    $html .= "  xajax_Listado_Productos_TMP('".$_REQUEST['orden_requisicion_tmp_id']."');";
    $html .= "  </script>";
    $html .= ThemeCerrarTabla();
    return $html;
    }
    
	
	function Forma_Glosa($accion,$DATOS,$DATOS_DETALLE)
    {
    $ctl = AutoCarga::factory("ClaseUtil");
 	$html .= $ctl->LimpiarCampos();
 	$html .= $ctl->RollOverFilas();
 	$html .= $ctl->AcceptDate('/');
          
      $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
  $html .= " <script>";
  $html .= "  function Validar(Formulario)";
  $html .= "  {";
  $html .= "    if(Formulario.fecha_glosa.value==\"\")";
  $html .= "    {";
  $html .= "     alert(\"Error: La Fecha Glosa No Puede Estar Vacia!!\");";
  $html .= "    return false;";
  $html .= "    }";
  $html .= "    if(Formulario.motivo_glosa_id.value==\"\")";
  $html .= "    {";
  $html .= "     alert(\"Error: No hay Motivo Glosa Seleccionada!!\");";
  $html .= "    return false;";
  $html .= "    }";
  $html .= "    if(Formulario.codigo_concepto_general.value==\"\")";
  $html .= "    {";
  $html .= "     alert(\"Error: No hay Concepto General Glosa Seleccionada!!\");";
  $html .= "    return false;";
  $html .= "    }";
  $html .= "    if(Formulario.codigo_concepto_especifico.value==\"\")";
  $html .= "    {";
  $html .= "     alert(\"Error: No hay Concepto Especifico Glosa Seleccionada!!\");";
  $html .= "    return false;";
  $html .= "    }";
  $html .= " document.esm_glosa.submit();";
  $html .= " }";
  $html .= " </script>";
  
  $html .= "	<script>";
  $html .= "	function Asignar(campo,valor)";
  $html .= "	{"; 
  $html .= "  var total;";
  $html .= "  total = (document.getElementById('valor_aceptado').value-document.getElementById('valor_no_aceptado').value);";
  $html .= "  if(total<0)";
  $html .= "	valor=0;"; 
  $html .= "	document.getElementById(campo).value=valor;"; 
  $html .= "	}";  
  
  $html .= "	function CalculoNoAceptado(campo,valor_glosa,valor_aceptado)";
  $html .= "	{"; 
  $html .= "   var valor = (valor_glosa-valor_aceptado);";
  $html .= "   if(valor<0)";
  $html .= "      valor=0;";
  $html .= "	document.getElementById(campo).value=valor;"; 
  $html .= "	"; 
  $html .= "	}";   
  $html .= "	function Validar_AceptarGlosa(Formulario)";
  $html .= "	{"; 
  $html .= "   if(isNaN(Formulario.valor_aceptado))";
  $html .= "    {";
  $html .= "      alert('El Numero No Es Correcto!!');";
  $html .= "      return false;";
  $html .= "    }";
  $html .= "   if(isNaN(Formulario.valor_no_aceptado))";
  $html .= "    {";
  $html .= "      alert('El Numero No Es Correcto!!');";
  $html .= "      return false;";
  $html .= "    }";
  $html .= "   if(Formulario.valor_no_aceptado <=0 && Formulario.valor_aceptado <=0)";
  $html .= "    {";
  $html .= "      alert('Uno De Los Valores Debe Ser Mayor a Cero!!');";
  $html .= "      return false;";
  $html .= "    }";
  $html .= "   var entrar = confirm('Confirma Aceptar la Glosa?');";
  $html .= "    if (entrar) ";
  $html .= "    {";
  
  $html .= "      if(Formulario.sw_glosa_total_factura=='0')";
  $html .= "        xajax_AceptarGlosaDetalle(xajax.getFormValues('forma_glosa'));";
  $html .= "        else";
  $html .= "            xajax_AceptarGlosaTotal(xajax.getFormValues('forma_glosa'));";
  $html .= "    }";
  $html .= "        else";
  $html .= "              {";
  $html .= "              return(false);";
  $html .= "              }";
  $html .= "	"; 
  $html .= "	}";   
  
  $html .= "	function Validar_AnularGlosa(Formulario)";
  $html .= "	{"; 
  $html .= "   var entrar = confirm('Confirma Anular la Glosa?');";
  $html .= "    if (entrar) ";
  $html .= "    {";
  $html .= "        xajax_AnularGlosaDetalle(xajax.getFormValues('forma_glosa'));";
  $html .= "    }";
  $html .= "        else";
  $html .= "              {";
  $html .= "              return(false);";
  $html .= "              }";
  $html .= "	"; 
  $html .= "	}";   
  $html .= "	function ValidarCantidad(campo,valor,cant_sol,capa)\n";
	$html .= "	{\n";
	$html .= "		document.getElementById(campo).style.background='';\n";
	$html .= "		document.getElementById('error').innerHTML='';\n";
	$html .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
	$html .= "		{\n";
	$html .= "			document.getElementById(campo).value='';\n";
	$html .= "			document.getElementById(campo).style.background='#ff9595';\n";
	$html .= "			document.getElementById('error').innerHTML='<center>VALOR NO VALIDO</center>';\n";
  $html .= "		}\n";
	$html .= "	}\n";

  $html .= " </script>";
    $html .= $ctl->AcceptNum(true);
    $html .= ThemeAbrirTabla('GLOSA');
	if($DATOS['sw_glosa_total_factura']=='1')
		$mensaje=" GLOSA A TODA LA FACTURA";
		else
		$mensaje=" GLOSA AL DETALLE DE LA FACTURA";
		
	if($DATOS['sw_mayor_valor']=='1')
		$mensaje_=" GLOSA POR MAYOR VALOR";
		else
		$mensaje_=" GLOSA POR MENOR VALOR";
	
    $html .= "  <center>";
	 $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          #".$DATOS['prefijo']."-".$DATOS['factura_fiscal'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TIPO GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$mensaje;
    $html .= "      			<a href=\"".$accion['cambiar_tipo_glosa']."\" >";
    $html .= "						 <img title=\"CAMBIAR TIPO GLOSA\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
    $html .= "						</a>";
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      MOTIVO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['motivo_glosa_descripcion'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      CONCEPTO GENERAL";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['descripcion_concepto_general'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      CONCEPTO ESPECIFICO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['descripcion_concepto_especifico'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['valor_glosa'],2);
    $html .= "      </td>";
    $html .= "    </tr>";    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR ACEPTADO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "           $".FormatoValor($DATOS['valor_aceptado'],2);
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR NO ACEPTADO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['valor_no_aceptado'],2);
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$mensaje_;
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"6\">";
    $html .= "      OBSERVACION";
    $html .= "      </td>";
	$html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td align=\"center\" colspan=\"6\">";
    $html .= "          ".$DATOS['observacion'];
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";
	$html .= "  <center>";
    $html .= "  <table border=\"1\" width=\"20%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td width=\"50%\" class=\"modulo_table_list_title\">";
	$html .= "		GLOSAR";
	$html .= "      </td>";
	$html .= "      <td align=\"center\" width=\"20%\">";
	/*
  * Para evaluar si la glosa es para toda la factura o por detalle
  */
  if($DATOS['sw_glosa_total_factura']=='0')
  {
  $Link  = "       				<a href=\"".$accion['glosar']."\" >";
	$Link .= "						 <img title=\"CREAR GLOSA\" src=\"".GetThemePath()."/images/pplan.png\" border=\"0\">";
  }
    else
        {
        $Link  = "       				<a onclick=\"xajax_VerGlosa_Total('".$DATOS['esm_glosa_id']."');\">";
        $Link .= "						 <img title=\"CREAR GLOSA\" src=\"".GetThemePath()."/images/pplan.png\" border=\"0\">";
        }
  $html .= "                ".$Link;
	$html .= "						</a>";
    $html .= "      </td>";
	$html .= "	  </tr>";
	$html .= "	</table>";
	$html .= "<br>";
    $html .= "  <center>";
    $html .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FACTURA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          #".$DATOS['prefijo']."-".$DATOS['factura_fiscal'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FECHA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['fecha_registro'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      USUARIO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['nombre'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TERCERO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['tipo_id_tercero']." ".$DATOS['tercero_id']."-".$DATOS['nombre_tercero'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      PLAN";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['plan_descripcion'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      SALDO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['saldo'],2);
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";
	
	if(!empty($DATOS_DETALLE) && $DATOS['sw_glosa_total_factura']=='0')
	{
	$html .= "  <center>";
    $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_table_list_title\">";
    $html .= "      <td >";
    $html .= "      PRODUCTO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      VALOR GLOSA";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      VALOR ACEPTADO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      VALOR NO ACEPTADO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      MOTIVO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      CONCEPTO GENERAL";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      CONCEPTO ESPECIFICO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      OP";
    $html .= "      </td>";
	$html .= "    </tr>";

			$est = "modulo_list_claro";
            $bck = "#DDDDDD";
	foreach($DATOS_DETALLE as $key => $valor)
		{
		($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
		($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
	$html .= "	    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
	$html .= "      	<td>";
	$html .= "				".$valor['producto'];
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				$".FormatoValor($valor['valor_glosa'],2);
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				$".FormatoValor($valor['valor_aceptado'],2);
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				$".FormatoValor($valor['valor_no_aceptado'],2);
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				".$valor['motivo_glosa_descripcion'];
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				".$valor['descripcion_concepto_general'];
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				".$valor['descripcion_concepto_especifico'];
	$html .= "			</td>";
	$html .= "			<td>";
	if($valor['sw_estado']=='1')
    {
    $link = "      		<a onclick=\"xajax_VerGlosa('".$valor['esm_glosa_detalle_id']."','".$valor['esm_glosa_id']."');\" >";
    $link .= "        <img title=\"GLOSA\" src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\">";
    }
    else
      {
      $link = "      		<a onclick=\"xajax_VerGlosa('".$valor['esm_glosa_detalle_id']."','".$valor['esm_glosa_id']."');\" >";
      $link .= "        <img title=\"GLOSA\" src=\"".GetThemePath()."/images/folder_lleno.png\" border=\"0\">";
      }
  $html .=  $link;
	$html .= "				</a>";
	$html .= "			</td>";
	$html .= "		</tr>";
		}
	$html .= "	</table>";
	}
   
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" value=\"APLICAR GLOSA\" onclick=\"xajax_AplicarGlosaGeneral('".$DATOS['esm_glosa_id']."');\">\n";
    $html .= "      </td>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" value=\"ANULAR GLOSA\" onclick=\"xajax_AnularGlosaGeneral('".$DATOS['esm_glosa_id']."');\">\n";
    $html .= "      </td>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= ThemeCerrarTabla();
	$html .= $this->CrearVentana(740,"GLOSA");
    return $html;
    }
    
    
   	function Forma_Glosar($accion,$DATOS,$DetalleFactura,$MotivoGlosa,$glosas_concepto_general)
    {
    $ctl = AutoCarga::factory("ClaseUtil");
 	$html .= $ctl->LimpiarCampos();
 	$html .= $ctl->RollOverFilas();
 	$html .= $ctl->AcceptDate('/');
	$Select_MotivoGlosa  = "	<select class=\"select\" name=\"motivo_glosa_id\"name=\"motivo_glosa_id\" style=\"width:50%\">";
	$Select_MotivoGlosa .= "	<option value=\"\">-- SELECCIONAR --</option>";
	foreach($MotivoGlosa as $key => $valor)
	$Select_MotivoGlosa .= "	<option value=\"".$valor['motivo_glosa_id']."\">".$valor['motivo_glosa_id']." - ".$valor['motivo_glosa_descripcion']."</option>";
	$Select_MotivoGlosa .= "	</select>";
	
	
	
	$html .= "	<script>";
	$html .= "	function Asignar(campo,valor)";
	$html .= "	{"; 
	$html .= "	document.getElementById(campo).value=valor;"; 
	$html .= "	"; 
	$html .= "	}";
	
	$html .= "	function ValidarCantidad(campo,valor,cant_sol,capa)\n";
	$html .= "	{\n";
	$html .= "		document.getElementById(campo).style.background='';\n";
	$html .= "		document.getElementById('error').innerHTML='';\n";
	$html .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
	$html .= "		{\n";
	$html .= "			document.getElementById(campo).value='';\n";
	$html .= "			document.getElementById(campo).style.background='#ff9595';\n";
	$html .= "			document.getElementById('error').innerHTML='<center>VALOR NO VALIDO</center>';\n";
  $html .= "		}\n";
	$html .= "	}\n";
	
      $html .= "</script>\n";
      $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
	  $html .= " <script>";
    $html .= "  function Validar(Formulario)";
    $html .= "  {";
    $html .= "    if(Formulario.fecha_glosa.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: La Fecha Glosa No Puede Estar Vacia!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.motivo_glosa_id.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Motivo Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.codigo_concepto_general.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Concepto General Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.codigo_concepto_especifico.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Concepto Especifico Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    
    $html .= " document.esm_glosa.submit();";
    $html .= " }";
    $html .= " </script>";
    $html .= $ctl->AcceptNum(true);
    $html .= ThemeAbrirTabla('GLOSA');
	if($DATOS['sw_glosa_total_factura']=='1')
		$mensaje=" GLOSA A TODA LA FACTURA";
		else
		$mensaje=" GLOSA AL DETALLE DE LA FACTURA";
		
	if($DATOS['sw_mayor_valor']=='1')
		$mensaje_=" GLOSA POR MAYOR VALOR";
		else
		$mensaje_=" GLOSA POR MENOR VALOR";
	
    $html .= "  <center>";
	 $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          #".$DATOS['prefijo']."-".$DATOS['factura_fiscal'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TIPO GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$mensaje;
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      MOTIVO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['motivo_glosa_descripcion'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      CONCEPTO GENERAL";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['descripcion_concepto_general'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      CONCEPTO ESPECIFICO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['descripcion_concepto_especifico'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['valor_glosa'],2);
    $html .= "      </td>";
    $html .= "    </tr>";    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR ACEPTADO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "           $".FormatoValor($DATOS['valor_aceptado'],2);
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR NO ACEPTADO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['valor_no_aceptado'],2);
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$mensaje_;
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"6\">";
    $html .= "      OBSERVACION";
    $html .= "      </td>";
	$html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td align=\"center\" colspan=\"6\">";
    $html .= "          ".$DATOS['observacion'];
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";
	$html .= "  <center>";
    $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FACTURA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          #".$DATOS['prefijo']."-".$DATOS['factura_fiscal'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FECHA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['fecha_registro'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      USUARIO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['nombre'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TERCERO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['tipo_id_tercero']." ".$DATOS['tercero_id']."-".$DATOS['nombre_tercero'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      PLAN";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['plan_descripcion'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      SALDO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['saldo'],2);
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";

	
	$Select_GlosaConceptoGeneral .= "	<option value=\"\">-- SELECCIONAR --</option>";
	foreach($glosas_concepto_general as $key => $valor)
	$Select_GlosaConceptoGeneral .= "	<option value=\"".$valor['codigo_concepto_general']."\">".$valor['codigo_concepto_general']." - ".$valor['descripcion_concepto_general']."</option>";
	$Select_GlosaConceptoGeneral .= "	</select>";
	
  if(!empty($DetalleFactura))
	{
    $html .= "<center>";
	$html .= "	<div id=\"error\" class=\"label_error\"></div>";
	$html .= "	<form id=\"GlosarItems\" name=\"GlosarItems\" method=\"POST\">";
	$i=0;
	$html .= "  <table border=\"1\" width=\"50%\" align=\"center\" rules=\"all\">\n";
	$html .= "    <tr class=\"modulo_list_claro\">";
	$html .= "      <td class=\"modulo_table_list_title\" WIDTH=\"50%\">";
	$html .= "		MOTIVO GLOSA";
	$html .= "      </td>";
	$html .= "      <td class=\"modulo_list_claro\" >";
	$html .= "		".$Select_MotivoGlosa;
	$html .= "      </td>";
	$html .= "		<td>";
	$html .= "		<input type=\"button\" value=\"GUARDAR GLOSA\" class=\"input-submit\" onclick=\"xajax_GuardarGlosa(xajax.getFormValues('GlosarItems'));\"";
	$html .= "		</td>";
	$html .= "	</tr>";
	$html .= "	</table>";
	$html .= "	<br>";
	foreach($DetalleFactura as $k1 => $dtl)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
	$html .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"15%\">";
    $html .= "      PRODUCTO";
    $html .= "      </td>";
	$html .= "      <td WIDTH=\"20%\">";
    $html .= "      ".$dtl['producto'];
    $html .= "      </td>";
	$html .= "      <td class=\"modulo_table_list_title\" width=\"10%\">";
    $html .= "      PRECIO";
    $html .= "      </td>";
	$html .= "      <td width=\"15%\">";
    $html .= "      $".FormatoValor($dtl['precio'],2);
    $html .= "      </td>";
	$html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR TOTAL";
    $html .= "      </td>";
	$html .= "      <td width=\"10%\">";
    $html .= "      $".FormatoValor($dtl['valor_total'],2);
    $html .= "      </td>";
	$html .= "		<td class=\"modulo_table_list_title\">";
	$html .= "		CANTIDAD";
	$html .= "		</td>";
	$html .= "		<td>";
	$html .= "		<b>".$dtl['cantidad']."</b>";
	$html .= "		</td>";
	$html .= "			<td rowspan=\"2\" align=\"center\">";
	$html .= "                    <input type=\"checkbox\" class=\"input-checkbox\" value=\"".$dtl['codigo_producto']."\" name=\"".$i."\" id=\"".$i."\">";
	$html .= "                    </td>";
	$html .= "	</tr>";
    
	$html .= "	 <tr class=\"modulo_list_claro\" >\n";
	$html .= "		<td class=\"modulo_table_list_title\">";
    $html .= "      VALOR GLOSA";
    $html .= "      </td>";
	$html .= "		<td>";
	$html .= "			<a onclick=\"Asignar('valor_glosa".$i."','".$dtl['valor_total']."');\" >";
	$html .= "			<img title=\"Valor Glosa\" src=\"".GetThemePath()."/images/hcright.png\" border=\"0\">";
	$html .= "			</a>";
	$html .= "          <input type=\"text\" name=\"valor_glosa".$i."\" id=\"valor_glosa".$i."\" onkeypress=\"return acceptNum(event);\" class=\"input-text\" value=\"0\" onkeyup=\"ValidarCantidad('valor_glosa".$i."',document.getElementById('valor_glosa".$i."').value,'".$dtl['valor_total']."','hell".$i."')\">";
	$html .= "     </td>";
	$html .= "                    	<td class=\"modulo_table_list_title\">";
	$html .= "                    	CONCEPTO GENERAL";
	$html .= "                    	</td>";
	$html .= "                    	<td>";
	$html .= "						<select class=\"select\" name=\"codigo_concepto_general".$i."\"name=\"codigo_concepto_general".$i."\" style=\"width:50%\" onchange=\"xajax_Listado_ConceptoEspecifico(this.value,'".$i."');\">";
	$html .= "						".$Select_GlosaConceptoGeneral;
	$html .= "						</select>";
	$html .= "                    	</td>";
	$html .= "                    	<td class=\"modulo_table_list_title\">";
	$html .= "                    	CONCEPTO ESPECIFICO";
	$html .= "                    	</td>";
	$html .= "                    	<td>";
	$html .= "          			<select name=\"codigo_concepto_especifico".$i."\" id=\"codigo_concepto_especifico".$i."\" style=\"width:50%\" class=\"select\">";
	$html .= "          			<option value=\"\">-- SELECCIONAR --</option>";
    $html .= "          			</select>";
	$html .= "                    	</td>";
	$html .= "                    	<td class=\"modulo_table_list_title\">";
	$html .= "                    	OBSERVACION";
	$html .= "                    	</td>";
	$html .= "                    	<td>";
	$html .= "                    	<textarea name=\"observacion".$i."\" id=\"observacion".$i."\" class=\"textarea\" style=\"width:100%\"></textarea>";
	$html .= "                    	</td>";
	
	$html .= "					</tr>";
	$html .= "  </table>\n";
	$html .= "<br>";
	$i++;
	}
	$html .= "  <table border=\"1\" width=\"50%\" align=\"center\" rules=\"all\">\n";
	$html .= "    <tr class=\"modulo_list_claro\">";
	$html .= "		<td align=\"center\">";
	$html .= "		<input type=\"hidden\" value=\"".$_REQUEST['esm_glosa_id']."\" name=\"esm_glosa_id\" id=\"esm_glosa_id\">";
	$html .= "		<input type=\"hidden\" value=\"".$DATOS['prefijo']."\" name=\"prefijo\" id=\"prefijo\">";
	$html .= "		<input type=\"hidden\" value=\"".$DATOS['factura_fiscal']."\" name=\"factura_fiscal\" id=\"factura_fiscal\">";
	$html .= "		<input type=\"hidden\" value=\"".$i."\" name=\"registros\" id=\"registros\">";
	$html .= "		<input type=\"button\" value=\"GUARDAR GLOSA\" class=\"input-submit\" onclick=\"xajax_GuardarGlosa(xajax.getFormValues('GlosarItems'));\"";
	$html .= "		</td>";
	$html .= "	</tr>";
	$html .= "	</table>";
	$html .= "	</form>";
    $html .= "</center>";
	
	}
      
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= ThemeCerrarTabla();
    return $html;
    }
  
  
  
  // CREAR LA CAPITA
	function CrearVentana($tmn,$Titulo)
    {
      $html .= "<script>\n";
      $html .= "  var contenedor = 'Contenedor';\n";
      $html .= "  var titulo = 'titulo';\n";
      $html .= "  var hiZ = 4;\n";
      $html .= "  function OcultarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"none\";\n";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "  }\n";
      //Mostrar Span
	  $html .= "  function MostrarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"\";\n";
      $html .= "      Iniciar();\n";
      $html .= "    }\n";
      $html .= "    catch(error){alert(error)}\n";
      $html .= "  }\n";

      $html .= "  function MostrarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xShow(Seccion);\n";
      $html .= "  }\n";
      $html .= "  function OcultarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xHide(Seccion);\n";
      $html .= "  }\n";

      $html .= "  function Iniciar()\n";
      $html .= "  {\n";
      $html .= "    contenedor = 'Contenedor';\n";
      $html .= "    titulo = 'titulo';\n";
      $html .= "    ele = xGetElementById('Contenido');\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    ele = xGetElementById(contenedor);\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
      $html .= "    ele = xGetElementById(titulo);\n";
      $html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
      $html .= "    xMoveTo(ele, 0, 0);\n";
      $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $html .= "    ele = xGetElementById('cerrar');\n";
      $html .= "    xResizeTo(ele,20, 20);\n";
      $html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
      $html .= "  }\n";

      $html .= "  function myOnDragStart(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "    window.status = '';\n";
      $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
      $html .= "    else xZIndex(ele, hiZ++);\n";
      $html .= "    ele.myTotalMX = 0;\n";
      $html .= "    ele.myTotalMY = 0;\n";
      $html .= "  }\n";
      $html .= "  function myOnDrag(ele, mdx, mdy)\n";
      $html .= "  {\n";
      $html .= "    if (ele.id == titulo) {\n";
      $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
      $html .= "    }\n";
      $html .= "    else {\n";
      $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
      $html .= "    }  \n";
      $html .= "    ele.myTotalMX += mdx;\n";
      $html .= "    ele.myTotalMY += mdy;\n";
      $html .= "  }\n";
      $html .= "  function myOnDragEnd(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "  }\n";
      
      
      $html.= "function Cerrar(Elemento)\n";
           $html.= "{\n";
           $html.= "    capita = xGetElementById(Elemento);\n";
           $html.= "    capita.style.display = \"none\";\n";
           $html.= "}\n";
      
      
      
      $html .= "</script>\n";
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";


    
      return $html;
    }    
  /* DESCARGA DE ARCHIVOS */
    function Vista_Formulario_Descargas($action,$buscador,$DATOS,$DATOS_TRASLADOS,$DATOS_DESPACHOS,$DATOS_DISPENSADOS,$DATOS_PENDIENTES_DISPENSADOS,$plan)
    {
  
	$ctl = AutoCarga::factory("ClaseUtil");

	$html .= $ctl->LimpiarCampos();
	$html .= $ctl->RollOverFilas();

	
     $csv = Autocarga::factory("ReportesCsv");
	 $html.= $csv->GetJavacriptReporte ('app', 'Formulacion_Externa_Facturacion', 'Cortes', $DATOS, 'comas');
	 $fncn  = $csv->GetJavaFunction();

	 
	 
	 
		$html .= ThemeAbrirTabla('DESCARGAR  CORTES');
		$html .= "<form name=\"factura\" action=\"".$action['buscar']."\" method=\"post\">\n";
		$html .= "  <table width=\"55%\" align=\"center\">\n";
		$html .= "    <tr>\n";
		$html .= "      <td>\n";
		$html .= "	      <fieldset class=\"fieldset\">\n";
		$html .= "          <legend class=\"normal_10AN\">FILTRO PARA LA DESCARGA  DE CORTES</legend>\n";
		$html .= "		      <table width=\"100%\">\n";
		$html .= "            <tr>\n";
		$html .= "              <td    align=\"center\"  class=\"normal_10AN\">NO CORTE</td>\n";
		$html .= "              <td>\n";
		$html .= "                <input type=\"text\" name=\"buscador[no_corte]\" id=\"no_corte\" class=\"input-text\" value=\"\">\n";
		$html .= "              </td>\n";
		$html .= "            </tr>\n";

		$html .= "			      <tr>\n";

		$html .= "			      <tr>\n";
		$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
		$html .= "					      <input type=\"hidden\" name=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\">\n";
		$html .= "					      <input type=\"hidden\" name=\"datos[ssiid]\" value=\"".$_REQUEST['datos']['ssiid']."\">\n";
		$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
		$html .= "					      <input type=\"reset\" class=\"input-submit\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.factura);\">\n";
		$html .= "				      </td>\n";
		$html .= "			      </tr>\n";
		$html .= "		      </table>\n";
		$html .= "	      </fieldset>\n";
		$html .= "	    </td>\n";
		$html .= "	  </tr>\n";
		$html .= "	</table>\n";
		$html .= "</form>\n";

		
		
		
	
		
	if(!empty($DATOS))
	{
		$html .= "  <center>";
	    $html .= "  <table border=\"0\" width=\"60%\" align=\"center\"  class=\"modulo_table_list\" >\n";
	    $html .= "    <tr  class=\"formulacion_table_list\">";
	    $html .= "      <td >";
	    $html .= "      NO CORTE";
	    $html .= "      </td>";
		$html .= "      <td >";
	    $html .= "      FECHA INICIO";
	    $html .= "      </td>";
		$html .= "      <td >";
	    $html .= "      FECHA FINAL";
	    $html .= "      </td>";
		$html .= "      <td >";
	    $html .= "      EMPRESA";
	    $html .= "      </td>";
	    $html .= "      <td width=\"5%\" >";
	    $html .= "      OP";
	    $html .= "      </td>";
		$html .= "    </tr>";

			$est = "modulo_list_claro";
			$bck = "#DDDDDD";
				($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
				($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
				$html .= "	    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
				$html .= "      	<td  align=\"center\" >";
				$html .= "				".$DATOS['corte_id'];
				$html .= "			</td>";
				$html .= "      	<td>";
				$html .= "			".$DATOS['fecha_inicio'];
				$html .= "			</td>";
				$html .= "      	<td>";
				$html .= "				".$DATOS['fecha_final'];
				$html .= "			</td>";
				$html .= "      	<td>";
				$html .= "				".$DATOS['razon_social'];
				$html .= "			</td>";
			
				
			
				
				$html .= "          <td align=\"center\">\n";
				$html .= "	           <a href=\"javascript:".$fncn."\" class=\"label_error\">\n";
				$html .= "        <img title=\"DESCARGAR CORTE\" src=\"".GetThemePath()."/images/uf.png\" border=\"0\"> ";
				$html .= "            </a>\n";
				$html .= "          </td>\n";
			
				$html .= "		</tr>";
			
			$html .= "	</table>";
	}
   
		
		
		
		
		
		
		


		$html .= "  <table border=\"0\" width=\"30%\" align=\"center\">";
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </form>";
		$html .= "  </td>";

		$html .= "  </tr>";
		$html .= "  </table>";


      
      $html .= ThemeCerrarTabla();

    return $html;
    }
    
    
  
  }
?>