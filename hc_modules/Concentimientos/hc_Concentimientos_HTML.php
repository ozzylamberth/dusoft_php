<?php

/**
* Submodulo de Concentimientos (HTML).
*
* Submodulo para manejar los Concentimientos odontologicos en un paciente en una evolución.
* @author Carlos A. Henao <carlosarturohenao@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Concentimientos_HTML.php,v 1.3 2005/09/07 22:34:15 carlos Exp $
*/

/**
* Concentimientos_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo Concentimientos, se extiende la clase Concentimientos y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Concentimientos_HTML extends Concentimientos
{

	function Concentimientos_HTML()
	{
	    $this->Concentimientos();//constructor del padre
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


	function frmForma($areas)
	{
		$pfj=$this->frmPrefijo;
		if(empty($this->titulo))
		{
			$this->salida= ThemeAbrirTablaSubModulo('CONCENTIMIENTOS ODONTOLOGICOS');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$diag = $this->ConsultaDiagnosticoI();
		$obsevaciones = $this->ConsultarDescripcion();

		$this->salida.="<script>";
		$this->salida.="function desabilitar(frm,valor){";
    $this->salida.="  if(valor==1){";
    $this->salida.="    frm.TipoDocumentoResponsable.disabled=true;";
		$this->salida.="    frm.DocumentoResponsable.disabled=true;";
		$this->salida.="    frm.parentescoResponsable.disabled=true;";
		$this->salida.="    frm.nombreResponsable.disabled=true;";
    $this->salida.="  }else{";
    $this->salida.="    frm.TipoDocumentoResponsable.disabled=false;";
		$this->salida.="    frm.DocumentoResponsable.disabled=false;";
		$this->salida.="    frm.parentescoResponsable.disabled=false;";
		$this->salida.="    frm.nombreResponsable.disabled=false;";
		$this->salida.="  }";
		$this->salida.="}";
		$this->salida.="</script>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj =>'BuscarItems'));
		$this->salida.= "<form name=\"forma$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\" width=\"100%\">TRAER TIPO DE CONCENTIMIENTO</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" width=\"50%\">";
		$this->salida.="TIPO DE CONCENTIMIENTO: <select name=\"con".$pfj."\" class=\"select\">";
		$busquedas = $this->Get_Concentimientos();

		for($i=0;$i<sizeof($busquedas);$i++)
		{
			if($busquedas[$i]['indice_orden']==1)
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['id_concentimiento']."\" selected>".$busquedas[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida.="	<option value=\"".$busquedas[$i]['id_concentimiento']."\">".$busquedas[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="</td>";
		$this->salida.="</tr>";
		//$this->concentimiento_id=$_REQUEST["con".$pfj];
		if (empty($_SESSION['con']['busquedas']))
			$_SESSION['con']['busquedas']=$_REQUEST["con".$pfj];//id_concentimiento
		$_SESSION['con']['ingreso']=$this->ingreso;
		$_SESSION['con']['evolucion']=$this->evolucion;
		$_SESSION['con']['usuario']=UserGetUID();
		//echo $_SESSION['con']['busquedas']; 
		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" width=\"100%\"><input type=\"submit\" name=\"traer".$pfj."\" value=\"TRAER\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</form>";

		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj =>'Insertar_Descripcion','areas'=>$areas,'busquedas'=>$busquedas));
		$this->salida.= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
		//$this->salida.="</tr>";
		//$areas = $this->Get_Concentimientos_Items();
		if($areas)
		{
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="<td align=\"left\" width=\"5%\">SEL</td>";
				$this->salida.="<td align=\"center\" width=\"95%\">ITEM</td>";
				$this->salida.="</tr>";
				for($i=0; $i<sizeof($areas); $i++)
				{
					$this->salida.="<tr class=\"modulo_list_claro\">";
					$this->salida.="<td align=\"left\" width=\"5%\"><input type=checkbox name=\"con".$pfj."".$i."\" value=\"".$areas[$i][id_concentimiento].','.$areas[$i][item_id]."\"></td>";
					$this->salida.="<td align=\"left\" width=\"95%\"><b>".strtoupper($areas[$i][descripcion])."</b></td>";
					$this->salida.="</tr>";
				}
				$this->salida.="</table><br>";
		}
//--------------
		$this->salida.="            <BR><table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida.="        <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td class=\"label\" valign=\"top\">RESPONSABLE</td>";
		$this->salida.="        <td class=\"label\" valign=\"top\">PACIENTE<input type=\"radio\" name=\"responsable\" value=\"1\" onclick=\"desabilitar(this.form,this.value)\" checked></td>";
		$this->salida.="        <td class=\"label\" valign=\"top\">OTRO RESPONSABLE<input type=\"radio\" name=\"responsable\" value=\"2\" onclick=\"desabilitar(this.form,this.value)\"></td>";
		$this->salida.="            <tr class=\"modulo_table_list_title\">";
    $this->salida.="            <td>&nbsp;</td>";
    $this->salida.="            <td colspan=\"2\">DATOS RESPONSABLE</td>";
		$this->salida.="            </tr>";
		$this->salida.="            <tr>";
    $this->salida.="            <td>&nbsp;</td>";
    $this->salida.="            <td class=\"label\">TIPO DOCUMENTO</td>";
		$this->salida .= "		       <td><select name=\"TipoDocumentoResponsable\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoDocumentoResponsable);
		$this->salida .= "          </select></td>";
    $this->salida .= "          </tr>";
		$DocumentoResponsable=$_REQUEST["DocumentoResponsable"];
		$nombreResponsable=$_REQUEST["nombreResponsable"];
		$this->salida.="            <tr>";
    $this->salida.="            <td>&nbsp;</td>";
    $this->salida.="            <td class=\"".$this->SetStyle("DocumentoResponsable")."\">DOCUMENTO</td>";
		$this->salida .= "		      <td><input type=\"text\" class=\"input-text\" name=\"DocumentoResponsable\" maxlength=\"32\" value=\"$DocumentoResponsable\"></td>";
    $this->salida .= "          </tr>";
		$this->salida.="            <tr>";
    $this->salida.="            <td>&nbsp;</td>";
		$this->salida.="            <td class=\"".$this->SetStyle("nombreResponsable")."\">NOMBRE</td>";
    $this->salida .= "		       <td><input type=\"text\" class=\"input-text\" name=\"nombreResponsable\" maxlength=\"32\" value=\"$nombreResponsable\"></td>";
		$this->salida .= "          </tr>";
		$this->salida.="            <tr>";
    $this->salida.="            <td>&nbsp;</td>";
    $this->salida.="            <td class=\"".$this->SetStyle("parentescoResponsable")."\">PARENTESCO</td>";
		$this->salida .= "		      <td><select name=\"parentescoResponsable\" class=\"select\">";
		$parentescos=$this->tiposParentescosPaciente();
		$this->MostrasSelect($parentescos,'False',$parentescoResponsable);
		$this->salida .= "          </select></td>";
    $this->salida.="            </tr>";
    $this->salida.="        </td>";
		$this->salida.="        </tr>";
    $this->salida.="        </table><BR>";
//--------------

		$this->salida.="<table align=\"center\" border=\"0\" width=\"90%\">";
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
		$this->salida.="<td align=\"center\" width=\"100%\"><textarea name=\"observacion".$pfj."\" cols=\"80\" rows=\"5\" style = \"width:100%\" class=\"textarea\">".$_REQUEST["observacion".$pfj.""]."</textarea></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" width=\"100%\"><input type=\"submit\" name=\"guardar_obj$pfj\" value=\"GUARDAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
/*		if($_REQUEST["con".$pfj]<>NULL)
		{*/
			//////////////////////////
			// SISTEMA DE IMPRESION //
			/////////////////////////
			$reporte= new GetReports();
			$mostrar=$reporte->GetJavaReport('system','reportes','concentimiento_html',array('ingreso'=>$this->ingreso, 'evolucion'=>$this->evolucion, 'cuenta'=>$this->cuenta, 'usuario_id'=>$this->usuario_id, 'plan'=>$this->plan, 'servicio'=>$this->servicio, 'tipoidpaciente'=>$this->tipoidpaciente, 'paciente'=>$this->paciente),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
			$nombre_funcion=$reporte->GetJavaFunction();
			$this->salida .=$mostrar;
	
			$this->salida.="<br><br><center>";
			$this->salida.="<label class=\"label_mark\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;&nbsp;IMPRIMIR 	CONCENTIMIENTO</a>";
			$this->salida.="</center>";
	
			//////////////////////////
			// SISTEMA DE IMPRESION //
			/////////////////////////
//		}
			//UNSET($_SESSION['con']);
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
			return true;
		}
	
		function frmHistoria()
		{
			return true;
		}
     
     function frm_Diagnostico_Odontologico_PrimeraVez()
     {
          $pfj=$this->frmPrefijo;
		$areas = $this->Get_AreaEvaluada();
		$diag = $this->ConsultaDiagnostico_PrimeraVez();
		$obsevaciones = $this->ConsultarDescripcion_PrimeraVez();
          
          $this->salida  = ThemeAbrirTablaSubModulo('DIAGNOSTICO ODONTOLOGICO DE PRIMERA CITA');
          if(!empty($diag))
          {
               $accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
               $this->salida.= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
               $this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\" class=\"hc_table_submodulo_list\">";
               
               $this->salida.="<tr class=\"modulo_table_list_title\">";
               $this->salida.="<td align=\"center\" colspan=\"3\" width=\"100%\">DIAGNOSTICO ODONTOLOGICO DE PRIMERA CITA</td>";
               $this->salida.="</tr>";
     
               $this->salida.="<tr class=\"modulo_table_list_title\">";
               $this->salida.="<td align=\"center\" colspan=\"2\" width=\"20%\">AREA EVALUADA</td>";
               $this->salida.="<td align=\"center\" width=\"80%\">DIAGNOSTICOS</td>";
               $this->salida.="</tr>";
     
               for($i=0; $i<sizeof($areas); $i++)
               {
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td align=\"left\" colspan=\"2\" width=\"20%\">".strtoupper($areas[$i][1])."</td>";
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
/**
* Funcion que se encarga de listar los elementos pasados por parametros
* @return array
* @param array codigos y valores que vienen en el arreglo
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los valores del arreglo
* @param string elemento seleccionado en el objeto donde se imprimen los valores
*/
	function Mostrar($arreglo,$Seleccionado='False',$Defecto=''){

	  switch($Seleccionado){
			case 'False':{
			  foreach($arreglo as $value=>$titulo){
					if($value==$Defecto){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
		  }
			case 'True':{
				foreach($arreglo as $value=>$titulo){
					if($value==$Defecto){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
			}
		}
	}
/**
* Funcion que se encarga de listar los nombres de los tipos de origen de una cirugia
* @return array
* @param array codigos y valores de los tipos de origen de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los tipos de origen
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de origen
*/
	function MostrasSelect($arreglo,$Seleccionado='False',$valor=''){
		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($arreglo as $value=>$titulo){
					if($value==$valor){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  foreach($arreglo as $value=>$titulo){
				  if($value==$valor){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

}

?>
