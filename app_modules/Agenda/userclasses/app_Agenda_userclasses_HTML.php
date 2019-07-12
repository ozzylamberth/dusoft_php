<?php

class app_Agenda_userclasses_HTML extends app_Agenda_user
{
	var $contador;

	function app_Agenda_user_HTML()
	{
		$this->app_Agenda_user(); //Constructor del padre 'modulo'
		$this->contador=$_REQUEST['contador'];
		return true;
	}

	function ValidacionDia($mes,$dias,$year,$citas,$cita)
	{
		$s=$this->BusquedaDiaFestivo(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year)));
		if($s!=0)
		{
			$this->salida .= "<table border=\"1\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
		}
		else
		{
			$this->salida .= "<table border=\"1\" width=\"98%\" align=\"center\" class=\"modulo_table\">";
		}
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"20%\" rowspan=\"2\">".date("d",mktime(0,0,0,$mes,$dias,$year))."</td>";

		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(0,0,0,$mes,$dias,$year));
		//verifica que si el arreglo de las citas concuerda con una fecha
		if(date("Y-m-d H",mktime(0,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" align=\"right\" ".$_ENV['THEME_VARS']['CitaExistente']."><font color=\"#ffffff\">&nbsp;</font></td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			if(date("Y-m-d H",mktime(0,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
			  $cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\" align=\"right\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" align=\"right\">&nbsp;</td>";
			}
		}

		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(6,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" ".$_ENV['THEME_VARS']['CitaExistente'].">&nbsp;</td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			if(date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
      $cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\">&nbsp;</td>";
			}
		}
    $this->salida .= "</tr><tr>";
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(12,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" ".$_ENV['THEME_VARS']['CitaExistente'].">&nbsp;</td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			if(date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
        $cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\">&nbsp;</td>";
			}else{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\">&nbsp;</td>";
			}
		}
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(18,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(23,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" ".$_ENV['THEME_VARS']['CitaExistente'].">&nbsp;</td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			if(date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(23,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
        $cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\">&nbsp;</td>";
			}
		}
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		return true;
	}

	function ValidacionDiaSabado($mes,$dias,$year,$citas,$cita)
	{
		$s=$this->BusquedaDiaFestivo(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year)));
		if($s!=0)
		{
			$this->salida .= "          <table border=\"1\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
		}
		else
		{
			$this->salida .= "          <table border=\"1\" width=\"98%\" align=\"center\" class=\"agendasab\">";
		}
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"20%\" rowspan=\"2\">". date("d",mktime(0,0,0,$mes,$dias,$year)). "</td>";

		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(0,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(0,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" align=\"right\" ".$_ENV['THEME_VARS']['CitaExistente']."><font color=\"#ffffff\">&nbsp;</font></td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			if(date("Y-m-d H",mktime(0,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
      $cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\" align=\"right\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" align=\"right\">&nbsp;</td>";
			}
		}
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(6,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" ".$_ENV['THEME_VARS']['CitaExistente'].">&nbsp;</td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			if(date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
			$cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\">&nbsp;</td>";
			}
		}
		$this->salida .= "</tr><tr>";
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(12,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" ".$_ENV['THEME_VARS']['CitaExistente'].">&nbsp;</td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			if(date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
			$cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\">&nbsp;</td>";
			}
		}
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(18,0,0,$mes,$dias,$year));
    if(date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(23,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" ".$_ENV['THEME_VARS']['CitaExistente'].">&nbsp;</td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			if(date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(23,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
      $cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\">&nbsp;</td>";
			}
		}
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		return true;
	}

	function ValidacionDiaDomingo($mes,$dias,$year,$citas,$cita)
	{
		$this->salida .= "<table border=\"1\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"20%\" rowspan=\"2\">". date("d",mktime(0,0,0,$mes,$dias,$year)). "</td>";

		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(0,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(0,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" align=\"right\" ".$_ENV['THEME_VARS']['CitaExistente']."><font color=\"#ffffff\">&nbsp;</font></td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			if(date("Y-m-d H",mktime(0,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
			$cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\" align=\"right\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" align=\"right\">&nbsp;</td>";
			}
		}
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(6,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" ".$_ENV['THEME_VARS']['CitaExistente'].">&nbsp;</td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
      if(date("Y-m-d H",mktime(6,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
			$cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\">&nbsp;</td>";
			}
		}
    $this->salida .= "</tr><tr>";
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(12,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" ".$_ENV['THEME_VARS']['CitaExistente'].">&nbsp;</td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
      if(date("Y-m-d H",mktime(12,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
			$cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\">&nbsp;</td>";
			}
		}
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d H:i",mktime(18,0,0,$mes,$dias,$year));
		if(date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(23,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		{
			$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\" ".$_ENV['THEME_VARS']['CitaExistente'].">&nbsp;</td>";
			$cita++;
			list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
      if(date("Y-m-d H",mktime(18,0,0,$mes,$dias,$year))<=date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and date("Y-m-d H",mktime(23,0,0,$mes,$dias,$year))>date("Y-m-d H",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])) and !empty($citas[$cita]))
		  {
			$cita++;
			}
		}
		else
		{
			if(empty($_REQUEST['click']))
			{
				$this->salida .= "<td width=\"40%\">&nbsp;</td>";
			}
			else
			{
				$this->salida .= "<td width=\"40%\" onclick=\"cita('".$fechaespecial."')\">&nbsp;</td>";
			}
		}
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		return true;
	}


	function CalendarioConsulta()
	{
	  if(empty($_REQUEST['year']))
		{
			$year=date("Y");
		}
		else
		{
			$year=$_REQUEST['year'];
		}
    //Se halla la variable $diasini que se encarga de encontrar el dia en el que inicia el mes
		//este dia se halla en formato %A que indica el dia de la semana en palabra
		if($year==date("Y"))
		{
			if(empty($_REQUEST['meses']))
			{
				$diasini=strftime("%A",mktime(0,0,0,date("m"),1,$year));
			}
			else
			{
				$diasini=strftime("%A",mktime(0,0,0,$_REQUEST['meses'],1,$year));

			}
		}
		else
		{
			$diasini=strftime("%A",mktime(0,0,0,$_REQUEST['meses'],1,$year));
		}


		$t=0;
		if(empty($_REQUEST['meses']))
		{
			$mes=date("m");
		}
		else
		{
			$mes=$_REQUEST['meses'];
		}
		$citas=SessionGetVar('CITASMES');
    //Se halla la variable $diasfin que se encarga de encontrar el dia en el que finaliza el mes
		//este dia se halla en formato %t que indica el numero del dia
		$diasfin=date("t",mktime(0,0,0,$mes,1,$year));
		$dias=1;
		$this->salida .= '<script>';
		$this->salida .= 'function cita(dato)';
		$this->salida .= '{';
		$this->salida .= 'window.location="Contenido.php?DiaEspe="+dato+"';
		foreach($_REQUEST as $value=>$dato)
		{
			if($value!='todos' and $value!='DiaEspe')
			{
				$this->salida .= '&'.$value.'='.$dato;
			}
		}
		$this->salida .= '";';
		$this->salida .= '}';
		$this->salida .= '</script>';
		$this->salida .= "        <table border=\"1\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		$this->salida .= "          <td width=\"13%\">LUNES</td>";
		$this->salida .= "          <td width=\"13%\">MARTES</td>";
		$this->salida .= "          <td width=\"13%\">MIÉRCOLES</td>";
		$this->salida .= "          <td width=\"13%\">JUEVES</td>";
		$this->salida .= "          <td width=\"13%\">VIERNES</td>";
		$this->salida .= "          <td width=\"13%\">SABADO</td>";
		$this->salida .= "          <td width=\"13%\">DOMINGO</td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td>";
		$i=0;
		$cita=0;
		while($i<sizeof($citas))
		{
			list($a,$b,$c)=$this->PartirFecha($citas[$i]);
			if($mes==date("m",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])))
			{
				break;
			}
			else
			{
				$cita++;
			}
			$i++;
		}
		if($diasini=='lunes')
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasini=='martes' or $diasini=='lunes')
		{
      $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        <td>";
		if($diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
      $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
      $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasini=='viernes' or $diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
      $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasini=='sábado' or $diasini=='viernes' or $diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
			$this->ValidacionDiaSabado($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasini='domingo' or $diasini='sábado' or $diasini=='viernes' or $diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
			$this->ValidacionDiaDomingo($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
      $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        <td>";
		if($diasfin!=0)
		{
      $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDiaSabado($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
	  if($diasfin!=0)
		{
			$this->ValidacionDiaDomingo($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td>";
		if($diasfin!=0)
		{
      $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        <td>";
  	if($diasfin!=0)
		{
      $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
	  if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDiaSabado($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDiaDomingo($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td>";
   	if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDiaSabado($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDiaDomingo($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
    if($diasfin!=0)
		{
      $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
		  $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
      $this->ValidacionDiaSabado($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
			$this->ValidacionDiaDomingo($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td>";
		if($diasfin!=0)
		{
		  $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
		  $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        <td>";
		if($diasfin!=0)
		{
		  $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
		  $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
		  $this->ValidacionDia($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
		if($diasfin!=0)
		{
      $this->ValidacionDiaSabado($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
		$this->salida .= "        <td>";
    if($diasfin!=0)
		{
			$this->ValidacionDiaDomingo($mes,$dias,$year,&$citas,&$cita);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>";
    $this->salida .= "        </tr>";
    $this->salida .= "			   </table>";
    return true;
	}


	function ValidacionCalendario($mes,$dias,$year,&$citas,&$cita,&$link)
	{
		$s=$this->BusquedaDiaFestivo(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year)));
		if($s!=0)
		{
		  if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
			{
			  $clase = "DiaHoyUn";
        if(empty($_REQUEST['DiaEspe']) or $_REQUEST['DiaEspe']==date("Y-m-d"))
				  $clase = "DiaHoy";
				
        $this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"".$clase."\" >\n";
			}
			else
			{
			  $clase = "agendadomfes";
        if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==$_REQUEST['DiaEspe'])
				  $clase = "DiaHoy";
				
			  $this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"".$clase."\">\n";
			}
		}
		else
		{
			if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
			{
			  $clase = "DiaHoyUn";
        if(empty($_REQUEST['DiaEspe']) or $_REQUEST['DiaEspe']==date("Y-m-d"))
				  $clase = "DiaHoy";
          
				$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"".$clase."\" >\n";
			}
			else
			{
			  $clase = "modulo_table";
        if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==$_REQUEST['DiaEspe'])
          $clase = "DiaHoy";
        $this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"".$clase."\" >\n";
			}
		}
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d",mktime(0,0,0,$mes,$dias,$year));
		if($citas[$cita]==$fechaespecial)
		{
			$this->salida .= "  <tr>\n";
      $this->salida .= "    <td align=\"center\" ".$_ENV['THEME_VARS']['CitaExistente'].">\n";
	  $iframe = $_REQUEST['iframe'];
	  if($iframe)
	  {
      //$this->salida .= "      <a class=\"hcLink\" href=\"#\" onclick=\"xajax_ValidarTiempoCita('".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
	  $this->salida .= "      <a class=\"hcLink\" href=\"javascript:parent.modiframe('".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');\" >".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>";
	  }else{
      $this->salida .= "      <a class=\"hcLink\" href=\"".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."\" >".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>";
	   }
      //Modificacion OPORTUNIDAD
      $fecha_oportunidad = $citas[$cita];
      $departamento_oportunidad = $_SESSION['AsignacionCitas']['departamento'];
      $tipo_cita_oportunidad = $_SESSION['AsignacionCitas']['cita'];
      $especialidad_oportunidad = $this->EspecialidadTipoConsulta($tipo_cita_oportunidad);
      $arreglo_profesional = explode("," , $_SESSION['AsignacionCitas']['profesional']);
      $profesional_oportunidad = $arreglo_profesional[0];
      $this->salida .= "&nbsp<font color=\"FFFF00\"><b>".$this->DesplegarOportunidad($fecha_oportunidad,$departamento_oportunidad,$especialidad_oportunidad,$profesional_oportunidad)."</b></font></td>\n";
      $this->salida .= "  </tr>\n";
			$cita++;
		}
		else
		{
			$this->salida .= "  <tr>\n";
      $this->salida .= "    <td align=\"center\">\n";
		$iframe = $_REQUEST['iframe'];
		if($iframe)
		{
			$this->salida .= "      <a class=\"label_error\" href=\"javascript:parent.modiframe('".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');\" >".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
		}else{
			$this->salida .= "      <a class=\"label_error\" href=\"".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."\" >".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
		}
      $this->salida .= "    </td>\n";
      $this->salida .= "  </tr>\n";
		}
		$this->salida .= "</table>\n";
		return true;
	}

function ValidacionCalendarioSabado($mes,$dias,$year,&$citas,&$cita,&$link)
{
			$s=$this->BusquedaDiaFestivo(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year)));
			if($s!=0)
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					if(empty($_REQUEST['DiaEspe']) or $_REQUEST['DiaEspe']==date("Y-m-d"))
					{
						$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
					}
					else
					{
						$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoyUn\">";
					}
				}
				else
				{
					if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==$_REQUEST['DiaEspe'])
					{
						$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
					}
					else
					{
						$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
					}
				}
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					if(empty($_REQUEST['DiaEspe']) or $_REQUEST['DiaEspe']==date("Y-m-d"))
					{
						$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
					}
					else
					{
						$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoyUn\">";
					}
				}
				else
				{
				  if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==$_REQUEST['DiaEspe'])
					{
						$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
					}
					else
					{
						$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"agendasab\">";
					}
				}
			}
      list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
			$fechaespecial=date("Y-m-d",mktime(0,0,0,$mes,$dias,$year));
			if($citas[$cita]==$fechaespecial)
			{
				$this->salida .= "  <tr>\n";
        $this->salida .= "    <td align=\"center\" ".$_ENV['THEME_VARS']['CitaExistente'].">\n";
		$iframe = $_REQUEST['iframe'];
	  if($iframe)
      {
        //$this->salida .= "      <a class=\"hcLink\" href=\"#\" onclick=\"xajax_ValidarTiempoCita('".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
			$this->salida .= "      <a class=\"hcLink\" href=\"javascript:parent.modiframe('".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');\" >".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
		}else{
			$this->salida .= "      <a class=\"hcLink\" href=\"".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."\" >".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
        }
        //Modificacion OPORTUNIDAD
        $fecha_oportunidad = $citas[$cita];
        $departamento_oportunidad = $_SESSION['AsignacionCitas']['departamento'];
        $tipo_cita_oportunidad = $_SESSION['AsignacionCitas']['cita'];
        $especialidad_oportunidad = $this->EspecialidadTipoConsulta($tipo_cita_oportunidad);
        $arreglo_profesional = explode("," , $_SESSION['AsignacionCitas']['profesional']);
        $profesional_oportunidad = $arreglo_profesional[0];
        $this->salida .= "&nbsp<font color=\"FFFF00\"><b>".$this->DesplegarOportunidad($fecha_oportunidad,$departamento_oportunidad,$especialidad_oportunidad,$profesional_oportunidad)."</b></font></td>\n";
        $this->salida .= "  </tr>\n";
				$cita++;
			}
			else
			{
				$this->salida .= "  <tr>\n";
        $this->salida .= "    <td align=\"center\">\n";
		$iframe = $_REQUEST['iframe'];
		if($iframe)
		{
			$this->salida .= "      <a class=\"label_error\" href=\"javascript:parent.modiframe('".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');\" >".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
		}else{
			$this->salida .= "      <a class=\"label_error\" href=\"".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."\" >".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
		}
		
        $this->saliad .= "    </td>\n";
        $this->salida .= "  </tr>\n";
			}
			$this->salida .= "</table>";
}

function ValidacionCalendarioDomingo($mes,$dias,$year,&$citas,&$cita,&$link)
{
		if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
		{
			if(empty($_REQUEST['DiaEspe']) or $_REQUEST['DiaEspe']==date("Y-m-d"))
			{
				$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
			}
			else
			{
				$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoyUn\">";
			}
		}
		else
		{
		  if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==$_REQUEST['DiaEspe'])
			{
				$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
			}
			else
			{
				$this->salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
		}
		list($a,$b,$c)=$this->PartirFecha($citas[$cita]);
		$fechaespecial=date("Y-m-d",mktime(0,0,0,$mes,$dias,$year));
		if($citas[$cita]==$fechaespecial)
		{
			$this->salida .= "  <tr>\n";
      $this->salida .= "    <td align=\"center\" ".$_ENV['THEME_VARS']['CitaExistente'].">\n";
      $iframe = $_REQUEST['iframe'];
	  if($iframe)
      {
	  $this->salida .= "      <a class=\"hcLink\" href=\"javascript:parent.modiframe('".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');\">".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
	   }else{
	   $this->salida .= "      <a class=\"hcLink\" href=\"".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."\">".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
	   }
      //$this->salida .= "      <a class=\"hcLink\" href=\"#\" onclick=\"xajax_ValidarTiempoCita('".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
      //Modificacion OPORTUNIDAD
      $fecha_oportunidad = $citas[$cita];
      $departamento_oportunidad = $_SESSION['AsignacionCitas']['departamento'];
      $tipo_cita_oportunidad = $_SESSION['AsignacionCitas']['cita'];
      $especialidad_oportunidad = $this->EspecialidadTipoConsulta($tipo_cita_oportunidad);
      $arreglo_profesional = explode("," , $_SESSION['AsignacionCitas']['profesional']);
      $profesional_oportunidad = $arreglo_profesional[0];
      $this->salida .= "&nbsp<font color=\"FFFF00\"><b>".$this->DesplegarOportunidad($fecha_oportunidad,$departamento_oportunidad,$especialidad_oportunidad,$profesional_oportunidad)."</b></font></td>\n";
      $this->salida .= "  </tr>\n";
			$cita++;
		}
		else
		{
			$this->salida .= "  <tr>\n";
      $this->salida .= "    <td align=\"center\">\n";
		$iframe = $_REQUEST['iframe'];
		if($iframe)
		{
			$this->salida .= "      <a class=\"label_error\" href=\"javascript:parent.modiframe('".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');\">".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
		}else{
			$this->salida .= "      <a class=\"label_error\" href=\"".$link."&DiaEspe=".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."\">".date("d",mktime(0,0,0,$mes,$dias,$year))."</a>\n";
		}
      $this->salida .= "    </td>\n";
      $this->salida .= "  </tr>\n";
		}
		$this->salida .= "</table>";
}

//esta es la otra funcion
	function CalendarioEstandard()
	{
    
    if(empty($_REQUEST['year']))
		{
			$_REQUEST['year']=$year=date("Y");
		}
		else
		{
			$year=$_REQUEST['year'];
		}
		if($year==date("Y"))
		{
			if(empty($_REQUEST['meses']))
			{
				$diasini=strftime("%A",mktime(0,0,0,date("m"),1,$year));
			}
			else
			{
				$diasini=strftime("%A",mktime(0,0,0,$_REQUEST['meses'],1,$year));
			}
		}
		else
		{
			$diasini=strftime("%A",mktime(0,0,0,$_REQUEST['meses'],1,$year));
		}
		$t=0;
		if(empty($_REQUEST['meses']))
		{
			$mes=date("m");
		}
		else
		{
			$mes=$_REQUEST['meses'];
		}
		$citas=SessionGetVar('CITASMES');
		$diasfin=date("t",mktime(0,0,0,$mes,1,$year));
		list($dbconn) = GetDBconn();
		$dias=1;
		$this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$this->salida .= "        <tr class=\"modulo_table_calen\">\n";
		$this->salida .= "          <td width=\"14%\">LUNES</td>\n";
		$this->salida .= "          <td width=\"14%\">MARTES</td>\n";
		$this->salida .= "          <td width=\"14%\">MIÉRCOLES</td>\n";
		$this->salida .= "          <td width=\"14%\">JUEVES</td>\n";
		$this->salida .= "          <td width=\"14%\">VIERNES</td>\n";
		$this->salida .= "          <td width=\"14%\">SABADO</td>\n";
		$this->salida .= "          <td width=\"15%\">DOMINGO</td>\n";
    $this->salida .= "        </tr>\n";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">\n";
		$this->salida .= "          <td>\n";
		$i=0;
		$cita=0;
    
	
		$vec = array();
		
		/*
		foreach($_REQUEST as $v=>$v1)
		{
		  if($v=='Direccion'){
        $v1=urlencode($v1);
			}
			if(empty($link))
			{
				if($v!='DiaEspe')
				{
				  $link="Contenido.php?";
				  if (is_array($v1)) {
						foreach($v1 as $k2=>$v2) {
							if (is_array($v2)) {
								foreach($v2 as $k3=>$v3) {
									if (is_array($v3)) {
										foreach($v3 as $k4=>$v4) {
											$link .= "$v" . "[$k2][$k3][$k4]=$v4";
										}
									}else{
										$link .= "$v" . "[$k2][$k3]=$v3";
									}
								}
							}else{
								$link .= "$v" . "[$k2]=$v2";
							}
						}
					} else {
						$link .= "$v=$v1";
					}
				}
			}
			else
			{
				if($v!='DiaEspe')
				{
				  if($v=='Direccion'){
						$v1=urlencode($v1);
					}
					if (is_array($v1)) {
						foreach($v1 as $k2=>$v2) {
							if (is_array($v2)) {
								foreach($v2 as $k3=>$v3) {
									if (is_array($v3)) {
										foreach($v3 as $k4=>$v4) {
											$link .= "&$v" . "[$k2][$k3][$k4]=$v4";
										}
									}else{
										$link .= "&$v" . "[$k2][$k3]=$v3";
									}
								}
							}else{
								$link .= "&$v" . "[$k2]=$v2";
							}
						}
					} else {
						$link .= "&$v=$v1";
					}
				}
			}
		}*/
		foreach($_REQUEST as $v=>$v1)
		{
			if( $v!='DiaEspe' and $v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
			{
				$vec[$v]=$v1;
			}
		}
		$iframe = $_REQUEST['iframe'];
		if($iframe){
			$accion=ModuloGetURL('app',$_REQUEST['modulo'],'','EscogerBusqueda',$vec);
			$link = $accion;
		}else{
			$accion=ModuloGetURL('app',$_REQUEST['modulo'],'',$_REQUEST['metodo'],$vec);
			$link = $accion;
		}
		
		
		while($i<sizeof($citas))
		{
		  list($a,$b,$c)=$this->PartirFecha($citas[$i]);
			if($mes==date("m",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])))
			{
				break;
			}
			else
			{
				$cita++;
			}
			$i++;
		}

		if($diasini=='lunes' || $diasini == 'Monday')
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
	  if($diasini=='martes' or $diasini=='Tuesday' or $diasini=='lunes' or $diasini == 'Monday')
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
    $this->salida .= "        <td>\n";
		if($diasini=='miércoles' or $diasini=='Wednesday' or $diasini=='martes' or $diasini=='Tuesday' or $diasini=='lunes' or $diasini == 'Monday')
		{
      $this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasini=='jueves' or $diasini== 'Thursday' or $diasini=='miércoles' or $diasini=='Wednesday' or $diasini=='martes' or $diasini=='Tuesday' or $diasini=='lunes' or $diasini == 'Monday')
		{
      $this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasini=='viernes' or $diasini=='Friday' or $diasini=='jueves' or $diasini== 'Thursday' or $diasini=='miércoles' or $diasini=='Wednesday' or $diasini=='martes' or $diasini=='Tuesday' or $diasini=='lunes' or $diasini == 'Monday')
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
  if($diasini=='sábado' or $diasini=='viernes' or $diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
			$this->ValidacionCalendarioSabado($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
  if($diasini='domingo' or $diasini='sábado' or $diasini=='viernes' or $diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
			$this->ValidacionCalendarioDomingo($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "          </td>\n";
    $this->salida .= "        </tr>\n";
		$this->salida .= "        <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "          <td>\n";
		if($diasfin!=0)
		{
      $this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "          </td>\n";
		$this->salida .= "          <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
    $this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
     	$this->ValidacionCalendarioSabado($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendarioDomingo($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "          </td>\n";
    $this->salida .= "        </tr>\n";
		$this->salida .= "        <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "          <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
    $this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
      $dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendarioSabado($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendarioDomingo($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "          </td>\n";
    $this->salida .= "        </tr>\n";
		$this->salida .= "        <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "          <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
    $this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendarioSabado($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendarioDomingo($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "          </td>\n";
    $this->salida .= "        </tr>\n";
		$this->salida .= "        <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "          <td>\n";
		if($diasfin!=0)
		{
      $this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
    $this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendarioSabado($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendarioDomingo($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "          </td>\n";
    $this->salida .= "        </tr>\n";
		$this->salida .= "        <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "          <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
    $this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendario($mes,$dias,$year,$citas,$cita,$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
   		$this->ValidacionCalendarioSabado($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "        </td>\n";
		$this->salida .= "        <td>\n";
		if($diasfin!=0)
		{
			$this->ValidacionCalendarioDomingo($mes,$dias,$year,&$citas,&$cita,&$link);
			$dias++;
			$diasfin--;
		}
		$this->salida .= "          </td>\n";
    $this->salida .= "        </tr>\n";
    $this->salida .= "</table>\n";
    return true;
	}

	function FechaStamp($fecha)
	{
    if($fecha){
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
    }
  }

/**
* Funcion que se encarga de separar la hora del formato timestamp
* @return array
*/

  function HoraStamp($hora)
	{
   $hor = strtok ($hora," ");
   for($l=0;$l<4;$l++)
	 {
		 $time[$l]=$hor;
     $hor = strtok (":");
	 }
   return  $time[1].":".$time[2].":".$time[3];
 }

	function CalendarioConsultaDia()
	{
    $this->salida .= "<SCRIPT>";
		$this->salida .= "function IntervalosCheck(frm,x){";
		$this->salida .= "  document.formaProgramacionQx.vectorReserv1.value=' ';";
		$this->salida .= "  document.formaProgramacionQx.vectorReserv.value=' ';";
		$this->salida .= "  document.formaProgramacionQx2.vectorReserv.value=' ';";
		$this->salida .= "  var ArrayElements= new Array();";
		$this->salida .= "  var ArrayValores= new Array();";
    $this->salida .= "  var ArrayTodosValores= new Array();";
		$this->salida .= "  var bandera=1;";
    $this->salida .= "  var i=0;";
		$this->salida .= "  var numElements=0;";
    $this->salida .= "  var j=0;";
		$this->salida .= "  var w=0;";
		//Cuenta el numero de Valores checkeados y coloca el numero del objeto en el vector
		$this->salida .= "  for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "    if(frm.elements[i].checked){";
		$this->salida .= "      numElements=numElements+1;";
		$this->salida .= "      ArrayElements[j]=i;";
    $this->salida .= "      ArrayValores[j]=frm.elements[i].value;";
    $this->salida .= "      j++;";
    $this->salida .= "    }";
		$this->salida .= "  }";
		//Coge la hora y minutosdel primer valor checkeado
    $this->salida .= "  var PrimerValor=ArrayValores[0];";
		$this->salida .= "  vector1=PrimerValor.split(' ');";
		$this->salida .= "  var fecha=vector1[0];";
		$this->salida .= "  var tiempo=vector1[1];";
    $this->salida .= "  vector2=tiempo.split(':');";
		$this->salida .= "  horaValor=vector2[0];";
    $this->salida .= "  minutosValor=vector2[1];";
		$this->salida .= "  var minutosAnt=minutosValor;";
		$this->salida .= "  var horaAnt=horaValor;";
		//Si hay mas de un valor checkeado recorre los elementos y lo checkea y compera si los intervalos son seguidos
    $this->salida .= "  if(numElements >= 2){";
    $this->salida .= "    for(i=ArrayElements[0];i<=ArrayElements[j-1];i++){";
		$this->salida .= "      if(bandera==1){";
    $this->salida .= "        var cadena=frm.elements[i].value;";
		$this->salida .= "        vector3=cadena.split(' ');";
		$this->salida .= "        fecha1=vector3[0];";
		$this->salida .= "        tiempo1=vector3[1];";
    $this->salida .= "        vector4=tiempo1.split(':');";
    $this->salida .= "        hora=vector4[0];";
    $this->salida .= "        minutos=vector4[1];";
    $this->salida .= "        if(hora == horaAnt && minutos == minutosAnt){";
    $this->salida .= "          frm.elements[i].checked=true;";
    $this->salida .= "          ArrayTodosValores[w]=frm.elements[i].value;";
    $this->salida .= "          w++;";
		$this->salida .= "          horaAnt=hora;";
    $this->salida .= "          minutosAnt=minutos;";
		$this->salida .= "        }else{";
    $this->salida .= "          bandera=0;";
		$this->salida .= "          alert ('no es Posible Reservar este Intervalo');";
		$this->salida .= "          for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "            frm.elements[i].checked=false;";
		$this->salida .= "          }";
		$this->salida .= "          ArrayTodosValores=new Array();";
		//Linpia los hiddens si los intervalos son eeroneos
		$this->salida .= "          document.formaProgramacionQx2.vectorReserv1.value=' ';";
		$this->salida .= "          document.formaProgramacionQx2.vectorReserv.value=' ';";
		$this->salida .= "          document.formaProgramacionQx.vectorReserv1.value=' ';";
		$this->salida .= "          document.formaProgramacionQx.vectorReserv.value=' ';";
		$this->salida .= "        }";
		$this->salida .= "        horaAnt=hora;";
		$this->salida .= "        minutosAnt=Number(minutosAnt)+Number(x);";
		$this->salida .= "        if(minutosAnt==60){";
		$this->salida .= "          horaAnt=Number(horaAnt)+Number(1);";
    $this->salida .= "          if(horaAnt==24){";
    $this->salida .= "            horaAnt=00;";
		$this->salida .= "          }";
    $this->salida .= "          minutosAnt=00;";
		$this->salida .= "        }";
    $this->salida .= "      }";
		$this->salida .= "    }";
    $this->salida .= "  }";
		$this->salida .= "  if(numElements >= 2 && bandera!=0){";
		//Coloca los hiddens en los hiddens
    $this->salida .= "    document.formaProgramacionQx2.vectorReserv.value=ArrayTodosValores;";
		$this->salida .= "    document.formaProgramacionQx2.vectorReserv1.value+=ArrayTodosValores+',';";
    $this->salida .= "    document.formaProgramacionQx.vectorReserv.value=ArrayTodosValores;";
		$this->salida .= "    document.formaProgramacionQx.vectorReserv1.value+=ArrayTodosValores+',';";
		//$this->salida .= "    document.formaProgramacionQx.vectorReserv1.value+=document.formaProgramacionQx.vectorReserv.value+',';";
		$this->salida .= "  }";
		$this->salida .= "}";
		$this->salida .= "</SCRIPT>";
		$i=0;
		$intervalo=$_REQUEST['intervalo'];
		$z=0;
		$c=$_REQUEST['iniminutos'];
		$x=$_REQUEST['interval'];
		$optioncheckbox=$_REQUEST['opciones'];
		//1 consulta
		//2 ocupados
		//3 desocupados
		//4 todos
		$FechaProgramacion=$_REQUEST['DiaEspe'];
		$infoCadena = explode ('-', $FechaProgramacion);
		$diaProgram=$infoCadena[2];
		$mesProgram=$infoCadena[1];
		$anoProgram=$infoCadena[0];
		$turnos=$_SESSION['CITASDIA'];
    array_multisort($turnos);
		if(empty($_REQUEST['ocupado']))
		{
			$ocupado='#304E8E';
		}
		else
		{
			$ocupado='#F3F3E9';
		}
		$desocupado='#D6DFF7';
		if($x%2)
		{
			$d=0;
		}
		else
		{
			$d=($c/5);
		}
    if($intervalo==1){
			$HoraInter='00';
			if($d==0 or $d==2 or $d==4 or $d==6 or $d==8 or $d==10)
			{
				$MinutosInter='00';
			}
			else
			{
				$MinutosInter='05';
			}
			$mesInter=$mesProgram;$diaInter=$diaProgram;$anoInter=$anoProgram;
			$HoraFin='06';$MinutosFin='00';
		}elseif($intervalo==2){
			$HoraInter='06';
			if($d==0 or $d==2 or $d==4 or $d==6 or $d==8 or $d==10)
			{
				$MinutosInter='00';
			}
			else
			{
				$MinutosInter='05';
			}
			$mesInter=$mesProgram;$diaInter=$diaProgram;$anoInter=$anoProgram;
			$HoraFin='12';$MinutosFin='00';
		}elseif($intervalo==3){
		  $HoraInter='12';
			if($d==0 or $d==2 or $d==4 or $d==6 or $d==8 or $d==10)
			{
				$MinutosInter='00';
			}
			else
			{
				$MinutosInter='05';
			}
			$mesInter=$mesProgram;$diaInter=$diaProgram;$anoInter=$anoProgram;
      $HoraFin='18';$MinutosFin='00';
		}elseif($intervalo==4){
		  $HoraInter='18';
			if($d==0 or $d==2 or $d==4 or $d==6 or $d==8 or $d==10)
			{
				$MinutosInter='00';
			}
			else
			{
				$MinutosInter='05';
			}
			$mesInter=$mesProgram;$diaInter=$diaProgram;$anoInter=$anoProgram;
      $HoraFin='00';$MinutosFin='00';
			$diaProgram+=1;$diaProgram=str_pad($diaProgram,2,0,STR_PAD_LEFT);
		}elseif($intervalo==5)
		{
			$HoraInter=$_REQUEST['inihora'];
			if($d==0 or $d==2 or $d==4 or $d==6 or $d==8 or $d==10)
			{
				$MinutosInter='00';
			}
			else
			{
				$MinutosInter='05';
			}
			$mesInter=$mesProgram;$diaInter=$diaProgram;$anoInter=$anoProgram;
			$HoraFin=$_REQUEST['finhora'];$MinutosFin=$_REQUEST['finminutos'];
		}
		$HoraInter1=$HoraInter;
		$MinutosInter1=$MinutosInter;
		//Le quite el igual para que no me saliera el inicio del intervalo en la parte anterior !Leyo Jaime, si algo Digame
		while(date("Y-m-d H:i:s",mktime($HoraInter,$MinutosInter,0,$mesInter,$diaInter,$anoInter)) <= date("Y-m-d H:i:s",mktime($HoraFin,$MinutosFin,0,$mesProgram,$diaProgram,$anoProgram))){
      $intervalos=date("Y-m-d H:i:s",mktime($HoraInter,$MinutosInter,0,$mesInter,$diaInter,$anoInter));
			$arregloIntervalos[$i]=$intervalos;
			$i++;
			$FechaIntervalo=date("Y-m-d H:i:s",mktime($HoraInter,$MinutosInter+$x,0,$mesInter,$diaInter,$anoInter));
			$Fecha=$this->FechaStamp($FechaIntervalo);
			$infoCadena = explode ('/', $Fecha);
			$diaInter=$infoCadena[0];
			$mesInter=$infoCadena[1];
			$anoInter=$infoCadena[2];
			$Hora=$this->HoraStamp($FechaIntervalo);
			$infoCadena = explode (':', $Hora);
			$HoraInter=$infoCadena[0];
			$MinutosInter=$infoCadena[1];
		}
		$bandera=1;
		while($bandera==1){
			$turnoIntervalo=$turnos[$z];
			$Fecha=$this->FechaStamp($turnoIntervalo);
			$infoCadena = explode ('/', $Fecha);
			$diaInter=$infoCadena[0];
			$mesInter=$infoCadena[1];
			$anoInter=$infoCadena[2];
			$Hora=$this->HoraStamp($turnoIntervalo);
			$infoCadena = explode (':', $Hora);
			$HoraInter=$infoCadena[0];
			$MinutosInter=$infoCadena[1];
			$SegundosInter=$infoCadena[2];
   if(date("Y-m-d H:i:s",mktime($HoraInter,$MinutosInter,0,$mesInter,$diaInter,$anoInter)) >= date("Y-m-d H:i:s",mktime($HoraInter1,$MinutosInter1,0,$mesInter,$diaInter,$anoInter))){
				 $bandera=0;
			}else{
        $z++;
			}
		}
    $this->salida .= "			<br><br>";
		$this->salida .= "        <table border=\"1\" width=\"90%\" align=\"center\" class=\"modulo_table\">";
    $this->salida .= "        <tr class=\"modulo_table_title\">";
		for($i=0;$i<sizeof($arregloIntervalos);$i++){
      (list($date,$hours)=explode(' ',$arregloIntervalos[$i]));
      (list($hour,$minuts)=explode(':',$hours));
      if($i==0){
        $hourFirst=$hour;
			}
      if($hour==$hourFirst){
        $contadorHours++;
			}
		}
    $this->salida .= "        <td width=\"5%\">Hora</td>";
		$this->salida .= "        <td colspan=\"".$contadorHours."\">Minutos</td>";
		$this->salida .= "        </tr>";
		$con=$_SESSION['Agenda']['contador'];
		$HoraInterConAnt=-1;
		$bandera=0;
		$primeraHora=1;
		$contador=0;
		$contadorPrimeraHora=1;
		$primeraVez=-1;
    for($i=0;$i<sizeof($arregloIntervalos);$i++){
      $InterConsecutivo=$arregloIntervalos[$i];
      $Fecha=$this->FechaStamp($InterConsecutivo);
			$infoCadena = explode ('/', $Fecha);
			$diaInterCon=$infoCadena[0];
			$mesInterCon=$infoCadena[1];
			$anoInterCon=$infoCadena[2];
			$HoraEsta=$this->HoraStamp($InterConsecutivo);
			$infoCadena = explode (':', $HoraEsta);
			$HoraInterCon=$infoCadena[0];
			$MinutosInterCon=$infoCadena[1];
			$SegundosInterCon=$infoCadena[2];
      $turnoIntervalo=$turnos[$z];
			$Fecha=$this->FechaStamp($turnoIntervalo);
			$infoCadena = explode ('/', $Fecha);
			$diaInter=$infoCadena[0];
			$mesInter=$infoCadena[1];
			$anoInter=$infoCadena[2];
			$Hora=$this->HoraStamp($turnoIntervalo);
			$infoCadena = explode (':', $Hora);
			$HoraInter=$infoCadena[0];
			$MinutosInter=$infoCadena[1];
			$SegundosInter=$infoCadena[2];
			if($HoraInterCon!=$HoraInterConAnt){
			  if($bandera==1){
        $this->salida .="         </tr>";
				}
				$this->salida .="         <tr>";
				$this->salida .="         <td bgcolor=\"#F3F3E9\">$HoraInterCon</td>";
				$bandera=1;
			}
			if(date("Y-m-d H:i:s",mktime($HoraInter,$MinutosInter,0,$mesInter,$diaInter,$anoInter)) == date("Y-m-d H:i:s",mktime($HoraInterCon,$MinutosInterCon,0,$mesInterCon,$diaInterCon,$anoInterCon))){
				//$this->salida .= "          <td bgcolor=\"$desocupado\">$MinutosInterCon</td>";
        if($optioncheckbox==3 || $optioncheckbox==4){
				  if(empty($_REQUEST['diferencia']))
					{
            $this->salida .= "        <td valign=\"top\" bgcolor=\"$desocupado\" width=\"5%\">$MinutosInterCon&nbsp&nbsp&nbsp;<input type=\"checkbox\" name=\"seleccion$con\" value=\"".$HoraInterCon.":".$MinutosInterCon."\" onclick=\"IntervalosCheck(this.form,$x)\"></td>";
					}
					else
					{
            $Fecha=$this->FechaStamp($_REQUEST['DiaEspe']);
						$infoCadena = explode ('/', $Fecha);
						$diaEspe=$infoCadena[0];
						$mesEspe=$infoCadena[1];
						$anoEspe=$infoCadena[2];
						$FechaTotal=date("Y-m-d",mktime(0,0,0,$mesEspe,$diaEspe,$anoEspe));
						$varia=$FechaTotal.' '.$HoraInterCon.':'.$MinutosInterCon;
						$chequeado='';
						if($_SESSION['IntervalosQX'][$varia]==1)
						{
							$chequeado='checked';
						}
					  $this->salida .= "        <td valign=\"top\" bgcolor=\"$desocupado\" width=\"5%\">$MinutosInterCon&nbsp&nbsp&nbsp;<input type=\"checkbox\" name=\"seleccion$con\" value=\"".$FechaTotal." ".$HoraInterCon.":".$MinutosInterCon."\" onclick=\"IntervalosCheck(this.form,$x)\" $chequeado></td>";
					}
				}else{
          $this->salida .= "        <td bgcolor=\"$desocupado\" width=\"5%\">$MinutosInterCon</td>";
				}
				$z++;
			}else{
        if($optioncheckbox==2 || $optioncheckbox==4){
          $this->salida .= "        <td valign=\"top\" bgcolor=\"$ocupado\" width=\"5%\"><font color=\"#D6D5D5\">$MinutosInterCon</font>&nbsp&nbsp&nbsp;<input type=\"checkbox\" name=\"seleccion$con\" value=\"".$HoraInterCon.":".$MinutosInterCon."\" onclick=\"IntervalosCheck(this.form,$x)\"></td>";
				}else{
          $this->salida .= "        <td bgcolor=\"$ocupado\" width=\"5%\"><font color=\"#D6D5D5\">$MinutosInterCon</font></td>";
				}
			}
			$con++;
			$HoraInterConAnt=$HoraInterCon;
		}
    $this->salida .= "        </table>";
    $_SESSION['Agenda']['contador']=$con;




		/*$this->salida .= "			<br><br>";
		$this->salida .= "        <table border=\"1\" width=\"90%\" align=\"center\" class=\"modulo_table\">";
		$con=$_SESSION['Agenda']['contador'];
    for($i=0;$i<sizeof($arregloIntervalos);$i++){
      $InterConsecutivo=$arregloIntervalos[$i];
      $Fecha=$this->FechaStamp($InterConsecutivo);
			$infoCadena = explode ('/', $Fecha);
			$diaInterCon=$infoCadena[0];
			$mesInterCon=$infoCadena[1];
			$anoInterCon=$infoCadena[2];
			$HoraEsta=$this->HoraStamp($InterConsecutivo);
			$infoCadena = explode (':', $HoraEsta);
			$HoraInterCon=$infoCadena[0];
			$MinutosInterCon=$infoCadena[1];
			$SegundosInterCon=$infoCadena[2];
      $turnoIntervalo=$turnos[$z];
			$Fecha=$this->FechaStamp($turnoIntervalo);
			$infoCadena = explode ('/', $Fecha);
			$diaInter=$infoCadena[0];
			$mesInter=$infoCadena[1];
			$anoInter=$infoCadena[2];
			$Hora=$this->HoraStamp($turnoIntervalo);
			$infoCadena = explode (':', $Hora);
			$HoraInter=$infoCadena[0];
			$MinutosInter=$infoCadena[1];
			$SegundosInter=$infoCadena[2];
			if(date("Y-m-d H:i:s",mktime($HoraInter,$MinutosInter,0,$mesInter,$diaInter,$anoInter)) == date("Y-m-d H:i:s",mktime($HoraInterCon,$MinutosInterCon,0,$mesInterCon,$diaInterCon,$anoInterCon))){
				$this->salida .="         <tr bgcolor=\"$desocupado\">";
				$this->salida .= "        <td>$HoraInterCon:$MinutosInterCon</td>";
        if($optioncheckbox==3 || $optioncheckbox==4){
				  if(empty($_REQUEST['diferencia']))
					{
            $this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"seleccion$con\" value=\"".$HoraInterCon.":".$MinutosInterCon."\" onclick=\"IntervalosCheck(this.form,$x)\"></td>";
					}
					else
					{
            $Fecha=$this->FechaStamp($_REQUEST['DiaEspe']);
						$infoCadena = explode ('/', $Fecha);
						$diaEspe=$infoCadena[0];
						$mesEspe=$infoCadena[1];
						$anoEspe=$infoCadena[2];
						$FechaTotal=date("Y-m-d",mktime(0,0,0,$mesEspe,$diaEspe,$anoEspe));
						$varia=$FechaTotal.' '.$HoraInterCon.':'.$MinutosInterCon;
						$chequeado='';
						if($_SESSION['IntervalosQX'][$varia]==1)
						{
							$chequeado='checked';
						}
					  $this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"seleccion$con\" value=\"".$FechaTotal." ".$HoraInterCon.":".$MinutosInterCon."\" onclick=\"IntervalosCheck(this.form,$x)\" $chequeado></td>";
					}
				}else{
          $this->salida .= "        <td width=\"5%\">&nbsp;</td>";
				}
				$this->salida .="         </tr>";
				$z++;
			}else{
				$this->salida .="         <tr bgcolor=\"$ocupado\">";
				$this->salida .= "        <td>$HoraInterCon:$MinutosInterCon</td>";
        if($optioncheckbox==2 || $optioncheckbox==4){
          $this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"seleccion$con\" value=\"".$HoraInterCon.":".$MinutosInterCon."\" onclick=\"IntervalosCheck(this.form,$x)\"></td>";
				}else{
          $this->salida .= "        <td width=\"5%\">&nbsp;</td>";
				}
				$this->salida .="         </tr>";
			}
			$con++;
		}
		$this->salida .= "			  </table>";
		$_SESSION['Agenda']['contador']=$con;
		*/
		return true;
	}
}
?>