<?php
	/********************************************************************************* 
 	* $Id: hc_GrupoRiesgoRenoproteccion_GrupoRiesgo_HTML.class.php,v 1.2 2009/11/06 18:18:37 hugo Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_GrupoRiesgoRenoproteccion
	* 
 	**********************************************************************************/

	class GrupoRiesgo_HTML
	{

		function GrupoRiesgo_HTML()
		{
			$this->redcolorf="#990000";
			return true;
		}
		
		function frmHistoria($riesgosbp)
		{
			if(sizeof($riesgosbp)>0)
			{
				$salida="";
				$fecha="";
				$puntaje=0;
				foreach($riesgosbp as $valor)
				{
					$salida1="";
					if($k%2==0)
						$estilo="hc_submodulo_list_claro";
					else
						$estilo="hc_submodulo_list_oscuro";
					
					$salida.="<tr class=\"$estilo\" height=\"20\">";
					
					if(!$valor['grupo_id'])
						$salida1.="<td align=\"center\" class=\"label_error\">Si</td>";
					else
					{
						$class="class=\"label\"";
						if($valor['valor'] > 0)
							$class="class=\"label_error\"";
							
						$salida1.="<td align=\"center\" $class>".$valor['descripcion_grupo']."</td>";
					}
					
					$fecha="<td align=\"center\">".$valor['fecha']."</td>";
					
					$puntaje=$puntaje+$valor['valor'];
					
					$salida.="<td align=\"left\" class=\"label\">".$valor['descripcion']."</td>";
					$salida.="$salida1";
					$salida.="</tr>";
					$k++;
				}
				
				$this->salida.="	<br><table align=\"center\" border=\"1\"  class=\"hc_table_submodulo_list\" width=\"100%\">";
				$this->salida.="		<tr class=\"modulo_table_list_title\">";
				$this->salida.="			<td width=\"80%\">RIESGO PSICOSOCIAL</td>";
				$this->salida.="			$fecha";
				$this->salida.="		</tr>";
				$this->salida.="		$salida";
				$this->salida.="	<tr class=\"modulo_table_list_title\">";
				$this->salida.="		<td>Puntaje</td>";
				$this->salida.="		<td align=\"center\">$puntaje</td>";
				$this->salida.="	</tr>";
				$this->salida.="</table><br>";
			}
			else
				return false;
			
			
			return $this->salida;
		}
		
		function frmConsulta($riesgosbp)
		{
			if(sizeof($riesgosbp)>0)
			{
				$salida="";
				$fecha="";
				$puntaje=0;
				foreach($riesgosbp as $valor)
				{
					$salida1="";
					if($k%2==0)
						$estilo="hc_submodulo_list_claro";
					else
						$estilo="hc_submodulo_list_oscuro";
					
					$salida.="<tr class=\"$estilo\" height=\"20\">";
					
					if(!$valor['grupo_id'])
						$salida1.="<td align=\"center\" class=\"label_error\">Si</td>";
					else
					{
						$class="class=\"label\"";
						if($valor['valor'] > 0)
							$class="class=\"label_error\"";
							
						$salida1.="<td align=\"center\" $class>".$valor['descripcion_grupo']."</td>";
					}
					
					$fecha="<td align=\"center\">".$valor['fecha']."</td>";
					
					$puntaje=$puntaje+$valor['valor'];
					
					$salida.="<td align=\"left\" class=\"label\">".$valor['descripcion']."</td>";
					$salida.="$salida1";
					$salida.="</tr>";
					$k++;
				}
				
				$this->salida.="	<br><table align=\"center\" border=\"1\"  class=\"hc_table_submodulo_list\" width=\"100%\">";
				$this->salida.="		<tr class=\"modulo_table_list_title\">";
				$this->salida.="			<td width=\"80%\">RIESGO PSICOSOCIAL</td>";
				$this->salida.="			$fecha";
				$this->salida.="		</tr>";
				$this->salida.="		$salida";
				$this->salida.="	<tr class=\"modulo_table_list_title\">";
				$this->salida.="		<td>Puntaje</td>";
				$this->salida.="		<td align=\"center\">$puntaje</td>";
				$this->salida.="	</tr>";
				$this->salida.="</table><br>";
			}
			else
				return false;
			
			
			return $this->salida;
		}
		
		function frmForma($datos_adicionales,$tipos_raza,$riesgosR,$conteo,$DatosR,$grupos,$puntajes,$ocupacion)
		{
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");

			$this->salida.= ThemeAbrirTablaSubModulo('DATOS CLINICOS POR GRUPO DE RIESGO');
			
			$this->salida.= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida.= $this->SetStyle("MensajeError");
			$this->salida.= "      </table><br>";
			
			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'GrupoRiesgoRenoproteccion'));
			
			$this->salida.="<form name=\"formariesgo$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"40%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td colspan=\"2\">ANTECEDENTES EPIDEMIOLOGICOS</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.="			<td class=\"label\">RAZA</td>";
			$this->salida.="			<td align=\"center\">";
			$this->salida.="			<select name=\"raza$pfj\" class=\"select\">";
			$this->salida.="				<option value=\"\">--SELECCIONE--</option>";
			for($i=0;$i<sizeof($tipos_raza);$i++)
			{
				if($tipos_raza[$i][tipo_raza_id]==$datos_adicionales[0][tipo_raza_id])
					$sel="selected";
				else
					$sel="";
					
				$this->salida.="				<option value=\"".$tipos_raza[$i][tipo_raza_id]."\" $sel>".$tipos_raza[$i][descripcion]."</option>";
			}
			$this->salida.="			</select>";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.="			<td class=\"label\">OCUPACION</td>";

			$ocupacion=$ocupacion[0][ocupacion];
			if(!$ocupacion)
			{
				$ocupacion=$datos_adicionales[0][ocupacion_descripcion];
				if($_REQUEST['ocupacion'.$pfj])
					$ocupacion=$_REQUEST['ocupacion'.$pfj];
			}
				
			$this->salida.="			<td align=\"center\"><textarea class=\"input-text\" name=\"ocupacion$pfj\" cols=\"40\" rows=\"2\">".$ocupacion."</textarea></td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table><br>";
			
			$k=0;
			$salida="";
			
			foreach($riesgosR as $Riesgos)
			{
				($k%2==0)? $estilo="hc_submodulo_list_claro" : $estilo="hc_submodulo_list_oscuro";
				
				$fecha="";
				$puntaje="";
				$br=0;
				
				$salida.="	<tr class=\"$estilo\" height=\"25\">";
				$salida.="		<td class=\"label\">".$Riesgos['descripcion']."</td>";
				foreach($DatosR as $key=>$valor)
				{
					if($key==$evolucion) $br=1;
					$ban=0;	
					foreach($valor as $DatosRiesgos)
					{
						if($Riesgos['riesgo_id']==$DatosRiesgos['riesgo_id'])
						{
							$ban1=0;
							foreach($puntajes as $p)
							{
								if($key==$p['evolucion_id'])
								{
									$puntaje.="		<td>".$p['puntaje']."</td>";
									$_SESSION['puntaje'][$key]=$p['puntaje'];
									$ban1=1;
									break;
								}
							}
							if($ban1==0)
								$puntaje.="		<td>0</td>";
								
							if($key < $evolucion)
							{
								$fecha.="<td align=\"center\" width=\"10%\">".$DatosRiesgos['fecha']."</td>";
								if(!$Riesgos['grupo_id'])
									$salida.="<td align=\"center\"><label class=\"label_error\">".$DatosRiesgos['valor']."</label><img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></td>";
								else
								{
									$gp=null;
									foreach($grupos as $GR)
										if($Riesgos['grupo_id']==$GR['grupo_id'])
											$gp[]=$GR;
											
									foreach($gp as $GruposR)
									{
										if($GruposR['puntaje_grupo']==$DatosRiesgos['valor'])
										{
											$style="";
											if($DatosRiesgos['valor']>0)
												$style="style=\"color:#990000;font-weight:bold;\"";
	
											$salida.="<td align=\"center\"><label class=\"label\" $style>".$GruposR['descripcion_grupo']."</label></td>";
											break;
										}
									}
								}
							}
							else
							{
								$fecha.="<td align=\"center\" width=\"10%\">".date("Y-m-d")."</td>";
								
								if(!$Riesgos['grupo_id'])
									$salida.="<td align=\"center\"><label class=\"label_error\">".$DatosRiesgos['valor']."</label><input type=\"checkbox\" name=\"riesgos$pfj"."[]\" value=\"".$Riesgos['puntaje']."ç".$Riesgos['riesgo_id']."\" checked></td>";
								else
								{
									$gp=null;
									foreach($grupos as $GR)
										if($Riesgos['grupo_id']==$GR['grupo_id'])
											$gp[]=$GR;
										
									$salida.="	<td align=\"center\">";
									$salida.="		<select name=\"riesgos$pfj"."[]\" class=\"select\">";
									foreach($gp as $GruposR)
									{
										if($GruposR['puntaje_grupo']==$DatosRiesgos['valor'])
											$sel="selected";
										else
											$sel="";
										$salida.="		<option value=\"".$GruposR['puntaje_grupo']."ç".$Riesgos['riesgo_id']."\" $sel>".$GruposR['descripcion_grupo']."</option>";
									}
									$salida.="		</select>";
									$salida.="	</td>";
								}
							}
							$ban=1;
							break;
						}
					}
					if($ban==0)
					{
						$fecha.="<td align=\"center\" width=\"10%\">".date("Y-m-d")."</td>";
						if($key == $evolucion)
						{
							if(!$Riesgos['grupo_id'])
								$salida.="<td align=\"center\"><input type=\"checkbox\" name=\"riesgos$pfj"."[]\" value=\"".$Riesgos['puntaje']."ç".$Riesgos['riesgo_id']."\"></td>";
							else
							{
								$gp=null;
								foreach($grupos as $GruposR)
									if($Riesgos['grupo_id']==$GruposR['grupo_id'])
										$gp[]=$GruposR;
								
								$salida.="	<td align=\"center\">";
								$salida.="		<select name=\"riesgos$pfj"."[]\" class=\"select\">";
								
								foreach($gp as $GruposR)
								{
									($GruposR['puntaje_grupo']==0)? $sel="selected" :$sel="";
									$salida.="		<option value=\"".$GruposR['puntaje_grupo']."ç".$Riesgos['riesgo_id']."\" $sel>".$GruposR['descripcion_grupo']."</option>";
								}
								$salida.="		</select>";
								$salida.="	</td>";
							}
						}
						else
							$salida.="	<td align=\"center\">&nbsp;</td>";
					}
				}
				if($br==0)
				{
					$fecha.="<td align=\"center\" width=\"10%\">".date("Y-m-d")."</td>";
					
					$ban1=0;
					foreach($puntajes as $p)
					{
						if($evolucion==$p['evolucion_id'])
						{
							$puntaje.="		<td>".$p['puntaje']."</td>";
							$_SESSION['puntaje'][$evolucion]=$p['puntaje'];
							$ban1=1;
							break;
						}
					}
					if($ban1==0)
						$puntaje.="		<td>0</td>";
					
					if(!$Riesgos['grupo_id'])
						$salida.="<td align=\"center\"><input type=\"checkbox\" name=\"riesgos$pfj"."[]\" value=\"".$Riesgos['puntaje']."ç".$Riesgos['riesgo_id']."\"></td>";
					else
					{
						$gp=null;
						foreach($grupos as $GruposR)
							if($Riesgos['grupo_id']==$GruposR['grupo_id'])
								$gp[]=$GruposR;
						
						$salida.="	<td align=\"center\">";
						$salida.="		<select name=\"riesgos$pfj"."[]\" class=\"select\">";
						
						foreach($gp as $GruposR)
						{
							if($GruposR['puntaje_grupo']==0)
								$sel="selected";
							else
								$sel="";
							$salida.="		<option value=\"".$GruposR['puntaje_grupo']."ç".$Riesgos['riesgo_id']."\" $sel>".$GruposR['descripcion_grupo']."</option>";
						}
						$salida.="		</select>";
						$salida.="	</td>";
					}
				}
				
				$salida.="	</tr>";
				$k++;
			}
			
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"80%\">RIESGO PSICOSOCIAL</td>";
			$this->salida.="			$fecha";
			$this->salida.="		</tr>";
			$this->salida.="		$salida";
			$this->salida.="	<tr class=\"modulo_table_list_title\">";
			$this->salida.="		<td>Puntaje</td>";
			$this->salida.="		$puntaje";
			$this->salida.="	</tr>";
			$this->salida.="</table><br>";
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionReno'));
			$accion2=ModuloHCGetURL($evolucion,-1,0,'',false);
			
			$this->salida.="	<table align=\"center\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"guardar$pfj\" value=\"GUARDAR\"></td>";
			$this->salida.="</form>";
			$this->salida.="<form name=\"formasig$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"siguiente$pfj\" value=\"SIGUIENTE\"></td>";
			$this->salida.="</form>";
			$this->salida.="<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></td>";
			$this->salida.="</form>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";

			$this->salida.=ThemeCerrarTablaSubModulo();
			
			return $this->salida;
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