<?php

/**
* Submodulo de Remisiones.
*
* Submodulo para manejar las remisiones a otros centros.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Remisiones_HTML.php,v 1.4 2005/06/17 16:46:44 tizziano Exp $
*/

/**
* Remisiones_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo de Remisiones, se extiende la clase Remisiones y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Remisiones_HTML extends Remisiones
{

	function Remisiones_HTML()
	{
	    $this->Remisiones();//constructor del padre
	    return true;
	}



	function SetStyle($campo)
	{
	  $pfj=$this->frmPrefijo;
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr align=\"center\"><td colspan=\"2\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

/*IMPLEMENTACION DE LA BARRA DE NAVEGACION*/

	//cor - jea - ads
	function CalcularNumeroPasos($conteo)
	{
		$pfj=$this->frmPrefijo;
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	//cor - jea - ads
	function CalcularBarra($paso)
	{
		$pfj=$this->frmPrefijo;
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	//cor - jea - ads
	function CalcularOffset($paso)
	{
		$pfj=$this->frmPrefijo;
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	//cor - jea - ads
	function RetornarBarra()
	{
		$pfj=$this->frmPrefijo;
		$motivos=$this->Motivos_Remision();
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
// MODIFICACIONES AQUI FOR

		for($p=0;$p<sizeof($_REQUEST['vect'.$pfj]);$p++)
		{
			if (!empty ($_REQUEST['sno'.$p]) OR !empty($_REQUEST['vector'.$pfj][$p]))
			{
				$vector[$p]=array('sno'.$p=>$_REQUEST['sno'.$p]);
			}
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'otro'.$pfj => $_REQUEST['otro'.$pfj], 'observacion'.$pfj => $_REQUEST['observacion'.$pfj], 'ref'.$pfj => $_REQUEST['ref'.$pfj],
		'niveles'.$pfj => $_REQUEST['niveles'.$pfj], 'ambulancia'.$pfj => $_REQUEST['ambulancia'.$pfj], 'criterio'.$pfj => $_REQUEST['criterio'.$pfj], 'codigo'.$pfj => $_REQUEST['codigo'.$pfj],
		'descripcion'.$pfj => $_REQUEST['descripcion'.$pfj],'vector'.$pfj => $vector,'vect'.$pfj=>$motivos));// MODIFICACIONES AQUI VECTOR
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}
//FIN DE IMPLEMENTACION


      /*
      *		frmForma
      *
      *		Formulario que permite ingresar los signos vitales al paciente seleccioado
      *
      *		@Author Tizziano Perea O.
      *		@access Private
      *		@param array datos del paciente
      *		@param array datos de la estacion
      *		@param integer cantidad
      *		@param string nombre de la funcion que llama a esta funcion
      *		@param array paramentros de la funcionque llama a esta funcion
      *		@return boolean
      */
		function frmForma($arr,$vector,$diag,$inserto)
		{
			$pfj=$this->frmPrefijo;
			$motivos=$this->Motivos_Remision();
			$num_motivo = $this->Get_Numero_Motivos_Remision();
			if(empty($this->titulo))
			{
				$this->salida = ThemeAbrirTabla('DATOS PARA LA REMISION DEL PACIENTE');
			}
			else
			{
				$this->salida  = ThemeAbrirTabla($this->titulo);
			}

			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Insert_Conducta','vect'.$pfj=>$motivos, 'rem'.$pfj => $_REQUEST['centro']));
			$this->salida .= "<form name=\"conducta$pfj\"' action='".$accion."' method='POST'>";

			$this->salida .= $this->SetStyle("MensajeError",11);

			$this->salida.="</table>";
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="<td align=\"center\" colspan=\"4\">MOTIVO DE REFERENCIA</td>";
			$this->salida.="</tr>";
			$p=0;
			$m=4;

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$nure=sizeof($motivos);
			$cols=$nure%4;
			// MODIFICACIONES AQUI
			if (empty ($_REQUEST['vector'.$pfj]))
			{
				foreach ($motivos as $k=> $v)
				{
					if($p == $m)
					{
						$m=$m+4;
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						if ($_REQUEST['sno'.$p]!=NULL )
						{
							$this->salida.="<td align=\"left\"><input type = \"checkbox\" name=\"sno$p\" value =\"$v[motivo_remision_id]\" checked>&nbsp;&nbsp;$v[descripcion]</td>";
						}
						else
						{
							$this->salida.="<td align=\"left\"><input type = \"checkbox\" name=\"sno$p\" value =\"$v[motivo_remision_id]\">&nbsp;&nbsp;$v[descripcion]</td>";
						}

					}
					else
					{
						if ($_REQUEST['sno'.$p]!=NULL)
						{
							$this->salida.="<td align=\"left\"><input type = \"checkbox\" name=\"sno$p\" value =\"$v[motivo_remision_id]\" checked>&nbsp;&nbsp;$v[descripcion]</td>";
						}
						else
						{
							$this->salida.="<td align=\"left\"><input type = \"checkbox\" name=\"sno$p\" value =\"$v[motivo_remision_id]\">&nbsp;&nbsp;$v[descripcion]</td>";
						}
					}
					$p++;
				}

				if ($p-1)
				{
					$this->salida.="<td align=\"left\">Otro, Cual:&nbsp;<input type = \"text\" class=\"input-text\" name=\"otro$pfj\" size = 15 maxlength = 256 value =\"".$_REQUEST['otro'.$pfj]."\"></td>";
				}
			// MODIFICACIONES AQUI FOR
			}
			else
			// MODIFICACIONES AQUI FOR
			{
				$sno = $_REQUEST['vector'.$pfj];
				$p=0;
				$j = 0;
				foreach($sno as $x=>$snovector)
				{
					foreach($snovector as $marca=>$valor)
					{
						$indice[] = $marca;
					}
				}
						foreach($motivos as $k=> $v)
						{
							if($p == $m)
							{
								$m=$m+4;
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
								if ($indice[$j] == 'sno'.$p)
								{
									$this->salida.="<td align=\"left\"><input type = \"checkbox\" name=\"sno$p\" value =\"$v[motivo_remision_id]\" checked>&nbsp;&nbsp;$v[descripcion]</td>";
									$j ++;
								}
								else
								{
									$this->salida.="<td align=\"left\"><input type = \"checkbox\" name=\"sno$p\" value =\"$v[motivo_remision_id]\">&nbsp;&nbsp;$v[descripcion]</td>";
								}
							}
							else
							{
								if ($indice[$j] == 'sno'.$p)
								{
									$this->salida.="<td align=\"left\"><input type = \"checkbox\" name=\"sno$p\" value =\"$v[motivo_remision_id]\" checked>&nbsp;&nbsp;$v[descripcion]</td>";
									$j ++;
								}
								else
								{
									$this->salida.="<td align=\"left\"><input type = \"checkbox\" name=\"sno$p\" value =\"$v[motivo_remision_id]\">&nbsp;&nbsp;$v[descripcion]</td>";
								}
							}
							$p++;
						}

				if ($p-1)
				{
					$this->salida.="<td align=\"left\">Otro, Cual:&nbsp;<input type = \"text\" class=\"input-text\" name=\"otro$pfj\" size = 15 maxlength = 256 value =\"".$_REQUEST['otro'.$pfj]."\"></td>";
				}
			}

			$this->salida.="</tr>";

			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="<td align=\"center\" colspan=\"4\">OBSERVACIONES</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" colspan=\"4\"><br><textarea name=\"observacion$pfj\" cols=\"80\" rows=\"7\" style = \"width:90%\" class=\"textarea\">".$_REQUEST['observacion'.$pfj]."</textarea><br><br></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="<td align=\"center\" colspan=\"3\">TIPO DE REMISION</td>";
			$this->salida.="<td align=\"center\">NIVEL DE LA INSTITUCION</td>";
			$this->salida.="<td align=\"center\">TRASLADO EN AMBULANCIA:</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

			$this->salida.="<td align=\"center\" colspan=\"3\">";
			$this->salida.="<select  name =\"ref$pfj\"  class =\"select\">";
			$ref=$this->Get_Referencia();
			for($i=0; $i<sizeof($ref); $i++)
			{
				if ($ref[$i][tipo_remision_id] == $_REQUEST['ref'.$pfj])
				{
					$this->salida .=" <option value=\"".$ref[$i][tipo_remision_id]."\" selected>".$ref[$i][descripcion]."</option>";
				}
				else
				{
					$this->salida .=" <option value=\"".$ref[$i][tipo_remision_id]."\">".$ref[$i][descripcion]."</option>";
				}
			}
			$this->salida.="</select>";
			$this->salida.="</td>";

			$this->salida.="<td align=\"center\">";
			$this->salida.="<select  name =\"niveles$pfj\"  class =\"select\">";
			$this->salida .= "<option value =\"-1\">NO APLICA</option>";
			$niveles=$this->Get_Niveles();
			for($i=0; $i<sizeof($niveles); $i++)
			{
				if ($niveles[$i][nivel] == $_REQUEST['niveles'.$pfj])
				{
					$this->salida .=" <option value=\"".$niveles[$i][nivel]."\" selected>".$niveles[$i][descripcion]."</option>";
				}
				else
				{
					$this->salida .=" <option value=\"".$niveles[$i][nivel]."\">".$niveles[$i][descripcion]."</option>";
				}
			}
			$this->salida.="</select>";
			$this->salida.="</td>";
			if ($_REQUEST['ambulancia'.$pfj] == 1)
			{
				$this->salida.="<td align=\"center\"><input type = \"checkbox\" name=\"ambulancia$pfj\" value =\"1\" checked></td>";
			}
			else
			{
				$this->salida.="<td align=\"center\"><input type = \"checkbox\" name=\"ambulancia$pfj\" value =\"1\"></td>";
			}
			$this->salida.="</tr>";
			$this->salida.="</table>";

			$this->salida .= "<table  align=\"center\" border=\"0\"  width=\"70%\">";
			$this->salida .= "<tr>";
			$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"4\">INSTITUCIONES A REMITIR</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr>";
			$this->salida .= "<td align=\"center\" colspan=\"4\" class=\"modulo_list_oscuro\">";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"7\">CENTROS DE REMISION</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"5%\">TIPO</td>";
			$this->salida.="<td width=\"10%\" align = left >";
			$this->salida.="<select  name =\"criterio$pfj\"  class =\"select\">";
			$this->salida.="<option value=\"Todas\">TODOS LOS NIVELES</option>";
			$nivel=$this->Niveles();
			for($i=0; $i<sizeof($nivel); $i++)
			{
				if ($nivel[$i][nivel] == $_REQUEST['criterio'.$pfj])
				{
					$this->salida .=" <option value=\"".$nivel[$i][nivel]."\" selected>INSTITUCION ".$nivel[$i][descripcion]."</option>";
				}
				else
				{
					$this->salida .=" <option value=\"".$nivel[$i][nivel]."\">INSTITUCION ".$nivel[$i][descripcion]."</option>";
				}
			}
			$this->salida.="</select>";
			$this->salida.="</td>";
			$this->salida.="<td width=\"6%\">CODIGO:</td>";
			$this->salida.="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10 name =\"codigo$pfj\" value=\"".$_REQUEST['codigo'.$pfj]."\"></td>" ;
			$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
			$this->salida.="<td width=\"25%\" align='center'><input type='text' class='input-text' name = \"descripcion$pfj\" value=\"".$_REQUEST['descripcion'.$pfj]."\"></td>";
			$this->salida.="<td width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"Buscar$pfj\" type=\"submit\" value=\"BUSQUEDA\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			
			if(!empty($vector))
			{
				$this->FormaResultados($vector);
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";

			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\" colspan=\"7\"><br><input class=\"input-submit\" name=\"guardar$pfj\"  type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</form>";
			$this->salida .= themeCerrarTabla();
			return true;
		}


    /**
	*
	*/
	function FormaResultados($arr)
	{
		if ($arr)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"30%\">INSTITUCION</td>";
			$this->salida.="  <td width=\"10%\">NIVEL</td>";
			$this->salida.="  <td width=\"5%\"></td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($arr);$i++)
			{
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="  <td>".$arr[$i][descripcion]."</td>";
				$this->salida.="  <td align=\"center\">".$arr[$i][nivel]."</td>";
					if ($arr[$i][centro_remision] == $_REQUEST['centro'])
					{
						$this->salida.="  <td align=\"center\"><input type = radio name='centro'".$arr[$i][centro_remision]." value =\"".$arr[$i][centro_remision]."\" checked></td>";//".$arr[$i][centro_remision]."//,".$arr[$i][descripcion]."
					}
					else
					{
						$this->salida.="<td align=\"center\"><input type = radio name='centro'".$arr[$i][centro_remision]." value =\"".$arr[$i][centro_remision]."\"></td>";//,".$arr[$i][descripcion]."
					}
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
			$this->salida .=$this->RetornarBarra();
		}
	}



	function frmFormaConfirmacion()
	{
		$pfj = $this->frmPrefijo;
		$hospital = $this->BuscarCentro();
		$num_motivo = $this->GetMotivos_Remision();
		$conducta = $this->GetConduta_Remision();

		$this->salida= ThemeAbrirTablaSubModulo('REPORTE DE REMISIONES');

		$this->salida .="<br><table width=\"100%\" align=\"center\">";
		$this->salida .="<tr class=\"modulo_table_title\">";
		$this->salida .="<td align=\"center\">CERTIFICADO DE REMISION";
		$this->salida .="</td>";
		$this->salida .="</tr>";
		$this->salida .="</table>";

		$this->salida .="<table width=\"100%\" align=\"center\">";
		$this->salida .="<tr class=\"modulo_table_title\">";
		$this->salida .="<td align=\"center\" colspan=\"2\">MOTIVO DE REMISION";
		$this->salida .="</td>";
		$this->salida .="</tr>";
		foreach ($num_motivo as $k => $v)
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
			$this->salida .="<td align=\"center\" colspan=\"2\">$v[0]";
			$this->salida .="</td>";
			$this->salida .="</tr>";
		}
		if (!empty($v[1]))
		{
			$this->salida .="<tr>";
			$this->salida .="<td class=\"hc_table_submodulo_list_title\" width=\"50%\">OTRO MOTIVO DE REMISION";
			$this->salida .="<td class=\"hc_submodulo_list_claro\" width=\"50%\">$v[1]";
			$this->salida .="</td>";
			$this->salida .="</tr>";
		}

		$this->salida .="</table><br>";

		$this->salida .="<table width=\"100%\" align=\"center\">";
		$this->salida .="<tr class=\"modulo_table_title\">";
		$this->salida .="<td align=\"center\" colspan=\"3\">CONDUCTA DE REMISION";
		$this->salida .="</td>";
		$this->salida .="</tr>";
		$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .="<td align=\"center\" colspan=\"3\">OBSERVACIONES";
		$this->salida .="</td>";
		$this->salida .="</tr>";
		foreach ($conducta as $k2 => $v2)
		{

			$this->salida .="<tr class=\"hc_submodulo_list_claro\" >";
			$this->salida .="<td align=\"left\" colspan=\"3\">$v2[0]";
			$this->salida .="</td>";
			$this->salida .="</tr>";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td align=\"center\" colspan=\"2\" width=\"50%\">TIPO DE REMISION";
			$this->salida .="</td>";
			$this->salida .="<td align=\"center\" width=\"50%\">TRASLADO";
			$this->salida .="</td>";
			$this->salida .="</tr>";
			$this->salida .="<tr>";
			$this->salida .="<td align=\"center\" colspan=\"2\" width=\"50%\" class=\"hc_submodulo_list_claro\">$v2[1]";
			$this->salida .="</td>";
			if ($v2[2] == '1')
			{
				$traslado = "TRASLADADO EN AMBULANCIA";
				$this->salida .="<td class=\"hc_submodulo_list_claro\" width=\"50%\" align=\"center\">$traslado";
				$this->salida .="</td>";
			}
			else
			{
				$traslado = "NO TRASLADADO EN AMBULANCIA";
				$this->salida .="<td class=\"hc_submodulo_list_claro\" width=\"50%\" align=\"center\">$traslado";
				$this->salida .="</td>";
			}
			$this->salida .="</tr>";
		}
		$this->salida .="</table><br>";

		$this->salida .="<table width=\"100%\" align=\"center\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">INFORMACION DEL PROFESIONAL TRATANTE</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">REMITIDO POR:</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align=\"center\" width=\"50%\">NOMBRE DEL PROFESIONAL:</td>";
		$this->salida.="<td align=\"center\" width=\"50%\" class=\"modulo_list_oscuro\">$v2[3]</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td colspan=\"2\" align=\"center\">$v2[4]</td>";
		$this->salida.="</tr>";
		$this->salida .="</table><br>";

		if (empty($hospital[descripcion]))
		{
			$this->salida .="<center>";
			$this->salida .="<label class=titulo3>El Paciente Fue Remitido a Otro Departamento o Institucion</label>";
			$this->salida .="</center>";
		}
		else
		{
			$this->salida .="<table width=\"100%\" align=\"center\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"2\">INFORMACION DEL DEPARTAMENTO O CENTRO DE REMISION</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" colspan=\"2\">REMITIDO A:</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"50%\">NOMBRE DEL CENTRO O DEPARTAMENTO:</td>";
			$this->salida.="<td align=\"center\" width=\"50%\">NIVEL:</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.="<td align=\"center\" width=\"50%\">$hospital[descripcion]</td>";
			$this->salida.="<td align=\"center\" width=\"50%\">NIVEL DE LA INSTITUCION:&nbsp;&nbsp;".$hospital[nivel]."</td>";
			$this->salida.="</tr>";
			$this->salida .="</table><br><br>";
		}

		$this->salida.="<center>";
		$this->salida.="<label class=\"label_mark\">EL CERTIFICADO DE REMISION FUE EXPEDIDO SATISFACTORIAMENTE.</label>";
		$this->salida.="</center>";

		$reporte= new GetReports();
		$mostrar=$reporte->GetJavaReport('system','reportes','certificado_remision_html',array('ingreso'=>$this->ingreso, 'evolucion'=>$this->evolucion, 'codcentro'=>$hospital),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
		$nombre_funcion=$reporte->GetJavaFunction();
		$this->salida .=$mostrar;
		echo $mostrar;

		$this->salida.="<center>";
		$this->salida.="<label class=\"label_mark\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;&nbsp;IMPRIMIR PDF</a>";
		$this->salida.="</center>";

		unset ($_SESSION['INSERTO']);
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


	function frmConsulta()
	{
		return true;
	}//frmConsulta
}
?>
