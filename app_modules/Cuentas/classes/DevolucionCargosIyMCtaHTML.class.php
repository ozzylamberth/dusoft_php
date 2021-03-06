<?php
  /******************************************************************************
  * $Id: DevolucionCargosIyMCtaHTML.class.php,v 1.6 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.6 $ 
  * 
  * @autor Lorena Aragon Galindo
  ********************************************************************************/
  IncludeClass('DevolucionCargosIyMCta','','app','Cuentas');
  
  class DevolucionCargosIyMCtaHTML
  {
    function DevolucionCargosIyMCtaHTML(){}
    /**********************************************************************************
    * Funcion donde se buscan crea la forma de los paquetes
    * 
    * @return array 
    ***********************************************************************************/
    function CrearFormaDevolucionCargosCta($Cuenta,$TipoId,$PacienteId,$accionDevolver,$accionSalir,$objeto,$mensaje){ 
      
      $file = 'app_modules/Cuentas/RemoteXajax/DevolucionCargosIyMCta.php';
      $objeto->SetXajax(array("ValidarFechaVencimiento","InsertarFechaVencimiento","EliminaFechaVence","EliminaFechaVenceForma"),$file);
      $funciones=new DevolucionCargosIyMCta;      
      $Nombres=$funciones->BuscarNombresPaciente($TipoId,$PacienteId);
      $Apellidos=$funciones->BuscarApellidosPaciente($TipoId,$PacienteId);              
      $vector=$funciones->IYMCuenta($Cuenta);      
      $html .= ThemeAbrirTabla('DEVOLVER INSUMOS Y MEDICAMENTOS DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);       
      $html .="<script language='javascript'>";
      $html .= '  function mOvr(src,clrOver){';
      $html .= '    src.style.background = clrOver;';
      $html .= '  }';
      $html .= '  function mOut(src,clrIn){';
      $html .= '    src.style.background = clrIn;';
      $html .= '  }';
      $html .= "  function Iniciar()\n";
      $html .= "  {\n";        
      $html .= "    document.getElementById('titulo').innerHTML = '<center>FECHAS DE VENCIMIENTO</center>';\n";
      $html .= "    document.getElementById('error').innerHTML = '';\n";                
      $html .= "    contenedor = 'd2Container';\n";
      $html .= "    titulo = 'titulo';\n";
      $html .= "    ele = xGetElementById('d2Container');\n";
      $html .= "    xResizeTo(ele,700, 'auto');\n";
      $html .= "    xMoveTo(ele, xClientWidth()/5, xScrollTop()+24);\n";
      $html .= "    ele = xGetElementById('titulo');\n";
      $html .= "    xResizeTo(ele,680, 20);\n";
      $html .= "    xMoveTo(ele, 0, 0);\n";
      $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $html .= "    ele = xGetElementById('cerrar');\n";
      $html .= "    xResizeTo(ele,20, 20);\n";
      $html .= "    xMoveTo(ele, 680, 0);\n";
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
      $html .= "  function MostrarSpan(Seccion)\n";
      $html .= "  { \n";
      $html .= "    e = xGetElementById(Seccion);\n";
      $html .= "    e.style.display = \"\";\n";
      $html .= "  }\n";
      $html .= "  function Cerrar()\n";
      $html .= "  { \n";
      $html .= "    e = xGetElementById('d2Container');\n";
      $html .= "    e.style.display = \"none\";\n";
      $html .= "  }\n";
      $html .= "  function MostrarVentana()\n";
      $html .= "  { \n";
      $html .= "    e = xGetElementById('d2Container');\n";
      $html .= "    e.style.display = \"block\";\n";
      $html .= "  }\n";
      $html.= "  function LlamarCalendariofechaVencimiento()";
      $html.= "  {";
      $html.= "  window.open('classes/calendariopropio/Calendario.php?forma=formaFechaVence&campo=fechaVencimiento&separador=/','CALENDARIO_SIIS','width=450,height=250,resizable=no,status=no,scrollbars=yes');";
      $html.= "  }";
      $html.= "  function CallEliminaFechaVence(codigo_producto,loteT,valor,cantidad){";
      $html.= "   xajax_EliminaFechaVence(codigo_producto,loteT,valor,cantidad);";
      $html.= "  }";
      $html.= "  function CallEliminaFechaVenceForma(codigo_producto,loteT,valor,cantidad){";
      $html.= "   xajax_EliminaFechaVenceForma(codigo_producto,loteT,valor,cantidad);";
      $html.= "  }";
      $html .="</script>";
      
      $ventana.= "  <div id='d2Container' class='d2Container' style=\"display:none\">\n";
      $ventana.= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;\"></div>\n";
      $ventana.= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $ventana.= "  <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
      $ventana.= "  <div id='d2Contents'>\n";
      $ventana.= "  </div>";
      $ventana.= "  </div>";        
      $html   .=$ventana;
      foreach($_REQUEST as $name_request=>$val_request){
        $nameVal_request=substr($name_request,0,8);        
        if($nameVal_request=='cantidad'){
          if($val_request>0){
            $vector_request[$name_request]=$val_request;
          }
        }
      }      
      if($vector){
        $html .= "<form name=\"forma1\" action=\"$accionDevolver\" method=\"post\">";
        $html .= "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"70%\" align=\"center\">";        
        if($mensaje){
          $html .= "<p class=\"label_error\" align=\"center\">$mensaje</p>";
        }
        
        foreach($vector as $empresa=>$vector1){
          foreach($vector1 as $centro_utilidad=>$vector2){
            foreach($vector2 as $bodega=>$vector3){
              foreach($vector3 as $nom_bodega=>$vector4){                
                $html .= "<tr class=\"modulo_table_title\"><td colspan=\"5\">BODEGA:&nbsp;&nbsp;&nbsp;$bodega - $nom_bodega</td></tr>";
                $html .= "<tr class=\"modulo_table_list_title\">";
                $html .= "<td width=\"15%\">CODIGO</td>";
                $html .= "<td width=\"40%\">DESCRIPCION</td>";
				$html .= "<td width=\"15%\">FECHA VENCIMIENTO</td>";
				$html .= "<td>LOTE</td>";
                $html .= "<td width=\"15%\">CANTIDAD<br>A DEVOLVER</td>";        
                $html .= "</tr>";                
                foreach($vector4 as $lote=>$vector5){
					foreach($vector5 as $fecha_vencimiento=>$vector6){
						foreach($vector6 as $codigo_producto=>$datos){
						  if($j % 2){$estilo="modulo_list_claro";  }
						  else{$estilo="modulo_list_oscuro";   }                           
						   $disabled='';$img=''; 
						  if($datos['permiso']!='1'){
							$disabled='disabled';
							$img="<img border = 0 src=\"".GetThemePath()."/images/delete.gif\" title=\"No tiene Permiso en esta Bodega\">";
						  }
						  $html .= "       <tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";              
						  $html .= "       <td>$codigo_producto</td>";
						  $html .= "       <td>".$datos['descripcion']."</td>";
						  $html .= "       <td>".$datos['fecha_vencimiento']."</td>";
						  $html .= "       <td>".$datos['lote']."</td>";
						  $html .= "       <td align=\"center\">";
						  $validarFechaVence='';                  
						  if($datos['sw_control_fecha_vencimiento']==1){ 
							$_SESSION['FECHAS_VENCIMIENTO']['REQUIERE'][$codigo_producto]=1;
							//lo dejo aqui porque no lo alcance a terminar                   
							$validarFechaVence=" onchange=\"xajax_ValidarFechaVencimiento(this.name,this.value,'$action');\"";
						  }
						  $datos['cantidad']=(int)($datos['cantidad']);                 
						  $val="cantidad||//".$empresa."||//".$centro_utilidad."||//".$bodega."||//".$codigo_producto."||//".$nom_bodega."||//".$datos['cantidad']."||//".$datos['departamento_al_cargar']."||//".$datos['fecha_vencimiento']."||//".$datos['lote']."";                                                                                                                              
						  $html .= "       <select $validarFechaVence $disabled name=\"$val\" class=\"select\">";
						  $html .= "       <option value=\"0\">0</option>";
						  for($cont=1;$cont<=$datos['cantidad'];$cont++){
							$sel='';
							if((array_key_exists($val,$vector_request)) && $vector_request[$val]==$cont){$sel='selected';}
							$html .= "       <option value=\"$cont\" $sel>$cont</option>";
						  }
						  $html .= "       </select>&nbsp;&nbsp;$img";
						  $html .= "       </td>";                  
						  $html .= "       </tr>";
						  $html .= "       <tr class='$estilo'><td colspan=\"3\" id=\"MostrarFechas$codigo_producto\">";
						  if($_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto]){
							$html.= "   <table align=\"center\" width=\"70%\">";
							$html.= "    <tr class=\"modulo_table_title\" align=\"center\">";
							$html.= "      <td align=\"center\" width=\"30%\">FECHA VENCIMIENTO</td>\n";
							$html.= "      <td align=\"center\" width=\"40%\">No. LOTE</td>\n";
							$html.= "      <td align=\"center\">CANTIDAD</td>\n";
							$html.= "      <td align=\"center\" width=\"5%\">&nbsp;</td>\n";
							$html.= "    </tr>";
							$j=0;
							foreach($_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto] as $loteT=>$arreglo){        
							  (list($cantidadLoteT,$fechaVencimientoT)=explode('||//',$arreglo));
							  if($j % 2){$estilo="modulo_list_claro";}else{$estilo="modulo_list_oscuro";   }         
							  $html.= "    <tr class=\"$estilo\">";
							  (list($dia,$mes,$ano)=explode('/',$fechaVencimientoT));
							  $html.= "    <td align=\"center\">".$ano."-".$mes."-".$dia."</td>";
							  $html.= "    <td>$loteT</td>";
							  $html.= "    <td>$cantidadLoteT</td>";
							  $html.= "    <td><a href=\"javascript:CallEliminaFechaVenceForma('$codigo_producto','$loteT','$val','$cantidad')\"><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
							  $html.= "    </tr>";                
							  $j++;
							}
							$html.= "  </table>";
						  }
						  $html .= "       </td></tr>";                  
						  $j++;
						}
					}	
				}	
              }  
            }
          }
        }  
        $motivos=$funciones->MotivosDevolucionIyM();                
        $html .= "<tr>";
        $html .= "<td align=\"right\" colspan=\"2\"><label class=\"label\">MOTIVO DEVOLUCION:&nbsp&nbsp;</label>";
        $html .= "<select size=\"1\" name=\"MotivoDevolucion\" class=\"select\">";
        for($m=0;$m<sizeof($motivos);$m++){
          if($_SESSION['FACTURACION_CUENTAS']['motivosDevolucion'][$var[$i][codigo_producto]]==$motivos[$m][motivo_devolucion_id] || $MotivosDevolucion[$var[$i][codigo_producto]]==$motivos[$m][motivo_devolucion_id]){
              $html .= "<option value = \"".$motivos[$m][motivo_devolucion_id]."\" selected>".$motivos[$m][descripcion]."</option>";
          }else{
              $html .= "<option value = \"".$motivos[$m][motivo_devolucion_id]."\">".$motivos[$m][descripcion]."</option>";
          }
        }
        $html .= "</select>";
        $html .= "</td>";
        $html .= "<td align=\"right\"><input type=\"submit\" name=\"Devolver\" value=\"DEVOLVER\" class=\"input-submit\"></td>";
        $html .= "</tr>";        
        
        $html .= "</table>";
        $html .= "</form>";
        $html .= "<form name=\"forma1\" action=\"$accionSalir\" method=\"post\">";
        $html .= "<table border=\"0\" width=\"70%\" align=\"center\">";
        $html .= "<tr><td align=\"center\"><input type=\"submit\" name=\"Salir\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";         
        $html .= "</table>";
        $html .= "</form>";
      }
      $html .= ThemeCerrarTabla();      
      return $html;   
    }
    
   
    
    
    
    
  }
?>