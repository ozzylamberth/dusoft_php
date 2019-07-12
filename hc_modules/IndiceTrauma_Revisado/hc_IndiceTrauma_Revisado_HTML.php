<?php

/**
* Submodulo Indice Trauma Revisado.
*
* Submodulo para manejar el sistema de Traumas de los pacientes ingresados.
* @author Tizziano Perea O. <tperea@ipsoft-sa.com>
* @version 1.0
* @package SIIS
* $Id: hc_IndiceTrauma_Revisado_HTML.php,v 1.7 2007/03/20 16:47:42 tizziano Exp $
*/

/**
* IndiceTrauma_Revisado_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo IndiceTrauma_Revisado, se extiende la clase IndiceTrauma_Revisado y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class IndiceTrauma_Revisado_HTML extends IndiceTrauma_Revisado
{

	function IndiceTrauma_Revisado_HTML()
	{
	    $this->IndiceTrauma_Revisado();//constructor del padre
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
	function RetornarBarra_Paginadora()//Barra paginadora
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Listar_Indices','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj]));
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


	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$VectorI = $this->Listar_ITR_Impresion();
		if($VectorI)
		{
			if (empty($contador)){
				$contador=sizeof($VectorI);
			}
			
               $this->salida .="<br><table align=\"center\" width=\"100%\" border='0'>";
			$this->salida .="<tr class='modulo_table_title'>";
               $this->salida .="<td colspan=\"9\" align=\"center\">LISTADO DE ITR</td>";
			$this->salida .="</tr>";
               $this->salida .="<tr class='hc_table_submodulo_list_title'>";
			$this->salida .="<td align=\"center\">FECHA</td>";
			$this->salida .="<td align=\"center\">HORA</td>";
			$this->salida .="<td align=\"center\">REGION</td>";
			$this->salida .="<td align=\"center\">TRAUMA</td>";
			$this->salida .="<td align=\"center\">CARDIOVASCULAR</td>";
			$this->salida .="<td align=\"center\">RESPIRATORIO</td>";
			$this->salida .="<td align=\"center\">SNC</td>";
			$this->salida .="<td align=\"center\">INDICE DE TRAUMA</td>";
			$this->salida .="<td align=\"center\">USUARIO</td>";
			$this->salida .="</tr>";
			$cont=1;
			$spy=0;
			while ($cont <= sizeof($VectorI) && $cont <= $contador)
			{
				list($fecha,$hora) = explode(" ",$VectorI[$cont-1][fecha_registro]);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$hora=$hora.":".$min;
				$fecha = $fecha;

				if($spy==0)
				{
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$this->salida.="<tr class=\"modulo_list_claro\">";
					$spy=0;
				}

				if($VectorI[$cont-1][region] == 0) $region = "--"; else $region = $VectorI[$cont-1][region];
				if($VectorI[$cont-1][tipo_trauma] == 0) $trauma = "--"; else $trauma = $VectorI[$cont-1][tipo_trauma];
				if($VectorI[$cont-1][cardiovascular] == ' ') $cardio = "--"; else $cardio = $VectorI[$cont-1][cardiovascular];
				if($VectorI[$cont-1][respiratorio] == 0) $respiratorio = "--"; else $respiratorio = $VectorI[$cont-1][respiratorio];
				if($VectorI[$cont-1][snc] == ' ') $snc = "--"; else $snc = $VectorI[$cont-1][snc];
				if($VectorI[$cont-1][usuario] == ' ') $usuario = "--"; else $usuario = $VectorI[$cont-1][usuario];
				if($VectorI[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorI[$cont-1][fuerza_brazo_d];
				if($VectorI[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorI[$cont-1][fuerza_brazo_i];
				if($VectorI[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorI[$cont-1][fuerza_pierna_d];

				$IT = $region + $trauma + $cardio + $respiratorio + $snc;
				$this->salida .="<td align=\"center\">" .$fecha. "</td>";
				$this->salida .="<td align=\"center\">" .$hora. "</td>";
				$this->salida .="<td align=\"center\">" .$region. "</td>";
				$this->salida .="<td align=\"center\">" .$trauma. "</td>";
				$this->salida .="<td align=\"center\">" .$cardio. "</td>";
				$this->salida .="<td align=\"center\">" .$respiratorio. "</td>";
				$this->salida .="<td align=\"center\">" .$snc. "</td>";
				$this->salida .="<td align=\"center\" class=\"label_error\">" .$IT. "</td>";
				$this->salida .="<td align=\"center\">" .$usuario. "</td>";

				$this->salida .="</tr>";
				$cont++;
			}
			$this->salida .="</table><br>";
		}
	    return true;
	}
     
     /**
     * Funcion de Impresion de En Papel.
     */
     function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$VectorI = $this->Listar_ITR_Impresion();
		if($VectorI)
		{
			if (empty($contador)){
				$contador=sizeof($VectorI);
			}
			
               $salida .="<br><table align=\"center\" width=\"100%\" border='1'>";
			$salida .="<tr>";
               $salida .="<td colspan=\"9\" align=\"center\">LISTADO DE ITR</td>";
			$salida .="</tr>";
               $salida .="<tr>";
			$salida .="<td align=\"center\">FECHA</td>";
			$salida .="<td align=\"center\">HORA</td>";
			$salida .="<td align=\"center\">REGION</td>";
			$salida .="<td align=\"center\">TRAUMA</td>";
			$salida .="<td align=\"center\">CARDIOVASCULAR</td>";
			$salida .="<td align=\"center\">RESPIRATORIO</td>";
			$salida .="<td align=\"center\">SNC</td>";
			$salida .="<td align=\"center\">INDICE DE TRAUMA</td>";
			$salida .="<td align=\"center\">USUARIO</td>";
			$salida .="</tr>";
			$cont=1;
			$spy=0;
			while ($cont <= sizeof($VectorI) && $cont <= $contador)
			{
				list($fecha,$hora) = explode(" ",$VectorI[$cont-1][fecha_registro]);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$hora=$hora.":".$min;
				$fecha = $fecha;

                    $salida.="<tr>";

				if($VectorI[$cont-1][region] == 0) $region = "--"; else $region = $VectorI[$cont-1][region];
				if($VectorI[$cont-1][tipo_trauma] == 0) $trauma = "--"; else $trauma = $VectorI[$cont-1][tipo_trauma];
				if($VectorI[$cont-1][cardiovascular] == ' ') $cardio = "--"; else $cardio = $VectorI[$cont-1][cardiovascular];
				if($VectorI[$cont-1][respiratorio] == 0) $respiratorio = "--"; else $respiratorio = $VectorI[$cont-1][respiratorio];
				if($VectorI[$cont-1][snc] == ' ') $snc = "--"; else $snc = $VectorI[$cont-1][snc];
				if($VectorI[$cont-1][usuario] == ' ') $usuario = "--"; else $usuario = $VectorI[$cont-1][usuario];
				if($VectorI[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorI[$cont-1][fuerza_brazo_d];
				if($VectorI[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorI[$cont-1][fuerza_brazo_i];
				if($VectorI[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorI[$cont-1][fuerza_pierna_d];

				$IT = $region + $trauma + $cardio + $respiratorio + $snc;
				$salida .="<td align=\"center\">" .$fecha. "</td>";
				$salida .="<td align=\"center\">" .$hora. "</td>";
				$salida .="<td align=\"center\">" .$region. "</td>";
				$salida .="<td align=\"center\">" .$trauma. "</td>";
				$salida .="<td align=\"center\">" .$cardio. "</td>";
				$salida .="<td align=\"center\">" .$respiratorio. "</td>";
				$salida .="<td align=\"center\">" .$snc. "</td>";
				$salida .="<td align=\"center\" class=\"label_error\">" .$IT. "</td>";
				$salida .="<td align=\"center\">" .$usuario. "</td>";

				$salida .="</tr>";
				$cont++;
			}
			$salida .="</table><br>";
		}
		return $salida;
     }


	function SetStyle($campo)
	{
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr><td align='center' class=\"label_error\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}


	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('INDICE DE TRAUMA REVISADO - ITR');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$Regiones = $this->GetTipoRegion();
		$Trauma = $this->GetTipoTrauma();
		$SNC = $this->GetTiposSNC();

		$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Insertar_Indices'));
		$this->salida .= "<form name=\"ITR$pfj\"' action='".$href."' method='POST'>";

		$selectHora=$_REQUEST['selectHora'.$pfj];
		$selectMinutos=$_REQUEST['selectMinutos'.$pfj];
		$this->salida .= "<table colspan=\"2\" align=\"center\" width=\"90%\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= $this->SetStyle("MensajeError",11);
		$this->salida .= "<tr class='modulo_table_title'>\n";
		$this->salida .= "<td align='center' width=\"50%\">REGISTRO DE INDICES DE TRAUMAS REVISADOS\n";
		$this->salida .= "</td>\n";
		$this->salida .= "<td align='center' width=\"50%\">\n";

		$this->salida.="<input type='text' readonly class='input-text'  size = 9 maxlength=10 name = 'fechadef$pfj'  value =\"".$_REQUEST['fechadef'.$pfj]."\">";
		$this->salida.="&nbsp;&nbsp;".ReturnOpenCalendario("ITR$pfj",'fechadef'.$pfj, '/');
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

		/*---------------------------------------------------------------------
		*	ESTRUCTURA EN HTML DE LOS SISTEMAS NEUROLOGICOS A EVALUAR
		*	TIZZIANO PEREA O.
		---------------------------------------------------------------------*/

		$this->salida.="<table border='0' align='center' valign='top' width='90%' class=\"modulo_table_list\">";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table width='100%' valign='top' border='1' align='center' class=\"modulo_table_list\">";

		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td colspan='3' align='center'> INDICES</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr><td width='30%'>";
		$this->salida.="<table border='0' width='100%'  cellspacing='3' cellpadding='3' class=\"modulo_table_list\">";
		$this->salida.="<div class='hc_table_submodulo_list_title'>REGION</div>";

		foreach ($Regiones as $k => $R)
		{
			$this->salida.="<tr>";
			$this->salida.="<td class=\"modulo_list_claro\">".$R[tipo_region_id].' -   '.$R[descripcion]."</td>";
			$this->salida.="<td align='center' class=\"modulo_list_oscuro\">";
			if ($_REQUEST['region'.$pfj] != $R['tipo_region_id'])
			{
				$this->salida.="<input type='radio' name='region$pfj' value='".$R['tipo_region_id']."'></td>";
			}
			else
			{
					$this->salida.="<input type='radio' name='region$pfj' value='".$R['tipo_region_id']."' checked></td>";
			}
			$this->salida.="</tr>";
		}

		$this->salida.="</td></tr>";
		$this->salida.="</table>";

		$this->salida.="<td width='30%'>";
		$this->salida.="<table border='0' cellspacing='2' cellpadding='2' width='100%' class=\"modulo_table_list\">";
		$this->salida.="<div class='hc_table_submodulo_list_title'>TIPO DE TRAUMA</div>";
		foreach ($Trauma as $k => $T)
		{
			$this->salida.="<tr>";
			$this->salida.="<td class=\"modulo_list_claro\">".$T[tipo_trauma_id].' -   '.$T[descripcion]."</td>";
			$this->salida.="<td align='center' class=\"modulo_list_oscuro\">";
			if ($_REQUEST['trauma'.$pfj] != $T['tipo_trauma_id'])
			{
				$this->salida.="<input type='radio' name='trauma$pfj' value='".$T['tipo_trauma_id']."'></td>";
			}
			else
			{
				$this->salida.="<input type='radio' name='trauma$pfj' value='".$T['tipo_trauma_id']."' checked ></td>";
			}
			$this->salida.="</tr>";
		}
		$this->salida.="</table></td>";

		$this->salida.="<td width='30%'>";
		$this->salida.="<table border='0' cellspacing='3' cellpadding='3' width='100%' class=\"modulo_table_list\">";
		$this->salida.="<div class='hc_table_submodulo_list_title'>SISTEMA NERVIOSO CENTRAL</div>";

		foreach ($SNC as $k => $S)
		{
			$this->salida.="<tr>";
			$this->salida.="<td class=\"modulo_list_claro\">".$S[tipo_snc_id].' -   '.$S[descripcion]."</td>";
			$this->salida.="<td align='center' class=\"modulo_list_oscuro\">";
			if ($_REQUEST['snc'.$pfj] != $S['tipo_snc_id'])
			{
				$this->salida.="<input type='radio' name='snc$pfj' value='".$S['tipo_snc_id']."'></td>";
			}
			else
			{
				$this->salida.="<input type='radio' name='snc$pfj' value='".$S['tipo_snc_id']."' checked></td>";
			}
			$this->salida.="</tr>";
		}

		$this->salida.="</table></td>";

		$this->salida.="</td></tr>";
		$this->salida.="</table>";
		$this->salida.="</td></tr>";
		$this->salida.="</table><br>";

		$this->salida.="<table border=\"0\" align=\"center\" width=\"90%\" class=\"modulo_table_list\">";//cellspacing='3' cellpadding='6'

		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td colspan='2' align='center' width=\"50%\">";

		$this->salida.="<table border=\"0\" width=\"100%\" class=\"modulo_table_list\">";//cellspacing='3' cellpadding='6'
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align='center'> SISTEMA CARDIOVASCULAR</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_list_claro\">";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<select  name =\"cardio$pfj\" class =\"select\">";
		$this->salida.= "<option value =\"-1\">------------</option>";
		$CardioV=$this->Get_TipoCardioVascular();
		for($i=0; $i<sizeof($CardioV); $i++)
		{
			if ($CardioV[$i][tipo_cardiovascular_id] == $_REQUEST['cardio'.$pfj])
			{
				$this->salida .=" <option value=\"".$CardioV[$i][tipo_cardiovascular_id]."\" selected>".$CardioV[$i][descripcion]."</option>";
			}
			else
			{
				$this->salida .=" <option value=\"".$CardioV[$i][tipo_cardiovascular_id]."\">".$CardioV[$i][descripcion]."</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td'>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td'>";

		$this->salida.="<td align='center' width=\"50%\">";

		$this->salida.="<table border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align='center'>  SISTEMA RESPIRATORIO</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_list_claro\">";
		$this->salida.="<td align=\"center\">";
		$this->salida.="<select  name =\"fr$pfj\" class =\"select\">";
		$this->salida.= "<option value =\"-1\">------------</option>";
		$Respiratorio = $this->Get_TipoRespiratorio();
		for($i=0; $i<sizeof($Respiratorio); $i++)
		{
			if ($Respiratorio[$i][tipo_respiratorio_id] == $_REQUEST['fr'.$pfj])
			{
				$this->salida .=" <option value=\"".$Respiratorio[$i][tipo_respiratorio_id]."\" selected>".$Respiratorio[$i][descripcion]."</option>";
			}
			else
			{
				$this->salida .=" <option value=\"".$Respiratorio[$i][tipo_respiratorio_id]."\">".$Respiratorio[$i][descripcion]."</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td'>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td'>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";

		$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='Save$pfj' value='INSERTAR'>";
		$this->salida.="</form>";
		if (!empty($VectorI))
		{
			$this->ShowIndice_Trauma();
		}
		else
		{
			$this->ShowIndice_Trauma();
			$this->salida .= ThemeCerrarTablaSubModulo();
		}
		return true;
	}


	function ShowIndice_Trauma()
	{
		$pfj=$this->frmPrefijo;
		$VectorI = $this->Listar_ITR();
		
		/*Insercion del buscador*/
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Listar_Indices'));
		$this->salida.= "<form name=\"indices$pfj\" action=\"$accionI\" method=\"post\">";
		if(empty($VectorI))
		{
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA INDICES DE TRAUMA<br><br>";
			return true;
		}
		elseif($VectorI != "ShowMensaje")
		{
			if (empty($contador)){
				$contador=sizeof($VectorI);
			}
			$this->salida .="<div class='label_mark' align='center'><br><br>LISTADO DE INDICES DE TRAUMA<br><br>";
			$this->salida .="<table align=\"center\" width=\"90%\" border='0'>";
			$this->salida .="<tr class=\"modulo_table_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td>HORA</td>";
			$this->salida .="<td>REGION</td>";
			$this->salida .="<td>TRAUMA</td>";
			$this->salida .="<td>CARDIOVASCULAR</td>";
			$this->salida .="<td>RESPIRATORIO</td>";
			$this->salida .="<td>SNC</td>";
			$this->salida .="<td>INDICE DE TRAUMA</td>";
			$this->salida .="<td>USUARIO</td>";
			$this->salida .="</tr>";
			$cont=1;
			$spy=0;
			while ($cont <= sizeof($VectorI) && $cont <= $contador)
			{
				list($fecha,$hora) = explode(" ",$VectorI[$cont-1][fecha_registro]);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$hora=$hora.":".$min;
				$fecha = $fecha;

				if($spy==0)
				{
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$this->salida.="<tr class=\"modulo_list_claro\">";
					$spy=0;
				}

				if($VectorI[$cont-1][region] == 0) $region = "--"; else $region = $VectorI[$cont-1][region];
				if($VectorI[$cont-1][tipo_trauma] == 0) $trauma = "--"; else $trauma = $VectorI[$cont-1][tipo_trauma];
				if($VectorI[$cont-1][cardiovascular] == ' ') $cardio = "--"; else $cardio = $VectorI[$cont-1][cardiovascular];
				if($VectorI[$cont-1][respiratorio] == 0) $respiratorio = "--"; else $respiratorio = $VectorI[$cont-1][respiratorio];
				if($VectorI[$cont-1][snc] == ' ') $snc = "--"; else $snc = $VectorI[$cont-1][snc];
				if($VectorI[$cont-1][usuario] == ' ') $usuario = "--"; else $usuario = $VectorI[$cont-1][usuario];
				if($VectorI[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorI[$cont-1][fuerza_brazo_d];
				if($VectorI[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorI[$cont-1][fuerza_brazo_i];
				if($VectorI[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorI[$cont-1][fuerza_pierna_d];

				$IT = $region + $trauma + $cardio + $respiratorio + $snc;
				$this->salida .="<td align=\"center\">" .$fecha. "</td>";
				$this->salida .="<td align=\"center\">" .$hora. "</td>";
				$this->salida .="<td align=\"center\">" .$region. "</td>";
				$this->salida .="<td align=\"center\">" .$trauma. "</td>";
				$this->salida .="<td align=\"center\">" .$cardio. "</td>";
				$this->salida .="<td align=\"center\">" .$respiratorio. "</td>";
				$this->salida .="<td align=\"center\">" .$snc. "</td>";
				$this->salida .="<td align=\"center\" class=\"label_error\">" .$IT. "</td>";
				$this->salida .="<td align=\"center\">" .$usuario. "</td>";

				$this->salida .="</tr>";
				$cont++;
			}
			$this->salida .="</table>";
			//Mostrar Barra de Navegacion
			$VectorI=$this->RetornarBarra_Paginadora();
			if($VectorI)
			{
				$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
				$this->salida .= "  <tr>";
				$this->salida .= "  <td width=\"100%\" align=\"center\">";
				$this->salida .=$VectorI;
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table><br>";
			}
			$this->salida .= "</form>";
		}
		return true;
	}

	/*-----------------------------*/
	/*POSIBLE PENDIENTE
	//$FechaInicio = $this->datosPaciente[fecha_nacimiento];
	//$FechaFin = date("Y-m-d");
	//$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
	//if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
	/*-----------------------------*/
}

?>
