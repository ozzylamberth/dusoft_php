<?php
	/********************************************************************************* 
 	* $Id: hc_RegistroEvolucionReno_RegistroER_HTML.class.php,v 1.2 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RegistroEvolucionReno
	* 
 	**********************************************************************************/

	class RegistroER_HTML
	{

		function RegistroER_HTML()
		{
			$this->redcolorf="#990000";
			return true;
		}
		
		function frmConsulta($vector,$registros,$codigos,$registros_cod)
		{
			$k=0;
			$salida="";
			$fecha="";
			$cols=1;
			//echo "<br><br><br><pre>=> ".sizeof($registros);
			//print_r($registros);
			if(sizeof($registros)>0)
			{
				foreach($vector as $v)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
					
					if($v!="C" AND $v!="P")
					{
						if($k>=sizeof($vector)-3)
						{
							$estilo="hc_table_submodulo_list_title";
							$align="align=\"left\"";
						}
						
						$salida.="		<tr class=\"$estilo\">";
						$salida.="			<td $align><label class=\"".$this->SetStyle($v)."\">".$v."</label></td>";
						foreach($registros as $valor)
						{
							switch($k)
							{
								case 0:
									$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".substr($valor['fecha_registro'],0,10)."</td>";
									$cols=($cols*2)+1;
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['peso']."</label></td>";
								break;
								case 1:
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['talla']."</label></td>";
								break;
								case 2:
									$imc=substr($valor['peso']/(pow($valor['talla']/100,2)),0,5);
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$imc."</label></td>";
								break;
								case 3:
									switch($valor['estado_nutricional'])
									{
										case '1':
											$estado="Normal";
										break;
										case '2':
											$estado="<font color=\"".$this->redcolorf."\">Bajo Peso</font>";
										break;
										case '3':
											$estado="<font color=\"".$this->redcolorf."\">Sobre Peso</font>";
										break;
									}
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 4:
									$style="";
									if(!empty($valor['taalta']) AND $valor['taalta']>139)
										$style="style=\"color:#990000;font-weight:bold;\"";
									
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\" $style>".$valor['taalta']."</label></td>";
								break;
								case 5:
									$style="";
									if(!empty($valor['tabaja']) AND $valor['tabaja']<55)
										$style="style=\"color:#990000;font-weight : bold; \"";
										
									$salida.="			<td colspan=\"2\" align=\"center\" $style><label class=\"label\" $style>".$valor['tabaja']."</label></td>";
								break;
								case 6:
									$style="";
									if($valor['estadio_kdoqi']>=3)
										$style="style=\"color:#990000;font-weight : bold; \"";
									
									$salida.="			<td colspan=\"2\" align=\"center\" $style><label class=\"label\">".$valor['estadio_kdoqi']."</label></td>";
								break;
								case 7:
									$estado="No";
									if($valor['riesgo_deterioro_acelerado']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 8:
									$estado="No";
									if($valor['deterioro_acelerado']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 9:
									$estado="No";
									if($valor['retinopatia']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 10:
									$estado="No";
									if($valor['lesion_organo_blanco']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 11:
									$estado="No";
									if($valor['presencia_ulcera_pies']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 12:
									$estado="No";
									if($valor['riesgo_ulcera_pies']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 13:
									$estado="Si";
									if($valor['adherencia_farmacologica']=="f")
											$estado="<font color=\"".$this->redcolorf."\">No</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 14:
									$estado="Si";
									if($valor['cambio_habitos_alimenticios']=="f")
											$estado="<font color=\"".$this->redcolorf."\">No</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 15:
									$estado="Si";
									if($valor['habito_actividad_fisica']=="f")
											$estado="<font color=\"".$this->redcolorf."\">No</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 16:
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$valor['riesgo_psicosocial']."</font></label></td>";
								break;
								case 17:
									$estado="Si";
									if($valor['asistencia_grupo_apoyo']=="t")
											$estado="<font color=\"".$this->redcolorf."\">No</font>";
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								
								//case 18 remisiones
								case 19:
									$estado="No";
									if($valor['cierre_caso']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								
								break;
								case 20:
									switch($valor['causa_cierre_caso'])
									{
										case 1:
											$estado="Mejoria";
										break;
										case 2:
											$estado="<font color=\"".$this->redcolorf."\">Cambio de IPS</font>";
										break;
										case 3:
											$estado="<font color=\"".$this->redcolorf."\">Retiro de EPS</font>";
										break;
										case 4:
											$estado="<font color=\"".$this->redcolorf."\">Alta Voluntaria</font>";
										break;
										case 5:
											$estado="<font color=\"".$this->redcolorf."\">Muerte</font>";
										break;
									}
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								
								break;
								case 21:
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$valor['fecha_ideal_proxima_cita']."</font></label></td>";
								break;
								
								//CASE 22 pruebas de laboratorio
								case 23:
									$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[nombre]."</td>";
							
								break;
								case 24:
									$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[descripcion]."</td>";
								break;
							}
						}
					}
					elseif($v=="C")
					{
						$l=0;
						foreach($codigos as $codigo)
						{
							if($l%2==0)
							{
								$estilo1='hc_submodulo_list_claro';
							}
							else
							{
								$estilo1='hc_submodulo_list_oscuro';
							}
							
							$salida.="		<tr class=\"$estilo1\">";
							$salida.="			<td><label class=\"label\">".$codigo['descripcion']."</label></td>";
							foreach($registros_cod as $registro)
							{
								if($registro['evolucion_id']==$evolucion)
									$ban=1;
								
								if($codigo['codigo_evolucion_id']==$registro['codigo_evolucion_id'])
								{
									if($registro['valor']==1)
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
									else
										$estado="No";
	
									$salida.="		<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								}
							}
							$l++;
						}
					}
					$k++;
				}
			
				$this->salida.="	<table align=\"center\" border=\"1\"  class=\"hc_table_submodulo_list\" width=\"100%\">";
				$this->salida.="		<tr class=\"modulo_table_list_title\">";
				$this->salida.="			<td width=\"20%\" colspan=\"$cols\">EVOLUCION DE SIGNOS,SINTOMAS Y CONDUCTAS</td>";
				$this->salida.="		</tr>";
				$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="			<td align=\"left\"><label class=\"label\">Fecha</label></td>";
				$this->salida.="		".$fecha;
				$this->salida.="		</tr>";
				$this->salida.="".	$salida;
				$this->salida.="	</tr>";
				$this->salida.="	</table>";
			}
			else
				return false;

			return $this->salida;
		}
		
		function frmHistoria($vector,$registros,$codigos,$registros_cod)
		{
			$k=0;
			$salida="";
			$fecha="";
			$cols=1;
			if(sizeof($registros)>0)
			{
				foreach($vector as $v)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
					
					if($v!="C" AND $v!="P")
					{
						if($k>=sizeof($vector)-3)
						{
							$estilo="hc_table_submodulo_list_title";
							$align="align=\"left\"";
						}
						
						$salida.="		<tr class=\"$estilo\">";
						$salida.="			<td $align><label class=\"".$this->SetStyle($v)."\">".$v."</label></td>";
						foreach($registros as $valor)
						{
							switch($k)
							{
								case 0:
									$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".substr($valor['fecha_registro'],0,10)."</td>";
									$cols=($cols*2)+1;
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['peso']."</label></td>";
								break;
								case 1:
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['talla']."</label></td>";
								break;
								case 2:
									$imc=substr($valor['peso']/(pow($valor['talla']/100,2)),0,5);
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$imc."</label></td>";
								break;
								case 3:
									switch($valor['estado_nutricional'])
									{
										case '1':
											$estado="Normal";
										break;
										case '2':
											$estado="<font color=\"".$this->redcolorf."\">Bajo Peso</font>";
										break;
										case '3':
											$estado="<font color=\"".$this->redcolorf."\">Sobre Peso</font>";
										break;
									}
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 4:
									$style="";
									if(!empty($valor['taalta']) AND $valor['taalta']>139)
										$style="style=\"color:#990000;font-weight:bold;\"";
									
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\" $style>".$valor['taalta']."</label></td>";
								break;
								case 5:
									$style="";
									if(!empty($valor['tabaja']) AND $valor['tabaja']<55)
										$style="style=\"color:#990000;font-weight : bold; \"";
										
									$salida.="			<td colspan=\"2\" align=\"center\" $style><label class=\"label\" $style>".$valor['tabaja']."</label></td>";
								break;
								case 6:
									$style="";
									if($valor['estadio_kdoqi']>=3)
										$style="style=\"color:#990000;font-weight : bold; \"";
									
									$salida.="			<td colspan=\"2\" align=\"center\" $style><label class=\"label\">".$valor['estadio_kdoqi']."</label></td>";
								break;
								case 7:
									$estado="No";
									if($valor['riesgo_deterioro_acelerado']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 8:
									$estado="No";
									if($valor['deterioro_acelerado']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 9:
									$estado="No";
									if($valor['retinopatia']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 10:
									$estado="No";
									if($valor['lesion_organo_blanco']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 11:
									$estado="No";
									if($valor['presencia_ulcera_pies']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 12:
									$estado="No";
									if($valor['riesgo_ulcera_pies']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
																case 13:
									$estado="Si";
									if($valor['adherencia_farmacologica']=="f")
											$estado="<font color=\"".$this->redcolorf."\">No</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 14:
									$estado="Si";
									if($valor['cambio_habitos_alimenticios']=="f")
											$estado="<font color=\"".$this->redcolorf."\">No</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 15:
									$estado="Si";
									if($valor['habito_actividad_fisica']=="f")
											$estado="<font color=\"".$this->redcolorf."\">No</font>";
								
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								case 16:
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$valor['riesgo_psicosocial']."</font></label></td>";
								break;
								case 17:
									$estado="Si";
									if($valor['asistencia_grupo_apoyo']=="t")
											$estado="<font color=\"".$this->redcolorf."\">No</font>";
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								break;
								
								//case 18 remisiones
								case 19:
									$estado="No";
									if($valor['cierre_caso']=="t")
											$estado="<font color=\"".$this->redcolorf."\">Si</font>";
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								
								break;
								case 20:
									switch($valor['causa_cierre_caso'])
									{
										case 1:
											$estado="Mejoria";
										break;
										case 2:
											$estado="<font color=\"".$this->redcolorf."\">Cambio de IPS</font>";
										break;
										case 3:
											$estado="<font color=\"".$this->redcolorf."\">Retiro de EPS</font>";
										break;
										case 4:
											$estado="<font color=\"".$this->redcolorf."\">Alta Voluntaria</font>";
										break;
										case 5:
											$estado="<font color=\"".$this->redcolorf."\">Muerte</font>";
										break;
									}
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								
								break;
								case 21:
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$valor['fecha_ideal_proxima_cita']."</font></label></td>";
								break;
								
								//CASE 22 pruebas de laboratorio
								case 23:
									$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[nombre]."</td>";
							
								break;
								case 24:
									$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[descripcion]."</td>";
								break;
							}
						}
					}
					elseif($v=="C")
					{
						$l=0;
						foreach($codigos as $codigo)
						{
							if($l%2==0)
							{
								$estilo1='hc_submodulo_list_claro';
							}
							else
							{
								$estilo1='hc_submodulo_list_oscuro';
							}
							
							$salida.="		<tr class=\"$estilo1\">";
							$salida.="			<td><label class=\"label\">".$codigo['descripcion']."</label></td>";
							foreach($registros_cod as $registro)
							{
								if($registro['evolucion_id']==$evolucion)
									$ban=1;
								
								if($codigo['codigo_evolucion_id']==$registro['codigo_evolucion_id'])
								{
									if($registro['valor']==1)
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
									else
										$estado="No";
	
									$salida.="		<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
								}
							}
							$l++;
						}
					}
					$k++;
				}
			
				$this->salida.="	<table align=\"center\" border=\"1\"  class=\"hc_table_submodulo_list\" width=\"100%\">";
				$this->salida.="		<tr class=\"modulo_table_list_title\">";
				$this->salida.="			<td width=\"20%\" colspan=\"$cols\">EVOLUCION DE SIGNOS,SINTOMAS Y CONDUCTAS</td>";
				$this->salida.="		</tr>";
				$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="			<td align=\"left\"><label class=\"label\">Fecha</label></td>";
				$this->salida.="		".$fecha;
				$this->salida.="		</tr>";
				$this->salida.="".	$salida;
				$this->salida.="	</tr>";
				$this->salida.="	</table>";
			}
			else
				return false;

			return $this->salida;
		}
		
		function frmForma($vector,$registros,$codigos,$registros_cod,$pruebas,$Laboratorios,$resultadosLab,$signos,$datosEspecialidad,$datosprofesional,$especialidadesT)
		{
			$pfj=SessionGetvar("Prefijo");
			$evolucion=SessionGetvar("Evolucion");
			$paso=SessionGetvar("Paso");

			$this->salida.= ThemeAbrirTablaSubModulo('REGISTRO EVOLUCION');
			
			$this->salida.= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida.= $this->SetStyle("MensajeError");
			$this->salida.= "      </table><br>";
			
			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionReno'));
			
			$this->salida.="<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
			
			$k=0;
			$ok=0;
			$salida="";
			$fecha="";
			$cols=1;
			$evoluciones=array();
			foreach($vector as $v)
			{
				if($k%2==0)
				{
					$estilo='hc_submodulo_list_claro';
				}
				else
				{
					$estilo='hc_submodulo_list_oscuro';
				}
				
				if($v!="C" AND $v!="P")
				{
					if($k>=sizeof($vector)-3)
					{
						$estilo="hc_table_submodulo_list_title";
						$align="align=\"left\"";
					}
					
					$salida.="		<tr class=\"$estilo\">";
					$salida.="			<td $align><label class=\"".$this->SetStyle($v)."\">".$v."</label></td>";
					
					foreach($registros as $valor)
					{
						if($valor['evolucion_id']==$evolucion)
							$ok=1;
							
						switch($k)
						{
							case 0:
								$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".substr($valor['fecha_registro'],0,10)."</td>";
								$cols=($cols*2)+1;
								$evoluciones[]=$valor['evolucion_id'];
								
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['peso']."</label></td>";
							break;
							case 1:
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['talla']."</label></td>";
							break;
							case 2:
								$imc=substr($valor['peso']/(pow($valor['talla']/100,2)),0,5);
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$imc."</label></td>";
							break;
							case 3:
								switch($valor['estado_nutricional'])
								{
									case '1':
										$estado="Normal";
									break;
									case '2':
										$estado="<font color=\"".$this->redcolorf."\">Bajo Peso</font>";
									break;
									case '3':
										$estado="<font color=\"".$this->redcolorf."\">Sobre Peso</font>";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 4:
								$style="";
								if(!empty($valor['taalta']) AND $valor['taalta']>139)
									$style="style=\"color:#990000;font-weight:bold;\"";
								
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\" $style>".$valor['taalta']."</label></td>";
							break;
							case 5:
								$style="";
								if(!empty($valor['tabaja']) AND $valor['tabaja']<55)
									$style="style=\"color:#990000;font-weight : bold; \"";
									
								$salida.="			<td colspan=\"2\" align=\"center\" $style><label class=\"label\" $style>".$valor['tabaja']."</label></td>";
							break;
							case 6:
								$style="";
								if($valor['estadio_kdoqi']>=3)
									$style="style=\"color:#990000;font-weight : bold; \"";
								
								$salida.="			<td colspan=\"2\" align=\"center\" $style><label class=\"label\">".$valor['estadio_kdoqi']."</label></td>";
							break;
							case 7:
								$estado="No";
								if($valor['riesgo_deterioro_acelerado']=="t")
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 8:
								$estado="No";
								if($valor['deterioro_acelerado']=="t")
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 9:
								$estado="No";
								if($valor['retinopatia']=="t")
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 10:
								$estado="No";
								if($valor['lesion_organo_blanco']=="t")
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 11:
								$estado="No";
								if($valor['presencia_ulcera_pies']=="t")
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 12:
								$estado="No";
								if($valor['riesgo_ulcera_pies']=="t")
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 13:
								$estado="Si";
								if($valor['adherencia_farmacologica']=="f")
										$estado="<font color=\"".$this->redcolorf."\">No</font>";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 14:
								$estado="Si";
								if($valor['cambio_habitos_alimenticios']=="f")
										$estado="<font color=\"".$this->redcolorf."\">No</font>";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 15:
								$estado="Si";
								if($valor['habito_actividad_fisica']=="f")
										$estado="<font color=\"".$this->redcolorf."\">No</font>";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 16:
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$valor['riesgo_psicosocial']."</font></label></td>";
							break;
							case 17:
								$estado="Si";
								if($valor['asistencia_grupo_apoyo']=="f")
										$estado="<font color=\"".$this->redcolorf."\">No</font>";
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							
							//case 18 remisiones
							case 19:
								$estado="No";
								if($valor['cierre_caso']=="t")
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							
							break;
							case 20:
								switch($valor['causa_cierre_caso'])
								{
									case 1:
										$estado="Mejoria";
									break;
									case 2:
										$estado="<font color=\"".$this->redcolorf."\">Cambio de IPS</font>";
									break;
									case 3:
										$estado="<font color=\"".$this->redcolorf."\">Retiro de EPS</font>";
									break;
									case 4:
										$estado="<font color=\"".$this->redcolorf."\">Alta Voluntaria</font>";
									break;
									case 5:
										$estado="<font color=\"".$this->redcolorf."\">Muerte</font>";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							
							break;
							case 21:
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$valor['fecha_ideal_proxima_cita']."</font></label></td>";
							break;
							
							//CASE 22 pruebas de laboratorio
							case 23:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[nombre]."</td>";
						
							break;
							case 24:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[descripcion]."</td>";
							break;
						}
					}
					if($ok==0)
					{
						switch($k)
						{
							case 0:
								$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".date("Y-m-d")."</td>";
								$cols=($cols*2)+1;
								$evoluciones[]=$evolucion;
								if($_REQUEST['peso'.$pfj])
									$peso=$_REQUEST['peso'.$pfj];
								else
									$peso=$signos[0][peso];
								
								$salida.="			<td colspan=\"2\" align=\"center\"><input type=\"text\" class=\"input-text\" name=\"peso$pfj\" value=\"$peso\" maxlength=\"6\" size=\"5\"></td>";
							break;
							case 1:
								if($_REQUEST['talla'.$pfj])
									$talla=$_REQUEST['talla'.$pfj];
								else
									$talla=$signos[0][talla];
									
								$salida.="			<td colspan=\"2\" align=\"center\"><input type=\"text\" class=\"input-text\" name=\"talla$pfj\" value=\"$talla\" maxlength=\"6\" size=\"5\"></td>";
							break;
							case 2:
								if($_REQUEST['imc'.$pfj])
									$imc=$_REQUEST['imc'.$pfj];
								else
									$imc=$signos[0][peso]/(pow($signos[0][talla]/100,2));
								
								$salida.="			<td colspan=\"2\" align=\"center\"><input type=\"text\" class=\"input-text\" name=\"imc$pfj\" value=\"$imc\" maxlength=\"5\" size=\"5\" readonly></td>";
							break;
							case 3:
								$sel1="";$sel2="";$sel3="";
								switch($_REQUEST['estado_nutricional'.$pfj])
								{
									case '1':
										$sel1="selected";
									break;
									case '2':
										$sel2="selected";
									break;
									case '3':
										$sel3="selected";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\">";
								$salida.="				<select name=\"estado_nutricional$pfj\" class=\"select\">";
								$salida.="					<option value=\"1\" $sel1>Normal</option>";
								$salida.="					<option value=\"2\" $sel2>Bajo Peso</option>";
								$salida.="					<option value=\"3\" $sel3>Sobre Peso</option>";
								$salida.="				</select>";
								$salida.="			</td>";
							break;
							case 4:
								
								if($_REQUEST['ta_alta'.$pfj])
									$ta_alta=$_REQUEST['ta_alta'.$pfj];
								else
									$ta_alta=$signos[0][ta_alta];
								
								$style="";
								if(!empty($ta_alta) AND $ta_alta>139)
									$style="style=\"color:#990000;font-weight : bold; \"";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><input type=\"text\" class=\"input-text\" $style name=\"ta_alta$pfj\" value=\"$ta_alta\" maxlength=\"4\" size=\"5\"></td>";
							
							break;
							case 5:
								$ta_baja="";
								if($_REQUEST['ta_baja'.$pfj])
									$ta_baja=$_REQUEST['ta_baja'.$pfj];
								else
									$ta_baja=$signos[0][ta_baja];

								$style="";
								if(!empty($ta_baja) AND $ta_baja<55)
									$style="style=\"color:#990000;font-weight : bold; \"";
							
								$salida.="			<td colspan=\"2\" align=\"center\"><input type=\"text\" class=\"input-text\" $style name=\"ta_baja$pfj\" value=\"$ta_baja\" maxlength=\"4\" size=\"5\"></td>";
							
							break;
							case 6:
								$sel1="";$sel2="";$sel3="";$sel4="";$sel5="";
								switch($_REQUEST['kdoqi'.$pfj])
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
									case 5:
										$sel5="selected";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\">";
								$salida.="				<select name=\"kdoqi$pfj\" class=\"select\">";
								$salida.="					<option value=\"1\" $sel1>1</option>";
								$salida.="					<option value=\"2\" $sel2>2</option>";
								$salida.="					<option value=\"3\" $sel3>3</option>";
								$salida.="					<option value=\"4\" $sel4>4</option>";
								$salida.="					<option value=\"5\" $sel5>5</option>";
								$salida.="				</select>";
								$salida.="			</td>";
							break;
							case 7:
								$check1="";$check2="";

								if(!empty($_REQUEST['riesgo_deterioro_acelerado'.$pfj]))
								{
									if($_REQUEST['riesgo_deterioro_acelerado'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
							
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"riesgo_deterioro_acelerado$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"riesgo_deterioro_acelerado$pfj\" value=\"false\" $check2></td>";
							
							break;
							case 8:
								$check1="";$check2="";
								
								if(!empty($_REQUEST['deterioro_acelerado'.$pfj]))
								{
									if($_REQUEST['deterioro_acelerado'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
							
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"deterioro_acelerado$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"deterioro_acelerado$pfj\" value=\"false\" $check2></td>";
							
							break;
							case 9:
								$check1="";$check2="";
								
								if(!empty($_REQUEST['retinopatia'.$pfj]))
								{
									if($_REQUEST['retinopatia'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
							
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"retinopatia$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"retinopatia$pfj\" value=\"false\" $check2></td>";
							
							break;
							
							case 10:
								$check1="";$check2="";
								
								if(!empty($_REQUEST['lesion_organo_blanco'.$pfj]))
								{
									if($_REQUEST['lesion_organo_blanco'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"lesion_organo_blanco$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"lesion_organo_blanco$pfj\" value=\"false\" $check2></td>";
							
							break;
							case 11:
								$check1="";$check2="";
								
								if(!empty($_REQUEST['presencia_ulcera_pies'.$pfj]))
								{
									if($_REQUEST['presencia_ulcera_pies'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"presencia_ulcera_pies$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"presencia_ulcera_pies$pfj\" value=\"false\" $check2></td>";
							
							break;
							case 12:
								
								$check1="";$check2="";
								if(!empty($_REQUEST['presencia_ulcera_pies'.$pfj]))
								{
									if($_REQUEST['presencia_ulcera_pies'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"riesgo_ulcera_pies$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"riesgo_ulcera_pies$pfj\" value=\"false\" $check2></td>";
							
							break;
							case 13:
								
								$check1="";$check2="";
								if(!empty($_REQUEST['af'.$pfj]))
								{
									if($_REQUEST['af'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"af$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"af$pfj\" value=\"false\" $check2></td>";
							
							break;
							case 14:
								$check1="";$check2="";
								if(!empty($_REQUEST['cambio_habitos_alimenticios'.$pfj]))
								{
									if($_REQUEST['cambio_habitos_alimenticios'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"cambio_habitos_alimenticios$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"cambio_habitos_alimenticios$pfj\" value=\"false\" $check2></td>";
							break;
							case 15:
								$check1="";$check2="";
								if(!empty($_REQUEST['habito_actividad_fisica'.$pfj]))
								{
									if($_REQUEST['habito_actividad_fisica'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"habito_actividad_fisica$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"habito_actividad_fisica$pfj\" value=\"false\" $check2></td>";
					
							break;
							case 16:
										$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\"><font color=\"".$this->redcolorf."\">".$_SESSION['puntaje'][$evolucion]."</font></label></td>";
										$salida.="	<input type=\"hidden\" name=\"riesgo_psicosocial$pfj\" value=\"".$_SESSION['puntaje'][$evolucion]."\">";
									
							break;
							case 17:
								$check1="";$check2="";
								if(!empty($_REQUEST['asistencia_grupo_apoyo'.$pfj]))
								{
									if($_REQUEST['asistencia_grupo_apoyo'.$pfj]=='true')
										$check1="checked";
									else
										$check2="checked";
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"asistencia_grupo_apoyo$pfj\" value=\"true\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"asistencia_grupo_apoyo$pfj\" value=\"false\" $check2></td>";
					
							break;
							
							
							//case 18 remisiones
							case 19:
							
								$check1="";$check2="";$disabled="";
								if(!empty($_REQUEST['cierre_caso'.$pfj]))
								{
									if($_REQUEST['cierre_caso'.$pfj]=='true')
									{
										$check1="checked";
										$disabled="disabled=\"false\"";
									}
									else
									{
										$check2="checked";
										$disabled="disabled=\"true\"";
									}
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"cierre_caso$pfj\" value=\"true\" $check1 onclick=\"AccionCausa(this.form,false);\"></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"cierre_caso$pfj\" value=\"false\" $check2 onclick=\"AccionCausa(this.form,true);\"></td>";
								
							break;
							case 20:
								$sel1="";$sel2="";$sel3="";$sel4="";$sel5="";
								switch($_REQUEST['causa_cierre_caso'.$pfj])
								{
									case '1':
										$sel1="selected";
									break;
									case '2':
										$sel2="selected";
									break;
									case '3':
										$sel3="selected";
									break;
									case '4':
										$sel4="selected";
									break;
									case '5':
										$sel5="selected";
									break;
								}
								
								$salida.="			<td colspan=\"2\" align=\"center\" id=\"capa_causa\">";
								$salida.="				<select name=\"causa_cierre_caso$pfj\" class=\"select\" $disabled>";
								$salida.="					<option value=\"1\" $sel1>Mejoria</option>";
								$salida.="					<option value=\"2\" $sel2>Cambio de IPS</option>";
								$salida.="					<option value=\"3\" $sel3>Retiro de EPS</option>";
								$salida.="					<option value=\"4\" $sel4>Alta Voluntaria</option>";
								$salida.="					<option value=\"5\" $sel5>Muerte</option>";
								$salida.="				</select>";
								$salida.="			</td>";
							
							break;
							case 21:
								$salida.="			<td colspan=\"2\" align=\"center\"><input type=\"text\" class=\"input-text\" name=\"proxima_cita$pfj\" maxlength=\"10\" size=\"10\" value=\"".$_REQUEST['proxima_cita'.$pfj]."\"><sub>".ReturnOpenCalendario("formades$pfj","proxima_cita$pfj","-")."</sub></td>";
						
							break;
							
							//CASE 22 pruebas de laboratorio
							case 23:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$datosprofesional[0][nombre]."</td>";
							break;
							case 24:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$datosprofesional[0][descripcion]."</td>";
							break;
						}
					}
					$salida.="		</tr>";
				}
				elseif($v=="C")
				{
					$l=0;
					foreach($codigos as $codigo)
					{
						if($l%2==0)
						{
							$estilo1='hc_submodulo_list_claro';
						}
						else
						{
							$estilo1='hc_submodulo_list_oscuro';
						}
						
						$salida.="		<tr class=\"$estilo1\">";
						$salida.="			<td><label class=\"".$this->SetStyle("C$l")."\">".$codigo['descripcion']."</label></td>";
						$ban=0;
						foreach($registros_cod as $registro)
						{
							if($registro['evolucion_id']==$evolucion)
								$ban=1;
							
							if($codigo['codigo_evolucion_id']==$registro['codigo_evolucion_id'])
							{
								if($registro['valor']==1)
									$estado="<font color=\"".$this->redcolorf."\">Si</font>";
								else
									$estado="No";

								$salida.="		<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							}
						}
						if($ban==0)
						{
							$check1="";$check2="";
							if(!empty($_REQUEST['nombre'.$pfj][$l]))
							{
								if($_REQUEST['nombre'.$pfj][$l]=="1ç".$codigo['codigo_evolucion_id'])
									$check1="checked";
								else
									$check2="checked";
							}
							
							$vector2="";
							$capa="";
							$xcapa="";
							$onclick="";
							
							if(!empty($codigo['especialidad']))
							{
								$especialidad=$datosEspecialidad[$l];
								$vector2="new Array('".$especialidad[0][especialidad]."','".$especialidad[0][descripcion]."','".$especialidad[0][cargo]."','".$especialidad[0][tipo_consulta_id]."','capa$l','nombre".$pfj."[$l]','".$codigo['codigo_evolucion_id']."','".$codigo['sw_opcion_sino']."')";
								$capa="id=\"capa$l\"";
								$xcapa="id=\"xcapa$l\"";
								$onclick=" onclick=\"Obtener($vector2);\"";
							}
							elseif(empty($codigo['especialidad']) AND empty($codigo['cargo_cups']))
							{
								$onclick=" onclick=\"IniciarInterBusqueda('BUSQUEDA ESPECIALIDAD','capa$l','nombre".$pfj."[$l]','".$codigo['codigo_evolucion_id']."','".$codigo['sw_opcion_sino']."');MostrarSpan('ContainerB')\"";
								$capa="id=\"capa$l\"";
								$xcapa="id=\"xcapa$l\"";
							}
							
							$salida.="<td align=\"center\" $capa><input type=\"radio\" name=\"nombre".$pfj."[$l]\" value=\"1ç".$codigo['codigo_evolucion_id']."\" $onclick $check1></td>";
							$salida.="<td align=\"center\" $xcapa><input type=\"radio\" name=\"nombre".$pfj."[$l]\" value=\"2ç".$codigo['codigo_evolucion_id']."\" $check2></td>";
						}
						$l++;
					}
				}
				elseif($v=="P")
				{
					$salida.="<tr class=\"modulo_table_list_title\">";
					$salida.="	<td colspan=\"$cols\">PRUEBAS DE LABORATORIO</td>";
					$salida.="</tr>";
					
					$n=0;
					foreach($pruebas as $pruebasLab)
					{
						if($n%2==0)
						{
							$estilo2='hc_submodulo_list_claro';
						}
						else
						{
							$estilo2='hc_submodulo_list_oscuro';
						}
						
						if(empty($pruebasLab['alias']))
							$descripcion=$pruebasLab['descripcion'];
						else
							$descripcion=$pruebasLab['alias'];
						
						$salida.="		<tr class=\"$estilo2\">";
						$salida.="			<td><label class=\"label\">".$descripcion."</label></td>";
						
						$r=0;
						while($r<sizeof($evoluciones))
						{
							$a=0;
							foreach($resultadosLab as $resultados)
							{
								if($pruebasLab['cargo_cups']==$resultados['cargo'] AND $resultados['evolucion_id']==$evoluciones[$r])
								{
									$sw_modo=$resultados['sw_modo_resultado'];
									$resultado_id=$resultados['resultado_id'];
									$salida.="<td align=\"center\" colspan=\"2\">";
									$datos="resultado_id=".$resultado_id."&sw_modo=".$sw_modo;
									$url="classes/Visualizar/Visualizar.class.php?".$datos;
									if($resultados['sw_alerta'])
										$salida.="	<a href=\"javascript:AbrirVentanaVer('$url')\"><b>Ver</b></a>";
									else
										$salida.="	<a href=\"javascript:AbrirVentanaVer('$url')\">Ver</a>";
									$salida.="</td>";
									$a=1;
									break;
								}
							}
							if($a==0)
							{
								$b=0;
							
								foreach($Laboratorios as $lab)
								{
									if($pruebasLab['cargo_cups']==$lab['cargo'] AND $lab['evolucion_id']==$evoluciones[$r])
									{
										$salida.="<td align=\"center\" colspan=\"2\" id=\"trans$n$r\">";
										$datos="cargo=".$lab['cargo']."&descripcion=".$lab['descripcion']."&op=Transcribir&periodo=".($r+1)."&estilo=$estilo&evolucion_id=".$lab['evolucion_id']."&trans=trans$n$r";
										$url="classes/Transcripcion/TranscripcionExamenes.class.php?".$datos;
										$salida.="	<a href=\"javascript:AbrirVentana('$url')\">Transcribir</a>";
										$salida.="</td>";
										$b=1;
										break;
									}
								}
							}
							if($a==0 AND $b==0)
									$salida.="			<td align=\"center\" colspan=\"2\">&nbsp;</td>";
							$r++;
						}
						$salida.="</tr>";	
						$n++;
					}
				}
				$k++;
			}
			
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"20%\" colspan=\"$cols\">EVOLUCION DE SIGNOS,SINTOMAS Y CONDUCTAS</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="			<td align=\"left\"><label class=\"label\">Fecha</label></td>";
			$this->salida.="		".$fecha;
			$this->salida.="		</tr>";
			$this->salida.="".	$salida;
			$this->salida.="	</tr>";
			$this->salida.="	</table>";
			
			$this->salida.="	<table border=\"0\" align=\"center\" cellspacing=\"20\">";
			$this->salida.="		<tr>";
			$this->salida.="			<td><input class=\"input-submit\" type=\"submit\" name=\"guardar$pfj\" value=\"GUARDAR\"></td>";
			$this->salida.="</form>";
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'GraficasSeguimientoReno'));
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'GrupoRiesgoRenoproteccion'));
			
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
			
			$this->salida .= "		function AccionCausa(forma,x)";
			$this->salida .= "		{";
			$this->salida .= "			forma.causa_cierre_caso$pfj.disabled=x;";
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
			$this->salida .= "	 var capaActual = '';\n";
			$this->salida .= "	 var datos = new Array();\n";
			
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
			
			$this->salida .= "	function IniciarInterBusqueda(tit,capa,nombre,codigo,op)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datos=new Array();";
			$this->salida .= "	  titulo = 'tituloB';\n";
			$this->salida .= "	  contenedor = 'ContainerB';\n";
			$this->salida .= "	  capaActual= capa;\n";
			$this->salida .= "	  datos[4] = ''+capa;\n";
			$this->salida .= "	  datos[5] = ''+nombre;\n";
			$this->salida .= "	  datos[6] = ''+codigo;\n";
			$this->salida .= "	  datos[7] = ''+op;\n";
			$this->salida .= "	  document.interconsultaB.busqueda.value=''\n";
			$this->salida .= "		document.getElementById(titulo).innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop());\n";
			$this->salida .= "	  xResizeTo(ele,360, 'auto');\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,340, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarB');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 340, 0);\n";
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
			$this->salida .= "	  	datos[9]=obs;\n";
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
			
			$this->salida .= "	function BusquedaEsp(forma)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var resultado=jsrsArrayFromString(forma.busqueda.value,'ç')\n";
			$this->salida .= "		datos[0]=''+resultado[0];\n";
			$this->salida .= "		datos[1]=''+resultado[1];\n";
			$this->salida .= "		datos[2]=''+resultado[2];\n";
			$this->salida .= "		datos[3]='NULL';\n";
			$this->salida .= "		Cerrar('ContainerB')\n";
			$this->salida .= "		jsrsExecute('classes/modules/InterCPN/Inter.php',TraerForma,'TraerForma',datos);";
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

			$this->salida .= "<div id='ContainerB' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='tituloB' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrarB' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContainerB')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='errorB' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "		<form name=\"interconsultaB\" action=\"\" method=\"post\">";
			$this->salida .= "			<div id='InterconsultaB'>\n";
			$this->salida .= "			<table border=\"0\" align=\"center\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_table_list_title\" colspan=\"2\">ESPECIALIDADES</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_oscuro\">\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						SELECCIONE: <select name=\"busqueda\" class=\"select\">\n";
			$this->salida .= "						<option value=\"\">--SELECCIONE ESPECIALIDAD--</option>\n";
			for($i=0;$i<sizeof($especialidadesT);$i++)
				$this->salida .= "						<option value=\"".$especialidadesT[$i][especialidad]."ç".$especialidadesT[$i][descripcion]."ç".$especialidadesT[$i][cargo]."\">".$especialidadesT[$i][descripcion]."</option>\n";
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<input type=\"button\" name=\"Aceptar\" value=\"ACEPTAR\" class=\"input-submit\" onclick=\"BusquedaEsp(this.form)\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table\n";
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
			$obj=$this->obj;
			$pfj=$obj->frmPrefijo;
			
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