<?php

/**
* Submodulo de Protocolos Medicos (HTML).
*
* Submodulo para manejar los diferentes pasos que se debe seguir con un paciente según unas caracteristicas del
* paciente y demas datos.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_Vacunacion_HTML.php,v 1.2 2005/03/09 13:42:34 tizziano Exp $
*/

/**
* ProtocolosMedicos_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo protocolos medicos, se extiende la clase ProtocolosMedicos y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Vacunacion_HTML extends Vacunacion
{

/**
* Esta función Inicializa la clase Vacunación
*
* @access private
* @return boolean Para identificar que se realizo.
*/

	function Vacunacion_HTML()
	{
	    $this->Vacunacion();//constructor del padre
       	return true;
	}


	function frmConsulta()
	{
	  list($dbconn) = GetDBconn();
		$empresa=SessionGetVar('SYSTEM_USUARIO_EMPRESA');
		$query = "select razon_social from empresas where empresa_id='".$empresa."'";
		$result = $dbconn->Execute($query);
		$empresa=$result->fields[0];
		$query = "select c.nombre, b.nombre, a.fecha, a.evolucion_id, b.vacuna_id, b.dosis_id, a.lugar from hc_vacunas_cumplidas as a, dosis as b, vacunas as c where a.tipo_id_paciente='".$this->tipoidpaciente."' and a.paciente_id='".$this->paciente."' and b.vacuna_id=a.vacuna_id and b.dosis_id=a.dosis_id and a.vacuna_id=c.vacuna_id order by b.vacuna_id,b.dosis_id;";
		$result = $dbconn->Execute($query);
    $i=0;
		if ($dbconn->ErrorNo() != 0)
	  {
      return false;
    }
		else
		{
      while (!$result->EOF)
			{
			  $dosis[0][$i]=$result->fields[0];
				$dosis[1][$i]=$result->fields[1];
				$dosis[2][$i]=$result->fields[2];
				$dosis[3][$i]=$result->fields[3];
				$dosis[4][$i]=$result->fields[4];
				$dosis[5][$i]=$result->fields[5];
				$dosis[6][$i]=$result->fields[6];
				$result->MoveNext();
				$i++;
			 }
		}
	  $this->salida  = ThemeAbrirTablaSubModulo('Protocolos Medicos');
		if($i<>0)
		{
			$this->salida .='<table width="90%" border="1" align="center" class="hc_table_list">';
			$this->salida .='<tr align="center" class="hc_table_submodulo_list_title">';
			$this->salida .='<td rowspan="1">';
			$this->salida .='Vacuna';
			$this->salida .='</td>';
			$this->salida .='<td rowspan="1">';
			$this->salida .='No. de Dosis';
			$this->salida .='</td>';
			$this->salida .='<td colspan="1">';
			$this->salida .='Aplicación';
			$this->salida .='</td>';
			$this->salida .='</tr>';
			$i=0;
			$spy=0;
			while($i<sizeof($dosis[0]))
			{
				$t=$i;
				$s=0;
				$r=$dosis[4][$i];
				while($t<sizeof($dosis[0]))
				{
					if($r==$dosis[4][$t])
					{
						$s++;
						$t++;
					}
					else
					{
						break;
					}
				}
				if($spy==0)
				{
					$this->salida .='<tr class="hc_submodulo_list_claro">';
				}
				else
				{
					$this->salida .='<tr class="hc_submodulo_list_oscuro">';
				}
				$this->salida .='<td rowspan="'.$s.'">';
				$this->salida .=$dosis[0][$i];
				$this->salida .='</td>';
				$this->salida .='<td>';
				$this->salida .=$dosis[1][$i];
				$this->salida .='</td>';
				$this->salida .='<td align="center">';
				if(empty($dosis[3][$i]))
				{
					if(empty($dosis[6][$i]))
					{
						if(!empty($dosis[2][$i]))
						{
							$this->salida .='El día: '.$dosis[2][$i];
						}
					}
					else
					{
						if(empty($dosis[2][$i]))
						{
							$this->salida .=$dosis[6][$i];
						}
						else
						{
							$this->salida .=$dosis[6][$i].'. El día: '.$dosis[2][$i];
						}
					}
				}
				else
				{
					$this->salida .=$empresa.'. El día: '.$dosis[2][$i];
				}
				$this->salida .='</td>';
				$this->salida .='</tr>';
				$i++;
				$t=$i;
				while($t<sizeof($dosis[0]))
				{
					if($r==$dosis[4][$t])
					{
						if($spy==0)
						{
							$this->salida .='<tr class="hc_submodulo_list_claro">';
						}
						else
						{
							$this->salida .='<tr class="hc_submodulo_list_oscuro">';
						}
						$this->salida .='<td>';
						$this->salida .=$dosis[1][$i];
						$this->salida .='</td>';
						$this->salida .='<td align="center">';
						if(empty($dosis[3][$i]))
						{
							if(empty($dosis[6][$i]))
							{
								if(!empty($dosis[2][$i]))
								{
									$this->salida .='El día: '.$dosis[2][$i];
								}
							}
							else
							{
								if(empty($dosis[2][$i]))
								{
									$this->salida .=$dosis[6][$i];
								}
								else
								{
									$this->salida .=$dosis[6][$i].'. El día: '.$dosis[2][$i];
								}
							}
						}
						else
						{
							$this->salida .=$empresa.'. El día: '.$dosis[2][$i];
						}
						$this->salida .='</td>';
						$this->salida .='</tr>';
						$t++;
						$i++;
					}
					else
					{
						if($spy==0)
						{
							$spy=1;
						}
						else
						{
							$spy=0;
						}
						break;
					}
				}
			}
			$this->salida .='</table>';
		}
		else
		{
			$this->salida .='No existen datos de vacunas de este paciente';
		}
	  $this->salida .= ThemeCerrarTablaSubModulo();
    return true;
	}

	function SetStyle($campo)
	{
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr><td class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("hc_tderror");
		}
		return ("hc_tdlabel");
	}


	function frmForma($accion)
	{
	  $pfj=$this->frmPrefijo;
		if(empty($accion))
		{
			list($dbconn) = GetDBconn();
			$query="select count(*) from hc_vacunas_cumplidas where paciente_id='".$this->paciente."' and tipo_id_paciente='".$this->tipoidpaciente."';";
			$result = $dbconn->Execute($query);
			$dat=0;
			if(!empty($result->fields[0]))
			{$dat=1;}
			$query = "select b.nombre, c.nombre,c.edad, c.tipo_edad,c.vacuna_id,c.dosis_id from ((select b.vacuna_id, b.dosis_id from vacunas as a, dosis as b where a.vacuna_id=b.vacuna_id and b.ley=1 and b.edad is not null and b.tipo_edad is not null order by b.vacuna_id,b.dosis_id)except(select vacuna_id,dosis_id from hc_vacunas_cumplidas)) as a, vacunas as b,dosis as c where a.vacuna_id=b.vacuna_id and a.dosis_id=c.dosis_id order by c.vacuna_id,c.dosis_id;";
			$result = $dbconn->Execute($query);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				while (!$result->EOF)
				{
					$vacun[0][$i]=$result->fields[0];
					$vacun[1][$i]=$result->fields[1];
					$vacun[2][$i]=$result->fields[2];
					$vacun[3][$i]=$result->fields[3];
					$vacun[4][$i]=$result->fields[4];
					$vacun[5][$i]=$result->fields[5];
					$result->MoveNext();
					$i++;
				}
			}
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
			$this->salida ='<form method="post" name="datos" action= "'.$accion.'">';
			if(empty($this->titulo))
			{
				$this->salida = ThemeAbrirTablaSubModulo('VACUNACION');
			}
			else
			{
				$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
			}

			if($i<>0)
			{
				$this->salida .='<table width="630" border="1" align="center" class="hc_table_list">';
				$this->salida .='<tr align="center" class="hc_table_submodulo_list_title">';
				$this->salida .='<td rowspan="2">';
				$this->salida .='Vacuna';
				$this->salida .='</td>';
				$this->salida .='<td rowspan="2">';
				$this->salida .='No. de Dosis';
				$this->salida .='</td>';
				$this->salida .='<td colspan="4">';
				$this->salida .='Aplicación';
				$this->salida .='</td>';
				$this->salida .='</tr>';
				$this->salida .='<tr class="hc_table_submodulo_list_title">';
				$this->salida .='<td width="10%" align="center">';
				$this->salida .='Aquí';
				$this->salida .='</td>';
				$this->salida .='<td width="10%" align="center" colspan="3">';
				$this->salida .='Otro Lugar';
				$this->salida .='</td>';
				$this->salida .='</tr>';
				$i=0;
				$spy=0;
				while($i<sizeof($vacun[0]))
				{
					$t=$i;
					$s=0;
					$r=$vacun[4][$i];
					while($t<sizeof($vacun[0]))
					{
						if($r==$vacun[4][$t])
						{
							$s++;
							$t++;
						}
						else
						{
							break;
						}
					}
					if($spy==0)
					{
						$this->salida .='<tr class="hc_submodulo_list_claro">';
					}
					else
					{
						$this->salida .='<tr class="hc_submodulo_list_oscuro">';
					}
					$this->salida .='<td rowspan="'.$s.'">';
					$this->salida .=$vacun[0][$i];
					$this->salida .='</td>';
					$this->salida .='<td>';
					$this->salida .=$vacun[1][$i];
					$this->salida .='</td>';
					$this->salida .='<td align="center">';
					$this->salida .='<input type="radio" name="dosis'.$vacun[5][$i].$pfj.'" value="1">';
					$this->salida .='</td>';
					$this->salida .='<td align="center">';
					$this->salida .='<input type="radio" name="dosis'.$vacun[5][$i].$pfj.'" value="2">';
					$this->salida .='</td>';
					$this->salida .='<td align="center">';
					$this->salida .='Fecha<input type="text" name="fecha'.$vacun[5][$i].$pfj.'" size="3" class="input-text">';
					$this->salida .='</td>';
					$this->salida .='<td align="center">';
					$this->salida .='Lugar<input type="text" name="lugar'.$vacun[5][$i].$pfj.'" size="3" class="input-text">';
					$this->salida .='</td>';
					$this->salida .='</tr>';
					$i++;
					$t=$i;
					while($t<sizeof($vacun[0]))
					{
						if($r==$vacun[4][$t])
						{
							if($spy==0)
							{
								$this->salida .='<tr class="hc_submodulo_list_claro">';
							}
							else
							{
								$this->salida .='<tr class="hc_submodulo_list_oscuro">';
							}
							$this->salida .='<td>';
							$this->salida .=$vacun[1][$i];
							$this->salida .='</td>';
							$this->salida .='<td align="center">';
							$this->salida .='<input type="radio" name="dosis'.$vacun[5][$i].$pfj.'" value="1">';
							$this->salida .='</td>';
							$this->salida .='<td align="center">';
							$this->salida .='<input type="radio" name="dosis'.$vacun[5][$i].$pfj.'" value="2">';
							$this->salida .='</td>';
							$this->salida .='<td align="center">';
							$this->salida .='Fecha<input type="text" name="fecha'.$vacun[5][$i].$pfj.'" size="3" class="input-text">';
							$this->salida .='</td>';
							$this->salida .='<td align="center">';
							$this->salida .='Lugar<input type="text" name="lugar'.$vacun[5][$i].$pfj.'" size="3" class="input-text">';
							$this->salida .='</td>';
							$this->salida .='</tr>';
							$t++;
							$i++;
						}
						else
						{
							if($spy==0)
							{
								$spy=1;
							}
							else
							{
								$spy=0;
							}
							break;
						}
					}
				}
				$this->salida .='</table>';
				$this->salida.='<table width="100%" border="0"><tr><td align="left">';
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'otros'));
				$this->salida .='<a href='.$accion.'>mas...</a>';
				$this->salida.='</td><td align="center">';
				$this->salida.='<input type="submit" value="Insertar" class="input-submit">';
				$this->salida.='</td>';
				$this->salida.='<td align="right">';
				if($dat==1)
				{
					$this->salida.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				else
				{
					$this->salida.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				$this->salida.='</td>';
				$this->salida.='</tr></table>';
			}
			else
			{
				$this->salida.='<table width="100%" border="0"><tr><td align="left">';
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'otros'));
				$this->salida .='<a href='.$accion.'>mas...</a>';
				$this->salida.='</td><td align="center">';
				$this->salida.='</td>';
				$this->salida.='<td align="right">';
				if($dat==1)
				{
					$this->salida.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				else
				{
					$this->salida.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				$this->salida.='</td>';
				$this->salida.='</tr></table>';
			}
			$this->salida .='</form>';
			$this->salida .= ThemeCerrarTablaSubModulo();
		}
		elseif($accion=="otros")
		{
			list($dbconn) = GetDBconn();
			$query="select count(*) from hc_vacunas_cumplidas where paciente_id='".$this->paciente."' and tipo_id_paciente='".$this->tipoidpaciente."';";
			$result = $dbconn->Execute($query);
			$dat=0;
			if(!empty($result->fields[0]))
			{$dat=1;}
			$query = "select c.nombre, b.nombre, b.edad, b.tipo_edad, b.vacuna_id, b.dosis_id from (((select b.dosis_id from dosis as b)except(select b.dosis_id from vacunas as a, dosis as b where a.vacuna_id=b.vacuna_id and b.ley=1 and b.edad is not null and b.tipo_edad is not null))except(select dosis_id from hc_vacunas_cumplidas)) as a, dosis as b left join hc_vacunas_cumplidas as d on (b.dosis_id=d.dosis_id), vacunas as c where a.dosis_id=b.dosis_id and b.vacuna_id=c.vacuna_id order by b.vacuna_id,b.dosis_id;";
			$result = $dbconn->Execute($query);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				while (!$result->EOF)
				{
					$vacun[0][$i]=$result->fields[0];
					$vacun[1][$i]=$result->fields[1];
					$vacun[2][$i]=$result->fields[2];
					$vacun[3][$i]=$result->fields[3];
					$vacun[4][$i]=$result->fields[4];
					$vacun[5][$i]=$result->fields[5];
					$result->MoveNext();
					$i++;
				}
			}
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar,otros'));
			$this->salida ='<form method="post" name="datos" action= "'.$accion.'">';
			$this->salida .= ThemeAbrirTablaSubModulo('Vacunación');
			if($i<>0)
			{
				$this->salida .='<table width="630" border="1" align="center" class="hc_table_list">';
				$this->salida .='<tr align="center" class="hc_table_submodulo_list_title">';
				$this->salida .='<td rowspan="2">';
				$this->salida .='Vacuna';
				$this->salida .='</td>';
				$this->salida .='<td rowspan="2">';
				$this->salida .='No. de Dosis';
				$this->salida .='</td>';
				$this->salida .='<td colspan="4">';
				$this->salida .='Aplicación';
				$this->salida .='</td>';
				$this->salida .='</tr>';
				$this->salida .='<tr class="hc_table_submodulo_list_title">';
				$this->salida .='<td width="10%" align="center">';
				$this->salida .='Aquí';
				$this->salida .='</td>';
				$this->salida .='<td width="10%" align="center" colspan="3">';
				$this->salida .='Otro Lugar';
				$this->salida .='</td>';
				$this->salida .='</tr>';
				$i=0;
				$spy=0;
				while($i<sizeof($vacun[0]))
				{
					$t=$i;
					$s=0;
					$r=$vacun[4][$i];
					while($t<sizeof($vacun[0]))
					{
						if($r==$vacun[4][$t])
						{
							$s++;
							$t++;
						}
						else
						{
							break;
						}
					}
					if($spy==0)
					{
						$this->salida .='<tr class="hc_submodulo_list_claro">';
					}
					else
					{
						$this->salida .='<tr class="hc_submodulo_list_oscuro">';
					}
					$this->salida .='<td rowspan="'.$s.'">';
					$this->salida .=$vacun[0][$i];
					$this->salida .='</td>';
					$this->salida .='<td>';
					$this->salida .=$vacun[1][$i];
					$this->salida .='</td>';
					$this->salida .='<td align="center">';
					$this->salida .='<input type="radio" name="dosis'.$vacun[5][$i].$pfj.'" value="1">';
					$this->salida .='</td>';
					$this->salida .='<td align="center">';
					$this->salida .='<input type="radio" name="dosis'.$vacun[5][$i].$pfj.'" value="2">';
					$this->salida .='</td>';
					$this->salida .='<td align="center">';
					$this->salida .='Fecha<input type="text" name="fecha'.$vacun[5][$i].$pfj.'" size="3" class="input-text">';
					$this->salida .='</td>';
					$this->salida .='<td align="center">';
					$this->salida .='Lugar<input type="text" name="Lugar'.$vacun[5][$i].$pfj.'" size="3" class="input-text">';
					$this->salida .='</td>';
					$this->salida .='</tr>';
					$i++;
					$t=$i;
					while($t<sizeof($vacun[0]))
					{
						if($r==$vacun[4][$t])
						{
							if($spy==0)
							{
								$this->salida .='<tr class="hc_submodulo_list_claro">';
							}
							else
							{
								$this->salida .='<tr class="hc_submodulo_list_oscuro">';
							}
							$this->salida .='<td>';
							$this->salida .=$vacun[1][$i];
							$this->salida .='</td>';
							$this->salida .='<td align="center">';
							$this->salida .='<input type="radio" name="dosis'.$vacun[5][$i].$pfj.'" value="1">';
							$this->salida .='</td>';
							$this->salida .='<td align="center">';
							$this->salida .='<input type="radio" name="dosis'.$vacun[5][$i].$pfj.'" value="2">';
							$this->salida .='</td>';
							$this->salida .='<td align="center">';
							$this->salida .='Fecha<input type="text" name="fecha'.$vacun[5][$i].$pfj.'" size="3" class="input-text">';
							$this->salida .='</td>';
							$this->salida .='<td align="center">';
							$this->salida .='Lugar<input type="text" name="Lugar'.$vacun[5][$i].$pfj.'" size="3" class="input-text">';
							$this->salida .='</td>';
							$this->salida .='</tr>';
							$t++;
							$i++;
						}
						else
						{
							if($spy==0)
							{
								$spy=1;
							}
							else
							{
								$spy=0;
							}
							break;
						}
					}
				}
				$this->salida .='</table>';
				$this->salida.='<table width="100%" border="0"><tr><td align="left">';
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
				$this->salida .='<a href='.$accion.'>Volver</a>';
				$this->salida.='</td><td align="center">';
				$this->salida.='<input type="submit" value="Insertar" class="input-submit">';
				$this->salida.='</td>';
				$this->salida.='</td>';
				$this->salida.='<td align="right">';
				if($dat==1)
				{
					$this->salida.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				else
				{
					$this->salida.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				$this->salida.='</td>';
				$this->salida.='</tr></table>';
			}
			else
			{
				$this->salida.='<table width="100%" border="0"><tr><td align="left">';
				$this->salida.='<table width="100%" border="0"><tr><td align="left">';
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'otros'));
				$this->salida .='<a href='.$accion.'>mas...</a>';
				$this->salida.='</td><td align="center">';
				$this->salida.='</td>';
				$this->salida.='<td align="right">';
				if($dat==1)
				{
					$this->salida.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				else
				{
					$this->salida.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				$this->salida.='</td>';
				$this->salida.='</tr></table>';
			}
			$this->salida .='</form>';
			$this->salida .= ThemeCerrarTablaSubModulo();
		}
    return true;
	}

}

?>
