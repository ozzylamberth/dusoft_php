<?php

/**
* Submodulo de Motivo Consulta (HTML).
*
* Submodulo para manejar el motivo de la consulta en una evolución.
* @author Jairo Duvan Diaz <planetjd@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_NotasEnfermeria_HTML.php,v 1.6 2009/04/22 19:19:09 johanna Exp $
*/

/**
* NotasEnfermeria_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo motivo consulta, se extiende la clase Evolucion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class NotasEnfermeria_HTML extends NotasEnfermeria
{

	function NotasEnfermeria_HTML()
	{
	    $this->NotasEnfermeria();//constructor del padre
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListadoNotasE','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj]));
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


	function frmForma()
	{
		$pfj=$this->frmPrefijo;

		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('NOTAS DE ENFERMERIA');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\">";

		if($this->SetStyle("MensajeError"))
		{
			$this->salida.="<table align=\"center\">";
			$this->salida.=$this->SetStyle("MensajeError");
			$this->salida.="</table>";
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class='modulo_table_list_title'>";
		$this->salida.="<td align='center'>NOTAS DE ENFERMERIA";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class='hc_table_submodulo_list_title'>";
		$this->salida.="<td align='right'>HORA : ";
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
    //print_r($rangomin);
		$this->salida.= "<select name='selectHora$pfj' class='select'>\n";
		for($j = $rangomin; $j<=$rango_turno; $j++)
		{
    //print_r($j);
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
		$this->salida.= "</select>&nbsp;:&nbsp;\n";
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
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align='center' colspan=\"2\" class='hc_submodulo_list_claro'>";
		
		$this->salida.="<br><textarea onkeydown=\"return noControl(event)\" name=\"evol".$pfj."\" cols=\"80\" rows=\"7\" style = \"width:90%\" class=\"textarea\"></textarea>";//".$this->PlanTerapeuticoActual()."
		$this->salida.="<p align=\"center\">";
		$this->salida.="<input type=\"submit\" value=\"Insertar\" class=\"input-submit\">";
		$this->salida.="</p>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</form>";
		$this->salida.='</table>';

		$this->frmReporte();
    $this->salida .= "<script language='javascript'>\n";
    $this->salida .= " function disableselect(e) \n";
    $this->salida .= "{ \n";
    $this->salida .= " return false;\n";
    $this->salida .= "} \n";
    $this->salida .= " function reEnable() \n";
    $this->salida .= "{ \n";
    $this->salida .= " return true;\n";
    $this->salida .= "} \n";
    $this->salida .= " function inhabilitar()\n";
    $this->salida .= "  {\n";
    $this->salida .= "    alert ('ESTA FUNCION ESTA DESHABILITADA ') ;\n";
    $this->salida .= "    return false;\n";
    $this->salida .= "  }\n";
    $this->salida .= "  document.oncontextmenu=inhabilitar;\n ";
    $this->salida .= "  if (window.sidebar)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    document.getElementById('listado').onmousedown=disableselect;\n";
    $this->salida .= "    document.getElementById('listado').onclick=reEnable;\n";
    $this->salida .= "  }\n";
    $this->salida .= "</script>\n";
    $ctl = AutoCarga::factory('ClaseUtil');
    $this->salida .= $ctl->NoControl();
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmReporte()
	{
		$datos=$this->PlanTerapeuticoTodos();
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarSignosVitales'));
		$this->salida.= "<form name=\"NotasE$pfj\" action=\"$accionI\" method=\"post\">";

		if($datos===false)
		{
			return false;
		}
		if(!empty($datos))
		{
			$this->salida .="<div id=\"listado\">";
			$this->salida .="<div class='label_mark' align='center'><br>LISTADO GENERAL DE NOTAS DE ENFERMERIA<br><br></div>";

			$this->salida .="<table width=\"90%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td>NOTAS</td>";
			$this->salida .="</tr>";

			$spy=0;
			foreach($datos as $k=>$v)
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

				$this->salida .="<td width='10%' align='center'>$k</td>";


				$this->salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector){
     
					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>$vector[hora]</b></td>";
					$this->salida .="<td><b>";
					$this->salida .=$vector[usuario].' - '.$vector[nombre];
					$this->salida .="</b></td>";
					$this->salida .="</tr>";
					$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
					$this->salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$this->salida .="<td width='100%'>$vector[descripcion]</td>";
					$this->salida .="</tr>";
					$this->salida .="<tr>";

				}
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}

			$this->salida .= "</table>";
      $this->salida .= "</div>\n";
			//Mostrar Barra de Navegacion
				$datos =$this->RetornarBarra_Paginadora();
				if($datos)
				{
					$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"100%\" align=\"center\">";
					$this->salida .=$datos;
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  </table>";
				}
				$this->salida .= "  </form>";
				
			}
		else
		{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr  align=\"center\"><br><td><label class='label_mark'>NO HAY RESUMEN PARA ESTE PACIENTE</label>";
			$this->salida.="</td></tr>";
			$this->salida.="</table>";
			return false;
		}

    return true;
	}


	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$Notas=$this->PlanTerapeuticoNotasE();
    
		if (empty ($Notas))
		{
      $this->salida .="<div id=\"listado\">";
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA REPORTES DE NOTAS DE ENFERMERIA<br><br>";
			$this->salida .="</tr>";
			$this->salida .="</table>";
      $this->salida .= "</div>\n";
		}
		if(!empty($Notas))
		{ 
      $this->salida .="<div id=\"listado\">";
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td align=\"center\">FECHA</td>";
			$this->salida .="<td align=\"center\">LISTADO DE NOTAS DE ENFERMERIA</td>";
			$this->salida .="</tr>";
      $this->salida .= "</div>\n";

			$spy=0;
			foreach($Notas as $k=>$v)
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

				$this->salida .="<td width=\"10%\" align=\"center\">$k</td>";


				$this->salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector){

					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>".$vector[hora]."</b></td>";
					$this->salida .="<td><b>";
					$this->salida .=$vector[usuario].' - '.$vector[nombre];
					$this->salida .="</b></td>";

					$this->salida .="</tr>";
					$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
					$this->salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$this->salida .="<td width='100%'>$vector[descripcion]</td>";
					$this->salida .="</tr>";
					$this->salida .="<tr>";

				}
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}

			$this->salida.="</table><br>";
		}
        $this->salida.="<br><textarea onkeydown=\"return noControl(event)\" name=\"evol".$pfj."\" cols=\"80\" rows=\"7\" style = \"width:90%\" class=\"textarea\"></textarea>";//".$this->PlanTerapeuticoActual()."
        $this->salida .= "<script language='javascript'>\n";
        $this->salida .= " function disableselect(e) \n";
        $this->salida .= "{ \n";
        $this->salida .= " return false;\n";
        $this->salida .= "} \n";
        $this->salida .= " function reEnable() \n";
        $this->salida .= "{ \n";
        $this->salida .= " return true;\n";
        $this->salida .= "} \n";
        $this->salida .= " function inhabilitar()\n";
        $this->salida .= "  {\n";
        $this->salida .= "    alert ('ESTA FUNCION ESTA DESHABILITADA ') ;\n";
        $this->salida .= "    return false;\n";
        $this->salida .= "  }\n";
        $this->salida .= "  document.oncontextmenu=inhabilitar;\n ";
        $this->salida .= "  if (window.sidebar)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    document.getElementById('listado').onmousedown=disableselect;\n";
        $this->salida .= "    document.getElementById('listado').onclick=reEnable;\n";
        $this->salida .= "  }\n";
        $this->salida .= "</script>\n";
        $ctl = AutoCarga::factory('ClaseUtil');
        $this->salida .= $ctl->NoControl();
    		$this->salida .= ThemeCerrarTablaSubModulo();
    	return true;
	}


	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$Notas=$this->PlanTerapeuticoNotasE();
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarSignosVitales'));
		$salida.= "<form name=\"NotasE$pfj\" action=\"$accionI\" method=\"post\">";

		if($Notas===false)
		{
			return false;
		}
		if(!empty($Notas))
		{
			$salida .="<table width=\"100%\" border=\"1\" align=\"center\">";
			$salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida .="<td align=\"center\">FECHA</td>";
			$salida .="<td align=\"center\">LISTADO DE NOTAS DE ENFERMERIA</td>";
			$salida .="</tr>";

			$spy=0;
			foreach($Notas as $k=>$v)
			{
				if($spy==0)
				{
					$salida.="<tr class=\"hc_submodulo_list_oscuro\">";//align='center'
					$spy=1;
				}
				else
				{
					$salida.="<tr class=\"hc_submodulo_list_claro\">";//align='center'
					$spy=0;
				}

				$salida .="<td width='10%' align='center'>$k</td>";


				$salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector){

					$salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$salida .="<td><b>".$vector[hora]."</b></td>";
					$salida .="<td><b>";
					$salida .=$vector[usuario].' - '.$vector[nombre];
					$salida .="</b></td>";
					$salida .="</tr>";
					$salida .="<tr class=\"hc_submodulo_list_claro\">";
					$salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$salida .="<td width='100%'>$vector[descripcion]</td>";
					$salida .="</tr>";
					$salida .="<tr>";

				}
				$salida .="</table>";
				$salida .="</td>";
				$salida .="</tr>";
			}

			$salida.="</table><br>";
		}
    
	    return $salida;
    }
  }
?>