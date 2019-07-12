<?php

// $Id: hc_MotivoConsulta_HTML.php,v 1.8 2007/03/20 21:29:33 tizziano Exp $

class MotivoConsulta_HTML extends MotivoConsulta
{

	function MotivoConsulta_HTML()
	{
	    $this->MotivoConsulta();//constructor del padre
       	return true;
	}


/*IMPLEMENTACION DE LA BARRA DE NAVEGACION*/

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

///////////////////
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
		$motivos=$this->ConsultaMotivo();
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ListarSignosVitales'));
		$this->salida.= "<form name=\"motivoyenfermedad$pfj\" action=\"$accionI\" method=\"post\">";

		if($motivos===false)
		{
			return false;
		}
		if(!empty($motivos))
		{
			$this->salida .="<div class='label_mark' align='center'><br>MOTIVOS DE CONSULTA Y ENFERMEDAD ACTUAL<br><br></div>";

			$this->salida .="<table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td>MOTIVOS DE CONSULTA Y ENFERMEDAD ACTUAL</td>";
			$this->salida .="</tr>";

			$spy=0;
			foreach($motivos as $k=>$v)
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

				$this->salida .="<td width=\"10%\" nowrap align=\"center\">$k</td>";


				$this->salida .="<td><table border=\"1\" class=\"hc_table_submodulo_list\" width=\"100%\">";
				foreach($v as $k2=>$vector){

					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>&nbsp;$vector[hora]</b></td>";
					$this->salida .="<td><b>&nbsp;";
					$this->salida .=$vector[usuario].' - '.$vector[nombre];
					$this->salida .="</b></td>";
					$this->salida .="</tr>";

					if (!empty($vector[descripcion]))
					{
                              $motivo = chunk_split($vector[descripcion],150,'<br>');
						$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
						$this->salida .="<td class=\"hc_submodulo_list_claro\">&nbsp;</td>";
						$this->salida .="<td align=\"justify\"><b>&nbsp;MOTIVO DE CONSULTA  :<br></b>".$motivo."</td>";
						$this->salida .="</tr>";
					}

					if (!empty($vector[enfermedadactual]))
					{
                              $enfermedad = chunk_split($vector[enfermedadactual],150,'<br>');
						$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
						$this->salida .="<td class=\"hc_submodulo_list_claro\">&nbsp;</td>";
						$this->salida .="<td align=\"justify>\"<b>&nbsp;ENFERMEDAD ACTUAL  :<br></b>".$enfermedad."</td>";
						$this->salida .="</tr>";
					}

					$this->salida .="<tr>";
				}
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}

			$this->salida.="</table>";
			//Mostrar Barra de Navegacion
				$motivos =$this->RetornarBarra_Paginadora();
				if($motivos)
				{
					$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"100%\" align=\"center\">";
					$this->salida .=$motivos;
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
		$enfermedades=$this->Reporte_Motivos();

		if($enfermedades===false)
		{
			return false;
		}
		if(!empty($enfermedades))
		{
          
          	$this->salida .="<table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td>MOTIVOS DE CONSULTA Y ENFERMEDAD ACTUAL</td>";
			$this->salida .="</tr>";

			$spy=0;
			foreach($enfermedades as $k=>$v)
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

				$this->salida .="<td width=\"10%\" nowrap align=\"center\">$k</td>";
                    
                    $this->salida .="<td><table border=\"1\" class=\"hc_table_submodulo_list\" width=\"100%\">";				
                    
                    foreach($v as $k2=>$vector){

                        $this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
                        $this->salida .="<td><b>&nbsp;$vector[hora]</b></td>";
                        $this->salida .="<td><b>&nbsp;";
                        $this->salida .=$vector[usuario].' - '.$vector[nombre];
                        $this->salida .="</b></td>";
                        $this->salida .="</tr>";
    
                        if (!empty($vector[descripcion]))
                        {
                            $motivo = chunk_split($vector[descripcion],150,'<br>');
                            $this->salida .="<tr class=\"hc_submodulo_list_claro\">";
                            $this->salida .="<td class=\"hc_submodulo_list_claro\">&nbsp;</td>";
                            $this->salida .="<td align=\"justify\"><b>&nbsp;MOTIVO DE CONSULTA  :<br></b>".$motivo."</td>";
                            $this->salida .="</tr>";
                        }
    
                        if (!empty($vector[enfermedadactual]))
                        {
                            $enfermedad = chunk_split($vector[enfermedadactual],150,'<br>');
                            $this->salida .="<tr class=\"hc_submodulo_list_claro\">";
                            $this->salida .="<td class=\"hc_submodulo_list_claro\">&nbsp;</td>";
                            $this->salida .="<td align=\"justify>\"<b>&nbsp;ENFERMEDAD ACTUAL  :<br></b>".$enfermedad."</td>";
                            $this->salida .="</tr>";
                        }
				    $this->salida .="<tr>";
				}
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}

			$this->salida.="</table>";
		}
	    return true;
	}


	function frmHistoria()
	{
         	$pfj=$this->frmPrefijo;
		$enfermedades=$this->Reporte_Motivos();

		if($enfermedades===false)
		{
			return false;
		}
		if(!empty($enfermedades))
		{
          
          	$salida .="<br><table width=\"100%\" border=\"1\" align=\"center\">";
			$salida .="<tr>";
			$salida .="<td align=\"center\">FECHA</td>";
			$salida .="<td align=\"center\">MOTIVOS DE CONSULTA Y ENFERMEDAD ACTUAL</td>";
			$salida .="</tr>";

			$spy=0;
			foreach($enfermedades as $k=>$v)
			{
                    $salida.="<tr>";
				
                    $salida .="<td width=\"10%\" nowrap align=\"center\">$k</td>";
                    
                    $salida .="<td><table border=\"0\" width=\"100%\">";				
                    
                    foreach($v as $k2=>$vector){

                        $salida .="<tr>";
                        $salida .="<td><b>&nbsp;$vector[hora]</b></td>";
                        $salida .="<td><b>&nbsp;";
                        $salida .=$vector[usuario].' - '.$vector[nombre];
                        $salida .="</b></td>";
                        $salida .="</tr>";
    
                        if (!empty($vector[descripcion]))
                        {
                            $motivo = chunk_split($vector[descripcion],120,'<br>');
                            $salida .="<tr>";
                            $salida .="<td>&nbsp;</td>";
                            $salida .="<td align=\"justify\"><b>&nbsp;MOTIVO DE CONSULTA  :<br></b>".$motivo."</td>";
                            $salida .="</tr>";
                        }
    
                        if (!empty($vector[enfermedadactual]))
                        {
                             $enfermedad = chunk_split($vector[enfermedadactual],120,'<br>');
                             $salida .="<tr>";
                             $salida .="<td>&nbsp;</td>";
                             $salida .="<td align=\"justify>\"><b>&nbsp;ENFERMEDAD ACTUAL  :<br></b>".$enfermedad."</td>";
                             $salida .="</tr>";
                        }
				    $salida .="<tr>";
				}
				$salida .="</table>";
				$salida .="</td>";
				$salida .="</tr>";
			}

			$salida.="</table>";
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
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('MOTIVO CONSULTA Y ENFERMEDAD ACTUAL');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';

		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="<tr>";
		$this->salida.="<td width=\"50%\">";
		/**********************************/
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class='modulo_table_title'>";
		$this->salida.="<td align='center'>MOTIVO DE CONSULTA";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align='center' class='hc_submodulo_list_claro'>";
		$this->salida.="<textarea name=\"motivo".$pfj."\" cols=\"80\" rows=\"7\" style = \"width:90%\" class=\"textarea\"></textarea>";//".$this->PlanTerapeuticoActual()."
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";

		$this->salida.="<td width=\"50%\">";
		$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class='modulo_table_title'>";
		$this->salida.="<td align='center'>ENFERMEDAD ACTUAL";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align='center' class='hc_submodulo_list_claro'>";
		$this->salida.="<textarea name=\"enferact".$pfj."\" cols=\"80\" rows=\"7\" style = \"width:90%\" class=\"textarea\"></textarea>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		/**********************************/
		$this->salida.="<table width=\"100%\" align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" colspan=\"2\">";
		$this->salida.="<br><input type=\"submit\" value=\"INSERTAR\" class=\"input-submit\">";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</form>";
		$this->frmReporte();
          $this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
}

?>
