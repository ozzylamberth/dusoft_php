<?php

/**
* Submodulo de NotasAnestesiologia (HTML).
*
* Submodulo para manejar los suministros de anestesia a los pacientes.
* @author Tizziano Perea <tizzianop@gmail.com>.
* @version 1.0
* @package SIIS
* $Id: hc_NotasAnestesiologia_HTML.php,v 1.3 2006/12/19 21:00:13 jgomez Exp $
*/

/**
* NotasAnestesiologia_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo NotasAnestesiologia, se extiende la clase NotasAnestesiologia y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class NotasAnestesiologia_HTML extends NotasAnestesiologia
{

	function NotasAnestesiologia_HTML()
	{
	    $this->NotasAnestesiologia();//constructor del padre
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

	function frmConsulta()
	{
		$pfj=$this->frmPrefijo;
		$datosAnestesia = $this->Detalle_anestesiologia();
		if (empty ($datosAnestesia))
		{
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<div class='label_mark' align='center'>&nbsp;<br>";
			$this->salida .="</tr>";
			$this->salida .="</table>";
		}
		else
  		{
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$this->salida .="<tr class=\"modulo_table_list_title\">";
			$this->salida .="<td align=\"center\"colspan=\"2\">CARACTERISTICAS DE LA ANESTESIA</td>";
			$this->salida .="</tr>";

			$this->salida .="<tr>";
			$this->salida .="<td class=\"hc_table_submodulo_list_title\">EVOLUCION  FECHA</td>";
			$this->salida .="<td class=\"hc_table_submodulo_list_title\" align=\"center\">DESCRIPCION DEL SUMINISTRO</td>";
			$this->salida .="</tr>";

			$spy=0;
			foreach($datosAnestesia as $k=>$v)
			{
                    $this->salida.="<tr class=\"hc_submodulo_list_claro\">";
				
                    $fecha_registro = $this->PartirFecha($v[fecha_registro]);
				$this->salida .="<td width='10%' align='center'><b>".$v[evolucion_id]."</b><br>".$fecha_registro."</td>";

				$this->salida .="<td><table border='1' width='100%'>";

                    $this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .="<td align='center'><b>TIPO DE ANESTESIA</b></td>";
                    $this->salida .="<td align='center'><b>GAS ANESTESICO</b></td>";
                    $this->salida .="<td align='center'><b>GAS MEDICINAL</b></td>";
                    $this->salida .="<td align='center'><b>MINUTOS SUMISTRO</b></td>";
                    $this->salida .="</tr>";
                    
                    $this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
                    $this->salida .="<td>".$v[tipo_anestesia]."</td>";
                    if(empty($v[gas_anestecia])) $v[gas_anestecia] = "No Suministrado";
                    $this->salida .="<td>".$v[gas_anestecia]."</td>";
                    if(empty($v[gas_medico])) $v[gas_medico] = "No Suministrado";
                    $this->salida .="<td>".$v[gas_medico]."</td>";
                    if(empty($v[minutos_suministro])) $v[minutos_suministro] = "0";
                    $this->salida .="<td>".$v[minutos_suministro]." Min.</td>";
                    $this->salida .="</tr>";
                    
                    $vector = $this->ReconocerProfesional($v[usuario_id]);
                    $this->salida .="<tr>";
                    $this->salida .="<td class=\"hc_table_submodulo_list_title\">PROFESIONAL:</td>";
                    $this->salida .="<td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$vector[usuario]." - ".$vector[nombre]."</td>";
                    $this->salida .="</tr>";
				
                    $this->salida .="</table><BR>";
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
		$datosAnestesia = $this->Detalle_anestesiologia();
		if (empty ($datosAnestesia))
		{
			$salida .="<br><table width=\"100%\" border=\"0\" align=\"center\">";
			$salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida .="<div class='label_mark' align='center'>&nbsp;<br>";
			$salida .="</tr>";
			$salida .="</table>";
		}
		else
  		{
			$salida .="<br><table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida .="<tr class=\"modulo_table_list_title\">";
			$salida .="<td align=\"center\"colspan=\"2\">SUMINISTROS DE ANESTESIA</td>";
			$salida .="</tr>";

			$salida .="<tr>";
			$salida .="<td class=\"hc_table_submodulo_list_title\" align='center'>EVOLUCION  FECHA</td>";
			$salida .="<td class=\"hc_table_submodulo_list_title\" align=\"center\">DESCRIPCION DEL SUMINISTRO</td>";
			$salida .="</tr>";

			$spy=0;
			foreach($datosAnestesia as $k=>$v)
			{
                    $salida.="<tr class=\"hc_submodulo_list_claro\">";
				
                    $fecha_registro = $this->PartirFecha($v[fecha_registro]);
				$salida .="<td width='10%' align='center'><b>".$v[evolucion_id]."</b><br>".$fecha_registro."</td>";

				$salida .="<td><table border='0' width='100%'>";

                    $salida .="<tr class=\"hc_table_submodulo_list_title\">";
                    $salida .="<td align='center'><b>TIPO DE ANESTESIA</b></td>";
                    $salida .="<td align='center'><b>GAS ANESTESICO</b></td>";
                    $salida .="<td align='center'><b>GAS MEDICINAL</b></td>";
                    $salida .="<td align='center'><b>MINUTOS SUMISTRO</b></td>";
                    $salida .="</tr>";
                    
                    $salida .="<tr class=\"hc_submodulo_list_oscuro\">";
                    $salida .="<td align='center'>".$v[tipo_anestesia]."</td>";
                    if(empty($v[gas_anestecia])) $v[gas_anestecia] = "No Suministrado";
                    $salida .="<td align='center'>".$v[gas_anestecia]."</td>";
                    if(empty($v[gas_medico])) $v[gas_medico] = "No Suministrado";
                    $salida .="<td align='center'>".$v[gas_medico]."</td>";
                    if(empty($v[minutos_suministro])) $v[minutos_suministro] = "0";
                    $salida .="<td align='center'>".$v[minutos_suministro]." Min.</td>";
                    $salida .="</tr>";
                    
                    $vector = $this->ReconocerProfesional($v[usuario_id]);
                    $salida .="<tr>";
                    $salida .="<td class=\"hc_table_submodulo_list_title\">PROFESIONAL:</td>";
                    $salida .="<td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$vector[usuario]." - ".$vector[nombre]."</td>";
                    $salida .="</tr>";
				
                    $salida .="</table>";
				$salida .="</td>";
				$salida .="</tr>";
			}
			$salida.="</table><br>";
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
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('NOTAS DE ANESTESIOLOGIA');
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
		
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
          $this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';
          
          $this->salida.="<script language='javascript'>\n";
		$this->salida.="  function desabilita(frm,valor){";
		$this->salida.="    cadena=valor.split('/');";
		$this->salida.="    if(cadena[1]==0 || valor==-1){";
		$this->salida.="  		frm.gasAnestesico.disabled=true;\n";
		$this->salida.="  		frm.gasAnestesicoMe.disabled=true;\n";
		$this->salida.="  		frm.DuracionGas.disabled=true;\n";
		$this->salida.="  		frm.nogas.value='0';\n";
		$this->salida.="  	}else{\n";
		$this->salida.="  		frm.gasAnestesico.disabled=false;\n";
		$this->salida.="  		frm.gasAnestesicoMe.disabled=false;\n";
		$this->salida.="  		frm.DuracionGas.disabled=false;\n";
		$this->salida.="  		frm.nogas.value='1';\n";
		$this->salida.="  	}\n";
		$this->salida.="  }\n";
		$this->salida.="</script>\n";

          $this->salida .= "<table border=\"1\" width=\"100%\" align=\"center\">";
		$this->salida .= "<tr><td colspan=\"4\" class=\"modulo_table_list_title\">GASES ANESTESICOS</td></tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\">";
		$this->salida .= "<td width=\"10%\" nowrap class=\"".$this->SetStyle("TipoAnestesia")."\">TIPO ANESTESIA</td>";
		$this->salida .= "<td width=\"20%\" nowrap><select onchange=\"desabilita(this.form,this.value)\" name=\"TipoAnestesia\" onchange=\"desabilita(this.form,this.value)\" class=\"select\">";
		$this->salida .= "<option value=\"-1\" selected>---seleccione---</option>";
          $TiposAnestesias=$this->TiposDeAnestesias();
	     for($i=0;$i<sizeof($TiposAnestesias);$i++)
          {
     	     $value=$TiposAnestesias[$i]['qx_tipo_anestesia_id'].'/'.$TiposAnestesias[$i]['sw_uso_gases'];
               $titulo=$TiposAnestesias[$i]['descripcion'];
               if($value==$_SESSION['Liquidacion_QX']['TIPO_ANESTESIA']){
                    $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
               }else{
                    $this->salida .="     <option value=\"$value\">$titulo</option>";
               }
          }
     	$this->salida .= "</select></td>";
		$this->salida .= "<td>";
		
          if(empty($_SESSION['Liquidacion_QX']['TIPO_ANESTESIA']) || $_SESSION['Liquidacion_QX']['NO_GAS']!='1')
          {
		     $desabilitar='disabled';
		}
		$this->salida .= "<input type=\"hidden\" name=\"nogas\" value=\"".$_SESSION['Liquidacion_QX']['NO_GAS']."\">";
		$this->salida .= "<BR><table width=\"100%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "<td class=\"".$this->SetStyle("gasAnestesico")."\">GAS ANESTESICO</td>";
		$this->salida .= "<td><select name=\"gasAnestesico\" class=\"select\" $desabilitar>";
		$this->salida .= "<option value=\"-1\" selected>---seleccione---</option>";
          $TipoGases=$this->TiposGasesAnestesicos('A');
		foreach($TipoGases as $value=>$titulo)
          {
			if($value==$_SESSION['Liquidacion_QX']['GAS_ANESTESICO']){
				$this->salida .="     <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .="     <option value=\"$value\">$titulo</option>";
			}
		}
     	$this->salida .= "</select></td>";
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "<td class=\"".$this->SetStyle("gasAnestesicoMe")."\">GAS MEDICINAL</td>";
		$this->salida .= "<td><select name=\"gasAnestesicoMe\" class=\"select\" $desabilitar>";
		$this->salida .= "<option value=\"-1\" selected>---seleccione---</option>";
     	$TipoGases=$this->TiposGasesAnestesicos('M');
		foreach($TipoGases as $value=>$titulo)
          {
			if($value==$_SESSION['Liquidacion_QX']['GAS_ANESTESICO_ME']){
				$this->salida .="     <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .="     <option value=\"$value\">$titulo</option>";
			}
		}
     	$this->salida .= "</select></td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("DuracionGas")."\">MINUTOS SUMISTRO GAS</td>";
		$this->salida .= "<td><input size=\"4\" type=\"text\" class=\"input-text\" name=\"DuracionGas\" value=\"".$_SESSION['Liquidacion_QX']['DURACION_GAS']."\" $desabilitar></td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "</td>";
          $this->salida .="<td align='center' class='hc_submodulo_list_claro'>";
		$this->salida .="<input type=\"submit\" value=\"Insertar\" class=\"input-submit\">";
		$this->salida .="</td>";
          $this->salida .= "</tr>";
     	$this->salida .= "</table><BR>";
		$this->salida .="</form>";
          $this->frmConsulta();
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

}

?>
