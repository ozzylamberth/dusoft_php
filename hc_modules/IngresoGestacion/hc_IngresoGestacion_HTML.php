<?php

/**
* Submodulo de Ingreso Gestacion (HTML).
* NUEVA VERSION
* Submodulo para manejar los ingresos de pacientes con etapas de gestación activas.
* @author Tizziano Perea <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_IngresoGestacion_HTML.php,v 1.2 2005/03/09 13:24:20 tizziano Exp $
*/

/**
* IngresoGestacion_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo de ingreso de gestacion, se extiende la clase IngresoGestacion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class IngresoGestacion_HTML extends IngresoGestacion
{

	function IngresoGestacion_HTML()
	{
	    $this->IngresoGestacion();//constructor del padre
       	return true;
	}


	function frmConsulta()
	{
    	$pfj=$this->frmPrefijo;
		$datos=$this->Resumen_Gestaciones();
    	if($datos===false)
  		{
			return false;
		}
		if(!empty($datos))
		{
      		$this->salida.="<br>";
			$this->salida.="<table border=\"1\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"6\">RESUMEN ETAPAS DE GESTACION</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"10%\">CODIGO</td>";
		    $this->salida.="<td align=\"center\" width=\"15%\">INICIO GESTACION</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">FIN GESTACION</td>";
			$this->salida.="<td align=\"center\" width=\"5%\">NUMERO DE EMBARAZO</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">ULTIMA MENSTRUACION</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">ULTIMO PARTO</td>";
			$this->salida.="</tr>";
			for ($i=0;$i<sizeof($datos);$i++)
			{
				$spy=0;
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
				$this->salida.="<td align=\"center\">".$datos[$i][gestacion_id]."</td>";
				$this->salida.="<td align=\"center\">".$datos[$i][gestacion_fecha_inicio]."</td>";
				$this->salida.="<td align=\"center\">".$datos[$i][gestacion_fecha_fin]."</td>";
				$this->salida.="<td align=\"center\">".$datos[$i][gestacion_num_embarazo]."</td>";
				$this->salida.="<td align=\"center\">".$datos[$i][fum]."</td>";
				$this->salida.="<td align=\"center\">".$datos[$i][fup]."</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
			$this->salida.="<br>";
		}
		else
		{
			return false;
		}
    	return true;
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
		$datos=$this->SeleccionGestantes();
		$sexpaciente=$this->SexodePaciente();
		if ($sexpaciente[0]['sexo_id']=='F')
		{
			if (!empty($datos)||($datos[0]['estado']=='1'))
			{
				if(empty($this->titulo))
				{
					$this->salida = ThemeAbrirTablaSubModulo('INFORMACION DE GESTANTES');
				}
				else
				{
					$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
				}

				$this->salida.="<table width=\"70%\" border=\"0\" align=\"center\">";
				$this->salida.="<tr>";
				$this->salida.="<td>";
				if($this->SetStyle("MensajeError"))
				{
					$this->salida.="<table align=\"center\">";
					$this->salida.=$this->SetStyle("MensajeError");
					$this->salida.="</table>";
				}
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr>";
				$this->salida.="<td>";
				$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="<td width=\"40%\" align=\"left\">INICIO ETAPA DE GESTACION"; "</td>";


				$this->salida.="<td width=\"20%\" align=\"center\">".$datos[0]['gestacion_fecha_inicio']."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$this->salida.="<td width=\"40%\" align=\"left\">FINAL ETAPA DE GESTACION"; "</td>";
				$this->salida.="<td width=\"20%\" align=\"center\">".$datos[0]['gestacion_fecha_fin']."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="<td width=\"40%\" align=\"left\">FECHA DE ULTIMA MENSTRUACION"; "</td>";
				$this->salida.="<td width=\"20%\" align=\"center\">".$datos[0]['fum']."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$this->salida.="<td width=\"40%\" align=\"left\">FECHA ULTIMO PARTO"; "</td>";
				$this->salida.="<td width=\"20%\" align=\"center\">".$datos[0]['fup']."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="<td width=\"40%\" align=\"left\">NUMERO DE EMBARAZOS ANTERIORES"; "</td>";
				$this->salida.="<td width=\"20%\" align=\"center\">".$datos[0]['gestacion_num_embarazo']."</td>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<br>";
				$this->salida.="<tr>";
				$this->salida.="<td>";
				$this->salida.="<table width=\"100%\">";
				$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Desactivar_Gestacion', 'gestar'.$pfj=>$datos[0]['gestacion_id']));
				$this->salida.= "<form name=\"formages$pfj\" action=\"$accionI\" method=\"post\">";

				$this->salida.="<tr>";
				$this->salida.="<td align=\"center\"><input class=\"input-submit\" name=\"desactivar$pfj\" type=\"submit\" value=\"DESACTIVAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</form>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="</table>";
				$this->salida.= ThemeCerrarTablaSubModulo();
			}
			else
			{
				if (empty($datos)||$datos[0]['estado']=='0')
				{
				if(empty($this->titulo))
				{
					$this->salida = ThemeAbrirTablaSubModulo('INGRESO DE GESTANTES');
				}
				else
				{
					$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
				}

				$this->salida.="<table width=\"80%\" border=\"0\" align=\"center\">";
				$this->salida.="<tr>";
				$this->salida.="<td>";
				if($this->SetStyle("MensajeError"))
				{
					$this->salida.="<table align=\"center\">";
					$this->salida.=$this->SetStyle("MensajeError");
					$this->salida.="</table>";
				}
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
				$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
				$this->salida.="<tr>";
				$this->salida.="<td>";
				$this->salida.="<table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="<td class=".$this->SetStyle("inicio")." width=\"50%\" align=\"left\">INICIO ETAPA DE GESTACION"; "</td>";
				$this->salida.="<td width=\"50%\" align=\"left\"><input type='text' readonly class='input-text'  size = 15 maxlength=10 name = 'ietapa$pfj'  value =\"".$_REQUEST['ietapa'.$pfj]."\">";
				$this->salida.="&nbsp;&nbsp;".ReturnOpenCalendario("forma$pfj",'ietapa'.$pfj, '/');
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$this->salida.="<td class=".$this->SetStyle("fin")." width=\"50%\" align=\"left\">FINAL ETAPA DE GESTACION"; "</td>";
				$this->salida.="<td width=\"50%\" align=\"left\"><input type='text' readonly class='input-text'  size = 15 maxlength= 10 name = 'fetapa$pfj'  value =\"".$_REQUEST['fetapa'.$pfj]."\">";
				$this->salida.="&nbsp;&nbsp;".ReturnOpenCalendario("forma$pfj",'fetapa'.$pfj, '/')."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="<td class=".$this->SetStyle("fum")." width=\"50%\" align=\"left\">FECHA DE ULTIMA MENSTRUACION"; "</td>";
				$this->salida.="<td width=\"50%\" align=\"left\"><input type='text' readonly class='input-text'  size = 15 maxlength= 10 name = 'fmenstruacion$pfj'  value =\"".$_REQUEST['fmenstruacion'.$pfj]."\">";
				$this->salida.="&nbsp;&nbsp;".ReturnOpenCalendario("forma$pfj",'fmenstruacion'.$pfj,'/')."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				$this->salida.="<td class=".$this->SetStyle("fup")." width=\"50%\" align=\"left\">FECHA ULTIMO PARTO"; "</td>";
				$this->salida.="<td width=\"50%\" align=\"left\"><input type='text' readonly class='input-text'  size = 15 maxlength= 10 name = 'fparto$pfj'  value =\"".$_REQUEST['fparto'.$pfj]."\">";
				$this->salida.="&nbsp;&nbsp;".ReturnOpenCalendario("forma$pfj",'fparto'.$pfj,'/')."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida.="<td width=\"50%\" align=\"left\">NUMERO DE EMBARAZOS ANTERIORES"; "</td>";
				$this->salida.="<td width=\"50%\" align=\"left\"><input type='text' class='input-text'  size = 5 maxlength= 2 name = 'nembarazo$pfj'  value =\"".$_REQUEST['nembarazo'.$pfj]."\">";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="<tr>";
				$this->salida.="<td>";
				$this->salida.="<table width=\"100%\">";
				$this->salida.="<tr>";
				$this->salida.="<td width=\"100%\" align=\"center\"><input type='submit' class='submit' value='Insertar'>";"</td>";
				$this->salida.="</tr>";
				$this->salida.="</form>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.= ThemeCerrarTablaSubModulo();
				}
			}
		}
		else
		{
			$this->salida.= ThemeAbrirTablaSubModulo('PACIENTE NO APTO');
			$this->salida.="<table width=\"50%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida.="<tr>";
			$this->salida.="<td align=\"center\">PACIENTE NO APTO PARA EL INGRESO DE UNA GESTACION";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.= ThemeCerrarTablaSubModulo();
		}
		return true;
	}


	function frmHistoria()
	{
    	$pfj=$this->frmPrefijo;
		$datos=$this->Resumen_Gestaciones();
    	if($datos===false)
  		{
			return false;
		}
		if(!empty($datos))
		{
      		$salida.="<br>";
			$salida.="<table border=\"1\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="<td align=\"center\" colspan=\"6\"><font size=\"2\" face=\"arial\">RESUMEN ETAPAS DE GESTACION</font></td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td align=\"center\" width=\"10%\"><font size=\"2\" face=\"arial\">CODIGO</font></td>";
		    $salida.="<td align=\"center\" width=\"15%\"><font size=\"2\" face=\"arial\">INICIO GESTACION</font></td>";
			$salida.="<td align=\"center\" width=\"15%\"><font size=\"2\" face=\"arial\">FIN GESTACION</font></td>";
			$salida.="<td align=\"center\" width=\"5%\"><font size=\"2\" face=\"arial\">NUMERO DE EMBARAZO</font></td>";
			$salida.="<td align=\"center\" width=\"15%\"><font size=\"2\" face=\"arial\">ULTIMA MENSTRUACION</font></td>";
			$salida.="<td align=\"center\" width=\"15%\"><font size=\"2\" face=\"arial\">ULTIMO PARTO</font></td>";
			$salida.="</tr>";
			for ($i=0;$i<sizeof($datos);$i++)
			{
				$spy=0;
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
				$salida.="<td align=\"center\"><font size=\"2\" face=\"arial\">".$datos[$i][gestacion_id]."</font></td>";
				$salida.="<td align=\"center\"><font size=\"2\" face=\"arial\">".$datos[$i][gestacion_fecha_inicio]."</font></td>";
				$salida.="<td align=\"center\"><font size=\"2\" face=\"arial\">".$datos[$i][gestacion_fecha_fin]."</font></td>";
				$salida.="<td align=\"center\"><font size=\"2\" face=\"arial\">".$datos[$i][gestacion_num_embarazo]."</font></td>";
				$salida.="<td align=\"center\"><font size=\"2\" face=\"arial\">".$datos[$i][fum]."</font></td>";
				$salida.="<td align=\"center\"><font size=\"2\" face=\"arial\">".$datos[$i][fup]."</font></td>";
				$salida.="</tr>";
			}
			$salida.="</table>";
			$salida.="<br>";
		}
		else
		{
			return false;
		}
		return $salida;
	}

}


?>
