<?php

/**
* Submodulo de Signos Vitales Hospitalizacion (HTML).
*
* Submodulo para manejar los signos vitales de un paciente hospitalizado.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_SignosVitalesHospitalizacion_HTML.php,v 1.9 2009/04/22 19:19:59 johanna Exp $
*/

/**
* SignosVitalesHospitalizacion_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo signos vitales Hospitalizacion, se extiende la clase SignosVitalesHospitalizacion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class SignosVitalesHospitalizacion_HTML extends SignosVitalesHospitalizacion
{

	function SignosVitalesHospitalizacion_HTML()
	{
	    $this->SignosVitalesHospitalizacion();//constructor del padre
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarSignosVitales','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj]));
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
		function frmForma()
		{
			$pfj=$this->frmPrefijo;
			if(empty($this->titulo))
			{
				$this->salida = ThemeAbrirTabla('SIGNOS VITALES GENERALES');
			}
			else
			{
				$this->salida  = ThemeAbrirTabla($this->titulo);
			}

			$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarSignosVitales'));
			$this->salida .= "<form name=\"signos_vitales$pfj\"' action='".$href."' method='POST'>";
			/*-------------------------------------------
				En este segemento se imprime en pantalla
				los datos relacionados al paciente.
			  -------------------------------------------
			*/

			/*$this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
			$this->salida .= "<tr class=\"modulo_table_title\">\n";
			$this->salida .= "<td align=\"center\" width=\"40%\">PACIENTE</td>\n";
			$this->salida .= "<td align=\"center\" width=\"20%\">HABITACION</td>\n";
			$this->salida .= "<td align=\"center\" width=\"20%\">CAMA</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "<td align=\"center\">".$datos_estacion[NombrePaciente]."</td>\n";
			$this->salida .= "<td align=\"center\">".$datos_estacion[pieza]."</td>\n";
			$this->salida .= "<td align=\"center\">".$datos_estacion[cama]."</td>\n";
			$this->salida .= "</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "</table><br><br>\n";

			/*-------------------------------------------
				Segemento que imprime en pantalla
				la hora en que se tomaron los Signos Vitales.
			  -------------------------------------------
			*/
			$this->salida .= "<table colspan=\"2\" align=\"center\" width=\"90%\" border=\"0\" class=\"modulo_table_list\">\n";
   			$this->salida .= $this->SetStyle("MensajeError",11);
			$this->salida .= "<tr class='modulo_table_title'>\n";
			$this->salida .= "<td align='center' width=\"50%\">TOMA DE SIGNOS VITALES\n";
			$this->salida .= "</td>\n";
			$this->salida .= "<td align='center' width=\"50%\">\n";

               //Seleccion de la Hora de la toma del Signo Vital.
               $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
               $hora_inicio_turno = "00:00:00";
               $rango_turno = date("H");
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
     
               $i = 0;
               $rangomin = $rango_turno - 24;
               $this->salida.= "<select name='selectHora$pfj' class='select'>\n";
               for($j = $rangomin; $j<=$rango_turno; $j++)
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
                    //$this->salida .= "<option value='".date("Y-m-d")." ".$i."' selected $selected>".$i."</option>\n";
                    list($yy,$mm,$dd)=explode(" ",$fecha_c);
                    if (-23<=$j AND $j<=-1){
                    $fecha_c = (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))));
                    }
                    else
                    {
                    $fecha_c = (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))));
                    }
                    $this->salida .= "<option value='".$fecha_c." ".$i."' selected $selected>".$i."</option>\n";
               }//fin for
               
               if(!empty($_REQUEST['selectHora'.$pfj]))
               {
                    $horas_R = explode(" ", $_REQUEST['selectHora'.$pfj]);
                    //$this->salida .= "<option value='".date("Y-m-d")." ".$horas_R[1]."' selected='true'>".$horas_R[1]."</option>\n";
               }
               $this->salida.= "</select>:&nbsp;\n";
               $this->salida.= "<select name='selectMinutos$pfj' class='select'>\n";
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
                         $this->salida .= "         <option value='0$j:00' $selected>0$j</option>\n";
                    }
                    else{
                         $this->salida .= "         <option value='$j:00' $selected>$j</option>\n";
                    }
               }
               $this->salida .= "</select>\n";
			$this->salida .= "</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "</table>\n";

			/*-------------------------------------------
				Segemento que imprime en pantalla
				los Signos Vitales que se tomaran al paciente.
			  -------------------------------------------
			*/
			
			$this->salida .= "<table align=\"center\" width=\"90%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "<td align=\"center\" >FREC. CARD.</td>\n";
			$this->salida .= "<td align=\"center\" >FREC. RESP.</td>\n";
			$this->salida .= "<td align=\"center\" >PVC</td>\n";
			$this->salida .= "<td align=\"center\" >PIC</td>\n";
			$this->salida .= "<td align=\"center\" >PESO</td>\n";
			$this->salida .= "<td align=\"center\">TEMP.</td>\n";
			//$this->salida .= "<td align=\"center\">T. MANUAL</td>\n";
			$this->salida .= "<td align=\"center\">T.INCUB</td>\n";
			$this->salida .= "<td align=\"center\">SAT O<sub>2</sub></td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "<tr ".$this->Lista(1).">\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='fc$pfj' value='".$_REQUEST['fc'.$pfj]."' size='6' maxlength='5'> X min</td>\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='fr$pfj' value='".$_REQUEST['fr'.$pfj]."' size='6' maxlength='5'> X min</td>\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='pvc$pfj' value='".$_REQUEST['pvc'.$pfj]."' size='6' maxlength='6'> cmH<sub>2</sub>O</td>\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='pic$pfj' value='".$_REQUEST['pic'.$pfj]."' size='6' maxlength='6'> cmH<sub>2</sub>0</td>\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='peso$pfj' value='".$_REQUEST['peso'.$pfj]."' size='6' maxlength='6'> Kg.</td>\n";
			$this->salida .= "<td align='center'><input type='text' class='input-text' name='tpiel$pfj' value='".$_REQUEST['tpiel'.$pfj]."' size='6' maxlength='5'> ºC</td>\n";
			//$this->salida .= "<td align='center'><input type='text' class='input-text' name='manual$pfj' value='".$_REQUEST['manual'.$pfj]."' size='6' maxlength='6'> ºC</td>\n";
			$this->salida .= "<td align='center'><input type='text' class='input-text' name='servo$pfj' value='".$_REQUEST['servo'.$pfj]."' size='6' maxlength='6'> ºC</td>\n";
			$this->salida .= "<td align='center'><input type='text' class='input-text' name='sato$pfj' value='".$_REQUEST['sato'.$pfj]."' size='6' maxlength='4'> %</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "</table>\n\n";

			/*-------------------------------------------
				Segemento que imprime en pantalla
				los Signos Vitales que se tomaran al paciente.
			  -------------------------------------------
			*/
			$this->salida .= "<table colspan=\"2\" align=\"center\" width=\"90%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "<tr align='center' class='modulo_table_list_title'>\n";//class=\"modulo_table_list_title\"
			$this->salida .= "<td width=\"50%\">TENSION ARTERIAL</td>\n";
			$this->salida .= "<td width=\"50%\">OBSERVACION</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "<td width=\"50%\">";
			$this->salida .= "<p align='center'>";
			$this->salida .= "<input type=\"text\" class='input-text' name=\"taa$pfj\" value='".$_REQUEST['taa'.$pfj]."' size='6' maxlength='5'>&nbsp;<b>/</b>&nbsp;<input type=\"text\" class='input-text' name=\"tab$pfj\" value='".$_REQUEST['tab'.$pfj]."' size='6' maxlength='5'>";
			$this->salida .= "<label class=\"label\">&nbsp;&nbsp;SITIO&nbsp;&nbsp;";
			$sitios=$this->GetSignosVitalesSitios();
			if (!empty($sitios)) {
				$this->salida .="<select name=\"sitio$pfj\" class='select'>";//rowspan='3'
				$this->salida .="<option value='-1'>- - - -</option>";
				$this->SetOptionsSignosVitalesSitios($sitios,$_REQUEST['sitio'.$pfj]);
				$this->salida .="</select></p>\n";
			}
			else {
				$this->error = "Error al consultar la tabla \"hc_signos_vitales_sitios\"<br>";
				$this->mensajeDeError = "";
				return false;
			}

			$this->salida .="<table colspan=\"2\" align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
			$this->salida .="<tr align=\"center\"><td colspan=\"12\" class='modulo_table_list_title'>ESCALA VISUAL ANALOGA - EVA</td></tr>";
			$this->salida .="<tr align=\"center\">";
			$this->salida .="<td rowspan=\"2\">Menor Dolor</td>";
			$FechaInicio = $this->datosPaciente[fecha_nacimiento];
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
			{
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/no_dolor.png\" border=0></td>";
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/leve.png\" border=0></td>";
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/moderado.png\" border=0></td>";
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/severopain.png\" border=0></td>";
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/muyseveropain.png\" border=0></td>";

				$this->salida .="<td rowspan=\"2\">Mayor Dolor</td>";
				$this->salida .="</tr>";
				$this->salida .="<tr align=\"center\">";

				if ($_REQUEST['eva'.$pfj] != 0 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"0\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"0\"></td>";
				}
				if ($_REQUEST['eva'.$pfj] != 1 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"1\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"1\"></td>";
				}
				if ($_REQUEST['eva'.$pfj] != 2 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"2\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"2\"></td>";
				}

				if ($_REQUEST['eva'.$pfj] != 3 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"3\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"3\"></td>";
				}

				if ($_REQUEST['eva'.$pfj] != 4 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"4\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"4\"></td>";
				}

			}
			else
			{
				$this->salida .="<td>1</td>";
				$this->salida .="<td>2</td>";
				$this->salida .="<td>3</td>";
				$this->salida .="<td>4</td>";
				$this->salida .="<td>5</td>";
				$this->salida .="<td>6</td>";
				$this->salida .="<td>7</td>";
				$this->salida .="<td>8</td>";
				$this->salida .="<td>9</td>";
				$this->salida .="<td>10</td>";

				$this->salida .="<td rowspan=\"2\">Mayor Dolor</td>";
				$this->salida .="</tr>";
				$this->salida .="<tr align=\"center\">";

				if ($_REQUEST['eva'.$pfj] != 1 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"1\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"1\"></td>";
				}
				if ($_REQUEST['eva'.$pfj] != 2 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"2\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"2\"></td>";
				}
				if ($_REQUEST['eva'.$pfj] != 3 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"3\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"3\"></td>";
				}

				if ($_REQUEST['eva'.$pfj] != 4)
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"4\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"4\"></td>";
				}

				if ($_REQUEST['eva'.$pfj] != 5 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"5\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"5\"></td>";
				}

				if ($_REQUEST['eva'.$pfj] != 6 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"6\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"6\"></td>";
				}

				if ($_REQUEST['eva'.$pfj] != 7 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"7\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"7\"></td>";
				}

				if ($_REQUEST['eva'.$pfj] != 8 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"8\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"8\"></td>";
				}

				if ($_REQUEST['eva'.$pfj] != 9 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"9\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"9\"></td>";
				}

				if ($_REQUEST['eva'.$pfj] != 10 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"10\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva$pfj\" value=\"10\"></td>";
				}
			}
/*
			$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"3\"></td>";
			$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"4\"></td>";
			$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"5\"></td>";
			$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"6\"></td>";
			$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"7\"></td>";
			$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"8\"></td>";
			$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"9\"></td>";
			$this->salida .="<td><input type=\"radio\" name=\"eva$pfj\" value=\"10\"></td>";
*/
			$this->salida .="</tr></table>";

			$this->salida .= "</td>\n";
			$this->salida .= "<td width=\"50%\" align='center'>\n";
			$this->salida .= "<textarea name=\"observacion$pfj\" cols=\"50\" rows=\"4\" class=\"textarea\">".$_REQUEST['observacion'.$pfj]."</textarea>";//TENSION ARTERIAL
			$this->salida .= "<p align=\"center\"><input type='submit' class='input-submit' name='Save$pfj' value='INSERTAR'></p>";
			$this->salida .= "</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "</table>\n\n";
			$this->salida .= "</form>\n";
			$this->salida .="<div class='label_mark' align='center'><BR>LISTADO DE SIGNOS VITALES<br>";
			if (!empty($vectorSignos))
			{
				$this->ShowSignosVitales();
			}
			else
			{
				$this->ShowSignosVitales();
				$triage = $this->Consulta_Triage();
				if(!empty($triage))
				{
					$this->ShowSignosVitales_Triage();
				}
				$this->salida .= themeCerrarTabla();
			}
			return true;
		}

		/*
		*		SetOptionsSignosVitalesSitios
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function SetOptionsSignosVitalesSitios($sitio,$valor)
		{
			$pfj=$this->frmPrefijo;
			for($i=0; $i<sizeof($sitio); $i++)
			{
				if ($sitio[$i]['sitio_id']==$valor)
					$this->salida .= "<option value='".$sitio[$i]['sitio_id']."' selected>".$sitio[$i]['descripcion']."</option>\n";
				else
					$this->salida .= "<option value='".$sitio[$i]['sitio_id']."'>".$sitio[$i]['descripcion']."</option>\n";
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
		*		ShowSignosVitales
		*
		*		Muestra los signos vitales registrados del paciente seleccionado
		*
		*		@Author Tizziano Perea O
		*		@access Private
		*		@return boolean
		*/
		function ShowSignosVitales($vectorSignos)
		{
			$pfj=$this->frmPrefijo;
			$vectorSignos = $this->ListarSignosVitales();
			/*Insercion del buscador*/
			$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarSignosVitales'));
			$this->salida.= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
			if(!$vectorSignos)
			{
				return false;
			}
			elseif($vectorSignos != "ShowMensaje")
			{
				if (empty($contador)){
					$contador=sizeof($vectorSignos);
				}
				$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "<td>FECHA</td>\n";
				$this->salida .= "<td>HORA</td>\n";
				$this->salida .= "<td>F.C.</td>\n";
				$this->salida .= "<td>F.R.</td>\n";
				$this->salida .= "<td>PVC</td>\n";
				$this->salida .= "<td>PIC</td>\n";
				$this->salida .= "<td>PESO (Kg)</td>\n";
				$this->salida .= "<td>T.A.</td>\n";
				$this->salida .= "<td>MEDIA</td>\n";
				$this->salida .= "<td>SITIO TOMA DE T.A.</td>\n";
				$this->salida .= "<td>TEMP.</td>\n";
				$this->salida .= "<td>T. INCUB</td>\n";
				$this->salida .= "<td>EVA</td>\n";
				$this->salida .= "<td>SAT O<sub>2</sub></td>\n";
				$this->salida .= "<td>USUARIO</td>\n";
				$this->salida .= "</tr>\n";

				$cont=1;
				while ($cont <= sizeof($vectorSignos) && $cont <= $contador)
				{
					list($fecha,$hora) = explode(" ",$vectorSignos[$cont-1][fecha]);//substr(,0,10);
					$this->salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
					if($fecha == date("Y-m-d")) {
						$fecha = "HOY";
					}
					elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
						$fecha = "AYER";
					}
					else {
						$fecha = $fecha;
					}
					//---------------Alerta de temperatura
					if (!IncludeLib('datospaciente')){
						$this->error = "Error al cargar la libreria [datospaciente].";
						$this->mensajeDeError = "datospaciente";
						return false;
					}
					$x = GetDatosPaciente("","",$this->ingreso);//funcion del api realizada por jaime
					$Edad = CalcularEdad($x[fecha_nacimiento],'');
					list($Edad,$k) = explode(" ",$Edad[edad_aprox]);
					//temperatura es 20;
					$k = $this->GetAlarmaRangoControl(20,$x[sexo_id],$Edad,$vectorSignos[$cont-1][temp_piel]);
					if($k === "Alarma"){$estilo = "class='alerta'";} else {$estilo = "";}
					//---------------fin Alerta de temperatura
					//------- valido si estan en ceros que pongan "--";

					if($vectorSignos[$cont-1][fc] == 0) $fc = "--"; else $fc = $vectorSignos[$cont-1][fc];
					if($vectorSignos[$cont-1][fr] == 0) $fr = "--"; else $fr = $vectorSignos[$cont-1][fr];

					$FechaInicio = $this->datosPaciente[fecha_nacimiento];
					$FechaFin = date("Y-m-d");
					$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);

					if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
					{
						if($vectorSignos[$cont-1][evaluacion_dolor] == 0) $eva = "0"; else $eva = $vectorSignos[$cont-1][evaluacion_dolor];
					}
					else
					{
						if($vectorSignos[$cont-1][evaluacion_dolor] == 0) $eva = "--"; else $eva = $vectorSignos[$cont-1][evaluacion_dolor];
					}
					if($vectorSignos[$cont-1][pvc] == 0.00) $pvc = "--"; else $pvc = $vectorSignos[$cont-1][pvc];

					if($vectorSignos[$cont-1][ta_alta] == 0.00)
					{$taa = "--";}
					else {$ta_alta = $vectorSignos[$cont-1][ta_alta];}

					if($vectorSignos[$cont-1][ta_baja] == 0.00)
					{$taa = "--";}
					else {$ta_baja = $vectorSignos[$cont-1][ta_baja];}

					if($ta_alta AND $ta_baja)
					{$taa=$ta_alta." / ".$ta_baja;}


					if($vectorSignos[$cont-1][media] == 0) $media = "--"; else $media = $vectorSignos[$cont-1][media];
					if($vectorSignos[$cont-1][sato2] == 0) $sato = "--"; else $sato = $vectorSignos[$cont-1][sato2];
					if($vectorSignos[$cont-1][temp_piel] == 0) $temp = "--"; else $temp = $vectorSignos[$cont-1][temp_piel];
					if($vectorSignos[$cont-1][servo] == 0.00) $servo = "--"; else $servo = $vectorSignos[$cont-1][servo];
					if($vectorSignos[$cont-1][manual] == 0.00) $manual = "--"; else $manual = $vectorSignos[$cont-1][manual];
					if($vectorSignos[$cont-1][presion_intracraneana] == 0) $presion = "--"; else $presion = $vectorSignos[$cont-1][presion_intracraneana];
					if($vectorSignos[$cont-1][peso] == 0.000) $peso = "--"; else $peso = number_format($vectorSignos[$cont-1][peso],2,',','.');
					if($vectorSignos[$cont-1][sitio_id]=='' OR is_null($vectorSignos[$cont-1][sitio_id])){$sit='--';}else{$sit=$vectorSignos[$cont-1][sitio_id];}

					if($sit <> '' and $sit <> '--')
					{
						$sitio=$this->GetSignosVitalesSitios($sit);
					}

					//-------fin valido si estan en ceros que pongan "--";
					$this->salida .= "<td>".$fecha."</td>\n";
					$this->salida .= "<td>".$hora."</td>\n";
					$this->salida .= "<td>".$fc."</td>\n";
					$this->salida .= "<td>".$fr."</td>\n";
					$this->salida .= "<td>".$pvc."</td>\n";
					$this->salida .= "<td>".$presion."</td>\n";
					$this->salida .= "<td>".$peso."</td>\n";
					$this->salida .= "<td>".$taa."</td>\n";
					$this->salida .= "<td>".$media."</td>\n";
					$this->salida .= "<td>".$sitio[0][descripcion]."</td>\n";
					$this->salida .= "<td $estilo>".$temp."</td>\n";
					$this->salida .= "<td>".$servo."</td>\n";
					$this->salida .= "<td>".$eva."</td>\n";
					$this->salida .= "<td>".$sato."</td>\n";
					$fechareg =$vectorSignos[$cont-1][fecha_registro];
					$user=$this->GetDatosUsuarioSistema($vectorSignos[$cont-1][usuario_id]);
					if ($vectorSignos[$cont-1][usuario_id] == UserGetUID() AND $vectorSignos[$cont-1][evolucion_id] == $this->evolucion)
					{
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'BorrarSignoVital', 'fechar'.$pfj=>$fechareg));
						$this->salida .= "<td><a href='".$accion."'>ELIMINAR</a></td>\n";
					}
					else
					{
						$this->salida .= "<td>".$user[0][usuario]."</td>\n";
					}
					$this->salida .= "</tr>\n";
					if($vectorSignos[$cont-1][observacion] !='' AND $vectorSignos[$cont-1][observacion] != 'NULL')// OR $vectorSignos[$cont-1][observacion]='--'  OR is_null($vectorSignos[$cont-1][observacion]))
					{
						$observacion = $vectorSignos[$cont-1][observacion];
						$this->salida .= "<tr ".$this->Lista($cont)."'>\n";
						$this->salida .= "<td class=\"modulo_table_title\">OBSERVACION</td>\n";
						$this->salida .= "<td colspan=\"15\">".$observacion."</td>\n";
						$this->salida .= "</tr>\n";
					}
					unset($observacion);
					$cont++;
				}

				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n\n";
				//Mostrar Barra de Navegacion
				$vectorSignos=$this->RetornarBarra_Paginadora();
				if($vectorSignos)
				{
					$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"100%\" align=\"center\">";
					$this->salida .=$vectorSignos;
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  </table><br>";
				}
				$this->salida .= "</form>";
			}//fin del buscador
		return true;
	}//ShowSignosVitales


		/*
		*		frmConsulta
		*
		*		Muestra el resumen de los signos vitales registrados del paciente seleccionado
		*
		*		@Author Tizziano Perea O
		*		@access Private
		*		@return boolean
		*/
		function frmConsulta()
		{
			$pfj=$this->frmPrefijo;
			$vectorSig = $this->ListarSignos();

			if(!$vectorSig)
			{
				return false;
			}
			elseif($vectorSig != "ShowMensaje")
			{
				if (empty($contador)){
					$contador=sizeof($vectorSig);
				}
				$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"hc_table_submodulo_list\">\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "<td colspan=\"15\">LISTADO DE SIGNOS VITALES</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "<tr class=\"hc_table_submodulo_list_title\">\n";
				$this->salida .= "<td>FECHA</td>\n";
				$this->salida .= "<td>HORA</td>\n";
				$this->salida .= "<td>F.C.</td>\n";
				$this->salida .= "<td>F.R.</td>\n";
				$this->salida .= "<td>PVC</td>\n";
				$this->salida .= "<td>PIC</td>\n";
				$this->salida .= "<td>PESO (Kg)</td>\n";
				$this->salida .= "<td>T.A.</td>\n";
				$this->salida .= "<td>MEDIA</td>\n";
				$this->salida .= "<td>SITIO TOMA DE T.A.</td>\n";
				$this->salida .= "<td>TEMP.</td>\n";
				$this->salida .= "<td>T. INCUB</td>\n";
				$this->salida .= "<td>MANUAL</td>\n";
				$this->salida .= "<td>EVA</td>\n";
				$this->salida .= "<td>SAT O<sub>2</sub></td>\n";
				$this->salida .= "</tr>\n";

				$cont=1;
				while ($cont <= sizeof($vectorSig) && $cont <= $contador)
				{
					list($fecha,$hora) = explode(" ",$vectorSig[$cont-1][fecha]);//substr(,0,10);
					$this->salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
					$fecha = $fecha;
					//}
					//---------------Alerta de temperatura
					if (!IncludeLib('datospaciente')){
						$this->error = "Error al cargar la libreria [datospaciente].";
						$this->mensajeDeError = "datospaciente";
						return false;
					}
					$x = GetDatosPaciente("","",$this->ingreso);//funcion del api realizada por jaime
					$Edad = CalcularEdad($x[fecha_nacimiento],'');
					list($Edad,$k) = explode(" ",$Edad[edad_aprox]);
					//temperatura es 20;
					$k = $this->GetAlarmaRangoControl(20,$x[sexo_id],$Edad,$vectorSig[$cont-1][temp_piel]);
					if($k === "Alarma"){$estilo = "class='alerta'";} else {$estilo = "";}
					//---------------fin Alerta de temperatura
					//------- valido si estan en ceros que pongan "--";

					if($vectorSig[$cont-1][fc] == 0) $fc = "--"; else $fc = $vectorSig[$cont-1][fc];
					if($vectorSig[$cont-1][fr] == 0) $fr = "--"; else $fr = $vectorSig[$cont-1][fr];

					$FechaInicio = $this->datosPaciente[fecha_nacimiento];
					$FechaFin = date("Y-m-d");
					$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);

					if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
					{
						if($vectorSig[$cont-1][evaluacion_dolor] == 0) $eva = "0"; else $eva = $vectorSig[$cont-1][evaluacion_dolor];
					}
					else
					{
						if($vectorSig[$cont-1][evaluacion_dolor] == 0) $eva = "--"; else $eva = $vectorSig[$cont-1][evaluacion_dolor];
					}
					if($vectorSig[$cont-1][pvc] == 0.00) $pvc = "--"; else $pvc = $vectorSig[$cont-1][pvc];

					if($vectorSig[$cont-1][ta_alta] == 0.00)
					{$taa = "--";}
					else {$ta_alta = $vectorSig[$cont-1][ta_alta];}

					if($vectorSig[$cont-1][ta_baja] == 0.00)
					{$taa = "--";}
					else {$ta_baja = $vectorSig[$cont-1][ta_baja];}

					if($ta_alta AND $ta_baja)
					{$taa=$ta_alta." / ".$ta_baja;}


					if($vectorSig[$cont-1][media] == 0) $media = "--"; else $media = $vectorSig[$cont-1][media];
					if($vectorSig[$cont-1][sato2] == 0) $sato = "--"; else $sato = $vectorSig[$cont-1][sato2];
					if($vectorSig[$cont-1][temp_piel] == 0) $temp = "--"; else $temp = $vectorSig[$cont-1][temp_piel];
					if($vectorSig[$cont-1][servo] == 0.00) $servo = "--"; else $servo = $vectorSig[$cont-1][servo];
					if($vectorSig[$cont-1][manual] == 0.00) $manual = "--"; else $manual = $vectorSig[$cont-1][manual];
					if($vectorSig[$cont-1][presion_intracraneana] == 0) $presion = "--"; else $presion = $vectorSig[$cont-1][presion_intracraneana];
					if($vectorSig[$cont-1][peso] == 0.000) $peso = "--"; else $peso = number_format($vectorSig[$cont-1][peso],2,',','.');
					if($vectorSig[$cont-1][sitio_id]=='' OR is_null($vectorSig[$cont-1][sitio_id])){$sit='--';}else{$sit=$vectorSig[$cont-1][sitio_id];}

					if($sit <> '' and $sit <> '--')
					{
						$sitio=$this->GetSignosVitalesSitios($sit);
					}

					//-------fin valido si estan en ceros que pongan "--";
					$this->salida .= "<td>".$fecha."</td>\n";
					$this->salida .= "<td>".$hora."</td>\n";
					$this->salida .= "<td>".$fc."</td>\n";
					$this->salida .= "<td>".$fr."</td>\n";
					$this->salida .= "<td>".$pvc."</td>\n";
					$this->salida .= "<td>".$presion."</td>\n";
					$this->salida .= "<td>".$peso."</td>\n";
					$this->salida .= "<td>".$taa."</td>\n";
					$this->salida .= "<td>".$media."</td>\n";
					$this->salida .= "<td>".$sitio[0][descripcion]."</td>\n";
					$this->salida .= "<td $estilo>".$temp."</td>\n";
					$this->salida .= "<td>".$servo."</td>\n";
					$this->salida .= "<td>".$manual."</td>\n";
					$this->salida .= "<td>".$eva."</td>\n";
					$this->salida .= "<td>".$sato."</td>\n";
					$this->salida .= "</tr>\n";
					if($vectorSig[$cont-1][observacion] !='' AND $vectorSig[$cont-1][observacion] != 'NULL')// OR $vectorSig[$cont-1][observacion]='--'  OR is_null($vectorSig[$cont-1][observacion]))
					{
						$observacion = $vectorSig[$cont-1][observacion];
						$this->salida .= "<tr ".$this->Lista($cont)."'>\n";
						$this->salida .= "<td class=\"modulo_table_title\">OBSERVACION</td>\n";
						$this->salida .= "<td colspan=\"14\">".$observacion."</td>\n";
						$this->salida .= "</tr>\n";
					}
					unset($observacion);
					$cont++;
				}

				$this->salida .= "</tr>\n";
				$this->salida .= "</table><br>\n\n";
			}
			return true;
		}//frmConsulta


		function PartirFecha($fecha)
		{
			$a=explode('-',$fecha);
			$b=explode(' ',$a[2]);
			$c=explode(':',$b[1]);
			$d=explode('.',$c[2]);
			return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
		}


		function ShowSignosVitales_Triage()
		{
			$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"hc_table_submodulo_list\">\n";
			IncludeLib("funciones_admision");
			$this->salida .= "<tr>";
			$this->salida .= "<td colspan=\"8\" align=\"center\" class=\"modulo_table_list_title\">SIGNOS VITALES TRIAGE</td>";
			$this->salida .= "</tr>";

			$sig=BuscarSignosVitalesTriage($this->Consulta_Triage());
			$user=$this->GetDatosUsuarioSistema($sig[usuario_id]);

			list($fecha,$hora) = explode(" ",$this->PartirFecha($sig[fecha]));
			list($ano,$mes,$dia) = explode("-",$fecha);
			list($hora,$min) = explode(":",$hora);

			foreach($user as $kk=>$vv)
			{
				$nombrePro = $vv[nombre];
			}
			$this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .= "<td colspan=\"2\" align=\"justify\" class=\"modulo_table_list_title\">HORA Y FECHA DE REGISTRO</td>";
			$this->salida .= "<td colspan=\"1\" align=\"center\">".$fecha." - ".$hora.":".$min."</td>";
			$this->salida .= "<td colspan=\"2\" align=\"justify\" class=\"modulo_table_list_title\">USUARIO QUE REGISTRO</td>";
			$this->salida .= "<td colspan=\"3\" align=\"center\">".$nombrePro."</td>";
			$this->salida .= "</tr>";

			$glas=$sig[respuesta_motora_id] + $sig[respuesta_verbal_id]+ $sig[apertura_ocular_id];
			if(empty($glas)){   $glas='--';  }

			$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .= "<td>F.C.</td>";
			$this->salida .= "<td>F.R.</td>";
			$this->salida .= "<td>PESO(Kg)</td>";
			$this->salida .= "<td>TENSION ARTERIAL</td>";
			$this->salida .= "<td>TEMP.</td>";
			$this->salida .= "<td>EVA.</td>";
			$this->salida .= "<td>SAT O<sub>2</sub></td>";
			$this->salida .= "<td>GLASGOW</td>";
			$this->salida .= "</tr>";

			$this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .= "<td width=\"10%\">".$sig[signos_vitales_fc]."</td>";
			$this->salida .= "<td width=\"10%\">".$sig[signos_vitales_fr]."</td>";
			$this->salida .= "<td width=\"15%\">".$sig[signos_vitales_peso]."</td>";
			$this->salida .= "<td width=\"15%\">".$sig[signos_vitales_taalta]." / ".$sig[signos_vitales_tabaja]."</td>";
			$this->salida .= "<td width=\"10%\">".$sig[signos_vitales_temperatura]."</td>";
			$this->salida .= "<td width=\"10%\">".$sig[evaluacion_dolor]."</td>";
			$this->salida .= "<td width=\"10%\">".$sig[sato2]."</td>";
			$this->salida .= "<td width=\"10%\">".$glas."</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table><br>\n\n";
		}


		function frmHistoria()
		{
			$pfj=$this->frmPrefijo;
			$vectorSig = $this->ListarSignos();
			if(!$vectorSig)
			{
				return false;
			}
			elseif($vectorSig != "ShowMensaje")
			{
				if (empty($contador)){
					$contador=sizeof($vectorSig);
				}
				$salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\">\n";
				$salida .= "<tr class=\"modulo_table_list_title\">\n";
				$salida .= "<td colspan=\"15\" align=\"center\">LISTADO DE SIGNOS VITALES</td>\n";
				$salida .= "</tr>\n";
				$salida .= "<tr class=\"hc_table_submodulo_list_title\" align=\"center\">\n";
				$salida .= "<td>FECHA</td>\n";
				$salida .= "<td>HORA</td>\n";
				$salida .= "<td>F.C.</td>\n";
				$salida .= "<td>F.R.</td>\n";
				$salida .= "<td>PVC</td>\n";
				$salida .= "<td>PIC</td>\n";
				$salida .= "<td>PESO</td>\n";
				$salida .= "<td>TENSION</td>\n";
				$salida .= "<td>MED.</td>\n";
				$salida .= "<td>SITIO T.A.</td>\n";
				$salida .= "<td>TEMP.</td>\n";
				$salida .= "<td>T.INCU</td>\n";
				$salida .= "<td>MANUAL</td>\n";
				$salida .= "<td>EVA</td>\n";
				$salida .= "<td>SATO<sub>2</sub></td>\n";
				$salida .= "</tr>\n";

				$cont=1;
				while ($cont <= sizeof($vectorSig) && $cont <= $contador)
				{
					list($fecha,$hora) = explode(" ",$vectorSig[$cont-1][fecha]);
					$salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
					$fecha = $fecha;
					//---------------Alerta de temperatura
					if (!IncludeLib('datospaciente')){
						$this->error = "Error al cargar la libreria [datospaciente].";
						$this->mensajeDeError = "datospaciente";
						return false;
					}
					$x = GetDatosPaciente("","",$this->ingreso);//funcion del api realizada por jaime
					$Edad = CalcularEdad($x[fecha_nacimiento],'');
					list($Edad,$k) = explode(" ",$Edad[edad_aprox]);
					//temperatura es 20;
					$k = $this->GetAlarmaRangoControl(20,$x[sexo_id],$Edad,$vectorSig[$cont-1][temp_piel]);
					if($k === "Alarma"){$estilo = "class='alerta'";} else {$estilo = "";}
					//---------------fin Alerta de temperatura
					//------- valido si estan en ceros que pongan "--";

					if($vectorSig[$cont-1][fc] == 0) $fc = "--"; else $fc = $vectorSig[$cont-1][fc];
					if($vectorSig[$cont-1][fr] == 0) $fr = "--"; else $fr = $vectorSig[$cont-1][fr];

					$FechaInicio = $this->datosPaciente[fecha_nacimiento];
					$FechaFin = date("Y-m-d");
					$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);

					if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
					{
						if($vectorSig[$cont-1][evaluacion_dolor] == 0) $eva = "0"; else $eva = $vectorSig[$cont-1][evaluacion_dolor];
					}
					else
					{
						if($vectorSig[$cont-1][evaluacion_dolor] == 0) $eva = "--"; else $eva = $vectorSig[$cont-1][evaluacion_dolor];
					}
					if($vectorSig[$cont-1][pvc] == 0.00) $pvc = "--"; else $pvc = $vectorSig[$cont-1][pvc];

					if($vectorSig[$cont-1][ta_alta] == 0.00)
					{$taa = "--";}
					else {$ta_alta = $vectorSig[$cont-1][ta_alta];}

					if($vectorSig[$cont-1][ta_baja] == 0.00)
					{$taa = "--";}
					else {$ta_baja = $vectorSig[$cont-1][ta_baja];}

					if($ta_alta AND $ta_baja)
					{$taa=$ta_alta." / ".$ta_baja;}


					if($vectorSig[$cont-1][media] == 0) $media = "--"; else $media = $vectorSig[$cont-1][media];
					if($vectorSig[$cont-1][sato2] == 0) $sato = "--"; else $sato = $vectorSig[$cont-1][sato2];
					if($vectorSig[$cont-1][temp_piel] == 0) $temp = "--"; else $temp = $vectorSig[$cont-1][temp_piel];
					if($vectorSig[$cont-1][servo] == 0.00) $servo = "--"; else $servo = $vectorSig[$cont-1][servo];
					if($vectorSig[$cont-1][manual] == 0.00) $manual = "--"; else $manual = $vectorSig[$cont-1][manual];
					if($vectorSig[$cont-1][presion_intracraneana] == 0) $presion = "--"; else $presion = $vectorSig[$cont-1][presion_intracraneana];
					if($vectorSig[$cont-1][peso] == 0.000) $peso = "--"; else $peso = number_format($vectorSig[$cont-1][peso],2,',','.');
					if($vectorSig[$cont-1][sitio_id]=='' OR is_null($vectorSig[$cont-1][sitio_id])){$sit='--';}else{$sit=$vectorSig[$cont-1][sitio_id];}

					if($sit <> '' and $sit <> '--')
					{
						$sitio=$this->GetSignosVitalesSitios($sit);
					}

					//-------fin valido si estan en ceros que pongan "--";
					$salida .= "<td>".$fecha."</td>\n";
					$salida .= "<td>".$hora."</td>\n";
					$salida .= "<td>".$fc."</td>\n";
					$salida .= "<td>".$fr."</td>\n";
					$salida .= "<td>".$pvc."</td>\n";
					$salida .= "<td>".$presion."</td>\n";
					$salida .= "<td>".$peso."</td>\n";
					$salida .= "<td>".$taa."</td>\n";
					$salida .= "<td>".$media."</td>\n";
					$salida .= "<td><font size='1' face='arial'>".$sitio[0][descripcion]."</font></td>\n";
					$salida .= "<td $estilo>".$temp."</td>\n";
					$salida .= "<td>".$servo."</td>\n";
					$salida .= "<td>".$manual."</td>\n";
					$salida .= "<td>".$eva."</td>\n";
					$salida .= "<td>".$sato."</td>\n";
					$salida .= "</tr>\n";
					if($vectorSig[$cont-1][observacion] !='' AND $vectorSig[$cont-1][observacion] != 'NULL')// OR $vectorSig[$cont-1][observacion]='--'  OR is_null($vectorSig[$cont-1][observacion]))
					{
						$observacion = $vectorSig[$cont-1][observacion];
						$salida .= "<tr ".$this->Lista($cont)."'>\n";
						$salida .= "<td class=\"modulo_table_title\">OBSERVACION</td>\n";
						$salida .= "<td colspan=\"14\">".$observacion."</td>\n";
						$salida .= "</tr>\n";
					}
					unset($observacion);
					$cont++;
				}

				$salida .= "</tr>\n";
				$salida .= "</table><br>\n\n";
			}
			return $salida;
		}//frmHistoria

}
?>