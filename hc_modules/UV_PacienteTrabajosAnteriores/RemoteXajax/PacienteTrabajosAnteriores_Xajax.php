<?php
  /**
  * Archivo Ajax (PacientesTrabajosAnteriores)
  * Archivo que contiene funciones las cuales permiten conectarse con la BD por medio de xajax lo que permite no recargar la pagina para obtener una consulta
  *
  * @version $Id: PacienteTrabajosAnteriores_Xajax.php,v 1.1 2009/06/09 19:14:07 hugo Exp $
  * @package IPSOFT-SIIS
  * @author Jaime Gomez  
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  
  */
	
    IncludeClass("LogicaPTA",NULL,"hc","UV_PacienteTrabajosAnteriores");

    
    /**
    * Funcion que se utiliza al cargar la aplicacion para listar las enfermedades que hayan sido registradas al usuario
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/
    
    function MostrarEnfermedades($tipo_id_paciente,$paciente_id)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaPTA();
        $resultado1=$registrar->ObtenerDatosEnfermedades($tipo_id_paciente,$paciente_id);

        if(!empty($resultado1))
        {
            $salida = "                   <table class=\"modulo_table_list\" width='100%' align='center'>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td colspan='4' align=\"center\">\n";
            $salida .= "                       HISTORIAL DE ENFERMEDADES Y ACCIDENTES LABORALES";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td width='16%' align=\"center\">\n";
            $salida .= "                       FECHA";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='42%' align=\"center\">\n";
            $salida .= "                       ENFERMEDAD";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='42%' align=\"center\">\n";
            $salida .= "                       ACCIDENTE LABORAL";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            for($i=0;$i<count($resultado1);$i++)
            {
                $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                $salida .= "                     <td class=\"normal_10AN\" align=\"center\" ROWSPAN='".count($valor)."'>\n";
                $salida .= "                       ".$resultado1[$i]['fecha_registro']."";
                $salida .= "                     </td>\n";
                if($resultado1[$i]['enfermedad_profesional']=='1')
                {
                    $salida .= "                     <td class=\"normal_10AN\" align=\"left\" ROWSPAN='".count($valor)."'>\n";
                    $salida .= "                       ".$resultado1[$i]['descripcion_enfermedad']."";
                    $salida .= "                     </td>\n";
                }
                else
                {
                    $salida .= "                     <td class=\"normal_10AN\" align=\"left\" ROWSPAN='".count($valor)."'>\n";
                    $salida .= "                       NINGUNA";
                    $salida .= "                     </td>\n";
                }
            
                if($resultado1[$i]['accidente_laboral']=='1')
                {
                    $salida .= "                     <td class=\"normal_10AN\" align=\"left\" ROWSPAN='".count($valor)."'>\n";
                    $salida .= "                       ".$resultado1[$i]['descripcion_accidente']."";
                    $salida .= "                     </td>\n";
                }
                else
                {
                    $salida .= "                     <td class=\"normal_10AN\" align=\"left\" ROWSPAN='".count($valor)."'>\n";
                    $salida .= "                       NINGUNA";
                    $salida .= "                     </td>\n";
                }
            
            
                $salida .= "                   </tr>\n";
            }
            $salida .= "                   </table>";

        
        }
        else
        {
            $salida="<LABEL CLASS='label_error'>NO HAY REGISTROS DE ESTE PACIENTE </label>";
        }
       $objResponse->assign("lista_enfermedades1","innerHTML",$salida);
       return $objResponse;



    }

    /**
    * Funcion que se utiliza al cargar la aplicacion para listar los trabajos que hayan sido registradas al usuario
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/
    function MostrarTrabajosAnteriores($tipo_id_paciente,$paciente_id) 
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaPTA();
        $resultado=$registrar->ConsultarTrabajosAnteriores($tipo_id_paciente,$paciente_id);
        
        if(!empty($resultado))
        {
            $salida = "                   <table class=\"modulo_table_list\" width='100%' align='center'>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td colspan='6' align=\"center\">\n";
            $salida .= "                       HISTORIAL DE TRABAJOS ANTERIORES DEL PACIENTE";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   </table>";
            $salida .= "                   <br>\n";
             
            foreach($resultado[0] as $key=>$resultado1)
            {   
           
                    $salida .= "                   <table class=\"modulo_table_list\" width='100%' align='center'>\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                    $salida .= "                     <td width='10%' align=\"center\">\n";
                    $salida .= "                       EMPLEADOR";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='30%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $salida .= "                       ".$resultado1['empleador']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='15%' align=\"center\">\n";
                    $salida .= "                       LUGAR";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td colspan='3' class=\"modulo_list_claro\" align=\"left\">\n";
                    $salida .= "                       ".$resultado1['municipio']."-".$resultado1['departamento']."-".$resultado1['pais']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       CARGO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
                    $salida .= "                       ".$resultado1['cargo']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       FECHA INGRESO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
                    $salida .= "                       ".$resultado1['fecha_ini']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       FECHA RETIRO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
                    $salida .= "                       ".$resultado1['fecha_fin']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                      INTESIDAD";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
                    if($resultado1['intensidad']=='A')
                    {
                        $salida .= "                       ALTA";
                    }
                    elseif($resultado1['intensidad']=='M')
                    {
                        $salida .= "                       MEDIA";
                    }
                    elseif($resultado1['intensidad']=='B')
                    {
                        $salida .= "                       BAJA";
                    }
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       HORAS POR DIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
                    $salida .= "                       ".$resultado1['horas_dia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       DIAS A LA SEMANA ";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
                    $salida .= "                       ".$resultado1['dias_por_semana']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr >\n";
                    $salida .= "                      <td colspan='2' class=\"modulo_list_claro\" align=\"left\" >\n";
                    $salida .= "  <label class='label_error'>NOTA:</label> <BR>";
                    if($resultado1['empresa_elemetos_protectores']=="S")
                    {
                      $salida .= "  <label class='normal_10AN'>* LA EMPRESA SI APORTO ELEMENTOS PROTECTORES PERSONALES </label>";
                    }
                    elseif($resultado1['empresa_elemetos_protectores']=="N")
                    {
                      $salida .= " <label class='normal_10AN'> * LA EMPRESA NO APORTO ELEMENTOS PROTECTORES PERSONALES </label>";
                    }

                    $salida .= "                     <br>\n";
                    
                    if($resultado1['uso_elemetos_protectores']=="S")
                    {
                      $salida .= "<label class='normal_10AN'> * EL PACIENTE SI UTILIZO ELEMENTOS PROTECTORES PERSONALES </label>";
                    }
                    elseif($resultado1['uso_elemetos_protectores']=="N")
                    {
                      $salida .= "<label class='normal_10AN'> * EL PACIENTE NO UTILIZO ELEMENTOS PROTECTORES PERSONALES </label>";
                    }

                    $salida .= "                     </td>\n";
                    $salida .= "                     <td colspan='4'  align=\"left\" class='normal_10AN'>\n";
                    $salida .= "                     <table class=\"modulo_table_list\" width='100%' align='center'>\n";


                    if(!empty($resultado[1][$resultado1['trabajo_id']]))
                    {
                        
                        $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                        $salida .= "                     <td colspan='6' align=\"center\">\n";
                        $salida .= "                      RIESGOS ASOCIADOS A ESE TRABAJO";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                        $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                        $salida .= "                     <td  align=\"center\">\n";
                        $salida .= "                      TIPO DE RIESGO";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td  align=\"center\">\n";
                        $salida .= "                      COLOR";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td  align=\"center\">\n";
                        $salida .= "                      AGENTE DE RIESGO";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                       
                        foreach($resultado[1][$resultado1['trabajo_id']] as $key1 => $valor_riesgos)
                        {
                            $xan=500;

                            
                            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                            $salida .= "                     <td rowspan='".count($valor_riesgos)."' class=\"modulo_list_claro\" align=\"center\">\n";
                            $salida .= "                       ".$key1."";
                            $salida .= "                     </td>\n";

                            foreach($valor_riesgos as $key2 => $agentes_riesgos )
                            {
                                if($xan != 500)
                                {
                                    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                                }
                                $salida .= "                     <td bgcolor='".$agentes_riesgos['color']."' align=\"center\">\n";
                                $salida .= "                       &nbsp;";
                                $salida .= "                     </td>\n";
                                $salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
                                $salida .= "                       ".$agentes_riesgos['agente_de_riesgo_nom']."";
                                $salida .= "                     </td>\n";
                                $salida .= "                   </tr>\n";
                                $xan=600;
                            }    
                        }
                    }
                    else
                    {
                        $salida .= "                   <tr style=\"height:100;\" class=\"modulo_list_claro\">\n";
                        $salida .= "                     <td colspan='6' align=\"center\">\n";
                        $salida .= "                      <label class='label_error'>NO SE ASIGNARON AGENTES DE RIESGO A ESTE TRABAJO</label>";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                    }
                    $salida .= "                   </table>";
                    $salida .= "                 </td>\n";
                    $salida .= "               </tr>\n";
                    $salida .= "             </table>";
              }
            
        }
        else
        {
            $salida="<LABEL CLASS='label_error'>NO HAY REGISTROS DE ESTE PACIENTE </label>";
        }
        $objResponse->assign("cuadro_trabajos1","innerHTML",$salida);
        return $objResponse;

    }

    
    /**
    * Funcion que se utiliza al cargar la aplicacion para listar el historial de las EPS, ARP Y FONDO DE PENSIONES que ha tenido el usuario
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/

    function MostrarEPS_Anteriores_x($tipo_id_paciente,$paciente_id)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaPTA();
        $resultado1=$registrar->ConsultarEPS_Anterior($tipo_id_paciente,$paciente_id);
        if(!empty($resultado1))
        {
            $salida = "                   <table class=\"modulo_table_list\" width='100%' align='center'>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td colspan='5' align=\"center\">\n";
            $salida .= "                       HISTORIAL DE ENFERMEDADES Y ACCIDENTES LABORALES";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td width='27%' align=\"center\">\n";
            $salida .= "                       NOMBRE ARP";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='27%' align=\"center\">\n";
            $salida .= "                       NOMBRE EPS";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='26%' align=\"center\">\n";
            $salida .= "                      PENSION";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='10%' align=\"center\">\n";
            $salida .= "                      FECHA DE INGRESO";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='10%' align=\"center\">\n";
            $salida .= "                      FECHA DE RETIRO";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            for($i=0;$i<count($resultado1);$i++)
            {
                $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                $salida .= "                     <td class=\"normal_10AN\" align=\"center\">\n";
                $salida .= "                       ".$resultado1[$i]['nombre_arp_anterior']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td class=\"normal_10AN\" align=\"left\">\n";
                $salida .= "                       ".$resultado1[$i]['nombre_eps_anterior']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td class=\"normal_10AN\" align=\"left\">\n";
                $salida .= "                       ".$resultado1[$i]['nombre_pensiones_anterior']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td class=\"normal_10AN\" align=\"left\" >\n";
                $salida .= "                       ".$resultado1[$i]['fecha_ingreso']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td class=\"normal_10AN\" align=\"left\">\n";
                $salida .= "                       ".$resultado1[$i]['fecha_retiro']."";
                $salida .= "                     </td>\n";
                $salida .= "                   </tr>\n";
            }
            $salida .= "                   </table>";
    
    
        }
        else
        {
            $salida="<LABEL CLASS='label_error'>NO HAY REGISTROS DE ESTE PACIENTE </label>";
        }
        $objResponse->assign("eps_anteriores1","innerHTML",$salida);
        return $objResponse;
    }


    /**
    * Funcion que se utiliza para registrar en el historial las EPS, ARP Y FONDO DE PENSIONES que ha tenido el usuario
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/
    function GuardarEPS_Anteror($vector,$tipo_id_paciente,$paciente_id)
    {
        $usuario = UserGetUID();
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaPTA();

        if($vector["eps"]=="")
        {   $salida="EL CAMPO EPS SE ENCUENTRA VACIO";
            $objResponse->assign("eps_anteriores","innerHTML",$salida);
            return $objResponse;
        }
        
        if($vector["arp"]=="")
        {   $salida="EL CAMPO ARP SE ENCUENTRA VACIO";
            $objResponse->assign("eps_anteriores","innerHTML",$salida);
            return $objResponse;
        }

        if($vector["fecha3"]=="")
        {   $salida="LA FECHA DE INICIO SE ENCUENTRA VACIO";
            $objResponse->assign("eps_anteriores","innerHTML",$salida);
            return $objResponse;
        }
        if($vector["fecha4"]=="")
        {   $salida="LA FECHA DE RETIRO SE ENCUENTRA VACIO";
            $objResponse->assign("eps_anteriores","innerHTML",$salida);
            return $objResponse;
        }
            if($vector["pension"]=="")
        {   $salida="EL CAMPO PENSION SE ENCUENTRA VACIO";
            $objResponse->assign("eps_anteriores","innerHTML",$salida);
            return $objResponse;
        }

        $resultado2=$registrar->SacarUltimoRegistroEPS($tipo_id_paciente,$paciente_id);
        
        $f = explode("-", $vector["fecha3"]);
        $date2 = strtotime($f[2]."-".$f[1]."-".$f[0]);
        
        $f = explode("-", $vector["fecha4"]);
        $date3 = strtotime($f[2]."-".$f[1]."-".$f[0]);
        $s = 1;
        if(!empty($resultado2[0]))
        {
          $date1 = strtotime($resultado2[0]['fecha_retiro']);
          $s = ($date2 - $date1);
        }
        
        $x = ($date3 - $date2);
        if($s>0 && $x>0)
        {
            $resultado=$registrar->Guardar_EPS_Anterior($vector,$tipo_id_paciente,$paciente_id,$usuario);
            if($resultado===true)
            {
                $salida="<LABEL CLASS='label_error'>DATOS GUARDADOS SATISFACTORIAMENTE </label>";
                $objResponse->assign("eps_anteriores","innerHTML",$salida);
                $objResponse->Call("ResetearEPS_Anterior");
            }
            elseif($resultado===false)
            {
                $salida="<LABEL CLASS='label_error'>PROBLEMAS CON LA INSERCION ".$registrar->error."  ".$registrar->mensajeDeError."</label>";
                $objResponse->assign("eps_anteriores","innerHTML",$salida);
            
            }

                $resultado1=$registrar->ConsultarEPS_Anterior($tipo_id_paciente,$paciente_id);
                    if(!empty($resultado1))
                    {
                        $salida = "                   <table class=\"modulo_table_list\" width='100%' align='center'>\n";
                        $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                        $salida .= "                     <td colspan='5' align=\"center\">\n";
                        $salida .= "                       HISTORIAL DE ENFERMEDADES Y ACCIDENTES LABORALES";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                        $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                        $salida .= "                     <td width='27%' align=\"center\">\n";
                        $salida .= "                       NOMBRE ARP";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td width='27%' align=\"center\">\n";
                        $salida .= "                       NOMBRE EPS";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td width='26%' align=\"center\">\n";
                        $salida .= "                      PENSION";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td width='10%' align=\"center\">\n";
                        $salida .= "                      FECHA DE INGRESO";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td width='10%' align=\"center\">\n";
                        $salida .= "                      FECHA DE RETIRO";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                        for($i=0;$i<count($resultado1);$i++)
                        {
                            $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                            $salida .= "                     <td class=\"normal_10AN\" align=\"center\">\n";
                            $salida .= "                       ".$resultado1[$i]['nombre_arp_anterior']."";
                            $salida .= "                     </td>\n";
                            $salida .= "                     <td class=\"normal_10AN\" align=\"left\">\n";
                            $salida .= "                       ".$resultado1[$i]['nombre_eps_anterior']."";
                            $salida .= "                     </td>\n";
                            $salida .= "                     <td class=\"normal_10AN\" align=\"left\">\n";
                            $salida .= "                       ".$resultado1[$i]['nombre_pensiones_anterior']."";
                            $salida .= "                     </td>\n";
                            $salida .= "                     <td class=\"normal_10AN\" align=\"left\" >\n";
                            $salida .= "                       ".$resultado1[$i]['fecha_ingreso']."";
                            $salida .= "                     </td>\n";
                            $salida .= "                     <td class=\"normal_10AN\" align=\"left\">\n";
                            $salida .= "                       ".$resultado1[$i]['fecha_retiro']."";
                            $salida .= "                     </td>\n";
                            $salida .= "                   </tr>\n";
                        }
                        $salida .= "                   </table>";


                }
            $objResponse->assign("eps_anteriores1","innerHTML",$salida);
        }
        elseif($x<=0)
        {
            $salida="LA FECHA DE INGRESO ES MAYOR O IGUAL A LA FECHA DE RETIRO";
            $objResponse->assign("eps_anteriores","innerHTML",$salida);
            return $objResponse;
        }
        elseif($s<=0)
        {
            $salida="LA FECHA DE INGRESO ES MENOR A LA FECHA DE RETIRO DE LA ANTERIOR EPS";
            $objResponse->assign("eps_anteriores","innerHTML",$salida);
            return $objResponse;

        }
        
        return $objResponse;

    }


    /**
    * Funcion que se utiliza para registrar los trabajos anteriores que ha tenido el usuario
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @return string $salida
    **/
    function GuardarRiesgos_Paciente($vector,$tipo_id,$id_paciente)
    {
        $usuario = UserGetUID();
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaPTA();

            if($vector["empleador"]=="")
            {   $salida="EL CAMPO EMPLEADOR SE ENCUENTRA VACIO";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }

            if($vector["departamentos"]=="-1")
            {   $salida="NO HA SELECCIONADO EL DEPARTAMENTO";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }
            if($vector["ciudades"]=="-1")
            {   $salida="NO HA SELECCIONADA LA CIUDAD";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }

            if($vector["cargo"]=="")
            {   $salida="EL CAMPO CARGO SE ENCUENTRA VACIO";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }

            if($vector["fecha1"]=="")
            {   $salida="LA FECHA DE INICIO SE ENCUENTRA VACIO";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }

            if($vector["fecha2"]=="")
            {   $salida="LA FECHA DE FINALIZACION SE ENCUENTRA VACIO";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }

            list($elemento1,$elemento2,$elemento3) = explode("-", $vector["fecha1"]);
            $date2=$elemento3."-".$elemento2."-".$elemento1;
            $date2=strtotime($date2);
            list($elemento1,$elemento2,$elemento3) = explode("-", $vector["fecha2"]);
            $date3=$elemento3."-".$elemento2."-".$elemento1;
            $date3=strtotime($date3);
            $s = ($date3 - $date2);

            if($s<=0)
            {
                $salida="LA FECHA DE INGRESO ES MAYOR O IGUAL A LA FECHA DE RETIRO";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }


            
            if($vector ["dias_sem"]=="")
            {   $salida="DEBE SELECCIONAR EN TIEMPO DE DEDICACION LOS DIAS POR SEMANA";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }

            if($vector ["horas_dia"]=="")
            {   $salida="DEBE SELECCIONAR EN TIEMPO DE DEDICACION LAS HORAS POR DIA";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }

            if($vector ["intensidad"]=="")
            {   $salida="DEBE SELECCIONAR EN TIEMPO DE DEDICACION LA INTESIDAD DEL TRABAJO";
                $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
                return $objResponse;
            }


        $resultado=$registrar->Guardar_Trabajos_Anteriores($vector,$tipo_id,$id_paciente,$usuario);
              
        if($resultado===true)
        {
            $salida="<LABEL CLASS='label_error'>DATOS GUARDADOS SATISFACTORIAMENTE </label>";
            $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
            $objResponse->Call("ResetearTrabajos");
        }
        elseif($resultado===false)
        {
            $salida="<LABEL CLASS='label_error'>PROBLEMAS CON LA INSERCION ".$registrar->error."  ".$registrar->mensajeDeError."</label>";
            $objResponse->assign("cuadro_trabajos","innerHTML",$salida);
        
        }        


          $resultado=$registrar->ConsultarTrabajosAnteriores($tipo_id,$id_paciente);
       
         if(!empty($resultado))
         {
             $salida = "                   <table class=\"modulo_table_list\" width='100%' align='center'>\n";
             $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
             $salida .= "                     <td colspan='6' align=\"center\">\n";
             $salida .= "                       HISTORIAL DE TRABAJOS ANTERIORES DEL PACIENTE";
             $salida .= "                     </td>\n";
             $salida .= "                   </tr>\n";
             $salida .= "                   </table>";
             $salida .= "                   <br>\n";
             
             foreach($resultado[0] as $key=>$resultado1)
             {   
           
                    $salida .= "                   <table class=\"modulo_table_list\" width='100%' align='center'>\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                    $salida .= "                     <td width='10%' align=\"center\">\n";
                    $salida .= "                       EMPLEADOR";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='30%' class=\"modulo_list_claro\" align=\"left\">\n";
                    $salida .= "                       ".$resultado1['empleador']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='15%' align=\"center\">\n";
                    $salida .= "                       LUGAR";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td colspan='3' class=\"modulo_list_claro\" align=\"left\">\n";
                    $salida .= "                       ".$resultado1['municipio']."-".$resultado1['departamento']."-".$resultado1['pais']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       CARGO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
                    $salida .= "                       ".$resultado1['cargo']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       FECHA INGRESO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
                    $salida .= "                       ".$resultado1['fecha_ini']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       FECHA RETIRO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
                    $salida .= "                       ".$resultado1['fecha_fin']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                      INTESIDAD";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
                    if($resultado1['intensidad']=='A')
                    {
                        $salida .= "                       ALTA";
                    }
                    elseif($resultado1['intensidad']=='M')
                    {
                        $salida .= "                       MEDIA";
                    }
                    elseif($resultado1['intensidad']=='B')
                    {
                        $salida .= "                       BAJA";
                    }
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       HORAS POR DIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
                    $salida .= "                       ".$resultado1['horas_dia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       DIAS A LA SEMANA ";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
                    $salida .= "                       ".$resultado1['dias_por_semana']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr >\n";
                    $salida .= "                      <td colspan='2' class=\"modulo_list_claro\" align=\"left\" >\n";
                    $salida .= "  <label class='label_error'>NOTA:</label> <BR>";
                    if($resultado1['empresa_elemetos_protectores']=="S")
                    {
                      $salida .= "  <label class='normal_10AN'>* LA EMPRESA SI APORTO ELEMENTOS PROTECTORES PERSONALES </label>";
                    }
                    elseif($resultado1['empresa_elemetos_protectores']=="N")
                    {
                      $salida .= " <label class='normal_10AN'> * LA EMPRESA NO APORTO ELEMENTOS PROTECTORES PERSONALES </label>";
                    }

                    $salida .= "                     <br>\n";
                    
                    if($resultado1['uso_elemetos_protectores']=="S")
                    {
                      $salida .= "<label class='normal_10AN'> * EL PACIENTE SI UTILIZO ELEMENTOS PROTECTORES PERSONALES </label>";
                    }
                    elseif($resultado1['uso_elemetos_protectores']=="N")
                    {
                      $salida .= "<label class='normal_10AN'> * EL PACIENTE NO UTILIZO ELEMENTOS PROTECTORES PERSONALES </label>";
                    }
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td colspan='4'  align=\"left\" class='normal_10AN'>\n";
                    $salida .= "                     <table class=\"modulo_table_list\" width='100%' align='center'>\n";


                    if(!empty($resultado[1][$resultado1['trabajo_id']]))
                    {
                        
                        $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                        $salida .= "                     <td colspan='6' align=\"center\">\n";
                        $salida .= "                      RIESGOS ASOCIADOS A ESE TRABAJO";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                        $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                        $salida .= "                     <td  align=\"center\">\n";
                        $salida .= "                      TIPO DE RIESGO";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td  align=\"center\">\n";
                        $salida .= "                      COLOR";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td  align=\"center\">\n";
                        $salida .= "                      AGENTE DE RIESGO";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                       
                        foreach($resultado[1][$resultado1['trabajo_id']] as $key1 => $valor_riesgos)
                        {
                            $xan=500;
                            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                            $salida .= "                     <td rowspan='".count($valor_riesgos)."' class=\"modulo_list_claro\" align=\"center\">\n";
                            $salida .= "                       ".$key1."";
                            $salida .= "                     </td>\n";

                            foreach($valor_riesgos as $key2 => $agentes_riesgos )
                            {
                                if($xan != 500)
                                {
                                    $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
                                }
                                $salida .= "                     <td bgcolor='".$agentes_riesgos['color']."' align=\"center\">\n";
                                $salida .= "                       &nbsp;";
                                $salida .= "                     </td>\n";
                                $salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
                                $salida .= "                       ".$agentes_riesgos['agente_de_riesgo_nom']."";
                                $salida .= "                     </td>\n";
                                $salida .= "                   </tr>\n";
                                $xan=600;
                            }    
                        }
                    }
                    else
                    {
                        $salida .= "                   <tr style=\"height:100;\" class=\"modulo_list_claro\">\n";
                        $salida .= "                     <td colspan='6' align=\"center\">\n";
                        $salida .= "                      <label class='label_error'>NO SE ASIGNARON AGENTES DE RIESGO A ESTE TRABAJO</label>";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                    }
                    $salida .= "                   </table>";
                    $salida .= "                 </td>\n";
                    $salida .= "               </tr>\n";
                    $salida .= "             </table>";
                    $salida .= "                   <br>";

                    
            }
            
        }

        $objResponse->assign("cuadro_trabajos1","innerHTML",$salida);
        return $objResponse;

    }

    /**
    * Funcion que se utiliza para crear un select el cual contendra los municipiso de determinado departamento
    * @param string $departamento
    * @return string $salida con la lista de la ciudades
    **/
    function Llamar_ciudades($departamento)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaPTA();
        
        $ciudades=$registrar->ConsultarCiudades($departamento);

        if(!empty($ciudades))
        {
            $salida .="   <option value= \"-1\" >SELECCIONAR</option>";
            for($j=0;$j<sizeof($ciudades);$j++)
            {
                $salida .=" <option value= '".$ciudades[$j]['tipo_mpio_id']."' selected>".$ciudades[$j]['municipio']."</option>";
            }
        }
        else
        {
            $salida="<label class='label_error'>ESE DEPARTAMENTO NO TIENEN MUNICIPIOS REGISTRADOS </label>";
        }
        
        $objResponse->assign("ciudades","innerHTML",$salida);
       return $objResponse;

    }

    /**
    * Funcion que se utiliza para GUARDAR LA INFORMACION de enfermedades o accidentes laborales
    * @param string $tipo_id_paciente
    * @param string $paciente_id
    * @param string $enfermedad
    * @param string $accidentes
    * @param string $enfermedades_sw
    * @param string $accidentes_sw
    * @return string $salida
    **/

    function GuardarInfo($tipo_id_paciente,$paciente_id,$enfermedad,$accidentes,$enfermedades_sw,$accidentes_sw)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaPTA();
        $uid = UserGetUID();
        $resultado=$registrar->GuardarInfoEnfermedades($tipo_id_paciente,$paciente_id,$enfermedad,$accidentes,$enfermedades_sw,$accidentes_sw,$uid);
        
        if($resultado===true)
        {
            $salida="<LABEL CLASS='label_error' >DATOS GUARDADOS SATISFACTORIAMENTE </label>";
            $objResponse->assign("lista_enfermedades","innerHTML",$salida);
            $objResponse->Call("ResetearEnfermedades");
        }
        elseif($resultado===false)
        {
            $salida="<LABEL CLASS='label_error'>PROBLEMAS CON LA INSERCION ".$registrar->error."  ".$registrar->mensajeDeError."</label>";
            $objResponse->assign("lista_enfermedades","innerHTML",$salida);
        }

        $resultado1=$registrar->ObtenerDatosEnfermedades($tipo_id_paciente,$paciente_id);



        if(!empty($resultado1))
        {
            $salida = "                   <table class=\"modulo_table_list\" width='100%' align='center'>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td colspan='4' align=\"center\">\n";
            $salida .= "                       HISTORIAL DE ENFERMEDADES Y ACCIDENTES LABORALES";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                     <td width='16%' align=\"center\">\n";
            $salida .= "                       FECHA";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='42%' align=\"center\">\n";
            $salida .= "                       ENFERMEDAD";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='42%' align=\"center\">\n";
            $salida .= "                       ACCIDENTE LABORAL";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            for($i=0;$i<count($resultado1);$i++)
            {
                $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                $salida .= "                     <td class=\"normal_10AN\" align=\"center\" ROWSPAN='".count($valor)."'>\n";
                $salida .= "                       ".$resultado1[$i]['fecha_registro']."";
                $salida .= "                     </td>\n";
                if($resultado1[$i]['enfermedad_profesional']=='1')
                {
                    $salida .= "                     <td class=\"normal_10AN\" align=\"left\" ROWSPAN='".count($valor)."'>\n";
                    $salida .= "                       ".$resultado1[$i]['descripcion_enfermedad']."";
                    $salida .= "                     </td>\n";
                }
                else
                {
                    $salida .= "                     <td class=\"normal_10AN\" align=\"left\" ROWSPAN='".count($valor)."'>\n";
                    $salida .= "                       NINGUNA";
                    $salida .= "                     </td>\n";  
                }



                if($resultado1[$i]['accidente_laboral']=='1')
                {
                    $salida .= "                     <td class=\"normal_10AN\" align=\"left\" ROWSPAN='".count($valor)."'>\n";
                    $salida .= "                       ".$resultado1[$i]['descripcion_accidente']."";
                    $salida .= "                     </td>\n";
                }
                else
                {
                    $salida .= "                     <td class=\"normal_10AN\" align=\"left\" ROWSPAN='".count($valor)."'>\n";
                    $salida .= "                       NINGUNA";
                    $salida .= "                     </td>\n";  
                }


                $salida .= "                   </tr>\n";
            }
            $salida .= "                   </table>";


    }
       $objResponse->assign("lista_enfermedades1","innerHTML",$salida);
       return $objResponse;


}
?>