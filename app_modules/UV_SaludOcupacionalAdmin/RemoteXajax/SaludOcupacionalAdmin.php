<?php

    
     /**
    * Funcion que sirve para registrar en la base de datos un agente de riesgos
    * @param string $ocupaciones
    * @param string $nuevo_cargo
    * @param string $usuario
    * @return string con el mensaje de transaccion exitosa o fallida
    **/
    
    function UpCargoBD($ocupaciones,$nuevo_cargo,$usuario,$cargo_id)
    {
        $objResponse = new xajaxResponse();
        //$objResponse->alert($tipos_riesgo);
        if($ocupaciones!='-1' && $nuevo_cargo!='')
        {

            $cot = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
            $usuario=UserGetUID();
            $resultado=$cot->UpdateTheCargo($cargo_id,$ocupaciones,$nuevo_cargo,$usuario);

            if($resultado===true)
            {
                $cad="CARGO ACTUALIZADO SATISFACTORIAMENTE";
                $objResponse->assign("error","innerHTML",$cad);
                $objResponse->call("VentanaClose");
                $objResponse->call("UpListarCargos");
            }
            else
            {
                $cad=$cot->Error['MensajeError'];
                $objResponse->assign("errorGrup","innerHTML",$cad.$resultado);
            }

        }
        else
        {
            if($ocupaciones=='-1')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA SELECCIONAR LA OCUPACION");
            }
            elseif($nuevo_cargo=='')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA EL NOMBRE DEL CARGO");
            }

        }
        return $objResponse;
    }

    /**
    * Funcion que se utiliza para mostrar el menu de actualizacion de cargo
    * @return string con el menu de creacion del agente de riesgo.
    **/
    function Actualizar($cargo_ocupacion_id,$descripcion,$ocupacion_id)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $usuario=UserGetUID();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");

        $ocupaciones = $afi->ObtenerOcupaciones();
        if(!empty($ocupaciones))
        {

            $salida .= "                 <br>\n";
            $salida .= "                 <table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
            $salida .= "                   <tr class=\"formulacion_table_list\" >\n";
            $salida .= "                     <td colspan='2' class=\"formulacion_table_list\">\n";
            $salida .= "                      EDITAR DATOS CARGO\n";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   <tr class=\"formulacion_table_list\" >\n";
            $salida .= "                     <td class=\"formulacion_table_list\">\n";
            $salida .= "                       SELECCIONE OCUPACION\n";
            $salida .= "                       </td>\n";
            $salida .= "                     <td align='left' class=\"modulo_list_claro\">\n";
            $salida .= "                       <select class=\"select\" name=\"ocupaciones1\" id=\"ocupaciones1\" onchange=\"\">";
            $salida .= "                         <option style='background-color:#ffffff;' value=\"-1\">SELECCIONAR</option> \n";
                //ocupacion_id     tipo_riesgo_id integer    agente_riesgo_id integer    usuario_registro integer    fecha_registro    sw_estado
            for($i=0;$i<count($ocupaciones);$i++)
            {
                if($ocupacion_id==$ocupaciones[$i]['ocupacion_id'])
                {
                    $salida .= "                           <option value=\"".$ocupaciones[$i]['ocupacion_id']."\" selected>".$ocupaciones[$i]['descripcion']."</option> \n";
                }
                else
                {
                    $salida .= "                           <option value=\"".$ocupaciones[$i]['ocupacion_id']."\">".$ocupaciones[$i]['descripcion']."</option> \n";
                }
                
                
            }
            $salida .= "                         </select>\n";
            $salida .= "                       </td>\n";
            $salida .= "                     </tr>\n";
        }
        
        
        $salida .= "                   <tr class=\"formulacion_table_list\" >\n";
        $salida .= "                     <td  align=\"center\">\n";
        $salida .= "                       NOMBRE DEL CARGO";
        $salida .= "                     </td>\n";
        $salida .= "                     <td  align=\"left\" class=\"modulo_list_claro\">\n";
        $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"nuevo_cargo\" id=\"nuevo_cargo\" value=\"".$descripcion."\"  size=\"50\" onkeypress=\"\">\n";
        $salida .= "                     </td>\n";
        $salida .= "                   </tr>\n";
        $salida .= "                   <tr>\n";
        $salida .= "                     <td class=\"modulo_list_claro\" colspan='2' align=\"CENTER\">\n";
        $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"Actualizar Cargo\" onclick=\"xajax_UpCargoBD(document.getElementById('ocupaciones1').value,document.getElementById('nuevo_cargo').value,'".$usuario."','".$cargo_ocupacion_id."');\">\n";
        $salida .= "                     </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                 </table>\n";
        $objResponse->assign("ContenidoGrup","innerHTML",$salida);
        return $objResponse;
    }
    

    /**
    * Funcion que sirve para registrar en la base de datos un agente de riesgos
    * @param string $ocupaciones
    * @param string $nuevo_cargo
    * @param string $usuario
    * @return string con el mensaje de transaccion exitosa o fallida
    **/
    
    function RegistraCargoBD($ocupaciones,$nuevo_cargo,$usuario)
    {
        $objResponse = new xajaxResponse();
        //$objResponse->alert($tipos_riesgo);
        if($ocupaciones!='-1' && $nuevo_cargo!='')
        {

            $cot = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
            $usuario=UserGetUID();
            $resultado=$cot->InsertarNuevoCargo($ocupaciones,$nuevo_cargo,$usuario);

            if($resultado===true)
            {
                $cad="CARGO REGISTRADO SATISFACTORIAMENTE";
                $objResponse->assign("error","innerHTML",$cad);
                $objResponse->call("VentanaClose");
                $objResponse->call("TablaOcupacion");
            }
            else
            {
                $cad=$cot->Error['MensajeError'];
                $objResponse->assign("errorGrup","innerHTML",$cad.$resultado);
            }

        }
        else
        {
            if($ocupaciones=='-1')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA SELECCIONAR LA OCUPACION");
            }
            elseif($agente_de_riesgo=='')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA EL NOMBRE DEL AGENTE DE RIESGO");
            }

        }
        return $objResponse;
    }



    /**
    * Funcion que se utiliza para mostrar el menu de creacion de agentes de riesgo
    * @return string con el menu de creacion del agente de riesgo.
    **/
    function CrearNuevoCargo()
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
        $ocupaciones = $afi->ObtenerOcupaciones();
        if(!empty($ocupaciones))
        {

            $salida .= "                 <br>\n";
            $salida .= "                 <table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
            $salida .= "                   <tr class=\"formulacion_table_list\" >\n";
            $salida .= "                     <td colspan='2' class=\"formulacion_table_list\">\n";
            $salida .= "                       DATOS NUEVO CARGO\n";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   <tr class=\"formulacion_table_list\" >\n";
            $salida .= "                     <td class=\"formulacion_table_list\">\n";
            $salida .= "                       SELECCIONE OCUPACION\n";
            $salida .= "                       </td>\n";
            $salida .= "                     <td align='left' class=\"modulo_list_claro\">\n";
            $salida .= "                       <select class=\"select\" name=\"ocupaciones\" id=\"ocupaciones\" onchange=\"llamarCargosSegunOcupacion(this.value);\">";
            $salida .= "                         <option style='background-color:#ffffff;' value=\"-1\">SELECCIONAR</option> \n";
                //ocupacion_id     tipo_riesgo_id integer    agente_riesgo_id integer    usuario_registro integer    fecha_registro    sw_estado
            for($i=0;$i<count($ocupaciones);$i++)
            {
                $salida .= "                           <option value=\"".$ocupaciones[$i]['ocupacion_id']."\">".$ocupaciones[$i]['descripcion']."</option> \n";
            }
            $salida .= "                         </select>\n";
            $salida .= "                       </td>\n";
            $salida .= "                     </tr>\n";
        }
        
        
        $salida .= "                   <tr class=\"formulacion_table_list\" >\n";
        $salida .= "                     <td  align=\"center\">\n";
        $salida .= "                       NOMBRE DEL CARGO";
        $salida .= "                     </td>\n";
        $salida .= "                     <td  align=\"left\" class=\"modulo_list_claro\">\n";
        $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"nuevo_cargo\" id=\"nuevo_cargo\" value=\"\"  size=\"50\" onkeypress=\"\">\n";
        $salida .= "                     </td>\n";
        $salida .= "                   </tr>\n";
        $salida .= "                   <tr>\n";
        $salida .= "                     <td class=\"modulo_list_claro\" colspan='2' align=\"CENTER\">\n";
        $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"Crear Nuevo Cargo\" onclick=\"xajax_RegistraCargoBD(document.getElementById('ocupaciones').value,document.getElementById('nuevo_cargo').value,'".$usuario."');\">\n";
        $salida .= "                     </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                 </table>\n";
        $objResponse->assign("ContenidoGrup","innerHTML",$salida);
        return $objResponse;
    }
    

    /**
    * funcion que sirve para listar los cargos segun una ocupacion
    * @param array $ocupacion
    * @return string con la menu de opciones
    **/
    function CargosSegunOcupacion($ocupacion)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin","", "app","UV_SaludOcupacionalAdmin");
        $lista_cargos=$afi->Obtener_cargos_por_espacio($ocupacion);


        if(!empty($lista_cargos))
        {
                    $html .= "                 <table width=\"40%\" align=\"center\"  >\n";
                    $html .= "                   <tr>\n";
                    $html .= "                     <td align=\"LEFT\">\n";
                    $html .= "                      <LABEL class='normal_10AN'> LISTRA DE CARGOS ASOCIADOS</LABEL>";
                    $html .= "                     </td>\n";
                    $html .= "                   </tr>\n";
                    $html .= "                 </table>\n";
                    $html .= "                 <table class=\"modulo_table_list\" width=\"40%\" align=\"center\"  >\n";
                    $html .= "                   <tr class=\"formulacion_table_list\" >\n";
                    $html .= "                     <td  width='30%' align=\"center\">\n";
                    $html .= "                       <a title='TIPO DE RIESGO'>";
                    $html .= "                       CODIGO CARGO";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='60%' align=\"center\" id='".$td1."'>\n";
                    $html .= "                       <a title='COLOR TIPO DE RIESGO'\">";
                    $html .= "                         NOMBRE\n";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width=\"10%\" align=\"LEFT\">\n";
                    $html .= "                       EDITAR";
                    $html .= "                     </td>\n";
                    $html .= "                   </tr>\n";
                    //var_dump($agentes_riesgos);
            for($i=0;$i<count($lista_cargos);$i++)
            {       
                if($i%2==0)
                {
                    $estilo="class=\"modulo_list_claro\"";
                }
                else
                {
                     $estilo="class=\"modulo_list_oscuro\"";
                }

                    $html .= "                   <tr ".$estilo." >\n";
                    $html .= "                     <td align=\"center\">\n";
                    $html .= "                        ".$lista_cargos[$i]['cargo_ocupacion_id']."";
                    $html .= "                     </td>\n";
                    $html .= "                     <td align=\"left\">\n";
                    $html .= "                        ".$lista_cargos[$i]['descripcion']."";
                    $html .= "                     </td>\n";
                    $html .= "                     <td align=\"center\">\n";
                    $html .= "                             <a title='ACTUALIZAR DATOS DEL CARGO' href=\"javascript:MostrarCapa('ContenedorGrup');Actualizar('".$lista_cargos[$i]['cargo_ocupacion_id']."','".$lista_cargos[$i]['descripcion']."','".$lista_cargos[$i]['ocupacion_id']."');Iniciar2('ACTUALIZAR DATOS DEL CARGO')\">";
                    $html .= "                               <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $html .= "                             </a>";
                    $html .= "                     </td>\n";
                    $html .= "                   </tr>\n";
             }
            $html .= "                 </table>\n";
        }
        ELSE
        {
                    $html .= "                 <table width=\"40%\" align=\"center\"  >\n";
                    $html .= "                   <tr>\n";
                    $html .= "                     <td align=\"CENTER\">\n";
                    $html .= "                      <LABEL class='label_error'> ESTA OCUPACION NO TIENE CARGOS ASOCIADOS</LABEL>";
                    $html .= "                     </td>\n";
                    $html .= "                   </tr>\n";
                    $html .= "                 </table>\n";
        }
        $objResponse->assign("cargos_x_ocupacion1","innerHTML",$html);
        return $objResponse;
    }


    /**
    * funcion que sirve para asignar cargos a una ocupacion
    * @return string con la menu de opciones
    **/
    function TablaOcupacion()
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
        //$objResponse->alert('aaaaaa');
        $ocupaciones = $afi->ObtenerOcupaciones();

        
        if(!empty($ocupaciones))
        {
            $html .= "                 <table class=\"modulo_table_list\" width=\"40%\" align=\"center\">\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td class=\"formulacion_table_list\">\n";
            $html .= "                       OCUPACIONES\n";
            $html .= "                     <td>\n";
            $html .= "                       <select class=\"select\" name=\"ocupaciones\" id=\"ocupaciones\" onchange=\"llamarCargosSegunOcupacion(this.value);\">";
            $html .= "                         <option style='background-color:#ffffff;' value=\"-1\">SELECCIONAR</option> \n";
                //ocupacion_id     tipo_riesgo_id integer    agente_riesgo_id integer    usuario_registro integer    fecha_registro    sw_estado
            for($i=0;$i<count($ocupaciones);$i++)
            {
                $html .= "                           <option value=\"".$ocupaciones[$i]['ocupacion_id']."\">".$ocupaciones[$i]['descripcion']."</option> \n";
            }
            $html .= "                         </select>\n";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";
        }
        
        $objResponse->assign("cargos_x_ocupacion","innerHTML",$html);
        return $objResponse;
    }














    /**
    * funcion uer sirve para regisatrar los agentes de riesgo que se relacionan con un espacio.
    * @param string $espacio
    * @param string $tipo_riesgo
    * @param string $agente_riesgo
    * @param string $checar
    * @return string $cad
    **/
    function ValidarEspacioCheck($espacio,$tipo_riesgo,$agente_riesgo,$checar)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin","", "app","UV_SaludOcupacionalAdmin");
        $usuario=UserGetUID();
        $resultado = $afi->InsertarAgentePorEspacio($espacio,$tipo_riesgo,$agente_riesgo,$checar,$usuario);
        //var_dump($resultado);
        if($resultado[0]==1 && $resultado[1]===true)
        {
            $cad="AGENTE VINCULADO SATISFACTORIAMENTE";
            $objResponse->assign("error","innerHTML",$cad);
            
        }
        elseif($resultado[0]==0 && $resultado[1]===true)
        {
            $cad="AGENTE DESVINCULADO SATISFACTORIAMENTE";
            $objResponse->assign("error","innerHTML",$cad);
        }
        else
        {
            $cad=$afi->Error['MensajeError'];
            $objResponse->assign("error","innerHTML",$resultado[1].$cad.$resultado);
        }
    return $objResponse;

    }

    /**
    * funcion que sirve para asignar agentes de riesgo a espacios
    * @param array $tipos_de_riesgo
    * @param array $agentes_riesgo
    * @param array $agentesxocupacion
    * @return string con la menu de opciones
    **/
    function llamarAgentesSegunEspacio($espacio)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin","", "app","UV_SaludOcupacionalAdmin");
        $tipos_riesgos = $afi->ObtenerTiposDeRiesgos();
        $agentes_riesgos = $afi->ObtenerAgentesDeRiesgos();
        //var_dump($agentes_riesgos);
        $ag_x_es=$afi->Obteneragentes_de_riesgo_por_espacio($espacio);


        if(!empty($tipos_riesgos))
        {

                    $html .= "                 <table class=\"modulo_table_list\" width=\"70%\" align=\"center\"  >\n";
                    $html .= "                   <tr class=\"formulacion_table_list\" >\n";
                    $html .= "                     <td  width='35%' align=\"center\">\n";
                    $html .= "                       <a title='TIPO DE RIESGO'>";
                    $html .= "                       TIPO DE RIESGO";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='5%' align=\"center\" id='".$td1."'>\n";
                    $html .= "                       <a title='COLOR TIPO DE RIESGO'\">";
                    $html .= "                         COLOR \n";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='55%' align=\"center\" id='".$td1."'>\n";
                    $html .= "                         AGENTES DE RIESGOS \n";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width=\"5%\" align=\"LEFT\">\n";
                    $html .= "                       SELECCIONAR";
                    $html .= "                     </td>\n";
                    $html .= "                   </tr>\n";
                    //var_dump($agentes_riesgos);
            for($i=0;$i<count($tipos_riesgos);$i++)
            {       
                if($i%2==0)
                {
                    $estilo="class=\"modulo_list_claro\"";
                }
                else
                {
                     $estilo="class=\"modulo_list_oscuro\"";
                }
                    $cuantos=1;
                    $ban=0;
                    

                    $html .= "                   <tr ".$estilo.">\n";
                foreach($agentes_riesgos as $key=>$valor)
                {
                       
                   foreach($valor as $key=>$valor1)
                   {
                        
                        if($tipos_riesgos[$i]['tipo_riesgo_id']==$valor1['tipo_riesgo_id'])
                        {
                            if($ban==1)
                            {
                                $cuantos++;
                            }
                            if($cuantos>1)
                            {
                                $html2 .= "                   <tr ".$estilo.">\n";
                            }
                             
                            $html2 .= "                     <td  align=\"LEFT\">\n";
                            $html2 .= "                      ".$valor1['descripcion']."";
                            $html2 .= "                     </td>\n";
                            $html2 .= "                     <td  id='Agente".$valor1['agente_riesgo_id']."' align=\"center\">\n";
                            $html2 .= "                         <input type=\"checkbox\" name=\"".$valor1['tipo_riesgo_id']."\" id=\"".$valor1['agente_riesgo_id']."\" onclick='ValidarEspacio(this);' value=\"1\">";
                            $html2 .= "                     </td>\n";
                            $html2 .= "                   </tr>\n";                    
                            $ban=1;
                        }
                   }

                }
                    $html .= "                     <td rowspan='".$cuantos."' class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $html .= "                        ".$tipos_riesgos[$i]['descripcion']."";
                    $html .= "                     </td>\n";
                    $html .= "                     <td rowspan='".$cuantos."' BGCOLOR='".$tipos_riesgos[$i]['color']."' Align=\"center\">\n";
                    $html .= "                     </td>\n";
                   // echo $cuantos;
                    if($ban==0)
                    {
                        $html1 .= "                     <td colspan='2' align=\"center\">\n";
                        $html1 .= "                       <label class='label_error'>ESTE TIPO DE RIESGO NO CONTIENE AGENTE DE RIESGO ASIGNADOS</label>";

                        $html1 .= "                     </td>\n";
                        $html1 .= "                     </tr>\n";
                        $html.=$html1;
                        $html1='';
                    }
                    elseif($ban==1)
                    {   
                        $html.=$html2;
                        $html2='';
                    }

            }
            
            $html .= "                 </table>\n";

        }

        $objResponse->assign("agentes_x_espacio1","innerHTML",$html);

        //var_dump($ag_x_tr);
          for($i=0;$i<count($ag_x_es);$i++)
          {
              $objResponse->assign($ag_x_es[$i]['agente_riesgo_id'],"checked","true");
          }


        return $objResponse;
    }



    /**
    * funcion que sirve para asignar agentes de riesgo una ocupacion
    * @return string con la menu de opciones
    **/
    function TablaAgentesXEspacios()
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
        $espacios = $afi->ObtenerEspacios();
        if(!empty($espacios))
        {

            $html .= "                 <table class=\"modulo_table_list\" width=\"40%\" align=\"center\">\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td class=\"formulacion_table_list\">\n";
            $html .= "                       ESPACIOS\n";
            $html .= "                     <td>\n";
            $html .= "                       <select class=\"select\" name=\"espacios_x\" id=\"espacios_x\" onchange=\"llamarAgentesSegunEspacio(this.value);\">";
            $html .= "                         <option style='background-color:#ffffff;' value=\"-1\">SELECCIONAR</option> \n";
                //ocupacion_id     tipo_riesgo_id integer    agente_riesgo_id integer    usuario_registro integer    fecha_registro    sw_estado
            for($i=0;$i<count($espacios);$i++)
            {
                $html .= "                           <option value=\"".$espacios[$i]['tipo_espacio_id']."\">".$espacios[$i]['descripcion']."</option> \n";
            }
            $html .= "                         </select>\n";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";
        }
            $html .= "                     </table>\n";
        $objResponse->assign("agentes_x_espacio","innerHTML",$html);
        return $objResponse;
    }



    /**
    * funcion uer sirve para regisatrar los agentes de riesgo que se relacionan con una ocuapcion.
    * @param string $ocupacion
    * @param string $tipo_riesgo
    * @param string $agente_riesgo
    * @param string $checar
    * @return string $cad
    **/
    function ValidarCheck($ocupacion,$tipo_riesgo,$agente_riesgo,$checar)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin","", "app","UV_SaludOcupacionalAdmin");
        $usuario=UserGetUID();
        $resultado = $afi->InsertarAgentePorOcupacion($ocupacion,$tipo_riesgo,$agente_riesgo,$checar,$usuario);
        //var_dump($resultado);
        if($resultado[0]==1 && $resultado[1]===true)
        {
            $cad="AGENTE VINCULADO SATISFACTORIAMENTE";
            $objResponse->assign("error","innerHTML",$cad);
            
        }
        elseif($resultado[0]==0 && $resultado[1]===true)
        {
            $cad="AGENTE DESVINCULADO SATISFACTORIAMENTE";
            $objResponse->assign("error","innerHTML",$cad);
        }
        else
        {
            $cad=$afi->Error['MensajeError'];
            $objResponse->assign("error","innerHTML",$cad.$resultado);
        }
    return $objResponse;

    }



    /**
    * funcion que sirve para asignar agentes de riesgo una ocupacion
    * @param array $tipos_de_riesgo
    * @param array $agentes_riesgo
    * @param array $agentesxocupacion
    * @return string con la menu de opciones
    **/
    function llamarAgentesSegunOcupacion($ocupacion)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin","", "app","UV_SaludOcupacionalAdmin");
        $tipos_riesgos = $afi->ObtenerTiposDeRiesgos();
        $agentes_riesgos = $afi->ObtenerAgentesDeRiesgos();
        //var_dump($agentes_riesgos);
        $ag_x_tr=$afi->Obteneragentes_de_riesgo_por_ocupacion($ocupacion);


        if(!empty($tipos_riesgos))
        {

                    $html .= "                 <table class=\"modulo_table_list\" width=\"70%\" align=\"center\"  >\n";
                    $html .= "                   <tr class=\"formulacion_table_list\" >\n";
                    $html .= "                     <td  width='35%' align=\"center\">\n";
                    $html .= "                       <a title='TIPO DE RIESGO'>";
                    $html .= "                       TIPO DE RIESGO";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='5%' align=\"center\" id='".$td1."'>\n";
                    $html .= "                       <a title='COLOR TIPO DE RIESGO'\">";
                    $html .= "                         COLOR \n";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='55%' align=\"center\" id='".$td1."'>\n";
                    $html .= "                         AGENTES DE RIESGOS \n";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width=\"5%\" align=\"LEFT\">\n";
                    $html .= "                       SELECCIONAR";
                    $html .= "                     </td>\n";
                    $html .= "                   </tr>\n";
                    //var_dump($agentes_riesgos);
            for($i=0;$i<count($tipos_riesgos);$i++)
            {       
                if($i%2==0)
                {
                    $estilo="class=\"modulo_list_claro\"";
                }
                else
                {
                     $estilo="class=\"modulo_list_oscuro\"";
                }
                    $cuantos=1;
                    $ban=0;
                    

                    $html .= "                   <tr ".$estilo.">\n";
                foreach($agentes_riesgos as $key=>$valor)
                {
                       
                   foreach($valor as $key=>$valor1)
                   {
                        
                        if($tipos_riesgos[$i]['tipo_riesgo_id']==$valor1['tipo_riesgo_id'])
                        {
                            if($ban==1)
                            {
                                $cuantos++;
                            }
                            if($cuantos>1)
                            {
                                $html2 .= "                   <tr ".$estilo.">\n";
                            }
                             
                            $html2 .= "                     <td  align=\"LEFT\">\n";
                            $html2 .= "                      ".$valor1['descripcion']."";
                            $html2 .= "                     </td>\n";
                            $html2 .= "                     <td  id='Agente".$valor1['agente_riesgo_id']."' align=\"center\">\n";
                            $html2 .= "                         <input type=\"checkbox\" name=\"".$valor1['tipo_riesgo_id']."\" id=\"".$valor1['agente_riesgo_id']."\" onclick='Validar(this);' value=\"1\">";
                            $html2 .= "                     </td>\n";
                            $html2 .= "                   </tr>\n";                    
                            $ban=1;
                        }
                   }

                }
                    $html .= "                     <td rowspan='".$cuantos."' class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $html .= "                        ".$tipos_riesgos[$i]['descripcion']."";
                    $html .= "                     </td>\n";
                    $html .= "                     <td rowspan='".$cuantos."' BGCOLOR='".$tipos_riesgos[$i]['color']."' Align=\"center\">\n";
                    $html .= "                     </td>\n";
                   // echo $cuantos;
                    if($ban==0)
                    {
                        $html1 .= "                     <td colspan='2' align=\"center\">\n";
                        $html1 .= "                       <label class='label_error'>ESTE TIPO DE RIESGO NO CONTIENE AGENTE DE RIESGO ASIGNADOS</label>";

                        $html1 .= "                     </td>\n";
                        $html1 .= "                     </tr>\n";
                        $html.=$html1;
                        $html1='';
                    }
                    elseif($ban==1)
                    {   
                        $html.=$html2;
                        $html2='';
                    }

            }
            
            $html .= "                 </table>\n";

        }

        $objResponse->assign("agentes_x_ocupacion1","innerHTML",$html);

        //var_dump($ag_x_tr);
          for($i=0;$i<count($ag_x_tr);$i++)
          {
              $objResponse->assign($ag_x_tr[$i]['agente_riesgo_id'],"checked","true");
          }


        return $objResponse;
    }



    
    /**
    * funcion que sirve para asignar agentes de riesgo una ocupacion
    * @return string con la menu de opciones
    **/
    function TablaAgentesXOcupacion()
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
        
        $ocupaciones = $afi->ObtenerOcupaciones();

        
        if(!empty($ocupaciones))
        {

            $html .= "                 <table class=\"modulo_table_list\" width=\"40%\" align=\"center\">\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td class=\"formulacion_table_list\">\n";
            $html .= "                       OCUPACIONES\n";
            $html .= "                     <td>\n";
            $html .= "                       <select class=\"select\" name=\"ocupaciones\" id=\"ocupaciones\" onchange=\"llamarAgentesSegunOcupacion(this.value);\">";
            $html .= "                         <option style='background-color:#ffffff;' value=\"-1\">SELECCIONAR</option> \n";
                //ocupacion_id     tipo_riesgo_id integer    agente_riesgo_id integer    usuario_registro integer    fecha_registro    sw_estado
            for($i=0;$i<count($ocupaciones);$i++)
            {
                $html .= "                           <option value=\"".$ocupaciones[$i]['ocupacion_id']."\">".$ocupaciones[$i]['descripcion']."</option> \n";
            }
            $html .= "                         </select>\n";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";
        }
        
        $objResponse->assign("agentes_x_ocupacion","innerHTML",$html);
        return $objResponse;
    }

  /**
  * Archivo Ajax (ConsultaxAfiliados)
  * Archivo que contiene funciones las cuales permiten conectarse con la BD por medio de xajax lo que permite no recargar la pagina para obtener una consulta
  *
  * @version $Id: ConsultaxAfiliados.php,v 1.8 2007/11/09 13:56:03 jgomez Exp $   
  * @package IPSOFT-SIIS
  * @author Jaime Gomez  
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  
  */

        function CambiarEstado($agente_riesgo_id,$tipos_riesgo,$agente_de_riesgo_nom,$sw_estado,$td)
        {
            $path = SessionGetVar("rutaImagenes");
            $objResponse = new xajaxResponse();
            list($tipos_riesgo,$color) = split( '-', $tipos_riesgo);
                
                 if(!empty($tipos_riesgo) && $agente_de_riesgo_nom!='')
                 {
         
                     $cot = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
                     $usuario=UserGetUID();
                     if($sw_estado=='1')
                     {
                        $sw_estado=0;
                        $html2 = "                             <a title='CAMBIAR ESTADO DEL AGENTE DE RIESGO' href=\"javascript:CambiarEstado('".$agente_riesgo_id."','".$tipos_riesgo."','".$agente_de_riesgo_nom."','".$sw_estado."','".$td."');\">";
                        $html2 .= "                               <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                        $html2 .= "                             </a>";
                     }
                     elseif($sw_estado=='0')
                     {
                        $sw_estado=1;
                        $html2 = "                             <a title='CAMBIAR ESTADO DEL AGENTE DE RIESGO' href=\"javascript:CambiarEstado('".$agente_riesgo_id."','".$tipos_riesgo."','".$agente_de_riesgo_nom."','".$sw_estado."','".$td."');\">";
                        $html2 .= "                               <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                        $html2 .= "                             </a>";
                     }
                        
                     
                     $resultado=$cot->UpdateAgenteDeRiesgos($agente_riesgo_id,$tipos_riesgo,$agente_de_riesgo_nom,$sw_estado,$usuario);
         
                     if($resultado===true)
                     {
                         $objResponse->assign($td,"innerHTML",$html2);
                         //$objResponse->call("VentanaClose");
                        // $objResponse->call("PintarTiposAgentes");
                     }
                }

            return $objResponse;
        }
        /**
        * Funcion que sirve para la actalizacion de agentes de riesgo
        * @param string $agente_riesgo_id
        * @param string $tipos_riesgo
        * @param string $agente_de_riesgo_nom
        * @param string $sw_estado
        * @return string con el mensaje de transaccion exitosa o fallida
        **/
         function ActuaAgenteRiesgoBD($agente_riesgo_id,$tipos_riesgo,$agente_de_riesgo_nom,$sw_estado)
         {
                  //$agente_riesgo_id,$tipos_riesgo,$agente_de_riesgo_nom,$usuario  
                $objResponse = new xajaxResponse();

                list($tipos_riesgo,$color) = split( '-', $tipos_riesgo);
                
                 if(!empty($tipos_riesgo) && $agente_de_riesgo_nom!='')
                 {
         
                     $cot = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
                     $usuario=UserGetUID();
                     $resultado=$cot->UpdateAgenteDeRiesgos($agente_riesgo_id,$tipos_riesgo,$agente_de_riesgo_nom,$sw_estado,$usuario);
         
                     if($resultado===true)
                     {
                         $cad="AGENTE ACTUALIZADO SATISFACTORIAMENTE";
                         $objResponse->assign("error","innerHTML",$cad);
                         $objResponse->call("VentanaClose");
                         $objResponse->call("PintarTiposAgentes");
                     }
                     else
                     {
                         $cad=$cot->Error['MensajeError'];
                         $objResponse->assign("errorGrup","innerHTML",$cad.$resultado);
                     }
         
                 }
                 else
                 {
                     if($tipos_riesgo=='')
                     {
                         $objResponse->assign("errorGrup","innerHTML","FALTA SELECCIONAR EL TIPO DE RIESGO");
                     }
                     elseif($agente_de_riesgo_nom=='')
                     {
                         $objResponse->assign("errorGrup","innerHTML","FALTA EL NOMBRE DEL AGENTE DE RIESGO");
                     }
         
                 }
                return $objResponse;

        }



        
        /**
        * Funcion que sirve para registrar en la base de datos un agente de riesgos
        * @param string $tipos_riesgo
        * @param string $agente_de_riesgo
        * @param string $usuario
        * @return string con el mensaje de transaccion exitosa o fallida
        **/
        function EditarInfoAgente($agente_riesgo_id,$tipo_riesgo_id,$descripcion,$sw_estado)
        {
            $objResponse = new xajaxResponse();
                    $path = SessionGetVar("rutaImagenes");
                    
            $objResponse = new xajaxResponse();
            $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
            $salida .= "                     <td width='30%' align=\"center\">\n";
            $salida .= "                       SELECCIONAR TIPO DE RIESGO";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='70%' align=\"left\">\n";
            $cot = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
            $usuario=UserGetUID();
            $tipos_de_riesgo=$cot->ObtenerTiposDeRiesgos();
                    
            if(!empty($tipos_de_riesgo))
            {
                $salida .= "                         <select class=\"select\" name=\"tipos_riesgo\" id=\"tipos_riesgo\" onchange=\"llamar(this.value);\">";
                $salida .= "                           <option style='background-color:#ffffff;' value=\"-1\">SELECCIONAR</option> \n";
    
                // tipo_riesgo_id  descripcion     color   sw_estado   fecha_registro  usuario_registro
    
                for($i=0;$i<count($tipos_de_riesgo);$i++)
                {
                    if($tipo_riesgo_id==$tipos_de_riesgo[$i]['tipo_riesgo_id'])
                    {
                        $salida .= "                           <option style='background-color:".$tipos_de_riesgo[$i]['color'].";' value=\"".$tipos_de_riesgo[$i]['tipo_riesgo_id']."-".$tipos_de_riesgo[$i]['color']."\" selected>".$tipos_de_riesgo[$i]['descripcion']."</option> \n";
                    }
                    else
                    {
                        $salida .= "                           <option style='background-color:".$tipos_de_riesgo[$i]['color'].";' value=\"".$tipos_de_riesgo[$i]['tipo_riesgo_id']."-".$tipos_de_riesgo[$i]['color']."\">".$tipos_de_riesgo[$i]['descripcion']."</option> \n";
                    }
    
                }
    
                $salida .= "                         </select>\n";
    
    
    
            }
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
            $salida .= "                     <td  align=\"center\">\n";
            $salida .= "                       NOMBRE DEL AGENTE DE RIESGO";
            $salida .= "                     </td>\n";
            $salida .= "                     <td  align=\"center\">\n";
            $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"agente_de_riesgo\" id=\"agente_de_riesgo\" value=\"".$descripcion."\"  size=\"50\" onkeypress=\"\">\n";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   <tr>\n";
            $salida .= "                     <td colspan='2' align=\"CENTER\">\n";
            $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"Actualizar Agentede Riesgo\" onclick=\"xajax_ActuaAgenteRiesgoBD('".$agente_riesgo_id."',document.getElementById('tipos_riesgo').value,document.getElementById('agente_de_riesgo').value,'".$sw_estado."');\">\n";
            $salida .= "                     </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                 </table>\n";
            $objResponse->assign("ContenidoGrup","innerHTML",$salida);
            return $objResponse;

    
    
        } 

    /**
    * Funcion que sirve para registrar en la base de datos un agente de riesgos
    * @param string $tipos_riesgo
    * @param string $agente_de_riesgo
    * @param string $usuario
    * @return string con el mensaje de transaccion exitosa o fallida
    **/
    function CrearAgenteRiesgoBD($tipos_riesgo,$agente_de_riesgo,$usuario)
    {
        $objResponse = new xajaxResponse();
        //$objResponse->alert($tipos_riesgo);
        list($tipos_riesgo,$color) = split( '-', $tipos_riesgo);
        
        if($tipos_riesgo!='0' && $color!='')
        {

            $cot = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
            $usuario=UserGetUID();
            $resultado=$cot->InsertarAgenteDeRiesgos($tipos_riesgo,$agente_de_riesgo,$usuario);

            if($resultado===true)
            {
                $cad="AGENTE REGISTRADO SATISFACTORIAMENTE";
                $objResponse->assign("error","innerHTML",$cad);
                $objResponse->call("VentanaClose");
                $objResponse->call("PintarTiposAgentes");
            }
            else
            {
                $cad=$cot->Error['MensajeError'];
                $objResponse->assign("errorGrup","innerHTML",$cad.$resultado);
            }

        }
        else
        {
            if($tipos_riesgo=='')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA SELECCIONAR EL TIPO DE RIESGO");
            }
            elseif($agente_de_riesgo=='')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA EL NOMBRE DEL AGENTE DE RIESGO");
            }

        }
        return $objResponse;
    }



    /**
    * Funcion que se utiliza para mostrar el menu de creacion de agentes de riesgo
    * @return string con el menu de creacion del agente de riesgo.
    **/
    function CrearAgenteRiesgos()
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
        $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
        $salida .= "                     <td width='30%' align=\"center\">\n";
        $salida .= "                       SELECCIONAR TIPO DE RIESGO";
        $salida .= "                     </td>\n";
        $salida .= "                     <td width='70%' align=\"left\">\n";
        $cot = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
        $usuario=UserGetUID();
        $tipos_de_riesgo=$cot->ObtenerTiposDeRiesgos();
        
    if(!empty($tipos_de_riesgo))
    {
        $salida .= "                         <select class=\"select\" name=\"tipos_riesgo\" id=\"tipos_riesgo\" onchange=\"llamar(this.value);\">";
        $salida .= "                           <option style='background-color:#ffffff;' value=\"-1\" selected>SELECCIONAR</option> \n";

           // tipo_riesgo_id  descripcion     color   sw_estado   fecha_registro  usuario_registro
            
        for($i=0;$i<count($tipos_de_riesgo);$i++)
        {
            $salida .= "                           <option style='background-color:".$tipos_de_riesgo[$i]['color'].";' value=\"".$tipos_de_riesgo[$i]['tipo_riesgo_id']."-".$tipos_de_riesgo[$i]['color']."\">".$tipos_de_riesgo[$i]['descripcion']."</option> \n";

        }

        $salida .= "                         </select>\n";



    }
        $salida .= "                     </td>\n";
        $salida .= "                   </tr>\n";
        $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
        $salida .= "                     <td  align=\"center\">\n";
        $salida .= "                       NOMBRE DEL AGENTE DE RIESGO";
        $salida .= "                     </td>\n";
        $salida .= "                     <td  align=\"center\">\n";
        $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"agente_de_riesgo\" id=\"agente_de_riesgo\" value=\"\"  size=\"50\" onkeypress=\"\">\n";
        $salida .= "                     </td>\n";
        $salida .= "                   </tr>\n";
        $salida .= "                   <tr>\n";
        $salida .= "                     <td colspan='2' align=\"CENTER\">\n";
        $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"Crear Agente de Riesgo\" onclick=\"xajax_CrearAgenteRiesgoBD(document.getElementById('tipos_riesgo').value,document.getElementById('agente_de_riesgo').value,'".$usuario."');\">\n";
        $salida .= "                     </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                 </table>\n";
        $objResponse->assign("ContenidoGrup","innerHTML",$salida);
        return $objResponse;
    }
    
    /**
    * Funcion que sirve para editar la informacion de un tipo de riesgo en la base de datos
    * @param string $tipo_riesgo_id
    * @param string $descripcion
    * @param string $color
    * @param string $usuario_registro
    * @return string con el mensaje de transaccion exitosa o fallida
    **/
    function ActualizarTipoAgente($tipo_riesgo_id,$tipo_riesgo,$color,$usuario_registro)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        if($tipo_riesgo!='' && $color!='')
        {
        
            $cot = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
            $usuario=UserGetUID();
            $resultado=$cot->UpdateTiposDeRiesgos($tipo_riesgo_id,$tipo_riesgo,$color,$usuario_registro);
    
            if($resultado===true)
            {
                $cad="TIPO DE AGENTE ACTUALIZADO SATISFACTORIAMENTE";
                $objResponse->assign("error","innerHTML",$cad);
                $objResponse->call("VentanaClose");
                $objResponse->call("PintarTiposAgentes");
            }
            else
            {
                $cad=$cot->Error['MensajeError'];
                $objResponse->assign("errorGrup","innerHTML",$cad.$resultado);
            }

        }
        else
        {
            if($tipo_riesgo=='')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA EL NOMBRE DEL TIPO DE RIESGO");
            }
            elseif($color=='')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA SELECCIONAR EL COLOR DEL TIPO DE RIESGO");
            }
    
        }
        return $objResponse;



    }

    /**
    * Funcion que sirve para editar la informacion de un tipo de riesgo
    * @param string $tipo_riesgo_id
    * @param string $descripcion
    * @param string $color
    * @param string $usuario_registro
    * @return string con el mensaje de transaccion exitosa o fallida
    **/
    function EditarInfo($tipo_riesgo_id,$descripcion,$color,$usuario_registro)
    {
        
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
        $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
        $salida .= "                     <td width='30%' align=\"center\">\n";
        $salida .= "                       NOMBRE TIPO DE RIESGO";
        $salida .= "                     </td>\n";
        $salida .= "                     <td width='70%' align=\"center\">\n";
        $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"tipo_riesgo\" id=\"tipo_riesgo\" value=\"".$descripcion."\"  size=\"50\" onkeypress=\"\">\n";//return acceptNum(event)
        $salida .= "                       <input type=\"hidden\" name=\"tipo_riesgo_id\" id=\"tipo_riesgo_id\" value=\"".$tipo_riesgo_id."\">\n";
        $salida .= "                     </td>\n";
        $salida .= "                   </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "<div id='colorex' style='display:none;'>";
        $salida .= "                   <table class=\"modulo_table_list\" width=\"90%\" align='center'>\n";
        $salida .= "                   <tr>\n";
        $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
        $salida .= "                       SELECCIONAR COLOR";
        $salida .= "                     </td>\n";
       // $salida .= "                 </table>\n";
        //$salida .= "                 <table width=\"90%\" align=\"center\">\n";
        $salida .= "                     <td width='40%' align=\"center\">\n";
        //$salida .= "                       <a title='VER GRUPO FAMILIAR'>";
        //$salida .= "                       VER GRUPO";


    
    /*$salida .= "<div class='floatingWindowContent'>";
    $salida .= "<div  id='#F0F8FF' class='smallColorSquare' onclick=\"colorClick('#F0F8FF')\" title='AliceBlue' style='background-color:#F0F8FF'><span></span></div>";
    $salida .= "<div  id='#FAEBD7' class='smallColorSquare' onclick=\"colorClick('#FAEBD7')\" title='AntiqueWhite' style='background-color:#FAEBD7'><span></span></div>";
    $salida .= "<div  id='#00FFFF' class='smallColorSquare' onclick=\"colorClick('#00FFFF')\" title='Aqua and Cyan' style='background-color:#00FFFF'><span></span></div>";
    $salida .= "<div  id='#7FFFD4' class='smallColorSquare' onclick=\"colorClick('#7FFFD4')\" title='Aquamarine' style='background-color:#7FFFD4'><span></span></div>";
    $salida .= "<div  id='#F0FFFF' class='smallColorSquare' onclick=\"colorClick('#F0FFFF')\" title='Azure' style='background-color:#F0FFFF'><span></span></div>";
    $salida .= "<div  id='#F5F5DC' class='smallColorSquare' onclick=\"colorClick('#F5F5DC')\" title='Beige' style='background-color:#F5F5DC'><span></span></div>";
    $salida .= "<div  id='#FFE4C4' class='smallColorSquare' onclick=\"colorClick('#FFE4C4')\" title='Bisque' style='background-color:#FFE4C4'><span></span></div>";
    $salida .= "<div  id='#000000' class='smallColorSquare' onclick=\"colorClick('#000000')\" title='Black' style='background-color:#000000'><span></span></div>";
    $salida .= "<div  id='#FFEBCD' class='smallColorSquare' onclick=\"colorClick('#FFEBCD')\" title='BlanchedAlmond' style='background-color:#FFEBCD'><span></span></div>";
    $salida .= "<div  id='#0000FF' class='smallColorSquare' onclick=\"colorClick('#0000FF')\" title='Blue' style='background-color:#0000FF'><span></span></div>";
    $salida .= "<div  id='#8A2BE2' class='smallColorSquare' onclick=\"colorClick('#8A2BE2')\" title='BlueViolet' style='background-color:#8A2BE2'><span></span></div>";
    $salida .= "<div  id='#A52A2A' class='smallColorSquare' onclick=\"colorClick('#A52A2A')\" title='Brown' style='background-color:#A52A2A'><span></span></div>";
    $salida .= "<div  id='#DEB887' class='smallColorSquare' onclick=\"colorClick('#DEB887')\" title='BurlyWood' style='background-color:#DEB887'><span></span></div>";
    $salida .= "<div  id='#5F9EA0' class='smallColorSquare' onclick=\"colorClick('#5F9EA0')\" title='CadetBlue' style='background-color:#5F9EA0'><span></span></div>";
    $salida .= "<div  id='#7FFF00' class='smallColorSquare' onclick=\"colorClick('#7FFF00')\" title='Chartreuse' style='background-color:#7FFF00'><span></span></div>";
    $salida .= "<div  id='#D2691E' class='smallColorSquare' onclick=\"colorClick('#D2691E')\" title='Chocolate' style='background-color:#D2691E'><span></span></div>";
    $salida .= "<div  id='#FF7F50' class='smallColorSquare' onclick=\"colorClick('#FF7F50')\" title='Coral' style='background-color:#FF7F50'><span></span></div>";
    $salida .= "<div  id='#6495ED' class='smallColorSquare' onclick=\"colorClick('#6495ED')\" title='CornflowerBlue' style='background-color:#6495ED'><span></span></div>";
    $salida .= "<div  id='#FFF8DC' class='smallColorSquare' onclick=\"colorClick('#FFF8DC')\" title='Cornsilk' style='background-color:#FFF8DC'><span></span></div>";
    $salida .= "<div  id='#DC143C' class='smallColorSquare' onclick=\"colorClick('#DC143C')\" title='Crimson' style='background-color:#DC143C'><span></span></div>";
    $salida .= "<div  id='#00008B' class='smallColorSquare' onclick=\"colorClick('#00008B')\" title='DarkBlue' style='background-color:#00008B'><span></span></div>";
    $salida .= "<div  id='#008B8B' class='smallColorSquare' onclick=\"colorClick('#008B8B')\" title='DarkCyan' style='background-color:#008B8B'><span></span></div>";
    $salida .= "<div  id='#B8860B' class='smallColorSquare' onclick=\"colorClick('#B8860B')\" title='DarkGoldenRod' style='background-color:#B8860B'><span></span></div>";
    $salida .= "<div  id='#A9A9A9' class='smallColorSquare' onclick=\"colorClick('#A9A9A9')\" title='DarkGray' style='background-color:#A9A9A9'><span></span></div>";
    $salida .= "<div  id='#006400' class='smallColorSquare' onclick=\"colorClick('#006400')\" title='DarkGreen' style='background-color:#006400'><span></span></div>";
    $salida .= "<div  id='#BDB76B' class='smallColorSquare' onclick=\"colorClick('#BDB76B')\" title='DarkKhaki' style='background-color:#BDB76B'><span></span></div>";
    $salida .= "<div  id='#8B008B' class='smallColorSquare' onclick=\"colorClick('#8B008B')\" title='DarkMagenta' style='background-color:#8B008B'><span></span></div>";
    $salida .= "<div  id='#556B2F' class='smallColorSquare' onclick=\"colorClick('#556B2F')\" title='DarkOliveGreen' style='background-color:#556B2F'><span></span></div>";
    $salida .= "<div  id='#FF8C00' class='smallColorSquare' onclick=\"colorClick('#FF8C00')\" title='Darkorange' style='background-color:#FF8C00'><span></span></div>";
    $salida .= "<div  id='#9932CC' class='smallColorSquare' onclick=\"colorClick('#9932CC')\" title='DarkOrchid' style='background-color:#9932CC'><span></span></div>";
    $salida .= "<div  id='#8B0000' class='smallColorSquare' onclick=\"colorClick('#8B0000')\" title='DarkRed' style='background-color:#8B0000'><span></span></div>";
    $salida .= "<div  id='#E9967A' class='smallColorSquare' onclick=\"colorClick('#E9967A')\" title='DarkSalmon' style='background-color:#E9967A'><span></span></div>";
    $salida .= "<div  id='#8FBC8F' class='smallColorSquare' onclick=\"colorClick('#8FBC8F')\" title='DarkSeaGreen' style='background-color:#8FBC8F'><span></span></div>";
    $salida .= "<div  id='#483D8B' class='smallColorSquare' onclick=\"colorClick('#483D8B')\" title='DarkSlateBlue' style='background-color:#483D8B'><span></span></div>";
    $salida .= "<div  id='#2F4F4F' class='smallColorSquare' onclick=\"colorClick('#2F4F4F')\" title='DarkSlateGray' style='background-color:#2F4F4F'><span></span></div>";
    $salida .= "<div  id='#00CED1' class='smallColorSquare' onclick=\"colorClick('#00CED1')\" title='DarkTurquoise' style='background-color:#00CED1'><span></span></div>";
    $salida .= "<div  id='#9400D3' class='smallColorSquare' onclick=\"colorClick('#9400D3')\" title='DarkViolet' style='background-color:#9400D3'><span></span></div>";
    $salida .= "<div  id='#FF1493' class='smallColorSquare' onclick=\"colorClick('#FF1493')\" title='DeepPink' style='background-color:#FF1493'><span></span></div>";
    $salida .= "<div  id='#00BFFF' class='smallColorSquare' onclick=\"colorClick('#00BFFF')\" title='DeepSkyBlue' style='background-color:#00BFFF'><span></span></div>";
    $salida .= "<div  id='#696969' class='smallColorSquare' onclick=\"colorClick('#696969')\" title='DimGray' style='background-color:#696969'><span></span></div>";
    $salida .= "<div  id='#1E90FF' class='smallColorSquare' onclick=\"colorClick('#1E90FF')\" title='DodgerBlue' style='background-color:#1E90FF'><span></span></div>";
    $salida .= "<div  id='#D19275' class='smallColorSquare' onclick=\"colorClick('#D19275')\" title='Feldspar' style='background-color:#D19275'><span></span></div>";
    $salida .= "<div  id='#B22222' class='smallColorSquare' onclick=\"colorClick('#B22222')\" title='FireBrick' style='background-color:#B22222'><span></span></div>";
    $salida .= "<div  id='#FFFAF0' class='smallColorSquare' onclick=\"colorClick('#FFFAF0')\" title='FloralWhite' style='background-color:#FFFAF0'><span></span></div>";
    $salida .= "<div  id='#228B22' class='smallColorSquare' onclick=\"colorClick('#228B22')\" title='ForestGreen' style='background-color:#228B22'><span></span></div>";
    $salida .= "<div  id='#FF00FF' class='smallColorSquare' onclick=\"colorClick('#FF00FF')\" title='Fuchsia' style='background-color:#FF00FF'><span></span></div>";
    $salida .= "<div  id='#DCDCDC' class='smallColorSquare' onclick=\"colorClick('#DCDCDC')\" title='Gainsboro' style='background-color:#DCDCDC'><span></span></div>";
    $salida .= "<div  id='#F8F8FF' class='smallColorSquare' onclick=\"colorClick('#F8F8FF')\" title='GhostWhite' style='background-color:#F8F8FF'><span></span></div>";
    $salida .= "<div  id='#FFD700' class='smallColorSquare' onclick=\"colorClick('#FFD700')\" title='Gold' style='background-color:#FFD700'><span></span></div>";
    $salida .="<div  id='#DAA520' class='smallColorSquare' onclick=\"colorClick('#DAA520')\" title='GoldenRod' style='background-color:#DAA520'><span></span></div>";
    $salida .="<div  id='#808080' class='smallColorSquare' onclick=\"colorClick('#808080')\" title='Gray' style='background-color:#808080'><span></span></div>";
    $salida .="<div  id='#008000' class='smallColorSquare' onclick=\"colorClick('#008000')\" title='Green' style='background-color:#008000'><span></span></div>";
    $salida .="<div  id='#ADFF2F' class='smallColorSquare' onclick=\"colorClick('#ADFF2F')\" title='GreenYellow' style='background-color:#ADFF2F'><span></span></div>";
    $salida .="<div  id='#F0FFF0' class='smallColorSquare' onclick=\"colorClick('#F0FFF0')\" title='HoneyDew' style='background-color:#F0FFF0'><span></span></div>";
    $salida .="<div  id='#FF69B4' class='smallColorSquare' onclick=\"colorClick('#FF69B4')\" title='HotPink' style='background-color:#FF69B4'><span></span></div>";
    $salida .="<div  id='#CD5C5C' class='smallColorSquare' onclick=\"colorClick('#CD5C5C')\" title='IndianRed' style='background-color:#CD5C5C'><span></span></div>";
    $salida .="<div  id='#4B0082' class='smallColorSquare' onclick=\"colorClick('#4B0082')\" title='Indigo' style='background-color:#4B0082'><span></span></div>";
    $salida .="<div  id='#FFFFF0' class='smallColorSquare' onclick=\"colorClick('#FFFFF0')\" title='Ivory' style='background-color:#FFFFF0'><span></span></div>";
    $salida .="<div  id='#F0E68C' class='smallColorSquare' onclick=\"colorClick('#F0E68C')\" title='Khaki' style='background-color:#F0E68C'><span></span></div>";
    $salida .="<div  id='#E6E6FA' class='smallColorSquare' onclick=\"colorClick('#E6E6FA')\" title='Lavender' style='background-color:#E6E6FA'><span></span></div>";
    $salida .="<div  id='#FFF0F5' class='smallColorSquare' onclick=\"colorClick('#FFF0F5')\" title='LavenderBlush' style='background-color:#FFF0F5'><span></span></div>";
    $salida .="<div  id='#7CFC00' class='smallColorSquare' onclick=\"colorClick('#7CFC00')\" title='LawnGreen' style='background-color:#7CFC00'><span></span></div>";
    $salida.=" <div  id='#FFFACD' class='smallColorSquare' onclick=\"colorClick('#FFFACD')\" title='LemonChiffon' style='background-color:#FFFACD'><span></span></div>";
    $salida.=" <div  id='#ADD8E6' class='smallColorSquare' onclick=\"colorClick('#ADD8E6')\" title='LightBlue' style='background-color:#ADD8E6'><span></span></div>";
    $salida.=" <div  id='#F08080' class='smallColorSquare' onclick=\"colorClick('#F08080')\" title='LightCoral' style='background-color:#F08080'><span></span></div>";
    $salida.=" <div  id='#E0FFFF' class='smallColorSquare' onclick=\"colorClick('#E0FFFF')\" title='LightCyan' style='background-color:#E0FFFF'><span></span></div>";
    $salida.=" <div  id='#FAFAD2' class='smallColorSquare' onclick=\"colorClick('#FAFAD2')\" title='LightGoldenRodYellow' style='background-color:#FAFAD2'><span></span></div>";
    $salida.=" <div  id='#D3D3D3' class='smallColorSquare' onclick=\"colorClick('#D3D3D3')\" title='LightGrey' style='background-color:#D3D3D3'><span></span></div>";
    $salida.=" <div  id='#90EE90' class='smallColorSquare' onclick=\"colorClick('#90EE90')\" title='LightGreen' style='background-color:#90EE90'><span></span></div>";
    $salida.=" <div  id='#FFB6C1' class='smallColorSquare' onclick=\"colorClick('#FFB6C1')\" title='LightPink' style='background-color:#FFB6C1'><span></span></div>";
    $salida.=" <div  id='#FFA07A' class='smallColorSquare' onclick=\"colorClick('#FFA07A')\" title='LightSalmon' style='background-color:#FFA07A'><span></span></div>";
    $salida.=" <div  id='#20B2AA' class='smallColorSquare' onclick=\"colorClick('#20B2AA')\" title='LightSeaGreen' style='background-color:#20B2AA'><span></span></div>";
    $salida.=" <div  id='#87CEFA' class='smallColorSquare' onclick=\"colorClick('#87CEFA')\" title='LightSkyBlue' style='background-color:#87CEFA'><span></span></div>";
    $salida.=" <div  id='#8470FF' class='smallColorSquare' onclick=\"colorClick('#8470FF')\" title='LightSlateBlue' style='background-color:#8470FF'><span></span></div>";
    $salida.=" <div  id='#778899' class='smallColorSquare' onclick=\"colorClick('#778899')\" title='LightSlateGray' style='background-color:#778899'><span></span></div>";
    $salida.=" <div  id='#B0C4DE' class='smallColorSquare' onclick=\"colorClick('#B0C4DE')\" title='LightSteelBlue' style='background-color:#B0C4DE'><span></span></div>";
    $salida.=" <div  id='#FFFFE0' class='smallColorSquare' onclick=\"colorClick('#FFFFE0')\" title='LightYellow' style='background-color:#FFFFE0'><span></span></div>";
    $salida.=" <div  id='#00FF00' class='smallColorSquare' onclick=\"colorClick('#00FF00')\" title='Lime' style='background-color:#00FF00'><span></span></div>";
    $salida.=" <div  id='#32CD32' class='smallColorSquare' onclick=\"colorClick('#32CD32')\" title='LimeGreen' style='background-color:#32CD32'><span></span></div>";
    $salida.=" <div  id='#FAF0E6' class='smallColorSquare' onclick=\"colorClick('#FAF0E6')\" title='Linen' style='background-color:#FAF0E6'><span></span></div>";
    $salida.=" <div  id='#FF00FF' class='smallColorSquare' onclick=\"colorClick('#FF00FF')\" title='Magenta' style='background-color:#FF00FF'><span></span></div>";
    $salida.=" <div  id='#800000' class='smallColorSquare' onclick=\"colorClick('#800000')\" title='Maroon' style='background-color:#800000'><span></span></div>";
    $salida.=" <div  id='#66CDAA' class='smallColorSquare' onclick=\"colorClick('#66CDAA')\" title='MediumAquaMarine' style='background-color:#66CDAA'><span></span></div>";
    $salida.=" <div  id='#0000CD' class='smallColorSquare' onclick=\"colorClick('#0000CD')\" title='MediumBlue' style='background-color:#0000CD'><span></span></div>";
    $salida.=" <div  id='#BA55D3' class='smallColorSquare' onclick=\"colorClick('#BA55D3')\" title='MediumOrchid' style='background-color:#BA55D3'><span></span></div>";
    $salida.=" <div  id='#9370D8' class='smallColorSquare' onclick=\"colorClick('#9370D8')\" title='MediumPurple' style='background-color:#9370D8'><span></span></div>";
    $salida.=" <div  id='#3CB371' class='smallColorSquare' onclick=\"colorClick('#3CB371')\" title='MediumSeaGreen' style='background-color:#3CB371'><span></span></div>";
    $salida.=" <div  id='#7B68EE' class='smallColorSquare' onclick=\"colorClick('#7B68EE')\" title='MediumSlateBlue' style='background-color:#7B68EE'><span></span></div>";
    $salida.=" <div  id='#00FA9A' class='smallColorSquare' onclick=\"colorClick('#00FA9A')\" title='MediumSpringGreen' style='background-color:#00FA9A'><span></span></div>";
    $salida.=" <div  id='#48D1CC' class='smallColorSquare' onclick=\"colorClick('#48D1CC')\" title='MediumTurquoise' style='background-color:#48D1CC'><span></span></div>";
    $salida.=" <div  id='#C71585' class='smallColorSquare' onclick=\"colorClick('#C71585')\" title='MediumVioletRed' style='background-color:#C71585'><span></span></div>";
    $salida.=" <div  id='#191970' class='smallColorSquare' onclick=\"colorClick('#191970')\" title='MidnightBlue' style='background-color:#191970'><span></span></div>";
    $salida.=" <div  id='#F5FFFA' class='smallColorSquare' onclick=\"colorClick('#F5FFFA')\" title='MintCream' style='background-color:#F5FFFA'><span></span></div>";
    $salida.=" <div  id='#FFE4E1' class='smallColorSquare' onclick=\"colorClick('#FFE4E1')\" title='MistyRose' style='background-color:#FFE4E1'><span></span></div>";
    $salida.=" <div  id='#FFE4B5' class='smallColorSquare' onclick=\"colorClick('#FFE4B5')\" title='Moccasin' style='background-color:#FFE4B5'><span></span></div>";
    $salida.=" <div  id='#FFDEAD' class='smallColorSquare' onclick=\"colorClick('#FFDEAD')\" title='NavajoWhite' style='background-color:#FFDEAD'><span></span></div>";
    $salida.=" <div  id='#000080' class='smallColorSquare' onclick=\"colorClick('#000080')\" title='Navy' style='background-color:#000080'><span></span></div>";
    $salida.=" <div  id='#FDF5E6' class='smallColorSquare' onclick=\"colorClick('#FDF5E6')\" title='OldLace' style='background-color:#FDF5E6'><span></span></div>";
    $salida.=" <div  id='#808000' class='smallColorSquare' onclick=\"colorClick('#808000')\" title='Olive' style='background-color:#808000'><span></span></div>";
    $salida.=" <div  id='#6B8E23' class='smallColorSquare' onclick=\"colorClick('#6B8E23')\" title='OliveDrab' style='background-color:#6B8E23'><span></span></div>";
    $salida.=" <div  id='#FFA500' class='smallColorSquare' onclick=\"colorClick('#FFA500')\" title='Orange' style='background-color:#FFA500'><span></span></div>";
    $salida.=" <div  id='#FF4500' class='smallColorSquare' onclick=\"colorClick('#FF4500')\" title='OrangeRed' style='background-color:#FF4500'><span></span></div>";
    $salida.=" <div  id='#DA70D6' class='smallColorSquare' onclick=\"colorClick('#DA70D6')\" title='Orchid' style='background-color:#DA70D6'><span></span></div>";
    $salida.=" <div  id='#EEE8AA' class='smallColorSquare' onclick=\"colorClick('#EEE8AA')\" title='PaleGoldenRod' style='background-color:#EEE8AA'><span></span></div>";
    $salida.=" <div  id='#98FB98' class='smallColorSquare' onclick=\"colorClick('#98FB98')\" title='PaleGreen' style='background-color:#98FB98'><span></span></div>";
    $salida.=" <div  id='#AFEEEE' class='smallColorSquare' onclick=\"colorClick('#AFEEEE')\" title='PaleTurquoise' style='background-color:#AFEEEE'><span></span></div>";
    $salida.=" <div  id='#D87093' class='smallColorSquare' onclick=\"colorClick('#D87093')\" title='PaleVioletRed' style='background-color:#D87093'><span></span></div>";
    $salida.=" <div  id='#FFEFD5' class='smallColorSquare' onclick=\"colorClick('#FFEFD5')\" title='PapayaWhip' style='background-color:#FFEFD5'><span></span></div>";
    $salida.=" <div  id='#FFDAB9' class='smallColorSquare' onclick=\"colorClick('#FFDAB9')\" title='PeachPuff' style='background-color:#FFDAB9'><span></span></div>";
    $salida.=" <div  id='#CD853F' class='smallColorSquare' onclick=\"colorClick('#CD853F')\" title='Peru' style='background-color:#CD853F'><span></span></div>";
    $salida.=" <div  id='#FFC0CB' class='smallColorSquare' onclick=\"colorClick('#FFC0CB')\" title='Pink' style='background-color:#FFC0CB'><span></span></div>";
    $salida.=" <div  id='#DDA0DD' class='smallColorSquare' onclick=\"colorClick('#DDA0DD')\" title='Plum' style='background-color:#DDA0DD'><span></span></div>";
    $salida.=" <div  id='#B0E0E6' class='smallColorSquare' onclick=\"colorClick('#B0E0E6')\" title='PowderBlue' style='background-color:#B0E0E6'><span></span></div>";
    $salida.=" <div  id='#800080' class='smallColorSquare' onclick=\"colorClick('#800080')\" title='Purple' style='background-color:#800080'><span></span></div>";
    $salida.=" <div  id='#FF0000' class='smallColorSquare' onclick=\"colorClick('#FF0000')\" title='Red' style='background-color:#FF0000'><span></span></div>";
    $salida.=" <div  id='#BC8F8F' class='smallColorSquare' onclick=\"colorClick('#BC8F8F')\" title='RosyBrown' style='background-color:#BC8F8F'><span></span></div>";
    $salida.=" <div  id='#4169E1' class='smallColorSquare' onclick=\"colorClick('#4169E1')\" title='RoyalBlue' style='background-color:#4169E1'><span></span></div>";
    $salida.=" <div  id='#8B4513' class='smallColorSquare' onclick=\"colorClick('#8B4513')\" title='SaddleBrown' style='background-color:#8B4513'><span></span></div>";
    $salida.=" <div  id='#FA8072' class='smallColorSquare' onclick=\"colorClick('#FA8072')\" title='Salmon' style='background-color:#FA8072'><span></span></div>";
    $salida .= "<div id='#F4A460' class='smallColorSquare' onclick=\"colorClick('#F4A460')\" title='SandyBrown' style='background-color:#F4A460'><span></span></div>";
    $salida .= "<div id='#2E8B57' class='smallColorSquare' onclick=\"colorClick('#2E8B57')\" title='SeaGreen' style='background-color:#2E8B57'><span></span></div>";
    $salida .= "<div id='#FFF5EE' class='smallColorSquare' onclick=\"colorClick('#FFF5EE')\" title='SeaShell' style='background-color:#FFF5EE'><span></span></div>";
    $salida .= "<div id='#A0522D' class='smallColorSquare' onclick=\"colorClick('#A0522D')\" title='Sienna' style='background-color:#A0522D'><span></span></div>";
    $salida .= "<div id='#C0C0C0' class='smallColorSquare' onclick=\"colorClick('#C0C0C0')\" title='Silver' style='background-color:#C0C0C0'><span></span></div>";
    $salida .= "<div id='#87CEEB' class='smallColorSquare' onclick=\"colorClick('#87CEEB')\" title='SkyBlue' style='background-color:#87CEEB'><span></span></div>";
    $salida .= "<div id='#6A5ACD' class='smallColorSquare' onclick=\"colorClick('#6A5ACD')\" title='SlateBlue' style='background-color:#6A5ACD'><span></span></div>";
    $salida .= "<div id='#708090' class='smallColorSquare' onclick=\"colorClick('#708090')\" title='SlateGray' style='background-color:#708090'><span></span></div>";
    $salida .= "<div id='#FFFAFA' class='smallColorSquare' onclick=\"colorClick('#FFFAFA')\" title='Snow' style='background-color:#FFFAFA'><span></span></div>";
    $salida .= "<div id='#00FF7F' class='smallColorSquare' onclick=\"colorClick('#00FF7F')\" title='SpringGreen' style='background-color:#00FF7F'><span></span></div>";
    $salida .= "<div id='#4682B4' class='smallColorSquare' onclick=\"colorClick('#4682B4')\" title='SteelBlue' style='background-color:#4682B4'><span></span></div>";
    $salida .= "<div id='#D2B48C' class='smallColorSquare' onclick=\"colorClick('#D2B48C')\" title='Tan' style='background-color:#D2B48C'><span></span></div>";
    $salida .= "<div id='#008080' class='smallColorSquare' onclick=\"colorClick('#008080')\" title='Teal' style='background-color:#008080'><span></span></div>";
    $salida .= "<div id='#D8BFD8' class='smallColorSquare' onclick=\"colorClick('#D8BFD8')\" title='Thistle' style='background-color:#D8BFD8'><span></span></div>";
    $salida .= "<div id='#FF6347' class='smallColorSquare' onclick=\"colorClick('#FF6347')\" title='Tomato' style='background-color:#FF6347'><span></span></div>";
    $salida .= "<div id='#40E0D0' class='smallColorSquare' onclick=\"colorClick('#40E0D0')\" title='Turquoise' style='background-color:#40E0D0'><span></span></div>";
    $salida .= "<div id='#EE82EE' class='smallColorSquare' onclick=\"colorClick('#EE82EE')\" title='Violet' style='background-color:#EE82EE'><span></span></div>";
    $salida .= "<div id='#D02090' class='smallColorSquare' onclick=\"colorClick('#D02090')\" title='VioletRed' style='background-color:#D02090'><span></span></div>";
    $salida .= "<div id='#F5DEB3' class='smallColorSquare' onclick=\"colorClick('#F5DEB3')\" title='Wheat' style='background-color:#F5DEB3'><span></span></div>";
    $salida .= "<div id='#FFFFFF' class='smallColorSquare' onclick=\"colorClick('#FFFFFF')\" title='White' style='background-color:#FFFFFF'><span></span></div>";
    $salida .= "<div id='#F5F5F5' class='smallColorSquare' onclick=\"colorClick('#F5F5F5')\" title='WhiteSmoke' style='background-color:#F5F5F5'><span></span></div>";
    $salida .= "<div id='#FFFF00' class='smallColorSquare' onclick=\"colorClick('#FFFF00')\" title='Yellow' style='background-color:#FFFF00'><span></span></div>";
    $salida .= "<div id='#9ACD32' class='smallColorSquare' onclick=\"colorClick('#9ACD32')\" title='YellowGreen' style='background-color:#9ACD32'><span></span></div>";
    $salida .= "</div>";*/
    
    $salida .= "<div class='floatingWindowContent'>";
    $salida .= "<div  id='#F0F8FF' class='smallColorSquare' onclick=\"colorClick('#F0F8FF')\" title='AliceBlue' style='background-color:#F0F8FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FAEBD7' class='smallColorSquare' onclick=\"colorClick('#FAEBD7')\" title='AntiqueWhite' style='background-color:#FAEBD7'><span>&nbsp</span></div>";
    $salida .= "<div  id='#00FFFF' class='smallColorSquare' onclick=\"colorClick('#00FFFF')\" title='Aqua and Cyan' style='background-color:#00FFFF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#7FFFD4' class='smallColorSquare' onclick=\"colorClick('#7FFFD4')\" title='Aquamarine' style='background-color:#7FFFD4'><span>&nbsp</span></div>";
    $salida .= "<div  id='#F0FFFF' class='smallColorSquare' onclick=\"colorClick('#F0FFFF')\" title='Azure' style='background-color:#F0FFFF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#F5F5DC' class='smallColorSquare' onclick=\"colorClick('#F5F5DC')\" title='Beige' style='background-color:#F5F5DC'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFE4C4' class='smallColorSquare' onclick=\"colorClick('#FFE4C4')\" title='Bisque' style='background-color:#FFE4C4'><span>&nbsp</span></div>";
    $salida .= "<div  id='#000000' class='smallColorSquare' onclick=\"colorClick('#000000')\" title='Black' style='background-color:#000000'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFEBCD' class='smallColorSquare' onclick=\"colorClick('#FFEBCD')\" title='BlanchedAlmond' style='background-color:#FFEBCD'><span>&nbsp</span></div>";
    $salida .= "<div  id='#0000FF' class='smallColorSquare' onclick=\"colorClick('#0000FF')\" title='Blue' style='background-color:#0000FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#8A2BE2' class='smallColorSquare' onclick=\"colorClick('#8A2BE2')\" title='BlueViolet' style='background-color:#8A2BE2'><span>&nbsp</span></div>";
    $salida .= "<div  id='#A52A2A' class='smallColorSquare' onclick=\"colorClick('#A52A2A')\" title='Brown' style='background-color:#A52A2A'><span>&nbsp</span></div>";
    $salida .= "<div  id='#DEB887' class='smallColorSquare' onclick=\"colorClick('#DEB887')\" title='BurlyWood' style='background-color:#DEB887'><span>&nbsp</span></div>";
    $salida .= "<div  id='#5F9EA0' class='smallColorSquare' onclick=\"colorClick('#5F9EA0')\" title='CadetBlue' style='background-color:#5F9EA0'><span>&nbsp</span></div>";
    $salida .= "<div  id='#7FFF00' class='smallColorSquare' onclick=\"colorClick('#7FFF00')\" title='Chartreuse' style='background-color:#7FFF00'><span>&nbsp</span></div>";
    $salida .= "<div  id='#D2691E' class='smallColorSquare' onclick=\"colorClick('#D2691E')\" title='Chocolate' style='background-color:#D2691E'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FF7F50' class='smallColorSquare' onclick=\"colorClick('#FF7F50')\" title='Coral' style='background-color:#FF7F50'><span>&nbsp</span></div>";
    $salida .= "<div  id='#6495ED' class='smallColorSquare' onclick=\"colorClick('#6495ED')\" title='CornflowerBlue' style='background-color:#6495ED'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFF8DC' class='smallColorSquare' onclick=\"colorClick('#FFF8DC')\" title='Cornsilk' style='background-color:#FFF8DC'><span>&nbsp</span></div>";
    $salida .= "<div  id='#DC143C' class='smallColorSquare' onclick=\"colorClick('#DC143C')\" title='Crimson' style='background-color:#DC143C'><span>&nbsp</span></div>";
    $salida .= "<div  id='#00008B' class='smallColorSquare' onclick=\"colorClick('#00008B')\" title='DarkBlue' style='background-color:#00008B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#008B8B' class='smallColorSquare' onclick=\"colorClick('#008B8B')\" title='DarkCyan' style='background-color:#008B8B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#B8860B' class='smallColorSquare' onclick=\"colorClick('#B8860B')\" title='DarkGoldenRod' style='background-color:#B8860B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#A9A9A9' class='smallColorSquare' onclick=\"colorClick('#A9A9A9')\" title='DarkGray' style='background-color:#A9A9A9'><span>&nbsp</span></div>";
    $salida .= "<div  id='#006400' class='smallColorSquare' onclick=\"colorClick('#006400')\" title='DarkGreen' style='background-color:#006400'><span>&nbsp</span></div>";
    $salida .= "<div  id='#BDB76B' class='smallColorSquare' onclick=\"colorClick('#BDB76B')\" title='DarkKhaki' style='background-color:#BDB76B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#8B008B' class='smallColorSquare' onclick=\"colorClick('#8B008B')\" title='DarkMagenta' style='background-color:#8B008B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#556B2F' class='smallColorSquare' onclick=\"colorClick('#556B2F')\" title='DarkOliveGreen' style='background-color:#556B2F'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FF8C00' class='smallColorSquare' onclick=\"colorClick('#FF8C00')\" title='Darkorange' style='background-color:#FF8C00'><span>&nbsp</span></div>";
    $salida .= "<div  id='#9932CC' class='smallColorSquare' onclick=\"colorClick('#9932CC')\" title='DarkOrchid' style='background-color:#9932CC'><span>&nbsp</span></div>";
    $salida .= "<div  id='#8B0000' class='smallColorSquare' onclick=\"colorClick('#8B0000')\" title='DarkRed' style='background-color:#8B0000'><span>&nbsp</span></div>";
    $salida .= "<div  id='#E9967A' class='smallColorSquare' onclick=\"colorClick('#E9967A')\" title='DarkSalmon' style='background-color:#E9967A'><span>&nbsp</span></div>";
    $salida .= "<div  id='#8FBC8F' class='smallColorSquare' onclick=\"colorClick('#8FBC8F')\" title='DarkSeaGreen' style='background-color:#8FBC8F'><span>&nbsp</span></div>";
    $salida .= "<div  id='#483D8B' class='smallColorSquare' onclick=\"colorClick('#483D8B')\" title='DarkSlateBlue' style='background-color:#483D8B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#2F4F4F' class='smallColorSquare' onclick=\"colorClick('#2F4F4F')\" title='DarkSlateGray' style='background-color:#2F4F4F'><span>&nbsp</span></div>";
    $salida .= "<div  id='#00CED1' class='smallColorSquare' onclick=\"colorClick('#00CED1')\" title='DarkTurquoise' style='background-color:#00CED1'><span>&nbsp</span></div>";
    $salida .= "<div  id='#9400D3' class='smallColorSquare' onclick=\"colorClick('#9400D3')\" title='DarkViolet' style='background-color:#9400D3'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FF1493' class='smallColorSquare' onclick=\"colorClick('#FF1493')\" title='DeepPink' style='background-color:#FF1493'><span>&nbsp</span></div>";
    $salida .= "<div  id='#00BFFF' class='smallColorSquare' onclick=\"colorClick('#00BFFF')\" title='DeepSkyBlue' style='background-color:#00BFFF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#696969' class='smallColorSquare' onclick=\"colorClick('#696969')\" title='DimGray' style='background-color:#696969'><span>&nbsp</span></div>";
    $salida .= "<div  id='#1E90FF' class='smallColorSquare' onclick=\"colorClick('#1E90FF')\" title='DodgerBlue' style='background-color:#1E90FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#D19275' class='smallColorSquare' onclick=\"colorClick('#D19275')\" title='Feldspar' style='background-color:#D19275'><span>&nbsp</span></div>";
    $salida .= "<div  id='#B22222' class='smallColorSquare' onclick=\"colorClick('#B22222')\" title='FireBrick' style='background-color:#B22222'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFFAF0' class='smallColorSquare' onclick=\"colorClick('#FFFAF0')\" title='FloralWhite' style='background-color:#FFFAF0'><span>&nbsp</span></div>";
    $salida .= "<div  id='#228B22' class='smallColorSquare' onclick=\"colorClick('#228B22')\" title='ForestGreen' style='background-color:#228B22'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FF00FF' class='smallColorSquare' onclick=\"colorClick('#FF00FF')\" title='Fuchsia' style='background-color:#FF00FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#DCDCDC' class='smallColorSquare' onclick=\"colorClick('#DCDCDC')\" title='Gainsboro' style='background-color:#DCDCDC'><span>&nbsp</span></div>";
    $salida .= "<div  id='#F8F8FF' class='smallColorSquare' onclick=\"colorClick('#F8F8FF')\" title='GhostWhite' style='background-color:#F8F8FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFD700' class='smallColorSquare' onclick=\"colorClick('#FFD700')\" title='Gold' style='background-color:#FFD700'><span>&nbsp</span></div>";
    $salida .="<div  id='#DAA520' class='smallColorSquare' onclick=\"colorClick('#DAA520')\" title='GoldenRod' style='background-color:#DAA520'><span>&nbsp</span></div>";
    $salida .="<div  id='#808080' class='smallColorSquare' onclick=\"colorClick('#808080')\" title='Gray' style='background-color:#808080'><span>&nbsp</span></div>";
    $salida .="<div  id='#008000' class='smallColorSquare' onclick=\"colorClick('#008000')\" title='Green' style='background-color:#008000'><span>&nbsp</span></div>";
    $salida .="<div  id='#ADFF2F' class='smallColorSquare' onclick=\"colorClick('#ADFF2F')\" title='GreenYellow' style='background-color:#ADFF2F'><span>&nbsp</span></div>";
    $salida .="<div  id='#F0FFF0' class='smallColorSquare' onclick=\"colorClick('#F0FFF0')\" title='HoneyDew' style='background-color:#F0FFF0'><span>&nbsp</span></div>";
    $salida .="<div  id='#FF69B4' class='smallColorSquare' onclick=\"colorClick('#FF69B4')\" title='HotPink' style='background-color:#FF69B4'><span>&nbsp</span></div>";
    $salida .="<div  id='#CD5C5C' class='smallColorSquare' onclick=\"colorClick('#CD5C5C')\" title='IndianRed' style='background-color:#CD5C5C'><span>&nbsp</span></div>";
    $salida .="<div  id='#4B0082' class='smallColorSquare' onclick=\"colorClick('#4B0082')\" title='Indigo' style='background-color:#4B0082'><span>&nbsp</span></div>";
    $salida .="<div  id='#FFFFF0' class='smallColorSquare' onclick=\"colorClick('#FFFFF0')\" title='Ivory' style='background-color:#FFFFF0'><span>&nbsp</span></div>";
    $salida .="<div  id='#F0E68C' class='smallColorSquare' onclick=\"colorClick('#F0E68C')\" title='Khaki' style='background-color:#F0E68C'><span>&nbsp</span></div>";
    $salida .="<div  id='#E6E6FA' class='smallColorSquare' onclick=\"colorClick('#E6E6FA')\" title='Lavender' style='background-color:#E6E6FA'><span>&nbsp</span></div>";
    $salida .="<div  id='#FFF0F5' class='smallColorSquare' onclick=\"colorClick('#FFF0F5')\" title='LavenderBlush' style='background-color:#FFF0F5'><span>&nbsp</span></div>";
    $salida .="<div  id='#7CFC00' class='smallColorSquare' onclick=\"colorClick('#7CFC00')\" title='LawnGreen' style='background-color:#7CFC00'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFFACD' class='smallColorSquare' onclick=\"colorClick('#FFFACD')\" title='LemonChiffon' style='background-color:#FFFACD'><span>&nbsp</span></div>";
    $salida.=" <div  id='#ADD8E6' class='smallColorSquare' onclick=\"colorClick('#ADD8E6')\" title='LightBlue' style='background-color:#ADD8E6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#F08080' class='smallColorSquare' onclick=\"colorClick('#F08080')\" title='LightCoral' style='background-color:#F08080'><span>&nbsp</span></div>";
    $salida.=" <div  id='#E0FFFF' class='smallColorSquare' onclick=\"colorClick('#E0FFFF')\" title='LightCyan' style='background-color:#E0FFFF'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FAFAD2' class='smallColorSquare' onclick=\"colorClick('#FAFAD2')\" title='LightGoldenRodYellow' style='background-color:#FAFAD2'><span>&nbsp</span></div>";
    $salida.=" <div  id='#D3D3D3' class='smallColorSquare' onclick=\"colorClick('#D3D3D3')\" title='LightGrey' style='background-color:#D3D3D3'><span>&nbsp</span></div>";
    $salida.=" <div  id='#90EE90' class='smallColorSquare' onclick=\"colorClick('#90EE90')\" title='LightGreen' style='background-color:#90EE90'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFB6C1' class='smallColorSquare' onclick=\"colorClick('#FFB6C1')\" title='LightPink' style='background-color:#FFB6C1'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFA07A' class='smallColorSquare' onclick=\"colorClick('#FFA07A')\" title='LightSalmon' style='background-color:#FFA07A'><span>&nbsp</span></div>";
    $salida.=" <div  id='#20B2AA' class='smallColorSquare' onclick=\"colorClick('#20B2AA')\" title='LightSeaGreen' style='background-color:#20B2AA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#87CEFA' class='smallColorSquare' onclick=\"colorClick('#87CEFA')\" title='LightSkyBlue' style='background-color:#87CEFA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#8470FF' class='smallColorSquare' onclick=\"colorClick('#8470FF')\" title='LightSlateBlue' style='background-color:#8470FF'><span>&nbsp</span></div>";
    $salida.=" <div  id='#778899' class='smallColorSquare' onclick=\"colorClick('#778899')\" title='LightSlateGray' style='background-color:#778899'><span>&nbsp</span></div>";
    $salida.=" <div  id='#B0C4DE' class='smallColorSquare' onclick=\"colorClick('#B0C4DE')\" title='LightSteelBlue' style='background-color:#B0C4DE'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFFFE0' class='smallColorSquare' onclick=\"colorClick('#FFFFE0')\" title='LightYellow' style='background-color:#FFFFE0'><span>&nbsp</span></div>";
    $salida.=" <div  id='#00FF00' class='smallColorSquare' onclick=\"colorClick('#00FF00')\" title='Lime' style='background-color:#00FF00'><span>&nbsp</span></div>";
    $salida.=" <div  id='#32CD32' class='smallColorSquare' onclick=\"colorClick('#32CD32')\" title='LimeGreen' style='background-color:#32CD32'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FAF0E6' class='smallColorSquare' onclick=\"colorClick('#FAF0E6')\" title='Linen' style='background-color:#FAF0E6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FF00FF' class='smallColorSquare' onclick=\"colorClick('#FF00FF')\" title='Magenta' style='background-color:#FF00FF'><span>&nbsp</span></div>";
    $salida.=" <div  id='#800000' class='smallColorSquare' onclick=\"colorClick('#800000')\" title='Maroon' style='background-color:#800000'><span>&nbsp</span></div>";
    $salida.=" <div  id='#66CDAA' class='smallColorSquare' onclick=\"colorClick('#66CDAA')\" title='MediumAquaMarine' style='background-color:#66CDAA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#0000CD' class='smallColorSquare' onclick=\"colorClick('#0000CD')\" title='MediumBlue' style='background-color:#0000CD'><span>&nbsp</span></div>";
    $salida.=" <div  id='#BA55D3' class='smallColorSquare' onclick=\"colorClick('#BA55D3')\" title='MediumOrchid' style='background-color:#BA55D3'><span>&nbsp</span></div>";
    $salida.=" <div  id='#9370D8' class='smallColorSquare' onclick=\"colorClick('#9370D8')\" title='MediumPurple' style='background-color:#9370D8'><span>&nbsp</span></div>";
    $salida.=" <div  id='#3CB371' class='smallColorSquare' onclick=\"colorClick('#3CB371')\" title='MediumSeaGreen' style='background-color:#3CB371'><span>&nbsp</span></div>";
    $salida.=" <div  id='#7B68EE' class='smallColorSquare' onclick=\"colorClick('#7B68EE')\" title='MediumSlateBlue' style='background-color:#7B68EE'><span>&nbsp</span></div>";
    $salida.=" <div  id='#00FA9A' class='smallColorSquare' onclick=\"colorClick('#00FA9A')\" title='MediumSpringGreen' style='background-color:#00FA9A'><span>&nbsp</span></div>";
    $salida.=" <div  id='#48D1CC' class='smallColorSquare' onclick=\"colorClick('#48D1CC')\" title='MediumTurquoise' style='background-color:#48D1CC'><span>&nbsp</span></div>";
    $salida.=" <div  id='#C71585' class='smallColorSquare' onclick=\"colorClick('#C71585')\" title='MediumVioletRed' style='background-color:#C71585'><span>&nbsp</span></div>";
    $salida.=" <div  id='#191970' class='smallColorSquare' onclick=\"colorClick('#191970')\" title='MidnightBlue' style='background-color:#191970'><span>&nbsp</span></div>";
    $salida.=" <div  id='#F5FFFA' class='smallColorSquare' onclick=\"colorClick('#F5FFFA')\" title='MintCream' style='background-color:#F5FFFA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFE4E1' class='smallColorSquare' onclick=\"colorClick('#FFE4E1')\" title='MistyRose' style='background-color:#FFE4E1'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFE4B5' class='smallColorSquare' onclick=\"colorClick('#FFE4B5')\" title='Moccasin' style='background-color:#FFE4B5'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFDEAD' class='smallColorSquare' onclick=\"colorClick('#FFDEAD')\" title='NavajoWhite' style='background-color:#FFDEAD'><span>&nbsp</span></div>";
    $salida.=" <div  id='#000080' class='smallColorSquare' onclick=\"colorClick('#000080')\" title='Navy' style='background-color:#000080'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FDF5E6' class='smallColorSquare' onclick=\"colorClick('#FDF5E6')\" title='OldLace' style='background-color:#FDF5E6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#808000' class='smallColorSquare' onclick=\"colorClick('#808000')\" title='Olive' style='background-color:#808000'><span>&nbsp</span></div>";
    $salida.=" <div  id='#6B8E23' class='smallColorSquare' onclick=\"colorClick('#6B8E23')\" title='OliveDrab' style='background-color:#6B8E23'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFA500' class='smallColorSquare' onclick=\"colorClick('#FFA500')\" title='Orange' style='background-color:#FFA500'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FF4500' class='smallColorSquare' onclick=\"colorClick('#FF4500')\" title='OrangeRed' style='background-color:#FF4500'><span>&nbsp</span></div>";
    $salida.=" <div  id='#DA70D6' class='smallColorSquare' onclick=\"colorClick('#DA70D6')\" title='Orchid' style='background-color:#DA70D6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#EEE8AA' class='smallColorSquare' onclick=\"colorClick('#EEE8AA')\" title='PaleGoldenRod' style='background-color:#EEE8AA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#98FB98' class='smallColorSquare' onclick=\"colorClick('#98FB98')\" title='PaleGreen' style='background-color:#98FB98'><span>&nbsp</span></div>";
    $salida.=" <div  id='#AFEEEE' class='smallColorSquare' onclick=\"colorClick('#AFEEEE')\" title='PaleTurquoise' style='background-color:#AFEEEE'><span>&nbsp</span></div>";
    $salida.=" <div  id='#D87093' class='smallColorSquare' onclick=\"colorClick('#D87093')\" title='PaleVioletRed' style='background-color:#D87093'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFEFD5' class='smallColorSquare' onclick=\"colorClick('#FFEFD5')\" title='PapayaWhip' style='background-color:#FFEFD5'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFDAB9' class='smallColorSquare' onclick=\"colorClick('#FFDAB9')\" title='PeachPuff' style='background-color:#FFDAB9'><span>&nbsp</span></div>";
    $salida.=" <div  id='#CD853F' class='smallColorSquare' onclick=\"colorClick('#CD853F')\" title='Peru' style='background-color:#CD853F'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFC0CB' class='smallColorSquare' onclick=\"colorClick('#FFC0CB')\" title='Pink' style='background-color:#FFC0CB'><span>&nbsp</span></div>";
    $salida.=" <div  id='#DDA0DD' class='smallColorSquare' onclick=\"colorClick('#DDA0DD')\" title='Plum' style='background-color:#DDA0DD'><span>&nbsp</span></div>";
    $salida.=" <div  id='#B0E0E6' class='smallColorSquare' onclick=\"colorClick('#B0E0E6')\" title='PowderBlue' style='background-color:#B0E0E6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#800080' class='smallColorSquare' onclick=\"colorClick('#800080')\" title='Purple' style='background-color:#800080'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FF0000' class='smallColorSquare' onclick=\"colorClick('#FF0000')\" title='Red' style='background-color:#FF0000'><span>&nbsp</span></div>";
    $salida.=" <div  id='#BC8F8F' class='smallColorSquare' onclick=\"colorClick('#BC8F8F')\" title='RosyBrown' style='background-color:#BC8F8F'><span>&nbsp</span></div>";
    $salida.=" <div  id='#4169E1' class='smallColorSquare' onclick=\"colorClick('#4169E1')\" title='RoyalBlue' style='background-color:#4169E1'><span>&nbsp</span></div>";
    $salida.=" <div  id='#8B4513' class='smallColorSquare' onclick=\"colorClick('#8B4513')\" title='SaddleBrown' style='background-color:#8B4513'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FA8072' class='smallColorSquare' onclick=\"colorClick('#FA8072')\" title='Salmon' style='background-color:#FA8072'><span>&nbsp</span></div>";
    $salida .= "<div id='#F4A460' class='smallColorSquare' onclick=\"colorClick('#F4A460')\" title='SandyBrown' style='background-color:#F4A460'><span>&nbsp</span></div>";
    $salida .= "<div id='#2E8B57' class='smallColorSquare' onclick=\"colorClick('#2E8B57')\" title='SeaGreen' style='background-color:#2E8B57'><span>&nbsp</span></div>";
    $salida .= "<div id='#FFF5EE' class='smallColorSquare' onclick=\"colorClick('#FFF5EE')\" title='SeaShell' style='background-color:#FFF5EE'><span>&nbsp</span></div>";
    $salida .= "<div id='#A0522D' class='smallColorSquare' onclick=\"colorClick('#A0522D')\" title='Sienna' style='background-color:#A0522D'><span>&nbsp</span></div>";
    $salida .= "<div id='#C0C0C0' class='smallColorSquare' onclick=\"colorClick('#C0C0C0')\" title='Silver' style='background-color:#C0C0C0'><span>&nbsp</span></div>";
    $salida .= "<div id='#87CEEB' class='smallColorSquare' onclick=\"colorClick('#87CEEB')\" title='SkyBlue' style='background-color:#87CEEB'><span>&nbsp</span></div>";
    $salida .= "<div id='#6A5ACD' class='smallColorSquare' onclick=\"colorClick('#6A5ACD')\" title='SlateBlue' style='background-color:#6A5ACD'><span>&nbsp</span></div>";
    $salida .= "<div id='#708090' class='smallColorSquare' onclick=\"colorClick('#708090')\" title='SlateGray' style='background-color:#708090'><span>&nbsp</span></div>";
    $salida .= "<div id='#FFFAFA' class='smallColorSquare' onclick=\"colorClick('#FFFAFA')\" title='Snow' style='background-color:#FFFAFA'><span>&nbsp</span></div>";
    $salida .= "<div id='#00FF7F' class='smallColorSquare' onclick=\"colorClick('#00FF7F')\" title='SpringGreen' style='background-color:#00FF7F'><span>&nbsp</span></div>";
    $salida .= "<div id='#4682B4' class='smallColorSquare' onclick=\"colorClick('#4682B4')\" title='SteelBlue' style='background-color:#4682B4'><span>&nbsp</span></div>";
    $salida .= "<div id='#D2B48C' class='smallColorSquare' onclick=\"colorClick('#D2B48C')\" title='Tan' style='background-color:#D2B48C'><span>&nbsp</span></div>";
    $salida .= "<div id='#008080' class='smallColorSquare' onclick=\"colorClick('#008080')\" title='Teal' style='background-color:#008080'><span>&nbsp</span></div>";
    $salida .= "<div id='#D8BFD8' class='smallColorSquare' onclick=\"colorClick('#D8BFD8')\" title='Thistle' style='background-color:#D8BFD8'><span>&nbsp</span></div>";
    $salida .= "<div id='#FF6347' class='smallColorSquare' onclick=\"colorClick('#FF6347')\" title='Tomato' style='background-color:#FF6347'><span>&nbsp</span></div>";
    $salida .= "<div id='#40E0D0' class='smallColorSquare' onclick=\"colorClick('#40E0D0')\" title='Turquoise' style='background-color:#40E0D0'><span>&nbsp</span></div>";
    $salida .= "<div id='#EE82EE' class='smallColorSquare' onclick=\"colorClick('#EE82EE')\" title='Violet' style='background-color:#EE82EE'><span>&nbsp</span></div>";
    $salida .= "<div id='#D02090' class='smallColorSquare' onclick=\"colorClick('#D02090')\" title='VioletRed' style='background-color:#D02090'><span>&nbsp</span></div>";
    $salida .= "<div id='#F5DEB3' class='smallColorSquare' onclick=\"colorClick('#F5DEB3')\" title='Wheat' style='background-color:#F5DEB3'><span>&nbsp</span></div>";
    $salida .= "<div id='#FFFFFF' class='smallColorSquare' onclick=\"colorClick('#FFFFFF')\" title='White' style='background-color:#FFFFFF'><span>&nbsp</span></div>";
    $salida .= "<div id='#F5F5F5' class='smallColorSquare' onclick=\"colorClick('#F5F5F5')\" title='WhiteSmoke' style='background-color:#F5F5F5'><span>&nbsp</span></div>";
    $salida .= "<div id='#FFFF00' class='smallColorSquare' onclick=\"colorClick('#FFFF00')\" title='Yellow' style='background-color:#FFFF00'><span>&nbsp</span></div>";
    $salida .= "<div id='#9ACD32' class='smallColorSquare' onclick=\"colorClick('#9ACD32')\" title='YellowGreen' style='background-color:#9ACD32'><span>&nbsp</span></div>";
    $salida .= "</div>";

    $salida .= "                     </td>\n";
    $salida .= "                   </tr>\n";
    $salida .= "                   </table>\n";
    $salida .= "</div>";
    $salida .= "  <div id='window1' >";
//     $salida .= "                   <table >\n";
//     $salida .= "                   <tr>\n";
//     $salida .= "                     <td colspan='2'>\n";
    
    $salida .= "                 <table align='center' width='90%' class=\"modulo_table_list\">\n";
    $salida .= "                   <input type=\"hidden\" name=\"color_seleccionado\" id=\"color_seleccionado\"value=\"".$color."\">\n";
    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                     <td WIDTH='50%'>\n";
    $salida .= "                     COLOR SELECCIONADO\n";
    $salida .= "                     </td>\n";
    $salida .= "                     <td WIDTH='50%' id='color_sel' bgcolor='".$color."'>\n";
    $salida .= "                       ".$color."\n";
    $salida .= "                     </td>\n";
    $salida .= "                   </tr>\n";
    $salida .= "                   <tr >\n";
    $salida .= "                      <td colspan='2' align=\"CENTER\">\n";
    $beneficiario = "javascript:Devolver();\"";
    $salida .="                         <a title='VOLVER A LA PALETA DE COLORES' class='label_error' href=\"".$beneficiario."\">";
    $salida .="                          <label >VOLVER A LA PALETA DE COLORES</label>\n";//usuarios.png
    $salida .="                         </a>\n";
    $salida .= "                     </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                     <td WIDTH='50%'>\n";
    $salida .= "                     COLOR SELECCIONADO\n";
    $salida .= "                     </td>\n";
    $salida .= "                     <td WIDTH='50%' id='color_sel'>\n";
    $salida .= "                     \n";
    $salida .= "                     </td>\n";
    $salida .= "                   </tr>\n";
    $salida .= "                   <tr>\n";
    $salida .= "                     <td colspan='2' align=\"CENTER\">\n";
    $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"Actualizar Tipo de Agente\" onclick=\"xajax_ActualizarTipoAgente(document.getElementById('tipo_riesgo_id').value,document.getElementById('tipo_riesgo').value,document.getElementById('color_seleccionado').value,'".$usuario_registro."');\">\n";
    $salida .= "                     </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                 </table>\n";

    $salida .= "  </div>";
//     $salida .= "                   </tr>\n";
//     $salida .= "                 </table>\n";
        //document.getElementById('numeroF0F8FF').style.display ='none';
        
        $objResponse->assign("ContenidoGrup","innerHTML",$salida);
        $clase_sql = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
        $tipos_de_riesgo = $clase_sql->ObtenerTiposDeRiesgos();
        if(!empty($tipos_de_riesgo))
        {
            for($i=0;$i<count($tipos_de_riesgo);$i++)
            {
                $objResponse->assign($tipos_de_riesgo[$i]['color'],"style.display","none");
            }

        }

        
        return $objResponse;

    }




    /**
    * funcion que sirve para refrescar la lista de tipos de angetes registrado
    * @return string $html  con la forma que contiene la lista de tipos de riesgos.
    **/
    function PintarTiposAgentes()
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        $clase_sql = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
        $tipos_de_riesgo = $clase_sql->ObtenerTiposDeRiesgos();

        $agentes_de_riesgo = $clase_sql->ObtenerAgentesDeRiesgos();
        
//         $i=substr('#ff6678', 3, 2);
//         $i=hexdec($i);
//         $o=0x99;
//         $o=hexdec($o);
//         $r=$i-$o;
//         $objResponse->alert($i);
//         $objResponse->alert($o);
//         $objResponse->alert($r);
        
        if(!empty($tipos_de_riesgo))
        {           

       
          
            for($i=0;$i<count($tipos_de_riesgo);$i++)//
            {   

                    $td="BotonBenef".$i;
                    $html .= "               <div align='center' id=\"Benef".$i."\" style=\"width:100%; height:25px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick=''>";
                    $html .= "                 <table class=\"modulo_table_list\" width=\"50%\" align=\"center\"  >\n";
                    $html .= "                   <tr class=\"formulacion_table_list\" >\n";
                    $html .= "                     <td width='87%' align=\"center\">\n";
                    $html .= "                       <a title='TIPO DE RIESGO'>";
                    $html .= "                        ".$tipos_de_riesgo[$i]['descripcion']."";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='5%' align=\"center\">\n";
                    $html .= "                       <a title='EDITAR INFORMACION TIPO DE RIESGO' href=\"javascript:MostrarCapa('ContenedorGrup');EditarInfo('".$tipos_de_riesgo[$i]['tipo_riesgo_id']."','".$tipos_de_riesgo[$i]['descripcion']."','".$tipos_de_riesgo[$i]['color']."','".$tipos_de_riesgo[$i]['usuario_registro']."');Iniciar2('CREAR TIPO DE RIESGO');\">";
                    $html .= "                          <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td BGCOLOR='".$tipos_de_riesgo[$i]['color']."' width='5%' align=\"center\" id='".$td1."'>\n";
                    $html .= "                       <a title='COLOR TIPO DE RIESGO'\">";
                    $html .= "                          \n";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                     <td width='3%' align=\"center\" id='".$td."'>\n";
                    $html .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Benef".$i."','0','".$path."','".$td."');\">";
                    $html .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $html .= "                       </a>";
                    $html .= "                     </td>\n";
                    $html .= "                   </tr>\n";
                    $html .= "                 </table>\n";

                    $html .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $html .= "                   <tr>\n";
                    $html .= "                     <td align=\"center\">\n";
                    $html .= "                      <table width=\"100%\" align=\"center\">\n";
                    $html .= "                        <tr>\n";
                    $html .= "                          <td colspan='3' align=\"center\">\n";
                    $html .= "                            &nbsp; ";
                    $html .= "                          </td>\n";
                    $html .= "                        </tr>\n";
                    $html .= "                     </table>\n";    
                    $ban=0;
                    $html1='';
                    foreach($agentes_de_riesgo as $key=>$valor)
                    {
                        foreach($valor as $key=>$valor1)
                        {
                            if($valor1['tipo_riesgo_id']==$tipos_de_riesgo[$i]['tipo_riesgo_id'])
                            {
                                $html1 .= "                       <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
                                $html1 .= "                         <tr>\n";
                                $html1 .= "                           <td colspan='3' CLASS='modulo_table_list_title' align=\"center\">\n";
                                $html1 .= "                             LISTADO DE AGENTES DE RIESGO";
                                $html1 .= "                           </td>\n";
                                $html1 .= "                         </tr>\n";
                                $html1 .= "                         <tr class=\"modulo_list_claro\">\n";
                                $html1 .= "                           <td width=\"70%\" class=\"GrupoMedicamentos\"  align=\"center\">\n";
                                $html1 .= "                             NOMBRE";
                                $html1 .= "                           </td>\n";
                                $html1 .= "                           <td width=\"15%\" class=\"GrupoMedicamentos\"  align=\"center\">\n";
                                $html1 .= "                             EDITAR";
                                $html1 .= "                           </td>\n";
                                $html1 .= "                           <td width=\"15%\" class=\"GrupoMedicamentos\"  align=\"center\">\n";
                                $html1 .= "                             ESTADO";
                                $html1 .= "                           </td>\n";
                                $html1 .= "                         </tr>\n";
                                if($ban==0)
                                {   
                                    $html .=$html1;
                                    $ban++;            
                                    $html1='';
                                }
                                $html2 .= "                         <tr class=\"modulo_list_claro\">\n";
                                $html2 .= "                           <td BGCOLOR='#FFAAAA'  align=\"left\">\n";
                                $html2 .= "                             ".$valor1['descripcion'];
                                $html2 .= "                           </td>\n";
                                $html2 .= "                           <td align=\"center\">\n";//                                                   agente_riesgo_id   tipo_riesgo_id  descripcion usuario_registro    fecha_registro  sw_estado
                                $html2 .= "                             <a title='EDITAR INFORMACION DEL AGENTE DE RIESGO' href=\"javascript:MostrarCapa('ContenedorGrup');EditarInfoAgente('".$valor1['agente_riesgo_id']."','".$valor1['tipo_riesgo_id']."','".$valor1['descripcion']."','".$valor1['sw_estado']."');Iniciar2('EDITAR INFORMACION AGENTE DE RIESGO');\">";
                                $html2 .= "                               <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                                $html2 .= "                             </a>";
                                $html2 .= "                           </td>\n";
                                $html2 .= "                           <td align=\"center\" id='tdcito".$valor1['agente_riesgo_id']."'>\n";
                                $html2 .= "                             <a title='CAMBIAR ESTADO DEL AGENTE DE RIESGO' href=\"javascript:CambiarEstado('".$valor1['agente_riesgo_id']."','".$valor1['tipo_riesgo_id']."','".$valor1['descripcion']."','".$valor1['sw_estado']."','tdcito".$valor1['agente_riesgo_id']."');\">";
                                if($valor1['sw_estado']=='1')
                                {
                                    $html2 .= "                               <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                                }
                                elseif($valor1['sw_estado']=='0')
                                {
                                    $html2 .= "                               <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                                }
                                $html2 .= "                             </a>";
                                $html2 .= "                           </td>\n";
                                $html2 .= "                         </tr>\n";
                                $html .=$html2;
                            }
                         $html2='';   
                        }
                        
                   }
                    if($ban==0)
                        {
                                $html .= "                             <label class='label_error'>ESTE TIPO DE RIESGO NO TIENE AGENTES CREADOS</label>";
                                $html .= "                           </td>\n";
                                $html .= "                         </tr>\n";
                        }

                        $html .= "                 </table>\n";
                        $html .= "                 <table width=\"100%\" align=\"center\">\n";
                        $html .= "                   <tr>\n";
                        $html .= "                     <td colspan='3' align=\"center\">\n";
                        $html .= "                       &nbsp; ";
                        $html .= "                     </td>\n";
                        $html .= "                   </tr>\n";
                        $html .= "                 </table>\n";
                        $html .= "               </td>\n";
                        $html .= "             </tr>\n";

                        $html .= "           </table>\n";

                    $html .= "                </div>\n";
               
               
            }


        }
        else
        {
            $html .= "                 <table WIDTH='100%'>\n";
            $html .= "                   <tr>\n";
            $html .= "                      <td align=\"CENTER\">\n";
            $html .="                          <label class='label_error'>NO HAY TIPOS DE RIESGOS CREADOS</label>\n";//usuarios.png
            $html .= "                    </tr>\n";
            $html .= "                 </table>\n";
        }

        $objResponse->assign("tipos_de_riesgox","innerHTML",$html);
        return $objResponse;
    
    }
  
    /**
    *  funcion que sirve para el registro de tipos de riesgo
    *  @return string $salida con la forma que contiene el menu de creacion de tipos de riesgo
    **/
    function FormaNuevoTipoRiesgo()
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
        $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
        $salida .= "                     <td width='30%' align=\"center\">\n";
        $salida .= "                       NOMBRE TIPO DE RIESGO";
        $salida .= "                     </td>\n";
        $salida .= "                     <td width='70%' align=\"center\">\n";
        $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"tipo_riesgo\" id=\"tipo_riesgo\" size=\"50\" onkeypress=\"\">\n";//return acceptNum(event)
        $salida .= "                     </td>\n";
        $salida .= "                   </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "<div id='colorex'>";
        $salida .= "                   <table class=\"modulo_table_list\" width=\"90%\" align='center'>\n";
        $salida .= "                   <tr>\n";
        $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
        $salida .= "                       SELECCIONAR COLOR";
        $salida .= "                     </td>\n";
       // $salida .= "                 </table>\n";
        //$salida .= "                 <table width=\"90%\" align=\"center\">\n";
        $salida .= "                     <td width='40%' align=\"center\">\n";
        //$salida .= "                       <a title='VER GRUPO FAMILIAR'>";
        //$salida .= "                       VER GRUPO";


    
    /*$salida .= "<div class='floatingWindowContent'>";
    $salida .= "<div  id='#F0F8FF' class='smallColorSquare' onclick=\"colorClick('#F0F8FF')\" title='AliceBlue' style='background-color:#F0F8FF'><span></span></div>";
    $salida .= "<div  id='#FAEBD7' class='smallColorSquare' onclick=\"colorClick('#FAEBD7')\" title='AntiqueWhite' style='background-color:#FAEBD7'><span></span></div>";
    $salida .= "<div  id='#00FFFF' class='smallColorSquare' onclick=\"colorClick('#00FFFF')\" title='Aqua and Cyan' style='background-color:#00FFFF'><span></span></div>";
    $salida .= "<div  id='#7FFFD4' class='smallColorSquare' onclick=\"colorClick('#7FFFD4')\" title='Aquamarine' style='background-color:#7FFFD4'><span></span></div>";
    $salida .= "<div  id='#F0FFFF' class='smallColorSquare' onclick=\"colorClick('#F0FFFF')\" title='Azure' style='background-color:#F0FFFF'><span></span></div>";
    $salida .= "<div  id='#F5F5DC' class='smallColorSquare' onclick=\"colorClick('#F5F5DC')\" title='Beige' style='background-color:#F5F5DC'><span></span></div>";
    $salida .= "<div  id='#FFE4C4' class='smallColorSquare' onclick=\"colorClick('#FFE4C4')\" title='Bisque' style='background-color:#FFE4C4'><span></span></div>";
    $salida .= "<div  id='#000000' class='smallColorSquare' onclick=\"colorClick('#000000')\" title='Black' style='background-color:#000000'><span></span></div>";
    $salida .= "<div  id='#FFEBCD' class='smallColorSquare' onclick=\"colorClick('#FFEBCD')\" title='BlanchedAlmond' style='background-color:#FFEBCD'><span></span></div>";
    $salida .= "<div  id='#0000FF' class='smallColorSquare' onclick=\"colorClick('#0000FF')\" title='Blue' style='background-color:#0000FF'><span></span></div>";
    $salida .= "<div  id='#8A2BE2' class='smallColorSquare' onclick=\"colorClick('#8A2BE2')\" title='BlueViolet' style='background-color:#8A2BE2'><span></span></div>";
    $salida .= "<div  id='#A52A2A' class='smallColorSquare' onclick=\"colorClick('#A52A2A')\" title='Brown' style='background-color:#A52A2A'><span></span></div>";
    $salida .= "<div  id='#DEB887' class='smallColorSquare' onclick=\"colorClick('#DEB887')\" title='BurlyWood' style='background-color:#DEB887'><span></span></div>";
    $salida .= "<div  id='#5F9EA0' class='smallColorSquare' onclick=\"colorClick('#5F9EA0')\" title='CadetBlue' style='background-color:#5F9EA0'><span></span></div>";
    $salida .= "<div  id='#7FFF00' class='smallColorSquare' onclick=\"colorClick('#7FFF00')\" title='Chartreuse' style='background-color:#7FFF00'><span></span></div>";
    $salida .= "<div  id='#D2691E' class='smallColorSquare' onclick=\"colorClick('#D2691E')\" title='Chocolate' style='background-color:#D2691E'><span></span></div>";
    $salida .= "<div  id='#FF7F50' class='smallColorSquare' onclick=\"colorClick('#FF7F50')\" title='Coral' style='background-color:#FF7F50'><span></span></div>";
    $salida .= "<div  id='#6495ED' class='smallColorSquare' onclick=\"colorClick('#6495ED')\" title='CornflowerBlue' style='background-color:#6495ED'><span></span></div>";
    $salida .= "<div  id='#FFF8DC' class='smallColorSquare' onclick=\"colorClick('#FFF8DC')\" title='Cornsilk' style='background-color:#FFF8DC'><span></span></div>";
    $salida .= "<div  id='#DC143C' class='smallColorSquare' onclick=\"colorClick('#DC143C')\" title='Crimson' style='background-color:#DC143C'><span></span></div>";
    $salida .= "<div  id='#00008B' class='smallColorSquare' onclick=\"colorClick('#00008B')\" title='DarkBlue' style='background-color:#00008B'><span></span></div>";
    $salida .= "<div  id='#008B8B' class='smallColorSquare' onclick=\"colorClick('#008B8B')\" title='DarkCyan' style='background-color:#008B8B'><span></span></div>";
    $salida .= "<div  id='#B8860B' class='smallColorSquare' onclick=\"colorClick('#B8860B')\" title='DarkGoldenRod' style='background-color:#B8860B'><span></span></div>";
    $salida .= "<div  id='#A9A9A9' class='smallColorSquare' onclick=\"colorClick('#A9A9A9')\" title='DarkGray' style='background-color:#A9A9A9'><span></span></div>";
    $salida .= "<div  id='#006400' class='smallColorSquare' onclick=\"colorClick('#006400')\" title='DarkGreen' style='background-color:#006400'><span></span></div>";
    $salida .= "<div  id='#BDB76B' class='smallColorSquare' onclick=\"colorClick('#BDB76B')\" title='DarkKhaki' style='background-color:#BDB76B'><span></span></div>";
    $salida .= "<div  id='#8B008B' class='smallColorSquare' onclick=\"colorClick('#8B008B')\" title='DarkMagenta' style='background-color:#8B008B'><span></span></div>";
    $salida .= "<div  id='#556B2F' class='smallColorSquare' onclick=\"colorClick('#556B2F')\" title='DarkOliveGreen' style='background-color:#556B2F'><span></span></div>";
    $salida .= "<div  id='#FF8C00' class='smallColorSquare' onclick=\"colorClick('#FF8C00')\" title='Darkorange' style='background-color:#FF8C00'><span></span></div>";
    $salida .= "<div  id='#9932CC' class='smallColorSquare' onclick=\"colorClick('#9932CC')\" title='DarkOrchid' style='background-color:#9932CC'><span></span></div>";
    $salida .= "<div  id='#8B0000' class='smallColorSquare' onclick=\"colorClick('#8B0000')\" title='DarkRed' style='background-color:#8B0000'><span></span></div>";
    $salida .= "<div  id='#E9967A' class='smallColorSquare' onclick=\"colorClick('#E9967A')\" title='DarkSalmon' style='background-color:#E9967A'><span></span></div>";
    $salida .= "<div  id='#8FBC8F' class='smallColorSquare' onclick=\"colorClick('#8FBC8F')\" title='DarkSeaGreen' style='background-color:#8FBC8F'><span></span></div>";
    $salida .= "<div  id='#483D8B' class='smallColorSquare' onclick=\"colorClick('#483D8B')\" title='DarkSlateBlue' style='background-color:#483D8B'><span></span></div>";
    $salida .= "<div  id='#2F4F4F' class='smallColorSquare' onclick=\"colorClick('#2F4F4F')\" title='DarkSlateGray' style='background-color:#2F4F4F'><span></span></div>";
    $salida .= "<div  id='#00CED1' class='smallColorSquare' onclick=\"colorClick('#00CED1')\" title='DarkTurquoise' style='background-color:#00CED1'><span></span></div>";
    $salida .= "<div  id='#9400D3' class='smallColorSquare' onclick=\"colorClick('#9400D3')\" title='DarkViolet' style='background-color:#9400D3'><span></span></div>";
    $salida .= "<div  id='#FF1493' class='smallColorSquare' onclick=\"colorClick('#FF1493')\" title='DeepPink' style='background-color:#FF1493'><span></span></div>";
    $salida .= "<div  id='#00BFFF' class='smallColorSquare' onclick=\"colorClick('#00BFFF')\" title='DeepSkyBlue' style='background-color:#00BFFF'><span></span></div>";
    $salida .= "<div  id='#696969' class='smallColorSquare' onclick=\"colorClick('#696969')\" title='DimGray' style='background-color:#696969'><span></span></div>";
    $salida .= "<div  id='#1E90FF' class='smallColorSquare' onclick=\"colorClick('#1E90FF')\" title='DodgerBlue' style='background-color:#1E90FF'><span></span></div>";
    $salida .= "<div  id='#D19275' class='smallColorSquare' onclick=\"colorClick('#D19275')\" title='Feldspar' style='background-color:#D19275'><span></span></div>";
    $salida .= "<div  id='#B22222' class='smallColorSquare' onclick=\"colorClick('#B22222')\" title='FireBrick' style='background-color:#B22222'><span></span></div>";
    $salida .= "<div  id='#FFFAF0' class='smallColorSquare' onclick=\"colorClick('#FFFAF0')\" title='FloralWhite' style='background-color:#FFFAF0'><span></span></div>";
    $salida .= "<div  id='#228B22' class='smallColorSquare' onclick=\"colorClick('#228B22')\" title='ForestGreen' style='background-color:#228B22'><span></span></div>";
    $salida .= "<div  id='#FF00FF' class='smallColorSquare' onclick=\"colorClick('#FF00FF')\" title='Fuchsia' style='background-color:#FF00FF'><span></span></div>";
    $salida .= "<div  id='#DCDCDC' class='smallColorSquare' onclick=\"colorClick('#DCDCDC')\" title='Gainsboro' style='background-color:#DCDCDC'><span></span></div>";
    $salida .= "<div  id='#F8F8FF' class='smallColorSquare' onclick=\"colorClick('#F8F8FF')\" title='GhostWhite' style='background-color:#F8F8FF'><span></span></div>";
    $salida .= "<div  id='#FFD700' class='smallColorSquare' onclick=\"colorClick('#FFD700')\" title='Gold' style='background-color:#FFD700'><span></span></div>";
    $salida .="<div  id='#DAA520' class='smallColorSquare' onclick=\"colorClick('#DAA520')\" title='GoldenRod' style='background-color:#DAA520'><span></span></div>";
    $salida .="<div  id='#808080' class='smallColorSquare' onclick=\"colorClick('#808080')\" title='Gray' style='background-color:#808080'><span></span></div>";
    $salida .="<div  id='#008000' class='smallColorSquare' onclick=\"colorClick('#008000')\" title='Green' style='background-color:#008000'><span></span></div>";
    $salida .="<div  id='#ADFF2F' class='smallColorSquare' onclick=\"colorClick('#ADFF2F')\" title='GreenYellow' style='background-color:#ADFF2F'><span></span></div>";
    $salida .="<div  id='#F0FFF0' class='smallColorSquare' onclick=\"colorClick('#F0FFF0')\" title='HoneyDew' style='background-color:#F0FFF0'><span></span></div>";
    $salida .="<div  id='#FF69B4' class='smallColorSquare' onclick=\"colorClick('#FF69B4')\" title='HotPink' style='background-color:#FF69B4'><span></span></div>";
    $salida .="<div  id='#CD5C5C' class='smallColorSquare' onclick=\"colorClick('#CD5C5C')\" title='IndianRed' style='background-color:#CD5C5C'><span></span></div>";
    $salida .="<div  id='#4B0082' class='smallColorSquare' onclick=\"colorClick('#4B0082')\" title='Indigo' style='background-color:#4B0082'><span></span></div>";
    $salida .="<div  id='#FFFFF0' class='smallColorSquare' onclick=\"colorClick('#FFFFF0')\" title='Ivory' style='background-color:#FFFFF0'><span></span></div>";
    $salida .="<div  id='#F0E68C' class='smallColorSquare' onclick=\"colorClick('#F0E68C')\" title='Khaki' style='background-color:#F0E68C'><span></span></div>";
    $salida .="<div  id='#E6E6FA' class='smallColorSquare' onclick=\"colorClick('#E6E6FA')\" title='Lavender' style='background-color:#E6E6FA'><span></span></div>";
    $salida .="<div  id='#FFF0F5' class='smallColorSquare' onclick=\"colorClick('#FFF0F5')\" title='LavenderBlush' style='background-color:#FFF0F5'><span></span></div>";
    $salida .="<div  id='#7CFC00' class='smallColorSquare' onclick=\"colorClick('#7CFC00')\" title='LawnGreen' style='background-color:#7CFC00'><span></span></div>";
    $salida.=" <div  id='#FFFACD' class='smallColorSquare' onclick=\"colorClick('#FFFACD')\" title='LemonChiffon' style='background-color:#FFFACD'><span></span></div>";
    $salida.=" <div  id='#ADD8E6' class='smallColorSquare' onclick=\"colorClick('#ADD8E6')\" title='LightBlue' style='background-color:#ADD8E6'><span></span></div>";
    $salida.=" <div  id='#F08080' class='smallColorSquare' onclick=\"colorClick('#F08080')\" title='LightCoral' style='background-color:#F08080'><span></span></div>";
    $salida.=" <div  id='#E0FFFF' class='smallColorSquare' onclick=\"colorClick('#E0FFFF')\" title='LightCyan' style='background-color:#E0FFFF'><span></span></div>";
    $salida.=" <div  id='#FAFAD2' class='smallColorSquare' onclick=\"colorClick('#FAFAD2')\" title='LightGoldenRodYellow' style='background-color:#FAFAD2'><span></span></div>";
    $salida.=" <div  id='#D3D3D3' class='smallColorSquare' onclick=\"colorClick('#D3D3D3')\" title='LightGrey' style='background-color:#D3D3D3'><span></span></div>";
    $salida.=" <div  id='#90EE90' class='smallColorSquare' onclick=\"colorClick('#90EE90')\" title='LightGreen' style='background-color:#90EE90'><span></span></div>";
    $salida.=" <div  id='#FFB6C1' class='smallColorSquare' onclick=\"colorClick('#FFB6C1')\" title='LightPink' style='background-color:#FFB6C1'><span></span></div>";
    $salida.=" <div  id='#FFA07A' class='smallColorSquare' onclick=\"colorClick('#FFA07A')\" title='LightSalmon' style='background-color:#FFA07A'><span></span></div>";
    $salida.=" <div  id='#20B2AA' class='smallColorSquare' onclick=\"colorClick('#20B2AA')\" title='LightSeaGreen' style='background-color:#20B2AA'><span></span></div>";
    $salida.=" <div  id='#87CEFA' class='smallColorSquare' onclick=\"colorClick('#87CEFA')\" title='LightSkyBlue' style='background-color:#87CEFA'><span></span></div>";
    $salida.=" <div  id='#8470FF' class='smallColorSquare' onclick=\"colorClick('#8470FF')\" title='LightSlateBlue' style='background-color:#8470FF'><span></span></div>";
    $salida.=" <div  id='#778899' class='smallColorSquare' onclick=\"colorClick('#778899')\" title='LightSlateGray' style='background-color:#778899'><span></span></div>";
    $salida.=" <div  id='#B0C4DE' class='smallColorSquare' onclick=\"colorClick('#B0C4DE')\" title='LightSteelBlue' style='background-color:#B0C4DE'><span></span></div>";
    $salida.=" <div  id='#FFFFE0' class='smallColorSquare' onclick=\"colorClick('#FFFFE0')\" title='LightYellow' style='background-color:#FFFFE0'><span></span></div>";
    $salida.=" <div  id='#00FF00' class='smallColorSquare' onclick=\"colorClick('#00FF00')\" title='Lime' style='background-color:#00FF00'><span></span></div>";
    $salida.=" <div  id='#32CD32' class='smallColorSquare' onclick=\"colorClick('#32CD32')\" title='LimeGreen' style='background-color:#32CD32'><span></span></div>";
    $salida.=" <div  id='#FAF0E6' class='smallColorSquare' onclick=\"colorClick('#FAF0E6')\" title='Linen' style='background-color:#FAF0E6'><span></span></div>";
    $salida.=" <div  id='#FF00FF' class='smallColorSquare' onclick=\"colorClick('#FF00FF')\" title='Magenta' style='background-color:#FF00FF'><span></span></div>";
    $salida.=" <div  id='#800000' class='smallColorSquare' onclick=\"colorClick('#800000')\" title='Maroon' style='background-color:#800000'><span></span></div>";
    $salida.=" <div  id='#66CDAA' class='smallColorSquare' onclick=\"colorClick('#66CDAA')\" title='MediumAquaMarine' style='background-color:#66CDAA'><span></span></div>";
    $salida.=" <div  id='#0000CD' class='smallColorSquare' onclick=\"colorClick('#0000CD')\" title='MediumBlue' style='background-color:#0000CD'><span></span></div>";
    $salida.=" <div  id='#BA55D3' class='smallColorSquare' onclick=\"colorClick('#BA55D3')\" title='MediumOrchid' style='background-color:#BA55D3'><span></span></div>";
    $salida.=" <div  id='#9370D8' class='smallColorSquare' onclick=\"colorClick('#9370D8')\" title='MediumPurple' style='background-color:#9370D8'><span></span></div>";
    $salida.=" <div  id='#3CB371' class='smallColorSquare' onclick=\"colorClick('#3CB371')\" title='MediumSeaGreen' style='background-color:#3CB371'><span></span></div>";
    $salida.=" <div  id='#7B68EE' class='smallColorSquare' onclick=\"colorClick('#7B68EE')\" title='MediumSlateBlue' style='background-color:#7B68EE'><span></span></div>";
    $salida.=" <div  id='#00FA9A' class='smallColorSquare' onclick=\"colorClick('#00FA9A')\" title='MediumSpringGreen' style='background-color:#00FA9A'><span></span></div>";
    $salida.=" <div  id='#48D1CC' class='smallColorSquare' onclick=\"colorClick('#48D1CC')\" title='MediumTurquoise' style='background-color:#48D1CC'><span></span></div>";
    $salida.=" <div  id='#C71585' class='smallColorSquare' onclick=\"colorClick('#C71585')\" title='MediumVioletRed' style='background-color:#C71585'><span></span></div>";
    $salida.=" <div  id='#191970' class='smallColorSquare' onclick=\"colorClick('#191970')\" title='MidnightBlue' style='background-color:#191970'><span></span></div>";
    $salida.=" <div  id='#F5FFFA' class='smallColorSquare' onclick=\"colorClick('#F5FFFA')\" title='MintCream' style='background-color:#F5FFFA'><span></span></div>";
    $salida.=" <div  id='#FFE4E1' class='smallColorSquare' onclick=\"colorClick('#FFE4E1')\" title='MistyRose' style='background-color:#FFE4E1'><span></span></div>";
    $salida.=" <div  id='#FFE4B5' class='smallColorSquare' onclick=\"colorClick('#FFE4B5')\" title='Moccasin' style='background-color:#FFE4B5'><span></span></div>";
    $salida.=" <div  id='#FFDEAD' class='smallColorSquare' onclick=\"colorClick('#FFDEAD')\" title='NavajoWhite' style='background-color:#FFDEAD'><span></span></div>";
    $salida.=" <div  id='#000080' class='smallColorSquare' onclick=\"colorClick('#000080')\" title='Navy' style='background-color:#000080'><span></span></div>";
    $salida.=" <div  id='#FDF5E6' class='smallColorSquare' onclick=\"colorClick('#FDF5E6')\" title='OldLace' style='background-color:#FDF5E6'><span></span></div>";
    $salida.=" <div  id='#808000' class='smallColorSquare' onclick=\"colorClick('#808000')\" title='Olive' style='background-color:#808000'><span></span></div>";
    $salida.=" <div  id='#6B8E23' class='smallColorSquare' onclick=\"colorClick('#6B8E23')\" title='OliveDrab' style='background-color:#6B8E23'><span></span></div>";
    $salida.=" <div  id='#FFA500' class='smallColorSquare' onclick=\"colorClick('#FFA500')\" title='Orange' style='background-color:#FFA500'><span></span></div>";
    $salida.=" <div  id='#FF4500' class='smallColorSquare' onclick=\"colorClick('#FF4500')\" title='OrangeRed' style='background-color:#FF4500'><span></span></div>";
    $salida.=" <div  id='#DA70D6' class='smallColorSquare' onclick=\"colorClick('#DA70D6')\" title='Orchid' style='background-color:#DA70D6'><span></span></div>";
    $salida.=" <div  id='#EEE8AA' class='smallColorSquare' onclick=\"colorClick('#EEE8AA')\" title='PaleGoldenRod' style='background-color:#EEE8AA'><span></span></div>";
    $salida.=" <div  id='#98FB98' class='smallColorSquare' onclick=\"colorClick('#98FB98')\" title='PaleGreen' style='background-color:#98FB98'><span></span></div>";
    $salida.=" <div  id='#AFEEEE' class='smallColorSquare' onclick=\"colorClick('#AFEEEE')\" title='PaleTurquoise' style='background-color:#AFEEEE'><span></span></div>";
    $salida.=" <div  id='#D87093' class='smallColorSquare' onclick=\"colorClick('#D87093')\" title='PaleVioletRed' style='background-color:#D87093'><span></span></div>";
    $salida.=" <div  id='#FFEFD5' class='smallColorSquare' onclick=\"colorClick('#FFEFD5')\" title='PapayaWhip' style='background-color:#FFEFD5'><span></span></div>";
    $salida.=" <div  id='#FFDAB9' class='smallColorSquare' onclick=\"colorClick('#FFDAB9')\" title='PeachPuff' style='background-color:#FFDAB9'><span></span></div>";
    $salida.=" <div  id='#CD853F' class='smallColorSquare' onclick=\"colorClick('#CD853F')\" title='Peru' style='background-color:#CD853F'><span></span></div>";
    $salida.=" <div  id='#FFC0CB' class='smallColorSquare' onclick=\"colorClick('#FFC0CB')\" title='Pink' style='background-color:#FFC0CB'><span></span></div>";
    $salida.=" <div  id='#DDA0DD' class='smallColorSquare' onclick=\"colorClick('#DDA0DD')\" title='Plum' style='background-color:#DDA0DD'><span></span></div>";
    $salida.=" <div  id='#B0E0E6' class='smallColorSquare' onclick=\"colorClick('#B0E0E6')\" title='PowderBlue' style='background-color:#B0E0E6'><span></span></div>";
    $salida.=" <div  id='#800080' class='smallColorSquare' onclick=\"colorClick('#800080')\" title='Purple' style='background-color:#800080'><span></span></div>";
    $salida.=" <div  id='#FF0000' class='smallColorSquare' onclick=\"colorClick('#FF0000')\" title='Red' style='background-color:#FF0000'><span></span></div>";
    $salida.=" <div  id='#BC8F8F' class='smallColorSquare' onclick=\"colorClick('#BC8F8F')\" title='RosyBrown' style='background-color:#BC8F8F'><span></span></div>";
    $salida.=" <div  id='#4169E1' class='smallColorSquare' onclick=\"colorClick('#4169E1')\" title='RoyalBlue' style='background-color:#4169E1'><span></span></div>";
    $salida.=" <div  id='#8B4513' class='smallColorSquare' onclick=\"colorClick('#8B4513')\" title='SaddleBrown' style='background-color:#8B4513'><span></span></div>";
    $salida.=" <div  id='#FA8072' class='smallColorSquare' onclick=\"colorClick('#FA8072')\" title='Salmon' style='background-color:#FA8072'><span></span></div>";
    $salida .= "<div id='#F4A460' class='smallColorSquare' onclick=\"colorClick('#F4A460')\" title='SandyBrown' style='background-color:#F4A460'><span></span></div>";
    $salida .= "<div id='#2E8B57' class='smallColorSquare' onclick=\"colorClick('#2E8B57')\" title='SeaGreen' style='background-color:#2E8B57'><span></span></div>";
    $salida .= "<div id='#FFF5EE' class='smallColorSquare' onclick=\"colorClick('#FFF5EE')\" title='SeaShell' style='background-color:#FFF5EE'><span></span></div>";
    $salida .= "<div id='#A0522D' class='smallColorSquare' onclick=\"colorClick('#A0522D')\" title='Sienna' style='background-color:#A0522D'><span></span></div>";
    $salida .= "<div id='#C0C0C0' class='smallColorSquare' onclick=\"colorClick('#C0C0C0')\" title='Silver' style='background-color:#C0C0C0'><span></span></div>";
    $salida .= "<div id='#87CEEB' class='smallColorSquare' onclick=\"colorClick('#87CEEB')\" title='SkyBlue' style='background-color:#87CEEB'><span></span></div>";
    $salida .= "<div id='#6A5ACD' class='smallColorSquare' onclick=\"colorClick('#6A5ACD')\" title='SlateBlue' style='background-color:#6A5ACD'><span></span></div>";
    $salida .= "<div id='#708090' class='smallColorSquare' onclick=\"colorClick('#708090')\" title='SlateGray' style='background-color:#708090'><span></span></div>";
    $salida .= "<div id='#FFFAFA' class='smallColorSquare' onclick=\"colorClick('#FFFAFA')\" title='Snow' style='background-color:#FFFAFA'><span></span></div>";
    $salida .= "<div id='#00FF7F' class='smallColorSquare' onclick=\"colorClick('#00FF7F')\" title='SpringGreen' style='background-color:#00FF7F'><span></span></div>";
    $salida .= "<div id='#4682B4' class='smallColorSquare' onclick=\"colorClick('#4682B4')\" title='SteelBlue' style='background-color:#4682B4'><span></span></div>";
    $salida .= "<div id='#D2B48C' class='smallColorSquare' onclick=\"colorClick('#D2B48C')\" title='Tan' style='background-color:#D2B48C'><span></span></div>";
    $salida .= "<div id='#008080' class='smallColorSquare' onclick=\"colorClick('#008080')\" title='Teal' style='background-color:#008080'><span></span></div>";
    $salida .= "<div id='#D8BFD8' class='smallColorSquare' onclick=\"colorClick('#D8BFD8')\" title='Thistle' style='background-color:#D8BFD8'><span></span></div>";
    $salida .= "<div id='#FF6347' class='smallColorSquare' onclick=\"colorClick('#FF6347')\" title='Tomato' style='background-color:#FF6347'><span></span></div>";
    $salida .= "<div id='#40E0D0' class='smallColorSquare' onclick=\"colorClick('#40E0D0')\" title='Turquoise' style='background-color:#40E0D0'><span></span></div>";
    $salida .= "<div id='#EE82EE' class='smallColorSquare' onclick=\"colorClick('#EE82EE')\" title='Violet' style='background-color:#EE82EE'><span></span></div>";
    $salida .= "<div id='#D02090' class='smallColorSquare' onclick=\"colorClick('#D02090')\" title='VioletRed' style='background-color:#D02090'><span></span></div>";
    $salida .= "<div id='#F5DEB3' class='smallColorSquare' onclick=\"colorClick('#F5DEB3')\" title='Wheat' style='background-color:#F5DEB3'><span></span></div>";
    $salida .= "<div id='#FFFFFF' class='smallColorSquare' onclick=\"colorClick('#FFFFFF')\" title='White' style='background-color:#FFFFFF'><span></span></div>";
    $salida .= "<div id='#F5F5F5' class='smallColorSquare' onclick=\"colorClick('#F5F5F5')\" title='WhiteSmoke' style='background-color:#F5F5F5'><span></span></div>";
    $salida .= "<div id='#FFFF00' class='smallColorSquare' onclick=\"colorClick('#FFFF00')\" title='Yellow' style='background-color:#FFFF00'><span></span></div>";
    $salida .= "<div id='#9ACD32' class='smallColorSquare' onclick=\"colorClick('#9ACD32')\" title='YellowGreen' style='background-color:#9ACD32'><span></span></div>";
    $salida .= "</div>";*/
    
    $salida .= "<div class='floatingWindowContent'>";
    $salida .= "<div  id='#F0F8FF' class='smallColorSquare' onclick=\"colorClick('#F0F8FF')\" title='AliceBlue' style='background-color:#F0F8FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FAEBD7' class='smallColorSquare' onclick=\"colorClick('#FAEBD7')\" title='AntiqueWhite' style='background-color:#FAEBD7'><span>&nbsp</span></div>";
    $salida .= "<div  id='#00FFFF' class='smallColorSquare' onclick=\"colorClick('#00FFFF')\" title='Aqua and Cyan' style='background-color:#00FFFF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#7FFFD4' class='smallColorSquare' onclick=\"colorClick('#7FFFD4')\" title='Aquamarine' style='background-color:#7FFFD4'><span>&nbsp</span></div>";
    $salida .= "<div  id='#F0FFFF' class='smallColorSquare' onclick=\"colorClick('#F0FFFF')\" title='Azure' style='background-color:#F0FFFF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#F5F5DC' class='smallColorSquare' onclick=\"colorClick('#F5F5DC')\" title='Beige' style='background-color:#F5F5DC'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFE4C4' class='smallColorSquare' onclick=\"colorClick('#FFE4C4')\" title='Bisque' style='background-color:#FFE4C4'><span>&nbsp</span></div>";
    $salida .= "<div  id='#000000' class='smallColorSquare' onclick=\"colorClick('#000000')\" title='Black' style='background-color:#000000'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFEBCD' class='smallColorSquare' onclick=\"colorClick('#FFEBCD')\" title='BlanchedAlmond' style='background-color:#FFEBCD'><span>&nbsp</span></div>";
    $salida .= "<div  id='#0000FF' class='smallColorSquare' onclick=\"colorClick('#0000FF')\" title='Blue' style='background-color:#0000FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#8A2BE2' class='smallColorSquare' onclick=\"colorClick('#8A2BE2')\" title='BlueViolet' style='background-color:#8A2BE2'><span>&nbsp</span></div>";
    $salida .= "<div  id='#A52A2A' class='smallColorSquare' onclick=\"colorClick('#A52A2A')\" title='Brown' style='background-color:#A52A2A'><span>&nbsp</span></div>";
    $salida .= "<div  id='#DEB887' class='smallColorSquare' onclick=\"colorClick('#DEB887')\" title='BurlyWood' style='background-color:#DEB887'><span>&nbsp</span></div>";
    $salida .= "<div  id='#5F9EA0' class='smallColorSquare' onclick=\"colorClick('#5F9EA0')\" title='CadetBlue' style='background-color:#5F9EA0'><span>&nbsp</span></div>";
    $salida .= "<div  id='#7FFF00' class='smallColorSquare' onclick=\"colorClick('#7FFF00')\" title='Chartreuse' style='background-color:#7FFF00'><span>&nbsp</span></div>";
    $salida .= "<div  id='#D2691E' class='smallColorSquare' onclick=\"colorClick('#D2691E')\" title='Chocolate' style='background-color:#D2691E'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FF7F50' class='smallColorSquare' onclick=\"colorClick('#FF7F50')\" title='Coral' style='background-color:#FF7F50'><span>&nbsp</span></div>";
    $salida .= "<div  id='#6495ED' class='smallColorSquare' onclick=\"colorClick('#6495ED')\" title='CornflowerBlue' style='background-color:#6495ED'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFF8DC' class='smallColorSquare' onclick=\"colorClick('#FFF8DC')\" title='Cornsilk' style='background-color:#FFF8DC'><span>&nbsp</span></div>";
    $salida .= "<div  id='#DC143C' class='smallColorSquare' onclick=\"colorClick('#DC143C')\" title='Crimson' style='background-color:#DC143C'><span>&nbsp</span></div>";
    $salida .= "<div  id='#00008B' class='smallColorSquare' onclick=\"colorClick('#00008B')\" title='DarkBlue' style='background-color:#00008B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#008B8B' class='smallColorSquare' onclick=\"colorClick('#008B8B')\" title='DarkCyan' style='background-color:#008B8B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#B8860B' class='smallColorSquare' onclick=\"colorClick('#B8860B')\" title='DarkGoldenRod' style='background-color:#B8860B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#A9A9A9' class='smallColorSquare' onclick=\"colorClick('#A9A9A9')\" title='DarkGray' style='background-color:#A9A9A9'><span>&nbsp</span></div>";
    $salida .= "<div  id='#006400' class='smallColorSquare' onclick=\"colorClick('#006400')\" title='DarkGreen' style='background-color:#006400'><span>&nbsp</span></div>";
    $salida .= "<div  id='#BDB76B' class='smallColorSquare' onclick=\"colorClick('#BDB76B')\" title='DarkKhaki' style='background-color:#BDB76B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#8B008B' class='smallColorSquare' onclick=\"colorClick('#8B008B')\" title='DarkMagenta' style='background-color:#8B008B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#556B2F' class='smallColorSquare' onclick=\"colorClick('#556B2F')\" title='DarkOliveGreen' style='background-color:#556B2F'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FF8C00' class='smallColorSquare' onclick=\"colorClick('#FF8C00')\" title='Darkorange' style='background-color:#FF8C00'><span>&nbsp</span></div>";
    $salida .= "<div  id='#9932CC' class='smallColorSquare' onclick=\"colorClick('#9932CC')\" title='DarkOrchid' style='background-color:#9932CC'><span>&nbsp</span></div>";
    $salida .= "<div  id='#8B0000' class='smallColorSquare' onclick=\"colorClick('#8B0000')\" title='DarkRed' style='background-color:#8B0000'><span>&nbsp</span></div>";
    $salida .= "<div  id='#E9967A' class='smallColorSquare' onclick=\"colorClick('#E9967A')\" title='DarkSalmon' style='background-color:#E9967A'><span>&nbsp</span></div>";
    $salida .= "<div  id='#8FBC8F' class='smallColorSquare' onclick=\"colorClick('#8FBC8F')\" title='DarkSeaGreen' style='background-color:#8FBC8F'><span>&nbsp</span></div>";
    $salida .= "<div  id='#483D8B' class='smallColorSquare' onclick=\"colorClick('#483D8B')\" title='DarkSlateBlue' style='background-color:#483D8B'><span>&nbsp</span></div>";
    $salida .= "<div  id='#2F4F4F' class='smallColorSquare' onclick=\"colorClick('#2F4F4F')\" title='DarkSlateGray' style='background-color:#2F4F4F'><span>&nbsp</span></div>";
    $salida .= "<div  id='#00CED1' class='smallColorSquare' onclick=\"colorClick('#00CED1')\" title='DarkTurquoise' style='background-color:#00CED1'><span>&nbsp</span></div>";
    $salida .= "<div  id='#9400D3' class='smallColorSquare' onclick=\"colorClick('#9400D3')\" title='DarkViolet' style='background-color:#9400D3'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FF1493' class='smallColorSquare' onclick=\"colorClick('#FF1493')\" title='DeepPink' style='background-color:#FF1493'><span>&nbsp</span></div>";
    $salida .= "<div  id='#00BFFF' class='smallColorSquare' onclick=\"colorClick('#00BFFF')\" title='DeepSkyBlue' style='background-color:#00BFFF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#696969' class='smallColorSquare' onclick=\"colorClick('#696969')\" title='DimGray' style='background-color:#696969'><span>&nbsp</span></div>";
    $salida .= "<div  id='#1E90FF' class='smallColorSquare' onclick=\"colorClick('#1E90FF')\" title='DodgerBlue' style='background-color:#1E90FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#D19275' class='smallColorSquare' onclick=\"colorClick('#D19275')\" title='Feldspar' style='background-color:#D19275'><span>&nbsp</span></div>";
    $salida .= "<div  id='#B22222' class='smallColorSquare' onclick=\"colorClick('#B22222')\" title='FireBrick' style='background-color:#B22222'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFFAF0' class='smallColorSquare' onclick=\"colorClick('#FFFAF0')\" title='FloralWhite' style='background-color:#FFFAF0'><span>&nbsp</span></div>";
    $salida .= "<div  id='#228B22' class='smallColorSquare' onclick=\"colorClick('#228B22')\" title='ForestGreen' style='background-color:#228B22'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FF00FF' class='smallColorSquare' onclick=\"colorClick('#FF00FF')\" title='Fuchsia' style='background-color:#FF00FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#DCDCDC' class='smallColorSquare' onclick=\"colorClick('#DCDCDC')\" title='Gainsboro' style='background-color:#DCDCDC'><span>&nbsp</span></div>";
    $salida .= "<div  id='#F8F8FF' class='smallColorSquare' onclick=\"colorClick('#F8F8FF')\" title='GhostWhite' style='background-color:#F8F8FF'><span>&nbsp</span></div>";
    $salida .= "<div  id='#FFD700' class='smallColorSquare' onclick=\"colorClick('#FFD700')\" title='Gold' style='background-color:#FFD700'><span>&nbsp</span></div>";
    $salida .="<div  id='#DAA520' class='smallColorSquare' onclick=\"colorClick('#DAA520')\" title='GoldenRod' style='background-color:#DAA520'><span>&nbsp</span></div>";
    $salida .="<div  id='#808080' class='smallColorSquare' onclick=\"colorClick('#808080')\" title='Gray' style='background-color:#808080'><span>&nbsp</span></div>";
    $salida .="<div  id='#008000' class='smallColorSquare' onclick=\"colorClick('#008000')\" title='Green' style='background-color:#008000'><span>&nbsp</span></div>";
    $salida .="<div  id='#ADFF2F' class='smallColorSquare' onclick=\"colorClick('#ADFF2F')\" title='GreenYellow' style='background-color:#ADFF2F'><span>&nbsp</span></div>";
    $salida .="<div  id='#F0FFF0' class='smallColorSquare' onclick=\"colorClick('#F0FFF0')\" title='HoneyDew' style='background-color:#F0FFF0'><span>&nbsp</span></div>";
    $salida .="<div  id='#FF69B4' class='smallColorSquare' onclick=\"colorClick('#FF69B4')\" title='HotPink' style='background-color:#FF69B4'><span>&nbsp</span></div>";
    $salida .="<div  id='#CD5C5C' class='smallColorSquare' onclick=\"colorClick('#CD5C5C')\" title='IndianRed' style='background-color:#CD5C5C'><span>&nbsp</span></div>";
    $salida .="<div  id='#4B0082' class='smallColorSquare' onclick=\"colorClick('#4B0082')\" title='Indigo' style='background-color:#4B0082'><span>&nbsp</span></div>";
    $salida .="<div  id='#FFFFF0' class='smallColorSquare' onclick=\"colorClick('#FFFFF0')\" title='Ivory' style='background-color:#FFFFF0'><span>&nbsp</span></div>";
    $salida .="<div  id='#F0E68C' class='smallColorSquare' onclick=\"colorClick('#F0E68C')\" title='Khaki' style='background-color:#F0E68C'><span>&nbsp</span></div>";
    $salida .="<div  id='#E6E6FA' class='smallColorSquare' onclick=\"colorClick('#E6E6FA')\" title='Lavender' style='background-color:#E6E6FA'><span>&nbsp</span></div>";
    $salida .="<div  id='#FFF0F5' class='smallColorSquare' onclick=\"colorClick('#FFF0F5')\" title='LavenderBlush' style='background-color:#FFF0F5'><span>&nbsp</span></div>";
    $salida .="<div  id='#7CFC00' class='smallColorSquare' onclick=\"colorClick('#7CFC00')\" title='LawnGreen' style='background-color:#7CFC00'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFFACD' class='smallColorSquare' onclick=\"colorClick('#FFFACD')\" title='LemonChiffon' style='background-color:#FFFACD'><span>&nbsp</span></div>";
    $salida.=" <div  id='#ADD8E6' class='smallColorSquare' onclick=\"colorClick('#ADD8E6')\" title='LightBlue' style='background-color:#ADD8E6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#F08080' class='smallColorSquare' onclick=\"colorClick('#F08080')\" title='LightCoral' style='background-color:#F08080'><span>&nbsp</span></div>";
    $salida.=" <div  id='#E0FFFF' class='smallColorSquare' onclick=\"colorClick('#E0FFFF')\" title='LightCyan' style='background-color:#E0FFFF'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FAFAD2' class='smallColorSquare' onclick=\"colorClick('#FAFAD2')\" title='LightGoldenRodYellow' style='background-color:#FAFAD2'><span>&nbsp</span></div>";
    $salida.=" <div  id='#D3D3D3' class='smallColorSquare' onclick=\"colorClick('#D3D3D3')\" title='LightGrey' style='background-color:#D3D3D3'><span>&nbsp</span></div>";
    $salida.=" <div  id='#90EE90' class='smallColorSquare' onclick=\"colorClick('#90EE90')\" title='LightGreen' style='background-color:#90EE90'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFB6C1' class='smallColorSquare' onclick=\"colorClick('#FFB6C1')\" title='LightPink' style='background-color:#FFB6C1'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFA07A' class='smallColorSquare' onclick=\"colorClick('#FFA07A')\" title='LightSalmon' style='background-color:#FFA07A'><span>&nbsp</span></div>";
    $salida.=" <div  id='#20B2AA' class='smallColorSquare' onclick=\"colorClick('#20B2AA')\" title='LightSeaGreen' style='background-color:#20B2AA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#87CEFA' class='smallColorSquare' onclick=\"colorClick('#87CEFA')\" title='LightSkyBlue' style='background-color:#87CEFA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#8470FF' class='smallColorSquare' onclick=\"colorClick('#8470FF')\" title='LightSlateBlue' style='background-color:#8470FF'><span>&nbsp</span></div>";
    $salida.=" <div  id='#778899' class='smallColorSquare' onclick=\"colorClick('#778899')\" title='LightSlateGray' style='background-color:#778899'><span>&nbsp</span></div>";
    $salida.=" <div  id='#B0C4DE' class='smallColorSquare' onclick=\"colorClick('#B0C4DE')\" title='LightSteelBlue' style='background-color:#B0C4DE'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFFFE0' class='smallColorSquare' onclick=\"colorClick('#FFFFE0')\" title='LightYellow' style='background-color:#FFFFE0'><span>&nbsp</span></div>";
    $salida.=" <div  id='#00FF00' class='smallColorSquare' onclick=\"colorClick('#00FF00')\" title='Lime' style='background-color:#00FF00'><span>&nbsp</span></div>";
    $salida.=" <div  id='#32CD32' class='smallColorSquare' onclick=\"colorClick('#32CD32')\" title='LimeGreen' style='background-color:#32CD32'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FAF0E6' class='smallColorSquare' onclick=\"colorClick('#FAF0E6')\" title='Linen' style='background-color:#FAF0E6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FF00FF' class='smallColorSquare' onclick=\"colorClick('#FF00FF')\" title='Magenta' style='background-color:#FF00FF'><span>&nbsp</span></div>";
    $salida.=" <div  id='#800000' class='smallColorSquare' onclick=\"colorClick('#800000')\" title='Maroon' style='background-color:#800000'><span>&nbsp</span></div>";
    $salida.=" <div  id='#66CDAA' class='smallColorSquare' onclick=\"colorClick('#66CDAA')\" title='MediumAquaMarine' style='background-color:#66CDAA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#0000CD' class='smallColorSquare' onclick=\"colorClick('#0000CD')\" title='MediumBlue' style='background-color:#0000CD'><span>&nbsp</span></div>";
    $salida.=" <div  id='#BA55D3' class='smallColorSquare' onclick=\"colorClick('#BA55D3')\" title='MediumOrchid' style='background-color:#BA55D3'><span>&nbsp</span></div>";
    $salida.=" <div  id='#9370D8' class='smallColorSquare' onclick=\"colorClick('#9370D8')\" title='MediumPurple' style='background-color:#9370D8'><span>&nbsp</span></div>";
    $salida.=" <div  id='#3CB371' class='smallColorSquare' onclick=\"colorClick('#3CB371')\" title='MediumSeaGreen' style='background-color:#3CB371'><span>&nbsp</span></div>";
    $salida.=" <div  id='#7B68EE' class='smallColorSquare' onclick=\"colorClick('#7B68EE')\" title='MediumSlateBlue' style='background-color:#7B68EE'><span>&nbsp</span></div>";
    $salida.=" <div  id='#00FA9A' class='smallColorSquare' onclick=\"colorClick('#00FA9A')\" title='MediumSpringGreen' style='background-color:#00FA9A'><span>&nbsp</span></div>";
    $salida.=" <div  id='#48D1CC' class='smallColorSquare' onclick=\"colorClick('#48D1CC')\" title='MediumTurquoise' style='background-color:#48D1CC'><span>&nbsp</span></div>";
    $salida.=" <div  id='#C71585' class='smallColorSquare' onclick=\"colorClick('#C71585')\" title='MediumVioletRed' style='background-color:#C71585'><span>&nbsp</span></div>";
    $salida.=" <div  id='#191970' class='smallColorSquare' onclick=\"colorClick('#191970')\" title='MidnightBlue' style='background-color:#191970'><span>&nbsp</span></div>";
    $salida.=" <div  id='#F5FFFA' class='smallColorSquare' onclick=\"colorClick('#F5FFFA')\" title='MintCream' style='background-color:#F5FFFA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFE4E1' class='smallColorSquare' onclick=\"colorClick('#FFE4E1')\" title='MistyRose' style='background-color:#FFE4E1'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFE4B5' class='smallColorSquare' onclick=\"colorClick('#FFE4B5')\" title='Moccasin' style='background-color:#FFE4B5'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFDEAD' class='smallColorSquare' onclick=\"colorClick('#FFDEAD')\" title='NavajoWhite' style='background-color:#FFDEAD'><span>&nbsp</span></div>";
    $salida.=" <div  id='#000080' class='smallColorSquare' onclick=\"colorClick('#000080')\" title='Navy' style='background-color:#000080'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FDF5E6' class='smallColorSquare' onclick=\"colorClick('#FDF5E6')\" title='OldLace' style='background-color:#FDF5E6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#808000' class='smallColorSquare' onclick=\"colorClick('#808000')\" title='Olive' style='background-color:#808000'><span>&nbsp</span></div>";
    $salida.=" <div  id='#6B8E23' class='smallColorSquare' onclick=\"colorClick('#6B8E23')\" title='OliveDrab' style='background-color:#6B8E23'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFA500' class='smallColorSquare' onclick=\"colorClick('#FFA500')\" title='Orange' style='background-color:#FFA500'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FF4500' class='smallColorSquare' onclick=\"colorClick('#FF4500')\" title='OrangeRed' style='background-color:#FF4500'><span>&nbsp</span></div>";
    $salida.=" <div  id='#DA70D6' class='smallColorSquare' onclick=\"colorClick('#DA70D6')\" title='Orchid' style='background-color:#DA70D6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#EEE8AA' class='smallColorSquare' onclick=\"colorClick('#EEE8AA')\" title='PaleGoldenRod' style='background-color:#EEE8AA'><span>&nbsp</span></div>";
    $salida.=" <div  id='#98FB98' class='smallColorSquare' onclick=\"colorClick('#98FB98')\" title='PaleGreen' style='background-color:#98FB98'><span>&nbsp</span></div>";
    $salida.=" <div  id='#AFEEEE' class='smallColorSquare' onclick=\"colorClick('#AFEEEE')\" title='PaleTurquoise' style='background-color:#AFEEEE'><span>&nbsp</span></div>";
    $salida.=" <div  id='#D87093' class='smallColorSquare' onclick=\"colorClick('#D87093')\" title='PaleVioletRed' style='background-color:#D87093'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFEFD5' class='smallColorSquare' onclick=\"colorClick('#FFEFD5')\" title='PapayaWhip' style='background-color:#FFEFD5'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFDAB9' class='smallColorSquare' onclick=\"colorClick('#FFDAB9')\" title='PeachPuff' style='background-color:#FFDAB9'><span>&nbsp</span></div>";
    $salida.=" <div  id='#CD853F' class='smallColorSquare' onclick=\"colorClick('#CD853F')\" title='Peru' style='background-color:#CD853F'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FFC0CB' class='smallColorSquare' onclick=\"colorClick('#FFC0CB')\" title='Pink' style='background-color:#FFC0CB'><span>&nbsp</span></div>";
    $salida.=" <div  id='#DDA0DD' class='smallColorSquare' onclick=\"colorClick('#DDA0DD')\" title='Plum' style='background-color:#DDA0DD'><span>&nbsp</span></div>";
    $salida.=" <div  id='#B0E0E6' class='smallColorSquare' onclick=\"colorClick('#B0E0E6')\" title='PowderBlue' style='background-color:#B0E0E6'><span>&nbsp</span></div>";
    $salida.=" <div  id='#800080' class='smallColorSquare' onclick=\"colorClick('#800080')\" title='Purple' style='background-color:#800080'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FF0000' class='smallColorSquare' onclick=\"colorClick('#FF0000')\" title='Red' style='background-color:#FF0000'><span>&nbsp</span></div>";
    $salida.=" <div  id='#BC8F8F' class='smallColorSquare' onclick=\"colorClick('#BC8F8F')\" title='RosyBrown' style='background-color:#BC8F8F'><span>&nbsp</span></div>";
    $salida.=" <div  id='#4169E1' class='smallColorSquare' onclick=\"colorClick('#4169E1')\" title='RoyalBlue' style='background-color:#4169E1'><span>&nbsp</span></div>";
    $salida.=" <div  id='#8B4513' class='smallColorSquare' onclick=\"colorClick('#8B4513')\" title='SaddleBrown' style='background-color:#8B4513'><span>&nbsp</span></div>";
    $salida.=" <div  id='#FA8072' class='smallColorSquare' onclick=\"colorClick('#FA8072')\" title='Salmon' style='background-color:#FA8072'><span>&nbsp</span></div>";
    $salida .= "<div id='#F4A460' class='smallColorSquare' onclick=\"colorClick('#F4A460')\" title='SandyBrown' style='background-color:#F4A460'><span>&nbsp</span></div>";
    $salida .= "<div id='#2E8B57' class='smallColorSquare' onclick=\"colorClick('#2E8B57')\" title='SeaGreen' style='background-color:#2E8B57'><span>&nbsp</span></div>";
    $salida .= "<div id='#FFF5EE' class='smallColorSquare' onclick=\"colorClick('#FFF5EE')\" title='SeaShell' style='background-color:#FFF5EE'><span>&nbsp</span></div>";
    $salida .= "<div id='#A0522D' class='smallColorSquare' onclick=\"colorClick('#A0522D')\" title='Sienna' style='background-color:#A0522D'><span>&nbsp</span></div>";
    $salida .= "<div id='#C0C0C0' class='smallColorSquare' onclick=\"colorClick('#C0C0C0')\" title='Silver' style='background-color:#C0C0C0'><span>&nbsp</span></div>";
    $salida .= "<div id='#87CEEB' class='smallColorSquare' onclick=\"colorClick('#87CEEB')\" title='SkyBlue' style='background-color:#87CEEB'><span>&nbsp</span></div>";
    $salida .= "<div id='#6A5ACD' class='smallColorSquare' onclick=\"colorClick('#6A5ACD')\" title='SlateBlue' style='background-color:#6A5ACD'><span>&nbsp</span></div>";
    $salida .= "<div id='#708090' class='smallColorSquare' onclick=\"colorClick('#708090')\" title='SlateGray' style='background-color:#708090'><span>&nbsp</span></div>";
    $salida .= "<div id='#FFFAFA' class='smallColorSquare' onclick=\"colorClick('#FFFAFA')\" title='Snow' style='background-color:#FFFAFA'><span>&nbsp</span></div>";
    $salida .= "<div id='#00FF7F' class='smallColorSquare' onclick=\"colorClick('#00FF7F')\" title='SpringGreen' style='background-color:#00FF7F'><span>&nbsp</span></div>";
    $salida .= "<div id='#4682B4' class='smallColorSquare' onclick=\"colorClick('#4682B4')\" title='SteelBlue' style='background-color:#4682B4'><span>&nbsp</span></div>";
    $salida .= "<div id='#D2B48C' class='smallColorSquare' onclick=\"colorClick('#D2B48C')\" title='Tan' style='background-color:#D2B48C'><span>&nbsp</span></div>";
    $salida .= "<div id='#008080' class='smallColorSquare' onclick=\"colorClick('#008080')\" title='Teal' style='background-color:#008080'><span>&nbsp</span></div>";
    $salida .= "<div id='#D8BFD8' class='smallColorSquare' onclick=\"colorClick('#D8BFD8')\" title='Thistle' style='background-color:#D8BFD8'><span>&nbsp</span></div>";
    $salida .= "<div id='#FF6347' class='smallColorSquare' onclick=\"colorClick('#FF6347')\" title='Tomato' style='background-color:#FF6347'><span>&nbsp</span></div>";
    $salida .= "<div id='#40E0D0' class='smallColorSquare' onclick=\"colorClick('#40E0D0')\" title='Turquoise' style='background-color:#40E0D0'><span>&nbsp</span></div>";
    $salida .= "<div id='#EE82EE' class='smallColorSquare' onclick=\"colorClick('#EE82EE')\" title='Violet' style='background-color:#EE82EE'><span>&nbsp</span></div>";
    $salida .= "<div id='#D02090' class='smallColorSquare' onclick=\"colorClick('#D02090')\" title='VioletRed' style='background-color:#D02090'><span>&nbsp</span></div>";
    $salida .= "<div id='#F5DEB3' class='smallColorSquare' onclick=\"colorClick('#F5DEB3')\" title='Wheat' style='background-color:#F5DEB3'><span>&nbsp</span></div>";
    $salida .= "<div id='#FFFFFF' class='smallColorSquare' onclick=\"colorClick('#FFFFFF')\" title='White' style='background-color:#FFFFFF'><span>&nbsp</span></div>";
    $salida .= "<div id='#F5F5F5' class='smallColorSquare' onclick=\"colorClick('#F5F5F5')\" title='WhiteSmoke' style='background-color:#F5F5F5'><span>&nbsp</span></div>";
    $salida .= "<div id='#FFFF00' class='smallColorSquare' onclick=\"colorClick('#FFFF00')\" title='Yellow' style='background-color:#FFFF00'><span>&nbsp</span></div>";
    $salida .= "<div id='#9ACD32' class='smallColorSquare' onclick=\"colorClick('#9ACD32')\" title='YellowGreen' style='background-color:#9ACD32'><span>&nbsp</span></div>";
    $salida .= "</div>";

    $salida .= "                     </td>\n";
    $salida .= "                   </tr>\n";
    $salida .= "                   </table>\n";
    $salida .= "</div>";
    $salida .= "  <div id='window1' style='display:none;'>";
//     $salida .= "                   <table >\n";
//     $salida .= "                   <tr>\n";
//     $salida .= "                     <td colspan='2'>\n";
    
    $salida .= "                 <table align='center' width='90%' class=\"modulo_table_list\">\n";
    $salida .= "                   <input type=\"hidden\" name=\"color_seleccionado\" id=\"color_seleccionado\"value=\"\">\n";
    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                     <td WIDTH='50%'>\n";
    $salida .= "                     COLOR SELECCIONADO\n";
    $salida .= "                     </td>\n";
    $salida .= "                     <td WIDTH='50%' id='color_sel'>\n";
    $salida .= "                     \n";
    $salida .= "                     </td>\n";
    $salida .= "                   </tr>\n";
    $salida .= "                   <tr >\n";
    $salida .= "                      <td colspan='2' align=\"CENTER\">\n";
    $beneficiario = "javascript:Devolver();\"";
    $salida .="                         <a title='VOLVER A LA PALETA DE COLORES' class='label_error' href=\"".$beneficiario."\">";
    $salida .="                          <label >VOLVER A LA PALETA DE COLORES</label>\n";//usuarios.png
    $salida .="                         </a>\n";
    $salida .= "                     </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                     <td WIDTH='50%'>\n";
    $salida .= "                     COLOR SELECCIONADO\n";
    $salida .= "                     </td>\n";
    $salida .= "                     <td WIDTH='50%' id='color_sel'>\n";
    $salida .= "                     \n";
    $salida .= "                     </td>\n";
    $salida .= "                   </tr>\n";
    $salida .= "                   <tr>\n";
    $salida .= "                     <td colspan='2' align=\"CENTER\">\n";
    $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"Guardar Tipo de Agente\" onclick=\"xajax_GuardarTipoAgente(document.getElementById('tipo_riesgo').value,document.getElementById('color_seleccionado').value)\">\n";
    $salida .= "                     </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                 </table>\n";

    $salida .= "  </div>";
//     $salida .= "                   </tr>\n";
//     $salida .= "                 </table>\n";
        //document.getElementById('numeroF0F8FF').style.display ='none';
        
        $objResponse->assign("ContenidoGrup","innerHTML",$salida);
        $clase_sql = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
        $tipos_de_riesgo = $clase_sql->ObtenerTiposDeRiesgos();
        if(!empty($tipos_de_riesgo))
        {
            for($i=0;$i<count($tipos_de_riesgo);$i++)
            {
                $objResponse->assign($tipos_de_riesgo[$i]['color'],"style.display","none");
            }

        }

        
        return $objResponse;



    }

    /**
    *   funcion que sirve para la asignacion de medicos a los diferentes grupos familiares
    *   @param string $descripcion
    *   @param string $color
    *   @return string con el mensaje de transaccion exitosa o fallida
    **/
    
    function GuardarTipoAgente($descripcion,$color)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        if($descripcion!='' && $color!='')
        {
        
            $cot = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
            $usuario=UserGetUID();
            $resultado=$cot->InsertarTiposDeRiesgos($descripcion,$color,$usuario);
    
            if($resultado===true)
            {
                $cad="TIPO DE AGENTE REGISTRADO SATISFACTORIAMENTE";
                $objResponse->assign("error","innerHTML",$cad);
                $objResponse->call("VentanaClose");
                $objResponse->call("PintarTiposAgentes");
            }
            else
            {
                $cad=$cot->Error['MensajeError'];
                $objResponse->assign("errorGrup","innerHTML",$cad.$resultado);
            }

        }
        else
        {
            if($descripcion=='')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA EL NOMBRE DEL TIPO DE RIESGO");
            }
            elseif($color=='')
            {
                $objResponse->assign("errorGrup","innerHTML","FALTA SELECCIONAR EL COLOR DEL TIPO DE RIESGO");
            }
    
        }
        return $objResponse;

    }
    

    /**
    *   funcion que sirve para listar los grupos familiares de un respectivo medico
    *   @param string $medico_tipo_id
    *   @param string $medico_id
    *   @return array $salida vector con todos datos de los grupos familiares correspondientes al medico 
    **/

    function ListarGruposFamiliares($medico_tipo_id,$medico_id,$nombre)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        $cot = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $grupos_familiares = $cot->ObternerListadeCotizantesPorMedico($medico_tipo_id,$medico_id);
        
        if(!empty($grupos_familiares))
        {
            
            $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       AFILIACION";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       IDENTIFICACION";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='70%' align=\"center\">\n";
            $salida .= "                       NOMBRE ";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       <a title='VER GRUPO FAMILIAR'>";
            $salida .= "                       VER GRUPO";
            $salida .= "                       </a>";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            for($i=0;$i<count($grupos_familiares);$i++)
            {
                $salida .= "                   <tr class=\"modulo_list_claro\" >\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                        ".$grupos_familiares[$i]['eps_afiliacion_id']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                        ".$grupos_familiares[$i]['tipo_id_cotizante']." - ".$grupos_familiares[$i]['cotizante_id'];
                $salida .= "                     </td>\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                       ".$grupos_familiares[$i]['nombre_afiliado']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td width='3%' align=\"center\" id='BotonCotizante'>\n";
                $beneficiario = "javascript:MostrarCapa('ContenedorGrup');Bus_Ben1('".$grupos_familiares[$i]['eps_afiliacion_id']."','".$grupos_familiares[$i]['tipo_id_cotizante']."','".$grupos_familiares[$i]['cotizante_id']."','".$medico_tipo_id."','".$medico_id."','".$nombre."');Iniciar2('GRUPO FAMILIAR');\"";
                $salida .= "                       <a title='VER GRUPO FAMILIAR' href=\"".$beneficiario."\">";
                $salida .= "                          <sub><img src=\"".$path."/images/familia1.jpeg\" border=\"0\" width=\"20\" height=\"20\"></sub>\n";
                $salida .= "                       </a>";
                $salida .= "                     </td>\n";
                $salida .= "                   </tr>\n";

            }
            $salida .= "                   </table>\n";
        }
        else
        {
            $salida .= "                 <table  width=\"100%\" align=\"center\" cellspacing='0' >\n";
            $salida .= "                   <tr>\n";
            $salida .= "                     <td  align=\"center\">\n";
            $salida .= "                       <label class='label_error'>ESTE MEDICO NO TIENE GRUPOS FAMILIARES ASIGNADOS</label>";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </table>\n";
        }

        $objResponse->assign("ContenidoGrup","innerHTML",utf8_encode($salida));
        return $objResponse;

    }


    /**
    *   funcion que sirve para la asignacion de medicos a los diferentes grupos familiares
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los beneficiarios encontrados en la busqueda
    **/

    function ObtenerMedicos($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id,$td)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        $cot = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $medicos = $cot->ObtenerMedicos();
        //var_dump($medicos);
        if(!empty($medicos))
        {
            $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       IDENTIFICACION";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='70%' align=\"center\">\n";
            $salida .= "                       NOMBRE ";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       ASIGNAR MEDICO";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            for($i=0;$i<count($medicos);$i++)
            {
                $salida .= "                   <tr class=\"modulo_list_claro\" >\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                        ".$medicos[$i]['tipo_id_tercero']." - ".$medicos[$i]['tercero_id'];
                $salida .= "                     </td>\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                       ".$medicos[$i]['nombre']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td width='3%' align=\"center\" id='BotonCotizante'>\n";
                $salida .= "                       <a title='ASIGNAR MEDICO' href=\"javascript:SeleccionarMed('".$eps_afiliacion_id."','".$afiliado_tipo_id."','".$afiliado_id."','".$td."','".$medicos[$i]['tipo_id_tercero']."','".$medicos[$i]['tercero_id']."','".$medicos[$i]['usuario_profesional']."','".$medicos[$i]['nombre']."');\">";
                $salida .= "                          <sub><img src=\"".$path."/images/pparacarin.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                       </a>";
                $salida .= "                     </td>\n";
                $salida .= "                   </tr>\n";

            }
            $salida .= "                   </table>\n";
        }
        else
        {
            $salida .= "                 <table  width=\"100%\" align=\"center\" cellspacing='0' >\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
            $salida .= "                     <td  align=\"center\">\n";
            $salida .= "                       <label class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </table>\n";
        }

        $objResponse->assign("ContenidoMed","innerHTML",utf8_encode($salida));
        return $objResponse;

    }


    /**
    *   Funcion que sirve para obtener los beneficiarios de un cotizante
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los beneficiarios encontrados en la busqueda
    **/
    function  BuscarBeneficiarios1($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id,$medico_tipo_id,$medico_id,$medicos_nombre)
    {

        $datos['afiliado_tipo_id']=$afiliado_tipo_id;
        $datos['afiliado_id']=$afiliado_id;
    
       
        $usuario=UserGetUID();
            
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        
        $cot = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $afi = AutoCarga::factory("ConsultarBeneficiarios", "", "app","UV_MedicinaFamiliar");
        $cotizante = $cot->GetDatosAfiliado($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id);
        $afiliados = $afi->ObtenerBeneficiariosCotizante($datos);

         if(!empty($cotizante))
         {

                    $salida .= "               <div align='center' id=\"Cotizante\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick='' >";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\" cellspacing='0' >\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                    $salida .= "                     <td width='97%' align=\"center\">\n";
                    $salida .= "                       <a title='NOMBRE AFILIADO'>";
                    $salida .= "                        ".$cotizante['nombre_afiliado']."  -  (COTIZANTE)";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='3%' align=\"center\" id='BotonCotizante'>\n";
                    $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Cotizante','0','".$path."','BotonCotizante');\">";
                    $salida .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"10%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\"align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['afiliado_tipo_id']."-".$cotizante['afiliado_id']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        SEXO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['descripcion_eps_tipo_sexo_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTAMENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante["DATOS_COTIZANTE"]['descripcion_estamento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       ".$cotizante['fecha_afiliacion']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA NACIMIENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['fecha_nacimiento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTADO CIVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante["DATOS_COTIZANTE"]['descripcion_estado_civil']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTRATO SOCIOECONOMICO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante["DATOS_COTIZANTE"]['descripcion_estrato_socioeconomico']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ZONA RESIDENCIAL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['descripcion_zona_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        DIRECCION RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['direccion_residencia']." (".$cotizante['municipio']."-".$cotizante['departamento'].")";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['telefono_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO MOVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['telefono_movil']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                 </table>\n";
                    $salida .= "               </div>\n";
         }
              $i=0;  
        if(!empty($afiliados))
        {           

        //var_dump($afiliados);

            foreach($afiliados as $key=>$valor)
            {   
                foreach($valor as $key=>$valor1)
                {
                    $td="BotonBenef".$i;
                    $salida .= "               <div align='center' id=\"Benef".$i."\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick=''>";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\" cellspacing='0' >\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                    $salida .= "                     <td width='97%' align=\"center\">\n";
                    $salida .= "                       <a title='NOMBRE AFILIADO'>";
                    $salida .= "                        ".$valor1['primer_nombre']." ".$valor1['segundo_nombre']." ".$valor1['primer_apellido']." ".$valor1['segundo_apellido']." -  (BENEFICIARIO)";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='3%' align=\"center\" id='".$td."'>\n";
                    $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Benef".$i."','0','".$path."','".$td."');\">";
                    $salida .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"10%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\"align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['afiliado_tipo_id']."-".$valor1['afiliado_id']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        SEXO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    if($valor1['tipo_sexo_id']=='M')
                    {
                        $salida .= "              MASCULINO       ";
                    }
                    else
                    {
                        $salida .= "              FEMENINO";
                    }
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       ".$valor1['fecha_afiliacion_sgss']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        PARENTESCO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"left\">\n";
                    $salida .= "                       ".$valor1['descripcion_parentesco']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA NACIMIENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['fecha_nacimiento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ZONA RESIDENCIAL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    IF($valor1['zona_residencia']=='U')
                    {
                        $salida .= "                       URBANA";
                    }
                    else
                    {
                        $salida .= "                       RURAL";
                    }
                    
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        DIRECCION RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='DIRECCION RESIDENCIA'>";
                    $salida .= "                         ".$valor1['direccion_residencia']."-( ".$valor1['departamento_municipio'].")";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['telefono_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO MOVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['telefono_movil']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                 </table>\n";
                    $salida .= "                   </div>\n";
                    $i++;
                }
            }
        }
        $salida .= "                 <table WIDTH='100%'>\n";
        $salida .= "                   <tr>\n";
        $salida .= "                      <td align=\"CENTER\">\n";
        $beneficiario = "javascript:MostrarCapa('ContenedorGrup');ListarGrupos('".$medico_tipo_id."','".$medico_id."','".$medicos_nombre."');Iniciar2('GRUPOS FAMILIARES DEL MEDICO ".$medicos_nombre."');\"";
        $salida .="                         <a title='VOLVER A GRUPOS FAMILIARES' class='label_error' href=\"".$beneficiario."\">";
        $salida .="                          <label >VOLVER A GRUPOS FAMILIARES</label>\n";//usuarios.png
        $salida .="                         </a>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                 </table>\n";

        
        $objResponse->assign("ContenidoGrup","innerHTML",utf8_encode($salida));
        return $objResponse;
    }

    
    /**
    *   Funcion que sirve para obtener los beneficiarios de un cotizante
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los beneficiarios encontrados en la busqueda
    **/
    function  BuscarBeneficiarios($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id)
    {

        $datos['afiliado_tipo_id']=$afiliado_tipo_id;
        $datos['afiliado_id']=$afiliado_id;
    
       
        $usuario=UserGetUID();
            
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        
        $cot = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $afi = AutoCarga::factory("ConsultarBeneficiarios", "", "app","UV_MedicinaFamiliar");
        $cotizante = $cot->GetDatosAfiliado($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id);
        $afiliados = $afi->ObtenerBeneficiariosCotizante($datos);

         if(!empty($cotizante))
         {

                    $salida .= "               <div align='center' id=\"Cotizante\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick='' >";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\" cellspacing='0' >\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                    $salida .= "                     <td width='97%' align=\"center\">\n";
                    $salida .= "                       <a title='NOMBRE AFILIADO'>";
                    $salida .= "                        ".$cotizante['nombre_afiliado']."  -  (COTIZANTE)";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='3%' align=\"center\" id='BotonCotizante'>\n";
                    $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Cotizante','0','".$path."','BotonCotizante');\">";
                    $salida .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"10%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\"align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['afiliado_tipo_id']."-".$cotizante['afiliado_id']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        SEXO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['descripcion_eps_tipo_sexo_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTAMENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante["DATOS_COTIZANTE"]['descripcion_estamento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       ".$cotizante['fecha_afiliacion']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA NACIMIENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['fecha_nacimiento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTADO CIVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante["DATOS_COTIZANTE"]['descripcion_estado_civil']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTRATO SOCIOECONOMICO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante["DATOS_COTIZANTE"]['descripcion_estrato_socioeconomico']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ZONA RESIDENCIAL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['descripcion_zona_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        DIRECCION RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['direccion_residencia']." (".$cotizante['municipio']."-".$cotizante['departamento'].")";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['telefono_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO MOVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['telefono_movil']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                 </table>\n";
                    $salida .= "               </div>\n";
         }
              $i=0;  
        if(!empty($afiliados))
        {           

        //var_dump($afiliados);

            foreach($afiliados as $key=>$valor)
            {   
                foreach($valor as $key=>$valor1)
                {
                    $td="BotonBenef".$i;
                    $salida .= "               <div align='center' id=\"Benef".$i."\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick=''>";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\" cellspacing='0' >\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                    $salida .= "                     <td width='97%' align=\"center\">\n";
                    $salida .= "                       <a title='NOMBRE AFILIADO'>";
                    $salida .= "                        ".$valor1['primer_nombre']." ".$valor1['segundo_nombre']." ".$valor1['primer_apellido']." ".$valor1['segundo_apellido']." -  (BENEFICIARIO)";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='3%' align=\"center\" id='".$td."'>\n";
                    $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Benef".$i."','0','".$path."','".$td."');\">";
                    $salida .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"10%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\"align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['afiliado_tipo_id']."-".$valor1['afiliado_id']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        SEXO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    if($valor1['tipo_sexo_id']=='M')
                    {
                        $salida .= "              MASCULINO       ";
                    }
                    else
                    {
                        $salida .= "              FEMENINO";
                    }
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       ".$valor1['fecha_afiliacion_sgss']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        PARENTESCO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"left\">\n";
                    $salida .= "                       ".$valor1['descripcion_parentesco']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA NACIMIENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['fecha_nacimiento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ZONA RESIDENCIAL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    IF($valor1['zona_residencia']=='U')
                    {
                        $salida .= "                       URBANA";
                    }
                    else
                    {
                        $salida .= "                       RURAL";
                    }
                    
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        DIRECCION RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='DIRECCION RESIDENCIA'>";
                    $salida .= "                         ".$valor1['direccion_residencia']."-( ".$valor1['departamento_municipio'].")";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['telefono_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO MOVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['telefono_movil']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                 </table>\n";
                    $salida .= "                   </div>\n";
                    $i++;
                }
            }
        }
        $objResponse->assign("ContenidoGrup","innerHTML",utf8_encode($salida));
        return $objResponse;
    }

  

    /**
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los afiliados encontrados en la busqueda
    **/
    function  BuscarDatos($datos,$pagina,$contador)
    {
        $vector_permiso=SessionGetVar("permisosAfiliaciones");
        $usuario=UserGetUID();
            
        if($datos==1)
        {
            $datos = SessionGetVar("BUSQUEDA");
        }
        //var_dumP($datos);
        //var_dump($pagina);
      //var_dump($contador);

//         foreach($datos as $key=>$valor)
//         {
//             if(!empty($valor))
//             {
//                 echo "aa".$key."--".$valor;
//             }
//         }
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");

        if(!empty($datos['fecha1']))
        {
            $partes=explode("-", $datos['fecha1']);
            $datos['fecha1']=$partes[2]."-".$partes[1]."-".$partes[0];
        }

        if(!empty($datos['fecha2']))
        {
            $partes=explode("-", $datos['fecha2']);
            $datos['fecha2']=$partes[2]."-".$partes[1]."-".$partes[0];
        }

        //var_dump($datos);
        if($contador==0)
        {
            $contador = $afi->GetAfiliados($datos, $count=true, $limit=false, $offset=0);
            SessionDelVar("CONTADOR");
            SessionSetVar("CONTADOR",$contador);
        }
             
        $limit=20;
        $offset=($pagina-1)*$limit;
        $afiliados = $afi->GetAfiliados($datos, $count=false, $limit, $offset);
        // var_dump($afiliados);
       //$objResponse->alert();

        if(!empty($afiliados))
        {
            SessionDelVar("BUSQUEDA");
            SessionDelVar("PAGINA");
            SessionSetVar("BUSQUEDA",$datos);
            SessionSetVar("PAGINA",$pagina);
            $salida .= "                 <table width=\"100%\" align=\"center\">\n";
            $salida .= "                    <tr class=\"normal_10AN\">\n";
            $salida .= "                       <td width=\"100%\" align=\"left\">\n";
            $salida .= "                       SE ENCONTRARON (".$contador.") REGISTRO(S)";
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </tABLE>\n";
            $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td width=\"5%\" align=\"center\">\n";
            $salida .= "                       <a title='EPS TIPO AFILIADO'>";
            $salida .= "                        T";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                       <a title='ESTADO DEL AFILIADO'>";
            $salida .= "                        ESTADO";
            $salida .= "                       </a>";
//             $salida .= "                       </td>\n";
//             $salida .= "                       <td width=\"5%\" align=\"center\">\n";
//             $salida .= "                       <a title='SUBESTADO DEL AFILIADO'>";
//             $salida .= "                        SUBESTADO";
//             $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                       <a title='IDENTIFICACION DEL AFILIADO'>";
            $salida .= "                         IDENTIFICACION";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"25%\" align=\"center\">\n";
            $salida .= "                       <a title='NOMBRE DEL AFILIADO'>";
            $salida .= "                          NOMBRE";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"15%\" align=\"center\">\n";
            $salida .= "                       <a title='ESTAMENTO'>";
            $salida .= "                        ESTAMENTO";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
//             $salida .= "                       <td width=\"12%\" align=\"center\">\n";
//             $salida .= "                       <a title='DEPENDENCIA'>";
//             $salida .= "                        DEPENDENCIA";
//             $salida .= "                       </a>";
//             $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                       <a title='FECHA DE AFILIACION'>";
            $salida .= "                          FECHA";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"18%\" align=\"center\">\n";
             $salida .= "                       <a title='MEDICO FAMILIAR'>";
             $salida .= "                        MEDICO FAMILIAR";
             $salida .= "                       </a>";
             $salida .= "                       </td>\n";

            $salida .= "                       <td colspan='2' width=\"4%\" align=\"center\">\n";
            $salida .= "                          ACCIONES";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            for($i=0;$i<count($afiliados);$i++)
            {   
                $td="medico".$i;
                $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $salida .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_eps_tipo_afiliado']."'>";
                $salida .= "                       ".$afiliados[$i]['eps_tipo_afiliado_id'];
                $salida .= "                       </a>\n";
                $salida .= "                       </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_estado']."'>";
                $salida .= "                       ".$afiliados[$i]['estado_afiliado_id'];
                $salida .= "                      </a>\n";
                $salida .= "                      - ";
//                 $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_subestado']."'>";
                $salida .= "                       ".$afiliados[$i]['subestado_afiliado_id'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       ".$afiliados[$i]['afiliado_tipo_id']."-".$afiliados[$i]['afiliado_id'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
                $salida .= "                       ".$afiliados[$i]['nombre_afiliado'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['estamento_id']."'>";
                $salida .= "                       ".$afiliados[$i]['descripcion_estamento'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                       ".$afiliados[$i]['fecha_afiliacion'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td id='".$td."' align=\"left\">\n";
                if(!empty($afiliados[$i]['tipo_id_medico']))
                {
                    $salida.= "                        <a title='".$afiliados[$i]['tipo_id_medico']." - ".$afiliados[$i]['medico_id']."' href=\"#\">";
                    $salida.= "                           <sub>".$afiliados[$i]['nombre_profesional']."</sub>\n";
                    $salida.= "                         </a>";  
                }
                else
                {       
                        $salida.= "                         &nbsp;";      
                }
                

                $salida .= "                      </td>\n";
//                 $salida .= "                      <td  align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
//                 $nuevousu = ModuloGetURL('app','UV_MedicinaFamiliar','controller','Info_AfiliadosCotizante',array('eps_afiliacion_id'=>$afiliados[$i]['eps_afiliacion_id'],'afiliado_tipo_id'=>$afiliados[$i]['afiliado_tipo_id'],'afiliado_id'=>$afiliados[$i]['afiliado_id'],'cuantos'=>$contador));//"javascript:MostarDatosDocumento('".$empresa_id."','".$valor['prefijo']."','".$valor['numero']."');MostrarCapa('ContenedorDet');IniciarDoc('DATOS DEL DOCUMENTO');";//
//                 $salida .= "                         <a title='INFORMACION COMPLETA DEL USUARIO' href=\"".$nuevousu."\">";
//                 $salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"21\" height=\"21\"></sub>\n";
//                 $salida .= "                         </a>\n";
//                 $salida .= "                       </td>\n";
                $salida .= "                      <td  align=\"center\">\n";
                if($afiliados[$i]['eps_tipo_afiliado_id']=='C')
                {
                    $beneficiario = "javascript:MostrarCapa('ContenedorGrup');Bus_Ben('".$afiliados[$i]['eps_afiliacion_id']."','".$afiliados[$i]['afiliado_tipo_id']."','".$afiliados[$i]['afiliado_id']."');Iniciar2('GRUPO FAMILIAR');\"";
                    $salida .="                         <a title='CONSULTAR BENEFICIARIO (GRUPO FAMILIAR)' href=\"".$beneficiario."\">";
                    $salida .="                          <sub><img src=\"".$path."/images/familia1.jpeg\" border=\"0\" width=\"21\" height=\"21\"></sub>\n";//usuarios.png
                    $salida .="                         </a>\n";
                }
                else
                {
                    $salida .= "                        &nbsp;";

                }
                 $salida .= "                       </td>\n";
                 $salida .= "                      <td  align=\"center\">\n";

                $medicos = "javascript:MostrarCapa('ContenedorMed');ExtraerMedicos('".$afiliados[$i]['eps_afiliacion_id']."','".$afiliados[$i]['afiliado_tipo_id']."','".$afiliados[$i]['afiliado_id']."','".$td."');Iniciar3('MEDICOS FAMILIARES');\"";
                $salida .="                         <a title='CONSULTAR MEIDCOS FAMILIARES' href=\"".$medicos."\">";
                $salida .="                          <sub><img src=\"".$path."/images/medico3.jpeg\" border=\"0\" width=\"21\" height=\"21\"></sub>\n";//usuarios.png
                $salida .="                         </a>\n";
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
            } 
            $salida .= "                    </table>\n";
            $salida .= "                    <br>\n";
            $op="1";
            $slc=$documentos;        
            $salida .= "".ObtenerPaginadorAFI($pagina,$path,$contador,$op,$datos);
            $objResponse->call("Mostrar1");
            
        }
        else
        {
            
            $salida .= "                 <table width=\"100%\" align=\"center\">\n";
            $salida .= "                    <tr class=\"label_error\">\n";
            $salida .= "                       <td width=\"100%\" align=\"center\">\n";
            $salida .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </table>\n";
            $objResponse->call("Mostrar2");
        }
        $objResponse->assign("tabla_afiliados","innerHTML",utf8_encode($salida));
        return $objResponse;
    }


    /**
    * Funcion que sirve para la paginacion de registros generados por el buscador
    * @param string $pagina
    * @param string $path direccion de los temas visuales(imagenes) de la aplicacion
    * @param string $slc cantidad total de registros  
    * @param string $op opcion  para mostrar el paginador (arriba =0 , abajo =1)
    * @param array $datos vector que contiene los datos a buscar
    * @return string $Tabla con la forma del paginador
    *
    **/
    function ObtenerPaginadorAFI($pagina,$path,$slc,$op,$datos)
    {

      
     // var_dump($slc);
      $TotalRegistros = $slc;
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 20;
      }
      else
      {
        $LimitRow = 20;
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
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'1','".$slc."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".($pagina-1)."','".$slc."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".$i."','".$slc."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".($pagina+1)."','".$slc."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".($NumeroPaginas)."','".$slc."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     Página&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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

      return utf8_decode($Tabla);
    }

  

    
    /**
    *   Funcion que sirve para obtener los subestados a paritr d un estado
    *   @param string $estado
    *   @return array $subestados vector con todos los subestados del afiliado
    **/
    function ObtenerSubestados($estado)
    {

        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
        $subestados = $afi->ObtenerTiposSubestadosAfiliados($estado);        
        //$objResponse->assign("JJJJJ");
        //var_dump($subestados);
         if(!empty($subestados))
         {
            $html .= "    <option value=\"0\">---Seleccionar---</option>\n";
            
            foreach($subestados as $key => $datos)
            {   
                $html .= "                  <option value=\"".$datos['subestado_afiliado_id']."\" >".$datos['descripcion_subestado']."</option>\n";
            }
            $html .= "              </select>\n";
             $objResponse->assign("subestado_afiliado_id","innerHTML",$html);
         }
   
        return $objResponse;
    }




    /**
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $pagina
    *   @param string $path
    *   @param string $slc
    *   @param string $op
    *   @param string $empresa_id
    *   @param string $centro_utilidad
    *   @param string $bodega
    *   @param string $usuario_id
    *   @param string $clas_documento
    *   @param string $tipos_documento
    *   @return array $salida vector con todos datos de los afiliados encontrados en la busqueda
    **/
    function ObtenerPaginador($pagina,$path,$slc,$op,$empresa_id,$centro_utilidad,$bodega,$usuario_id,$clas_documento,$tipos_documento)
    {

      
      //echo "io";
      $TotalRegistros = $slc['contador'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 20;
      }
      else
      {
        $LimitRow = 20;
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
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('1','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('".($pagina-1)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:MostrarDocusFinal('".$i."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('".($pagina+1)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:MostrarDocusFinal('".($NumeroPaginas)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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

      return $Tabla;
    }

    /**
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $direccion
    *   @param string $alt
    *   @param string $imagen
    *   @param string $empresa_id
    *   @param string $prefijo
    *   @param string $numero
    *   @return array $salida1 
    **/

    function RetornarImpresionDoc($direccion,$alt,$imagen,$empresa_id,$prefijo,$numero)
    {    
        global $VISTA;
        $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida1 ="<a title='".$alt."' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>".$imagen1."</a>";
        return $salida1;
    }
    

    /**
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $tr
    *   @param string $tmp
    *   @param string $bodega_doc_id
    *   @return array $salida1
    **/
    function BorrarTmpAfirmativo($tr,$tmp,$bodega_doc_id)
    {
        $consulta=new MovBodegasSQL();
        $objResponse = new xajaxResponse();
        $buscar=$consulta->EliminarDocTemporal($bodega_doc_id,$tmp,UserGetUID());
        if($buscar==1)
        {
            $objResponse->alert("EL DOCUMENTO TEMPORAL $tmp FUE ELIMINADO EXITOSAMENTE");
            $objResponse->remove($tr);
        }
        else
        { $objResponse->alert("NO SE PUEDE BORRAR");
        } 
        
        return $objResponse;
    }
?>