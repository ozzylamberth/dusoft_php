<?php

/**
* Submodulo de HallazgosQuirurgicos (HTML).
*
* Submodulo para manejar los Hallazgos de la Cirugia.
* @author Tizziano Perea <tizzianop@gmail.com>.
* @version 1.0
* @package SIIS
* $Id: hc_HallazgosQuirurgicos_HTML.php,v 1.2 2006/12/19 21:00:13 jgomez Exp $
*/

/**
* HallazgosQuirurgicos_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo HallazgosQuirurgicos, se extiende la clase HallazgosQuirurgicos y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class HallazgosQuirurgicos_HTML extends HallazgosQuirurgicos
{

	function HallazgosQuirurgicos_HTML()
	{
	    $this->HallazgosQuirurgicos();//constructor del padre
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

  
/*IMPLEMENTACION DE LA BARRA DE NAVEGACION*/

	//cor - jea - ads
	function CalcularNumeroPasos($conteo)
	{
		$pfj=$this->frmPrefijo;
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	//cor - jea - ads
	function CalcularBarra($paso)
	{
		$pfj=$this->frmPrefijo;
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	//cor - jea - ads
	function CalcularOffset($paso)
	{
		$pfj=$this->frmPrefijo;
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	//cor - jea - ads
	function RetornarBarra_Paginadora()//Barra paginadora
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListadoNotasE','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj]));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}
//FIN DE IMPLEMENTACION


	function frmReporte()
	{
		$pfj=$this->frmPrefijo;
		$datos=$this->HallazgosQuirurgicos_Reporte();

		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarSignosVitales'));
		$this->salida.= "<form name=\"Evolucion$pfj\" action=\"$accionI\" method=\"post\">";

		if($datos===false)
		{
			return false;
		}
		if(!empty($datos))
		{
			$this->salida .="<br><table width=\"90%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td align=\"center\">HALLAZGOS DE LA CIRUGIA</td>";
			$this->salida .="</tr>";

			$spy=0;
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


				$this->salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector){

					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>$vector[hora]</b></td>";
					$this->salida .="<td><b>";
					$this->salida .=$vector[usuario].' - '.$vector[nombre];
					$this->salida .="</b></td>";
					$this->salida .="</tr>";
					$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
					$this->salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$this->salida .="<td width='100%'>$vector[descripcion]</td>";
					$this->salida .="</tr>";
					$this->salida .="<tr>";

				}
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}

			$this->salida.="</table>";
			//Mostrar Barra de Navegacion
               $datos =$this->RetornarBarra_Paginadora();
               if($datos)
               {
                    $this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
                    $this->salida .= "  <tr>";
                    $this->salida .= "  <td width=\"100%\" align=\"center\">";
                    $this->salida .=$datos;
                    $this->salida .= "  </td>";
                    $this->salida .= "  </tr>";
                    $this->salida .= "  </table>";
               }
               $this->salida .= "</form>";
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

	
	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$evoluciones=$this->HallazgosQuirurgicos_Consulta();
		if (empty ($evoluciones))
		{
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA REPORTES DE HALLAZGOS QUIRURGICOS<br><br>";
			$this->salida .="</tr>";
			$this->salida .="</table>";
		}
		if (!empty ($evoluciones))
  		{
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td align=\"center\"colspan=\"2\">HALLAZGOS DE LA CIRUGIA</td>";
			$this->salida .="</tr>";

			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td align=\"center\">HALLAZGOS</td>";
			$this->salida .="</tr>";

			$spy=0;
			foreach($evoluciones as $k=>$v)
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


				$this->salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector){

					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>$vector[hora]</b></td>";
					$this->salida .="<td><b>";
					$this->salida .=$vector[usuario].' - '.$vector[nombre];
					$this->salida .="</b></td>";

					$this->salida .="</tr>";
					$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
					$this->salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$this->salida .="<td width='100%'>$vector[descripcion]</td>";
					$this->salida .="</tr>";
					$this->salida .="<tr>";

				}
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}

			$this->salida.="</table><br>";
		}
          return true;
	}


	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$evoluciones=$this->HallazgosQuirurgicos_Consulta();
		if($evoluciones===false)
		{
			return false;
		}
		if(!empty($evoluciones))
		{
			$salida .="<table width=\"100%\" border=\"1\" align=\"center\">";
               $salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida .="<td align=\"center\"colspan=\"2\">HALLAZGOS DE LA CIRUGIA</td>";
			$salida .="</tr>";
			$salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida .="<td align=\"center\">FECHA</td>";
			$salida .="<td align=\"center\">HALLAZGOS</td>";
			$salida .="</tr>";

			foreach($evoluciones as $k=>$v)
			{
				$salida .="<tr class=\"hc_submodulo_list_claro\">";
				$salida .="<td width='10%' align='center'>$k</td>";
				$salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector)
				{
					$salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$salida .="<td><b>$vector[hora]</b></td>";
					$salida .="<td><b>";
					$salida .=$vector[usuario].' - '.$vector[nombre];
					$salida .="</b></td>";
					$salida .="</tr>";
					$salida .="<tr class=\"hc_submodulo_list_claro\">";
					$salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$salida .="<td width='100%'>$vector[descripcion]</td>";
					$salida .="</tr>";
					$salida .="<tr>";
				}
				$salida .="</table>";
				$salida .="</td>";
				$salida .="</tr>";
			}
			$salida.="</table><br>";
			return $salida;
		}
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
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('HALLAZGOS DE LA CIRUGIA');
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
		$this->salida.="<tr class='modulo_table_title'>";
		$this->salida.="<td align='center'>HALLAZGOS";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align='center' class='hc_submodulo_list_claro'>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
		$this->salida.="<br><textarea name=\"evol".$pfj."\" cols=\"80\" rows=\"7\" style = \"width:90%\" class=\"textarea\"></textarea>";
		$this->salida.="<p align=\"center\">";
		$this->salida.="<input type=\"submit\" value=\"Insertar\" class=\"input-submit\">";
		$this->salida.="</p><p></p>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</form>";
		$this->salida.="</table>";

		$this->frmReporte();
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

}

?>
