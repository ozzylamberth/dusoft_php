<?php
/** 
    * $Id: hc_UV_CicloFamiliar_VistaCF_HTML.class.php,v 1.1 2008/09/03 18:50:27 hugo Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS-FI
    * 
    * $Revision: 1.1 $ 
    * 
    * @autor J gomez
    */
class VistaCF_HTML
{  
     function VistaCF_HTML($objeto=null)
     {
          $this->obj=$objeto;
          return true;
     }

     
//      function frmConsulta($consulta,$hs)
//      {
//           if(empty($hs[0]['fecha_registro']))
//           {
//                $this->salida.="<table  border=\"1\" width=\"100%\"  align=\"center\" >\n";
//                $this->salida .="<tr>\n";
//                $this->salida .=   "<td COLSPAN=3 align=\"center\">NO HAY RESULTADOS DE EXAMEN FISICO</td>\n";
//                $this->salida .= "</tr>\n";
//                $this->salida.="</table>\n";
//                return $this->salida;
//           }       
//           else
//           {    
//               
//                     $contadorcapas=0;
//                     $evolucion=$consulta[0]['evolucion_id'];
//                     $vectordecapas=Array();
//                     $sistemasexaminados=0;
//                     for($j=0;$j<count($consulta);$j++)
//                     { 
//                         if($evolucion!=$consulta[$j]['evolucion_id'])
//                          {
//                            $vectordecapas[$contadorcapas]=$sistemasexaminados;
//                            $contadorcapas++;
//                            $sistemasexaminados=1;
//                            $evolucion=$consulta[$j]['evolucion_id'];
//                          }
//                          else
//                          {
//                            $sistemasexaminados++;
//                          }
//                     } 
//                     if($j==count($consulta))
//                     {
//                        $vectordecapas[$contadorcapas]=$sistemasexaminados;
//                     }  
//                     
//                     
//                     
//                     $j=0;
//                     $W=50000;
//                     $this->salida.="<table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\" >\n";
//                     $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
//                     $this->salida .= "<td COLSPAN=3 align=\"center\">EXAMEN FISICO</td>\n";    
//                     $this->salida .= "</tr>\n";
//                     $this->salida .= "</tABLE>\n";
//                     $this->salida .= "<BR>\n";
//                     for($contadorcapas=0;$contadorcapas<count($vectordecapas);$contadorcapas++)
//                     { 
//                            $this->salida.="<table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\" >\n";
//                            $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
//                              $resto = substr ($hs[$contadorcapas]['fecha_registro'], 0, 10); 
//                              $this->salida .= "<td COLSPAN=2 align=\"left\" width=\"700\">PROFESIONAL:".$hs[$contadorcapas]['nombre']."</td>\n";
//                              $this->salida .= "<td COLSPAN=1 align=\"center\" width=\"700\">FECHA:".$resto."</td>\n";    
//                            $this->salida .= "</tr>\n";
//                            $this->salida.= "<tr>\n";
//                             $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"25%\">SISTEMA</td>\n";
//                             $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"15%\">ESTADO</td>\n";
//                             $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"60%\">OBSERVACIONES</td>\n";
//                            $this->salida.= "</tr>\n";
//                            $limitecapa=$vectordecapas[$contadorcapas];
//                            $limitecapa=$limitecapa+$j;                         
//                          for($i=$j;$i<$limitecapa;$i++)//historia_actual_osc.gif
//                          {   $zorro++;
//                               $this->salida .= "<tr>\n";
//                               $this->salida .= "<td align=\"center\" style='font-size:9pt;' class=\"hc_list_oscuro\" width=\"20%\">".$consulta[$i]['nombre']."</td>\n";
//                               if($consulta[$i]['sw_normal']=='A')
//                               { 
//                                 $this->salida .= "<td bgcolor=RED align=\"center\" width=\"15%\">ANORMAL</td>\n";
//                               }
//                               elseif($consulta[$i]['sw_normal']=='N')
//                               { 
//                                 $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"15%\">NORMAL</td>\n";
//                               }
//                               
//                               if($i==$j)
//                               { 
//                                    $this->salida .= "<td align=\"center\" rowspan='".$vectordecapas[$contadorcapas]."' class=\"hc_list_oscuro\" <TEXTAREA NAME=strg, ROWS=".$vectordecapas[$contadorcapas]." COLS=55 OnFocus=\"this.blur()\">".$hs[$contadorcapas]['hallazgo']."</TEXTAREA></td>\n";
//                               } 
//                               $this->salida .= "</tr>\n";
//                          }
//                          $j=$i;
//                          $this->salida.="</table>";
//                          $this->salida.="<br>";
//                          
//                          
//                     }  
//                
//                
//                
//                
//                
//                return $this->salida;
//           }  
//      }
//    
//      function frmHistoria($consulta,$hs)
//      {
//           if(empty($hs[0]['fecha_registro']))
//           {
//                $this->salida.="<table  border=\"1\" width=\"100%\"  align=\"center\" >\n";
//                $this->salida .="<tr>\n";
//                $this->salida .=   "<td COLSPAN=3 align=\"center\">NO HAY RESULTADOS DE EXAMEN FISICO</td>\n";
//                $this->salida .= "</tr>\n";
//                $this->salida.="</table>\n";
//                return $this->salida;
//           }       
//           else
//           {    
//               
//                     $contadorcapas=0;
//                     $evolucion=$consulta[0]['evolucion_id'];
//                     $vectordecapas=Array();
//                     $sistemasexaminados=0;
//                     for($j=0;$j<count($consulta);$j++)
//                     { 
//                         if($evolucion!=$consulta[$j]['evolucion_id'])
//                          {
//                            $vectordecapas[$contadorcapas]=$sistemasexaminados;
//                            $contadorcapas++;
//                            $sistemasexaminados=1;
//                            $evolucion=$consulta[$j]['evolucion_id'];
//                          }
//                          else
//                          {
//                            $sistemasexaminados++;
//                          }
//                     } 
//                     if($j==count($consulta))
//                     {
//                        $vectordecapas[$contadorcapas]=$sistemasexaminados;
//                     }  
//                     
//                     
//                     
//                     $j=0;
//                     $W=50000;
//                     $this->salida.="<table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\" >\n";
//                     $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
//                     $this->salida .= "<td COLSPAN=3 align=\"center\">EXAMEN FISICO</td>\n";    
//                     $this->salida .= "</tr>\n";
//                     $this->salida .= "</tABLE>\n";
//                     $this->salida .= "<BR>\n";
//                     for($contadorcapas=0;$contadorcapas<count($vectordecapas);$contadorcapas++)
//                     { 
//                            $this->salida.="<table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\" >\n";
//                            $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
//                            $resto = substr ($hs[$contadorcapas]['fecha_registro'], 0, 10); 
//                            $this->salida .= "<td COLSPAN=2 align=\"left\" width=\"700\">PROFESIONAL:".$hs[$contadorcapas]['nombre']."</td>\n";
//                            $this->salida .= "<td COLSPAN=1 align=\"center\" width=\"700\">FECHA:".$resto."</td>\n";    
//                            $this->salida .= "</tr>\n";
//                            $this->salida.= "<tr>\n";
//                            $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"25%\">SISTEMA</td>\n";
//                            $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"15%\">ESTADO</td>\n";
//                            $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"60%\">OBSERVACIONES</td>\n";
//                            $this->salida.= "</tr>\n";
//                            $limitecapa=$vectordecapas[$contadorcapas];
//                            $limitecapa=$limitecapa+$j;                         
//                          for($i=$j;$i<$limitecapa;$i++)//historia_actual_osc.gif
//                          {   $zorro++;
//                               $this->salida .= "<tr>\n";
//                               $this->salida .= "<td align=\"center\" style='font-size:9pt;' class=\"hc_list_oscuro\" width=\"20%\">".$consulta[$i]['nombre']."</td>\n";
//                               if($consulta[$i]['sw_normal']=='A')
//                               { 
//                                 $this->salida .= "<td bgcolor=RED align=\"center\" width=\"15%\">ANORMAL</td>\n";
//                               }
//                               elseif($consulta[$i]['sw_normal']=='N')
//                               { 
//                                 $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"15%\">NORMAL</td>\n";
//                               }
//                               
//                               if($i==$j)
//                               { 
//                                    $this->salida .= "<td align=\"center\" rowspan='".$vectordecapas[$contadorcapas]."' class=\"hc_list_oscuro\">".$hs[$contadorcapas]['hallazgo']."</td>\n";
//                               } 
//                               $this->salida .= "</tr>\n";
//                          }
//                          $j=$i;
//                          $this->salida.="</table>";
//                          $this->salida.="<br>";
//                          
//                          
//                     }  
//                
//                
//                
//                
//                
//                return $this->salida;
//           }  
//      }


	function Forma($datos=null,$CicloIndividual,$Lista_ciclos_familiares,$Lista_ciclos_familiares_seleccionados,$Lista_ciclos_familiares_observacion,$fr,$factores_riesgo)
	{ 
        $path = SessionGetVar("rutaImagenes");
        //var_dump($factores_riesgo);
        $contador=count($Lista_ciclos_familiares_seleccionados);
        $vector_ceros=array();
        for($j=0;$j<count($Lista_ciclos_familiares_seleccionados);$j++)
        {
            $vector_ceros[$i]=0;
        }

        
        $this->salida .= ThemeAbrirTabla("CICLOS VITAL INDIVIDUAL Y FAMILIAR DEL PACIENTE");
        $this->salida .= "            <form name=\"menu_docu\" action=\"#\" method=\"post\">\n";
        $this->salida .= "            <div id=\"mensaje\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
        $this->salida .= "            </div>\n";
        $this->salida .= "                 <table width=\"85%\" align=\"center\" >\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
        $this->salida .= "                       <fieldset class='fieldset1'>";
        $this->salida .= "                       <legend> CICLO VITAL INDIVIDUAL </legend>";
        $this->salida .= "                         <table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
        $this->salida .= "                           <tr>\n";
        $this->salida .= "                             <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                               <a title='CICLO VIOTAL INDIVIDUAL' class=\"Normal_10AN\" href=\"#\">\n";
        $this->salida .= "                                 <img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"> ".$CicloIndividual[0]['descripcion']." (".$CicloIndividual[0]['edad_min']." - ".$CicloIndividual[0]['edad_max'].") A&#209;OS\n";
        $this->salida .= "                               </a>\n";
        $this->salida .= "                             </td>\n";
        $this->salida .= "                           </tr>\n";
        $this->salida .= "                         </table>\n";
        $this->salida .= "                       </fieldset>";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td   align=\"center\" >\n";
        $this->salida .= "                       &nbsp;";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"center\">\n";
        $this->salida .= "                       <fieldset class='fieldset1'>";
        $this->salida .= "                       <legend> CICLO VITAL FAMILIAR </legend>";
        $this->salida .= "                         <table width=\"100%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
        for($i=0;$i<count($Lista_ciclos_familiares);$i++)
        {
            for($j=0;$j<count($vector_ceros);$j++)
            {
                if($vector_ceros[$j]==0)
                {
                    if($Lista_ciclos_familiares[$i]['ciclo_vital_familiar_id']==$Lista_ciclos_familiares_seleccionados[$j]['ciclo_vital_familiar_id'])
                    {
                        $vector_ceros[$j]=1; break;
                    }
                }
            }

            $this->salida .= "                           <tr>\n";
            $q=$i+1;
            if($j<count($vector_ceros))
            {
                $this->salida .= "                             <td id='ciclo".$q."' width='40%' class=\"modulo_list_claro\" align=\"left\">\n";
                $java = "javascript:SeleccionarCicloFamiliar('ciclo".$q."','".$datos->datosEvolucion['ingreso']."','".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."','".$Lista_ciclos_familiares[$i]['ciclo_vital_familiar_id']."','".$Lista_ciclos_familiares[$i]['descripcion']."');\"";
                $this->salida .= "                               <a title='SELECCIONAR ".$Lista_ciclos_familiares[$i]['descripcion']."' class=\"Normal_10AN\" href=\"".$java."\">\n";
                $this->salida .= "                                 <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"> ".$Lista_ciclos_familiares[$i]['descripcion']."</sub>\n";
                $this->salida .= "                               </a>\n";
                $this->salida .= "                             </td>\n";
            }
            elseif($j==count($vector_ceros))
            {
                $this->salida .= "                             <td id='ciclo".$q."' width='40%' class=\"modulo_list_claro\" align=\"left\">\n";
                $java = "javascript:SeleccionarCicloFamiliar('ciclo".$q."','".$datos->datosEvolucion['ingreso']."','".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."','".$Lista_ciclos_familiares[$i]['ciclo_vital_familiar_id']."','".$Lista_ciclos_familiares[$i]['descripcion']."');\"";
                $this->salida .= "                               <a title='SELECCIONAR ".$Lista_ciclos_familiares[$i]['descripcion']."' class=\"Normal_10AN\" href=\"".$java."\">\n";
                $this->salida .= "                                 <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"> ".$Lista_ciclos_familiares[$i]['descripcion']."</sub>\n";
                $this->salida .= "                               </a>\n";
                $this->salida .= "                             </td>\n";
            }


            if($i==0)
            {
                $this->salida .= "                             <td width='60%' rowspan='".(count($Lista_ciclos_familiares)-1)."' class=\"modulo_list_claro\" align=\"center\">\n";
                $this->salida .= "                               <a title='INSERTAR OBSERVACIONES CICLO DE VIDA FAMILIAR' class=\"Normal_10AN\">\n";
                $this->salida .= "                                 <sub><textarea ROWS=10 style=\"width:100%\" class='textarea' name='obs_cvf' id='obs_cvf'>".$Lista_ciclos_familiares_observacion[0]['observaciones']."</textarea></sub>\n";
                $this->salida .= "                               </a>\n";
                $this->salida .= "                             </td>\n";
            }
            if($i==(count($Lista_ciclos_familiares)-1))
            {
                $this->salida .= "                             <td class=\"modulo_list_claro\" align=\"center\">\n";
                $this->salida .= "                               <input type=\"button\" class=\"input-submit\" value=\"Insertar Observacion\" onclick=\"GuardarObsCvf('".$datos->datosEvolucion['ingreso']."','".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."',document.getElementById('obs_cvf').value);\">\n";
                $this->salida .= "                               </a>\n";
                $this->salida .= "                             </td>\n";
            }
            $this->salida .= "                           </tr>\n";


        }

        $this->salida .= "                         </table>\n";
        $this->salida .= "                       </fieldset>";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td   align=\"center\" class=\"\">\n";
        $this->salida .= "                       &nbsp;";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                     <td align=\"center\">\n";
        $this->salida .= "                       <fieldset class='fieldset1'>";
        $this->salida .= "                       <legend> FACTORES DE RIESGO </legend>";
        $this->salida .= "                         <table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
        
    for($i=0;$i<count($factores_riesgo);$i++)
		{
			$this->salida .= "                           <tr class=\"modulo_list_claro\">\n";
        	$this->salida .= "                             <td id='tdtf".$i."' class=\"Normal_10AN\" align=\"left\" align=\"midle\">\n";
			if($factores_riesgo[$i]['checksito']=='1')
			{
				if($factores_riesgo[$i]['factor_seleccionado']=='activo')
				{
					$java = "javascript:SeleccionarFR('tdtf".$i."','".$datos->datosEvolucion['ingreso']."','".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."','".$factores_riesgo[$i]['factor_riesgo_id']."','0','".$factores_riesgo[$i]['descripcion']."');\"";
					$this->salida .= "                               <a title='SELECCIONAR ".$factores_riesgo[$i]['descripcion']."' class=\"Normal_10AN\" href=\"".$java."\">\n";
					$this->salida .= "                                 <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"> ".$factores_riesgo[$i]['descripcion']."</sub>\n";
				}
				elseif($factores_riesgo[$i]['factor_seleccionado']=='inactivo')
				{
					$java = "javascript:SeleccionarFR('tdtf".$i."','".$datos->datosEvolucion['ingreso']."','".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."','".$factores_riesgo[$i]['factor_riesgo_id']."','1','".$factores_riesgo[$i]['descripcion']."','".$CicloIndividual[0]['ciclo_vital_individual_id']."');\"";
					$this->salida .= "                               <a title='SELECCIONAR ".$factores_riesgo[$i]['descripcion']."' class=\"Normal_10AN\" href=\"".$java."\">\n";
					$this->salida .= "                                 <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"> ".$factores_riesgo[$i]['descripcion']."</sub>\n";
				}
			}
			$this->salida .= "                               </a>\n";
			$this->salida .= "                             </td>\n";
		}
        $this->salida .= "                           </tr>\n";
        //$this->salida .= "                           <tr>\n";
        //$this->salida .= "                             <td class=\"modulo_list_claro\" align=\"center\">\n";
        //$this->salida .= "                               <input type=\"button\" class=\"input-submit\" value=\"Insertar Observacion\" onclick=\"GuardarFR('".$datos->datosEvolucion['ingreso']."','".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."','".$CicloIndividual[0]['ciclo_vital_individual_id']."',document.getElementById('fr').value);\">\n";
        //$this->salida .= "                               </a>\n";
        //$this->salida .= "                             </td>\n";
        //$this->salida .= "                           </tr>\n";
        $this->salida .= "                         </table>\n";
        $this->salida .= "                       </fieldset>";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   </table>";
        $this->salida .= "             </form>";

        $this->salida .= " <form name=\"volver\" action=\"".$Exit."\" method=\"post\">\n";//".$this->action[0]."
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

}
?>