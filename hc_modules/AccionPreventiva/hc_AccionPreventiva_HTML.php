<?php

/**
 * $Id: hc_AccionPreventiva_HTML.php,v 1.12 2005/08/02 16:42:00 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Submodulo para controlar las acciones preventivas de odontologia a realizar en el paciente
 */

/**
* Accion Preventiva
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de accion preventiva.
*/

class AccionPreventiva_HTML extends AccionPreventiva
{

	function AccionPreventiva_HTML()
	{
		$this->AccionPreventiva();//constructor del padre
          $this->backcolorf="#990000";
		return true;
	}

	function SetStyle($campo)
	{
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr><td align=\"center\" class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("hc_tderror");
		}
		return ("hc_tdlabel");
	}

	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		$this->salida =ThemeAbrirTablaSubModulo('ACCIÓN PREVENTIVA');

          $datos='';
          $datos=$this->BuscarAccionPreventiva();
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar','datos'.$pfj=>$datos));
          $this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
          $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"label_error\">";
          $this->salida.=$this->SetStyle("MensajeError");
          $this->salida.="</table>";
          $this->salida.="<table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
	     $this->salida.="<tr class=\"hc_submodulo_list_oscuro\"><td></td><td></td><td align=center class=label>FRECUENCIA</td></tr>";
          $ciclo=sizeof($datos);
          $j=0;
          for($i=0;$i<$ciclo;$i++)
          {
               if($j==0)
               {
                    $color="class=\"hc_submodulo_list_claro\"";
                    $j=1;
               }
               else
               {
                    $color="class=\"hc_submodulo_list_oscuro\"";
                    $j=0;
               }
               $this->salida.="<tr $color>";
               $this->salida.="<td width=\"35%\" align=\"center\">";
               $this->salida.="<label class=\"label\">".$datos[$i]['nombre']."</label>";
               $this->salida.="</td>";
               $this->salida.="<td width=\"10%\" align=\"center\">";
               if($datos[$i]['tipo_accion_id']<>7)
               {
                    $this->salida.="	<table width=\"100%\" border=\"0\" align=\"center\">";
                    $this->salida.="	<tr>";
                    $this->salida.="	<td class=\"label\" align=\"right\">";
                    $this->salida.="SI    ";
                    if($datos[$i]['sw_accion_preventiva']==1 AND $datos[$i]['hc_accion_preventiva_id']<>NULL)
                    {
                         $this->salida.="<input type=\"radio\" name=\"accionprev".$pfj."".$i."\" value=1 checked>";
                    }
                    else
                    {
                         $this->salida.="<input type=\"radio\" name=\"accionprev".$pfj."".$i."\" value=1>";
                    }
                    $this->salida.="	</td>";
                    $this->salida.="	</tr>";
                    $this->salida.="	<tr>";
                    $this->salida.="	<td class=\"label\" align=\"right\">";
                    $this->salida.="NO    ";
                    if($datos[$i]['sw_accion_preventiva']==0 AND $datos[$i]['hc_accion_preventiva_id']<>NULL)
                    {
                         $this->salida.="<input type=\"radio\" name=\"accionprev".$pfj."".$i."\" value=0 checked>";
                    }
                    else
                    {
                         $this->salida.="<input type=\"radio\" name=\"accionprev".$pfj."".$i."\" value=0>";
                    }
                    $this->salida.="	</td>";
                    $this->salida.="	</tr>";
                    $this->salida.="	</table>";
               }
               $this->salida.="</td>";
               $this->salida.="<td width=\"55%\" align=\"center\">";
               $this->salida.="<textarea class=\"input-text\" name=\"observacio".$pfj."".$i."\" cols=\"50\" rows=\"5\">".$datos[$i]['descripcion']."</textarea>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
          }
          //*********** VALIDO PARA FECHA REGISTRO
          list($fecha,$hora) = explode(" ",$this->PartirFecha($datos[0]['fecha_registro']));

          $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
          $this->salida.="<td width=\"100%\" align=\"center\" colspan=\"3\" class=\"label\">FECHA DE REGISTRO DE LA ACCION PREVENTIVA:    <font color=\"".$this->backcolorf."\">".$fecha."</font></td>";
          $this->salida.="</tr>";
          //***********
          $this->salida.="</table><br>";
          $this->salida.="<table width=\"10%\" align=\"center\">";
          $this->salida.="<tr>";
          $this->salida.="<td align=\"center\">";
          $this->salida.="<input type=\"submit\" name=\"insertar\" value=\"INSERTAR\" class=\"input-submit\">";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</form>";
          
          $datos=$this->BuscarAccionPreventivaAnterior();
		if(!empty($datos) AND $datos[0][evolucion_id]!=$this->evolucion)
		{
               $this->frmAccionPrimeraVez($datos);
		}

		$this->salida.=ThemeCerrarTablaSubModulo();
		return true;
	}

	function frmAccionPrimeraVez($datos)
	{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class='hc_table_submodulo_list_title'>";
			$this->salida.="<td colspan=\"3\">";
			$this->salida.="CONSOLIDADO ACCIÓN PREVENTIVA PRIMERA VEZ";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$ciclo=sizeof($datos);
			$j=0;
			for($i=0;$i<$ciclo;$i++)
			{
                    if($j==0)
                    {
                         $this->salida.="<tr class=\"hc_submodulo_list_claro\">";
                         $j=1;
                    }
                    else if($j==1)
                    {
                         $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
                         $j=0;
                    }
                    $this->salida.="<td width=\"35%\" align=\"justify\">";
                    $this->salida.="<label class=\"label\">".$datos[$i]['nombre']."</label>";
                    $this->salida.="</td>";
                    $this->salida.="<td width=\"8%\" align=\"center\">";
                    if($datos[$i]['nombre']<>'OBSERVACIONES')
                    {
                         if($datos[$i]['sw_accion_preventiva']==1)
                         {
                              $this->salida.="SI";
                         }
                         else
                         {
                              $this->salida.="NO";
                         }
                    }
                    $this->salida.="</td>";
                    $this->salida.="<td width=\"58%\" align=\"justify\">";
                    $this->salida.="".$datos[$i]['descripcion']."";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
			}
               
               //*********** VALIDO PARA FECHA REGISTRO
               list($fecha,$hora) = explode(" ",$this->PartirFecha($datos[0]['fecha_registro']));
     
               $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
               $this->salida.="<td width=\"100%\" align=\"center\" colspan=\"3\" class=\"label\">FECHA DE REGISTRO DE LA ACCION PREVENTIVA:    <font color=\"".$this->backcolorf."\">".$fecha."</font></td>";
               $this->salida.="</tr>";
               //***********

			$this->salida.="</table>";
	}

	function frmConsulta()
	{
		$datos=$this->BuscarAccionPreventiva2();
		if($datos===false)
		{
			return false;
		}
		if (sizeof($datos)!=0)
		{
			$this->salida.="<br>";
			$this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class='hc_table_submodulo_list_title'>";
			$this->salida.="<td colspan=\"3\">";
			$this->salida.="CONSOLIDADO ACCIÓN PREVENTIVA";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$ciclo=sizeof($datos);
			$j=0;
			for($i=0;$i<$ciclo;$i++)
			{
				if($j==0)
				{
					$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
					$j=1;
				}
				else if($j==1)
				{
					$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
					$j=0;
				}
				$this->salida.="<td width=\"35%\" align=\"justify\">";
				$this->salida.="<label class=\"label\">".$datos[$i]['nombre']."</label>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"10%\" align=\"center\">";
				if($datos[$i]['nombre']<>'OBSERVACIONES')
				{
					if($datos[$i]['sw_accion_preventiva']==1)
					{
						$this->salida.="SI";
					}
					else
					{
						$this->salida.="NO";
					}
				}
				$this->salida.="</td>";
				$this->salida.="<td width=\"55%\" align=\"justify\">";
				$this->salida.="".$datos[$i]['descripcion']."";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}
               
               //*********** VALIDO PARA FECHA REGISTRO
               list($fecha,$hora) = explode(" ",$this->PartirFecha($datos[0]['fecha_registro']));
     
               $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
               $this->salida.="<td width=\"100%\" align=\"center\" colspan=\"3\" class=\"label\">FECHA DE REGISTRO DE LA ACCION PREVENTIVA:    <font color=\"".$this->backcolorf."\">".$fecha."</font></td>";
               $this->salida.="</tr>";
               //***********

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
		$datos=$this->BuscarAccionPreventiva2();
		if($datos===false)
		{
			return false;
		}
		if (sizeof($datos)!=0)
		{
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class='hc_table_submodulo_list_title'>";
			$salida.="<td colspan=\"3\" align=\"center\">";
			$salida.="ACCIÓN PREVENTIVA";
			$salida.="</td>";
			$salida.="</tr>";
			$ciclo=sizeof($datos);
			$j=0;
			for($i=0;$i<$ciclo;$i++)
			{
				if($j==0)
				{
					$salida.="<tr class=\"hc_submodulo_list_claro\">";
					$j=1;
				}
				else if($j==1)
				{
					$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
					$j=0;
				}
				$salida.="<td width=\"35%\" align=\"justify\">";
				$salida.="<label class=\"label\">".$datos[$i]['nombre']."</label>";
				$salida.="</td>";
				$salida.="<td width=\"10%\" align=\"center\">";
				if($datos[$i]['nombre']<>'OBSERVACIONES')
				{
					if($datos[$i]['sw_accion_preventiva']==1)
					{
						$salida.="SI";
					}
					else
					{
						$salida.="NO";
					}
				}
				$salida.="</td>";
				$salida.="<td width=\"55%\" align=\"justify\">";
				$salida.="".$datos[$i]['descripcion']."";
				$salida.="</td>";
				$salida.="</tr>";
			}
               
               //*********** VALIDO PARA FECHA REGISTRO
               list($fecha,$hora) = explode(" ",$this->PartirFecha($datos[0]['fecha_registro']));
     
               $salida.="<tr class=\"hc_submodulo_list_oscuro\">";
               $salida.="<td width=\"100%\" align=\"center\" colspan=\"3\" class=\"label\">FECHA DE REGISTRO DE LA ACCION PREVENTIVA:    <font color=\"".$this->backcolorf."\">".$fecha."</font></td>";
               $salida.="</tr>";
               //***********
               
			$salida.="</table>";
			$salida.="<br>";
		}
		else
		{
			return false;
		}
		return $salida;
	}
     
     function PartirFecha($fecha)
     {
          $a=explode('-',$fecha);
          $b=explode(' ',$a[2]);
          $c=explode(':',$b[1]);
          $d=explode('.',$c[2]);
          return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
     }

}
?>
