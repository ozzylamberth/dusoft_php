<?php

/**
 * $Id: app_AsignacionCitasTipoPlan_userclasses_HTML.php,v 1.2 2008/05/28 15:15:54 juanpablo Exp $
 */
IncludeClass("ClaseHTML");
class app_AsignacionCitasTipoPlan_userclasses_HTML extends app_AsignacionCitasTipoPlan_user
{

	
	function app_AsignacionCitasTipoPlan_userclasses_HTML()
	{
          $this->salida='';
          $this->app_AsignacionCitasTipoPlan_user();
          return true;
	}


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
	  
	function FormaTiposPlanesCitas($msg,$offset,$sql="")
	{	
		SessionDelVar('sqlcomplemento');
		$this->paginaActual = 1;
    		if($offset > 0){
			$this->offset = $offset;
			$this->paginaActual = ($offset / $this->limit)+1;
			
		}
		else{
			$this->offset = 0;
		}
   		if($_REQUEST['offset']){
      			$this->paginaActual = intval($_REQUEST['offset']);
      			if($this->paginaActual > 1){
        			$this->offset = ($this->paginaActual - 1) * ($this->limit);
			}
   		}
		
		if($_REQUEST['buscar']){
				if($_REQUEST['TipoDocumento']=='Cedula'){
					if(trim($_REQUEST['busqueda'])!=""){
						$sqlcomplemento= "a.tipo_id_tercero='CC' AND a.tercero_id = '".trim($_REQUEST['busqueda'])."' AND ";
						SessionSetVar('sqlcomplemento',$sqlcomplemento);
					}	
					else{
						$sqlcomplemento= "";	
						SessionSetVar('sqlcomplemento',$sqlcomplemento);
					}	
				}
				if($_REQUEST['TipoDocumento']=='Nombre'){
					if(trim($_REQUEST['busqueda'])!=""){
						$sqlcomplemento= "UPPER(a.nombre) ILIKE ('%".trim($_REQUEST['busqueda'])."%') AND ";
						SessionSetVar('sqlcomplemento',$sqlcomplemento);
					}	
					else{
						$sqlcomplemento= "";	
						SessionSetVar('sqlcomplemento',$sqlcomplemento);
					}	
				}
		}
		else
		{
			if($sql!="")
				$sqlcomplemento= $sql;
		}
		
		$tipos_planes = $this->GetTiposPlanes();
 		$this->salida.= ThemeAbrirTabla('CONFIGURACION CITAS POR PROFESIONAL');
		$action=ModuloGetURL('app','AsignacionCitasTipoPlan','user','GuardarCitasTiposPlanes');
		$i=$this->offset;
				$mostrar ="\n<script language='javascript'>\n";
				$mostrar.="function mOvr(src,clrOver) {;\n";
				$mostrar.="src.style.background = clrOver;\n";
				$mostrar.="}\n";

				$mostrar.="function mOut(src,clrIn) {\n";
				$mostrar.="src.style.background = clrIn;\n";
				$mostrar.="}\n";
				
				$mostrar.="function DefaultCitas(frm,id,a,b,c,d){\n";
				$mostrar.="if(formaguardar.elements[a].value=='' || formaguardar.elements[a].value < 0 || formaguardar.elements[a].value > 15)";
				$mostrar.="{alert('VERIFIQUE EL VALOR [0-15] ');formaguardar.elements[a].focus();return false;}";
				$mostrar.="if(formaguardar.elements[b].value=='' || formaguardar.elements[b].value < 0 || formaguardar.elements[b].value > 15)";
				$mostrar.="{alert('VERIFIQUE EL VALOR [0-15] ');formaguardar.elements[b].focus();return false;}";
				$mostrar.="if(formaguardar.elements[c].value=='' || formaguardar.elements[c].value < 0 || formaguardar.elements[c].value > 15)";
				$mostrar.="{alert('VERIFIQUE EL VALOR [0-15] ');formaguardar.elements[c].focus();return false;}";
				$mostrar.="if(formaguardar.elements[d].value=='' || formaguardar.elements[d].value < 0 || formaguardar.elements[d].value > 15)";
				$mostrar.="{alert('VERIFIQUE EL VALOR [0-15] ');formaguardar.elements[d].focus();return false;}";
				
				$mostrar.="url= '$action&profesional='+id+'&c1='+formaguardar.elements[a].value+'&c2='+formaguardar.elements[b].value+'&c3='+formaguardar.elements[c].value+'&c4='+formaguardar.elements[d].value;";
				$mostrar.="frm.action= url;";
				$mostrar.="frm.submit();";
				$mostrar.="}";
				
				$mostrar.="function DefaultCitas1(frm,a,b,c,d){\n";
				$mostrar.="if(formaguardar.elements[a].value=='' || formaguardar.elements[a].value < 0 || formaguardar.elements[a].value > 15)";
				$mostrar.="{alert('VERIFIQUE EL VALOR [0-15] ');formaguardar.elements[a].focus();return false;}";
				$mostrar.="if(formaguardar.elements[b].value=='' || formaguardar.elements[b].value < 0 || formaguardar.elements[b].value > 15)";
				$mostrar.="{alert('VERIFIQUE EL VALOR [0-15] ');formaguardar.elements[b].focus();return false;}";
				$mostrar.="if(formaguardar.elements[c].value=='' || formaguardar.elements[c].value < 0 || formaguardar.elements[c].value > 15)";
				$mostrar.="{alert('VERIFIQUE EL VALOR [0-15] ');formaguardar.elements[c].focus();return false;}";
				$mostrar.="if(formaguardar.elements[d].value=='' || formaguardar.elements[d].value < 0 || formaguardar.elements[d].value > 15)";
				$mostrar.="{alert('VERIFIQUE EL VALOR [0-15] ');formaguardar.elements[d].focus();return false;}";
				$mostrar.="url= '$action&DefaultAll=1&c1='+formaguardar.elements[a].value+'&c2='+formaguardar.elements[b].value+'&c3='+formaguardar.elements[c].value+'&c4='+formaguardar.elements[d].value;";
				$mostrar.="if(confirm('Desea Establecer estos valores predeterminados para los profesionales?')){";
				$mostrar.="frm.action= url;";
				$mostrar.="frm.submit();}";
				$mostrar.="}";
				
				$mostrar.="</script>";
				
				
				$this->salida .="$mostrar";
		
		$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
		
		$this->salida.="<br><center><label class =\"label_mark\">$msg</label></center><br>";
		$accion=ModuloGetURL('app','AsignacionCitasTipoPlan','user','FormaTiposPlanesCitas');
		$this->salida .= "<form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO PROFESIONALES</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">TIPO</td>";
		$this->salida.="<td width=\"10%\" align = left >";
		
		//////////////////////////////////////////////////////////
		$this->salida .= "<select name=\"TipoDocumento\" class=\"select\">\n";
		if(!empty($_REQUEST['TipoDocumento']))
		{
			if($_REQUEST['TipoDocumento'] == 'Cedula'){
				$this->salida.="<option value='Cedula' selected>C�ula</option>";
				$this->salida.="<option value='Nombre'>Nombre</option>";
			}
			if($_REQUEST['TipoDocumento'] == 'Nombre'){
				$this->salida.="<option value='Nombre' selected>Nombre</option>";
				$this->salida.="<option value='Cedula'>C�ula</option>";
			}	
		}
		else{
			$this->salida.="<option value='Cedula'>C�ula</option>";
			$this->salida.="<option value='Nombre'>Nombre</option>";
		}
		$this->salida .= "</select>\n";
		$this->salida.="</td>";
		
				
		/////////////////////////////////////////////////////////
		
		
		$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
		$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' name = 'busqueda'  size=\"40\" maxlength=\"40\"  value ='".$_REQUEST['busqueda']."'></td>" ;
		$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
				if($_REQUEST['busqueda'])
				{
					$cadena="El Buscador Avanzado: realiz�la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
				}
				else
				{
					$cadena="Buscador Avanzado: Busqueda de todos los usuarios";
				}
				$this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
				$this->salida.="</tr>";
		$this->salida.="</form>";
		$this->salida.="</table>";
		
		$this->salida .= "<table cellspacing=\"1\"  cellpadding=\"0\" border=\"0\" width=\"99%\" align=\"center\" >";
		$this->salida .= "<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "<td ROWSPAN=2>TIPO</td>";
		$this->salida .= "<td ROWSPAN=2>DOCUMENTO</td>";
		$this->salida .= "<td ROWSPAN=2>NOMBRE PROFESIONAL</td>";
		$this->salida .= "<td ROWSPAN=2>DEPARTAMENTO</td>";
		$this->salida .= "<td COLSPAN=4>CANTIDAD DE PACIENTES POR TIPO PLAN	</td></tr>";
		$this->salida .= "<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "<td>".$tipos_planes[1][0]."</td>";
		$this->salida .= "<td>".$tipos_planes[1][1]."</td>";
		$this->salida .= "<td>".$tipos_planes[1][2]."</td>";
		$this->salida .= "<td>".$tipos_planes[1][3]."</td><td>&nbsp;</td></tr><br>";
		$this->salida .= "<tr><td colspan=\"9\">&nbsp;</td></tr>";
		
		
		$profesionales=$this->ConsultaProfesionales($sqlcomplemento);
		
		if(empty($profesionales)){
			
			$this->salida .= "<tr><td class=\"label_error\" align=\"center\" colspan=\"9\">NO SE ENCONTR�NINGUN REGISTRO</td></tr>";
			$this->salida .= "</table><br>";
		}
		else{   
			$CiTiPl = $this->GetDefaultCitasTiposPlanes();
			
			
			$this->salida .= "<form name=\"formaguardar\" method=\"POST\">";
			$this->salida .= "<tr align=\"center\"><td colspan=\"4\" aling=\"right\"><font family=\"sans_serif, sans_serif, Verdana, helvetica, Arial;\" size=\"1.5px\" color=\"#000545\">APLICAR VALORES A TODOS LOS PROFESIONALES</font></td>";
			$this->salida .= "<td><input type=\"text\"  name='".$tipos_planes[0][0]."' size=\"1\" maxlength=\"2\" value='".$CiTiPl[0][1]."'></td>";
			$this->salida .= "<td><input type=\"text\"  name='".$tipos_planes[0][1]."' size=\"1\" maxlength=\"2\" value='".$CiTiPl[1][1]."'></td>";
			$this->salida .= "<td><input type=\"text\"  name='".$tipos_planes[0][2]."' size=\"1\" maxlength=\"2\" value='".$CiTiPl[2][1]."'></td>";
			$this->salida .= "<td><input type=\"text\"  name='".$tipos_planes[0][3]."' size=\"1\" maxlength=\"2\" value='".$CiTiPl[3][1]."'></td>";
			$this->salida .= "<input type=\"hidden\" name=\"offset1\" value='".$this->offset."'>";
			$this->salida .= "<td><input class=\"input-submit\" type=\"button\" value=\"Todos\" name=\"base\" title=\"Aplicar Todos\" OnClick=DefaultCitas1(this.form,'".$tipos_planes[0][0]."','".$tipos_planes[0][2]."','".$tipos_planes[0][1]."','".$tipos_planes[0][3]."');></td></tr>";
			
			
			$y=0;
			$i=$this->offset;
			$aux=0;
			while(!$profesionales->EOF){
				if($aux==0){$aux=$profesionales->fields[1];}
				if($aux!=$profesionales->fields[1])
				{
					$aux=$profesionales->fields[1];
					$y++;
				}
				if($y % 2){
			  		$estilo='modulo_list_claro';
				}else{
			  		$estilo='modulo_list_oscuro';
				}
				$id_profe= $profesionales->fields[0]."-".$profesionales->fields[1]."-".$profesionales->fields[3];
				$text1= $id_profe."-".$tipos_planes[0][0];
				$text2= $id_profe."-".$tipos_planes[0][1];
				$text3= $id_profe."-".$tipos_planes[0][2];
				$text4= $id_profe."-".$tipos_planes[0][3];
				$this->salida .= "<tr height='25' class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#FFFFFF'); align=\"center\">";	
				$this->salida .= "<td>".$profesionales->fields[0]."</td>";
				$this->salida .= "<td>".$profesionales->fields[1]."</td>";
				$this->salida .= "<td>".$profesionales->fields[2]."</td>";
				$this->salida .= "<td>".$profesionales->fields[4]."</td>";
				
				$consulta =$this->GetCitasTiposPlanes_ProfesionalDpto($profesionales->fields[0],$profesionales->fields[1],$profesionales->fields[3]);
				
				if($consulta){
					
					$this->salida .= "<td><input type=\"text\"  name='".trim($text1)."' size=\"1\" maxlength=\"2\" value='".$consulta->fields[2]."'></td>";
					$consulta->MoveNext();
					$this->salida .= "<td><input type=\"text\"  name='".trim($text2)."' size=\"1\" maxlength=\"2\" value='".$consulta->fields[2]."'></td>";
					$consulta->MoveNext();
					$this->salida .= "<td><input type=\"text\"  name='".trim($text3)."' size=\"1\" maxlength=\"2\" value='".$consulta->fields[2]."'></td>";
					$consulta->MoveNext();
					$this->salida .= "<td><input type=\"text\"  name='".trim($text4)."' size=\"1\" maxlength=\"2\" value='".$consulta->fields[2]."'></td>";
					$consulta->MoveNext();
					$consulta->Close();
				}
				else{
					$this->salida .= "<td><input type=\"text\"  name='".trim($text1)."' size=\"1\" maxlength=\"2\" value=0></td>";
					$this->salida .= "<td><input type=\"text\"  name='".trim($text2)."' size=\"1\" maxlength=\"2\" value=0></td>";
					$this->salida .= "<td><input type=\"text\"  name='".trim($text3)."' size=\"1\" maxlength=\"2\" value=0></td>";
					$this->salida .= "<td><input type=\"text\"  name='".trim($text4)."' size=\"1\" maxlength=\"2\" value=0></td>";
				}
				
				
				$this->salida .= "<td><input class=\"input-submit\" type=\"button\" value=\"...\" name=\"guardar\" title=\"Modificar Registro\" OnClick=DefaultCitas(this.form,'".$id_profe."','".$text1."','".$text2."','".$text3."','".$text4."');></td>";
				$this->salida .= "</tr>";
				
				$profesionales->MoveNext();
				$i++;
				
			}
			$this->salida .= "<input type=\"hidden\" name=\"offset1\" value='".$this->offset."'>";
			$this->salida .= "</form>";
			$profesionales->Close();
			$this->salida .= "</tr></table><br>";
			$Paginador = new ClaseHTML();
			$conteo = SessionGetvar('conteo');
			$this->actionPaginador=ModuloGetURL('app','AsignacionCitasTipoPlan','user','FormaTiposPlanesCitas');
			$this->salida .= $Paginador->ObtenerPaginado($conteo,$this->paginaActual,$this->actionPaginador);
		

		}
		
		
		$action = ModuloGetURL('system','Menu');
		$this->salida.="<form name=\"formavolver2\" method=\"POST\" action=\"$action\">";
		$this->salida.="		<center><input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></center>";
		$this->salida.="</form>";
		
		$this->salida.=ThemecerrarTabla();
		return true;
	}	
	

}//fin clase

?>

