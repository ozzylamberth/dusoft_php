<?php
/**
* Submodulo para el Cumplimiento de Procedimientos Realizados.
*
* Submodulo para manejar y detallar los procedimientos realizados por
* cada uno de los tipos de profesionales.
* @author Tizziano Perea O. <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Cumplimiento_ProcedimientosRealizados_HTML.php,v 1.15 2006/12/19 21:00:13 jgomez Exp $
*/

class Cumplimiento_ProcedimientosRealizados_HTML extends Cumplimiento_ProcedimientosRealizados
{
  //cor - clzc - ads
	function Cumplimiento_ProcedimientosRealizados_HTML()
	{
		$this->Cumplimiento_ProcedimientosRealizados();//constructor del padre
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
    'fecha'=>'',
    'autor'=>'TIZZIANO PEREA OCORO',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }

///////////////////////////////////
   
	//cor - clzc -ads
	function SetStyle($campo)
	{
		if ($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

	//cor - jea - ads
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	//cor - jea - ads
	function CalcularBarra($paso)
	{
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
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	//cor - jea - ads
	function RetornarBarraCargos_Avanzada()//Barra paginadora de los planes clientes
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
		
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Cargos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
		'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj],
		'datos'.$pfj=>$_REQUEST['datos'.$pfj], 'evolucion'.$pfj=>$_REQUEST['evolucion'.$pfj]));

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


	function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora de los planes clientes
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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'datos'.$pfj=>$_REQUEST['datos'.$pfj], 'evolucion'.$pfj=>$_REQUEST['evolucion'.$pfj]));

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
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListadoProcedimientos','conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj]));
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

//TT
	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('PROCEDIMIENTOS REALIZADOS');
		//lo que inserte
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Cargos',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj], 'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'cargo'.$pfj=>$_REQUEST['cargo'.$pfj], 'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));

		$this->salida.="<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">ADICION DE PROCEDIMIENTOS - BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td colspan=\"1\" width=\"10%\">TIPO:</td>";

		$this->salida.="<td colspan=\"4\" width=\"65%\" align = left >";
		$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
		$this->salida.="<option value = '-1' selected>Todos</option>";
		if (($_REQUEST['criterio1'.$pfj])  == '-2')
		{
			$this->salida.="<option value = '-2' selected>Frecuentes</option>";
		}
		else
		{
			$this->salida.="<option value = '-2' >Frecuentes</option>";
		}

		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"10%\">CARGO:</td>";
		$this->salida .="<td width=\"15%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargo$pfj'  value =\"".$_REQUEST['cargo'.$pfj]."\"    ></td>" ;

		$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
		$this->salida .="<td width=\"34%\" align='center'><input type='text' size =34 class='input-text' 	name = 'descripcion$pfj'   value =\"".$_REQUEST['descripcion'.$pfj]."\"        ></td>" ;

		$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</form>";
		$this->salida.="</table>";
		//hasta aqui lo que inserte
		$this->frmReporte();
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


	function frmReporte()
	{
		$pfj=$this->frmPrefijo;
		$datos = $this->Consulta_Procedimiento_Realizado();
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarSignosVitales'));
		$this->salida.= "<form name=\"Procedimientos$pfj\" action=\"$accionI\" method=\"post\">";
		if($datos===false)
		{
			return false;
		}
		if(!empty ($datos))
		{
			$this->salida.="<table align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td width=\"10%\" align=\"center\">EVOLUCION</td>";
			$this->salida.="<td width=\"70%\"align=\"center\">PROCEDIMIENTOS REALIZADOS</td>";
			$this->salida.="<td width=\"10%\"align=\"center\">DETALLES</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($datos);$i++)
			{
				if ($datos[$i][evolucion_id] != $datos[$i-1][evolucion_id])
				{
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td  align=\"center\" width=\"7%\">".$datos[$i][evolucion_id]."</td>";
					$this->salida.="<td width=\"70%\"><table align=\"center\" border=\"1\" width=\"100%\">";

					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="  <td width=\"9%\">CARGO</td>";
					$this->salida.="  <td width=\"60%\">PROCEDIMIENTO</td>";
					$this->salida.="  <td width=\"5%\">CANTIDAD</td>";
					$this->salida.="</tr>";
					for($x=0; $x<sizeof($datos); $x++)
					{
						if( $x % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						if ($datos[$x][procedimiento_nota_id] == $datos[$i][procedimiento_nota_id] AND $datos[$x][evolucion_id] == $datos[$i][evolucion_id])
						{
							$evolucion = $datos[0][evolucion_id];
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="<td align=\"center\" width=\"9%\">".$datos[$x][cargo]."</td>";
							$this->salida.="<td align=\"left\" width=\"60%\">".$datos[$x][descripcion]."</td>";
							$this->salida.="<td align=\"center\" width=\"5%\">".$datos[$x][cantidad]."</td>";
							$this->salida.="</tr>";
						}
					}
					$this->salida.="</table></td>";
					$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'resumen',
					'info_evolucion'.$pfj => $datos[$i][evolucion_id],
					'info_ingreso'.$pfj => $datos[$i][ingreso],
					'info_usuario'.$pfj => $datos[$i][usuario_id]));
					$this->salida.="<td align=\"center\" width=\"10%\"><a href='$accion2'><img title=\"Resumen\" src=\"".GetThemePath()."/images/resumen.gif\" border='0'></a></td>";
					$this->salida.="</tr>";
				}
			}
			$this->salida.="</table>";
			$this->salida.="<table align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td colspan=\"2\" align=\"center\" width=\"100%\">OPCIONES VALIDAS PARA EVOLUCION ACTUAL</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			if($evolucion == $this->evolucion)
			{
				$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'observacion', 'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion));
				$this->salida.="  <td align=\"center\" width=\"50%\"><a href='$accion1'><img title=\"Editar\" src=\"".GetThemePath()."/images/edita.png\" border='0'></a></td>";
				$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar'));
				$this->salida.="  <td align=\"center\" width=\"50%\"><a href='$accion2'><img title=\"Eliminar por evolucion\" src=\"".GetThemePath()."/images/delete2.gif\"  border='0'></a></td>";
			}
			else
			{
				$this->salida.="<td colspan=\"2\" align=\"center\" width=\"100%\">&nbsp;</td>";
			}
			$this->salida.="</tr>";
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
			$this->salida.="<tr align=\"center\"><br><td><label class='label_mark'>NO HAY PROCEDIMIENTOS PARA ESTE PACIENTE</label>";
 			$this->salida.="</td></tr>";
 			$this->salida.="</table>";
 			return false;
 		}
	    return true;
	}



	function frmForma_Seleccion_Procedimientos($vectorA)
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('PROCEDIMIENTOS ENCONTRADOS');
		$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Cargos',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
		'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));

		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">ADICION DE PROCEDIMIENTOS - BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td colspan=\"1\" width=\"10%\">TIPO:</td>";

		$this->salida.="<td colspan=\"4\" width=\"65%\" align = left >";
		$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
		$this->salida.="<option value = '-1' selected>Todos</option>";
		if (($_REQUEST['criterio1'.$pfj])  == '-2')
		{
			$this->salida.="<option value = '-2' selected>Frecuentes</option>";
		}
		else
		{
			$this->salida.="<option value = '-2' >Frecuentes</option>";
		}

		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"10%\">CARGO:</td>";
		$this->salida .="<td width=\"15%\" align='center'><input type='text' size =10  maxlengh = 10 class='input-text' size = 10 maxlength = 10	name = 'cargo$pfj'  value =\"".$_REQUEST['cargo'.$pfj]."\"    ></td>" ;

		$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
		$this->salida .="<td width=\"34%\" align='center'><input type='text' size =35 class='input-text' 	name = 'descripcion$pfj'   value =\"".$_REQUEST['descripcion'.$pfj]."\"        ></td>" ;

		$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";


		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";

		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		if ($vectorA)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"10%\">CARGO</td>";
			$this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";

			for($i=0;$i<sizeof($vectorA);$i++)
			{
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorA[$i][cargo]."</td>";
				$this->salida.="  <td align=\"left\" width=\"50%\">".$vectorA[$i][descripcion]."</td>";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Llenar_Observacion_Procedimiento','cargo'.$pfj=>$vectorA[$i][cargo], 'descripcion'.$pfj=>$vectorA[$i][descripcion], 'cantidad'.$pfj=>$vectorA[$i][sw_cantidad]));
				$this->salida.="  <td align=\"center\" width=\"5%\"><a href='$accion'>ADICIONAR</a></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraCargos_Avanzada();
			if(!empty($var))
			{
				$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
				$this->salida .= "  <tr>";
				$this->salida .= "  <td width=\"100%\" align=\"center\">";
				$this->salida .=$var;
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table><br>";
			}
		}
		$this->salida .= "</form>";

		//BOTON DEVOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}



	function Llenar_Observacion_Procedimiento($cargo, $descripcion, $cantidad)
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('DESCRIPCION DE LOS PROCEDIMIENTOS A REALIZAR');

		/**************INCLUIR LIB PARA OBTENER EQUIVALENCIAS***************************/

		IncludeLib('funciones_facturacion');
		$equi='';
		if($this->plan_id){
		$equi=ValdiarEquivalencias($this->plan_id,$cargo);
		$_SESSION['VectorCargos'] = $equi;
          
		/*********************************************************************/

		/**********************INCLUIR MALLA PARA VALIDACION DE DATOS*******************/

		IncludeLib('malla_validadora');
		$malla=MallaValidadoraCargoCups($cargo,$this->plan_id,$this->servicio);

          /**********************INCLUIR MALLA PARA VALIDACION DE DATOS*******************/

		$accionII=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_observacion','cargo'.$pfj=>$cargo, 'descripcion'.$pfj=>$descripcion, 'malla_validadora'.$pfj =>$malla));
		$this->salida.="<form name=\"formades$pfj\" action=\"$accionII\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="</table>";

		if (!empty($malla['mensaje']))
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr>";
			$this->salida.="<td class=\"label_error\" align=\"center\">"."( ".$malla['mensaje']." )"."</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
		}

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"3\">OBSERVACION</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"12%\">CARGO</td>";
		$this->salida.="<td width=\"65%\">DESCRIPCION</td>";
		$this->salida.="<td width=\"3%\">CANTIDAD</td>";
		$this->salida.="</tr>";

		if($i % 2){ $estilo='modulo_list_claro';}
		else {$estilo='modulo_list_oscuro';}
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td align=\"center\" width=\"12%\">$cargo</td>";
		$this->salida.="<td align=\"left\" width=\"65%\">$descripcion</td>";
		if ($cantidad == 1)
		{
			$this->salida.="<td width=\"3%\" align=\"center\"><input type='text' readonly class='input-text' size = 4 maxlength = 3 name=\"cantidad$pfj\" value =\"".$cantidad."\" class=\"select\"></td>";
		}
		else
		{
			$this->salida.="<td width=\"3%\" align=\"center\"><select name=\"cantidad$pfj\" class=\"select\">";
			for($i=1;$i<=20;$i++)
			{
				$this->salida .= "<option value=\"$i\">$i</option>";
			}
			$this->salida .= "</select></td>";
		}
		$this->salida.="</tr>";

		/***********************INSERCION FUNCION DE EQUIVALENCIAS*******************/

		if(sizeof($equi) > 1)
		{
			$this->salida.="<tr>";
			$this->salida.="<td class=\"$estilo\" align=\"center\" colspan=\"3\">";
			//tiene varias equivalencias
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "<tr class=\"modulo_table_title\">";
			$this->salida .= "<td>TARIFARIO</td>";
			$this->salida .= "<td>CARGO</td>";
			$this->salida .= "<td>DESCRIPCION</td>";
			$this->salida .= "<td></td>";
			$this->salida .= "</tr>";

			for($i=0; $i<sizeof($equi); $i++)
			{
				if($i % 2){$estilo='hc_submodulo_list_oscuro';}
				else {$estilo='hc_submodulo_list_claro';}

				$this->salida .= "<tr class=\"$estilo\">";
				$this->salida .= "<td align=\"center\">".$equi[$i][tarifario_id]."</td>";
				$this->salida .= "<td align=\"center\">".$equi[$i][cargo]."</td>";
				$this->salida .= "<td>".$equi[$i][descripcion]."</td>";
				$this->salida .= "<td align=\"center\"><input type=\"checkbox\" name=\"check_cargo$i\" value=\"".$equi[$i][tarifario_id].",".$equi[$i][cargo].",".$equi[$i][descripcion]."\"></td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}

		/***********************INSERCION FUNCION DE EQUIVALENCIAS*******************/

		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="</table><br>";
		$this->salida.="</form>";
		//BOTON DEVOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "<tr><td colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= "</table><br>";
		$this->salida.="</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		}else{
			$this->frmError["MensajeError"]="NO EXISTE UN PLAN ACTIVO";
			$_REQUEST['cargo'.$pfj] = '';
			$_REQUEST['descripcion'.$pfj] = '';
			$this->frmForma();
		}
		return true;
	}


	function frmForma_Modificar_Observacion($evolucion, $datos)
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('PROCEDIMIENTOS REALIZADOS');
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'modificar',
		'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\" class=\"modulo_list_claro\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"3\">OBSERVACION</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"10%\">CARGO</td>";
		$this->salida.="<td width=\"65%\">DESCRIPCION</td>";
		$this->salida.="<td width=\"5%\">ELIMINAR</td>";
		$this->salida.="</tr>";
		for($m=0; $m<sizeof($datos); $m++)
		{
			$descripcion_medica = $datos[0][descripcion_medica];
			if ($datos[$m][evolucion_id] == $evolucion)
			{
				if( $x % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"10%\" align=\"center\" >".$datos[$m][cargo]."</td>";
                    
                    $ICaracteristicas = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarCaracteristicas',
		          'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion, 'cargo'.$pfj => $datos[$m][cargo].",".$datos[$m][descripcion]));

				$this->salida.="<td width=\"65%\"><a href=\"$ICaracteristicas\">".$datos[$m][descripcion]."</a></td>";
				
                    $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminacion_individual',
				'procedimiento_detalle_id'.$pfj => $datos[$m][procedimiento_detalle_id],
				'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion));
				$this->salida.="  <td align=\"center\" width=\"5%\"><a href='$accion2'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/delete2.gif\"  border='0'></a></td>";
				$this->salida.="</tr>";
			}
		}

     //Observacion
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td align=\"center\" width=\"15%\">OBSERVACION</td>";
		$this->salida.="<td colspan=\"2\" width=\"65%\" align='center' ><textarea class='textarea' name = 'obs$pfj' cols = 100 rows = 3>$descripcion_medica</textarea></td>";
		$this->salida.="</tr>";
          
     //Dx's
          $diag =$this->Diagnosticos_Solicitados('N');

          $DXPrincipal = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarDX',
          'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion, 'sw_dx_finalidad'.$pfj => 'N'));

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td align=\"center\" width=\"15%\"><a href=\"$DXPrincipal\">DIAGNOSTICOS</a></td>";
          $this->salida.="<td width=\"65%\" colspan=\"2\">";
          $this->salida.="<table width=\"100%\">";
          if(!empty($diag))
          {
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"10%\">PRIMARIO</td>";
               $this->salida.="<td width=\"10%\">TIPO DX</td>";
               $this->salida.="<td width=\"10%\">CODIGO</td>";
               $this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
               $this->salida.="  <td width=\"5%\"></td>";
               $this->salida.="</tr>";
          }

          for($j=0;$j<sizeof($diag);$j++)
          {
               $this->salida.="<tr class=\"$estilo\">";
               
               if($diag[$j]['sw_principal']==1)
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
               }
               else
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
               }
               
               if($diag[$j][tipo_diagnostico] == '1')
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
               }elseif($diag[$j][tipo_diagnostico] == '2')
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
               }else
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
               }
               $this->salida.="<td align=\"center\" width=\"10%\">".$diag[$j][diagnostico_id]."</td>";
               $this->salida.="<td align=\"justify\" width=\"60%\">".$diag[$j][diagnostico_nombre]."</td>";
               $accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico',
               'codigo'.$pfj=>$diag[$j][diagnostico_id],
               'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj], 'principal'.$pfj =>$diag[$j][sw_principal],
               'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion, 'sw_dx_finalidad'.$pfj => 'N'));
               $this->salida.="<td align=\"center\" width=\"6%\"><a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
               $this->salida.="</tr>";
          }
          if(!empty($diag))
          {
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"> (ID) - IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;(CN) - CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;(CR) - CONFIRMADO REPETIDO</td>";
               $this->salida.="</tr>";
          }
          
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

	//Dx's Complicacion
          $diag =$this->Diagnosticos_Solicitados('C');

          $DXComplicacion = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarDX',
          'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion, 'sw_dx_finalidad'.$pfj => 'C'));

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td align=\"center\" width=\"15%\"><a href=\"$DXComplicacion\">DIAGNOSTICOS COMPLICACION</a></td>";
          $this->salida.="<td width=\"65%\" colspan=\"2\">";
          $this->salida.="<table width=\"100%\">";
          if(!empty($diag))
          {
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"10%\">PRIMARIO</td>";
               $this->salida.="<td width=\"10%\">TIPO DX</td>";
               $this->salida.="<td width=\"10%\">CODIGO</td>";
               $this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
               $this->salida.="  <td width=\"5%\"></td>";
               $this->salida.="</tr>";
          }

          for($j=0;$j<sizeof($diag);$j++)
          {
               $this->salida.="<tr class=\"$estilo\">";
               
               if($diag[$j]['sw_principal']==1)
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
               }
               else
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
               }
               
               if($diag[$j][tipo_diagnostico] == '1')
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
               }elseif($diag[$j][tipo_diagnostico] == '2')
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
               }else
               {
                    $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
               }
               $this->salida.="<td align=\"center\" width=\"10%\">".$diag[$j][diagnostico_id]."</td>";
               $this->salida.="<td align=\"justify\" width=\"60%\">".$diag[$j][diagnostico_nombre]."</td>";
               $accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico',
               'codigo'.$pfj=>$diag[$j][diagnostico_id],
               'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj], 'principal'.$pfj =>$diag[$j][sw_principal],
               'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion, 'sw_dx_finalidad'.$pfj => 'C'));
               $this->salida.="<td align=\"center\" width=\"6%\"><a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
               $this->salida.="</tr>";
          }
          if(!empty($diag))
          {
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"> (ID) - IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;(CN) - CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;(CR) - CONFIRMADO REPETIDO</td>";
               $this->salida.="</tr>";
          }
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
     
		$this->salida.="<tr class=\"$estilo\">";
          $this->salida.= "<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar_modificacion$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida .= "</form>";
		//BOTON DEVOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
     
     function frmInsertarCaracteristicas($evolucion, $datos, $cargo)
     {
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('CARACTERISTICAS DEL PROCEDIMIENTO');
          
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'modificar_caracteristicas',
		'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion, 'cargo'.$pfj => $cargo));
		
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\" class=\"modulo_list_claro\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">OBSERVACION</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"10%\">CARGO</td>";
		$this->salida.="<td width=\"70%\">DESCRIPCION</td>";
		$this->salida.="</tr>";

          $Procedimiento = explode(",",$cargo);
          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td width=\"10%\" align=\"center\" >".$Procedimiento[0]."</td>";
          $this->salida.="<td width=\"70%\">".$Procedimiento[1]."</td>";
          $this->salida.="</tr>";
     
     //Tipo Sala
          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td width=\"10%\" align=\"center\"><b>TIPO SALA</b></td>";
          $this->salida.="<td width=\"70%\" align =\"left\">";
          $this->salida.="<select size =\"1\" name =\"tiposala$pfj\"  class =\"select\">";
          $this->salida.="<option value =\"-1\" selected>-SELECCIONE-</option>";
          $TipoSala = $this->TipoSalas_QX();
          if(empty($_REQUEST['tiposala'.$pfj])){
                    $_REQUEST['tiposala'.$pfj]=$v[tipo_sala_id];
          }
          for($i=0;$i<sizeof($TipoSala);$i++){
               if($_REQUEST['tiposala'.$pfj]  != $TipoSala[$i][tipo_sala_id]){
                    $this->salida.="<option value = \"".$TipoSala[$i][tipo_sala_id]."\">".$TipoSala[$i][descripcion]."</option>";
               }else{
                    $this->salida.="<option value = \"".$TipoSala[$i][tipo_sala_id]."\" selected >".$TipoSala[$i][descripcion]."</option>";
               }
          }
          $this->salida.="</select>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="</tr>";
          
          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.= "<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardar_caracteristicas$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
		$this->salida.="</tr>";
          $this->salida.="</table><br>";
          
          //BOTON DEVOLVER
          $this->salida.="<table align=\"center\">";
		
          $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'volver_DX',
		'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion));
		
          $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida.="</table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
     }
     
     function frmInsertarDX($evolucion, $datos, $vectorD)
     {
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('INSERTAR DIAGNOSTICOS');
          
          $sw_dx_finalidad = $_REQUEST['sw_dx_finalidad'.$pfj];
          
          $accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion,
          'sw_dx_finalidad'.$pfj => $sw_dx_finalidad));
		
          $this->salida.="<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";

		$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;

		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"        ></td>" ;

		$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="</form>";
          
          $accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_varios_diagnosticos',
		'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion, 'sw_dx_finalidad'.$pfj => $sw_dx_finalidad));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
          
          if ($vectorD)
          {
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"8%\">CODIGO</td>";
               $this->salida.="  <td width=\"60%\">DIAGNOSTICO</td>";
               $this->salida.="  <td width=\"17%\">TIPO DX</td>";
               $this->salida.="  <td width=\"5%\">OPCION</td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($vectorD);$i++)
               {
                    $codigo          = $vectorD[$i][diagnostico_id];
                    $diagnostico    = $vectorD[$i][diagnostico_nombre];

                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";

                    $this->salida.="  <td align=\"center\" width=\"8%\">$codigo</td>";
                    $this->salida.="  <td align=\"left\" width=\"60%\">$diagnostico</td>";
   				$this->salida.="<td align=\"center\" width=\"17%\">";
				$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
                    $this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".$pfj."[$i]' value = ".$codigo."></td>";
                    $this->salida.="</tr>";
               }
 			
               $this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
               
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardardiag$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";
               $var=$this->RetornarBarraDiagnosticos_Avanzada();
               if(!empty($var))
               {
                    $this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
                    $this->salida .= "  <tr>";
                    $this->salida .= "  <td width=\"100%\" align=\"center\">";
                    $this->salida .=$var;
                    $this->salida .= "  </td>";
                    $this->salida .= "  </tr>";
                    $this->salida .= "  </table><br>";
               }
          }
		
          $this->salida .= "</form>";
 		
          //BOTON DEVOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'volver_DX',
		'datos'.$pfj => $datos, 'evolucion'.$pfj => $evolucion));
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
         
          $this->salida .= ThemeCerrarTablaSubModulo();
		return true;
     }


	function frmForma_Resumen_Procedimientos($info_general, $evolucion, $ingreso)
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('RESUMEN DE PROCEDIMIENTOS REALIZADOS');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		if($info_general)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"2\">RESUMEN DE PROCEDIMIENTOS REALIZADOS</td>";
			$this->salida.="</tr>";
			for ($j=0; $j<sizeof($info_general); $j++)
			{
				if($info_general[$j][usuario_id] != $info_general[$j-1][usuario_id])
				{
					$Observacion = $info_general[$j][descripcion_medica];
					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="<td width=\"30%\">PROFESIONAL</td>";
					$this->salida.="<td align=\"left\" width=\"70%\">"."&nbsp; ".$info_general[$j][nombre]."&nbsp; - &nbsp;".$info_general[$j][usuario]."</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"center\" width=\"100%\" colspan=\"2\">".$info_general[$j][descripcion_usuario]."</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="<td width=\"100%\" colspan=\"2\">INFORMACION GENERAL DEL PROCEDIMIENTO</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"30%\">FECHA Y HORA DE REALIZACION</td>";
					$fecha= $this->FechaStamp($info_general[$j][fecha_registro]);
					$hora= $this->HoraStamp($info_general[$j][fecha_registro]);
					$this->salida.="<td align=\"left\" width=\"80%\">"."&nbsp; ".$fecha."&nbsp; - &nbsp;".$hora."</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr>";
					$this->salida.="<td width=\"100%\" colspan=\"2\"><br><table width=\"100%\" border=\"1\">";

					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="<td align=\"center\" colspan=\"3\">PROCEDIMIENTOS ENCONTRADOS</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr  class=\"modulo_table_title\">";
					$this->salida.="<td align=\"center\">CARGO</td>";
					$this->salida.="<td align=\"center\">PROCEDIMIENTO</td>";
					$this->salida.="<td align=\"center\">CANTIDAD</td>";
					$this->salida.="</tr>";
					//VECTOR
					for ($xx=0; $xx<sizeof($info_general); $xx++)
					{
						if($info_general[$xx][cargo] != $info_general[$xx-1][cargo])
						{
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="<td align=\"center\" width=\"10%\">".$info_general[$xx][cargo]."</td>";
							$this->salida.="<td align=\"left\" width=\"85%\">".$info_general[$xx][nombre_cargo]."</td>";
							$this->salida.="<td align=\"center\" width=\"5%\">".$info_general[$xx][cantidad]."</td>";
							$this->salida.="</tr>";
							if($info_general[$xx][cargo_equivalencia] != "")
							{
								$this->salida.="<tr>";
								$this->salida.="<td align=\"center\" colspan=\"3\" width=\"100%\"><table width=\"100%\">";
								$this->salida.="<tr class=\"modulo_list_oscuro\">";
								$this->salida.="<td colspan=\"3\" align=\"center\"><b>EQUIVALENCIAS RELACIONADAS A ESTE PROCEDIMIENTO</b></td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"modulo_list_oscuro\">";
								$this->salida.="<td align=\"center\"><b>CARGO EQ.</b></td>";
								$this->salida.="<td align=\"center\"><b>TARIFARIO EQ.</b></td>";
								$this->salida.="<td align=\"center\"><b>DESCRIPCION EQ.</b></td>";
								$this->salida.="</tr>";

								for ($jj=0; $jj<sizeof($info_general); $jj++)
								{
									if($jj % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_claro';}
									if($info_general[$xx][cargo] == $info_general[$jj][cargo])
									{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="<td align=\"center\" width=\"10%\">".$info_general[$jj][cargo_equivalencia]."</td>";
										$this->salida.="<td align=\"center\" width=\"15%\">".$info_general[$jj][tarifario_id]."</td>";
										$this->salida.="<td align=\"left\" width=\"75%\">".$info_general[$jj][nombre_equivelancia]."</td>";
										$this->salida.="</tr>";
									}
								}
								$this->salida.="</table></td>";
								$this->salida.="</tr>";
							}
						}
					}
					$this->salida.="</table></td>";
					$this->salida.="</tr>";
				}
			}
			$this->salida.="</table><br>";
               
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
			if (!empty($Observacion))
			{
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_table_list_title\" align=\"center\" width=\"100%\" colspan=\"2\">OBSERVACION MEDICA</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"justify\" width=\"10%\" colspan=\"2\" ><textarea class='textarea' name = 'lectura$pfj' cols = 60 rows = 3 style=\"width:100%\" readonly>".$Observacion."</textarea></td>";
				$this->salida.="</tr>";
			}
               
               $InformacionSala = $this->Get_InformacionSala($evolucion, $ingreso);
               
               if (!empty($InformacionSala))
			{
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_table_list_title\" align=\"center\" width=\"100%\" colspan=\"2\">SALA DONDE OCURRIO EL PROCEDIMIENTO</td>";
				$this->salida.="</tr>";
                    
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"center\" width=\"25%\">SALA</td>";
                    $this->salida.="<td align=\"center\" width=\"55%\">PROCEDIMIENTO</td>";
                    $this->salida.="</tr>";

                    for($i=0; $i<sizeof($InformacionSala); $i++)
                    {
                         if($i % 2){ $estilo='modulo_list_oscuro';}
					else {$estilo='modulo_list_claro';}
					$this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td align=\"justify\" width=\"30%\">".$InformacionSala[$i][descripcion]."</td>";
                         $this->salida.="<td align=\"justify\" width=\"50%\"><b>".$InformacionSala[$i][cargo_cups]."</b> - ".$InformacionSala[$i][nombre_procedimiento]."</td>";
                         $this->salida.="</tr>";
                    }
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"center\" colspan=\"2\">&nbsp;</td>";
                    $this->salida.="</tr>";
               }


			$diag =$this->Diagnosticos_Solicitados('N');
			if(!empty($diag[0][diagnostico_id]))
			{
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_table_list_title\" align=\"center\" width=\"15%\">DIAGNOSTICOS</td>";
				$this->salida.="<td class=\"modulo_list_oscuro\" width=\"65%\">";
				$this->salida.="<table width=\"100%\">";
				for($i=0;$i<sizeof($diag);$i++)
				{
					if($i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
                         if($diag[$i]['sw_principal']==1)
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
                         }
                         else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
                         }
                         
                         if($diag[$i][tipo_diagnostico] == '1')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
                         }elseif($diag[$i][tipo_diagnostico] == '2')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
                         }else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
                         }
                         $this->salida.="<td align=\"left\" width=\"10%\">".$diag[$i][diagnostico_id]."</td>";
					$this->salida.="<td align=\"left\" width=\"70%\">".$diag[$i][diagnostico_nombre]."</td>";
                         $this->salida.="<tr>";
				}
				$this->salida.="</table>";
				$this->salida .="</td>" ;
				$this->salida.="</tr>";

			}
               
               unset($diag);
               $diag =$this->Diagnosticos_Solicitados('C');
			if (!empty($diag[0][diagnostico_id]))
			{
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_table_list_title\" align=\"center\" width=\"15%\">DIAGNOSTICOS DE COMPLICACION</td>";
				$this->salida.="<td class=\"modulo_list_oscuro\" width=\"65%\">";
				$this->salida.="<table width=\"100%\">";
				for($i=0;$i<sizeof($diag);$i++)
				{
					if($i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
                         if($diag[$i]['sw_principal']==1)
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
                         }
                         else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
                         }
                         
                         if($diag[$i][tipo_diagnostico] == '1')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
                         }elseif($diag[$i][tipo_diagnostico] == '2')
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
                         }else
                         {
                              $this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
                         }
                         $this->salida.="<td align=\"left\" width=\"10%\">".$diag[$i][diagnostico_id]."</td>";
					$this->salida.="<td align=\"left\" width=\"70%\">".$diag[$i][diagnostico_nombre]."</td>";
                         $this->salida.="<tr>";
				}
				$this->salida.="</table>";
				$this->salida .="</td>" ;
				$this->salida.="</tr>";

			}
			
			$this->salida.="</table><br>";
		}
		$this->salida .= "</form>";

		//BOTON DEVOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr><br>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


	function frmConsulta()
	{
		return true;
	}
     
/**
* frmHistoria -
*
* @return boolean
*/


	function frmHistoria()
	{
		return $salida;
	}



	/**
	* Separa la fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
	 function FechaStamp($fecha)
	 {
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}


	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
	{
		$hor = strtok ($hora," ");
		for($l=0;$l<4;$l++)
		{
			$time[$l]=$hor;
			$hor = strtok (":");
		}
		$x = explode (".",$time[3]);
		return  $time[1].":".$time[2].":".$x[0];
	}

}
?>
