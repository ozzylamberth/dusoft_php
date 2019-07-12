<?php

/**
* Submodulo de Signos Vitales (HTML).
*
* Submodulo para manejar los signos vitales de un paciente en una evolucin.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_SignosVitalesOdontologia_HTML.php,v 1.12 2006/12/19 21:00:15 jgomez Exp $
*/

/**
* SignosVitales_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo signos vitales, se extiende la clase SignosVitales y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class SignosVitalesOdontologia_HTML extends SignosVitalesOdontologia
{

	function SignosVitalesOdontologia_HTML()
	{
	    $this->SignosVitalesOdontologia();//constructor del padre
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
          'autor'=>'JAIME ANDRES VALENCIA',
          'descripcion_cambio' => '',
          'requiere_sql' => false,
          'requerimientos_adicionales' => '',
          'version_kernel' => '1.0'
          );
          return $informacion;
     }

/////////////
  
     function frmConsulta()
     {
          $pfj=$this->frmPrefijo;
          $dato=$this->BusquedaDatosSignos();
          if(sizeof($dato[0])!=0)
          {
               $this->salida.="<br>";
               $this->salida.= "<table border=\"1\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list\">";
               $this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.= "<td align=\"center\" colspan=\"4\">";
               $this->salida.= "<label>CONSOLIDADO SIGNOS VITALES</label>";
               $this->salida.= "</td>";
               $this->salida.= "</tr>";
               $this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.= "<td align=\"center\">";
               $this->salida.= "<label>Tension Arterial</label>";
               $this->salida.= "</td>";
               $this->salida.= "<td>";
               $this->salida.= "<label>Frecuencia Cardiaca</label>";
               $this->salida.= "</td>";
               $this->salida.= "<td align=\"center\">";
               $this->salida.= "<label>Temperatura</label>";
               $this->salida.= "</td>";
               $this->salida.= "<td align=\"center\">";
               $this->salida.= "<label>Frecuencia Respiratoria</label>";
               $this->salida.= "</td>";
               $this->salida.= "</tr>";
               $i=0;
               $s=0;
               while($i<sizeof($dato[0]))
               {
                    if ($s==0)
                    {
                         $this->salida.= "<tr  class=\"hc_submodulo_list_oscuro\">";
                         $s=1;
                    }
                    else
                    {
                         $this->salida.= "<tr  class=\"hc_submodulo_list_claro\">";
                         $s=0;
                    }
                    $this->salida.= "<td align=\"center\">";
                    $this->salida.= "".$dato[0][$i]."&nbsp;mmHg&nbsp;/&nbsp;".$dato[1][$i]."&nbsp;mmHg";
                    $this->salida.= "</td>";
                    $this->salida.= "<td align=\"center\">";
                    $this->salida.= $dato[2][$i]."&nbsp;X min";
                    $this->salida.= "</td>";
                    $this->salida.= "<td align=\"center\">";
                    $this->salida.= $dato[3][$i]."&nbsp;ºC";
                    $this->salida.= "</td>";
                    $this->salida.= "<td align=\"center\">";
                    $this->salida.= $dato[4][$i]."&nbsp;X min";
                    $this->salida.= "</td>";
                    $this->salida.= "</tr>";
                    $reloj = $dato[5][$i];
                    $i++;
               }
               list($fecha,$hora) = explode(" ",$this->PartirFecha($reloj));
               list($ano,$mes,$dia) = explode("-",$fecha);
               list($hora,$min) = explode(":",$hora);
               $this->salida.="<tr>";
               $this->salida.="<td align=\"center\" colspan=\"4\" class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<label class='label' align='center'>FECHA Y HORA DE REGISTRO DE SIGNOS VITALES: <b>$fecha - $hora : $min</b></label>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
               $this->salida.="</table>";
               $this->salida.="<br>";
          }
          else
          {
               return false;
          }
          return true;
     }



	function frmHistoria()
	{
          $pfj=$this->frmPrefijo;
          $dato=$this->BusquedaDatosSignos();
          
          if(sizeof($dato[0])!=0)
		{
			$salida.= "<table border=\"1\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list\">";
			$salida.= "<tr class=\"hc_table_submodulo_list_title\">";
			$salida.= "<td align=\"center\" colspan=\"7\">";
			$salida.= "<label>SIGNOS VITALES</label>";
			$salida.= "</td>";
			$salida.= "</tr>";
			$salida.= "<tr class=\"hc_table_submodulo_list_title\">";
			$salida.= "<td align=\"center\">";
			$salida.= "<label>Tension Arterial</label>";
			$salida.= "</td>";
			$salida.= "<td align=\"center\">";
			$salida.= "<label>Frecuencia Cardiaca</label>";
			$salida.= "</td>";
			$salida.= "<td align=\"center\">";
			$salida.= "<label>Temperatura</label>";
			$salida.= "</td>";
			$salida.= "<td align=\"center\">";
			$salida.= "<label>Frecuencia Respiratoria</label>";
			$salida.= "</td>";
			$salida.= "</tr>";
     		$i=0;
			$s=0;
			while($i<sizeof($dato[0]))
			{
				if ($s==0)
				{
				$salida.= "<tr  class=\"hc_submodulo_list_oscuro\">";
				$s=1;
				}
				else
				{
				$salida.= "<tr  class=\"hc_submodulo_list_claro\">";
				$s=0;
				}
				$salida.= "<td align=\"center\">";
				$salida.= "".$dato[0][$i]."&nbsp;mmHg&nbsp;/&nbsp;".$dato[1][$i]."&nbsp;mmHg";
				$salida.= "</td>";
				$salida.= "<td align=\"center\">";
				$salida.= $dato[2][$i]."&nbsp;X min";
				$salida.= "</td>";
				$salida.= "<td align=\"center\">";
				$salida.= $dato[3][$i]."&nbsp;ºC";
				$salida.= "</td>";
				$salida.= "<td align=\"center\">";
				$salida.= $dato[4][$i]."&nbsp;X min";
				$salida.= "</td>";
				$salida.= "</tr>";
 				$i++;
			}
			$salida.= "</table>";
			$salida.="<br>";
		}
		else
		{
			return false;
		}
		return $salida;
	}


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

     function Signos_PrimeraVez()
     {
          $pfj=$this->frmPrefijo;
          $signos = $this->Get_Signos_PrimeraVez();
          if (!empty($signos))
          {
               $this->salida.= "<table border=\"1\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list\">";
               $this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.= "<td align=\"center\" colspan=\"4\">";
               $this->salida.= "<label>CONSOLIDADO SIGNOS VITALES PRIMERA VEZ</label>";
               $this->salida.= "</td>";
               $this->salida.= "</tr>";
               $this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.= "<td align=\"center\">";
               $this->salida.= "<label>Tension Arterial</label>";
               $this->salida.= "</td>";
               $this->salida.= "<td>";
               $this->salida.= "<label>Frecuencia Cardiaca</label>";
               $this->salida.= "</td>";
               $this->salida.= "<td align=\"center\">";
               $this->salida.= "<label>Temperatura</label>";
               $this->salida.= "</td>";
               $this->salida.= "<td align=\"center\">";
               $this->salida.= "<label>Frecuencia Respiratoria</label>";
               $this->salida.= "</td>";
               $this->salida.= "</tr>";
               foreach($signos as $k => $v)
               {
                    $this->salida.= "<tr align=\"center\" class=\"modulo_list_claro\">";
                    $this->salida.= "<td>";
                    $this->salida.= "<b><label>".$v[0]."&nbsp;mmHg&nbsp;/&nbsp;".$v[1]."&nbsp;mmHg</label></b>";
                    $this->salida.= "</td>";
                    $this->salida.= "<td>";
                    $this->salida.= "<b><label>".$v[2]."&nbsp;X min</label></b>";
                    $this->salida.= "</td>";
                    $this->salida.= "<td align=\"center\">";
                    $this->salida.= "<b><label>".$v[3]."&nbsp;ºC</label></b>";
                    $this->salida.= "</td>";
                    $this->salida.= "<td align=\"center\">";
                    $this->salida.= "<b><label>".$v[4]."&nbsp;X min</label></b>";
                    $this->salida.= "</td>";
                    $this->salida.= "</tr>";
               }
               $this->salida.= "</table>";
               list($fecha,$hora) = explode(" ",$this->PartirFecha($v[7]));
               list($ano,$mes,$dia) = explode("-",$fecha);
               list($hora,$min) = explode(":",$hora);
               $this->salida .="<label class='label' align='center'>FECHA Y HORA DE REGISTRO DE SIGNOS VITALES: <b>$fecha - $hora : $min</b><br><br></label>";
          }
          else
          {
          	$this->salida .="<div class='label_mark' align='center'>NO HAY SIGNOS VITALES REGISTRADOS EN LA CONSULTA ODONTOLOGICA DE PRIMERA VEZ<br></div>";
          }
          return true;
     }

	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		$result=$this->DatosSignos();
          
		if(empty($this->titulo))
		{
	 		$this->salida = ThemeAbrirTablaSubModulo('SIGNOS VITALES');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
		if($this->SetStyle("MensajeError"))
		{
			$this->salida.="<table align=\"center\">";
			$this->salida.=$this->SetStyle("MensajeError");
			$this->salida.="</table>";
		}
		$this->salida.="<table width=\"100%\"border=\"0\" align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table width=\"100%\" align=\"center\" border=\"0\" class=\"hc_table_submodulo_list\">";
                    
          list($fecha,$hora) = explode(" ",$this->PartirFecha($result->fields[7]));
          list($ano,$mes,$dia) = explode("-",$fecha);
          list($hora,$min) = explode(":",$hora);

        if(!empty($fecha) AND !empty($hora) AND !empty($min))
        {
          $this->salida .="<div class='label' align='center'>FECHA Y HORA DE REGISTRO DE SIGNOS VITALES: <b>$fecha - $hora : $min</b><br><br></div>";
        }
       else
        {
          $this->salida .="<div class='label' align='center'>FECHA Y HORA DE REGISTRO DE SIGNOS VITALES: <b>$fecha $hora $min</b><br><br></div>";
        }

		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">Tension Arterial</label>";
		$this->salida.="</td>";
          $this->salida.="<td align=\"right\">";
          
          if ($result->fields[0] == 'NULL')
          {
          	$result->fields[0] = '';
          }
          if ($_REQUEST['taalta'.$pfj] == 'NULL')
          {
          	$_REQUEST['taalta'.$pfj] = '';
          }
          
          if(empty($result->fields[0]))
          {
               $this->salida.="<input type=\"text\" maxlength=\"3\" name=\"taalta".$pfj."\" size=\"5\" class=\"input-text\" value=\"".$_REQUEST['taalta'.$pfj]."\">&nbsp;mmHg&nbsp;";
          }
          else
          {             
               $this->salida.="<input type=\"text\" maxlength=\"3\" name=\"taalta".$pfj."\" size=\"5\" class=\"input-text\" value=\"".$result->fields[0]."\">&nbsp;mmHg&nbsp;";
          }
          $this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="/";
		$this->salida.="</td>";
		$this->salida.="<td>";
          
          if ($result->fields[1] == 'NULL')
          {
          	$result->fields[1] = '';
          }
          if ($_REQUEST['tabaja'.$pfj] == 'NULL')
          {
          	$_REQUEST['tabaja'.$pfj] = '';
          }

          if(empty($result->fields[1]))
          {
               $this->salida.="<input type=\"text\" maxlength=\"3\" name=\"tabaja".$pfj."\" size=\"5\" class=\"input-text\" value=\"".$_REQUEST['tabaja'.$pfj]."\">&nbsp;mmHg&nbsp;";
          }
          else
          {             
               $this->salida.="<input type=\"text\" maxlength=\"3\" name=\"tabaja".$pfj."\" size=\"5\" class=\"input-text\" value=\"".$result->fields[1]."\">&nbsp;mmHg&nbsp;";
          }
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">Frecuencia Cardiaca</label>";
		$this->salida.="</td>";
		$this->salida.="<td>";
          
          if ($result->fields[2] == 'NULL')
          {
          	$result->fields[2] = '';
          }
          if ($_REQUEST['fc'.$pfj] == 'NULL')
          {
          	$_REQUEST['fc'.$pfj] = '';
          }

          if(empty($result->fields[2]))
          {
               $this->salida.="<input type=\"text\" maxlength=\"3\" name=\"fc".$pfj."\" size=\"5\" class=\"input-text\" value=\"".$_REQUEST['fc'.$pfj]."\">&nbsp;X min";
          }
          else
          {             
			$this->salida.="<input type=\"text\" maxlength=\"3\" name=\"fc".$pfj."\" size=\"5\" class=\"input-text\" value=\"".$result->fields[2]."\">&nbsp;X min";
          }
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">Frecuencia Respiratoria</label>";
		$this->salida.="</td>";
		$this->salida.="<td>";
          
          if ($result->fields[4] == 'NULL')
          {
          	$result->fields[4] = '';
          }
          if ($_REQUEST['fr'.$pfj] == 'NULL')
          {
          	$_REQUEST['fr'.$pfj] = '';
          }

          if(empty($result->fields[4]))
          {
               $this->salida.="<input type=\"text\" maxlength=\"3\" name=\"fr".$pfj."\" size=\"5\" class=\"input-text\"  value=\"".$_REQUEST['fr'.$pfj]."\">&nbsp;X min";
          }
          else
          {             
			$this->salida.="<input type=\"text\" maxlength=\"3\" name=\"fr".$pfj."\" size=\"5\" class=\"input-text\"  value=\"".$result->fields[4]."\">&nbsp;X min";
          }
          $this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">Temperatura</label>";
		$this->salida.="</td>";
		$this->salida.="<td>";
          
          if ($result->fields[3] == 'NULL')
          {
          	$result->fields[3] = '';
          }
          if ($_REQUEST['temperatura'.$pfj] == 'NULL')
          {
          	$_REQUEST['temperatura'.$pfj] = '';
          }

          if(empty($result->fields[3]))
          {
               $this->salida.="<input type=\"text\" maxlength=\"4\" name=\"temperatura".$pfj."\" size=\"5\" class=\"input-text\"  value=\"".$_REQUEST['temperatura'.$pfj]."\">&nbsp;ºC";
          }
          else
          {             
			$this->salida.="<input type=\"text\" maxlength=\"4\" name=\"temperatura".$pfj."\" size=\"5\" class=\"input-text\"  value=\"".$result->fields[3]."\">&nbsp;ºC";
          }
          $this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table width=\"0\" align=\"center\"><tr><td>";
		$this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR\" class=\"input-submit\">";
		$this->salida.="</td></tr></table><br>";
		$this->salida.="</form>";
          $this->Signos_PrimeraVez();
		$this->salida .= ThemeCerrarTablaSubModulo();
          return true;
	}
}

?>
