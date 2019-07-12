<?php
/**
* Submodulo de ControlPrenatal(HTML).
*
* Submodulo para manejar la informacion de una madre mediante datos de parto y datos del recien nacido
* verificando su estado de salud en la madres en pre y post parto, al igual que la salud del recien nacido.
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_ControlPrenatal_HTML.php,v 1.3 2006/06/30 16:01:58 luis Exp $
*/

/**
* ControlPrenatal_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo ControlPrenatal, se extiende la clase ControlPrenatal y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class ControlPrenatal_HTML extends ControlPrenatal
{

  function ControlPrenatal_HTML()
	{
		$this->ControlPrenatal();//constructor del padre
		return true;
	}




  /**
* Funcion que calcula y  retorna las semanas de gestacion de una paciente, sacando la fecha
* inicial desde la tabla gestacion, mediante el campo (FUM -->fecha ultima mestruacion), la cual llega
* a la variable $FechaIni y con la fecha actual, la cual es $FechaFin, se sacan las semanas de la paciente.
* @return boolean
* @param date fecha sacada de la tabla gestacion(campo --> fum)
*/
	function CalcularSemanasGestante($FechaIni)
	{
			$FechaFin=date("Y-m-d");
 			$fech=strtok($FechaIni,"-");
			for($i=0;$i<3;$i++)
			{
				$date[$i]=$fech;
				$fech=strtok("-");
			}
			$fech=strtok($FechaFin,"-");
			for($i=0;$i<3;$i++)
			{
				$date1[$i]=$fech;
				$fech=strtok("-");
			}
			$edad=(ceil($date1[0])-$date[0]);
			$meses=$date1[1]-$date[1];
			$dias=$date1[2]-$date[2];
			$total=($edad*378)+($meses*31.5)+$dias;
			$meses1=(($total%378)/30);
			$meses1=$meses1*4.5;
  		return $meses1;
	}


/**
* Funcion que consulta los datos de los controles que se le ha hecho a la madres a lo largo
*de cada citas de control prenatal, con un unico id de gestacion y varias evoluciones
* los trae en una tabla.
* @return boolean
*/

	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$result=$this->ConsultaControl(); //llamada a consultar
		if(!$result->EOF)
		{
			$this->salida.="<table border=\"1\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\" colspan=\"12\">CONTROL PRENATAL</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td>Peso</td>";
			$this->salida.="<td>Tension Alta</td>";
			$this->salida.="<td>Tension Baja</td>";
			$this->salida.="<td>Altura Uterina</td>";
			$this->salida.="<td>FCF</td>";
			$this->salida.="<td>Movimiento Fetal</td>";
			$this->salida.="<td>Valor Cuello Uterino</td>";
			$this->salida.="<td>Edemas</td>";
			$this->salida.="<td>Monitoreo Fetal</td>";
			$this->salida.="<td>Presentacion</td>";
			$this->salida.="<td>Evolución</td>";
			$this->salida.="<td>Fecha de Evolución</td>";
			$this->salida.="</tr>";
			while (!$result->EOF)
        	{
				$dato1=$result->fields[0];
				$dato2=$result->fields[1];
				$dato3=$result->fields[2];
				$dato4=$result->fields[3];
				$dato5=$result->fields[4];
				$dato6=$result->fields[5];
				$dato7=$result->fields[6];
				$dato8=$result->fields[7];
				$dato9=$result->fields[8];
				$dato10=$result->fields[9];
				$dato11=$result->fields[10];
				$dato12=$result->fields[11];

				if ($p==0)
				{
					$p=1;
					$estilo="hc_submodulo_list_claro";
					$this->salida.="<tr class=$estilo align=\"center\">";
					$this->salida.="<td>";
					$this->salida.=$dato1;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato2;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato3;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato4;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato5;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato6;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato7;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato8;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato9;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato10;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato11;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato12;
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
				else
				{
					$estilo="hc_submodulo_list_oscuro";
					$p=0;
					$this->salida.="<tr class=$estilo align=\"center\">";
					$this->salida.="<td>";
					$this->salida.=$dato1;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato2;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato3;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato4;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato5;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato6;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato7;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato8;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato9;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato10;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato11;
					$this->salida.="</td>";
					$this->salida.="<td>";
					$this->salida.=$dato12;
					$this->salida.="</td>";
				}
				$result->MoveNext();
			}

			$this->salida.="</tr>";
			$this->salida.="</table>";
		}
		elseif($result->EOF)
		{
			return false;
		}
		return true;
	}



	function frmHistoria()
	{
    	$pfj=$this->frmPrefijo;
    	$result=$this->ConsultaControl(); //llamada a consultar
		if(!$result->EOF)
		{
				$salida.="<table border=\"1\" class=\"hc_table_list\">";
				$salida.="<tr>";
				$salida.="<td align=\"center\" colspan=\"12\">CONTROL PRENATAL</td>";
				$salida.="</tr>";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="<td>Peso</td>";
				$salida.="<td>Tension Alta</td>";
				$salida.="<td>Tension Baja</td>";
				$salida.="<td>Altura Uterina</td>";
				$salida.="<td>FCF</td>";
				$salida.="<td>Movimiento Fetal</td>";
				$salida.="<td>Valor Cuello Uterino</td>";
				$salida.="<td>Edemas</td>";
				$salida.="<td>Monitoreo Fetal</td>";
				$salida.="<td>Presentacion</td>";
				$salida.="<td>Evolución</td>";
				$salida.="<td>Fecha de Evolución</td>";
				$salida.="</tr>";
				while (!$result->EOF)
        		{
						$dato1=$result->fields[0];
						$dato2=$result->fields[1];
						$dato3=$result->fields[2];
						$dato4=$result->fields[3];
						$dato5=$result->fields[4];
						$dato6=$result->fields[5];
						$dato7=$result->fields[6];
						$dato8=$result->fields[7];
						$dato9=$result->fields[8];
						$dato10=$result->fields[9];
						$dato11=$result->fields[10];
						$dato12=$result->fields[11];

						if ($p==0)
						{
							$p=1;
							$estilo="hc_submodulo_list_claro";
							$salida.="<tr class=$estilo align=\"center\">";
							$salida.="<td>";
							$salida.=$dato1;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato2;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato3;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato4;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato5;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato6;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato7;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato8;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato9;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato10;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato11;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato12;
							$salida.="</td>";
							$salida.="</tr>";
						}
						else
						{
							$estilo="hc_submodulo_list_oscuro";
							$p=0;
							$salida.="<tr class=$estilo align=\"center\">";
							$salida.="<td>";
							$salida.=$dato1;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato2;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato3;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato4;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato5;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato6;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato7;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato8;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato9;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato10;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato11;
							$salida.="</td>";
							$salida.="<td>";
							$salida.=$dato12;
							$salida.="</td>";
						}
					$result->MoveNext();
				}

			$salida.="</tr>";
			$salida.="</table>";
		}
		elseif($result->EOF)
		{
			return false;
		}
		return $salida;
	}


	/**
	* Funcion que señaliza una palabra para simbolizar que esta en estado de alerta
	* @return boolean
	*/
  function SetStyle($campo)
	{
		if ($this->frmError[$campo] || $campo=="MensajeError"){
			if ($campo=="MensajeError"){
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}




/**
* Funcion de  captura de datos de los controles  prenatales que se le ha hecho a la madre
* Manejo de Graficas de controles(crecimiento cuello uterino,crecimiento peso materno y presion arterial)
* @return boolean
*/

	function frmForma()
	{
		global $_ROOT;
		$pfj=$this->frmPrefijo;
    	$this->salida='';
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('CONTROL PRENATAL');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

    	if($this->datosPaciente['sexo_id']=='M')
		{
		    $this->salida.="<br><table align=\"center\" border=\"0\" >";
				$this->salida.="<tr>";
				$this->salida.="<td class=\"label_error\"><img src=\"".GetThemePath()."/images/infor.png\">&nbsp;&nbsp;";
				$this->salida.="EL PACIENTE NO PUEDE SER DE TIPO MASCULINO";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.= ThemeCerrarTablaSubModulo();
				return true;
		}

    	$source=$this->ConsultaGestacion(); //funcion q trae los datos de la gestacion del paciente.
		$fechaData=$source->fields[0]; //$fechaData trae la fecha de fum....
    	$gestacion=$source->fields[1]; //trae la gestacion_id.....
		
		list($dbconn) = GetDBconn();

		if(empty($gestacion))
		{
						$this->salida.="<br><table align=\"center\" border=\"0\">";
						$this->salida.="<tr>";
						$this->salida.="<td class=\"label_error\"><img src=\"".GetThemePath()."/images/info.png\">&nbsp;&nbsp;";
						$this->salida.="ESTE PACIENTE NO TIENE UNA GESTACION ACTIVA";
						$this->salida.="</td>";
						$this->salida.="</tr>";
						$this->salida.="</table>";
						$this->salida.= ThemeCerrarTablaSubModulo();
						return true;
		}

		/*revisa si hay altura uterina en la base de datos con el id de este paciente*/
    	$dat=$this->RevisarAltura($gestacion); //sacaamos los datos para genrerar la grafica
		$conteo=$dat->RecordCount();

		/*revisa si hay altura uterina en la base de datos con el id de este paciente*/
	  	$semana=0;
		//mandamos la fecha de inicio para sacar las semanas de gestacion
	    $semana=$this->CalcularSemanasGestante($fechaData);

  	//echo "**$semana**";
 		// exit();
		//$this->salida = ThemeAbrirTablaSubModulo('Error de Datos');


		if($semana<12)
		{

				$this->salida.="<br><table align=\"center\" border=\"0\">";
				$this->salida.="<tr>";
				$this->salida.="<td class='label_error'><img src=\"".GetThemePath()."/images/info.png\">&nbsp;&nbsp;";
				$this->salida.="EL CONTROL DE GESTACION DEBE SER DESPUES DE LAS 12 SEMANAS DE LA ULTIMA MESTRUACION";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.= ThemeCerrarTablaSubModulo();
				return true;
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'insertar'));
    	$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<input type=\"hidden\"  name=\"semana".$pfj."\" value=\"$semana\">"; //valor hidden..


		IncludeLib("jpgraph/Presion_arterial_diastolica"); //cargamos la libreria de presion diastolica.
		IncludeLib("jpgraph/Peso_materno"); //cargamos libreria de peso materno.
		IncludeLib("jpgraph/Cuello_uterino"); //cargamos libreria de cuello_uterino


	  if ((empty($conteo)) or ($conteo==false))
		{
			$primera_vez=1;//Aqui arranca por primera vez con esta variable no muestra nada de grafica(es primera vez)
		}
		else
		{
				$datay2= array();
				$datax2= array();
				while (!$dat->EOF)
				{
						$sp=$dat->fields[3];     // campo 'semanas' de la tabla hc_controles.


						/* aqui preguntamos por el rango de la altura uterina 13 y 38*/
						if($dat->fields[3] >=13 and  $dat->fields[0] < 38 and !empty($dat->fields[0]))
						{
								$datay2[]=$dat->fields[0]; // campo 'alturauterina' de la tabla hc_controles.
								$datax2[]=$dat->fields[3]; //aqui se guarda x como un valor las graficas
						}

						$s=$dat->fields[1];     //variable que tomara la tension sistolica o alta.
						$di=$dat->fields[2];   //variable que tomara la tension diastolica o baja.
						$pam=($s+(2 * $di))/3; //funcion de la presiona arterial media... OJO RESTAR 0.5


						/*aqui preguntamos por el rango de la presion diastolica 16 y 40 semanas */
						if(($sp>=16 and $pam >=40))
						{
								$datayd2[]=$pam;
								$dataxd2[]=$dat->fields[3]; //aqui se saca el valor de las semanas de la gestacion en la BD..
						}

						/*aqui preguntamos por el rango del peso de materna */
 						if($dat->fields[3] >=16 and  $dat->fields[4] < 20 )
						{
								$datayp2[] =$dat->fields[4]; //aqui va la variable y de crecimiento del peso materno
								$dataxp2[] =$dat->fields[3] + 2;// aqui se saca el valor de la semanas de gestacion          (X)....
								//hubo que aumentar 2 al x
						}
						$dat->MoveNext();
				}

	 }

      $this->FormaDatos(); //llama la forma que pide los datos para generar las graficas.


			//si la variable $primera_vez esta activo significa que saldran las graficas
			//de NO-GRAFICO es por que no hay datos en ninguna de las 3 graficas.
      if($primera_vez==1)
			{
			  $RutaPresionDiastolica=GraficarPresionArterialDiastolica(); // llamando a la presion arterial diastolica
				$RutaPesoMaterno=GraficarControlPesoMaterno(); 	// llamando a la funcion de peso materno
				$RutaCuelloUterino=GraficarControlCuelloUterino(); //llamando a la funcion de cuello uterino
			}


		 /*preguntamos sobre los arreglos de presion diastolica*/
		 if(sizeof($datayd2)<1 || sizeof($dataxd2)<1)
		 {
				$RutaPresionDiastolica=GraficarPresionArterialDiastolica();
		 }
		 else
		 {
		  	$RutaPresionDiastolica=GraficarPresionArterialDiastolica(1,$datayd2,$dataxd2);
		 }


		 /*preguntamos sobre los arreglos de peso materno */
		 if(sizeof($datayp2)<1 || sizeof($dataxp2)<1)
		 {
				$RutaPesoMaterno=GraficarControlPesoMaterno();
		 }
		 else
		 {
		  	$RutaPesoMaterno=GraficarControlPesoMaterno(1,$datayp2,$dataxp2);
		 }


		 /*preguntamos sobre los arreglos de incremento del cuello uterino */
		 if(sizeof($datay2)<1 || sizeof($datax2)<1)
		 {
				$RutaCuelloUterino=GraficarControlCuelloUterino();
		 }
		 else
		 {
		   	$RutaCuelloUterino=GraficarControlCuelloUterino(1,$datay2,$datax2);
		 }



		 //en este grafico mostramos las graficas de gestacion. png
			$this->salida.="<table align='center' border='0' width='100%'>";
			$this->salida.="<tr   align=\"center\">";

			if(is_file($_ROOT."$RutaPresionDiastolica"))
      {
			  $this->salida.="  <td>";
				$this->salida.="    <img src=$RutaPresionDiastolica border='1'>"; //aqui se imprime para mostrar el grafico
				$this->salida.="  </td>";
			}

      if(is_file($_ROOT."$RutaPesoMaterno"))
      {
				$this->salida.="  <td>";
				$this->salida.="    <img src=$RutaPesoMaterno border='1'>"; //aqui se imprime para mostrar el grafico
				$this->salida.="  </td>";
			}

			if(is_file($_ROOT."$RutaCuelloUterino"))
      {
				$this->salida.="  <td>";
				$this->salida.="    <img src=$RutaCuelloUterino border='1' >"; //aqui se imprime para mostrar el grafico
				$this->salida.="  </td>";
			}

			$this->salida.="</tr>";
			$this->salida.="</table>";



			$this->salida.= "<p align=\"center\"><input type=\"submit\" name=\"enviar\" value=\"Insertar\" class=\"input-submit\"></p>";
      $this->salida.="</form>";
			$this->salida.= ThemeCerrarTablaSubModulo();
			return true;

  }

	function FormaDatos()
	{
		$pfj=$this->frmPrefijo;
		$presentacion=$this->ComboPartosPresentacion();
    $this->salida.="<table border=\"0\" align=\"center\"  width=\"100%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table><br>";


		$this->salida.="<table border=\"1\" align=\"center\" class=\"hc_table_list\" width=\"42%\" cellpading=\"3\" cellspacing=\"3\">";
		$this->salida.="<tr class='hc_submodulo_list_oscuro'>";
		$this->salida.="   <td class=\"".$this->SetStyle("peso")."\" colspan=\"4\">&nbsp;Peso</td>";
		$this->salida.="   <td align=\"center\" width=\"10%\"><input name=\"peso".$pfj."\" value='".$_REQUEST['peso']."' class=\"input-text\" type=\"text\" size=\"5\" maxlength=\"5\"></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class='hc_submodulo_list_claro'>";
		$this->salida.="   <td class=\"".$this->SetStyle("taa")."\" colspan=\"4\">&nbsp;Tension Arterial Alta</td>";
		$this->salida.="   <td align=\"center\"><input name=\"tenalta".$pfj."\" class='input-text' value='".$_REQUEST['tenalta']."' type=\"text\"size=\"5\" maxlength=\"5\"></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class='hc_submodulo_list_oscuro'>";
		$this->salida.="   <td class=\"".$this->SetStyle("tab")."\" colspan=\"4\">&nbsp;Tension Arterial Baja</td>";
		$this->salida.="   <td align=\"center\"><input name=\"tenbaja".$pfj."\"  value='".$_REQUEST['tenbaja']."' class=\"input-text\" type=\"text\" size=\"5\" maxlength=\"5\"></td>";
   	$this->salida.="</tr>";

		$this->salida.="<tr class='hc_submodulo_list_claro' >";
		$this->salida.="   <td  colspan=\"4\">&nbsp;Altura Uterina</td>";
		$this->salida.="	  <td align=\"center\"><input name=\"alturau".$pfj."\" value='".$_REQUEST['alturau']."' class=\"input-text\"  type=\"text\" size=\"5\" maxlength=\"5\"></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class='hc_submodulo_list_oscuro' >";
		$this->salida.="   <td class=\"".$this->SetStyle("fcf")."\"fcf colspan=\"4\">&nbsp;FCF</td>";
		$this->salida.="   <td align=\"center\"><input name=\"fcf".$pfj."\" value='".$_REQUEST['fcf']."' class=\"input-text\" type=\"text\" size=\"5\" maxlength=\"5\"></td>";
		$this->salida.="</tr>";


		$this->salida.="<tr class='hc_submodulo_list_claro'>";
		$this->salida.="	   <td class=\"label\"' colspan=\"4\">&nbsp;Presentacion Fetal</td>";
		$this->salida.="    <td><select name=\"presentacion".$pfj."\" align=\"left\"  class=\"select\">";
    $vars="";
		while (!$presentacion->EOF)
		{
			$vars[$presentacion->fields[0]]=$presentacion->fields[1];
			$presentacion->MoveNext();
		}
    $presentacion->Close();
		foreach($vars as $id7=>$pre)
		{
		$this->salida.=" <option value=\"$id7\">$pre</option>";  }
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br><br>";

		$this->salida.="<table border=\"1\" align=\"center\" class=\"hc_table_list\" width=\"42%\" cellpading=\"4\" cellspacing=\"4\">";
		$this->salida.="<tr class='hc_submodulo_list_oscuro'>";
		$this->salida.="	  <td class='label' width=\"40%\">&nbsp;Movimientos Fetales &gt; 10 </td>";
		$this->salida.="	  <td class='label' align=\"center\"  width=\"9%\">Si</td>";
		$this->salida.="   <td width=\"9%\" align=\"center\"><input type=\"radio\" name=\"movfetal".$pfj."\" value=\"1\"></td>";
		$this->salida.="   <td class='label' align=\"center\"  width=\"9%\">No</td>";
		$this->salida.="   <td width=\"9%\" align=\"center\"><input type=\"radio\" name=\"movfetal".$pfj."\" value=\"0\" checked ></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class='hc_submodulo_list_claro'>";
		$this->salida.="   <td class='label'>&nbsp;Valoracion Cuello Uterino</td>";
		$this->salida.="   <td class='label' align=\"center\">Si</td>";
		$this->salida.="   <td align=\"center\"><input type=\"radio\" name=\"cuellou".$pfj."\" value=\"1\"></td>";
		$this->salida.="   <td class='label' align=\"center\">No</td>";
		$this->salida.="	  <td align=\"center\"><input type=\"radio\" name=\"cuellou".$pfj."\" value=\"0\"checked></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class='hc_submodulo_list_oscuro'>";
		$this->salida.="   <td class='label'>&nbsp;Edemas</td>";
		$this->salida.="   <td class='label' align=\"center\">Si</td>";
		$this->salida.="   <td align=\"center\"><input type=\"radio\" name=\"edemas".$pfj."\" value=\"1\"></td>";
		$this->salida.="	  <td align=\"center\" class='label'>No</td>";
		$this->salida.="	  <td align=\"center\"><input type=\"radio\" name=\"edemas".$pfj."\" value=\"0\" checked ></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class='hc_submodulo_list_claro'>";
		$this->salida.="   <td class='label'>&nbsp;Monitoreo Fetal</td>";
		$this->salida.="   <td class='label' align=\"center\">Si</td>";
		$this->salida.="   <td align=\"center\"><input type=\"radio\" name=\"monife".$pfj."\" value=\"1\"></td>";
		$this->salida.="   <td class='label'align=\"center\">No</td>";
	  $this->salida.="   <td align=\"center\"><input type=\"radio\" name=\"monife".$pfj."\" value=\"0\" checked ></td>";
	  $this->salida.="</tr>";

		$this->salida.="</table>";
    $this->salida.="<br>";
		$this->salida.="<br>";
		return true;

	}

}
?>
