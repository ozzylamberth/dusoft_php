<?php

/**
* Submodulo de InscripcionPYP_HTML.
* $Id: hc_InscripcionPYP_InscripcionesPYP_HTML.class.php,v 1.6 2007/02/01 20:50:05 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
*/

class InscripcionesPYP_HTML
{
	function InscripcionesPYP_HTML()
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
		$this->salida="";
		return $this->salida;
	}
	
	function SetStyle($campo)
	{
		if ($this->frmError[$campo]||$campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td align=\"center\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}
	
	function frmForma($programas_ins,$programas_can)
	{
		$evolucion=SessionGetVar("Evolucion");
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		$paso=SessionGetVar("Paso");
		$pfj=SessionGetVar("Prefijo");
		
		$this->salida = ThemeAbrirTablaSubModulo('INSCRIPCION A PROGRAMAS PYP');
		$this->salida.= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida.= "  <tr class=\"modulo_table_list_title\"><td align=\"center\"> PROGRAMAS ACTUALES </td></tr>";
		
		if(sizeof($programas_ins)>0)
		{
			$k=0;
			for($i=0;$i<sizeof($programas_ins);$i++)
			{
				if($k%2==0)
					$estilo='hc_submodulo_list_claro';
				else
					$estilo='hc_submodulo_list_oscuro';
					
				$this->salida.= "  <tr class=\"$estilo\">";
				$this->salida.= "  <td><label class=\"label\">".strtoupper($programas_ins[$i][descripcion])."</label></td>";
				$this->salida.= "  </tr>";
				$k++;
			}
		}
		else
		{
			$this->salida .= "  <tr class=\"hc_submodulo_list_claro\">";
			$this->salida .= "  <td><label class=\"label\">NINGUNO</label></td>";
			$this->salida .= "  </tr>";
		}
		
		$this->salida .= "  </table>";
		$this->salida .= "  <br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_list_title\"><td align=\"center\"> PROGRAMAS A LOS CUALES ES CANDIDATO </td></tr>";
		
		if(sizeof($programas_can)>0)
		{
			$k=0;
			
			for($i=0;$i<sizeof($programas_can);$i++)
			{
				if($k%2==0)
					$estilo='hc_submodulo_list_claro';
				else
					$estilo='hc_submodulo_list_oscuro';
				
				$this->salida .= "  <tr class=\"$estilo\">";
				
				$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>$programas_can[$i][hc_modulo],'programa'.$pfj=>$programas_can[$i][programa_id]));
				$this->salida .= "  <td><label class=\"label\"><a href=\"$accion1\">".strtoupper($programas_can[$i][descripcion])."</a></label></td>";
				$this->salida .= "  </tr>";	
				$k++;
			}
		}
		else
		{
			$this->salida .= "  <tr class=\"hc_submodulo_list_claro\">";
			$this->salida .= "  	<td><label class=\"label\">NINGUNO</label></td>";
			$this->salida .= "  </tr>";
		}
		
		$this->salida .= "  </table><br>";
		$accion2=ModuloHCGetURL($evolucion,-1,0,'',false);
		$this->salida .= "	<form name=\"formavolver\" action=\"$accion2\" method=\"post\">";
		$this->salida .= "		<center><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\">";
		$this->salida .= "		</center>";
		$this->salida .= "	</form>";
		
		$this->salida .= ThemeCerrarTablaSubModulo();
    		
		return $this->salida;
	}
	
	function frmMensaje($mensaje)
	{
		$evolucion=SessionGetVar("Evolucion");
		$pfj=SessionGetVar("Prefijo");
		
		$this->salida = ThemeAbrirTablaSubModulo('INSCRIPCION A PROGRAMAS PYP');
		$this->salida.= "<table align=\"center\">"; 
		$this->salida.="<tr><td align=\"center\" class=\"label_error\"><img src=\"".GetThemePath()."/images/informacion.png\"> ".$mensaje."</td></tr>";
		$this->salida.= "</table><br>";
		
		$accion2=ModuloHCGetURL($evolucion,-1,0,'',false);
		$this->salida .= "	<form name=\"formavolver\" action=\"$accion2\" method=\"post\">";
		$this->salida .= "		<center><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\">";
		$this->salida .= "		</center>";
		$this->salida .= "	</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return $this->salida;
	}
	
}

?>