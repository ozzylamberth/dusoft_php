<?php

/**
* Submodulo de Finalidad (HTML).
*
* Submodulo para manejar la finalidad de la atención prestada a un paciente en una evolución (rips)
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_Finalidad_HTML.php,v 1.5 2006/12/19 21:00:13 jgomez Exp $
*/

/**
* Finalidad_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo finalidad, se extiende la clase Finalidad y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Finalidad_HTML extends Finalidad
{

	function Finalidad_HTML()
	{
	    $this->Finalidad();//constructor del padre
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
  //////////////////////////
     function frmConsulta()
	{
		$result=$this->FinalidadConsulta();
		if($result==false)
		{
			return false;
		}
		if(!$result->EOF)
		{
			$this->salida.="<br>";
			$this->salida.="<table width=\"100%\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>";
               $this->salida .= "<label class=\"label\">FINALIDAD DE LA ATENCION</label>";
			$this->salida .="</td>";
			$this->salida .="</tr>";
			while (!$result->EOF)
			{
				if($spy==0)
				{
					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
				}
				else
				{
					$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
				}
				$this->salida .="<td align=\"center\">";
				$this->salida .=$result->fields[0];
				$this->salida .="</td>";
				$this->salida .="</tr>";
				$result->MoveNext();
			}
			$this->salida .="</table>";
			$this->salida.="<br>";
			$result->close();
		}
		else
		{
		  return false;
		}
          return true;
	}



 	function frmHistoria()
	{
		$result=$this->FinalidadConsulta();
		if($result==false)
		{
			return false;
		}
		if(!$result->EOF)
		{
			$salida.="<br>";
			$salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td>";
			$salida.= "<label class=\"label\">FINALIDAD DE LA ATENCION</label>";
			$salida.="</td>";
			$salida.="</tr>";
			while (!$result->EOF)
			{
				if($spy==0)
				{
					$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
				}
				else
				{
					$salida.="<tr class=\"hc_submodulo_list_claro\">";
				}
				$salida.="<td align=\"center\">";
				$salida.=$result->fields[0];
				$salida.="</td>";
				$salida.="</tr>";
				$result->MoveNext();
			}
			$salida .="</table>";
			$salida.="<br>";
			$result->close();
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
		$finali=$this->ConsultaFinalidad();
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('FINALIDAD DE LA ATENCION');
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
		$this->salida.= "<table width=\"100%\" border=\"0\">";
		$i=0;
		while($i<sizeof($finali[0]))
		{
			$this->salida.= "<tr>";
			$this->salida.= "<td>";
 			$this->salida.= "<table width=\"100%\" border=\"0\" class=\"modulo_table_list\">";
			$this->salida.= "<tr>";
			$this->salida.= "<td width=\"50%\">";
               
			if($finali[2][$i]!="")
			{
				$senalar=$finali[4][$i];
				$this->salida.="<input type=\"radio\" value=\"".$finali[1][$i]."\"  checked=\"true\" name=\"finalidad".$pfj."\"><label class=\"label\">".$finali[0][$i]."</label>";
			}
			else
			{
                    /*if($finali[1][$i]==$this->tipoFinalidad or !empty($finali[3][$i]))
				{
					$this->salida.="<input type=\"radio\" value=\"".$finali[2][$i]."\" name=\"finalidad".$pfj."\"";
					if($finali[1][$i]==$this->tipoFinalidad)
                         {
						$this->salida.="checked";
					}
					$this->salida.="><label>".$finali[0][$i]."</label>";
                    
				}
				else
				{*/
			  		$this->salida.="<input type=\"radio\" value=\"".$finali[1][$i]."\" name=\"finalidad".$pfj."\"><label class=\"label\">".$finali[0][$i]."</label>";
                         
				//}
			}
			$this->salida.= "</td>";
			$datos=$this->ConsultaFinalidadDetalle($finali[1][$i]);
			if($datos!=false)
			{
				$this->salida.= "<td>";
				$this->salida.= "<table width=\"100%\" height=\"100%\" border=\"0\" class=\"normal_10n\">";
				$salida.= "<tr class=\"modulo_list_oscuro\">";
				$salida1.= "<tr class=\"modulo_list_claro\">";
				foreach($datos as $k=>$v)
				{
					$salida.= "<td align=\"center\">";
					$salida.= $v['descripcion'];
					$salida.= "</td>";
					$salida1.= "<td align=\"center\">";
					//if($finali[1][$i] != $v[tipo_finalidad_detalle])
					if($v[evolucion_id] != $this->evolucion)
					{
						$salida1.= "<input type=\"checkbox\" name=\"otros".$finali[1][$i].$k.$pfj."\" value=\"".$finali[1][$i].','.$k."\">";
					}
					else
					{
						$salida1.= "<input type=\"checkbox\" name=\"otros".$finali[1][$i].$k.$pfj."\" value=\"".$finali[1][$i].','.$k."\" checked>";
					}
					$salida1.= "</td>";
				}
				$salida.= "</tr>";
				$salida1.= "</tr>";
				$this->salida.=$salida;
				$this->salida.=$salida1;
				$this->salida.= "</table>";
				$this->salida.= "</td>";
				unset($salida);
				unset($salida1);
			}
			$this->salida.= "</tr>";
			$this->salida.= "</table>";
			$this->salida.= "</td>";
			$this->salida.= "</tr>";
			$i++;
		}
		$this->salida.= "<tr>";
		$this->salida.= "<td>";
		$this->salida.= "<input type=\"checkbox\" value=\"1\" name=\"no_es_pyp\"";
		if($senalar==1)
		{
			$this->salida.= "checked";
		}
		$this->salida.= "> <label class=\"label\">Señalar si la atención no es de PROMOCION Y PREVENCION (recobro de la consulta externa).</label>";
		$this->salida.= "</td>";
		$this->salida.= "</tr>";
		$this->salida.= "</table>";
		$this->salida.="<table width=\"0\" align=\"left\"><tr><td>";
		$this->salida.="<input type=\"submit\" value=\"Insertar\" class=\"input-submit\">";
		$this->salida.="</td></tr></table>";
		$this->salida.="</form>";
		$this->salida.= ThemeCerrarTablaSubModulo();
		return true;
	}

}

?>
