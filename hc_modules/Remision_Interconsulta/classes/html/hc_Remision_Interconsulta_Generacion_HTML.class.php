<?php
/**
* Submodulo de Remision de Interconsultas
*
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_Remision_Interconsulta_Generacion_HTML.class.php,v 1.2 2007/08/30 20:31:20 tizziano Exp $
*/

class Generacion_HTML
{
	function Generacion_HTML()
	{
		return true;
	}
	
	function frmHistoria()
	{
		$this->salida="";
		return $this->salida;
	}
	
	function frmConsulta()
	{
		return true;
	}
	
	/**
	* Funcion que señaliza una palabra para simbolizar que esta en estado de alerta
	* @return boolean
	*/
	function SetStyle($campo)
	{
		if ($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

	function frmForma($vector,$motivo_consulta,$enfermedad,$antecedentes,$ex_fisico,$ex_fisico_hallazgo,$apoyod,$diagI,$datos_evolucion,$medicamentos,$plan_seg,$diagE,$tipos_salida,$datos_salida,$nivel)
	{
		$this->salida.= ThemeAbrirTablaSubModulo('REMISION Y CONTRA-REMISION');
		
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida.= 		$this->SetStyle("MensajeError");
		$this->salida.= "	</table><br>";
		
		IncludeFile("hc_modules/Remision_Interconsulta/RemoteXajax/Remision_InterconsultaX.php");
		$objClassModules=new hc_Classmodules();
		$objClassModules->SetXajax(array("GeneracionI","GuardaDatos","DatosEvolucion","PlanSeguimiento","Salida",
          						    "SeleccionPrimario","SeleccionIncluir","VistaOK"));
		
		$ingreso=SessionGetVar("Ingreso");
		$evolucion=SessionGetVar("Evolucion");
		$paso=SessionGetVar("Paso");
		
		$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
		
		$reporte=new GetReports();
		$i=0;
		$cont=0;
		$salida1="";
		foreach($vector as $key=>$valor)
		{
			$j=0;
			$salida2="";
			foreach($valor as $valor1)
			{
				$ok=0;
				$salida="";
				switch("$i$j")
				{
                         case "00":
                              if($motivo_consulta)
                              {
                                   $_SESSION['EPICRISIS_OK']["$ingreso"]["$i$j"]=1;
                                   $salida.="<script>";
                                   $salida.="	MostrarSpan('edit$i$j');";
                                   $salida.="	MostrarSpan('ok$i$j');";
                                   $salida.="</script>";
                                   $l=0;
                                   foreach($motivo_consulta as $mc)
                                   {
                                        $c=",  ";
                                        if(sizeof($motivo_consulta)==$l+1)
                                             $c="";
                                        
                                        $desc_mov=$mc['descripcion'];
                                        if(!strcmp($mc['descripcion'],$mc['descripcion1']))
                                             $desc_mov=$mc['descripcion1'];
                                        
                                        $mot_con.="".strtoupper($desc_mov)."$c";
                                        $l++;
                                   }
                                   $salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
                                   $salida.="	<tr>";
                                   $salida.="		<td><label class=\"label\">$mot_con</label><td>";
                                   $salida.="	</tr>";
                                   $salida.="</table>";
                              }
                              else
                              {
                                   $salida.="<script>";
                                   $salida .= "		e = xGetElementById('edit$i$j');\n";
                                   $salida .= "		e.style.display = \"none\";\n";
                                   $salida .= "		f = xGetElementById('ok$i$j');\n";
                                   $salida .= "		f.style.display = \"none\";\n";
                                   $salida.="</script>";
                              }
                         break;
                         
                         case "01":
                              $_SESSION['EPICRISIS_OK']["$ingreso"]["$i$j"]=1;
                              if($enfermedad)
                              {
                                   $salida.="<script>";
                                   $salida.="	MostrarSpan('edit$i$j');";
                                   $salida.="	MostrarSpan('ok$i$j');";
                                   $salida.="</script>";
                                   $l=0;
                                   foreach($enfermedad as $mc)
                                   {
                                        $c=",  ";
                                        if(sizeof($enfermedad)==$l+1)
                                             $c="";
                                        
                                        $enf=$mc['enfermedadactual'];
                                        if(!strcmp($mc['enfermedadactual'],$mc['enfermedadactual1']))
                                             $enf=$mc['enfermedadactual1'];
                                        
                                        $enfer.="".strtoupper($enf)."$c";
                                        $l++;
                                   }
                                   $salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
                                   $salida.="	<tr>";
                                   $salida.="		<td><label class=\"label\">$enfer</label><td>";
                                   $salida.="	</tr>";
                                   $salida.="</table>";
                              }
                         break;
                         
                         case "02":
                              $_SESSION['EPICRISIS_OK']["$ingreso"]["$i$j"]=1;
                              if($antecedentes)
                              {
                                   $salida.="<script>";
                                   $salida.="	MostrarSpan('edit$i$j');";
                                   $salida.="	MostrarSpan('ok$i$j');";
                                   $salida.="</script>";
                                   $m=0;
                                   $salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
                                   foreach($antecedentes as $key1=>$nivel1)
                                   {
                                        $n=0;
                                        $html1="";
                                        
                                        foreach($nivel1 as $key2=>$nivel2)
                                        {
                                             $tabla1="";
                                             
                                             $l=0;
                                             foreach($nivel2 as $key3=>$nivel3)
                                             {
                                                  $c=", ";
                                                  if(sizeof($nivel2)==$l+1)
                                                       $c="";
                                                  if($nivel3['sw_riesgo']=='1')
                                                       $estado="Si";
                                                  else
                                                       $estado="No";
                                                  $tabla1.="$estado - ".$nivel3['detalle']."$c";
                                                  $l++;
                                             }
                                             
                                             $tabla="<table border=\"0\">";
                                             $tabla.="	<tr class=\"label\">";
                                             $tabla.="		<td>";
                                             $tabla.="			$tabla1";
                                             $tabla.="		</td>";
                                             $tabla.="	</tr>";
                                             $tabla.="</table>";
                                             
                                             $html1.="<tr class=\"label\">";
                                             $html1.="	<td $styl1 width=\"20%\">$key2</td>";
                                             $html1.="	<td width=\"80%\">$tabla</td>";
                                             $html1.="</tr>";
                                             $n++;
                                        }
                                        $salida.="<tr rowspan=\"".($n+1)."\">";
                                        $salida.="	<td class=\"label\">$key1</td>";
                                        $salida.="</tr>";
                                        $salida.="	$html1";
                                        $m++;
                                   }
                                   $salida.="</table>";
                              }
                         break;
                         
                         case "03":
                              $_SESSION['EPICRISIS_OK']["$ingreso"]["$i$j"]=1;
                              if($ex_fisico)
                              {
                                   $salida.="<script>";
                                   $salida.="	MostrarSpan('edit$i$j');";
                                   $salida.="	MostrarSpan('ok$i$j');";
                                   $salida.="</script>";
                                   $salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
                                   foreach($ex_fisico as $key1=>$nivel1)
                                   {
                                        
                                        $estado="";
                                        $l=0;
                                        foreach($nivel1 as $nivel2)
                                        {
                                             $c=",  ";
                                             if(sizeof($nivel1)==$l+1)
                                                  $c="";
                                                  
                                             if($nivel2['sw_normal']=='N')
                                                  $estado.="<label class=\"label\">NORMAL</label>$c";
                                             else
                                                  $estado.="<label class=\"label_error\">ANORMAL</label>$c";
                                             
                                             $l++;
                                        }
                                        $salida.="<tr class=\"label\">";
                                        $salida.="	<td width=\"15%\" $styl1>".strtoupper($key1)."</td>";
                                        $salida.="	<td>$estado</td>";
                                        $salida.="</tr>";
                                   }
                                   $l=0;
                                   $salida.="<tr align=\"center\" $styl1>";
                                   $salida.="	<td colspan=\"2\" class=\"modulo_list_oscuro\">HALLAZGOS</td>";
                                   $salida.="</tr>";
                                   $salida.="<tr class=\"label\">";
                                   $salida.="	<td colspan=\"2\">";
                                   foreach($ex_fisico_hallazgo as $nivel1)
                                   {
                                        $c=",  ";
                                        if(sizeof($ex_fisico_hallazgo)==$l+1)
                                             $c="";
                                             
                                        $salida.="".strtoupper($nivel1['hallazgo'])."$c";
                                             
                                        $l++;
                                   }
                                   $salida.="	</td>";
                                   $salida.="</tr>";
                                   $salida.="</table>";
                              }
                         break;
                         
                         case "04":
                              $_SESSION['EPICRISIS_OK']["$ingreso"]["$i$j"]=1;
                              if($apoyod)
                              {
                                   $salida.="<script>";
                                   $salida.="	MostrarSpan('edit$i$j');";
                                   $salida.="	MostrarSpan('ok$i$j');";
                                   $salida.="</script>";
                                   $l=0;
                                   foreach($apoyod as $valor)
                                   {
                                        
                                        $c=",  ";
                                        if(sizeof($apoyod)==$l+1)
                                             $c="";
                                             
                                        $apd.="".strtoupper($valor['descripcion'])."$c";
                                        $l++;
                                   }
                                   $salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
                                   $salida.="	<tr>";
                                   $salida.="		<td><label class=\"label\">$apd</label><td>";
                                   $salida.="	</tr>";
                                   $salida.="</table>";
                              }
                         break;
                         
                         case "05":
                              if($diagI)
                              {
                                   $_SESSION['EPICRISIS_OK']["$ingreso"]["$i$j"]=1;
                                   $salida.="<script>";
                                   $salida.="	MostrarSpan('edit$i$j');";
                                   $salida.="	MostrarSpan('ok$i$j');";
                                   $salida.="</script>";
                                   $salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
                                   $salida.="<tr class=\"modulo_table_list_title\">";
                                   $salida.="	<td>CODIGO</td>";
                                   $salida.="	<td>DIAGNOSTICO</td>";
                                   $salida.="	<td>TIPO DIAGNOSTICO</td>";
                                   $salida.="	<td>PRIMARIO</td>";
                                   $salida.="</tr>";
                                   foreach($diagI as $nivel1)
                                   {
                                        $salida.="<tr>";
                                        $salida.="	<td class=\"label\">".$nivel1['diagnostico_id']."</td>";
                                        $salida.="	<td class=\"label\">".$nivel1['diagnostico_nombre']."</td>";
                                        $salida.="	<td class=\"label\">".$nivel1['tipo_diag']."</td>";
                                        $p="";
                                        if($nivel1['sw_principal'])
                                             $p=" P ";
                                        
                                        $salida.="	<td class=\"label\">$p</td>";
                                        $salida.="</tr>";
                                   }
                                   $salida.="</table>";
                              }
                              else
                              {
                                   $salida.="<script>";
                                   $salida .= "		e = xGetElementById('edit$i$j');\n";
                                   $salida .= "		e.style.display = \"none\";\n";
                                   $salida .= "		f = xGetElementById('ok$i$j');\n";
                                   $salida .= "		f.style.display = \"none\";\n";
                                   $salida.="</script>";
                              }
                         break;
						
                         case "10":
                              //if(SessionGetVar("listo_$ingreso")==1)
                                //   $_SESSION['EPICRISIS_OK']["$ingreso"]["$i$j"]=1;

                              if(!is_array($datos_salida))
                              {
                                   
                                   $salida.="<script>";
                                   $salida .= "		e = xGetElementById('edit$i$j');\n";
                                   $salida .= "		e.style.display = \"none\";\n";
                                   $salida .= "		f = xGetElementById('ok$i$j');\n";
                                   $salida .= "		f.style.display = \"none\";\n";
                                   $salida.="</script>";
                                   
                                   $b=true;
                                   
                                   $salida.="<form name=\"forma_dat\" method=\"post\" action=\"\" class=\"modulo_table_list\">";
                                   $salida.="<table align=\"center\" border=\"0\" width=\"85%\">";
                                   
                                   //******
                                   $salida.="	<tr align=\"center\">";
                                   $salida.="		<td class=\"label\" width=\"50%\" colspan=\"2\">";
                                   
                                   $salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                                   $salida.="<tr class=\"modulo_table_title\">";
                                   $salida.="  <td align=\"center\" colspan=\"7\">CONCEPTOS DE CONTROL</td>";
                                   $salida.="</tr>";
                                   $salida.="<tr class=\"modulo_list_oscuro\">";
                                   $salida.="<td width=\"5%\">Tratamiento Medico</td>";
                                   $salida.="<td width=\"10%\" align=\"center\"><input type=\"radio\" id=\"Id_tratamiento\" name=\"trat_medico\" value=\"1\">";
                                   $salida.="</td>";
                                   $salida.="</tr>";                                        
                                   $salida.="<tr class=\"modulo_list_oscuro\">";
                                   $salida.="<td width=\"5%\">Diagnostico</td>";
                                   $salida.="<td width=\"10%\" align=\"center\"><input type=\"radio\" id=\"Id_tratamiento\" name=\"trat_medico\" value=\"2\">";
                                   $salida.="</td>";
                                   $salida.="</tr>";                                        
                                   $salida.="<tr class=\"modulo_list_oscuro\">";
                                   $salida.="<td width=\"5%\">Tratamiento Quirurgico</td>";
                                   $salida.="<td width=\"10%\" align=\"center\"><input type=\"radio\" id=\"Id_tratamiento\" name=\"trat_medico\" value=\"3\">";
                                   $salida.="</td>";
                                   $salida.="</tr>";                                        
                                   $salida.="<tr class=\"modulo_list_oscuro\">";
                                   $salida.="<td width=\"5%\">Otros</td>";
                                   $salida.="<td width=\"10%\" align=\"center\"><input type=\"radio\" id=\"Id_tratamiento\" name=\"trat_medico\" value=\"4\">";
                                   $salida.="</td>";
                                   $salida.="</tr>";                                        
                                   $salida.="</table><br>";

                                   
                                   $salida.="	</td>";
                                   
                                   $salida.="	<td class=\"label\" width=\"50%\">";
                                   
                                   $salida.="<table align=\"center\" border=\"0\"  width=\"100%\">";
                                   $salida.="<tr class=\"modulo_table_title\">";
                                   $salida.="  <td align=\"center\">ESPECIFIQUE RECEPCION</td>";
                                   $salida.="</tr>";
                                   $salida.="<tr class=\"modulo_list_oscuro\">";
                                   $salida.="<td align=\"center\" class=\"label\"><textarea name=\"recepcion\" id=\"recepcion_id\" class=\"input-text\" cols=\"70%\" rows=\"4\"></textarea></td>";
                                   $salida.="</tr>";                                        
                                   $salida.="</table><br>";

                                   
                                   $salida.="	</td>";
                                   
                                   $salida.="	</tr>";

                                   //******
                                   
                                   $salida.="<tr>";
                                   $salida.="	<td class=\"label\" colspan=\"2\" align=\"center\">TIPO CAUSA</td>";
                                   $salida.="	<td class=\"label\" align=\"center\">REMITIDO A</td>";
                                   $l=0;
                                   foreach($tipos_salida as $nivel1)
                                   {
                                        $salida.="<tr>";
                                        $salida.="	<td class=\"label\">".$nivel1['descripcion']."</td>";
                                        foreach($datos_salida as $nivel2)
                                        {
                                             $check="";
                                             if($nivel1['hc_epicrisis_tipo_causa_salida_id']==$nivel2['tipo_causa_id'])
                                                  $check="checked";
                                                  
                                             $salida.=" <td class=\"label\"><input type=\"radio\" name=\"salida\" value=\"".$nivel1['hc_epicrisis_tipo_causa_salida_id']."\" $check></td>";
                                             if($b)
                                             {
                                                  $salida.=" <td class=\"label\" rowspan=\"".sizeof($tipos_salida)."\"><textarea name=\"remision\" class=\"input-text\" cols=\"50%\" rows=\"3\">".$nivel2['descripcion_remision']."</textarea></td>";
                                                  $b=false;
                                             }
                                        }
                                        if(!$datos_salida)
                                        {
                                             $salida.=" <td class=\"label\"><input type=\"radio\" name=\"salida\" value=\"".$nivel1['hc_epicrisis_tipo_causa_salida_id']."\" $check></td>";
                                             if($b)
                                             {
                                                  $salida.=" <td align=\"center\" class=\"label\" rowspan=\"".sizeof($tipos_salida)."\"><textarea name=\"remision\" class=\"input-text\" cols=\"50%\" rows=\"3\">".$nivel2['descripcion_remision']."</textarea></td>";
                                                  $b=false;
                                             }
                                        }
                                        
                                        $salida.="</tr>";
                                        $l++;
                                   }
                                   $salida.="	<tr>";
                                   $salida.="		<td class=\"label\" align=\"center\" colspan=\"3\"><input type=\"button\" name=\"Guardar\" class=\"input-submit\" value=\"GUARDAR\" onclick=\"DatosSalida(this.form,'$i$j','$l');ValorOk('ok$i$j','$i$j',$ok);\"></td>";
                                   $salida.="	</tr>";
                                   $salida.="</table>";
                                   $salida.="</form>";
                              }
                              else
                              {
                                   $salida.="<script>";
                                   $salida.="	MostrarSpan('edit$i$j');";
                                   $salida.="	MostrarSpan('ok$i$j');";
                                   $salida.="</script>";
                                   
                                   $salida.="<table align=\"left\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
                                   $salida.="<tr>";
                                   $salida.="	<td class=\"label\" width=\"20%\" >TIPO CAUSA : </td>";
                                   foreach($datos_salida as $nivel1)
                                   {
                                        $causa.=$nivel1['causa'];
                                        $remision.=$nivel1['descripcion_remision'];
                                        $recepcion .=$nivel1['recepcion'];
                                        if($nivel1['concepto_control'] == '1')
                                        { $concepto .= "TRATAMIENTO MEDICO"; }
                                        elseif($nivel1['concepto_control'] == '2')
                                        { $concepto .= "DIAGNOSTICO"; }
                                        elseif($nivel1['concepto_control'] == '3')
                                        { $concepto .= "TRATAMIENTO QUIRURGICO"; }
                                        elseif($nivel1['concepto_control'] == '4')
                                        { $concepto .= "OTRAS"; }
                                        
                                   }
                                   $salida.="	<td class=\"label\">".$causa."</td>";
                                   $salida.="</tr>";
                                   
                                   if(!empty($remision))
                                   {
                                        $salida.="<tr align=\"left\">";
                                        $salida.="	<td class=\"label\">REMITIDO A : </td>";
                                        $salida.="	<td class=\"label\">".$remision."</td>";
                                        $salida.="</tr>";
                                   }
                                   
                                   if(!empty($concepto))
                                   {
                                        $salida.="<tr align=\"left\">";
                                        $salida.="	<td class=\"label\">CONCEPTO CONTROL: </td>";
                                        $salida.="	<td class=\"label\">".$concepto."</td>";
                                        $salida.="</tr>";
                                   }
                                   
                                   if(!empty($recepcion))
                                   {
                                        $salida.="<tr align=\"left\">";
                                        $salida.="	<td class=\"label\">ESPECIFIQUE RECEPCION : </td>";
                                        $salida.="	<td class=\"label\">".strtoupper($recepcion)."</td>";
                                        $salida.="</tr>";
                                   }

                                   $salida.="</table>";
                              }
                         break;
				}
			
				$salida2.="	<tr class=\"modulo_table_list_title\">";
				$salida2.="		<td width=\"90%\" align=\"left\"><font color=\"\">".($i+1).".".($j+1).". ".$valor1."</font></td>";
				$salida2.="		<td width=\"5%\" align=\"center\"><div id=\"edit$i$j\"><a href=\"javascript:GenerarI('capa$i$j','$i$j');Iniciar('$valor1','capa$i$j','$i$j');MostrarSpan('d2Container');\"><img src=\"".GetThemePath()."/images/editar.png\" border=\"0\" title=\"EDITAR\"></a></div></td>";
				
				$img="checkN.gif";
				if($_SESSION['EPICRISIS_OK']["$ingreso"]["$i$j"])
					$img="checkS.gif";

				
				$salida2.="		<td width=\"5%\" align=\"center\"><div id=\"ok$i$j\"><a href=\"javascript:ValorOk('ok$i$j','$i$j',$ok);\"><img src=\"".GetThemePath()."/images/$img\" border=\"0\" title=\"OK\"></a></div></td>";
				
				$salida2.="	</tr>";
				$salida2.="	<tr align=\"left\" height=\"25\">";
				$salida2.="		<td id=\"capa$i$j\" colspan=\"3\" class=\"modulo_list_claro\">";
				$salida2.="	$salida";
				$salida2.="		</td>";
				$salida2.="	</tr>";
				$salida2.="		<tr><td colspan=\"3\">&nbsp;</td></tr>";
				$j++;
				$cont++;
			}
			
			$salida1.="	<tr align=\"left\" class=\"hc_table_submodulo_list_title\">";
			$salida1.="		<td class=\"label\" colspan=\"3\"><font size=\"2\">".($i+1).". $key </font></td>";
			$salida1.="	</tr>";
			$salida1.="		$salida2";
			
			$i++;
		}

		$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
		$this->salida.="$salida1";
		$this->salida.="</table>";
		
		if(sizeof($_SESSION['EPICRISIS_OK']["$ingreso"])==SessionGetVar("Vector"))
		{
			SessionSetVar("listo_$ingreso",2);
		}
		
		$accionPP=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'=>'Epicrisis','salirmultiplesinsert'=>"1"));
		$this->salida.="<form name=\"forma_1\" action=\"$accionPP\" method=\"post\">";
		$this->salida.="<form>";
		
		
		$mostrarT=$reporte->GetJavaReport('hc','Remision_Interconsulta','ReporteRemision',array(),array('rpt_name'=>'Remision'.$ingreso,'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcionT=$reporte->GetJavaFunction();
		$this->salida.="		<center><label class=\"label\"><a href=\"javascript:$funcionT\">IMPRIMIR REMISION</a></label> <IMG src=\"".GetThemePath()."/images/imprimir.png\"> </center>";	
		$this->salida .= "$mostrarT";
		
		$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
		$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='d2Contents' class=\"d2Content\">\n";
		$this->salida .= "	</div>\n";
		$this->salida .= "</div>\n";
		
		$this->salida.= ThemeCerrarTablaSubModulo();
		
		$this->salida.=" <script>\n";
		$this->salida .= "	var hiZ = 2;\n";
		$this->salida .= "	var titulo = '';\n";
		$this->salida .= "	var contenedor = '';\n";
		$this->salida .= "	var capaActual;\n";
		$this->salida .= "	var daticos;";
		$this->salida .= "	var hctapA;";
		$this->salida .= "	var hctadB;";
		$this->salida .= "	var riesgo;";
		$this->salida .= "	var ex;";
		$this->salida .= "	var ey;";
		
		$this->salida .= "	function CargarF()\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  document.forma_1.submit();\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function GenerarI(capa,indice)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  capaActual = capa;\n";
		$this->salida .= "	  xajax_GeneracionI(capa,indice);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Primario(diag_id,primario,capa1,Xcapa1,capa2,Xcapa2,estado,indice)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  xajax_SeleccionPrimario(diag_id,primario,capa1,Xcapa1,capa2,Xcapa2,estado,indice);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Incluir(diag_id,estado,indice)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  xajax_SeleccionIncluir(diag_id,estado,indice);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function ValidarDatos(indice)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var mensaje='';\n";
		$this->salida .= "	  datico = xGetElementById('dato_text').value;\n";
		$this->salida .= "		if(!datico)\n";
		$this->salida .= "			mensaje='DATOS OBLIGATORIOS'\n";
		$this->salida .= "		document.getElementById('error').innerHTML=mensaje;\n";
		$this->salida .= "		if(!mensaje)\n";
		$this->salida .= "	  	xajax_GuardaDatos(''+datico,indice);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function DatosSalida(forma,indice,dim)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  var salida    = 0;\n";
		$this->salida .= "	  var remision  = forma.remision.value;\n";
          $this->salida .= "	  var concepto  = 0;\n";
          $this->salida .= "	  var recepcion = forma.recepcion.value;\n";          
		$this->salida .= "		for(var i=0;i<dim;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(forma.salida[i].checked){\n";
		$this->salida .= "				salida=forma.salida[i].value; break; \n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
          $this->salida .= "		for(var i=0;i<4;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(forma.trat_medico[i].checked){\n";
		$this->salida .= "				concepto=forma.trat_medico[i].value; break; \n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "	  xajax_Salida(salida,''+remision,indice,concepto,recepcion);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function ValidarDatosAnte(forma,indice)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		daticos=new Array();";
		$this->salida .= "		hctapA=new Array();";
		$this->salida .= "		hctadB=new Array();";
		$this->salida .= "		riesgo=new Array();";
		$this->salida .= "		var mensaje='',l=0,j=0;k=0;m=0;";
		
		$this->salida .= "		for(var i=0;i<forma.elements.length;i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			if(forma.elements[i].type=='hidden' && forma.elements[i].name=='hctap[]')\n";
		$this->salida .= "			{\n";
		$this->salida .= "				hctapA[j++]=forma.elements[i].value;\n";
		$this->salida .= "			}\n";
		$this->salida .= "			if(forma.elements[i].type=='hidden' && forma.elements[i].name=='hctad[]')\n";
		$this->salida .= "			{\n";
		$this->salida .= "				hctadB[k++]=forma.elements[i].value;\n";
		$this->salida .= "			}\n";
		$this->salida .= "			if(forma.elements[i].type=='hidden' && forma.elements[i].name=='sw_riesgo[]')\n";
		$this->salida .= "			{\n";
		$this->salida .= "				riesgo[m++]=forma.elements[i].value;\n";
		$this->salida .= "			}\n";
		$this->salida .= "			if(forma.elements[i].type=='textarea')\n";
		$this->salida .= "			{\n";
		$this->salida .= "				if(!forma.elements[i].value)\n";
		$this->salida .= "					mensaje='TODOS LOS CAMPOS SON OBLIGATORIOS';\n";
		$this->salida .= "				daticos[l]=forma.elements[i].value+'__'+hctapA[l]+'__'+hctadB[l]+'__'+riesgo[l];\n";
		$this->salida .= "				l++;\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "		xajax_GuardaDatos(daticos,indice);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function ValorOk(capa,indice,ok)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  capaActual = capa;\n";
		$this->salida .= "	  xajax_VistaOK(capa,indice,ok);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Iniciar(tit,capa,indice)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  titulo = 'titulo';\n";
		$this->salida .= "	  contenedor = 'd2Container';\n";
		$this->salida .= "	  capaActual = capa;\n";
		$this->salida .= "	  ex=400; ey='auto';\n";
		$this->salida .= "	  switch(''+indice)\n";
		$this->salida .= "	 	{\n";
		$this->salida .= "	  	case '02':\n";
		$this->salida .= "	  		ex=340;\n";
		$this->salida .= "	  		ey=320;\n";
		$this->salida .= "	  	break;\n";
		$this->salida .= "	  	case '03':\n";
		$this->salida .= "	  		ex=450;\n";
		$this->salida .= "	  		ey=300;\n";
		$this->salida .= "	  	break;\n";
		$this->salida .= "	  	case '05':\n";
		$this->salida .= "	  		ex=600;\n";
		$this->salida .= "	  		ey=200;\n";
		$this->salida .= "	  	break;\n";
		$this->salida .= "	  	case '21':\n";
		$this->salida .= "	  		ex=600;\n";
		$this->salida .= "	  		ey=200;\n";
		$this->salida .= "	  	break;\n";
		$this->salida .= "	  	case '22':\n";
		$this->salida .= "	  		ex=380;\n";
		$this->salida .= "	  		ey='auto';\n";
		$this->salida .= "	  	break;\n";
		$this->salida .= "	 	}\n";
		$this->salida .= "		document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
		$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
		$this->salida .= "		ele = xGetElementById('d2Contents');\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/10, xScrollTop());\n";
		$this->salida .= "	  xResizeTo(ele,ex,ey);\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/10, xScrollTop()+24);\n";
		$this->salida .= "	  xResizeTo(ele,ex,'auto');\n";
		$this->salida .= "		ele = xGetElementById(titulo);\n";
		$this->salida .= "	  xResizeTo(ele,(ex-20), 20);\n";
		$this->salida .= "		xMoveTo(ele, 0, 0);\n";
		$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$this->salida .= "		ele = xGetElementById('cerrar');\n";
		$this->salida .= "	  xResizeTo(ele,20, 20);\n";
		$this->salida .= "		xMoveTo(ele, (ex-20), 0);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  window.status = '';\n";
		$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
		$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
		$this->salida .= "	  ele.myTotalMX = 0;\n";
		$this->salida .= "	  ele.myTotalMY = 0;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  if (ele.id == titulo) {\n";
		$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
		$this->salida .= "	  }\n";
		$this->salida .= "	  else {\n";
		$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
		$this->salida .= "	  }  \n";
		$this->salida .= "	  ele.myTotalMX += mdx;\n";
		$this->salida .= "	  ele.myTotalMY += mdy;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function MostrarSpan(Seccion)\n";
		$this->salida .= "	{ \n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"\";\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Cerrar(Seccion)\n";
		$this->salida .= "	{ \n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"none\";\n";
		$this->salida .= "	}\n";
		
		$this->salida.=" </script>";
		
		return $this->salida;
	}
}
?>