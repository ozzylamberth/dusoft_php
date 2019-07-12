<?php

/**
* Submodulo de Protocolos Medicos (HTML).
*
* Submodulo para manejar los diferentes pasos que se debe seguir con un paciente según unas caracteristicas del
* paciente y demas datos.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_SolicitudPatologica_HTML.php,v 1.7 2006/12/19 21:00:15 jgomez Exp $
*/

/**
* ProtocolosMedicos_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo protocolos medicos, se extiende la clase ProtocolosMedicos y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class SolicitudPatologica_HTML extends SolicitudPatologica
{

/**
* Este es el constructor de la clase
*
* @return boolean Para identificar que se realizo.
*/

	function SolicitudPatologica_HTML()
	{
	    $this->SolicitudPatologica();//constructor del padre
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
    'autor'=>'LORENA ARAGON G.',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }
//////////////
/**
* frmConsulta - función que contiene la forma de consulta de los registros insertados en el ingreso
*
* @return boolean
*/

	function frmConsulta()
	{
	  $SolicitudesPatologicas=$this->SolicitudesPatologicasHC();
		if($SolicitudesPatologicas){
      $this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">\n";
			$this->salida .= "<tr class=\"modulo_table_title\">\n";
			$this->salida .= "<td align=\"center\">LISTADO SOLICITUDES PATOLOGICAS</td>\n";
			$this->salida .= "</tr>\n";
			for($i=0;$i<sizeof($SolicitudesPatologicas);$i++){
			$this->salida .= "<tr class=\"hc_submodulo_list_oscuro\">\n";
			$this->salida .= "<td>\n";
			$this->salida .= "    <BR><table align=\"center\" width=\"98%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">\n";
			$this->salida .= "    <tr class=\"hc_submodulo_list_claro\">";
			$this->salida .= "     <td width=\"15%\" nowrap class=\"label\">QUIROFANO</td>";
			$this->salida .= "     <td width=\"25%\" nowrap>".$SolicitudesPatologicas[$i]['nomquirofano']."</td>";
			$this->salida .= "     <td width=\"15%\" nowrap class=\"label\">SOLICITUD</td>";
			$this->salida .= "     <td>".$SolicitudesPatologicas[$i]['solicitud']."</td>";
			$this->salida .= "    </tr>";
      $this->salida .= "    <tr class=\"hc_submodulo_list_claro\">";
      $this->salida .= "     <td class=\"label\">FECHA</td>";
			$this->salida .= "     <td>".$SolicitudesPatologicas[$i]['fecha']."</td>";
			$this->salida .= "     <td class=\"label\">TRATAMIENTOS</td>";
			$this->salida .= "     <td>".$SolicitudesPatologicas[$i]['tratamientos_efectuados']."</td>";
			$this->salida .= "    </tr>";
			$this->salida .= "    <tr class=\"hc_submodulo_list_claro\">";
			$this->salida .= "     <td class=\"label\">HALLAZGOS</td>";
			$this->salida .= "     <td colspan=\"3\">".$SolicitudesPatologicas[$i]['hallazgos']."</td>";
			$this->salida .= "    </tr>";
			$this->salida .= "    <tr class=\"hc_submodulo_list_claro\">";
			$this->salida .= "     <td class=\"label\">OBSERVACIONES</td>";
			$this->salida .= "     <td colspan=\"3\">".$SolicitudesPatologicas[$i]['observaciones']."</td>";
			$this->salida .= "    </tr>";
      $Diagnosticos=$this->DiagnosticosPatologicasHC($SolicitudesPatologicas[$i]['patologia_solicitud_id']);
			if($Diagnosticos){
			$this->salida .= "    <tr><td colspan=\"4\">";
      $this->salida .= "        <table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
      $this->salida .= "        <tr class=\"modulo_table_title\"><td>DIAGNOSTICOS</td></tr>\n";
			for($j=0;$j<sizeof($Diagnosticos);$j++){
      $this->salida .= "        <tr class=\"hc_submodulo_list_claro\"><td>".$Diagnosticos[$j]['diagnostico_nombre']."</td></tr>";
      }
      $this->salida .= "        </table>";
			$this->salida .= "     </td></tr>";
			}

			$Tejidos=$this->TejidosPatologicasHC($SolicitudesPatologicas[$i]['patologia_solicitud_id']);
			if($Tejidos){
			$this->salida .= "    <tr><td colspan=\"4\">";
      $this->salida .= "        <table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
      $this->salida .= "        <tr class=\"modulo_table_title\"><td>TEJIDOS</td></tr>\n";
			for($j=0;$j<sizeof($Tejidos);$j++){
      $this->salida .= "        <tr class=\"hc_submodulo_list_claro\"><td>".$Tejidos[$j]['descripcion']."</td></tr>";
      }
      $this->salida .= "        </table>";
			$this->salida .= "     </td></tr>";
			}

			$this->salida .= "    </table><br>";
			$this->salida .= "</td>\n";
			$this->salida .= "</tr>";
			}
			$this->salida .= "</table>";
		}
    return true;
	}

/**
* SetStyle - función que realiza el estilo del mensaje del error
*
* @return string
*/


	function SetStyle($campo)
	{
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr><td align=\"center\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

/**
* frmForma - función que contiene la forma pirncipal de la clase
*
* @return boolean.
*/

	function frmForma($solicitud,$tratamientos,$hallazgos,$observaciones,$quirofano){

    $datosCirugia=$this->DatosCirugiaPaciente();
		if($datosCirugia){
		  if(empty($_SESSION['PATOLOGIA']['DIAGNOSTICOS'])){
				$_SESSION['PATOLOGIA']['DIAGNOSTICOS'][$datosCirugia[0]['diagnostico_id']]=$datosCirugia[0]['diagnostico_nombre'];
				if(!$quirofano || $quirofano==-1){
					$quirofano=$datosCirugia[0]['quirofano_id'];
				}
				if(empty($hallazgos)){
					$hallazgos='';
					for($i=0;$i<sizeof($datosCirugia);$i++){
						$hallazgos.=$datosCirugia[$i]['hallazgos_quirurgicos']."\x0a";
					}
				}
			}
		}
		if(empty($this->titulo)){
			$this->salida= ThemeAbrirTablaSubModulo('SOLICITUD PATOLOGIA');
		}else{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'insertar'));
		$this->salida .= "       <form method=\"post\" name=\"forma".$this->frmPrefijo."\" action=\"$accion\">";

		$this->salida .= "       <table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "       <tr class=\"hc_submodulo_list_claro\"><td class=\"".$this->SetStyle("quirofano")."\">QUIROFANO </td>";
		$this->salida .= "       <td align=\"left\" class=\"hc_submodulo_list_claro\"><select name=\"quirofano".$this->frmPrefijo."\" class=\"select\">";
		$quirofanos=$this->TotalQuirofanos();
		$this->MostrasSelect($quirofanos,'False',$quirofano);
		$this->salida .= "       </select></td>";
    $this->salida .= "       </tr>";
		$this->salida .= "      <tr class=\"hc_submodulo_list_claro\"><td class=\"".$this->SetStyle("Solicitud")."\">SOLICITUD </td>";
		$this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"Solicitud".$this->frmPrefijo."\" size=\"85\" maxlength=\"150\" value=\"$solicitud\"></td></tr>";
		$this->salida .= "      <tr class=\"hc_submodulo_list_claro\"><td colspan=\"2\">";
		$this->salida .= "          <table width=\"85%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "          <tr class=\"hc_submodulo_list_claro\">";
		$this->salida .= "          <td align=\"left\"><label class=\"label\">TRATAMIENTOS EFECTUADOS Y TIEMPO DE DURACION</label><BR><BR><textarea style=\"width:100%\" name=\"tratamientos".$this->frmPrefijo."\" class=\"textarea\" rows=\"3\" cols=\"60\">$tratamientos</textarea></td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "      </td></tr>";
		$this->salida .= "      <tr class=\"hc_submodulo_list_claro\"><td colspan=\"2\">";
		$this->salida .= "          <table width=\"85%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "          <tr class=\"hc_submodulo_list_claro\">";
		$this->salida .= "          <td align=\"left\"><label class=\"label\">HALLAZGOS</label><BR><BR><textarea style=\"width:100%\" name=\"hallazgos".$this->frmPrefijo."\" class=\"textarea\" rows=\"3\" cols=\"60\">$hallazgos</textarea></td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "      </td></tr>";
		$this->salida .= "      <tr class=\"hc_submodulo_list_claro\"><td colspan=\"2\">";
		$this->salida .= "          <table width=\"85%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "          <tr class=\"hc_submodulo_list_claro\">";
		$this->salida .= "          <td align=\"left\"><label class=\"label\">OBSERVACIONES</label><BR><BR><textarea style=\"width:100%\" name=\"observaciones".$this->frmPrefijo."\" class=\"textarea\" rows=\"3\" cols=\"60\">$observaciones</textarea></td>";
		$this->salida .= "            </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "      </td></tr>";
		$this->salida .= "      <tr><td class=\"hc_submodulo_list_claro\" colspan=\"2\">";
		$this->salida .= "          <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "          <tr class=\"modulo_table_title\">";
		$this->salida .= "          <td colspan=\"2\">DIAGNOSTICOS</td>";
		$this->salida .= "          </tr>";
		//unset($_SESSION['PATOLOGIA']['DIAGNOSTICOS']);
		$diagnosticos=$_SESSION['PATOLOGIA']['DIAGNOSTICOS'];
		if($diagnosticos){
			$this->salida .= "        <tr class=\"hc_submodulo_list_claro\"><td align=\"center\" colspan=\"2\" class=\"hc_submodulo_list_oscuro\">";
			$this->salida .= "            <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
			$this->salida .= "            <tr class=\"modulo_table_title\">";
			$this->salida .= "            <td>DIAGNOSTICOS INSERTADOS</td>";
			$this->salida .= "            <td>&nbsp;</td>";
			$this->salida .= "            </tr>";
			foreach($diagnosticos as $codigo=>$nombreDiag){
				$this->salida .= "          <tr class=\"hc_submodulo_list_claro\">";
				$this->salida .= "          <td>$nombreDiag</td>";
				$actionElimina=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionDiagnostico',
				"codigoDiagnostico".$this->frmPrefijo=>$codigo,"elimina".$this->frmPrefijo=>1,
				"Solicitud".$this->frmPrefijo=>$solicitud,"tratamientos".$this->frmPrefijo=>$tratamientos,"hallazgos".$this->frmPrefijo=>$hallazgos,"observaciones".$this->frmPrefijo=>$observaciones,"quirofano".$this->frmPrefijo=>$quirofano));
				$this->salida .= "          <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionElimina\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
				$this->salida .= "          </tr>";
			}
			$this->salida .= "            </table><br>";
			$this->salida .= "        </td></tr>";
		}
		$this->salida .= "          <tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida .= "          <td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"buscarDiagn".$this->frmPrefijo."\" value=\"BUSCAR\"></td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "      </td></tr>";
		$this->salida .= "     <tr><td class=\"hc_submodulo_list_claro\" colspan=\"2\">";
		$this->salida .= "          <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "          <tr class=\"modulo_table_title\">";
		$this->salida .= "          <td colspan=\"2\">TEJIDOS</td>";
		$this->salida .= "          </tr>";
		$tejidos=$_SESSION['PATOLOGIA']['TEJIDOS'];
		if($tejidos){
			$this->salida .= "        <tr class=\"hc_submodulo_list_oscuro\"><td align=\"center\" colspan=\"2\">";
			$this->salida .= "            <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
			$this->salida .= "            <tr class=\"modulo_table_title\">";
			$this->salida .= "            <td>TEJIDOS INSERTADOS</td>";
			$this->salida .= "            <td>&nbsp;</td>";
			$this->salida .= "            </tr>";
			foreach($tejidos as $codigo=>$nombreTej){
				$this->salida .= "          <tr class=\"hc_submodulo_list_claro\">";
				$this->salida .= "          <td>$nombreTej</td>";
				$actionEliminaT=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionTejido',
				"codigoTejido".$this->frmPrefijo=>$codigo,"elimina".$this->frmPrefijo=>1,
				"Solicitud".$this->frmPrefijo=>$solicitud,"tratamientos".$this->frmPrefijo=>$tratamientos,"hallazgos".$this->frmPrefijo=>$hallazgos,"observaciones".$this->frmPrefijo=>$observaciones,"quirofano".$this->frmPrefijo=>$quirofano));
				$this->salida .= "          <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionEliminaT\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
				$this->salida .= "          </tr>";
			}
			$this->salida .= "            </table><br>";
			$this->salida .= "        </td></tr>";
		}
		$this->salida .= "          <tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida .= "          <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"buscarTejido".$this->frmPrefijo."\" value=\"BUSCAR\"></td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "     </td></tr>";
    $this->salida .= "     </table>";
		$this->salida .= "     <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
    $this->salida .= "     <tr><td align=\"center\">";
    $this->salida .= "     <input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\" class=\"input-submit\">";
		$this->salida .= "     </td></tr>";
		$this->salida .= "     </table>";
		$this->frmConsulta();
		//$this->salida .= '      </form>';
		$this->salida .= ThemeCerrarTablaSubModulo();
    return true;
	}

/**
* FormaBuscadorDiagnostico - función que imprime la forma que realiza el buscador de los diagnosticos
*
* @return boolean
*/

	function FormaBuscadorDiagnostico($solicitud,$tratamientos,$hallazgos,$observaciones,$quirofano){

    $this->salida  = ThemeAbrirTablaSubModulo('BUSCADOR DIAGNOSTICOS',500);
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'diagnostico',
		"Solicitud".$this->frmPrefijo=>$solicitud,"tratamientos".$this->frmPrefijo=>$tratamientos,"hallazgos".$this->frmPrefijo=>$hallazgos,"observaciones".$this->frmPrefijo=>$observaciones,"quirofano".$this->frmPrefijo=>$quirofano));
		$this->salida .= "    <form method='post' name='forma".$this->frmPrefijo."' action=$action>";
		$this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"5\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">DIAGNOSTICO</td>";
    $this->salida .= "    <td><input size=\"80\" type=\"text\" name=\"procedimientoBus".$this->frmPrefijo."\" value=\"".$_REQUEST['procedimientoBus'.$this->frmPrefijo]."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
		$this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus".$this->frmPrefijo."\" value=\"".$_REQUEST['codigoBus'.$this->frmPrefijo]."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td><input type=\"submit\" name=\"buscar".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "	  </table><BR>";
		$diags=$this->HallarDiagnosticosPatologia($_REQUEST['codigoBus'.$this->frmPrefijo],$_REQUEST['procedimientoBus'.$this->frmPrefijo]);
		if($diags){
      $this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>CODIGO</td>";
			$this->salida .= "	   <td>DESCRIPCION</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$y=0;
			for($i=0;$i<sizeof($diags);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td width=\"10%\" nowrap>".$diags[$i]['diagnostico_id']."</td>";
				$this->salida .= "    <td>".$diags[$i]['diagnostico_nombre']."</td>";
				$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionDiagnostico',
				"codigoDiagnostico".$this->frmPrefijo=>$diags[$i]['diagnostico_id'],"nombreDiagnostico".$this->frmPrefijo=>$diags[$i]['diagnostico_nombre'],
				"Solicitud".$this->frmPrefijo=>$solicitud,"tratamientos".$this->frmPrefijo=>$tratamientos,"hallazgos".$this->frmPrefijo=>$hallazgos,"observaciones".$this->frmPrefijo=>$observaciones,"quirofano".$this->frmPrefijo=>$quirofano));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table><BR>";
			$this->salida .= $this->RetornarBarra(1);
		}else{
      $this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "     <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "	   </table><br>";
		}
		$this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
    $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" name=\"Salir".$this->frmPrefijo."\" value=\"SALIR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	   </table><br>";
    $this->salida .= '      </form>';
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

/**
* BucadorTejidosPatologicosBuscador - función que imprime la forma que realiza el buscador de los diagnosticos
*
* @return boolean
*/

	function BucadorTejidosPatologicosBuscador($solicitud,$tratamientos,$hallazgos,$observaciones,$quirofano){
    $this->salida = ThemeAbrirTablaSubModulo('BUSCADOR TEJIDOS PATOLOGICOS',500);
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'tejido',
		"Solicitud".$this->frmPrefijo=>$solicitud,"tratamientos".$this->frmPrefijo=>$tratamientos,"hallazgos".$this->frmPrefijo=>$hallazgos,"observaciones".$this->frmPrefijo=>$observaciones,"quirofano".$this->frmPrefijo=>$quirofano));
		$this->salida .= "    <form method='post' name='forma".$this->frmPrefijo."' action=$action>";
		$this->salida .= "	  <table width=\"85%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"5\">PARAMETROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">TEJIDO</td>";
    $this->salida .= "    <td><input size=\"80\" type=\"text\" name=\"procedimientoBus".$this->frmPrefijo."\" value=\"".$_REQUEST['procedimientoBus'.$this->frmPrefijo]."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
		$this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus".$this->frmPrefijo."\" value=\"".$_REQUEST['codigoBus'.$this->frmPrefijo]."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td><input type=\"submit\" name=\"buscar".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "	  </table><BR>";
		$tejidos=$this->HallarTejidosPatologia($_REQUEST['codigoBus'.$this->frmPrefijo],$_REQUEST['procedimientoBus'.$this->frmPrefijo]);
		if($tejidos){
      $this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>CODIGO</td>";
			$this->salida .= "	   <td>DESCRIPCION</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$y=0;
			for($i=0;$i<sizeof($tejidos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td width=\"10%\" nowrap>".$tejidos[$i]['tejido_id']."</td>";
				$this->salida .= "    <td>".$tejidos[$i]['descripcion']."</td>";
				$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionTejido',
				"codigoTejido".$this->frmPrefijo=>$tejidos[$i]['tejido_id'],"nombreTejido".$this->frmPrefijo=>$tejidos[$i]['descripcion'],
				"Solicitud".$this->frmPrefijo=>$solicitud,"tratamientos".$this->frmPrefijo=>$tratamientos,"hallazgos".$this->frmPrefijo=>$hallazgos,"observaciones".$this->frmPrefijo=>$observaciones,"quirofano".$this->frmPrefijo=>$quirofano));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table><BR>";
			$this->salida .=$this->RetornarBarra(2);
		}else{
      $this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "     <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "	   </table><br>";
		}
		$this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
    $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" name=\"Salir".$this->frmPrefijo."\" value=\"SALIR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	   </table><br>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

/**
* CalcularNumeroPasos - funcion que cuenta el numero de pasos para la barra
*
* @return int
*/
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

/**
* CalcularBarra - funcion que realiza el claculo de registro en la barra
*
* @return int
*/

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
/**
*  CalcularBarra - Calcula el limite de registros en la barra
*
* @return int
*/
	//cor - jea - ads
	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

/**
* RetornarBarra - funcion que dibuja el html de la barra dentro de una forma
*
* @return boolean
*/

	//cor - jea - ads
	function RetornarBarra($origen)//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo){
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso)){
			$paso=1;
		}
		if($origen==1){
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'diagnostico',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		"Solicitud".$this->frmPrefijo=>$_REQUEST["Solicitud".$this->frmPrefijo],"tratamientos".$this->frmPrefijo=>$_REQUEST["tratamientos".$this->frmPrefijo],
		"hallazgos".$this->frmPrefijo=>$_REQUEST["hallazgos".$this->frmPrefijo],"observaciones".$this->frmPrefijo=>$_REQUEST["observaciones".$this->frmPrefijo],
		"quirofano".$this->frmPrefijo=>$_REQUEST["quirofano".$this->frmPrefijo]));
		}elseif($origen==2){
    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'tejido',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		"Solicitud".$this->frmPrefijo=>$_REQUEST["Solicitud".$this->frmPrefijo],"tratamientos".$this->frmPrefijo=>$_REQUEST["tratamientos".$this->frmPrefijo],
		"hallazgos".$this->frmPrefijo=>$_REQUEST["hallazgos".$this->frmPrefijo],"observaciones".$this->frmPrefijo=>$_REQUEST["observaciones".$this->frmPrefijo],
		"quirofano".$this->frmPrefijo=>$_REQUEST["quirofano".$this->frmPrefijo]));
		}
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
			$diferencia=$numpasos-9;
			if($diferencia<=0){
				$diferencia=1;
			}for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}if($paso!=$numpasos){
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos)){
			if($numpasos>10){
				$valor=10+3;
			}else{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}else{
			if($numpasos>10){
				$valor=10+5;
			}else{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
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

/**
* frmHistoria - función que
*
* @return boolean Para identificar que se realizo.
*/

	function frmHistoria()
	{
    $SolicitudesPatologicas=$this->SolicitudesPatologicasHC();
		if($SolicitudesPatologicas){
      $salida.="<table align=\"center\" width=\"90%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">\n";
			$salida.="<tr class=\"modulo_table_title\">\n";
			$salida.="<td align=\"center\">LISTADO SOLICITUDES PATOLOGICAS</td>\n";
			$salida.="</tr>\n";
			for($i=0;$i<sizeof($SolicitudesPatologicas);$i++){
			$salida.="<tr class=\"hc_submodulo_list_oscuro\">\n";
			$salida.="<td>\n";
			$salida.="  <BR><table align=\"center\" width=\"98%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">\n";
			$salida.="  <tr class=\"hc_submodulo_list_claro\">";
			$salida.="  <td width=\"15%\" nowrap class=\"label\">QUIROFANO</td>";
			$salida.="  <td width=\"25%\" nowrap>".$SolicitudesPatologicas[$i]['nomquirofano']."</td>";
			$salida.="  <td width=\"15%\" nowrap class=\"label\">SOLICITUD</td>";
			$salida.="  <td>".$SolicitudesPatologicas[$i]['solicitud']."</td>";
			$salida.="  </tr>";
      $salida.="  <tr class=\"hc_submodulo_list_claro\">";
      $salida.="  <td class=\"label\">FECHA</td>";
			$salida.="  <td>".$SolicitudesPatologicas[$i]['fecha']."</td>";
			$salida.="  <td class=\"label\">TRATAMIENTOS</td>";
			$salida.="  <td>".$SolicitudesPatologicas[$i]['tratamientos_efectuados']."</td>";
			$salida.="  </tr>";
			$salida.="  <tr class=\"hc_submodulo_list_claro\">";
			$salida.="  <td class=\"label\">HALLAZGOS</td>";
			$salida.="  <td colspan=\"3\">".$SolicitudesPatologicas[$i]['hallazgos']."</td>";
			$salida.="  </tr>";
			$salida.="  <tr class=\"hc_submodulo_list_claro\">";
			$salida.="  <td class=\"label\">OBSERVACIONES</td>";
			$salida.="  <td colspan=\"3\">".$SolicitudesPatologicas[$i]['observaciones']."</td>";
			$salida.="  </tr>";
      $Diagnosticos=$this->DiagnosticosPatologicasHC($SolicitudesPatologicas[$i]['patologia_solicitud_id']);
			if($Diagnosticos){
			$salida.="  <tr><td colspan=\"4\">";
      $salida.="  <table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
      $salida.="  <tr class=\"modulo_table_title\"><td>DIAGNOSTICOS</td></tr>\n";
			for($j=0;$j<sizeof($Diagnosticos);$j++){
      $salida.="  <tr class=\"hc_submodulo_list_claro\"><td>".$Diagnosticos[$j]['diagnostico_nombre']."</td></tr>";
      }
      $salida.="  </table>";
			$salida.="  </td></tr>";
			}
			$Tejidos=$this->TejidosPatologicasHC($SolicitudesPatologicas[$i]['patologia_solicitud_id']);
			if($Tejidos){
			$salida.="  <tr><td colspan=\"4\">";
      $salida.="  <table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
      $salida.="  <tr class=\"modulo_table_title\"><td>TEJIDOS</td></tr>\n";
			for($j=0;$j<sizeof($Tejidos);$j++){
      $salida.="  <tr class=\"hc_submodulo_list_claro\"><td>".$Tejidos[$j]['descripcion']."</td></tr>";
      }
      $salida.="  </table>";
			$salida.="  </td></tr>";
			}
			$salida.="  </table><br>";
			$salida.="  </td>\n";
			$salida.="  </tr>";
			}
			$salida.="</table>";
		}
		return $salida;
	}



}

?>
