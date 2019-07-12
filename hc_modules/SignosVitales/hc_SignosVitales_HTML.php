<?php

/**
* Submodulo de Signos Vitales (HTML).
*
* Submodulo para manejar los signos vitales de un paciente en una evolución.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_SignosVitales_HTML.php,v 1.4 2006/12/19 21:00:15 jgomez Exp $
*/

/**
* SignosVitales_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo signos vitales, se extiende la clase SignosVitales y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class SignosVitales_HTML extends SignosVitales
{

	function SignosVitales_HTML()
	{
	    $this->SignosVitales();//constructor del padre
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

////////////////////
  
	function frmConsulta()
	{
          $pfj=$this->frmPrefijo;
          $dato=$this->BusquedaDatosSignos();
		if(sizeof($dato[0])!=0)
		{
			$this->salida.="<br>";
			$this->salida.= "<table border=\"1\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list\">";
			$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.= "<td align=\"center\" colspan=\"7\">";
			$this->salida.= "<label>SIGNOS VITALES</label>";
			$this->salida.= "</td>";
			$this->salida.= "</tr>";
			$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.= "<td align=\"center\">";
			$this->salida.= "<label>Tension Arterial</label>";
			$this->salida.= "</td>";
			$this->salida.= "<td align=\"center\">";
			$this->salida.= "<label>Frecuencia Cardiaca</label>";
			$this->salida.= "</td>";
			$this->salida.= "<td align=\"center\">";
			$this->salida.= "<label>Temperatura</label>";
			$this->salida.= "</td>";
			$this->salida.= "<td align=\"center\">";
			$this->salida.= "<label>Frecuencia Respiratoria</label>";
			$this->salida.= "</td>";
			$this->salida.= "<td align=\"center\">";
			$this->salida.= "<label>Peso</label>";
			$this->salida.= "</td>";
			$this->salida.= "<td align=\"center\">";
			$this->salida.= "<label>Talla</label>";
			$this->salida.= "</td>";
			$this->salida.= "<td align=\"center\">";
			$this->salida.= "<label>Masa Corporal</label>";
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
				$this->salida.= "".$dato[0][$i]."/".$dato[1][$i]."";
				$this->salida.= "</td>";
				$this->salida.= "<td align=\"center\">";
				$this->salida.= $dato[2][$i];
				$this->salida.= "</td>";
				$this->salida.= "<td align=\"center\">";
				$this->salida.= $dato[3][$i];
				$this->salida.= "</td>";
				$this->salida.= "<td align=\"center\">";
				$this->salida.= $dato[4][$i];
				$this->salida.= "</td>";
				$this->salida.= "<td align=\"center\">";
				$this->salida.= $dato[5][$i];
				$this->salida.= "</td>";
				$this->salida.= "<td align=\"center\">";
				$this->salida.= $dato[6][$i];
				$this->salida.= "</td>";
				$dato1=$dato[5][$i]/(pow(($dato[6][$i]/100),2));
				$this->salida.= "<td align=\"center\">";
				$this->salida.= $dato1;
				$this->salida.= "</td>";
                    
                    $reloj = $dato[7][$i];
				$this->salida.= "</tr>";
				$i++;
			}
               
               list($fecha,$hora) = explode(" ",$this->PartirFecha($reloj));
               list($ano,$mes,$dia) = explode("-",$fecha);
               list($hora,$min) = explode(":",$hora);
               $this->salida .="<div class='label' align='center'>FECHA Y HORA DE REGISTRO DE SIGNOS VITALES: <b>$fecha - $hora : $min</b><br><br></div>";

			$this->salida.= "</table>";
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
			$salida.="<br>";
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
			$salida.= "<td align=\"center\">";
			$salida.= "<label>Peso</label>";
			$salida.= "</td>";
			$salida.= "<td align=\"center\">";
			$salida.= "<label>Talla</label>";
			$salida.= "</td>";
			$salida.= "<td align=\"center\">";
			$salida.= "<label>Masa Corporal</label>";
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
				$salida.= "".$dato[0][$i]."/".$dato[1][$i]."";
				$salida.= "</td>";
				$salida.= "<td align=\"center\">";
				$salida.= $dato[2][$i];
				$salida.= "</td>";
				$salida.= "<td align=\"center\">";
				$salida.= $dato[3][$i];
				$salida.= "</td>";
				$salida.= "<td align=\"center\">";
				$salida.= $dato[4][$i];
				$salida.= "</td>";
				$salida.= "<td align=\"center\">";
				$salida.= $dato[5][$i];
				$salida.= "</td>";
				$salida.= "<td align=\"center\">";
				$salida.= $dato[6][$i];
				$salida.= "</td>";
				$dato1=$dato[5][$i]/(pow(($dato[6][$i]/100),2));
				$salida.= "<td align=\"center\">";
				$salida.= $dato1;
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
		$this->salida.="<table width=\"100%\"border=\"0\" align=\"center\"  class=\"hc_table_submodulo_list\">";
          
          list($fecha,$hora) = explode(" ",$this->PartirFecha($result->fields[7]));
          list($ano,$mes,$dia) = explode("-",$fecha);
          list($hora,$min) = explode(":",$hora);

          $this->salida .="<div class='label' align='center'>FECHA Y HORA DE REGISTRO DE SIGNOS VITALES: <b>$fecha - $hora : $min</b><br><br></div>";

          $this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table width=\"632\" align=\"center\" border=\"0\">";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">T.A.</label>";
		$this->salida.="</td>";
		$this->salida.="<td align=\"right\">";
		if(!empty($result->fields[0]))
		{
			$taalta=$result->fields[0];
		}
		elseif ($_REQUEST['taalta'.$pfj] == 'NULL')
		{
			$taalta = '';
		}
		else
		{
			$taalta = $_REQUEST['taalta'.$pfj];
		}
		$this->salida.="<input type=\"text\" maxlength=\"3\" name=\"taalta".$pfj."\" size=\"5\" class=\"input-text\" value=\"".$taalta."\">";
          $this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="/";
		$this->salida.="</td>";
		$this->salida.="<td>";
		if(!empty($result->fields[1]))
		{
			$tabaja=$result->fields[1];
		}
		elseif ($_REQUEST['tabaja'.$pfj] == 'NULL')
		{
			$tabaja = '';
		}
		else
		{
			$tabaja = $_REQUEST['tabaja'.$pfj];
		}
		$this->salida.="<input type=\"text\" maxlength=\"3\" name=\"tabaja".$pfj."\" size=\"5\" class=\"input-text\" value=\"".$tabaja."\">";
          $this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">F.C</label>";
		$this->salida.="</td>";
		$this->salida.="<td>";
		if(!empty($result->fields[2]))
		{
			$fc=$result->fields[2];
		}
		elseif ($_REQUEST['fc'.$pfj] == 'NULL')
		{
			$fc = '';
		}
		else
		{
			$fc = $_REQUEST['fc'.$pfj];
		}
		$this->salida.="<input type=\"text\" maxlength=\"3\" name=\"fc".$pfj."\" size=\"5\" class=\"input-text\" value=\"".$fc."\">";
          $this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">F.R</label>";
		$this->salida.="</td>";
		$this->salida.="<td>";
		if(!empty($result->fields[4]))
		{
			$fr=$result->fields[4];
		}
		elseif ($_REQUEST['fr'.$pfj] == 'NULL')
		{
			$fr = '';
		}
		else
		{
			$fr = $_REQUEST['fr'.$pfj];
		}
		$this->salida.="<input type=\"text\" maxlength=\"3\" name=\"fr".$pfj."\" size=\"5\" class=\"input-text\"  value=\"".$fr."\">";
   		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">Tº</label>";
		$this->salida.="</td>";
		$this->salida.="<td>";
		
		if(!empty($result->fields[3]))
		{
				$temperatura=$result->fields[3];
		}
		elseif ($_REQUEST['temperatura'.$pfj] == 'NULL')
		{
			$temperatura='';
		}
		else
		{
			$temperatura = $_REQUEST['temperatura'.$pfj];
		}
		$this->salida.="<input type=\"text\" maxlength=\"4\" name=\"temperatura".$pfj."\" size=\"5\" class=\"input-text\"  value=\"".$temperatura."\">";
          $this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">Peso</label>";
		$this->salida.="</td>";
		$this->salida.="<td>";
		if(!empty($result->fields[5]))
		{
			$peso=$result->fields[5];
		}
		elseif ($_REQUEST['peso'.$pfj] == 'NULL')
		{
			$peso = '';
		}
		else
		{
			$peso=$_REQUEST['peso'.$pfj];
		}
		$this->salida.="<input type=\"text\" maxlength=\"5\" name=\"peso".$pfj."\" size=\"5\" class=\"input-text\"  value=\"".$peso."\">";
		$this->salida.="<label class=\"label\">Kg</label>";
          $this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">Talla</label>";
		$this->salida.="</td>";
		$this->salida.="<td>";
		if(!empty($result->fields[6]))
		{
			$talla=$result->fields[6];
		}
		elseif ($_REQUEST['talla'.$pfj] == 'NULL')
		{
			$talla= '';
		}
		else
		{
			$talla = $_REQUEST['talla'.$pfj];
		}
		$this->salida.="<input type=\"text\" maxlength=\"3\" name=\"talla".$pfj."\" size=\"3\" class=\"input-text\"  value=\"".$talla."\">";
		$this->salida.="<label class=\"label\">cm</label>";
   		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="<label class=\"label\">Masa Corporal</label>";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$dato=$result->fields[5]/(pow(($result->fields[6]/100),2));
		$this->salida.="<input type=\"text\" maxlength=\"8\" name=\"MC\" size=\"5\" class=\"input-text\"  value=\"".$dato."\" READONLY>";
          $this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";
	  	$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="<table width=\"0\" align=\"center\"><tr><td>";
		$this->salida.="<input type=\"submit\" value=\"Insertar\" class=\"input-submit\">";
		$this->salida.="</td></tr></table>";
		$this->salida.="</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
          return true;
	}
}

?>
