<?php
/**
* @package IPSOFT-SIIS
*
* @author    Mauricio Bejarano L. 
* @version   $Revision: 1.5 $
* $Id: app_Os_Mantenimiento_Apoyod_userclasses_HTML.php,v 1.5 2008/05/07 19:19:18 cahenao Exp $
* @package   Mantenimiento_Apoyod
* 
*
*/


class app_Os_Mantenimiento_Apoyod_userclasses_HTML extends app_Os_Mantenimiento_Apoyod_user
{
	//Constructor de la clase app_LiquidacionPrecios_userclasses_HTML
	function app_Os_Mantenimiento_Apoyod_userclasses_HTML()
	{
							$this->salida='';
							return true;
	}

	//aoltu
	function SetStyle($campo)
	{
			if ($this->frmError[$campo] || $campo=="MensajeError")
			{
					if ($campo=="MensajeError")
					{
							$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
							return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
			}
			return ("label");
	}

		/**
		*
		*/
		function Menu(){
			$this->salida= ThemeAbrirTablaSubModulo('MANTENIMIENTO CARGOS APOYO DIAGNOSTICO','200');
				$accionAdicion=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','Consultar_Cargos',array('accion'=>'adiciona'));
				$accionModifica=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','Consultar_Cargos',array('accion'=>'modifica'));
				$this->salida.="<table  align=\"center\" border=\"1\"  width=\"200\" class=\"modulo_table_list\">";
				$this->salida.="	<tr class=\"formulacion_table_list\">";
				$this->salida.=" 		<td align=\"center\" width=\"200\">MENU MANTENIMIENTO</td>";
				$this->salida.="	</tr>";
				$this->salida.="	<tr class=\"modulo_list_claro\">";
				$this->salida.="		<form name=\"forma\" action=\"$accionAdicion\" method=\"post\">";
				$this->salida.="		<td align=\"center\" width=\"20%\"><input class=\"input-submit\" name=\"adiciona\" type=\"submit\" value=\"ADICIONA CARGO\"></td>";
				$this->salida.="	</form>";
				$this->salida.="	</tr>";
				$this->salida.="	<tr class=\"modulo_list_claro\">";
				$this->salida.="		<form name=\"forma\" action=\"$accionModifica\" method=\"post\">";
				$this->salida.="		<td align=\"center\" width=\"20%\"><input class=\"input-submit\" name=\"modifica\" type=\"submit\" value=\"MODIFICA PLANTILLA\"></td>";
				$this->salida.="	</form>";
				$this->salida.="	</tr>";
				$this->salida.="</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}

    function Consultar_Cargos($vector='',$acc=''){		
      if(empty($acc)){
			$acc=$_REQUEST['accion'];
      }
      $cargo=$_REQUEST['cargo'];
      $tecnica=$_REQUEST['tecnica'];
      $descripcion=$_REQUEST['descripcion'];
     if($acc=='adiciona'){
        $titulo='ADICION CARGOS APOYO DIAGNOSTICO';
      }else{
        $titulo='MANTENIMIENTO CARGOS APOYO DIAGNOSTICO';
      }
      $this->salida= ThemeAbrirTablaSubModulo($titulo);
      if($acc=='adiciona'){
			//$accion=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','BuscaCargosCups',array('accion'=>$acc));
        $accion=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','BuscaCargosApoyod',array('accion'=>$acc));
      }else{
        $accion=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','BuscaCargosApoyod',array('accion'=>$acc));
      }
      $this->salida.=" <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\">";
      $this->salida.=$this->SetStyle("MensajeError");
      $this->salida.="	</table>";
		
      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida.="	<tr class=\"formulacion_table_list\">";
      $this->salida.=" 		<td colspan = 10 align=\"center\" width=\"100%\">BUSQUEDA DE CARGOS</td>";
      $this->salida.="	</tr>";
		
      $this->salida.="	<tr class=\"formulacion_table_list\">";
      $this->salida.="		<td >CARGOS APOYO DIAGNOSTICO: </td>";
      $this->salida.="		<td >CARGOS: </td>";
      $this->salida.="		<td><input type=\"text\" class=\"input-text\" name=\"cargo\" maxlength=\"10\" value = ".$cargo."></td>";
      $this->salida.="		<td >DESCRIPCION: </td>";
      $this->salida.="		<td><input type=\"text\" class=\"input-text\" name=\"descripcion\" maxlength=\"32\" value = ".$descripcion."></td>";
      $this->salida.="		<td align=\"right\"  width=\"13%\"><input class=\"input-submit\" name=\"forma\" type=\"submit\" value=\"BUSCAR\"></td>";
      $this->salida.="	</tr>";
		
      $this->salida.="	</table>";
      $this->salida.="</form>";
		
      if(!empty($vector)){
        $accionSel=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','SeleccionarCargos',array('accion'=>$acc));//'vector'=>$vector,
		
        $this->salida.="<form name=\"forma\" action=\"$accionSel\" method=\"post\">";
        $this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\">";
        $this->salida.=$this->SetStyle("MensajeError");
        $this->salida.="	</table>";
			
        $this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\">";
        $this->salida.="	<tr class=\"formulacion_table_list\">";
        $this->salida.=" 		<td colspan = 10 align=\"center\" width=\"100%\">RESULTADO BUSQUEDA</td>";
        $this->salida.="	</tr>";
        $this->salida.="	<tr class=\"formulacion_table_list\" align=\"center\">";
        $this->salida.="		<td width=\"10%\" align=\"center\">CARGOS : </td>";
        $this->salida.="		<td width=\"80%\" align=\"center\">DESCRIPCION : </td>";
        $this->salida.="		<td width=\"10%\" align=\"center\">OPCION : </td>";
        $this->salida.="	</tr>";
        for($i=0;$i<sizeof($vector);$i++)
        {
          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}
            $descripcion_small= substr ($vector[$i]['descripcion'],0,90);
            $this->salida.="<tr class='$estilo' align='center'>";
            $this->salida.="	<td width=\"10%\" align=\"center\">".$vector[$i]['cargo']."</td>";
            $this->salida.="	<td width=\"80%\" align=\"left\" title =\"".$vector[$i]['descripcion']."\">".$descripcion_small."</td>";
            $this->salida.="	<td width=\"10%\" align=\"center\"><input type = radio name= 'seleccion' value = ".$vector[$i]['cargo']."></td>";
            $this->salida.="<tr>";
        }
        $this->salida.="<tr >";
        $this->salida .= "<td align=\"right\"  width=\"13%\" colspan=\"3\"><input class=\"input-submit\" name=\"forma\" type=\"submit\" value=\"SELECCIONAR\"></td>";
        $this->salida.="</tr>";
        $this->salida.="	</table>";
        $this->salida.="</form>";
      }else{
        $this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
        $this->salida.="	<tr class=\"formulacion_table_list\">";
        $this->salida.=" 		<td colspan = 10 align=\"center\" width=\"100%\">EN CARGO BUSCADO YA PERTENESE A LA TABLA APOYOD_CARGOS</td>";
        $this->salida.="	</tr>";
        $this->salida.="</table>";
        }
       $accionV=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','Menu');
       $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
       $this->salida.="	<tr class=\"modulo_list_claro\">";
       $this->salida.="	<form name=\"forma\" action=\"$accionV\" method=\"post\">";
       $this->salida.="		<td align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
       $this->salida.="	</tr>";
       $this->salida.="</table>"; 
       $this->salida .= ThemeCerrarTabla();
      return true;
	}//fin function Consultar_Cumplimiento

	/**
	*
	*/
	function FmEdicion($cargo='')
	{  
    IncludeFile("app_modules/Os_Mantenimiento_Apoyod/RemoteXajax/Platillas.php");
    $this->SetXajax(array("PruebaF","EliminarOpciones","ActualizarP")); 
		
    if(empty($cargo)){
			$cargo_apd=$_REQUEST['cargo'];
		}else{
			$cargo_apd=$cargo;
		}
		//$tecnica_apd=$_REQUEST['tecnica'];
		$plantilla_apd=$_REQUEST['plantilla'];

    
		$descripcion=$this->ConsultaDescripcion($cargo_apd);
		$descripcion_small= substr ($descripcion,0,50);
		$this->salida= ThemeAbrirTablaSubModulo('MANTENIMIENTO CARGOS APOYO DIAGNOSTICO');
		$accion=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','ActualizarDatos',array('cargo_apd'=>$cargo_apd,'tecnica_apd'=>$tecnica_apd,'plantilla_apd'=>$plantilla_apd));
		
    $this->salida .= "<script>\n";
      $this->salida .= "  function Continuar()\n";
      $this->salida .= "  {\n";
      $this->salida .= "      document.forma.action = '".$accion."';\n";
      $this->salida .= "      document.forma.submit();\n";
      $this->salida .= "  }\n";
      $this->salida .= "  function EvaluarDatos()\n";
      $this->salida .= "  {\n";
      $this->salida .= "    xajax_IngresarLabPlantilla(xajax.getFormValues('forma'));\n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
  		
    
		$this->salida .= "<form name=\"forma\" id=\"forma\" action=\"".$accion."\" method=\"post\">";
		$this->salida .= "  <input type=hidden name='cargo' id ='cargo' value='".$cargo_apd."'>";
		$this->salida .= "  <script>";
		$this->salida .= "  function filtrotecnica(valor)"."\n";
		$this->salida .= '    {'."\n";
		$accion2=ModuloGetUrl('app','Os_Mantenimiento_Apoyod','user','FmEdicion');
		//$this->salida.='    window.location.href="'.$accion2.'&tecnica="+valor+"&cargo="+document.getElementById("cargo").value+"&plantilla="+document.getElementById("plantilla").value';
		$this->salida.='    window.location.href="'.$accion2.'&cargo="+document.getElementById("cargo").value+"&plantilla="+valor';
		$this->salida.=' ;'."\n";
		$this->salida.=' }'."\n";
		$this->salida.= "</script>";
		
		$this->salida .= "  <script>";
		$this->salida.=  "  function filtroplantilla(valor)"."\n";
		$this->salida.='    {'."\n";
		$accion2=ModuloGetUrl('app','Os_Mantenimiento_Apoyod','user','FmEdicion');
		$this->salida.='    window.location.href="'.$accion2.'&plantilla="+valor+"&cargo="+document.getElementById("cargo").value+"&tecnica="+document.getElementById("tecnica").value';
		$this->salida.=' ;'."\n";
		$this->salida.=' }'."\n";
		$this->salida.= "</script>";
		
		$this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="	</table>";
		//$this->salida.="	</br>";
		
		$this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\"  class=\"modulo_table_list\">";
		$this->salida.="		<tr class=\"formulacion_table_list\">";
		$this->salida.=" 			<td colspan = 4 align=\"center\" width=\"100%\">EDICION DE CARGOS - TECNICAS</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr  align=\"center\">";
		$this->salida.="			<td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">CARGOS : </td>";
		$this->salida.="			<td class=\"modulo_list_oscuro\" width=\"20%\" align=\"center\">".$cargo_apd."</td>";
		$this->salida.="			<td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">DESCRIPCION : </td>";
		$this->salida.="			<td class=\"modulo_list_oscuro\" width=\"40%\" align=\"center\" title=\"$descripcion\">".$descripcion_small."</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"formulacion_table_list\">";
		$this->salida.=" 			<td colspan = 4 align=\"center\" width=\"100%\">SELECCION DE TECNICA</td>";
		$this->salida.="		</tr>";
		
		//consulta tecnica
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "			<td class=\"formulacion_table_list\" width=\"10%\" align=\"center\">TECNICA DEL CARGO :</td>";
		$this->salida .= "			<td class=\"modulo_list_oscuro\" align=\"center\" colspan=\"3\" >\n";
    $this->salida .= "        <select name=\"tecnica\" id=\"tecnica\" class=\"select\" onchange=\"filtrotecnica(this.value)\">";
		$Tecnicas=$this->BuscaTecnicaCargo($cargo_apd);
		$this->salida .= " 				  <option value=\"\" selected>--</option>\n";
		for($i=0;$i<sizeof($Tecnicas);$i++)
    {
			if($Tecnicas[$i][tecnica_id])
			{
					$nom_tecnica=$Tecnicas[$i]['nombre_tecnica'];
					$nom_tecnica_small=substr ($nom_tecnica,0,50);
					if($Tecnicas[$i]['apoyod_cargos_tecnicas_id']."||//".$Tecnicas[$i]['lab_examen_id']."||//".$Tecnicas[$i]['tecnica_id']."||//".$Tecnicas[$i]['lab_plantilla_id']==$_REQUEST['plantilla'])
          {
						$this->salida .="		<option value=\"".$Tecnicas[$i]['apoyod_cargos_tecnicas_id']."||//".$Tecnicas[$i]['lab_examen_id']."||//".$Tecnicas[$i]['tecnica_id']."||//".$Tecnicas[$i]['lab_plantilla_id']."\" selected>".$Tecnicas[$i]['tecnica_id']."-".$nom_tecnica_small."</option>";
					}else
          {
						$this->salida .="		<option value=\"".$Tecnicas[$i]['apoyod_cargos_tecnicas_id']."||//".$Tecnicas[$i]['lab_examen_id']."||//".$Tecnicas[$i]['tecnica_id']."||//".$Tecnicas[$i]['lab_plantilla_id']."\">".$Tecnicas[$i]['tecnica_id']."-".$nom_tecnica_small."</option>";
					}
			}
		}
		$this->salida .= "        </select>";
		$this->salida .= "			  <input type=\"hidden\" name=\"plantilla\" id=\"plantilla\">";
		$this->salida .= "			</td>\n";
		$this->salida .= "		</tr>\n";
		
		$plantilla_apd=$_REQUEST['plantilla'];
    $mdl = AutoCarga::factory("PlantillasHTML","views","app","Os_Mantenimiento_Apoyod");  
		if(isset($plantilla_apd))
    {
			$resultado=$this->ConsultaPlantilla($cargo_apd,$tecnica_apd,$plantilla_apd);
      $datos = $resultado['vector'];
			$dat = explode("||//",$plantilla_apd);
			$plantilla_apd=$dat[3];
			
      if(!$plantilla_apd)
			{
				$plantilla_apd = '1';
			}
			
      $this->salida .= "    <tr>\n";
			$this->salida .= "			<td colspan=\"4\">\n";
			switch($plantilla_apd)
      {
				case '1':{
										$this->salida.="	<input type=hidden name='caso' value='1'>";
										$this->salida.="	<input type=hidden name='cargo' value='".$cargo_apd."'>";
										$this->salida.="	<table border=\"0\" align=\"center\"  width=\"100%\" class=\"modulo_table_list\">";
										$this->salida.="	<tr class=\"formulacion_table_list\" align=\"center\">";
										$this->salida.="		<td width=\"5%\" align=\"center\">SEXO</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">LAB EXAMEN</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">EDAD MIN</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">EDAD MAX</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">CARGO</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">TECNICA</td>";
										$this->salida.="		<td width=\"15%\" align=\"center\">RANGO MIN</td>";
										$this->salida.="		<td width=\"15%\" align=\"center\">RANGO MAX</td>";
										$this->salida.="		<td width=\"15%\" align=\"center\">UNIDADES</td>";
										$this->salida.="	</tr>";
										for($i=0;$i<sizeof($datos);$i++){
											if( $i % 2){ $estilo='modulo_list_claro';}
											else {$estilo='modulo_list_oscuro';}
											$this->salida.="	<tr class='$estilo' align=\"center\">";
											$this->salida.="		<td width=\"5%\" align=\"center\">".$datos[$i]['sexo_id']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[$i]['lab_examen_id']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[$i]['edad_min']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[$i]['edad_max']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[$i]['cargo']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[$i]['tecnica_id']."</td>";
											$this->salida.="		<td><input type=\"text\" class=\"input-text\" name='datos[".$i."][rango_min]' maxlength=\"10\" value = ".$datos[$i]['rango_min']."></td>";
											$this->salida.="		<td><input type=\"text\" class=\"input-text\" name='datos[".$i."][rango_max]' maxlength=\"10\" value = ".$datos[$i]['rango_max']."></td>";
											$this->salida.="		<td><input type=\"text\" class=\"input-text\" name='datos[".$i."][unidades]' maxlength=\"10\" value = ".$datos[$i]['unidades']."></td>";
											$this->salida.="		<input type=hidden name='datos[".$i."][sexo_id]' value='".$datos[$i]['sexo_id']."'>";
											$this->salida.="		<input type=hidden name='datos[".$i."][lab_examen_id]' value='".$datos[$i]['lab_examen_id']."'>";
											$this->salida.="		<input type=hidden name='datos[".$i."][edad_min]' value='".$datos[$i]['edad_min']."'>";
											$this->salida.="		<input type=hidden name='datos[".$i."][edad_max]' value='".$datos[$i]['edad_max']."'>";
											$this->salida.="		<input type=hidden name='datos[".$i."][cargo]' value='".$datos[$i]['cargo']."'>";
											$this->salida.="		<input type=hidden name='datos[".$i."][tecnica_id]' value='".$datos[$i]['tecnica_id']."'>";
                      $this->salida.="	</tr>\n";
                    }
										$this->salida.="	</table>";
				break;
				}
				case '2':{
              $this->IncludeJS("CrossBrowser");
              $this->IncludeJS("CrossBrowserEvent");
              $this->IncludeJS("CrossBrowserDrag");
              $this->salida .= $mdl->ListaSubexamenes($datos,$resultado['opciones']);
              $this->salida .= "		  </td>";
              $this->salida .= "		</tr>";
              $this->salida .= "	</table>";
              $this->salida .= $mdl->FormaConvensiones();              
              $this->salida .="  </form>";
          		$accionV=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','Menu');
          		$this->salida .="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          		$this->salida .="	<tr class=\"modulo_list_claro\">";
          		$this->salida .="	<form name=\"forma\" action=\"$accionV\" method=\"post\">";
          		$this->salida .="		<td align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
          		$this->salida .="	</tr>";
          		$this->salida .="</table>";
          		$this->salida .= ThemeCerrarTabla();
              return true;
				break;
				}
				case '3':{
										$this->salida.="	<input type=hidden name='caso' value='3'>";
										$this->salida.="	<input type=hidden name='cargo' value='".$cargo_apd."'>";
										$this->salida.="	<table border=\"0\" align=\"center\"  width=\"100%\" class=\"modulo_table_list\">";
										$this->salida.="		<tr class=\"formulacion_table_list\" align=\"center\">";
										$this->salida.="			<td class=\"modulo_table_title\" width=\"15%\" align=\"center\">LAB EXAMEN</td>";
										$this->salida.="			<td class=\"modulo_list_claro\" width=\"15%\" align=\"left\">".$datos[0]['lab_examen_id']."</td>";
										$this->salida.="			<td class=\"formulacion_table_list\" width=\"15%\" align=\"center\">CARGO</td>";
										$this->salida.="			<td class=\"modulo_list_claro\" width=\"15%\" align=\"center\">".$datos[0]['cargo']."</td>";
										$this->salida.="			<td class=\"formulacion_table_list\" width=\"15%\" align=\"center\">TECNICA</td>";
										$this->salida.="			<td class=\"modulo_list_claro\" width=\"15%\" align=\"center\">".$datos[0]['tecnica_id']."</td>";
										$this->salida.="		</tr>";
										$this->salida.="		<tr>";
										$this->salida.="			<td colspan = \"6\" align=\"center\" width=\"60%\">";
										$this->salida.=getFckeditor("datos[0][detalle]",'300',"100%",$datos[0]['detalle']);
										$this->salida.="			</td>";
										$this->salida.="		</tr>";
										$this->salida.="		<input type=hidden name='datos[0][lab_examen_id]' value='".$datos[0]['lab_examen_id']."'>";
										$this->salida.="		<input type=hidden name='datos[0][cargo]' value='".$datos[0]['cargo']."'>";
										$this->salida.="		<input type=hidden name='datos[0][tecnica_id]' value='".$datos[0]['tecnica_id']."'>";
										$this->salida.="	</table>";
				break;
				}
			}
 			$this->salida .= "		  </td>";
      $this->salida .= "		</tr>";
 			//texto para las normalidades
			$this->salida .= "		<tr align=\"center\" class=\"modulo_list_claro\">";
			$this->salida .= "			<td colspan=\"1\" class=\"NORMAL_10\">Texto para las Normalidades";
			$this->salida .= "			</td>";
			$this->salida .= "			<td colspan=\"6\">";
			$this->salida .= "				<textarea name=\"normalidades\" rows=\"8\" cols=\"50\" >".$datos[0]['normalidades']."</textarea>"; 
			$this->salida .= "			</td>";
			$this->salida .= "		</tr>";
			//FIN texto para las normalidades
			$this->salida .= "		<tr align=\"right\" class=\"modulo_list_claro\">";
			$this->salida .= "			<td colspan=\"4\">\n";
      $this->salida .= "        <input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"ALMACENAR CAMBIOS\">\n";
      $this->salida .= "      </td>\n";
			$this->salida .= "		</tr>\n";
		}
    $this->salida.="	</table>";
    $this->salida.= $mdl->FormaConvensiones();
		$this->salida.="  </form>";	
		
		//BOTON DE VOLVER
		$accionV=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','Menu');
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="	<tr class=\"modulo_list_claro\">";
		$this->salida.="	<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida.="		<td align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="	</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }
	
		
	/**
	*
	*/
	function FmSeleccionaTecnica($cargo){
		$this->salida= ThemeAbrirTablaSubModulo('ADICION TECNICA A UN CARGO DE APOYOD');
		$accion=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','ControlAdicion',array('paso'=>'sub_examen'));
		$vector=$this->ConsultaCargosTegnica($cargo);

		$this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\">";
		$this->salida.=$this->SetStyle("MensajeError");
		$this->salida.="	</table>";
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida.="	<input type=hidden name='datos[cargo]' value='".$cargo."'>";
		$this->salida.="	<input type=hidden name='accion' value='selecciona'>";
		$this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\" class=\"modulo_table_list\">";
		$this->salida.="	<tr class=\"formulacion_table_list\">";
		$this->salida.=" 		<td colspan = 5 align=\"center\" width=\"100%\">CARGOS - TECNICAS EXISTENTES</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"formulacion_table_list\" align=\"center\">";
		$this->salida.="		<td width=\"10%\" align=\"center\">TECNICA*</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">CARGO*</td>";
		$this->salida.="		<td width=\"60%\" align=\"center\">NOMBRE TECNICA</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">PREDETERMINADO</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">OPCION</td>";
		$this->salida.="	</tr>";
		for($i=0;$i<sizeof($vector);$i++){
			if( $i % 2){ $estilo='modulo_list_claro';}
			else {$estilo='modulo_list_oscuro';}
			$this->salida.="	<tr class='$estilo' align=\"left\">";
			$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['tecnica_id']."</td>";
			$this->salida.="		<td width=\"10%\" align=\"center\">".$cargo."</td>";
			$this->salida.="		<td width=\"60%\" align=\"left\">".$vector[$i]['nombre_tecnica']."</td>";
			$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['sw_predeterminado']."</td>";
			$this->salida.="		<td width=\"10%\" align=\"center\"><input type = radio name= 'datos[tecnica]' value = ".$vector[$i]['tecnica_id']."||//".$vector[$i]['apoyod_cargos_tecnicas_id']."></td>";
			//$this->salida.="		<td width=\"10%\" align=\"center\"><input type = radio name= 'datos[tecnica]' value = ".$vector[$i]['tecnica_id']."></td>";
			$this->salida.="	</tr>";
		}
		
		$this->salida.="	<tr class=\"modulo_list_claro\" align=\"right\">";
		$this->salida.="		<td align=\"right\"  width=\"13%\" colspan=\"5\"><input class=\"input-submit\" name=\"forma\" type=\"submit\" value=\"SELECCIONAR\"></td>";
		$this->salida.="	</tr>";
		$this->salida.="	</table>";
		$this->salida.="</form>";

		//formulario que adiciona una nueva cargo-tecnica
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida.="	<input type=hidden name='datos[cargo]' value='".$cargo."'>";
		$this->salida.="	<input type=hidden name='accion' value='adiciona_cargo_tecnica'>";
		$this->salida.="	<input type=hidden name='datos[apoyod_cargos_tecnicas_id]' value='".$vector[0]['apoyod_cargos_tecnicas_id']."'>";
		$this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\" class=\"modulo_table_list\">";
		$this->salida.="	<tr class=\"formulacion_table_list\">";
		$this->salida.=" 		<td colspan = 4 align=\"center\" width=\"100%\">ADICIONAR CARGOS - TECNICAS</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"formulacion_table_list\" align=\"center\">";
		$this->salida.="		<td width=\"10%\" align=\"center\">TECNICA</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">CARGO</td>";
		$this->salida.="		<td width=\"60%\" align=\"center\">NOMBRE TECNICA</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">PREDETERMINADO</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class='modulo_list_claro' align=\"left\">";
		$this->salida.="			<td width=\"10%\" class=\"modulo_list_oscuro\" align=\"left\" title = ''><select name=\"datos[tecnica]\" class=\"select\">";
		$this->salida.=" 				<option value=\"-1\">--</option>";
		$this->salida.=" 				<option value=\"1\" >1-Generico</option>";
		$this->salida.=" 				<option value=\"2\" >2-Normal</option>";
		$this->salida.=" 				<option value=\"3\" >3-Datalab</option>";
		$this->salida.=" 				<option value=\"4\" >4-Vitros</option>";
		$this->salida.="				</select>";
		$this->salida.="			</td>";
		$this->salida.="			<td width=\"10%\" align=\"center\">".$cargo."</td>";
		$this->salida.="			<td width=\"60%\"><input  width=\"100%\" type=\"text\" class=\"input-text\" name='datos[nombre_tecnica]' maxlength=\"90\"></td>";
		$this->salida.="			<td width=\"10%\"><input type=\"text\" class=\"input-text\" name='datos[sw_predeterminado]' maxlength=\"1\"></td>";
		$this->salida.="		</tr>";
		$this->salida.="	<tr class=\"modulo_list_claro\" align=\"right\">";
		$this->salida.="		<td align=\"right\"  width=\"13%\" colspan=\"4\"><input class=\"input-submit\" name=\"forma\" type=\"submit\" value=\"ADICIONAR\"></td>";
		$this->salida.="	</tr>";
		$this->salida.="	</table>";
		$this->salida.="</form>";
		//BOTON DE VOLVER
		$accionV=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','Menu');
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="	<tr class=\"modulo_list_claro\">";
		$this->salida.="	<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida.="		<td align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="	</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	/**
	*
	*/
	function FmSeleccionaSubExamen($datos){
		$this->salida= ThemeAbrirTablaSubModulo('ADICION O SELECCION DE SUBEXAMENES');
		$accion=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','ControlAdicion',array('paso'=>'plantilla'));
		$vector=$this->ConsultaLabExamenes($datos);

		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida.="	<input type=hidden name='accion' value='selecciona'>";
		$this->salida.="	<input type=hidden name='datos[cargo]' value='".$datos[cargo]."'>";
		$this->salida.="	<input type=hidden name='datos[tecnica]' value='".$datos[tecnica]."'>";
		$this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\" class=\"modulo_table_list\">";
		$this->salida.="	<tr class=\"formulacion_table_list\">";
		$this->salida.=" 		<td colspan = 6 align=\"center\" width=\"100%\">SELECCION DE SUB EXAMENES</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"formulacion_table_list\" align=\"center\">";
		$this->salida.="		<td width=\"10%\" align=\"center\">TECNICA*</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">CARGO*</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">LAB EXAMEN*</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">ORDEN</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">PLANTILLA</td>";
		$this->salida.="		<td width=\"40%\" align=\"center\">DESCRIPCION</td>";
		//$this->salida.="		<td width=\"40%\" align=\"center\">OPCION</td>";
		$this->salida.="	</tr>";
		for($i=0;$i<sizeof($vector);$i++){
			if( $i % 2){ $estilo='modulo_list_claro';}
			else {$estilo='modulo_list_oscuro';}
			$this->salida.="	<tr class='$estilo' align=\"left\">";
			$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['tecnica_id']."</td>";
			$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['cargo']."</td>";
			$this->salida.="		<td width=\"60%\" align=\"center\">".$vector[$i]['lab_examen_id']."</td>";
			$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['indice_de_orden']."</td>";
			$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['lab_plantilla_id']."</td>";
			$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['nombre_examen']."</td>";
			//$this->salida.="		<td width=\"10%\" align=\"center\"><input type = radio name= 'datos[lab_examen]' value = ".$vector[$i]['lab_plantilla_id']."></td>";
			$this->salida.="	</tr>";
		}
// 		$this->salida.="	<tr class=\"modulo_list_claro\" align=\"right\">";
// 		$this->salida.="		<td align=\"right\"  width=\"13%\" colspan=\"7\"><input class=\"input-submit\" name=\"forma\" type=\"submit\" value=\"SELECCIONAR\"></td>";
// 		$this->salida.="	</tr>";
		$this->salida.="	</table>";
		$this->salida.="</form>";
		
		$Lab_examen=$this->CreaLabExamen($datos[cargo],$datos[tecnica]);
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida.="	<input type=hidden name='accion' value='adiciona_lab_examen'>";
		$this->salida.="	<input type=hidden name='datos[cargo]' value='".$datos[cargo]."'>";
		$this->salida.="	<input type=hidden name='datos[tecnica]' value='".$datos[tecnica]."||//".$datos[apoyod_cargos_tecnicas_id]."'>";
		//$this->salida.="	<input type=hidden name='datos[apoyod_cargos_tecnicas_id]' value='".$datos[apoyod_cargos_tecnicas_id]."'>";
		$this->salida.="	<input type=hidden name='datos[lab_examen]' value='".$Lab_examen."'>";
		$this->salida.="	<table border=\"0\" align=\"center\"  width=\"80%\" class=\"modulo_table_list\">";
		$this->salida.="	<tr class=\"formulacion_table_list\">";
		$this->salida.=" 		<td colspan = 6 align=\"center\" width=\"100%\">ADICIONAR NUEVO SUB EXAMEN</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"formulacion_table_list\" align=\"center\">";
		$this->salida.="		<td width=\"10%\" align=\"center\">TECNICA*</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">CARGO*</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">LAB EXAMEN*</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">ORDEN</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">PLANTILLA</td>";
		$this->salida.="		<td width=\"40%\" align=\"center\">DESCRIPCION</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"modulo_list_claro\" align=\"center\">";
		$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[tecnica]."</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[cargo]."</td>";
		$this->salida.="		<td width=\"10%\" align=\"center\">".$Lab_examen."</td>";
		$this->salida.="		<td width=\"10%\"><input  width=\"10%\" type=\"text\" class=\"input-text\" name='datos[orden]' maxlength=\"2\"></td>";
		$this->salida.="		<td width=\"10%\" class=\"modulo_list_oscuro\" align=\"left\" title = ''><select name=\"datos[plantilla]\" class=\"select\">";
		$this->salida.=" 			<option value=\"-1\">--</option>";
		$this->salida.=" 			<option value=\"1\" >Plantilla 1</option>";
		$this->salida.=" 			<option value=\"2\" >Plantilla 2</option>";
		$this->salida.=" 			<option value=\"3\" >Plantilla 3</option>";
		$this->salida.="			</select>";
		$this->salida.="		</td>";
		$this->salida.="		<td width=\"10%\"><input  width=\"10%\" type=\"text\" class=\"input-text\" name='datos[nom_examen]' maxlength=\"90\"></td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"modulo_list_claro\" align=\"right\">";
		$this->salida.="		<td align=\"right\"  width=\"13%\" colspan=\"7\"><input class=\"input-submit\" name=\"forma\" type=\"submit\" value=\"ADICION SUB_EXAMEN\"></td>";
		$this->salida.="	</tr>";
		$this->salida.="	</table>";
		$this->salida.="</form>";
				//BOTON DE VOLVER
		$accionV=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','Menu');
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="	<tr class=\"modulo_list_claro\">";
		$this->salida.="	<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida.="		<td align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="	</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
		/**
  	*
  	*/
  	function FmAdicionaPlantilla($datos,$rst2)
    {
  		// echo "<br> en plantilla ->";PRINT_R($datos);
      IncludeFile("app_modules/Os_Mantenimiento_Apoyod/RemoteXajax/Platillas.php");
      $this->SetXajax(array("IngresarLabPlantilla")); 

  		$descripcion=$this->ConsultaDescripcion($datos[cargo]);
      $descripcion_small= substr ($descripcion,0,50);
  		$this->salida= ThemeAbrirTablaSubModulo('ADICION PLANTILLAS CARGOS APOYO DIAGNOSTICO');
  		$accion=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','AdicionarDatos');
  		
      $this->salida .= "<script>\n";
      $this->salida .= "  function Continuar(frm)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    frm.action = '".$accion."';\n";
      $this->salida .= "    frm.submit();\n";
      $this->salida .= "  }\n";
      $this->salida .= "  function EvaluarDatos()\n";
      $this->salida .= "  {\n";
      $this->salida .= "    xajax_IngresarLabPlantilla(xajax.getFormValues('FormaPlantilla'));\n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
  		$this->salida .= "<form name=\"FormaPlantilla\" id=\"FormaPlantilla\" action=\"javascript:EvaluarDatos()\" method=\"post\">";
  		$this->salida .= "	<input type=hidden name='accion' value='adiciona_lab_examen'>";
  		$this->salida .= "	<input type=hidden name='datos[cargo]' value='".$datos[cargo]."'>";
  		$this->salida .= "	<input type=hidden name='datos[tecnica]' value='".$datos[tecnica]."'>";
  		$this->salida .= "	<input type=hidden name='datos[lab_examen]' value='".$datos[lab_examen]."'>";
  		$this->salida .= "	<input type=hidden name='datos[plantilla]' value='".$datos[plantilla]."'>";
  		$this->salida .= "	<center>\n";
  		$this->salida .= "	  <div id=\"error\"></div>\n";
  		$this->salida .= "	</center>\n";
  		$this->salida .= "	<table border=\"0\" align=\"center\"  width=\"80%\"  class=\"modulo_table_list\">";
  		$this->salida .= "		<tr class=\"formulacion_table_list\">";
  		$this->salida .= " 			<td colspan = 4 align=\"center\" width=\"100%\">ADICION PLANTILLAS</td>";
  		$this->salida .= "		</tr>";
  		$this->salida .= "		<tr  align=\"center\">";
  		$this->salida .= "			<td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">CARGOS : </td>";
  		$this->salida .= "			<td class=\"modulo_list_oscuro\" width=\"20%\" align=\"center\">".$datos[cargo]."</td>";
  		$this->salida .= "			<td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">DESCRIPCION : </td>";
  		$this->salida .= "			<td class=\"modulo_list_oscuro\" width=\"40%\" align=\"center\" title=\"$descripcion_small\">".$descripcion_small."</td>";
  		$this->salida .= "		</tr>";
		
			$this->salida.="		<tr>";
			$this->salida.="			<td colspan=\"4\">";
			$vector=$this->ConsultaPlantilla($datos[cargo],$datos[tecnica],$datos[plantilla],$modificar=true);
			switch($datos[plantilla]){
				case '1':{
										$this->salida.="	<input type=hidden name='caso' value='1'>";
										$this->salida.="	<input type=hidden name='cargo' value='".$datos[cargo]."'>";
										$this->salida.="	<table border=\"0\" align=\"center\"  width=\"100%\" class=\"modulo_table_list\">";
										$this->salida.="	<tr class=\"formulacion_table_list\" align=\"center\">";
										$this->salida.="		<td width=\"5%\" align=\"center\">SEXO*</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">LAB EXAMEN*</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">EDAD MIN*</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">EDAD MAX*</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">CARGO*</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">TECNICA*</td>";
										$this->salida.="		<td width=\"15%\" align=\"center\">RANGO MIN</td>";
										$this->salida.="		<td width=\"15%\" align=\"center\">RANGO MAX</td>";
										$this->salida.="		<td width=\"15%\" align=\"center\">UNIDADES</td>";
										$this->salida.="	</tr>";
										for($i=0;$i<sizeof($vector);$i++){
											if( $i % 2){ $estilo='modulo_list_claro';}
											else {$estilo='modulo_list_oscuro';}
											$this->salida.="	<tr class='$estilo' align=\"center\">";
											$this->salida.="		<td width=\"5%\" align=\"center\">".$vector[$i]['sexo_id']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['lab_examen_id']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['edad_min']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['edad_max']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['cargo']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['tecnica_id']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['rango_min']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['rango_max']."</td>";
											$this->salida.="		<td width=\"10%\" align=\"center\">".$vector[$i]['unidades']."</td>";
                      $this->salida.="	</tr>";
                    }
										$this->salida.="	<tr>";
										$this->salida.="			<td class=\"modulo_list_oscuro\" align=\"left\" title = 'Indefinido= sexo indiferente, Masculino = Sexo Masculino, Femenino = Sexo Femenino'><select name=\"datos[sexo]\" class=\"select\">";
										$this->salida .=" 				<option value=\"0\" selected>Indefinido</option>";
										$this->salida .=" 				<option value=\"M\" >Masculino</option>";
										$this->salida .=" 				<option value=\"F\" >Femenino</option>";
										$this->salida.="				</select>";
										$this->salida.="			</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[lab_examen]."</td>";
										$this->salida.="		<td><input type=\"text\" class=\"input-text\" name='datos[edad_min]' maxlength=\"10\" title = '0= Edad indiferente'></td>";
										$this->salida.="		<td><input type=\"text\" class=\"input-text\" name='datos[edad_max]' maxlength=\"10\" title = '0= Edad indiferente'></td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[cargo]."</td>";
										$this->salida.="		<td width=\"10%\" align=\"center\">".$datos[tecnica]."</td>";
										$this->salida.="		<td><input type=\"text\" class=\"input-text\" name='datos[rango_min]' maxlength=\"10\" ></td>";
										$this->salida.="		<td><input type=\"text\" class=\"input-text\" name='datos[rango_max]' maxlength=\"10\" ></td>";
										$this->salida.="		<td><input type=\"text\" class=\"input-text\" name='datos[unidades]' maxlength=\"10\" title = 'Unidades de medicion del cargo dada la tecnica'></td>";
										$this->salida.="	</tr>";
										$this->salida.="	</table>";
				break;
				}
				case '2':
        {
          $this->salida.="	<table border=\"0\" align=\"center\"  width=\"100%\" class=\"modulo_table_list\">";
          $this->salida.="	<tr class=\"formulacion_table_list\" align=\"center\">";
          $this->salida.="		<td width=\"10%\" align=\"center\">OPCION EXAMEN*</td>";
          $this->salida.="		<td width=\"10%\" align=\"center\">ID EXAMEN*</td>";
          $this->salida.="		<td width=\"10%\" align=\"center\">CARGO*</td>";
          $this->salida.="		<td width=\"10%\" align=\"center\">TECNICA*</td>";
          $this->salida.="		<td width=\"35%\" align=\"center\">OPCIONES</td>";
          $this->salida.="		<td width=\"25%\" align=\"center\">UNIDADES</td>";
          $this->salida.="	</tr>";
          
          for($i=0;$i<sizeof($vector);$i++)
          {
            if(!empty($vector[$i]))
            {
              if( $i % 2){ $estilo='modulo_list_claro';}
              else {$estilo='modulo_list_oscuro';}
              $this->salida .= "	<tr class='$estilo' align=\"center\">";
              $this->salida .= "		<td align=\"center\">".$vector[$i]['lab_examen_opcion_id']."</td>";
              $this->salida .= "		<td align=\"center\">".$vector[$i]['lab_examen_id']."</td>";
              $this->salida .= "		<td align=\"center\">".$vector[$i]['cargo']."</td>";
              $this->salida .= "		<td align=\"center\">".$vector[$i]['tecnica_id']."</td>";
              $this->salida .= "		<td align=\"center\">".$vector[$i]['unidades']."</td>";
              $this->salida .= "		<td align=\"center\"></td>\n";
              $this->salida .= "	</tr>\n";
            }
          }
          $opcion=$this->ConsultaMaxOpcion($datos[cargo],$datos[tecnica]);
          $this->salida .= "	<tr class='$estilo' align=\"center\">";
          $this->salida .= "		<td width=\"15%\" align=\"center\">".$opcion."</td>";
          $this->salida .= "		<td width=\"15%\" align=\"center\">".$datos[lab_examen]."</td>";
          $this->salida .= "		<td width=\"15%\" align=\"center\">".$datos[cargo]."</td>";
          $this->salida .= "		<td width=\"15%\" align=\"center\">".$datos[tecnica]."</td>";
          $this->salida .= "		<td align=\"center\">\n";
          $this->salida .= "		  <div id=\"div_opcion\">\n";
          $this->salida .= "		  </div>\n";
          $this->salida .= "		</td>\n";
          $this->salida .= "		<td><input type=\"text\" class=\"input-text\" name='datos[unidades]' maxlength=\"10\" ></td>";
          $this->salida .= "		<input type=hidden name='datos[opcion_id]' value='".$opcion."'>";
          $this->salida .= "	</tr>";
          
          $this->salida.="	</table>";
          $this->salida.="			</td>";
          $this->salida.="		</tr>";
          $this->salida.="		<tr align=\"center\" class=\"formulacion_table_list\">";
          $this->salida.="			<td colspan=\"4\" >TEXTO PARA LAS NORMALIDADES</td>";
          $this->salida.="		</tr>\n";
          $this->salida.="		<tr align=\"center\" class=\"modulo_list_claro\">";
          $this->salida.="			<td colspan=\"4\">";
          $this->salida.="				<textarea name=\"datos[normalidades]\" rows=\"2\" style=\"width:100%\"></textarea>"; 
          $this->salida.="			</td>";
          $this->salida.="		</tr>";
          
          
          //Boton de adicionar opcion
          $this->salida .= "	<tr align=\"center\">";
          $this->salida .= "		<td class=\"formulacion_table_list\" width=\"15%\" align=\"center\">OPCION</td>";
          $this->salida .= "		<td width=\"15%\" align=\"center\">\n";
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name='datos[opcion_des]' id=\"opcion_descripcion\" maxlength=\"25\"></td>";
          
          
          $this->salida .= "		<td align=\"center\">\n";
          $this->salida .= "      <input class=\"input-submit\" name=\"adicionar\" type=\"submit\"  value=\"ADICIONAR\">\n";
          //$this->salida .= "      </form>\n";
          $this->salida .= "    </td>\n";
          //$this->salida.="	<form name=\"forma\" action=\"$accionV\" method=\"post\">";
          $this->salida.="	</tr>";
          $this->salida.="	</table>";
          $this->salida.="</form>";
          $flag =true;
          break;
				}
				case '3':{
										$this->salida.="	<table border=\"0\" align=\"center\"  width=\"100%\" class=\"modulo_table_list\">";
										$this->salida.="		<tr class=\"formulacion_table_list\" align=\"center\">";
										$this->salida.="			<td class=\"modulo_table_title\" width=\"15%\" align=\"center\">LAB EXAMEN</td>";
										$this->salida.="			<td class=\"modulo_list_claro\" width=\"15%\" align=\"left\">".$datos[lab_examen]."</td>";
										$this->salida.="			<td class=\"modulo_table_title\" width=\"15%\" align=\"center\">CARGO</td>";
										$this->salida.="			<td class=\"modulo_list_claro\" width=\"15%\" align=\"center\">".$datos[cargo]."</td>";
										$this->salida.="			<td class=\"modulo_table_title\" width=\"15%\" align=\"center\">TECNICA</td>";
										$this->salida.="			<td class=\"modulo_list_claro\" width=\"15%\" align=\"center\">".$datos[tecnica]."</td>";
										$this->salida.="		</tr>";
										$this->salida.="		<tr>";
										$this->salida.="			<td colspan = \"6\" align=\"center\" width=\"60%\">";
										$this->salida.=getFckeditor("datos[detalle]",'300',"100%",$datos[0]['detalle']);
										$this->salida.="			</td>";
										$this->salida.="		</tr>";
										$this->salida.="					<tr class=\"formulacion_table_list\">";
										$this->salida.="						<td  align=\"center\" colspan=\"6\" >CONVENCIONES</td>";
										$this->salida.="					</tr>";
										$this->salida.="					<tr>";
										$this->salida.="						<td width=\"10%\" align=\"left\" class=\"modulo_table_title\">NOMBRE PACIENTE</td>";
										$this->salida.="						<td width=\"10%\" align=\"left\" class=\"modulo_list_claro\">[PACIENTE]</td>";
										$this->salida.="						<td width=\"10%\" align=\"left\" class=\"modulo_table_title\">CARGO CUPS</td>";
										$this->salida.="						<td width=\"10%\" align=\"left\" class=\"modulo_list_claro\">[CARGO]</td>";
										$this->salida.="						<td width=\"10%\" align=\"left\" class=\"modulo_table_title\">EXAMEN</td>";
										$this->salida.="						<td width=\"10%\" align=\"left\" class=\"modulo_list_claro\">[EXAMEN]</td>";
// 										$this->salida.="						<td width=\"10%\" align=\"left\" class=\"modulo_table_title\">DESCRIPCION TECNICA</td>";
// 										$this->salida.="						<td width=\"10%\" align=\"left\" class=\"modulo_list_claro\">[TECNICA]</td>";
										$this->salida.="					</tr>";
										$this->salida.="	</table>";
				break;
				}
			}
      if(!$flag)
      {
  			$this->salida.="			</td>";
  			$this->salida.="		</tr>";
  			//texto para las normalidades
  			$this->salida.="		<tr align=\"center\" class=\"modulo_list_claro\">";
  			$this->salida.="			<td colspan=\"1\" class=\"NORMAL_10\">Texto para las Normalidades";
  			$this->salida.="			</td>";
  			$this->salida.="			<td colspan=\"3\">";
  			$this->salida.="				<textarea name=\"normalidades\" rows=\"3\" style=\"width:100%\"></textarea>"; 
  			//$this->salida.="				<input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"ALMACENAR PLANTILLA\">";
  			$this->salida.="			</td>";
  			$this->salida.="		</tr>";
  			//
  			$this->salida .= "		<tr align=\"right\" class=\"modulo_list_claro\">";
  			$this->salida .= "			<td colspan=\"4\">\n";
        $this->salida .= "        <input class=\"input-submit\" name=\"guardar\" type=\"button\" value=\"ALMACENAR PLANTILLA\" onclick=\"Continuar(document.FormaPlantilla)\">\n";
        $this->salida .= "      </td>\n";
  			$this->salida .= "		</tr>\n";
  		  $this->salida.="	</table>";
        $this->salida.="</form>";
      }
		//BOTON DE VOLVER
		$accionV=ModuloGetURL('app','Os_Mantenimiento_Apoyod','user','Menu');
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="	<tr class=\"modulo_list_claro\">";
		$this->salida.="	<form name=\"formaVolver\" action=\"$accionV\" method=\"post\">";
		$this->salida.="		<td align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="	</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
}//fin clase

?>