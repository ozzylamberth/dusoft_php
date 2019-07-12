<?php

/**
* Submodulo de Certificado Defuncion.
*
* Submodulo para manejar los certificados de defunciones.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Certificado_Defuncion_HTML.php,v 1.4 2006/12/19 21:00:13 jgomez Exp $
*/


/**
* Certificado_Defuncion_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo de Certificado_Defuncion_HTML, se extiende la clase Certificado_Defuncion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Certificado_Defuncion_HTML extends Certificado_Defuncion
{

	function Certificado_Defuncion_HTML()
	{
	    $this->Certificado_Defuncion();//constructor del padre
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

/////////////////////////

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

/*-----------------------------------------------------------------------------------
							IMPRESION DE DIAGNOSTICOS DE MUERTE
-----------------------------------------------------------------------------------*/

	function frmFormaDiagnosticos($vectorD)
	{
		$pfj=$this->frmPrefijo;

		$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"        ></td>" ;
		$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		if ($vectorD)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"10%\">CODIGO</td>";
			$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++)
			{
				$codigo          = $vectorD[$i][diagnostico_id];
				$diagnostico    = $vectorD[$i][diagnostico_nombre];
				if( $i % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
				$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".$pfj."[$i]' value = '".$codigo.",".$diagnostico."'></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";

			$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardardiagnostico$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraDiagnosticos_Avanzada();
			if(!empty($var))
			{
				$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
				$this->salida .= "  <tr>";
				$this->salida .= "  <td width=\"100%\" align=\"center\">";
				$this->salida .=$var;
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table><br>";
			}
		}
		return true;
	}

	//cor - jea - ads - *
	function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],'selectHora'.$pfj=>$_REQUEST['selectHora'.$pfj], 'selectMinutos'.$pfj=>$_REQUEST['selectMinutos'.$pfj],
		'motivo'.$pfj=>$_REQUEST['motivo'.$pfj],'certificado'.$pfj=>$_REQUEST['certificado'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],'semanas'.$pfj=>$_REQUEST['semanas'.$pfj],'meses'.$pfj=>$_REQUEST['meses'.$pfj],
		'fechadef'.$pfj=>$_REQUEST['fechadef'.$pfj]));

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
		function frmForma($vectorD)
		{
			$pfj=$this->frmPrefijo;
			$motivos=$this->Motivos_Defuncion();
			$tipos = $this->Tipos_Certificados();
			if(empty($this->titulo))
			{
				$this->salida  = ThemeAbrirTablaSubModulo('CERTIFICADO DE DEFUNCION');
			}
			else
			{
				$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
			}

			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Insert_Conducta','vect'=>$motivos,'selectHora'.$pfj=>$_REQUEST['selectHora'.$pfj], 'selectMinutos'.$pfj=>$_REQUEST['selectMinutos'.$pfj]));
			$this->salida .= "<form name=\"defuncion$pfj\"' action='".$accion."' method='POST'>";

			/*-------------------------------------------
				Segemento que imprime en pantalla
				la hora en que se produjo la muerte del paciente
			  -------------------------------------------
			*/
			$selectHora=$_REQUEST['selectHora'.$pfj];
			$selectMinutos=$_REQUEST['selectMinutos'.$pfj];
			$this->salida .= "<table colspan=\"2\" align=\"center\" width=\"80%\" border=\"0\" class=\"modulo_table_list\">\n";
   			$this->salida .= $this->SetStyle("MensajeError",11);
			$this->salida .= "<tr class='modulo_table_title'>\n";
			$this->salida .= "<td align='center' width=\"50%\">FECHA Y HORA DE DEFUNCION\n";
			$this->salida .= "</td>\n";
			$this->salida .= "<td align='center' width=\"50%\">\n";

			$this->salida.="<input type='text' readonly class='input-text'  size = 9 maxlength=10 name = 'fechadef$pfj'  value =\"".$_REQUEST['fechadef'.$pfj]."\">";
			$this->salida.="&nbsp;&nbsp;".ReturnOpenCalendario("defuncion$pfj",'fechadef'.$pfj, '/');
			$this->salida.="&nbsp;&nbsp";
			$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

			if(date("H:i:s") <= $hora_inicio_turno)
			{
				list($h,$m,$s)=explode(":",$hora_control);
			}
			else
			{//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
				list($h,$m,$s)=explode(":",$hora_control);
			}

			$i=0;

			$this->salida .= "<select name=\"selectHora$pfj\" class=\"select\">\n";

			for($j=0; $j<$rango_turno; $j++)
			{
				if ($i==23)
				{
					list($h,$m,$s)=explode(":",$hora_inicio_turno);
					$i=date("H",mktime($h+$j,$m,$s));
				}
				else
				{
					list($h,$m,$s)=explode(":",$hora_inicio_turno);
					$i=date("H",mktime($h+$j,$m,$s));
				}

				if($i==$selectHora)
				{
					$selected=true;
					$this->salida .="<option value='".$i."' selected>".$i."</option>\n";
				}
				else
				{
					$this->salida .="<option value='".$i."' >".$i."</option>\n";
				}
				#################################################
				###########################
			}//fin for

			if(empty($selected))
			{
				$this->salida .= "<option value='".date("H")."' selected='true'>".date("H")."</option>\n";
			}
				$this->salida .= "</select>:&nbsp;\n";
				$this->salida .= "<select name='selectMinutos$pfj' class='select'>\n";

			for($j=0; $j<=59; $j++)
			{
				if(empty($selectMinutos)){
					if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}//$selected = "";
				}
				else
				{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
					if($j == $selectMinutos){ $selected = "selected='true'";} else { $selected = "";}
				}
					if($j<10)
					{
						$this->salida .= "<option value='0$j' $selected>0$j</option>\n";
					}
					else
					{
						$this->salida .= "<option value='$j' $selected>$j</option>\n";
					}
				}
			$this->salida .= "</select>\n";
			$this->salida .= "</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "</table><br>\n";


			/*-----------------------------------------------------------*/

			$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="<td align=\"center\" colspan=\"4\">SITIO DE DEFUNCION</td>";
			$this->salida.="</tr>";
			$p=0;
			$m=4;
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$nure=sizeof($motivos);
			$cols=$nure%4;
			foreach ($motivos as $k=> $v)
			{
				if($p == $m)
				{
					$m=$m+4;
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					if($p+1==$nure)
					{
						$cols1=$cols+1;
					}
					else
					{
						$cols1=1;
					}
					if ($_REQUEST['motivo'.$pfj] != $v[motivo_defuncion_id])
					{
						$this->salida.="<td colspan=\"".$cols1."\" align=\"left\"><input type = \"radio\" name=\"motivo$pfj\" value =\"$v[motivo_defuncion_id]\">&nbsp;&nbsp;$v[descripcion]</td>";
					}
					else
					{
						$this->salida.="<td colspan=\"".$cols1."\" align=\"left\"><input type = \"radio\" checked name=\"motivo$pfj\" value =\"$v[motivo_defuncion_id]\">&nbsp;&nbsp;$v[descripcion]</td>";
					}
     			}
				else
				{
					if($p+1==$nure)
					{
					$cols1=$cols+1;
					}
					else
					{
					$cols1=1;
					}
					if ($_REQUEST['motivo'.$pfj] != $v[motivo_defuncion_id])
					{
						$this->salida.="<td colspan=\"".$cols1."\" align=\"left\"><input type = \"radio\" name=\"motivo$pfj\" value =\"$v[motivo_defuncion_id]\">&nbsp;&nbsp;$v[descripcion]</td>";
					}
					else
					{
						$this->salida.="<td colspan=\"".$cols1."\" align=\"left\"><input type = \"radio\" checked name=\"motivo$pfj\" value =\"$v[motivo_defuncion_id]\">&nbsp;&nbsp;$v[descripcion]</td>";
					}
				}
				$p++;
			}
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="<td align=\"center\" colspan=\"2\">CERTIFICADO DE DEFUNCION EXPEDIDO POR:</td>";
			$this->salida.="</tr>";
			$c=0;
			$m=2;
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

			foreach ($tipos as $a=> $t)
			{
				if($c == $m)
				{
					$m=$m+2;
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

					if ($_REQUEST['certificado'.$pfj] != $t[tipo_certificado_id])
					{
						$this->salida.="<td align=\"left\"><input type = \"radio\" name=\"certificado$pfj\" value =\"$t[tipo_certificado_id]\">&nbsp;&nbsp;$t[descripcion]</td>";
					}
					else
					{
						$this->salida.="<td align=\"left\"><input type = \"radio\" checked name=\"certificado$pfj\" value =\"$t[tipo_certificado_id]\">&nbsp;&nbsp;$t[descripcion]</td>";
					}
				}
				else
				{
					if ($_REQUEST['certificado'.$pfj] != $t[tipo_certificado_id])
					{
						$this->salida.="<td align=\"left\"><input type = \"radio\" name=\"certificado$pfj\" value =\"$t[tipo_certificado_id]\">&nbsp;&nbsp;$t[descripcion]</td>";
					}
					else
					{
						$this->salida.="<td align=\"left\"><input type = \"radio\" checked name=\"certificado$pfj\" value =\"$t[tipo_certificado_id]\">&nbsp;&nbsp;$t[descripcion]</td>";
					}
				}
				$c++;
			}
			$this->salida.="</table>";

			$FechaInicio = $this->datosPaciente[fecha_nacimiento];
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
			$sexpaciente=$this->SexodePaciente();

			if ($sexpaciente[0]['sexo_id']=='F' AND ($edad_paciente[anos] > 10 &&  $edad_paciente[anos] < 54))
			{
				$this->salida.="<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="<td colspan=\"3\" align=\"center\">DEFUNCION DE MUJERES EN EDAD FERTIL (10 - 54 AÑOS)</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td>";

				$this->salida.="<table border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"center\"> ¿ESTABA EMBARAZADA CUANDO FALLECIO? </td>";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_list_oscuro\">";
				if ($_REQUEST['estado'.$pfj] == '1')
				{
					$this->salida.="<input type=\"radio\" name=\"estado$pfj\" value=\"1\" checked> SI<br>";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"estado$pfj\" value=\"1\"> SI<br>";
				}

				if ($_REQUEST['estado'.$pfj] == '2')
				{
					$this->salida.="<input type=\"radio\" name=\"estado$pfj\" value=\"2\" checked> NO<br>";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"estado$pfj\" value=\"2\"> NO<br>";
				}

				if ($_REQUEST['estado'.$pfj] == '3')
				{
					$this->salida.="<input type=\"radio\" name=\"estado$pfj\" value=\"3\" checked> SIN INFORMACION </td>";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"estado$pfj\" value=\"3\">  SIN INFORMACION </td>";
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="<td>";

				$this->salida.="<table width=\"100%\" border=\"0\" class=\"modulo_table_list\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"center\"> ¿ESTUVO EMBARAZADA EN LAS ULTIMAS 6 SEMANAS? </td>";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_list_claro\">";
				if ($_REQUEST['semanas'.$pfj] == '1')
				{
					$this->salida.="<input type=\"radio\" name=\"semanas$pfj\" value=\"1\" checked> SI<br>";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"semanas$pfj\" value=\"1\"> SI<br>";
				}

				if ($_REQUEST['semanas'.$pfj] == '2')
				{
					$this->salida.="<input type=\"radio\" name=\"semanas$pfj\" value=\"2\" checked> NO<br>";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"semanas$pfj\" value=\"2\"> NO<br>";
				}

				if ($_REQUEST['semanas'.$pfj] == '3')
				{
					$this->salida.="<input type=\"radio\" name=\"semanas$pfj\" value=\"3\" checked> SIN INFORMACION </td>";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"semanas$pfj\" value=\"3\">  SIN INFORMACION </td>";
				}

				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="<td>";

				$this->salida.="<table width=\"100%\" border=\"0\" class=\"modulo_table_list\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"center\"> ¿ESTUVO EMBARAZADA EN LOS ULTIMOS 12 MESES?</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_list_oscuro\">";
				if ($_REQUEST['meses'.$pfj] == '1')
				{
					$this->salida.="<input type=\"radio\" name=\"meses$pfj\" value=\"1\" checked> SI<br>";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"meses$pfj\" value=\"1\"> SI<br>";
				}

				if ($_REQUEST['meses'.$pfj] == '2')
				{
					$this->salida.="<input type=\"radio\" name=\"meses$pfj\" value=\"2\" checked> NO<br>";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"meses$pfj\" value=\"2\"> NO<br>";
				}

				if ($_REQUEST['meses'.$pfj] == '3')
				{
					$this->salida.="<input type=\"radio\" name=\"meses$pfj\" value=\"3\" checked> SIN INFORMACION </td>";
				}
				else
				{
					$this->salida.="<input type=\"radio\" name=\"meses$pfj\" value=\"3\">  SIN INFORMACION </td>";
				}

				$this->salida.="</tr>";
				$this->salida.="</table> </td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
			}

			$diag =$this->ConsultaDiagnosticoI();
			if ($diag)
			{
				$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td align=\"center\" colspan=\"6\">DIAGNOSTICOS DE MUERTE O DEFUNCION ASIGNADOS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"10%\">CODIGO</td>";
				$this->salida.="<td width=\"65%\">DIAGNOSTICO</td>";
				$this->salida.="<td width=\"42%\">Diagnostico Y Tiempo de Muerte</td>";
				$this->salida.="<td width=\"2%\">INSERTAR</td>";
				$this->salida.="</tr>";

				for($i=0;$i<sizeof($diag);$i++)
				{
					$diagnostico_id = $diag[$i][diagnostico_id];
					if( $i % 2){$estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_id]."</td>";
					$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
					$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_muerte]."</td>";

					$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'cambiar_descripcion', 'diagnostico_id'.$pfj =>$diag[$i][diagnostico_id],'descripcion'.$pfj=>$diag[$i][diagnostico_nombre], 'contenido'.$pfj=>$diag[$i][diagnostico_muerte],
					'selectHora'.$pfj=>$_REQUEST['selectHora'.$pfj], 'selectMinutos'.$pfj=>$_REQUEST['selectMinutos'.$pfj],
					'motivo'.$pfj=>$_REQUEST['motivo'.$pfj],'certificado'.$pfj=>$_REQUEST['certificado'.$pfj],
					'estado'.$pfj=>$_REQUEST['estado'.$pfj],'semanas'.$pfj=>$_REQUEST['semanas'.$pfj],'meses'.$pfj=>$_REQUEST['meses'.$pfj],'fechadef'.$pfj=>$_REQUEST['fechadef'.$pfj]));

					$this->salida.="<td align=\"center\"><a href='$accion'><img src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
					$this->salida.="<tr>";
				}
				$this->salida.="</table><br>";
			}

			if(!empty($vectorD))
			{
				$this->frmFormaDiagnosticos($vectorD);
			}
			else
			{
				$this->frmFormaDiagnosticos();
			}
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\"><input class=\"input-submit\" name=\"guardar_partida_defuncion$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</form>";

			$this->salida .= ThemeCerrarTablaSubModulo();
			return true;
		}




	function frmFormaConfirmacion()
	{
		$pfj = $this->frmPrefijo;
		$info1 = $this->GetDatos_Certificado();
		$info2 = $this->GetDatos_Motivo();
		$diag = $this->ConsultaDiagnosticoI();
		$info3 = $this->GetDatos_ConductaMujer();


		$this->salida= ThemeAbrirTablaSubModulo('REPORTE DE CERTIFICADO DE DEFUNCION');

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td colspan=\"2\" align=\"center\">CERTIFICADO DE DEFUNCION</td>";
		$this->salida.="</tr>";

		foreach ($info1 as $k => $v)
		{
			list($fecha,$hora) = explode(" ",$this->PartirFecha($v[0]));
			list($ano,$mes,$dia) = explode("-",$fecha);
			list($hora,$min) = explode(":",$hora);
			$hora = $hora.":".$min;
			$fecha = $fecha;

			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"2\">FECHA Y HORA DEL DESCESO</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\" class=\"modulo_list_claro\">$fecha</td>";
			$this->salida.="<td align=\"center\" class=\"modulo_list_claro\">$hora</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"2\">INFORMACION DEL PROFESIONAL TRATANTE</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\">EXPEDIDO POR:</td>";
			$this->salida.="<td align=\"center\" class=\"modulo_list_claro\">$v[3]</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\">NOMBRE DEL PROFESIONAL:</td>";
			$this->salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$v[4]</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td colspan=\"2\" align=\"center\">$v[5]</td>";
			$this->salida.="</tr>";
		}
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\">SITIO DEL DESCESO</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$info2</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
		$sexpaciente=$this->SexodePaciente();
		if ($sexpaciente[0]['sexo_id']=='F' AND ($edad_paciente[anos] > 10 &&  $edad_paciente[anos] < 54))
		{
			foreach ($info3 as $k3 => $v3)
			{
				if ($v3[0] == '1'){$estado = "SI ESTUVO EMBARAZADA";}
				elseif ($v3[0] == '2'){$estado = "NO ESTUVO EMBARAZADA";}
				else{$estado = "NO HAY INFORMACION AL RESPECTO";}

				if ($v3[1] == '1'){$estado1 = "SI ESTUVO EMBARAZADA";}
				elseif ($v3[1] == '2'){$estado1 = "NO ESTUVO EMBARAZADA";}
				else{$estado1 = "NO HAY INFORMACION AL RESPECTO";}

				if ($v3[2] == '1'){$estado2 = "SI ESTUVO EMBARAZADA";}
				elseif ($v3[2] == '2'){$estado2 = "NO ESTUVO EMBARAZADA";}
				else{$estado2 = "NO HAY INFORMACION AL RESPECTO";}

				$this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td align=\"center\" colspan=\"2\">INFORMACION FERTIL DEL PACIENTE (10 - 54 AÑOS)</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td align=\"left\" class=\"modulo_list_oscuro\"> ¿ESTABA EMBARAZADA CUANDO FALLECIO? </td>";
				$this->salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$estado</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr>";
				$this->salida.="<td align=\"left\" class=\"modulo_list_oscuro\"> ¿ESTUVO EMBARAZADA EN LAS ULTIMAS 6 SEMANAS? </td>";
				$this->salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$estado1</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr>";
				$this->salida.="<td align=\"left\" class=\"modulo_list_oscuro\"> ¿ESTUVO EMBARAZADA EN LOS ULTIMOS 12 MESES? </td>";
				$this->salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$estado2</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
			}
		}


		if ($diag)
		{
			$this->salida.="<br><table  align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"3\">DIAGNOSTICOS DE MUERTE O DEFUNCION ASIGNADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">CODIGO</td>";
			$this->salida.="<td width=\"50%\">DIAGNOSTICO</td>";
			$this->salida.="<td width=\"40%\">Diagnostico Y Tiempo de Muerte</td>";
			$this->salida.="</tr>";

			for($i=0;$i<sizeof($diag);$i++)
			{
				$diagnostico_id = $diag[$i][diagnostico_id];
				if( $i % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_id]."</td>";
				$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
				$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_muerte]."</td>";
			}
			$this->salida.="</table><br>";
		}

		$this->salida.="<center>";
		$this->salida.="<label class=\"label_mark\">EL CERTIFICADO DE DEFUNCION FUE EXPEDIDO SATISFACTORIAMENTE.</label>";
		$this->salida.="</center>";

		$reporte= new GetReports();
		$mostrar=$reporte->GetJavaReport('system','reportes','certificado_defuncion_html',array('ingreso'=>$this->ingreso, 'evolucion'=>$this->evolucion),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
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



	function CambiarDescripcion()
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('DIAGNOSTICOS DE MUERTE O DEFUNCION');

		$accionA=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Insertar_Descripcion','codigo'.$pfj=>$_REQUEST['diagnostico_id'.$pfj],
		'selectHora'.$pfj=>$_REQUEST['selectHora'.$pfj], 'selectMinutos'.$pfj=>$_REQUEST['selectMinutos'.$pfj],
		'motivo'.$pfj=>$_REQUEST['motivo'.$pfj],'certificado'.$pfj=>$_REQUEST['certificado'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],'semanas'.$pfj=>$_REQUEST['semanas'.$pfj],'meses'.$pfj=>$_REQUEST['meses'.$pfj],'fechadef'.$pfj=>$_REQUEST['fechadef'.$pfj]));

		$this->salida.="<form name=\"descripcion$pfj\" action=\"$accionA\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table width=\"80%\" align=\"center\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\" width=\"15%\">CODIGO</td>";
		$this->salida.="<td align=\"center\" colspan=\"2\" width=\"65%\">NOMBRE</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"left\" width=\"50%\" colspan=\"2\" class=\"modulo_list_claro\">".$_REQUEST['diagnostico_id'.$pfj]."</td>";
		$this->salida.="<td align=\"left\" width=\"50%\" colspan=\"2\" class=\"modulo_list_claro\">".$_REQUEST['descripcion'.$pfj]."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<br>";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"50%\" class=\"modulo_table_list\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
		$this->salida.="<td align =\"left\" width=\"50%\"><textarea name='descripcion_diag$pfj' cols=40 rows=7>".$_REQUEST['contenido'.$pfj]."</textarea>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr>";
		$this->salida.="<td colspan=\"2\" width=\"50%\" align=\"right\"><input class=\"input-submit\" name=\"insertar$pfj\" type=\"submit\" value=\"INSERTAR\"></td>";
		$this->salida.="</form>";

		$accionB=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Volver_Original',
		'selectHora'.$pfj=>$_REQUEST['selectHora'.$pfj], 'selectMinutos'.$pfj=>$_REQUEST['selectMinutos'.$pfj],
		'motivo'.$pfj=>$_REQUEST['motivo'.$pfj],'certificado'.$pfj=>$_REQUEST['certificado'.$pfj],
		'estado'.$pfj=>$_REQUEST['estado'.$pfj],'semanas'.$pfj=>$_REQUEST['semanas'.$pfj],'meses'.$pfj=>$_REQUEST['meses'.$pfj],'fechadef'.$pfj=>$_REQUEST['fechadef'.$pfj]));

		$this->salida.="<form name=\"descripcion2$pfj\" action=\"$accionB\" method=\"post\">";
		$this->salida.="<td colspan=\"2\" width=\"50%\" align=\"left\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</table><br>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

     /*
     * frmConsulta
     *
     * Muestra el resumen de los signos vitales registrados del paciente seleccionado
     * @Author Tizziano Perea O
     * @access Private
     * @return boolean
     */
     function frmConsulta()
     {
          $pfj=$this->frmPrefijo;
          return true;
     }//frmConsulta
     
}
?>
