<?php

/**
 * $Id: calendario.inc.php,v 1.2 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

//CalendarioEstandard();


	function CalendarioTodos()
	{
		$salida.=ReturnHeader('Calendario');
		$salida.=ReturnBody();
		if(empty($_REQUEST['separador']))
		{
			$_REQUEST['separador']='-';
		}
		$fechas='d'.$_REQUEST['separador'].'m'.$_REQUEST['separador'].'Y';
		$salida.="\n".'<script>'."\n";
		$salida.='function year1(t)'."\n";
		$salida.='{'."\n";
		$salida.='window.location.href="Calendario.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='year' and $v!='meses' and $v!='DiaEspe')
			{
				$salida.='&'.$v.'='.$v1;
			}
		}
		$salida.='";'."\n";
		$salida.='}'."\n";
		$salida.='function funcionir(p)'."\n";
		$salida.='{'."\n";
		$salida.='window.opener.document.'.$_REQUEST['forma'].'.'.$_REQUEST['campo'].'.value=p;'."\n";
		$salida.='window.close();'."\n";
		$salida.='}'."\n";
		$salida.='</script>';
		$salida.="</head>";
		$salida .='<form name="cosa">';
		$salida .= " <br>";
		$salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
		$salida .='<tr align="center">';
		$salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year']))
		{
			$year=$_REQUEST['year']=date("Y");
			$salida.=AnosAgenda(True,$_REQUEST['year']);
		}
		else
		{
			$salida.=AnosAgenda(true,$_REQUEST['year']);
			$year=$_REQUEST['year'];
		}
		$salida .= "</select></td>";
		$salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['meses']))
		{
			$mes=$_REQUEST['meses']=date("m");
			$year=date("Y");
			$salida.=MesesAgenda(True,$year,$mes);
		}
		else
		{
			$salida.=MesesAgenda(True,$year,$_REQUEST['meses']);
			$mes=$_REQUEST['meses'];
		}
		$salida .= "</select>";
		$salida .= "</td>";
		$salida .= "</tr>";
		$salida .= "</table>";
		$salida .='</form>';
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
		$diasfin=date("t",mktime(0,0,0,$mes,1,$year));
		list($dbconn) = GetDBconn();
		$dias=1;
		$salida .= "        <table border=\"0\" width=\"60%\" align=\"center\" cellspacing=\"2\">";
		$salida .= "        <tr class=\"modulo_table_calen\">";
		$salida .= "          <td width=\"5%\">LUN</td>";
		$salida .= "          <td width=\"5%\">MAR</td>";
		$salida .= "          <td width=\"5%\">MIE</td>";
		$salida .= "          <td width=\"5%\">JUE</td>";
		$salida .= "          <td width=\"5%\">VIE</td>";
		$salida .= "          <td width=\"5%\">SAB</td>";
		$salida .= "          <td width=\"5%\">DOM</td></tr>";
		$salida .= "        <tr class=\"modulo_list_oscuro\">";
		$salida .= "        <td>";
		$i=0;
		if($diasini=='lunes')
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"1\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasini=='martes' or $diasini=='lunes')
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        <td>";
		if($diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasini=='viernes' or $diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasini=='sábado' or $diasini=='viernes' or $diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"agendasab\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasini='domingo' or $diasini='sábado' or $diasini=='viernes' or $diasini=='jueves' or $diasini=='miércoles' or $diasini=='martes' or $diasini=='lunes')
		{
			if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
			{
				$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
			}
			else
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        </tr>";
		$salida .= "        <tr class=\"modulo_list_claro\">";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendasab\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
			{
				$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
			}
			else
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        </tr>";
		$salida .= "        <tr class=\"modulo_list_claro\">";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendasab\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
			{
				$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
			}
			else
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        </tr>";
		$salida .= "        <tr class=\"modulo_list_claro\">";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendasab\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
			{
				$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
			}
			else
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        </tr>";
		$salida .= "        <tr class=\"modulo_list_claro\">";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendasab\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
			{
				$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
			}
			else
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        </tr>";
		$salida .= "        <tr class=\"modulo_list_claro\">";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			$sql="select count(dia) from dias_festivos where date(dia)=date('".date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))."');";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				echo "hola";
				return false;
			}
			else
			{
				$s=$result->fields[0];
			}
			if($s!=0)
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			else
			{
				if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
				{
					$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
				}
				else
				{
					$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendasab\">";
				}
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
		$salida .= "        <td>";
		if($diasfin!=0)
		{
			if(date("Y-m-d",mktime(0,0,0,$mes,$dias,$year))==date("Y-m-d"))
			{
				$salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"DiaHoy\">";
			}
			else
			{
				$salida .= "          <table border=\"0\" width=\"98%\" align=\"center\" class=\"agendadomfes\">";
			}
			$salida .= "<tr><td align=\"center\"><a href=\"javascript:funcionir('".date($fechas,mktime(0,0,0,$mes,$dias,$year))."')\">".date("d",mktime(0,0,0,$mes,$dias,$year)).'</a></td></tr>';
			$salida .= "</table>";
			$dias++;
			$diasfin--;
		}
		$salida .= "        </td>";
    $salida .= "        </tr>";
    $salida .= "			   </table>";
		$salida .=ReturnFooter();
    return $salida;
	}

	function AnosAgenda($Seleccionado='False',$ano)
	{
		$anoActual=date("Y");
		$a=$anoActual-1970;
		$anoActual1=$anoActual;
		$anoActual1=$anoActual-$a;
		for($i=0;$i<($a+10);$i++)
		{
			$vars[$i]=$anoActual1;
			$anoActual1=$anoActual1+1;
		}
		switch($Seleccionado)
		{
			case 'False':
			{
				foreach($vars as $value=>$titulo)
				{
          if($titulo==$ano)
					{
					  $salida .=" <option value=\"$titulo\" selected>$titulo</option>";
				  }else{
            $salida .=" <option value=\"$titulo\">$titulo</option>";
				  }
				}
				break;
		  }case 'True':
			{
			  foreach($vars as $value=>$titulo)
				{
					if($titulo==$ano)
					{
				    $salida .=" <option value=\"$titulo\" selected>$titulo</option>";
				  }else{
				    $salida .=" <option value=\"$titulo\">$titulo</option>";
					}
				}
				break;
		  }
	  }
		return $salida;
	}

	function MesesAgenda($Seleccionado='False',$Año,$Defecto)
	{
		$anoActual=date("Y");
		$vars[1]='ENERO';
    $vars[2]='FEBRERO';
		$vars[3]='MARZO';
		$vars[4]='ABRIL';
		$vars[5]='MAYO';
		$vars[6]='JUNIO';
		$vars[7]='JULIO';
		$vars[8]='AGOSTO';
		$vars[9]='SEPTIEMBRE';
		$vars[10]='OCTUBRE';
		$vars[11]='NOVIEMBRE';
		$vars[12]='DICIEMBRE';
		$mesActual=date("m");
		switch($Seleccionado)
		{
			case 'False':
			{
			  if($anoActual==$Año)
				{
			    foreach($vars as $value=>$titulo)
					{
						if($value==$Defecto)
						{
							$salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else{
							$salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				else
				{
          foreach($vars as $value=>$titulo)
					{
						if($value==$Defecto)
						{
							$salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else{
									$salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				break;
			}
			case 'True':
			{
			  if($anoActual==$Año)
				{
				  foreach($vars as $value=>$titulo)
					{
						if($value==$Defecto)
						{
							$salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else
						{
							$salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				else
				{
          foreach($vars as $value=>$titulo)
					{
						if($value==$Defecto)
						{
							$salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else
						{
							$salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				break;
			}
		}
		return $salida;
	}
?>
