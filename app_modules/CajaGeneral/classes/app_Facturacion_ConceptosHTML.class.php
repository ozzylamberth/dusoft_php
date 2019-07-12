<?php

/* * ****************************************************************************
 * $Id: app_Facturacion_ConceptosHTML.class.php,v 1.5 2010/11/18 14:18:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * $Revision: 1.5 $ 
 * 
 * @autor Carlo A. Henao 
 * Proposito del Archivo:	Manejo logico de las facturas concepto desde caja
 * ****************************************************************************** */
IncludeClass('app_Facturacion_Conceptos', '', 'app', 'CajaGeneral');

class app_Facturacion_ConceptosHTML {

    var $empresa;

    function app_Facturacion_ConceptosHTML()
    {
        
    }

    //METODO PARA CONSULTAR LOS USUARIOS DE LA CAJA CONCEPTOS
    function TraerUsuarios($Caja = null)
    {
        $UsuarioCpto = new app_Facturacion_Conceptos();
        $UsuarioConceptos = $UsuarioCpto->TraerUsuariosConceptos($Caja);
        return $UsuarioConceptos;
    }

    //FIN METODO PARA CONSULTAR LOS USUARIOS DE LA CAJA CONCEPTOS

    function ManejoFacturasHTML($dat = array())
    {
        $acc = $dat[accion];
        $html = "<tr class=modulo_list_oscuro>";
        $html .= "<td    align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a TITLE='REIMPRESION Y ENVIOS DE FACTURAS CONCEPTO' href=\"$acc\">MANEJO DE FACTURAS</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/informacion.png\">";
        $html .="</td>";
        $html.="</tr>";
        return $html;
    }

    function FormaMenuFacturacionConceptos($empresa)
    {
        $html = ThemeAbrirTabla('MENU FACTURACION CONCEPTOS', '68%');
        $html .= "            <br>";
        $html .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $html .= "               <tr>";
        $html .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU</td>";
        $html .= "               </tr>";
        $html .= "               <tr>";
        $accion1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaBusquedaFacturasConceptos', array('empresa' => $empresa));
        $html .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion1\">BUSCAR FACTURAS</a></td>";
        $html .= "               </tr>";
       /* $html .= "               <tr>";
        $accion2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaFormaResponsable', array('empresa' => $empresa)); //,'FormaResponsable'
        $html .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accion2\">ENVIOS</a></td>";
        $html .= "               </tr>";*/
        /*        $html .= "               <tr>";
          $accion3=ModuloGetURL('app','CajaGeneral','user');//,'FormaBuscarRad' ,array('empresa'=>$empresa)
          $html .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion3\">MANEJO ENVIOS</a></td>";
          $html .= "               </tr>"; */
        $html .= "           </table>";
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarPermisosUser'); //MenudeCaja
        $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $html .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
        $html .= "</form>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    function FormaBusquedaFacturasConceptosHTML($empresa, $arr)
    {
        $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarPermisosUser'); //MenudeCaja
        $aceptar = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaFormaBuscar', array('empresa' => $empresa));

        $html .= "<script language=\"javascript\">";
        $html .= "function acceptNum(evt)\n";
        $html .= "    {\n";
        $html .= "      var nav4 = window.Event ? true : false;\n";
        $html .= "      var key = nav4 ? evt.which : evt.keyCode;\n";
        $html .= "      return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
        $html .= "    }\n";
        $html .= "		function BuscarTercero()\n";
        $html .= "		{\n";
        $html .= "			var url=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaBuscarTerceros', array("empresa" => $empresa)) . "\"\n";
        $html .= "			window.open(url,'','width=750,height=550,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
        $html .= "		}\n";

        $html .= "		function limpiarCampos(objeto)\n";
        $html .= "		{\n";
        $html .= "			objeto.Factura.value = \"\";\n";
        $html .= "			objeto.Documento.value = \"\";\n";
        $html .= "			objeto.Nombres.value = \"\";\n";
        $html .= "			objeto.Apellidos.value = \"\";\n";
        $html .= "			objeto.DocumentoTercero.value = \"\";\n";
        $html .= "			objeto.PrefijoFac.selectedIndex='0';\n";
        $html .= "			objeto.TipoDocumento.selectedIndex='0';\n";
        $html .= "			objeto.TipoDocumentoTercero.selectedIndex='0';\n";
        $html .= "		}\n";

        $html .= "</script>\n";
        $fct = new app_Facturacion_Conceptos();
        $html.= ThemeAbrirTabla('BUSQUEDA DE FACTURAS');
        IncludeLib("tarifario");
        //$accion=ModuloGetURL('app','Facturacion_Fiscal','user','BuscarFacturas');
        $html .= $this->EncabezadoEmpresa($empresa);
        $html .= "<br>\n";
        $html .= "<table border=\"0\" width=\"82%\" align=\"center\" class=\"normal_10\">\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        $html .= "			<table border=\"0\" width=\"100%\" align=\"center\">\n";
        $html .= "				<tr>\n";
        $html .= "					<td>\n";
        $html .= "					<fieldset><legend class=\"normal_10AN\">CRITERIOS DE BUSQUEDA</legend>\n";
        $html .= "						<form name=\"forma\" action=\"$aceptar\" method=\"post\">\n";
        $html .= "							<table width=\"100%\" align=\"center\" border=\"0\">\n";
        $html .= "								<tr>\n";
        $html .= "									<td class=\"normal_10AN\" width=\"17%\">PREFIJO : </td>\n";
        $html .= "									<td width=\"30%\">\n";
        $prefijos = $fct->ObtenerPrefijosFacturas($empresa);
        $html .= "										<select name=\"PrefijoFac\" class=\"select\">\n";
        $html .= "											<option value=\"\">-SELECCIONE-</option>\n";
        $sel = "";
        foreach ($prefijos as $key => $pref)
        {
            ($this->post['PrefijoFac'] == $pref['prefijo']) ? $sel = "selected" : $sel = "";
            $html .= "										<option value=\"" . $pref['prefijo'] . "\" $sel>" . $pref['prefijo'] . "</option>\n";
        }
        $html .= "										</select>\n";
        $html .= "									</td>\n";
        $html .= "									<td class=\"normal_10AN\" width=\"18%\">No. FACTURA: </td>\n";
        $html .= "									<td>\n";
        $html .= "										<input type=\"text\" class=\"input-text\" name=\"Factura\" onkeypress=\"return acceptNum(event);\" value=\"" . $this->post['Factura'] . "\">\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr>\n";
        $html .= "									<td class=\"normal_10AN\">TIPO ID CLIENTE: </td>\n";
        $html .= "									<td>\n";
        $html .= "										<select name=\"TipoDocumentoTercero\" class=\"select\">\n";
        $html .= "											<option value=\"\">-------SELECCIONE-------</option>\n";
        $tipo_id = $fct->ObternerTiposIdTerceros();
        $html .= $this->BuscarIdPaciente($tipo_id, $this->post['TipoDocumentoTercero']);
        $html .= "										</select>\n";
        $html .= "									</td>\n";
        $html .= "									<td class=\"normal_10AN\">CLIENTE ID: </td>\n";
        $html .= "									<td>\n";
        $html .= "										<input type=\"text\" class=\"input-text\" style=\"width:100%\" name=\"DocumentoTercero\" value=\"" . $this->post['DocumentoTercero'] . "\">\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";

        $html .= "								<tr>\n";
        $html .= "									<td class=\"normal_10AN\" align=\"center\" colspan=\"4\">\n";
        $html .= "										<a href=\"javascript:BuscarTercero()\" class=\"label_error\">BUSCAR CLIENTE POR NOMBRE</a>\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";

        $html .= "								<tr>\n";
        $html .= "									<td colspan = 4>\n";
        $html .= "										<table width=\"100%\">\n";
        $html .= "											<tr align=\"center\" >\n";
        $html .= "												<td width=\"40%\">\n";
        $html .= "													<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
        $html .= "												</td>\n";

        $html .= "												<td width=\"20%\">\n";
        $html .= "													<input class=\"input-submit\" type=\"button\" onclick=\"limpiarCampos(document.forma)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
        $html .= "												</td>\n";

        $html .= "												</form>\n";
        //$actionM=ModuloGetURL('app','Facturacion_Fiscal','user','FormaMenus');
        $html .= "												<form name=\"formabuscar\" action=\"$volver\" method=\"post\">";
        $html .= "												<td width=\"40%\">\n";
        $html .= "													<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
        $html .= "												</td>\n";
        $html .= "												</form>\n";
        $html .= "											</td>\n";
        $html .= "										</tr>\n";
        $html .= "									</table>\n";
        $html .= "								</td>\n";
        $html .= "							</tr>\n";
        $html .= "						</table>\n";
        $html .= "						</fieldset>\n";
        $html .= "					</td>\n";
        $html .= "				</tr>\n";
        $html .= "			</table>\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
        $html .= "</table>\n";
        $html .= "	<table border=\"0\" width=\"90%\" align=\"center\">\n";
        $html .= "		" . $this->SetStyle("MensajeError");
        $html .= "  </table>\n";
        $aceptar = $aceptar . "&empresa=" . $empresa . "&volver=" . $volver;

        if (!empty($arr))
        {
            $html .= "       <br>";
            $html .= "    <table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $html .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $html .= "        <td>No. FACTURA</td>";
            $html .= "        <td>IDENTIFICACIÓN</td>";
            $html .= "        <td>TERCERO</td>";
            $html .= "        <td>FECHA REGISTRO</td>";
            $html .= "        <td>USUARIO</td>";
            $html .= "        <td>SINCRONIZADO CON FI</td>";
            $html .= "       <td colspan='2'>ACCION</td>";
            $html .= "      </tr>";
            
            $html .= "
                        <script>
                        function confirmar_sincronizacion(empresa_id, prefijo, numero_factura){

                        if(confirm('Desea Sincronizar La Factura ['+numero_factura+'] ')){
                            xajax_sincronizar_facturas_pendientes_ws_fi(empresa_id, prefijo, numero_factura);
                            return false;
                        } else {                                
                            return false;
                        }
                    }
                    
                    </script>";
            
            for ($i = 0; $i < sizeof($arr); $i++)
            {
                //$datos=$this->CallMetodoExterno('app','Triage','user','BuscarPlanes',array('PlanId'=>$PlanId,'Ingreso'=>$Ingreso));
                //$Fechas=$this->FechaStamp($Fecha);
                //$Horas=$this->HoraStamp($Fecha);
                $accionHRef = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaFormaDetalleFacturaConcepto', array('fecha_registro' => $arr[$i][fecha_registro], 'factura_fiscal' => $arr[$i][factura_fiscal], 'prefijo' => $arr[$i][prefijo], 'empresa' => $arr[$i][empresa_id], 'nombre_tercero' => $arr[$i][nombre_tercero], 'tipo_factura' => $arr[$i][tipo_factura], 'identificacion' => $arr[$i][identificacion], 'sw_clase_factura' => $arr[$i][sw_clase_factura]));
                //$accionHRef=ModuloGetURL('app','Facturacion_Fiscal','user','Facturacion',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'numero'=>$arr[$i][factura_fiscal],'prefijo'=>$arr[$i][prefijo],'empresa'=>$arr[$i][empresa_id],'cu'=>$arr[$i][centro_utilidad]));
                if ($i % 2)
                {
                    $estilo = 'modulo_list_claro';
                }
                else
                {
                    $estilo = 'modulo_list_oscuro';
                }
                
                 $fct = new app_Facturacion_Conceptos();
                $logs = $fct->traerLogsSincronizacion($arr[$i][prefijo], $arr[$i][factura_fiscal]);
                
                $sincronizado = "Sincronizado";
                $color = "black";
                $url_images = GetThemePath() . "/images/conectado.png";
                $funcion_sincronizar = "alert('La Factura ya esta Sincronizada');";
                
                if($logs['estado'] == '1' || !$logs){
                    $sincronizado = "No Sincronizado";
                     $color = "red";
                     $url_images = GetThemePath() . "/images/desconectado.png";
                      $funcion_sincronizar = "confirmar_sincronizacion('{$arr[$i]['empresa_id']}', '{$arr[$i]['prefijo']}', '{$arr[$i]['factura_fiscal']}');";
                }
                
                $html .= "      <tr class=\"$estilo\">";
                $html .= "        <td align=\"center\">" . $arr[$i][prefijo] . " " . $arr[$i][factura_fiscal] . "</td>";
                $html .= "        <td align=\"center\">" . $arr[$i][identificacion] . "</td>";
                $html .= "        <td align=\"center\">" . $arr[$i][nombre_tercero] . "</td>";
                $html .= "        <td align=\"center\">" . $arr[$i][fecha_registro] . "</td>";
                $html .= "        <td align=\"center\">" . $arr[$i][nombre] . "</td>";  
                $html .= "        <td align=\"center\" ><span>{$sincronizado}</span>  </td>";
                $html .= "       <td align=\"center\"><a href='#'  onclick=\"{$funcion_sincronizar}\" style='margin-left:10px;'><img title=\"SINCRONIZAR CON FI\" src='{$url_images}' border=\"0\"></a></td>";
                $html .= "        <td align=\"center\"><a href=\"$accionHRef\">VER</a>   </td>";
                $html .= "      </tr>";
            }//fin for
            $html .= " </table>";
            $this->conteo = $_SESSION['SPY'];
            //$html .=$this->RetornarBarra();
        }
        $html .= ThemeCerrarTabla();
        return $html;
    }
    
    
    
    /**
     * Muestra los totales de la factura concepto.
     * @access private
     * @return void
     * @param int numero de la cuenta
     */
    function FormaDetalleFacturaConcepto($fecha_registro, $factura_fiscal, $prefijo, $empresa, $nombre_tercero, $tipo_factura, $identificacion, $sw_clase_factura, $impuestos = null)
    {
        $datos['PrefijoFac'] = $prefijo;
        $datos['Factura'] = $factura_fiscal;
        $fact = new app_Facturacion_Conceptos();
        $arr = $fact->ObtenerFacturasXPrefijo($datos, $empresa);
        $html = ThemeAbrirTabla('DETALLE DE LA FACTURA CONCEPTO Nro: ' . $prefijo . ' ' . $factura_fiscal, '95%');
        $html .= $this->EncabezadoEmpresa($empresa);
        $html .= " <table border=\"0\" width=\"90%\" align=\"center\" >";
        $html .= "   <tr><td><fieldset><legend class=\"field\">DATOS FACTURA CONCEPTO</legend>";
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\"  >";
        $html .= "   <tr>";
        $html .= "      <td class=\"label_mark\">NOMBRE TERCERO: </td>";
        $html .= "      <td class=\"label\">" . $arr[0][nombre_tercero] . "</td>";
        $html .= "      <td width=\"5%\"></td>";
        $html .= "      <td class=\"label_mark\">IDENTIDICACIÓN :</td>";
        $html .= "      <td class=\"label\">" . $arr[0][identificacion] . "</td>";
        $html .= "      <td width=\"5%\"></td>";
        $html .= "      <td class=\"label_mark\">FECHA FACTURA: </td>";
        $html .= "      <td class=\"label\">" . $arr[0][fecha_registro] . "</td>";
        $html .= "   </tr>";
        $html .= "  </table>";
        $html .= "    </fieldset></td></tr>";
        $html .= "  </table>";
        if ($sw_clase_factura == '0')
        {
            $tipo_factura = 'contado';
        }
        elseif ($sw_clase_factura == '1')
        {
            $tipo_factura = 'credito';
        }
        $html .= "  <table border=\"0\" width=\"89%\" align=\"center\" >";
        $html .= "    <tr><td><fieldset><legend class=\"field\"DETALLE> FACTURA CONCEPTO - " . $tipo_factura . "</legend>";
        $html .= "     <table border=\"0\" width=\"75%\" align=\"center\">";
        $dat = $fact->DetalleFacturaConceptos($empresa, $prefijo, $factura_fiscal);
        $html .= "   <tr align=\"center\">";
        $html .= "      <td align=\"center\" class=\"modulo_table_title\" width=\"65%\">CONCEPTO</td>";
        $html .= "      <td align=\"center\" class=\"modulo_table_title\" width=\"10%\">GRAVAMEN</td>";
        $html .= "      <td align=\"center\" class=\"modulo_table_title\" width=\"25%\">VALOR</td>";
        $html .= "   </tr>";
        foreach ($dat AS $i => $v)
        {
            $html .= "   <tr>";
            $html .= "      <td align=\"center\" class=\"modulo_list_claro\" width=\"65%\">" . $v[descripcion] . " - " . $v[concepto] . " </td>";
            $html .= "      <td align=\"center\" class=\"modulo_list_claro\" width=\"10%\">" . $arr[0]['gravamen']. "</td>";
            $html .= "      <td align=\"left\" class=\"modulo_list_claro\" width=\"25%\">" . FormatoValor($v[precio]) . "</td>";
            $html .= "   </tr>";
        }
        if ($arr[0][sw_clase_factura] == '0')
        {
            $totalpagado = $arr[0][total_efectivo] + $arr[0][total_tarjetas] + $arr[0][total_cheques];
        }
        else
        {
            $totalpagado = $arr[0][total_efectivo] = $arr[0][total_tarjetas] = $arr[0][total_cheques] = 0;
        }
        
        
       $total_precio_sin_iva =  $arr[0][total_factura] - $arr[0]['gravamen'];
       
       
       $retencion_fuente = 0;
       $retencion_ica = 0;
        
        if(!is_null($impuestos)){
            if ($impuestos['porcentaje_rtf'] > 0) {      
            //echo $valor_subtotal;
              if ($total_precio_sin_iva >= $impuestos['base_rtf']) {
                  //echo print_r($impuestos);
                  $retencion_fuente = $total_precio_sin_iva  * ($impuestos['porcentaje_rtf'] / 100);
                  if ($retencion_fuente > 0) {
                      $retencion_fuente = (int) $retencion_fuente;
                  }
              }
          }


          if ($impuestos['porcentaje_ica'] > 0) {
              if ($total_precio_sin_iva >= $impuestos['base_ica']) {
                  $retencion_ica = $total_precio_sin_iva * ($impuestos['porcentaje_ica'] / 1000);
                  if ($retencion_ica > 0) {
                      $retencion_ica = (int) $retencion_ica;
                  }
              }

          }
          
    }
        
       // echo $arr[0][total_factura]. " " . $retencion_fuente . " " .$retencion_ica;
        $html .= "   <tr>";
        $html .= "      <td align=\"right\" class=\"modulo_table_list\" colspan=\"2\">TOTAL FACTURA:&nbsp;</td>";
        $html .= "      <td align=\"left\" class=\"modulo_table_list\">" . FormatoValor($arr[0][total_factura] - $retencion_fuente - $retencion_ica) . "</td>";
        $html .= "   </tr>";
        $html .= "   <tr>";
        $html .= "      <td align=\"right\" class=\"label_mark\" colspan=\"2\">TOTAL EFECTIVO:&nbsp;</td>";
        $html .= "      <td align=\"left\" class=\"label_mark\">" . FormatoValor($arr[0][total_efectivo]) . "</td>";
        $html .= "   </tr>";
        $html .= "   <tr>";
        $html .= "      <td align=\"right\" class=\"label_mark\" colspan=\"2\">TOTAL TARJETAS:&nbsp;</td>";
        $html .= "      <td align=\"left\" class=\"label_mark\">" . FormatoValor($arr[0][total_tarjetas]) . "</td>";
        $html .= "   </tr>";
        $html .= "   <tr>";
        $html .= "      <td align=\"right\" class=\"label_mark\" colspan=\"2\">TOTAL CHEQUES:&nbsp;</td>";
        $html .= "      <td align=\"left\" class=\"label_mark\">" . FormatoValor($arr[0][total_cheques]) . "</td>";
        $html .= "   </tr>";
        $html .= "   <tr>";
        $html .= "      <td align=\"right\" class=\"label_mark\" colspan=\"2\">TOTAL PAGADO:&nbsp;</td>";
        $html .= "      <td align=\"left\" class=\"label_mark\">" . FormatoValor($totalpagado) . "</td>";
        $html .= "      <td></td>";
        $html .= "   </tr>";
         $html .= "   <tr>";
        $html .= "      <td align=\"right\" class=\"label_mark\" colspan=\"2\">RETEFUENTE</td>";
        $html .= "      <td align=\"left\" class=\"label_mark\">" . FormatoValor($retencion_fuente) . "</td>";
        $html .= "      <td></td>";
        $html .= "   </tr>";
         $html .= "   <tr>";
        $html .= "      <td align=\"right\" class=\"label_mark\" colspan=\"2\">RETEICA</td>";
        $html .= "      <td align=\"left\" class=\"label_mark\">" . FormatoValor($retencion_ica) . "</td>";
        $html .= "      <td></td>";
        $html .= "   </tr>";
        $html .= "       </table>";
        $html .= "    </fieldset></td></tr>";
        $html .= "  </table>";
        $html .= "  <table border=\"0\" width=\"89%\" align=\"center\" >";
        $html .= "   <tr>";
        $arreglo = array('empresa' => $empresa, 'prefijo' => $prefijo, 'factura' => $factura_fiscal, 'tipo_factura' => $tipo_factura);
        $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaBusquedaFacturasConceptos', array('empresa' => $empresa));
        $mensaje = 'FACTURA CONCEPTOS GENERADA';
        $botonC = 'facturaconceptos';
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaFormaMensaje', array('arreglo' => $arreglo, 'action' => $action, 'mensaje' => $mensaje, 'botonC' => $botonC));
        $html .= "<form name=\"formaimprimir\" action=\"$accion\" method=\"post\">";
        $html .= "<td align=\"right\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"imprimir\" value=\"IMPRIMIR FACTURA\"></td>\n";
        $html .= "</form>";
        $accion1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaBusquedaFacturasConceptos', array('empresa' => $empresa));
        $html .= "<form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
        $html .= "<td align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"VOLVER\" value=\"VOLVER\"></td>\n";
        $html .= "</form>";
        $html .= "   </tr>";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     *
     */
    function FormaResponsable($empresa)
    {
        $fct = new app_Facturacion_Conceptos();
        /* 				if(empty($_REQUEST['accionRips']))
          {  $action=ModuloGetURL('app','CajaGeneral','user','LlamarFormaBuscarEnvios',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId));  }
          else
          {
          $action=ModuloGetURL('app','CajaGeneral','user',$_REQUEST['accionRips'],array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId));
          } */
        $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamarFormaBuscarEnvios', array('empresa' => $empresa));
        $html.= ThemeAbrirTabla('ELEGIR TERCERO');
        $html.= "            <br><br>";
        $html.= "            <table width=\"50%\" align=\"center\" border=\"0\">";
        $html.= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
        $html.= "               <tr><td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td><td><select name=\"Plan\" class=\"select\">";
        $responsables = $fct->Terceros($empresa);
        for ($i = 0; $i < sizeof($responsables); $i++)
        {
            $html.=" <option value=\"" . $responsables[$i][tipo_id_tercero] . "," . $responsables[$i][tercero_id] . "," . $responsables[$i][nombre_tercero] . "\">" . $responsables[$i][nombre_tercero] . "</option>";
        }
        $html.= "              </select></td></tr>";
        $html.= "              <tr>";
        $html.= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></td></form>";
        $actionM = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaFormaMenuFacturacionConceptos', array('empresa' => $empresa));
        $html.= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $html.= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form></tr>";
        $html.= "           </table>";
        $html.= ThemeCerrarTabla();
        return $html;
    }

    function EncabezadoEmpresa($empresa)
    {
        $dat = new app_Facturacion_Conceptos();
        $datos = $dat->DatosEncabezadoEmpresa($empresa);
        $html .= "<br>\n";
        $html .= "	<table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >\n";
        $html .= " 		<tr class=\"modulo_table_title\" height=\"21\">\n";
        $html .= " 			<td width=\"10%\">EMPRESA</td>\n";
        $html .= " 			<td class=\"modulo_list_claro\" >" . $datos[razon_social] . "</td>\n";
        $html .= " 		</tr>\n";
        $html .= " </table>\n";
        return $html;
    }

    /**
     *
     */
    function FormaBuscarEnvios($arr, $empresa, $plan)
    {
        IncludeLib('funciones_admision');
        $html = ThemeAbrirTabla('BUSQUEDA DE FACTURAS CONCEPTO PARA ENVIOS');
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaBuscarEnvios', array('empresa' => $empresa, 'plan' => $plan)); //BuscarEnvios
        $html .= $this->EncabezadoEmpresa($empresa);
        $html .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
        $html .= "<tr class=\"modulo_table_list_title\">";
        $html .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
        //$html .= "<td align = left >SELECCIONE LA FECHA:</td>";
        $html .= "</tr>";
        $dat = explode(',', $plan);
        $html .= "<tr><td class=\"modulo_list_claro\" align=\"center\">TERCERO: <b> $dat[2] $dat[0]: $dat[1]</b></td></tr>";
        $html .= "<tr class=\"modulo_list_claro\" >";
        $html .= "<td width=\"40%\" >";
        $html .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $html .= "<tr><td>";
        $html .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $html .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
        $html .= "<SCRIPT>";
        $html .= "function Revisar(frm,x){";
        $html .= "  if(x==true){";
        $html .= "frm.Fecha.value='TODAS LAS FECHAS'";
        $html .= "  }";
        $html .= "else{";
        $html .= "frm.Fecha.value=''";
        $html .= "}";
        $html .= "}";
        $html .= "function Todos(frm,x){";
        $html .= "  if(x==true){";
        $html .= "    for(i=0;i<frm.elements.length;i++){";
        $html .= "      if(frm.elements[i].type=='checkbox'){";
        $html .= "        frm.elements[i].checked=true";
        $html .= "      }";
        $html .= "    }";
        $html .= "  }else{";
        $html .= "    for(i=0;i<frm.elements.length;i++){";
        $html .= "      if(frm.elements[i].type=='checkbox'){";
        $html .= "        frm.elements[i].checked=false";
        $html .= "      }";
        $html .= "    }";
        $html .= "  }";
        $html .= "}";
        $html .= "</SCRIPT>";
        $ac = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarEnvios');
        $I = $_REQUEST['FechaI'];
        //$f = explode(',',$_SESSION['ENVIOS']['TERCERO']);
        $html .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">DESDE: </td>";
        $html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\" value=\"" . $I . "\">" . ReturnOpenCalendario('forma', 'FechaI', '/') . "</td>";
        $html .= "                </tr>";
        $html .= "                <tr>";
        $fi = $_REQUEST['FechaF'];
        if (!empty($i))
        {
            $f = explode('-', $_REQUEST['FechaF']);
            $fi = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        /* if($arr=='si' OR !empty($arr))
          {  $fi='';  } */
        $html .= "                    <td class=\"" . $this->SetStyle("FechaF") . "\">HASTA: </td>";
        $html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\" value=\"" . $fi . "\">" . ReturnOpenCalendario('forma', 'FechaF', '/') . "</td>";
        $html .= "                </tr>";
        /*      $html .= "                <tr>";
          $html .= "                    <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";
          $html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"prefijo\" value=\"".$_POST['prefijo']."\" size='3' maxlength=\"5\"></td>";
          $html .= "                </tr>";
          $html .= "                <tr>";
          $html .= "                    <td class=\"".$this->SetStyle("numero")."\">NUMERO: </td>";
          $html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"numero\" value=\"".$_POST['numero']."\" size='6' maxlength=\"10\"></td>";
          $html .= "                </tr>"; */
        $html .= "<tr class=\"label\">";
        $html .= "</tr>";
        $html .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $html .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
        $html .= "</form>";
        $actionM = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaFormaMenuFacturacionConceptos', array('empresa' => $empresa));  //}
        $html .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $html .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"><br></td></form>";
        $html .= "</tr>";
        $html .= "</table></td></tr>";
        $html .= "</td></tr></table>";
        $html .= "</table>";
        $html .= "</td>";
        $html .= "    </tr>";
        $html .= "  </table>";
        //mensaje
        $html .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
        $html .= $this->SetStyle("MensajeError");
        $html .= "  </table>";
        unset($_SESSION['FACTURACION']['ENVIO']['ARREGLO']);
        if (!empty($arr))
        {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamarFormaEnvio', array('empresa' => $empresa, 'plan' => $plan, 'datos' => $_REQUEST));
            $html .= "    <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $html .= "     <br><table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $html .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $html .= "        <td width=\"8%\">FACTURA</td>";
            $html .= "        <td width=\"15%\">FECHA REG.</td>";
            $html .= "        <td width=\"25%\">USUARIO</td>";
            $html .= "        <td width=\"15%\">VALOR</td>";
            $html .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\"></td>";
            $html .= "      </tr>";

            for ($i = 0; $i < sizeof($arr); $i++)
            {
                if ($i % 2)
                {
                    $estilo = 'modulo_list_claro';
                }
                else
                {
                    $estilo = 'modulo_list_oscuro';
                }
                $html .= "      <tr class=\"$estilo\">";
                $html .= "        <td align=\"center\">" . $arr[$i][prefijo] . " " . $arr[$i][factura_fiscal] . "</td>";
                $html .= "        <td align=\"center\">" . FechaStamp($arr[$i][fecha_registro]) . "</td>";
                $html .= "        <td align=\"center\">" . $arr[$i][nombre] . "</td>";
                $html .= "        <td align=\"center\">" . FormatoValor($arr[$i][total_factura]) . "</td>";
                $html .= "        <td><input type=\"checkbox\" value=\"" . $arr[$i][prefijo] . "||" . $arr[$i][factura_fiscal] . "||" . $arr[$i][total_factura] . "||" . $arr[$i][empresa_id] . "\" name=\"Envio" . $arr[$i][prefijo] . "" . $arr[$i][factura_fiscal] . $i . "\"></td>";
                $html .= "      </tr>";
            }
            $html .= "  </table>";
            $html .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\" HACER ENVIO\"></p>";
            $html .= "</form>";
        }
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     *
     */
    function FormaEnvio($empresa, $plan, $datos)
    {
        $tercero = explode(',', $plan);
        $html = ThemeAbrirTabla('ENVIOS - FACTURAS CONCEPTOS');
        IncludeLib("tarifario");
        $html.=$this->EncabezadoEmpresa($empresa);
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaHacerEnvio', array('empresa' => $empresa, 'Plan' => $plan, 'datos' => $datos, 'enviod' => $_REQUEST));
        $html .= "   <form name=\"formaenviar\" action=\"$accion\" method=\"post\">";
        $html .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $html .= "        <td width=\"30%\">FECHA DE REGISTRO DEL ENVIO</td>";
        $html .= "        <td width=\"70%\" align=\"left\" colspan=\"3\" class=\"modulo_list_oscuro\">";
        $html .= "          <input type=\"text\" class=\"input-text\" name=\"Fecha_Envio\" size=\"12\" value=\"" . date("d/m/Y") . "\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $html .= "&nbsp;&nbsp;" . ReturnOpenCalendario('formaenviar', 'Fecha_Envio', '/') . "</td>";
        $html .= "      </tr>";
        $html .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $html .= "        <td width=\"10%\">FACTURA</td>";
        $html .= "        <td width=\"20%\">VALOR</td>";
        $html .= "        <td width=\"15%\">IDENTIFICACION</td>";
        $html .= "        <td width=\"40%\">TERCERO</td>";
        //$html .= "        <td width=\"20%\">PLAN</td>";
        $html .= "      </tr>";
        $i = $total = 0;
        $k = 1;
        foreach ($_REQUEST as $k => $v)
        {
            if (substr_count($k, 'Envio'))
            {
                //0 prefijo 1 factura 2 total
                $x = explode('||', $v);
                $total+=$x[2];
                $var = '';
                //$var=$this->DetalleFactura($x[0],$x[1]);
                if ($i % 2)
                {
                    $estilo = 'modulo_list_claro';
                }
                else
                {
                    $estilo = 'modulo_list_oscuro';
                }
                //if(sizeof($var) > 1)
                //{
                //		$html .= "      <tr class=\"$estilo\">";
                //		$html .= "        <td align=\"center\">".$x[0]." ".$x[1]."</td>";
                //		$html .= "        <td align=\"center\">".FormatoValor($x[4])."</td>";
                //		$html .= "        <td></td>";
                //		$html .= "        <td>AGRUPADA</td>";
                //		$html .= "        <td>".$x[6]."</td>";
                //		$html .= "      </tr>";
                //}
                //else
                //{
                $html .= "      <tr class=\"$estilo\">";
                $html .= "        <td align=\"center\"  width=\"10%\">" . $x[0] . " " . $x[1] . "</td>";
                $html .= "        <td align=\"center\">" . FormatoValor($x[2]) . "</td>";
                $html .= "        <td>$tercero[0]: $tercero[1]</td>";
                $html .= "        <td>" . $tercero[2] . "</td>";
                //$html .= "        <td>".$x[6]."</td>";
                $html .= "      </tr>";
                //}
                /* for($j=0; $j<sizeof($var); $j++)
                  {

                  $html .= "      <tr class=\"$estilo\">";
                  $html .= "        <td align=\"center\">".$x[0]." ".$x[1]."</td>";
                  $html .= "        <td align=\"center\">".FormatoValor($x[4])."</td>";
                  $html .= "        <td>".$var[$j][tipo_id_paciente]." ".$var[$j][paciente_id]."</td>";
                  $html .= "        <td>".$var[$j][nombre]."</td>";
                  $html .= "        <td>".$x[6]."</td>";
                  $html .= "      </tr>";
                  $k++;
                  } */
                $i++;
            }
        }
        $html .= "  </table>";
        $html .= "     <br><table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">";
        $html .= "      <tr>";
        $html .= "        <td width=\"25%\"></td>";
        $html .= "        <td width=\"35%\">TOTAL DOCUMENTOS: </td>";
        $html .= "        <td>$i</td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= "        <td width=\"25%\"></td>";
        $html .= "        <td width=\"35%\">TOTAL ENVIO ($): </td>";
        $html .= "        <td>" . FormatoValor($total) . "</td>";
        $html .= "      </tr>";
        $html .= "  </table><BR>";
        $html .= "  <p><br></p>";
        $html .= "     <table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
        $html .= "      <tr>";
        $html .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ENVIAR\"></td>";
        $html .= "  </form>";
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamarFormaBuscarEnvios', array('empresa' => $empresa, 'Plan' => $plan));
        $html .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $html .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\"></td>";
        $html .= "  </form>";
        /*      $ac=ModuloGetURL('app','CajaGeneral','user','BuscarEnvios',array('adicionar'=>'1'));
          $html .= "             <form name=\"formadicionarenvio\" action=\"$ac\" method=\"post\">";
          $html .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ADICIONAR FACTURA\"></td>";
          $html .= "  </form>"; */
        $html .= "      </tr>";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*     * ******************************************************************************* 
     * 
     * ******************************************************************************** */

    function HacerEnvio($empresa, $plan, $f_envio, $datos, $enviod)
    {
        $fecha_envio = $this->ConvFecha($f_envio);
        $fct = new app_Facturacion_Conceptos();
        $arr = $fct->DatosHacerEnvio($empresa, $plan, $fecha_envio, $datos, $enviod);
        $ht = $this->FormaImpresionEnvio($titulo = "", $arr, $empresa, $plan);
        //$ht = $this->FormaBuscarEnvios('',$empresa,$plan);
        return $ht;
    }

    /**
     * Forma para la impresion de los envios de factura conceptos generados
     *
     * @param string titulo(titulo)
     * @param array arr
     */
    function FormaImpresionEnvio($titulo, $arr, $empresa, $plan)
    {
        $reporte = new GetReports();
        $html = ThemeAbrirTabla("ENVIOS REALIZADOS", "90%");
        $html .= $this->EncabezadoEmpresa($empresa);
        $html .= "<br>";
        $html .= "<table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "	<tr class=\"modulo_table_list_title\">\n";
        $html .= "		<th>NO. ENVIO</th>\n";
        //$html .= "		<th>PLAN</th>\n";
        $html .= "		<th>NO. FACTURAS</th>\n";
        $html .= "		<th>TOTAL</th>\n";
        $html .= "		<th>OPCIONES</th>\n";
        $html .= "	</tr>\n";
        $i = 0;
        //$tipo_reporte = $this->ConsultaTipoReporte($arr,'ENVIO_FACTURA');
        foreach ($arr as $envio_id => $datos_envio)
        {
            ($i % 2 == 0) ? $clase = "modulo_list_claro" : $clase = "modulo_list_oscuro";
            $i++;
            $mostrar = $reporte->GetJavaReport('app', 'CajaGeneral', 'enviosHTMConceptos', array('envio' => $envio_id, 'tipo_reporte' => $tipo_reporte), array('rpt_name' => 'envio', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = $reporte->GetJavaFunction();
            $html .=$mostrar;
            //$accion = ModuloGetURL("app","CajaGeneral","user","DescargarRipsEnvio",array("tiporips"=>"Envio","EnvioRips"=>$envio_id."/".$_SESSION['FACTURACION']['EMPRESA'],"download"=>"1"));
            $html .= "	<tr class=\"$clase\">\n";
            $html .= "		<td align=\"left\">" . $envio_id . "</td>\n";
            //$html .= "		<td align=\"left\">".$datos_envio["plan_descripcion"]."</td>\n";
            $html .= "		<td align=\"center\">" . $datos_envio["cantidad_facturas"] . "</td>\n";
            $html .= "		<td align=\"right\">" . FormatoValor($datos_envio["total_envio"]) . "</td>\n";
            $html .= "		<td align=\"center\">";
            $html .= "			<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\">";
            $html .= "				<tr class=\"$clase\" >\n";
            $html .= "					<td width=\"50%\" align=\"center\">";
            $html .= "						<a href=\"javascript:$funcion\" title=\"Imprimir Envio\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\"></a>";
            $html .= "					</td>";
            /* 			$html .= "					<form name=\"frmDescargarRips$envio_id\" action=\"$accion\" method=\"post\" target=\"_blank\">";
              $html .= "						<td width=\"50%\" align=\"center\">";
              $html .= "							<input type=\"image\" src=\"".GetThemePath()."/images/abajo.png\" name=\"btnDescargarRips\" value=\"DESCARGAR RIPS\"  class=\"input-submit\" title=\"Descargar Rips\">";
              $html .= "						</td>"; */
            $html .= "					</form>";
            $html .= "				</tr>";
            $html .= "			</table>";
            $html .= "		</td>";
            $html .= "	</tr>";
        }
        $html .= "</table>";
        $html .= "<br>";
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamarFormaBuscarEnvios', array('empresa' => $empresa, 'Plan' => $plan));
        $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $html .= "	<div align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></div>";
        $html .= "</form>";
        unset($reporte);
        $html .= ThemeCerrarTabla();
        return $html;
    }

//Fin FormaImpresionEnvio
    /*     * ******************************************************************************* 
     * 
     * ******************************************************************************** */

    function BuscarEnvios($FechaI, $FechaF, $prefijo, $numero, $empresa, $plan)
    {
        $fct = new app_Facturacion_Conceptos();
        $arr = $fct->TraerDatosEnvio($FechaI, $FechaF, $prefijo, $numero, $empresa, $plan);
        $ht = $this->FormaBuscarEnvios($arr, $empresa, $plan);
        return $ht;
    }

    function BuscarFacturas($empresa)
    {
        unset($_SESSION['FACTURACION']['VAR']);
        $this->post = $_REQUEST;
        $this->post['PrefijoFac'] = $this->post['PrefijoFac'];
        $cantidad = $_SESSION['SPY'];
        $this->Emp = $_SESSION['FACTURACION']['EMPRESA'];

        if (empty($_REQUEST['paso']))
            $cantidad = 0;
        $factura = $_REQUEST['Factura'];

        $prefijofac = $_REQUEST['PrefijoFac'];
        $tercero_dc = $this->post['DocumentoTercero'];
        $tercero_td = $this->post['TipoDocumentoTercero'];
        $tipo_documento = $this->post['TipoDocumento'];
        //$empresa = $this->post['empresa'];
        //$volver = $this->post['volver'];

        if (!empty($factura) && !is_numeric($factura))
        {
            $this->frmError["MensajeError"] = "EL CAMPO No. FACTURA DEBE SER NUMERICO";
            //$this->FormaBuscarFacturas();
            $this->FormaBusquedaFacturasConceptosHTML($empresa);
            return true;
        }

        $fct = new app_Facturacion_Conceptos();

        if (!empty($prefijofac))
        {
            $paso = true;
            if (empty($factura))
                $this->frmError['MensajeError'] = "SE DEBE INDICAR EL NUMERO DE FACTURA A BUSCAR";
            else
                $var = $fct->ObtenerFacturasXPrefijo($this->post, $empresa);
        }
        else if (!empty($tercero_dc))
        {
            $paso = true;
            if (empty($tercero_td))
                $this->frmError['MensajeError'] = "SE DEBE INDICAR EL TIPO DE TERCERO A BUSCAR";
            else
                $var = $fct->ObtenerFacturasXTerceroId($this->post, $empresa, SessionGetVar("DocumentosFacturacion"), $_REQUEST['Of'], $cantidad, $empresa);
        }
        else if (!empty($nombres) || !empty($apellidos))
        {
            $paso = true;
            $var = $fct->ObtenerFacturasXNombrePaciente($this->post, $this->Emp, SessionGetVar("DocumentosFacturacion"), $_REQUEST['Of'], $cantidad);
        }
        else
        {
            $this->frmError['MensajeError'] = "SE DEBEN INDICAR PARAMETROS DE BUSQUEDA";
        }

        if (empty($var) && $paso)
            $this->frmError['MensajeError'] = "LA BUSQUEDA NO ARROJO NINGUN RESULTADO";

        if (empty($_REQUEST['paso']))
            $_SESSION['SPY'] = $fct->conteo;

        //$this->FormaBuscarFacturas($var);
        $ht = $this->FormaBusquedaFacturasConceptosHTML($empresa, $var);
        return $ht;
    }

    /**
     * Cambia el formato de la fecha de dd/mm/YY a YY/mm/dd
     * @access private
     * @return string
     * @param date fecha
     * @var    cad   Cadena con el nuevo formato de la fecha
     */
    function ConvFecha($fecha)
    {
        if ($fecha)
        {
            $fech = strtok($fecha, "/");
            for ($i = 0; $i < 3; $i++)
            {
                $date[$i] = $fech;
                $fech = strtok("/");
            }
            $cad = $date[2] . "-" . $date[1] . "-" . $date[0];
            return $cad;
        }
    }

    function SetStyle($campo)
    {
        if ($this->frmError[$campo] || $campo == "MensajeError")
        {
            if ($campo == "MensajeError")
            {
                return ("<tr><td class='label_error' colspan='3' align='center'>" . $this->frmError["MensajeError"] . "</td></tr>");
            }
            return ("label_error");
        }
        return ("label");
    }

    /**
     * Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
     * @access private
     * @return void
     */
    function BuscarIdPaciente($tipo_id, $TipoId = '')
    {
        $s = "";
        foreach ($tipo_id as $value => $titulo)
        {
            ($value == $TipoId) ? $s = "selected" : $s = "";
            $html .=" <option value=\"$value\" $s >$titulo</option>\n";
        }
        return $html;
    }

}

//fin classe
?>