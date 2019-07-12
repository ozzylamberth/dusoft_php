<?php
	/********************************************************************************* 
 	* $Id: hc_RegistroEvolucionGestacion_RegistroEG_HTML.class.php,v 1.3 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RegistroEvolucionGestacion
	* 
 	**********************************************************************************/

	class RegistroEG_HTML
	{

		function RegistroEG_HTML()
		{
			$this->redcolorf="#990000";
			return true;
		}
		
		function frmHistoria($datosConsulta,$vector,$semanas)
		{
			if(sizeof($datosConsulta)>0)
			{
				$this->salida.="	<br><table align=\"center\" class=\"hc_table_submodulo_list\" border=\"1\" width=\"100%\">";
				$this->salida.="		<tr class=\"modulo_table_list_title\">";
				$this->salida.="			<td width=\"90%\">Rango Semanas</td>";
				$num_sem=sizeof($semanas);
				for($j=0;$j<sizeof($datosConsulta);$j++)
				{
					for($i=0;$i<$num_sem;$i++)
					{
						if($datosConsulta[$j][semana_actual]>=$semanas[$i][rango_inicio] AND $datosConsulta[$j][semana_actual]<=$semanas[$i][rango_fin])
						{
							$this->salida.="			<td colspan=\"2\" width=\"9%\">".$semanas[$i][rango_inicio]."-".$semanas[$i][rango_fin]."</td>";;
						}
					}
				}
				$this->salida.="		</tr>";
				$this->salida.="		<tr class=\"modulo_table_list_title\">";
				$this->salida.="			<td>&nbsp;</td>";
				for($j=0;$j<sizeof($datosConsulta);$j++)
				{
					for($i=0;$i<$num_sem;$i++)
					{
						if($datosConsulta[$j][semana_actual]>=$semanas[$i][rango_inicio] AND $datosConsulta[$j][semana_actual]<=$semanas[$i][rango_fin])
						{
							$this->salida.="			<td width=\"4.5%\">Si</td>";
							$this->salida.="			<td width=\"4.5%\">No</td>";
						}
					}
				}
				$this->salida.="		</tr>";
	
				$k=0;
				
				for($i=0;$i<sizeof($vector);$i++)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
					
					if($vector[$i]!="C" AND $vector[$i]!="P")
					{
						$this->salida.="		<tr class=\"$estilo\">";
						$this->salida.="			<td><label class=\"".$this->SetStyle($vector[$i])."\">".$vector[$i]."</label></td>";
	
						for($j=0;$j<$num_sem;$j++)
						{
							$ban=0;
							for($l=0;$l<sizeof($datosConsulta);$l++)
							{
								if($datosConsulta[$l][semana_sugerida]==$semanas[$j][rango_media])
								{
									switch($i)
									{
										case 0:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".substr($datosConsulta[$l][fecha_registro],0,10)."</label></td>";
										break;
										case 1:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][semana_actual]."</label></td>";
										break;
										case 2:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][peso]."</label></td>";
										break;
										case 3:
												$ta_alta=$datosConsulta[$l][taalta];
												$ta_baja=$datosConsulta[$l][tabaja];
												
												$styl1="";
												if(!empty($ta_alta) AND $ta_alta>139)
													$styl1 = " style=\"color:#990000;font-weight : bold; \" ";
												
												$styl2="";
												if(!empty($ta_baja) AND $ta_baja<55)
													$styl2 = " style=\"color:#990000;font-weight : bold; \" ";		
										
												$this->salida.="<td align=\"center\" colspan=\"2\"><label $styl1 class=\"label\">".$ta_alta."</label> / <label $styl2 class=\"label\">".$ta_baja."</label></td>";
										break;
										case 4:
												switch($datosConsulta[$l][mamas])
												{
													case 1:
														$estado="Normal";
													break;
													case 2:
														$estado="<font color=\"".$this->redcolorf."\">Anormal</font>";
													break;
												}
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
										break;
										case 5:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][altura_uterina]."</label></td>";
										break;
										case 6:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][fcf]."</label></td>";
										break;
										case 7:
												switch($datosConsulta[$l][presentacion_fetal])
												{
													case 1:
														$estado="Podalica";
													break;
													case 2:
														$estado="Cefalica";
													break;
													case 3:
														$estado="Transversa";
													break;
													case 4:
														$estado="N.A";
													break;
												}
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
										break;
										case 8:
												switch($datosConsulta[$l][estado_nutricional])
												{
													case 1:
														$estado="Normal";
													break;
													case 2:
														$estado="<font color=\"".$this->redcolorf."\">Bajo Peso</font>";
													break;
													case 3:
														$estado="<font color=\"".$this->redcolorf."\">Sobrepeso</font>";
													break;
												}
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
										break;
										case 9:
												if($datosConsulta[$l][movimientos_fetales]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 10:
												if($datosConsulta[$l][actividad_uterina]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 11:
												if($datosConsulta[$l][especuloscopia]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 12:
												if($datosConsulta[$l][clasificacion_riesgo]==1)
													$estado="BAJO";
												else
													$estado="<font color=\"".$this->redcolorf."\">ALTO</font>";
											
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 13:
												if($datosConsulta[$l][riesgo_biologico]>=3)
													$estado="<font color=\"".$this->redcolorf."\">".$datosConsulta[$l][riesgo_biologico]."</font>";
												else
													$estado=$datosConsulta[$l][riesgo_biologico];
													
													$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 14:
												if($datosConsulta[$l][riesgo_psicosocial]>0)
													$estado="<font color=\"".$this->redcolorf."\">".$datosConsulta[$l][riesgo_psicosocial]."</font>";
												else
													$estado=$datosConsulta[$l][riesgo_psicosocial];
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										//case 15: pruebas de laboratorio*//
										case 16:
												if($datosConsulta[$l][hospitalizacion_antes_cpn]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 17:
												if($datosConsulta[$l][asesoria_pretest]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 18:
												if($datosConsulta[$l][asesoria_postest]==1)
												{
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
													$post2=1;
												}
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 19:
												if($datosConsulta[$l][vacunacion_tt]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										//case 20: codigos especialidades,medicamentos,cargos*//
										case 21:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$datosConsulta[$l][fecha_ideal_proxima_cita]."</font></label></td>";
										break;
										case 22:
												if($datosConsulta[$l][cierre_caso]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
												
										break;
										case 23:
											$vectorR=array("N.A","ITU","Vaginosis","Diabetes","Hipertension",
																					"Preeclampsia","RCIU","Polihidramnios");
																					
											
											$this->salida.="	<td align=\"center\" colspan=\"2\"><label class=\"label\">";
											for($h=0;$h<sizeof($vectorR);$h++)
											{
												if($datosConsulta[$l][riesgo_especifico1]==$h+1)
													$this->salida.=" ".$vectorR[$h]."<br>";
												if($datosConsulta[$l][riesgo_especifico2]==$h+1)
													$this->salida.=" ".$vectorR[$h]."<br>";
												if($datosConsulta[$l][riesgo_especifico3]==$h+1)
													$this->salida.=" ".$vectorR[$h]."";
											}
											$this->salida.="</td>";
										break;
									}
								}
							}
						}
					}
					$k++;
				}
			}
			else
				return false;
		
			return $this->salida;
		}
		
		function frmConsulta($datosConsulta,$vector,$semanas)
		{
			if(sizeof($datosConsulta)>0)
			{
				$this->salida.="	<br><table align=\"center\" class=\"hc_table_submodulo_list\" border=\"1\" width=\"100%\">";
				$this->salida.="		<tr class=\"modulo_table_list_title\">";
				$this->salida.="			<td width=\"90%\">Rango Semanas</td>";
				$num_sem=sizeof($semanas);
				for($j=0;$j<sizeof($datosConsulta);$j++)
				{
					for($i=0;$i<$num_sem;$i++)
					{
						if($datosConsulta[$j][semana_actual]>=$semanas[$i][rango_inicio] AND $datosConsulta[$j][semana_actual]<=$semanas[$i][rango_fin])
						{
							$this->salida.="			<td colspan=\"2\" width=\"9%\">".$semanas[$i][rango_inicio]."-".$semanas[$i][rango_fin]."</td>";;
						}
					}
				}
				$this->salida.="		</tr>";
				$this->salida.="		<tr class=\"modulo_table_list_title\">";
				$this->salida.="			<td>&nbsp;</td>";
				for($j=0;$j<sizeof($datosConsulta);$j++)
				{
					for($i=0;$i<$num_sem;$i++)
					{
						if($datosConsulta[$j][semana_actual]>=$semanas[$i][rango_inicio] AND $datosConsulta[$j][semana_actual]<=$semanas[$i][rango_fin])
						{
							$this->salida.="			<td width=\"4.5%\">Si</td>";
							$this->salida.="			<td width=\"4.5%\">No</td>";
						}
					}
				}
				$this->salida.="		</tr>";
	
				$k=0;
				
				for($i=0;$i<sizeof($vector);$i++)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
					
					if($vector[$i]!="C" AND $vector[$i]!="P")
					{
						$this->salida.="		<tr class=\"$estilo\">";
						$this->salida.="			<td><label class=\"".$this->SetStyle($vector[$i])."\">".$vector[$i]."</label></td>";
	
						for($j=0;$j<$num_sem;$j++)
						{
							$ban=0;
							for($l=0;$l<sizeof($datosConsulta);$l++)
							{
								if($datosConsulta[$l][semana_sugerida]==$semanas[$j][rango_media])
								{
									switch($i)
									{
										case 0:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".substr($datosConsulta[$l][fecha_registro],0,10)."</label></td>";
										break;
										case 1:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][semana_actual]."</label></td>";
										break;
										case 2:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][peso]."</label></td>";
										break;
										case 3:
												$ta_alta=$datosConsulta[$l][taalta];
												$ta_baja=$datosConsulta[$l][tabaja];
												
												$styl1="";
												if(!empty($ta_alta) AND $ta_alta>139)
													$styl1 = " style=\"color:#990000;font-weight : bold; \" ";
												
												$styl2="";
												if(!empty($ta_baja) AND $ta_baja<55)
													$styl2 = " style=\"color:#990000;font-weight : bold; \" ";		
										
												$this->salida.="<td align=\"center\" colspan=\"2\"><label $styl1 class=\"label\">".$ta_alta."</label> / <label $styl2 class=\"label\">".$ta_baja."</label></td>";
										break;
										case 4:
												switch($datosConsulta[$l][mamas])
												{
													case 1:
														$estado="Normal";
													break;
													case 2:
														$estado="<font color=\"".$this->redcolorf."\">Anormal</font>";
													break;
												}
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
										break;
										case 5:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][altura_uterina]."</label></td>";
										break;
										case 6:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][fcf]."</label></td>";
										break;
										case 7:
												switch($datosConsulta[$l][presentacion_fetal])
												{
													case 1:
														$estado="Podalica";
													break;
													case 2:
														$estado="Cefalica";
													break;
													case 3:
														$estado="Transversa";
													break;
													case 4:
														$estado="N.A";
													break;
												}
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
										break;
										case 8:
												switch($datosConsulta[$l][estado_nutricional])
												{
													case 1:
														$estado="Normal";
													break;
													case 2:
														$estado="<font color=\"".$this->redcolorf."\">Bajo Peso</font>";
													break;
													case 3:
														$estado="<font color=\"".$this->redcolorf."\">Sobrepeso</font>";
													break;
												}
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
										break;
										case 9:
												if($datosConsulta[$l][movimientos_fetales]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 10:
												if($datosConsulta[$l][actividad_uterina]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 11:
												if($datosConsulta[$l][especuloscopia]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 12:
												if($datosConsulta[$l][clasificacion_riesgo]==1)
													$estado="BAJO";
												else
													$estado="<font color=\"".$this->redcolorf."\">ALTO</font>";
											
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 13:
												if($datosConsulta[$l][riesgo_biologico]>=3)
													$estado="<font color=\"".$this->redcolorf."\">".$datosConsulta[$l][riesgo_biologico]."</font>";
												else
													$estado=$datosConsulta[$l][riesgo_biologico];
													
													$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 14:
												if($datosConsulta[$l][riesgo_psicosocial]>0)
													$estado="<font color=\"".$this->redcolorf."\">".$datosConsulta[$l][riesgo_psicosocial]."</font>";
												else
													$estado=$datosConsulta[$l][riesgo_psicosocial];
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										//case 15: pruebas de laboratorio*//
										case 16:
												if($datosConsulta[$l][hospitalizacion_antes_cpn]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 17:
												if($datosConsulta[$l][asesoria_pretest]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 18:
												if($datosConsulta[$l][asesoria_postest]==1)
												{
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
													$post2=1;
												}
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										case 19:
												if($datosConsulta[$l][vacunacion_tt]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
										break;
										//case 20: codigos especialidades,medicamentos,cargos*//
										case 21:
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$datosConsulta[$l][fecha_ideal_proxima_cita]."</font></label></td>";
										break;
										case 22:
												if($datosConsulta[$l][cierre_caso]==1)
													$estado="<font color=\"".$this->redcolorf."\">Si</font>";
												else
													$estado="No";
													
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
												
										break;
										case 23:
											$vectorR=array("N.A","ITU","Vaginosis","Diabetes","Hipertension",
																					"Preeclampsia","RCIU","Polihidramnios");
																					
											
											$this->salida.="	<td align=\"center\" colspan=\"2\"><label class=\"label\">";
											for($h=0;$h<sizeof($vectorR);$h++)
											{
												if($datosConsulta[$l][riesgo_especifico1]==$h+1)
													$this->salida.=" ".$vectorR[$h]."<br>";
												if($datosConsulta[$l][riesgo_especifico2]==$h+1)
													$this->salida.=" ".$vectorR[$h]."<br>";
												if($datosConsulta[$l][riesgo_especifico3]==$h+1)
													$this->salida.=" ".$vectorR[$h]."";
											}
											$this->salida.="</td>";
										break;
									}
								}
							}
						}
					}
					$k++;
				}
			}
			else
				return false;
		
			return $this->salida;
		}
		
		
		function frmForma($vector,$semanas,$datosConsulta,$datosEvolucion,$datosEvolucionregistros,$datosEspecialidad,$datosprofesional,$datossignos,$datosRegistros,$puntajeRiesgos,$puntajeTotalRiesgos,$proximaCita,$semana_gestante,$cierre,$fcp,$lab,$resultados,$pruebasLab,$datossignosConsulta,$datos_sistemas)
		{
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$paso=SessionGetVar("Paso");
			$plan=SessionGetVar("Plan");
			$ingreso=SessionGetVar("Ingreso");
			$datosPaciente=SessionGetvar("DatosPaciente");
			$pfj=SessionGetVar("Prefijo");
			
			
			$_SESSION['frmprefijo']=$pfj;
			$_SESSION['datospaciente']=$datosPaciente;
			$_SESSION['ingreso']=$ingreso;
			$_SESSION['evolucion']=$evolucion;
			$_SESSION['paso']=$paso;
			$_SESSION['plan']=$plan;

			$this->salida.= ThemeAbrirTablaSubModulo('REGISTRO EVOLUCION DE LA GESTACION');
			
			$this->salida.= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida.= $this->SetStyle("MensajeError");
			$this->salida.= "      </table><br>";
			
			$tipo_atencion=$cierre[0][sw_estado];
			$pre1=0;
			$this->frmGestacion($semana_gestante,$fcp);

			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionGestacion'));
			
			$num_sem=sizeof($semanas);

			$this->salida.="<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"20%\">Rango Semanas</td>";
			for($i=0;$i<$num_sem;$i++)
			{
				$this->salida.="			<td colspan=\"2\" width=\"9%\">".$semanas[$i][rango_inicio]."-".$semanas[$i][rango_fin]."</td>";
			}
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td>&nbsp;</td>";
			for($i=0;$i<$num_sem;$i++)
			{
					$this->salida.="			<td width=\"4.5%\">Si</td>";
					$this->salida.="			<td width=\"4.5%\">No</td>";
			}
			$this->salida.="		</tr>";

			$k=0;
			
			$post1=0;
			$post2=0;
			$evoluciones=array();
			$periodos=array();
			for($i=0;$i<sizeof($vector);$i++)
			{
				if($k%2==0)
				{
					$estilo='hc_submodulo_list_claro';
				}
				else
				{
					$estilo='hc_submodulo_list_oscuro';
				}
				
				if($vector[$i]!="C" AND $vector[$i]!="P")
				{
					$this->salida.="		<tr class=\"$estilo\">";
					$this->salida.="			<td><label class=\"".$this->SetStyle($vector[$i])."\">".$vector[$i]."</label></td>";

					for($j=0;$j<$num_sem;$j++)
					{
						$ban=0;
						for($l=0;$l<sizeof($datosConsulta);$l++)
						{
							if($datosConsulta[$l][semana_sugerida]==$semanas[$j][rango_media])
							{
								switch($i)
								{
									case 0:
											$evoluciones[$j]=$datosConsulta[$l][evolucion_id];
											$periodos[$j]=$semanas[$j][periodo_id];
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".substr($datosConsulta[$l][fecha_registro],0,10)."</label></td>";
									break;
									case 1:
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][semana_actual]."</label></td>";
									break;
									case 2:
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][peso]."</label></td>";
									break;
									case 3:
											$ta_alta=$datosConsulta[$l][taalta];
											$ta_baja=$datosConsulta[$l][tabaja];
											
											$styl1="";
											if(!empty($ta_alta) AND $ta_alta>139)
												$styl1 = " style=\"color:#990000;font-weight : bold; \" ";
											
											$styl2="";
											if(!empty($ta_baja) AND $ta_baja<55)
												$styl2 = " style=\"color:#990000;font-weight : bold; \" ";		
									
											$this->salida.="<td align=\"center\" colspan=\"2\"><label $styl1 class=\"label\">".$ta_alta."</label> / <label $styl2 class=\"label\">".$ta_baja."</label></td>";
									break;
									case 4:
											switch($datosConsulta[$l][mamas])
											{
												case 1:
													$estado="Normal";
												break;
												case 2:
													$estado="<font color=\"".$this->redcolorf."\">Anormal</font>";
												break;
											}
												
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
									break;
									case 5:
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][altura_uterina]."</label></td>";
									break;
									case 6:
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$datosConsulta[$l][fcf]."</label></td>";
									break;
									case 7:
											switch($datosConsulta[$l][presentacion_fetal])
											{
												case 1:
													$estado="<font color=\"".$this->redcolorf."\">Podalica</font>";
												break;
												case 2:
													$estado="Cefalica";
												break;
												case 3:
													$estado="<font color=\"".$this->redcolorf."\">Transversa</font>";
												break;
												case 4:
													$estado="N.A";
												break;
											}
												
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
									break;
									case 8:
											switch($datosConsulta[$l][estado_nutricional])
											{
												case 1:
													$estado="Normal";
												break;
												case 2:
													$estado="<font color=\"".$this->redcolorf."\">Bajo Peso</font>";
												break;
												case 3:
													$estado="<font color=\"".$this->redcolorf."\">Sobrepeso</font>";
												break;
											}
												
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
									break;
									case 9:
											if($datosConsulta[$l][movimientos_fetales]==1)
												$estado="Si";
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									case 10:
											if($datosConsulta[$l][actividad_uterina]==1)
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									case 11:
											if($datosConsulta[$l][especuloscopia]==1)
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									case 12:
											if($datosConsulta[$l][clasificacion_riesgo]==1)
												$estado="BAJO";
											else
												$estado="<font color=\"".$this->redcolorf."\">ALTO</font>";
										
												
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									case 13:
											if($datosConsulta[$l][riesgo_biologico]>=3)
												$estado="<font color=\"".$this->redcolorf."\">".$datosConsulta[$l][riesgo_biologico]."</font>";
											else
												$estado=$datosConsulta[$l][riesgo_biologico];
												
												$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									case 14:
											if($datosConsulta[$l][riesgo_psicosocial]>0)
												$estado="<font color=\"".$this->redcolorf."\">".$datosConsulta[$l][riesgo_psicosocial]."</font>";
											else
												$estado=$datosConsulta[$l][riesgo_psicosocial];
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									//case 15: pruebas de laboratorio*//
									case 16:
											if($datosConsulta[$l][hospitalizacion_antes_cpn]==1)
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									case 17:
											if($datosConsulta[$l][asesoria_pretest]==1)
											{
												$estado="Si";
												$pre1=1;
											}
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									case 18:
											if($datosConsulta[$l][asesoria_postest]==1)
											{
												$estado="Si";
												$post2=1;
											}
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									case 19:
											if($datosConsulta[$l][vacunacion_tt]==1)
												$estado="Si";
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									break;
									//case 20: codigos especialidades,medicamentos,cargos*//
									case 21:
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$datosConsulta[$l][fecha_ideal_proxima_cita]."</font></label></td>";
									break;
									case 22:
											if($datosConsulta[$l][cierre_caso]==1)
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
												
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
											
									break;
									case 23:
										$vectorR=array("N.A","ITU","Vaginosis","Diabetes","Hipertension",
																				"Preeclampsia","RCIU","Polihidramnios");
																				
										
										$this->salida.="	<td align=\"center\" colspan=\"2\"><label class=\"label\">";
										for($h=0;$h<sizeof($vectorR);$h++)
										{
											$font="";
											if($vectorR[$h]!="N.A")
												$font="<font color=\"".$this->redcolorf."\">";
											
											if($datosConsulta[$l][riesgo_especifico1]==$h+1)
												$this->salida.="$font".$vectorR[$h]."</font><br>";
											if($datosConsulta[$l][riesgo_especifico2]==$h+1)
												$this->salida.="$font".$vectorR[$h]."</font><br>";
											if($datosConsulta[$l][riesgo_especifico3]==$h+1)
												$this->salida.="$font".$vectorR[$h]."</font>";
										}
										$this->salida.="</td>";
									break;
								}
								$ban=1;
								break;
							}
						}
						if($ban==0)
						{
							if($semana_gestante>=$semanas[$j][rango_inicio] and $semana_gestante<=$semanas[$j][rango_fin] AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
							{
								switch($i)
								{
									case 0:
											$evoluciones[$j]=$evolucion;
											$periodos[$j]=$semanas[$j][periodo_id];
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">".date("Y-m-d")."</label></td>";
									break;
									case 1:
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\">$semana_gestante</label></td>";
									break;
									case 2:
											if($_REQUEST['peso'.$pfj])
												$peso=$_REQUEST['peso'.$pfj];
											else
												$peso=$datossignosConsulta[0][peso];
												
												$this->salida.="<td align=\"center\" colspan=\"2\"><input type=\"text\" name=\"peso$pfj\" class=\"input-text\" value=\"".$peso."\" size=\"8\" maxlenght=\"6\"></td>";
									break;
									case 3:
											if($_REQUEST['ta_alta'.$pfj])
												$ta_alta=$_REQUEST['ta_alta'.$pfj];
											else
												$ta_alta=$datossignosConsulta[0][ta_alta];
											
											if($_REQUEST['ta_baja'.$pfj])
												$ta_baja=$_REQUEST['ta_baja'.$pfj];
											else
												$ta_baja=$datossignosConsulta[0][ta_baja];
												
											$styl1="";
											if(!empty($ta_alta) AND $ta_alta>139)
												$styl1 = " style=\"color:#990000;font-weight : bold; \" ";
											
											$styl2="";
											if(!empty($ta_baja) AND $ta_baja<55)
												$styl2 = " style=\"color:#990000;font-weight : bold; \" ";
												
											$this->salida.="<td align=\"center\" colspan=\"2\"><input type=\"text\" name=\"ta_alta$pfj\" $styl1 class=\"input-text\" value=\"".$ta_alta."\" size=\"3\" maxlenght=\"3\"> / <input type=\"text\" name=\"ta_baja$pfj\" $styl2 class=\"input-text\" value=\"".$ta_baja."\" size=\"3\" maxlenght=\"3\"></td>";
									break;
									case 4:
												$sel1="";$sel2="";
												if($_REQUEST['mamas'.$pfj])
												{
													switch($_REQUEST['mamas'.$pfj])
													{
														case 1:
															$sel1="selected";
														break;
														case 2:
															$sel2="selected";
														break;
													}
												}
												else
												{
													switch($datos_sistemas[0]['mamas'])
													{
														case 1:
															$sel1="selected";
														break;
														case 2:
															$sel2="selected";
														break;
													}
												}
												$this->salida.="<td align=\"center\" colspan=\"2\">";
												$this->salida.="	<select name=\"mamas$pfj\" class=\"select\">";
												$this->salida.="		<option value=\"1\" $sel1>Normal</option>";
												$this->salida.="		<option value=\"2\" $sel2>Anormal</option>";
												$this->salida.="	</select>";
												$this->salida.="</td>";
									break;
									case 5:
											
											$altura_uterina=$_REQUEST['altura_uterina'.$pfj];
	
											$this->salida.="<td align=\"center\" colspan=\"2\"><input type=\"text\" name=\"altura_uterina$pfj\" class=\"input-text\" value=\"".$altura_uterina."\" size=\"8\" maxlenght=\"6\"></td>";
									break;
									case 6:
											
											$fcf=$_REQUEST['fcf'.$pfj];
												
											$this->salida.="<td align=\"center\" colspan=\"2\"><input type=\"text\" name=\"fcf$pfj\" class=\"input-text\" value=\"".$fcf."\" size=\"8\" maxlenght=\"6\"></td>";
									break;
									case 7:
											$sel1="";$sel2="";$sel3="";$sel4="";
											
											switch($_REQUEST['presentacion_fetal'.$pfj])
											{
												case 1:
													$sel1="selected";
												break;
												case 2:
													$sel2="selected";
												break;
												case 3:
													$sel3="selected";
												break;
												case 4:
													$sel4="selected";
												break;
											}
											
											$this->salida.="<td align=\"center\" colspan=\"2\">";
											$this->salida.="	<select name=\"presentacion_fetal$pfj\" class=\"select\">";
											$this->salida.="		<option value=\"4\" $sel4>N.A</option>";
											$this->salida.="		<option value=\"1\" $sel1>Cefalica</option>";
											$this->salida.="		<option value=\"2\" $sel2>Podalica</option>";
											$this->salida.="		<option value=\"3\" $sel3>Tranversa</option>";
											$this->salida.="	</select>";
											$this->salida.="</td>";
											
									break;
									case 8:
											$sel1="";$sel2="";$sel3="";
											
											switch($_REQUEST['estado_nutricional'.$pfj])
											{
												case 1:
													$sel1="selected";
												break;
												case 2:
													$sel2="selected";
												break;
												case 3:
													$sel3="selected";
												break;
											}
											
											$this->salida.="<td align=\"center\" colspan=\"2\">";
											$this->salida.="	<select name=\"estado_nutricional$pfj\" class=\"select\">";
											$this->salida.="		<option value=\"1\" $sel1>Normal</option>";
											$this->salida.="		<option value=\"2\" $sel2>Bajo Peso</option>";
											$this->salida.="		<option value=\"3\" $sel3>Sobrepeso</option>";
											$this->salida.="	</select>";
											$this->salida.="</td>";
									break;
									case 9:
											$check1="";$check2="";
											if($_REQUEST['movimientos_fetales'.$pfj])
											{
												if($_REQUEST['movimientos_fetales'.$pfj]==1)
													$check1="checked";
												else
													$check2="checked";
											}
											
											
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"movimientos_fetales$pfj\" value=\"1\" $check1></td>";
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"movimientos_fetales$pfj\" value=\"2\" $check2></td>";
									break;
									case 10:
											$check1="";$check2="";
											if($_REQUEST['actividad_uterina'.$pfj])
											{
												if($_REQUEST['actividad_uterina'.$pfj]==1)
													$check1="checked";
												else
													$check2="checked";
											}
											
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"actividad_uterina$pfj\" value=\"1\" $check1></td>";
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"actividad_uterina$pfj\" value=\"2\" $check2></td>";
									break;
									case 11:
											$check1="";$check2="";
											if($_REQUEST['especu'.$pfj])
											{
												if($_REQUEST['especu'.$pfj]==1)
													$check1="checked";
												else
													$check2="checked";
											}
											
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"especu$pfj\" value=\"1\" $check1></td>";
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"especu$pfj\" value=\"2\" $check2></td>";
									break;
									case 12:
											$this->salida.="<td align=\"center\" colspan=\"2\">";
											if($puntajeTotalRiesgos < 3)
											{
												$this->salida.="<label class=\"label\">BAJO</label>";
												$this->salida.="<input type=\"hidden\" name=\"clasifi_riesgo$pfj\" value=\"1\">";
											}
											else
											{
												$this->salida.="<label class=\"label\"><font color=\"".$this->redcolorf."\">ALTO</font></label>";
												$this->salida.="<input type=\"hidden\" name=\"clasifi_riesgo$pfj\" value=\"2\">";
											}
											$this->salida.="</td>";
									break;
									case 13:
												
											$valor1=0;
											$valor2=0;
											$valor=0;
											$npuntos=0;
											
											if($puntajeRiesgos[1]>=2) $valor1=1;
											if($puntajeRiesgos[2]>=2) $valor2=1;
											
											$valor=$valor1+$valor2;
											$npuntos=$puntajeTotalRiesgos-$valor;
											
											if($npuntos>=3)
												$this->salida.="			<td align=\"center\" colspan=\"2\"><label class=\"label\"><font color=\"".$this->redcolorf."\">$npuntos</font></label></td>";
											else
												$this->salida.="			<td align=\"center\" colspan=\"2\"><label class=\"label\">$npuntos</label></td>";
		
											$this->salida.="<input type=\"hidden\" name=\"riesgo_bio$pfj\" value=\"$npuntos\">";
									break;
									case 14:
											$valor1=0;
											$valor2=0;
											$valor=0;
											
											if($puntajeRiesgos[1]>=2) $valor1=1;
											if($puntajeRiesgos[2]>=2) $valor2=1;
											
											$valor=$valor1+$valor2;
		
											if($valor>0)
												$this->salida.="			<td align=\"center\" colspan=\"2\"><label class=\"label\"><font color=\"".$this->redcolorf."\">$valor</font></label></td>";
											else
												$this->salida.="			<td align=\"center\" colspan=\"2\"><label class=\"label\">$valor</label></td>";
	
											$this->salida.="<input type=\"hidden\" name=\"riesgo_psico$pfj\" value=\"$valor\">";
									break;
									//case 15: pruebas de laboratorio*//
									case 16:
											$check1="";$check2="";
											if($_REQUEST['hospt_cpn'.$pfj])
											{
												if($_REQUEST['hospt_cpn'.$pfj]==1)
													$check1="checked";
												else
													$check2="checked";
											}
											
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"hospt_cpn$pfj\" value=\"1\" $check1></td>";
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"hospt_cpn$pfj\" value=\"2\" $check2></td>";
									break;
									case 17:
											$check1="";$check2="";
											if($_REQUEST['pretest'.$pfj])
											{
												if($_REQUEST['pretest'.$pfj]==1)
													$check1="checked";
												else
													$check2="checked";
											}
											if($pre1)
												$this->salida.="			<td align=\"center\" colspan=\"2\"><label class=\"label\"><font color=\"".$this->redcolorf."\">Si</font></label></td>";
											else
											{
												$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"pretest$pfj\" value=\"1\" $check1></td>";
												$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"pretest$pfj\" value=\"2\" $check2></td>";
											}
									break;
									case 18:
											$check1="";$check2="";
											if($_REQUEST['postest'.$pfj])
											{
												if($_REQUEST['postest'.$pfj]==1)
													$check1="checked";
												else
													$check2="checked";
											}
											
											if($post1 AND $post2)
												$this->salida.="			<td align=\"center\" colspan=\"2\"><label class=\"label\"><font color=\"".$this->redcolorf."\">Si</font></label></td>";
											else
											{
												$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"postest$pfj\" value=\"1\" $check1></td>";
												$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"postest$pfj\" value=\"2\" $check2></td>";
											}
									break;
									case 19:
											$check1="";$check2="";
											if($_REQUEST['vacunacion_tt'.$pfj])
											{
												if($_REQUEST['vacunacion_tt'.$pfj]==1)
													$check1="checked";
												else
													$check2="checked";
											}
											
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"vacunacion_tt$pfj\" value=\"1\" $check1></td>";
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"vacunacion_tt$pfj\" value=\"2\" $check2></td>";
									break;
									//case 20: codigos especialidades,medicamentos,cargos*//
									case 21:
											$this->salida.="<td align=\"center\" colspan=\"2\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$proximaCita."</font></label></td>";
											$this->salida.="<input type=\"hidden\" name=\"fecha_ideal_cita$pfj\" value=\"$proximaCita\">";
									break;
									case 22:
											$check1="";$check2="";
											if($_REQUEST['cierre_caso'.$pfj])
											{
												if($_REQUEST['cierre_caso'.$pfj]==1)
													$check1="checked";
												else
													$check2="checked";
											}
											if($semana_gestante < 30)
												$check2="checked";
											
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"cierre_caso$pfj\" value=\"1\" $check1></td>";
											$this->salida.="<td align=\"center\"><input type=\"radio\" name=\"cierre_caso$pfj\" value=\"2\" $check2></td>";
									break;
									case 23:
											$cont=0;
											$check=array();
											for($y=0;$y<sizeof($_REQUEST['riesgos_especifico'.$pfj]);$y++)
											{
												for($n=1;$n<=8;$n++)
												{
													if($_REQUEST['riesgos_especifico'.$pfj][$y]==$n)
													{	
														$check[$n-1]="checked";
														$cont++;
													}
												}
											}
											
											$vectorR=array("N.A","ITU","Vaginosis","Diabetes","Hipertension","Preeclampsia","RCIU","Polihidramnios");
											
											$this->salida.="<td align=\"center\" colspan=\"2\">";
											$this->salida.="	<table border=\"0\">";
											for($p=0;$p<8;$p++)
											{
												$this->salida.="		<tr align=\"right\">";
												$this->salida.="			<td>";
												$this->salida.="				<label class=\"label\">".$vectorR[$p]."</label>";
												$this->salida.="			</td>";
												$this->salida.="			<td>";
												$disabled="";
												if($cont==3 AND !$check[$p])
													$disabled="disabled";
													
													$this->salida.="				<input type=\"checkbox\" name=\"riesgos_especifico".$pfj."[]\" value=\"".($p+1)."\" ".$check[$p]." onclick=\"Checkeo(this.name,this.checked,".($p+1).");\" $disabled><br>";
													$this->salida.="			</td>";
													$this->salida.="		</tr>";
											}
											$this->salida.="	</table>";
											$this->salida.="</td>";
									break;
								}
							}
							else
								$this->salida.="<td align=\"center\" colspan=\"2\">&nbsp;</td>";
						}
					}
				}
				elseif($vector[$i]=="P")
				{
					$this->salida.="		<tr class=\"modulo_table_list_title\">";
					$this->salida.="		<td colspan=\"19\" align=\"left\"><label>PRUEBAS DE LABORATORIO</label></td>";
					$this->salida.="		</tr>";
					$n=0;
					for($s=0;$s<sizeof($pruebasLab);$s++)
					{
						if($n%2==0)
						{
							$estilo='hc_submodulo_list_claro';
						}
						else
						{
							$estilo='hc_submodulo_list_oscuro';
						}
		
						$this->salida.="		<tr class=\"$estilo\">";
						
						if(empty($pruebasLab[$s][alias]))
							$descripcion=$pruebasLab[$s][descripcion];
						else
							$descripcion=$pruebasLab[$s][alias];

						$this->salida.="			<td><label class=\"label\">".$descripcion."</label></td>";
						for($j=0;$j<$num_sem;$j++)
						{
							$a=0;
							for($g=0;$g<sizeof($resultados);$g++)
							{
								if($pruebasLab[$s][cargo_cups]==$resultados[$g][cargo] AND $resultados[$g][evolucion_id]==$evoluciones[$j] AND $resultados[$g][periodo_solicitud]==$periodos[$j])
								{
									if($pruebasLab[$s][sw_post]==1)
										$post1=1;
									
									$sw_modo=$resultados[$g][sw_modo_resultado];
									$resultado_id=$resultados[$g][resultado_id];
									$this->salida.="<td align=\"center\" colspan=\"2\">";
									$datos="resultado_id=".$resultado_id."&sw_modo=".$sw_modo;
									$url="classes/Visualizar/Visualizar.class.php?".$datos;
									if($resultados[$g][sw_alerta])
										$this->salida.="	<a href=\"javascript:AbrirVentanaVer('$url')\"><b>Ver</b></a>";
									else
										$this->salida.="	<a href=\"javascript:AbrirVentanaVer('$url')\">Ver</a>";
									$this->salida.="</td>";
									
									$a=1;
									break;
								}
							}
							if($a==0)
							{
								$b=0;
								for($l=0;$l<sizeof($lab);$l++)
								{
									if($pruebasLab[$s][cargo_cups]==$lab[$l][cargo] AND $lab[$l][evolucion_id]==$evoluciones[$j] AND $lab[$l][periodo_solicitud]==$periodos[$j] AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
									{
										$this->salida.="<td align=\"center\" colspan=\"2\" id=\"trans$s$j\">";
										$datos="cargo=".$lab[$l][cargo]."&descripcion=".$lab[$l][descripcion]."&op=Transcribir&periodo=".($j+1)."&estilo=$estilo&evolucion_id=".$lab[$l][evolucion_id]."&trans=trans$s$j";
										$url="classes/Transcripcion/TranscripcionExamenes.class.php?".$datos;
										$this->salida.="	<a href=\"javascript:AbrirVentana('$url')\">Transcribir</a>";
										$this->salida.="</td>";
										$b=1;
										break;
									}
								}
							}
							if($a==0 and $b==0)
							{
								$this->salida.="			<td align=\"center\" colspan=\"2\">&nbsp;</td>";
							}
						}
						$this->salida.="</tr>";
						$n++;
					}
					$this->salida.="		<tr class=\"modulo_table_list_title\">";
					$this->salida.="		<td colspan=\"19\" align=\"left\"><label>CONDUCTA</label></td>";
					$this->salida.="		</tr>";
				}
				elseif($vector[$i]=="C")
				{
					$f=0;
					for($m=0;$m<sizeof($datosEvolucion);$m++)
					{
						if($f%2==0)
						{
							$estilo1='hc_submodulo_list_claro';
						}
						else
						{
							$estilo1='hc_submodulo_list_oscuro';
						}
						
						$this->salida.="		<tr class=\"$estilo1\" align=\"center\">";
						$this->salida.="			<td align=\"left\"><label class=\"".$this->SetStyle("C$m")."\">".$datosEvolucion[$m][descripcion]."</label></td>";

						for($j=0;$j<$num_sem;$j++)
						{
							$ban=0;
							for($s=0;$s<sizeof($datosEvolucionregistros);$s++)
							{
								if($datosEvolucion[$m][codigo_evolucion_id]==$datosEvolucionregistros[$s][codigo_evolucion_id] AND $datosEvolucionregistros[$s][semana_sugerida]==$semanas[$j][rango_media])
								{
									if($datosEvolucion[$m][sw_opcion_sino])
									{
										$font1="<font color=\"".$this->redcolorf."\">";
										$font1_f="</font>";
										$font2="";
										$font2_f="";
									}
									else
									{
										$font1="";
										$font1_f="";
										$font2="<font color=\"".$this->redcolorf."\">";
										$font2_f="</font>";
									}
									
									if($datosEvolucionregistros[$s][valor]==1)
										$estado="$font1 Si $font1_f";
									else
										$estado="$font2 No $font2_f";
									
									$this->salida.="		<td colspan=\"2\"><label class=\"label\">".$estado."</label></td>";
									$ban=1;
									break;
								}
							}
							if($ban==0)
							{
								if($semana_gestante>=$semanas[$j][rango_inicio] and $semana_gestante<=$semanas[$j][rango_fin] AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
								{
									$check1="";$check2="";
									if($_REQUEST['nombre'.$pfj][$m])
									{
										if($_REQUEST['nombre'.$pfj][$m]==1)
											$check1="checked";
										else
											$check2="checked";
									}
									
									if(!empty($datosEvolucion[$m][sw_parto]) AND $semana_gestante < 30)
										$check2="checked";
									
									$vector2="";
									$capa="";
									$xcapa="";
									$onclick="";
									
									if(!empty($datosEvolucion[$m][especialidad]))
									{
										$especialidad=$datosEspecialidad[$m];
	
										$vector2="new Array('".$especialidad[0][especialidad]."','".$especialidad[0][descripcion]."','".$especialidad[0][cargo]."','".$especialidad[0][tipo_consulta_id]."','capa$m','nombre".$pfj."[$m]','".$datosEvolucion[$m][codigo_evolucion_id]."','".$datosEvolucion[$m][sw_opcion_sino]."')";
										$capa="id=\"capa$m\"";
										$xcapa="id=\"xcapa$m\"";
										$onclick=" onclick=\"Obtener($vector2);\"";
									}
	
									$this->salida.="<td align=\"center\" $capa><input type=\"radio\" name=\"nombre".$pfj."[$m]\" value=\"1ç".$datosEvolucion[$m][codigo_evolucion_id]."\" $onclick $check1></td>";
									$this->salida.="<td align=\"center\" $xcapa><input type=\"radio\" name=\"nombre".$pfj."[$m]\" value=\"2ç".$datosEvolucion[$m][codigo_evolucion_id]."\" $check2></td>";
								}
								else
									$this->salida.="		<td colspan=\"2\">&nbsp;</td>";
							}
						}
						$this->salida.="		</tr>";
						$f++;
					}
					
				}
				$k++;
			}
			$this->salida.="		</tr>";
			$this->salida.="	<tr class=\"hc_table_submodulo_list_title\" >";
			$this->salida.="		<td><label class=\"label\"> PROFESIONAL </label></td>";
			for($j=0;$j<$num_sem;$j++)
			{
				$ban=0;
				for($b=0;$b<sizeof($datosConsulta);$b++)
				{
					if($datosConsulta[$b][semana_sugerida]==$semanas[$j][rango_media])
					{
						$this->salida.="		<td colspan=\"2\">".$datosConsulta[$b][nombre]."</td>";
						$ban=1;
						break;
					}
				}
				if($ban==0 AND $semana_gestante>=$semanas[$j][rango_inicio] and $semana_gestante<=$semanas[$j][rango_fin])
					$this->salida.="		<td colspan=\"2\">".$datosprofesional[0][nombre]."</td>";
				elseif($ban==0)
					$this->salida.="		<td colspan=\"2\">&nbsp;</td>";
			}
			$this->salida.="	</tr>";
			$this->salida.="	</table><br>";

			if($tipo_atencion=='4' OR (SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
			{
				$accionC=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'CierredeCaso'));
				$this->salida.="<center><label class=\"label\"><a href=\"$accionC\">CIERRE DE CASO</a></label></center>";		
			}
			
			$this->salida.="	<table align=\"center\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			if((!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
				$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"guardar$pfj\" value=\"GUARDAR\"></td>";
			$this->salida.="</form>";
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'GraficasSeguimientoCPN'));
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RiesgoBiopsicosocial'));
			
			$this->salida.="<form name=\"formasig$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"siguiente$pfj\" value=\"SIGUIENTE\"></td>";
			$this->salida.="</form>";
			$this->salida.="<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></td>";
			$this->salida.="</form>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			$this->salida.= ThemeCerrarTablaSubModulo();
			
			$this->salida .= "	<script language=\"javascript\">";
			$this->salida .= "		var cont=0;\n";
			$this->salida .= "		var valores=new Array();\n";
			$this->salida .= "		var j=0;\n";
			$this->salida .= "		function Checkeo(nombre,x,valor)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var i;\n";
			$this->salida .= "			switch(valor)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				case 1:\n";
			$this->salida .= "					cont=0;\n";
			$this->salida .= "					if(x)\n";
			$this->salida .= "					{\n";
			$this->salida .= "						for(i=0;i<document.formades$pfj.elements.length;i++)\n";
			$this->salida .= "						{\n";
			$this->salida .= "							if(document.formades$pfj.elements[i].type=='checkbox' && document.formades$pfj.elements[i].value!=valor)\n";
			$this->salida .= "							{\n";
			$this->salida .= "								document.formades$pfj.elements[i].disabled=true;\n";
			$this->salida .= "								document.formades$pfj.elements[i].checked=false;\n";
			$this->salida .= "							}\n";
			$this->salida .= "						}\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else\n";
			$this->salida .= "					{\n";
			$this->salida .= "						for(i=0;i<document.formades$pfj.elements.length;i++)\n";
			$this->salida .= "						{\n";
			$this->salida .= "							if(document.formades$pfj.elements[i].type=='checkbox' && document.formades$pfj.elements[i].value!=valor)\n";
			$this->salida .= "								document.formades$pfj.elements[i].disabled=false;\n";
			$this->salida .= "						}\n";
			$this->salida .= "					}\n";
			$this->salida .= "				break\n";
			$this->salida .= "				default:\n";
			$this->salida .= "				if(x==true)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					valores[cont]=valor\n";
			$this->salida .= "					cont++;\n";
			$this->salida .= "					if(cont==3)\n";
			$this->salida .= "					{\n";
			$this->salida .= "							for(i=0;i<document.formades$pfj.elements.length;i++)\n";
			$this->salida .= "							{\n";
			$this->salida .= "								if(document.formades$pfj.elements[i].type=='checkbox' && document.formades$pfj.elements[i].value!=valores[0]
																					&& document.formades$pfj.elements[i].value!=valores[1] && document.formades$pfj.elements[i].value!=valores[2])\n";
			$this->salida .= "								{\n";
			$this->salida .= "									document.formades$pfj.elements[i].disabled=true;\n";
			$this->salida .= "									document.formades$pfj.elements[i].checked=false;\n";
			$this->salida .= "								}\n";
			$this->salida .= "							}\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else\n";
			$this->salida .= "				{\n";
			$this->salida .= "				 j=0;\n";
			$this->salida .= "					for(var k=0;k<cont;k++)\n";
			$this->salida .= "						if(valores[k]!=valor)\n";
			$this->salida .= "							valores[j++]=valores[k];\n";
			$this->salida .= "					cont--;\n";
			$this->salida .= "					if(cont<3)\n";
			$this->salida .= "					{\n";
			$this->salida .= "							for(i=0;i<document.formades$pfj.elements.length;i++)\n";
			$this->salida .= "							{\n";
			$this->salida .= "								if(document.formades$pfj.elements[i].type=='checkbox')\n";
			$this->salida .= "								{\n";
			$this->salida .= "									document.formades$pfj.elements[i].disabled=false;\n";
			$this->salida .= "								}\n";
			$this->salida .= "							}\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				break\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";

			$this->salida .= "		function mOvr(capa)";
			$this->salida .= "		{";
			$this->salida .= "			IniciaPro();";
			$this->salida .= "			e=xGetElementById(capa);";
			$this->salida .= "			e.style.display = \"\";";
			$this->salida .= "		}";
			$this->salida .= "		function mOut(capa)";
			$this->salida .= "		{";
			$this->salida .= "			e=xGetElementById(capa);";
			$this->salida .= "			e.style.display = \"none\";";
			$this->salida .= "		}";
			$this->salida .= "	</script>";
			
			$this->salida .= "<script language=\"javascript\">\n";
			$this->salida .= "	var capa_actual;\n";
			$this->salida .= "	function showhide1(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		for(i=0; i<capas2.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(capas2[i]);\n";
			$this->salida .= "			if(capas2[i] != Seccion)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				if(e.style.display == \"none\")\n";
			$this->salida .= "				{\n";
			$this->salida .= "					e.style.display = \"\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else \n";
			$this->salida .= "				{\n";
			$this->salida .= "					e.style.display = \"none\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function funcion1(x)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var z=new Array(\"'\"+x[0]+\"'\",\"'\"+x[1]+\"'\");\n";
			$this->salida .= "		jsrsExecute('classes/modules/procesos1.php',VerDatos,'VerDatos',z);";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function VerDatos(x)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.getElementById('d2Contents').innerHTML=x; \n";
			$this->salida .= "		Iniciar('Consulta Examenes Clinicos');\n";
			$this->salida .= "		MostrarSpan('d2Container');\n";
			$this->salida .= "	}\n";

			$this->salida .= "	function AbrirVentana(url)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.open(url,'transcribir',\"width=700,height=0,x=2,y=2,resizable=no,status=no,scrollbars=yes,location=no\"); \n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function AbrirVentanaVer(url)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.open(url,'ver',\"width=710,height=0,x=2,y=2,resizable=no,status=no,scrollbars=yes,location=no\"); \n";
			$this->salida .= "	}\n";
			
			$this->salida .= "</script>\n";
			
			$this->salida .= "<script>\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	 var titulo = '';\n";
			$this->salida .= "	 var contenedor = '';\n";
			$this->salida .= "	 var datos = '';\n";
			$this->salida .= "	 var capaActual = '';\n";
			
			$this->salida .= "	function Iniciar(tit)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  titulo = 'titulo';\n";
			$this->salida .= "	  contenedor = 'd2Container';\n";
			$this->salida .= "		document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		ele = xGetElementById('d2Contents');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/25, xScrollTop());\n";
			$this->salida .= "	  xResizeTo(ele,800,'auto');\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/25, xScrollTop()+24);\n";
			$this->salida .= "	  xResizeTo(ele,800, 'auto');\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,780, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 780, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function IniciaPro()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		ele = xGetElementById('d2Container');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop()+170);\n";
			$this->salida .= "	  xResizeTo(ele,250, 'auto');\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
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
			$this->salida .= "	function IniciarInterconsulta(tit)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  titulo = 'tituloI';\n";
			$this->salida .= "	  contenedor = 'ContainerI';\n";
			$this->salida .= "		document.getElementById(titulo).innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		document.interconsulta.cantidad.value = '1';\n";
			$this->salida .= "		document.interconsulta.observacion.value = '';\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop());\n";
			$this->salida .= "	  xResizeTo(ele,370, 'auto');\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,350, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarI');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 350, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function Obtener(vector)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  datos=vector;\n";
			$this->salida .= "	  capaActual=vector[4];\n";
			$this->salida .= "		jsrsExecute('classes/modules/InterCPN/Inter.php',TraerForma,'TraerForma',vector);";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function Evaluar(forma)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  mensaje='';\n";
			$this->salida .= "	  var canti= document.interconsulta.cantidad.value;\n";
			$this->salida .= "	  var obs = document.interconsulta.observacion.value;\n";
			$this->salida .= "		if( canti== '')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			mensaje='DEBE INGRESAR UNA CANTIDAD';\n";
			$this->salida .= "		}\n";
			$this->salida .= "		if(obs == '')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			mensaje='DEBE INGRESAR UNA OBSERVACION';\n";
			$this->salida .= "		}\n";
			$this->salida .= "		document.getElementById('errorI').innerHTML = '<center>'+mensaje+'</center>';\n";
			$this->salida .= "		if(mensaje=='')\n";
			$this->salida .= "		{\n";
			$this->salida .= "	  	datos[8]=canti;\n";
			$this->salida .= "	  	datos[9]=obs;\n";;
			$this->salida .= "			SolicitudesInterconsulta(datos);\n";
			$this->salida .= "	  }\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function VInterconsultas(html)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		Cerrar('ContainerI');\n";
			$this->salida .= "		document.getElementById(capaActual).innerHTML = '<center>'+html+'</center>';\n";
			$this->salida .= "		document.getElementById('x'+capaActual).innerHTML = '';\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function TraerForma(html)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.getElementById('Interconsulta').innerHTML = html;";
			$this->salida .= "		IniciarInterconsulta(datos[1]);";
			$this->salida .= "		MostrarSpan('ContainerI');";
			$this->salida .= "	}\n";
			
			$this->salida .= "</script>\n";
			
			$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='d2Contents' class='d2Content'>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";

			$this->salida .= "<div id='ContainerI' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='tituloI' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrarI' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContainerI')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='errorI' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "		<form name=\"interconsulta\" action=\"\" method=\"post\">";
			$this->salida .= "			<div id='Interconsulta'>\n";
			$this->salida .= "			</div>\n";
			$this->salida .= "		</form>";
			$this->salida .= "</div>\n";

			return $this->salida;
		}
		
		function frmGestacion($semana,$fecha)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\" cellpadding=\"0\" cellspacing=\"2\">";
			$this->salida.="	<tr>";
			$this->salida.="		<td width=\"25%\" class=\"modulo_table_list_title\">SEMANA DE GESTACION</td>";
			$this->salida.="		<td width=\"25%\" class=\"modulo_table_list_title\">FECHA PROBLABLE DE PARTO</td>";
			$this->salida.="  </tr>";
			$this->salida.="	<tr>";
			$this->salida.="		<td width=\"25%\" class=\"hc_table_submodulo_list_title\"><label class=\"label\">$semana</label></td>";
			$this->salida.="		<td width=\"25%\" class=\"hc_table_submodulo_list_title\"><label class=\"label\">$fecha</label></td>";
			$this->salida.="  </tr>";
			$this->salida.="</table><br>";
			return true;
		}
		
		function SetStyle($campo)
		{
			
			if ($this->frmError[$campo]||$campo=="MensajeError")
			{
				if ($campo=="MensajeError")
				{
					return ("<tr><td align=\"center\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
				}
				return ("label_error");
			}
			return ("label");
		}
	}
?>