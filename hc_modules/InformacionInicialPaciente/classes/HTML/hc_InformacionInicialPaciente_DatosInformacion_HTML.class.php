<?php
  /** 
  * $Id: hc_InformacionInicialPaciente_DatosInformacion_HTML.class.php,v 1.3 2008/11/18 16:31:48 hugo Exp $
  * 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS-FI
  * 
  * $Revision: 1.3 $ 
  * 
  * @autor J gomez
  */
  class DatosInformacion_HTML
  {  
     function DatosInformacion_HTML($objeto=null)
     {
          $this->obj=$objeto;
          return true;
     }

     
     function frmConsulta($consulta,$hs)
     {
          if(empty($hs[0]['fecha_registro']))
          {
               $this->salida.="<table  border=\"1\" width=\"100%\"  align=\"center\" >\n";
               $this->salida .="<tr>\n";
               $this->salida .=   "<td COLSPAN=3 align=\"center\">NO HAY RESULTADOS DE EXAMEN FISICO</td>\n";
               $this->salida .= "</tr>\n";
               $this->salida.="</table>\n";
               return $this->salida;
          }       
          else
          {    
              
                    $contadorcapas=0;
                    $evolucion=$consulta[0]['evolucion_id'];
                    $vectordecapas=Array();
                    $sistemasexaminados=0;
                    for($j=0;$j<count($consulta);$j++)
                    { 
                        if($evolucion!=$consulta[$j]['evolucion_id'])
                         {
                           $vectordecapas[$contadorcapas]=$sistemasexaminados;
                           $contadorcapas++;
                           $sistemasexaminados=1;
                           $evolucion=$consulta[$j]['evolucion_id'];
                         }
                         else
                         {
                           $sistemasexaminados++;
                         }
                    } 
                    if($j==count($consulta))
                    {
                       $vectordecapas[$contadorcapas]=$sistemasexaminados;
                    }  
                    
                    
                    
                    $j=0;
                    $W=50000;
                    $this->salida.="<table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\" >\n";
                    $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
                    $this->salida .= "<td COLSPAN=3 align=\"center\">EXAMEN FISICO</td>\n";    
                    $this->salida .= "</tr>\n";
                    $this->salida .= "</tABLE>\n";
                    $this->salida .= "<BR>\n";
                    for($contadorcapas=0;$contadorcapas<count($vectordecapas);$contadorcapas++)
                    { 
                        $this->salida.="<table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\" >\n";
                        $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
                        $resto = substr ($hs[$contadorcapas]['fecha_registro'], 0, 10);
                        $this->salida .= "<td COLSPAN=2 align=\"left\" width=\"700\">PROFESIONAL:".$hs[$contadorcapas]['nombre']."</td>\n";
                        $this->salida .= "<td COLSPAN=1 align=\"center\" width=\"700\">FECHA:".$resto."</td>\n";
                        $this->salida .= "</tr>\n";
                        $this->salida.= "<tr>\n";
                        $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"25%\">SISTEMA</td>\n";
                        $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"15%\">ESTADO</td>\n";
                        $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"60%\">OBSERVACIONES</td>\n";
                        $this->salida.= "</tr>\n";
                        $limitecapa=$vectordecapas[$contadorcapas];
                        $limitecapa=$limitecapa+$j;
                        for($i=$j;$i<$limitecapa;$i++)//historia_actual_osc.gif
                        {   $zorro++;
                              $this->salida .= "<tr>\n";
                              $this->salida .= "<td align=\"center\" style='font-size:9pt;' class=\"hc_list_oscuro\" width=\"20%\">".$consulta[$i]['nombre']."</td>\n";
                              if($consulta[$i]['sw_normal']=='A')
                              { 
                                $this->salida .= "<td bgcolor=RED align=\"center\" width=\"15%\">ANORMAL</td>\n";
                              }
                              elseif($consulta[$i]['sw_normal']=='N')
                              { 
                                $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"15%\">NORMAL</td>\n";
                              }
                              
                              if($i==$j)
                              { 
                                   $this->salida .= "<td align=\"center\" rowspan='".$vectordecapas[$contadorcapas]."' class=\"hc_list_oscuro\" <TEXTAREA NAME=strg, ROWS=".$vectordecapas[$contadorcapas]." COLS=55 OnFocus=\"this.blur()\">".$hs[$contadorcapas]['hallazgo']."</TEXTAREA></td>\n";
                              } 
                              $this->salida .= "</tr>\n";
                         }
                         $j=$i;
                         $this->salida.="</table>";
                         $this->salida.="<br>";
                    }  
               return $this->salida;
          }  
     }
   
     function frmHistoria($consulta,$hs)
     {
          if(empty($hs[0]['fecha_registro']))
          {
               $this->salida.="<table  border=\"1\" width=\"100%\"  align=\"center\" >\n";
               $this->salida .="<tr>\n";
               $this->salida .=   "<td COLSPAN=3 align=\"center\">NO HAY RESULTADOS DE EXAMEN FISICO</td>\n";
               $this->salida .= "</tr>\n";
               $this->salida.="</table>\n";
               return $this->salida;
          }       
          else
          {    
              
                    $contadorcapas=0;
                    $evolucion=$consulta[0]['evolucion_id'];
                    $vectordecapas=Array();
                    $sistemasexaminados=0;
                    for($j=0;$j<count($consulta);$j++)
                    { 
                        if($evolucion!=$consulta[$j]['evolucion_id'])
                         {
                           $vectordecapas[$contadorcapas]=$sistemasexaminados;
                           $contadorcapas++;
                           $sistemasexaminados=1;
                           $evolucion=$consulta[$j]['evolucion_id'];
                         }
                         else
                         {
                           $sistemasexaminados++;
                         }
                    } 
                    if($j==count($consulta))
                    {
                       $vectordecapas[$contadorcapas]=$sistemasexaminados;
                    }  
                    
                    
                    
                    $j=0;
                    $W=50000;
                    $this->salida.="<table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\" >\n";
                    $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
                    $this->salida .= "<td COLSPAN=3 align=\"center\">EXAMEN FISICO</td>\n";    
                    $this->salida .= "</tr>\n";
                    $this->salida .= "</tABLE>\n";
                    $this->salida .= "<BR>\n";
                    for($contadorcapas=0;$contadorcapas<count($vectordecapas);$contadorcapas++)
                    { 
                           $this->salida.="<table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\" >\n";
                           $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
                           $resto = substr ($hs[$contadorcapas]['fecha_registro'], 0, 10); 
                           $this->salida .= "<td COLSPAN=2 align=\"left\" width=\"700\">PROFESIONAL:".$hs[$contadorcapas]['nombre']."</td>\n";
                           $this->salida .= "<td COLSPAN=1 align=\"center\" width=\"700\">FECHA:".$resto."</td>\n";    
                           $this->salida .= "</tr>\n";
                           $this->salida.= "<tr>\n";
                           $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"25%\">SISTEMA</td>\n";
                           $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"15%\">ESTADO</td>\n";
                           $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"60%\">OBSERVACIONES</td>\n";
                           $this->salida.= "</tr>\n";
                           $limitecapa=$vectordecapas[$contadorcapas];
                           $limitecapa=$limitecapa+$j;                         
                         for($i=$j;$i<$limitecapa;$i++)//historia_actual_osc.gif
                         {   $zorro++;
                              $this->salida .= "<tr>\n";
                              $this->salida .= "<td align=\"center\" style='font-size:9pt;' class=\"hc_list_oscuro\" width=\"20%\">".$consulta[$i]['nombre']."</td>\n";
                              if($consulta[$i]['sw_normal']=='A')
                              { 
                                $this->salida .= "<td bgcolor=RED align=\"center\" width=\"15%\">ANORMAL</td>\n";
                              }
                              elseif($consulta[$i]['sw_normal']=='N')
                              { 
                                $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"15%\">NORMAL</td>\n";
                              }
                              
                              if($i==$j)
                              { 
                                   $this->salida .= "<td align=\"center\" rowspan='".$vectordecapas[$contadorcapas]."' class=\"hc_list_oscuro\">".$hs[$contadorcapas]['hallazgo']."</td>\n";
                              } 
                              $this->salida .= "</tr>\n";
                         }
                         $j=$i;
                         $this->salida.="</table>";
                         $this->salida.="<br>";
                         
                         
                    }  
               
               
               
               
               
               return $this->salida;
          }  
     }


    /**
    *
    *
    *
    *
    **/
    
	function Forma($datos=null,$datos_alerta,$riesgos_laborales)
	{ 
        var_dump($datos_alerta);
        $ThemeImages = GetThemePath() . "/images";
        $this->salida.= ThemeAbrirTablaSubModulo("INFORMACION INICIAL PACIENTE");
        $this->salida.="<form name='forma1' action='#' method='post'>";
        if(!empty($datos_alerta))
        {
            $this->salida.="<table class='modulo_table_list' align='center'>";
            for($i=0;$i<count($datos_alerta);$i++)
            {
                $this->salida.="    <tr class='modulo_list_claro'>";
                if($i==0)
                {
                    $this->salida.="        <td rowspan='".count($datos_alerta)."'>";        $this->salida.="            <label class='label_error'> ALERTAS </label>";
                    $this->salida.="        </td>";
                }
                
                $this->salida.="        <td class='modulo_table_list_title'>";
                $this->salida.="             DESCRIPCION";
                $this->salida.="        </td>";
                $this->salida.="        <td class='modulo_list_claro'>";
                $this->salida.="             ".$datos_alerta[$i]['descripcion']." ";
                $this->salida.="        </td>";
                $this->salida.="        <td class='modulo_table_list_title'>";
                $this->salida.="             OBSERVACION";
                $this->salida.="        </td>";
                $this->salida.="        <td class='modulo_list_claro'>";
                $this->salida.="             &nbsp;".$datos_alerta[$i]['observacion']." ";
                $this->salida.="        </td>";

            }
            $this->salida.="    </tr>";
            $this->salida.="</table>";
        }
        $this->salida.="<br>";
        //if(!empty($riesgos_laborales))
        //{
            $this->salida.="<table width='80%' class='modulo_table_list' align='center'>";
           // for($i=0;$i<count($datos_alerta);$i++)
            //{
                $this->salida.="    <tr class='modulo_list_claro'>";
                $this->salida.="        <td COLSPAN='2' class='modulo_table_list_title'>";
                $this->salida.="         RIESGOS LABORALES";
                $this->salida.="        </td>";
                $this->salida.="    </tr>";
                $this->salida.="    <tr class='modulo_list_claro'>";
                $this->salida.="        <td class='modulo_table_list_title'>";
                $this->salida.="             OCUPACION";
                $this->salida.="        </td>";
                $this->salida.="        <td class='modulo_table_list_title'>";
                $this->salida.="             LUGAR DE TRABAJO";
                $this->salida.="        </td>";
                $this->salida.="    </tr>";
                $this->salida.="    <tr class='modulo_list_claro'>";
                $this->salida.="        <td class='modulo_list_claro'>";
                $this->salida.="             MARRANERITO".$datos_alerta[$i]['descripcion']." ";
                $this->salida.="        </td>";
                $this->salida.="        <td class='modulo_list_claro'>";
                $this->salida.="             MARRANERITO1111";
                $this->salida.="        </td>";
                $this->salida.="     </tr>";

            //}
            $this->salida.="    </tr>";
            $this->salida.="</table>";
        //}

        $this->salida.="<br>";
        //if(!empty($riesgos_laborales))
        //{
            $this->salida.="<table width='100%' class='modulo_table_list' align='center'>";
           // for($i=0;$i<count($datos_alerta);$i++)
            //{
                $this->salida.="    <tr class='modulo_list_claro'>";
                $this->salida.="        <td COLSPAN='3' class='modulo_table_list_title'>";
                $this->salida.="          INCAPACIDADES EN LOS ULTIMOS 12 MESES";
                $this->salida.="        </td>";
                $this->salida.="    </tr>";
                $this->salida.="    <tr class='modulo_list_claro'>";
                $this->salida.="        <td width='10%' class='modulo_table_list_title'>";
                $this->salida.="             FECHA";
                $this->salida.="        </td>";
                $this->salida.="        <td width='75%' class='modulo_table_list_title'>";
                $this->salida.="             DIAGNOSTICO";
                $this->salida.="        </td>";
                $this->salida.="        <td width='15%' class='modulo_table_list_title'>";
                $this->salida.="             NUMERO DE DIAS";
                $this->salida.="        </td>";
                $this->salida.="    </tr>";
                $this->salida.="    <tr class='modulo_list_claro'>";
                $this->salida.="        <td align='center' class='modulo_list_claro'>";
                $this->salida.="             2006-05-17";
                $this->salida.="        </td>";
                $this->salida.="        <td class='modulo_list_claro'>";
                $this->salida.="            SE CAYO DE UN DECIMO PISO, Y ASI SIGUE TOTALMENTE VIVA";
                $this->salida.="        </td>";
                $this->salida.="        <td align='center' class='modulo_list_claro'>";
                $this->salida.="            15";
                $this->salida.="        </td>";
                $this->salida.="     </tr>";

            //}
            $this->salida.="    </tr>";
            $this->salida.="</table>";
        //}
        $this->salida.="</form>";
        $this->salida .= ThemeCerrarTablaSubModulo();

        //////////////JAVASCRIPT////////////////////////////////////////////////
        $this->salida.="<script language=\"javaScript\">\n";
        $this->salida.="</script>\n";
        ///////////////////////////////////////////////////////////////////////////////////
        return $this->salida;
	}




         /**
     * Historia clinica de paciente
     */
     function HistoriaClinicaPaciente($historia,$evolucion,$modulo)
     {
          if (!empty($historia))
          {
               $salida .= "<table align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table\">\n";
               $salida .= "<tr>\n";
               $salida .= "<td align=\"center\"><B>HISTORIAL CRONOLOGICO DEL PACIENTE</B>";
               $salida .= "</td>\n";
               $salida .= "</tr>\n";
               $salida .= "</table>\n";
          }
          else
          {
               $salida .= "<table align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table\">\n";
               $salida .= "<tr>\n";
               $salida .= "<td align=\"center\"><B>EL PACIENTE AUN NO PRESENTA HISTORIAL CRONOLOGICO</B>";
               $salida .= "</td>\n";
               $salida .= "</tr>\n";
               $salida .= "</table>\n";
          }
          foreach($historia as $k=>$v)
          {
               $salida .= "<table align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table\">\n";
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
                              $salida .= "<table align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table\">\n";
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
                              $salida .= "<table align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table\">\n";
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
                                        $salida .= "<table align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table\">\n";
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
                                        $salida .= "<table align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table\">\n";
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
                                        $salida .= "<table align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table\">\n";
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
                                        $salida .= "<table align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table\">\n";
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
          
          return $salida;
     }//fin HistoriaClinicaPaciente
    /**
    *   Funcion que sirve para obtener los beneficiarios de un cotizante
    *   @param string $cotizante
    *   @param string $afiliados
    *   @return array $salida vector con todos datos de los beneficiarios encontrados en la busqueda
    **/
    function  BuscarBeneficiarios($cotizante,$afiliados)
    {
        $usuario=UserGetUID();
        $path = SessionGetVar("rutaImagenes");

   
       //var_dump($cotizante);
         if(!empty($cotizante))
         {
            //var_dump($cotizante);
                    $salida .= "               <div align='center' id=\"Cotizante\" style=\"width:100%;  z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick='' >";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"100%\" align=\"center\" cellspacing='0' >\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                    $salida .= "                     <td width='97%' align=\"center\">\n";
                    $salida .= "                       <a title='NOMBRE AFILIADO'>";
                    $salida .= "                        ".$cotizante['nombre_afiliado']."  -  (COTIZANTE)";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='3%' align=\"center\" id='BotonCotizante'>\n";
                    $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Cotizante','1','".$path."','BotonCotizante');\">";
                    $salida .= "                          <sub><img src=\"".$path."/images/arriba.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"15%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"35%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"15%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"35%\"align=\"LEFT\">\n";
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

                    if(empty($afiliados))
                    {
                        $salida .= "                   <br>\n";
                        $salida .= "                 <table width=\"100%\" align=\"center\" cellspacing='0' >\n";
                        $salida .= "                   <tr >\n";
                        $salida .= "                     <td width='100%' align=\"center\">\n";
                        $salida .= "                       <label class='label_error'> ESTE AFILIADO NO TIENE BENEFICIARIOS </label>";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                        $salida .= "                   </table>\n";
                        $salida .= "                   <br>\n";
                    }


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
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"100%\" align=\"center\" cellspacing='0' >\n";
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
                    $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"15%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"35%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"15%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"35%\"align=\"LEFT\">\n";
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

// $salida .= "<script>\n";
// $salida.= "function biger(lyr,vlr,path,td)";
// $salida.= "{";
// $salida.= "   if(vlr==0)\n";
//  $salida.= "  {";
//  $salida.= "       document.getElementById(lyr).style.height='auto';\n";
//  $salida.= "       cad= \"<a title='DESPLEGAR INFORMACION' href='javascript:biger('\"+lyr+\"',1,'\"+path+\"','\"+td+\"');'  <sub><img src='\"+path+\"/images/arriba.png' border='0' width='17' height='17'></sub></a>\"\n;";
//  $salida.= "       document.getElementById(td).innerHTML=cad;";
//  $salida.= "   }";
//  //$salida.= "   if(vlr==1)";
//  //$salida.= "   {";
//  //$salida.= "       document.getElementById(lyr).style.height='20px';\n";
//  //$salida.= "       cad= \"<a title='DESPLEGAR INFORMACION' href='javascript:biger(lyr,0,path,td);' <sub><img src= '\"+path+\"'/images/abajo.png' border='0' width='17' height='17'></sub></a>\"\n;";
//  //$salida.= "       document.getElementById(td).innerHTML=cad;\n";
//  //$salida.= "   }\n";
// $salida.= "}\n";
// $salida .= "</script>\n";
return  $salida;
    }




     /**
    *   Funcion que sirve para obtener los beneficiarios de un cotizante
    *   @param string $cotizante
    *   @param string $afiliados
    *   @return array $salida vector con todos datos de los beneficiarios encontrados en la busqueda
    **/
    function  BuscarBeneficiarios1($cotizante,$afiliados,$tipo_id_ben,$id_ben)
    {
        $usuario=UserGetUID();
        $path = SessionGetVar("rutaImagenes");

   
       //var_dump($cotizante);
         if(!empty($cotizante))
         {
            //var_dump($cotizante);
                    $salida .= "               <div align='center' id=\"Cotizante\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick='' >";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"100%\" align=\"center\" cellspacing='0' >\n";
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
                    $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"15%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"35%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"15%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"35%\"align=\"LEFT\">\n";
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

                    if(empty($afiliados))
                    {
                        $salida .= "                   <br>\n";
                        $salida .= "                 <table width=\"100%\" align=\"center\" cellspacing='0' >\n";
                        $salida .= "                   <tr >\n";
                        $salida .= "                     <td width='100%' align=\"center\">\n";
                        $salida .= "                       <label class='label_error'> ESTE AFILIADO NO TIENE BENEFICIARIOS </label>";
                        $salida .= "                     </td>\n";
                        $salida .= "                   </tr>\n";
                        $salida .= "                   </table>\n";
                        $salida .= "                   <br>\n";
                    }


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

                    
                    if($tipo_id_ben==$valor1['afiliado_tipo_id'] && $id_ben==$valor1['afiliado_id'])
                    {
                        $salida .= "               <div align='center' id=\"Benef".$i."\" style=\"width:100%; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick=''>";
                        $salida .= "                 <table class=\"modulo_table_list\" width=\"100%\" align=\"center\" cellspacing='0' >\n";
                        $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                        $salida .= "                     <td width='97%' align=\"center\">\n";
                        $salida .= "                       <a title='NOMBRE AFILIADO'>";
                        $salida .= "                        ".$valor1['primer_nombre']." ".$valor1['segundo_nombre']." ".$valor1['primer_apellido']." ".$valor1['segundo_apellido']." -  (BENEFICIARIO)";
                        $salida .= "                       </a>";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td width='3%' align=\"center\" id='".$td."'>\n";
                        $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Benef".$i."','1','".$path."','".$td."');\">";
                        $salida .= "                          <sub><img src=\"".$path."/images/arriba.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";

                    }
                    else
                    {
                        $salida .= "               <div align='center' id=\"Benef".$i."\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick=''>";
                        $salida .= "                 <table class=\"modulo_table_list\" width=\"100%\" align=\"center\" cellspacing='0' >\n";
                        $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                        $salida .= "                     <td width='97%' align=\"center\">\n";
                        $salida .= "                       <a title='NOMBRE AFILIADO'>";
                        $salida .= "                        ".$valor1['primer_nombre']." ".$valor1['segundo_nombre']." ".$valor1['primer_apellido']." ".$valor1['segundo_apellido']." -  (BENEFICIARIO)";
                        $salida .= "                       </a>";
                        $salida .= "                     </td>\n";
                        $salida .= "                     <td width='3%' align=\"center\" id='".$td."'>\n";
                        $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Benef".$i."','0','".$path."','".$td."');\">";
                        $salida .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";

                    }


                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"15%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"35%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"15%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"35%\"align=\"LEFT\">\n";
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
        return  $salida;
    }
    /**
    *
    */
    function FormaDiagnosticosCronicos($datos)
    {
      $html = "";
      if(!empty($datos))
      {
        $html .= "<table width=\"100%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <fieldset class=\"filedset\">\n";
        $html .= "        <legend class=\"normal_10AN\">DIAGNOSTICOS CRONICOS</legend>\n";
        $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "          <tr class=\"formulacion_table_list\">\n";
        $html .= "            <td width=\"5%\">COD</td>\n";
        $html .= "            <td width=\"45%\">DESCRIPCION</td>\n";
        $html .= "            <td width=\"10%\">FECHA</td>\n";
        $html .= "            <td width=\"40%\">OBSERVACION</td>\n";
        $html .= "          </tr>\n";
        
        $est = "modulo_list_claro";
        foreach($datos as $key => $dtl)
        {
          ($est == "modulo_list_claro")? $est = "modulo_list_oscuro": $est = "modulo_list_claro";
          
          $html .= "          <tr class=\"".$est."\">\n";
          $html .= "            <td >".$key."</td>\n";
          $html .= "            <td align=\"justify\">".$dtl['diagnostico_nombre']."</td>\n";
          $html .= "            <td align=\"center\">".$dtl['fecha_registro']."</td>\n";
          $html .= "            <td >".$dtl['descripcion']."</td>\n";
          $html .= "          </tr>\n";
        }
        $html .= "        </table>\n";
        $html .= "      </fieldsed>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
      }
      else
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">\n";
        $html .= "    NO SE HAN MARCADO DIAGNOSTICOS COMO CRONICOS PARA EL PACIENTE\n";
        $html .= "  </label>\n";
        $html .= "</center>\n";
      }
      return $html;
    }
    /**
    *
    */
    function FormaProgramasPyP_incapacidades($datos,$incapacidades)
    {
      $html = "";
      
      $html .= "<table width=\"100%\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";      
      if(!empty($incapacidades))
      {
        $html .= "      <fieldset class=\"filedset\">\n";
        $html .= "        <legend class=\"normal_10AN\">INCAPACIDADES</legend>\n";
        $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "          <tr>\n";
        $html .= "            <td width=\"35%\" class=\"formulacion_table_list\" align=\left\>CANTIDAD INCAPACIDADES</td>\n";
        $html .= "            <td width=\"15%\">".$incapacidades['cantidad_incapacidades']."</td>\n";
        $html .= "            <td width=\"35%\" class=\"formulacion_table_list\" align=\left\>DIAS DE INCAPACIDAD EN LOS ULTIMOS 12 MESES</td>\n";
        $html .= "            <td width=\"15%\">".$incapacidades['dias_incapacidad']."</td>\n";
        $html .= "          </tr>\n";
        $html .= "        </table>\n";
        $html .= "      </fieldsed><br>\n";
      }

      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";      
      if(!empty($datos))
      {
        $html .= "      <fieldset class=\"filedset\">\n";
        $html .= "        <legend class=\"normal_10AN\">PROGRAMAS PYP A LOS CUALES ESTA INCRIPTO</legend>\n";
        $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "          <tr class=\"formulacion_table_list\">\n";
        $html .= "            <td width=\"45%\">PROGRAMA</td>\n";
        $html .= "            <td width=\"10%\">FECHA INSCRIPCION</td>\n";
        $html .= "          </tr>\n";
        
        $est = "modulo_list_claro";
        foreach($datos as $key => $dtl)
        {
          ($est == "modulo_list_claro")? $est = "modulo_list_oscuro": $est = "modulo_list_claro";
          
          $html .= "          <tr class=\"".$est."\">\n";
          $html .= "            <td align=\"justify\">".$dtl['descripcion']."</td>\n";
          $html .= "            <td align=\"center\">".$dtl['fecha_inscripcion']."</td>\n";
          $html .= "          </tr>\n";
        }
        $html .= "        </table>\n";
        $html .= "      </fieldsed>\n";
      }
      else
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">\n";
        $html .= "    ACTUALMENTE EL PACIENTE NO SE ENCUENTRA INSCRIPTO A NINGUN PROGRAMA PYP\n";
        $html .= "  </label>\n";
        $html .= "</center>\n";
      }
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      return $html;
    }
    /**
    *
    */
    function FormaCiclos($datos,$indiv)
    { 
      $html  = "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" >\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td class=\"formulacion_table_list\">CICLO VITAL</td>\n";
      $html .= "  </tr>\n";
      foreach($indiv as $k => $dtl)
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <fieldset class=\"fieldset\">\n";
        $html .= "        <legend class=\"normal_10AN\">".$k."</legend>\n";
        $html .= "        <table width=\"100%\" align=\"center\" class=\"label\">\n";
        $html .= "          <tr>\n";
        $html .= "            <td class=\"normal_10\">\n";
        foreach($dtl as $k1 => $detalle)
        {
          $html .= "              ".$detalle['descripcion']."\n";
        }
        $html .= "            </td>\n";
        $html .= "          </tr>\n";
        $html .= "        </table>\n";
        $html .= "      </fieldset>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      
      $est = "modulo_list_claro";
      foreach($datos as $k => $dtl)
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <fieldset class=\"fieldset\">\n";
        $html .= "        <legend class=\"normal_10AN\">".$k."</legend>\n";
        $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "          <tr class=\"formulacion_table_list\">\n";
        $html .= "            <td width=\"80%\">DESCRIPCION</td>\n";
        $html .= "            <td>FECHA REGISTRO</td>\n";
        $html .= "          </tr>\n";
        foreach($dtl as $k1 => $detalle)
        {        
          ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
          $html .= "          <tr class=\"".$est."\">\n";
          $html .= "            <td >".$detalle['descripcion']."</td>\n";
          $html .= "            <td align=\"center\">".$detalle['fecha_registro']."</td>\n";
          $html .= "          </tr>\n";
        }
        $html .= "        </table>\n";
        $html .= "      </fieldset>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
      return $html;
    }
    /**
    *
    */
    function FormaMedicamentosFormuladosHTML($medicamentos)
    { 
      $html  = "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td colspan=\"8\" align=\"center\">ANTECEDENTES FARMACOLOGICOS</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "    <td width='25%'>MEDICAMENTO</td>\n";
      $html .= "    <td width='7%' >INICIO</td>\n";
      $html .= "    <td width='7%' >FINAL</td>\n";
      $html .= "    <td width='5%' >FRM</td>\n";
      $html .= "    <td width='16%'>DOSIS</td>\n";
      $html .= "    <td width='5%' >PRM</td>\n";
      $html .= "    <td width='15%'>PERIORICIDAD</td>\n";
      $html .= "    <td width='15%'>TIEMPO TOTAL</td>\n";
      $html .= "  </tr>\n";
      
      $est = "modulo_list_claro";
      foreach($medicamentos as $k => $dtl)
      {
        ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
        $html .= "  <tr class=\"".$est."\">\n";
        $html .= "    <td  align=\"left\">\n";
        if(empty($dtl['descripcion']))
          $html .="      ".$dtl['codigo_medicamento']."";
        else
          $html .="      ".$dtl['descripcion']."";
        
        $html .= "    </td>\n";
        $html .= "    <td  align=\"center\">\n";
        $html .="      ".$dtl['fecha_registro']."";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">\n";
        if(empty($dtl['fecha_finalizacion']))
          $html .="      ACTIVO";
        else
          $html .="      ".$dtl['fecha_finalizacion']."";
                        
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">\n";
        if(!empty($dtl['nombre']) &&  $dtl['sw_formulado'])
          $html .= "      SI\n";
        else
          $html .= "      NO\n";
                
        $html .= "    </td>\n";
        $html .= "    <td  align=\"left\">\n";
        $html .= "      ".$dtl['dosis']." ".$dtl['unidad_dosificacion']." ".$dtl['frecuencia'].""; //  dosis   unidad_dosificacion frecuencia
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">\n";
        if($dtl['sw_permanente']=='1')
          $html .= "      SI\n";
        elseif($dtl['sw_permanente']=='0')
          $html .= "      NO\n";
        
        $html .= "    </td>\n";
        $html .= "    <td align=\"left\">\n";
        $html .= "      ".$dtl['perioricidad_entrega']."";
        $html .= "    </td>\n";
        $html .= "    <td align=\"left\">\n";
        $html .= "      ".$dtl['tiempo_total']."";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "  </table>";
      return $html;
    }
  }
?>