<?php

/**
* Submodulo de Signos Vitales Urgencias (HTML).
*
* Submodulo para manejar los signos vitales de un paciente en Urgencias.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_SignosVitalesUrgencias_HTML.php,v 1.3 2006/12/19 21:00:15 jgomez Exp $
*/

/**
* SignosVitalesUrgencias_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo signos vitales en Urgencias, se extiende la clase SignosVitalesUrgencias y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class SignosVitalesUrgencias_HTML extends SignosVitalesUrgencias
{

	function SignosVitalesUrgencias_HTML()
	{
	    $this->SignosVitalesUrgencias();//constructor del padre
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
/////////////////////////////////

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
				$this->salida = ThemeAbrirTabla('SIGNOS VITALES GENERALES URGENCIAS');
			}
			else
			{
				$this->salida  = ThemeAbrirTabla($this->titulo);
			}

			$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarSignosVitales'));
			$this->salida .= "<form name=\"signos_vitales$pfj\"' action='".$href."' method='POST'>";

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
               $rangomin = $rango_turno - 6;
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
                    $this->salida .= "<option value='".date("Y-m-d")." ".$i."' selected $selected>".$i."</option>\n";
               }//fin for
               
               if(!empty($_REQUEST['selectHora'.$pfj]))
               {
                    $horas_R = explode(" ", $_REQUEST['selectHora'.$pfj]);
                    $this->salida .= "<option value='".date("Y-m-d")." ".$horas_R[1]."' selected='true'>".$horas_R[1]."</option>\n";
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
			$this->salida .= "<td align=\"center\" >TEMP.</td>\n";			
               $this->salida .= "<td align=\"center\" >TENSION ARTERIAL</td>\n";
			$this->salida .= "</tr>\n";
			
               $this->salida .= "<tr ".$this->Lista(1).">\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='fc$pfj' value='".$_REQUEST['fc'.$pfj]."' size='6' maxlength='5'> X min</td>\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='fr$pfj' value='".$_REQUEST['fr'.$pfj]."' size='6' maxlength='5'> X min</td>\n";
			$this->salida .= "<td align='center'><input type='text' class='input-text' name='tpiel$pfj' value='".$_REQUEST['tpiel'.$pfj]."' size='6' maxlength='5'> ºC</td>\n";
			$this->salida .= "<td align='center'>";
			$this->salida .= "<input type=\"text\" class='input-text' name=\"taa$pfj\" value='".$_REQUEST['taa'.$pfj]."' size='6' maxlength='5'>&nbsp;<b>/</b>&nbsp;<input type=\"text\" class='input-text' name=\"tab$pfj\" value='".$_REQUEST['tab'.$pfj]."' size='6' maxlength='5'>";
			$this->salida .= "</td>\n";
               $this->salida .= "</tr>\n";
               
               $this->salida .= "<tr ".$this->Lista(1).">\n";
               $this->salida .= "<td align=\"center\" colspan=\"4\">\n";
               $this->salida .= "<input type='submit' class='input-submit' name='Save$pfj' value='INSERTAR'>";
			$this->salida .= "</td>\n";               
               $this->salida .= "</tr>\n";
               $this->salida .= "</table>\n\n";

			/*-------------------------------------------
				Segemento que imprime en pantalla
				los Signos Vitales que se tomaran al paciente.
			  -------------------------------------------
			*/
			$this->salida .= "</form>\n";
			$this->salida .="<div class='label_mark' align='center'><BR>LISTADO DE SIGNOS VITALES<br>";
               $this->ShowSignosVitales();
               $this->salida .= themeCerrarTabla();
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
				$this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "<td>FECHA</td>\n";
				$this->salida .= "<td>HORA</td>\n";
				$this->salida .= "<td>F.C.</td>\n";
				$this->salida .= "<td>F.R.</td>\n";
                    $this->salida .= "<td>TEMP.</td>\n";
				$this->salida .= "<td>TENSION</td>\n";
				$this->salida .= "<td>MEDIA</td>\n";
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

					if($vectorSignos[$cont-1][ta_alta] == 0.00)
					{$taa = "--";}
					else {$ta_alta = $vectorSignos[$cont-1][ta_alta];}

					if($vectorSignos[$cont-1][ta_baja] == 0.00)
					{$taa = "--";}
					else {$ta_baja = $vectorSignos[$cont-1][ta_baja];}

					if($ta_alta AND $ta_baja)
					{$taa=$ta_alta." / ".$ta_baja;}


					if($vectorSignos[$cont-1][media] == 0) $media = "--"; else $media = $vectorSignos[$cont-1][media];
					if($vectorSignos[$cont-1][temp_piel] == 0) $temp = "--"; else $temp = $vectorSignos[$cont-1][temp_piel];

					//-------fin valido si estan en ceros que pongan "--";
					$this->salida .= "<td>".$fecha."</td>\n";
					$this->salida .= "<td>".$hora."</td>\n";
					$this->salida .= "<td>".$fc."</td>\n";
					$this->salida .= "<td>".$fr."</td>\n";
					$this->salida .= "<td $estilo>".$temp."</td>\n";					
					$this->salida .= "<td>".$taa."</td>\n";
					$this->salida .= "<td>".$media."</td>\n";
					
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
               $this->salida .= "<td>TENSION</td>\n";
               $this->salida .= "<td>MEDIA</td>\n";
               $this->salida .= "<td>TEMP.</td>\n";
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

                    if($vectorSig[$cont-1][ta_alta] == 0.00)
                    {$taa = "--";}
                    else {$ta_alta = $vectorSig[$cont-1][ta_alta];}

                    if($vectorSig[$cont-1][ta_baja] == 0.00)
                    {$taa = "--";}
                    else {$ta_baja = $vectorSig[$cont-1][ta_baja];}

                    if($ta_alta AND $ta_baja)
                    {$taa=$ta_alta." / ".$ta_baja;}

                    if($vectorSig[$cont-1][media] == 0) $media = "--"; else $media = $vectorSig[$cont-1][media];
                    if($vectorSig[$cont-1][temp_piel] == 0) $temp = "--"; else $temp = $vectorSig[$cont-1][temp_piel];

                    //-------fin valido si estan en ceros que pongan "--";
                    $this->salida .= "<td>".$fecha."</td>\n";
                    $this->salida .= "<td>".$hora."</td>\n";
                    $this->salida .= "<td>".$fc."</td>\n";
                    $this->salida .= "<td>".$fr."</td>\n";
                    $this->salida .= "<td>".$taa."</td>\n";
                    $this->salida .= "<td>".$media."</td>\n";
                    $this->salida .= "<td $estilo>".$temp."</td>\n";
                    $this->salida .= "</tr>\n";
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
               $salida .= "<td>TENSION</td>\n";
               $salida .= "<td>MEDIA</td>\n";
               $salida .= "<td>TEMP.</td>\n";
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

                    if($vectorSig[$cont-1][ta_alta] == 0.00)
                    {$taa = "--";}
                    else {$ta_alta = $vectorSig[$cont-1][ta_alta];}

                    if($vectorSig[$cont-1][ta_baja] == 0.00)
                    {$taa = "--";}
                    else {$ta_baja = $vectorSig[$cont-1][ta_baja];}

                    if($ta_alta AND $ta_baja)
                    {$taa=$ta_alta." / ".$ta_baja;}

                    if($vectorSig[$cont-1][media] == 0) $media = "--"; else $media = $vectorSig[$cont-1][media];
                    if($vectorSig[$cont-1][temp_piel] == 0) $temp = "--"; else $temp = $vectorSig[$cont-1][temp_piel];

                    //-------fin valido si estan en ceros que pongan "--";
                    $salida .= "<td>".$fecha."</td>\n";
                    $salida .= "<td>".$hora."</td>\n";
                    $salida .= "<td>".$fc."</td>\n";
                    $salida .= "<td>".$fr."</td>\n";
                    $salida .= "<td>".$taa."</td>\n";
                    $salida .= "<td>".$media."</td>\n";
                    $salida .= "<td $estilo>".$temp."</td>\n";
                    $salida .= "</tr>\n";
                    $cont++;
               }
               $salida .= "</tr>\n";
               $salida .= "</table><br>\n\n";
          }
          return $salida;
     }//frmHistoria

}
?>
