<?php
//ESTE ES EL QUE VA A QUEDAR
/**
* Submodulo de Reserva de Sangre.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_DatosPacienteAdicionales_HTML.php,v 1.6 2006/08/26 18:09:21 lorena Exp $
*/

class DatosPacienteAdicionales_HTML extends DatosPacienteAdicionales
{
    //clzc
	function DatosPacienteAdicionales_HTML()
	{
		$this->DatosPacienteAdicionales();//constructor del padre
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

/**
* frmForma - Esta es la funcion que llama la forma principal
*
* @return boolean
*/

//clzc
	function frmForma()
	{
	  $pfj=$this->frmPrefijo;
		if(empty($this->titulo)){
					$this->salida= ThemeAbrirTablaSubModulo('DATOS ADICIONALES DEL PACIENTE');
		}else{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		$this->salida.="<SCRIPT>";
    $this->salida.="function ponerFecha(frm,valor,valorFecha){";
    $this->salida.="  if(valor!=1){";
		$this->salida.="    frm.fecha_reserva.value=' ';";
		$this->salida.="  }else{";
    $this->salida.="    frm.fecha_reserva.value=valorFecha;";
		$this->salida.="  }";
		$this->salida.="}";
		$this->salida.="</SCRIPT>";
		$this->salida.="  <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
    $this->salida.="  </table>";

	/*	$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ConfirmarComponentes'));
		$this->salida.="<form name=\"forma\" action=\"$accion1\" method=\"post\">";
		$this->frmConsulta();
    $this->salida.="</form>";*/

    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ReservaComponentes'));
    $this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
    
    $mostrar=ReturnClassBuscador('proveedores','','','forma','');
    $this->salida .=$mostrar;
    $this->salida .="</script>\n";
    
		$this->salida.="<table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\" colspan=\"4\">INFORMACIÓN ADICIONAL DEL PACIENTE</td>";
		$this->salida.="</tr>";		
		$this->salida.="        <tr class = modulo_list_claro>";
		$this->salida.="        <td class=\"".$this->SetStyle("grupo_sanguineo")."\">GRUPO SANGUINEO </td>";
		$factorPaciente=$this->FactorPaciente();
		if($factorPaciente){
		  if($factorPaciente['rh']=='-'){
        $clase='label_error';
				$this->salida.="        <td class=\"".$this->SetStyle("grupo_sanguineo")."\">".$factorPaciente['grupo_sanguineo']." / <label class=\"$clase\">NEGATIVO</label></td>";
			}elseif($factorPaciente['rh']=='+'){
        $clase='label';
				$this->salida.="        <td class=\"".$this->SetStyle("grupo_sanguineo")."\">".$factorPaciente['grupo_sanguineo']." / <label class=\"$clase\">POSITIVO</label></td>";
			}else{
        $this->salida.="        <td class=\"".$this->SetStyle("grupo_sanguineo")."\">&nbsp;</td>";
			}
			$this->salida.="        <td colspan=\"2\"><input type=\"submit\" value=\"Modificar Factor\" name=\"ModificarFactor$pfj\" class=\"input-submit\"></td>";
		}else{
      $this->salida.="        <td>SIN REGISTRO</td>";
			$this->salida.="        <td colspan=\"2\"><input type=\"submit\" value=\"Seleccionar Factor\" name=\"SeleccionFactor$pfj\" class=\"input-submit\"></td>";
		}
    $this->salida.="        <input type=\"hidden\" name=\"grupo_sanguineo$pfj\" value=\"".$factorPaciente['grupo_sanguineo']."\">";
		$this->salida.="        <input type=\"hidden\" name=\"rh$pfj\" value=\"".$factorPaciente['rh']."\">";
		$this->salida.="        </tr>";
    
    $arreglox=$this->Get_datos_Adicionales();     
     
		
		$this->salida.="      <tr class =\"modulo_list_claro\">";
		$this->salida.="      <td align=\"left\" class=\"label\">DIRECCION DEL TRABAJO</td>";
		$this->salida.="      <td align=\"left\" colspan=\"3\">";
		$this->salida.="      <input type=\"text\" class=\"input-text\" name=\"dir_tra"."$pfj\" value='".$arreglox[direccion_trabajo]."' maxlength='60' size=\"50\">";
		$this->salida.="      </td>";
		$this->salida.="      </tr>";


		$this->salida.="      <tr class =\"modulo_list_claro\">";
		$this->salida.="      <td align=\"left\" class=\"label\">TELEFONO DEL TRABAJO</td>";
		$this->salida.="      <td align=\"left\" colspan=\"3\">";
		$this->salida.="      <input type=\"text\" class=\"input-text\" name=\"tel_tra"."$pfj\"  value='".$arreglox[telefono_trabajo]."' maxlength='30' size=\"30\">";
		$this->salida.="      </td>";
		$this->salida.="      </tr>";


		$this->salida.="        <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="        <td align=\"left\" colspan =\"4\">EN CASO DE ACCIDENTE AVISAR A :</td>";
		$this->salida.="        </tr>";


		$this->salida.="      <tr class =\"modulo_list_claro\">";
		$this->salida.="      <td align=\"left\" class=\"label\">NOMBRE</td>";
		$this->salida.="      <td align=\"left\" colspan=\"3\">";
		$this->salida.="      <input type=\"text\" class=\"input-text\" name=\"nom"."$pfj\" value='".$arreglox[nombre_aviso]."' maxlength='90' size=\"50\">";
		$this->salida.="      </td>";
		$this->salida.="      </tr>";


		$this->salida.="      <tr class =\"modulo_list_claro\">";
		$this->salida.="      <td align=\"left\" class=\"label\">TELEFONO</td>";
		$this->salida.="      <td align=\"left\" colspan=\"3\">";
		$this->salida.="      <input type=\"text\" class=\"input-text\" name=\"tel"."$pfj\" value='".$arreglox[telefono_aviso]."' maxlength='30' size=\"30\">";
		$this->salida.="      </td>";
		$this->salida.="        </tr>";

		$this->salida.="      <tr>";
		$this->salida.="        <td colspan=\"5\" align=\"center\"><br><input type=\"submit\" value=\"INSERTAR\" name=\"Guardar$pfj\" class=\"input-submit\"></td>";
		$this->salida.="        </tr>";


	  $this->salida.="        </table><BR>";
    $this->salida.="</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

/**
* RegistroFactorSanguineoPaciente - Esta es la funcion que llama la forma principal
*
* @return boolean
*/

  function RegistroFactorSanguineoPaciente($ModificarFactor,$SeleccionFactor,$grupo_sanguineo,$rh,$sw_urgencia,
		$fecha_reserva,$hora,$minutos,$embarazos_previos,$fecha_ultimo_embarazo,$estado_gestacion,$motivo_reserva,
		$confirmarR,$vector){
		$pfj=$this->frmPrefijo;
		foreach($vector as $nom=>$valor){
			$cad[$nom]=$valor;
		}
		$vect1=array("accion".$pfj=>'GuardarRegistroFactor',"ModificarFactor".$pfj=>$ModificarFactor,"SeleccionFactor".$pfj=>$SeleccionFactor,"grupo_sanguineo".$pfj=>$grupo_sanguineo,"rh".$pfj=>$rh,"sw_urgencia".$pfj=>$sw_urgencia,
		"fecha_reserva".$pfj=>$fecha_reserva,"hora".$pfj=>$hora,"minutos".$pfj=>$minutos,"embarazos_previos".$pfj=>$embarazos_previos,"fecha_ultimo_embarazo".$pfj=>$fecha_ultimo_embarazo,"estado_gestacion".$pfj=>$estado_gestacion,"motivo_reserva".$pfj=>$motivo_reserva,
		"confirmarR".$pfj=>$confirmarR);
		$vect=array_merge($cad,$vect1);
		$this->salida= ThemeAbrirTablaSubModulo('REGISTRO FACTOR SANGUINEO PACIENTE');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,$vect);
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
    $this->salida.="    <tr class = \"modulo_list_claro\">";
		$this->salida.="     <td align=\"left\" class=\"".$this->SetStyle("grupo_sanguineo")."\">GRUPO SANGUINEO</td>";
		$this->salida .= "   <td><select name=\"grupo_sanguineoReg$pfj\" class=\"select\">";
		$facts=$this->ConsultaFactor();
		$this->MostrasSelect($facts,'False',$_REQUEST["grupo_sanguineoReg".$pfj]);
		$this->salida .= "   </select></td>";
		$this->salida.="     <td align=\"left\" class=\"".$this->SetStyle("rh")."\">Rh </td>";
    $this->salida.="     <td align=\"left\">";
		$this->salida.="     <select size=\"1\" name =\"rh$pfj\" class =\"select\">";
		if($_REQUEST['rhReg'.$pfj]=='+'){
      $checkeado='selected';
		}elseif($_REQUEST['rhReg'.$pfj]=='-'){
      $checkeado1='selected';
		}
		$this->salida.="     <option value = -1>-Seleccione-</option>";
    $this->salida.="     <option value=\"+\" $checkeado> Positivo </option>";
		$this->salida.="     <option value=\"-\" $checkeado1> Negativo </option>";
    $this->salida.="     </select>";
		$this->salida.="     </td>";
    $this->salida.="     </tr>";
		$this->salida .="    <tr class = \"modulo_list_claro\">";
		if(!$_REQUEST['fecha_examen'.$pfj]){
      $_REQUEST['fecha_examen'.$pfj]=date('d-m-Y');
		}
		$this->salida .="    <td class=\"".$this->SetStyle("fecha_examen")."\" align=\"left\">FECHA DEL EXAMEN</td>";
		$this->salida .="    <td colspan=\"3\" align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=''  name=\"fecha_examen$pfj\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha_examen'.$pfj,'-')."</td>" ;
    $this->salida.="     </tr>";
		$this->salida .="    <tr class = \"modulo_list_claro\">";
		$this->salida.="     <td class=\"".$this->SetStyle("laboratorio")."\" align=\"left\">LABORATORIO</td>";
    $this->salida.="     <td colspan=\"3\" align=\"left\"><input type=\"text\" name=\"laboratorio$pfj\" value=\"".$_REQUEST["laboratorio".$pfj]."\" size=\"40\" class=\"input-submit\"></td>";
    $this->salida.="     </tr>";
		$bacteriologos=$this->TotalBacteriologos();
		if(is_array($bacteriologos))
		{
			$this->salida .="    <tr class = \"modulo_list_claro\">";
			$this->salida .= "   <td class=\"".$this->SetStyle("bacteriologo")."\">PROFESIONAL</td>";
			$this->salida .= "   <td colspan=\"3\"><select name=\"bacteriologo$pfj\" class=\"select\">";
			$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST["bacteriologo".$pfj]);
			$this->salida .= "    </select></td>";
			$this->salida.="     </tr>";
		}


		$this->salida .="    <tr class = \"modulo_list_claro\">";
    $this->salida.="     <td align=\"left\" colspan=\"4\"><b>OBSERVACIONES</b><br><textarea style=\"width:100%\" name=\"observaciones$pfj\" class=\"textarea\" rows=\"3\" cols=\"60\">".$_REQUEST["observaciones".$pfj]."</textarea></td>";
    $this->salida.="     </tr>";
		$this->salida.="     </table>";
    $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
    $this->salida.="     <tr><td align=\"center\">";
		$this->salida.="     <input type=\"submit\" name=\"Aceptar$pfj\" value=\"ACEPTAR\" class=\"input-submit\">";
		$this->salida.="     <input type=\"submit\" name=\"Cancelar$pfj\" value=\"CANCELAR\" class=\"input-submit\">";
		$this->salida.="     </td></tr>";
    $this->salida.="     </table>";
    $this->salida.="     </form>";
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
  

	function frmConsulta()
	{
		return true;
	}


	function frmHistoria()
	{

		return $salida;
	}

}

?>
