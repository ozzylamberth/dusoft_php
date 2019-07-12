<?php

/**
* Submodulo de Asistencia Ventilatoria de UNIDADES ESPECIALES (HTML).
*
* Submodulo para manejar los ingresos de las Asistencias Ventilatorias de un paciente.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Asistencia_Ventilatoria_HTML.php,v 1.3 2006/12/19 21:00:13 jgomez Exp $
*/

/**
* Asistencia_Ventilatoria_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo Asistencia Ventilatoria, se extiende la clase Asistencia_Ventilatoria y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Asistencia_Ventilatoria_HTML extends Asistencia_Ventilatoria
{

	function Asistencia_Ventilatoria_HTML()
	{
	    $this->Asistencia_Ventilatoria();//constructor del padre
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

///////////////////////

/*IMPLEMENTACION DE LA BARRA DE NAVEGACION*/

	//cor - jea - ads
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	//cor - jea - ads
	function CalcularBarra($paso)
	{
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarAV','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj]));
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
		*		FrmAsistenciaVentilatoria
		*
		*		Formulario que permite ingresar datos de la asistencia ventilatoria del paciente seleccionado
		*
		*		@Author Tizziano Perea O.
		*		@access Private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@return boolean
		*/
		function frmForma()//$estacion,$datos_estacion)
		{
			$pfj=$this->frmPrefijo;
			/*
			* *****************************PARA NEONATOS****************************
			*/
			$FechaInicio = $this->datosPaciente[fecha_nacimiento];
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
			{
				if(empty($this->titulo))
				{
					$this->salida= ThemeAbrirTablaSubModulo("ASISTENCIA VENTILATORIA");
				}
				else
				{
					$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
				}

				$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarAV'));
				$this->salida .= "<form name=\"Asistecia_Ventilatoria$pfj\"' action='".$href."' method='POST'>";

				/*-------------------DATOS DE LA ASISTECIA VENTILATORIA---------------------*/
				$this->salida .= "<table colspan=\"2\" width=\"95%\" align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
				$this->salida .= $this->SetStyle("MensajeError",10);
				$this->salida .= "<tr class='modulo_table_title'>\n";
				$this->salida .= "<td align='center' width=\"50%\">ASISTENCIA VENTILATORIA\n";
				$this->salida .= "</td>\n";
				$this->salida .= "<td align='center' width=\"50%\">\n";

				$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
				$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

				if(date("H:i:s") <= $hora_inicio_turno)
				{
					list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
					list($h,$m,$s)=explode(":",$hora_control);
				}
				else
				{//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
					list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
					list($h,$m,$s)=explode(":",$hora_control);
				}

				$i=0;
				$this->salida .= "<select name=\"selectHora$pfj\" class=\"select\">\n";
				for($j=0; $j<$rango_turno; $j++)
				{
					list($anno, $mes, $dia)=explode("-",$fecha_control);
					if ($i==23)
					{
						list($h,$m,$s)=explode(":",$hora_inicio_turno);
						$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
						$fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
						$fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
					}
					else
					{
						list($h,$m,$s)=explode(":",$hora_inicio_turno);
						$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
						$fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
						$fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
					}
					if(empty($selectHora)){
						if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
					}
					else
					{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
						list($A,$B) = explode(" ",$selectHora);
						if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
					}
					#################################################
					list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
					if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
						$show = "Hoy a las";
					}
					elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
						$show = "Mañana a las";
					}
					elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
						$show = "Ayer a las";
					}
					else{
						$show = $fecha_control;
					}
					###########################
					$this->salida .="<option value='".$fecha_control." ".$i."' $selected>".$show." ".$i."</option>\n";
				}//fin for

					$this->salida .= "<option value='".date("Y-m-d H")."'selected='true'>Hoy a las ".date("H")."</option>\n";
					$this->salida .= "</select>:&nbsp;\n";
					$this->salida .= "<select name='selectMinutos$pfj' class='select'>\n";

				for($j=0; $j<=59; $j++)
				{
					if(empty($selectMinutos)){
						if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
					}
					else
					{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
						list($A,$B) = explode(" ",$selectMinutos);
						if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
					}
					if ($j<10){
						$this->salida .= "<option value='0$j:00' $selected>0$j</option>\n";
					}
						else{
						$this->salida .= "<option value='$j:00' $selected>$j</option>\n";
					}
				}
				$this->salida .= "</select>\n";
				$this->salida .= "</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n";

				/*------------- CAMPOS DE INSERCION ASISTENCIA VENTILATORIA ---------*/

				$this->salida .= "<table align=\"center\"  width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\" align='center'>\n";
				$this->salida .= "<td>MODO</td>\n";
				$this->salida .= "<td>FIO<sub>2</sub></td>\n";
				$this->salida .= "<td>F. RESP</td>\n";
				$this->salida .= "<td>F. VENT</td>\n";
				$this->salida .= "<td>ESPONT</td>\n";
				$this->salida .= "<td>ETC O<sub>2</sub></td>\n";
				$this->salida .= "<td>TI</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "<tr ".$this->Lista(1)."' align='center'>\n";
				$modos=$this->GetAsistenciaVentilatoriaModos();
				if (!empty($modos)) {
					$this->salida .= "<td><select name='modo$pfj' class='select'>";
					$this->SetOptionsAsistenciaVentilatoriaModos($modos,'');
					$this->salida .= "</select></td>\n";
				}
				else {
					$this->error = "Error al consultar la tabla \"hc_asistencia_ventilatoria_modos\"<br>";
					$this->mensajeDeError = "";
					return false;
				}

				$f102=$this->GetControlOxiConcentraciones('',1);
				if (!empty($f102))
				{
					$this->salida .= "<td align='center'>\n";
					$concentracion = $this->GetControlOxiConcentraciones($_REQUEST['f102'.$pfj],1);
					$this->salida .= "<select name='f102$pfj' class='select'>\n";
					$this->salida .= "					".$concentracion;
					$this->salida .= "</select>\n";
					$this->salida .= "</td>\n";
				}
				else {
					$this->error = "Error al consultar la tabla \"hc_asistencia_ventilatoria_f102\"<br>";
					$this->mensajeDeError = "";
					return false;
				}
				$this->salida .= "<td><input type='text' class='input-text' name='fr_respiratoria$pfj' value='".$_REQUEST['fr_respiratoria'.$pfj]."' size='5' maxlength='5'> X min</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='fr_ventilatoria$pfj' value='".$_REQUEST['fr_ventilatoria'.$pfj]."' size='6' maxlength='6'> X min</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='expontanea$pfj' value='".$_REQUEST['expontanea'.$pfj]."' size='6' maxlength='6'> X min</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='etco2$pfj' value='".$_REQUEST['etco2'.$pfj]."' size='6' maxlength='7'> mmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='ti$pfj' value='".$_REQUEST['ti'.$pfj]."' size='6' maxlength='6'> Seg</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\" align='center'>\n";
				$this->salida .= "<td>REL I:E</td>\n";
				$this->salida .= "<td>PEEP</td>\n";
				$this->salida .= "<td>PI PICO</td>\n";
				$this->salida .= "<td>PI MESETA</td>\n";
				$this->salida .= "<td>PI MEDIA</td>\n";
				$this->salida .= "<td>PAW</td>\n";
				$this->salida .= "<td>To. VIA A</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "<tr ".$this->Lista(0)."' align='center'>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='i_e$pfj' value='".$_REQUEST['i_e'.$pfj]."' size='10' maxlength='10'></td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='peep$pfj' value='".$_REQUEST['peep'.$pfj]."' size='5' maxlength='5'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='pip$pfj' value='".$_REQUEST['pip'.$pfj]."' size='5' maxlength='5'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='pp$pfj' value='".$_REQUEST['pp'.$pfj]."' size='6' maxlength='7'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='pm$pfj' value='".$_REQUEST['pm'.$pfj]."' size='6' maxlength='7'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='paw$pfj' value='".$_REQUEST['paw'.$pfj]."' size='6' maxlength='6'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='t_via_a$pfj' value='".$_REQUEST['t_via_a'.$pfj]."' size='6' maxlength='6'> (ºC)</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n\n";
				$this->salida .= "<div class='label_mark' align='center'><br><input type='submit' class='input-submit' name='Save' value='INSERTAR'>";
				$this->salida .= "</form>\n";

				if (!empty($vectorAsistencia))
				{
					$this->ShowAsistenciaVentilatoriaNeonatos();
				}
				else
				{
					$this->ShowAsistenciaVentilatoriaNeonatos();
					$this->salida .= ThemeCerrarTablaSubModulo();
				}
			}
			/************************************************************************/
			/*****************************FIN NEONATOS*******************************/
			else
			{
				if(empty($this->titulo))
				{
					$this->salida= ThemeAbrirTablaSubModulo("ASISTENCIA VENTILATORIA");
				}
				else
				{
					$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
				}

				$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarAV'));
				$this->salida .= "<form name=\"Asistecia_Ventilatoria$pfj\"' action='".$href."' method='POST'>";

				/*-------------------DATOS DE LA ASISTECIA VENTILATORIA---------------------*/
				$this->salida .= "<table colspan=\"2\" width=\"100%\" align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
				$this->salida .= $this->SetStyle("MensajeError",10);
				$this->salida .= "<tr class='modulo_table_title'>\n";
				$this->salida .= "<td align='center' width=\"50%\">ASISTENCIA VENTILATORIA\n";
				$this->salida .= "</td>\n";
				$this->salida .= "<td align='center' width=\"50%\">\n";

				$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
				$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

				if(date("H:i:s") <= $hora_inicio_turno)
				{
					list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
					list($h,$m,$s)=explode(":",$hora_control);
				}
				else
				{//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
					list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
					list($h,$m,$s)=explode(":",$hora_control);
				}

				$i=0;
				$this->salida .= "<select name=\"selectHora$pfj\" class=\"select\">\n";
				for($j=0; $j<$rango_turno; $j++)
				{
					list($anno, $mes, $dia)=explode("-",$fecha_control);
					if ($i==23)
					{
						list($h,$m,$s)=explode(":",$hora_inicio_turno);
						$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
						$fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
						$fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
					}
					else
					{
						list($h,$m,$s)=explode(":",$hora_inicio_turno);
						$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
						$fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
						$fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
					}
					if(empty($selectHora)){
						if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
					}
					else
					{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
						list($A,$B) = explode(" ",$selectHora);
						if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
					}
					#################################################
					list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
					if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
						$show = "Hoy a las";
					}
					elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
						$show = "Mañana a las";
					}
					elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
						$show = "Ayer a las";
					}
					else{
						$show = $fecha_control;
					}
					###########################
					$this->salida .="<option value='".$fecha_control." ".$i."' $selected>".$show." ".$i."</option>\n";
				}//fin for

					$this->salida .= "<option value='".date("Y-m-d H")."'selected='true'>Hoy a las ".date("H")."</option>\n";
					$this->salida .= "</select>:&nbsp;\n";
					$this->salida .= "<select name='selectMinutos$pfj' class='select'>\n";

				for($j=0; $j<=59; $j++)
				{
					if(empty($selectMinutos)){
						if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
					}
					else
					{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
						list($A,$B) = explode(" ",$selectMinutos);
						if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
					}
					if ($j<10){
						$this->salida .= "<option value='0$j:00' $selected>0$j</option>\n";
					}
						else{
						$this->salida .= "<option value='$j:00' $selected>$j</option>\n";
					}
				}
				$this->salida .= "</select>\n";
				$this->salida .= "</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n";

				/*------------- CAMPOS DE INSERCION ASISTENCIA VENTILATORIA ---------*/

				$this->salida .= "<table align=\"center\"  width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\" align='center'>\n";
				$this->salida .= "<td>MODO</td>\n";
				$this->salida .= "<td>FIO<sub>2</sub></td>\n";
				$this->salida .= "<td>F. RESP</td>\n";
				$this->salida .= "<td>F. VENT</td>\n";
				$this->salida .= "<td>ESPONT</td>\n";
				$this->salida .= "<td>VOL/MIN</td>\n";
				$this->salida .= "<td>SEN</td>\n";
				$this->salida .= "<td>P. INSP</td>\n";

				$this->salida .= "</tr>\n";
				$this->salida .= "<tr ".$this->Lista(1)."' align='center'>\n";
				$modos=$this->GetAsistenciaVentilatoriaModos();
				if (!empty($modos)) {
					$this->salida .= "<td><select name='modo$pfj' class='select'>";
					$this->SetOptionsAsistenciaVentilatoriaModos($modos,'');
					$this->salida .="<option value='-1'>- - - -</option>";
					$this->salida .= "</select></td>\n";
				}
				else {
					$this->error = "Error al consultar la tabla \"hc_asistencia_ventilatoria_modos\"<br>";
					$this->mensajeDeError = "";
					return false;
				}

				$f102=$this->GetControlOxiConcentraciones('',1);
				if (!empty($f102))
				{
					$this->salida .= "<td align='center'>\n";
					$concentracion = $this->GetControlOxiConcentraciones($_REQUEST['f102'.$pfj],1);
					$this->salida .= "<select name='f102$pfj' class='select'>\n";
					$this->salida .= "					".$concentracion;
					$this->salida .= "</select>\n";
					$this->salida .= "</td>\n";
				}
				else {
					$this->error = "Error al consultar la tabla \"hc_asistencia_ventilatoria_f102\"<br>";
					$this->mensajeDeError = "";
					return false;
				}
				$this->salida .= "<td><input type='text' class='input-text' name='fr_respiratoria$pfj' value='".$_REQUEST['fr_respiratoria'.$pfj]."' size='5' maxlength='5'> X min</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='fr_ventilatoria$pfj' value='".$_REQUEST['fr_ventilatoria'.$pfj]."' size='6' maxlength='6'> X min</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='expontanea$pfj' value='".$_REQUEST['expontanea'.$pfj]."' size='6' maxlength='6'> X min</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='volumen$pfj' value='".$_REQUEST['volumen'.$pfj]."' size='6' maxlength='6'> X min</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='sens$pfj' value='".$_REQUEST['sens'.$pfj]."' size='5' maxlength='5'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='p_insp$pfj' value='".$_REQUEST['p_insp'.$pfj]."' size='6' maxlength='6'> cm</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\" align='center'>\n";
				$this->salida .= "<td>REL I:E</td>\n";
				$this->salida .= "<td>PEEP</td>\n";
				$this->salida .= "<td>PI PICO</td>\n";
				$this->salida .= "<td>PI MESETA</td>\n";
				$this->salida .= "<td>PI MEDIA</td>\n";
				$this->salida .= "<td>ETC O<sub>2</sub></td>\n";
				$this->salida .= "<td colspan=\"2\">TI</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "<tr ".$this->Lista(0)."' align='center'>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='i_e$pfj' value='".$_REQUEST['i_e'.$pfj]."' size='10' maxlength='10'></td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='peep$pfj' value='".$_REQUEST['peep'.$pfj]."' size='5' maxlength='5'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='pip$pfj' value='".$_REQUEST['pip'.$pfj]."' size='5' maxlength='5'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='pp$pfj' value='".$_REQUEST['pp'.$pfj]."' size='6' maxlength='7'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='pm$pfj' value='".$_REQUEST['pm'.$pfj]."' size='6' maxlength='7'> cmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td><input type='text' class='input-text' name='etco2$pfj' value='".$_REQUEST['etco2'.$pfj]."' size='6' maxlength='7'> mmH<sub>2</sub>O</td>\n";
				$this->salida .= "<td colspan=\"2\"><input type='text' class='input-text' name='ti$pfj' value='".$_REQUEST['ti'.$pfj]."' size='6' maxlength='6'> Seg</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n\n";
				$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='Save' value='INSERTAR'>";
				$this->salida .= "</form>\n";
				if (!empty($vectorAsistencia))
				{
					$this->ShowAsistenciaVentilatoria();
				}
				else
				{
					$this->ShowAsistenciaVentilatoria();
					$this->salida .= ThemeCerrarTablaSubModulo();
				}
			}
			return true;
		}


		/*
		*		SetOptionsAsistenciaVentilatoriaModos
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function SetOptionsAsistenciaVentilatoriaModos($modo,$valor)
		{
			$pfj=$this->frmPrefijo;
			for($i=0; $i<sizeof($modo); $i++) {
				if ($modo[$i]['modo_id']==$valor)
					$this->salida .= "<option value='".$modo[$i]['modo_id']."' selected>".$modo[$i]['descripcion']."</option>\n";
				else
					$this->salida .= "<option value='".$modo[$i]['modo_id']."'>".$modo[$i]['descripcion']."</option>\n";
			}
			return true;
		}


		function Lista($numero)
		{
			$pfj=$this->frmPrefijo;
			if ($numero%2)
				return ("class='modulo_list_oscuro'");
			return ("class='modulo_list_claro'");
		}//End lISTA



		/*
		*		ShowAsistenciaVentilatoria
		*
		*		Muestra los registros de asistencia ventilatoria del paciente x
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ShowAsistenciaVentilatoria()
		{
			$pfj=$this->frmPrefijo;
			$vectorAsistencia = $this->GetAsistenciaVentilatoria();
			$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarAV'));
			$this->salida.= "<form name=\"formaav$pfj\" action=\"$accionI\" method=\"post\">";
			if(!$vectorAsistencia)
			{
				return false;
			}
			elseif($vectorAsistencia != "ShowMensaje")
			{
				if (empty($contador))
				$contador=sizeof($vectorAsistencia);
				$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "<td>FECHA</td>\n";
				$this->salida .= "<td>HORA</td>\n";
				$this->salida .= "<td>MODO</td>\n";
				$this->salida .= "<td>FIO<sub>2</sub></td>\n";
				$this->salida .= "<td>F. RESP</td>\n";
				$this->salida .= "<td>F. VENT</td>\n";
				$this->salida .= "<td>ESPONT</td>\n";
				$this->salida .= "<td>VOL/MIN</td>\n";
				$this->salida .= "<td>SENS</td>\n";
				$this->salida .= "<td>P. INSP</td>\n";
				$this->salida .= "<td>TI</td>\n";
				$this->salida .= "<td>REL I:E</td>\n";
				$this->salida .= "<td>PEEP</td>\n";
				$this->salida .= "<td>P PI</td>\n";
				$this->salida .= "<td>P MES</td>\n";
				$this->salida .= "<td>PI MED</td>\n";
				$this->salida .= "<td>ETCO<sub>2</sub></td>\n";
				$this->salida .= "<td>USUARIO</td>\n";
				$this->salida .= "</tr>\n";

				$cont=1;
				//while ($cont<$contador && $data= $resultado->FetchNextObject($toUpper=false))
				while ($cont <= sizeof($vectorAsistencia) && $cont <= $contador)
				{
					list($date,$time) = explode(" ",$vectorAsistencia[$cont-1][fecha]);
					$this->salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
					if($date == date("Y-m-d"))
					{
						$fecha = "HOY";
					}
					elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
					{
						$fecha = "AYER ";
					}
					else
					{
						$fecha = $date;
					}
					//---- validar que si los datos esten vacios ponga "--";
					if($vectorAsistencia[$cont-1][fr_respiratoria] != 0) $fr = number_format($vectorAsistencia[$cont-1][fr_respiratoria],0,',','.'); else $fr = "--";
					if($vectorAsistencia[$cont-1][fr_ventilatoria] != 0) $fv = number_format($vectorAsistencia[$cont-1][fr_ventilatoria],1,',','.'); else $fv = "--";
					if(!empty($vectorAsistencia[$cont-1][descripcion_f]))  $fi02 = $vectorAsistencia[$cont-1][descripcion_f]; else $fi02 = "--";
					if($vectorAsistencia[$cont-1][expontanea] != 0) $ex = number_format($vectorAsistencia[$cont-1][expontanea],1,',','.'); else $ex = "--";
					if($vectorAsistencia[$cont-1][volumen] != 0) $vol = number_format($vectorAsistencia[$cont-1][volumen],1,',','.'); else $vol = "--";
					if($vectorAsistencia[$cont-1][sens] != 0) $sens = number_format($vectorAsistencia[$cont-1][sens],0,',','.'); else $sens = "--";
					if($vectorAsistencia[$cont-1][p_insp] != 0) $p_insp = number_format($vectorAsistencia[$cont-1][p_insp],1,',','.'); else $p_insp = "--";
					if($vectorAsistencia[$cont-1][ti] != 0) $ti = number_format($vectorAsistencia[$cont-1][ti],1,',','.'); else $ti = "--";
					if(!empty($vectorAsistencia[$cont-1][i_e])) $ie = $vectorAsistencia[$cont-1][i_e]; else $ie = "--";
					if($vectorAsistencia[$cont-1][peep] != 0) $peep = number_format($vectorAsistencia[$cont-1][peep],0,',','.'); else $peep = "--";
					if($vectorAsistencia[$cont-1][pip] != 0) $pip = number_format($vectorAsistencia[$cont-1][pip],0,',','.'); else $pip = "--";
					if($vectorAsistencia[$cont-1][pp] != 0) $pp = number_format($vectorAsistencia[$cont-1][pp],0,',','.'); else $pp = "--";
					if($vectorAsistencia[$cont-1][pm] != 0) $pm = number_format($vectorAsistencia[$cont-1][pm],0,',','.'); else $pm = "--";
					if($vectorAsistencia[$cont-1][etco2] != 0) $etco2 = number_format($vectorAsistencia[$cont-1][etco2],0,',','.'); else $etco2 = "--";

					$this->salida .= "<td>".$fecha."</td>\n";
					$this->salida .= "<td>".$time."</td>\n";
					$this->salida .= "<td>".$vectorAsistencia[$cont-1][descripcion]."</td>\n";
					$this->salida .= "<td>".$fi02."</td>\n";
					$this->salida .= "<td>".$fr."</td>\n";
					$this->salida .= "<td>".$fv."</td>\n";
					$this->salida .= "<td>".$ex."</td>\n";
					$this->salida .= "<td>".$vol."</td>\n";
					$this->salida .= "<td>".$sens."</td>\n";
					$this->salida .= "<td>".$p_insp."</td>\n";
					$this->salida .= "<td>".$ti."</td>\n";
					$this->salida .= "<td>".$ie."</td>\n";
					$this->salida .= "<td>".$peep."</td>\n";
					$this->salida .= "<td>".$pip."</td>\n";
					$this->salida .= "<td>".$pp."</td>\n";
					$this->salida .= "<td>".$pm."</td>\n";
					$this->salida .= "<td>".$etco2."</td>\n";
					$fechareg =$vectorAsistencia[$cont-1][fecha_registro];
					$user=$this->GetDatosUsuarioSistema($vectorAsistencia[$cont-1][usuario_id]);
					if ($vectorAsistencia[$cont-1][usuario_id] == UserGetUID() AND $vectorAsistencia[$cont-1][evolucion_id] == $this->evolucion)
					{
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'BorrarAV', 'fechar'.$pfj=>$fechareg));
						$this->salida .= "<td><a href='".$accion."'>ELIMINAR</a></td>\n";
					}
					else
					{
						$this->salida .= "<td>".$user[0][usuario]."</td>\n";
					}

					$this->salida .= "</tr>\n";
					$cont++;
				}
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n\n";
				//Mostrar Barra de Navegacion
				$vectorAsistencia=$this->RetornarBarra_Paginadora();
				if($vectorAsistencia)
				{
					$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"100%\" align=\"center\">";
					$this->salida .=$vectorAsistencia;
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  </table><br>";
					$this->salida .= "  </form>";
				}
			}
			return true;
		}//ShowAsistenciaVentilatoria



		/*
		*		ShowAsistenciaVentilatoriaNeonatos
		*
		*		Muestra los registros de asistencia ventilatoria de pacientes en unidad NEONATOS
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ShowAsistenciaVentilatoriaNeonatos()
		{
			$pfj=$this->frmPrefijo;
			$vectorAsistencia = $this->GetAsistenciaVentilatoria();
			$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarAV'));
			$this->salida.= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
			if(!$vectorAsistencia)
			{
				return false;
			}
			elseif($vectorAsistencia != "ShowMensaje")
			{
				if (empty($contador))
				$contador=sizeof($vectorAsistencia);
				$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "<td>FECHA</td>\n";
				$this->salida .= "<td>HORA</td>\n";
				$this->salida .= "<td>MODO</td>\n";
				$this->salida .= "<td>FIO<sub>2</sub></td>\n";
				$this->salida .= "<td>F. RESP</td>\n";
				$this->salida .= "<td>F. VENT</td>\n";
				$this->salida .= "<td>ESPONT</td>\n";
				$this->salida .= "<td>TI</td>\n";
				$this->salida .= "<td>REL I:E</td>\n";
				$this->salida .= "<td>PEEP</td>\n";
				$this->salida .= "<td>P PICO</td>\n";
				$this->salida .= "<td>P MESE</td>\n";
				$this->salida .= "<td>PI MED</td>\n";
				$this->salida .= "<td>PAW</td>\n";
				$this->salida .= "<td>To. VIA A</td>\n";
				$this->salida .= "<td>ETCO<sub>2</sub></td>\n";
				$this->salida .= "<td>USUARIO</td>\n";
				$this->salida .= "</tr>\n";

				$cont=1;
				//while ($cont<$contador && $data= $resultado->FetchNextObject($toUpper=false))
				while ($cont <= sizeof($vectorAsistencia) && $cont <= $contador)
				{//echo "<br>".$vectorAsistencia[$cont-1][fecha];
					list($date,$time) = explode(" ",$vectorAsistencia[$cont-1][fecha]);
					$this->salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
					if($date == date("Y-m-d"))
					{
						$fecha = "HOY";
					}
					elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
					{
						$fecha = "AYER ";
					}
					else
					{
						$fecha = $date;
					}
					//---- validar que si los datos esten vacios ponga "--";
					if($vectorAsistencia[$cont-1][fr_respiratoria] != 0) $fr = number_format($vectorAsistencia[$cont-1][fr_respiratoria],0,',','.'); else $fr = "--";
					if($vectorAsistencia[$cont-1][fr_ventilatoria] != 0) $fv = number_format($vectorAsistencia[$cont-1][fr_ventilatoria],1,',','.'); else $fv = "--";
					if(!empty($vectorAsistencia[$cont-1][descripcion_f]))  $fi02 = $vectorAsistencia[$cont-1][descripcion_f]; else $fi02 = "--";
					if($vectorAsistencia[$cont-1][expontanea] != 0) $ex = number_format($vectorAsistencia[$cont-1][expontanea],1,',','.'); else $ex = "--";
					if($vectorAsistencia[$cont-1][ti] != 0) $ti = number_format($vectorAsistencia[$cont-1][ti],1,',','.'); else $ti = "--";
					if(!empty($vectorAsistencia[$cont-1][i_e])) $ie = $vectorAsistencia[$cont-1][i_e]; else $ie = "--";
					if($vectorAsistencia[$cont-1][peep] != 0) $peep = number_format($vectorAsistencia[$cont-1][peep],0,',','.'); else $peep = "--";
					if($vectorAsistencia[$cont-1][pip] != 0) $pip = number_format($vectorAsistencia[$cont-1][pip],0,',','.'); else $pip = "--";
					if($vectorAsistencia[$cont-1][paw] != 0) $paw = number_format($vectorAsistencia[$cont-1][paw],1,',','.'); else $paw = "--";
					if($vectorAsistencia[$cont-1][t_via_a] != 0) $t_via_a = number_format($vectorAsistencia[$cont-1][t_via_a],1,',','.'); else $t_via_a = "--";
					if($vectorAsistencia[$cont-1][pp] != 0) $pp = number_format($vectorAsistencia[$cont-1][pp],0,',','.'); else $pp = "--";
					if($vectorAsistencia[$cont-1][pm] != 0) $pm = number_format($vectorAsistencia[$cont-1][pm],0,',','.'); else $pm = "--";
					if($vectorAsistencia[$cont-1][etco2] != 0) $etco2 = number_format($vectorAsistencia[$cont-1][etco2],0,',','.'); else $etco2 = "--";

					$this->salida .= "<td>".$fecha."</td>\n";
					$this->salida .= "<td>".$time."</td>\n";
					$this->salida .= "<td>".$vectorAsistencia[$cont-1][descripcion]."</td>\n";
					$this->salida .= "<td>".$fi02."</td>\n";
					$this->salida .= "<td>".$fr."</td>\n";
					$this->salida .= "<td>".$fv."</td>\n";
					$this->salida .= "<td>".$ex."</td>\n";
					$this->salida .= "<td>".$ti."</td>\n";
					$this->salida .= "<td>".$ie."</td>\n";
					$this->salida .= "<td>".$peep."</td>\n";
					$this->salida .= "<td>".$pip."</td>\n";
					$this->salida .= "<td>".$pp."</td>\n";
					$this->salida .= "<td>".$pm."</td>\n";
					$this->salida .= "<td>".$paw."</td>\n";
					$this->salida .= "<td>".$t_via_a."</td>\n";
					$this->salida .= "<td>".$etco2."</td>\n";
					$fechareg =$vectorAsistencia[$cont-1][fecha_registro];
					$user=$this->GetDatosUsuarioSistema($vectorAsistencia[$cont-1][usuario_id]);
					if ($vectorAsistencia[$cont-1][usuario_id] == UserGetUID() AND $vectorAsistencia[$cont-1][evolucion_id] == $this->evolucion)
					{
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'BorrarAV', 'fechar'.$pfj=>$fechareg));
						$this->salida .= "<td><a href='".$accion."'>ELIMINAR</a></td>\n";
					}
					else
					{
						$this->salida .= "<td>".$user[0][usuario]."</td>\n";
					}

					$this->salida .= "</tr>\n";
					$cont++;
				}
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n\n";
				//Mostrar Barra de Navegacion
				$vectorAsistencia=$this->RetornarBarra_Paginadora();
				if($vectorAsistencia)
				{
					$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"100%\" align=\"center\">";
					$this->salida .=$vectorAsistencia;
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  </table><br>";
				}
			}
			return true;
	}//ShowAsistenciaVentilatoriaNeonatos


	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
		if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
		{
			$vectorAsis = $this->GetAsistenciaVentilatoriaGeneral();
			$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarAV'));
			$this->salida.= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
			if(!$vectorAsis)
			{
				return false;
			}
			elseif($vectorAsis != "ShowMensaje")
			{
				if (empty($contador))
				$contador=sizeof($vectorAsis);
				$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"hc_table_submodulo_list\">\n";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"17\" class=\"modulo_table_list_title\" align=\"center\">LISTADOS GENERALES DE ASISTENCIA VENTILATORIA";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr class=\"hc_table_submodulo_list_title\" align=\"center\">\n";
				$this->salida .= "<td>FECHA</td>\n";
				$this->salida .= "<td>HORA</td>\n";
				$this->salida .= "<td>MODO</td>\n";
				$this->salida .= "<td>FIO<sub>2</sub></td>\n";
				$this->salida .= "<td>F. RESP</td>\n";
				$this->salida .= "<td>F. VENT</td>\n";
				$this->salida .= "<td>ESPONT</td>\n";
				$this->salida .= "<td>TI</td>\n";
				$this->salida .= "<td>REL I:E</td>\n";
				$this->salida .= "<td>PEEP</td>\n";
				$this->salida .= "<td>P PICO</td>\n";
				$this->salida .= "<td>P MESE</td>\n";
				$this->salida .= "<td>PI MED</td>\n";
				$this->salida .= "<td>PAW</td>\n";
				$this->salida .= "<td>To. VIA A</td>\n";
				$this->salida .= "<td>ETCO<sub>2</sub></td>\n";
				$this->salida .= "</tr>\n";
				$cont=1;

				while ($cont <= sizeof($vectorAsis) && $cont <= $contador)
				{
					list($date,$time) = explode(" ",$vectorAsis[$cont-1][fecha]);
					$this->salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
					$fecha = $date;

					//---- validar que si los datos esten vacios ponga "--";
					if($vectorAsis[$cont-1][fr_respiratoria] != 0) $fr = number_format($vectorAsis[$cont-1][fr_respiratoria],0,',','.'); else $fr = "--";
					if($vectorAsis[$cont-1][fr_ventilatoria] != 0) $fv = number_format($vectorAsis[$cont-1][fr_ventilatoria],1,',','.'); else $fv = "--";
					if(!empty($vectorAsis[$cont-1][descripcion_f]))  $fi02 = $vectorAsis[$cont-1][descripcion_f]; else $fi02 = "--";
					if($vectorAsis[$cont-1][expontanea] != 0) $ex = number_format($vectorAsis[$cont-1][expontanea],1,',','.'); else $ex = "--";
					if($vectorAsis[$cont-1][ti] != 0) $ti = number_format($vectorAsis[$cont-1][ti],1,',','.'); else $ti = "--";
					if(!empty($vectorAsis[$cont-1][i_e])) $ie = $vectorAsis[$cont-1][i_e]; else $ie = "--";
					if($vectorAsis[$cont-1][peep] != 0) $peep = number_format($vectorAsis[$cont-1][peep],0,',','.'); else $peep = "--";
					if($vectorAsis[$cont-1][pip] != 0) $pip = number_format($vectorAsis[$cont-1][pip],0,',','.'); else $pip = "--";
					if($vectorAsis[$cont-1][paw] != 0) $paw = number_format($vectorAsis[$cont-1][paw],1,',','.'); else $paw = "--";
					if($vectorAsis[$cont-1][t_via_a] != 0) $t_via_a = number_format($vectorAsis[$cont-1][t_via_a],1,',','.'); else $t_via_a = "--";
					if($vectorAsis[$cont-1][pp] != 0) $pp = number_format($vectorAsis[$cont-1][pp],0,',','.'); else $pp = "--";
					if($vectorAsis[$cont-1][pm] != 0) $pm = number_format($vectorAsis[$cont-1][pm],0,',','.'); else $pm = "--";
					if($vectorAsis[$cont-1][etco2] != 0) $etco2 = number_format($vectorAsis[$cont-1][etco2],0,',','.'); else $etco2 = "--";

					$this->salida .= "<td>".$fecha."</td>\n";
					$this->salida .= "<td>".$time."</td>\n";
					$this->salida .= "<td>".$vectorAsis[$cont-1][descripcion]."</td>\n";
					$this->salida .= "<td>".$fi02."</td>\n";
					$this->salida .= "<td>".$fr."</td>\n";
					$this->salida .= "<td>".$fv."</td>\n";
					$this->salida .= "<td>".$ex."</td>\n";
					$this->salida .= "<td>".$ti."</td>\n";
					$this->salida .= "<td>".$ie."</td>\n";
					$this->salida .= "<td>".$peep."</td>\n";
					$this->salida .= "<td>".$pip."</td>\n";
					$this->salida .= "<td>".$pp."</td>\n";
					$this->salida .= "<td>".$pm."</td>\n";
					$this->salida .= "<td>".$paw."</td>\n";
					$this->salida .= "<td>".$t_via_a."</td>\n";
					$this->salida .= "<td>".$etco2."</td>\n";
					$this->salida .= "</tr>\n";
					$cont++;
				}
				$this->salida .= "</tr>\n";
				$this->salida .= "</table><br>\n\n";
			}
		}
		else
		{
			$vectorAsis = $this->GetAsistenciaVentilatoriaGeneral();
			$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarAV'));
			$this->salida.= "<form name=\"formaav$pfj\" action=\"$accionI\" method=\"post\">";
			if(!$vectorAsis)
			{
				return false;
			}
			elseif($vectorAsis != "ShowMensaje")
			{
				if (empty($contador))
				$contador=sizeof($vectorAsis);
				$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"hc_table_submodulo_list\">\n";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"17\" class=\"modulo_table_list_title\" align=\"center\">LISTADOS GENERALES DE ASISTENCIA VENTILATORIA";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr class=\"hc_table_submodulo_list_title\" align=\"center\">\n";
				$this->salida .= "<td>FECHA</td>\n";
				$this->salida .= "<td>HORA</td>\n";
				$this->salida .= "<td>MODO</td>\n";
				$this->salida .= "<td>FIO<sub>2</sub></td>\n";
				$this->salida .= "<td>F. RESP</td>\n";
				$this->salida .= "<td>F. VENT</td>\n";
				$this->salida .= "<td>ESPONT</td>\n";
				$this->salida .= "<td>VOL/MIN</td>\n";
				$this->salida .= "<td>SENS</td>\n";
				$this->salida .= "<td>P. INSP</td>\n";
				$this->salida .= "<td>TI</td>\n";
				$this->salida .= "<td>REL I:E</td>\n";
				$this->salida .= "<td>PEEP</td>\n";
				$this->salida .= "<td>P PI</td>\n";
				$this->salida .= "<td>P MES</td>\n";
				$this->salida .= "<td>PI MED</td>\n";
				$this->salida .= "<td>ETCO<sub>2</sub></td>\n";
				$this->salida .= "</tr>\n";
				$cont=1;

				while ($cont <= sizeof($vectorAsis) && $cont <= $contador)
				{
					list($date,$time) = explode(" ",$vectorAsis[$cont-1][fecha]);
					$this->salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
					$fecha = $date;

					//---- validar que si los datos esten vacios ponga "--";
					if($vectorAsis[$cont-1][fr_respiratoria] != 0) $fr = number_format($vectorAsis[$cont-1][fr_respiratoria],0,',','.'); else $fr = "--";
					if($vectorAsis[$cont-1][fr_ventilatoria] != 0) $fv = number_format($vectorAsis[$cont-1][fr_ventilatoria],1,',','.'); else $fv = "--";
					if(!empty($vectorAsis[$cont-1][descripcion_f]))  $fi02 = $vectorAsis[$cont-1][descripcion_f]; else $fi02 = "--";
					if($vectorAsis[$cont-1][expontanea] != 0) $ex = number_format($vectorAsis[$cont-1][expontanea],1,',','.'); else $ex = "--";
					if($vectorAsis[$cont-1][volumen] != 0) $vol = number_format($vectorAsis[$cont-1][volumen],1,',','.'); else $vol = "--";
					if($vectorAsis[$cont-1][sens] != 0) $sens = number_format($vectorAsis[$cont-1][sens],0,',','.'); else $sens = "--";
					if($vectorAsis[$cont-1][p_insp] != 0) $p_insp = number_format($vectorAsis[$cont-1][p_insp],1,',','.'); else $p_insp = "--";
					if($vectorAsis[$cont-1][ti] != 0) $ti = number_format($vectorAsis[$cont-1][ti],1,',','.'); else $ti = "--";
					if(!empty($vectorAsis[$cont-1][i_e])) $ie = $vectorAsis[$cont-1][i_e]; else $ie = "--";
					if($vectorAsis[$cont-1][peep] != 0) $peep = number_format($vectorAsis[$cont-1][peep],0,',','.'); else $peep = "--";
					if($vectorAsis[$cont-1][pip] != 0) $pip = number_format($vectorAsis[$cont-1][pip],0,',','.'); else $pip = "--";
					if($vectorAsis[$cont-1][pp] != 0) $pp = number_format($vectorAsis[$cont-1][pp],0,',','.'); else $pp = "--";
					if($vectorAsis[$cont-1][pm] != 0) $pm = number_format($vectorAsis[$cont-1][pm],0,',','.'); else $pm = "--";
					if($vectorAsis[$cont-1][etco2] != 0) $etco2 = number_format($vectorAsis[$cont-1][etco2],0,',','.'); else $etco2 = "--";

					$this->salida .= "<td>".$fecha."</td>\n";
					$this->salida .= "<td>".$time."</td>\n";
					$this->salida .= "<td>".$vectorAsis[$cont-1][descripcion]."</td>\n";
					$this->salida .= "<td>".$fi02."</td>\n";
					$this->salida .= "<td>".$fr."</td>\n";
					$this->salida .= "<td>".$fv."</td>\n";
					$this->salida .= "<td>".$ex."</td>\n";
					$this->salida .= "<td>".$vol."</td>\n";
					$this->salida .= "<td>".$sens."</td>\n";
					$this->salida .= "<td>".$p_insp."</td>\n";
					$this->salida .= "<td>".$ti."</td>\n";
					$this->salida .= "<td>".$ie."</td>\n";
					$this->salida .= "<td>".$peep."</td>\n";
					$this->salida .= "<td>".$pip."</td>\n";
					$this->salida .= "<td>".$pp."</td>\n";
					$this->salida .= "<td>".$pm."</td>\n";
					$this->salida .= "<td>".$etco2."</td>\n";
					$this->salida .= "</tr>\n";
					$cont++;
				}
				$this->salida .= "</tr>\n";
				$this->salida .= "</table><br>\n\n";
			}
		}
		return true;
	}


	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
		if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
		{
			$vectorAsis = $this->GetAsistenciaVentilatoriaGeneral();
			$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarAV'));
			$salida.= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
			if(!$vectorAsis)
			{
				return false;
			}
			elseif($vectorAsis != "ShowMensaje")
			{
				if (empty($contador))
				$contador=sizeof($vectorAsis);
				$salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" >\n";
				$salida .= "<tr>";
				$salida .= "<td colspan=\"17\" class=\"hc_table_submodulo_list_title\" align=\"center\">LISTADOS GENERALES DE ASISTENCIA VENTILATORIA";
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$salida .= "<td>FECHA</td>\n";
				$salida .= "<td>HORA</td>\n";
				$salida .= "<td>MODO</td>\n";
				$salida .= "<td>FIO<sub>2</sub></td>\n";
				$salida .= "<td>F. RESP</td>\n";
				$salida .= "<td>F. VENT</td>\n";
				$salida .= "<td>ESPONT</td>\n";
				$salida .= "<td>TI</td>\n";
				$salida .= "<td>REL I:E</td>\n";
				$salida .= "<td>PEEP</td>\n";
				$salida .= "<td>P PICO</td>\n";
				$salida .= "<td>P MESE</td>\n";
				$salida .= "<td>PI MED</td>\n";
				$salida .= "<td>PAW</td>\n";
				$salida .= "<td>To. VIA A</td>\n";
				$salida .= "<td>ETCO<sub>2</sub></td>\n";
				$salida .= "</tr>\n";
				$cont=1;

				while ($cont <= sizeof($vectorAsis) && $cont <= $contador)
				{
					list($date,$time) = explode(" ",$vectorAsis[$cont-1][fecha]);
					$salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
					$fecha = $date;

					//---- validar que si los datos esten vacios ponga "--";
					if($vectorAsis[$cont-1][fr_respiratoria] != 0) $fr = number_format($vectorAsis[$cont-1][fr_respiratoria],0,',','.'); else $fr = "--";
					if($vectorAsis[$cont-1][fr_ventilatoria] != 0) $fv = number_format($vectorAsis[$cont-1][fr_ventilatoria],1,',','.'); else $fv = "--";
					if(!empty($vectorAsis[$cont-1][descripcion_f]))  $fi02 = $vectorAsis[$cont-1][descripcion_f]; else $fi02 = "--";
					if($vectorAsis[$cont-1][expontanea] != 0) $ex = number_format($vectorAsis[$cont-1][expontanea],1,',','.'); else $ex = "--";
					if($vectorAsis[$cont-1][ti] != 0) $ti = number_format($vectorAsis[$cont-1][ti],1,',','.'); else $ti = "--";
					if(!empty($vectorAsis[$cont-1][i_e])) $ie = $vectorAsis[$cont-1][i_e]; else $ie = "--";
					if($vectorAsis[$cont-1][peep] != 0) $peep = number_format($vectorAsis[$cont-1][peep],0,',','.'); else $peep = "--";
					if($vectorAsis[$cont-1][pip] != 0) $pip = number_format($vectorAsis[$cont-1][pip],0,',','.'); else $pip = "--";
					if($vectorAsis[$cont-1][paw] != 0) $paw = number_format($vectorAsis[$cont-1][paw],1,',','.'); else $paw = "--";
					if($vectorAsis[$cont-1][t_via_a] != 0) $t_via_a = number_format($vectorAsis[$cont-1][t_via_a],1,',','.'); else $t_via_a = "--";
					if($vectorAsis[$cont-1][pp] != 0) $pp = number_format($vectorAsis[$cont-1][pp],0,',','.'); else $pp = "--";
					if($vectorAsis[$cont-1][pm] != 0) $pm = number_format($vectorAsis[$cont-1][pm],0,',','.'); else $pm = "--";
					if($vectorAsis[$cont-1][etco2] != 0) $etco2 = number_format($vectorAsis[$cont-1][etco2],0,',','.'); else $etco2 = "--";

					$salida .= "<td>".$fecha."</td>\n";
					$salida .= "<td>".$time."</td>\n";
					$salida .= "<td>".$vectorAsis[$cont-1][descripcion]."</td>\n";
					$salida .= "<td>".$fi02."</td>\n";
					$salida .= "<td>".$fr."</td>\n";
					$salida .= "<td>".$fv."</td>\n";
					$salida .= "<td>".$ex."</td>\n";
					$salida .= "<td>".$ti."</td>\n";
					$salida .= "<td>".$ie."</td>\n";
					$salida .= "<td>".$peep."</td>\n";
					$salida .= "<td>".$pip."</td>\n";
					$salida .= "<td>".$pp."</td>\n";
					$salida .= "<td>".$pm."</td>\n";
					$salida .= "<td>".$paw."</td>\n";
					$salida .= "<td>".$t_via_a."</td>\n";
					$salida .= "<td>".$etco2."</td>\n";
					$salida .= "</tr>\n";
					$cont++;
				}
				$salida .= "</tr>\n";
				$salida .= "</table><br>\n\n";
			}
		}
		else
		{
			$vectorAsis = $this->GetAsistenciaVentilatoriaGeneral();
			$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarAV'));
			$salida.= "<form name=\"formaav$pfj\" action=\"$accionI\" method=\"post\">";
			if(!$vectorAsis)
			{
				return false;
			}
			elseif($vectorAsis != "ShowMensaje")
			{
				if (empty($contador))
				$contador=sizeof($vectorAsis);
				$salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" >\n";
				$salida .= "<tr>";
				$salida .= "<td colspan=\"17\" align=\"center\"class=\"hc_table_submodulo_list_title\">LISTADOS GENERALES DE ASISTENCIA VENTILATORIA";
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$salida .= "<td>FECHA</td>\n";
				$salida .= "<td>HORA</td>\n";
				$salida .= "<td>MODO</td>\n";
				$salida .= "<td>FIO<sub>2</sub></td>\n";
				$salida .= "<td>F. RESP</td>\n";
				$salida .= "<td>F. VENT</td>\n";
				$salida .= "<td>ESPONT</td>\n";
				$salida .= "<td>VOL/MIN</td>\n";
				$salida .= "<td>SENS</td>\n";
				$salida .= "<td>P. INSP</td>\n";
				$salida .= "<td>TI</td>\n";
				$salida .= "<td>REL I:E</td>\n";
				$salida .= "<td>PEEP</td>\n";
				$salida .= "<td>P PI</td>\n";
				$salida .= "<td>P MES</td>\n";
				$salida .= "<td>PI MED</td>\n";
				$salida .= "<td>ETCO<sub>2</sub></td>\n";
				$salida .= "</tr>\n";
				$cont=1;

				while ($cont <= sizeof($vectorAsis) && $cont <= $contador)
				{
					list($date,$time) = explode(" ",$vectorAsis[$cont-1][fecha]);
					$salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
					$fecha = $date;

					//---- validar que si los datos esten vacios ponga "--";
					if($vectorAsis[$cont-1][fr_respiratoria] != 0) $fr = number_format($vectorAsis[$cont-1][fr_respiratoria],0,',','.'); else $fr = "--";
					if($vectorAsis[$cont-1][fr_ventilatoria] != 0) $fv = number_format($vectorAsis[$cont-1][fr_ventilatoria],1,',','.'); else $fv = "--";
					if(!empty($vectorAsis[$cont-1][descripcion_f]))  $fi02 = $vectorAsis[$cont-1][descripcion_f]; else $fi02 = "--";
					if($vectorAsis[$cont-1][expontanea] != 0) $ex = number_format($vectorAsis[$cont-1][expontanea],1,',','.'); else $ex = "--";
					if($vectorAsis[$cont-1][volumen] != 0) $vol = number_format($vectorAsis[$cont-1][volumen],1,',','.'); else $vol = "--";
					if($vectorAsis[$cont-1][sens] != 0) $sens = number_format($vectorAsis[$cont-1][sens],0,',','.'); else $sens = "--";
					if($vectorAsis[$cont-1][p_insp] != 0) $p_insp = number_format($vectorAsis[$cont-1][p_insp],1,',','.'); else $p_insp = "--";
					if($vectorAsis[$cont-1][ti] != 0) $ti = number_format($vectorAsis[$cont-1][ti],1,',','.'); else $ti = "--";
					if(!empty($vectorAsis[$cont-1][i_e])) $ie = $vectorAsis[$cont-1][i_e]; else $ie = "--";
					if($vectorAsis[$cont-1][peep] != 0) $peep = number_format($vectorAsis[$cont-1][peep],0,',','.'); else $peep = "--";
					if($vectorAsis[$cont-1][pip] != 0) $pip = number_format($vectorAsis[$cont-1][pip],0,',','.'); else $pip = "--";
					if($vectorAsis[$cont-1][pp] != 0) $pp = number_format($vectorAsis[$cont-1][pp],0,',','.'); else $pp = "--";
					if($vectorAsis[$cont-1][pm] != 0) $pm = number_format($vectorAsis[$cont-1][pm],0,',','.'); else $pm = "--";
					if($vectorAsis[$cont-1][etco2] != 0) $etco2 = number_format($vectorAsis[$cont-1][etco2],0,',','.'); else $etco2 = "--";

					$salida .= "<td>".$fecha."</td>\n";
					$salida .= "<td>".$time."</td>\n";
					$salida .= "<td>".$vectorAsis[$cont-1][descripcion]."</td>\n";
					$salida .= "<td>".$fi02."</td>\n";
					$salida .= "<td>".$fr."</td>\n";
					$salida .= "<td>".$fv."</td>\n";
					$salida .= "<td>".$ex."</td>\n";
					$salida .= "<td>".$vol."</td>\n";
					$salida .= "<td>".$sens."</td>\n";
					$salida .= "<td>".$p_insp."</td>\n";
					$salida .= "<td>".$ti."</td>\n";
					$salida .= "<td>".$ie."</td>\n";
					$salida .= "<td>".$peep."</td>\n";
					$salida .= "<td>".$pip."</td>\n";
					$salida .= "<td>".$pp."</td>\n";
					$salida .= "<td>".$pm."</td>\n";
					$salida .= "<td>".$etco2."</td>\n";
					$salida .= "</tr>\n";
					$cont++;
				}
				$salida .= "</tr>\n";
				$salida .= "</table><br>\n\n";
			}
		}
		return $salida;
	}
}
?>
