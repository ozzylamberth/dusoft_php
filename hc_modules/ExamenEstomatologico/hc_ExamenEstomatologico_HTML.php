<?php

/**
* Submodulo de ExamenEstomatologico (HTML).
*
* Submodulo para manejar el examen por sistemas que debe realizarse a un paciente en una evolucin.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_ExamenEstomatologico_HTML.php,v 1.15 2006/12/19 21:00:13 jgomez Exp $
*/

/**
* ExamenEstomatologico
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo revision por sistemas, se extiende la clase ExamenEstomatologico y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class ExamenEstomatologico_HTML extends ExamenEstomatologico
{

/**
* Color de fondo especial para el manejo de antecedentes
*
* @var text
* @access private
*/
	var $backcolor;

/**
* Color especial de la letra para el manejo de antecedentes
*
* @var text
* @access private
*/
	var $backcolorf;

	function ExamenEstomatologico_HTML()
	{
		$this->ExamenEstomatologico();//constructor del padre
		$this->backcolor="red";
		$this->backcolorf="#990000";
		return true;
	}

  /**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

  function GetVersion()
  {
    $informacion=array(
    'version'=>'1',
    'subversion'=>'0',
    'revision'=>'0',
    'fecha'=>'01/27/2005',
    'autor'=>'TIZZIANO PEREA OCORO',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }

///////////////////

	function frmConsulta()
	{
		$consultas=$this->DatosConsultaRevision();
		$maestrico = $this->GetEstomatologico_MaestroForaneo();
		$hallazgos = $this->DatosConsultaRevisionHallazgo();
		if(!empty($consultas))
		{
			$this->salida.="<br>";
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\" colspan=\"2\" class=\"modulo_table_list_title\">CONSOLIDADO EXAMEN ESTOMATOLOGICO</td>";
			$this->salida.="</tr>";
			foreach ($maestrico as $k => $v)
			{
				for ($j=1; $j<=6; $j++)
				{
					if (!empty($v[$j]))
					{
						if($spy==0)
						{
							$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
							$spy=1;
						}
						else
						{
							$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
							$spy=0;
						}

						$this->salida .="<td width=\"20%\" align='left'><b>$v[$j]</b></td>";
						$this->salida .="<td><table border=\"0\" width=\"100%\">";

						for ($i=0; $i<sizeof($consultas); $i++)
						{
							if ($j == $consultas[$i]['estomatologico_maestro_id'])
							{
								$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
								$this->salida.="<td width=\"15%\"><b>".$consultas[$i][nombre]."</b></td>";
								$this->salida.="<td width=\"8%\" align=\"right\" class=\"hc_table_submodulo_list_title\">";

								if($consultas[$i][sw_defecto]=='0')
								{
									if($consultas[$i][normal]=='1')
									{
										$this->salida.="<label class=\"label_error\">Si</label><br>";
									}
									else
									{
										$this->salida.="<label class=\"label\">No</label>";
									}
								}
								else
								{
									if($consultas[$i][normal]=='0')
									{
										$this->salida.="<label class=\"label_error\">Anormal</label>";
									}
									else
									{
										$this->salida.="<label class=\"label\">Normal</label><br>";
									}
								}
								$this->salida.="</td>";
								$this->salida.='<td align="justify" width="35%">'.$consultas[$i][observacion].'</td>';
                                        list($fecha,$hora) = explode(" ",$this->PartirFecha($consultas[$i][fecha_registro]));
                                        list($ano,$mes,$dia) = explode("-",$fecha);
                                        list($hora,$min) = explode(":",$hora);
                                        $this->salida.="<td align=\"center\" width=\"12%\"><font size=\"1\" color=\"$this->backcolorf\">".$fecha."</font></td>";
		

								$this->salida.="</tr>";
							}
						}
						$this->salida.='</table>';
						$this->salida.='</td>';
					}
					$this->salida.="</tr>";
				}
			}

			if (!empty($hallazgos[descripcion_hallazgo]))
			{
				$this->salida.="<tr>";
				$this->salida.="<td colspan=\"2\">";
				$this->salida.="<table width=\"100%\" align=\"center\" class=\"modulo_table_list\"><tr>";
				$this->salida.="<td align=\"center\" class=\"modulo_table_list_title\">DESCRIPCION DE HALLAZGOS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td class=\"hc_submodulo_list_claro\">".$hallazgos[descripcion_hallazgo];
				$this->salida.="</td></tr></table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
		}
		else
		{
			return false;
		}
		return true;
	}


	function frmHistoria()
	{
		$consultas=$this->DatosConsultaRevision();
		$maestrico = $this->GetEstomatologico_MaestroForaneo();
		$hallazgos = $this->DatosConsultaRevisionHallazgo();
		if(!empty($consultas))
		{
			$salida.="<br>";
			$salida.="<table align=\"center\" border=\"1\" width=\"100%\">";
			$salida.="<tr>";
			$salida.="<td align=\"center\" colspan=\"2\">EXAMEN ESTOMATOLOGICO</td>";
			$salida.="</tr>";
			foreach ($maestrico as $k => $v)
			{
				for ($j=1; $j<=6; $j++)
				{
					if (!empty($v[$j]))
					{
						if($spy==0)
						{
							$salida.="<tr>";
							$spy=1;
						}
						else
						{
							$salida.="<tr>";
							$spy=0;
						}

						$salida .="<td width=\"20%\" align=\"center\"><b>$v[$j]</b></td>";
						$salida .="<td width=\"80%\" align=\"center\"><table border=\"1\" width=\"100%\">";

						for ($i=0; $i<sizeof($consultas); $i++)
						{
							if ($j == $consultas[$i]['estomatologico_maestro_id'])
							{
								$salida.="<tr>";
								$salida.="<td width=\"25%\"><b>".$consultas[$i][nombre]."</b></td>";
								$salida.="<td align=\"center\" width=\"7%\">";

								if($consultas[$i][sw_defecto]=='0')
								{
									if($consultas[$i][normal]=='1')
									{
										$salida.="<label class=\"label_error\">Si</label><br>";
									}
									else
									{
										$salida.="<label class=\"label\">No</label>";
									}
								}
								else
								{
									if($consultas[$i][normal]=='0')
									{
										$salida.="<label class=\"label_error\">Anormal</label>";
									}
									else
									{
										$salida.="<label class=\"label\">Normal</label><br>";
									}
								}
								$salida.="</td>";
								$salida.="<td align=\"justify\" width=\"53%\">".$consultas[$i][observacion]."</td>";
								list($fecha,$hora) = explode(" ",$this->PartirFecha($consultas[$i][fecha_registro]));
                                        list($ano,$mes,$dia) = explode("-",$fecha);
                                        list($hora,$min) = explode(":",$hora);
                                        $salida.="<td align=\"center\" width=\"15%\"><font size=\"1\" color=\"$this->backcolorf\">".$fecha."</font></td>";
								$salida.="</tr>";
							}
						}
						$salida.='</table>';
						$salida.='</td>';
					}
					$salida.="</tr>";
				}
			}

			if (!empty($hallazgos[descripcion_hallazgo]))
			{
				$salida.="<tr>";
				$salida.="<td colspan=\"2\">";
				$salida.="<table width=\"100%\" align=\"center\" class=\"modulo_table_list\"><tr>";
				$salida.="<td align=\"center\" class=\"modulo_table_list_title\">DESCRIPCION DE HALLAZGOS</td>";
				$salida.="</tr>";
				$salida.="<tr>";
				$salida.="<td class=\"hc_submodulo_list_claro\">".$hallazgos[descripcion_hallazgo];
				$salida.="</td></tr></table>";
				$salida.="</td>";
				$salida.="</tr>";
			}
			$salida.="</table><br>";
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
			return ("labe_error");
		}
		return ("label");
	}


	function frmReporte()
	{
		$consultas=$this->DatosConsultaRevision();
		$maestrico = $this->GetEstomatologico_MaestroForaneo();
		$hallazgos = $this->DatosConsultaRevisionHallazgo();
		if(!empty($consultas))
		{
			$this->salida.="<table align=\"center\" border=\"0\" width=\"88%\" class=\"modulo_table_list\">";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\" colspan=\"2\" class=\"modulo_table_list_title\">CONSOLIDADO EXAMEN ESTOMATOLOGICO</td>";
			$this->salida.="</tr>";
			foreach ($maestrico as $k => $v)
			{
				for ($j=1; $j<=6; $j++)
				{
					if (!empty($v[$j]))
					{
						if($spy==0)
						{
							$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
							$spy=1;
						}
						else
						{
							$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
							$spy=0;
						}

						$this->salida .="<td width=\"20%\" align='left'><b>$v[$j]</b></td>";
						$this->salida .="<td><table border=\"0\" width=\"100%\">";

						for ($i=0; $i<sizeof($consultas); $i++)
						{
							if ($j == $consultas[$i]['estomatologico_maestro_id'])
							{
								$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
								$this->salida.="<td width=\"15%\"><b>".$consultas[$i][nombre]."</b></td>";
								$this->salida.="<td width=\"8%\" align=\"right\" class=\"hc_table_submodulo_list_title\">";

								if($consultas[$i][sw_defecto]=='0')
								{
									if($consultas[$i][normal]=='1')
									{
										$this->salida.="<label class=\"label_error\">Si</label><br>";
									}
									else
									{
										$this->salida.="<label class=\"label\">No</label>";
									}
								}
								else
								{
									if($consultas[$i][normal]=='0')
									{
										$this->salida.="<label class=\"label_error\">Anormal</label>";
									}
									else
									{
										$this->salida.="<label class=\"label\">Normal</label><br>";
									}
								}
								$this->salida.="</td>";
								$this->salida.='<td align="justify" width="35%">'.$consultas[$i][observacion].'</td>';
                                        list($fecha,$hora) = explode(" ",$this->PartirFecha($consultas[$i][fecha_registro]));
                                        list($ano,$mes,$dia) = explode("-",$fecha);
                                        list($hora,$min) = explode(":",$hora);
                                        $this->salida.="<td align=\"center\" width=\"12%\"><font size=\"1\" color=\"$this->backcolorf\">".$fecha."</font></td>";
								$this->salida.="</tr>";
							}
						}
						$this->salida.='</table>';
						$this->salida.='</td>';
					}
					$this->salida.="</tr>";
				}
			}

			if (!empty($hallazgos[descripcion_hallazgo]))
			{
				$this->salida.="<tr>";
				$this->salida.="<td colspan=\"2\">";
				$this->salida.="<table width=\"100%\" align=\"center\" class=\"modulo_table_list\"><tr>";
				$this->salida.="<td align=\"center\" class=\"modulo_table_list_title\">DESCRIPCION DE HALLAZGOS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td class=\"hc_submodulo_list_claro\">".$hallazgos[descripcion_hallazgo];
				$this->salida.="</td></tr></table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
		}
		else
		{
			return false;
		}
		return true;
	}

     function frm_ExamenEstomatologico_PrimeraVez()
     {
     	$pfj=$this->frmPrefijo;
		$Primera_consulta=$this->Get_ExamenEstomatologico_PrimeraVez();
		$maestrico = $this->GetEstomatologico_PrimeraVez();
		$hallazgos = $this->Get_RevisionHallazgo_PrimeraVez();

          $this->salida  = ThemeAbrirTablaSubModulo('RESUMEN EXAMEN ESTOMATOLOGICO DE PRIMERA VEZ');
          if(!empty($Primera_consulta))
		{
			$this->salida.="<table align=\"center\" border=\"0\" width=\"88%\" class=\"modulo_table_list\">";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\" colspan=\"2\" class=\"modulo_table_list_title\">CONSOLIDADO EXAMEN ESTOMATOLOGICO DE PRIMERA VEZ</td>";
			$this->salida.="</tr>";
			foreach ($maestrico as $k => $v)
			{
				for ($j=1; $j<=6; $j++)
				{
					if (!empty($v[$j]))
					{
						if($spy==0)
						{
							$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
							$spy=1;
						}
						else
						{
							$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
							$spy=0;
						}

						$this->salida .="<td width=\"20%\" align='left'><b>$v[$j]</b></td>";
						$this->salida .="<td><table border=\"0\" width=\"100%\">";

						for ($i=0; $i<sizeof($Primera_consulta); $i++)
						{
							if ($j == $Primera_consulta[$i]['estomatologico_maestro_id'])
							{
								$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
								$this->salida.="<td width=\"15%\"><b>".$Primera_consulta[$i][nombre]."</b></td>";
								$this->salida.="<td width=\"8%\" align=\"right\" class=\"hc_table_submodulo_list_title\">";

								if($Primera_consulta[$i][sw_defecto]=='0')
								{
									if($Primera_consulta[$i][normal]=='1')
									{
										$this->salida.="<label class=\"label_error\">Si</label><br>";
									}
									else
									{
										$this->salida.="<label class=\"label\">No</label>";
									}
								}
								else
								{
									if($Primera_consulta[$i][normal]=='0')
									{
										$this->salida.="<label class=\"label_error\">Anormal</label>";
									}
									else
									{
										$this->salida.="<label class=\"label\">Normal</label><br>";
									}
								}
								$this->salida.="</td>";
								$this->salida.='<td align="justify" width="35%">'.$Primera_consulta[$i][observacion].'</td>';
                                        list($fecha,$hora) = explode(" ",$this->PartirFecha($Primera_consulta[$i][fecha_registro]));
                                        list($ano,$mes,$dia) = explode("-",$fecha);
                                        list($hora,$min) = explode(":",$hora);
                                        $this->salida.="<td align=\"center\" width=\"12%\"><font size=\"1\" color=\"$this->backcolorf\">".$fecha."</font></td>";
								$this->salida.="</tr>";
							}
						}
						$this->salida.='</table>';
						$this->salida.='</td>';
					}
					$this->salida.="</tr>";
				}
			}

			if (!empty($hallazgos[descripcion_hallazgo]))
			{
				$this->salida.="<tr>";
				$this->salida.="<td colspan=\"2\">";
				$this->salida.="<table width=\"100%\" align=\"center\" class=\"modulo_table_list\"><tr>";
				$this->salida.="<td align=\"center\" class=\"modulo_table_list_title\">DESCRIPCION DE HALLAZGOS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td class=\"hc_submodulo_list_claro\">".$hallazgos[descripcion_hallazgo];
				$this->salida.="</td></tr></table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
		}
		else
		{
			$this->salida.="<div align=\"center\" class='label_mark'>NO SE HA REGISTRADO EXAMENES ESTOMATOLOGICOS EN LA PRIMERA CITA ODONTOLOGICA.</div>";
		}
        
		//BOTON DEVOLVER
		$this->salida.="<table width=\"87%\" align=\"center\"><tr><td align=\"center\">";
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida.= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida.= "<tr><td colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
          $this->salida.="</table>";
		$this->salida.= ThemeCerrarTablaSubModulo();
          return true;
     }

	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		$maestro = $this->GetEstomatologico_Maestro();
		$datos = $this->GetTipos_Sistemas();
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('EXAMEN ESTOMATOLOGICO');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar', 'vect'.$pfj =>$ap));
		$this->salida.='<form name="forma_estomatologico'.$pfj.'" action="'.$accion.'" method="post">';
		if($this->SetStyle("MensajeError"))
		{
			$this->salida.="<table align=\"center\">";
			$this->salida.=$this->SetStyle("MensajeError");
			$this->salida.="</table>";
		}
		$this->salida.="<table  align=\"center\" border=\"1\"  width=\"87%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td colspan=\"2\" align=\"right\">OBSERVACIONES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		foreach ($maestro as $k => $v)
		{
			for ($j=1; $j<=sizeof($v); $j++)
			{
				if($spy==0)
				{
					$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
					$spy=0;
				}

				$this->salida .="<td width=\"20%\" align='center'><b>$v[$j]</b></td>";
				$this->salida .="<td><table border=\"0\" width=\"100%\" class=\"modulo_table_list\">";

				for ($i=0; $i<sizeof($datos); $i++)
				{
					$ap = $datos[$i]['tipo_sistema_id'];

					if ($j == $datos[$i]['estomatologico_maestro_id'])
					{
						$this->salida.="<tr class=\"hc_submodulo_list_claro\">";

						$this->salida.="<td width=\"25%\"><b>".$datos[$i][nombre]."</b></td>";
						$this->salida.="<td width=\"2%\" align=\"right\" class=\"hc_table_submodulo_list_title\">";

						if($datos[$i]['sw_defecto']=='0')
						{
							$this->salida.="<label class=\"label\">Si</label><br>";
							$this->salida.="<label class=\"label\">No</label>";
						}
						else
						{
							$this->salida.='<label class="label">Normal</label><br>';
							$this->salida.='<label class="label">Anormal</label>';
						}
						$this->salida.="</td>";
						$this->salida.="<td width=\"1%\" class=\"hc_table_submodulo_list_title\">";

						if($_REQUEST['anormal'.$ap.$pfj] == '1')
						{
							$this->salida.='<input type="radio" name="anormal'.$ap.$pfj.'" value="1" checked="true"><br>';
						}
						else
						{
							$this->salida.='<input type="radio" name="anormal'.$ap.$pfj.'" value="1" ><br>';
						}
						if($_REQUEST['anormal'.$ap.$pfj] == '0')
						{
							$this->salida.='<input type="radio" name="anormal'.$ap.$pfj.'" value="0" checked="true"';
						}
						else
						{
							$this->salida.='<input type="radio" name="anormal'.$ap.$pfj.'" value="0"';
						}
						$this->salida.='</td>';
						$this->salida.='<td align="center" width="40%"><input type="text" class="input-text" name="observacion'.$ap.$pfj.'" size="55" maxlength="256" value="'.$_REQUEST['observacion'.$ap.$pfj].'"></td>';

						$this->salida.="</tr>";
					}
				}
				$this->salida.='</table>';
				$this->salida.='</td>';
				$this->salida.="</tr>";
			}
		}
		$this->salida.="</table><br>";

		$this->salida.="<table width=\"87%\" align=\"center\" border=\"1\"><tr>";
		$this->salida.="<td class=\"hc_table_submodulo_list_title\">OTROS HALLAZGOS<textarea style=\"width:100%\" name=\"hallazgo$pfj\" cols=\"48\" rows=\"5\">".$_REQUEST['hallazgo'.$pfj]."</textarea>";
		$this->salida.="</td></tr></table><br>";

		$this->salida.="<table width=\"87%\" align=\"center\"><tr><td align=\"center\">";
		$this->salida.="<input type=\"submit\" name=\"guardar\" value=\"INSERTAR\" class=\"input-submit\">";
		$this->salida.="</td></tr>";
          if(($this->hc_modulo == 'OdontologiaTratamiento') || ($this->hc_modulo == 'OdontologiaGeneral'))
          {
          	$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'primera_vez'));
               $this->salida.="<tr><td align=\"right\" class=\"label\"><a href='$accion'><img src=\"".GetThemePath()."/images/pinactivo.png\" border='0'>&nbsp;EXAMEN ESTOMATOLOGICO DE PRIMERA CITA</a>";
               $this->salida.="</td></tr>";
          }
          $this->salida.="</table>";
		$this->salida.="</form>";
		$this->frmReporte();
		$this->salida .= ThemeCerrarTablaSubModulo();
          return true;
	}

}

?>
