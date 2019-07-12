<?php

/**
* Submodulo de Revisión por Sistemas (HTML).
*
* Submodulo para manejar el examen por sistemas que debe realizarse a un paciente en una evolución.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_RevisionxSistemas_HTML.php,v 1.8 2006/12/19 21:00:15 jgomez Exp $
*/

/**
* RevisionxSistemas_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo revisión por sistemas, se extiende la clase RevisionxSistemas y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class RevisionxSistemas_HTML extends RevisionxSistemas
{

	function RevisionxSistemas_HTML()
	{
	    $this->RevisionxSistemas();//constructor del padre
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
////////////////////////////
  
  
  //cor - clzc -ads
  function SetStyle($campo)
  {
		if ($this->frmError[$campo] || $campo=="MensajeError"){
			if ($campo=="MensajeError"){
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
  }

	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		$datos=$this->DatosRevisionSistemas();
		if(empty($this->titulo))
		{
			$this->salida.= ThemeAbrirTablaSubModulo('REVISION POR SISTEMA');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"2\">REVISION POR SISTEMA DEL PACIENTE</td>";
		$this->salida.="</tr>";

		for($i=0; $i<sizeof($datos[0]);)
		{
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               //es impar
               if($i % 2)
               {
                    $this->TablaBasica($i,$datos);
               }
               else
               {
                    $this->TablaBasica($i,$datos);
               }

               $i++;
               if(!empty($datos[0][$i]))
               {
                    $this->TablaBasica($i,$datos);
               }
               $i++;
               if($i>=sizeof($datos[0]))
               {
                         $this->salida.="<td></td>";
               }
               $this->salida.="</tr>";
		}
		$this->salida.="</table>";
		$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr><td align=\"center\">";
		$this->salida.="<input type=\"submit\" value=\"Insertar\" class=\"input-submit\">";
		$this->salida.="</td></tr></table>";
		$this->salida.="</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


	function frmConsulta()
	{
		$dato=$this->DatosConsultaRevision();
		if(sizeof($dato[0])!=0)
		{
			$this->salida.="<br>";
			$this->salida.= "<table border=\"1\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"2\">REVISION POR SISTEMA DEL PACIENTE</td>";
			$this->salida.="</tr>";
			$this->salida.= "<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.= "<td align=\"center\">";
			$this->salida.= "<label>Sistema</label>";
			$this->salida.= "</td>";
			$this->salida.= "<td width=\"100%\">";
			$this->salida.= "<label>Observacion</label>";
			$this->salida.= "</td>";
			$this->salida.= "</tr>";
			$i=0;
			$s=0;
			while($i<sizeof($dato[0]))
			{
				if($s==0)
				{
				$this->salida.= "<tr  class=\"hc_submodulo_list_oscuro\">";
				$this->salida.= "<td align=\"center\" nowrap>";
				$this->salida.= $dato[0][$i];
				$this->salida.= "</td>";
				$this->salida.= "<td>";
				$this->salida.= $dato[1][$i];
				$this->salida.= "</td>";
				$this->salida.= "</tr>";
				$s=1;
				}
				else
				{
				$this->salida.= "<tr  class=\"hc_submodulo_list_claro\">";
				$this->salida.= "<td align=\"center\" nowrap>";
				$this->salida.= $dato[0][$i];
				$this->salida.= "</td>";
				$this->salida.= "<td>";
				$this->salida.= $dato[1][$i];
				$this->salida.= "</td>";
				$this->salida.= "</tr>";
				$s=0;
				}
				$i++;
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


	function frmHistoria()
	{
		$dato=$this->DatosConsultaRevision();
		if(sizeof($dato[0])!=0)
		{
			$salida.="<br>";
			$salida.= "<table border=\"1\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="  <td align=\"center\" colspan=\"2\">REVISION POR SISTEMA DEL PACIENTE</td>";
			$salida.="</tr>";
			$salida.= "<tr class=\"hc_table_submodulo_list_title\">";
			$salida.= "<td align=\"center\">";
			$salida.= "<label>Sistema</label>";
			$salida.= "</td>";
			$salida.= "<td width=\"100%\">";
			$salida.= "<label>Observacion</label>";
			$salida.= "</td>";
			$salida.= "</tr>";
			$i=0;
			$s=0;
			while($i<sizeof($dato[0]))
			{
				if($s==0)
				{
				$salida.= "<tr  class=\"hc_submodulo_list_oscuro\">";
				$salida.= "<td align=\"center\" nowrap>";
				$salida.= $dato[0][$i];
				$salida.= "</td>";
				$salida.= "<td>";
				$salida.= $dato[1][$i];
				$salida.= "</td>";
				$salida.= "</tr>";
				$s=1;
				}
				else
				{
				$salida.= "<tr  class=\"hc_submodulo_list_claro\">";
				$salida.= "<td align=\"center\" nowrap>";
				$salida.= $dato[0][$i];
				$salida.= "</td>";
				$salida.= "<td>";
				$salida.= $dato[1][$i];
				$salida.= "</td>";
				$salida.= "</tr>";
				$s=0;
				}
				$i++;
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

	function TablaBasica($i,$datos)
	{
          $pfj=$this->frmPrefijo;	
          $this->salida.="<td width=\"50%\" nowrap>";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
          if($datos[4][$i] != 0)
          {
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"30%\" align=\"left\">".$datos[1][$i]."</td>";
               $this->salida.="<td width=\"70%\" align=\"center\" nowrap>";
               $this->salida.="<textarea style = \"width:100%\" name=\"observ".$datos[0][$i].$pfj."\" rows=\"2\" class=\"textarea\">".$datos[2][$i]."</textarea>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
          }
          $this->salida.="</table>";
          $this->salida.="</td>";
          return true;
	}

}
?>
