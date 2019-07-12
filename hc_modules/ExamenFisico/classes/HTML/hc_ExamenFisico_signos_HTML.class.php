<?php

class signos_HTML 
{  
     function signos_HTML($objeto=null) 
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
                              //jab -- CP: ANORMAL; SP: NORMAL
			      if($consulta[$i]['sw_normal']=='A')
                              { 
                                $this->salida .= "<td bgcolor=RED align=\"center\" width=\"15%\">CP</td>\n";
                              }
                              elseif($consulta[$i]['sw_normal']=='N')
                              { 
                                $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"15%\">SP</td>\n";
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
               
               //jab - tabla de convenciones
	       $this->salida.="<table width=\"320\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
               $this->salida .= "<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "<td align=\"center\" colspan=\"2\">CUADRO DE CONVENCIONES</td>\n";
               $this->salida.="<tr>";
	       $this->salida .= "<td align=\"center\" width=\"30\" class=\"hc_list_oscuro\"><b>SP :</b></td>\n"; //jab
               $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\">SIN EVIDENCIA DE PATOLOGIA (Normal)</td>\n";
	       $this->salida .= "</tr>\n";
	       $this->salida .= "<tr>\n";
	       $this->salida .= "<td align=\"center\" width=\"30\" class=\"hc_list_oscuro\"><b>CP :</b></td>\n"; //jab
	       $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\">CON EVIDENCIA DE PATOLOGIA (Anormal)</td>\n";
	       $this->salida .= "</tr>\n";
               $this->salida.="</table>";
               
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
                                $this->salida .= "<td bgcolor=RED align=\"center\" width=\"15%\">CP</td>\n";
                              }
                              elseif($consulta[$i]['sw_normal']=='N')
                              { 
                                $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"15%\">SP</td>\n";
                              }
                              
                              if($i==$j)
                              { 
                                   //$this->salida .= "<td align=\"center\" rowspan='".$vectordecapas[$contadorcapas]."' class=\"hc_list_oscuro\" <TEXTAREA NAME=strg, ROWS=".$vectordecapas[$contadorcapas]." COLS=55 OnFocus=\"this.blur()\">".$hs[$contadorcapas]['hallazgo']."</TEXTAREA></td>\n";
                                   $this->salida .= "<td align=\"left\" class=\"hc_list_oscuro\">".$hs[$contadorcapas]['hallazgo']."</td>\n";
                              } 
                              $this->salida .= "</tr>\n";
                         }
                         $j=$i;
                         $this->salida.="</table>";
                         $this->salida.="<br>";
                         
                         
                    }  
               
               //jab - tabla de convenciones
	       $this->salida.="<table width=\"320\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
               $this->salida .= "<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "<td align=\"center\" colspan=\"2\">CUADRO DE CONVENCIONES</td>\n";
               $this->salida.="<tr>";
	       $this->salida .= "<td align=\"center\" width=\"30\" class=\"hc_list_oscuro\"><b>SP :</b></td>\n"; //jab
               $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\">SIN EVIDENCIA DE PATOLOGIA (Normal)</td>\n";
	       $this->salida .= "</tr>\n";
	       $this->salida .= "<tr>\n";
	       $this->salida .= "<td align=\"center\" width=\"30\" class=\"hc_list_oscuro\"><b>CP :</b></td>\n"; //jab
	       $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\">CON EVIDENCIA DE PATOLOGIA (Anormal)</td>\n";
	       $this->salida .= "</tr>\n";
               $this->salida.="</table>";
               
               return $this->salida;
          }  
     }

//            datos paciente,sistemas,registrosnoactual ,regis unico, hallagosno act,hallazgo act 
	function Forma($datos=null,$consulta,$consulta2,$sw_solo,$hallazgo,$hs)
	{ 
    //var_dump($datos);
    $ban=0;
		if($consulta2!=0 || $sw_solo!=0 || $hallazgo!=0 || $hs!=0)
		{
      		$this->salida.="<body onload='start()'>"; 
    		}  
		///////////////////////////////////////////////////////////////////////////		
        
          $ThemeImages = GetThemePath() . "/images";
          $this->salida.= ThemeAbrirTablaSubModulo("EXAMEN FISICO");
  
     	/////////////////VARIABLES CALCULO CONSULTA////////////////////
          $n_sistemas=count($consulta);
          $n_sw_sistemas=count($consulta2);
          //$limite=$n_sw_sistemas/$n_sistemas;
          //////////////////////////////////////////////////////////////////
                   
          $this->salida.="<form name='forma1' action='' method='post'>";
	     ///////////////////////llenar TABLA con datos/////////////////////////////////////////////// 
       
  
          if($hs!=0 && $sw_solo!=0)
          { 
               //$this->salida.=" <p align='center'><input name=\"nuevo\" type=\"button\" value=\"ACTUALIZAR EXAMEN\" class=\"input-bottom\" onClick=javascript:iniciar1()><p>";
               //echo "por aqui"; 
               
               $this->salida.="<div id=\"Errorsito_mensaje\" class='label_error' style=\"text-transform: uppercase; text-align:center;\"> \n";
              // $this->salida.="aaaaa".$hs."aaaaa".$sw_solo;
               $this->salida.="</div>\n";
               $this->salida.="<div id=\"pantalla\"> \n";
               $this->salida.="<table width=\"700\" align=\"center\" class=\"modulo_table_list\">\n";
               $this->salida .= "<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "<td align=\"center\" width=\"195\">SISTEMA</td>\n";
               $this->salida .= "<td align=\"center\" width=\"65\">SP</td>\n";
               $this->salida .= "<td align=\"center\" width=\"65\">CP</td>\n";
               $this->salida .= "<td align=\"center\" width=\"50%\">HALLAZGOS</td>\n";
               $this->salida .= "</tr>\n";
               // var_dump($sw_solo);
               FOR($i=0;$i<count($sw_solo);$i++)
               { 
                    
                         $this->salida.="<tr>";
                         $this->salida.="<td align=\"left\" class=\"hc_list_claro\">".$sw_solo[$i]['nombre']."</td>";
                         $name="N_".$sw_solo[$i]['tipo_sistema_id'];
                         $name1="A_".$sw_solo[$i]['tipo_sistema_id'];
                         if(empty($sw_solo[$i]['sw_normal']))
                         {
                            $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"".$name."\" name=\"".$name."\" value=\"".$name."\" onclick=\"gid(document.forma1.".$name.".value,'".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.".$name1.".value);\"></td>";
                            $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"".$name1."\" name=\"".$name1."\" value=\"".$name1."\" onclick=\"gid(document.forma1.".$name1.".value,'".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.".$name.".value);\"></td>";
                         }            
                         elseif($sw_solo[$i]['sw_normal']=='N')
                         { 
                            $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"".$name."\" name=\"".$name."\" value=\"".$name."\" onclick=\"gid(document.forma1.".$name.".value,'".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.".$name1.".value);\" checked></td>";
                            $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"".$name1."\" name=\"".$name1."\" value=\"".$name1."\" onclick=\"gid(document.forma1.".$name1.".value,'".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.".$name.".value);\"></td>";
                         }
                         elseif($sw_solo[$i]['sw_normal']=='A')
                         { 
                            $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"".$name."\" name=\"".$name."\" value=\"".$name."\" onclick=\"gid(document.forma1.".$name.".value,'".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.".$name1.".value);\"></td>";
                            $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"".$name1."\" name=\"".$name1."\" value=\"".$name1."\" onclick=\"gid(document.forma1.".$name1.".value,'".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.".$name.".value);\" checked></td>";
                         }          
                         if($i==0)
                         {  
                             if(empty($hs)) 
                             {
                              $this->salida.="<td align=\"center\" ROWSPAN='".count($sw_solo)."' class=\"modulo_table_list_title\"><TEXTAREA id='string' NAME='string' ROWS=".count($sw_solo)." onClick=\"document.forma1.hallazgo.disabled=false;\"COLS=35></TEXTAREA>";
                             }
                             else
                             {
                              $this->salida.="<td align=\"center\" ROWSPAN='".count($sw_solo)."' class=\"modulo_table_list_title\"><TEXTAREA id='string' NAME='string' ROWS=".count($sw_solo)." onClick=\"document.forma1.hallazgo.disabled=false;\"COLS=35>".$hs[$i]['hallazgo']."</TEXTAREA>";
                             } 
                              $this->salida.="<BR>";
                              $this->salida.="<input type=\"button\" name=\"hallazgo\" value=\"Actualizar\"  class=\"input-button\" onClick=\"valor_hallazgo('".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.string.value,'".$datos->datosEvolucion['usuario_id']."');\">";
                              $this->salida.="</td>";
                         }
    
		               $this->salida.="</tr>";
               
                    
               }
          }      
    
		///////////////////////llenar TABLA sin datos/////////////////////////////////////////////// 

          else
          {  
          	//$this->salida.=" <p align='center'><input name=\"nuevo\" type=\"button\" value=\"AGREGAR NUEVO EXAMEN\" class=\"input-bottom\" onClick=javascript:iniciar1()><p>";
               $this->salida.="<div id=\"Errorsito_mensaje\" class='label_error' style=\"text-transform: uppercase; text-align:center;\"> \n";
               $this->salida.="</div>\n";
               $this->salida.="<div id=\"pantalla\"> \n";
               $this->salida.="<table width=\"700\" align=\"center\" class=\"modulo_table_list\">\n";
               $this->salida .= "<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "<td align=\"center\" width=\"195\">SISTEMA</td>\n";
               /*jab -- $this->salida .= "<td align=\"center\" width=\"65\">NORMAL</td>\n";
               $this->salida .= "<td align=\"center\" width=\"65\">ANORMAL</td>\n";*/
               $this->salida .= "<td align=\"center\" width=\"65\">SP</td>\n";
               $this->salida .= "<td align=\"center\" width=\"65\">CP</td>\n";
	       $this->salida .= "<td align=\"center\" width=\"50%\">HALLAZGOS</td>\n";
               $this->salida .= "</tr>\n";
       
               FOR($i=0;$i<count($consulta);$i++)
               {
                    
                    
                         $this->salida.="<tr>";
                         $this->salida.="<td align=\"left\" class=\"hc_list_claro\">".$consulta[$i]['nombre']."</td>";
                         $name="N_".$consulta[$i]['tipo_sistema_id'];
                         $name1="A_".$consulta[$i]['tipo_sistema_id'];
                         $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"".$name."\" name=\"".$name."\" value=\"".$name."\"   onclick=\"gid(document.forma1.".$name.".value,'".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.".$name1.".value);\"></td>";
                         $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"".$name1."\" name=\"".$name1."\" value=\"".$name1."\" onclick=\"gid(document.forma1.".$name1.".value,'".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.".$name.".value);\"></td>";
                         if($i==0)
                         {    
                              $this->salida.="<td align=\"center\" ROWSPAN='".count($consulta)."' class=\"modulo_table_list_title\"><TEXTAREA id='string' NAME='string' ROWS=".count($consulta)." onClick=\"document.forma1.hallazgo.disabled=false;\"COLS=35></TEXTAREA>";
                              // getFckeditor($Nombre,$Height="200",$Width="100%",$Valor='',$ToolBar='siis')<TEXTAREA NAME=string, ROWS=".$n_rows." COLS=35> </TEXTAREA>    getFckeditor('hallazgo',$Height="95%",$Width="95%",$Valor='',$ToolBar='siis');
                              $this->salida.="<BR>";
                              $this->salida.="<input type=\"button\" name=\"hallazgo\" value=\"Insertar\"  class=\"input-button\" onClick=\"valor_hallazgo('".$datos->datosEvolucion['evolucion_id']."','".$datos->datosEvolucion['ingreso']."',document.forma1.string.value,'".$datos->datosEvolucion['usuario_id']."');\">";
                              $this->salida.="</td>";
                         }
                         $this->salida.="</tr>";
                      
               }
	               
	     }   
               
          
		$this->salida.="</table>";
          //$this->salida.="<p align='center'><input type=\"button\" name=\"cerrar\"value=\"CERRAR\" class=\"input-bottom\" onClick=\"ciar2()\"></p>";
          $this->salida.="</div>";
		$this->salida.="</form>";
               
		/////////////////////LLENAR EL HISTORIAL/////////////////////////////////////////////
  
               if(!empty($consulta2))
               { //echo "limite:".$limite."n_sw_switches".$n_sw_sistemas;
                    $x=0;
                    $this->salida.="<br><br>";
                    $this->salida.="<p align='center'><label class='label_error'><img src=\"".GetThemePath()."/images/HistoriaClinica1/historia_actual_osc.gif\"><br><big>HISTORIAL</big></label><p/>";
                    // $this->salida.="<br>";
                    $contadorcapas=0;
                    $evolucion=$consulta2[0]['evolucion_id'];
                    $vectordecapas=Array();
                    $sistemasexaminados=0;
                    for($j=0;$j<count($consulta2);$j++)
                    { 
                        if($evolucion!=$consulta2[$j]['evolucion_id'])
                         {
                           $vectordecapas[$contadorcapas]=$sistemasexaminados;
                           $contadorcapas++;
                           $sistemasexaminados=1;
                           $evolucion=$consulta2[$j]['evolucion_id'];
                         }
                         else
                         {
                           $sistemasexaminados++;
                         }
                    } 
                    if($j==count($consulta2))
                    {
                       $vectordecapas[$contadorcapas]=$sistemasexaminados;
                    }  
                    
                    
                    
                    $j=0;
                    $W=50000;
                    for($contadorcapas=0;$contadorcapas<count($vectordecapas);$contadorcapas++)
                    { 
                        
                         $this->salida.="<div id=\"L".$contadorcapas."\" style=\"position:relative; width:700px; height:20px; z-index:1; left: 25px; top: 38px; border: 1px none #000000; overflow:hidden; scrollbars=no;\"ondblClick=\"big('L".$contadorcapas."');\"> ";
                          $this->salida.="<div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\">";
                           $this->salida.="<table width=\"700\"  align=\"center\" class=\"modulo_table_list\" >\n";
                           $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
                             $resto = substr ($hallazgo[$contadorcapas]['fecha_registro'], 0, 10); 
                             $this->salida .= "<td COLSPAN=2 align=\"left\" width=\"700\">PROFESIONAL:".$hallazgo[$contadorcapas]['nombre']."-".$vectordecapas[$contadorcapas]."</td>\n";
                             $this->salida .= "<td COLSPAN=1 align=\"center\" width=\"700\">FECHA:".$resto."</td>\n";	   
                           $this->salida .= "</tr>\n";
                           $this->salida.= "<tr>\n";
                            $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"25%\">SISTEMA</td>\n";
                            $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"15%\">ESTADO</td>\n";
                            $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"60%\"><img src=\"".GetThemePath()."/images/observacion.png\">OBSERVACIONES</td>\n";
                           $this->salida.= "</tr>\n";
                           $limitecapa=$vectordecapas[$contadorcapas];
                           $limitecapa=$limitecapa+$j;                         
                         for($i=$j;$i<$limitecapa;$i++)//historia_actual_osc.gif
                         {   $zorro++;
                              $this->salida .= "<tr>\n";
                              $this->salida .= "<td align=\"center\" style='font-size:9pt;' class=\"hc_list_oscuro\" width=\"20%\">".$consulta2[$i]['nombre']."</td>\n";
                              if($consulta2[$i]['sw_normal']=='A')
                              { 
                              	$this->salida .= "<td bgcolor=RED align=\"center\" width=\"15%\">CP</td>\n";
                              }
                              elseif($consulta2[$i]['sw_normal']=='N')
                              { 
                              	$this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"15%\">SP</td>\n";
                              }
                              
                              if($i==$j)
                              { 
                                   $this->salida .= "<td align=\"center\" rowspan='".$vectordecapas[$contadorcapas]."' class=\"hc_list_oscuro\" <TEXTAREA NAME=strg, ROWS=".$vectordecapas[$contadorcapas]." COLS=55 OnFocus=\"this.blur()\">".$hallazgo[$contadorcapas]['hallazgo']."</TEXTAREA></td>\n";
                              } 
                              $this->salida .= "</tr>\n";
                         }
                         $j=$i;
                         $this->salida.="</table>";
                         $this->salida.="</font>";
                         $this->salida.="<input type=\"button\" name=\"ha\" value=\"Cerrar\" onClick=\"small('L".$contadorcapas."')\">";
                         $this->salida.="</div>";
                         $this->salida.="</div>"; 
                    }
                    $this->salida.="<BR><BR>";
                    //$this->salida.="<BR><BR>";
               }   
	       
	       //jab - tabla de convenciones
	       $this->salida.="<table width=\"320\" align=\"center\" class=\"modulo_table_list\">\n";
               $this->salida .= "<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "<td align=\"center\" colspan=\"2\">CUADRO DE CONVENCIONES</td>\n";
               $this->salida.="<tr>";
	       $this->salida .= "<td align=\"center\" width=\"30\" class=\"hc_list_claro\"><b>SP :</b></td>\n"; //jab
               $this->salida .= "<td align=\"center\" class=\"hc_list_claro\">SIN EVIDENCIA DE PATOLOGIA (Normal)</td>\n";
	       $this->salida .= "</tr>\n";
	       $this->salida .= "<tr>\n";
	       $this->salida .= "<td align=\"center\" width=\"30\" class=\"hc_list_claro\"><b>CP :</b></td>\n"; //jab
	       $this->salida .= "<td align=\"center\" class=\"hc_list_claro\">CON EVIDENCIA DE PATOLOGIA (Anormal)</td>\n";
	       $this->salida .= "</tr>\n";
               $this->salida.="</table>";
	          
			$this->salida .= ThemeCerrarTablaSubModulo();
	
               ////////////////////////FUNCIONES JAVASCRIPT/////////////////////////////////////////////
               $this->salida.="<script language=\"javaScript\">\n";
               $this->salida.="function ciar2()\n"; 
               $this->salida.="{\n";              
               $this->salida.="     document.getElementById('pantalla').style.display ='none';\n";
               $this->salida.="}\n";
               $this->salida.="function ciar1()\n"; 
               $this->salida.="{\n";
               $this->salida.="  document.getElementById('pantalla').style.display = 'none';\n";
               $this->salida.="}\n";
               $this->salida.="function valida()\n";
               $this->salida.="{";
               $this->salida.="     if(document.forma1.string.value.length==1)\n";
               $this->salida.="     {\n";
               $this->salida.="     alert('No ha colocado ningun comentario');\n";
               $this->salida.="          document.forma1.string.focus();\n";
               $this->salida.="          return false;\n";
               $this->salida.="     }\n";
               $this->salida.="}\n";
               $this->salida.="function big(lyr)\n"; 
               $this->salida.="{\n";
               $this->salida.="     document.getElementById(lyr).style.height='auto';\n"; 
               $this->salida.="}\n";
               $this->salida.="function small(lyr)\n"; 
               $this->salida.="  {\n";
               $this->salida.="     document.getElementById(lyr).style.height='20px';\n";
               $this->salida.="     document.forma1.hallazgo.focus();\n"; 
               $this->salida.="}\n";
               $this->salida.="function iniciar1()\n"; 
               $this->salida.="{\n";
               $this->salida.="     document.getElementById('pantalla').style.display = 'block';\n";
               $this->salida.="     document.forma1.hallazgo.disabled=true;\n"; 
               $this->salida.="}\n";
               
               //////////////////////////////////////////////////////////////////////
               $this->salida.="function gid(id,ingreso,evoluicion,id21)\n";
               $this->salida.="{\n document.getElementById(id21).checked=false;";
               $this->salida.="     jsrsExecute('hc_modules/ExamenFisico/RemoteScripting/procesos.php', resultadox, 'guardar_id',Array(id,ingreso,evoluicion),true);\n";
               $this->salida.="} ";
               $this->salida.="function resultadox(datos)\n";
               $this->salida.="{\n";  
               $this->salida.="   document.getElementById('Errorsito_mensaje').innerHTML='SISTEMA EVALUADO';\n";
               $this->salida.="} ";
               
               //////////////////////////////////////////////////////////////////////
               $this->salida.="function valor_hallazgo(evolucion_id,ingreso,hallazgo,usuario_id)\n";
               $this->salida.="{\n";  
               $this->salida.=" var ban=0;";
               $this->salida.="  for(i=0;i<document.forma1.elements.length;i++)";
               $this->salida.="    {";
               $this->salida.="      if(document.forma1.elements[i].type=='radio' && document.forma1.elements[i].checked==true)";
               $this->salida.="       {";
               $this->salida.="          ban=1; break;";
               $this->salida.="       }";
               $this->salida.="    }";
               $this->salida.="     if(hallazgo =='')\n";
               $this->salida.="     { ";
               $this->salida.="      document.getElementById('Errorsito_mensaje').innerHTML='El campo de texto hallazgos se encuentra vacio';\n";
               $this->salida.="      return false;\n"; 
               $this->salida.="     }\n";
               $this->salida.="     else\n";
               $this->salida.="     {\n";
               $this->salida.="       if(ban==1) ";
               $this->salida.="       {\n";
               $this->salida.="         jsrsExecute('hc_modules/ExamenFisico/RemoteScripting/procesos.php', valores_resultado_exa, 'get_hallazgo',Array(evolucion_id,ingreso,hallazgo,usuario_id),true);\n";
               $this->salida.="       }\n";
               $this->salida.="        else\n";
               $this->salida.="       {\n";
               $this->salida.="         document.getElementById('Errorsito_mensaje').innerHTML='NO SE EVALUADO NINGUN SISTEMA DEL EXAMEN FISICO';\n";
               $this->salida.="         return false;\n"; 
               $this->salida.="       }\n";
               $this->salida.="     }\n";
               $this->salida.="}\n";    
               $this->salida.="function valores_resultado_exa(cadena) \n";
               $this->salida.="{\n";
               $this->salida.="  if(cadena=='DATOS INSERTADOS CORRECTAMENTE' || cadena=='DATOS ACTUALIZADOS CORRECTAMENTE')\n";
               $this->salida.="   {\n";
               $this->salida.="      document.getElementById('Errorsito_mensaje').innerHTML=cadena;\n";
               $this->salida.="      document.forma1.hallazgo.value = 'Actualizar';\n";  
               $this->salida.="   }\n";
               $this->salida.="}\n";
               $this->salida.="function start()\n"; 
               $this->salida.="{\n";
               $this->salida.="     document.getElementById('pantac').style.display = 'none';\n"; 
               $this->salida.="     document.getElementById('pantalla').style.display = 'none';\n"; 
                                 
                                // for($w=0;$w<$limite;$w++)
                                  for($contadorcapas=0;$contadorcapas<count($vectordecapas);$contadorcapas++) 
                                   {    
               $this->salida.="        document.getElementById(L".$contadorcapas.").style.height='20px';\n";
                                   }
               $this->salida.="}\n";
               $this->salida.="function mOvr(src,clrOver)\n"; 
               $this->salida.="{\n";
               $this->salida.="     src.style.background = clrOver;\n";
               $this->salida.="}";
               $this->salida.="function mOut(src,clrIn) \n";
               $this->salida.="{\n";
               $this->salida.="     src.style.background = clrIn;\n";
               $this->salida.="}\n";
               $this->salida.="</script>\n";

          ///////////////////////////////////////////////////////////////////////////////////			
	
          $this->salida.="<BR><br><br><br>";
          $this->salida.="<BR><br><br><br>";
          $this->salida.="<BR><br><br><br>";
          $this->salida.="<BR><br><br><br>";
          return $this->salida;
	}

}
?>