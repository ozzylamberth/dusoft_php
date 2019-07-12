<?php
	/**************************************************************************************
	* $Id: CalendarioHtml.class.php,v 1.3 2006/04/27 13:26:32 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	class CalendarioHtml
	{		
		var $dia_solo_hoy;
		var $tiempo_actual;
		var $numerodiasemana;
		/********************************************************************************
		* Constructor
		*********************************************************************************/
		function CalendarioHtml()
		{
			$this->tiempo_actual = time();
			$this->dia_solo_hoy = date("d",$this->tiempo_actual);
			return true;
		}
		/********************************************************************************
		* Funcion que retorna el html del calendario
		* 
		* @return String Html de la página del calendario
		*********************************************************************************/
		function ObtenerCalendario()
		{
			$MesesC[1]='ENERO';
			$MesesC[2]='FEBRERO';
			$MesesC[3]='MARZO';
			$MesesC[4]='ABRIL';
			$MesesC[5]='MAYO';
			$MesesC[6]='JUNIO';
			$MesesC[7]='JULIO';
			$MesesC[8]='AGOSTO';
			$MesesC[9]='SEPTIEMBRE';
			$MesesC[10]='OCTUBRE';
			$MesesC[11]='NOVIEMBRE';
			$MesesC[12]='DICIEMBRE';
			
			$festivo['1']['01'] = "1";
			$festivo['7']['20'] = "1";
			$festivo['8']['07'] = "1";
			$festivo['12']['08'] = "1";
			$festivo['12']['25'] = "1";
			
			$mes = date("n", $this->tiempo_actual);
			$anyo = date("Y", $this->tiempo_actual);
			
			if($_REQUEST['year'])
			{
				$mes = $_REQUEST['meses'];
				$anyo = $_REQUEST['year'];
			}
			//Variable para llevar la cuenta del dia actual
			$dia_actual = 1;
			//calculo el numero del dia de la semana del primer dia
			$numero_dia = $this->calcula_numero_dia_semana(1,$mes,$anyo);
			//calculo el último dia del mes
			$ultimo_dia = $this->ultimoDia($mes,$anyo);	
			
			$salida .= ReturnHeader('Calendario');
      $salida .= ReturnBody();
			$salida .= "<script>\n";
			$salida .= "	function year1(t)\n";
			$salida .= "	{\n";
	    $salida .= '		window.location.href="Calendario.php?year="+t.elements[1].value+"&meses="+t.elements[0].value+"';
      foreach($_REQUEST as $v=>$v1)
      {
         if($v!='year' and $v!='meses' and $v!='DiaEspe')
         {
            $salida .= '&'.$v.'='.$v1;
         }
      }
      $salida .= '";'."\n";
			$salida .= "	}\n";
			$salida .= "	function devuelveFecha(dia,mes,ano)\n";
			$salida .= "	{\n";
			$salida .= "		var meses = \"\";\n";
			$salida .= "		var formulario_destino = '".$_REQUEST['forma']."';\n";
			$salida .= "		var campo_destino = '".$_REQUEST['campo']."';\n";
			$salida .= "		var separador = '".$_REQUEST['separador']."';\n";
			$salida .= "		(mes < 10)? meses = \"0\"+mes:meses = \"\"+mes;\n";
			$salida .= "		eval (\"opener.document.\" + formulario_destino + \".\" + campo_destino + \".value='\" + dia + separador + meses + separador + ano + \"'\")\n";
			$salida .= "		window.close()\n";
			$salida .= "	}\n";
			$salida .= "</script><br>\n";
			$salida .= "<form name=\"calendario\" action=\"\" method=\"POST\">\n";
			$salida .= "<table width=300 align=\"center\" cellspacing=3 cellpadding=2 border=0 class=\"modulo_table_list\">\n";
			$salida .= "	<tr>\n";
			$salida .= "		<td colspan=\"7\" align=\"center\" >\n";
			$salida .= "			<table width=100% cellspacing=2 cellpadding=2 border=0>\n";
			$salida .= "				<tr class=\"label\">\n";
			$salida .= "					<td><b>MES</b>\n";
			$salida .= "						<select class=\"select\" name=\"mes\" onChange=\"year1(this.form)\">\n";
			$sel = "";
			for($i=1; $i<=sizeof($MesesC); $i++)
			{
				($i == $mes)? $sel = "selected":$sel = "";
				$salida .= "							<option value=\"$i\" $sel>".$MesesC[$i]."</option>\n";
			}
			$salida .= "						</select> ";
			$salida .= "					</td>\n";
			$salida .= "					<td><b>AÑO</b>\n";
			$salida .= "						<select class=\"select\" name=\"year\" onChange=\"year1(this.form)\">\n";
			$anyo1 = date("Y", $this->tiempo_actual);
			
			for($i=1900; $i<$anyo1+20; $i++)
			{
				($i == $anyo)? $sel = "selected":$sel = "";
				$salida .= "							<option value=\"$i\" $sel>".$i."</option>\n";
			}
			$salida .= "						</select> ";
			$salida .= "					</td>\n";
			$salida .= "				</tr>\n";
			$salida .= "			</table>\n";
			$salida .= "		</td>\n";
			$salida .= "	</tr>\n";
			$salida .= "	<tr class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-align:center;text-indent: 0pt\">\n";
			$salida .= "		<td width=14% >LUN</td>\n";
			$salida .= "	  <td width=14% >MAR</td>\n";
			$salida .= "	  <td width=14% >MIE</td>\n";
			$salida .= "	  <td width=14% >JUE</td>\n";
			$salida .= "	  <td width=14% >VIE</td>\n";
			$salida .= "	  <td width=14% >SAB</td>\n";
			$salida .= "		<td width=14% >DOM</td>\n";
			$salida .= "	</tr>\n";
			$salida .= "	<tr height=\"21\">\n";
			for ($i=0;$i<7;$i++)
			{
				if ($i < $numero_dia)
				{
					//si el dia de la semana i es menor que el numero del primer dia de la semana no pongo nada en la celda
					$salida .= "		<td class=\"modulo_list_oscuro\"></td>\n";
				} 
				else 
				{
					$dia1 = "$dia_actual";
					if($i < 10) $dia1 = "0$dia_actual";
					
					$sql = "select TO_CHAR(dia,'DD') AS dia 
									from dias_festivos 
									where date(dia) = date('$anyo-$mes-$dia1');";
					$dias = $this->ConexionBaseDatos($sql);
					
					if($festivo[$mes][$dia1] == "1" || $dias[$dia1] == '1') 
						$estilo = " class=\"modulo_table\" ";
					else
						$estilo = $this->dame_estilo($dia_actual,$mes,$anyo,$i);
					
					$salida .= "		<td align=center $estilo>\n";
					$salida .= "			<a href='javascript:devuelveFecha(\"$dia1\",$mes,$anyo)'><b>$dia1</b></a>\n";
					$salida .= "		</td>\n";
					$dia_actual++;
				}
			}
			$salida .= "	</tr>\n";
			$numero_dia = 0;
			while ($dia_actual <= $ultimo_dia)
			{
				//si estamos a principio de la semana escribo el <TR>
				if ($numero_dia == 0)	$salida .= "	<tr height=\"21\">\n";
				
				$dia1 = "$dia_actual";
				if($dia_actual < 10) $dia1 = "0$dia_actual";
				
				$sql = "select TO_CHAR(dia,'DD') AS dia 
								from dias_festivos 
								where date(dia) = date('$anyo-$mes-$dia1');";
				$dias = $this->ConexionBaseDatos($sql);
				
				if($festivo[$mes][$dia1] == "1" || $dias[$dia1] == '1') 
					$estilo = " class=\"modulo_table\" ";
				else
					$estilo = $this->dame_estilo($dia_actual,$mes,$anyo,$numero_dia);
						
				$salida .= "		<td align=center $estilo >\n";
				$salida .= "			<a href='javascript:devuelveFecha(\"$dia1\",$mes,$anyo)'><b>$dia1</b></a>\n";
				$salida .= "		</td>\n";
				$dia_actual++;
				$numero_dia++;
				//si es el uñtimo de la semana, me pongo al principio de la semana y escribo el </tr>
				if ($numero_dia == 7)
				{
					$numero_dia = 0;
					$salida .= "		</tr>\n";
				}
			}
	
			if($numero_dia > 0)
			{
				for ($i=$numero_dia;$i<7;$i++)
				{
					$salida .= "		<td class=\"modulo_list_oscuro\"></td>\n";
				}
			}
	
			$salida .= "	</tr>\n";
			$salida .= "</table>\n";
			$salida .= "</form>\n";
			
			return $salida;
		}	
		/********************************************************************************
		* Funcion donde se calcula que dia de la semana es la fecha inicial del mes 
		*********************************************************************************/
		function calcula_numero_dia_semana($dia,$mes,$ano)
		{
			$sql = "SELECT TO_CHAR(date('$ano-$mes-$dia'),'day') AS dia ";
			list($dbconn)=GetDBConn();
			$rst = $dbconn->Execute($sql);
			
			if(!$rst->EOF)
			{
				if(trim($rst->fields[0]) == "sunday" || trim($rst->fields[0]) == "domingo")
					$this->numerodiasemana = '0';
				if(trim($rst->fields[0]) == "monday" || trim($rst->fields[0]) == "lunes")
					$this->numerodiasemana = '1';
				if(trim($rst->fields[0]) == "tuesday" || trim($rst->fields[0]) == "martes")
					$this->numerodiasemana = '2';
				if(trim($rst->fields[0]) == "wednesday" || trim($rst->fields[0]) == "miercoles")
					$this->numerodiasemana = '3';
				if(trim($rst->fields[0]) == "thursday" || trim($rst->fields[0]) == "jueves")
					$this->numerodiasemana = '4';
				if(trim($rst->fields[0]) == "friday" || trim($rst->fields[0]) == "viernes")
					$this->numerodiasemana = '5';
				if(trim($rst->fields[0]) == "saturday" || trim($rst->fields[0]) == "sabado")
					$this->numerodiasemana = '6';
				
				$rst->MoveNext();
			}
			$rst->Close();
			$numero = $this->numerodiasemana;
			if ($this->numerodiasemana == 0) 
				$numero = 6;
			else
				$numero--;
			return $numero;
		}
		/********************************************************************************
		* Funcion que devuelve el último día de un mes
		* 
		* @param int $mes Mes que se evaluara
		* @param int $anyo Indica el año de la fecha dada
		* @return int ultimo dia del mes
		*********************************************************************************/
		function ultimoDia($mes,$anyo)
		{ 
			$ultimo_dia = 28; 
			while (checkdate($mes,$ultimo_dia + 1,$anyo))
			{ 
				 $ultimo_dia++; 
			} 
			return $ultimo_dia; 
		} 
		/********************************************************************************
		* Funcion donde se definen los estilos para los dias, evaluando si es un domingo, 
		* un sabado o un dia cualquiera 
		* 
		* @param int $dia_imprimir indica que dia es el que se va a imprimir 
		* @param int $mes Mes que se esta evaluado en la fecha dada
		* @param int $ano Año que se esta evaluando en la fecha dada
		* @param int $numero_dia Indica que dia de la semana es
		* @return string Estilo definido para el dia dado 
		**********************************************************************************/
		function dame_estilo($dia_imprimir,$mes,$ano, $numero_dia)
		{
			//dependiendo si el día es Hoy, Domigo o Cualquier otro, devuelvo un estilo
			if ($this->dia_solo_hoy == $dia_imprimir && $mes==date("n", $this->tiempo_actual) && $ano==date("Y", $this->tiempo_actual))
			{
				//si es hoy
				$estilo = "class=\"DiaHoy\" ";
			}
			else
			{
				if ( $numero_dia == 6)//Domingo
				{
					$estilo = " class=\"modulo_table\" ";
				}
				else if( $numero_dia == 5)//Sabado
					{
						$estilo = "class=\"agendadomfes\" ";
					}
					else //Cualquier dia
					{
						$estilo = " class=\"modulo_list_claro\"";
					}
			}
			return $estilo;
		}
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return array datos del dia de la fecha dada 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				return false;
			}
			
			$dias = array();
			while(!$rst->EOF)
			{
				$dias[$rst->fields[0]] = "1";
				$rst->MoveNext();
			}
			$rst->Close();
			return $dias;
		}
	}
?>