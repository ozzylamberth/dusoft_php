<?php
	
    IncludeClass("LogicaSO",NULL,"hc","UV_DatosSaludOcupacional");

    /**
    * Funcion que se utiliza para mostrar las ocupaciones registradas para el paciente
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/
    function MostrarDatosOcupacion($tipo_id_paciente,$paciente_id)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $consultar = new LogicaSO();
        $resultado=$consultar->MostrarDatosOcupacion($tipo_id_paciente,$paciente_id);
        //VAR_DUMP($resultado);

        if(!empty($resultado))
        {
            $salida .= "                   <table class=\"modulo_table_list\" width='90%' align='center'>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td colspan='4' align=\"center\">\n";
            $salida .= "                       OCUPACIONES SELECCIONADAS PARA EL PACIENTE";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td align=\"center\">\n";
            $salida .= "                       OCUPACION";
            $salida .= "                     </td>\n";
            $salida .= "                     <td  align=\"center\">\n";
            $salida .= "                       TIPO DE RIESGO";
            $salida .= "                     </td>\n";
//             $salida .= "                     <td  align=\"center\">\n";
//             $salida .= "                       COLOR";
//             $salida .= "                     </td>\n";
            $salida .= "                     <td align=\"center\">\n";
            $salida .= "                       AGENTE DE RIESGO";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            foreach($resultado as $key=>$valor)
            {
                //var_dump($valor);
                $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\" ROWSPAN='".count($valor)."'>\n";
                $salida .= "                       ".$key."";
                $salida .= "                     </td>\n";
                $ban=0;
                foreach($valor as $key1=>$valor1)
                {
                    if($ban!=0)
                    {
    
                        $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    }
                    $ban++;
                    $salida .= "                         <td class=\"modulo_list_claro\" align=\"center\" >\n";
                    $salida .= "                         ".$key1."";
                    $salida .= "                        </td>\n";
                    $salida .= "                     <td colspan='2' class=\"modulo_list_claro\" align=\"center\" >\n";
                    $salida .= "                       <table width='100%' cellspacing='0' >";
                    foreach($valor1 as $key1=>$valor2)
                    {
                        
                        
                        $salida .= "                         <tr class=\"modulo_list_claro\">\n";
//                         $salida .= "                           <td  width='27%'   align=\"left\" >\n";
//                         $salida .= "                             ".$valor2['color']."";
//                         $salida .= "                           </td>\n";
                        $salida .= "                           <td width='100%' bgcolor='".$valor2['color']."'  align=\"left\">\n";
                        $salida .= "                            ".$valor2['nombre_agente']."";
                        $salida .= "                           </td>\n";
                        $salida .= "                   </tr>\n";
                        
                    }
                    $salida .= "                   </table>";
                    $salida .= "                   </td>\n";
                    $salida .= "                   </tr>\n";
                }
                $salida .= "                   </tr>\n";
        }
            $salida .= "                   </table>";
            

    }
       $objResponse->assign("cuadro_datos1","innerHTML",$salida);
       return $objResponse;
    }


    /**
    * Funcion que se utiliza para mostrar los espacios registrados para el paciente
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/
    function MostrarDatosEspacio($tipo_id_paciente,$paciente_id)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $consultar = new LogicaSO();
        $resultado=$consultar->MostrarDatosEspacio($tipo_id_paciente,$paciente_id);
        //VAR_DUMP($resultado);

        if(!empty($resultado))
        {
            $salida .= "                   <table class=\"modulo_table_list\" width='90%' align='center'>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td colspan='4' align=\"center\">\n";
            $salida .= "                       ESPACIOS SELECCIONADOS PARA EL PACIENTE";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td align=\"center\">\n";
            $salida .= "                       ESPACIO";
            $salida .= "                     </td>\n";
            $salida .= "                     <td  align=\"center\">\n";
            $salida .= "                       TIPO DE RIESGO";
            $salida .= "                     </td>\n";
//             $salida .= "                     <td  align=\"center\">\n";
//             $salida .= "                       COLOR";
//             $salida .= "                     </td>\n";
            $salida .= "                     <td align=\"center\">\n";
            $salida .= "                       AGENTE DE RIESGO";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            foreach($resultado as $key=>$valor)
            {
                //var_dump($valor);
                $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\" ROWSPAN='".count($valor)."'>\n";
                $salida .= "                       ".$key."";
                $salida .= "                     </td>\n";
                $ban=0;
                foreach($valor as $key1=>$valor1)
                {
                    if($ban!=0)
                    {
    
                        $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    }
                    $ban++;
                    $salida .= "                         <td class=\"modulo_list_claro\" align=\"center\" >\n";
                    $salida .= "                         ".$key1."";
                    $salida .= "                        </td>\n";
                    $salida .= "                     <td colspan='2' class=\"modulo_list_claro\" align=\"center\" >\n";
                    $salida .= "                       <table width='100%' cellspacing='0' >";
                    foreach($valor1 as $key1=>$valor2)
                    {
                        
                        
                        $salida .= "                         <tr class=\"modulo_list_claro\">\n";
//                         $salida .= "                           <td  width='27%'   align=\"left\" >\n";
//                         $salida .= "                             ".$valor2['color']."";
//                         $salida .= "                           </td>\n";
                        $salida .= "                           <td width='100%' bgcolor='".$valor2['color']."'  align=\"left\">\n";
                        $salida .= "                            ".$valor2['nombre_agente']."";
                        $salida .= "                           </td>\n";
                        $salida .= "                   </tr>\n";
                        
                    }
                    $salida .= "                   </table>";
                    $salida .= "                   </td>\n";
                    $salida .= "                   </tr>\n";
                }
                $salida .= "                   </tr>\n";
        }
            $salida .= "                   </table>";
            

    }
       $objResponse->assign("cuadro_datos","innerHTML",$salida);
       return $objResponse;
    }

    function GuardarInfoO($ocupaciones,$tipo_id_paciente,$paciente_id)
    {

        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaSO();
        $uid = UserGetUID();
        $resultado=$registrar->RegistrarInfoO($ocupaciones,$tipo_id_paciente,$paciente_id,$uid);
       
        if($resultado===true)
        {
          $cad="OCUPACION ASIGNADA SATISFACTORIAMENTE";
        }
        else
        {
           $resultado2=$registrar->Error['MensajeError'];
          
           $encuentra = strstr ($resultado2,'duplicate key violates unique constraint');
           if($encuentra!=false)
           {
             $cad= "ESTA OCUPACION YA HA SIDO ASIGNADA A ESTE USUARIO";
           }
           elseif($encuentra===false)
           {
             $cad= $resultado2;
           }
        }
        
        $objResponse->assign("mensaje","innerHTML",$cad);
        
        return $objResponse;

    }





    function GuardarInfoE($espacios_x,$tipo_id_paciente,$paciente_id)
    {

        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaSO();
        $uid = UserGetUID();
        $resultado=$registrar->RegistrarInfoE($espacios_x,$tipo_id_paciente,$paciente_id,$uid);

        if($resultado===true)
        {
          $cad="ESPACIO ASIGNADO SATISFACTORIAMENTE";
        }
        else
        {
           $resultado2=$registrar->Error['MensajeError'];
           $encuentra = strstr ($resultado2, 'duplicate key violates unique constraint');
           if($encuentra!=false)
           {
             $cad= "ESTE ESPACIO YA HA SIDO ASIGNADO PARA ESTE USUARIO";
           }
           elseif($encuentra===false)
           {
             $cad= $resultado2;
           }
         
        }
        
        $objResponse->assign("mensaje","innerHTML",$cad);
        
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
           // $objResponse->alert('SSIIII');
            $java = "javascript:SeleccionarCicloFamiliar('".$td_id."','".$ingreso."','".$tip_id."','".$paciente_id."','".$cvf."','".$descripcion."');\"";
            $salida = "<a title='SELECCIONAR ".$descripcion."' class=\"Normal_10AN\" href=\"".$java."\">\n";
            $salida .= "  <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"> ".$descripcion."</sub>\n";
            $salida .= "</a>\n";
            $resultado=$consulta->EliminarCicloFamiliar($ingreso,$tip_id,$paciente_id,$cvf);
        }
        else
        {
            //$objResponse->alert('NOOOOOOO'.$td_id);
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
?>