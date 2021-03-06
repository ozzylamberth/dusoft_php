<?php
	/**************************************************************************************
	* $Id: EpicrisisX.php,v 1.6 2007/01/15 18:54:27 luis Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Luis Alejandro Vargas	
	**************************************************************************************/
	
	function VistaOk($capa,$indice,$ok)
	{
		$objResponse=new xajaxResponse();
		$path=SessionGetVar("RutaImg");
		
		$ingreso=SessionGetVar("Ingreso");
		
		if(!$ok)
		{
			$ok=1;
			$img="checkS.gif";
			$_SESSION['EPICRISIS_OK']["$ingreso"]["$indice"]=1;
		}
		else
		{
			$ok=0;
			$img="checkN.gif";
			$_SESSION['EPICRISIS_OK']["$ingreso"]["$indice"]=0;
		}
		
		if(sizeof($_SESSION['EPICRISIS_OK']["$ingreso"])==SessionGetVar("Vector"))
		{
			SessionSetVar("listo_$ingreso",1);
			$objResponse->call("CargarF");
		}
		
		$salida="<a href=\"javascript:ValorOk('$capa','$indice',$ok);\"><img src=\"$path/images/$img\" border=\"0\" title=\"OK\"></a>";
		
		$objResponse->assign("$capa","innerHTML",$salida);
		
		
		return $objResponse;
	}
	
	function GeneracionI($capa,$indice)
	{
		$objResponse=new xajaxResponse();
		
		$ingreso=SessionGetVar("Ingreso");
		$evolucion=SessionGetVar("Evolucion");
		$tipoidpaciente=SessionGetVar("tipoidpaciente");
		$paciente=SessionGetVar("paciente");
		
		SessionDelVar("Diagnostico");
		$epicrisis=new GeneracionEpicrisis();	
		
		switch("$indice")
		{
				case "00":
					$consulta=$epicrisis->GetDatosMotivosConsulta($ingreso);
				break;
				
				case "01":
					$consulta=$epicrisis->GetDatosEnfermedad($ingreso);
				break;
				
				case "02":
					$consulta=$epicrisis->GetDatosAntecedentesPersonales($ingreso,$evolucion,$paciente,$tipoidpaciente);
				break;
				
				case "03":
					$consulta[0]=$epicrisis->GetDatosExamenFisico($ingreso);
					if(!$consulta[0])
						$consulta[0]=$epicrisis->GetDatosExamenFisico($ingreso,1);
					
					$consulta[1]=$epicrisis->GetDatosExamenFisicoHallazgos($ingreso);
					if(!$consulta[1])
						$consulta[1]=$epicrisis->GetDatosExamenFisicoHallazgos($ingreso,1);
				break;
				
				case "04":
					$consulta=$epicrisis->GetDatosApoyosD($ingreso,$tipoidpaciente,$paciente);
				break;
				
				case "05":
					SessionSetVar("Diagnostico","ingreso");
					$consulta=$epicrisis->GetDiagnosticos($ingreso,"ingreso",'1','1');
					
				break;
				
				case "10":
					$consulta=$epicrisis->GetDatosEvolucion($ingreso);
				break;
				
				case "11":
					$consulta=$epicrisis->GetMedicamentosPacientes($ingreso);
				break;
				
				case "20":
					$consulta=$epicrisis->GetDatosPlanSeguimiento($ingreso);
				break;
				
				case "21":
					SessionSetVar("Diagnostico","egreso");
					$consulta=$epicrisis->GetDiagnosticos($ingreso,"egreso",'1','1');
					
				break;
				
				case "22":
					$consulta[0]=$epicrisis->GetTiposCausaSalida();
					$consulta[1]=$epicrisis->GetDatosCausaSalida($ingreso);
				break;
		}
		$salida=CreaHtml($consulta,$indice);
		
		$objResponse->assign("d2Contents","innerHTML",$salida);
		
		return $objResponse;
	}
	
	function DatosEvolucion($datos_evolucion,$indice)
	{
		$objResponse=new xajaxResponse();
		$ingreso=SessionGetVar("Ingreso");
		$epicrisis=new GeneracionEpicrisis();
		
		if(empty($datos_evolucion))
		{
			$objResponse->assign("capa_error$indice","innerHTML","<center><label class=\"label_error\">INGRESE LA INFORMACION DE LA EVOLUCION</label></center>");
		}
		else
		{
			$resultado=$epicrisis->InsertDatosEvolucion(utf8_decode($datos_evolucion),$ingreso);
			
			if($resultado)
			{
				$salida=plantillasMostrar(1,$datos_evolucion);
				$objResponse->assign("edit$indice","style.display","");
				$objResponse->assign("ok$indice","style.display","");
				$objResponse->assign("capa$indice","innerHTML",$salida);
			}
		}
		
		return $objResponse;
	}
	
	function PlanSeguimiento($datos,$indice)
	{
		$objResponse=new xajaxResponse();
		$ingreso=SessionGetVar("Ingreso");
		$epicrisis=new GeneracionEpicrisis();
		
		if(empty($datos))
		{
			$objResponse->assign("capa_error$indice","innerHTML","<center><label class=\"label_error\">INGRESE LA INFORMACION DEL PLAN DE SEGUIMIENTO</label></center>");
		}
		else
		{
			$resultado=$epicrisis->InsertDatosPlanSeguimiento(utf8_decode($datos),$ingreso);
				
			if($resultado)
			{
				$salida=plantillasMostrar(1,$datos);
				$objResponse->assign("edit$indice","style.display","");
				$objResponse->assign("ok$indice","style.display","");
				$objResponse->assign("capa$indice","innerHTML",$salida);
			}
		}
		
		return $objResponse;
	}
	
	function Salida($dato_salida,$remision,$indice)
	{
		$objResponse=new xajaxResponse();
		$ingreso=SessionGetVar("Ingreso");
		$epicrisis=new GeneracionEpicrisis();

		if(empty($dato_salida))
		{
			$objResponse->assign("capa_error$indice","innerHTML","<center><label class=\"label_error\">SELECCIONE CAUSA</label></center>");
		}
		else
		{
			if(!$remision)
				$remision="NULL";
			else
				$remision="'$remision'";

			$datos[0]=$dato_salida;
			$datos[1]=utf8_decode($remision);

			$resultado=$epicrisis->InsertDatosSalida($datos,$ingreso);
			
			if($resultado)
			{
				$consulta=$epicrisis->GetDatosCausaSalida($ingreso);
				$salida=plantillasMostrar(4,$consulta);
				$objResponse->assign("edit$indice","style.display","");
				$objResponse->assign("ok$indice","style.display","");
				$objResponse->assign("capa$indice","innerHTML",$salida);
			}
		}
		
		return $objResponse;
	}
	
	function CreaHtml($datos,$indice)
	{
		$salida="";
		$dato="";
		
		switch("$indice")
		{
				case "00":
					foreach($datos as $consulta)
						$dato=$consulta['descripcion1'];
					$plantilla=1;
				break;
				
				case "01":
					foreach($datos as $consulta)
						$dato=$consulta['enfermedadactual'];
					$plantilla=1;
				break;
				
				case "02":
					$dato=$datos;
					$plantilla=2;
				break;
				
				case "03":
					$dato=$datos;
					$plantilla=5;
				break;
				
				case "04":
					foreach($datos as $consulta)
						$dato=$consulta['descripcion1'];
					$plantilla=1;
				break;
				
				case "05":/*diagnosticos de ingreso*/
					$dato=$datos;
					$plantilla=3;
				break;
				
				case "10":
					foreach($datos as $consulta)
						$dato.=$consulta['descripcion_evolucion'];

					$plantilla=1;
				break;
				
				case "11":
					foreach($datos as $consulta)
						$dato=$consulta['descripcion1'];
					$plantilla=1;
				break;
				
				case "20":
					foreach($datos as $consulta)
						$dato=$consulta['plan_seguimiento'];
					$plantilla=1;
				break;
				
				case "21":/*diagnosticos de egreso*/
					$dato=$datos;
					$plantilla=3;
				break;
				
				case "22":
					$dato=$datos;
					$plantilla=4;
				break;
		}
		
		$salida=plantillasEditar($plantilla,$dato,$indice);
		
		return $salida;
	}
	
	function plantillasEditar($plantilla,$datos,$indice)
	{
		$salida="";
		$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
		$path=SessionGetVar("RutaImg");
		
		switch($plantilla)
		{
			case 1:
				$salida.="<table align=\"center\" border=\"0\" width=\"100%\"  class=\"modulo_table_list\">";
				$salida.="	<tr class=\"modulo_table_list_title\">";
				$salida.="		<td>EDITAR DATOS</td>";
				$salida.="	</tr>";
				$salida.="	<tr class=\"modulo_list_claro\">";
				$salida.="		<td><textarea name=\"dato_text\" id=\"dato_text\" class=\"input-text\" cols=\"73\" rows=\"7\">".strtoupper(utf8_encode($datos))."</textarea></td>";
				$salida.="	</tr>";
				$salida.="	<tr class=\"modulo_list_claro\">";
				$salida.="		<td align=\"center\"><input type=\"button\" name=\"Aceptar\" class=\"input-submit\" value=\"ACEPTAR\" onclick=\"ValidarDatos('$indice');Cerrar('d2Container');\"></td>";
				$salida.="	</tr>";
				$salida.="</table>";
			break;
			case 2:
				$m=0;
				$salida.="<form name=\"forma_ante\" action=\"\" method=\"POST\">";
				$salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
				foreach($datos as $key1=>$nivel1)
				{
					$n=0;
					$html1="";
					foreach($nivel1 as $key2=>$nivel2)
					{
						$tabla="";
						$tabla1="";
						$l=0;
						foreach($nivel2 as $key3=>$nivel3)
						{
							$c=",  ";
							if(sizeof($nivel2)==$l+1)
								$c="";
								
							if($nivel3['sw_riesgo']=='1')
								$estado="Si";
							else
								$estado="No";
								
							$tabla1.="$estado - ".$nivel3['detalle']."$c";
							$l++;
						}
						$tabla.="<table border=\"0\" width=\"100%\">";
						$tabla.="		<textarea name=\"descripcion[]\" class=\"input-text\" cols=\"35\" rows=\"3\">".utf8_encode($tabla1)."</textarea>";
						$tabla.="</table>";
						
						$html1.="<tr class=\"modulo_list_oscuro\">";
						$html1.="	<td class=\"label\">$key2</td>";
						$html1.="	<td>$tabla</td>";
						$html1.="</tr>";
						
						$salida.="<input type=\"hidden\" name=\"hctap[]\" value=\"".$nivel2[$key3]['hctap']."\">";
						$salida.="<input type=\"hidden\" name=\"hctad[]\" value=\"".$nivel2[$key3]['hctad']."\">";
						$salida.="<input type=\"hidden\" name=\"sw_riesgo[]\" value=\"".$nivel2[$key3]['sw_riesgo']."\">";
						$n++;
					}
					$salida.="<tr rowspan=\"".($n+1)."\" class=\"modulo_table_list_title\">";
					$salida.="	<td colspan=\"3\" width=\"50%\">$key1</td>";
					$salida.="</tr>";
					$salida.="	$html1";
					$m++;
				}
				$salida.="	<tr class=\"modulo_list_claro\">";
				$salida.="		<td align=\"center\" colspan=\"3\"><input type=\"button\" name=\"Aceptar\" class=\"input-submit\" value=\"ACEPTAR\" onclick=\"ValidarDatosAnte(this.form,'$indice');Cerrar('d2Container');\"></td>";
				$salida.="	</tr>";
				$salida.="</table>";
				$salida.="</form>";
			break;
			
			case 3:
				$salida.="<table align=\"center\" border=\"0\" width=\"100%\"  class=\"modulo_table_list\">";
				$salida.="<tr class=\"modulo_table_list_title\">";
				$salida.="	<td>CODIGO</td>";
				$salida.="	<td>DIAGNOSTICO</td>";
				$salida.="	<td>TIPO DIAGNOSTICO</td>";
				$salida.="	<td>PRIMARIO</td>";
				$salida.="	<td>INCLUIR</td>";
				$salida.="</tr>";
				$l=0;
				foreach($datos as $nivel1)
				{
					$salida.="<tr class=\"modulo_list_claro\" align=\"center\">";
					$salida.="	<td class=\"label\">".$nivel1['diagnostico_id']."</td>";
					$salida.="	<td class=\"label\">".$nivel1['diagnostico_nombre']."</td>";
					$salida.="	<td class=\"label\">".$nivel1['tipo_diag']."</td>";
					$class="label";
					if($nivel1['sw_principal'])
					{
						$p="<img src=\"$path/images/checksi.png\" border=\"0\">";
						$p1="";
						$diag_id=$nivel1['diagnostico_id'];
						$capaP="capa$l";
						$capaI="Xcapa$l";
					}
					else
					{
						$p="<a href=\"javascript:Primario('".$nivel1['diagnostico_id']."','".$diag_id."','$capaP','$capaI','capa$l','Xcapa$l','".$nivel1['estado']."','$indice');\"><img src=\"$path/images/checkno.png\" border=\"0\"></a>";
						$nombre="checksi.png";
						if(!$nivel1['estado'])
							$nombre="checkno.png";
						$p1="<a href=\"javascript:Incluir('".$nivel1['diagnostico_id']."','".$nivel1['estado']."','$indice');\"><img src=\"$path/images/$nombre\" border=\"0\"></a>";
					}

					$salida.="	<td class=\"label\" id=\"capa$l\">$p</td>";
					$salida.="	<td class=\"label\" id=\"Xcapa$l\">$p1</td>";
					$salida.="</tr>";
					$l++;
				}
				$salida.="</table>";
			break;
			
			case 4:
				$b=true;
				$salida.="<form name=\"forma_dat2\" action=\"\" method=\"post\"  class=\"modulo_table_list\">";
				$salida.="<table align=\"center\" border=\"0\" width=\"60%\">";
				$salida.="<tr class=\"modulo_table_list_title\"> ";
				$salida.="	<td colspan=\"2\">TIPO CAUSA</td>";
				$salida.="	<td>REMITIDO A</td>";
				foreach($datos[0] as $nivel1)
				{
					$salida.="<tr class=\"modulo_list_claro\">";
					$salida.="	<td class=\"label\">".$nivel1['descripcion']."</td>";
					foreach($datos[1] as $nivel2)
					{
						$check="";
						if($nivel1['hc_epicrisis_tipo_causa_salida_id']==$nivel2['tipo_causa_id'])
							$check="checked";
							
						$salida.="	<td class=\"label\"><input type=\"radio\" name=\"salida\" value=\"".$nivel1['hc_epicrisis_tipo_causa_salida_id']."\" $check></td>";
						if($b)
						{
							$salida.="	<td class=\"label\" rowspan=\"".sizeof($datos[0])."\"><textarea name=\"remision\"  class=\"input-text\" cols=\"50%\" rows=\"3\">".utf8_encode($nivel2['descripcion_remision'])."</textarea></td>";
							$b=false;
						}
					}
					$salida.="</tr>";
				}
				$salida.="	<tr class=\"modulo_list_claro\">";
				$salida.="		<td align=\"center\" colspan=\"3\"><input type=\"button\" name=\"Guardar\" class=\"input-submit\" value=\"GUARDAR\" onclick=\"DatosSalida(this.form,'$indice');Cerrar('d2Container');\"></td>";
				$salida.="	</tr>";
				$salida.="</table>";
				$salida.="</form>";
			break;
			
			case 5:
				$salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
				foreach($datos[0] as $key1=>$nivel1)
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
					$salida.="<tr class=\"modulo_list_claro\">";
					$salida.="	<td width=\"20%\" $styl1 class=\"modulo_list_oscuro\">".strtoupper($key1)."</td>";
					//$salida.="	<td><textarea name=\"sw_normales[]\" class=\"input-text\" cols=\"58\" rows=\"1\">$estado</textarea></td>";
					$salida.="	<td>$estado</td>";
					$salida.="</tr>";
				}
				$l=0;
				$salida.="<tr align=\"center\" $styl1>";
				$salida.="	<td colspan=\"2\" class=\"modulo_list_oscuro\">HALLAZGOS</td>";
				$salida.="</tr>";
				$salida.="<tr class=\"label\">";
				foreach($datos[1] as $nivel1)
				{
					$c=",  ";
					if(sizeof($datos[1])==$l+1)
						$c="";
						
					$hallazgo.="".strtoupper($nivel1['hallazgo'])."$c";
						
					$l++;
				}
				$salida.="	<td colspan=\"2\" width=\"100%\"  class=\"modulo_list_claro\"><textarea name=\"dato_text\" id=\"dato_text\" class=\"input-text\" cols=\"79\" rows=\"3\">".utf8_encode($hallazgo)."</textarea></td>";
				$salida.="</tr>";
				$salida.="	<tr class=\"modulo_list_claro\">";
				$salida.="		<td align=\"center\" colspan=\"3\"><input type=\"button\" name=\"Guardar\" class=\"input-submit\" value=\"GUARDAR\" onclick=\"ValidarDatos('$indice');Cerrar('d2Container');\"></td>";
				$salida.="	</tr>";
				$salida.="</table>";
			break;
			
		}
		return $salida;
	}
	
	function plantillasMostrar($plantilla,$datos)
	{
		$salida="";
		$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
		switch($plantilla)
		{
			case 1:
				$salida.="<table align=\"center\" border=\"0\" width=\"100%\"  class=\"modulo_table_list\">";
				$salida.="	<tr>";
				$salida.="		<td><label class=\"label\">".strtoupper($datos)."</label><td>";
				$salida.="	</tr>";
				$salida.="</table>";
			break;
			case 2:
				$m=0;
				$salida="<table align=\"center\" border=\"0\" width=\"100%\"  class=\"modulo_table_list\">";
				foreach($datos as $key1=>$nivel1)
				{
					$n=0;
					$html1="";
					foreach($nivel1 as $key2=>$nivel2)
					{
						$tabla1="";
						$l=0;
						foreach($nivel2 as $key3=>$nivel3)
						{
							$c=",  ";
							if(sizeof($nivel2)==$l+1)
								$c="";
							if($nivel3['sw_riesgo']=='1')
								$estado="Si";
							else
								$estado="No";
							
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
			break;
			case 3:
				$salida.="<table align=\"center\" border=\"0\" width=\"100%\"  class=\"modulo_table_list\">";
				$salida.="<tr class=\"modulo_table_list_title\">";
				$salida.="	<td>CODIGO</td>";
				$salida.="	<td>DIAGNOSTICO</td>";
				$salida.="	<td>TIPO DIAGNOSTICO</td>";
				$salida.="	<td>PRIMARIO</td>";
				$salida.="</tr>";
				foreach($datos as $nivel1)
				{
					$salida.="<tr class=\"modulo_list_claro\">";
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
			break;
			case 4:
				$salida.="<table align=\"left\" border=\"0\"  witdh=\"100%\" class=\"modulo_table_list\">";
				$salida.="<tr>";
				$salida.="	<td class=\"label\" width=\"10%\">TIPO CAUSA : </td>";
				foreach($datos as $nivel1)
				{
					$causa.=$nivel1['causa'];
					$remision.=$nivel1['descripcion_remision'];
				}
				$salida.="	<td class=\"label\">".$causa."</td>";
				$salida.="</tr>";
				
				if(!empty($remision))
				{
					$salida.="<tr align=\"left\">";
					$salida.="	<td class=\"label\">REMITIDO A : </td>";
					$salida.="	<td class=\"label\">".utf8_encode($remision)."</td>";
					$salida.="</tr>";
				}
				$salida.="</table>";
			break;
			
			case 5:
				$salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
				foreach($datos[0] as $key1=>$nivel1)
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
				foreach($datos[1] as $nivel1)
				{
					$c=",  ";
					if(sizeof($datos[1])==$l+1)
						$c="";
						
					$salida.="".strtoupper($nivel1['hallazgo'])."$c";
						
					$l++;
				}
				$salida.="	</td>";
				$salida.="</tr>";
				$salida.="</table>";
			break;
			
		}
		return $salida;
	}
	
	function SeleccionPrimario($diag_id,$diag_id_primario,$AcapaP,$AcapaI,$XcapaP,$XcapaI,$estado,$indice)
	{
		$objResponse=new xajaxResponse();
		
		$ingreso=SessionGetVar("Ingreso");
		$path=SessionGetVar("RutaImg");
		
		$epicrisis=new GeneracionEpicrisis();	
		$nombre=SessionGetVar("Diagnostico");
		$up=$epicrisis->UpdateDiagnoticoPrimario($ingreso,$nombre,$diag_id,$diag_id_primario,$estado);
		
		if($up)
		{
			$consulta=$epicrisis->GetDiagnosticos($ingreso,$nombre,'1','1');
			$salida1=plantillasEditar(3,$consulta,$indice);
			$objResponse->assign("d2Contents","innerHTML",$salida1);
			
			$consulta=$epicrisis->GetDiagnosticos($ingreso,$nombre,'1');
			$salida2=plantillasMostrar(3,$consulta);
			$objResponse->assign("capa$indice","innerHTML",$salida2);
		}

		return $objResponse;
	}
	
	function SeleccionIncluir($diag_id,$estado,$indice)
	{
		$objResponse=new xajaxResponse();
		
		$ingreso=SessionGetVar("Ingreso");
		$path=SessionGetVar("RutaImg");
		
		$epicrisis=new GeneracionEpicrisis();	
		$nombre=SessionGetVar("Diagnostico");
		$up=$epicrisis->UpdateIncluirDiagnostico($ingreso,$nombre,$diag_id,$estado);
		if($up)
		{
			$consulta=$epicrisis->GetDiagnosticos($ingreso,$nombre,'1','1');
			$salida1=plantillasEditar(3,$consulta,$indice);
			$objResponse->assign("d2Contents","innerHTML",$salida1);
			
			$consulta=$epicrisis->GetDiagnosticos($ingreso,$nombre,'1');
			$salida2=plantillasMostrar(3,$consulta);
			$objResponse->assign("capa$indice","innerHTML",$salida2);
		}

		return $objResponse;
	}
	
	
	function GuardaDatos($datos,$indice)
	{
		$objResponse=new xajaxResponse();
		
		$ingreso=SessionGetVar("Ingreso");
		$paciente=SessionGetVar("paciente");
		$tipoidpaciente=SessionGetVar("tipoidpaciente");
		
		$epicrisis=new GeneracionEpicrisis();	
		
		$salida="";
		$dato="";
	
		switch("$indice")
		{
				case "00":/*motivo consulta*/
					$motivo_consulta=$epicrisis->GetDatosMotivosConsulta($ingreso);
					$resultado=$epicrisis->InsertDatosMotivoConsulta(utf8_decode($datos),$ingreso,$motivo,0);
					if($resultado)
					{
						$html=plantillasMostrar(1,$datos);
						$objResponse->assign("capa$indice","innerHTML",$html);
					}
				break;
				
				case "01":/*estado general y enfermedad actual*/
					$enfermedad=$epicrisis->GetDatosEnfermedad($ingreso);
					$resultado=$epicrisis->InsertDatosMotivoConsulta(utf8_decode($datos),$ingreso,$enfermedad,1);
					if($resultado)
					{
						$html=plantillasMostrar(1,$datos);
						$objResponse->assign("capa$indice","innerHTML",$html);
					}
				break;
				
				case "02":/*Antecendentes Personales*/
					$resultado=$epicrisis->InsertDatosAntecedentesPersonales(utf8_decode($datos),$ingreso);
					$antecedentes=$epicrisis->GetDatosAntecedentesPersonales($ingreso,$evolucion,$paciente,$tipoidpaciente,1);
					$html=plantillasMostrar(2,$antecedentes);
					$objResponse->assign("capa$indice","innerHTML",$html);
				break;
				
				case "03":

					$resultado=$epicrisis->InsertDatosExamenFisicoHallazgos(utf8_decode($datos),$ingreso);
					
					$consulta[0]=$epicrisis->GetDatosExamenFisico($ingreso);
					if(!$consulta[0])
						$consulta[0]=$epicrisis->GetDatosExamenFisico($ingreso,1);
					
					$consulta[1]=$epicrisis->GetDatosExamenFisicoHallazgos($ingreso);
					if(!$consulta[1])
						$consulta[1]=$epicrisis->GetDatosExamenFisicoHallazgos($ingreso,1);
					
					$html=plantillasMostrar(5,$consulta);
					$objResponse->assign("capa$indice","innerHTML",$html);
					
				break;
				
				case "04":
					$resultado=$epicrisis->InsertDatosApoyosD(utf8_decode($datos),$ingreso);
					if($resultado)
					{
						$html=plantillasMostrar(1,$datos);
						$objResponse->assign("capa$indice","innerHTML",$html);
					}
				break;
				
				case "05":/*diagnosticos de ingreso*/
				break;
				
				case "10":/*datos de la evolucion*/
					$resultado=$epicrisis->InsertDatosEvolucion(utf8_decode($datos),$ingreso);
					if($resultado)
					{
						$html=plantillasMostrar(1,$datos);
						$objResponse->assign("capa$indice","innerHTML",$html);
					}
					
				break;
				
				case "11":/*medicamentos*/
					$resultado=$epicrisis->InsertMedicamentosPacientes(utf8_decode($datos),$ingreso);
					if($resultado)
					{
						$html=plantillasMostrar(1,$datos);
						$objResponse->assign("capa$indice","innerHTML",$html);
					}
				break;
				
				case "20":/*plan seguimiento*/
					$resultado=$epicrisis->InsertDatosPlanSeguimiento(utf8_decode($datos),$ingreso);
					if($resultado)
					{
						$html=plantillasMostrar(1,$datos);
						$objResponse->assign("capa$indice","innerHTML",$html);
					}
				break;
				
				case "21":/*diagnosticos de egreso*/

				break;
		}
		
		return $objResponse;
	}
?>