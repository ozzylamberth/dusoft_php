<?php

/**
* Submodulo de Diagnosticos Muerte (HTML).
*
* Submodulo para manejar los Diagnosticos de Muerte (rips) en un paciente en una evolución.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co

* Modificado por
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* Jun/02/2004

* @version 1.0
* @package SIIS
* $Id: hc_DiagnosticoM_HTML.php,v 1.3 2006/12/19 21:00:13 jgomez Exp $
*/

/**
* DiagnosticoM_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo diagnostico Muerte, se extiende la clase DiagnosticoM y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class DiagnosticoM_HTML extends DiagnosticoM
{
//JAIME
	function DiagnosticoM_HTML()
	{
	    $this->DiagnosticoM();//constructor del padre
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
///////////////////////////////////////////////////////////
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
	function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora
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
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));

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


function frmForma($vectorD)
	{
		$pfj=$this->frmPrefijo;
		if(empty($this->titulo))
		{
			$this->salida= ThemeAbrirTablaSubModulo('DIAGNOSTICOS DE MUERTE');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$diag =$this->ConsultaDiagnosticoM();
		if ($diag)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">DIAGNOSTICOS DE MUERTE ASIGNADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
							$this->salida.="  <td width=\"10%\">CODIGO</td>";
							$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";

							$this->salida.="</tr>";
      for($i=0;$i<sizeof($diag);$i++)
		    {
					$diagnostico_id = $diag[$i][diagnostico_id];
          if( $i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
          $accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico', 'diagnostico_id'.$pfj => $diagnostico_id));
    			$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
					$this->salida.="<td align=\"left\">$diagnostico_id</td>";
					$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
					$this->salida.="<tr>";
				}
			$this->salida.="</table><br>";
		}

			$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
			 'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
			 'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
			 'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));

			$this->salida .= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
							$this->salida.="</tr>";

							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="<td width=\"4%\">CODIGO:</td>";

							$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;

							$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
              $this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"        ></td>" ;

							$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
							$this->salida.="</tr>";
							$this->salida.="</table><br>";

              $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		          $this->salida .= $this->SetStyle("MensajeError");
		          $this->salida.="</table>";

							$this->salida.="</form>";

				 $accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_varios_diagnosticos'));
				 $this->salida .= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
        if ($vectorD)
          {
             $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
							$this->salida.="</tr>";

							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"10%\">CODIGO</td>";
							$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
							$this->salida.="  <td width=\"5%\">OPCION</td>";
							$this->salida.="</tr>";
              for($i=0;$i<sizeof($vectorD);$i++)
						   {
									$codigo          = $vectorD[$i][diagnostico_id];
									$diagnostico    = $vectorD[$i][diagnostico_nombre];

									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";

									$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
									$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
									$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".$pfj."[$i]' value = ".$codigo."></td>";
									$this->salida.="</tr>";

								}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
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
      $this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


	function frmConsulta()
	{
	$pfj=$this->frmPrefijo;
	$diag =$this->ConsultaDiagnosticoM();
    if ($diag)
		{
			$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"2\">DIAGNOSTICOS DE MUERTE ASIGNADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"15%\">CODIGO</td>";
							$this->salida.="  <td width=\"85%\">DIAGNOSTICO DE MUERTE</td>";
							$this->salida.="</tr>";
      for($i=0;$i<sizeof($diag);$i++)
		    {
          if( $i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\">".$diag[$i][diagnostico_id]."</td>";
					$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
					$this->salida.="<tr>";
				}
			$this->salida.="</table>";
		}
    return true;
	}

	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$diag =$this->ConsultaDiagnosticoM();
		if ($diag)
			{
				$salida.="<br><table  align=\"center\" border=\"1\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
				$salida.="<tr class=\"modulo_table_title\">";
				$salida.="  <td align=\"center\" colspan=\"2\">DIAGNOSTICOS DE MUERTE ASIGNADOS</td>";
				$salida.="</tr>";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width=\"15%\">CODIGO</td>";
				$salida.="  <td width=\"85%\">DIAGNOSTICO DE MUERTE</td>";
				$salida.="</tr>";
		for($i=0;$i<sizeof($diag);$i++)
		{
			if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
					$salida.="<tr class=\"$estilo\">";
					$salida.="<td align=\"center\">".$diag[$i][diagnostico_id]."</td>";
					$salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
					$salida.="<tr>";
				}
				$salida.="</table>";
			}
		return $salida;
	}
}

?>
