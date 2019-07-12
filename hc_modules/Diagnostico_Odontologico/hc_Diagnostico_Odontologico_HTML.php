<?php

/**
* Submodulo de Diagnosticos Ingreso Odontologico (HTML).
*
* Submodulo para manejar los Diagnosticos de ingreso odontologicos en un paciente en una evolución.
* @author Tizziano Perea Ocoro <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Diagnostico_Odontologico_HTML.php,v 1.14 2006/08/10 23:04:37 lorena Exp $
*/

/**
* Diagnostico_Odontologico_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo diagnostico de ingreso odontologico, se extiende la clase Diagnostico_Odontologico y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Diagnostico_Odontologico_HTML extends Diagnostico_Odontologico
{

	function Diagnostico_Odontologico_HTML()
	{
	    $this->Diagnostico_Odontologico();//constructor del padre
       	return true;
	}

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
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'area'.$pfj=>$_REQUEST['area'.$pfj]));

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
			$this->salida= ThemeAbrirTablaSubModulo('DIAGNOSTICOS ODONTOLOGICOS');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$areas = $this->Get_AreaEvaluada();
		$diag = $this->ConsultaDiagnosticoI();
		$obsevaciones = $this->ConsultarDescripcion();

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj =>'Insertar_Descripcion'));
		$this->salida.= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\" width=\"9%\">ADICION DX</td>";
		$this->salida.="<td align=\"center\" width=\"19%\">AREA EVALUADA</td>";
		$this->salida.="<td align=\"center\" width=\"72%\">DIAGNOSTICOS</td>";
		$this->salida.="</tr>";

		for($i=0; $i<sizeof($areas); $i++)
		{
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Dx', 'area'.$pfj=>$areas[$i][0]));
			$this->salida.="<td class=\"modulo_list_oscuro\" align=\"center\" width=\"9%\"><a href=\"$accionD\"><img src=\"".GetThemePath()."/images/diagnosticos.png\" border='0' title=\"Ingresar Diagnostico\"></a></td>";
			$this->salida.="<td class=\"modulo_list_oscuro\" align=\"justify\" width=\"19%\"><b>".$areas[$i][1]."</b></td>";
			//$this->salida.="<td class=\"modulo_list_oscuro\" align=\"justify\" width=\"19%\"><b>".strtoupper($areas[$i][1])."</b></td>";
			$this->salida.="<td align=\"center\" width=\"72%\">";
			$this->salida.="<table width=\"100%\">";

			if (!empty($diag))
			{
				for($j=0;$j<sizeof($diag);$j++)
				{
					if($diag[$j][area_evaluada_id] == $areas[$i][0] AND $diag[$j][area_evaluada_id] != $diag[$j-1][area_evaluada_id])
					{
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="<td align=\"center\" width=\"6%\">PRINCIPAL</td>";
						$this->salida.="<td align=\"center\" width=\"10%\">TIPO DX</td>";
						$this->salida.="<td align=\"center\" width=\"10%\">CODIGO</td>";
						$this->salida.="<td align=\"center\" width=\"60%\">DIAGNOSTICO</td>";
						$this->salida.="<td align=\"center\"width=\"5%\">ELIMINAR</td>";
						$this->salida.="</tr>";
					}

					if ($diag[$j][area_evaluada_id] == $areas[$i][0])
					{
						$diagnostico_id = $diag[$j][diagnostico_id];
                              $area_E = $diag[$j][area_evaluada_id];
						if( $j % 2){$estilo='modulo_list_claro';}
						else {$estilo='modulo_list_claro';}
						$this->salida.="<tr class=\"$estilo\">";
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'cambiar_diagnostico', 'diagnostico_id'.$pfj =>$diagnostico_id));
						if($diag[$j]['sw_principal']==1)
						{
							$this->salida.="<td align=\"center\" width=\"6%\"><img src=\"".GetThemePath()."/images/checksi.png\" border='0'></td>";
						}
						else
						{
							$this->salida.="<td align=\"center\" width=\"6%\"><a href='$accion'><img src=\"".GetThemePath()."/images/checkno.png\" border='0'></a></td>";
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
						$this->salida.="<td align=\"left\" width=\"65%\">".$diag[$j][diagnostico_nombre]."</td>";

						$accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico', 'diagnostico_id'.$pfj =>$diagnostico_id, 'principal'.$pfj =>$diag[$j][sw_principal], 'area_E'.$pfj=>$area_E));
						if ($diag[$j][evolucion_id] != $this->evolucion)
						{
							$this->salida.="<td align=\"center\" width=\"5%\"></td>";
						}
						else
						{
							$this->salida.="<td align=\"center\" width=\"5%\"><a href='$accionE'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
						}
						$this->salida.="</tr>";
					}
				}
			}
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}
		if (!empty($diag))
		{
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"left\" colspan=\"3\" valign=\"top\">&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
		}
		$this->salida.="</table><br>";

		$this->salida.="<table align=\"center\" border=\"0\" width=\"90%\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\" width=\"100%\">OBSERVACIONES </td>";
		$this->salida.="</tr>";

		if(!empty($obsevaciones))
		{
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td align=\"justify\" width=\"100%\">".strtoupper($obsevaciones)."</td>";
			$this->salida.="</tr>";
		}

		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="<td align=\"center\" width=\"100%\"><textarea name=\"observacion".$pfj."\" cols=\"80\" rows=\"5\" style = \"width:100%\" class=\"textarea\"></textarea></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\" width=\"100%\"><input type=\"submit\" name=\"guardar_obj$pfj\" value=\"GUARDAR\"></td>";
		$this->salida.="</tr>";
          $this->salida.="</table>";

          ///////////////////// NUEVO ////////////////////////
          if(($this->hc_modulo == 'OdontologiaTratamiento') || ($this->hc_modulo == 'OdontologiaGeneral'))
          {
          	$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'primera_vez'));
               $this->salida.="<br><table width=\"100%\">";
               $this->salida.="<tr><td align=\"right\" class=\"label\"><a href='$accion'><img src=\"".GetThemePath()."/images/pinactivo.png\" border='0'>&nbsp;DIAGNOSTICO ODONTOLOGICO DE PRIMERA CITA</a>";
               $this->salida.="</td></tr>";
               $this->salida.="</table>";
          }
          ///////////////////// NUEVO ////////////////////////

		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


	function frm_Busqueda_Dx($area, $vectorD)
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('BUSQUEDA DE DIAGNOSTICOS');
		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj], 'paso1'=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj],
		'area'.$pfj=>$area));
		$this->salida.="<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida.="<td width=\"46%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"></td>" ;
		$this->salida.= "<td  width=\"5%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_varios_diagnosticos', 'area'.$pfj=>$area));
		$this->salida.= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
		if ($vectorD)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"8%\">CODIGO</td>";
			$this->salida.="  <td width=\"53%\">DIAGNOSTICO</td>";
			$this->salida.="  <td width=\"17%\">TIPO DX</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++)
			{
				$codigo= $vectorD[$i][diagnostico_id];
				$diagnostico= $vectorD[$i][diagnostico_nombre];
				if( $i % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td align=\"center\" width=\"8%\">$codigo</td>";
				$this->salida.="<td align=\"left\" width=\"53%\">$diagnostico</td>";
				$this->salida.="<td width=\"17%\" align=\"center\">";
				$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx$i$pfj\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
 				$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".$pfj."[$i]' value = ".$codigo."></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"left\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraDiagnosticos_Avanzada();
			if(!empty($var))
			{
				$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
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
		$this->salida .= "<tr><td colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;

		$areas = $this->Get_AreaEvaluada();
		$diag = $this->ConsultaDiagnosticoI();
		$obsevaciones = $this->ConsultarDescripcion();

          if(!empty($diag))
          {
               $accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj =>'Insertar_Descripcion'));
               $this->salida.= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
               $this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\" class=\"hc_table_submodulo_list\">";
               
               $this->salida.="<tr class=\"modulo_table_list_title\">";
               $this->salida.="<td align=\"center\" colspan=\"3\" width=\"100%\">CONSOLIDADO DIAGNOSTICOS ODONTOLOGICOS</td>";
               $this->salida.="</tr>";
     
               $this->salida.="<tr class=\"modulo_table_list_title\">";
               $this->salida.="<td align=\"center\" colspan=\"2\" width=\"20%\">AREA EVALUADA</td>";
               $this->salida.="<td align=\"center\" width=\"80%\">DIAGNOSTICOS</td>";
               $this->salida.="</tr>";
     
               for($i=0; $i<sizeof($areas); $i++)
               {
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td align=\"left\" colspan=\"2\" width=\"20%\">".$areas[$i][1]."</td>";
                    $this->salida.="<td align=\"center\" width=\"80%\">";
                    $this->salida.="<table width=\"100%\">";
     
                    if (!empty($diag))
                    {
                         for($j=0;$j<sizeof($diag);$j++)
                         {
                              if($diag[$j][area_evaluada_id] == $areas[$i][0] AND $diag[$j][area_evaluada_id] != $diag[$j-1][area_evaluada_id])
                              {
                                   $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                   $this->salida.="<td align=\"center\" width=\"6%\">PRINCIPAL</td>";
                                   $this->salida.="<td align=\"center\" width=\"10%\">TIPO DX</td>";
                                   $this->salida.="<td align=\"center\" width=\"10%\">CODIGO</td>";
                                   $this->salida.="<td align=\"center\" width=\"60%\">DIAGNOSTICO</td>";
                                   $this->salida.="</tr>";
                              }
     
                              if ($diag[$j][area_evaluada_id] == $areas[$i][0])
                              {
                                   $diagnostico_id = $diag[$j][diagnostico_id];
                                   if( $j % 2){$estilo='modulo_list_claro';}
                                   else {$estilo='modulo_list_claro';}
                                   $this->salida.="<tr class=\"$estilo\">";
                                   if($diag[$j]['sw_principal']==1)
                                   {
                                        $this->salida.="<td align=\"center\" width=\"6%\"><img src=\"".GetThemePath()."/images/checksi.png\" border='0'></td>";
                                   }
                                   else
                                   {
                                        $this->salida.="<td align=\"center\" width=\"6%\">&nbsp;</td>";
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
                                   $this->salida.="<td align=\"left\" width=\"65%\">".$diag[$j][diagnostico_nombre]."</td>";
                                   $this->salida.="</tr>";
                              }
                         }
                    }
                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
               }
               if (!empty($diag))
               {
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td align=\"left\" colspan=\"3\" valign=\"top\">&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="</table>";
               
               if(!empty($obsevaciones))
               {
                    $this->salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"hc_table_submodulo_list\">";
                    $this->salida.="<tr class=\"modulo_table_list_title\">";
                    $this->salida.="<td align=\"center\" width=\"100%\">OBSERVACIONES  DIAGNOSTICAS</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"modulo_list_oscuro\">";
                    $this->salida.="<td align=\"justify\" width=\"100%\">".strtoupper($obsevaciones)."</td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
               }
     
               $this->salida .= "<br>";
          }
		return true;
	}

	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;

		$areas = $this->Get_AreaEvaluada();
		$diag = $this->ConsultaDiagnosticoI();
		$obsevaciones = $this->ConsultarDescripcion();
          
          if(!empty($diag))
          {
               $salida.="<table  align=\"center\" border=\"1\" width=\"100%\" class=\"hc_table_submodulo_list\">";
               
               $salida.="<tr class=\"modulo_table_list_title\">";
               $salida.="<td align=\"center\" colspan=\"3\" width=\"100%\">DIAGNOSTICOS ODONTOLOGICOS</td>";
               $salida.="</tr>";
     
               $salida.="<tr class=\"modulo_table_list_title\">";
               $salida.="<td align=\"center\" colspan=\"2\" width=\"20%\">AREA EVALUADA</td>";
               $salida.="<td align=\"center\" width=\"80%\">DIAGNOSTICOS</td>";
               $salida.="</tr>";
     
               for($i=0; $i<sizeof($areas); $i++)
               {
                    $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Dx', 'area'.$pfj=>$areas[$i][0]));
                    $salida.="<td align=\"left\" colspan=\"2\" width=\"20%\">".$areas[$i][1]."</td>";
                    $salida.="<td align=\"center\" width=\"80%\">";
                    $salida.="<table width=\"100%\">";
     
                    if (!empty($diag))
                    {
                         for($j=0;$j<sizeof($diag);$j++)
                         {
                              if($diag[$j][area_evaluada_id] == $areas[$i][0] AND $diag[$j][area_evaluada_id] != $diag[$j-1][area_evaluada_id])
                              {
                                   $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                   $salida.="<td align=\"center\" width=\"6%\">PRINCIPAL</td>";
                                   $salida.="<td align=\"center\" width=\"10%\">TIPO DX</td>";
                                   $salida.="<td align=\"center\" width=\"10%\">CODIGO</td>";
                                   $salida.="<td align=\"center\" width=\"60%\">DIAGNOSTICO</td>";
                                   $salida.="</tr>";
                              }
     
                              if ($diag[$j][area_evaluada_id] == $areas[$i][0])
                              {
                                   $diagnostico_id = $diag[$j][diagnostico_id];
                                   if( $j % 2){$estilo='modulo_list_claro';}
                                   else {$estilo='modulo_list_oscuro';}
                                   $salida.="<tr class=\"$estilo\">";
                                   if($diag[$j]['sw_principal']==1)
                                   {
                                        $salida.="<td align=\"center\" width=\"6%\">DX Nº 1</td>";
                                   }
                                   else
                                   {
                                        $salida.="<td align=\"center\" width=\"6%\">&nbsp;</td>";
                                   }
     
                                   if($diag[$j][tipo_diagnostico] == '1')
                                   {
                                        $salida.="<td align=\"center\" width=\"10%\">ID</td>";
                                   }elseif($diag[$j][tipo_diagnostico] == '2')
                                   {
                                        $salida.="<td align=\"center\" width=\"10%\">CN</td>";
                                   }else
                                   {
                                        $salida.="<td align=\"center\" width=\"10%\">CR</td>";
                                   }
     
                                   $salida.="<td align=\"center\" width=\"10%\">".$diag[$j][diagnostico_id]."</td>";
                                   $salida.="<td align=\"left\" width=\"65%\">".$diag[$j][diagnostico_nombre]."</td>";
                                   $salida.="</tr>";
                              }
                         }
                    }
                    $salida.="</table>";
                    $salida.="</td>";
                    $salida.="</tr>";
               }
               if (!empty($diag))
               {
                    $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $salida.="<td align=\"left\" colspan=\"3\" valign=\"top\">&nbsp;&nbsp;&nbsp;ID = &nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CN = &nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CR = &nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;</td>";
                    $salida.="</tr>";
               }
               $salida.="</table>";
               
               if(!empty($obsevaciones))
               {
                    $salida.="<table align=\"center\" border=\"1\" width=\"100%\" class=\"hc_table_submodulo_list\">";
                    $salida.="<tr class=\"modulo_table_list_title\">";
                    $salida.="<td align=\"center\" width=\"100%\">OBSERVACIONES  DIAGNOSTICAS</td>";
                    $salida.="</tr>";
                    $salida.="<tr class=\"modulo_list_oscuro\">";
                    $salida.="<td align=\"justify\" width=\"100%\">".strtoupper($obsevaciones)."</td>";
                    $salida.="</tr>";
                    $salida.="</table>";
               }
               $salida.="<br>";
          }
		return $salida;
	}
     
     function frm_Diagnostico_Odontologico_PrimeraVez()
     {
          $pfj=$this->frmPrefijo;
		$areas = $this->Get_AreaEvaluada();
		$diag = $this->ConsultaDiagnostico_PrimeraVez();
		$obsevaciones = $this->ConsultarDescripcion_PrimeraVez();
          
          $this->salida  = ThemeAbrirTablaSubModulo('DIAGNOSTICO ODONTOLOGICO DE PRIMERA VEZ');
          if(!empty($diag))
          {
               $accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
               $this->salida.= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
               $this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\" class=\"hc_table_submodulo_list\">";
               
               $this->salida.="<tr class=\"modulo_table_list_title\">";
               $this->salida.="<td align=\"center\" colspan=\"3\" width=\"100%\">CONSOLIDADO DIAGNOSTICO ODONTOLOGICO DE PRIMERA VEZ</td>";
               $this->salida.="</tr>";
     
               $this->salida.="<tr class=\"modulo_table_list_title\">";
               $this->salida.="<td align=\"center\" colspan=\"2\" width=\"20%\">AREA EVALUADA</td>";
               $this->salida.="<td align=\"center\" width=\"80%\">DIAGNOSTICOS</td>";
               $this->salida.="</tr>";
     
               for($i=0; $i<sizeof($areas); $i++)
               {
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td align=\"left\" colspan=\"2\" width=\"20%\">".$areas[$i][1]."</td>";
                    $this->salida.="<td align=\"center\" width=\"80%\">";
                    $this->salida.="<table width=\"100%\">";
     
                    if (!empty($diag))
                    {
                         for($j=0;$j<sizeof($diag);$j++)
                         {
                              if($diag[$j][area_evaluada_id] == $areas[$i][0] AND $diag[$j][area_evaluada_id] != $diag[$j-1][area_evaluada_id])
                              {
                                   $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                   $this->salida.="<td align=\"center\" width=\"6%\">PRINCIPAL</td>";
                                   $this->salida.="<td align=\"center\" width=\"10%\">TIPO DX</td>";
                                   $this->salida.="<td align=\"center\" width=\"10%\">CODIGO</td>";
                                   $this->salida.="<td align=\"center\" width=\"60%\">DIAGNOSTICO</td>";
                                   $this->salida.="</tr>";
                              }
     
                              if ($diag[$j][area_evaluada_id] == $areas[$i][0])
                              {
                                   $diagnostico_id = $diag[$j][diagnostico_id];
                                   if( $j % 2){$estilo='modulo_list_claro';}
                                   else {$estilo='modulo_list_claro';}
                                   $this->salida.="<tr class=\"$estilo\">";
                                   if($diag[$j]['sw_principal']==1)
                                   {
                                        $this->salida.="<td align=\"center\" width=\"6%\"><img src=\"".GetThemePath()."/images/checksi.png\" border='0'></td>";
                                   }
                                   else
                                   {
                                        $this->salida.="<td align=\"center\" width=\"6%\">&nbsp;</td>";
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
                                   $this->salida.="<td align=\"left\" width=\"65%\">".$diag[$j][diagnostico_nombre]."</td>";
                                   $this->salida.="</tr>";
                              }
                         }
                    }
                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
               }
               if (!empty($diag))
               {
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td align=\"left\" colspan=\"3\" valign=\"top\">&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="</table>";
               
               if(!empty($obsevaciones))
               {
                    $this->salida.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"hc_table_submodulo_list\">";
                    $this->salida.="<tr class=\"modulo_table_list_title\">";
                    $this->salida.="<td align=\"center\" width=\"100%\">OBSERVACIONES  DIAGNOSTICAS</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"modulo_list_oscuro\">";
                    $this->salida.="<td align=\"justify\" width=\"100%\">".strtoupper($obsevaciones)."</td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
               }
          }
          else
          {
            $this->salida.="<div align=\"center\" class='label_mark'>NO SE HA REGISTRADO DIAGNOSTICO ODONTOLOGICO EN LA PRIMERA CITA.</div>";
          }
          
          //BOTON DEVOLVER
          $this->salida.="<br><table width=\"87%\" align=\"center\"><tr><td align=\"center\">";
          $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $this->salida.= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
          $this->salida.= "<tr><td colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
          $this->salida.="</table>";
          $this->salida .= "<br>";

		$this->salida.= ThemeCerrarTablaSubModulo();
		return true;
     }

}

?>
