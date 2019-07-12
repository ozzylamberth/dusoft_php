<?php

/**
* Submodulo de ConfirmacionEquiposQuirurgicos (HTML).
*
* Submodulo para manejar los Hallazgos de la Cirugia.
* @author Tizziano Perea <tizzianop@gmail.com>.
* @version 1.0
* @package SIIS
* $Id: hc_ConfirmacionEquiposQuirurgicos_HTML.php,v 1.2 2006/04/03 15:56:03 lorena Exp $
*/

/**
* ConfirmacionEquiposQuirurgicos_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo ConfirmacionEquiposQuirurgicos, se extiende la clase ConfirmacionEquiposQuirurgicos y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/
IncludeClass("ClaseHTML");
class ConfirmacionEquiposQuirurgicos_HTML extends ConfirmacionEquiposQuirurgicos
{

	function ConfirmacionEquiposQuirurgicos_HTML()
	{
	    $this->ConfirmacionEquiposQuirurgicos();//constructor del padre
       	return true;
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
			$this->salida = ThemeAbrirTablaSubModulo('CONFIRMACION DE EQUIPOS UTILIZADOS EN CIRUGIA');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\">";

		if($this->SetStyle("MensajeError")){
			$this->salida.="<table align=\"center\">";
			$this->salida.=$this->SetStyle("MensajeError");
			$this->salida.="</table>";
		}

		$this->salida.="<table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class='modulo_table_title'>";
		$this->salida.="<td align='center'>EQUIPOS PROGRAMADOS</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td align='center' class='hc_submodulo_list_claro'>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';			
		if($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']){
			$this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"6\">EQUIPOS FIJOS</td></tr>";		
			$this->salida .= "    <tr class=\"modulo_table_title\">";
			$this->salida .= "    <td width=\"15%\" nowrap>DEPARTAMENTO</td>";					
			$this->salida .= "    <td width=\"20%\" nowrap>QUIROFANO</td>";				
			$this->salida .= "    <td width=\"20%\" nowrap>TIPO EQUIPO</td>";						
			$this->salida .= "    <td width=\"15%\" nowrap>EQUIPO</td>";
			$this->salida .= "    <td width=\"20%\" nowrap>DURACION (minutos)</td>";					
			$this->salida .= "    <td width=\"8%\" nowrap>&nbsp;</td>";					
			$this->salida .= "    <tr>";			
			foreach($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['equipo'] as $dpto=>$datos){
				foreach($datos as $quirofano=>$datos1){
					foreach($datos1 as $tipoEquipo=>$datos2){
						foreach($datos2 as $equipo=>$nomequipo){				
							$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
							$this->salida .= "    <td>".$dpto."</td>";								
							$this->salida .= "    <td>".$quirofano."</td>";								
							$this->salida .= "    <td>".$tipoEquipo."</td>";								
							$this->salida .= "    <td>".$nomequipo."</td>";															
							$this->salida .= "    <td align=\"center\"><input size=\"3\" type=\"text\" name=\"duracionFijo".$pfj."[".$equipo."]\" value=\"".$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['duracion'][$dpto][$quirofano][$tipoEquipo][$equipo]."\" class=\"input-text\"></td>";	
							$actionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ModificarDatos','EliminarEquipo'.$pfj=>1,"fijo".$pfj=>1,"dpto".$pfj=>$dpto,"quirofano".$pfj=>$quirofano,"tipoEquipo".$pfj=>$tipoEquipo,"equipo".$pfj=>$equipo));							
							$this->salida .= "    <td><a href=\"$actionElim\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";	
							$this->salida .= "    </tr>";
							$y++;
						}
					}
				}			
			}			
			$this->salida .= "	  </table></BR>";
		}
		if($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']){
			$this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"5\">EQUIPOS MOVILES</td></tr>";		
			$this->salida .= "    <tr class=\"modulo_table_title\">";
			$this->salida .= "    <td width=\"15%\" nowrap>DEPARTAMENTO</td>";								
			$this->salida .= "    <td width=\"20%\" nowrap>TIPO EQUIPO</td>";						
			$this->salida .= "    <td width=\"15%\" nowrap>EQUIPO</td>";
			$this->salida .= "    <td width=\"20%\" nowrap>DURACION (minutos)</td>";					
			$this->salida .= "    <td width=\"8%\" nowrap>&nbsp;</td>";					
			$this->salida .= "    <tr>";			
			foreach($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['equipo'] as $dpto=>$datos){
				foreach($datos as $tipoEquipo=>$datos1){
					foreach($datos1 as $equipo=>$nomequipo){										
						$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
						$this->salida .= "    <td>".$dpto."</td>";														
						$this->salida .= "    <td>".$tipoEquipo."</td>";								
						$this->salida .= "    <td>".$nomequipo."</td>";							
						$this->salida .= "    <td align=\"center\"><input size=\"3\" type=\"text\" name=\"duracionMovil".$pfj."[".$equipo."]\" value=\"".$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['duracion'][$dpto][$tipoEquipo][$equipo]."\" class=\"input-text\"></td>";							
						$actionElim=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'ModificarDatos','EliminarEquipo'.$pfj=>1,"fijo".$pfj=>2,"dpto".$pfj=>$dpto,"tipoEquipo".$pfj=>$tipoEquipo,"equipo".$pfj=>$equipo));							
						$this->salida .= "    <td><a href=\"$actionElim\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";	
						$this->salida .= "    </tr>";
						$y++;
					}
				}							
			}		
			$this->salida .= "	  </table>";			
		}
		$this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\">";
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'BuscadorEquipos'));
		$this->salida .= "    <tr><td align=\"right\" class=\"label\"><a href=\"$action\">Seleccionar Equipo Quirugico</a></td></tr>";		
		$this->salida .= "	  </table>";
		
		$this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\">";		
		$this->salida .= "    <tr><td align=\"center\" class=\"label\"><input type=\"submit\" name=\"Insertar\" value=\"INSERTAR\" class=\"input-submit\"></td></tr>";		
		$this->salida .= "	  </table>";
		
		$this->salida.="</form>";		
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
	
	function Forma_Seleccion_EquiposQX($tipoEquipo,$Quirofano,$Departamento,$descripcionEquipo){
		
		$pfj=$this->frmPrefijo;
		$this->paginaActual = 1;
    $this->offset = 0;
		$this->salida .= ThemeAbrirTabla('EQUIPOS QUIRURGICOS');		
		$this->salida .= "<script>";
    $this->salida .= "function SeleccionQuiro(frm,valor){";
    $this->salida .= "  if(valor=='F'){";
    $this->salida .= "    frm.Quirofano.disabled=false;";
		$this->salida .= "  }else{";
    $this->salida .= "    frm.Quirofano.disabled=true;";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .= "</script>";		
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'BuscadorEquipos'));
		$this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"6\">BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">TIPO EQUIPO</td>";
		$this->salida.="<td width=\"10%\" align = left >";
		$this->salida.="<select size = 1 name = \"tipoEquipo$pfj\"  class =\"select\" onchange=\"SeleccionQuiro(this.form,this.value)\">";
		if($tipoEquipo==-1){
      $selected='selected';
		}elseif(($tipoEquipo=='M')){
      $selected1='selected';
		}elseif(($tipoEquipo=='F')){
      $selected2='selected';
		}
    $this->salida.="<option value = '-1' $selected>Todos</option>";
		$this->salida.="<option value = 'M' $selected1>Movil</option>";
		$this->salida.="<option value = 'F' $selected2>Fijo</option>";
		$this->salida.="</select>";
		$this->salida.="</td>";
    if($tipoEquipo!='F'){
      $disable='disabled';
		}
		$this->salida.="<td width=\"5%\">QUIROFANO</td>";
		$this->salida.="<td width=\"10%\" align = left >";
		$this->salida.="<select size = 1 name = \"Quirofano$pfj\"  class =\"select\" $disable>";
		$this->salida.="<option value = '-1' >Todos</option>";
		$quiros = $this->TiposQuirofanosTotal();
		for($i=0;$i<sizeof($quiros);$i++){
			if($Quirofano!= $quiros[$i]['quirofano']){
				$this->salida.="<option value = ".$quiros[$i]['quirofano'].">".$quiros[$i]['descripcion']."</option>";
			}else{
				$this->salida.="<option value = ".$quiros[$i]['quirofano']." selected >".$quiros[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="<td width=\"5%\">DEPARTAMENTO</td>";
		$this->salida.="<td width=\"10%\" align = left >";
		$this->salida.="<select size = 1 name = \"Departamento$pfj\"  class =\"select\">";
		$this->salida.="<option value = '-1' >Todos</option>";
		$Dptos = $this->TotalDepartamentos();		
		for($i=0;$i<sizeof($Dptos);$i++){
			if($Departamento!= $Dptos[$i]['departamento']){
				$this->salida.="<option value = ".$Dptos[$i]['departamento'].">".$Dptos[$i]['descripcion']."</option>";
			}else{
				$this->salida.="<option value = ".$Dptos[$i]['departamento']." selected >".$Dptos[$i]['descripcion']."</option>";
			}
		}
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"10%\">DESCRIPCION</td>";
		$this->salida .="<td width=\"25%\" colspan=\"2\" align='center'><input type='text' class='input-text' name = \"descripcionEquipo$pfj\"   value =\"$descripcionEquipo\"></td>" ;
		$this->salida .= "<td  colspan=\"3\" width=\"6%\" align=\"center\">";
		$this->salida .= "	<input class=\"input-submit\" name=\"Filtrar$pfj\" type=\"submit\" value=\"FILTRAR\">";
		$this->salida .= "	<input class=\"input-submit\" name=\"Volver$pfj\" type=\"submit\" value=\"VOLVER\">";
		$this->salida .= "</td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";		
		$vector=$this->BusquedaEquiposQX($tipoEquipo,$Quirofano,$Departamento,$descripcionEquipo);
		if($vector){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
      $this->salida.="  <td >TIPO</td>";
			$this->salida.="  <td>DEPARTAMENTO</td>";
			$this->salida.="  <td>DESCRIPCION</td>";			
			$this->salida.="  <td width=\"5%\">&nbsp;</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector);$i++){
				if($i % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				if($vector[$i]['fijo']=='1'){
				$Fijo='F';
				$this->salida.="  <td>FIJO</td>";	
				}else{
				$Fijo='M';
				$this->salida.="  <td>MOVIL</td>";	
				}				
				$this->salida.="  <td>".$vector[$i]['nom_departamento']."</td>";
				$this->salida.="  <td>".$vector[$i]['nom_equipo']."</td>";				
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'SeleccionarEquipos',"dpto".$pfj=>$vector[$i]['nom_departamento'],
				"nom_equipo".$pfj=>$vector[$i]['nom_equipo'],"equipo".$pfj=>$vector[$i]['equipo_id'],"fijo".$pfj=>$Fijo,"quirofano".$pfj=>$vector[$i]['quirofano'],"tipoEquipoVec".$pfj=>$vector[$i]['tipo_equipo']));
				$this->salida.="  <td align=\"center\"><a href=\"$accion\"><img title=\"Seleccion Equipo\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
				$this->salida.="</tr>";
			}			
			$this->salida.="</table>";				
			$Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'BuscadorEquipos','tipoEquipo'.$pfj=>$tipoEquipo,'Quirofano'.$pfj=>$Quirofano,'Departamento'.$pfj=>$Departamento,"descripcionEquipo".$pfj=>$descripcionEquipo));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);				
		}
		$this->salida .= "</form>";		
		$this->salida .= ThemeCerrarTabla();
		return true;
 }
 
 function frmConsulta(){
 
  	$pfj=$this->frmPrefijo;
		$this->EquiposConfimadosFijos();
		$this->EquiposComfimadosMoviles();
		if(empty ($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']) && empty($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']))
		{
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA REPORTES DE CONFIRMACION DE EQUIPOS<br><br>";
			$this->salida .="</tr>";
			$this->salida .="</table>";
		}else{
			$this->salida.="<table width=\"60%\" border=\"0\" align=\"center\">";
			$this->salida.="<table width=\"90%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr class='modulo_table_title'>";
			$this->salida.="<td align='center'>EQUIPOS PROGRAMADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida.="<td align='center' class='hc_submodulo_list_claro'>";
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
			$this->salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';			
			if($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']){
				$this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
				$this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"6\">EQUIPOS FIJOS</td></tr>";		
				$this->salida .= "    <tr class=\"modulo_table_title\">";
				$this->salida .= "    <td width=\"15%\" nowrap>DEPARTAMENTO</td>";					
				$this->salida .= "    <td width=\"20%\" nowrap>QUIROFANO</td>";				
				$this->salida .= "    <td width=\"20%\" nowrap>TIPO EQUIPO</td>";						
				$this->salida .= "    <td width=\"15%\" nowrap>EQUIPO</td>";
				$this->salida .= "    <td width=\"20%\" nowrap>DURACION (minutos)</td>";									
				$this->salida .= "    <tr>";			
				foreach($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['equipo'] as $dpto=>$datos){
					foreach($datos as $quirofano=>$datos1){
						foreach($datos1 as $tipoEquipo=>$datos2){
							foreach($datos2 as $equipo=>$nomequipo){				
								$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
								$this->salida .= "    <td>".$dpto."</td>";								
								$this->salida .= "    <td>".$quirofano."</td>";								
								$this->salida .= "    <td>".$tipoEquipo."</td>";								
								$this->salida .= "    <td>".$nomequipo."</td>";															
								$this->salida .= "    <td>".$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['duracion'][$dpto][$quirofano][$tipoEquipo][$equipo]."</td>";									
								$this->salida .= "    </tr>";
								$y++;
							}
						}
					}			
				}			
				$this->salida .= "	  </table></BR>";
			}
			if($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']){
				$this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
				$this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"5\">EQUIPOS MOVILES</td></tr>";		
				$this->salida .= "    <tr class=\"modulo_table_title\">";
				$this->salida .= "    <td width=\"15%\" nowrap>DEPARTAMENTO</td>";								
				$this->salida .= "    <td width=\"20%\" nowrap>TIPO EQUIPO</td>";						
				$this->salida .= "    <td width=\"15%\" nowrap>EQUIPO</td>";
				$this->salida .= "    <td width=\"20%\" nowrap>DURACION (minutos)</td>";									
				$this->salida .= "    <tr>";			
				foreach($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['equipo'] as $dpto=>$datos){
					foreach($datos as $tipoEquipo=>$datos1){
						foreach($datos1 as $equipo=>$nomequipo){										
							$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
							$this->salida .= "    <td>".$dpto."</td>";														
							$this->salida .= "    <td>".$tipoEquipo."</td>";								
							$this->salida .= "    <td>".$nomequipo."</td>";							
							$this->salida .= "    <td align=\"center\">".$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['duracion'][$dpto][$tipoEquipo][$equipo]."</td>";														
							$this->salida .= "    </tr>";
							$y++;
						}
					}							
				}		
				$this->salida .= "	  </table>";			
			}		
		}	
		$this->salida.="</form>";		
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
	
	function frmHistoria(){
 
  	$pfj=$this->frmPrefijo;
		$this->EquiposConfimadosFijos();
		$this->EquiposComfimadosMoviles();
		if(empty ($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']) && empty($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']))
		{
			return false;
		}else{
			$salida.="<table width=\"60%\" border=\"0\" align=\"center\">";
			$salida.="<table width=\"90%\" border=\"0\" align=\"center\">";
			$salida.="<tr class='modulo_table_title'>";
			$salida.="<td align='center'>EQUIPOS PROGRAMADOS</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td align='center' class='hc_submodulo_list_claro'>";
			$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
			$salida.='<form name="forma'.$pfj.'" action="'.$accion.'" method="post">';			
			if($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']){
				$salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
				$salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"6\">EQUIPOS FIJOS</td></tr>";		
				$salida .= "    <tr class=\"modulo_table_title\">";
				$salida .= "    <td width=\"15%\" nowrap>DEPARTAMENTO</td>";					
				$salida .= "    <td width=\"20%\" nowrap>QUIROFANO</td>";				
				$salida .= "    <td width=\"20%\" nowrap>TIPO EQUIPO</td>";						
				$salida .= "    <td width=\"15%\" nowrap>EQUIPO</td>";
				$salida .= "    <td width=\"20%\" nowrap>DURACION (minutos)</td>";									
				$salida .= "    <tr>";			
				foreach($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['equipo'] as $dpto=>$datos){
					foreach($datos as $quirofano=>$datos1){
						foreach($datos1 as $tipoEquipo=>$datos2){
							foreach($datos2 as $equipo=>$nomequipo){				
								$salida .= "    <tr class=\"modulo_list_oscuro\">";
								$salida .= "    <td>".$dpto."</td>";								
								$salida .= "    <td>".$quirofano."</td>";								
								$salida .= "    <td>".$tipoEquipo."</td>";								
								$salida .= "    <td>".$nomequipo."</td>";															
								$salida .= "    <td>".$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['duracion'][$dpto][$quirofano][$tipoEquipo][$equipo]."</td>";									
								$salida .= "    </tr>";
								$y++;
							}
						}
					}			
				}			
				$salida .= "	  </table></BR>";
			}
			if($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']){
				$salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
				$salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"5\">EQUIPOS MOVILES</td></tr>";		
				$salida .= "    <tr class=\"modulo_table_title\">";
				$salida .= "    <td width=\"15%\" nowrap>DEPARTAMENTO</td>";								
				$salida .= "    <td width=\"20%\" nowrap>TIPO EQUIPO</td>";						
				$salida .= "    <td width=\"15%\" nowrap>EQUIPO</td>";
				$salida .= "    <td width=\"20%\" nowrap>DURACION (minutos)</td>";									
				$salida .= "    <tr>";			
				foreach($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['equipo'] as $dpto=>$datos){
					foreach($datos as $tipoEquipo=>$datos1){
						foreach($datos1 as $equipo=>$nomequipo){										
							$salida .= "    <tr class=\"modulo_list_oscuro\">";
							$salida .= "    <td>".$dpto."</td>";														
							$salida .= "    <td>".$tipoEquipo."</td>";								
							$salida .= "    <td>".$nomequipo."</td>";							
							$salida .= "    <td>".$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['duracion'][$dpto][$tipoEquipo][$equipo]."</td>";														
							$salida .= "    </tr>";
							$y++;
						}
					}							
				}		
				$salida .= "	  </table>";			
			}		
		}	
		$salida.="</form>";		
		$salida.="</td>";
		$salida.="</tr>";
		$salida.="</table>";		
		return $salida;
	}

}

?>
