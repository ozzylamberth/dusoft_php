<?php
//este

function PartirFecha($fecha)
{
    $a=explode('-',$fecha);
    $b=explode(' ',$a[2]);
    $c=explode(':',$b[1]);
    $d=explode('.',$c[2]);
    return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
}

function IncludeCabeceraHC($datosPaciente,$datosEvolucion,$paso,$modulo,$numPasos,$datosResponsable)
{

    if(empty($datosPaciente) or empty($datosEvolucion))
    {
        return false;
    }
    $acciones[0]=ModuloHCGetURL($datosEvolucion['evolucion_id'],-1,0,$modulo);
    $acciones[1]=ModuloHCGetURL($datosEvolucion['evolucion_id'],'historia',0,$modulo);
    $acciones[2]=ModuloHCGetURL($datosEvolucion['evolucion_id'],'cerrar',0,$modulo,$modulo,array('primera'=>1,'DESMARCAR'=>1));
    if(!empty($_SESSION['HISTORIACLINICA']['RETORNO']))
    {
            if(!empty($_SESSION['HISTORIACLINICA']['RETORNO']['argumentos'])){
        $acciones[3]=ModuloGetURL($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'],$_SESSION['HISTORIACLINICA']['RETORNO']['argumentos']);
            }else{
                $acciones[3]=ModuloGetURL($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']);
            }
    }
    else
    {
        $acciones[3]=null;
    }

    $acciones[4]=ModuloHCGetURL($datosEvolucion['evolucion_id'],'apoyod',0,$modulo);
    $inforpaciente[0]=RetornarWinOpenDatosPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id'],$datosPaciente['primer_nombre'].' '.$datosPaciente['segundo_nombre'].' '.$datosPaciente['primer_apellido'].' '.$datosPaciente['segundo_apellido'],'hcPaciente');
    $edad=CalcularEdad($datosPaciente['fecha_nacimiento'],$datosEvolucion['fecha']);
    if($edad['anos']!=""){
        $datos.=$edad['anos'].' años, ';
    }
    if($edad['meses']!=""){
        $datos.=$edad['meses'].' meses, ';
    }
    if($edad['dias']!=""){
        $datos.=$edad['dias'].' dias.';
    }
    $inforpaciente[1]=$datos;
    $inforpaciente[2]=$datosResponsable['nombre_tercero'].' - '.$datosResponsable['plan_descripcion'];
    //si se quiere cambiar el color solo cambiar themeMenuAbrirTabla por ThemeAbrirTabla.
    $salida .= ThemeAbrirTablaHistoriaClinica($paso,$acciones,$inforpaciente,$datosEvolucion['estado']);
    $salida .= "<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
    $salida .= "    <tr class='modulo_table_list'><td>\n";
    return $salida;
}

function IncludePasosDeHC($datosEvolucion,$paso,$modulo,$numPasos)
{
    $imagen=GetThemePath().'/images/HistoriaClinica/';
    if($paso>=0 and $datosEvolucion['estado']==1)
    {
        $salida.='<table align="center" width="90%" border="0">';
        $salida.='<tr align="center">';
        $salida.='<td width="45%" align="right">';
        if($paso!=-1 AND $paso!=$numPasos)
        {
            $accion=ModuloHCGetURL($datosEvolucion['evolucion_id'],$paso-1,0,$modulo,$modulo,array('devolver'=>1));
            $salida .= "              <a href='$accion'><img src='".$imagen."adelante.png' width='22' height='22' align='middle' border='0' title=\"ANTERIOR\"></a>";
        }
        $salida.='</td>';
        $salida.='<td width="5%" align="left">';
        $salida.='</td>';

        $salida.='<td width="45%" align="left">';
        if($paso!=$numPasos-1 and $paso!=$numPasos)
        {
                    $accion=ModuloHCGetURL($datosEvolucion['evolucion_id'],$paso+1,0,$modulo);
                    $salida .= "<a href='$accion'><img src='".$imagen."atras.png' width='22' height='22' align='middle' border='0' title=\"SIGUIENTE\"></a>";
        }
/***************/
        if($paso==$numPasos-1)
        {
            $accion=ModuloHCGetURL($datosEvolucion['evolucion_id'],$paso+1,0,$modulo);
            $salida .= "<a href='$accion'><img src='".$imagen."cerrar.png' width='22' height='22' align='middle' border='0'></a>";
        }
/***************/
        $salida.='</td>';
        $salida.='</tr>';
        $salida .= "    </table>\n";
    }
    return $salida;
}

function IncludePieDePaginaHC($datosEvolucion,$paso,$modulo,$numPasos)
{
    $imagen=GetThemePath().'/images/HistoriaClinica/';
    $salida .= "        </td>\n";
    $salida .= "    </tr>\n";
    if($paso>=0 and $datosEvolucion['estado']==1)
    {
        $salida.='<tr align="center">';
        $salida.='<td>';
        $salida.='<table align="center" width="90%" border="0">';
        $salida.='<tr align="center">';
        $salida.='<td width="45%" align="right">';
        if($paso!=-1 AND $paso!=$numPasos)
        {
            $accion=ModuloHCGetURL($datosEvolucion['evolucion_id'],$paso-1,0,$modulo,$modulo,array('devolver'=>1));
            $salida .= "              <a href='$accion'><img src='".$imagen."adelante.png' width='22' height='22' align='middle' border='0' title=\"ANTERIOR\"></a>";
        }
        $salida.='</td>';
        $salida.='<td width="5%" align="left">';
        $salida.='</td>';

        $salida.='<td width="45%" align="left">';
        if($paso!=$numPasos-1 and $paso!=$numPasos)
        {
                    $accion=ModuloHCGetURL($datosEvolucion['evolucion_id'],$paso+1,0,$modulo);
                    $salida .= "              <a href='$accion'><img src='".$imagen."atras.png' width='22' height='22' align='middle' border='0' title=\"SIGUIENTE\"></a>";
        }
/***************/
        if($paso==$numPasos-1)
        {
            $accion=ModuloHCGetURL($datosEvolucion['evolucion_id'],$paso+1,0,$modulo);
            $salida .= "<a href='$accion'><img src='".$imagen."cerrar.png' width='22' height='22' align='middle' border='0'></a>";
        }
/***************/
        $salida.='</td>';
        $salida.='</tr>';
        $salida .= "    </table>\n";
        $salida.='</td>';
        $salida.='</tr>';
    }
    $salida .= "    </table><br>\n";
    $salida.= ThemeCerrarTablaHistoriaClinica();
    return $salida;
}

function ListadoInicioHC($submodulos,$evolucion,$modulo)
{
     $salida.= ThemeMenuAbrirTabla("HISTORIA CLÍNICA","60%","C");
     $salida.='<br>';
     $salida.='<table align="center" width="85%" border="0" cellspacing="0" cellpadding="0">';
     $i=0;
     $imagen=GetThemePath().'/images/';
     $salida.='<tr align="center">';
     /*    $salida.="<td align=\"right\">";
     $salida.='<img src="'.$imagen.'/HistoriaClinica/curvaizq.png" width=10 height=30 border=0>';
     $salida.='</td>';*/
     $salida.="<td colspan=\"3\" align=\"center\" class='normal_11N'>";// background=\"". GetThemePath() ."/images/HistoriaClinica/franjatitulo.png\" class=\"titulo_tabla\"
     $salida.='LISTADO DE SUBMODULOS HISTORIA CLÍNICA';
     $salida.='</td>';
     /*    $salida.="<td align=\"left\">";
     $salida.='<img src="'.$imagen.'/HistoriaClinica/curvader.png" width=10 height=30 border=0>';
     $salida.='</td>';*/
     $salida.='</tr>';

     $salida1.='<br>';
     $salida1.='<table align="center" width="85%" border="0" cellspacing="0" cellpadding="0">';


     $salida1.='<tr align="center">';
     /*    $salida1.="<td align=\"right\">";
     $salida1.='<img src="'.$imagen.'/HistoriaClinica/curvaizq.png" width=9 height=30 border=0>';
     $salida1.='</td>';*/
     $salida1.="<td colspan=\"3\" align=\"center\" class='normal_11N'>";// background=\"". GetThemePath() ."/images/HistoriaClinica/franjatitulo.png\" class=\"titulo_tabla\"
     $salida1.='LISTADO DE SUBMODULOS ADICIONALES HISTORIA CLÍNICA';
     $salida1.='</td>';
     /*    $salida1.="<td align=\"left\">";
     $salida1.='<img src="'.$imagen.'/HistoriaClinica/curvader.png" width=9 height=30 border=0>';
     $salida1.='</td>';*/
     $salida1.='</tr>';


    $saber=0;
    foreach($submodulos as $k=>$v)
    {
        foreach($v as $t=>$r)
        {
            foreach($r as $h=>$m)
            {
                if($m['sw_mostrar']==1)
                {
                    if(empty($dato))
                    {
                         $dato=$h;
                         $salida.='<tr align="left">';
                         $salida.='<td width="10" colspan="3" class="normal_11N">';
                         $salida.="<br><img src=\"". GetThemePath() ."/images/edita.png\" width='15' height='15'>&nbsp;&nbsp;".$m['agrupamiento'];
                         $salida.='</td>';
                         $salida.='</tr>';
                         $salida.='<tr><td> </td></tr>';
                         $salida.='<tr><td> </td></tr>';

                    }
                    if($dato!=$h)
                    {
                         $dato=$h;
                         $salida.='<tr align="left">';
                         $salida.='<td width="10" colspan="3">';
                         $salida.='<br></td>';
                         $salida.='</tr>';
                         $salida.='<tr align="left">';
                         $salida.='<td width="10" colspan="3" class="normal_11N">';
                         $salida.="<img src=\"". GetThemePath() ."/images/edita.png\" width='15' height='15'>&nbsp;&nbsp;".$m['agrupamiento'];
                         $salida.='</td>';
                         $salida.='</tr>';
                         $salida.='<tr><td> </td></tr>';
                         $salida.='<tr><td> </td></tr>';
                    }
                    $salida.='<tr align="center" border="0" cellspacing="0" cellpadding="0">';
                    $salida.='<td width="10" align="right">';
                    $salida.='<img src="'.$imagen.'/submenu/borde_izq.png" width="14" height="26">';
                    $salida.='</td>';
                    $salida.="<td class='titulo_tabla_submenu' background=\"". GetThemePath() ."/images/submenu/franja.png\" width='100%'>";
                    $accion=ModuloHCGetURL($evolucion,$i,0,$modulo);
                    $salida.='<a href="'.$accion.'">'.strtoupper($m['descripcion']).'</a>';
                    $salida.='</td>';
                    $salida.='<td width="10" align="left">';
                    $salida.='<img src="'.$imagen.'/submenu/borde_der.png" width="14" height="26">';
                    $salida.='</td>';
                    $salida.='</tr>';
                }
                else
                {
                    $saber=1;
                    if(!empty($_REQUEST['mostrar']))
                    {
                        if(empty($dato1))
                        {
                            $dato1=$h;
                            $salida1.='<tr align="left">';
                            $salida1.='<td width="10" colspan="3" class="normal_11N">';
                            $salida1.="<br><img src=\"". GetThemePath() ."/images/edita.png\" width='15' height='15'>&nbsp;&nbsp;".$m['agrupamiento'];
                            $salida1.='</td>';
                            $salida1.='</tr>';
                        }
                        if($dato1!=$h)
                        {
                            $dato1=$h;
                            $salida1.='<tr align="left">';
                            $salida1.='<td width="10" colspan="3">';
                            $salida1.='<br></td>';
                            $salida1.='</tr>';
                            $salida1.='<tr align="left">';
                            $salida1.='<td width="10" colspan="3" class="normal_11N">';
                            $salida1.="<img src=\"". GetThemePath() ."/images/edita.png\" width='15' height='15'>&nbsp;&nbsp;".$m['agrupamiento'];
                            $salida1.='</td>';
                            $salida1.='</tr>';
                        }
                        $salida1.='<tr align="center" border="0" cellspacing="0" cellpadding="0">';
                        $salida1.='<td width="10" align="right">';
                        $salida1.='<img src="'.$imagen.'/submenu/borde_izq.png" width="14" height="26">';
                        $salida1.='</td>';
                        $salida1.="<td  class='titulo_tabla_submenu' background=\"". GetThemePath() ."/images/submenu/franja.png\" width='100%'>";
                        $accion=ModuloHCGetURL($evolucion,$i,0,$modulo,$modulo,array('mostrar'=>$_REQUEST['mostrar']));
                        $salida1.='<a href="'.$accion.'">'.strtoupper($m['descripcion']).'</a>';
                        $salida1.='</td>';
                        $salida1.='<td width="10" align="left">';
                        $salida1.='<img src="'.$imagen.'/submenu/borde_der.png" width="14" height="26" border=0>';
                        $salida1.='</td>';
                        $salida1.='</tr>';
                    }
                }
            }
        }
        $i++;
    }
    if(empty($_REQUEST['mostrar']) and $saber==1)
    {
        $salida.="<tr align=\"right\">\n";
        $salida.="<td align=\"right\" colspan=\"3\"  class=\"normal_11\">\n";
        $accion=ModuloHCGetURL($evolucion,'inicio',0,$modulo,$modulo,array('mostrar'=>1));
        $salida.="<a href=\"$accion\">Otros Submodulos</a>\n";
        $salida.="</td>\n";
        $salida.="</tr>\n";
    }
    else
    {
        $salida1.="<tr align=\"right\">\n";
        $salida1.="<td align=\"right\" colspan=\"3\"  class=\"normal_11\">\n";
        $accion=ModuloHCGetURL($evolucion,'inicio',0,$modulo,$modulo);
        $salida1.="<a href=\"$accion\">Ocultar Otros Submodulos</a>\n";
        $salida1.="</td>\n";
        $salida1.="</tr>\n";
    }
    $salida .= "            </table>\n";

    $salida1 .= "            </table>\n";
    if(!empty($_REQUEST['mostrar']))
    {
        $salida.=$salida1;
    }
    $salida .= ThemeMenuCerrarTabla();
    return $salida;
}


    function HistoriaClinicaPaciente($historia,$evolucion,$modulo)
    {
        $salida .= themeAbrirTabla("RESUMEN DE HISTORIA CLINICA");
          if (!empty($historia))
          {
               $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
               $salida .= "<tr>\n";
               $salida .= "<td align=\"center\"><B>HISTORIAL CRONOLOGICO DEL PACIENTE</B>";
               $salida .= "</td>\n";
               $salida .= "</tr>\n";
               $salida .= "</table>\n";
        }
          else
          {
               $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
               $salida .= "<tr>\n";
               $salida .= "<td align=\"center\"><B>EL PACIENTE AUN NO PRESENTA HISTORIAL CRONOLOGICO</B>";
               $salida .= "</td>\n";
               $salida .= "</tr>\n";
               $salida .= "</table>\n";
          }
        foreach($historia as $k=>$v)
        {
            $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
            $salida .= "<tr>\n";
            $salida .= "<td>\n";
            $salida .= '<b>Ingreso No.:</b>&nbsp;'.$k;
            $salida .= "</td>\n";
            $salida .= "</tr>\n";
            $salida .= "</table>\n";
               if ($centinela != $k)
               {
                $y = 1;
               }
            foreach($v as $x=>$s)
            {
                    for($b=0; $b<sizeof($s); $b++)
                {
                    for($l=0; $l<sizeof($s); $l++)
                    {
                        $triage[] = $s[$l]['triage_id'];
                        $motivo[] = $s[$l]['motivo_consulta'];
                        $enfermedad[] = $s[$l]['enfermedad_actual'];
                        $diagnostico[] = $s[$l]['diagnostico_nombre'];
                    }

                         $centinela = $s[$b]['ingreso'];

                         if($y != 2)
                         {
                            $y = 2;
                              $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                              $salida .= "<tr>\n";
                              $salida .= "<td>\n";
                              if(!empty($s[$b]['fecha_ingreso']))
                              {
                                   $salida.='<b>Fecha de Ingreso :</b>&nbsp; '.$s[$b]['fecha_ingreso'].'<br>';
                              }
                              if(!empty($s[$b]['comentario']))
                              {
                                   $salida.='<b>Descripción Ingreso :</b>&nbsp;'.$s[$b]['comentario'].'<br>';
                              }
                              if(!empty($s[$b]['dpto']))
                              {
                                   $salida.='<b>Departamento :</b>&nbsp;'.$s[$b]['dpto'];//via_ingreso_nombre
                              }
                              $salida .= "</td>\n";
                              $salida .= "</tr>\n";
                              $salida .= "</table>\n";
                         }

                    if($s[$b]['evolucion_id'] != $s[$b-1]['evolucion_id'])
                    {
                        $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                        $salida .= "<tr>\n";
                        $salida .= "<td>\n";
                        $accion=ModuloHCGetURL($evolucion,'pasohc',0,$modulo,$modulo,array('evolucion_consulta'=>$s[$b]['evolucion_id']));
                        $salida.="<a href='$accion'><b>Evolución No.:</b>&nbsp;".$s[$b]['evolucion_id']." - ".$s[$b]['fecha_evolucion']." - <b>Profesional:</b> ".$s[$b]['nombre_medico']." - ".$s[$b]['descipcion_medico']."</a>";
                        $salida .= "</td>\n";
                        $salida .= "</tr>\n";
                        $salida .= "</table>\n";

                        for($a=0; $a<sizeof($triage); $a++)
                        {
                            if(!empty($s[$a]['triage_id']) AND $s[$a]['triage_id'] != $s[$a-1]['triage_id'])
                            {
                                $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                                $salida .= "<tr>\n";
                                $salida .= "<td width=\"25%\">\n";
                                $salida.="&nbsp;&nbsp;&nbsp;&nbsp;Triage - $a :";
                                $salida .= "</td>\n";
                                $salida .= "<td width=\"75%\">\n";
                                $salida.=$s[$a]['triage_id'];
                                $salida .= "</td>\n";
                                $salida .= "</tr>\n";
                                $salida .= "</table>\n";
                            }
                        }

                              for($j=0; $j<sizeof($motivo); $j++)
                        {
                            if(!empty($s[$j]['motivo_consulta']) AND $s[$j]['motivo_consulta'] != $s[$j-1]['motivo_consulta'])// AND $s[$b]['evolucion_id'] == $s[$b]['evo_motivo']
                            {
                                $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                                $salida .= "<tr>\n";
                                $salida .= "<td width=\"25%\">\n";
                                $salida.="&nbsp;&nbsp;&nbsp;&nbsp;<b>Motivo - $j :</b>";
                                $salida .= "</td>\n";
                                $salida .= "<td width=\"75%\">\n";
                                $salida.=$s[$j]['motivo_consulta'];
                                $salida .= "</td>\n";
                                $salida .= "</tr>\n";
                                $salida .= "</table>\n";
                            }
                        }

                        for($z=0; $z<sizeof($enfermedad); $z++)
                        {
                            if(!empty($s[$z]['enfermedad_actual']) AND $s[$z]['enfermedad_actual'] != $s[$z-1]['enfermedad_actual'])// AND $s[$b]['evolucion_id'] == $s[$b]['evo_motivo']
                            {
                                $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                                $salida .= "<tr>\n";
                                $salida .= "<td width=\"25%\">\n";
                                $salida.="&nbsp;&nbsp;&nbsp;&nbsp;<b>Enfermedad Actual - $z :</b>";
                                $salida .= "</td>\n";
                                $salida .= "<td width=\"75%\">\n";
                                $salida.=$s[$z]['enfermedad_actual'];
                                $salida .= "</td>\n";
                                $salida .= "</tr>\n";
                                $salida .= "</table>\n";
                            }
                        }

                        $dx = array_unique($diagnostico);

                        for($w=0; $w<sizeof($dx); $w++)
                        {

                            if(!empty($s[$w]['diagnostico_nombre']) AND $s[$w]['diagnostico_nombre'] != $s[$w-1]['diagnostico_nombre'])
                            {
                                $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                                $salida .= "<tr>\n";
                                $salida .= "<td width=\"25%\">\n";
                                $salida.="&nbsp;&nbsp;&nbsp;&nbsp;<b>Diagnostico de Ingreso - $w :</b>";
                                $salida .= "</td>\n";
                                $salida .= "<td width=\"75%\">\n";
                                $salida.=$s[$w]['diagnostico_nombre'];
                                $salida .= "</b></td>\n";
                                $salida .= "</tr>\n";
                                $salida .= "</table>\n";
                            }
                        }
                    }
                }
            }
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
        
	$_SESSION['evolucion']=$datosevolucion['evolucion_id'];
	$_SESSION['mod']=$modulo;
	
	$salida .= themeAbrirTabla("Conducta Medica");
        $accion=ModuloHCGetURL($datosevolucion['evolucion_id'],'cerrar',0,$modulo,$modulo);
        $salida .= "<form name='cerrar' method='post' action='$accion'>\n";
        $salida .= "<table align=\"center\" width=\"50%\" border=\"0\"  >\n";
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
            $salida .= "           <label class=\"label\">$v[titulo_mostrar]</label>\n";
            $salida .= "        </td>\n";
            $salida .= "    </tr>\n";
        }

        $salida .= "    <tr align='left'>\n";
        $salida .= "        <td colspan=\"2\">\n";
        $salida .= "            &nbsp;\n";
        $salida .= "        </td>\n";
        $salida .= "    </tr>\n";

        $salida .= "    <tr align='left'>\n";
        $salida .= "        <td colspan=\"2\">\n";
        $salida .= "            <label class=\"label\">OBSERVACIONES</label>\n";
        $salida .= "        </td>\n";
        $salida .= "    </tr>\n";

        $salida .= "    <tr align='left'>\n";
        $salida .= "        <td colspan=\"2\">\n";
        $salida .= "            <textarea cols='8' name='conducta_observacion' style='width:100%' class=\"textarea\"></textarea>\n";
        $salida .= "        </td>\n";
        $salida .= "    </tr>\n";

        $salida .= "    <tr align='left'>\n";
        $salida .= "        <td colspan=\"2\">\n";
        $salida .= "            &nbsp;\n";
        $salida .= "        </td>\n";
        $salida .= "    </tr>\n";

        $salida .= "    <tr align='left'>\n";
        $salida .= "        <td colspan=\"2\">\n";
        $salida .= "           <input type='submit' name='cerrar' value='Continuar' class='input-submit'>";
        $salida .= "        </td>\n";
        $salida .= "    </tr>\n";
        $salida .= "    </table>\n";
        $salida .= "    </form>\n";
        $salida .= themeCerrarTabla();
        return $salida;
    }

    function VolverListado($contenedor,$modulo,$tipo,$metodo,$ingreso,$evolucion)
    {
        $accion1 = ModuloGetURL('app','ImpresionHC','user','main',array('ModuloRETORNO'=>array('contenedor'=>$contenedor,'modulo'=>$modulo,'tipo'=>$tipo,'metodo'=>$metodo),'ingreso'=>$ingreso,'evolucion'=>$evolucion));
        $accion2 = ModuloGetURL($contenedor,$modulo,$tipo,$metodo);
				$accion3=ModuloGetURL('app','HonorariosMedicos','user','FormaConsultarPorFecha');
        $salida  = themeAbrirTabla("HISTORIA CLINICA");
        $salida .= "<DIV align='center' class='titulo3'>\n";
        $salida .= "La historia clinica fue cerrada satisfactoriamente. \n";
        $salida .= "</DIV>\n";
        $salida .= "\n";
        $salida .= "<FORM>\n";
        $salida .= "  <TABLE width='80%' cellspacing='2' border='0' cellpadding='2' align='center'>\n";
				$salida .= "    <tr>\n";
        $salida .= "    <td align='center'><A href='$accion3'>Honorarios Medicos</A></td>\n";
        $salida .= "    </tr>   \n";
        $salida .= "    <tr>\n";
        $salida .= "    <td align='center'><A href='$accion1'>Ir al modulo de Impresión</A></td>\n";
        $salida .= "    </tr>\n";
        $salida .= "    <tr>\n";
        $salida .= "    <td align='center'><A href='$accion2'>Listado de pacientes para atención</A></td>\n";
        $salida .= "    </tr>   \n";
        $salida .= "  </TABLE>\n";
        $salida .= "</FORM>\n";
        $salida .= themeCerrarTabla();
        return $salida;
    }


    function MensajeErrorSubmodulo($submodulo='',$Err='',$ErrMsg='')
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
?>
