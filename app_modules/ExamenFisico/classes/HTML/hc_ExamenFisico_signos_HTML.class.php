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
        if(!empty($consulta) )
         { 
            $this->salida.="<table border=\"0\" width=\"100%\"  align=\"center\" class=\"modulo_table_list\" >\n";
            $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
            $resto = substr ($hs[0]['fecha_registro'], 0, 10); 
            $this->salida .= "<td COLSPAN=2 align=\"left\" width=\"700\">PROFESIONAL:".$hs[0]['nombre']."</td>\n";
            $this->salida .= "<td COLSPAN=1 align=\"center\" width=\"700\">FECHA:".$resto."</td>\n";    
            $this->salida .= "</tr>\n";
            $this->salida.= "<tr>\n";
            $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"25%\">SISTEMA</td>\n";
            $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"15%\">ESTADO</td>\n";
            $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"60%\"><img src=\"".GetThemePath()."/images/observacion.png\"> OBSERVACIONES</td>\n";
            $this->salida.= "</tr>\n";
            
            for($i=0;$i<11;$i++)
            {   
                $this->salida .= "<tr>\n";
                $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"20%\">".$consulta[$i]['nombre']."</td>\n";//style='font-size:9pt;'
                if($consulta[$i]['sw_normal']=='A')
                { 
                      $this->salida .= "<td bgcolor=RED align=\"center\" width=\"15%\">ANORMAL</td>\n";
                }
                else
                { 
                      $this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"15%\">NORMAL</td>\n";
                }
                                          
                if($i==0)
                {
                      $this->salida .= "<td align=\"center\" rowspan='11' class=\"hc_list_oscuro\" <TEXTAREA NAME=strg, ROWS=11 COLS=55 OnFocus=\"this.blur()\">".$hs[0]['hallazgo']."</TEXTAREA></td>\n";
                } 
                $this->salida .= "</tr>\n";
            }
            
            $this->salida.="</table>";
        }
        else
        {
          return false;
          
        }    
	     return $this->salida;
     }

     
     function frmHistoria($consulta,$hs)
     {
          if(empty($hs[0]['fecha_registro']))
          {
               $this->salida.="<table  border=\"1\" width=\"100%\"  align=\"center\" >\n";
               $this->salida .="<tr>\n";
               $resto = substr ($hs[$k]['fecha_registro'], 0, 20); 
               $this->salida .=   "<td COLSPAN=3 align=\"center\">NO HAY RESULTADOS DE EXAMEN FISICO</td>\n";
               $this->salida .= "</tr>\n";
               $this->salida.="</table>\n";
               return $this->salida;
          }       
          else
          {    
               for($k=0;$k<count($hs);$k++)
               {
                    $comienzo=0;
                    $final=11;
                    $this->salida .= "<br>\n";
                    $this->salida.="<table  border=\"1\" width=\"100%\"  align=\"center\" >\n";
                    $this->salida .= "<tr>\n";
                    $resto = substr ($hs[$k]['fecha_registro'], 0, 20); 
                    $this->salida .= "   <td COLSPAN=3 align=\"center\">EXAMEN FISICO</td>\n";
                    $this->salida .= "</tr>\n";
                    $this->salida .= "<tr>\n";
                    $this->salida .= "  <td COLSPAN=2 align=\"left\" >PROFESIONAL:".$hs[$k]['nombre']."</td>\n";
                    $this->salida .= "  <td COLSPAN=1 align=\"center\">FECHA:".$resto."</td>\n";    
                    $this->salida .= "</tr>\n";
                    $this->salida.= "<tr>\n";
                    $this->salida.= "  <td align=\"center\" width=\"30%\">SISTEMA</td>\n";
                    $this->salida.= "  <td align=\"center\" width=\"20%\">ESTADO</td>\n";
                    $this->salida.= "  <td align=\"center\" width=\"50%\">OBSERVACIONES</td>\n";
                    $this->salida.= "</tr>\n";
                    for($i=$comienzo;$i<=$final;$i++)
                    {   
                         $this->salida .= "<tr>\n";
                         $this->salida .= "  <td align=\"center\" width=\"20%\">".$consulta[$i]['nombre']."</td>\n";
                         if($consulta[$i]['sw_normal']=='A')
                         { 
                         	$this->salida .="  <td bgcolor=RED align=\"center\" width=\"15%\">ANORMAL</td>\n";
                         }
                         else
                         { 
                         	$this->salida .= "  <td align=\"center\" width=\"15%\">NORMAL</td>\n";
                         }
                         if($i==0)
                         {
                         	$this->salida .= "  <td VALIGN=\"TOP\" rowspan='12'>".$hs[$k]['hallazgo']."</td>\n";
                         } 
                         $this->salida .= "</tr>\n";
                    }
                    $this->salida.="</table>";
                    
                    $comienzo=$comienzo+12;
                    $final=$final+12;    
                    
               }  
               return $this->salida;
          }  
     }

//            datos paciente,sistemas,registrosnoactual ,regis unico, hallagosno act,hallazgo act 
	function Forma($datos=null,$consulta,$consulta2,$sw_solo,$hallazgo,$hs)
	{ 
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
          $limite=$n_sw_sistemas/$n_sistemas;
          //////////////////////////////////////////////////////////////////
          $this->salida.="<form name='forma1' action='' method='post'>";
	     ///////////////////////llenar TABLA con datos/////////////////////////////////////////////// 
  
  
          if($hs!=0 && $sw_solo!=0)
          { 
               //$this->salida.=" <p align='center'><input name=\"nuevo\" type=\"button\" value=\"ACTUALIZAR EXAMEN\" class=\"input-bottom\" onClick=javascript:iniciar1()><p>";
               //echo "por aqui"; 
               
               $this->salida.="<div id=\"Errorsito_mensaje\" class='label_error' style=\"text-transform: uppercase; text-align:center;\"> \n";
               $this->salida.="</div>\n";
               $this->salida.="<div id=\"pantalla\"> \n";
               $this->salida.="<table width=\"700\" align=\"center\" class=\"modulo_table_list\">\n";
               $this->salida .= "<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "<td align=\"center\" width=\"195\">SISTEMA</td>\n";
               $this->salida .= "<td align=\"center\" width=\"65\">NORMAL</td>\n";
               $this->salida .= "<td align=\"center\" width=\"65\">ANORMAL</td>\n";
               $this->salida .= "<td align=\"center\" width=\"50%\">HALLAZGOS</td>\n";
               $this->salida .= "</tr>\n";
   
               FOR($i=0;$i<count($consulta);$i++)
               { 
                    if($i % 2 ==0)
                    {
                         $this->salida.="<tr>";
                         $this->salida.="<td align=\"left\" class=\"hc_list_claro\">".$sw_solo[$i]['nombre']."</td>";
                         //$this->salida.="<label> ".$sw_solo[$i]['nombre']."";
                         //$this->salida.="<label> ".$sw_solo[$i]['sw_normal']."";
                         if($sw_solo[$i]['sw_normal']=='N')
                         {
                         $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"'".$sw_solo[$i]['nombre']."'\" value=\"N\"checked></td>";
                         $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"'".$sw_solo[$i]['nombre']."'\" value=\"A\"></td>";
                         }            
                         elseif($sw_solo[$i]['sw_normal']=='A')
                         { 
                         $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"'".$sw_solo[$i]['nombre']."'\" value=\"N\"></td>";
                         $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"'".$sw_solo[$i]['nombre']."'\" value=\"A\" checked></td>";
                         }
          
                         if($i==0)
                         {  
                              $this->salida.="<td align=\"center\" ROWSPAN='".count($consulta)."' class=\"modulo_table_list_title\"><TEXTAREA id='string' NAME='string' ROWS=".count($consulta)." onClick=\"document.forma1.hallazgo.disabled=false;\"COLS=35>".$hs[$i]['hallazgo']."</TEXTAREA>";
                              $this->salida.="<BR>";
                              $this->salida.="<input type=\"button\" name=\"hallazgo\" value=\"Actualizar\"  class=\"input-button\" onClick=\"valores_exa()\">";
                              $this->salida.="</td>";
                         }
    
		               $this->salida.="</tr>";
                	}  
                    else
                    {  
	                    $this->salida.="<tr>";
                         $this->salida.="<td align=\"left\" class=\"hc_list_oscuro\">".$sw_solo[$i]['nombre']."</td>";
               
	                    if($sw_solo[$i]['sw_normal']=='N')
                         {
                              $this->salida.="<td align=\"center\" class=\"hc_list_oscuro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"'".$sw_solo[$i]['nombre']."'\" value=\"N\" checked></td>";
                              $this->salida.="<td align=\"center\" class=\"hc_list_oscuro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"'".$sw_solo[$i]['nombre']."'\" value=\"A\"></td>";
                         }
	                    elseif($sw_solo[$i]['sw_normal']=='A')
                         { 
                              $this->salida.="<td align=\"center\" class=\"hc_list_oscuro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"'".$sw_solo[$i]['nombre']."'\" value=\"N\"></td>";
                              $this->salida.="<td align=\"center\" class=\"hc_list_oscuro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"'".$sw_solo[$i]['nombre']."'\" value=\"A\" checked></td>";
                         }
                         $this->salida.="</tr>";
                    }
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
               $this->salida .= "<td align=\"center\" width=\"65\">NORMAL</td>\n";
               $this->salida .= "<td align=\"center\" width=\"65\">ANORMAL</td>\n";
               $this->salida .= "<td align=\"center\" width=\"50%\">HALLAZGOS</td>\n";
               $this->salida .= "</tr>\n";
       
               FOR($i=0;$i<count($consulta);$i++)
               {
                    if($i % 2 ==0)
                    {
                         $this->salida.="<tr>";
                         $this->salida.="<td align=\"left\" class=\"hc_list_claro\">".$consulta[$i]['nombre']."</td>";
                         $i=$i+1;
                         $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"sistema".$i."\" value=\"N\"></td>";
                         $this->salida.="<td align=\"center\" class=\"hc_list_claro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"sistema".$i."\" value=\"A\"></td>";
                         $i=$i-1;
                         if($i==0)
                         {    
                              $this->salida.="<td align=\"center\" ROWSPAN='".count($consulta)."' class=\"modulo_table_list_title\"><TEXTAREA id='string' NAME='string' ROWS=".count($consulta)." onClick=\"document.forma1.hallazgo.disabled=false;\"COLS=35></TEXTAREA>";
                              // getFckeditor($Nombre,$Height="200",$Width="100%",$Valor='',$ToolBar='siis')<TEXTAREA NAME=string, ROWS=".$n_rows." COLS=35> </TEXTAREA>    getFckeditor('hallazgo',$Height="95%",$Width="95%",$Valor='',$ToolBar='siis');
                              $this->salida.="<BR>";
                              $this->salida.="<input type=\"button\" name=\"hallazgo\" value=\"Insertar\"  class=\"input-button\" onClick=\"valores_exa()\">";
                              $this->salida.="</td>";
                         }
                    	$this->salida.="</tr>";
                    }  
                    else
                    {  
                         $this->salida.="<tr>";
                         $this->salida.="<td align=\"left\" class=\"hc_list_oscuro\">".$consulta[$i]['nombre']."</td>";
                         $i=$i+1;
                         $this->salida.="<td align=\"center\" class=\"hc_list_oscuro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"sistema".$i."\" value=\"N\"></td>";
                         $this->salida.="<td align=\"center\" class=\"hc_list_oscuro\"><input type=\"radio\" id=\"sistema".$i."\" name=\"sistema".$i."\" value=\"A\"></td>";
                         $i=$i-1;
                         $this->salida.="</tr>";
                    }
               }
	     }   
               
          $this->salida.="<input type=\"hidden\" name=\"usuario\"value='".$datos->datosEvolucion['usuario_id']."'>";
          $this->salida.="<input type=\"hidden\" name=\"ingreso\"value='".$datos->datosEvolucion['ingreso']."'>";
          $this->salida.="<input type=\"hidden\" name=\"evolucion\"value='".$datos->datosEvolucion['evolucion_id']."'>";
		$this->salida.="</table>";
          //$this->salida.="<p align='center'><input type=\"button\" name=\"cerrar\"value=\"CERRAR\" class=\"input-bottom\" onClick=\"ciar2()\"></p>";
          $this->salida.="</div>";
		$this->salida.="</form>";
  
		/////////////////////LLENAR EL HISTORIAL/////////////////////////////////////////////
  
               if($limite>0.5)
               { //echo "limite:".$limite."n_sw_switches".$n_sw_sistemas;
                    $x=0;
                    $this->salida.="<br><br>";
                    $this->salida.="<p align='center'><label class='label_error'><img src=\"".GetThemePath()."/images/HistoriaClinica1/historia_actual_osc.gif\"><br><big>HISTORIAL</big></label><p/>";
                    // $this->salida.="<br>";
                    for($j=0;$j<$limite;$j++)
                    {
                         $this->salida.="<div id=\"L".$j."\" style=\"position:relative; width:700px; height:20px; z-index:1; left: 25px; top: 38px; border: 1px none #000000; overflow:hidden; scrollbars=no;\"ondblClick=\"big('L".$j."');\"> ";
                         $this->salida.="<div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\">";
                         $this->salida.="<table width=\"700\"  align=\"center\" class=\"modulo_table_list\" >\n";
                         $this->salida .= "<tr class=\"modulo_table_list_title\" onMouseOver=\"mOvr(this,'#000000');\" onMouseOut=\"mOut(this,'#2A63B9');\" >\n";
                         $resto = substr ($hallazgo[$j]['fecha_registro'], 0, 10); 
                         $this->salida .= "<td COLSPAN=2 align=\"left\" width=\"700\">PROFESIONAL:".$hallazgo[$j]['nombre']."</td>\n";
                         $this->salida .= "<td COLSPAN=1 align=\"center\" width=\"700\">FECHA:".$resto."</td>\n";	   
                         $this->salida .= "</tr>\n";
                         $this->salida.= "<tr>\n";
                         $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"25%\">SISTEMA</td>\n";
                         $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"15%\">ESTADO</td>\n";
                         $this->salida.= "<td align=\"center\" class=\"hc_list_claro\" width=\"60%\"><img src=\"".GetThemePath()."/images/observacion.png\"> OBSERVACIONES</td>\n";
                         $this->salida.= "</tr>\n";
                         $limite2=($n_sistemas+$x);
                         for($i=$x;$i<$limite2;$i++)//historia_actual_osc.gif
                         {   
                              $this->salida .= "<tr>\n";
                              $this->salida .= "<td align=\"center\" style='font-size:9pt;' class=\"hc_list_oscuro\" width=\"20%\">".$consulta2[$i]['nombre']."</td>\n";
                              if($consulta2[$i]['sw_normal']=='A')
                              { 
                              	$this->salida .= "<td bgcolor=RED align=\"center\" width=\"15%\">ANORMAL</td>\n";
                              }
                              else
                              { 
                              	$this->salida .= "<td align=\"center\" class=\"hc_list_oscuro\" width=\"15%\">NORMAL</td>\n";
                              }
                              
                              if($i==$x)
                              {
                                   $this->salida .= "<td align=\"center\" rowspan='12' class=\"hc_list_oscuro\" <TEXTAREA NAME=strg, ROWS=11 COLS=55 OnFocus=\"this.blur()\">".$hallazgo[$j]['hallazgo']."</TEXTAREA></td>\n";
                              } 
                              $this->salida .= "</tr>\n";
                         }
                         $x=$i;
                         $this->salida.="</table>";
                         $this->salida.="</font>";
                         $this->salida.="<input type=\"button\" name=\"ha\" value=\"Cerrar\" onClick=\"small('L".$j."')\">";
                         $this->salida.="</div>";
                         $this->salida.="</div>"; 
                    }
                    $this->salida.="<BR><BR>";
                    //$this->salida.="<BR><BR>";
               }      
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
               $this->salida.="     document.getElementById(lyr).style.height='350px';\n"; 
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
               $this->salida.="function valores_exa()\n";
               $this->salida.="{\n";  
               $this->salida.="     var i;\n";
               $this->salida.="     var contador,contador1;\n";
               $this->salida.="     contador=0;contador1=0;\n";
               $this->salida.="     var j=0;\n";
               $this->salida.="     var datos = new Array();\n";
               $this->salida.="     if(document.forma1.string.value !='')\n";
               $this->salida.="     { ";
               $this->salida.="       datos[j++]=document.forma1.string.value;\n";
               $this->salida.="     }\n";
               $this->salida.="     else\n";
               $this->salida.="     {\n";
               $this->salida.="       document.getElementById('Errorsito_mensaje').innerHTML='El campo de texto hallazgos se encuentra vacio';\n";
               $this->salida.="      return false;\n";
               $this->salida.="     }\n";
               $this->salida.="     datos[j++]=document.forma1.usuario.value;\n";
               $this->salida.="     datos[j++]=document.forma1.ingreso.value;\n";
               $this->salida.="     datos[j++]=document.forma1.evolucion.value;\n";
               $this->salida.="     for(i=0;i<document.forma1.elements.length;i++)\n";
               $this->salida.="     {\n";
               $this->salida.="          if(document.forma1.elements[i].type=='radio')\n";
               $this->salida.="           {\n";
               $this->salida.="                  contador=contador+1;\n";
               $this->salida.="                 if(document.forma1.elements[i].checked==true)\n";
               $this->salida.="                 {\n";
               $this->salida.="                  contador1=contador1+1;\n";
               $this->salida.="                  datos[j++]=document.forma1.elements[i].value;\n";
               $this->salida.="                 }\n";
               $this->salida.="           }\n";  
               $this->salida.="     }\n";
              //$this->salida.="                 alert(contador);";
               //$this->salida.="                 alert(contador1);";
               $this->salida.="                var a=(contador/contador1);";
              // $this->salida.="                 alert(a);";
               $this->salida.="                 if((contador/contador1) != 2)\n";
               $this->salida.="                 {\n";
               $this->salida.="                   document.getElementById('Errorsito_mensaje').innerHTML='Uno de los sistemas no ha sido selecciono verifique y vuelva a insertar';\n";
               $this->salida.="                   return false;\n";
               $this->salida.="                 }\n";   
               $this->salida.="       jsrsExecute('hc_modules/ExamenFisico/RemoteScripting/procesos.php', valores_resultado_exa, 'get_valores_exa', datos);\n";
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
                                 for($w=0;$w<$limite;$w++)
                                   {    
               $this->salida.="        document.getElementById(L".$w.").style.height='20px';\n";
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