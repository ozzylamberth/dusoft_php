<?php
/**
* Submodulo de Diagnosticos Ingreso (HTML).
*
* Submodulo para manejar los Diagnosticos de ingreso (rips) en un paciente en una evolución.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co

* Modificado por
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* Jun/02/2004

* @version 1.0
* @package SIIS
* $Id: hc_DiagnosticoI_HTML.php,v 1.13 2008/12/15 18:08:53 hugo Exp $
*/

/**
* DiagnosticoI_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo diagnostico ingreso, se extiende la clase DiagnosticoI y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class DiagnosticoI_HTML extends DiagnosticoI
{
//JAIME
	function DiagnosticoI_HTML()
	{
	    $this->DiagnosticoI();//constructor del padre
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
			$this->salida= ThemeAbrirTablaSubModulo('DIAGNOSTICOS DE INGRESO');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$diag =$this->ConsultaDiagnosticoI();

    $rpt = new GetReports();
    $mst = $rpt->GetJavaReport('hc','DiagnosticosdeVIH','ficha_datos_basicos',array("paciente"=>$this->datosPaciente),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
    $fnc = $rpt->GetJavaFunction();
    $this->salida .= $mst;
    
    if($this->VerificarFicha() == '1')
    {
      $this->salida .= "<center>\n";
      $this->salida .= "  <a href=\"javascript:".$fnc."\" class=\"label_error\">FICHA DE NOTIFICACION</a>";
      $this->salida .= "</center><br>\n";
    }
    
		if ($diag)
		{
      $fichas = $this->ObtenerFichas();

      if(!empty($fichas))
      {
        $url = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>"llenar_ficha"));
 
        $this->salida .= "<center>\n";
        foreach($fichas as $k => $dtl)
        {
          $vec = array(); 
          $vec['tabla'] = "hc_diagnosticos_ingreso";
          $vec['grupo_ficha_id'] = $dtl['grupo_ficha_id'];
          $vec['diagnostico_ingreso'][$dtl['grupo_ficha_id']]['evolucion_id'] = $dtl['evolucion_id'];
          $vec['diagnostico_ingreso'][$dtl['grupo_ficha_id']]['tipo_diagnostico_id'] = $dtl['tipo_diagnostico_id'];
          $vec['diagnostico_ingreso'][$dtl['grupo_ficha_id']]['diagnostico_id'] = $dtl['diagnostico_id'];
          $this->salida .= "  <a href=\"".$url.URLrequest($vec)."\" class=\"label\">LLENAR FICHA DE ".strtoupper($k)."</a>&nbsp;\n";
        }
        $this->salida .= "</center><br>\n";
      }
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"6\">DIAGNOSTICOS DE INGRESO ASIGNADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"15%\">PRIMARIO</td>";
			$this->salida.="<td width=\"10%\">TIPO DX</td>";
			$this->salida.="<td width=\"8%\">CODIGO</td>";
			$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
			$this->salida.="<td width=\"7%\">ELIMINAR</td>";
			$this->salida.="<td width=\"7%\">&nbsp;&nbsp;&nbsp;&nbsp;NOTA&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($diag);$i++)
			{
				$diagnostico_id = $diag[$i][diagnostico_id];
                    $evolucion = $diag[$i][evolucion_id];
				if( $i % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'cambiar_diagnostico', 'diagnostico_id'.$pfj =>$diagnostico_id, 'evolucion'.$pfj =>$evolucion));
				if($diag[$i]['sw_principal']==1)
				{
					$this->salida.="<td align=\"center\" width=\"15%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
				}
				else
				{
					$this->salida.="<td align=\"center\" width=\"15%\"><a href='$accion'><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></a></td>";
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

				$this->salida.="<td width=\"8%\" align=\"center\">".$diag[$i][diagnostico_id]."</td>";
				$this->salida.="<td width=\"60%\" align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
				$accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar_diagnostico', 'diagnostico_id'.$pfj =>$diagnostico_id, 'principal'.$pfj =>$diag[$i][sw_principal]));
				if ($diag[$i][evolucion_id] != $this->evolucion)
				{
					$this->salida.="<td align=\"center\" width=\"10%\">&nbsp;</td>";
				}
				else
				{
					$this->salida.="<td align=\"center\" width=\"10%\"><a href='$accionE'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
				}

                    if ($diag[$i][evolucion_id] == $this->evolucion)
				{
					$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'cambiar_descripcion', 'diagnostico_id'.$pfj =>$diag[$i][diagnostico_id],'descripcion'.$pfj=>$diag[$i][diagnostico_nombre], 'contenido'.$pfj=>$diag[$i][descripcion]));
					$this->salida.="<td align=\"center\" width=\"10%\"><a href='$accion'><img title=\"Modificar\" src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
				}
				else
				{
          			$this->salida.="<td align=\"center\" width=\"10%\">&nbsp;</td>";
				}
				$this->salida.="</tr>";
				if(!empty($diag[$i][descripcion]))
				{
					$this->salida.="<tr>";
					$this->salida.="<td class=\"modulo_table_list_title\">DESCRIPCION DX:";
					$this->salida.="</td>";
					$this->salida.="<td colspan=\"5\" class=\"$estilo\">".$diag[$i][descripcion]."";
					$this->salida.="</td>";
					$this->salida.="</tr>";
				}
			}
			if (!empty($diag))
			{
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"left\" colspan=\"6\" valign=\"top\">&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
		}
    
		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos', 'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj], 'codigo'.$pfj=>$_REQUEST['codigo'.$pfj], 'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
		$this->salida.= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"></td>" ;
		$this->salida.= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSQUEDA\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_varios_diagnosticos'));
		$this->salida.= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
		if ($vectorD)
		{
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"8%\">CODIGO</td>";
			$this->salida.="  <td width=\"60%\">DIAGNOSTICO</td>";
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
				$this->salida.="<td align=\"left\" width=\"60%\">$diagnostico</td>";
				$this->salida.="<td align=\"center\" width=\"17%\">";
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
			$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
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
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}



	function CambiarDescripcion()
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('DIAGNOSTICOS DE INGRESO');
		
		
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
		$accionA=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Insertar_Descripcion','codigo'.$pfj=>$_REQUEST['diagnostico_id'.$pfj]));
		$this->salida.= "<form name=\"descripcion$pfj\" action=\"$accionA\" method=\"post\">";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\" width=\"5%\">CODIGO</td>";
		$this->salida.="<td align=\"center\" colspan=\"2\" width=\"75%\">NOMBRE</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" width=\"5%\" colspan=\"2\" class=\"modulo_list_claro\">".$_REQUEST['diagnostico_id'.$pfj]."</td>";
		$this->salida.="<td align=\"left\" width=\"75%\" colspan=\"2\" class=\"modulo_list_claro\">".$_REQUEST['descripcion'.$pfj]."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<br>";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\" width=\"20%\">DESCRIPCION</td>";
		$this->salida.="<td align =\"left\" width=\"80%\"><textarea name='descripcion_diag$pfj' style=\"width:100%\" cols=40 rows=7>".$_REQUEST['contenido'.$pfj]."</textarea>";//.$_REQUEST['contenido'.$pfj].
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td>";
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="<tr>";
		$this->salida.="<td colspan=\"2\" width=\"50%\" align=\"right\"><input class=\"input-submit\" name=\"insertar$pfj\" type=\"submit\" value=\"INSERTAR\"></td>";
		$this->salida.="</form>";
		$accionB=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Volver_Original'));
		$this->salida.="<form name=\"descripcion2$pfj\" action=\"$accionB\" method=\"post\">";
		$this->salida.="<td colspan=\"2\" width=\"50%\" align=\"left\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</table><br>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$diag =$this->ConsultaDiagnosticoI();
		if ($diag)
		{
			$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">DIAGNOSTICOS DE INGRESO ASIGNADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">CODIGO</td>";
			$this->salida.="<td width=\"50%\">DIAGNOSTICO DE INGRESO</td>";
			$this->salida.="<td width=\"40%\">OBSERVACION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($diag);$i++)
			{
				if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\">".$diag[$i][diagnostico_id]."</td>";
					$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
					$this->salida.="<td align=\"left\">".$diag[$i][descripcion]."</td>";
					$this->salida.="<tr>";
			}
			$this->salida.="</table><br>";
			$this->salida.="<br>";
		}
		return true;
	}

	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$diag =$this->ConsultaDiagnosticoI();
		if ($diag)
		{
			$salida.="<br><table  align=\"center\" border=\"1\"  width=\"100%\">";
			$salida.="<tr>";
			$salida.="<td align=\"center\" colspan=\"3\">DIAGNOSTICOS DE INGRESO ASIGNADOS</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td width=\"10%\" align=\"center\">CODIGO</td>";
			$salida.="<td width=\"50%\" align=\"center\">DIAGNOSTICO DE INGRESO</td>";
			$salida.="<td width=\"40%\">OBSERVACION</td>";
			$salida.="</tr>";
			for($i=0;$i<sizeof($diag);$i++)
			{
				$salida.="<tr>";
				$salida.="<td align=\"center\">".$diag[$i][diagnostico_id]."</td>";
				$salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
				$salida.="<td align=\"left\">".$diag[$i][descripcion]."</td>";
				$salida.="<tr>";
			}
			$salida.="</table><br>";
		}
		return $salida;
	}
}
?>
