<?php
// HC_HTML.class.php  23/02/2005
// ----------------------------------------------------------------------
// Copyright (C) 2005 IPSOFT SA.
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: clase de vista para el manejo de HC
// $Id: HC_HTML.class.php,v 1.3 2005/06/09 21:09:49 carlos Exp $
// ----------------------------------------------------------------------

class HC_HTML extends ManejadorDeHC
{
    function HC_HTML()
    {
        $this->ManejadorDeHC();
        return true;
    }
    
    //METODO PARA RETORNAR EL HTML DE LA CABECERA DE LA HC
    function IncludeCabeceraHC()
    {
        $acciones[0]=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],-1);
        $acciones[1]=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'historia');
        $acciones[2]=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar');
        $acciones[3]=ModuloGetURL($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']);
        $acciones[4]=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'apoyod');
        //Activo el javascript de DatosPaciente para las ventanas emergentes      
        $this->SetJavaScripts('DatosPaciente');
        $inforpaciente[0]=RetornarWinOpenDatosPaciente($this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id'],$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido'],'hcPaciente');
        
        if($this->datosPaciente['edad_paciente']['anos']!=""){
            $datos = $this->datosPaciente['edad_paciente']['anos'] .' años, ';
        }
        if($this->datosPaciente['edad_paciente']['meses']!=""){
            $datos.=$this->datosPaciente['edad_paciente']['meses'].' meses, ';
        }
        if($this->datosPaciente['edad_paciente']['dias']!=""){
            $datos.=$this->datosPaciente['edad_paciente']['dias'].' dias.';
        }
        $inforpaciente[1]=$datos;
        $inforpaciente[2]=$this->datosResponsable['nombre_tercero'].' - '.$this->datosResponsable['plan_descripcion'];
        //si se quiere cambiar el color solo cambiar themeMenuAbrirTabla por ThemeAbrirTabla.
        $salida .= ThemeAbrirTablaHistoriaClinica($this->paso,$acciones,$inforpaciente,$this->datosEvolucion['estado']);
        $salida .= "<table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
        $salida .= "    <tr class='modulo_table_list'><td>\n";
        $salida .= $this->IncludeNavegadorDePasos();
        return $salida;
    } //END OF IncludeCabeceraHC()
    
    
    //Metodo que genera el navegador de pasos.
    function IncludeNavegadorDePasos()
    {
        //FILTRAR ESTADOS Y PASOS QUE NO DEBEN MOSTRAR EL NAVEGADOR
        if($this->paso < -1 || $this->datosEvolucion['estado'] == 0)
        {
            return '';
        }
        
        $imagen=GetThemePath().'/images/HistoriaClinica/';        
        
        //OBTENER EL NUMERO DE PASOS VISIBLES
        if($this->mostrarSubmodulosOcultos)
        {
            $numPasos = $this->numPasos[0]+$this->numPasos[1];
        }
        else
        {
            $numPasos = $this->numPasos[1];
        }
        
        //
        if($this->paso == -1)
        {
            $BotonAtras      = FALSE;
            $BotonSiguiente  = TRUE;
        }
        elseif($this->paso >= 1 && $this->paso < $numPasos)
        {
            $BotonAtras      = TRUE;
            $BotonSiguiente  = TRUE;
        }        
        elseif($this->paso == $numPasos)
        {
            $BotonAtras      = TRUE;
            $BotonSiguiente  = FALSE;
        }
        else
        {
            return '';
        }
        
        if($BotonAtras)
        {    
            $NuevoPaso=$this->paso-1;
            $msg = $this->hc_submodulos[$NuevoPaso][0]['TITULO'];

            if($NuevoPaso<1)
            {
                $NuevoPaso = -1;
                $msg = "LISTADO DE PASOS DE LA HISTORIA CLINICA";
            }
                                    
            $accion = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],$NuevoPaso);
            $BotonAtras = "<a href='$accion'><img src='".$imagen."adelante.png' width='22' height='22' align='middle' border='0' title='$msg'></a>\n";
        }
        else
        {
            $BotonAtras = "&nbsp;";
        }
        
        if($BotonSiguiente)
        {
            $NuevoPaso=$this->paso+1;
            if($NuevoPaso<1)
            {
                $NuevoPaso = 1;
            }
            
            $msg = $this->hc_submodulos[$NuevoPaso][0]['TITULO'];                
            $accion = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],$NuevoPaso);
            $BotonSiguiente = "<a href='$accion'><img src='".$imagen."atras.png' width='22' height='22' align='middle' border='0' title='$msg'></a>\n";
        }
        else
        {
            $BotonSiguiente = "&nbsp;";
        }
        
        $salida.="<table align=\"center\" width=\"30%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
        $salida.="    <tr align=\"center\">\n";
        $salida.="        <td align=\"left\">\n";
        $salida.="           $BotonAtras\n";
        $salida.="        </td>\n";
        $salida.="        <td width=\"80%\">\n";
        $salida.="            &nbsp;\n";
        $salida.="        </td>\n";
        $salida.="        <td align=\"right\">\n";
        $salida.="           $BotonSiguiente\n";
        $salida.="        </td>\n";
        $salida.="    </tr>\n";      
        $salida.="</table>\n";     
           
        return $salida;
    }//fin de IncludeNavegadorDePasos()
    
    
    // METODO PARA INCLUIR EL PIE DE PAGINA DE LA HC
    function IncludePieDePaginaHC()
    {
        $salida .="           <br/>\n";
        $salida .= $this->IncludeNavegadorDePasos();
        $salida .= "        </td>\n";
        $salida .= "    </tr>\n";
        $salida .= "    </table>\n";
        $salida.= ThemeCerrarTablaHistoriaClinica();
        return $salida;
    }//fin de IncludePieDePaginaHC()
    
    //Metodo para generar la vista del listado de submodulos
    function ListadoInicioHC()
    {
        $directorioImagenes=GetThemePath().'/images/';  
        $SubmodulosMostrar[1] = &$this->hc_estructura[1];
        $NumeroSubmodulosOcultos = sizeof($this->hc_estructura[0]);
        
        foreach($SubmodulosMostrar as $KeyMostrar=>$VectorSeccion)
        {
            $salida.='<br>'."\n";
            $salida.='<table align="center" width="60%" border="0" cellspacing="0" cellpadding="0">'."\n";
            $salida.='    <tr align="center">'."\n";
            $salida.="        <td align=\"right\">"."\n";
            $salida.='            <img src="'.$directorioImagenes.'/HistoriaClinica/curvaizq.png" width=9 height=28 border=0>'."\n";
            $salida.='        </td>'."\n";
            $salida.="        <td align=\"center\"  background=\"". GetThemePath() ."/images/HistoriaClinica/franjatitulo.png\" class=\"titulo_tabla\">"."\n";
            $salida.='            LISTADO DE PASOS HISTORIA CLÍNICA'."\n";
            $salida.='        </td>'."\n";
            $salida.="        <td align=\"left\">"."\n";
            $salida.='            <img src="'.$directorioImagenes.'/HistoriaClinica/curvader.png" width=9 height=28 border=0>'."\n";
            $salida.='        </td>'."\n";
            $salida.='    </tr>'."\n";      
            $salida.= "<tr align=\"left\">\n";
            $salida.= "    <td width=\"10\" colspan=\"3\">\n";
            $salida.= "        &nbsp;\n";
            $salida.= "    </td>\n";
            $salida.= "</tr>\n";                    
            $salida.= $this->GetFormaSeccionListadoInicioHC(&$VectorSeccion);
            $salida.= "</table>\n";
        }
        
        
        if($NumeroSubmodulosOcultos > 0)
        {        
            if($this->mostrarSubmodulosOcultos )
            {
                $SubmodulosNoMostrar[0] = &$this->hc_estructura[0];
                foreach($SubmodulosNoMostrar as $KeyMostrar=>$VectorSeccion)
                {        
                    $salida.='<br><br>'."\n";
                    $salida.='<table align="center" width="60%" border="0" cellspacing="0" cellpadding="0">'."\n";
                    $salida.='    <tr align="center">'."\n";
                    $salida.="        <td align=\"right\">"."\n";
                    $salida.='            <img src="'.$directorioImagenes.'/HistoriaClinica/curvaizq.png" width=9 height=28 border=0>'."\n";
                    $salida.='        </td>'."\n";
                    $salida.="        <td align=\"center\"  background=\"". GetThemePath() ."/images/HistoriaClinica/franjatitulo.png\" class=\"titulo_tabla\">"."\n";
                    $salida.='            LISTADO DE PASOS ADICIONALES HISTORIA CLÍNICA'."\n";
                    $salida.='        </td>'."\n";
                    $salida.="        <td align=\"left\">"."\n";
                    $salida.='            <img src="'.$directorioImagenes.'/HistoriaClinica/curvader.png" width=9 height=28 border=0>'."\n";
                    $salida.='        </td>'."\n";
                    $salida.='    </tr>'."\n"; 
                    $salida.= "<tr align=\"left\">\n";
                    $salida.= "    <td width=\"10\" colspan=\"3\">\n";
                    $salida.= "        &nbsp;\n";
                    $salida.= "    </td>\n";
                    $salida.= "</tr>\n";                  
                    $salida.= $this->GetFormaSeccionListadoInicioHC(&$VectorSeccion); 
                    $salida.= "</table>\n";
                }          
            }
        

            $salida.= '<br>'."\n";
            $salida.= '<table align="center" width="60%" border="0" cellspacing="0" cellpadding="0">'."\n";        
            $salida.= "    <tr align=\"right\">\n";
            $salida.= "        <td align=\"right\" class=\"normal_11\">\n";    
                    
            if($this->mostrarSubmodulosOcultos && $NumeroSubmodulosOcultos)
            {
                $accion = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],-1,0,'','',array('HC_MOSTRAR_SUBMODULOS_OCULTOS'=>FALSE));
                $salida.= "<a href=\"$accion\">Ocultar pasos adicionales</a>\n";
            }
            else
            {
                $accion = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],-1,0,'','',array('HC_MOSTRAR_SUBMODULOS_OCULTOS'=>TRUE));            
                $salida.= "<a href=\"$accion\">Mostrar pasos adicionales</a>\n";            
            }    
            
            $salida.= "        </td>\n";
            $salida.= "    </tr>\n";                    
            $salida.= "</table>\n";
        }
 
        return $salida;
    }//fin de ListadoInicioHC()
    
    //Metodo para generar cada seccion de la HC en el listado de submodulos
    function GetFormaSeccionListadoInicioHC($VectorSeccion)
    {    
        $separarSeccion=false;
        $directorioImagenes=GetThemePath().'/images/'; 
        $salida.="";
        
        foreach($VectorSeccion as $KeySeccion=>$VectorSubmodulos)
        {
            if($separarSeccion)
            {
                $salida.= "<tr align=\"left\">\n";
                $salida.= "    <td width=\"10\" colspan=\"3\">\n";
                $salida.= "        &nbsp;\n";
                $salida.= "    </td>\n";
                $salida.= "</tr>\n";            
            }
            
            $salida.="<tr align=\"left\">\n";
            $salida.="    <td width=\"10\" colspan=\"3\" class=\"normal_11N\">\n";
            $salida.="        <img src=\"".$directorioImagenes ."/flecha.png\" width='15' height='15'>&nbsp;&nbsp;".$this->hc_secciones[$KeySeccion]."\n";
            $salida.="    </td>\n";
            $salida.="</tr>\n";
            
            $separarSeccion=true;
            
            foreach($VectorSubmodulos as $KeySubmodulo=>$DatosSubmodulo)
            {
                $accion = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],$DatosSubmodulo['PASO']);
                $salida.= '<tr align="center" border="0" cellspacing="0" cellpadding="0">'."\n";
                $salida.= '    <td width="10" align="right">'."\n";
                $salida.= '        <img src="'.$directorioImagenes.'/submenu/borde_izq.png" width=14 height=25 border=0>'."\n";
                $salida.= '    </td>'."\n";
                $salida.= "    <td  class='titulo_tabla_submenu' background=\"". $directorioImagenes ."/submenu/franja.png\">"."\n";
                $salida.= '        <a href="'.$accion.'">'.$DatosSubmodulo['TITULO'].'</a>'."\n";
                $salida.= '    </td>'."\n";
                $salida.= '    <td width="10" align="left">'."\n";
                $salida.= '        <img src="'.$directorioImagenes.'/submenu/borde_der.png" width=14 height=25 border=0>'."\n";
                $salida.= '    </td>'."\n";
                $salida.= '</tr>'."\n";                                
            }

        }        
        return $salida;    
    }//fin de GetFormaSeccionListadoInicioHC()
    
     //Metodo para Incluir los submodulos de un paso de HC
    function IncludePasoHC()
    {
         $submodulosPaso = &$this->hc_submodulos[$this->paso];
         foreach($submodulosPaso as $k=>$datosSubmodolo)
         {
         //Array ( [paso] => 6 [secuencia] => 0 [submodulo] => ExamenFisico [titulo_mostrar] => [titulo_generico] => Examen Físico [hc_seccion_id] => 1 [hc_seccion_titulo] => HISTORIA CLINICA [sw_mostrar] => 1 [parametros] => [TITULO] => Examen Físico ) 
             $SubModulo_obj = &$this->GetObjSubmoduloHC($datosSubmodolo['submodulo']);
            if(!is_object($SubModulo_obj))
            {
                $Err = 'ERROR AL LLAMAR EL SUBMODULO : '.$datosSubmodolo['submodulo'];
                $ErrMsg = 'No se pudo crear el objecto.';
                $TituloVentana = 'ERROR EN LLAMADO A SUBMODULO';
                return $this->MensajeErrorSubmodulo($datosSubmodolo['submodulo'],$Err,$ErrMsg,$TituloVentana);
            }    
            $prefijo='frm_'.$submodulo;
            $SubModulo_obj->setPrefijo($prefijo);        
            $SubModulo_obj->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,$this->paso,$prefijo,$datosSubmodolo['submodulo'],$this->hc_modulo);
            $salida .= $SubModulo_obj->GetSalida();
            foreach($SubModulo_obj->GetJavaScriptsSubmodulos() as $v=>$k)
            {
                $this->SetJavaScripts($v);
            }        
            unset($SubModulo_obj);            
         }
         return $salida;
    }
    
    //Metodo para obtener un objeto tip submodulo
    function GetObjSubmoduloHC($submodulo)
    {
        $fileName = "hc_modules/$submodulo/hc_$submodulo.php";
        if(!IncludeFile($fileName))return false;  
        $fileName = "hc_modules/$submodulo/hc_$submodulo"."_HTML.php";
        if(!IncludeFile($fileName))return false;  
        $className = "$submodulo";
        if(!class_exists($className))return false;
        $className="$submodulo"."_HTML";
        if(!class_exists($className))return false;    
        $SubModulo_obj = new $className();
        return $SubModulo_obj;        
    }
    
    
    //Metodo para Incluir un submodulo de HC
    function IncludeSubmoduloHC($submodulo)
    {
        $fileName = "hc_modules/$submodulo/hc_$submodulo.php";
    
        if(!IncludeFile($fileName)){
            return "El archivo '$fileName' no existe.";
        }
    
        $fileName = "hc_modules/$submodulo/hc_$submodulo"."_HTML.php";
    
        if(!IncludeFile($fileName)){
            return "El archivo '$fileName' no existe.";
        }
    
        $className = "$submodulo";
    
        if(!class_exists($className)){
            return "La clase '$className' no existe.";
        }
    
        $className="$submodulo"."_HTML";
    
        if(!class_exists($className)){
            return "La clase '$className' no existe.";
        }
    
        $SUBMODULO= new $className();
        
        $prefijo='frm_'.$submodulo;
        
        $SUBMODULO->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,$this->paso,$prefijo,$submodulo,$this->hc_modulo);
        $salida = $submodulo_obj->GetSalida();
        foreach($submodulo_obj->GetJavaScriptsSubmodulos() as $v=>$k)
        {
            $this->SetJavaScripts($v);
        }        
        unset($SUBMODULO);
        return $salida;
    }    
    
    
    
    
    
    
    
//fin de los nuevos metodos    
    
    
    
    
    
    
    
        function HistoriaClinicaPaciente($historia,$evolucion,$modulo)
        {
            $salida .= themeAbrirTabla("RESUMEN DE HISTORIA CLINICA");
            $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
            $salida .= "<tr>\n";
            $salida .= "<td align=\"center\"><B>HISTORIAL CRONOLOGICO DEL PACIENTE</B>";
            $salida .= "</td>\n";
            $salida .= "</tr>\n";
            $salida .= "</table>\n";
            foreach($historia as $k=>$v)
            {
                $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                $salida .= "<tr>\n";
                $salida .= "<td>\n";
                $salida.='Ingreso No.:&nbsp;'.$k;
                //$notas=1;
                $s=1;
                foreach($v as $t=>$v)
                {
                    if($s==1)
                    {
                        if(!empty($v['fecha_ingreso']))
                        {
                            $salida.=' - '.$v['fecha_ingreso'].'<br>';
                        }
                        if(!empty($v['comentario']))
                        {
                            $salida.='Descripción Ingreso :&nbsp;'.$v['comentario'].'<br>';
                        }
                        if(!empty($v['descripciondepto']))
                        {
                            $salida.='Departamento :&nbsp;'.$v['descripciondepto'];//via_ingreso_nombre
                        }
                        $salida .= "</td>\n";
                        $salida .= "</tr>\n";
                        $salida .= "<tr>\n";
                        $salida .= "<td>\n";
                        $s=2;
                    }
    
                    $salida .= "<table align=\"center\" width=\"95%\" border=\"0\"  class=\"modulo_table\">\n";
                    $salida .= "<tr>\n";
                    $salida .= "<td colspan=\"2\">\n";
                    $accion=ModuloHCGetURL($evolucion,'pasohc',0,$modulo,$modulo,array('evolucion_consulta'=>$v['evolucion_id']));
                    $salida.="<a href='$accion'>Evolución No.:&nbsp;$t - ".$v['fecha']." - Profesional: ".$v['nombre']."</a>";
                    $salida .= "</td>\n";
                    $salida .= "</tr>\n";
                    if(!empty($v['triage_id']))
                    {
                        $salida .= "<tr>\n";
                        $salida .= "<td width=\"25%\">\n";
                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;Triage:";
                        $salida .= "</td>\n";
                        $salida .= "<td width=\"75%\">\n";
                        $salida.=$v['triage_id'];
                        $salida .= "</td>\n";
                        $salida .= "</tr>\n";
                    }
                    if(!empty($v['descripcionmotiv']))
                    {
                        $salida .= "<tr>\n";
                        $salida .= "<td width=\"25%\">\n";
                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;Motivo:";
                        $salida .= "</td>\n";
                        $salida .= "<td width=\"75%\">\n";
                        $salida.=$v['descripcionmotiv'];
                        $salida .= "</td>\n";
                        $salida .= "</tr>\n";
                    }
                    if(!empty($v['enfermedadactual']))
                    {
                        $salida .= "<tr>\n";
                        $salida .= "<td width=\"25%\">\n";
                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;Enfermedad Actual:";
                        $salida .= "</td>\n";
                        $salida .= "<td width=\"75%\">\n";
                        $salida.=$v['enfermedadactual'];
                        $salida .= "</td>\n";
                        $salida .= "</tr>\n";
                    }
                    if(!empty($v['diagnostico_nombre']))
                    {
                        $salida .= "<tr>\n";
                        $salida .= "<td width=\"25%\">\n";
                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;Diagnostico de Ingreso:";
                        $salida .= "</td>\n";
                        $salida .= "<td width=\"75%\">\n";
                        $salida.=$v['diagnostico_nombre'];
                        $salida .= "</td>\n";
                        $salida .= "</tr>\n";
                    }
                    $salida .= "</table>\n";
                }
    /*            if($notas==2)
                {
                    $salida .= "<table align=\"center\" width=\"95%\" border=\"0\"  class=\"modulo_table\">\n";
                    $salida .= "    <tr>\n";
                    $salida .= "        <td>\n";
                    $accion=ModuloHCGetURL($evolucion,'pasone',0,$modulo,$modulo,array('ingreso_consulta'=>$v['ingreso']));
                    $salida.="<a href='$accion'>Notas Enfermeria</a>";
                    $salida .= "        </td>\n";
                    $salida .= "    </tr>\n";
                    $salida .= "    </table>\n";
                }*/
                $salida .= "        </td>\n";
                $salida .= "    </tr>\n";
                $salida .= "    </table>\n";
            }
            $salida .= themeCerrarTabla();
            return $salida;
        }//fin HistoriaClinicaPaciente
    
        /*function NotasEnfermeria($evolucion,$modulo)
        {
            $salida .= themeAbrirTabla("RESUMEN DE HISTORIA CLINICA");
            $salida .= "<table align=\"center\" width=\"95%\" border=\"0\"  class=\"modulo_table\">\n";
            $salida .= "    <tr>\n";
            $salida .= "        <td align=\"center\" class=\"label_mark\">NO EXISTEN HISTORIALES REGISTRADOS HASTA EL MOMENTO\n";
            //$accion=ModuloHCGetURL($evolucion['evolucion_id'],'pasone',0,$modulo,$modulo,array('ingreso_consulta'=>$evolucion['ingreso']));
            //$salida.="<a href='$accion'>Notas Enfermeria</a>";
            $salida .= "        </td>\n";
            $salida .= "    </tr>\n";
            $salida .= "    </table>\n";
            $salida .= themeCerrarTabla();
            return $salida;
        }*/
    
    
        function Mensaje($mensaje,$url,$modulo)
        {
            $salida .= themeAbrirTabla("Mensaje de Alerta");
            $salida .= "<table align=\"center\" width=\"100%\" border=\"0\">\n";
            $salida .= "    <tr align='center'>\n";
            $salida .= "        <td>\n";
            $salida .= "          <label class=\"label_error\">".strtoupper($mensaje)."</label>";
            $salida .= "        </td>\n";
            $salida .= "    </tr>\n";
            if(!empty($url))
            {
                $salida .= "    <tr align='center'>\n";
                $salida .= "        <td>\n";
                $salida .= "           <a href=\"".$url."\">$modulo</a>";
                $salida .= "        </td>\n";
                $salida .= "    </tr>\n";
            }
            $salida .= "    </table>\n";
            $salida .= themeCerrarTabla();
            return $salida;
        }
    

    function PartirFecha($fecha)
    {
        $a=explode('-',$fecha);
        $b=explode(' ',$a[2]);
        $c=explode(':',$b[1]);
        $d=explode('.',$c[2]);
        return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
    }    
    
        function FormaDiasNE($notas_e)
        {
            $salida .= "<form name=\"formaNotasECtrl\" method=\"post\">";
            $salida.= "        <script>\n";
            $salida .= "            function CargarPagina(href,valor) {\n";
            $salida.= "                var url=href;\n";
            $salida.= "                location.href=url+'&select_fecha='+valor;\n";
            $salida.= "            }\n\n";
            $salida.= "        </script>\n\n";
            $salida.=$notas_e->GetDiasControles();
            $salida.=$notas_e->GetSalida();
            $salida  .="</form>";
            return $salida;
        }
    
    
    
        function ConductaMedica($datos,$datosevolucion,$modulo)
        {
            $salida .= themeAbrirTabla("Conducta Medica");
            $accion=ModuloHCGetURL($datosevolucion['evolucion_id'],'cerrar',0,$modulo,$modulo);
            $salida .= "<form name='cerrar' method='post' action='$accion'>\n";
            $salida .= "<table align=\"center\" width=\"30%\" border=\"0\"  class=\"modulo_table\">\n";
            $spy=0;
            foreach($datos as $k=>$v)
            {
                $salida .= "    <tr align='left'>\n";
                $salida .= "        <td>\n";
                if($spy==0)
                {
                    $salida .= "<input type='radio' name='conducta' value='$k' class='input-text' checked>\n";
                    $spy=1;
                }
                else
                {
                    $salida .= "<input type='radio' name='conducta' value='$k' class='input-text'>\n";
                }
                $salida .= "        </td>\n";
                $salida .= "        <td>\n";
                $salida .= "<label class=\"label\">$v[titulo_mostrar]</label>\n";
                $salida .= "        </td>\n";
                $salida .= "    </tr>\n";
            }
            $salida .= "    <tr align='left'>\n";
            $salida .= "        <td colspan=\"2\">\n";
            $salida.="<input type='submit' name='cerrar' value='Continuar' class='input-submit'>";
            $salida .= "        </form>\n";
            $salida .= "        </td>\n";
            $salida .= "    </tr>\n";
            $salida .= "    </table>\n";
            $salida .= themeCerrarTabla();
            return $salida;
        }
    
        function VolverListado($contenedor,$modulo,$tipo,$metodo,$ingreso,$evolucion)
        {
            $accion1 = ModuloGetURL('app','ImpresionHC','user','main',array('ModuloRETORNO'=>array('contenedor'=>$contenedor,'modulo'=>$modulo,'tipo'=>$tipo,'metodo'=>$metodo),'ingreso'=>$ingreso,'evolucion'=>$evolucion));
            $accion2 = ModuloGetURL($contenedor,$modulo,$tipo,$metodo);
            $salida  = themeAbrirTabla("HISTORIA CLINICA");
            $salida .= "<DIV align='center' class='titulo3'>\n";
            $salida .= "La historia clinica fue cerrada satisfactoriamente. \n";
            $salida .= "</DIV>\n";
            $salida .= "\n";
            $salida .= "<FORM>\n";
            $salida .= "  <TABLE width='80%' cellspacing='2' border='0' cellpadding='2' align='center'>\n";
            $salida .= "    <tr>\n";
            $salida .= "    <td align='center'><A href='$accion1'>Ir al modulo de Impresión</A></td>\n";
            $salida .= "    </tr>\n";
            $salida .= "    <tr>\n";
            $salida .= "    <td align='center'><A href='$accion2'>Listado de pacientes para atención</A></td>\n";
            $salida .= "    </tr>    \n";
            $salida .= "  </TABLE>\n";
            $salida .= "</FORM>\n";
            $salida .= themeCerrarTabla();
            return $salida;
        }
    
    
        function MensajeErrorSubmodulo($submodulo='',$Err='',$ErrMsg='',$TituloVentana='')
        {
            if(empty($Err) && empty($ErrMsg)){
                $Err="El submodulo retorno \"FALSE\"";
                $ErrMsg="Reporte este evento al personal encargado de soporte.";
            }
            else
            {
                if(empty($Err))
                {
                    $Err="&nbsp;";
                }
    
                if(empty($ErrMsg))
                {
                    $ErrMsg="&nbsp;";
                }
            }
            if(!empty($TituloVentana))
            {
                $TituloVentana = "Mensaje retornado por el submodulo $submodulo";
            }
            $salida .= ThemeAbrirTablaSubModulo("Mensaje retornado por el submodulo $submodulo");
            $salida .= "<table align=\"center\" width=\"100%\" border=\"0\">\n";
            $salida .= "    <tr align='center'>\n";
            $salida .= "        <td>\n";
            $salida .= "          <label class=\"titulo3_error\">".strtoupper($Err)."</label>";
            $salida .= "        </td>\n";
            $salida .= "    </tr>\n";
            $salida .= "    <tr align='center'>\n";
            $salida .= "        <td>\n";
            $salida .= "          <label class=\"titulo3\">$ErrMsg</label>";
            $salida .= "        </td>\n";
            $salida .= "    </tr>\n";
            $salida .= "    </table>\n";
            $salida .= ThemeCerrarTablaSubModulo();
            return $salida;
        }
}
?>
