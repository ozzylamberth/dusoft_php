<?php

/**
* Submodulo de Cronicos (HTML).
*
* Submodulo para manejar los problemas cronicos de un paciente.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_Cronicos_HTML.php,v 1.6 2005/05/12 23:38:29 tizziano Exp $
*/

/**
* Cronicos_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo cronicos, se extiende la clase Cronicos y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Cronicos_HTML extends Cronicos
{

	function Cronicos_HTML()
	{
	    $this->Cronicos();//constructor del padre
       	return true;
	}

	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$cron=$this->BusquedaAntecedentes();
		if(sizeof($cron[0])!=0)
		{
			$this->salida.="<table align=\"center\" width=\"100%\">";
			$this->salida.="<tr>";
			$this->salida.="<td>";
			$this->salida.="<table border=\"1\" width=\"100%\" class=\"hc_table_list\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"70%\">";
			$this->salida.="INSCRIPCIÓN P & P";
			$this->salida.="</td>";
			$this->salida.="<td width=\"30%\">";
			$this->salida.="REALIZACION";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$i=0;
			while($i<sizeof($cron[0]))
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
				$this->salida.="<td>";
				$this->salida.=$cron[1][$i];
				$this->salida.="</td>";
				$this->salida.="<td>";
				if($cron[2][$i]=="1")
				{
					$this->salida.="Si";
				}
				else
				{
					$this->salida.="No";
				}
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$i++;
			}
			$this->salida.="</table>";
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
		$cron=$this->BusquedaAntecedentes();
		if(sizeof($cron[0])!=0)
		{
			$salida.="<br>";
			$salida.="<table align=\"center\" width=\"100%\">";
			$salida.="<tr>";
			$salida.="<td>";
			$salida.="<table border=\"1\" width=\"100%\" class=\"hc_table_list\">";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td width=\"70%\">";
			$salida.="INSCRIPCIÓN P & P";
			$salida.="</td>";
			$salida.="<td width=\"30%\">";
			$salida.="REALIZACION";
			$salida.="</td>";
			$salida.="</tr>";
			$i=0;
			while($i<sizeof($cron[0]))
			{
				if($spy==0)
				{
					$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$salida.="<tr class=\"hc_submodulo_list_claro\">";
					$spy=0;
				}
				$salida.="<td>";
				$salida.=$cron[1][$i];
				$salida.="</td>";
				$salida.="<td>";
				if($cron[2][$i]=="1")
				{
					$salida.="Si";
				}
				else
				{
					$salida.="No";
				}
				$salida.="</td>";
				$salida.="</tr>";
				$i++;
			}
			$salida.="</table>";
			$salida.="</td>";
			$salida.="</tr>";
			$salida.="</table>";
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
			return ("<tr><td class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("hc_tderror");
		}
		return ("hc_tdlabel");
	}


	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		$cron=$this->BusquedaTipoAntecedentes($this->BusquedaAntecedentes1());
		if(empty($this->titulo))
		{
			$this->salida  = ThemeAbrirTablaSubModulo('INSCRIPCIÓN P & P');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar', 'Vec_Cronico'.$pfj=>$cron[0]));
		$this->salida.='<form name="prueba'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"hc_table_list\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td>";
		$this->salida.="NOMBRE";
          $this->salida.="</td>";
	  	$this->salida.="<td>";
		$this->salida.="SI";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="NO";
		$this->salida.="</td>";
	  	$this->salida.="</tr>";
		$i=0;
		while($i<sizeof($cron[0]))
		{
               $this->salida.="<tr>";
			$this->salida.="<td>";
			$this->salida.="<label class=\"label\">".$cron[1][$i]."</label>";
			$this->salida.="</td>";
			$this->salida.="<td>";
			if($cron[2][$i]=="1")
			{
				$this->salida.="<input type=\"radio\" name=\"sino".$cron[0][$i].$pfj."\" value=\"1\" class=\"input-text\" checked=\"true\">";
			}
			else
			{
				$this->salida.="<input type=\"radio\" name=\"sino".$cron[0][$i].$pfj."\" value=\"1\" class=\"input-text\">";
			}
			$this->salida.="</td>";
			$this->salida.="<td>";
			if($cron[2][$i]=="0")
			{
				$this->salida.="<input type=\"radio\" name=\"sino".$cron[0][$i].$pfj."\" value=\"0\" class=\"input-text\" checked=\"true\">";
			}
			elseif ($cron[2][$i]=="")
			{
				$this->salida.="<input type=\"radio\" name=\"sino".$cron[0][$i].$pfj."\" value=\"0\" class=\"input-text\" checked=\"true\">";
			}
			else
			{
				$this->salida.="<input type=\"radio\" name=\"sino".$cron[0][$i].$pfj."\" value=\"0\" class=\"input-text\">";
			}
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$i++;
		}
          $this->salida.="</table><br>";
		$this->salida.="<table width=\"0\" align=\"center\"><tr><td>";
		$this->salida.="<input type=\"submit\" value=\"INSERTAR\" class=\"input-submit\">";
		$this->salida.="</td></tr></table><br>";
		$this->salida.="</form>";
		$this->frmConsulta();
		$this->salida.= ThemeCerrarTablaSubModulo();
		return true;
	}

}

?>
