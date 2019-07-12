<?php
/**
* Submodulo de GraficasSeguimientoCPN_Graficas_HTML
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_GraficasSeguimientoCPN_Graficas_HTML.class.php,v 1.2 2007/02/01 20:55:43 luis Exp $
*/
class Graficas_HTML
{

  function Graficas_HTML()
	{
		return true;
	}
	
	function frmHistoria()
	{
		$this->salida="";
		return $this->salida;
	}
	
	function frmConsulta()
	{
		return true;
	}
	
	/**
	* Funcion que señaliza una palabra para simbolizar que esta en estado de alerta
	* @return boolean
	*/
  function SetStyle($campo)
	{
		if ($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
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

	function frmForma($datosgraf,$semana_gestante,$fcp)
	{
		global $_ROOT;
		
		$evolucion=SessionGetvar("Evolucion");
		$paso=SessionGetvar("Paso");
		$pfj=SessionGetvar("Prefijo");
		
		$this->salida='';
		$this->salida = ThemeAbrirTablaSubModulo('GRAFICAS CPN');
		
		if($this->ban==1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		
		$this->frmGestacion($semana_gestante,$fcp);
		
		IncludeLib("jpgraph/Presion_arterial_diastolica"); //cargamos la libreria de presion diastolica.
		IncludeLib("jpgraph/Peso_materno"); //cargamos libreria de peso materno.
		IncludeLib("jpgraph/Cuello_uterino"); //cargamos libreria de cuello_uterino
	  
		if(empty($datosgraf))
		{
			$primera_vez=1;//Aqui arranca por primera vez con esta variable no muestra nada de grafica(es primera vez)
		}
		else
		{
			$datay2 = array();
			$datax2 = array();
			$datayd2 = array();
			$dataxd2 = array();
			$datayp2 = array();
			$dataxp2 = array();
			$b=true;
			
			for($i=0;$i<sizeof($datosgraf);$i++)
			{
				/*presion arterial diastolica*/	
				if($datosgraf[$i][semana_actual]>=16)
				{
					$datayd2[]=$datosgraf[$i][tabaja];
					$dataxd2[]=$datosgraf[$i][semana_actual];
				}
				
				/*altura uterina*/	
				if($datosgraf[$i][semana_actual]>=13 and  $datosgraf[$i][altura_uterina] < 35 and !empty($datosgraf[$i][altura_uterina]))
				{
					$datay2[]=$datosgraf[$i][altura_uterina];
					$datax2[]=$datosgraf[$i][semana_actual];
				}
				
				/*peso*/
				if($datosgraf[$i][semana_actual]>=16)
				{
					if($b)
					{
						$peso=$datosgraf[$i][peso];
						$datayp2[]=0.0;
						$b=false;
					}
					else	
						$datayp2[]=$datosgraf[$i][peso]-$peso;
					
					$dataxp2[]=$datosgraf[$i][semana_actual];
				}
			}
		}
		
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
		$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida.="<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida.="<td>PRESION DIASTOLICA</td>";
		$this->salida.="<td>PESO MATERNO</td>";
		$this->salida.="<td>ALTURA UTERINA</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr align=\"center\" class=\"hc_submodulo_list_oscuro\">";
	
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
		
		$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'CronogramaCitasyProcedimientos'));
		$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionGestacion'));
			
		$this->salida.="<table align=\"center\" cellspacing=\"20\">";
		$this->salida.="	<tr>";
		$this->salida.="<form name=\"formasig$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"siguiente$pfj\" value=\"SIGUIENTE\"></td>";
		$this->salida.="</form>";
		$this->salida.="<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
		$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></td>";
		$this->salida.="</form>";
		$this->salida.="	</tr>";
		$this->salida.="</table>";
	
		$this->salida.= ThemeCerrarTablaSubModulo();
		return $this->salida;
  }
	
	function frmGestacion($semana,$fecha)
	{
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\" cellpadding=\"0\" cellspacing=\"2\">";
		$this->salida.="	<tr>";
		$this->salida.="		<td width=\"25%\" class=\"modulo_table_list_title\">SEMANA DE GESTACION</td>";
		$this->salida.="		<td width=\"25%\" class=\"modulo_table_list_title\">FECHA PROBLABLE DE PARTO</td>";
		$this->salida.="  </tr>";
		$this->salida.="	<tr>";
		$this->salida.="		<td width=\"25%\" class=\"hc_table_submodulo_list_title\"><label class=\"label\">$semana</label></td>";
		$this->salida.="		<td width=\"25%\" class=\"hc_table_submodulo_list_title\"><label class=\"label\">$fecha</label></td>";
		$this->salida.="  </tr>";
		$this->salida.="</table><br>";
		return true;
	}
}
?>
