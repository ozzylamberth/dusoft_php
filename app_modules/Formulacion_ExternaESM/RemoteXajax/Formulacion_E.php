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
  *funcion que permite validar la informacion del paciente
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

        $sla= AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');

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
		
          $datos = $sla->ObtenerDatosAfiliados($form);
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
			
			  // $V_EMS_Paciente = $sla->Validar_Paciente_ESM($form);
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
			   
			  /*$V_EMS_Paciente = $sla->Validar_Paciente_ESM($form);
			   if(empty($V_EMS_Paciente))
			   {
			
			        $mensaje = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id'].", NO ESTA AFILIADO A UN ESTABLECIMIENTO DE SANIDAD MILITAR";
                   $objResponse->assign("errorA","innerHTML",$mensaje);
          
			   }*/
			  
			  
              /*else
              {
                SessionSetVar("DatosPaciente",$datos);
                $objResponse->call("Continuar");
              }*/
            }
			
			
			}
		
     
			
          }
        }
      
	    SessionSetVar("DatosPaciente",$datos);
      //  $objResponse->call("Continuar");
	  
    }
    else
    {
      $objResponse->assign("error","innerHTML",$mensaje);
    }
    return $objResponse;
  }
     /**
    *funcion que permite eliminar el diagnostico de la formula temporal
    * @param array $form Vector con los datos de la forma
    * @return object
    */
     function Eliminar_dx($tipo_id_paciente,$paciente_id,$codigo)
		{
        $objResponse = new xajaxResponse();
        $sel = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
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

    /**
    *funcion que permite ingresar el diagnostico de la formula temporal
    * @param array $form Vector con los datos de la forma
    * @return object
    */
    function IngresarDX($form,$codigo,$tipo_id_paciente,$paciente_id)
		{
        $objResponse = new xajaxResponse();
        $sel = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
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
	/**
  * Funcion que permite mostrar los profesionales
  * @param array  $form vector con toda la forma
  * @return Object $objResponse objeto de respuesta al formulario  
  */
		function MostrarProfesionales($form,$opcion)
		{
          $objResponse = new xajaxResponse();
          list($esm_tipo_id_tercero,$esm_tercero_id) = explode("@",$form['esm']);
          
          $sel = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
          $profesionales = $sel->Profesionales_Esm($esm_tipo_id_tercero,$esm_tercero_id);
          $html = " <option value=\"-1\">---SELECCIONAR---</option> \n";
          if(!empty($profesionales))
          {
              foreach($profesionales as $key=>$valor)
              $html .= "  <option value=\"".$valor['tipo_id_tercero']."@".$valor['tercero_id']."\" >".$valor['tipo_id_tercero']."  ".$valor['tercero_id']."   ".$valor['nombre']." </option>\n";
          }
              $objResponse->assign("profesional","innerHTML",$html);
              if($opcion=='1')
              {
                $html = " <option value=\"-1\">---SELECCIONAR---</option> \n";
                if(!empty($profesionales))
                {
                foreach($profesionales as $key=>$valor)
                $html .= "  <option value=\"".$valor['tipo_id_tercero']."@".$valor['tercero_id']."\" >".$valor['tipo_id_tercero']."  ".$valor['tercero_id']." ".$valor['nombre']." </option>\n";
                }
                $objResponse->assign("profesional_aut","innerHTML",$html);
              }
          return $objResponse;
      }
      /**
    * Funcion que permite mostrar los profesionales asociados a una IPS
    * @param array  $form vector con toda la forma
    * @return Object $objResponse objeto de respuesta al formulario  
    */
     function Mostrar_profesion_IPS($form,$opcion)
		{
        $objResponse = new xajaxResponse();
        list($tipo_id_ips,$id_ips) = explode("@",$form['ips']);
        $sel = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');

        $profesionales = $sel->Profesionales_ips($tipo_id_ips,$id_ips);
        $html = " <option value=\"-1\">---SELECCIONAR---</option> \n";
        if(!empty($profesionales))
        {
        foreach($profesionales as $key=>$valor)
        $html .= "  <option value=\"".$valor['tipo_id_tercero']."@".$valor['tercero_id']."\" >".$valor['nombre']." </option>\n";
        }
        $objResponse->assign("profesional_ips","innerHTML",$html);
        if($opcion=='1')
        {
          $Ubicacion = $sel->Ubicacion_ips($tipo_id_ips,$id_ips);
          $objResponse->assign("ubicacion_ips","innerHTML",$Ubicacion['ubicacion']);
			    $IPS_ESM = $sel->IPS_ESM($tipo_id_ips,$id_ips);
			    $html_ips = " <option value=\"-1\">---SELECCIONAR---</option> \n";
		     	if(!empty($IPS_ESM))
			    {
			       foreach($IPS_ESM as $key=>$esm)
				      $html_ips .= "  <option value=\"".$esm['tipo_id_tercero_esm']."@".$esm['tercero_id_esm']."\" > ".$esm['nombre_tercero']." </option>\n";
			    }
			   $objResponse->assign("esm_ips","innerHTML",$html_ips);
		
        }
			return $objResponse;
		}
     /**
    * Funcion que permite mostrar los profesionales asociados a una IPS
    * @param array  $form vector con toda la forma
    * @return Object $objResponse objeto de respuesta al formulario  
    */
    function Mostrar_profesion_IPS_ESM($form,$opcion)
		{
        $objResponse = new xajaxResponse();
        list($esm_tipo_id_tercero,$esm_tercero_id) = explode("@",$form['esm_ips']);
				$sel = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $profesionales = $sel->Profesionales_Esm($esm_tipo_id_tercero,$esm_tercero_id);
        $html = " <option value=\"-1\">---SELECCIONAR---</option> \n";
        if(!empty($profesionales))
        {
          foreach($profesionales as $key=>$valor)
          $html .= "  <option value=\"".$valor['tipo_id_tercero']."@".$valor['tercero_id']."\" >".$valor['tipo_id_tercero']." ".$valor['tercero_id']."  &nbsp; ".$valor['nombre']." </option>\n";
        }
        $objResponse->assign("profesional_ips_esm","innerHTML",$html);
        if($opcion=='1')
        {
          $html = " <option value=\"-1\">---SELECCIONAR---</option> \n";
          if(!empty($profesionales))
          {
            foreach($profesionales as $key=>$valor)
            $html .= "  <option value=\"".$valor['tipo_id_tercero']."@".$valor['tercero_id']."\" > ".$valor['tipo_id_tercero']."  ".$valor['tercero_id']."\"&nbsp;".$valor['nombre']." </option>\n";
          }
				
          $objResponse->assign("profesional_aut_esm_ips","innerHTML",$html);
			
			}
			return $objResponse;
		}
    /**
    * Funcion que permite buscar los diagnosticos
    * @param array  $form vector con toda la forma
    * @return Object $objResponse objeto de respuesta al formulario  
    */
  
	    function MostrarDX($form,$tipo_id_paciente,$paciente_id)
      {
          $objResponse = new xajaxResponse();
          $codigo=$form['codigo_dx'];
          $descripcion_dx=$form['descripcion_dx'];

          $sel = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
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
				
       }
			return $objResponse;
		}
	
      /**
    * Funcion que permite ingresar el insumo seleccionado para ser despachado
    * @return Object $objResponse objeto de respuesta al formulario  
    */
    function Guardartmp_ins_hospita($codigo,$formula_id,$cantidad,$tiempo,$unidad,$tipo_id_paciente,$paciente_id,$plan_id,$opcion)
		{
          $objResponse = new xajaxResponse();
          $sel = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
              
          $datos=$sel->Insertar_Insumos($formula_id,$codigo,$cantidad,$tiempo,$unidad,$tipo_id_paciente,$paciente_id);
		    if($datos==true)
        {
          $url=ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDiagnosticos",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"formula_id"=>$formula_id));
          $objResponse->script('
					
					window.location="'.$url.'"
										');
					
        }
        return $objResponse;
		}
	
    /**
    * Funcion que permite ingresar los productos ambulatorios
    * @return Object $objResponse objeto de respuesta al formulario  
    */
  
    function Guardartmp_ambu($codigo,$formula_id,$cantidad,$tiempo,$unidad,$tipo_id_paciente,$paciente_id,$plan_id,$opcion)
		{
        $objResponse = new xajaxResponse();
        $sel = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
				$datos=$sel->Medicamentos_ambulatorios_Ingreso($formula_id,$codigo,$cantidad,$tiempo,$unidad,$tipo_id_paciente,$paciente_id);
		    if($datos==true)
        {
          $url=ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"formula_id"=>$formula_id));
          $objResponse->script('
					window.location="'.$url.'"
										');
					
			}
			return $objResponse;
		}
		  /**
    * Funcion que consulta los pacientes
    * @return Object $objResponse objeto de respuesta al formulario  
    */
    function Listado_Pacientes($Formulario,$offset)
		{
        $objResponse = new xajaxResponse();
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
				$datos =$obje->Pacientes_esm($Formulario,$Formulario['suministro_id'],$producto,$offset);

        $pghtml = AutoCarga::factory("ClaseHTML");

        if(!empty($datos))
        {
          $action['paginador'] = "Paginador(xajax.getFormValues('FormularioBuscador')";
          $paginador = $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
          $html .= "   <form id=\"ProductosEnLista\" name=\"ProductosEnLista\"> ";
          $html .= "	<table align=\"center\" border=\"0\" width=\"70%\" class=\"modulo_table_list\">\n";
          $html .= "		<tr class=\"formulacion_table_list\" >\n";
          $html .= "			<td width=\"10%\">IDENTIFICACION</td>\n";
          $html .= "			<td width=\"30%\" >NOMBRE COMPLETO</td>\n";
          $html .= "			<td width=\"5%\">SELECCIONAR</td>\n";
          $html .= "		</tr>\n";
          $html .= "		<tr class=\"formulacion_table_list\" >\n";
          $html .= "			<td align=\"center\" colspan=\"5\">DATOS PACIENTES</td>";
          $html .= "		</tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          $i=0;
          foreach($datos as $k1 => $dtl)
          {
                ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
                ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

                $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
                $html .= "			<td align=\"center\"><b>".$dtl['tipo_id_paciente']." ".$dtl['paciente_id']."</b></td>\n";
                $html .= "			<td align=\"left\">".$dtl['nombre_completo']."\n";
              
                 $html .= "  <input type=\"hidden\" name=\"suministro_id\" id=\"suministro_id\" value=\"".$Formulario['suministro_id']."\">
                <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$Formulario['empresa_id']."\">
                <input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" value=\"".$Formulario['centro_utilidad']."\">
                <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$Formulario['bodega']."\">
                <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">
                </td>\n";
                $html .= "			<td align=\"center\" id=\"ok".$i."\"><input type=\"checkbox\" value=\"\" class=\"checkbox\" name=\"".$i."\" id=\"".$i."\" onclick=\"if(document.getElementById('".$i."').checked==true) xajax_Listado_Productos_A_Suministrar(this.value,'".$dtl['tipo_id_paciente']."','".$dtl['paciente_id']."','".$dtl['nombre_completo']."',document.getElementById('suministro_id').value);\">\n";
                $html .= "		</td></tr>\n";
                $i++;
          }
          $html .= "    </table>";
          $html .= "    </form>";
        } 
        else
        {
          $html .= "<center>\n";
          $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
          $html .= "</center>\n";
        }
				$objResponse->assign("paginador","innerHTML",$paginador);
				$objResponse->assign("ListaProductos","innerHTML",$html);
				$objResponse->script("ListaProductos.style.display=\"\";");
				return $objResponse;
		}
   /**
    * Funcion que borra los pacientes temporales a los cuales se les ha realizado suministro
    * @return Object $objResponse objeto de respuesta al formulario  
    */
  
     function Borrar_Total_Paciente($orden_id,$Identificacion)
      {
          $objResponse = new xajaxResponse();
          $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');

          $token=$obje->Eliminar_tmp_por_paciente_suministro($orden_id,$Identificacion);
           
          if($token==true)
          {
            $objResponse->script("xajax_Listado_Productos_TMP_s('".$orden_id."');");
          }
				else
				$objResponse->alert("Error en el Borrado...!!");
				return $objResponse;
		}
  /**
    * Funcion que borra el sumistro por producto
    * @return Object $objResponse objeto de respuesta al formulario  
    */
  
    function Borrar_Item_suminstro_paciente($orden_id,$Identificacion,$codigo_producto,$fecha_vencimiento,$lote)
		{
				$objResponse = new xajaxResponse();
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');

				$token=$obje->Borrar_Item_suministro_producto_paciente($orden_id,$Identificacion,$codigo_producto,$fecha_vencimiento,$lote);
				if($token==true)
				{

							 $objResponse->script("xajax_Listado_Productos_TMP_s('".$orden_id."');");
				}
				else
				$objResponse->alert("Error en el Borrado...!!");

				return $objResponse;
		}
    /**
    * Funcion que lista los productos a sumistrar producto
    * @return Object $objResponse objeto de respuesta al formulario  
    */
    function Listado_Productos_A_Suministrar($value,$tipo_paciente,$paciente,$nombre,$orden_id,$Formulario)
		{
		
        $objResponse = new xajaxResponse();
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
			
        $DocTemporal=$obje->Obtener_InfoDocTemporal($orden_id);
        $porcentaje = ModuloGetVar("","","ESM_PorcentajeIntermediacion");
			
        $datos_empresa = SessionGetVar("DatosEmpresaAF"); 
        $empresa_id=$datos_empresa['empresa_id'];
        $lista= $obje->ObtenerContratoId($empresa_id);
        $pghtml = AutoCarga::factory("ClaseHTML");

        $html .= "   <form id=\"PacientesEnLista\" name=\"PacientesEnLista\"> ";
        $html .= "	<table align=\"center\" border=\"0\" width=\"70%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"10%\">IDENTIFICACION</td>\n";
        $html .= "			<td width=\"30%\" >PACIENTE</td>\n";
        $datos_nombre=$obje->Consultar_Paciente($tipo_paciente,$paciente);
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"modulo_list_claro\" >\n";
        $html .= "			<td width=\"10%\">".$tipo_paciente."  ".$paciente."</td>\n";
        $html .= "			<td width=\"30%\" >".$datos_nombre['nombre']."</td>\n";
        $html .= "		</tr>\n";
        $html .= "	</table><BR>";
        $tmporales =$obje->Consultar_Registros_tmp_suministro($orden_id,$tipo_paciente,$paciente);
        if(!empty($tmporales))
        {
              $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";

              $html .= "		<tr class=\"formulacion_table_list\" >\n";
              $html .= "			<td colspan=\"10\">PRODUCTOS INGRESADOS</td>\n";
              $html .= "		</tr>\n";
              
              $html .= "		<tr class=\"formulacion_table_list\" >\n";
              $html .= "			<td width=\"10%\">CODIGO</td>\n";
              $html .= "			<td width=\"50%\" >DESCRIPCION</td>\n";
              $html .= "			<td width=\"10%\" >FECHA VENCIMIENTO</td>\n";
              $html .= "			<td width=\"8%\" >LOTE</td>\n";
              $html .= "			<td width=\"8%\">CANTIDAD</td>\n";
              $html .= "			<td width=\"5%\">OP</td>\n";
              $html .= "		</tr>\n";
              $est = "modulo_list_claro";
              $bck = "#DDDDDD";
              
              foreach($tmporales as $k1 => $dtll)
              {
                    ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
                    ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
                    $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
                    $html .= "			<td align=\"center\"><b>".$dtll['codigo_producto']." </b></td>\n";
                    $html .= "			<td align=\"left\">".$dtll['descripcion']."</td>\n";

                    $html .= "			<td align=\"left\">".$dtll['fecha_vencimiento']."</td>\n";
                    $html .= "			<td align=\"left\">".$dtll['lote']."</td>\n";
                    $html .= "			<td align=\"left\">".$dtll['cantidad']."</td>\n";
                    $html .= "      <td align=\"center\">";
                    $html .= "      <a onclick=\"xajax_Borrar_Item_suminstr('".$value."','".$tipo_paciente."','".$paciente."','".$nombre."','".$orden_id."',xajax.getFormValues('PacientesEnLista'),'".$dtll['codigo_producto']."','".$dtll['fecha_vencimiento']."','".$dtll['lote']."');\">";
                    $html .= "			 <img title=\"ELIMINAR ITEM \" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">";
                    $html .= "      <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
                    $html .= "      </td>\n";
                    $html .= "		</tr>\n";
             }
              $html .= "    </table>";

              $html .= "	<table align=\"RIGHT\" border=\"0\" width=\"10%\" >\n";
              $html .= "		<tr  align=\"RIGHT\">";
              $html .= "      <td  >";
              $html .= "      <input type=\"button\" class=\"input-submit\" value=\"GENERAR\" style=\"width:100%\" onclick=\"xajax_Regresar_Buscardor_Item('".$orden_id."');\" >";
              $html .= "      </td>";
              $html .= "		</tr>\n";
              $html .= "    </table>";
          }
        $html .= "	<br><table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td colspan=\"10\">BUSCADOR DE PRODUCTOS</td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td>";
        $html .= "      CODIGO DE BARRA";
        $html .= "      </td  >";
        $html .= "      <td class=\"modulo_list_claro\">";
        $html .= "      <input type=\"text\" class=\"input-text\" name=\"codigo_barras\" id=\"codigo_barras\" style=\"width:100%\">";
        $html .= "      </td>";
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td>";
        $html .= "      CODIGO PRODUCTO";
        $html .= "      </td  >";
        $html .= "      <td class=\"modulo_list_claro\">";
        $html .= "      <input type=\"text\" class=\"input-text\" name=\"codigo\" id=\"codigo\" style=\"width:100%\">";
        $html .= "      </td>";
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td class=\"formulacion_table_list\" >";
        $html .= "      DESCRIPCION";
        $html .= "      </td>";
        $html .= "      <td class=\"modulo_list_claro\" >";
        $html .= "      <input type=\"text\" class=\"input-text\" name=\"descripcion\" id=\"descripcion\" style=\"width:100%\">";
        $html .= "		</tr>\n";
        $html .= "	</table>";
        $html .= "	<table align=\"center\" border=\"0\" width=\"10%\" >\n";

        $html .= "		<tr>\n";
        $html .= "      <td  >";
        $html .= "      <input type=\"hidden\" name=\"orden_requisicion_tmp_id\" id=\"orden_requisicion_tmp_id\" value=\"".$_REQUEST['orden_requisicion_tmp_id']."\" >";
        $html .= "      <input type=\"button\" class=\"input-submit\" value=\"buscar\" style=\"width:100%\" onclick=\"xajax_Listado_Productos_A_Suministrar('".$value."','".$tipo_paciente."','".$paciente."','".$nombre."','".$orden_id."',xajax.getFormValues('PacientesEnLista'));\" >";
        $html .= "      </td>";
        $html .= "		</tr>\n";
        $html .= "  </table><br>\n";
        $html .= "<script>";
        $html .= " function Paginador('".$value."','".$tipo_paciente."','".$paciente."','".$nombre."','".$orden_id."',xajax.getFormValues('PacientesEnLista'),offset)";
        $html .= " { ";
        $html .= "  xajax_Listado_Productos_A_Suministrar('".$value."','".$tipo_paciente."','".$paciente."','".$nombre."','".$orden_id."',xajax.getFormValues('PacientesEnLista'),offset);";
        $html .= " } ";
        $html .= "</script>";
        $datos__ =$obje->ConsultarListaDetalle_Productos($Formulario,$lista,$DocTemporal,$paciente,$tipo_paciente,$offset);
        if(!empty($datos__))
        {
				$action['paginador'] = "Paginador('".$value."','".$tipo_paciente."','".$paciente."','".$nombre."','".$orden_id."',xajax.getFormValues('PacientesEnLista'),offset)";
				$paginador = $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  

				$html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
				$html .= "                 </div>\n";
				$html .= "                                    <div id=\"error\" class='label_error'></div>";
				
				$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";

				$html .= "		<tr class=\"formulacion_table_list\" >\n";
				$html .= "			<td width=\"10%\">CODIGO PRODUCTO</td>\n";
				$html .= "			<td width=\"55%\" >DESCRIPCION</td>\n";
				$html .= "			<td width=\"10%\" >FECHA VENCIMIENTO</td>\n";
				$html .= "			<td width=\"10%\" >LOTE</td>\n";
				$html .= "			<td width=\"8%\">CANTIDAD</td>\n";
				$html .= "			<td width=\"10%\" >EXISTENCIAS</td>\n";
				$html .= "			<td width=\"5%\">SELECCIONAR</td>\n";
				$html .= "		</tr>\n";
				

				$est = "modulo_list_claro";
				$bck = "#DDDDDD";
				$i=0;
				$cantidad=0;
				foreach($datos__ as $k1 => $dtl)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

					$productos_tmp_cantidad =$obje->Cantidad_producto_tmp_suministro($dtl['codigo_producto'],$dtl['fecha_vencimiento'],$dtl['lote']);
					
					if(!empty($productos_tmp_cantidad))
					{
					  $cantidad=$productos_tmp_cantidad['cantidad'];
									
					}else
					{
					
					$cantidad=0;
					
					}
					
					$total_existencia=$dtl['existencia_actual'] - $cantidad;
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
					$html .= "			<td align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
					$html .= "			<td align=\"left\">".$dtl['descripcion']."</td>\n";
					$html .= "			<td align=\"left\">".$dtl['fecha_vencimiento']."</td>\n";
					$html .= "			<td align=\"left\">".$dtl['lote']."</td>\n";
					$html .= "      <input type=\"hidden\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\" >";
          $html .= "      <input type=\"hidden\" name=\"fecha_vencimiento".$i."\" id=\"fecha_vencimiento".$i."\" value=\"".$dtl['fecha_vencimiento']."\" >";
          $html .= "      <input type=\"hidden\" name=\"lote".$i."\" id=\"lote".$i."\" value=\"".$dtl['lote']."\" >";
          $html .= "			<td align=\"center\" > <input   type=\"text\" name=\"cantidad_solicitada".$i."\" id=\"cantidad_solicitada".$i."\" class=\"input-text\" style=\"width:100%\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad_solicitada".$i."',xGetElementById('cantidad_solicitada".$i."').value,'".$total_existencia."','hell$i');\" ></td>\n";
					$html .= "			<td align=\"left\">".round($total_existencia)."</td>\n";
					$html .= "";
					$html .= "                                                <input ".$habilitar." style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"".$i."\" id=\"".$i."\" value=\"".$i."\" >";
					$html .= "                                             </td>";
					$html .= "		</tr>\n";
					$i++;
				}

				$html .= "    </table>";
		  	$html .= "	<table align=\"RIGHT\" border=\"0\" width=\"15%\" >\n";
				$html .= "		<tr  align=\"RIGHT\">";
				$html .= "      <td  >";
				$html .= "                                               <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\" >";
				$html .= "      <input type=\"button\" class=\"input-submit\" value=\"GUARDAR\" style=\"width:100%\" onclick=\"xajax_GuardarPT('".$value."','".$tipo_paciente."','".$paciente."','".$nombre."','".$orden_id."',xajax.getFormValues('PacientesEnLista'));\" >";
				$html .= "      </td>";
				$html .= "		</tr>\n";
				$html .= "    </table>";

			}
			
			$html .= "    </form>";
	
			$objResponse->assign("Contenido","innerHTML",$html);
			$objResponse->call("MostrarSpan");
			return $objResponse;
		
		
		}
     /**
    * Funcion que guardar el suminstro temporal
    * @return Object $objResponse objeto de respuesta al formulario  
    */
    function GuardarPT($value,$tipo_paciente,$paciente,$nombre,$orden_id,$Formulario)
		{
          $objResponse = new xajaxResponse();
          $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
          $k=0;
          for($i=0;$i<=$Formulario['registros'];$i++)
          {
            if($Formulario[$i]!="")
            {
              
                if($Formulario['cantidad_solicitada'.$i] == "")
                {
                  $objResponse->assign('error',"innerHTML","NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
                }
                $Retorno = $obje->GuardarTemporal($orden_id,$Formulario['codigo_producto'.$i],$Formulario['cantidad_solicitada'.$i],$tipo_paciente,$paciente,$Formulario['fecha_vencimiento'.$i],$Formulario['lote'.$i]);
                $objResponse->assign("".$orden_id."@".$Formulario['codigo_producto']."","innerHTML",$consulta->mensajeDeError);


                if($Retorno)
                $k++;
              }
            }
        
          if($k!=0)
          {
            $objResponse->script("xajax_Listado_Productos_A_Suministrar('".$value."','".$tipo_paciente."','".$paciente."','".$nombre."','".$orden_id."',xajax.getFormValues('PacientesEnLista'));" );
          
          }
          if($Retorno === false)
          {
            $objResponse->assign('error_doc','innerHTML',$obje->mensajeDeError);
          }

          return $objResponse;
		}
	
	  /**
    * Funcion que eliminar los registros temporales que hay de suminstro por paciente
    * @return Object $objResponse objeto de respuesta al formulario  
    */
		function Borrar_Item_suminstr($value,$tipo_paciente,$paciente,$nombre,$orden_id,$Formulario,$codigo_producto,$fecha_vencimiento,$lote)
		{
				$objResponse = new xajaxResponse();
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $token=$obje->Borrar_Item_suministro($orden_id,$codigo_producto,$tipo_paciente,$paciente,$fecha_vencimiento,$lote);

				if($token)
				{

						$objResponse->script("xajax_Listado_Productos_A_Suministrar('".$value."','".$tipo_paciente."','".$paciente."','".$nombre."','".$orden_id."',xajax.getFormValues('PacientesEnLista'));" );
			
				}
				else
				$objResponse->alert("Error en el Borrado...!!");

				return $objResponse;
		}
	/**
    * Funcion que permite buscar los items
    * @return Object $objResponse objeto de respuesta al formulario  
    */
  
		
		 function Regresar_Buscardor_Item($orden_id)
		{
			  $objResponse = new xajaxResponse();
			  $objResponse->script("xajax_Listado_Pacientes(xajax.getFormValues('FormularioBuscador'),'1');");
			  $objResponse->script("xajax_Listado_Productos_TMP_s('".$orden_id."');");
			  $objResponse->call("OcultarSpan");
			  return $objResponse;
		}
			/**
    * Funcion que permite listar los productos temporales 
    * @return Object $objResponse objeto de respuesta al formulario  
    */
     function Listado_Productos_TMP_s($orden_id)
    {
        $objResponse = new xajaxResponse();
        $sql = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $datos =$sql->Listado_ProductosTemporales($orden_id);
       
        $pghtml = AutoCarga::factory("ClaseHTML");
      
        if(!empty($datos))
        {
          
            $html .= "	<table align=\"center\" border=\"0\" width=\"68%\" class=\"modulo_table_list\">\n";

            $html .= "		<tr class=\"formulacion_table_list\" >\n";
            $html .= "			<td width=\"15%\">IDENTIFICACION</td>\n";
            $html .= "			<td width=\"45%\" >PACIENTE</td>\n";
            $html .= "			<td width=\"3%\" >OP</td>\n";
            $html .= "		</tr>\n";
              
            foreach($datos as $k1 => $dtl)
            {
                ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
                ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
                $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
                $html .= "			<td align=\"center\"><b>".$dtl['identificacion']."</b></td>\n";
                $html .= "			<td align=\"left\">".$dtl['nombre_completo']."</td>\n";
                    
                $html .= "      <td align=\"center\">";
                $html .= "      <a onclick=\"xajax_Borrar_Total_Paciente('".$orden_id."','".$dtl['identificacion']."');\">";
                $html .= "			 <img title=\"ELIMINAR ITEM \" src=\"".GetThemePath()."/images/delete.gif\" border=\"0\">";
                $html .= "      <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
                $html .= "      </td>\n";
                $html .= " 		<tr   colspan=\"10\">\n";
                $html .= "      <td  colspan=\"10\" align=\"center\">";
                $html .= " 	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
                $html .= "		<tr class=\"formulacion_table_list\" >\n";
                $html .= "			<td width=\"10%\">CODIGO</td>\n";
                $html .= "			<td width=\"45%\" >DESCRIPCION</td>\n";
                $html .= "			<td width=\"10%\" >FECHA VENCIMIENTO</td>\n";
                $html .= "			<td width=\"25%\" >LOTE</td>\n";
                $html .= "			<td width=\"15%\" >CANTIDAD</td>\n";
                $html .= "			<td width=\"5%\" >OP</td>\n";
			
                $html .= "		</tr>\n";
                $datos_x =$sql->Listado_ProductosTemporales_por_paciente($orden_id,$dtl['identificacion']);
                foreach($datos_x as $k1 => $deta)
                {

                    ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
                    ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
                    $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#E0F8F7'); >\n";

                    $html .= "			<td  width=\"10%\" align=\"center\"><b>".$deta['codigo_producto']." </b></td>\n";
                    $html .= "			<td  width=\"40%\" align=\"left\">".$deta['descripcion']."</td>\n";
                    $html .= " 				<td width=\"15%\" align=\"center\">".$deta['fecha_vencimiento']."</td>\n";
                    $html .= " 				<td width=\"15%\" align=\"center\">".$deta['lote']."</td>\n";
                    $html .= " 				<td width=\"15%\" align=\"center\">".$deta['cantidad']."</td>\n";
                    $html .= "      <td align=\"center\">";
                    $html .= "      <a onclick=\"xajax_Borrar_Item_suminstro_paciente('".$orden_id."','".$dtl['identificacion']."','".$deta['codigo_producto']."','".$deta['fecha_vencimiento']."','".$deta['lote']."');\">";
                    $html .= "			 <img title=\"ELIMINAR ITEM \" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">";
                    $html .= "      <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
                    $html .= "      </td>\n";
                    $html .= " 			</tr>\n";
                    $c++;
                  
              }

              $html .= " 	    </table>";
              $html .= " 	      </td>\n";
              $html .= " 		</tr>\n";
              $html .= "		</tr>\n";
			
          }
          $html .= " 	    </table>";
          $html .= "	<table align=\"center\" border=\"0\" width=\"15%\" >\n";
          $html .= "		<tr  align=\"RIGHT\">";
          $html .= "      <td  >";
          $html .= "                                               <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\" >";
          $html .= "      <input type=\"button\" class=\"input-submit\" value=\"GUARDAR\" style=\"width:100%\" onclick=\"xajax_Redireccionar('".$orden_id."');\" >";
          $html .= "      </td>";
          $html .= "		</tr>\n";
          $html .= "    </table>";
     }
		$objResponse->assign("ProductosEnTemporal_s","innerHTML",$html);
		return $objResponse;
    }
  	/**
    * Funcion que permite direccionar para crear el suministro 
    * @return Object $objResponse objeto de respuesta al formulario  
    */
    function Redireccionar($orden)
    {
      $objResponse = new xajaxResponse();
      $url = ModuloGetURL("app","Formulacion_ExternaESM","controller","CrearSuministro")."&orden=".$orden." ";
      $script = "window.location=\"".$url."\";";
      $objResponse->script($script);
      return $objResponse;
    }
/**
    * Funcion que permite imprimir el documento de suministro
    * @return Object $objResponse objeto de respuesta al formulario  
    */

  
	function imprimir_documento($bodegas_doc_id,$numeracion)
	{
	  $objResponse = new xajaxResponse();
	  
		$direccion="app_modules/Formulacion_ExternaESM/Imprimir/imprimir_producto_suministro_por_paciente.php";	
		$objResponse->script("Imprimir('".$direccion."','".$bodegas_doc_id."','".$numeracion."');");
	 // $sql->BorrarTemporal($orden_requisicion_tmp_id);
	  //$objResponse->script("alert(\"Exito al Crear El Documento!! Numero: #".$token['orden_requisicion_id']."\");");
	  $objResponse->script($script);
	 	  
	  return $objResponse;
	}
   

?>