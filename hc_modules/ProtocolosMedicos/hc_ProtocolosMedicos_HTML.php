<?php

/**
* Submodulo de Protocolos Medicos (HTML).
*
* Submodulo para manejar los diferentes pasos que se debe seguir con un paciente según unas caracteristicas del
* paciente y demas datos.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_ProtocolosMedicos_HTML.php,v 1.2 2005/03/09 13:34:55 tizziano Exp $
*/

/**
* ProtocolosMedicos_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo protocolos medicos, se extiende la clase ProtocolosMedicos y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class ProtocolosMedicos_HTML extends ProtocolosMedicos
{

	function ProtocolosMedicos_HTML()
	{
	    $this->ProtocolosMedicos();//constructor del padre
      return true;
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
		//$fecha=$this->BusquedaFechaPaciente();
		$fecha = $this->datosPaciente[fecha_nacimiento];
		$edad_paciente = CalcularEdad($fecha,date("Y-m-d"));

		$tipo_pro=$this->BusquedaProtocoloMedico($edad_paciente);

		$tipo_det=$this->BusquedaDetalleMedico($edad_paciente);
		$tipo_apo=$this->BusquedaApoyoMedico($edad_paciente);
	  $this->salida  = ThemeAbrirTablaSubModulo('PROTOCOLOS MEDICOS1');

		if($this->saber<>0)
		{
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
			$this->salida.='<form name="prueba'.$pfj.'" action="'.$accion.'" method="post">';

      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		  $this->salida.="<tr>";
		  $this->salida.="<td>";
		  //$i=0;
		  //$s=0;
		  //$d=0;
		  //while($i<sizeof($tipo_pro[0]))
			for($i=0; $i<sizeof($tipo_pro[0]) ; $i++)
		  {
		    $salida="<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"hc_table_submodulo_list\" border=\"0\">";
        $salida.="<tr  class=\"hc_table_submodulo_list_title\">";
        $salida.="<td>";
			  if ($tipo_pro[2][$i]=="0")
			  {
	 	      $salida.="Pregunte: ".$tipo_pro[1][$i];
			  }
			  if ($tipo_pro[2][$i]=="1")
			  {
	 	      $salida.="Determine: ".$tipo_pro[1][$i];
			  }
			  if ($tipo_pro[2][$i]=="2")
			  {

					$salida.="Verifique: ".$tipo_pro[1][$i];
			  }
			  if ($tipo_pro[2][$i]=="3")
			  {
	 	      $salida.="Pregunte, Determine y Verifique: ".$tipo_pro[1][$i];
			  }
        $salida.="</td>";
	      $salida.="<td>Si</td>";
	      $salida.="<td>No</td>";
	      $salida.="<td>Clasificar</td>";
	      $salida.="<td>Tratar</td>";
			  $spy=0;
				$mirar=0;
				//$s=0;
				//while($s<sizeof($tipo_det[2]))
				//$P =sizeof($tipo_det[2]);
				//echo $P;
				//print_r(strtoupper($this->datosPaciente[sexo_id]));
			  for($s=0; $s<sizeof($tipo_det[2]); $s++)
			  {
					if($tipo_pro[0][$i]==$tipo_det[2][$s])
					{
						if($mirar==0)
						{
							$this->salida.=$salida;
							$mirar=1;
						}
						$this->salida.="</tr>";
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
						$this->salida.="<td>".$tipo_det[0][$s]."</td>";
						$this->salida.="<td><input type=\"radio\" name=\"prot".$tipo_det[3][$s].$pfj."\" value=\"1\" class=\"input-text\"></td>";
						$this->salida.="<td><input type=\"radio\" name=\"prot".$tipo_det[3][$s].$pfj."\" value=\"0\" checked=\"true\" class=\"input-text\"></td>";
						$this->salida.="<td align=\"center\"><input type=\"text\" name=\"clasificar".$tipo_det[3][$s].$pfj."\" size=\"25\" class=\"input-text\"></td>";
						$this->salida.="<td align=\"center\"><input type=\"text\" name=\"tratar".$tipo_det[3][$s].$pfj."\" class=\"input-text\"></td>";
						$this->salida.="</tr>";
						//$d=0;
						//while($d<sizeof($tipo_apo[4]))
						for($d=0; $d<sizeof($tipo_apo[4]); $d++)
						{
							if($tipo_det[3][$s]==$tipo_apo[4][$d])
							{
								$this->salida.="<tr>";
								$this->salida.="<td colspan=\"5\"><input type=\"checkbox\" name=\"exam".$tipo_apo[4][$d].$pfj."\" value=\"".$tipo_apo[0][$d].",".$tipo_apo[1][$d].",".$tipo_apo[2][$d].",".$tipo_apo[3][$d]."\" class=\"input-text\">".$tipo_apo[1][$d]."</td>";
								$this->salida.="</tr>";
							}
							//$d++;
						}
					}
			  //$s++;
		    }
				if($mirar!=0)
				{
					$this->salida.="<tr>";
					$this->salida.="<td><br></td>";
					$this->salida.="</tr>";
					$this->salida.="<tr>";
					$this->salida.="<td class=\"hc_table_submodulo_list_title\" colspan=\"5\">Observaciones</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr>";
					$this->salida.="<td colspan=\"5\" align=\"center\"><textarea name=\"observaciones".$tipo_pro[0][$i].$pfj."\" cols=\"50\" rows=\"5\"class=\"textarea\"></textarea></td>";
					$this->salida.="</tr>";

					$this->salida.="<tr>";
					$this->salida.="<td><br></td>";
					$this->salida.="</tr>";

					$this->salida.="<tr>";
					$this->salida.="<td class=\"hc_table_submodulo_list_title\" colspan=\"5\">Recomendaciones</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr>";
					$this->salida.="<td colspan=\"5\" align=\"center\"><textarea name=\"recomendaciones".$tipo_pro[0][$i].$pfj."\" cols=\"50\" rows=\"5\"class=\"textarea\"></textarea></td>";
					$this->salida.="</tr>";

					$this->salida.="</table>";
				}
				$this->salida.="<br>";
		  //$i++;
		  }
		  $this->salida.="</td>";
		  $this->salida.="</tr>";


			$this->salida.="<table width=\"0\" align=\"center\">";
		  $this->salida.="<tr>";
		  $this->salida.="<td><input type=\"submit\" value=\"Insertar\" class=\"input-submit\"></td>";
		  $this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</form>";
  }
	else
	{
	  $this->salida.="NO EXISTEN PROTOCOLOS MEDICAOS PARA REALIZARLE A ESTE PACIENTE";
	}

	$this->salida .= ThemeCerrarTablaSubModulo();
  return true;
	}

	function frmConsulta()
	{
		$hc_pro=$this->ConsultaProtocolosMedicos();
		if(sizeof($hc_pro[0])<>0)
		{
			$this->salida.="<br>";
	    $this->salida.='<table border="1" class="hc_table_list" align="center" width="80%">';
		  $this->salida.="<tr>";
		  $this->salida.="<td>";
		  $i=0;
		  while($i<sizeof($hc_pro[0]))
	    {
		    $b=$hc_pro[4][$i];
		    $this->salida.='<table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">';
        $this->salida.='<tr align="center" class="hc_table_submodulo_list_title">';
        $this->salida.='<td nowrap="false" width="40%" align="left">';
	 	    $this->salida.='<label>'.$hc_pro[0][$i].'</label>';
        $this->salida.='</td>';
	      $this->salida.='<td>';
	 	    $this->salida.='Clasificación';
	      $this->salida.='</td>';
	      $this->salida.='<td>';
	 	    $this->salida.='Tratamiento';
	      $this->salida.='</td>';
        $this->salida.='</tr>';
			  while($b==$hc_pro[4][$i])
		    {
          $this->salida.='<tr>';
          $this->salida.='<td nowrap="false" width="40%">';
   	      $this->salida.=$hc_pro[1][$i];
          $this->salida.='</td>';
          $this->salida.='<td>';
          $this->salida.=$hc_pro[2][$i];
          $this->salida.='</td>';
          $this->salida.='<td>';
          $this->salida.=$hc_pro[3][$i];
          $this->salida.='</td>';
          $this->salida.='</tr>';
				  $i++;
			  }
        $this->salida.='</table>';
		  }
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

}

?>
