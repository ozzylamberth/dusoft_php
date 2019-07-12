<?php

/**
* Submodulo Control Neurologico.
*
* Submodulo para manejar el sistema neurologico de pacientes hospitalizados.
* @author Tizziano Perea O. <tperea@ipsoft-sa.com>
* @version 1.0
* @package SIIS
* $Id: hc_Control_Neurologico_HTML.php,v 1.3 2006/12/19 21:00:13 jgomez Exp $
*/

/**
* Control_Neurologico_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo motivo consulta, se extiende la clase Evolucion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Control_Neurologico_HTML extends Control_Neurologico
{

	function Control_Neurologico_HTML()
	{
	    $this->Control_Neurologico();//constructor del padre
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Listar_ControlesNeurologicos','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj]));
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
		$VectorCon = $this->Listar_ControlesNeuro();
		if (empty($VectorCon))
		{
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA TOMA DE CONTROLES NEUROLOGICOS<br><br>";
		}
		else
		{
			if($VectorCon != "ShowMensaje")
			{
				if (empty($contador)){
					$contador=sizeof($VectorCon);
				}

				$this->salida .="<table align=\"center\" width=\"100%\" border=\"0\" class=\"hc_table_submodulo_list\">";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"15\" class=\"modulo_table_list_title\" align=\"center\">LISTADOS GENERALES DE CONTROLES NEUROLOGICOS";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .="<tr class=\"modulo_table_list_title\">";
				$this->salida .="<td rowspan='2'>FECHA</td>";
				$this->salida .="<td rowspan='2'>HORA</td>";
				$this->salida .="<td colspan='2'>PUPILA DERECHA</td>";
				$this->salida .="<td colspan='2'>PUPILA IZQUIDA.</td>";
				$this->salida .="<td rowspan='2'>CONCIENCIA</td>";
				$this->salida .="<td colspan='4'> FUERZA </td>";
				$this->salida .="<td colspan='4'> ESCALA DE GLASGOW </td>";
//				$this->salida .="<td rowspan='2'>USUARIO</td>";
				$this->salida .="</tr>";
				$this->salida .="<tr class='hc_table_submodulo_list_title'>";
				$this->salida .="<td align=\"center\"> TALLA </td>";
				$this->salida .="<td align=\"center\"> REACCION</td>";
				$this->salida .="<td align=\"center\"> TALLA </td>";
				$this->salida .="<td align=\"center\"> REACCION </td>";
				$this->salida .="<td align=\"center\"> B. DER. </td>";
				$this->salida .="<td align=\"center\"> B. IZQ. </td>";
				$this->salida .="<td align=\"center\"> P. DER. </td>";
				$this->salida .="<td align=\"center\"> P. IZQ. </td>";
				$this->salida .="<td align=\"center\"> A. OCULAR </td>";
				$this->salida .="<td align=\"center\"> R. VERBAL </td>";
				$this->salida .="<td align=\"center\"> R. MOTORA </td>";
				$this->salida .="<td align=\"center\"> E.G. </td>";
				$this->salida .="</tr>";
				$cont=1;
				$spy=0;
				while ($cont <= sizeof($VectorCon) && $cont <= $contador)
				{
					list($fecha,$hora) = explode(" ",$VectorCon[$cont-1][fecha]);
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

					if($VectorCon[$cont-1][pupila_talla_d] == 0) $ptallad = "--"; else $ptallad = $VectorCon[$cont-1][pupila_talla_d];
					if($VectorCon[$cont-1][pupila_reaccion_d] == ' ') $preacciond = "--"; else $preacciond = $VectorCon[$cont-1][pupila_reaccion_d];
					if($VectorCon[$cont-1][pupila_talla_i] == 0) $ptallai = "--"; else $ptallai = $VectorCon[$cont-1][pupila_talla_i];
					if($VectorCon[$cont-1][pupila_reaccion_i] == ' ') $preaccioni = "--"; else $preaccioni = $VectorCon[$cont-1][pupila_reaccion_i];
					if($VectorCon[$cont-1][descripcion] == ' ') $conciencia = "--"; else $conciencia = $VectorCon[$cont-1][descripcion];
					if($VectorCon[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorCon[$cont-1][fuerza_brazo_d];
					if($VectorCon[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorCon[$cont-1][fuerza_brazo_i];
					if($VectorCon[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorCon[$cont-1][fuerza_pierna_d];
					if($VectorCon[$cont-1][fuerza_pierna_i] == ' ') $piernai = "--"; else $piernai = $VectorCon[$cont-1][fuerza_pierna_i];
					if($VectorCon[$cont-1][tipo_apertura_ocular_id] == 0 ) $AO = "--"; else $AO = $VectorCon[$cont-1][tipo_apertura_ocular_id];
					if($VectorCon[$cont-1][tipo_respuesta_verbal_id] == 0 ) $RV = "--"; else $RV = $VectorCon[$cont-1][tipo_respuesta_verbal_id];
					if($VectorCon[$cont-1][tipo_respuesta_motora_id] == 0 ) $RM = "--"; else $RM = $VectorCon[$cont-1][tipo_respuesta_motora_id];
	//				if($VectorCon[$cont-1][usuario] == ' ') $user = "--"; else $user = $VectorCon[$cont-1][usuario];

					$EG = $AO + $RV + $RM;
					if($EG == 0) $EG = "--"; else $EG = $EG;

					$this->salida .="<td align=\"center\">" .$fecha. "</td>";
					$this->salida .="<td align=\"center\">" .$hora. "</td>";
					$this->salida .="<td align=\"center\">" .$ptallad. "</td>";
					$this->salida .="<td align=\"center\">" .$preacciond. "</td>";
					$this->salida .="<td align=\"center\">" .$ptallai. "</td>";
					$this->salida .="<td align=\"center\">" .$preaccioni. "</td>";
					$this->salida .="<td align=\"center\">" .$conciencia. "</td>";
					$this->salida .="<td align=\"center\">" .$brazod. "</td>";
					$this->salida .="<td align=\"center\">" .$brazoi. "</td>";
					$this->salida .="<td align=\"center\">" .$piernad. "</td>";
					$this->salida .="<td align=\"center\">" .$piernai. "</td>";
					$this->salida .="<td align=\"center\">" .$AO. "</td>";
					$this->salida .="<td align=\"center\">" .$RV. "</td>";
					$this->salida .="<td align=\"center\">" .$RM. "</td>";

					if ($EG < 8)
					{
						$this->salida .="<td align=\"center\" class ='GlasgowBajo'>" .$EG. "</td>";
					}

					if ($EG >= 8 && $EG < 12)
					{
						$this->salida .="<td align=\"center\" class ='GlasgowIntermedio'>" .$EG. "</td>";
					}

					if ($EG >= 12)
					{
						$this->salida .="<td align=\"center\" class ='GlasgowAlto'>" .$EG. "</td>";
					}

					$this->salida .="</tr>";
					$cont++;
				}
				$this->salida .="</table>";
			}
		}
		return true;
	}


	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$VectorCon = $this->Listar_ControlesNeuro();
		if (empty($VectorCon))
		{
			$salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA TOMA DE CONTROLES NEUROLOGICOS<br><br>";
		}
		else
		{
			if($VectorCon != "ShowMensaje")
			{
				if (empty($contador)){
					$contador=sizeof($VectorCon);
				}

				$salida .="<table align=\"center\" width=\"100%\" border=\"1\" class=\"hc_table_submodulo_list\">";
				$salida .= "<tr>";
				$salida .= "<td colspan=\"15\" class=\"hc_table_submodulo_list_title\" align=\"center\">LISTADOS GENERALES DE CONTROLES NEUROLOGICOS";
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .="<tr class=\"modulo_table_list_title\">";
				$salida .="<td rowspan='2'>FECHA</td>";
				$salida .="<td rowspan='2'>HORA</td>";
				$salida .="<td colspan='2'>PUPILA DERECHA</td>";
				$salida .="<td colspan='2'>PUPILA IZQUIDA.</td>";
				$salida .="<td rowspan='2'>CONCIENCIA</td>";
				$salida .="<td colspan='4'> FUERZA </td>";
				$salida .="<td colspan='4'> ESCALA DE GLASGOW </td>";
//				$salida .="<td rowspan='2'>USUARIO</td>";
				$salida .="</tr>";
				$salida .="<tr class='hc_table_submodulo_list_title'>";
				$salida .="<td align=\"center\"> TALLA </td>";
				$salida .="<td align=\"center\"> REACCION</td>";
				$salida .="<td align=\"center\"> TALLA </td>";
				$salida .="<td align=\"center\"> REACCION </td>";
				$salida .="<td align=\"center\"> B. DER. </td>";
				$salida .="<td align=\"center\"> B. IZQ. </td>";
				$salida .="<td align=\"center\"> P. DER. </td>";
				$salida .="<td align=\"center\"> P. IZQ. </td>";
				$salida .="<td align=\"center\"> A. OCULAR </td>";
				$salida .="<td align=\"center\"> R. VERBAL </td>";
				$salida .="<td align=\"center\"> R. MOTORA </td>";
				$salida .="<td align=\"center\"> E.G. </td>";
				$salida .="</tr>";
				$cont=1;
				$spy=0;
				while ($cont <= sizeof($VectorCon) && $cont <= $contador)
				{
					list($fecha,$hora) = explode(" ",$VectorCon[$cont-1][fecha]);
					list($ano,$mes,$dia) = explode("-",$fecha);
					list($hora,$min) = explode(":",$hora);
					$hora=$hora.":".$min;

					$fecha = $fecha;

					if($spy==0)
					{
						$salida.="<tr class=\"modulo_list_oscuro\">";
						$spy=1;
					}
					else
					{
						$salida.="<tr class=\"modulo_list_claro\">";
						$spy=0;
					}

					if($VectorCon[$cont-1][pupila_talla_d] == 0) $ptallad = "--"; else $ptallad = $VectorCon[$cont-1][pupila_talla_d];
					if($VectorCon[$cont-1][pupila_reaccion_d] == ' ') $preacciond = "--"; else $preacciond = $VectorCon[$cont-1][pupila_reaccion_d];
					if($VectorCon[$cont-1][pupila_talla_i] == 0) $ptallai = "--"; else $ptallai = $VectorCon[$cont-1][pupila_talla_i];
					if($VectorCon[$cont-1][pupila_reaccion_i] == ' ') $preaccioni = "--"; else $preaccioni = $VectorCon[$cont-1][pupila_reaccion_i];
					if($VectorCon[$cont-1][descripcion] == ' ') $conciencia = "--"; else $conciencia = $VectorCon[$cont-1][descripcion];
					if($VectorCon[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorCon[$cont-1][fuerza_brazo_d];
					if($VectorCon[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorCon[$cont-1][fuerza_brazo_i];
					if($VectorCon[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorCon[$cont-1][fuerza_pierna_d];
					if($VectorCon[$cont-1][fuerza_pierna_i] == ' ') $piernai = "--"; else $piernai = $VectorCon[$cont-1][fuerza_pierna_i];
					if($VectorCon[$cont-1][tipo_apertura_ocular_id] == 0 ) $AO = "--"; else $AO = $VectorCon[$cont-1][tipo_apertura_ocular_id];
					if($VectorCon[$cont-1][tipo_respuesta_verbal_id] == 0 ) $RV = "--"; else $RV = $VectorCon[$cont-1][tipo_respuesta_verbal_id];
					if($VectorCon[$cont-1][tipo_respuesta_motora_id] == 0 ) $RM = "--"; else $RM = $VectorCon[$cont-1][tipo_respuesta_motora_id];
	//				if($VectorCon[$cont-1][usuario] == ' ') $user = "--"; else $user = $VectorCon[$cont-1][usuario];

					$EG = $AO + $RV + $RM;
					if($EG == 0) $EG = "--"; else $EG = $EG;

					$salida .="<td align=\"center\">" .$fecha. "</td>";
					$salida .="<td align=\"center\">" .$hora. "</td>";
					$salida .="<td align=\"center\">" .$ptallad. "</td>";
					$salida .="<td align=\"center\">" .$preacciond. "</td>";
					$salida .="<td align=\"center\">" .$ptallai. "</td>";
					$salida .="<td align=\"center\">" .$preaccioni. "</td>";
					$salida .="<td align=\"center\">" .$conciencia. "</td>";
					$salida .="<td align=\"center\">" .$brazod. "</td>";
					$salida .="<td align=\"center\">" .$brazoi. "</td>";
					$salida .="<td align=\"center\">" .$piernad. "</td>";
					$salida .="<td align=\"center\">" .$piernai. "</td>";
					$salida .="<td align=\"center\">" .$AO. "</td>";
					$salida .="<td align=\"center\">" .$RV. "</td>";
					$salida .="<td align=\"center\">" .$RM. "</td>";

					if ($EG < 8)
					{
						$salida .="<td align=\"center\" class ='GlasgowBajo'>" .$EG. "</td>";
					}

					if ($EG >= 8 && $EG < 12)
					{
						$salida .="<td align=\"center\" class ='GlasgowIntermedio'>" .$EG. "</td>";
					}

					if ($EG >= 12)
					{
						$salida .="<td align=\"center\" class ='GlasgowAlto'>" .$EG. "</td>";
					}

					$salida .="</tr>";
					$cont++;
				}
				$salida .="</table>";
			}
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
			$this->salida = ThemeAbrirTablaSubModulo('CONTROL DE ESTADO NEUROLOGICO');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$Tallas = $this->GetTallasPupilas();
		$Reaccion = $this->GetReaccionPupilas();
		$Nivel_Conciencia = $this->GetNivelesConciencia();
		$TiposFuerza = $this->GetTiposFuerza();
		$TipoAperturaOcular = $this->GetTipoAperturaOcular();
		$RespuestaVerbal = $this->GetRespuestaVerbal();
		$RespuestaMotora = $this->GetRespuestaMotora();

		$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Insertar_ControlesNeurologicos'));
		$this->salida .= "<form name=\"Neurologico$pfj\"' action='".$href."' method='POST'>";

		$this->salida .= "<table colspan=\"2\" align=\"center\" width=\"90%\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= $this->SetStyle("MensajeError",11);
		$this->salida .= "<tr class='modulo_table_title'>\n";
		$this->salida .= "<td align='center' width=\"50%\">TOMA DE CONTROLES NEUROLOGICOS\n";
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

		$this->salida.="<table border='0' align='center' valign='top' width='90%' class=\"modulo_table_list\">";
		$this->salida.="<tr>";
		$this->salida.="<td>";

		/*---------------------------------------------------------------------
		*	ESTRUCTURA EN HTML DE LOS SISTEMAS NEUROLOGICOS A EVALUAR
		*	TIZZIANO PEREA O.
		---------------------------------------------------------------------*/

		$this->salida.="<table border='1' cellspacing='3' cellpadding='6' width='100%' class=\"modulo_table_list\">";

		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td colspan='2' align='center'> PUPILAS</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_claro\">";
		$this->salida.="<td align='center'>";
		$this->salida.="<table border='1' class=\"modulo_table_list\"><div align='center'>TALLA PUPILA IZQUIERDA</div>";
		$this->salida.="<tr class=\"modulo_list_claro\">";
		$this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_4.png\" border=0></td>";
		$this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_3.png\" border=0></td>";
		$this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_2.png\" border=0></td>";
		$this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_grande.png\" border=0></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="<td align='center'><input type='radio' name='pupilaI$pfj' value='".$Tallas[0]['talla_pupila_id']."'></td>";
		$this->salida.="<td align='center'><input type='radio' name='pupilaI$pfj' value='".$Tallas[1]['talla_pupila_id']."'></td>";
		$this->salida.="<td align='center'><input type='radio' name='pupilaI$pfj' value='".$Tallas[2]['talla_pupila_id']."'></td>";
		$this->salida.="<td align='center'><input type='radio' name='pupilaI$pfj' value='".$Tallas[3]['talla_pupila_id']."'></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr>";
		$this->salida.="<td colspan='4' align='center'><label>REACCION</label><br>";

		$this->salida.="<select name='reaccionI$pfj' class='select'>";
		foreach ($Reaccion as $k => $v)
		{
			$this->salida .= "<option value='".$v['reaccion_pupila_id']."' >".$v['descripcion']."</option>\n";
		}
		$this->salida.="</select></td>";
		$this->salida.="</tr>";

		$this->salida.="</table>";
		$this->salida.="</td>";

		$this->salida.="<td align='center'>";
		$this->salida.="<table border='1' class=\"modulo_table_list\"><div align='center'>TALLA PUPILA DERECHA</div>";
		$this->salida.="<tr class=\"modulo_list_claro\">";
		$this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_4.png\" border=0></td>";
		$this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_3.png\" border=0></td>";
		$this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_2.png\" border=0></td>";
		$this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_grande.png\" border=0></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="<td align='center'><input type='radio' name='pupilaD$pfj' value='".$Tallas[0]['talla_pupila_id']."'></td>";
		$this->salida.="<td align='center'><input type='radio' name='pupilaD$pfj' value='".$Tallas[1]['talla_pupila_id']."'></td>";
		$this->salida.="<td align='center'><input type='radio' name='pupilaD$pfj' value='".$Tallas[2]['talla_pupila_id']."'></td>";
		$this->salida.="<td align='center'><input type='radio' name='pupilaD$pfj' value='".$Tallas[3]['talla_pupila_id']."'></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr>";
		$this->salida.="<td colspan='4' align='center'><label>REACCION</label><br>";

		$this->salida.="<select name='reaccionD$pfj' class='select'>";
		foreach ($Reaccion as $k => $v)
		{
			$this->salida .= "<option value='".$v['reaccion_pupila_id']."' >".$v['descripcion']."</option>\n";
		}
		$this->salida.="</select></td>";

		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";

		$this->salida.="<td>";
		$this->salida.="<table border='1' cellspacing='1' cellpadding='3' width='100%' class=\"modulo_table_list\">";//width='100%'

		$this->salida.="<tr class=\"modulo_table_list_title\">";
    	$this->salida.="<td colspan='2'> NIVELES DE CONCIENCIA</td>";
		$this->salida.="</tr>";

		$spy=0;
		foreach ($Nivel_Conciencia as $k => $c)
		{
			if($spy==0)
			{
				$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";//align='center'
				$spy=1;
			}
			else
			{
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";//align='center'
				$spy=0;
			}

    		$this->salida.="<td><b>$c[descripcion]</b></td>";
			$this->salida.="<td align='center'><input type='radio' name='orientado$pfj' value='".$c['nivel_consciencia_id']."'></td>";
			$this->salida.="</tr>";
		}

		$this->salida.="</table>";
		$this->salida.="</td>";


		$this->salida.="<td>";
		$this->salida.="<table border='1' width='100%' class=\"modulo_table_list\">";//width='100%'

		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td colspan='2'> FUERZA</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_claro\">";
    	$this->salida.="<td> BRAZO DERECHO </td>";
		$this->salida.="<td align='center'>";
		$this->salida.="<select name='brader$pfj' class='select'>";

		foreach ($TiposFuerza as $k => $f)
		{
			$this->salida.="<option value='".$f['fuerza_id']."' >".$f['descripcion']."</option>\n";
		}
		$this->salida.="</select></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_oscuro\">";
    	$this->salida.="<td> BRAZO IZQUIERDO </td>";
    	$this->salida.="<td align='center'>";
		$this->salida.="<select name='braizq$pfj' class='select'>";
		foreach ($TiposFuerza as $k => $f)
		{
			$this->salida.="<option value='".$f['fuerza_id']."' >".$f['descripcion']."</option>\n";
		}
		$this->salida.="</select></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_claro\">";
    	$this->salida.="<td> PIERNA DERECHA </td>";
		$this->salida.="<td align='center'>";
		$this->salida.="<select name='pierder$pfj' class='select'>";
		foreach ($TiposFuerza as $k => $f)
		{
			$this->salida.="<option value='".$f['fuerza_id']."' >".$f['descripcion']."</option>\n";
		}
		$this->salida.="</select></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_oscuro\">";
    	$this->salida.="<td> PIERNA IZQUIERDA</td>";
		$this->salida.="<td align='center'>";
		$this->salida.="<select name='pierizq$pfj' class='select'>";
		foreach ($TiposFuerza as $k => $f)
		{
			$this->salida.="<option value='".$f['fuerza_id']."' >".$f['descripcion']."</option>\n";
		}
		$this->salida.="</select> </td>";
    	$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";

		/*---------------------------------------------------------------------
		*	ESTRUCTURA EN HTML DE LA ESCALA DE GLASGOW
		*	TIZZIANO PEREA O.
		---------------------------------------------------------------------*/

		$this->salida.="<table border='0' align='center' valign='top' width='90%' class=\"modulo_table_list\">";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table width='100%' valign='top' border='0' align='center' class=\"modulo_table_list\">";

		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td colspan='3' align='center'> ESCALA DE GLASGOW</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr><td>";
		$this->salida.="<table border='1' cellspacing='4' cellpadding='5' width='100%' class=\"modulo_table_list\">";
		$this->salida.="<div align='center' class='modulo_table_title'>APERTURA OCULAR</div>";

		foreach ($TipoAperturaOcular as $k => $AO)
		{
			$this->salida.="<tr>";
			$this->salida.="<td class=\"modulo_list_claro\">".$AO[apertura_ocular_id].' -   '.$AO[descripcion]."</td>";
			$this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='ao$pfj' value='".$AO['apertura_ocular_id']."'> </td>";
			$this->salida.="</tr>";
		}

		$this->salida.="</td></tr>";
		$this->salida.="</table>";

		$this->salida.="<td>";
		$this->salida.="<table border='1'  cellspacing='2' cellpadding='3' width='100%' class=\"modulo_table_list\">";
		$this->salida.="<div align='center' class='modulo_table_title'>RESPUESTA VERBAL</div>";
		foreach ($RespuestaVerbal as $k => $RV)
		{
			$FechaInicio = $this->datosPaciente[fecha_nacimiento];
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
			{
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_list_claro\">".$RV[respuesta_verbal_id].' -   '.$RV[descripcion_lactante]."</td>";
				$this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='rv$pfj' value='".$RV['respuesta_verbal_id']."'> </td>";
				$this->salida.="</tr>";
			}
			else
			{
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_list_claro\">".$RV[respuesta_verbal_id].' -   '.$RV[descripcion]."</td>";
				$this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='rv$pfj' value='".$RV['respuesta_verbal_id']."'> </td>";
				$this->salida.="</tr>";
			}
		}
		$this->salida.="</table></td>";


		$this->salida.="<td>";
		$this->salida.="<table border='1' width='100%' class=\"modulo_table_list\">";
		$this->salida.="<div align='center' class='modulo_table_title'> RESPUESTA MOTORA</div>";
		foreach ($RespuestaMotora as $k => $RM)
		{
			$FechaInicio = $this->datosPaciente[fecha_nacimiento];
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
			{
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_list_claro\">".$RM[respuesta_motora_id].' -   '.$RM[descripcion_lactante]."</td>";
				$this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='rm$pfj' value='".$RM['respuesta_motora_id']."'> </td>";
				$this->salida.="</tr>";
			}
			else
			{
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_list_claro\">".$RM[respuesta_motora_id].' -   '.$RM[descripcion]."</td>";
				$this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='rm$pfj' value='".$RM['respuesta_motora_id']."'> </td>";
				$this->salida.="</tr>";
			}
		}
		$this->salida.="</table></td>";

		$this->salida.="</td></tr>";
		$this->salida.="</table>";
		$this->salida.="</td></tr>";
		$this->salida.="</table>";

		$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='Save$pfj' value='INSERTAR'>";
		$this->salida.="</form>";
		$this->salida .="<div class='label_mark' align='center'><BR>LISTADO DE CONTROLES NEUROLOGICOS<br><br>";
		if (!empty($VectorControl))
		{
			$this->ShowControl_Neurologico();
		}
		else
		{
			$this->ShowControl_Neurologico();
			$this->salida .= ThemeCerrarTablaSubModulo();
		}
		return true;
	}


	function ShowControl_Neurologico()
	{
		$pfj=$this->frmPrefijo;
		$VectorControl = $this->Listar_ControlesNeurologicos();

		/*Insercion del buscador*/
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Listar_ControlesNeurologicos'));
		$this->salida.= "<form name=\"neuro$pfj\" action=\"$accionI\" method=\"post\">";
		if(!$VectorControl)
		{
			return false;
		}
		elseif($VectorControl != "ShowMensaje")
		{
			if (empty($contador)){
				$contador=sizeof($VectorControl);
			}

			$this->salida .="<table align=\"center\" width=\"100%\" border='0'>";
			$this->salida .="<tr class=\"modulo_table_list_title\">";
			$this->salida .="<td rowspan='2'>FECHA</td>";
			$this->salida .="<td rowspan='2'>HORA</td>";
			$this->salida .="<td colspan='2'>PUPILA DERECHA</td>";
			$this->salida .="<td colspan='2'>PUPILA IZQUIDA.</td>";
			$this->salida .="<td rowspan='2'>CONCIENCIA</td>";
			$this->salida .="<td colspan='4'> FUERZA </td>";
			$this->salida .="<td colspan='4'> ESCALA DE GLASGOW </td>";
			$this->salida .="<td rowspan='2'>USUARIO</td>";
			$this->salida .="</tr>";
			$this->salida .="<tr class='hc_table_submodulo_list_title'>";
			$this->salida .="<td align=\"center\"> TALLA </td>";
			$this->salida .="<td align=\"center\"> REACCION</td>";
			$this->salida .="<td align=\"center\"> TALLA </td>";
			$this->salida .="<td align=\"center\"> REACCION </td>";
			$this->salida .="<td align=\"center\"> B. DER. </td>";
			$this->salida .="<td align=\"center\"> B. IZQ. </td>";
			$this->salida .="<td align=\"center\"> P. DER. </td>";
			$this->salida .="<td align=\"center\"> P. IZQ. </td>";
			$this->salida .="<td align=\"center\"> A. OCULAR </td>";
			$this->salida .="<td align=\"center\"> R. VERBAL </td>";
			$this->salida .="<td align=\"center\"> R. MOTORA </td>";
			$this->salida .="<td align=\"center\"> E.G. </td>";
			$this->salida .="</tr>";
			$cont=1;
			$spy=0;
			while ($cont <= sizeof($VectorControl) && $cont <= $contador)
			{
				list($fecha,$hora) = explode(" ",$VectorControl[$cont-1][fecha]);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$hora=$hora.":".$min;
				//$this->salida .= "<tr align='center'>\n";
				if($fecha == date("Y-m-d"))
				{
					$fecha = "HOY";
				}
				elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
				{
					$fecha = "AYER";
				}
				else
				{
					$fecha = $fecha;
				}

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

				if($VectorControl[$cont-1][pupila_talla_d] == 0) $ptallad = "--"; else $ptallad = $VectorControl[$cont-1][pupila_talla_d];
				if($VectorControl[$cont-1][pupila_reaccion_d] == ' ') $preacciond = "--"; else $preacciond = $VectorControl[$cont-1][pupila_reaccion_d];
				if($VectorControl[$cont-1][pupila_talla_i] == 0) $ptallai = "--"; else $ptallai = $VectorControl[$cont-1][pupila_talla_i];
				if($VectorControl[$cont-1][pupila_reaccion_i] == ' ') $preaccioni = "--"; else $preaccioni = $VectorControl[$cont-1][pupila_reaccion_i];
				if($VectorControl[$cont-1][descripcion] == ' ') $conciencia = "--"; else $conciencia = $VectorControl[$cont-1][descripcion];
				if($VectorControl[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorControl[$cont-1][fuerza_brazo_d];
				if($VectorControl[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorControl[$cont-1][fuerza_brazo_i];
				if($VectorControl[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorControl[$cont-1][fuerza_pierna_d];
				if($VectorControl[$cont-1][fuerza_pierna_i] == ' ') $piernai = "--"; else $piernai = $VectorControl[$cont-1][fuerza_pierna_i];
				if($VectorControl[$cont-1][tipo_apertura_ocular_id] == 0 ) $AO = "--"; else $AO = $VectorControl[$cont-1][tipo_apertura_ocular_id];
				if($VectorControl[$cont-1][tipo_respuesta_verbal_id] == 0 ) $RV = "--"; else $RV = $VectorControl[$cont-1][tipo_respuesta_verbal_id];
				if($VectorControl[$cont-1][tipo_respuesta_motora_id] == 0 ) $RM = "--"; else $RM = $VectorControl[$cont-1][tipo_respuesta_motora_id];
				if($VectorControl[$cont-1][usuario] == ' ') $user = "--"; else $user = $VectorControl[$cont-1][usuario];
				$EG = $AO + $RV + $RM;
				if($EG == 0) $EG = "--"; else $EG = $EG;

					$this->salida .="<td align=\"center\">" .$fecha. "</td>";
					$this->salida .="<td align=\"center\">" .$hora. "</td>";
					$this->salida .="<td align=\"center\">" .$ptallad. "</td>";
					$this->salida .="<td align=\"center\">" .$preacciond. "</td>";
					$this->salida .="<td align=\"center\">" .$ptallai. "</td>";
					$this->salida .="<td align=\"center\">" .$preaccioni. "</td>";
					$this->salida .="<td align=\"center\">" .$conciencia. "</td>";
					$this->salida .="<td align=\"center\">" .$brazod. "</td>";
					$this->salida .="<td align=\"center\">" .$brazoi. "</td>";
					$this->salida .="<td align=\"center\">" .$piernad. "</td>";
					$this->salida .="<td align=\"center\">" .$piernai. "</td>";
					$this->salida .="<td align=\"center\">" .$AO. "</td>";
					$this->salida .="<td align=\"center\">" .$RV. "</td>";
					$this->salida .="<td align=\"center\">" .$RM. "</td>";
					if ($EG < 8)
					{
						$this->salida .="<td align=\"center\" class ='GlasgowBajo'>" .$EG. "</td>";
					}

					if ($EG >= 8 && $EG < 12)
					{
						$this->salida .="<td align=\"center\" class ='GlasgowIntermedio'>" .$EG. "</td>";
					}

					if ($EG >= 12)
					{
						$this->salida .="<td align=\"center\" class ='GlasgowAlto'>" .$EG. "</td>";
					}

					$fechareg =$VectorControl[$cont-1][fecha_registro];
					$user=$this->GetDatosUsuarioSistema($VectorControl[$cont-1][usuario_id]);
					if ($VectorControl[$cont-1][usuario_id] == UserGetUID() AND $VectorControl[$cont-1][evolucion_id] == $this->evolucion)
					{
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'BorrarControlNeuro', 'fechar'.$pfj=>$fechareg));
						$this->salida .= "<td><a href='".$accion."'>ELIMINAR</a></td>\n";
					}
					else
					{
						$this->salida .="<td align=\"center\">" .$user[0][usuario]. "</td>";
					}

					$this->salida .="</tr>";
					$cont++;
				}
				$this->salida .="</table>";
				//Mostrar Barra de Navegacion
				$VectorControl=$this->RetornarBarra_Paginadora();
				if($VectorControl)
				{
					$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"100%\" align=\"center\">";
					$this->salida .=$VectorControl;
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  </table><br>";
				}
				$this->salida .= "</form>";
		}
		return true;
	}
}

?>
