<?php
	
    IncludeClass("LogicaAF",NULL,"hc","UV_FormulacionAntecedentes");



    /**
    * Funcion que sirve para modificar medicametos no formulados por el medico
    * @param array $vector con los datos del paciente y el medicamento
    * @return string con un mensaje de error o exito segun corresponda.
    **/
    function ModMedNoformu($vector)
    {
        
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $vector1=SessionGetVar("datos_usu");
        $vector['tipo_id_paciente']=$vector1['tipo_id_paciente'];
        $vector['paciente_id']=$vector1['paciente_id'];
        //$vector['fecha_registro']=date("Y-m-d");
        if($vector['tipo']==1 && $vector['fecha_finalizacion']=='')
        {
            $vector['fecha_finalizacion']="NULL";
        }
        elseif($vector['tipo']==2 && $vector['fecha_finalizacion']=='')
        {
            $objResponse->assign("errorMed","innerHTML","NO HA SELECCIONADO LA FECHA DE SUSPENSION DEL MEDICAMENTO");
            return $objResponse;
            
        }
        else
        {
          $f = explode ("-",$vector['fecha_finalizacion']);
          $vector['fecha_finalizacion']="'".$f[2]."-".$f[1]."-".$f[0]."'";
    
        }
        
     

        /*if($vector['dosis']=='')
        {
            $objResponse->assign("errorMed","innerHTML","EL CAMPO DOSIS SE ENCUENTRA VACIO");
            return $objResponse;
        }*/
        
        /*if($vector['unidad_dosificacion']=='--')
        {
            $objResponse->assign("errorMed","innerHTML","NO HA SELECCIONADO NINGUNA UNIDAD DE DOSIFICACION");
            return $objResponse;
        }*/
        
        if($vector['frecuencia']=='')
        {
            $objResponse->assign("errorMed","innerHTML","NO HA INGRESADO LA FRECUENCIA DE USO DEL MEDICAMENTO");
            return $objResponse;
        }
        
        if(empty($vector['sw_permanente']))
        {   
            $vector['sw_permanente']=0;
        }
        $vector['sw_formulado']=0;
        if($vector['descripcion']=='')
        {
            $vector['descripcion']="NULL";
        }
        else
        {
            $vector['descripcion']="'".$vector['descripcion']."'";
        }
        //$vector['evolucion_id']=$vector1['evolucion_id'];
        //VAR_DUMP();
        $consultar = new LogicaAF();
        $resultado=$consultar->ModificarMedicamento($vector);
        //var_dump($resultado);
        if($resultado===true)
        {
            $cad="MEDICAMENTO ACTUALIZADO SATISFACTORIAMENTE";
            $objResponse->assign("mensaje","innerHTML",$cad);
            
            $med_usu = $consultar->Busqueda_Medicamentos_Usuario($vector['tipo_id_paciente'],$vector['paciente_id']);
            $html = Formulados($med_usu);

            $objResponse->assign("formulacion","innerHTML",$html);
            $objResponse->call("VentanaClose");
        }
        else
        {
            $cad=$consultar->Error['MensajeError'];
            $objResponse->assign("errorMed","innerHTML",$cad.$resultado);
        }
            
            
        
        return $objResponse;
 
    }

    
    
	/**
     * Funcion que realiza la insercion de la reformulacion de medicamentos
     **/    
     function Insertar_Medicamentos($VectorForma,$evolucion_ant)
     {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $objResponse->alert(print_r($VectorForma,true));
        
        $vector1 = SessionGetVar("datos_usu");
        $VectorForma['tipo_id_paciente'] = $vector1['tipo_id_paciente'];
        $VectorForma['paciente_id']      = $vector1['paciente_id'];
        $VectorForma['evolucion_id']     = $vector1['evolucion_id'];
        
        //inserta un 1 si es pos o si es no pos y selecciono el check
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
     
          //Insercion de medicamentos
        $query="";
        $query="INSERT INTO hc_medicamentos_recetados_amb
                  (
                    codigo_producto,
                    evolucion_id, 
                    cantidad, 
                    observacion, 
                    sw_paciente_no_pos, 
                    via_administracion_id, 
                    dosis, 
                    unidad_dosificacion, 
                    tipo_opcion_posologia_id
                  )
                  (
                   	SELECT  codigo_producto, 
                            ".$VectorForma['evolucion_id']." AS evolucion_id, 
                            cantidad, 
                            observacion, 
                            sw_paciente_no_pos, 
                            via_administracion_id, 
                            dosis, 
                            unidad_dosificacion, 
                            tipo_opcion_posologia_id 
                    FROM    hc_medicamentos_recetados_amb 
                    WHERE   codigo_producto = '".$VectorForma['cod_med']."' 
                    AND     evolucion_id = ".$VectorForma['evolucion_ant']."
                  );";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $dbconn->RollbackTrans();
          $objResponse->assign("errorRefMed","innerHTML","NO SE PUDO REFORMULAR EL MEDICAMENTO - INSERCION EN TABLA hc_medicamentos_recetados_amb ".$dbconn->ErrorMsg()." - ".$query);
          return $objResponse;
        }
        else
        {
         	$query="";
          $query_pos = "SELECT tipo_opcion_posologia_id 
                             FROM   hc_medicamentos_recetados_amb 
                             WHERE codigo_producto = '".$VectorForma['cod_med']."'
                             AND   evolucion_id = ".$VectorForma['evolucion_ant'].";";
               $resulta = $dbconn->Execute($query_pos);
               if ($dbconn->ErrorNo() != 0)
               {
                    $dbconn->RollbackTrans();
                    $objResponse->assign("errorRefMed","innerHTML","NO SE PUDO OBTENER LA POSOLOGIA");
                    return $objResponse;
               }
               
               list($opcion) = $resulta->FetchRow();
               if ($opcion == '1')
               {
                    $query="INSERT INTO hc_posologia_horario_op1
                                        (codigo_producto, evolucion_id, periocidad_id, tiempo)
                                        (
	                                        SELECT codigo_producto, ".$VectorForma['evolucion_id']." AS evolucion_id, 
                                                    periocidad_id, tiempo
                                             FROM   hc_posologia_horario_op1
                                             WHERE codigo_producto = '".$VectorForma['cod_med']."' 
                                             AND   evolucion_id = ".$VectorForma['evolucion_ant']."
                                        );";	
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $dbconn->RollbackTrans();
                         $objResponse->assign("errorRefMed","innerHTML","NO SE PUDO INSERTAR LA POSOLOGIA 1");
                         return $objResponse;
		          }
               }
               
               if ($opcion == '2')
               {
                    $query="INSERT INTO hc_posologia_horario_op2
                                        (codigo_producto, evolucion_id, duracion_id)
                                        (
	                                        SELECT codigo_producto, ".$VectorForma['evolucion_id']." AS evolucion_id, 
                                                    duracion_id
                                             FROM   hc_posologia_horario_op2
                                             WHERE codigo_producto = '".$VectorForma['cod_med']."' 
                                             AND   evolucion_id = ".$VectorForma['evolucion_ant']."
                                        );";	
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $dbconn->RollbackTrans();
                         $objResponse->assign("errorRefMed","innerHTML","NO SE PUDO INSERTAR LA POSOLOGIA 2");
                         return $objResponse;
                    }
               }
               
               if ($opcion == '3')
               {
                    $query="INSERT INTO hc_posologia_horario_op3
                                        (codigo_producto, evolucion_id, sw_estado_momento,
                                         sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena)
                                        (
	                                        SELECT codigo_producto, ".$VectorForma['evolucion_id']." AS evolucion_id, 
                                                    sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena
                                             FROM   hc_posologia_horario_op3
                                             WHERE codigo_producto = '".$VectorForma['cod_med']."' 
                                             AND   evolucion_id = ".$VectorForma['evolucion_ant']."
                                        );";	
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $dbconn->RollbackTrans();
                         $objResponse->assign("errorRefMed","innerHTML","NO SE PUDO INSERTAR LA POSOLOGIA 3");
                         return $objResponse;
                    }
               }

               if ($opcion == '4')
               {
                    $query="INSERT INTO hc_posologia_horario_op4
                                        (codigo_producto, evolucion_id, hora_especifica)
                                        (
	                                        SELECT codigo_producto, ".$VectorForma['evolucion_id']." AS evolucion_id, 
                                                    hora_especifica
                                             FROM   hc_posologia_horario_op4
                                             WHERE codigo_producto = '".$VectorForma['cod_med']."' 
                                             AND   evolucion_id = ".$VectorForma['evolucion_ant']."
                                        );";	
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $dbconn->RollbackTrans();
                         $objResponse->assign("errorRefMed","innerHTML","NO SE PUDO INSERTAR LA POSOLOGIA 4");
                         return $objResponse;
                    }
               }

               if ($opcion == '5')
               {
                    $query="INSERT INTO hc_posologia_horario_op5
                                        (codigo_producto, evolucion_id, frecuencia_suministro)
                                        (
	                                        SELECT codigo_producto, ".$VectorForma['evolucion_id']." AS evolucion_id, 
                                                    frecuencia_suministro
                                             FROM   hc_posologia_horario_op5
                                             WHERE codigo_producto = '".$VectorForma['cod_med']."' 
                                             AND   evolucion_id = ".$VectorForma['evolucion_ant']."
                                        );";	
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                              $dbconn->RollbackTrans();
                              $objResponse->assign("errorRefMed","innerHTML","NO SE PUDO INSERTAR LA POSOLOGIA 5");
                              return $objResponse;
                    }
               }
          }

          $dbconn->CommitTrans();
          $objResponse->assign("errorRefMed","innerHTML","MEDICAMENTO REFORMULADO SATISFACTORIAMENTE");
          return $objResponse;
     }
	/****************************************************************************/
    /**
    * Funcion que sirve para guardar medicametos no formulados por el medico
    * @param array $vector con los datos del paciente y el medicamento
    * @return string con un mensaje de error o exito segun corresponda.
    **/
    function GuardarMedNoformu($vector)
    {
        
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $vector1=SessionGetVar("datos_usu");
        $vector['tipo_id_paciente']=$vector1['tipo_id_paciente'];
        $vector['paciente_id']=$vector1['paciente_id'];
        $vector['fecha_registro']=date("Y-m-d");
        if($vector['tipo']==1)
        {
            $vector['fecha_finalizacion']="NULL";
        }
        elseif($vector['tipo']==2 && $vector['fecha_finalizacion']=='')
        {
            $objResponse->assign("errorMed","innerHTML","NO HA SELECCIONADO LA FECHA DE SUSPENSION DEL MEDICAMENTO");
            return $objResponse;
            
        }
        else
        {
          $f = explode ("-",$vector['fecha_finalizacion']);
            $vector['fecha_finalizacion']="'".$f[2]."-".$f[1]."-".$f[0]."'";
    
        }
        $vector['medico_id']=UserGetUID();
        
        
        if($vector['cod_med']=='')
        {
            $objResponse->assign("errorMed","innerHTML","NO HA COLOCADO EL NOMBRE DEL MEDICAMENTO");
            return $objResponse;
    
        }
        if($vector['dosis']=='')
        {
            $objResponse->assign("errorMed","innerHTML","EL CAMPO DOSIS SE ENCUENTRA VACIO");
            return $objResponse;
        }
        
        if($vector['unidad_dosificacion']=='--')
        {
            $objResponse->assign("errorMed","innerHTML","NO HA SELECCIONADO NINGUNA UNIDAD DE DOSIFICACION");
            return $objResponse;
        }
        
        if($vector['frecuencia']=='')
        {
            $objResponse->assign("errorMed","innerHTML","NO HA INGRESADO LA FRECUENCIA DE USO DEL MEDICAMENTO");
            return $objResponse;
        }
        
        if(empty($vector['sw_permanente']))
        {   
            $vector['sw_permanente']=0;
        }
        $vector['sw_formulado']=0;
        if($vector['descripcion']=='')
        {
            $vector['descripcion']="NULL";
        }
        else
        {
            $vector['descripcion']="'".$vector['descripcion']."'";
        }
        $vector['evolucion_id']=$vector1['evolucion_id'];
        //VAR_DUMP();
        $consultar = new LogicaAF();
        $resultado=$consultar->GuardarNoformulado($vector);
        //var_dump($resultado);
        if($resultado===true)
        {
            $cad="MEDICAMENTO REGISTRADO SATISFACTORIAMENTE";
            $objResponse->assign("mensaje","innerHTML",$cad);
            $objResponse->call("VentanaClose");
        }
        else
        {
            $cad=$consultar->Error['MensajeError'];
            $aguja="duplicate key violates unique";
            $pos=strpos($cad, $aguja);
            if($pos!=false)
            {
                $cadena="ESTE MEDICAMENTO YA SE ENCUENTRA FORMULADO PARA ESTE PACIENTE";
                $objResponse->assign("errorMed","innerHTML",$cadena);
            }
            elseif($pos===false)
            {
                $objResponse->assign("errorMed","innerHTML",$cad.$resultado);
            }
            
            
        }
        return $objResponse;
    
    
    
    }




    /**
    * Funcion que sirve pata colocar los datos de un medicamento nuevo no formualdo por el medico
    * @param string $tipo_id_paciente codigo de medicamento
    * @param string $paciente_id nombre del medicamento
    * @param string $codigo_medicamento 
    * @param string $evolucion_id evolucion en la que se encuntra el paciente
    * @return string con la forma para crear el nuevo medicamento
    **/

    function datos_medicamentoUp($tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion_id,$swRef)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $consultar = new LogicaAF();
    
        //$vector=$consultar->ConsultarNombreMedicamentos($codigo);
        
        global $_ROOT;
        global $VISTA;
        $datos_extraidos=$consultar->DatosExtraidos($tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion_id);
        //VAR_DUMP($datos_extraidos);
        $salida = "                 <div id=\"tabelas\">";
        $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
        $salida .= "                 </div>\n";
        $salida .= "                 <form name=\"up_med\" id=\"up_med\">\n";
        $salida .= "                   <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" colspan='3'>\n";
        $salida .= "                         DATOS DEL MEDICAMENTO";
        $salida .= "                       </td>\n";
        $salida .= "                     </tr>\n";
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\"width=\"35%\">\n";
        $salida .= "                         NOMBRE DEL MEDICAMENTO";
        $salida .= "                       </td>\n";

        if(empty($datos_extraidos[0]['nombre_medicamento']))
        {
            $salida .= "                       <td align='left' colspan='2' class='modulo_list_claro'>\n";
            $salida .= "                           <input type='hidden' name=\"cod_med\"  id=\"cod_med\" value = '".$datos_extraidos[0]['codigo_medicamento']."'>";
            $salida .= "                         ".$datos_extraidos[0]['codigo_medicamento'];
            $salida .= "                       </td>\n";
        }
        elseif(!empty($datos_extraidos[0]['nombre_medicamento']))
        {
            $salida .= "                       <td align='left' colspan='2' class='modulo_list_claro'>\n";
            $salida.="                           <input type='hidden' name=\"cod_med\"  id=\"cod_med\" value = '".$datos_extraidos[0]['codigo_medicamento']."'>";
            $salida .= "                         ".$datos_extraidos[0]['nombre_medicamento'];
            $salida .= "                       </td>\n";
        }
        $salida .= "                     </tr>\n";
    
        if($swRef == true)
        {
          $salida .= "<tr class=\"modulo_table_list_title\">";
          $salida .= "  <td width=\"24%\"align=\"left\" >TIEMPO TRATAMIENTO</td>";
          $salida .= "  <td width=\"40%\"align=\"left\" >".$datos_extraidos[0]['tiempo_total']."</td>";
          $chk = "NO"; $value = "0";
          if($datos_extraidos[0]['sw_permanente'] == '1')
          {
            $chk = "SI"; 
            $value = "1";
          }
          $salida .= "  <td width=\"25%\"align=\"left\" >\n";
          $salida .= "    PERMANENTE: ".$chk;
          $salida .= "  <input type = hidden name='sw_permanente' value='".$value."' disabled>";
          $salida .= "  </td>";
          $salida .= "</tr>";
        } 
        else
        {        
               $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
               $salida .= "                       <td align=\"center\" width=\"25%\">\n";
               $salida .= "                         <a title='FECHA EN LA CUAL EL PACIENTE DEJ&#211; DE USAR EL PRODUCTO'>FECHA DE FINALIZACION</a>";
               $salida .= "                       </td>\n";
               $salida .= "                       <td class=\"normal_10AN\"  align=\"left\" >\n";
               $salida .= "                         <select name=\"tipo\" id=\"tipo\" class=\"select\" onchange=\"Vercalendario(this.value);\">";
               if(empty($datos_extraidos[0]['fecha_finalizacion']))
               {
                    $salida .= "                           <option value=\"1\" selected>ACTIVO (EN USO)</option> \n";
                    $salida .= "                           <option value=\"2\">FINALIZADO</option> \n";
               }
               elseif(!empty($datos_extraidos[0]['fecha_finalizacion']))
               {
                    $hoy=strtotime(date("Y-m-d"));
                    $f = explode("-",$datos_extraidos[0]['fecha_finalizacion']);
                    $futuro=strtotime($f[2]."-".$f[1]."-".$f[0]);
                    if($futuro > $hoy)
                    {
                         $salida .= "                           <option value=\"1\" selected >ACTIVO (EN USO)</option> \n";
                         $salida .= "                           <option value=\"2\">FINALIZADO</option> \n";
                    }
                    else
                    {    
                         $salida .= "                           <option value=\"1\">ACTIVO (EN USO)</option> \n";
                         $salida .= "                           <option value=\"2\" selected>FINALIZADO</option> \n";    
                    }
          
               }
               $salida .= "                         </select>";
               if(!empty($datos_extraidos[0]['fecha_finalizacion']))
               {
                    $salida .= "                         &nbsp;<input type=\"text\" class=\"input-text\" name=\"fecha_finalizacion\" id=\"fecha_finalizacion\" size=\"12\" OnFocus=\"this.blur()\" onclick=\"\" value=\"".$datos_extraidos[0]['fecha_finalizacion']."\">\n";
               }
               else
               {
                    $salida .= "                         &nbsp;<input type=\"text\" class=\"input-text\" name=\"fecha_finalizacion\" id=\"fecha_finalizacion\" size=\"12\" OnFocus=\"this.blur()\" onclick=\"\" value=\"\">\n";
               }
               $salida .= "                       </td>";
               $salida .= "                       <td align=\"left\">\n";
               if($datos_extraidos[0]['sw_permanente']=='1')
               {
                    $salida .= "                         <a title='MEDICAMENTO PERMANENTE'>PERMANENTE <input type=\"checkbox\" class=\"checkbox\" name=\"sw_permanente\" id=\"sw_permanente\" onclick=\"\" value=\"1\" checked></a>\n";
               }
               elseif($datos_extraidos[0]['sw_permanente']=='0')
               {
                    $salida .= "                         <a title='MEDICAMENTO PERMANENTE'>PERMANENTE <input type=\"checkbox\" class=\"checkbox\" name=\"sw_permanente\" id=\"sw_permanente\" onclick=\"\" value=\"1\"></a>\n";
               }
               $salida .= "                       </td>\n";
               $salida .= "                     </tr>\n";
        }
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" width=\"25%\">\n";
        $salida .= "                         DOSIS";
        $salida .= "                       </td>\n";
        $salida .= "                       <td colspan='2' class=\"normal_10AN\"  align=\"left\" >\n";
        if($datos_extraidos[0]['sw_formulado']=='0')
        {
            $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"dosis\" id=\"dosis\" size=\"10\" onclick=\"\" onkeypress=\"return acceptNum(event)\" value=\"".$datos_extraidos[0]['dosis']."\">\n";
        }
        else
        {
            $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"dosis\" id=\"dosis\" size=\"10\" onclick=\"\" onkeypress=\"return acceptNum(event)\" value=\"".$datos_extraidos[0]['dosis']."\" disabled>\n";
        }
        $salida.="                           <input type='hidden' name=\"evolucion_id\"  id=\"evolucion_id\" value = '".$datos_extraidos[0]['evolucion_id']."'>";
        
        $unidades_dosificacion=$consultar->ObtenerUnidadesDosificacion();
        if(!empty($unidades_dosificacion))
        {
            if($datos_extraidos[0]['sw_formulado']=='0')
            {
                $salida .= "                         <select name=\"unidad_dosificacion\" id=\"unidad_dosificacion\" class=\"select\" onchange=\"\">";
            }
            else
            {
                $salida .= "                         <select name=\"unidad_dosificacion\" id=\"unidad_dosificacion\" class=\"select\" onchange=\"\" disabled>";
            }    
            $salida .= "                           <option value=\"--\">SELECCIONAR</option> \n";
                for($i=0;$i<count($unidades_dosificacion);$i++)
                {
                    if($datos_extraidos[0]['unidad_dosificacion']==$unidades_dosificacion[$i]['unidad_dosificacion'])
                    {
                        $salida .= "                           <option value=\"".$unidades_dosificacion[$i]['unidad_dosificacion']."\" selected>".$unidades_dosificacion[$i]['unidad_dosificacion']."</option> \n";
                    }
                    else
                    {
                        $salida .= "                           <option value=\"".$unidades_dosificacion[$i]['unidad_dosificacion']."\">".$unidades_dosificacion[$i]['unidad_dosificacion']."</option> \n";
                    }
                    
                }
            $salida .= "                         </select>";
        }
        $salida .= "                       </td>";
        $salida .= "                     </tr>\n";
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" width=\"25%\">\n";
        $salida .= "                         <a title='FRECUENCIA DE TIEMPO CON LA CUAL SE APLICA EL MEDICAMENTO'>FRECUENCIA</a>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td colspan='2' class=\"normal_10AN\"  align=\"left\" >\n";
        if($swRef == true)
        { $onlyread = "disabled"; }else{ $onlyread = ""; }
        $salida .= "                         <input type=\"text\" $onlyread class=\"input-text\" name=\"frecuencia\" id=\"frecuencia\" size=\"50\" onclick=\"\" value=\"".$datos_extraidos[0]['frecuencia']."\">\n";
        $salida .= "                       </td>";
        $salida .= "                     </tr>\n";
        if($swRef != true)
        {
               $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
               $salida .= "                       <td align=\"center\" width=\"25%\">\n";
               $salida .= "                         OBSERVACIONES";
               $salida .= "                       </td>\n";
               $salida .= "                       <td colspan='2' class=\"normal_10AN\"  align=\"left\" >\n";
               $salida .= "                        <TEXTAREA class=\"normal_10AN\" NAME='descripcion' id='descripcion' ROWS='2' style=\"width:80%\" OnFocus=\"\">".$datos_extraidos[0]['descripcion']."</TEXTAREA>\n";
               $salida .= "                       </td>";
               $salida .= "                     </tr>\n";
        }
        if($swRef == true)
        {
               $salida .= "                     <tr>\n";
               $salida .= "                       <td align=\"center\" width=\"25%\" class=\"modulo_table_list_title\">\n";
               $salida .= "                         <a title='PROFESIONAL QUE REALIZO LA FORMULACION DEL MEDICAMENTO'>PROFESIONAL QUE FORMULO</a>";
               $salida .= "                       </td>\n";
               $salida .= "                       <td colspan='2' align=\"left\" class='modulo_list_claro'><b>".$datos_extraidos[0]['nombre']."</b>\n";
               $salida .= "                       </td>";
               $salida .= "                     </tr>\n";
        }
        $salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $evolucion_ant = "";
        if($swRef == true)
        {
          $evolucion_ant = $datos_extraidos[0]['evolucion_id'];
          $salida .= "                       <td colspan='3' class=\"normal_10AN\"  align=\"center\" >\n";
          $salida .= "                         <input type=\"button\" class=\"input-submit\" value=\"Reformular Medicamento\" onclick=\"xajax_Insertar_Medicamentos(xajax.getFormValues('up_med'),'".$evolucion_ant."');\">\n";
          $salida .= "                         <input type='hidden' name=\"evolucion_ant\" id=\"evolucion_ant\" value = '".$datos_extraidos[0]['evolucion_id']."'>";               
          $salida .= "                         <input type='hidden' name=\"posologia\" id=\"posologia\" value = '".$datos_extraidos[0]['evolucion_id']."'>";               
          $salida .= "                       </td>";
        }
        else
        {
           $salida .= "                       <td colspan='3' class=\"normal_10AN\"  align=\"center\" >\n";
           $salida .= "                         <input type=\"button\" class=\"input-submit\" value=\"Modificar Medicamento\" onclick=\"xajax_ModMedNoformu(xajax.getFormValues('up_med'));\">\n";
           $salida .= "                       </td>";
        }
        $salida .= "                     </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "                 </form>\n";
        if($swRef == true)
        {  $objResponse->assign("ContenidoRefMed","innerHTML",$salida);  }
        else
        {  $objResponse->assign("ContenidoMed","innerHTML",$salida);  }	
        return $objResponse;
    
    }



    /**
    * Funcion que sirve pata colocar los datos de un medicamento nuevo no formualdo por el medico
    * @param string $codigo codigo de medicamento
    * @param string $nombre nombre del medicamento
    * @return string con la forma para crear el nuevo medicamento
    **/
    
    function datos_medicamento($codigo='',$nombre='')
    {
    
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $consultar = new LogicaAF();
    
        //$vector=$consultar->ConsultarNombreMedicamentos($codigo);
        
        global $_ROOT;
        global $VISTA;
    
    
        $salida = "                 <div id=\"tabelas\">";
        $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
        $salida .= "                 </div>\n";
        $salida .= "                 <form name=\"adicionar_med\" id=\"adicionar_med\">\n";
        $salida .= "                   <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" colspan='3'>\n";
        $salida .= "                         DATOS DEL MEDICAMENTO";
        $salida .= "                       </td>\n";
        $salida .= "                     </tr>\n";
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\"width=\"35%\">\n";
        $salida .= "                         NOMBRE DEL MEDICAMENTO";
        $salida .= "                       </td>\n";
        if(empty($nombre))
        {
            $salida .= "                       <td align='left' colspan='2' class='modulo_list_claro'>\n";
            $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"cod_med\" id=\"cod_med\" size=\"50\" onclick=\"\" onKeyUp=\"javascript:todoMay(event,this)\" value=\"\">\n";
            $salida .= "                       </td>\n";
        }
        else
        {
            $salida .= "                       <td align='left' colspan='2' class='modulo_list_claro'>\n";
            $salida.="                           <input type='hidden' name=\"cod_med\"  id=\"cod_med\" value = '".$codigo."'>";
            $salida .= "                         ".$nombre;
            $salida .= "                       </td>\n";
        }
    
        $salida .= "                     </tr>\n";
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" width=\"25%\">\n";
        $salida .= "                         <a title='FECHA EN LA CUAL EL PACIENTE DEJ&#211; DE USAR EL PRODUCTO'>FECHA DE FINALIZACION</a>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td class=\"normal_10AN\"  align=\"left\" >\n";
        $salida .= "                         <select name=\"tipo\" id=\"tipo\" class=\"select\" onchange=\"Vercalendario(this.value);\">";
        $salida .= "                           <option value=\"1\" selected>ACTIVO (EN USO)</option> \n";
        $salida .= "                           <option value=\"2\">FINALIZADO</option> \n";
        $salida .= "                         </select>";
        $salida .= "                         &nbsp;<input type=\"text\" class=\"input-text\" name=\"fecha_finalizacion\" id=\"fecha_finalizacion\" size=\"12\" OnFocus=\"this.blur()\" onclick=\"\" value=\"\">\n";
        $salida .= "                       </td>";
        $salida .= "                       <td align=\"left\">\n";
        $salida .= "                         <a title='MEDICAMENTO PERMANENTE'>PERMANENTE <input type=\"checkbox\" class=\"checkbox\" name=\"sw_permanente\" id=\"sw_permanente\" onclick=\"\" value=\"1\"></a>\n";
        $salida .= "                       </td>\n";
        $salida .= "                     </tr>\n";
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" width=\"25%\">\n";
        $salida .= "                         DOSIS";
        $salida .= "                       </td>\n";
        $salida .= "                       <td colspan='2' class=\"normal_10AN\"  align=\"left\" >\n";
        $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"dosis\" id=\"dosis\" size=\"10\" onclick=\"\" onkeypress=\"return acceptNum(event)\" value=\"\">\n";
        $unidades_dosificacion=$consultar->ObtenerUnidadesDosificacion();
        if(!empty($unidades_dosificacion))
        {
            $salida .= "                         <select name=\"unidad_dosificacion\" id=\"unidad_dosificacion\" class=\"select\" onchange=\"\">";
            $salida .= "                           <option value=\"--\" selected>SELECCIONAR</option> \n";
                for($i=0;$i<count($unidades_dosificacion);$i++)
                {
                    $salida .= "                           <option value=\"".$unidades_dosificacion[$i]['unidad_dosificacion']."\">".$unidades_dosificacion[$i]['unidad_dosificacion']."</option> \n";
                }
            $salida .= "                         </select>";
        }
        $salida .= "                       </td>";
        $salida .= "                     </tr>\n";
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" width=\"25%\">\n";
        $salida .= "                         <a title='FRECUENCIA DE TIEMPO CON LA CUAL SE APLICA EL MEDICAMENTO'>FRECUENCIA</a>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td colspan='2' class=\"normal_10AN\"  align=\"left\" >\n";
        $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"frecuencia\" id=\"frecuencia\" size=\"50\" onclick=\"\" value=\"\">\n";
        $salida .= "                       </td>";
        $salida .= "                     </tr>\n";
        $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" width=\"25%\">\n";
        $salida .= "                         OBSERVACIONES";
        $salida .= "                       </td>\n";
        $salida .= "                       <td colspan='2' class=\"normal_10AN\"  align=\"left\" >\n";
        $salida .= "                        <TEXTAREA class=\"normal_10AN\" NAME='descripcion' id='descripcion' ROWS='2' style=\"width:80%\" OnFocus=\"\"></TEXTAREA>\n";
        $salida .= "                       </td>";
        $salida .= "                     </tr>\n";
        $salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $salida .= "                       <td colspan='3' class=\"normal_10AN\"  align=\"center\" >\n";
        $salida .= "                         <input type=\"button\" class=\"input-submit\" value=\"Guardar Medicamento\" onclick=\"xajax_GuardarMedNoformu(xajax.getFormValues('adicionar_med'));\">\n";
        $salida .= "                       </td>";
        $salida .= "                     </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "                 </form>\n";
        $objResponse->assign("ContenidoMed","innerHTML",$salida);
        return $objResponse;
    
    }

    

function buscar_medicamento($opcion,$producto,$principio_activo,$ban,$offset)
{

    $path = SessionGetVar("rutaImagenes");
    $objResponse=new xajaxResponse();
    $registrar = new LogicaAF();
    $salida .= "<script language='javascript' src='hc_modules/UV_FormulacionAntecedentes/RemoteXajax/FornulacionAntecedentes.js'></script>";
    $vector=$registrar->Busqueda_Avanzada_Medicamentos($opcion,$producto,$principio_activo,$ban,$offset);
    //var_dump($vector);


    $salida .= "                 <div id=\"tabelas\">";
       if(!empty($vector['MEDICAMENTOS']))
       {    
                $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
                $salida .= "                 </div>\n";
                $salida .= "                 <form name=\"adicionar\">\n";
                $salida .= "                   <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
                $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
                $salida .= "                       <td align=\"center\" width=\"10%\">\n";
                $salida .= "                         CODIGO";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\"width=\"35%\">\n";
                $salida .= "                         PRODUCTO";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width=\"25%\">\n";
                $salida .= "                         <a title='PRINCIPIO ACTIVO'>PRINCIPIO ACTIVO<a> ";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width=\"25%\">\n";
                $salida .= "                         <a title='FORMA'>FORMA<a>";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width=\"5%\">\n";
                $salida .= "                         <a title='SELECCIONAR'>SL<a>";
                $salida .= "                       </td>\n";
                $salida .= "                     </tr>\n";         
                for($i=0;$i<count($vector['MEDICAMENTOS']);$i++)
                {
//                  ["item"]=>
//                     string(6) "NO POS"
//                     ["codigo_producto"]=>
//                     string(10) "0101020022"
//                     ["producto"]=>
//                     string(16) "KLARICID 500 MGS"
//                     ["principio_activo"]=>
//                     string(14) "CLARITROMICINA"
//                     ["forma"]=>
//                     string(7) "TABLETA"
//                     ["unidad_dosificacion"]=>
//                     NULL
//                     ["concentracion_forma_farmacologica"]=>
//                     string(1) "0"
//                     ["unidad_medida_medicamento_id"]=>
//                     string(2) "TA"
//                     ["factor_conversion"]=>
//                     string(4) "0.00"
//                     ["factor_equivalente_mg"]=>
//                     string(4) "0.00"
//                     ["cod_forma_farmacologica"]=>
//                     string(2) "01"
                    $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                    $salida .= "                      <td align=\"left\">\n";
                    $salida .= "                     ".$vector['MEDICAMENTOS'][$i]['codigo_producto']."";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\">\n";
                    $salida .= "                        ".$vector['MEDICAMENTOS'][$i]['producto'];
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\">\n";
                    $salida .= "                        ".$vector['MEDICAMENTOS'][$i]['principio_activo'];
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\">\n";
                    $salida .= "                        ".$vector['MEDICAMENTOS'][$i]['forma'];
                    $salida .= "                      </td>\n";
                    $javadx = "javascript:MostrarCapa('ContenedorMed');AsignarMedicamento('".$vector['MEDICAMENTOS'][$i]['codigo_producto']."','".$vector['MEDICAMENTOS'][$i]['producto']."');Iniciar('ASIGNAR MEDICAMENTO');";
                    $salida .= "                      <td align=\"center\" onclick=\"".$javadx."\">\n";
                    $salida .= "                         <a title='SELECCIONAR'>\n";
                    $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         </a>\n";
                    $salida .= "                      </td>\n";
                    $salida .= "                    </tr>\n";
                    }   
              $salida .= "</table>\n";
        }  
        else
        {
            
          $salida .= "<table align='center'>\n";
          $salida .= "<tr>\n";
          $salida .= "<td>\n";
          $salida .= "    <label class='label_error' style=\"text-transform: uppercase; text-align:center;\">NO SE ENCONTRARON RESULTADOS</label>\n"; 
          $salida .= "</td>\n";
          $salida .= "</tr>\n";
          $salida .= "</table>\n";
          $salida .= "                 <table width=\"100%\" align=\"center\" >\n";
          $salida .= "                   <tr>\n";
          $salida .= "                     <td align=\"center\" class='label_mark'>\n";
          $salida .= "                       <a href=\"javascript:AsignarNuevoMedicamento();\" class=\"label_error\">ADICIONAR MEDICAMENTOS NO FORMULADOS</a>\n";
          $salida .= "                     </td>\n";
          $salida .= "                   </tr>\n";
          $salida .= "                 </table>\n";
        }
        $salida .="                     </div>";
        $Cont=SessionGetVar("CUANTOS_HAY");
        $salida .= "".ObtenerPaginadoMed($offset,$path,$Cont,$opcion,$producto,$principio,$ban);
        //$salida .= "".ObtenerPaginadoCuenta($offset,$path,$Cont,$opcion,$cuenta,$path,$Cont,'1',$tip_bus);
        $objResponse->assign("tabelos","innerHTML",$salida);  
        return $objResponse;

}




    function GuardarFR($ingreso,$tip_pac,$id_pac,$cvi,$fr)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaCF();

        $encontrar_registro_FR=$registrar->ConsultaCiclosFR($ingreso,$tip_pac,$id_pac);

        if(!empty($encontrar_registro_FR))
        {
           $resultado=$registrar->EliminarFR($ingreso,$tip_pac,$id_pac);
        }

        if($resultado==true || empty($encontrar_registro))
        {
           $resultado1 = $registrar->InsertarFR($ingreso,$tip_pac,$id_pac,$cvi,$fr);
        }
        else
        {
           $resultado1="INSERCION ESTA MAL";
        }
        
        $objResponse->assign("mensaje","innerHTML",$resultado1);
        
        return $objResponse;




    }


    function GuardarObsCvf($ingreso,$tip_pac,$id_pac,$observaciones)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaCF();

        $encontrar_registro=$registrar->ConsultaCiclosObservaciones($ingreso,$tip_pac,$id_pac);

        if(!empty($encontrar_registro))
        {
           $resultado=$registrar->EliminarCFO($ingreso,$tip_pac,$id_pac);
        }

        if($resultado==true  || empty($encontrar_registro))
        {
           $resultado1=$registrar->InsertarCicloFamiliaresObservaciones($ingreso,$tip_pac,$id_pac,$observaciones);
        }
        else
        {
            $resultado1="INSERCION ESTA MAL";
        }
        
        $objResponse->assign("mensaje","innerHTML",$resultado1);
        
        return $objResponse;

    }



    function Prueba($td_id,$ingreso,$tip_id,$paciente_id,$cvf,$descripcion)
	{
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $consulta = new LogicaCF();
        $CCFS=$consulta->ConsultaCiclosFamiliaresPacienteSeleccionado($ingreso,$tip_id,$paciente_id,$cvf,$descripcion);
        if(!empty($CCFS))
        {
            $objResponse->alert('SSIIII');
            $java = "javascript:SeleccionarCicloFamiliar('".$td_id."','".$ingreso."','".$tip_id."','".$paciente_id."','".$cvf."','".$descripcion."');\"";
            $salida = "<a title='SELECCIONAR ".$descripcion."' class=\"Normal_10AN\" href=\"".$java."\">\n";
            $salida .= "  <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"> ".$descripcion."</sub>\n";
            $salida .= "</a>\n";
            $resultado=$consulta->EliminarCicloFamiliar($ingreso,$tip_id,$paciente_id,$cvf);
        }
        else
        {
            $objResponse->alert('NOOOOOOO'.$td_id);
            $java = "javascript:SeleccionarCicloFamiliar('".$td_id."','".$ingreso."','".$tip_id."','".$paciente_id."','".$cvf."','".$descripcion."');\"";
            $salida = "<a title='SELECCIONAR ".$descripcion."' class=\"Normal_10AN\" href=\"".$java."\">\n";
            $salida .= "  <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"> ".$descripcion."</sub>\n";
            $salida .= "</a>\n";
            $resultado=$consulta->InsertarCiclosFamiliares($ingreso,$tip_id,$paciente_id,$cvf);
        }

//      $objResponse->alert("solo es un mensaje de prueba");
        $objResponse->assign($td_id,"innerHTML",$salida);
        $objResponse->assign("mensaje","innerHTML",$resultado);
		
		return $objResponse;
	}

/**
*FUNCION PARA MOSTRAR EL PAGINADOR DE MEDICAMENTOS
**/

    
    function ObtenerPaginadoMed($pagina,$path,$slc,$op,$producto,$principio,$ban)
    {
      
      //echo "ioAAAAAAAAAAAAAAAAAAA".$slc;
      $TotalRegistros = $slc;
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $uid = UserGetUID();
         $LimitRow = intval(GetLimitBrowser());
      }
      else
      {
        $LimitRow = $limite;
      }
      if ($TotalRegistros > 0)
      {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros/$LimitRow);
        
         if($TotalRegistros%$LimitRow > 0)
        {
          $NumeroPaginas++;
        }
            
        $Inicio = $pagina;
        if($NumeroPaginas - $pagina < 9 )
        {
          $Inicio = $NumeroPaginas - 9;
        }
        elseif($pagina > 1)
        {
          $Inicio = $pagina - 1;
        }
        
        if($Inicio <= 0)
        {
          $Inicio = 1;
        }
          
        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 

        $TablaPaginado .= "<tr>\n";
        if($NumeroPaginas > 1)
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                        //     Buscar_Med(tipo,producto,principio_act,ban,offset)
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Buscar_Med('".$op."','".$producto."','".$principio."','1','1')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Buscar_Med('".$op."','".$producto."','".$principio."','1','".($pagina-1)."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
          }
          $Fin = $NumeroPaginas + 1;
          if($NumeroPaginas > 10)
          {
            $Fin = 10 + $Inicio;
          }
            
          for($i=$Inicio; $i< $Fin ; $i++)
          {
            if ($i == $pagina )
            {
              $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
            }
            else
        {                                                                                                      
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Buscar_Med('".$op."','".$producto."','".$principio."','1','".$i."')\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";//Buscar_Med('".$op."','".$producto."','".$principio."','1','".($pagina-1)."')
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Buscar_Med('".$op."','".$producto."','".$principio."','1','".($pagina+1)."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Buscar_Med('".$op."','".$producto."','".$principio."','1','".$NumeroPaginas."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
        $aviso .= "   </tr>\n";
        
        if($op == 2)
        {
          $TablaPaginado .= $aviso;
        }
        else
        {
          $TablaPaginado = $aviso.$TablaPaginado;
        }
      }
      
      $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
      $Tabla .= $TablaPaginado;
      $Tabla .= "</table>";
      //  VAR_DUMP($Tabla);
      return $Tabla;
    }
    /**
    *
    */
    function Formulados($med_usu)
    {
      $path = GetThemePath();
      
      $html  = "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "                   <tr>\n";
      $html .= "                     <td width='25%' class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "                       <a title='NOMBRE DEL MEDICAMENTO'><label >NOMBRE DEL MEDICAMENTO</label></a>";
      $html .= "                     </td>\n";
      $html .= "                     <td width='7%' class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "                       <a title='FECHA DE INICIO DEL TRATAMIENTO'><label >INICIO</label></a>";
      $html .= "                     </td>\n";
      $html .= "                     <td width='7%' class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "                       <a title='FECHA DE FINALIZACION DEL TRATAMIENTO'><label >FINAL</label></a>";
      $html .= "                     </td>\n";
      $html .= "                     <td width='5%' class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "                       <a title='MEDICAMENTO FORMULADO'><label >FRM</label></a>";
      $html .= "                     </td>\n";
      $html .= "                     <td width='16%' class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "                       <a title='DOSIS FORMULADA'><label >DOSIS</label></a>";
      $html .= "                     </td>\n";
      $html .= "                     <td width='5%' class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "                       <a title='FORMULACION PERMANANTE'><label >FP</label></a>";
      $html .= "                     </td>\n";
      $html .= "                     <td width='15%' class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "                       <a title='PERIORICIDAD CON LA CUAL SE DEBE ENTREGAR AL PACIENTE EL MEDICAMENTO'><label >PERIORICIDAD</label></a>";
      $html .= "                     </td>\n";
      $html .= "                     <td width='15%' class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "                       <a title='TIEMPO TOTAL DEL TRATAMIENTO'><label >TIEMPO TOTAL</label></a>";
      $html .= "                     </td>\n";
      $html .= "                     <td width='5%' class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "                       <a title='MODIFICAR DATOS'><label >MOD</label></a>";
      $html .= "                     </td>\n";
      $html .= "                   </tr>\n";
      for($i=0;$i<count($med_usu);$i++)
      {
          $html .= "                   <tr class=\"modulo_list_claro\">\n";
          $html .= "                     <td  align=\"left\">\n";
          if(empty($med_usu[$i]['descripcion']))
          {
              $html .="                       ".$med_usu[$i]['codigo_medicamento']."";
          }
          else
          {
              $html .="                       ".$med_usu[$i]['descripcion']."";
          }
          $html .= "                     </td>\n";
          $html .= "                     <td  align=\"left\">\n";
          $html .="                       ".$med_usu[$i]['fecha_registro']."";
          $html .= "                     </td>\n";
          $html .= "                     <td align=\"left\">\n";
          if(empty($med_usu[$i]['fecha_finalizacion']))
          {
              $html .="                       ACTIVO";
          }
          else
          {
              $html .="                       ".$med_usu[$i]['fecha_finalizacion']."";
          }
          
          $html .= "                     </td>\n";
          $html .= "                     <td align=\"center\">\n";
           if(!EMPTY($med_usu[$i]['nombre']) &&  $med_usu[$i]['sw_formulado'])
           {
               $html .="                   <a title='MEDICAMENTO FORMULADO POR ".$med_usu[$i]['nombre']."' href='#'>";
               $html .= "                    <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
               $html .= "                  <a>\n";
           }
           else
           {
               $html .="                   <a title='MEDICAMENTO NO FORMULADO'>";
               $html .= "                    <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
               $html .= "                  <a>\n";
           }
          
          $html .= "                     </td>\n";
          $html .= "                     <td  align=\"left\">\n";
          $html .="                       ".$med_usu[$i]['dosis']." ".$med_usu[$i]['unidad_dosificacion']." ".$med_usu[$i]['frecuencia'].""; //  dosis   unidad_dosificacion frecuencia
          $html .= "                     </td>\n";
          $html .= "                     <td align=\"center\">\n";
          if($med_usu[$i]['sw_permanente']=='1')
          {
              $html .="                   <a title='MEDICAMENTO FORMULADO PERMANENTEMENTE' href='#'>";
              $html .= "                    <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
              $html .= "                  <a>\n";
          }
          elseif($med_usu[$i]['sw_permanente']=='0')
          {
              $html .="                   <a title='MEDICAMENTO NO FORMULADO PERMANENTEMENTE'>";
              $html .= "                    <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
              $html .= "                  <a>\n";
          }
          $html .= "                     </td>\n";
          $html .= "                     <td align=\"left\">\n";
          $html .="                   ".$med_usu[$i]['perioricidad_entrega']."";
          $html .= "                     </td>\n";
          $html .= "                     <td align=\"left\">\n";
          $html .="                   ".$med_usu[$i]['tiempo_total']."";
          $html .= "                     </td>\n";
          
          if($med_usu[$i]['medico_id']==UserGetUID())
          {                                                                                                           //tipo_id_paciente    paciente_id     codigo_medicamento
              $javadx = "javascript:MostrarCapa('ContenedorMed');AsignarMedicamentoUp('".$med_usu[$i]['tipo_id_paciente']."','".$med_usu[$i]['paciente_id']."','".$med_usu[$i]['codigo_medicamento']."','".$med_usu[$i]['evolucion_id']."');Iniciar('ASIGNAR MEDICAMENTO');";
              $html .= "                      <td align=\"center\" onclick=\"".$javadx."\">\n";
              $html .="                   <a title='MODIFICAR DATOS'>";
              $html .= "                    <sub><img src=\"".$path."/images/editar.gif\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
              $html .= "                  <a>\n";
          }
          else
          {
              $html .= "                     <td align=\"center\">\n";
          }
          $html .= "                     </td>\n";
          $html .= "                   </tr>\n";
      }
      $html .= "                   </table>";
      return $html;
    }
?>