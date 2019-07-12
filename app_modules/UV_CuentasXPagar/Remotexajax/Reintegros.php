<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Reintegros.php,v 1.1 2009/01/14 22:22:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  
  /**
  * Funcion que permite hacer l abusqueda del familiar
  *
  * @param array $form Arreglo con los datros de la forma
  *
  * @return object
  */
  function BuscarFamiliar($form)
  {
    $rtg = AutoCarga::factory('Reintegros','','app','UV_CuentasXPagar');
    $vst = AutoCarga::factory('ReintegrosHTML','views','app','UV_CuentasXPagar');
    
    $beneficiarios = $rtg->ObtenerBeneficiariosCotizante($form,"C");
    
    $action['cerrar'] = "OcultarSpanGrande()";
    
    $html = $vst->FormaMostarFamiliares($action,$beneficiarios);
    $html = utf8_encode($html);
    
    $objResponse = new xajaxResponse();
    
    $objResponse->assign("capa_buscador","innerHTML",$html);
    $objResponse->call("MostrarSpanGrande");
    
    return $objResponse;
  }
  /**
  * Funcion que permite hacer la busqueda del afiliado
  *
  * @param array $form Arreglo con los datros de la forma
  * @param integer $off Referencia al offset
  *
  * @return object
  */
  function BuscarAfiliado($form,$off)
  {
    $objResponse = new xajaxResponse();

 		$vst = AutoCarga::factory('ReintegrosHTML','views','app','UV_CuentasXPagar');
    $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
    
    $pagina = $conteo = 0;
    $afiliados = array();
    if(!empty($form) || $off)
    {
      $rtg = AutoCarga::factory('Reintegros','','app','UV_CuentasXPagar');
      $afiliados = $rtg->ObtenerListaAfiliados($form['buscador'],$off,true);
      $conteo = $rtg->conteo;
      $pagina = $rtg->pagina;
    }
    $tipos_documento = $cxp->ObtenerTipoIdTerceros();
    
    $action['paginador'] = "BuscarAfiliado(";
    $action['buscar'] = "BuscarAfiliado(0)";
    $action['cerrar'] = "OcultarSpanGrande()";
    
    $html = $vst->FormaBuscadorAfiliados($action,$form['buscador'],$tipos_documento,$afiliados,$pagina,$conteo,$msgError);
    $html = utf8_encode($html);
    
    $objResponse->assign("capa_buscador","innerHTML",$html);
    $objResponse->call("MostrarSpanGrande");
    
    return $objResponse;
  }
  /**
  *
  */
  function AsiganarAfiliado($afiliado_tipo_id,$afiliado_id,$eps_afiliacion_id)
  {
    $rtg = AutoCarga::factory('Reintegros','','app','UV_CuentasXPagar');
    $datos = array("Documento"=> $afiliado_id,"TipoDocumento"=>$afiliado_tipo_id,"eps_afiliacion_id"=>$eps_afiliacion_id);
    
    $afiliados = $rtg->ObtenerListaAfiliados($datos,$off);
    $afiliado = $afiliados[key($afiliados)]; 
    
    $nombre = utf8_encode($afiliado['apellidos_afiliado']." ".$afiliado['nombres_afiliado']);
    $ocupacion = utf8_encode($afiliado['descripcion_ciuo_88_grupo_primario']);

    $scp  = "tam = document.reintegro.dependencia_laboral.length;";
    $scp .= "for (i= 0; i< tam; i++ )";
    $scp .= "{";
    $scp .= "  if(document.reintegro.dependencia_laboral[i].value == '".$afiliado['codigo_dependencia_id']."') ";
    $scp .= "    document.reintegro.dependencia_laboral.selectedIndex = i;";
    $scp .= "}";
    
    $objResponse = new xajaxResponse();
    $objResponse->assign("afiliado_tipo_id","value",$afiliado_tipo_id);
    $objResponse->assign("afiliado_id","value",$afiliado_id);
    $objResponse->assign("eps_afiliacion_id","value",$eps_afiliacion_id);
    $objResponse->assign("estamento_id","value",$afiliado['estamento_id']);
    $objResponse->assign("ciu88_grandes_grupos","value",$afiliado['ciuo_88_gran_grupo']);
    $objResponse->assign("ciu88_sub_grupos_principales","value",$afiliado['ciuo_88_subgrupo_principal']);
    $objResponse->assign("ciu88_sub_grupo","value",$afiliado['ciuo_88_subgrupo']);
    $objResponse->assign("ciu88_grupos_primarios","value",$afiliado['ciuo_88_grupo_primario']);
    $objResponse->assign("ocupacion_texto","innerHTML",$ocupacion);
    $objResponse->assign("estamento","innerHTML",$afiliado['descripcion_estamento']);
    $objResponse->assign("identificacion","innerHTML",$afiliado_tipo_id." ".$afiliado_id);
    $objResponse->assign("solicitante","innerHTML",$nombre);
    $objResponse->assign("familiar_tipo_id","value","");
    $objResponse->assign("familiar_id","value","");
    $objResponse->assign("parentesco_id","value","");
    $objResponse->assign("familiar","innerHTML","");
    $objResponse->assign("parentesco","innerHTML","");
    $objResponse->script($scp);
    $objResponse->call("OcultarSpanGrande");
    return $objResponse;
  }
  /**
  *
  */
  function AsignarFamiliar($afiliado_tipo_id,$afiliado_id,$eps_afiliacion_id)
  {
    $vst = AutoCarga::factory('Reintegros','','app','UV_CuentasXPagar');
    $datos = array("afiliado_id"=> $afiliado_id,"afiliado_tipo_id"=>$afiliado_tipo_id,"eps_afiliacion_id"=>$eps_afiliacion_id);
   
    $beneficiarios = $vst->ObtenerBeneficiariosCotizante($datos,"B");
    $afiliado = $beneficiarios[key($beneficiarios)]; 
    
    $nombre = utf8_encode($afiliado['primer_apellido']." ".$afiliado['segundo_apellido']." ".$afiliado['primer_nombre']." ".$afiliado['segundo_nombre']);
    $parentesco = utf8_encode($afiliado['descripcion_parentesco']);
    
    $objResponse = new xajaxResponse();
    $objResponse->assign("familiar_tipo_id","value",$afiliado_tipo_id);
    $objResponse->assign("familiar_id","value",$afiliado_id);
    $objResponse->assign("parentesco_id","value",$afiliado['parentesco_id']);
    $objResponse->assign("familiar","innerHTML",$nombre);
    $objResponse->assign("parentesco","innerHTML",$parentesco);

    $objResponse->call("OcultarSpanGrande");
    return $objResponse;
  }  
  /**
  *
  */
  function SeleccionaraOcupacion($form,$op)
  {
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
    
    if($op == '1' )
    {
      $form['grandes_grupos'] = $form['ciu88_grandes_grupos'];
      $form['sub_grupos_principales'] = $form['ciu88_sub_grupos_principales'];
      $form['sub_grupo'] = $form['ciu88_sub_grupo'];
      $form['grupos_primarios'] = $form['ciu88_grupos_primarios'];
      
      //if($form['grandes_grupos'] !== "0") $form['grandes_grupos'] = "-1"; 
    }
    else if($op == '2')
    {
      if(!$form['grandes_grupos']) $form['grandes_grupos'] = "0";
    }

    $ocupacion = $afi->ObtenerGruposOcupacion();
    $subgrupoprincipal = $subgrupo = $grupo_primario = array();
    
    if($form['grandes_grupos'] != '-1')
      $subgrupoprincipal = $afi->ObtenerSubGruposPrincipalesOcupacion($form['grandes_grupos']);
    if($form['sub_grupos_principales'] != '-1')
      $subgrupo = $afi->ObtenerSubGruposOcupacion($form['grandes_grupos'],$form['sub_grupos_principales']);
    if($form['sub_grupo'] != '-1')
      $grupo_primario = $afi->ObtenerGruposPrimariosOcupacion($form['grandes_grupos'],$form['sub_grupos_principales'],$form['sub_grupo']);
    
    $sel = "";
    $html  = "		<table width=\"100%\" class=\"modulo_table_list\">\n";
    $html .= "			<tr >\n";
    $html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO</td>\n";
    $html .= "				<td width=\"%\">\n";
    $html .= "					<select name=\"grandes_grupos\" class=\"select\" onchange=\"SeleccionarOcupacion()\">\n";
    $html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
    foreach($ocupacion as $key => $detalle)
    {
      ($key == $form['grandes_grupos'])? $sel = "selected": $sel = "";
      $html .= "						<option value=\"".$key."\" title=\"".$detalle['descripcion_ciuo_88_gran_grupo']."\" $sel>".substr($detalle['descripcion_ciuo_88_gran_grupo'],0,40)."</option>\n";
    }
    $html .= "					</select>\n";			
    $html .= "				</td>\n";		
    $html .= "			</tr>\n";
    $html .= "			<tr >\n";
    $html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SUBGRUPO PRINCIPAL</td>\n";
    $html .= "				<td width=\"%\">\n";
    $html .= "					<select name=\"sub_grupos_principales\" class=\"select\" onChange=\"SeleccionarOcupacion()\">\n";
    $html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
    
    foreach($subgrupoprincipal as $key => $dtl)
    {
      ($key == $form['sub_grupos_principales'])? $sel = "selected": $sel = "";
      $html .= "						<option value=\"".$key."\" title=\"".$dtl['descripcion_ciuo_88_subgrupo_principal']."\" $sel>".substr($dtl['descripcion_ciuo_88_subgrupo_principal'],0,40)."</option>\n";
    }
    
    $html .= "					</select>\n";			
    $html .= "				</td>\n";		
    $html .= "			</tr>\n";
    $html .= "			<tr >\n";
    $html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SUBGRUPO</td>\n";
    $html .= "				<td width=\"%\">\n";
    $html .= "					<select name=\"sub_grupo\" class=\"select\" onChange=\"SeleccionarOcupacion()\">\n";
    $html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
    foreach($subgrupo as $key => $dtl)
    {
      ($key == $form['sub_grupo'])? $sel = "selected": $sel = "";
      $html .= "						<option value=\"".$key."\" title=\"".$dtl['descripcion_ciuo_88_subgrupo']."\" $sel>".substr($dtl['descripcion_ciuo_88_subgrupo'],0,40)."</option>\n";
    }
    $html .= "					</select>\n";			
    $html .= "				</td>\n";		
    $html .= "			</tr>\n";
    $html .= "			<tr >\n";
    $html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO PRIMARIO</td>\n";
    $html .= "				<td width=\"%\" >\n";
    $html .= "					<select name=\"grupos_primarios\" class=\"select\" onChange=\"LabelOcupacion(document.oculta.grupos_primarios.options[document.oculta.grupos_primarios.selectedIndex].title)\">\n";
    $html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
    foreach($grupo_primario as $key => $dtl)
    {
      ($key == $form['grupos_primarios'])? $sel = "selected": $sel = "";
      $html .= "						<option value=\"".$key."\" title=\"".$dtl['descripcion_ciuo_88_grupo_primario']."\" $sel>".substr($dtl['descripcion_ciuo_88_grupo_primario'],0,40)."</option>\n";
    }
    $html .= "					</select>\n";			
    $html .= "				</td>\n";		
    $html .= "			</tr>\n";
    $html .= "		</table>\n";
    $html .= "    <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "	    <tr>\n";
    $html .= "		    <td align=\"center\">\n";
    $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosOcupacion(document.oculta)\">\n";
    $html .= "		    </td>";
    $html .= "		    <td align=\"center\">\n";
    $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan()\">\n";
    $html .= "		    </td>";
    $html .= "	    </tr>";
    $html .= "    </table>";
    //$html = utf8_encode($html);
    
    $objResponse = new xajaxResponse();
    
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  /**
  *
  */
  function AsignarOcupacion($form)
  {
    $objResponse = new xajaxResponse();
    $scp = "    document.getElementById(\"ocupacion_texto\").innerHTML = document.reintegro.texto.value\n"; 

    $objResponse->assign("ciu88_grandes_grupos","value",$form['grandes_grupos']);
    $objResponse->assign("ciu88_sub_grupos_principales","value",$form['sub_grupos_principales']);
    $objResponse->assign("ciu88_sub_grupo","value",$form['sub_grupo']);
    $objResponse->assign("ciu88_grupos_primarios","value",$form['grupos_primarios']);
    $objResponse->script($scp);
    $objResponse->call("OcultarSpan");
    return $objResponse;
  }
  /**
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function ValidarForma($form)
  {
    $ctl = AutoCarga::factory("ClaseUtil");
    
    $objResponse = new xajaxResponse();
    $mensaje = "";
    
    $f =  explode("/",$form['fecha_solicitud']);
    $fecha = $f[2]."/".$f[1]."/".$f[0];
    
    $f =  explode("/",$form['fecha_factura']);
    $fechaf = $f[2]."/".$f[1]."/".$f[0];
      
    if(!$ctl->ValidarFecha($form['fecha_solicitud'],"/"))
      $mensaje = "LA FECHA DE SOLICITUD ES INCORRECTA O POSEE UN FORMATO INCORRECTO";
    else if($fecha > date ("Y/m/d"))
      $mensaje = "LA FECHA DEL DOCUMENTO NO PUEDE SER MAYOR A DIA ACTUAL ".date ("d/m/Y");
      else if($form['afiliado_id'] == '')
        $mensaje = "NO SE HA SELECCIONADO EL AFILIADO ASOCIADO AL DOCUMENTO DE REINTEGRO";
        else if($form['lugar_expedicion_documento'] == '')
          $mensaje = "NO SE HA INDICADO EL LUGAR DE EXPEDICION";
          else if($form['dependencia_laboral'] == "-1")
            $mensaje = "SE DEBE SELECCIONAR LA DEPENDENCIA";
            else if(!$form['concepto_reintegro'])
              $mensaje = "SE DEBE ESPECIFICAR EL CONCEPTO DEL REINTEGRO";
              else if($form['concepto_reintegro'] == "OT" && $form['otro_concepto'] == "")
                $mensaje = "SE DEBE ESPECIFICAR LA DESCRIPCION DEL OTRO CONCEPTO";
                else if(!is_numeric($form['valor_solicitado']))
                  $mensaje = "EL VALOR SOLICITADO POSEE UN FORMATO INCORRECTO";
                  else if($form['valor_solicitado'] <= 0)
                    $mensaje = "EL VALOR SOLICITADO DEBE SER MAYOR A CERO";
                    else if($form['familiar_cx'] == '1')
                    {
                      if(!$form['familiar_id'])
                        $mensaje = "NO SE HA ESPECIFICADO EL FAMILIAR";
                    }
                    else if($form['prefijo_factura'] == "" || $form['numero_factura'] == "")
                      $mensaje = "EL PRTEFIJO Y NUMERO DE LA FACTURA SON OBLIGATORIOS";
                      else if(!$ctl->ValidarFecha($form['fecha_factura'],"/"))
                        $mensaje = "LA FECHA DE LA FACTURA ES INCORRECTA O POSEE UN FORMATO INCORRECTO";
                        else if($fechaf > $fecha)
                          $mensaje = "LA FECHA DE LA FACTURA NO DEBE SER MAYOR A LA FECHA DE SOLICITUD ".$form['fecha_factura'];
                          else if(!is_numeric($form['valor_total']))
                            $mensaje = "EL VALOR TOTAL DE LA FACTURA POSEE UN FORMATO INCORRECTO";
                            else if($form['valor_gravamen'] != "" && !is_numeric($form['valor_gravamen']))
                              $mensaje = "EL VALOR DEL GRAVAMEN POSEE UN FORMATO INCORRECTO";
                              else if($form['valor_iva'] != "" && !is_numeric($form['valor_iva']))
                                $mensaje = "EL VALOR DEL IVA POSEE UN FORMATO INCORRECTO";
                                else if($form['auditor'] == "-1")
                                  $mensaje = "SE DEBE SELECCIONAR EL AUDITOR ASIGNADO A LA REVISION DE LA CUENTA";
                                
    $objResponse->assign("error_forma","innerHTML",$mensaje);
    if($mensaje == "")
      $objResponse->call("ContinuarReintegro");
    return $objResponse;
  }
  /**
  *
  */
  function BuscarTercero($form,$off)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
 		$vst = AutoCarga::factory('ReintegrosHTML','views','app','UV_CuentasXPagar');
    $tipos_terceros = $cxp->ObtenerTipoIdTerceros();
    
    $msgError = "";
    $lista_terceros = array();
    if(!empty($form) && $off >= 0)
    {
      $rtg = AutoCarga::factory('Reintegros','','app','UV_CuentasXPagar');
      $lista_terceros = $rtg->ObtenerProveedores($form['buscadortercero'],$off);
      $conteo = $rtg->conteo;
      $pagina = $rtg->pagina;
      $msgError = $rtg->ErrMsg();
    }
    
    $action['paginador'] = "BuscarTercero(";
    $action['buscar'] = "BuscarTercero(0)";
    $action['cerrar'] = "OcultarSpanGrande()";
    
    $html = $vst->FormaBuscarTerceros($action,$form['buscadortercero'],$tipos_terceros,$lista_terceros,$pagina,$conteo,$msgError);
    $html = utf8_encode($html);
    
    $objResponse->assign("capa_buscador","innerHTML",$html);
    $objResponse->call("MostrarSpanGrande");
    return $objResponse;
  }  
  /**
  *
  */
  function AsignarTercero($nombre_tercero,$codigo_proveedor)
  {
    $objResponse = new xajaxResponse();
    $objResponse->assign("prestador_servicio","value",$nombre_tercero);
    $objResponse->assign("codigo_proveedor","value",$codigo_proveedor);
    $objResponse->call("OcultarSpanGrande");
    return $objResponse;
  }
?>