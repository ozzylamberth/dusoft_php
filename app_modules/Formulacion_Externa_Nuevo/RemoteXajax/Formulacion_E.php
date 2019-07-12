<?php
	/**
	* Archivo Xajax
	* Tiene como responsabilidad hacer el manejo de las funciones
	* que son invocadas por medio de xajax
	*
	* @package IPSOFT-SIIS
	* @version $Revision: 1.3 $
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	
	 /**
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function ValidarPaciente($form)
  {
    $objResponse = new xajaxResponse();
   
    $mensaje = "";
    if($form['TipoDocumento'] == "-1")
      $mensaje = "SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO";
    else if($form['Documento'] == "")
      $mensaje = "SE DEBE INGRESAR EL NUMERO DEL DOCUMENTO";
      else if($form['Responsable'] == '-1')
        $mensaje = "SE DEBE SELECCIONAR EL PLAN";
            
    if($mensaje == "")
    {
	 
        $form['tipo_id_paciente'] = $form['TipoDocumento'];
        $form['paciente_id'] = $form['Documento'];
        $form['plan_id'] = $form['Responsable'];    
        $inp = AutoCarga::factory('InformacionPacientes');
        $datos = $inp->ValidarInformacion($form);
        $sla= AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
        if(is_array($datos))
        {
	          if(empty($datos))
            {
              $html = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
              $objResponse->assign("errorA","innerHTML",$html);
            }
          else
          {
            SessionSetVar("DatosPaciente",$datos);
            $objResponse->call("Continuar");
          }
        }
        else
        {
	  
          if(is_numeric($datos))
          $objResponse->assign("errorA","innerHTML",$inp->ObtenerClasificacionErrores($datos));
        
          if($datos == 3)
          {
		
            /*$datos = $sla->ObtenerDatosAfiliados($form);*/
            $datos = $sla->ObtenerDatosAfiliado_($form);
			
            if($datos === false)
            $objResponse->assign("errorA","innerHTML",$sla->ErrMsg());
            else
            {
		        $validacion = $sla->ObtenerInformacionPlan($form['plan_id']);
            if(!empty($datos) && $datos['plan_atencion'] != $form['plan_id'])
            {
			        if($validacion['sw_afiliados'] == '2' OR $validacion['sw_tipo_plan'] == '1')
              {
			           SessionSetVar("DatosPaciente",$datos);
						$objResponse->call("Continuar");
						
              }
              else
              {
			
                $html  = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
                $html .= "<br>PLAN DE AFILIACION ".$datos['plan_descripcion']." ";
                $objResponse->assign("errorA","innerHTML",$html);
				
              }
            }
            else
            {
               $validacion = $sla->ObtenerDatosPlanAfiliado($form['tipo_id_paciente'],$form['paciente_id']);
			        if(empty($validacion))
              {
			 
                $mensaje = "EL USUARIO  IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id'].", NO ES UN AFILIADO ";
                $objResponse->assign("errorA","innerHTML",$mensaje);
				
              }
			 else
			   {
			   
			     SessionSetVar("DatosPaciente",$datos);
					$objResponse->call("Continuar");
			   
			   }
			}
        }
		}
    }
    SessionSetVar("DatosPaciente",$datos);
    
    }
    else
    {
      $objResponse->assign("error","innerHTML",$mensaje);
    }
    return $objResponse;
  }
	
	/**
  * Funcion que permite buscar diagnosticos 
  * @param array $form Vector con los datos de la forma
  * @return object
  */
	
	  function MostrarDX($form,$tipo_id_paciente,$paciente_id)
		{
			$objResponse = new xajaxResponse();
			$codigo=$form['codigo_dx'];
			$descripcion_dx=$form['descripcion_dx'];
      $sel = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
      $datos=$sel->Busqueda_Avanzada_Diagnosticos($tipo_id_paciente,$paciente_id,$codigo,$descripcion_dx);
      if(empty($datos))
			{
			
			}else
			{
        for($i=0;$i<sizeof($datos);$i++)
				{
              $codigo= $datos[$i]['diagnostico_id'];
              $datos=$sel->Insertar_DX_tipo_Diagnostico_TMP($codigo,$tipo_id_paciente,$paciente_id);
				 
          if($datos==true)
					{
						$objResponse->script('
						xajax_MostrarDX("'.$form.'","'.$tipo_id_paciente.'","'.$paciente_id.'");
						');
					}
         $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";

          $dx_ingres=$sel->Diagnostico_Temporal_S($tipo_id_paciente,$paciente_id);
          if(!empty($dx_ingres))
          {
                $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
                $html .= "								<tr >\n";
                $html .= "									<td width=\"50%\" colspan=\"9\" align='center' style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">DIAGNOSTICOS ASIGNADOS</td>\n";
                $html .= "								</tr>\n";
                $html .= "								<tr >\n";
                $html .= "									<td  colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
                $html .= "									<td width=\"50%\" colspan=\"5\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DIAGNOSTICO</td>\n";
                $html .= "									<td  width=\"5%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OP</td>\n";
                $html .= "								</tr>\n";
                foreach($dx_ingres as $key => $dtl)
                {
                  if( $i % 2){$estilo='modulo_list_claro';}
                  else {$estilo='modulo_list_oscuro';}
                  $html .= "<tr class=\"$estilo\">";
                  $html .= "<td align=\"left\" colspan=\"2\">".$dtl['diagnostico_id']."</td>";
                  $html .= "<td align=\"left\"  colspan=\"5\" width=\"65%\">".$dtl['diagnostico_nombre']."</td>";
                  $html .= "				<td  width=\"5%\" align=\"center\"  >\n";
                  $html .= "					<a href=\"#\" onclick=\"xajax_Eliminar_dx('".$tipo_id_paciente."', '".$paciente_id."','".$dtl['diagnostico_id']."')\" class=\"label_error\"  ><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
                  $html .= "					</a></center>\n";
                  $html .= "			</td>\n";		
                  $html .= "</tr>";
                  $i++;
                }
				       $html .= "							</table>\n";
			
            }
		
			    $objResponse->assign("dx_registrados","innerHTML",$html);
		}
      return $objResponse;
      }
    }
  /*    
  * Funcion que permite Eliminar un diagnostico  ingresado en el temporal
  * @param array $form Vector con los datos de la forma
  * @return object
  */
	
     function Eliminar_dx($tipo_id_paciente,$paciente_id,$codigo)
		{
        $objResponse = new xajaxResponse();
        $sel = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
        $datos=$sel->Eliminar_DX_tm($tipo_id_paciente,$paciente_id,$codigo);
        $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
        $dx_ingres=$sel->Diagnostico_Temporal_S($tipo_id_paciente,$paciente_id);
			  if(!empty($dx_ingres))
		  	{
			
			
				$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
				$html .= "								<tr >\n";
				$html .= "									<td width=\"50%\" colspan=\"9\" align='center' style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">DIAGNOSTICOS ASIGNADOS</td>\n";
				$html .= "								</tr>\n";
				$html .= "								<tr >\n";
				$html .= "									<td  colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
				$html .= "									<td width=\"50%\" colspan=\"5\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DIAGNOSTICO</td>\n";
				$html .= "									<td  width=\"5%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OP</td>\n";
				$html .= "								</tr>\n";
				foreach($dx_ingres as $key => $dtl)
				{
					if( $i % 2){$estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$html .= "<tr class=\"$estilo\">";
					$html .= "<td align=\"left\" colspan=\"2\">".$dtl['diagnostico_id']."</td>";
					$html .= "<td align=\"left\"  colspan=\"5\" width=\"65%\">".$dtl['diagnostico_nombre']."</td>";
          $html .= "				<td  width=\"5%\" align=\"center\"  >\n";
					$html .= "					<a href=\"#\" onclick=\"xajax_Eliminar_dx('".$tipo_id_paciente."', '".$paciente_id."','".$dtl['diagnostico_id']."')\" class=\"label_error\"  ><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
					$html .= "					</a></center>\n";
					$html .= "			</td>\n";		
					$html .= "</tr>";
					$i++;
				}
				$html .= "							</table>\n";
			
     }
		$objResponse->assign("dx_registrados","innerHTML",$html);
    return $objResponse;
		}
	/*    
  * Funcion que permite ingresar un diagnostico al temporal de la formula
  * @param array $form Vector con los datos de la forma
  * @return object
  */
	
	 function IngresarDX($form,$codigo,$tipo_id_paciente,$paciente_id)
    {
        $objResponse = new xajaxResponse();
        $sel = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
        $datos=$sel->Insertar_DX_tipo_Diagnostico_TMP($codigo,$tipo_id_paciente,$paciente_id);
        if($datos==true)
        {
            $objResponse->script('
				    xajax_MostrarDX("'.$form.'","'.$tipo_id_paciente.'","'.$paciente_id.'");

							');
			   }
			$style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
      $dx_ingres=$sel->Diagnostico_Temporal_S($tipo_id_paciente,$paciente_id);
			if(!empty($dx_ingres))
			{
				$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
				$html .= "								<tr >\n";
				$html .= "									<td width=\"50%\" colspan=\"9\" align='center' style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">DIAGNOSTICOS ASIGNADOS</td>\n";
				$html .= "								</tr>\n";
				$html .= "								<tr >\n";
				$html .= "									<td  colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
				$html .= "									<td width=\"50%\" colspan=\"5\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DIAGNOSTICO</td>\n";
				$html .= "									<td  width=\"5%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OP</td>\n";
        $html .= "								</tr>\n";
				foreach($dx_ingres as $key => $dtl)
				{
					if( $i % 2){$estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$html .= "<tr class=\"$estilo\">";
					$html .= "<td align=\"left\" colspan=\"2\">".$dtl['diagnostico_id']."</td>";
					$html .= "<td align=\"left\"  colspan=\"5\" width=\"65%\">".$dtl['diagnostico_nombre']."</td>";
	        $html .= "				<td  width=\"5%\" align=\"center\"  >\n";
					$html .= "					<a href=\"#\" onclick=\"xajax_Eliminar_dx('".$tipo_id_paciente."', '".$paciente_id."','".$dtl['diagnostico_id']."')\" class=\"label_error\"  ><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
					$html .= "					</a></center>\n";
					$html .= "			</td>\n";		
					$html .= "</tr>";
					$i++;
				}
        $html .= "							</table>\n";
			}
      $objResponse->assign("dx_registrados","innerHTML",$html);
			return $objResponse;
		}
	
	/*    
  * Funcion que permite ingresar los productos seleccionados en la formula
  * @param array $form Vector con los datos de la forma
  * @return object
  */
  	
	 function Guardartmp_ambu($codigo,$formula_id,$cantidad,$tiempo,$unidad,$tipo_id_paciente,$paciente_id,$plan_id)
    {
        $objResponse = new xajaxResponse();
				$sel = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
				$datos=$sel->Medicamentos_ambulatorios_Ingreso($formula_id,$codigo,$cantidad,$tiempo,$unidad,$tipo_id_paciente,$paciente_id);
        if($datos==true)
        {
          $url=ModuloGetURL("app", "Formulacion_Externa", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula_id"=>$formula_id));
          $objResponse->script('
          window.location="'.$url.'"
                      ');
        }
			return $objResponse;
		}

     /**
	*Funcion que sirve para buscar los productos a dispensar
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
  
    function BuscarProducto1($FormularioBuscador,$descripcion,$codigo_producto,$total_cantidad,$prinicpio_activo,$formula_id,$bodega_otra)
    {
              $objResponse = new xajaxResponse();

              $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
              $today = date("Y-m-d"); 

              $privilegios=$obje->Usuario_Privilegios_($FormularioBuscador);

              $autorizados=$obje->ConsultaAutorizacion_por_medicamento($formula_id,$codigo_producto);
              $dias_c=$FormularioBuscador['tiempo_entrega'] * 1;
              $dias_dipensados= ModuloGetVar('','','dispensacion_dias_ultima_entrega');
              list($a,$m,$d,) = split("-",$today);

              $fecha_dias = date("Y-m-d",(mktime(0,0,0, $m,($d - $dias_dipensados),$a)));
              $datos_ex=$obje->ConsultarUltimoResg_Dispens_($prinicpio_activo,$FormularioBuscador['paciente_id'],$FormularioBuscador['tipo_id_paciente'],$codigo_producto,$today,$fecha_dias);

          	     if(empty($datos_ex) || $autorizados['sw_autorizado']=='1')
                 {
                      
                                 
                                   $html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
                                   $html .= "                 </div>\n";
                                   $html .= "                                    <div id=\"error\" class='label_error'></div>";
                                   
                                   $cantidad = $obje->Cantidad_ProductoTemporal($formula_id,$prinicpio_activo,$codigo_producto);
                                  
                                   $CantidaEntregar=round($total_cantidad);
                                   $cantidad_=0;
                                           
                                        if($cantidad['codigo_formulado']==$codigo_producto)
                                        {	
                                              $cantidad_=$cantidad['total'];					
                                          
                                        }
                                                  $cantidad_final=$CantidaEntregar-$cantidad_;
                                                  $html .= "                 <form id=\"forma".$formula_id."@".$codigo_producto."\" name=\"".$formula_id."@".$codigo_producto."\" action=\"\" method=\"post\">\n";
                                                  $html .= "                 <table width=\"98%\" align=\"center\" >\n";
                                                  $html .= "                    <tr class=\"formulacion_table_list\"  >\n";
                                                  $html .= "                     <td width=\"45%\">PRODUCTO: ".$codigo_producto." &nbsp; ".$descripcion.". </td> ";
                                                  $html .= "                      <td  width=\"25%\" >CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".$CantidaEntregar."\"  style=\"width:20%\" class=\"input-text\"></td> ";
                                                  $html .= "                       <td  width=\"25%\"  >CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" style=\"width:20%\"  id=\"cantidad_pendiente\" value=\"".($CantidaEntregar-$cantidad_)."\" class=\"input-text\"></td>\n";
                                                  $html .= "                        <input type=\"hidden\" name=\"principio_activo\" id=\"principio_activo\" value=\"".$prinicpio_activo."\">";
                                                  $html .= "                        <input type=\"hidden\" name=\"medicamento_formulado\" id=\"medicamento_formulado\" value=\"".$codigo_producto."\">";
                                                  $html .= "                        <input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
                                                  $html .= "                        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"".$codigo_producto."\">";
                                                  $html .= "                        <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$FormularioBuscador['bodega']."\">";
                                                  $html .= "                        <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$FormularioBuscador['empresa_id']."\">";
                                                  $html .= "                        <input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" value=\"".$FormularioBuscador['centro_utilidad']."\">";
                                                  $html .= "                        <input type=\"hidden\" name=\"tiempo_entrega\" id=\"tiempo_entrega\" value=\"".$FormularioBuscador['tiempo_entrega']."\">";
                                                  $html .= "                        <input type=\"hidden\" name=\"tipo_id_paciente\" id=\"tipo_id_paciente\" value=\"".$FormularioBuscador['tipo_id_paciente']."\">";
                                                  $html .= "                        <input type=\"hidden\" name=\"paciente_id\" id=\"paciente_id\" value=\"".$FormularioBuscador['paciente_id']."\">";

                                                  $html .= "                     </td>";
                                                  $html .= "                    </tr>\n";
                                                  $html .= "                   <tr class=\"modulo_list_claro\">\n";
                                                  $html .= "                      <td colspan=\"3\" align=\"center\">";
                                    
                                              $Existencias=$obje->Consultar_ExistenciasBodegas($prinicpio_activo,$FormularioBuscador,$codigo_producto);     
                                          
                                              if(!empty($Existencias))
                                             {	
                                                    $html .= "                                   <table width=\"100%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
                                                    $html .= "                                       <tr class=\"formulacion_table_list\">\n";
                                                    $html .= "                                       <td width=\"10%\">";
                                                    $html .= "                                            CODIGO  ";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                       <td width=\"35%\">";
                                                    $html .= "                                            PRODUCTO  ";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                       <td width=\"10%\">";
                                                    $html .= "                                            LOTE";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                        <td width=\"10%\">";
                                                    $html .= "                                              FECHA VENC";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                       <td width=\"5%\">";
                                                    $html .= "                                             EXISTENCIA";
                                                    $html .= "                                      </td>";
                                                    $html .= "                                        <td width=\"5%\">";
                                                    $html .= "                                              CANTIDAD";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                        <td width=\"5%\">";
                                                    $html .= "                                              SEL";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                        </tr>\n";
                                                    $i=0;
                                                   foreach($Existencias as $key=>$v)
                                                   {
                                                      $ProductoLote=$obje->Buscar_ProductoLote($formula_id,$codigo_producto,$v['lote'],$v['codigo_producto']);
                                                      if(!empty($ProductoLote))
                                                      {   
                                                          $habilitar = " checked=\"true\" disabled ";
                                                      }
                                                      else
                                                      $habilitar = "  ";
                                                                
                                                      $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$FormularioBuscador['empresa_id']);
                                                      $fecha =$v['fecha_vencimiento'];  //esta es la que viene de la DB
                                                      list($ano,$mes,$dia) = split( '[/.-]', $fecha );
                                                      $fecha = $mes."/".$dia."/".$ano;
                                                                
                                                      $fecha_actual=date("m/d/Y");
                                                      $fecha_compara_actual=date("Y-m-d");
                                                      $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
                                                      $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
                                                      $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
                                                                  
                                                      $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
                                                      $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
                                                      $color =" style=\"width:100%\" ";
                                                      $vencido=0;
                                                      if($int_nodias<$fech_vencmodulo)
                                                      {
                                                          $color = "style=\"width:100%;background:".$colores['PV'].";\"";
                                                          $vencido=0;
                                                      }
                                                          
                                                      if($fecha_dos<=$fecha_uno_act)
                                                      {
                                                          $color = "style=\"width:100%;background:".$colores['VN'].";\"";
                                                          $vencido=1;
                                                      }
                                                      if($vencido==0)
                                                      {  
                                                    
                                                          $html .= "                                        <tr class=\"modulo_list_claro\">";
                                                          $html .= "                                           <td>";
                                                          $html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['codigo_producto']."\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" >";
                                                          $html .= "                                            </td>";
                                                          $html .= "                                           <td>".$v['producto']." ";
                                                          $html .= "                                            </td>";
                                                          $html .= "                                           <td>";
                                                          $html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['lote']."\" name=\"lote".$i."\" id=\"lote".$i."\" >";
                                                          $html .= "                                            </td>";
                                                          $html .= "                                           <td>";
                                                          $fecha_vencimiento=explode("-",$v['fecha_vencimiento']);
                                                          $fechavencimiento=$fecha_vencimiento[2]."-".$fecha_vencimiento[1]."-".$fecha_vencimiento[0];
                                                          $html .= "                                               <input ".$color."  type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$fechavencimiento."\" name=\"fecha_vencimiento".$i."\" id=\"fecha_vencimiento".$i."\" >";
                                                          $html .= "                                              </td>";
                                                          $html .= "                                             <td>";
                                                          $html .= "                                              <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['existencia_actual']."\" name=\"existencia_actual".$i."\" id=\"existencia_actual".$i."\" >";
                                                          $html .= "                                           </td>";
                                                          $html .= "                                              <td>";
                                                          $html .= "                                                <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad".$i."\" id=\"cantidad".$codigo_producto."".$i."\"  value=\"$cantidad_lote\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad".$codigo_producto."".$i."',xGetElementById('cantidad".$codigo_producto."".$i."').value,'".$v['existencia_actual']."','hell$i');\">";
                                                          $html .= "                                             </td>";
                                                          $html .= "                                           <td>";
                                                          if($vencido!=1)
                                                            $html .= "                                                <input ".$habilitar." style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"".$i."\" id=\"".$i."\" value=\"".$i."\" >";
                                                            $html .= "                                               <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\" >";
                                                            $html .= "                                             </td>";
                                                            $html .= "                                       </tr>";
                                                        $i++;
                                                      }
                                                  }
                                                      $html .= "                                       <tr>";
                                                      $html .= "                                              <td colspan=\"4\" align=\"center\">";
                                                      $html .= "  													<div class=\"label_error\" id=\"".$codigo_producto."\"></div>";
                                                      $html .= "                                              </td>";
                                                      $html .= "                                          </tr>";
                                                      $html .= "                                     </table>\n";
                                                      $html .= "                         </td>";
                                                      $html .= "                      </tr>\n";
                                                      $html .= "                                          <tr >\n";
                                                      $html .= "                                         <td class=\"modulo_list_claro\"   colspan=\"4\" align=\"center\">";
                                                      $html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
                                                      $html .= "  												<input type=\"hidden\" name=\"bodega_\" id=\"bodega_\" value=\"".$FormularioBuscador['bodega']."\">";
                                                      $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"GUARDAR TEMPORAL\" onclick=\"xajax_GuardarPT(xajax.getFormValues('forma".$formula_id."@".$codigo_producto."','".$formula_id."'));\">";
                                                      $html .= "                                          </td>";
                                         
                                                    
                                                      if($bodega_ac!=$FormularioBuscador['bodega'])
                                                      {
                                                          $html .= "  												<input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$bodega_ac."\">";
                                                      
                                                       }
                                                   
                                                        $html .= "                                        </tr>\n";
                                         }else
                                          {
                                                  
                                                                    $medicamento_Formulado=$obje->Medicamento_Formulado_tmp($formula_id,$codigo_producto);
                                                                    if(empty($medicamento_Formulado))
                                                                    {
                                                                          $obje->Medicamentos_ambulatorios_Ingreso($formula_id,$codigo_producto,$total_cantidad,$FormularioBuscador);
                                                                    }

                                                                    $html .= "	<table align=\"center\" border=\"0\" width=\"30%\" >\n";
                                                                    $html .= "  <tr class=\"label_error\">\n";
                                                                    $html .= "      <td  class=\"label_error\"  colspan=\"15\" align=\"CENTER\">NO SE ENCONTRARON EXISTENCIAS PARA ESTE PRODUCTO</td>\n";
                                                                    $html .= "  </tr >\n";
                                                                    $html .= "  <tr class=\"label_error\">\n";
                                                                  $html .= "                                         <td class=\"modulo_list_claro\"   colspan=\"1\" align=\"center\">";
                                                                    $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"CERRAR\" onclick=\"xajax_Cancelar_Proceso('".$codigo_producto."','".$formula_id."');\">";
                                                                    $html .= "                                          </td>" ;                                                                   
                                                                    $html .= "  </tr >\n";
                                                                    $html .= "    </table>";

                                                       
                                            }
                                       
                                          $html .= "                 </table>\n";
                                          $html .= "              </form>";
                                          $html .= "                <br>\n";
                   }else
                  {
                                    
                                    
                                    
                            $html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
                            $html .= "                                       <tr class=\"label_error\">\n";
                            $html .= "                                       <td align=\"center\" >";
                            $html .= "                                         <I> ESTE MEDICAMENTO YA FUE DESPACHADO HACE MENOS DE  $dias_dipensados DIA(S)</I> <br>";
                            $html .= "                                          <I>FORMULA NO : </I>".$datos_ex['formula_papel']."\n <BR> ";
                            $html .= "                                          <I>CANTIDAD DESPACHADA : </I> ".round($datos_ex['unidades'])."\n<BR> ";
                            $html .= "                                          <I>USUARIO DESPACHO  :</I>".$datos_ex['nombre']."  \n <BR> ";
                            $html .= "                                      <I>LUGAR DE DESPACHO : </I> ".$datos_ex['razon_social']."  \n ";
                            $html .= "          <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
                            $html .= "                                        </td>";
                            $html .= "                                        </tr>\n";
                            $html .= "<embed src=\"".GetBaseURL()."/Sonido_Alertas/confrontados.mid\"  hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
                            $html .= "                                   </table ><br><br>";

                            if($privilegios['sw_privilegios']=='1')
                             {
                                 /*  $medicamento_Formulado=$obje->Medicamento_Formulado_tmp($formula_id,$codigo_producto);
                                    if(empty($medicamento_Formulado))
                                    {
                                          $obje->Medicamentos_ambulatorios_Ingreso($formula_id,$codigo_producto,$total_cantidad,$FormularioBuscador);
                                    }*/
                             
                                        
                                                  $html .= "	<table align=\"center\" border=\"0\" width=\"30%\" >\n";
                                                  $autorizacion='1';
                                                  $html .= "  <tr class=\"formulacion_table_list\">\n";
                                                  $html .= "      <td   colspan=\"15\" align=\"CENTER\">OBSERVACIONES:</td>\n";
                                                  $html .= "  </tr >\n";
                                                  $html .= "  <tr class=\"modulo_table_list_title\">\n";
                                                  $html .= "      <td   colspan=\"13\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"observaciones\"  id=\"observaciones\"   rows=\"2\"  style=\"width:100%\"></textarea>\n";
                                                  $html .= "       </td>\n";
                                                  $html .= "  </tr >\n";
                                                  $html .= "		<tr  align=\"center\">";
                                                  $html .= "      <td  >";
                                                  $html .= "      <input type=\"submit\" class=\"input-submit\" value=\"AUTORIZAR DESPACHO DEL MEDICAMENTO\" style=\"width:100%\" onclick=\"xajax_Autorizacion_despacho(xajax.getFormValues('registrar_dx'),'".$descripcion."','".$codigo_producto."','".$total_cantidad."','".$prinicpio_activo."','".$formula_id."',document.getElementById('observaciones').value);\" >";
                                                  $html .= "      </td>";
                                                  $html .= "		</tr>\n";
                                                  $html .= "    </table>";
                              }
 
                }
        $objResponse->script("xajax_MostrarProductox('".$formula_id."');");
        $objResponse->assign("Contenido","innerHTML",$html);
        $objResponse->call("MostrarSpan");
        return $objResponse;
  }
  
  /*Funcion que permite eliminar el medicamento temporal seleccionado
  	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
    function Cancelar_Proceso($medicamento,$formula_id)
	  {
        $objResponse = new xajaxResponse();
        
			  $objResponse->call("OcultarSpan");
          $objResponse->script("xajax_MostrarProductox('".$formula_id."');");
        return $objResponse;
      }
  
  
   /**
	*Funcion que sirve para guardar en un temporal los productos a despachar
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
  
    function GuardarPT($Formulario)
	  {
        $objResponse = new xajaxResponse();
		  	 $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
   
        $k=0;
        for($i=0;$i<=$Formulario['registros'];$i++)
        {
            if($Formulario[$i]!="" && $Formulario['cantidad'.$i]!="")
            {
                $cantidad = $obje->Cantidad_ProductoTemporal($Formulario['formula_id'],$Formulario['principio_activo'],$Formulario['medicamento_formulado']);
			  		
                  if(($cantidad['total']+$Formulario['cantidad'.$i])<=$Formulario['cantidad_solicitada'])
                  {
                      if($Formulario['cantidad'.$i] == "")
                      {
                          $objResponse->assign('error_doc',"innerHTML","NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
                      }
                    /*print_r($Formulario);*/
					$Retorno = $obje->GuardarTemporal($Formulario['formula_id'],$Formulario['codigo_producto'.$i],$Formulario['cantidad'.$i],$Formulario['fecha_vencimiento'.$i],$Formulario['lote'.$i],$Formulario,$Formulario['medicamento_formulado']);
           
                    $objResponse->assign("".$Formulario['formula_id']."@".$Formulario['codigo_producto']."","innerHTML",$consulta->mensajeDeError);
                    if($Retorno)
                    $k++;
                }
          }
			
	    }
        $medicamento_Formulado=$obje->Medicamento_Formulado_tmp($Formulario['formula_id'],$Formulario['medicamento_formulado']);
         if(empty($medicamento_Formulado))
        {
           $obje->Medicamentos_ambulatorios_Ingreso($Formulario['formula_id'],$Formulario['medicamento_formulado'],$Formulario['cantidad_solicitada'],$Formulario);
        }else
        {
            if($medicamento_Formulado['0']['cantidad']<$Formulario['cantidad_solicitada'])
            {
          
                 $obje->Update_cantidad_formulacion_tmp($Formulario['formula_id'],$Formulario['medicamento_formulado'],$Formulario['cantidad_solicitada']);
            
            }else
            {
              if($medicamento_Formulado['0']['cantidad']>$Formulario['cantidad_solicitada'])
              {
                 $obje->Update_cantidad_formulacion_tmp($Formulario['formula_id'],$Formulario['medicamento_formulado'],$Formulario['cantidad_solicitada']);
                 $obje->Eliminarformulados_tmp($Formulario['formula_id'],$Formulario['medicamento_formulado']);
                   for($i=0;$i<=$Formulario['registros'];$i++)
                    {
                        if($Formulario[$i]!="" && $Formulario['cantidad'.$i]!="")
                        {
                            $cantidad = $obje->Cantidad_ProductoTemporal($Formulario['formula_id'],$Formulario['principio_activo'],$Formulario['medicamento_formulado']);
                        
                              if(($cantidad['total']+$Formulario['cantidad'.$i])<=$Formulario['cantidad_solicitada'])
                              {
                                  if($Formulario['cantidad'.$i] == "")
                                  {
                                      $objResponse->assign('error_doc',"innerHTML","NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
                                  }
                                $Retorno = $obje->GuardarTemporal($Formulario['formula_id'],$Formulario['codigo_producto'.$i],$Formulario['cantidad'.$i],$Formulario['fecha_vencimiento'.$i],$Formulario['lote'.$i],$Formulario,$Formulario['medicamento_formulado']);
                       
                                $objResponse->assign("".$Formulario['formula_id']."@".$Formulario['codigo_producto']."","innerHTML",$consulta->mensajeDeError);
                                if($Retorno)
                                $k++;
                            }
                      }
                  
                  }
              
              }
              
            }
         }
      
          $objResponse->script("xajax_MostrarProductox('".$Formulario['formula_id']."');");
     $objResponse->call("OcultarSpan");
    return $objResponse;
  }
  /**
	*Funcion que sirve para mostrar los productos temporales 
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
  	function MostrarProductox($formula_id)
		{
        $objResponse = new xajaxResponse();
        $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
       	$empresa = SessionGetVar("DatosEmpresaAF");  
        $farmacia=$empresa['empresa_id'];
     
        $datos=$obje->Medicamentos_Formulados_tmp_t($formula_id);
        $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
        $r_Borrar='1';
		    if(!empty($datos))
        { 

              $html .= "			<fieldset class=\"fieldset\">\n";
              $html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS FORMULADOS</legend>\n";
              $cantidad_=0;
             foreach($datos as $key=>$detalle)        
             {
                    
                                   $cantidad = $obje->Cantidad_ProductoTemporal($formula_id,$prinicpio_activo,$detalle['codigo_producto']);
                                  
                                   $cantidad_=$cantidad['total'];					

                                  $html .= "                 <table width=\"95%\" align=\"center\">\n";
                                  $html .= "                    <tr class=\"formulacion_table_list\"  >\n";
                                  $html .= "                     <td  width=\"45%\">PRODUCTO: ".$detalle['codigo_producto']." &nbsp; ".$detalle['descripcion_prod'].". </td> ";
                                  $html .= "                      <td   width=\"25%\" >CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".round($detalle['cantidad'])."\"  style=\"width:20%\" class=\"input-text\"></td> ";
                                  $html .= "                       <td     width=\"25%\"  >CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" style=\"width:20%\"  id=\"cantidad_pendiente\" value=\"".(round($detalle['cantidad']) -$cantidad_)."\" class=\"input-text\"></td>\n";
                                  $html .= "				<td  class=\"label_error\" >\n";
                                  $html .= "					<a href=\"#\" onclick=\"xajax_Eliminar_codigo_prodcto_d('".$formula_id."','".$v['codigo_producto']."','".$v['esm_dispen_tmp_id']."','".$detalle['codigo_producto']."','".$r_Borrar."')\"  ><img src=\"".GetThemePath()."/images/no.png\" border='0' >\n";
                                  $html .= "					</a>\n";
                                   $html .= "			</td>\n";		
                                   
                                   if($detalle['sw_marcado']=='1')
                                   {
                                                $html .= "				<td   >\n";
                                                $html .= "				MARC\n";
                                                $html .= "			</td>\n";		
                                    }
                                    $html .= "                    </tr>\n";

                                          $html .= "                    </tr>\n";
                                          $cantidad = $obje->Buscar_Productos_despacho_tmp($formula_id,$detalle['codigo_producto']);
                                          if(!empty($cantidad))
                                          { 
                                                    $html .= "                   <tr>\n";
                                                    $html .= "                      <td colspan=\"3\" align=\"center\">";
                                                    $html .= "                                   <table width=\"85%\" align=\"center\"  $style >";
                                                    $html .= "                                       <tr class=\"formulacion_table_list\">\n";
                                                    $html .= "                                       <td width=\"10%\">";
                                                    $html .= "                                            CODIGO  ";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                       <td width=\"45%\">";
                                                    $html .= "                                            PRODUCTO  ";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                       <td width=\"10%\">";
                                                    $html .= "                                            LOTE";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                        <td width=\"8%\">";
                                                    $html .= "                                              FECHA VENC";
                                                    $html .= "                                        </td>";
                                                    $html .= "                                        <td width=\"5%\">";
                                                    $html .= "                                              CANTIDAD";
                                                    $html .= "                                        </td>";
                                                    $html .= "                      <td align=\"center\" width=\"5%\">\n";
                                                    $html .= "                         <a title='ELIMINAR REGISTRO'>X<a>";
                                                    $html .= "                       </td>\n";
                                                    $html .= "                                        </tr>\n";
                                                
                                                    foreach($cantidad as $ll=>$v)
                                                    {
                                                                $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);

                                                                $fecha =$v['fecha_vencimiento'];  //esta es la que viene de la DB
                                                                list($ano,$mes,$dia) = split( '[/.-]', $fecha );
                                                                $fecha = $mes."/".$dia."/".$ano;
                                                                $fecha_actual=date("m/d/Y");
                                                                $fecha_compara_actual=date("Y-m-d");
                                                                $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
                                                                $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
                                                                $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');

                                                                $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
                                                                $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
                                                                $color ="";
                                                                if($int_nodias<$fech_vencmodulo)
                                                                {
                                                                    $color = "style=\"background:".$colores['PV']."\"";
                                                                }

                                                                if($fecha_dos<=$fecha_uno_act)
                                                                {
                                                                    $color = "style=\"background:".$colores['VN']."\"";
                                                                }
              
                                                            
                                                                  $html .= "                                        <tr  >";
                                                                  $html .= "                                           <td   class=\"label\">";
                                                                  $html .= "                                             ".$v['codigo_producto']."";
                                                                  $html .= "                                            </td>";
                                                                  $html .= "                                           <td  class=\"label\" >".$v['descripcion_prod']." ";
                                                                  $html .= "                                            </td>";
                                                                  $html .= "                                           <td  class=\"label\" >";
                                                                  $html .= "                                            ".$v['lote']." ";
                                                                  $html .= "                                            </td>";
                                                                  $html .= "                                           <td class=\"label\" ".$color." > ";
                                                               
                                                                  $html .= "                                            ".$v['fecha_vencimiento']."";
                                                                  $html .= "                                              </td>";
                                                                  $html .= "                                              <td  class=\"label\" >";
                                                                  $html .= "                                             ".$v['cantidad_despachada']."   ";
                                                                  $html .= "                                             </td>"; 
                                                                  $html .= "				<td   >\n";
                                                                  $html .= "					<a href=\"#\" onclick=\"xajax_Eliminar_codigo_prodcto_d('".$formula_id."','".$v['codigo_producto']."','".$v['esm_dispen_tmp_id']."','".$detalle['codigo_producto']."')\" class=\"label_error\"  ><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
                                                                  $html .= "					</a>\n";
                                                                  $html .= "			</td>\n";		
                                                                  
                                                                  $html .= "                                       </tr>";
                                                    }     
                                                    $html .= "                                     </table>\n";
                                           }                                              
                         
                              $html .= "                                             </td>";
                              $html .= "                    </tr>\n";
                              $html .= "    </table>";  
                                      
                       }
                $html .= "			</fieldset>\n";   
                $html .= "                 <table width=\"75%\" align=\"center\" >\n";
                $html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
                $html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
                $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"FINALIZAR FORMULACION Y DISPENSAR \" onclick=\"xajax_Cambiarvetana('".$formula_id."');\">";
                $html .= "                                          </td>";
                $html .= "                    </table>\n";
         }
      		$objResponse->assign("productostmp","innerHTML",$html);
					return $objResponse;
		}
    /**
	*Funcion que sirve para borrar un producto seleccionado para despachar  
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
    
    function Eliminar_codigo_prodcto_d($formula_id,$codigo_producto,$esm_dispen_tmp_id,$medicamento_formulado,$r_Borrar)
		{
        $objResponse = new xajaxResponse();
		     $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
   
         $veces=$obje->Buscar_veces_producto_formulado($formula_id,$medicamento_formulado);
    
        if($veces['cantidad']=='1' || $r_Borrar=='1')
        {
            $obje->Eliminar_Medicamento_tmp($medicamento_formulado,$formula_id);
        }
      	/*$vector=$obje->EliminarProducto_tmp($formula_id,$codigo_producto,$esm_dispen_tmp_id);*/
      	$vector=$obje->EliminarProducto_tmp($formula_id,$medicamento_formulado,$esm_dispen_tmp_id);
				if($vector)
        {
			    $objResponse->script("xajax_MostrarProductox('".$formula_id."');");
	      }
           $objResponse->script("xajax_MostrarProductox('".$formula_id."');");
			return $objResponse;

		}
      /**
	*Funcion que sirve para visualizar los medicamentos que van hacer despachados  
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
    function Cambiarvetana($formula_id,$todopendiente)
		{
			$objResponse = new xajaxResponse();
    	$url=ModuloGetURL("app", "Formulacion_Externa", "controller", "Preparar_Documento_Dispensacion", array("formula_id"=>$formula_id,"todopendiente"=>$todopendiente));
			$objResponse->script('
						 window.location="'.$url.'";
							');
		    return $objResponse;
		}
    /**
	*Funcion que permite generar la entrega de los medicamentos 
  * @return Object $objResponse objeto de respuesta al formulario  	
	*/
    function PacienteReclama($observacion,$formula_id,$observacion2,$todo_pendiente)
    {
      
          $objResponse = new xajaxResponse();
					$url=ModuloGetURL("app", "Formulacion_Externa", "controller", "GenerarEntregaMedicamentos",array("observacion"=>$observacion."-".$observacion2,"formula_id"=>$formula_id,"todo_pendiente"=>$todo_pendiente));
          $objResponse->script('
					 window.location="'.$url.'";');
          return $objResponse;
    }
   
    /**
    *Funcion que sirve para buscar los medicamentos que se han quedado pendientes  
    * @return Object $objResponse objeto de respuesta al formulario  	
	*/
    function BuscarProducto2($FormularioBuscador,$formula_id,$bodega_otra)
    {
            $objResponse = new xajaxResponse();
            $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
            $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";

            $busqueda=$obje->Consultar_Medicamentos_Detalle_P($FormularioBuscador,$formula_id);

            $cantidad_entrega=$busqueda[0]['cantidad'];
            $empresa = SessionGetVar("DatosEmpresaAF");
            $farmacia=$empresa['empresa_id'];
            $centrou=$empresa['centro_utilidad'];
            $html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $html .= "                 </div>\n";
            $html .= "                                    <div id=\"error\" class='label_error'></div>";

                foreach($busqueda as $k => $valor)
                {
                        $cantidad = $obje->Cantidad_ProductoTemporalP($formula_id,$valor['cod_principio_activo'],$valor['codigo_producto']);
                        $cantidad_entrega=round($valor['cantidad']);
                        $cantidad_=0;
                    if($cantidad['codigo_formulado']==$valor['codigo_producto'])
                    {	
                          $cantidad_=$cantidad['total'];					
                    }
                    $cantidad_final=$cantidad_entrega-$cantidad_;	
                    $html .= "                 <form id=\"forma".$formula_id."@".$valor['codigo_producto']."\" name=\"".$formula_id."@".$valor['codigo_producto']."\" action=\"\" method=\"post\">\n";
                    $html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $html .= "                    <tr class=\"formulacion_table_list\">\n";
                    $html .= "                     <td width=\"50%\">PRODUCTO: ".$valor['codigo_producto']." &nbsp; ".$valor['descripcion_prod'].". </td>
                    <td>CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".$cantidad_entrega."\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".($cantidad_entrega-$cantidad_)."\" class=\"input-text\"></td>\n";
                    $html .= "                        <input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
                    $html .= "                        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"".$valor['codigo_producto']."\">";
                    $html .= "                        <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$FormularioBuscador['empresa_id']."\">";
                    $html .= "                        <input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" value=\"".$FormularioBuscador['centro_utilidad']."\">";
                    $html .= "                        <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$FormularioBuscador['bodega']."\">";
                    $html .= "                        <input type=\"hidden\" name=\"principio_activo\" id=\"principio_activo\" value=\"".$valor['cod_principio_activo']."\">";
                    $html .= "                        <input type=\"hidden\" name=\"medicamento_formulado\" id=\"medicamento_formulado\" value=\"".$valor['codigo_producto']."\">";
                    $html .= "                     </td>";
                    $html .= "                    </tr>\n";
                    $html .= "                   <tr>\n";
                    $html .= "                      <td colspan=\"3\" align=\"center\">";
                    if($cantidad_final!=0)
                    {
                          $Existencias=$obje->Consultar_ExistenciasBodegas($valor['cod_principio_activo'],$FormularioBuscador,$valor['codigo_producto'],'1');      
                          if(!empty($Existencias))
                          {
                                $html .= "                                   <table width=\"85%\" align=\"center\"  $style>";
                                $html .= "                                       <tr class=\"formulacion_table_list\">\n";
                                $html .= "                                       <td width=\"10%\">";
                                $html .= "                                            CODIGO  ";
                                $html .= "                                        </td>";
                                $html .= "                                       <td width=\"45%\">";
                                $html .= "                                            PRODUCTO  ";
                                $html .= "                                        </td>";
                                $html .= "                                       <td width=\"10%\">";
                                $html .= "                                            LOTE";
                                $html .= "                                        </td>";
                                $html .= "                                        <td width=\"15%\">";
                                $html .= "                                              FECHA VENCIMIENTO";
                                $html .= "                                        </td>";
                                $html .= "                                       <td width=\"5%\">";
                                $html .= "                                             EXISTENCIA";
                                $html .= "                                      </td>";
                                $html .= "                                        <td width=\"5%\">";
                                $html .= "                                              CANTIDAD";
                                $html .= "                                        </td>";
                                $html .= "                                        <td width=\"5%\">";
                                $html .= "                                              SEL";
                                $html .= "                                        </td>";
                                $html .= "                                        </tr>\n";
                                $i=0;
                  
                              foreach($Existencias as $key=>$v)
                                {
                                    $ProductoLote=$obje->Buscar_ProductoLoteP($formula_id,$valor['codigo_producto'],$v['lote'],$v['codigo_producto']);
                                    if(!empty($ProductoLote))
                                    {
                                        $habilitar = " checked=\"true\" disabled ";
                                    }
                                      else
                                      $habilitar = "  ";

                                    $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);
                                    $fecha =$v['fecha_vencimiento'];  //esta es la que viene de la DB
                                    list($ano,$mes,$dia) = split( '[/.-]', $fecha );
                                    $fecha = $mes."/".$dia."/".$ano;
                                    $fecha_actual=date("m/d/Y");
                                    $fecha_compara_actual=date("Y-m-d");
                                    $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
                                    $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
                                    $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
                                    $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
                                    $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
                                    $color =" style=\"width:100%\" ";
                                    $vencido=0;
                                    if($int_nodias<$fech_vencmodulo)
                                    {
                                        $color = "style=\"width:100%;background:".$colores['PV'].";\"";
                                        $vencido=0;
                                    }
                      
                                    if($fecha_dos<=$fecha_uno_act)
                                    {
                                        $color = "style=\"width:100%;background:".$colores['VN'].";\"";
                                        $vencido=1;
                                    }
                                  if($vencido==0)
                                  {  					
                                        $html .= "                                        <tr class=\"modulo_list_claro\">";
                                        $html .= "                                           <td>";
                                        $html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['codigo_producto']."\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" >";
                                        $html .= "                                            </td>";
                                        $html .= "                                           <td>".$v['producto']." ";
                                        $html .= "                                            </td>";
                                        $html .= "                                           <td>";
                                        $html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['lote']."\" name=\"lote".$i."\" id=\"lote".$i."\" >";
                                        $html .= "                                            </td>";
                                        $html .= "                                           <td>";
                                        $fecha_vencimiento=explode("-",$v['fecha_vencimiento']);
                                        $fechavencimiento=$fecha_vencimiento[2]."-".$fecha_vencimiento[1]."-".$fecha_vencimiento[0];
                                        $html .= "                                               <input ".$color."  type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$fechavencimiento."\" name=\"fecha_vencimiento".$i."\" id=\"fecha_vencimiento".$i."\" >";
                                        $html .= "                                              </td>";
                                        $html .= "                                             <td>";
                                        $html .= "                                              <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['existencia_actual']."\" name=\"existencia_actual".$i."\" id=\"existencia_actual".$i."\" >";
                                        $html .= "                                           </td>";
                                        $html .= "                                              <td>";
                                        $html .= "                                                <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad".$i."\" id=\"cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."\"   value=\"$cantidad_lote\"  onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."',xGetElementById('cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."').value,'".$v['existencia_actual']."','hell$i');\">";
                                        $html .= "                                             </td>";
                                        $html .= "                                           <td>";
                                        if($vencido!=1)
                                        $html .= "                                                <input ".$habilitar." style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"".$i."\" id=\"".$i."\" value=\"".$i."\" >";
                                        $html .= "                                               <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\" >";
                                        $html .= "                                             </td>";
                                        $html .= "                                       </tr>";
                                        $i++;
                                      }
                                  }
                                $html .= "                                       <tr>";
                                $html .= "                                              <td colspan=\"4\" align=\"center\">";
                                $html .= "  													<div class=\"label_error\" id=\"".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."\"></div>";
                                $html .= "                                              </td>";
                                $html .= "                                          </tr>";
                                $html .= "                                     </table>\n";
                                $html .= "                         </td>";
                                $html .= "                      </tr>\n";
                                $html .= "                                          <tr >\n";
                                $html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
                                $html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
                                //	$html .= "  												<input type=\"hidden\" name=\"bodegas_doc_id\" id=\"bodegas_doc_id\" value=\"".$FormularioBuscador['bodegas_doc_id']."\">";
                                $html .= "  												<input type=\"hidden\" name=\"bodega_\" id=\"bodega_\" value=\"".$FormularioBuscador['bodega']."\">";

                                $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"GUARDAR TEMPORAL\" onclick=\"xajax_GuardarPTP(xajax.getFormValues('forma".$formula_id."@".$valor['codigo_producto']."'),'".$formula_id."','".$valor['f_rango']."');\">";
                                $html .= "                                          </td>";
                                $html .= "                                        </tr>\n";
                                }else
                                {
              
                                   
                                          $html .= "	<table align=\"center\" border=\"0\" width=\"30%\" >\n";
                                        
                                          $html .= "  <tr class=\"label_error\">\n";
                                          $html .= "      <td  class=\"label_error\"  colspan=\"15\" align=\"CENTER\">NO SE ENCONTRARON EXISTENCIAS PARA ESTE PRODUCTO</td>\n";
                                          $html .= "  </tr >\n";
                                          $html .= "    </table>";
                                
                            }
              }
              $html .= "                 </table>\n";
              $html .= "              </form>";
              $html .= "                <br>\n";
     }
        $objResponse->assign("BuscadorProductos","innerHTML",$html);
        return $objResponse;
  }
  
     /**
	*Funcion que sirve para guardar en un temporal los productos a despachar que han estado pendientes
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
  
   function GuardarPTP($Formulario,$formula_id,$f_rango)
	 {
	    $objResponse = new xajaxResponse();
        $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
      $empresa = SessionGetVar("DatosEmpresaAF");
   
	    $k=0;
	    for($i=0;$i<=$Formulario['registros'];$i++)
      {
          if($Formulario[$i]!="" && $Formulario['cantidad'.$i]!="")
          {
				        $cantidad = $obje->Cantidad_ProductoTemporalP($Formulario['formula_id'],$Formulario['principio_activo'],$Formulario['medicamento_formulado']);
                    if(($cantidad['total']+$Formulario['cantidad'.$i])<=$Formulario['cantidad_solicitada'])
                    {
                        if($Formulario['cantidad'.$i] == "")
                        {
                            $objResponse->assign('error_doc',"innerHTML","NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
                          }
                           $Retorno = $obje->GuardarTemporalP($Formulario['formula_id'],$Formulario['codigo_producto'.$i],$Formulario['cantidad'.$i],$Formulario['fecha_vencimiento'.$i],$Formulario['lote'.$i],$Formulario,$Formulario['medicamento_formulado'],'0');
                            $objResponse->assign("".$Formulario['formula_id']."@".$Formulario['codigo_producto']."","innerHTML",$consulta->mensajeDeError);
                            if($Retorno)
                            $k++;
                     }
          }
			
	    }
	    
		if($k>0)
		{
			$objResponse->script(" Recargar_informacion('".$empresa['bodega']."');");
			$objResponse->script("xajax_BuscarProducto2(xajax.getFormValues('buscador'),'".$Formulario['formula_id']."',1);");
			$objResponse->script("xajax_MostrarProductox2('".$Formulario['formula_id']."');");
		}
	    if($Retorno === false)
	    {
	      $objResponse->assign('error_doc','innerHTML',$obje->mensajeDeError);
	    }
      return $objResponse;
    }
    
        /**
    *Funcion que sirve para mostrar los productos temporales 
    * @return Object $objResponse objeto de respuesta al formulario  	
    */
		function MostrarProductox2($formula_id)
		{
        $objResponse = new xajaxResponse();
        $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
        
        $empresa = SessionGetVar("DatosEmpresaAF");
        $farmacia=$empresa['empresa_id'];

        $vector=$obje->Buscar_producto_tmp_p($formula_id);
       if(!empty($vector))
        {
              $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
              $html .= "                    <tr  class=\"formulacion_table_list\" >\n";
              $html .= "                       <td align=\"center\" width=\"12%\">\n";
              $html .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
              $html .= "                      </td>\n";
              $html .= "                       <td align=\"center\" width=\"45%\">\n";
              $html .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
              $html .= "                      </td>\n";
              $html .= "                       <td width=\"10%\">LOTE</td>\n";    
              $html .= "                      <td width=\"15%\">FECHA VENCIMIENTO</td>\n";    
              $html .= "                       <td align=\"center\" width=\"12%\">\n";
              $html .= "                        CANTIDAD";
              $html .= "                      </td>\n";
              $html .= "                      <td align=\"center\" width=\"5%\">\n";
              $html .= "                         <a title='ELIMINAR REGISTRO'>X<a>";
              $html .= "                       </td>\n";
              $html .= "                    </tr>\n";
              foreach($vector as $key=>$detalle)
              {
                      $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);
                      $fecha =$detalle['fecha_vencimiento'];  //esta es la que viene de la DB
                      list($ano,$mes,$dia) = split( '[/.-]', $fecha );
                      $fecha = $mes."/".$dia."/".$ano;
                      $fecha_actual=date("m/d/Y");
                      $fecha_compara_actual=date("Y-m-d");
                      $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
                      $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
                      $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
                      $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
                      $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
                      $color ="";
                      if($int_nodias<$fech_vencmodulo)
                      {
                        $color = "style=\"background:".$colores['PV']."\"";
                      }

                      if($fecha_dos<=$fecha_uno_act)
                      {
                          $color = "style=\"background:".$colores['VN']."\"";
                      }
									
                      $html .= "                     <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                      $html .= "                       <td align=\"left\" class=\"label_mark\">\n";
                      $html .= "                        ".$detalle['codigo_producto'];
                      $html .= "                       </td>\n";
                      $html .= "                       <td align=\"left\" class=\"label_mark\">\n";
                      $html .= "                         ".$detalle['descripcion_prod'];
                      $html .= "                      </td>\n";
                      $html .= "                       <td class=\"label_mark\">".$detalle['lote']."</td>\n";
                      $html .= "                      <td align=\"center\" class=\"label_mark\" ".$color.">".$detalle['fecha_vencimiento']."</td>\n";
                      $html .= "                      <td align=\"right\" class=\"label_mark\">\n";
                      $html .= "                        ".$detalle['cantidad_despachada'];
                      $html .= "                      </td>\n";
                      $html .= "				<td  width=\"5%\" align=\"center\"  >\n";
                      $html .= "					<a href=\"#\" onclick=\"xajax_Eliminar_codigo_prodcto_d2('".$formula_id."','".$detalle['codigo_producto']."','".$detalle['esm_dispen_tmp_id']."')\" class=\"label_error\"  ><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
                      $html .= "					</a></center>\n";
                      $html .= "			</td>\n";		
                      $html .= "                   </tr>\n";
              }
					$html .= "                    </table><BR>\n";
					$html .= "                 <table width=\"75%\" align=\"center\" >\n";
					$html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
					$html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
          $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"REALIZAR ENTREGA\" onclick=\"xajax_Cambiarvetana2('".$formula_id."');\">";
					$html .= "                                          </td>";
					$html .= "                    </table>\n";
				}
				else
				{
            $html .= "                  <table width=\"80%\" align=\"center\">\n";
            $html .= "                   <tr>\n";
            $html .= "                   <td align=\"center\">\n";
            $html .= "                      <label class='label_error'> ESTE DOCUMENTO NO TIENE PRODUCTOS ASIGNADOS</label>";
            $html .= "                   </td>\n";
            $html .= "                  </tr>\n";
            $html .= "                  </table>\n";
        }
				$objResponse->assign("productostmp","innerHTML",$html);
				return $objResponse;
		}
    
   /**
	*Funcion que sirve para borrar un producto seleccionado para despachar  
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
  function Eliminar_codigo_prodcto_d2($formula_id,$codigo_producto,$esm_dispen_tmp_id)
	{
      $objResponse = new xajaxResponse();
      $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
      $empresa = SessionGetVar("DatosEmpresaAF");
      $farmacia=$empresa['empresa_id'];
			$vector=$obje->EliminarProducto_tmpP($formula_id,$codigo_producto,$esm_dispen_tmp_id);
		   if($vector)
			{
          $objResponse->script("xajax_MostrarProductox2('".$formula_id."');");
	     }
			return $objResponse;

		}
    /**
    *Funcion que sirve para visualizar los medicamentos que van hacer despachados que han quedado pendientes  
    * @return Object $objResponse objeto de respuesta al formulario  	
	*/
  	function Cambiarvetana2($formula_id)
		{
        $objResponse = new xajaxResponse();
          
        $url=ModuloGetURL("app", "Formulacion_Externa", "controller", "Preparar_Documento_Dispensacion_Pendientes", array("formula_id"=>$formula_id));
        $objResponse->script('
						 window.location="'.$url.'";
							');
		    return $objResponse;
        
		}
      /**
	*Funcion que permite generar la entrega de los medicamentos 
  * @return Object $objResponse objeto de respuesta al formulario  	
	*/
    function PacienteReclama_P($observacion,$formula_id,$observacion2,$todo_pendiente)
    {
      
          $objResponse = new xajaxResponse();
					$url=ModuloGetURL("app", "Formulacion_Externa", "controller", "EntregaMedicamentos_Pendientes",array("observacion"=>$observacion."-".$observacion2,"formula_id"=>$formula_id,"todo_pendiente"=>$todo_pendiente));
          $objResponse->script('
					 window.location="'.$url.'";
					');
          return $objResponse;
    }
     /**
	*Funcion que sirve para autorizar el despacho de un medicamento de la formula  
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
	function Autorizacion_despacho($Formulario,$descripcion,$codigo_producto,$total_cantidad,$prinicpio_activo,$formula_id,$observacion)
	{
			$objResponse = new xajaxResponse();
      $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
	  $medicamento_Formulado=$obje->Medicamento_Formulado_tmp($formula_id,$codigo_producto);
                                    if(empty($medicamento_Formulado))
                                    {
                                          $obje->Medicamentos_ambulatorios_Ingreso($formula_id,$codigo_producto,$total_cantidad,$Formulario);
                                    }
  		$vector=$obje->UpdateAutorizacion_por_medicamento($formula_id,$observacion,$codigo_producto);
      $autorizado='1';
		    if($vector==true)
        {
        
            $objResponse->script("xajax_BuscarProducto1(xajax.getFormValues('registrar_dx'),'".$descripcion."','".$codigo_producto."','".$total_cantidad."','".$prinicpio_activo."','".$formula_id."');");
	      }
			return $objResponse;

		}
      /**
	*Funcion que sirve para marcar un producto 
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
      function Marcar_Producto($FormularioBuscador,$descripcion,$codigo_producto,$total_cantidad,$prinicpio_activo,$formula_id,$bodega_otra)
    {
	     $objResponse = new xajaxResponse();
        $obje =AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
  
        $medicamento_Formulado=$obje->Medicamento_Formulado_tmp($formula_id,$codigo_producto);
        if(empty($medicamento_Formulado))
        {
                $obje->Medicamentos_ambulatorios_Ingreso($formula_id,$codigo_producto,$total_cantidad,$FormularioBuscador);
                $obje->Update_Marcar($codigo_producto,$formula_id);
                
        }
           
  
           $url=ModuloGetURL("app", "Formulacion_Externa", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$FormularioBuscador['tipo_id_paciente'],"paciente_id"=>$FormularioBuscador['paciente_id'],"plan_id"=>$FormularioBuscador['plan_id'],"formula_id"=>$formula_id));
          $objResponse->script('
					 window.location="'.$url.'";
					');
	   
            return $objResponse;
    }
?>