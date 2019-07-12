<?php
/** 
    * $Id: signos_HTML.class.php,v 1.2 2007/10/12 14:40:56 jgomez Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS-FI
    * 
    * $Revision: 1.2 $ 
    * 
    * @autor J gomez
    */
class VistaAF_HTML
{  
     function VistaAF_HTML($objeto=null)
     {
          $this->obj=$objeto;
          return true;
     }

	function Forma($datos=null,$med_usu,$accion1)
	{ 
        $path = SessionGetVar("rutaImagenes");
        SessionDelVar("datos_usu");
        $datos_usu['tipo_id_paciente']=$datos->datosPaciente['tipo_id_paciente'];
        $datos_usu['paciente_id']=$datos->datosPaciente['paciente_id'];
        $datos_usu['evolucion_id']=$datos->datosEvolucion['evolucion_id'];
        SessionSetVar("datos_usu",$datos_usu);
        $RUTA = $_ROOT ."classes/calendariopropio/Calendario.php?forma=up_med&campo=fecha_finalizacion&separador=-";
        $this->salida .='<script language="javascript">'."\n".'function LlamarCalendariofecha_finalizacion()'."\n"."{"."\n"."window.open('".$RUTA."','CALENDARIO_SIIS','width=450,height=250,resizable=no,status=no,scrollbars=yes');"."\n".'}'."\n".'</script>'."\n";
        $this->salida .= "<script language=\"javaScript\">\n";
        $this->salida .= "   var contenedor1=''\n";
        $this->salida .= "   var titulo1=''\n";
        $this->salida .= "   var hiZ = 2;\n";
        $this->salida .= "   var DatosFactor = new Array();\n";
        $this->salida .= "   var EnvioFactor = new Array();\n";
        $this->salida .= "   function Iniciar(tit)\n";
        $this->salida .= "   {\n";
        $this->salida .= "       contenedor1 = 'ContenedorMed';\n";
        $this->salida .= "       titulo1 = 'tituloMed';\n";
        $this->salida .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $this->salida.= "        Capa = xGetElementById(contenedor1);\n";
        $this->salida .= "       xResizeTo(Capa, 500, 250);\n";
        $this->salida .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
        $this->salida .= "       ele = xGetElementById(titulo1);\n";
        $this->salida .= "       xResizeTo(ele, 480, 20);\n";
        $this->salida .= "       xMoveTo(ele, 0, 0);\n";
        $this->salida .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $this->salida .= "       ele = xGetElementById('cerrarMed');\n";
        $this->salida .= "       xResizeTo(ele, 20, 20);\n";
        $this->salida .= "       xMoveTo(ele, 480, 0);\n";
        $this->salida .= "   }\n";
        
	   $this->salida .= "   function IniciarRef(tit)\n";
        $this->salida .= "   {\n";
        $this->salida .= "       contenedor1 = 'ContenedorRefMed';\n";
        $this->salida .= "       titulo1 = 'tituloRefMed';\n";
        $this->salida .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $this->salida.= "        Capa = xGetElementById(contenedor1);\n";
        $this->salida .= "       xResizeTo(Capa, 500, 250);\n";
        $this->salida .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
        $this->salida .= "       ele = xGetElementById(titulo1);\n";
        $this->salida .= "       xResizeTo(ele, 480, 20);\n";
        $this->salida .= "       xMoveTo(ele, 0, 0);\n";
        $this->salida .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $this->salida .= "       ele = xGetElementById('cerrarRefMed');\n";
        $this->salida .= "       xResizeTo(ele, 20, 20);\n";
        $this->salida .= "       xMoveTo(ele, 480, 0);\n";
        $this->salida .= "   }\n";

        $this->salida.= "</script>\n";
        $this->salida .= "<script language=\"javaScript\">\n";
        $this->salida .= "   function myOnDragStart(ele, mx, my)\n";
        $this->salida .= "   {\n";
        $this->salida .= "     window.status = '';\n";
        $this->salida .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $this->salida .= "     else xZIndex(ele, hiZ++);\n";
        $this->salida .= "     ele.myTotalMX = 0;\n";
        $this->salida .= "     ele.myTotalMY = 0;\n";
        $this->salida .= "   }\n";
        $this->salida .= "   function myOnDrag(ele, mdx, mdy)\n";
        $this->salida .= "   {\n";
        $this->salida .= "     if (ele.id == titulo1) {\n";
        $this->salida .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
        $this->salida .= "     }\n";
        $this->salida .= "     else {\n";
        $this->salida .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $this->salida .= "     }  \n";
        $this->salida .= "     ele.myTotalMX += mdx;\n";
        $this->salida .= "     ele.myTotalMY += mdy;\n";
        $this->salida .= "   }\n";
        $this->salida .= "   function myOnDragEnd(ele, mx, my)\n";
        $this->salida .= "   {\n";
        $this->salida .= "   }\n";
        $this->salida .= "  function MostrarCapa(Elemento)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    capita = xGetElementById(Elemento);\n";
        $this->salida .= "    capita.style.display = \"\";\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function Cerrar(Elemento)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    capita = xGetElementById(Elemento);\n";
        $this->salida .= "    capita.style.display = \"none\";\n";
        $this->salida .= "    document.getElementById('errorMed').innerHTML='';\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function CerrarRef(Elemento)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    capita = xGetElementById(Elemento);\n";
        $this->salida .= "    capita.style.display = \"none\";\n";
        $this->salida .= "    document.getElementById('errorRefMed').innerHTML='';\n";
        $this->salida .= "  }\n";
        $this->salida .= "</script>\n";
    
        /**
        *Ventana emergente 3 aqui es cuando se modifica una cuenta. 
        ***/
        $this->salida.="<div id='ContenedorMed' class='d2Container' style=\"display:none\">";
        $this->salida .= "    <div id='tituloMed' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarMed' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMed');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorMed' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoMed'>\n";
        $this->salida .= "    </div>\n";     
        $this->salida.="</div>";
       
        /**
        * Ventana para la reformulacion de medicamentos
        **/ 
	   $this->salida.="<div id='ContenedorRefMed' class='d2Container' style=\"display:none\">";
        $this->salida .= "    <div id='tituloRefMed' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarRefMed' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:CerrarRef('ContenedorRefMed');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorRefMed' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoRefMed'>\n";
        $this->salida .= "    </div>\n";     
        $this->salida.="</div>";

        $this->salida .= ThemeAbrirTabla("ANTECEDENTES FARMACOLOGICOS");
         ////link para ir a adicionar medicamentos
        //$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Getforma'));
//         $this->salida .= "            <form name=\"menu_docu\" action=\"".$accion1."\" method=\"post\">\n";
        $this->salida .= "                 <table width=\"100%\" align=\"center\" >\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td align=\"center\" class='label_mark'>\n";
        $this->salida .= "                       <a href=\"".$accion1."\" class=\"label_error\">ADICIONAR MEDICAMENTOS NO FORMULADOS</a>\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                 </table>\n";
        $this->salida .= "                 <br>\n";
        $this->salida .= "                    <div id=\"mensaje\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
        $this->salida .= "                    </div>\n";
        $this->salida .= "                    <div id=\"formulacion\">\n";
        if(!empty($med_usu))
        {
            $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                   <tr>\n";
            $this->salida .= "                     <td width='25%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='NOMBRE DEL MEDICAMENTO'><label >NOMBRE DEL MEDICAMENTO</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='7%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='FECHA DE INICIO DEL TRATAMIENTO'><label >INICIO</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='7%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='FECHA DE FINALIZACION DEL TRATAMIENTO'><label >FINAL</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='5%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='MEDICAMENTO FORMULADO'><label >FRM</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='16%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='DOSIS FORMULADA'><label >DOSIS</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='5%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='FORMULACION PERMANANTE'><label >FP</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='15%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='PERIORICIDAD CON LA CUAL SE DEBE ENTREGAR AL PACIENTE EL MEDICAMENTO'><label >PERIORICIDAD</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='15%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='TIEMPO TOTAL DEL TRATAMIENTO'><label >TIEMPO TOTAL</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='5%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='MODIFICAR DATOS'><label >MOD</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='5%' class=\"formulacion_table_list\" align=\"center\">\n";
            $this->salida .= "                       <a title='REFORMULAR'><label >REFORMULAR</label></a>";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                   </tr>\n";
            for($i=0;$i<count($med_usu);$i++)
            {
                $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
                $this->salida .= "                     <td  align=\"left\">\n";
                if(empty($med_usu[$i]['descripcion']))
                {
                    $this->salida .="                       ".$med_usu[$i]['codigo_medicamento']."";
                }
                else
                {
                    $this->salida .="                       ".$med_usu[$i]['descripcion']."";
                }
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td  align=\"left\">\n";
                $this->salida .="                       ".$med_usu[$i]['fecha_registro']."";
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td align=\"left\">\n";
                if(empty($med_usu[$i]['fecha_finalizacion']))
                {
                    $this->salida .="                       ACTIVO";
                }
                else
                {
                    $this->salida .="                       ".$med_usu[$i]['fecha_finalizacion']."";
                }
                
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td align=\"center\">\n";
                 if(!EMPTY($med_usu[$i]['nombre']) &&  $med_usu[$i]['sw_formulado'])
                 {
                     $this->salida .="                   <a title='MEDICAMENTO FORMULADO POR ".$med_usu[$i]['nombre']."' href='#'>";
                     $this->salida .= "                    <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
                     $this->salida .= "                  <a>\n";
                 }
                 else
                 {
                     $this->salida .="                   <a title='MEDICAMENTO NO FORMULADO'>";
                     $this->salida .= "                    <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
                     $this->salida .= "                  <a>\n";
                 }
                
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td  align=\"left\">\n";
                $this->salida .="                       ".$med_usu[$i]['dosis']." ".$med_usu[$i]['unidad_dosificacion']." ".$med_usu[$i]['frecuencia'].""; //  dosis   unidad_dosificacion frecuencia
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td align=\"center\">\n";
                if($med_usu[$i]['sw_permanente']=='1')
                {
                    $this->salida .="                   <a title='MEDICAMENTO FORMULADO PERMANENTEMENTE' href='#'>";
                    $this->salida .= "                    <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
                    $this->salida .= "                  <a>\n";
                }
                elseif($med_usu[$i]['sw_permanente']=='0')
                {
                    $this->salida .="                   <a title='MEDICAMENTO NO FORMULADO PERMANENTEMENTE'>";
                    $this->salida .= "                    <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
                    $this->salida .= "                  <a>\n";
                }
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td align=\"left\">\n";
                $this->salida .="                   ".$med_usu[$i]['perioricidad_entrega']."";
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td align=\"left\">\n";
                $this->salida .="                   ".$med_usu[$i]['tiempo_total']."";
                $this->salida .= "                     </td>\n";
                
                if($med_usu[$i]['medico_id']==UserGetUID())
                {                                                                                                           //tipo_id_paciente    paciente_id     codigo_medicamento
                    $javadx = "javascript:MostrarCapa('ContenedorMed');AsignarMedicamentoUp('".$med_usu[$i]['tipo_id_paciente']."','".$med_usu[$i]['paciente_id']."','".$med_usu[$i]['codigo_medicamento']."','".$med_usu[$i]['evolucion_id']."');Iniciar('ASIGNAR MEDICAMENTO');";
                    $this->salida .= "                      <td align=\"center\" onclick=\"".$javadx."\">\n";
                    $this->salida .="                   <a title='MODIFICAR DATOS'>";
                    $this->salida .= "                    <sub><img src=\"".$path."/images/editar.gif\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
                    $this->salida .= "                  <a>\n";
                }
                else
                {
                    $this->salida .= "                     <td align=\"center\">\n";
                }
                $this->salida .= "                     </td>\n";
                    
               //Modificado Tizziano Perea
               //print_r($datos_usu);
               //print_r($med_usu[$i]);
               $fechatoday = date("Y-m-d");
               if ($med_usu[$i]['fecha_finalizacion'] > $fechatoday AND $med_usu[$i]['evolucion_id'] != $datos_usu['evolucion_id'])
               {
                    $swRef = true;
                    $javadx = "javascript:MostrarCapa('ContenedorRefMed');ReformularMedicamentoUp('".$med_usu[$i]['tipo_id_paciente']."','".$med_usu[$i]['paciente_id']."','".$med_usu[$i]['codigo_medicamento']."','".$med_usu[$i]['evolucion_id']."','".$swRef."');IniciarRef('REFORMULAR MEDICAMENTO');";
                    $this->salida .= "                      <td align=\"center\" onclick=\"".$javadx."\">\n";
                    $this->salida .="                   <a title='REFORMULAR MEDICAMENTO'>";
                    $this->salida .= "                    <sub><img src=\"".$path."/images/producto.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
                    $this->salida .= "                  <a>\n";
                    $this->salida .= "                     </td>\n";
               }
               else
               {
                    $this->salida .= "                      <td align=\"center\">\n";/*
                    $this->salida .="                   <a title='REFORMULAR MEDICAMENTO'>";
                    $this->salida .= "                    <sub><img src=\"".$path."/images/medicinqx.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
                    $this->salida .= "                  <a>\n";*/
                    $this->salida .= "                     </td>\n";
               }
               
               
               //Modificado Tizziano Perea

               $this->salida .= "                   </tr>\n";
               
               $this->salida .= "                   <tr>\n";
               $this->salida .= "                     <td width='25%' class=\"formulacion_table_list\" align=\"center\">\n";
               $this->salida .= "                       <a title='MEDICO QUE FORMULO'><label >PROFESIONAL QUE FORMULO</label></a>";
               $this->salida .= "                     </td>\n";
               $this->salida .= "                     <td colspan=\"3\" class=\"modulo_list_claro\" align=\"center\">".$med_usu[$i]['nombre']."\n";
               $this->salida .= "                     </td>\n";
               $this->salida .= "                     <td width='25%' class=\"formulacion_table_list\" align=\"center\">\n";
               $this->salida .= "                       <a title='FECHA DE FORMULACION DEL MEDICAMENTO'><label >FORMULACION</label></a>";
               $this->salida .= "                     </td>\n";
               $this->salida .= "                     <td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">".$med_usu[$i]['fecha_formulacion']."\n";
               $this->salida .= "                     </td>\n";
               $this->salida .= "                     <td width='25%' class=\"formulacion_table_list\" align=\"center\">\n";
               $this->salida .= "                       <a title='ULTIMO PROFESIONAL QUE ACTUALIZO EL ESTADO DEL MEDICAMENTO'><label >ACTUALIZO</label></a>";
               $this->salida .= "                     </td>\n";
               $this->salida .= "                     <td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">".$med_usu[$i]['medico_update']."\n";
               $this->salida .= "                     </td>\n";
               $this->salida .= "                   </tr>\n";
            }
          $this->salida .= "                   </table>";
        }
        $this->salida .= "          </div>\n";
        //$this->salida .= "             </form>";


        $this->salida .= ThemeCerrarTabla();
        return $this->salida;
	}



    function Forma_adicionar_medicamento_nf($this,$accion2)
    {
        
        global $_ROOT;
        SessionDelVar("datos_usu");
        $datos_usu['tipo_id_paciente']=$this->datosPaciente['tipo_id_paciente'];
        $datos_usu['paciente_id']=$this->datosPaciente['paciente_id'];
        $datos_usu['evolucion_id']=$this->datosEvolucion['evolucion_id'];
        SessionSetVar("datos_usu",$datos_usu);
        $RUTA = $_ROOT ."classes/calendariopropio/Calendario.php?forma=adicionar_med&campo=fecha_finalizacion&separador=-";
        $this->salida .='<script language="javascript">'."\n".'function LlamarCalendariofecha_finalizacion()'."\n"."{"."\n"."window.open('".$RUTA."','CALENDARIO_SIIS','width=450,height=250,resizable=no,status=no,scrollbars=yes');"."\n".'}'."\n".'</script>'."\n";
        $this->salida .= "<script language=\"javaScript\">\n";
        $this->salida .= "   var contenedor1=''\n";
        $this->salida .= "   var titulo1=''\n";
        $this->salida .= "   var hiZ = 2;\n";
        $this->salida .= "   var DatosFactor = new Array();\n";
        $this->salida .= "   var EnvioFactor = new Array();\n";
        $this->salida .= "   function Iniciar(tit)\n";
        $this->salida .= "   {\n";
        $this->salida .= "       contenedor1 = 'ContenedorMed';\n";
        $this->salida .= "       titulo1 = 'tituloMed';\n";
        $this->salida .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $this->salida.= "        Capa = xGetElementById(contenedor1);\n";
        $this->salida .= "       xResizeTo(Capa, 500, 250);\n";
        $this->salida .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
        $this->salida .= "       ele = xGetElementById(titulo1);\n";
        $this->salida .= "       xResizeTo(ele, 480, 20);\n";
        $this->salida .= "       xMoveTo(ele, 0, 0);\n";
        $this->salida .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $this->salida .= "       ele = xGetElementById('cerrarMed');\n";
        $this->salida .= "       xResizeTo(ele, 20, 20);\n";
        $this->salida .= "       xMoveTo(ele, 480, 0);\n";
        $this->salida .= "   }\n";
        $this->salida.= "</script>\n";
        $this->salida .= "<script language=\"javaScript\">\n";
        $this->salida .= "   function myOnDragStart(ele, mx, my)\n";
        $this->salida .= "   {\n";
        $this->salida .= "     window.status = '';\n";
        $this->salida .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $this->salida .= "     else xZIndex(ele, hiZ++);\n";
        $this->salida .= "     ele.myTotalMX = 0;\n";
        $this->salida .= "     ele.myTotalMY = 0;\n";
        $this->salida .= "   }\n";
        $this->salida .= "   function myOnDrag(ele, mdx, mdy)\n";
        $this->salida .= "   {\n";
        $this->salida .= "     if (ele.id == titulo1) {\n";
        $this->salida .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
        $this->salida .= "     }\n";
        $this->salida .= "     else {\n";
        $this->salida .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $this->salida .= "     }  \n";
        $this->salida .= "     ele.myTotalMX += mdx;\n";
        $this->salida .= "     ele.myTotalMY += mdy;\n";
        $this->salida .= "   }\n";
        $this->salida .= "   function myOnDragEnd(ele, mx, my)\n";
        $this->salida .= "   {\n";
        $this->salida .= "   }\n";
        $this->salida .= "  function MostrarCapa(Elemento)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    capita = xGetElementById(Elemento);\n";
        $this->salida .= "    capita.style.display = \"\";\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function Cerrar(Elemento)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    capita = xGetElementById(Elemento);\n";
        $this->salida .= "    capita.style.display = \"none\";\n";
        $this->salida .= "    document.getElementById('errorMed').innerHTML='';\n";
        $this->salida .= "  }\n";
        $this->salida .= "</script>\n";
    
        /**
        *Ventana emergente 3 aqui es cuando se modifica una cuenta. 
        ***/
        $this->salida.="<div id='ContenedorMed' class='d2Container' style=\"display:none\">";
        $this->salida .= "    <div id='tituloMed' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarMed' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMed');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorMed' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoMed'>\n";
        $this->salida .= "    </div>\n";     
        $this->salida.="</div>";
        $this->salida .= ThemeAbrirTabla("ANTECEDENTES FARMACOLOGICOS");
        $this->salida .= "            <form name=\"menu_docu\" action=\"#\" method=\"post\">\n";
        $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"center\" colspan='7'>\n";
        $this->salida .= "                          ADICION DE MEDICAMENTOS   -    BUSQUEDA AVANZADA";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td class=\"normal_10AN\"  align=\"center\" >\n";
        $this->salida .= "                      TIPO ";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td class=\"normal_10AN\"  align=\"center\" >\n";
        $this->salida .= "                         <select name=\"tipo\" id=\"tipo\" class=\"select\" disabled onchange=\"\">";
        $this->salida .= "                           <option value=\"0\">SELECCIONAR</option> \n";
        $this->salida .= "                           <option value=\"001\"selected>TODOS</option> \n";
        $this->salida .= "                           <option value=\"002\">FRECUENTES</option> \n";
        $this->salida .= "                         </select>";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td class=\"normal_10AN\"  align=\"center\" >\n";
        $this->salida .= "                      PRODUCTO ";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                        <input type=\"text\" class=\"input-text\" name=\"producto\" id=\"producto\" maxlength=\"50\" size\"50\" onkeydown=\"recogerTecla(event)\">";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td class=\"normal_10AN\"  align=\"center\" >\n";
        $this->salida .= "                          PRINCIPIO ACTIVO";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td  align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                          <input type=\"text\" class=\"input-text\" name=\"principio_act\" id=\"principio_act\" maxlength=\"50\" size\"50\" onkeydown=\"recogerTecla(event)\">";//onkeypress=\"return acceptNum(event)\"
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td  align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                         <input type=\"button\" class=\"input-submit\" value=\"BUSCAR\" onclick=\"Buscar_Med(document.getElementById('tipo').value,document.getElementById('producto').value,document.getElementById('principio_act').value,'0','1')\">\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        $this->salida .= "                   </table>";
        $this->salida .= "                   <br>";
        $this->salida .= "                    <div id=\"mensaje\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
        $this->salida .= "                    </div>\n";
        $this->salida .= "                 <div id=\"tabelos\">";
        $this->salida .= "                 </div>";
        $this->salida .= "                   </form>";
        $this->salida .= "                   <br>";
        $this->salida .= "<script language=\"javaScript\">
                            function mOvr(src,clrOver)
                            {
                                src.style.background = clrOver;
                            }
    
                            function mOut(src,clrIn) 
                            {
                            src.style.background = clrIn;
                            }
    
                            xajax_buscar_medicamento(document.getElementById('tipo').value,document.getElementById('producto').value,document.getElementById('principio_act').value,'0','1');
                        </script>";
        $this->salida .= " <form name=\"volver\" action=\"".$accion2."\" method=\"post\">\n";//".$this->action[0]."
        $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "       <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "       </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= " </form>\n";
            $this->salida .= ThemeCerrarTabla();
            return $this->salida;
    }
    /**
    *
    */
    function FormaMedicamentosFormuladosHTML($medicamentos)
    { 
      $html  = "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td colspan=\"8\">ANTECEDENTES FARMACOLOGICOS</td>\n";
      $html .= "  <tr>\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td width='25%'>MEDICAMENTO</td>\n";
      $html .= "    <td width='7%' >INICIO</td>\n";
      $html .= "    <td width='7%' >FINAL</td>\n";
      $html .= "    <td width='5%' >FORMULADO</td>\n";
      $html .= "    <td width='16%'>DOSIS</td>\n";
      $html .= "    <td width='5%' >PERMANENTE</td>\n";
      $html .= "    <td width='15%'>PERIORICIDAD</td>\n";
      $html .= "    <td width='15%'>TIEMPO TOTAL</td>\n";
      $html .= "  </tr>\n";
        
      foreach($medicamentos as $k => $dtl)
      {
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "    <td  align=\"left\">\n";
        if(empty($dtl['descripcion']))
          $html .="      ".$dtl['codigo_medicamento']."";
        else
          $html .="      ".$dtl['descripcion']."";
        
        $html .= "    </td>\n";
        $html .= "    <td  align=\"left\">\n";
        $html .="      ".$dtl['fecha_registro']."";
        $html .= "    </td>\n";
        $html .= "    <td align=\"left\">\n";
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
        $html .= "  ".$dtl['perioricidad_entrega']."";
        $html .= "    </td>\n";
        $html .= "    <td align=\"left\">\n";
        $html .= "  ".$dtl['tiempo_total']."";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "  </table>";
      return $html;
    }
    /**
    *
    */
    function FormaMedicamentosFormulados($medicamentos)
    { 
      $html  = "<table width=\"100%\" align=\"center\" cellspacing=\"0\" border=\"1\"  rules=\"all\">\n";
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td colspan=\"8\" align=\"center\">ANTECEDENTES FARMACOLOGICOS</td>\n";
      $html .= "  <tr>\n";
      $html .= "  <tr class=\"label\" align=\"center\">\n";
      $html .= "    <td width='25%'>MEDICAMENTO</td>\n";
      $html .= "    <td width='7%' >INICIO</td>\n";
      $html .= "    <td width='7%' >FINAL</td>\n";
      $html .= "    <td width='5%' >FORMULADO</td>\n";
      $html .= "    <td width='16%'>DOSIS</td>\n";
      $html .= "    <td width='5%' >PERMANENTE</td>\n";
      $html .= "    <td width='15%'>PERIORICIDAD</td>\n";
      $html .= "    <td width='15%'>TIEMPO TOTAL</td>\n";
      $html .= "  </tr>\n";
        
      foreach($medicamentos as $k => $dtl)
      {
        $html .= "  <tr class=\"normal_10\">\n";
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