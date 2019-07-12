<?php

/**
* Submodulo de Diagnosticos Medicos (HTML).
*
* Submodulo para manejar el ingreso de Diagnosticos Medicos.
* @author Tizziano Perea O. <tperea@ipsoft-sa.com>
* @version 1.0
* @package SIIS
* $Id: hc_DiagnosticosMedicos_HTML.php,v 1.5 2006/12/19 21:00:13 jgomez Exp $
*/

/**
* DiagnosticosMedicos_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo Diagnosticos Medicos, se extiende la clase DiagnosticosMedicos y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class DiagnosticosMedicos_HTML extends DiagnosticosMedicos
{

	function DiagnosticosMedicos_HTML()
	{
		$this->DiagnosticosMedicos();//constructor del padre
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
    'autor'=>'TIZZIANO PEREA OCORO',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }

  
	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$datos=$this->Busqueda_Diagnosticos_Medicos();

		if($datos===false)
		{
			return false;
		}
		if(!empty($datos))
		{
			$this->salida .="<br><table width=\"90%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
               $this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida .="<td>FECHA</td>";
               $this->salida .="<td align=\"center\">LISTADO DE DIAGNOSTICOS MEDICOS</td>";
               $this->salida .="</tr>";

			foreach($datos as $k=>$v)
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

				$this->salida .="<td width='10%' align='center'>$k</td>";


				$this->salida .="<td><table border='0' width='100%' class=\"hc_submodulo_list_oscuro\">";
				foreach($v as $k2=>$vector){

					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>$vector[hora]</b></td>";
					$this->salida .="<td>";
					$this->salida .=$vector[usuario].' - '.$vector[nombre]."";
					$this->salida .="</tr class=\"hc_submodulo_list_claro\">";
					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td>&nbsp;</td>";
					$this->salida .="<td width='100%'>$vector[descripcion]</td>";
					$this->salida .="</tr>";
					$this->salida .="<tr>";

				}
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}

			$this->salida.="</table>";
		}
          else
          {
               $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
               $this->salida.="<tr  align=\"center\"><br><td><label class='label_mark'>NO HAY RESUMEN PARA ESTE PACIENTE</label>";
               $this->salida.="</td></tr>";
               $this->salida.="</table>";
               return false;
          }
	    return true;
	}


     function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$datos=$this->Busqueda_Diagnosticos_Medicos();

		if($datos===false)
		{
			return false;
		}
		if(!empty($datos))
		{
			$salida .="<br><table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
               $salida .="<tr class=\"hc_table_submodulo_list_title\">";
               $salida .="<td>FECHA</td>";
               $salida .="<td align=\"center\">LISTADO DE DIAGNOSTICOS MEDICOS</td>";
               $salida .="</tr>";

			foreach($datos as $k=>$v)
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

				$salida .="<td width='10%' align='center'>$k</td>";


				$salida .="<td><table border='0' width='100%' class=\"hc_submodulo_list_oscuro\">";
				foreach($v as $k2=>$vector){

					$salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$salida .="<td><b>$vector[hora]</b></td>";
					$salida .="<td>";
					$salida .=$vector[usuario].' - '.$vector[nombre]."";
					$salida .="</tr class=\"hc_submodulo_list_claro\">";
					$salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$salida .="<td>&nbsp;</td>";
					$salida .="<td width='100%'>$vector[descripcion]</td>";
					$salida .="</tr>";
					$salida .="<tr>";

				}
				$salida .="</table>";
				$salida .="</td>";
				$salida .="</tr>";
			}

			$salida.="</table><br>";
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
		$datos=$this->Busqueda_Diagnosticos_Medicos();
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('DIAGNOSTICOS MEDICOS');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\">";

		if($this->SetStyle("MensajeError"))
		{
			$this->salida.="<table align=\"center\">";
			$this->salida.=$this->SetStyle("MensajeError");
			$this->salida.="</table>";
		}

		$this->salida.="<table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class='modulo_table_list_title'>";
		$this->salida.="<td align='center'>DIAGNOSTICOS MEDICOS";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align='center' class='hc_submodulo_list_claro'>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Insertar_Diagnosticos'));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<br><textarea name=\"descripciones".$pfj."\" cols=\"80\" rows=\"7\" style = \"width:90%\" class=\"textarea\">".$datos['descripcion']."</textarea>";
		$this->salida.="<p align=\"center\">";
		$this->salida.="<input type=\"submit\" value=\"Insertar\" class=\"input-submit\" name=\"insertar$pfj\">";
		$this->salida.="</p><p></p>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</form>";
		$this->salida.="</table>";
		$this->frmConsulta();
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

}

?>
