<?php
/**
* Submodulo de GraficasSeguimientoReno_GraficasReno_HTML
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_GraficasSeguimientoReno_GraficasReno_HTML.class.php,v 1.2 2007/02/01 20:48:35 luis Exp $
*/
class GraficasReno_HTML
{

  function GraficasReno_HTML()
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
	* Funcion que se?aliza una palabra para simbolizar que esta en estado de alerta
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

	function frmForma($datosgraf,$datosgrafCreatinina)
	{
		global $_ROOT;
		
		$pfj=SessionGetvar("Prefijo");
		$evolucion=SessionGetvar("Evolucion");
		$paso=SessionGetvar("Paso");
		
		$this->salida='';
		$this->salida = ThemeAbrirTablaSubModulo('GRAFICAS RENOPROTECCION');
		
		if($this->ban==1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		
		IncludeLib("jpgraph/Presion_arterial"); //cargamos la libreria de presion arterial.
		IncludeLib("jpgraph/Peso"); //cargamos libreria de peso.
		IncludeLib("jpgraph/Curva_depuracion_creatinina"); //cargamos libreria de Curva_deparacion_creatinina.
		
		//echo "<br><br><br><pre>";
		
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
			
			
			for($i=0;$i<sizeof($datosgraf);$i++)
			{
				$ta=$datosgraf[$i][taalta];//variable que tomara la tension sistolica o alta.
				$tb=$datosgraf[$i][tabaja];//variable que tomara la tension diastolica o baja.
				
				/*Presion arterial*/	
				$datayda2[]=$ta;
				$dataydb2[]=$tb;
				$dataxd2[]=$i;
				$datafd1[]=$datosgraf[$i]['fecha'];
				
				/*Peso*/	
				$datayp2[]=$datosgraf[$i][peso];
				$dataxp2[]=$i;
				$datafd2[]=$datosgraf[$i]['fecha'];
			}
		
			for($i=0;$i<sizeof($datosgrafCreatinina);$i++)
			{
				/*Depracion de Creatinina*/	
				$datay2[]=$datosgrafCreatinina[$i][resultado];
				$datax2[]=$i;
				$datafd3[]=$datosgrafCreatinina[$i]['fecha'];
			}
		
		}
		
		//si la variable $primera_vez esta activo significa que saldran las graficas
		//de NO-GRAFICO es por que no hay datos en ninguna de las 3 graficas.
		if($primera_vez==1)
		{
			$RutaPresionArterial=GraficarPresionArterial(); // llamando a la presion arterial diastolica
			$RutaPeso=GraficarControlPeso(); 	// llamando a la funcion de peso materno
			$RutaCurvaCreatinina=GraficarCurvaCreatinina(); //llamando a la funcion de cuello uterino
		}
		
		/*preguntamos sobre los arreglos de presion diastolica*/
		if(sizeof($datayda2)<1 || sizeof($dataydb2)<1 || sizeof($dataxd2)<1)
		{
			$RutaPresionArterial=GraficarPresionArterial();
		}
		else
		{
			$RutaPresionArterial=GraficarPresionArterial(1,$datayda2,$dataydb2,$dataxd2,$datafd1);
		}
		
		/*preguntamos sobre los arreglos de peso materno */
		if(sizeof($datayp2)<1 || sizeof($dataxp2)<1)
		{
			$RutaPeso=GraficarControlPeso();
		}
		else
		{
			$RutaPeso=GraficarControlPeso(1,$datayp2,$dataxp2,$datafd2);
		}

		/*preguntamos sobre los arreglos de incremento del cuello uterino */
		if(sizeof($datay2)<1 || sizeof($datax2)<1)
		{
			$RutaCurvaCreatinina=GraficarCurvaCreatinina();
		}
		else
		{
			$RutaCurvaCreatinina=GraficarCurvaCreatinina(1,$datay2,$datax2,$datafd3);
		}
		
		//en este grafico mostramos las graficas de gestacion.png
		$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida.="<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida.="<td>PRESION ARTERIAL</td>";
		$this->salida.="<td>PESO</td>";
		$this->salida.="<td>DEPRACION CREATININA</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr align=\"center\" class=\"hc_submodulo_list_oscuro\">";
	
		if(is_file($_ROOT."$RutaPresionArterial"))
		{
			$this->salida.="	<td>";
			$this->salida.="		<img src=$RutaPresionArterial border='1'>"; //aqui se imprime para mostrar el grafico
			$this->salida.="	</td>";
		}
	
		if(is_file($_ROOT."$RutaPeso"))
		{
			$this->salida.="	<td>";
			$this->salida.="		<img src=$RutaPeso border='1'>"; //aqui se imprime para mostrar el grafico
			$this->salida.="	</td>";
		}
		
		
		if(is_file($_ROOT."$RutaCurvaCreatinina"))
		{
			$this->salida.="  <td>";
			$this->salida.="    <img src=$RutaCurvaCreatinina border='1' >"; //aqui se imprime para mostrar el grafico
			$this->salida.="  </td>";
		}
		
	
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'PruebasLaboratorioReno'));
		$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionReno'));
		
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
}
?>