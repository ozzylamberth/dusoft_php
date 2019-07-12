<?php

/**
* Submodulo de Atención (HTML).
*
* Submodulo para manejar el tipo de atención (rips) de un paciente en una evolución.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_Atencion_HTML.php,v 1.4 2006/08/10 21:29:06 carlos Exp $
*/

/**
* Atencion_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo atencion, se extiende la clase Atencion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Atencion_HTML extends Atencion
{

	function Atencion_HTML()
	{
	    $this->Atencion();//constructor del padre
       	return true;
	}


	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$result=$this->ConsultaAtencion();
		if(!$result->EOF)
		{
			$this->salida.="<br><table width=\"100%\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td align=\"center\">";
			$this->salida .= "<label class=\"label\">ORIGEN DE LA ATENCION</label>";
			$this->salida .="</td>";
			$this->salida .="</tr>";
			$s=0;
			while (!$result->EOF)
			{
				$this->salida .="<tr>";
				if($s==0)
				{
					$this->salida .="<td align=\"center\" class=\"hc_submodulo_list_oscuro\">";
					$s=1;
				}
				else
				{
					$this->salida .="<td align=\"center\" class=\"hc_submodulo_list_claro\">";
					$s=0;
				}
				$this->salida .=$result->fields[0];
				$this->salida .="</td>";
				$this->salida .="</tr>";
				$result->MoveNext();
			}
			$this->salida .="</table><br>";
		}
		else
		{
			return false;
		}
    	return true;
	}



	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$result=$this->ConsultaAtencion();
		if(!$result->EOF)
		{
			$salida .="<br><table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida .="<td align=\"center\" width=\"50%\">";
			$salida .="<label class=\"label\">ORIGEN DE LA ATENCION</label>";
			$salida .="</td>";
			$s=0;
			while (!$result->EOF)
			{
				if($s==0)
				{
					$salida .="<td align=\"center\" class=\"hc_submodulo_list_oscuro\">";
					$s=1;
				}
				else
				{
					$salida .="<td align=\"center\" class=\"hc_submodulo_list_claro\">";
					$s=0;
				}
				$salida .=$result->fields[0];
				$salida .="</td>";
				$result->MoveNext();
			}
			$salida .="</tr>";
			$salida .="</table><br>";
		}
		else
		{
			return false;
		}
    return $salida;
	}



	function SetStyle($campo)
	{
		if ($this->frmError[$campo]||$campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td class=\"label_error\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
				return ("label_error");
		}
		return ("label");
	}

	function frmForma($val)
	{
		$pfj=$this->frmPrefijo;
		$atencion=$this->AtencionConsulta();
		if(empty($this->titulo))
		{
			$this->salida  = ThemeAbrirTablaSubModulo('ORIGEN DE LA ATENCION');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
		if($this->SetStyle("MensajeError"))
		{
			$this->salida.="<table align=\"center\">";
			$this->salida.=$this->SetStyle("MensajeError");
			$this->salida.="</table><br>";
		}
		$salida.= "<table width=\"100%\" align=\"center\" border=\"1\" class=\"hc_table_submodulo_list\">";
		//$this->salida.="<select name=\"atencion".$pfj."\" class=\"select\">";
		//$riesgo=$this->RiesgoAtencion();
		$i=0;
		$p=4;
		$s==0;
		while($i<sizeof($atencion[0]))
		{
			if ($p==4)
			{
				$p=0;
				$salida.= "<tr>";
			}
			else
			{
				if($s==0)
				{
					$salida.="<td class=\"hc_submodulo_list_oscuro\">";
					$s=1;
				}
				else
				{
					$salida.="<td class=\"hc_submodulo_list_claro\">";
					$s=0;
				}
				if($atencion[2][$i]!="")
				{
					$salida.="<input type=\"radio\" value=\"".$atencion[1][$i]."\" checked name=\"atencion".$pfj."\" class=\"input-text\"><label class=\"label\">".$atencion[0][$i]."</label>";
				}
				else
				{
					$salida.="<input type=\"radio\" name=\"atencion".$pfj."\" value=\"".$atencion[1][$i]."\"><label class=\"label\">".$atencion[0][$i]."</label>";
				}
				$i++;
				$salida.= "</td>";
				$j=0;
				while($j<sizeof($riesgo))
				{
					if($atencion[2][$i]!="")
					{
						if($riesgo[$j]['tipo_atencion_id']==$atencion[1][$i])
						{
							unset($riesgo[$j]);
						}
					}
					$j++;
				}
			}
			$p= $p+1;
			if ($p==4)
			{
				$salida.= "</tr>";
			}

		}
		$salida.= "</table>";
		$t=0;
		foreach($riesgo as $k=>$v)
		{
			if($t==0)
			{
				$this->salida.= "<table width=\"100%\" align=\"center\">";
				$t=1;
			}
			$this->salida.= "<tr align=\"center\">";
			$this->salida.= "<td>";
			$this->salida.= "<label class=\"label_error\">Posible Enfermedad Profesional</label>";
			$this->salida.= "</td>";
			$this->salida.= "</tr>";
		}
		if($t==1)
		{
			$this->salida.= "</table>";
			$this->salida.="<br>";
		}
		$this->salida.=$salida;
		$this->salida.="<br>";
		$this->salida.="<table width=\"0\" align=\"center\"><tr><td>";
		$this->salida.="<input type=\"submit\" value=\"INSERTAR\" class=\"input-submit\">";
		$this->salida.="</td></tr></table>";
		$this->salida.="</form>";
		$this->frmConsulta();
		$this->OrigenAtencion_PrimeraVez($val);
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

		//OROGEN DE LA ATENCION PRIMERA VEZ
		function OrigenAtencion_PrimeraVez($val)
		{
				$pfj=$this->frmPrefijo;
				$Primer_OrigenAtencion = $this->GetOrigenAtencion_PrimeraVez();
				if($this->primera_Evo != $this->evolucion)
					{ 
								if(!empty($Primer_OrigenAtencion->fields[0]))
								{
									if($val!='1')
										{
											$this->salida.="<table width=\"70%\" border=\"0\" align=\"right\">";
											$acc=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'','valor'=>'1'));
											$this->salida.="<tr  align=\"right\"><br><td><a href=\"$acc\">VER DATOS ORIGEN ATENCION DE PRIMERA CITA</a>";
											$this->salida.="</td></tr>";
											$this->salida.="</table>";
										}
										else
										{
											$this->salida.="<br><table width=\"100%\" align=\"center\" class=\"hc_table_submodulo_list\">";
											$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
											$this->salida .="<td align=\"center\">";
											$this->salida .= "<label class=\"label\">ORIGEN DE LA ATENCION CITA PRIMERA VEZ</label>";
											$this->salida .="</td>";
											$this->salida .="</tr>";
											$s=0;
											while (!$Primer_OrigenAtencion->EOF)
											{
													$this->salida .="<tr>";
													if($s==0)
													{
															$this->salida .="<td align=\"center\" class=\"hc_submodulo_list_oscuro\">";
															$s=1;
													}
													else
													{
															$this->salida .="<td align=\"center\" class=\"hc_submodulo_list_claro\">";
															$s=0;
													}
													$this->salida .=$Primer_OrigenAtencion->fields[0];
													$this->salida .="</td>";
													$this->salida .="</tr>";
													$Primer_OrigenAtencion->MoveNext();
											}
											$acc=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'','valor'=>''));
											$this->salida.="<tr  align=\"right\"><br><td><a href=\"$acc\">OCULTAR</a>";
											$this->salida.="</td></tr>";
											$this->salida .="</table><br>";
										}
								}
								else
								{
										$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
										$this->salida.="<tr  align=\"center\"><br><td><label class='label_mark'>EL PACIENTE NO TIENE REGISTRO DE ORIGEN ATENCION DE PRIMERA CITA ODONTOLOGICA</label>";
										$this->salida.="</td></tr>";
										$this->salida.="</table>";
										return false;
								}
					}
					return true;
			}
}

?>
