<?php

/**
* Submodulo de Atención (HTML).
*
* Submodulo para manejar el tipo de atención (rips) de un paciente en una evolución.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_NotasOperatorias_HTML.php,v 1.18 2007/01/19 19:02:58 lorena Exp $
*/

/**
* Atencion_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo atencion, se extiende la clase Atencion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/
IncludeClass("ClaseHTML");
class NotasOperatorias_HTML extends NotasOperatorias
{

	function NotasOperatorias_HTML()
	{
		$this->NotasOperatorias();//constructor del padre
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


  
  
	function frmForma(){

		if(empty($this->titulo))
		{
			$this->salida  = ThemeAbrirTablaSubModulo('NOTA OPERATORIA DE LA CIRUGIA');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
    $pfj=$this->frmPrefijo;
    $this->salida.="<script>";
    $this->salida.="function CargarAccion(url){\n";
    $this->salida.="document.formauno$pfj.action=url;\n";
    $this->salida.="document.formauno$pfj.submit();}";
    $this->salida.="</script>";
		//$datosCumplimiento=$this->DatosCumplimiento();
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'FrmNotasOperatorias'));
		$this->salida.="    <form name='formauno".$this->frmPrefijo."' action=\"$accion\" method=post>";
		/*if($datosCumplimiento){
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DATOS PRINCIPALES DE LA CIRUGIA</td></tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td class=\"hc_table_submodulo_list_title\">CIRUJANO PRINCIPAL</td>";
			$this->salida .= "  <td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$datosCumplimiento['tipo_id_cirujano']." ".$datosCumplimiento['cirujano_id']."&nbsp&nbsp&nbsp&nbsp;".$datosCumplimiento['nombrecirujano']."</td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td class=\"hc_table_submodulo_list_title\" align=\"center\">DIAGNOSTICO PRE-QUIRURGICO</td>";
			$this->salida .= "  <td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$datosCumplimiento['diagnostico_nombre']."</td>";
			$this->salida .= "  </tr>";
      $this->salida .= "</table>";
			$confirmacion=$this->ConfirmarExisteCumplimiento();
			if(!$_SESSION['NotaOperatoria']['NotaId']){
				(list($fechaIn,$horaIn)=explode(' ',$datosCumplimiento['hora_inicio']));
				(list($ano,$mes,$dia)=explode('-',$fechaIn));
				$_REQUEST['fechainicio'.$this->frmPrefijo]=$dia.'-'.$mes.'-'.$ano;
				(list($hora,$minutos)=explode(':',$horaIn));
				$_REQUEST['hora'.$this->frmPrefijo]=$hora;
				$_REQUEST['minutos'.$this->frmPrefijo]=$minutos;
				(list($fechaFn,$horaFn)=explode(' ',$datosCumplimiento['hora_fin']));
				(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
				(list($horaFn,$minutosFn)=explode(':',$horaFn));
				$Duracion=(mktime($horaFn,$minutosFn,0,$mesFn,$diaFn,$anoFn)-mktime($hora,$minutos,0,$mes,$dia,$ano))/60;
				$horaDur=(int)($Duracion/60);
				$minutosDur=$Duracion%60;
				$_REQUEST['horadur'.$this->frmPrefijo]=$horaDur;
				$_REQUEST['minutosdur'.$this->frmPrefijo]=$minutosDur;
				$_REQUEST['quirofano'.$this->frmPrefijo]=$datosCumplimiento['quirofano_id'];
				$_REQUEST['viaAcceso'.$this->frmPrefijo]=$datosCumplimiento['via_acceso'];
				$_REQUEST['tipoCirugia'.$this->frmPrefijo]=$datosCumplimiento['tipo_cirugia'];
				$_REQUEST['ambitoCirugia'.$this->frmPrefijo]=$datosCumplimiento['ambito_cirugia'];
			}else{
        $datosNotas=$this->DatosNotasOperatorias();
				(list($fechaIn,$horaIn)=explode(' ',$datosNotas['hora_inicio']));
				(list($ano,$mes,$dia)=explode('-',$fechaIn));
				$_REQUEST['fechainicio'.$this->frmPrefijo]=$dia.'-'.$mes.'-'.$ano;
				(list($hora,$minutos)=explode(':',$horaIn));
				$_REQUEST['hora'.$this->frmPrefijo]=$hora;
				$_REQUEST['minutos'.$this->frmPrefijo]=$minutos;
				(list($fechaFn,$horaFn)=explode(' ',$datosNotas['hora_fin']));
				(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
				(list($horaFn,$minutosFn)=explode(':',$horaFn));
				$Duracion=(mktime($horaFn,$minutosFn,0,$mesFn,$diaFn,$anoFn)-mktime($hora,$minutos,0,$mes,$dia,$ano))/60;
				$horaDur=(int)($Duracion/60);
				$minutosDur=$Duracion%60;
				$_REQUEST['horadur'.$this->frmPrefijo]=$horaDur;
				$_REQUEST['minutosdur'.$this->frmPrefijo]=$minutosDur;
				$_REQUEST['quirofano'.$this->frmPrefijo]=$datosNotas['quirofano_id'];
				$_REQUEST['viaAcceso'.$this->frmPrefijo]=$datosNotas['via_acceso'];
				$_REQUEST['tipoCirugia'.$this->frmPrefijo]=$datosNotas['tipo_cirugia'];
				$_REQUEST['ambitoCirugia'.$this->frmPrefijo]=$datosNotas['ambito_cirugia'];
				$_REQUEST['finalidadCirugia'.$this->frmPrefijo]=$datosNotas['finalidad_procedimiento_id'];
				$datosNotasDiag=$this->DiagnosticosNotasOperatorias();
				if(empty($_REQUEST['cargo'.$this->frmPrefijo]) && empty($_REQUEST['codigo'.$this->frmPrefijo])){
					$_REQUEST['cargo'.$this->frmPrefijo]=$datosNotasDiag['diagnostico_nombre'];
					$_REQUEST['codigo'.$this->frmPrefijo]=$datosNotasDiag['diagnostico_id'];
				}
				if(empty($_REQUEST['cargo1'.$this->frmPrefijo]) && empty($_REQUEST['codigo1'.$this->frmPrefijo])){
				$_REQUEST['cargo1'.$this->frmPrefijo]=$datosNotasDiag['complicacion'];
				$_REQUEST['codigo1'.$this->frmPrefijo]=$datosNotasDiag['complicacion_id'];
				}
			}
		}*/
		
		
		/*$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td class=\"label_error\" align=\"center\">NO TIENE PROGRAMACIONES ACTIVAS</td></tr>";
		$this->salida .= "</table>";	*/
		if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo])){
			$this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">";
			$this->salida .= "  <tr><td align=\"center\" class=\"label_error\">";
			$this->salida .= "   EL PACIENTE NO TIENE UNA PROGRAMACION ACTIVA PARA REALIZAR UNA NOTA OPERATORIA";
			$this->salida .= "  </td></tr>";
			$this->salida .= "  </table>";	
			$this->salida.= " </form>";
			$this->salida .= ThemeCerrarTablaSubModulo();
    	return true;
		}
		
		$this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";		
		
			
		$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "<tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">PROFESIONALES QUE PARTICIPARON EN LA CIRUGIA</td></tr>";		
		$this->salida .= "<tr>";
		$this->salida .= "	<td class=\"hc_table_submodulo_list_title\">ANESTESIOLOGO</td>";
		$this->salida .= "	<td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">";
		$this->salida .= "	<select name=\"anestesista".$this->frmPrefijo."\" class=\"select\">";
	  $anestesiologos=$this->profesionalesEspecialistaAnestecistas();
	  $this->salida .=" 	<option value=\"-1\">---Seleccione---</option>";
		for($i=0;$i<sizeof($anestesiologos);$i++){
			$value=$anestesiologos[$i]['tercero_id'].'/'.$anestesiologos[$i]['tipo_id_tercero'];
			$titulo=$anestesiologos[$i]['nombre'];
			if($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']){
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}	  
	  $this->salida .= "	</select>";
		$this->salida .= "	</td>";				
		$this->salida .= "</tr>";	
		
		$this->salida .= "<tr>";
		$this->salida .= "<td class=\"hc_table_submodulo_list_title\">AYUDANTE</td>";
		$this->salida .= "<td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">";
		$this->salida .= "   <select name=\"ayudante".$this->frmPrefijo."\" class=\"select\">";
	  $profesionales=$this->profesionalesAyudantes();
	  $this->salida .=" 	 <option value=\"-1\">---Seleccione---</option>";
		for($i=0;$i<sizeof($profesionales);$i++){
			$value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
			$titulo=$profesionales[$i]['nombre'];
			if($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']){
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
	  $this->salida .= "    </select>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
						
		$this->salida .= "<tr>";
		$this->salida .= "<td class=\"hc_table_submodulo_list_title\">INSTRUMENTADOR</td>";
		$this->salida .= "<td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">";
		$this->salida .= "<select name=\"instrumentista".$this->frmPrefijo."\" class=\"select\">";
	  $instrumentistas=$this->profesionalesEspecialistaInstrumentistas();
	  $this->salida .=" 	 <option value=\"-1\">---Seleccione---</option>";
		for($i=0;$i<sizeof($instrumentistas);$i++){
			$value=$instrumentistas[$i]['tercero_id'].'/'.$instrumentistas[$i]['tipo_id_tercero'];
			$titulo=$instrumentistas[$i]['nombre'];
			if($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']){
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
	  $this->salida .= "    </select>";
		$this->salida .= "</td>";  		
		$this->salida .= "</tr>";		
		
		$this->salida .= "<tr>";
		$this->salida .= "<td class=\"hc_table_submodulo_list_title\">CIRCULANTE</td>";
		$this->salida .= "<td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">";
		$this->salida .= "<select name=\"circulante".$this->frmPrefijo."\" class=\"select\">";
	  $ciruculantes=$this->profesionalesEspecialistaCiculantes();
	  $this->salida .=" 	 <option value=\"-1\">---Seleccione---</option>";
		for($i=0;$i<sizeof($ciruculantes);$i++){
			$value=$ciruculantes[$i]['tercero_id'].'/'.$ciruculantes[$i]['tipo_id_tercero'];
			$titulo=$ciruculantes[$i]['nombre'];
			if($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']){
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
	  $this->salida .= "    </select>";
		$this->salida .= "</td>";  
		$this->salida .= "</tr>";		
		$this->salida .= "</table><BR>";
    
		
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">CLASIFICACION DE LA CIRUGIA</td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">FECHA INICIO</td>";
		$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input type=\"text\" size=\"10\" maxlength=\"10\" class=\"input-text\" name=\"fechainicio".$this->frmPrefijo."\" value=\"".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']."\" class=\"text-input\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "  &nbsp&nbsp&nbsp;".ReturnOpenCalendario('formauno'.$this->frmPrefijo,'fechainicio'.$this->frmPrefijo,'/')."</td>";
		$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">HORA INICIO</td>";
		$this->salida .= "  <td width=\"15%\" class=\"hc_submodulo_list_oscuro\">";
		$this->salida .= "  <select size=\"1\" name=\"hora".$this->frmPrefijo."\" class=\"select\">";
		$this->salida .= "  <option value = -1>Hora Inicio</option>";
		for($j=0;$j<=23; $j++){
			if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']==$hora){
					$this->salida.="    <option selected value = \"$hora\">0$j</option>";
				}else{
					$this->salida.="    <option value = \"$hora\">0$j</option>";
				}
			}else{
				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']==$j){
					$this->salida.="    <option selected value = $j>$j</option>";
				}else{
					$this->salida.="    <option value = $j>$j</option>";
				}
			}
		}
		$this->salida.="   </select>";
		$this->salida.="   <select size=\"1\"  name=\"minutos".$this->frmPrefijo."\" class=\"select\">";
		$this->salida.="   <option value = -1>Minutos Inicio</option>";
		for($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']==$min){
					$this->salida.="<option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="<option value=\"$min\">0$j</option>";
				}
			}else{
				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']==$j){
					$this->salida.="<option selected value=$j>$j</option>";
				}else{
					$this->salida.="<option value=$j>$j</option>";
				}
			}
		}
		$this->salida.="      </select>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">DURACION</td>";
		$this->salida .= "  <td width=\"15%\" class=\"hc_submodulo_list_oscuro\">";
		$this->salida .= "  <select size=\"1\" name=\"horadur".$this->frmPrefijo."\" class=\"select\">";
		$this->salida .= "  <option value = -1>Horas</option>";
		for($j=0;$j<=23; $j++){
			if (($j >= 0) AND ($j<= 9)){
				$horadur = '0'.$j;
				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']==$horadur){
					$this->salida.="    <option selected value = \"$horadur\">0$j</option>";
				}else{
					$this->salida.="    <option value = \"$horadur\">0$j</option>";
				}
			}else{
				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']==$j){
					$this->salida.="    <option selected value = $j>$j</option>";
				}else{
					$this->salida.="    <option value = $j>$j</option>";
				}
			}
		}
		$this->salida.="   </select>";
		$this->salida.="   <select size=\"1\"  name=\"minutosdur".$this->frmPrefijo."\" class=\"select\">";
		$this->salida.="   <option value = -1>Minutos</option>";
		for($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$mindur = '0'.$j;
				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']==$mindur){
					$this->salida.="<option selected value = \"$mindur\" >0$j</option>";
				}else{
					$this->salida.="<option value=\"$mindur\">0$j</option>";
				}
			}else{
				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']==$j){
					$this->salida.="<option selected value=$j>$j</option>";
				}else{
					$this->salida.="<option value=$j>$j</option>";
				}
			}
		}
		$this->salida.="      </select>";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">QUIROFANO</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><select name=\"quirofano".$this->frmPrefijo."\" class=\"select\">";
		$quirofanos=$this->TotalQuirofanos();
		$this->MostrasSelect($quirofanos,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']);
		$this->salida .= "  </select></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">VIA ACCESO</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><select name=\"viaAcceso".$this->frmPrefijo."\" class=\"select\">";
		$accesos=$this->ViaAccesosCirugia();
		$this->MostrasSelect($accesos,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']);
		$this->salida .= "    </select></td>";
		$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO CIRUGIA</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><select name=\"tipoCirugia".$this->frmPrefijo."\" class=\"select\">";
		$tiposCirugias=$this->TiposdeCirugia();
		$this->MostrasSelect($tiposCirugias,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']);
		$this->salida .= "    </select></td>";
		$this->salida.= "     </tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">AMBITO CIRUGIA</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><select name=\"ambitoCirugia".$this->frmPrefijo."\" class=\"select\">";
		$tiposAmbitos=$this->TiposdeAmbitosdeCirugia();
		$this->MostrasSelect($tiposAmbitos,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']);
		$this->salida .= "    </select></td>";
		$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">FINALIDAD CIRUGIA</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><select name=\"finalidadCirugia".$this->frmPrefijo."\" class=\"select\">";
		$tiposFinalidades=$this->TiposfinalidadesCirugia();
		$this->MostrasSelect($tiposFinalidades,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']);
		$this->salida .= "    </select></td>";
		$this->salida.= "     </tr>";
		/*$this->salida.= "		  <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "		  <td colspan=\"4\" align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"GUARDAR NOTA\" name=\"Guardar".$this->frmPrefijo."\"></td>";
		$this->salida.= "    </tr>";*/
		$this->salida.= "  </table>";
			
		
		
		
		/*$this->salida.= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida.= "    <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
    $this->salida.= "     <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "     <td class=\"".$this->SetStyle("cargo")."\">POST-QUIRURGICO</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"cargo".$this->frmPrefijo."\" maxlength=\"256\" size=\"70\" class=\"input-text\" value=\"".$_REQUEST['cargo'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "     <td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"codigo".$this->frmPrefijo."\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"".$_REQUEST['codigo'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "     <td class=\"hc_submodulo_list_oscuro\"><input type=\"submit\" name=\"buscar".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "     <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "     <td class=\"".$this->SetStyle("cargo1")."\">COMPLICACION</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"cargo1".$this->frmPrefijo."\" maxlength=\"256\" size=\"70\" class=\"input-text\" value=\"".$_REQUEST['cargo1'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "     <td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"codigo1".$this->frmPrefijo."\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"".$_REQUEST['codigo1'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "     <td class=\"hc_submodulo_list_oscuro\"><input type=\"submit\" name=\"buscar1".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "		<tr class=\"hc_table_submodulo_list_title\">";
    $this->salida.= "		<td colspan=\"4\" align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"GUARDAR NOTA\" name=\"Guardar".$this->frmPrefijo."\"></td>";
		$this->salida.= "    </tr>";
    $this->salida.= "  </table>";*/
		//$this->salida.= "  </form>";
		/*$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'BusquedaTodosDiagnosticos'));
		$this->salida.= "   <form name='formados".$this->frmPrefijo."' action=\"$accion\" method=post>";
		$this->salida.= "    <BR><table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida.= "    <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
    $this->salida.= "     <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "     <td class=\"".$this->SetStyle("cargo")."\">POST-QUIRURGICO</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"cargo".$this->frmPrefijo."\" maxlength=\"256\" size=\"70\" class=\"input-text\" value=\"".$_REQUEST['cargo'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "     <td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"codigo".$this->frmPrefijo."\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"".$_REQUEST['codigo'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "     <td class=\"hc_submodulo_list_oscuro\"><input type=\"submit\" name=\"buscar".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "     <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "     <td class=\"".$this->SetStyle("cargo1")."\">COMPLICACION</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"cargo1".$this->frmPrefijo."\" maxlength=\"256\" size=\"70\" class=\"input-text\" value=\"".$_REQUEST['cargo1'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "     <td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"codigo1".$this->frmPrefijo."\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"".$_REQUEST['codigo1'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "     <td class=\"hc_submodulo_list_oscuro\"><input type=\"submit\" name=\"buscar1".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "		  <tr class=\"hc_table_submodulo_list_title\">";
    $this->salida.= "		  <td colspan=\"4\" align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"GUARDAR DIAGNOSTICOS\" name=\"Guardar".$this->frmPrefijo."\"></td>";
		$this->salida.= "    </tr>";
		$this->salida.= "    </table>";
		$this->salida.= "  </form>";
    */
    //$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'InsercionProcedimientosNota'));
		//$this->salida.= "   <form name='formados".$this->frmPrefijo."' action=\"$accion\" method=post>";
		
		$this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"98%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">CUMPLIMIENTO DE  PROCEDIMIENTOS PROGRAMADOS</td>";
		$this->salida.="</tr>";	
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";			
		$this->salida.="  <td width=\"9%\">CARGO</td>";
		$this->salida.="  <td width=\"51%\">DESCRIPCION</td>";
		$this->salida.="  <td colspan= 3 width=\"13%\">OPCION</td>";
		$this->salida.="</tr>";
		
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1']){		
			
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'] as $codigo=>$procedimiento){
						
				if( $i % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$row=4;					
				$this->salida.="  <td align=\"center\" width=\"9%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"52%\">$procedimiento</td>";								
				$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmEditarEspecificaionProc','cargo'.$this->frmPrefijo=>$codigo,'descripcion'.$this->frmPrefijo=>$procedimiento));
				$this->salida.="  <td align=\"center\" width=\"6%\"><a href='javascript:CargarAccion(\"$accion1\")'><img title=\"Modificar\" src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
				$this->salida .= "<td width=\"5%\" align=\"center\"><img title=\"Procedimiento Realizado\" border=\"0\" src=\"".GetThemePath()."/images/ok.png\"></td>";
				
				//$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar', 'hc_os_solicitud_id'.$pfj => $hc_os_solicitud_id));
				//$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion2'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";				
				
				$this->salida .= "<td width=\"5%\" align=\"center\"><input type=\"checkbox\" name=\"SeleccionElimina".$this->frmPrefijo."[".$codigo."]\" value=\"$procedimiento\"></td>";
				$this->salida.="</tr>";        
        
        if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo]){
          $this->salida .= "         <tr class=\"modulo_list_claro\"><td colspan=\"5\">";    
          $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td width=\"10%\">CODIGO</td>";
          $this->salida.="<td>PROCEDIMIENTO</td>";      
          $this->salida.="</tr>";        
          foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo] as $pro=>$des){
            $this->salida.="<tr class=\"modulo_list_oscuro\">";
            $this->salida.="<td width=\"20%\">".$pro."</td>";
            $this->salida.="<td>".$des."</td>";        
            $this->salida.="</tr>";
          }        
          $this->salida.="</table>";
          $this->salida .= "         </td></tr>";
        }
        
        
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
				$this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."</td>";
				$this->salida.="</tr>";				
				//$diag =$this->Diagnosticos_Solicitados($vector1[$i][hc_os_solicitud_id]);
				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo]){
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"15%\">Diagnosticos Pre-QX</td>";
					$this->salida.="<td colspan = 4 width=\"65%\">";
					$this->salida.="<table width=\"100%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"10%\">PRIMARIO</td>";
					$this->salida.="<td width=\"10%\">TIPO DX</td>";
					$this->salida.="<td width=\"8%\">CODIGO</td>";
					$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
					$this->salida.="<td width=\"7%\">ELIMINAR</td>";
					$this->salida.="</tr>";
					
					foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag){
						if(empty($codiag_uno)){$codiag_uno=$codigoDiagnostico;}
						
						foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
							$this->salida.="<tr class=\"$estilo\">";
							if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico){
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
							}else{								
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
							}
							if($tipoDiagnostico == '1'){
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
							}elseif($tipoDiagnostico == '2'){
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
							}else{
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
							}
							$this->salida.="<td align=\"center\" width=\"8%\">".$codigoDiagnostico."</td>";
							$this->salida.="<td align=\"justify\" width=\"60%\">".$nombreDiag."</td>";																					
							$this->salida.="<tr>";
						}				
					}
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"center\" colspan=\"5\" valign=\"top\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
					$this->salida.="</tr>";		
					$this->salida.="</table>";
					$this->salida .="</td>" ;
					$this->salida.="</tr>";	
				}
				$i++;               				
			}			 
		}				
		$this->salida.= "        <tr class=\"$estilo\"><td colspan=\"5\" align=\"right\">";			
		$this->salida.= "        <input type=\"submit\" type=\"submit\" value=\"Eliminar Procedimientos\" name=\"EliminarProc".$this->frmPrefijo."\" class=\"input-submit\">";
		$this->salida.= "        </td></tr>";		
			
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3']){
			
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">PROCEDIMIENTOS ADICIONADOS</td>";
			$this->salida.="</tr>";	
			
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'] as $codigo=>$procedimiento){
						
				if( $i % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$row=4;					
				$this->salida.="  <td align=\"center\" width=\"9%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"52%\">$procedimiento</td>";								
				$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmEditarEspecificaionProc','cargo'.$this->frmPrefijo=>$codigo,'descripcion'.$this->frmPrefijo=>$procedimiento));
				$this->salida.="  <td align=\"center\" width=\"6%\"><a href='javascript:CargarAccion(\"$accion1\")'><img title=\"Modificar\" src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
				$this->salida .= "<td width=\"5%\" align=\"center\"><img title=\"Procedimiento Realizado\" border=\"0\" src=\"".GetThemePath()."/images/ok.png\"></td>";
				
				$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'EliminaProcedimientoVec3','cargo'.$this->frmPrefijo=>$codigo));
				$this->salida.="  <td align=\"center\" width=\"6%\"><a href='javascript:CargarAccion(\"$accion2\")'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";							
				
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
				$this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."</td>";
				$this->salida.="</tr>";				
				//$diag =$this->Diagnosticos_Solicitados($vector1[$i][hc_os_solicitud_id]);

				if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo]){
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"15%\">Diagnosticos Pre-QX</td>";
					$this->salida.="<td colspan = 4 width=\"65%\">";
					$this->salida.="<table width=\"100%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"10%\">PRIMARIO</td>";
					$this->salida.="<td width=\"10%\">TIPO DX</td>";
					$this->salida.="<td width=\"8%\">CODIGO</td>";
					$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";					
					$this->salida.="</tr>";
					
					foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag){
						if(empty($codiag_uno)){$codiag_uno=$codigoDiagnostico;}
						
						foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
							$this->salida.="<tr class=\"$estilo\">";
							if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico){
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
							}else{								
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
							}
							if($tipoDiagnostico == '1'){
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
							}elseif($tipoDiagnostico == '2'){
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
							}else{
								$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
							}	
							$this->salida.="<td align=\"center\" width=\"8%\">".$codigoDiagnostico."</td>";
							$this->salida.="<td align=\"justify\" width=\"60%\">".$nombreDiag."</td>";													
							$this->salida.="<tr>";
						}				
					}
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"center\" colspan=\"5\" valign=\"top\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
					$this->salida.="</tr>";
		
					$this->salida.="</table>";
					$this->salida .="</td>" ;
					$this->salida.="</tr>";	
				}
				$i++;
			}										
		}	 
		$this->salida.="</table>";
		
		$this->salida.= "        <table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida.= "        <tr><td align=\"center\">";
		$this->salida.= "        <input type=\"submit\" type=\"submit\" value=\"Adicionar Procedimiento\" name=\"AdicionarProc".$this->frmPrefijo."\" class=\"input-submit\">";
		//$this->salida.= "        <input type=\"submit\" type=\"submit\" value=\"Eliminar Procedimientos\" name=\"EliminarProc".$this->frmPrefijo."\" class=\"input-submit\">";
		$this->salida.= "        </td></tr>";
		$this->salida.= "        </table>";		
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2']){
			$con=0;
			$this->salida.= "          <BR><table border=\"0\" width=\"98%\" align=\"center\">";
			$this->salida .= "         <tr class=\"hc_table_submodulo_list_title\"><td colspan=\"4\">PROCEDIMIENTOS NO REALIZADOS Y JUSTIFICACION</td></tr>";
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'] as $codigo=>$procedimiento){				
				$this->salida .= "<tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida .= "<td width=\"10%\">".$codigo."</td>";
				$this->salida .= "<td>".$procedimiento."</td>";
				if($con==0){
					$this->salida .= "<td align=\"center\" rowspan=\"".sizeof($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'])."\">";
					$this->salida .= "<textarea name=\"justificacion".$this->frmPrefijo."\" cols=\"50\" rows=\"3\" class=\"textarea\">".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['justificacion']."</textarea>";
					$this->salida .= "</td>";				
					$con++;
				}
				$this->salida .= "</tr>";																
			}
			$this->salida.= "        </table>";
		}	
		$this->salida.= "    <BR><table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida.= "    <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">DIAGNOSTICOS</td></tr>";
		$this->salida.= "    <tr class=\"modulo_table_title\">";
		$this->salida.= "    <td width=\"15%\" align=\"center\">&nbsp;</td>";
		$this->salida.= "    <td width=\"15%\" align=\"center\">TIPO DX</td>";
		$this->salida.= "    <td width=\"15%\" align=\"center\">CODIGO</td>";
		$this->salida.= "    <td align=\"center\">DIAGNOSTICO</td>";
		$this->salida.= "    <td width=\"15%\" align=\"center\">&nbsp;</td>";
		$this->salida.= "    </tr>";
		/*$this->salida.= "     <tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.= "     <td>PRE-QUIRURGICO</td>";		
		if(!$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PRE_QUIRURGICO']){
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
		}else{
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PRE_QUIRURGICO'] as $codigo=>$vect){
				foreach($vect as $tipo=>$diagnostico){
					if($tipo == '1'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
					}elseif($tipo == '2'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
					}else{
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
					}
					$this->salida.= "     <td>$codigo</td>";
					$this->salida.= "     <td>$diagnostico</td>";
				}	
			}		
		}	
		$this->salida.= "     <td align=\"center\"><input type=\"submit\" name=\"BuscarPreQX".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
		$this->salida.= "    </tr>";
		*/
    $this->salida.= "     <tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.= "     <td>POST-QUIRURGICO</td>";
		if(!$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO']){
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
		}else{
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'] as $codigo=>$vect){
				foreach($vect as $tipo=>$diagnostico){
					if($tipo == '1'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
					}elseif($tipo == '2'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
					}else{
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
					}
					$this->salida.= "     <td>$codigo</td>";
					$this->salida.= "     <td>$diagnostico</td>";
				}	
			}		
		}	
		$this->salida.= "     <td align=\"center\"><input type=\"submit\" name=\"BuscarPostQX".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
		$this->salida.= "    </tr>";
		$this->salida.= "     <tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.= "     <td>COMPLICACION</td>";
		if(!$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION']){
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
		}else{
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'] as $codigo=>$vect){
				foreach($vect as $tipo=>$diagnostico){
					if($tipo == '1'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
					}elseif($tipo == '2'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
					}else{
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
					}					
					$this->salida.= "     <td>$codigo</td>";
					$this->salida.= "     <td>$diagnostico</td>";
				}	
			}
		}	
		$this->salida.= "     <td align=\"center\"><input type=\"submit\" name=\"BuscarComplicacion".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
		$this->salida.= "    </tr>";		
		$this->salida.= "    </table>";		 		
		
		$this->salida.= "    <BR><table border=\"0\" width=\"98%\" align=\"center\">";
		$che='';
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']==1){$che='checked';}
		$this->salida.= "    <tr class=\"modulo_table_title\"><td align=\"center\">MATERIAL ENVIADO A PATOLOGIA&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"SelMaterialPat".$this->frmPrefijo."\" value=\"1\" $che></td></tr>";
		$this->salida.= "    <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "    <td><label class=\"label\">CLASE DE MATERIAL ENVIADO</label><BR><textarea name=\"MaterialPat".$this->frmPrefijo."\" class=\"textarea\" cols=\"80\" rows=\"3\">".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['MaterialPat']."</textarea></td>";
		$this->salida.= "    </tr>";			
		$this->salida.= "    </table><BR>";		 		
		$this->salida.= "    <BR><table border=\"0\" width=\"98%\" align=\"center\">";
		$che1='';
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']==1){$che1='checked';}
		$this->salida.= "    <tr class=\"modulo_table_title\"><td align=\"center\">CULTIVO ENVIADO&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"SelCultivo".$this->frmPrefijo."\" value=\"1\" $che1></td></tr>";
		$this->salida.= "    <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "    <td><label class=\"label\">DESCRIPCION DEL CULTIVO</label><BR><textarea name=\"Cultivo".$this->frmPrefijo."\" class=\"textarea\" cols=\"80\" rows=\"3\">".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo']."</textarea></td>";
		$this->salida.= "    </tr>";			
		$this->salida.= "    </table><BR>";		 		
		
		$this->salida.= "    <BR><table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida.= "    <tr><td align=\"center\"><input type=\"submit\" name=\"GuardarNota".$this->frmPrefijo."\" value=\"Guardar Nota\" class=\"input-submit\"></td></tr>";
		$this->salida.= "    </table>";		 		
		
		/*$this->salida.= "        <BR><table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida.= "        <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">NUEVO PROCEDIMIENTO</td></tr>";
		$this->salida.= "        <tr>";
    $this->salida.= "        <td class=\"hc_table_submodulo_list_title\">TIPOS PROCEDIMIENTOS</td>";
		$this->salida .= "			 <td colspan=\"3\" class=\"hc_submodulo_list_oscuro\"><select name=\"tipoProcedimiento".$this->frmPrefijo."\" class=\"select\">";
	  $tiposProcedimientos=$this->tiposdeProcedimientos();
	  $this->MostrartiposdeProcedimientos($tiposProcedimientos,'False',$tipoProcedimiento.$this->frmPrefijo);
	  $this->salida .= "       </select></td>";
    $this->salida.= "        </tr>";
		$this->salida.= "        <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "        <td class=\"".$this->SetStyle("nuevoProcedimiento")."\">PROCEDIMIENTO</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"textnuevoProcedimiento".$this->frmPrefijo."\" maxlength=\"256\" size=\"70\" class=\"input-text\" value=\"".$_REQUEST['textnuevoProcedimiento'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "        <td class=\"hc_submodulo_list_oscuro\"><input type=\"text\" name=\"nuevoProcedimiento".$this->frmPrefijo."\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"".$_REQUEST['nuevoProcedimiento'.$this->frmPrefijo]."\" READONLY></td>";
		$this->salida.= "        <td class=\"hc_submodulo_list_oscuro\"><input type=\"submit\" name=\"buscarProc".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida.= "        </tr>";*/
		/*$this->salida.= "        <tr>";
    $this->salida.= "        <td class=\"hc_table_submodulo_list_title\">AYUDANTE</td>";
		$this->salida.= "        <td colspan=\"3\" class=\"hc_submodulo_list_oscuro\"><select name=\"ayudante".$this->frmPrefijo."\" class=\"select\">";
		$profesionales=$this->profesionalesAyudantes();
		$this->BuscarProfesionlesEspecialistas($profesionales,'False',$_REQUEST['ayudante'.$this->frmPrefijo]);
		$this->salida .= "        </select></td></tr>";
    $this->salida.= "        </tr>";
    $this->salida.= "        <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "        <td class=\"".$this->SetStyle("hallazgos")."\">HALLAZGOS</td>";
		$this->salida.= "        <td colspan=\"3\" class=\"hc_submodulo_list_oscuro\"><textarea name=\"hallazgos".$this->frmPrefijo."\" class=\"textarea\" cols=\"80\" rows=\"3\">".$_REQUEST['hallazgos'.$this->frmPrefijo]."</textarea></td>";
    $this->salida.= "        </tr>";
		$this->salida.= "        <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "        <td class=\"".$this->SetStyle("descripcionQuirugica")."\">DESCRIPCION QUIRURGICA</td>";
		$this->salida.= "        <td colspan=\"3\" class=\"hc_submodulo_list_oscuro\"><textarea name=\"descripcionQuirugica".$this->frmPrefijo."\" class=\"textarea\" cols=\"80\" rows=\"3\">".$_REQUEST['descripcionQuirugica'.$this->frmPrefijo]."</textarea></td>";
    $this->salida.= "        </tr>";
		$this->salida.= "        <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "        <td colspan=\"4\">";
		//$this->salida.= "        <input type=\"submit\" class=\"input-submit\" value=\"CANCELAR\" name=\"CancelarPro".$this->frmPrefijo."\">";
		$this->salida.= "        <input type=\"submit\" class=\"input-submit\" value=\"SELECCIONAR\" name=\"GuardarPro".$this->frmPrefijo."\">";
		$this->salida.= "        </td>";
    $this->salida.= "        </tr>";*/
		//$this->salida.= "        </table>";
		$this->salida.= " </form>";
		/*$datosAyundantes=$this->DatosAyundantesCumplimiento();
		if($datosAyundantes){
		  $this->salida .= "<BR><table border=\"0\" width=\"90%\" align=\"center\">";
		  $this->salida .= "<tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">AYUDATES</td></tr>";
			for($i=0;$i<sizeof($datosAyundantes);$i++){
				$this->salida .= "<tr>";
				$this->salida .= "<td class=\"hc_table_submodulo_list_title\">AYUDANTE</td>";
				$this->salida .= "<td class=\"hc_submodulo_list_oscuro\">".$datosAyundantes[$i]['tipo_id_ayudante']." ".$datosAyundantes[$i]['ayudante_id']."&nbsp&nbsp&nbsp&nbsp;".$datosAyundantes[$i]['nombre']."</td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "</table><BR>";
    }
		$this->salida.= "     <BR><table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida.= "     <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
		$this->salida.= "     <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "     <td class=\"".$this->SetStyle("cargo")."\">POST-QUIRURGICO</td><td><input type=\"text\" name=\"cargo\" maxlength=\"256\" size=\"70\" class=\"input-text\" value=\"$cargo\" READONLY></td>";
		$this->salida.= "     <td><input type=\"text\" name=\"'".$this->frmPrefijo."codigo'\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigo\" READONLY></td>";
		$this->salida.= "     <td><input type=\"button\" name=\"buscar\" value=\"BUSCAR\" onclick=\"abrirVentanaDiagnostico(this.name)\" class=\"input-submit\"></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "     <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "     <td class=\"".$this->SetStyle("cargo1")."\">COMPLICACION</td><td><input type=\"text\" name=\"cargo1\" maxlength=\"256\" size=\"70\" class=\"input-text\" value=\"$cargo1\" READONLY></td>";
		$this->salida.= "     <td><input type=\"text\" name=\"'".$this->frmPrefijo."codigo1'\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigo1\" READONLY></td>";
		$this->salida.= "     <td><input type=\"button\" name=\"buscar1\" value=\"BUSCAR\" onclick=\"abrirVentanaDiagnostico(this.name)\" class=\"input-submit\"></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "     </table>";
    $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .="     <tr class=\"modulo_table_title\"><td align=\"center\">HALLAZGOS</td></tr>";
		$this->salida.= "     <tr class=\"hc_table_submodulo_list_title\"><td align=\"center\"><textarea rows=\"3\" cols=\"65\" name=\"'".$this->frmPrefijo."hallazgos'\"></textarea></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "     </table>";
		$this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .="     <tr class=\"modulo_table_title\"><td align=\"center\">DESCRIPCION TECNICA QUIRURGICA</td></tr>";
		$this->salida.= "     <tr class=\"hc_table_submodulo_list_title\"><td align=\"center\"><textarea rows=\"3\" cols=\"65\" name=\"'".$this->frmPrefijo."tecnica'\"></textarea></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "     </table><BR>";
		$datosProcedimientos=$this->DatosProcedimientosCumplimiento();
		if($datosProcedimientos){
		  $this->salida .= "<BR><table border=\"0\" width=\"90%\" align=\"center\">";
		  $this->salida .= "<tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">PROCEDIMIENTOS REALIZADOS</td></tr>";
			$this->salida .= "<td class=\"hc_table_submodulo_list_title\" width=\"10%\">CODIGO</td>";
			$this->salida .= "<td class=\"hc_table_submodulo_list_title\" width=\"65%\">PROCEDIMIENTO</td>";
			$this->salida .= "<td class=\"hc_table_submodulo_list_title\">VIA</td>";
			$this->salida .= "<td class=\"hc_table_submodulo_list_title\" width=\"5%\">&nbsp;</td>";
      $y=0;
			for($i=0;$i<sizeof($datosProcedimientos);$i++){
			  if($y % 2){$estilo='hc_submodulo_list_oscuro';}else{$estilo='hc_submodulo_list_claro';}
				$this->salida .= "<tr class=\"$estilo\">";
				$this->salida .= "<td>".$datosProcedimientos[$i]['procedimiento_qx']."</td>";
				$this->salida .= "<td>".$datosProcedimientos[$i]['descripcion']."</td>";
				$this->salida .= "<td>".$datosProcedimientos[$i]['via']."</td>";
				$this->salida .= "<td>&nbsp;</td>";
				$this->salida .= "</tr>";
				$y++;
			}
			$this->salida .= "</table><BR>";
    }*/
		$this->salida .= ThemeCerrarTablaSubModulo();
    return true;
	}
	
	function frmForma_BuscarDiagnosticos($tipoDiag){
		
		$pfj=$this->frmPrefijo;
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
		$this->salida= ThemeAbrirTablaSubModulo('BUSQUEDA DIAGNOSTICO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmBuscarDiagnosticosPost',"tipoDiag".$this->frmPrefijo=>$tipoDiag));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";

		$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = codigo".$this->frmPrefijo." value=\"".$_REQUEST['codigo'.$this->frmPrefijo]."\"></td>" ;
		//la misma pero con el value $this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'  value =\"".$_REQUEST['codigo'.$pfj]."\"    ></td>" ;

		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = \"diagnostico".$this->frmPrefijo."\"   value =\"".$_REQUEST['diagnostico'.$this->frmPrefijo]."\"></td>" ;

		$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar".$this->frmPrefijo."\" type=\"submit\" value=\"BUSQUEDA\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$vectorD=$this->Busqueda_Avanzada_Diagnosticos($_REQUEST['codigo'.$this->frmPrefijo],$_REQUEST['diagnostico'.$this->frmPrefijo]);
    if ($vectorD){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"8%\">CODIGO</td>";
			$this->salida.="  <td width=\"60%\">DIAGNOSTICO</td>";
			$this->salida.="  <td width=\"17%\">TIPO DX</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++){
			
				$codigo          = $vectorD[$i][diagnostico_id];
				$diagnostico    = $vectorD[$i][diagnostico_nombre];

				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";

				$this->salida.="  <td align=\"center\" width=\"8%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"60%\">$diagnostico</td>";
				$this->salida.="<td align=\"center\" width=\"17%\">";
				$this->salida.="<input type=\"radio\" name=\"dx".$this->frmPrefijo."[$codigo]\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx".$this->frmPrefijo."[$codigo]\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx".$this->frmPrefijo."[$codigo]\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
				$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= \"opD".$this->frmPrefijo."[]\" value = \"".$codigo."||//".$diagnostico."\"></td>";
				$this->salida.="</tr>";
      }
      $this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
               
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar".$this->frmPrefijo."\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmBuscarDiagnosticosPost',"buscar".$this->frmPrefijo=>'BUSCAR',"tipoDiag".$this->frmPrefijo=>$tipoDiag,"codigo".$pfj=>$_REQUEST['codigo'.$pfj],'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
			
    }
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr><td align=\"center\"><input type=\"submit\" name=\"Volver".$this->frmPrefijo."\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
		$this->salida.="</table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
    return true;
	}
	
	
	function frmForma_Modificar_Observacion($cargo,$descripcion){
		$pfj=$this->frmPrefijo;
    $opcionesProcedimientos=$this->BuscarOpcionesProcedimientos($cargo);    
    
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
    
    $this->salida= ThemeAbrirTablaSubModulo('MODIFICACION DEL PROCEDIMIENTO'); 
    $this->salida.="<script language='javascript'>\n";
    $this->salida.="  var rem=\"\";\n";
    $this->salida.="  function abrirVentana(nom,frm){\n";
    $this->salida.="    var nombre=\"PROCEDIMIENTOS QUIRURGICOS\";\n";
    $this->salida.="    var valortipo=frm.tipoProcedimiento.value;";
    $this->salida.="    if(nom=='buscar2'){\n";
    $this->salida.="      var tipo=\"procedimientosQX\";\n";
    $this->salida.="      var alias=\"codigos\";\n";
    $this->salida.="    }\n";
    $this->salida.="    var str =\"width=450,height=250,resizable=no,status=no,scrollbars=yes\";\n";
    $this->salida.="    var url2 ='$RUTA'+tipo+'&alias='+alias+'&key=cargo,descripcion'+'&tipoProcedimiento='+valortipo;\n";
    $this->salida.="    rem = window.open(url2, nombre, str);}\n";    
    $this->salida .= "  function Iniciar()\n";
    $this->salida .= "  {\n";        
    $this->salida .= "    document.getElementById('titulo').innerHTML = '<center>OPCIONES PROCEDIMIENTOS</center>';\n";
    $this->salida .= "    document.getElementById('error').innerHTML = '';\n";                
    $this->salida .= "    contenedor = 'd2Container';\n";
    $this->salida .= "    titulo = 'titulo';\n";
    $this->salida .= "    ele = xGetElementById('d2Container');\n";
    $this->salida .= "    xResizeTo(ele,500, 'auto');\n";
    $this->salida .= "    xMoveTo(ele, xClientWidth()/3, xScrollTop()+24);\n";
    $this->salida .= "    ele = xGetElementById('titulo');\n";
    $this->salida .= "    xResizeTo(ele,480, 20);\n";
    $this->salida .= "    xMoveTo(ele, 0, 0);\n";
    $this->salida .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $this->salida .= "    ele = xGetElementById('cerrar');\n";
    $this->salida .= "    xResizeTo(ele,20, 20);\n";
    $this->salida .= "    xMoveTo(ele, 480, 0);\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function myOnDragStart(ele, mx, my)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    window.status = '';\n";
    $this->salida .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
    $this->salida .= "    else xZIndex(ele, hiZ++);\n";
    $this->salida .= "    ele.myTotalMX = 0;\n";
    $this->salida .= "    ele.myTotalMY = 0;\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function myOnDrag(ele, mdx, mdy)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    if (ele.id == titulo) {\n";
    $this->salida .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
    $this->salida .= "    }\n";
    $this->salida .= "    else {\n";
    $this->salida .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
    $this->salida .= "    }  \n";
    $this->salida .= "    ele.myTotalMX += mdx;\n";
    $this->salida .= "    ele.myTotalMY += mdy;\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function myOnDragEnd(ele, mx, my)\n";
    $this->salida .= "  {\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function MostrarSpan(Seccion)\n";
    $this->salida .= "  { \n";
    $this->salida .= "    e = xGetElementById(Seccion);\n";
    $this->salida .= "    e.style.display = \"\";\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function Cerrar()\n";
    $this->salida .= "  { \n";
    $this->salida .= "    e = xGetElementById('d2Container');\n";
    $this->salida .= "    e.style.display = \"none\";\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function MostrarVentana(Seccion)\n";
    $this->salida .= "  { \n";
    $this->salida .= "    e = xGetElementById(Seccion);\n";
    $this->salida .= "    e.style.display = \"block\";\n";
    $this->salida .= "  }\n";          
    $this->salida.="</script>\n";
    
		
    $ventana.= "  <div id='d2Container' class='d2Container' style=\"display:none\">\n";
    $ventana.= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;\"></div>\n";
    $ventana.= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $ventana.= "  <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
    $ventana.= "  <div id='d2Contents'>\n";
    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmModificarProdedimiento',"cargo".$this->frmPrefijo=>$cargo,"descripcion".$this->frmPrefijo=>$descripcion,"modify_procedimiento_opcion".$this->frmPrefijo=>1));
    $ventana.= "      <form name=\"formaVentana\" action=\"$accion\" method=\"post\">";                     
    if($opcionesProcedimientos){
      $ventana.= "    <table align=\"center\" width=\"98%\">";  
      $ventana.="       <tr class=\"modulo_table_list_title\"><td colspan=\"3\">$descripcion</td></tr>";
      $ventana.="       <tr class=\"modulo_table_list_title\">";
      $ventana.="       <td width=\"5%\">&nbsp;</td>";
      $ventana.="       <td width=\"20%\">CODIGO</td>";
      $ventana.="       <td>PROCEDIMIENTO</td>";
      $ventana.="       </tr>";
      $cont=sizeof($opcionesProcedimientos);              
      for($i=0;$i<$cont;$i++){          
        $ventana.="   <tr class=\"modulo_list_claro\">";
        $ventana.="   <td width=\"5%\"><input type=\"checkbox\" name=\"seleccion".$this->frmPrefijo."[]\" align=\"center\" value=\"".$opcionesProcedimientos[$i]['procedimiento_opcion']."\"></td>";
        $ventana.="   <td width=\"20%\">".$opcionesProcedimientos[$i]['procedimiento_opcion']."</td>";      
        $ventana.="   <td>".$opcionesProcedimientos[$i]['descripcion']."</td>";      
        $ventana.="   </tr>";                                        
      }     
      $ventana.="     <tr><td align=\"center\" class=\"input-submit\" colspan=\"3\"><input type=\"submit\" name=\"insertarr\" value=\"INSERTAR\"></td></tr>";         
      $ventana.="     </table>";      
    }
    $ventana.="       </form>";
    $ventana.="     </div>";
    $ventana.="</div>";        
    $this->salida.=$ventana;
    
    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmModificarProdedimiento',"cargo".$this->frmPrefijo=>$cargo,"descripcion".$this->frmPrefijo=>$descripcion));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"3\">OBSERVACION</td>";
		$this->salida.="</tr>";
		
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td width=\"15%\">CARGO</td>";
		$this->salida.="  <td width=\"65%\">DESCRIPCION</td>";
    $this->salida.="  <td width=\"5%\">&nbsp;</td>";
		$this->salida.="</tr>";
		
		if( $i % 2){ $estilo='modulo_list_claro';}
		else {$estilo='modulo_list_oscuro';}
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="  <td align=\"center\" width=\"15%\">$cargo</td>";
		$this->salida.="  <td align=\"left\" width=\"65%\">$descripcion</td>";        
    $opcProcedimientos=$this->ComprobarOpcionesProcedimientosCups();
    if($opcProcedimientos==1){
      $this->salida.= "<td width=\"5%\" align=\"center\"><a href=\"javascript:Iniciar();MostrarVentana('d2Container')\"><img border=\"0\" src=\"".GetThemePath()."/images/pcargos.png\" title=\"Procedimientos Opciones\"></a></td>";            
    }else{
      $this->salida.="  <td width=\"5%\">&nbsp;</td>";
    }
		$this->salida.="</tr>";
    
    $this->salida.="<tr class=\"modulo_list_claro\"><td id=\"MostrarProcedimientoOpc\" colspan=\"3\">";    
    if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$cargo]){          
      $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
      $this->salida.="<tr class=\"modulo_table_list_title\">";
      $this->salida.="<td width=\"10%\">CODIGO</td>";
      $this->salida.="<td>PROCEDIMIENTO</td>";      
      $this->salida.="<td width=\"5%\">&nbsp;</td>";
      $this->salida.="</tr>";        
      foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$cargo] as $pro=>$des){
        $this->salida.="<tr class=\"modulo_list_oscuro\">";
        $this->salida.="<td width=\"20%\">".$pro."</td>";
        $this->salida.="<td>".$des."</td>"; 
        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmModificarProdedimiento',"cargo".$this->frmPrefijo=>$cargo,"descripcion".$this->frmPrefijo=>$descripcion,"procedimiento_opcion".$this->frmPrefijo=>$pro));
        $this->salida.="<td align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\");\"></a></td>";                         
        $this->salida.="</tr>";
      }        
      $this->salida.="</table>";      
    }
    $this->salida.="</td></tr>";
    
    
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="  <td align=\"center\" width=\"15%\">OBSERVACION</td>";
		$this->salida .="<td colspan=\"2\" width=\"65%\" align='center'><textarea class='textarea' name = \"obs".$this->frmPrefijo."\" cols = 100 rows = 3>".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$cargo]."</textarea></td>" ;
		$this->salida.="</tr>";

		//$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$cargo]){
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"center\" width=\"15%\">DIAGNOSTICOS PRE-QX</td>";
			$this->salida.="<td colspan=\"2\" width=\"65%\">";
			$this->salida.="<table width=\"100%\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"10%\">PRIMARIO</td>";
      $this->salida.="<td width=\"10%\">TIPO DX</td>";
			$this->salida.="<td width=\"8%\">CODIGO</td>";
			$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
			$this->salida.="<td width=\"7%\">ELIMINAR</td>";
			$this->salida.="</tr>";
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$cargo] as $codigoDiagnostico=>$vectorDiag){
				if(empty($codiag_uno)){$codiag_uno=$codigoDiagnostico;}
				
				foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
					$this->salida.="<tr class=\"$estilo\">";
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$cargo]==$codigoDiagnostico){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
					}else{
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmModificarProdedimiento',"cargo".$this->frmPrefijo=>$cargo,"descripcion".$this->frmPrefijo=>$descripcion,"CambioDiagPrincipal".$this->frmPrefijo=>'Cambio','codiag'.$this->frmPrefijo=>$codigoDiagnostico));
						$this->salida.="<td align=\"center\" width=\"10%\"><a href='$accion'><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></a></td>";
					}
					if($tipoDiagnostico == '1'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
					}elseif($tipoDiagnostico == '2'){
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
					}else{
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
					}
					$this->salida.="<td align=\"center\" width=\"8%\">".$codigoDiagnostico."</td>";
					$this->salida.="<td align=\"justify\" width=\"60%\">".$nombreDiag."</td>";					
					$accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmModificarProdedimiento',"cargo".$this->frmPrefijo=>$cargo,"descripcion".$this->frmPrefijo=>$descripcion,"EliminacionDiagnostico".$this->frmPrefijo=>'Cambio','codiag'.$this->frmPrefijo=>$codigoDiagnostico,'codiag_uno'.$this->frmPrefijo=>$codiag_uno));					
					$this->salida.="<td align=\"center\" width=\"7%\"><a href='$accionE'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
					$this->salida.="<tr>";
				}				
			}				
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" colspan=\"5\" valign=\"top\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";

			$this->salida.="</table>";
			$this->salida .="</td>" ;
			$this->salida.="</tr>";
    }					
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida .= "<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar".$this->frmPrefijo."\" type=\"submit\" value=\"GUARDAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida .= "</form>";
				
		
		
		
    

		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'insertar_varios_diagnosticos',"cargo".$this->frmPrefijo=>$cargo,"descripcion".$this->frmPrefijo=>$descripcion));
		
		$this->salida .= "<form name=\"formades".$this->frmPrefijo."\" action=\"$accionI\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";

		$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = codigo".$this->frmPrefijo." value=\"".$_REQUEST['codigo'.$this->frmPrefijo]."\"></td>" ;
		//la misma pero con el value $this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'  value =\"".$_REQUEST['codigo'.$pfj]."\"    ></td>" ;

		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = \"diagnostico".$this->frmPrefijo."\"   value =\"".$_REQUEST['diagnostico'.$this->frmPrefijo]."\"></td>" ;

		$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar".$this->frmPrefijo."\" type=\"submit\" value=\"BUSQUEDA\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$vectorD=$this->Busqueda_Avanzada_Diagnosticos($_REQUEST['codigo'.$this->frmPrefijo],$_REQUEST['diagnostico'.$this->frmPrefijo]);
    if ($vectorD){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"8%\">CODIGO</td>";
			$this->salida.="  <td width=\"60%\">DIAGNOSTICO</td>";
			$this->salida.="  <td width=\"17%\">TIPO DX</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++){
			
				$codigo          = $vectorD[$i][diagnostico_id];
				$diagnostico    = $vectorD[$i][diagnostico_nombre];

				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";

				$this->salida.="  <td align=\"center\" width=\"8%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"60%\">$diagnostico</td>";
				$this->salida.="<td align=\"center\" width=\"17%\">";
				$this->salida.="<input type=\"radio\" name=\"dx".$this->frmPrefijo."[$cargo][$codigo]\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx".$this->frmPrefijo."[$cargo][$codigo]\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx".$this->frmPrefijo."[$cargo][$codigo]\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
				$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= \"opD".$this->frmPrefijo."[$cargo][$codigo]\" value = \"$diagnostico\"></td>";
				$this->salida.="</tr>";
      }
      $this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
               
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar".$this->frmPrefijo."\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'insertar_varios_diagnosticos',"buscar".$this->frmPrefijo=>'BUSCAR',"cargo".$this->frmPrefijo=>$cargo,"descripcion".$this->frmPrefijo=>$descripcion));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);

			
    }
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr><td align=\"center\"><input type=\"submit\" name=\"Volver".$this->frmPrefijo."\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
		$this->salida.="</table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
    return true;
	}
	
	
					
	function frmForma_ProcedimientosQX(){
		
		$pfj=$this->frmPrefijo;
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
		$this->salida= ThemeAbrirTablaSubModulo('PROCEDIMIENTOS QUIRURGICOS');
		/*$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],
		'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj]));*/
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'FrmBuscadorProcedimientos'));
		$this->salida .= "<form name=\"formades".$this->frmPrefijo."\" action=\"$accion\" method=\"post\">";		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">TIPO</td>";		
		$this->salida.="<td colspan=\"4\" align = left >";
		$this->salida.="<select name=\"tipoProcedimiento".$this->frmPrefijo."\" class=\"select\">";
	  $tiposProcedimientos=$this->tiposdeProcedimientos();
	  $this->MostrartiposdeProcedimientos($tiposProcedimientos,'False',$_REQUEST['tipoProcedimiento'.$this->frmPrefijo]);
	  $this->salida .= "</select>";		
		/*$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
		$this->salida.="<option value = '001' selected>Todos</option>";
		if (($_REQUEST['criterio1'.$pfj])  == '002'){
			$this->salida.="<option value = '002' selected>Frecuentes</option>";
		}else{
			$this->salida.="<option value = '002' >Frecuentes</option>";
		}
		$categoria = $this->tiposdeProcedimientos();
		for($i=0;$i<sizeof($categoria);$i++){
			$apoyod_tipo_id = $categoria[$i][apoyod_tipo_id];
			if (($_REQUEST['criterio1'.$pfj])  != $apoyod_tipo_id){
				$this->salida.="<option value = $apoyod_tipo_id>".$categoria[$i][descripcion]."</option>";
			}else{
				$this->salida.="<option value = $apoyod_tipo_id selected >".$categoria[$i][descripcion]."</option>";
			}
		}
		$this->salida.="</select>";
		*/
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"6%\">CARGO:</td>";
		$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name =\"cargo".$this->frmPrefijo."\"  value =\"".$_REQUEST['cargo'.$this->frmPrefijo]."\"    ></td>" ;

		$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
		$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = \"descripcion".$this->frmPrefijo."\"   value =\"".$_REQUEST['descripcion'.$this->frmPrefijo]."\"        ></td>" ;

		$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar".$this->frmPrefijo."\" type=\"submit\" value=\"BUSQUEDA\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";		
		$vectorA=$this->RegistrosCargosCups($_REQUEST['tipoProcedimiento'.$this->frmPrefijo],$_REQUEST['cargo'.$this->frmPrefijo],$_REQUEST['descripcion'.$this->frmPrefijo]);
		if($vectorA){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"30%\">TIPO</td>";
			$this->salida.="  <td width=\"10%\">CARGO</td>";
			$this->salida.="  <td>DESCRIPCION</td>";
			$this->salida.="  <td width=\"3%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorA);$i++){
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">".$vectorA[$i][tipo]."</td>";
				$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorA[$i][cargo]."</td>";
				$this->salida.="  <td align=\"left\" width=\"50%\">".$vectorA[$i][descripcion]."</td>";
				$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"op".$this->frmPrefijo."[".$vectorA[$i][cargo]."]\" value = \"".$vectorA[$i][descripcion]."\"></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar".$this->frmPrefijo."\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";			
			$Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmBuscadorProcedimientos',"buscar".$this->frmPrefijo=>'1',"cargo".$this->frmPrefijo=>$_REQUEST['cargo'.$this->frmPrefijo],"descripcion".$this->frmPrefijo=>$_REQUEST['descripcion'.$this->frmPrefijo],"tipoProcedimiento".$this->frmPrefijo=>$_REQUEST['tipoProcedimiento'.$this->frmPrefijo]));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
		}
		$this->salida.="<BR><table align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr><td align=\"center\"><input class=\"input-submit\" name=\"volver".$this->frmPrefijo."\" type=\"submit\" value=\"VOLVER\"></td></tr>";
		$this->salida.="</table>";			
		$this->salida .= "</form>";

		//BOTON DEVOLVER
		//$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		//$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		//$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
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
	* Funcion que se encarga de separar la fecha del formato timestamp
	* @return array
	*/
	function FechaStamp($fecha){

    if($fecha){
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
    }
  }
	/**
	* Funcion que se encarga de separar la hora del formato timestamp
	* @return array
	*/
  function HoraStamp($hora){

   $hor = strtok ($hora," ");
   for($l=0;$l<4;$l++){

		 $time[$l]=$hor;
     $hor = strtok (":");

	 }
   return  $time[1].":".$time[2].":".$time[3];
 }

 /**
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function BuscarProfesionlesEspecialistas($profesionales,$Seleccionado='False',$Profesionales=''){

		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($profesionales);$i++){
				  $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
					if($value==$Profesionales){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($profesionales);$i++){
			    $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
				  if($value==$Profesionales){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

 function BuscadorDiagnostico($codigoDes,$descripcionDes){

    $this->salida  = ThemeAbrirTablaSubModulo('BUSQUEDA DE DIAGNOSTICOS');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionDiagnostico',"buscar".$this->frmPrefijo.""=>$_REQUEST['buscar'.$this->frmPrefijo],"buscar1".$this->frmPrefijo.""=>$_REQUEST['buscar1'.$this->frmPrefijo],
		"cargo".$this->frmPrefijo=>$_REQUEST['cargo'.$this->frmPrefijo],
		"codigo".$this->frmPrefijo=>$_REQUEST['codigo'.$this->frmPrefijo],
		"cargo1".$this->frmPrefijo=>$_REQUEST['cargo1'.$this->frmPrefijo],
		"codigo1".$this->frmPrefijo=>$_REQUEST['codigo1'.$this->frmPrefijo]));
		$this->salida .= "  <form name='formauno".$this->frmPrefijo."' action=$accion method='post'>";
    $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">DIAGNOSTICOS</td></tr>";
    $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "  <td width=\"10%\">CODIGO</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"6\" type=\"text\" name=\"codigoDes".$this->frmPrefijo."\" value=\"$codigoDes\"></td>";
		$this->salida .= "  <td width=\"13%\">DESCRIPCION</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"60\" type=\"text\" name=\"descripcionDes".$this->frmPrefijo."\" value=\"$descripcionDes\"></td>";
		$this->salida .= "  <td width=\"10%\"><input type=\"submit\" name=\"Buscar".$this->frmPrefijo."\" value=\"BUSCAR\"></td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
		$diagnosticos=$this->RegistrosDiagnosticos($codigoDes,$descripcionDes);
		if($diagnosticos){
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\">";
			$this->salida .= "  <td>CODIGO</td>";
			$this->salida .= "  <td>DESCRIPCION</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($diagnosticos);$i++){
			  if($y % 2){$estilo='hc_submodulo_list_claro';}else{$estilo='hc_submodulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
        $this->salida .= "  <td width=\"15%\">".$diagnosticos[$i]['diagnostico_id']."</td>";
			  $this->salida .= "  <td>".$diagnosticos[$i]['diagnostico_nombre']."</td>";
				$this->salida .= "  <td align=\"center\" width=\"5%\">";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionDiagnostico',"codigoDiagnostico".$this->frmPrefijo.""=>$diagnosticos[$i]['diagnostico_id'],"nombreDiagnostico".$this->frmPrefijo.""=>$diagnosticos[$i]['diagnostico_nombre'],
				"buscar".$this->frmPrefijo.""=>$_REQUEST['buscar'.$this->frmPrefijo],"buscar1".$this->frmPrefijo.""=>$_REQUEST['buscar1'.$this->frmPrefijo],"bandera".$this->frmPrefijo.""=>1,
				"cargo".$this->frmPrefijo=>$_REQUEST['cargo'.$this->frmPrefijo],
				"codigo".$this->frmPrefijo=>$_REQUEST['codigo'.$this->frmPrefijo],
				"cargo1".$this->frmPrefijo=>$_REQUEST['cargo1'.$this->frmPrefijo],
				"codigo1".$this->frmPrefijo=>$_REQUEST['codigo1'.$this->frmPrefijo]));
				$this->salida .= "  <a href=\"$accion\" class=\"link\"><b><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></b></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table><BR>";
			$this->salida .=$this->RetornarBarra(1);
		}
		$this->salida .= "  <BR><table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr><td class=\"input-submit\" align=\"center\"><input type=\"submit\" value=\"SALIR\" name=\"salir".$this->frmPrefijo."\"></td></tr>";
		$this->salida .= "  </table>";
    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
    return true;
  }

/**
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function MostrartiposdeProcedimientos($tiposProcedimientos,$Seleccionado='False',$tipoProcedimiento=''){

		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">----------Todos-------</option>";
				for($i=0;$i<sizeof($tiposProcedimientos);$i++){
				  $value=$tiposProcedimientos[$i]['tipo_cargo'].'/'.$tiposProcedimientos[$i]['grupo_tipo_cargo'];
					$titulo=$tiposProcedimientos[$i]['descripcion'];
					if($value==$tipoProcedimiento){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($tiposProcedimientos);$i++){
				  $value=$tiposProcedimientos[$i]['tipo_cargo'].'/'.$tiposProcedimientos[$i]['grupo_tipo_cargo'];
					$titulo=$tiposProcedimientos[$i]['descripcion'];
				  if($value==$tipoProcedimiento){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}



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
	function RetornarBarra($origen)//Barra paginadora de los planes clientes
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
		if($origen==1){
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Bucardiagnostico',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj],
		"fechainicio".$pfj=>$_REQUEST['fechainicio'.$pfj],"hora".$pfj=>$_REQUEST['hora'.$pfj],"minutos".$pfj=>$_REQUEST['minutos'.$pfj],
		"horadur".$pfj=>$_REQUEST['horadur'.$pfj],"minutosdur".$pfj=>$_REQUEST['minutosdur'.$pfj],
		"viaAcceso".$pfj=>$_REQUEST['viaAcceso'.$pfj],"tipoCirugia".$pfj=>$_REQUEST['tipoCirugia'.$pfj],
		"ambitoCirugia".$pfj=>$_REQUEST['ambitoCirugia'.$pfj],"finalidadCirugia".$pfj=>$_REQUEST['finalidadCirugia'.$pfj],
		"quirofano".$pfj=>$_REQUEST['quirofano'.$pfj],"cargo".$pfj=>$_REQUEST['cargo'.$pfj],"codigo".$pfj=>$_REQUEST['codigo'.$pfj],
		"cargo1".$pfj=>$_REQUEST['cargo1'.$pfj],"codigo1".$pfj=>$_REQUEST['codigo1'.$pfj],"descripcionDes".$pfj=>$_REQUEST['descripcionDes'.$pfj],
		"codigoDes".$pfj=>$_REQUEST['codigoDes'.$pfj],"buscar".$pfj=>$_REQUEST['buscar'.$pfj],"buscar1".$pfj=>$_REQUEST['buscar1'.$pfj]));
		}elseif($origen==2){
    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsercionProcedimientosNota',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'buscarProc'.$pfj=>1,'tipoProcedimiento'.$pfj=>$_REQUEST['tipoProcedimiento'.$pfj],
		'codigoPro'.$pfj=>$_REQUEST['codigoPro'.$pfj],
		'descripcionPro'.$pfj=>$_REQUEST['descripcionPro'.$pfj]));
		}
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


	function frmConsulta()
	{
		$datos=$this->ConsultaNotasOperatoriasRealizadasHis();
		if($datos){
		for($i=0;$i<sizeof($datos);$i++){
			$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">NOTA OPERATORIA</td></tr>";
			(list($fechaIn,$horaIn)=explode(' ',$datos[$i]['hora_inicio']));
			(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
			(list($hhIn,$mmIn)=explode(':',$horaIn));				
			(list($fechaFn,$horaFn)=explode(' ',$datos[$i]['hora_fin']));				
			(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
			(list($hhFn,$mmFn)=explode(':',$horaFn));
			$segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
			$Horas=(int)($segundos/60);				
			$Minutos=($segundos%60);
			$this->salida .= "  <tr>";			
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">FECHA INICIO</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$fechaIn." ".$hhIn.":".$mmIn."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">DURACION</td>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_submodulo_list_oscuro\">".str_pad($Horas,2,0,STR_PAD_LEFT).":".str_pad($Minutos,2,0,STR_PAD_LEFT)."&nbsp;&nbsp;&nbsp;(HH:mm)</td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">QUIROFANO</td>";
			$this->salida .= "  <td align=\"left\" colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['nom_quirofano']."</td>";
			$this->salida .= "  </tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">VIA ACCESO</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['via']."</td>";		
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['tipo']."</td>";		
			$this->salida.= "     </tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">AMBITO CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ambito']."</td>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">FINALIDAD CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['finalidad']."</td>";
			$this->salida.= "     </tr>";		
			$this->salida.= "  </table>";		
      $this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">PROFESIONALES</td></tr>";      
      $this->salida .= "  <tr>";      
      $this->salida .= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">ANESTESIOLOGO</td>";
      if($datos[$i]['anestesiologo']){     
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['anestesiologo']."</td>";       
      }else{
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }  
      $this->salida .= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">AYUDANTE</td>";      
      if($datos[$i]['ayudante']){     
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ayudante']."</td>";
      }else{
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      } 
      $this->salida .= "  </tr>";      
      $this->salida .= "    <tr>";      
      $this->salida .= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">INSTRUMENTADOR</td>";
      if($datos[$i]['instrumentador']){
        $this->salida .= "    <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['instrumentador']."</td>";    
      }else{
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }       
      $this->salida .= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">CIRCULANTE</td>";
      if($datos[$i]['circulante']){
        $this->salida .= "    <td class=\"hc_submodulo_list_oscuro\">".$datos[$i]['circulante']."</td>";   
      }else{
        $this->salida .= "  <td  class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }   
      $this->salida.= "     </tr>";      
      $this->salida.= "  </table>"; 	
			$this->salida.="	 <table  align=\"center\" border=\"0\" width=\"95%\">";
			$this->salida.="	 <tr class=\"modulo_table_title\">";
			$this->salida.="   <td align=\"center\" colspan=\"3\">PROCEDIMIENTOS REALIZADOS</td>";
			$this->salida.="	 </tr>";	
			$this->salida.="	 <tr class=\"hc_table_submodulo_list_title\">";			
			$this->salida.="  <td width=\"20%\">CARGO</td>";
			$this->salida.="  <td colspan=\"2\">DESCRIPCION</td>";			
			$this->salida.="	</tr>";
			$procedimientos=$this->ProcedimientosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id']);
			for($j=0;$j<sizeof($procedimientos);$j++){
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";				
				$this->salida.="  <td align=\"center\" width=\"20%\" rowspan=\"3\">".$procedimientos[$j]['procedimiento_qx']."</td>";
				$this->salida.="  <td align=\"left\" colspan=\"2\">".$procedimientos[$j]['descripcion']."</td>";											        
        
        $procedimientosOpc=$this->BuscarProcedimientosInsertadosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']); 
        $this->salida.="<tr class=\"modulo_list_claro\"><td id=\"MostrarProcedimientoOpc\" colspan=\"5\">";    
        if($procedimientosOpc){          
          $this->salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td width=\"10%\">CODIGO</td>";
          $this->salida.="<td>PROCEDIMIENTO</td>";                
          $this->salida.="</tr>";        
          for($m=0;$m<sizeof($procedimientosOpc);$m++){
            $this->salida.="<tr class=\"modulo_list_oscuro\">";
            $this->salida.="<td width=\"20%\">".$procedimientosOpc[$m]['procedimiento_opcion']."</td>";
            $this->salida.="<td>".$procedimientosOpc[$m]['descripcion']."</td>";             
            $this->salida.="</tr>";
          }        
          $this->salida.="</table>";      
        }
        $this->salida.="</td></tr>";
    
				$this->salida.="</tr>";	
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td class=\"hc_table_submodulo_list_title\" colspan = 1 align=\"left\" width=\"10%\">Observacion</td>";
				$this->salida.="  <td class=\"hc_submodulo_list_claro\" align=\"left\" width=\"64%\">".$procedimientos[$j]['observaciones']."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="	<td class=\"hc_table_submodulo_list_title\" align=\"center\" width=\"10%\">Diagnosticos Pre-QX</td>";
				$this->salida.="	<td colspan=\"2\" class=\"hc_submodulo_list_claro\">";				
				$diag =$this->Diagnosticos_ProcedimientosNO($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']);
				if($diag){					
					$this->salida.="<table width=\"100%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"10%\">PRIMARIO</td>";
					$this->salida.="<td width=\"10%\">TIPO DX</td>";
					$this->salida.="<td width=\"8%\">CODIGO</td>";
					$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";					
					$this->salida.="</tr>";					
					for($m=0;$m<sizeof($diag);$m++){							
						$this->salida.="<tr class=\"$estilo\">";
						if($diag[$m]['sw_principal']=='1'){
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
						}else{								
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
						}
						if($diag[$m]['tipo_diagnostico'] == '1'){
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
						}elseif($diag[$m]['tipo_diagnostico'] == '2'){
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
						}else{
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
						}
						$this->salida.="<td class=\"hc_submodulo_list_claro\" align=\"center\" width=\"8%\">".$diag[$m]['diagnostico_id']."</td>";
						$this->salida.="<td class=\"hc_submodulo_list_claro\" align=\"justify\" width=\"60%\">".$diag[$m]['diagnostico_nombre']."</td>";																					
						$this->salida.="<tr>";										
					}
					$this->salida.="</table>";					
				}				
			}
			$this->salida.="		</td></tr>";	
			$this->salida.= "		</table>";
			$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
			/*$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">PRE QX</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom2']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_pre_qx'] == '1'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_pre_qx'] == '2'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_pre_qx'] == '3'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}else{
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";	*/		
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">POST QX</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_post_qx'] == '1'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_post_qx'] == '2'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_post_qx'] == '3'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}else{
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";			
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">COMPLICACION</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom1']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_complicacion'] == '1'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_complicacion'] == '2'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_complicacion'] == '3'){		
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}else{
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";			
			$this->salida.= "</table>";			
			if($datos[$i]['descripcion_envio_patologico']){
				$this->salida .="<table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida.= "<tr>";
				if($datos[$i]['envio_patologico']==1){
					$this->salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;SI</td>";						
				}else{
					$this->salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$this->salida.= "</tr>";
				$this->salida.= "<tr>";					
				$this->salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_patologico']."</td>";						
				$this->salida.= "</tr>";
				$this->salida.= "</table>";
			}
			if($datos[$i]['descripcion_envio_cultivo']){
				$this->salida .="<table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida.= "<tr>";
				if($datos[$i]['envio_cultivo']==1){
					$this->salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;SI</td>";						
				}else{
					$this->salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$this->salida.= "</tr>";
				$this->salida.= "<tr>";					
				$this->salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_cultivo']."</td>";						
				$this->salida.= "</tr>";			
				$this->salida.= "</table>";			
			}
			$this->salida.= "<BR>";		
		}
		}else{
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA REPORTES DE HALLAZGOS QUIRURGICOS<br><br>";
			$this->salida .="</tr>";
			$this->salida .="</table>";
		}
		return true;
		
	}
	
	function frmHistoria()
	{
		$datos=$this->ConsultaNotasOperatoriasRealizadasHis();
		if($datos){
		for($i=0;$i<sizeof($datos);$i++){
			$salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
			$salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">NOTA OPERATORIA</td></tr>";
			(list($fechaIn,$horaIn)=explode(' ',$datos[$i]['hora_inicio']));
			(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
			(list($hhIn,$mmIn)=explode(':',$horaIn));				
			(list($fechaFn,$horaFn)=explode(' ',$datos[$i]['hora_fin']));				
			(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
			(list($hhFn,$mmFn)=explode(':',$horaFn));
			$segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
			$Horas=(int)($segundos/60);				
			$Minutos=($segundos%60);
			$salida .= "  <tr>";			
			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">FECHA INICIO</td>";
			$salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$fechaIn." ".$hhIn.":".$mmIn."</td>";				
			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">DURACION</td>";
			$salida .= "  <td width=\"15%\" class=\"hc_submodulo_list_oscuro\">".str_pad($Horas,2,0,STR_PAD_LEFT).":".str_pad($Minutos,2,0,STR_PAD_LEFT)."&nbsp;&nbsp;&nbsp;(HH:mm)</td>";
			$salida .= "  </tr>";
			$salida .= "  <tr>";
			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">QUIROFANO</td>";
			$salida .= "  <td align=\"left\" colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['nom_quirofano']."</td>";
			$salida .= "  </tr>";
			$salida .= "		<tr>";
			$salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">VIA ACCESO</td>";
			$salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['via']."</td>";		
			$salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO CIRUGIA</td>";
			$salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['tipo']."</td>";		
			$salida.= "     </tr>";
			$salida .= "		<tr>";
			$salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">AMBITO CIRUGIA</td>";
			$salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ambito']."</td>";
			$salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">FINALIDAD CIRUGIA</td>";
			$salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['finalidad']."</td>";
			$salida.= "     </tr>";		
			$salida.= "  </table>";		
      $salida.= "  <table border=\"1\" width=\"95%\" align=\"center\">";
      $salida.= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">PROFESIONALES</td></tr>";      
      $salida.= "  <tr>";      
      $salida.= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">ANESTESIOLOGO</td>";
      if($datos[$i]['anestesiologo']){     
        $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['anestesiologo']."</td>";       
      }else{
        $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }  
      $salida.= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">AYUDANTE</td>";      
      if($datos[$i]['ayudante']){     
        $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ayudante']."</td>";
      }else{
        $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      } 
      $salida.= "  </tr>";      
      $salida.= "    <tr>";      
      $salida.= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">INSTRUMENTADOR</td>";
      if($datos[$i]['instrumentador']){
        $salida.= "    <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['instrumentador']."</td>";    
      }else{
        $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }       
      $salida.= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">CIRCULANTE</td>";
      if($datos[$i]['circulante']){
        $salida.= "    <td class=\"hc_submodulo_list_oscuro\">".$datos[$i]['circulante']."</td>";   
      }else{
        $salida.= "  <td  class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }   
      $salida.= "     </tr>";      
      $salida.= "  </table>"; 	
			$salida.="	 <table  align=\"center\" border=\"1\" width=\"95%\">";
			$salida.="	 <tr class=\"modulo_table_title\">";
			$salida.="   <td align=\"center\" colspan=\"3\">PROCEDIMIENTOS REALIZADOS</td>";
			$salida.="	 </tr>";	
			$salida.="	 <tr class=\"hc_table_submodulo_list_title\">";			
			$salida.="  <td width=\"20%\">CARGO</td>";
			$salida.="  <td colspan=\"2\">DESCRIPCION</td>";			
			$salida.="	</tr>";
			$procedimientos=$this->ProcedimientosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id']);
			for($j=0;$j<sizeof($procedimientos);$j++){
				$salida.="<tr class=\"hc_submodulo_list_claro\">";				
				$salida.="  <td align=\"center\" width=\"20%\" rowspan=\"3\">".$procedimientos[$j]['procedimiento_qx']."</td>";
				$salida.="  <td align=\"left\" colspan=\"2\">".$procedimientos[$j]['descripcion']."</td>";											
				$salida.="</tr>";
         
        $procedimientosOpc=$this->BuscarProcedimientosInsertadosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']); 
        $salida.="<tr class=\"modulo_list_claro\"><td id=\"MostrarProcedimientoOpc\" colspan=\"5\">";    
        if($procedimientosOpc){         
          $salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
          $salida.="<tr class=\"modulo_table_list_title\">";
          $salida.="<td width=\"10%\">CODIGO</td>";
          $salida.="<td>PROCEDIMIENTO</td>";                
          $salida.="</tr>";        
          for($m=0;$m<sizeof($procedimientosOpc);$m++){
            $salida.="<tr class=\"modulo_list_oscuro\">";
            $salida.="<td width=\"20%\">".$procedimientosOpc[$m]['procedimiento_opcion']."</td>";
            $salida.="<td>".$procedimientosOpc[$m]['descripcion']."</td>";             
            $salida.="</tr>";
          }        
          $salida.="</table>";      
        }
        $salida.="</td></tr>";
        	
				$salida.="<tr class=\"$estilo\">";
				$salida.="  <td class=\"hc_table_submodulo_list_title\" colspan = 1 align=\"left\" width=\"10%\">Observacion</td>";
				$salida.="  <td class=\"hc_submodulo_list_claro\" align=\"left\" width=\"64%\">".$procedimientos[$j]['observaciones']."</td>";
				$salida.="</tr>";
				$salida.="<tr class=\"$estilo\">";
				$salida.="	<td class=\"hc_table_submodulo_list_title\" align=\"center\" width=\"10%\">Diagnosticos Pre-QX</td>";
				$salida.="	<td colspan=\"2\" class=\"hc_submodulo_list_claro\">";				
				$diag =$this->Diagnosticos_ProcedimientosNO($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']);
				if($diag){					
					$salida.="<table width=\"100%\" border=\"1\">";
					$salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$salida.="<td width=\"10%\">PRIMARIO</td>";
					$salida.="<td width=\"10%\">TIPO DX</td>";
					$salida.="<td width=\"8%\">CODIGO</td>";
					$salida.="<td width=\"60%\">DIAGNOSTICO</td>";					
					$salida.="</tr>";					
					for($m=0;$m<sizeof($diag);$m++){							
						$salida.="<tr class=\"$estilo\">";
						if($diag[$m]['sw_principal']=='1'){
							$salida.="<td align=\"center\" width=\"10%\">X</td>";
						}else{								
							$salida.="<td align=\"center\" width=\"10%\">&nbsp;</td>";
						}
						if($diag[$m]['tipo_diagnostico'] == '1'){
							$salida.="<td align=\"center\" width=\"10%\">ID</td>";
						}elseif($diag[$m]['tipo_diagnostico'] == '2'){
							$salida.="<td align=\"center\" width=\"10%\">CN</td>";
						}else{
							$salida.="<td align=\"center\" width=\"10%\">CR</td>";
						}
						$salida.="<td class=\"hc_submodulo_list_claro\" align=\"center\" width=\"8%\">".$diag[$m]['diagnostico_id']."</td>";
						$salida.="<td class=\"hc_submodulo_list_claro\" align=\"justify\" width=\"60%\">".$diag[$m]['diagnostico_nombre']."</td>";																					
						$salida.="<tr>";										
					}
					$salida.="</table>";					
				}				
			}
			$salida.="		</td></tr>";	
			$salida.= "		</table>";
			$salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
			$salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
			/*$salida .= "  <tr>";
			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">PRE QX</td>";
			$salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom2']."</td>";				
			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_pre_qx'] == '1'){
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_pre_qx'] == '2'){
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_pre_qx'] == '3'){
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}else{
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$salida .= "  </tr>";	*/
			$salida .= "  <tr>";
			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">POST QX</td>";
			$salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom']."</td>";				
			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_post_qx'] == '1'){
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">ID</td>";
			}elseif($datos[$i]['tipo_diagnostico_post_qx'] == '2'){
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">CN</td>";
			}elseif($datos[$i]['tipo_diagnostico_post_qx'] == '3'){
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">CR</td>";
			}else{
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$salida .= "  </tr>";			
			$salida .= "  <tr>";
			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">COMPLICACION</td>";
			$salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom1']."</td>";				
			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_complicacion'] == '1'){
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">ID</td>";
			}elseif($datos[$i]['tipo_diagnostico_complicacion'] == '2'){
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">CN</td>";
			}elseif($datos[$i]['tipo_diagnostico_complicacion'] == '3'){
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">CR</td>";
			}else{
				$salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";	
			}			
			$salida .= "  </tr>";			
			$salida.= "</table>";	
			if($datos[$i]['descripcion_envio_patologico']){
				$salida .="<table border=\"1\" width=\"95%\" align=\"center\">";
				$salida.= "<tr>";
				if($datos[$i]['envio_patologico']==1){
					$salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;SI</td>";						
				}else{
					$salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$salida.= "</tr>";
				$salida.= "<tr>";					
				$salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_patologico']."</td>";						
				$salida.= "</tr>";
				$salida.= "</table>";
			}
			if($datos[$i]['descripcion_envio_cultivo']){
				$salida .="<table border=\"1\" width=\"95%\" align=\"center\">";
				$salida.= "<tr>";
				if($datos[$i]['envio_cultivo']==1){
					$salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;SI</td>";						
				}else{
					$salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$salida.= "</tr>";
				$salida.= "<tr>";					
				$salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_cultivo']."</td>";						
				$salida.= "</tr>";			
				$salida.= "</table>";			
			}
			$salida.= "<BR>";		
		}
		}
		return $salida;
	}

	function BuscadorCups($tipoProcedimiento,$codigoPro,$descripcionPro){
		$this->salida  = ThemeAbrirTablaSubModulo('BUSQUEDA DE CARGOS CUPS');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'InsercionProcedimientosNota',"tipoProcedimiento".$this->frmPrefijo.""=>$tipoProcedimiento));
		$this->salida .= "  <form name='formauno".$this->frmPrefijo."' action=$accion method='post'>";
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">PROCEDIMIENTO</td></tr>";
		$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "  <td width=\"10%\">CODIGO</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"6\" type=\"text\" name=\"codigoPro".$this->frmPrefijo."\" value=\"$codigoPro\"></td>";
		$this->salida .= "  <td width=\"13%\">DESCRIPCION</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"60\" type=\"text\" name=\"descripcionPro".$this->frmPrefijo."\" value=\"$descripcionPro\"></td>";
		$this->salida .= "  <td width=\"10%\"><input type=\"submit\" name=\"buscarProc".$this->frmPrefijo."\" value=\"BUSCAR\"></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><BR>";
		$cargos=$this->RegistrosCargosCups($tipoProcedimiento,$codigoPro,$descripcionPro);
		if($cargos){
			$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\">";
			$this->salida .= "  <td>CODIGO</td>";
			$this->salida .= "  <td>DESCRIPCION</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($cargos);$i++){
				if($y % 2){$estilo='hc_submodulo_list_claro';}else{$estilo='hc_submodulo_list_oscuro';}
				$this->salida .= "  <tr class=\"$estilo\">";
				$this->salida .= "  <td width=\"15%\">".$cargos[$i]['cargo']."</td>";
				$this->salida .= "  <td>".$cargos[$i]['descripcion']."</td>";
				$this->salida .= "  <td align=\"center\" width=\"5%\">";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'InsercionProcedimientosNota',"cargo".$this->frmPrefijo.""=>$cargos[$i]['cargo'],"nombreProcedimiento".$this->frmPrefijo.""=>$cargos[$i]['descripcion'],
				"buscarProc".$this->frmPrefijo.""=>1,"bandera".$this->frmPrefijo.""=>1));
				$this->salida .= "  <a href=\"$accion\" class=\"link\"><b><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></b></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table><BR>";
			$this->salida .=$this->RetornarBarra(2);
		}
		$this->salida .= "  <BR><table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr><td class=\"input-submit\" align=\"center\"><input type=\"submit\" value=\"SALIR\" name=\"salir".$this->frmPrefijo."\"></td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
	
	function FrmConsultaNotasOperatoriasRealizadas($datos){
		
		$this->salida  = ThemeAbrirTablaSubModulo('NOTAS OPERATORIAS REALIZADAS');
		for($i=0;$i<sizeof($datos);$i++){
			$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DATOS DE LA CIRUGIA</td></tr>";
			(list($fechaIn,$horaIn)=explode(' ',$datos[$i]['hora_inicio']));
			(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
			(list($hhIn,$mmIn)=explode(':',$horaIn));				
			(list($fechaFn,$horaFn)=explode(' ',$datos[$i]['hora_fin']));				
			(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
			(list($hhFn,$mmFn)=explode(':',$horaFn));
			$segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
			$Horas=(int)($segundos/60);				
			$Minutos=($segundos%60);
			$this->salida .= "  <tr>";			
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">FECHA INICIO</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$fechaIn." ".$hhIn.":".$mmIn."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">DURACION</td>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_submodulo_list_oscuro\">".str_pad($Horas,2,0,STR_PAD_LEFT).":".str_pad($Minutos,2,0,STR_PAD_LEFT)."&nbsp;&nbsp;&nbsp;(HH:mm)</td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">QUIROFANO</td>";
			$this->salida .= "  <td align=\"left\" colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['nom_quirofano']."</td>";
			$this->salida .= "  </tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">VIA ACCESO</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['via']."</td>";		
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['tipo']."</td>";		
			$this->salida.= "     </tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">AMBITO CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ambito']."</td>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">FINALIDAD CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['finalidad']."</td>";
			$this->salida.= "     </tr>";		
			$this->salida.= "  </table>";	
      $this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">PROFESIONALES</td></tr>";      
      $this->salida .= "  <tr>";      
      $this->salida .= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">ANESTESIOLOGO</td>";
      if($datos[$i]['anestesiologo']){     
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['anestesiologo']."</td>";       
      }else{
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }  
      $this->salida .= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">AYUDANTE</td>";      
      if($datos[$i]['ayudante']){     
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ayudante']."</td>";
      }else{
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      } 
      $this->salida .= "  </tr>";      
      $this->salida .= "    <tr>";      
      $this->salida .= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">INSTRUMENTADOR</td>";
      if($datos[$i]['instrumentador']){
        $this->salida .= "    <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['instrumentador']."</td>";    
      }else{
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }       
      $this->salida .= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">CIRCULANTE</td>";
      if($datos[$i]['circulante']){
        $this->salida .= "    <td class=\"hc_submodulo_list_oscuro\">".$datos[$i]['circulante']."</td>";   
      }else{
        $this->salida .= "  <td  class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }   
      $this->salida.= "     </tr>";      
      $this->salida.= "  </table>"; 		
			$this->salida.="	 <table  align=\"center\" border=\"0\" width=\"95%\">";
			$this->salida.="	 <tr class=\"modulo_table_title\">";
			$this->salida.="   <td align=\"center\" colspan=\"3\">PROCEDIMIENTOS REALIZADOS</td>";
			$this->salida.="	 </tr>";	
			$this->salida.="	 <tr class=\"hc_table_submodulo_list_title\">";			
			$this->salida.="  <td width=\"20%\">CARGO</td>";
			$this->salida.="  <td colspan=\"2\">DESCRIPCION</td>";			
			$this->salida.="	</tr>";
			$procedimientos=$this->ProcedimientosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id']);
			for($j=0;$j<sizeof($procedimientos);$j++){
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";				
				$this->salida.="  <td align=\"center\" width=\"20%\" rowspan=\"3\">".$procedimientos[$j]['procedimiento_qx']."</td>";
				$this->salida.="  <td align=\"left\" colspan=\"2\">".$procedimientos[$j]['descripcion']."</td>";											
				$this->salida.="</tr>";	        
        
        $procedimientosOpc=$this->BuscarProcedimientosInsertadosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']); 
        $this->salida.="<tr class=\"modulo_list_claro\"><td id=\"MostrarProcedimientoOpc\" colspan=\"5\">";    
        if($procedimientosOpc){          
          $this->salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td width=\"10%\">CODIGO</td>";
          $this->salida.="<td>PROCEDIMIENTO</td>";                
          $this->salida.="</tr>";        
          for($m=0;$m<sizeof($procedimientosOpc);$m++){
            $this->salida.="<tr class=\"modulo_list_oscuro\">";
            $this->salida.="<td width=\"20%\">".$procedimientosOpc[$m]['procedimiento_opcion']."</td>";
            $this->salida.="<td>".$procedimientosOpc[$m]['descripcion']."</td>";             
            $this->salida.="</tr>";
          }        
          $this->salida.="</table>";      
        }
        $this->salida.="</td></tr>";
        
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td class=\"hc_table_submodulo_list_title\" colspan = 1 align=\"left\" width=\"10%\">Observacion</td>";
				$this->salida.="  <td class=\"hc_submodulo_list_claro\" align=\"left\" width=\"64%\">".$procedimientos[$j]['observaciones']."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="	<td class=\"hc_table_submodulo_list_title\" align=\"center\" width=\"10%\">Diagnosticos Pre-QX</td>";
				$this->salida.="	<td colspan=\"2\" class=\"hc_submodulo_list_claro\">";				
				$diag =$this->Diagnosticos_ProcedimientosNO($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']);
				if($diag){					
					$this->salida.="<table width=\"100%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"10%\">PRIMARIO</td>";
					$this->salida.="<td width=\"10%\">TIPO DX</td>";
					$this->salida.="<td width=\"8%\">CODIGO</td>";
					$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";					
					$this->salida.="</tr>";					
					for($m=0;$m<sizeof($diag);$m++){							
						$this->salida.="<tr class=\"$estilo\">";
						if($diag[$m]['sw_principal']=='1'){
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
						}else{								
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
						}
						if($diag[$m]['tipo_diagnostico'] == '1'){
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
						}elseif($diag[$m]['tipo_diagnostico'] == '2'){
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
						}else{
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
						}
						$this->salida.="<td class=\"hc_submodulo_list_claro\" align=\"center\" width=\"8%\">".$diag[$m]['diagnostico_id']."</td>";
						$this->salida.="<td class=\"hc_submodulo_list_claro\" align=\"justify\" width=\"60%\">".$diag[$m]['diagnostico_nombre']."</td>";																					
						$this->salida.="<tr>";										
					}
					$this->salida.="</table>";					
				}				
			}
			$this->salida.="		</td></tr>";	
			$this->salida.= "		</table>";
			$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
			/*$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">PRE QX</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom2']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_pre_qx'] == '1'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_pre_qx'] == '2'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_pre_qx'] == '3'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}else{
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";*/
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">POST QX</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_post_qx'] == '1'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_post_qx'] == '2'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_post_qx'] == '3'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}else{
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";			
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">COMPLICACION</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom1']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_complicacion'] == '1'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresiòn Diagnostica\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_complicacion'] == '2'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}elseif($datos[$i]['tipo_diagnostico_complicacion'] == '3'){
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}else{
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";			
			$this->salida.= "</table>";
			if($datos[$i]['descripcion_envio_patologico']){
				$this->salida .="<table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida.= "<tr>";
				if($datos[$i]['envio_patologico']==1){
					$this->salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;SI</td>";						
				}else{
					$this->salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$this->salida.= "</tr>";
				$this->salida.= "<tr>";					
				$this->salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_patologico']."</td>";						
				$this->salida.= "</tr>";
				$this->salida.= "</table>";
			}
			if($datos[$i]['descripcion_envio_cultivo']){
				$this->salida .="<table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida.= "<tr>";
				if($datos[$i]['envio_cultivo']==1){
					$this->salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;SI</td>";						
				}else{
					$this->salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$this->salida.= "</tr>";
				$this->salida.= "<tr>";					
				$this->salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_cultivo']."</td>";						
				$this->salida.= "</tr>";			
				$this->salida.= "</table>";			
			}
			$this->salida.= "<BR>";	
		}
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;	
	}

}

?>
